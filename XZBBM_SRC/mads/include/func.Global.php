<?php
/**
 *
 *
 * @File name: func.Global.php
 * @todo: 全局函数
 * @author: jingki @ 2008-1-24
 * @modified   Melon @ 2010-04-26
 */
if ( ! defined( 'IN_SYS' ) ) {
    header( "HTTP/1.1 404 Not Found" );
    die ();
}

/**
 *
 *
 * @todo 自动加载类文件
 * @return void
 */
function __autoload( $class ) {
    $class_file = ROOT_DIR . 'include/class.' . $class . '.php';
    if ( class_exists( $class_file, false ) ) {
        return;
    } elseif ( ! is_readable( $class_file ) ) {
        throw new JException ( "unable to read class file" );
    } else {
        include $class_file;
    }
}

/**
 *
 *
 * @todo load app
 * 如果app name是多个单词的则每个单词以下划线分割，程序会自动加载对应的类
 * e.g. game_list => 加载 app.GameList.php
 * @param string  $appName application name
 */
function load_app( $appName = '' , $doMethod = '') {
	
	$_REQUEST [ACTION] = $appName == ''?$_REQUEST [ACTION]:$appName;
	$_REQUEST [DO_METHOD] = $doMethod == ''?$_REQUEST [DO_METHOD]:$doMethod;
	
	$_REQUEST [ACTION] = @$_REQUEST [ACTION] ? ucfirst( $_REQUEST [ACTION] ) : DEFAULT_ACTION;
    $_REQUEST [DO_METHOD] = @$_REQUEST [DO_METHOD] ? ucfirst( $_REQUEST [DO_METHOD] ) : DEFAULT_DO_METHOD;
    if ( strpos( $_REQUEST [ACTION], '_' ) !== false ) {
        $_REQUEST [ACTION] = str_replace( '_', ' ', $_REQUEST [ACTION] );
        $_REQUEST [ACTION] = str_replace( ' ', '', ucwords( $_REQUEST [ACTION] ) );
    }
    if ( strpos( $_REQUEST [DO_METHOD], '_' ) !== false ) {
        $_REQUEST [DO_METHOD] = str_replace( '_', ' ', $_REQUEST [DO_METHOD] );
        $_REQUEST [DO_METHOD] = str_replace( ' ', '', ucwords( $_REQUEST [DO_METHOD] ) );
    }
    $appName = $appName ? $appName : $_REQUEST [ACTION];
    
    load ( APP_DIR . 'app.' . ucfirst( $appName ) . '.php' );
}

/**
 *
 *
 * @todo load conifgure file
 */
function load_cfg( $cfgName = 'System' ) {
    load ( ROOT_DIR . 'config/inc.' . ucfirst( $cfgName ) . '.php' );
}

/**
 *
 *
 * @name load
 * @todo include a file
 * @param string  $file file name
 * @return void
 *
 */
function load( $file ) {
    if ( file_exists( $file ) ) {
        include_once $file;
    } else {
        throw new JException ( "file not exists" );
    }
}

/**
 *
 *
 * @name 调用模板
 * @author Melon`` @ 2008
 */
function template( $name, $temDir = 'default' ) {
    return sprintf( "%s/{$temDir}/tpl.%s.php", TPL_DIR, $name );
}

/**
 *
 *
 * @name send_cache_headers
 * @todo send http headers for cache control
 * @param int     $expire expire time in seconds
 * @return void
 *
 */
function send_cache_headers( $expire = 30, $charset = '' ) {
    if ( $expire == 0 ) {
        @header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
        @header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
        @header( "Cache-Control: no-cache, no-store, must-revalidate" );
        @header( "Pragma: no-cache" );
    } else {
        @header( "Expires: " . gmdate( "D, d M Y H:i:s", time() + $expire ) . " GMT" );
        @header( "Cache-Control: max-age=" . $expire );
    }
    if ( $charset )
        @header( "Content-type: text/html; charset=UTF-8" );
}

/**
 *
 *
 * @todo: 格式化sql
 * @example
 * $bind = array('value_1','value_2','value_3');
 * $sql = "delete from content where cid in (".array_to_sql($bind).")";
 * @author: Melon`` @ 2010
 *
 */
function array_to_sql( $data ) {
    if ( ! is_array( $data ) ) {
        if ( empty ( $data ) ) {
            return "''";
        }
        return "'{$data}'";
    } else {
        $count = count( $data );
        $ret = "";
        for ( $i = 0; $i < $count; $i ++ ) {
            $ret .= "'" . $data [$i] . "'";
            if ( $i != $count - 1 ) {
                $ret .= ",";
            }
        }
        return $ret;
    }
}

/**
 *
 *
 * @todo:  写文件
 * @author: Melon`` @ 2010
 *
 */
function write( $fileName, $content, $type = "w" ) {
    $fd = fopen( $fileName, $type );
    if ( $fd ) {
        fwrite( $fd, $content );
        fclose( $fd );
        return true;
    } else {
        return false;
    }
}

/**
 *
 *
 * @todo:  检查目录是否存在,没有新建
 * @param unknown $dir       目录相对路径
 * @param unknown $recursive 递归
 * @return ture:成功 flase:失败
 * @author: Melon`` @ 2010
 *
 */
function sdir( $dir, $recursive = false ) {
    if ( ! file_exists( $dir ) ) {
        @ mkdir( $dir, 0777, $recursive );
        if ( file_exists( $dir ) ) {
            return true;
        } else {
            return false;
        }
    } else {
        return true;
    }
}

/**
 *
 *
 * @todo:  删除一个目录
 * @param unknown $dir 目录相对路径
 * @return ture:成功 flase:失败
 * @author: Melon`` @ 2010
 *
 */
function srmdir( $path ) {
    if ( ! is_dir( $path ) )
        return false;
    $handle = @opendir( $path );
    while ( $val = @readdir( $handle ) ) {
        if ( $val == '.' || $val == '..' )
            continue;
        $value = $path . "/" . $val;
        if ( is_dir( $value ) ) {
            self::rmdir( $value );
        } else if ( is_file( $value ) ) {
                unlink( $value );
            }
    }
    @closedir( $handle );
    rmdir( $path );
    return true;
}

/**
 *
 *
 * @todo:  删除一个目录，rmdir别名
 * @param unknown $dir 目录相对路径
 * @return ture:成功 flase:失败
 * @author: Melon`` @ 2010
 *
 */
function deldir( $path ) {
    return srmdir ( $path );
}

/**
 *
 *
 * @todo: 获取客户端IP
 * @author: Melon`` @ 2010
 *
 */
function get_ip() {
    if ( isset ( $_SERVER ['HTTP_CLIENT_IP'] ) ) {
        $hdr_ip = stripslashes( $_SERVER ['HTTP_CLIENT_IP'] );
    } else {
        if ( isset ( $_SERVER ['HTTP_X_FORWARDED_FOR'] ) ) {
            $hdr_ip = stripslashes( $_SERVER ['HTTP_X_FORWARDED_FOR'] );
        } else {
            $hdr_ip = stripslashes( $_SERVER ['REMOTE_ADDR'] );
        }
    }
    return $hdr_ip;
}

function go_win( $link=1, $note='', $top=false, $js='', $exit=true ) {
    if ( $link === 1 ) {
        $link = './index.php';
    }elseif ( $link === 2 ) {
        $link = $_SERVER['HTTP_REFERER'];
    }

    $top = $top ? 'top.' : '';
    echo "<script type=\"text/javascript\">\n";
    echo "try{document.domain=\"56.com\";}catch(e){};\n";
    echo $js ? $js."\n":"";
    echo $note ? "alert('".preg_replace( "/(\r?\n)+/", " ", str_replace( "'", "\'", $note ) )."');\n" : "";
    echo "window.{$top}location='{$link}';";
    echo "</script>\n";

    if ( $exit ) {
        exit();
    }
}

/**
 *
 *
 * @todo 异步回调函数
 * @author Melon`` @ 2009
 */
function mCallback( $data, $script = FALSE, $delay = FALSE, $appendScript = '' ) {
    $append = array ( 'reload' => Core::$vars ['reload'], 'hide' => Core::$vars ['hide'] );
    $data = array_merge( $data, $append );
    $data = json_encode( $data );
    if ( $script === TRUE ) {
        echo "<script type=\"text/javascript\">\n";
        echo "try{document.domain = \"56.com\";}catch(e){};\n";
    }
    if ( $delay ) {
        echo "setTimeout(function(){\n";
    }
    if ( $_REQUEST ['callback'] ) {
        echo sprintf( "%s(%s);\n", $_REQUEST ['callback'], $data );
    }
    if ( $appendScript ) {
        echo $appendScript . "\n";
    }
    if ( $delay ) {
        echo "},{$delay});\n";
    }
    if ( $script === TRUE ) {
        echo "</script>\n";
    }
}

function data_to_html( $text, $htmlspcs = true ) {
    if ( $htmlspcs ) {
        $text = htmlspecialchars( $text );
    }
    $text = str_replace( array ( "\t", '   ', '  ' ), array ( '&nbsp; &nbsp; &nbsp; &nbsp; ', '&nbsp; &nbsp;', '&nbsp;&nbsp;' ), $text );
    $text = nl2br( $text );
    return $text;
}

function alert( $text ) {
    echo '<script type="text/javascript">alert("'.$text.'");parent.location.reload();</script>';
}

//获取完整URL
function curpageurl() {
    $pageURL = 'http';
    if ( $_SERVER["HTTPS"] == "on" ) {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ( $_SERVER["SERVER_PORT"] != "80" ) {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

function gbk2utf8($data){
  if(is_array($data)){
        return array_map('gbk2utf8', $data);
    }
  return iconv('gbk','utf-8',$data);
}

function utf82gbk($data){
  if(is_array($data)){
        return array_map('utf82gbk', $data);
    }
  return iconv('utf-8','gbk',$data);
}

/**
 *
 *
 * @package     BugFree
 * @version     $Id: FunctionsMain.inc.php,v 1.32 2005/09/24 11:38:37 wwccss Exp $
 * @author                  Chunsheng Wang <wwccss@263.net>
 * @param string  $String the string to cut.
 * @param int     $Length the length of returned string.
 * @param booble  $Append whether append "...": false|true
 * @return string           the cutted string.
 */
function syssubstr( $String, $Length, $Append = false ) {
    if ( strlen( $String ) <= $Length ) {
        return $String;
    }else {
        $I = 0;
        while ( $I < $Length ) {
            $StringTMP = substr( $String, $I, 1 );
            if ( ord( $StringTMP ) >=224 ) {
                $StringTMP = substr( $String, $I, 3 );
                $I = $I + 3;
            }elseif ( ord( $StringTMP ) >=192 ) {
                $StringTMP = substr( $String, $I, 2 );
                $I = $I + 2;
            }else {
                $I = $I + 1;
            }
            $StringLast[] = $StringTMP;
        }
        $StringLast = implode( "", $StringLast );
        if ( $Append ) {
            $StringLast .= "...";
        }
        return $StringLast;
    }
}

function intformat($int){
	if($int < 0) {
		return $int;
	}
	$rs = array();
	while($int){
		if($int<1000){
			$rs[] = fmod($int,1000);
		}else{
			$rs[] = substr(strval(fmod($int,1000)+1000),1,3);
		}
		$int = floor($int / 1000);
	}
	return implode(',',array_reverse($rs));
}

function randomcolor() {
    $str = '#';
    for($i = 0 ; $i < 6 ; $i++) {
        $randNum = rand(0 , 15);
        switch ($randNum) {
            case 10: $randNum = 'A'; break;
            case 11: $randNum = 'B'; break;
            case 12: $randNum = 'C'; break;
            case 13: $randNum = 'D'; break;
            case 14: $randNum = 'E'; break;
            case 15: $randNum = 'F'; break;
        }
        $str .= $randNum;
    }
    return $str;
}

/**
 * 对Unicode编码进行解码
 * @param $str Unicode编码的字符串
 * @param $code 返回汉字字符串的编码，默认utf-8
 */
function uni_decode ($str, $code = 'utf-8'){
	$str = json_decode(preg_replace_callback('/&#(\d{5});/', create_function('$dec', 'return \'\\u\'.dechex($dec[1]);'), '"'.$str.'"'));
	if($code != 'utf-8'){
		$str = iconv('utf-8', $code, $str);
	}
	return $str;
}

/**
 * 对汉字进行Unicode编码 （#21704;&#21704;）
 * @param $str 汉字字符串
 * @param $code 汉字字符串的编码，默认utf-8
 */
function uni_encode ($str, $code = 'utf-8'){
	if($code != 'utf-8'){
		$str = iconv($code, 'utf-8', $str);
	}
	$str = json_encode($str);
	$str = preg_replace_callback('/\\\\u(\w{4})/', create_function('$hex', 'return \'&#\'.hexdec($hex[1]).\';\';'), substr($str, 1, strlen($str)-2));
	return $str;
}

/**
 * @todo 生成随机字符串
 */
function makerandom($length){
	$seed = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$str = "";
	while(strlen($str) < $length){
		$str .= substr($seed,(mt_rand() % strlen($seed)),1);
	}
	return $str;
}

/**
 * @todo ASCII解码
 */
function ascii_decode($str){
	preg_match_all( "/(d{2,5})/", $str,$a);
	$a = $a[0];
	foreach ($a as $dec){
		if ($dec < 128){
			$utf .= chr($dec);
		}elseif ($dec < 2048){
			$utf .= chr(192 + (($dec - ($dec % 64)) / 64));
			$utf .= chr(128 + ($dec % 64));
		}else{
			$utf .= chr(224 + (($dec - ($dec % 4096)) / 4096));
			$utf .= chr(128 + ((($dec % 4096) - ($dec % 64)) / 64));
			$utf .= chr(128 + ($dec % 64));
		}
	}
	return $utf;
}

/**
 * @todo ASCII编码
 */
function ascii_encode($c){
	$len = strlen($c);
	$a = 0;
	while ($a < $len){
		$ud = 0;
		if (ord($c{$a}) >=0 && ord($c{$a})<= 127){
			$ud = ord($c{$a});
			$a += 1;
		}elseif (ord($c{$a}) >=192 && ord($c{$a})<=223 ){
			$ud = (ord($c{$a})-192)*64 + (ord($c{$a+1})-128);
			$a += 2;
		}elseif (ord($c{$a}) >=224 && ord($c{$a})<=239 ){
			$ud = (ord($c{$a})-224)*4096 + (ord($c{$a+1})-128)*64 + (ord($c{$a+2})-128);
			$a += 3;
		}elseif (ord($c{$a}) >=240 && ord($c{$a})<=247 ){
			$ud = (ord($c{$a})-240)*262144 + (ord($c{$a+1})-128)*4096 + (ord($c{$a+2})-128)*64 + (ord($c{$a+3})-128);
			$a += 4;
		}elseif (ord($c{$a}) >=248 && ord($c{$a})<=251 ){
			$ud = (ord($c{$a})-248)*16777216 + (ord($c{$a+1})-128)*262144 + (ord($c{$a+2})-128)*4096 + (ord($c{$a+3})-128)*64 + (ord($c{$a+4})-128);
			$a += 5;
		}elseif (ord($c{$a}) >=252 && ord($c{$a})<=253 ){
			$ud = (ord($c{$a})-252)*1073741824 + (ord($c{$a+1})-128)*16777216 + (ord($c{$a+2})-128)*262144 + (ord($c{$a+3})-128)*4096 + (ord($c{$a+4})-128)*64 + (ord($c{$a+5})-128);
			$a += 6;
		}elseif (ord($c{$a}) >=254 && ord($c{$a})<=255 ){ //error
			$ud = false;
		}
		$scill .= "&#$ud;";
	}
	return $scill;
}

/**
 * @todo 获取两个日期之间的日期
 * @input 时间戳或者标准格式日期 $type = 0 不包含最后一天 $type = 1 包含最后一天
 * @output 两个时间之间的日期 
 * @author bo.wang3
 */
function pre_dates($start,$end,$type = 0){
	
	$dt_start = is_numeric($start)?$start:strtotime($start);
	$dt_end = is_numeric($end)?$end:strtotime($end);
	$dt_end = $type == 0?$dt_end:$dt_end+86400;
	
	while ($dt_start<$dt_end){ //不能等于，理由是最后一天的0点表示最后一天无投放，计算投放天数时不能把最后一天算进去
		$dates[] = date('Ymd',$dt_start);
		$dt_start = strtotime('+1 day',$dt_start);
	}
	return $dates;
}

/**
 * @todo 生成随机签名
 * @author bo.wang3
 */
function gc_sig(){ 
	return strtoupper(md5($_SESSION['admin'].$_SERVER['REMOTE_ADDR'].TIMESTAMP.mt_rand(0,1000)));
}

/**
 * @todo 获取指定字符之间的内容
 * @author bo.wang3
 */
function get_mid_chars($string, $start, $end=null){ 
	
	if(($start_pos = strpos($string, $start)) !== false) {
		if($end) {
			if(($end_pos = strpos($string, $end, $start_pos + strlen($start))) !== false) {
				return substr($string, $start_pos + strlen($start), $end_pos - ($start_pos + strlen($start)));
			}
		} else {
			return substr($string, $start_pos);
		}
	}
	return false;
	
}

/**
 * @todo 判断是否utf8编码
 * @author bo.wang3
 */
function is_utf8($str) {

	$encode = mb_detect_encoding($str, array('ASCII','UTF-8','GB2312','GBK'),true);

	if ($encode == 'UTF-8') {
		return true;
	} else {
		return false;
	}
}

/**
 * 自动识别数据编码并进行转换
 * @param array/string $data       数组
 * @param string $output    转换后的编码
 */
function iconv_encoding($data,  $output = 'utf-8') {
	$encode_arr = array('UTF-8','ASCII','GBK','GB2312','BIG5','JIS','eucjp-win','sjis-win','EUC-JP');
	$encoded = mb_detect_encoding($data, $encode_arr);

	if (!is_array($data)) {
		return mb_convert_encoding($data, $output, $encoded);
	}
	else {
		foreach ($data as $key=>$val) {
			$key = iconv_encoding($key, $output);
			if(is_array($val)) {
				$data[$key] = iconv_encoding($val, $output);
			} else {
				$data[$key] = mb_convert_encoding($data, $output, $encoded);
			}
		}
		return $data;
	}
}

/**
 * @todo 将标准的html表格转化为数组
 * @author bo.wang3
 */
function table2array($str) {
	
	$srt = get_mid_chars('<ta>');
	$str = preg_replace('/(<table>|<\/table>|<\/tr>|<\/td>)/', '', $str); //step 1
	$str = explode('<tr>', ltrim($str,'<tr>')); //step 2
	foreach ($str as $k=>$v) { //step 3
		$str[$k] = explode('<td>', ltrim($v,'<td>'));
	}
	
	return $str;
}

/**
 * @todo 将标准的csv表格转化为数组 
 * @description Spreadsheet_Excel_Reader识别的Excel下标是从1开始的 需要csv兼容一下
 * @ 既可以输入csv文件，也可以输入csv字符串
 * @author bo.wang3
 */
function csv2array($file_source,$ctc = '') {
	
	if(empty($ctc)){
		setlocale(LC_ALL, 'zh_CN');
		//国产WPS或者Office类软件重新保存之后可能导致编码改变
		$ctc = iconv_encoding(file_get_contents($file_source));
	}

	$csv = explode("\n", rtrim($ctc,"\n"));
	if(is_array($csv)){
		
		for($i=count($csv);$i>0;$i--){
		
			$csv[($i-1)] = explode(",", $csv[($i-1)]);
			for($j=count($csv[($i-1)]);$j>0;$j--){
				$csv[($i-1)][$j] = $csv[($i-1)][($j-1)];
			}
			unset($csv[($i-1)][0]);
			$csv[$i] = $csv[($i-1)];
		}
		unset($csv[0]);
		
		return $csv;
	}else{
		return false;
	}
	
	/*
	setlocale(LC_ALL, 'zh_CN');
	$handle = fopen($file_source,"r");
	$out = array ();
	$n = 0;
	
	while ($data = fgetcsv($handle, 10000)) {
		$num = count($data);
		for ($i = 0; $i < $num; $i++) {
			$out[$n][$i] = $data[$i];
		}
		$n++;
	}
	return $out;
	*/
}

/**
 * @todo 将二维数组转化为csv字符串便于导出csv文件
 * @author bo.wang3
 */
function array2csv($array) {
	
	foreach ($array as $value) {
		foreach ($value as $v){
			$csv_str .= iconv('utf8', 'gb2312', $v).',';
		}
		
		$csv_array[] .= rtrim($csv_str,',');
		unset($csv_str);
	}
	
	return implode("\n" , $csv_array);
}

/**
 * @todo 获取两个时间段之间的交集
 * @ $type = 0 不包含最后一天 $type = 1 包含最后一天
 * @input 
 * @ date1 = array('start' => '1413734400','end' => '1413735500')
 * @ date2 = array('start' => '1413731100','end' => '1413737770')
 * @output
 * @ array('start' => '1413734400','end' => '1413735500')
 * @author bo.wang3
 */
function GetMixedTime($date1,$date2,$type = 0) {
	
	$jdate = array(
			"start"=>max(intval($date1['start']),intval($date2['start'])),
			"end"=>min(intval($date1['end']),intval($date2['end']))
			);
	
	if($type == 0){
		return ($jdate['end'] < $jdate['start'])?false:$jdate;
	}else{
		return ($jdate['end'] <= $jdate['start'])?false:$jdate;
	}
}

/**
 * @todo 对二维数组去重
 * @author bo.wang3 http://justcoding.iteye.com/blog/708685
 */
function unique_2darr($array2D,$stkeep=true,$ndformat=true)
{
	// 判断是否保留一级数组键 (一级数组键可以为非数字)
	if($stkeep) $stArr = array_keys($array2D);

	// 判断是否保留二级数组键 (所有二级数组键必须相同)
	if($ndformat) $ndArr = array_keys(end($array2D));

	//降维,也可以用implode,将一维数组转换为用逗号连接的字符串
	foreach ($array2D as $v){
		$v = join(",",$v);
		$temp[] = $v;
	}

	//去掉重复的字符串,也就是重复的一维数组
	$temp = array_unique($temp);

	//再将拆开的数组重新组装
	foreach ($temp as $k => $v)
	{
		if($stkeep) $k = $stArr[$k];
		if($ndformat)
		{
			$tempArr = explode(",",$v);
			foreach($tempArr as $ndkey => $ndval) $output[$k][$ndArr[$ndkey]] = $ndval;
		}
		else $output[$k] = explode(",",$v);
	}

	return $output;
}

?>