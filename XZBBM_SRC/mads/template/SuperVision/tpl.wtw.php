<?php
if(!defined('IN_SYS')) {
	header("HTTP/1.1 404 Not Found");
	die;
}
if($this->show == 'normal'){
	include template('header');
}

$this->listdata = json_decode($_SESSION[$_REQUEST['skey']],ture);
?>
<?php if($this->show != 'normal'){?>
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
<?php }?>
<!-- 数据展示模块 -->
<br>
	<table class="report_list">
		<tr>
			<th colspan="4">
				<a>广告系统 <?= date('Y/m/d',$this->listdata['bt'])?> - <?= date('Y/m/d',$this->listdata['et'])?> 订单投放情况盘点  @ <?= date('Y-m-d H:i:s',TIMESTAMP)?></a>
			</th>
		</tr>
	</table>
<br>
<div class="ctr_panel">
	<table class="report_list">
			<tr>
				<td><strong>广告编号</strong></td>
				<td><strong>广告位置</strong></td>
				<td><strong>广告类型</strong></td>
				<td><strong>客户名称</strong></td>
				<td><strong>区间天数</strong></td>
				<td><strong>定向区域</strong></td>
				<td><strong>实际完成量（CPM）</strong></td>
				<td><strong>AE下单量（CPM）</strong></td>
				<td><strong>需求完成比</strong></td>
				<!-- <td><strong>投放设置量（CPM）</strong></td>
				<td><strong>投放完成比</strong></td> -->
			</tr>
			<?php foreach($this->listdata as $k => $v){
				$yd = $this->GetConsumerNeed($v['aid'],'','ads',$this->listdata['bt'],$this->listdata['et']);
				$st = floor($this->GetActualAmount($this->listdata['bt'],$this->listdata['et'],$v['aid'])/1000);
				if(in_array($k, array('bt','et'))) continue;
			?>
    		<tr>
    			<td><strong>#<?= $v['aid']?></strong></td>
    			<td><?= $v['ad_place']?></td>
    			<td><?= $v['ad_type']?></td>
    			<td><?= $v['Consumer']?></td>
    			<td><strong><?= count(pre_dates($this->listdata['bt'], $this->listdata['et']))?>天</strong></td>
    			<td><textarea rows="3" cols="6"><?= str_replace("_", "\r\n", $v['city'])?></textarea></td>
    			<td><?= $st?></td>
    			<td><?= $yd?></td>
    			<td><strong><?= is_numeric($yd)?(round(($st/$yd),4)*100).'%':'-'?></strong></td>
    			<!-- <td><?= $v['cpm_order']?></td>
    			<td><strong><?= round(($v['cpm_finish']/$v['cpm_order']),4)*100?>%</strong></td> -->
			</tr>
			<?php }?>
	</table>
</div>
	<br>
	<table>
		<tr>
			<td style="text-align: left;font-size: 9px;">数据说明：1、以上数据包含盘点结束日期当天。2、系统数据截止到<?= date('Y-m-d 07:00:00')?>。</td>
		</tr>
	</table>
<? if($this->show == 'normal'){?>
<br>
<?php include template('footer');?>
<?php }?>