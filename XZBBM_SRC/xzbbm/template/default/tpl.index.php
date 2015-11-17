<?php
if (! defined ( 'IN_SYS' )) {
    header ( "HTTP/1.1 404 Not Found" );
    die ();
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="keywords" content="学长帮帮忙,xzbbm,学长帮帮忙网站,学长帮帮忙官网,学长帮帮忙软件,学长帮帮忙APP,学长,学妹,师兄,师妹,考研复试,课件讲稿,复习资料,习题答案,课程设计,考试真题,毕业论文" />
	<meta name="description" content="我们专注于学习笔记、课件讲稿、复习资料、习题答案、课程设计、考试真题、毕业论文等大学校内资料的自由分享。" />
    <title>学长帮帮忙 - 卖出你的第一份学习笔记</title>
    <link href="css/global.min.css?v=2" rel="stylesheet">
    <link href="css/index.min.css?v=2" rel="stylesheet">
    <link rel="shortcut icon" href="/favicon.ico" /> 
</head>
<body>
    <div class="xz-wrap">
        <div class="xz__hd">
            <div class="xz__hd-main">
                <div class="main__control">
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
                <div class="main__info">
                    <div class="info-left">
                        <p class="info-left__title">最任性的</p>
                        <p class="info-left__title">校内知识分享平台</p>
                        <div class="info-left__code"></div>
                        <div class="info-left__codetxt">
                            <span>扫描二维码</span>
                            <div class="codetxt--scroll">
                                <ul>
                                    <li><font color="#238EAA"><span style="background-color: #E1F4F9">卖课堂笔记</span></font></li>
                                    <li><font color="#238EAA"><span style="background-color: #E1F4F9">卖毕业论文</span></font></li>
                                    <li><font color="#238EAA"><span style="background-color: #E1F4F9">卖课程设计</span></font></li>
                                    <li><font color="#238EAA"><span style="background-color: #E1F4F9">卖实验报告</span></font></li>
                                    <li><font color="#238EAA"><span style="background-color: #E1F4F9">卖课件讲稿</span></font></li>
                                    <li><font color="#238EAA"><span style="background-color: #E1F4F9">卖考试真题</span></font></li>
                                </ul>
                            </div>  
                        </div>
                        <div class="info-left__download">
                            <div class="download iphone-download">
                                <a href="" class="download-icon iphone-icon"></a>
                                <p>版本：v1.0.0.1220</p>
                                <p>更新日期：2015/04/01</p>
                            </div>
                            <div class="download android-download">
                                <a href="https://app.xzbbm.cn" class="download-icon android-icon"></a>
                                <p>版本：v1.0.0.0001</p>
                                <p>更新日期：2015/04/30</p>
                            </div>
                        </div>
                    </div>
                    <div class="info-right" id="imgscroll">
                        <div class="phone-icon">
                            <div class="img-scroll">
                                <ul class="img-scroll-list J_content">
                                    <li>
                                        <img src="img/imgscroll-2.png" alt="超过一百万份海量大学知识实时推送">
                                    </li>
                                    <li>
                                        <img src="img/imgscroll-1.png" alt="3,156所大中专院校21,534个二级学院">
                                    </li>
                                    <li>
                                        <img src="img/imgscroll-5.png" alt="垂直化的学术搜索快速定位身边资料">
                                    </li>
                                    <li>
                                        <img src="img/imgscroll-3.png" alt="分享你的学习笔记帮助学弟提高绩点">
                                    </li>
                                    <li>
                                        <img src="img/imgscroll-6.png" alt="实时赚取资料收益随时随地一键提现">
                                    </li>
                                    <li>
                                        <img src="img/imgscroll-4.png" alt="快速方便购买资料在线打印一键下单">
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <ul class="scroll-nav J_nav">
                            <li class="selected">
                                <span class="nav-rot">●</span>
                                <div class="img-info img-info-big">
                                    <span class="icon-nav"></span>
                                    <p>超过一百万份海量</p>
                                    <p>大学知识实时推送</p>
                                </div>
                            </li>
                            <li>
                                <span class="nav-rot">●</span>
                                <div class="img-info img-info-big">
                                    <span class="icon-nav"></span>
                                    <p>3,156所大中专院校</p>
                                    <p>21,534个二级学院</p>
                                </div>
                            </li>
                            <li>
                                <span class="nav-rot">●</span>
                                <div class="img-info img-info-big">
                                    <span class="icon-nav"></span>
                                    <p>垂直化的学术搜索</p>
                                    <p>快速定位身边资料</p>
                                </div>
                            </li>
						    <li>
                                <span class="nav-rot">●</span>
                                <div class="img-info img-info-big">
                                    <span class="icon-nav"></span>
                                    <p>分享你的学习笔记</p>
                                    <p>帮助学弟提高绩点</p>
                                </div>
                            </li>
                            <li>
                                <span class="nav-rot">●</span>
                                <div class="img-info img-info-big">
                                    <span class="icon-nav"></span>
                                    <p>实时赚取资料收益</p>
                                    <p>随时随地一键提现</p>
                                </div>
                            </li>
                            <li>
                                <span class="nav-rot">●</span>
                                <div class="img-info img-info-big">
                                    <span class="icon-nav"></span>
                                    <p>快速方便购买资料</p>
                                    <p>在线打印一键下单</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="xz__search">
            <div class="xz__search-main">
                <h2 class="section-name">学长们都在卖</h2>
                <div class="search__bd">
                    <div class="school-infos school-initial">
                        <ul>
                            <li>
                                <span id="clicked">A</span>
                                <span>B</span>
                            </li>
                            <li>
                                <span>C</span>
                                <span>D</span>
                            </li>
                            <li>
                                <span>E</span>
                                <span>F</span>
                            </li>
                            <li>
                                <span>G</span>
                                <span>H</span>
                            </li>
                            <li>
                                <span>J</span>
                                <span>K</span>
                            </li>
                            <li>
                                <span>L</span>
                                <span>M</span>
                            </li>
                            <li>
                                <span>N</span>
                                <span>P</span>
                            </li>
                            <li>
                                <span>Q</span>
                                <span>R</span>
                            </li>
                            <li>
                                <span>S</span>
                                <span>T</span>
                            </li>
                            <li>
                                <span>W</span>
                                <span>X</span>
                            </li>
                            <li>
                                <span>Y</span>
                                <span>Z</span>
                            </li>
                        </ul>
                    </div>
                    <div class="school-info school-name" id="school-name">
                        <ul>
                            <div id="loading"></div>
                        </ul>
                    </div>
                    <div class="school-info school-academy" id="school-academy">
                        <ul>
                            <div id="loading"></div>
                        </ul>
                    </div>
                    <div class="school-info school-data" id="academy-file">
                        <ul>
                            <div id="loading"></div>
                        </ul>
                        <div class="data-none">
                            <div class="send-code"></div>
                            <p class="code-txt">暂时没有该学院下相关资料<br/>注册/登录 或扫描左侧二维码上传第一份知识！</p>
                        </div>
                    </div>
                </div>
                <div class="next-page-btn">
                    <p>戳一下看看</p>
                    <span class="icon-next"></span>
                </div>
            </div>
        </div>
        <div class="zx__scroll">
            <div class="scroll__section1">
                <div class="scroll__section-main">
                    <div class="section-left section1-left">
                        <div class="scroll1-zuobiao"></div>
                        <div class="scroll1-icon"></div>
                        <div class="scroll1-bowen"></div>
                    </div>
                    <div class="section-right">
                        <h2 class="section-name">精确到学院</h2>
                        <p class="section-bd">校园知识精准到学院级别，一键搜索<br/>看看学长学姐都留了什么</p>
                    </div>
                </div>
            </div>
            <div class="scroll__section2">
                <div class="scroll__section-main">
                    <div class="section-left">
                        <h2 class="section-name">海量资料</h2>
                        <p class="section-bd"><span class="notice">3,156</span>所高校<span class="notice">3000</span>万大学生与你分享<br/>课堂笔记、实验报告、毕业论文、期末试卷、课后答案...</p>
                    </div>
                    <div class="section-right section2-right">
                        <div class="scroll2-water">
                            <div class="mask"></div>
                        </div>
                        
                        <div class="scroll2-txt1"></div>
                        <div class="scroll2-txt2"></div>
                    </div>
                </div>
            </div>
            <div class="scroll__section3">
                <div class="scroll__section-main">
                    <div class="section-left section3-left">
                        <div class="scroll3-circle1 scroll3-circle1com1"></div>
                        <div class="scroll3-circle1 scroll3-circle1com2"></div>
                        <div class="scroll3-circle2"></div>
                        <div class="scroll3-circle3 scroll3-circle3com1"></div>
                        <div class="scroll3-circle3 scroll3-circle3com2"></div>
                        <div class="scroll3-printer"></div>
                        <div class="scroll3-printer-ft"></div>
                        <div class="scroll3-paper"></div>
                        <div class="scroll3-needle"></div>
                    </div>
                    <div class="section-right">
                        <h2 class="section-name">极速云打印</h2>
                        <p class="section-bd">手机下单，最近打印店领取纸质版<br/>免找零，免排队，免U盘</p>
                    </div>
                </div>
            </div>
            <div class="scroll__section4">
                <div class="scroll__section-main">
                    <div class="section-left">
                        <h2 class="section-name">优质资料订阅</h2>
                        <p class="section-bd">订阅老师、学习委员、优质资料提供商<br/>第一手资料，就要新鲜</p>
                    </div>
                    <div class="section-right section4-right">
                        <div class="scroll4-book-wrap">
                            <div class="scroll4-book"></div>
                        </div>
                        <div class="scroll4-badge"></div>
                    </div>
                    <div class="hide-page-btn">
                        <p>收起</p>
                        <span class="icon-hide"></span>
                    </div>
                </div>
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

<!-- s common message box -->
    <div class="commonmessage-popbox popbox" id="commonmessage-popbox">
        <span class="popbox__close J_btnClose"></span>
        <div class="popbox__main">
            <div class="main-logo"></div>
        </div>
        <p class="success-txt text-style" id="commonmessage">注册成功</p>
    </div>
<!-- e common message box -->

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

    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/jquery.cookie.js"></script>
    <script type="text/javascript" src="js/jscrollpane-textSlider-placeholder-switchtable-popbox.js"></script>
    <script type="text/javascript" src="js/md5.js?v=2"></script>
    <script type="text/javascript" src="js/common.min.js?v=2"></script>
    <script type="text/javascript" src="js/index.min.js?v=2"></script>
    <div class="tj" style="display:none">
    	<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1254673635'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s95.cnzz.com/z_stat.php%3Fid%3D1254673635' type='text/javascript'%3E%3C/script%3E"));</script>
	</div>
	<?php if($_GET['xztoken']){?>
	   <input type="hidden" value="<?= $_GET['xztoken']?>" id="xztoken">
	<?php }?>
</body>
</html>
