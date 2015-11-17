package com.fcv.vms.pojo;
import java.io.Serializable;
import java.util.Date;

public class WarnStat implements Serializable{
	
	/**
	 * 
	 */
	private static final long serialVersionUID = 1L;
	
	private Integer id;
	private String vin;
	private int dParamId;
	private Date startTime;
	private Date endTime;
	private String warnName;
	private String detail;
	private int warnTime;
	private String warnSystem;
	private int isOver;
	
	public int getIsOver() {
		return isOver;
	}
	public void setIsOver(int isOver) {
		this.isOver = isOver;
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
	public int getdParamId() {
		return dParamId;
	}
	public void setdParamId(int dParamId) {
		this.dParamId = dParamId;
	}
	public Date getStartTime() {
		return startTime;
	}
	public void setStartTime(Date startTime) {
		this.startTime = startTime;
	}
	public Date getEndTime() {
		return endTime;
	}
	public void setEndTime(Date endTime) {
		this.endTime = endTime;
	}
	public String getWarnName() {
		return warnName;
	}
	public void setWarnName(String warnName) {
		this.warnName = warnName;
	}
	public String getDetail() {
		return detail;
	}
	public void setDetail(String detail) {
		this.detail = detail;
	}
	public int getWarnTime() {
		return warnTime;
	}
	public void setWarnTime(int warnTime) {
		this.warnTime = warnTime;
	}
	public String getWarnSystem() {
		return warnSystem;
	}
	public void setWarnSystem(String warnSystem) {
		this.warnSystem = warnSystem;
	}
	@Override
	public String toString() {
		return "WarnStat [id=" + id + ", vin=" + vin + ", dParamId=" + dParamId
				+ ", startTime=" + startTime + ", endTime=" + endTime
				+ ", warnname=" + warnName + ", detail=" + detail
				+ ", warnTime=" + warnTime + ", warnSystem=" + warnSystem
				+ ", isOver=" + isOver + "]";
	}
	
}
