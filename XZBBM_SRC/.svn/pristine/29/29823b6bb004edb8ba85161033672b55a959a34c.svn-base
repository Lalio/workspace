<?php
if(!defined('IN_SYS')) {
	header("HTTP/1.1 404 Not Found");
	die;
}
include template('header');
?>
<script type="text/javascript" src="./script/js/WdatePicker.js"></script>
<script type="text/javascript" src="./script/js/order.js"></script>
<script type="text/javascript" src="./script/js/query.js"></script>
<script type="text/javascript" src="./script/js/supervision.js"></script>
<div class="ctr_panel">
	<!-- 查询查单模块 -->
	<? if( $this->do != 'ShowResult' ){?>
	<br>
		<form id="panel" enctype="multipart/form-data" action="./?action=SuperVision&do=Scwscript&headerType=xls" method="post" target="_blank">
		<table>
			<tr><th colspan="2"><a>广告投放效果评估</a></th></tr>
		    <tr>
		    	<td width="25%">开始时间：</td>
		    	<td width="75%"><input type="text" name="bt" id="bt" value="<?=date('Y-m-d',TIMESTAMP-86400*3)?>" size="40" onclick="WdatePicker()" readonly/></td>
		    </tr>
		    <tr><td>结束时间：</td><td><input type="text" name="et" id="et" value="<?=date('Y-m-d',TIMESTAMP-86400)?>" size="40" onclick="WdatePicker()" readonly/></td></tr>
		    <tr><td>类型设定：</td>
				<td>
					<select name="op_type" class="op_type">
						<option value="" selected>- 请选择盘点类型 -</option>
						<option value="Acs">A - ACS数据盘点</option>
						<option value="Kc">B - 库存/投放汇总盘点(全国)</option>
						<option value="Sz_Tf_Dj">C - 执行设置量/实际投放量/广告点击量盘点</option>
						<option value="Kcyl_Zy">D - 库存余量/占用量盘点</option>
						<option value="Kcjs">E - 库存结算量盘点(全国)</option>
						<option value="Khtf">F - 客户投放量盘点</option>
						<option value="Xd_Zx_Sj">G - 客户下单量/执行设置量/实际完成量盘点</option>
						<option value="Wtw">H - 未投完订单盘点</option>
					</select>
				</td>
			</tr>
		    <tr class="param" id="vids"><td>客户ID（每次只能输入一个ID）：</td><td><input type="text" name="vid" value="" /></td></tr>
			<tr class="param" id="file">
				<td>原始数据报表</td>
				<td>
					<input type="file" name="Excel" size="39">
				</td>
			</tr>
			<tr class="param" id="cid">
				<td>广告ID：(多个AID用英文,分隔)</td>
				<td>
					<textarea name="aid" id="aids" rows="2" cols="100"/></textarea>
					<br /><select class="contract_selected" onchange="getrelatedids(this.value)"><option value="">-- 按合同号导入 --</option></select>
				</td>
			</tr>
			<tr class="param" id="tf_type">
				<td>广告类型</td>
				<td>
					<select name="type">
						<option value="1" selected>前贴片</option>
						<option value="2">中贴片</option>
						<option value="3">后贴片</option>
						<option value="21">暂停广告</option>
					</select>
				</td>
			</tr>
			<tr class="param" id="area">
				<td>盘点区域：(多个地区用英文,分隔)</td><td>
					<textarea name="areas" rows="2" cols="100" id="city"/></textarea>
					<br>
					<select class="province_selected" related_id="01"><option>-- 请选择省份 --</option></select>
			    	<select class="city_selected" id="01" related_id="city"><option>-- 请选择城市 --</option></select>
				</td>
			</tr>
			<tr class="param" id="rid">
				<td>预订单号</td>
				<td>
					<input type="text" name="rid" value="" size="80"/>
				</td>
			</tr>
			<tr class="param" id="vid">
				<td>广告客户</td>
				<td>
					<select name="vid" id="client">
						<option value="" selected></option>
						<?php 
							foreach($this->clients as $k => $v){
								echo "<option value ='$k'>$v</option>";   
	                        }
						?>
					</select>
					<input class="client_quicksearch" for_id="client" type="text" value="快速翻查" onclick="this.value=''" onblur="this.value='快速翻查'" size="10">
				</td>
			</tr>
			<tr><td>其它选项：</td>
				<td>
					<input type="checkbox" name="config[outtoexcel]" id="outtoexcel" value="on"> 导出为Excel报表
					<input type="checkbox" name="config[sendemail]" id="sendemail" value="on"> 邮件记录（<?= ADMIN."@renren-inc.com";?>）
				</td>
			</tr>
			<tr>
				<td colspan="2" style="height:60px;text-align:center">
					<span id="sbt_btn"><a onclick="add_querytask();" href="javascript:;" class="button blue">开始盘点</a></span>
				</td>
			</tr>		
		</table>
		</form>
	<br>
	<? }?>
	<!-- 查询查单模块 -->
	<!-- 数据展示模块 -->
	<? if( $this->do == 'ShowResult' ){ $s = 0;?>
	<br>
		<table class="report_list">
			<tr>
				<th colspan="4">
					<a>广告投放效果监测报告（<?= $this->bt?> 至 <?= $this->et?>  @ <?= date('Y-m-d H:i:s',TIMESTAMP)?>）</a>
					<a target="_blank" class="buttom smlll green" href="./?action=SuperVision&do=ShowResult&func=Acs&skey=<?= $_GET['skey']?>&type=1&headerType=xls">每日统计汇总表</a>
					<a target="_blank" class="buttom smlll red" href="./?action=SuperVision&do=ShowResult&func=Acs&skey=<?= $_GET['skey']?>&type=5&headerType=xls">24H数据统计表</a>
					<a target="_blank" class="buttom smlll green" href="./?action=SuperVision&do=ShowResult&func=Acs&skey=<?= $_GET['skey']?>&type=2&headerType=xls">AID->PV</a>
					<a target="_blank" class="buttom smlll red" href="./?action=SuperVision&do=ShowResult&func=Acs&skey=<?= $_GET['skey']?>&type=3&headerType=xls">AID->CLICK</a>
					<a target="_blank" class="buttom smlll green" href="./?action=SuperVision&do=ShowResult&func=Acs&skey=<?= $_GET['skey']?>&type=4&headerType=xls">AID->PV&Click</a>
				</th>
			</tr>
		</table>
	<br>
	<?php foreach($this->listdata as $key => $value){ ?>
		<table class="report_list">
			<tr><td>广告编号：#<?= $value['info']['aid']?></td><td>广告名称：<?= $value['info']['title']?></td><td>投放位置：<?= $this->adstype[$value['info']['cid']]?></td><td>广告类型：<?= $this->ad_sub_type[$value['info']['type']]?></td><td>投放城市：<?= $value['info']['city']?$value['info']['city']:'中国'?></td><td>投放周期：<?= date('Y年m月d日',$value['info']['starttime'])?> 至 <?= date('Y年m月d日',$value['info']['endtime'])?></td></tr>
		</table>
		<table class="report_list">
				<tr><td>日期</td><td>类型</td><? for($i=0;$i<24;$i++){?><td><b><?= $i<10?'0'.$i:$i?></b></td><? }?><td>总计</td><td>趋势图</td></tr>
		    <?php foreach($value as $k => $v){ if($k == 'info') continue;?>
	    		<tr>
					<td rowspan="3"><b><?= $k?></b></td>
					<td>PV</td>
					<?php for($i=0;$i<24;$i++){?>
						<td><i><?= intformat($v['detail'][$i]['view'])?intformat($v['detail'][$i]['view']):0?></i></td>
					<?php }?>
					<td><b><?= intformat($v['summ']['view_total'])?intformat($v['summ']['view_total']):0?></b></td>
					<td rowspan="3"><a class="view_detail" href="javascript:;" aid="<?= $value['info']['aid']?>" ts="<?= strtotime($k)?>">PV</a> / <a class="click_detail" href="javascript:;" aid="<?= $value['info']['aid']?>" ts="<?= strtotime($k)?>">CLICK</a> / <a class="ip_detail" href="javascript:;" aid="<?= $value['info']['aid']?>" ts="<?= strtotime($k)?>">IP</a></td>
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
		</table>
	<br>
	<? $s++;}?>
	<? }?>
	<!-- 数据展示模块 -->
</div>
<?php include template('footer');?>