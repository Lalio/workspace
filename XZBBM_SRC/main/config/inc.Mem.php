<?php
if (!defined('IN_SYS')){
	header('HTTP/1.1 404 NOT FOUND');
}
Core::$configs ['mem']['server'] =  array(
        array('host' => '172.16.215.103', 'port' => 22222, 'weight'=> 50 ),
        array('host' => '172.16.215.132' , 'port' => 22222, 'weight'=> 50 ), 
        array('host' => '172.16.215.151' , 'port' => 22222, 'weight'=> 50 ), 
        array('host' => '172.16.215.170' , 'port' => 22222, 'weight'=> 50 ), 
        array('host' => '172.16.215.172', 'port' => 22222, 'weight'=> 50 ),  
        array('host' => '172.16.215.173', 'port' => 22222, 'weight'=> 50 ),  
        array('host' => '172.16.215.175', 'port' => 22222, 'weight'=> 50 ),    
 ); 