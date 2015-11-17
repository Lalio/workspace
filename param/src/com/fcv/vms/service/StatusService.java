package com.fcv.vms.service;

import java.util.Date;
import java.util.Map;

public interface StatusService {
	int getMVByVin(String vin);
	Map<String , Date> getMap();
}
