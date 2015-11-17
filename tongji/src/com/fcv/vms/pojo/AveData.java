package com.fcv.vms.pojo;

import java.io.Serializable;
import java.text.SimpleDateFormat;
import java.util.Date;

public class AveData implements Serializable {

    /**
	 * 
	 */
    private static final long serialVersionUID = -3888374562745848124L;
    
    private int id;
    private String vin;
    private String regName;
    private Date startTime;
    private Date lastTime;

    private Date onlineStime;
    private Date onlineEtime = null;
    private int onlineTime;
    
    private int isOver;
    
    private int count1;
    private int count2;
    private int count3;
    private int count4;
    private int count5;
    private int count6;
    
    private int isAttendance; // 是否出勤

    private int speedNum;//每个统计区间的速度次数
    private float totalSpeed;//每个统计区间的速度总和
    private float maxSpeed;//每个统计区间的最大速度
    
    private float startSOC = 0f; // 初始soc
    private float endSOC; // 最后soc
    private float totalSOC;
    private int SOCNum;
    
    private float totalMotorConV;
    private int motorConVNum;// 电机控制器电压
    private float totalMotorConApos;
    private int motorConAposNum;// 电机控制器正电流
    private float totalMotorConAneg;
    private int motorConAnegNum;// 电机控制器负电流
    private float totalMotorConTem;
    private int motorConTemNum;//电机控制器温度
    private float totalMotorV;
    private int motorVNum;//电机电压
    private float totalMotorTem;
    private int motorTemNum;//电机温度
    private float totalMotorRot;
    private int motorRotNum;//电机转速
    private float totalMotorTor;
    private int motorTorNum;//电机转矩
    

    private float totalAccel; 
    private int accelNum; // 加速踏板
    private float totalBrake;
    private int brakeNum;// 制动踏板
    private float totalFlue;
    private int flueNum;// 油耗
    private float totalEngineThrottle;
    private int engineThrottleNum;// 发动机实际油门
    
    
    private float totalBatteryA;
    private int batteryANum;//电池电流
    private float totalBatteryA_all;
    private int batteryA_allNum;//电池总电流
    private float totalBatteryV;
    private int batteryVNum;//电池电压
    private float totalBatteryV_all;
    private int batteryV_allNum;//电池总电压
    private float totalBatteryTem;
    private int batteryTemNum;//电池温度
    private float totalPower;
    private int powerNum;//充放电能量
    
   
    private float totalGeneratorConApos; 
    private int generatorConAposNum;// 发电机正电流
    private float totalGeneratorConAneg; 
    private int generatorConAnegNum;// 发电机负电流

    
    private float totalDCDC_V;
    private int DCDC_VNum;//DCDC电压
    private float totalDCDC_A;
    private int DCDC_ANum;//DCDC电流
    private float totalDCDC_Tem;
    private int DCDC_TemNum;//DCDC温度
    
    private float totalMileage;
    private int warnTime;//总报警时长
    private int warnNum;//总报警次数
    private int timeType;//统计的时间粒度
    private int energyStorageWarn;
    private int driveWarn;
    private int auxiliaryWarn;
    private int otherWarn;
    private int energyStorageWarnTime;
    private int driveWarnTime;
    private int auxiliaryWarnTime;
    private int otherWarnTime;
    
	public String getRegName() {
		return regName;
	}
	public void setRegName(String regName) {
		this.regName = regName;
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
	public int getOnlineTime() {
		return onlineTime;
	}
	public void setOnlineTime(int onlineTime) {
		this.onlineTime = onlineTime;
	}
	public int getIsOver() {
		return isOver;
	}
	public void setIsOver(int isOver) {
		this.isOver = isOver;
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
	public int getIsAttendance() {
		return isAttendance;
	}
	public void setIsAttendance(int isAttendance) {
		this.isAttendance = isAttendance;
	}
	public int getSpeedNum() {
		return speedNum;
	}
	public void setSpeedNum(int speedNum) {
		this.speedNum = speedNum;
	}
	public float getTotalSpeed() {
		return totalSpeed;
	}
	public void setTotalSpeed(float totalSpeed) {
		this.totalSpeed = totalSpeed;
	}
	public float getMaxSpeed() {
		return maxSpeed;
	}
	public void setMaxSpeed(float maxSpeed) {
		this.maxSpeed = maxSpeed;
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
	public float getTotalSOC() {
		return totalSOC;
	}
	public void setTotalSOC(float totalSOC) {
		this.totalSOC = totalSOC;
	}
	public int getSOCNum() {
		return SOCNum;
	}
	public void setSOCNum(int sOCNum) {
		SOCNum = sOCNum;
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
	public float getTotalMileage() {
		return totalMileage;
	}
	public void setTotalMileage(float totalMileage) {
		this.totalMileage = totalMileage;
	}
	public int getWarnTime() {
		return warnTime;
	}
	public void setWarnTime(int warnTime) {
		this.warnTime = warnTime;
	}
	public int getWarnNum() {
		return warnNum;
	}
	public void setWarnNum(int warnNum) {
		this.warnNum = warnNum;
	}
	public int getTimeType() {
		return timeType;
	}
	public void setTimeType(int timeType) {
		this.timeType = timeType;
	}
	public int getEnergyStorageWarn() {
		return energyStorageWarn;
	}
	public void setEnergyStorageWarn(int energyStorageWarn) {
		this.energyStorageWarn = energyStorageWarn;
	}
	public int getDriveWarn() {
		return driveWarn;
	}
	public void setDriveWarn(int driveWarn) {
		this.driveWarn = driveWarn;
	}
	public int getAuxiliaryWarn() {
		return auxiliaryWarn;
	}
	public void setAuxiliaryWarn(int auxiliaryWarn) {
		this.auxiliaryWarn = auxiliaryWarn;
	}
	public int getOtherWarn() {
		return otherWarn;
	}
	public void setOtherWarn(int otherWarn) {
		this.otherWarn = otherWarn;
	}
	public int getEnergyStorageWarnTime() {
		return energyStorageWarnTime;
	}
	public void setEnergyStorageWarnTime(int energyStorageWarnTime) {
		this.energyStorageWarnTime = energyStorageWarnTime;
	}
	public int getDriveWarnTime() {
		return driveWarnTime;
	}
	public void setDriveWarnTime(int driveWarnTime) {
		this.driveWarnTime = driveWarnTime;
	}
	public int getAuxiliaryWarnTime() {
		return auxiliaryWarnTime;
	}
	public void setAuxiliaryWarnTime(int auxiliaryWarnTime) {
		this.auxiliaryWarnTime = auxiliaryWarnTime;
	}
	public int getOtherWarnTime() {
		return otherWarnTime;
	}
	public void setOtherWarnTime(int otherWarnTime) {
		this.otherWarnTime = otherWarnTime;
	}
	@Override
	public String toString() {
		return "AveData [id=" + id + ", vin=" + vin + ", startTime="
				+ startTime + ", lastTime=" + lastTime + ", onlineStime="
				+ onlineStime + ", onlineEtime=" + onlineEtime
				+ ", onlineTime=" + onlineTime + ", isOver=" + isOver
				+ ", count1=" + count1 + ", count2=" + count2 + ", count3="
				+ count3 + ", count4=" + count4 + ", count5=" + count5
				+ ", count6=" + count6 + ", isAttendance=" + isAttendance
				+ ", speedNum=" + speedNum + ", totalSpeed=" + totalSpeed
				+ ", maxSpeed=" + maxSpeed + ", startSOC=" + startSOC
				+ ", endSOC=" + endSOC + ", totalSOC=" + totalSOC + ", SOCNum="
				+ SOCNum + ", totalMotorConV=" + totalMotorConV
				+ ", motorConVNum=" + motorConVNum + ", totalMotorConApos="
				+ totalMotorConApos + ", motorConAposNum=" + motorConAposNum
				+ ", totalMotorConAneg=" + totalMotorConAneg
				+ ", motorConAnegNum=" + motorConAnegNum
				+ ", totalMotorConTem=" + totalMotorConTem
				+ ", motorConTemNum=" + motorConTemNum + ", totalMotorV="
				+ totalMotorV + ", motorVNum=" + motorVNum + ", totalMotorTem="
				+ totalMotorTem + ", motorTemNum=" + motorTemNum
				+ ", totalMotorRot=" + totalMotorRot + ", motorRotNum="
				+ motorRotNum + ", totalMotorTor=" + totalMotorTor
				+ ", motorTorNum=" + motorTorNum + ", totalAccel=" + totalAccel
				+ ", accelNum=" + accelNum + ", totalBrake=" + totalBrake
				+ ", brakeNum=" + brakeNum + ",totalFlue=" + totalFlue
				+ ", flueNum=" + flueNum + ", totalEngineThrottle="
				+ totalEngineThrottle + ", engineThrottleNum="
				+ engineThrottleNum + ", totalBatteryA=" + totalBatteryA
				+ ", batteryANum=" + batteryANum + ", totalBatteryA_all="
				+ totalBatteryA_all + ", batteryA_allNum=" + batteryA_allNum
				+ ", totalBatteryV=" + totalBatteryV + ", batteryVNum="
				+ batteryVNum + ", totalBatteryV_all=" + totalBatteryV_all
				+ ", batteryV_allNum=" + batteryV_allNum + ", totalBatteryTem="
				+ totalBatteryTem + ", batteryTemNum=" + batteryTemNum
				+ ", totalPower=" + totalPower + ", powerNum=" + powerNum
				+ ", totalGeneratorConApos=" + totalGeneratorConApos
				+ ", generatorConAposNum=" + generatorConAposNum
				+ ", totalGeneratorConAneg=" + totalGeneratorConAneg
				+ ", generatorConAnegNum=" + generatorConAnegNum
				+ ", totalDCDC_V=" + totalDCDC_V + ", DCDC_VNum=" + DCDC_VNum
				+ ", totalDCDC_A=" + totalDCDC_A + ", DCDC_ANum=" + DCDC_ANum
				+ ", totalDCDC_Tem=" + totalDCDC_Tem + ", DCDC_TemNum="
				+ DCDC_TemNum + ", totalMileage=" + totalMileage
				+ ", warnTime=" + warnTime + ", warnNum=" + warnNum
				+ ", timeType=" + timeType + ", energyStorageWarn="
				+ energyStorageWarn + ", driveWarn=" + driveWarn
				+ ", auxiliaryWarn=" + auxiliaryWarn + ", otherWarn="
				+ otherWarn + ", energyStorageWarnTime="
				+ energyStorageWarnTime + ", driveWarnTime=" + driveWarnTime
				+ ", auxiliaryWarnTime=" + auxiliaryWarnTime
				+ ", otherWarnTime=" + otherWarnTime + "]";
	}
	
}
