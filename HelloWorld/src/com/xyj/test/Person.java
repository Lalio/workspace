package com.xyj.test;

public class Person {

	public Person(){}
	
	public Person(String name){
		this.name = name;
	}
	private String name;

	public String getName() {
		return name;
	}

	public void setName(String name) {
		this.name = name;
	}
}
