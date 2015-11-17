package com.fcv.vms.dao.hibernate.impl;


import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;

import org.hibernate.Query;
import org.hibernate.Session;
import org.hibernate.Transaction;
import org.springframework.orm.hibernate3.support.HibernateDaoSupport;

import com.fcv.vms.dao.StatusDao;
import com.fcv.vms.pojo.Processing;
import com.fcv.vms.pojo.Status;

public class StatusDaoHibernateImpl extends HibernateDaoSupport implements StatusDao {

	
	@Override
	public int getMarkValueByVin(String vin) {
//		System.out.println("status------getMarkValueByVin");
		String hql = "select s.markValue from Status s where s.vin=:vin";
		Session session = null;
		Transaction tx = null;
		int value = 0;
		try{
			session = getHibernateTemplate().getSessionFactory().openSession();
			tx = session.beginTransaction();
			Query q = session.createQuery(hql);
			q.setParameter("vin", vin);
			value = (Integer) q.uniqueResult();
			
		}catch (Exception e) {
			tx.rollback();
			e.printStackTrace();
		}finally{
			session.close();
		}
//		System.out.println("value   "+value);
		return value;
	}

	@Override
	public Map<String, Date> getMap() {
		Map<String, Date> map = new HashMap<String, Date>();
		Session session = null;
		Transaction tx = null;
		List<Status> p = new ArrayList<Status>();
		try{
			String hql = "from Status ";
			session = getHibernateTemplate().getSessionFactory().openSession();
			tx = session.beginTransaction();
			Query q = session.createQuery(hql);
			p = q.list();
		}catch (Exception e) {
			tx.rollback();
			e.printStackTrace();
		}finally{
			session.close();
		}
		
		for (Iterator iterator = p.iterator(); iterator.hasNext();) {
			Status status = (Status) iterator.next();
			map.put(status.getVin(), status.getTime());
		}
		
		return map;
	}

}
