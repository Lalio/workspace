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
import com.fcv.vms.dao.RectangularArea;
import com.fcv.vms.dao.RectangularAreaToTerminal;
import com.fcv.vms.dao.Reg;
import com.fcv.vms.dao.RoundArea;
import com.fcv.vms.dao.RoundAreaToTerminal;
import com.fcv.vms.model.HibernateSessionFactory;
/**
 * 
 * 围栏管理
 * 用户缓存数据库中的围栏信息，以供快速取得围栏信息进行是否在进出围栏的判断
 * 
 * 围栏表需要维护
 * 
 * 1.隔小段时间访问数据库，通过对比查看是否有新的围栏，有则加入表中
 * 2.隔大段时间访问数据库，查看表中是否有围栏已被删除
 * 
 * 
 * @author fcv
 *
 */
public class RailManager3 {
	private static Map<Integer,RectangularArea> rectangularRails=new HashMap<Integer,RectangularArea>();
	private static Map<Integer,RoundArea> roundRails=new HashMap<Integer,RoundArea>();
	private static Map<Integer,PolygonArea> polygonRails=new HashMap<Integer,PolygonArea>();

	private static Map<String,CarInfo> cars=new HashMap<String,CarInfo>();
	
	//private static Object lock=new Object();
	
	public static void init()
	{
		
		/*Session session=HibernateSessionFactory.getSession();
		List<PolygonToTerminal> list=session.createQuery("from PolygonToTerminal").list();
		System.out.println(list);*/
		/**
		 * 维护map表rails
		 * 
		 * 每隔一段时间
		 * 
		 */
		Thread t=new Thread(new Runnable(){

			public void run() {
				while(true)
				{
					
					
					//System.out.println("维护一次围栏表！");
					
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
	 * 访问数据库取得所有围栏
	 * 如果围栏不在map表中，则添加到表中
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
				
			RailManager3.polygonRails=rails;
			
			
			List<RoundArea> roundareas=session.createQuery("from RoundArea").list();
			
			Iterator<RoundArea> rounditerator=roundareas.iterator();
			
			Map<Integer,RoundArea> roundrails=new HashMap<Integer,RoundArea>();
			while(rounditerator.hasNext())
			{
				RoundArea area=rounditerator.next();
				roundrails.put(area.getId(), area);
			}
				
			RailManager3.roundRails=roundrails;
			
			
			List<RectangularArea> rectangularareas=session.createQuery("from RectangularArea").list();
			
			Iterator<RectangularArea> rectangulariterator=rectangularareas.iterator();
			
			Map<Integer,RectangularArea> rectangularrails=new HashMap<Integer,RectangularArea>();
			while(rectangulariterator.hasNext())
			{
				RectangularArea area=rectangulariterator.next();
				rectangularrails.put(area.getId(), area);
			}
				
			RailManager3.rectangularRails=rectangularrails;
			
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
	 * 车辆出入围栏更新
	 * 
	 * 一个session只有一个vin,每个session都是独立一个线程，不可能出现多个线程出现同session，除非你在使用该
	 * session时是在其他线程，或你在handler处理设置成了独立线程，所以不可能出现同一vin访问该方法，所以可不考
	 * 虑同步获取到得值	
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
			 * new一个车辆信息
			 * 获取车辆相关围栏id数组
			 * 获取车辆上次进出围栏状态
			 */
			result=new CarInfo();
			result.time=now;
			//result.ids=getRailIdsByCar(vin);
			updateRailIdsByCar(result,vin);
			
			result.status=getLastStatusOfCar(vin);
			
			cars.put(vin, result);
		}
		else
		{
			/**
			 * 每隔一段时间更新车辆围栏数组
			 */
			if(now-result.time>interval)
			{
				result.time=now;
				//result.ids=getRailIdsByCar(vin);
				updateRailIdsByCar(result,vin);
			}
		}
		
		
		int[] ids=result.ids;
		
		/**
		 * 有围栏
		 * 判断每一个围栏是否出界，如果有一个围栏出界，则算出界
		 * 通过与上次记录比较，如果改变了，即从界外到达界内，或
		 * 界内到达界外，或没有界到有界
		 */
		
		boolean isInRail=false;
		boolean hasRail=false;
		
		String inIds="";	
		if(ids!=null&&ids.length>0)
		{
			hasRail=true;
			for(int id:ids)
			{
				PolygonArea r=polygonRails.get(id);
				if(r!=null)
				{
					if(RailUtil.isInPolygonRail(r.getLatlngs(), lat, lng, true))
					{
						inIds+=id+",";
						
					}
				}
			}
			if(!inIds.equals(""))
			{
				isInRail=true;
			}
		}
		
		String roundids="";
		ids=result.roundIds;
		
		if(ids!=null&&ids.length>0)
		{
			hasRail=true;
			for(int id:ids)
			{
				RoundArea r=roundRails.get(id);
				if(r!=null)
				{
					if(RailUtil.isInRoundRail(r,lat,lng))
						roundids+=id+",";
				}
			}
			
			if(!roundids.equals(""))
			{
				isInRail=true;
			}
		}
		
		String rectangularids="";
		ids=result.rectangularIds;
		
		if(ids!=null&&ids.length>0)
		{
			hasRail=true;
			for(int id:ids)
			{
				RectangularArea r=rectangularRails.get(id);
				if(r!=null)
				{
					if(RailUtil.isInRectangularRail(r,lat,lng))
						rectangularids+=id+",";
				}
			}
			
			if(!rectangularids.equals(""))
			{
				isInRail=true;
			}
		}
		
		if(!hasRail)
		{
			result.status=CarInfo.CAR_STATUS_NO_RAIL;
			return CarInfo.CAR_STATUS_NO_RAIL;
		}
		
		/**
		 * 只有在状态改变了才做记录
		 */
		if(isInRail)
		{
			if(result.status!=CarInfo.CAR_STATUS_IN_RAIL)
			{
				result.status=CarInfo.CAR_STATUS_IN_RAIL;
				PassRailLog log=new PassRailLog();
				log.setLat(lat);
				log.setLng(lng);
				log.setVin(vin);
				log.setRailIds(inIds);
				log.setRoundIds(roundids);
				log.setRectangularIds(rectangularids);
				log.setTime(new Date());
				log.setStatus(result.status);
				
				addToLog(log);
				//addToLog(lat,lng,vin,inIds,result.status);
			}
			
		}
		else
		{
			if(result.status!=CarInfo.CAR_STATUS_OUT_RAIL)
			{
				result.status=CarInfo.CAR_STATUS_OUT_RAIL;
				
				PassRailLog log=new PassRailLog();
				log.setLat(lat);
				log.setLng(lng);
				log.setVin(vin);
				log.setRailIds(inIds);
				log.setRoundIds(roundids);
				log.setRectangularIds(rectangularids);
				log.setTime(new Date());
				log.setStatus(result.status);
				//addToLog(lat,lng,vin,inIds,result.status);
				addToLog(log);
			}
		}
		
		return result.status;
	}
	private static void addToLog(PassRailLog log) {
		Session session=null;
		try{
			session=HibernateSessionFactory.getSession();
			
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
	 * 获取车辆上次的围栏状态
	 * 
	 * 因为数据库根据id其实既是顺时间排序的，所以我们倒序id得到的第一个数据即为最新数据，提高效率，如果需要可更改为时间排序，
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
	 * 添加一条车辆出入围栏的记录
	 * 记录车辆出入状态，时间，经纬度，vin码
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
	 * 获取车辆所有围栏的ID数组
	 * @param vin
	 * @return
	 */
	/*private static int[] getRailIdsByCar(String vin)
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
	}*/
	
	
	private static int[] updateRailIdsByCar(CarInfo info,String vin)
	{
		Session session=null;
		int[] ids=null;
		int[] roundIds=null;
		int[] rectangularIds=null;
		try{
			session=HibernateSessionFactory.getSession();
			List<PolygonAreaToTerminal> list=session.createQuery("from PolygonAreaToTerminal where vin='"+vin+"'").list();
			ids=new int[list.size()];
			int i=0;
			for(PolygonAreaToTerminal rc:list)
				ids[i++]=rc.getPk().getAreaId();
			info.ids=ids;
			
			Reg reg=(Reg) session.createQuery("from Reg where vin='"+vin+"'").uniqueResult();
			if(reg!=null)
			{	
				int regid=reg.getId();
				List<RoundAreaToTerminal> roundlist=session.createQuery("from RoundAreaToTerminal where regId="+regid).list();
				roundIds=new int[roundlist.size()];
				i=0;
				for(RoundAreaToTerminal rc:roundlist)
					roundIds[i++]=rc.getPk().getAreaId();
				info.roundIds=roundIds;
				
				
				List<RectangularAreaToTerminal> rectangularlist=session.createQuery("from RectangularAreaToTerminal where regId="+regid).list();
				rectangularIds=new int[rectangularlist.size()];
				i=0;
				for(RectangularAreaToTerminal rc:rectangularlist)
					rectangularIds[i++]=rc.getPk().getAreaId();
				info.rectangularIds=rectangularIds;
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
