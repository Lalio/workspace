<?php include template('header');?>
<div class="ctr_panel">
	<br>
	<?php if($this->show=='add'||$this->show=='edit') {?>
	<form id="input_form">
	<input type="hidden" name="id" value="<?= $_GET['id']?>">
	<table>
	    <tr>
	        <th colspan="2"><a href="javascript:;">容器配置</a></th>
	    </tr>
		<tr>
			<td>模板名称</td>
			<td><input type="text" name="t_name" size="70"  value="<?= $this->pagedata['editInfo']['t_name']?>"></td>
		</tr>
		<tr>
			<td>模板类别</td>
			<td>
				<select name="type">
					<option value="0">56模板</option>
					<option value="1">第三方模板</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>模板地址</td>
			<td><input type="text" name="t_url" size="70"  value="<?= $this->pagedata['editInfo']['t_url']?>"></td>
		</tr>
		<tr>
			<td>模板说明</td>
			<td><textarea name="description" rows="5" cols="150"><?= $this->pagedata['editInfo']['description']?></textarea></td>
		</tr>
	    <tr>
			<td colspan="2" style="height:60px;text-align:center"><span class="button blue" id="sbt_btn" onclick="asyn_sbt('input_form','./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&func=<?= $_GET[show]=='edit'?'edit':'add'?>')">确认提交</span>
		</tr>
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
			<td colspan="6"><?php echo empty($this->pagedata['list'])?'-暂无数据-':$this->pagedata['sp']?></td>
		</tr>
		<tr>
			<th><a>编号</a></th>
			<th><a>模板名称</a></th>
			<th><a>模板地址</a></th>
			<th><a>模板说明</a></th>
			<th><a>状态</a></th>
			<th><input class="button green medium" type="button" onclick="location.href='./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&show=add'" value="添加模板"/></th>
		</tr>
		<?php foreach ($this->pagedata['list'] as $data) {?>
		<tr>
			<td>#<?= $data['id']?></td>
			<td><?= $data['t_name']?></td>
			<td><?= $data['t_url']?></td>
			<td><?= $data['description']?></td>
			<td><p align="center"><a class="state_switch" id="<?= $data['id']?>" state="<?= $data['state']?>" table="ad_templates" href="javascript:;"><?= $data['state']==0?'<font color="green"> [有效] </font>':'<font color="red"> [无效] </font>'?></a></p></td>
	        <td>
	        	<p align="center">
	        		<!-- 
	        		<input type="button" class="action_btn" func="up" dataid="<?= $data['id']?>" ts="<?= $data['ts']?>" value="上移1位">
	        		<input type="button" class="action_btn" func="down" dataid="<?= $data['id']?>" ts="<?= $data['ts']?>" value="下降1位">
	        		<input type="button" class="action_btn" func="top" dataid="<?= $data['id']?>" ts="<?= $data['ts']?>" value="置顶">
	        		 -->
	        		<input class="button white small" type="button" onclick="location.href='./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&show=edit&id=<?= $data['id']?>'" value="修改">
	        		<input class="delete_btn button black small" type="button" id="<?= $data['id']?>" url="./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&func=delete" value="删除">
	        	</p>
	        </td>
		</tr>
		<?php }?>
		<tr>
			<td colspan="6"><?php echo empty($this->pagedata['list'])?'-暂无数据-':$this->pagedata['sp']?></td>
		</tr>
	</table>
	<br>		
	<?php }?>
<?php
include template('footer');
?>