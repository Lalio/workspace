package com.fcv.vms.dao;

import java.util.Date;
import java.util.List;
import java.util.Map;
import java.util.Vector;

import com.fcv.vms.pojo.Processing;

public interface ProcessingDao {

	void add(Processing proce);

	Map<String, List<Processing>>  getProce();

	Map<String , Processing> getMap();
	
	void update(Processing proce);

}
