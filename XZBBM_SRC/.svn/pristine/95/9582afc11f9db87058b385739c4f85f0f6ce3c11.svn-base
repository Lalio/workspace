<?php
/**
 *
 *
 * @name 最新正整理，视频相关公共函数
 * @author Melon`` @ 1010
 */
class G {

    /**
     *
     *
     * @todo 通过视频id获取视频信息
     * @param unknown $ids 视频id。如：NTAxODM0NTU,MTAxOeM0NTU,54055346,40265656
     * @param unknown $fli 需要返回的字段。如：id,user_id,totaltime。默认返回全部
     * @param unknown $fle 不需返回的字段。
     * @param unknown $dy  清缓存
     * @example http://kankan.56.com/?action=AppCpmCpi&do=center&vid=NTAxODM0NTU
     * @author Melon`` @ 1010
     */
    function GetIdsVideo( $ids, $fli = '', $fle = '', $dy = '' ) {
        $ids = is_array( $ids ) ? implode( ',', $ids ) : $ids;

        $data = Http::Get ( 'info.v.56.com', "?ids={$ids}&fli={$fli}&fle={$fle}&dy={$dy}" );

        if ( $data ) {
            $data = json_decode( $data, TRUE );
            return is_array( $data ) ? $data : array ();
        } else {
            return array ();
        }
    }

    /**
     *
     *
     * @todo 通过相册视频id获取相册视频视频信息
     * @param unknown $ids 相册视频视频id。如：NTAxODM0NTU,MTAxOeM0NTU,54055346,40265656
     * @param unknown $fli 需要返回的字段。如：id,user_id,totaltime。默认返回全部
     * @param unknown $fle 不需返回的字段。
     * @param unknown $dy  清缓存
     * @author Seamaid`` @ 2011
     */
    function GetIdsPhotoVideo( $ids, $fli = '', $fle = '', $dy = '' ) {
        $ids = is_array( $ids ) ? implode( ',', $ids ) : $ids;
        $data = Http::Get ( 'p.56.com', "/API/vInfo.php?ids={$ids}&fli={$fli}&fle={$fle}&dy={$dy}" );
        if ( $data ) {
            $data = json_decode( $data, TRUE );
            return is_array( $data ) ? $data : array ();
        } else {
            return array ();
        }
    }

    /**
     *
     *
     * @todo 通过录制id获取相册视频视频信息
     * @param unknown $ids 录制视频视频id。如：NTAxODM0NTU,MTAxOeM0NTU,54055346,40265656
     * @param unknown $fli 需要返回的字段。如：id,user_id,totaltime。默认返回全部
     * @param unknown $fle 不需返回的字段。
     * @param unknown $dy  清缓存
     * @author Seamaid`` @ 2011
     */
    function GetIdsLuzhiVideo( $ids, $fli = '', $fle = '', $dy = '' ) {
        $ids = is_array( $ids ) ? implode( ',', $ids ) : $ids;
        $data = Http::Get ( 'info.v.56.com', "/?do=Luzhi&ids={$ids}&fli={$fli}&fle={$fle}&dy={$dy}" );
        if ( $data ) {
            $data = json_decode( $data, TRUE );
            return is_array( $data ) ? $data : array ();
        } else {
            return array ();
        }
    }

    /**
     *
     *
     * @todo:获取视频图片地址
     * @param unknown $rsArray 单个视频字段数组
     * @param unknown $proxy   图片是否使用代理，false取源站
     * @param string  URL
     * @author: Melon`` @ 2010
     *
     */
    static public function FlvImg( $rsArray, $proxy = true ) {
        if ( $rsArray ['exercise'] == 'p' ) {
            $imageURL = $rsArray ['URL_host'];
        } else if ( $rsArray ['exercise'] == 'y' ) {
                $imageURL = "img/mp3.gif";
            } else {
            if ( substr( $rsArray ['URL_host'], 0, 7 ) == 'http://' ) {
                $imageURL = $rsArray ['URL_host'];
            } else {
                //    if (in_array($rsArray['img_host'],$scfg['config']['img_cached_host'] )) $rsArray['img_host'] = $rsArray['img_host'];
                if ( $proxy ) {
                    preg_match( "/v(\d+)\.56\.com/i", $rsArray ['img_host'], $pattern );
                    if ( ( int ) $pattern [1] > 16 ) {
                        $rsArray ['img_host'] = 'img.' . $rsArray ['img_host'];
                    }
                }
                $imageURL = 'http://' . $rsArray ['img_host'] . '/images/' . $rsArray ['URL_pURL'] . "/" . $rsArray ['URL_sURL'] . "/" . $rsArray ['user_id'] . "i56olo56i56.com_" . $rsArray ['URL_URLid'] . ".jpg";

            }
        }
        return $imageURL;
    }

    /**
     *
     *
     * @todo: 获取flashvars的参数，用于把视频参数拼凑传给flash播放视频
     * @param unknown $rsArray 单个视频字段数组
     * @param string  URL
     * @author: Melon`` @ 2010
     *
     */
    static public function GetVars( $rsArray ) {
        return "img_host=" . $rsArray ['img_host'] . "&host=" . $rsArray ['URL_host'] . "&pURL=" . $rsArray ['URL_pURL'] . "&sURL=" . $rsArray ['URL_sURL'] . "&user=" . $rsArray ['user_id'] . "&URLid=" . $rsArray ['URL_URLid'] . "&totaltimes=" . $rsArray ['totaltime'] . ( ( strlen( $rsArray ['effectID'] ) > 3 ) ? ( $rsArray ['effectID'] ) : "&effectID=" . $rsArray ['effectID'] ) . "&flvid=" . $rsArray ['id'];
    }

    /**
     *
     *
     * @todo:  算用户目录
     * @author: Melon`` @ 2010
     *
     */
    static public function UserDir( $user_id, $c = 30 ) {
        $a1 = 0;
        $a2 = 0;
        for ( $i = 0; $i < strlen( $user_id ); $i ++ ) {
            $a1 += ( ord( $user_id {$i} ) ) * $i; //a charCodeAt(a)
            $a2 += ( ord( $user_id {$i} ) ) * ( $i * 2 + 1 );
        }
        $a1 %= $c; //第一级路经
        $a2 %= $c; //第二级路经
        return array ( 'URL_pURL' => $a1, 'URL_sURL' => $a2, 'p' => $a1, 's' => $a2 );
    }

    /**
     *
     *
     * @todo:  得到flvURL，视频播放页地址
     * @param :       $id      FLVID
     * @param :       $product 站点还是space
     * @param string  URL
     * @author: Melon`` @ 2010
     *
     */
    static public function FlvUrl( $id, $pct = 1, $site = true ) {
        $host = self::Phost ( $id, $pct );
        return $site ? $host . "/v_" . self::FlvEnId ( $id ) . ".html" : $host . "/spaceDisplay.php?id=" . self::FlvEnId ( $id );
    }

    /**
     *
     *
     * @todo: 对视频ID base64encode
     * @param unknown $id FLVID
     * @param string  BASE64
     * @author: Melon`` @ 2010
     *
     */
    static public function FlvEnId( $id ) {
        if ( is_numeric( $id ) ) {
            return str_replace( '=', '', base64_encode( $id ) );
        } else {
            return $id;
        }
    }

    /**
     *
     *
     * @todo: 对视频ID base64decode
     * @param string  BASE64
     * @param unknown $id FLVID
     * @author: Melon`` @ 2010
     *
     */
    static public function FlvDeId( $id ) {
        if ( is_numeric( $id ) ) {
            return $id;
        } else {
            return ( int ) base64_decode( $id );
        }
    }

    /*
     * 功能:从url得到ID
     */
    static public function getUrlId( $url ) {
        if ( ! strstr( $url, 'http' ) ) {
            $id = self::flvDeId ( $url );
        } else {
            if ( strstr( $url, 'v=' ) ) {
                $id = explode( 'v=', trim( $url ) );
                $id = str_replace( '.html', '', $id [1] );
                $id = self::flvDeId ( $id );
            } elseif ( strstr( $url, 'v_' ) ) {
                $id = explode( 'v_', trim( $url ) );
                $id = str_replace( '.html', '', $id [1] );
                $id = self::flvDeId ( $id );
            } elseif ( strstr( $url, 'vid-' ) ) {
                $id = explode( 'vid-', trim( $url ) );
                $id = str_replace( '.html', '', $id [1] );
                $id = self::flvDeId ( $id );
            } elseif ( strstr( $url, '.html' ) ) {
                $id = explode( '/id', trim( $url ) );
                $id = str_replace( '.html', '', $id [1] );
            } else {
                $id = explode( 'id=', trim( $url ) );
                $id = explode( '&', $id [1] );
                $id = $id [0];
            }
        }
        return $id;
    }

    /**
     *
     *
     * @todo:  得到主机   $pct 产品id 没有产品ID时取用户
     * @author: Melon`` @ 2010
     *
     */
    static public function Phost( &$str, $pct = false ) {
        if ( $pct === false ) {
            $len = strlen( $str );
            $rs = 0;
            for ( $i = 0; $i < $len; $i ++ )
                $rs += ord( $str [$i] );
            $host = "http://www.56.com/w" . ( $rs % 88 + 11 );
        } else {
            $id = self::FlvDeId ( $str );
            $pct = self::Pct ( $pct );
            $host = "http://www.56.com/$pct" . ( $id % 88 + 11 );
        }
        return $host;
    }

    /**
     *
     *
     * @todo:  产品
     * @param unknown $mode = id　返回ID string  $mode = name　返回name  string $mode = id,name||其它　返回id和name　Array
     * @author: Melon`` @ 2010
     *
     */
    static public function Pct( $pct, $mode = 'id' ) {
        $pctArray = array ( 1 => 'u', 2 => 'l', 3 => 'p' );
        $pctName = array ( 'u' => '上传', 'l' => '录制', 'p' => '动感相册' );
        if ( is_numeric( $pct ) )
            $pct = $pctArray [$pct];

        if ( $mode == 'id' ) {
            return $pct;
        } else if ( $mode == 'name' ) {
                return $pctName [$pct];
            } else {
            return array ( 'id' => $pct, 'name' => $pctName [$pct] );
        }
    }

    /**
     *
     *
     * @todo:  图片代理
     * @example
     * e.g: v21.56.com To img.v21.56.com
     * 兼容  http://v21.56.com To http://v21.56.com
     * http://v21.56.com/images/0/21/jingkii56olo56i56.com_118733351699.jpg To http://img.v21.56.com/images/0/21/jingkii56olo56i56.com_118733351699.jpg
     * 兼容 http://l.p302.56.com/photo2video/upImg/d1/15/68rebill57919.jpg    Add By Rebill 2010-06-01
     *
     * @author: Melon`` @ 2010
     *
     */
    static public function ImgProxy( $vhost ) {
        if ( strstr( $vhost, 'img.v' ) || strstr( $vhost, 'img.p' ) ) {
            return $vhost;
        }
        //兼容新存储格式(l.p302.56.com)
        preg_match( "/\w\.p(\d+)\.56\.com[\w\/\.]*/i", $vhost, $pattern );
        if ( ( int ) $pattern [1] > 300 ) {
            if ( strstr( $vhost, 'http://' . $pattern [0] ) ) {
                $vhost = str_ireplace( 'http://' . $pattern [0], 'http://img.' . $pattern [0], $vhost );
            } else {
                $vhost = 'img.' . $pattern [0] . substr( $pattern [0], 1 );
            }
            return $vhost;
        }
        preg_match( "/(v|p)(\d+)\.56\.com[\w\/\.]*/i", $vhost, $pattern );
        if ( ( int ) $pattern [2] > 16 || $pattern [1] == 'p' ) {
            if ( strstr( $vhost, 'http://' . $pattern [1] ) ) {
                $vhost = str_ireplace( 'http://' . $pattern [1], 'http://img.' . $pattern [1], $vhost );
            } else {
                $vhost = 'img.' . $pattern [1] . substr( $pattern [0], 1 );
            }
        }
        return $vhost;
    }

    /**
     *
     *
     * @todo 获取专辑播放地址
     * @author Melon`` @ 2010
     */
    static function GetAlbumPlayUrl( $aid, $vid, $o = 0 ) {
        return "http://www.56.com/w" . ( $aid % 89 + 11 ) . "/" . 'play_album-aid-' . $aid . '_vid-' . ( is_numeric( $vid ) ? self::FlvEnId ( $vid ) : $vid ) . ( $o ? '_o-' . $o : '' ) . '.html';
    }

    /**
     *
     *
     * @todo 专辑信息页地址
     * @author Melon`` @ 2010
     */
    static function GetAlbumInfoUrl( $aid ) {
        return "http://www.56.com/w" . ( $aid % 89 + 11 ) . "/" . 'album-aid-' . $aid . '.html';
    }

    /*
     * 转码
     */
    static public function mb( &$string, $to_encoding = "UTF-8", $from = "GB2312" ) {
        return mb_convert_encoding( $string, $to_encoding, $from );
    }

    /*
     * @todo:获得活动中使用的swf地址
       @vid:视频id
       $pct=类型1、上传视频 2、相册视频 3 录制视频
       $play n 不自动播放 y 自动播放
    */
    static public function flvHdSwf( $vid, $pct = 1, $play = "n" ) {
        $swf = '';
        if ( ! is_numeric( $vid ) ) {
            $vid = self::GetUrlId ( $vid );
        }
        switch ( $pct ) {
        case 1 : //上传视频
            if ( $play == 'n' ) {
                $swf = sprintf( "http://player.56.com/sp_%s.swf", self::FlvEnId ( $vid ) );
            } else {
                $swf = sprintf( "http://player.56.com/sp2_%s.swf", self::FlvEnId ( $vid ) );
            }
            break;
        case 2 : //相册视频
            if ( $play == 'n' ) {
                $swf = sprintf( "http://player.56.com/deux_%s.swf", $vid );
            } else {
                $swf = sprintf( "http://player.56.com/p2_%s.swf", $vid );
            }
            break;
        case 3 : // 录制视频
            if ( $play == 'n' ) {
                $swf = sprintf( "http://www.56.com/flashApp/v_player_simple.11.04.28.b.swf?vid=%s&video_type=rec&", self::FlvEnId ( $vid ) );
            } else {
                $swf = sprintf( "http://www.56.com/flashApp/v_player_simple.11.04.28.b.swf?vid=%s&video_type=rec&auto_start=on&", self::FlvEnId ( $vid ) );
            }
            break;
        default : //默认就按上传视频吧
            if ( ! is_numeric( $vid ) ) {
                $vid = self::GetUrlId ( $vid );
                if ( $play == 'n' ) {
                    $swf = sprintf( "http://player.56.com/sp_%s.swf", self::FlvEnId ( $vid ) );
                } else {
                    $swf = sprintf( "http://player.56.com/sp2_%s.swf", self::FlvEnId ( $vid ) );
                }
            }
        }
    }

    /**
     *
     *
     * @todo 获取播放数
     * @param unknown $id
     * @author bo.wang3
     */
    function GetViews( $vid ) {
        $rs = g::GetIdsVideo( $vid, 'times' );
        return $rs[$vid]['times'];
    }

    /**
     *
     *
     * @todo 获取评论数
     * @param unknown $id
     * @author bo.wang3
     */
    function GetComments( $id ) {
        $rs = g::GetIdsVideo( $id, 'comment' );
        return $rs[$id]['comment'];
    }

    /**
     *
     *
     * @todo 获取顶数
     * @param unknown $id
     * @author bo.wang3
     */
    function GetDings( $id ) {
        $rs = g::GetIdsVideo( $id, 'ding' );
        return $rs[$id]['ding'];
    }

    /**
     *
     *
     * @todo 获取踩数
     * @param unknown $id
     * @author bo.wang3
     */
    function GetCais( $id ) {
        $rs = g::GetIdsVideo( $id, 'cai' );
        return $rs[$id]['cai'];
    }

    /**
     *
     *
     * @author bo.wang3
     * @todo 分段输出整数，用于输出播放数和评论数
     * @version 2012-11-14
     */
    function echo_views( $int ) {
        if ( empty( $int ) ) { //播放、评论数出现错误时显示0
            echo 0;
            rerurn;
        }
        $rs = array();
        while ( $int ) {
            if ( $int<1000 ) {
                $rs[] = fmod( $int, 1000 );
            }else {
                $rs[] = substr( strval( fmod( $int, 1000 )+1000 ), 1, 3 ); //求余数并自动补0
            }
            $int = floor( $int / 1000 );
        }
        echo implode( ',', array_reverse( $rs ) );
    }

    /**
     *
     *
     * @author bo.wang3
     * @todo 格式化输出视频长度
     * @version 2012-11-14
     */
    function echo_totaltime( $int ) {
        if ( empty( $int ) ) { //数据读取错误时显示0
            echo 0;
            rerurn;
        }else {
            $min = floor( $int/60 )>9?floor( $int/60 ):'0'.floor( $int/60 );
            $sec = ( $int%60 )>9?( $int%60 ):'0'.( $int%60 );
            echo $min.':'.$sec;
        }
    }
}
