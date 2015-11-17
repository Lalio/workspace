package com.fcv.vms.server;

import java.nio.charset.Charset;

import org.apache.mina.core.buffer.IoBuffer;
import org.apache.mina.core.session.IoSession;
import org.apache.mina.filter.codec.ProtocolEncoder;
import org.apache.mina.filter.codec.ProtocolEncoderOutput;

public class MyProtocalEncoder implements ProtocolEncoder {
	private Charset charset;

	public MyProtocalEncoder(){
		charset=Charset.forName(common.CharsetString);
	}
	
	public void dispose(IoSession session) throws Exception {
		
	}
	
	public void encode(IoSession session, Object message, ProtocolEncoderOutput out)
	{	
		if(message!=null)
		{
			//byte[] msg=(byte[])message;	
			//IoBuffer write=IoBuffer.allocate(msg.length);
			IoBuffer write=(IoBuffer)message;
			//write.putInt(60);
			write.flip();
			out.write(write);	

		}
	}
	
	//�õ���Ӧǰ�ͺ�ߵ��ֽ�
	/*private byte[] changeToByte(int i){
		byte[] bs=new byte[2];
		if(i==512)
		{
			bs[0]=0x00;
			bs[1]=0x02;
		}
		else{
			bs[0]=(byte) (i%512);
			bs[1]=(byte)(int) (i/256);
		}
		return bs;
	}
	//���õ�������
	private byte getInspectByte(byte[] bs){
		byte b=bs[0];
		for(int i=1;i<bs.length;i++)
			b=(byte) (b^bs[i]);
		return b;
	}*/
}