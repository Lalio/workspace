<?php
if(!defined('IN_SYS')) {
    echo ("HTTP/1.1 404 Not Found");
    die;
}
//APP分类配置 前三个为公共分类 后边的为通用分类
Core::$configs['classes'] = array(
     array('id'    => 0,
     	   'name'  => '校园排行榜',
     	   'type'  => 'tab',
           'f_id'=> -1),
     array('id'    => 1,
     	   'name' => '大家都在看',
     	   'type'  => 'tab',
           'f_id'=> -1),
     array('id' => 9,
           'name' => '24H关注榜',
     	   'type'  => 'tab',
           'f_id'=> -1),
     array('id' => 5,
           'name' => '考研弹药库',
           'tag' => '考研',
     	   'type'  => 'tab',
           'f_id'=> -1),
	 array('id' => 8,
	       'name' => '职场处子秀',
	       'tag' => '简历',
	       'type'  => 'tab',
	       'f_id'=> -1),
     array('id' => 2,
           'name' => '决战CET',
           'tag' => '四六级',
           'type'  => 'tab',
           	'f_id'=> -1),
     array('id' => 3,
           'name' => '公考小贴士',
           'tag' => '公考',
     	   'type'  => 'tab',
           'f_id'=> -1),
     array('id' => 4,
           'name' => '司考对对碰',
           'tag' => '司考',
     	   'type'  => 'tab',
           'f_id'=> -1),
     array('id' => 6,
           'name' => '专八竞技场',
           'tag' => '专业八级',
     	   'type'  => 'tab',
           'f_id'=> -1),
     array('id' => 7,
           'name' => '注册会计师',
           'tag' => '注会资料',
     	   'type'  => 'tab',
           'f_id'=> -1),
 );