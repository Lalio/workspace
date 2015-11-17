package com.fcv.vms.model;

public class WorkData {
	private String vin;
	private byte [] datas;
	private int sid ;

	public WorkData(String vin,byte[] datas,int sid){
		this.vin = vin;
		this.datas = datas;
		this.sid = sid;
	}
	
	
	
	public String getVin() {
		return vin;
	}
	public void setVin(String vin) {
		this.vin = vin;
	}
	public byte[] getDatas() {
		return datas;
	}
	public void setDatas(byte[] datas) {
		this.datas = datas;
	}
	public int getSid() {
		return sid;
	}
	public void setSid(int sid) {
		this.sid = sid;
	}


}
