package com.fcv.vms.service;

import java.util.List;

import com.fcv.vms.pojo.DParam;

public interface DParamService{
	
	List<DParam> getDParamsByMarkId(int markId);
	
	void insertDParams(String vin,String key,String value);
	
	List<DParam> getDParams();
	
	void reset();
}
