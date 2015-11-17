<?php
if ( !defined( 'IN_SYS' ) ) {
	header( "HTTP/1.1 404 Not Found" );
	die;
}

/**
 * @todo 借助SinaSAE实现中文分词接口
 * @author bo.wang3
 * @version 2013-10-26
 */
class ChFc {

	public function __construct(){
	    header("Content-Type: text/html; charset=".CHARSET);
	}
	
	public function Main(){
	
	    $str = $_REQUEST['str'];
        $seg = new SaeSegment();
        $ret = $seg->segment($str, 1);

        echo json_encode($ret);
	}

}
