package com.fcv.vms.pojo;

import java.io.Serializable;
import java.util.Date;

public class AveData implements Serializable {

    /**
	 * 
	 */
    private static final long serialVersionUID = -3888374562745848124L;

    private int id;
    private String vin;
    private Date startTime;
    private Date lastTime;

    private Date onlineStime;
    private Date onlineEtime = null;
    private long onlineTime;
    


    private int isOver;
    
    
    private float batteryA;//电池电流
    private float totalBatteryA;
    private int batteryANum;
    private float batteryA_all;//电池总电流
    private float totalBatteryA_all;
    private int batteryA_allNum;
    private float batteryV;//电池电压
    private float totalBatteryV;
    private int batteryVNum;
    private float batteryV_all;//电池总电压
    private float totalBatteryV_all;
    private int batteryV_allNum;
    private float batteryTem;//电池温度
    private float totalBatteryTem;
    private int batteryTemNum;
    private float power;//充放电能量
    private float totalPower;
    private int powerNum;
    
    private float motorConV; // 电机控制器电压
    private float totalMotorConV;
    private int motorConVNum;
    private float motorConApos; // 电机控制器正电流
    private float totalMotorConApos;
    private int motorConAposNum;
    private float motorConAneg; // 电机控制器负电流
    private float totalMotorConAneg;
    private int motorConAnegNum;
    private float motorConTem;//电机控制器温度
    private float totalMotorConTem;
    private int motorConTemNum;
    private float motorV;//电机电压
    private float totalMotorV;
    private int motorVNum;
    private float motorTem;//电机温度
    private float totalMotorTem;
    private int motorTemNum;
    private float motorRot;//电机转速
    private float totalMotorRot;
    private int motorRotNum;
    private float motorTor;//电机转矩
    private float totalMotorTor;
    private int motorTorNum;
    

    private float generatorConApos; // 发电机正电流
    private float totalGeneratorConApos; 
    private int generatorConAposNum;
    private float generatorConAneg; // 发电机负电流
    private float totalGeneratorConAneg; 
    private int generatorConAnegNum;

    
    private float DCDC_V;//DCDC电压
    private float totalDCDC_V;
    private int DCDC_VNum;
    private float DCDC_A;//DCDC电流
    private float totalDCDC_A;
    private int DCDC_ANum;
    private float DCDC_Tem;//DCDC温度
    private float totalDCDC_Tem;
    private int DCDC_TemNum;
    

    private float accel; // 加速踏板
    private float totalAccel; 
    private int accelNum;
    private float brake; // 制动踏板
    private float totalBrake;
    private int brakeNum;
    private float tow;//牵引踏板
    private float totalTow;
    private int towNum;
    private float flue; // 油耗
    private float totalFlue;
    private int flueNum;
    private float engineThrottle; // 发动机实际油门
    private float totalEngineThrottle;
    private int engineThrottleNum;
    private float startSOC = 0f; // 初始soc
    private float endSOC; // 最后soc
    private float totalSOC;
    private float SOC;//平均SOC

    
    private float speed;//每个统计区间的平均速度
    private float totalSpeed;
    private int speedNum;
    private float maxSpeed;//每个统计区间的最大速度
    private int isAttendance; // 是否出勤
    private int count1;
    private int count2;
    private int count3;
    private int count4;
    private int count5;
    private int count6;
    
    
    
	
	@Override
	public String toString() {
		return "AveData [id=" + id + ", vin=" + vin + ", startTime="
				+ startTime + ", lastTime=" + lastTime + ", onlineStime="
				+ onlineStime + ", onlineEtime=" + onlineEtime
				+ ", onlineTime=" + onlineTime + ", isOver=" + isOver
				+ ", batteryA=" + batteryA + ", totalBatteryA=" + totalBatteryA
				+ ", batteryANum=" + batteryANum + ", batteryA_all="
				+ batteryA_all + ", totalBatteryA_all=" + totalBatteryA_all
				+ ", batteryA_allNum=" + batteryA_allNum + ", batteryV="
				+ batteryV + ", totalBatteryV=" + totalBatteryV
				+ ", batteryVNum=" + batteryVNum + ", batteryV_all="
				+ batteryV_all + ", totalBatteryV_all=" + totalBatteryV_all
				+ ", batteryV_allNum=" + batteryV_allNum + ", batteryTem="
				+ batteryTem + ", totalBatteryTem=" + totalBatteryTem
				+ ", batteryTemNum=" + batteryTemNum + ", power=" + power
				+ ", totalPower=" + totalPower + ", powerNum=" + powerNum
				+ ", motorConV=" + motorConV + ", totalMotorConV="
				+ totalMotorConV + ", motorConVNum=" + motorConVNum
				+ ", motorConApos=" + motorConApos + ", totalMotorConApos="
				+ totalMotorConApos + ", motorConAposNum=" + motorConAposNum
				+ ", motorConAneg=" + motorConAneg + ", totalMotorConAneg="
				+ totalMotorConAneg + ", motorConAnegNum=" + motorConAnegNum
				+ ", motorConTem=" + motorConTem + ", totalMotorConTem="
				+ totalMotorConTem + ", motorConTemNum=" + motorConTemNum
				+ ", motorV=" + motorV + ", totalMotorV=" + totalMotorV
				+ ", motorVNum=" + motorVNum + ", motorTem=" + motorTem
				+ ", totalMotorTem=" + totalMotorTem + ", motorTemNum="
				+ motorTemNum + ", motorRot=" + motorRot + ", totalMotorRot="
				+ totalMotorRot + ", motorRotNum=" + motorRotNum
				+ ", motorTor=" + motorTor + ", totalMotorTor=" + totalMotorTor
				+ ", motorTorNum=" + motorTorNum + ", generatorConApos="
				+ generatorConApos + ", totalGeneratorConApos="
				+ totalGeneratorConApos + ", generatorConAposNum="
				+ generatorConAposNum + ", generatorConAneg="
				+ generatorConAneg + ", totalGeneratorConAneg="
				+ totalGeneratorConAneg + ", generatorConAnegNum="
				+ generatorConAnegNum + ", DCDC_V=" + DCDC_V + ", totalDCDC_V="
				+ totalDCDC_V + ", DCDC_VNum=" + DCDC_VNum + ", DCDC_A="
				+ DCDC_A + ", totalDCDC_A=" + totalDCDC_A + ", DCDC_ANum="
				+ DCDC_ANum + ", DCDC_Tem=" + DCDC_Tem + ", totalDCDC_Tem="
				+ totalDCDC_Tem + ", DCDC_TemNum=" + DCDC_TemNum + ", accel="
				+ accel + ", totalAccel=" + totalAccel + ", accelNum="
				+ accelNum + ", brake=" + brake + ", totalBrake=" + totalBrake
				+ ", brakeNum=" + brakeNum + ", tow=" + tow + ", totalTow="
				+ totalTow + ", towNum=" + towNum + ", flue=" + flue
				+ ", totalFlue=" + totalFlue + ", flueNum=" + flueNum
				+ ", engineThrottle=" + engineThrottle
				+ ", totalEngineThrottle=" + totalEngineThrottle
				+ ", engineThrottleNum=" + engineThrottleNum + ", startSOC="
				+ startSOC + ", endSOC=" + endSOC + ", totalSOC=" + totalSOC
				+ ", SOC=" + SOC + ", speed=" + speed + ", totalSpeed="
				+ totalSpeed + ", speedNum=" + speedNum + ", maxSpeed="
				+ maxSpeed + ", isAttendance=" + isAttendance + ", count1="
				+ count1 + ", count2=" + count2 + ", count3=" + count3
				+ ", count4=" + count4 + ", count5=" + count5 + ", count6="
				+ count6 + "]";
	}

	public float getTotalSOC() {
		return totalSOC;
	}

	public void setTotalSOC(float totalSOC) {
		this.totalSOC = totalSOC;
	}

	public float getSOC() {
		return SOC;
	}

	public void setSOC(float sOC) {
		SOC = sOC;
	}

	public float getPower() {
		return power;
	}

	public void setPower(float power) {
		this.power = power;
	}

	public float getTotalPower() {
		return totalPower;
	}

	public void setTotalPower(float totalPower) {
		this.totalPower = totalPower;
	}

	public int getPowerNum() {
		return powerNum;
	}

	public void setPowerNum(int powerNum) {
		this.powerNum = powerNum;
	}

	public int getId() {
		return id;
	}
	public void setId(int id) {
		this.id = id;
	}
	public String getVin() {
		return vin;
	}
	public void setVin(String vin) {
		this.vin = vin;
	}
	public Date getStartTime() {
		return startTime;
	}
	public void setStartTime(Date startTime) {
		this.startTime = startTime;
	}
	public Date getLastTime() {
		return lastTime;
	}
	public void setLastTime(Date lastTime) {
		this.lastTime = lastTime;
	}
	public Date getOnlineStime() {
		return onlineStime;
	}
	public void setOnlineStime(Date onlineStime) {
		this.onlineStime = onlineStime;
	}
	public Date getOnlineEtime() {
		return onlineEtime;
	}
	public void setOnlineEtime(Date onlineEtime) {
		this.onlineEtime = onlineEtime;
	}
	public long getOnlineTime() {
		return onlineTime;
	}
	public void setOnlineTime(long onlineTime) {
		this.onlineTime = onlineTime;
	}
	public int getIsOver() {
		return isOver;
	}
	public void setIsOver(int isOver) {
		this.isOver = isOver;
	}
	public float getBatteryA() {
		return batteryA;
	}
	public void setBatteryA(float batteryA) {
		this.batteryA = batteryA;
	}
	public float getTotalBatteryA() {
		return totalBatteryA;
	}
	public void setTotalBatteryA(float totalBatteryA) {
		this.totalBatteryA = totalBatteryA;
	}
	public int getBatteryANum() {
		return batteryANum;
	}
	public void setBatteryANum(int batteryANum) {
		this.batteryANum = batteryANum;
	}
	public float getBatteryA_all() {
		return batteryA_all;
	}
	public void setBatteryA_all(float batteryA_all) {
		this.batteryA_all = batteryA_all;
	}
	public float getTotalBatteryA_all() {
		return totalBatteryA_all;
	}
	public void setTotalBatteryA_all(float totalBatteryA_all) {
		this.totalBatteryA_all = totalBatteryA_all;
	}
	public int getBatteryA_allNum() {
		return batteryA_allNum;
	}
	public void setBatteryA_allNum(int batteryA_allNum) {
		this.batteryA_allNum = batteryA_allNum;
	}
	public float getBatteryV() {
		return batteryV;
	}
	public void setBatteryV(float batteryV) {
		this.batteryV = batteryV;
	}
	public float getTotalBatteryV() {
		return totalBatteryV;
	}
	public void setTotalBatteryV(float totalBatteryV) {
		this.totalBatteryV = totalBatteryV;
	}
	public int getBatteryVNum() {
		return batteryVNum;
	}
	public void setBatteryVNum(int batteryVNum) {
		this.batteryVNum = batteryVNum;
	}
	public float getBatteryV_all() {
		return batteryV_all;
	}
	public void setBatteryV_all(float batteryV_all) {
		this.batteryV_all = batteryV_all;
	}
	public float getTotalBatteryV_all() {
		return totalBatteryV_all;
	}
	public void setTotalBatteryV_all(float totalBatteryV_all) {
		this.totalBatteryV_all = totalBatteryV_all;
	}
	public int getBatteryV_allNum() {
		return batteryV_allNum;
	}
	public void setBatteryV_allNum(int batteryV_allNum) {
		this.batteryV_allNum = batteryV_allNum;
	}
	public float getBatteryTem() {
		return batteryTem;
	}
	public void setBatteryTem(float batteryTem) {
		this.batteryTem = batteryTem;
	}
	public float getTotalBatteryTem() {
		return totalBatteryTem;
	}
	public void setTotalBatteryTem(float totalBatteryTem) {
		this.totalBatteryTem = totalBatteryTem;
	}
	public int getBatteryTemNum() {
		return batteryTemNum;
	}
	public void setBatteryTemNum(int batteryTemNum) {
		this.batteryTemNum = batteryTemNum;
	}
	public float getMotorConV() {
		return motorConV;
	}
	public void setMotorConV(float motorConV) {
		this.motorConV = motorConV;
	}
	public float getTotalMotorConV() {
		return totalMotorConV;
	}
	public void setTotalMotorConV(float totalMotorConV) {
		this.totalMotorConV = totalMotorConV;
	}
	public int getMotorConVNum() {
		return motorConVNum;
	}
	public void setMotorConVNum(int motorConVNum) {
		this.motorConVNum = motorConVNum;
	}
	public float getMotorConApos() {
		return motorConApos;
	}
	public void setMotorConApos(float motorConApos) {
		this.motorConApos = motorConApos;
	}
	public float getTotalMotorConApos() {
		return totalMotorConApos;
	}
	public void setTotalMotorConApos(float totalMotorConApos) {
		this.totalMotorConApos = totalMotorConApos;
	}
	public int getMotorConAposNum() {
		return motorConAposNum;
	}
	public void setMotorConAposNum(int motorConAposNum) {
		this.motorConAposNum = motorConAposNum;
	}
	public float getMotorConAneg() {
		return motorConAneg;
	}
	public void setMotorConAneg(float motorConAneg) {
		this.motorConAneg = motorConAneg;
	}
	public float getTotalMotorConAneg() {
		return totalMotorConAneg;
	}
	public void setTotalMotorConAneg(float totalMotorConAneg) {
		this.totalMotorConAneg = totalMotorConAneg;
	}
	public int getMotorConAnegNum() {
		return motorConAnegNum;
	}
	public void setMotorConAnegNum(int motorConAnegNum) {
		this.motorConAnegNum = motorConAnegNum;
	}
	public float getMotorConTem() {
		return motorConTem;
	}
	public void setMotorConTem(float motorConTem) {
		this.motorConTem = motorConTem;
	}
	public float getTotalMotorConTem() {
		return totalMotorConTem;
	}
	public void setTotalMotorConTem(float totalMotorConTem) {
		this.totalMotorConTem = totalMotorConTem;
	}
	public int getMotorConTemNum() {
		return motorConTemNum;
	}
	public void setMotorConTemNum(int motorConTemNum) {
		this.motorConTemNum = motorConTemNum;
	}
	public float getMotorV() {
		return motorV;
	}
	public void setMotorV(float motorV) {
		this.motorV = motorV;
	}
	public float getTotalMotorV() {
		return totalMotorV;
	}
	public void setTotalMotorV(float totalMotorV) {
		this.totalMotorV = totalMotorV;
	}
	public int getMotorVNum() {
		return motorVNum;
	}
	public void setMotorVNum(int motorVNum) {
		this.motorVNum = motorVNum;
	}
	public float getMotorTem() {
		return motorTem;
	}
	public void setMotorTem(float motorTem) {
		this.motorTem = motorTem;
	}
	public float getTotalMotorTem() {
		return totalMotorTem;
	}
	public void setTotalMotorTem(float totalMotorTem) {
		this.totalMotorTem = totalMotorTem;
	}
	public int getMotorTemNum() {
		return motorTemNum;
	}
	public void setMotorTemNum(int motorTemNum) {
		this.motorTemNum = motorTemNum;
	}
	public float getMotorRot() {
		return motorRot;
	}
	public void setMotorRot(float motorRot) {
		this.motorRot = motorRot;
	}
	public float getTotalMotorRot() {
		return totalMotorRot;
	}
	public void setTotalMotorRot(float totalMotorRot) {
		this.totalMotorRot = totalMotorRot;
	}
	public int getMotorRotNum() {
		return motorRotNum;
	}
	public void setMotorRotNum(int motorRotNum) {
		this.motorRotNum = motorRotNum;
	}
	public float getMotorTor() {
		return motorTor;
	}
	public void setMotorTor(float motorTor) {
		this.motorTor = motorTor;
	}
	public float getTotalMotorTor() {
		return totalMotorTor;
	}
	public void setTotalMotorTor(float totalMotorTor) {
		this.totalMotorTor = totalMotorTor;
	}
	public int getMotorTorNum() {
		return motorTorNum;
	}
	public void setMotorTorNum(int motorTorNum) {
		this.motorTorNum = motorTorNum;
	}
	public float getGeneratorConApos() {
		return generatorConApos;
	}
	public void setGeneratorConApos(float generatorConApos) {
		this.generatorConApos = generatorConApos;
	}
	public float getTotalGeneratorConApos() {
		return totalGeneratorConApos;
	}
	public void setTotalGeneratorConApos(float totalGeneratorConApos) {
		this.totalGeneratorConApos = totalGeneratorConApos;
	}
	public int getGeneratorConAposNum() {
		return generatorConAposNum;
	}
	public void setGeneratorConAposNum(int generatorConAposNum) {
		this.generatorConAposNum = generatorConAposNum;
	}
	public float getGeneratorConAneg() {
		return generatorConAneg;
	}
	public void setGeneratorConAneg(float generatorConAneg) {
		this.generatorConAneg = generatorConAneg;
	}
	public float getTotalGeneratorConAneg() {
		return totalGeneratorConAneg;
	}
	public void setTotalGeneratorConAneg(float totalGeneratorConAneg) {
		this.totalGeneratorConAneg = totalGeneratorConAneg;
	}
	public int getGeneratorConAnegNum() {
		return generatorConAnegNum;
	}
	public void setGeneratorConAnegNum(int generatorConAnegNum) {
		this.generatorConAnegNum = generatorConAnegNum;
	}
	public float getDCDC_V() {
		return DCDC_V;
	}
	public void setDCDC_V(float dCDC_V) {
		DCDC_V = dCDC_V;
	}
	public float getTotalDCDC_V() {
		return totalDCDC_V;
	}
	public void setTotalDCDC_V(float totalDCDC_V) {
		this.totalDCDC_V = totalDCDC_V;
	}
	public int getDCDC_VNum() {
		return DCDC_VNum;
	}
	public void setDCDC_VNum(int dCDC_VNum) {
		DCDC_VNum = dCDC_VNum;
	}
	public float getDCDC_A() {
		return DCDC_A;
	}
	public void setDCDC_A(float dCDC_A) {
		DCDC_A = dCDC_A;
	}
	public float getTotalDCDC_A() {
		return totalDCDC_A;
	}
	public void setTotalDCDC_A(float totalDCDC_A) {
		this.totalDCDC_A = totalDCDC_A;
	}
	public int getDCDC_ANum() {
		return DCDC_ANum;
	}
	public void setDCDC_ANum(int dCDC_ANum) {
		DCDC_ANum = dCDC_ANum;
	}
	public float getDCDC_Tem() {
		return DCDC_Tem;
	}
	public void setDCDC_Tem(float dCDC_Tem) {
		DCDC_Tem = dCDC_Tem;
	}
	public float getTotalDCDC_Tem() {
		return totalDCDC_Tem;
	}
	public void setTotalDCDC_Tem(float totalDCDC_Tem) {
		this.totalDCDC_Tem = totalDCDC_Tem;
	}
	public int getDCDC_TemNum() {
		return DCDC_TemNum;
	}
	public void setDCDC_TemNum(int dCDC_TemNum) {
		DCDC_TemNum = dCDC_TemNum;
	}
	public float getAccel() {
		return accel;
	}
	public void setAccel(float accel) {
		this.accel = accel;
	}
	public float getTotalAccel() {
		return totalAccel;
	}
	public void setTotalAccel(float totalAccel) {
		this.totalAccel = totalAccel;
	}
	public int getAccelNum() {
		return accelNum;
	}
	public void setAccelNum(int accelNum) {
		this.accelNum = accelNum;
	}
	public float getBrake() {
		return brake;
	}
	public void setBrake(float brake) {
		this.brake = brake;
	}
	public float getTotalBrake() {
		return totalBrake;
	}
	public void setTotalBrake(float totalBrake) {
		this.totalBrake = totalBrake;
	}
	public int getBrakeNum() {
		return brakeNum;
	}
	public void setBrakeNum(int brakeNum) {
		this.brakeNum = brakeNum;
	}
	public float getTow() {
		return tow;
	}
	public void setTow(float tow) {
		this.tow = tow;
	}
	public float getTotalTow() {
		return totalTow;
	}
	public void setTotalTow(float totalTow) {
		this.totalTow = totalTow;
	}
	public int getTowNum() {
		return towNum;
	}
	public void setTowNum(int towNum) {
		this.towNum = towNum;
	}
	public float getFlue() {
		return flue;
	}
	public void setFlue(float flue) {
		this.flue = flue;
	}
	public float getTotalFlue() {
		return totalFlue;
	}
	public void setTotalFlue(float totalFlue) {
		this.totalFlue = totalFlue;
	}
	public int getFlueNum() {
		return flueNum;
	}
	public void setFlueNum(int flueNum) {
		this.flueNum = flueNum;
	}
	public float getEngineThrottle() {
		return engineThrottle;
	}
	public void setEngineThrottle(float engineThrottle) {
		this.engineThrottle = engineThrottle;
	}
	public float getTotalEngineThrottle() {
		return totalEngineThrottle;
	}
	public void setTotalEngineThrottle(float totalEngineThrottle) {
		this.totalEngineThrottle = totalEngineThrottle;
	}
	public int getEngineThrottleNum() {
		return engineThrottleNum;
	}
	public void setEngineThrottleNum(int engineThrottleNum) {
		this.engineThrottleNum = engineThrottleNum;
	}
	public float getStartSOC() {
		return startSOC;
	}
	public void setStartSOC(float startSOC) {
		this.startSOC = startSOC;
	}
	public float getEndSOC() {
		return endSOC;
	}
	public void setEndSOC(float endSOC) {
		this.endSOC = endSOC;
	}
	public float getSpeed() {
		return speed;
	}
	public void setSpeed(float speed) {
		this.speed = speed;
	}
	public float getTotalSpeed() {
		return totalSpeed;
	}
	public void setTotalSpeed(float totalSpeed) {
		this.totalSpeed = totalSpeed;
	}
	public int getSpeedNum() {
		return speedNum;
	}
	public void setSpeedNum(int speedNum) {
		this.speedNum = speedNum;
	}
	public float getMaxSpeed() {
		return maxSpeed;
	}
	public void setMaxSpeed(float maxSpeed) {
		this.maxSpeed = maxSpeed;
	}
	public int getIsAttendance() {
		return isAttendance;
	}
	public void setIsAttendance(int isAttendance) {
		this.isAttendance = isAttendance;
	}
	public int getCount1() {
		return count1;
	}
	public void setCount1(int count1) {
		this.count1 = count1;
	}
	public int getCount2() {
		return count2;
	}
	public void setCount2(int count2) {
		this.count2 = count2;
	}
	public int getCount3() {
		return count3;
	}
	public void setCount3(int count3) {
		this.count3 = count3;
	}
	public int getCount4() {
		return count4;
	}
	public void setCount4(int count4) {
		this.count4 = count4;
	}
	public int getCount5() {
		return count5;
	}
	public void setCount5(int count5) {
		this.count5 = count5;
	}
	public int getCount6() {
		return count6;
	}
	public void setCount6(int count6) {
		this.count6 = count6;
	}
    
    
    
    
}
