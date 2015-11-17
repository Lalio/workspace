package com.fcv.vms.pojo;

import java.io.Serializable;



/** @author Hibernate CodeGenerator */
public class Mark implements Serializable {

    /**
	 * 
	 */
	private static final long serialVersionUID = -6288248890533925863L;

	/** identifier field */
    private Integer id;

    private String markName;
    
    private Integer modelValue;
    
    private Integer markValue;
   
	private byte[] dparamDatas;   
    
    private byte[] tparamDatas;  
    
	private String usersId;
	
	private Integer dparamBegin;
	
	private Integer dparamLength;
	
	private Integer tparamBegin;
	
	private Integer tparamLength;
	
	private Integer dtcExplainIsUse;
	
	private Integer fpLength;
	
	private Integer snBegin;
	
	private Integer idBegin;
	
	private Integer idLength;
	
	private Integer modelValueBegin;
	
	private Integer modelValueLength;
	
	private Integer locateBegin;
	
	private String parameterTypes;
	
	private String parameterTypeImgs;
    
    public String getParameterTypeImgs() {
		return parameterTypeImgs;
	}

	public void setParameterTypeImgs(String parameterTypeImgs) {
		this.parameterTypeImgs = parameterTypeImgs;
	}

	public String getParameterTypes() {
		return parameterTypes;
	}

	public void setParameterTypes(String parameterTypes) {
		this.parameterTypes = parameterTypes;
	}

	/** default constructor */
	public Mark() {
    }
		   
	public Integer getDtcExplainIsUse() {
		return dtcExplainIsUse;
	}

	public void setDtcExplainIsUse(Integer dtcExplainIsUse) {
		this.dtcExplainIsUse = dtcExplainIsUse;
	}

	public Integer getModelValue() {
		return modelValue;
	}

	public void setModelValue(Integer modelValue) {
		this.modelValue = modelValue;
	}

	public Integer getDparamBegin() {
		return dparamBegin;
	}

	public Integer getDparamLength() {
		return dparamLength;
	}

	public Integer getTparamBegin() {
		return tparamBegin;
	}

	public Integer getTparamLength() {
		return tparamLength;
	}

	public Integer getFpLength() {
		return fpLength;
	}

	public Integer getSnBegin() {
		return snBegin;
	}

	public Integer getIdBegin() {
		return idBegin;
	}

	public Integer getIdLength() {
		return idLength;
	}

	public Integer getModelValueBegin() {
		return modelValueBegin;
	}

	public Integer getModelValueLength() {
		return modelValueLength;
	}

	public Integer getLocateBegin() {
		return locateBegin;
	}

	public void setDparamBegin(Integer dparamBegin) {
		this.dparamBegin = dparamBegin;
	}

	public void setDparamLength(Integer dparamLength) {
		this.dparamLength = dparamLength;
	}

	public void setTparamBegin(Integer tparamBegin) {
		this.tparamBegin = tparamBegin;
	}

	public void setTparamLength(Integer tparamLength) {
		this.tparamLength = tparamLength;
	}

	public void setFpLength(Integer fpLength) {
		this.fpLength = fpLength;
	}

	public void setSnBegin(Integer snBegin) {
		this.snBegin = snBegin;
	}

	public void setIdBegin(Integer idBegin) {
		this.idBegin = idBegin;
	}

	public void setIdLength(Integer idLength) {
		this.idLength = idLength;
	}

	public void setModelValueBegin(Integer modelValueBegin) {
		this.modelValueBegin = modelValueBegin;
	}

	public void setModelValueLength(Integer modelValueLength) {
		this.modelValueLength = modelValueLength;
	}

	public void setLocateBegin(Integer locateBegin) {
		this.locateBegin = locateBegin;
	}

	public Integer getId() {
		return id;
	}

	public void setId(Integer id) {
		this.id = id;
	}

	public String getMarkName() {
		return markName;
	}

	public void setMarkName(String markName) {
		this.markName = markName;
	}

	public Integer getMarkValue() {
		return markValue;
	}

	public void setMarkValue(Integer markValue) {
		this.markValue = markValue;
	}

	public byte[] getDparamDatas() {
		return dparamDatas;
	}

	public byte[] getTparamDatas() {
		return tparamDatas;
	}

	public void setDparamDatas(byte[] dparamDatas) {
		this.dparamDatas = dparamDatas;
	}

	public void setTparamDatas(byte[] tparamDatas) {
		this.tparamDatas = tparamDatas;
	}

	public String getUsersId() {
		return usersId;
	}

	public void setUsersId(String usersId) {
		this.usersId = usersId;
	}
	
}
