/*
 * Created on 2004-6-13
 *
 * TODO To change the template for this generated file go to
 * Window - Preferences - Java - Code Style - Code Templates
 */
package com.fcv.vms.model;

import java.sql.Statement;
import java.sql.Timestamp;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;

import org.hibernate.HibernateException;
import org.hibernate.Query;
import org.hibernate.Session;
import org.hibernate.Transaction;
import org.hibernate.transform.Transformers;

import com.fcv.vms.Rail.RailManager3;
import com.fcv.vms.server.comserver;

public class HibernateModel {

    /**
     * call storedprodurce
     */
    public int OnSavedata(String vin, byte[] DB_datas, int sid) throws Exception {
        int datastail = DB_datas.length - 21;
        int speed;
        int sn = DB_datas[0];
        if (sn < 0)
            sn = sn + 256;

        if (vin.substring(0, 5).equals("NC201")) {
            int d = 0;
            int h = 0;
            if ((int) DB_datas[19 + 6] < 0)
                d = (int) DB_datas[19 + 6] + 256;
            else
                d = (int) DB_datas[19 + 6];
            if ((int) DB_datas[19 + 7] < 0)
                h = (int) DB_datas[19 + 7] + 256;
            else
                h = (int) DB_datas[19 + 7];
            speed = (int) ((d + h * 256) * 0.5 * 0.03);
        } else {
            if (DB_datas[31] < 0)// ///////////////////////
                speed = (int) DB_datas[31] + 256;// ////////////////////
            else
                // ///////////////////////////
                speed = DB_datas[31];// ////////////////
        }
        int locateisvalid = 0;
        Date locatetime;
        float locatelongitude = 0;
        float locatelatitude = 0;
        float locatespeed = 0;
        float locatedirection = 0;
        int istrouble = 0;
        byte[] datas = new byte[DB_datas.length - 42];
        byte[] troubles = new byte[12];

        Session sess = null;
        try {
            // 车辆参数
            for (int q = 0; q < DB_datas.length - 42; q++)
                datas[q] = DB_datas[19 + q];
            // GPS时间

            if (DB_datas[datastail + 5] > 0 && DB_datas[datastail + 5] < 100 && DB_datas[datastail + 4] > 0
                    && DB_datas[datastail + 4] < 13 && DB_datas[datastail + 3] > 0 && DB_datas[datastail + 3] < 32
                    && DB_datas[datastail + 2] < 60 && DB_datas[datastail + 1] < 60 && DB_datas[datastail] < 25) {
                Calendar calendar = Calendar.getInstance();
                calendar.set(DB_datas[datastail + 5] + 2000, DB_datas[datastail + 4] - 1, DB_datas[datastail + 3],
                        DB_datas[datastail], DB_datas[datastail + 1], DB_datas[datastail + 2]);
                locatetime = calendar.getTime();
                // locatetime=new
                // Date(DB_datas[111]+2000,DB_datas[110],DB_datas[109],DB_datas[106],DB_datas[107],DB_datas[108]);
            } else
                locatetime = new Date();
            // 判断GPS定位是否有效（定位状态A为有效）
            if ((char) DB_datas[datastail + 6] == 'A' || (char) DB_datas[datastail + 6] == 'a') {
                if ((char) DB_datas[datastail + 6] == 'A' || (char) DB_datas[datastail + 6] == 'a')
                    locateisvalid = 1;
                else
                    locateisvalid = 0;
                // System.out.println("isvilid:"+locateisvalid);
                // GPS经度和纬度的计算
                if ((char) DB_datas[datastail + 11] == 'S')
                    locatelatitude = 0
                            - (float) DB_datas[datastail + 7]
                            * 60
                            - ((float) DB_datas[datastail + 8] + (float) DB_datas[datastail + 9] / 100 + (float) DB_datas[datastail + 10] / 10000);
                else
                    locatelatitude = (float) DB_datas[datastail + 7]
                            * 60
                            + ((float) DB_datas[datastail + 8] + (float) DB_datas[datastail + 9] / 100 + (float) DB_datas[datastail + 10] / 10000);
                if ((char) DB_datas[datastail + 16] == 'W')
                    locatelongitude = 0
                            - (float) DB_datas[datastail + 12]
                            * 60
                            - ((float) DB_datas[datastail + 13] + (float) DB_datas[datastail + 14] / 100 + (float) DB_datas[datastail + 15] / 10000);
                else
                    locatelongitude = (float) DB_datas[datastail + 12]
                            * 60
                            + ((float) DB_datas[datastail + 13] + (float) DB_datas[datastail + 14] / 100 + (float) DB_datas[datastail + 15] / 10000);
                // GPS车辆速度
                locatespeed = (float) ((float) DB_datas[datastail + 17] * 1.852);
                // GPS车辆方向
                int bytesSite = DB_datas[datastail + 19];
                if (bytesSite < 0)
                    bytesSite = bytesSite + 256;
                locatedirection = (float) DB_datas[datastail + 18] * 256 + (float) bytesSite;

            } else {
                if ((char) DB_datas[datastail + 7] == 'A' || (char) DB_datas[datastail + 7] == 'a')
                    locateisvalid = 1;
                else
                    locateisvalid = 0;
                // GPS经度和纬度的计算
                if ((char) DB_datas[datastail + 12] == 'S')
                    locatelatitude = 0
                            - (float) DB_datas[datastail + 8]
                            * 60
                            - ((float) DB_datas[datastail + 9] + (float) DB_datas[datastail + 10] / 100 + (float) DB_datas[datastail + 11] / 10000);
                else
                    locatelatitude = (float) DB_datas[datastail + 8]
                            * 60
                            + ((float) DB_datas[datastail + 9] + (float) DB_datas[datastail + 10] / 100 + (float) DB_datas[datastail + 11] / 10000);
                if ((char) DB_datas[datastail + 7] == 'W')
                    locatelongitude = 0
                            - (float) DB_datas[datastail + 13]
                            * 60
                            - ((float) DB_datas[datastail + 14] + (float) DB_datas[datastail + 15] / 100 + (float) DB_datas[datastail + 16] / 10000);
                else
                    locatelongitude = (float) DB_datas[datastail + 13]
                            * 60
                            + ((float) DB_datas[datastail + 14] + (float) DB_datas[datastail + 15] / 100 + (float) DB_datas[datastail + 16] / 10000);
                // GPS车辆速度
                locatespeed = (float) ((float) DB_datas[datastail + 18] * 1.852);
                // GPS车辆方向
                int bytesSite = DB_datas[datastail + 20];
                if (bytesSite < 0)
                    bytesSite = bytesSite + 256;
                locatedirection = (float) DB_datas[datastail + 19] * 256 + (float) bytesSite;
                // System.out.println(DB_datas[datastail+19]+":1920:"+bytesSite+";locatedirection:"+locatedirection);
            }
            // 车辆故障
            for (int p = 0; p < 12; p++)
                troubles[p] = 0;

            int railStatus = RailManager3.updateCarPosition(vin, locatelatitude / 60, locatelongitude / 60);
            double distance = speed * 0.0005556;
            if (distance < 0)
                distance = 0;

            double gpsdistance = locatespeed * 0.0005556;
            if (gpsdistance < 0)
                gpsdistance = 0;

            sess = HibernateSessionFactory.getSession();
            Transaction tr = sess.beginTransaction();

            Timestamp now_time= new Timestamp(System.currentTimeMillis());
            
            Query query = null;
            
            query = sess.createSQLQuery("call n_updateinfo2(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");//9是不用插入data，2是time是传入的
            query.setInteger(0, sn);
            query.setString(1, vin);
            query.setInteger(2, datas[19]);
            query.setInteger(3, istrouble);
            query.setBinary(4, datas);
            query.setBinary(5, troubles);
            query.setInteger(6, locateisvalid);
            query.setTimestamp(7, locatetime);
            query.setFloat(8, locatelongitude);
            query.setFloat(9, locatelatitude);
            query.setFloat(10, locatespeed);
            query.setFloat(11, locatedirection);
            query.setInteger(12, sid);
            query.setInteger(13, railStatus);
            query.setDouble(14, distance);
            query.setDouble(15, gpsdistance);
            query.setTimestamp(16, now_time);
            query.executeUpdate();
            
//            int max_id = comserver.reg_id.get(vin.toLowerCase());
//            comserver.reg_id.put(vin.toLowerCase(), ++max_id);
            
            //   String tableNameString = "z_data_" + getUnsignedInt(vin.hashCode());
            String tableNameString = "z_data_" + vin.toLowerCase();
            SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
            String sql = "INSERT "
                    + tableNameString
                    + " (SN,VIN,Time,Datas,LocateIsValid,LocateTime,LocateLongitude,LocateLatitude,LocateSpeed,LocateDirection) "
                    + " VALUES(" + sn + ",'" + vin + "','" + now_time + "',?,"
                    + locateisvalid + ",'" + sdf.format(locatetime) + "',?,?,?,?)";

            query = sess.createSQLQuery(sql);
//            query.setInteger(0, max_id);
            query.setBinary(0, datas);
            query.setFloat(1, locatelongitude);
            query.setFloat(2, locatelatitude);
            query.setFloat(3, locatespeed);
            query.setFloat(4, locatedirection);
            query.executeUpdate();

            tr.commit();

        } catch (HibernateException e) {
            throw new Exception(e);
        } finally {
            if (sess != null)
                try {
                    sess.close();
                } catch (HibernateException e1) {
                    throw new Exception(e1);
                }
        }
        return 0;
    }

    public int OnProcessReg(String vin, int model, int istrouble) throws Exception {
        Session sess = null;
        int sid = 0;
        try {
            sess = HibernateSessionFactory.getSession();
            Query query = sess.createSQLQuery("call n_processinfo(?,?,?)");
            query.setString(0, vin);
            query.setInteger(1, model);
            query.setInteger(2, istrouble);
            Transaction tr = sess.beginTransaction();
            query.executeUpdate();
            tr.commit();

        } catch (HibernateException e) {
            throw new Exception(e);
        } finally {
            if (sess != null)
                try {
                    sess.close();
                } catch (HibernateException e1) {
                    throw new Exception(e1);
                }
        }
        return sid;
    }

    public static long getUnsignedInt(int x) {
        return x & 0x00000000ffffffffL;
    }

    /**
     * 创建车辆表名称的缓存，vin。hashcode之后装在hashmap里作为key
     * 穿甲车辆和id的缓存
     */
    public static void createMap() {
        String hql = "SELECT new map(r.vin as vin,r.has_data_table as table ) FROM RegMap r";
        Session sess;
        sess = HibernateSessionFactory.getSession();
        List list = sess.createQuery(hql).list();
        Iterator<HashMap<String, Object>> i = list.iterator();
        while (i.hasNext()) {
            HashMap<String, Object> map = i.next();
            comserver.reg_table.put(map.get("vin").toString().toLowerCase(), (Integer) map.get("table"));
        }
        
        
        
//        String sql = "select vin,max(id) as max_id from data group by vin";
//        Query query = sess.createSQLQuery(sql);
//        List<HashMap<String, Object>> list2 =  query.setResultTransformer(Transformers.ALIAS_TO_ENTITY_MAP).list();
//        
//        for (Iterator<HashMap<String, Object>> iterator = list2.iterator(); iterator.hasNext();) {
//        	HashMap<String, Object> map2 = iterator.next();
//        	comserver.reg_id.put(map2.get("VIN").toString().toLowerCase(), (Integer) map2.get("max_id"));
//			
//		}
        return;
        
    }

    /**
     * 查看该车在数据库中有没有表，没有则创建
     * 
     * @param vin
     */
    public void processZDataAndReg(String vin, int markValue) {
        try {
            Integer has_table = comserver.reg_table.get(vin.toLowerCase());
            if (has_table == null || has_table == 0) {
            	System.out.println(" create table "+ vin);
                OnProcessReg(vin, markValue, 0);

                Session sess = HibernateSessionFactory.getSession();
              //  String tableNameString = "z_data_" + getUnsignedInt(vin.hashCode());
                String tableNameString = "z_data_" + vin.toLowerCase();
                String exitSql = "select * from INFORMATION_SCHEMA.tables where table_name = '" + tableNameString + "'";
                Query query2 = sess.createSQLQuery(exitSql);
                boolean isExitTable = !(query2.list().size() == 0);

                Transaction tr = sess.beginTransaction();

                Query query = null;
                if (!isExitTable) {
                    // create table
                    query = sess.createSQLQuery("CREATE TABLE " + tableNameString + " select * from data WHERE 1=2");
                    query.executeUpdate();
                    
                    query = sess.createSQLQuery("ALTER TABLE "+tableNameString+" CHANGE ID ID int(11) primary key not null auto_increment ");
                    query.executeUpdate();
                    
                }

                query = sess.createSQLQuery("UPDATE reg SET has_data_table = 1 WHERE vin = '" + vin + "'");
                query.executeUpdate();
                query = sess.createSQLQuery("UPDATE reg SET vin_hash = '" + getUnsignedInt(vin.hashCode())
                        + "' WHERE vin = '" + vin + "'");
                query.executeUpdate();
                System.out.println("[createTable]:isExitTable=" + isExitTable + ",vin=" + vin + ",tableNameString="
                        + tableNameString);
                tr.commit();
                comserver.reg_table.put(vin.toLowerCase(), 1);

            }
        } catch (Exception e) {
            e.printStackTrace();
            System.out.println(" processZDataAndReg create table throws exception!");
        }

    }
}