<?php
if ( !defined( 'IN_SYS' ) ) {
    header( "HTTP/1.1 404 Not Found" );
    die;
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="Keywords" content="校内知识照片、课堂笔记、期末试卷、往年试题、课后答案、大学资料、课件、课程设计、简历模板、面试真题、学长、学长帮帮忙">
    <meta name="description" content="学长帮帮忙是第一校内知识分享平台，3000万大学生学习所需。学长包含获取和分享优质的课堂笔记、期末试卷、课后答案、老师课件、课程设计、知识照片、学习资料等各类高度集中的大学校内知识。">
    <title>学长搜索 - 最垂直的高校校内知识搜索引擎</title>
    <link href="css/global.min.css" rel="stylesheet">
    <link href="css/search.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="/favicon.ico" /> 
</head>
<body>
    <div class="xz-wrap xz-search-wrap">
        <div class="input-mask"></div>
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
                    <a href="./user.html" class="user-name"></a>
                </div>
			</div>
    	</div>
    	<div class="xz-search">
    		<div class="search-main">
                <div class="search-con">
                    <div class="search-name-bwrap school-bwrap">
                        <span class="close-search-name"></span>
                        <div class="search-name-wrap school-wrap" data-flag="school" data-default="学校">
                            <span class="search-name school-name">学校</span>
                            <span class="arrow arrow-down"></span>
                            <div class="choose-auto school-auto">
                                <div class="input-text-wrap school-input-wrap">
                                    <span class="ajax-loading"></span>
                                    <input type="text" class="input-text school-input"/>
                                </div>
                                <ul class="search-list school-search-list" id="school-search-list"></ul>  
                                <p class="input-tips">请输入学校</p> 
                                <p class="input-none">找不到结果</p>
                            </div>
                        </div>
                    </div>
                    <div class="search-name-bwrap college-bwrap">
                        <span class="close-search-name"></span>
                        <div class="search-name-wrap college-wrap" data-flag="college" data-default="学院">
                            <span class="search-name college-name college-name-not">学院</span>
                            <span class="arrow arrow-not"></span>
                            <div class="choose-auto college-auto">
                                <div class="input-text-wrap college-input-wrap">
                                    <span class="ajax-loading"></span>
                                    <input type="text" class="input-text college-input"/>
                                </div>
                                <ul class="search-list college-search-list" id="college-search-list"></ul>  
                                <p class="input-none">找不到结果</p> 
                                <p class="input-tips">请输入学院</p>
                                 
                            </div>
                        </div> 
                    </div>
                    <div class="search-name-bwrap sort-bwrap">
                        <span class="close-search-name"></span>
                        <div class="search-name-wrap sort-wrap" data-flag="sort" data-default="默认排序">
                            <span class="search-name sort-name">默认排序</span>
                            <span class="arrow arrow-down"></span>
                            <div class="choose-auto sort-auto">
                                <ul class="search-list sort-search-list">
                                    <li>默认排序</li>
                                    <li>按好评率排序</li>
                                    <li>按下载量排序</li>
                                    <li>按浏览量排序</li>
                                </ul>   
                            </div>
                        </div>
                    </div>
                    <!-- <div class="search-name-bwrap price-bwrap">
                        <span class="close-search-name"></span>
                        <div class="search-name-wrap price-wrap" data-flag="price" data-default="价格不限">
                            <span class="search-name price-name">价格不限</span>
                            <span class="arrow arrow-down"></span>
                            <div class="choose-auto price-auto">
                                <ul class="search-list price-search-list">
                                    <li>价格不限</li>
                                    <li>付费</li>
                                    <li>免费</li>
                                </ul>   
                            </div>                    
                        </div>
                    </div> -->
                </div>
                <div class="search-info">
                    <p class="search-result"></p>
                    <p class="style-change list-active">
                        <span class="style-list"></span>
                        <span class="style-block"></span>
                    </p>
                </div>
    		</div>
    	</div>
        <div class="xz-bd">
            <div class="bd-main">
                <div class="result-bd">
                    <ul class="result-list" id="result-list">
                    </ul>
                    <ul class="result-block" id="result-block">
                    </ul>
                </div>
                <div class="loading">
                    <span></span>
                    <p>加载中...</p>
                </div>
                <p class="load-over">数据加载完毕</p>
            </div>
        </div>
        <div class="xz__ft">
            <div class="xz__ft-main">
                <div class="ft-right">
                    <p> 知识就是力量</p>
                    <p><a href="http://www.miibeian.gov.cn" target="_blank">粤ICP备15020442号-3</a></p>
                </div>
                <div class="ft-left">
                    <p><a href="mailto:contact@xiaoming-inc.com">举报信箱</a> | 020-25421129</p>
                    <p>XiaoMing NetWork Co.,Ltd</p>
                </div>  
            </div>
        </div>
        <div class="gotop"></div>
    </div>
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

    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/jquery.cookie.js"></script>
    <script type="text/javascript" src="js/jscrollpane-textSlider-placeholder-switchtable-popbox.js"></script>
    <script type="text/javascript" src="js/common.min.js?v=1"></script>
    <script type="text/javascript" src="js/search.min.js?v=4"></script>
    <div class="tj" style="display:none">
    	<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1254673635'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s95.cnzz.com/z_stat.php%3Fid%3D1254673635' type='text/javascript'%3E%3C/script%3E"));</script>
	</div>
</body>
</html>
