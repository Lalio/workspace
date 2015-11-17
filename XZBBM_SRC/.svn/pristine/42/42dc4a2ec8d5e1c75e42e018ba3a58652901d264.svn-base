<?php
if(!defined('IN_SYS')) {
	header("HTTP/1.1 404 Not Found");
	die;
}

Class FilePush {

	static function Main($scode, $bid ,$type){

		//初始化学校列表 利用数据缓存避免重复查表
		Core::InitDataCache();
		Core::InitDb();
		$dc = Core::$dc;
		$db = Core::$db['online'];

		$sql = 'SELECT * FROM geo_universities ORDER BY weight DESC';
		$res = $dc->getData(md5($sql),864000);
		if(false === $res || $_GET['i'] == 'c'){
			$res = $db->dataArray($sql);
			$dc->setData(md5($sql),$res);
		}

		if(in_array($type,array(0,1,9))){ //学校资料
			
			switch ($type){
				case 0: $orderby = 'ORDER BY weight DESC,file_views DESC';break;
				case 1: $orderby = 'ORDER BY file_downs DESC';break;
				case 9: $orderby = 'ORDER BY file_time DESC';break;
			}
			
			
			foreach($res as $key => $value)
			{
				$collegeList[$value["id"]] = $value["name"];
			}
			
			//学校资源取18个
			$begin = ($bid-1)*18;
			$pri_rs = $db->dataArray("select file_id, file_name,  from_unixtime(file_time, '%Y-%m-%d') file_time, file_extension, file_views, file_info from pd_files where in_recycle = 0 and ucode = $scode $orderby limit $begin,18");
			
			if(empty($pri_rs)){ //学校已经被收录但资源为空
				//公共资源取20个
				$begin = ($bid-1)*20;
				$pub_rs = $db->dataArray("select file_id, file_name,  from_unixtime(file_time, '%Y-%m-%d') file_time, file_extension, file_views, file_info from pd_files where in_recycle = 0 and ucode = 0 $orderby limit $begin,20");
			}else{
				//公共资源取2个
				$begin = ($bid-1)*2;
				$pub_rs = $db->dataArray("select file_id, file_name,  from_unixtime(file_time, '%Y-%m-%d') file_time, file_extension, file_views, file_info from pd_files where in_recycle = 0 and ucode = 0 $orderby limit $begin,2");
			}
			
			$rs = array_merge($pri_rs,$pub_rs);

		}else{ //非校园的特殊资料
			
			foreach (Core::$configs[classes] as $v){
				$class[$v['id']] = $v['tag'];
			}
			
			$begin = ($bid-1)*20;
			
			$rs = $db->dataArray("select file_id, file_name,  from_unixtime(file_time, '%Y-%m-%d') file_time, file_extension, file_views, file_info from pd_files where file_tag LIKE '%".$class[$type]."%' order by file_downs desc limit 0,20");

		}
		
		foreach($rs as $k => $v){
			if(isset($v['file_info']) && empty($v['file_info'])){
				$rs[$k]['file_info'] = "发布：$v[file_time]  浏览：$v[file_views]";
			}
		}

		return $rs;
	}

}
