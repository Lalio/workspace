var $btnNext = $('.next-page-btn')
var $btnHide = $('.hide-page-btn')
var $xzScroll = $('.zx__scroll');
var $searchBd = $('.search-bd');
var searchInfo = ['四六级考试','研究生考试','公务员考试','数据库原理'];
var infoLen = searchInfo.length;
var searchIndex = 0;
var scrollsection1 = $('.scroll__section1');
var scrollsection2 = $('.scroll__section2');
var scrollsection3 = $('.scroll__section3');
var scrollsection4 = $('.scroll__section4');

//找找看加载标识
var loadFlag = true;


!function() {

	var index = {
		indexURL : 'http://112.124.50.239/?action=SuperAPI',
        universityId : 0,              //存储学校的id，方便取资料
		imageScroll : function(){

			LEGO.imageScroll("#imgscroll",{mousewheel: false});

		},
		textSlider : function(){

			$(".codetxt--scroll").textSlider({line:1,speed:500,timer:3000});

		},
		showControl : function() {

			$btnNext.on('click',function() {

                $('.zx__scroll').show();
                $('html,body').animate({
                    'scrollTop' : '1150px'
                },600,function() {
                    index.loadAction();
                    index.loadActionScroll();
                    $btnNext.hide();
                    $btnHide.show();
                });

            });
            $btnHide.on('click',function() {

                $(this).hide();
                $xzScroll.hide();
                $btnNext.show();
                $(window).off('scroll',index.scrollFun);
                scrollsection1.removeClass('active');
                scrollsection2.removeClass('active');
                scrollsection3.removeClass('active');
                scrollsection4.removeClass('active');

            });

		},
		placeHolder : function() {

			//定时换取placeholder
            var timer = setInterval(function() {
                $searchBd[0].setAttribute('value',searchInfo[searchIndex]);
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
		scrollFun : function() {

			var height = $(window).height() - 210;
			var scrollTop = $(window).scrollTop();
            var offsetTop1 = scrollsection1.offset().top;
            var offsetTop2 = scrollsection2.offset().top;
            var offsetTop3 = scrollsection3.offset().top;
            var offsetTop4 = scrollsection4.offset().top;

            if(!scrollsection1.hasClass('active') && offsetTop1 - scrollTop < height){
                scrollsection1.addClass('active');
            }
            if(!scrollsection2.hasClass('active') && offsetTop2 - scrollTop < height){
                scrollsection2.addClass('active');
            }
            if(!scrollsection3.hasClass('active') && offsetTop3 - scrollTop < height){
                scrollsection3.addClass('active');
            }
            if(!scrollsection4.hasClass('active') && offsetTop4 - scrollTop < height){
                scrollsection4.addClass('active');
            }

		},
		loadActionScroll : function() {

			if($xzScroll.css('display') == 'block'){

                $(window).on('scroll',index.scrollFun);
            }

		},
		loadAction : function() {

			if($xzScroll.css('display') == 'block'){

                var height = $(window).height() - 210;

                var scrollTop = $(window).scrollTop();
                var offsetTop1 = scrollsection1.offset().top;
                var offsetTop2 = scrollsection2.offset().top;
                var offsetTop3 = scrollsection3.offset().top;
                var offsetTop4 = scrollsection4.offset().top;

                if(!scrollsection1.hasClass('active') && offsetTop1 - scrollTop < height){
                    scrollsection1.addClass('active');
                }
                if(!scrollsection2.hasClass('active') && offsetTop2 - scrollTop < height){
                    scrollsection2.addClass('active');
                }
                if(!scrollsection3.hasClass('active') && offsetTop3 - scrollTop < height){
                    scrollsection3.addClass('active');
                }
                if(!scrollsection4.hasClass('active') && offsetTop4 - scrollTop < height){
                    scrollsection4.addClass('active');
                }
            }

		},
        getSchoolRequest : function(char_index) {

            var url = index.indexURL + '&do=WebUniversity&debug=on&msg={"char_1":"' + char_index + '"}';
            $.ajax({
                url : url,
                type : 'get',
                dataType : 'jsonp',
                success : function(data) {

                    //学校列表
                    var university = data.university;
                    var university_len = university.length;
                    var university_html = '';
                    for(var i = 0;i < university_len;i++){
                        if(i == 0){
                            university_html += '<li id="clicked" university_id="' + university[i].university_id + '" title="' + university[i].name + '">' + university[i].name + '</li>';
                            index.universityId = university[i].university_id;  //存储学校id
                        }else{
                            university_html += '<li university_id="' + university[i].university_id + '" title="' + university[i].name + '">' + university[i].name + '</li>';
                        }
                    }
                    $('#school-name ul').html(university_html);
                    $('#school-name').jScrollPane();

                    //学院列表
                    var college = data.college_list;
                    var college_len = college.length;
                    var college_html = '';
                    for(var i = 0;i < college_len;i++){
                        if(i == 0){
                          college_html += '<li id="clicked" college_id="' + college[i].college_id + '" title="' + college[i].college + '">' + college[i].college + '</li>';  
                        }else{
                            college_html += '<li college_id="' + college[i].college_id + '" title="' + college[i].college + '">' + college[i].college + '</li>';
                        }  
                    }
                    $('#school-academy ul').html(college_html);
                    $('#school-academy').jScrollPane();

                    //热门列表
                    var file = data.document_list;
                    var file_len = file.length;
                    var file_html = '';
                    if(file_len == 0){
                        $('.data-none').show();
                    }else{
                        for(var i = 0;i < file_len;i++){
                            file_html += '<li file_id="' + file[i].file_id + '" title="' + file[i].file_name + '"><a href="' + file[i].web_url + '" target="_blank">' + file[i].file_name + '</a></li>';
                        }
                    }
                    $('#academy-file ul').html(file_html);

                },
                error : function(){}
            });

        },
		triggerSendRequest : function() {

            var zimu_index = 'A';              //字幕列表索引,默认为A,防止重复点击刷新
            var college_id;                    //学院列表索引

			//点击获取学校
			$('.school-initial ul li span').on('click',function() {

                if($(this).html() == zimu_index) return;
                //隐藏无热门资料提示
                $('.data-none').hide();
                //添加load样式
                $('#school-name,#school-academy,#academy-file').find('ul').html('<div id="loading"></div>');
                //去除其他选中样式
                $('.school-initial ul li span').removeAttr('id');
                //添加当前样式
                $(this).attr('id','clicked');
				zimu_index = $(this).html();
				index.getSchoolRequest(zimu_index.toLowerCase());

			});

            //点击学校获取学院
            $(document).on('click','#school-name ul li',function() {

                var university_index = $(this).attr('university_id');  //当前id
                if(university_index == index.universityId) return;
                //隐藏无热门资料提示
                $('.data-none').hide();
                index.universityId = university_index;  //存储学校id
                var $this = $(this);
                //去除其他选中样式
                $('#school-name ul li').removeAttr('id');
                //添加当前样式
                $(this).attr('id','clicked');
                university_id = university_index;
                $('#school-academy ul,#academy-file ul').html('<div id="loading"></div>');
                index.getCollegesRequest(university_id);

            });

            //点击学院获取资料
            $(document).on('click','#school-academy ul li',function() {

                if(!loadFlag) return;
                loadFlag = false;    //请求中，防止继续发送请求

                //隐藏无热门资料提示
                $('.data-none').hide();
                var $this = $(this);
                //去除其他选中样式
                $('#school-academy ul li').removeAttr('id');
                //添加当前样式
                $(this).attr('id','clicked');
                college_id = $this.attr('college_id');
                $('#academy-file ul').html('<div id="loading"></div>');
                index.getFileRequest(index.universityId,college_id);

            });

		},
		getCollegesRequest : function(university_id) {

			var url = index.indexURL + '&dg=ml&do=WebSelect&debug=on&msg={"ucode":' + university_id + ',"ccode":0}';

            $.ajax({
                url : url,
                type : 'get',
                dataType : 'jsonp',
                success : function(data) {
    				
                    //学院列表
    				var college = data.college_list;
                    var college_len = college.length;
                    var college_html = '';
                    for(var i = 0;i < college_len;i++){
                        if(i == 0){
                          college_html += '<li id="clicked" college_id="' + college[i].college_id + '" title="' + college[i].college + '">' + college[i].college + '</li>';  
                        }else{
                            college_html += '<li college_id="' + college[i].college_id + '" title="' + college[i].college + '">' + college[i].college + '</li>';
                        }  
                    }
    				$('#school-academy ul').html(college_html);
                    $('#school-academy').jScrollPane();

                    //热门列表
                    var file = data.document_list;
                    var file_len = file.length;
                    var file_html = '';
                    if(file_len == 0){
                        $('.data-none').show();
                    }else{
                        for(var i = 0;i < file_len;i++){
                            file_html += '<li file_id="' + file[i].file_id + '" title="' + file[i].file_name + '"><a href="' + file[i].web_url + '" target="_blank">' + file[i].file_name + '</a></li>';
                        }
                    }
                    $('#academy-file ul').html(file_html);

			    },
                error : function(){}
            });

		},
        getFileRequest : function(university_id,college_id) {

            var url = index.indexURL + '&dg=ml&do=WebSelect&debug=on&msg={"ucode":' + university_id + ',"ccode":' + college_id + '}';
            $.ajax({
                url : url,
                type : 'get',
                dataType : 'jsonp',
                success : function(data) {
                    
                    //热门列表
                    var file = data.document_list;
                    var file_len = file.length;
                    var file_html = '';
                    if(file_len == 0){
                        $('.data-none').show();
                    }else{
                        for(var i = 0;i < file_len;i++){
                            file_html += '<li file_id="' + file[i].file_id + '" title="' + file[i].file_name + '"><a href="' + file[i].web_url + '" target="_blank">' + file[i].file_name + '</a></li>';
                        }
                    }
                    $('#academy-file ul').html(file_html);
                    setTimeout(function() {
                        loadFlag = true;

                    },500); 
                },
                error : function(){}
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
                        url : index.indexURL + '&do=WebTestEmail&debug=on&msg={"email":"' + username + '"}',
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
                        url : index.indexURL + '&do=WebRegister&debug=on&msg={"email":"'+username+'","password":"'+password+'"}',
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
                    $('#login-error-tips').html('用户名或者密码不能为空！');
                    return;
                }

                
                $('.submit-loading').show();
                $.ajax({
                    url : index.indexURL + '&do=WebLogin&debug=on&msg={"email":"'+username+'","password":"'+password+'"}',
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
                clearInterval(index.emailTimer);
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
                index.sendEmailAjax(value);
                
            });

        },
        sendEmailAjax : function(value) {

            $('.submit-loading').show();
            var isSend = false;
            $.ajax({
                url : index.indexURL + '&do=WebChangePassword&debug=on&msg={"temp_ip":"http://192.168.191.11:8000/index.html","email":"'+value+'"}',
                type : 'get',
                dataType : 'jsonp',
                success : function(data) {
                    $('.submit-loading').hide();
                                            
                    $('.J_maskLayer,#sendemail-popbox').hide(); 
                    wilson.popupBox("none", {boxSelector: ".sendemail-success-popbox"});
                    $('.success-email').html(value);

                    $('.countdown').off('click',function() {
                        index.sendEmailAjax(value);
                    });

                    isSend = false;
                    $('#resend').addClass('nonsend');

                    var countdown = 60;
                    index.emailTimer = setInterval(function() {
                        if(countdown == 0) {
                            clearInterval(index.emailTimer);
                            isSend = true;
                            $('#resend').removeClass('nonsend');
                            $('#resend').on('click',function() {
                                if(isSend){
                                    index.sendEmailAjax(value);
                                    isSend = false;
                                }
                            });
                        }
                        $('.countdown').html(countdown--);
                    },1000);
                }
            });

        },
        changePasswordInit : function() {

            var href = location.href;
            if(href.indexOf('reset_token') != -1){
                var str = href.split("?");
                var xztoken = str[1].split(":");
                var xztokenStr = xztoken[1].substring(3,xztoken[1].length-3);
                $.cookie('reset_token',xztokenStr);
                wilson.popupBox("none", {boxSelector: ".findback-popbox"});
            }

        },
        changePasswordAjax : function() {

            $('#findback-btn').on('click',function() {

                var password = $.trim($('#new-password').val());
                if(password == ''){
                    $('#findback-error-tips').html('密码不能为空！')
                    return;
                }
                $('.submit-loading').show();
                $.ajax({
                    url : index.indexURL + '&do=WebUpdateUser&debug=on&msg={"email":"'+$.cookie('email')+'","key":"password","value":"'+password+'","xztoken":"'+$.cookie('reset_token')+'"}',
                    type : 'get',
                    dataType : 'jsonp',
                    success : function(data) {
                        $('.submit-loading').hide();
                        window.location = 'http://192.168.191.11:8000/index.html';
                    }
                });
            });

        },
		init : function() {
			this.imageScroll();
			this.textSlider();
			this.showControl();
			this.placeHolder();
            this.getSchoolRequest('a');
			this.triggerSendRequest();
            this.loginControl();
            this.registerControl();
            this.getLoginCookie();
            this.register();
            this.login();
            this.logout();
            this.sendEmail();
            this.changePasswordInit();
            this.changePasswordAjax();
		}
	}

    if(typeof index !== "undefined")  window.index = index;

	index.init();
} ();
