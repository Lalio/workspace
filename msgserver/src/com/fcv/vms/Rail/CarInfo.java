package com.fcv.vms.Rail;

public class CarInfo {
	
	public static int CAR_STATUS_IN_RAIL=2;
	public static int CAR_STATUS_OUT_RAIL=1;
	public static int CAR_STATUS_NO_RAIL=0;
	/**
	 * ״̬
	 * 	1����ʾ��Χ��
	 * 	0����ʾ����Χ��
	 */
	public int status=-1;
	/**
	 * �ϴθ���ʱ��
	 */
	public long time=0;
	/**
	 * �ó���Χ��ID����
	 * 
	 */
	public int[] ids=null;
	
	
	public int[] roundIds=null;
	public int[] rectangularIds=null;
}
