package com.fcv.vms.dao.hibernate.impl;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;

import org.hibernate.Query;
import org.hibernate.Session;
import org.hibernate.Transaction;
import org.hibernate.transform.Transformers;
import org.springframework.orm.hibernate3.support.HibernateDaoSupport;

import com.fcv.vms.dao.DataDao;
import com.fcv.vms.main.Processer;
import com.fcv.vms.pojo.AveData;
import com.fcv.vms.pojo.Data;

public class DataDaoHibernateImpl extends HibernateDaoSupport implements
		DataDao {
	public String tableNameString = "data ";

	public List<AveData> getDatas(String vin, String stime, String etime) {
//		String tableNameString = "z_data_" + vin.toLowerCase();
//		final String sql = "select * from " + tableNameString
//				+ "  where  time > '" + stime
//				+ "' and time < '" + etime
//				+ "' order by time";
		final String sql = "select * from " + tableNameString
				+ "  where vin = '"+vin+"' and  time >= '" + stime
				+ "' and time <= '" + etime
				+ "' order by time";

		Session session = null;
		Transaction tx = null;
		List<AveData> list = null;
		try {
			session = getHibernateTemplate().getSessionFactory().openSession();
			tx = session.beginTransaction();
			list = session.createSQLQuery(sql).addEntity(AveData.class).list();
			tx.commit();
		} catch (Exception e) {
			tx.rollback();
			list = new ArrayList<AveData>();
			e.printStackTrace();
		} finally {
			session.close();
		}

		return list;
	}

	/**
	 * 获取表中第一个有效数据的时间
	 */
	@Override
	public Date getFirstDataTime(String vin) {
		String tableNameString = "z_data_" + vin.toLowerCase();
		Session session = null;
		Date time = null;
		try {
//			String tableNameString = "z_data_" + vin.toLowerCase();
//			String sql = "SELECT time FROM " + tableNameString
//					+ " order by time asc limit 1";
			String sql = "SELECT time FROM " + tableNameString
					+ " where time >= '2015-07-20 00:00:00' order by time asc limit 1";

			session = getHibernateTemplate().getSessionFactory().openSession();
			Query query = session.createSQLQuery(sql);
			time = (Date) query.uniqueResult();

		} catch (Exception e) {
			e.printStackTrace();
		} finally {
			if (session != null) {
				session.close();
			}
		}

		return time;
	}

	/**
	 * 获取今天的有效时间的最大值
	 */
	@Override
	public Date getTodayMax(String vin,Date today) {
		Date date = new Date(today.getTime()); 
		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd");
		date.setDate(date.getDate()+1);
		Session session = null;
		Date time = null;
		try {
//			String tableNameString = "z_data_" + vin.toLowerCase();
//			String sql = "SELECT time FROM " + tableNameString
//					+ " where  time < '"+sdf.format(date)+"' order by time desc limit 1";
			String sql = "SELECT time FROM " + tableNameString
					+ " where vin = '"+vin+"' and time < '"+sdf.format(date)+"' order by time desc limit 1";

			session = getHibernateTemplate().getSessionFactory().openSession();
			Query query = session.createSQLQuery(sql);
			time = (Date) query.uniqueResult();

		} catch (Exception e) {
			e.printStackTrace();
		} finally {
			if (session != null) {
				session.close();
			}
		}

		return time;
	}

	/**
	 * 暂时没有用
	 */
	@Override
	public Date getNextMin(String vin, Date today) {
		Date date = new Date(today.getTime()); 
		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd");
		date.setDate(date.getDate()+1);
		Session session = null;
		Date time = null;
		try {
			String tableNameString = "z_data_" + vin.toLowerCase();
			String sql = "SELECT time FROM " + tableNameString
					+ " where time > '"+sdf.format(date)+"' order by time asc limit 1";

			session = getHibernateTemplate().getSessionFactory().openSession();
			Query query = session.createSQLQuery(sql);
			time = (Date) query.uniqueResult();

		} catch (Exception e) {
			e.printStackTrace();
		} finally {
			if (session != null) {
				session.close();
			}
		}

		return time;
	}

	@Override
	public Date getNextStime(String vin, Date stime) {
		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		Session session = null;
		Date time = null;
		try {
//			String tableNameString = "z_data_" + vin.toLowerCase();
//			String sql = "SELECT time FROM " + tableNameString
//					+ " where time > '"+sdf.format(stime)+"' order by time asc limit 1";
			String sql = "SELECT time FROM " + tableNameString
					+ " where vin = '"+vin+"' and time > '"+sdf.format(stime)+"' order by time asc limit 1";

			session = getHibernateTemplate().getSessionFactory().openSession();
			Query query = session.createSQLQuery(sql);
			time = (Date) query.uniqueResult();

		} catch (Exception e) {
			e.printStackTrace();
		} finally {
			if (session != null) {
				session.close();
			}
		}

		return time;
	}

	@Override
	public void getMaxTime() {
		Session session = null;
		Transaction tx = null;
		List<Map<String, Object>> p = new ArrayList<Map<String, Object>>();
		try{
			String sql = "SELECT vin,max(lastTime) as lastTime FROM data  GROUP BY vin; ";
			session = getHibernateTemplate().getSessionFactory().openSession();
			tx = session.beginTransaction();
			p = session.createSQLQuery(sql).setResultTransformer(Transformers.ALIAS_TO_ENTITY_MAP).list();
		}catch (Exception e) {
			tx.rollback();
			e.printStackTrace();
		}finally{
			session.close();
		}
		
		for (Iterator iterator = p.iterator(); iterator.hasNext();) {
			Map<String, Object> map =  (Map<String, Object>) iterator.next();
			Processer.maxtime_map.put((String)map.get("vin"),(Date)map.get("lastTime"));
		}
		
		return ;
	}

	@Override
	public List<String> getVin() {
//		final String sql = "select distinct(vin) from " + tableNameString +" where time >= '2015-07-20 00:00:00'";
		final String sql = "select distinct(vin) from reg ";

		Session session = null;
		Transaction tx = null;
		List<String> list = null;
		try {
			session = getHibernateTemplate().getSessionFactory().openSession();
			tx = session.beginTransaction();
			list = session.createSQLQuery(sql).list();
			tx.commit();
		} catch (Exception e) {
			tx.rollback();
			list = new ArrayList<String>();
			e.printStackTrace();
		} finally {
			session.close();
		}

		return list;
	}

	@Override
	public void getAllDatas(long begin,long end) {
		int num = 0;
		final String sql = "select * from " + tableNameString + "  order by time asc limit "+begin+","+end;

		Session session = null;
		Transaction tx = null;
		List<AveData> list = new ArrayList<AveData>();
		try {
			session = getHibernateTemplate().getSessionFactory().openSession();
			tx = session.beginTransaction();
			list = session.createSQLQuery(sql).addEntity(AveData.class).list();
			tx.commit();
		} catch (Exception e) {
			tx.rollback();
			list = new ArrayList<AveData>();
			e.printStackTrace();
		} finally {
			session.close();
		}
		Processer.come.addAndGet(list.size());
		System.err.println("这次在"+begin+"和"+end+"之间拿了"+list.size()+"条数据,现在一共拿了"+Processer.come+"条数据");
		for (AveData aveData : list) {
			Date date = aveData.getStartTime();
			String vin = aveData.getVin();
			List<AveData> list2 = Processer.all.get(vin.toLowerCase());
			if (list2 == null) {
				list2 = new ArrayList<AveData>();
				Processer.all.put(vin.toLowerCase(), list2);
				num++;
			} 	
			list2.add(aveData);
		}
		System.out.println("取出来的vin数量"+num);
		
		return ;
	}
	
	@Override
	public void getAllDatas(String vinString) {
		String tableNameString = "z_data_" + vinString.toLowerCase();
		List<Data> result = new ArrayList<Data>();
		int num = 0;
		final String sql = "select * from " + tableNameString + " order by time asc ";

		Session session = null;
		Transaction tx = null;
		List<Data> list = new ArrayList<Data>();
		try {
			session = getHibernateTemplate().getSessionFactory().openSession();
			tx = session.beginTransaction();
			list = session.createSQLQuery(sql).addEntity(Data.class).list();
			tx.commit();
		} catch (Exception e) {
			tx.rollback();
			list = new ArrayList<Data>();
			e.printStackTrace();
		} finally {
			session.close();
		}
		Processer.come.addAndGet(list.size());
		System.err.println(vinString+" got "+list.size()+" datas now  , has got "+Processer.come.get()+" at all");
		for (Data data : list) {
			result.add(data);
		}
//		for (AveData aveData : list) {
//			Date date = aveData.getStartTime();
//			String vin = aveData.getVin();
//			List<AveData> list2 = Processer.all.get(vin.toLowerCase());
//			if (list2 == null) {
//				list2 = new ArrayList<AveData>();
//				Processer.all.put(vin.toLowerCase(), list2);
//				num++;
//			} 	
//			list2.add(aveData);
//		}
		
		return ;
	}
	
	@Override
	public List<Data> getAllDatas(String vinString,Date ...dates) {
		String tableNameString = "z_data_" + vinString.toLowerCase();
			
		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		List<Data> result = new ArrayList<Data>();
	
		Date begin = dates[0];
		String sql = "select * from " + tableNameString + " where  time >= '"+sdf.format(begin)+"'  order by time asc ";

		if (dates.length > 1) {
			Date end = dates[1];
			sql = "select * from " + tableNameString + " where time >= '"+sdf.format(begin)+"' and time < '"+sdf.format(end)+"' order by time asc ";
		}

		Session session = null;
		Transaction tx = null;
		List<Data> list = new ArrayList<Data>();
		try {
			session = getHibernateTemplate().getSessionFactory().openSession();
			tx = session.beginTransaction();
			list = session.createSQLQuery(sql).addEntity(Data.class).list();
			tx.commit();
		} catch (Exception e) {
			tx.rollback();
			list = new ArrayList<Data>();
			e.printStackTrace();
		} finally {
			session.close();
		}
		Processer.come.addAndGet(list.size());
		System.err.println(vinString+" in "+sdf.format(begin)+" got "+list.size()+" datas now  , has got "+Processer.come.get()+" at all");
		for (Data data : list) {
			result.add(data);
		}
		
		return result;
	}

	@Override
	public Map<String, Date> getLastTime() {
		Map<String, Date> result = new HashMap<String, Date>();
		Session session = null;
		Transaction tx = null;
		List<Map<String, Object>> p = new ArrayList<Map<String, Object>>();
		try{
			String sql = "SELECT vin,max(lastTime) as lastTime FROM avedata_1_min GROUP BY vin; ";
			session = getHibernateTemplate().getSessionFactory().openSession();
			tx = session.beginTransaction();
			p = session.createSQLQuery(sql).setResultTransformer(Transformers.ALIAS_TO_ENTITY_MAP).list();
		}catch (Exception e) {
			tx.rollback();
			e.printStackTrace();
		}finally{
			session.close();
		}
		
		for (Iterator iterator = p.iterator(); iterator.hasNext();) {
			Map<String, Object> map =  (Map<String, Object>) iterator.next();
			result.put((String)map.get("vin"),(Date)map.get("lastTime"));
		}
		
		return result;
	}

}
