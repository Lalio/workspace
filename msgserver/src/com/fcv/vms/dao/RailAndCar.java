package com.fcv.vms.dao;

import java.io.Serializable;

public class RailAndCar implements Serializable {
	private Integer id;	
	private int rid;
	private String railName;
	private int cid;
	private String cname;
	private String cvin;
	
	public String getCname() {
		return cname;
	}
	public void setCname(String cname) {
		this.cname = cname;
	}
	public String getCvin() {
		return cvin;
	}
	public void setCvin(String cvin) {
		this.cvin = cvin;
	}
	public int getRid() {
		return rid;
	}
	public void setRid(int rid) {
		this.rid = rid;
	}
	public int getCid() {
		return cid;
	}
	public void setCid(int cid) {
		this.cid = cid;
	}
	
	public Integer getId() {
		return id;
	}
	public void setId(Integer id) {
		this.id = id;
	}
	public String getRailName() {
		return railName;
	}
	public void setRailName(String railName) {
		this.railName = railName;
	}
}
