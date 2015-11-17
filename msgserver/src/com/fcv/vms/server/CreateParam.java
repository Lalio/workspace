package com.fcv.vms.server;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.HashSet;
import java.util.Iterator;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;
import java.util.Set;
import java.util.concurrent.ConcurrentHashMap;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import org.apache.commons.collections.map.HashedMap;
import org.hibernate.Session;
import org.hibernate.Transaction;
import org.hibernate.transform.Transformers;

import net.sourceforge.pinyin4j.PinyinHelper;

import com.fcv.vms.model.HibernateSessionFactory;
import com.fcv.vms.dao.DParam;

/*
 * 创建z_param 表
 */

public class CreateParam {
	private static Map<String, List<String>> vin_name = new HashedMap();// 车辆vin对应的参数列表
	private static Map<String, List<String>> isMap = new HashedMap();// 车辆vin对应参数是dparamismap
																		// = 1
																		// 列表

	public static void main(String[] args) throws Exception {
		Map<String, Integer> vinid_map = new ConcurrentHashMap<String, Integer>();

		Session session = null;
		Transaction tx = null;
		List<Map<String, Object>> p = new ArrayList<Map<String, Object>>();
		try {
			String sql = "SELECT m.id,r.vin  FROM mark m LEFT JOIN reg r on m.MarkValue = r.MarkValue where vin is not null";
			session = HibernateSessionFactory.getSession();
			tx = session.beginTransaction();
			p = session.createSQLQuery(sql)
					.setResultTransformer(Transformers.ALIAS_TO_ENTITY_MAP)
					.list();
		} catch (Exception e) {
			tx.rollback();
			e.printStackTrace();
		} finally {
			session.close();
		}

		for (Iterator iterator = p.iterator(); iterator.hasNext();) {
			Map<String, Object> map = (Map<String, Object>) iterator.next();
			vinid_map.put((String) map.get("VIN"), (Integer) map.get("ID"));
		}

		List<DParam> list = new ArrayList<DParam>();
		String hql = "SELECT * FROM DParam ";
		try {
			session = HibernateSessionFactory.getSession();
			tx = session.beginTransaction();
			list = session.createSQLQuery(hql).addEntity(DParam.class).list();
			tx.commit();
		} catch (Exception e) {
			tx.rollback();
			list = new ArrayList<DParam>();
			e.printStackTrace();
		} finally {
			session.close();
		}

		for (Entry<String, Integer> entry : vinid_map.entrySet()) {
			List<String> name;
			List<String> isMapName;
			String vin = entry.getKey();
			int id = entry.getValue();
			for (DParam dParam : list) {
				if (id == dParam.getMarkId()) {
					if (dParam.getDparamIsMap() == 1) {
						isMapName = isMap.get(vin);
						if (isMapName == null) {
							isMapName = new ArrayList<String>();
							isMap.put(vin, isMapName);
						}
						if (dParam.getNamePinYin() != null
								&& !dParam.getNamePinYin().equals("")) {
							isMapName.add(dParam.getNamePinYin());
						} else {
							isMapName.add(dParam.getDparamName());
						}

					}

					name = vin_name.get(vin);
					if (name == null) {
						name = new ArrayList<String>();
						vin_name.put(vin, name);
					}
					if (dParam.getNamePinYin() != null
							&& !dParam.getNamePinYin().equals("")) {
						String pinyin = dParam.getNamePinYin();
						int sign = 0;// 标志name里面是否已经存放了该参数
						for (String string : name) {
							if (string.equals(pinyin)) {
								System.out
										.println("the same pinyin :" + string);
								sign = 1;
								break;
							}
						}
						if (sign == 0) {
							name.add(dParam.getNamePinYin());
						}
					} else {
						name.add(dParam.getDparamName());
					}

				}
			}
		}

		// 载入驱动
		Class.forName("com.mysql.jdbc.Driver");
		// 建立连接
		Connection con = DriverManager
				.getConnection(
						"jdbc:mysql://cenyanmysql.mysql.rds.aliyuncs.com:3306/vms?useUnicode=true&amp;zeroDateTimeBehavior=convertToNull&amp;characterEncoding=UTF-8",
						"cenyan", "cenyan");
		int number = 0;
		int newNumber = 0;

		for (Iterator iterator = p.iterator(); iterator.hasNext();) {
			try {
				Map<String, Object> map = (Map<String, Object>) iterator.next();
				String vin = (String) map.get("VIN");
				String tableNameString = "z_param_" + vin.toLowerCase();

				Statement exitsStatement = con.createStatement();
				String exitSql = "select * from INFORMATION_SCHEMA.tables where table_name = '"
						+ tableNameString + "'";
				exitsStatement.execute(exitSql);
				if (!exitsStatement.getResultSet().next()) {// 如果没有这张表
					// try {
					// Statement dropStatement = con.createStatement();
					// String sqlString11 = "drop TABLE " + tableNameString;
					// dropStatement.execute(sqlString11);
					// dropStatement.close();
					// } catch (Exception e) {
					// continue;
					// }
					List<String> name = vin_name.get(vin);
					List<String> isMapName = isMap.get(vin);
					StringBuilder sqlString = new StringBuilder("");
					int sign = 0;
					Map<String, String> pingyin_map = new HashMap<String, String>();
					if (name != null) {
						for (String string : name) {
							sign = 0;
							String s = pinyin(string);
							pingyin_map.put(string, s);

							if (isMapName != null) {
								for (String string2 : isMapName) {
									if (string2.equals(string)
											|| pinyin(string2).equals(s)) {
										sqlString.append(s + " varchar(20), ");
										sign = 1;
										continue;
									}
								}
							}

							if (sign == 0) {
								sqlString.append(s + " float, ");
							}
						}
					}
					String sql = "CREATE TABLE "
							+ tableNameString
							+ " (  `ID` int(11) NOT NULL AUTO_INCREMENT, SN int(11), VIN varchar(20), Time datetime, LocateIsValid int(11), LocateTime datetime, LocateLongitude double, LocateLatitude double, LocateSpeed double, LocateDirection double, "
							+ sqlString + "  PRIMARY KEY (`ID`)  ) ";
					Statement createStatement = con.createStatement();
					createStatement.execute(sql);
					createStatement.close();

					Statement Statement = con.createStatement();
					String updateString = "UPDATE reg SET has_param_table = 1 WHERE vin = '"
							+ vin + "'";
					Statement.execute(updateString);
					Statement.close();

					System.out
							.println(vin
									+ "    "
									+ tableNameString
									+ "  *************************************  number :"
									+ (++newNumber));
				} else {// 表已经存在

					// Map<String, String> m = new HashMap<String, String>();
					// String sql =
					// "select COLUMN_NAME from information_schema.COLUMNS where table_name = '"
					// + tableNameString + "'; ";
					// Statement createStatement = con.createStatement();
					// ResultSet rs = createStatement.executeQuery(sql);
					// while (rs.next()) {
					// m.put(rs.getString("COLUMN_NAME").toLowerCase(), "");
					// }
					// createStatement.close();
					//
					// List<String> name = vin_name.get(vin);
					// List<String> isMapName = isMap.get(vin);
					// StringBuilder sqlString = new StringBuilder("");
					// int sign = 0;
					// Map<String, String> pingyin_map = new HashMap<String,
					// String>();
					// if (name != null) {
					// for (String string : name) {
					// sign = 0;
					// String s = pinyin(string).toLowerCase();
					// pingyin_map.put(string, s);
					// // if (m.get(s) == null) {
					//
					//
					// if (isMapName != null) {
					// for (String string2 : isMapName) {
					// if (string2.equals(string)
					// || pinyin(string2).equals(s)) {
					// sqlString.append(" change " + s + " " + s
					// + " varchar(20), ");
					// sign = 1;
					// continue;
					// }
					// }
					// }
					//
					// if (sign == 0) {
					// sqlString.append(" change " + s+" " + s + " float, ");
					// }
					//
					// // }
					// }
					// if (sqlString.length() > 2) {
					// sqlString = sqlString.delete(
					// sqlString.length() - 2,
					// sqlString.length() - 1);
					//
					// String alter = " alter table " + tableNameString
					// +sqlString ;
					// Statement alterStatement = con.createStatement();
					// alterStatement.execute(alter);
					// alterStatement.close();
					// }
					// }
					//
					// System.out.println(vin + "    " + tableNameString
					// + "  exit  number :" + (++number));
				}

				exitsStatement.close();

			} catch (Exception e) {
				e.printStackTrace();
			}
			con.close();
		}
		System.out.println("OVER!!");

	}

	public static long getUnsignedInt(int x) {
		return x & 0x00000000ffffffffL;
	}

	public static String pinyin(String string) {
		Pattern pattern = Pattern.compile("[\\u4e00-\\u9fa5]");
		Pattern pattern2 = Pattern.compile("^\\w+$");
		char ch[] = string.toCharArray();
		StringBuffer sb = new StringBuffer();

		for (int i = 0; i < ch.length; i++) {
			String t = String.valueOf(ch[i]);
			Matcher matcher = pattern.matcher(t);
			Matcher matcher2 = pattern2.matcher(t);
			if (matcher.find()) {// 匹配中文
				String pinyin = PinyinHelper.toHanyuPinyinStringArray(matcher
						.group().toCharArray()[0])[0].replaceAll("\\d+", "")
						.replace("u:", "v");
				sb.append(pinyin);
			} else {
				if (matcher2.find()) {// 匹配字母数字下划线

				} else {
					t = "_";
				}
				sb.append(t);
			}
		}

		return sb.toString();
	}
}
