<?php
if(!defined('IN_SYS')) {    
        header("HTTP/1.1 404 Not Found");    
        die;
}

/* 用户顶、踩、收藏等资料互动操作 */
Class FileAction { 

	static function Favorite($uid, $fid, $type){
		//type 1收藏 0取消
		Core::InitDb();
		$db = Core::$db['online'];

		if($type == 1){
			$sql = "insert into xz_favorite (uid, file_id) values ($uid, $fid)";
		}elseif($type == 0){
			$sql = "delete from xz_favorite where file_id = $fid and uid = $uid";
		}
		
		if($db->conn($sql)){
			$ret['rcode'] = 0;
		}
		else{
			$ret['rcode'] = 1;
		}
		return $ret;
	}

	static function GetDingCaiFav($fid,$uid){

		Core::InitDb();
		$db = Core::$db['online'];
		
		$rs = $db->rsArray("SELECT good_count,bad_count FROM pd_files WHERE file_id = $fid LIMIT 1");
		$ret = array(
			'ding' => $rs['good_count'],
			'cai' => $rs['bad_count']
			);

		$ret['collect'] = 0; //先假定未被收藏 
		if(!empty($uid)){
			$rs = $db->rsArray("SELECT time FROM xz_favorite WHERE uid = $uid AND file_id = $fid LIMIT 1");
			if(!empty($rs)){
				$ret['collect'] = 1; //被收藏
			}
		}

		return $ret;
	}

	/*
	   type=0 顶一下
	   type=1 踩一下
	 */
	static function AddComment($uid, $fid, $type){

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

	static function GetMyFavorite($uid,$catg_id){

		Core::InitDb();
		$db = Core::$db['online'];

		$sql = "select xz_favorite.file_id, pd_files.file_name, pd_files.file_extension,pd_files.good_count, pd_files.file_info, from_unixtime(pd_files.file_time, '%Y-%m-%d') file_time, pd_files.file_views, pd_files.file_downs from xz_favorite, pd_files where uid = $uid and xz_favorite.file_id = pd_files.file_id";
		$rs = $db->dataArray($sql);
		
		foreach($rs as $k => $v){
			if(isset($v['file_info']) && empty($v['file_info'])){
				$rs[$k]['file_info'] = "资料类型：$v[file_extension]  好评总数：$v[good_count]";
			}
		}
		
		return $rs;
	}


}