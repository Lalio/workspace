//***************数据盘点系统通用逻辑js文件***by bo.wang3 @ sys_dev***********************
//*** __Host 为全局变量由具体的逻辑页面设定
//异步触发后台功能
function add_querytask(){
	
	var func = $('.op_type').val();
	
	if(!func){
		alert('请设定数据盘点类型！');
		return false;
	}
	
	if(func == 'Sz_Tf_Dj'){ //数据报表用表单提交
		$("#panel").submit();
	}else{ //表单数据用ajax提交
		$.ajax({
	        url:'./?action=SuperVision&do=Query&func='+func,
	        data:$('#panel').serialize(),
	        type:"post",
	        dataType:"json",
	        beforeSend:function(){
	            $("#sbt_btn").html('<img src="./etc/images/loading1.gif">');
	        },
	        success:function(data){
		        if(data.rs == 0){
		        	location.href = data.msg;
			    }else if(data.rs == 1){
			    	$("#sbt_btn").html('<a target="_blank" href="'+data.msg+'" class="button green medium">点击下载数据报告</a>');
			    }else if(data.rs == 2){ //ACS数据报表有四种格式
			    	$("#sbt_btn").html('<a target="_blank" href="./?action=SuperVision&do=ShowResult&func=Acs&skey='+data.msg+'&type=1&headerType=xls" class="button green medium">报表1</a><a target="_blank" href="./?action=SuperVision&do=ShowResult&func=Acs&skey='+data.msg+'&type=2&headerType=xls" class="button green medium">报表2</a><a target="_blank" href="./?action=SuperVision&do=ShowResult&func=Acs&skey='+data.msg+'&type=3&headerType=xls" class="button green medium">报表3</a><a target="_blank" href="./?action=SuperVision&do=ShowResult&func=Acs&skey='+data.msg+'&type=4&headerType=xls" class="button green medium">报表4</a><a target="_blank" href="./?action=SuperVision&do=ShowResult&func=Acs&skey='+data.msg+'&type=5&headerType=xls" class="button green medium">报表5</a>');
			    }else{
			    	alert(data.msg);
			    	$("#sbt_btn").html('<a onclick="add_querytask();" href="javascript:;" class="button blue">开始盘点</a>');
				}
	    	}
		});
	}
}

function load_input_panel(){
	
	$ckd = $('.op_type').val();
	
	//init
	$("#sbt_btn").fadeOut('fast');
	$("#sbt_btn").fadeIn('fast');
	
	$('.param').hide();
	$('#sendemail').attr("disabled", "");
	$('#outtoexcel').attr("disabled", "");
	$('#sendemail').attr("checked", "");
	$('#outtoexcel').attr("checked", "");
	
	switch($ckd){
		case 'Acs':
			$('#cid').fadeIn('fast');
			$('#sendemail').attr("disabled", "disabled");
			//$('#outtoexcel').attr('checked','checked');
		break;
		case 'Sz_Tf_Dj':
			$('#file').fadeIn('fast');
			$('#sendemail').attr("disabled", "disabled");
			$('#outtoexcel').attr("disabled", "disabled");
			//$('#outtoexcel').attr('checked','checked');
		break;
		case 'Kc':
			$('#tf_type').fadeIn('fast');
			//$('#sendemail').attr('checked','checked');
		break;
		case 'Kcyl_Zy':
			$('#tf_type').fadeIn('fast');
			$('#area').fadeIn('fast');
			//$('#sendemail').attr('checked','checked');
		break;
		case 'Wtw':
			$('#rid').fadeIn('fast');
			$('#vid').fadeIn('fast');
			$('#area').fadeIn('fast');
			$('#sendemail').attr('disabled','disabled');
		break;
		case 'Xd_Zx_Sj':
			$('#rid').fadeIn('fast');
			$('#sendemail').attr('disabled','disabled');
		break;
		case 'Kcjs':
			$('#tf_type').fadeIn('fast');
			$('#sendemail').attr('disabled','disabled');
		break;
		case 'Khtf':
			$('#vid').fadeIn('fast');
			$('#sendemail').attr('disabled','disabled');
		break;
	}
}
	
jQuery(document).ready(function(){
	
	$('.param').hide();
	$('.param').css('height','50px');
	load_input_panel();
	
	$(".op_type").change(function(){
		load_input_panel()
    });
});