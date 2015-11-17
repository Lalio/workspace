<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;
}

Class GetSubTypes {

	static function Main($ucode){
		Core::InitDb();
		$db = Core::$db['online'];
		$rs = $db->dataArray("select college from geo_colleges where university_id = $ucode");
		
		foreach($rs as $data){
			$cols[] = array('sname' => $data['college'],'skey' => $data['college']);
		}
		
		$i = 1;//id计数器
		
		$rt[0] = array('id' => $i,'name' => '我的大学','data' => $cols);
		
		$names = array(
				'期末试卷' => array(
				),
				'校内资料' => array(
						'课后答案','课程设计','实验报告','毕业论文','课件讲稿'
				),
				'研究生考试' => array(
						'数学','英语','政治','专业课','复试真题','高分心得'
				),
				'公务员考试' => array(
						'国考','省考','事业单位','申论','行测','提分经验'
				),
				'其他资料' => array(
						'四六级','司法考试','专业八级','求职简历','托福雅思','GRE','留学'
				)
		);
		
		foreach($names as $name => $types){
			
			foreach($types as $type){
				$d[] = array('sname' => $type , 'skey' => $type);
			}
			$rt[] = array('id' => ++$i,'name' => $name,'data' => $d);
			unset($d);
		}
		
		return $rt;
	}
}