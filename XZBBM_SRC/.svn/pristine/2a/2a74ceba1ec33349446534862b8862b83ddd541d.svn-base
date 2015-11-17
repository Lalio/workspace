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

/**
 *
 *
 * @todo 获取已登录用户ID，如果已登录用户则返回登录的member_id，否则返回false
 * @return string/false 已登录用户则返回登录的member_id，否则返回false
 *
 */
function get_ursname() {
    $cookie_name = 'pass_hex';
    $cookie_value = &$_COOKIE ['pass_hex'];

    //get user_id
    if ( $cookie_value ) {
        list ( $user_id ) = explode( "@", $_COOKIE ['member_id'] );
        $user_id = trim( strtolower( $user_id ) );
    }

    if ( strlen( $cookie_value ) == 32 && date( "Ymd" ) < 20090128 ) {
        $member_id = isset ( $_COOKIE ['member_id'] ) ? $_COOKIE ['member_id'] : null;
        $pass_hex = isset ( $_COOKIE ['pass_hex'] ) ? $_COOKIE ['pass_hex'] : null;
        $member_login = isset ( $_COOKIE ['member_login'] ) ? $_COOKIE ['member_login'] : null;

        if ( md5( substr( base64_encode( $member_id . "|" . $pass_hex ), 0, 20 ) ) == $member_login ) {
            return $user_id;
        } else {
            return false;
        }
    }

    if ( empty ( $cookie_value ) || strlen( $cookie_value ) != 40 )
        return false;

    $checksum = $random_key = $secret_key = $key_version = "";
    $tmp_key = $md5_key = "";
    $handle = fopen( "/dev/shm/secrectkey.56", "r" );
    $valid_secret_keys = array ();
    if ( $handle ) {
        while ( ! feof( $handle ) ) {
            $buffer = trim( fgets( $handle, 4096 ) );
            if ( empty ( $buffer ) || substr_compare( $buffer, "#", 0, 1 ) == 0 )
                continue;
            list ( $k, $v ) = explode( " ", $buffer, 2 );
            $valid_secret_keys ["$k"] = $v;
        }
        fclose( $handle );
    }
    //check key format & get checksum, tmp_key
    if ( sscanf( $cookie_value, "%39s%u", $tmp_key, $checksum ) != 2 )
        return false;

    //checksum
    if ( substr( sprintf( "%u", crc32( $tmp_key ) ), - 1 ) != $checksum )
        return false;

    //get $key_version, $random_key, $key
    list ( $key_version, $random_key, $md5_key ) = sscanf( $tmp_key, "%3s%4s%s" );

    //check version of secret key
    if ( ! array_key_exists( $key_version, $valid_secret_keys ) )
        return false;

    //check md5_key
    if ( $md5_key != md5( sprintf( "%s|%s|%s", $user_id, $valid_secret_keys [$key_version], $random_key ) ) )
        return false;

    return $user_id;
}
/**
 *
 *
 * @todo 根据用户名获取用户昵称
 * @param string  $userid
 */
function get_nickname( $userid ) {
    $rs = User::GetProfile( $userid );
    if ( $rs ) {
        return $rs['nickname'];
    }else {
        return false;
    }
}
/**
 *
 *
 * @todo 获取登录的56ID
 * @param string  $username
 */
function u_get_user_id( $username = null ) {
    $username = $username ? $username : get_ursname ();
    if ( $username ) {
        $username_no_suffix = explode( '@', $username );
        $username_no_suffix = $username_no_suffix [0];
        return $username_no_suffix;
    }
    return false;
}

function is_utf8($str) {
    
    $encode = mb_detect_encoding($str, array('ASCII','UTF-8','GB2312','GBK'),true);

    if ($encode == 'UTF-8') { 
        return true; 
    } else { 
        return false; 
    } 
}

/**
 *
 *
 * @todo 判断是否属于56用户名
 */
function is_user_id( $user_id ) {
    return preg_match( "/^[a-z][a-z0-9\.\-_]{2,25}$/i", $user_id );
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
        $link = 'http://www.xzbbm.cn';
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
 * @todo 写log
 * @param unknown $key     文件名
 * @param unknown $value   内容
 * @param unknown $percent 1-100 写log的概率，用于大量写log操作但又不需完全记录的情况。
 * @return boolean
 * @author Melon`` @ 2009
 */
function mLog( $key, $value, $percent = 100 ) {
    if ( $percent == 100 || rand( 0, 100 - $percent ) === 0 ) {
        ! is_dir( "./log" ) && @mkdir( "./log", 0777 );
        ! is_dir( "./log/{$key}" ) && @mkdir( "./log/{$key}", 0777 );

        $value = timeformat ( TIMESTAMP ) . '    |    ' . $value . "\n";

        $fp = @fopen( "./log/{$key}/" . date( "Y-m-d" ) . ".log", 'a+' );
        $w = @fwrite( $fp, $value, strlen( $value ) );
        @fclose( $fp );
        return $w;
    }
    return FALSE;
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
    echo '<script type="text/javascript">alert("'.$text.'");</script>';
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
?>
