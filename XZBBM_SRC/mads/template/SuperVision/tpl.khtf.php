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
				<a>广告系统客户投放盘点（<?= $this->listdata['bt']?> 至 <?= $this->listdata['et']?>  @ <?= date('Y-m-d H:i:s',TIMESTAMP)?>）</a>
			</th>
		</tr>
	</table>
<br>
<div class="ctr_panel">
	<table class="report_list">
			<tr>
				<td><strong>时间范围</strong></td>
				<td><strong>预订单编号</strong></td>
				<td><strong>客户名称</strong></td>
				<td><strong>产品线/活动</strong></td>
				<td><strong>审核通过（CPM）</strong></td>
				<td><strong>准备投放（CPM）</strong></td>
				<td><strong>正在投放（CPM）</strong></td>
				<td><strong>投放结束（CPM）</strong></td>
				<td><strong>合计（CPM）</strong></td>
			</tr>
			<?php foreach ($this->listdata['ctc'] as $v) {?>
	    		<tr>
	    			<td><?= $this->listdata['bt']?> - <?= $this->listdata['et']?></td>
	    			<td style="text-align: left">#<?= $v['id']?></td>
	    			<td style="text-align: left"><?= $this->clients[$v['client']]?></td>
	    			<td style="text-align: left"><?= $v['brand']?></td>
	    			<td><?= (TIMESTAMP < $v['endtime'] && $v['status'] == 2)?$v['data']:'-'?></td>
	    			<td><?= (TIMESTAMP < $v['endtime'] && $v['status'] == 4)?$v['data']:'-'?></td>
	    			<td><?= (TIMESTAMP < $v['endtime'] && $v['status'] == 5)?$v['data']:'-'?></td>
	    			<td><?= (TIMESTAMP >= $v['endtime'])?$v['data']:'-'?></td>
	    			<td><strong><?= $v['data']?></strong></td>
				</tr>
			<?php }?>
	</table>
	<br>
	<table>
		<tr>
			<td style="text-align: left;font-size: 9px;">数据说明：1、以上数据包含盘点结束日期当天。2、系统数据截止到<?= date('Y-m-d 07:00:00')?>。</td>
		</tr>
	</table>
</div>
<? if($this->show == 'normal'){?>
<br>
<?php include template('footer');?>
<?php }?>