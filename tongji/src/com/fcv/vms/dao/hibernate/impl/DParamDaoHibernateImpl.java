package com.fcv.vms.dao.hibernate.impl;

import java.util.Iterator;
import java.util.List;

import org.hibernate.Query;
import org.hibernate.Session;
import org.hibernate.Transaction;
import org.springframework.orm.hibernate3.support.HibernateDaoSupport;

import com.fcv.vms.dao.DParamDao;
import com.fcv.vms.pojo.DParam;

public class DParamDaoHibernateImpl extends HibernateDaoSupport implements DParamDao {

	public List<DParam> getDParams(int markID) {
		
		String hql ="from DParam where markid=:markId "+
					"and (dparamname in ('��ص�ѹ','����ܵ�ѹ','��ص���','����ܵ���','����¶�',"+
					"'�����������ѹ','��������������ѹ','����������¶�','�������������','����������������','����������������','�����ѹ','����¶�','���ת��','���ת��',"+
					"'������������������',"+
					"'DCDC�����ѹ','DCDC�������','DCDC�¶�',"+
					"'����̤��','�ƶ�̤��','������ʵ������','SOC','����','��ŵ�����') "+
					"or dparamname like '%�ͺ�%')";
//		String hql ="from DParam where markid=:markId ";
		Session session = null;
		Transaction tx = null;
		List<DParam> list = null;
		try{
			session = getHibernateTemplate().getSessionFactory().openSession();
			tx = session.beginTransaction();
			Query query = session.createQuery(hql);
			query.setParameter("markId", markID);
			list = query.list();
		}catch (Exception e) {
			tx.rollback();
			e.printStackTrace();
		}finally{
			session.close();
		}
		return list;
	}

	@Override
	public List<DParam> getDParams() {
		Session session = null;
		List<DParam> list = null;
		try{
			session = getHibernateTemplate().getSessionFactory().openSession();
			Query query = session.createQuery("from DParam");
			list = query.list();
		}catch (Exception e) {
			e.printStackTrace();
		}finally{
			session.close();
		}
		for (Iterator iterator = list.iterator(); iterator.hasNext();) {
			DParam dParam = (DParam) iterator.next();
			System.out.println("DParam      "+dParam.getId());
		}
		return list;
	}
}