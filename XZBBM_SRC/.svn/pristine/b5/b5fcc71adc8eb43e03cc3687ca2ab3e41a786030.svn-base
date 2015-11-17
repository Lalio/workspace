var $searchBd = $('.search-bd');
var searchInfo = ['清华大学 C语言','有没有《概率论》的课件','实验报告能不能参考一下'];
var searchIndex = 0;
var infoLen = searchInfo.length;
!function() {

	var common = {
		indexUrl : '//api.xzbbm.cn/?action=SuperAPI',
		backToTop : function(){

			var $gotop = $('.gotop');

			$(window).on('scroll',function(){
				if($(window).scrollTop() > 180){
					$gotop.addClass('gotop-show');
				}else{
					$gotop.removeClass('gotop-show');
				}
			});
			
			$gotop.on('click',function(){
				$('html,body').animate({
					'scrollTop' : '0px'
				},800);
			});

		},
		loginControl : function() {

            $('.user-login').on('click',function() {
                $('#login-popbox .form-input').val('');
                $('#login-error-tips').html('');
                wilson.popupBox("none", {boxSelector: ".login-popbox"});
            });

            $('.forget-pass,#findback-form-submit').on('click',function() {
                $('.J_maskLayer,.popbox').hide();
                wilson.popupBox("none", {boxSelector: ".sendemail-popbox"});
            });

            $('.toregister').on('click',function() {
                $('#register-popbox .password,#register-form-submit').show();
                $('#findback-form-submit,.finback-tips').hide();
                $('#register-popbox .form-input').val('');
                $('#register-error-tips').html('');
                $('.J_maskLayer,.popbox').hide();
                wilson.popupBox("none", {boxSelector: ".register-popbox"});
            });

        },
        registerControl : function() {

            $('.user-register').on('click',function() {
                $('#register-popbox .password,#register-form-submit,#register-code,#code-img-register').show();
                $('#findback-form-submit,.finback-tips').hide();
                $('#register-popbox .form-input').val('');
                $('#register-error-tips').html('');
                wilson.popupBox("none", {boxSelector: ".register-popbox"});
            });

        },
        getLoginCookie : function() {
        	if($.cookie('xztoken')){
        		$('.user-btn').hide(); 
                $('.hd-control .user-name').html($.cookie('user_name'));
                $('.hd-control .user-name,.hd-control .logout').show();
        	}
        },
        register : function() {

            var isTureEmail = false;

            $('#register-popbox .email').on('blur',function() {

                var username = $.trim($('#register-popbox .email').val());

                if(username === ''){
                    $('#register-error-tips').html('邮箱地址不能为空！');
                    isTureEmail = false;
                    return;
                }
                if(!/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i.test(username)){
                    $('#register-error-tips').html('邮箱格式不正确，建议使用QQ或网易邮箱！');
                    isTureEmail = false;
                    return;
                }else{
                    $('.email-loading').show();
                    
                    $.ajax({
                        url : common.indexUrl + '&do=WebTestEmail&debug=on&msg={"email":"' + username + '"}',
                        type : 'get',
                        dataType : 'jsonp',
                        success : function(data) {
                            
                            $('.email-loading').hide();

                            if(data.rcode === 0){
                                isTureEmail = true;
                                $('#register-error-tips').html('');
                                return;
                            }

                            if(!data.isOk){
                                isTureEmail = false;
                                if(data.error == '亲，你的邮箱号码已注册！'){
                                    $('#register-popbox .password,#register-form-submit,#register-code,#code-img-register').hide();
                                    $('#findback-form-submit,.finback-tips').show();
                                }else{
                                    $('#register-error-tips').html(data.error);
                                }
                            }

                        }
                    });
                }

            });

            $('#register-form-submit').on('click',function(e) {
                
                e.preventDefault();
                var username = $.trim($('#register-popbox .email').val());
                var password = $.trim($('#register-popbox .password').val());
                var code = $.trim($('#register-popbox .code').val());

                if(username === '' || password === '' || code === ''){
                    $('#register-error-tips').html('邮箱,密码或者验证码不能为空！');
                    isTureEmail = false;
                    return;
                }

                if(isTureEmail){
                    $('.submit-loading').show();
                    $.ajax({
                        url : common.indexUrl + '&do=WebRegister&debug=on&msg={"email":"'+username+'","password":"'+password+'","yzm":"'+code+'","xztoken":"'+$('#xztoken').val()+'"}',
                        type : 'get',
                        dataType : 'jsonp',
                        success : function(data) {

                            $('.submit-loading').hide();

                            if(!data.isOk && typeof data.isOk != "undefined"){
                                $('#register-error-tips').html(data.error);
                                return;
                            }
                            
                            $('.J_maskLayer,#register-popbox').hide();

                            wilson.popupBox("none", {boxSelector: "#success-popbox"});
                            $('#success-popbox .success-email').html(username);
                        }
                    });
                }
            });

        },
        login : function() {

            $('#login-form-submit').on('click',function(e) {
                
                e.preventDefault();
                var username = $.trim($('#login-popbox .email').val());
                var password = $.trim($('#login-popbox .password').val());
                var code = $.trim($('#login-popbox .code').val());
                
                if(username === '' || password === '' || code == ''){
                    $('#login-error-tips').html('用户名，密码或者验证码不能为空！');
                    return;
                }

                
                $('.submit-loading').show();
                $.ajax({
                    url : common.indexUrl + '&do=WebLogin&debug=on&msg={"email":"'+username+'","password":"'+password+'","yzm":"'+ code +'"}',
                    type : 'get',
                    dataType : 'jsonp',
                    success : function(data) {
                        $('.submit-loading').hide();

                        if(!data.isOk && typeof data.isOk != "undefined"){
                            $('#login-error-tips').html(data.error);
                            this.changeCodeImg();
                            return;
                        }
                                                
                        $('.J_maskLayer,#login-popbox,.user-btn').hide(); 
                        $('.hd-control .user-name,.hd-control .logout').show();
                        $('.hd-control .user-name').html(data.user.user_name);
                        
                        $.cookie('userid',data.user.userid,{
                            path : '/',
                            expires : 7,
                        });
                        $.cookie('phone',data.user.phone,{
                            path : '/',
                            expires : 7,
                        });
                        $.cookie('email',data.user.email,{
                            path : '/',
                            expires : 7,
                        });
                        $.cookie('user_name',data.user.user_name,{
                            path : '/',
                            expires : 7,
                        });
                        $.cookie('xztoken',data.user.xztoken,{
                            path : '/',
                            expires : 7,
                        });
                        $.cookie('user_icon',data.user.user_icon,{
                            path : '/',
                            expires : 7,
                        });
                        $.cookie('ccode',data.user.ccode,{
                            path : '/',
                            expires : 7,
                        });
                        $.cookie('ucode',data.user.ucode,{
                            path : '/',
                            expires : 7,
                        });
                        $.cookie('uploadCcode',data.user.ccode,{
                            path : '/',
                            expires : 7,
                        });
                        $.cookie('uploadUcode',data.user.ucode,{
                            path : '/',
                            expires : 7,
                        });
                        location.reload();
                    }
                });
            });

        },
        changeCodeImg : function() {

            
            $("#login-code,#register-code").on('focus',function(){
                var src = common.indexUrl + '&do=GVerifyCode&headerType=png&ts=' + Math.random();
                var imgHtml = '<img src="'+src+'" class="code-img" alt="">'; 
                $(this).next('a').html(imgHtml);
            });

            $('#code-img-login,#code-img-register').on('click',function(){
                var src = common.indexUrl + '&do=GVerifyCode&headerType=png&ts=' + Math.random();
                var imgHtml = '<img src="'+src+'" class="code-img" alt="">'; 
                $(this).html(imgHtml);
            });

        },
        logout : function() {

            $('.logout').on('click',function() {
                $.cookie('email', null);
                $.cookie('phone', null);
                $.cookie('xztoken', null);
                $.cookie('ccode', null);
                $.cookie('ucode', null);
                $.cookie('findback_email', null);
                $.cookie('user_icon', null);
                $.cookie('reset_token', null);
                $.cookie('userid', null);
                $.cookie('user_name', null);
                $.cookie('uploadUcode', null);
                $.cookie('uploadCcode', null);
                location.reload();
            });

        },
        emailTimer : null,
        sendEmail : function() {

            $('#sendemail-form-submit').on('click',function() {
                clearInterval(common.emailTimer);
                var value =$.trim($('#sendemail').val());
                if(value === ''){
                    $('#sendmail-error-tips').html('邮箱地址不能为空！'); 
                    return;
                }
                if(!/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i.test(value)){
                   $('#sendmail-error-tips').html('邮箱格式不正确，建议使用QQ或网易邮箱。！'); 
                    return;
                }

                $.cookie('findback_email',value);
                common.sendEmailAjax(value);
                
            });

        },
        sendFile : function() {

        	$('#sendfile-form-submit').on('click',function() {
                clearInterval(common.emailTimer);
                var email = $.trim($('#sendfile').val())?$.trim($('#sendfile').val()):$.cookie('email');
                var file_index =$.trim($(this).attr('file_index'));
                if(email === ''){
                    $('#sendfile-error-tips').html('邮箱地址不能为空！'); 
                    return;
                }

                if(!/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i.test(email)){
                   $('#sendfile-error-tips').html('邮箱格式不正确！'); 
                   return;
                }
                common.sendFileAjax(email,file_index);
            });

        },
        sendFileAjax : function(email,file_index) {

            $('.submit-loading').show();
            var isSend = false;
            $.ajax({
                url : common.indexUrl + '&do=SendMail&debug=on&msg={"file_index":"'+file_index+'","xztoken":"'+$.cookie('xztoken')+'","email":"'+email+'"}',
                type : 'get',
                dataType : 'jsonp',
                success : function(data) {
                	
                	$('.submit-loading').hide();
                	
                	if(data.rcode == 1){
                		$('#sendfile-error-tips').html(data.error); 
                	}else{
                        
                		$('.J_maskLayer').hide();
                        wilson.popupBox("none", {boxSelector: ".sendefile-success-popbox"});

                        var countdown = 4;
                        common.emailTimer = setInterval(function() {
                            if(countdown == 1) {
                                location.reload();
                            }
                            $('.countdown').html(countdown--);
                        },1000);
                	}
                    
                }
            });

        },
        sendEmailAjax : function(value) {

            $('.submit-loading').show();
            var isSend = false;
            $.ajax({
                url : common.indexUrl + '&do=WebChangePassword&debug=on&msg={"temp_ip":"http://xzbbm.cn/index.html","email":"'+value+'"}',
                type : 'get',
                dataType : 'jsonp',
                success : function(data) {
                    $('.submit-loading').hide();
                                            
                    $('.J_maskLayer,#sendemail-popbox').hide(); 
                    wilson.popupBox("none", {boxSelector: ".sendemail-success-popbox"});
                    $('.success-email').html(value);

                    $('.countdown').off('click',function() {
                        common.sendEmailAjax(value);
                    });

                    isSend = false;
                    $('#resend').addClass('nonsend');

                    var countdown = 59;
                    common.emailTimer = setInterval(function() {
                        if(countdown == 0) {
                            clearInterval(common.emailTimer);
                            isSend = true;
                            $('#resend').removeClass('nonsend');
                            $('#resend').on('click',function() {
                                if(isSend){
                                    common.sendEmailAjax(value);
                                    isSend = false;
                                }
                            });
                        }
                        $('.countdown').html(countdown--);
                    },1000);
                }
            });

        },	
        userToIndex : function() { //没有登录就跳回首页

            if(window.location.href.indexOf('user') != -1 && !$.cookie('xztoken'))
                window.location = 'http://xzbbm.cn';

        },
		placeHolder : function() {

            //input placeholder
            $('.form-input').placeholder();

			//定时换取placeholder
            var timer = setInterval(function() {
                $searchBd[0].setAttribute('placeholder',searchInfo[searchIndex]);
                if(searchIndex < infoLen-1){
                    searchIndex++;
                }else{
                    searchIndex = 0;
                }
            },5000);
             //触发input焦点，清除计时器
             $searchBd.on('focus',function() {
                clearInterval(timer);
             });

		}, 	
        init : function() {
        	this.getLoginCookie();
			this.backToTop();	
			this.loginControl();
            this.registerControl();
            this.register();
            this.login();
            this.changeCodeImg();
            this.logout();
            this.sendEmail();
            this.userToIndex();
            this.placeHolder();
            this.sendFile();	
		}
	}

    if(typeof common !== "undefined")  window.common = common;

	common.init();
} ();

$.extend({
	  getUrlVars: function(){
	    var vars = [], hash;
	    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
	    for(var i = 0; i < hashes.length; i++)
	    {
	      hash = hashes[i].split('=');
	      vars.push(hash[0]);
	      vars[hash[0]] = hash[1];
	    }
	    return vars;
	  },
	  getUrlVar: function(name){
	    return $.getUrlVars()[name];
	  }
});


