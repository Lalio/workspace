<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;
}

/**
 * @todo 56广告系统
 * @author bo.wang3
 * @version 2013-7-22 14:29
 */
Class System extends Mads{

	public $menulist;
	public $menu;
	
	public function __Construct(){
		parent::__Construct();
	}
	
	/**
 	 * @todo 日志系统
     * @author bo.wang3
     * @version 2013-7-22 14:29
     */
	public function ShowLog(){
		
		$this->BackendDbLogic('','admin_log','showlog','',' ORDER BY logtime DESC'); //功能切换、数据、数据表名、模版文件名
	}
	
	/**
	 * @todo 广告规则设定
	 * @author bo.wang3
	 * @version 2013-7-22 14:29
	 */
	public function RuleSet(){
		
		if($this->func == 'add'){
			
			for($i = 0; $i < count($_REQUEST['type']); $i++){
				$rule[] = array(
						'city' => $_REQUEST['city'][$i],
						'type' => $_REQUEST['type'][$i],
						'cid' => $_REQUEST['cid'][$i],
						'totaltime' => $_REQUEST['totaltime'][$i],
						'maxnum' => $_REQUEST['maxnum'][$i],
						'maxtime' => $_REQUEST['maxtime'][$i],
						);
				
				$repeat_test[] = $_REQUEST['type'][$i].$_REQUEST['city'][$i].$_REQUEST['cid'][$i].$_REQUEST['totaltime'][$i];
			}
			
			//广告规则去重  同一个城市、同一个广告位、同一个类型 不能有两套规则
			if(count($repeat_test) != count(array_unique($repeat_test))){
					echo json_encode(array('rs'=>1,'msg'=>'同一城市、同一广告位、同一投放类型不能同时具有两套投放规则。'));exit;
			}
			
			$data = array(
					'type' => $_REQUEST['tf_type'],
					'description' => $_REQUEST['description'],
					'ts' => date('Ymd',TIMESTAMP),
					'rule' => json_encode($rule)
			);
		}
	
		$this->BackendDbLogic($data,'ad_rules','ruleset'); //功能切换、数据、数据表名、模版文件名
	}
	
	/**
	 * @todo 广告投放黑名单
	 * 支持以以下维度屏蔽广告投放 
	 * VID
	 * @author bo.wang3
	 * @version 2013-7-22 14:29
	 */
	public function BlackList(){
		
		if($this->func == 'add'){
			
			if(!empty($_REQUEST['blackwords'])){
				$int = rtrim($_REQUEST['blackwords'],',');
				unlink('/home/bo.wang3/blacklist');
				$file_name = '/home/bo.wang3/blacklist';
			}else{
				$file_name = $_FILES['config_file']['tmp_name'];
				$int = file_get_contents($file_name);
				$int = rtrim($int,',');
			}
			
			//输入数据进行检查
			$int_a = explode(',', $int);

			foreach ($int_a as $v){
				
				if(empty($v)){
					go_win(2,'输入数据格式不合法！\n每个黑词应该使用英文逗号分隔，不得出现两个及两个以上连续的逗号。\nVID屏蔽必须是数字。');
					exit;
				}
				
				if($_REQUEST['type'] == 1 && !is_numeric($v)){
					go_win(2,'输入数据格式不合法！\n每个黑词应该使用英文逗号分隔，不得出现两个及两个以上连续的逗号。\nVID屏蔽必须是数字。');
					exit;
				}
			}
			
			//入Mysql
			$dbv = array(
				'type' => $_REQUEST['type'],
				'blackwords' => $int,
				'description' => $_REQUEST['description'],
				'status' => 0,
				'ts' => date('Ymd',TIMESTAMP)
			);
			
			$id = $this->_db->insert('ad_blacklist',$dbv);
			
			//写文件
			file_put_contents($file_name, $int);
			$this->PutFileIntoDb($file_name, 'blacklist', "blacklist_$id.txt" , $id , 'txt');
			
			go_win('./?action=System&do=BlackList&show=list','黑词库添加成功！');
			exit;
		}
		
		$this->BackendDbLogic('','ad_blacklist','blacklist','',' ORDER BY status ASC,id DESC'); //功能切换、数据、数据表名、模版文件名
	}
	
	/**
	 * @todo 启动广告投放黑名单
	 * @author bo.wang3
	 * @version 2013-7-22 14:29
	 */
	public function BlackListStart(){
		
		$this->_db->update('ad_blacklist',array('status' => 1),"id = $_REQUEST[id]");
		
		if($_REQUEST['type'] == "1"){
			
			$vids = $this->_db->rsArray("SELECT blackwords FROM ad_blacklist WHERE id = $_REQUEST[id]");
			$vids = explode(',', $vids['blackwords']);
			
			foreach ($vids as $v){
				$this->_re->redis()->set('novid'.$v,4);
			}
		}
		
		if($_REQUEST['type'] == "2"){
			$this->ReFreshKeyWordsBlackList();
		}
		
		echo json_encode(array('rs' => 0,'msg' => "投放黑名单$_REQUEST[id]屏蔽成功！"));
		exit;
	}
	
	
	/**
	 * @todo 停止广告投放黑名单
	 * @author bo.wang3
	 * @version 2013-7-22 14:29
	 */
	public function BlackListStop(){
		
		$this->_db->update('ad_blacklist',array('status' => 0),"id = $_REQUEST[id]");
		
		if($_REQUEST['type'] == "1"){
			
			$vids = $this->_db->rsArray("SELECT blackwords FROM ad_blacklist WHERE id = $_REQUEST[id]");
			$vids = explode(',', $vids['blackwords']);
				
			foreach ($vids as $v){
				$this->_re->redis()->delete('novid'.$v);
			}
		}
		
		if($_REQUEST['type'] == "2"){
			$this->ReFreshKeyWordsBlackList();
		}
		
		echo json_encode(array('rs' => 0,'msg' => "投放黑名单$_REQUEST[id]取消成功！"));
		exit;
	}
	
	/**
	 * @todo 重置投放屏蔽关键词
	 * @author bo.wang3
	 * @version 2013-7-22 14:29
	 */
	public function ReFreshKeyWordsBlackList(){
		
		$rss = $this->_db->dataArray('SELECT blackwords FROM ad_blacklist WHERE type = 2 AND status = 1');
		
		foreach($rss as $rs){
			$nokeywords .= str_replace(',', '|', $rs['blackwords']).'|';
		}
		
		$nokeywords = rtrim($nokeywords,'|');
		
		//$this->_re->redis()->set('nokeywords',mb_convert_encoding ( $nokeywords, 'UTF-8','Unicode'));
		$this->_re->redis()->set('nokeywords', $nokeywords);
	}
	
	/**
	 * @todo 显示投放屏蔽关键词
	 * @author bo.wang3
	 * @version 2013-7-22 14:29
	 */
	public function ShowKeyWordsBlackList(){
	
		var_dump($this->_re->redis()->get('nokeywords'));
	}
	
	/**
	 * @todo 显示屏蔽VID
	 * @author bo.wang3
	 * @version 2013-7-22 14:29
	 */
	public function ShowVidBlackList(){
	
		var_dump($this->_re->redis()->keys('novid*'));
	}
	
	/**
	 * @todo 客户管理
	 * @author bo.wang3
	 * @version 2013-4-22 14:29
	 */
	public function ClientManage(){
		
		$data = array(
				'vid' => $_REQUEST['vid'],
				'vname' => $_REQUEST['vname'],
				'lossrate' => $_REQUEST['lossrate'],
			);

		$where = "";
		if($this->func =='search'){
			//搜索配置
			foreach($_GET as $k => $v){
				if(!empty($v) && in_array($k, array('vname'))){
					$where .="$k LIKE '%$v%' and ";
				}
			}
			$where .= "1=1";
		}
		
		$this->BackendDbLogic($data,'client','clientmanage',$where,' ORDER BY vid DESC'); //功能切换、数据、数据表名、模版文件名
	}
	
	/**
	 * @todo 商业客户
	 * @author bo.wang3
	 * @version 2013-4-22 14:29
	 */
	public function CommercialClient(){
	
		$this->BackendDbLogic($_POST,'bclient','bclientmanage','',' ORDER BY id ASC'); //功能切换、数据、数据表名、模版文件名
	}
	
	/**
	 * @todo 广告位管理
	 * @author bo.wang3
	 * @version 2013-4-22 14:29
	 */
	public function CidManage(){
	
		$data = array(
				'cid' => $_REQUEST['cid'],
				'cname' => $_REQUEST['cname'],
				'type' => $_REQUEST['type'],
				'flag' => 0,
				'is_cpm' => 0
				);
		
		$this->BackendDbLogic($data,'channel','channelmanage','',' ORDER BY cid DESC'); //功能切换、数据、数据表名、模版文件名
	}
	
	/**
	 * @todo 获取数据库文档内容
	 * @author bo.wang3
	 * @version 2013-6-25 14:29
	 */
	public function GetDbFileContent(){
	
		//获取数据库文档内容
		$re = $this->_db->BinRsArray("SELECT bin_data FROM ad_document WHERE id = ".$_REQUEST['id']);
		echo $re['bin_data'];
		//header("Content-type: $re[file_type]");
		//echo $re['bin_data'];
	}
	
	/**
	 * @todo Tgid管理
	 * 对Tgid进行实时管理
	 * @author bo.wang3
	 * @version 2014-9-19 14:29
	 */
	public function Tgid(){

		//入Mysql
		$dbv = array(
				'id' => $_REQUEST['id'],
				'description' => $_REQUEST['description'],
				'tgid' => $_REQUEST['tgid'],
				'vid' => $_REQUEST['vid'],
				'aid1' => $_REQUEST['aid1'],
				'aid2' => $_REQUEST['aid2'],
		);
		
		if($this->func == 'add'){
			$dbv['status'] = 0;
		}
		
		if($this->func == 'start' || $this->func == 'stop'){
			
			$info = $this->_db->rsArray('SELECT tgid,vid FROM ad_tgid WHERE id = '.$_REQUEST['id']);
			
			//写redis
			if($this->func == 'start'){
				$this->_re->redis()->set('tgidvids_'.$info['tgid'], json_encode(explode(',',$info['vid'])));
			}elseif($this->func == 'stop'){
				$this->_re->redis()->delete('tgidvids_'.$info['tgid']);
			}
			
			if(false !== $this->_db->update('ad_tgid',array('status' => $this->func=='start'?1:0),"id = {$_REQUEST['id']}")){
				echo json_encode(array('rs' => 0,'msg' => "#$_REQUEST[id]状态切换成功！"));
			}else{
				echo json_encode(array('rs' => 1,'msg' => "#$_REQUEST[id]状态切换失败！".$this->_db->errorMsg));
			}
			exit;
		}
		
		$this->BackendDbLogic($dbv,'ad_tgid','tgid','',' ORDER BY status ASC,id DESC'); //功能切换、数据、数据表名、模版文件名
	}
	
	/**
	 * @todo Tgid管理
	 * 生成tgid统计报表
	 * @author bo.wang3
	 * @version 2014-9-19 14:29
	 */
	public function TgidReport(){
		
		$starttime = strtotime($_REQUEST['starttime']);
		$endtime = strtotime($_REQUEST['endtime']);
		$id = $_REQUEST['id'];
		
		if($starttime >= $endtime){
			echo json_encode(array('rs' => 1,'msg' => '时间区间设置不正确！'));
			exit;
		}
		
		//插入查量任务队列
		$db_data = array(
				'id' => '',
				'is_together' => 1,
				'info' => $_REQUEST['info'],
				'type' => 'pv',
				'cmd'  => intval($_REQUEST['cmd']),
				'starttime' => $starttime,
				'endtime' => $endtime,
				'aids' => trim($_REQUEST['aids']),
				'admin' => ADMIN,
				'ts'   =>  TIMESTAMP
		);
		$op_rs = $this->_db->insert('ad_puvamounts',$db_data);
		
		//查量写数据库
		$this->_db->conn("UPDATE ad_tgid SET report_ids = concat(report_ids,'$op_rs,') WHERE id = $id");
		
		$api_param = array(
				'aids' => str_replace(',', '|', $_REQUEST['aids']),
				'tgid' => trim($_REQUEST['tgid']),
				'cmd'  => 10,
				'starttime'  => $starttime,
				'endtime'  => $endtime,
				'is_together' => intval($_REQUEST['is_together']),
				'tid' => $op_rs
				);

		//请求接口
		file_get_contents('http://14.17.117.101/ad_t4ulog.php?'.http_build_query($api_param));
	}
	
	/**
	 * @todo 修复数据工具 中文编码未加反斜杠入库
	 * @author bo.wang3
	 * @version 2013-7-22 14:29
	 */
	public function SwitchJsonCode(){
		/*
		$rs = $this->_db->rsArray("SELECT * FROM admin_log WHERE aid = 15711 and logtime = '2014-05-04 14:29:56'");
		var_dump($rs);
		$rs['detail'] = iconv('utf8', 'gbk', $rs['detail']);
		//foreach($rs as $k=>$v){
			//$rs[$k] = iconv('utf8', 'gbk', $v);
		//};
		var_dump($rs);*/
		$tars = $this->_db->dataArray("select aid,logtime,detail from admin_log where detail regexp 'city\":\"u' or detail regexp 'area\":\"u' order by logtime desc");
		
		foreach($tars as $tar){
			$detail = json_decode($tar['detail'],true);
			
			foreach ($detail as $k=>$v){
				if(in_array($k, array('title','description','city','area','keyword','excludekeyword','excludearea'))){
					$detail[$k] = uni_decode(str_replace('u', '\u', $v),'gbk');
				}
			}
			foreach ($detail as $k=>$v){
				$detail[$k] = urlencode($v);
			}
			$detail = json_encode($detail);
			$detail = urldecode($detail);
			//var_dump($this->_db->conn("UPDATE admin_log SET detail = '$detail' WHERE aid = {$tar['aid']} and logtime = '{$tar['logtime']}'"));
			$sql .= "UPDATE admin_log SET detail = '$detail' WHERE aid = {$tar['aid']} and logtime = '{$tar['logtime']}';";
			//echo $this->_db->_errorMsg;
			//var_dump($this->_db->update('admin_log',array('detail' => $detail),"aid = {$tar['aid']} and logtime = '{$tar['logtime']}'"));
		}
		
		var_dump(file_put_contents('/home/bo.wang3/repair.sql', $sql));
	}
	
	/**
	 * @todo 输出所有的客户
	 * @author bo.wang3
	 * @version 2014-7-22 14:29
	 */
	public function OutPutClients(){
		
		echo '<table>';
			foreach($this->clients as $k=>$v){
				echo "<tr><td>$v</td></tr>";
			}
		echo '</table>';
	}

}
