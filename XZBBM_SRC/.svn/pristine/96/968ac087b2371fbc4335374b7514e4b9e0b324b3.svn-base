<?php
/**
 * 程序入口
 */
try{
	define("ROOT_DIR",str_replace("\\","/",dirname(__FILE__))."/"); //程序目录

	include ROOT_DIR.'/common.inc.php';

	foreach ($_REQUEST as $key => $value)
	{
	    $_REQUEST[$key] = strip_tags($value);
	}
	
	load_app($argv[1],$argv[2]); //让框架同时兼容CLI和CGI两种模式
	
	$app = new $_REQUEST[ACTION] ();

	if($_REQUEST[DO_METHOD] && method_exists($app, $_REQUEST[DO_METHOD])) {
		$rs = $app->$_REQUEST[DO_METHOD]();
	}elseif($_REQUEST[DO_METHOD]){
		throw new JException('method not exists');
	}
	
}catch(Exception $e) {
	$e->ShowErrorMessage(1);
}
