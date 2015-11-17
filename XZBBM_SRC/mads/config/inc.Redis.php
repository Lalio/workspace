<?php
if(!defined('IN_SYS')) {
    echo ("HTTP/1.1 404 Not Found");
    die;
}
//Redis数据库配置参数

Core::$configs ['redis'] = array (
	'master' => array (
		'server' => '10.11.81.48',
		'port' => 6371,
		'pwd' => '5665com',
	),
    'slave' => array (
        'server' => '10.11.81.48', 
        'port' => 6399, 
        'pwd' => '5665com', 
    )
);
