<?php
require_once (dirname ( __FILE__ ) . '/app.InterFaces.php');
require_once (str_replace ( '/app', '/include', dirname ( __FILE__ ) ) . '/class.OpenSearchFile.php');

if (! defined ( 'IN_SYS' ))
{
    header ( "HTTP/1.1 404 Not Found" );
    die ();
}
class TestAPI extends Xzbbm
{
    public function __construct()
    {
        parent::__construct ();
    }
    public function GetMyHomePageInfo()
    {
        $user = $this->GetUser ( $msg ["xztoken"] );
        /*
         * if (empty ( $user ["user_name"] )) if (! empty ( $user ["phone"] )) $rs ["username"] = $user ["phone"]; else $rs ["username"] = $user ["email"]; else
         */
        $rs ["username"] = $user ["user_name"];
        $rs ["phone"] = $user ["phone"];
        $rs ["email"] = $user ["email"];
        $rs ["payAccount"] = $user ["pay_account"];
        $ucode = $user ["ucode"];
        $ccode = $user ["ccode"];
        if ($ucode == 0 && $ccode == 0)
        {
            $this->Error ( "ucode and ccode is null." );
            return;
        }
        else
        {
            $sql = "SELECT name FROM geo_universities WHERE university_id = $ucode";
            // echo $sql . "</br>";
            $rs ["university"] = $this->_db->rsArray ( $sql )["name"];
            $sql = "SELECT college FROM geo_colleges WHERE college_id = $ccode";
            // echo $sql . "</br>";
            $rs ["college"] = $this->_db->rsArray ( $sql )["college"];
        }
        
        // 查出推送数目
        $sql = "SELECT push_num FROM pd_subscribe WHERE user_id = {$user['userid']} AND push_setting != 0";
        $push_num = $this->_db->dataArray ( $sql );
        for($i = 0; $i < count ( $push_num ); $i ++)
        {
            $rs ["mySubscribeNews"] += ( int ) $push_num [$i] ["push_num"];
        }
        
        $sql = "SELECT * FROM pd_files ORDER BY file_time DESC LIMIT 1";
        $file = $this->_db->rsArray ( $sql );
        
        if (empty ( $file ["user_name"] ))
        {
            $sql = "SELECT * FROM pd_users WHERE userid = {$file["userid"]}";
            $lastUser = $this->_db->rsArray ( $sql );
            $rs ["lastUsername"] = $this->assignmentUserame ( $lastUser );
        }
        else
            $rs ["lastUsername"] = $file ["user_name"];
        $rs ["lastFileTitle"] = $file ["file_name"];
        $rs ["lastUpdateTime"] = $this->dateChange ( $file ["file_time"] );
        $rs ["myAllMoneny"] = $user ["profile_income"];
        $rs ["fileId"] = $file ["file_id"];
        echo $rs;
        $this->Ret ( [ 
                "homePageInfo" => $rs 
        ] );
    }
    public function GetPrintInfo()
    {
        $this->Ret ( [ 
                "FileName" => "2002年中央、国家机关公务员录用考试 《行政职业能力测验(B类)》试卷 ",
                "UserName" => "小明",
                "UserPhone" => "12312344",
                "DownloadUrl" => "http://oss.xzbbm.cn/85689388d2495126389969e3b47dadec.pdf",
                "TipsID" => 1111,
                "PagesFrom" => 1,
                "PagesTo" => 10,
                "Copies" => 2,
                "Dulpex" => 0,
                "Color" => 0 
        ] );
    }
    public function TestRet()
    {
        $channel = $this->msg ['channel'];
        $amount = $this->msg ['amount'];
        $orderNo = substr ( md5 ( time () ), 0, 12 );
        
        // $extra 在渠道为 upmp_wap 和 alipay_wap 时，需要填入相应的参数，具体见技术指南。其他渠道时可以传空值也可以不传。
        $extra = array ();
        switch ($channel)
        {
            case 'alipay' :
                $extra = array (
                        'success_url' => 'http://www.baidu.com',
                        'cancel_url' => 'http://www.taobao.com' 
                );
                break;
            case 'upmp_wap' :
                $extra = array (
                        'result_url' => 'http://blog.csdn.net' 
                );
                break;
        }
        
        \Pingpp\Pingpp::setApiKey ( 'sk_test_rfjvHGrXTerP0q10yHv18y1K' );
        $this->say ( "this is my key : " . (\Pingpp\Pingpp::getApiKey ()) );
        
        $this->say ( "show the params : " . "  amount:" . $amount . "   orderNo:" . $orderNo . "   extra:" . $extra . "  channel:" . $channel . "  client_ip:" . $_SERVER ["REMOTE_ADDR"] );
        
        try
        {
            $ch = \Pingpp\Charge::create ( array (
                    "subject" => "充值",
                    "body" => "充值",
                    "amount" => $amount,
                    "order_no" => $orderNo,
                    "currency" => "cny",
                    "extra" => $extra,
                    "channel" => $channel,
                    "client_ip" => $_SERVER ["REMOTE_ADDR"],
                    "app" => array (
                            "id" => "app_eXHevDLiLq9SSC0K" 
                    ) 
            ) );
            
            $this->say ( "this is ok" );
            echo $ch;
        }
        catch ( \Pingpp\Error\Base $e )
        {
            $this->say ( "this is my exception" );
            
            header ( 'Status: ' . $e->getHttpStatus () );
            echo ($e->getHttpBody ());
        }
        
        $this->say ( "this is OVER" );
    }
    public function EnCode()
    {
        $encode = $this->authcode ( "this is check email number", "ENCODE", "key", 3600 );
        echo "the check email number is " . $encode . "</br>";
        $encode = rawurlencode ( $encode );
        echo "the check email number is url " . $encode . "</br>";
        echo "</br></br></br>http://112.124.50.239/?action=TestAPI&do=DeCode&debug=on&dg=ml&msg={%22encode%22:%22$encode%22}";
    }
    public function DeCode()
    {
        $encode = rawurldecode ( $this->msg ['encode'] );
        $this->say ( "this is dealed by rawurlde :" . $encode );
        $decode = $this->authcode ( $encode, "DECODE", "key" );
        $this->say ( "jiemizhihou  :" . $decode );
        $update ["validity"] = 1;
        if ($decode == "this is check email number")
        {
            $success = $this->_db->update ( "pd_users", $update, "phone='18819444095'" );
            
            echo "<script>setTimeout(\"this.location='http://www.xzbbm.cn'\",1000);</script> 
              <p align=\"center\"; valign=\"center\">邮箱验证成功，页面正在跳转......</p>";
            
            // header("Location: http://www.xzbbm.cn");//header前面不能有输出语句
        }
        else
        {
            echo "<script>setTimeout(\"this.location='http://www.xzbbm.cn'\",1000);</script> 
              <p align=\"center\"; valign=\"center\">密文解析失败，页面正在跳转......</p>";
        }
    }
    function checkEmail()
    {
        $interFaces = new InterFaces ();
        $str = "checkEmail is coming!";
        file_put_contents ( "/data/backend_service/log/testapi.log", "{$this->file_store_path} $str \n", FILE_APPEND );
        $this->say ( $str );
        $sql = "SELECT validity FROM pd_users WHERE userid = 21331";
        $validity = $this->_db->dataArray ( $sql );
        foreach ( $validity as $k => $v )
        {
            if ($v ['validity'] == 0)
            {
                $encode = $this->authcode ( "this is check email number", "ENCODE", "key", 3600 );
                $encode = rawurlencode ( $encode );
                $ts = date ( 'Y年m月d日', time () );
                $subject = "验证邮箱";
                $adresses = array (
                        '709865568@qq.com',
                        '707558952@qq.com',
                        '673426762@qq.com' 
                );
                $body = <<<HTML
        <p style="line-height: 23px; color: #4C4C4C; font-family: '微软雅黑','Microsoft YaHei','lucida Grande', Verdana; font-size: 17px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px;font-weight: bold;">
亲爱的709865568@qq.com，欢迎你加入学长帮帮忙大家庭，请点击&nbsp; <a target="_blank" style="outline: none; text-decoration: none; cursor: pointer; color: rgb(44, 74, 119);" href="http://112.124.50.239/?action=TestAPI&do=DeCode&debug=on&msg={%22encode%22:%22$encode%22}"><font size="5" color="#000099"><u><strong>此处</strong></u></font></a> &nbsp;以验证邮箱。<font size="2" color="#990000">( 此验证邮件1小时内有效 )</font><br><br><br><br><br><br>我们致力于打造一个免费、开放、简洁的校内资料分享平台。</p>
<h3 style="font-size: 19px; font-weight: bold; margin: 1.17em 0px; color: #009966; font-family: 'lucida Grande', Verdana; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 23px; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px">
在这里你可以简单而方便的：</h3>
<ol style="padding: 8px; color: #333333; font-family: 'lucida Grande', Verdana; font-size: 15px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 23px; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px;">
	<li style="list-style: decimal inside; margin: 0px; padding: 0px;">
    利用709865568&@qq.comPC、手机浏览器、APP客户端浏览、检索、免费获取全国<font color="#FF8C05">3000+</font>所各大高校海量校内资料；</li>
	<li style="list-style: decimal inside; margin: 0px; padding: 0px;">
    使用709865568&@qq&.com微博、微信、学长客户端通过扫描资料二维码方便而简洁的拷贝和传播资料，分享你的见解；</li>
	<li style="list-style: decimal inside; margin: 0px; padding: 0px;">
    上传709865568@qq&.com身边资料并参加收益分享计划，从每一次予人玫瑰的行为中获取收益的同时传播知识，方便他人。</li>
</ol>
<h3 style="font-size: 19px; font-weight: bold; margin: 1.17em 0px; color: #009966; font-family: 'lucida Grande', Verdana; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 23px; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px">
以下方式都可以很快找到我们：</h3>
<blockquote style="color: #4C4C4C; font-family: 'lucida Grande', Verdana; font-size: 14px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 23px; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px;">
	新浪微博：&nbsp;&nbsp; 
	<a target="_blank" style="outline: none; text-decoration: none; cursor: pointer; color: rgb(44, 74, 119);" href="http://weibo.com/xuezhangbangbangmang">@<font color="#2C4A77"><span lang="zh-cn">生机博博_Miracle</span></font></a><br>
	客服邮箱：&nbsp;&nbsp; <a target="_blank" style="outline: none; text-decoration: none; cursor: pointer; color: rgb(44, 74, 119);" href="mailto:cs@xzbbm.cn">cs@xzbbm.cn</a><br>
	微信公众号：超级学霸<br>
</blockquote>
<p style="line-height: 23px; color: #CC0033; font-weight:bold ;font-family: 'lucida Grande', Verdana; font-size: 14px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px;">
如果你有任何建议、想法或者对本资料的标题、内容、版权具有疑义，请直接与我们联系，感谢你的支持与关注！</p>
<h3 style="font-size: 19px; font-weight: bold; margin: 1.17em 0px; color: #009966; font-family: 'lucida Grande', Verdana; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 23px; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px">
中国好学长，学长帮帮忙。</h3>
<p style="line-height: 23px; color: #4C4C4C; font-family: 'lucida Grande', Verdana; font-size: 14px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px;">
学长帮帮忙团队（www.xzbbm.cn） $ts </p>
HTML;
                $interFaces->sendEmail ( '', $adresses, $subject, $body );
            }
        }
    }
    function authcode($string, $operation = 'DECODE', $key = '', $expiry = 3600)
    {
        $ckey_length = 4;
        // 随机密钥长度 取值 0-32;
        // 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
        // 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
        // 当此值为 0 时，则不产生随机密钥
        
        $key = md5 ( $key ? $key : 'key' ); // 这里可以填写默认key值
        $keya = md5 ( substr ( $key, 0, 16 ) );
        $keyb = md5 ( substr ( $key, 16, 16 ) );
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr ( $string, 0, $ckey_length ) : substr ( md5 ( microtime () ), - $ckey_length )) : '';
        
        $cryptkey = $keya . md5 ( $keya . $keyc );
        $key_length = strlen ( $cryptkey );
        
        $string = $operation == 'DECODE' ? base64_decode ( substr ( $string, $ckey_length ) ) : sprintf ( '%010d', $expiry ? $expiry + time () : 0 ) . substr ( md5 ( $string . $keyb ), 0, 16 ) . $string;
        $string_length = strlen ( $string );
        
        $result = '';
        $box = range ( 0, 255 );
        
        $rndkey = array ();
        for($i = 0; $i <= 255; $i ++)
        {
            $rndkey [$i] = ord ( $cryptkey [$i % $key_length] );
        }
        
        for($j = $i = 0; $i < 256; $i ++)
        {
            $j = ($j + $box [$i] + $rndkey [$i]) % 256;
            $tmp = $box [$i];
            $box [$i] = $box [$j];
            $box [$j] = $tmp;
        }
        
        for($a = $j = $i = 0; $i < $string_length; $i ++)
        {
            $a = ($a + 1) % 256;
            $j = ($j + $box [$a]) % 256;
            $tmp = $box [$a];
            $box [$a] = $box [$j];
            $box [$j] = $tmp;
            $result .= chr ( ord ( $string [$i] ) ^ ($box [($box [$a] + $box [$j]) % 256]) );
        }
        
        if ($operation == 'DECODE')
        {
            if ((substr ( $result, 0, 10 ) == 0 || substr ( $result, 0, 10 ) - time () > 0) && substr ( $result, 10, 16 ) == substr ( md5 ( substr ( $result, 26 ) . $keyb ), 0, 16 ))
            {
                return substr ( $result, 26 );
            }
            else
            {
                return '';
            }
        }
        else
        {
            return $keyc . str_replace ( '=', '', base64_encode ( $result ) );
        }
    }
    public function countSchoolFile()
    {
        // $sql = "SELECT university_id , name FROM geo_universities";
        // $schoolInfo = $this->_db->dataArray($sql);
        // foreach ($schoolInfo as $k => $v){
        // $school_id = $v["university_id"];
        // $sql = "SELECT COUNT(file_id) as sum FROM pd_files WHERE ucode = $school_id";
        // $sum = $this->_db->rsArray($sql);
        
        // $result[$k]['sum'] = $sum['sum'];
        // $result[$k]['name'] = $v['name'];
        
        // }
        
        // print_r($result);
        $sql = "SELECT p.province,u.name,count(*) as total FROM pd_files f , geo_universities u , geo_provinces p WHERE f.ucode = u.university_id AND u.province = p.province_id GROUP BY u.name ORDER BY p.province";
        $info = $this->_db->dataArray ( $sql );
        foreach ( $info as $k => $v )
        {
            $province = $v ['province'];
            $name = $v ['name'];
            $total = $v ['total'];
            echo "$province\t$name\t$total</br>";
        }
    }
    public function GetDocumentInfo()
    {
        // public int file_id;
        // public int file_time;// 发布时间，以秒计算
        
        // public int file_downs;// 下载次数
        // public int file_views;// 浏览次数
        // public int good_count;// 好评次数
        
        // public String qrcode_str;// 资料二维码下载地址
        // public String download_addr;// 分享链接的地址
        // public int is_only_platform;//是否仅在平台内浏览，如果是，那么发送到邮箱及下载原文件这两个操作是被禁止的
        // public String file_extension;// 后缀，标示是doc、excel、ppt
        // public String file_name;// 文件名
        // public String file_info;// 文件详情
        // public ArrayList<String> viewImageUrlList = new ArrayList<String>();// 可见的资料图片下载地址
        
        // public int pay_type;// 付费类型，tip，0可以代金券，1现金支付
        // public String user_name;// 发布者的名称
        // public String user_description;// 发布者的自我介绍
        // public String user_icon_url;// 发布者的头像下载地址
        // public String userid;//发布者的ID
        // public float price;// 原始价格
        // public float today_price;// 当前价格
        // public int yaohe_length;// 吆喝时间长
        // public String yaohe_url;// 吆喝下载地址
        // public String uname;//学校名称
        // public String cname;//学院名称
        
        // public String user_action_str;// 身边栏目中显示用，格式 小明同学 55分钟前 发布了
        $file_id = $this->msg ["file_id"];
        $rs = $this->_db->rsArray ( "select * from pd_files where file_id = '$file_id' limit 1" );
        $ret ["file_id"] = $rs ["file_id"];
        $ret ["file_time"] = $rs ["file_time"];
        $ret ["file_downs"] = $rs ["file_downs"];
        $ret ["file_views"] = $rs ["file_views"];
        $ret ["good_count"] = $rs ["good_count"];
        $ret ["qrcode_str"] = "http://www.xzbbm.cn/?action=QrCodes&do=GcQr&fid={$rs["file_id"]}";
        $ret ["download_addr"] = ($rs ["is_only_platform"] == 1 && $rs ["file_extension"] != "png") ? "" : $this->GetUrl ( array (
                'file_real_name' => $rs ["file_real_name"],
                'page' => '',
                'degree' => '',
                'timeout' => 3600,
                'file_extension' => $rs ["file_extension"] 
        ) );
        $ret ["is_only_platform"] = $rs ["is_only_platform"];
        $ret ["file_extension"] = $rs ["file_extension"];
        $ret ["file_name"] = $rs ["file_name"];
        $ret ["file_info"] = $rs ["file_info"];
        
        $list = [ ];
        for($x = 0; $x < $rs ["has_png"]; $x ++)
        {
            $url = $this->GetUrl ( array (
                    'file_real_name' => $rs ["file_real_name"],
                    'page' => "$x",
                    'degree' => '',
                    'timeout' => 3600,
                    'file_extension' => "png" 
            ) );
            array_push ( $list, $url );
        }
        $ret ["viewImageUrlList"] = $list;
        
        $ret ["pay_type"] = $rs ["pay_type"];
        $ret ["user_name"] = $rs ["user_name"];
        $ret ["user_description"] = $rs ["user_description"];
        $ret ["userid"] = $rs ["userid"];
        $ret ["price"] = $rs ["price"];
        $ret ["today_price"] = $rs ["today_price"];
        $ret ["yaohe_length"] = $rs ["yaohe_length"];
        if ($ret ["yaohe_length"] > 0)
        {
            $ret ["yaohe_url"] = $this->GetUrl ( array (
                    'file_real_name' => $rs ["file_real_name"],
                    'page' => '',
                    'degree' => '',
                    'timeout' => 10,
                    'file_extension' => "amr" 
            ) );
        }
        else
        {
            $ret ["yaohe_url"] = "";
        }
        
        $ret ["uname"] = $rs ["uname"];
        $ret ["cname"] = $rs ["cname"];
        $uid = $rs ["ucode"];
        $ccode = $rs ["ccode"];
        
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
        if (empty ( $ret ["user_name"] ))
        {
            $user = $this->_db->rsArray ( "SELECT * FROM pd_users WHERE userid = '{$ret["userid"]}' LIMIT 0,1" );
            $ret ["user_name"] = $user ["user_name"];
            $ret ["user_description"] = $user ["user_description"];
            if (empty ( $ret ["user_name"] ))
            {
                if (! empty ( $user ["phone"] ))
                {
                    $ret ["user_name"] = ($user ["phone"]);
                }
                else
                {
                    $ret ["user_name"] = ($user ["email"]);
                }
            }
        }
        $ret ["comment_list"] = $this->GetComments ();
        
        // $ret["user_action_str"] =
        $this->Ret ( [ 
                "document" => $ret 
        ] );
    }
    public function updateInfo()
    {
        // //uname
        // $sql = "SELECT DISTINCT(f.ucode),u.name FROM geo_universities u,pd_files f WHERE u.university_id = f.ucode limit 200,100";
        // $info = $this->_db->dataArray($sql);
        // foreach ($info as $k => $v){
        // $update['uname'] = $v['name'];
        
        // $ucode = $v['ucode'];
        
        // $this->_db->update("pd_files",$update,"ucode = $ucode");
        // }
        // echo "10000finish";
        
        // //user_name
        // $sql = "SELECT DISTINCT(email),phone,f.userid FROM pd_users u , pd_files f WHERE u.userid = f.userid ";
        // $info = $this->_db->dataArray($sql);
        // foreach ($info as $k => $v){
        // $update ['user_name'] = $v['phone']=='' ? $v['email'] : $v['phone'];
        // $userid = $v['userid'];
        // $this->_db->update("pd_files",$update,"userid = $userid");
        
        // }
        
        // price
        $sql = "SELECT DISTINCT(f.userid) FROM pd_users u , pd_files f WHERE u.userid = f.userid";
        $info = $this->_db->dataArray ( $sql );
        foreach ( $info as $k => $v )
        {
            $userid = $v ['userid'];
            $num = rand ( 0, 1000 );
            $this->_db->update ( "pd_files", [ 
                    'price' => $num,
                    'today_price' => $num 
            ], "userid = $userid " );
        }
    }
    public function opensearch()
    {
        echo "come";
        print_r ( OpenSearchFile::Main ("file_name", '高数', 0, 0, 1, 3079, 1000, 'a', 'a', 5 ) );
    }
    
    // 最近
    public function almost()
    {
    }
    // 发送
    public function send()
    {
    }
    // 下载
    public function download()
    {
        $fileid = 123;
        $time = 1;
        $client_ip = 1;
        $userid = 1;
        $info = array (
                'dl_fileid' => $fileid,
                'time' => $time,
                'client_ip' => $client_ip,
                'userid' => $userid 
        );
        $this->_db->insert ( 'xz_download', $info );
    }
    // 云印
    public function cloudprint()
    {
    }
    // 评论
    public function comment()
    {
        
        
    }
    // 申诉
    public function appeal()
    {
        print_r(Core::$configs);
    }
    // 用户操作
    public function operate()
    {
    
        $userid =  $this->GetUser ()['userid'];
        $client_ip = get_ip ();
        $fileid = $this->msg ['fileid'];
        $operate = $this->msg ['operate'];
        
        $info = array (
                
                'operate' => $operate,
                'ts' => TIMESTAMP,
                'client_ip' => $client_ip,
                'user_id' => $userid,
                'file_id' => $fileid 
        );
        $rs = $this->_db->insert ( 'pd_log', $info );
        
        if (is_numeric ( $rs ))
        {
            $this->ok ();
        }
        else
        {
            $this->Error ( $this->_db->_errorMsg );
        }
    }
    
    public function timeMachineInfo(){

        $userid = $this->GetUser ()['userid'];
        $operate = $this->msg ['operate'];
        $start = $this->msg ['start'];
        $num = $this->msg ['num'];
        
        // $sql = "SELECT file_id FROM pd_log WHERE user_id = $userid AND operate = $operate ORDER BY ";
        // $rs = $this->GetData($sql);
        // $fileid = $rs[0]['file_id'];
        // foreach ($rs as $k => $v){
        // $fileid[$k] = $v['file_id'];
        
        // }
        // $sql = "SELECT * FROM pd_files WHERE file_id IN (".array_to_sql($fileid).")";
        switch ($operate)
        {
            case 0 :
                $sql = "SELECT * FROM pd_files,xz_emalilist WHERE file_id IN (SELECT file_id FROM pd_log WHERE user_id = $userid AND operate = $operate ORDER BY ts DESC )  LIMIT $start,$num";
                break;//发送
            case 1 :
                $sql = "SELECT * FROM pd_files,xz_download WHERE file_id IN (SELECT file_id FROM pd_log WHERE user_id = $userid AND operate = $operate ORDER BY ts DESC )  LIMIT $start,$num";
                break;//下载
            case 2 :
                $sql = "SELECT * FROM pd_files,cloudprint_task WHERE file_id IN (SELECT file_id FROM pd_log WHERE user_id = $userid AND operate = $operate ORDER BY ts DESC )  LIMIT $start,$num";
                break;//云印
            case 3 : 
                break;// 买入
            case 4 :
                break; // 卖出
            case 5 :
                $sql = "SELECT * FROM pd_files WHERE file_id IN (SELECT file_id FROM pd_log WHERE user_id = $userid AND operate = $operate ORDER BY ts DESC )  LIMIT $start,$num";
                break;//浏览
        }
        $rs = $this->GetData ( $sql );
        print_r($rs);
        $this->Ret ( $rs );
    }
    
    public function TestXSS()
    {
        echo $this->msg[file_name];
    }
}
