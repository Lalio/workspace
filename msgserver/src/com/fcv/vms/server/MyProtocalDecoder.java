package com.fcv.vms.server;

import java.nio.charset.Charset;


import org.apache.mina.core.buffer.IoBuffer;
import org.apache.mina.core.session.IoSession;
import org.apache.mina.filter.codec.ProtocolDecoder;
import org.apache.mina.filter.codec.ProtocolDecoderOutput;

public class MyProtocalDecoder implements ProtocolDecoder {
	//�ܳ���
	private int length_data=128;
	private int flength=0;
	private int forder=0;
	private final int length_cmd_client=22;
	private final int length_cmd_http=32;
	private final int length_cmd_httpdata=256;
	//����֡ͷ
	private final int top_frame_data=51;
	private final int end_frame_data=-18;
	//����֡ͷ
	private final int top_frame_httpdata_cmd=54;
	private final int top_frame_client_cmd=52;
	private final int top_frame_http_cmd=53;
	private final int end_frame_cmd=-17;
	
	int low=0;
	int high=0;
	
	//�ڲ�������
	
	private Charset charset;
	
	public MyProtocalDecoder(){
		charset=Charset.forName(common.CharsetString);
	}
	
	public void decode(IoSession session, IoBuffer in, ProtocolDecoderOutput out)
		throws Exception {
		
		IoBuffer buf=Context.getContext(session).getBuf();
		IoBuffer frame=Context.getContext(session).getFrame();
		//���ϴγ��Ȳ������µ������뱾�ν��ܵ� ��������
		if(buf.position()>0){
			IoBuffer temp=buf.getSlice(buf.remaining());
			buf.clear();
			buf.put(temp);
		}
		
		buf.put(in);
		buf.flip();
		//��ʾ����
		int top=0;				
		
		//ѭ���ж�
		while(buf.remaining()>3){
		    //ѭ����ȡ���ж�ͷ֡�Ƿ���Э���ֽ�
			top=buf.get();					
			
			if(top==top_frame_data){//����֡
				low=buf.get();				
				high=buf.get();
								
				length_data=(low&0xff)|(high<<8&0x3ff);
				//System.out.println("length_data"+length_data);
				flength=high>>2&0x7;
				forder=high>>5&0x7;
				
				if(low==76)//��Э�飨���˻�ĳ���
				{
					System.out.println("��Э��");
					length_data=128;
					buf.position(buf.position()-2);
				    if(buf.remaining()>=length_data-1){
					   IoBuffer write=buf.getSlice(length_data-2);
			
					   if(buf.get()!=end_frame_data)
					   {
					 	  byte end;
					      buf.position(buf.position()-length_data);
					      end=buf.get();
					      for(int i=0;i<length_data-2;i++)
					      {
					    	  end=(byte)((int)(buf.get())^(int)(end));
					      }
					      
					      if(buf.get()==end){
					    	  out.write(write);
					      }  			    	  
					   }
					   else 
						  out.write(write);
				     }
					 else{
						 //���Ȳ������ȴ��´�����
						 buf.position(buf.position()-3);
						 return;
					 }
				}
				else //��Э��
				{
					if(buf.remaining()>=length_data-3){
					   IoBuffer write=buf.getSlice(length_data-4);
										   
					   if(buf.get()!=end_frame_data)
					   {
					      buf.position(buf.position()-length_data+1);
					   }
					   else 
					   {			
						  if(flength==0)//�ɱ䳤Э��
						  {
							  //System.out.println("�ɱ䳤Э��");
						      out.write(write);
						  }
						  else //����Э��
						  {
								if(forder==1)//��֡
								{
									frame.position(0);
									//System.out.println("��֡λ��1"+frame.position());
									frame.put(write.getSlice(length_data-25));
									frame.flip();
									//System.out.println("��֡λ��2"+frame.position());
									//System.out.println("��֡"+frame);
								}
								else if(forder<flength)//�м�֡
								{
									write.position(write.position()+19);
									frame.position(length_data-25);
									frame.position(frame.position()+(forder-2)*(length_data-44));
									//System.out.println("�м�֡λ��1"+frame.position()+"��"+forder+"֡");
									frame.put(write.getSlice(length_data-44));
									frame.flip();
									//System.out.println("�м�֡λ��2"+frame.position()+"��"+forder+"֡");
									frame.position(length_data-22);
									frame.position(frame.position()+(forder-2)*(length_data-44));
									//System.out.println("�м�֡"+frame.getSlice(20));
								}
								else if(forder==flength)//β֡
								{
									frame.position(0);
									frame.put(write.getSlice(19));							
									frame.position(length_data-25);
									frame.position(frame.position()+(forder-2)*(length_data-44));
									//System.out.println("β֡λ��1"+frame.position());
									frame.put(write.getSlice(length_data-23));
									frame.flip();
									//System.out.println("β֡λ��2"+frame.position());
									//System.out.println("β֡"+frame);
									out.write(frame);
									
								}
						  }
					   }
					   
				     }
					 else
					 {
						//���Ȳ������ȴ��´�����
						buf.position(buf.position()-3);
						return;
					 }
				}
			}
			else if(top==top_frame_http_cmd)//HTTPҳ�������֡
			{	
				//System.out.println("Http CMD:"+buf);
				if(buf.remaining()>=length_cmd_http-1){
					buf.position(buf.position()-1);
					IoBuffer write=buf.getSlice(length_cmd_http-1);
						
					if(buf.get()==end_frame_cmd)//��β��֤ͨ������
					{	
						out.write(write);
					}
					else//���ִ���֡����ջ��壬�����ݴ�
					{
						buf.position(buf.position()-length_cmd_http+1);
					}
				}
				else{
					//���Ȳ������ȴ��´�����
					buf.position(buf.position()-1);
					return;	
				}
			}
			else if(top==top_frame_client_cmd)
			{
				//buf.position(buf.position()+17);
				//System.out.println("Client:"+buf);
				//buf.position(buf.position()-17);
				if(buf.remaining()>=length_cmd_client-1){
					buf.position(buf.position()-1);
					IoBuffer write=buf.getSlice(length_cmd_client-1);
						
					if(buf.get()==end_frame_cmd)//��β��֤ͨ������
					{	
						out.write(write);
					}
					else//���ִ���֡����ջ��壬�����ݴ�
					{
						buf.position(buf.position()-length_cmd_client+1);
					}
				}
				else{
					//���Ȳ������ȴ��´�����
					buf.position(buf.position()-1);
					return;	
				}
			}
			else if(top==top_frame_httpdata_cmd)
			{
				//System.out.println("Http Data:"+buf);
				if(buf.remaining()>=length_cmd_httpdata-1){
					buf.position(buf.position()-1);
					IoBuffer write=buf.getSlice(length_cmd_httpdata-1);
						
					if(buf.get()==end_frame_cmd)//��β��֤ͨ������
					{	
						out.write(write);
					}
					else//���ִ���֡����ջ��壬�����ݴ�
					{
						buf.position(buf.position()-length_cmd_httpdata+1);
					}
				}
				else{
					//���Ȳ������ȴ��´�����
					buf.position(buf.position()-1);
					return;	
				}
			}
				
		}
	}

	public void dispose(IoSession arg0) throws Exception {
	}

	public void finishDecode(IoSession arg0, ProtocolDecoderOutput arg1)
			throws Exception {
	}
}