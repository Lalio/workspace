<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;
}

/**
 * @todo 56广告系统库存预订子系统
 * @author bo.wang3
 * @version 2013-7-22 14:29
 */
Class Reserve extends Mads{
	
	public $pre_locktime;
	public $ckvalidtime;
	public $nor_locktime;

	public function __Construct(){
		parent::__Construct();
		//每天下午6点会发送提醒提交订单的邮件 预订单到期前三天每天早晨9点半会发送提醒下合同的邮件
		//status状态梳理
		//0 已保存  1已提交 2已审核 3撤回中 4 准备投放 5 正在投放 （投放结束的判断暂时以预订单结束时间为准）
		$this->pre_locktime = 3600*3;  //预锁量3个小时
		$this->ckvalidtime  = 3600*1;  //查量结果有效期1个小时
		$this->nor_locktime = 86400*5;  //默认正式锁量五天
	}

	/**
 	 * @todo 基础信息填充
     * @author bo.wang3
     * @version 2013-7-22 14:29
     */
	public function Basic(){
		
	    if($this->func == 'add' || $this->func == 'edit'){
	    	
	    	$data = $_POST;
	        
		    //表单筛查
			$data['starttime'] = strtotime($_POST['starttime']);
			$data['endtime'] = strtotime($_POST['endtime']);
			if($data['starttime'] >= $data['endtime']){
				echo json_encode(array('rs'=>1,'msg'=>'投放开始时间不得大于等于结束时间。'));exit;
			}
			
			$data['channel'] = implode('_',$data['channel']);
			$data['display_hour'] = implode(',',$data['display_hour']);
			
			$data['freq'] = $data['freq_day'].'_'.$data['freq_num'];
			$data['ts'] = TIMESTAMP;

			if(empty($data['area']) || !strstr($data['area'],'_')){
			    echo json_encode(array('rs'=>1,'msg'=>'城市_CPM量配置不正确！'));exit;
			}
			
			unset($data['freq_day']);
			unset($data['freq_num']); //后边入ads_pre表计算频次的时候还要用到这两个变量 暂存到了$_POST里面
	    }
		
		if($this->func == 'edit' || $this->func == 'delete'){ //删除之前的预订单
			$this->_db->delete('ads_pre',"for_rid = $_REQUEST[id]");
		}
		
		if($this->func == 'search'){
		    //搜索配置
		    $this->r_starttime = $_GET['starttime']?strtotime($_GET['starttime']):0;
		    $this->r_endtime = $_GET['endtime']?strtotime($_GET['endtime']):9999999999;
		    
		    $where = "((starttime between {$this->r_starttime} and {$this->r_endtime}) and (endtime between {$this->r_starttime} and {$this->r_endtime})) and ";
		    
		    foreach($_GET as $k => $v){
				if(!empty($v) && in_array($k, array('id','client','dcs','dcs_leader','cs','cs_leader','status','area','cid','type','tp_time','ae','reserve_id'))){
					if($k=='status'){
						if($v<4){
							$where .= "$k = '$v' and ";
						}elseif($v==7){//保存订单查询
							$where .= "$k = 0 and ";
						}elseif($v==4){
							$where .= "$k = 4 and ";
							$where .= "endtime > unix_timestamp(now()) and ";
						}elseif($v==5){
							$where .= "$k = 5 and ";
							$where .= "endtime > unix_timestamp(now()) and ";
						}else{
							$where .= "$k > 3 and ";
							$where .= "endtime < unix_timestamp(now()) and ";
						}
					}else{
						if(is_numeric($v)){
							$where .= "$k = '$v' and ";
						}else{
							$where .= "$k LIKE '%$v%' and ";
						}
		            }
		        }
		    }
		}
		
		//订单排序规则
		//AE的话，如果最近三天的释放的项目显示在最上面，然后单号的排期由新到旧
		//资源的话，有新审核需求的放在最上面，然后单号的排列由新到旧
		if($this->show == 'list'){
    		//角色配置
    		if(ROLE == 'AE'){
    			$where .= "ae = '".ADMIN."'"; //只显示自己的预订单
    			$orderby = ' ORDER BY if(deadline - '.TIMESTAMP.' < 259200 and deadline - '.TIMESTAMP.' > 0,0,1),id DESC';
    		}elseif(in_array(ROLE,array('RESOURCE','DEVELOPER'))){
    			//$where .= "status > 0"; //显示所有已提交+已确认+撤回中的预订单
    			$orderby = ' ORDER BY if(deadline - '.TIMESTAMP.' < 259200 and deadline - '.TIMESTAMP.' > 0,0,1),id DESC';
    			$where .= "1 = 1"; //搜出所有的订单
    		}
		}
		
		if($this->show == 'edit'){
			//读取第三方检测报告目录
			$dir = '../statistics_report/'.$_REQUEST['id'];
			$dh = opendir($dir);
			if(false !== $dh) {
				while (($file = readdir($dh)) !== false) {
					if ($file!="." && $file!="..") {
						if(strstr($file,'MADS')){
							$this->reports .= "<li><a style='color:rgb(216, 56, 56)' href='../statistics_report/{$_REQUEST['id']}/{$file}'><strong>$file</strong></a>&nbsp;&nbsp;&nbsp;<a style='color:rgb(216, 56, 56)' href='javascript:;' class='3report' tar='{$_REQUEST['id']}/{$file}'>[删除]</a></li>";
						}else{
							$this->reports .= "<li><a style='color:rgb(39, 155, 39)' href='../statistics_report/{$_REQUEST['id']}/{$file}'>$file</a>&nbsp;&nbsp;&nbsp;<a style='color:rgb(39, 155, 39)' href='javascript:;' class='3report' tar='{$_REQUEST['id']}/{$file}'>[删除]</a></li>";
						}
					}
				}
				closedir($dh);
			}
		}
		
		if($_GET['ext'] == 'report'){
			$this->BackendDbLogic($data,'ad_reserve','outputtoexcel2',$where,' ORDER BY ts DESC',3000); //导出统计表
		}else{
			$this->BackendDbLogic($data,'ad_reserve','basic',$where,$orderby); //功能切换、数据、数据表名、模版文件名
		}
	}
	
	
	/**
	 * @todo 预订单系统V2
	 * @author bo.wang3
	 * @version 2013-7-22 14:29
	 */
	public function Rms(){
		
		if($this->func == 'add' || $this->func == 'edit'){
			
			
			
			//拼装入库数组
			foreach($_POST['mode'] as $k => $v){
				
				$data[] = array(
						'mode' => intval($v[0]),
						'agent' => $_POST['agent'],
						'region' => $_POST['region'],
						'client' => $_POST['client'],
						'brand' => $_POST['brand'],
						'dcs' => $_POST['dcs'],
						'dcs_leader' => $_POST['dcs_leader'],
						'cs' => $_POST['cs'],
						'cs_leader' => $_POST['cs_leader'],
						'reserve_id' => $_POST['reserve_id'],
						'kpi' => $_POST['kpi'],
						'ts' => $_POST['ts'],
						'message' => $_POST['message'][$k][0],
						'id' => $_POST['id'][$k][0],
						'cid' => $_POST['cid'][$k][0],
						'keyword' => $_POST['keyword'][$k][0],
						'status' => $_POST['status'][$k][0],
						'result' => $_POST['result'][$k][0],
						'type' => $_POST['type'][$k][0],
						'ae' => $_POST['ae'][$k][0],
						'starttime' => strtotime($_POST['starttime'][$k][0]),
						'endtime' => strtotime($_POST['endtime'][$k][0]),
						'tp_time' => $_POST['tp_time'][$k][0],
						'freq' => $_POST['freq_day'][$k][0].'_'.$_POST['freq_num'][$k][0],
						'area' => rtrim($_POST['area'][$k][0]),
						'is_group' => isset($_POST['is_group'][$k][0])?1:0,
						'channel' => implode('_',$_POST['channel'][$k]),
						'display_hour' => implode(',',$_POST['display_hour'][$k]),
						'ts' => TIMESTAMP
				);
				
			}
			
			if(empty($data)){
				echo json_encode(array('rs'=>1,'msg'=>"子预订单信息不能为空，请重新编辑并提交！"));exit;
			}

			//数据有效性检查
			foreach($data as $cindex => $cvalue){

				//表单筛查
				if($cvalue['starttime'] >= $cvalue['endtime']){
					echo json_encode(array('rs'=>1,'msg'=>'投放开始时间不得大于等于结束时间。'));exit;
				}
				
				//过滤空格
				$data[$cindex]['area'] = trim($cvalue['area']);
				
				if(empty($cvalue['area']) || !strstr($cvalue['area'],'_')){
					echo json_encode(array('rs'=>1,'msg'=>'城市_CPM量配置不正确！'));exit;
				}
			}
		}

		$ads_pre_detail['aid'] = "";
		
		if($this->func == 'delete'){ //删除之前的预订单
			$ads_pre_id = $this->_db->dataArray("SELECT aid FROM ads_pre where for_rid =".$_REQUEST['id']);
			foreach($ads_pre_id as $k => $v){
				$ads_pre_detail['aid'] = implode("",array($ads_pre_detail['aid'],$ads_pre_id[$k]['aid'],","));
			}
			$this->_db->delete('ads_pre',"for_rid = ".$_REQUEST['id']);
			$this->_db->delete('ad_document',"useful = 'autoschedule' and index_key = $_REQUEST[id]");
		}
		
		$this->addbtn_state = true; //新增子预定单开关
		if($this->show == 'edit'){
			if(empty($_REQUEST['reserve_id'])){
				header('Location:./?action=Reserve&do=Rms&show=list');
				exit;
			}else{
				$wherestr = "reserve_id = '{$_REQUEST['reserve_id']}'";
				//同一个预订单的子预订单中，只要有一个已经不处于已保存状态，就不可以再新增同辈子预订单了
				$status = $this->_db->dataArray("SELECT status FROM ad_reserve WHERE reserve_id = '{$_REQUEST['reserve_id']}'");
				foreach($status as $v){
					if($v['status'] != 0){
						$this->addbtn_state = false;
						break;
					}
				}
			}
		}
		
		//预订单排序规则
		//AE:如果最近三天的释放的项目显示在最上面，然后单号的排期由新到旧
		//资源:有新审核需求的放在最上面，然后单号的排列由新到旧
		//技术:按照释放时间进行排序
		if($this->show == 'list'){
			//角色配置
			if(ROLE == 'AE'){
				$wherestr .= "ae = '".ADMIN."' "; //只显示自己的预订单
				$orderstr = ' ORDER BY if(deadline - '.TIMESTAMP.' < 259200 and deadline - '.TIMESTAMP.' > 0,0,1),id DESC';
			}elseif(ROLE == 'RESOURCE'){
				$wherestr .= 'status != 0 '; //显示所有已提交+已确认+撤回中的预订单
				$orderstr = ' ORDER BY field(status,2,1,3) DESC,id DESC';
			}else{
				$wherestr .= '1 = 1 '; //技术查看所有的订单
				$orderstr = ' ORDER BY if(deadline - '.TIMESTAMP.' < 259200 and deadline - '.TIMESTAMP.' > 0,0,1),id DESC';
			}
		}

		if($_GET['ext'] == 'report'){
			$this->BackendDbLogicMultiEdit($data,'ad_reserve','outputtoexcel2',$wherestr,$orderstr,3000); //导出统计表
		}else{
			$this->BackendDbLogicMultiEdit($data,'ad_reserve','basic2',$wherestr,$orderstr,30,$ads_pre_detail); //功能切换、数据、数据表名、模版文件名
		}
	}
	
	
	/**
	 * @todo 功能按钮_大键
	 * @author bo.wang3
	 * @version 2013-7-31 14:29
	 */
	public function BtnB($btype){
	    
	    
	    $func = $this->show == 'edit'?'edit':'add';
	    
	    switch($btype){
	        case 1://保存
	            $btn = <<<HTML
<span class="button blue" id="sbt_btn" onclick="asyn_sbt('input_form','./?action=$this->action&do=$this->do&func=$func')">保存订单</span>
HTML;
	            break;
            case 2://审核通过
                $btn = <<<HTML
	    		<span class="button green" id="sbt_btn" onclick="asyn_sbt('input_form','./?action=$this->action&do=Audit&id=$_GET[id]')">审核通过</span>
HTML;
                break;
            case 3://审核失败
                $btn = <<<HTML
				<span class="button red" id="sbt_btn" onclick="asyn_sbt('input_form','./?action=$this->action&do=AuditReJect&id=$_GET[id]')">审核失败</span>
HTML;
                break;
            case 4://撤销
                $btn = <<<HTML
<span class="button black" id="sbt_btn" onclick="if(confirm('确定要撤销此预订单吗（撤销后预订单将 1.回退给相关AE 2.对应锁量将全部释放 ）？')){asyn_trigger('./?action=$this->action&do=ReBackSubmit&id=$_GET[id]');history.go(-1);}">撤销订单</span>
HTML;
                break;
	    }
	    
	    return $btn;
	}
	
	/**
	 * @todo 功能按钮_小键
	 * @author bo.wang3
	 * @version 2013-7-31 14:29
	 */
	public function BtnS($id){
		
		$s_btn = array(); 
		
		$rs = $this->_db->rsArray('SELECT reason,deadline,status,reserve_code,result FROM ad_reserve WHERE id = '.$id);
		
		$reserve_code = $rs['reserve_code'];
		$reason = $rs['reason'];
		$status = $rs['status'];
		$result = $rs['result'];
		$deadline = $rs['deadline']?date('Y-m-d 00:00:00',($rs['deadline'] + $this->nor_locktime)):date('Y-m-d 00:00:00',(TIMESTAMP + $this->nor_locktime));
		
		//提交
		$s_btn[1] = <<<HTML
<span class="button green small"  onclick="if(confirm('提交审核前请复核信息填写无误并已点击页面底部【保存】按钮保存全部订单信息，要继续吗？')){asyn_trigger('./?action=Reserve&do=PreOrderSubmit&id=$id')}">提交审核</span>
HTML;
		//修改
		$s_btn[2] = <<<HTML
<span class="button white small"  onclick="location.href='./?action=Reserve&do=$this->do&show=edit&id=$id'">修改</span>
HTML;
		//删除
		$s_btn[3] = <<<HTML
<span class="button black small delete_btn_reserve" id="$id" url="./?action=Reserve&do=$this->do&func=delete">删除订单</span>
HTML;
		//查看
		$s_btn[4] = <<<HTML
<span class="button blue small" onclick="location.href='./?action=Reserve&do=Rms&show=edit&id=$id'">查看</span>
HTML;
		//申请撤回
		$s_btn[5] = <<<HTML
<span class="button white small" onclick="if(confirm('确定要申请撤销此预订单吗？')){asyn_trigger('./?action=Reserve&do=ReBackAsk&id=$id')}">申请撤回</span>
HTML;
		//审核通过
		$s_btn[6] = <<<HTML
<span class="button green small" onclick="if($('#deadline_$id').val() != '') {if(false != confirm('将当前预订单锁量时间延长至'+$('#deadline_$id').val()+'？')) {asyn_trigger('./?action=Reserve&do=Audit&id=$id&reason='+$('#reason_$id').val()+'&deadline='+$('#deadline_$id').val())}} else { alert('资源释放时间节点不能为空！')}">审核通过</span>
HTML;
		//审核失败
		$s_btn[7] = <<<HTML
<span class="button red small" onclick="if($('#reason_$id').val() != '') {asyn_trigger('./?action=Reserve&do=AuditReJect&id=$id&reason='+$('#reason_$id').val()) } else {alert('审核失败理由不能为空！')}">审核失败</span>
HTML;
		//同意撤回
		$s_btn[8] = <<<HTML
<span class="button black small" onclick="if(confirm('确定要撤销此预订单吗？撤销后预订单将 \\n1.回退给相关AE \\n2.对应锁量将全部释放 \\n3.对应合同全部删除')){asyn_trigger('./?action=Reserve&do=ReBackSubmit&id=$id&reserve_code=$reserve_code');}">同意撤回</span>
HTML;
		//快捷查量
		$s_btn[9] = <<<HTML
<a class="button blue small" target="_blank" href="./?action=Query&do=Task&show=add&r_id=$id">快捷查量</a>
HTML;
		//查量历史
		$s_btn[10] = <<<HTML
<a class="button white small" target="_blank" href="./?action=Query&do=Task&show=list&r_id=$id">查量历史</a>
HTML;
		//导出为Excel
		$s_btn[11] = <<<HTML
<a class="button white small" target="_blank" href="./?action=Reserve&do=OutPutToExcel&headerType=xls&ids=$id">导出为Excel</a>
HTML;
		//审核理由
		$s_btn[12] = <<<HTML
审核理由<input type="text" size="50" id="reason_$id" value="$reason" nd_check/>
HTML;
		//资源释放截止
		$s_btn[13] = <<<HTML
资源锁量延期<input type="text" id="deadline_$id" class="input_Calendar" onclick="WdatePicker({startDate:'$deadline',dateFmt:'yyyy-MM-dd HH:mm:ss',highLineWeekDay:true,readonly:true})" size="25" value="$deadline">
HTML;
		//生成排期
		$s_btn[14] = <<<HTML
<span class="button red small pre_lock" rid="$id">预锁资源</span>
HTML;
		
		//1提交//2修改//3删除//4查看//5申请撤回//6审核通过//7审核失败//14生成排期
		//8同意撤回//9快捷查量//10查量历史//11导出为Excel//12审核理由//13资源释放截止
		
		if(ROLE == 'AE'){
			
			switch ($status){
				
				case 0:
					$cm = $this->_db->rsArray("SELECT id FROM ad_checkamounts WHERE for_rid = $id");
					if(empty($cm)){
						$btn = $s_btn[3].$s_btn[9];
					}else{//包含查量历史和生成排期
						$btn = $s_btn[3].$s_btn[9].$s_btn[10].$s_btn[14];
						if(true === $this->IsLocked($id)){
							$btn .= $s_btn[1];
						}
					}
					break;
					
				case 1:
					$btn = $s_btn[10].$s_btn[5];
					break;
					
				case 2:
					if($result == 2){
						$btn = $s_btn[3].$s_btn[9].$s_btn[10].$s_btn[14];
						if(true === $this->IsLocked($id)){
							$btn .= $s_btn[1];
						}
					}else{
						$btn = $s_btn[9].$s_btn[10].$s_btn[5];
					}
					break;
					
				//1提交//2修改//3删除//4查看//5申请撤回//6审核通过//7审核失败//14生成排期
				//8同意撤回//9快捷查量//10查量历史//11导出为Excel//12审核理由//13资源释放截止
					
				case 3:
					$btn = $s_btn[10];
					break;
					
				case 4:
					$btn = $s_btn[10].$s_btn[5];
					break;
					
				case 5:
					$btn = $s_btn[10];
					break;
					
				case 6:
					$btn = $s_btn[10];
					break;
			}
			
		}else{
			
			switch ($status){
				case 1:$btn = $s_btn[9].$s_btn[10].$s_btn[6].$s_btn[13].$s_btn[7].$s_btn[12];break;
				case 2:$btn = $s_btn[9].$s_btn[10];break;
				case 3:$btn = $s_btn[10].$s_btn[8];break;
				case 4:$btn = $s_btn[10];break;
				case 5:$btn = $s_btn[10];break;
				case 6:$btn = $s_btn[10];break;
			}
			
		}
		
		return $s_btn[11].$btn;
	}
	
	/**
	 * @todo 根据城市_CPM列表取得指定城市的CPM量
	 * @author bo.wang3
	 * @version 2013-7-31 14:29
	 */
    public function GetCityCpm($city,$city_cpm){
        
        $area_arr = explode("\n",$city_cpm);
        
        foreach ($area_arr as $a){
            $a = explode("_",$a);
            if($a[0] == $city){
                return "$a[1]";
            }
        }
	
	}
	
	/**
	 * @todo 结合预订单查量结果对客户需求预锁量三个小时
	 * @author bo.wang3
	 * @version 2013-8-27 14:29
	 */
	public function PreLock(){
	
		$id = trim($_REQUEST['id']);
		$task_id = trim($_REQUEST['task_id']);
	
		$rs = $this->_db->rsArray("SELECT id,input,output,require_ts,from_unixtime(require_ts) as require_time
								   FROM ad_checkamounts 
								   WHERE for_rid = $id 
								   ORDER BY id DESC LIMIT 0,1");
		
		$input = json_decode($rs['input'],true);
		$valid_days = pre_dates($input['starttime'], $input['endtime']);
		$valid_day_num = count($valid_days);
		
		if(empty($rs['id']) || (TIMESTAMP - $rs['require_ts'] > $this->ckvalidtime)){
			echo json_encode(array('rs' => 1,'msg' => '该预订单尚未进行查量或查量结果已超时，请重新查量！'));
			exit;
		}
		
		if(empty($rs['output'])){
			echo json_encode(array('rs' => 1,'msg' => "查量正在进行中，请稍后重试!"));
			exit;
		}
			
		$reserve_info = $this->_db->rsArray("SELECT * FROM ad_reserve WHERE id = $id");
		$area_info = explode("\n", $reserve_info['area']);
		foreach($area_info as $v){
			$tmp = explode('_', $v);
			$area_cpm[$tmp[0]] = floor($tmp[1]);
		}
	
		//获取经过损耗处理之后的查量结果
		$outputs = explode(',', $this->GetMaxAmount($id));
		foreach($outputs as $v){
			$tmp = explode(':', $v);
			$can_cpm[$tmp[0]] = floor($tmp[1]*$valid_day_num);
		}
		
		//检查客户需求与查量结果城市是否匹配，是否在有效量之内
		foreach ($area_cpm as $k => $v){
			if(!array_key_exists($k, $can_cpm)){
				echo json_encode(array('rs'=>1,'msg'=>"城市[{$k}]尚未进行查量，请查量、调整客户需求后进行锁量！"));exit;
			}
			if($area_cpm[$k] > $can_cpm[$k]){
				echo json_encode(array('rs'=>1,'msg'=>"城市[{$k}]预锁定量超过了系统最大量，请调整客户需求后进行锁量！"));exit;
			}
		}
		
		//检查通过
		if($_REQUEST['func'] == 'query'){
			echo json_encode($rs);exit;
		}
		
		//清除旧的预订单信息
		$this->WriteReserveLog(ADMIN, "[预锁资源删除原有ads_pre]", $id, "ads_pre", "", "", "delete",array());
		$this->_db->delete('ads_pre',"for_rid = {$id}");
		$databefore = $this->_db->dataArray("SELECT * FROM ad_reserve WHERE id = $id");
		
		//清除旧的排期表
		$this->_db->delete('ad_document',"useful = 'autoschedule' and index_key = $id");
		
		//根据是否成组投放对ads_pre入库
		
		//分别获取频控和频次
		$pq = explode('_', $reserve_info['freq']);
		if($reserve_info['is_group'] == 1){
			foreach($area_cpm as $k => $v){
				$city .= $k.'_';
				$cpm += $v;
			}
			$city = rtrim($city,'_');
			$cpm = floor($cpm/$valid_day_num);
			
			//成组投放
			$value = array(
					'title' => "预订单数据",
					'description' => "$reserve_info[dcs]_$reserve_info[cs]_$reserve_info[starttime]_$reserve_info[endtime]",
					'link' => '#freq_'.(2880*(int)$pq[0]),
					'freq' => $pq[1],
					'starttime' => $reserve_info['starttime'],
					'endtime' => $reserve_info['endtime'],
					'city' => $city,
					'cpm' => $cpm,
					'cid' => $reserve_info['cid'],
					'type' => $reserve_info['type'],
					'channel' => $reserve_info['channel'],
					'username' => ADMIN,
					'status' => 0,
					'for_rid' => $id
			);
			$rs_log = $this->_db->insert('ads_pre',$value);
			$value_log = array(
					'city' => $value['city'],
					'cpm' => $value['cpm'],
					'status' => $value['status'],
					'starttime' => date('Y-m-d H:i:s',$value['starttime']),
					'endtime' => date('Y-m-d H:i:s',$value['endtime']),
					'aid' => $rs_log
			);
			if(false === $rs_log){
				echo json_encode(array('rs'=>1,'msg'=>"子预订单系统预锁量模块发生异常，请与开发人员联系！"));exit;
			}else{
				$this->WriteReserveLog(ADMIN, "[预锁资源]", $id, "ad_reserve", array('deadline' => (TIMESTAMP + $this->pre_locktime),'ts' => TIMESTAMP),$databefore ,"update",$value_log);
			}
		}else{
			//分组投放
			foreach ($area_cpm as $city => $cpm){
				$value = array(
						'title' => "预订单数据",
						'description' => "$reserve_info[dcs]_$reserve_info[cs]_$reserve_info[starttime]_$reserve_info[endtime]",
						'link' => '#freq_'.(2880*(int)$pq[0]),
						'freq' => $pq[1],
						'starttime' => $reserve_info['starttime'],
						'endtime' => $reserve_info['endtime'],
						'city' => $city=='中国'?'':$city,
						'cpm' => floor($cpm/$valid_day_num),
						'cid' => $reserve_info['cid'],
						'type' => $reserve_info['type'],
						'channel' => $reserve_info['channel'],
						'username' => ADMIN,
						'status' => 0,
						'for_rid' => $id
				);
				$rs_log = $this->_db->insert('ads_pre',$value);
				$value_log = array(
						'city' => $value['city'],
						'cpm' => $value['cpm'],
						'status' => $value['status'],
						'starttime' => date('Y-m-d H:i:s',$value['starttime']),
						'endtime' => date('Y-m-d H:i:s',$value['endtime']),
						'aid' => $rs_log
				);
				if(false === $rs_log){
					echo json_encode(array('rs'=>1,'msg'=>"子预订单系统预锁量模块发生异常，请与开发人员联系！"));exit;
				}else{
					$this->WriteReserveLog(ADMIN, "[预锁资源]", $id, "ad_reserve", array('deadline' => (TIMESTAMP + $this->pre_locktime),'ts' => TIMESTAMP),$databefore ,"update",$value_log);
				}
			}
		}
		
		//预锁量操作
		$this->_db->update('ad_reserve',array('deadline' => $rs['require_ts'] + $this->pre_locktime),"id = $id");
			
		echo json_encode(array('rs_code' => 1,'pre_deadline' => date('Y-m-d H:i:s',($rs['require_ts'] + $this->pre_locktime))));
		exit;
	}

	/**
 	 * @todo 提交预订单 主要是检查是否有预锁定资源，area和ads_pre是否超过ad_checkamount的限制
 	 * @des ad_checkamount -> area（城市及总量） -> ads_pre（分量）
     * @author bo.wang3
     * @version 2013-8-1 14:29
     */
	public function PreOrderSubmit(){

		$id = trim($_REQUEST['id']);
		
		$reserve = $this->_db->rsArray("SELECT * FROM ad_reserve WHERE id= $id");
		$ckm_re = $this->_db->rsArray("SELECT * FROM ad_checkamounts WHERE for_rid = $id order by id desc");
		
		$total_day = count(pre_dates($reserve['starttime'], $reserve['endtime']));
		
		//检查查量结果是否超期
		if((TIMESTAMP - $ckm_re['require_ts']) > ($this->pre_locktime + $this->ckvalidtime)){
			echo json_encode(array('rs' => 0,'msg' => '系统超时，请稍后重新刷新页面！'));
			exit;
		}
		
		//检查是否预预锁定资源		
		if(false === $this->IsLocked($id)){
			echo json_encode(array('rs' => 0,'msg' => '该预订单预锁定资源量已经失效，请重新锁量后提交！'));
			exit;
		}
		
		//获取最新查量信息
		$ckm_rs_o = $this->GetMaxAmount($id);//获取最新的查量结果
		$ckm_rs_o = explode(',', $ckm_rs_o);
		foreach ($ckm_rs_o as $rs){
			$tmp = explode(':', $rs);
			$ckm_rs[$tmp[0]] = $tmp[1];
		}
		
		//检查area
		$areas_o = explode("\n", $reserve['area']);
		foreach ($areas_o as $area){
			$tmp = explode("_", $area);
			$area_rs[$tmp[0]] = $tmp[1];
		}
		
		foreach ($area_rs as $k => $v){
			if(!array_key_exists($k, $ckm_rs)){
				echo json_encode(array('rs' => 0,'msg' => "投放定向区域[$k]查量结果无效，请重新修改或查量！"));
				exit;
			}
			if($v > floor($ckm_rs[$k]*$total_day)){
				echo json_encode(array('rs' => 0,'msg' => "投放定向区域[$k]总量超过可预定量，请重新修改！"));
				exit;
			}
			$area_sum += $v; //计算总CPM
		}
		
		//检查ads_pre
		if($reserve['is_group'] == 1){
			//成组投放的情况
			$rs = $this->_db->rsArray("SELECT cpm,city FROM ads_pre WHERE for_rid= $id");
			if(floor($area_sum/$total_day) != $rs['cpm']){
				echo json_encode(array('rs' => 0,'msg' => "预锁定资源总量与客户需求量不符，请重新预锁资源！"));
				exit;
			}
			foreach($area_rs as $k => $v){
				$areas .= $k.'_';
			}
			if(trim($areas,'_') != $rs['city']){
				echo json_encode(array('rs' => 0,'msg' => "预锁定资源定向区域与客户需求不符，请重新预锁资源！"));
				exit;
			}
		}else{
			//分组投放的情况
			foreach($area_rs as $k => $v){
				
				$city = $k=='中国'?'':$k;
				
				$rs = $this->_db->rsArray("SELECT cpm FROM ads_pre WHERE for_rid= $id AND city = '$city'");
				if(empty($rs)){
					echo json_encode(array('rs' => 0,'msg' => "投放定向区域[$k]尚未进行预锁量，请重新修改或查量！"));
					exit;
				}
				if(floor($v/$total_day) != $rs['cpm']){
					echo json_encode(array('rs' => 0,'msg' => "投放定向区域[$k]预锁量与客户需求量不符合，请重新修改或查量！"));
					exit;
				}
			}
		}

		$state = array(
				'status' => 1,
				'result' => 0,
				'reserve_code' => '',
				'ts' => TIMESTAMP
		);
		
		$starttime = date('Y-m-d H:i:s',$reserve['starttime']);
		$endtime = date('Y-m-d H:i:s',$reserve['endtime']);
		$sbttime = date('Y-m-d H:i:s',TIMESTAMP);
		$client = empty($reserve[client])?"$reserve[agent]($reserve[brand])":$this->clients[$reserve[client]];
		
		$databefore = $this->_db->dataArray("SELECT * FROM ad_reserve WHERE id = $id");
		if($this->_db->update('ad_reserve',$state,"id = $id")){
			$this->WriteReserveLog(ADMIN, "[提交审核]",$id, "ad_reserve", $state, $databefore, "update","");
		
			echo json_encode(array('rs' => 0,'msg' => '预订单提交成功！'));
				
			$to = $this->GetEmailAdress(ADMIN);
			//邮件正文
			$topic = "广告预订单(ID:$id)已经提交资源部审核！";
			$message = "您的广告预订单已经提交资源部审核，详细内容如下：</br>";
			$message .= <<<HTMLCODE
			<p>预订单编号：#{$reserve[id]}</br>
			   客户名称：{$client}</br>
			   投放类型：{$this->adstype[$reserve[cid]]}</br>
			   投放周期：{$starttime} - {$endtime}</br>
			   提交AE：{$reserve[ae]}</br>
			   提交时间：{$sbttime}</p>
HTMLCODE;
			$message .= "请您在广告系统后台实时关注预订单状态。";
				
			$this->PushEmail($topic,$message,$to,$id,'limin.pang,bo.wang3,junfeng.deng');
		
		}else{
			echo json_encode(array('rs' => 1,'msg' => '预订单提交失败，请与技术人员联系！'));exit;
		}
		
		//提交审核前对area字段、ads_pre表、最新查量结果做有效性判断
		//检查ads_pre表
		/*
		$adspre_info = $this->_db->dataArray("SELECT aid,city,cpm,status FROM ads_pre WHERE for_rid = $id");
		foreach ($adspre_info as $rs){
			$rs['city'] = empty($rs['city'])?'中国':$rs['city'];
			
			if(!array_key_exists($rs['city'], $ckm_rs)){
				echo json_encode(array('rs' => 0,'msg' => "ADS_PRE_{$rs['aid']}没有对应查量信息，请重新查量并更新排期表！"));
				exit;
			}
			
			if($rs['status'] == 1){
				echo json_encode(array('rs' => 0,'msg' => "ADS_PRE_{$rs['aid']}尚未进行预锁量，请重新生成排期表！"));
				exit;
			}
			
			if($rs['cpm'] > floor($ckm_rs[$rs['city']])){
				echo json_encode(array('rs' => 0,'msg' => "ADS_PRE_{$rs['aid']}预锁定量超过查量最大值，请重新生成排期表！"));
				exit;
			}
		}
		*/
		
		//检查自动排期表
		/*
		$pq = $this->_db->rsArray("SELECT bin_data FROM ad_document WHERE useful = 'autoschedule' and index_key = $id");
		$pq = csv2array('',$pq['bin_data']);
		unset($pq[1]);
		
		foreach ($pq as $rs){
			if(!array_key_exists($rs[2], $ckm_rs)){
				echo json_encode(array('rs' => 0,'msg' => "排期表定向区域【{[$rs[2]}]尚未进行预锁量，请重新查量并更新排期表！"));
				exit;
			}
			for($i = 6;$i <= count($rs);$i++){
				$pq_total += $rs[$i];
			}
			if($pq_total > floor($ckm_rs[$rs[2]])*$total_day){
				echo json_encode(array('rs' => 0,'msg' => "排期表定向区域[{$rs[2]}]预定总量超过预锁定量，请重新修改！"));
				exit;
			}
			unset($pq_total);
		}
		*/
	}

	/**
 	 * @todo 预订单审核通过，对资源进行正式锁定并生成排期表
     * @author bo.wang3
     * @version 2013-7-31 14:29
     */
	public function Audit(){
		
		$id = trim($_REQUEST['id']);
			
		$rs = $this->_db->rsArray("SELECT * FROM ad_reserve where id = $id");
		$rs['area'] = explode("\n",$rs['area']);
		$valid_days = pre_dates($rs['starttime'], $rs['endtime']);
		$valid_day_num = count($valid_days);
		
		//生成授权码
		$reserve_code = substr(base64_encode(makerandom(10)), 0,12);
		if($rs[type] < 10){
			$rs[type] = '0'.$rs[type];
		}
		$reserve_code = ("$rs[type]_$reserve_code");
			
		$client = empty($rs[client])?"$rs[agent]($rs[brand])":$this->clients[$rs[client]];
			
		$state = array(
				'status' => 2,
				'result' => 1,
				'ts'     => TIMESTAMP,
				'deadline' => $_REQUEST[deadline]?strtotime($_REQUEST[deadline]):(TIMESTAMP+$this->nor_locktime),
				'reserve_code' => $reserve_code,
				'reason' => $_REQUEST[reason]
		);
			
		//根据AE实际的需求量自动生成排期表
		$pq[0] = array(
				'投放备注信息',
				'投放区域',
				'曝光监测代码',
				'点击监测代码',
				'贴片素材地址',
		);
				
		foreach ($valid_days as $valid_day){
			$pq[0][] = $valid_day;
		}
		
		//成组投放的特殊处理
		if($rs['is_group'] == 1){
			
			foreach($rs['area'] as $k => $v){
				$tmp = explode('_', $v);
				$city .= $tmp[0].',';
				$cpm += $tmp[1];
			}
			
			$city = rtrim($city,',');
			unset($rs['area']);
			$rs['area'][0] = "{$city}_{$cpm}";
			
		}
		
		foreach($rs['area'] as $k => $v){
				
			$tmp = explode('_', $v);
			$city = str_replace(',', '_', $tmp[0]);
			if(floor($tmp[1]/$valid_day_num) == 0){
				$cpm = '';
				$unontinuous = true;
			}else{
				$cpm = floor($tmp[1]/$valid_day_num);
			}
				
			$pq[($k+1)] = array(
					'',
					$city,
					'',
					'',
					'',
			);
		
			for ($i=0;$i<$valid_day_num;$i++){
				$pq[($k+1)][] = $cpm;
			}
		}
			
		//处理后的排期表转化为csv格式写入临时文件
		file_put_contents(TMPFILE,array2csv($pq));
		//删除上一份自动预订单表
		$this->_db->delete('ad_document',"useful = 'autoschedule' and index_key = $id");
		//生成文档入库
		$fid = $this->PutFileIntoDb(TMPFILE,'autoschedule',"#{$id} 广告投放排期表  By ".ADMIN.".csv",$id , 'csv');
			
		$databefore = $this->_db->dataArray("SELECT * FROM ad_reserve WHERE id = $id");
		//发送邮件
		if($this->_db->update('ad_reserve',$state,"id = $id")){
			$this->WriteReserveLog(ADMIN,"[审核通过]", $id, "ad_reserve", $state, $databefore, "update",array());
			
			$to = $this->GetEmailAdress($rs['ae']);
					
			$starttime = date('Y-m-d H:i:s',$rs['starttime']);
			$endtime = date('Y-m-d H:i:s',$rs['endtime']);
			$sbttime = date('Y-m-d H:i:s',TIMESTAMP);
					
			//邮件正文
			$topic = "广告预订单(ID:$id)已经审核通过！";
			$message = "您的预订单(ID:$id)已经审核通过，授权编码为 <b>$reserve_code</b>  ,请于 ".date('Y年m月d日',$state[deadline])." 前补全合同信息，否则该预订单资源将被释放！";
			$message .= <<<HTMLCODE
			<p>预订单编号：#{$rs[id]}</br>
			   客户名称：{$client}</br>
			   投放类型：{$this->adstype[$rs[cid]]}</br>
			   投放周期：{$starttime} - {$endtime}</br>
			   提交AE：{$rs[ae]}</br>
			   提交时间：{$sbttime}</p>
HTMLCODE;
			$this->PushEmail($topic,$message,$to,$id);
			
			if($unontinuous){
				$msg = "订单状态修改成功！\n--------\n该预订单投放区间不连续，请提醒AE重新导入自定义排期。";
			}else{
				$msg = "订单状态修改成功！\n--------\n投放排期表生成成功！";
			}
			
			echo json_encode(array('rs' => 0,'msg' => $msg));
		}else{
			echo json_encode(array('rs' => 1,'msg' => '订单状态修改失败！'.$this->_db->_errorMsg));
		}
		exit;
	}

	/**
 	 * @todo 订单审核失败
     * @author bo.wang3
     * @version 2013-7-31 14:29
     */
	public function AuditReJect(){
		
			$id = trim($_REQUEST['id']);
			
			//锁量失效
			$this->_db->conn("update ads_pre set status = 1 where for_rid = ".$id);
			
			$state = array(
			        'status' => 2,
			        'result' => 2,
					'reserve_code' => '',
					'deadline' => '',
					'reason' => $_REQUEST['reason'],
					'ts' => TIMESTAMP,
			);
			
			$state['reason'] = $_REQUEST[reason];
			$databefore = $this->_db->dataArray("SELECT * FROM ad_reserve WHERE id = $id");
			
			if($this->_db->update('ad_reserve',$state,"id = $id")){
				$this->WriteReserveLog(ADMIN, "[审核失败]",$id, "ad_reserve", $state, $databefore, "update",array('statu' => 1));
	
				$rs = $this->_db->rsArray("SELECT ae,message,reason from ad_reserve where id = $id");
				$to = $this->GetEmailAdress($rs['ae']);
				
				echo json_encode(array('rs' => 0,'msg' => '订单状态修改成功！'));
				//邮件正文
				$topic = "广告预订单(ID:$id)没有审核通过！";
				$message = "您提交的广告预订单(ID:$id)没有审核通过，该预订单已经被回退到广告系统，请及时查看。";
				if(!empty($state[reason])){
					$message .= "退回理由：$state[reason]";
				}
				$this->PushEmail($topic,$message,$to,$id);
				
			}else{
				echo json_encode(array('rs' => 1,'msg' => '订单状态修改失败！'));
			}
			exit;
	}

	/**
 	 * @todo 订单申请撤回
     * @author bo.wang3
     * @version 2013-7-31 14:29
     */
	public function ReBackAsk(){

		$id = trim($_REQUEST['id']);
		$state = array(
		        'status' => 3,
				'result' => 0,
				'ts' => TIMESTAMP
		);

		$databefore = $this->_db->dataArray("SELECT * FROM ad_reserve WHERE id = $id");
		if($this->_db->update('ad_reserve',$state,"id = $id")){
			$this->WriteReserveLog(ADMIN, "[申请撤回]", $id, "ad_reserve", $state, $databefore, "update","");
			echo json_encode(array('rs' => 0,'msg' => '申请已发送！'));
		}else{
			echo json_encode(array('rs' => 1,'msg' => '订单状态修改失败！'));
		}
		exit;
	}

	/**
 	 * @todo 订单撤回确定
     * @author bo.wang3
     * @version 2013-7-31 14:29
     */
	public function ReBackSubmit(){

		$id = trim($_REQUEST['id']);
		$reserve_code = trim($_REQUEST['reserve_code']);
		
		if(!empty($reserve_code)){
			$databefore = $this->_db->dataArray("SELECT id FROM ad_contract WHERE reserve_code = '$reserve_code'");
			//删除对应子合同
			$this->_db->delete('ad_contract',"reserve_code = '$reserve_code'");
			foreach($databefore as $k =>$v){
				$this->WriteReserveLog(ADMIN, "[订单撤回删除合同]", $databefore[$k]['id'], "ad_contract", "", "", "delete","");
			}
			//echo json_encode(array('rs' => 0,'msg' => '预订单授权码不得为空，请与技术人员联系！'));
			//exit;
		}
		
		$state = array(
		        'status' => 0, //保存状态
				'result' => 2, //审核不通过 可以用来区分撤回的状态
				'reserve_code' => '',//锁量失效
				'ts' => TIMESTAMP,
				'reason' => '',
				'deadline' => '' //释放截止期清除
		);
		
		//删除排期表
		$this->_db->delete('ad_document',"useful = 'autoschedule' and index_key = $id");

		$databefore = $this->_db->dataArray("SELECT * FROM ad_reserve WHERE id = $id");
		if($this->_db->update('ad_reserve',$state,"id = $id")){
			$this->WriteReserveLog(ADMIN, "[撤回确定]", $id, "ad_reserve", $state, $databefore, "update",array('status' => 1));
			$this->_db->update("ads_pre",array('status' => 1),"for_rid = $id");//1无效
			$rs = $this->_db->rsArray("SELECT ae from ad_reserve where id = $id");
			$to = $this->GetEmailAdress($rs['ae']);

			//邮件正文
			$topic = "广告预订单(ID:$id)已经被撤回！";
			$message = "您的广告预订单 ID:$id 已经被资源部门撤回，请您修改后重新提交！";
			$this->PushEmail($topic,$message,$to,$id);

			echo json_encode(array('rs' => 0,'msg' => '预订单撤回成功！'));

		}else{
			echo json_encode(array('rs' => 1,'msg' => '订单状态修改失败！'));
		}
		exit;
	}
	
	/**
	 * @todo 释放预订单资源
	 * @author bo.wang3
	 * @version 2013-7-31 14:29
	 */
	public function Release(){
	
		$reserve_code = trim($_REQUEST['reserve_code']);
		$worker = ADMIN;//当前投放
		$release_time = date('Y-m-d H:i:s');//锁量释放时间
		
		$reserve = $this->_db->rsArray("SELECT * from ad_reserve where reserve_code = '$reserve_code' limit 0,1");
		$client = empty($reserve[client])?"$reserve[agent]($reserve[brand])":$this->clients[$reserve[client]];
		
		if($this->_db->conn("update ads_pre set status = 1 where for_rid = ".$reserve[id])){
			$databefore = $this->_db->dataArray("SELECT * FROM ad_reserve WHERE id = $reserve[id]");
			
			$this->_db->conn("update ad_reserve set status = 5 where id = $reserve[id]");
			$this->WriteReserveLog(ADMIN, "[释放资源]", $reserve[id], "ad_reserve", array('status'=> 5, 'ts' => TIMESTAMP), $databefore, "update",array('status' => 1));
			$to = $this->GetEmailAdress($reserve['ae']);
	
			//邮件正文
			$topic = "广告预订单(ID:$reserve[id])已完成合同下单！";
			$message = "您的广告预订单 ID:$reserve[id] 对应广告已完成合同下单流程，进入正式投放环节，请关注监测数据及投放结果。";
			$message .= <<<HTMLCODE
					<p>预订单编号：#{$reserve[id]}</br>
					   客户名称：{$client}</br>
					   投放类型：{$this->adstype[$reserve[cid]]}</br>
					   提交AE：{$reserve[ae]}</p>
					   投放人员：{$worker}</p>
					   锁量释放时间：{$release_time}</p>
HTMLCODE;
			
			$this->PushEmail($topic,$message,$to,$reserve[id],ADMIN);
	
			echo json_encode(array('rs' => 0,'msg' => '资源释放成功！'));
	
		}else{
			echo json_encode(array('rs' => 1,'msg' => '资源释放失败，预订单对应资源已释放或授权编码不正确。'));
		}
		exit;
	}
	
	/**
	 * @todo 每日18:30时提醒AE最终确定
	 * @author bo.wang3
	 * @version 2013-7-31 14:29
	 */
	public function InformAe(){
	
		//获取已保存的预订单
		$today = getdate();
		$s_time = mktime(0,0,0,$today['mon'],$today['mday'],$today['year']);
		$e_time = $s_time + 86400;
		$rs = $this->_db->dataArray("SELECT * from ad_reserve where status = 0 and ts > $s_time and ts < $e_time");
		$client = empty($rs[client])?"$rs[agent]($rs[brand])":$this->clients[$rs[client]];
		
		foreach($rs as $data){
			//每天下午六点发送邮件
				$to = $this->GetEmailAdress($data['ae']);;
				//邮件正文
				$topic = "广告预订单(ID:$data[id])尚未提交审核！";
				$message = "您的如下广告预订单尚未提交资源部审核：";
				$message .= <<<HTMLCODE
				<p>预订单编号：#{$data[id]}</br>
				   投放类型：{$this->adstype[$data[cid]]}</br>
				   提交AE：{$data[ae]}</br>
HTMLCODE;
				$this->PushEmail($topic,$message,$to,$data[id]);
		}
	}
	
	/**
	 * @todo 预订单锁量延迟
	 * @author bo.wang3
	 * @version 2013-7-31 14:29
	 */
	public function DelayDeadline(){
		
		$r = $this->_db->rsArray('SELECT deadline,ae FROM ad_reserve WHERE id = '.$_REQUEST[id]);
		$r['deadline'] = date('Y-m-d H:i:s',$r['deadline']);
		$n_d = strtotime($_REQUEST['deadline']);
		
		$databefore = $this->_db->dataArray("SELECT * FROM ad_reserve WHERE id = $_REQUEST[id]");

		if($this->_db->update('ad_reserve',array('deadline' => $n_d),"id = $_REQUEST[id]")){
			$this->WriteReserveLog(ADMIN, "[锁量延期]", $_REQUEST[id], "ad_reserve", array('deadline'=> $n_d, 'ts' => TIMESTAMP), $databefore, "update","");
			
			$to = $this->GetEmailAdress($r['ae']);;
			//邮件正文
			$topic = "广告预订单(ID:$_REQUEST[id])锁量延期成功！";
			$message = "您的编号为 #$_REQUEST[id] 的广告预订单已成功延期锁量，释放节点已由 $r[deadline] 更改至  $_REQUEST[deadline] ，预定资源将于 $_REQUEST[deadline] 被释放。";
			
			$this->PushEmail($topic,$message,$to);
			
		}
	}
	
	/**
	 * @todo 导出预订单数据
	 * @author bo.wang3
	 * @version 2013-6-25 14:29
	 */
	public function OutPutToExcel(){
	    
	    $ids = rtrim($_REQUEST['ids'],',');
	    
	    $ids_check = explode(',',$ids); //数据检查
	    foreach ($ids_check as $id){
	        if(!is_numeric($id)){
	            go_win('输入数据不合法！');
	            exit;
	        }
	    }
	    
        $sql = "select id,agent,case when region=1 then '华南' when region=2 then '华北' when region=3 then '华东' else region end as region ,client,  if(client=0,agent, client.vname) as vname,brand,dcs,dcs_leader,cs,";
        $sql .= "cs_leader,date_format(from_unixtime(starttime),'%Y年%m月%d日')st,date_format(from_unixtime  (endtime),'%Y年%m月%d日')et,area, case when `mode`= 1 then '内部推广' when `mode`= 2 then '购买' ";
        $sql .= "when `mode`= 3 then '补量' when `mode`= 4 then  '赠送' else `mode` end as `mode`,ad_reserve.cid,channel.cname,ad_reserve.type,channel,display_hour,tp_time,";
        $sql .= "substring_index(freq,'_',1)as days, substring_index(substring_index(freq,'_',2),'_',-1)as times,display_hour,kpi,ae,keyword,date_format(from_unixtime(ts),'%Y-%m-%d %H:%i:%s')";
        $sql .= "ts,message from ad_reserve left join client on ad_reserve.client=client.vid ,channel where  ad_reserve.cid=channel.cid and id in( $ids )";
        
        $this->pagedata = $this->_db->dataArray($sql);
        
        include Template('outputtoexcel','Reserve');
	}
	
	/**
	 * @todo 守护进程     对快到期的还没有下合同的预订单发邮件提醒
	 *               对已经下到合同的预订单修改状态
	 *               对已经超期且没有下合同的订单进行状态回退
	 *               释放锁量
	 *               此脚本每分钟跑一次
	 * @author bo.wang3
	 * @version 2013-7-31 14:29
	 */
	public function Daemon(){
	
		//step1 处理已经审核且审核通过的预订单
		$rs = $this->_db->dataArray("SELECT id,status,result from ad_reserve WHERE status = 2 AND result = 1");
		
		foreach($rs as $data){
			$reserve_ids[] = intval($data['id']);
		}
		
		foreach($reserve_ids as $id){ //遍历预订单表
			//取出预订单
			$reserve = $this->_db->rsArray("SELECT * FROM ad_reserve WHERE id = $id LIMIT 0,1");
			
			//根据授权号匹配
			$contract = $this->_db->rsArray("SELECT id FROM ad_contract WHERE reserve_code = '$reserve[reserve_code]' LIMIT 0,1");
			$gap_day_num = count(pre_dates(TIMESTAMP , $reserve['deadline']));
			$client = empty($reserve[client])?"$reserve[agent]($reserve[brand])":$this->clients[$reserve[client]];
			
			if(empty($contract)){ //尚未下合同

				if(TIMESTAMP > $reserve[deadline]){ //超期回退
					//对超期失效的预订单进行回退操作
					$v = array(
							'status' => 0,
							'result' => 0,
							'reserve_code' => '',
							'deadline' => '',
							'reason' => '',
							'ts' => TIMESTAMP
					);
					
					$databefore = $this->_db->dataArray("SELECT * FROM ad_reserve WHERE id = $reserve[id]");
					$this->_db->update('ad_reserve',$v,"id = $id");
					$this->WriteReserveLog(ADMIN, "[回退超期未下合同预订单]", $reserve[id], "ad_reserve", $v, $databefore, "update",array('status' => 1));
					$this->_db->delete('ad_document',"useful = 'autoschedule' and index = $reserve[id]");
					
					echo $id.'step1-1!'.date('Y-m-d',TIMESTAMP).'|';
					continue;
				}
				
				if($gap_day_num > 0 && $gap_day_num <= 3){ //还剩三天邮件提醒
					//控制每天只发送一次
					$to = $this->GetEmailAdress($reserve['ae']);
					//邮件正文
					$topic = "广告预订单(ID:$id)授权过期提醒！";
					$message = "您的广告预订单 ID:$id （授权号：$reserve[reserve_code]）对应的广告资源已经完成审批流程，但是尚未确认合同数据，请于  ".date('Y-m-d H:i:s',$reserve['deadline'])." 前进行确认，否则该预订单将被取消，对应的预定资源将被释放。";
					$message .= <<<HTMLCODE
					<p>预订单编号：#{$reserve[id]}</br>
					   客户名称：{$client}</br>
					   投放类型：{$this->adstype[$reserve[cid]]}</br>
					   提交AE：{$reserve[ae]}</p>
HTMLCODE;
					if(isset($_REQUEST['sendmail'])){
						$this->PushEmail($topic,$message,$to,$reserve[id]);
					}
					
					echo $id.'step1-2!'.$message.'|';
				}
			
			}else{//已经下合同
				$databefore = $this->_db->dataArray("SELECT * FROM ad_reserve WHERE id = $id");
				//修改预订单状态   status 4
				$this->_db->update('ad_reserve',array('status' => 4),"id = $id");
				$this->WriteReserveLog(ADMIN, "[下合同订单修改状态]", $id, "ad_reserve", array('status'=> 4, 'ts' => TIMESTAMP), $databefore, "update","");
				echo $id.'step1-3!'.date('Y-m-d H:i:s',TIMESTAMP).'|';
			}
		}
		
		//step2 处理尚未审核的预订单
		$rs = $this->_db->dataArray("SELECT id,status,result from ad_reserve WHERE status < 2");
		
		foreach($rs as $data){
			$reserve_ids[] = intval($data['id']);
		}
		
		foreach($reserve_ids as $id){ //遍历预订单表
			//取出预订单
			$reserve = $this->_db->rsArray("SELECT * FROM ad_reserve WHERE id = $id LIMIT 0,1");
			
			if(TIMESTAMP > $reserve['deadline']){
				if($reserve['status'] == 1){
					//已经提交尚未审核的订单自动回退到已保存状态
					//对超期失效的预订单进行回退操作
					$v = array(
							'status' => 0,
							'result' => 0,
							'reserve_code' => '',
							'deadline' => '',
							'reason' => '',
							'ts' => TIMESTAMP
					);
					$databefore = $this->_db->dataArray("SELECT * FROM ad_reserve WHERE id = $id");
					$this->_db->update('ad_reserve',$v,"id = $id");
					$this->WriteReserveLog(ADMIN, "[预锁量订单超期回退]", $id, "ad_reserve", $v , $databefore, "update","");
				}
				echo $id.'step2!'.date('Y-m-d',TIMESTAMP).'|';
			}
		}
		
		//step3 处理投放阶段预订单的锁量
		$rs = $this->_db->dataArray("SELECT id,status,result from ad_reserve WHERE status > 2");
		
		foreach($rs as $data){
			$reserve_ids[] = intval($data['id']);
		}
		
		foreach($reserve_ids as $id){ //遍历预订单表
			//取出预订单
			$reserve = $this->_db->rsArray("SELECT * FROM ad_reserve WHERE id = $id LIMIT 0,1");
				
			if($reserve['status'] == 4 && TIMESTAMP > $reserve['deadline']){ //已下合同的订单锁量自动延期
				$databefore = $this->_db->dataArray("SELECT * FROM ad_reserve WHERE id = $id");
				$this->_db->update('ad_reserve',array('deadline' => (TIMESTAMP + $this->pre_locktime)),"id = $id");
				$this->WriteReserveLog(ADMIN, "[已下合同订单自动延迟预锁量]", $id, "ad_reserve", array('deadline' => (TIMESTAMP + $this->pre_locktime), 'ts' => TIMESTAMP), $databefore, "update","");
			}
			
			echo $id.'step3!'.date('Y-m-d',TIMESTAMP).'|';
		}
			
		//step3 清除投放结束订单的锁量 ? 是否可以改成已下单的预订单的锁量
		//$this->_db->update('ads_pre',array('status' => 1),"for_rid in (SELECT id FROM ad_reserve WHERE (unix_timestamp(now()) > endtime))");
	
		//step4 释放其他锁量超期的订单
		$log_id = $this->_db->dataArray("SELECT id FROM ad_reserve WHERE (unix_timestamp(now()) > deadline))");
		foreach($log_id as $k => $v){
			$this->WriteReserveLog(ADMIN, "[超期释放预锁定量]", $log_id[$k]['id'], "ads_pre", "", "", "update",array('status' => 1));
		}
		$this->_db->update('ads_pre',array('status' => 1),"for_rid in (SELECT id FROM ad_reserve WHERE (unix_timestamp(now()) > deadline))");
	}

	/**
	 * @todo 第三方检测数据报告导入
	 * @author bo.wang3
	 * @version 2014-7-8 14:29
	 */
	public function ReportUploadForm($id){
		
		$id = $_REQUEST['id']?intval($_REQUEST['id']):$id;
		echo <<<HTMLCODE
			<script type="text/javascript" src="./script/js/WdatePicker.js"></script>
			<form action="./?action=Reserve&do=ReportProcess&id={$_REQUEST['id']}" method="POST" enctype="multipart/form-data">
				开始日期  <input style="border-radius:5px;vertical-align:middle" type="text" class="input_Calendar" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',highLineWeekDay:true,readonly:true})" name="startdate" size="10" value="">
				结束日期  <input style="border-radius:5px;vertical-align:middle" type="text" class="input_Calendar" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',highLineWeekDay:true,readonly:true})" name="enddate" size="10" value="">
				<input type="file" name="report" size="20"><input type="submit" value="导入Excel文件">
			</form>
HTMLCODE;
	}
	
	
/**
	 * @todo 第三方检测数据报告处理
	 * @author bo.wang3
	 * @version 2014-4-22 14:29
	 */
	public function ReportProcess(){
		
		$s = date('Ymd',strtotime($_REQUEST['startdate']));
		$e = date('Ymd',strtotime($_REQUEST['enddate']));
		
		$id = intval($_REQUEST['id']);
		$o1 = $this->_db->rsArray("SELECT starttime,endtime,area,reserve_code FROM ad_reserve where id = $id limit 0,1");
		$o2 = $this->_db->dataArray("SELECT contract_id FROM ad_contract where reserve_code = '{$o1[reserve_code]}'");
		
		foreach ($o2 as $v){
			$cname[] = "'".$v['contract_id']."'";
		}
		$cname = implode(',', $cname);
		
		if(empty($o2)){
			alert('没有找到该预订单对应的合同，可能该合同尚未被发布。');
			$this->ReportUploadForm($id);
			exit;
		}
		
		if(strtotime($_REQUEST['startdate']) >= strtotime($_REQUEST['enddate'])){
			alert('监测报告区间设置不正确，请检查。');
			$this->ReportUploadForm($id);
			exit;
		}
		
		if(empty($_FILES['report']['tmp_name'])){
			alert('监测报告上传失败，请检查设置！');
			$this->ReportUploadForm($id);
			exit;
		}
		
		//解析文档
		$data = new Spreadsheet_Excel_Reader($_FILES['report']['tmp_name']);
		$content = $data->sheets[0]['cells'];
		
		//原始文档入库
		$fid = $this->PutFileIntoDb($_FILES['report']['tmp_name'], '3report', "$s-$e($o1[reserve_code]).xls", $id);
		
		if(!is_array($content) || false === $data){
			alert('目标文档无法被识别，请重新确认或与开发人员联系！');
			$this->ReportUploadForm($id);
			exit;
		}
		
		//开始计算数据
		$aid_arrs = $this->_db->dataArray("SELECT related_aids FROM ad_contract WHERE contract_id IN ($cname)");
		
		foreach($aid_arrs as $aid_arr){
			$aids .= $aid_arr['related_aids'];
		}
		$aid_all = rtrim($aids,','); //获得目标范围
		
		$size = count($content);
		
		//数据表预处理
		for($i = 2;$i <= $size; $i++){
			//对报表数据进行规整
			for($j=1;$j<=20;$j++){ //补全
				if(!isset($content[$i][$j])){
					$content[$i][$j] = 0;
				}
				if($j > 3 && $j < 10){  //类型处理
					$content[$i][$j] = intval($content[$i][$j]);
				}
			}
		}
		
		for($i = 2;$i <= $size; $i++){ //循环进行处理
			
			$city = trim($content[$i][1]) == '中国'?'':trim($content[$i][1]);
			
			//拼装日期
			$stime = strtotime($content[$i][2]);
			$etime = strtotime($content[$i][3]);
			$days = pre_dates($stime, ($etime+86400));
			
			//写入原始数据
			$ratio = count(pre_dates($stime, $etime))/count(pre_dates($o1['starttime'], $o1['endtime']));
			$ratio = $ratio > 1?1:$ratio;
			
			$content[$i][12] = intval($ratio*$this->GetCityCpm($city,$o1['area']));
			
			$rss = $this->_db->dataArray("SELECT aid FROM ads WHERE concat(area,city) LIKE '%$city%' and aid IN ($aid_all)");

			foreach($rss as $rs){
				$aid_a[] = $rs['aid'];
			}
			
			$aids = implode(',', $aid_a);
			
			foreach ($days as $day){
				$year = date('Y',strtotime($day));
				$month = date('m',strtotime($day));
				$day = date('d',strtotime($day));
				$where[] = "(year = $year AND month = $month AND day = $day )";
			}			
			$where = implode(' OR ',$where);
			
			//读取我方数据并写入
			$tj = $this->_db->rsArray("SELECT sum(view) as view_total,sum(click) as click_total,sum(viewip) as uv_total FROM ad_log WHERE aid IN ($aids) AND ( $where )");

			$view_total = $tj['view_total']?intval($tj['view_total']):0;
			$click_total = $tj['click_total']?intval($tj['click_total']):0;
			$uv_total = $tj['uv_total']?intval($tj['uv_total']):0;
			
			$content[$i][13] = $view_total;
			$content[$i][14] = $click_total;
			
			//计算GAP
			
			//曝光流失GAP
			$content[$i][15] = $view_total == 0 || $content[$i][4] == 0?'-':round(($view_total - $content[$i][4])*100/$view_total,2).'%';
			//曝光+频控流失GAP
			$content[$i][16] = $view_total == 0 || $content[$i][5] == 0?'-':round(($view_total - $content[$i][5])*100/$view_total,2).'%';
			//曝光+地域流失GAP
			$content[$i][17] = $view_total == 0 || $content[$i][6] == 0?'-':round(($view_total - $content[$i][6])*100/$view_total,2).'%';
			//曝光+频控+地域流失GAP
			$content[$i][18] = $view_total == 0 || $content[$i][7] == 0?'-':round(($view_total - $content[$i][7])*100/$view_total,2).'%';
			//点击流失GAP
			$content[$i][19] = $click_total == 0 || $content[$i][8] == 0?'-':round(($click_total - $content[$i][8])*100/$click_total,2).'%';
			//UV流失GAP
			$content[$i][20] = $uv_total == 0 || $content[$i][9] == 0?'-':round(($uv_total - $content[$i][9])*100/$uv_total,2).'%';
			//对应的AID
			$content[$i][21] = 'AID:'.$aids;
			
			//规整数字显示格式
			foreach($content[$i] as $m => $t){
				if(in_array($m,array(4,5,6,7,8,9,12,13,14))){
					$content[$i][$m] = intformat($t);
				}
			}
			
			unset($aid_a);
			unset($where);
			unset($aids);
			unset($rss);
		};

		//生成分析后的报告
		$analyzed = "<html><head></head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><body><table style='border: 1px solid #525C3D;'>";
		foreach ($content as $k => $v){
			
			if($k == 1){
				$analyzed .= "<tr style='background-color:#C3EBD7;border: 1px solid #525C3D;'>";
			}elseif($k%2){
				$analyzed .= "<tr style='background-color:#C3EBD7;border: 1px solid #525C3D;'>";
			}else{
				$analyzed .= "<tr style='border: 1px solid #525C3D;'>";
			}
			
			for($m = 1;$m <= 21;$m ++){
				$analyzed .= '<td style="width:70px;">'.$v[$m].'</td>';
			}
			
			$analyzed .= "</tr>";
		}
		
		$analyzed .= "</table></body></html>";
		
		//文档入库 - 已改为数据库记录
		/*
		$year = date('Y',strtotime($_REQUEST['startdate']));
		$month = date('m',strtotime($_REQUEST['startdate']));
		
		$path = '../statistics_report';
		
		$dir = $path.'/'.$id.'/';
		 
		if(!is_dir($dir)){
			mkdir($dir ,0777);
		}
		
		$o_des = "$dir{$s}-{$e}>{$o1['reserve_code']}.xls";
		*/
		
		//处理后的结果写入文件
		file_put_contents('/home/bo.wang3/tmp',$analyzed);
		//生成文档入库
		$fid = $this->PutFileIntoDb('/home/bo.wang3/tmp','3report',"{$s}-{$e}($o1[reserve_code]) [MADS Analyzed @ ".date('Y-m-d H:i:s')."].xls",$id);
		
		if(false === $fid){
			alert('数据分析报告写入数据库失败，请联系开发人员！');
			$this->ReportUploadForm($id);
			exit;
		}else{
			alert('1.第三方监测报告导入成功！\n2.数据GAP对比报告生成成功！');
			$this->ReportUploadForm($id);
			exit;
		}
		
	}
	
	/**
	 * @todo 删除第三方监测报告
	 * @author bo.wang3
	 * @version 2014-4-22 14:29
	 */
	public function Delete3Report(){
		
		$this->_db->delete('ad_document','id = '.$_REQUEST['tar']);
		/*
		$path = '../statistics_report/'.$_REQUEST['tar'];
		system("rm -rf '$path'");
		*/
	}
	
	/**
	 * @todo 获取子预订单信息设定面板
	 * @author bo.wang3
	 * @version 2013-6-25 14:29
	 */
	public function GetSubReservePanel(){
		
		$rd = makerandom(6); //区分多表单
		$startdate = date('Y-m-d',TIMESTAMP); //开始时间
		$admin = ADMIN;  //当前操作用户
		
		foreach($this->adstype as $k => $v){
			$cid_str .= "<option value ='$k'>$v</option>";
		}
		
		foreach($this->ad_sub_type as $k => $v){
			$types_str .= "<option value ='$k'>$v</option>";
		}
		
		foreach(Core::$vars['Channel'] as $k => $v){
			$channel_str .= <<<HTMLCODE
			<input type="checkbox" name="channel[{$rd}][]" class="channels_{$rd}" value="{$k}" id="channel_{$rd}_{$v}" checked><label for="channel_{$rd}_{$v}">{$v}</label>
HTMLCODE;
		}
		
		for($i=0;$i<24;$i++){
			$hour_str .= <<<HTMLCODE
			<input type="checkbox" name="display_hour[{$rd}][]" class="display_hours_{$rd}" value="{$i}" id="display_hour_{$rd}_{$i}" checked><label for="display_hour_{$rd}_{$i}">$i</label>
HTMLCODE;
		}
		
		$htmlcode = <<<HTMLCODE
		<tr>
			<td>子订单<a href="javascript:;" onclick="$(this).parent().parent().remove();"><strong>[-]</strong></a>
				<input type="hidden" name="id[{$rd}][]" value="" />
				<input type="hidden" name="status[{$rd}][]" value="" />
				<input type="hidden" name="result[{$rd}][]" value="" />
				<input type="hidden" name="ae[{$rd}][]" value="$admin" />
			</td>
			<td style="font-size:9px;padding:12px">
				<hr>广告位置
				<select name="cid[{$rd}][]" class="cid" id="cid_{$rd}" from_cid_id="cid_{$rd}" for_type_id="type_{$rd}">
					{$cid_str}
				</select>
				<input class="adtype_quicksearch" for_id="cid_{$rd}" id="cqk_{$rd}" type="text" value="快速翻查" onclick="this.value=''" onblur="this.value='快速翻查'" size="10" x-webkit-speech="true">
				广告形式
				<select name="type[{$rd}][]" class="type" id="type_{$rd}" from_cid_id="cid_{$rd}" for_type_id="type_{$rd}">
					{$types_str}
				</select>
				
				<hr>投放周期
				<input type="text" class="input_Calendar" onclick="WdatePicker({startDate:'{$startdate} 0:0:0',dateFmt:'yyyy-MM-dd HH:mm:ss',highLineWeekDay:true,readonly:true})" name="starttime[{$rd}][]" size="30" value=""> - <input type="text" class="input_Calendar" onclick="WdatePicker({startDate:'{$startdate} 0:0:0',dateFmt:'yyyy-MM-dd HH:mm:ss',highLineWeekDay:true,readonly:true})" name="endtime[{$rd}][]" size="30"  value="">
				广告用途
				<select name="mode[{$rd}][]">
					<option value=2 >​购买​</option>
				    <option value=1 >​内部推广​</option>
				    <option value=3 >​补量​</option>
				    <option value=​4 >​赠送​</option>​​​​
				</select>
				广告时长
				<input type="text" name="tp_time[{$rd}][]" size="5" value="">秒
				频次控制
				<input type="text" name="freq_day[{$rd}][]" size="5" value="" nd_check>天 <input type="text" name="freq_num[{$rd}][]" size="5" value="" nd_check>次
				
				<hr>客户需求|<input type="checkbox" name="is_group[{$rd}][]" class="is_group" id="is_group_{$rd}"><label for="is_group_{$rd}">成组投放</label>
				<textarea rows="5" cols="12" name="area[{$rd}][]" id="area_{$rd}" class="city_input" id="area"></textarea>
				<font color="orange">(按CPM售卖的广告禁止设置为0)</font>
				<br><iframe width="450" height="45" src="./?action=Tackle&do=InputSwitchForm&id=area_{$rd}&symbol=_" scrolling="no" border="0" frameborder="0">浏览器不支持嵌入式框架，或被配置为不显示嵌入式框架。</iframe>
				<a target="_blank" href="./etc/xls/multicities_input_demo.xls">[Demo]</a>
				
				<hr>定向频道<br/>
					<input class="checkall" type="checkbox" id="channels_{$rd}" checked><a>全选/全不选</a><br/>
					{$channel_str}
				
				<hr>投放时间<br/>
					<input class="checkall" type="checkbox" id="display_hours_{$rd}" checked><a>全选/全不选</a><br/>
					{$hour_str}
					
				<hr>关键词
				<input type="text" name="keyword[{$rd}][]" size="80" value="">(关键字之间请用空格符间隔，例如：美女 视频 周杰伦)
			
				<hr>项目备注
				<input type="text" name="message[{$rd}][]" size="130" value="">
				
						<hr>
			</td>
		</tr>
HTMLCODE;
			
		echo $htmlcode;
	}

	/**
	 * @todo 获取预订单流程控制信息
	 * @author bo.wang3
	 * @version 2013-6-25 14:29
	 */
	public function GetProcessInformation($id){
		
		$rs = $this->_db->rsArray("select reserve_id,reserve_code,status,endtime from ad_reserve where id = ".$id);
		if(!empty($rs['reserve_code'])){
			$cinfo = $this->_db->rsArray("SELECT id,contract_id FROM ad_contract WHERE reserve_code = '".$rs['reserve_code']."' LIMIT 0,1");
		}
		
		$aids = $this->GetAidsByCid($cinfo['id']);
		
		switch($rs['status']){
			case 0 : $rs = '-·--·-<font color="green" class="nd_flash">保存成功</font>-·--·-正在审核-·--·-正在撤回-·--·-审核通过-·--·-准备投放-·--·-正在投放-·--·-投放结束-·--·-';break;
			case 1 : $rs = '-·--·-保存成功-·--·-<font color="blue" class="nd_flash">正在审核</font>-·--·-正在撤回-·--·-审核通过-·--·-准备投放-·--·-正在投放-·--·-投放结束-·--·-';break;
			case 2 : $rs = '-·--·-保存成功-·--·-正在审核-·--·-正在撤回-·--·-<font color="red" class="nd_flash">审核完成</font>-·--·-准备投放-·--·-正在投放-·--·-投放结束-·--·-';break;
			case 3 : $rs = '-·--·-保存成功-·--·-正在审核-·--·-<font color="orange" class="nd_flash">正在撤回</font>-·--·-审核通过-·--·-准备投放-·--·-正在投放-·--·-投放结束-·--·-';break;
			default:
				//$rs = $this->admin[role]=='AE'&&$cinfo['id']?'':'<a target="_blank" href="./?action=Order&do=Contract&show=edit&id='.$cinfo['id'].'">';
				if(TIMESTAMP >= $rs['endtime']){
					$rs = '-·--·-保存成功-·--·-正在审核-·--·-正在撤回-·--·-审核通过-·--·-准备投放-·--·-正在投放-·--·-<strong><a target="_blank" href="./?action=Order&do=ContractV2&show=edit&contract_id='.$cinfo['contract_id'].'&id='.$cinfo['id'].'">投放结束</a></strong>-·--·-';
				}else{
					switch($rs['status']){
						case 4 : $rs = '-·--·-保存成功-·--·-正在审核-·--·-正在撤回-·--·-审核通过-·--·-<font color="green" class="nd_flash"><a target="_blank" href="./?action=Order&do=ContractV2&show=edit&contract_id='.$cinfo['contract_id'].'&id='.$cinfo['id'].'">准备投放</a></font>-·--·-正在投放-·--·-投放结束-·--·-';break;
						case 5 : $rs = '-·--·-保存成功-·--·-正在审核-·--·-正在撤回-·--·-审核通过-·--·-准备投放-·--·-<font color="blue" class="nd_flash"><a target="_blank" href="./?action=Order&do=ContractV2&show=edit&contract_id='.$cinfo['contract_id'].'&id='.$cinfo['id'].'">正在投放</a></font>-·--·-投放结束-·--·-';break;
					}
				}
				//$rs .= $this->admin[role]=='AE'?'':'</a>';
				break;
		}
		
		$brother = $this->_db->rsArray("select id from ad_reserve where reserve_code = '' and reserve_id = '$rs[reserve_id]'");
		
		if(!empty($brother['id'])){
			$rs = '-·--·-保存成功-·--·-正在审核-·--·-正在撤回-·--·-审核通过-·--·-<font color="orange" class="nd_flash">等待投放</font>-·--·-准备投放-·--·-正在投放-·--·-投放结束-·--·-'; break;
		}
		
		return $rs;
	
	}
	
	
	/**
	 * @todo 获取预订单资源锁量状态
	 * @author bo.wang3
	 * @version 2013-6-25 14:29
	 */
	public function GetSuoLiangState($id,$reserve_code){
	
		$rs = $this->_db->dataArray("select status from ads_pre where for_rid = $id limit 0,1");

		foreach ($rs as $r){
			$sl += $r['status'];
		}
		
		if(empty($rs)){
			return '<font color="grey"><未绑定></font>';
		}elseif($sl > 0){
			return '<font color="green"><未锁量></font>';
		}else{
			if(empty($reserve_code)){
				return '<font color="red"><预锁定></font>';
			}else{
				return '<font color="red"><已锁定></font>';
			}
		}
	
	}
	
	/**
	 * @todo 获取预订单资源锁量截止期
	 * @author bo.wang3
	 * @version 2013-6-25 14:29
	 */
	public function GetSuoLiangDeadLine($reserve_id){
	
		$rss = $this->_db->dataArray("select id,deadline from ad_reserve where reserve_id = '$reserve_id' and status = 2 and result = 1");

		foreach ($rss as $rs){
			if(!empty($rs['deadline'])){
				$ck_rs .= "#{$rs['id']}:".date('Y年m月d日',$rs['deadline'])."\n";
			}
		}
	
		return rtrim($ck_rs);
	}
	
	
	/**
	 * @todo 获取第三方公司监测报告数据
	 * @author bo.wang3
	 * @version 2013-6-25 14:29
	 */
	public function Get3ReportsInfo($id){
		
		//读取第三方检测报告目录
		$res = $this->_db->dataArray("SELECT id,file_name FROM ad_document WHERE useful = '3report' AND index_key = $id order by id desc");
		
		foreach($res as $re){
			if(strstr($re['file_name'],'MADS')){
				$reports .= "<li><a style='color:#004B97' href='./?action=System&do=GetDbFileContent&id={$re['id']}&headerType=xls'>{$re['file_name']}</a>&nbsp;&nbsp;&nbsp;<a style='color:#004B97' href='javascript:;' class='3report' report_name='{$re['file_name']}' tar='{$re['id']}'>[删除]</a></li>";
			}else{
				$reports .= "<li><a style='color:#004B97' href='./?action=System&do=GetDbFileContent&id={$re['id']}&headerType=xls'>{$re['file_name']}</a>&nbsp;&nbsp;&nbsp;<a style='color:#004B97' href='javascript:;' class='3report' report_name='{$re['file_name']}' tar='{$re['id']}'>[删除]</a></li>";
			}
		}
		
		return $reports;
	}
	
	/**
	 * @todo 预订单首页订单变动加小红点提示投放和AE
	 * @author bo.wang3
	 * @version 2013-6-25 14:29
	 */
	public function RedBtn($reserve_id){
		//AE:有审核不通过的单、同意撤回的单 资源:有新的提交审核的单
		$rss = $this->_db->dataArray("SELECT result,status,deadline FROM ad_reserve WHERE reserve_id = '$reserve_id'");
		
		foreach($rss as $rs){
			
			if(ROLE == 'AE' && $rs['status'] == 2 && $rs['result'] == 2){
				return '<font style="color:red;font-size:11px">[需求审核完成]</font>';
			}	
			
			if(ROLE == 'AE' && $rs['status'] == 0 && $rs['result'] == 2){
				return '<font style="color:red;font-size:11px">[需求撤回成功]</font>';
			}
			
			if($rs['status'] == 2 && $rs['result'] == 1 && ($rs['deadline'] - TIMESTAMP < 259200) && ($rs['deadline'] - TIMESTAMP > 0)){
				return '<font style="color:red;font-size:11px">['.ceil(($rs['deadline']-TIMESTAMP)/86400).'天内有释放]</font>';
			}
			
			if(ROLE == 'RESOURCE' && $rs['status'] == 3){
				return '<font style="color:green;font-size:11px">[新的撤回请求]</font>';
			}
			
			if(ROLE == 'RESOURCE' && $rs['status'] == 1){
				return '<font style="color:red;font-size:11px">[新的审核请求]</font>';
			}
		}
		
	}
	
	/**
	 * @todo 自动排期表重新导入、下载控制面板
	 * @author bo.wang3
	 * @version 2013-8-27 14:29
	 */
	public function SchedulePanel($id,$task_id){
		
		$dd = $this->_db->rsArray("SELECT status,reserve_code,schedule_ts,result,deadline FROM ad_reserve WHERE id = $id");
		$pq = $this->_db->rsArray("SELECT id,file_name from ad_document where useful = 'autoschedule' and index_key = $id");
		$deadts = $dd['deadline'] > 0?$dd['deadline']:($dd['schedule_ts'] + $this->pre_locktime);
			
		if(!empty($pq)){
			$pq['file_name_encoded'] = urlencode(str_replace('.xls', '', $pq['file_name']));
			$str = <<<HTMLCODE
			<hr><font style="font-weight: bolder;font-style: italic;">投放排期管理</font><br>
			投放排期表：<a target="_blank" href="./?action=System&do=GetDbFileContent&id={$pq[id]}&headerType=csv&file_name={$pq[file_name_encoded]}">{$pq[file_name]}</a>
HTMLCODE;
		}
			
		if(in_array($dd['status'], array(2)) && TIMESTAMP < $deadts){
				$str .= <<<HTMLCODE
			<br>自定义排期表：<iframe name="ScheduleUpload" width="450" height="45" src="./?action=Reserve&do=UserDefinedSchedule&id={$id}&task_id={$task_id}" scrolling="no" border="0" frameborder="0">浏览器不支持嵌入式框架，或被配置为不显示嵌入式框架。</iframe>
						  <font style="color:green;">自定义排期规则：单城市总投放量之和应<预锁量；禁止新增未锁量城市；当日不投放请留空；</font></br>
HTMLCODE;
		}
			
		return $str;
	}
	
	/**
	 * @todo 根据预订单查量结果自动生成排期表
	 * @author bo.wang3
	 * @version 2013-8-27 14:29
	 */
	public function AutoSchedule(){
		
		$id = trim($_REQUEST['id']);
		$task_id = trim($_REQUEST['task_id']);
		$rs = $this->_db->rsArray("SELECT id,input,output,require_ts FROM ad_checkamounts WHERE for_rid = $id ORDER BY id DESC LIMIT 0,1");
		$area_info = $this->_db->rsArray("SELECT area FROM ad_reserve WHERE id = $id");
		$area_info = explode("\n", $area_info['area']);
		
		$input = json_decode($rs['input'],true);
		$valid_days = pre_dates($input['starttime'], $input['endtime']);
		$valid_day_num = count($valid_days);
		
		if($this->func == 'query'){
			
			if(empty($rs['output'])){
				echo json_encode(array('type' => 'invalid','msg' => "最新查量结果无效！（查量正在进行中，请稍后重试）"));
				exit;
			}
			
			if((TIMESTAMP - $rs['require_ts']) > 3600){
				echo json_encode(array('type' => 'invalid','msg' => "最新查量结果无效！（最后一次查量结果距现在已超过1小时，请重新查量）"));
				exit;
			}
			
			foreach ($area_info as $a) {
				$tmp = explode('_', $a);
				if(false === strpos($rs['output'] , $tmp[0])){
					echo json_encode(array('type' => 'invalid','msg' => "城市[{$tmp[0]}]没有查量信息，请重新提交查量。"));
					exit;
				}
			}
			
			$rs['type'] = 'valid';
			$rs['require_ts'] = date('Y-m-d H:i:s',$rs['require_ts']);
			echo json_encode($rs);
			exit;
		}
		
		if($this->func == 'gc'){

			//获取经过损耗处理之后的查量结果
			$outputs = explode(',', $this->GetMaxAmount($id));
			
			/* XLS模式生成
			$pq =  '<table><tr><td>投放备注信息</td><td>投放区域</td><td>曝光监测代码</td><td>点击监测代码</td><td>贴片素材地址</td>';
			foreach ($valid_days as $valid_day){
				$pq .= "<td>$valid_day</td>";
			}
			$pq .= '</tr>';
			
			foreach($outputs as $output){
				
				$tmp = explode(':', $output);
				$city = $tmp[0];
				$cpm = floor($tmp[1]);
				
				$pq .= "<tr><td></td><td>$city</td><td></td><td></td><td></td>";
				for ($i=0;$i<$valid_day_num;$i++){
					$pq .= "<td>$cpm</td>";
				}
				
				$pq .= '</tr>';
				
				//预锁量操作
				$this->_db->update('ads_pre',array('cpm' => $cpm,'status' => 0),"for_rid = $id and city LIKE '%$tmp[0]%'");
				$area_prcd .= $city."_".($cpm*$valid_day_num)."\n"; //算总量
			}
			$pq .= '</table>';
			*/
			
			//以下改用CSV格式生成排期表
			$pq[0] = array(
					'投放备注信息',
					'投放区域',
					'曝光监测代码',
					'点击监测代码',
					'贴片素材地址',
					);
			
			foreach ($valid_days as $valid_day){
				$pq[0][] = $valid_day;
			}
			
			foreach($outputs as $k => $v){
			
				$tmp = explode(':', $v);
				$city = $tmp[0] == '中国'?'':$tmp[0];
				$cpm = floor($tmp[1]);
				
				$pq[($k+1)] = array(
						'',
						$tmp[0],
						'',
						'',
						'',
				);
			
				for ($i=0;$i<$valid_day_num;$i++){
					$pq[($k+1)][] = $cpm;
				}
			
				//预锁量操作
				$ads_pre_id = $this->_db->dataArray("SELECT * FROM ads_pre WHERE for_rid = $id and city = '$city'");
				$log_pre_id = "";
				foreach ($ads_pre_id as $a => $b){
					$log_pre_id = implode("",array($log_pre_id,$ads_pre_id[$a]['aid'],","));
				}
				$this->WriteReserveLog(ADMIN, "[自动生成排期并预锁量]", $id, "ads_pre", "", "", "update",array('cpm' => $cpm,'status' => 0,'aid' => $log_pre_id));

				$this->_db->update('ads_pre',array('cpm' => $cpm,'status' => 0),"for_rid = $id and city = '$city'");
				$area_prcd .= $tmp[0]."_".($cpm*$valid_day_num)."\n"; //算总量
			}
			
			//写入排期
			$v = array(
					'area' => rtrim($area_prcd),
					'schedule_ts' => TIMESTAMP
			);
			$databefore = $this->_db->dataArray("SELECT * FROM ad_reserve WHERE id = $id");
			$this->_db->update('ad_reserve',$v,"id = $id");
			$this->WriteReserveLog(ADMIN, "[自动生成排期并预锁量]", $id, "ad_reserve", $v, $databefore, "update","");
			
			//处理后的排期表转化为csv格式写入临时文件
			file_put_contents(TMPFILE,array2csv($pq));
			//删除上一份自动预订单表
			$this->_db->delete('ad_document',"useful = 'autoschedule' and index_key = $id");
			//生成文档入库
			$fid = $this->PutFileIntoDb(TMPFILE,'autoschedule',"#{$id} 广告投放排期表(task_id:{$task_id}) By ".ADMIN.".csv",$id , 'csv');
		
			echo json_encode(array('rs_code' => 1,'pre_deadline' => date('Y-m-d H:i:s',(TIMESTAMP + $this->pre_locktime))));
			exit;
		}
	}
	
	/**
	 * @todo 用户自主导入自定义排期表
	 * @author bo.wang3
	 * @version 2013-8-27 14:29
	 */
	public function UserDefinedSchedule(){
		
		$id = intval($_REQUEST['id']);
		$task_id = intval($_REQUEST['task_id']);
		
		if(empty($_FILES['schedule']['tmp_name'])){
			
			echo <<<HTMLCODE
			<form action="./?action=Reserve&do=UserDefinedSchedule&id={$id}&task_id={$task_id}" method="POST" enctype="multipart/form-data">
				<input type="file" name="schedule" size="20"><input type="submit" value="导入Excel文件">
			</form>
HTMLCODE;
			
		}else{
			
			$sche_ori = file_get_contents($_FILES['schedule']['tmp_name']); //获取排期内容并转化为数组
			$sche = csv2array($_FILES['schedule']['tmp_name']);
			
			if(!is_array($sche)){
				alert('系统无法识别该排期表，请确认该排期表由MADS系统自动生成且为CVS格式！');
				exit;
			}
			
			$rs = $this->_db->rsArray("SELECT * FROM ad_reserve WHERE id = $id");
			$valid_days = pre_dates($rs['starttime'], $rs['endtime']);
			$total_day = count($valid_days);
			
			//判断排期表日期格式的有效性
			foreach($sche[1] as $k => $v){
				if($k < 6) continue;
				$sche_dates[] = trim($v);
			}
			$date_diff = array_diff($sche_dates, $valid_days);
			if(!empty($date_diff)){
				alert('该排期表日期设置不正确，请确认在投放区间内排期表连续，若当天不投放请留空！');
				exit;
			}
			
			//判断排期表数据部分是否符合标准
			//获取锁量结果，得到每日标准量和区间内总量
			$sl_rs = $rs['area'];
			$sl_rs = explode("\n", $sl_rs);
			
			foreach($sl_rs as $ck){
				$tmp = explode('_', $ck);
				$valid[$tmp[0]]['day_cpm'] = floor($tmp[1]/$total_day); //单城市日均量
				$valid[$tmp[0]]['total_cpm'] = $tmp[1]; //单城市总量
				$all_city .= $tmp[0].'_'; //全部城市
				$all_cpm += $tmp[1]; //全部城市总量
			}
			
			$all_city = rtrim($all_city,'_');
			
			//分组投放的情况
			if($rs['is_group'] == 1){
				unset($valid);
				$valid[$all_city]['day_cpm'] = floor($all_cpm/$total_day);
				$valid[$all_city]['total_cpm'] = $all_cpm;
			}
				
			$total_cpm = array(); //用于记录新导入排期表各个城市的总量
			foreach ($sche as $key => $value) {
				
				if($key == 1) continue;
				
				$city = $value[2];
				if(!array_key_exists($city, $valid)){
					alert("城市[$city]尚未进行预锁量及审核，无法导入自定义排期表！");
					exit;
				}
				
				foreach ($value as $k => $v) {
					if($k < 6) continue;
					//判断每日值和标准值的差异，上限不超过5倍
					if($v != '' && ($v/$valid[$city]['day_cpm'] > 5)){
						alert("城市：{$city} 日期：{$sche[1][$k]} 排期量（{$v}）超过标准值（{$valid[$city]['day_cpm']}）的5倍，请重新修改后提交！");
						exit;
					}else{
						$total_cpm[$city] += $v;
					}
				}
				
				if($total_cpm[$city] > $valid[$city]['total_cpm']){
					//判断排期总量不得超过系统查量总量
					alert("排期城市[$city]排期总投放量（{$total_cpm[$city]}）超过系统锁定的预定量（{$valid[$city]['total_cpm']}），请重新修改！");
					exit;
				}
				
				//分组投放的情况
				if($rs['is_group'] == 1){
					$city_arr = explode('_', $city);
					foreach($city_arr as $c){
						$new_areas .= "{$c}_".floor($total_cpm[$city]/count($city_arr))."\n";
					}
				}else{
					$new_areas .= "{$city}_{$total_cpm[$city]}\n";
				}
			}
			
			//根据排期表重新写入ads_pre
			//获取排期表数据
			foreach($sche as $key => $value){
				if($key == 1) continue;
				foreach($value as $k => $v){
					if($k < 6) continue;
					$sche_data[$key][] = trim($v);
				}
			}
			
			if($rs['is_group'] == 1){
				//成组投放
				
				//检查排期表的合理性
				/*
				$sche_data = array_unique($sche_data);
				
				if(count(array_unique($sche_data)) > 1){
					alert("选择成组投放的预定单排期表内各个城市应该具有相同的排期，订单请重新修改排期表！");
					exit;
				}
				*/
				
				$this->WriteReserveLog(ADMIN, "[自定义生成排期删除原有ads_pre]", $id, "ads_pre", "", "", "delete",array());
				$this->_db->delete('ads_pre',"for_rid = $id");
				
				foreach($valid as $k => $v){
					$citys = $k;
				}
				
				for($k = 0; $k < count($sche_data[2]);$k++){
					
					$v = $sche_data[2][$k];
					if(trim($v) == '' || intval($v) == 0) continue;
						
					$starttime = strtotime($valid_days[$k]);
					$s = 1;
					while($v == $sche_data[2][($k+$s)] && $s < (count($sche_data[2]) - $k)){
						$s++;
					}
					$endtime = strtotime($valid_days[($k+$s-1)]) + 86400; //包含最后一天
					$k = $k+$s-1;
					
					$cpm = $v;
					$pq = explode('_', $rs['freq']);
						
					//成组投放
					$value = array(
							'title' => "预订单数据",
							'description' => "$rs[dcs]_$rs[cs]_$rs[starttime]_$rs[endtime]",
							'link' => '#freq_'.(2880*(int)$pq[0]),
							'freq' => $pq[1],
							'starttime' => $starttime,
							'endtime' => $endtime,
							'city' => rtrim($citys,'_'),
							'cpm' => $cpm,
							'cid' => $rs['cid'],
							'type' => $rs['type'],
							'channel' => $rs['channel'],
							'username' => ADMIN,
							'status' => 0,
							'for_rid' => $id
					);
					$rs_log = $this->_db->insert('ads_pre',$value);
					$value_log = array(
						'city' => $value['city'],
						'cpm' => $value['cpm'],
						'status' => $value['status'],
						'starttime' => date('Y-m-d H:i:s',$value['starttime']),
						'endtime' => date('Y-m-d H:i:s',$value['endtime']),
						'aid'=> $rs_log
						);
					$this->WriteReserveLog(ADMIN, "[自定义生成排期]", $id, "ads_pre", "", "", "insert",$value_log);
				}
				
			}else{
				//非成组投放
				$this->WriteReserveLog(ADMIN, "[自定义生成排期删除ads_pre]", $id, "ads_pre", "", "", "delete",array());
				$this->_db->delete('ads_pre',"for_rid = $id");
				
				foreach($sche_data as $key => $sche_single){
					
					$city = $sche[$key][2];
					
					for($k = 0; $k < count($sche_single);$k++){
							
						$v = $sche_single[$k];
						if(trim($v) == '' || intval($v) == 0) continue;
					
						$starttime = strtotime($valid_days[$k]);
						$s = 1;
						while($v == $sche_single[($k+$s)] && $s < (count($sche_single) - $k)){
							$s++;
						}
						$endtime = strtotime($valid_days[($k+$s-1)]) + 86400; //包含最后一天
						$k = $k+$s-1;
							
						$cpm = $v;
						$pq = explode('_', $rs['freq']);
						
						//分组投放
						$value = array(
								'title' => "预订单数据",
								'description' => "$rs[dcs]_$rs[cs]_$rs[starttime]_$rs[endtime]",
								'link' => '#freq_'.(2880*(int)$pq[0]),
								'freq' => $pq[1],
								'starttime' => $starttime,
								'endtime' => $endtime,
								'city' => $city == '中国'?'':$city,
								'cpm' => $cpm,
								'cid' => $rs['cid'],
								'type' => $rs['type'],
								'channel' => $rs['channel'],
								'username' => ADMIN,
								'status' => 0,
								'for_rid' => $id
						);
						$rs_log = $this->_db->insert('ads_pre',$value);
						$value_log = array(
							'city' => $value['city'],
							'cpm' => $value['cpm'],
							'status' => $value['status'],
							'starttime' => date('Y-m-d H:i:s',$value['starttime']),
							'endtime' => date('Y-m-d H:i:s',$value['endtime']),
							'aid' => $rs_log
						);
						$this->WriteReserveLog(ADMIN, "[自定义生成排期]", $id, "ads_pre", "", "", "insert",$value_log);
					}
				}
			}
			
			//重置areas预定总量
			$databefore = $rs;
			$this->_db->update('ad_reserve',array('area' => rtrim($new_areas,"\n")),"id = {$rs['id']}");
			$this->WriteReserveLog(ADMIN, "[重置预定总量]", $id, "ad_reserve", array('area' => rtrim($new_areas,"\n"), 'ts' => TIMESTAMP), $databefore, "update","");
			
			//更新当前排期表
			//写入临时文件
			file_put_contents(TMPFILE,$sche_ori);
			//删除上一份自动预订单表
			$this->_db->delete('ad_document',"useful = 'autoschedule' and index_key = $id");
			//生成文档入库
			$fid = $this->PutFileIntoDb(TMPFILE,'autoschedule',"#{$id} 广告投放排期表(task_id:{$task_id}) By ".ADMIN.".csv" ,$id, 'csv');
			
			//前端返回
			alert('自定义排期表导入成功！');
			echo <<<HTMLCODE
			<script type="text/javascript">
				parent.location.reload();
			</script>
HTMLCODE;
		}
	}

	/**
	 * @todo 批量导入预订单数据
	 * @author bo.wang3
	 * @version 2013-6-25 14:29
	 */
	public function InputPreOrders(){
			
		$data = new Spreadsheet_Excel_Reader('/home/bo.wang3/final.xls');
		$content = $data->sheets[0]['cells'];
	
		foreach($content as $ctc){
			$target[$ctc[23]][] = $ctc;
		}
	
		foreach($target as $k => $v){
	
			foreach ($v as $tmp){
				$area .= "$tmp[9]_".ltrim($tmp[10],'* ')."\n";
			}
	
			$data = array(
					'region'       => $v[0][1]=='华南'?1:($v[0][1]=='华北'?2:3),
					'agent'        => $v[0][2],
					'client'       => $v[0][3],
					'brand'        => $v[0][4],
					'dcs'          => $v[0][5],
					'dcs_leader'   => $v[0][6],
					'cs'           => $v[0][7],
					'cs_leader'    => $v[0][8],
					'mode'          => $v[0][11]=='内部推广'?1:($v[0][11]=='购买'?2:($v[0][11]=='补量'?3:4)),
					'cid'          => $v[0][12],
					'type'         => $v[0][13]=='前贴片'?1:($v[0][13]=='中贴片'?2:($v[0][11]=='后贴片'?3:21)),
					'area'         => $area,
					'channel'      => '',
					'starttime'    => strtotime($v[0][15]),
					'endtime'      => strtotime($v[0][16]),
					'tp_time'      => $v[0][17],
					'freq'         => $v[0][19],
					'ae'           => $v[0][20],
					'kpi'          => $v[0][21],
					'message'      => $v[0][22],
					'ts'           => TIMESTAMP,
					'deadline'     => (TIMESTAMP + 864000),
					'reserve_code' => "0_".substr(base64_encode((TIMESTAMP + mt_rand(0,1000))), 0,12),
					'status'       => 2,
					'result'       => 1
			);
				
			$this->_db->insert('ad_reserve',$data);
			$this->_db->conn("UPDATE ad_contract SET reserve_code = '$data[reserve_code]' WHERE contract_id = '$k'");
			unset($area);
			unset($tmp);
		}
	}
	
	/**
	 * @todo 跳转到当前用户最新下单的预订单界面
	 * @author bo.wang3
	 * @version 2013-6-25 14:29
	 */
	public function GoToLasted(){
		
		$rs = $this->_db->rsArray("SELECT reserve_id FROM ad_reserve WHERE ae = '".ADMIN."' ORDER BY id DESC");
		header('Location:./?action=Reserve&do=Rms&show=edit&reserve_id='.$rs['reserve_id']);
	}
	
	/**
	 * @todo 获取可供预定量的上限
	 * @des 判断最后两次查量的输入条件，若条件相同：量取查量结果的较大值，城市取两次查量结果的并集
	 * 							   若条件不相同，以最后一次查量结果为准
	 * @author bo.wang3
	 * @version 2013-11-25 14:29
	 */
	public function GetMaxAmount($rid){
		
		//获取最后二次的查量结果
		$rs_e = $this->_db->dataArray("SELECT id,input,output
									  FROM ad_checkamounts
									  WHERE for_rid = $rid
									  ORDER BY id DESC
									  LIMIT 0,2");
		
		if(count($rs_e) == 0){
			return '';
		}elseif(count($rs_e) == 1){
			
			return $this->GetProcessedCheckAmountResult($rs_e[0]['id']);
		}elseif(count($rs_e) == 2){

			$last_input = json_decode($rs_e[0]['input'],true);
			$penu_input = json_decode($rs_e[1]['input'],true);
			
			//判断最后两次查询的条件是否相同
			foreach ($last_input as $k => $v){
				if($k == 'area' || $k == 'rid') continue;
				if($v != $penu_input[$k]){
					$is_same = false;
					break;
				}else{
					$is_same = true;
				}
			}
			
			if(false === $is_same){
				return $this->GetProcessedCheckAmountResult($rs_e[0]['id']);
			}else{
				$last_rs = $this->GetProcessedCheckAmountResult($rs_e[0]['id']);
				$penu_rs = $this->GetProcessedCheckAmountResult($rs_e[1]['id']);
			
				$last_ar = explode("," , $last_rs);
				$penu_ar = explode("," , $penu_rs);
				
				foreach ($last_ar as $k => $v){
					$tmp = explode(":", $v);
					$last_ar[$tmp[0]] = $tmp[1];
					unset($last_ar[$k]);
				}
				
				foreach ($penu_ar as $k => $v){
					$tmp = explode(":", $v);
					$penu_ar[$tmp[0]] = $tmp[1];
					unset($penu_ar[$k]);
				}
				
				foreach ($penu_ar as $k => $v){
					if(!array_key_exists($k, $last_ar) || $v > $last_ar[$k]){
						$last_ar[$k] = $v;
					}
				}
				
				foreach ($last_ar as $k => $v){
					$rs .= "$k:$v,";
				}
				
				return rtrim($rs,',');
			}
		}
	}
	
	/**
	 * @todo 获取查量的对比和可投放量
	 * @author bo.wang3
	 * @version 2013-6-25 14:29
	 */
	public function GetContrast($id){
		
		
		$rd = $this->_db->rsArray("SELECT starttime,endtime,area FROM ad_reserve WHERE id = $id");
		$ck = str_replace(",", "\n", $this->GetMaxAmount($id));
		$ck = str_replace(":", "_", $ck);
		$total = count(pre_dates($rd['starttime'], $rd['endtime']));
		
		$rd_a = explode("\n", $rd['area']);
		$ck_a = explode("\n", $ck);
		
		foreach ($rd_a as $r){ //原始客户的预定量 总量
			$tmp = explode('_', $r);
			$rd_array[$tmp[0]] = $tmp[1];
		}
		
		foreach ($ck_a as $c){ //系统的查量结果 每日量改总量
			$tmp = explode('_', $c);
			$ck_array[$tmp[0]] = $tmp[1]*$total;
			$cm .= $tmp[0]."_".$ck_array[$tmp[0]]."\n";
		}
		
		foreach ($rd_array as $k => $v){  //可满足量
			$cpm = ($v <= $ck_array[$k])?$v:$ck_array[$k];
			if($v > $ck_array[$k]){
				$ct .= $k.'_'.floor($cpm)."(↓)\n";
			}else{
				$ct .= $k.'_'.floor($cpm)."\n";
			}
		}
		
		$tpl = <<<HTML
		可满足量
		<textarea rows="5" cols="12" class="city_input"  readonly>%s</textarea>
		查量结果
		<textarea rows="5" cols="12" class="city_input"  readonly>%s</textarea>
HTML;
		
		if(!empty($ck)){
			echo sprintf($tpl,rtrim($ct),rtrim($cm));
		}
	}
	
	/**
	 * @todo 根据ad_reserve批量修正ads_pre的cpm数据
	 * @author bo.wang3
	 * @version 2013-6-25 14:29
	 */
	public function RepairAdspre(){
			
		$data = $this->_db->dataArray("SELECT starttime,endtime,id,area FROM ad_reserve");
	
		foreach($data as $k => $v){
			$rid = $v['id'];
			$total = count(pre_dates($v['starttime'], $v['endtime']));
			$v['area'] = rtrim($v['area']);
			$this->_db->update('ad_reserve',array('area' => $v['area']),"id = $rid");
			$tar = explode("\n", $v['area']);
			foreach($tar as $t){
				$tmp = explode('_', $t);
				$city = $tmp[0];
				$cpm = floor($tmp[1]/$total);

				//写入update ads_pre log
				$ads_pre_id = $this->_db->dataArray("SELECT * FROM ads_pre WHERE for_rid = $rid and city = '$city'");
				$log_pre_id = "";
				foreach ($ads_pre_id as $a => $b){
					$log_pre_id = implode("",array($log_pre_id,$ads_pre_id[$a]['aid'],","));
				}
				$this->WriteReserveLog(ADMIN, "[批量修正ads_pre的cpm数据]", $rid, "ads_pre", "", "", "update",array('cpm' => $cpm,'status' => 0,'aid' => $log_pre_id));

				var_dump($this->_db->conn("UPDATE ads_pre set cpm = $cpm WHERE for_rid = $rid AND city = '$city'"));
			}
		}
	}

}
