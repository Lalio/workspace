package com.fcv.vms.pojo;

import java.io.Serializable;
import java.util.Date;

import net.sourceforge.jtds.jdbc.DateTime;

public class Processing implements Serializable{

	/**
	 * 
	 */
	private static final long serialVersionUID = 5794107507151691052L;
	
	private Integer id;
	private String vin;
	private Date endTime;
	private Date today_max;
	private Date today;
	
	
	public Date getToday_max() {
		return today_max;
	}
	public void setToday_max(Date today_max) {
		this.today_max = today_max;
	}
	public Date getToday() {
		return today;
	}
	public void setToday(Date today) {
		this.today = today;
	}
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

}
