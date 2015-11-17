<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;    
}
include template('header');
?>
<script type="text/javascript" src="script/ofc/swfobject.js"></script>
<script type="text/javascript">

	swfobject.embedSWF(
	"script/ofc.swf", "content_bar",
	"1200", "550", "9.0.0", "expressInstall.swf",
	{"data-file":"./index.php?action=Query%26do=OfcPieJson%26ts=<?= $_GET['ts']?>%26t=<?= $_GET['t']?>","loading":"数据加载中..."} );

	swfobject.embedSWF(
	"script/ofc.swf", "content_pie",
	"1300", "400", "9.0.0", "expressInstall.swf",
	{"data-file":"./index.php?action=Query%26do=OfcBarJson%26ts=<?= $_GET['ts']?>%26t=<?= $_GET['t']?>","loading":"数据加载中..."} );

</script>
</br>
<table>
<tr>
	<td id="content_pie"></td>
</tr>
<tr>
	<td id="content_bar"></td>
</tr>
</table>
<?php
include template('footer');
?>
