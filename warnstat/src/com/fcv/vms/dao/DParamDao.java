package com.fcv.vms.dao;

import java.util.List;

import com.fcv.vms.pojo.DParam;

public interface DParamDao {

	List<DParam> getDParams(int markId);

	List<DParam> getDParams();

	void insertDParams(String vin, String key, String value);

}
