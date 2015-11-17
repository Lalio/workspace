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
	 * ����������ִ�еģ�����ÿ������ֻ�ܴ�begin�÷�����ʼ��Ĭ�ϸ÷���Ϊ״̬ΪNULL�ſ���ִ��
	 */
	abstract public boolean begin();
	/**
	 * ��������������Ӧִ�ж���
	 * @param cmd ��Ҫִ�е�����
	 */
	abstract protected void execute(int cmd);
	/**
	 * ��������֡���õ���Ӧ��Ҫִ�е�����
	 * @param buf ΪͨѶ������������õ�������֡
	 */
	abstract protected void handler(IoBuffer buf); 
	/**
	 * ��������������ת��÷���
	 */
	abstract public void end();
	/**
	 * �����쳣����ת��÷���
	 */
	abstract public void error();	
	abstract public void error(String msg);
	/**
	 * �˳����񣬸�֪HTML�������
	 */
	abstract public void exit();
	/**
	 * @param _status ����״̬����״̬�ı�ʱ���ڴ˷�����������Ӧ����
	 */
	abstract protected void setStatus(int _status);
	/**
	 * @return ���ص�ǰ״̬������˵��
	 */
	abstract public String getStatus();
	/**
	 * @param _command �������
	 * �����������
	 */
	abstract protected void setCommand(int _command);
	
	//����������״̬����״̬�ʹ���״̬
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
