package com.fcv.vms.server;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.Statement;

public class DeleteTable {
	public static void main(String [] args ) throws Exception{
		// 载入驱动
		Class.forName("com.mysql.jdbc.Driver");
		// 建立连接
		Connection con = DriverManager
				.getConnection(
						"jdbc:mysql://cenyanmysql.mysql.rds.aliyuncs.com:3306/vms?useUnicode=true&amp;zeroDateTimeBehavior=convertToNull&amp;characterEncoding=UTF-8",
						"cenyan", "cenyan");
		
		System.out.println(" 请再次确认需要 你要删除的表！");
		System.exit(0);
////		
		Statement statement = con.createStatement();
		Statement deleteStatement = con.createStatement();
		
		String sqlString = "select table_name from INFORMATION_SCHEMA.tables where table_name like 'z_ave_%'";
		ResultSet rs = statement.executeQuery(sqlString);
		int num = 0;
		while (rs.next()) {
			String tableName = rs.getString("table_name");
			String deleteSql = "drop table "+tableName;
			deleteStatement.execute(deleteSql);
			System.out.println(tableName+"     "+(++num));
		}
		
		
		
	}
}
