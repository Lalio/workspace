package com.fcv.vms.server;

import org.apache.mina.core.buffer.IoBuffer;
import org.apache.mina.core.session.IoSession;

public class Context {
	private IoBuffer buf;
	private IoBuffer frame;
	private int isFirst;

	private final static String Contxt="contxt";
	
	public static Context getContext(IoSession session){
		Context ctx=(Context)session.getAttribute(Contxt);
		if(ctx==null)
		{
			ctx=new Context();
			session.setAttribute(Contxt,ctx);
		}
		return ctx;
	}
	
	public Context(){
		buf=IoBuffer.allocate(2048);
        buf.setAutoExpand(true);
		buf.setAutoShrink(true);
		frame=IoBuffer.allocate(1024);
        frame.setAutoExpand(true);
		frame.setAutoShrink(true);
		setFirst(0);
	}
	 
	public void setBuf(IoBuffer buf) {
		this.buf = buf;
	}
	
	public IoBuffer getBuf() {
		return buf;
	}
	
	public void setFrame(IoBuffer frame) {
		this.frame = frame;
	}
	
	public IoBuffer getFrame() {
		return frame;
	}
	
	public void setFirst(int isFirst) {
		this.isFirst = isFirst;
	}
	
	public int isFirst() {
		return isFirst;
	}
}
