package com.fcv.vms.Rail;

import java.util.Date;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;


import org.hibernate.Query;
import org.hibernate.Session;
import org.hibernate.Transaction;

import com.fcv.vms.dao.PassRailLog;
import com.fcv.vms.dao.PolygonArea;
import com.fcv.vms.dao.PolygonAreaToTerminal;
import com.fcv.vms.model.HibernateSessionFactory;
/**
 * 
 * Χ������
 * �û��������ݿ��е�Χ����Ϣ���Թ�����ȡ��Χ����Ϣ�����Ƿ��ڽ���Χ�����ж�
 * 
 * Χ������Ҫά��
 * 
 * 1.��С��ʱ��������ݿ⣬ͨ���ԱȲ鿴�Ƿ����µ�Χ��������������
 * 2.�����ʱ��������ݿ⣬�鿴�����Ƿ���Χ���ѱ�ɾ��
 * 
 * 
 * @author fcv
 *
 */
public class RailManager2 {
	private static Map<Integer,PolygonArea> rails=new HashMap<Integer,PolygonArea>();

	private static Map<String,CarInfo> cars=new HashMap<String,CarInfo>();
	
	//private static Object lock=new Object();
	
	public static void init()
	{
		
		/*Session session=HibernateSessionFactory.getSession();
		List<PolygonToTerminal> list=session.createQuery("from PolygonToTerminal").list();
		System.out.println(list);*/
		/**
		 * ά��map��rails
		 * 
		 * ÿ��һ��ʱ��
		 * 
		 */
		Thread t=new Thread(new Runnable(){

			public void run() {
				while(true)
				{
					
					
					//System.out.println("ά��һ��Χ����");
					
					///synchronized(lock)
					//{
					updateRails();
					//}
					
					try {
						Thread.sleep(60000);
					} catch (InterruptedException e) {
						e.printStackTrace();
					}
				}			
			}}
		);
		
		t.start();
	}
	/**
	 * �������ݿ�ȡ������Χ��
	 * ���Χ������map���У�����ӵ�����
	 */
	private static void updateRails()
	{
		Session session=null;
		try{
			session=HibernateSessionFactory.getSession();
			
			List<PolygonArea> rs=session.createQuery("from PolygonArea").list();
			
			Iterator<PolygonArea> iterator=rs.iterator();
			
			Map<Integer,PolygonArea> rails=new HashMap<Integer,PolygonArea>();
			while(iterator.hasNext())
			{
				PolygonArea rail=iterator.next();
				rails.put(rail.getId(), rail);	
				rail.setLatlngs(RailUtil.praseToLatLng(rail.getLatlngArray()));
			}
			RailManager2.rails=rails;
			
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}
		finally
		{
			if(session!=null)
				session.close();
		}
	}
	
	private static int interval=60000;
	
	/**
	 * ��������Χ������
	 * 
	 * һ��sessionֻ��һ��vin,ÿ��session���Ƕ���һ���̣߳������ܳ��ֶ���̳߳���ͬsession����������ʹ�ø�
	 * sessionʱ���������̣߳�������handler�������ó��˶����̣߳����Բ����ܳ���ͬһvin���ʸ÷��������Կɲ���
	 * ��ͬ����ȡ����ֵ	
	 *
	 * @param vin
	 * @param lat
	 * @param lng
	 * @return
	 */
	public static int updateCarPosition(String vin,float lat,float lng)
	{
		//System.out.println("VIN:"+vin+"/Position(Lat:"+lat+"/Lng:"+lng+")");
		
		CarInfo result=cars.get(vin);
		
		long now=new Date().getTime();
		
		if(result==null)
		{
			/**
			 * newһ��������Ϣ
			 * ��ȡ�������Χ��id����
			 * ��ȡ�����ϴν���Χ��״̬
			 */
			result=new CarInfo();
			result.time=now;
			result.ids=getRailIdsByCar(vin);
			result.status=getLastStatusOfCar(vin);
			
			cars.put(vin, result);
		}
		else
		{
			/**
			 * ÿ��һ��ʱ����³���Χ������
			 */
			if(now-result.time>interval)
			{
				result.time=now;
				result.ids=getRailIdsByCar(vin);
			}
		}
		
		int[] ids=result.ids;
		/**
		 * û��Χ��
		 */
		if(ids==null||ids.length==0)
		{
			return CarInfo.CAR_STATUS_NO_RAIL;
		}
		/**
		 * ��Χ��
		 * �ж�ÿһ��Χ���Ƿ���磬�����һ��Χ�����磬�������
		 * ͨ�����ϴμ�¼�Ƚϣ�����ı��ˣ����ӽ��⵽����ڣ���
		 * ���ڵ�����⣬��û�н絽�н�
		 */
		String inIds="";
		if(ids!=null)
		{
			for(int id:ids)
			{
				PolygonArea r=rails.get(id);
				if(r!=null)
				{
					if(RailUtil.isInPolygonRail(r.getLatlngs(), lat, lng, true))
					{
						inIds+=id+",";
					}
				}
			}
		}
		/**
		 * ֻ����״̬�ı��˲�����¼
		 */
		if(!inIds.equals(""))
		{
			if(result.status!=CarInfo.CAR_STATUS_IN_RAIL)
			{
				result.status=CarInfo.CAR_STATUS_IN_RAIL;
				addToLog(lat,lng,vin,inIds,result.status);
			}
			
		}
		else
		{
			if(result.status!=CarInfo.CAR_STATUS_OUT_RAIL)
			{
				result.status=CarInfo.CAR_STATUS_OUT_RAIL;
				addToLog(lat,lng,vin,inIds,result.status);
			}
		}
		
		return result.status;
	}
	/**
	 * ��ȡ�����ϴε�Χ��״̬
	 * 
	 * ��Ϊ���ݿ����id��ʵ����˳ʱ������ģ��������ǵ���id�õ��ĵ�һ�����ݼ�Ϊ�������ݣ����Ч�ʣ������Ҫ�ɸ���Ϊʱ������
	 * 
	 * @param vin
	 * @return
	 */
	private static int getLastStatusOfCar(String vin) {
		Session session=null;
		int status=CarInfo.CAR_STATUS_NO_RAIL;
		try{
			session=HibernateSessionFactory.getSession();
			Query query= session.createQuery("from PassRailLog where vin='"+vin+"' order by id desc");
			query.setMaxResults(1);
			PassRailLog rail=(PassRailLog) query.uniqueResult();
		
			if(rail!=null)
			{			
				status=rail.getStatus();
			}
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}
		finally
		{
			if(session!=null)
				session.close();
		}
		return status;
	}
	/**
	 * ���һ����������Χ���ļ�¼
	 * ��¼��������״̬��ʱ�䣬��γ�ȣ�vin��
	 * @param lat
	 * @param lng
	 * @param vin
	 * @param inIds
	 * @param status
	 */
	private static void addToLog(float lat, float lng, String vin, String inIds,int status) {
		Session session=null;
		try{
			session=HibernateSessionFactory.getSession();
			PassRailLog log=new PassRailLog();
			log.setLat(lat);
			log.setLng(lng);
			log.setVin(vin);
			log.setRailIds(inIds);
			log.setTime(new Date());
			log.setStatus(status);
			
			Transaction tx= session.beginTransaction();
			session.save(log);
			tx.commit();
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}	
		finally
		{
			if(session!=null)
				session.close();
		}
	}
	/**
	 * ��ȡ��������Χ����ID����
	 * @param vin
	 * @return
	 */
	private static int[] getRailIdsByCar(String vin)
	{
		Session session=null;
		int[] ids=null;
		try{
			session=HibernateSessionFactory.getSession();
			List<PolygonAreaToTerminal> list=session.createQuery("from PolygonAreaToTerminal where vin='"+vin+"'").list();
			ids=new int[list.size()];
			int i=0;
			for(PolygonAreaToTerminal rc:list)
			{
				ids[i++]=rc.getPk().getAreaId();
			}
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}
		finally
		{
			if(session!=null)
				session.close();
		}
		return ids;
	}
	
}
