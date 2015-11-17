<?php
if(!defined('IN_SYS')) {    
	header("HTTP/1.1 404 Not Found");    
	die;
}

Class SearchFile {      

	static function Main($fname, $stype, $bid, $ucode ,$uid){	

	    Core::InitDataCache(); //初始化数据缓类
	    
		if(empty($fname) || empty($ucode)){
			return array();
		}

		$begin = $bid*20;

		Core::InitDb();
		$db = Core::$db['online'];
		
		$key_fc = file_get_contents("http://2.xzbbm.sinaapp.com/main/?action=ChFc&str=$fname");
		$key_fc = json_decode($key_fc,true);
		
		$long_key = "file_tag LIKE '%$fname%' or file_name LIKE '%$fname%' or ";
		
		if($key_fc[0][word] != $fname){
			foreach ($key_fc as $data){
				$long_key .= "file_tag LIKE '%$data[word]%' or file_name LIKE '%$data[word]%' or ";
			}
		}
		
		$long_key = rtrim($long_key,"or ");

		$paras = "file_id, file_info, file_name, from_unixtime(file_time, '%Y-%m-%d') file_time, file_extension, file_downs, file_views, good_count";
		$condition = "( ucode = $ucode or ucode = 0 ) AND ($long_key)";
		$limit = "limit $begin,20";

		if($stype == 0){
			$order = 'order by file_downs desc';
		}elseif($stype == 1){
			$order = 'order by file_time desc';
		}

		$sql = "select $paras from pd_files where $condition $order $limit";
		
		$rs = $db->dataArray($sql);

	    foreach($rs as $k => $v){
	    	if(isset($v['file_info']) && empty($v['file_info'])){
	    		$rs[$k]['file_info'] = "下载 $v[file_downs]  浏览 $v[file_views]  点赞 $v[good_count]";
	    	}
	    }
		//可以做异步		
		/*		
		$rs1 = $db->rsArray("select searchid,file_ids from pd_search_index where word = '$fname' and ( ucode = '$ucode' or ucode = 0 ) limit 1");
		if(empty($rs1)){

			foreach($rs as $data){
				$ids .= $data['file_id'].',';
			}
			$ids = rtrim($ids);

			$value = array(
					'word' => $fname,
					'total_count' => 0,
					'file_ids' => $ids,
					'ucode' => $ucode
				      );

			$db->insert('pd_search_index',$value);
		}
        */
	    
	    foreach($rs as $k => $v){
	    	if(isset($v['file_info']) && empty($v['file_info'])){
	    		$rs[$k]['file_info'] = "发布：$v[file_time]  浏览：$v[file_views]";
	    	}
	    	$rs[$k]["download_addr"] = "http://www.xzbbm.cn/?do=FileDown&idf=$v[file_real_name]&key=$v[file_key]&token=kNUxOrw0oeVugRtb";
	    	$rs[$k]["qrcode_str"] = "http://www.xzbbm.cn/?do=ViewFile&file_id=$v[file_id]&download&from=app";
	    }
	    
		return $rs;	
	}
}
