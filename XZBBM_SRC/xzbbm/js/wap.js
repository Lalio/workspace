/*
$.extend({'share':function(_appid){
	var isCommit = confirm('使用学长客户端即可在手机查看、分享海量高校教辅资料，点击确定开始下载。');
	if(isCommit == true){
		location.href  = 'http://www.xzbbm.cn/?do=App&from=wap';
	}
	return false;
}})
*/	
$(document).ready( function() {
	$("#sendmailsbt").click(function(){
		var obj = $("#sendmailaddr");
		var from = $(this).attr("from");
		if(obj.val() == '' || obj.val() == '电子邮箱地址'){
			return false;
		}
		if(confirm("请确认邮箱地址:"+obj.val())){
			$.ajax({
				url:"/?action=Index&do=SendByMail&file_index="+obj.attr("file_index")+"&addr="+obj.val(),
		        type:"get",
		        dataType:"jsonp",
		        jsonp: 'callback', 
		        success:function(data){
		        	if(data.rcode == 1){
		        		alert(data.res);
		        	}else{
		        		alert(data.res);
		        		$('.dialog').toggle();
		        	}
		        }
		    });
		}
	});
	
	$("#cloudprint").click(function(){
    	var file_id = $(this).attr("file_id");
    	$.ajax({
    		url:"/?action=Index&do=CloudPrint&file_id="+file_id,
            type:"get",
            dataType:"json",
            success:function(data){
            	if(data.rs == 0){
            		alert(data.msg);
            	}else{
            		alert('打印接口调用失败！');
            	}
            }
        });
  });
/*	
	$(".nd_rotate").click(function(){
		//图片旋转,通过$.animate()方法
        $(this).animate({
            rotate:"+=90deg"  //为rotate属性赋值,注意：deg为角度单位
        },'slow');
	})
*/
})