package com.fcv.vms.server;

import java.io.FileInputStream;
import java.io.IOException;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;
import java.util.concurrent.ConcurrentHashMap;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import org.apache.commons.collections.map.HashedMap;
import org.apache.poi.hssf.usermodel.*;  
import org.apache.poi.ss.usermodel.DataFormatter;  
import org.apache.poi.ss.usermodel.DateUtil;  
import org.apache.poi.xssf.usermodel.XSSFCell;  
import org.apache.poi.xssf.usermodel.XSSFRow;  
import org.apache.poi.xssf.usermodel.XSSFSheet;  
import org.apache.poi.xssf.usermodel.XSSFWorkbook;  
import org.hibernate.Session;
import org.hibernate.Transaction;
import org.hibernate.transform.Transformers;

import com.fcv.vms.dao.DParam;
import com.fcv.vms.model.HibernateModel;
import com.fcv.vms.model.HibernateSessionFactory;

import net.sourceforge.pinyin4j.PinyinHelper;
/*
 * 用excle表去修改dparam表的标准字段
 */
public class ExcleToParam {
	 public static List<Map> readExcel2007(String filePath) throws IOException{  
	        List<Map> valueList=new ArrayList<Map>();  
	        FileInputStream fis =null;  
	        try {  
	            fis =new FileInputStream(filePath);  
	            XSSFWorkbook xwb = new XSSFWorkbook(fis);   // 构造 XSSFWorkbook 对象，strPath 传入文件路径  
	            XSSFSheet sheet = xwb.getSheetAt(0);            // 读取第一章表格内容  
	            // 定义 row、cell  
	            XSSFRow row;  
	            // 循环输出表格中的第一行内容   表头  
	            Map<Integer, String> keys=new HashMap<Integer, String>();  
	            row = sheet.getRow(1);  
	            if(row !=null){  
	                //System.out.println("j = row.getFirstCellNum()::"+row.getFirstCellNum());  
	                //System.out.println("row.getPhysicalNumberOfCells()::"+row.getPhysicalNumberOfCells());  
	            	int num = row.getPhysicalNumberOfCells();
	                for (int j = row.getFirstCellNum(); j <=row.getPhysicalNumberOfCells(); j++) {  
	                    // 通过 row.getCell(j).toString() 获取单元格内容，  
	                    if(row.getCell(j)!=null){  
	                        if(!row.getCell(j).toString().isEmpty()){  
	                            keys.put(j, row.getCell(j).toString());  
	                        }  
	                    }else{  
	                        keys.put(j, "K-R1C"+j+"E");  
	                    }  
	                }  
	            }  
	            // 循环输出表格中的从第二行开始内容  
	            for (int i = sheet.getFirstRowNum() + 1; i <= sheet.getPhysicalNumberOfRows(); i++) {  
	                row = sheet.getRow(i);  
	                if (row != null) {  
	                    boolean isValidRow = false;  
	                    Map<String, Object> val = new HashMap<String, Object>();  
	                    for (int j = row.getFirstCellNum(); j <= row.getPhysicalNumberOfCells(); j++) {  
	                        XSSFCell cell = row.getCell(j);  
	                        if (cell != null) {  
	                            String cellValue = null;  
	                            if(cell.getCellType()==XSSFCell.CELL_TYPE_NUMERIC){  
	                                if(DateUtil.isCellDateFormatted(cell)){  
	                                    cellValue = new DataFormatter().formatRawCellContents(cell.getNumericCellValue(), 0, "yyyy-MM-dd HH:mm:ss");  
	                                }  
	                                else{  
	                                    cellValue = String.valueOf(cell.getNumericCellValue());  
	                                }  
	                            }  
	                            else{  
	                                cellValue = cell.toString();  
	                            }  
	                            if(cellValue!=null&&cellValue.trim().length()<=0){  
	                                cellValue=null;  
	                            }  
	                            val.put(keys.get(j), cellValue);  
	                            if(!isValidRow && cellValue!= null && cellValue.trim().length()>0){  
	                                isValidRow = true;  
	                            }  
	                        }  
	                    }  
	  
	                    // 第I行所有的列数据读取完毕，放入valuelist  
	                    if (isValidRow) {  
	                        valueList.add(val);  
	                    }  
	                }  
	            }  
	        } catch (Exception e) {  
	            e.printStackTrace();  
	        }finally {  
	            fis.close();  
	        }  
	        return valueList;  
	    }  
	
	
    public static void main(String[] args) throws Exception {
    	
    	
    	Session session = null;
		Transaction tx = null;
		
//      HibernateModel.createRegTable();
      // 载入驱动
      Class.forName("com.mysql.jdbc.Driver");
      // 建立连接
      Connection con = DriverManager
              .getConnection(
                      "jdbc:mysql://cenyanmysql.mysql.rds.aliyuncs.com:3306/vms?useUnicode=true&amp;zeroDateTimeBehavior=convertToNull&amp;characterEncoding=UTF-8",
                      "cenyan", "cenyan");
    	
    	
    	
     	List<Map> list = new ArrayList<Map>();
    	try {
    		list = readExcel2007("C:/Users/lalio/Desktop/123.xlsx");
		} catch (Exception e) {
			e.printStackTrace();
		}
		
    	
        Statement stmt = con.createStatement();
        String sql = "SELECT id,dparamname,markid,namepinyin  FROM DPARAM";
        ResultSet rs = stmt.executeQuery(sql);
        String id  ;
        String name;
        int markId;
        String namepinyin;
        while(rs.next()){//三层循环，第一层是dparam，第二层是excle，第三层是excle里面的map
        	id = rs.getString("id");
        	name = rs.getString("dparamname");
        	markId = rs.getInt("markid");
        	namepinyin = rs.getString("namepinyin");
    		int flag = 0;

        	for (Map<?, ?> map :list) {
        		Iterator i = map.entrySet().iterator();
        		while(i.hasNext()){
        			Entry entry = (Entry) i.next(); 
        			Object val = entry.getValue();
        			int key = (int) Float.parseFloat(entry.getKey().toString().equals("标准字段名") ? " -1" : entry.getKey().toString() );
        			if(val != null && name.equals( val.toString()) && key == markId){
        				String nameType = map.get("标准字段名").toString();
        				String namePinYin = pinyin(nameType);
        				Statement updateStmt = con.createStatement();
        				String updateSQL = "UPDATE dparam SET dparamnametype = '"+nameType+"' , namepinyin = '"+namePinYin+"'  WHERE ID =  '"+id+"'";
        				updateStmt.executeUpdate(updateSQL);
        				flag = 1;
        				break;
        			}
        		}
        		if(flag == 1){
        			break;
        		} 
    		}
        	
        	if (flag == 0 && (namepinyin == null || namepinyin.equals("")) ) {//把没有进行归类的参数，按照原来的参数名填充dparamnametype 和 namepinyin
        		String nameType = name;
				String namePinYin = pinyin(nameType);
				Statement updateStmt = con.createStatement();
				String updateSQL = "UPDATE dparam SET dparamnametype = '"+nameType+"' , namepinyin = '"+namePinYin+"'  WHERE ID =  '"+id+"'";
				updateStmt.executeUpdate(updateSQL);
			}
        	
        	
        }
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
////   删除所有子表    
//      	Statement stmt = con.createStatement();
//        String sqlString  = "select * from INFORMATION_SCHEMA.tables where table_name like 'z_param_%'";
//        ResultSet  rSet = stmt .executeQuery(sqlString);
//        int num = 0;
//        while(rSet.next()){
//        	String sql = "drop table "+rSet.getString("table_name");
//        	Statement stmt2 = con.createStatement();
//        	stmt2.execute(sql);
//        	System.out.println(++num + "删除了："+rSet.getString("table_name"));
//        	
//        }
        
        
//      //创建状态
//      	Statement stmt = con.createStatement();
//        String allvin = "select vin,count(*) as count from data group by vin order by count desc limit 100";
//        ResultSet resultSet = stmt.executeQuery(allvin);
//        while (resultSet.next()) {
//            try {
//                String vin = resultSet.getString("vin");
////                HibernateModel model= new HibernateModel();
////                model.createTable(vin);
//                int count = resultSet.getInt("count");
//                String tableNameString = "z_data_" + vin.toLowerCase();
//                System.out.println(vin + " " + count + " " + tableNameString);
//                Statement exitsStatement = con.createStatement();
//                String exitSql = "select * from INFORMATION_SCHEMA.tables where table_name = '" + tableNameString + "'";
//                exitsStatement.execute(exitSql);
//                if(!exitsStatement.getResultSet().next())
//                {
//                    //create 
//                	System.out.println("创建了表"+tableNameString);
//                    Statement createStatement = con.createStatement();
//                    String sqlString = "CREATE TABLE " + tableNameString + " select * from data WHERE 1=2";
//                    createStatement.execute(sqlString);
//                    createStatement.close();
//                } 
//                else {
//                    //drop
//                    Statement dropStatement = con.createStatement();
//                    String sqlString = "drop TABLE " + tableNameString;
//                    dropStatement.execute(sqlString);
//                    dropStatement.close();
//                    
//                    //create
//                    Statement createStatement = con.createStatement();
//                    String createString = "CREATE TABLE " + tableNameString + " select * from data WHERE 1=2";
//                    createStatement.execute(createString);
//                    createStatement.close();
//                }
//                
//                exitsStatement.close();
//
//                Statement updateStatement = con.createStatement();
//                String updateSql = "INSERT INTO " + tableNameString +" SELECT * FROM data WHERE data.vin='"+vin+"' ";
//                updateStatement.execute(updateSql);
//                updateStatement.close();
//            } catch (Exception e) {
//                e.printStackTrace();
//            }
//           
//        }
//        
//        //删除data表里的所有数据
////        Statement createStatement = con.createStatement();
////        String sqlString = "DELETE FROM data WHERE 1 = 1";
////        createStatement.execute(sqlString);
////        createStatement.close();
//      
//        
//        
//        stmt.close();
//        con.close();
    }
    
    
    
    
    
    public static long getUnsignedInt(int x) {
        return x & 0x00000000ffffffffL;
    }
    
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
						.group().toCharArray()[0])[0].replaceAll("\\d+", "").replace("u:","v");
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
