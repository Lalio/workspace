<?php include template('header');?>
<div class="ctr_panel">
	<br>
	<?php if($this->show=='add'||$this->show=='edit') {?>
	<form id="input_form">
	<input type="hidden" name="id" value="<?= $_GET['id']?>">
	<table>
	    <tr>
	        <th colspan="2"><a href="javascript:;">自制节目账号配置</a></th>
	    </tr>
		<tr>
			<td>自制节目名称</td>
			<td><input type="text" name="show_name" size="70"  value="<?= $this->pagedata['editInfo']['show_name']?>"></td>
		</tr>
		<tr>
			<td>管理员账号（多个管理员用,分隔）</td>
			<td><input type="text" name="account" size="70"  value="<?= $this->pagedata['editInfo']['account']?>"></td>
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
			<td colspan="5"><?php echo empty($this->pagedata['list'])?'-暂无数据-':$this->pagedata['sp']?></td>
		</tr>
		<tr>
			<th><a>编号</a></th>
			<th><a>自制节目名称</a></th>
			<th><a>管理员账号</a></th>
			<th><a>状态</a></th>
			<th><input class="button green medium" type="button" onclick="location.href='./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&show=add'" value="新增记录"/></th>
		</tr>
		<?php foreach ($this->pagedata['list'] as $data) {?>
		<tr>
			<td>#<?= $data['id']?></td>
			<td><?= $data['show_name']?></td>
			<td><?= $data['account']?></td>
			<td><p align="center"><a class="state_switch" id="<?= $data['id']?>" state="<?= $data['state']?>" table="flash_show2account" href="javascript:;"><?= $data['state']==0?'<font color="green"> [有效] </font>':'<font color="red"> [无效] </font>'?></a></p></td>
	        <td>
	        	<p align="center">
	        		<input class="button white small" type="button" onclick="location.href='./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&show=edit&id=<?= $data['id']?>'" value="修改">
	        		<input class="delete_btn button black small" type="button" id="<?= $data['id']?>" url="./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&func=delete" value="删除">
	        	</p>
	        </td>
		</tr>
		<?php }?>
		<tr>
			<td colspan="5"><?php echo empty($this->pagedata['list'])?'-暂无数据-':$this->pagedata['sp']?></td>
		</tr>
	</table>
	<br>		
	<?php }?>
</div>
<?php
include template('footer');
?>