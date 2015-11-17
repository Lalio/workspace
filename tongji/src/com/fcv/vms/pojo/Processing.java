package com.fcv.vms.pojo;

import java.io.Serializable;
import java.util.Date;

import net.sourceforge.jtds.jdbc.DateTime;

public class Processing implements Serializable{

	/**
	 * 
	 */
	private static final long serialVersionUID = 5794107507151691052L;
	
	public Processing(){}
	
	public Processing(String vin,Date endTime,int timeType ){
		this.vin = vin;
		this.endTime = endTime;
		this.timeType = timeType;
	}
	
	private Integer id;
	private String vin;
	private Date endTime;
	private int timeType;
	public Integer getId() {
		return id;
	}
	public void setId(Integer id) {
		this.id = id;
	}
	public String getVin() {
		return vin;
	}
	public void setVin(String vin) {
		this.vin = vin;
	}
	public Date getEndTime() {
		return endTime;
	}
	public void setEndTime(Date endTime) {
		this.endTime = endTime;
	}
	public int getTimeType() {
		return timeType;
	}
	public void setTimeType(int timeType) {
		this.timeType = timeType;
	}
	
	

}
