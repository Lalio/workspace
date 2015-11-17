package com.fcv.vms.service;


import java.util.Date;
import java.util.List;

import com.fcv.vms.pojo.WarnStat;

public interface WarnStatService{

	void add(List<WarnStat> warnStat);
	
	List<WarnStat> getWarnStat(String vin,String stime,String etime);

	void update(WarnStat warnStat);

	boolean isHave(String vin, Date time,int dParamID);

	int getWarnNum(String vin, Date today2);
	
}
