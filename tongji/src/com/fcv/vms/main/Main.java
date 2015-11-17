package com.fcv.vms.main;

import java.util.ArrayList;
import java.util.Date;

import com.fcv.vms.dao.DataDao;
import com.fcv.vms.dao.ProcessingDao;
import com.fcv.vms.dao.hibernate.impl.DataDaoHibernateImpl;
import com.fcv.vms.dao.hibernate.impl.ProcessingDaoHibernateImpl;
import com.fcv.vms.pojo.MyDParam;




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
 