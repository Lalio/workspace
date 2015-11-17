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
				<a>广告系统运行数据盘点报告 - 库存余量/占用（<?= $this->listdata['bt']?> 至 <?= $this->listdata['et']?>  @ <?= date('Y-m-d H:i:s',TIMESTAMP)?>）</a>
			</th>
		</tr>
	</table>
<br>
	<table class="report_list">
			<tr>
				<td><strong>广告类型</strong></td>
				<td><strong>时间范围</strong></td>
				<td><strong>盘点区域</strong></td>
				<td><strong>库存量预测（CPM）</strong></td>
				<td><strong>预定实投（CPM）</strong></td>
				<td><strong>下单实投（CPM）</strong></td>
				<td><strong>剩余量（CPM）</strong></td>
			</tr>
    		<tr>
    			<td><?= $this->listdata['type'] == 1?'前贴片':($this->listdata['type'] == 2?'中贴片':($this->listdata['type'] == 3?'后贴片':'暂停广告'))?></td>
    			<td><?= $this->listdata['bt']?> - <?= $this->listdata['et']?></td>
    			<td><?= $this->listdata['areas']?></td>
    			<td><?= $this->listdata['kc_str']?></td>
    			<td><?= $this->listdata['yd_str']?> (<?= round($this->listdata['yd']/$this->listdata['kc'],4)*100?>%)</td>
    			<td><?= $this->listdata['st_str']?> (<?= round($this->listdata['st']/$this->listdata['kc'],4)*100?>%)</td>
    			<td><?= $this->listdata['yl_str']?> (<?= round($this->listdata['yl']/$this->listdata['kc'],4)*100?>%)</td>
			</tr>
	</table>
	<br>
	<table>
		<tr>
			<td style="text-align: left;font-size: 9px;">数据说明：1、 库存预测量基于该区域过去30天日平均VV估算得出。2、以上数据包含盘点结束日期当天。3、系统数据截止到<?= date('Y-m-d 07:00:00')?>。</td>
		</tr>
	</table>
<? if($this->show == 'normal'){?>
<br>
<?php include template('footer');?>
<?php }?>