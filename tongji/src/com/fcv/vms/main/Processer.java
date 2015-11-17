package com.fcv.vms.main;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;
import java.util.Map;
import java.util.Vector;
import java.util.concurrent.ConcurrentHashMap;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;
import java.util.concurrent.atomic.AtomicInteger;
import java.util.concurrent.atomic.AtomicLong;

import org.springframework.context.ApplicationContext;
import org.springframework.context.support.ClassPathXmlApplicationContext;

import com.fcv.vms.pojo.AveData;
import com.fcv.vms.pojo.DParam;
import com.fcv.vms.pojo.Processing;
import com.fcv.vms.pojo.Reg;
import com.fcv.vms.pojo.WarnStat;
import com.fcv.vms.service.DataService;
import com.fcv.vms.service.ProcessingService;
import com.fcv.vms.service.RegService;
import com.fcv.vms.service.StatusService;

public class Processer {
	public static AtomicInteger threadNum = new AtomicInteger(0);
	public static Map<Integer, List<Reg>> md_reg_map = new ConcurrentHashMap<Integer, List<Reg>>();
	public static Map<String, Date> maxtime_map = new ConcurrentHashMap<String, Date>();
	public static Map<String, Date> status_map = new ConcurrentHashMap<String, Date>();
	public static Map<String, Date> lasttime_map = new ConcurrentHashMap<String, Date>();
	public static Map<String, List<DParam>> vindparam_map = new ConcurrentHashMap<String, List<DParam>>();
	public static Map<String, List<Processing>>  vintime_map  = new ConcurrentHashMap<String,List<Processing>>();
	public static Map<String, String> vinname_map = new ConcurrentHashMap<String, String>();


	public static Map<String, List<AveData>> all = new ConcurrentHashMap<String, List<AveData>>();

	public static AtomicLong avecount = new AtomicLong(0);
	public static AtomicLong warncount = new AtomicLong(0);;

	public static AtomicLong come = new AtomicLong(0);

	public void begin() throws ParseException {
		ExecutorService fixedThreadPool = Executors.newFixedThreadPool(4);
		ApplicationContext ac = new ClassPathXmlApplicationContext(
				"applicationContext.xml");
		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		SimpleDateFormat day_sdf = new SimpleDateFormat("yyyy-MM-dd");
		DataService dataService = (DataService) ac.getBean("dataService");
		RegService regService = (RegService) ac.getBean("regService");
		ProcessingService processingService = (ProcessingService) ac
				.getBean("processingService");

		long fivedaytime = 1000 * 60 * 60 * 24 * 5;
		long onedaytime = 1000 * 60 * 60 * 24 ;

		// while (true) {
		// if (threadNum.get() == 0) {
		System.out.println("tongji    " + new Date());
		vindparam_map.clear();
		regService.getVinId();// 获取vin-dparam的缓存map（vindparam_map）
		List<String> vinList = dataService.getVin();
		vinname_map = regService.getVinName();
//		List<String> vinList = new ArrayList<String>();
//		vinList.add("a000004e199dcf2v4");
		vintime_map = processingService.getProce();// 获取车辆统计的最后时间
		for (Date startDate = sdf.parse("2015-07-12 00:00:00"); startDate
				.compareTo(sdf.parse("2015-11-10 00:00:00")) <= 0; startDate = new Date(
				startDate.getTime() + fivedaytime)) {
			Date endDate = new Date(startDate.getTime());
			endDate.setDate(endDate.getDate() + 5);

			for (String vin : vinList) {
//				Date startDate = null;
//				List<Processing> list = vintime_map.get(vin);
//				for (Processing processing : list) {
//					if (processing.getTimeType() == 1440) {
//						startDate = processing.getEndTime();
//					}
//				}
				
				if (startDate == null) {// 可能是新车加入
					startDate = day_sdf.parse(day_sdf.format(dataService.getFirstDataTime(vin)));
					if (startDate == null) {
						continue;
					}
				}

				Task task = (Task) ac.getBean("task");
				task.setVin(vin);
				task.setStartDate(startDate);
				task.setEndDate(endDate);
				task.setRegName(vinname_map.get(vin));
				threadNum.getAndIncrement();
				fixedThreadPool.execute(task);
			}
			// }
			// } else {
			// try {
			// Thread.sleep(1000 * 60);
			// System.out.println("sleep 1min ，now time is ：" + new Date() +
			// "     threadnum is ：" + threadNum);
			// } catch (InterruptedException e) {
			// e.printStackTrace();
			// }
			//
			// }
		}

	}
}
