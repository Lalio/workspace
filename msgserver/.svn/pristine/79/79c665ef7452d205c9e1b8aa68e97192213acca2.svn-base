package com.fcv.vms.dao;

import java.io.Serializable;

public class PK_AreaToTerminal implements Serializable{
	/**
	 * 
	 */
	private static final long serialVersionUID = 4064709235284188160L;
	
	private int regId;
	private int areaId;
	
	public int getRegId() {
		return regId;
	}

	public void setRegId(int regId) {
		this.regId = regId;
	}

	public int getAreaId() {
		return areaId;
	}

	public void setAreaId(int areaId) {
		this.areaId = areaId;
	}

	@Override 
	public boolean equals(Object o) { 
		if(o instanceof PK_AreaToTerminal) 
		{
			PK_AreaToTerminal pk=(PK_AreaToTerminal)o;
			if(pk.getRegId()==regId&&pk.getAreaId()==areaId)
				return true;
		}
		return false;
	}
}
