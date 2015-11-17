<?php
if (! defined ( 'IN_SYS' ))
{
    header ( "HTTP/1.1 404 Not Found" );
    die ();
}
class Xzbbm
{
    public function __construct()
    {
        Core::InitDataCache (); // 初始化数据缓类
        Core::InitDb (); // 初始化数据类
        
        $this->_db = Core::$db ['online'];
        $this->_dc = Core::$dc;
        $this->_oss = new AliOss (); // 初始化云存储
        
        $this->totalUser = $this->GetData ( 'SELECT count(userid) as user_sum FROM pd_users' );
        
        $this->totalUser = intformat ( $this->totalUser [0] ['user_sum'] );
        
        $this->totalFile = $this->GetData ( 'SELECT count(file_id) as file_sum FROM pd_files' );
        $this->totalFile = intformat ( sprintf ( "%.0f", $this->totalFile [0] ['file_sum'] ) );
        
        // $this->totalView = $this->GetData('SELECT sum(file_views) as sum_v FROM pd_files');
        // $this->totalView = sprintf("%.0f", $this->totalView[0]['sum_v']);
        
        $this->totalDown = $this->GetData ( 'SELECT sum(file_downs) as sum_d FROM pd_files' );
        $this->totalDown = intformat ( sprintf ( "%.0f", $this->totalDown [0] ['sum_d'] ) );
        
        $this->userinfo = $this->GetLoginedUserInfo ();
        
        $tmp_ucode = 0;
        if (isset ( $_SESSION ['ucode'] ))
        {
            $tmp_ucode = $_SESSION ['ucode'];
        }
        else if (isset ( $this->userinfo ['ucode'] ))
        {
            $tmp_ucode = $this->userinfo ['ucode'];
        }
        $this->ucode = $tmp_ucode;
        
        $y = date ( 'Y', TIMESTAMP );
        $m = date ( 'm', TIMESTAMP );
        $d = date ( 'd', TIMESTAMP );
        
        $this->file_store_path = "$y/$m/$d";
        
        if ($_GET ['debug'] == 'on')
        {
            $this->json_str = $_REQUEST ["msg"];
            $this->msg = json_decode ( $_REQUEST ["msg"], true );
        }
        else
        {
            $this->json_str = file_get_contents ( "php://input" );
            $this->msg = json_decode ( $this->json_str, true );
        }
        $this->xztoken = $this->msg ["xztoken"];
        if (empty ( $this->xztoken ))
        {
            $this->xztoken = getallheaders ()["token"];
        }
        $this->say ( $this->json_str );
    }
    
    /**
     *
     * @author why
     */
    public function SaveFile()
    {
        $file = $_FILES ['Filedata'];
        $file_info = explode ( '.', $file ["name"] );
        $file_name = $file_info [0];
        $file_extension = $file_info [(count ( $file_info ) - 1)];
        $file_path = $file ['tmp_name'];
        
        // 判断文件类型
        if (true !== file_type ( $file_path ))
        {
            $this->Error ( 'Error File Type!|' . file_type ( $file_path ) );
        }
        
        if (! file_exists ( $file_path ))
        {
            $this->Error ( $file_path . " - 文件不存在，请检查原路径是否正确。" );
        }
        else
        {
            // public int sequence; // 第几张图片
            // public String file_path; // 本地的文件位置
            // public int file_type; // 接口复用，type＝0是图片，type＝1是吆喝语音,type=2是微软系列文档
            // $file_md5 = md5_file ( $file_path ); //TODO 资源重复问题
            $file_index = $this->msg ["file_index"];
            $sequence = $this->msg ["sequence"] - 1;
            $file_type = $this->msg ["file_type"];
            $file_save_path = "";
            if ($file_type == 0) // type＝0是图片
            {
                $file_save_path = "$file_index-$sequence.png";
            }
            else if ($file_type == 1) // type＝1是吆喝语音
            {
                $file_save_path = "$file_index.amr";
            }
            else // type=2是微软系列文档
            {
                $file_save_path = "$file_index.$file_extension";
            }
            
            $this->say ( "upload file to oss file_save_path = $file_save_path" );
            upload_by_file ( $this->_oss, $file_save_path, $file_path );
            $this->ok ();
        }
    }
    /**
     * 通用给客户端返回Error的接口
     *
     * @param
     *            $str
     * @author why
     */
    public function Error($str)
    {
        $ret = [ 
                "isOk" => false,
                "error" => $str 
        ];
        $this->Ret ( $ret );
        exit ();
    }
    /**
     * 通用给客户端返回json字符串的接口
     *
     * @param $ret_msg 数组            
     * @author why
     */
    public function Ret($ret_msg)
    {
        if ($_GET ['show'] == 'on')
        {
            echo print_r ( $ret_msg );
            exit ();
        }
        $ret_msg = (json_encode ( $ret_msg ));
        $this->say ( $ret_msg );
        
        $callback = $_REQUEST ["callback"];
        if (empty ( $callback ))
        {
            header ( 'Content-Type: application/json; charset=utf-8' );
            echo $ret_msg;
        }
        else
        {
            header ( "Content-Type: text/javascript" );
            echo $callback . "(" . $ret_msg . ")";
        }
    }
    /**
     * 通用log接口
     *
     * @param
     *            $str
     * @author why
     */
    public function say($str)
    {
        file_put_contents ( "/data/backend_service/log/pay_api.log", "{$this->file_store_path} $str \n", FILE_APPEND );
    }
    /**
     * 通用给客户端返回操作成功的接口
     *
     * @author why
     */
    public function ok()
    {
        $this->Ret ( [ 
                'rcode' => 0 
        ] );
    }
    /**
     * 获得用户信息,如果方法参数为空，则取到当前用户
     */
    public function GetUser($token = '')
    {
        if ($token != '')
        {
            $token = $this->xztoken;
        }
        return $this->_db->rsArray ( "SELECT * FROM pd_users WHERE phone = '$token' or email = '$token' or token = '$token' LIMIT 0,1" );
        // 登陆/注册完成后获得token，具有时效性，用于验证用户
    }
    
    /**
     *
     * @todo 获取数据 fileCache->Database
     * @author bo.wang3
     * @param $sql SQL查询命令            
     * @version 2012-11-01
     */
    public function GetData($sql)
    {
        $data = array ();
        $memKey = md5 ( $sql );
        
        $data = $this->_dc->getData ( $memKey, 43200 ); // 读取缓存数据;
        if (false === $data || (isset ( $_GET ['i'] ) && $_GET ['i'] == 'c'))
        {
            $data = $this->_db->dataArray ( $sql );
            $this->_dc->setData ( $memKey, $data ); // 设置缓存数据
        }
        
        return $data;
    }
    
    /**
     *
     * @todo 获取接口数据
     * @author bo.wang3
     * @param $sql SQL查询命令            
     * @version 2012-11-01
     */
    public function GetInterFacesData($cmd, $content)
    {
        $tar = "http://112.124.50.239:8867/?action=AndroidProxy&debug=on&json_msg=";
        $json_msg = array (
                "header" => array (
                        "cmd" => $cmd,
                        "datatype" => "2",
                        "version" => "1" 
                ),
                "content" => $content 
        );
        $json_msg = json_encode ( $json_msg );
        $url = $tar . $json_msg;
        
        $data = array ();
        // $memKey = md5($url);
        // $data = $this->_dc->getData($memKey,43200); //读取缓存数据;
        
        // if(false === $data || (isset($_GET['i']) && $_GET['i']=='c')){
        $data = json_decode ( file_get_contents ( $url ), true );
        $data = $data ['content'];
        // $this->_dc->setData($memKey,$data['content']); //设置缓存数据
        // }
        
        return $data;
    }
    
    /**
     *
     * @todo 获取资料SHA1双向密钥URL统一接口 支持原图和毛玻璃效果 模糊度支持 300 500 700 900 1100 五个量级
     * @param
     *            file_realname资料的md5编码 page截图第几页 degree模糊度 timeoutURL过期时间，默认不用改
     * @return http://img.xzbbm.cn/00008d7a90e656cd19e3a949c638fd6a-1.png?Expires=1427888815&OSSAccessKeyId=mpFFmaUpbVb62R1F&Signature=z6stMoZHcHx2vgHa%2FieAxdv7bF4%3D
     * @author bo.wang
     * @version 2015-04-01
     */
    function GetUrl($cfg = array('file_real_name' => '','page' => '','degree' => '', 'timeout' => 10, 'file_extension' => 'png'))
    {
        if ($cfg ['file_extension'] == 'png')
        {
            $hostname = 'img.xzbbm.cn';
            $object = $cfg ['page'] == '' ? "{$cfg['file_real_name']}.png" : "{$cfg['file_real_name']}-{$cfg['page']}.png";
            $object .= $cfg ['degree'] == '' ? '' : "@!forested{$cfg['degree']}";
        }
        else
        {
            $hostname = 'oss.xzbbm.cn';
            $object = "{$cfg['file_real_name']}.{$cfg['file_extension']}";
        }
        
        return get_sign_url ( $this->_oss, $object, $cfg ['timeout'], $hostname );
    }
    
    /**
     *
     * @todo 生成PDF文件
     * @author bo.wang
     * @version 2013-09-18 14:29
     */
    public function GeneratePdf($file_key, $pdf_key)
    {
        
        // 从云端获取原资料
        $tmp_path = get_object ( $this->_oss, $file_key );
        $pdf_tmp_path = str_replace ( $file_key, $pdf_key, $tmp_path );
        
        // 对Office类文档生成PDF.
        system ( "PATH=/usr/local/mysql/bin:/usr/local/mysql/bin:/usr/local/mysql/bin:/usr/local/mysql/bin:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin /usr/bin/python /usr/bin/unoconv -f pdf $tmp_path > /dev/null &" );
        
        if (file_exists ( $pdf_tmp_path ))
        {
            // 把生成的pdf推向云端
            upload_by_file ( $this->_oss, $pdf_key, $pdf_tmp_path );
            $this->_db->update ( 'pd_files', array (
                    'has_pdf' => 1 
            ), "file_real_name = '" . str_replace ( '.pdf', '', $pdf_key ) . "'" );
        }
    }
    
    /**
     *
     * @todo 生成SWF文件
     * @author bo.wang
     * @version 2013-09-18 14:29
     */
    public function GenerateSwf($pdf_key, $swf_key)
    {
        
        // 从云端获取原资料
        $tmp_path = get_object ( $this->_oss, $pdf_key );
        $swf_tmp_path = str_replace ( $pdf_key, $swf_key, $tmp_path );
        
        // PDF转化为SWF.
        system ( "PATH=/usr/local/mysql/bin:/usr/local/mysql/bin:/usr/local/mysql/bin:/usr/local/mysql/bin:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin pdf2swf $tmp_path -o $swf_tmp_path -T 9 -f -t -j 100 -p 1-15 -s languagedir=/usr/local/share/xpdf/xpdf-chinese-simplified > /dev/null &" ); // 完整版
        
        if (file_exists ( $swf_tmp_path ))
        {
            // 把生成的swf推向云端
            upload_by_file ( $this->_oss, $swf_key, $swf_tmp_path );
            $this->_db->update ( 'pd_files', array (
                    'has_swf' => 1 
            ), "file_real_name = '" . str_replace ( '.swf', '', $swf_key ) . "'" );
        }
    }
    
    /**
     *
     * @todo 生成高精度png 八张
     * @author bo.wang
     * @version 2013-09-18 14:29
     */
    public function GeneratePng($pdf_key)
    {
        
        // 从云端获取原资料
        $tmp_path = get_object ( $this->_oss, $pdf_key );
        $file_real_name = str_replace ( '.pdf', '', $pdf_key );
        $png_tmp_path = str_replace ( 'pdf', 'png', $tmp_path );
        
        // PDF转化为PNG.
        exec ( "convert -resize 100%x100% -density 150 {$tmp_path}[0-40] $png_tmp_path > /dev/null &" ); // 生成截图
                                                                                                         // 把生成的png推向云端
        for($i = 0; $i < 10; $i ++)
        {
            if (file_exists ( "/dev/shm/osstmp/$file_real_name-$i.png" ))
            {
                upload_by_file ( $this->_oss, "$file_real_name-$i.png", "/dev/shm/osstmp/$file_real_name-$i.png" );
            }
            else
            {
                break;
            }
        }
        
        $this->_db->update ( 'pd_files', array (
                'has_png' => $i 
        ), "file_real_name = '$file_real_name'" );
    }
    
    /**
     *
     * @todo 生成缩略图png 3张
     * @author bo.wang
     * @version 2013-09-18 14:29
     */
    public function GenerateThumb($pdf_key)
    {
        
        // 从云端获取原资料
        $tmp_path = get_object ( $this->_oss, $pdf_key );
        $file_real_name = str_replace ( '.pdf', '', $pdf_key );
        $thumb_tmp_path = str_replace ( '.pdf', '', $tmp_path ) . "-s.png";
        
        // PDF转化为Thumb.
        exec ( "convert -resize 100%x100% -density 50 {$tmp_path}[0-2] $thumb_tmp_path > /dev/null &" ); // 生成截图
                                                                                                         // 把生成的png推向云端
        for($i = 0; $i < 3; $i ++)
        {
            if (file_exists ( "/dev/shm/osstmp/$file_real_name-s-$i.png" ))
            {
                upload_by_file ( $this->_oss, "$file_real_name-s-$i.png", "/dev/shm/osstmp/$file_real_name-s-$i.png" );
            }
            else
            {
                break;
            }
        }
        
        $this->_db->update ( 'pd_files', array (
                'has_thumb' => $i 
        ), "file_real_name = '$file_real_name'" );
    }
    
    /**
     *
     * @todo 获取学校数据
     * @author bo.wang
     * @version 2013-09-18 14:29
     */
    public function GetUniversityInfo($uid = 1000, $uname, $need = 'all')
    {
        if ($uid == 0)
        {
            
            $rs = array (
                    'university_id' => 0,
                    'name' => '学长帮帮忙',
                    'province' => '',
                    'sicon_id' => 'xz',
                    'college' => '' 
            );
        }
        else
        {
            
            $uinfo = $this->GetData ( "SELECT * FROM geo_universities,geo_provinces
									 WHERE (geo_universities.university_id = $uid or geo_universities.name LIKE '$uname')
									 AND geo_universities.province = geo_provinces.province_id ORDER BY geo_universities.weight DESC,geo_universities.total_files DESC" );
            
            $cinfo = $this->GetData ( "SELECT * FROM geo_colleges WHERE university_id = {$uinfo[0]['university_id']} ORDER BY total DESC" );
            
            if (empty ( $cinfo ))
            {
                $cinfo [0] = array (
                        'college_id' => 0,
                        'college' => '校本部',
                        'university_id' => $uid 
                );
            }
            
            $rs = array (
                    'university_id' => $uinfo [0] ['university_id'],
                    'name' => $uinfo [0] ['name'],
                    'province' => $uinfo [0] ['province'],
                    'sicon_id' => $uinfo [0] ['sicon_id'],
                    'college' => $cinfo 
            );
        }
        
        if ($need == 'all')
        {
            return $rs;
        }
        else
        {
            return $rs [$need];
        }
    }
    
    /**
     *
     * @todo 获取省份学校数据
     * @author bo.wang
     * @version 2013-09-18 14:29
     */
    public function GetProvinceInfo($need = 'all', $pid = 0, $uname)
    {
        if ($pid != 0)
        {
            $pinfo = $this->GetData ( "SELECT * FROM geo_universities,geo_provinces
								 WHERE (geo_provinces.province_id = $pid)
								 AND geo_universities.province = geo_provinces.province_id
								 ORDER BY geo_universities.weight DESC,geo_universities.total_files DESC" );
        }
        else if (! empty ( $uname ))
        {
            $pinfo = $this->GetData ( "SELECT * FROM geo_universities,geo_provinces
                    WHERE (geo_universities.name LIKE '%{$uname}%')
                    AND geo_universities.province = geo_provinces.province_id
                    ORDER BY geo_universities.weight DESC,geo_universities.total_files DESC" );
        }
        foreach ( $pinfo as $data )
        {
            $universities [] = array (
                    'university_id' => $data ['university_id'],
                    'name' => $data ['name'],
                    'sicon_id' => $data ['sicon_id'] 
            );
        }
        
        $rs = array (
                'name' => $pinfo [0] ['province'],
                'data' => $universities 
        );
        
        if ($need == 'all')
        {
            return $rs;
        }
        else
        {
            return $rs [$need];
        }
    }
    
    /**
     *
     * @todo 根据编号获得学校名
     * @author bo.wang
     * @version 2013-09-18 14:29
     */
    public function GetNameByUcode($ucode)
    {
        $info = $this->GetData ( "SELECT name FROM geo_universities WHERE university_id = $ucode LIMIT 0,1" );
        return $info [0] ['name'];
    }
    
    /**
     *
     * @todo 根据学校编号获得省份信息
     * @author bo.wang
     * @version 2013-09-18 14:29
     */
    public function GetProByUcode($ucode, $need = 'province')
    {
        $info = $this->GetData ( "SELECT geo_provinces.province,geo_provinces.province_id FROM geo_provinces,geo_universities WHERE geo_universities.university_id = $ucode and geo_universities.province = geo_provinces.province_id LIMIT 0,1" );
        return $info [0] [$need];
    }
    
    /**
     *
     * @todo 根据编号获得学院名
     * @author bo.wang
     * @version 2013-09-18 14:29
     */
    public function GetNameByCcode($ccode)
    {
        $info = $this->GetData ( "SELECT college FROM geo_colleges WHERE college_id = $ccode LIMIT 0,1" );
        return $info [0] ['college'];
    }
    
    /**
     *
     * @todo 获取省份数据
     * @author bo.wang
     * @version 2013-09-18 14:29
     */
    public function GetProvinces()
    {
        $pinfo = $this->GetData ( "SELECT * FROM geo_provinces" );
        return $pinfo;
    }
    
    /**
     *
     * @todo 显示校徽
     * @author bo.wang
     * @version 2013-09-18 14:29
     */
    public function ShowUicon($ucode)
    {
        $sicon_id = $this->GetUniversityInfo ( $ucode, '', 'sicon_id' );
        
        echo '<img onerror="this.parentNode.removeChild(this)" height="25px" style="vertical-align:middle;" src="http://cdn.xzbbm.cn/images/sicons/' . $sicon_id . '.png" />';
    }
    
    /**
     *
     * @todo 获取登录用户信息
     * @author bo.wang3
     * @return 成功返回数组，失败返回空
     * @version 2012-11-01
     */
    public function GetLoginedUserInfo()
    {
        if (isset ( $_COOKIE ['userinfo'] ))
        {
            $userinfo = json_decode ( $_COOKIE ['userinfo'], true );
            
            if ($userinfo ['ip'] == $_SERVER ['REMOTE_ADDR'] && $userinfo ['ua'] == $_SERVER ['HTTP_USER_AGENT'])
            {
                return $userinfo;
            }
        }
        
        return '';
    }
    
    /**
     *
     * @todo 实时获取单份资料收益
     * @author bo.wang3
     * @return 成功返回数组，失败返回空
     * @version 2012-11-01
     */
    /*
     * public function GetProfile($file_id)
     * {
     * $rs = $this->_db->rsArray ( 'SELECT file_views,file_downs FROM pd_files WHERE file_id =' . $file_id );
     *
     * $views = intval ( $rs ['file_views'] );
     * $downs = intval ( $rs ['file_downs'] );
     *
     * if (($views / $downs) > 0.01)
     * {
     * $v = $this->GetData ( "SELECT profile FROM xz_profile WHERE times = $views LIMIT 0,1" );
     * $d = $this->GetData ( "SELECT profile FROM xz_profile WHERE times = $downs LIMIT 0,1" );
     *
     * $sum = $v [0] ['profile'] + $d [0] ['profile'] * 10;
     *
     * return sprintf ( "%.2f", substr ( sprintf ( "%.3f", $sum ), 0, - 2 ) );
     * }
     * else
     * {
     * return '0.00';
     * }
     * }
     */
    
    /**
     *
     * @todo 新增云印订单，请在确认支付成功后调用
     * @author bo.wang
     * @version 2013-09-18 14:29
     */
    public function CloudPrint($file_id, $store_id = '100001', $pages = 3, $total = 1, $duplex = 1, $userid)
    {
        $value = array (
                'order_id' => build_order_no (),
                'store_id' => '100001',
                'pages' => 3,
                'total' => 1,
                'duplex' => 1,
                'file_id' => $file_id,
                'userid' => 1,
                'price' => 0.2,
                'ts' => TIMESTAMP,
                'state' => 0 
        );
        
        $this->_db->insert ( 'xz_cloudprint', $value );
        
        return array (
                'rcode' => 0,
                'msg' => '您的打印任务已经推送给商家，请于' . date ( 'Y-m-d H:i:s', (TIMESTAMP + 86400) ) . '前到广工图书馆一层领取。' 
        );
    }
   
}