<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;    
}
include template('header');
?>
<table>
	<tr>
			<td colspan="7"><?php echo empty($this->pagedata['list'])?'-暂无数据-':$this->pagedata['sp']?></td>
		</tr>
		<tr>
			<th><a>合同编号</a></th>
			<th><a>客户名称</a></th>
			<th><a>投放类型</a></th>
			<th><a>投放周期</a></th>
			<th><a>日均CPM</a></th>
			<th><a>单价</a></th>
			<th><input class="button green medium" type="button" onclick="location.href='./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&show=add'" value="新增合同"/></th>
		</tr>
		<?php foreach ($this->pagedata['list'] as $data) {?>
		<tr>
			<td><?= $data['contract_id']?></td>
			<td><?= $this->clients[$data['customer_id']]?></td>
			<td><?= $this->adstype[$data['ad_type']]?></td>
			<td>开始：<?= date('Y 年 m 月 d 日',$data['starttime'])?><br>结束：<?= date('Y 年 m 月 d 日',$data['endtime'])?></td>
			<td><?= $data['day_cpm']?></td>
			<td><?= $data['unit_price']?></td>
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
			<td colspan="7"><?php echo empty($this->pagedata['list'])?'-暂无数据-':$this->pagedata['sp']?></td>
		</tr>
	</table>
<?php
include template('footer');
?>
