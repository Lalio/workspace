package com.fcv.vms.pojo;

import java.io.Serializable;
import java.util.Arrays;
import java.util.Date;

/** @author Hibernate CodeGenerator */
public class Data implements Serializable {

    /**
	 * 
	 */
	private static final long serialVersionUID = 1L;

	/** identifier field */
    private Integer id;

    private String vin;

    private Date time;
    
    private byte[] datas;    
    
    private int locateIsValid; 
    
    private Date locateTime; 
    
    private float locateLongitude; 
    
    private float locateLatitude; 
    
    private float locateSpeed; 
    
    private float locateDirection;  
    
    private int sn;  
    
    
    
    
    
   
    @Override
	public String toString() {
		return "Data [id=" + id + ", vin=" + vin + ", time=" + time
				+ ", datas=" + Arrays.toString(datas) + ", locateIsValid="
				+ locateIsValid + ", locateTime=" + locateTime
				+ ", locateLongitude=" + locateLongitude + ", locateLatitude="
				+ locateLatitude + ", locateSpeed=" + locateSpeed
				+ ", locateDirection=" + locateDirection + ", sn=" + sn + "]";
	}

	/** default constructor */
    public Data() {
    }
              
    public int getSn() {
		return sn;
	}

	public void setSn(int sn) {
		this.sn = sn;
	}

	public Date getLocateTime() {
		return locateTime;
	}

	public void setLocateTime(Date locateTime) {
		this.locateTime = locateTime;
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

	public Date getTime() {
		return time;
	}

	public void setTime(Date time) {
		this.time = time;
	}

	public byte[] getDatas() {
		return datas;
	}

	public void setDatas(byte[] datas) {
		this.datas = datas;
	}

	public int getLocateIsValid() {
		return locateIsValid;
	}

	public void setLocateIsValid(int locateIsValid) {
		this.locateIsValid = locateIsValid;
	}

	public float getLocateLongitude() {
		return locateLongitude;
	}

	public void setLocateLongitude(float locateLongitude) {
		this.locateLongitude = locateLongitude;
	}

	public float getLocateLatitude() {
		return locateLatitude;
	}

	public void setLocateLatitude(float locateLatitude) {
		this.locateLatitude = locateLatitude;
	}

	public float getLocateSpeed() {
		return locateSpeed;
	}

	public void setLocateSpeed(float locateSpeed) {
		this.locateSpeed = locateSpeed;
	}

	public float getLocateDirection() {
		return locateDirection;
	}

	public void setLocateDirection(float locateDirection) {
		this.locateDirection = locateDirection;
	}
}
