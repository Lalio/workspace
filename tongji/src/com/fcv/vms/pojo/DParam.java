package com.fcv.vms.pojo;

import java.io.Serializable;

/** @author Hibernate CodeGenerator */
public class DParam  implements Serializable {

    /**
	 * 
	 */
	private static final long serialVersionUID = 1L;

	/** identifier field */
    private Integer id;

    private String dparamName;

    private String dparamFormat;
    
    private String dParamNameType;
    
    private String namePinYin;
    
    private String dparamUnit;

    private float dparamFactor;
    
    private float dparamOffset;
    
    private float dparamValueMin;
    
    private float dparamValueMax;
    
    private float dparamValueDefault;
    
    private Integer dparamByteLength;
    
    private Integer dparamOrder;
    
    private Integer dparamByteBegin;
    
    private Integer dparamIsByte;
    
    private String dparamByteDatas;
    
    private Integer dparamIsMap;
    
    private String dparamMapDatas;
    
    private Integer dparamMeterType;
    
    private Integer markId;
    
    private String dparamType;
    
    private String dparamMeter;
    
    private Integer dparamAgreeType;
    
    private Integer dparamNewAgreeType;
    
    private Integer dparamHighAndLow;

	public Integer getId() {
		return id;
	}

	public void setId(Integer id) {
		this.id = id;
	}

	public String getDparamName() {
		return dparamName;
	}

	public void setDparamName(String dparamName) {
		this.dparamName = dparamName;
	}

	public String getDparamFormat() {
		return dparamFormat;
	}

	public void setDparamFormat(String dparamFormat) {
		this.dparamFormat = dparamFormat;
	}

	public String getdParamNameType() {
		return dParamNameType;
	}

	public void setdParamNameType(String dParamNameType) {
		this.dParamNameType = dParamNameType;
	}

	public String getNamePinYin() {
		return namePinYin;
	}

	public void setNamePinYin(String namePinYin) {
		this.namePinYin = namePinYin;
	}

	public String getDparamUnit() {
		return dparamUnit;
	}

	public void setDparamUnit(String dparamUnit) {
		this.dparamUnit = dparamUnit;
	}

	public float getDparamFactor() {
		return dparamFactor;
	}

	public void setDparamFactor(float dparamFactor) {
		this.dparamFactor = dparamFactor;
	}

	public float getDparamOffset() {
		return dparamOffset;
	}

	public void setDparamOffset(float dparamOffset) {
		this.dparamOffset = dparamOffset;
	}

	public float getDparamValueMin() {
		return dparamValueMin;
	}

	public void setDparamValueMin(float dparamValueMin) {
		this.dparamValueMin = dparamValueMin;
	}

	public float getDparamValueMax() {
		return dparamValueMax;
	}

	public void setDparamValueMax(float dparamValueMax) {
		this.dparamValueMax = dparamValueMax;
	}

	public float getDparamValueDefault() {
		return dparamValueDefault;
	}

	public void setDparamValueDefault(float dparamValueDefault) {
		this.dparamValueDefault = dparamValueDefault;
	}

	public Integer getDparamByteLength() {
		return dparamByteLength;
	}

	public void setDparamByteLength(Integer dparamByteLength) {
		this.dparamByteLength = dparamByteLength;
	}

	public Integer getDparamOrder() {
		return dparamOrder;
	}

	public void setDparamOrder(Integer dparamOrder) {
		this.dparamOrder = dparamOrder;
	}

	public Integer getDparamByteBegin() {
		return dparamByteBegin;
	}

	public void setDparamByteBegin(Integer dparamByteBegin) {
		this.dparamByteBegin = dparamByteBegin;
	}

	public Integer getDparamIsByte() {
		return dparamIsByte;
	}

	public void setDparamIsByte(Integer dparamIsByte) {
		this.dparamIsByte = dparamIsByte;
	}

	public String getDparamByteDatas() {
		return dparamByteDatas;
	}

	public void setDparamByteDatas(String dparamByteDatas) {
		this.dparamByteDatas = dparamByteDatas;
	}

	public Integer getDparamIsMap() {
		return dparamIsMap;
	}

	public void setDparamIsMap(Integer dparamIsMap) {
		this.dparamIsMap = dparamIsMap;
	}

	public String getDparamMapDatas() {
		return dparamMapDatas;
	}

	public void setDparamMapDatas(String dparamMapDatas) {
		this.dparamMapDatas = dparamMapDatas;
	}

	public Integer getDparamMeterType() {
		return dparamMeterType;
	}

	public void setDparamMeterType(Integer dparamMeterType) {
		this.dparamMeterType = dparamMeterType;
	}

	public Integer getMarkId() {
		return markId;
	}

	public void setMarkId(Integer markId) {
		this.markId = markId;
	}

	public String getDparamType() {
		return dparamType;
	}

	public void setDparamType(String dparamType) {
		this.dparamType = dparamType;
	}

	public String getDparamMeter() {
		return dparamMeter;
	}

	public void setDparamMeter(String dparamMeter) {
		this.dparamMeter = dparamMeter;
	}

	public Integer getDparamAgreeType() {
		return dparamAgreeType;
	}

	public void setDparamAgreeType(Integer dparamAgreeType) {
		this.dparamAgreeType = dparamAgreeType;
	}

	public Integer getDparamNewAgreeType() {
		return dparamNewAgreeType;
	}

	public void setDparamNewAgreeType(Integer dparamNewAgreeType) {
		this.dparamNewAgreeType = dparamNewAgreeType;
	}

	public Integer getDparamHighAndLow() {
		return dparamHighAndLow;
	}

	public void setDparamHighAndLow(Integer dparamHighAndLow) {
		this.dparamHighAndLow = dparamHighAndLow;
	}

	@Override
	public String toString() {
		return "DParam [id=" + id + ", dparamName=" + dparamName
				+ ", dparamFormat=" + dparamFormat + ", dParamNameType="
				+ dParamNameType + ", namePinYin=" + namePinYin
				+ ", dparamUnit=" + dparamUnit + ", dparamFactor="
				+ dparamFactor + ", dparamOffset=" + dparamOffset
				+ ", dparamValueMin=" + dparamValueMin + ", dparamValueMax="
				+ dparamValueMax + ", dparamValueDefault=" + dparamValueDefault
				+ ", dparamByteLength=" + dparamByteLength + ", dparamOrder="
				+ dparamOrder + ", dparamByteBegin=" + dparamByteBegin
				+ ", dparamIsByte=" + dparamIsByte + ", dparamByteDatas="
				+ dparamByteDatas + ", dparamIsMap=" + dparamIsMap
				+ ", dparamMapDatas=" + dparamMapDatas + ", dparamMeterType="
				+ dparamMeterType + ", markId=" + markId + ", dparamType="
				+ dparamType + ", dparamMeter=" + dparamMeter
				+ ", dparamAgreeType=" + dparamAgreeType
				+ ", dparamNewAgreeType=" + dparamNewAgreeType
				+ ", dparamHighAndLow=" + dparamHighAndLow + "]";
	}

	
    

	
}
