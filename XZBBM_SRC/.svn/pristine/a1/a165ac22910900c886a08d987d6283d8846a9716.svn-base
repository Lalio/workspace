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
	<title>学长帮帮忙-个人中心</title>
	<link href="http://<?= DOMAIN?>/css/global.css" rel="stylesheet">
	<link href="http://<?= DOMAIN?>/css/user.css" rel="stylesheet">
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
                <span class="user-btn user-login">登录</span>
                <span class="logout">退出</span>
                <a href="http://192.168.191.11:8000/user.html" class="user-name"></a>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="main">
			<div class="content" id="content">
				<div class="page" id="ziliao-div">
					<ul class="user__control">
						<li class="upload">
							<span class="user-con-ico"></span>
							上传资料
						</li>
						<li class="com-contorl" id="delete-file">
							<span class="user-con-ico"></span>
							移到废纸篓
						</li>
					</ul>
					<table class="user__table">
						<thead>
							<tr>
								<th style="width:8%;"><span class="all-select" data-flag="false">全选</span></th>
								<th style="width:58%;">资料名</th>
								<th style="width:17%;">上传时间</th>
								<th style="width:17%;">收益</th>
							</tr>
						</thead>
						<tbody>
							<!-- <tr>
								<td>
									<input type="checkbox" class="checkbox">
								</td>
								<td>
									<span class="passage">2011年苏州市公共基础知识A类真题及答案解析2011年苏州市公共基础知识A类真题及答案解析</span>
								</td>
								<td class="col">2014-11-04 15:20</td>
								<td class="col">18.3元</td>
							</tr>
							<tr>
								<td>
									<input type="checkbox" class="checkbox">
								</td>
								<td>
									<span class="passage">2011年苏州市公共基础知识A类真题及答案解析</span>
								</td>
								<td class="col">2014-11-04 15:20</td>
								<td class="col">18.3元</td>
							</tr>
 -->						</tbody>
					</table>
					<div class="loading">
	                    <span></span>
	                    <p>加载中...</p>
	                </div>				
				</div>
				<div class="page" id="setting-div">
					<div class="setting-page profit">
						<h2 class="page-title">我的收益</h2>
						<ul class="profit-info">
							<li class="profit-list">
								<p class="profit-subtitle">今日收益：</p>
								<p class="profit-subbd" id="today_porfit"></p>
							</li>
							<li class="profit-list">
								<p class="profit-subtitle">总收益：</p>
								<p class="profit-subbd" id="total_porfit"></p>
							</li>
							<li class="profit-list">
								<p class="profit-subtitle">资料数：</p>
								<p class="profit-subbd" id="file_count"></p>
							</li>
							<li class="profit-list">
								<p class="profit-subtitle">粉丝数：</p>
								<p class="profit-subbd" id="fans_count"></p>
							</li>
						</ul>
						<div class="profit__line">
							<h3>我的一周收益曲线</h3>
							<div id="placeholder" style="height:370px;"></div>
						</div>
					</div>
					<div class="setting-page info">
						<h2 class="page-title">我的信息</h2>
						<ul class="my-info">
							<li>
								<div class="info-wrap">
									<span class="info-detail info-name">电子邮箱</span>
									<span class="info-detail info-bd">103****434@qq.com</span>
									<span class="info-detail info-control"></span>
								</div>
							</li>
							<li>
								<div class="info-wrap">
									<span class="info-detail info-name">手机号码</span>
									<span class="info-detail info-bd">13824473672</span>
									<span class="info-detail info-control">
										<i class="info-btn">修改</i>
									</span>
								</div>
								<div class="change change6">
									<p><span class="change-name">当前手机号码</span><span class="change-info">156****8747</span></p>
									<p><span class="change-name">新手机号码</span><input type="text"/></p>
									<div class="change-submit">确定</div>
								</div>
							</li>
							<li>
								<div class="info-wrap">
									<span class="info-detail info-name">密码</span>
									<span class="info-detail info-bd">  </span>
									<span class="info-detail info-control">
										<i class="info-btn">修改</i>
									</span>
								</div>
								<div class="change change6">
									<p><span class="change-name">当前密码</span><input type="password"/></p>
									<p><span class="change-name">新密码</span><input type="password"/></p>
									<div class="change-submit">确定</div>
								</div>
							</li>
							<li>
								<div class="info-wrap">
									<span class="info-detail info-name">教育信息</span>
									<span class="info-detail info-bd">广东工业大学-应用数学学院</span>
									<span class="info-detail info-control">
										<i class="info-btn">修改</i>
									</span>
								</div>
								<div class="change change6">
									<p><span class="change-name">当前信息</span><span class="change-info">156****8747</span></p>
									<p><span class="change-name">新信息</span><input type="text"/></p>
									<div class="change-submit">确定</div>
								</div>
							</li>
							<li>
								<div class="info-wrap">
									<span class="info-detail info-name">收件地址</span>
									<span class="info-detail info-bd">广州大学城外环西路100好图书馆</span>
									<span class="info-detail info-control">
										<i class="info-btn">修改</i>
									</span>
								</div>
								<div class="change change6">
									<p><span class="change-name">当前地址</span><span class="change-info">156****8747</span></p>
									<p><span class="change-name">新地址</span><input type="text"/></p>
									<div class="change-submit">确定</div>
								</div>
							</li>
							<li>
								<div class="info-wrap">
									<span class="info-detail info-name">实名信息</span>
									<span class="info-detail info-bd">
										<i>赵家伟</i>
										<i>身份证4420******1205</i>
									</span>
									<span class="info-detail info-control">
										<i class="info-btn">修改</i>
									</span>
								</div>
								<div class="change change6">
									<p><span class="change-name">当前账号</span><span class="change-info">156****8747</span></p>
									<p><span class="change-name">新账号</span><input type="text"/></p>
									<div class="change-submit">确定</div>
								</div>
							</li>
							<li>
								<div class="info-wrap">
									<span class="info-detail info-name">支付宝账号</span>
									<span class="info-detail info-bd">103****434</span>
									<span class="info-detail info-control">
										<i class="info-btn">修改</i>
									</span>
								</div>
								<div class="change change6">
									<p><span class="change-name">当前账号</span><span class="change-info">156****8747</span></p>
									<p><span class="change-name">新账号</span><input type="text"/></p>
									<div class="change-submit">确定</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="left-col">
			<div class="user-box">
				<div class="user-headPic">
				<img src="" width="80" height="80" alt="" class="headPic">
				</div>
				<div class="user-info">
					<p class="name"></p>
				</div>
			</div>
			<ul class="action-list" id="actionList">
				<li class="item active" id="ziliao"><a href="javascript:;" class="inner">我的资料</a></li>
				<li class="item" id="setting"><a href="javascript:;" class="inner">个人设置</a></li>
			</ul>
		</div>
	</div>
	<div class="xz__ft">
        <div class="xz__ft-main">
            <div class="ft-right">
                <p> 知识就是力量</p>
                <p>粤ICP备48211903</p>
            </div>
            <div class="ft-left">
                <p>小明网络</p>
                <p>XiaoMing NetWork Co.,Ltd</p>
            </div>  
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

<!-- s upload -->
    <div class="upload-popbox popbox">
        <span class="popbox__close J_btnClose"></span>
        <div class="popbox__main">
            <div class="main-logo"></div>
        </div>
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
            <p class="upload-tips">选择学校和学院后才可上传</p>
        </div>
        <div class="uploadZone"></div>
        <!-- <div class="stop-upload"></div> -->
    </div>
<!-- e upload -->

	<script type="text/javascript" src="http://<?= DOMAIN?>/js/jquery.js"></script>
    <script type="text/javascript" src="http://<?= DOMAIN?>/js/jscrollpane-textSlider-placeholder-switchtable-popbox.js"></script>
    <script type="text/javascript" src="http://<?= DOMAIN?>/js/jquery.cookie.js"></script>
    <script type="text/javascript" src="http://<?= DOMAIN?>/js/common.js"></script>
    <script type="text/javascript" src="http://<?= DOMAIN?>/js/echarts-all.js"></script>
    <script type="text/javascript" src="http://<?= DOMAIN?>/js/md5.js"></script>
    <script type="text/javascript" src="http://<?= DOMAIN?>/js/dropzone.js"></script>
    <script type="text/javascript" src="http://<?= DOMAIN?>/js/user.js"></script>
</body>
</html>