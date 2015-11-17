package com.fcv.vms.dao;

import java.io.Serializable;

public class RectangularArea  implements Serializable{
	/**
	 * 
	 */
	private static final long serialVersionUID = 3474595491621673949L;
	
	private Integer id;
	private String name;
	private int attribute;
	private int topLeftLatitude;
	private int topLeftLongitude;
	private int bottomRightLatitude;
	private int bottomRightLongitude;
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
	public int getTopLeftLatitude() {
		return topLeftLatitude;
	}
	public void setTopLeftLatitude(int topLeftLatitude) {
		this.topLeftLatitude = topLeftLatitude;
	}
	public int getTopLeftLongitude() {
		return topLeftLongitude;
	}
	public void setTopLeftLongitude(int topLeftLongitude) {
		this.topLeftLongitude = topLeftLongitude;
	}
	public int getBottomRightLatitude() {
		return bottomRightLatitude;
	}
	public void setBottomRightLatitude(int bottomRightLatitude) {
		this.bottomRightLatitude = bottomRightLatitude;
	}
	public int getBottomRightLongitude() {
		return bottomRightLongitude;
	}
	public void setBottomRightLongitude(int bottomRightLongitude) {
		this.bottomRightLongitude = bottomRightLongitude;
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
