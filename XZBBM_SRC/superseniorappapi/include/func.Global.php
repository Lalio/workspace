<?php
/**
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

function formatSize($size){
    $size  = doubleval($size);
    $rank =0;
    $rankchar ='B';
    while($size>1024){
        $size = $size/1024;
        $rank++;
    }
    if($rank==1){
        $rankchar="K";
    }
    else if($rank==2){
        $rankchar="M";
    }
    else if($rank==3){
        $rankchar="G";
    }
    $size = number_format($size, 2, '.', '');
    return  $size."".$rankchar;
}

/**
 *
 *
 * @todo: 格式化时间
 * @author: Melon`` @ 2010
 *
 */
function timeformat( $time, $i = '1' ) {
    switch ( $i ) {
    case 2 :
        return date( "Y-m-d", $time );
        break;
    case 3 :
        return date( "H:i:s", $time );
        break;
    case 4 :
        return date( "m-d", $time );
        break;
    case 5 :
        $left = $_SERVER ['REQUEST_TIME'] - $time;
        if ( $left < 0 ) {
            return '刚刚发布';
        }
        $sec_per_min = 60;
        $sec_per_hour = 3600;
        $sec_per_day = $sec_per_hour * 24;
        $sec_per_week = $sec_per_day * 7;
        $sec_per_month = $sec_per_day * 30;
        //$sec_per_year = $sec_per_day  * 365;


        if ( $left > 3 * $sec_per_month ) {
            return date( "Y-m-d", $time );
        }

        if ( $left >= $sec_per_month ) {
            $m = floor( $left / $sec_per_month );
            $left -= $m * $sec_per_month;
        }
        if ( $left >= $sec_per_week ) {
            $w = floor( $left / $sec_per_week );
            $left -= $w * $sec_per_week;
        }
        if ( $left >= $sec_per_day ) {
            $d = floor( $left / $sec_per_day );
            $left -= $d * $sec_per_day;
        }
        if ( $left >= $sec_per_hour ) {
            $h = floor( $left / $sec_per_hour );
            $left -= $h * $sec_per_hour;
        }
        if ( $left >= $sec_per_min ) {
            $im = floor( $left / $sec_per_min );
            $left -= $im * $sec_per_min;
        }

        //$ret [0] .= $y ? $y . '年' : '';
        $ret [1] = $m ? $m . '个月' : '';
        $ret [2] = $w ? $w . '周' : '';
        $ret [3] = $d ? $d . '天' : '';
        $ret [4] = $h ? $h . '小时' : '';
        $ret [5] = $im ? $im . '分钟' : '';
        $ret [6] = $left ? $left . '秒' : '';

        $max_return_fields = 2;
        $now = '';
        foreach ( $ret as $item ) {
            if ( empty ( $item ) ) {
                continue;
            }
            $now .= $item;
            $max_return_fields --;
            if ( $max_return_fields <= 0 || $m || $w || $d ) {
                break;
            }
        }
        unset ( $item );

        if ( empty ( $now ) ) {
            return null;
        } else {
            return $now . '前';
        }

        break;
    case 6 :
        return date( "Y-m-d H:i", $time );
        break;
    case 7 :
        return date( "Y-m", $time );
        break;
    case 8 :
        return date( "Y年m月", $time );
        break;
    case 9 :
        return date( "ymd", $time );
        break;
    case 10 :
        return date( "ym", $time );
        break;
    case 11 :
        return date( "d", $time );
        break;
    case 12 :
        return date( "Y", $time );
        break;
    case 13 :
        return date( "H:i", $time );
        break;
    default :
        return date( "Y-m-d H:i:s", $time );
        break;
    }

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
            srmdir( $value );
        } else if ( is_file( $value ) ) {
            unlink( $value );
            echo "   - 清除成功<br>";
        }
    }
    @closedir( $handle );
    echo $path;var_dump(rmdir( $path ));
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

// function to parse the http auth header
function http_digest_parse($txt)
{
    // protect against missing data
    $needed_parts = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1);
    $data = array();

    preg_match_all('@(\w+)=([\'"]?)([a-zA-Z0-9=./\_-]+)\2@', $txt, $matches, PREG_SET_ORDER);

    foreach ($matches as $m) {
        $data[$m[1]] = $m[3];
        unset($needed_parts[$m[1]]);
    }

    return $needed_parts ? false : $data;
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

function get_adress($ip) {
	
	$ip = $ip?$ip:get_ip();
	$rs = file_get_contents('http://ip.taobao.com/service/getIpInfo.php?ip='.$ip);
	$rs = json_decode($rs,true);
	
	$str = "{$rs['data']['region']} {$rs['data']['city']}";
	$str = str_replace('省', '', $str);
	$str = str_replace('市', '', $str);
	
	return "{$rs['data']['region']} {$rs['data']['city']}";
}

function go_win( $link=1, $note='', $top=false, $js='', $exit=true ) {
    if ( $link === 1 ) {
        $link = 'http://www.xzbbm.cn';
    }elseif ( $link === 2 ) {
        $link = $_SERVER['HTTP_REFERER'];
    }

    $top = $top ? 'top.' : '';
    echo "<script type=\"text/javascript\">\n";
    echo "try{document.domain=\"xzbbm.cn\";}catch(e){};\n";
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

function half_replace($str){
	$len = strlen($str)/2;
	return substr_replace($str,str_repeat('*',$len),ceil(($len)/2),$len);
}

function jcallback($json){
	return "$_REQUEST[callback]($json)";    
}

function alert( $text ) {
    echo '<script type="text/javascript">alert("'.$text.'")</script>';
    echo '<script type="text/javascript">location.href="http://xzbbm.cn"</script>';
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

function syssubstr($sourcestr,$cutlength, $Append = false){
    $returnstr='';
    $i=0;
    $n=0;
    $str_length=strlen($sourcestr);//字符串的字节数
    while (($n<$cutlength) and ($i<=$str_length)){
        $temp_str=substr($sourcestr,$i,1);
        $ascnum=Ord($temp_str);//得到字符串中第$i位字符的ascii码
        if ($ascnum>=224) {//如果ASCII位高与224，
            $returnstr=$returnstr.substr($sourcestr,$i,3); //根据UTF-8编码规范，将3个连续的字符计为单个字符
            $i=$i+3; //实际Byte计为3
            $n++; //字串长度计1
        }elseif ($ascnum>=192){ //如果ASCII位高与192，
            $returnstr=$returnstr.substr($sourcestr,$i,2); //根据UTF-8编码规范，将2个连续的字符计为单个字符
            $i=$i+2; //实际Byte计为2
            $n++; //字串长度计1
        }elseif ($ascnum>=65 && $ascnum<=90){ //如果是大写字母，
            $returnstr=$returnstr.substr($sourcestr,$i,1);
            $i=$i+1; //实际的Byte数仍计1个
            $n++; //但考虑整体美观，大写字母计成一个高位字符
        }else{ //其他情况下，包括小写字母和半角标点符号，
            $returnstr=$returnstr.substr($sourcestr,$i,1);
            $i=$i+1; //实际的Byte数计1个
            $n=$n+0.5; //小写字母和半角标点等与半个高位字符宽...
        }
    }
    if ($str_length > $cutlength && $Append === true){
        $returnstr = $returnstr . "...";//超过长度时在尾处加上省略号
    }
    return $returnstr;
}


/**
 * @todo 随机生成字符串
 * @author Miracle
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
 * @todo 读取二进制字节判断文件类型是否正确
 * @author Miracle
 */
function file_type($filename)
{
    $file = fopen($filename, "rb");
    $bin = fread($file, 4);
    fclose($file);
    $strInfo = @unpack("C4chars", $bin);
    $fileCode = intval($strInfo['chars1'].$strInfo['chars2'].$strInfo['chars3'].$strInfo['chars4']);

    $validCode = array(
    		255216255225,
    		255216255224,
    		2147483647,
    		807534,
    		37806870,
    		73848370,
    		829711433,
    		20820717224);
    
    if(in_array($fileCode,$validCode)){
        return true;
    }else{
        return $fileCode;
    }
}

/**
 * @todo 格式化数字
 * @author Miracle
 */
function intformat($int){
	if(empty($int)) { 
		return 0;
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

/**
 * @todo 识别爬虫访问
 * @author Miracle
 */
function is_crawler() {
	$userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
	$spiders = array(
		'Googlebot', // Google 爬虫
		'Baiduspider', // 百度爬虫
		'Yahoo! Slurp', // 雅虎爬虫
		'YodaoBot', // 有道爬虫
		'msnbot', // Bing爬虫
		'iaskspider',// 更多爬虫关键字
		'Sogou',
		'360',
		'Baidu',
		'baidu',
		'Google',
		'google'
	);
	foreach ($spiders as $spider) {
		$spider = strtolower($spider);
		if (strpos($userAgent, $spider) !== false) {
			return true;
		}
	}
	return false;
}

/**
 * @todo Linux环境下不解压缩读取zip包内文件
 * @author Miracle
 */
function getzipinfo($path,$file_size) {
	$zip = zip_open($path);
	if ($zip){
	  $str =  "<h1>这是一份压缩包类型的资料，包含：</h1>";
	  $i = 0;
	  $j = 0;
	  while ($zip_entry = zip_read($zip)){
	  	
	  	$file_name = zip_entry_name($zip_entry);
	  	$file_name = iconv('gbk','utf8',$file_name);
	  	
	  	if(strstr($file_name,'/')){
		  	$file_name_b = explode('/', $file_name);
		  	unset($file_name_b[0]);
		  	if(empty($file_name_b[1])) continue;
		  	
		  	$file_name_a = explode('.', implode('/',$file_name_b));
		  	$file_extension = $file_name_a[1];
		  	$file_name = "$file_name_a[0].$file_extension";
	  	}else{
	  		$file_name_a = explode('.', $file_name);
	  		$file_extension = $file_name_a[1];
	  		$file_name = "$file_name_a[0].$file_extension";
	  	}
	  	
	  	if(!empty($file_name)){
  	        if($file_extension){
  	            /* $str .= '<p><img onerror="this.src=\'http://cdn.xzbbm.cn/web/images/chm.png\'" src="http:/'.DOMAIN.'/images/' .
  	             strtolower($file_extension) . '.png" /> ' .
  	            $file_name . '<p/>'; */
  	            $str .= (($i + $j) < 20)?'<h2 style="font-size:28px"> ' . $file_name . '</h2>':'';
  	            $i++;
  	        }else{
  	            $str .= (($i + $j) < 20)?'<h2 style="font-size:28px">-|  ' . $file_name . '</h2>':'';
  	            $j++;
  	        }
	  	}
	  	
  	  }
  	  $str .= $j==0?'<h1 style="font-size:38px">共'.$i.'个文件，压缩包大小为'.round(($file_size/1024),2).'KB</h1>':'<h1 style="font-size:38px">共'.$i.'个文件，'.$j.'个目录，压缩包大小为'.round(($file_size/1024),2).'KB</h1>';
	}

	zip_close($zip);
	return $str;
}


/**
 * @todo Linux环境下不解压缩读取rar包内文件
 * @ 优化算法 扩展名识别率达到100%，时间 + 空间复杂度降低50%
 * @author Miracle
 */
function getrarinfo($path,$file_size) {
	
	exec("unrar l ".$path,$result);
	
	$i = 0;
	$j = 0;
	$start = array_search('-------------------------------------------------------------------------------', $result);
	$end = count($result)+1;
	
	foreach($result as $k => $v){
		if($k <= $start || $k >= $end){
			unset($result[$k]);
		}else{
			if($v == '-------------------------------------------------------------------------------'){
				$end = $k;
				unset($result[$k]);
			}
		}
	}
	
	foreach($result as $k => $v){
			
		$result[$k] = iconv('gbk','utf8',$v);
		
		$file_name = $result[$k];
		$file_name_a = explode('.', $file_name);
		$file_extension = $file_name_a[count($file_name_a)-1];
		
		preg_match('/\.\w{2,4}/', $result[$k], $output);
		

	    if(!empty($output[0])){
	        $extension = str_replace('.', '', $output[0]);
	        $tmp = explode($extension,$file_name);
	        $fullname = trim($tmp[0]).$extension;
	        /*          $str .= '<p><img onerror="this.src=\'http://' . DOMAIN .
	         '/images/chm.png\'" src="http://'.DOMAIN.'/images/' .
	        strtolower($extension) . '.png" /> ' . $fullname . '<p/>'; */
	        $str .= $k < 20?'<h2 style="font-size:28px"> ' . $fullname . '</h2>':'';
	        $i++;
	    }else{
	        $tmp = explode('        0',$result[$k]);
	        $fullname = trim($tmp[0]);
	        $str .= $k < 20?'<h2 style="font-size:28px">-|  ' . $fullname . '</h2>':'';
	        $j++;
	    }
	}
	
	if($i == 0 && $j == 0){
		$header = '<h1 style="font-size:38px">(!) 这是一份压缩包类型（已被加密）的资料</h1>';
		$footer = '<h1 style="font-size:38px">压缩包大小为'.round(($file_size/1024),2).'KB</h1>';
	}else{
		$header = '<h1 style="font-size:38px">这是一份压缩包类型的资料，包含：</h1>';
		$footer = $j==0?'<h1 style="font-size:38px">共'.$i.'个文件，压缩包大小为'.round(($file_size/1024),2).'KB</h1>':'<h1>共'.$i.'个文件，'.$j.'个目录，压缩包大小为'.round(($file_size/1024),2).'KB</h1>';
	}
	
	return $header.$str.$footer;
}

/**
 * @todo 生成文件存储目录结构
 * @author Miracle
 */
function make_store_dir($root = '/data/stores/pdf',$store_path) {

	$a_path = "$root/$store_path";
	
	if(!is_dir($a_path)){
		$path = explode('/',$store_path);
		
		$y_dir = $root.'/'.$path[0].'/';
		$m_dir = $y_dir.$path[1].'/';
		$d_dir = $m_dir.$path[2].'/';
		
		if(!is_dir($y_dir)){
			mkdir($y_dir ,0777);
		}
		if(!is_dir($m_dir)){
			mkdir($m_dir ,0777);
		}
		if(!is_dir($d_dir)){
			mkdir($d_dir ,0777);
		}
	}
	
	if(is_dir($a_path)){
		return $a_path;
	}else{
		exit('make_store_dir 目录构造失败！');
	}
}

function arrayToObject($e){
	if( gettype($e)!='array' ) return;
	foreach($e as $k=>$v){
		if( gettype($v)=='array' || getType($v)=='object' )
			$e[$k]=(object)arrayToObject($v);
	}
	return (object)$e;
}

function objectToArray($e){
	$e=(array)$e;
	foreach($e as $k=>$v){
		if( gettype($v)=='resource' ) return;
		if( gettype($v)=='object' || gettype($v)=='array' )
			$e[$k]=(array)objectToArray($v);
	}
	return $e;
}


/**
 * @todo 根据字符串生成指定范围的数字
 * @author Miracle
 */
function strtonum($str,$mod = 30) {

	return(abs(crc32($str))%$mod);
}


//判断是否属手机
function is_mobile() {
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	$mobile_agents = Array("240x320","acer","acoon","acs-","abacho","ahong","airness","alcatel","amoi","android","anywhereyougo.com","applewebkit/525","applewebkit/532","asus","audio","au-mic","avantogo","becker","benq","bilbo","bird","blackberry","blazer","bleu","cdm-","compal","coolpad","danger","dbtel","dopod","elaine","eric","etouch","fly ","fly_","fly-","go.web","goodaccess","gradiente","grundig","haier","hedy","hitachi","htc","huawei","hutchison","inno","ipad","ipaq","ipod","jbrowser","kddi","kgt","kwc","lenovo","lg ","lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-","lge9","longcos","maemo","mercator","meridian","micromax","midp","mini","mitsu","mmm","mmp","mobi","mot-","moto","nec-","netfront","newgen","nexian","nf-browser","nintendo","nitro","nokia","nook","novarra","obigo","palm","panasonic","pantech","philips","phone","pg-","playstation","pocket","pt-","qc-","qtek","rover","sagem","sama","samu","sanyo","samsung","sch-","scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank","sony","spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca","telit","tianyu","tim-","toshiba","tsm","up.browser","utec","utstar","verykool","virgin","vk-","voda","voxtel","vx","wap","wellco","wig browser","wii","windows ce","wireless","xda","xde","zte");
	$is_mobile = false;
	foreach ($mobile_agents as $device) {
		if (stristr($user_agent, $device)) {
			$is_mobile = true;
			break;
		}
	}
	return $is_mobile;
}

/**************************************************************
 *
 *	使用特定function对数组中所有元素做处理
 *	@param	string	&$array		要处理的字符串
 *	@param	string	$function	要执行的函数
 *	@return boolean	$apply_to_keys_also		是否也应用到key上
 *	@access public
 *
 *************************************************************/
function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
{
    static $recursive_counter = 0;
    if (++$recursive_counter > 1000) {
        die('possible deep recursion attack');
    }
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            arrayRecursive($array[$key], $function, $apply_to_keys_also);
        } else {
            $array[$key] = $function($value);
        }

        if ($apply_to_keys_also && is_string($key)) {
            $new_key = $function($key);
            if ($new_key != $key) {
                $array[$new_key] = $array[$key];
                unset($array[$key]);
            }
        }
    }
    $recursive_counter--;
}
/**************************************************************
 * for logging.
 * by why 
 * @param $str
 **************************************************************
 */
function say($str)
{
    file_put_contents ( "/data/backend_service/log/oss_api.log", "[say] $str \n", FILE_APPEND );
}
/**************************************************************
 * it could be useful if you using nginx instead of apache
 * by why 
 **************************************************************
 */
if (!function_exists('getallheaders'))
{
    function getallheaders()
    {
        $headers = '';
        foreach ($_SERVER as $name => $value)
        {
            if (substr($name, 0, 5) == 'HTTP_')
            {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}

/**
 * 得到新订单号
 * @return  string
 */
function build_order_no()
{
    /* 选择一个随机的方案 */
    mt_srand((double) microtime() * 1000000);

    /* PHPALLY + 年月日 + 6位随机数 */
    return date('Ymd') . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
}
