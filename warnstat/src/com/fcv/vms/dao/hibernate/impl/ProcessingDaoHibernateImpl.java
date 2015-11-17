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
import org.hibernate.transform.Transformers;
import org.springframework.orm.hibernate3.support.HibernateDaoSupport;

import com.fcv.vms.dao.ProcessingDao;
import com.fcv.vms.pojo.Processing;

public class ProcessingDaoHibernateImpl extends HibernateDaoSupport implements ProcessingDao {

	public void add(Processing proce) {
		Session session = null;
		Transaction tx = null;
		try{
			session = getHibernateTemplate().getSessionFactory().openSession();
			tx = session.beginTransaction();
			session.save(proce);
			tx.commit();
		}catch (Exception e) {
			tx.rollback();
			e.printStackTrace();
		}finally{
			session.close();
		}
	}

	public Processing getProce(String vin) {
		Session session = null;
		Transaction tx = null;
		Processing p = null;
		try{
			String hql = "from Processing p where p.vin=:vin";
			session = getHibernateTemplate().getSessionFactory().openSession();
			tx = session.beginTransaction();
			Query q = session.createQuery(hql);
			q.setParameter("vin", vin);
			p = (Processing) q.uniqueResult();
		}catch (Exception e) {
			tx.rollback();
			e.printStackTrace();
		}finally{
			session.close();
		}
		
		return p;
	}

	
	

	public void update(Processing proce) {
		Session session = null;
		Transaction tx = null;
		try{
			session = getHibernateTemplate().getSessionFactory().openSession();
			tx = session.beginTransaction();
			Processing processing = (Processing) session.createQuery("from Processing where vin = :vin").setString("vin", proce.getVin()).uniqueResult();
			processing.setEndTime(proce.getEndTime());
			tx.commit();
		}catch (Exception e) {
			tx.rollback();
			e.printStackTrace();
		}finally{
			session.close();
		}
	}
	
	public Map<String , Processing> getMap(){
		Map<String, Processing> map = new HashMap<String, Processing>();
		Session session = null;
		Transaction tx = null;
		List<Processing> p = new ArrayList<Processing>();
		try{
			String hql = "from Processing ";
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
			Processing processing = (Processing) iterator.next();
			map.put(processing.getVin(), processing);
		}
		
		return map;
		
	}

	@Override
	public Map<String, Date> getProce() {
		Map<String, Date> result = new HashMap<String, Date>();
		Session session = null;
		Transaction tx = null;
		List<Map<String, Object>> p = new ArrayList<Map<String, Object>>();
		try{
			String sql = "SELECT vin,endtime  FROM processing_warnstat ; ";
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
			result.put((String)map.get("vin"),(Date)map.get("endtime"));
		}
		
		return result;
	}
}
