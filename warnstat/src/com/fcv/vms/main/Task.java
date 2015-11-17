package com.fcv.vms.main;

import java.math.BigInteger;
import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import org.springframework.web.servlet.tags.EscapeBodyTag;

import net.sourceforge.pinyin4j.PinyinHelper;

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
	private Date startDate;
	private Date endDate;

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
				dParams = new ArrayList<DParam>();
			}
			List<MyDParam> myDParams = getDParams(dParams);
			begin(vin, myDParams);
			Processer.threadNum.getAndDecrement();
		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	public int moduleValue = 0;
	public static long fiveMin = 1000 * 60 * 5;
	public static long oneMin = 1000 * 60;
	public static long twoSec = 1000 * 2;
	public int speedNum = 0;

	private void begin(String vin, List<MyDParam> myDParams) throws Exception {
		Map<String, Boolean> warn_map = new HashMap<String, Boolean>();
		List<WarnStat> warn_list = new ArrayList<WarnStat>();
		Map<String, WarnStat> lastwarn_map = new HashMap<String, WarnStat>();

		Processing processing = new Processing();
		Date endTime = startDate;
		processing.setEndTime(endTime);
		processing.setVin(vin);
		// vin = "A000004E19925D4V4";// test
		// vin = "A000004E8EB0401V4";//test
		List<Data> car_list = dataService.getAllDatas(vin, startDate);
		Data data;
		MyDParam myDParam;
		int size = myDParams.size();

		if (size == 0) {
			System.out.println(vin + " has not param to analys");

			if (Processer.vintime_map.get(vin) == null) {
				processingService.add(processing);
			} else {
				processingService.update(processing);
			}
			Processer.vintime_map.put(vin, endTime);

			return;
		}
		if (car_list.size() == 0) {
			System.out.println(vin + " has not data to analys");

			if (Processer.vintime_map.get(vin) == null) {
				processingService.add(processing);
			} else {
				processingService.update(processing);
			}
			Processer.vintime_map.put(vin, endTime);

			return;
		}

		lastwarn_map = warnStatService.getLastWarn(vin);
		for (int i = 0; i < size; i++) {// 逐个分析参数,初始化标志是否报警的map
			if (myDParams.get(i).getdParam().getDparamNewAgreeType() == 1) {
				String dparamname = myDParams.get(i).getdParam()
						.getDparamName();
				if (lastwarn_map.get(dparamname) != null) {
					if (lastwarn_map.get(dparamname).getIsOver() == 0) {//最后一次是故障
						warn_map.put(dparamname, true);
					} else {
						warn_map.put(dparamname, false);
					}
				} else {//不报警的参数
				}
			}
		}

		// 获取分析后的数据，并插入表中
		for (Iterator<Data> iterator = car_list.iterator(); iterator.hasNext();) {
			data = iterator.next();

			byte[] bDatas = null;

			for (int i = 0; i < size; i++) {// 逐个分析参数
				myDParam = myDParams.get(i);
				String dparamName = myDParam.getdParam().getDparamName();
				bDatas = data.getDatas();
				endTime = data.getTime();
				processing.setEndTime(endTime);
				float param = getParam(bDatas, myDParam);// 解析数据

				if (myDParam.getdParam().getDparamNewAgreeType() == 1) {

					Map<Integer, DparamMapValue> map = myDParam.getDmv();

					DparamMapValue dmv = map.get((int) param);

					if (dmv != null) {
						String warnMsg = dmv.getValue();
						if (warnMsg.equals("正常") || warnMsg.equals("均衡")
								|| warnMsg.equals("合格") || warnMsg.equals("运行")
								|| warnMsg.equals("normal")
								|| warnMsg.equals("无故障")
								|| warnMsg.equals("电池正常")
								|| warnMsg.equals("断开")) { // 正常状态,直接过滤掉

						} else {//故障状态
							WarnStat lastwarn = lastwarn_map.get(dparamName);
							if (lastwarn != null) {
								if (lastwarn.getIsOver() == 0 && endTime.getTime() - lastwarn.getEndTime().getTime() > 1000 * 60 * 10) {//超过十分钟，则结束之前的故障
									//结束故障
									lastwarn.setIsOver(1);
									lastwarn.setWarnTime((int) ((lastwarn.getEndTime().getTime() - lastwarn
											.getStartTime().getTime()) / 1000) + 2);//加两秒是因为一个故障最少发生了两秒
									warn_list.add(lastwarn);
									//新建故障
									lastwarn = new WarnStat();
									int dParamId = myDParam.getdParam().getId();
									lastwarn.setVin(vin);
									lastwarn.setWarnName(dparamName);
									lastwarn.setDetail(warnMsg);
									lastwarn.setdParamId(dParamId);
									lastwarn.setStartTime(endTime);
									lastwarn.setEndTime(endTime);
									lastwarn.setWarnSystem(myDParam.getdParam()
											.getDparamType().equals("0") ? "其他系统"
											: myDParam.getdParam().getDparamType());
									lastwarn.setIsOver(0);
									lastwarn_map.put(dparamName, lastwarn);
									
								} else {//不超过十分钟，则算为同一个故障
									lastwarn.setEndTime(endTime);
								}
							} else {
								//新建故障
								lastwarn = new WarnStat();
								int dParamId = myDParam.getdParam().getId();
								lastwarn.setVin(vin);
								lastwarn.setWarnName(dparamName);
								lastwarn.setDetail(warnMsg);
								lastwarn.setdParamId(dParamId);
								lastwarn.setStartTime(endTime);
								lastwarn.setEndTime(endTime);
								lastwarn.setWarnSystem(myDParam.getdParam()
										.getDparamType().equals("0") ? "其他系统"
										: myDParam.getdParam().getDparamType());
								lastwarn.setIsOver(0);
								lastwarn_map.put(dparamName, lastwarn);
							}
							
						}

//						if (warnMsg.equals("正常") || warnMsg.equals("均衡")
//								|| warnMsg.equals("合格") || warnMsg.equals("运行")
//								|| warnMsg.equals("normal")
//								|| warnMsg.equals("无故障")
//								|| warnMsg.equals("电池正常")
//								|| warnMsg.equals("断开")) { // 正常状态
//							if (warn_map.get(dparamName) == true) {// 如果上一次是故障状态，则故障结束,数据插入
//								WarnStat warnStat = warnStats.get(dparamName);
//								warnStat.setEndTime(endTime);
//								warnStat.setWarnTime((int) ((endTime.getTime() - warnStat
//										.getStartTime().getTime()) / 1000));
//								warnStat.setIsOver(1);
//								warn_list.add(warnStat);
//								warnStats.remove(dparamName);
//
//								warn_map.put(dparamName, false);
//							} else {// 如果上次也是正常的
//							}
//
//						} else {// 故障状态
//							if (warn_map.get(dparamName) == true) {// 如果上一次也是故障状态
//
//							} else {// 如果上次是正常的，则开始新的故障
//								WarnStat warnStat = new WarnStat();
//								int dParamId = myDParam.getdParam().getId();
//								warnStat.setVin(vin);
//								warnStat.setWarnName(dparamName);
//								warnStat.setStartTime(endTime);
//								warnStat.setDetail(warnMsg);
//								warnStat.setdParamId(dParamId);
//								warnStat.setWarnSystem(myDParam.getdParam()
//										.getDparamType().equals("0") ? "其他系统"
//										: myDParam.getdParam().getDparamType());
//								warnStat.setIsOver(0);
//								warnStats.put(dparamName, warnStat);
//
//								warn_map.put(dparamName, true);
//							}
//						}

					} else {
					}

				}

			}

			if (warn_list.size() >= 100) {
				// 插入故障
				warnStatService.add(warn_list);
				warn_list.clear();
			}
		}
		// 插入故障
		for (Entry<String, WarnStat> entry : lastwarn_map.entrySet()) {// 把没有结束的报警也插入数据库中
			if(entry.getValue().getIsOver() == 0){
				warn_list.add(entry.getValue());
			}
		}
		warnStatService.add(warn_list);
		warn_list.clear();

		if (Processer.vintime_map.get(vin) == null) {
			processingService.add(processing);
		} else {
			processingService.update(processing);
		}
		Processer.vintime_map.put(vin, endTime);
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
					param = Math.round((tmpFloat * factory + offset) * 10) / 10;
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
					try {
						dmv.setValue(elements[1]);
					} catch (ArrayIndexOutOfBoundsException e) {
						dmv.setValue("110");
						map.put(key, dmv);
					}

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
		return (float) Math.round((num) * 100) / 100;
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

}
