<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;    
}
?>
<?php include template('header');?>
<div class="ctr_panel">
	<br />
	
	<?php if($_GET['show']=='add'||$_GET['show']=='edit') {
		//编辑格式预处理
		$edata = $this->_pagedata['editInfo'];
	?>
	<script type="text/javascript">
		var __Host = "<?= HOSTNAME?>";
	</script>
	<form id="input_form">
	<input type="hidden" name="file_id" value="<?= $_GET['file_id']?>">
	<table>
		<tr><td>编号</td><td><?= $_GET['file_id']?></td></tr>
		<tr><td>发布日期</td><td><?= date('Y-m-d',$edata['file_time'])?></td></tr>
		<tr><td>标题（精准）</td><td><input type="text" name="file_name" size="50" value="<?= $edata['file_name']?>"></td></tr>
	    <tr><td>别名（新意）</td><td><input type="text" name="file_cname" size="50" value="<?= $edata['file_cname']?>"></td></tr>
	    <tr><td>短介绍</td><td><input type="text" name="file_info" size="70" value="<?= $edata['file_info']?>" readonly></td></tr>
	    <tr><td>标签</td><td><input type="text" name="file_tag" size="20" value="<?= $edata['file_tag']?>"></td></tr>
	    <tr><td>完整介绍</td><td><input type="text" name="file_description" size="100" value="<?= !empty($edata['file_description'])?$edata['file_description']:"本资料由可以帮你更多的好学长（姐） 友情贡献，优异的成绩源于踏实的付出，多看书、多思考、多练习才是掌握知识的根本途径。"?>"></td></tr>
	    <tr><td>索引</td><td><?= $edata['file_index']?></td></tr>
	    <tr><td>MD5值</td><td><?= $edata['file_md5']?></td></tr>
	    <tr><td>短连接</td><td>http://xzbbm.cn/<?= $edata['file_key']?></td></tr>
	    <tr><td>存储路径</td><td><?= $edata['file_store_path']?>/<?= $edata['file_real_name']?>.<?= $edata['file_extension']?></td></tr>
	    <tr><td>用户编码</td><td><?= $edata['userid']?></td></tr>
	    <tr><td>学校编码</td><td><?= $edata['ucode']?></td></tr>
	    <tr><td>上传时间</td><td><?= date('Y-m-d H:i:s',$edata['file_time'])?></td></tr>
	    <tr><td>用户IP定位</td><td><?= $edata['ip']?></td></tr>
	    <tr>
			<td colspan="2" style="height:60px;text-align:center">
				<span class="button blue" id="sbt_btn" onclick="asyn_sbt_uncheck('input_form','./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&func=<?= $_GET['show']=='add' || $_GET['func']=='copy'?'add':'edit'?>')">确认提交</span>
			</td>
		</tr>
	</table>
	</form>
	<script type="text/javascript">
	function rtn(data){
		switch (data.rs){
			case 0:
				if(data.msg != true){
					alert('新增广告成功，AID为：'+data.msg);
					location.href="./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&show=list";
				}
				$("#sbt_btn").html('同步成功');
				$("#sbt_btn").attr('class','button green');
			break;
		}
	}
	</script>
	<br />		<!-- 关闭空的html标签 -->
	<?php }?>
	
	<?php if($this->show != 'edit') {?>
	<table>
		<tr>
			<td colspan="12">
			资料归属
			<select onchange="location.href='./?dg=ml&type=<?= $_GET['type']?>&page=<?= $_GET['page']?$_GET['page']:1?>&u='+$(this).val()+'&o=<?= $_GET['o']?$_GET['o']:'file_time'?>'">
			<?php foreach($this->schlist as $k=>$v){?>
				<option value="<?= $k?>" <?= $_GET['u']==$k?'selected':''?>>[<?= $k?>]<?= $v?></option>
			<?php }?>
			</select>
			排序方式
			<select onchange="location.href='./?dg=ml&type=<?= $_GET['type']?>&page=<?= $_GET['page']?$_GET['page']:1?>&o='+$(this).val()+'&u=<?= $_GET['u']?$_GET['u']:0?>'">
				<option value="file_time" <?= $_GET['o']=='file_time'?'selected':''?>>最新</option>
				<option value="file_best" <?= $_GET['o']=='file_best'?'selected':''?>>权重+最热</option>
				<option value="file_downs" <?= $_GET['o']=='file_downs'?'selected':''?>>最热</option>
			</select>
			<a target="_blank" href="http://www.xzbbm.cn/?i=c">首页（无缓存）</a>
			<?php if($_REQUEST['type'] == 'in_recycle'){?>
				<button onclick="location.href='./?type=all&page=<?= $_REQUEST['page']?>&u=<?= $_REQUEST['u']?>&o=<?= $_REQUEST['o']?>'">资料仓</button>
			<?php }else{?>
				<button onclick="location.href='./?type=in_recycle&page=<?= $_REQUEST['page']?>&u=<?= $_REQUEST['u']?>&o=<?= $_REQUEST['o']?>'">回收站</button>
			<?php }?>
			<button onclick="location.href='./?do=DaPao'">推广工具</button>
			资料名或资料ID搜索
			<input size="50" value="" onchange="location.href='./?keyword='+$(this).val()"/>
			<?php echo empty($this->_pagedata['list'])?'-暂无数据-':$this->_pagedata['sp']?></td>
		</tr>
		<tr>
			<th><input class="checkall" type="checkbox" id="aids"><a>文件ID</a></th>
			<th><a>上传时间</a></th>
			<!-- <th><a>库二维码</a></th> -->
			<th><a>资料名称（精准）</a></th>
			<th><a>学校信息</a></th>
			<th><a>学院信息</a></th>
			<th><a>短介绍</a></th>
			<th><a>PDF</a></th>
			<th><a>PNG</a></th>
			<th><a>TEXT</a></th>
			<th><a>IN_RECYCLE</a></th>
			<!-- 
			<th><a>SWF状态</a></th>
			-->
			<th><a>权重</a></th>
			<th><a>操作</a></th>
		</tr>
		<?php foreach ($this->_pagedata['list'] as $data) {?>
		<tr>
			<td><a href="http://xzbbm.cn/<?= $data['file_key']?>" target="_blank">#<?= $data['file_id']?></a></td>
			<td><?= date('Y-m-d H:i:s',$data['file_time'])?></td>
			<!-- <td><?= empty($data['file_qrcode'])?'<font color="grey">无</font>':'<font color="green">有</font>';?></td> -->
			<td><input size="60" id="name_<?= $data['file_id']?>" file_id="<?= $data['file_id']?>" op_type="file_name" value="<?= $data['file_name']?>" readonly/></td>
			<td><input size="30" file_id="<?= $data['file_id']?>" op_type="ucode" value="<?= $this->schnamelist[$data['ucode']]?>"/></td>
			<td><input size="30" file_id="<?= $data['file_id']?>" op_type="ccode" etx="<?= $data['ucode']?>" value="<?= $this->collist[$data['ccode']]?>"/></td>
			<td><input size="25" file_id="<?= $data['file_id']?>" op_type="file_info" value="<?= empty($data['file_info'])?"发布：".date('Y-m-d',$data[file_time])."  浏览：".$data[file_downs]."":$data['file_info']?>" readonly></td>
			<td><?= $data['has_pdf']==0?'<font color="red">'.$data['has_pdf'].'</font>':'<font color="green">'.$data['has_pdf'].'</font>'?></td>
			<td><?= $data['has_png']==0?'<font color="red">'.$data['has_png'].'</font>':'<font color="green">'.$data['has_png'].'</font>'?></td>
			<td><?= $data['has_text']==0?'<font color="red">'.$data['has_text'].'</font>':'<font color="green">'.$data['has_text'].'</font>'?></td>
			<td><?= $data['in_recycle']==0?'<font color="red">'.$data['in_recycle'].'</font>':'<font color="green">'.$data['in_recycle'].'</font>'?></td>
			<!-- 
			<td><?= $data['swf_able']==0?'<font color="grey">无</font>':'<font color="green">有</font>'?></td>
	         -->
	        <td>
	        	<input size="1" file_id="<?= $data['file_id']?>" op_type="weight" value="<?= $data['weight']?>"/>
	        	<!-- 
	        	<select class="weight" file_id="<?= $data['file_id']?>">
	        	<?php for($i=0;$i<30;$i++){?>
	        		<option value="<?= $i?>"<?= $i==$data['weight']?'selected':''?>><?= $i?></option>
	        	<?php }?>
	        	</select>
	        	-->
	        </td>
	        <td>
	        	<!--  <a class="button green small" onclick="location.href='./?action=Index&do=Files&show=edit&file_id=<?= $data['file_id']?>'">修改</a> -->
	        	<!--  <a class="button green small" onclick="javascript:;">修改</a> -->
	        	<!--  <a class="add_tag button white small" onclick="javascript:;'" file_id="<?= $data['file_id']?>">打TAG</a> -->
	        	<?php if($data['in_recycle'] == 0){?>
	        	<a class="recycle button black small" opc="1" onclick="javascript:;'" file_id="<?= $data['file_id']?>">屏蔽</a>
	        	<?php }else{?>
	        	<a class="recycle button green small" opc="0" onclick="javascript:;'" file_id="<?= $data['file_id']?>">恢复</a>
	        	<a class="recycle button red small" opc="2" onclick="javascript:;'" file_id="<?= $data['file_id']?>">X</a>
	        	<?php }?>
	        	
	        	<!--  <a class="button white small" href="http://www.xzbbm.cn/?do=FileDown&token=kNUxOrw0oeVugRtb&idf=<?= $data['file_index']?>&key=<?= $data['file_key']?>" target="_blank">下载</a> -->
	        </td>
		</tr>
		<?php }?>
		<tr>
			<td colspan="12"><?php echo empty($this->_pagedata['list'])?'-暂无数据-':$this->_pagedata['sp']?></td>
		</tr>
	</table>
	<?php }?>
</div>
<div class="result" style="display:none;background-color:#009933;position:fixed;width:500px;height:60px;right:100px;bottom:10px;"><font color="white" size="8">（!）数据更新成功</font></div>
<script type="text/javascript">
$(":text").change(function(){
	var file_id = $(this).attr("file_id");
	var op_type = $(this).attr("op_type");
	var ext = $(this).attr("etx");
	var value = encodeURI($(this).val());
	$.ajax({
		url:'./?do=MdfFileInfo',
		type:"post",
	    data: "file_id="+file_id+"&op_type="+op_type+"&value="+value+"&ext="+ext,
        dataType:"json",
        success:function(data){
        	if(data.rcode == 0){
        		$(".result").fadeIn('slow');
        		$(".result").fadeOut('slow');
        	}else{
        		alert(data.msg);
        		location.reload();
        	}
        }
    });
})
$(".weight").change(function(){
	var file_id = $(this).attr("file_id");
	var value = $(this).attr("value");
	$.ajax({
		url:'./?do=MdfFileInfo',
		type:"post",
	    data: "file_id="+file_id+"&op_type=weight&value="+value,
        dataType:"json",
        success:function(data){
        	if(data.rcode == 1){
        		location.reload();
        	}else{
        		location.reload();
        	}
        }
    });
})
$(".recycle").click(function(){
	var file_id = $(this).attr("file_id");
	var value = $(this).attr("opc");
	$.ajax({
		url:'./?do=MdfFileInfo',
		type:"post",
	    data: "file_id="+file_id+"&op_type=in_recycle&value="+value,
        dataType:"json",
        success:function(data){
        	if(data.rcode == 1){
            	alert('资料屏蔽成功！');
        		location.reload();
        	}else{
        		location.reload();
        	}
        }
    });
})
$(".add_tag").click(function(){
	var file_id = $(this).attr("file_id");
	$.ajax({
		url:'./?do=GetTag',
		type:"post",
	    data: "file_id="+file_id,
        dataType:"json",
        success:function(data){
            var name = $("#name_"+file_id);
            var cname = $("#cname_"+file_id);
            name.attr("value",data+name.attr("value"));
            cname.attr("value",data+cname.attr("value"));
        }
    });
})
</script>
<?php
include template('footer');
?>