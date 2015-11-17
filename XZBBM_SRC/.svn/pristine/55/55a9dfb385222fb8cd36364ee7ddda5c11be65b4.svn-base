<?php
include_once('simple_html_dom.php');

/******************************************************
*descrip：获取互联网上的文件信息及链接
*params: keyword
*		 begin_item - 返回数据从第几条开始
* 		 ret_items - 返回数据的条数
*return: title - 文件标题
*        link - 连接地址
*        ext - 资料扩展名
*        source - 资料来源
*author: created by charly @201407
*******************************************************/
Class GetWebFile {
	static function Main($keyword, $begin_item, $ret_items){
		Core::InitDataCache();
		$memKey = md5($keyword.$begin_item.$ret_items);
		
		$rs = Core::$dc->getData($memKey);
		if(false === $rs){
			$page_count = 1; //配置返回搜索的页数
			
			//要搜索的网站配置
			$website = array(
					"百度文库" => "wenku.baidu.com",
					"道客巴巴" => "www.doc88.com",
					"CSDN" => "download.csdn.net"
			);
			
			foreach($website as $web_name => $site){
					
				for($pn=1; $pn <= $page_count; $pn++)
				{
				$url = "http://www.so.com/s?q=+" . $keyword . "+site:".$site."&pn=".$pn;
				$dom = file_get_html("$url");
				foreach($dom->find('h3 a') as $element)
				{
				$href = $element->href;
				$title = $element->plaintext;
				$ext =  $element->attr['data-stp'];
				if(strstr($href,$site))
				{
				$res["title"] = $title;
				$res["link"] = $href;
				$res["ext"] = $ext;
				$res["source"] = $web_name;
				$result[] = $res;
				}
				}
			}
				}
				sort($result);
				for($item = 0; $item < $ret_items; $item++)
				{
				$i= $begin_item + $item;
				$re[] = $result[$i];
			}
			Core::$dc->setData($memKey,$re);
			$rs = $re;
		}
		return $rs;
		
	}
}

//test code
//$keyword = $argv[1];
//$begin_item = $argv[2];
//$ret_items = $argv[3];

//$re = GetWebFile::Main($keyword, $begin_item, $ret_items);
//var_dump($re);
