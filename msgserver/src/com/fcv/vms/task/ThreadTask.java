 package com.fcv.vms.task;

import org.apache.mina.core.buffer.IoBuffer;
import org.apache.mina.core.session.IoSession;

public class ThreadTask extends Task implements Runnable{
	
	protected int command=0;
	protected int status=0;
		
	private Object lock=new Object();
	
	protected IoSession target=null;
	protected IoSession source=null;
	
	public void createTask(IoSession target,IoSession source)
	{	
		this.target=target;
		this.source=source;
	}
	/**
	 * 任务是线性执行的，所以每个任务只能从begin该方法开始，默认该方法为状态为NULL才可以执行
	 */
	public boolean begin(){
		if(status==STATUS_NULL)
		{
			Thread t=new Thread(this);
			t.start();
			return true;
		}
		return false;
	}
	/**
	 * 根据命令做出相应执行动作
	 * @param cmd 需要执行的命令
	 */
	protected void execute(int cmd)
	{
		
	}
	/**
	 * 处理命令帧，得到相应需要执行的命令
	 * @param buf 为通讯服务器解析后得到的命令帧
	 */
	protected void handler(IoBuffer buf)
	{
		
	}
	
	/**
	 * 任务正常结束，转入该方法
	 * 设置session任务属性为空，并且设置isOver属性为true，从而运行的线程能结束
	 */
	@Override
	public void end() {		
		synchronized(target)		
		{
			isOver=true;
			setTask(target,null);	
		}
		print("任务结束！");
	}
	/**
	 * 任务异常出错,任务结束，并且退出
	 */
	@Override
	public void error() {
		error("任务异常退出");
	}
	@Override
	public void error(String msg) {		
		synchronized(target)		
		{
			isOver=true;
			setStatus(STATUS_ERROR);			
			setTask(target,null);	
		}	
		source.write(FlexMessageManager.getFlexErrorMessageFrame(msg, vin));
		print(msg);
	}
	/**
	 * 手动退出任务
	 */
	@Override
	public void exit() {
		source.write(FlexMessageManager.getFlexErrorMessageFrame("任务退出！", vin));
		synchronized(target)		
		{
			isOver=true;
			setTask(target,null);	
		}
		print("任务退出！");
	}
	/**
	 * @param _status 设置状态，即状态改变时可在此方法中做出相应处理
	 */
	protected void setStatus(int _status)
	{
		
	}
	
	/**
	 * @return 返回当前状态的文字说明
	 */
	public String getStatus()
	{
		return null;
	}
	
	/**
	 * @param _command 命令代码
	 * 设置命令代码
	 */
	@Override
	protected void setCommand(int _command)
	{
		synchronized(lock)
		{
			command=_command;
			lock.notifyAll();
		}
	}
	
	/* (non-Javadoc)
	 * @see java.lang.Thread#run()
	 * curCmd为记录前次命令代码，用于重发机制，默认为命令代码1，该命令代码为第一个命令，即开始状态的命令，
	 * 所以在设置命令代码的时候请把第一个开始命令赋值1，你也可以重写run方法
	 */
	protected boolean isOver=false;
	public void run() {
		command=1;
		execute(command);
		//记录重发数，重发20次后任务失败退出
		int times=0;
		int curCmd=1;
		while(true)
		{	
			synchronized(lock)
			{
				try {
					lock.wait(20000);
					if(isOver)
					{		
						break;
					}
					if(curCmd!=command)
					{
						execute(command);
						curCmd=command;
						times=0;
					}
					else
					{
						if(times>5)
						{
							error("车载端没有响应！任务退出！");
							break;
						}
						else
						{
							System.out.println("重发:");
							execute(command);
							times++;
						}
					}
				} 
				catch (InterruptedException e) {
					error("程序出错！");
					break;
				}			
			}
		}
	}

	
}
