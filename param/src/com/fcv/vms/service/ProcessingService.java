package com.fcv.vms.service;


import java.util.Date;
import java.util.Map;

import com.fcv.vms.pojo.Processing;

public interface ProcessingService{

	void add(Processing proc);	//����
	
	Processing getProce(String vin);	//
	
	Map<String , Processing> getMap();
	
	void update(Processing proc);
	
	Map<String, Date> getProce();
}
