<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<title>56.com 广告管理系统  - Ver 3.0</title>
<meta name="keywords" content="视频,在线视频,免费视频,视频搜索,视频播放,上传视频,相册视频,美女视频,电影电视剧" />
<meta name="description" content="56.com拥有数量巨大的原创视频库、及完整影视库，从观看视频、上传视频、到分享视频都拥有极佳的用户体验；56.com目前是中国最大的视频分享平台，在此平台能与众多的网友进行视频分享、互动、娱乐……" />
<link href="./script/css/main.css" rel="stylesheet" type="text/css" />
<link href="./script/css/jquery.fancybox-1.3.4.css" rel="stylesheet" type="text/css" />
<script src="./script/js/jquery-1.4.4.js?jsversion=12"></script>
<script src="./script/js/main.js?jsversion=12"></script>
<script src="./script/js/jquery.fancybox-1.3.4.js?jsversion=12"></script>
<script src="./script/js/jquery.PrintArea.js?jsversion=12"></script>
<script type="text/javascript">
    var __Host = "<?= HOSTNAME?>";
</script>
</head>
<body id="top">
<? if($this->action != 'Auth'){ ?>
<!-- 选择菜单开始 -->
<div class="order_ctr_menu" id="menu">
<table align="center">
    <tr>
        <th><a href="javascript:;"><?= ADMIN?></a></th>
            <?= $this->menu?>
        <th><a href="./?action=Auth&do=LoginOut">登出</a></th>
    </tr>
</table>
</div>
<br/><br/>
<!-- 选择菜单结束 -->
<? }?>
<div class="main">