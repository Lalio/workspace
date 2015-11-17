//package com.lkm.hellloworld;
//
//import java.security.MessageDigest;
//import java.security.NoSuchAlgorithmException;
//import java.util.List;
//
//import com.google.gson.Gson;
//import com.google.gson.reflect.TypeToken;
//
//public class HelloWorld {
//
//	public static void main(String[] args) throws Exception {
//		try {
//			Gson g = new Gson();
//
//			Package<Nurse> nurses = g.fromJson(NURSE,
//					new TypeToken<Package<Nurse>>() {
//					}.getType());
//			for (Nurse e : nurses.data) {
//				System.out.println(e.id + " : " + e.name);
//			}
//
//			Package<User> users = g.fromJson(USER,
//					new TypeToken<Package<User>>() {
//					}.getType());
//			for (User e : users.data) {
//				System.out.println(e.username + " : " + e.password);
//			}
//
//			Package<Food> foods = g.fromJson(FOOD,
//					new TypeToken<Package<Food>>() {
//					}.getType());
//			for (Food e : foods.data) {
//				System.out.println(e.name + " : " + e.ingredients[0] + ","
//						+ e.ingredients[1]);
//			}
//
//			List<University> lu = g.fromJson(s,
//					new TypeToken<List<University>>() {
//					}.getType());
//
//			// University ml = g.fromJson(s, University.class);
//
//			System.out.println(g.toJson(lu));
//
//			for (College cg : lu.get(0).schoolList)
//				System.out.println(cg);
//
//			
//			System.out.println(encodeSHA1("123456"));
//		} catch (Exception e) {
//			e.printStackTrace();
//		}
//
//	
//	
//	
//	
//	
//	
//	
//	
//	
//	}
//
//	
//	
//	
//	
//	
//	
//	
//	
//	
//	
//	
//	
//	
//	
//	
//	
//	
//	
//	public final static String NURSE = "{'data':["
//			+ "{'id':'497','name':'梁静文'}," + "{'id':'301','name':'黄思婷'},"
//			+ "{'id':'498','name':'刘彩凤'}," + "{'id':'507','name':'成振华'},"
//			+ "{'id':'523','name':'陈威'}]," + "'success':true,'size':5}";
//
//	public final static String USER = "{'data':[{'username':'apple','password':'123456'},{'username':'ibm','password':'abcdefg'}],'success':true,'size':2}";
//
//	public final static String FOOD = "{'data':[{'name':'铁板牛肉','ingredients':['牛肉','铁板']},{'name':'水煮牛肉','ingredients':['牛肉','水']}],'success':true,'size':2}";
//
//	static String s = "[{\"province\":\"华南师范大学\",\"schoolList\":[{\"code\":\"文学院\",\"name\":\"文学院\",\"isCollege\":true},{\"code\":\"教育科学学院\",\"name\":\"教育科学学院\",\"isCollege\":true},{\"code\":\"心理学院\",\"name\":\"心理学院\",\"isCollege\":true},{\"code\":\"历史文化学院\",\"name\":\"历史文化学院\",\"isCollege\":true},{\"code\":\"政治与行政学院\",\"name\":\"政治与行政学院\",\"isCollege\":true},{\"code\":\"法学院\",\"name\":\"法学院\",\"isCollege\":true},{\"code\":\"经济与管理学院\",\"name\":\"经济与管理学院\",\"isCollege\":true},{\"code\":\"外国语言文化学院\",\"name\":\"外国语言文化学院\",\"isCollege\":true},{\"code\":\"体育科学学院\",\"name\":\"体育科学学院\",\"isCollege\":true},{\"code\":\"生命科学学院\",\"name\":\"生命科学学院\",\"isCollege\":true},{\"code\":\"教育信息技术学院\",\"name\":\"教育信息技术学院\",\"isCollege\":true},{\"code\":\"物理与电信工程学院\",\"name\":\"物理与电信工程学院\",\"isCollege\":true},{\"code\":\"数学科学学院\",\"name\":\"数学科学学院\",\"isCollege\":true},{\"code\":\"化学与环境学院\",\"name\":\"化学与环境学院\",\"isCollege\":true},{\"code\":\"地理科学学院\",\"name\":\"地理科学学院\",\"isCollege\":true},{\"code\":\"计算机学院\",\"name\":\"计算机学院\",\"isCollege\":true},{\"code\":\"软件学院\",\"name\":\"软件学院\",\"isCollege\":true},{\"code\":\"美术学院\",\"name\":\"美术学院\",\"isCollege\":true},{\"code\":\"音乐学院\",\"name\":\"音乐学院\",\"isCollege\":true},{\"code\":\"信息光电子科技学院\",\"name\":\"信息光电子科技学院\",\"isCollege\":true},{\"code\":\"公共管理学院\",\"name\":\"公共管理学院\",\"isCollege\":true},{\"code\":\"旅游管理系\",\"name\":\"旅游管理系\",\"isCollege\":true},{\"code\":\"城市文化学院\",\"name\":\"城市文化学院\",\"isCollege\":true},{\"code\":\"国际商学院\",\"name\":\"国际商学院\",\"isCollege\":true},{\"code\":\"职业教育学院\",\"name\":\"职业教育学院\",\"isCollege\":true},{\"code\":\"南海学院\",\"name\":\"南海学院\",\"isCollege\":true}]}]";
//
//	public static String encodeSHA1(String decript) {
//		try {
//			MessageDigest digest = java.security.MessageDigest
//					.getInstance("SHA-1");
//			digest.update(decript.getBytes());
//			byte messageDigest[] = digest.digest();
//			StringBuffer hexString = new StringBuffer();
//			for (int i = 0; i < messageDigest.length; i++) {
//				String shaHex = Integer.toHexString(messageDigest[i] & 0xFF);
//				if (shaHex.length() < 2) {
//					hexString.append(0);
//				}
//				hexString.append(shaHex.toUpperCase());
//			}
//			return hexString.toString();
//		} catch (NoSuchAlgorithmException e) {
//			e.printStackTrace();
//		}
//		return "";
//	}
//}
