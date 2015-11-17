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
	<input type="hidden" name="tid" value="<?= $_GET['tid']?>">
	<table>
	    <tr>
	        <th colspan="2"><a href="javascript:;">任务设定</a></th>
	    </tr>
		<tr>
			<td width="20%">查量周期</td>
			<td width="80%"><input type="text" class="input_Calendar" onclick="WdatePicker()" readOnly="true" name="starttime" size="30" value="<?= $this->pagedata['starttime']?date('Y-m-d',$this->pagedata['starttime']):''?>"> - <input type="text" class="input_Calendar" onclick="WdatePicker()" readOnly name="endtime" size="30"  value="<?= $this->pagedata['endtime']?date('Y-m-d',$this->pagedata['endtime']):''?>"></td>
		</tr>
<!-- 	<tr>
		<td>聚合查询</td>
			<td>
				<select name="is_together">
				    <option value="1" <?= $this->pagedata['type']==1?'selected':''?>>否</option>
				    <option value="0" <?= $this->pagedata['type']==0?'selected':''?>>是</option>
				</select>
			</td>
		</tr> 
		<tr>
		<td>统计类型</td>
			<td>
				<select name="type">
				    <option value="pv" <?= $this->pagedata['type']=='pv'?'selected':''?>>PV</option>
				    <option value="uv" <?= $this->pagedata['type']=='uv'?'selected':''?>>UV</option>
				</select>
			</td>
		</tr> -->
		<tr>
		<td>AIDS(多个aid请用英文半角,分隔)</td>
			<td>
				<textarea name="aids" cols="50" rows="3" id="aids"></textarea><br /><select class="contract_selected" onchange="getrelatedids(this.value)"><option value="">-- 按合同号导入 --</option></select>
			</td>
		</tr>
		<tr>
		<td>查量类型</td>
			<td>
				<select name="cmd">
					<option value="1">按站内外来源查PV量</option>
					<option value="2">按站内外来源查UV量</option>
					<option value="3">按各独立UV查量</option>
					<option value="4">联合频控UV查量</option>
					<option value="9">返量PV查询</option>
				</select>
			</td>
		</tr>
		<tr>
		<td>查量说明</td>
			<td>
				<textarea name="info" cols="50" rows="1"></textarea>
			</td>
		</tr>
		<tr>
			<td>创建时间</td>
			<td><?= $this->pagedata['ts']?date('Y-m-d H:i:s',$this->pagedata['ts']):date('Y-m-d H:i:s',TIMESTAMP)?><input type="hidden" name="ts" value="<?= TIMESTAMP?>"></td>
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
	<?php }?>
	
	<?php if($this->show=='list') {?> 
	<!-- 列表模块开始 -->
	<table>
		<tr>
			<td colspan="8"><?= empty($this->pagedata['list'])?'-暂无数据-':$this->pagedata['sp']?></td>
		</tr>
		<tr>
			<th><a>任务编号</a></th>
			<th><a>查量类型</a></th>
			<th><a>统计类型</a></th>
			<th width="20%"><a>查量周期</a></th>
			<th width="20%"><a>说明</a></th>
			<th width="20%"><a>查量结果</a></th>
			<th><a>任务提交时间</a></th>
			<th><input class="button green medium" type="button" onclick="location.href='./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&show=add'" value="新增查量任务"/></th>
		</tr>
		<?php foreach ($this->pagedata['list'] as $data) {?>
		<tr>
			<td>#<?= $data['id']?></td>
			<td><?= $this->qtype[$data[cmd]]?></td>
			<td><?= strtoupper($data['type'])?></td>
			<td>开始：<?= date('Y年m月d日',$data['starttime'])?><br />结束：<?= date('Y年m月d日',$data['endtime'])?></td>
			<td><?= $data['info']?></td>
	        <td><p style="align:center"><span id="puv_queue_<?= $data['id']?>"><font color="green" class="puv_queue" id="<?= $data['id']?>" idf="<?= date('Y-m-d',$data['ts'])."/{$data['id']}"?>">生成统计报表</font></span></p></td>
	        <td><p style="align:center"><?= date('Y-m-d H:i:s',$data['ts'])?></p></td>
	        <td>
	        	<p align="center">
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
			<td colspan="8"><?= empty($this->pagedata['list'])?'-暂无数据-':$this->pagedata['sp']?></td>
		</tr>
	</table>
	<br />	
	<!-- 列表模块结束 -->	
	<?php }?>
</div>
<?php
include template('footer');
?>