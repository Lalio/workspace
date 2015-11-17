<?php include template('header');?>
<div class="ctr_panel">
	<br>
	<?php if($this->show=='add'||$this->show=='edit') {?>
	<form id="input_form">
	<input type="hidden" name="cid" value="<?= $_GET['id']?>">
	<table>
	    <tr>
	        <th colspan="2"><a href="javascript:;">系统广告投放位配置</a></th>
	    </tr>
	    <?php if($this->show == 'edit') {?>
		<tr>
			<td>广告位编号</td>
			<td><input type="text" name="cid" size="20"  value="<?= $this->pagedata['editInfo']['cid']?>" readonly></td>
		</tr>
		<?php }?>
		<tr>
			<td>广告位名称</td>
			<td><input type="text" name="cname" size="50"  value="<?= $this->pagedata['editInfo']['cname']?>"></td>
		</tr>
		<tr>
			<td>广告位类型</td>
			<td><input type="text" name="type" size="30"  value="<?= $this->pagedata['editInfo']['type']?>"></td>
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
				$("#sbt_btn").hide();
				$("#sbt_btn").html('操作成功');
				$("#sbt_btn").attr('class','button green');
				$("#sbt_btn").fadeIn();
				setTimeout("location.reload()",500);
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
			<th><a>广告位编号</a></th>
			<th><a>名称</a></th>
			<th><a>类型</a></th>
			<th><a>状态</a></th>
			<th><input class="button green medium" type="button" onclick="location.href='./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&show=add'" value="新增记录"/></th>
		</tr>
		<?php foreach ($this->pagedata['list'] as $data) {?>
		<tr>
			<td>#<?= $data['cid']?></td>
			<td><?= $data['cname']?></td>
			<td><?= $data['type']?></td>
			<td><p align="center"><a class="state_switch" id="<?= $data['cid']?>" state="<?= $data['flag']?>" table="channel" href="javascript:;"><?= $data['flag']==0?'<font color="green"> [有效] </font>':'<font color="red"> [无效] </font>'?></a></p></td>
	        <td>
	        	<p align="center">
	        		<input class="button white small" type="button" onclick="location.href='./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&show=edit&cid=<?= $data['cid']?>'" value="修改">
	        		<input class="delete_btn button black small" type="button" id="<?= $data['cid']?>" url="./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&func=delete" value="删除">
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