package com.fcv.vms.service.impl;


import java.util.Date;
import java.util.List;

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

	public List<WarnStat> getWarnStat(String vin,String markId,String isover) {
		return warnStatDao.getWarnStat(vin,markId,isover);
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
	public int getWarnNum(String vin, Date today2) {
		return warnStatDao.getWarnNum(vin,today2);
	}



}
