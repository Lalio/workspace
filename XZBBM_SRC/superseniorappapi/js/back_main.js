//***************通用后台模板加载js文件***by bo.wang3 @ web2***********************
//异步提交表单
function asyn_sbt(formid,url){
	
	var form_valid = true;
	
	$("input[type='text']").each(function(i){
    	if($(this).attr("value") == '' && $(this).attr("ext") != 'uncheck'){
			alert('请完整填写表单!'+$(this).attr("name"));
			this.className = 'input_red';
			form_valid = false;
			return false;
        }else{
        	this.className = 'input_green';
        }
	});
    
    if(true === form_valid){
    	$.ajax({
            url:url,
            data:$('#'+formid).serialize(),
            type:"post",
            dataType:"json",
            beforeSend:function(){
                $("#sbt_btn").html('<img src="./etc/images/loading.gif"><span class="commiting">正在处理</span>');
                $("#sbt_btn").attr('class','');
            },
            success:function(data){
                //操作码约定 0 提交正常
                if(data.rs != 0){
                	make_vc();
                    alert(data.msg); //[该错误消息由服务器返回]
                    $("#sbt_btn").html('重新提交');
                    $("#sbt_btn").attr('class','button red');
                }else{
                	rtn(data); 
                }
            }
        });
    	return true;
    }else{
    	return false;
    }

}

function asyn_sbt_uncheck(formid,url){
	
	var form_valid = true;
	
    if(true === form_valid){
    	$.ajax({
            url:url,
            data:$('#'+formid).serialize(),
            type:"post",
            dataType:"json",
            beforeSend:function(){
                $("#sbt_btn").html('<img src="./etc/images/loading.gif"><span class="commiting">正在处理</span>');
                $("#sbt_btn").attr('class','');
            },
            success:function(data){
                //操作码约定 0 提交正常
                if(data.rs != 0){
                	make_vc();
                    alert(data.msg); //[该错误消息由服务器返回]
                    $("#sbt_btn").html('重新提交');
                    $("#sbt_btn").attr('class','button red');
                }else{
                	rtn(data); 
                }
            }
        });
    	return true;
    }else{
    	return false;
    }

}

//异步触发后台功能
function asyn_trigger(url){
    $.ajax({
        url:url,
        type:"get",
        dataType:"json",
        success:function(data){
            alert(data.msg); //[该错误消息由服务器返回]
            location.reload();
        }
    });
}

//输入检查
$(document).ready(function(){
	
	$(".ctr_panel tr:even td").css("background","#F0FBFF");
	$(".ctr_panel tr:even td").attr("bg","#F0FBFF");
	$(".ctr_panel tr:odd td").attr("bg","#FFFFFF");
	
	$(".ctr_panel tr td").mouseover(function(){
        $(this).css("background","#FFFFF4");
    })
    
    $(".ctr_panel tr td").mouseout(function(){
        $(this).css("background",$(this).attr("bg"));
    })

    //页面载入
    $("body").fadeIn('fast');
    
    //加入speech标签
    //$("input").attr("x-webkit-speech",true);

    $(".delete_btn").click(function(){
        if(false == confirm('确定要删除这条记录吗（执行后无法撤销）？')){
            return false;
        }

        $.ajax({
                url:$(this).attr('url'),
                data:"id="+$(this).attr('id'),
                success:function(data){
                    //location.reload();
                }
         });
        
        $(this).parent().parent().parent().fadeOut();
    });

});

//***************通用后台模板加载js文件***by bo.wang3 @ web2***********************