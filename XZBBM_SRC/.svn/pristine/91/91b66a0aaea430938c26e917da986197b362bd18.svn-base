<?php
if (! defined ( 'IN_SYS' )) {
    header ( "HTTP/1.1 404 Not Found" );
    die ();
}
?>
<!DOCTYPE html>
<html> 
<head>
<title><?= $this->pageData['file_name']?><?= $this->pageData['ucode'] >0 && !strstr($this->pageData['file_name'],$this->GetUniversityInfo($this->pageData['ucode'],'','name'))?' 源自 '.$this->GetUniversityInfo($this->pageData['ucode'],'','name'):''?></title>
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
	display:inline-block;width:44px;margin:0 3px;color:#fff;font-size:12px;line-height:28px;background:#05A766;border-radius:4px;text-align:center;
}
</style>
</head>
<body>
<div id="page" style="background:#f7f7f7;">

	<div style='clear:both;'></div>
	<h2 id='title' style='margin:<?= $_GET['imei'] == ''?0:5?> 2% 0 2%; line-height: 1.5em; font-size:20px; line-height:1.2em; padding-bottom:0.5em; border-bottom:1px solid #e5e5e5; margin-bottom:2px;'>
		<?= $this->pageData['file_name']?>
	</h2>
	<div id='content' style='margin:0 2% 0 2%; line-height: 1.5em; font-size:18px;'>
	<p style="text-align:left;color:#272727;font-size:12px;">来源：<?= $this->ShowUicon($this->pageData['ucode']);?> <?= $this->GetUniversityInfo($this->pageData['ucode'],'','name')?>   &nbsp;&nbsp;&nbsp;&nbsp;<img src="http://<?= DOMAIN?>/images/eye.jpg" />&nbsp;<span onclick="alert('平均每100次浏览本资料即有<?= intval($this->chuanbo*100)?>+位同学选择发送资料电子版到自己的邮箱');">传播率：<?= $this->chuanbo*100?>%</span></p>
    	<?php if(!empty($this->pageData['attention'])){?>
			<?= $this->pageData['attention']?>
		<?php }else{?>
			<div class="nd_rotate" style="text-align: center">
			<?php if($this->pageData['has_png'] == 1){?>
				<img class="lazy" width="100%" src="http://<?= DOMAIN?>/GetFile/<?= $this->pageData['file_id']?>/png/<?= TIMESTAMP?>/<?= sha1(TIMESTAMP.'sNsxCrth13LGsu60')?>/1" />
			<?php }else{?>
				<img class="lazy" width="100%" src="http://<?= DOMAIN?>/GetFile/<?= $this->pageData['file_id']?>/png/<?= TIMESTAMP?>/<?= sha1(TIMESTAMP.'sNsxCrth13LGsu60')?>/<?= ($this->page-1)?>" />
			<?php }?>
			</div>
				<p style="text-align: left;">
				<?php if($this->page > 1){?>
					<span class="btn" style="margin:0px 16px 0px 16px;background-color:#2181cb;" onclick="location.href='<?= $this->pre_url?>'">上一页</span>
				<?php }?>
				<?php if($this->page < $this->pageData['has_png']){?>
					<span class="btn" style="margin:0px 16px 0px 16px;background-color:#2181cb;" onclick="location.href='<?= $this->next_url?>'">下一页</span>
				<?php }?>
				</p>
		<?php }?>
	</div>
	
	<br><br>
	<div class="pinlun">
       <div class="comment">
		 <div id="disqus_thread"></div>
		    <!-- Duoshuo Comment BEGIN -->
		    <div class="ds-thread"></div>
		        <script type="text/javascript">
		        var duoshuoQuery = {short_name:"xzbbm"};	
		        (function() {		
			        var ds = document.createElement('script');		
			        ds.type = 'text/javascript';ds.async = true;		
			        ds.src = 'http://static.duoshuo.com/embed.js';		
			        ds.charset = 'UTF-8';		
			        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(ds);	})();	
		        </script>
		     <!-- Duoshuo Comment END -->
	   </div>
	</div>
	
</div>
<script type="text/javascript" src="http://<?= DOMAIN?>/js/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="http://<?= DOMAIN?>/js/jquery.rotate.js"></script>
<script type="text/javascript" src="http://<?= DOMAIN?>/js/wap.js?v=2"></script>
<div class="tj" style="display:none">
	<script src="http://s95.cnzz.com/z_stat.php?id=1254673635&web_id=1254673635" language="JavaScript"></script>
   <!--  <script type="text/javascript" src="http://tajs.qq.com/stats?sId=28758788" charset="UTF-8"></script> -->
</div>
</body>
</html>