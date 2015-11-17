package com.fcv.vms.service;


import java.util.Date;
import java.util.Map;

import com.fcv.vms.pojo.Processing;

public interface ProcessingService{

	void add(Processing procw);	//ÐÂÔö
	
	Processing getProce(String vin);	//
	
	Map<String , Processing> getMap();
	
	void update(Processing procw);
	
	Map<String, Date> getProce();
}
