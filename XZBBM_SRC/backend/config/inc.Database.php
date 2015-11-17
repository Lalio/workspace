<?php
if(!defined('IN_SYS')) {
    echo ("HTTP/1.1 404 Not Found");
    die;
}
//数据库配置参数

Core::$configs ['db'] = array (
    'online' => array (
        'host' => 'xzbbmrds.mysql.rds.aliyuncs.com', 
        'username' => 'xzbbm_db', 
        'password' => 'FgbAsj9ZND4rEpDj', 
        'database' => 'xzbbm', 
        '_charset' => 'utf8'
    ),
    'tmp' => array (
    		'host' => 'localhost',
    		'username' => 'xzbbm_db',
    		'password' => 'xyL8RvmBce',
    		'database' => 'xzbbm_tmp',
    		'_charset' => 'utf8'
    ),
);
