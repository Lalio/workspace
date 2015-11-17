<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;
}

/**
 * @todo 
 * @author bo.wang3
 * @version 2013-4-22 14:29
 */
Class Tools{
	
	public function __Construct(){
		
		Core::InitDb();  //初始化数据库
		Core::InitDataCache(); //初始化数据缓类
		$this->_db = Core::$db['online'];
		$this->_dc = Core::$dc;
	}
	
	public function PaoDan(){
		
		if(isset($_REQUEST['keywords']) && $_REQUEST['keywords'] != ''){
			 
		}else{
			echo <<<HTML
		</table>
HTML;
		}
	}
	
}