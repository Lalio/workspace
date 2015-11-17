<?php
if (! defined ( 'IN_SYS' ))
{
	header ( "HTTP/1.1 404 Not found" );
	die ();
}

define ( "ROOT_DIR", str_replace ( "\\", "/", dirname ( __FILE__ ) ) . "/" ); // 程序目录

include ROOT_DIR . 'include/openSearch/CloudsearchClient.php';
include ROOT_DIR . 'include/openSearch/CloudsearchSearch.php';

/* 阿里云OpenSearch操作类 */
class OpenSearchFile
{
	static $client;
	static function InitSearchClient()
	{
		if (! isset ( self::$client ))
		{
			self::$client = new CloudsearchClient ( "mpFFmaUpbVb62R1F", "mUcDNjdQSciQnXTxGIeL19jmv5BbSG", array (
					'host' => 'http://intranet.opensearch-cn-hangzhou.aliyuncs.com'
			), // 内网api入口
					// array('host' => 'http://opensearch-cn-hangzhou.aliyuncs.com'),//公网api入口
					"aliyun" );
		}
	}
	static function Main($index, $key, $stype = 0, $from, $limit, $ucode, $ccode, $uname, $cname, $pay, $longitude, $latitude)
	{
		// $stype 0默认,1好评,2下载,3浏览,4付费,5推荐,6身边,
		// pay:0不限，1付费，2免费
		// 初始化搜索
		self::InitSearchClient ();

		$search = new CloudsearchSearch ( self::$client );
		$search->addIndex ( "xzbbm2015" );

		// 返回的字段
		$search->addFetchFields ( "file_id" );
		$search->addFetchFields ( "file_info" );
		$search->addFetchFields ( "file_key" );
		// $search->addFetchFields ( "file_description" );
		$search->addFetchFields ( "file_name" );
		$search->addFetchFields ( "file_time" );
		$search->addFetchFields ( "file_extension" );
		$search->addFetchFields ( "file_downs" );
		$search->addFetchFields ( "file_views" );
		$search->addFetchFields ( "good_count" );
		$search->addFetchFields ( "file_index" );
		$search->addFetchFields ( "uname" );
		$search->addFetchFields ( "cname" );
		$search->addFetchFields ( "ucode" );
		$search->addFetchFields ( "ccode" );
		$search->addFetchFields ( "file_real_name" );
		$search->addFetchFields ( "user_name" );
		$search->addFetchFields ( "user_description" );

		$search->addFetchFields ( "today_price" ); // by why
		$search->addFetchFields ( "price" ); // by why
		$search->addFetchFields ( "today_price" ); // by why
		$search->addFetchFields ( "file_real_name" ); // by why
		$search->addFetchFields ( "buy_count" ); // by why
		$search->addFetchFields ( "yaohe_length" ); // by why
		$search->addFetchFields ( "comment_count" ); // by why
		$search->addFetchFields ( "good_comment_rate" ); // by why
		$search->addFetchFields ( "userid" ); // by why
		$search->addFetchFields ( "in_recycle" ); // by why
		$search->addFetchFields ( "total_page" ); // by why
		$search->addFetchFields ( "free_view_page" ); // by why
		$search->addFetchFields ( "has_png" ); // by why

		$filter_str = "in_recycle = 0";

		if ($pay == 1) // 免费文档//by why// 价格筛选
		{
			$filter_str .= " AND today_price = 0 ";
			//$search->addFilter ( "today_price = 0" );
		}
		else if ($pay == 2) // 付费文档//by why
		{
			$filter_str .= " AND today_price > 0 ";
			//      $search->addFilter ( "today_price > 0" );
		}
		else
		{
			// 不限//by why
		}

		//         $search->addFilter ( "in_recycle=0" );//TODO
		// 过滤学校
		if ($ccode > 0)
		{
			$filter_str .= " AND (ccode='$ccode' OR ccode='0') ";
			//     $search->addFilter ( "ccode='$ccode' OR ccode='0'" );
			$search->addSort ( "ccode", "-" );
		}
		if ($ucode > 0)
		{
			$filter_str .= " AND (ucode='$ucode' OR ucode='0') ";
// 			$filter_str .= " AND (ucode='$ucode' OR ucode='0') ";
			//    $search->addFilter ( "ucode='$ucode' OR ucode='0'" );
			$search->addSort ( "ucode", "-" );
		}

// 		// 搜索附近
// 		if (! empty ( $longitude ) && ! empty ( $latitude ))
// 		{
// 			$filter_str .= " AND (distance(longitude,latitude,\"$longitude\",\"$latitude\")<2000 OR ucode = $ucode) ";
// 			//   $search->addFilter ( "distance(longitude,latitude,\"$longitude\",\"$latitude\")<2000 OR ucode = $ucode" ); // 附近100公里以及本校的资料
// 			$search->addSort ( "distance(longitude,latitude,\"$longitude\",\"$latitude\")", "+" ); // 由近到远
// 		}
		
		// 结果排序 #$stype 0默认,1好评,2下载,3浏览,4付费,5推荐,6身边
		if ($stype == 0)
		{
			if ($index == "ucode")
			{
				$search->addSort ( "file_views", "-" );
			}
			else
			{
				$search->addSort ( "file_time", "-" );
			}
		}
		elseif ($stype == 1)
		{
			$search->addSort ( "good_count", "-" );
		}
		elseif ($stype == 2)
		{
			$search->addSort ( "file_downs", "-" );
		}
		elseif ($stype == 3)
		{
			$search->addSort ( "file_views", "-" );
		}
		elseif ($stype == 4)
		{
			$search->addSort ( "buy_count", "-" );
		}
		elseif ($stype == 5)
		{
			$search->addSort ( "file_views", "-" ); // TODO
		}
		elseif ($stype == 6)
		{
		    if (! empty ( $longitude ) && ! empty ( $latitude ))
		    {
		        $filter_str .= " AND (distance(longitude,latitude,\"$longitude\",\"$latitude\")<2000 OR ucode = $ucode) ";
		        //   $search->addFilter ( "distance(longitude,latitude,\"$longitude\",\"$latitude\")<2000 OR ucode = $ucode" ); // 附近100公里以及本校的资料
		        $search->addSort ( "distance(longitude,latitude,\"$longitude\",\"$latitude\")", "+" ); // 由近到远
		    }else{
		        $search->addSort ( "file_views", "-" );
		    }
		}

		
		$search->addFilter($filter_str);
		
		$hits = $limit;
		$startHit = $from;
		$search->setHits ( $hits );
		$search->setStartHit ( $startHit );

		if ($index == "ucode")
		{
			// do nothing
		}
		else
		{
			$search->setQueryString ( "$index:'$key'" );
		}

		// 搜索，返回json格式
		$search->setFormat ( 'json' );
		$json = $search->search ();

		$result = json_decode ( $json, true );

		$result_items = $result ['result'] ['items'];

		return $result_items;
	}
}



