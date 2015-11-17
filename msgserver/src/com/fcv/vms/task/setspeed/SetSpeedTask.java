package com.fcv.vms.task.setspeed;

import org.apache.mina.core.buffer.IoBuffer;
import org.apache.mina.core.session.IoSession;

import com.fcv.vms.task.FlexMessageManager;
import com.fcv.vms.task.Task;
import com.fcv.vms.task.ThreadTask;

public class SetSpeedTask extends ThreadTask{
	
	public int speed=0;
	
	public SetSpeedTask(IoSession target,IoSession source,int _speed,String _vin){
		super.createTask(target, source);
		speed=_speed;
		vin=_vin;
		init();
	}
	
	private byte[] frame=new byte[27];
	private byte check=0x0d;
	private void setCheckCode(){
		int c=check;
		for(int i=21;i<24;i++)
			c=c^frame[i];
		frame[24]=(byte) c;
	}
	private void init()
	{				
		frame[0]='G';
		frame[1]='C';
		frame[2]='M';
		frame[3]='D';
		
		frame[25]=0x0d;
		frame[26]=0x0a;
		
		byte[] vb=vin.getBytes();
		//设置V码
		System.arraycopy(vb, 0, frame, 4, vb.length);
		
		for(int i=0;i<vb.length;i++)
			check=(byte) (check^vb[i]);
	}
	
	//服务器发送命令状态
	private static final int COMMAND_CONNECT=1;
	private static final int COMMAND_SETSPEED=2;
	//客户端发送的命令，服务器接收
	private static final int STATUS_CONNECTED=1;
	private static final int STATUS_FINISH=2;
	@Override
	protected void execute(int cmd) {
		switch(cmd)
		{
			case COMMAND_CONNECT:
			{
				/**
				 * 格式:(27位)
				 * GCMD+V码+0x49+0x00+0x00+校验码+0D+0A
				 */				
				frame[21]=0x49;
				frame[22]=0x00;
				frame[23]=0x00;
				//设置校验码
				setCheckCode();				
				target.write(frame);	
				return;
			}
			case COMMAND_SETSPEED:
			{
				/**
				 * 格式:(27位)
				 * GCMD+V码+0x50+0x00+0x00+校验码+0D+0A
				 */
				frame[21]=0x50;
				frame[22]=(byte) speed;
				setCheckCode();
				target.write(frame);
				return;
			}
		}
	}
	
	@Override
	protected void handler(IoBuffer buf) {
		buf.position(buf.position()+17);
		int cmd=buf.get()&0xff;	
		switch(cmd)
		{
			case 0x49:
			{
				if(getStatusNum()==STATUS_NULL)			
				{				
					setCommand(COMMAND_SETSPEED);
					setStatus(STATUS_CONNECTED);
				}
				return;
			}
			case 0x50:
			{
				if(getStatusNum()==STATUS_CONNECTED)
				{
					setStatus(STATUS_FINISH);		
				}
				return;
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
					source.write(FlexMessageManager.getFlexStatusMessageFrame(FlexMessageManager.SETSPEED_CONNECT_SUCCESS, vin, 0));
					print("连接成功，发送设置速率的命令！");
					return;
				}
				case STATUS_FINISH:
				{
					source.write(FlexMessageManager.getFlexStatusMessageFrame(FlexMessageManager.SETSPEED_FINISH_SUCCESS, vin, 0));
					print("设置速率成功！");
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
			case STATUS_NULL:return "未连接";
			case STATUS_CONNECTED:return "已经连接！";
			case STATUS_FINISH:return "设置速率完成，任务结束！";
			case STATUS_ERROR:return "任务执行失败！";
		}
		return "状态错误！";
	}
}
