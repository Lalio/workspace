<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;
}

Class GetPersonCloud {

	static function Tags($userid)
	{
		$rs = array(
			array(
				"catg_id" => "1",
				"name" => "云端收藏"
			),
			array(
				"catg_id" => "2",
				"name" => "我的上传"
			),
			array(
				"catg_id" => "3",
				"name" => "下载历史"
			),
		);
		
		return $rs;
	}
	
	static function GetFiles($uid,$catg_id,$page)
	{
		Core::InitDb();
		$db = Core::$db['online'];
		
		$need = "pd_files.file_id, file_name, location , profile , from_unixtime(file_time, '%Y-%m-%d') file_time, file_extension, file_views, file_info, file_real_name, ip";
		$page = $page?intval($page):0;
		
		$start = $page*10;
		
		switch ($catg_id){
			case 1: //我的收藏
				
				$rs = $db->dataArray("SELECT $need,time FROM xz_favorite,pd_files WHERE uid = $uid AND pd_files.file_id = xz_favorite.file_id ORDER BY time DESC limit $start,15");
				break;
				
			case 2: //我的上传
				
				$rs = $db->dataArray("SELECT $need FROM pd_files WHERE userid = 1 ORDER BY file_id ASC limit $start,15");
				break;
				
			case 3: //我的下载
				
				$rs = $db->dataArray("SELECT $need,fid,ts,client_ip,client_adress FROM pd_files,xz_emaillist WHERE uid = $uid AND xz_emaillist.fid = pd_files.file_id GROUP BY fid ORDER BY id DESC limit $start,15");
				break;
		}
		
		foreach($rs as $k => $v){
			switch ($catg_id){
				case 1: 
					$rs[$k]['file_info'] = "收藏于 ".$rs[$k]['time'];
					break;
				case 2: 
					$rs[$k]['file_info'] = "上传于 {$rs[$k]['location']} - 收益 ￥{$rs[$k]['profile']}";
					break;
				case 3:
					$rs[$k]['file_info'] = "$v[ts] @ ".$rs[$k]['client_adress'];
					break;
			}
			
			$rs[$k]["download_addr"] = "http://www.xzbbm.cn/?do=FileDown&idf=$v[file_real_name]&key=$v[file_key]&token=kNUxOrw0oeVugRtb";
			$rs[$k]["qrcode_str"] = "http://www.xzbbm.cn/?do=ViewFile&file_id=$v[file_id]&download&from=app";
		}
		
		return $rs;
	}
	
}
