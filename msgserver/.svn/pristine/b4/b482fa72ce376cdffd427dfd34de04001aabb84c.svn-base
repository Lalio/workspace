package com.fcv.vms.server;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.Statement;

import com.fcv.vms.model.HibernateModel;

public class AlertID {
	public static void main(String[] args) throws Exception {

		// 载入驱动
		Class.forName("com.mysql.jdbc.Driver");
		// 建立连接
		Connection con = DriverManager.getConnection(
				"jdbc:mysql://cenyanmysql.mysql.rds.aliyuncs.com:3306/vms?useUnicode=true&amp;zeroDateTimeBehavior=convertToNull&amp;characterEncoding=UTF-8",
				"cenyan", "cenyan");
		Statement stmt = con.createStatement();
		String allvin = "select distinct vin from reg";
		ResultSet resultSet = stmt.executeQuery(allvin);
		while (resultSet.next()) {
			try {
				String vin = resultSet.getString("vin");
				String tableNameString = "z_data_" + vin.toLowerCase();
				Statement exitsStatement = con.createStatement();
				String exitSql = "select * from INFORMATION_SCHEMA.tables where table_name = '" + tableNameString + "'";
				exitsStatement.execute(exitSql);
				if (!exitsStatement.getResultSet().next()) {
					continue;
				} else {
//					Statement createStatement = con.createStatement();
//					String createString = "alter table " + tableNameString
//							+ " modify column id int(11) not null auto_increment, add primary key (id)";
//					createStatement.execute(createString);
//					createStatement.close();
					
					Statement createStatement2 = con.createStatement();
					String createString2 = "select min(id) as minid from " + tableNameString;
							
					ResultSet resultSet2 = createStatement2.executeQuery(createString2);
					resultSet2.next();
					int minid = resultSet2.getInt("minid");
					if(minid <= 0)
						System.out.println(tableNameString);
					createStatement2.close();
					
				}

				exitsStatement.close();

			} catch (Exception e) {
				e.printStackTrace();
			}

		}

		stmt.close();
		con.close();
		System.exit(0);
	}

}
