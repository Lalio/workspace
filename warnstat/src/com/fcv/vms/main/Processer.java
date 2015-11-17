package com.fcv.vms.main;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;
import java.util.Map;
import java.util.concurrent.ConcurrentHashMap;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;
import java.util.concurrent.atomic.AtomicInteger;
import java.util.concurrent.atomic.AtomicLong;

import org.springframework.context.ApplicationContext;
import org.springframework.context.support.ClassPathXmlApplicationContext;

import com.fcv.vms.pojo.AveData;
import com.fcv.vms.pojo.DParam;
import com.fcv.vms.pojo.Reg;
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
	public static Map<String, Date> vintime_map = new ConcurrentHashMap<String, Date>();

	public static Map<String, List<AveData>> all = new ConcurrentHashMap<String, List<AveData>>();

	public static AtomicLong avecount = new AtomicLong(0);
	public static AtomicLong warncount = new AtomicLong(0);;

	public static AtomicLong come = new AtomicLong(0);

	public static AtomicLong write = new AtomicLong(0);

	public void begin() throws ParseException {
		ExecutorService fixedThreadPool = Executors.newFixedThreadPool(4);
		ApplicationContext ac = new ClassPathXmlApplicationContext(
				"applicationContext.xml");
		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		DataService dataService = (DataService) ac.getBean("dataService");
		StatusService statusService = (StatusService) ac
				.getBean("statusService");
		RegService regService = (RegService) ac.getBean("regService");
		ProcessingService processingService = (ProcessingService) ac
				.getBean("processingService");

		long fivedaytime = 1000 * 60 * 60 * 24 * 5;
		long onedaytime = 1000 * 60 * 60 * 24;
		long santian = 1000 * 60 * 60 * 24 * 3;

		while (true) {
			if (threadNum.get() == 0) {
				System.out.println(new Date());
				long t = System.currentTimeMillis();
				vindparam_map.clear();
				regService.getVinId();// 获取vin-dparam的缓存map（vindparam_map）
										// 不该在方法内直接修改外部变量，会污染并且难以管理，不可取。
				System.out.println(System.currentTimeMillis() - t);

				List<String> vinList = dataService.getVin();

				System.out.println(System.currentTimeMillis() - t);
				vintime_map = processingService.getProce();// 获取车辆统计的最后时间
				System.out.println(System.currentTimeMillis() - t);
				// for (Date startDate = sdf.parse("2015-07-01 00:00:00");
				// startDate
				// .compareTo(sdf.parse("2015-10-25 00:00:00")) <= 0; startDate
				// = new Date(
				// startDate.getTime() + fivedaytime)) {

				for (String vin : vinList) {
					Date startDate = vintime_map.get(vin);
					// Date endDate = new Date(startDate.getTime());
					// endDate.setDate(endDate.getDate() + 5);

					if (startDate == null) {// 可能是新车加入
						startDate = dataService.getFirstDataTime(vin);
						if (startDate == null) {
							continue;
						}
					}

					Task task = (Task) ac.getBean("task");
					task.setVin(vin);
					task.setStartDate(startDate);
					// task.setEndDate(endDate);
					threadNum.getAndIncrement();
					fixedThreadPool.execute(task);
					// }
				}
			} else {

				try {
					Thread.sleep(1000 * 60 * 10);
					System.out.println("sleep 10 min ，now time is ："
							+ new Date() + "     threadnum is ：" + threadNum);
				} catch (InterruptedException e) {
					e.printStackTrace();
				}

			}
		}

	}
}
