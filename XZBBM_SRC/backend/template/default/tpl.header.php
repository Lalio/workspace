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
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title>学长帮帮忙数据管理后台  - Ver 1.0</title>
<link href="./script/css/main.css" rel="stylesheet" type="text/css" />
<script src="./script/js/jquery-1.4.4.min.js"></script>
<script src="./script/js/main.js?v=131315"></script>
</head>
<body>
<? if($this->action != 'Auth'){ ?>
<!-- 选择菜单开始 -->
<div class="menu" id="menu">
<!-- 
<table align="center">
    <tr>
        <th><a href="javascript:;">服务器时间：<?= date('Y-m-d H:i:s',TIMESTAMP)?></a></th>
        <th><a href="javascript:;">资料总数：<?= $this->id_total?></a></th>
        <th><a href="javascript:;">文件总数：<?= $this->md5_total?></a></th>
        <th><a href="javascript:;">注册总数：<?= $this->user_total?></a></th>
        <th><a href="javascript:;">下载总数：<?= $this->down_total?></a></th>
    </tr>
</table>
--> 
</div>
<!-- 选择菜单结束 -->
<? }?>