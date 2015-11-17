<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
td{
	text-align: left;
}
body {
    font-size: 14pt;
}
table tr{
	border: 1px solid #525C3D;
}
</style>
</head>
<body>
<table>
	<tr>
		<th><a>预订单ID</a></th>
		<th><a>客户名称（品牌/产品线）</a></th>
		<th><a>投放类型</a></th>
		<th><a>投放周期</a></th>
		<th><a>查量时间段</a></th>
		<th><a>订单流程</a></th>
		<th><a>AE</a></th>
		<th><a><?= $_GET['area']?> 时间段量（CPM）</a></th>
		<th><a><?= $_GET['area']?> 总投放量（CPM）</a></th>
		<th><a>占比</a></th>
	</tr>
	<?php foreach ($this->pagedata['list'] as $data) {
		$cinfo = $this->_db->rsArray("SELECT id FROM ad_contract WHERE reserve_code = '".$data['reserve_code']."' LIMIT 0,1");
	?>
	<tr>
		<td><b>#<?= $data['id']?></b></td>
		<td><?= $data['client']?$this->clients[$data['client']]:$data['agent']?>（<?= $data['brand']?>）</td>
		<td><?= $this->adstype[$data['cid']]?></td>
		<td>开始：<?= date('Y年m月d日',$data['starttime'])?><br>结束：<?= date('Y年m月d日',$data['endtime'])?></td>
        <td><b>开始：<?= date('Y年m月d日',strtotime($_REQUEST['starttime']))?><br>结束：<?= date('Y年m月d日',strtotime($_REQUEST['endtime']))?></b></td>
        <td><p><?php
        	switch($data['status']){
        		case 0 : echo '<font color="green">已保存<font>';break;
        		case 1 : echo '<font color="blue">已提交<font>';break;
        		case 2 : echo '<font color="red">已审核<font>';break;
        		case 3 : echo '<font color="orange">撤回中<font>';break;
        		default: 
        		         if(TIMESTAMP >= $data['endtime']){
        					echo '<font color="black">投放结束<font>';
        				 }else{
							switch($data['status']){
								case 4 : echo '<font color="green">准备投放<font>';break;
								case 5 : echo '<font color="blue">正在投放<font>';break;
							}
						 }
				break;
        	}
        ?></p></td>
        <td><p><?= $data['ae']?></p></td>
		<td><p><?php
			if(!empty($_GET['area'])){
				preg_match("+$_GET[area]_\d*+", $data[area], $match);
				$match = explode('_', $match[0]);
				$cpm = $match[1]; 
			}else{
				$area_arr =explode("\n", trim($data[area]));
				foreach ($area_arr as $a){
					$a = explode("_",$a);
					$cpm += intval($a[1]);
				}
			}
			
			/*
			$d_days = intval(($data['endtime'] - $data['starttime'])/86400);
			$d_days += 1;
				
			if(!empty($_GET['starttime']) && !empty($_GET['endtime'])){
				if($data['starttime'] >= $this->r_starttime && $data['endtime'] <= $this->r_endtime){
					$s_days = intval(($data['endtime'] - $data['starttime'])/86400);
				}elseif($data['starttime'] <= $this->r_starttime && $data['endtime'] >= $this->r_endtime){
					$s_days = intval(($data['endtime'] - $data['starttime'])/86400);
				}elseif($data['starttime'] <= $this->r_starttime && $data['endtime'] <= $this->r_endtime){
					$s_days = intval(($data['endtime'] - $this->r_starttime)/86400);
				}elseif($data['starttime'] >= $this->r_starttime && $data['endtime'] >= $this->r_endtime){
					$s_days = intval(($this->r_endtime - $data['starttime'])/86400);
				}
					$s_days += 1;
			}else{
				$s_days = $d_days;
			}
			
			echo $s_days;echo $d_days;
			*/
			
			$nd_days = pre_dates($_GET['starttime'],$_GET['endtime']);
			$tt_days = pre_dates($data['starttime'],$data['endtime']);
			
			$ra = count(array_intersect($nd_days, $tt_days)) == 0?1:(count(array_intersect($nd_days, $tt_days))/count($tt_days));
			
			echo intval($cpm*$ra);
		?></p>
		</td>
		<td><p><?php
			echo $cpm;
			unset($cpm);
		?></p>
		</td>
		<td><p><?php
			echo intval(100*$ra).'%';
		?></p>
		</td>
	</tr>
	<?php }?>
</table>
</body>
</html>