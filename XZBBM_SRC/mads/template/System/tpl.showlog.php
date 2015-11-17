<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;    
}
?>

<?php include template('header');?>
<div class="ctr_panel">
	<br />
	<?php if($this->show == 'list') {?>
	<table>
		<tr>
			<td colspan="5"><?php echo empty($this->pagedata['list'])?'-暂无数据-':$this->pagedata['sp']?></td>
		</tr>
		<tr>
			<th width="5%"><a>管理员</a></th>
			<th width="5%"><a>广告ID</a></th>
			<th width="20%"><a>操作类型</a></th>
			<th width="10%"><a>操作时间</a></th>
			<th width="60%"><a>操作细节</a></th>
		</tr>
		<?php foreach ($this->pagedata['list'] as $data) {?>
		<tr>
			<td><?= $data['username']?></td>
			<td><?= $data['aid']?></td>
			<td style="font-size: 3px"><?= $data['log']?></td>
			<td style="font-size: 3px"><?= $data['logtime']?></td>
	        <td style="font-size: 3px"><?= $data['detail']?></td>
		</tr>
		<?php }?>
		<tr>
			<td colspan="5"><?php echo empty($this->pagedata['list'])?'-暂无数据-':$this->pagedata['sp']?></td>
		</tr>
	</table>
	<br />		<!-- 关闭空的html标签 -->
	<?php }?>
</div>
<?php
include template('footer');
?>