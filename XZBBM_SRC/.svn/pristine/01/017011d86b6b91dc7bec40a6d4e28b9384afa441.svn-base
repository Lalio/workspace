<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;    
}
if($this->show == 'edit'){
//读取查量记录
$tar = $this->_db->rsArray("SELECT report_ids FROM ad_tgid WHERE id = {$_REQUEST['id']}");
$puv = $this->_db->dataArray("SELECT * FROM ad_puvamounts WHERE id in (".rtrim($tar['report_ids'],',').")");
}
?>

<?php include template('header');?>
<script type="text/javascript" src="./script/js/system.js"></script>
<script type="text/javascript" src="./script/js/WdatePicker.js"></script>
<div class="ctr_panel">
	<br>
	<?php if($this->show=='add'||$this->show=='edit') {?>
	<form id="input_form">
	<input type="hidden" name="id" value="<?= $_REQUEST['id']?>">
	<table>
	    <tr>
	        <th colspan="2"><a href="javascript:;">TGID配置模块</a></th>
	    </tr>
	    <tr>
			<td>项目描述</td>
			<td><input type="text" name="description" size="180"  value="<?= $this->pagedata['editInfo']['description']?>"></td>
		</tr>
		<tr>
			<td>TGID</td>
			<td>
				<input type="text" name="tgid" id="tgid" size="40"  value="<?= $this->pagedata['editInfo']['tgid']?>">
				<object id="clippy" class="clippy" width="62" height="24" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000">
					<param value="http://www.xzbbm.cn/etc/copy/copy.swf" name="movie">
					<param value="always" name="allowScriptAccess">
					<param value="high" name="quality">
					<param value="noscale" name="scale">
					<param value="txt=http://v.56.com/coop/rand_list.phtml?tgid=<?= $this->pagedata['editInfo']['tgid']?>" name="FlashVars">
					<param value="opaque" name="wmode">
					<embed width="62" height="24" align="middle" flashvars="txt=http://v.56.com/coop/rand_list.phtml?tgid=<?= $this->pagedata['editInfo']['tgid']?>" src="./etc/copy.swf" quality="high" wmode="transparent" allowscriptaccess="sameDomain" pluginspage="http://www.adobe.com/go/getflashplayer" type="application/x-shockwave-flash">
				</object>
			</td>
		</tr>
		<tr>
			<td>VID</td>
			<td>
				<textarea name="vid" rows="3" cols="110" id="vid"><?= $this->pagedata['editInfo']['vid']?></textarea>
				<iframe width="400" height="45" src="./?action=Tackle&do=ParsingTxt&dom_id=vid" scrolling="no" border="0" frameborder="0">浏览器不支持嵌入式框架，或被配置为不显示嵌入式框架。</iframe>
			</td>
		</tr>
		<tr>
			<td>AID1</td>
			<td>
				<textarea name="aid1" rows="3" cols="80" id="aid1"><?= $this->pagedata['editInfo']['aid1']?></textarea>
				<iframe width="380" height="35" src="./?action=Tackle&do=ParsingTxt&dom_id=aid1" scrolling="no" border="0" frameborder="0">浏览器不支持嵌入式框架，或被配置为不显示嵌入式框架。</iframe>
				<a class="button green small" href="./?action=Order&do=Transaction&func=search&show=list&aid=<?= trim($this->pagedata['editInfo']['aid1'])?>&vid=&title=&area=&starttime=&endtime=&link=&cid=&type=&username=&status=" target="_blank">广告详情</a>
			</td>
		</tr>
		<tr>
			<td>AID2</td>
			<td>
				<textarea name="aid2" rows="3" cols="80" id="aid2"><?= $this->pagedata['editInfo']['aid2']?></textarea>
				<iframe width="380" height="35" src="./?action=Tackle&do=ParsingTxt&dom_id=aid2" scrolling="no" border="0" frameborder="0">浏览器不支持嵌入式框架，或被配置为不显示嵌入式框架。</iframe>
				<a class="button green small" href="./?action=Order&do=Transaction&func=search&show=list&aid=<?= trim($this->pagedata['editInfo']['aid2'])?>&vid=&title=&area=&starttime=&endtime=&link=&cid=&type=&username=&status=" target="_blank">广告详情</a>
			</td>
		</tr>
		<?php if($this->show == 'edit'){?>
		<tr>
			<td>数据统计</td>
			<td>
				<input id="id" value="<?= $_REQUEST['id']?>" type="hidden">
				开始时间<input type="text" class="input_Calendar" id="report_starttime" onclick="WdatePicker({startDate:'<?= date('Y-m-d')?>',dateFmt:'yyyy-MM-dd',highLineWeekDay:true,readonly:true})" size="15" value=""><br>
				结束时间<input type="text" class="input_Calendar" id="report_endtime" onclick="WdatePicker({startDate:'<?= date('Y-m-d')?>',dateFmt:'yyyy-MM-dd',highLineWeekDay:true,readonly:true})" size="15" value=""><br>
				AIDS <input type="text" id="aids" size="70" value=""><br>
				说明   <input type="text" id="info" size="140" value="">
				<a href="javascript:;" target="_blank" is_together="0" class="button orange small gc_report">报表类型1</a>
				<a href="javascript:;" target="_blank" is_together="1" class="button orange small gc_report">报表类型2</a>
				<hr>
				<?php foreach ($puv as $p) {
					$url = "http://14.17.117.101/result/".date('Y-m-d',$p['ts'])."/{$p['id']}.xls";
				?>
					<?php if(true === $this->UrlValidity($url)){?>
					· <a href="<?= $url?>" target="_blank"><?= $p['info']?>.xls</a><br>
					<?php }?>
				<?php }?>
			</td>
		</tr>
		<?php }?>
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
			<th><a>编号</a></th>
			<th><a>项目描述</a></th>
			<th><a>TGID</a></th>
			<th width="80px"><a>状态</a></th>
			<th width="300px"><input class="button green medium" type="button" onclick="location.href='./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&show=add'" value="新增规则"/></th>
		</tr>
		<?php foreach ($this->pagedata['list'] as $data) {?>
		<tr>
			<td>#<?= $data['id']?></td>
			<td><?= $data['description']?></td>
			<td><?= $data['tgid']?></td>
			<td style="text-align:center;"><?= $data['status']==0?'<font color="red">[无效]</font>':'<font color="green">[有效]</font>'?></td>
	        <td>
	        	<p align="center">
	        		<a class="button green small" href="javascript:;" onclick="asyn_trigger('./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&func=start&id=<?= $data['id']?>');">启动</a>
	        		<a class="button red small" href="javascript:;" onclick="asyn_trigger('./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&func=stop&id=<?= $data['id']?>');">停止</a>
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