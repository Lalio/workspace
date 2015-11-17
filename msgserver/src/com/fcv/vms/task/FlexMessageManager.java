package com.fcv.vms.task;

public class FlexMessageManager {
	public static byte[] getFlexErrorMessageFrame(String error,String vin){
		byte[] eb=error.getBytes();
		
		int length=4+eb.length+17;	
		byte[] frame=new byte[length];
		
		if(vin!=null&&vin.length()==17)
		{
			byte[] vb=vin.getBytes();
			System.arraycopy(vb, 0, frame, 3, vb.length);
		}
							
		System.arraycopy(eb, 0, frame, 20, eb.length);
				
		frame[0]=0x37;
		frame[1]=(byte) (length>>8);
		frame[2]=(byte) (length&0x00ff);
		
		frame[frame.length-1]=(byte) 0xef;
		return frame;
	}
	
	private static final ThreadLocal<byte[]> FlexFrameModel=new ThreadLocal<byte[]>(){
		public byte[] initialValue(){
				byte[] frame=new byte[25];
				frame[0]=0x35;						
				frame[24]=(byte) 0xef;
				return frame;
		} 		
	};
	
	public static final int SETSPEED_CONNECT_SUCCESS=31;
	public static final int SETSPEED_FINISH_SUCCESS=32;
	
	public static final int DOWNLOAD_CONNECT=21;
	public static final int DOWNLOAD_REMOVE_FLASH=22;
	public static final int DOWNLOAD_SIZE=23;
	public static final int DOWNLOAD_FINISH=24;
	public static final int DOWNLOAD_RESTART=25;
	public static final int DOWNLOAD_ERROR=26;
	
	public static final int UPLOAD_SIZE=41;
	public static final int UPLOAD_SUCCESS=42;
	public static final int UPLOAD_WRONG=43;
	
	public static byte[] getFlexStatusMessageFrame(int cmd,String vin,long size){
		byte[] frame=FlexFrameModel.get();
		byte[] vb=vin.getBytes();
		//ÉèÖÃVÂë
		System.arraycopy(vb, 0, frame, 2, vb.length);

		switch(cmd)
		{
			case DOWNLOAD_CONNECT:
			{
				frame[1]=0x52;
				frame[19]=0x10;
				
				frame[20]=(byte) (size>>24);
				frame[21]=(byte) (size>>16&0x00ff);
				frame[22]=(byte) (size>>8&0x0000ff);
				frame[23]=(byte) (size&0x000000ff);
				return frame;
			}
			case DOWNLOAD_REMOVE_FLASH:
			{
				frame[1]=0x52;
				frame[19]=0x11;
				return frame;
			}
			case DOWNLOAD_SIZE:
			{
				frame[1]=0x52;
				frame[19]=0x12;
				
				frame[20]=(byte) (size>>24);
				frame[21]=(byte) (size>>16&0x00ff);
				frame[22]=(byte) (size>>8&0x0000ff);
				frame[23]=(byte) (size&0x000000ff);
				return frame;
			}
			case DOWNLOAD_FINISH:
			{
				frame[1]=0x52;
				frame[19]=0x18;
				return frame;
			}
			case DOWNLOAD_RESTART:
			{
				frame[1]=0x52;
				frame[19]=0x19;
				return frame;
			}
			case DOWNLOAD_ERROR:
			{
				frame[1]=0x52;
				frame[19]=0x20;
				return frame;
			}
			case SETSPEED_CONNECT_SUCCESS:
			{
				frame[1]=0x50;
				frame[19]=0x10;
				return frame;
			}
			case SETSPEED_FINISH_SUCCESS:
			{
				frame[1]=0x50;
				frame[19]=0x11;
				return frame;
			}
			case UPLOAD_SIZE:
			{
				frame[1]=0x53;
				frame[2]=0x10;
				frame[3]=(byte) (size>>24);
				frame[4]=(byte) (size>>16&0x00ff);
				frame[5]=(byte) (size>>8&0x0000ff);
				frame[6]=(byte) (size&0x000000ff);
				return frame;
			}
			case UPLOAD_SUCCESS:
			{
				frame[1]=0x53;
				frame[2]=0x20;
				return frame;
			}
			case UPLOAD_WRONG:
			{
				frame[1]=0x53;
				frame[2]=0x21;
				return frame;
			}
			default:
				return null;		
		}
		
	}
}
