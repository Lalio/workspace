package com.fcv.vms.pojo;

public class DparamMapValue{
	private int key;
	private String value;
	private int switchStatus;
	private int switchColor;
	
	@Override
	public String toString() {
		return "DparamMapValue [key=" + key + ", value=" + value
				+ ", switchStatus=" + switchStatus + ", switchColor="
				+ switchColor + "]";
	}
	public DparamMapValue(int key,String value,int switchStatus,int switchColor){
		this.key = key;
		this.value = value;
		this.switchColor = switchColor;
		this.switchStatus = switchStatus;
	}
	public DparamMapValue(){}
	
	public int getKey() {
		return key;
	}
	public void setKey(int key) {
		this.key = key;
	}
	public String getValue() {
		return value;
	}
	public void setValue(String value) {
		this.value = value;
	}
	public int getSwitchStatus() {
		return switchStatus;
	}
	public void setSwitchStatus(int switchStatus) {
		this.switchStatus = switchStatus;
	}
	public int getSwitchColor() {
		return switchColor;
	}
	public void setSwitchColor(int switchColor) {
		this.switchColor = switchColor;
	}
}