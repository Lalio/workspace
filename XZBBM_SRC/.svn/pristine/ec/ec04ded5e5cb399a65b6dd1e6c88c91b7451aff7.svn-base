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

$p['action'] = 'action';//key of app  e.g: app.php?action=index / $_REQUEST['action'] = index
$p['default_action'] = 'Query';//default action
$p['do_method'] = 'do';//key of app	e.g: app.php?app=index&do=phpinfo / $_REQUEST['do'] = phpinfo
$p['default_do_method'] = 'Main';//default action

$p['app_dir'] = ROOT_DIR.'app/';//dir of apps
$p['tpl_dir'] = ROOT_DIR.'template';//dir of templates
$p['cache_dir'] = ROOT_DIR.'cache/';

$p['domain'] = $_SERVER['HTTP_HOST'];//域名,可作保存cookie域名
$p['hostname'] = $_SERVER[HTTP_HOST] == "t3.56.com"?$_SERVER[HTTP_HOST]:"mads.56.com"; //域名
$p['charset'] = 'UTF-8';//页面编码

load_cfg('Admin'); //后台权限
load_cfg('Database'); //关系数据库
load_cfg('Redis'); //K/V数据库
load_cfg('Area'); //区域配置
load_cfg('Type'); //投放类型配置
load_cfg('Channel'); //投放频道配置
load_cfg('Sales'); //销售人员配置
load_cfg('Workers'); //投放人员配置
