<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;
}

/**
 * @todo 56广告系统库存查询子系统
 * @author bo.wang3
 * @version 2013-4-22 14:29
 */
Class Query	extends Mads{
	
	public $qtype;//查量类型

	public function __Construct(){
		
		parent::__Construct();
		
		$this->qtype = array( //查量类型
				1 => '按站内外来源查PV量',
				2 => '按站内外来源查UV量',
				3 => '按独立UV查量',
				4 => '联合频控UV查量',
				9 => '返量PV查询',
/* 				3 => '按频道',
				4 => '按小时',
				5 => '按月份',
				6 => '按日期',
				7 => '按省份',
				8 => '按城市' */
		);
		
	}

	public function Main(){
		$this->RealTime();
	}

	/**
 	 * @todo 输出日历
     * @author bo.wang3
     * @version 2013-4-22 14:29
     */
	public function RealTime(){
				
		$year = $_REQUEST['y']?$_REQUEST['y']:date('Y');  
		$month = $_REQUEST['m']?$_REQUEST['m']:date('n');
		$cid = $_REQUEST['t']?$_REQUEST['t']:5;   
  
		$startday = date('w',mktime(0,0,0,$month,1,$year)); //w参数返回给定日期是星期中的第几天，这里判定某月的1号是星期几
		$totalday = date('t',mktime(0,0,0,$month,1,$year)); //t参数返回给定月份所应有的天数
		$today = mktime(0,0,0,date('n'),date('j'),date('Y'));//今天的时间戳

		$week = array('星期日','星期一','星期二','星期三','星期四','星期五','星期六');
		$i = $j = 0;

		include template('cal','Query');
	}

	/**
	 * @todo 查量任务
	 * @author bo.wang3
	 * @version 2013-4-22 14:29
	 */
	public function Task(){
	
	    if($this->func == 'add' || $this->func == 'edit'){
	    	
	        $input = array(
                    'starttime' => strtotime($_REQUEST['starttime']),
                    'endtime' => strtotime($_REQUEST['endtime']),
                    'channel' => implode('_', $_REQUEST['channel']),
                    'area' => str_replace(',', '_', $_REQUEST['area']),
                    'cid' => $_REQUEST['adtype'],
	        		'type' => $_REQUEST['type'],
                    'freq_day' => $_REQUEST['freq_day'],
                    'freq_num' => $_REQUEST['freq_num'],
	                'display_hour' => implode('_', $_REQUEST['display_hour']),
	        		'keyword' => str_replace(' ', '_', $_REQUEST['keyword']),
	        		'client'     => $_REQUEST['client'],
	        		'totaltime' => $_REQUEST['totaltime'],
	        		'double_pk' => $_REQUEST['double_pk'],
	        		'version' => isset($_REQUEST['version'])?$_REQUEST['version']:2,
	        		'rid' => isset($_REQUEST['for_rid'])?intval($_REQUEST['for_rid']):'',
	        		'project' => 1
	        );
	        
	        if(empty($input['starttime']) || empty($input['endtime'])){
	        	echo json_encode(array('rs'=>1,'msg'=>'请指定查量区间！'));exit;
	        }
	         
	        if($input['starttime'] >= $input['endtime']){
	        	echo json_encode(array('rs'=>1,'msg'=>'开始时间不能大于或者等于结束时间！'));exit;
	        }
	        
	        if(($input['endtime'] - $input['starttime']) > 5184000){
	        	echo json_encode(array('rs'=>1,'msg'=>'查量区间不能超过六十天！'));exit;
	        }
	        
	        /*
	        $pre_s = $this->_db->rsArray("SELECT id FROM ad_checkamounts WHERE input = ' $input'");
	        if(!empty($pre_s)){
	            echo json_encode(array('rs'=>1,'msg'=>'相同条件的查量任务已存在，任务ID：'.$pre_s['id']));exit;
	        }
	        */
	        
	        $data = array(
                    'id' => $_REQUEST['id'],
                    'input' => json_encode($input),
                    'status' => $_REQUEST['status'],
                    'require_ts' => $_REQUEST['require_ts'],
                    'applicant' => $_REQUEST['applicant'],
	                'op_type' => isset($_REQUEST['op_type'])?$_REQUEST['op_type']:1,
	        		'for_rid' => $_REQUEST['for_rid']
	        );
	        
	        /*
	        $areas = explode(',',$_POST['area']);
	        $i = 0;
	        foreach($areas as $area){ //计算总任务数量  每个省的要把该省的所有城市叠加
	        	if(strstr($area, '省') || strstr($area, '自治区')){
	        		$key = str_replace('省', '', $area);
	        		$key = str_replace('自治区', '', $key);
	        		$i += count(Core::$vars['Area'][$key]) - 1;
	        	}else{
	        		$i++;
	        	}
	        }
	        $data['total_cell'] = (($input['endtime'] - $input['starttime'])/86400) * $i;
	        unset($i);
	        */
	        //以城市数量作为进度条参数
	        $data['total_cell'] = (substr_count($input['area'], '_')+1);
	    }
	    
	    if($this->show == 'add' && isset($_REQUEST['r_id'])){ //过渡查量单的特殊处理
	    	
	    	$id = trim($_REQUEST['r_id']);
	    	
    		$this->pagedata = $this->_db->rsArray("SELECT * FROM ad_reserve WHERE id = $id");
    		$this->pagedata['adtype'] = $this->pagedata['cid'];
    		$this->pagedata['area'] = explode("\n",$this->pagedata['area']);
    			
    		foreach ($this->pagedata['area'] as $v){
    			$a = explode("_",$v);
    			$area .= $a[0].',';
    		}
    		$this->pagedata['areas'] = rtrim($area,',');
    		$this->pagedata['totaltime'] = $this->pagedata['tp_time'];
	    }
	    
	    if($this->show == 'list'){
	    	$wherestr = isset($_REQUEST['r_id'])?" for_rid = ".$_REQUEST['r_id']:"detail = ''";
	    }
	    
	    $this->BackendDbLogic($data,'ad_checkamounts','checkamounts',$wherestr); //功能切换、数据、数据表名、模版文件名
	}
	
	/**
	 * @todo P/UV查量
	 * @author bo.wang3
	 * @version 2013-4-22 14:29
	 */
	public function Puv(){
		
		if($this->func == 'add'){
			
			$data = array(
					'id' => '',
					'is_together' => 1,
					'info' => $_REQUEST['info'],
					'type' => $_REQUEST['type'],
					'starttime' => strtotime($_REQUEST['starttime']),
					'endtime' => strtotime($_REQUEST['endtime']),
					'aids' => str_replace(',', '|', $_REQUEST['aids']),
					'admin' => ADMIN,
					'ts'   =>  TIMESTAMP
			);
			
			switch($_REQUEST['cmd']){
				case 1:
					$data['cmd'] = 1;
					$data['type'] = 'PV';
					$data['is_together'] = 0;
					break;
				case 2:
					$data['cmd'] = 1;
					$data['type'] = 'UV';
					$data['is_together'] = 0;
					break;
				case 3:
					$data['cmd'] = 2;
					$data['type'] = 'UV';
					$data['is_together'] = 1;
					break;
				case 4:
					$data['cmd'] = 2;
					$data['type'] = 'UV';
					$data['is_together'] = 0;
					break;
				case 9:
					$data['cmd'] = 9;
					$data['type'] = 'PV';
					$data['is_together'] = 1;
					break;
			}
			 
			if(!isset($data['starttime']) || !isset($data['endtime'])){
				echo json_encode(array('rs'=>1,'msg'=>'请指定查量区间！'));exit;
			}
			 
			if(($data['endtime'] - $data['starttime']) > 15552000){
				echo json_encode(array('rs'=>1,'msg'=>'查量区间不能超过180天！'));exit;
			}
			
			if($data['endtime'] < $data['starttime']){
				echo json_encode(array('rs'=>1,'msg'=>'查量结束时间不得小于开始时间！'));exit;
			}
			
			/* 			
 		    $pre_s = $this->_db->rsArray("SELECT id FROM ad_checkamounts WHERE input = ' $input'");
			 
			if(!empty($pre_s)){
				echo json_encode(array('rs'=>1,'msg'=>'相同条件的查量任务已存在，任务ID：'.$pre_s['id']));exit;
			} */
		}
		 
		$this->BackendDbLogic($data,'ad_puvamounts','puv','cmd not in (10,11)'); //功能切换、数据、数据表名、模版文件名
	}
	
	/**
	 * @todo 杀死查量进程
	 * @author bo.wang3
	 * @version 2013-4-22 14:29
	 */
	public function KillProcess(){
	   
	    $id = trim($_REQUEST['id']);
	    $this->_db->conn("UPDATE ad_checkamounts SET status = 4 WHERE id = $id");
	}
	
	/**
	 * @todo 获取查量结果 用于在WEB页面和邮件中显示 可对比计算损耗前后的差异
	 * @author bo.wang3
	 * @version 2013-4-22 14:29
	 */
	public function GetCheckAmountResult($task_id,$style = true){
		
		$chk_rs = str_replace(',', '<br>', $this->GetProcessedCheckAmountResult($task_id));
		
		if(!(ROLE == 'AE' || $this->do == 'CheckAmountResult')){
			
			$outputs = $this->_db->rsArray("SELECT output FROM ad_checkamounts WHERE id = $task_id");
			$outputs = explode(',', $outputs['output']);
			
			//原始数据
			foreach ($outputs as $output){
				$ori_chk_rs .= $output.'<br>';
			}
			$ori_chk_rs .= '-----------<br>';
		}
		
		if($style){
			return $ori_chk_rs."<font color='red'>$chk_rs</font>";
		}else{
			return $ori_chk_rs.$chk_rs;
		}
		
	}
	
	/**
	 * @todo 查量结果邮件发送脚本 成功发送 
	 * @author bo.wang3
	 * @version 2013-4-22 14:29
	 */
	public function CheckAmountResult(){
	    
	    //获取查完量的预订单
	    $rs = $this->_db->dataArray("SELECT * from ad_checkamounts where (status = 2 or status = 3) and msg_status = 0 and detail = ''");

	    foreach($rs as $data){

	        $input = json_decode($data['input'],true);
	        //频道转码
	        $input[channel] = explode('_', $input[channel]);
	        foreach($input[channel] as $channel){
	        	$channels .= $this->channel[$channel].'、';
	        }
	        
	        $require_ts = date("Y-m-d H:i:s",$data['require_ts']);
	        $exec_ts = $data['exec_ts'];
	        $end_ts = date("Y-m-d H:i:s",($data['require_ts']+$data['exec_ts']));
	        $starttime = date("Y-m-d",$input['starttime']);
	        $endtime = date("Y-m-d",$input['endtime']);
	        $type = $data['op_type'] == 0?'不包含在投、预定':'包含在投、预定';
	        $chk_rs = $this->GetCheckAmountResult($data['id']);//查量结果
	        
	        if($data['status'] == 3){
	        	
	            $to = "li.li7@renren-inc.com";
	            //邮件正文
	            $topic = "库存查量需求(ID:$data[id])查量出现异常！";
	            $message = <<<HTMLCODE
库存查量需求（需求编号：$data[id]）查量失败，细节如下：<br>
<p>
条件: <br/>
时间定向[$starttime - $endtime] <br/>
频道定向[$channels] <br/>
区域定向[$input[area]] <br/>
投放时间[$input[display_hour]]<br/>
</p>
<p>
结果：$chk_rs<br/><br/>
</p>
此需求提交于$require_ts，开始查询于$exec_ts，结束于$end_ts。
HTMLCODE;
	            
	        }else{
	            
	            $to = $this->GetEmailAdress($data['applicant']);
	            //邮件正文
	            $topic = "库存查量需求(ID:$data[id])已完成！";
	            $message = <<<HTMLCODE
尊敬的$data[applicant]，您的库存查量需求（需求编号：$data[id]，{$type}）已经完成：<br/><br/>
<p>
条件: <br/>
时间定向[$starttime - $endtime] <br/>
频道定向[$channels] <br/>
区域定向[$input[area]] <br/>
投放时间[$input[display_hour]]<br/>
</p>
<p>
结果：<br/>$chk_rs<br/>
</p>
此需求提交于{$require_ts}，结束查询于{$end_ts}，总共耗时{$exec_ts}秒。
HTMLCODE;
	        }
	        
	        //取消库存查量结果通知邮件
	        //$this->PushEmail($topic,$message,$to);
	        $rs = $this->_db->dataArray("UPDATE ad_checkamounts SET msg_status = 1 WHERE id = $data[id]");
	    }
	
	}
	
	/**
 	 * @todo 输出库存量饼状图JSON
     * @author bo.wang3
     * @version 2013-4-25 14:29
     */
	public function OfcPieJson(){
		
		load('./include/ofc/open-flash-chart.php');

		$title = $this->adstype[$_REQUEST['t']].'|'.date('Y年m月d日',$_REQUEST['ts']).'（'.date('Y-m-d H:i:s',TIMESTAMP).'）';
		$title = new title( $title );

		$pie = new pie();
		$pie->start_angle(35)
		    ->gradient_fill()
		    ->tooltip( '#val# of #total#<br>总库存量：1000000CPM' )
		    ->colours(
		        array(
		            '#66CCCC',    // <-- 预定
		            '#99CC33',    // <-- 剩余
		            '#FF0033',    // <-- 投放
		        ) );

		$pre = new pie_value(6000,'',''); //预定库存
		$pre->set_label('预订投放 6000CPM', '#336699', 15 );

		$surplus = new pie_value(8000,'',''); //剩余库存
		$surplus->set_label('剩余库存 8000CPM', '#336699', 15 );

		$valid = new pie_value(10000,'',''); //正式投放
		$valid->set_label('确定投放 10000CPM', '#336699', 15 );


		$pie->set_values( array($pre,$surplus,$valid) );

		$chart = new open_flash_chart();
		$chart->set_title( $title );
		$chart->add_element( $pie );
		$chart->set_bg_colour( '#FFFFFF' );

		echo $chart->toPrettyString();
	}

	/**
 	 * @todo 输出区域分布柱状图JSON
     * @author bo.wang3
     * @version 2013-4-25 14:29
     */
	public function OfcBarJson(){
		
		load('./include/ofc/open-flash-chart.php');

		$title = date('Y年m月d日',$_REQUEST['ts']).' - 剩余库存区域分布数据';
		$title = new title( $title );

		for( $i=0; $i<count($this->area); $i++){
		    $bar = new bar_value(mt_rand(0,10));
		    $bar->set_tooltip( 'Hello<br>#val#' );
		    $data[$i] = $bar;
		}

		$bar = new bar_sketch( '#81AC00', '#567300', 1 );
		$bar->set_values( $data );
		$bar->set_key( '单位：1000CPM | Generated at '. date('Y-m-d H:i:s',TIMESTAMP),12);

		$x = new x_axis();
		$x_labels = new x_axis_labels();
		$xdata = $this->area;
		$x_labels->set_labels($xdata);
		$x->set_labels($x_labels);

		$chart = new open_flash_chart();
		$chart->set_title( $title );
		$chart->add_element( $bar );
		$chart->set_bg_colour( '#FFFFFF' );
		$chart->set_x_axis($x);

		echo $chart->toPrettyString();
	}
	
	/**
	 * @todo 输出日历详情 前台异步调用
	 * @author bo.wang3
	 * @version 2013-4-22 14:29
	 */
	public function Statistics(){
	
		include template('cal_detail','Query');
	}

	/**
	 * @todo 按照AID输出广告展示、点击、独立IP趋势图
	 * @author bo.wang3
	 * @version 2013-12-11 14:29
	 * @param 时间戳
	 */
	public function VcHistory(){
		
		/* load('./include/ofc/open-flash-chart.php');
		
		//接收外部参数
		$aid = trim($_REQUEST['aid']);
		$type = $_REQUEST['type']?$_REQUEST['type']:view;
		
		//时间参数
		$y  = date('Y',TIMESTAMP);
		$m  = date('n',TIMESTAMP);
		$td = date('t',TIMESTAMP); //当月总天数
		
		$y_p  = date('Y',strtotime("last month"));
		$m_p  = date('n',strtotime("last month"));
		$td_p = date('t',strtotime("last month")); //上月总天数
		
		// generate some random data
		srand((double)microtime()*1000000);
		
		$data_1 = array();
		$data_2 = array(); 
		
		$totalday = $td >= $td_p ? $td:$td_p;
		
		for( $i=1; $i<=$totalday ; $i++ ){
			$rs1 = $this->_db->rsArray("SELECT sum($type) as total FROM ad_log WHERE aid = $aid AND year = $y AND month = $m AND day = $i");
			$data_1[] = empty($rs1['total'])?0:$rs1['total'];
			//echo "SELECT sum($type) as total FROM ad_log WHERE aid = $aid AND year = $y AND month = $m AND day = $i"."<br>";
			//echo "SELECT sum($type) as total FROM ad_log WHERE aid = $aid AND year = $y_p AND month = $m_p AND day = $i"."<br>";
			$rs2 = $this->_db->rsArray("SELECT sum($type) as total FROM ad_log WHERE aid = $aid AND year = $y_p AND month = $m_p AND day = $i");
			$data_2[] = empty($rs2['total'])?0:$rs2['total'];
		}

		$line_1 = new line();
		$line_1->set_default_dot_style('');
		$line_1->set_width( 1 );
		$line_1->set_colour( '#3399FF' );
		$line_1->set_values( $data_1 );
		$line_1->set_key( "$y.$m", 10 );
		
		$line_2 = new line();
		$line_2->set_default_dot_style('');
		$line_2->set_width( 2 );
		$line_2->set_colour( '#339933' );
		$line_2->set_values( $data_2 );
		$line_2->set_key( "$y_p.$m_p", 10 );
		
		$x = new x_axis();
		$x->set_colour('#d7e4a3'); //设置X轴线的颜色
		$x_labels = new x_axis_labels();
		for($i=1 ; $i<=$totalday ; $i++){
			$xdata[] = strval($i);
		}
		$x_labels->set_labels($xdata);
		$x->set_labels($x_labels);
		
		$y = new y_axis();
		$max = max($data_1) >= max($data_2)? max($data_1) : max($data_2); 

		$y->set_range( 0, $max , intval($max/4) );
		
		$chart = new open_flash_chart();
		$chart->set_title( new title( "广告[AID:$aid] 历史数据图  维度：$type" ) );
		$chart->set_x_axis( $x );
		$chart->set_y_axis( $y );
		$chart->set_bg_colour( '#FFFFFF' );
		$chart->add_element( $line_1 );
		$chart->add_element( $line_2 );
		
		echo $chart->toPrettyString(); */
		
		load('./include/ofc/open-flash-chart.php');
		
		//接收外部参数
		$aid = trim($_REQUEST['aid']);
		$type = $_REQUEST['type']?$_REQUEST['type']:view;
		$ts = $_REQUEST['ts']?$_REQUEST['ts']:TIMESTAMP;
		
		//时间参数
		$y  = !empty($_REQUEST['y'])?intval($_REQUEST['y']):date('Y',$ts);
		$m  = !empty($_REQUEST['m'])?intval($_REQUEST['m']):date('n',$ts);
		$d  = !empty($_REQUEST['d'])?intval($_REQUEST['d']):date('j',$ts);
		
		$y_y  = date('Y',($ts-86400));
		$y_m  = date('n',($ts-86400));
		$y_d  = date('j',($ts-86400));
		
		$data_1 = array();
		$data_2 = array();
		
		/* 		$totalday = $td >= $td_p ? $td:$td_p; */
		
		for( $i=0; $i<24 ; $i++ ){
			$rs = $this->_db->rsArray("SELECT $type FROM ad_log WHERE aid = $aid AND year = $y AND month = $m AND day = $d AND hour =$i");
			$data_1[] = empty($rs[$type])?0:intval($rs[$type]);
			$rs = $this->_db->rsArray("SELECT $type FROM ad_log WHERE aid = $aid AND year = $y_y AND month = $y_m AND day = $y_d AND hour =$i");
			$data_2[] = empty($rs[$type])?0:intval($rs[$type]);
			//echo "SELECT sum($type) as total FROM ad_log WHERE aid = $aid AND year = $y AND month = $m AND day = $i"."<br>";
			//echo "SELECT sum($type) as total FROM ad_log WHERE aid = $aid AND year = $y_p AND month = $m_p AND day = $i"."<br>";
			//$rs2 = $this->_db->rsArray("SELECT sum($type) as total FROM ad_log WHERE aid = $aid AND year = $y_p AND month = $m_p AND day = $d");
			//$data_2[] = empty($rs2['total'])?0:$rs2['total'];
		}
		
		$line_1 = new line();
		$line_1->set_default_dot_style('');
		$line_1->set_width( 1 );
		$line_1->set_colour( '#3399FF' );
		$line_1->set_values( $data_2 );
		$line_1->set_key( "$y_y-$y_m-$y_d", 10 );
		
		$line_2 = new line();
		$line_2->set_default_dot_style('');
		$line_2->set_width( 2 );
		$line_2->set_colour( '#339933' );
		$line_2->set_values( $data_1 );
		$line_2->set_key( "$y-$m-$d", 10 );
		
		$x = new x_axis();
		$x->set_colour('#d7e4a3'); //设置X轴线的颜色
		$x_labels = new x_axis_labels();
		for($i=0 ; $i<24 ; $i++){
			$xdata[] = strval($i);
		}
		$x_labels->set_labels($xdata);
		$x->set_labels($x_labels);
		
		$y = new y_axis();
		$max = max($data_1) >= max($data_2)? max($data_1) : max($data_2);
		
		$y->set_range( 0, $max , intval($max/4) );
		
		$chart = new open_flash_chart();
		$chart->set_title( new title( "广告[AID:$aid] 投放效果数据图  维度：$type" ) );
		$chart->set_x_axis( $x );
		$chart->set_y_axis( $y );
		$chart->set_bg_colour( '#FFFFFF' );
		$chart->add_element( $line_1 );
		$chart->add_element( $line_2 );
		
		echo $chart->toPrettyString();
	}
	
	/**
	 * @todo 按照AID输出广告展示、点击、独立IP柱状图
	 * @author bo.wang3
	 * @version 2013-12-11 14:29
	 * @param 时间戳
	 */
	public function VcHistoryZz(){
	
		load('./include/ofc/open-flash-chart.php');
	
		//接收外部参数
		$aid = trim($_REQUEST['aid']);
		$type = $_REQUEST['type']?$_REQUEST['type']:view;
		$ts = $_REQUEST['ts']?$_REQUEST['ts']:TIMESTAMP;
	
		//时间参数
		$y  = !empty($_REQUEST['y'])?intval($_REQUEST['y']):date('Y',$ts);
		$m  = !empty($_REQUEST['m'])?intval($_REQUEST['m']):date('n',$ts);
		$d  = !empty($_REQUEST['d'])?intval($_REQUEST['d']):date('j',$ts);
	
		$y_y  = date('Y',($ts-86400));
		$y_m  = date('n',($ts-86400));
		$y_d  = date('j',($ts-86400));
	
		$data_1 = array();
		$data_2 = array();
	
		for( $i=0; $i<24 ; $i++ ){
			$rs = $this->_db->rsArray("SELECT $type FROM ad_log WHERE aid = $aid AND year = $y AND month = $m AND day = $d AND hour =$i");
			$data_1[] = empty($rs[$type])?0:intval($rs[$type]);
			$rs = $this->_db->rsArray("SELECT $type FROM ad_log WHERE aid = $aid AND year = $y_y AND month = $y_m AND day = $y_d AND hour =$i");
			$data_2[] = empty($rs[$type])?0:intval($rs[$type]);
		}
	
		$line_1 = new line();
		$line_1->set_default_dot_style('');
		$line_1->set_width( 1 );
		$line_1->set_colour( '#3399FF' );
		$line_1->set_values( $data_2 );
		$line_1->set_key( "$y_y-$y_m-$y_d", 10 );
	
		$line_2 = new line();
		$line_2->set_default_dot_style('');
		$line_2->set_width( 2 );
		$line_2->set_colour( '#339933' );
		$line_2->set_values( $data_1 );
		$line_2->set_key( "$y-$m-$d", 10 );
	
		$x = new x_axis();
		$x->set_colour('#d7e4a3'); //设置X轴线的颜色
		$x_labels = new x_axis_labels();
		for($i=0 ; $i<24 ; $i++){
			$xdata[] = strval($i);
		}
		$x_labels->set_labels($xdata);
		$x->set_labels($x_labels);
	
		$y = new y_axis();
		$max = max($data_1) >= max($data_2)? max($data_1) : max($data_2);
	
		$y->set_range( 0, $max , intval($max/4) );
	
		$chart = new open_flash_chart();
		$chart->set_title( new title( "广告[AID:$aid] 投放效果数据图  维度：$type" ) );
		$chart->set_x_axis( $x );
		$chart->set_y_axis( $y );
		$chart->set_bg_colour( '#FFFFFF' );
		$chart->add_element( $line_1 );
		$chart->add_element( $line_2 );
	
		echo $chart->toPrettyString();
	}
	
	/**
 	 * @todo 输出统计数据
     * @author bo.wang3
     * @version 2013-4-22 14:29
     * @param 时间戳
     */
	private function _ShowData($ts){
		
		$sdata = "
		<ul>
			<li>预估库存：10000CPM</li> 
			<li>已被预定： 2000CPM</li> 
			<li>正式投放： 3000CPM</li>
			<li>剩余库存： 5000CPM</li>
		</ul>
		";

		return $sdata;
	}
	
	/**
	 * @todo 输出HTML5格式的流量统计
	 * @author bo.wang3
	 * @version 2013-4-22 14:29
	 */
	public function VchHistory(){
	
		include template('vchhistory','Query');
	}
	
}