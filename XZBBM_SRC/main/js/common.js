!function() {

	var common = {
		indexUrl : 'http://112.124.50.239/?action=SuperAPI',
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
                $('#register-popbox .password,#register-form-submit').show();
                $('#findback-form-submit,.finback-tips').hide();
                $('#register-popbox .form-input').val('');
                $('#register-error-tips').html('');
                wilson.popupBox("none", {boxSelector: ".register-popbox"});
            });

        },
        getLoginCookie : function() {

            var email = $.cookie('email');
            if(email){
                $('.user-btn').hide(); 
                $('.hd-control .user-name').html(email);
                $('.hd-control .user-name,.hd-control .logout').show();
            }

        },
        register : function() {

            var isTureEmail = false;

            $('#register-popbox .email').on('blur',function() {

                var username = $.trim($('#register-popbox .email').val());
                if(username === ''){
                    $('#register-error-tips').html('邮箱不能为空！');
                    isTureEmail = false;
                    return;
                }
                if(!/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i.test(username)){
                    $('#register-error-tips').html('邮箱格式错误！');
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
                                    $('#register-popbox .password,#register-form-submit').hide();
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
                
                if(username === '' || password === ''){
                    $('#register-error-tips').html('邮箱或者密码不能为空！');
                    return;
                }

                if(isTureEmail){
                    $('.submit-loading').show();
                    $.ajax({
                        url : common.indexUrl + '&do=WebRegister&debug=on&msg={"email":"'+username+'","password":"'+password+'"}',
                        type : 'get',
                        dataType : 'jsonp',
                        success : function(data) {
                            
                            $('.submit-loading').hide();
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
                
                if(username === '' || password === ''){
                    $('#login-error-tips').html('邮箱或者密码不能为空！');
                    return;
                }

                
                $('.submit-loading').show();
                $.ajax({
                    url : common.indexUrl + '&do=WebLogin&debug=on&msg={"email":"'+username+'","password":"'+password+'"}',
                    type : 'get',
                    dataType : 'jsonp',
                    success : function(data) {
                        $('.submit-loading').hide();

                        if(data.error && data.error == '输入信息有误'){
                            $('#login-error-tips').html('用户名或者密码错误');
                            return;
                        }
                                                
                        $('.J_maskLayer,#login-popbox,.user-btn').hide(); 
                        $('.hd-control .user-name').html(data.user.email);
                        $('.hd-control .user-name,.hd-control .logout').show();

                        $.cookie('email',data.user.email,{
                            path : '/',
                            expires : 7
                        });
                        $.cookie('xztoken',data.xztoken,{
                            path : '/',
                            expires : 7
                        });
                        $.cookie('user_icon',data.user.user_icon,{
                            path : '/',
                            expires : 7
                        });
                    }
                });
            });

        },
        logout : function() {

            $('.logout').on('click',function() {
                $.cookie('email', null);
                $.cookie('xztoken', null);
                $.cookie('ccode', null);
                $.cookie('ucode', null);
                $.cookie('findback_email', null);
                $.cookie('user_icon', null);
                $.cookie('reset_token', null);
                location.reload();
            });

        },
        emailTimer : null,
        sendEmail : function() {

            $('#sendemail-form-submit').on('click',function() {
                clearInterval(common.emailTimer);
                var value =$.trim($('#sendemail').val());
                if(value === ''){
                    $('#sendmail-error-tips').html('邮箱不能为空！'); 
                    return;
                }
                if(!/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i.test(value)){
                   $('#sendmail-error-tips').html('邮箱格式错误！'); 
                    return;
                }

                $.cookie('findback_email',value);
                common.sendEmailAjax(value);
                
            });

        },
        sendEmailAjax : function(value) {

            $('.submit-loading').show();
            var isSend = false;
            $.ajax({
                url : common.indexUrl + '&do=WebChangePassword&debug=on&msg={"temp_ip":"http://192.168.1.118:8000/index.html","email":"'+value+'"}',
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

                    var countdown = 60;
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
                window.location = 'http://192.168.191.11:8000/index.html';

        }, 	
        init : function() {
			this.backToTop();	
			this.loginControl();
            this.registerControl();
            this.getLoginCookie();
            this.register();
            this.login();
            this.logout();
            this.sendEmail();
            this.userToIndex();
		}
	}

    if(typeof common !== "undefined")  window.common = common;

	common.init();
} ();



