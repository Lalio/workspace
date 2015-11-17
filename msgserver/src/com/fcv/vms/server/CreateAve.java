package com.fcv.vms.server;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.Statement;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import org.hibernate.Session;
import org.hibernate.Transaction;

import com.fcv.vms.model.HibernateModel;


public class CreateAve  {

	public static void main(String[] args) throws Exception {
		Session session = null;
		Transaction transaction = null;
		
		Class.forName("com.mysql.jdbc.Driver");
		// 建立连接
		Connection con = DriverManager
				.getConnection(
						"jdbc:mysql://cenyanmysql.mysql.rds.aliyuncs.com:3306/vms?useUnicode=true&amp;zeroDateTimeBehavior=convertToNull&amp;characterEncoding=UTF-8",
						"cenyan", "cenyan");

		Statement vinStatement = con.createStatement();
		String vinSql = "SELECT vin FROM reg";
		ResultSet rs = vinStatement.executeQuery(vinSql);
		
		Statement createStatement = con.createStatement();
		Statement zhujianStatement = con.createStatement();
		Statement keyStatement = con.createStatement();
		Statement autoStatement = con.createStatement();
		
		int num = 0;
		String regex = "^.{15}V\\d{1}$";
		while (rs.next()) {
			String vin = rs.getString("vin");
			if (vin.matches(regex)) {//合法的VIN是17位的并且V+数字结尾。用正则表达式过滤掉垃圾数据
				String tableName = "z_ave_"+vin.toLowerCase();
				
				String createSql = "CREATE TABLE "+ tableName + " SELECT * FROM z_ave where 1 = 2";
				createStatement.execute(createSql);

				String zhujianSql = "ALTER TABLE "+tableName+" CHANGE ID ID int(11) primary key not null auto_increment ";
				zhujianStatement.execute(zhujianSql);
				
				System.out.println(vin+"      " +(++num));
			}
			
//			String keySql = "ALTER TABLE "+ tableName + " add primary key (ID)";
//			keyStatement.execute(keySql);
//			
//			String autoSql = "ALTER TABLE "+ tableName + " change id id int auto_increment";
//			autoStatement.execute(autoSql);
			
		}
		
	}
	

}
