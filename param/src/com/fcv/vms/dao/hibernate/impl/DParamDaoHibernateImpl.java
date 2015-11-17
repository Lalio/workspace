package com.fcv.vms.dao.hibernate.impl;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;
import java.util.concurrent.ConcurrentHashMap;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import net.sourceforge.pinyin4j.PinyinHelper;

import org.apache.commons.collections.map.HashedMap;
import org.hibernate.Query;
import org.hibernate.Session;
import org.hibernate.Transaction;
import org.hibernate.transform.Transformers;
import org.springframework.orm.hibernate3.support.HibernateDaoSupport;

import com.fcv.vms.dao.DParamDao;
import com.fcv.vms.main.Processer;
import com.fcv.vms.pojo.DParam;

public class DParamDaoHibernateImpl extends HibernateDaoSupport implements
		DParamDao {
	private static Map<String, List<String>> vin_name = new HashedMap();// ����vin��Ӧ�Ĳ����б�
	private static Map<String, List<String>> isMap = new HashedMap();// ����vin��Ӧ������dparamismap
																		// = 1
																		// �б�

	public List<DParam> getDParams(int markID) {

		// String hql ="from DParam where markid=:markId "+
		// "and (dparamname in ('��ص�ѹ','����ܵ�ѹ','��ص���','����ܵ���','����¶�',"+
		// "'�����������ѹ','��������������ѹ','����������¶�','�������������','����������������','����������������','�����ѹ','����¶�','���ת��','���ת��',"+
		// "'������������������',"+
		// "'DCDC�����ѹ','DCDC�������','DCDC�¶�',"+
		// "'����̤��','ǣ��̤��','�ƶ�̤��','������ʵ������','SOC','����','��ŵ�����') "+
		// "or dparamname like '%�ͺ�%')";
		String hql = "from DParam where markid=:markId ";
		Session session = null;
		Transaction tx = null;
		List<DParam> list = null;
		try {
			session = getHibernateTemplate().getSessionFactory().openSession();
			tx = session.beginTransaction();
			Query query = session.createQuery(hql);
			query.setParameter("markId", markID);
			list = query.list();
		} catch (Exception e) {
			tx.rollback();
			e.printStackTrace();
		} finally {
			session.close();
		}
		return list;
	}

	@Override
	public List<DParam> getDParams() {
		Session session = null;
		List<DParam> list = null;
		try {
			session = getHibernateTemplate().getSessionFactory().openSession();
			Query query = session.createQuery("from DParam");
			list = query.list();
		} catch (Exception e) {
			e.printStackTrace();
		} finally {
			session.close();
		}
		for (Iterator iterator = list.iterator(); iterator.hasNext();) {
			DParam dParam = (DParam) iterator.next();
			System.out.println("DParam      " + dParam.getId());
		}
		return list;
	}

	@Override
	public void insertDParams(String vin, String key, String value) {

		Session session = null;
		System.out.println("vin is " + vin + "   had writen "
				+ Processer.write.addAndGet(100)
				+ " avedata ��ready to write 100 paramdata");

		try {
			String tableString = "z_param_" + vin.toLowerCase();
			String sql = "insert into " + tableString + " " + key + " values "
					+ value;
			session = getHibernateTemplate().getSessionFactory().openSession();
			Query query = session.createSQLQuery(sql);
			query.executeUpdate();
		} catch (Exception e) {
			System.out.println(" exception vin is " + vin);
			e.printStackTrace();
		} finally {
			session.close();
		}
	}

	@Override
	public void reset() {

		Map<String, Integer> vinid_map = new ConcurrentHashMap<String, Integer>();

		Session session = null;
		Transaction tx = null;
		List<Map<String, Object>> p = new ArrayList<Map<String, Object>>();
		try {
			String sql = "SELECT m.id,r.vin  FROM mark m LEFT JOIN reg r on m.MarkValue = r.MarkValue where vin is not null";
			session = getHibernateTemplate().getSessionFactory().openSession();
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
			session = getHibernateTemplate().getSessionFactory().openSession();
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
			List<String> nameList;
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

					nameList = vin_name.get(vin);
					if (nameList == null) {
						nameList = new ArrayList<String>();
						vin_name.put(vin, nameList);
					}
					if (dParam.getNamePinYin() != null
							&& !dParam.getNamePinYin().equals("")) {
						String pinyin = dParam.getNamePinYin();
						int sign = 0;// ��־name�����Ƿ��Ѿ�����˸ò���
						for (String string : nameList) {
							if (string.equals(pinyin)) {
								System.out.println(vin
										+ "     the same pinyin :" + string);
								sign = 1;
								break;
							}
						}
						if (sign == 0) {
							nameList.add(pinyin);
						}
					} else {
						System.out.println(vin + "        "
								+ dParam.getDparamName()
								+ "  has no namePinyin");
						nameList.add(dParam.getDparamName());
					}

				}
			}
		}

		Connection con = null;
		try {
			// ��������
			Class.forName("com.mysql.jdbc.Driver");
			// ��������
			con = DriverManager
					.getConnection(
							"jdbc:mysql://cenyanmysql.mysql.rds.aliyuncs.com:3306/vms?useUnicode=true&amp;zeroDateTimeBehavior=convertToNull&amp;characterEncoding=UTF-8",
							"cenyan", "cenyan");
		} catch (Exception e) {
		}

		int number = 0;
		int newNumber = 0;

		for (Iterator iterator = p.iterator(); iterator.hasNext();) {

			Map<String, Object> map = (Map<String, Object>) iterator.next();
			String vin = (String) map.get("VIN");
			try {
				String tableNameString = "z_param_" + vin.toLowerCase();

				Statement exitsStatement = con.createStatement();
				String exitSql = "select * from INFORMATION_SCHEMA.tables where table_name = '"
						+ tableNameString + "'";
				exitsStatement.execute(exitSql);
				if (!exitsStatement.getResultSet().next()) {// ���û�����ű�

				} else {// ���Ѿ�����

					try {
						Statement dropStatement = con.createStatement();
						String sqlString11 = "drop TABLE " + tableNameString;
						dropStatement.execute(sqlString11);
						dropStatement.close();
						System.out.println("drop table " + tableNameString);
					} catch (Exception e) {
						e.printStackTrace();
					}

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

				System.out.println(vin + "    " + tableNameString
						+ "  *************************************  number :"
						+ (++newNumber));

			} catch (Exception e) {
				System.out.println(" exception vin is " + vin);

				e.printStackTrace();
			}

		}
		try {
			con.close();
		} catch (SQLException e) {
			e.printStackTrace();
		}
		vin_name.clear();
		isMap.clear();
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
			if (matcher.find()) {// ƥ������
				String pinyin = PinyinHelper.toHanyuPinyinStringArray(matcher
						.group().toCharArray()[0])[0].replaceAll("\\d+", "")
						.replace("u:", "v");
				sb.append(pinyin);
			} else {
				if (matcher2.find()) {// ƥ����ĸ�����»���

				} else {
					t = "_";
				}
				sb.append(t);
			}
		}

		return sb.toString();
	}
}
