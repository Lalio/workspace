<?php
/**
 * @todo common setting
 * @author bo.wang3
 * @version 2013-1-28 14:29
 */
if($_GET['dg']=='ml'){
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
define("HOSTNAME", $_SERVER[HTTP_HOST] == 't3.56.com'?$_SERVER[HTTP_HOST]:'mads.56.com'); //环境切换
define("SIGNATURE", gc_sig());
define("TMPFILE", '/home/bo.wang3/tmp');//具有写权限的临时文件

//init define 
foreach(Core::$configs['define'] as $k=>$v) {
    define(STRTOUPPER($k), $v);
}
Core::$configs['define'] = null;//free

//时间校正
if(function_exists('date_default_timezone_set')) {
    date_default_timezone_set('Etc/GMT-8');
}

session_start();//该函数将检查是否有一个会话ID

send_cache_headers(0);//强制客户端禁止缓存

switch($_REQUEST['headerType']){
	
	case 'png':
		break;
	case 'txt':
		header("Content-type:text/plain; charset=".CHARSET);
		header("Content-Disposition:attachment;filename=".($_REQUEST['file_name']?$_REQUEST['file_name']:("MADS_Auto_Built @ ".date('Y-m-d H:i:s')).".txt"));
		break;
	case 'csv':
		header("Content-type:text/csv");
		header("Content-Disposition:attachment;filename=".($_REQUEST['file_name']?$_REQUEST['file_name']:("MADS_Auto_Built @ ".date('Y-m-d H:i:s')).".csv"));
		break;
	case 'xls':
		header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=".($_REQUEST['file_name']?$_REQUEST['file_name']:("MADS_Auto_Built @ ".date('Y-m-d H:i:s')).".xls")); 
        break;
	default:
		header("Content-Type: text/html; charset=".CHARSET);
		
}