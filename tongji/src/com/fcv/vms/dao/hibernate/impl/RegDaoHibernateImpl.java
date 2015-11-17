package com.fcv.vms.dao.hibernate.impl;

import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;
import java.util.concurrent.ConcurrentHashMap;

import org.hibernate.Query;
import org.hibernate.Session;
import org.hibernate.Transaction;
import org.hibernate.transform.Transformers;
import org.springframework.orm.hibernate3.support.HibernateDaoSupport;

import com.fcv.vms.dao.RegDao;
import com.fcv.vms.main.Processer;
import com.fcv.vms.pojo.AveData;
import com.fcv.vms.pojo.DParam;
import com.fcv.vms.pojo.Reg;

public class RegDaoHibernateImpl extends HibernateDaoSupport implements RegDao {
	// private int markValue = 49;
	// private int markValue = 53;
	// private int markValue = 57;
	// private int markValue = 68;
	// private int markValue = 72;
	// private int markValue = 73;
	// private int markValue = 76;
	// private int markValue = 77;
	// private int markValue = 78;
	// private int markValue = 81;
	// private int markValue = 91;
	// private int markValue = 103;
	// private int markValue = 104;
	// private int markValue = 105;
	// private int markValue = 108;
	// private int markValue = 110;

	public List<Reg> getRegs(int modelValue) {
		// System.out.println("Reg------getByModelValue");
		Session session = null;
		Transaction tx = null;
		List<Reg> list = null;
		try {
			session = getHibernateTemplate().getSessionFactory().openSession();
			tx = session.beginTransaction();

			if (modelValue == 0) {
				Query q = session
						.createQuery("from Reg r where r.isuse=1 and r.has_data_table=1 order by r.id desc");
				list = q.list();
			} else {
				Query q = session
						.createQuery("from Reg r where r.modelValue=:modelValue and r.isuse=1 and r.has_data_table=1 order by r.id desc");
				q.setParameter("modelValue", modelValue);
				list = q.list();
			}

		} catch (Exception e) {
			tx.rollback();
			e.printStackTrace();
		} finally {
			session.close();
		}
		// for (Iterator iterator = list.iterator(); iterator.hasNext();) {
		// Reg reg = (Reg) iterator.next();
		// System.out.println("REG     "+reg.getId());
		// }
		return list;
	}

	@Override
	public List<Integer> getModelValue() {
		// System.out.println("Reg------getmodelValue");
		Session session = null;
		Transaction tx = null;
		List<Integer> list = null;
		try {
			session = getHibernateTemplate().getSessionFactory().openSession();
			tx = session.beginTransaction();
			Query q = session
					.createQuery("select distinct(modelValue) from Reg");
			list = q.list();
		} catch (Exception e) {
			tx.rollback();
			e.printStackTrace();
		} finally {
			session.close();
		}
		// for (Iterator iterator = list.iterator(); iterator.hasNext();) {
		// Integer integer = (Integer) iterator.next();
		// System.out.println("ModelValue   "+integer.intValue());
		// }
		return list;
	}

	public List<Integer> getMarkValueByModel(int modelValue) {
		// System.out.println("Reg------getByModelValueDistinct");
		Session session = null;
		List<Integer> markValues = null;
		try {
			session = getHibernateTemplate().getSessionFactory().openSession();
			Query q = session
					.createQuery("select distinct(markValue) from Reg where modelValue=:modelValue");
			q.setParameter("modelValue", modelValue);
			markValues = q.list();
		} catch (Exception e) {
			e.printStackTrace();
		} finally {
			session.close();
		}
		// System.out.println("markValues   "+markValues);
		return markValues;
	}

	@Override
	public void getVinId() {
		Map<String, Integer> vinid_map = new ConcurrentHashMap<String, Integer>();

		Session session = null;
		Transaction tx = null;
		List<Map<String, Object>> p = new ArrayList<Map<String, Object>>();
		try {
			String sql = "SELECT m.id,r.vin  FROM mark m LEFT JOIN reg r on m.MarkValue = r.MarkValue where vin is not null and vin REGEXP BINARY '.{15}V[[:digit:]]$' ";
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
		String hql = "SELECT * FROM DParam ORDER BY DParamName DESC ";// order
																		// by
																		// 是因为车速需要在瞬时油耗之前，因为解析瞬时油耗的时候需要用到车速
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
			List<DParam> list2;
			String vin = entry.getKey().toLowerCase();
			int id = entry.getValue();
			for (DParam dParam : list) {
				if (id == dParam.getMarkId()) {
					list2 = Processer.vindparam_map.get(vin);
					if (list2 == null) {
						list2 = new ArrayList<DParam>();
						Processer.vindparam_map.put(vin.toLowerCase(), list2);
					}
					list2.add(dParam);
				}

			}
		}

		return;
	}

	@Override
	public Map<String, String> getVinName() {
		Map<String, String> result = new ConcurrentHashMap<String, String>();
		Session session = null;
		Transaction tx = null;
		List<Map<String, Object>> list = null;
		try {
			session = getHibernateTemplate().getSessionFactory().openSession();
			tx = session.beginTransaction();
			list = session
					.createSQLQuery("select VIN,Name from Reg")
					.setResultTransformer(Transformers.ALIAS_TO_ENTITY_MAP)
					.list();
			
		} catch (Exception e) {
			tx.rollback();
			e.printStackTrace();
		} finally {
			session.close();
		}
		for (Map<String, Object> map : list) {
			result.put(((String)map.get("VIN")).toLowerCase(),(String)map.get("Name"));
		}
		return result;
	}

}
