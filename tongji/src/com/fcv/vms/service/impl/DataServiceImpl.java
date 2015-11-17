package com.fcv.vms.service.impl;

import java.util.Date;
import java.util.List;
import java.util.Map;

import com.fcv.vms.dao.DataDao;
import com.fcv.vms.pojo.AveData;
import com.fcv.vms.pojo.Data;
import com.fcv.vms.pojo.MyDParam;
import com.fcv.vms.service.DataService;

public class DataServiceImpl implements DataService {

	private DataDao dataDao;
	

	public void setDataDao(DataDao dataDao) {
		this.dataDao = dataDao;
	}


	public List<AveData> getDatas(String vin, String stime,String etime) {
		return dataDao.getDatas(vin,stime,etime);
	}


	@Override
	public Date getFirstDataTime(String vin) {
		return dataDao.getFirstDataTime(vin);
	}


	@Override
	public Date getTodayMax(String vin,Date today) {
		return dataDao.getTodayMax(vin,today);
	}


	@Override
	public Date getNextMin(String vin, Date today) {
		return dataDao.getNextMin(vin,today);
	}


	@Override
	public Date getNextStime(String vin, Date stime) {
		return dataDao.getNextStime(vin,stime);
	}

	@Override
	public void getMaxTime(){
		 dataDao.getMaxTime();
		 return;
	}

	@Override
	public List<String> getVin() {
		return dataDao.getVin();
	}


	@Override
	public void getAllDatas(long begin , long end) {
		dataDao.getAllDatas(begin ,end);
		return;
		
	}


	@Override
	public void getAllDatas(String vinString) {
		dataDao.getAllDatas(vinString);
		return;
		
	}


	@Override
	public List<Data> getAllDatas(String vinString, Date ...dates) {
		
		return dataDao.getAllDatas(vinString,dates);
		
	}


	@Override
	public Map<String, Date> getLastTime() {
		
		return dataDao.getLastTime();
	}
}
