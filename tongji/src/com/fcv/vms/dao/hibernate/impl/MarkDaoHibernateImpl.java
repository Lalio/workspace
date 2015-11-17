package com.fcv.vms.dao.hibernate.impl;


import org.hibernate.Query;
import org.hibernate.Session;
import org.hibernate.Transaction;
import org.springframework.orm.hibernate3.support.HibernateDaoSupport;

import com.fcv.vms.dao.MarkDao;

public class MarkDaoHibernateImpl extends HibernateDaoSupport implements MarkDao {

	
	public int getMarkIdByMarkValue(int markValue) {
		String hql = "select m.id from Mark m where m.markValue=:markValue";
		Session session = null;
		Transaction tx = null;
		int markId = 0;
		try{
			session = getHibernateTemplate().getSessionFactory().openSession();
			tx = session.beginTransaction();
			Query query = session.createQuery(hql);
			query.setParameter("markValue", markValue);
			Object obj = query.uniqueResult();
			if(obj != null){
				markId = (Integer) obj;
			}
		}catch (Exception e) {
			tx.rollback();
			e.printStackTrace();
		}finally{
			session.close();
		}
		return markId;
	}

}
