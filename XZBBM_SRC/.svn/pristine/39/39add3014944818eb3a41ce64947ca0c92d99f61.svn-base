<?php
if (! defined ( 'IN_SYS' ))
{
    header ( "HTTP/1.1 404 Not Found" );
    die ();
}
class SubscribeAPI extends Xzbbm
{
    public function __construct()
    {
        parent::__construct ();
        if (empty ( $this->msg ["xztoken"] ))
        {
            $this->Error ( "please login again!" );
            return;
        }
        $this->msg ["userId"] = $this->GetUser ()["userid"];
    }
    private function assignmentUserame($user)
    {
        if (! empty ( $user ["real_name"] ))
            return $user ["real_name"];
        if (empty ( $user ["user_name"] ))
            if (! empty ( $user ["phone"] ))
                return half_replace ( $user ["phone"] );
            else
                return half_replace ( $user ["email"] );
        else
            return $user ["user_name"];
    }
    private function dateChange($int_time)
    {
        $one_day = 24 * 60 * 60;
        if (date ( 'U' ) - $int_time <= $one_day)
            return "昨天 " . date ( 'H:i', $file ["file_time"] );
        else if (date ( 'U' ) - $int_time <= $one_day * 2)
            return "前天 " . date ( 'H:i', $file ["file_time"] );
        else if (date ( 'U' ) - $int_time <= $one_day * 366)
            return date ( 'Y-m-d H:i:s', $file ["file_time"] );
        else
            return date ( 'm-d H:i', $file ["file_time"] ); // 最后更新
    }
    /**
     * request CollectDocument{subscribeId:int}
     *
     * @author zmw
     */
    public function GetSubscribeInfo()
    {
        $subscribe_id = $this->msg ["subscribeId"];
        $user_id = $this->msg ["userId"];
        $sql = "SELECT * FROM pd_subscribe WHERE user_id = $user_id AND subscribe_id = $subscribe_id";
        // echo $sql . "</br>";
        $subscribe_info = $this->_db->rsArray ( $sql );
        $sql = "SELECT * FROM pd_users WHERE userid = $subscribe_id";
        // echo $sql . "</br>";
        $user_info = $this->_db->rsArray ( $sql );
        if (count ( $subscribe_info ) <= 0)
        {
            $rs ["pushSetting"] = 0;
            $rs ["isSubscribe"] = 0;
        }
        else
        {
            $rs ["pushSetting"] = $subscribe_info ["push_setting"];
            $rs ["isSubscribe"] = $subscribe_info ["is_subscribe"];
        }
        
        if (empty ( $user_info ["user_name"] ))
            if (! empty ( $user_info ["phone"] ))
                $rs ["userName"] = half_replace ( $user_info ["phone"] );
            else
                $rs ["userName"] = half_replace ( $user_info ["email"] );
        else
            $rs ["userName"] = $user_info ["user_name"];
        
        $rs ["isVUser"] = $user_info ["is_v_user"] ? true : false;
        $rs ["isPlatformIdenty"] = $user_info ["is_platform_identy"] ? true : false;
        $ucode = $user_info ["ucode"];
        $ccode = $user_info ["ccode"];
        if ($ucode == 0 && $ccode == 0)
            $rs ["userIntro"] = "学长帮帮忙";
        else
        {
            $sql = "SELECT name FROM geo_universities WHERE university_id = $ucode";
            // echo $sql . "</br>";
            $university = $this->_db->rsArray ( $sql )["name"];
            $sql = "SELECT college FROM geo_colleges WHERE college_id = $ccode";
            // echo $sql . "</br>";
            $college = $this->_db->rsArray ( $sql )["college"];
            $rs ["userIntro"] = $university . "  " . $college;
        }
        $rs ["userImage"] = $user_info ['user_icon'];
        $this->Ret ( $rs );
    }
    /**
     *
     * @author zmw
     */
    public function TestZMW()
    {
        $sql = "SELECT name FROM geo_universities WHERE sicon_id = 0";
        $rsql = $this->_db->dataArray ( $sql );
        for($i = 0; $i < count ( $rsql ); $i ++)
            echo $rsql [$i] ["name"] . "</br>";
        
        /*
         * $update ["sicon_id"] = 10000; for($i = 0; $i <= 647; $i ++, $update["sicon_id"] ++){ $is_success = $this->_db->update("geo_universities", $update, "name = '$college[$i]'"); if($is_success == 0) echo $college[$i]."unsucess</br>"; else echo $college[$i]."sucess</br>"; } echo $i;
         */
    }
    
    /**
     * request CollectDocument{user_id:int，document_id:int}
     * response {is_ok:string}
     * http://112.124.50.239/?action=SubscribeAPI&do=CollectDocument&debug=on&msg={%22user_id%22:%22123%22,%22document_id%22:%22%22}
     *
     * @author zmw
     *        
     */
    public function CollectDocument()
    {
        $user_id = $this->msg ["userId"];
        $document_id = $this->msg ["documentId"];
        if (empty ( $user_id ))
        {
            $this->Ret ( [ 
                    "is_ok" => "user_id is null" 
            ] );
            return;
        }
        if (empty ( $document_id ))
        {
            $this->Ret ( [ 
                    "is_ok" => "document_id is null" 
            ] );
            return;
        }
        // echo "user_id=".$user_id. " document_id=".$document_id;
        // "SELECT COUNT(uid)语句该怎么执行"
        if (! $this->IsFileExits ( $document_id ))
        {
            $this->Ret ( [ 
                    "is_ok" => "file not exist" 
            ] );
            return;
        }
        $sql = "SELECT * FROM xz_favorite WHERE uid = $user_id && file_id = $document_id";
        $count = $this->_db->rsCount ( $sql );
        // echo " count = ".$count;
        if ($count >= 1)
        {
            $this->Ret ( [ 
                    "is_ok" => "you have already collect this document" 
            ] );
            return;
        }
        $coll_document = array (
                'uid' => $user_id,
                'file_id' => $document_id,
                'time' => date ( 'Y-m-d H:i:s' ) 
        );
        $this->_db->insert ( 'xz_favorite', $coll_document );
        $this->Ret ( [ 
                "is_ok" => "ok" 
        ] );
    }
    /**
     * request CancelCollectDocument{user_id:int，document_id:int}
     * response {is_ok:string}
     * http://112.124.50.239/?action=SubscribeAPI&do=CancelCollectDocument&debug=on&msg={%22user_id%22:%22123%22,%22document_id%22:%22%22}
     *
     * @author ZMW
     *        
     */
    public function CancelCollectDocument()
    {
        $user_id = $this->msg ["userId"];
        $document_id = $this->msg ["documentId"];
        $sql = "SELECT * FROM xz_favorite WHERE uid = $user_id && file_id = $document_id";
        $rsql = $this->_db->rsCount ( $sql );
        $count = count ( $rsql );
        if ($count <= 0)
        {
            $this->Ret ( [ 
                    "is_ok" => "you hadn't collect this document" 
            ] );
            return;
        }
        $where = "uid = $user_id && file_id = $document_id";
        $this->_db->delete ( 'xz_favorite', $where );
        $this->Ret ( [ 
                "is_ok" => "success to cancel collect" 
        ] );
    }
    /**
     * request GetMyCollections{user_id:int，page:int}
     * response {documents:string}
     * http://112.124.50.239/?action=SubscribeAPI&do=GetMyCollections&debug=on&msg={%22user_id%22:%22123%22,%22page%22:%22%22}
     *
     * @author zmw
     *        
     */
    public function GetMyCollections()
    {
        $user_id = $this->msg ["userId"];
        $page = $this->msg ["page"];
        $limit_m = ($page - 1) * 10;
        $sql = "SELECT * FROM pd_files WHERE file_id IN (SELECT file_id FROM xz_favorite WHERE uid = $user_id) LIMIT $limit_m,10 ";
        $rsql = $this->_db->dataArray ( $sql );
        for($i = 0; $i < count ( $rsql ); $i ++)
        {
            $rs [$i] ["file_id"] = $rsql [$i] ["file_id"];
            $rs [$i] ["file_name"] = $rsql [$i] ["file_name"];
            $rs [$i] ["file_description"] = $rsql [$i] ["file_description"];
            $rs [$i] ["file_tag"] = $rsql [$i] ["file_tag"];
            $rs [$i] ["pay_type"] = $rsql [$i] ["pay_type"];
            $rs [$i] ["price"] = $rsql [$i] ["price"];
            $rs [$i] ["is_push"] = $rsql [$i] ["is_push"];
            $rs [$i] ["location"] = $rsql [$i] ["location"]; // TODO
            $rs [$i] ["school_id"] = $rsql [$i] ["ucode"];
            $rs [$i] ["college_id"] = $rsql [$i] ["ccode"];
            $rs [$i] ["online_view_count"] = $rsql [$i] ["file_views"];
            $rs [$i] ["buy_count"] = $rsql [$i] ["buy_count"];
            $rs [$i] ["download_count"] = $rsql [$i] ["file_downs"];
            $rs [$i] ["good_comment_rate"] = $rsql [$i] ["good_comment_rate"];
            $rs [$i] ["comment_count"] = $rsql [$i] ["comment_count"];
            $rs [$i] ["publish_time"] = $rsql [$i] ["file_time"];
            $rs [$i] ["modify_time"] = $rsql [$i] ["modify_time"];
            $rs [$i] ["file_extension"] = $rsql [$i] ["file_extension"];
            $rs [$i] ["file_size"] = formatSize ( $rsql [$i] ["file_size"] );
        }
        $this->Ret ( [ 
                'documents' => $rs 
        ] );
    }
    /**
     * request ReportUser{user_id:int，report_id:int}
     * response {}
     * http://112.124.50.239/?action=SubscribeAPI&do=ReportUser&debug=on&msg={%22user_id%22:21204,%22report_id%22:21203}
     *
     * @deprecated 举报用户
     * @author ZMW
     *        
     */
    public function ReportUser()
    {
        $user_id = $this->msg ['userId'];
        $report_id = $this->msg ['reportId'];
        if ($user_id == $report_id)
        {
            $this->Ret ( "self" );
            return;
        }
        $where = "user_id = $user_id AND subscribe_id = $report_id";
        
        $sql = "SELECT * FROM pd_subscribe WHERE " . $where;
        $count = $this->_db->rsCount ( $sql );
        if ($count <= 0)
        { // 如果没有user_id 与 report_id 的记录，就增加
            $insert = array (
                    'user_id' => $user_id,
                    'subscribe_id' => $report_id,
                    'is_subscribe' => 2,
                    'push_setting' => 0 
            );
            $this->_db->insert ( 'pd_subscribe', $insert );
        }
        else
        {
            $sql = $sql . " AND is_subscribe = 2";
            // echo $sql . "</br>";
            $count = $this->_db->rsCount ( $sql );
            if ($count <= 0)
            { // 如果还未举报过,标记为举报
                $update ['is_subscribe'] = 2;
                $this->_db->update ( 'pd_subscribe', $update, $where );
            }
            else
            { // 已举报过该用户
                $this->Error ( "have reported" );
                return;
            }
        }
        // 被举报用户的被举报数+1
        $sql = "SELECT report_num FROM pd_users WHERE userid = $report_id";
        // echo $sql . "</br>";
        $rs = $this->_db->rsArray ( $sql );
        // echo "report_num = " . $rs ['report_num'];
        $rs ['report_num'] ++;
        $where = "userid = '$report_id'";
        $this->_db->update ( 'pd_users', $rs, $where );
        $this->ok ();
    }
    /**
     * 获取文档详情
     * request GetUserDocuments{user_id:int, page:int}
     * response {documents}
     * 112.124.50.239/?action=SubscribeAPI&do=GetUserDocuments&debug=on&msg={%22user_id%22:%2220164%22,%22page%22:1}
     *
     * @author ZMW
     *        
     */
    public function GetUserDocuments()
    {
        $user_id = $this->msg ["documentUserId"];
        $page = $this->msg ["page"];
        if (empty ( $user_id ))
        {
            $this->Error ( "user_id is null" );
            return;
        }
        if (empty ( $page ) || $page <= 0)
        {
            $this->Error ( "page is invalid" );
            return;
        }
        $limit_m = ($page - 1) * 10;
        $rsql = $this->_db->dataArray ( "SELECT * FROM pd_files WHERE userid = '$user_id' ORDER BY file_time DESC LIMIT $limit_m,10" );
        if (count ( $rsql ) <= 0)
        {
            $this->Error ( "is end" );
            return;
        }
        
        // echo "SELECT * FROM pd_files WHERE userid = '$user_id' LIMIT $limit_m,10";
        // echo "limit_m=" . $limit_m . "</br>";
        // echo "length" . count ( $rsql );
        for($i = 0; $i < count ( $rsql ); $i ++)
        {
            $rs [$i] ["file_id"] = $rsql [$i] ["file_id"];
            $rs [$i] ["file_name"] = $rsql [$i] ["file_name"];
            $rs [$i] ["file_description"] = $rsql [$i] ["file_description"];
            $rs [$i] ["file_tag"] = $rsql [$i] ["file_tag"];
            $rs [$i] ["pay_type"] = $rsql [$i] ["pay_type"];
            $rs [$i] ["price"] = $rsql [$i] ["price"];
            $rs [$i] ["is_push"] = $rsql [$i] ["is_push"];
            $rs [$i] ["location"] = $rsql [$i] ["location"]; // TODO
            $rs [$i] ["school_id"] = $rsql [$i] ["ucode"];
            $rs [$i] ["college_id"] = $rsql [$i] ["ccode"];
            $rs [$i] ["online_view_count"] = $rsql [$i] ["file_views"];
            $rs [$i] ["buy_count"] = $rsql [$i] ["buy_count"];
            $rs [$i] ["download_count"] = $rsql [$i] ["file_downs"];
            $rs [$i] ["good_comment_rate"] = $rsql [$i] ["good_comment_rate"];
            $rs [$i] ["comment_count"] = $rsql [$i] ["comment_count"];
            $rs [$i] ["publish_time"] = $rsql [$i] ["file_time"];
            $rs [$i] ["file_time"] = $rsql [$i] ["file_time"];
            $rs [$i] ["file_extension"] = $rsql [$i] ["file_extension"];
            $rs [$i] ["file_size"] = formatSize ( $rsql [$i] ["file_size"] );
        }
        
        $this->Ret ( [ 
                "documents" => $rs 
        ] );
    }
    /**
     * request SubscribeUser{user_id:int，cancel_id:int}
     * response {}
     * http://112.124.50.239/?action=SubscribeAPI&do=CancelSubscribe&debug=on&msg={%22user_id%22:21204,%22cancel_id%22:21203}
     *
     * @deprecated 取消关注
     * @author ZMW
     */
    public function CancelSubscribe()
    {
        $user_id = $this->msg ['userId'];
        $cancel_id = $this->msg ['cancelId'];
        if ($user_id == $cancel_id)
        {
            $this->Error ( "self" );
            return;
        }
        $where = "user_id = '$user_id' AND subscribe_id = '$cancel_id'";
        $sql = "SELECT * FROM pd_subscribe WHERE " . $where . " AND is_subscribe = 1";
        if ($this->_db->rsCount ( $sql ) <= 0)
        {
            $this->Error ( "have not subscribe." );
            return;
        }
        $update ['is_subscribe'] = 0;
        $update ['push_setting'] = 0;
        $this->_db->update ( 'pd_subscribe', $update, $where );
        $this->ok ();
    }
    /**
     * request SubscribeUser{user_id:int，subscribe_id:int,push_setting:boolean}
     * response {is_ok:string}
     * http://112.124.50.239/?action=SubscribeAPI&do=SubscribeUser&debug=on&msg={%22user_id%22:21204,%22subscribe_id%22:21206}
     *
     * @author ZMW
     */
    public function SubscribeUser()
    {
        $user_id = $this->msg ["userId"];
        // echo $user_id;
        $subscribe_id = $this->msg ["subscribeId"];
        // $this->say("user_id = ".$user_id ." subscribe_id = ". $subscribe_id);
        // $this->say($user_id == $subscribe_id);
        if ($user_id == $subscribe_id)
        {
            $this->Error ( "self" );
            return;
        }
        $push_setting = $this->msg ["pushSetting"];
        
        if (empty ( $user_id ) || empty ( $subscribe_id ))
        {
            $this->Error ( "parameter is invalid." );
            return;
        }
        
        $where = "user_id = $user_id AND subscribe_id = $subscribe_id";
        if (empty ( $push_setting ))
            $push_setting = 1; // 推送设置默认'仅显示气泡'
        $sql = "SELECT * FROM pd_subscribe WHERE " . $where;
        $rs = $this->_db->rsArray ( $sql );
        if (count ( $rs ) <= 0)
        { // 如果数据库中无此记录，就添加
            $insert = array (
                    'user_id' => $user_id,
                    'subscribe_id' => $subscribe_id,
                    'is_subscribe' => 1,
                    'push_setting' => $push_setting 
            );
            $this->_db->insert ( 'pd_subscribe', $insert );
        }
        else
        { // 数据库中已经有该记录
            $update ["is_subscribe"] = 1;
            $update ["push_setting"] = $push_setting;
            $this->_db->update ( 'pd_subscribe', $update, $where );
        }
        $this->ok ();
    }
    /**
     * request MySubscriptions{user_id:int,page:int}
     * response {users}
     * http://112.124.50.239/?action=SubscribeAPI&do=MySubscriptions&debug=on&msg={%22user_id%22:%2221204%22}
     *
     * @author ZMW
     */
    public function MySubscriptions()
    {
        $user_id = $this->msg ["userId"];
        // echo $user_id;
        $page = $this->msg ["page"];
        $limit_m = ($page - 1) * 10;
        
        // 检索出所有关注的用户的信息
        $sql = "SELECT * FROM pd_users WHERE userid IN (SELECT subscribe_id FROM pd_subscribe WHERE user_id = $user_id AND is_subscribe = 1) LIMIT $limit_m, 10";
        $users = $this->_db->dataArray ( $sql );
        if (count ( $users ) <= 0)
        {
            $this->Error ( "end" );
            return;
        }
        for($i = 0; $i < count ( $users ); $i ++)
        {
            $subscribe_id = $users [$i] ["userid"];
            // 检索出所关注用户最近发表的文件的信息
            $sql = "SELECT * FROM pd_files WHERE userid = $subscribe_id ORDER BY file_time DESC LIMIT 1";
            // echo $sql."</br>";
            $file = $this->_db->rsArray ( $sql );
            // 检索出对所关注用户的推送设置
            $sql = "SELECT * FROM pd_subscribe WHERE user_id = $user_id AND subscribe_id = $subscribe_id AND is_subscribe = 1 LIMIT 0,1";
            $subscribe = $this->_db->rsArray ( $sql );
            // echo $rsql_time ."</br>";
            $rs [$i] ["userId"] = $users [$i] ["userid"];
            
            if (empty ( $users [$i] ["user_name"] ))
                if (! empty ( $users [$i] ["phone"] ))
                {
                    $rs [$i] ["userName"] = half_replace ( $users [$i] ["phone"] );
                    // $rs [$i] ["userName"] = $users [$i] ["phone"] ;
                }
                else
                {
                    // $rs [$i] ["userName"] = $users [$i] ["email"];
                    $rs [$i] ["userName"] = half_replace ( $users [$i] ["email"] );
                }
            else
                $rs [$i] ["userName"] = $users [$i] ["user_name"];
            
            $rs [$i] ["isVUser"] = $users [$i] ["is_v_user"] ? true : false;
            $rs [$i] ["isPlatformIdenty"] = $users [$i] ["is_platform_identy"] ? true : false;
            $rs [$i] ["userImage"] = $users [$i] ["user_icon"]; // 用户头像
            $rs [$i] ["newsNum"] = $subscribe ["push_num"]; // 未读消息的数目
            $rs [$i] ["userIntro"] = $rsql [$i] ["intro"];
            $rs [$i] ["isPush"] = $subscribe ["push_setting"];
            $rs [$i] ["fileName"] = $file ["file_name"]; // 文件简介
            
            $rs [$i] ["lastUpdateTime"] = $file ["file_time"];
            /*
             * if (date ( 'U' ) - $file ["file_time"] <= $one_day) $rs [$i] ["lastUpdateTime"] = "昨天 " . date ( 'H:i', $file ["file_time"] ); else if (date ( 'U' ) - $file ["file_time"] <= $one_day * 2) $rs [$i] ["lastUpdateTime"] = "前天 " . date ( 'H:i', $file ["file_time"] ); else if (date ( 'U' ) - $file ["file_time"] <= $one_day * 366) $rs [$i] ["lastUpdateTime"] = date ( 'Y-m-d H:i:s', $file ["file_time"] ); else $rs [$i] ["lastUpdateTime"] = date ( 'm-d H:i', $file ["file_time"] ); // 最后更新
             */
        }
        $this->Ret ( [ 
                "subscriptionList" => $rs 
        ] );
    }
    
    /**
     * 清空未读
     * request ClearPushNum{userId:int, subscribeId:int}
     * response{}
     * http://112.124.50.239/?action=SubscribeAPI&do=ClearPushNum&debug=on&msg={%22userId%22:%2221204%22,%22subscribeId%22:21203}
     *
     * @author ZMW
     */
    public function ClearPushNum()
    {
        $user_id = $this->msg ["userId"];
        $subscribe_id = $this->msg ["subscribeId"];
        $update ["push_num"] = 0;
        $where = "user_id = $user_id AND subscribe_id = $subscribe_id";
        $this->_db->update ( "pd_subscribe", $update, $where );
    }
    /**
     *
     * @author ZMW
     */
    public function GetMyHomePageInfo()
    {
        $user = $this->GetUser ();
        $rs ["username"] = $user ["user_name"];
        $rs ["phone"] = $user ["phone"];
        $rs ["email"] = $user ["email"];
        $rs ["payAccount"] = $user ["pay_account"];
        $rs ["userImage"] = $user ['user_icon'];
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
            $rs ["university"] = $this->_db->rsArray ( $sql )["name"];
            $sql = "SELECT college FROM geo_colleges WHERE college_id = $ccode";
            $rs ["college"] = $this->_db->rsArray ( $sql )["college"];
        }
        
        // 查出推送数目
        $sql = "SELECT push_num FROM pd_subscribe WHERE user_id = {$user['userid']} AND push_setting <> 0 AND is_subscribe = 1";
        $push_num = $this->_db->dataArray ( $sql );
        for($i = 0; $i < count ( $push_num ); $i ++)
        {
            $rs ["mySubscribeNews"] += ( int ) $push_num [$i] ["push_num"];
        }
        
        $sql = "SELECT * FROM pd_files WHERE userid IN (SELECT subscribe_id FROM pd_subscribe WHERE user_id = {$user['userid']} AND is_subscribe = 1) ORDER BY file_time DESC LIMIT 1";
        $file = $this->_db->rsArray ( $sql );
        // 如果有订阅用户并且用户有发布过
        if (count ( $file ) <= 0)
        {
            // $sql = "SELECT * FROM pd_files ORDER BY file_time DESC LIMIT 1";
            // $file = $this->_db->rsArray ( $sql );
            $rs ["lastUsername"] = "";
            $rs ["lastFileTitle"] = "";
            $rs ["lastUpdateTime"] = 0;
            $rs ["fileId"] = - 1;
        }
        else
        { // file存在的情况
          // if (empty ( $file ["user_name"] )) {
            $sql = "SELECT * FROM pd_users WHERE userid = {$file["userid"]}";
            $lastUser = $this->_db->rsArray ( $sql );
            // $rs ["lastUsername"] = $this->assignmentUserame ( $lastUser );
            $rs ["lastUsername"] = $lastUser ['user_name'];
            // } else
            // $rs ["lastUsername"] = $file ["user_name"];
            $rs ["lastFileTitle"] = $file ["file_name"];
            $rs ["lastUpdateTime"] = $file ["file_time"];
            $rs ["fileId"] = $file ["file_id"];
        }
        $today_zero_time = strtotime ( "today" );
        $today_porfit = $this->_db->rsArray ( "select sum(amount) as profit from pay_order where file_own_userid=$user[userid] and state = 'client_success' and time > $today_zero_time" )['profit'];
        $total_porfit = $this->_db->rsArray ( "select sum(amount) as profit from pay_order where file_own_userid=$user[userid] and state = 'client_success'" )['profit'];
        
        $rs ["myAllMoneny"] = $total_porfit;
        $rs ["yestodayIncome"] = $today_porfit;
        
        $this->Ret ( [ 
                "homePageInfo" => $rs 
        ] );
    }
    public function UpdateUserInfo()
    {
        $user_id = $this->GetUser ()['userid'];
        $username = $this->msg ["username"];
        $phone = $this->msg ["phone"];
        $email = $this->msg ["email"];
        $password = $this->msg ["password"];
        $alipay = $this->msg ["alipay"];
        $test_email = $this->msg [test_email];
        
        if ($test_email)
        {
            $sql = "SELECT * FROM pd_users WHERE email = '$email'";
            $count = $this->_db->rsCount ( $sql );
            if ($count > 0)
            {
                $this->Error ( "该邮箱已经被注册！" );
            }
            else
            {
                $this->ok ();
            }
            return;
        }
        
        if (! empty ( $username ))
            $update ['user_name'] = $username;
        if (! empty ( $phone ))
            $update ['phone'] = $phone;
        if (! empty ( $email ))
        {
            /*
             * if($this->checkEmail($email)){ $this->Error("email is error"); return ; }
             */
            $sql = "SELECT * FROM pd_users WHERE email = '$email'";
            $count = $this->_db->rsCount ( $sql );
            if ($count > 0)
            {
                $this->Error ( "该邮箱已经被注册！" );
                return;
            }
            $update ['email'] = $email;
        }
        if (! empty ( $password ))
            $update ['password'] = md5 ( $password );
        if (! empty ( $alipay ))
            $update ['pay_account'] = $alipay;
        if (empty ( $update ))
        {
            $this->Error ( "all field is null" );
            return;
        }
        $this->_db->update ( "pd_users", $update, "userid = $user_id" );
        $this->ok ();
    }
    public function UpdateByVersion()
    {
        $client_version = $this->msg ['versionCode'];
        $ver = "1.0.3";
        $size = "7.8";
        if (false == in_array ( $client_version, array (
                $ver 
        ) ))
        {
            $rs ['isupdate'] = "true";
            $rs ['updatetips'] = "有高端大气上档次，低调奢华有内涵的新版本了，赶快升级试试吧！";
            $rs ['apk_url'] = "http://cdn.xzbbm.cn/apps/xzbbm_$ver.apk";
            $rs ['current_version'] = $ver;
            $rs ["large_of_apk"] = $size;
            $rs ["apk_name"] = "xzbbm_$ver.apk";
        }
        else
        {
            $rs ['isupdate'] = "false";
        }
        
        $user = $this->GetUser ();
        $time_space = TIMESTAMP - $user [last_login_time];
        $rs ['relogin'] = $time_space > 86400 ? true : false; // one day = 86400s
                                                              // a weak = 604800s
        $this->Ret ( $rs );
    }
    /**
     * request{requestType:int}
     * 0代表最近，1代表付费，2免费，3废纸篓
     *
     * @author zmw
     */
    public function PublishManager()
    {
        $user_id = $this->GetUser ()['userid'];
        $request_type = $this->msg ['requestType'];
        $request_num = $this->msg ['requestNum'];
        $page = $this->msg ['page'];
        
        $from = $this->msg ['from'];
        $limit = $this->msg ['limit'];
        
        if ($limit > 0)
        {
            $sql_limit = " ORDER BY file_time DESC LIMIT $from , $limit"; // use from limit by why
        }
        else
        {
            
            if ($page <= 0)
            {
                $this->Error ( "page is invalid" );
                return;
            }
            $limit_m = ($page - 1) * $request_num;
            $sql_limit = " ORDER BY file_time DESC LIMIT $limit_m , $request_num";
        }
        
        $sql = "SELECT * FROM pd_files WHERE userid = $user_id ";
        if ($request_type == 0)
            $sql .= " AND in_recycle = 0";
        else if ($request_type == 1)
            $sql .= " AND in_recycle = 0 AND price <> 0";
        else if ($request_type == 2)
            $sql .= " AND in_recycle = 0 AND price = 0";
        else if ($request_type == 3)
            $sql .= " AND in_recycle = 1";
        else
        {
            $this->Error ( "requestType error" );
            return;
        }
        $sql .= $sql_limit; // by why
        $files = $this->_db->dataArray ( $sql );
        if (count ( $files ) <= 0)
        {
            $this->Error ( $request_type );
            return;
        }
        for($i = 0; $i < count ( $files ); $i ++)
        {
            $rs [$i] ['fileType'] = $files [$i] ['file_extension'];
            $rs [$i] ['yaoheLength'] = $files [$i] ['yaohe_length'];
            $rs [$i] ['fileName'] = $files [$i] ['file_name'];
            $rs [$i] ['price'] = $files [$i] ['price'];
            $rs [$i] ['fileIndex'] = $files [$i] ['file_index'];
            $rs [$i] ['fileKey'] = $files [$i] ['file_key'];
            $rs [$i] ['fileTime'] = $files [$i] ['file_time']; // by why
            $rs [$i] ['fileId'] = $files [$i] ['file_id'];
            $rs [$i] ['is_converted'] = $files [$i] ['is_converted'];
            $rs [$i] ['is_locked'] = $files [$i] ['is_locked'];
            $rs [$i] ['FormatedFileTime'] = date ( 'Y/m/d', $files [$i] ['file_time'] ); // by berton
            if ($files [$i] ['yaohe_length'] > 0)
            {
                $rs [$i] ['yaoheUrl'] = $this->GetUrl ( array (
                        'file_real_name' => $files [$i] ["file_real_name"],
                        'page' => '',
                        'degree' => '',
                        'timeout' => 3600,
                        'file_extension' => "amr" 
                ) );
            }
            else
            {
                $rs [$i] ["yaohe_url"] = "";
            }
        }
        $this->Ret ( [ 
                "publishList" => $rs,
                "requestType" => $this->msg ['requestType'] 
        ] );
    }
    public function AddToGarbage()
    {
        $user_id = $this->GetUser ()['userid'];
        $request_type = $this->msg ['requestType'];
        $file_index = $this->msg ['fileIndex'];
        for($i = 0; $i < count ( $file_index ); $i ++)
        {
            $where = "userid = $user_id AND file_index = " . "'" . $file_index [$i] . "'";
            $this->say ( $where );
            $update ['in_recycle'] = $request_type;
            $this->_db->update ( 'pd_files', $update, $where );
        }
        $this->ok ();
    }
    private function checkEmail($inAddress)
    {
        return (ereg ( "^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+", $inAddress ));
    }
    /**
     *
     * @author ZMW
     * @param $file_id 判断是否存在的文件id            
     * @return boolean 返回文件是否存在
     */
    private function IsFileExits($file_id)
    {
        $sql = "SELECT (file_id) FROM pd_files WHERE file_id = $file_id";
        $file_num = $this->_db->rsCount ( $sql );
        // echo "file_num =".$file_num;
        return ( bool ) $file_num;
    }
}