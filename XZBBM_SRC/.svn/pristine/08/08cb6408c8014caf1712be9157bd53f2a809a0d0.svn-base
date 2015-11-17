<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;
}
?>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<title>文件信息校正工具</title>
<script src="//s1.56img.com/script/lib/jquery/jquery-1.4.4.min.js"></script>
<style>
table,tr,td {
    border-collapse: collapse;
    border: 1px solid #525C3D;
    margin: 0 auto;
    text-align: center;
}
</style>
</head>

<body>

<table width="98%">
<tr>
<td><button onclick="location.href='http://www.xzbbm.cn/?action=Tools&do=MdfFileInfo&id=<?= $_GET['id'] - 1?>&pre'"> ← 上一份</button></td>
<td><span lang="zh-cn">文件ID：<b><?= $_GET['id']?></b></span></td>
		<td><a onclick="alert('hello')">屏蔽</a></td>
		<td><a class="delete_file" href="javascript:;">删除</a></td>
		<td><a href="http://www.xzbbm.cn/?do=FileDown&idf=<?= $this->file[file_real_name]?>&key=<?= $this->file[file_key]?>" target="_blank">下载</a></td>
<td><button onclick="location.href='http://www.xzbbm.cn/?action=Tools&do=MdfFileInfo&id=<?= $_GET['id'] + 1?>&next'">下一份 →</button></td>
	</tr>

	<tr>
		<td colspan="6" height="100">文件名：<input id="name" type="text" size="52" value="<?= $this->file['file_name']?>"></input>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;文件简介：<textarea id="description" rows="5" name="S1" cols="106"><?= $this->file['file_description']?></textarea></td>
	</tr>
 
	<tr>
		<td colspan="6" style="text-align: center" height="50">文件信息:<?php if(!empty($this->file)) {?> 文件类型[<?= $this->file['file_extension']?>] 存储路径[<?= $this->file['file_store_path']?>] 文件大小[<?= round(($this->file['file_size']/1048576),2)?> MB] 识别码[<?= $this->file['file_real_name']?>]<?php }else{?> 文件已擦除或不存在。<?php }?></td>
	</tr>

	<tr>
		<td colspan="6">
		<?php $rs = get_headers("http://cdn.xzbbm.cn/img/$this->file[file_store_path]/$this->file[file_real_name]-big-0.jpg");if($rs[0] != 'HTTP/1.0 400 Bad request'){?>
		<?php for($i=0;$i<$this->file['pic_count'];$i++){?>
		    <p><img src="http://cdn.xzbbm.cn/img/<?= $this->file[file_store_path]?>/<?= $this->file[file_real_name]?>-big-<?= $i?>.jpg"></img><p/>
		<?php }?>
		<?php }else{echo '截图不存在。';}?>
		</td>
	</tr>

</table>

<p align="center">xzbbm.cn 文件信息校正工具 By miracle（QQ:599017308）</p>
<script type="text/javascript">
$("#name").blur(function(){
	$.ajax({
        url:'./?action=Tools&mdname&do=MdfFileInfo&id=<?= $_GET['id']?>&value='+$(this).attr('value'),
        type:"get",
    });
});

$("#description").blur(function(){
	$.ajax({
        url:'./?action=Tools&mddes&do=MdfFileInfo&id=<?= $_GET['id']?>&value='+$(this).attr('value'),
        type:"get",
    });
});

$(".delete_file").click(function(){
	if(confirm("确认要擦除该文件的所有信息吗？")){
		$.ajax({
	        url:'./?action=Tools&do=CleanFileInfo&auth=ApcjuFFx&ids=<?= $_GET['id']?>',
	        type:"get",
	        success:function(data){
	            alert(data);
	        }
	    });
	}
});

</script>
</body>

</html>