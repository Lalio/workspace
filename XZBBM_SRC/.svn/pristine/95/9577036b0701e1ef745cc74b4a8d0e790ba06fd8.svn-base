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
	<title>个人中心 - 学长帮帮忙</title>
	<link href="css/global.min.css?v=2" rel="stylesheet">
	<link href="css/user.min.css?v=2" rel="stylesheet">
	<link rel="shortcut icon" href="/favicon.ico" /> 
</head>
<body>
	<div class="xz-hd">
		<div class="hd-main">
			<a href="./" class="hd-logo"></a>
            <div class="hd-search">
                <form method="get" action="./search.html" target="_blank" name="navSearchForm">
                    <input type="text" class="search-bd" autocomplete="off" name="xzs" placeholder="有没有《工程力学》的课后答案" value=""/>
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
									<span class="passage-ico pdf"></span><span class="passage">2011年苏州市公共基础知识A类真题及答案解析2011年苏州市公共基础知识A类真题及答案解析</span>
								</td>
								<td class="col">2014-11-04 15:20</td>
								<td class="col">18.3元</td>
							</tr> -->
						</tbody>
					</table>
					<div class="loading">
	                    <span></span>
	                    <p>加载中...</p>
	                </div>				
				</div>
				<div class="page" id="setting-div">
					<div class="setting-page info">
						<h2 class="page-title">资料编辑</h2>
						<ul class="my-info">
						    <li>
								<div class="info-wrap">
									<span class="info-detail info-name">昵称</span>
									<span class="info-detail info-bd"><?= $uinfo['user_name']?></span>
									<span class="info-detail info-control">
										<i class="info-btn">修改</i>
									</span>
								</div>
								<div class="change change6">
									<p><span class="change-name">新昵称</span><input class="form-input" type="text" id="user_name" placeholder="<?= $uinfo['user_name']?>"/></p>
									<div class="change-submit" for="user_name">确定</div>
								</div>
							</li>
						    <li>
								<div class="info-wrap">
									<span class="info-detail info-name">学校</span>
									<span class="info-detail info-bd"><?= $this->GetUniversityInfo($uinfo['ucode'],'','name')?></span>
									<span class="info-detail info-control">
										<i class="info-btn">修改</i>
									</span>
								</div>
								<div class="change change6">
									<p><span class="change-name">学校</span><input class="form-input" id="ucode" type="text" placeholder="<?= $this->GetUniversityInfo($uinfo['ucode'],'','name')?>" /></p>
									<div class="change-submit" for="ucode">确定</div>
								</div>
							</li>
					        <li>
								<div class="info-wrap">
									<span class="info-detail info-name">学院</span>
									<span class="info-detail info-bd" id="ccodetext"><?= $this->GetNameByCcode($uinfo['ccode'])?></span>
									<span class="info-detail info-control">
										<i class="info-btn">修改</i>
									</span>
								</div>
								<div class="change change6">
									<p><span class="change-name">学院</span><input class="form-input" id="ccode" type="text" placeholder="<?= $this->GetNameByCcode($uinfo['ccode'])?>"/></p>
									<div class="change-submit" for="ccode">确定</div>
								</div>
							</li>
							<li>
								<div class="info-wrap">
									<span class="info-detail info-name">电子邮箱</span>
									<span class="info-detail info-bd"><?= $uinfo['email']?></span>
									<span class="info-detail info-control"></span>
								</div>
							</li>
							<li>
								<div class="info-wrap">
									<span class="info-detail info-name">手机号码</span>
									<span class="info-detail info-bd"><?= $uinfo['phone']?></span>
									<span class="info-detail info-control">
										<i class="info-btn">修改</i>
									</span>
								</div>
								<div class="change change6">
									<p><span class="change-name">新手机号码</span><input class="form-input" type="text" id="phone" placeholder="<?= $uinfo['phone']?>"/></p>
									<div class="change-submit" for="phone">确定</div>
								</div>
							</li>
							<li>
								<div class="info-wrap">
									<span class="info-detail info-name">密码</span>
									<span class="info-detail info-bd"></span>
									<span class="info-detail info-control">
										<i class="info-btn">修改</i>
									</span>
								</div>
								<div class="change change6">
									<p><span class="change-name">新密码</span><input class="form-input" type="password" id="password"/></p>
									<div class="change-submit" for="password">确定</div>
								</div>
							</li>
							<li>
								<div class="info-wrap">
									<span class="info-detail info-name">云印收件地址</span>
									<span class="info-detail info-bd"><?= $uinfo['address']?></span>
									<span class="info-detail info-control">
										<i class="info-btn">修改</i>
									</span>
								</div>
								<div class="change change6">
									<p><span class="change-name">新地址</span><input class="form-input" type="text" id="address" placeholder="<?= $uinfo['address']?>"/></p>
									<div class="change-submit" for="address">确定</div>
								</div>
							</li>
							<li>
								<div class="info-wrap">
									<span class="info-detail info-name">姓名</span>
									<span class="info-detail info-bd">
										<i><?= $uinfo['real_name']?></i>
									</span>
									<span class="info-detail info-control">
										<i class="info-btn">修改</i>
									</span>
								</div>
								<div class="change change6">
									<p><span class="change-name">姓名</span><input class="form-input" type="text" id="real_name" placeholder="<?= $uinfo['real_name']?>"></p>
									<div class="change-submit" for="real_name">确定</div>
								</div>
							</li>
							<li>
								<div class="info-wrap">
									<span class="info-detail info-name">身份证</span>
									<span class="info-detail info-bd">
										<i><?= $uinfo['sfz']?></i>
									</span>
									<span class="info-detail info-control">
										<i class="info-btn">修改</i>
									</span>
								</div>
								<div class="change change6">
									<p><span class="change-name">身份证</span><input class="form-input" type="text" id="sfz" placeholder="<?= $uinfo['sfz']?>"></p>
									<div class="change-submit" for="sfz">确定</div>
								</div>
							</li>
							<li>
								<div class="info-wrap">
									<span class="info-detail info-name">支付宝账号</span>
									<span class="info-detail info-bd"><?= $uinfo['pay_account']?></span>
									<span class="info-detail info-control">
										<i class="info-btn">修改</i>
									</span>
								</div>
								<div class="change change6">
									<p><span class="change-name">新账号</span><input class="form-input" type="text" id="pay_account" placeholder="<?= $uinfo['pay_account']?>"/></p>
									<div class="change-submit" for="pay_account">确定</div>
								</div>
							</li>
							<!-- 
							<li>
								<div class="info-wrap">
									<span class="info-detail info-name">APP推广链接(<?= $invite['app']?>)</span>
									<span class="info-detail info-bd">
										<input type="text" size="40" value="https://xzbbm.cn/invite/app/<?= $_COOKIE['xztoken']?>" />					
                                        &nbsp;&nbsp;&nbsp;
                                        <object id="clippy" class="clippy" width="62" height="24" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000">
                                			<param value="https://xzbbm.cn/etc/copy/copy.swf" name="movie">
                                			<param value="always" name="allowScriptAccess">
                                			<param value="high" name="quality">
                                			<param value="noscale" name="scale">
                                			<param value="txt=https://xzbbm.cn/invite/app/<?= $_COOKIE['xztoken']?>" name="FlashVars">
                                			<param value="opaque" name="wmode">
                                			<embed width="62" height="24" align="middle" flashvars="txt=https://xzbbm.cn/invite/app/<?= $_COOKIE['xztoken']?>" src="https://xzbbm.cn/etc/copy/copy.swf" quality="high" wmode="transparent" allowscriptaccess="sameDomain" pluginspage="http://www.adobe.com/go/getflashplayer" type="application/x-shockwave-flash">
                                		</object>
									</span>
								</div>
							</li>
							 -->
							<li>
								<div class="info-wrap">
									<span class="info-detail info-name">邀请注册链接</span>
								    <span class="info-detail info-bd">
										<input type="text" size="60" class="form-input" value="https://xzbbm.cn/invite/reg/<?= $_COOKIE['xztoken']?>" placeholder="https://xzbbm.cn/invite/reg/<?= $_COOKIE['xztoken']?>" />					
                                        &nbsp;&nbsp;&nbsp;
                                        <object id="clippy" class="clippy" width="62" height="24" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000">
                                			<param value="https://xzbbm.cn/etc/copy/copy.swf" name="movie">
                                			<param value="always" name="allowScriptAccess">
                                			<param value="high" name="quality">
                                			<param value="noscale" name="scale">
                                			<param value="txt=https://xzbbm.cn/invite/reg/<?= $_COOKIE['xztoken']?>" name="FlashVars">
                                			<param value="opaque" name="wmode">
                                			<embed width="62" height="24" align="middle" flashvars="txt=https://xzbbm.cn/invite/reg/<?= $_COOKIE['xztoken']?>" src="https://xzbbm.cn/etc/copy/copy.swf" quality="high" wmode="transparent" allowscriptaccess="sameDomain" pluginspage="http://www.adobe.com/go/getflashplayer" type="application/x-shockwave-flash">
                                		</object>
                                		&nbsp;&nbsp;&nbsp;&nbsp;
                                		<font color="blue">吸引粉丝：(<?= $invite['reg']?$invite['reg']:0?>)</font>
                                		&nbsp;&nbsp;
                                		<font color="green">有效注册：(<?= $invite['reg_valid']?$invite['reg_valid']:0?>)</font>
                                		<!-- <font>(<?= $invite['reg']?$invite['reg']:0?>)</font> -->
									</span>
								</div>
							</li>
						</ul>
					</div>
				</div>
				<div class="page" id="profile-div">
					<div class="profile-page profit">
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
				<li class="item active" id="ziliao"><a href="javascript:;" class="inner">我的发布</a></li>
				<li class="item" id="setting"><a href="javascript:;" class="inner">个人设置</a></li>
				<li class="item" id="profile"><a href="javascript:;" class="inner">资料收益</a></li>
			</ul>
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
        <span class="popbox__close"></span>
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
    
<!-- s common message box -->
    <div class="commonmessage-popbox popbox" id="commonmessage-popbox">
        <span class="popbox__close J_btnClose"></span>
        <div class="popbox__main">
            <div class="main-logo"></div>
        </div>
        <p class="success-txt text-style" id="commonmessage">注册成功</p>
    </div>
<!-- e common message box -->
    
<!-- s time message box-->
    <div class="timemessage-popbox popbox" id="timemessage-popbox">
        <span class="popbox__close J_btnClose"></span>
        <div class="popbox__main">
            <div class="main-logo"></div>
        </div>
        <p class="text-style" id="timemessage"></p>
    </div>
<!-- e time message box-->

<!--s edit -->
    <div class="file-popbox popbox">
        <span class="show__close J_btnClose"></span>
        <div class="file__form">
            <div class="input">
                <label>资料标题</label>
                <input type="text" class="file__title">
            </div>
            <div class="input">
                <label>语音描述</label>
                <span class="voice"><i class="time">18</i><i class="close"></i></span>
            </div>
            <div class="textarea">
                <label>资料介绍</label>
                <textarea class="file__des">
                </textarea>
            </div>
            <div class="input">
                <label>资料价格</label>
                <span class="payOrFree free">
                    <i class="isFree"></i>
                    <i class="isPay"></i>
                </span>
                <input type="text" class="file__price">元
            </div>
            <div class="input">
                <label>资料归属</label>
                <input type="text" class="file__to">
                <input type="text" class="file__to">
            </div>
            <div class="file__submit">保存</div>
        </div>
    </div>
<!--e edit -->

	<script type="text/javascript" src="js/jquery.js?v=2"></script>
    <script type="text/javascript" src="js/jscrollpane-textSlider-placeholder-switchtable-popbox.js?v=2"></script>
    <script type="text/javascript" src="js/jquery.cookie.js?v=2"></script>
    <script type="text/javascript" src="js/common.min.js?v=2"></script>
    <script type="text/javascript" src="js/echarts-all.js?v=2"></script>
    <script type="text/javascript" src="js/md5.js?v=2"></script>
    <script type="text/javascript" src="js/dropzone.js?v=2"></script>
    <script type="text/javascript" src="js/user.min.js?v=3"></script>
</body>
</html>