package com.fcv.vms.task.download;

import java.util.HashMap;
import java.util.Map;

public class DownLoadManager {	
	private static Map<String,FileCache> FileCacheMap=new HashMap<String,FileCache>();
		
	public static FileCache getFileCacheByName(String name)
	{
		synchronized(FileCacheMap)
		{
			if(FileCacheMap.containsKey(name))
			{ 
				return FileCacheMap.get(name);		
			}
			else
			{
				FileCache fc=new FileCache(name);
				FileCacheMap.put(name, fc);
				return fc;
			}
		}
	}
	
}
