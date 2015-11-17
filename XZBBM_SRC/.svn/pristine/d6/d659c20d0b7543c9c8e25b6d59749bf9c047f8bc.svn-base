<?php
if (! defined ( 'IN_SYS' ))
{
    header ( "HTTP/1.1 404 Not Found" );
    die ();
}
class SuperAPI extends Xzbbm
{
    public function __construct()
    {
        parent::__construct ();
    }
    
    /**
     * 1.获取通过BeginPublish接口获得file_index 2.将图片按照顺序带上file_index上传到服务器
     * 3.当所有图片upload完成后，使用PublishDocument，将图片外的其余信息发给服务器，即发布流程完成
     *
     * @author why
     *        
     */
    public function BeginPublich()
    {
        $file_index = md5 ( uniqid ( mt_rand (), true ) . microtime () . '1' );
        $this->Ret ( [ 
                "file_index" => $file_index 
        ] );
    }
    /**
     * 1.获取通过BeginPublish接口获得file_index 2.将图片按照顺序带上file_index上传到服务器
     * 3.当所有图片upload完成后，使用PublishDocument，将图片外的其余信息发给服务器，即发布流程完成
     *
     * @author why
     *        
     */
    public function ImageUpload()
    {
        $this->SaveFile ();
    }
    /**
     * 1.获取通过BeginPublish接口获得file_index 2.将图片按照顺序带上file_index上传到服务器
     * 3.当所有图片upload完成后，使用PublishDocument，将图片外的其余信息发给服务器，即发布流程完成
     *
     * @author why
     *        
     */
    public function PublishDocument()
    {
        // public String file_index;
        // public String file_title; // 标题
        // public String file_tag; // 文件标签，“考试真题”，“课堂笔记”，“黑板板书”，“课程论文”等
        // public double price; // 价格，如果不收费则为0
        // public int free_view_page; // 图片可见张数
        // public int total_page; // 图片总页数
        // public int fuzzy_level; // 模糊程度
        // public boolean is_price_reduce; // 是否加入开源免费计划
        // public boolean is_push; // 是否推送给订阅者
        // public boolean is_only_platform; // 是否仅在我们的平台内供浏览，不允许发送，给其他人活动原文件
        // public int ucode; // 学校编码，默认为当前用户的所在院校
        // public int ccode; // 学院编码，默认为当前用户的所在院校
        // public float yaohe_length; // 吆喝语音的时长，如果没有则为0
        // public String file_des; // 描述
        // public float longitude; //经度
        // public float latitude; //纬度
        $file_index = $this->msg ["file_index"];
        $file_title = $this->msg ["file_title"];
        $file_tag = $this->msg ["file_tag"];
        $price = intval ( $this->msg ["price"] );
        if ($price < 0)
            $price = 0;
        else if ($price > 1000000)
            $price = 1000000;
        $user = $this->GetUser ();
        $free_view_page = $this->msg ["free_view_page"];
        $total_page = $this->msg ["total_page"];
        $fuzzy_level = $this->msg ["fuzzy_level"];
        $is_price_reduce = $this->msg ["is_price_reduce"] == true ? 1 : 0;
        $is_push = $this->msg ["is_push"] == true ? 1 : 0;
        $is_only_platform = $this->msg ["is_only_platform"] == true ? 1 : 0;
        $ucode = empty($this->msg ["ucode"]) ? $user['ucode'] : $this->msg ["ucode"];
        $ccode = empty($this->msg ["ccode"]) ? $user['ccode'] : $this->msg ["ccode"];
        $yaohe_length = $this->msg ["yaohe_length"];
        $file_des = $this->msg ["file_des"];
        $longitude = $this->msg ["longitude"];
        $latitude = $this->msg ["latitude"];
        // ------------------------------------------
        $file_key = makerandom ( 5 );
        $pay_type = 1; // pay_type=0可用代金券，pay_type＝1用现金
        
        
        $file_userid = $user ["userid"];
        $user_name = $user ["user_name"];
        $user_description = $user ["user_description"];
        
        $uname = $this->GetData ( "SELECT geo_universities.name FROM geo_universities,geo_provinces
						                WHERE (geo_universities.university_id = $ucode)
						                AND geo_universities.province = geo_provinces.province_id" )[0]['name'];
        $cname = $this->GetData ( "SELECT college FROM geo_colleges WHERE college_id = $ccode" )[0]['college'];
        
        $file_extension = "png";
        
        $ins = array (
                'file_name' => $file_title,
                'file_index' => $file_index,
                'file_key' => $file_key,
                'file_tag' => $file_tag,
                'file_extension' => $file_extension,
                'file_info' => $file_des,
                'file_real_name' => $file_index,
                'file_md5' => $file_index, // TODO
                'file_size' => 0, // TODO
                'file_time' => TIMESTAMP,
                'file_views' => 1,
                // 'is_checked' => 0, delete by zmw
                'userid' => $file_userid,
                'ip' => get_ip (),
                'location' => $location_id,
                'ucode' => $file_ucode,
                'pay_type' => $pay_type,
                'price' => $price,
                'today_price' => $price,
                'is_push' => $is_push,
                'ccode' => $file_ccode,
                'free_view_page' => $free_view_page,
                'total_page' => $total_page,
                'fuzzy_level' => $fuzzy_level,
                'is_price_reduce' => $is_price_reduce,
                'is_only_platform' => $is_only_platform,
                'yaohe_length' => $yaohe_length,
                'user_name' => $user_name,
                'user_description' => $user_description,
                'uname' => $uname,
                'cname' => $cname,
                'longitude' => $longitude,
                'latitude' => $latitude,
                'has_png' => $total_page,
                'ucode' => $ucode,
                'ccode' => $ccode,
                'uname' => $uname,
                'cname' => $cname 
        );
        
        $rs_fid = $this->_db->insert ( 'pd_files', $ins );
        // add by ZMW below
        // if ($is_push == 1)
        // {
        $user_id_array = [ ];
        $subscribe_id = $user ['userid']; // 检索出所有关注这个用户的用户关注信息
        $sql = "SELECT * FROM pd_subscribe WHERE subscribe_id = $subscribe_id AND is_subscribe = 1 AND push_setting <> 0";
        $subscribes = $this->_db->dataArray ( $sql );
        for($i = 0; $i < count ( $subscribes ); $i ++)
        {
            $push_num = $subscribes [$i] ["push_num"];
            $user_id = $subscribes [$i] ["user_id"];
            $push_num ++;
            $update ["push_num"] = $push_num;
            $where = "user_id = $user_id AND subscribe_id = $subscribe_id";
            $this->_db->update ( "pd_subscribe", $update, $where );
            
            if ($subscribes [$i] ["push_setting"] == 2)
                array_push ( $user_id_array, $user_id );
        }
        // }
        // add by zmw above
        if (is_numeric ( $rs_fid ))
        {
            if (count ( $user_id_array ) > 0)
                $xg_ret = $this->PushAccount ( $user_id_array, $file_title, "发布者：" . $user ['user_name'] . "，点击查看资料详情！" );
            $this->ok ();
        }
        else
        {
            $this->Error ( $this->_db->_errorMsg );
        }
    }
    
    // public function TestPush()
    // {
    // $subscribe_id = $this->msg [user_id];
    // $sql = "SELECT * FROM pd_subscribe WHERE subscribe_id = $subscribe_id AND is_subscribe = 1 AND push_setting <> 0";
    // $subscribes = $this->_db->dataArray ( $sql );
    // for($i = 0; $i < count ( $subscribes ); $i ++)
    // {
    // $push_num = $subscribes [$i] ["push_num"];
    // $user_id = $subscribes [$i] ["user_id"];
    // $push_num ++;
    // $update ["push_num"] = $push_num;
    // $where = "user_id = $user_id AND subscribe_id = $subscribe_id";
    // $this->_db->update ( "pd_subscribe", $update, $where );
    
    // if ($subscribes [$i] ["push_setting"] == 2)
    // {
    // $xg_ret = $this->PushAccount ( $user_id, $user ['user_name'] . '刚刚发布了', $file_title );
    // var_dump ( $xg_ret );
    // var_dump ( $subscribes [$i] );
    // }
    // }
    // }
    
    /**
     * 获取文档详情
     *
     * @author why
     */
    public function GetDocumentInfo()
    {
        $request_file_index = $this->msg ["file_index"];
        $request_file_key = $this->msg ["file_key"];
        $mine = $this->GetUser ();
        if (empty ( $request_file_index ))
            $request_file_index = $this->msg ["file_real_name"];
        if (! empty ( $request_file_index ))
        {
            $rs = $this->_db->rsArray ( "select * from pd_files where file_index = '$request_file_index' limit 1" );
        }
        else if (! empty ( $request_file_key ))
        {
            $rs = $this->_db->rsArray ( "select * from pd_files where file_key = '$request_file_key' limit 1" );
        }
        else
        {
            $file_id = $this->msg ["file_id"];
            $rs = $this->_db->rsArray ( "select * from pd_files where file_id = '$file_id' limit 1" );
        }
        
        $rs ["web_url"] = "http://www.xzbbm.cn/view/{$rs["file_real_name"]}";
        $rs ["qrcode_str"] = "https://api.xzbbm.cn/?action=SuperAPI&do=OutputQr&type=file&param={$rs['file_key']}";
        
        $is_pay = $this->FilePayCheck ( $mine ['userid'], $rs ['file_id'] );
        $rs ["viewImageUrlList"] = $this->GetImageList ( $mine ['userid'], $rs, $is_pay );
        $rs ['is_pay'] = $is_pay;
        
        if ($rs ["has_png"] == 0)
        {
            $this->_db->update ( "pd_files", [ 
                    "is_converted" => 3,
                    "convert_request_time" => TIMESTAMP 
            ], "file_id={$rs[file_id]}" );
        }
        
        $rs ["file_description"] = ""; // clear file description
        $rs ["yaohe_url"] = "";
        if ($rs ["yaohe_length"] > 0)
        {
            $rs ["yaohe_url"] = $this->GetUrl ( array (
                    'file_real_name' => $rs ["file_real_name"],
                    'page' => '',
                    'degree' => '',
                    'timeout' => 3600,
                    'file_extension' => "amr" 
            ) );
        }
        if ($rs ["userid"] == 0)
            $rs ["userid"] = 1;
        $user = $this->GetData ( "SELECT * FROM pd_users WHERE userid = '{$rs["userid"]}' LIMIT 0,1" )[0];
        $rs ["user_name"] = empty ( $user ["user_name"] ) ? half_replace ( empty ( $user ["phone"] ) ? $user ["user_email"] : $user ["phone"] ) : $user ["user_name"];
        $rs ["user_description"] = empty ( $user ["user_description"] ) ? "学长只能帮你到这了" : $user ["user_description"];
        $rs ["user_icon_url"] = $user ["user_icon"];
        $rs ["comment_list"] = $this->GetComments ( $rs ["file_id"] );
        $rs ["comment_count"] = $this->GetCommentCount ( $rs ["file_id"] );
        $rs ["good_comment_rate"] = rand ( 20, 50 ) / 10; // TODO
        $rs ['get_price'] = $rs ['today_price'];
        $ret_price = $this->GetNormalPrice ( $rs, false, 1 );
        $rs ['express_price'] = $ret_price ['express_price'];
        $rs ['buffet_price'] = $ret_price ['buffet_price'];
        // public boolean is_pay; // true不需要付费，false需要付费
        // public int get_price; // 下载或者浏览的付费
        // public int express_price; // 云印快递的付费
        // public int buffet_price; // 云印自取的付费
        
        $this->operate ( $mine ["userid"], $rs ["file_id"], Xzbbm::operate_view );
        $this->Ret ( [ 
                "document" => $rs 
        ] );
    }
    
    /**
     * 刷新图片
     *
     * @author why
     */
    public function RefreshFileImages()
    {
        $request_file_index = $this->msg ["file_index"];
        if (! empty ( $request_file_index ))
        {
            $rs = $this->_db->rsArray ( "select * from pd_files where file_index = '$request_file_index' limit 1" );
            $userid = $this->GetUser ()[userid];
            
            if ($rs ['file_id'] > 0 && $userid > 0)
            {
                $is_pay = $this->FilePayCheck ( $userid, $rs ['file_id'] );
                $ret ["viewImageUrlList"] = $this->GetImageList ( $userid, $rs, $is_pay );
                $ret ["is_pay"] = $is_pay;
                $this->Ret ( $ret );
                return;
            }
        }
        $this->Error ( "参数错误" );
    }
    /**
     * 评论/申诉文档
     *
     * @author why
     */
    public function CommentDocument()
    {
        $file_index = $this->msg [file_index];
        $content = $this->msg ['content'];
        $type = $this->msg ['type']; // 0评论 1申诉 2建议//TOOD
        $good_comment_rate = $this->msg ['good_comment_rate'];
        $user = $this->GetUser ();
        $rs_file = $this->_db->rsArray ( "select * from pd_files where file_index = '$file_index' limit 1" );
        if ($user [userid] <= 0)
        {
            $this->Error ( "参数错误" );
            return;
        }
        if (empty ( $content ))
        {
            $this->Error ( "content can't be empty" );
            return;
        }
        
        $comment_user_id = $user [userid];
        $file_id = $rs_file ['file_id'];
        $reply_id = 0;
        
        $info = array (
                'content' => $content,
                'time' => TIMESTAMP,
                'comment_user_id' => $comment_user_id,
                'reply_id' => $reply_id,
                'file_id' => $file_id,
                'type' => $type,
                'good_comment_rate' => $good_comment_rate 
        );
        $rs = $this->_db->insert ( 'pd_comment', $info );
        
        if (is_numeric ( $rs ))
        {
            $this->ok ();
        }
        else
        {
            $this->say ( $info . "\n[ERROR]" . $this->_db->_errorMsg );
            $this->Error ( "服务器出错，请稍后重试" );
        }
    }
    /**
     * 获取评论
     *
     * @author why
     */
    function GetComments($file_id)
    {
        // $post_file_id = $this->msg ['file_id'];
        // $from = $this->msg ['from'];
        // $limit = $this->msg ['limit'];
        // if ($limit > 0)
        // $list = $this->GetData ( "select * from pd_comment where file_id = $post_file_id DESC time LIMIT $from,$limit" );
        // else
        $list = $this->_db->dataArray ( "select * from pd_comment where file_id = $file_id order by time desc limit 20" );
        foreach ( $list as $k => $v )
        {
            $item = $list [$k];
            
            $comment_user = $this->GetData ( "SELECT * FROM pd_users WHERE userid = '{$item['comment_user_id']}' LIMIT 0,1" )[0];
            $item [comment_user_name] = $comment_user ['user_name'];
            $item [comment_user_icon] = $comment_user ['user_icon'];
            
            $list [$k] = $item;
        }
        return $list;
    }
    /**
     *
     * @author why
     * @param
     *            $file_id
     * @return comment count
     */
    function GetCommentCount($file_id)
    {
        $count = $this->_db->rsArray ( "select count(*) as comment_count from pd_comment where file_id = $file_id" );
        return $count ['comment_count'];
    }
    
    /**
     */
    public function SearchDocument()
    {
        $ucode = $this->msg ["ucode"];
        $ccode = $this->msg ["ccode"];
        $uname = $this->msg ["uname"];
        $cname = $this->msg ["cname"];
        $from = $this->msg ["from"];
        $limit = $this->msg ["limit"];
        $keyword = $this->msg ["keyword"];
        $order = $this->msg ["order"];
        $pay = $this->msg ["pay"];
        $longitude = $this->msg ["longitude"];
        $latitude = $this->msg ["latitude"];
        $search_index = "file_name";
        if (empty ( $keyword ))
        {
            $search_index = "ucode";
            $keyword = $ucode;
        }
        $result_items = OpenSearchFile::Main ( $search_index, $keyword, $order, $from, $limit, $ucode, $ccode, $uname, $cname, $pay, $longitude, $latitude );
        $result_items = $this->AddInfoToSearchRetList ( $result_items );
        foreach ( $result_items as $k => $v )
        {
            $result_items [$k] ['sicon_id'] = $this->GetData ( 'SELECT sicon_id,name FROM geo_universities WHERE university_id = ' . $v ['ucode'] )[0]['sicon_id'];
            $result_items [$k] ['uname'] = $this->GetData ( 'SELECT sicon_id,name FROM geo_universities WHERE university_id = ' . $v ['ucode'] )[0]['name'];
            $result_items [$k] ['uname'] = $result_items [$k] ['uname'] == '' ? '学长帮帮忙' : $result_items [$k] ['uname'];
            $result_items [$k] ['cname'] = $this->GetData ( 'SELECT college FROM geo_colleges WHERE college_id = ' . $v ['ccode'] )[0]['college'];
            $result_items [$k] ['cname'] = $result_items [$k] ['cname'] == '' ? '' : $result_items [$k] ['cname'];
            $result_items [$k] ['FormatedFileTime'] = date ( 'Y/m/d', $result_items [$k] ['file_time'] );
        }
        $this->Ret ( [ 
                "documentItemList" => $result_items 
        ] );
    }
    /**
     */
    function AddInfoToSearchRetList($result_items)
    {
        // public String user_action_str;// 身边栏目中显示用，格式 小明同学 55分钟前 发布了
        foreach ( $result_items as $k => $v )
        {
            $ret = $result_items [$k];
            
            $ret ["total_page"] = $ret ["total_page"] ? $ret ["total_page"] : 'X';
            $ret ["qrcode_str"] = "https://api.xzbbm.cn/?action=SuperAPI&do=OutputQr&type=file&param={$rs['file_key']}";
            $ret ["web_url"] = "./{$ret["file_key"]}";
            $ret ["thumbnail"] = $this->GetUrl ( array (
                    'file_real_name' => $v ["file_real_name"],
                    'page' => floor ( $v ["has_png"] / 2 ),
                    'degree' => '300',
                    'timeout' => 3600,
                    'file_extension' => 'png' 
            ) );
            
            if ($ret ["yaohe_length"] > 0)
            {
                $ret ["yaohe_url"] = $this->GetUrl ( array (
                        'file_real_name' => $ret ["file_real_name"],
                        'page' => '',
                        'degree' => '',
                        'timeout' => 3600,
                        'file_extension' => "amr" 
                ) );
            }
            if (empty ( $ret ["ucode"] ))
            {
                $icon_id = 0;
                $ret ["ucode"] = 0;
                $ret ["ccode"] = 0;
            }
            else
            {
                $icon_id = $this->GetData ( "SELECT sion_id FROM geo_universities
                        WHERE university_id = {$ret[ucode]} LIMIT 1" )[0]["sion_id"];
            }
            if (empty ( $icon_id ))
                $icon_id = 0;
            $ret ["uicon"] = "https://www.xzbbm.cn/images/sicons/" . $icon_id . ".png";
            
            $file = $this->GetData ( 'SELECT has_text,file_real_name FROM pd_files WHERE file_id = ' . $ret ["file_id"] )[0];
            
            if (1 == $file ['has_text'])
            {
                $ret ["file_info"] = file_get_contents ( $this->GetUrl ( array (
                        'file_real_name' => $file ['file_real_name'],
                        'timeout' => 60,
                        'file_extension' => 'txt' 
                ) ), NULL, NULL, 0, 600 );
            }
            
            $ret ["file_info"] = strlen ( $ret ["file_info"] ) > 10 ? strlen ( $ret ["file_info"] ) . $ret ["file_info"] : '来自一位默默无闻的学长';
            
            if (empty ( $ret ["uname"] ))
            {
                $ret ["uname"] = $this->GetData ( "SELECT geo_universities.name FROM geo_universities,geo_provinces
                        WHERE (geo_universities.university_id = $uid)
                        AND geo_universities.province = geo_provinces.province_id" )[0]['name'];
            }
            if (empty ( $ret ["uname"] ))
            {
                $ret ["uname"] = "学长帮帮忙";
            }
            if (empty ( $ret ["cname"] ))
            {
                $ret ["cname"] = $this->GetData ( "SELECT college FROM geo_colleges WHERE college_id = $ccode" )[0]['college'];
            }
            if (empty ( $ret ["cname"] ))
            {
                $ret ["cname"] = "校本部";
            }
            
            if ($ret ["userid"] == 0)
                $ret ["userid"] = 1;
            $user = $this->GetData ( "SELECT * FROM pd_users WHERE userid = '{$ret["userid"]}' LIMIT 0,1" )[0];
            $ret ["user_name"] = empty ( $user ["user_name"] ) ? half_replace ( empty ( $user ["phone"] ) ? $user ["user_email"] : $user ["phone"] ) : $user ["user_name"];
            $ret ["user_description"] = empty ( $user ["user_description"] ) ? "学长只能帮你到这了" : $user ["user_description"];
            $ret ["user_icon"] = $user ["user_icon"];
            $ret ["user_action_str"] = "{$ret ["user_name"]} " . timeformat ( $ret ['file_time'], 5 ) . " 发布了"; // TODO
            $ret ["good_count"] = $ret ["good_comment_rate"];
            $result_items [$k] = $ret;
        }
        
        return $result_items;
    }
    
    // request Login {email:string,password:string}
    // response {xztoken:string}
    // http://testapi.xzbbm.cn/?action=SuperAPI&do=Login&debug=on&msg={%22password%22:%22123%22,%22email%22:%22abcabc@qqq.com%22}
    public function Login()
    {
        // public String weibo_id;
        // public String weixin_id;
        // public String qq_id;
        $email = $this->msg ["email"];
        $phone = $this->msg ["phone"];
        $password = md5 ( $this->msg ["password"] );
        $ucode = 0;
        $ccode = 0;
        $is_ok = false;
        if (! empty ( $email ))
        {
            $rs_email = $this->_db->rsArray ( "SELECT * FROM pd_users WHERE email = '$email' LIMIT 0,1" );
            if (! empty ( $rs_email ) && $password == $rs_email ['password'])
            {
                $ucode = $rs_email ["ucode"];
                $ccode = $rs_email ["ccode"];
                $is_ok = true;
                $rs_user = $rs_email;
                if (empty ( $rs_user [xztoken] ))
                {
                    $rs_user [xztoken] = md5 ( uniqid ( mt_rand (), true ) . microtime () . '1' );
                    $this->_db->update ( 'pd_users', [ 
                            'xztoken' => $rs_user [xztoken] 
                    ], "email = '$email'" );
                }
            }
        }
        else if (! empty ( $phone ))
        {
            $rs_phone = $this->_db->rsArray ( "SELECT * FROM pd_users WHERE phone = '$phone' LIMIT 0,1" );
            if (! empty ( $rs_phone ) && $password == $rs_phone ['password'])
            {
                $ucode = $rs_phone ["ucode"];
                $ccode = $rs_phone ["ccode"];
                $is_ok = true;
                $rs_user = $rs_phone;
                if (empty ( $rs_user [xztoken] ))
                {
                    $rs_user [xztoken] = md5 ( uniqid ( mt_rand (), true ) . microtime () . '1' );
                    $this->_db->update ( 'pd_users', [ 
                            'xztoken' => $rs_user [xztoken] 
                    ], "phone = '$phone'" );
                }
            }
        }
        
        if ($is_ok == true)
        {
            $ucode = $rs_user [ucode];
            $ccode = $rs_user [ccode];
            $uinfo = $this->GetData ( "SELECT geo_universities.name,geo_universities.sicon_id FROM geo_universities,geo_provinces
                    WHERE (geo_universities.university_id = $ucode)
                    AND geo_universities.province = geo_provinces.province_id" )[0];
            $rs_user ["uname"] = $uinfo ['name'];
            $rs_user ["uicon"] = "https://www.xzbbm.cn/images/sicons/" . $uinfo [sicon_id] . ".png";
            $rs_user ["cname"] = $this->GetNameByCcode ( $ccode );
            
            $this->Ret ( [ 
                    'user' => $rs_user,
                    'ucode' => $ucode,
                    'ccode' => $ccode,
                    'is_new_user' => false,
                    'xztoken' => $rs_user [xztoken],
                    'is_jump_to_bind_phone' => false 
            ] ); // (empty ( $phone )) && (empty ( $rs_email ['phone'] ) || $rs_email ['phone'] == "empty")
        }
        else
        {
            $weibo_token = $this->msg ["weibo_token"];
            $qq_token = $this->msg ["qq_token"];
            $weixin_token = $this->msg ["weixin_token"];
            
            if (empty ( $weibo_token ) && empty ( $qq_token ) && empty ( $weixin_token ))
            {
                $this->Error ( '您输入的信息有误！' );
            }
            else
            {
                $this->ThirdLogin ();
            }
        }
    }
    /**
     */
    public function ThirdLogin()
    {
        $weibo_token = $this->msg ["weibo_token"];
        $qq_token = $this->msg ["qq_token"];
        $weixin_token = $this->msg ["weixin_token"];
        $userIcon = $this->msg ["userIcon"];
        $userName = $this->msg ["userName"];
        $userGender = $this->msg ["userGender"];
        $userNote = $this->msg ["userNote"];
        $is_new_user = false;
        if (! empty ( $weibo_token ))
        {
            $rs = $this->_db->rsArray ( "SELECT * FROM pd_users WHERE weibo_token = '$weibo_token' LIMIT 0,1" );
        }
        else if (! empty ( $weixin_token ))
        {
            $rs = $this->_db->rsArray ( "SELECT * FROM pd_users WHERE weixin_token = '$weixin_token' LIMIT 0,1" );
        }
        else if (! empty ( $qq_token ))
        {
            $rs = $this->_db->rsArray ( "SELECT * FROM pd_users WHERE qq_token = '$qq_token' LIMIT 0,1" );
        }
        else
        {
            $this->Error ( "没有验证token" );
        }
        if (empty ( $rs ))
        {
            $sex = ("m" == $userGender) ? "male" : "femal";
            $rs = $this->CreateUser ( '123456', '', '', $sex, $qq_token, $weibo_token, $weixin_token );
            $is_new_user = true;
            $this->_db->update ( 'pd_users', [ 
                    'user_icon' => $userIcon,
                    'user_name' => $userName,
                    'user_description' => $userNote 
            ], "xztoken = '$rs[xztoken]'" );
        }
        if (! empty ( $rs ))
        {
            $rs ["password"] = "";
            $ucode = $rs [ucode];
            $ccode = $rs [ccode];
            $uinfo = $this->GetData ( "SELECT geo_universities.name,geo_universities.sicon_id FROM geo_universities,geo_provinces
                    WHERE (geo_universities.university_id = $ucode)
                    AND geo_universities.province = geo_provinces.province_id" )[0];
            $rs ["uname"] = $uinfo ['name'];
            $rs ["uicon"] = "https://www.xzbbm.cn/images/sicons/" . $uinfo [sicon_id] . ".png";
            $rs ["cname"] = $this->GetNameByCcode ( $ccode );
            
            $this->Ret ( [ 
                    'user' => $rs,
                    'ucode' => $rs [ucode],
                    'ccode' => $rs [ccode],
                    'is_new_user' => $is_new_user,
                    'xztoken' => $rs [xztoken],
                    'is_jump_to_bind_phone' => false,
                    'is_jump_to_bind_email' => empty ( $rs ['email'] ) 
            ] ); // empty ( $rs [phone] )
        }
        else
        {
            $this->Error ( "服务器出错" );
        }
    }
    
    /**
     * 创建新用户
     *
     * @author why
     * @param unknown $password            
     * @param unknown $phone            
     * @param unknown $email            
     * @param unknown $sex            
     * @param unknown $qq_token            
     * @param unknown $weibo_token            
     * @param unknown $weixin_token            
     */
    public function CreateUser($password, $phone, $email, $sex, $qq_token, $weibo_token, $weixin_token)
    {
        $random_xztoken = md5 ( uniqid ( mt_rand (), true ) . microtime () . '1' );
        $user = array (
                'weixin_token' => $weixin_token,
                'weibo_token' => $weibo_token,
                'qq_token' => $qq_token,
                'phone' => $phone,
                'email' => $email,
                'sex' => $sex,
                'password' => md5 ( $password ),
                'xztoken' => $random_xztoken,
                'is_locked' => 0,
                'last_login_time' => TIMESTAMP,
                'reg_time' => TIMESTAMP,
                'reg_ip' => $_SERVER ['REMOTE_ADDR'],
                'last_login_ip' => $_SERVER ['REMOTE_ADDR'],
                'user_icon' => 'http://cdn.xzbbm.cn/usericons/default' . rand ( 1, 7 ) . '.png',
                'user_name' => substr_replace ( $email, '****', 3, 3 ) ? substr_replace ( $email, '****', 3, 3 ) : substr_replace ( $phone, '****', 3, 3 ) 
        );
        $this->_db->insert ( 'pd_users', $user );
        $rs_user = $this->_db->rsArray ( "SELECT * FROM pd_users WHERE xztoken = '$random_xztoken' LIMIT 0,1" );
        return $rs_user;
    }
//     public function BindEmail()
//     {
//         $email = $this->msg ["email"];
//         $password = $this->msg ["password"];
//         $is_test = $this->msg ["is_test"];
//         $xztoken = $this->msg ["xztoken"];
//         $rs = $this->_db->rsArray ( "SELECT * FROM pd_users WHERE email = '$email' LIMIT 0,1" );
//         if ($is_test)
//         {
//             if (! empty ( $rs ))
//             {
//                 $this->Error ( '亲，该邮箱已注册！' );
//             }
//             else
//             {
//                 $this->ok ();
//             }
//         }
//         else
//         {
//             if (! empty ( $rs ))
//             {
//                 $this->Error ( '亲，该邮箱已注册！' );
//             }
//             else
//             {
//                 $this->_db->update ( 'pd_users', [ 
//                         'email' => $email,
//                         'password' => $password 
//                 ], "xztoken = '$xztoken'" );
//             }
//         }
//     }
    
    // request Register {phone:string}
    // response {}
    // http://testapi.xzbbm.cn/?action=SuperAPI&do=Register&debug=on&msg={%22xztoken%22:%22abcabc@qqq.com%22,%22phone%22:%22122222222%22}
    public function Register()
    {
        $phone = $this->msg ["phone"];
        $password = $this->msg ["password"];
        $xztoken = $this->msg ["xztoken"];
        $is_test = $this->msg ["is_test"];
//         $email = $this->msg ["email"];
//         if (! empty ( $email ))
//         {
//             $this->BindEmail ();
//             return;
//         }
        
        if (! empty ( $phone )) // check phone
        {
            $rs_phone = $this->_db->rsArray ( "SELECT * FROM pd_users WHERE phone = '$phone' LIMIT 0,1" );
            if ($is_test)
            { // 测试手机是否用过
                if (! empty ( $rs_phone )) // have registered
                {
                    $this->Error ( '亲，你的手机号码已注册！' );
                }
                else
                {
                    $this->ok ();
                }
                return;
            }
            if (! empty ( $xztoken ) && empty ( $rs_phone ))
            {
                $this->_db->update ( 'pd_users', [ 
                        'phone' => $phone,
                        'password' => $password 
                ], "xztoken = '$xztoken'" );
                $rs_user = $this->GetUser ();
                $rs_user ["password"] = "";
                $this->Ret ( [ 
                        'user' => $rs_user,
                        'xztoken' => $rs_user [xztoken],
                        'ucode' => $rs_user [ucode],
                        'ccode' => $rs_user [ccode] 
                ] );
                return;
            }
            if (! empty ( $xztoken ) && ! empty ( $rs_phone ))
            {
                $new_user = $this->GetUser ( $xztoken );
                $qq_token = $new_user ['qq_token'];
                $weibo_token = $new_user ['weibo_token'];
                $weixin_token = $new_user ['weixin_token'];
                if (! empty ( $qq_token ))
                {
                    $this->_db->update ( 'pd_users', [ 
                            'qq_token' => $qq_token 
                    ], "phone = '$rs_phone[phone]'" );
                }
                if (! empty ( $weibo_token ))
                {
                    $this->_db->update ( 'pd_users', [ 
                            'weibo_token' => $weibo_token 
                    ], "phone = '$rs_phone[phone]'" );
                }
                if (! empty ( $weixin_token ))
                {
                    $this->_db->update ( 'pd_users', [ 
                            'weixin_token' => $weixin_token 
                    ], "phone = '$rs_phone[phone]'" );
                }
                $this->_db->update ( 'pd_users', [ 
                        'user_icon' => $new_user [user_icon],
                        'user_name' => $new_user [user_name],
                        'user_description' => $new_user [user_description],
                        'password' => $password 
                ], "phone = '$rs_phone[phone]'" );
                
                $this->_db->update ( 'pd_users', [ 
                        'weixin_token' => '',
                        'qq_token' => '',
                        'weibo_token' => '',
                        'xztoken' => '',
                        'phone' => '',
                        'email' => '',
                        'is_locked' => 1 
                ], "xztoken = '$new_user[xztoken]'" );
                
                $rs_user = $rs_phone;
                if (empty ( $rs_user [xztoken] ))
                {
                    $rs_user [xztoken] = md5 ( uniqid ( mt_rand (), true ) . microtime () . '1' );
                    $this->_db->update ( 'pd_users', [ 
                            'xztoken' => $rs_user [xztoken] 
                    ], "phone = '$phone'" );
                }
                $rs_user ["password"] = "";
                $this->Ret ( [ 
                        'user' => $rs_user,
                        'xztoken' => $rs_user [xztoken],
                        'ucode' => $rs_user [ucode],
                        'ccode' => $rs_user [ccode] 
                ] );
                return;
            }
            
            if (! empty ( $phone ) && ! empty ( $password ))
            {
                $rs_user = $this->CreateUser ( $password, $phone, '', '', '', '', '' );
                $rs_user ["password"] = "";
                $this->Ret ( [ 
                        'user' => $rs_user,
                        'xztoken' => $rs_user [xztoken],
                        'ucode' => $rs_user [ucode],
                        'ccode' => $rs_user [ccode] 
                ] );
                return;
            }
        }
        $this->Error ( "输入信息有误" );
    }
    
    // request SelectedSchoolCollege {ucode:int,ccode:int}
    // response {}
    // http://testapi.xzbbm.cn/?action=SuperAPI&do=SelectedSchoolCollege&debug=on&msg={%22xztoken%22:%22abcabc@qqq.com%22,%22ucode%22:11,%22ccode%22:11}
    public function SelectedSchoolCollege()
    {
        $xztoken = $this->msg ["xztoken"]; // TODO
        if (empty ( $xztoken ))
        {
            $this->Error ( "xztoken can not be null" );
            return;
        }
        $ucode = $this->msg ["ucode"];
        $ccode = $this->msg ["ccode"];
        $this->_db->update ( 'pd_users', [ 
                'ucode' => $ucode,
                'ccode' => $ccode 
        ], "xztoken = '$xztoken'" );
        $this->ok ();
    }
    
    // requeset GetAllProvinces {}
    // response [{province_id:int, province:string}]
    // http://testapi.xzbbm.cn/?action=SuperAPI&do=GetAllProvinces&debug=on
    public function GetAllProvinces()
    {
        $this->Ret ( [ 
                "provinceList" => $this->GetProvinces () 
        ] );
    }
    
    // request GetUniversities {province_id:int}
    // response [{university_id:int,name:string,sicon_id:int}]
    // http://testapi.xzbbm.cn/?action=SuperAPI&do=GetUniversities&debug=on&msg={%22xztoken%22:%22abcabc@qqq.com%22,%22province_id%22:7}
    public function GetUniversities()
    {
        $province_id = $this->msg ['province_id'];
        $university = $this->msg ['university'];
        $this->Ret ( [ 
                "universityList" => $this->GetProvinceInfo ( 'data', $province_id, $university ) 
        ] );
    }
    
    // requeset GetColleges {university_id:int}
    // response [{college_id:int, college:string, university_id:int, total(该学院的资料总数):int}]
    // http://testapi.xzbbm.cn/?action=SuperAPI&do=GetColleges&debug=on&msg={%22xztoken%22:%22abcabc@qqq.com%22,%22university_id%22:3102}
    public function GetColleges()
    {
        $university_id = $this->msg ["university_id"];
        $this->Ret ( [ 
                "collegeList" => $this->GetUniversityInfo ( $university_id, '', 'college' ) 
        ] );
    }
    public $hot_list = array (
            '数据库原理' => "",
            '通信原理' => "",
            '化工' => "",
            '机械' => "",
            '物理实验报告' => "",
            '考研英语' => "",
            '政治答题' => "",
            '李永乐数学' => "",
            '司法考试' => "",
            '公务员考试' => "",
            '高等数学' => "",
            'C++' => "",
            '金属材料' => "",
            '电动' => "",
            '高数' => "",
            '线性代数' => "",
            '会计' => "",
            '工商管理试题' => "",
            '计算机组成原理' => "",
            '微观经济学' => "",
            '考研数学' => "" 
    );
    public $mimetypes = array (
            'ez' => 'application/andrew-inset',
            'hqx' => 'application/mac-binhex40',
            'cpt' => 'application/mac-compactpro',
            'doc' => 'application/msword',
            'docx' => 'application/msword',
            'bin' => 'application/octet-stream',
            'dms' => 'application/octet-stream',
            'lha' => 'application/octet-stream',
            'lzh' => 'application/octet-stream',
            'exe' => 'application/octet-stream',
            'class' => 'application/octet-stream',
            'so' => 'application/octet-stream',
            'dll' => 'application/octet-stream',
            'oda' => 'application/oda',
            'pdf' => 'application/pdf',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',
            'smi' => 'application/smil',
            'smil' => 'application/smil',
            'mif' => 'application/vnd.mif',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.ms-powerpoint',
            'wbxml' => 'application/vnd.wap.wbxml',
            'wmlc' => 'application/vnd.wap.wmlc',
            'wmlsc' => 'application/vnd.wap.wmlscriptc',
            'bcpio' => 'application/x-bcpio',
            'vcd' => 'application/x-cdlink',
            'pgn' => 'application/x-chess-pgn',
            'cpio' => 'application/x-cpio',
            'csh' => 'application/x-csh',
            'dcr' => 'application/x-director',
            'dir' => 'application/x-director',
            'dxr' => 'application/x-director',
            'dvi' => 'application/x-dvi',
            'spl' => 'application/x-futuresplash',
            'gtar' => 'application/x-gtar',
            'hdf' => 'application/x-hdf',
            'js' => 'application/x-javascript',
            'skp' => 'application/x-koan',
            'skd' => 'application/x-koan',
            'skt' => 'application/x-koan',
            'skm' => 'application/x-koan',
            'latex' => 'application/x-latex',
            'nc' => 'application/x-netcdf',
            'cdf' => 'application/x-netcdf',
            'sh' => 'application/x-sh',
            'shar' => 'application/x-shar',
            'swf' => 'application/x-shockwave-flash',
            'sit' => 'application/x-stuffit',
            'sv4cpio' => 'application/x-sv4cpio',
            'sv4crc' => 'application/x-sv4crc',
            'tar' => 'application/x-tar',
            'tcl' => 'application/x-tcl',
            'tex' => 'application/x-tex',
            'texinfo' => 'application/x-texinfo',
            'texi' => 'application/x-texinfo',
            't' => 'application/x-troff',
            'tr' => 'application/x-troff',
            'roff' => 'application/x-troff',
            'man' => 'application/x-troff-man',
            'me' => 'application/x-troff-me',
            'ms' => 'application/x-troff-ms',
            'ustar' => 'application/x-ustar',
            'src' => 'application/x-wais-source',
            'xhtml' => 'application/xhtml+xml',
            'xht' => 'application/xhtml+xml',
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'au' => 'audio/basic',
            'snd' => 'audio/basic',
            'mid' => 'audio/midi',
            'midi' => 'audio/midi',
            'kar' => 'audio/midi',
            'mpga' => 'audio/mpeg',
            'mp2' => 'audio/mpeg',
            'mp3' => 'audio/mpeg',
            'aif' => 'audio/x-aiff',
            'aiff' => 'audio/x-aiff',
            'aifc' => 'audio/x-aiff',
            'm3u' => 'audio/x-mpegurl',
            'ram' => 'audio/x-pn-realaudio',
            'rm' => 'audio/x-pn-realaudio',
            'rpm' => 'audio/x-pn-realaudio-plugin',
            'ra' => 'audio/x-realaudio',
            'wav' => 'audio/x-wav',
            'pdb' => 'chemical/x-pdb',
            'xyz' => 'chemical/x-xyz',
            'bmp' => 'image/bmp',
            'gif' => 'image/gif',
            'ief' => 'image/ief',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'jpe' => 'image/jpeg',
            'png' => 'image/png',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'djvu' => 'image/vnd.djvu',
            'djv' => 'image/vnd.djvu',
            'wbmp' => 'image/vnd.wap.wbmp',
            'ras' => 'image/x-cmu-raster',
            'pnm' => 'image/x-portable-anymap',
            'pbm' => 'image/x-portable-bitmap',
            'pgm' => 'image/x-portable-graymap',
            'ppm' => 'image/x-portable-pixmap',
            'rgb' => 'image/x-rgb',
            'xbm' => 'image/x-xbitmap',
            'xpm' => 'image/x-xpixmap',
            'xwd' => 'image/x-xwindowdump',
            'igs' => 'model/iges',
            'iges' => 'model/iges',
            'msh' => 'model/mesh',
            'mesh' => 'model/mesh',
            'silo' => 'model/mesh',
            'wrl' => 'model/vrml',
            'vrml' => 'model/vrml',
            'css' => 'text/css',
            'html' => 'text/html',
            'htm' => 'text/html',
            'asc' => 'text/plain',
            'txt' => 'text/plain',
            'rtx' => 'text/richtext',
            'rtf' => 'text/rtf',
            'sgml' => 'text/sgml',
            'sgm' => 'text/sgml',
            'tsv' => 'text/tab-separated-values',
            'wml' => 'text/vnd.wap.wml',
            'wmls' => 'text/vnd.wap.wmlscript',
            'etx' => 'text/x-setext',
            'xsl' => 'text/xml',
            'xml' => 'text/xml',
            'mpeg' => 'video/mpeg',
            'mpg' => 'video/mpeg',
            'mpe' => 'video/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',
            'mxu' => 'video/vnd.mpegurl',
            'avi' => 'video/x-msvideo',
            'movie' => 'video/x-sgi-movie',
            'ice' => 'x-conference/x-cooltalk' 
    );
    /**
     * 随即获取热门词汇
     *
     * @author why
     */
    public function GetHotWords()
    {
        $this->Ret ( [ 
                "hotWordList" => array_rand ( $this->hot_list, 9 ) 
        ] );
    }
    
    /**
     * 发送邮件
     *
     * @author why
     */
    public function SendMail()
    {
        $email = $this->msg ["email"];
        $file_index = $this->msg ["file_index"];
        $rs = $this->GetData ( "SELECT file_id FROM pd_files WHERE file_index = '" . $file_index . "' LIMIT 0,1" );
        $file_id = $rs [0] ["file_id"];
        $user = $this->GetUser ();
        $uid = $user [userid];
        if ($user [userid] > 0 && $file_id > 0)
        {
            $client_ip = get_ip ();
            
            $all = $this->_db->rsArray ( "SELECT count(*) as total FROM xz_emaillist WHERE ts > '" . date ( 'Y-m-d 00:00:00', TIMESTAMP ) . "' and client_ip = '$client_ip'" );
            $usr = $this->_db->rsArray ( "SELECT count(*) as total FROM xz_emaillist WHERE ts > '" . date ( 'Y-m-d 00:00:00', TIMESTAMP ) . "' and uid = $uid" );
            $per = $this->_db->rsArray ( "SELECT count(*) as total FROM xz_emaillist WHERE ts > '" . date ( 'Y-m-d 00:00:00', TIMESTAMP ) . "' and client_ip = '$client_ip' and fid = $file_id" );
            
            $at = intval ( $all ['total'] );
            $pt = intval ( $per ['total'] );
            $ut = intval ( $usr ['total'] );
            
            if ($pt > 0)
            {
                $this->Error ( "该资料已经发送到您邮箱，请勿重复发送。" );
            }
            if ($at > 8 || $ut > 8)
            {
                $this->Error ( "您今天发送邮件次数已经用完。" );
            }
            
            if ($email != $user [email])
            {
                $ret_update = $this->_db->update ( 'pd_users', [ 
                        'email' => $email 
                ], "userid = '$user[userid]'" );
            }
            
            $is_pay = $this->FilePayCheck ( $user [userid], $file_id );
            if ($is_pay)
            {
                SendEmailNotLogin::Main ( $file_id, $user [userid] );
                $this->operate ( $user [userid], $file_id, Xzbbm::operate_send );
                $this->ok ();
                return;
            }
            else
            {
                $this->Error ( "您未购买该资料，无法发送到您邮箱！" );
            }
        }
        $this->Error ( "参数错误" );
    }
    /**
     * 云印
     *
     * @author why
     */
    public function XZPrint()
    {
        // $user = $this->GetUser ();
        // $file_index = $this->msg ["file_index"];
        // $rs = $this->GetData ( "SELECT file_id FROM pd_files WHERE file_index = '" . $file_index . "' LIMIT 0,1" );
        // $fcode = $rs [0] ["file_id"];
        // $ret = $this->CloudPrint ( $fcode, '100001', 3, 1, 1, $user [userid] );
        // $this->Ret ( $ret );
        
        // $this->operate ( $user [userid], $fcode, xzbbm::operate_print );
        $this->Error ( "no support" );
    }
    /**
     * 下载原文件
     *
     * @author why
     */
    public function DownloadFile()
    {
        $user = $this->GetUser ();
        $file_index = $this->msg ["file_index"];
        $rs = $this->GetData ( "SELECT * FROM pd_files WHERE file_index = '" . $file_index . "' LIMIT 0,1" );
        $rs = $rs [0];
        $file_id = $rs ['file_id'];
        $is_pay = $this->FilePayCheck ( $user [userid], $file_id );
        if ($is_pay)
        { // ($rs ["is_only_platform"] == 1 && $rs ["file_extension"] != "png") ? "" :
            
            $userid = $user ['userid'];
            $operate = Xzbbm::operate_download;
            $zero = strtotime ( date ( 'Y-m-d 00:00:00', TIMESTAMP ) );
            $sql_count = "SELECT count(*) as count FROM pd_log WHERE pd_log.user_id = $userid AND operate = $operate AND log_time > $zero";
            $count = $this->_db->rsArray ( $sql_count )["count"];
            if ($count < 8)
            {
                $this->operate ( $user ['userid'], $rs ['file_id'], Xzbbm::operate_download );
                
                if ($_REQUEST ["from"] == "web")
                {
                    $token = sha1 ( TIMESTAMP . 'sNsxCrth13LGsu60' );
                    $ts = TIMESTAMP;
                    $ret ["download_addr"] = "https://api.xzbbm.cn/?action=SuperAPI&do=OutputFile&token=$token&file_index={$rs[file_index]}&ts=$ts";
                    $this->Ret ( $ret );
                }
                else
                {
                    $ret ["download_addr"] = $this->GetUrl ( array (
                            'file_real_name' => $rs ["file_real_name"],
                            'page' => '',
                            'degree' => '',
                            'timeout' => 3600,
                            'file_extension' => $rs ["file_extension"] 
                    ) );
                    $this->Ret ( $ret );
                }
            }
            else
            {
                $this->Error ( "亲，今天下载次数已经用完！" );
            }
        }
        else
        {
            $this->Error ( "您未购买该资料，无法发送到您邮箱！" );
        }
    }
    /**
     * web api 学校信息
     *
     * @author why
     */
    public function WebUniversity()
    {
        $char_1 = $this->msg ["char_1"];
        $char_2 = $this->msg ["char_2"];
        $char_3 = $this->msg ["char_3"];
        
        $where = "";
        if (! empty ( $char_1 ))
        {
            $where = "initials = '$char_1' ";
        }
        if (! empty ( $char_2 ))
        {
            $where .= " or initials = '$char_2'";
        }
        if (! empty ( $char_3 ))
        {
            $where .= " or initials = '$char_3'";
        }
        
        $ret_arr = $this->GetData ( "SELECT * FROM geo_universities,geo_provinces
						                WHERE ($where)
						                AND geo_universities.province = geo_provinces.province_id ORDER BY geo_universities.weight DESC,geo_universities.total_files DESC" );
        
        // foreach ( $arr as $key => $value )
        // {
        // $ret [$value ["pinyin"]] = $value;
        // }
        // ksort ( $ret );
        // $i = 0;
        // foreach ( $ret as $key => $value )
        // {
        // $ret_arr [$i] = $value;
        // ++ $i;
        // }
        
        $first = $ret_arr [0];
        
        $ret_college_arr = $this->GetUniversityInfo ( $first ["university_id"], '', 'college' );
        
        // foreach ( $college_list as $key => $value )
        // {
        // $ret_college_list [$value ["pinyin"]] = $value;
        // }
        // ksort ( $ret_college_list );
        // $i = 0;
        // foreach ( $ret_college_list as $key => $value )
        // {
        // $ret_college_arr [$i] = $value;
        // ++ $i;
        // }
        
        $first_college = $ret_college_arr [0];
        
        $tmpucode = $first_college ['university_id'];
        $tmpccode = $first_college ['college_id'];
        $document_list = OpenSearchFile::Main ( 'ucode', '', 5, 0, 11, $tmpucode, $tmpccode, '', '', 0, 0, 0 );
        $document_list = $this->AddInfoToSearchRetList ( $document_list );
        $total ['university'] = $ret_arr;
        $total ['college_list'] = $ret_college_arr;
        $total ['document_list'] = $document_list;
        
        $this->Ret ( $total );
    }
    /**
     * web api 选择学校/学院
     *
     * @author why
     */
    public function WebSelect()
    {
        $ucode = $this->msg ["ucode"];
        $ccode = $this->msg ["ccode"];
        $from = $this->msg ["from"];
        $limit = $this->msg ["limit"];
        if (empty ( $ccode ) && ! empty ( $ucode ))
        {
            $ret_college_arr = $this->GetUniversityInfo ( $ucode, '', 'college' );
            
            // foreach ( $college_list as $key => $value )
            // {
            // $ret_college_list [$value ["pinyin"]] = $value;
            // }
            // ksort ( $ret_college_list );
            // $i = 0;
            // foreach ( $ret_college_list as $key => $value )
            // {
            // $ret_college_arr [$i] = $value;
            // ++ $i;
            // }
            
            $first_college = $ret_college_arr [0];
            
            $tmpucode = $first_college ['university_id'];
            $tmpccode = $first_college ['college_id'];
            $document_list = OpenSearchFile::Main ( 'ucode', '', 5, $from, $limit, $tmpucode, $tmpccode, '', '', 0, 0, 0 );
            $document_list = $this->AddInfoToSearchRetList ( $document_list );
            $total ['college_list'] = $ret_college_arr;
            $total ['document_list'] = $document_list;
            
            $this->Ret ( $total );
        }
        else if (! empty ( $ccode ) && ! empty ( $ucode ))
        {
            $document_list = OpenSearchFile::Main ( 'ucode', '', 5, $from, $limit, $ucode, $ccode, '', '', 0, 0, 0 );
            $document_list = $this->AddInfoToSearchRetList ( $document_list );
            $total ['document_list'] = $document_list;
            
            $this->Ret ( $total );
        }
    }
    /**
     *
     * @author why
     */
    public function WebGetUniversity()
    {
        $uname = $this->msg ["uname"];
        $uname = trim ( $uname );
        if (empty ( $uname ))
            return;
        $uinfo = $this->GetData ( "SELECT * FROM geo_universities,geo_provinces
						                WHERE (geo_universities.name LIKE '%$uname%')
						                AND geo_universities.province = geo_provinces.province_id ORDER BY geo_universities.weight DESC,geo_universities.total_files DESC LIMIT 10 " );
        
        $this->Ret ( $uinfo );
    }
    /**
     *
     * @author why
     */
    public function WebGetCollege()
    {
        $ucode = $this->msg ["ucode"];
        $cname = $this->msg ["cname"];
        $cinfo = $this->GetData ( "SELECT * FROM geo_colleges WHERE(university_id = $ucode AND college LIKE '%$cname%') ORDER BY total DESC LIMIT 10" );
        $this->Ret ( $cinfo );
    }
    /**
     * 获取学校名／学院名／校徽
     *
     * @param
     *            $ucode
     * @param
     *            $ccode
     * @author why
     */
    public function GetSchoolInfo()
    {
        $ucode = $this->msg [ucode];
        $ccode = $this->msg [ccode];
        
        $uinfo = $this->GetData ( "SELECT geo_universities.name,geo_universities.sicon_id FROM geo_universities,geo_provinces
						                WHERE (geo_universities.university_id = $ucode)
						                AND geo_universities.province = geo_provinces.province_id" )[0];
        $ret ["uname"] = $uinfo ['name'];
        $ret ["uicon"] = "https://www.xzbbm.cn/images/sicons/" . $uinfo [sicon_id] . ".png";
        $ret ["cname"] = $this->GetNameByCcode ( $ccode );
        
        $this->Ret ( $ret );
    }
    /**
     * WEB测试手机号码是不是被使用过
     *
     * @author why
     */
    public function WebTestPhone()
    {
        $phone = $this->msg ["phone"];
        $email = $this->msg ["email"];
        $rs_phone = $this->_db->rsArray ( "SELECT * FROM pd_users WHERE phone = '$phone' LIMIT 0,1" );
        
        if (! empty ( $rs_phone ))
        {
            $this->Error ( '亲，你的手机号码已注册！' );
            return;
        }
        $this->ok ();
    }
    /**
     * WEB测试邮箱是否被使用过
     *
     * @author why
     */
    public function WebTestEmail()
    {
        $phone = $this->msg ["phone"];
        $email = $this->msg ["email"];
        if (! preg_match ( "/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i", $email ))
        {
            $this->Error ( "请键入正确的邮箱地址，推荐使用QQ或网易邮箱。" );
            return;
        }
        $rs_email = $this->_db->rsArray ( "SELECT * FROM pd_users WHERE email = '$email' LIMIT 0,1" );
        if (! empty ( $rs_email ))
        {
            $this->Error ( '亲，你的邮箱号码已注册！' );
            return;
        }
        $this->ok ();
    }
    /**
     * WEB注册
     *
     * @author why
     */
    public function WebRegister()
    {
        if (strtoupper ( $this->msg ["yzm"] ) !== strtoupper ( $_SESSION ['verifycode'] ))
        {
            // 验证码不正确
            $this->Error ( "验证码不正确" );
        }
        $phone = $this->msg ["phone"];
        $email = $this->msg ["email"];
        $password = $this->msg ["password"];
        
        // 邀请注册统计
        if ($this->msg ["xztoken"] != '')
        {
            $this->_db->Conn ( "UPDATE xz_invite SET reg_valid = (reg_valid + 1) 
                                WHERE userid = (SELECT userid FROM pd_users 
                                WHERE xztoken = '" . $this->msg ["xztoken"] . "')" );
        }
        
        if (! empty ( $email ) && ! empty ( $password ))
        {
            // $rs_phone = $this->_db->rsArray ( "SELECT * FROM pd_users WHERE phone = '$phone' LIMIT 0,1" );
            $rs_email = $this->_db->rsArray ( "SELECT * FROM pd_users WHERE email = '$email' LIMIT 0,1" );
            // if (! empty ( $rs_phone ))
            // {
            // $this->Error ( '亲，你的手机号码已注册！' );
            // return;
            // }
            if (! empty ( $rs_email ))
            {
                $this->Error ( '亲，你的邮箱号码已注册！' );
                return;
            }
            
            $rs_user = $this->CreateUser ( $password, $phone, $email, '', '', '', '' );
            $rs_user ["password"] = "";
            $this->Ret ( [ 
                    'user' => $rs_user,
                    'xztoken' => $rs_user [xztoken] 
            ] );
            $adresses = $email;
            $subject = "学长帮帮忙－邮箱验证";
            $url = "https://api.xzbbm.cn/?action=SuperAPI&do=Validity&xztoken=" . $rs_user [xztoken];
            $body = "</br>亲爱的同学，欢迎加入学长帮帮忙！</br>请点此链接完成注册流程：<br><br><a href='$url' target='_blank'>$url</a><br><br>(如链接无法点击，请复制到浏览器中打开)";
            $this->sendEmail ( '', $adresses, $subject, $body, '' );
            return;
        }
        $this->Error ( "输入信息有误" );
    }
    
    // public function TestValidiy()
    // {
    // $xztoken = $_REQUEST['xztoken'];
    // $adresses = $_REQUEST['email'];;
    // $subject = "学长帮帮忙－邮箱验证";
    // $url = "https://api.xzbbm.cn/?action=SuperAPI&do=Validity&xztoken=".$xztoken;
    // $body = "</br>亲爱的同学，欢迎加入学长帮帮忙！</br>请点此链接完成注册流程：<br><br><a href='$url' target='_blank'>$url</a><br><br>(如链接无法点击，请复制到浏览器中打开)";
    // $this->sendEmail ( '', $adresses, $subject, $body, '' );
    // }
    public function Validity()
    {
        $user = $this->GetUser ( $_REQUEST ['xztoken'] );
        if (! empty ( $user ))
        {
            $this->_db->update ( 'pd_users', [ 
                    'validity' => 1 
            ], "xztoken = '$user[xztoken]'" );
            exit ( "验证成功，欢迎使用学长帮帮忙" );
            $url = "https://xzbbm.cn/?authok";
        }
        else
        {
            $url = "https://xzbbm.cn/?authfail";
        }
        header ( "Location:" . $url );
    }
    
    /**
     * WEB登陆
     *
     * @author why
     */
    public function WebLogin()
    {
        if (strtoupper ( $this->msg ["yzm"] ) !== strtoupper ( $_SESSION ['verifycode'] ))
        {
            // 验证码不正确
            $this->Error ( "验证码不正确" );
        }
        // $phone = $this->msg ["phone"];
        $email = $this->msg ["email"];
        $password = $this->msg ["password"];
        if (! empty ( $email ) && ! empty ( $password ))
        {
            $rs_email = $this->_db->rsArray ( "SELECT * FROM pd_users WHERE email = '$email' or phone = '$email' LIMIT 0,1" ); // TODO
            
            if (! empty ( $rs_email ))
            {
                if ($rs_email ['password'] == md5 ( $password ))
                {
                    $rs_email ['password'] = "";
                    $this->Ret ( [ 
                            'user' => $rs_email 
                    ] );
                    return;
                }
            }
        }
        $this->Error ( "输入信息有误" );
    }
    /**
     * WEB更新用户信息，此接口有风险
     *
     * @author why
     */
    public function WebUpdateUser()
    {
        $user = $this->GetUser ();
        $key = $this->msg [key];
        $value = $this->msg [value];
        
        if (! empty ( $user ) && ! empty ( $key ) && ! empty ( $value ))
        {
            
            if ($key == 'useid' && $key == 'weixin_token')
            {
                
                $this->Error ( "输入信息有误" );
                return;
            }
            $this->_db->update ( 'pd_users', [ 
                    $key => $value 
            ], "xztoken = '$user[xztoken]'" );
            
            $this->ok ();
            return;
        }
        $this->Error ( "输入信息有误" );
    }
    /**
     * WEB更新文档信息，此接口有风险
     *
     * @author why
     */
    public function WebUpdateFile()
    {
        $user = $this->GetUser ();
        $file_index = $this->msg ['file_index'];
        $modify_file_res = $this->msg [modify_file_res];
        $rs_file = $this->_db->rsArray ( "select userid from pd_files where file_index = '$file_index' limit 1" );
        if ($rs_file ['userid'] != $user ['userid'])
        {
            $this->Error ( "参数错误" );
            return;
        }
        if (! empty ( $modify_file_res ))
        {
            $ret = $this->_db->update ( 'pd_files', $modify_file_res, "file_index = '$file_index'" );
            if ($ret == true)
                $this->ok ();
            else
                $this->Error ( $this->_db->_errorMsg );
            return;
        }
        $this->Error ( "参数错误" );
    }
    /**
     * WEB修改密码
     *
     * @author why
     */
    public function WebChangePassword()
    {
        $email = $this->msg ["email"];
        $temp_ip = $this->msg ["temp_ip"];
        if (! empty ( $email ))
        {
            $rs_email = $this->_db->rsArray ( "SELECT * FROM pd_users WHERE email = '$email' LIMIT 0,1" );
            if (! empty ( $rs_email ))
            {
                $reset_token = md5 ( uniqid ( mt_rand (), true ) . microtime () . '1' );
                $this->_db->update ( 'pd_users', [ 
                        'xztoken' => $reset_token 
                ], "email = '$email'" );
                
                $flag = $this->checkEmail ( $temp_ip, $email, $reset_token );
                if ($flag == true)
                {
                    $this->ok ();
                }
                else
                {
                    $this->Error ( "学长刚刚打瞌睡，请刷新后重试" );
                }
                return;
            }
        }
        $this->Error ( "该邮箱尚未注册" );
    }
    
    /**
     *
     * @todo 以随机的某个账户对某一资源进行批量群发
     * @author bo.wang
     * @version 2013-06-06 14:29
     */
    private function sendEmail($path, $adresses, $subject, $body, $f_name)
    {
        $email_account = array (
                'helper_robot_a@xzbbm.cn',
                'helper_robot_b@xzbbm.cn',
                'helper_robot_c@xzbbm.cn',
                'helper_robot_d@xzbbm.cn',
                'helper_robot_e@xzbbm.cn',
                'helper_robot_f@xzbbm.cn',
                'helper_robot_g@xzbbm.cn',
                'helper_robot_h@xzbbm.cn',
                'helper_robot_i@xzbbm.cn',
                'helper_robot_j@xzbbm.cn' 
        );
        
        shuffle ( $email_account );
        
        $mail = new PHPMailer ();
        $mail->IsHTML ( true );
        $mail->IsSMTP ();
        $mail->CharSet = 'UTF-8';
        $mail->SMTPAuth = true;
        $mail->FromName = "学长帮帮忙";
        
        // $is_qquser = false;
        
        $mail->AddAddress ( $adresses, "" ); // 以前是传入一个串email数组，现在是传入单个email
                                             // foreach ( $adresses as $data )
                                             // { // 批量添加收信人
                                             // $mail->AddAddress ( $data, "" );
                                             // // if(strstr(strtolower($data),'qq')){
                                             // // $is_qquser = true;
                                             // // }
                                             // }
                                             
        // if($is_qquser){ //QQ用户专属邮箱
                                             // $mail->Host = "smtp.qq.com";
                                             // $mail->Username = "xzbbm@vip.qq.com";
                                             // $mail->Password = "wb3108010638";
                                             // $mail->From = "xzbbm@vip.qq.com";
                                             // }else{
        $mail->Host = "smtp.ym.163.com";
        $mail->Username = $email_account [0];
        $mail->Password = "wb8221608";
        $mail->From = $email_account [0];
        // }
        
        $curdir = dirname ( __FILE__ );
        if (! empty ( $path ) && ! empty ( $f_name ))
        {
            $mail->AddAttachment ( $path, $f_name );
        }
        
        $mail->Subject = $subject;
        $mail->Body = <<<HTML
    	<div class="" id="qm_con_body"><div id="mailContentContainer" class="qmbox qm_con_body_content"><style>
    	.mmsgLetter				{ 	width:580px;margin:0 auto;padding:10px;color:#333;background:#fff;border:0px solid #aaa;border:1px solid #aaa\9;border-radius:5px;-webkit-box-shadow:3px 3px 10px #999;-moz-box-shadow:3px 3px 10px #999;box-shadow:3px 3px 10px #999;font-family:Verdana, sans-serif; }
    	.mmsgLetter a:link,
    	.mmsgLetter a:visited 	{	color:#407700; }
    	.mmsgLetterContent 		{	text-align:left;padding:30px;font-size:14px;line-height:1.5;
    	background:url(http://weixin.qq.com/zh_CN/htmledition/images/weixin/letter/mmsgletter_2_bg_wmark.jpg) no-repeat top right; }
    	.mmsgLetterContent h3	{ 	color:#000;font-size:20px;font-weight:bold;
    	margin:20px 0 20px;border-top:2px solid #eee;padding:20px 0 0 0;
    	font-family:"微软雅黑","黑体", "Lucida Grande", Verdana, sans-serif;}
    	.mmsgLetterContent p 	{	margin:20px 0;padding:0; }
    	.mmsgLetterContent .salutation 		{ font-weight:bold;}
    	.mmsgLetterContent .mmsgMoreInfo 	{ }
    	.mmsgLetterContent a.mmsgButton	 	{	display:block;float:left;height:40px;text-decoration:none;text-align:center;cursor:pointer;}
    	.mmsgLetterContent a.mmsgButton	span 	{	display:block;float:left;padding:0 25px;height:40px;line-height:36px;font-size:14px;font-weight:bold;color:#fff;text-shadow:1px 0 0 #235e00;}
    	
    	.mmsgLetterContent a.mmsgButton:link,
    	.mmsgLetterContent a.mmsgButton:visited { background:#338702 url(http://weixin. qq.com/zh_CN/htmledition/images/weixin/letter/mmsgletter_2_btn.png) no-repeat right -40px; }
    	
    	.mmsgLetterContent a.mmsgButton:link span,
    	.mmsgLetterContent a.mmsgButton:visited span { background:url(http://weixin.qq.com/zh_CN/htmledition/images/weixin/letter/mmsgletter_2_btn.png) no-repeat 0 0; }
    	
    	.mmsgLetterContent a.mmsgButton:hover,
    	.mmsgLetterContent a.mmsgButton:active { background:#338702 url(http://weixin.qq.com/zh_CN/htmledition/images/weixin/letter/mmsgletter_2_btn.png) no-repeat right -120px; }
    	
    	.mmsgLetterContent a.mmsgButton:hover span,
    	.mmsgLetterContent a.mmsgButton:active span { background:url(http://weixin.qq.com/zh_CN/htmledition/images/weixin/letter/mmsgletter_2_btn.png) no-repeat 0 -80px; }
    	
    	.mmsgLetterInscribe 	{	padding:40px 0 0;}
    	.mmsgLetterInscribe .mmsgAvatar	{	float:left; }
    	.mmsgLetterInscribe .mmsgName	{ margin:0 0 10px; }
    	.mmsgLetterInscribe .mmsgSender	{ margin:0 0 0 54px;}
    	.mmsgLetterInscribe .mmsgInfo	{ font-size:12px;margin:0;line-height:1.2; }
    	
    	.mmsgLetterHeader		{	height:23px;background:url(https://www.xzbbm.cn/images/mmsgletter_2_bg_topline.png) repeat-x 0 0; }
    	.mmsgLetterFooter 		{	margin:16px;text-align:center;font-size:12px;color:#888;
    	text-shadow:1px 0px 0px #eee;}
    	.mmsgLetterClr { clear:both;overflow:hidden;height:1px; }
    	
    	
    	.mmsgLetterUser { padding:10px 0; }
    	.mmsgLetterUserItem { padding:0 0 20px 0;}
    	.mmsgLetterUserAvatar { height:40px;border:1px solid #ccc;padding:2px;display:block;float:left; }
    	.mmsgLetterUserAvatar img { width:40px;height:40px; }
    	.mmsgLetterInfo { margin-left:48px; }
    	.mmsgLetterName { display:block;color:#5fa207;font-weight:bold;margin-left:10px; }
    	.mmsgLetterDesc { font-size:12px;float:left;height:43px;background:url(http://weixin.qq.com/zh_CN/htmledition/images/weixin/letter/mmsgletter_chat_right.gif) no-repeat right top; }
    	.mmsgLetterDesc div{ white-space:nowrap;float:left;height:43px;padding:0 20px;line-height:40px;background:url(http://weixin.qq.com/zh_CN/htmledition/images/weixin/letter/mmsgletter_chat_left.gif) no-repeat left top; }
    	
    	.mmsgLetterUser {}
    	.mmsgLetterAvatar { float:left;}
    	.mmsgLetterInfo { margin:0 0 0 60px; }
    	.mmsgLetterNickName { font-size:14px;font-weight:bold;}
    	.mmsgLetterUin { font-size:12px;color:#666;}
    	
    	.mmsgLetterUser { padding:10px 0; }
    	.mmsgLetterUserItem { padding:0 0 20px 0;}
    	.mmsgLetterUserAvatar { height:40px;border:1px solid #ccc;padding:2px;display:block;float:left; }
    	.mmsgLetterUserAvatar img { width:40px;height:40px; }
    	.mmsgLetterInfo { margin-left:48px; }
    	.mmsgLetterName { display:block;color:#5fa207;font-weight:bold;margin-left:10px;padding-top:10px; }
    	.mmsgLetterDesc { font-size:12px;float:left;height:43px;background:url(http://weixin.qq.com/zh_CN/htmledition/images/weixin/letter/mmsgletter_chat_right.gif) no-repeat right top; }
    	.mmsgLetterDesc div{ white-space:nowrap;float:left;height:43px;padding:0 20px;line-height:40px;background:url(http://weixin.qq.com/zh_CN/htmledition/images/weixin/letter/mmsgletter_chat_left.gif) no-repeat left top; }
    	
    	</style>
    	
    	<div style="background-color:#d0d0d0;background-image:url(http://weixin.qq.com/zh_CN/htmledition/images/weixin/letter/mmsgletter_2_bg.png);text-align:center;padding:40px;">
    	<div class="mmsgLetter" style="width:580px;margin:0 auto;padding:10px;color:#333;background-color:#fff;border:0px solid #aaa;border-radius:5px;-webkit-box-shadow:3px 3px 10px #999;-moz-box-shadow:3px 3px 10px #999;box-shadow:3px 3px 10px #999;font-family:Verdana, sans-serif; ">
    	
    	<div class="mmsgLetterHeader" style="height:23px;background:url(https://www.xzbbm.cn/images/mmsgletter_2_bg_topline.png) repeat-x 0 0;">
    		
    	</div>
    	<div class="mmsgLetterContent" style="text-align:left;padding:30px;font-size:14px;line-height:1.5;background:url(https://www.xzbbm.cn/images/stamp.png?v=4) no-repeat top right;">
    	
    	<div>
        <br>         
        $body
    	
		<div class="mmsgLetterInscribe" style="padding:40px 0 0;">
		<img class="mmsgAvatar" width="55px" src="https://www.xzbbm.cn/images/xiaoming.png" style="float:left;">
		<div class="mmsgSender" style="margin:0 0 0 54px;">
		<p class="mmsgName" style="margin:0 0 10px;">Xiao Ming</p>
		<p class="mmsgInfo" style="font-size:12px;margin:0;line-height:1.2;">
		产品经理 (Product Manager)<br>
		<a href="mailto:feedback@xiaoming-inc.com" style="color:#407700;" target="_blank">feedback@xiaoming-inc.com</a>
		</p>
		</div>
		</div>
		</div>

		<div class="mmsgLetterFooter" style="margin:16px;text-align:center;font-size:12px;color:#888;text-shadow:1px 0px 0px #eee;">

		<img src="http://s95.cnzz.com/z_stat.php?id=1254673635&web_id=1254673635" style="width:0px;height:0px;">
		</div>
		</div>
	
	
		</div>
    	</div>
                </div>
HTML;
        if (false === $mail->Send ())
        {
            return false;
        }
        return true;
        
        /*
         * <h3 style="font-size: 1.17em; font-weight: bold; margin: 1.17em 0px; color: rgb(0, 0, 0); font-family: 'lucida Grande', Verdana; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 23px; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px;"> 您的注册信息：</h3> <p style="line-height: 23px; color: rgb(0, 0, 0); font-family: 'lucida Grande', Verdana; font-size: 14px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px;"> 账号：xzbbm.duoshuo.com</p> <p style="line-height: 23px; color: rgb(0, 0, 0); font-family: 'lucida Grande', Verdana; font-size: 14px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px;"> <span lang="zh-cn">默认密码</span>：xzbbm</p> <p style="line-height: 23px; color: rgb(0, 0, 0); font-family: 'lucida Grande', Verdana; font-size: 14px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px;"> 我们注意到你的默认密码还没有进行修改，为了你的隐私安全得到保障，我们强烈建议你立即修改默认密码并请妥善保存。</p>
         */
    }
    /**
     * 重置密码时发送验证邮件
     *
     * @param
     *            $temp_ip
     * @param
     *            $adresses
     * @param
     *            $reset_token
     * @author why
     */
    function checkEmail($temp_ip, $adresses, $reset_token)
    {
        $subject = "学长帮帮忙-密码找回";
        $body = "</br>请点击以下链接重新设置您的密码<br><br><a href='https://xzbbm.cn/?%22reset_token%22:%22$reset_token%22' target='_blank'>https://xzbbm.cn/?%22reset_token%22:%22$reset_token%22</a><br><br>(如链接无法点击，请复制到浏览器中打开)";
        
        return $this->sendEmail ( '', $adresses, $subject, $body, '' );
    }
    
    /**
     * 返回时光机的数据
     *
     * @author why
     */
    public function timeMachineInfo()
    {
        $userid = $this->GetUser ()['userid'];
        $operate = $this->msg ['operate'];
        $start = $this->msg ['start'];
        $num = $this->msg ['num'];
        $all = - 1;
        // LEFT JOIN xz_cloudprint ON cloudprint_task.file_id = pd_log.file_id AND cloudprint_task.userid = pd_log.user_id
        if ($operate == $all)
        { // 发送 下载 云印
            $sql = "SELECT *
            FROM pd_files,pd_log
            WHERE pd_log.user_id = $userid AND pd_log.operate < 3 AND pd_files.file_id = pd_log.file_id
            ORDER BY log_time DESC LIMIT $start,$num";
        } // LEFT JOIN xz_cloudprint ON cloudprint_task.file_id = pd_log.file_id AND cloudprint_task.userid = pd_log.user_id
        else
        {
            $sql = "SELECT *
            FROM pd_files,pd_log
            WHERE pd_log.user_id = $userid AND operate = $operate AND pd_files.file_id = pd_log.file_id
            ORDER BY log_time DESC LIMIT $start,$num";
        }
        
        $rs = $this->GetData ( $sql );
        $this->Ret ( [ 
                "timeMachineList" => $rs 
        ] );
    }
    
    /**
     * 测试二维码接口
     *
     * @author why
     */
    public function OutputQr()
    {
        $type = $_REQUEST ["type"]; // file user pay print printret
        $param = $_REQUEST ["param"]; // file_key userid order_id
        $size = $_REQUEST ["size"];
        if (empty ( $param ))
            $data = "https://app.xzbbm.cn";
        else
            $data = "https://xzbbm.cn/$param?$type";
        $key = md5 ( uniqid ( mt_rand (), true ) . microtime () . '1' );
        $path = $this->GetQr ( $data, $key, false );
        header ( "Content-Type:image/png" );
        $imgSource = imagecreatefrompng ( $path );
        imagepng ( $imgSource );
        unlink ( $path );
    }
    /**
     *
     * @todo 生成验证码
     * @author bo.wang3
     * @version 2013-4-22 14:29
     */
    public function GVerifyCode()
    {
        $v = new VerifyCode ( 100, 30, 4 ); // 实列化，默认根据字符大小产生图像，产生5个字符
        $v->ZH = false; // 打开中文验证码开关
        $v->bg_alpha = 127; // 背景全透明，注意IE6不支持png透明，需写hack
        $_SESSION ['verifycode'] = $v->show (); // 字符串写入session，并得到返回的验证字串
    }
    /**
     * 分享订阅号
     *
     * @author why
     */
    public function ShareUser()
    {
        $url = "https://app.xzbbm.cn/";
        header ( "Location:" . $url );
    }
    /**
     * 云印价格计算
     *
     * @author why
     */
    public function GetPrintPrice()
    {
        $file_index = $this->msg ["file_index"];
        $file = $this->GetData ( "SELECT * FROM pd_files WHERE file_real_name = '" . $file_index . "' LIMIT 0,1" )[0];
        $user = $this->GetUser ();
        $user_id = $user [userid];
        $file_id = $file ["file_id"];
        
        $is_duplex = $this->msg [duplex] == 0 ? false : true; // 0单面 1双面
        $total = $this->msg [total]; // 份数
        
        $ret = $this->GetNormalPrice ( $file, $is_duplex, $total );
        if (! empty ( $ret ))
        {
            $this->Ret ( $ret );
            return;
        }
        $this->Error ( "参数错误" );
    }
    
    /**
     */
    public function OutputFile()
    {
        if (! isset ( $_REQUEST ['ts'] ) || ! isset ( $_REQUEST ['token'] ))
        {
            go_win ( 1, '访问参数不完整。' );
            exit ();
        }
        
        if ((TIMESTAMP - $_REQUEST ['ts']) > 5 || sha1 ( $_REQUEST ['ts'] . 'sNsxCrth13LGsu60' ) != $_REQUEST ['token'])
        {
            go_win ( 1, "请刷新页面后重试！。C_ts:$_REQUEST[ts] C_token:$_REQUEST[token] S_ts:" . TIMESTAMP . " S_token:" . sha1 ( $_REQUEST ['ts'] . 'sNsxCrth13LGsu60' ) );
            exit ();
        }
        
        @set_time_limit ( 0 );
        @ignore_user_abort ( true );
        
        $rs = $this->GetData ( "select file_real_name,file_name,file_extension from pd_files where file_index = '{$_REQUEST['file_index']}' limit 0,1" );
        $rs = $rs [0];
        
        // 从云端取回文件
        $tmp_path = get_object ( $this->_oss, "{$rs['file_real_name']}.{$rs['file_extension']}" );
        
        if (file_exists ( $tmp_path ))
        {
            
            // 输出开始
            ob_end_clean ();
            $interval = 6000;
            header ( 'Cache-Control: public' );
            header ( 'Last-Modified: ' . gmdate ( 'r', TIMESTAMP ) );
            header ( 'Expires: ' . gmdate ( 'r', (TIMESTAMP + $interval) ) );
            header ( 'Cache-Control: max-age=' . $interval );
            header ( 'Content-Length: ' . filesize ( $tmp_path ) );
            header ( "Content-Type: " . $this->mimetypes [$rs ['file_extension']] );
            header ( 'Content-Disposition: inline;' );
            header ( "Content-Disposition: attachment;filename=" . urlencode ( "{$rs['file_name']}.{$rs['file_extension']}" ) );
            header ( 'Content-Transfer-Encoding: binary' );
            
            $chunk = 6000; // 下载速度控制
            $sent = 0;
            
            if (($fp = @fopen ( $tmp_path, 'rb' )) === false)
                exit ( 'Can not open file!' );
            
            do
            {
                $buf = fread ( $fp, $chunk );
                $sent += strlen ( $buf );
                echo $buf;
                ob_flush ();
                flush ();
                if (strlen ( $buf ) == 0)
                {
                    break;
                }
            }
            while ( true );
            
            @fclose ( $fp );
        }
    }
    
    /**
     * 获得服务器时间戳
     *
     * @author why
     */
    public function GetServerTs()
    {
        $this->Ret ( array (
                'now' => ( string ) TIMESTAMP 
        ) );
    }
    
    /**
     * 根据学校或学院名称返回其编号
     *
     * @author wangbo
     */
    public function GetUCcodeByName()
    {
        $uname = $this->msg ["uname"];
        $cname = $this->msg ["cname"];
        
        $ucode = $this->GetData ( "SELECT university_id FROM geo_universities WHERE name = '$uname'" )[0]['university_id'];
        $ccode = $this->GetData ( "SELECT college_id
                                 FROM geo_colleges,geo_universities
                                 WHERE geo_universities.name = '$uname'
                                 AND geo_colleges.college = '$cname'
                                 AND geo_universities.university_id = geo_colleges.university_id" )[0]['college_id'];
        
        $this->Ret ( array (
                'ccode' => $ccode ? $ccode : - 1,
                'ucode' => $ucode ? $ucode : - 1 
        ) );
    }
}