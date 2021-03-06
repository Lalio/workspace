package com.fcv.vms.service;

import java.util.Date;
import java.util.List;
import java.util.Map;

import com.fcv.vms.pojo.Reg;


public interface RegService{

	List<Reg> getRegs(int modelValue);

	List<Integer> getModelValues();
	
	List<Integer> getMarkValueByModel(int modelValue);
	
	void getVinId();
	
	Map<String,String> getVinName();

}
