<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;    
}
?>
<script type="text/javascript" src="etc/ofc/swfobject.js"></script>
<script type="text/javascript">
<? switch($_GET['ShowType']){
 	case 'OfcPieJson':
 		echo <<<JSCODE
 		swfobject.embedSWF(
	"etc/ofc.swf", "content_bar",
	"98%", "550", "9.0.0", "expressInstall.swf",
	{"data-file":"./?action=Query%26do=OfcPieJson%26ts=$_GET[ts]%26t=$_GET[t]","loading":"数据加载中..."} );
JSCODE;
	break;
	case 'OfcBarJson':
		echo <<<JSCODE
		swfobject.embedSWF(
	"etc/ofc.swf", "content_pie",
	"98%", "400", "9.0.0", "expressInstall.swf",
	{"data-file":"./?action=Query%26do=OfcBarJson%26ts=$_GET[ts]%26t=$_GET[t]","loading":"数据加载中..."} );
JSCODE;
	break;
	case 'VcHistory':
		echo <<<JSCODE
		swfobject.embedSWF(
	"etc/ofc.swf", "vc_history",
	"98%", "400", "9.0.0", "expressInstall.swf",
	{"data-file":"./?action=Query%26do=VcHistory%26aid=$_GET[aid]%26type=$_GET[type]%26ts=$_GET[ts]","loading":"数据加载中..."} );
JSCODE;
	break;
 }?>
</script>
 
 
<? switch($_GET['ShowType']){
 	case 'OfcPieJson':
 		echo <<<HTML
 		<div id="content_pie"></div>
HTML;
	break;
	case 'OfcBarJson':
		echo <<<HTML
		<div id="content_bar"></div>
HTML;
	break;
	case 'VcHistory':
		echo <<<HTML
		<div id="vc_history"></div>
HTML;
	break;
 }?>