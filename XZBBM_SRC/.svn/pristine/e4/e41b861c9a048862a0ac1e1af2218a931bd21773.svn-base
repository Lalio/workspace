<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;   
}

if($this->show == 'edit'){
	if(empty($this->pagedata['editInfo'])){
		header("Location:./?action=Reserve&do=Rms&show=list");
	}else{
		$this->ts_contract = true;
		$this->addbtn_state = true;
		foreach($this->pagedata['editInfo'] as $editinfo){
			if(!($editinfo['status'] == 2 && $editinfo['result'] == 1)){
				$this->ts_contract = false;
			}
			if(!in_array($editinfo['status'], array(0,1,2,3))){
				$this->addbtn_state = false;
			}
		}
	}
}

?>
<?php include template('header');?>
<script type="text/javascript" src="./script/js/WdatePicker.js?v=1"></script>
<script type="text/javascript" src="./script/js/order.js?v=1"></script>
<script type="text/javascript" src="./script/js/reserve.js?v=1"></script>
<div class="ctr_panel">
	<br>
	<?php if($this->show == 'add'||$this->show == 'edit') {?>
	<!-- 新单、编辑模块开始 -->
	<form id="input_form">
	<table>
	    <tr>
	        <th colspan="2"><a href="javascript:;">资源预定</a></th>
	    </tr>
		<tr>
			<td width="15%">代理</td>
			<td width="85%"><input class="uncheck" type="text" name="agent" size="40"  value="<?= $this->pagedata['editInfo'][0]['agent']?>"></td>
		</tr>
		<tr>
		    <td>区域</td>
			<td>
				<select name="region">
				    <option value="1"<?= $this->pagedata['editInfo'][0]['region'] == "1"?' selected':''?>>​华南​</option>
				    <option value="2"<?= $this->pagedata['editInfo'][0]['region'] == "2"?' selected':''?>>​华北​</option>
				    <option value="3"<?= $this->pagedata['editInfo'][0]['region'] == "3"?' selected':''?>>​华东</option>
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
							     echo $k==$this->pagedata['editInfo'][0]['client']?"<option value ='$k' selected>$v</option>":"<option value ='$k'>$v</option>";   
						    //}
                        }
					?>
				</select>
				<input class="client_quicksearch" for_id="client" type="text" value="快速翻查" onclick="this.value=''" onblur="this.value='快速翻查'" size="10">
			</td>		
		</tr>
		<tr>
			<td>品牌/产品线</td>
			<td><input type="text" name="brand" size="30"  value="<?= $this->pagedata['editInfo'][0]['brand']?>"></td>
		</tr>
		<tr>
			<td>直客销售</td>
			<td>
				<select name="dcs">
					<option value=""></option>
					<?php foreach ($this->sales as $k => $v) {?>
						<option value="<?= $k?>"<?= $this->pagedata['editInfo'][0]['dcs']==$k?'selected':''?>><?= $k?></option>
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
						<option value="<?= $k?>"<?= $this->pagedata['editInfo'][0]['dcs_leader']==$k?'selected':''?>><?= $k?></option>
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
						<option value="<?= $k?>"<?= $this->pagedata['editInfo'][0]['cs']==$k?'selected':''?>><?= $k?></option>
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
						<option value="<?= $k?>"<?= $this->pagedata['editInfo'][0]['cs_leader']==$k?'selected':''?>><?= $k?></option>
					<?php }?>
				</select>
			</td>
		</tr>
		<tr id="common">
			<td>预订单编号
			<?php if(true == $this->addbtn_state){?>
				<a href="javascript:;" class="add_c_reserve"><strong>[+]</strong></a>
			<?php }?>
			</td>
			<td><input type="text" name="reserve_id" size="40"  value="<?= $this->pagedata['editInfo'][0]['reserve_id']?$this->pagedata['editInfo'][0]['reserve_id']:"R-AD".TIMESTAMP.makerandom(2)."_Auto"?>" readonly></td>
		</tr>
		
		<?php foreach($this->pagedata['editInfo'] as $editinfo){?>
			<?php
				$ids .= $editinfo['id'].',';
				$tmp = $this->_db->rsArray("select id from ad_checkamounts where for_rid = {$editinfo[id]} order by id desc limit 1");
				$editinfo['task_id'] = $tmp['id'];
			?>
		<tr id="<?= $editinfo['id']?>">
			<td>子订单(#<?= $editinfo['id']?>) <a href="javascript:;" class="sz_inputpanel" for_id="inputpanel_<?= $editinfo['id']?>">[╣]</a>
				<? if($this->func == 'add'){?><a href="javascript:;" onclick="$(this).parent().parent().remove();"><strong>[-]</strong></a><?php }?>
				<input type="hidden" name="id[<?= $editinfo['id']?>][]" value="<?= $editinfo['id']?>" />
				<input type="hidden" name="status[<?= $editinfo['id']?>][]" value="<?= $editinfo['status']?>" />
				<input type="hidden" name="result[<?= $editinfo['id']?>][]" value="<?= $editinfo['result']?>" />
				<input type="hidden" name="ae[<?= $editinfo['id']?>][]" value="<?= ($editinfo['ae']=='' && ROLE == 'AE')?ADMIN:$editinfo['ae']?>" />
			</td>
			<td style="font-size:9px;padding:12px">
				<div class="inputpanel" id="inputpanel_<?= $editinfo['id']?>" style="display:block;">
					<hr>广告位置
					<select name="cid[<?= $editinfo['id']?>][]" class="cid" id="cid_<?= $editinfo['id']?>" from_cid_id="cid_<?= $editinfo['id']?>" for_type_id="type_<?= $editinfo['id']?>">
						<?php
							foreach($this->adstype as $k => $v){
								echo $k==$editinfo['cid']?"<option value ='$k' selected>$v</option>":"<option value ='$k'>$v</option>";   
							} 
						?>
					</select>
					<input class="adtype_quicksearch" for_id="cid_<?= $editinfo['id']?>" id="cqk_<?= $editinfo['id']?>" type="text" value="快速翻查" onclick="this.value=''" onblur="this.value='快速翻查'" size="10" x-webkit-speech="true">
					广告形式
					<select name="type[<?= $editinfo['id']?>][]" class="type" id="type_<?= $editinfo['id']?>" from_cid_id="cid_<?= $editinfo['id']?>" for_type_id="type_<?= $editinfo['id']?>">
						<?php
							foreach($this->ad_sub_type as $k => $v){
								 echo $k == $editinfo['type']?"<option value ='$k' selected>$v</option>":"<option value ='$k'>$v</option>";   
							}
						?>
					</select>
					
					<hr>投放周期
					<input type="text" class="input_Calendar" onclick="WdatePicker({startDate:'<?= date('Y-m-d',(TIMESTAMP + 604800))?> 0:0:0',dateFmt:'yyyy-MM-dd HH:mm:ss',highLineWeekDay:true,readonly:true})" name="starttime[<?= $editinfo['id']?>][]" size="30" value="<?= $editinfo['starttime']?date('Y-m-d H:i:s',$editinfo['starttime']):''?>"> - <input type="text" class="input_Calendar" onclick="WdatePicker({startDate:'<?= date('Y-m-d',(TIMESTAMP + 604800))?> 0:0:0',dateFmt:'yyyy-MM-dd HH:mm:ss',highLineWeekDay:true,readonly:true})" name="endtime[<?= $editinfo['id']?>][]" size="30"  value="<?= $editinfo['endtime']?date('Y-m-d H:i:s',$editinfo['endtime']):''?>">
					广告用途
					<select name="mode[<?= $editinfo['id']?>][]">
						<option value="2" <?= $editinfo['mode'] == "2"?'selected':''?>>​购买​</option>
					    <option value="1" <?= $editinfo['mode'] == "1"?'selected':''?>>​内部推广​</option>
					    <option value="3" <?= $editinfo['mode'] == "3"?'selected':''?>>​补量​</option>
					    <option value="4" <?= $editinfo['mode'] == "4"?'selected':''?>>​赠送​</option>
					</select>
					广告时长
					<input type="text" name="tp_time[<?= $editinfo['id']?>][]" size="5" value="<?= $editinfo['tp_time']?>">秒
					频次控制
					<? $freq = explode('_',$editinfo['freq']);?>
					<input type="text" name="freq_day[<?= $editinfo['id']?>][]" size="5" value="<?= $freq[0]?>" nd_check>天 <input type="text" name="freq_num[<?= $editinfo['id']?>][]" size="5" value="<?= $freq[1]?>" nd_check>次
					
					<hr>
					客户需求|<input type="checkbox" name="is_group[<?= $editinfo['id']?>][]" class="is_group" id="is_group_<?= $editinfo['id']?>"<?= $editinfo['is_group'] == 1?'checked':''?>><label for="is_group_<?= $editinfo['id']?>">成组投放</label>
					<textarea rows="5" cols="12" name="area[<?= $editinfo['id']?>][]" class="city_input" id="area_<?= $editinfo['id']?>" <?= $editinfo['status']==0 || ($editinfo['status']==2 && $editinfo['result']==2)?'':'readonly'?>><?= $editinfo['area']?></textarea>
					<?= $this->GetContrast($editinfo['id']);?>
					<font color="orange">(按CPM售卖的广告禁止设置为0)</font>
					<?php if($editinfo['status'] == 0){?>
						<br><iframe width="450" height="45" src="./?action=Tackle&do=InputSwitchForm&id=area_<?= $editinfo['id']?>&symbol=_" scrolling="no" border="0" frameborder="0">浏览器不支持嵌入式框架，或被配置为不显示嵌入式框架。</iframe>
						<a target="_blank" href="./etc/xls/multicities_input_demo.xls">[Demo]</a>
					<?php }?>
					
					<hr>定向频道
					<? $channel_arr = explode('_',$editinfo['channel']);?>
					<input class="checkall" type="checkbox" id="channels_<?= $editinfo['id']?>" checked><a>全选/全不选</a><br/>
					<? foreach(Core::$vars['Channel'] as $k => $v){ ?>
						<input type="checkbox" name="channel[<?= $editinfo['id']?>][]" class="channels_<?= $editinfo['id']?>" value="<?= $k?>" <?= in_array($k, $channel_arr)?'checked':''?> id="<?= "channel_$editinfo[id]_$v"?>"><label for="<?= "channel_$editinfo[id]_$v"?>"><?= $v?></label>
					<? }?>
					
					<hr>投放时间
					<? $display_hour_arr = explode(',',$editinfo['display_hour']);?>
					<input class="checkall" type="checkbox" id="display_hours_<?= $editinfo['id']?>" checked><a>全选/全不选</a><br/>
					<? for($i=0;$i<24;$i++){ ?>
					<input type="checkbox" name="display_hour[<?= $editinfo['id']?>][]" class="display_hours_<?= $editinfo['id']?>" value="<?= $i?>" <?= !empty($editinfo['display_hour'])&&in_array($i, $display_hour_arr)?'checked':''?> id="<?= "display_hour_$editinfo[id]_$i"?>"><label for="<?= "display_hour_$editinfo[id]_$i"?>"><?= $i?></label>
					<? }?>
					
					<hr>关键词
					<input type="text" name="keyword[<?= $editinfo['id']?>][]" size="80" value="<?= $editinfo['keyword']?>">(关键字之间请用空格符间隔，例如：美女 视频 周杰伦)
					
					<hr>项目备注
					<textarea rows="2" cols="100" name="message[<?= $editinfo['id']?>][]"><?= $editinfo['message']?></textarea>
					
					<hr><font style="font-weight: bolder;font-style: italic;">工具栏  </font><br/>
					<?= $this->BtnS($editinfo['id'])?>
					
					<hr><font style="font-weight: bolder;font-style: italic;">订单执行状态</font><br>
						&nbsp;&nbsp;
						&nbsp;编辑状态：<?= $editinfo['status'] == 0 || ($editinfo['status'] == 2 && $editinfo['result'] == 2)?"<font color='green'>[可修改]</font>":"[不可修改]"?>
						&nbsp;审核状态：<?= $editinfo['result'] == 1?"<font color='green'>[已通过]</font>":(($editinfo['result'] == 2 && $editinfo['status'] == 2)?"<input class='input_red' type='text' size='55' readonly value='【审核失败】修改意见：{$editinfo[reason]}' />":"[尚未审核]")?>	        		
						&nbsp;资源锁定状态：<?= $editinfo['reserve_code']?$this->GetSuoLiangState($editinfo['id'],$editinfo['reserve_code']):$this->GetSuoLiangState($editinfo['id']);?>
						&nbsp;锁量授权编码：<?= $editinfo['reserve_code']?"<strong>[{$editinfo['reserve_code']}]</strong>":"-"?>
						<? if(ROLE == 'RESOURCE' && !in_array($editinfo['status'], array(0,1,3))){?>
						&nbsp;资源释放截止：<? if(!empty($editinfo[deadline])) {?><input type="text" id="deadline_delay" class="input_Calendar" onclick="WdatePicker({startDate:'<?= date('Y-m-d',TIMESTAMP+864000)?> 0:0:0',dateFmt:'yyyy-MM-dd HH:mm:ss',highLineWeekDay:true,readonly:true , onpicked:function(dp){ delay_deadline(<?= $editinfo['id']?>,dp.cal.getNewDateStr())}})" size="20" value="<?= date('Y-m-d H:i:s',$editinfo[deadline])?>"> 前<? }else{ ?>-<? }?>
						<?}else{?>
						&nbsp;资源释放截止：<?php if(!empty($editinfo[deadline])) {?><?= date('Y-m-d H:i:s',$editinfo[deadline])?> 前<? }else{ ?>-<? }?>
						<? }?>
						
					<?= $this->SchedulePanel($editinfo['id'],$editinfo['task_id']); //自动排期表重新导入、下载控制面板?>
						
					<? if($editinfo['status'] > 4){?>
						<hr><font style="font-weight: bolder;font-style: italic;">
						数据监测报告 & GAP自动统计</font> <a target="_blank" href="./etc/xls/3report_demo.xls">  [范例]</a>
						<iframe name="ReportUpload" width="850" height="45" src="./?action=Reserve&do=ReportUploadForm&id=<?= $editinfo['id']?>" scrolling="no" border="0" frameborder="0">浏览器不支持嵌入式框架，或被配置为不显示嵌入式框架。</iframe>
						<?= $this->Get3ReportsInfo($editinfo['id'])?>
					<? }?>
				</div>	
					<hr>
					<font style="font-weight: bolder;font-style: italic;">投放流程节点</font><br>
					&nbsp;&nbsp;<?= $this->GetProcessInformation($editinfo['id'])?>
					<hr>
			</td>
		</tr>
		<?php }?>
		
		<tr>
			<td>KPI说明</td>
			<td><textarea rows="3" cols="75" name="kpi" class="city_input"><?= $this->pagedata['editInfo'][0]['kpi']?></textarea></td>
		</tr>
		<tr>
			<td>提交AE</td>
			<td><?= $this->pagedata['editInfo'][0]['ae']?$this->pagedata['editInfo'][0]['ae']:ADMIN?></td>
		</tr>
		<tr>
			<td>操作时间</td>
			<td><?= $this->pagedata['editInfo'][0]['ts']?date('Y-m-d H:i:s',$this->pagedata['editInfo'][0]['ts']):date('Y-m-d H:i:s',TIMESTAMP)?><input type="hidden" name="ts" value="<?= TIMESTAMP?>"></td>
		</tr>
	    <tr>
			<td colspan="2" style="height:60px;text-align:center">
				<span class="button blue" id="sbt_btn" onclick="asyn_sbt('input_form','./?action=<?= $this->action?>&do=<?= $this->do?>&func=<?= $this->show == 'edit'?'edit':'add'?>')">保存订单</span>
				<?php if(ROLE == 'RESOURCE' && true == $this->ts_contract){?>
					<a class="button green" target="_blank" href="./?action=Order&do=FromReserveToContract&reserve_id=<?= $_GET['reserve_id']?>">(!) 推送合同</a>
				<?php }?>
				<?php if($this->show == 'edit'){?>
					<a class="button white" target="_blank" href="./?action=Reserve&do=OutPutToExcel&headerType=xls&ids=<?= $ids?>">批量导出</a>
				<?php }?>
			</td>
		</tr>
	</table>
	</form>
	<br />		
	<!-- 新单、编辑模块结束 -->
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
		<tr><td>按流程状态查询</td>
			<td><select name="status">
					<option></option>
					<option value=7>保存成功</option>
					<option value=1>正在审核</option>
					<option value=2>审核完成</option>
					<option value=3>正在撤回</option>
					<option value=4>准备投放</option>
					<option value=5>正在投放</option>
					<option value=6>投放结束</option>
				</select>
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
				<select class="cid" name="cid" id="cid">
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
			<td>按广告类型查询</td>
			<td>
				<select class="type" name="type" id="type">
					<option></option>
					<?php
						foreach($this->ad_sub_type as $k => $v){
							echo $k==$this->pagedata['editInfo']['cid']?"<option value='$k' selected>$v</option>":"<option value='$k'>$v</option>";
						}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td>按广告时长查询</td>
			<td><input type="text" name="tp_time" size="15" value=""></td>
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
			<td colspan="8">
			<?= empty($this->pagedata['list'])?'-暂无数据-':$this->pagedata['sp']?></td>
		</tr>
		<tr>
			<th width="5px"><a>ID</a></th>
			<th width="210px"><a>客户名称（品牌/产品线）</a></th>
			<th width="220px"><a>代理/区域</a></th>
			<th width="70px"><a>AE</a></th>
			<th><a>KPI说明</a></th>
			<th><a>备注信息</a></th>
			<th><a>锁量释放节点</a></th>
			<?php if($this->func == 'search' && isset($_GET['area'])){?>
			<th><a><?= $_GET['area']?>量（CPM）</a></th>
			<?php }?>
			<th width="120px">
				<span class="button green small" onclick="location.href='./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&show=add'">新单</span>
				<span class="button green small" onclick="location.href='./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&show=search'">查单</span>
			</th>
		</tr>
		<?php foreach ($this->pagedata['list'] as $data) {?>
		<tr class="<?= $data['reserve_id']?>" id="<?= $data['id']?>" style="height:70px">
			<td><a href="javascript:;" title="<?= $data['area']?>">#<?= $data['id']?></a></td>
			<td><?= $data['client']?$this->clients[$data['client']]:$data['agent']?><br><font style="color:green;font-size:11px">[<?= $data['brand']?>]</font></td>
			<td><?= $data['agent']?$data['agent']:'-'?><br><font style="color:green;font-size:11px">[<?= $data['region']=="3"?"华东":($data['region']=="2"?"华北":"华南")?>]</font></td>
	        <td><p><strong><?= $data['ae']?></strong></p></td>
	        <td><textarea rows="3" cols="31"><?= str_replace(' ', '', $data['kpi'])?></textarea></td>
	        <td><textarea rows="3" cols="31"><?= str_replace(' ', '', $data['message'])?></textarea></td>
	        <td><textarea rows="3" cols="19"><?= $this->GetSuoLiangDeadLine($data['reserve_id'])?></textarea></td>
	        <td style="text-align:center;">
	        	<ul style="padding:3px">
	        		<li><a class="button white small" onclick="location.href='./?action=<?= $_GET['action']?>&do=Rms&show=edit&reserve_id=<?= $data['reserve_id']?>'">报单详情</a></li>
	        		<li><?= $this->RedBtn($data['reserve_id']);?></li>
	        	</ul>
	        </td>
		</tr>
		<?php }?>
		<tr>
			<td colspan="8"><?= empty($this->pagedata['list'])?'-暂无数据-':$this->pagedata['sp']?></td>
		</tr>
	</table>
	<br />	
	<!-- 列表模块结束 -->	
	<?php }?>
</div>
<script type="text/javascript">
	function rtn(data){
		switch (data.rs){
			case 0:
	    		$("#sbt_btn").attr('class','button green');
	    		$("#sbt_btn").html("保存成功");
		    	setInterval(function(){
			    	<?php if($this->show == 'add'){?>
			    		location.href = './?action=Reserve&do=GoToLasted';
			    	<?php }else{?>
			    		location.reload();
			    	<?php }?>
		    	},1000)
			break;
		}
	}
	<?php if($_REQUEST['id']){?>
	$('#inputpanel_<?= intval($_REQUEST['id'])?>').fadeIn('slow');
	<?php }?>
</script>
<?php
include template('footer');
?>
