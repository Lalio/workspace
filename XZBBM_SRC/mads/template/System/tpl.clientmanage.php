<?php include template('header');?>
<div class="ctr_panel">
	<br>
	<?php if($this->show=='add'||$this->show=='edit') {?>
	<form id="input_form">
	<input type="hidden" name="id" value="<?= $_GET['id']?>">
	<table>
	    <tr>
	        <th colspan="2"><a href="javascript:;">广告客户配置</a></th>
	    </tr>
	    <tr>
			<td>客户编号</td>
			<td><input type="text" name="vid" size="15"  value="<?= $this->pagedata['editInfo']['vid']?>"></td>
		</tr>
		<tr>
			<td>客户名称</td>
			<td><input type="text" name="vname" size="70"  value="<?= $this->pagedata['editInfo']['vname']?>"></td>
		</tr>
		<tr>
			<td>损耗率</td>
			<td><input type="text" name="lossrate" size="40"  value="<?= $this->pagedata['editInfo']['lossrate']?>">（取值范围 0.00~1.00 eg 0.85）</td>
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
			<th><a>客户编号</a></th>
			<th><a>客户名称</a></th>
			<!-- <th><a>状态</a></th> -->
			<th>
				<input class="button green small" type="button" onclick="location.href='./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&show=add'" value="新增"/>
				<input class="button green small" type="button" onclick="location.href='./?action=<?=$_GET['action']?>&do=<?= $_GET['do']?>&show=search'" value="搜索"/>
			</th>
		</tr>
		<?php foreach ($this->pagedata['list'] as $data) {?>
		<tr>
			<td>#<?= $data['vid']?></td>
			<td><?= $data['vname']?></td>
			<!-- <td><p align="center"><a class="state_switch" id="<?= $data['id']?>" state="<?= $data['state']?>" table="flash_show2account" href="javascript:;"><?= $data['state']==0?'<font color="green"> [有效] </font>':'<font color="red"> [无效] </font>'?></a></p></td> -->
	        <td>
	        	<p align="center">
	        		<input class="button white small" type="button" onclick="location.href='./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&show=edit&id=<?= $data['vid']?>'" value="修改">
	        		<input class="delete_btn button black small" type="button" id="<?= $data['vid']?>" url="./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&func=delete" value="删除">
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
	<?php if($this->show=='search'){?>
	<form id="searchform" method="get" action="">
	<input type="hidden" name="action" value="<?= $this->action?>">
	<input type="hidden" name="do" value="<?= $this->do?>">
	<input type="hidden" name="func" value="search">
	<input type="hidden" name="show" value="list">
	<table>
		<tr>
			<th colspan="2"><a href="javascript:;">客户查询</a></th>
		</tr>
		<tr>
			<td>客户名称</td>
			<td><input type="text" name="vname" size="50" value=""></td>
		</tr>
		<tr>
			<td colspan="2" style="height:60px;text-align:center">
				<span class="button blue" id="sbt_btn" onclick="searchform.submit();">搜索</span>
			</td>
		</tr>
	</table>
	</form>
	<br />
	<?php }?>
</div>
<?php
include template('footer');
?>
