<?php include template('header');?>
<div class="ctr_panel">
	<br>
	<?php if($this->show=='add'||$this->show=='edit') {?>
	<form id="input_form">
	<input type="hidden" name="id" value="<?= $_GET['id']?>">
	<table>
	    <tr>
	        <th colspan="2"><a href="javascript:;">商业客户账号配置</a></th>
	    </tr>
		<tr>
			<td>用户名</td>
			<td><input type="text" name="username" size="40"  value="<?= $this->pagedata['editInfo']['username']?>"></td>
		</tr>
		<tr>
			<td>商业客户名称</td>
			<td><input type="text" name="cname" size="70"  value="<?= $this->pagedata['editInfo']['cname']?>"></td>
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
			<th><a>用户名</a></th>
			<th><a>商业客户名称</a></th>
			<th><a>状态</a></th>
			<th><input class="button green medium" type="button" onclick="location.href='./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&show=add'" value="新增记录"/></th>
		</tr>
		<?php foreach ($this->pagedata['list'] as $data) {?>
		<tr>
			<td>#<?= $data['id']?></td>
			<td><a href="http://i.56.com/l/u/<?= $data['username']?>" target="_blank"><?= $data['username']?></a></td>
			<td><?= $data['cname']?></td>
			<td><p align="center"><a class="state_switch" id="<?= $data['id']?>" state="<?= $data['status']?>" table="bclient" href="javascript:;"><?= $data['status']==0?'<font color="green"> [有效] </font>':'<font color="red"> [无效] </font>'?></a></p></td>
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