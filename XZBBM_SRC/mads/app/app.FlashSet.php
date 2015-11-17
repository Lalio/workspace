<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;
}

/**
 * @todo 广告播放器参数配置
 * @author bo.wang3
 * @version 2013-4-22 14:29
 */
Class FlashSet extends Mads{

	public function __construct(){
		parent::__construct();
	}

	public function Main(){
		include Template('login','Auth');
	}
	
	/**
	 * @todo 广告容器配置
	 * @author bo.wang3
	 * @version 2013-4-22 14:29
	 */
	public function Template(){
	
	    $this->BackendDbLogic($_POST,'ad_templates','template'); //功能切换、数据、数据表名、模版文件名
	}
	
	/**
	 * @todo 自制节目管理账号配置
	 * @author bo.wang3
	 * @version 2013-4-22 14:29
	 */
	public function Show2Account(){
	
	    $this->BackendDbLogic($_POST,'flash_show2account','show2account'); //功能切换、数据、数据表名、模版文件名
	}
	
}