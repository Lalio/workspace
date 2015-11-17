package com.fcv.vms.pojo;

import java.util.Map;

public class MyDParam {
	private DParam dParam;
	private Map<Integer, DparamMapValue> dmv ;

	public DParam getdParam() {
		return dParam;
	}
	public void setdParam(DParam dParam) {
		this.dParam = dParam;
	}
	public Map<Integer, DparamMapValue> getDmv() {
		return dmv;
	}
	public void setDmv(Map<Integer, DparamMapValue> dmv) {
		this.dmv = dmv;
	}
	@Override
	public String toString() {
		return "MyDParam [dParam=" + dParam.toString() + ", dmv=" + dmv + "]";
	}
	
	
	

}
