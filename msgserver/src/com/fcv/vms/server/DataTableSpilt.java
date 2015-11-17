package com.fcv.vms.server;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.Statement;

import com.fcv.vms.model.HibernateModel;

public class DataTableSpilt {
    public static void main(String[] args) throws Exception {
       
//        HibernateModel.createRegTable();
        // 载入驱动
        Class.forName("com.mysql.jdbc.Driver");
        // 建立连接
        Connection con = DriverManager
                .getConnection(
                        "jdbc:mysql://cenyanmysql.mysql.rds.aliyuncs.com:3306/vms?useUnicode=true&amp;zeroDateTimeBehavior=convertToNull&amp;characterEncoding=UTF-8",
                        "cenyan", "cenyan");
        // 创建状态
        Statement stmt = con.createStatement();
//   删除所有子表    
//        String sqlString  = "select * from INFORMATION_SCHEMA.tables where table_name like 'z_data_%'";
//        ResultSet  rSet = stmt .executeQuery(sqlString);
//        while(rSet.next()){
//        	String sql = "drop table "+rSet.getString("table_name");
//        	Statement stmt2 = con.createStatement();
//        	stmt2.execute(sql);
//        	System.out.println("删除了："+rSet.getString("table_name"));
//        	
//        }

        String allvin = "select vin,count(*) as count from data group by vin order by count desc limit 100";
        ResultSet resultSet = stmt.executeQuery(allvin);
        while (resultSet.next()) {
            try {
                String vin = resultSet.getString("vin");
//                HibernateModel model= new HibernateModel();
//                model.createTable(vin);
                int count = resultSet.getInt("count");
                String tableNameString = "z_data_" + vin.toLowerCase();
                System.out.println(vin + " " + count + " " + tableNameString);
                Statement exitsStatement = con.createStatement();
                String exitSql = "select * from INFORMATION_SCHEMA.tables where table_name = '" + tableNameString + "'";
                exitsStatement.execute(exitSql);
                if(!exitsStatement.getResultSet().next())
                {
                    //create 
                	System.out.println("创建了表"+tableNameString);
                    Statement createStatement = con.createStatement();
                    String sqlString = "CREATE TABLE " + tableNameString + " select * from data WHERE 1=2";
                    createStatement.execute(sqlString);
                    createStatement.close();
                } 
                else {
                    //drop
                    Statement dropStatement = con.createStatement();
                    String sqlString = "drop TABLE " + tableNameString;
                    dropStatement.execute(sqlString);
                    dropStatement.close();
                    
                    //create
                    Statement createStatement = con.createStatement();
                    String createString = "CREATE TABLE " + tableNameString + " select * from data WHERE 1=2";
                    createStatement.execute(createString);
                    createStatement.close();
                }
                
                exitsStatement.close();

                Statement updateStatement = con.createStatement();
                String updateSql = "INSERT INTO " + tableNameString +" SELECT * FROM data WHERE data.vin='"+vin+"' ";
                updateStatement.execute(updateSql);
                updateStatement.close();
            } catch (Exception e) {
                e.printStackTrace();
            }
           
        }
        
        //删除data表里的所有数据
//        Statement createStatement = con.createStatement();
//        String sqlString = "DELETE FROM data WHERE 1 = 1";
//        createStatement.execute(sqlString);
//        createStatement.close();
      
        
        
        stmt.close();
        con.close();
    }
    public static long getUnsignedInt(int x) {
        return x & 0x00000000ffffffffL;
    }
}
