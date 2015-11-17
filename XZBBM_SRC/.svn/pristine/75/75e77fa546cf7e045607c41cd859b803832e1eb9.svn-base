<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;
}

/**
 * @todo 56广告系统库存监测子系统
 * @des 系统盘点的时候统一包括结束日期那一天
 * @author bo.wang3
 * @version 2013-7-22 14:29
 */
Class SuperVision extends Mads{
	
	public $show;
	
	public function __construct(){
		
		parent::__construct();
	}
	
	/**
	 * @author bo.wang3
	 * @version 2014-1-16 14:29
	 */
	public function Run(){

		include template('main',__class__);
	}
	
	/**
	 * @author bo.wang3
	 * @version 2014-1-16 14:29
	 */
	public function Query(){
		
		if($_REQUEST['func'] != 'Acs' && in_array(ROLE, array('AE','WORKER'))){
			echo json_encode(array('rs' => 3, 'msg' => '抱歉，您没有操作此功能的权限。'));exit;
		}
	
		switch($_REQUEST['func']){
			case 'Acs':
				$this->Acs();
			break;
			case 'Kc':
				$this->Inventory();
			break;
			case 'Sz_Tf_Dj':
				$this->Scwscript();
			break;
			case 'Kcyl_Zy':
				$this->Kcyl_Zy();
			break;
			case 'Wtw':
				$this->Wtw();
			break;
			case 'Xd_Zx_Sj':
				$this->Xd_Zx_Sj();
			break;
			case 'Kcjs':
				$this->Kcjs();
			break;
			case 'Khtf':
				$this->Khtf();
			break;
		}
	}
	
	/**
	 * @todo 封装的库存盘点类，获取基础的盘点数据
	 * @author bo.wang3
	 * @version 2014-1-16 14:29
	 * @ $bt $et 日期
	 * @ 需要盘点的类型  $need 
	 * @return array
	 */
	public function Kcpd($bt,$et,$type,$areas,$ext = true){
		
		if(is_numeric($bt) || is_numeric($et)){
			exit('GetActualAmount - 日期格式输入不正确！');
		}
		
		if(strtotime($et) < strtotime($bt)){
			echo json_encode(array('rs' => 3, 'msg' => '盘点时间范围不正确！'));exit;
		}
	
		if((strtotime($et) - strtotime($bt))/86400 > 180){
			echo json_encode(array('rs' => 3, 'msg' => '时间跨度不能超过180天！'));exit;
		}
		
		//远程获取视频VV
		$url = 'http://14.17.117.101/ad_get_vv.php';
		$q = array(
				'starttime' => $bt,
				'endtime' => $et, //ORACLE日期处理的时候本身是包含最后一天的
				'areas' => $areas?$areas:$_REQUEST['areas'],
				'type' => $type
		);
	
		$vvs = json_decode(file_get_contents("$url?".http_build_query($q)),true);
			
		//统一包含最后一天，兼容Mysql
		$bt = strtotime($bt);
		$et = strtotime($et) + 86400;
		
		foreach($vvs as $k => $v){
	
			$ads_area = $k =='中国'?'':$k;
			$data[$k]['vv'] = (double)$v;
			$data[$k]['area'] = $k;
			$data[$k]['bt'] =  date('Y-m-d',$bt);
			$data[$k]['et'] =  date('Y-m-d',($et - 86400));
			$data[$k]['type'] =  $type?intval($type):intval($_REQUEST['type']);
			
			if($type == 1){
				//前贴根据参数自动估算库存  全国是1.3倍
				$ratio = $this->_db_source->rsArray("SELECT xishu FROM ad_geoinfo WHERE area_name RLIKE '{$k}'");
				$data[$k]['kcz'] = floor($data[$k]['vv'] * (float)$ratio['xishu']);
			}else{
				$data[$k]['kcz'] = $data[$k]['vv'];
			}
				
			$data[$k]['kc'] = floor($data[$k]['kcz']/1000);
			
			if($ext == true){
				
				//缩小aid范围，缩小后续统计范围
				$tar_aids = $this->_db_source->dataArray("SELECT aid FROM ad_log
														  WHERE (stime BETWEEN $bt AND $et)
														  AND (aid between 1000 and 90000)
														  GROUP BY aid");
									
				foreach($tar_aids as $tmp){
					$tar_aids_r[] = $tmp['aid'];
				}
				$tar_aids = implode(',',$tar_aids_r);
				unset($tar_aids_r);
				
				//定义贴片类型
				$tj_types = array(
						'normal' => "(ads.type = $type AND ads.cid IN (111,114,137,170))", //常规贴片
						'full' => "(ads.type = 43 AND ads.cid IN (213))",
				);
				
				foreach ($tj_types as $type_name => $type_condition){
				
					//通投及定投
					$sql =  "SELECT aid FROM ads,ad_geoinfo
							 WHERE ads.aid IN ($tar_aids)
							 AND ads.vid NOT IN (1,11,144,315,23,230,800)
							 AND $type_condition";
				
					$sql_arr = array(
						//全国通投aids
						'tt' => $sql." AND (((ad_geoinfo.area_level = 4 or ad_geoinfo.area_level = 2) and (CONCAT(ads.city,ads.area) = ad_geoinfo.area_name))
								   	   OR CONCAT(ads.city,ads.area) RLIKE '_')",
						//区域定向aids
						'qy' => $sql." AND ad_geoinfo.area_level = 1
									   AND CONCAT(ads.city,ads.area) = ad_geoinfo.area_name",
						//北上广深aids
						'bsgs' => $sql." AND ad_geoinfo.area_level = 3
										 AND CONCAT(ads.city,ads.area) = ad_geoinfo.area_name ",
						//定向某个城市
						//'dx' => $sql." AND concat(city,area) RLIKE '$ads_area'"
					);
				
					//实投-所有的投放量  包括我秀、内部推广 不包括800
					$sql_arr['st'] = "SELECT aid FROM ads
									  WHERE aid IN ($tar_aids)
									  AND vid != 800
									  AND $type_condition";
				
					//我秀和内部推广
					$sql_arr['nb'] = "SELECT aid FROM ads
									  WHERE aid IN ($tar_aids)
									  AND vid IN (1,11,144,315,23,230)
									  AND $type_condition";
				
					//正逆向的数据之差用于评估流程链的标准性，例如DSP AE下单时间范围跟实际投放时间范围不一致
		
					//品牌实投
					//逆向推导品牌对应的实投量 只关注指定时间范围内真实的品牌投放量
					$sql_arr['pq'] = "SELECT aid FROM ads,ad_contract
									  WHERE ads.aid IN ($tar_aids)
									  AND ads.vid NOT IN (1,11,144,315,23,230,800)
									  AND $type_condition
									  AND CONCAT(',',ad_contract.related_aids) RLIKE CONCAT(CONCAT(',',ads.aid),',')";
		
					//正逆向的数据之差用于评估流程链的标准性，例如DSP AE下单时间范围跟实际投放时间范围不一致
				
					//获取目标aids
					foreach($sql_arr as $s => $t){
						$aids_arr[$s] = $this->_db_source->dataArray($t." GROUP BY aid");
						foreach ($aids_arr[$s] as $tmp){
							$aids_t[] = $tmp['aid'];
						}
						$aids_arr[$s] = implode(',', $aids_t);
						unset($aids_t);
					}
				
					//查询及统计
					foreach($aids_arr as $m => $n){
				
						$sql = "SELECT sum(view) as total FROM ad_log
								WHERE (stime BETWEEN $bt AND $et)
								AND aid IN ($n)";
							
						$q_rs = $this->_db_source->rsArray($sql);
				
						if($type_name == 'full'){ //全贴片按比例修改值
							switch((int)$type){
								case 1:
									$q_rs['total'] = $q_rs['total']*0.88;
								break;
								case 2:
									$q_rs['total'] = $q_rs['total']*0.02;
								break;
								case 3:
									$q_rs['total'] = $q_rs['total']*0.10;
								break;
								default:
									$q_rs['total'] = 0;
								break;
							}
						}
						$data[$k][$m] += intval($q_rs['total']);
					}
				}
							
				//有效投放
				$data[$k]['yx'] = $data[$k]['st'] - $data[$k]['nb'];
				//盘点做差品牌量
				$data[$k]['pq_c'] = $data[$k]['tt'] + $data[$k]['qy'] + $data[$k]['bsgs'];
				//盘点非品牌量
				$data[$k]['pq_no'] = $data[$k]['yx'] - $data[$k]['pq'];
							
				//盘点指定时间段内的品牌预定量
				//预定量
				$sql = "SELECT ads_pre.cpm,ads_pre.starttime,ads_pre.endtime,ads_pre.cid,ads_pre.type,ads_pre.for_rid FROM ads_pre,ad_reserve
						WHERE ad_reserve.id = ads_pre.for_rid
						AND ad_reserve.status > 1 AND ad_reserve.status != 3 AND ad_reserve.result = 1
						AND ((ad_reserve.starttime between $bt and $et) or (ad_reserve.endtime between $bt and $et) or (ad_reserve.starttime <= $bt and ad_reserve.endtime >= $et))
						AND ((ad_reserve.cid IN (111,114,137,170) and ad_reserve.type = $type) or ad_reserve.cid = 213)
						AND ad_reserve.id = ads_pre.for_rid";
									
				$rs = $this->_db_source->dataArray($sql);
				$r_ids = array();
									
				foreach ($rs as $v){
					
					$mixeddate = GetMixedTime(array('start' => intval($v['starttime']),'end' => intval($v['endtime'])),array('start' => $bt,'end' => ($et)),1);
					$valid_num = false === $mixeddate?0:count(pre_dates($mixeddate['start'], $mixeddate['end'],0)); //不包含最后一天
				
					if($v['cid'] == '213'){
					//全贴的预定量按 0.83 0.02 0.15 的比例计算
						if($type == 1){
							$data[$k]['yd'] += $valid_num*$v['cpm']*0.88;
						}elseif($type == 2){
							$data[$k]['yd'] += $valid_num*$v['cpm']*0.02;
						}else{
							$data[$k]['yd'] += $valid_num*$v['cpm']*0.10;
						}
					}else{
						$data[$k]['yd'] += ($valid_num*$v['cpm']);
					}
									
					//记录下预订单编号
					if(!in_array($v['for_rid'], $r_ids)){
						$r_ids[] = $v['for_rid'];
					}
				}
											
				$data[$k]['yd'] = floor($data[$k]['yd']);
										
				//根据预订单号反推品牌实投量
				foreach ($r_ids as $r_id){
					$data[$k]['pq_yd'] += $this->GetActualAmount($bt, $et, $r_id ,'ad_reserve',$type);
				}
							
				$data[$k]['pq_yd_no'] = $data[$k]['yx'] - $data[$k]['pq_yd'];
			}
		}
		
		return $data;
	}
	
	/**
	 * @todo 客户投放盘点
	 * @author bo.wang3
	 * @version 2014-1-16 14:29
	 */
	public function Khtf(){
	
		$bt = strtotime($_REQUEST['bt']);
		$et = strtotime($_REQUEST['et']) + 86400;
		$vid = empty($_REQUEST['vid'])?'client':trim($_REQUEST['vid']);
	
		$skey = md5('Khtf'.$bt.$et.$vid);
	
		if(!empty($_SESSION[$skey])){
	
		}else{
	
			if($bt >= $et || $et - $bt > 86400*100){
				echo json_encode(array('rs' => 3, 'msg' => '盘点时间段设置不正确！'));exit;
			}else{
				$data['bt'] = $_REQUEST['bt'];
				$data['et'] = $_REQUEST['et'];
				$total_day = count(pre_dates($bt, $et ,1));
			}
				
			//查找已审核+准备投放+正在投放+投放结束的订单 如未指定客户，则列出所有的客户
			$sql = "SELECT * from ad_reserve
					WHERE status > 1 AND status != 3 AND result = 1
					AND client = $vid
					AND ((starttime between $bt and $et) or (endtime between $bt and $et) or (starttime < $bt and endtime > $et))";
	
			$data['ctc'] = $this->_db_source->dataArray($sql);
				
			foreach($data['ctc'] as $k => $v){
				//取两个时间的交集，获取有效时间段
				$data_point = GetMixedTime(array('start' => intval($v['starttime']),'end' => intval($v['endtime'])),array('start' => $bt,'end' => $et),1);
				$valid_day_total = count(pre_dates($data_point['start'], $data_point['end']));;
	
				if(in_array($v['status'], array(2,4))){
					//已审核+准备投放的订单量为预定量
					$sql = "SELECT sum(cpm) as t FROM ads_pre
							WHERE for_rid = $v[id]";
						
					$rs = $this->_db_source->rsArray($sql);
					$data['ctc'][$k]['data'] = $rs['t']*$valid_day_total;
				}else{
					//正在投放+投放结束的订单量为实际曝光量
					$rs = $this->GetActualAmount($bt, $et, $v['id'] , 'ad_reserve' , $v['type']);
					$data['ctc'][$k]['data'] = floor($rs/1000); //转换为CPM
				}
			}
				
			if(empty($data)){
				echo json_encode(array('rs' => 3, 'msg' => '指定条件下数据盘点结果为空！'));exit;
			}
	
			$_SESSION[$skey] = json_encode($data);
				
		}
	
		if(isset($_REQUEST['config']['outtoexcel'])){ //导出为Excel表格
			echo json_encode(array('rs' => 1, 'msg' => './?action=SuperVision&do=ShowResult&func=Khtf&headerType=xls&skey='.$skey));
		}else{
			echo json_encode(array('rs' => 0, 'msg' => './?action=SuperVision&do=ShowResult&func=Khtf&skey='.$skey));
		}
	}
	
	/**
	 * @todo 库存余量占用盘点
	 * @author bo.wang3
	 * @version 2014-1-16 14:29
	 */
	public function Kcyl_Zy(){
	
		$bt = $_REQUEST['bt'];
		$et = $_REQUEST['et']; 
		$type = trim($_REQUEST['type']);
		$areas = empty($_REQUEST['areas'])?'中国':trim($_REQUEST['areas']);
		
		if(strtotime($bt) > strtotime($et)){
			echo json_encode(array('rs' => 3, 'msg' => '盘点时间设置不正确！'));exit;
		}
	
		$skey = md5('Kcyl_Zy_QUERY'.$bt.$et.$type.$areas);
	
		if(!empty($_SESSION[$skey])){
	
		}else{
				
			$inner_days = pre_dates($bt, $et , 1);//包括时间段最后一天全天
			$days_total = count($inner_days);
			
			$data['bt'] =  $_REQUEST['bt'];
			$data['et'] =  $_REQUEST['et'];
			$data['type'] =  $type;
			$data['areas'] =  $areas;
	
			//开始获取预定量和实投量
			$input = array(
				'starttime' => strtotime($data['bt']),
				'endtime' => strtotime($data['et']) + 86400, //包含最后一天
				'area' => str_replace(',', '_', $areas),
				'cid' => 170,
				'type' => $_REQUEST['type'],
				'version' => 2,
				'project' => 2,
				'is_show' => 0
			);
			
			$d_data = array(
				'input' => addslashes(json_encode($input)),
				'status' => 0,
				'op_type' => 1,
				'require_ts' => TIMESTAMP,
				'applicant' => ADMIN
			);
			
			//插入数据库获得任务编码
			$op_rs = $this->_db->insert('ad_checkamounts',$d_data);
			$msg = $input;
			
			$msg['id'] = $op_rs;
			$msg['op_type'] = $d_data['op_type'];
			if(HOSTNAME == 't3.56.com'){
				$msg['srcpath'] = '/tmp/dispatch_t3';
				$msg['workpath'] = '/tmp/dispatch_t3_workspace';
				$port = 8081;
			}else{
				$msg['srcpath'] = '/home/applications/ad_simulation/dispatch';
				$msg['workpath'] = '/home/applications/adsim_workspace/dispatch';
				$port = 8080;
			}
			$msg = urlencode(json_encode($msg));
			
			//与查量服务器通信
			Http::Post('180.153.21.185','dispatch',$msg,$port);
			
			//盘点过去30天可投视频的库存预测未来库存 - 两台机器并行工作
			$rs = $this->Kcpd(date('Y-m-d',TIMESTAMP - 2592000),date('Y-m-d',TIMESTAMP),$type,$areas,false); //盘点库存量
			$rs = current($rs);

			$data['kc'] = floor(($rs['kc']/30)*$days_total);
			$data['kc_str'] = intformat($data['kc']);
			
			$t = 0;
			do{//计时并获取远程结果，假如查量时间超过10秒则通知前端用户等待，通知后端脚本发邮件。
				if($t++ > 10){
					$v = array(
							'kc' => $data['kc'],
							'bt' => $data['bt'],
							'et' => $data['et'],
							'area' => $areas,
							'to' => ADMIN.'@renren-inc.com'
					);
					$this->_db->update('ad_checkamounts',array('detail' => json_encode($v)),"id = $op_rs");
					echo json_encode(array('rs' => 3, 'msg' => '由于此次库存余量盘点耗时较长，系统稍后会将查量结果发送到'.ADMIN.'@renren-inc.com，请注意查收！'));exit;
				}else{
					$r = $this->_db->rsArray("SELECT detail FROM ad_checkamounts WHERE id = $op_rs");
					sleep(1);
				}
			}while(empty($r['detail']));
			
			$detail = json_decode($r['detail'],true);
			
			$data['yd'] = floor($detail[1]['nor_pre']/1000);
			$data['st'] = floor($detail[1]['nor_act']/1000);
			$data['yl'] = $data['kc'] - $data['yd'] - $data['st'];
			
			//if($data['yl'] < 0){
				//echo json_encode(array('rs' => 3, 'msg' => "预定({$data[yd]})+实投量({$data[st]})大于预计库存({$data[kc]})，请与技术人员联系！"));exit;
			//}
			
			$data['yd_str'] = intformat($data['yd']);
			$data['st_str'] = intformat($data['st']);
			$data['yl_str'] = intformat($data['yl']);
			
			if(empty($detail)){
				echo json_encode(array('rs' => 3, 'msg' => '指定条件下数据盘点结果为空！'));exit;
			}
			
			$_SESSION[$skey] = json_encode($data);
			
			/*
			//库存
			//预定量
			$sql = "select cpm,starttime,endtime,cid,type,status from ads_pre
			where status = 0
			and ((cid = 170 and type = $type) or cid = 213)
			and ((starttime between $bt and $et) or (endtime between $bt and $et))";
			$rs = $this->_db_source->dataArray($sql);
				
			foreach ($rs as $v){
				$mixeddate = GetMixedTime(array('start' => intval($v['starttime']),'end' => intval($v['endtime'])),array('start' => $bt,'end' => $et));
				$valid_num = false === $mixeddate?0:count(pre_dates($mixeddate['start'], $mixeddate['end'] + 86400));
				if($v['cid'] == 213){
					//全贴的预定量按 0.83 0.02 0.15 的比例计算
					if($type == 1){
						$data['yd'] += $valid_num*$v['cpm']*0.83;
					}elseif($type == 2){
						$data['yd'] += $valid_num*$v['cpm']*0.02;
					}else{
						$data['yd'] += $valid_num*$v['cpm']*0.15;
					}
				}else{
					$data['yd'] += $valid_num*$v['cpm'];
				}
			}
			$data['yd_str'] = intformat($data['yd']);
							
			//实投量
			$sql = "select cpm,starttime,endtime,cid,type,status from ads
			where ((cid = 170 and type = $type) or cid = 213)
			and ((starttime between $bt and $et) or (endtime between $bt and $et))";
			$rs = $this->_db_source->dataArray($sql);
					
			foreach ($rs as $v){
				$mixeddate = GetMixedTime(array('start' => intval($v['starttime']),'end' => intval($v['endtime'])),array('start' => $bt,'end' => $et));
				$valid_num = false === $mixeddate?0:count(pre_dates($mixeddate['start'], $mixeddate['end'] + 86400));
				if($v['cid'] == 213){
					//全贴的预定量按 0.83 0.02 0.15 的比例计算
					if($type == 1){
						$data['st'] += $valid_num*$v['cpm']*0.83;
					}elseif($type == 2){
						$data['st'] += $valid_num*$v['cpm']*0.02;
					}else{
						$data['st'] += $valid_num*$v['cpm']*0.15;
					}
				}else{
					$data['st'] += $valid_num*$v['cpm'];
				}
			}
			$data['st_str'] = intformat($data['st']);
											
			//余量
			$data['yl'] = $data['kc'] - $data['st'] - $data['yd'];
			$data['yl_str'] = intformat($data['yl']);
			*/
		}
	
		if(isset($_REQUEST['config']['sendmail'])){ //邮件知会
			$this->EmailInform('Kcyl_Zy', $skey);
		}
	
		if(isset($_REQUEST['config']['outtoexcel'])){ //导出为Excel表格
			echo json_encode(array('rs' => 1, 'msg' => './?action=SuperVision&do=ShowResult&func=Kcyl_Zy&headerType=xls&skey='.$skey));
		}else{
			echo json_encode(array('rs' => 0, 'msg' => './?action=SuperVision&do=ShowResult&func=Kcyl_Zy&skey='.$skey));
		}
	}
	
	/**
	 * @todo 库存结算盘点
	 * @author bo.wang3
	 * @version 2014-1-16 14:29
	 */
	public function Kcjs(){
	
		$bt = trim($_REQUEST['bt']);
		$et = trim($_REQUEST['et']);
		$type = trim($_REQUEST['type']);
	
		$skey = md5('Kcjs'.$bt.$et.$type);
	
		if(!empty($_SESSION[$skey])){
	
		}else{
			
			if(strtotime($et) > TIMESTAMP){
				echo json_encode(array('rs' => 3, 'msg' => '盘点结束时间应小于当前时间！'));exit;
			}
			
			$rs = $this->Kcpd($bt,$et,$type,'中国'); //盘点库存量
			
			$data = array(
				'vv' => $rs['中国']['vv'],
				'kcz' => $rs['中国']['kcz'],
				'kc' => $rs['中国']['kc'],
				'yd' => $rs['中国']['yd'],
				'pq' => floor($rs['中国']['pq']/1000),
				'bt' => $bt,
				'et' => $et
			);
			
			$data['st'] = floor($data['pq']/1000);
			$data['sh'] = round(($data['pq']/$data['yd'] - 1),4)*100; //投放损耗
			
			$data['jsl'] = min($data['yd'],$data['pq']); //可结算量等于客户预定量和实投量中较小的一个
			$data['jsbl'] = round(($data['jsl']/$data['kc']),4)*100;  //可结算比例
			
			if(empty($data)){
				echo json_encode(array('rs' => 3, 'msg' => '指定条件下数据盘点结果为空！'));exit;
			}
			
			$_SESSION[$skey] = json_encode($data);
			
		}
	
		if(isset($_REQUEST['config']['outtoexcel'])){ //导出为Excel表格
			echo json_encode(array('rs' => 1, 'msg' => './?action=SuperVision&do=ShowResult&func=Kcjs&headerType=xls&skey='.$skey));
		}else{
			echo json_encode(array('rs' => 0, 'msg' => './?action=SuperVision&do=ShowResult&func=Kcjs&skey='.$skey));
		}
	}
	
	/**
	 * @todo 下单、执行设置、实际投出量盘点
	 * @author chuwen.shan
	 * @version 2014-1-16 14:29
	 */
	public function Xd_Zx_Sj(){
	
		$bt = strtotime($_REQUEST['bt']);
		$et = strtotime($_REQUEST['et']) + 86400;
		$rid = trim($_REQUEST['rid']);
	
		$skey = md5('Xd_Zx_Sj'.$bt.$et.$rid);
	
		if(!empty($_SESSION[$skey])){
	
		}else{
	
			if($bt >= $et || $et - $bt > 86400*90){
				echo json_encode(array('rs' => 3, 'msg' => '盘点时间段设置不正确！'));exit;
			}else{
				$data['bt'] = $_REQUEST['bt'];
				$data['et'] = $_REQUEST['et'];
				$total_day = count(pre_dates($bt, $et ,1)); 
			}
			
			//获取预订单详情
			$data['reserve'] = $this->_db_source->rsArray("SELECT * FROM ad_reserve WHERE id = $rid");
			
			//获取指定时间段内客户预订量
			$data['re'] = $this->GetConsumerNeed('',$rid,'ad_reserve',$bt,$et);

			//获取目标AID群
			$sql = "SELECT ad_contract.related_aids from ad_reserve,ad_contract
					WHERE ad_reserve.id = $rid
					AND ad_reserve.reserve_code = ad_contract.reserve_code";
			$as = $this->_db_source->rsArray($sql);
			
			//获取执行设置量和实际投出量
			foreach($data['re'] as $k => $v){
				
				if(empty($v['city'])){
					//预定全国通投的盘点
					$data['re'][$k]['cpm_finish'] = floor($this->GetActualAmount($bt,$et,$data['reserve']['id'],'ad_reserve')/1000);
					$data['re'][$k]['aid'] = rtrim($as['related_aids'],',');
					break;
				}else{
					//预定区域定向的盘点
					//获取目标aid
					$sql = "SELECT aid from ads
							WHERE aid IN (".rtrim($as['related_aids'],',').")
							AND concat(city,area) RLIKE '$v[city]' LIMIT 1";
					$ads = $this->_db_source->rsArray($sql);
					
					//获取指定日期区间内投放设置量和完成量
					// 由于不准确，这里该用自己的接口盘点
					$sql = "SELECT vid,sum(cpm_order) as cpm_order,sum(cpm_finish) as cpm_finish FROM ad_dayfinish
					WHERE time between $bt and $et
					AND aid = $ads[aid]";
					$rs = $this->_db_source->rsArray($sql);
					
					$data['re'][$k]['aid'] = $ads['aid'];
					$data['re'][$k]['cpm'] = $v['cpm'];
					//$data['re'][$k]['cpm_order'] = $rs['cpm_order'];
					$data['re'][$k]['cpm_finish'] = floor($this->GetActualAmount($bt,$et,$ads['aid'])/1000);
					$data['re'][$k]['vid'] = $rs['vid'];
				}
			}
			
			if($data['re'] == 0){
				echo json_encode(array('rs' => 3, 'msg' => '指定条件下数据盘点结果为空！'));exit;
			}
			
			$_SESSION[$skey] = json_encode($data); //带过去re和reserve两个字段
			
		}
	
		if(isset($_REQUEST['config']['outtoexcel'])){ //导出为Excel表格
			echo json_encode(array('rs' => 1, 'msg' => './?action=SuperVision&do=ShowResult&func=Xd_Zx_Sj&headerType=xls&skey='.$skey));
		}else{
			echo json_encode(array('rs' => 0, 'msg' => './?action=SuperVision&do=ShowResult&func=Xd_Zx_Sj&skey='.$skey));
		}
	}
	
	
	/**
	 * @todo 未投完订单盘点
	 * @author bo.wang3
	 * @version 2014-1-16 14:29
	 */
	public function Wtw(){
	
		$bt = strtotime($_REQUEST['bt']);
		$et = strtotime($_REQUEST['et']) + 86400; //包括时间段最后一天全天
		$rid = trim($_REQUEST['rid']);
		$vid = $_REQUEST['vid']?trim($_REQUEST['vid']):'';
		$areas = $_REQUEST['areas']?rtrim($_REQUEST['areas'],','):'';
		
		$skey = md5('Wtw'.$bt.$et.$vid.$rid.$areas);
	
		if(!empty($_SESSION[$skey])){
	
		}else{
			
			if($bt >= $et || $et - $bt > 86400*32){
				echo json_encode(array('rs' => 3, 'msg' => '盘点时间段范围不得超过31天！'));exit;
			}
			
			$sql = "SELECT ad_contract.related_aids FROM ad_reserve,ad_contract
					WHERE ad_reserve.id = $rid
					AND ad_reserve.reserve_code = ad_contract.reserve_code";
			
			$for_aids = $this->_db_source->rsArray($sql);
			
			$aids = empty($for_aids['related_aids'])?'':rtrim($for_aids['related_aids'],',');
			
			$sql = "SELECT aid,sum(cpm_order) as cpm_order,sum(cpm_finish) as cpm_finish,ad_place,ad_type,Consumer,title,city,vid 
					FROM ad_dayfinish 
					WHERE (time BETWEEN $bt AND $et)
					AND vid NOT IN (1,11,144,315,23,230,800)";
			
			if(!empty($vid)){
				$sql .= " AND vid = $vid";
			}
			
			if(!empty($aids)){
				$sql .= " AND aid IN ($aids)";
			}elseif(!empty($rid)){
				$sql .= " AND aid IN ()";
			}
			
			if(!empty($areas)){
				$areas = explode(',',$areas);
				foreach ($areas as $area){
					$area_cdt[] = "city RLIKE '$area'";
				}
				$sql .= " AND (".implode(' OR ', $area_cdt).")";
			}
			
			$sql .= ' GROUP BY aid';
			
			$data = $this->_db_source->dataArray($sql);
			
			if(empty($data)){
				echo json_encode(array('rs' => 3, 'msg' => '指定条件下数据盘点结果为空！'));exit;
			}else{
				$data['bt'] = $bt;
				$data['et'] = $et;
			}
			
			$_SESSION[$skey] = json_encode($data);
			
		}
	
		if(isset($_REQUEST['config']['outtoexcel'])){ //导出为Excel表格
			echo json_encode(array('rs' => 1, 'msg' => './?action=SuperVision&do=ShowResult&func=Wtw&headerType=xls&skey='.$skey));
		}else{
			echo json_encode(array('rs' => 0, 'msg' => './?action=SuperVision&do=ShowResult&func=Wtw&skey='.$skey));
		}
	}
	
	/**
	 * @todo 库存数据盘点
	 * @author bo.wang3
	 * @version 2014-1-16 14:29
	 */
	public function Inventory(){
		
		$bt = trim($_REQUEST['bt']);
		$et = trim($_REQUEST['et']);
		$type = trim($_REQUEST['type']);
		$area = empty($_REQUEST['areas'])?'中国':trim($_REQUEST['areas']);
		
		$skey = md5('INVEN_QUERY'.$bt.$et.$type.$area);
		
		if(!empty($_SESSION[$skey])){
			
		}else{
			
			$rs = $this->Kcpd($bt,$et,$type,$area);
			$_SESSION[$skey] = json_encode($rs);
		}
		
		if(isset($_REQUEST['config']['outtoexcel'])){ //邮件知会
			$this->EmailInform('Inventory', $skey);
		}
		
		if(isset($_REQUEST['config']['outtoexcel'])){ //导出为Excel表格
			echo json_encode(array('rs' => 1, 'msg' => './?action=SuperVision&do=ShowResult&func=Inventory&headerType=xls&type='.$type.'&skey='.$skey));
		}else{
			echo json_encode(array('rs' => 0, 'msg' => './?action=SuperVision&do=ShowResult&func=Inventory&type='.$type.'&skey='.$skey));
		}
	}
	
	/**
	 * @todo ACS数据盘点
	 * @author bo.wang3
	 * @version 2014-1-16 14:29
	 */
	public function Acs(){
		
		$bt = strtotime($_REQUEST['bt']);
		$et = strtotime($_REQUEST['et']);
		$aids = explode(',',trim($_REQUEST['aid']));
		
		$skey = md5('ACS_QUERY'.$bt.$et.$_REQUEST['aid']);
		
		if(empty($_SESSION[$skey])){ //借用SESSION机制防止多次查询
			
			if($et < $bt){
				echo json_encode(array('rs' => 3, 'msg' => '时间范围不正确！'));exit;
			}
			
			for($i = $bt;$i <= $et;$i += 86400){
				$dates[] = date('Y-m-d',$i);
			}
			
			if(count($dates) > 120){
				echo json_encode(array('rs' => 3, 'msg' => '时间跨度不能超过120天！'));exit;
			}
			
			$rs['aids'] = $_REQUEST['aid'];
			$rs['bt'] =  $_REQUEST['bt'];
			$rs['et'] =  $_REQUEST['et'];
			
			$rs['common']['dates'] = $dates;
			$rs['common']['aids'] = $aids;
			
			foreach($aids as $aid){
					
				$rs['data'][$aid]['info'] = $this->_db->rsArray("SELECT * FROM ads WHERE aid = $aid LIMIT 0,1");
					
				foreach($dates as $data){
					$date = explode('-', $data);
					$year =  intval($date[0]);
					$month = intval($date[1]);
					$day =   intval($date[2]);
			
					$rs['data'][$aid][$data]['summ'] = $this->_db->rsArray("SELECT sum(view) as view_total,sum(click) as click_total,sum(viewip) as viewip_total FROM ad_log WHERE aid = $aid AND year = $year AND month = $month AND day = $day");
					
					$tmp = $this->_db->dataArray("SELECT hour,view,click,viewip FROM ad_log WHERE aid = $aid AND year = $year AND month = $month AND day = $day");
					foreach ($tmp as $v){
						$detail[$v[hour]] = $v;
						unset($detail[$v[hour]]['hour']);
					}
			
					$rs['data'][$aid][$data]['detail'] = $detail;
					unset($detail);
				}
					
			}
			
			if(empty($rs)){
				echo json_encode(array('rs' => 3, 'msg' => '指定条件下数据盘点结果为空！'));exit;
			}
			
			$_SESSION[$skey] = json_encode($rs);
			
		}
		
		if(isset($_REQUEST['config']['outtoexcel'])){ //导出为Excel表格
			echo json_encode(array('rs' => 2, 'msg' => $skey));
		}else{
			echo json_encode(array('rs' => 0, 'msg' => './?action=SuperVision&do=ShowResult&func=Acs&skey='.$skey));
		}
	}
	
	/**
	 * @author chuwen.shan
	 * @version 2014-1-16 14:29
	 */
	public function Scwscript(){
		
		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('gb2312');
		$fname = $_FILES['Excel']['tmp_name'];
		
		$data->read($fname);
		
		$startTime = strtotime($data->sheets[0]['cells'][1][13]);
		
		$j = 13;
		
		$count = 12;
		while($time = strtotime($data->sheets[0]['cells'][1][$j])){
			if(preg_match("/^[0-9]{1,4}(\-|\/)[0-9]{1,4}(\-|\/)[0-9]{1,4}$/",$data->sheets[0]['cells'][1][$j])){
				$endTime = $time;
				$count++;
			}
			$j++;
		}
		
		if($endTime - $startTime > 86400*31*2) exit;
		
		if($startTime < 0 || $endTime < 0){
			exit;
		}
		$i = 0;
		
		while($aid = $data->sheets[0]['cells'][$i*4+2][10]){
			preg_match('/\d+/',$data->sheets[0]['cells'][$i*4+2][10],$matches);
			$data->sheets[0]['cells'][$i*4+2][10] = $matches[0];
			$aid = $matches[0];
			$sqlViewClick   = "select year,month,day,sum(view),sum(click) from ad_log ";		//View & Click
			$sqlViewClick  .= "where stime >= $startTime and stime < $endTime ";
			$sqlViewClick  .= "and aid = $aid ";
			$sqlViewClick  .= "group by year,month,day";
			$queryViewClick = $this->_db_source->conn($sqlViewClick);
			$sqlRows=NULL;
			while($rowsViewClick = mysql_fetch_array($queryViewClick) ){ /*sql数据*/
				//将sql数据放到$b数组中 $b['Y-n-d']['vv'] = xxx ; $b['Y-n-d']['clk'] = xxx;
				$time_sql = $rowsViewClick[year] . "-" . $rowsViewClick[month] . "-" . $rowsViewClick[day];
				$time_sql2 = strtotime($time_sql);
		
				$sum_vv = round($rowsViewClick[3]/1000,0);
				$sum_clk = $rowsViewClick[4];
				$sqlRows[$time_sql2] = array('vv'=>$sum_vv,'clk'=>$sum_clk);
			}
			$j=13;
		
			while( $data->sheets[0]['cells'][1][$j] ){ /*日期遍历*/
				if( $data->sheets[0]['cells'][$i*4+2][$j] ){ /*购买量有值*/
					preg_match('/\d+/',$data->sheets[0]['cells'][$i*4+2][$j],$matches);
					$data->sheets[0]['cells'][$i*4+2][$j] = $matches[0];
					//取日期时间戳 strtotime
					$time_xls = (int)strtotime($data->sheets[0]['cells'][1][$j]);
		
					//取数组，逐条判断
					if( array_key_exists($time_xls,$sqlRows) ){ /*$b['对应日期']['vv'] ||　$b['对应日期']['clk']　存在*/
						//填充
						$data->sheets[0]['cells'][$i*4+4][$j] = $sqlRows[$time_xls]['vv'];
						$data->sheets[0]['cells'][$i*4+5][$j] = $sqlRows[$time_xls]['clk'];
					}else {
						//填0
						$data->sheets[0]['cells'][$i*4+4][$j] = 0;
						$data->sheets[0]['cells'][$i*4+5][$j] = 0;
					}
				}
				//else{/*下一个*/}
				$j++;
			}
			$i++;
		}	//__end search stime
		
		$i=0;
		
		while($aid = $data->sheets[0]['cells'][$i*4+2][10]){
		
			$sqlCpm = "select date_format(logtime,'%Y-%m-%d')logdate,unix_timestamp(date_format(logtime,'%Y-%m-%d'))logtime, starttime, endtime,enable,cpm from ";
			$sqlCpm .= "(select * from ad_history where aid = $aid order by unix_timestamp(logtime) desc ) tmp group by date_format(logtime,'%Y-%m-%d') desc";
			
			$queryCpm = $this->_db_t3->conn($sqlCpm);
			
			$j = $count;
		
			while($rowsCpm = mysql_fetch_array($queryCpm)){
				//[startTime         <--tt(endTime)]
				for(; $j >= 13 ; $j--){
					$tt = (int)strtotime($data->sheets[0]['cells'][1][$j]);
					if($tt < $rowsCpm[logtime]){break;}
					if( $data->sheets[0]['cells'][$i*4+2][$j] ){
						if($rowsCpm[enable]==0 || ($rowsCpm[endtime] < $rowsCpm[starttime]) )  //cpm=0;
						{	$data->sheets[0]['cells'][$i*4+3][$j] = 0;	}
						else {
							if( $tt < $rowsCpm[starttime] || $tt > $rowsCpm[endtime])  //cpm= 0;
							{	$data->sheets[0]['cells'][$i*4+3][$j] = 0;	}
							if( $rowsCpm[logtime] <= $rowsCpm[endtime] ){
								if( $rowsCpm[starttime] <= $tt && $tt <= $rowsCpm[endtime]) //cpm = cpm;
								{	$data->sheets[0]['cells'][$i*4+3][$j] = $rowsCpm[5];
									
								}
							}
						}
					}
				}
			}
			for(; $j >= 13 ; $j--){
				if( $data->sheets[0]['cells'][$i*4+2][$j] ){
					$data->sheets[0]['cells'][$i*4+3][$j] = 0;
				}
			}
			$i++;
		}

		for($i = 1; $i <= $data->sheets[0]['numRows'];$i++){
			for($j = 1; $j <= $data->sheets[0]['numCols'];$j++){
				$out = str_replace("\n","",$data->sheets[0]['cells'][$i][$j]);
				$out2 = str_replace("\t","",$out);
				$out2 .= "\t";
			}
			$out2 .= "\n";
		}
		
		for($i = 1; $i <= $data->sheets[0]['numRows'];$i++){
			for($j = 1; $j <= $data->sheets[0]['numCols'];$j++){
				$out = str_replace("\n","",$data->sheets[0]['cells'][$i][$j]);
				$out2 = str_replace("\t","",$out);
				echo $out2."\t";
			}
			echo "\n";
		}
		
		exit();	
	}
	
	/**
	 * @todo Excel格式数据报表导入
	 * @author bo.wang3
	 * @version 2013-4-22 14:29
	 */
	public function ExcelUploadForm(){
		echo <<<HTMLCODE
			<form action="./?action=SuperVision&do=ExcelProcess" method="POST" enctype="multipart/form-data">
				<input type="file" name="excel" size="20"><input type="submit" value="导入Excel文件">
			</form>
HTMLCODE;
	}
	
	/**
	 * @todo Excel格式数据报表处理
	 * @author bo.wang3
	 * @version 2013-4-22 14:29
	 */
	public function ExcelProcess(){
	
		$data = new Spreadsheet_Excel_Reader($_FILES['excel']['tmp_name']);
		$content = $data->sheets[0]['cells'];
		if(!is_array($content) || $data === false){
			alert('目标文档无法被系统识别，请确认文档类型为XLS，如有疑问请与开发人员联系。');
			$this->ExcelUploadForm();
			exit;
		}
	
		echo "<script type=text/javascript>window.parent.exceldatainput('".json_encode($content)."');</script>";
		$this->ExcelUploadForm();
	}

	/**
	 * @author bo.wang3
	 * @version 2014-1-16 14:29
	 */
	public function EmailInform($func,$key){
		
		$data = json_decode($_SESSION[$key],ture);
	
		switch($_REQUEST['func']){
			case 'Inventory':
	
				$topic = "MADS广告系统数据盘点报告——库存数据盘点";
				$body = <<<HTML
 			<h4>数据统计区间： {$data['bt']}-{$data['et']}</h4>
 			<p>可投视频VV => {$data['vv']}</p>
			<p>库存 => {$data['kc']}</p>
			<p>实投 => {$data['中国']['st_aids']}</p>
			<p>“我秀及内部推广”实投 => {$data['中国']['nb_aids']}</p>
			<p>全国通投 => {$data['中国']['tt_aids']}</p>
			<p>区域定向实投 => {$data['中国']['qy_aids']}</p>
			<p>北上广深实投 => {$data['中国']['dx_aids']}</p>
			<p>品牌合计实投 => {$data['中国']['dx_aids']}</p>
HTML;
				$this->PushEmail($topic, $body, ADMIN);
				break;
			case 'Kcyl_Zy':
				
				$topic = "MADS广告系统数据盘点报告——库存余量、占用盘点";
				$body = <<<HTML
 			<h4>数据统计区间： {$data['bt']}-{$data['et']}</h4>
 			<p>可投视频VV => {$data['vv']}</p>
			<p>总库存 => {$data['kc_str']}</p>
			<p>预定量 => {$data['yd_str']}</p>
			<p>实投量 => {$data['st_str']}</p>
			<p>剩余量 => {$data['yl_str']}</p>
HTML;
				$this->PushEmail($topic, $body, ADMIN);
				
				break;
			case 'Acs':
				break;
		}
	
	}
	
	/**
	 * @author bo.wang3
	 * @version 2014-1-16 14:29
	 */
	public function ShowResult(){
	
		$skey = trim($_GET['skey']);
		$rs = json_decode($_SESSION[$skey],true);
	
		$this->bt = $rs['bt'];
		$this->et = $rs['et'];
		$this->listdata = $rs['data'];
		$this->common = $rs['common'];
	
		switch($_REQUEST['func']){
			case 'Inventory':
				if($_REQUEST['headerType'] != 'xls'){
					$this->show = 'normal';
				}
				include template('inventory',__class__);
			break;
			case 'Xd_Zx_Sj':
				if($_REQUEST['headerType'] != 'xls'){
					$this->show = 'normal';
				}
				include template('xd_zx_sj',__class__);
				break;
			case 'Kcyl_Zy':
				if($_REQUEST['headerType'] != 'xls'){
					$this->show = 'normal';
				}
				include template('kcyl_zy',__class__);
			break;
			case 'Khtf':
				if($_REQUEST['headerType'] != 'xls'){
					$this->show = 'normal';
				}
				include template('khtf',__class__);
			break;
			case 'Wtw':
				if($_REQUEST['headerType'] != 'xls'){
					$this->show = 'normal';
				}
				include template('wtw',__class__);
			break;
			case 'Acs':
				if($_REQUEST['headerType'] == 'xls'){
					include template('report',__class__);
				}else{
					include template('main',__class__);
				}
			break;
			case 'Kcjs':
				if($_REQUEST['headerType'] != 'xls'){
					$this->show = 'normal';
				}
				include template('kcjs',__class__);
			break;
		}
	
	}
}