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
function load_app( $appName = '' ) {
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

/**
 *
 *
 * @todo:  中文截割GBK
 * @author: Melon`` @ 2010
 *
 */
function ssubstr( $string, $sublen = 20 ) {
    if ( $sublen >= strlen( $string ) ) {
        return $string;
    }
    for ( $i = 0; $i < $sublen - 2; $i ++ ) {
        if ( ord( $string {$i} ) < 127 ) {
            $s .= $string {$i};
            continue;
        } else {
            if ( $i < $sublen - 3 ) {
                $s .= $string {$i} . $string {++ $i};
                continue;
            }
        }
    }
    return $s . '..';
}

/**
 *
 *
 * @todo: 获取客户端IP
 * @author: Melon`` @ 2010
 *
 */
function ip() {
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

function half_replace($str){
	$len = strlen($str)/2;
	return substr_replace($str,str_repeat('*',$len),ceil(($len)/2),$len);
}

function alert( $text ) {
    echo '<script type="text/javascript">alert("'.$text.'")</script>';
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

/** json.hpack for PHP (4/5)
 * @description JSON Homogeneous Collection Packer
 * @version     1.0.1
 * @author      Andrea Giammarchi
 * @license     Mit Style License
 * @project     http://github.com/WebReflection/json.hpack/tree/master
 * @blog        http://webreflection.blogspot.com/
 */

/** json_hpack(homogeneousCollection:Array[, compression:Number]):Array
 * @param   Array       mono dimensional homogeneous collection of objects to pack
 * @param   [Number]    optional compression level from 0 to 4 - default 0
 * @return  Array       optimized collection
 */
function json_hpack($collection, $compression = 0){
    if(3 < $compression){
        $i      = json_hbest($collection);
        $result = json_hbest($i);
    } else {
        $header = array();
        $result = array(&$header);
        $first  = $collection[0];
        $k      = 0;
        foreach($first as $key => $value)
            $header[] = $key;
        $len = count($header);
        for($length = count($collection), $i = 0; $i < $length; ++$i){
            for(
                $item   = $collection[$i],
                $row    = array(),
                $j      = 0;
                $j < $len; ++$j
            )
                $row[$j] = $item->$header[$j];
            $result[] = $row;
        }
        $index  = count($result);
        if(0 < $compression){
            for($row = $result[1], $j = 0; $j < $len; ++$j){
                if(!is_float($row[$j]) && !is_int($row[$j])){
                    $first = array();
                    $header[$j] = array($header[$j], &$first);
                    for($i = 1; $i < $index; ++$i){
                        $value  = $result[$i][$j];
                        $l      = array_search($value, $first, true);
                        $result[$i][$j] = $l === false ? array_push($first, $value) - 1 : $l;
                    }
                    unset($first);
                }
            }
        }
        if(2 < $compression){
            for($j = 0; $j < $len; ++$j){
                if(is_array($header[$j])){
                    for($row = $header[$j][1], $value = array(), $first = array(), $k = 0, $i = 1; $i < $index; ++$i){
                        $value[$k] = $row[$first[$k] = $result[$i][$j]];
                        ++$k;
                    }
                    if(strlen(json_encode($value)) < strlen(json_encode(array_merge($first, $row)))){
                        for($k = 0, $i = 1; $i < $index; ++$i){
                            $result[$i][$j] = $value[$k];
                            ++$k;
                        }
                        $header[$j] = $header[$j][0];
                    }
                }
            }
        }
        elseif(1 < $compression){
            $length -= floor($length / 2);
            for($j = 0; $j < $len; ++$j){
                if(is_array($header[$j])){
                    if($length < count($first = $header[$j][1])){
                        for($i = 1; $i < $index; ++$i){
                            $value = $result[$i][$j];
                            $result[$i][$j] = $first[$value];
                        }
                        $header[$j] = $header[$j][0];
                    }
                }
            }
        }
        if(0 < $compression){
            for($j = 0; $j < $len; ++$j){
                if(is_array($header[$j])){
                    $enum = $header[$j][1];
                    $header[$j] = $header[$j][0];
                    array_splice($header, $j + 1, 0, array($enum));
                    ++$len;
                    ++$j;
                }
            }
        }
    }
    return $result;
}

/** json_hunpack(packedCollection:Array):Array
 * @param   Array       optimized collection to unpack
 * @return  Array       original  mono dimensional homogeneous collection of objects
 */
function json_hunpack($collection){
    for(
        $result = array(),
        $keys   = array(),
        $header = $collection[0],
        $len    = count($header),
        $length = count($collection),
        $i      = 0,
        $k      = 0,
        $l      = 0;
        $i < $len; ++$i
    ){
        $keys[] = $header[$i];
        $k = $i + 1;
        if($k < $len && is_array($header[$k])){
            ++$i;
            for($j = 1; $j < $length; ++$j){
                $row = &$collection[$j];
                $row[$l] = $header[$i][$row[$l]];
            };
        };
        ++$l;
    };
    for($j = 1; $j < $length; ++$j)
        $result[] = json_hunpack_createRow($keys, $collection[$j]);
    return $result;
}

/** json_hunpack_createRow(objectKeys:Array, values:Array):stdClass
 * @param   Array       a list of keys to assign
 * @param   Array       a list of values to assign
 * @return  stdClass    object representing the row
 */
function json_hunpack_createRow($keys, $array){
    $o = new StdClass;
    for($i = 0, $len = count($keys); $i < $len; ++$i)
        $o->$keys[$i] = $array[$i];
    return $o;
}

/** json_hbest(packedCollection:Array):Number
 * @param   Array       optimized collection to clone
 * @return  Number      best compression option
 */
function json_hbest($collection){
    static  $_cache = array();
    if(is_array($collection)){
        for($i       = 0,
            $j       = 0,
            $len     = 0,
            $length  = 0;
            $i < 4;
            ++$i
        ){
            $_cache[$i] = json_hpack($collection, $i);
            $len = strlen(json_encode($_cache[$i]));
            if($length === 0)
                $length = $len;
            elseif($len < $length){
                $length = $len;
                $j = $i;
            }
        }
        return $j;
    } else {
        $result = $_cache[$collection];
        $_cache = array();
        return $result;
    }
}

function makerandom($length){
    $seed = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    while(strlen($str) < $length){
        $str .= substr($seed,(mt_rand() % strlen($seed)),1);
    }
    return $str;
}

function long2str($v, $w) {
    $len = count($v);
    $n = ($len - 1) << 2;
    if ($w) {
        $m = $v[$len - 1];
        if (($m < $n - 3) || ($m > $n)) return false;
        $n = $m;
    }
    $s = array();
    for ($i = 0; $i < $len; $i++) {
        $s[$i] = pack("V", $v[$i]);
    }
    if ($w) {
        return substr(join('', $s), 0, $n);
    }
    else {
        return join('', $s);
    }
}
 
function str2long($s, $w) {
    $v = unpack("V*", $s. str_repeat("\0", (4 - strlen($s) % 4) & 3));
    $v = array_values($v);
    if ($w) {
        $v[count($v)] = strlen($s);
    }
    return $v;
}

function int32($n) {
    while ($n >= 2147483648) $n -= 4294967296;
    while ($n <= -2147483649) $n += 4294967296; 
    return (int)$n;
}

function xxtea_encrypt($str, $key) {
    if ($str == "") {
        return "";
    }
    $v = str2long($str, true);
    $k = str2long($key, false);
    if (count($k) < 4) {
        for ($i = count($k); $i < 4; $i++) {
            $k[$i] = 0;
        }
    }
    $n = count($v) - 1;

    $z = $v[$n];
    $y = $v[0];
    $delta = 0x9E3779B9;
    $q = floor(6 + 52 / ($n + 1));
    $sum = 0;
    while (0 < $q--) {
        $sum = int32($sum + $delta);
        $e = $sum >> 2 & 3;
        for ($p = 0; $p < $n; $p++) {
            $y = $v[$p + 1];
            $mx = int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
            $z = $v[$p] = int32($v[$p] + $mx);
        }
        $y = $v[0];
        $mx = int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
        $z = $v[$n] = int32($v[$n] + $mx);
    }
    return long2str($v, false);
}

function xxtea_decrypt($str, $key) {
    if ($str == "") {
        return "";
    }
    $v = str2long($str, false);
    $k = str2long($key, false);
    if (count($k) < 4) {
        for ($i = count($k); $i < 4; $i++) {
            $k[$i] = 0;
        }
    }
    $n = count($v) - 1;

    $z = $v[$n];
    $y = $v[0];
    $delta = 0x9E3779B9;
    $q = floor(6 + 52 / ($n + 1));
    $sum = int32($q * $delta);
    while ($sum != 0) {
        $e = $sum >> 2 & 3;
        for ($p = $n; $p > 0; $p--) {
            $z = $v[$p - 1];
            $mx = int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
            $y = $v[$p] = int32($v[$p] - $mx);
        }
        $z = $v[$n];
        $mx = int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
        $y = $v[0] = int32($v[0] - $mx);
        $sum = int32($sum - $delta);
    }
    return long2str($v, true);
}

/*
$name, log title

$level:
EMERG   system is unusable
ALERT   action must be taken immediately
CRIT    critical conditions
ERR             error conditions
WARNING warning conditions
NOTICE  normal, but significant, condition
INFO    informational message
DEBUG   debug-level message

$content

*/
function rlog($title, $level, $content)
{
        openlog("$title", LOG_PID, LOG_LOCAL0);
        syslog(LOG_DEBUG,"$level $content");
        closelog();
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

?>
