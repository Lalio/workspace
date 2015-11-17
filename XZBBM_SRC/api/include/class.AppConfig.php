<?php
if (! defined ( 'IN_SYS' ))
{
    header ( "HTTP/1.1 404 Not Found" );
    die ();
}

/**
 *
 * @todo 首屏图片类
 * @author bo.wang3
 * @version 2013-03-01 14:29
 */
class AppConfig
{
    // 第一个正式版本 1.0.1 发布时间2013/09/25
    // 第四个正式版本 1.1.1 发布时间2013/11/6
    // 第五个正式版本 1.2.6 发布时间2013/12/20
    
    /**
     *
     * @todo 服务器送达APP端配置
     * @author bo.wang3
     * @version 2013-03-01
     */
    static function Main($cmd_arr)
    {
        
        // 版本升级配置
        // $isUpdateByVersion = TRUE; //是否按版本号配置升级
        
        // if($isUpdateByVersion){
        // $version_arr = array('1.0.1','1.0.2','1.1.1','1.2.5'); //需要升级的版本号
        // if(in_array($cmd_arr['versioncode'], $version_arr)){
        // if(false == in_array($cmd_arr['versioncode'], array('1.2.6','1.3.7'))){
        // if(false == in_array($cmd_arr['versioncode'], array('1.4.9'))){
        $rs ['isupdate'] = "true";
        $rs ['updatetips'] = "久违了，我们的新版本上线了，赶紧更新！";
        // $rs['apk_url'] = "http://cdn.xzbbm.cn/apps/xzbbm_1.4.9.apk";
        $ver = "1.0.3";
        $rs ['apk_url'] = "http://cdn.xzbbm.cn/apps/xzbbm_$ver.apk";
//         $rs ['apk_url'] = "https://app.xzbbm.cn";
        // }else{
        // $rs['isupdate'] = "false";
        // }
        // }
        // 分类升级配置
        // if(!isset($cmd_arr['sort_md5']) && $cmd_arr['sort_md5'] != md5(json_encode(Core::$configs['classes']))){
        $rs ['classes'] = Core::$configs ['classes'];
        // }
        
        // 首屏图片推送配置
        $firstsrceen = array (
                'http://cdn.xzbbm.cn/images/bootsrceen/2013113001.jpg',
                'http://cdn.xzbbm.cn/images/bootsrceen/2013113002.jpg',
                'http://cdn.xzbbm.cn/images/bootsrceen/2013113003.jpg',
                'http://cdn.xzbbm.cn/images/bootsrceen/2013113004.jpg',
                'http://cdn.xzbbm.cn/images/bootsrceen/2013113005.jpg' 
        );
        shuffle ( $firstsrceen );
        switch ($cmd_arr ['schoolcode'])
        { // 根据学校编码返回首屏图片地址
            case '1' :
                $rs ['img_url'] = $firstsrceen [0];
                break;
            default :
                $rs ['img_url'] = $firstsrceen [0];
        }
        $rs ['showtime'] = 4000; // 首屏图片展示时间
                                
        // 第一次摇一摇缓存
        $rs ['shake_catch_url'] = 'http://cdn.xzbbm.cn/images/bootsrceen/2013113005.jpg';
        
        return $rs;
    }
    static function LastedVersion()
    {
        return json_encode ( array (
                'ver' => '1.3.7',
                'url' => 'http://cdn.xzbbm.cn/apps/xzbbm_1.3.7.apk' 
        ) );
    }
}
