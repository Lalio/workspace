package com.fcv.vms.server;

import java.io.File;
import java.io.FileOutputStream;
import java.io.PrintStream;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import org.hibernate.Query;
import org.hibernate.Session;
import org.hibernate.Transaction;

import com.fcv.vms.dao.*;
import com.fcv.vms.model.HibernateSessionFactory;
import com.sun.org.apache.bcel.internal.generic.NEW;

public class DataTableSpilt2 {
	public static void main(String[] args) throws Exception {
		 File f=new File("c:/out.txt");
		 f.createNewFile();
		 FileOutputStream fileOutputStream = new FileOutputStream(f);
		 PrintStream printStream = new PrintStream(fileOutputStream);
		 System.setOut(printStream);
		Transaction tx = null;
		Session session = HibernateSessionFactory.getSession();
		Query query = null;
       
//        HibernateModel.createRegTable();
        // 载入驱动
        Class.forName("com.mysql.jdbc.Driver");
        // 建立连接
        Connection con = DriverManager
                .getConnection(
                        "jdbc:mysql://cenyanmysql.mysql.rds.aliyuncs.com:3306/vms?useUnicode=true&amp;zeroDateTimeBehavior=convertToNull&amp;characterEncoding=UTF-8",
                        "cenyan", "cenyan");
        // 创建状态
        Statement stmt = con.createStatement();
//   删除所有子表    
//        String sqlString  = "select * from INFORMATION_SCHEMA.tables where table_name like 'z_data_%'";
//        ResultSet  rSet = stmt .executeQuery(sqlString);
//        while(rSet.next()){
//        	String sql = "drop table "+rSet.getString("table_name");
//        	Statement stmt2 = con.createStatement();
//        	stmt2.execute(sql);
//        	System.out.println("删除了："+rSet.getString("table_name"));
//        	
//        }

		StringBuilder sb = new StringBuilder();

		long max = 1087100159;// 十亿多，最大的id
		long per = 1000000;// 一百万
		long num = 40000000;
		long id = 0;
		while (num < max) {
			// System.out.println(new Date()+ "    "+num);
			// String allvin =
			// "DELETE from data where (id > "+num+" and id < "+(num+5000000)+") and (length(vin) <> 17 or vin not REGEXP '^[A-Za-z0-9]+$')";
			// num = num + 5000000;
			// stmt.execute(allvin);
			// }
			// }
			// Statement s = con.createStatement();
			// String sql = "SELECT * FROM Data  where id > " + num
			// + "   ORDER BY id limit 0," + per + "";
			// ResultSet rs = s.executeQuery(sql);
			// Map<String, List<Data>> map = new HashMap<String, List<Data>>();
			// while (rs.next()) {
			// Data data = new Data();
			// String vin;
			// data.setId(rs.getInt("ID"));
			// data.setDatas(rs.getBytes("Datas"));
			// data.setLocateDirection((float) rs.getDouble("LocateDirection"));
			// data.setLocateLatitude(rs.getInt("LocateIsValid"));
			// data.setLocateLatitude((float) rs.getDouble("LocateLatitude"));
			// data.setLocateLongitude((float) rs.getDouble("LocateLongitude"));
			// data.setLocateSpeed((float) rs.getDouble("LocateSpeed"));
			// data.setLocateTime(rs.getDate("LocateTime"));
			// data.setSn(rs.getInt("SN"));
			// data.setTime(rs.getDate("Time"));
			// vin = rs.getString("VIN");
			// data.setVin(vin);
			// List<Data> l = map.get(vin);
			// if (l == null) {
			// l = new ArrayList<Data>();
			// map.put(vin, l);
			// }
			// l.add(data);
			// }
			// rs.close();

			// hibernate拿出来的数据并不是想要的按id查询出的前几位。
			List<Data> list = new ArrayList<Data>();
			String hql = "SELECT * FROM Data  where id > " + num
					+ "   ORDER BY id limit 0," + per + "";
			try {
//				session = HibernateSessionFactory.getSession();
				tx = session.beginTransaction();
				list = session.createSQLQuery(hql).addEntity(Data.class).list();
				tx.commit();
			} catch (Exception e) {
				tx.rollback();
				list = new ArrayList<Data>();
				e.printStackTrace();
			} finally {
//				session.close();
			}

			Map<String, List<Data>> map = new HashMap<String, List<Data>>();
			for (Data data : list) {
				String vin = data.getVin();
				List<Data> l = map.get(vin);
				if (l == null) {
					l = new ArrayList<Data>();
					map.put(vin, l);
				}
				l.add(data);
			}
			list = null;

			String allvin = "SELECT max(id) as id,vin from  (select * from data where id > "
					+ num
					+ "  ORDER BY id  limit 0,"
					+ per
					+ ") as data  group by vin ";
			// String allvin = "select distinct(vin) from data";
			ResultSet resultSet = stmt.executeQuery(allvin);
			while (resultSet.next()) {
				try {
					String vin = resultSet.getString("vin");

					if (!vin.matches("^[A-Za-z0-9]+$") || vin.length() != 17) {// 判断是否为乱码
						int t = resultSet.getInt("id");
						id = id > t ? id : t;
						continue;
					}

					// HibernateModel model= new HibernateModel();
					// model.createTable(vin);
					String tableNameString = "z_data_" + vin.toLowerCase();
					Statement exitsStatement = con.createStatement();
					String exitSql = "select * from INFORMATION_SCHEMA.tables where table_name = '"
							+ tableNameString + "'";
					exitsStatement.execute(exitSql);
					if (!exitsStatement.getResultSet().next()) {
						// create
						System.out.println("创建了表" + tableNameString);
						Statement createStatement = con.createStatement();
						String sqlString = "CREATE TABLE " + tableNameString
								+ " select * from data WHERE 1=2";
						createStatement.execute(sqlString);
						createStatement.close();

						// Statement Statement = con.createStatement();
						// String updateString =
						// "UPDATE reg SET has_data_table = 1 WHERE vin = '"
						// + vin + "'";
						// Statement.execute(updateString);
						// Statement.close();
					} else {
						// // drop
						// Statement dropStatement = con.createStatement();
						// String sqlString = "drop TABLE " + tableNameString;
						// dropStatement.execute(sqlString);
						// dropStatement.close();
						//
						// // create
						// Statement createStatement = con.createStatement();
						// String createString = "CREATE TABLE " +
						// tableNameString
						// + " select * from data WHERE 1=2";
						// createStatement.execute(createString);
						// createStatement.close();
					}

					exitsStatement.close();

					// Statement updateStatement = con.createStatement();
					// String updateSql = "INSERT INTO " + tableNameString
					// +
					// " SELECT * FROM (select * from data where id > "+num+" limit 0,"+per+") as data WHERE data.vin='"
					// + vin
					// + "' ";
					// updateStatement.execute(updateSql);
					// updateStatement.close();
					List<Data> l = map.get(vin);

					for (Data data : l) {
						sb.append(data.toString() + ",");
					}
					sb = sb.delete(sb.length() - 1, sb.length());

					String insertSql = "INSERT INTO "
							+ tableNameString
							+ " (ID,SN,VIN,Time,Datas,LocateIsValid,LocateTime,LocateLongitude,LocateLatitude,LocateSpeed,LocateDirection) VALUES "
							+ sb.toString();
					sb.delete(0, sb.length());
					int size = l.size();
					try {
						tx = session.beginTransaction();

						query = session.createSQLQuery(insertSql);

						for (int i = 0; i < size; i++) {
							query.setBinary(i, l.get(i).getDatas());
						}
						query.executeUpdate();
						tx.commit();
					} catch (Exception e) {
						tx.rollback();
						e.printStackTrace();
					} finally {
						// session.close();
					}
					int t = resultSet.getInt("id");
					id = id > t ? id : t;

//					System.out.println(new Date() + "  num is " + num
//							+ "   id is :" + id + "     tablename is "
//							+ tableNameString);

				} catch (Exception e) {
					e.printStackTrace();
				}

			}
			resultSet.close();
			
			System.out.println("run out of "+new Date()+ " .   num is " + num
					+ "   id is :" + id );
			
			num = id;
		

			// 删除data表里的所有数据
			// Statement createStatement = con.createStatement();
			// String sqlString = "DELETE FROM data WHERE 1 = 1";
			// createStatement.execute(sqlString);
			// createStatement.close();

			// stmt.close();
			// con.close();
		}
	}

	public static long getUnsignedInt(int x) {
		return x & 0x00000000ffffffffL;
	}

}
