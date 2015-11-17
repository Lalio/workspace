package com.fcv.vms.task.file;

import java.io.File;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;

public class FileManager {
	public static String DefaultProgramFilePath=System.getProperty("user.dir")+"/download/";
	private static char Delimiter='|';
	
	private static List<String> downloadFiles=new ArrayList<String>();
	private static StringBuilder sb=null; 
	private static byte[] frame=null;
	
	private static Date UpdatedTime=null;
	
	private static Object lock=new Object();
	
	
	
	public static byte[] getProgramFiles()
	{
		synchronized(lock)
		{
			if(UpdatedTime==null)
			{
				refreshProgramFiles();
				UpdatedTime=new Date();
			}
			else
			{
				Date now=new Date();
				if(now.getTime()-UpdatedTime.getTime()>180000)
				{
					refreshProgramFiles();
					UpdatedTime=now;
					return frame;
				}
			}
			return frame;
		}		
	}
	
	/**
	 * 
	 * ���ͷ����ӵ�еĳ����ļ��嵥��Flex Socket��Э���ڴ�Ϊ
	 * 
	 * 0x36+���ȸ��ֽ�+���ȵ��ֽ�+0x51(�����ȡ�ļ��嵥������)+�ļ�1+�ָ���+�ļ�2...+0xef(������)
	 * 
	 * @return
	 */
	private static void refreshProgramFiles()
	{
		File file=new File(DefaultProgramFilePath);
		File[] files=file.listFiles();
		sb=new StringBuilder();
		downloadFiles.clear();
		for(File f:files)
		{
			if(f.isFile())
			{
				//sb.append(f.getName()).append(Delimiter);
				downloadFiles.add(f.getName());
			}
		}
		
		toByte();
	}
	
	private static void toByte()
	{
		if(sb.length()>0)
		{
			byte[] context=sb.toString().getBytes();
			frame=new byte[5+context.length-1];
			
			int length=context.length+4;
			
			frame[0]=0x36;
			frame[1]=(byte) (length>>8);
			frame[2]=(byte) (length&0x00ff);
			frame[3]=0x51;
			frame[frame.length-1]=(byte) 0xef;
			System.arraycopy(context, 0, frame, 4, context.length-1);	
		}
		else
		{
			frame=new byte[5];
			
			frame[0]=0x36;
			frame[1]=0x00;
			frame[2]=0x05;
			frame[3]=0x51;
			frame[4]=(byte) 0xef;
	
		}
	}
	
	
	public static void addFile(String file){
		synchronized(lock)
		{
			if(sb==null)
			{
				refreshProgramFiles();
				UpdatedTime=new Date();
			}
			
			//sb.append(file).append(Delimiter);
			toByte();
		}
	}
	
	public static boolean exists(String file){
		for(String name:downloadFiles)
		{
			if(name.equals(file))
				return true;			
		}
		return false;
	}
	/**
	 * 
	 * ����ָ���ʽ��
	 * 0x35+0x52+V��+�ļ����ֳ���+�ļ���+0xef
	 * 
	 * @param buf Ϊ�����ļ������ȴ���IoBuffer����������Ҫ������������ݵõ��ļ�
	 * @return
	 */
	public static String getPath(String name){
		return DefaultProgramFilePath+name;
	}
}
