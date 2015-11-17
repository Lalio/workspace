package com.fcv.vms.task;

import org.apache.mina.core.buffer.IoBuffer;
import org.apache.mina.core.session.IoSession;

public abstract class Task {
	
	protected Integer command=0;
	protected Integer status=0;
	
	protected IoSession target=null;
	protected IoSession source=null;
	
	public String vin;
	
	public void createTask(IoSession target,IoSession source)
	{	
		this.target=target;
		this.source=source;
	}
	
	/**
	 * 任务是线性执行的，所以每个任务只能从begin该方法开始，默认该方法为状态为NULL才可以执行
	 */
	abstract public boolean begin();
	/**
	 * 根据命令做出相应执行动作
	 * @param cmd 需要执行的命令
	 */
	abstract protected void execute(int cmd);
	/**
	 * 处理命令帧，得到相应需要执行的命令
	 * @param buf 为通讯服务器解析后得到的命令帧
	 */
	abstract protected void handler(IoBuffer buf); 
	/**
	 * 任务正常结束，转入该方法
	 */
	abstract public void end();
	/**
	 * 任务异常出错，转入该方法
	 */
	abstract public void error();	
	abstract public void error(String msg);
	/**
	 * 退出任务，告知HTML任务结束
	 */
	abstract public void exit();
	/**
	 * @param _status 设置状态，即状态改变时可在此方法中做出相应处理
	 */
	abstract protected void setStatus(int _status);
	/**
	 * @return 返回当前状态的文字说明
	 */
	abstract public String getStatus();
	/**
	 * @param _command 命令代码
	 * 设置命令代码
	 */
	abstract protected void setCommand(int _command);
	
	//基本的两个状态，无状态和错误状态
	protected static final int STATUS_NULL=0;
	protected static final int STATUS_ERROR=-1;
	
	public static String TASK="task";
	
	protected void print(String msg)
	{
		System.out.println("VIN:"+vin+"----"+msg+"----");
	}
	
	public static Task getTask(IoSession session){
		return (Task) session.getAttribute(TASK);
	}
	public static void setTask(IoSession session,Task task){
		session.setAttribute(TASK,task);
	}
	public static void executeTask(IoSession session,IoBuffer buf){
		Task task=getTask(session);
		if(task!=null)
			task.handler(buf);
	}
}
