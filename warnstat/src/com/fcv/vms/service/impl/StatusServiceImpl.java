package com.fcv.vms.service.impl;

import java.util.Date;
import java.util.Map;

import com.fcv.vms.dao.StatusDao;
import com.fcv.vms.service.StatusService;

public class StatusServiceImpl implements StatusService {

	private StatusDao statusDao;
	
	public void setStatusDao(StatusDao statusDao) {
		this.statusDao = statusDao;
	}


	@Override
	public int getMVByVin(String vin) {
		
		return statusDao.getMarkValueByVin(vin);
	}


	@Override
	public Map<String, Date> getMap() {
		return statusDao.getMap();
	}

}
