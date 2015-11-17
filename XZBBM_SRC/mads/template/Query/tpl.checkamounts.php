<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;    
}
?>
<?php include template('header');?>
<script type="text/javascript" src="./script/js/WdatePicker.js"></script>
<script type="text/javascript" src="./script/js/query.js"></script>
<script type="text/javascript" src="./script/js/order.js"></script>
<div class="ctr_panel">
	<br>
	<?php if($this->show == 'add'||$this->show == 'edit') {?>
	<!-- 新增、编辑模块开始 -->
	<form id="input_form">
	<input type="hidden" name="id" value="<?= $_GET['id']?>">
	<input type="hidden" name="status" value="0">
	<input type="hidden" name="applicant" value="<?= ADMIN?>">
	<input type="hidden" name="for_rid" value="<?= $_REQUEST['r_id']?>">
	<table>
	    <tr>
	        <th colspan="2"><a href="javascript:;">任务设定</a></th>
	    </tr>
		<tr>
			<td width="10%">时间定向</td>
			<?php if(!isset($_REQUEST['r_id'])){?>
				<td width="90%"><input type="text" class="input_Calendar" onclick="WdatePicker({startDate:'<?= date('Y-m-d',TIMESTAMP)?> 0:0:0',dateFmt:'yyyy-MM-dd HH:mm:ss',highLineWeekDay:true,readonly:true})" readOnly name="starttime" size="30" value="<?= $this->pagedata['starttime']?date('Y-m-d H:i:s',$this->pagedata['starttime']):''?>"> - <input type="text" class="input_Calendar" onclick="WdatePicker({startDate:'<?= date('Y-m-d',TIMESTAMP)?> 0:0:0',dateFmt:'yyyy-MM-dd HH:mm:ss',highLineWeekDay:true,readonly:true})" readOnly name="endtime" size="30"  value="<?= $this->pagedata['endtime']?date('Y-m-d H:i:s',$this->pagedata['endtime']):''?>"></td>
			<?php }else{?>
				<td width="90%"><input type="text" readonly name="starttime" size="30" value="<?= $this->pagedata['starttime']?date('Y-m-d H:i:s',$this->pagedata['starttime']):''?>"> - <input type="text" readonly name="endtime" size="30"  value="<?= $this->pagedata['endtime']?date('Y-m-d H:i:s',$this->pagedata['endtime']):''?>"></td>
			<?php }?>
		</tr>
		<tr><td>定向频道</td>
			<? $channel_arr = explode('_',$this->pagedata['channel']);?>
			<td>
				<input class="checkall" type="checkbox" id="channels"><a>全选/全不选</a><br/>
				<? foreach(Core::$vars['Channel'] as $k => $v){ ?>
					<input type="checkbox" name="channel[]" class="channels" value="<?= $k?>" <?= in_array($k, $channel_arr)?'checked':''?> id="<?= "channel_$v";?>"><label for="<?= "channel_$v";?>"><?= $v?></label>
				<? }?>
			</td>
		</tr>
		<tr>
			<td>区域定向</td> 
            <td>
            	<textarea rows="3" cols="90" name="area" class="city_input" id="city"><?= $this->pagedata['areas']?></textarea><br>
            	<?php if(!isset($_REQUEST['r_id'])){?>
	            	<select class="province_selected" related_id="01"><option>-- 请选择省份 --</option></select>
	                <select class="city_selected" id="01" related_id="city"><option>-- 请选择城市 --</option></select>
	                (城市之间请用英文 , 分隔)
                <?php }?>
            </td>
		</tr>
		<tr>
			<td>广告形式</td>
			<td>
				<select name="adtype" class="cid" id="cid">
					<?php
						foreach($this->adstype as $k => $v){ 
							echo ( $k == $this->pagedata['adtype'] )?"<option value ='$k' selected>$v</option>":(isset($_REQUEST['r_id'])?"":"<option value ='$k'>$v</option>");   
						}
					?>
				</select>
				<input class="adtype_quicksearch" for_id="cid" type="text" value="快速翻查" onclick="this.value=''" onblur="this.value='快速翻查'" size="10" x-webkit-speech="true">
			</td>
		</tr>
		<tr>
			<td>广告类型</td>
			<td>
				<select name="type" class="type" id="type">
				    <option></option>
					<?php
						foreach($this->ad_sub_type as $k => $v){
							 echo $k==$this->pagedata['type']?"<option value ='$k' selected>$v</option>":(isset($_REQUEST['r_id'])?"":"<option value ='$k'>$v</option>");   
						} 
					?>
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
							     echo $k==$this->pagedata['client']?"<option value ='$k' selected>$v</option>":(isset($_REQUEST['r_id'])?"":"<option value ='$k'>$v</option>");   
						    //}
                        }
					?>
				</select>
				<input class="client_quicksearch" for_id="client" type="text" value="快速翻查" onclick="this.value=''" onblur="this.value='快速翻查'" size="10">
			</td>		
		</tr>
		<tr>
			<td>频次控制</td>
			<? $freq = explode('_',$this->pagedata['freq']);?>
			<td><input type="text" name="freq_day" size="5" class="uncheck" value="<?= $freq[0]?>">天 <input type="text" name="freq_num" size="5" class="uncheck" value="<?= $freq[1]?>">次          <input type="checkbox" name="double_pk" value="enable" id="double_pk">双频控</td>
		</tr>
		<tr>
			<td>广告时长</td>
			<td><input type="text" name="totaltime" size="5" class="uncheck" value="<?= $this->pagedata['totaltime']?>">秒</td>
		</tr>
		<tr>
			<td>投放时间</td>
			<? $display_hour_arr = explode(',',$this->pagedata['display_hour']);?>
			<td>
				<input class="checkall" type="checkbox" id="display_hours"><a>全选/全不选</a><br/>
				<? for($i=0;$i<24;$i++){ ?>
				<input type="checkbox" name="display_hour[]" class="display_hours" value="<?= $i?>" <?= !empty($this->pagedata['display_hour'])&&in_array($i, $display_hour_arr)?'checked':''?> id="<?= "display_hour_$i"?>"><label for="<?= "display_hour_$i"?>"><?= $i?></label>
				<? }?>
			</td>
		</tr>
		<tr>
			<td>关键字</td>
			<td><input type="text" name="keyword" size="80"  value="<?= $this->pagedata['keyword']?>">(关键字之间请用空格符间隔，例如：美女 视频 周杰伦)</td>
		</tr>
		<?php if('AE' != ROLE){?>
		<tr>
			<td>查量方式</td>
			<td>
				<select name="op_type">
					<option value="1" <?= $this->pagedata['op_type']==1?'selected':''?>>包含在投、预定</option>
				    <option value="0" <?= $this->pagedata['op_type']==0?'selected':''?>>不包含在投、预定</option>
				</select>
			</td>
		</tr>
		<?php }?>
		<?php if('DEVELOPER' == ROLE){?>
		<tr>
			<td>系统版本</td>
			<td>
				<select name="version">
					<option value="2" <?= $this->pagedata['version']==2?'selected':''?> selected>2</option>
				    <option value="1" <?= $this->pagedata['version']==1?'selected':''?>>1</option>
				</select>
			</td>
		</tr>
		<?php }?>
		<tr>
			<td>提交时间</td>
			<td><?= $this->pagedata['require_ts']?date('Y-m-d H:i:s',$this->pagedata['require_ts']):date('Y-m-d H:i:s',TIMESTAMP)?><input type="hidden" name="require_ts" value="<?= TIMESTAMP?>"></td>
		</tr>
	    <tr>
			<td colspan="2" style="height:60px;text-align:center">
                <span class="button blue" id="sbt_btn" onclick="asyn_sbt('input_form','./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&func=<?= $_GET[show]=='edit'?'edit':'add'?>')">确认提交</span>
			</td>
		</tr>
	</table>
	</form>
	<script type="text/javascript">
	function rtn(data){
		switch (data.rs){
			case 0:
				$("#sbt_btn").html("提交成功");
				$("#sbt_btn").attr('class','button green');
				location.href="./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&show=list";
			break;
		}
	}
	</script>
	<br />		
	<!-- 新增、编辑模块结束 -->
	<?php if(ROLE == 'AE' && $_REQUEST['r_id']){ //AE查量界面所有的字段为只读，所有参数以预订单提单数据为准?>
		<script type="text/javascript">
			$("input,select,textarea").not("#double_pk").attr("readonly",true);
			$("input,select,textarea").not("#double_pk").click(function(){
				return false;
			});
		</script>
	<?php }?>
	<?php }?>
	
	<?php if($this->show=='list') {?> 
	<!-- 列表模块开始 -->
	<table>
		<tr>
			<td colspan="11"><?= empty($this->pagedata['list'])?'-暂无数据-':$this->pagedata['sp']?></td>
		</tr>
		<tr>
			<th width="15px"><a>ID</a></th>
			<th width="45px"><a>区域</a></th>
			<th><a>频道定向</a></th>
			<th width="110px"><a>频控定向</a></th>
			<th width="175px"><a>时间定向</a></th>
			<th width="235px"><a>广告形式</a></th>
			<th width="80px"><a>广告类型</a></th>
			<th width="175px"><a>查量时间</a></th>
			<th width="63px"><a>执行</a></th>
			<th width="153px"><a>查量结果（CPM）</a></th>
			<th width="10px">
				<?php if(ROLE != 'AE'){?>
				<input class="button green medium" type="button" onclick="location.href='./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&show=add'" value="新建"/>
				<?php }?>
			</th>
		</tr>
		<?php foreach ($this->pagedata['list'] as $data) { $task = json_decode($data['input'],true);?>
		<tr>
			<td>#<?= $data['id']?></td>
			<td><textarea rows="6" cols="6"><?= str_replace("_", "\r\n", $task['area'])?></textarea></td>
			<td><textarea rows="6" cols="12"><?= str_replace("_", "\r\n", $this->ChannelSwt($task['channel']))?></textarea></td>
			<td><? if(empty($task['freq_day'])){?>未指定<?}else{?><?= $task['freq_day']?>天<?= $task['freq_num']?>次<? }?>
			    <? if($task['double_pk'] == 'enable'){?><br><font style="color:blue;font-size:8px">[双频控]</font><? }?>
			    <? if(ROLE != 'AE'){?><br><font style="color:green;font-size:8px">[<?= $data['op_type'] == 1?'包含在投、预定':'不包含在投、预定'?>]</font><? }?>
				<? if($task['version'] == 2){?><br><font style="color:red;font-size:8px"><?= $task['version'] == '2'?'[新系统]':''?></font><? }?>
			</td>
			<td>开始：<?= date('Y-m-d H:i:s',$task['starttime'])?><br />结束：<?= date('Y-m-d H:i:s',$task['endtime'])?></td>
	        <td><?= $this->adstype[$task['cid']]?></td>
			<td><?= $this->ad_sub_type[$task['type']]?></td>
			<td><strong><?= date('Y年m月d日 H:i:s',$data['require_ts'])?></strong></td>
	        <td><p style="align:center"><?= floor($data['exec_ts']/60)?>分<?= $data['exec_ts']%60?>秒</p></td>
	        <td><p style="align:center"><?
	        	switch($data['status']){
	        		case 0 : echo '<font color="green" class="queue" id="'.$data['id'].'">正在排队<font>';break;
	        		case 1 : echo '<font color="blue">正在查量 (<span class="processing" id="'.$data['id'].'">'.(int)($data['already_cell']/($data['total_cell']+1)*100).'</span>%)<font>';break;
	        		case 2 : echo "<textarea rows='6' cols='16'>".str_replace("<br>", "\r\n", $this->GetCheckAmountResult($data['id'],false))."</textarea>";break;
	        		case 3 : echo '<font color="green">查量失败<font>';break;
	        		case 4 : case 5: echo '<font color="black">强制终止<font>';break;
	        	}
	        ?></p></td>
	        <td>
	        	<p align="center">
	        		<!-- 
	        		<input type="button" class="action_btn" func="up" dataid="<?= $data['id']?>" ts="<?= $data['ts']?>" value="上移1位">
	        		<input type="button" class="action_btn" func="down" dataid="<?= $data['id']?>" ts="<?= $data['ts']?>" value="下降1位">
	        		<input type="button" class="action_btn" func="top" dataid="<?= $data['id']?>" ts="<?= $data['ts']?>" value="置顶">
	        		<input class="button white small" type="button" onclick="location.href='./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&show=edit&id=<?= $data['id']?>'" value="修改">
	        		 -->
	        		<? if($data['status'] != 1){?>
	        		<input class="delete_btn button black small" type="button" id="<?= $data['id']?>" url="./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&func=delete" value="删除">
	        	    <? }else{ ?>
	        	    <input class="button grey small" type="button" onclick="asyn_trigger('./?action=Query&do=KillProcess&id=<?= $data['id']?>');" value="终止">
	        	    <? }?> 
	        	</p>
	        </td>
		</tr>
		<?php }?>
		<tr>
			<td colspan="11"><?= empty($this->pagedata['list'])?'-暂无数据-':$this->pagedata['sp']?></td>
		</tr>
	</table>
	<br />	
	<!-- 列表模块结束 -->	
	<?php }?>
</div>
<?php
include template('footer');
?>