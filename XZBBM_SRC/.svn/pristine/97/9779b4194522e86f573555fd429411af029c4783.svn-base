<?php
if (! defined ( 'IN_SYS' )) {
    header ( "HTTP/1.1 404 Not Found" );
    die ();
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no,width=device-width">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="Keywords" content="学长帮帮忙   <?= $this->pageData['file_name']?>">
    <meta name="description" content="学长帮帮忙新媒体页面">
	<title><?= $this->pageData['file_name']?><?= $this->pageData['ucode'] >0 && !strstr($this->pageData['file_name'],$this->GetUniversityInfo($this->pageData['ucode'],'','name'))?'':''?></title>
    <link href="/css/newmedia.min.css?v=3" rel="stylesheet">
    <link rel="shortcut icon" href="/favicon.ico" /> 
<style>
.school-ico{width:18px;height:18px;margin:1px 5px 0 0;background:url(http://www.xzbbm.cn/images/sicons/<?= $this->GetUniversityInfo($this->pageData['ucode'],'','sicon_id');?>.png) no-repeat;background-size:100%}
</style>
<script type="text/javascript">var domain='<?= DOMAIN?>';</script>
</head>
<body>
	<!-- 旧版微信兼容 -->
	<h1 class="thumbnails" style="display:none">
      <img src="/images/newmedia/moren.jpg"/>
    </h1>
	<div class="loading">
		<div class="loading-wrap">
			<!-- <div id="logo"></div> -->
			<div class="loading-img"></div>
			<!-- <div class="loading-txt">0%</div> -->
		</div>
	</div>
	<!-- 弹窗蒙层 -->
	<div id="mask"></div>
	<div class="dialog">
		<span class="J_close"></span>
		<div class="send hasEmail">
			<input type="text" placeholder="输入邮箱免费取阅完整资料"  id="sendmailaddr" file_index="<?= $fdata['file_index']?>" value="<?= $_COOKIE[md5('xzbbm.cn_send_adress')]?$_COOKIE[md5('xzbbm.cn_send_adress')]:'电子邮箱地址'?>" onclick="this.value=''"/>
			<span class="btn-send" id="sendmailsbt" from="weixin"></span>
		</div>
		<!-- <div class="send non-email">
			<p>*该资料不支持发送邮箱，请下载APP获取</p>
		</div> -->
		<a href="###" class="app-download" id="J_download">APP下载</a>
	</div>

	<div id="swiper-container" class="swiper-container">
		<div class="music musicon"></div>
		<div id="logo">
			<span class="txt txt1"></span>
			<span class="txt txt2"></span>
			<span class="txt txt3"></span>
		</div>
		<div class="next-page"></div>
		<span class="download-ico" id="download-ico"></span>
		<span class="page-num" id="page-num">
			<i class="current-num">1</i>/<i class="total-num"><?= ($fdata['has_png']+1)?></i>			
		</span>
		<ul class="swiper-wrapper">
			<li class="swiper-slide swiper-slide0">
				<div class="section-0 slider-item">
					<div class="passage-info">
						<h2 class="passage-title"><?= $fdata['file_name']?></h2>
						<?php if($fdata['description']){?>
						<div class="intro">
							<p class="intro-text">简介：</p>
							<p class="intro-bd">
								<?= $fdata['description']?>
							</p>
						</div>
						<?php }?>
						<div class="passage-source">
                            <p class="school-ico"></p>
                            <p class="form-school"><?= $this->GetUniversityInfo($fdata['ucode'],'','name')?></p>
                            <p class="upload-date">发布日期：</p>
                            <p class="form-date"><?= date('Y-m-d',$fdata['file_time'])?></p>
                            <p class="passage-infos">浏览 <span><?= intformat($fdata['file_views'])?></span> / 下载 <span><?= intformat($this->pageData['file_downs'])?></span> / 评论 <span><?= intformat($this->pageData['comment_count'])?></span></p>
                            <p class="reputation">
                                <span>好评率 : </span>
                                <span class="star">
                                    <i class="all-star"></i>
                                    <i class="all-star"></i>
                                    <i class="all-star"></i>
                                    <i class="all-star"></i>
                                    <i class="half-star"></i>
                                </span>
                            </p>
                        </div>
                        <div class="uploader">
                            <!-- <span class="uploader-img"><img src="<?= $udata['user_icon']?>" /></span> -->
                            <!-- <span class="uploader-name"><?= $udata['user_name']?></span> -->
                            <!-- <span class="uploader-voice"></span> -->
                        </div>
					</div>
				</div>
			</li>
			<?php for($i = 0;$i < $fdata['has_png'];$i++){ if($i > 10) break;?>
				<li class="swiper-slide swiper-slide1">
					<div class="section-1 slider-item">
						<div class="img-wrap img-wrap-<?= $this->pageData['file_extension'] == 'ppt'?'ppt':'pdf'?>">
							<img src="https://xzbbm.cn/GetFile/<?= $fdata['file_id']?>/png/<?= TIMESTAMP?>/<?= sha1(TIMESTAMP.'sNsxCrth13LGsu60')?>/<?= $i?>" class="<?= $fdata['file_extension'] == 'ppt'?'ppt':'pdf'?>">
						</div>
					</div>
				</li>
			<?php }?>
		</ul>
	</div>
	<div class="tj" style="display:none">
		<script src="http://s95.cnzz.com/z_stat.php?id=1254673635&web_id=1254673635" language="JavaScript"></script>
   		<!--  <script type="text/javascript" src="http://tajs.qq.com/stats?sId=28758788" charset="UTF-8"></script> -->
	</div>
	<audio id="audio" src="/music/0<?= mt_rand(1,5)?>0.mp3" loop preload="auto" autoplay="true"></audio>

	<script type="text/javascript" src="/js/lib/zepto.min.js"></script>
	<script type="text/javascript" src="/js/lib/idangerous.swiper.min.js"></script>
	<script type="text/javascript" src="/js/newmedia.min.js"></script>
	<script type="text/javascript" src="/js/wap.min.js?v=6"></script>
</body>
</html>