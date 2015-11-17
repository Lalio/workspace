<?php
if(!defined('IN_SYS')) {
    echo ("HTTP/1.1 404 Not Found");
    die;
}
//数据库配置参数

Core::$configs ['db'] = array (
	'main' => array (
			'host' => 'xzbbmrds.mysql.rds.aliyuncs.com',
			'username' => 'u_mads',
			'password' => 'S3454kdkdk344__kd_md',
			'database' => 'ads',
			'_charset' => 'latin1'
	),
    'mads' => array (
        'host' => 'xzbbmrds.mysql.rds.aliyuncs.com', 
        'username' => 'u_mads', 
        'password' => 'S3454kdkdk344__kd_md', 
        'database' => 'ads', 
        '_charset' => 'latin1'
    ),
    'mads_test' => array ( //MADS测试环境
    		'host' => '10.61.81.139',
    		'username' => 'u_mads',
    		'password' => 'S3454kdkdk344__kd_md',
    		'database' => 'ads',
    		'_charset' => 'latin1'
    ),
    'mads_slave' => array ( //MADS从库，双机主从备
    		'host' => '10.11.80.78',
    		'username' => 'root',
    		'password' => '',
    		'database' => 'ads',
    		'_charset' => 'latin1'
    ),
    'mads_dbsource' => array ( //MADS冷备数据源，用于大数据分析 防止锁表
    		'host' => '10.11.81.49:39219',
    		'username' => 'root',
    		'password' => 'k_dfkSA5665R_k',
    		'database' => 'ads',
    		'_charset' => 'latin1',
    ),
    't3' => array (
    		'host' => '10.61.81.139',
    		'username' => 'act_cpm',
    		'password' => '1234',
    		'database' => 'ad_db',
    		'_charset' => 'latin1'
    )
);
