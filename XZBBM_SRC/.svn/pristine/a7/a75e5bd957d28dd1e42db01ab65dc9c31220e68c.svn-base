<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;
}

Class Yao {

	static function Main($ucode,$nettype){
		
		Core::InitDb();
		$db = Core::$db['online'];
		
		//数据来源1
		$select = 'showtime,type,content,link,show_delay';
		$order = 'BY rand() DESC limit 0,30';
		
		$rs = $db->dataArray("SELECT $select FROM xz_yao ORDER $order");
		
		switch ($nettype){
			case 'wifi':
				//$rs = $db->rsArray("SELECT $select FROM xz_yao WHERE show_delay = 0 ORDER $order");
				break;
			case '3g':
				//$rs = $db->rsArray("SELECT $select FROM xz_yao WHERE show_delay = 0 ORDER $order");
				break;
			case 'gprs':
				//$rs = $db->rsArray("SELECT $select FROM xz_yao WHERE show_delay = 1 ORDER $order");
				break;
		}
		
		//数据来源2
		$yaos = array(
				"今天距离2015年1月4日硕士研究生考试还剩".floor((strtotime('2015-01-04 09:00:00') - TIMESTAMP)/86400)."天，考试时间为2015年1月4日至1月5日，一般可在2月底查询成绩。",
				"今天距离2014年3月23日英语专业八级考试（TEM-8）还剩".floor((strtotime('2014-03-23 09:00:00') - TIMESTAMP)/86400)."天，考试时间为2014年3月23日至3月24日。",
				"今天距离2014年3月30日计算机等级考试还剩".floor((strtotime('2014-03-30 09:00:00') - TIMESTAMP)/86400)."天，考试时间为2014年3月30日至4月1日。",
				"今天距离2014年4月20日韩语能力等级考试（JLPT）还剩".floor((strtotime('2014-04-20 09:00:00') - TIMESTAMP)/86400)."天，考试时间为2014年4月20日至4月21日。",
				);
		
		foreach($yaos as $yao){
			$rs[] = array(
					'showtime' => 300000,
					'type' => 'text',
					'content' => $yao,
					'link' => '',
					'show_delay' => 0
					);
		}
		
		shuffle($rs);
		$rs[0]['content'] = $rs[0]['content'];
		
		return $rs[0];
	}
}