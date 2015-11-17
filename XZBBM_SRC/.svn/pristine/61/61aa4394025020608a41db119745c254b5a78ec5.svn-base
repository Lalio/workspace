<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;
}

/**
 * @todo 56广告系统工具模块
 * @author bo.wang3
 * @version 2013-5-6 14:29
 */
Class Tackle extends Mads{

	public function __Construct(){
		parent::__Construct();
	}

	/**
     * @todo 投放区域数据
     * @author bo.wang3
     * @param $type prov 返回二维数组 city 返回某个省的一维数组
     * @return array
     * @version 2013-5-6 14:29
     */
    public function AreaData($prov){
        
        if(empty($prov)){
            foreach($this->area as $k=>$v){
                $rs[] = $k;
            }
        }else{
            $rs = Core::$vars['Area'][$prov];
        }
        return $rs;
    }

	/**
	 * @todo 获取区域省份信息
	 * @author bo.wang3
	 * @version 2013-5-6 14:29
	 */
	public function GetAreaInfo(){

		$prov = trim($_GET['prov']);

		if(!empty($prov)){
			echo json_encode($this->AreaData($prov));
		}else{
			echo json_encode($this->AreaData(''));
		}
		exit;
	}

	/**
	 * @todo 根据参数生成贴片广告设置命令
	 * @author bo.wang3
	 * @version 2013-5-6 14:29
	 */
	public function GetVadAvg(){

		$avg = $_POST['link_para'];
		$cmd = "$avg[tpl]$avg[show]$avg[sex]#sec_$avg[sec]";
		
		$cmd .= empty($avg[pub_show])?'':'#pub_show_'.$avg[pub_show];
		$cmd .= empty($avg[pub_freq])?'':'#pub_freq_'.$avg[pub_freq];
		$cmd .= empty($avg[level])?'':'#level_'.$avg[level];
		$cmd .= empty($avg[age_s])?'':'#age_'.$avg[age_s].'_'.$avg[age_n];
		$cmd .= empty($avg[time])?'':"#time_".$avg[time];
		$cmd .= empty($avg[freq])?'':"#freq_".(2880*$avg[freq]);

		echo $cmd;
		exit;
		
	}

	/**
	 * @todo 判定广告素材是否有效
	 * @author bo.wang3
	 * @version 2013-6-3 14:29
	 */
	public function MaterialValidity(){
		
		$rs = $this->UrlValidity(trim($_GET['url']));

		if($rs){
			echo json_encode(array('rs' => 0));
		}else{
			echo json_encode(array('rs' => 1 ,'msg' => $rs));		
		}
		
		exit;
	}

	/**
	 * @todo 获取合同信息
	 * @author bo.wang3
	 * @version 2013-6-6 14:29
	 */
	public function GetContractInfo(){

		$rs = $this->_db->dataArray('SELECT id,contract_id,remark,customer_id,day_cpm,starttime,endtime FROM ad_contract order by id desc');

		foreach($rs as $k => $v){
			$rs[$k]['customer'] = $this->clients[$rs[$k]['customer_id']];
		}

		if(!empty($rs)){
			echo json_encode($rs);
		}
		exit;
	}

	/**
	 * @todo 获取合同曝光代码城市信息
	 * @author bo.wang3
	 * @version 2013-6-6 14:29
	 */
	public function GetContractCode(){

		//此函数的部分处理方法参见 app.Order.php 文件的 XlsProcess 方法
		$rs = $this->_db->rsArray('SELECT * FROM ad_contract WHERE id = '.$_REQUEST['id']);

		$data['startdatetime'] = date('Y-m-d H:i:s',$rs['starttime']);
		$data['starttime_year'] = date('Y',$rs['starttime']);
		$data['starttime_month'] = date('n',$rs['starttime']);
		$data['starttime_day'] = date('j',$rs['starttime']);
		
		$data['enddatetime'] = date('Y-m-d H:i:s',$rs['endtime']);
		$data['endtime_year'] = date('Y',$rs['endtime']);
		$data['endtime_month'] = date('n',$rs['endtime']);
		$data['endtime_day'] = date('j',$rs['endtime']);
		
		$data['ad_type'] = $rs['ad_type'];
		$data['ad_sub_type'] = $rs['ad_sub_type'];
		$data['customer_id'] = $rs['customer_id'];
		$data['title'] = $rs['title'];
		$data['description'] = $rs['description'];
		$data['day_cpm'] = $rs['day_cpm'];
		
		$rs['monitor_code'] = $rs['monitor_code']; //取出数据
		$data['monitor_code'] = json_decode($rs['monitor_code'],true); //解码
		
		if($data['monitor_code'][1][1] == '投放备注信息'){
			
			//新排期表
			$data['pqv'] = 2;
			
			unset($data['monitor_code'][1][1]);
			unset($data['monitor_code'][1][2]);
			unset($data['monitor_code'][1][3]);
			unset($data['monitor_code'][1][4]);
			unset($data['monitor_code'][1][5]);
			$date = $data['monitor_code'][1]; //获得排期表头
			unset($data['monitor_code'][1]); //去掉头
			
			foreach ($data['monitor_code'] as $k => $v) {
					
				$citycode['info'] = $v[1]; //子合同信息
				$citycode['idf'] = $k; //数据对应识别码，与位置相关，要求每一条数据不能改动位置
					
				$citycode['city'] = $v[2]; //全国通投必须写 中国
				$citycode['viewurl'] = $v[3]; //曝光监测代码
				$citycode['clickurl'] = $v[4]; //点击监测代码
				$citycode['flvurl'] = $v[5]; //素材地址
					
				//获得排期内容
				unset($data['monitor_code'][$k][1]);
				unset($data['monitor_code'][$k][2]);
				unset($data['monitor_code'][$k][3]);
				unset($data['monitor_code'][$k][4]);
				unset($data['monitor_code'][$k][5]);
				$sch = $data['monitor_code'][$k];
				$sch_final = array_combine($date, $sch);
				
				//排期表
				$citycode['schedule'] = json_encode($sch_final);
				//当天的CPM投放量
				$citycode['today_cpm'] = array_key_exists(date('m/d/Y',TIMESTAMP), $sch_final)?$sch_final[date('m/d/Y',TIMESTAMP)]:-1;
				$data['citycode'][] = $citycode;
			}
			
		}else{
			
			//旧排期表
			$data['pqv'] = 1;
			
			$rs['monitor_code'] = stripcslashes($rs['monitor_code']); //取出数据
			$data['monitor_code'] = json_decode($rs['monitor_code'],true); //解码
			
			unset($data['monitor_code'][1][1]);
			unset($data['monitor_code'][1][2]);
			unset($data['monitor_code'][1][3]);
			unset($data['monitor_code'][1][4]);
			$date = $data['monitor_code'][1]; //获得排期表头
			unset($data['monitor_code'][1]); //去掉头
				
			foreach ($data['monitor_code'] as $k => $v) {
					
				$citycode['city'] = $data['monitor_code'][$k][1]; //全国通投必须写 中国
				$citycode['viewurl'] = $data['monitor_code'][$k][2]; //曝光监测代码
				$citycode['clickurl'] = $data['monitor_code'][$k][3]; //点击监测代码
				$citycode['flvurl'] = $data['monitor_code'][$k][4]; //素材地址
					
				//获得排期内容
				unset($data['monitor_code'][$k][1]);
				unset($data['monitor_code'][$k][2]);
				unset($data['monitor_code'][$k][3]);
				unset($data['monitor_code'][$k][4]);
				$sch = $data['monitor_code'][$k];
				$sch = array_combine($date, $sch);
				//当天的CPM投放量
				$citycode['today_cpm'] = array_key_exists(date('m/d/Y',TIMESTAMP), $sch)?$sch[date('m/d/Y',TIMESTAMP)]:-1;
				//排期表
				$citycode['schedule'] = json_encode($sch);
				$data['citycode'][] = $citycode;
			}
			
		}
		
		unset($data['monitor_code']);
		echo json_encode($data);
		exit;
	}

	/**
	 * @todo 获取广告模板信息
	 * @author bo.wang3
	 * @version 2013-6-6 14:29
	 */
	public function GetTemplateInfo(){

		$rs = $this->_db->dataArray('SELECT t_name,t_url FROM ad_templates WHERE state = 0');

		if(!empty($rs)){
			echo json_encode($rs);
		}
		exit;
	}

		/**
	 * @todo 批量暂停|恢复投放
	 * @author bo.wang3
	 * @version 2013-6-6 14:29
	 */
	public function MultiStopRestart(){

		$switch = isset($_GET['switch'])?$_GET['switch']:'stop';
		$aids = rtrim($_GET['aids'],','); //去除字符串的最后一个逗号
		$aid_arr = explode(',',rtrim($aids));
		
		foreach ($aid_arr as $aid){
			
			$sig = gc_sig();

			if($switch == 'stop'){
				$v = array('signature' => $sig,'enable' => 0);
				$this->_db->update('ads',$v,'aid = '.$aid);
				$log = "[停止广告] id=$aids";
			}else{
				$v = array('signature' => $sig,'enable' => 1);
				$this->_db->update('ads',$v,'aid = '.$aid);
				$log = "[恢复广告] id=$aids";
			}
			
			$username = ADMIN;
			$detail = json_encode($v);
			
			$w_rs = $this->WriteLog($username, $log, $aid, $detail);
			if($w_rs['rs'] === false){
				echo $log['msg'];
				exit;
			}
		}
		
		echo json_encode(array('rs' => 0));
		exit;
	}

	/**
	 * @todo 修改每日的CPM投放量
	 * @author bo.wang3
	 * @version 2013-5-6 14:29
	 */
	public function ChangePerCpm(){

		$aid = trim($_REQUEST['aid']);
		$daytime = trim($_REQUEST['daytime']);
		$cpm = trim($_REQUEST['cpm']);

		$rs = $this->_db->conn("update ads_ext set cpm = '$cpm' where aid = '$aid' and daytime = '$daytime'");

		if($rs !== false){
			echo json_encode(array('rs' => 0));
		}else{
			echo json_encode(array('rs' => 1));
		}
		exit;

	}

	/**
	 * @todo 广告客户模糊匹配
	 * @author bo.wang3
	 * @version 2013-5-6 14:29
	 */
	public function ClientFuzzyMatch(){

		$key = $_REQUEST['encode']=='gbk'?iconv('gbk', 'utf8', trim($_REQUEST['key'])):trim($_REQUEST['key']); //获取关键字
		if(empty($key)) exit;
		$rs = $this->_db->dataArray("SELECT vid,vname FROM client WHERE vid = '$key' OR vname LIKE '%$key%' LIMIT 0,30");
		echo json_encode($rs);
		exit;
	}

	/**
	 * @todo 广告位模糊匹配
	 * @author bo.wang3
	 * @version 2013-5-6 14:29
	 */
	public function AdTypeFuzzyMatch(){

		$key = $_REQUEST['encode']=='gbk'?iconv('gbk', 'utf8', trim($_REQUEST['key'])):trim($_REQUEST['key']); //获取关键字
		if(empty($key)) exit;
		$rs = $this->_db->dataArray("SELECT cid,cname FROM channel WHERE flag = 0 AND (cid = '$key' OR cname LIKE '%$key%') limit 0,30");
		echo json_encode($rs);
		exit;
	}

	/**
 	 * @todo 销毁XML临时文件
     * @author bo.wang3
     * @version 2013-07-09 14:29
     */
	public function DestoryPreViewXml(){

		$dir = "../view_ad/";
		$handle = opendir($dir);

		if($handle){
			while(false !== ($xml_file = readdir($handle))){
				if(!is_dir($xml_file)){
					unlink($dir.$xml_file);
				}
			}
		}else{
			exit('操作XML目录失败，请检查是否具备权限！');
		}
	}
	
	/**
	 * @todo 记录状态切换
	 * @author bo.wang3
	 * @version 2013-6-27 14:29
	 */
	public function StateSwitch(){
	
	    $id = trim($_REQUEST['id']);
	    $table = trim($_REQUEST['table']);
	    
	    $state = trim($_REQUEST['state']);
	    $newstate = ($state + 1)%2;
	    
	    //兼容多种可能
	    $this->_db->conn("UPDATE $table SET state = $newstate WHERE id = $id");
	    $this->_db->conn("UPDATE $table SET status = $newstate WHERE id = $id");
	    $this->_db->conn("UPDATE $table SET flag = $newstate WHERE cid = $id");
	    
	    echo json_encode(array('rs' => 0,'newstate' => $newstate));
	}
	
	/**
	 * @todo 查询库存查量单状态
	 * @author bo.wang3
	 * @version 2013-9-16 14:29
	 */
	public function GetAmountQueryState(){
	
	    $id = trim($_REQUEST['id']);
	   
	    $rs = $this->_db->rsArray("SELECT status,total_cell,already_cell FROM ad_checkamounts WHERE id = $id limit 1");
	
	    if($rs !== false){
	        echo json_encode(array('rs' => 0,'status' => $rs['status'],'rate' => (int)($rs['already_cell']/($rs['total_cell']+1)*100)));
	    }else{
	        echo json_encode(array('rs' => 1,'msg' => $this->_db->_errorMsg));
	    }
	}
	
	/**
	 * @todo 外部写日志接口 使用HTTP方式调用
	 * @author bo.wang3
	 * @version 2013-9-16 14:29
	 */
	public function InputLog(){
	    
	    $aid = trim($_REQUEST['aid']);
	    $username = trim($_REQUEST['username']);
	    $log = $_REQUEST['log'];
	    $detail = $_REQUEST['detail'];
	
	    $log = $this->WriteLog($username, $log, $aid, $detail);
	    
	    if($log['rs'] === false){
	        echo $log['msg'];
	        exit;
	    }
	}

	/**
	 * @todo 根据广告位置切换广告类型
	 * @author bo.wang3
	 * @version 2013-9-16 14:29
	 */
	public function Cid2Type(){
	    
        $cid  = $_REQUEST['cid']; 	    
	    $type = Core::$vars['Type1'];  //初始化广告投放类型
	    
	    //初始化位置-类型对应数组
	    $cid2type = array(
    		10	=> '4,6',
    		11	=> '4,6',
    		13	=> '4,6',
    		30	=> '4,6',
    		89	=> '4,6',
    		24	=> '4,6',
    		59	=> '4,6',
    		63	=> '4,6',
    		64	=> '4,6,8',
    		72	=> '4,6',
            111	=> '1,21,2,3,37,26,32',
            114	=> '1,21,2,3,37,26,32',
	    	120	=> '4,6,8',
	    	124	=> '4,6,8',
            137	=> '1,21,2,3,37,26,32',
            170	=> '1,21,2,3,37,26,31,32',
	    	169 => '8',
            175	=> '8',
            134	=> '8',
            135	=> '8',
            147	=> '8',
            148	=> '8',
            150	=> '8',
            154	=> '8',
            157	=> '8',
            146	=> '4,6',
            156	=> '4,6',
	    	167 => '1,21',
            177	=> '4,6',
            178	=> '4,6',
            179	=> '4,6',
	    	180 => '4,6',
	    	181	=> '4,6',
	    	183	=> '4,6',
	    	184	=> '4,6',
	    	186	=> '8',
	    	187	=> '4,6',
	    	188	=> '4,6',
	    	190	=> '4,6,8',
	    	193	=> '4,6',
	    	194	=> '4,6',
	    	195	=> '4,6',
	    	196	=> '4,6',
	    	197	=> '4,6',
	    	198	=> '4,6',
	    	199	=> '4,6',
	    	200	=> '4,6',
	    	201	=> '4,6',
	    	202	=> '4,6',
	    	203	=> '4,6',
	    	204	=> '4,6',
	    	205	=> '4,6',
	    	206	=> '4,6',
	    	207	=> '4,6',
	    	208	=> '4,6',
	    	209 => '1,21',
	    	210 => '1,21',
	    	211 => '1,21',
	    	212 => '1,21',
	    	213 => '43',
	    	214 => '44',
	    	215 => '44',
	    	216 => '44',
	    	217 => '44',
	    	218 => '44',
	    	219 => '44',
	    	220 => '44',
	    	221 => '8',
	    	222 => '44',
	    	223 => '44',
	    	224 => '44',
	    	225 => '44',
	    	226 => '44',
	    	227 => '44',
	    	228 => '44',
	    	239 => '8',
	    	240 => '8',
	    	241 => '8',
	    	250 => '4',
	    	251 => '4',
	    	252 => '4',
	    	253 => '4',
	    	254 => '4',
	    	255 => '4',
	    	256 => '4',
	    	257 => '4'
	            ); 
	    
	    $t = array_key_exists($cid, $cid2type)?explode(',',$cid2type[$cid]):array_keys($type);
	    
	    foreach ($t as $v){
	        $rs[] = array('id' => $v,'name' => $type[$v]);
	    }
	    
	    echo json_encode($rs);
	    exit;
	}
	
	/**
	 * @todo 批量添加贴片广告命令
	 * @author bo.wang3
	 * @version 2013-9-16 14:29
	 */
	public function MultiAddCmd(){
	     
	    $cmd  = $_REQUEST['cmd'];
	    $aids  = $_REQUEST['aids'];
	    $aid_arr = explode(',',$aids);
	    
	    foreach ($aid_arr as $aid){
	        $link[$aid] = $this->_db->rsArray("SELECT link FROM ads WHERE aid = $aid LIMIT 0,1");
	        //提取原有命令串中不包含cmd的部分
	        $cmd_arr = explode("#",$link[$aid][link]);
	        foreach($cmd_arr as $k => $v){
	    		if(false !== strstr($v,ltrim($cmd,"#"))){
	    			unset($cmd_arr[$k]);
	    		}
	        }
	        //拼装回命令串
	        $link[$aid] = implode('#',$cmd_arr);
	        unset($cmd_arr);
	    }
	    
	    foreach ($aid_arr as $aid){
	    	$cmd = $cmd."_$aid";
	    } 
	       
	    foreach ($aid_arr as $aid){
	        $arr = array(
	                'link' => $link[$aid].$cmd
	                );
	        $link[$aid] = $this->_db->update("ads",$arr,"aid = $aid");
	        $this->WriteLog(ADMIN, '批量广告增加控制参数' , $aid, json_encode($arr));
	    }
	     
	    echo json_encode(array('rs' => 0,'msg' => "AID($aids)添加参数 $cmd 成功!"));
	    exit;
	}
	
	
 	/**
	 * @todo 批量数据自动转换工具 Excel->TextArea
	 * @author bo.wang3
	 * @version 2013-7-8 14:29
	 **/
	public function InputSwitchForm(){
		echo <<<HTMLCODE
			<form action="./?action=Tackle&do=InputSwitchProcess&id=$_REQUEST[id]&symbol=$_REQUEST[symbol]" method="POST" enctype="multipart/form-data">
				<input type="file" name="content" size="10"><input type="submit" value="导入Excel文件">
			</form>
HTMLCODE;
		
		echo "<script type=text/javascript>window.parent.inputswitch();</script>";
	}
	
	/**
	 * @todo 投放时间间隔选择批量处理
	 * @author bo.wang3
	 * @version 2013-4-22 14:29
	 **/
	public function InputSwitchProcess(){
		
		$symbol = $_REQUEST['symbol'];
		$id = $_REQUEST['id'];
	
		//解析文档
		$data = new Spreadsheet_Excel_Reader($_FILES['content']['tmp_name']);
		$content = $data->sheets[0]['cells'];
		if(!is_array($content)){
			alert('目标文档无法被系统识别，请确认文档类型为.XLS，如有疑问请与开发人员联系。');
			$this->InputSwitchForm();
			exit;
		}
		
		foreach ($content as $data){
			$word .= implode($symbol, $data).'\r\n';
		}
		
		$word = rtrim($word,'\r\n');
			
		echo "<script type=text/javascript>window.parent.inputswitch('{$id}','{$word}');</script>";
		$this->InputSwitchForm();
	}
	
	/**
	 * @todo P/UV查量结果
	 * @author bo.wang3
	 * @version 2013-4-22 14:29
	 */
	public function PuvResult(){
	
		$idf = $_REQUEST['idf'];
	
		if(true === $this->UrlValidity("http://14.17.117.101/result/$idf.xls")){
			$rs = array('rs' => 0);
		}else{
			$rs = array('rs' => 1);
		}
	
		echo json_encode($rs);
	}
	
	/**
	 * @todo 自动同步种子视频播放、评论数，将链接地址改成ACS地址便于统计
	 * @author bo.wang3
	 * @version 2013-4-22 14:29
	 */
	public function ZzSync(){
		
		$ads = $this->_db->dataArray('SELECT aid,google,flash FROM ads WHERE cid BETWEEN 214 and 220');
		
		foreach($ads as $ad){
			
			$ad['google'] = json_decode($ad['google'],true);
			
			$vid = g::getUrlId($ad['flash']);
			$vinfo = g::GetIdsVideo($vid,'times,comment');
			$vinfo = $vinfo[$vid];
			
			$ad['google']['views'] = $vinfo['times']?$vinfo['times']:$ad['google']['views'];
			$ad['google']['comments'] = $vinfo['comment']?$vinfo['comment']:$ad['google']['comments'];
			/* 改由dumpfile逻辑实现
			if(!strstr($ad['google']['img_url'],'http://acs.56.com/redirect/view/')){
				$ad['google']['img_url'] = "http://acs.56.com/redirect/view/{$ad[aid]}/".$ad['google']['img_url'];
			}
			
			if(!strstr($ad['google']['img_link'],'http://acs.56.com/redirect/click/')){
				$ad['google']['img_link'] = "http://acs.56.com/redirect/click/{$ad[aid]}/".$ad['google']['img_link'];
			}
			
			if(!strstr($ad['google']['title_link'],'http://acs.56.com/redirect/click/')){
				$ad['google']['title_link'] = "http://acs.56.com/redirect/click/{$ad[aid]}/".$ad['google']['title_link'];
			}
			*/
			$this->_db->update('ads',array('google' => addslashes(json_encode($ad['google']))),'aid = '.$ad['aid']);
		}
		
	}
	
	/**
	 * @todo 生成柱状图
	 * @author bo.wang3
	 * @version 2013-4-22 14:29
	 */
	public function GerZPic(){
	
		$str = trim($_REQUEST['str']);
		echo h24($str);
	}
	
	/**
	 * @todo 解析txt文件的内容并填入指定id的容器
	 * @author bo.wang3
	 * @version 2014-9-19 14:29
	 **/
	public function ParsingTxt(){
		
		if(empty($_FILES['txtfile']['tmp_name'])){
			echo <<<HTMLCODE
			<form action="./?action=Tackle&do=ParsingTxt&dom_id={$_REQUEST['dom_id']}" method="POST" enctype="multipart/form-data">
				<input type="file" name="txtfile" size="10"><input type="submit" value="导入文本文件">
			</form>
HTMLCODE;
		}else{
			$ctt = mb_convert_encoding( file_get_contents($_FILES['txtfile']['tmp_name']), 'UTF-8', 'UTF-8,GBK,GB2312,BIG5' ); 
			$ctt = trim($ctt);
			echo <<<HTMLCODE
			<script type="text/javascript">
			  window.parent.document.getElementById('{$_REQUEST['dom_id']}').value='{$ctt}';
			</script>
			<form action="./?action=Tackle&do=ParsingTxt&dom_id={$_REQUEST['dom_id']}" method="POST" enctype="multipart/form-data">
				<input type="file" name="txtfile" size="10"><input type="submit" value="导入文本文件">
			</form>
HTMLCODE;
		}
	}
	
	/**
	 * @todo  生成BINLOG日志
	 * @author bo.wang3
	 * @version 2014-5-15 14:29
	 */
	public function ReadBinLog(){
		
		$hour = intval($_REQUEST['hour']);
		
		if($hour == 10){
			$stime = date('Y-m-d H:i:s',(TIMESTAMP-(3600*8+300))); //8个小时为一个检查区间
		}else{
			$stime = date('Y-m-d H:i:s',(TIMESTAMP-(3600*1+300))); //1个小时为一个检查区间
		}
		
		$etime = date('Y-m-d H:i:s',(TIMESTAMP-(3600*0+300)));
		
		//获取当前日志
		$logs = $this->_db->dataArray("show master logs");
		$log_path1 = "/diska/mysqldata/".$logs[count($logs) - 1]['Log_name'];
		$log_path2 = "/diska/mysqldata/".$logs[count($logs) - 2]['Log_name'];
		
		//修改日志目录权限
		//exec('chmod -R 777 /diska/mysqldata',$rs);
		
		//获取binlog日志
		exec("mysqlbinlog $log_path1 -s --start-datetime='{$stime}' --stop-datetime='{$etime}' | grep '\bads\b' | grep '\bsignature\b'",$ads_bin1);
		exec("mysqlbinlog $log_path2 -s --start-datetime='{$stime}' --stop-datetime='{$etime}' | grep '\bads\b' | grep '\bsignature\b'",$ads_bin2);
		
		foreach($ads_bin1 as $v){
			$ads_bin[] = $v;
		}
		
		foreach($ads_bin2 as $v){
			$ads_bin[] = $v;
		}
		
		$tj = array('insert' => 0,'update' => 0 , 'replace' => 0 , 'total' => 0 , 'ori_total' => count($ads_bin) , 'in' => 0);
		
		//过滤不需匹配的ads_log
		foreach($ads_bin as $k => $v){
			
			if(!strstr($v,'signature')){
				unset($ads_bin[$k]);
				continue;
			};
			
			$ads_bin[$k] = iconv('GBK','utf8',$v);
		}
		
		//开始检查
		$error_record = array(); //错误记录数组
		
		foreach($ads_bin as $k => $v){
			
			preg_match("/[A-F0-9]{32,32}/", $v, $matches);
			$sig = $matches[0]; //正则匹配出签名
			
			$rs = $this->_db->rsArray("SELECT aid FROM admin_log WHERE detail LIKE '%$sig%'");
			if(empty($rs)){
				$error_record[] = $v;
			}
			
			$sql_parse = explode(' ',$v);
			//插入统计
			if(in_array($sql_parse[0],array('insert','INSERT'))){
				$tj['insert'] ++;
			}
			//修改统计
			if(in_array($sql_parse[0],array('update','UPDATE'))){
				$tj['update'] ++;
			}
			//修改统计
			if(in_array($sql_parse[0],array('replace','REPLACE'))){
				$tj['replace'] ++;
			}
			//批量暂停、开始统计
			if($sql_parse[6] == 'IN'){
				$tj['in'] ++;
				//$tj['total'] += (substr_count($sql_parse[7],",") + 1);
			}
			//else{
				$tj['total'] ++;
			//}
			unset($sql_parse);
		}
		
		$body = <<<HTMLCODE
 			<h4>监测区间： {$stime}-{$etime}</h4>
 			<p>BINLOG日志：{$log_path1} + {$log_path2}</p>
 			<p>属于MADS系统的ads表操作记录(包含signature字段)：<strong> {$tj['total']} </strong>条<br>
 			   -- insert语句： {$tj['insert']} 条<br>
 			   -- update语句： {$tj['update']} 条 （包含批量修改语句  {$tj['in']} 条）<br>
 			   -- replace语句： {$tj['replace']} 条<br>
 			</p>
 			<!--<p>属于MADS系统的admin_log操作记录：<strong> {$tj['total']} </strong>条<br>-->
 			</p>
HTMLCODE;
		
 		if(empty($error_record)){
 			$footer = '<p>ads新增、修改记录均有对应的admin_log记录，没有发现入库异常。</p>';
 		}else{
 			$footer = '<p>发现入库异常,以下ads操作语句没有admin_log记录：</p>';
 			foreach($error_record as $data){
 				$footer .= '<p>'.$data.'</p>';
 			}
 		}
 		
 		$ctc = $body.$footer;
 		
 		if($hour == 10){ //发日报
 			var_dump($this->PushEmail('admin_log监控日报',$ctc,'bo.wang3','','junfeng.deng'));
 		}elseif(!empty($error_record)){ //发小时报警
 			var_dump($this->PushEmail('【报警】admin_log小时监控报告',$ctc,'bo.wang3','',''));
 		}
 		
	}
	
}