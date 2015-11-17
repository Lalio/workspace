<?php
if(!defined('IN_SYS')) {
	header("HTTP/1.1 404 Not Found");
	die;
}
include template('header');
?>
<script type="text/javascript" src="./script/js/WdatePicker.js"></script>
<div class="ctr_panel">
	<?php if($_GET['do'] == "ReportByHour"){?>	
	<br>
		<table>
			<tr><th colspan="4"><a><?= $_GET['bt']." 投放数据监测  广告ID：".$_GET['aid']?></a></th></tr>
		    <tr><td>小时</td><td>PV</td><td>CLICK</td><td>IP</td></tr>
		    <?php foreach($this->pageData as $data){?>
		    	<tr><td><?= $data['hour']?></td><td><?= $data['view']?></td><td><?= $data['click']?></td><td><?= $data['viewip']?></td></tr>
		    <?php }?>		
		</table>
	<br>
	<?php }else{?>
	<br>
	<form action="" method="get" name="form1">
	<input type="hidden" name="action" value="AdReport" />
	<input type="hidden" name="do" value="Report" />
	<table>
		<tr><th colspan="2"><a>ACS数据报表查询</a></th></tr>
	    <tr><td width="35%">开始时间：</td><td><input type="text"  name="bt" value="<?=date('Y-m-d',TIMESTAMP-86400*10)?>" size="40" onclick="WdatePicker()"/></td></tr>
	    <tr><td>结束时间：</td><td><input type="text" name="et" value="<?=date('Y-m-d',TIMESTAMP-86400)?>" size="40" onclick="WdatePicker()"/></td></tr>
	    <tr><td>客户ID（每次只能输入一个ID）：</td><td><input type="text" name="vid" value="" /></td></tr>
		<tr><td>广告ID（多个ID请用英文逗号隔开）：</td><td><input type="text" name="aid" value="" size="130"/></td></tr>
		<tr>
			<td colspan="2" style="height:60px;text-align:center">
				<input class="button green small" type="submit" value="查询" />   （广告ID和客户ID只能选择其一输入，广告ID优先）
			</td>
		</tr>		
	</table>
	</form>
	<br>	
	<br>
		<form action="" method="get" name="form1">
		<input type="hidden" name="action" value="AdReport" />
		<input type="hidden" name="do" value="ReportByHour" />
		<table>
			<tr><th colspan="2"><a>广告AID按小时查量</a></th></tr>
<!-- 			<tr><td>查询维度</td>
				<td>
				<select name="type">
				    <option value="view">展示量​</option>
					<option value="click">​点击量</option>
				</select>
				</td>
			</tr> -->
		    <tr><td>查询日期：</td><td><input type="text"  name="bt" value="<?=date('Y-m-d',TIMESTAMP)?>" size="40" onclick="WdatePicker()"/></td></tr>
			<tr><td>广告ID（每次只能输入一个ID）：</td><td><input type="text" name="aid" value="" size="30"/></td></tr>
			<tr>
				<td colspan="2" style="height:60px;text-align:center">
					<input class="button green small" type="submit" value="查询" />
				</td>
			</tr>		
		</table>
	</form>
	<br>
<?php }?>
</div>
<?php include template('footer');?>