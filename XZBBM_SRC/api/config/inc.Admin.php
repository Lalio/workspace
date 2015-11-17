<?php
if(!defined('IN_SYS')) {
    echo ("HTTP/1.1 404 Not Found");
    die;
}
// 后台认证信息
Core::$configs['admin_func'] = array(
    'Backend' => array(
        'VideoManage' => '3401',
        'BannerManage' => '3403',
        'PhaseManage' => '3405',
    )
);