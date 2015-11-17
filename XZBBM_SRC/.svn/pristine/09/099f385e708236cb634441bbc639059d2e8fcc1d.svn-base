<?php
if (! defined ( 'IN_SYS' )) {
    header ( "HTTP/1.1 404 Not Found" );
    die ();
}
if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false){//from wx download    berton
    $downloadbtn = <<<HTML
    href="#header" onclick="changepic();"
HTML;
}else{
    $downloadbtn = <<<HTML
    href="https://app.xzbbm.cn"
HTML;
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0">
	<title>学长帮帮忙</title>
	<meta name="format-detection" content="telephone=no">
	<meta name="keywords" content="学长帮帮忙,xzbbm,学长帮帮忙网站,学长帮帮忙官网,学长帮帮忙软件,学长帮帮忙APP,学长,学妹,师兄,师妹,考研复试,课件讲稿,复习资料,习题答案,课程设计,考试真题,毕业论文" />
    <meta name="description" content="我们专注于学习笔记、课件讲稿、复习资料、习题答案、课程设计、考试真题、毕业论文等大学校内资料的自由分享。" />
	<link href="favicon.ico" rel="shortcut icon" type="image/x-icon">
	<link rel="stylesheet" href="./css/m.min.css?v=1.2">
  	<link rel="shortcut icon" href="/favicon.ico" /> 
  	<!-- loading -->
  	<style id='s'>html,body{height:100%;overflow:hidden}</style>
  	<script>
  	  	document.write('<div id="t" style="position:absolute;top:0;z-index:999;background:#fff;height:100%;width:100%"><div style="background:rgba(0,0,0,0.8);border-radius:5px;position:absolute;top:50%;left:50%;margin:-15px 0 0 -40px;width:80px;line-height:30px;font-size:14px;height:30px;text-align:center;color:#f1f1f1">正在加载</div></div>');
  	</script>
</head>
<body>

	<!-- start the header -->
	<div id="header">
		<img id="header-img" src="" alt="banner" width="100%">
	</div>
	<!-- end the header -->


	<!-- start the middle content -->
	<div id="content">
		<a id="top-btn" <?= $downloadbtn?> class="download-btn" style="margin: 106px 77px;">下载安卓版</a>
		<div id="more">
			<p>下面更多内容</p>
			<img src="./img/down.gif" alt="import" width="50%">
		</div>
	   <div class="part part1 hide-animation">
			<div class="sprite pic1"></div>
			<div class="title-div clearfix">
				<span class="sprite icon icon1"></span>
				<span class="title">海量校内资料</span>
			</div>
			<p class="desc"><span class="article-red">3,156所高校百万校内资料与你分享</span></p>
			<p class="desc">笔记、课件、论文、报告、试题...</p>
		</div>
		<div class="part part2 hide-animation">
			<div class="sprite pic2"></div>
			<div class="title-div clearfix">
				<span class="sprite icon icon2"></span>
				<span class="title">分类精确到学院</span>
			</div>
			<p class="desc">精准到学院级别的各科复习资料</p>
			<p class="desc">各专业历年期中、期末考试真题</p>
			<p class="desc"><span class="article-blue">一键搜索看看学长都留了什么</span></p>
		</div>
		<div class="part part3 hide-animation">
			<div class="sprite pic3"></div>
			<div class="title-div clearfix">
				<span class="sprite icon icon3"></span>
				<span class="title">资料离线云打印</span>
			</div>
			<p class="desc"><span>手机微信、支付宝一键下单</span></p>
			<p class="desc"><span>打印流程实时消息通知</span></p>
			<p class="desc"><span class="article-green">免找零、免排队、免U盘</span></p>
			<p class="desc"><span class="article-purple">宿舍有打印机就能华丽变身打印店</span></p>
		</div>	
		<div class="part part4 hide-animation">
			<div class="sprite pic4"></div>
			<div class="title-div clearfix">
				<span class="sprite icon icon4"></span>
				<span class="title">校园知识变现金</span>
			</div>
			<p class="desc">一键发布课堂笔记，每天都有现金入账</p>
			<p class="desc"><span class="article-yellow">学长们平均每天在这里收入超过15块</span></p>
		</div>		
		<div class="line">
			<div class="left-line"></div>
			<div class="right-line"></div>
			<div class="sprite icon5"></div>
		</div>						
	</div>
	<!-- end the middle content -->

	<!-- start the footer -->
	<div id="footer" class="hide-animation">
		<p>下载“学长帮帮忙”，更多干货等你发现......</p>
		<p><span class="func">课堂笔记、实验报告、课程设计、课后答案、考试真题...</span></p>
		<a id="bottom-btn" class="download-btn" <?= $downloadbtn?>>下载安卓版</a>
		<p class="copyright">©2013-2015 xzbbm.cn, All Rights Reserved</p>
	</div>
	<!-- end the footer -->

	<!-- javascript -->
	<script type="text/javascript">
    	var wx_small_banner_url = "/img/m_banner_small_wx.jpg";
        var wx_banner_url = "/img/m_banner_wx.png";
    	var small_banner_url = "/img/m_banner_small.jpg";
        var banner_url = "/img/m_banner.png";
    
    	var banner = document.getElementById('header-img');
    	banner.setAttribute("src",banner_url);
    	
    	function changepic(){
    	    if(banner.src.replace("https://xzbbm.cn","") == banner_url){
    	    	banner.setAttribute("src",wx_banner_url);
        	}else if(banner == small_banner_url){
        		banner.setAttribute("src",wx_small_banner_url);
            }
    	}
	</script>
	        
	<script type="text/javascript" src="./js/mindex.js?v=1.1"></script>
	<div class="tj" style="display:none">
    	<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1254673635'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s95.cnzz.com/z_stat.php%3Fid%3D1254673635' type='text/javascript'%3E%3C/script%3E"));</script>
	</div>
</body>
</html>