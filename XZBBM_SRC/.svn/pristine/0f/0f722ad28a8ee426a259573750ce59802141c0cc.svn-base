<?php
/*
 * 主要功能:接口，完成数据报表生成htm
 *@author:Jorkey Young
 *@2012-12-12
 * 
接口：http://backend.v.56.com/adminv5/list/api/cronAreaReport.php
*/
set_time_limit(0);
define("MASTER", true);
define("ROOT",dirname(__FILE__).'/../');

chdir( ROOT );

$_REQUEST['action']='Adreport';
$_REQUEST['do']='report2';
if(!file_exists(ROOT.'rpt_tmp/rpt_todo.txt')){echo 'no rpt_todo.txt' ;return false;}
//将任务一次性读取到数组
$data=file(ROOT.'rpt_tmp/rpt_todo.txt');
if(empty($data)){echo 'nothing todo for rpt_todo.txt';return false;}
//把计划任务表清空
file_put_contents(ROOT.'rpt_tmp/rpt_todo.txt','');

foreach($data as $f){
	$f=trim($f);
    $f=explode('_',$f);  
	$_REQUEST['bt']=$f[0];
	$_REQUEST['et']=$f[1];
	require 'index.php';

	$row=$_REQUEST['bt'].'_'.$_REQUEST['et']."\n";
    echo( "ROOT_DIR" . ROOT_DIR . "\n" );
	file_put_contents(ROOT_DIR.'rpt_tmp/rpt_done.txt',$row,FILE_APPEND);

}

