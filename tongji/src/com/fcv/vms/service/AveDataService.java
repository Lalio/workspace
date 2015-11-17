package com.fcv.vms.service;

import java.util.Date;
import java.util.List;

import com.fcv.vms.pojo.AveData;



public interface AveDataService {
	
	void add(List<AveData> list);
	
	void add(List<AveData> list,String vin);
	
	void update(AveData aveData);

}
