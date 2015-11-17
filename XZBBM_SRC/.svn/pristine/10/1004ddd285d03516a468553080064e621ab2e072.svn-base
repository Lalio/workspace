<?php
if (! defined ( 'IN_SYS' )) {
    header ( "HTTP/1.1 404 Not Found" );
    die ();
}
?>
<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="title" content="学长帮帮忙 - 专注校内资料的互动与分享" />
    <meta name="keywords" content="学长帮帮忙,xzbbm,学长帮帮忙网站,学长帮帮忙官网,学长帮帮忙软件,学长帮帮忙APP,学长,学妹,师兄,师妹,考研复试,课件讲稿,复习资料,习题答案,课程设计,考试真题,毕业论文" />
    <meta name="description" content="我们专注于课件讲稿、复习资料、习题答案、课程设计、考试真题、毕业论文等校内资料的自由分享。" />
	<title><?= $pagedata['file_name']?> - 学长帮帮忙 - XZBBM.CN</title>
	<link href="http://<?= DOMAIN?>/css/global.css" rel="stylesheet">
	<link href="http://<?= DOMAIN?>/css/show.css" rel="stylesheet">
</head>
<body>
	<div class="xz-hd">
		<div class="hd-main">
			<a href="###" class="hd-logo"></a>
            <div class="hd-search">
                <form method="get" action="./?action=NewVersion&do=search" target="_blank" name="navSearchForm">
                    <input type="text" class="search-bd" autocomplete="off" name="xzs" value="微积分"/>
                    <button type="submit" class="btn-search"></button>
                </form>                        
            </div>
			<div class="hd-control">
				<span class="user-btn user-register">注册</span>
				<span class="user-btn user-login">登陆</span>
                <span class="logout">退出</span>
                <a href="http://192.168.1.118:8000/user.html" class="user-name"></a>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="main">
			<div class="content">
			<?php for($i = 0;$i < $pagedata['has_png'];$i++){?>
				<img src="http://<?= DOMAIN?>/images/file_loading.gif" data-original="http://<?= DOMAIN?>/GetFile/<?= $pagedata['file_id']?>/png/<?= TIMESTAMP?>/<?= sha1(TIMESTAMP.'sNsxCrth13LGsu60')?>/<?= $i?>" width="100%" style="display: block;">
			<?php }?>
			</div>
		</div>
		<div class="left-col">
			<div class="material-box">
				<h2 class="tit"><?= $pagedata['file_name']?></h2>
				<div class="contributor-box oh">
					<div class="contributor-headPic fl">
					<img src="<?= $pagedata['user_icon']?>" width="80" height="80" alt="" class="headPic">
					</div>
					<div class="contributor-info fr">
						<p class="name"><?= $pagedata['user_name']?></p>
						<p class="upload-date"><?= date('Y-m-d',$pagedata['file_time'])?></p>
						<p class="school"><?= $pagedata['name']?>-<?= $pagedata['college']?></p>
					</div>
				</div>
			</div>
			<ul class="action-list">
				<li class="item view">完整清晰版</li>
				<li class="item sent">发送到邮箱</li>
				<li class="item download">下载源文件</li>
				<li class="item print">云印纸质版</li>
			</ul>
		</div>
	</div>
<!-- s login -->
    <div class="login-popbox popbox" id="login-popbox">
        <span class="popbox__close J_btnClose"></span>
        <div class="popbox__main">
            <div class="main-logo"></div>
            <p class="popbox-text other-txt">使用第三方账号登录</p>
            <ul class="other">
                <li class="ico qq-ico"></li>
                <li class="ico renren-ico"></li>
                <li class="ico sina-ico"></li>
            </ul>
            <p class="popbox-text">使用邮箱或手机登录</p>
            <form action="" method="post" class="form">
                <input type="text" placeholder="邮箱或手机登录" class="form-input email"/><br/>
                <input type="password" placeholder="密码" class="form-input password"/><br/>
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
            <p class="popbox-text other-txt">使用第三方账号注册</p>
            <ul class="other">
                <li class="ico qq-ico"></li>
                <li class="ico renren-ico"></li>
                <li class="ico sina-ico"></li>
            </ul>
            <p class="popbox-text">使用邮箱注册</p>
            <form action="" method="post" class="form">
                <input type="text" placeholder="邮箱注册" class="form-input email"/>
                <span class="email-loading"></span>
                <br/>
                <input type="password" placeholder="密码" class="form-input password"/><br/>
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
<div class="sendemail-success-popbox popbox" id="sendemail-success-popbox">
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

<!-- s qrcode pay -->
    <div class="qrcode-popbox popbox">
        <span class="popbox__close J_btnClose"></span>
        <div class="popbox__main">
            <div class="main-logo"></div>
        </div>
        <div class="qrcode"></div>
        <div class="mod-txt">
            <p class="text-style qrcode-txt1">使用学长帮帮忙APP扫描</p>
            <p class="text-style qrcode-txt2">左侧二维码获取资料</p>
            <p class="text-style qrcode-txt3">首次登录APP送 <span class="qrcode-notice">300元</span> 抵用券！</p>
        </div>
    </div>
<!-- e qrcode pay -->
    <script type="text/javascript" src="http://<?= DOMAIN?>/js/jquery.js"></script>
    <script type="text/javascript" src="http://<?= DOMAIN?>/js/jscrollpane-textSlider-placeholder-switchtable-popbox.js"></script>
    <script type="text/javascript" src="http://<?= DOMAIN?>/js/jquery.cookie.js"></script>
    <script type="text/javascript" src="http://<?= DOMAIN?>/js/common.js"></script>
    <script type="text/javascript" src="http://<?= DOMAIN?>/js/jquery.lazyload.js?v=1"></script>
    <script>
        $(function(){
          $('img').lazyload({
            effect:'fadeIn'
          });
        });
    </script>
</body>
</html>