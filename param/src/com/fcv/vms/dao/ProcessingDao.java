package com.fcv.vms.dao;

import java.util.Date;
import java.util.List;
import java.util.Map;

import com.fcv.vms.pojo.Processing;

public interface ProcessingDao {

	void add(Processing proce);

	Processing getProce(String vin);

	Map<String , Processing> getMap();
	
	void update(Processing proce);
	
	Map<String, Date> getProce();

}
