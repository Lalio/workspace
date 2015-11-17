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
				<a>广告系统 （<?= $this->listdata['bt']?> ~ <?= $this->listdata['et']?>） 客户下单量-执行设置量-实际完成量盘点  @ <?= date('Y-m-d H:i:s',TIMESTAMP)?></a>
			</th>
		</tr>
	</table>
<br>
<div class="ctr_panel">
	<table class="report_list">
			<tr>
				<td><strong>预订单编号</strong></td>
				<td><strong>广告单编号</strong></td>
				<td><strong>数据区间</strong></td>
				<td><strong>广告客户</strong></td>
				<td><strong>产品线/活动</strong></td>
				<td><strong>订单状态</strong></td>
				<td><strong>定向区域</strong></td>
				<td><strong>AE下单量（CPM）</strong></td>
				<!-- <td><strong>执行设置量（CPM）</strong></td> -->
				<td><strong>实际完成量（CPM）</strong></td>
				<!-- <td><strong>投放倍数</strong></td> -->
				<td><strong>投放完成率</strong></td>
			</tr>
			<?php foreach($this->listdata['re'] as $k => $v){?>
    		<tr>
    			<td><?= $k == 0?"<strong>#{$this->listdata['reserve']['id']}</strong>":"..."?></td>
    			<td><?= $k == 0?"<strong>{$v['aid']}</strong>":"{$v['aid']}"?></td>
    			<td><?= $k == 0?"<strong>{$this->listdata['bt']} ~ {$this->listdata['et']}</strong>":"..."?></td>
    			<td><?= $k == 0?"<strong>{$this->clients[$this->listdata['reserve']['client']]}</strong>":"..."?></td>
    			<td><?= $k == 0?"<strong>{$this->listdata['reserve']['brand']}</strong>":"..."?></td>
    			<td><?= $k == $k?"<span class='nd_flash'>".($this->listdata['reserve']['status'] == 4?'准备投放':'正常投放')."</class>":"..."?></td>
    			<td><?= $v['city']?$v['city']:'中国'?></td>
    			<td><?= intformat($v['cpm'])?></td>
    			<!-- <td><?= $v['cpm_order']?></td> -->
    			<td><?= intformat($v['cpm_finish'])?></td>
    			<!-- <td><?= round(($v['cpm_order']/$v['cpm']),4)*100?>%</td> -->
    			<td><?= round(($v['cpm_finish']/$v['cpm']),4)*100?>%</td>
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