package com.fcv.vms.pojo;

import java.io.Serializable;
import java.util.Date;

/** @author Hibernate CodeGenerator */
public class Reg implements Serializable {

    /**
	 * 
	 */
    private static final long serialVersionUID = 1L;

    /** identifier field */
    private Integer id;

    private String vin;

    private String name;

    private Integer markValue;

    private Integer modelValue;

    private Date time;

    private Integer isuse;

    private Date stime;

    private float areaLng;

    private float areaLat;

    private Integer areaLenght;

    private String motorcadeName;

    private String driverName;

    private String contact;

    private int has_data_table;

    public int getHas_data_table() {
        return has_data_table;
    }

    public void setHas_data_table(int has_data_table) {
        this.has_data_table = has_data_table;
    }

    public String getMotorcadeName() {
        return motorcadeName;
    }

    public void setMotorcadeName(String motorcadeName) {
        this.motorcadeName = motorcadeName;
    }

    public String getDriverName() {
        return driverName;
    }

    public void setDriverName(String driverName) {
        this.driverName = driverName;
    }

    public String getContact() {
        return contact;
    }

    public void setContact(String contact) {
        this.contact = contact;
    }

    public float getAreaLng() {
        return areaLng;
    }

    public void setAreaLng(float areaLng) {
        this.areaLng = areaLng;
    }

    public float getAreaLat() {
        return areaLat;
    }

    public void setAreaLat(float areaLat) {
        this.areaLat = areaLat;
    }

    public Integer getAreaLenght() {
        return areaLenght;
    }

    public void setAreaLenght(Integer areaLenght) {
        this.areaLenght = areaLenght;
    }

    /** default constructor */
    public Reg() {
    }

    // public String getUsersId() {
    // return usersId;
    // }
    //
    // public void setUsersId(String usersId) {
    // this.usersId = usersId;
    // }

    public Integer getId() {
        return id;
    }

    public Date getStime() {
        return stime;
    }

    public void setStime(Date stime) {
        this.stime = stime;
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

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public Integer getMarkValue() {
        return markValue;
    }

    public void setMarkValue(Integer markValue) {
        this.markValue = markValue;
    }

    public Integer getModelValue() {
        return modelValue;
    }

    public void setModelValue(Integer modelValue) {
        this.modelValue = modelValue;
    }

    public Date getTime() {
        return time;
    }

    public void setTime(Date time) {
        this.time = time;
    }

    public Integer getIsuse() {
        return isuse;
    }

    public void setIsuse(Integer isuse) {
        this.isuse = isuse;
    }

}
