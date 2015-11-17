<?php
if(!defined('IN_SYS')) {    
        header("HTTP/1.1 404 Not Found");    
        die;
}

Class AddToFavorite {      

	static function Main($uid, $fid){
		
		Core::InitDb();
		$db = Core::$db['online'];

		$sql = "insert into xz_favorite (uid, fid) values ($uid, $fid)";
		if($db->conn($sql)){
			$ret['rcode'] = 0;
		}
		else{
			$ret['rcode'] = 1;
		}
		return $ret;
	}
}
