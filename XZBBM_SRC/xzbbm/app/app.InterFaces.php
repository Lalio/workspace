<?php
if (! defined ( 'IN_SYS' ))
{
    header ( "HTTP/1.1 404 Not Found" );
    die ();
}

/**
 *
 * @todo 系统正常运行的一些自动脚本
 * @author bo.wang3
 * @version 2013-10-26
 */
class InterFaces extends Xzbbm
{
    public function __construct()
    {
        // 该类仅可运行在cli模式下
        if (! isset ( $_SERVER ['SHELL'] ))
            exit ( '脚本禁止运行' );
        parent::__construct ();
    }
    
    /**
     *
     * @todo 定时的更新pd_users的user_name字段，用于搜索引擎
     * @author bo.wang3
     * @version 2013-10-26
     */
    public function ReFreshUserName()
    {
        $rss = $this->_db->dataArray ( "SELECT userid,phone,email,user_name FROM pd_users" );
    
        foreach ( $rss as $rs )
        {
            if(false == strstr($rs['user_name'],'*')){
                $user_name = "{$rs[user_name]},{$rs[phone]},{$rs[email]}";
            }else{
                $user_name = "{$rs[phone]},{$rs[email]}";
            }
    
            $this->_db->update('pd_files',array('user_name' => $user_name),'userid = '.$rs['userid']);
            echo "$rs[userid]\n";
        }
        echo "资料用户相关信息更新完毕\n";
    
    }
    
    /**
     *
     * @todo 以随机的某个账户对某一资源进行批量群发
     * @author bo.wang
     * @version 2013-06-06 14:29
     */
    public function sendEmail($path, $adresses, $subject, $body, $f_name)
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
        if(!empty($path) && !empty($f_name)) {
            $mail->AddAttachment($path,$f_name);
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
    	
    	.mmsgLetterHeader		{	height:23px;background:url(http://www.xzbbm.cn/images/mmsgletter_2_bg_topline.png) repeat-x 0 0; }
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
    	
    	<div class="mmsgLetterHeader" style="height:23px;background:url(http://www.xzbbm.cn/images/mmsgletter_2_bg_topline.png) repeat-x 0 0;">
    		
    	</div>
    	<div class="mmsgLetterContent" style="text-align:left;padding:30px;font-size:14px;line-height:1.5;background:url(http://www.xzbbm.cn/images/stamp.png?v=4) no-repeat top right;">
    	
    	<div>
        <br>         
        $body
    	
		<div class="mmsgLetterInscribe" style="padding:40px 0 0;">
		<img class="mmsgAvatar" width="55px" src="http://www.xzbbm.cn/images/xiaoming.png" style="float:left;">
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
     *
     * @todo 刷新资料收益及用户收益
     * @author bo.wang
     * @version 2013-06-06 14:29
     */
    public function ReFreshProfile()
    {
        $rs = $this->_db->dataArray ( "SELECT file_id FROM pd_files WHERE userid = 1 OR (file_time > 1402329600)" );
        
        foreach ( $rs as $v )
        {
            $file_ids [] = $v ['file_id'];
        }
        
        foreach ( $file_ids as $id )
        {
            if (true === $this->_db->update ( 'pd_files', array (
                    'profile' => $this->GetProfile ( $id ) 
            ), 'file_id = ' . $id ))
            {
                // echo $id."\n";
            }
        }
        
        echo "资料收益刷新完毕！\n";
        
        $rss = $this->_db->dataArray ( "SELECT userid,profile_income,profile_cashback,profile FROM pd_users" );
        
        foreach ( $rss as $rs )
        {
            
            $o = $this->_db->rsArray ( "SELECT sum(profile) as total FROM pd_files WHERE userid = {$rs['userid']}" );
            
            $v ['profile_income'] = $o ['total'];
            $v ['profile'] = $v ['profile_income'] - $rs ['profile_cashback'];
            
            if (true === $this->_db->update ( 'pd_users', $v, 'userid = ' . $rs ['userid'] ))
            {
                // echo $rs['userid']."\n";
            }
        }
        
        echo "用户资料收益刷新完毕！\n";
        echo "Process Is Over! (" . date ( 'Y-m-d H:i:s' ) . ")\n";
    }
    
    /**
     *
     * @todo 
     * @author bo.wang
     * @version 2015-01-06 14:29
     */
    public function DelRepeatFiles()
    {
        $rss = $this->_db->dataArray ( "SELECT file_id,file_name,file_md5,userid 
        								FROM pd_files
        								GROUP BY file_name,file_md5,userid
        								Having count(*) > 1" );
        
        foreach ( $rss as $rs )
        {
            $ck = $this->_db->rsArray('select file_id from pd_files where file_id = '.$rs['file_id']);
            //判断一下是否还存在
            if(!empty($ck)){
            	$v = $this->_db->conn ( "delete from pd_files
	            						 where file_name = '$rs[file_name]'
	            						 and file_md5 = '$rs[file_md5]'
	            						 and userid = $rs[userid]
	            						 and file_id != $rs[file_id]" );
            	echo $rs[file_id].'N|';
            }else{
            	echo $rs[file_id].'Y|';
            }
        	
        }
        echo "Process Is Over! (" . date ( 'Y-m-d H:i:s' ) . ")\n";
    }
    
    
    /**
     *
     * @todo 向百度动态提交网站地图
     * @author bo.wang
     * @version 2013-06-06 14:29
     */
    public function PushToBaidu()
    {
    	$file = $this->_db->dataArray ( "SELECT file_key from pd_files where in_recycle = 0 order by rand() limit 0,50" );
    	
    	foreach ( $file as $data )
    	{
    		$urls[] = "http://www.xzbbm.cn/{$data['file_key']}";
    	}
    	
    	$api = 'http://data.zz.baidu.com/urls?site=www.xzbbm.cn&token=qmM79f03aI82B5sm';
		$ch = curl_init();
		$options =  array(
		    CURLOPT_URL => $api,
		    CURLOPT_POST => true,
		    CURLOPT_RETURNTRANSFER => true,
		    CURLOPT_POSTFIELDS => implode("\n", $urls),
		    CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
		);
		curl_setopt_array($ch, $options);
		$result = curl_exec($ch);
		
		var_dump($result);
    }
    
    /**
     *
     * @todo 生成网站地图
     * @author bo.wang
     * @version 2013-06-06 14:29
     */
    public function BuildXmlSiteMap()
    {
        $content = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">	    
XML;
        $file = $this->_db->dataArray ( "SELECT file_key from pd_files where in_recycle = 0 order by rand() limit 0,10000" );
        
        foreach ( $file as $data )
        {
            
            $date = date ( 'Y-m-d', TIMESTAMP );
            
            $content .= <<<XML
<url>
<loc>http://xzbbm.cn/{$data['file_key']}</loc>
<priority>0.5</priority>
<lastmod>$date</lastmod>
<changefreq>always</changefreq>
</url>	            
XML;
        }
        
        $content .= <<<XML
</urlset>
XML;
        
        $obj = "/data/backend_service/src/xzbbm/sitemap.xml";
        
        file_put_contents ( $obj, $content );
        if (file_exists ( $obj ))
        {
            echo 'XML生成成功！' . date ( 'Y-m-d H:i:s' ) . '\r\n';
        }
        else
        {
            echo 'XML生成失败！' . date ( 'Y-m-d H:i:s' ) . '\r\n';
        }
    }
    
    /**
     *
     * @todo 定时发送邮件 发送成功后文件下载数加1 实现在一段时间内对同一文件的批量发送
     * @author bo.wang
     * @version 2013-06-06 14:29
     */
    public function EmailService()
    {
        
        // 获取发送总数
        $sql = "SELECT count(*) as send_sum FROM xz_emaillist WHERE state = 1";
        $count = $this->_db->rsArray ( $sql );
        
        while ( 1 )
        {
            sleep ( 5 );
            
            // 锁定待发送资料的fid
            $sql = "SELECT fid FROM xz_emaillist WHERE state = 0 GROUP BY fid ORDER BY COUNT( fid ) DESC LIMIT 1";
            $rs = $this->_db->rsArray ( $sql );
            if (empty ( $rs ))
            {
                echo "无待发送资料！" . date ( 'Y-m-d H:i:s', time () ) . "\t\n";
            }
            else
            {
                $fid = $rs ['fid'];
                
                // 获取该fid对应资源的详细情况
                $sql = "SELECT * FROM pd_files WHERE file_id = $fid";
                $res_fid = $this->_db->rsArray ( $sql );
                $uname = $res_fid ['uname'] ? $res_fid ['uname'] : '学长帮帮忙';
                
                // 获取请求该资源的用户列表 备注：同一时间段内用户对某个资源多次请求只发送一次邮件
                $sql = "SELECT id,uid FROM xz_emaillist WHERE fid = $fid AND state = 0 LIMIT 0,50";
                $uids = $this->_db->dataArray ( $sql );
                
                foreach ( $uids as $k => $v )
                { // 获取ids数组备用
                    $ids [] = $v ['id'];
                }
                
                $ids_str = implode ( ',', $ids ); // 拼装成字符串
                $this->_db->conn ( "UPDATE xz_emaillist SET state = 2 where id IN ($ids_str)" ); // 把目标账户锁起来
                
                foreach ( $uids as $k => $v )
                { // 获取uids数组查询他们的邮件地址
                    $uids [$k] = $v ['uid'];
                }
                
                $uids = implode ( ',', $uids ); // 拼装成字符串
                                                
                // 获取目标邮箱地址列表
                $sql = "SELECT email FROM pd_users WHERE userid IN ($uids)";
                $adresses = $this->_db->dataArray ( $sql );
                
                foreach ( $adresses as $k => $v )
                { // 获取uids数组查询他们的邮件地址
                    $adresses [$k] = $v ['email'];
                }
                $adresses_str = implode ( ',', $adresses ); // 拼装成字符串
                
                $ts = date ( 'Y年m月d日', time () );
                if ($res_fid ['has_thumb'] > 0)
                {
                    unset ( $pstr );
                    for($i = 0; $i < $res_fid ['has_thumb']; $i ++)
                    {
                        $pstr .= '<p><img src="http://www.xzbbm.cn/GetFile/' . $res_fid ['file_id'] . '/thumb/' . TIMESTAMP . '/' . sha1 ( TIMESTAMP . 'sNsxCrth13LGsu60' ) . '/' . $i . '"></p>';
                    }
                }
                $body = <<<HTML
<p>亲爱的同学：</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;欢迎你加入学长帮帮忙大家庭，这是学长向大家发出的第{$count[send_sum]}份邮件，我们致力于打造一个开放、简洁的校内知识分享平台，邮件附件是《$res_fid[file_name]》。</p>
<h3 style="font-weight: bold; margin: 1.17em 0px; color: #009966; font-family: 'lucida Grande', Verdana; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 23px; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px">
在这里你可以简单而方便的：</h3>
<ol style="padding: 8px; color: #333333; font-family: 'lucida Grande', Verdana; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 23px; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px;">
	<li style="list-style: decimal inside; margin: 0px; padding: 0px;">
            搜索并获取全国超过<font color="#FF8C05">3000+</font>所高校，<font color="#FF8C05">20000+</font>个二级学院海量校内资料</li>
	<li style="list-style: decimal inside; margin: 0px; padding: 0px;">
            订阅关注隔壁王老师和学委李同学，实时收到他们发布的课件和作业答案</li>
	<li style="list-style: decimal inside; margin: 0px; padding: 0px;">
            发布你的第一份课堂笔记，这不仅会帮助到他人你还能为此获得一笔收益</li>
</ol>
<p style="line-height: 23px; color:#FF8C05; font-family: 'lucida Grande', Verdana;font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px;">
<font color="#CC0033">
<!-- <a href="http://xzbbm.cn/$res_fid[file_key]" target="_blank" id="qr_img"><img src="http://cdn.xzbbm.cn/qrcodes/$res_fid[file_store_path]/$res_fid[file_real_name]-180-1-Q.png" width="100px" height="100px" alt="如果无法看到此资料二维码，请选择邮件上方 信任此发件人的图片"></a> --> </font></p>
<h3 style=" font-weight: bold; margin: 1.17em 0px; color: #009966; font-family: 'lucida Grande', Verdana; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 23px; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px">
以下方式都可以很快找到我们：</h3>
<blockquote style="color: #4C4C4C; font-family: 'lucida Grande', Verdana; font-size: 14px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 23px; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px;">
	电话：020 - 29324567   转  王小明<br>
           邮箱：<a target="_blank" style="outline: none; text-decoration: none; cursor: pointer; color: rgb(44, 74, 119);" href="mailto:contact@xiaoming-inc.com">contact@xiaoming-inc.com</a><br>
	公众号：学长帮帮忙<br>
</blockquote>
HTML;
                
                // 发送验证邮箱的邮件
                $sql = "SELECT validity,email FROM pd_users WHERE userid IN ($uids)";
                $validity = $this->_db->dataArray ( $sql );
                foreach ( $validity as $k => $v )
                {
                    //暂时去掉验证邮箱的功能 by why 
//                     if (ord ( $v ['validity'] ) == 0)
//                     {
//                         $sql = "SELECT count(*) as user_sum FROM xz_emaillist WHERE state = 1 AND uid IN ($uids)";
//                         $user_count = $this->_db->rsArray ( $sql );
//                         if ($user_count [user_sum] >= 1)
//                         {
//                             $this->checkEmail ( $v ['email'], 0 ); // 传入0参数表示只发送邮箱验证不发送正式文件
//                         }
                        
//                         else
//                         {
//                             $this->checkEmail ( $v ['email'], 1 ); // 传入1参数表示即发送邮箱验证又发送正式文件
//                             if (empty ( $res_fid ) || $res_fid ['in_recycle'] == 1)
//                             {
//                                 $subject = "您所需要的资料已过期或已被清除 - 学长帮帮忙";
//                                 $this->sendEmail ( '', $v ['email'], $subject, $body, '');
//                             }
//                             else
//                             {
//                                 $subject = $res_fid ["file_name"] . " - $uname";
//                                 // 从云端取回文件
//                                 $tmp_path = get_object ( $this->_oss, "$res_fid[file_real_name].$res_fid[file_extension]" );
//                                 // $path = "/data/stores/file/".$res_fid["file_store_path"]."/".$res_fid["file_real_name"].".".$res_fid["file_extension"]; //真实地址
//                                 $f_name = $res_fid ["file_name"] . '[学长帮帮忙].' . $res_fid ["file_extension"]; // 附件名称
//                                 $this->sendEmail ( $tmp_path, $v ['email'], $subject, $body, $f_name );
//                             }
//                         }
//                     }
//                     else
                    { // 开始发送正式邮件
                        if (empty ( $res_fid ) || $res_fid ['in_recycle'] == 1)
                        {
                            $subject = "您所需要的资料已过期或已被清除 - 学长帮帮忙";
                            $this->sendEmail ( '', $v ['email'], $subject, $body,'' );
                        }
                        else
                        {
                            $subject = $res_fid ["file_name"] . " - $uname";
                            // 从云端取回文件
                            $tmp_path = get_object ( $this->_oss, "$res_fid[file_real_name].$res_fid[file_extension]" );
                            // $path = "/data/stores/file/".$res_fid["file_store_path"]."/".$res_fid["file_real_name"].".".$res_fid["file_extension"]; //真实地址
                            $f_name = $res_fid ["file_name"] . '[学长帮帮忙].' . $res_fid ["file_extension"]; // 附件名称
                            $this->sendEmail ( $tmp_path, $v ['email'], $subject, $body, $f_name );
                        }
                    }
                }
                
                echo date ( 'Y-m-d H:i:s', time () ) . "\t send fid:" . $fid . " to:$adresses_str success.\n";
                $this->_db->conn ( "UPDATE xz_emaillist SET state = 1 where id IN ($ids_str)" );
                $e_sum = count ( $ids ); // 统计总发送数
                $this->_db->conn ( "UPDATE pd_files set file_downs = (file_downs + $e_sum) where file_id = $fid" );
                
                $count [send_sum] += count ( $adresses );
                unset ( $ids );
                unset ( $uids );
            }
        }
    }
    
    /**
     *
     * @todo 每日数据报表
     * @author bo.wang
     * @version 2013-06-06 14:29
     */
    public function DailyReport()
    {
        $subject = "【学长帮帮忙】每日运营数据报表 " . " - " . date ( 'Y年m月d日' );
        
        // 最近七日每日发送邮件
        $sql [] = "SELECT DATE_FORMAT(ts,'%Y-%m-%d') as date,count(*) as total 
				  FROM xz_emaillist 
				  WHERE ts > DATE_FORMAT(from_unixtime(unix_timestamp()- 604800),'%Y-%m-%d %H:%I:%S') 
				  GROUP BY DATE_FORMAT(ts,'%Y-%m-%d')
				  ORDER BY ts DESC";
        
        // 最近七日每日新增用户
        $sql [] = "SELECT DATE_FORMAT(FROM_UNIXTIME(reg_time),'%Y-%m-%d') as date,count(*) as total
				  FROM pd_users
				  WHERE reg_time > (unix_timestamp() - 604800)
				  GROUP BY DATE_FORMAT(FROM_UNIXTIME(reg_time),'%Y-%m-%d')
				  ORDER BY reg_time DESC";
        
        // 过去一周热门资料TOP10
        $sql [] = "SELECT file_name,count(*) as total,file_views,file_downs
				  FROM pd_files,xz_emaillist
				  WHERE ts > DATE_FORMAT(FROM_UNIXTIME(UNIX_TIMESTAMP() - 86400*7),'%Y-%m-%d %H:%i:%s') and fid = file_id
				  GROUP BY fid ORDER BY total DESC LIMIT 10";
        
        // 过去一月用户新增学校TOP5
        $sql [] = "SELECT name,ucode,count(ucode) as utotal
				  FROM pd_users,geo_universities
				  WHERE ucode = university_id and ucode > 0 and reg_time > (UNIX_TIMESTAMP() - 86400*30)
				  GROUP BY ucode ORDER BY utotal DESC LIMIT 5";
        
        // 过去一月学校活跃度TOP5
        $sql [] = "SELECT name,ucode,count(name) as utotal
				  FROM xz_emaillist,pd_users,geo_universities
				  WHERE ts > DATE_FORMAT(from_unixtime(unix_timestamp()-86400*2),'%Y-%m-%d %H:%I:%S') and uid = userid and ucode = university_id and ucode > 0
				  GROUP BY name ORDER BY utotal DESC LIMIT 5";
        
        // 最近七日用户上传资料汇总
        $sql [] = "SELECT email,count(*) as total 
				  FROM pd_files,pd_users 
			      WHERE pd_files.userid = pd_users.userid 
				  AND pd_files.file_time > (unix_timestamp(now()) - 86400*7) 
				  GROUP BY email ORDER BY total DESC LIMIT 10";
        
        // 24小时用户下载量排行榜
        $sql [] = "SELECT email,count(uid) as total
				  FROM xz_emaillist,pd_users
			      WHERE xz_emaillist.ts > '" . date ( 'Y-m-d', (TIMESTAMP - 86400) ) . "' AND
				  xz_emaillist.uid = pd_users.userid
				  GROUP BY uid ORDER BY total DESC LIMIT 20";
        
        foreach ( $sql as $k => $v )
        {
            $rs [] = $this->_db->dataArray ( $v );
        }
        
        $body .= "<strong>最近七日资料上传用户排行Top10：</strong><br>";
        foreach ( $rs [5] as $k => $v )
        {
            $body .= "$v[email] : $v[total]份<br>";
        }
        
        $body .= "<strong>最近七日每日发送邮件：</strong><br>";
        foreach ( $rs [0] as $k => $v )
        {
            if ($k > 6)
                break;
            $body .= $k == 0 ? "<strong>$v[date] : $v[total]</strong><br>" : "$v[date] : $v[total]<br>";
        }
        
        $body .= "<strong>最近七日每日新增用户：</strong><br>";
        foreach ( $rs [1] as $k => $v )
        {
            $body .= $k == 0 ? "<strong>$v[date] : $v[total]</strong><br>" : "$v[date] : $v[total]<br>";
        }
        
        $body .= "<strong>过去一周热门资料TOP10：</strong><br>";
        foreach ( $rs [2] as $k => $v )
        {
            $body .= "(" . ($k + 1) . ") $v[file_name] ($v[total])<br>";
        }
        
        $body .= "<strong>过去一月用户新增学校TOP5：</strong><br>";
        foreach ( $rs [3] as $k => $v )
        {
            $body .= "(" . ($k + 1) . ") $v[name] (Month+:$v[utotal])<br>";
        }
        
        $body .= "<strong>过去一月学校活跃度TOP5：</strong><br>";
        foreach ( $rs [4] as $k => $v )
        {
            $body .= "(" . ($k + 1) . ")$v[name] (Month+:$v[utotal])<br>";
        }
        
        $body .= "<strong>24H用户发送邮件排行榜：</strong><br>";
        foreach ( $rs [6] as $k => $v )
        {
            $body .= "(" . ($k + 1) . ")$v[email] : $v[total]<br>";
        }
        
        $body .= "<br><strong>Any question please contact with WANGBO.(15626060103)</strong>";
        
        $this->sendEmail ( '', 'xzbbm@vip.qq.com', $subject, $body,'' );
        $this->sendEmail ( '', 'qg_wuheyang@qq.com', $subject, $body,'' );
        $this->sendEmail ( '', '651132692@qq.com', $subject, $body,'' );
    }
    
    
    public function checkEmail($adresses, $sign)
    {
        $encode = $this->authcode ( "this is check email number", "ENCODE", "key", 3600 );
        $a = $encode;
        $encode = rawurlencode ( $encode );
        
        $ts = date ( 'Y年m月d日', time () );
        $subject = "验证邮箱";
        // QG测试邮箱 $adresses = '709865568@qq.com';
        if ($sign == 1)
        {
            $body = <<<HTML
        <p style="line-height: 23px; color: #4C4C4C; font-family: '微软雅黑','Microsoft YaHei','lucida Grande', Verdana; font-size: 17px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px;font-weight: bold;">
亲爱的同学，欢迎你加入学长帮帮忙大家庭，请点击&nbsp; <a target="_blank" style="outline: none; text-decoration: none; cursor: pointer; color: rgb(44, 74, 119);" href="http://112.124.50.239/?action=AcceptCheckEmail&do=accpetCheckEmail&debug=on&msg={%22encode%22:%22$encode%22,%22email%22:%22$adresses%22}"><font size="4" color="#000099"><u><strong>此处</strong></u></font></a> &nbsp;验证您的邮箱，如果您并没有收取邮件的需求，请忽略此邮件。<font size="2" color="#990000">( 此验证邮件1小时内有效 )</font><br><br><br><br><br><br>我们致力于打造一个免费、开放、简洁的校内资料分享平台。</p>
<h3 style="font-size: 19px; font-weight: bold; margin: 1.17em 0px; color: #009966; font-family: 'lucida Grande', Verdana; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 23px; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px">
在这里你可以简单而方便的：</h3>
<ol style="padding: 8px; color: #333333; font-family: 'lucida Grande', Verdana; font-size: 15px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 23px; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px;">
	<li style="list-style: decimal inside; margin: 0px; padding: 0px;">
    利用PC、手机浏览器、APP客户端浏览、检索、免费获取全国<font color="#FF8C05">3000+</font>所各大高校海量校内资料；</li>
	<li style="list-style: decimal inside; margin: 0px; padding: 0px;">
    使用微博、微信、学长客户端通过扫描资料二维码方便而简洁的拷贝和传播资料，分享你的见解；</li>
	<li style="list-style: decimal inside; margin: 0px; padding: 0px;">
    上传身边资料并参加收益分享计划，从每一次予人玫瑰的行为中获取收益的同时传播知识，方便他人。</li>
</ol>
<h3 style="font-size: 19px; font-weight: bold; margin: 1.17em 0px; color: #009966; font-family: 'lucida Grande', Verdana; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 23px; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px">
以下方式都可以很快找到我们：</h3>
<blockquote style="color: #4C4C4C; font-family: 'lucida Grande', Verdana; font-size: 14px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 23px; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px;">
	新浪微博：&nbsp;&nbsp;
	<a target="_blank" style="outline: none; text-decoration: none; cursor: pointer; color: rgb(44, 74, 119);" href="http://weibo.com/xuezhangbangbangmang">@<font color="#2C4A77"><span lang="zh-cn">生机博博_Miracle</span></font></a><br>
	客服邮箱：&nbsp;&nbsp; <a target="_blank" style="outline: none; text-decoration: none; cursor: pointer; color: rgb(44, 74, 119);" href="mailto:cs@xzbbm.cn">cs@xzbbm.cn</a><br>
	微信公众号：学长帮帮忙<br>
</blockquote>
<p style="line-height: 23px; color: #CC0033; font-weight:bold ;font-family: 'lucida Grande', Verdana; font-size: 14px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px;">
如果你有任何建议、想法或者对本资料的标题、内容、版权具有疑义，请直接与我们联系，感谢你的支持与关注！</p>
<h3 style="font-size: 19px; font-weight: bold; margin: 1.17em 0px; color: #009966; font-family: 'lucida Grande', Verdana; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 23px; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px">
中国好学长，学长帮帮忙。</h3>
<p style="line-height: 23px; color: #4C4C4C; font-family: 'lucida Grande', Verdana; font-size: 14px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px;">
学长帮帮忙团队（www.xzbbm.cn） $ts </p>
HTML;
        }
        else
        {
            $body = <<<HTML
        <p style="line-height: 23px; color: #4C4C4C; font-family: '微软雅黑','Microsoft YaHei','lucida Grande', Verdana; font-size: 17px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px;font-weight: bold;">
亲爱的同学，欢迎你加入学长帮帮忙大家庭，我们检测到你尚未完成邮箱验证操作，导致邮件无法发送成功，请点击&nbsp; <a target="_blank" style="outline: none; text-decoration: none; cursor: pointer; color: rgb(44, 74, 119);" href="http://112.124.50.239/?action=AcceptCheckEmail&do=accpetCheckEmail&debug=on&msg={%22encode%22:%22$encode%22,%22email%22:%22$adresses%22}"><font size="5" color="#000099"><u><strong>此处</strong></u></font></a> &nbsp;以验证邮箱。<font size="2" color="#990000">( 此验证邮件1小时内有效 )</font><br><br><br><br><br><br>我们致力于打造一个免费、开放、简洁的校内资料分享平台。</p>
<h3 style="font-size: 19px; font-weight: bold; margin: 1.17em 0px; color: #009966; font-family: 'lucida Grande', Verdana; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 23px; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px">
在这里你可以简单而方便的：</h3>
<ol style="padding: 8px; color: #333333; font-family: 'lucida Grande', Verdana; font-size: 15px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 23px; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px;">
	<li style="list-style: decimal inside; margin: 0px; padding: 0px;">
    利用PC、手机浏览器、APP客户端浏览、检索、免费获取全国<font color="#FF8C05">3000+</font>所各大高校海量校内资料；</li>
	<li style="list-style: decimal inside; margin: 0px; padding: 0px;">
    使用微博、微信、学长客户端通过扫描资料二维码方便而简洁的拷贝和传播资料，分享你的见解；</li>
	<li style="list-style: decimal inside; margin: 0px; padding: 0px;">
    上传身边资料并参加收益分享计划，从每一次予人玫瑰的行为中获取收益的同时传播知识，方便他人。</li>
</ol>
<h3 style="font-size: 19px; font-weight: bold; margin: 1.17em 0px; color: #009966; font-family: 'lucida Grande', Verdana; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 23px; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px">
以下方式都可以很快找到我们：</h3>
<blockquote style="color: #4C4C4C; font-family: 'lucida Grande', Verdana; font-size: 14px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 23px; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px;">
	新浪微博：&nbsp;&nbsp;
	<a target="_blank" style="outline: none; text-decoration: none; cursor: pointer; color: rgb(44, 74, 119);" href="http://weibo.com/xuezhangbangbangmang">@<font color="#2C4A77"><span lang="zh-cn">生机博博_Miracle</span></font></a><br>
	客服邮箱：&nbsp;&nbsp; <a target="_blank" style="outline: none; text-decoration: none; cursor: pointer; color: rgb(44, 74, 119);" href="mailto:cs@xzbbm.cn">cs@xzbbm.cn</a><br>
	微信公众号：学长帮帮忙<br>
</blockquote>
<p style="line-height: 23px; color: #CC0033; font-weight:bold ;font-family: 'lucida Grande', Verdana; font-size: 14px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px;">
如果你有任何建议、想法或者对本资料的标题、内容、版权具有疑义，请直接与我们联系，感谢你的支持与关注！</p>
<h3 style="font-size: 19px; font-weight: bold; margin: 1.17em 0px; color: #009966; font-family: 'lucida Grande', Verdana; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 23px; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px">
中国好学长，学长帮帮忙。</h3>
<p style="line-height: 23px; color: #4C4C4C; font-family: 'lucida Grande', Verdana; font-size: 14px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; orphans: auto; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px;">
学长帮帮忙团队（www.xzbbm.cn） $ts </p>
HTML;
        }
        
        $str = "checkEmail is coming!!!!aaa" . $adresses . "the true email is " . $adresses;
        file_put_contents ( "/data/backend_service/log/testapi.log", "{$this->file_store_path} $str \n", FILE_APPEND );
        
        $this->sendEmail ( '', $adresses, $subject, $body );
    }
    
	//自编的函数统一移动到include/func.Global.php~目录下  框架会自动加载
    
    
    
    public function MailTest(){
        
        
        $body = <<<HTML
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
    	
    	.mmsgLetterHeader		{	height:23px;background:url(http://www.xzbbm.cn/images/mmsgletter_2_bg_topline.png) repeat-x 0 0; }
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
    	
    	<div class="mmsgLetterHeader" style="height:23px;background:url(http://www.xzbbm.cn/images/mmsgletter_2_bg_topline.png) repeat-x 0 0;">
    		
    	</div>
    	<div class="mmsgLetterContent" style="text-align:left;padding:30px;font-size:14px;line-height:1.5;background:url(http://www.xzbbm.cn/images/stamp.png?v=4) no-repeat top right;">
    	
    	<div>
                
        <!--  邮件正文部分 -->
    		
<!--    <p>你好!</p>
        	<p>
        	请点击以下链接确认验证新邮箱
        	</p>
        	<p style="word-wrap:break-word;word-break:break-all;">
    			<a href="https://mp.weixin.qq.com/cgi-bin/activateboundemail?uin=3076439867&amp;ticket=86e6e8b9e8c180f1c3a73906f020e764c9b8f8e0" target="_blank">https://mp.weixin.qq.com/cgi-bin/activateboundemail?uin=3076439867&amp;ticket=86e6e8b9e8c180f1c3a73906f020e764c9b8f8e0</a>
        	</p>
        	<p>
        		如果以上链接无法点击，请将上面的地址复制到你的浏览器(如IE)的地址栏来进入。 （该链接在24小时内有效，24小时后点击无效）
            </p> 
    							
    	</div>
-->
    	
    		<div class="mmsgLetterInscribe" style="padding:40px 0 0;">
    		<img class="mmsgAvatar" width="55px" src="http://i5.qhimg.com/t01161f301444422e2a.jpg" style="float:left;">
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
    	</div></div>

HTML;
        
        $this->sendEmail ( '', '599017308@qq.com', 'tests', $body );
        
    }

}
