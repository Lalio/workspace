package com.fcv.vms.main;

import java.math.BigInteger;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Date;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;
import java.util.Vector;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import net.sourceforge.pinyin4j.PinyinHelper;

import org.springframework.expression.ParseException;

import com.fcv.vms.dao.AveDataDao;
import com.fcv.vms.dao.hibernate.impl.AveDataDaoHibernateImpl;
import com.fcv.vms.pojo.AveData;
import com.fcv.vms.pojo.DParam;
import com.fcv.vms.pojo.Data;
import com.fcv.vms.pojo.DparamMapValue;
import com.fcv.vms.pojo.MyDParam;
import com.fcv.vms.pojo.Processing;
import com.fcv.vms.pojo.WarnStat;
import com.fcv.vms.service.AveDataService;
import com.fcv.vms.service.DParamService;
import com.fcv.vms.service.DataService;
import com.fcv.vms.service.MarkService;
import com.fcv.vms.service.ProcessingService;
import com.fcv.vms.service.RegService;
import com.fcv.vms.service.WarnStatService;

public class Task implements Runnable {

	private String vin;
	private String regName;
	private Date startDate;
	private Date endDate;

	public String getRegName() {
		return regName;
	}

	public void setRegName(String regName) {
		this.regName = regName;
	}

	public Date getEndDate() {
		return endDate;
	}

	public void setEndDate(Date endDate) {
		this.endDate = endDate;
	}

	public Date getStartDate() {
		return startDate;
	}

	public void setStartDate(Date startDate) {
		this.startDate = startDate;
	}

	public String getVin() {
		return vin;
	}

	public void setVin(String vin) {
		this.vin = vin;
	}

	public Task() {

	}

	private DataService dataService;
	private AveDataService aveDataService;
	private AveDataDao aveDataDao = new AveDataDaoHibernateImpl();
	private WarnStatService warnStatService;
	private ProcessingService processingService;
	private RegService regService;
	private MarkService markService;
	private DParamService dParamService;

	public void setDataService(DataService dataService) {
		this.dataService = dataService;
	}

	public void setAveDataService(AveDataService aveDataService) {
		this.aveDataService = aveDataService;
	}

	public void setWarnStatService(WarnStatService warnStatService) {
		this.warnStatService = warnStatService;
	}

	public void setProcessingService(ProcessingService processingService) {
		this.processingService = processingService;
	}

	public void setRegService(RegService regService) {
		this.regService = regService;
	}

	public void setMarkService(MarkService markService) {
		this.markService = markService;
	}

	public void setdParamService(DParamService dParamService) {
		this.dParamService = dParamService;
	}

	public void run() {
		try {
			List<DParam> dParams = Processer.vindparam_map.get(vin);
			if (dParams == null) {
				System.err.println("vin:" + vin + "拿不到对应的参数");
			} else {
				List<MyDParam> myDParams = getDParams(dParams);
				begin(vin, myDParams);
				Processer.threadNum.getAndDecrement();
			}

		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	private Map<String, WarnStat> warnStats = new HashMap<String, WarnStat>();
	public int moduleValue = 0;
	public static long oneDay = 1000 * 60 * 1440;
	public static long oneHour = 1000 * 60 * 60;
	public static long thirtyMin = 1000 * 60 * 30;
	public static long tenMin = 1000 * 60 * 10;
	public static long fiveMin = 1000 * 60 * 5;
	public static long oneMin = 1000 * 60;
	public static long twoSec = 1000 * 2;
	public int speedNum = 0;

	public SimpleDateFormat min_sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm");
	public SimpleDateFormat hour_sdf = new SimpleDateFormat("yyyy-MM-dd HH");
	public SimpleDateFormat day_sdf = new SimpleDateFormat("yyyy-MM-dd");

	private void begin(String vin, List<MyDParam> myDParams) throws Exception {

		Processing processing_1_min = new Processing(vin, startDate, 1);
		Processing processing_5_min = new Processing(vin, startDate, 5);
		Processing processing_10_min = new Processing(vin, startDate, 10);
		Processing processing_30_min = new Processing(vin, startDate, 30);
		Processing processing_60_min = new Processing(vin, startDate, 60);
		Processing processing_1440_min = new Processing(vin, startDate, 1440);

		List<Processing> processingVector = Processer.vintime_map.get(vin);
		if (processingVector != null) {
			for (Processing processing : processingVector) {
				int timeTpye = processing.getTimeType();
				if (timeTpye == 1) {
					processing_1_min = processing;
				} else if (timeTpye == 5) {
					processing_5_min = processing;
				} else if (timeTpye == 10) {
					processing_10_min = processing;
				} else if (timeTpye == 30) {
					processing_30_min = processing;
				} else if (timeTpye == 60) {
					processing_60_min = processing;
				} else {
					processing_1440_min = processing;
				}
			}
		}

		List<AveData> list_1_min = new ArrayList<AveData>();
		List<AveData> list_5_min = new ArrayList<AveData>();
		List<AveData> list_10_min = new ArrayList<AveData>();
		List<AveData> list_30_min = new ArrayList<AveData>();
		List<AveData> list_60_min = new ArrayList<AveData>();
		List<AveData> list_1440_min = new ArrayList<AveData>();

		AveData ave_1_min = new AveData();
		AveData ave_5_min = new AveData();
		AveData ave_10_min = new AveData();
		AveData ave_30_min = new AveData();
		AveData ave_60_min = new AveData();
		AveData ave_1440_min = new AveData();

		ave_1_min.setTimeType(1);
		ave_5_min.setTimeType(5);
		ave_10_min.setTimeType(10);
		ave_30_min.setTimeType(30);
		ave_60_min.setTimeType(60);
		ave_1440_min.setTimeType(1440);

		List<Data> car_list = dataService.getAllDatas(vin, startDate, endDate);
		List<WarnStat> warnList = warnStatService.getWarnStat(vin, startDate,
				endDate);

		if (car_list.size() == 0) {
			System.out.println(vin + "  between " + startDate + " and "
					+ endDate + " has not data to analys");

		}

		boolean first_1_min = true;// 对avedata的startTime 和 startSOC 初始化
		boolean first_5_min = true;
		boolean first_10_min = true;
		boolean first_30_min = true;
		boolean first_60_min = true;
		boolean first_1440_min = true;

		for (Data data : car_list) {

			Date time = data.getTime();

			if (time.after(processing_1_min.getEndTime())) {
				if (first_1_min) {
					// ave_1_min.setStartTime(min_sdf.parse(min_sdf
					// .format(processing_1_min.getEndTime())));
					ave_1_min.setStartTime(min_sdf.parse(min_sdf
							.format(startDate)));
				}
				dataAnalysis(data, myDParams, ave_1_min, first_1_min);
				first_1_min = false;

				if (ave_1_min.getLastTime().getTime()
						- ave_1_min.getStartTime().getTime() >= 1000 * 60) {

					processing_1_min.setEndTime(ave_1_min.getLastTime());
					warnAnalysis(
							ave_1_min,
							warnList,
							ave_1_min.getStartTime(),
							new Date(
									ave_1_min.getStartTime().getTime() + 1000 * 60));

					ave_1_min = finishData(ave_1_min, list_1_min);

				}
			}

			if (time.after(processing_5_min.getEndTime())) {
				if (first_5_min) {
					ave_5_min.setStartTime(min_sdf.parse(min_sdf
							.format(processing_5_min.getEndTime())));
				}
				dataAnalysis(data, myDParams, ave_5_min, first_5_min);
				first_5_min = false;

				if (ave_5_min.getLastTime().getTime()
						- ave_5_min.getStartTime().getTime() >= 1000 * 60 * 5) {
					processing_5_min.setEndTime(ave_5_min.getLastTime());
					warnAnalysis(
							ave_5_min,
							warnList,
							ave_5_min.getStartTime(),
							new Date(
									ave_5_min.getStartTime().getTime() + 1000 * 60 * 5));

					ave_5_min = finishData(ave_5_min, list_5_min);
				}
			}

			if (time.after(processing_10_min.getEndTime())) {
				if (first_10_min) {
					ave_10_min.setStartTime(min_sdf.parse(min_sdf
							.format(processing_10_min.getEndTime())));
				}
				dataAnalysis(data, myDParams, ave_10_min, first_10_min);
				first_10_min = false;
				if (ave_10_min.getLastTime().getTime()
						- ave_10_min.getStartTime().getTime() >= 1000 * 60 * 10) {
					processing_10_min.setEndTime(ave_10_min.getLastTime());
					warnAnalysis(ave_10_min, warnList,
							ave_10_min.getStartTime(), new Date(ave_10_min
									.getStartTime().getTime() + 1000 * 60 * 10));

					ave_10_min = finishData(ave_10_min, list_10_min);
				}
			}

			if (time.after(processing_30_min.getEndTime())) {
				if (first_30_min) {
					ave_30_min.setStartTime(min_sdf.parse(min_sdf
							.format(processing_30_min.getEndTime())));
				}
				dataAnalysis(data, myDParams, ave_30_min, first_30_min);
				first_30_min = false;

				if (ave_30_min.getLastTime().getTime()
						- ave_30_min.getStartTime().getTime() >= 1000 * 60 * 30) {
					processing_30_min.setEndTime(ave_30_min.getLastTime());
					warnAnalysis(ave_30_min, warnList,
							ave_30_min.getStartTime(), new Date(ave_30_min
									.getStartTime().getTime() + 1000 * 60 * 30));

					ave_30_min = finishData(ave_30_min, list_30_min);
				}
			}

			if (time.after(processing_60_min.getEndTime())) {
				if (first_60_min) {
					ave_60_min.setStartTime(hour_sdf.parse(hour_sdf
							.format(processing_60_min.getEndTime())));
				}
				dataAnalysis(data, myDParams, ave_60_min, first_60_min);
				first_60_min = false;

				if (ave_60_min.getLastTime().getTime()
						- ave_60_min.getStartTime().getTime() >= 1000 * 60 * 60) {
					processing_60_min.setEndTime(ave_60_min.getLastTime());
					warnAnalysis(ave_60_min, warnList,
							ave_60_min.getStartTime(), new Date(ave_60_min
									.getStartTime().getTime() + 1000 * 60 * 60));

					ave_60_min = finishData(ave_60_min, list_60_min);
				}
			}

			if (time.after(processing_1440_min.getEndTime())) {
				if (first_1440_min) {
					ave_1440_min.setStartTime(day_sdf.parse(day_sdf
							.format(processing_1440_min.getEndTime())));
				}
				dataAnalysis(data, myDParams, ave_1440_min, first_1440_min);
				first_1440_min = false;

				if (ave_1440_min.getLastTime().getTime()
						- ave_1440_min.getStartTime().getTime() >= 1000 * 60 * 1440) {
					processing_1440_min.setEndTime(ave_1440_min.getLastTime());
					warnAnalysis(
							ave_1440_min,
							warnList,
							ave_1440_min.getStartTime(),
							new Date(
									ave_1440_min.getStartTime().getTime() + 1000 * 60 * 1440));

					ave_1440_min = finishData(ave_1440_min, list_1440_min);
				}
			}
			lastDataTime = data.getTime();

		}
		Date startTime = ave_1440_min.getStartTime();
		if (startTime == null) {// 没有数据
		} else {
			// 统计累计数据是5天为间隔，如1-5号，因为没有6号的数据，5号那天的数据会丢失，下面的十行代码是为了把5号的数据写入一天的表。实时统计时候需要去掉
			processing_1440_min.setEndTime(new Date(
					startTime.getTime() + 1000 * 60 * 1440));
			warnAnalysis(ave_1440_min, warnList, startTime,
					new Date(startTime.getTime() + 1000 * 60 * 1440));

			ave_1440_min.setOnlineStime(startTime);
			ave_1440_min.setOnlineEtime(new Date(startTime.getTime() + oneDay));
			ave_1440_min.setOnlineTime((int) (oneDay / 1000.0f));// 秒为单位

			list_1440_min.add(ave_1440_min);
		}

		// for循环之后，把剩下的数据全部插入表中
		 aveDataDao.add(list_1_min, vin);
		 aveDataDao.add(list_5_min, vin);
		 aveDataDao.add(list_10_min, vin);
		 aveDataDao.add(list_30_min, vin);
		 aveDataDao.add(list_60_min, vin);
		 aveDataDao.add(list_1440_min, vin);
		
		 updateProcessing(processing_1_min);
		 updateProcessing(processing_5_min);
		 updateProcessing(processing_10_min);
		 updateProcessing(processing_30_min);
		 updateProcessing(processing_60_min);
		 updateProcessing(processing_1440_min);

	}

	/**********************
	 * 获得协议参数
	 * 
	 * @param datas
	 * @param dParam
	 * @return
	 */
	public float getParam(byte[] datas, MyDParam myDParam) {
		DParam dParam = myDParam.getdParam();

		int byteBegin = dParam.getDparamByteBegin();
		int byteLength = dParam.getDparamByteLength();
		int isByte = dParam.getDparamIsByte();
		float factory = dParam.getDparamFactor();// 参数分辨率（倍数）
		float offset = dParam.getDparamOffset();// 参数偏移值
		String byteDatas = dParam.getDparamByteDatas();

		float param = 0;

		if ((byteBegin + byteLength) <= datas.length) {
			byte[] dataArray = cutDatas(datas, byteBegin, byteLength); // 截取datas
			if (isByte == 0) {
				float tmpFloat = 0;

				if (dParam.getDparamHighAndLow() == 1) {// 大端法解析
					tmpFloat = byteTransformFloat_big(dataArray);
				} else {// 小端法解析
					tmpFloat = byteTransformFloat_small(dataArray);
				}

				if (tmpFloat == 0) {
					param = 0;
				} else {
					param = Math.round((tmpFloat * factory + offset) * 10.0f) / 10.0f;
				}

			} else {
				String[] bytedataArray = byteDatas.split(":");
				if (bytedataArray.length >= 4) {
					int bytelen = Integer.parseInt(bytedataArray[0]);
					int bytebeginbit = Integer.parseInt(bytedataArray[1]);
					int byteendbit = Integer.parseInt(bytedataArray[2])
							+ bytebeginbit;
					int bytefactor = Integer.parseInt(bytedataArray[3]);
					int tmpInt = 0;
					for (int i = Math.min(dataArray.length, bytelen) - 1; i >= 0; --i) {
						tmpInt |= dataArray[i];
						tmpInt = tmpInt << 4 * i;
					}
					String tmpString = toBinaryString(tmpInt);

					if (tmpString.length() - byteendbit < 0
							|| tmpString.length() - bytebeginbit < 0) {
						return 0;

					}

					tmpString = tmpString.substring(tmpString.length()
							- byteendbit, tmpString.length() - bytebeginbit);

					param = (float) (new BigInteger(tmpString, 2).intValue() * bytefactor);
				}
			}

		} else {
			param = 0;
		}

		return param;
	}

	/******************************************
	 * 切割数据
	 * 
	 * @param datas
	 * @param dParam
	 * @return
	 */
	private byte[] cutDatas(byte[] datas, int byteBegin, int byteLength) {
		byte[] b = new byte[byteLength];
		int j = 0;
		for (int i = byteBegin; i < (byteBegin + byteLength); i++) {
			b[j] = datas[i];
			j++;
		}
		return b;
	}

	/********************************************
	 * 将byte数组转换为 float 值(小端法)
	 * 
	 * @param data
	 * @return
	 */
	private float byteTransformFloat_small(byte[] data) {
		float result = 0;
		int[] byteFactor = { 1, 256, 65536, 16777216 };
		for (int i = 0; i < data.length; i++) {
			result += (data[i] & 0xff) * byteFactor[i];
		}
		return result;
	}

	/********************************************
	 * 将byte数组转换为 float 值(大端法)
	 * 
	 * @param data
	 * @return
	 */
	private float byteTransformFloat_big(byte[] data) {
		float result = 0;
		int[] byteFactor = { 1, 256, 65536, 16777216 };
		for (int i = 0; i < data.length; i++) {
			result += (data[i] & 0xff) * byteFactor[data.length - 1 - i];
		}
		return result;
	}

	/******************
	 * 数值转字符串（只取低8位）
	 * 
	 * @param i
	 * @return
	 */
	final char[] digits = { '0', '1' };

	private String toBinaryString(int i) {
		char[] buf = new char[32];
		int charPos = 32;
		int radix = 1 << 1;
		int mask = radix - 1;

		for (int k = 0; k < 8; k++) {
			buf[--charPos] = digits[i & mask];
			i >>>= 1;// 右移赋值，左边空出的位以0填充
		}
		return new String(buf, charPos, (32 - charPos));
	}

	/*******************************
	 * 获取需要的协议信息，封装成MyDParam
	 * 
	 * @param markValue
	 * @return
	 */
	private List<MyDParam> getDParams(List<DParam> dParams) {
		List<MyDParam> list = new ArrayList<MyDParam>();

		if (dParams.size() > 0) {
			for (int j = 0; j < dParams.size(); j++) {
				MyDParam myDParam = new MyDParam();
				DParam dParam = dParams.get(j);
				myDParam.setdParam(dParam);

				Map<Integer, DparamMapValue> map = stringTransformMap(
						dParam.getDparamIsMap(), dParam.getDparamMapDatas());
				myDParam.setDmv(map);

				list.add(myDParam);
			}
		}
		return list;
	}

	/*******************************
	 * 获取故障信息，将被":"隔开字符串转换成map集合
	 * 
	 * @param dparamMapDatas
	 * @return
	 */
	private Map<Integer, DparamMapValue> stringTransformMap(int dparamIsMap,
			String dparamMapDatas) {
		Map<Integer, DparamMapValue> map = new HashMap<Integer, DparamMapValue>();
		if (dparamIsMap != 0) {

			try {

				String[] warns = dparamMapDatas.split(",");

				for (String s : warns) {
					String[] elements = s.split(":");
					int key = Integer.parseInt(elements[0]);
					DparamMapValue dmv = new DparamMapValue();

					dmv.setKey(Integer.parseInt(elements[0]));
					dmv.setValue(elements[1]);
					if (elements.length > 2) {
						dmv.setSwitchStatus(Integer.parseInt(elements[2]));
						dmv.setSwitchColor(Integer.parseInt(elements[3]));
					}

					map.put(key, dmv);
				}

			} catch (java.lang.NumberFormatException e) {

			} catch (Exception e) {
				e.printStackTrace();
			}
		}
		return map;
	}

	/**
	 * 让数据保留两位小数
	 */
	private float floatFormat(float num) {
		return (float) Math.round((num) * 100.0f) / 100.0f;
	}

	/**
	 * 把参数名转换为字段名，把中文转换为拼音，特殊字符转换为下划线
	 * 
	 * @param string
	 * @return
	 */
	public static String pinyin(String string) {
		Pattern pattern = Pattern.compile("[\\u4e00-\\u9fa5]");
		Pattern pattern2 = Pattern.compile("^\\w+$");
		char ch[] = string.toCharArray();
		StringBuffer sb = new StringBuffer();

		for (int i = 0; i < ch.length; i++) {
			String t = String.valueOf(ch[i]);
			Matcher matcher = pattern.matcher(t);
			Matcher matcher2 = pattern2.matcher(t);
			if (matcher.find()) {// 匹配中文
				String pinyin = PinyinHelper.toHanyuPinyinStringArray(matcher
						.group().toCharArray()[0])[0].replaceAll("\\d+", "")
						.replace("u:", "v");
				sb.append(pinyin);
			} else {
				if (matcher2.find()) {// 匹配字母数字下划线

				} else {
					t = "_";
				}
				sb.append(t);
			}
		}

		return sb.toString();
	}

	Date lastDataTime = new Date();//为了统计SOC，对前后两个数据的时间间隔做限制，大于30秒的数据丢弃掉
	public void dataAnalysis(Data data, List<MyDParam> myDParams,
			AveData aveData, boolean first) {

		aveData.setLastTime(data.getTime());

		aveData.setVin(vin);

		aveData.setRegName(regName);

		aveData.setIsOver(1);

		byte[] datas = data.getDatas();

		float speedForFlue = 0;

		for (MyDParam myDParam : myDParams) {
			float result = getParam(datas, myDParam);
			String pinyin = myDParam.getdParam().getNamePinYin();

			switch (pinyin.toLowerCase()) {
			case "chesu":
				if (result >= 0 && result <= 20) {
					aveData.setCount1(aveData.getCount1() + 1);
				} else if (result > 20 && result <= 40) {
					aveData.setCount2(aveData.getCount2() + 1);
				} else if (result > 40 && result <= 60) {
					aveData.setCount3(aveData.getCount3() + 1);
				} else if (result > 60 && result <= 80) {
					aveData.setCount4(aveData.getCount4() + 1);
				} else if (result > 80 && result <= 100) {
					aveData.setCount4(aveData.getCount4() + 1);
				} else {
					aveData.setCount6(aveData.getCount6() + 1);

				}

				float maxSpeed = aveData.getMaxSpeed();
				aveData.setMaxSpeed(maxSpeed > result ? maxSpeed : result);
				aveData.setTotalMileage(aveData.getTotalMileage() + result
						* (2.0f / 3600.0f));// 里程 = 瞬时速度（KM/H） * 时间 --> 时间 2s =
											// 2 /
				// (60 * 60) H

				aveData.setTotalSpeed(aveData.getTotalSpeed() + result);

				speedForFlue = result;

				aveData.setSpeedNum(aveData.getSpeedNum() + 1);

				break;

			case "dianjikongzhiqidianliu":
				if (result > 0) {
					aveData.setTotalMotorConApos(aveData.getTotalMotorConApos()
							+ result);

					aveData.setMotorConAposNum(aveData.getMotorConAposNum() + 1);
				} else {
					aveData.setTotalMotorConAneg(aveData.getTotalMotorConAneg()
							+ result);

					aveData.setMotorConAnegNum(aveData.getMotorConAnegNum() + 1);
				}
				break;
			case "fadianjikongzhishurudianliu":
				if (result > 0) {
					aveData.setTotalGeneratorConApos(aveData
							.getTotalGeneratorConApos() + result);

					aveData.setGeneratorConAposNum(aveData
							.getGeneratorConAposNum() + 1);

				} else {
					aveData.setTotalGeneratorConAneg(aveData
							.getTotalGeneratorConAneg() + result);

					aveData.setGeneratorConAnegNum(aveData
							.getGeneratorConAnegNum() + 1);

				}
				break;

			case "shunshiyouhao": // 暂时用瞬时油耗代替油耗
				float flue = 0;
				if (speedForFlue == 0) {// 运动的时候：(瞬时油耗/100)*（速度*2/3600）,
										// 停止的时候：瞬时油耗*2/3600
					flue = result * 2.0f / 3600.0f;

				} else {
					flue = (result / 100.0f) * (speedForFlue * 2.0f / 3600.0f);
				}
				flue = flue > 0.03f ? 0.03f : flue;// 大于0.03的只取0.03，防止数据突变
				aveData.setTotalFlue(aveData.getTotalFlue() + flue);

				aveData.setFlueNum(aveData.getFlueNum() + 1);
				break;

			case "dianjikongzhiqiqianduandianya":
				aveData.setTotalMotorConV(aveData.getTotalMotorConV() + result);

				aveData.setMotorConVNum(aveData.getMotorConVNum() + 1);
				break;

			case "soc":
				if (first) {// 初始化startSOCs
					aveData.setStartSOC(result);
					aveData.setEndSOC(result);
				}

				float soc = result - aveData.getEndSOC();
				// if((-soc) > 0.0){
				// System.out.println("  soc  is :" + soc);
				// }
				long gapTime = (data.getTime().getTime() - lastDataTime.getTime());
				if (soc < 0.0 && soc > -3.0 && speedForFlue > 0.0 && gapTime > 0 &&  gapTime < 30 * 1000) {// 后一个soc大于前一个，证明充电了，暂时把这些数据丢去，差值大于3，数据突变了
					aveData.setTotalSOC(aveData.getTotalSOC() + (-soc));// 取正数
				} else {
				}
				
				aveData.setSOCNum(aveData.getSOCNum() + 1);

				aveData.setEndSOC(result);
				break;

			case "jiasutaban":
				aveData.setTotalAccel(aveData.getTotalAccel() + result);

				aveData.setAccelNum(aveData.getAccelNum() + 1);
				break;

			case "zhidongtaban":
				aveData.setTotalBrake(aveData.getTotalBrake() + result);

				aveData.setBrakeNum(aveData.getBrakeNum() + 1);
				break;

			case "fadongjishijiyoumen":
				aveData.setTotalEngineThrottle(aveData.getEngineThrottleNum()
						+ result);

				aveData.setEngineThrottleNum(aveData.getEngineThrottleNum() + 1);
				break;

			case "chongfangdiannengliang":
				aveData.setTotalPower(aveData.getTotalPower() + result);

				aveData.setPowerNum(aveData.getPowerNum() + 1);
				break;

			case "dianchidianya":
				aveData.setTotalBatteryV(aveData.getTotalBatteryV() + result);

				aveData.setBatteryVNum(aveData.getBatteryVNum() + 1);
				break;

			case "dianchizongdianya":
				aveData.setTotalBatteryV_all(aveData.getTotalBatteryV_all()
						+ result);

				aveData.setBatteryV_allNum(aveData.getBatteryV_allNum() + 1);
				break;

			case "dianchidianliu":
				aveData.setTotalBatteryA(aveData.getTotalBatteryA() + result);

				aveData.setBatteryANum(aveData.getBatteryANum() + 1);
				break;

			case "dianchizongdianliu":
				aveData.setTotalBatteryA_all(aveData.getTotalBatteryA_all()
						+ result);

				aveData.setBatteryA_allNum(aveData.getBatteryA_allNum() + 1);
				break;

			case "dianchiwendu":
				aveData.setTotalBatteryTem(aveData.getTotalBatteryTem()
						+ result);

				aveData.setBatteryTemNum(aveData.getBatteryTemNum() + 1);
				break;

			case "dianjikongzhiqiwendu":
				aveData.setTotalMotorConTem(aveData.getTotalMotorConTem()
						+ result);

				aveData.setMotorConTemNum(aveData.getMotorConTemNum() + 1);
				break;

			case "dianjidianya":
				aveData.setTotalMotorV(aveData.getTotalMotorV() + result);

				aveData.setMotorVNum(aveData.getMotorVNum() + 1);
				break;

			case "dianjiwendu":
				aveData.setTotalMotorTem(aveData.getTotalMotorTem() + result);

				aveData.setMotorTemNum(aveData.getMotorTemNum() + 1);
				break;

			case "dianjizhuanju":
				aveData.setTotalMotorTor(aveData.getTotalMotorTor() + result);

				aveData.setMotorTorNum(aveData.getMotorTorNum() + 1);
				break;

			case "dianjizhuansu":
				aveData.setTotalMotorRot(aveData.getTotalMotorRot() + result);

				aveData.setMotorRotNum(aveData.getMotorRotNum() + 1);
				break;

			case "dcdcshuchudianya":
				aveData.setTotalDCDC_V(aveData.getTotalDCDC_V() + result);

				aveData.setDCDC_VNum(aveData.getDCDC_VNum() + 1);
				break;

			case "dcdcshuchudianliu":
				aveData.setTotalDCDC_A(aveData.getTotalDCDC_A() + result);

				aveData.setDCDC_ANum(aveData.getDCDC_ANum() + 1);
				break;

			case "dcdcwendu":
				aveData.setTotalDCDC_Tem(aveData.getTotalDCDC_Tem() + result);

				aveData.setDCDC_TemNum(aveData.getDCDC_TemNum() + 1);
				break;

			default:
				break;
			}

		}

	}

	public void warnAnalysis(AveData aveData, List<WarnStat> list,
			Date gapStartTime, Date gapEndTime) {
		int allWarnNum = 0;
		int allWarnTime = 0;
		int energyStorageWarn = 0;
		int driveWarn = 0;
		int auxiliaryWarn = 0;
		int otherWarn = 0;
		int energyStorageWarnTime = 0;
		int driveWarnTime = 0;
		int auxiliaryWarnTime = 0;
		int otherWarnTime = 0;

		Date allWarnStartInGap, allWarnEndInGap, energyStorageWarnStartInGap, energyStorageWarnEndInGap, driveWarnStartInGap, driveWarnEndInGap, auxiliaryWarnStartInGap, auxiliaryWarnEndInGap, otherWarnStartInGap, otherWarnEndInGap;// 标记故障开始时间，如果开始时间一样，则故障时间不叠加
		allWarnStartInGap = new Date(gapStartTime.getTime());
		allWarnEndInGap = new Date(gapStartTime.getTime());
		energyStorageWarnStartInGap = new Date(gapStartTime.getTime());
		energyStorageWarnEndInGap = new Date(gapStartTime.getTime());
		driveWarnStartInGap = new Date(gapStartTime.getTime());
		driveWarnEndInGap = new Date(gapStartTime.getTime());
		auxiliaryWarnStartInGap = new Date(gapStartTime.getTime());
		auxiliaryWarnEndInGap = new Date(gapStartTime.getTime());
		otherWarnStartInGap = new Date(gapStartTime.getTime());
		otherWarnEndInGap = new Date(gapStartTime.getTime());

		for (WarnStat warnStat : list) {
			Date warnEndTime = warnStat.getEndTime();
			Date warnStartTime = warnStat.getStartTime();

			if (warnEndTime.after(gapStartTime)
					&& warnStartTime.before(gapEndTime)) {// 获取统计区间的报警数据
				String warnSystem = warnStat.getWarnSystem();
				allWarnNum += 1;
				allWarnTime += warnTimeAnalysis(gapStartTime, gapEndTime,
						warnStartTime, warnEndTime, allWarnStartInGap,
						allWarnEndInGap);
				switch (warnSystem) {
				case "储能系统":
					energyStorageWarn += 1;
					energyStorageWarnTime += warnTimeAnalysis(gapStartTime,
							gapEndTime, warnStartTime, warnEndTime,
							energyStorageWarnStartInGap,
							energyStorageWarnEndInGap);
					break;
				case "驱动系统":
					driveWarn += 1;
					driveWarnTime += warnTimeAnalysis(gapStartTime, gapEndTime,
							warnStartTime, warnEndTime, driveWarnStartInGap,
							driveWarnEndInGap);
					break;
				case "辅助系统":
					auxiliaryWarn += 1;
					auxiliaryWarnTime += warnTimeAnalysis(gapStartTime,
							gapEndTime, warnStartTime, warnEndTime,
							auxiliaryWarnStartInGap, auxiliaryWarnEndInGap);
					break;
				default: // 属于其他系统
					otherWarn += 1;
					otherWarnTime += warnTimeAnalysis(gapStartTime, gapEndTime,
							warnStartTime, warnEndTime, otherWarnStartInGap,
							otherWarnEndInGap);
					break;
				}

			}
			if (warnStartTime.after(gapEndTime)) {// list里面的数据是按照startTime排序的，如果有一个数据超过endTime，后面的都超过
				break;
			}
		}

		aveData.setWarnNum(allWarnNum);
		aveData.setWarnTime(allWarnTime);
		aveData.setEnergyStorageWarn(energyStorageWarn);
		aveData.setEnergyStorageWarnTime(energyStorageWarnTime);
		aveData.setDriveWarn(driveWarn);
		aveData.setDriveWarnTime(driveWarnTime);
		aveData.setAuxiliaryWarn(auxiliaryWarn);
		aveData.setAuxiliaryWarnTime(auxiliaryWarnTime);
		aveData.setOtherWarn(otherWarn);
		aveData.setOtherWarnTime(otherWarnTime);

	}

	public AveData finishData(AveData aveData, List<AveData> avedataList)
			throws Exception {
		SimpleDateFormat sdf = null;
		int timeType = aveData.getTimeType();
		if (timeType == 1440) {
			sdf = day_sdf;
		} else if (timeType == 60) {
			sdf = hour_sdf;
		} else {
			sdf = min_sdf;
		}

		long gapTime = 0;
		int listSize = 0;
		switch (aveData.getTimeType()) {
		case 1:
			gapTime = oneMin;
			listSize = 300;
			break;
		case 5:
			gapTime = fiveMin;
			listSize = 100;
			break;
		case 10:
			gapTime = tenMin;
			listSize = 80;
			break;
		case 30:
			gapTime = thirtyMin;
			listSize = 70;
			break;
		case 60:
			gapTime = oneHour;
			listSize = 60;
			break;
		case 1440:
			aveData.setIsAttendance((aveData.getTotalMileage() > 5.0 ? 1 : 0));
			gapTime = oneDay;
			listSize = 5;
			break;

		default:
			break;
		}
		Date time = sdf.parse(sdf.format(aveData.getLastTime()));
		float soc = aveData.getEndSOC();
		Date startTime = aveData.getStartTime();
		aveData.setOnlineStime(startTime);
		aveData.setOnlineEtime(new Date(startTime.getTime() + gapTime));// st =
																		// 09:43:14
																   		// -- et
																		// =
																		// 12:21:12
																		// 这种情况
																		// 用09:44:00结尾
		aveData.setOnlineTime((int) (gapTime / 1000.0f));// 秒为单位

		avedataList.add(aveData);
		if (avedataList.size() > listSize) {
			 aveDataDao.add(avedataList, vin);
			avedataList.clear();
		}

		AveData newAveData = new AveData();
		newAveData.setStartTime(time);
		newAveData.setStartSOC(soc);
		newAveData.setEndSOC(soc);
		newAveData.setTimeType(aveData.getTimeType());
		return newAveData;
	}

	public int warnTimeAnalysis(Date gapStartTime, Date gapEndTime,
			Date warnStartTime, Date warnEndTime, Date warnStartInGap,
			Date warnEndInGap) {
		int warnTime = 0;
		if (warnEndInGap.after(warnEndTime)) {

		} else {

			if (warnStartInGap.after(warnStartTime)) {
				warnStartInGap.setTime(warnStartInGap.getTime());
			} else {
				warnStartInGap.setTime(warnStartTime.getTime());
			}

			if (warnEndTime.after(gapEndTime)) {
				warnEndInGap.setTime(gapEndTime.getTime());
			} else {
				warnEndInGap.setTime(warnEndTime.getTime());
			}

			warnTime = (int) ((warnEndInGap.getTime() - warnStartInGap
					.getTime()) / 1000.0f);

			warnStartInGap.setTime(warnEndInGap.getTime());
		}
		return warnTime;
	}

	public void updateProcessing(Processing processing) {
		List<Processing> list = Processer.vintime_map.get(vin);
		if (list == null) {
			list = Collections.synchronizedList(new ArrayList<Processing>());
			Processer.vintime_map.put(vin, list);
		}
		Processing p = null;

		for (int i = 0; i < list.size(); i++) {
			Processing processing2 = list.get(i);
			if (processing2.getTimeType() == processing.getTimeType()) {
				p = processing2;
				list.remove(processing2);
			}
		}

		if (p == null) {
			processingService.add(processing);
		} else {
			processingService.update(processing);
		}
		list.add(processing);
	}

}
