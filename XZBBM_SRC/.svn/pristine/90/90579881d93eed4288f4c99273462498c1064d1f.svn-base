<?php
/**
 * @todo 后台快速开发框架20130205
 * @author bo.wang
 * @version 2013-1-28 14:29 
 */
try {
	
 	include './common.inc.php';
    load_app($argv[1],$argv[2]); //让框架同时兼容CLI和CGI两种模式
    
    $app = new $_REQUEST [ACTION] ();
    if ($_REQUEST [DO_METHOD] && method_exists ( $app, $_REQUEST [DO_METHOD] )) {
        $rs = $app->$_REQUEST [DO_METHOD] ();
    } elseif ($_REQUEST [DO_METHOD]) {
        throw new JException ( 'method not exists' );
    }
} catch ( Exception $e ) {
    $e->ShowErrorMessage ( 1 );
}