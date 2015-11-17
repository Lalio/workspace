<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;    
}
?>
<?php include template('header');?>
<div class="ctr_panel">
	<br />		<!-- 关闭空的html标签 -->
	<table>
		<tr>
			<td colspan="2">
			资料归属
			<select id="uid">
			<?php foreach($this->schlist as $k=>$v){?>
				<option value="<?= $k?>" <?= $_GET['uid']==$k?'selected':''?>>[<?= $k?>]<?= $v?></option>
			<?php }?>
			</select>
			关键词（多个关键词使用空格分隔）
			<input id="keywords" onclick="this.value=''" size="40" type="text" value="试卷 试题"></input>
			<button onclick="location.href='./?do=DaPao&qrcode=t&uid='+$('#uid').val()+'&keywords='+$('#keywords').val()">二维码列表</button>
			<button onclick="location.href='./?do=DaPao&uid='+$('#uid').val()+'&keywords='+$('#keywords').val()">纯文本列表</button>
			<button onclick="location.href='./'">资料管理</button>
			<?php echo empty($this->_pagedata['list'])?'':$this->_pagedata['sp']?></td>
		</tr>
		<tr>
			<th><a>库二维码</a></th>
			<th><a>资料名称</a></th>
		</tr>
		<tr>
			<td style="text-align:center;"><img style="width:120px;margin:5px;" src="https://xzbbm.cn/?action=QrCodes&do=GcQr&size=180&str=<?= urlencode('http://www.xzbbm.cn/?do=WeiList&uid='.$_REQUEST['uid'].'&keywords='.str_replace(' ', '_', $_REQUEST['keywords']))?>"></img></td>
			<td><strong>←← "别说学长没帮你" 微信推广页面，请使用手机扫描左侧二维码后转发  O(∩_∩)O </strong></td>
		</tr>
		<?php foreach ($this->rs as $data) {?>
		<tr>
			<td style="text-align:center;"><? if($_REQUEST['qrcode'] == 't'){?><img style="width:90px;margin:10px;" src=""><?php }?></td>
			<td><?= $data['file_name']?>   <a href="http://xzbbm.cn/<?= $data['file_key']?>" target="_blank">http://xzbbm.cn/<?= $data['file_key']?></a></td>
		</tr>
		<?php }?>
		<tr>
			<td colspan="2"><?php echo empty($this->_pagedata['list'])?'-暂无数据-':$this->_pagedata['sp']?></td>
		</tr>
	</table>
</div>
<?php
include template('footer');
?>