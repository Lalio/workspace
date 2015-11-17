package com.fcv.vms.dao.hibernate.impl;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Date;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;
import java.util.Vector;

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
	
	
	@Override
	public Map<String, List<Processing>> getProce() {
		Map<String, List<Processing>> result = new HashMap<String, List<Processing>>();
		Session session = null;
		Transaction tx = null;
		List<Processing> list =  null;;
		try{
			String hql = "from Processing";
			session = getHibernateTemplate().getSessionFactory().openSession();
			tx = session.beginTransaction();
			list =session.createQuery(hql).list();
		}catch (Exception e) {
			tx.rollback();
			e.printStackTrace();
		}finally{
			session.close();
		}
		for (Processing processing : list) {
			String vin = processing.getVin();
			List<Processing> l = result.get(vin);
			if (l == null) {
				l = Collections.synchronizedList(new ArrayList<Processing>());
				result.put(vin.toLowerCase(), l);
			}
			l.add(processing);
		}
		
		return result;
	}
	
	

	public void update(Processing proce) {
		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		Session session = null;
		Transaction tx = null;
		try{
			session = getHibernateTemplate().getSessionFactory().openSession();
			tx = session.beginTransaction();
			session.createQuery("update Processing p set p.endTime = '"+sdf.format(proce.getEndTime())+"' where p.vin = '"+proce.getVin()+"' and p.timeType = "+proce.getTimeType()+" ").executeUpdate();
			tx.commit();
		}catch (Exception e) {
			if (proce!= null) {
				System.out.println(" exception vin is " + proce.getVin());
			}
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
			map.put(processing.getVin().toLowerCase(), processing);
		}
		
		return map;
		
	}
}
