package com.fcv.vms.task.upload;

import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;

import org.apache.mina.core.buffer.IoBuffer;
import org.apache.mina.core.session.IoSession;

import com.fcv.vms.task.FlexMessageManager;
import com.fcv.vms.task.Task;
import com.fcv.vms.task.file.FileManager;

public class UpLoadTask extends Task {

	public String file;	
	private FileOutputStream output;
	public String name;
	private String cachefile;
	
	public UpLoadTask(IoSession _target,String filename)
	{
		target=_target;
		//用s表示
		name=filename;
		file=FileManager.getPath(filename);
		//上传文件的特定V码
		vin="#################";
	}
	private byte[] data=new byte[256];
	private int count=0;
	@Override
	public boolean begin() {	
		File f=new File(file);
		if(f.exists())
		{
			error("文件已经存在！");
			return false;
		}
		else
		{
			int i=0;
			while(true){
				cachefile=file+i;
				File nf=new File(cachefile);
				if(nf.exists())
					i++;
				else
					break;
			}
			
			try {
				output=new FileOutputStream(cachefile);
			} catch (FileNotFoundException e) {
				error("文件写入出错！");
				return false;
			}
		}
		target.write(FlexMessageManager.getFlexStatusMessageFrame(FlexMessageManager.UPLOAD_SIZE, vin, count));
		return true;
	}
	@Override
	public void end() {
		synchronized(target)		
		{
			setTask(target,null);
		}
		if(output!=null)
		{
			try {
				output.close();
			} catch (IOException e) {
				e.printStackTrace();
			}
			/**
			 * 防止多个任务传同一文件导致的错误，这里首先采用一个缓存文件名
			 * 上传完后，检测是否目标文件名存在，不在则改写文件名
			 */
			File old=new File(cachefile);
			if(old.exists())
			{
				File newFile=new File(file);
				if(!newFile.exists())
				{	
					if(old.renameTo(newFile))
					{
						target.write(FlexMessageManager.getFlexStatusMessageFrame(FlexMessageManager.UPLOAD_SUCCESS, vin, 0));
						FileManager.addFile(name);
						return;
					}
				}
			}		
			target.write(FlexMessageManager.getFlexStatusMessageFrame(FlexMessageManager.UPLOAD_WRONG, vin, 0));
		}
	}
	@Override
	public void error() {
		error("任务异常退出！");
	}
	@Override
	public void error(String msg) {
		synchronized(target)		
		{
			setTask(target,null);	
		}
		if(output!=null)
			try {
				output.close();
			} catch (IOException e) {
				e.printStackTrace();
			}
		target.write(FlexMessageManager.getFlexErrorMessageFrame(msg,vin));
	}
	
	@Override
	protected void handler(IoBuffer buf) {
		int cmd=buf.get()&0xff;
		if(cmd==0x36)
		{
			if((buf.get()&0xff)==0x53)
			{
				int size=buf.get()&0xff;
				try{				
					buf.get(data, 0, size);
					output.write(data, 0, size);
					
					count+=size;
					target.write(FlexMessageManager.getFlexStatusMessageFrame(FlexMessageManager.UPLOAD_SIZE, vin, count));
				}
				catch(IOException e)
				{
					error("文件写入出错！");
				}
			}
			
		}	
	}
	
	@Override
	protected void setCommand(int _command) {
		// TODO Auto-generated method stub
		
	}
	@Override
	protected void setStatus(int _status) {
		// TODO Auto-generated method stub
		
	}
	@Override
	protected void execute(int cmd) {
		// TODO Auto-generated method stub
	}
	@Override
	public String getStatus() {
		// TODO Auto-generated method stub
		return "";
	}
	@Override
	public void exit() {
		synchronized(target)		
		{
			setTask(target,null);	
		}
		target.write(FlexMessageManager.getFlexErrorMessageFrame("任务退出！",vin));	
		print("任务退出！");
	}
	
}
