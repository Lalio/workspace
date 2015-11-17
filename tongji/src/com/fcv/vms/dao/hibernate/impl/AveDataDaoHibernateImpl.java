package com.fcv.vms.dao.hibernate.impl;

import java.util.Iterator;
import java.util.List;

import org.dom4j.Document;
import org.hibernate.Query;
import org.hibernate.Session;
import org.hibernate.SessionFactory;
import org.hibernate.Transaction;
import org.hibernate.cfg.Configuration;
import org.hibernate.cfg.Mappings;
import org.hibernate.engine.Mapping;
import org.hibernate.mapping.PersistentClass;
import org.hibernate.mapping.Table;
import org.hibernate.util.xml.Origin;
import org.hibernate.util.xml.XmlDocument;
import org.springframework.orm.hibernate3.support.HibernateDaoSupport;

import com.fcv.vms.dao.AveDataDao;
import com.fcv.vms.main.Processer;
import com.fcv.vms.pojo.AveData;

public class AveDataDaoHibernateImpl extends HibernateDaoSupport implements
		AveDataDao {

//	public static Configuration cfg = new Configuration().configure("/hibernate.cfg.xml");
//	static{
//		cfg.buildMappings();
//	}
	
	private static final ThreadLocal<Configuration> threadLocal = new ThreadLocal<Configuration>(); 

	public Configuration getConfiguration(){
		Configuration configuration = threadLocal.get();
		if (configuration == null) {
			configuration = new Configuration().configure("/hibernate.cfg.xml");
			configuration.buildMappings();
			threadLocal.set(configuration);
		}
		return configuration;
	}
	    
	@Override
	public void add(List<AveData> list, String vin) {
		SessionFactory sessionFactory = null;
		Session session = null;
		Transaction tx = null;
		String tablenameString = "z_ave_" + vin.toLowerCase();

		Configuration cfg = getConfiguration();
//		Mappings mappings = cfg.createMappings();
//		System.out.println(mappings);
//		Iterator<Table> iterator = mappings.iterateTables();
//		while (iterator.hasNext()) {
//			System.out.println(iterator.next().getName());
//		}

		String className = AveData.class.getName();
		PersistentClass persistentClass = cfg.getClassMapping(className);
		Table table = persistentClass.getTable();
		table.setName(tablenameString);
		
		int size = list.size();
		
		System.out.println(tablenameString + "  had writen " + Processer.avecount.getAndAdd(size)
				+ " avedata ，ready to write " + size + " avedata");
		try {
			sessionFactory = cfg.buildSessionFactory();
			session = sessionFactory.openSession();
			tx = session.beginTransaction();

			for (int i = 0; i < size; i++) {// 批量插入，100一次
				session.save(list.get(i));
				if (i % 100 == 0) {
					session.flush();
					session.clear();
				}
			}
			tx.commit();
		} catch (Exception e) {
			e.printStackTrace();
			tx.rollback();
		} finally {
			if (session != null) {
				session.close();
			} 
			if (sessionFactory != null) {
				sessionFactory.close();
			}
		}

	}

	@Override
	public void add(List<AveData> list) {
		Session session = null;
		Transaction tx = null;
		int size = list.size();
		System.out.println("had writen " + Processer.avecount.addAndGet(size)
				+ " avedata ，ready to write " + size + " avedata");
		try {
			session = getHibernateTemplate().getSessionFactory().openSession();
			tx = session.beginTransaction();

			for (int i = 0; i < size; i++) {// 批量插入，100一次
				session.save(list.get(i));
				if (i % 100 == 0) {
					session.flush();
					session.clear();
				}
			}
			tx.commit();
		} catch (Exception e) {
			tx.rollback();
			e.printStackTrace();
		} finally {
			session.close();
		}

	}

	@Override
	public void update(AveData aveData) {
		Session session = null;
		Transaction tx = null;
		try {
			session = getHibernateTemplate().getSessionFactory().openSession();
			tx = session.beginTransaction();
			session.update(aveData);
			tx.commit();
		} catch (Exception e) {
			tx.rollback();
			e.printStackTrace();
		} finally {
			session.close();
		}

	}

}
