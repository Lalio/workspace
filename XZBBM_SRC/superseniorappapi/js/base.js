var _speedMark = new Date();
var s_in_action = false;
var reg_btn = false;
//生成校验码
$.extend({'make_vc':function(){
 	var vc_source = "http://www.xzbbm.cn/?action=Auth&do=GVerifyCode&headerType=png&ts="+Math.random();
 	$(".vc_span").html("");
 	$(".vc_span").html("<a href='javascript:;' title='换一张'><img id='vc_img' src='"+vc_source+"' onclick='$.make_vc();'></a>");
}})

//取得cookie    
function getCookie(name) {    
	var nameEQ = name + "=";    
	var ca = document.cookie.split(';');    //把cookie分割成组    
	for(var i=0;i < ca.length;i++) {    
		var c = ca[i];                      //取得字符串    
		while (c.charAt(0)==' ') {          //判断一下字符串有没有前导空格    
			c = c.substring(1,c.length);      //有的话，从第二位开始取    
		}    
		if (c.indexOf(nameEQ) == 0) {       //如果含有我们要的name    
			return unescape(c.substring(nameEQ.length,c.length));    //解码并截取我们要值    
		}    
	}    
	return false;    
}    
    
//清除cookie    
function clearCookie(name) {    
	setCookie(name, "", -1);    
}    
    
//设置cookie    
function setCookie(name, value, seconds) {    
	seconds = seconds || 0;   //seconds有值就直接赋值，没有为0，这个根php不一样。    
 	var expires = "";    
 	if (seconds != 0 ) {      //设置cookie生存时间    
 		var date = new Date();    
 		date.setTime(date.getTime()+(seconds*1000));    
 		expires = "; expires="+date.toGMTString();    
 	}    
 	document.cookie = name+"="+escape(value)+expires+"; path=/";   //转码并赋值    
} 

//延时加载
$("img.lazy").scrollLoading();

$.extend({'login':function(formid){
	$.ajax({
		url:"http://www.xzbbm.cn/?action=Auth&do=Login",
        data:$('#'+formid).serialize(),
        type:"post",
        dataType:"json",
        success:function(data){
        	if(data.rs == 0){
        		location.reload();
        	}else{
        		if(data.rs == 1){
            		alert('验证码不正确');
            	}
            	if(data.rs == 2){
            		alert('请完整填写邮箱地址及密码');
            	}
            	if(data.rs == 3){
            		alert('您的用户名和密码不匹配');
            	}
            	$.make_vc();
        	}
        }
    });
}})

$.extend({'reg':function(formid){
	$.ajax({
		url:"http://www.xzbbm.cn/?action=Auth&do=Reg",
        data:$(formid).serialize(),
        type:"post",
        dataType:"json",
        success:function(data){
        	if(data.rs == 0){
        		alert('欢迎加入学长帮帮忙！');
        		$.login('regform');
        	}else{
        		if(data.rs == 1){
            		alert('验证码不正确');
            	}
            	if(data.rs == 2){
            		alert('请完整填写表单');
            	}
            	if(data.rs == 3){
            		alert('两次密码输入不一致');
            	}
            	if(data.rs == 4){
            		alert('您已经注册过学长帮帮忙，请直接登录');
            	}
            	if(data.rs == 5){
            		alert('请先阅读并同意《学长帮帮忙服务协议》');
            	}
            	$.make_vc();
        	}
        }
    });
}})

$.extend({'upload_mdf_cmt':function(file_name,file_index){
	
	var index = file_index.replace(/(^\s*)/g,"");
	
  	$.ajax({
  		url:'http://www.xzbbm.cn/?do=UploadMdfFileName',
  		data:{file_index:index,file_name:file_name,tag:$('input[name="tag"]:checked').val()},
  		type:"post",
  		dataType:"json",
        success:function(data){
          location.href = 'http://www.xzbbm.cn/view/'+index;
        }
    });
  	
  	$('#jzyl').fadeOut('fast');
  	
}})

$.extend({'change_university':function(value,tb){
	$.ajax({
		url:"http://www.xzbbm.cn/?action=Index&do=GetUniversities&province="+value,
        type:"get",
        dataType:"json",
        async:false,
        success:function(data){
        	$(".select_uni_1").empty();
            for(var o in data){ 
            	$(".select_uni_1").append('<option value="'+data[o].id+'">'+data[o].name+'</option>');
            }
            if(tb){ //同时刷新学院
            	$.change_college(data[0].id);
            }
        }
    });
}})

$.extend({'change_college':function(value){
	$.ajax({
		url:"http://www.xzbbm.cn/?action=Index&do=GetColleges&university_id="+value,
        type:"get",
        dataType:"json",
        success:function(data){
        	$(".select_col_1").empty();
            for(var o in data){ 
            	$(".select_col_1").append('<option value="'+data[o].college_id+'">'+data[o].college+'</option>');
            }
        }
    });
}})

$.extend({'getmore':function(value){
	
	$(".switch").attr('onclick',"$.getmore('"+value+"')");
	$(".switch").html('<img style="padding-top: 15px;" src="http://cdn.xzbbm.cn/web/images/loading1.gif" />');
	
	$.ajax({
		url:"http://www.xzbbm.cn/?do=Search&o=i&from=index&func=json&k="+encodeURI(value),
        type:"get",
        dataType:"json",
        success:function(data){
        	$("#news").empty();
        	$("#news").toggle();
            for(var o in data){ 
            	if(o < 15){
            		$("#news").append('<li><a target="_blank" class="kan_title" href="./view/'+data[o].file_index+'"><img onerror="this.src=\'http://cdn.xzbbm.cn/web/images/chm.png\'" src="http://cdn.xzbbm.cn/web/images/'+data[o].file_extension+'.png"> '+data[o].file_name+'</a><span><em class="eye">'+(data[o].file_views)+'人看过</em><em class="lie"> '+data[o].profile+'元收益</em></span></li>');
            		$("#news").fadeIn('fast');
            	}
            }
            $(".switch").html('换一换');
        }
    });
	
	$.ajax({
		url:"http://www.xzbbm.cn/?do=Search&o=u&from=index&func=json&k="+encodeURI(value),
        type:"get",
        dataType:"json",
        success:function(data){
        	$("#hots").empty();
        	$("#hots").toggle();
            for(var o in data){ 
            	if(o < 15){
            		$("#hots").append('<li><a target="_blank" class="kan_title" href="./view/'+data[o].file_index+'"><img onerror="this.src=\'http://cdn.xzbbm.cn/web/images/chm.png\'" src="http://cdn.xzbbm.cn/web/images/'+data[o].file_extension+'.png"> '+data[o].file_name+'</a><span><em class="eye">'+(data[o].file_views)+'人看过</em><em class="lie"> '+data[o].profile+'元收益</em></span></li>');
            		$("#hots").fadeIn('fast');
            	}
            }
        }
    });
	
	
}})

$.extend({'add_unirank':function(value){
	$.ajax({
		url:"http://www.xzbbm.cn/?action=Index&do=GetUniRank&client="+returnCitySN.cname,
        type:"get",
        dataType:"json",
        success:function(data){
        	$(".geo_pro").empty();
        	$(".geo_pro").html(data.pro);
        	$(".geo_ctc").empty();
        	$(".geo_ctc").html(data.ctc);
        }
    });
}})

$.fn.float = function(element){
    element = $(this);
    var top = element.position().top;
    pos = element.css("position");
    $(window).scroll(function(){
        var scrol = $(this).scrollTop();
        if(scrol > top){
            if(window.XMLHttpRequest){
                    element.css({"position":"fixed","top":"60px"});
            }else{
                    element.css("top",scrol);
            }
        }else{
            element.css({"position":"absolute","top":top});
        }
    })
}

$('#file_upload').uploadify({
	'swf'   		   : 'http://www.xzbbm.cn/etc/uploader/uploadify.swf',
	'uploader'         : 'http://www.xzbbm.cn/?do=Upload',
	'fileSizeLimit'    : '15MB',
	'width'            :  600,
	'buttonCursor'     : 'hand',
	'multi'            : false,
	'rollover'         : true,
	'buttonText'       : '请选择不大于15MB的 Office、PDF、RAR/ZIP、CHM、JPG 格式学术资料类文档上传',
	'fileTypeDesc' 	   : '各类文档类资料',
	'fileTypeExts'     : '*.doc; *.docx; *.xlsx; *.pptx; *.ppt; *.pdf; *.rar; *.zip; *.chm; *.jpg;',
	'method'           : 'post',
	'formData'         : {'uinfo':getCookie('userinfo'),'ucode':$('#uni').val()},
	'wmode'            : 'transparent',
	'auto'	   		   :  false,
	'preventCaching'   :  true,
	'simUploadLimit'   :  1,
    'onSelect' : function(file) {
        $("#fb_btn").fadeIn("fast");
        $("#filelist").html('');
        $("#filelist").append("</br><a href='javascript:;' class='up_file'>6</a>.名称："+file.name+"，大小："+Math.ceil(file.size/1024)+"KB，源自："+returnCitySN.cip+" ( "+returnCitySN.cname+" )");
     },
    'onUploadSuccess'  :function(file, data, response){
    	$("#loading").fadeOut("slow");
        var file_index = data;
        if(file_index.length != 33){
        	alert('Err_info:'+file_index+'\n请您刷新页面后重试，若仍无法解决，请复制此错误信息与我们联系，技术支持：miracle@xzbbm.cn');
        	return false;
        }
        var htmlcode = '<p>资料名称：<input style="font-size:17px;" type="text" id="upload_mdf" size="50" class="uploadinput" value="'+file.name.replace(file.type,'')+'" /></p>';
        htmlcode += '<p><label for="khda"><input id="khda" type="radio" name="tag" value="课后答案"/>课后答案</label>&nbsp;&nbsp;<label for="kjjg"><input id="kjjg" type="radio" name="tag" value="课件讲稿"/>课件讲稿</label>&nbsp;&nbsp;<label for="kjjg"><input id="kszt" type="radio" name="tag" value="考试真题"/>考试真题</label>&nbsp;&nbsp;<label for="kjjg"><input id="kcsj" type="radio" name="tag" value="课程设计"/>课程设计</label>&nbsp;<label for="kjjg"><input id="bylw" type="radio" name="tag" value="毕业论文"/>毕业论文</label>&nbsp;&nbsp;<label for="kjjg"><input id="kyzl" type="radio" name="tag" value="考研资料"/>考研资料</label>&nbsp;&nbsp;<label for="qtzl"><input id="qtzl" type="radio" name="tag" value="其他资料" checked/>其他资料</label>&nbsp;&nbsp;<span id="jzyl" class="button blue large" onclick="$.upload_mdf_cmt($(\'#upload_mdf\').attr(\'value\'),\''+file_index+'\');">就这样了</span></p>';
        htmlcode += '<p>“亲爱的学长，我是你刚刚上传的资料哦，能为我加多一点描述吗？这样小伙伴们就更容易找到我啦！”<br/></p>';
        $(".uploadflash").html(htmlcode);
    }
});

$(document).keydown(function(event) {
	
	if(event.keyCode == 13 && $("#light2").css('display') == 'block') { 
		if(reg_btn == true){
			$.reg('regform');
			return false;
		}else{
			$.login('loginform');
			return false;
		}
	} 
});

$(document).ready( function() {
	
	$('img').lazyload({ 
		//图片延时加载
	    effect:'fadeIn' 
	});
	
	$(".nd_rotate").click(function(){
		//图片旋转,通过$.animate()方法
        $(this).animate({
            rotate:"+=90deg"  //为rotate属性赋值,注意：deg为角度单位
        },'slow');
	})
	
	if($(".slides_container").length){
		$.add_unirank();
	}
	
	if($("#FlexPaperViewer").length){ //资料展示flash获得焦点
		$("#FlexPaperViewer").focus();
	}
	
	$(".ljxz a").click(function(){
		var cur_class = $(this).parent().attr('onmouseover');
		cur_class = cur_class.substring((cur_class.indexOf("'")+8), 31);
		var color = $("."+cur_class).css('background-color');
		
		//获取原始状态
		var dis1 = $('.u_more').css('display');
		var dis2 = $('#up').css('display');
		var dis3 = $('#down').css('display');
		
		$(".ljxz a").removeAttr("style");
		$(this).css('background',color);
		$(this).css('font-weight','bold ');
		$(this).css('color','#FFFFFF');

		$('.u_more').css('display',dis1);
		$('#up').css('display',dis2);
		$('#down').css('display',dis3);
	});
	
	if($(".slides_container").length){
		$.add_unirank();
	}
	
	$(".reg").click(function(){
		$.reg('#regform');
    });
	
	setInterval(function(){
		if(false === s_in_action){
			$.ajax({
				url:"http://www.xzbbm.cn/?action=Index&do=GetHotWords",
		        type:"get",
		        dataType:"json",
		        success:function(data){
			        	$("#s_container").attr("value",data.rs);
		        }
		    });
		}
	},30000);
	
    $(".fabu_action").click(function(){
    	$('#light1').fadeIn('slow');
    	$('#fade1').fadeIn('slow');
    });
	
	$("#fb_btn").click(function(){
    	$("#fb_btn").toggle();
    	$('#loading').toggle();
    	if($('.up_file').length == 1){
    		$('#file_upload').uploadify('settings','formData',{'timestamp':$('#ts').attr('value'),'token':$('#token').attr('value'),'ucode':$('#uni option:selected').val(),'tag':$('#col option:selected').text()});
        	$('#file_upload').uploadify('upload','*');
    	}
    	if($('.up_file').length > 1){
    		alert('本平台暂不支持资料批量上传，请打包或分多次上传！');
    	}
    	if($('.up_file').length == 0){
    		alert('请至少选择一份资料上传！');
    	}
    });
	
    $(".select_pro_1").change(function(){
    	$.change_university($(this).attr('value'),true);
    });
    
    $(".select_uni_1").change(function(){
    	$.change_college($(this).attr('value'));
    });
    
	$(".school_tc_show").click(function(){
		$('#light').fadeIn('slow');
		$('#fade').fadeIn('slow');
	})
    
    $(".sle_pro").click(function(){
    	$(this).addClass("on");
    	$(this).siblings().removeClass("on");
    	var value = $(this).attr("id");
    	$.ajax({
    		url:"http://www.xzbbm.cn/?action=Index&do=GetUniversities&province="+value,
            type:"get",
            dataType:"json",
            success:function(data){
            	$(".qh_overflow").empty();
            	$(".qh_overflow").toggle();
                for(var o in data){ 
                	$(".qh_overflow").append('<a href="http://www.xzbbm.cn/?do=SwitchCollege&ucode='+data[o].id+'">'+data[o].name+'</a>');
                }
                $(".qh_overflow").fadeIn('slow');
            }
        });
    });
    
    $(".switch").click(function(){
    	
    });
	
    $("#fileField").change(function(){
        $("#textfield").val($("#fileField").val());
    });
    
    $(".topLogin").click(function(){
        $("#login").fadeIn("fast");
    });
    $("#login .close").click(function(){
        $("#login").fadeOut("fast");
    });
    
    $(".topUpload").click(function(){
        $("#upload").fadeIn("fast");
    });
    $("#reg .close").click(function(){
        $("#upload").fadeOut("fast");
    });
    
    $(".mail").click(function(){
        $("#sendfile").fadeIn("fast");
    });
    $("#sendfile .close").click(function(){
        $("#sendfile").fadeOut("fast");
    });
    
    $(".top b").click(function(){
        $("#school").fadeIn("fast");
    });
    
    $(".select_sch").click(function(){
        $("#school").fadeIn("fast");
    });
    
    $("#school .close1").click(function(){
        $("#school").fadeOut("fast");
    });
    
    $("#upload .close1").click(function(){
        $("#upload").fadeOut("fast");
    });
    
    $(".select_pro").click(function(){
    	$.ajax({
    		url:"http://www.xzbbm.cn/?action=Index&do=GetColleges&province="+encodeURIComponent($(this).text()),
            type:"get",
            dataType:"json",
            success:function(data){
            	$("#school .college").empty();
            	$("#school .college").toggle();
                for(var o in data){ 
                	$("#school .college").append('<li><a onclick="location.href=\'http://www.xzbbm.cn/?do=SelectCollege&ucode='+data[o].id+'\'" href="javascript:;" title="'+data[o].name+'">'+data[o].name+'</a></li>');
                	$("#school .college").fadeIn('fast');
                }
            }
        });
    });
    
    $(".ding").click(function(){
    	$.ajax({
    		url:"http://www.xzbbm.cn/?action=Index&do=vote&type=ding&id="+$(this).attr("id"),
            type:"get",
            dataType:"json",
            success:function(data){
            	if(data.rs == 0){
            		$(".ding").html(data.msg);
                	$(".ding").className = '';
            	}
            }
        });
    });
    
    $(".cai").click(function(){
    	$.ajax({
    		url:"http://www.xzbbm.cn/?action=Index&do=vote&type=cai&id="+$(this).attr("id"),
            type:"get",
            dataType:"json",
            success:function(data){
            	if(data.rs == 0){
            		$(".cai").html(data.msg);
                	$(".cai").className = '';
            	}
            }
        });
    });
    
    $("#sendmailsbt").click(function(){
    	var obj = $("#sendmailaddr");
    	var from = $(this).attr("from");
    	$.ajax({
    		url:"http://www.xzbbm.cn/?action=Index&do=SendByMail&file_index="+obj.attr("file_index")+"&addr="+obj.val(),
            type:"get",
	        dataType:"jsonp",
	        jsonp: 'callback',
            success:function(data){
            	if(data.rcode == 1){
            		alert(data.res);
            		obj.attr("value","");
            	}else{
            		$("#light3").toggle();
            		if(data.rcode == 3){
            			$(".send_rs").css('background-color','rgba(233, 87, 87, 0.85)');
            		}
        			$(".send_rs").html(data.res);
        			$(".send_rs").fadeIn('slow');
        			$("#fade1").fadeOut('slow');
            		setInterval(function(){
            			$(".send_rs").fadeOut('slow');
            			$(".youxiang_btn").attr('onclick','alert("该资料已经寄出，请于5-10分钟后在邮箱中查收~");');
            		},5000);
            	}
            }
        });
    });
    
    $(".loginSub").click(function(){
    	$.ajax({
    		url:'http://www.xzbbm.cn/?do=Login',
    		type:"post",
    	    data: "username="+$("#un").attr("value")+"&password="+$("#pw").attr("value"),
            dataType:"json",
            success:function(data){
            	if(data.rcode == 1){
            		alert(data.msg);
            	}else{
            		location.reload();
            	}
            }
        });
    });
    
	$('#onlineview').click(function(){
		$.fancybox({
			width				: 910,
			height				: 540,
			overlayColor 		: "#000000", //要指定黑色背景，
			overlayOpacity 	    : 0.8, //
			transitionIn		: 'true',
			transitionOut		: 'true',
			type				: 'iframe',
			autoScale			: true,
			centerOnScroll      : true,
			hideOnOverlayClick  : false,
			padding			: 0,
			href: 'http://www.xzbbm.cn/?do=OnlineView&idf='+$(this).attr('idf'),
		});
	});
	
	$('.onlineview_img').fancybox({
		overlayColor 		: "#000000", //要指定黑色背景，
		overlayOpacity 	    : 0.8,
		transitionIn		: 'true',
		transitionOut		: 'true',
		type				: 'image',
		showNavArrows       : true,
		autoScale			: true,
		centerOnScroll      : true,
		hideOnOverlayClick  : false,
	});
	
	$('#index_banner').click(function(){
		$.fancybox({
			width				: 874,
			height				: 540,
			overlayColor		: "#000000", //要指定黑色背景，
			overlayOpacity 	    : 0.8, //
			transitionIn		: 'true',
			transitionOut		: 'true',
			type				: 'iframe',
			autoScale			: true,
			centerOnScroll      : true,
			hideOnOverlayClick  : false,
			//titleShow           : true,
			//title               : '为什么学长帮帮忙',
			padding			: 0,
			href: 'http://www.xzbbm.cn/?do=ShowMovie',
		});
	});
	
	$('.show_big_qrcode').click(function(){
		var src = $(this).attr("src");
		var file_name = $(this).attr("file_name");
		$.fancybox({
			width				: 512,
			height				: 512,
			overlayColor		: "#000000", //要指定黑色背景，
			overlayOpacity 	    : 0.85, //
			transitionIn		: 'true',
			transitionOut		: 'true',
			type				: 'iframe',
			autoScale			: true,
			centerOnScroll      : true,
			hideOnOverlayClick  : false,
			titleShow           : true,
			title               : '在手机端浏览、下载、分享《'+file_name+'》，请使用<a href="http://xzbbm.cn/app" target="_blank">学长帮帮忙客户端</a>扫描此二维码。',
			padding			    : 0,
			href: src,
		});
	});
	
    if($(".related").length){
	    //setInterval(function(){
	    	$.ajax({
	    		url:"http://www.xzbbm.cn/?action=Index&do=GetRelatedFile",
	            type:"get",
	            dataType:"json",
	            success:function(data){
	                $("#relate_"+data.rand).fadeOut("slow");
	                $("#relate_"+data.rand).html('<span><a target="file" href="./'+data.file_index+'" class="kan_title" title="'+data.file_name+'">'+data.file_name+'</a></span><span><em class="eye">'+data.file_views+'</em><em class="lie">'+data.file_downs+'</em></span>');
	                $("#relate_"+data.rand).fadeIn("slow");
	            }
	        });
	    //},3000);
    }
    
	$("#reg_email").blur(function(){
		var email = $(this).attr("value");
    	$.ajax({
    		url:"http://www.xzbbm.cn/?action=Auth&do=CheckUser&email="+email,
            type:"GET",
            dataType:"json",
            success:function(data){
            	if(data.rs == 2){
            		$("#emailcheck").html('<span style="font-size:13px;color:#FF3300;">E-mail地址格式不正确！</span>');
            	}
            	if(data.rs == 1){
            		$("#emailcheck").html('<span style="font-size:13px;color:#FF3300;">该E-mail地址已经被注册！</span>');
            	}
            	if(data.rs == 0){
            		$("#emailcheck").html('<img src="http://cdn.xzbbm.cn/web/images/dui.jpg" width="18" height="18">');
            	}
            }
        });
    });
	
	$("#pwd").blur(function(){
		if($(this).attr("value").length > 0){
	    	if($(this).attr("value").length < 6){
	    		$("#pwdrs").html('<span style="font-size:13px;color:#FF3300;">建议密码长度6位以上</span>');
	    	}else{
	    		$("#pwdrs").html('<img src="http://cdn.xzbbm.cn/web/images/dui.jpg" width="18" height="18">');
	    	}
		}
    });
	
	$("#confirm_pwd").blur(function(){
		if($(this).attr("value").length > 0){
	    	if($("#pwd").attr("value") != $("#confirm_pwd").attr("value")){
	    		$("#pwdcheckrs").html('<span style="font-size:13px;color:#FF3300;">两次密码输入不一致</span>');
	    	}else{
	    		$("#pwdcheckrs").html('<img src="http://cdn.xzbbm.cn/web/images/dui.jpg" width="18" height="18">');
	    	}
		}
    });
})