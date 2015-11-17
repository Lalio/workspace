package com.fcv.vms.dao;

import java.util.Date;
import java.util.Map;

public interface StatusDao {

	int getMarkValueByVin(String vin);

	Map<String, Date> getMap();

}
