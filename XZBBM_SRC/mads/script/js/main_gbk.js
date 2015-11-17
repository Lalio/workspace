//***************通用后台模板加载js文件***by bo.wang3 @ web2***********************
//异步提交表单
function asyn_sbt(formid,url){
	
	var form_valid = true;
	
    $("input[type='text']").each(function(i){
    	if($(this).attr("value") == '' && $(this).attr("ext") != 'uncheck'){
			alert('请完整填写表单!');
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

//生成校验码
function make_vc(){
	var vc_source = "http://"+__Host+"/admin3/?action=Auth&do=GVerifyCode&headerType=png&ts="+Math.random();
	$(".vc_span").html("     ");
	$(".vc_span").html("<a href='javascript:;' title='换一张'><img id='vc_img' border='0' src='"+vc_source+"' onclick='make_vc();'></a>");
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

    $(".cnum").click(function(){
        location.href = "http://"+__Host+"/admin3/?action=Query&do=CalDetail&ts="+$(this).attr('ts')+"&t="+$(this).attr('type');
    });

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

    if($(".province_selected").length){

        //加载省信息
        $.ajax({  
          type:'get',  
          url: "http://"+__Host+"/admin3/?action=Tackle&do=GetAreaInfo",  
          dataType:'json',  
          success: function(data_p){  
            for(var i=0;i<data_p.length;i++){
                $(".province_selected").append('<option value='+data_p[i]+'>'+data_p[i]+'</option>');
            } 
          }  
        });

        //当省改变的时候加载城市信息
        $(".province_selected").change(function(){
            var related_id = $(this).attr("related_id");
            $("#"+related_id).html('<option>-请选择城市-</option>');
            $.ajax({  
              type:'get',  
              url: "http://"+__Host+"/admin3/?action=Tackle&do=GetAreaInfo&prov="+encodeURI($(this).val()),
              dataType:'json',  
              success: function(data_c){  
                for(var j=0;j<data_c.length;j++){
                    $("#"+related_id).append('<option value='+data_c[j]+'>'+data_c[j]+'</option>');
                }
              }  
            });
        });

        //当城市改变的时候自动填写
        $(".city_selected").change(function(){ 
            var related_id = $(this).attr("related_id");
            if($("#"+related_id).val() == ''){
                $("#"+related_id).append($(this).val());
            }else{
                $("#"+related_id).append(","+$(this).val());
            }
        });
    }
    
    //记录状态切换
	$(".state_switch").click(function(){
		var cur = $(this);
		var id = cur.attr('id');
		var state = cur.attr('state');
		var table = cur.attr('table');
		$.ajax({
			url:'http://'+__Host+'/admin3/?action=Tackle&do=StateSwitch',
			data:{id:id,state:state,table:table},
            type:"post",
            dataType:"json",
            success:function(data){
                if(data.rs == 0){
                	cur.attr('state',data.newstate);
                	cur.fadeOut();
                	if(data.newstate == 0){
                		cur.html('<font color="green"> [有效] </font>');
                	}else{
                		cur.html('<font color="red"> [无效] </font>');
                	}
                	cur.fadeIn();
                }else{
                	alert(data.msg);
                }
            }
    	});
    });
	
	//贴片广告参数批量添加
	$(".multi_avg_cmt").click(function(){
		var aids = $(".multi_avg_aids").attr("value");
		var cmd = $(".multi_avg_cmd").attr("value");
		
		if(confirm("此操作将"+cmd+"参数添加至如下AID:\n"+aids+"\n确认要执行此操作吗？")){
			$.ajax({
				url:'http://'+__Host+'/admin3/?action=Tackle&do=MultiAddCmd',
				data:{aids:aids,cmd:cmd},
	            type:"post",
	            dataType:"json",
	            success:function(data){
	                if(data.rs == 0){
	                	alert(data.msg);
	                	$(".multi_avg_aids").attr("value","");
	                }else{
	                	alert("批量添加命令失败。");
	                }
	            }
	    	});
		}
    });
	

    //广告客户快速查找
    $(".client_quicksearch").keyup(function(){
        var key = $(this).val();
        var for_id = $(this).attr("for_id");
        $.ajax({
            url:"http://"+__Host+"/admin3/?encode=gbk&action=Tackle&do=ClientFuzzyMatch&key="+key,
            type:"get",
            dataType:"json",
            success:function(data){
            	$("#sub_client_quicksearch").remove();
            	if(eval(data).length == 1){
            		$("#"+for_id).attr("value",data[0].vid);
            	}else if(eval(data).length > 1){
            		$("#"+for_id).attr("value",data[0].vid);
            		$(".client_quicksearch").after("<select id='sub_client_quicksearch' onclick='"+for_id+".value=this.value;sub_client_quicksearch.style.display=none;'></select>");
                    for(var i in data){
                    	$("#sub_client_quicksearch").append('<option value="'+data[i].vid+'">['+data[i].vid+'] '+data[i].vname+'</option>');
                    }
            	}
            }
        });
    });

    //广告位置快速查找
    $(".adtype_quicksearch").keyup(function(){
        var key = $(this).val();
        var for_id = $(this).attr("for_id");
        $.ajax({
            url:"http://"+__Host+"/admin3/?encode=gbk&action=Tackle&do=AdTypeFuzzyMatch&key="+key,
            type:"get",
            dataType:"json",
            success:function(data){
            	$("#sub_adtype_quicksearch").remove();
            	if(eval(data).length == 1){
            		$("#"+for_id).attr("value",data[0].cid);
            	}else if(eval(data).length > 1){
            		$("#"+for_id).attr("value",data[0].cid);
            		$(".adtype_quicksearch").after("<select id='sub_adtype_quicksearch' onclick='"+for_id+".value=this.value;sub_adtype_quicksearch.style.display=none;'></select>");
                    for(var i in data){
                    	$("#sub_adtype_quicksearch").append('<option value="'+data[i].cid+'">['+data[i].cid+'] '+data[i].cname+'</option>');
                    }
            	}
            }
        });
    });

});

//***************通用后台模板加载js文件***by bo.wang3 @ web2***********************