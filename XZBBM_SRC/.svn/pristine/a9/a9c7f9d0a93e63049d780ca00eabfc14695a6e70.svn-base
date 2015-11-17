<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;
}
?>
<!DOCTYPE html>
<html lang="zh">
    <head>
        <meta charset="utf-8">
        <title>学长帮帮忙 - 中国领先的高校教辅资源分享平台</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="学长帮帮忙 - 国内领先的高校教辅资源分享平台">
        <meta name="author" content="bo.wang">

        <!-- CSS -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Lobster">
        <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Lato:400,700'>
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/style.css">

        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!-- Favicon and touch icons -->
        <link rel="shortcut icon" href="assets/ico/favicon.ico">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">

    </head>

    <body>

        <!-- Header -->
        <div class="container">
            <div class="header row">
                <div class="logo span4">
                    <h1><span>www.xzbbm.cn</span></h1>
                </div>
                <div class="call-us span8">
                    <p>新浪微博: <span><a href="http://www.weibo.com/xuezhangbangbangmang" target="_blank">@学长帮帮忙</a></span>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;E-mail: <span><a href="mailto:xzbbm@vip.163.com">xzbbm@vip.163.com</a></span></p>
                </div>
            </div>
        </div>

        <!-- Coming Soon -->
        <div class="coming-soon">
            <div class="inner-bg">
                <div class="container">
                    <div class="row">
                        <div class="span12">
                            <h2></h2>
                            <h2>学长帮帮忙，学长为你想的更多。</h2>
                            <p>攻城师们正在夜以继日的开发全新的网站和客户端，距离产品正式上线还有：</p>
                            <div class="timer">
                                <div class="days-wrapper">
                                    <span class="days"></span> <br>days
                                </div>
                                <div class="hours-wrapper">
                                    <span class="hours"></span> <br>hours
                                </div>
                                <div class="minutes-wrapper">
                                    <span class="minutes"></span> <br>minutes
                                </div>
                                <div class="seconds-wrapper">
                                    <span class="seconds"></span> <br>seconds
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="container">
            <div class="row">
                <div class="span12 subscribe">
                    <p><b>在时光飞逝的 1195 个日日夜夜里，已有 <span id="u_total">15712</span> 位同学加入学长帮帮忙（工大乐享），我们正在分享超过 <span id="f_total">196648</span> 份免费学术资料</b></p>
<!--                     <div class="row">
                        <div class="span12 social">
                            <a href="javascript:;" class="facebook" rel="tooltip" data-placement="top" data-original-title="Facebook"></a>
                            <a href="javascript:;" class="twitter" rel="tooltip" data-placement="top" data-original-title="Twitter"></a>
                            <a href="javascript:;" class="dribbble" rel="tooltip" data-placement="top" data-original-title="Dribbble"></a>
                            <a href="javascript:;" class="googleplus" rel="tooltip" data-placement="top" data-original-title="Google Plus"></a>
                            <a href="javascript:;" class="pinterest" rel="tooltip" data-placement="top" data-original-title="Pinterest"></a>
                            <a href="javascript:;" class="flickr" rel="tooltip" data-placement="top" data-original-title="Flickr"></a>
                        </div>
                    </div> -->
                    <p>Copyright © 2012-2013 xzbbm.cn All Rights Reserved  皖ICP备13015263号-1</p>
<!--                     <form class="form-inline" action="assets/sendmail.php" method="post">
                        <input type="text" name="email" placeholder="Enter your email...">
                        <button type="submit" class="btn">Subscribe</button>
                    </form>
                    <div class="success-message"></div>
                    <div class="error-message"></div> -->
                </div>
            </div>  
        </div>

        <div class="qr_code" style="width:150px;height:155px;position:absolute;right:12px;bottom:10px;border: solid 1px #432B6A;z-index:99999;"><a href="./?do=App" target="_blank"><img src="./images/qr_code.png" height="100%" width="150px"></a></div>
        <!-- Javascript -->
        <script src="assets/js/jquery-1.8.2.min.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/js/jquery.backstretch.min.js"></script>
        <script src="assets/js/jquery.countdown.js"></script>
        <script src="assets/js/scripts.js"></script>
        <script type="text/javascript">
            jQuery(document).ready(function(){
                
                //自动更新 by bo.wang
                setInterval(function(){
                    var u_total = $("#u_total");
                    u_total.fadeOut();
                    u_total.html(parseInt(u_total.text())+1);
                    u_total.fadeIn();
                },5000);

                setInterval(function(){
                    var f_total = $("#f_total");
                    f_total.fadeOut();
                    f_total.html(parseInt(f_total.text())+2);
                    f_total.fadeIn();
                },8000);

                setInterval(function(){
                    var f_total = $(".qr_code");
                    f_total.fadeOut();
                    f_total.fadeIn();
                },5000);

            });
        </script>

    </body>

</html>