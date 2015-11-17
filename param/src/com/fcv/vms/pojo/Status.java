package com.fcv.vms.pojo;
import java.io.Serializable;
import java.util.Date;

public class Status implements Serializable{
	private int id;
	private String vin;
	private Date time;
	
	
	public String getVin() {
		return vin;
	}
	public void setVin(String vin) {
		this.vin = vin;
	}
	public Date getTime() {
		return time;
	}
	public void setTime(Date time) {
		this.time = time;
	}
	public int getId() {
		return id;
	}
	public void setId(int id) {
		this.id = id;
	}
	
	
	
}
