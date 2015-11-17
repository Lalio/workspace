package com.fcv.vms.service.impl;

import java.util.Date;
import java.util.List;
import java.util.Map;

import com.fcv.vms.dao.RegDao;
import com.fcv.vms.pojo.Reg;
import com.fcv.vms.service.RegService;

public class RegServiceImpl implements RegService {

	private RegDao regDao;

	public void setRegDao(RegDao regDao) {
		this.regDao = regDao;
	}


	public List<Reg> getRegs(int modelValue) {
		return regDao.getRegs(modelValue);
	}


	@Override
	public List<Integer> getModelValues() {
		return regDao.getModelValue();
	}


	@Override
	public List<Integer> getMarkValueByModel(int modelValue) {
		return regDao.getMarkValueByModel(modelValue);
	}


	@Override
	public void getVinId() {
		regDao.getVinId();
		
	}


	@Override
	public Map<String, String> getVinName() {
		return regDao.getVinName();
	}



}
