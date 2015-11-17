<?php
if(!defined('IN_SYS')) {
	header("HTTP/1.1 404 Not Found");
	die;
}

Class TagCloud {

	static function Main($ucode)
	{
		Core::InitDb();

		$db = Core::$db['online'];
		$rs = $db->dataArray("select word from xz_tagscloud where ucode = $ucode and total > 3 order by total desc limit 0,80");

		foreach ($rs as $k => $v){
			if(strlen($v['word']) < 20){
				$tag = array(
						'tag' => $v['word'],
						'weight' => $k>40?($k>60?4:3):($k>20?2:1)
				);
				$tags[] = $tag;
			}
		}
		
		if(count($tags) < 8){
			$comm_word = array(
					'考研' => 4,
					'四级' => 4,
					'六级' => 4,
					'真题' => 4,
					'公务员' => 3,
					'司法考试' => 3,
					'简历' => 2,
					'专业八级' => 2,
					'注册会计师' => 2,
					'数学' => 2
			);
				
			foreach($comm_word as $k1 => $v1){
				$tag = array(
						'tag' => $k1,
						'weight' => $v1
				);
				$tags[] = $tag;
			}
		}
		
		shuffle($tags);
		return $tags;
	}

	static function GetTags($ucode)
	{
		Core::InitDb();
	
		$db = Core::$db['online'];
		$rss = $db->dataArray("select college from geo_colleges where university_id = $ucode");
		foreach ($rss as $rs){
			$colleges[] = $rs['college'];
		}
		
		$tags = array(
				'colleges' => $colleges,
				'tags' => array("考试真题","课后答案","课件讲稿","课程设计","考研资料","其他资料")
				);
		return $tags;
	}
}
