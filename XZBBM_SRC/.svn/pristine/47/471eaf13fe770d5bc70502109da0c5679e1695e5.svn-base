<?php
if(!defined('IN_SYS')) {    
        header("HTTP/1.1 404 Not Found");    
        die;
}

Class AddComment {      

	/*
	   type=0 顶一下
	   type=1 踩一下
	 */
	static function Main($uid, $fid, $type){
		
		Core::InitDb();
		$db = Core::$db['online'];

		if($type == 0){
			$sql = "update pd_files SET good_count = good_count + 1 WHERE file_id = $fid";
			if($db->conn($sql)){
				$ret['rcode'] = 0;
			}
			else{
				$ret['rcode'] = 1;
			}
		}
		else if($type == 1){
			$sql = "update pd_files SET bad_count = bad_count + 1 WHERE file_id = $fid";
			if($db->conn($sql)){
				$ret['rcode'] = 0;
			}
			else{
				$ret['rcode'] = 1;
			}
		}
		else{
			$ret['rcode'] = 1;
		}
		return $ret;
	}
}
