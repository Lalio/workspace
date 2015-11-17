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
<?php if($_GET['type'] == 1){?>

<table>
<tr><td><b>客户名称</b></td><td><b>广告标题</b></td><td><b>广告ID</b></td><td><b>广告位</b></td><td><b>类型</b></td><td><b>定向区域</b></td><td><b>优先级</b></td><td><b>广告时长</b></td><td><b>频控_天数</b></td><td><b>频控_次数</b></td><td><b>数据</b></td><? foreach($this->common['dates'] as $v) {?><td><b><?= $v?></b></td><? }?><td><b>汇总</b></td></tr>
<?php foreach($this->listdata as $k=>$v){
		$link = explode('#', $v[info][link]);
		foreach($link as $s){
			$s = explode('_', $s);
			$link[$s[0]] = $s[1];
		}
?>
	<tr>
		<td><?= $this->clients[$v[info][vid]]?></td>
		<td><?= $v[info][title]?></td>
		<td><?= $v[info][aid]?></td>
		<td><?= $this->adstype[$v[info][cid]]?></td>
		<td><?= $this->ad_sub_type[$v[info][type]]?></td>
		<td><?= $v[info][city].$v[info][area]?></td>
		<td><?= $link[level]?></td>
		<td><?= $link[sec]?></td>
		<td><?= (int)($link[freq]/86400)?></td>
		<td><?= $v[info][freq]?></td>
		<td>PV</td>
		<?php $pv_sum = 0; foreach($this->common['dates'] as $d){?>
		<td><?= $v[$d]['summ']['view_total']?intformat($v[$d]['summ']['view_total']):0?></td>
		<?php $pv_sum += $v[$d]['summ']['view_total'];}?>
		<td><b><?= intformat($pv_sum)?></b></td>
	</tr>
	<tr>
		<td><?= $this->clients[$v[info][vid]]?></td>
		<td><?= $v[info][title]?></td>
		<td><?= $v[info][aid]?></td>
		<td><?= $this->adstype[$v[info][cid]]?></td>
		<td><?= $this->ad_sub_type[$v[info][type]]?></td>
		<td><?= $v[info][city].$v[info][area]?></td>
		<td><?= $link[level]?></td>
		<td><?= $link[sec]?></td>
		<td><?= (int)($link[freq]/86400)?></td>
		<td><?= $v[info][freq]?></td>
		<td>CLICK</td>
		<?php $cc_sum = 0; foreach($this->common['dates'] as $d){?>
		<td><?= $v[$d]['summ']['click_total']?intformat($v[$d]['summ']['click_total']):0?></td>
		<?php $cc_sum += $v[$d]['summ']['click_total'];}?>
		<td><b><?= intformat($cc_sum)?></b></td>
	</tr>
	<tr>
		<td><?= $this->clients[$v[info][vid]]?></td>
		<td><?= $v[info][title]?></td>
		<td><?= $v[info][aid]?></td>
		<td><?= $this->adstype[$v[info][cid]]?></td>
		<td><?= $this->ad_sub_type[$v[info][type]]?></td>
		<td><?= $v[info][city].$v[info][area]?></td>
		<td><?= $link[level]?></td>
		<td><?= $link[sec]?></td>
		<td><?= (int)($link[freq]/86400)?></td>
		<td><?= $v[info][freq]?></td>
		<td>CTR</td>
		<?php foreach($this->common['dates'] as $d){?>
		<td><?= $v[$d]['summ']['view_total'] && $v[$d]['summ']['click_total']?(round(($v[$d]['summ']['click_total']/$v[$d]['summ']['view_total']),6)*100).'%':'-'?></td>
		<?php }?>
		<td><b><?= (round(($cc_sum/$pv_sum),6)*100).'%'?></b></td>
	</tr>
<?php }?>
</table>

<?php }elseif($_GET['type'] == 2){?>

<table>
<tr><td><b>广告ID</b></td><td><b>定向区域</b></td><td><b>数据类型</b></td><? foreach($this->common['dates'] as $v) {?><td><b><?= $v?></b></td><? }?><td><b>汇总</b></td></tr>
<?php foreach($this->listdata as $k=>$v){?>
	<tr>
		<td><?= $v[info][aid]?></td>
		<td><?= $v[info][city].$v[info][area]?></td>
		<td>PV</td>
		<?php $uv_sum = 0; foreach($this->common['dates'] as $d){?>
		<td><?= $v[$d]['summ']['view_total']?intformat($v[$d]['summ']['view_total']):0?></td>
		<?php $uv_sum += $v[$d]['summ']['view_total'];}?>
		<td><b><?= intformat($uv_sum)?></b></td>
	</tr>
<?php }?>
</table>

<?php }elseif($_GET['type'] == 3){?>

<table>
<tr><td><b>广告ID</b></td><td><b>定向区域</b></td><td><b>数据类型</b></td><? foreach($this->common['dates'] as $v) {?><td><b><?= $v?></b></td><? }?><td><b>汇总</b></td></tr>
<?php foreach($this->listdata as $k=>$v){?>
	<tr>
		<td><?= $v[info][aid]?></td>
		<td><?= $v[info][city].$v[info][area]?></td>
		<td>CLICK</td>
		<?php $cc_sum = 0; foreach($this->common['dates'] as $d){?>
		<td><?= $v[$d]['summ']['click_total']?intformat($v[$d]['summ']['click_total']):0?></td>
		<?php $cc_sum += $v[$d]['summ']['click_total'];}?>
		<td><b><?= intformat($cc_sum)?></b></td>
	</tr>
<?php }?>
</table>

<?php }elseif($_GET['type'] == 4){?>

<table>
<tr><td><b>广告ID</b></td><td><b>定向区域</b></td><td><b>数据类型</b></td><? foreach($this->common['dates'] as $v) {?><td><b><?= $v?></b></td><? }?><td><b>汇总</b></td></tr>
<?php foreach($this->listdata as $k=>$v){?>
	<tr>
		<td rowspan="2"><?= $v[info][aid]?></td>
		<td rowspan="2"><?= $v[info][city].$v[info][area]?></td>
		<td>PV</td>
		<?php $uv_sum = 0; foreach($this->common['dates'] as $d){?>
		<td><?= $v[$d]['summ']['view_total']?intformat($v[$d]['summ']['view_total']):0?></td>
		<?php $uv_sum += $v[$d]['summ']['view_total'];}?>
		<td><b><?= intformat($uv_sum)?></b></td>
	</tr>
	<tr>
		<td>CLICK</td>
		<?php $cc_sum = 0; foreach($this->common['dates'] as $d){?>
		<td><?= $v[$d]['summ']['click_total']?intformat($v[$d]['summ']['click_total']):0?></td>
		<?php $cc_sum += $v[$d]['summ']['click_total'];}?>
		<td><b><?= intformat($cc_sum)?></b></td>
	</tr>
<?php }?>
</table>

<?php }else{?>
	<table>
		<?php foreach($this->listdata as $key => $value){ ?>
			<!-- 
			<table class="report_list">
				<tr><td>广告编号：#<?= $value['info']['aid']?></td><td>广告名称：<?= $value['info']['title']?></td><td>投放位置：<?= $this->adstype[$value['info']['cid']]?></td><td>广告类型：<?= $this->ad_sub_type[$value['info']['type']]?></td><td>投放城市：<?= $value['info']['city']?$value['info']['city']:'中国'?></td><td>投放周期：<?= date('Y年m月d日',$value['info']['starttime'])?> 至 <?= date('Y年m月d日',$value['info']['endtime'])?></td></tr>
			</table>
			 -->
				<tr><td><strong>#<?= $value['info']['aid']?></strong></td><td>类型</td><? for($i=0;$i<24;$i++){?><td><b><?= $i<10?'0'.$i:$i?></b></td><? }?><td>总计</td></tr>
			    <?php foreach($value as $k => $v){ if($k == 'info') continue;?>
		    		<tr>
						<td rowspan="3"><b><?= $k?></b></td>
						<td>PV</td>
						<?php for($i=0;$i<24;$i++){?>
							<td><i><?= intformat($v['detail'][$i]['view'])?intformat($v['detail'][$i]['view']):0?></i></td>
						<?php }?>
						<td><b><?= intformat($v['summ']['view_total'])?intformat($v['summ']['view_total']):0?></b></td>
					</tr>
					<tr>
						<td>CLICK</td>
						<?php for($i=0;$i<24;$i++){?>
							<td><i><?= intformat($v['detail'][$i]['click'])?intformat($v['detail'][$i]['click']):0?></i></td>
						<?php }?>
						<td><b><?= intformat($v['summ']['click_total'])?intformat($v['summ']['click_total']):0?></b></td>
					</tr>
					<tr>
						<td>IP</td>
						<?php for($i=0;$i<24;$i++){?>
							<td><i><?= intformat($v['detail'][$i]['viewip'])?intformat($v['detail'][$i]['viewip']):0?></i></td>
						<?php }?>
						<td><b><?= intformat($v['summ']['viewip_total'])?intformat($v['summ']['viewip_total']):0?></b></td>
					</tr>
			<?php }?>
					<tr>
					</tr>
		<?}?>
	</table>
<?php }?>
</body>
