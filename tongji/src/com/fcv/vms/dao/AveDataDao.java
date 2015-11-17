package com.fcv.vms.dao;

import java.util.List;

import com.fcv.vms.pojo.AveData;



public interface AveDataDao {

	void add(List<AveData> list,String vin);

	void add(List<AveData> list);

	void update(AveData aveData);
	

}
