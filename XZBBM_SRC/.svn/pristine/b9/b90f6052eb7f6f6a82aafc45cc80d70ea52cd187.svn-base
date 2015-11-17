<?php
if(!defined('IN_SYS')){
	header("HTTP/1.1 404 Not found");		
	die;
}

define("ROOT_DIR",str_replace("\\","/",dirname(__FILE__))."/");//程序目录

include ROOT_DIR.'include/openSearch/CloudsearchClient.php';
include ROOT_DIR.'include/openSearch/CloudsearchSearch.php';

/* 阿里云OpenSearch操作类*/
class OpenSearchFile{
	
    static $client;

	static function InitSearchClient(){

		if(!isset(self::$client)){
			self::$client = new CloudsearchClient(
				"mpFFmaUpbVb62R1F", 
            	"mUcDNjdQSciQnXTxGIeL19jmv5BbSG", 
           		array('host' => 'http://intranet.opensearch-cn-hangzhou.aliyuncs.com'),//内网api入口
//            	array('host' => 'http://opensearch-cn-hangzhou.aliyuncs.com'),//公网api入口
            	"aliyun");
		}
	}		


	static function Main($fname, $stype, $bid, $ucode, $uid){

       //初始化搜索
        self::InitSearchClient();

        $search = new CloudsearchSearch(self::$client);
        $search->addIndex("xzbbm2015");

        //返回的字段
        $search->addFetchFields("file_id");
        $search->addFetchFields("file_info");
        $search->addFetchFields("file_name");
        $search->addFetchFields("file_time");
        $search->addFetchFields("file_extension");
        $search->addFetchFields("file_downs");
        $search->addFetchFields("file_views");
        $search->addFetchFields("good_count");
  //      $search->addFetchFields("file_key");
   //     $search->addFetchFields("file_real_name");        
  
        //过滤学校  
        $search->addFilter("ucode = ".$ucode. "OR ucode = 0" );

        //结果排序
        if($stype == 0){
            $search->addSort("file_downs","+");
        }elseif($stype == 1){
            $search->addSort("file_time","+");
        }

        //一次最多返回20条记录
        $hits = 20;
        $startHit = $bid * 20;
        $search->setHits($hits);        
        $search->setStartHit($startHit);
       
        $search->setQueryString("file_name:'".$fname."'"); 
         
		//搜索，返回json格式
        $search->setFormat('json');           
        $json = $search->search();

        $result = json_decode($json, true); 
        
        $result_items = $result['result']['items'];
        
		//转换时间格式 时间戳---->2015-12-12
		foreach($result_items as $k => $v){
	    	$time = $v['file_time'];
			$date = date("Y-m-d",$time);
			$result_items[$k]['file_time'] = $date;
		}

        //文件描述 下载次数，浏览次数，点赞次数
        foreach($result_items as $k => $v){
            if(isset($v['file_info']) && empty($v['file_info'])){
                $result_items[$k]['file_info'] = "下载 $v[file_downs]  浏览 $v[file_views]  点赞 $v[good_count]";
            }
        }

        //生成下载地址和二维码
        foreach($result_items as $k => $v){
            if(isset($v['file_info']) && empty($v['file_info'])){
                $result_items[$k]['file_info'] = "发布: $v[file_time]  浏览:$v[file_views]";
            }  
            $result_items[$k]["download_addr"] = "http://www.xzbbm.cn/?do=FileDown&idf=$v[file_real_name]&key=$v[file_key]&token=kNUxOrw0oeVugRtb";
            $result_items[$k]["qrcode_str"] = "http://www.xzbbm.cn/?do=ViewFile&file_id=$v[file_id]&download&from=app";
       }

       return $result_items;   
    }
}



