<?php
if(!defined('IN_SYS')) {    
        header("HTTP/1.1 404 Not Found");    
        die;
}

Class GetMyFavorite{      

	static function Main($uid){
		
		Core::InitDb();
		$db = Core::$db['online'];

		$sql = "select xz_favorite.fid, pd_files.file_name, pd_files.file_extension, from_unixtime(pd_files.file_time, '%Y-%m-%d') file_time, pd_files.file_views, pd_files.file_downs from xz_favorite, pd_files where uid = $uid and xz_favorite.fid = pd_files.file_id";
		$rs = $db->dataArray($sql);
		return $rs;
	}
}
