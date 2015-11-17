package com.fcv.vms.server;

import java.nio.charset.Charset;


import org.apache.mina.core.buffer.IoBuffer;
import org.apache.mina.core.session.IoSession;
import org.apache.mina.filter.codec.ProtocolDecoder;
import org.apache.mina.filter.codec.ProtocolDecoderOutput;

public class MyProtocalDecoder implements ProtocolDecoder {
	//总长度
	private int length_data=128;
	private int flength=0;
	private int forder=0;
	private final int length_cmd_client=22;
	private final int length_cmd_http=32;
	private final int length_cmd_httpdata=256;
	//数据帧头
	private final int top_frame_data=51;
	private final int end_frame_data=-18;
	//命令帧头
	private final int top_frame_httpdata_cmd=54;
	private final int top_frame_client_cmd=52;
	private final int top_frame_http_cmd=53;
	private final int end_frame_cmd=-17;
	
	int low=0;
	int high=0;
	
	//内部命令规格
	
	private Charset charset;
	
	public MyProtocalDecoder(){
		charset=Charset.forName(common.CharsetString);
	}
	
	public void decode(IoSession session, IoBuffer in, ProtocolDecoderOutput out)
		throws Exception {
		
		IoBuffer buf=Context.getContext(session).getBuf();
		IoBuffer frame=Context.getContext(session).getFrame();
		//将上次长度不够留下的数据与本次接受的 数据整合
		if(buf.position()>0){
			IoBuffer temp=buf.getSlice(buf.remaining());
			buf.clear();
			buf.put(temp);
		}
		
		buf.put(in);
		buf.flip();
		//显示数据
		int top=0;				
		
		//循环判断
		while(buf.remaining()>3){
		    //循环读取并判读头帧是否是协议字节
			top=buf.get();					
			
			if(top==top_frame_data){//数据帧
				low=buf.get();				
				high=buf.get();
								
				length_data=(low&0xff)|(high<<8&0x3ff);
				//System.out.println("length_data"+length_data);
				flength=high>>2&0x7;
				forder=high>>5&0x7;
				
				if(low==76)//老协议（大运会的车）
				{
					System.out.println("老协议");
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
						 //长度不够，等待下次整合
						 buf.position(buf.position()-3);
						 return;
					 }
				}
				else //新协议
				{
					if(buf.remaining()>=length_data-3){
					   IoBuffer write=buf.getSlice(length_data-4);
										   
					   if(buf.get()!=end_frame_data)
					   {
					      buf.position(buf.position()-length_data+1);
					   }
					   else 
					   {			
						  if(flength==0)//可变长协议
						  {
							  //System.out.println("可变长协议");
						      out.write(write);
						  }
						  else //分组协议
						  {
								if(forder==1)//首帧
								{
									frame.position(0);
									//System.out.println("首帧位置1"+frame.position());
									frame.put(write.getSlice(length_data-25));
									frame.flip();
									//System.out.println("首帧位置2"+frame.position());
									//System.out.println("首帧"+frame);
								}
								else if(forder<flength)//中间帧
								{
									write.position(write.position()+19);
									frame.position(length_data-25);
									frame.position(frame.position()+(forder-2)*(length_data-44));
									//System.out.println("中间帧位置1"+frame.position()+"第"+forder+"帧");
									frame.put(write.getSlice(length_data-44));
									frame.flip();
									//System.out.println("中间帧位置2"+frame.position()+"第"+forder+"帧");
									frame.position(length_data-22);
									frame.position(frame.position()+(forder-2)*(length_data-44));
									//System.out.println("中间帧"+frame.getSlice(20));
								}
								else if(forder==flength)//尾帧
								{
									frame.position(0);
									frame.put(write.getSlice(19));							
									frame.position(length_data-25);
									frame.position(frame.position()+(forder-2)*(length_data-44));
									//System.out.println("尾帧位置1"+frame.position());
									frame.put(write.getSlice(length_data-23));
									frame.flip();
									//System.out.println("尾帧位置2"+frame.position());
									//System.out.println("尾帧"+frame);
									out.write(frame);
									
								}
						  }
					   }
					   
				     }
					 else
					 {
						//长度不够，等待下次整合
						buf.position(buf.position()-3);
						return;
					 }
				}
			}
			else if(top==top_frame_http_cmd)//HTTP页面的命令帧
			{	
				//System.out.println("Http CMD:"+buf);
				if(buf.remaining()>=length_cmd_http-1){
					buf.position(buf.position()-1);
					IoBuffer write=buf.getSlice(length_cmd_http-1);
						
					if(buf.get()==end_frame_cmd)//结尾验证通过发送
					{	
						out.write(write);
					}
					else//出现错误帧，清空缓冲，进行容错
					{
						buf.position(buf.position()-length_cmd_http+1);
					}
				}
				else{
					//长度不够，等待下次整合
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
						
					if(buf.get()==end_frame_cmd)//结尾验证通过发送
					{	
						out.write(write);
					}
					else//出现错误帧，清空缓冲，进行容错
					{
						buf.position(buf.position()-length_cmd_client+1);
					}
				}
				else{
					//长度不够，等待下次整合
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
						
					if(buf.get()==end_frame_cmd)//结尾验证通过发送
					{	
						out.write(write);
					}
					else//出现错误帧，清空缓冲，进行容错
					{
						buf.position(buf.position()-length_cmd_httpdata+1);
					}
				}
				else{
					//长度不够，等待下次整合
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
