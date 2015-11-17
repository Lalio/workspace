package com.fcv.vms.service.impl;

import com.fcv.vms.dao.MarkDao;
import com.fcv.vms.service.MarkService;

public class MarkServiceImpl implements MarkService {

	private MarkDao markDao;
	
	
	public void setMarkDao(MarkDao markDao) {
		this.markDao = markDao;
	}


	@Override
	public int getMarkIDByMV(int modelValues) {
		return markDao.getMarkIdByMarkValue(modelValues);
	}

}
