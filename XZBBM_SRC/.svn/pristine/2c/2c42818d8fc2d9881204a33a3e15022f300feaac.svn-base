<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;    
}
if($this->show == 'edit'){ // 前一步已经将AE和RESOURCE区分开来
	if(ROLE == 'AE'){
	    //从AE角度看
		if($this->pagedata['editInfo']['status'] == 0 || ($this->pagedata['editInfo']['status'] == 2 && $this->pagedata['editInfo']['result'] == 2)){
		  $B_BTN = $this->BtnB(1);
		}
	}else{
		if($this->pagedata['editInfo']['result'] == 0){
			//从RESOURCE角度看
			$B_BTN = $this->BtnB(2);
			$B_BTN .= $this->BtnB(3);
			$B_BTN .= <<<HTML
	<a class="button blue" target="_blank" href="./?action=Query&do=Task&show=add&r_id=$_GET[id]">查量</a>
HTML;
			if($this->pagedata['editInfo']['status'] == 3){
				$B_BTN .= $this->BtnB(4);
			}
		}
	}
	
	$B_BTN .= <<<HTML
	<a class="button white" target="_blank" href="./?action=Reserve&do=OutPutToExcel&headerType=xls&ids=$_GET[id]">导出</a>
HTML;
}elseif($this->show == 'add'){
	//从AE角度看
	$B_BTN = $this->BtnB(1);
	//从RESOURCE角度看
}

if(!empty($this->pagedata['list'])){
	foreach($this->pagedata['list'] as $v){
		$reserve_id[] = $v['reserve_id'];
		$ids[] = $v['id'];
	}
	$ids = implode(',', $ids);
	$this->pagedata['list_tj'] = array_count_values($reserve_id);
}
?>
<?php include template('header');?>
<script type="text/javascript" src="./script/js/WdatePicker.js"></script>
<script type="text/javascript" src="./script/js/order.js"></script>
<script type="text/javascript" src="./script/js/reserve.js"></script>
<div class="ctr_panel">
	<br>
	<?php if($this->show == 'add'||$this->show == 'edit') {?>
	<!-- 新增、编辑模块开始 -->
	<form id="input_form">
	<input type="hidden" name="id" value="<?= $_GET['id']?>">
	<table>
	    <tr>
	        <th colspan="2"><a href="javascript:;">资源预定</a></th>
	    </tr>
	    <tr>
			<td>预订单编号</td>
			<td><input type="text" name="reserve_id" size="40"  value="<?= $this->show=='add'?"R-AD".TIMESTAMP.makerandom(2)."_Auto":$this->pagedata['editInfo']['reserve_id']?>"></td>
		</tr>
		<tr>
			<td width="15%">代理</td>
			<td width="85%"><input class="uncheck" type="text" name="agent" size="40"  value="<?= $this->pagedata['editInfo']['agent']?>"></td>
		</tr>
		<tr>
		    <td>区域</td>
			<td>
				<select name="region">
				    <option value="1"<?= $this->pagedata['editInfo']['region'] == "1"?' selected':''?>>​华南​</option>
				    <option value="2"<?= $this->pagedata['editInfo']['region'] == "2"?' selected':''?>>​华北​</option>
				    <option value="3"<?= $this->pagedata['editInfo']['region'] == "3"?' selected':''?>>​华东</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>客户名称</td>
			<td>
				<select name="client" id="client">
					<option value=""></option>
					<?php 
						foreach($this->clients as $k => $v){
                            //if(!in_array($k, array(188,326,243,5,246,245,291,211,123,255,337,264,63,225,223,265,214,213,251,14,212,89,342,202,261,201,26,127,249,248,206,205,208,343,216,142,278,297,276,258,71,148,210,194,156,161,311,345,283,183,175,256,66,224,268,254,240,158,221,121,54,139,259,238,126,51,215,179,196,103,233,266,152,125,275,88,119,333,138,130,235,60,140,135,229,241,218,133,128,207,314,294))){
							     echo $k==$this->pagedata['editInfo']['client']?"<option value ='$k' selected>$v</option>":"<option value ='$k'>$v</option>";   
						    //}
                        }
					?>
				</select>
				<input class="client_quicksearch" for_id="client" type="text" value="快速翻查" onclick="this.value=''" onblur="this.value='快速翻查'" size="10">
			</td>		
		</tr>
		<tr>
			<td>品牌/产品线</td>
			<td><input type="text" name="brand" size="30"  value="<?= $this->pagedata['editInfo']['brand']?>"></td>
		</tr>
		<tr>
			<td>直客销售</td>
			<td>
				<select name="dcs">
					<option value=""></option>
					<?php foreach ($this->sales as $k => $v) {?>
						<option value="<?= $k?>"<?= $this->pagedata['editInfo']['dcs']==$k?'selected':''?>><?= $k?></option>
					<?php }?>
				</select>
			</td>
		</tr>
		<tr>
			<td>直客销售Leader</td>
			<td>
				<select name="dcs_leader">
					<option value=""></option>
					<?php foreach ($this->sales as $k => $v) {?>
						<option value="<?= $k?>"<?= $this->pagedata['editInfo']['dcs_leader']==$k?'selected':''?>><?= $k?></option>
					<?php }?>
				</select>
			</td>
		</tr>
		<tr>
			<td>渠道销售</td>
			<td>
				<select name="cs">
					<option value=""></option>
					<?php foreach ($this->sales as $k => $v) {?>
						<option value="<?= $k?>"<?= $this->pagedata['editInfo']['cs']==$k?'selected':''?>><?= $k?></option>
					<?php }?>
				</select>
			</td>		
		</tr>
		<tr>
			<td>渠道销售Leader</td>
			<td>
				<select name="cs_leader">
					<option value=""></option>
					<?php foreach ($this->sales as $k => $v) {?>
						<option value="<?= $k?>"<?= $this->pagedata['editInfo']['cs_leader']==$k?'selected':''?>><?= $k?></option>
					<?php }?>
				</select>
			</td>
		</tr>
		<tr>
			<td>投放周期</td>
			<td><input type="text" class="input_Calendar" onclick="WdatePicker({startDate:'<?= date('Y-m-d',TIMESTAMP)?> 0:0:0',dateFmt:'yyyy-MM-dd HH:mm:ss',highLineWeekDay:true,readonly:true})" name="starttime" size="30" value="<?= $this->pagedata['editInfo']['starttime']?date('Y-m-d H:i:s',$this->pagedata['editInfo']['starttime']):''?>"> - <input type="text" class="input_Calendar" onclick="WdatePicker({startDate:'<?= date('Y-m-d',TIMESTAMP)?> 0:0:0',dateFmt:'yyyy-MM-dd HH:mm:ss',highLineWeekDay:true,readonly:true})" name="endtime" size="30"  value="<?= $this->pagedata['editInfo']['endtime']?date('Y-m-d H:i:s',$this->pagedata['editInfo']['endtime']):''?>"></td>
		</tr>
		<tr>
			<td>定向区域</td> 
			<td>
				<textarea rows="5" cols="30" name="area" class="city_input" id="area"><?= $this->pagedata['editInfo']['area']?></textarea>
				<iframe width="650" height="45" src="./?action=Tackle&do=InputSwitchForm&symbol=_" scrolling="no" border="0" frameborder="0">浏览器不支持嵌入式框架，或被配置为不显示嵌入式框架。</iframe>
				<script type="text/javascript">
					function inputswitch(ctc){
						$("#area").attr("value",ctc);
					}
				</script>
			</td>
		</tr>
		<tr>
		    <td>广告用途</td>
			<td>
				<select name="mode">
				    <option value=1 <?= $this->pagedata['editInfo']['mode'] == 1?'selected':''?>>​内部推广​</option>
				    <option value=2 <?= $this->pagedata['editInfo']['mode'] == 2?'selected':''?>>​购买​</option>
				    <option value=3 <?= $this->pagedata['editInfo']['mode'] == 3?'selected':''?>>​补量​</option>
				    <option value=​4 <?= $this->pagedata['editInfo']['mode'] == 4?'selected':''?>>​赠送​</option>​​​​
				</select>
			</td>
		</tr>
		<tr>
			<td>广告位置</td>
			<td>
				<select name="cid" id="cid">
					<?php
						foreach($this->adstype as $k => $v){
							echo $k==$this->pagedata['editInfo']['cid']?"<option value ='$k' selected>$v</option>":"<option value ='$k'>$v</option>";   
						} 
					?>
				</select>
				<input class="adtype_quicksearch" for_id="cid" type="text" value="快速翻查" onclick="this.value=''" onblur="this.value='快速翻查'" size="10" x-webkit-speech="true">
			</td>
		</tr>
		<tr>
		    <td>广告形式</td>
			<td>
				<select name="type" id="type">
					<?php
						foreach($this->ad_sub_type as $k => $v){
							 echo $k == $this->pagedata['editInfo']['type']?"<option value ='$k' selected>$v</option>":"<option value ='$k'>$v</option>";   
						} 
					?>
				</select>
			</td>
		</tr>
		<tr><td>定向频道</td>
			<? $channel_arr = explode('_',$this->pagedata['editInfo']['channel']);?>
			<td>
				<input class="checkall" type="checkbox" id="channels"><a>全选/全不选</a><br/>
				<? foreach(Core::$vars['Channel'] as $k => $v){ ?>
					<input type="checkbox" name="channel[]" class="channels" value="<?= $k?>" <?= in_array($k, $channel_arr)?'checked':''?> id="<?= "channel_$v";?>"><label for="<?= "channel_$v";?>"><?= $v?></label>
				<? }?>
			</td>
		</tr>
		<tr>
			<td>广告时长</td>
			<td><input type="text" name="tp_time" size="5" value="<?= $this->pagedata['editInfo']['tp_time']?>">秒</td>
		</tr>
		<tr>
			<td>频次控制</td>
			<? $freq = explode('_',$this->pagedata['editInfo']['freq']);?>
			<td><input type="text" name="freq_day" size="5" value="<?= $freq[0]?>">天 <input type="text" name="freq_num" size="5" value="<?= $freq[1]?>">次</td>
		</tr>
		<tr>
			<td>投放时间</td>
			<? $display_hour_arr = explode(',',$this->pagedata['editInfo']['display_hour']);?>
			<td>
				<input class="checkall" type="checkbox" id="display_hours"><a>全选/全不选</a><br/>
				<? for($i=0;$i<24;$i++){ ?>
				<input type="checkbox" name="display_hour[]" class="display_hours" value="<?= $i?>" <?= !empty($this->pagedata['editInfo']['display_hour'])&&in_array($i, $display_hour_arr)?'checked':''?> id="<?= "display_hour_$i"?>"><label for="<?= "display_hour_$i"?>"><?= $i?></label>
				<? }?>
			</td>
		</tr>
		<tr>
			<td>KPI说明</td>
			<td><textarea rows="3" cols="75" name="kpi" class="city_input"><?= $this->pagedata['editInfo']['kpi']?></textarea></td>
		</tr>
		<tr>
			<td>提交AE</td>
			<td><?= $this->pagedata['editInfo']['ae']?$this->pagedata['editInfo']['ae']:ADMIN?><? if(ROLE == 'AE'){?><input type="hidden" name="ae" value="<?= ADMIN?>"><? }?><input type="hidden" name="status" value="0"></td>
		</tr>
		<tr>
			<td>提交时间</td>
			<td><?= $this->pagedata['editInfo']['ts']?date('Y-m-d H:i:s',$this->pagedata['editInfo']['ts']):date('Y-m-d H:i:s',TIMESTAMP)?><input type="hidden" name="ts" value="<?= TIMESTAMP?>"></td>
		</tr>
		<tr>
			<td>备注信息</td>
			<td><textarea rows="3" cols="75" name="message" class="city_input"><?= $this->pagedata['editInfo']['message']?></textarea></td>
		</tr>
		<? if($this->pagedata['editInfo']['status'] == 5){?>
		<tr>
			<td>第三方监测数据导入 <a target="_blank" href="./etc/xls/3report_demo.xls">[范例]</a></td>
			<td>
				<iframe name="ReportUpload" width="850" height="45" src="./?action=Reserve&do=ReportUploadForm&id=<?= $_REQUEST['id']?>" scrolling="no" border="0" frameborder="0">浏览器不支持嵌入式框架，或被配置为不显示嵌入式框架。</iframe><br>
				<?= $this->reports?>
			</td>
		</tr>
		<? }?>
		<? if(($this->pagedata['editInfo']['status'] == 1 && ROLE != 'AE')){?>
		<tr>
			<td>审核理由</td>
			<td><textarea rows="1" cols="75" name="reason" id="reason"><?= $this->pagedata['editInfo']['reason']?></textarea></td>
		</tr>
		<tr>
			<td>有效截止期</td>
			<td><input type="text" name="deadline" class="input_Calendar" onclick="WdatePicker({startDate:'<?= date('Y-m-d',(TIMESTAMP + 604800))?> 0:0:0',dateFmt:'yyyy-MM-dd HH:mm:ss',highLineWeekDay:true,readonly:true})" size="25" value="<?= date('Y-m-d',(TIMESTAMP + 604800))?>">
		</tr>
		<? }?>
	    <tr>
			<td colspan="2" style="height:60px;text-align:center">
				<?= $B_BTN?>
			</td>
		</tr>
	</table>
	</form>
	<script type="text/javascript">
	function rtn(data){
		switch (data.rs){
			case 0:
				$("#sbt_btn").html("保存成功");
				$("#sbt_btn").attr('class','button green');
				location.href="./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&show=list";
			break;
		}
	}
	<? //if($this->pagedata['editInfo']['status'] == 1 || $this->pagedata['editInfo']['status'] == 2) {?>
		//$('input').attr('readonly','true');
	<? //}?>
	</script>
	<br />		
	<!-- 新增、编辑模块结束 -->
	<?php }?>
	
	<?php if($this->show == 'search') {?>
	<!-- 查单模块开始 -->
	<form id="searchform" method="get" action="">
	<input type="hidden" name="action" value="Reserve">
	<input type="hidden" name="do" value="Basic">
	<input type="hidden" name="func" value="search">
	<input type="hidden" name="show" value="list">
	<table>
	    <tr>
	        <th colspan="2"><a href="javascript:;">预订单查询</a></th>
	    </tr>
	    <tr>
			<td>按预订单ID查询</td>
			<td><input type="text" name="id" size="15"  value="" ></td>
		</tr>
	    <tr>
			<td>按预订单编号查询</td>
			<td><input type="text" name="reserve_id" size="15"  value="" ></td>
		</tr>
		<tr>
			<td>按客户名称查询</td>
			<td>
				<select name="client" id="client">
					<option value=""></option>
					<?php 
						foreach($this->clients as $k => $v){ 
							echo $k==$this->pagedata['editInfo']['client']?"<option value ='$k' selected>$v</option>":"<option value ='$k'>$v</option>";   
						} 
					?>
				</select>
				<input class="client_quicksearch" for_id="client" type="text" value="快速翻查" onclick="this.value=''" onblur="this.value='快速翻查'" size="10">
			</td>		
		</tr>
		<tr>
			<td>按直客销售查询</td>
			<td><input type="text" name="dcs" size="40"  value="" ></td>
		</tr>
		<tr>
			<td>按直客销售Leader查询</td>
			<td><input type="text" name="dcs_leader" size="40"  value="" ></td>
		</tr>
		<tr>
			<td>按渠道销售查询</td>
			<td><input type="text" name="cs" size="40"  value="" ></td>
		</tr>
		<tr>
			<td>按渠道销售Leader查询</td>
			<td><input type="text" name="cs_leader" size="40"  value="" ></td>
		</tr>
		<tr>
			<td>按投放周期查询</td>
			<td><input type="text" class="input_Calendar" onclick="WdatePicker({startDate:'<?= date('Y-m-d',TIMESTAMP)?> 0:0:0',dateFmt:'yyyy-MM-dd HH:mm:ss',highLineWeekDay:true,readonly:true})" name="starttime" size="30" value=""> - <input type="text" class="input_Calendar" onclick="WdatePicker({startDate:'<?= date('Y-m-d',TIMESTAMP)?> 0:0:0',dateFmt:'yyyy-MM-dd HH:mm:ss',highLineWeekDay:true,readonly:true})" name="endtime" size="30"  value=""></td>
		</tr>
		<tr>
			<td>按定向区域查询</td>
			<td><input type="text" name="area" size="40"  value="" ></td>
		</tr>
		<tr>
			<td>按广告位置查询</td>
			<td>
				<select name="cid" id="cid">
				    <option></option>
					<?php
						foreach($this->adstype as $k => $v){
							 echo $k==$this->pagedata['editInfo']['cid']?"<option value ='$k' selected>$v</option>":"<option value ='$k'>$v</option>";   
						} 
					?>
				</select>
				<input class="adtype_quicksearch" for_id="cid" type="text" value="快速翻查" onclick="this.value=''" onblur="this.value='快速翻查'" size="10" x-webkit-speech="true">
			</td>
		</tr>
		<tr>
			<td>按提交AE查询</td>
		    <td><input type="text" name="ae" size="40"  value="" ></td>
		</tr>
	    <tr>
			<td colspan="2" style="height:60px;text-align:center">
				<span class="button blue" id="sbt_btn" onclick="searchform.submit();">搜索预订单</span>
			</td>
		</tr>
	</table>
	</form>
	<br />		
	<!-- 查单模块结束-->
	<?php }?>
	
	<?php if($this->show=='list') {?> 
	<!-- 列表模块开始 -->
	<table>
		<tr>
			<td colspan="<?= ($this->func == 'search')?11:10?>"><?= empty($this->pagedata['list'])?'-暂无数据-':$this->pagedata['sp']?></td>
		</tr>
		<tr>
			<th width="5px" colspan="2"><a>ID</a></th>
			<th><a>客户名称（品牌/产品线）</a></th>
			<th width="220px"><a>投放位置/投放类型</a></th>
			<th width="170px"><a>投放周期</a></th>
			<th width="65px"><a>订单流程</a></th>
			<th width="80px"><a>预订单状态/编号</a></th>
			<th width="100px"><a>释放截至期</a></th>
			<th width="80px"><a>AE</a></th>
			<?php if($this->func == 'search' && isset($_GET['area'])){?>
			<th><a><?= $_GET['area']?>量（CPM）</a></th>
			<?php }?>
			<th width="80px">
				<a class="button green medium" href="./?action=Reserve&do=OutPutToExcel&headerType=xls&ids=<?= $ids?>" target="_blank">表1</a>
				<? if($this->func == 'search'){?>
					<a class="button green medium" href="./?<?= $_SERVER['QUERY_STRING']?>&ext=report&&headerType=xls" target="_blank">表2</a>
				<?php }?>
			</th>
		</tr>
		<?php foreach ($this->pagedata['list'] as $data) {
			$cinfo = $this->_db->rsArray("SELECT id FROM ad_contract WHERE reserve_code = '".$data['reserve_code']."' LIMIT 0,1");
		?>
		<tr class="<?= $data['reserve_id']?>" id="<?= $data['id']?>">
		<?php if(!empty($data['reserve_id']) && $this->pagedata['list_tj'][$data['reserve_id']] > 1){?>
			<td style="text-align:center;width:10px;"><a href="javascript:;" class="fold_t" for_class="<?= $data['reserve_id']?>" for_id="<?= $data['id']?>">收起</a></td>
		<?php }else{?>
			<td style="text-align:center;width:10px;"></td>
		<?php }?>
			<td><a href="javascript:;" title="<?= $data['area']?>">#<?= $data['id']?></a></td>
			<td><?= $data['client']?$this->clients[$data['client']]:$data['agent']?>（<?= $data['brand']?>）</td>
			<td><?= $this->adstype[$data['cid']]?><br><font style="color:green;font-size:11px">[类型:<?= $this->ad_sub_type[$data['type']]?>]</font></td>
			<td>开始：<?= date('Y年m月d日',$data['starttime'])?><br>结束：<?= date('Y年m月d日',$data['endtime'])?></td>
	        <td><p><?php
	        	switch($data['status']){
	        		case 0 : echo '<font color="green">已保存<font>';break;
	        		case 1 : echo '<font color="blue">已提交<font>';break;
	        		case 2 : echo '<font color="red">已审核<font>';break;
	        		case 3 : echo '<font color="orange">撤回中<font>';break;
	        		default: 
	        				 echo $this->admin[role]=='AE'?'':'<a target="_blank" href="./?action=Order&do=Contract&show=edit&id='.$cinfo['id'].'">';
	        		         if(TIMESTAMP >= $data['endtime']){
	        					echo '<font color="black">投放结束<font>';
	        				 }else{
								switch($data['status']){
									case 4 : echo '<font color="green">准备投放<font>';break;
									case 5 : echo '<font color="blue">正在投放<font>';break;
								}
							 }
							 echo $this->admin[role]=='AE'?'':'</a>';
					break;
	        	}
	        ?></p></td>
	        <td><p><?php
	        	switch($data['result']){
	        		case 0 : echo '-';break;
	        		case 1 : echo "[$data[reserve_code]]";break;
	        		case 2 : echo '[审核未通过]';break;
	        	}
	        ?></p></td>
	        <td><p><?= $data['status']<4 && $data['deadline']!=0?date('Y年m月d日',$data['deadline']):'-'?></p></td>
	        <td><p><?= $data['ae']?></p></td>
	        <?php if($this->func == 'search' && isset($_GET['area'])){?>
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
				
				echo intval($cpm*$s_days/$d_days);
				unset($cpm);
			?></p>
			</td>
			<?php }?>
	        <td>
	        	<p align="center">
					<a class="button white small" onclick="location.href='./?action=<?= $_GET['action']?>&do=Rms&show=edit&reserve_id=<?= $data['reserve_id']?>#<?= $data['id']?>'">报单详情</a>
	        	</p>
	        </td>
		</tr>
		<?php }?>
		<tr>
			<td colspan="<?= ($_GET['func']=='search')?11:10?>"><?= empty($this->pagedata['list'])?'-暂无数据-':$this->pagedata['sp']?></td>
		</tr>
	</table>
	<br />	
	<!-- 列表模块结束 -->	
	<?php }?>
</div>
<?php
include template('footer');
?>