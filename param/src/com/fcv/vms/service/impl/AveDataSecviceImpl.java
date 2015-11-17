package com.fcv.vms.service.impl;

import java.util.Date;
import java.util.List;

import com.fcv.vms.dao.AveDataDao;
import com.fcv.vms.pojo.AveData;
import com.fcv.vms.service.AveDataService;

public class AveDataSecviceImpl implements AveDataService {
	
	private AveDataDao aveDataDao;
	public void setAveDataDao(AveDataDao aveDataDao) {
		this.aveDataDao = aveDataDao;
	}

	@Override
	public void add(List<AveData> list) {
		aveDataDao.add(list);

	}

	@Override
	public void update(AveData aveData) {
		aveDataDao.update(aveData);

	}




}
