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
$p['default_action'] = 'SuperCarte';//default action
$p['do_method'] = 'do';//key of app	e.g: app.php?app=index&do=phpinfo / $_REQUEST['do'] = phpinfo
$p['default_do_method'] = 'Main';//default action

$p['app_dir'] = ROOT_DIR.'app/';//dir of apps
$p['tpl_dir'] = ROOT_DIR.'template';//dir of templates
$p['cache_dir'] = ROOT_DIR.'cache/';

$p['domain'] = $_SERVER['HTTP_HOST'];//域名,可作保存cookie域名
$p['charset'] = 'UTF-8';//页面编码

load_cfg('Database'); //数据库