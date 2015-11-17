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
				<a>广告系统运行数据盘点报告 - 库存结算（<?= $this->listdata['bt']?> 至 <?= $this->listdata['et']?>  @ <?= date('Y-m-d H:i:s',TIMESTAMP)?>）</a>
			</th>
		</tr>
	</table>
<br>
	<table class="report_list">
			<tr>
				<td><strong>库存量（CPM）</strong></td>
				<td><strong>品牌实投（CPM）</strong></td>
				<td><strong>预定量（CPM）</strong></td>
				<td><strong>投放完成率</strong></td>
				<!-- <td><strong>投放损耗</strong></td> -->
				<td><strong>可结算量（CPM）</strong></td>
				<td><strong>可结算比例</strong></td>
			</tr>
    		<tr>
    			<td><?= intformat($this->listdata['kc'])?></td>
    			<td><?= intformat($this->listdata['pq'])?></td>
    			<td><?= intformat($this->listdata['yd'])?></td>
    			<td><?= round($this->listdata['pq']/$this->listdata['yd'],4)*100?>%</td>
    			<!-- <td><?= $this->listdata['sh'] < 0?'-':$this->listdata['sh'].'%'?></td> -->
    			<td><?= intformat($this->listdata['jsl'])?></td>
    			<td><?= $this->listdata['jsbl']?>%</td>
			</tr>
	</table>
	<br>
	<table>
		<tr>
			<td style="text-align: left;font-size: 9px;">数据说明：1、 可结算量为品牌实投量和预定量之间的较小值。2、可计算比例 = 可结算量/库存量 3、以上数据包含盘点结束日期当天。4、系统数据截止到<?= date('Y-m-d 07:00:00')?>。</td>
		</tr>
	</table>
<? if($this->show == 'normal'){?>
<br>
<?php include template('footer');?>
<?php }?>