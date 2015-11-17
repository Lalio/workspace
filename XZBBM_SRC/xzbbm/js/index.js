var $btnNext = $('.next-page-btn')
var $btnHide = $('.hide-page-btn')
var $xzScroll = $('.zx__scroll');
var scrollsection1 = $('.scroll__section1');
var scrollsection2 = $('.scroll__section2');
var scrollsection3 = $('.scroll__section3');
var scrollsection4 = $('.scroll__section4');

//找找看加载标识
var loadFlag = true;


!function() {

	var index = {
		indexURL : '//api.xzbbm.cn/?action=SuperAPI',
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
                    'scrollTop' : '1250px'
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
                        file_html += '<li university_id="'+university[0].university_id+'" college_id="'+college[0].college_id+'" class="more_file"><a href="javascript:;" style="outline:0;">[+] 更多</a></li>';
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

                index.from_index = 1;   //初始化记录更多的次数
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

                index.from_index = 1;   //初始化记录更多的次数
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

                index.from_index = 1;   //初始化记录更多的次数
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
                index.getFileRequest(index.universityId,college_id,12,0);

            });
            //点击更多获取资料
            $(document).on('click','.more_file',function() {

                //隐藏无热门资料提示
                $('.data-none').hide();
                var $this = $(this);

                var college_id = $this.attr('college_id');
                var university_id = $this.attr('university_id');
                $('#academy-file ul').html('<div id="loading"></div>');
                index.getFileRequest(university_id,college_id,12,index.from_index);

            });

		},
        from_index : 1,   //记录文件更多的次数
		getCollegesRequest : function(university_id) {

			var url = index.indexURL + '&do=WebSelect&debug=on&msg={"ucode":' + university_id + ',"ccode":0,"limit":12,"from":0}';

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
                            first_college_id = college[i].college_id;
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
                        if(file_len >= 11) file_len--;
                        for(var i = 0;i < file_len;i++){
                            file_html += '<li file_id="' + file[i].file_id + '" title="' + file[i].file_name + '"><a href="' + file[i].web_url + '" target="_blank">' + file[i].file_name + '</a></li>';
                        }
                        file_html += '<li university_id="'+university_id+'" college_id="'+college[0].college_id+'" class="more_file"><a href="javascript:;" style="outline:0;">[+] 更多</a></li>';
                    }
                    $('#academy-file ul').html(file_html);

			    },
                error : function(){}
            });

		},
        getFileRequest : function(university_id,college_id,limite,from_index) {

            var url = index.indexURL + '&do=WebSelect&debug=on&msg={"ucode":' + university_id + ',"ccode":' + college_id + ',"limit":'+limite+',"from":'+from_index * 11+'}';
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
                        if(file_len >= 11) file_len--;
                        for(var i = 0;i < file_len;i++){
                            file_html += '<li file_id="' + file[i].file_id + '" title="' + file[i].file_name + '"><a href="' + file[i].web_url + '" target="_blank">' + file[i].file_name + '</a></li>';
                        }
                        if(file_len >= 11){
                            index.from_index++;
                            file_html += '<li university_id="'+university_id+'" college_id="'+college_id+'" class="more_file"><a href="javascript:;" style="outline:0;">[+] 更多</a></li>';
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
        inviteRegInit : function(){
            
        	var href = location.href;
            if(href.indexOf('?welcome') != -1){
                wilson.popupBox("none", {boxSelector: ".register-popbox"});
            }
            
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
                    url : index.indexURL + '&do=WebUpdateUser&debug=on&msg={"email":"'+$.cookie('email')+'","key":"password","value":"'+hex_md5(password)+'","xztoken":"'+$.cookie('reset_token')+'"}',
                    type : 'get',
                    dataType : 'jsonp',
                    success : function(data) {
                        $('.submit-loading').hide();
                        window.location = 'http://xzbbm.cn';
                    }
                });
            });

        },
        verifyResult : function() {
        	var href = location.href;
            if(href.indexOf('?authok') != -1){
        		$('#commonmessage').html("您的邮箱已经通过验证！");
        		wilson.popupBox("none", {boxSelector: ".commonmessage-popbox"});
            }
        },
		init : function() {
			this.imageScroll();
			this.textSlider();
			this.showControl();
            this.getSchoolRequest('a');
			this.triggerSendRequest();
            this.sendEmail();
            this.changePasswordInit();
            this.changePasswordAjax();
            this.inviteRegInit();
            this.verifyResult();
		}
	}

    if(typeof index !== "undefined")  window.index = index;

	index.init();
} ();
