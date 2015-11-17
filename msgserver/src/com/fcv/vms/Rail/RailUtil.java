package com.fcv.vms.Rail;

import com.fcv.vms.dao.RectangularArea;
import com.fcv.vms.dao.RoundArea;

public class RailUtil {
	
	public static float[][] praseToLatLng(byte[] latlngArray)
	{
		float[][] latlngs=new float[latlngArray.length/8][2];
		
		
		int p=0;
		int j=0;
		int k=0;
		while(p<latlngArray.length)
		{
			int latlng = latlngArray[p++];
			latlng = latlng << 8 | (latlngArray[p++] & 0xff);
			latlng = latlng << 8 | (latlngArray[p++] & 0xff);
			latlng = latlng << 8 | (latlngArray[p++] & 0xff);
			
			
			latlngs[j][k]=(float) (latlng/1000000.0);
			
			if(k==1)
			{
				k=0;
				j++;
			}
			else
			{
				k=1;
			}
		}
			
		return latlngs;	
	}
	
	public static float[][] praseToLatLng(String str)
	{
		String[] strs=str.split(",");
		float[][] latlngs=null;
		if(strs.length>1)
		{
			/**
			 * 如果字符串为奇数，则截断最后一个
			 */
			int i=strs.length/2;
			latlngs=new float[i][2];
			
			int z=0;
			for(int j=0;j<i;j++)
			{
				z=j*2;
				latlngs[j][0]=Float.valueOf(strs[z]);
				latlngs[j][1]=Float.valueOf(strs[z+1]);
			}
			
		}		
		return latlngs;
	}
	
	/**
	 * 判断是否目标经纬度点在围栏内
	 * 
	 * 围栏点已经排除了重复点和3个共线点的可能
	 * 
	 * 
	 * 基本原理：
	 * 		1.由目标点（下称p）向任意方向做射线（我们向坐标轴Y方向向上做射线），射线为无线延伸，所以一定可以找到一个点在多边形外，相交1次即为出一次或进一次多边形，默认0次为在多边形外
	 * 		  由于射线最后都在多边形外，反推则如果奇数次相交则p一定在多边形内（需要证明与多边形一边相交一次为进出一次多边形）
	 * 		2.引出的射线与多边形每条边（一条线段）判断是否相交
	 * 		3.如果交点数为奇数，则p在多边形内，否则在外
	 * 特殊情况:
	 * 		1.共线，射线和边重合，需要判断该边两端点前后的两点是否p的两边，如果在则为一个有效交点（用开区间比较是否在两边，前提是没有连续3个点以上共线，否则出错，这里默认没有3个点以上共线）
	 * 		2.射线与多边形的点相交，判断相交的端点前后两个点是否在p的两边（开区间比较）
	 * 
	 * @param latlngs 二维数组，0列为纬度（lat），1列为经度（lng）
	 * @param p 需要判断的目标
	 * @param isClose 是否闭合，如果闭合，则在线段上的点也属于在围栏内
	 * @return
	 */
	public static boolean isInPolygonRail(float[][] latlngs,float lat,float lng,boolean isClose)
	{
		int count=0;
		int i=0;
		for(;i<latlngs.length;i++)
		{
			float[] p1=latlngs[i];
			float[] p2=nextLatLng(latlngs,i);
			
			//前一个点
			float[] pp=null;
			//后一个点
			float[] pn=null;
			
			
			if(p1[1]==p2[1])
			{
				/**
				 * 共线情况
				 * 
				 */
				
				if(lng==p1[1])
				{
					if(isBetween(p1[0],p2[0],lat,true))
					{
						/**
						 * 在线段上，直接根据是否闭区间返回值
						 */
						return isClose;				
					}
					else
					{
						/**
						 * 不在线段上
						 * 开区间比较是否为中间值
						 */
						pn=nextLatLng(latlngs,i+1);
						pp=previousLatLng(latlngs,i);
						
						if(isBetween(pp[1],pn[1],lng,false))
						{
							count++;
						}
					}
				}
				
			}
			else
			{
				if(p1[1]==lng)
				{
					/**
					 * 如果与点p1相交，判断前后两点是否开区间在p两边
					 * 
					 * 如果p1与p重合，根据开闭区间返回
					 * 
					 */
					if(lat<p1[0])
					{
						pp=previousLatLng(latlngs,i);
						if(isBetween(pp[1],p2[1],lng,false))
						{
							count++;
						}
					}
					else if(lat==p1[0])
					{
						return isClose;
					}
				}			
				else if(p2[1]==lng)
				{
					/**
					 * 同上
					 */
					if(lat<p2[0])
					{
						pn=previousLatLng(latlngs,i+1);
						if(isBetween(pn[1],p1[1],lng,false))
						{
							count++;
						}
					}
					else if(lat==p2[0])
					{
						return isClose;
					}
				}
				else if(isBetween(p1[1],p2[1],lng,true))
				{
					/**
					 * 如果相交，则必须满足p1,p2在p的两侧
					 * 
					 * cross为相交点lng值，即Y轴值
					 * 
					 * 
					 */
					float cross=(lng-p1[1])*(p2[0]-p1[0])/(p2[1]-p1[1])+p1[0];
					
					if(cross>lat)
					{
						count++;
					}
					else if(cross==lat)
					{
						return isClose;
					}
				}
				
			}
			
		}
		
		
		return !(count%2==0);
		
		
	}
	/**
	 * 
	 * 获取数组的i位置的下一个对象，如果数组到尾了，则从开头继续
	 * @param array
	 * @param i
	 * @return
	 */
	private static float[] nextLatLng(float[][] array,int i)
	{
		return array[++i%array.length];	
	}
	/**
	 * 获取数组i位置的前一个对象，如果数组到了头，则逆向到尾部返回
	 * @param array
	 * @param i
	 * @return
	 */
	private static float[] previousLatLng(float[][] array,int i)
	{
		i=(i-1)%array.length;
		if(i<0)
		{
			i+=array.length;
		}
		return array[i];	
	}
	/**
	 * 判断x是否在x1和x2之间
	 * @param x1 
	 * @param x2
	 * @param x
	 * @param isClose 是否为闭区间，如果为闭区间则与两端点相等也属于在之间的值，返回true
	 * @return
	 */
	private static boolean isBetween(float x1,float x2,float x,boolean isClose)
	{
		if(x1>x2)
		{
			if(x>x2&&x<x1)
			{
				return true;
			}
			else if(x==x1||x==x2)
			{
				return isClose;	
			}
		}
		else if(x2>x1)
		{
			if(x>x1&&x<x2)
			{
				return true;
			}
			else if(x==x1||x==x2)
			{
				return isClose;	
			}
		}
		else
		{
			if(x==x1)
			{
				return isClose;
			}
		}
		
		return false;
	}
	
	/**
	 * 计算两点距离时经考虑在中国地区，即不考虑东西经和南北纬问题，
	 * 算法：
	 * 经度相同算距离:用纬度差乘以111千米/纬度相同算距离:用经度差乘以111千米乘以cosa(a为相同的纬度)
	 * @param area
	 * @param lat
	 * @param lng
	 * @return
	 */
	public static boolean isInRoundRail(RoundArea area,float lat,float lng)
	{
		float centerLat=(float) (area.getCenterLatitude()/1000000.0);
		float centerLng=(float) (area.getCenterLongitude()/1000000.0);
		
		float x=(centerLat-lat)*111;
		float y=(float) ((centerLng-lng)*111*Math.cos(lat*Math.PI/180));
		float d=(float) Math.sqrt(x*x+y*y)*1000;
						
		return d>area.getRadius()?false:true;
	}
	
	/**
	 * 不考虑东西经和南北纬
	 * @param area
	 * @param lat
	 * @param lng
	 * @return
	 */
	public static boolean isInRectangularRail(RectangularArea area,float lat,float lng)
	{
		int ilat=(int) (lat*1000000);
		int ilng=(int) (lng*1000000);
		
		int lat1=area.getTopLeftLatitude();
		int lat2=area.getBottomRightLatitude();
		
		int lng1=area.getTopLeftLongitude();
		int lng2=area.getBottomRightLongitude();
		
		if(((ilat<lat1&&ilat>lat2)||(ilat<lat2&&ilat>lat1))&&((ilng>lng1&&ilng<lng2)||(ilng>lng2&&ilng>lng1)))
			return true;
		
		return false;
	}
			
}
