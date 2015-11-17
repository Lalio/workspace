<?php
if(!defined('IN_SYS')) {    
	header("HTTP/1.1 404 Not Found");    
	die;
}
/**
 * @todo GPS定位接口 
 * code 0 排序方式为定位到省排第一，定位到的学校在该省也排第一。
 * code 1 定位到的省排第一。
 * code 2 默认排序
 * @author bo.wang
 * @version 2013-5-16 17:11
 */
Class Location { //手机用户学校定位接口 Baidu Geocoding API 反向地理编码

	static function Main($loc){

		//初始化学校列表 利用数据缓存避免重复查表
		Core::InitDataCache();
		Core::InitDb();
		$dc = Core::$dc;
		$db = Core::$db['online'];
		
		$sql = 'SELECT university_id,name,sicon_id,geo_provinces.province 
				FROM geo_universities,geo_provinces  
				WHERE geo_provinces.province_id = geo_universities.province
				ORDER BY weight DESC';
		
		$slist = $dc->getData(md5($sql),86400);
		if(false === $slist || $_GET['i'] == 'c'){
			$slist = $db->dataArray($sql);
			$dc->setData(md5($sql),$slist);
		}
		
		foreach($slist as $k => $v){
			$slist[$k]['id'] = $v['university_id'];
			$slist[$k]['name'] = $v['name'];
			$slist[$k]['icon'] = "http://cdn.xzbbm.cn/images/sicon/$v[sicon_id].png";
			unset($slist[$k]['university_id']);
			unset($slist[$k]['sicon_id']);
		}
		
		if($loc == '0,0'){
			$content['slist'] = $slist;
			return $content;
		}

		//坐标定位
		$param = array(
			'ak' => '3d1990c15d5941f53d744a62c63cacc0', //Baidu开发者密钥
			'location' => $loc,
			'output' => 'json',  //以json格式返回
			'pois' => 1  //显示周边100米内opi
		);

		$geolist = json_decode(file_get_contents('http://api.map.baidu.com/geocoder/v2/?'.http_build_query($param)),true);

		foreach($slist as $s){ //开始匹配
			$tags = explode(',', $s['tag']); //获得学校别称数组
			foreach ($tags as $t) {
				if(!empty($t)){
					if(false !== strpos($geolist['result']['formatted_address'],$t) || false !== strpos($geolist['result']['business'],$t)){ //主匹配
						//匹配成功
						$cur_school = $s;
						break 2; //跳出二重循环
					}else{ //辅助匹配
						foreach($geolist['result']['pois'] as $p){
							if(false !== strpos($p['addr'],$t) || false !== strpos($p['name'],$t)){
								//匹配成功
								$cur_school = $s;
								break 3; //跳出三重循环
							}
						}
					}
				}
			}
		}

		if(!empty($cur_school)){//定位学校成功 返回资源
			$content['code'] = 0;
			$sch_id = $cur_school['id'];
			$province_name = $cur_school['province'];
		}else{//定位学校不成功 进行省份定位
			foreach($slist as $s){
				if(false !== strpos($geolist['result']['formatted_address'],$s['province'])){
					$content['code'] = 1; //省份定位成功
					$province_name = $s['province'];
				}
			}
			if(!isset($content['code'])){
				$content['code'] = 2; //学校、省份均定位失败
			}
		}
		
		if(in_array($content['code'], array(0,1))){
			$sum = count($slist) - 1;
			for ($i = $sum ; $i >= 0 ; $i--) {//从后向前遍历
				if($slist[$i]['id'] != $sch_id){
					if($slist[$i]['province'] == $province_name){
						array_unshift($content['slist'],$slist[$i]);
					}else{
						$content['slist'][] = $slist[$i];
					}
				}else{
					$tmp = $slist[$i];
				}
			}

			if(isset($tmp)){
				array_unshift($content['slist'],$tmp);
			}
		}else{
			$content['slist'] = $slist;
		}

		foreach($content['slist'] as $k => $v){
			unset($content['slist'][$k]['tag']);
		}

		return $content;
	}


	public function Debug($loc){

		//初始化学校列表 利用数据缓存避免重复查表
		Core::InitDataCache();
		Core::InitDb();
		$dc = Core::$dc;
		$db = Core::$db['online'];
		
		$slist = $dc->getData(md5('schoollist'),86400);

		if(false === $slist || $_GET['i'] == 'c'){
			$sql = 'SELECT * FROM xz_universities';
			$slist = $db->dataArray($sql);
			$dc->setData(md5($sql),$slist);
		}

		//坐标定位
		$param = array(
			'ak' => '3d1990c15d5941f53d744a62c63cacc0', //Baidu开发者密钥
			'location' => $loc,
			'output' => 'json',  //以json格式返回
			'pois' => 1  //显示周边100米内opi
		);

		$geolist = json_decode(file_get_contents('http://api.map.baidu.com/geocoder/v2/?'.http_build_query($param)),true);

		foreach($slist as $s){ //开始匹配
			$tags = explode(',', $s['tag']); //获得学校别称数组
			foreach ($tags as $t) {
				if(!empty($t)){
					if(false !== strpos($geolist['result']['formatted_address'],$t) || false !== strpos($geolist['result']['business'],$t)){ //主匹配
						//匹配成功
						$cur_school = $s;
						break 2; //跳出二重循环
					}else{ //辅助匹配
						foreach($geolist['result']['pois'] as $p){
							if(false !== strpos($p['addr'],$t) || false !== strpos($p['name'],$t)){
								//匹配成功
								$cur_school = $s;
								break 3; //跳出三重循环
							}
						}
					}
				}
			}
		}

		if(!empty($cur_school)){//定位学校成功 返回资源
			$content['code'] = 0;
			$content['rs']['school_id'] = $cur_school['id'];
			$content['rs']['icon'] = $cur_school['icon'];
			$content['rs']['name'] = $cur_school['name'];
		}else{//定位学校不成功 返回该省的学校列表
			$content['code'] = 1;
			foreach($slist as $s){
				if(!empty($s) && false !== strpos($geolist['result']['formatted_address'],$s['province'])){
					$content['rs']['area'] = $s['province'];
					unset($s['tag']);
					unset($s['province']);
					$content['rs']['slist'][] = $s;
				}
			}
		}

		$content['debug'] = $geolist;
		return $content;
	}
}
