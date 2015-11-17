package com.xyj.test;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.FileWriter;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.ObjectInputStream;
import java.io.ObjectOutputStream;
import java.io.OutputStream;
import java.io.PrintWriter;
import java.io.Serializable;
import java.lang.reflect.Field;
import java.lang.reflect.Method;
import java.math.BigInteger;
import java.net.Socket;
import java.net.UnknownHostException;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Date;
import java.util.HashMap;
import java.util.HashSet;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;
import java.util.Scanner;
import java.util.Set;
import java.util.concurrent.ConcurrentHashMap;
import java.util.regex.Matcher;
import java.util.regex.Pattern;
import java.util.regex.PatternSyntaxException;

import net.sourceforge.pinyin4j.PinyinHelper;
class AaaA{
	int a = 1;
	String name = "bb";
	float b = 1.23f;
	public int getA() {
		return a;
	}
	public void setA(int a) {
		this.a = a;
	}
	public String getName() {
		return name;
	}
	public void setName(String name) {
		this.name = name;
	}
	public float getB() {
		return b;
	}
	public void setB(float b) {
		this.b = b;
	}
	
	
}

public class HelloWorld {
	public void test(){
		
	}
	public static void main(String[] args) throws Exception {
		
		
		String regex = "^.{15}V\\d{1}$";
		Pattern pattern = Pattern.compile(regex);
		Matcher matcher = pattern.matcher("864049024305056V4");
		while (matcher.find()) {
			System.out.println(matcher.group());			
		}
		
		
		
		
		
		
		float result = 0;
		byte[] data = {1,2,3};	
		int[] byteFactor = { 1, 256, 65536, 16777216 };
		for (int i = 0; i < data.length; i++) {
			float a = (data[i] & 0xff) * byteFactor[data.length - 1 - i];
			result += a;
		}
		System.out.println(result);
		

		
		
		
		 Class<?> demo = null;
	        try {
	            demo = Class.forName("com.xyj.test.AaaA");
	        } catch (Exception e) {
	            e.printStackTrace();
	        }
		
		AaaA aa = (AaaA) demo.newInstance();
		Field[] field = demo.getDeclaredFields();
		for (Field field2 : field) {
			
			System.out.println(field2.getType());
		}
		
		Field[] field2 = demo.getFields();
		for (Field field3 : field2) {
			System.out.println("aaa"+field3.getType());
		}
		
//		Method[] method = demo.getMethods();
//		for (Method method2 : method) {
//			method2.invoke(demo.newInstance());
//		}
		Method method = demo.getMethod("getB");
		System.out.println(method.invoke(demo.newInstance()));
		
		System.out.println();
		System.out.println();
		System.out.println(aa.getClass().getCanonicalName());
		System.out.println(aa.getClass().getModifiers());
		System.out.println(aa.getClass().getName());
		System.out.println(aa.getClass().getSimpleName());
		System.out.println(aa.getClass().getTypeName());
		
		
//		File file = new File("C:/HelloWorld.txt");
//		FileOutputStream fos = new FileOutputStream(file);
//		FileWriter pw = new FileWriter(file,true);
//		int num = 1;
//		while (true) {
//			pw = new FileWriter(file,true);
//			pw.append((++num)+"y\r\n");
////			pw.write((++num)+"y\r\n");
//			pw.flush();
//			fos.close();
//			pw.close();
//			Thread.sleep(5000);
//			System.out.println("已经睡了5秒");
//			if (num == 10 || num == 11) {
//				break;
//			}
//		}

		// int num = -1;
		// int result;//num为第num项，result为第num项的值
		// Scanner s = new Scanner(System.in);
		// while (num > 10 || num <= 0) {
		// System.out.println("请输入数列的长度，请确保数值大于0且小于10：");
		// num = s.nextInt();
		//
		// }
		// for (int i = 1; i <= num; i++) {
		// result=function(i);
		// System.out.println("斐波那契数列第"+i+"项为："+result);
		//
		// }
	}

	public static int function(int n) {
		if (n == 1 || n == 2)
			return 1;
		return function(n - 1) + function(n - 2);
	}

	// String string = "发绿色驴动机负荷率";
	// Pattern pattern = Pattern.compile("[\\u4e00-\\u9fa5]");
	// Pattern pattern2 = Pattern.compile("^\\w+$");
	// char ch[] = string.toCharArray();
	// int count = 0;
	//
	// StringBuffer sb = new StringBuffer();
	// for (int i = 0; i < ch.length; i++) {
	// String t = String.valueOf(ch[i]);
	// Matcher matcher = pattern.matcher(t);
	// Matcher matcher2 = pattern2.matcher(t);
	// if (matcher.find()) {
	// sb.append(PinyinHelper.toHanyuPinyinStringArray(matcher.group()
	// .toCharArray()[0])[0].replace("u:", "v"));
	// } else {
	// if (matcher2.find()) {
	//
	// } else {
	// t = "_";
	// }
	// sb.append(t);
	// }
	// }

	// while (matcher.find()) {
	// sb.append(PinyinHelper.toHanyuPinyinStringArray(matcher.group().toCharArray()[0])[0])
	// ;
	// System.out.println(matcher.group()+"              "+ matcher.start());
	// for (int i = 0; i <= matcher.groupCount(); i++) {
	// count = count + 1;
	// }
	// }

	// System.out.println(sb.toString().replaceAll("\\d+", ""));

	// int aa=1 ;
	// System.out.println(aa+(1e20- 1e20));
	// System.out.println("Virtual machine jar:"+System.getProperty("sun.boot.class.path"));
	// System.out.println("Virtual machine Exception:"+System.
	// getProperty("java.ext.dirs"));
	// System.out.println("Virtual machine search class : "+System.getProperty("java.class.path"));
	// System.out.println("Virtual machine value :"+System.getProperty("propertyName"));
	//
	// int a = 1;
	// int b = 1;
	// int result;
	// for (int i = 3; i < 100; i++) {
	// result = a + b;
	// a = b;
	// b = result;
	// }
	//

	// byte a= -6;
	// int aa = a;
	// System.out.println(aa & 0xff);
	// System.out.println(Integer.toBinaryString(a));
	// System.out.println(Integer.toBinaryString(aa));
	// System.out.println(Integer.toBinaryString(aa & 0xff));

	// BigInteger bigInteger =BigInteger.valueOf(111);
	// Long long1 = bigInteger.longValue();
	// SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm");
	// Date date = new Date();
	// System.out.println(sdf.parse("2015-08-12 13:55:23"));

	// Map<String, String> map = new ConcurrentHashMap<String,String>();
	// String aa= new String("aa");
	// map.put(aa, "bb");
	// aa= new String("aa");
	// map.put(aa, "bbb");
	// for(Entry<String, String> entry : map.entrySet()){
	// System.out.println(entry.getKey()+"          "+entry.getValue());
	// }

	// map.put(1, "杩欐槸1");
	//
	// Thread t1 = new Thread(new Runnable() {
	//
	// @Override
	// public void run() {
	// String string = HelloWorld.map.get(1);
	// string = "杩欐槸2";
	// try {
	// Thread.sleep(5000);
	// } catch (InterruptedException e) {
	// // TODO Auto-generated catch block
	// e.printStackTrace();
	// }
	// map.put(1, string);
	// System.err.println("t1:"+map.get(1));
	// }
	// });
	// t1.start();
	//
	// Thread t2= new Thread(new Runnable() {
	//
	// @Override
	// public void run() {
	// String string = HelloWorld.map.get(1);
	// string = "杩欐槸3";
	// map.put(1, string);
	// System.err.println("t2:"+map.get(1));
	// }
	// });
	// t2.start();
	// System.out.println("main"+map.get(1));
	// Thread.sleep(6000);
	// System.out.println("main2"+map.get(1));
	//
	// Date date = new Date();
	// SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd");
	// System.out.println(date);
	//
	// System.out.println(sdf.format(date));
	//
	// System.out.println(sdf.parse(sdf.format(date)));

	// public static void main(String[] args) throws Exception {
	//
	// float a = 222f;
	// System.out.println(a);

	// String aString[] = new String[]{"1","2"};
	// for (int i= 0; i< aString.length; i++) {
	// System.out.println("-------------"+aString[i]);
	// }

	// HashMap<String ,Integer> m = new HashMap<>();
	// //杩欓噷鏄妸瀵硅薄搴忓垪鍖栧埌鏂囦欢
	// Person crab = new Person();
	// crab.setName("Mr.Crab");
	//
	// ObjectOutputStream oo = new ObjectOutputStream
	// (new FileOutputStream("crab_file"));
	// oo.writeObject(crab);
	// oo.close();
	//
	// // 杩欓噷鏄妸瀵硅薄搴忓垪鍖栧埌鏂囦欢锛屾垜浠厛娉ㄩ噴鎺夛紝涓�浼氬効鐢�
	// ObjectInputStream oi = new ObjectInputStream
	// (new FileInputStream("crab_file"));
	// Person crab_back = (Person) oi.readObject();
	// System.out.println("Hi, My name is " + crab_back.getName());
	// oi.close();
	//
	// }
	// }
}