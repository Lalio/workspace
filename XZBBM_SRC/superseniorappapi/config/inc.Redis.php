<?php

if (! defined ( 'IN_SYS' )) {
	header('HTTP/1.1 404 Not Found');
	header("Status: 404 Not Found");
	$_SERVER['REDIRECT_STATUS'] = 404;
	readfile("../404.html");
	exit();
}

/**
 * @name: 新空间Redis配置
 * @author:	Ben`` @ 2012
 *
 */
Core::$configs ['redis'] = array ();

/**
 * @name: memcached 配置
 *
 */
$redisConfig = array ();
$redisConfig ['params'] = array ('option' => array(Redis::OPT_PREFIX, 'space:'));
$i = 0;
$redisConfig ['servers'] [$i ++] = array ('host' => '10.11.80.53', 'port' => 63791);
$redisConfig ['servers'] [$i ++] = array ('host' => '10.11.80.53', 'port' => 63792);
$redisConfig ['servers'] [$i ++] = array ('host' => '10.11.80.53', 'port' => 63793);
$redisConfig ['servers'] [$i ++] = array ('host' => '10.11.80.54', 'port' => 63791);
$redisConfig ['servers'] [$i ++] = array ('host' => '10.11.80.54', 'port' => 63792);
$redisConfig ['servers'] [$i ++] = array ('host' => '10.11.80.54', 'port' => 63793);
$redisConfig ['servers'] [$i ++] = array ('host' => '10.11.80.55', 'port' => 63791);
$redisConfig ['servers'] [$i ++] = array ('host' => '10.11.80.55', 'port' => 63792);
$redisConfig ['servers'] [$i ++] = array ('host' => '10.11.80.55', 'port' => 63793);
$redisConfig ['servers'] [$i ++] = array ('host' => '10.11.80.86', 'port' => 63791);
$redisConfig ['servers'] [$i ++] = array ('host' => '10.11.80.86', 'port' => 63792);
$redisConfig ['servers'] [$i ++] = array ('host' => '10.11.80.86', 'port' => 63793);
$redisConfig ['servers'] [$i ++] = array ('host' => '10.11.80.87', 'port' => 63791);
$redisConfig ['servers'] [$i ++] = array ('host' => '10.11.80.87', 'port' => 63792);
$redisConfig ['servers'] [$i ++] = array ('host' => '10.11.80.87', 'port' => 63793);
$redisConfig ['servers'] [$i ++] = array ('host' => '10.11.80.108', 'port' => 63791);
$redisConfig ['servers'] [$i ++] = array ('host' => '10.11.80.108', 'port' => 63792);
$redisConfig ['servers'] [$i ++] = array ('host' => '10.11.80.108', 'port' => 63793);
$redisConfig ['servers'] [$i ++] = array ('host' => '10.11.80.109', 'port' => 63791);
$redisConfig ['servers'] [$i ++] = array ('host' => '10.11.80.109', 'port' => 63792);
$redisConfig ['servers'] [$i ++] = array ('host' => '10.11.80.109', 'port' => 63793);

Core::$configs ['redis']['visitor'] = $redisConfig;
Core::$configs ['redis']['list'] = $redisConfig;
Core::$configs ['redis']['redis'] = $redisConfig;

$redisConfig['params'] = array('option' => array(Redis::OPT_PREFIX, 'space:'),
                               'db' => 0);
Core::$configs ['redis']['user_relate'] = $redisConfig;

unset ( $i );
unset ( $redisConfig );

