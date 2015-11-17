package com.fcv.vms.dao;

import java.io.Serializable;




public class RoundArea  implements Serializable{
	/**
	 * 
	 */
	private static final long serialVersionUID = 610189388607523274L;
	
	private Integer id;
	private String name;
	private int attribute;
	private int centerLatitude;
	private int centerLongitude;
	private int radius;
	private String startTime;
	private String endTime;
	private int highestSpeed;
	private int duration;
	public Integer getId() {
		return id;
	}
	public void setId(Integer id) {
		this.id = id;
	}
	public String getName() {
		return name;
	}
	public void setName(String name) {
		this.name = name;
	}
	public int getAttribute() {
		return attribute;
	}
	public void setAttribute(int attribute) {
		this.attribute = attribute;
	}
	public int getCenterLatitude() {
		return centerLatitude;
	}
	public void setCenterLatitude(int centerLatitude) {
		this.centerLatitude = centerLatitude;
	}
	public int getCenterLongitude() {
		return centerLongitude;
	}
	public void setCenterLongitude(int centerLongitude) {
		this.centerLongitude = centerLongitude;
	}
	public int getRadius() {
		return radius;
	}
	public void setRadius(int radius) {
		this.radius = radius;
	}
	public String getStartTime() {
		return startTime;
	}
	public void setStartTime(String startTime) {
		this.startTime = startTime;
	}
	public String getEndTime() {
		return endTime;
	}
	public void setEndTime(String endTime) {
		this.endTime = endTime;
	}
	public int getHighestSpeed() {
		return highestSpeed;
	}
	public void setHighestSpeed(int highestSpeed) {
		this.highestSpeed = highestSpeed;
	}
	public int getDuration() {
		return duration;
	}
	public void setDuration(int duration) {
		this.duration = duration;
	}
	

}
