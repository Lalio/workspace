package com.fcv.vms.service;


import java.util.Date;
import java.util.List;
import java.util.Map;
import java.util.Vector;

import com.fcv.vms.pojo.Processing;

public interface ProcessingService{

	void add(Processing proc);	//ÐÂÔö
	
	Map<String , Processing> getMap();
	
	void update(Processing proc);
	
	Map<String, List<Processing>>  getProce();

	
}
