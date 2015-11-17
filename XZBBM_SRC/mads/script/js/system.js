//***************系统通用逻辑js文件***by bo.wang3 @ sys_dev***********************

jQuery(document).ready(function(){
	
	var roles_code = $("#add_rule").html();
	
    $(".add_rule").click(function(){
    	$('#common').after('<tr>'+roles_code+'</tr>');
    	page_style(); //重新渲染
    });
    
    $(".gc_report").click(function(){ //生成TGID统计报表
    	var obj = $(this);
    	if($('#info').val() == ''){
    		alert('描述字段不能为空');
    		return false;
    	}
        $.ajax({  
            type:'get',  
            url: "http://"+__Host+"/admin3/?action=System&do=TgidReport",  
            dataType:'json',  
            data:{id:$('#id').val(),is_together:obj.attr('is_together'),info:$('#info').val(),tgid:$('#tgid').val(),aids:$('#aids').val(),starttime:$('#report_starttime').val(),endtime:$('#report_endtime').val()},
            success: function(data){ 
            	alert('任务已发送！');
            	location.reload();
            }  
          });
    });
});