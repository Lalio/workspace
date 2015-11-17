<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;
}

Class GetSchoolCode {

	static function Main($province)
	{
		Core::InitDb();
		$db = Core::$db['online'];
		$rs = $db->dataArray('select university_id, name, sicon_id from geo_provinces,geo_universities where geo_provinces.province = "'.$province.'" and geo_provinces.province_id = geo_universities.province order by weight desc');
		
		foreach($rs as $k => $v){
			 $rs[$k]['id'] = $v['university_id'];
			 $rs[$k]['name'] = $v['name'];
		     $rs[$k]['icon'] = "http://cdn.xzbbm.cn/images/sicon/$v[sicon_id].png";
		}
		
		return $rs;
	}
}
