<?php
if ( !defined( 'IN_SYS' ) ) {
	header( "HTTP/1.1 404 Not Found" );
	die;
}
?>
<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?= $fdata['file_name']?> (源自 <?= $this->GetUniversityInfo($fdata['ucode'],'','name')?>) - 学长帮帮忙 - XZBBM.CN</title>
	<meta name="keywords" content="<?= $fdata['file_name']?>,<?= $this->GetUniversityInfo($fdata['ucode'],'','name')?>" />
	<meta name="description" content="<?= $fdata['file_name']?> (源自 <?= $this->GetUniversityInfo($fdata['ucode'],'','name')?>)" />
	<link href="css/global.min.css?v=2" rel="stylesheet">
	<link href="css/show.min.css?v=2" rel="stylesheet">
	<link rel="shortcut icon" href="../favicon.ico" /> 
</head>
<body>
	<div class="xz-hd">
		<div class="hd-main">
			<a href="http://xzbbm.cn" class="hd-logo"></a>
            <div class="hd-search">
                <form method="get" action="./search.html" target="_blank" name="navSearchForm">
                    <input type="text" class="search-bd" autocomplete="off" name="xzs" placeholder="有没有去年的考试真题" value=""/>
                    <button type="submit" class="btn-search"></button>
                </form>                        
            </div>
			<div class="hd-control">
				<span class="user-btn user-register">注册</span>
				<span class="user-btn user-login">登录</span>
                <span class="logout">退出</span>
                <a href="../user.html" class="user-name"></a>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="main">
			<div class="content" id="main-content">
				<div class="school-ico">
                    <img onerror="this.src='//xzbbm.cn/images/sicons/0.png'" src="https://xzbbm.cn/images/sicons/<?= $this->GetUniversityInfo($fdata['ucode'],'','sicon_id')?>.png" alt="<?= $this->GetUniversityInfo($fdata['ucode'],'','name')?>" width="80" height="80">
                </div>
                <?php for($i = 0;$i < $fdata['has_png'];$i++){?>
				    <img alt="资料截图" src="//xzbbm.cn/img/file_loading.gif?v=3" data-original="//xzbbm.cn/GetFile/<?= $fdata['file_id']?>/png/<?= TIMESTAMP?>/<?= sha1(TIMESTAMP.'sNsxCrth13LGsu60')?>/<?= $i?>" width="80%" style="display: block;">
			    <?php }?>
			</div>
		</div>
		<div class="left-col">
			<div class="material-box">
				<h2 class="tit"><?= $fdata['file_name']?></h2>
				<div class="contributor-box oh">
					<div class="contributor-headPic fl">
					<img src="<?= $udata['user_icon']?>" width="65" height="65" alt="用户头像" class="headPic">
					</div>
					<div class="contributor-info fr">
						<p class="school" style="font-size: 20px"><?= $this->GetUniversityInfo($fdata['ucode'],'','name')?></p>
                        <p class="college" style="font-size: 15px"><?= $this->GetNameByCcode($fdata['ccode'])?></p>
						<p class="name" style="font-size: 5px"><?= $udata['user_name']?><br>发布于 <?= date('Y-m-d',$fdata['file_time'])?></p>

					</div>
				</div>
			</div>
            <div class="slider-state show-state"></div>
			<ul class="action-list">
			<? if(!$_COOKIE['xztoken']){ ?>
				<li class="item view" id="isHover">查看清晰版</li>
			<? }?>
				<li class="item send">发送到邮箱</li>
				<li class="item download" file_index="<?= $fdata['file_index']?>"">下载原文件</li>
			<? if(!in_array($fdata['file_extension'],array('zip','rar'))){ ?>	
				<li class="item print">云印纸质版</li>
			<? }?>
			</ul>
            <div class="share">
                <p>分享到：</p>
                <ul class="share-ico-wrap">
                    <li onclick="javascript:bShare.share(event,'qqim',0);return false;" class="share-ico qq"></li>
                    <li onclick="javascript:bShare.share(event,'weixin',0);return false;" class="share-ico wechat"></li>
                    <li onclick="javascript:bShare.share(event,'renren',0);return false;" class="share-ico renren"></li>
                    <li onclick="javascript:bShare.share(event,'sinaminiblog',0);return false;" class="share-ico sina"></li>
                </ul>
            </div>
            <p class="show-copy">学长帮帮忙平台不代表具体学校或学院</p>
		</div>
	</div>

    <div class="show-gotop"></div>

<!-- s login -->
    <div class="login-popbox popbox" id="login-popbox">
        <span class="popbox__close J_btnClose"></span>
        <div class="popbox__main">
            <div class="main-logo"></div>
            <!-- <p class="popbox-text other-txt">使用第三方账号登录</p>
            <ul class="other">
                <li class="ico qq-ico"></li>
                <li class="ico renren-ico"></li>
                <li class="ico sina-ico"></li>
            </ul> -->
            <p class="popbox-text">使用邮箱或手机登录</p>
            <form action="" method="post" class="form">
                <input type="text" placeholder="手机或邮箱登录" class="form-input email"/><br/>
                <input type="password" placeholder="密码" class="form-input password"/><br/>
                <input type="text" placeholder="点击获取验证码" class="form-input code" id="login-code"/>
                <a href="javascript:;" id="code-img-login">
                    
                </a>
                <br/>
                <div class="form-submit" id="login-form-submit">登录<span class="submit-loading"></span></div>
            </form>
            <a href="javascript:void(0)" class="toother forget-pass">忘记密码</a>
            <a href="javascript:void(0)" class="toother toregister">点击注册</a>
            <span class="register-tips">还没有账号？</span>
            <p class="error-tips" id="login-error-tips"></p>
        </div>
    </div>
    <!-- e login -->

 <!-- s register -->
    <div class="register-popbox popbox" id="register-popbox">
        <span class="popbox__close J_btnClose"></span>
        <div class="popbox__main">
            <div class="main-logo"></div>
            <!-- <p class="popbox-text other-txt">使用第三方账号注册</p>
            <ul class="other">
                <li class="ico qq-ico"></li>
                <li class="ico renren-ico"></li>
                <li class="ico sina-ico"></li>
            </ul> -->
            <p class="popbox-text">使用邮箱注册</p>
            <form action="" method="post" class="form">
                <input type="text" placeholder="邮箱注册" class="form-input email"/>
                <span class="email-loading"></span>
                <br/>
                <input type="password" placeholder="密码" class="form-input password"/><br/>
                <input type="text" placeholder="点击获取验证码" class="form-input code" id="register-code"/>
                <a href="javascript:;" id="code-img-register">
                </a>
                <br/>
                <div class="form-submit" id="register-form-submit">注册<span class="submit-loading"></span></div>
                <p class="finback-tips">你的邮箱已经注册过，重新验证可找回密码</p>
                <div class="form-submit" id="findback-form-submit">重新验证<span class="submit-loading"></span></div>
            </form>
            <p class="error-tips" id="register-error-tips"></p>
        </div>
    </div>
<!-- e register -->

 <!-- s success -->
    <div class="success-popbox popbox" id="success-popbox">
        <span class="popbox__close J_btnClose"></span>
        <div class="popbox__main">
            <div class="main-logo"></div>
        </div>
        <p class="success-txt text-style">注册成功</p>
    </div>
<!-- e success -->

<!-- s sendemail success-->
<div class="sendemail-success-popbox popbox">
    <span class="popbox__close J_btnClose"></span>
    <div class="popbox__main">
        <div class="main-logo"></div>
    </div>
    <p class="text-style">验证邮件已经发送到<span class="text-notice success-email"></span>，请点击查收邮件激活账号。</p>
    <p class="text-style">没有收到激活邮件？请耐心等待 ，或者<a href="javascript:void(0)" class="text-notice nonsend" id="resend">重新发送</a> <span class="text-notice countdown">60</span> </p>
</div>
<!-- e sendemail success-->

 <!-- s sendemail ing-->
<div class="sendemail-popbox popbox" id="sendemail-popbox">
    <span class="popbox__close J_btnClose"></span>
    <div class="popbox__main">
        <div class="main-logo"></div>
        <form action="" method="post" class="form">
            <input type="text" placeholder="填写注册的邮箱" class="form-input" id="sendemail"/><br/>
            <div class="form-submit" id="sendemail-form-submit">找回密码<span class="submit-loading"></span></div>
        </form>   
        <p class="error-tips" id="sendmail-error-tips"></p> 
    </div>
</div>
<!-- e sendemail ing-->
    
<!-- s sendfile ing-->
<div class="sendfile-popbox popbox" id="sendfile-popbox">
    <span class="popbox__close J_btnClose"></span>
    <div class="popbox__main">
        <div class="main-logo"></div>
        <form action="" method="post" class="form">
            <input type="text" placeholder="<?= $_COOKIE['email']?>" class="form-input" id="sendfile"/><br/>
            <div class="form-submit" id="sendfile-form-submit" file_index="<?= $fdata['file_index']?>">立即发送<span class="submit-loading"></span></div>
        </form>   
        <p class="error-tips" id="sendfile-error-tips"></p> 
    </div>
</div>
<!-- e sendfile ing-->
    
<!-- s sendefile success-->
<div class="sendefile-success-popbox popbox">
    <span class="popbox__close J_btnClose"></span>
    <div class="popbox__main">
        <div class="main-logo"></div>
    </div>
    <p class="text-style">当前资料已经发送到您的电子邮箱，请查收。</p>
    <p class="text-style">本窗口<span class="text-notice countdown">5</span>秒后自动关闭</p>
</div>
<!-- e sendefile success-->

<!-- s findback password -->
<div class="findback-popbox popbox">
    <span class="popbox__close J_btnClose"></span>
    <div class="popbox__main">
        <div class="main-logo"></div>
        <p class="text-style findback-txt">请输入新的密码</p>
        <form action="" method="post" class="form">
            <input type="password" placeholder="新密码" class="form-input" id="new-password"/><br/>
            <div class="form-submit" id="findback-btn">确认<span class="submit-loading"></span></div>
        </form>
        <p class="error-tips" id="findback-error-tips"></p>
    </div>
</div>
<!-- e findback password -->

<!--s yun pay -->
    <div class="pay-popbox popbox">
        <span class="show__close J_btnClose"></span>
        <ul class="tab">
            <li class="wechat-pay active">云打印下单</li>
            <li class="app-pay">APP下载</li>
        </ul>
        <div class="wechat-pay-bd">
            <div class="pay-left">
                <div class="qrcode"><img src="http://api.xzbbm.cn/?action=SuperAPI&do=OutputQr&type=print&size=380&param=<?= $fdata['file_key']?>"/></div>
                <p>请使用学长帮帮忙APP扫描二维码继续</p><p style="color: #1bc8f6;">我们为您提供24小时打印就近取件服务</p>
            </div>
            <div class="mod-txt">
                <p class="pay-name"><?= $fdata['file_name']?></p>
                <ul class="service">
                    <li><span class="choose-state chosed"></span>免费查看清晰版</li>
                    <li><span class="choose-state chosed"></span>一键发送到邮箱</li>
                    <li><span class="choose-state chosed"></span>无限下载原文件</li>
                    <li><span class="choose-state chosed"></span>在线云印纸质版</li>
                </ul>
                <p class="pay-num">￥ 0.00</p>
            </div>
        </div>
        <div class="app-pay-bd">
            <div class="pay-left">
                <div class="qrcode"><img src="http://api.xzbbm.cn/?action=SuperAPI&do=OutputQr&type=print&size=380"/></div>
                <p>请使用手机扫描二维码下载APP</p>
            </div>
            <div class="mod-txt">
                <p class="pay-name"><?= $fdata['file_name']?></p>
                <ul class="service">
                    <li><span class="choose-state chosed"></span>免费查看清晰版</li>
                    <li><span class="choose-state chosed"></span>一键发送到邮箱</li>
                    <li><span class="choose-state chosed"></span>无限下载原文件</li>
                    <li><span class="choose-state chosed"></span>在线云印纸质版</li>
                </ul>
                <p class="pay-num">￥ 0.00</p>
            </div>
        </div>
    </div>
<!--e yun pay -->

<!--s yun print -->
    <div class="print-popbox popbox">
        <span class="show__close J_btnClose"></span>
        <ul class="tab">
            <li class="xz-send active">学长云递</li>
            <li class="me-send">打印店自取</li>
        </ul>
        <div class="xz-send-bd">
            <p class="print-title"><?= $fdata['file_name']?></p>
            <form action="" method="post">
                <div class="page">
                    <span>共<i><?= $fdata['total_page']?></i>页</span>
                    <input type="text" />份
                </div>
                <div class="radio">
                    <input type="radio" /><span>单面</span>
                    <input type="radio" /><span>双面</span>
                </div>
                <div class="address">
                    <span>收货地址：</span>
                    <input type="text" class="address-input"/>
                </div>
                <p class="text">
                    <span class="pay-num">18.05</span>
                    <span class="tips">（定价￥7+打印费￥1.05+快递费￥10）</span>
                </p>
                <input type="submit" value="下单" class="send-submit">
                <p class="xztips">我们为您提供7*24小时随时打印就近取件服务</p>
            </form>
        </div>
        <div class="me-send-bd">
            <p class="print-title"><?= $fdata['file_name']?></p>
            <form action="" method="post">
                <div class="page">
                    <span>共<i><?= $fdata['total_page']?></i>页</span>
                    <input type="text" />份
                </div>
                <div class="radio">
                    <input type="radio" /><span>单面</span>
                    <input type="radio" /><span>双面</span>
                </div>
                <div class="address">
                    <span>选取打印店：</span>
                    <select>
                        <option><?= $this->GetUniversityInfo($fdata['ucode'],'','name')?>暂时没有加盟打印店</option>
                    </section>     
                </div>
                <p class="text">
                    <span class="pay-num">18.05</span>
                    <span class="tips">（定价￥7+打印费￥1.05+快递费￥10）</span>
                </p>
                <input type="submit" value="下单" class="send-submit">
            </form>
        </div>
        <div id="finfo" style="display: none;">
            <?= stripslashes((file_get_contents(get_object($this->_oss, "{$fdata[file_real_name]}.txt"))))?>
        </div>
    </div>
<!--e yun print -->

    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/jquery.cookie.js"></script>
    <script type="text/javascript" src="js/jscrollpane-textSlider-placeholder-switchtable-popbox.js"></script>
    <script type="text/javascript" src="js/common.min.js?v=2"></script>
    <script type="text/javascript" src="js/show.min.js?v=2"></script>
    <script type="text/javascript" src="js/jquery.lazyload.js"></script>
    <script>
    $(function(){
      $('img').lazyload({
        effect:'fadeIn'
      });
    });
    </script>
    <script type="text/javascript" charset="utf-8" src="http://static.bshare.cn/b/buttonLite.js#uuid=998d399e-c377-480f-9b79-ffc99ac6c625&style=-1"></script>
    <script type="text/javascript" charset="utf-8">
        bShare.addEntry({
            title: "<?= $fdata['file_name']?> - 源自 <?= $this->GetUniversityInfo($fdata['ucode'],'','name')?>",
            url: "http://xzbbm.cn/".<?= $fdata['file_key']?>,
            summary: "这份资料太给力了，找了好久终于找到了！",
            pic: "//xzbbm.cn/GetFile/<?= $fdata['file_id']?>/png/<?= TIMESTAMP?>/<?= sha1(TIMESTAMP.'sNsxCrth13LGsu60')?>/3"
        });
    </script>
    <script type="text/javascript" charset="utf-8" src="http://static.bshare.cn/b/button.js#uuid=998d399e-c377-480f-9b79-ffc99ac6c625&style=-1"></script>
    <!--  <script type="text/javascript" charset="utf-8" src="http://static.bshare.cn/b/addons/bshareMM.js#shareClass=BSHARE_IMAGE&bgColor=#F4F4F4"> </script> -->
    <div class="tj" style="display:none">
    	<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1254673635'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s95.cnzz.com/z_stat.php%3Fid%3D1254673635' type='text/javascript'%3E%3C/script%3E"));</script>
	</div>
</body>
</html>