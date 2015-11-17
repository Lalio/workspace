package com.fcv.vms.service.impl;


import java.util.Date;
import java.util.Map;

import com.fcv.vms.dao.ProcessingDao;
import com.fcv.vms.pojo.Processing;
import com.fcv.vms.service.ProcessingService;

public class ProcessingServiceImpl implements ProcessingService {

	private ProcessingDao processingDao;

	public void setProcessingDao(ProcessingDao processingDao) {
		this.processingDao = processingDao;
	}

	public void add(Processing proce) {
		processingDao.add(proce);
	}

	public Processing getProce(String vin) {
		return processingDao.getProce(vin);
	}

	public void update(Processing proce) {
		processingDao.update(proce);
		
	}
	public Map<String , Processing> getMap(){
		return processingDao.getMap();
	}

	@Override
	public Map<String, Date> getProce() {
		return processingDao.getProce();
	}

}
