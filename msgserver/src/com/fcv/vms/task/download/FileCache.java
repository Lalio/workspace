package com.fcv.vms.task.download;

import java.io.FileInputStream;
import java.io.IOException;

import com.fcv.vms.task.file.FileManager;

public class FileCache {
	private String name;
	public FileCache(String _name)
	{
		name=_name;
	}
	
	private byte[] cache;
	private Object lock=new Object();
	public byte[] getCache() throws IOException {
		synchronized(lock)
		{
			if(cache==null)
				readFileToCache();
		}
		
		if(cache==null)
			throw new IOException();
		return cache;
	}
	
	private byte checkcode;
	public byte getCheckcode() {
		return checkcode;
	}
	
	private void readFileToCache() throws IOException
	{
		String path=FileManager.getPath(name);
		FileInputStream fis=new FileInputStream(path);
		byte[] cache = new byte[fis.available()];
		int check=0;
		if(fis.available()>0)
		{
			int i=0;
			check=fis.read();
			cache[i++]=(byte) check;
			while(fis.available()>0)
			{
				int b=fis.read();
				check=check^b;
				cache[i++]=(byte) b;
			}
		}	
		
		this.cache=cache;
		this.checkcode=(byte) check;
	}
	
}
