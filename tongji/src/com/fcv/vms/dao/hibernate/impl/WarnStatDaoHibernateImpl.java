package com.fcv.vms.dao.hibernate.impl;



import java.math.BigInteger;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import org.hibernate.Query;
import org.hibernate.Session;
import org.hibernate.Transaction;
import org.springframework.orm.hibernate3.support.HibernateDaoSupport;

import com.fcv.vms.dao.WarnStatDao;
import com.fcv.vms.main.Processer;
import com.fcv.vms.pojo.WarnStat;

public class WarnStatDaoHibernateImpl extends HibernateDaoSupport implements WarnStatDao {

	
	public void add(List<WarnStat> list) {
//		System.out.println("Warn------save");
		Session session =null;
		Transaction tx = null;
		int size = list.size();
		System.out.println("had writen "+Processer.warncount.addAndGet(size)+" warndata,ready to write "+size+" warndata");
		try{
			session = getHibernateTemplate().getSessionFactory().openSession();
			tx = session.beginTransaction();
			for(int i = 0;  i < size; i++){
				session.save(list.get(i));
				if(i%100 == 0){
					session.flush();
					session.clear();
				}
			}
			tx.commit();
		}catch (Exception e) {
			tx.rollback();
			e.printStackTrace();
		}finally{
			session.close();
		}
	}


	public List<WarnStat> getWarnStat(String vin,Date ...dates) {
		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		
		Date stime = dates[0];
		String sql = "SELECT * FROM warnstat_new where vin = '"+vin+"' and isover = 1 starttime > '" + sdf.format(stime)
				+ "' order by starttime";
		if (dates.length > 1) {
			Date etime = dates[1];
			sql = "SELECT * FROM warnstat_new where vin = '"+vin+"' and isover = 1 and starttime > '" + sdf.format(stime)
					+ "' and starttime < '" + sdf.format(etime)
					+ "' order by starttime";
		}
		Session session =null;
		Transaction tx = null;
		List<WarnStat> list = new ArrayList<WarnStat>();
		try{
			session = getHibernateTemplate().getSessionFactory().openSession();
			tx = session.beginTransaction();
			list = session.createSQLQuery(sql).addEntity(WarnStat.class).list();
		}catch (Exception e) {
			tx.rollback();
			e.printStackTrace();
		}finally{
			session.close();
		}
//		System.out.println("WarnStat   "+w);
		return list;
	}


	/**
	 *暂时没有用到这个方法 
	 */
	public void update(WarnStat warnStat) {
//		System.out.println("Warn------update");
		Session session =null;
		Transaction tx = null;
		try{
			session = getHibernateTemplate().getSessionFactory().openSession();
			tx = session.beginTransaction();
			session.update(warnStat);
			tx.commit();
		}catch (Exception e) {
			tx.rollback();
			e.printStackTrace();
		}finally{
			session.close();
		}
	}


	@Override
	public boolean isHave(String vin, Date time,int dParamID) {
//		System.out.println("Warn------isHave");
		String hql = "from WarnStat w where w.vin=:vin and w.stime =:stime and DPARAMID = :dParamID";
		Session session =null;
		Transaction tx = null;
		boolean result = true;
		try{
			session = getHibernateTemplate().getSessionFactory().openSession();
			tx = session.beginTransaction();
			Query q = session.createQuery(hql);
			q.setParameter("vin", vin);
			q.setParameter("stime", new SimpleDateFormat("yyyy-MM-dd HH:mm:ss").format(time));
			q.setParameter("dParamID", dParamID);
			Object obj = q.uniqueResult();
			if(obj != null){
				result = false;
			}
		}catch (Exception e) {
			tx.rollback();
			e.printStackTrace();
		}finally{
			session.close();
		}
//		System.err.println("isHave    "+result);
		return result;
	}

	@Override
	public int getWarnNum(String vin, Date today2, long gap) {

		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		Date begintime = new Date();
		try {
			begintime = sdf.parse(sdf.format(today2));
		} catch (ParseException e1) {
			// TODO Auto-generated catch block
			e1.printStackTrace();
		}
		Date endtime = new Date(begintime.getTime()+ gap); 
		String sql = "SELECT count(*) as num FROM warnstat_1_min_test where vin = '"+vin+"'  and  stime >= '" + sdf.format(begintime)
				+ "' and stime <= '" + sdf.format(endtime)
				+ "' ";
		Session session =null;
		Transaction tx = null;
		int warnNum = 0 ;
		try{
			session = getHibernateTemplate().getSessionFactory().openSession();
			tx = session.beginTransaction();
			Query q = session.createSQLQuery(sql);
			warnNum =  ((BigInteger) q.uniqueResult()).intValue();
		}catch (Exception e) {
			tx.rollback();
			e.printStackTrace();
		}finally{
			session.close();
		}
		return warnNum;
	}

}
