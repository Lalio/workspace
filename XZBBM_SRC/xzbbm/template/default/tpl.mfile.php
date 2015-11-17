<?php
if (! defined ( 'IN_SYS' )) {
    header ( "HTTP/1.1 404 Not Found" );
    die ();
}
?>
<!DOCTYPE html>
<html> 
<head>
<title><?= $this->pageData['file_name']?><?= $this->pageData['ucode'] >0 && !strstr($this->pageData['file_name'],$this->GetUniversityInfo($this->pageData['ucode'],'','name'))?'':''?></title>
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
a {color: #000;text-decoration : underline;} 
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
	display:inline-block;margin:0 3px;color:#fff;font-size:12px;line-height:28px;background:#05A766;border-radius:4px;text-align:center;
}
</style>
<script type="text/javascript" src="/js/jquery.js?v=6"></script>
<script type="text/javascript">var domain='<?= DOMAIN?>';</script>
</head>
<body>
<div id="page" style="background:#f7f7f7;">
	<div id='toubiao' style='background:#2181cb; height:2px; z-index: 10;'>	</div>
	<div style='background:#2181cb; background: none repeat scroll 0 0 #2181CB;color: #FFFFFF;font-size: 85%;height: 2.2em;min-width: 25%;max-width: 75%;overflow:hidden;float:left;margin-left: 2%; text-align: center;padding-top:3px;padding-left:0.3em;padding-right:0.3em;<?= isset($_REQUEST['imei'])?'margin-bottom:1.2em;':''?>'>
		<span style='padding-top:5px;'>学长帮帮忙 - 专注校内知识分享</span>
	</div>
	<div style='clear:both;'></div>
	<h2 id='title' style='margin:0.5 2% 0 2%; line-height: 1.5em; font-size:20px; line-height:1.2em; padding-bottom:0.5em; border-bottom:1px solid #e5e5e5; margin-bottom:2px;'>
		<?= $this->pageData['file_name']?>
	</h2>
	<div id='content' style='margin:0 2% 0 2%; line-height: 1.5em; font-size:18px;'>
	<p style="text-align:left;color:#272727;font-size:12px;">来源：<?= $this->ShowUicon($this->pageData['ucode']);?> <?= $this->GetUniversityInfo($this->pageData['ucode'],'','name')?>&nbsp;&nbsp;<img src="http://<?= DOMAIN?>/images/eye.jpg" />&nbsp;<span onclick="alert('平均每100次浏览本资料即有<?= intval($this->chuanbo*100)?>+位同学选择发送资料电子版到自己的邮箱');">传播率：<?= $this->chuanbo*100?>%</span><strong><a href="javascript:;" id="cloudprint" file_id="<?= $fdata['file_id']?>">[云印]</a></strong><br><?php if(!empty($this->pageData['file_tag'])){?><span>TAG：<?= rtrim($this->pageData['file_tag'],',')?></span><?php }?></p>
    	<?php if(!empty($this->pageData['attention'])){?>
			<?= $this->pageData['attention']?>
		<?php }else{?>
			<?php for($i = 0;$i < $fdata['has_png'];$i++){?>
				<img src="/images/file_loading.gif" data-original="/GetFile/<?= $fdata['file_id']?>/png/<?= TIMESTAMP?>/<?= sha1(TIMESTAMP.'sNsxCrth13LGsu60')?>/<?= $i?>" width="100%" style="display: block;">
			<?php }?>
		<?php }?>
		<hr style="height:1px;border:none;border-top:1px dashed rgb(207, 207, 219);">
	</div>
    <p style="text-align:center;color:rgb(158, 158, 158);font-size:10px;">已有26,388优质校内资料经XZBBM.CN转码便于在移动设备上阅读</p>
	<br>
	<div id="givemefive" style="position: fixed;bottom:2px;text-align: right;width:100%;">
			<input style="color: slategrey ;padding: 5px 5px 6px;width: 50%; border-width: 0px;font-size: 14px;background-color: gainsboro; margin: auto auto auto 10px" type="text" id="sendmailaddr" file_index="<?= $this->pageData['file_index']?>" value="<?= $_COOKIE[md5('xzbbm.cn_send_adress')]?$_COOKIE[md5('xzbbm.cn_send_adress')]:'电子邮箱地址'?>" onclick="this.value=''"/>
			<span class="btn" id="sendmailsbt" from="weixin">免费取阅完整资料</span>
	</div>
	
	<!-- 
	<div class='' style='margin:0.8em 2% 0.4em 2%;  text-align: center; padding-top:0.6em; height:2.0em; background-color:#f8661e; '>
		<a href='http://xzbbm.cn/app' onclick='javascript:;' style='display: block; font-size: 85%; color: #FFFFFF;text-decoration:none;font-weight:bold;' >下载客户端零流量获取完整资料</a>
	</div>
	-->
</div>
<script type="text/javascript" src="/js/jquery.lazyload.js?v=1"></script>
<script type="text/javascript" src="/js/wap.min.js?v=6"></script>
<script>
$(function(){
  $('img').lazyload({
    effect:'fadeIn'
  });
});
</script>
    <div class="tj" style="display:none">
    	<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1254673635'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s95.cnzz.com/z_stat.php%3Fid%3D1254673635' type='text/javascript'%3E%3C/script%3E"));</script>
	</div>
</body>
</html>