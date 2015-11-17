<?php
if (! defined ( 'IN_SYS' )) {
    header ( "HTTP/1.1 404 Not Found" );
    die ();
}
?>
<!DOCTYPE html>
<html> 
<head>
<title>别说学长没帮你_<?= $uname?>_关键词:<?= implode('+',$keywords)?></title>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" /> 
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="yes" name="apple-mobile-web-app-capable" />
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
<style>
body {-webkit-touch-callout: none; -webkit-text-size-adjust: none; line-height:1.5em; margin:0px; } 
img {max-width:100%;} 
.img_box {width:100%; min-height:100px; text-align:center; margin-bottom:5px;}
.img_box img {vertical-align: middle;}
#content {}
p {padding:0px; margin:0.6em 0}
a {color: #000;text-decoration : none;} 
.button {
	padding: 0 10px;
	float: left;
	height: 30px;
	line-height: 30px;
	background-color: rgb(51, 51, 51);
	color: white;
	text-align: center;
	margin: 5px 10px 5px 200px;
	font-size: 12px;
	font-weight: bold;
	text-decoration: none;
	text-indent: 0;
} 
h1 {
	font-size: 15px;
}
p {
	font-size: 13px;
}
.btn {
	display:inline-block;width:60px;margin:0 3px;color:#fff;font-size:12px;line-height:28px;background:#05A766;border-radius:4px;text-align:center;
}
</style>
<script type="text/javascript" src="http://www.xzbbm.cn/js/jquery-1.8.0.min.js?v=6"></script>
</head>
<body>
<div id="page" style="background:#f7f7f7;">
	<div id='toubiao' style='background:#2181cb; height:2px; z-index: 10;'></div>
	<div style='position: related; top: 0px; background: none repeat scroll 0 0 #2181CB;color: #FFFFFF;font-size: 85%;height: 2.2em;min-width: 25%;max-width: 50%;overflow:hidden;float:left;margin-left: 2%; text-align: center;padding-top:3px;padding-left:0.3em;padding-right:0.3em;<?= isset($_REQUEST['imei'])?'margin-bottom:1.2em;':''?>'>
		<span style='padding-top:5px;'>学长帮帮忙 @ <?= $uname?></span>
	</div>
	<br>
	<div id='content' style='margin:0 0 0 0; line-height: 1.5em; font-size:18px;'>
		<ul>
		<li style="text-align:left;color:#272727;font-size:12px;margin:5px;">关键词:<?= implode('+',$keywords)?>&nbsp;&nbsp;&nbsp;&nbsp;<img src="http://cdn.xzbbm.cn/web/images/eye.jpg" />&nbsp;<span onclick="alert('平均每100次浏览本资料即有<?= intval($this->chuanbo*100)?>+位同学选择发送资料电子版到自己的邮箱');">传播率：96.52%</span>   &nbsp;&nbsp;&nbsp;&nbsp; <!-- <a class="show_big_qrcode" href="javascript:;" src="http://www.xzbbm.cn/?action=QrCodes&do=GcQr&size=510&fid=<?= $this->pageData['file_id']?>">[+]面对面</a>--></li>
		<?php foreach ($this->rs as $data) {?>
			<li><a href="http://xzbbm.cn/<?= $data['file_key']?>" target="_blank"><?= $data['file_name']?></a></li>
		<?php }?>
		<li>更多校内期末复习资料请直接谷哥"<a href="http://www.886404.com/search?q=%E5%AD%A6%E9%95%BF%E5%B8%AE%E5%B8%AE%E5%BF%99" target="_blank">学长帮帮忙</a>"</li>
		</ul>
	</div>
	
	<p style="text-align:center;color:rgb(158, 158, 158);font-size:10px;">您可以通过电子邮箱免费离线下载完整版</p>
	<p style="text-align:center;color:rgb(158, 158, 158);font-size:10px;">已有<?= $this->totalFile?>份优质校内资料经XZBBM.CN转码便于在移动设备上阅读</p>
	<!-- 
	<div class='' style='margin:0.8em 2% 0.4em 2%;  text-align: center; padding-top:0.6em; height:2.0em; background-color:#f8661e; '>
		<a href='http://xzbbm.cn/app' onclick='javascript:;' style='display: block; font-size: 85%; color: #FFFFFF;text-decoration:none;font-weight:bold;' >下载客户端零流量获取完整资料</a>
	</div>
	-->
</div>
<script type="text/javascript" src="http://www.xzbbm.cn/js/jquery.scroll_loading-min.js?v=6"></script>
<script type="text/javascript" src="http://www.xzbbm.cn/js/jquery.fancybox-1.3.4.js?v=6"></script>
<script type="text/javascript" src="http://www.xzbbm.cn/js/wap.js?v=8"></script>
<div class="tj" style="display:none">
    <script type="text/javascript" src="http://tajs.qq.com/stats?sId=28758788" charset="UTF-8"></script>
</div>
</body>
</html>