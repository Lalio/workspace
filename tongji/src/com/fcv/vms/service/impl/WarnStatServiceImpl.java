package com.fcv.vms.service.impl;


import java.util.Date;
import java.util.List;
import java.util.Map;

import com.fcv.vms.dao.WarnStatDao;
import com.fcv.vms.pojo.WarnStat;
import com.fcv.vms.service.WarnStatService;

public class WarnStatServiceImpl implements WarnStatService {

	private WarnStatDao warnStatDao;

	public void setWarnStatDao(WarnStatDao warnStatDao) {
		this.warnStatDao = warnStatDao;
	}

	public void add(List<WarnStat> warnStat) {
		warnStatDao.add(warnStat);
	}

	public List<WarnStat> getWarnStat(String vin,Date ...dates) {
		return warnStatDao.getWarnStat(vin,dates);
	}

	public void update(WarnStat warnStat) {
		warnStatDao.update(warnStat);
		
	}

	@Override
	public boolean isHave(String vin, Date time,int dParamID) {
		// TODO Auto-generated method stub
		return warnStatDao.isHave(vin,time,dParamID);
	}

	@Override
	public int getWarnNum(String vin, Date today2,long gap) {
		return warnStatDao.getWarnNum(vin,today2,gap);
	}



}
