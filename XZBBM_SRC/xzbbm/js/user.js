!function() {

	var user = {
		indexUrl : '//api.xzbbm.cn/?action=SuperAPI&',
        profitdata : {
            avarige_profit_array : [],
            my_profit_array : []
        },
		changeTab : function() {

            $('.item').on('click',function() {
                $('#ziliao-div').hide();
                $('#setting-div').hide();
                $('#profile-div').hide();
            });
			
            $('#ziliao').on('click',function() {
            	$('#ziliao-div').show();
            	$(this).siblings().removeClass('active');
            	$(this).addClass('active');
            });

            $('#setting').on('click',function() {
            	$('#setting-div').show();
            	$(this).siblings().removeClass('active');
            	$(this).addClass('active');
            });
            
            $('#profile').on('click',function() {
            	$('#profile-div').show();
            	$(this).siblings().removeClass('active'); 
            	$(this).addClass('active');

                //初始化图表
                var myChart = echarts.init(document.getElementById('placeholder'));
                user.chart(user.profitdata.avarige_profit_array,user.profitdata.my_profit_array);
                myChart.setOption(option); 

            });
            
            $(window).on('resize',function() {
                var myChart = echarts.init(document.getElementById('placeholder'));
                user.chart(user.profitdata.avarige_profit_array,user.profitdata.my_profit_array);
                myChart.setOption(option); 
            });

        },
        placeHolder : function() {

            //input placeholder
            $('.form-input').placeholder();

        },
        changeInfo : function() {

           $('.info-btn').on('click',function() {
                var $this = $(this);
                $this.parent().parent().next('.change').slideToggle();
                if($this.html() == '修改'){
                   $this.html('取消'); 
                }else{
                    $this.html('修改');
                }
           }); 

        },
        chart : function(average,MyProfit) {
 
            option = {
                tooltip : {
                    trigger: 'axis'
                },
                legend: {
                    data:['平均收益','我的收益']
                },
                toolbox: {
                    show : false
                },
                xAxis : [
                    {
                        type : 'category',
                        boundaryGap : false,
                        data : ['周一','周二','周三','周四','周五','周六','周日']
                    }
                ],
                yAxis : [
                    {
                        type : 'value'
                    }
                ],
                series : [
                    {
                        name:'平均收益',
                        type:'line',
                        data:average,
                        clickable:false,
                        markPoint : {
                            data : [
                                {type : 'max', name: '最高收益'},
                                {type : 'min', name: '最低收益'}
                            ]
                        },
                        markLine : {
                            data : [
                                {type : 'average', name: '平均收益'}
                            ]
                        }
                    },
                    {
                        name:'我的收益',
                        type:'line',
                        data:MyProfit,
                        markPoint : {
                            data : [
                                {type : 'max', name: '最高收益'},
                                {type : 'min', name: '最低收益'}
                            ]
                        },
                        markLine : {
                            data : [
                                {type : 'average', name : '平均收益'}
                            ]
                        }
                    }
                ]
            };

        },
        upload : function() {

            $('.upload').on('click',function() {
            	
            	$.ajax({
                    url : user.indexUrl + 'do=GetServerTs&debug=on',
                    type : 'get',
                    dataType : 'jsonp',
                    success : function(data) {
                    	var timestamp = data.now;
                        var token = hex_md5("2zwep62GnVv08Z5W9GGc" + timestamp);
                        $.cookie('timestamp',timestamp,{
                            path:'/',
                        })

                        $.cookie('token',token,{
                            path:'/',
                        })
                    }
                });
               
	        	setInterval(function(){
	        		$.ajax({
	                    url : user.indexUrl + 'do=GetServerTs&debug=on',
	                    type : 'get',
	                    dataType : 'jsonp',
	                    success : function(data) {
	                    	var timestamp = data.now;
	                        var token = hex_md5("2zwep62GnVv08Z5W9GGc" + timestamp);
	                        $.cookie('timestamp',timestamp,{
	                            path:'/',
	                        })

	                        $.cookie('token',token,{
	                            path:'/',
	                        })
	                    }
	                });
	            },2000);

                wilson.popupBox("none", {boxSelector: ".upload-popbox"});
                
                if(user.uploadUname != '' && user.uploadCname != ''){
                    //学校
                    $('.school-name').html(user.uploadUname);
                    $('.school-bwrap .close-search-name').show();
                    //学院
                    $('.college-name').html(user.uploadCname);
                    $('.college-bwrap .close-search-name').show();
                    $('.college-name').removeClass('college-name-not').addClass('college-name-allow');
                    $('.college-wrap .arrow').removeClass('arrow-not').addClass('arrow-down');
                    isCollege = true;
                    
                    //显示上传框
                    if($('#dropzone').length > 0){
                        $('#dropzone').show();
                    }else{
                        $('.uploadZone').html('<div id="dropzone" class="dropzone">');

                        var dropz = new Dropzone("#dropzone", {
                            url: "//xzbbm.cn/?do=Upload",
                            maxFiles: 10000,
                            maxFilesize: 15,
                            acceptedFiles: ".pdf,.rar,.zip,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.png,.jpg",
                            addRemoveLinks : true,
                            dictDefaultMessage : "<span style='color:#0bb5f1;'>请将文件拖拽至此</br></br></br>学长帮帮忙支持：word、ppt、excel、pdf、以及rar/zip格式资料（<15MB）的上传</span>",
                            dictRemoveFile : "删除文件",
                            dictInvalidInputType : "文件类型错误",
                            forceFallback : false,
                            paramName : 'Filedata',
                            fallback : function(){
                                $('.uploadZone').html('<p style="text-align:center;margin-top:80px;">你的浏览器不支持此上传工具，请更换<br/>Chrome 7+,Firefox 4+,IE 10+,Opera 12+,Safari 6+ 浏览器</p>'); 
                                $('.stop-upload').hide();
                            },
                            success : function(){
                            	
                            	$(".upload-popbox").hide();
                            	$('.J_maskLayer').hide();
                            	var countdown = 4;
                                common.emailTimer = setInterval(function() {
                                    if(countdown == 0) {
                                       clearInterval(common.emailTimer);
                                       location.reload();
                                    }
                                    $('.countdown').html(countdown--);
                                },1000);
                                $('#timemessage').html('全部资料上传成功，本窗口将于<span class="text-notice countdown">5</span>秒后关闭。');
                                wilson.popupBox("none", {boxSelector: ".timemessage-popbox"});
                            
                            }
                        });
                        Dropzone.autoDiscover = false;
                    }

                    $.cookie('uploadUcode',uploadUcode,{
                        path:'/'
                    })

                    $.cookie('uploadCcode',uploadUcode,{
                        path:'/'
                    })
                }

            });
            
        },
        isCollege : false,       //学院是否可以选择
        inputName : function(){

            var dataFlag;                //选中哪种选项
            var ucode = 0;
            var ccode = 0;

            //显示下拉层
            $('.search-name,.arrow').on('click',function() {

                dataFlag = $(this).parent().attr('data-flag');

                //未选择学校，禁学院
                if(dataFlag == 'college'){
                    if(!isCollege){
                        return;
                    }else{
                        $('.college-name').removeClass('college-name-not').addClass('college-name-allow');
                    }
                }

                $('.'+dataFlag+'-auto').show(); 
                $('.input-mask').show();
                $('.'+dataFlag+'-wrap .arrow').removeClass('arrow-down').addClass('arrow-up');

                //清空输入框的内容
                $('.input-text').val('').focus();

                if(dataFlag == 'school' || dataFlag == 'college'){
                    $('#school-search-list,#college-search-list').html('');
                }
                //显示学校提示
                $('.school-auto .input-tips').show();
                $('.input-none').hide();

                //选择了学校就列出学院
                if(dataFlag == 'college'){
                    if(uploadUcode != 0){
                        $('.college-input-wrap .ajax-loading').show();
                        $.ajax({
                            url : user.indexUrl + 'do=WebGetCollege&debug=on&msg={"ucode":' + uploadUcode + ',"cname":""}',
                            type : 'get',
                            dataType : 'jsonp',
                            success : function(data) {

                                var html = "",
                                    college_len = data.length;

                                $('#college-search-list').html('');
                                $('.college-input-wrap .ajax-loading').hide();
                                
                                if(college_len == 0){
                                    $('.college-auto .input-none').show();
                                    return;
                                }

                                $('.college-auto .input-none').hide();
                                for(var i = 0;i < college_len;i++){
                                    html += '<li collegeid="' + data[i].college_id + '">' + data[i].college + '</li>';
                                }
                                $('#college-search-list').html(html);

                            },
                            error : function() {}
                        });
                        $('.college-auto .input-tips').hide();
                    }else{
                        $('.college-auto .input-tips').show();
                    }
                }

            });

            //选定内容
            $('.search-con').on('click','.choose-auto ul li',function() {
                
                var thisHtml = $(this).html();
                $('.'+dataFlag+'-wrap .search-name ').html(thisHtml);
                $('.'+dataFlag+'-auto').hide(); 
                $('.input-mask').hide();
                $('.load-over').hide();
                $('.'+dataFlag+'-wrap .arrow').removeClass('arrow-up').addClass('arrow-down');
                
                var $dataFlagSelect = $('.'+dataFlag+'-bwrap .close-search-name');
                if(thisHtml === $('.'+dataFlag+'-wrap').attr('data-default')){
                    $dataFlagSelect.hide();
                }else{
                    $dataFlagSelect.show();
                }
                switch(dataFlag){
                    case "school" :
                    	$('#dropzone').hide();
                    	
                    	uploadUcode = $(this).attr("universityid");
                        
                        //初始化内容
                        $('.loading').show();
                        //选择了学校，学院解禁
                        isCollege = true;
                        $('.college-name').removeClass('college-name-not').addClass('college-name-allow');                      
                        $('.college-wrap .arrow').removeClass('arrow-not').addClass('arrow-down');
                        
                        break;
                    case "college" :
                    	uploadCcode = $(this).attr("collegeid");
                        
                        //初始化内容
                        $('.loading').show();

                        //储存ccode ucode
                        if(uploadUcode !== 0 && uploadCcode !== 0){
                            //上传被允许

                            if($('#dropzone').length > 0){
                                $('#dropzone').show();
                            }else{
                                $('.uploadZone').html('<div id="dropzone" class="dropzone">');

                                var dropz = new Dropzone("#dropzone", {
                                    url: "//xzbbm.cn/?do=Upload",
                                    maxFiles: 10000,
                                    maxFilesize: 15,
                                    acceptedFiles: ".pdf,.rar,.zip,.doc,.docx,.ppt,.pptx,.xls,.xlsx",
                                    addRemoveLinks : true,
                                    dictDefaultMessage : "<span style='color:#0bb5f1;'>请将文件拖拽至此</br></br></br>学长帮帮忙支持：word、ppt、excel、pdf、以及rar/zip格式资料（<15MB）的上传</span>",
                                    dictRemoveFile : "删除文件",
                                    dictInvalidInputType : "文件类型错误",
                                    forceFallback : false,
				                    paramName : 'Filedata',
                                    fallback : function(){
                                       $('.uploadZone').html('<p style="text-align:center;margin-top:80px;">你的浏览器不支持此上传工具，请更换<br/>Chrome 7+,Firefox 4+,IE 10+,Opera 12+,Safari 6+ 浏览器</p>'); 
                                       $('.stop-upload').hide();
                                    },
                                    success : function(){
                                    	$(".upload-popbox").hide();
                                    	$('.J_maskLayer').hide();
                                    	var countdown = 4;
                                        common.emailTimer = setInterval(function() {
                                            if(countdown == 0) {
                                               clearInterval(common.emailTimer);
                                               location.reload();
                                            }
                                            $('.countdown').html(countdown--);
                                        },1000);
                                        $('#timemessage').html('全部资料上传成功，本窗口将于<span class="text-notice countdown">5</span>秒后关闭。');
                                        wilson.popupBox("none", {boxSelector: ".timemessage-popbox"});
                                    }
                                });

                                Dropzone.autoDiscover = false;
                            }

                            $.cookie('uploadUcode',uploadUcode,{
                                path:'/'
                            })

                            $.cookie('uploadCcode',uploadCcode,{
                                path:'/'
                            })
                        }
                        break;
                }

            });

            //监听输入框
            var school_name = '';
            var college_name = '';
            //监听学校框
            $('.search-con .school-input').on('input propertychange',function() {

                $('.school-auto .input-tips').hide();
                $('.school-input-wrap .ajax-loading').show();
                school_name = $.trim($(this).val());
                
                if(school_name == ''){
                    $('.school-input-wrap .ajax-loading').hide();
                    $('.school-auto .input-none').show();
                    return;
                }
                
                $.ajax({
                    url : user.indexUrl + 'do=WebGetUniversity&debug=on&msg={"uname":"'+school_name+'"}',
                    type : 'get',
                    dataType : 'jsonp',
                    success : function(data) {
                        var html = "",
                            school_len = data.length;

                        $('#school-search-list').html('');
                        $('.school-input-wrap .ajax-loading').hide();
                        if(school_len == 0){
                            $('.school-auto .input-none').show();
                            return;
                        }
        
                        $('.school-auto .input-none').hide();
                        for(var i = 0;i < school_len;i++){
                            html += '<li universityid="' + data[i].university_id + '">' + data[i].name + '</li>';
                        }
                        $('#school-search-list').html(html);

                    },
                    error : function() {}
                })

            });
            //监听学院框
            $('.search-con .college-input').on('input propertychange',function() {

                $('.college-auto .input-tips').hide();
                $('.college-input-wrap .ajax-loading').show();

                college_name = $.trim($(this).val());
                
                if(college_name == ''){
                    $('.college-input-wrap .ajax-loading').hide();
                    return;
                }

                $.ajax({
                    url : user.indexUrl + 'do=WebGetCollege&debug=on&msg={"ucode":' + uploadUcode + ',"cname":"' + college_name + '"}',
                    type : 'get',
                    dataType : 'jsonp',
                    success : function(data) {

                        var html = "",
                            college_len = data.length;

                        $('#college-search-list').html('');
                        $('.college-input-wrap .ajax-loading').hide();
                        
                        if(college_len == 0){
                            $('.college-auto .input-none').show();
                            return;
                        }

                        $('.college-auto .input-none').hide();
                        for(var i = 0;i < college_len;i++){
                            html += '<li collegeid="' + data[i].college_id + '">' + data[i].college + '</li>';
                        }
                        $('#college-search-list').html(html);

                    },
                    error : function() {}
                })

            });
                
            //删除选定的选项
            $('.search-con').on('click','.close-search-name',function() {

                var $nextSelect = $(this).next(),
                    defaultValue = $nextSelect.attr('data-default'),
                    delectflag = $nextSelect.attr('data-flag');
	                $nextSelect.find('.search-name').html(defaultValue);
	                $(this).hide();
	                $('.load-over').hide();

                switch(delectflag){
                    case "school":
                        uploadUcode = 0;
                        uploadCcode = 0;

                        $.cookie('uploadUcode',null);
                        $.cookie('uploadCcode',null);

                        //禁止选择学院
                        isCollege = false;
                        $('.college-name').removeClass('college-name-allow').addClass('college-name-not').html('学院');
                        $('.college-wrap .arrow').removeClass('arrow-down arrow-up').addClass('arrow-not');
                        $('.college-bwrap .close-search-name,#dropzone,.school-auto').hide();

                        //初始化内容
                        $('.loading').show();
                        break;
                    case "college":
                        uploadCcode = 0;
                        $.cookie('uploadCcode',null);

                        //初始化内容
                        $('.loading').show();
                        $('#dropzone,.college-auto').hide();
                        break;
                }
                
            });

            //焦点离开，隐藏下拉层
            $('body').on('click','.J_maskLayer',function() {

                $('.choose-auto').hide();
                $('.'+dataFlag+'-wrap .arrow').removeClass('arrow-up').addClass('arrow-down');

            });

        },  
        deleteFile : function() {

            $('.all-select').on('click',function() {
                var $this = $(this);
                if($this.attr('data-flag') === "false"){
                    $('.checkbox').prop('checked',true);
                    $this.attr('data-flag',"true");
                    $this.html('取消');
                }else{
                    $('.checkbox').prop('checked',false);
                    $this.attr('data-flag',"false");
                    $this.html('全选');
                }

            });

            $('#delete-file').on('click',function() {

                var inputChecked = $('input[name="checkbox"]:checked');
                var fileIndexArr = [];
                if(inputChecked.length == 0){
                    alert('请选择删除项');
                    return;
                }
                $('input[name="checkbox"]:checked').each(function() {
                    fileIndexArr.push('"' + $(this).attr('fileIndex') + '"');
                });

                $.ajax({
                    url : '//api.xzbbm.cn/?action=SubscribeAPI&do=AddToGarbage&debug=on&msg={"xztoken":"'+$.cookie('xztoken')+'","fileIndex":['+fileIndexArr+'],"requestType":1}',
                    type : 'get',
                    dataType : 'jsonp',
                    success : function(data) {
                        $('input[name="checkbox"]:checked').each(function() {
                            $(this).parent().parent().remove();
                         });

                    }
                });
            });            

        },
        getFile : function() {

            var xztoken = $.cookie('xztoken');
            if(xztoken){
                user.scrollLoadData();
                $.ajax({
                    url : '//api.xzbbm.cn/?action=SubscribeAPI&do=PublishManager&debug=on&msg={"xztoken":"'+xztoken+'","from":0,"requestType":0,"limit":20}',
                    type : 'get',
                    dataType : 'jsonp',
                    success : function(data) {
                    
                        var html = '',
                            publishList = data.publishList,
                            len = publishList ? publishList.length : 0;
                        for(var i = 0;i < len;i++){
                        	if(publishList[i].is_converted != 0){
                        		html += '<tr>'+
                                '<td>'+
                                    '<input type="checkbox" class="checkbox" name="checkbox" fileIndex="'+publishList[i].fileIndex+'">'+
                                '</td>'+
                                '<td>'+
                                    '<span class="passage-ico '+publishList[i].fileType+'"></span><span class="passage">'+publishList[i].fileName+'<sapn style="color:green;margin:20px;">(!) 处理中</span></span>'+
                                '</td>'+
                                '<td class="col">'+publishList[i].FormatedFileTime+'</td>'+
                                '<td class="col">'+publishList[i].price+'元</td>'+
                            '</tr>';
                        	}else{
                        		html += '<tr>'+
                                '<td>'+
                                    '<input type="checkbox" class="checkbox" name="checkbox" fileIndex="'+publishList[i].fileIndex+'">'+
                                '</td>'+
                                '<td>'+
                                    '<span class="passage-ico '+publishList[i].fileType+'"></span><span class="passage"><a href="./'+publishList[i].fileKey+'" target="_blank">'+publishList[i].fileName+'</a></span>'+
                                '</td>'+
                                '<td class="col">'+publishList[i].FormatedFileTime+'</td>'+
                                '<td class="col">'+publishList[i].price+'元</td>'+
                            '</tr>';
                        	}
                        }
                        $('.user__table tbody').html(html);
                        $('.loading').hide();
                        user.scrollLoadFlag = true;
                    }

                });                
            }

        },
        transformDate : function(time) {

            var t = Number(time) * 1000;
            var date = new Date(t),
                year = date.getFullYear(),
                month = date.getMonth() + 1,
                day = date.getDay(),
                str = year + '/' + month + '/' +day;
                return  str;

        },
        infoInit : function() {

            // 设置头像
            $('.headPic').attr('src',$.cookie('user_icon'));
            $('.user-info .name').html($.cookie('user_name'));

            //设置收益
            $.ajax({
                url : '//api.xzbbm.cn/?action=PayAPI&do=MyProfit&debug=on&msg={"xztoken":"'+$.cookie('xztoken')+'"}',
                type : 'get',
                dataType : 'jsonp',
                success : function(data) {

                    $('#today_porfit').html('￥' + data.today_porfit);
                    $('#total_porfit').html('￥' + data.total_porfit);
                    $('#file_count').html(data.file_count);
                    $('#fans_count').html(data.fans_count);

                    user.profitdata.avarige_profit_array = data.avarige_profit_array;
                    user.profitdata.my_profit_array = data.my_profit_array;
                    
                }
            });

        },
        uploadReload : function() {

            $('.upload-popbox .popbox__close').on('click',function() {
                $('.J_maskLayer,.upload-popbox').hide();
                $('.loading').show();
                user.getFile();

            });

        },
        uploadUname : '',   //记录用户默认的学校
        uploadCname : '',   //记录用户默认的学院
        autoAddName : function() {

            var uploadCcode = $.cookie('uploadCcode');
            var uploadUcode = $.cookie('uploadUcode');

            if(uploadCcode!= 0 && uploadUcode != 0){
                $.ajax({
                    url : user.indexUrl + 'do=GetSchoolInfo&debug=on&msg={"ccode":"'+uploadCcode+'","ucode":"'+uploadUcode+'"}',
                    dataType : 'jsonp',
                    success : function(data) {
                        user.uploadUname = data.uname;
                        user.uploadCname = data.cname;
                    }
                });
            }

        },
        scrollLoadFlag : false,
        scrollLoadData : function() {

            var xztoken = $.cookie('xztoken');
            var scrollIndex = 1;   //记录页数
            $(window).scroll(function() {
                var documentHeight = $(document).height(),
                    scrollTop = $(window).scrollTop(),
                    windowHeight = $(window).height();
                if(documentHeight - scrollTop <= windowHeight && user.scrollLoadFlag){
                    $('.loading').show();
                    user.scrollLoadFlag = false;
                    $.ajax({
                        url : '//api.xzbbm.cn/?action=SubscribeAPI&do=PublishManager&debug=on&msg={"xztoken":"'+xztoken+'","from":'+(scrollIndex * 20 + 1)+',"requestType":0,"limit":20}',
                        type : 'get',
                        dataType : 'jsonp',
                        success : function(data) {
                        
                            var html = '',
                                publishList = data.publishList,
                                len = publishList ? publishList.length : 0;
                                if(len < 20){
                                    $('.loading span').remove();
                                    $('.loading p').html('所有数据已经加载完成');
                                    user.scrollLoadFlag = false;
                                    return;
                                }
                            for(var i = 0;i < len;i++){
                            	if(publishList[i].is_converted != 0){
                            		html += '<tr>'+
                                    '<td>'+
                                        '<input type="checkbox" class="checkbox" name="checkbox" fileIndex="'+publishList[i].fileIndex+'">'+
                                    '</td>'+
                                    '<td>'+
                                        '<span class="passage-ico '+publishList[i].fileType+'"></span><span class="passage">'+publishList[i].fileName+'<sapn style="color:green;margin:20px;">(!) 处理中</span></span>'+
                                    '</td>'+
                                    '<td class="col">'+publishList[i].FormatedFileTime+'</td>'+
                                    '<td class="col">'+publishList[i].price+'元</td>'+
                                '</tr>';
                            	}else{
                            		html += '<tr>'+
                                    '<td>'+
                                        '<input type="checkbox" class="checkbox" name="checkbox" fileIndex="'+publishList[i].fileIndex+'">'+
                                    '</td>'+
                                    '<td>'+
                                        '<span class="passage-ico '+publishList[i].fileType+'"></span><span class="passage"><a href="./'+publishList[i].fileKey+'" target="_blank">'+publishList[i].fileName+'</a></span>'+
                                    '</td>'+
                                    '<td class="col">'+publishList[i].FormatedFileTime+'</td>'+
                                    '<td class="col">'+publishList[i].price+'元</td>'+
                                '</tr>';
                            	}
                            }
                            $('.user__table tbody').append(html);
                            $('.loading').hide();
                            user.scrollLoadFlag = true;
                            scrollIndex++;
                        }

                    });
                }
            });

        },
        changeFreeOrPay : function() {

            $('.isFree').on('click',function() {
                $('.payOrFree').addClass('free').removeClass('pay');
            });
            $('.isPay').on('click',function() {
                $('.payOrFree').addClass('pay').removeClass('free');
            });

        },
        mdfUser : function() {
        	
            $('.change-submit').on('click',function() {
            	
            	var key = $('#'+$(this).attr('for')).attr('id');
            	var value = $('#'+$(this).attr('for')).val();
            	var xztoken = $.cookie('xztoken');
            	var cur = $(this);
            	
            		
        		var uname = $("#ucode").val()?$("#ucode").val():$("#ucode").attr('placeholder');
        		var cname = $("#ccode").val()?$("#ccode").val():$("#ccode").attr('placeholder');
        		
        		//判断学校、学院并由文字转换为编码
        	    $.ajax({
                    url : '//api.xzbbm.cn/?action=SuperAPI&do=GetUCcodeByName&debug=on&msg={"uname":"'+uname+'","cname":"'+cname+'"}',
                    type : 'get',
                    dataType : 'jsonp',
                    async : false,
                    success : function(ucdata) {
                    	
                    	if(key == 'ucode'){
                    		value = ucdata.ucode;
                    	}
                    	
                    	if(key == 'ccode'){
                    		value = ucdata.ccode;
                    	}
                    	
                    	if(key == 'password'){
                    		value = hex_md5(value);
                    	}
                    	
                    	if(key == 'phone'){
                    		 if(!/^1[34578][0-9]\d{8}$/.test(value)){
                                alert('您的手机号码格式不正确。'); 
                                return;
                            }
                    	}
                    	
                    	if(key == 'email'){
                            if(!/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i.test(value)){
                                alert('您的邮箱格式不正确，建议使用QQ或网易邮箱。'); 
                                return;
                            }
                    	}
                    	
                    	if(key == 'sfz'){
                            if(!/^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/.test(value)){
                                alert('您的身份证号码格式不正确。'); 
                                return;
                            }
                    	}
                    	
                    	if(key == 'ucode' && ucdata.ucode == -1){
                    		$('#commonmessage').html("请正确补全您所在学校全称（如：清华大学）</br>以便我们为您推荐更加准确的资料");
                    		wilson.popupBox("none", {boxSelector: ".commonmessage-popbox"});
                			return false;
                		}
                    	
                    	if(key == 'ccode' && ucdata.ccode == -1){
                    		$('#commonmessage').html("学长在"+uname+"下没有发现您所绑定的学院，换一个试试？");
                    		wilson.popupBox("none", {boxSelector: ".commonmessage-popbox"});
                			return false;
                		}
                    	
                    	$.ajax({
                            url : '//api.xzbbm.cn/?action=SuperAPI&do=WebUpdateUser&debug=on&msg={"xztoken":"'+xztoken+'","key":"'+key+'","value":"'+value+'"}',
                            type : 'get',
                            dataType : 'jsonp',
                            success : function(data) {
                            	if(false == data.isOK){
                            		alert(data.error);
                            	}else{
                            		cur.parent().slideToggle();
                                    var text_btn = cur.parent().parent().find('.info-btn');
                                    var text_ctc = cur.parent().parent().find('.info-bd');
                                    
                                    if(key != 'password'){
                                    	if(key == 'ucode'){
                                    		text_ctc.html(uname);
                                    		$.cookie('ucode',value);                
                                    		//修改了学校让学院清0
                                    		$.ajax({
                                                url : '//api.xzbbm.cn/?action=SuperAPI&do=WebUpdateUser&debug=on&msg={"xztoken":"'+xztoken+'","key":"ccode","value":"-1"}',
                                                type : 'get',
                                                dataType : 'jsonp',
                                                success : function(data) {
                                                	$('#ccodetext').html('学院未公开');
                                                	$('#ccode').attr('value','');
                                                	$('#ccode').attr('placeholder','学院未公开');
                                            		$.cookie('ccode','0');                
                                                }
                                            });
                                    	}else if(key == 'ccode'){
                                    		text_ctc.html(cname);
                                    		$.cookie('ccode',value);                
                                            $.cookie('uploadCcode', value);
                                            user.uploadCname = cname;
                                    	}else{
                                    		text_ctc.html(value);
                                    	}
                                    }else{
                                    	var countdown = 2;
                                    	$.cookie('xztoken', null);
                                        common.emailTimer = setInterval(function() {
                                            if(countdown == 0) {
                                               clearInterval(common.emailTimer);
                                               location.reload();
                                            }
                                            $('.countdown').html(countdown--);
                                        },1000);
                                        $('#timemessage').html('密码修改成功，请重新登录！<span class="text-notice countdown">3</span>');
                                        wilson.popupBox("none", {boxSelector: ".timemessage-popbox"});
                                		return false;
                                    }
                                    
                                    if(text_btn.html() == '修改'){
                                    	text_btn.html('取消'); 
                                    }else{
                                    	text_btn.html('修改');
                                    }
                                    
                                	if(ucdata.ucode == -1){
                                		$('#commonmessage').html("为了方便学长向您推荐更有针对性的学习资料</br>建议您设置您所在学校的全称（如：清华大学）");
                                		wilson.popupBox("none", {boxSelector: ".commonmessage-popbox"});
                            			return false;
                            		}
                                	
                                	if(ucdata.ccode == -1){
                                		$('#commonmessage').html("学长在"+uname+"下没有发现您所绑定的学院，换一个试试？");
                                		wilson.popupBox("none", {boxSelector: ".commonmessage-popbox"});
                            			return false;
                            		}
                            	}
                            }
                        });
                    	
                    }
                });
            })
        },
        init : function() {
			this.changeTab();
            this.changeInfo();
            this.upload();
            this.inputName();
            this.deleteFile();
            this.getFile();
            this.infoInit();
            this.placeHolder();
            this.uploadReload();
            this.autoAddName();
            this.changeFreeOrPay();
            this.mdfUser();
		}
	}

    if(typeof user !== "undefined")  window.user = user;

	user.init();
} ();



