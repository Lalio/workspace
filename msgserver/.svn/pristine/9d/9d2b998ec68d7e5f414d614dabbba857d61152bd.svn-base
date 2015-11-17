package com.fcv.vms.server;

import org.apache.mina.core.session.IoSession;
import org.apache.mina.filter.codec.ProtocolCodecFactory;
import org.apache.mina.filter.codec.ProtocolDecoder;
import org.apache.mina.filter.codec.ProtocolEncoder;

public class MyProtocalCodecFactory implements ProtocolCodecFactory {
	private MyProtocalDecoder decoder;
	private MyProtocalEncoder encoder;
	
	public MyProtocalCodecFactory(){
		super();
		decoder=new MyProtocalDecoder();
		encoder=new MyProtocalEncoder();
	}
	
	public ProtocolDecoder getDecoder(IoSession arg0) throws Exception {
		// TODO Auto-generated method stub
		return decoder;
	}

	public ProtocolEncoder getEncoder(IoSession arg0) throws Exception {
		// TODO Auto-generated method stub
		return encoder;
	}

	public MyProtocalDecoder getDecoder() {
		return decoder;
	}

	public MyProtocalEncoder getEncoder() {
		return encoder;
	}

	public void setDecoder(MyProtocalDecoder decoder) {
		this.decoder = decoder;
	}

	public void setEncoder(MyProtocalEncoder encoder) {
		this.encoder = encoder;
	}

}
