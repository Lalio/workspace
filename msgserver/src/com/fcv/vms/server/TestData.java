package com.fcv.vms.server;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.sql.Time;
import java.util.HashMap;

import org.hibernate.Query;
import org.hibernate.Session;
import org.hibernate.Transaction;

import com.fcv.vms.model.HibernateModel;
import com.fcv.vms.model.HibernateSessionFactory;
import com.mysql.jdbc.exceptions.jdbc4.MySQLSyntaxErrorException;

public class TestData {
	public static void main(String[] args) throws Exception {
		spilt();
	}

	public static void spilt() throws Exception {
		HibernateModel model = new HibernateModel();

//		HibernateModel.createRegTable();
		// 载入驱动
		Class.forName("com.mysql.jdbc.Driver");
		// 建立连接
		Connection con = DriverManager
				.getConnection(
						"jdbc:mysql://cenyanmysql.mysql.rds.aliyuncs.com:3306/vms?useUnicode=true&amp;zeroDateTimeBehavior=convertToNull&amp;characterEncoding=UTF-8",
						"cenyan", "cenyan");
		// 创建状态
		Statement stmt = con.createStatement();
		Statement stmt2 = con.createStatement();
		Statement stmt3 = con.createStatement();
		Statement stmt4 = con.createStatement();
		Statement stmt5 = con.createStatement();

		// String allvin =
		// "select datas from data limit 10";//"select distinct vin from data where vin not like 'A0000%' limit 100";
		String sqlString = "select table_name from INFORMATION_SCHEMA.tables where table_name like 'z_data%'";
		String sqlString2 = "select vin from reg";
		ResultSet resultSet = stmt.executeQuery(sqlString);
		ResultSet resultSet2 = stmt2.executeQuery(sqlString2);
		HashMap<Long, String> map = new HashMap<Long, String>();
		HashMap<Long, String> map2 = new HashMap<Long, String>();
	
		int count = 0;
		while (resultSet.next()) {
			try {
				String tn = resultSet.getString("table_name");
				Statement updatesStatement = con.createStatement();
				String updateSQL = "ALTER TABLE "+tn+" CHANGE ID ID int(11) primary key not null auto_increment ";
				updatesStatement.execute(updateSQL);
				updatesStatement.close();
				System.out.println(++count);
			} catch (MySQLSyntaxErrorException e) {
				System.out.println("aaa");
				continue;
			}
		
		}
//		System.out.println(count);
//		count = 0;
//		while (resultSet2.next()) {
//			String vin = resultSet2.getString("VIN");
//			String tableNameString = map.get(vin.toLowerCase()) ;
//			if (tableNameString != null) {
//				map.put(getUnsignedInt(vin.hashCode()), "getUnsignedInt" + tableNameString);
//				count++;
//			}
//			// System.out.println(vin
//			// +"     "+map.get(getUnsignedInt(vin.hashCode())));
//		}
//		for(Long name : map.keySet()){
//			if(map2.get(name) == null){
//				System.out.println("aaaaa"+map.get(name));
//				String sql = "drop table "+map.get(name);
//			}
//		}
		
//		for (String name : map.values()) {
//			if(!name.startsWith("getUnsignedInt"))
//			{
//				name = name.replace("getUnsignedInt", "");
//				String countTablesString = "select count(*) as c from " + name;
//				Statement counttableStatement = con.createStatement();
//				ResultSet countSet =
//				counttableStatement.executeQuery(countTablesString);
//				if(countSet.next())
//				System.out.println(name + " " + countSet.getBigDecimal("c"));
//			}
//		}
		System.out.println(count);
	}

	public static long getUnsignedInt(int x) {
		return x & 0x00000000ffffffffL;
	}

	public static void update(String vin, Connection con)
			throws ClassNotFoundException, SQLException {

		// 创建状态
		Statement stmt = con.createStatement();

		String sql = "UPDATE reg SET has_data_table = 1,vin_hash = '"
				+ getUnsignedInt(vin.hashCode()) + "' WHERE vin = '" + vin + "'";
		try {		
			System.out.println("更新REG" + stmt.executeUpdate(sql));
		} catch (Exception e) {
			System.out.println("hash           "+vin.hashCode());
		}
	}
}
