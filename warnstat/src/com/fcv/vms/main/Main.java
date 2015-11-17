package com.fcv.vms.main;




public class Main {

	
	public static void main(String[] args) {
			start();
//			System.exit(0);
	}

	private static void start(){
		Processer y =  new Processer();
		try {
			y.begin();
			System.out.println("main over");
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		

	}
}
 