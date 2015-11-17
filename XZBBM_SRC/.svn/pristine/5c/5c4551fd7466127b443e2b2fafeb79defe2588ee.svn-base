<?php include template('header');?>
<script type="text/javascript" src="./script/js/system.js"></script>
<div class="ctr_panel">
	<br>
	<?php if($this->show=='add'||$this->show=='edit'||$this->show=='copy') {?>
	<form id="input_panel" action="./?action=System&do=BlackList&func=add" method="POST" enctype="multipart/form-data">
	<table>
	    <tr>
	        <th colspan="2"><a href="javascript:;">广告投放屏蔽规则设定</a></th>
	    </tr>
		<tr>
			<td>屏蔽类型</td>
			<td>
				<select name="type">
					<option value="1"<?= $this->pagedata['editInfo']['type'] == '1'?'selected':''?>>VID</option>
					<option value="2"<?= $this->pagedata['editInfo']['type'] == '2'?'selected':''?>>关键词</option>
					<!-- 
					<option value="2"<?= $this->pagedata['editInfo']['type'] == '2'?'selected':''?>>M站</option>
					<option value="3"<?= $this->pagedata['editInfo']['type'] == '3'?'selected':''?>>APP</option>
				 	-->
				</select>
			</td>
		</tr>
		<tr>
			<td>屏蔽说明</td>
			<td><textarea name="description" rows="2" cols="120"><?= $this->pagedata['editInfo']['description']?></textarea></td>
		</tr>
		<tr>
			<td>黑词</td>
			<td><textarea name="blackwords" rows="4" cols="85"></textarea>  (黑词之间请用英文逗号分隔)</td>  
		</tr>
		<tr>
			<td>配置文件</td>
			<td>
				<input type="file" name="config_file" size="20">  (黑词之间请用英文逗号分隔)
			</td>
		</tr>
	    <tr>
			<td colspan="2" style="height:60px;text-align:center"><span class="button blue" id="sbt_btn" onclick="$('#input_panel').submit();">确认提交</span>
		</tr>
	</table>
	</form>
	<br />		
	<?php }?>
	<?php if($this->show=='list') {?>
	<table>
	<tr>
			<td colspan="6"><?php echo empty($this->pagedata['list'])?'-暂无数据-':$this->pagedata['sp']?></td>
		</tr>
		<tr>
			<th width="20px"><a>版本号</a></th>
			<th width="80px"><a>规则类型</a></th>
			<th><a>屏蔽说明</a></th>
			<th><a>配置文件</a></th>
			<th><a>当前状态</a></th>
			<th width="15px"><input class="button blue medium" type="button" onclick="location.href='./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&show=add'" value="新增屏蔽规则"/></th>
		</tr>
		<?php foreach ($this->pagedata['list'] as $data) { 
			$d_info = $this->_db->rsArray("SELECT id FROM ad_document WHERE useful = 'blacklist' AND index_key = {$data['id']} LIMIT 0,1");
		?>
		<tr>
			<td>#<?= "{$data['ts']}_{$data['id']}"?></td>
			<td><?= $data['type']=='1'?'VID':'关键词'?></td>
			<td><?= $data['description']?></td>
			<td style="text-align:center;"><a href="./?action=System&do=GetDbFileContent&id=<?= $d_info['id']?>&headerType=txt" target="_blank">点击下载</a></td>
	        <td style="text-align:center;"><?= $data['status'] == 0?'<font color="green">[未屏蔽]</font>':'<font color="red">[屏蔽中]</font>'?></td>
	        <td>
	        	<p align="center">
	        		<a class="button green small" href="javascript:;" onclick="asyn_trigger('./?action=System&do=BlackListStart&type=<?= $data['type']?>&id=<?= $data['id']?>');">启动</a>
	        		<a class="button red small" href="javascript:;" onclick="asyn_trigger('./?action=System&do=BlackListStop&type=<?= $data['type']?>&id=<?= $data['id']?>');">停止</a>
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