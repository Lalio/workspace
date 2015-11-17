package com.fcv.vms.dao.hibernate.impl;

import java.util.List;

import org.hibernate.Query;
import org.hibernate.Session;
import org.hibernate.Transaction;
import org.springframework.orm.hibernate3.support.HibernateDaoSupport;

import com.fcv.vms.dao.AveDataDao;
import com.fcv.vms.main.Processer;
import com.fcv.vms.pojo.AveData;


public class AveDataDaoHibernateImpl extends HibernateDaoSupport implements AveDataDao {
    @Override
    public void add(List<AveData> list) {
        Session session = null;
        Transaction tx = null;
        int size  = list.size();
        System.out.println("had writen "+Processer.avecount.addAndGet(size)+" avedata ，ready to write "+size+" avedata");
        try {
            session = getHibernateTemplate().getSessionFactory().openSession();
            tx = session.beginTransaction();
            
            for (int i = 0; i < size; i++) {//批量插入，100一次
            	session.save(list.get(i));
				if(i%100 == 0){
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
