<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;
}

/**
 * @todo 56广告系统库存下单子系统
 * @author bo.wang3
 * @version 2013-4-22 14:29
 */
Class Order	extends Mads{
	
	public $pagedata;

	public function __Construct(){
		parent::__Construct();
	}

	public function Main(){
				
		$this->Transaction();
	}

	/**
 	 * @todo 合同管理
     * @author bo.wang3
     * @version 2013-4-22 14:29
     */
	public function Contract(){

		if($this->func == 'add' || $this->func == 'edit'){
			/*
 			if(isset($_POST[reserve_code])){
				//判断预订单号是否有效
				$reserve = $this->_db->rsArray("SELECT id FROM ad_reserve WHERE reserve_code = '$_POST[reserve_code]'");
				if(empty($reserve)){
					echo json_encode(array('rs'=>1,'msg'=>'预订单编号不存在，请重新检查！'));exit;
				}
			}
		    */
			//提交数据进行检查
			$_POST['starttime'] = strtotime($_POST['starttime']);
			$_POST['endtime'] = strtotime($_POST['endtime']);
			if($_POST['starttime'] >= $_POST['endtime']){
				echo json_encode(array('rs'=>1,'msg'=>'投放开始时间不得大于等于结束时间。'));exit;
			}

			if(!is_numeric($_POST['unit_price']) || !is_numeric($_POST['total_price']) || !is_numeric($_POST['day_cpm']) || !is_numeric($_POST['total_cpm'])){
				echo json_encode(array('rs'=>1,'msg'=>'价格及CPM参数必须为数字！'));exit;
			}
			
			$mc = json_decode(stripslashes($_POST['monitor_code']),true);
			
			//检查排期表，补齐日期间隔数据
 			if(!empty($mc)){

 			    $mc_header_pre = $mc[1];//补空缺之前的头

 			    //遍历数组构造索引表，检查是否有重复的城市
 			    $city_index = array();
 			    foreach ($mc as $k => $v){
 			        if($k == 1) continue;
 			        if(false == array_key_exists($v[1], $city_index)){
 			            $city_index[$v[1]] = $k;
 			        }else{
 			            echo json_encode(array('rs'=>1,'msg'=>'系统禁止同一城市有多个监测代码，请重新检查排期表！'));
 			            exit;
 			        }
 			    }
 			    
 			    $sch_switch = count($mc_header_pre) > 4?true:false; //排期表开关
 			    
     			if(true === $sch_switch){ //没有排期数据是自动禁用排期表
     				
         			    for($i=(int)$_POST['starttime'];$i<=$_POST['endtime'];$i+=86400){
         			        //收集开始时间到结束时间范围内0点的时间戳
         			        $daytime[] = date('m/d/Y',$i);
         			    }
         			    
         			    //检查是否有不在开始、结束日期内的排期数据
         			    for($i=5;$i<=count($mc_header_pre);$i++){
         			        if(!in_array($mc_header_pre[$i], $daytime)){
         			            echo json_encode(array('rs'=>1,'msg'=>"$mc_header_pre[$i] - 不在投放周期内，请重新检查排期表，例如 01/02/2014)。"));exit;
         			        }
         			    }
         			    
            			//填补日期间隔，空缺的记录填补-1
            			foreach($daytime as $data){
            			    if(!in_array($data, $mc[1])){
            			        $mc[1][] = $data;
            			        for($i=2;$i<=count($mc);$i++){
            			            $mc[$i][] = -1; //默认-1表示不投放
            			        }
            			    }
            			}
            			$_POST['monitor_code'] = addslashes(json_encode($mc));
     			 }
			} 
			
			if($this->func == 'edit'){ //合同发生修改时同时刷新关联订单的监测代码、素材地址、排期表
				
				$ralated_aids = rtrim($_POST[related_aids],',');
				$ralated_aids = explode(',', $ralated_aids);
				$mc_header = $mc[1]; //补空缺之后的头
				
				//遍历相关AID数组，对每个AID进行修改
				foreach($ralated_aids as $aid){
				    
				    if(!empty($aid)){
				        $ad = $this->_db->rsArray("SELECT city From ads WHERE aid = $aid LIMIT 0,1");
				        
				        if(empty($ad['city'])){//投放中国的单
				            $index_id = $city_index['中国'];
				        }else{
				            $index_id = $city_index[$ad['city']];
				        }
				        
				        $common_d['signature'] = SIGNATURE;
				        
				        //修改通用信息
				        if(isset($_POST['sync']['client'])){
				            $common_d['vid'] = $_POST['customer_id'];
				        }
				        if(isset($_POST['sync']['time'])){
				            $common_d['starttime'] = $_POST['starttime'];
				            $common_d['endtime'] = $_POST['endtime'];
				        }
				        if(isset($_POST['sync']['title'])){
				            $common_d['title'] = $_POST['title'];
				        }
				        if(isset($_POST['sync']['des'])){
				            $common_d['description'] = $_POST['description'];
				        }
				        if(isset($_POST['sync']['type'])){
				            $common_d['cid'] = $_POST['ad_type'];
				        }
				        if(isset($_POST['sync']['code'])){
				            $common_d['tp_flv'] = $mc[$index_id][4];
				            $common_d['tp_viewurl'] = $mc[$index_id][2];
				            $common_d['tp_click'] = $mc[$index_id][3];
				            //批量修改排期数据
				            $this->_db->conn("delete from ads_ext where aid = $aid");
				            if(true === $sch_switch){
    				            for($i=5;$i<=count($mc_header);$i++){
    				                $arr = array(
    				                		'aid' => $aid,
    				                        'daytime' => strtotime($mc_header[$i]),
    				                        'cpm' => $mc[$index_id][$i]
    				                        );
    				                $this->_db->insert('ads_ext',$arr);
    				            }
    				            //对当日的CPM值实时进行更新
    				            $date_key = array_search(date('m/d/Y',TIMESTAMP),$mc_header);
    				            if(false !== $date_key){
    				                $common_d['cpm'] = $mc[$index_id][$date_key];
    				            }
				            }
				        }
				        
				        //记录修改之前的值 传递给展示页面进行展示
				        foreach($common_d as $k => $v){
				            $pre = $this->_db->rsArray("SELECT $k FROM ads WHERE aid = $aid LIMIT 0,1");
				            $mdf[] = array($k => $pre[$k]);
				        }
				        
				        $modify_c[] = array(
				                'aid' => $aid,
				                'mdf' => $mdf
				        );
				        
				        unset($mdf);
				        
				        //修改
				        if(!empty($common_d) && true === $this->_db->update('ads',$common_d,"aid = $aid")){
				            //写日志
				            $log = "[修改合同配置] - $_POST[contract_id] 同步 $aid";
				            $username = ADMIN;
				            $detail = json_encode($common_d);
				        
				            $log = $this->WriteLog($username, $log, $aid, $detail);
				            if(false === $log['rs']){
				                $this->_callback_rs = array('rs'=>1,'msg'=>$log['msg']);
				                echo json_encode($this->_callback_rs);
				                exit;
				            }
				        }
				    }
				   
				}
			}
			$modify_c = addslashes(json_encode($modify_c));
			$this->_db->update('ad_contract',array('last_modified' => $modify_c),"id = {$_REQUEST['id']}");
			unset($_POST['sync']);
		}
		
		
		//if(!in_array(ADMIN, array('yang.zhong','limin.pang','bo.wang3'))){
		if(1){
			$wherestr = "";
		}else{
			$wherestr = "";
		}
		
		//预订单->订单快捷下单
		if($_REQUEST['from'] == 'reserve_add'){
			$this->pagedata['editInfo'] = $_GET;
		}
		
		$this->BackendDbLogic($_POST,'ad_contract','contract',$wherestr,' Order BY id DESC,contract_id DESC');  //功能切换、数据、数据表名、模版文件名
	}
	
	
/**
	 * @todo 合同管理V2版本
	 * 新增合同一键推送功能，预订单转换为合同的信息由r_info传入
	 * @author bo.wang3
	 * @version 2013-4-22 14:29
	 */
	public function ContractV2($r_info){
		
		if($this->func == 'add' || $this->func == 'edit'){
			
			//组合入库数组
			foreach($_POST['reserve_code'] as $k => $v){
				$data[] = array(
						'reserve_code' => $v,
						'title' => $_REQUEST['title'],
						'description' => $_REQUEST['description'],
						'contract_id' => $_REQUEST['contract_id'],
						'customer_id' => $_REQUEST['customer_id'],
						'ts' => $_REQUEST['ts'],
						'admin' => $_REQUEST['admin'],
						'id' => $_REQUEST['id'][$k],
						'related_aids' => $_REQUEST['related_aids'][$k],
						'remark' => $_REQUEST['remark'][$k],
						'starttime' => strtotime($_REQUEST['starttime'][$k]),
						'endtime' => strtotime($_REQUEST['endtime'][$k]),
						'ad_type' => $_REQUEST['ad_type'][$k],
						'ad_sub_type' => $_REQUEST['ad_sub_type'][$k],
						'monitor_code' => stripcslashes($_REQUEST['monitor_code'][$k]),
				);
			}
			
			if(empty($data)){
				echo json_encode(array('rs'=>1,'msg'=>"子合同信息不能为空，请重新编辑并提交！"));exit;
			}
			
			//数据有效性检查
			foreach($data as $cindex => $cvalue){
				
				//判断预订单号是否有效
				$rs = $this->_db->rsArray("SELECT id FROM ad_reserve WHERE reserve_code = '$cvalue[reserve_code]'");
				if(empty($rs) && $cvalue['reserve_code'] != '0_Superjc2MDIz'){ //特殊情况下超级预订单号直接进行下单
					echo json_encode(array('rs'=>1,'msg'=>"[{$cvalue['remark']}]-预订单授权编码不正确，请联系资源部同事！"));exit;
				}
				
				//投放周期检查
				if($cvalue['starttime'] >= $cvalue['endtime']){
					echo json_encode(array('rs'=>1,'msg'=>"[{$cvalue['remark']}]-投放开始时间不得大于等于结束时间。"));exit;
				}
				
				//if(!is_numeric($_POST['unit_price']) || !is_numeric($_POST['total_price']) || !is_numeric($_POST['day_cpm']) || !is_numeric($_POST['total_cpm'])){
					//echo json_encode(array('rs'=>1,'msg'=>'价格及CPM参数必须为数字！'));exit;
				//}
				
				$mc = json_decode($cvalue['monitor_code'],true);
				
				//检查排期表，补齐日期间隔数据
				if(!empty($mc)){
					
					//排期表类型检查
					if($mc[1][1] != '投放备注信息' && $this->func == 'add'){
						echo json_encode(array('rs'=>1,'msg'=>"系统不支持当前版本排期表，请下载最新版本排期表。"));exit;
					}
					
					if($mc[1][1] == '投放备注信息'){
						//进行一次预处理 去除掉空的字段
						foreach($mc as $k => $v){
							foreach($v as $h => $t){
								if(empty($t)) unset($mc[$k][$h]);
							}
						}
						
						$mc_header_pre = $mc[1];//补空缺之前的头
						
						$sch_switch = count($mc_header_pre) > 5?true:false; //排期表开关
						
						if(true === $sch_switch){ //没有排期数据是自动禁用排期表
						
							$daytime = pre_dates($cvalue['starttime'], $cvalue['endtime']);
							foreach($daytime as $k => $v){
								$daytime[$k] = date('m/d/Y',strtotime($v));
							}
							
							//检查是否有不在开始、结束日期内的排期数据
							for($i=6;$i<=count($mc_header_pre);$i++){
								if(!in_array($mc_header_pre[$i], $daytime)){
									echo json_encode(array('rs'=>1,'msg'=>"[{$cvalue['remark']}]-($mc_header_pre[$i])不在投放周期内，请重新检查排期表，例如 01/02/2014)。"));exit;
								}
							}
						
							//填补日期间隔，空缺的记录填补-1
							foreach($daytime as $date){
								if(!in_array($date, $mc[1])){
									$mc[1][] = $date;
									for($i=2;$i<=count($mc);$i++){
										$mc[$i][count($mc[1])] = -1; //默认-1表示不投放
									}
								}
							}
						
							$data[$cindex]['monitor_code'] = addslashes(json_encode($mc));
						}
						}
					}
			}
			
			if($this->func == 'edit'){ //合同发生修改时同时刷新关联订单的监测代码、素材地址、排期表
				
				//同步信息
				$syncinfo = $_POST['sync'];
				
				foreach($data as $cindex => $cvalue){
					
					$ralated_aids = rtrim($data[$cindex]['related_aids'],',');
					$ralated_aids = explode(',', $ralated_aids);
					
					$mc = json_decode(stripslashes($cvalue['monitor_code']),true);
					$mc_header = $mc[1]; //补空缺之后的头
					
					//遍历相关AID数组，对每个AID进行修改
					foreach($ralated_aids as $aid){
							
						if(!empty($aid)){
								
							//idf作为识别码实际上是该条数据在排期表当中的位置
							$ad = $this->_db->rsArray("SELECT idf From ads WHERE aid = $aid LIMIT 0,1");

							/*
							if(empty($ad['city'])){ //投放中国的单
								$index_id = $city_index['中国']; //全国通投的合同订单在下单时区域信息留空，在制作排期表时填写中国
							}else{
								$index_id = $city_index[$ad['city']];
							}
							*/
							
							//修改通用信息
							if($syncinfo['client'] == 'on'){
								$common_d['vid'] = $data[$cindex]['customer_id'];
							}
							if($syncinfo['title'] == 'on'){
								$common_d['title'] = $data[$cindex]['title'];
							}
							if($syncinfo['des'] == 'on'){
								$common_d['description'] = $data[$cindex]['description'];
							}
							
							if($syncinfo[$cindex]['time'] == 'on'){
								$common_d['starttime'] = $data[$cindex]['starttime'];
								$common_d['endtime'] = $data[$cindex]['endtime'];
							}
								
							if($syncinfo[$cindex]['type'] == 'on'){
								$common_d['cid'] = $data[$cindex]['ad_type'];
							}
							
							if($syncinfo[$cindex]['ad_sub_type'] == 'on'){
								$common_d['type'] = $data[$cindex]['ad_sub_type'];
							}
							
							//批量修改排期表
							if($syncinfo[$cindex]['code'] == 'on'){
								
								$common_d['city'] = $mc[$ad[idf]][2];
								$common_d['tp_viewurl'] = $mc[$ad[idf]][3];
								$common_d['tp_click'] = $mc[$ad[idf]][4];
								$common_d['tp_flv'] = $mc[$ad[idf]][5];
								
								//批量修改排期数据
								$this->_db->conn("delete from ads_ext where aid = $aid");
				
								if(true === $sch_switch){
									
									$day_cpm = $mc[$ad[idf]][array_search(date('m/d/Y',TIMESTAMP), $mc_header)];
									//对当日的CPM值实时进行更新
									$common_d['cpm'] = $day_cpm?$day_cpm:-1;
										
									for($i=6;$i<=count($mc_header);$i++){
										$arr = array(
												'aid' => $aid,
												'daytime' => strtotime($mc_header[$i]),
												'cpm' => $mc[$ad[idf]][$i]
										);
										$this->_db->insert('ads_ext',$arr);
									}
								}
							}
							
							if(empty($common_d)){
								continue;
							}else{ //如果有发生修改 则生成修改签名、记录修改的内容写入mdf、同步ads表、写入log
								
								//记录修改之前的值 传递给展示页面进行展示
								foreach($common_d as $k => $v){
									$pre = $this->_db->rsArray("SELECT $k FROM ads WHERE aid = $aid LIMIT 0,1");
									$mdf[] = array($k => $pre[$k]);
								}
								
								$modify_c[] = array(
										'aid' => $aid,
										'mdf' => $mdf
								);
									
								unset($mdf);
								
								//主表修改签名
								$common_d['signature'] = gc_sig();
									
								//修改
								if(true === $this->_db->update('ads',$common_d,"aid = $aid")){
									//写日志
									$log = "[修改合同配置] - $_POST[contract_id] 同步  $aid";
									$username = ADMIN;
								
									foreach($common_d as $k => $v){
										$common_d[$k] = urlencode($v);
									}
									$detail = json_encode($common_d);
										
									$log = $this->WriteLog($username, $log, $aid, urldecode($detail));
								
									if(false === $log['rs']){
										echo json_encode(array('rs'=>1,'msg'=>"[{$cvalue['remark']}]- 批量修改广告信息时日志写入失败，请与开发人员联系！"));exit;
									}
								}
								
								unset($common_d['signature']);
							}//if modified
						}//if aid
					}//foreach aids
					//sync modified info
					$databefore= $this->_db->dataArray("SELECT * FROM ad_contract WHERE id = {$cvalue['id']}");
					$this->_db->update('ad_contract',array('last_modified' => addslashes(json_encode($modify_c))),'id = '.$cvalue['id']);
					$this->WriteReserveLog(ADMIN, "[修改合同]", $cvalue['id'], "ad_contract", array('last_modified' => addslashes(json_encode($modify_c)), 'ts' => TIMESTAMP), $databefore, "update","");
				}//foreach ids
			}//if edit
		}//if add or edit
		
		if($this->func == 'search'){
			//搜索配置
			foreach($_GET as $k => $v){
				if(!empty($v) && in_array($k, array('admin','contract_id'))){
					if($k == 'id'){ //ID批量查找
						$wherestr[] = "id IN ($v)";
					}elseif($k == 'area'){
						$wherestr[] = "concat(area,city) LIKE '$v'";
					}elseif(is_numeric($v)){
						$wherestr[] = "$k = '$v'";
					}else{
						$wherestr[] = "$k LIKE '%$v%'";
					}
				}
			}
		
		}else{
			if(in_array(ROLE,'WORKER') && ADMIN != 'yang.zhong'){
				$wherestr[] = "username = '".ADMIN."'";
			}
		}

		//预订单->订单快捷下单
		if(!empty($r_info)){
			$this->show = 'add';
			$this->pagedata['editInfo'] = $r_info;
		}
		
		$this->BackendDbLogicMultiEdit($data,'ad_contract','contract2',implode(' and ', $wherestr),' Order BY ts DESC');  //功能切换、数据、数据表名、模版文件名
	}
	
	/**
	 * @todo 广告下单
	 * @author bo.wang3
	 * @version 2013-4-22 14:29
	 */
	public function Transaction(){
	
		if($this->func == 'add' || $this->func == 'edit'){
			
			//关键字类预处理开始
			$keyword = explode(' ', $_REQUEST['keyword']);
			$excludekeyword = explode(' ', $_REQUEST['excludekeyword']);
			foreach($keyword as $k => $v){
				if(empty($v)){
					unset($keyword[$k]);
				}else{
					$keyword[$k] = trim($v);
				}
			}
			foreach($excludekeyword as $k => $v){
				if(empty($v)){
					unset($excludekeyword[$k]);
				}else{
					$excludekeyword[$k] = trim($v);
				}
			}
			//关键字类预处理结束
				
			$data = array(
					"signature" => SIGNATURE,
					"aid" => $_REQUEST['aid'],
					"name" => '',//弃用
					"title" => $_REQUEST['title'],
					"description" => $_REQUEST['description'],
					"flash" => $_REQUEST['flash'],
					"link" => $_REQUEST['link'],
					"channel" => implode('_', $_REQUEST['channel']),
					"keyword" => implode('_', $keyword),
					"excludekeyword" => implode('_', $excludekeyword),
					"area" => implode('_', $_REQUEST['area']),
					"weight" => $_REQUEST['weight'],
					"freq" => $_REQUEST['freq'],
					"type" => $_REQUEST['type'],
					"starttime" => strtotime($_REQUEST['starttime']),
					"endtime" => strtotime($_REQUEST['endtime']),
					"cpm" => $_REQUEST['cpm'],
					"enable" => $this->func == 'add'?0:$_REQUEST['enable'],
					"cid" => $_REQUEST['cid'],
					"vid" => $_REQUEST['vid'],
					"google" => stripcslashes($_REQUEST['google']),
				    "display_hours" => empty($_REQUEST['display_hours'])?'':','.implode(',',$_REQUEST['display_hours']).',',
					"hour_cpm" => $_REQUEST['hour_cpm'],
					"city" => str_replace(',', '_', $_REQUEST['city']),
					"fee" => $_REQUEST['fee_cpm']> 0 ? ($_REQUEST['fee_cpm'] * $_REQUEST['cpm']):$_REQUEST['fee'],
					"memo" => $_REQUEST['memo'],
					"fee_cpm" => $_REQUEST['fee_cpm'],
					"linkid" => $_REQUEST['linkid'],
					"excludearea" => str_replace(',', '_', $_REQUEST['excludearea']),
					"tp_flv" => $_REQUEST['tp_flv'],
					"tp_viewurl" => $_REQUEST['tp_viewurl'],
					"tp_click" => $_REQUEST['tp_click'],
					"isavg" => isset($_REQUEST['isavg'])?1:0,
					"maxavg" => isset($_REQUEST['maxavg'])?1:0,
					"idf"    => $_REQUEST['idf']
			);
				
			if($this->func == 'add'){
				$data["username"] = ADMIN;
			}
				
			if($this->func == 'add' && ROLE == 'DEVELOPER'){ //系统内部测试的单统一使用该账号
				$data["vid"] = 800;
			}
			
			//判定city和area的合法性
			if(false != strstr($data['city'].$data['area'], '中国') || false != strstr($data['city'].$data['area'], '全国')){
				echo json_encode(array('rs'=>1,'msg'=>'全国通投的广告订单请将自定义区域（城市）字段置为空。'));exit;
			}
				
			//合同绑定判断
			if($this->func == 'add' && empty($_REQUEST['contractid']) && !in_array($data['vid'], array(1,11,144,315,23,230,800))){
				echo json_encode(array('rs'=>1,'msg'=>'合同绑定失败，请检查订单合同项配置是否正确，如仍不能解决请与技术人员联系。'));exit;
			}
	
			//广告位判断
			if(empty($data['cid'])){
				echo json_encode(array('rs'=>1,'msg'=>'广告位设置失败！'));exit;
			}
				
			//时间判断
			if($data['starttime'] >= $data['endtime']){
				echo json_encode(array('rs'=>1,'msg'=>'投放开始时间不得大于等于结束时间。'));exit;
			}

			//未设定优先级时的默认处理
			if(!strstr($data['link'], "level")){
				foreach(Core::$vars['Province'] as $s){
					if(strstr($data['city'],$s)){
						$data['link'] .= "#level_2";
						break;
					};
				}
			}
				
			//如果是修改的话要对比二者修改的状态并记录到admin_log的detail字段
			if($this->func == 'edit'){
				$ad_pre = $this->_db->rsArray("SELECT * FROM ads WHERE aid = ".$data['aid']);
	
				foreach($ad_pre as $k => $v){
					if(!in_array($k,array('add_time','username','fee','fee_cpm','linkid','status')) && $ad_pre[$k] != $data[$k]){
						$ext[$k] = urlencode($data[$k]);
					}
				}
	
				$ext = json_encode($ext);
			}
				
		}
	
		if($this->func == 'search'){
			//搜索配置
			$_REQUEST['starttime'] = strtotime($_REQUEST['starttime']);
			$_REQUEST['endtime'] = strtotime($_REQUEST['endtime']);
	
			foreach($_GET as $k => $v){
				if(!empty($v) && !in_array($k, array('action','do','func','show','page','orderby'))){
					if($k == 'aid'){ //广告ID批量查找
						$wherestr[] = "aid IN ($v)";
					}elseif($k == 'area'){
						$wherestr[] = "concat(area,city) LIKE '$v'";
					}elseif(is_numeric($v)){
						$wherestr[] = "$k = '$v'";
					}else{
						//广告参数特殊处理
						$v_arr = explode('_',$v);
						unset($v);
						foreach($v_arr as $t){
							$v .= "%$t";
						}
						 
						$wherestr[] = "$k LIKE '%$v%'";
					}
				}
			}
			$pagesize = 250; //搜索的结果尽可能在一页显示完毕
	
		}else{
			if(ROLE != 'DEVELOPER'){
				$wherestr[] = "username = '".ADMIN."'";
			}
			$pagesize = 50;
		}
	
		/*刷新订单状态
		 * 3 正在投放 enable = 1 且 ts bet starttime,endtime
		* 4 预定中 enable = 1 且 ts < starttime
		* 2 停止中 enable = 0
		* 1 投放结束 enable = 1 且 ts > endtime
		* */
		$this->_db->conn('UPDATE ads SET status = 3 WHERE enable = 1 and ('.TIMESTAMP.' BETWEEN starttime and endtime)');
		$this->_db->conn('UPDATE ads SET status = 4 WHERE enable = 1 and starttime > '.TIMESTAMP);
		$this->_db->conn('UPDATE ads SET status = 2 WHERE enable = 0');
		$this->_db->conn('UPDATE ads SET status = 1 WHERE enable = 1 and endtime < '.TIMESTAMP);
	
		if($this->func == 'search'){
			$orderstr = $_REQUEST['orderby']?" ORDER BY $_REQUEST[orderby] DESC":" ORDER BY status DESC,aid ASC";
		}else{
			$orderstr = $_REQUEST['orderby']?" ORDER BY $_REQUEST[orderby] DESC":" ORDER BY aid DESC";
		}
	
		if($this->show == 'edit'){
				
			$aid = trim($_REQUEST['aid']);
				
			//读取操作记录
			$tfs = "'chuwen.shan','system'";
			$this->pagedata['logs'] = $this->_db->dataArray("SELECT * FROM admin_log WHERE aid = {$aid} and username NOT IN ($tfs) ORDER BY logtime DESC");
				
			//需要展示的日志维度
			$nd_shows = array('vid','title','description','tp_click','tp_viewurl','tp_flv','endtime','starttime','weight','cid','type','cpm','flash','link','keyword','excludekeyword','area','excludearea','enable');
				
			foreach($this->pagedata['logs'] as $k => $v){
				$this->pagedata['logs'][$k]['detail'] = json_decode($v['detail'],true);
	
				foreach($nd_shows as $nd_show){
					if(array_key_exists($nd_show, $this->pagedata['logs'][$k]['detail'])) continue 2;
				}
	
				unset($this->pagedata['logs'][$k]);
			}
				
			//读取合同信息
			$this->cinfo = $this->_db->rsArray("SELECT id,contract_id,remark FROM ad_contract WHERE related_aids LIKE '%,$aid,%' OR related_aids LIKE '$aid,%' limit 0,1");
			if(!empty($this->cinfo)){
				$this->cinfo['cid'] = $this->cinfo['id'];
				$this->cinfo['ctc'] = $this->cinfo['remark'].'('.$this->cinfo['contract_id'].')';
			}
		}
	
		$this->BackendDbLogic($data,'ads','transaction',implode(' and ', $wherestr),$orderstr,$pagesize,$ext); //功能切换、数据、数据表名、模版文件名
	}
	
	/**
	 * @todo 输出多城市监测代码
	 * @author bo.wang3
	 * @version 2013-4-22 14:29
	 */
	public function OutPutMultiCitys(){
	
		$rs = $this->_db->rsArray("SELECT monitor_code from ad_contract where id = $_REQUEST[id] limit 1");
	
		if(!empty($rs)){
			$monitor_code = json_decode($rs['monitor_code'],true);
			echo '<table style="border:1px solid">';
			foreach($monitor_code as $data0){
				echo '<tr>';
				foreach($data0 as $data1){
					echo "<td>$data1</td>";
				}
				echo '</tr>';
			}
			echo '</table>';
		}else{
			exit('参数配置不正确。');
		}
	
	}
	
	
	/**
	 * @todo 通过预订单直接下单
	 * @author bo.wang3
	 * @version 2013-4-22 14:29
	 */
	public function FromReserveToContract(){
		
		$reserve_id = trim($_REQUEST['reserve_id']);
		$rss = $this->_db->dataArray("SELECT client,brand,agent,cid,type,starttime,endtime,reserve_code FROM ad_reserve WHERE reserve_id = '$reserve_id'");
		
		//检查是否已经下合同了
		foreach($rss as $k => $v){
			
			$ck = $this->_db->rsArray("SELECT id FROM ad_contract WHERE reserve_code = '$v[reserve_code]'");
			if(!empty($ck)){
				go_win(2,'该预订单对应信息已有相关联的合同！');
				exit;
			}
			
			$rss[$k]['customer_id'] = $v['client'];
			$rss[$k]['ad_type'] = $v['cid'];
			$rss[$k]['ad_sub_type'] = $v['type'];
			$rss[$k]['title'] = "$v[brand]_$v[agent]_".date('Y.m.d',$v[starttime])."-".date('Y.m.d',$v[endtime]);
			$rss[$k]['description'] = "预订单系统自动下单";
			
			unset($rss[$k]['client']);
			unset($rss[$k]['cid']);
			unset($rss[$k]['type']);
			unset($rss[$k]['agent']);
			unset($rss[$k]['brand']);
			
		}
		
		$this->ContractV2($rss);
	}
	
	
	/**
	 * @todo 合同对应订单概览
	 * @author bo.wang3
	 * @version 2013-4-22 14:29
	 */
	public function Contract2Aid(){
		
	    $id = trim($_REQUEST['id']);
	    $rs = $this->_db->RsArray("SELECT contract_id,related_aids,last_modified FROM ad_contract WHERE id = '$id'");
	    
	    if(empty($rs['related_aids'])){
	    	go_win(2,'没有相关的广告单与该合同绑定！');
	        exit;
	    }
	    
	    $related_aids = rtrim($rs['related_aids'],',');
	    $related_aids = explode(',',$related_aids);
	    $this->pagedata['contract_id'] = $rs['contract_id'];
	    $this->pagedata['last_modified'] = json_decode($rs['last_modified'],true);
	    
	    foreach($this->pagedata['last_modified'] as $k => $v){
	        $this->pagedata['last_m'][$v['aid']] = $v['mdf'];
	    }
	    
	    foreach($related_aids as $aid){
	        $this->pagedata['list'][] =  $this->_db->RsArray("SELECT * FROM ads WHERE aid = $aid LIMIT 1");
	    }
	    
	    include Template('contract2aid','Order');
	}

	/**
 	 * @todo 合同与订单关联 下单的时候修改合同表
     * @author bo.wang3
     * @version 2013-4-22 14:29
     */
	public function Aid2Contract(){

		$cid = trim($_REQUEST['cid']);
		$aid = trim($_REQUEST['aid']);

		$rs = $this->_db->RsArray("SELECT related_aids FROM ad_contract WHERE id = '$cid'");
		$related_aids = $rs['related_aids']."$aid,";
		$databefore = $this->_db->dataArray("SELECT * FROM ad_contract WHERE id = $cid");
		$this->_db->conn("UPDATE ad_contract SET related_aids = '$related_aids' WHERE id = '$cid'");
		$this->WriteReserveLog(ADMIN, "[下单修改合同]", $cid, "ad_contract", array('related_aids'=> $related_aids, 'ts' => TIMESTAMP), $databefore, "update","");
	}
	
	/**
	 * @todo 统计页面
	 * @author bo.wang3
	 * @version 2013-4-22 14:29
	 * @param
	 */
	public function StatisticsPage(){

		$aid = trim($_REQUEST['aid']);
		
		$total = $this->_db->rsArray("SELECT count(*) as totals FROM ad_log WHERE aid = {$aid}");
		$total = ceil($total['totals']/24);

		$ts = TIMESTAMP;
		
		//时间参数
		$y  = !empty($_REQUEST['y'])?intval($_REQUEST['y']):date('Y',$ts);
		$m  = !empty($_REQUEST['m'])?intval($_REQUEST['m']):date('n',$ts);
		$d  = !empty($_REQUEST['d'])?intval($_REQUEST['d']):date('j',$ts);
		$h  = !empty($_REQUEST['h'])?intval($_REQUEST['h']):date('G',$ts);
		
		$y_y  = date('Y',($ts-86400));
		$y_m  = date('n',($ts-86400));
		$y_d  = date('j',($ts-86400));
		
		$this->pagedata = array();
		$this->pagedata = $this->_db->rsArray("SELECT sum(view) as total_views FROM ad_log WHERE aid = {$aid}");
		$this->pagedata += $this->_db->rsArray("SELECT sum(click) as total_clicks FROM ad_log WHERE aid = {$aid}");
		$this->pagedata += $this->_db->rsArray("SELECT sum(viewip) as total_viewips FROM ad_log WHERE aid = {$aid}");
		
		$this->pagedata += $this->_db->rsArray("SELECT sum(view) as today_views FROM ad_log WHERE aid = {$aid} AND year = {$y} AND month = {$m} AND day = {$d}");
		$this->pagedata += $this->_db->rsArray("SELECT sum(click) as today_clicks FROM ad_log WHERE aid = {$aid} AND year = {$y} AND month = {$m} AND day = {$d}");
		$this->pagedata += $this->_db->rsArray("SELECT sum(viewip) as today_viewips FROM ad_log WHERE aid = {$aid} AND year = {$y} AND month = {$m} AND day = {$d}");

		$this->pagedata += $this->_db->rsArray("SELECT sum(view) as yesterday_views FROM ad_log WHERE aid = {$aid} AND year = {$y_y} AND month = {$y_m} AND day = {$y_d}");
		$this->pagedata += $this->_db->rsArray("SELECT sum(click) as yesterday_clicks FROM ad_log WHERE aid = {$aid} AND year = {$y_y} AND month = {$y_m} AND day = {$y_d}");
		$this->pagedata += $this->_db->rsArray("SELECT sum(viewip) as yesterday_viewips FROM ad_log WHERE aid = {$aid} AND year = {$y_y} AND month = {$y_m} AND day = {$y_d}");

		$this->pagedata += $this->_db->rsArray("SELECT sum(view) as month_views FROM ad_log WHERE aid = {$aid} AND year = {$y} AND month = {$m}");
		$this->pagedata += $this->_db->rsArray("SELECT sum(click) as month_clicks FROM ad_log WHERE aid = {$aid} AND year = {$y} AND month = {$m}");
		$this->pagedata += $this->_db->rsArray("SELECT sum(viewip) as month_viewips FROM ad_log WHERE aid = {$aid} AND year = {$y} AND month = {$m}");
		
		$this->pagedata += $this->_db->rsArray("SELECT sum(view) as y_h_views FROM ad_log WHERE aid = {$aid} AND year = {$y_y} AND month = {$y_m} AND day = {$y_d} AND hour <= {$h}");
		$this->pagedata += $this->_db->rsArray("SELECT sum(click) as y_h_clicks FROM ad_log WHERE aid = {$aid} AND year = {$y_y} AND month = {$y_m} AND day = {$y_d} AND hour <= {$h}");
		$this->pagedata += $this->_db->rsArray("SELECT sum(viewip) as y_h_viewips FROM ad_log WHERE aid = {$aid} AND year = {$y_y} AND month = {$y_m} AND day = {$y_d} AND hour <= {$h}");
		
		$this->pagedata['pre_views'] = $this->pagedata['yesterday_views']*$this->pagedata['today_views']/$this->pagedata['y_h_views'];
		$this->pagedata['pre_clicks'] = $this->pagedata['yesterday_clicks']*$this->pagedata['today_clicks']/$this->pagedata['y_h_clicks'];
		$this->pagedata['pre_viewips'] = $this->pagedata['yesterday_viewips']*$this->pagedata['today_viewips']/$this->pagedata['y_h_viewips'];
		
		$this->pagedata['avg_views'] = $this->pagedata['total_views']/$total;
		$this->pagedata['avg_clicks'] = $this->pagedata['total_clicks']/$total;
		$this->pagedata['avg_viewips'] = $this->pagedata['total_viewips']/$total;
		
		foreach($this->pagedata as $k=>$v){
			$this->pagedata[$k] = $v?intval($v):0;
		}
		
		$rs = $this->_db->dataArray("SELECT hour,view FROM ad_log WHERE aid = {$aid} AND year = {$y} AND month = {$m} AND day = {$d}");
		foreach($rs as $data){
			$rc[$data['hour']] = (int)$data['view'];
		}
		for($i=0;$i<24;$i++){
			$this->pagedata['view_str'] .= $rc[$i]?$rc[$i].',':'0,';
		}
		unset($rc);
		
		$rs = $this->_db->dataArray("SELECT hour,click FROM ad_log WHERE aid = {$aid} AND year = {$y} AND month = {$m} AND day = {$d}");
		foreach($rs as $data){
			$rc[$data['hour']] = (int)$data['click'];
		}
		for($i=0;$i<24;$i++){
			$this->pagedata['click_str'] .= $rc[$i]?$rc[$i].',':'0,';
		}
		unset($rc);
		
		$rs = $this->_db->dataArray("SELECT hour,viewip FROM ad_log WHERE aid = {$aid} AND year = {$y} AND month = {$m} AND day = {$d}");
		foreach($rs as $data){
			$rc[$data['hour']] = (int)$data['viewip'];
		}
		for($i=0;$i<24;$i++){
			$this->pagedata['viewip_str'] .= $rc[$i]?$rc[$i].',':'0,';
		}
		unset($rc);
		
		$this->pagedata['total_per_view'] = $this->pagedata['total_views']/$this->pagedata['total_viewips'];
		$this->pagedata['total_per_click'] = $this->pagedata['total_clicks']/$this->pagedata['total_viewips'];
		
		$this->pagedata['today_per_view'] = $this->pagedata['today_views']/$this->pagedata['today_viewips'];
		$this->pagedata['today_per_click'] = $this->pagedata['today_clicks']/$this->pagedata['today_viewips'];
		
		$this->pagedata['month_per_view'] = $this->pagedata['month_views']/$this->pagedata['month_viewips'];
		$this->pagedata['month_per_click'] = $this->pagedata['month_clicks']/$this->pagedata['month_viewips'];
		
		$this->pagedata['yesterday_per_view'] = $this->pagedata['yesterday_views']/$this->pagedata['yesterday_viewips'];
		$this->pagedata['yesterday_per_click'] = $this->pagedata['yesterday_clicks']/$this->pagedata['yesterday_viewips'];
		
		$this->pagedata['avg_per_view'] = $this->pagedata['avg_views']/$this->pagedata['avg_viewips'];
		$this->pagedata['avg_per_click'] = $this->pagedata['avg_clicks']/$this->pagedata['avg_viewips'];
		
		$this->pagedata['pre_per_view'] = $this->pagedata['pre_views']/$this->pagedata['pre_viewips'];
		$this->pagedata['pre_per_click'] = $this->pagedata['pre_clicks']/$this->pagedata['pre_viewips'];
		
		$tc = $_GET['tc']?$_GET['tc']:1;
		$num = 10*$tc;
		
		$this->pagedata['detail'] = $this->_db->dataArray("SELECT year,month,day,sum(view) as views,sum(click) as clicks,sum(viewip) as viewips FROM ad_log WHERE aid = {$aid} GROUP BY year,month,day ORDER BY stime DESC LIMIT 0,$num");
		$this->pagedata['detail'] = array_reverse($this->pagedata['detail']);
		
		include Template('statistics','Order');
	}
	
	/**
	 * @todo 获得当日24H统计信息
	 * @author bo.wang3
	 * @version 2013-4-22 14:29
	 * @param
	 */
	public function GetDailyInfo(){
	
		$aid = trim($_REQUEST['aid']);
		$y = trim($_REQUEST['year']);
		$m = trim($_REQUEST['month']);
		$d = trim($_REQUEST['day']);
	
		for($i=0;$i<24;$i++){
			
			$rc = $this->_db->rsArray("SELECT hour,view,click,viewip FROM ad_log WHERE aid = {$aid} AND year = {$y} AND month = {$m} AND day = {$d} AND hour = $i");
			
			$str['view_str'] .= $rc['view']?(int)$rc['view'].',':'0,';
			$str['click_str'] .= $rc['click']?(int)$rc['click'].',':'0,';
			$str['viewip_str'] .= $rc['viewip']?(int)$rc['viewip'].',':'0,';
		}
		
		echo json_encode($str);
	}
	
	/**
	 * @todo 获得合同信息
	 * @author bo.wang3
	 * @version 2013-4-22 14:29
	 * @param
	 */
	public function GetContractInfo(){
		
		$id = trim($_REQUEST['id']);
		$col = trim($_REQUEST['col']);
		
		$rs = $this->_db->rsArray("SELECT {$col} FROM ad_contract WHERE id = {$id}");
		echo json_encode($rs);
	}

	/**
 	 * @todo 多城市监测代码导入
     * @author bo.wang3
     * @version 2013-4-22 14:29
     */
	public function MultiCitysUploadForm(){
		echo <<<HTMLCODE
			<form action="./?action=Order&do=MultiCitysProcess&for_id={$_REQUEST[for_id]}" method="POST" enctype="multipart/form-data">
				<input type="file" name="monitor_code" size="20"><input type="submit" value="导入Excel文件">
			</form>
HTMLCODE;
	}

	/**
 	 * @todo 多城市监测代码处理
     * @author bo.wang3
     * @version 2014-8-22 14:29
     */
	public function MultiCitysProcess(){

		$content = csv2array($_FILES['monitor_code']['tmp_name']);
		$file_name = $_FILES['monitor_code']['name'];
		
		if(false == $content || $content[1][1] != '投放备注信息'){ 
			
			//同时兼容XLS和CVS两种格式的文档导入
			$data = new Spreadsheet_Excel_Reader($_FILES['monitor_code']['tmp_name']);
			$content = $data->sheets[0]['cells'];
			
			if(!is_array($content) || $data === false){
				alert('目标文档无法被系统识别，请确认文档类型为XLS，如有疑问请与开发人员联系。');
				$this->MultiCitysUploadForm();
				exit;
			}
			
		}else{
			
			//Spreadsheet_Excel_Reader识别的Excel下标是从1开始的 需要csv兼容一下
			//!!!改新的不要改旧的，注意向上兼容
			$file_extension = 'csv';
			for($i = 6;$i <= count($content[1]);$i++){
				$content[1][$i] = date('m/d/Y',strtotime($content[1][$i]));
			}
		}
		
		//预处理一下
		foreach ($content as $k => $v){
			foreach($v as $m => $t){
				if($m > 5){
					if(strlen($t) == 0){ //把空排期干掉
						unset($content[$k][$m]);
						continue;
					}
					if($t == 0){
						alert("执行排期禁止单日投放设定为0，请修改！$k-$m");
						$this->MultiCitysUploadForm();
						exit;
					}
				}
			}
		}
		
		//原始文档入库 对文件名稍作处理
		$file_name = "[".date('Y-m-d-H-i-s',TIMESTAMP)."] ".preg_replace('/\[|\]/', '', str_replace(get_mid_chars($file_name,'[', ']'), '', $file_name));
		$fid = $this->PutFileIntoDb($_FILES['monitor_code']['tmp_name'], 'schedule', $file_name , $_REQUEST['for_id'],$file_extension == 'csv'?'csv':'xls');
		
		$content = json_encode($content);
		
		echo "<script type=text/javascript>window.parent.multicitysinput('{$_REQUEST[for_id]}','{$content}');</script>";
		echo <<<HTMLCODE
			<form action="./?action=Order&do=MultiCitysProcess&for_id={$_REQUEST['for_id']}" method="POST" enctype="multipart/form-data">
				<input type="file" name="monitor_code" size="20"><input type="submit" value="重新导入"> <font style="font-size:9.5px;" color="green">[排期表数据识别成功]</font>
			</form>
HTMLCODE;
	}
	
	/**
 	 * @todo 投放排期表导入
     * @author bo.wang3
     * @version 2013-7-8 14:29
     */
	public function ScheduleUploadForm(){
		echo <<<HTMLCODE
			<form action="./?action=Order&do=ScheduleProcess" method="POST" enctype="multipart/form-data">
				<input type="file" name="schedule" size="20"><input type="submit" value="导入Excel文件">
			</form>
HTMLCODE;
	}

	/**
 	 * @todo 投放排期表处理
     * @author bo.wang3
     * @version 2013-4-22 14:29
     */
	public function ScheduleProcess(){

		//解析文档
		$data = new Spreadsheet_Excel_Reader($_FILES['schedule']['tmp_name']);
		$content = $data->sheets[0]['cells'];
		if(!is_array($content)){
			alert('目标文档无法被系统识别，请确认文档类型为XLS，如有疑问请与开发人员联系。');
			exit;
		}
		
		unset($content[1]);
		foreach ($content as $data){
			$sd[$data[1]] = $data[2];
		}
		
		echo "<script type=text/javascript>window.parent.scheduleupload('".json_encode(iconv('gbk', 'utf8', $sd))."');</script>";
		echo <<<HTMLCODE
			<form action="./?action=Order&do=ScheduleProcess" method="POST" enctype="multipart/form-data">
				<input type="file" name="schedule" size="20"><input type="submit" value="重新导入"> <font color="green">{$_FILES[monitor_code][name]} - 导入成功！</font>
			</form>
HTMLCODE;
	}

	/**
 	 * @todo FLV文件上传
     * @author bo.wang3
     * @version 2013-6-25 14:29
     */
	public function FlvUploadForm(){
		echo <<<HTMLCODE
			<form action="./?action=Order&do=FlvProcess" method="POST" enctype="multipart/form-data">
				<select name="type">
				    <option value="flv" selected="">​FLV格式 [PC端广告]​</option>
					<option value="jpg">​JPG格式 [BANNER广告]</option>
					<option value="swf">​SWF格式 [BANNER广告]</option>
				    <option value="mp4">​MP4格式 [移动端广告]</option>
					<option value="html">HTML格式</option>
				</select>
				<input type="file" name="flvurl" size="20"><input type="submit" value="上传文件">
			</form>
HTMLCODE;
	}

	/**
 	 * @todo FLV文件处理
     * @author bo.wang3
     * @version 2013-6-25 14:29
     */
	public function FlvProcess(){

		$host = '180.153.21.102';
		$port = '17012';
		$user = 'yumu.yuan';
		$pass = 'P3QXerEqeJ';

		$http_file_dir  = $_FILES['flvurl']['tmp_name'];
		
		if(empty($http_file_dir)){
			alert('不能输入空文件！');
			$this->FlvUploadForm();
			exit;
		}

		$ftp_file_dir = '/tuiguang/'.date('Ym').'/';
		$ftp_file = md5($_FILES['flvurl']['name'].TIMESTAMP).'.'.$_REQUEST['type'];

		$conn = ftp_connect($host,$port) or die("FTP connect error!");

		ftp_login($conn,$user,$pass);

		ftp_pasv ( $conn , true ); // 开启FTP的被动模式，避免防火墙的干扰
		while(false == ftp_put ( $conn , $ftp_file_dir.$ftp_file , $http_file_dir , FTP_BINARY )){
			ftp_mkdir( $conn , $ftp_file_dir);
		}

		ftp_close($conn);
		
		if(in_array($_REQUEST['type'], array("jpg","swf","html"))){
			$http_url = 'http://cdn.56imgs.com/images'.$ftp_file_dir.$ftp_file;
		}else{
			$http_url = 'http://cdn1.56imgs.com/images'.$ftp_file_dir.$ftp_file;
		}

		echo "<script type=text/javascript>window.parent.flvupload('$http_url');</script>";
		$this->FlvUploadForm();
	}

	/**
 	 * @todo 输出排期表 
     * @author bo.wang3
     * @version 2013-4-22 14:29
     */
	public function Schedule(){

		if(isset($_REQUEST['aid'])){ //通过aid显示排期表
		    
		    $aid = trim($_REQUEST['aid']);

		    if(empty($aid)) exit;
		  
		    $data = $this->_db->dataArray( "select daytime,cpm from ads_ext where aid = '$aid'" );
		    if(empty($data)){
		        exit('该广告订单未使用排期表方式管理CPM量数据或排期表已失效。');
		    }else{
		        foreach ($data as $v){
		            $rs[$v[daytime]] = $v[cpm];
		        }
		    }
		    
		}elseif(isset($_REQUEST['msg'])){  //通过排期字符串显示排期表
			
			$_REQUEST['msg'] = stripcslashes($_REQUEST['msg']);
			$msg = json_decode($_REQUEST['msg'],true);

			foreach($msg as $k => $v){
				$tmp['daytime'] = strtotime($k);
				$tmp['cpm'] = $v;
				$data[] = $tmp;
			}
			
			foreach($data as $val){
				$rs[$val[daytime]] = $val[cpm];  //rs为最终的数组
			}
		}
		
		if(empty($data)) die;

		$starttime =  $data[0][daytime];//开始时间
		$endtime = $data[(count($data)-1)][daytime];//结束时间
		
		$month_arr = array();
		
		for($i = $starttime; $i <= $endtime; $i+= 86400){
		    $dateinfo = array('y' => date('Y',$i),'m' => date('n',$i));
		    if(!in_array($dateinfo, $month_arr)){
		        $month_arr[] = $dateinfo;
		    }
		}
		
		include template('schedule','Order');
	}
	
	/**
	 * @todo 广告投放预览
	 * @author bo.wang3
	 * @version 2013-6-25 14:29
	 */
	public function AdPreview(){

		$aid = trim($_REQUEST['aid']);
		$rs = $this->_db->rsArray( "select * from ads where aid='$aid'" );
		
		if(empty($rs)){
			exit('无此广告！');
		}

		if(in_array($rs['cid'], array(111,114,137,170,209,210,211,212,213,166,167))){

			if($rs['cid'] == 213){
				//生成贴片预览XML
				$xmlstring = <<<XML
<?xml version="1.0" encoding="GB2312"?>
<client corplist=",rison," tick_sec_lim="on" ipb="广东省广州市bipb_of-113.107.234.109" time="1" area="广东省广州市bipb_of-113.107.234.109">
	<ad>
		<aid>1</aid>
		<flash>$rs[flash]</flash>
		<link>$rs[link]</link>
		<starttime>0</starttime>
		<endtime>$rs[endtime]</endtime>
		<channel></channel>
		<keyword></keyword>
		<type>1</type>
		<area></area>
		<tp_flv>$rs[tp_flv]</tp_flv>
		<tp_viewurl><![CDATA[$rs[tp_viewurl]]]></tp_viewurl>
		<tp_click><![CDATA[$rs[tp_click]]]></tp_click>
	</ad>
	<ad>
		<aid>2</aid>
		<flash>$rs[flash]</flash>
		<link>$rs[link]#show_60_10_60</link>
		<starttime>0</starttime>
		<endtime>$rs[endtime]</endtime>
		<channel></channel>
		<keyword></keyword>
		<type>2</type>
		<area></area>
		<tp_flv>$rs[tp_flv]</tp_flv>
		<tp_viewurl><![CDATA[$rs[tp_viewurl]]]></tp_viewurl>
		<tp_click><![CDATA[$rs[tp_click]]]></tp_click>
	</ad>
	<ad>
		<aid>3</aid>
		<flash>$rs[flash]</flash>
		<link>$rs[link]</link>
		<starttime>0</starttime>
		<endtime>$rs[endtime]</endtime>
		<channel></channel>
		<keyword></keyword>
		<type>3</type>
		<area></area>
		<tp_flv>$rs[tp_flv]</tp_flv>
		<tp_viewurl><![CDATA[$rs[tp_viewurl]]]></tp_viewurl>
		<tp_click><![CDATA[$rs[tp_click]]]></tp_click>
	</ad>
</client>
XML;
			}else{
				//生成贴片预览XML
				$xmlstring = <<<XML
<?xml version="1.0" encoding="GB2312"?>
<client corplist=",rison," tick_sec_lim="on" ipb="广东省广州市bipb_of-113.107.234.109" time="1" area="广东省广州市bipb_of-113.107.234.109">
	<ad>
		<aid>$rs[aid]</aid>
		<flash>$rs[flash]</flash>
		<link>http://acs.56.com/redirect/click/$rs[aid]/$rs[link]</link>
		<starttime>0</starttime>
		<endtime>$rs[endtime]</endtime>
		<channel></channel>
		<keyword></keyword>
		<type>$rs[type]</type>
		<area></area>
		<tp_flv>$rs[tp_flv]</tp_flv>
		<tp_viewurl><![CDATA[$rs[tp_viewurl]]]></tp_viewurl>
		<tp_click><![CDATA[$rs[tp_click]]]></tp_click>
	</ad>
</client>
XML;
			}

			file_put_contents("../view_ad/$aid.xml",iconv("UTF-8","GB2312//IGNORE",$xmlstring));
		
			$acs_id = HOSTNAME == 'mads.56.com'?'mads_'.$aid:'t3_'.$aid;
			$this->bodycode = <<<HTMLCODE
		<div id="show_ct" style="margin:25px auto auto 50px; text-align:center; ">
			<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="600" height="485" id="v_player_site_acs_test" align="middle">
				<param name="allowScriptAccess" value="always" />
				<param name="allowNetworking" value="all" />
				<param name="allowFullScreen" value="true" />
				<param name="movie" value="http://player.56.com/v_MTI1ODc0MzY3.swf" />
				<param name="flashvars" value="&vid=MTI1ODc0MzY3&acs_id=$acs_id&" />
				<embed src="http://player.56.com/v_MTI1ODc0MzY3.swf" width="600" height="485" name="http://www.56.com/flashApp/v_player_site_acs_test" flashvars="&vid=MTI1ODc0MzY3&acs_id=$acs_id&" align="middle" allowScriptAccess="always" allowNetworking="all" allowFullScreen="true" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
			</object>
		</div>
HTMLCODE;
		}else{
			
 			if(empty($rs[flash]) && empty($rs[link])){
 				
 				$ctc = json_decode($rs[google],true);
 				$ctc[img_url] = str_replace("http://acs.56.com/redirect/view/$_REQUEST[aid]/",'',$ctc[img_url]);
 				
 				if(is_array($ctc)){
 					
 					$this->bodycode = <<<HTMLCODE
		<div id="show_ct" style="margin:20px auto auto 10px; text-align:center; ">
			<a href="{$ctc[img_link]}" target="_blank"><img src="{$ctc[img_url]}" /></a></br>
			主标题：<a href="{$ctc[title_link]}" target="_blank">{$ctc[title]}</a></br>
			副标题：<a href="{$ctc[sub_title_link]}" target="_blank">{$ctc[sub_title]}</a></br>
			播放数:{$ctc[views]}     评论数:{$ctc[comments]}
		</div>
HTMLCODE;

 				}else{
 					
 					$this->bodycode = <<<HTMLCODE
		<script type="text/javascript" src="http://180.153.21.77/ac/acs56.js"></script>
		<script type="text/javascript" src="http://180.153.21.77/ac/ac_$rs[cid].js"></script>
		<div id="show_ct" style="margin:5px auto auto 10px; text-align:center; ">
			<div id="cid_169_pre" class="banner120x72"></div>
		</div>
		$rs[google]
HTMLCODE;
 					
 				}
			} 
			
			if(strstr($rs[flash],'jpg') || strstr($rs[flash],'gif') || strstr($rs[flash],'png')){
				$this->bodycode = <<<HTMLCODE
				<div id="show_ct" style="margin:40px auto auto auto; text-align:center; ">	
					<p><a href="$rs[link]" target="_blank"><img src="$rs[flash]" border="0"></a></p>
					<p style="font-size:30px;font-weight:bold"><a href="$rs[link]" target="_blank">$rs[title]</a></p>
				</div>
HTMLCODE;
			}
			
			if(strstr($rs[flash],'swf')){
				$this->bodycode = <<<HTMLCODE
			<div id="show_ct" style="margin:200px auto auto auto; text-align:center; ">	
				<a href="$rs[link]" target="_blank">
					<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="980" height="100" align="middle">
						<param name="allowScriptAccess" value="always">
						<param name="allowNetworking" value="all">
						<param name="wmode" value="opaque">
						<param name="allowFullScreen" value="true">
						<param name="movie" value="$rs[flash]">
						<embed src="$rs[flash]" quality="high" wmode="opaque" align="middle" allowscriptaccess="sameDomain" width="95%" height="100%" type="application/x-shockwave-flash">
					</object>
				</a>
			</div>	
HTMLCODE;
			}
		}
		
		include template('preview','Order');
	}
	
	/**
	 * @todo 生成合同编号
	 * @author bo.wang3
	 * @version 2013-6-25 14:29
	 */
	public function GcContractId(){
	
		$ids = $this->_db->dataArray("SELECT id FROM ad_contract");
		
		foreach($ids as $id){
			$cid = "S-AD".TIMESTAMP.makerandom(2)."_Auto";
			$databefore = $this->_db->dataArray("SELECT * FROM ad_contract WHERE id = $id[id]");
			$this->_db->update('ad_contract',array('contract_id' => $cid),"id = {$id['id']}");
			$this->WriteReserveLog(ADMIN,"[生成合同编号]", $id['id'], "ad_contract", array('contract_id' => $cid, 'ts' => TIMESTAMP),$databefore, "update","");
		}
		
	}
	
	/**
	 * @todo 获取子合同信息设定面板
	 * @author bo.wang3
	 * @version 2013-6-25 14:29
	 */
	public function GetSubContractPanel($cid){
		
		$rd = makerandom(6); //区分多表单;
	
		$htmlcode = <<<HTMLCODE
	 	<tr>
			<td colspan="2">子合同<a href="javascript:;" onclick="$(this).parent().parent().remove();"><strong>[-]</strong></a></td>
			<td style="font-size:9px;padding:12px">
				授权编码
				<input type="text" name="reserve_code[]" size="40" value="0_Superjc2MDIz">
				
				<hr>签注信息
				<input type="text" name="remark[]" size="120" style="font-weight: bolder;font-style: italic;" value="">
				
				<hr>投放周期
				<input type="text" class="input_Calendar" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',highLineWeekDay:true,readonly:true})" name="starttime[]" size="30" value=""> - <input type="text" class="input_Calendar" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',highLineWeekDay:true,readonly:true})" name="endtime[]" size="30"  value="">
				
				<hr>投放位置
				<select name="ad_type[]" class="cid" id="cid_{$rd}" from_cid_id="cid_{$rd}" for_type_id="type_{$rd}">
HTMLCODE;
		
			foreach($this->adstype as $k => $v){ 
				$htmlcode .= in_array($k,array($this->pagedata['editInfo'][0]['ad_type']))?"<option value ='$k' selected>$v</option>":"<option value ='$k'>$v</option>";   
			} 
			
			$htmlcode .= <<<HTMLCODE
				</select>
				<input class="adtype_quicksearch" for_id="cid_{$rd}" id="cqk_{$rd}" type="text" value="快速翻查" onclick="this.value=''" onblur="this.value='快速翻查'" size="10"> 
				
				<hr>投放类型
				<select name="ad_sub_type[]" class="type" id="type_{$rd}" from_cid_id="cid_{$rd}" for_type_id="type_{$rd}">
HTMLCODE;
			foreach($this->ad_sub_type as $k => $v){ 
				$htmlcode .= $k == $this->pagedata['editInfo'][0]['ad_sub_type']?"<option value ='$k' selected>$v</option>":"<option value ='$k'>$v</option>";   
			}

			$htmlcode .= <<<HTMLCODE
				</select>
				
				<hr>监测代码、素材、排期表批量导入 <a href="./etc/xls/multicitys_demo.xls">[范例]</a>
				<input type="hidden" name="monitor_code[]" id="monitor_code_{$rd}" value=''>
				<iframe name="ExlUpload" width="500" height="45" src="./?action=Order&do=MultiCitysUploadForm&for_id=monitor_code_{$rd}" scrolling="no" border="0" frameborder="0">浏览器不支持嵌入式框架，或被配置为不显示嵌入式框架。</iframe>
			</td>
		</tr>
HTMLCODE;
			
		echo $htmlcode;
	}
	
	
	/**
	 * @todo 获取排期表的历史版本
	 * @author bo.wang3
	 * @version 2013-6-25 14:29
	 */
	public function GetScheduleHistory($id,$monitor_code){
		
		if(!empty($monitor_code)){
			//读取排期表历史版本
			$res = $this->_db->dataArray("SELECT id,file_name,file_extension FROM ad_document WHERE useful = 'schedule' AND index_key = $id order by id desc");
			
			foreach($res as $k => $v){
				if($k == 0){
					$sches = "历史版本：<br><strong>{$v['file_name']}</strong>&nbsp;&nbsp;&nbsp;<a href='./?action=System&do=GetDbFileContent&id={$v['id']}&headerType=".$v['file_extension']."&file_name=".urlencode($v['file_name'])."'>[导出] （当前版本）</a><br>";
				}else{
					$sches .= "{$v['file_name']}&nbsp;&nbsp;&nbsp;<a href='./?action=System&do=GetDbFileContent&id={$v['id']}&headerType=".$v['file_extension']."&file_name=".urlencode($v['file_name'])."'>[导出]</a><br>";
				}
			}
		}else{
			$sches = '';
		}
		
		return $sches;
	}
	
	/**
	 * @todo 获取系统自动生成的排期表
	 * @author bo.wang3
	 * @version 2013-6-25 14:29
	 */
	public function GetAutoSchedule($reserve_code){
	
		$pq = $this->_db->rsArray("SELECT file_name,id FROM ad_document WHERE useful = 'autoschedule' AND index_key = (SELECT id FROM ad_reserve WHERE reserve_code = '$reserve_code' LIMIT 0,1)");
		
		if(!empty($pq)){
			return '&nbsp;&nbsp;<a target="_blank" href="./?action=System&do=GetDbFileContent&id='.$pq['id'].'&headerType=csv&file_name='.urlencode($pq['file_name']).'"><font style="color:red;">['.$pq['file_name'].']</font></a>';
		}
	}
	
	
	/**
	 * @author bo.wang3
	 * @version 2013-6-25 14:29
	 */
	public function Tools(){
	
		$ids = $this->_db->dataArray("SELECT id FROM ad_contract where reserve_code = ''");
		
		foreach($ids as $id){
			$databefore = $this->_db->dataArray("SELECT * FROM ad_contract WHERE id = {id[id]");
			var_dump($this->_db->update("ad_contract",array('reserve_code' => '0_'.substr(base64_encode(makerandom(10)), 0,12)),"id = {$id['id']}"));
			$this->WriteReserveLog(ADMIN, "[修改合同]", $id['id'], "ad_contract", array('reserve_code' => '0_'.substr(base64_encode(makerandom(10))), 'ts' => TIMESTAMP),$databefore, "update","");
		}	
	}

	/**
	 * @todo 释放资源邮件提醒
	 * @author xiandi.ou
	 * @version 2014-11-03 15:29
	 */
	public function ReleaseEmail(){

		$contract_data = $this->_db->dataArray("SELECT contract_id,reserve_code,related_aids,admin FROM ad_contract WHERE related_aids is not null order by id desc");
		foreach($contract_data as $k => $v){
			if(rtrim($contract_data[$k]['related_aids'],',') != ''){
				$o = $contract_data[$k]['reserve_code'];
				$reserve_data = $this->_db->rsArray("SELECT id,status FROM ad_reserve WHERE reserve_code = '$o' LIMIT 0,1");
				if($reserve_data['status'] == 4){
					$message_detail[] =array(
						'rid' => $reserve_data['id'],
						'contract_id' => $contract_data[$k]['contract_id'],
						'admin' => $contract_data[$k]['admin']
					);
				}
			}
		}
		if(isset($message_detail)){
			$to = "yang.zhong,bowen.wang,yumu.yuan,xu.niu";
			$topic = "广告预订单释放资源提醒！";
			$message = "部分广告预订单已完成广告下单，但是尚未释放资源，请尽快进行释放资源。";
			$message .= <<<HTMLCODE
				<table>
					<tr>
						<th>预订单编号</th>
						<th>合同编号</th>
						<th>投放人员</th>
					</tr>
HTMLCODE;
			foreach($message_detail as $k => $v){
		//		$to = $this->GetEmailAdress($message_detail[$k]['admin']);
		//		$topic = "广告预订单(ID:".$message_detail[$k]['rid'].")释放资源提醒！";
		//		$message = "广告预订单 ID:".$message_detail[$k]['rid']."(合同编号:".$message_detail[$k]['contract_id']."）已完成广告下单，但是尚未释放资源，请尽快进行释放资源。";
				$message .= <<<HTMLCODE
					<tr>
						<td align="left">{$message_detail[$k]['rid']}</td>
						<td align="center">{$message_detail[$k]['contract_id']}</td>
						<td align="center">{$message_detail[$k]['admin']}</td>
					</tr>
HTMLCODE;
			}
			$message .= <<<HTMLCODE
				</table>
HTMLCODE;
			$this->PushEmail($topic,$message,$to,"","junfeng.deng,limin.pang,bo.wang3");
		}
	}
}
