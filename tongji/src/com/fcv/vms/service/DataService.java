package com.fcv.vms.service;

import java.util.Date;
import java.util.List;
import java.util.Map;

import com.fcv.vms.pojo.AveData;
import com.fcv.vms.pojo.Data;
import com.fcv.vms.pojo.MyDParam;

public interface DataService{
	List<AveData> getDatas(String vin,String stime,String etime);

	Date getFirstDataTime(String vin);
	
	Date getTodayMax(String vin,Date today);
	
	Date getNextMin(String vin,Date today);
	
	Date getNextStime(String vin,Date stime);
	
	List<String> getVin();

	void getMaxTime();
	
	void getAllDatas(long begin,long end);
	
	void getAllDatas(String vinString);
	
	List<Data> getAllDatas(String vinString, Date ...dates);
	
	Map<String, Date> getLastTime();
}
