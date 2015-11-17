<?php
if(!defined('IN_SYS')) {
    echo ("HTTP/1.1 404 Not Found");
    die;
}
/**
 * all configuration by bo.wang3
 */
Core::$configs = array();

//Core::$configs['define']会被定义为常量
Core::$configs['define']=array();
$p = &Core::$configs['define'];

$p['action'] = 'action';//key of app	e.g: app.php?action=index / $_REQUEST['action'] = index
$p['default_action'] = 'Index';//default action
$p['do_method'] = 'do';//key of app	e.g: app.php?app=index&do=phpinfo / $_REQUEST['do'] = phpinfo
$p['default_do_method'] = 'Main';//default action

$p['app_dir'] = ROOT_DIR.'app/';//dir of apps
$p['tpl_dir'] = ROOT_DIR.'template';//dir of templates
$p['cache_dir'] = ROOT_DIR.'cache/';

$p['domain'] = 'www.xzbbm.cn';//域名,可作保存cookie域名
//$p['domain'] = $_SERVER['HTTP_HOST'];//域名,可作保存cookie域名
$p['charset'] = 'UTF-8';//页面编码
$p['protocol'] = $_SERVER["SERVER_PORT"] == 443?'https':'http';//当前使用的协议

load_cfg('Database'); //数据库
load_cfg('Dc'); //文件缓存
load_cfg('Oss'); //云存储集群
load_cfg('Mem'); //Ocs缓存集群