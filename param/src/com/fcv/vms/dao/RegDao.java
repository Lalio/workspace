package com.fcv.vms.dao;

import java.util.Date;
import java.util.List;

import com.fcv.vms.pojo.Reg;

public interface RegDao {

	List<Reg> getRegs(int modelValue);

	List<Integer> getModelValue();

	List<Integer> getMarkValueByModel(int modelValue);

	void getVinId();
	

}
