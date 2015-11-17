<?php include template('header');?>
<script type="text/javascript" src="./script/js/system.js"></script>
<div class="ctr_panel">
	<br>
	<?php if($this->show=='add'||$this->show=='edit'||$this->show=='copy') {?>
	<form id="input_form">
	<input type="hidden" name="id" value="<?= $_GET['id']?>">
	<table id="rule_input">
	    <tr>
	        <th colspan="2"><a href="javascript:;">投放规则配置</a></th>
	    </tr>
		<tr>
			<td>规则类型</td>
			<td>
				<select name="tf_type">
					<option value="1"<?= $this->pagedata['editInfo']['type'] == '1'?'selected':''?>>PC端</option>
					<option value="2"<?= $this->pagedata['editInfo']['type'] == '2'?'selected':''?>>M站</option>
					<option value="3"<?= $this->pagedata['editInfo']['type'] == '3'?'selected':''?>>APP</option>
				</select>
			</td>
		</tr>
		<tr id="common">
			<td>规则描述 <a href="javascript:;" class="add_rule"><strong>[+]</strong></a></td>
			<td><textarea name="description" rows="3" cols="120"><?= $this->pagedata['editInfo']['description']?></textarea></td>
		</tr>
		<?php if($this->show == 'edit') {?>
		<?php $rule = json_decode($this->pagedata['editInfo']['rule'],true);?>
		<?php foreach($rule as $r){?>
		<tr<?= $this->func == 'copy'?' id="add_rule"':''?>>
			<td>规则<?= $this->func == 'copy'?'<a href="javascript:;" onclick="$(this).parent().parent().remove();"><strong>[-]</strong></a>':''?> </td>
			<td>
				投放地区
				<textarea rows="1" cols="15" name="city[]" class="city_input" id="city"><?= $r['city']?></textarea>
				广告位
				<select name="cid[]" id="cid">
					<?php
						foreach($this->adstype as $k => $v){
							 echo $r['cid'] != $k?"<option value ='$k'>$v</option>":"<option value ='$k' selected>$v</option>";   
						} 
					?>
				</select>
				广告类型
				<select name="type[]" id="type">
					<?php
						foreach($this->ad_sub_type as $k => $v){
							 echo $r['type'] != $k?"<option value ='$k'>$v</option>":"<option value ='$k' selected>$v</option>";   
						} 
					?>
				</select>
				视频时长
				<input type="text" name="totaltime[]" size="3" value="<?= $r['totaltime']?>">分钟
				最大帖数
				<input type="text" name="maxnum[]" size="3" value="<?= $r['maxnum']?>">
				最大广告时长
				<input type="text" name="maxtime[]" size="3" value="<?= $r['maxtime']?>">秒
			</td>
		</tr>
		<?php }?>
		<?php }else{?>
		<tr id="add_rule">
			<td>规则 <a href="javascript:;" onclick="$(this).parent().parent().remove();"><strong>[-]</strong></a></td>
			<td>
				投放地区
				<textarea rows="1" cols="15" name="city[]" class="city_input" id="city"></textarea>
				<hr>视频时长
				<input type="text" name="totaltime[]" size="3"/> 分钟
				广告位
				<select name="cid[]" id="cid">
					<?php
						foreach($this->adstype as $k => $v){
							 echo "<option value ='$k'>$v</option>";   
						} 
					?>
				</select>
				广告类型
				<select name="type[]" id="type">
					<?php
						foreach($this->ad_sub_type as $k => $v){
							 echo "<option value ='$k'>$v</option>";   
						} 
					?>
				</select>
				最大帖数
				<input type="text" name="maxnum[]" size="3"/>
				最大广告时长
				<input type="text" name="maxtime[]" size="3"/> 秒
			</td>
		</tr>
		<?php }?>
		<?php if($this->show != 'edit' || $this->func == 'copy'){?>
	    <tr>
			<td colspan="2" style="height:60px;text-align:center"><span class="button blue" id="sbt_btn" onclick="asyn_sbt('input_form','./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&func=add')">确认提交</span>
		</tr>
		<?php }?>
	</table>
	</form>
	<script type="text/javascript">
	function rtn(data){
		switch (data.rs){
			case 0:
				$("#sbt_btn").html('操作成功');
				$("#sbt_btn").attr('class','button green');
				location.href="./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&show=list";
			break;
		}
	}
	</script>
	<br />		
	<?php }?>
	<?php if($this->show=='list') {?>
	<table>
	<tr>
			<td colspan="4"><?php echo empty($this->pagedata['list'])?'-暂无数据-':$this->pagedata['sp']?></td>
		</tr>
		<tr>
			<th width="20px"><a>版本号</a></th>
			<th width="80px"><a>规则类型</a></th>
			<th><a>规则描述</a></th>
			<th width="15px"><input class="button green medium" type="button" onclick="location.href='./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&show=add'" value="新增投放规则"/></th>
		</tr>
		<?php foreach ($this->pagedata['list'] as $data) { ?>
		<tr>
			<td>#<?= "{$data['ts']}_{$data['id']}"?></td>
			<td><?= $data['type']=='1'?'PC端':($data['type']==2?'M站':'APP')?></td>
			<td><?= $data['description']?></td>
	        <td>
	        	<p align="center">
	        		<input class="button white small" type="button" onclick="location.href='./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&show=edit&id=<?= $data['id']?>'" value="详细">
	        		<input class="button green small" type="button" onclick="location.href='./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&show=edit&func=copy&id=<?= $data['id']?>'" value="复制">
	        	</p>
	        </td>
		</tr>
		<?php }?>
		<tr>
			<td colspan="4"><?php echo empty($this->pagedata['list'])?'-暂无数据-':$this->pagedata['sp']?></td>
		</tr>
	</table>
	<br>		
	<?php }?>
<?php
include template('footer');
?>