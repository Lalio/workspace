package com.fcv.vms.task.download;

import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;
import java.util.Date;

import org.apache.mina.core.buffer.IoBuffer;
import org.apache.mina.core.session.IoSession;

import com.fcv.vms.task.FlexMessageManager;
import com.fcv.vms.task.Task;
import com.fcv.vms.task.ThreadTask;

/**
 * @author fcv
 *
 */
public class DownLoadTask extends ThreadTask{
	
	public DownLoadTask(IoSession target,IoSession source,String _vin,String _name){
		createTask(target,source);
		vin=_vin;
		file=_name;
		init();
	}	
	public int count=0;
	private int row=1;
	public String file;	
	//GCMD+V码+0x61+地址高位+地址低位+长度+（100个数据位）+校验码+0D+0A
	private byte[] data=new byte[128];
	//命令帧 GCMD+V码+命令码+0d+0a
	private byte[] frame=new byte[27];
	
	private byte[] filecache;	
	private byte filecheckcode;
	/**
	 * 
	 * @Variable check 该变量为固定部分的异或后的值，即GCMD+V码+0x60异或后的值，用于避免重复计算
	 * 
	 */	
	private byte check=0x0d;
	private void setCheckCode(){
		int c=check;
		for(int i=21;i<24;i++)
			c=c^frame[i];
		frame[24]=(byte) c;
	}
	
	private void init(){		
		try {
			FileCache fc=DownLoadManager.getFileCacheByName(file);
			filecache=fc.getCache();
			filecheckcode=fc.getCheckcode();
		} catch (IOException e) {
			error("文件不存在！");
			return;
		}	
		
		byte[] vb=vin.getBytes();
		synchronized(data)
		{
			data[0]='G';
			data[1]='C';
			data[2]='M';
			data[3]='D';		
			data[21]=0x60;	
			data[126]=0x0d;
			data[127]=0x0a;
				
			frame[0]='G';
			frame[1]='C';
			frame[2]='M';
			frame[3]='D';		
			frame[25]=0x0d;
			frame[26]=0x0a;
						
			System.arraycopy(vb, 0, data, 4, vb.length);
			System.arraycopy(vb, 0, frame, 4, vb.length);	
		}
		
		//固定部分异或校验，即GCMD头和V码，防止重复校验
		for(int i=0;i<vb.length;i++)
			check=(byte) (check^vb[i]);	
	}
	
	/**
	 * 读取下一行数据
	 */
	private int getNextRow() {	
		int size=filecache.length-count>100?100:filecache.length-count;		
		
		if(size<=0)
			return size;
		
		synchronized(data)
		{
			System.arraycopy(filecache, count, data, 25, size);
			
			data[22]=(byte) (count>>8);
			data[23]=(byte) (count&0x00ff);
			data[24]=(byte) size;
			
			count+=size;			
			//校验码
			int c=check;
			for(int j=21;j<125;j++)
				c=c^data[j];	
			data[125]=(byte) c;	
		}
		row++;
		return size;
	}	
	
	//服务器发送命令状态
	private static final int COMMAND_CONNECT=1;
	private static final int COMMAND_REMOVE=2;
	//因为每一次发送数据需要命令不同，所以用一个row参数+10来代表发送数据命令
	private static final int COMMAND_SENDNEXT=10;
	private static final int COMMAND_RESEND=4;
	private static final int COMMAND_SENDFINISH=5;
	//客户端发送的命令，服务器接收
	private static final int STATUS_CONNECTED=1;
	private static final int STATUS_REMOVED=2;
	private static final int STATUS_SENDDATA=3;
	private static final int STATUS_SENDFINISH=4;
	private static final int STATUS_RESTART_SUCCESS=5;
	private static final int STATUS_RESTART_ERROR=6;
	
	@Override
	protected void execute(int cmd) {	
		System.out.println("执行命令:"+cmd);
		switch(cmd)
		{
			case COMMAND_CONNECT:
			{
				/**
				 * 连接命令
				 * 格式:(27位)
				 * GCMD+V码+0x49+0x00+0x00+校验码+0D+0A
				 */
				frame[21]=0x49;
				frame[22]=0x00;
				frame[23]=0x00;
				setCheckCode();
				target.write(frame);
				return;
			}
			case COMMAND_REMOVE:
			{
				/**
				 * Flash擦除命令
				 * 格式:(27位)
				 * GCMD+V码+0x51+0x00+文件所有数据校验码+校验码+0D+0A
				 */
				frame[21]=0x51;	
				frame[22]=0x00;
				frame[23]=filecheckcode;
				setCheckCode();
				target.write(frame);
				return;
			}		
			case COMMAND_RESEND:
			{
				synchronized(data)
				{
					target.write(data);
				}
				print("重新发送第"+count+"数据 ");
				return;
			}
			case COMMAND_SENDFINISH:
			{
				/**
				 * 文件数据发送完毕命令
				 * 格式:(128位)
				 * GCMD+V码+0x61+文件长度高字节+文件长度低字节+补位0+校验码+0D+0A
				 */	
				synchronized(data)
				{
					data[21]=0x61;	
					int l=filecache.length;
					data[22]=(byte) (l>>8);
					data[23]=(byte) (l&0x00ff);
					for(int i=24;i<125;i++)
						data[i]=0;
					data[125]=(byte) (check^data[21]^data[22]^data[23]);
					target.write(data);
				}
				setStatus(STATUS_SENDFINISH);
				
				return;
			}
			default:
			{
				if((cmd-row)==COMMAND_SENDNEXT)
				{	
					if(getNextRow()>0)
					{	
						synchronized(data)
						{
							target.write(data);
						}
						source.write(FlexMessageManager.getFlexStatusMessageFrame(FlexMessageManager.DOWNLOAD_SIZE, vin, count));
						//print("发送第"+row+"行数据");
					}
					else
						execute(COMMAND_SENDFINISH);
					return;
				}
				else if((cmd-row+1)==COMMAND_SENDNEXT)
				{			
					synchronized(data)
					{
						target.write(data);	
					}
				}
			}
		}
	}
	
	@Override
	protected void handler(IoBuffer buf) {
		buf.position(buf.position()+17);
		int cmd=buf.get()&0xff;
		if(cmd==0x49 && getStatusNum()==STATUS_NULL)
		{	
			setStatus(STATUS_CONNECTED);
			setCommand(COMMAND_REMOVE);
		}
		else if(cmd==0xff)
		{
			int cmd2=buf.get()&0xff;			
			
			if(cmd2==0x55 && getStatusNum()==STATUS_SENDFINISH)
				setStatus(STATUS_RESTART_SUCCESS);
			else if(cmd2==0xaa)
			{
				int cmd3=buf.get()&0xff;
				if(cmd3==0x55 && getStatusNum()==STATUS_CONNECTED)
				{	
					setStatus(STATUS_REMOVED);
					setCommand(COMMAND_SENDNEXT+row);				
				}
				else if(cmd3==0x01)
				{
					if(getStatusNum()==STATUS_SENDDATA)
						setCommand(COMMAND_SENDNEXT+row);
					else if(getStatusNum()==COMMAND_REMOVE)
					{
						setStatus(STATUS_SENDDATA);
						setCommand(COMMAND_SENDNEXT+row);
					}
				}		
				else if(cmd3==0xbb && getStatusNum()==STATUS_SENDFINISH)
					setStatus(STATUS_RESTART_ERROR);
			}
			else
			{
				if(buf.get()==0x00 && getStatusNum()==STATUS_SENDDATA)
					setCommand(COMMAND_RESEND);
			}
		}
	}
	private int getStatusNum()
	{
		synchronized(statusLock)
		{
			return status;
		}
	}
	private Object statusLock=new Object();
	@Override
	protected void setStatus(int _status) {
		synchronized(statusLock)
		{
			if(status==STATUS_ERROR)
				return;
			status=_status;	
		
			switch(status)
			{
				case STATUS_CONNECTED:
				{
					source.write(FlexMessageManager.getFlexStatusMessageFrame(FlexMessageManager.DOWNLOAD_CONNECT, vin, filecache.length));
					print("下载文件任务开始，开始连接！");
					return;
				}
				case STATUS_REMOVED:
				{
					source.write(FlexMessageManager.getFlexStatusMessageFrame(FlexMessageManager.DOWNLOAD_REMOVE_FLASH, vin, 0));
					print("连接成功，发送擦除Flash命令！");	
					return;
				}
				case STATUS_SENDDATA:
				{
					//source.write(FlexMessageManager.getFlexStatusMessageFrame(FlexMessageManager.DOWNLOAD_SIZE, vin, count));
					print("开始发送数据！");
					return;
				}
				case STATUS_SENDFINISH:
				{
					source.write(FlexMessageManager.getFlexStatusMessageFrame(FlexMessageManager.DOWNLOAD_FINISH, vin, count));
					print("发送数据结束！");
					return;
				}
				case STATUS_RESTART_SUCCESS:
				{
					source.write(FlexMessageManager.getFlexStatusMessageFrame(FlexMessageManager.DOWNLOAD_RESTART, vin, count));
					print("客户端重启！");
					end();
					return;
				}
				case STATUS_RESTART_ERROR:
				{
					source.write(FlexMessageManager.getFlexStatusMessageFrame(FlexMessageManager.DOWNLOAD_ERROR, vin, count));
					print("客户端检测出错！");
					end();
					return;
				}
			}
		}
	}
	@Override
	public String getStatus() {
		switch(status)
		{
			case STATUS_NULL:return "未连接！";
			case STATUS_CONNECTED:return "已经连接！";
			case STATUS_REMOVED:return "FLASH已经擦除！";
			case STATUS_SENDDATA:return "已经发送"+count+"bit数据";
			case STATUS_SENDFINISH:return "文件发送完毕";
			case STATUS_RESTART_SUCCESS:return "车载的重启成功！任务完成！";
			case STATUS_RESTART_ERROR:return "车载的检测出错！任务失败！";
			case STATUS_ERROR:return "任务执行失败！";
		}
		return "状态出错！";
	}
}
