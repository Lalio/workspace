!function() {

	var user = {
		indexUrl : 'http://112.124.50.239/?action=SuperAPI&',
        profitdata : {
            avarige_profit_array : [],
            my_profit_array : []
        },
		changeTab : function() {

            $('#ziliao').on('click',function() {
                var $this = $(this);
                if($this.hasClass('active')) return;
                $(this).addClass('active').siblings().removeClass('active');
                $('#ziliao-div').show();
                $('#setting-div').hide();
            });

            $('#setting').on('click',function() {
                var $this = $(this);
                if($this.hasClass('active')) return;
                $(this).addClass('active').siblings().removeClass('active');
                $('#ziliao-div').hide();
                $('#setting-div').show();

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
                color : ['#2ec7c9','#b6a2de'],
                tooltip : {
                    trigger: 'axis'
                },
                legend: {
                    data:['平均收益','我的收益']
                },
                toolbox: {
                    show : true,
                    feature : {
                        mark : {show: true},
                        dataView : {show: true, readOnly: false},
                        magicType : {show: true, type: ['line', 'bar']},
                        restore : {show: true},
                        saveAsImage : {show: true}
                    }
                },
                calculable : true,
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
                        markPoint : {
                            data : [
                                {type : 'max', name: '最大值'},
                                {type : 'min', name: '最小值'}
                            ]
                        },
                        markLine : {
                            data : [
                                {type : 'average', name: '平均值'}
                            ]
                        }
                    },
                    {
                        name:'我的收益',
                        type:'line',
                        data:MyProfit,
                        markPoint : {
                            data : [
                                {type : 'max', name: '最大值'},
                                {type : 'min', name: '最小值'}
                            ]
                        },
                        markLine : {
                            data : [
                                {type : 'average', name : '平均值'}
                            ]
                        }
                    }
                ]
            };

        },
        upload : function() {

            var timestamp = Date.parse(new Date());
            var token = hex_md5("2zwep62GnVv08Z5W9GGc" + timestamp);     
            $.cookie('ts',timestamp,{
                path:'/'
            })
            $.cookie('token',token,{
                path:'/'
            })

            setInterval(function(){
                timestamp = Date.parse(new Date());
                token = hex_md5("2zwep62GnVv08Z5W9GGc" + timestamp);
                $.cookie('ts',timestamp,{
                    path:'/'
                })

                $.cookie('token',token,{
                    path:'/'
                })
            },5000);

            $('.upload').on('click',function() {

                wilson.popupBox("none", {boxSelector: ".upload-popbox"});

            });
            
        },
        inputName : function(){

            var dataFlag;                //选中哪种选项
            var isCollege = false;       //学院是否可以选择
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
                    if(ucode != 0){
                        $('.college-input-wrap .ajax-loading').show();
                        $.ajax({
                            url : user.indexUrl + 'do=WebGetCollege&debug=on&msg={"ucode":' + ucode + ',"cname":""}',
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
                        ucode = $(this).attr("universityid");
                        
                        //初始化内容
                        $('.loading').show();
                        //选择了学校，学院解禁
                        isCollege = true;
                        $('.college-name').removeClass('college-name-not').addClass('college-name-allow');                      
                        $('.college-wrap .arrow').removeClass('arrow-not').addClass('arrow-down');
                        
                        break;
                    case "college" :
                        ccode = $(this).attr("collegeid");
                        
                        //初始化内容
                        $('.loading').show();

                        //储存ccode ucode
                        if(ucode !== 0 && ccode !== 0){
                            //上传被允许
                            // $('.stop-upload').hide();

                            if($('#dropzone').length > 0){
                                $('#dropzone').show();
                            }else{
                                $('.uploadZone').html('<div id="dropzone" class="dropzone">');

                                var dropz = new Dropzone("#dropzone", {
                                    url: "http://www.xzbbm.cn/?do=UploadTest",
                                    maxFiles: 10,
                                    maxFilesize: 1,
                                    acceptedFiles: ".png,.js",
                                    addRemoveLinks : true,
                                    dictDefaultMessage : "请将文件拖拽至此",
                                    dictRemoveFile : "删除文件",
                                    dictInvalidInputType : "文件类型错误",
                                    forceFallback : false,
                                    fallback : function(){
                                       $('.uploadZone').html('<p style="text-align:center;margin-top:80px;">你的浏览器不支持此上传工具，请更换<br/>Chrome 7+,Firefox 4+,IE 10+,Opera 12+,Safari 6+ 浏览器</p>'); 
                                        $('.stop-upload').hide();
                                    }
                                });

                                Dropzone.autoDiscover = false;
                            }

                            $.cookie('ucode',ucode,{
                                path:'/'
                            })

                            $.cookie('ccode',ccode,{
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
                    url : user.indexUrl + 'do=WebGetCollege&debug=on&msg={"ucode":' + ucode + ',"cname":"' + college_name + '"}',
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
                        ucode = 0;
                        ccode = 0;

                        $.cookie('ucode',null);
                        $.cookie('ccode',null);

                        //禁止选择学院
                        isCollege = false;
                        $('.college-name').removeClass('college-name-allow').addClass('college-name-not').html('学院');
                        $('.college-wrap .arrow').removeClass('arrow-down arrow-up').addClass('arrow-not');
                        $('.college-bwrap .close-search-name,#dropzone,.school-auto').hide();

                        //初始化内容
                        $('.loading').show();
                        break;
                    case "college":
                        ccode = 0;
                        $.cookie('ccode',null);

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
                    url : 'http://112.124.50.239/?action=SubscribeAPI&do=AddToGarbage&debug=on&msg={"xztoken":"'+$.cookie('xztoken')+'","fileIndex":['+fileIndexArr+'],"requestType":1}',
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
                $.ajax({
                    url : 'http://112.124.50.239/?action=SubscribeAPI&do=PublishManager&debug=on&msg={"xztoken":"'+xztoken+'","from":0,"requestType":0,"limit":50}',
                    type : 'get',
                    dataType : 'jsonp',
                    success : function(data) {
                    
                        var html = '',
                            publishList = data.publishList,
                            len = publishList ? publishList.length : 0;
                        for(var i = 0;i < len;i++){
                            html += '<tr>'+
                                        '<td>'+
                                            '<input type="checkbox" class="checkbox" name="checkbox" fileIndex="'+publishList[i].fileIndex+'">'+
                                        '</td>'+
                                        '<td>'+
                                            '<span class="passage">'+publishList[i].fileName+'</span>'+
                                        '</td>'+
                                        '<td class="col">'+user.transformDate(publishList[i].fileTime)+'</td>'+
                                        '<td class="col">'+publishList[i].price+'元</td>'+
                                    '</tr>';
                        }
                        $('.user__table tbody').html(html);
                        $('.loading').hide();
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
            $('.user-info .name').html($.cookie('email'));

            //设置收益
            $.ajax({
                url : 'http://112.124.50.239/?action=PayAPI&do=MyProfit&debug=on&msg={"xztoken":"'+$.cookie('xztoken')+'"}',
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
        init : function() {
			this.changeTab();
            this.changeInfo();
            this.upload();
            this.inputName();
            this.deleteFile();
            this.getFile();
            this.infoInit();
		}
	}

    if(typeof user !== "undefined")  window.user = user;

	user.init();
} ();



