<?php
/**
 * @todo common setting
 * @author bo.wang3
 * @version 2013-1-28 14:29
 */
if(isset($_GET['dg']) && $_GET['dg']=='ml'){
    ini_set('display_errors',true); //是否打开错误报告
    error_reporting(E_ALL ^ E_NOTICE); //错误报告的等级
}else{
    ini_set('display_errors',false);
}

define("IN_SYS", true); //确保唯一入口
define("ROOT_DIR",str_replace("\\","/",dirname(__FILE__))."/"); //程序目录
include ROOT_DIR.'include/func.Global.php'; 
include ROOT_DIR.'config/inc.System.php';

define("TIMESTAMP", $_SERVER['REQUEST_TIME']); //时间戳

//init define 
foreach(Core::$configs['define'] as $k=>$v) {
    define(STRTOUPPER($k), $v);
}
Core::$configs['define'] = null;//free

//时间校正
//if(function_exists('date_default_timezone_set')) {
    date_default_timezone_set('Etc/GMT-8');
//}

session_start();//该函数将检查是否有一个会话ID
