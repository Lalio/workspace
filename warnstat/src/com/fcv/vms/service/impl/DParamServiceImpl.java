package com.fcv.vms.service.impl;

import java.util.List;

import com.fcv.vms.dao.DParamDao;
import com.fcv.vms.pojo.DParam;
import com.fcv.vms.service.DParamService;

public class DParamServiceImpl implements DParamService {

	private DParamDao dParamDao;

	public void setdParamDao(DParamDao dParamDao) {
		this.dParamDao = dParamDao;
	}


	public List<DParam> getDParamsByMarkId(int markId) {
		return dParamDao.getDParams(markId);
	}


	@Override
	public List<DParam> getDParams() {
		return dParamDao.getDParams();
	}


	@Override
	public void insertDParams(String vin, String key,String value) {
		dParamDao.insertDParams(vin,key,value);
	}

}
