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
    static function Main($fname, $stype, $from, $limit, $ucode, $ccode, $uname, $cname, $pay)
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
        $search->addFetchFields ( "file_name" );
        $search->addFetchFields ( "file_time" );
        $search->addFetchFields ( "file_extension" );
        $search->addFetchFields ( "file_downs" );
        $search->addFetchFields ( "file_views" );
        $search->addFetchFields ( "good_count" );
        $search->addFetchFields ( "file_real_name" );
        $search->addFetchFields ( "uname" );
        $search->addFetchFields ( "cname" );
        $search->addFetchFields ( "ucode" );
        $search->addFetchFields ( "ccode" );
        $search->addFetchFields ( "user_name" );
        
        // $search->addFetchFields("file_real_name");
        
        // 过滤学校
        $search->addFilter ( "ucode = " . $ucode . " OR ucode = 0" );
        
        // 结果排序 #$stype 0默认,1好评,2下载,3浏览,4付费,5推荐,6身边
        $search->addSort ( "ucode", "-" );
        if ($stype == 0)
        {
            $search->addSort ( "file_views", "-" ); // TODO
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
            $search->addSort ( "file_time", "-" ); // TODO
        }
        elseif ($stype == 5)
        {
            $search->addSort ( "file_views", "-" ); // TODO
        }
        elseif ($stype == 6)
        {
            $search->addSort ( "file_views", "-" ); // TODO
        }
        
        $hits = $limit;
        $startHit = $from;
        $search->setHits ( $hits );
        $search->setStartHit ( $startHit );
        
        $search->setQueryString ( "file_name:'" . $fname . "'" );
        
        // 搜索，返回json格式
        $search->setFormat ( 'json' );
        $json = $search->search ();
        
        $result = json_decode ( $json, true );
        
        $result_items = $result ['result'] ['items'];
        
        return $result_items;
    }
}



