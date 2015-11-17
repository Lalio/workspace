//***************预订单系统通用逻辑js文件***by bo.wang3 @ sys_dev***********************

function delay_deadline(id,n_t){
	if(confirm('确认要将该预订单的锁量日期修改为 '+n_t+' 吗？')){
	$.ajax({
		url:"http://"+__Host+"/admin3/?action=Reserve&do=DelayDeadline",
        data:{id:id,deadline:n_t},
        type:"post",
        dataType:"json",
        success:function(data){
			alert('操作成功！');
				location.reload();
		    }
        });
     }
};

function inputswitch(id,ctc){
	$('#'+id).attr('value',ctc);
}

jQuery(document).ready(function(){
	
	//新增子预订单
    $(".add_c_reserve").click(function(){
    	//从后端初始化子合同面板
    	$.ajax({
			url:"http://"+__Host+"/admin3/?action=Reserve&do=GetSubReservePanel",
            type:"get",
            dataType:"html",
            success:function(htmlcode){
            	$("#common").after(htmlcode); 
                page_style(); //重新渲染样式
            }
        });
    });
    
    //根据查量结果自动生成排期表
    $(".gc_pq").click(function(){
    	
    	var rid = $(this).attr('rid');

    	$.ajax({
			url:"http://"+__Host+"/admin3/?action=Reserve&do=AutoSchedule&func=query&id="+rid,
            type:"get",
            dataType:"json",
            success:function(data){
            	if(data.type == 'invalid'){
            		alert(data.msg);
            		return false;
            	}
            	if(confirm('确定要根据该预订单最新查量结果\n----------\n编号：#'+data.id+'\n查量时间：'+data.require_ts+'\n----------\n自动生成（或替换当前）排期表吗？')){
                	$.ajax({
            			url:"http://"+__Host+"/admin3/?action=Reserve&do=AutoSchedule&func=gc&id="+rid+"&task_id="+data.id,
                        type:"get",
                        dataType:"json",
                        success:function(data){
                        	if(data.rs_code == 1){
                            	alert('投放排期表生成成功！\n----------\n此排期表对应资源最大量仅在\n'+data.pre_deadline+'\n前有效，请尽快确认。');
                            	location.reload();
                        	}
                        }
                	});
            	}
            }
        });
    });
	
    $(".3report").click(function(){
    	if(confirm('确认要删除该监测报告吗？\n'+$(this).attr('report_name'))){
        	var tar = $(this).attr('tar');
    		$.ajax({
    			url:"http://"+__Host+"/admin3/?action=Reserve&do=Delete3Report",
                data:{tar:tar},
                type:"post",
                dataType:"json",
                success:function(data){
    						alert('删除成功！');
    						location.reload();
    				    }
            });
         }
    });
    
    //根据查量结果自动进行预锁量
    $(".pre_lock").click(function(){
    	
    	var rid = $(this).attr('rid');

    	$.ajax({
			url:"http://"+__Host+"/admin3/?action=Reserve&do=PreLock&func=query&id="+rid,
            type:"get",
            dataType:"json",
            success:function(data){
            	if(data.rs == 1){
            		alert(data.msg);
            		return false;
            	}
            	if(confirm('确定要根据该预订单的可满足量\n----------\n编号：#'+data.id+'\n查量时间：'+data.require_time+'\n----------\n进行预锁量吗？')){
                	$.ajax({
            			url:"http://"+__Host+"/admin3/?action=Reserve&do=PreLock&id="+rid,
                        type:"get",
                        dataType:"json",
                        success:function(data){
                        	if(data.rs_code == 1){
                            	alert('资源预锁量成功！\n----------\n该锁定资源仅在\n'+data.pre_deadline+'\n前有效，请尽快提交资源审核。');
                            	location.reload();
                        	}else{
                        		alert(data.msg);
                        	}
                        }
                	});
            	}
            }
        });
    });
});