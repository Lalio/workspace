package com.fcv.vms.service;

import java.util.List;

import com.fcv.vms.pojo.DParam;

public interface DParamService{
	
	List<DParam> getDParamsByMarkId(int markId);
	
	
	List<DParam> getDParams();
}
