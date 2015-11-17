<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;
}

Class GetSubTypeFiles {

	static function Main($ucode,$ccode,$cate_name,$key_word,$num,$page){

		Core::InitDb();
		$db = Core::$db['online'];
		
		$param = "file_id, file_name, from_unixtime(file_time, '%Y-%m-%d') file_time, file_extension, file_views, file_downs, file_info, file_real_name, file_key, file_index";
		$condition = "ucode = $ucode and (file_name LIKE '%$key_word%' OR file_tag LIKE '%$key_word%' OR file_description LIKE '%$key_word%')";
		$start = ($page - 1)*$num;
		
		$rs = $db->dataArray("select $param from pd_files where $condition order by file_views desc limit $start,$num");
		
		if(empty($rs)){
			$condition = "file_name LIKE '%$key_word%' OR file_tag LIKE '%$key_word%' OR file_description LIKE '%$key_word%'";
			$rs = $db->dataArray("select $param from pd_files where $condition order by file_views desc limit $start,$num");
		}
		
		foreach($rs as $k => $v){
			
			$rs[$k]['file_info'] = "分享：$v[file_downs]  浏览：$v[file_views]";
			$rs[$k]["download_addr"] = "http://www.xzbbm.cn/?do=FileDown&idf=$v[file_index]&key=$v[file_key]&token=kNUxOrw0oeVugRtb";
			$rs[$k]["qrcode_str"] = "http://www.xzbbm.cn/?do=ViewFile&file_id=$v[file_id]&download&from=app";
			
			if($v[file_downs] > 900){
				$rs[$k]['file_info'] .= "   [精品]";
			}elseif($v[file_downs] > 600){
				$rs[$k]['file_info'] .= "   [推荐]";
			}elseif($v[file_downs] > 300){
				$rs[$k]['file_info'] .= "   [优质]";
			}
		}
		
		return $rs;
		
	}
}