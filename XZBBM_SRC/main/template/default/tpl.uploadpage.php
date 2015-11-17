<?php
if ( !defined( 'IN_SYS' ) ) {
	header( "HTTP/1.1 404 Not Found" );
	die;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="zh-CH" xmlns="http://www.w3.org/1999/xhtml">
<head>
<style type="text/css">
	.qh01{ width:914px; height:auto; overflow:hidden; background:url(http://<?= DOMAIN?>/images/qh_top.png) no-repeat; padding-top:34px; margin:88px auto;}
	.qh01_bottom{ width:100%; height:34px; overflow:hidden; background:url(http://<?= DOMAIN?>/images/qh_bottom.png) no-repeat;}
	.qh01_nr{ padding:0 30px; height:auto; overflow:hidden; background:url(http://<?= DOMAIN?>/images/qh_bg.png) repeat-y;}
	.qh_title{ width:100%; height:30px; overflow:hidden; border-bottom:1px #cfcfcf solid;}
	.qh_title h2{ float:left; font:600 14px/30px 宋体;}
	.qh_title span{ display:block; width:auto; height:30px; float:left; line-height:30px; text-indent:20px;}
	.qh_title span strong{ color:#61b21a;}
	.qh_title a{ display:block; width:20px; height:18px; padding:6px 0; float:right;}
	.qh01_left{ width:200px; height:auto; overflow:hidden;  margin:20px 0; float:left;}
	.qh01_left h3{ width:100%; height:30px; font:600 14px/30px 宋体; color:#666; padding-bottom:10px;}
	.qh01_left a{ display:block; width:40px; height:30px; float:left; overflow:hidden; margin-right:10px; text-indent:3px; line-height:30px;}
	
	.qh01_right{ width:652px; height:auto; overflow:hidden; float:left;border-left:1px #666666 dotted;margin:20px 0;}
	.qh01_right a{ display:block; width:150px; height:30px; line-height:30px; float:left; text-indent:5px; margin:0 5px;}
	.qh01_right h3{ width:100%; height:30px; font:600 14px/30px 宋体; color:#666; padding-bottom:10px; text-indent:10px;}
	.qh_overflow{ width:100%; height:300px; overflow-y:scroll;}
	.qh01_left a:hover,.qh01_right a:hover{ background:#61b21a; color:#fff;}
	.qh01_nr h4{ width:100%; height:30px; border-bottom:1px #666666 dotted; overflow:hidden; font:14px/30px 宋体; padding-top:10px;}
	.qh01_nr p{ line-height:24px;}
	.tongyi{ width:100%; height:30px; overflow:hidden; border-bottom:1px #666666 dotted; padding-top:15px;}
	.tongyi input{ float:left; margin:8px 0 0 0}
	.tongyi em{ display:block; height:30px; float:left; padding:0 10px; font-weight:600; line-height:30px;}
	.ps{ width:100%; height:45px; font:14px/45px 宋体; color:#848484;}
</style>
</head>
<body>
  <div class="qh01">
    <div class="qh01_nr">
      <div class="qh_title">
        <h2>分享资料</h2>
        <span>予人玫瑰，手有余香，与人分享是一种美德。</span> <a href = "javascript:void(0)" onclick = "$('#light1').style.display='none';$('#fade1').style.display='none'"><img src="http://<?= DOMAIN?>/images/guanbi.jpg" width="20" height="18" /></a> </div>
      <h4>归属高校：<strong>中国民用航空飞行学院</strong></h4>
      <p>
        1.您应保证您上传的资料不违反法律法规的规定，不包含暴力、色情、反动等一切违法或不良因素，同时不侵犯任何第三人的合法权利。<br />
        2.您应当为您的上传行为独立完全承担法律责任和对外经济赔偿责任。因您的个人行为所产生的一切争议和纠纷以及诉讼，与本站无关。<br />
        3.您的上传行为代表您同意您上传的作品在本站内的公开发布与传播并授权本站使用您上传的作品。任何非法转载行为与本站无关。<br />
        4.您声明您是作品的著作权人时，该声明是真实且具有法律效力的，您的上传行为代表您愿意授权学长帮帮忙使用您的资料，您同意学长帮帮忙使用和推荐您的作品，您同意学长帮帮忙对您的原创作品进行商业开发。<br />
        5.学长帮帮忙保留对申请了学长帮帮忙的用户投放商业性广告的权利。除特别签订协议或者学长帮帮忙许可外，您不得自行上传广告信息，您上传的资料中如夹杂广告或标题、简介中含有广告信息的，学长帮帮忙有权不经通知删除。<br />
        6.学长帮帮忙如果认为您上传的内容不适当，有权进行删除或修改，甚至对情节严重者进行封号处理。</p>
      <div class="tongyi">
        <input name="" type="checkbox" value="" />
        <em>我已经理解并同意了 知识共享3.0协议（CC BY-SA 3.0） 及以下分享协议</em> </div>
      <div class="ps">请您选择不大于15MB的OFFICE、PDF、RAR/ZIP、CHM、JPG格式学术资料类文档上传</div>
      <!-- <a href="#" class="fabu_btn" style="float:left; margin:0;">分享资料</a>  --></div>
    <div class="qh01_bottom"></div>
  </div>
</body>
</html>