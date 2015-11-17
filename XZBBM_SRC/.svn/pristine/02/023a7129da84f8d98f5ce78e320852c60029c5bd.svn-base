<?php
/**
 * 程序入口
 */

try{
	
	include './common.inc.php';
	load_app();
	$app = new $_REQUEST [ACTION] ();
	if($_REQUEST[DO_METHOD] && method_exists($app, $_REQUEST[DO_METHOD])) {
		$rs = $app->$_REQUEST[DO_METHOD]();
	}elseif($_REQUEST[DO_METHOD]){
		throw new JException('method not exists');
	}
	
}catch(Exception $e) {
	$e->ShowErrorMessage(1);
}
