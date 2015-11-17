//***************下单系统通用逻辑js文件***by bo.wang3 @ sys_dev***********************
//*** __Host 为全局变量由具体的逻辑页面设定
function getrelatedids(cid){
	$.ajax({
		url:"http://"+__Host+"/admin3/?action=Order&do=GetContractInfo&col=related_aids&id="+cid,
        type:"get",
        dataType:"json",
        success:function(data){
        	var html = data.related_aids;
        	var ctc  = html.substring(0,(html.length-1));
            $("#aids").html(ctc);
        }
    });
}

jQuery(document).ready(function(){
	
    $(".cnum").click(function(){
        location.href = "http://"+__Host+"/admin3/?action=Query&do=Statistics&ts="+$(this).attr('ts')+"&t="+$(this).attr('type');
    });
	
    if($(".processing").length > 0){
    	//自动更新查量进度
    	setInterval(function(){
    		$(".processing").each(function(i){
    			var id = this.id; 
    			$.ajax({
    				url:"http://"+__Host+"/admin3/?action=Tackle&do=GetAmountQueryState&id="+id,
    		        type:"get",
    		        dataType:"json",
    		        success:function(data){
    		            if(data.rs == 0){
    		            	if(data.status == 1){
    		            		$("#"+id).fadeOut();
    		            		$("#"+id).html(data.rate);
    		            		$("#"+id).fadeIn();
    		            	}else{
    		            		location.reload();
    		            	}
    		            }else{
    		            	alert(data.msg);
    		            	return false;
    		            }
    		        }
    		    });
    		});
    	},2000);
    }
    
    if($(".queue").length > 0){
    	//自动更新查量状态
    	setInterval(function(){
    		$(".queue").each(function(i){
    			var id = this.id; 
    			$.ajax({
    				url:"http://"+__Host+"/admin3/?action=Tackle&do=GetAmountQueryState&id="+id,
    		        type:"get",
    		        dataType:"json",
    		        success:function(data){
    		            if(data.rs == 0){
    		            	if(data.status == 1){
    		            		location.reload();
    		            	}
    		            }else{
    		            	alert(data.msg);
    		            	return false;
    		            }
    		        }
    		    });
    		});
    	},1000);
    }
    
    if($(".puv_queue").length > 0){
    	//自动更新查量状态
    	setInterval(function(){
    		$(".puv_queue").each(function(i){
    			var idf = $(this).attr("idf"); 
    			var id  = $(this).attr("id");
    			$.ajax({
    				url:"http://"+__Host+"/admin3/?action=Tackle&do=PuvResult&idf="+idf,
    		        type:"get",
    		        dataType:"json",
    		        success:function(data){
    		            if(data.rs == 0){
    		            	$("#puv_queue_"+id).html('<a target="_blank" href="http://14.17.117.101/result/'+idf+'.xls">下载统计报表</a>');
    		            }else{
    		            	$("#puv_queue_"+id).fadeOut('slow');
    		            	$("#puv_queue_"+id).fadeIn('slow');
    		            }
    		        }
    		    });
    		});
    	},1000);
    }
});