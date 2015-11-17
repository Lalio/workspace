//***************下单系统通用逻辑js文件***by bo.wang3 @ sys_dev***********************
//*** __Host 为全局变量由具体的逻辑页面设定

jQuery(document).ready(function(){
	
	//自动填写代码
	$("#gc_js").click(function(){
		$.ajax({
			url:"http://"+__Host+"/admin3/?action=AdvCode&do=Advs",
            data:$('form').serialize(),
            type:"post",
            dataType:"json",
            success:function(data_js){
                if(data_js.state == 1){
					alert('该类型广告位暂不支持代码自动生成！');
				}else{
					$('#switch_panel').html('');
					$('#switch_panel').append('<select id="switch_panel_s" onchange="$(\'#google\').attr(\'value\',$(this).val());$(\'#ad_js_panel\').toggle();$(\'#ad_js_input\').toggle();"><option>-请选择类型-</option></select>');
		      		for(var x in data_js.rs){
		      			$('#switch_panel_s').append('<option value="'+data_js.rs[x]+'">'+x+'</option>');
					}
				}
            }
        });
	});

	//自动生成贴片广告代码
	$("#gc_cmd").click(function(){
		$.ajax({
			url:"http://"+__Host+"/admin3/?action=Tackle&do=GetVadAvg",
            data:$('form').serialize(),
            type:"post",
            dataType:"html",
            success:function(data){
                $("#link").attr('value',data);
            }
        });
	});

	//广告素材有效性检查
	$(".validcheck").change(function(){
		var url = $(this).attr("value");
		var pat = new RegExp("&");
		if(pat.test(url)){
			alert('URL地址中不得包含下列特殊字符:&');
			return false;
		}else{
			$.ajax({  
		      type:'post',  
		      url: "http://"+__Host+"/admin3/?action=Tackle&do=MaterialValidity",
		      data: {url:url},
		      dataType:'json',  
		      success: function(data){  
		        if(data.rs == 1){
		            alert(data.msg);
		        }
		      }  
    		});
		}
	});

	//页面开始加载合同信息
	$.ajax({  
      type:'post',  
      url: "http://"+__Host+"/admin3/?action=Tackle&do=GetContractInfo",
      dataType:'json',
      success: function(data_p){  
        for(var i=0;i<data_p.length;i++){
			$(".contract_selected").append('<option value='+data_p[i].id+'>'+data_p[i].customer+' - '+data_p[i].contract_id+'</option>');
		}
      }  
    });

	//选择合同加载通用信息以及城市监测代码 
	$(".contract_selected").change(function(){
		$.ajax({  
	      type:'post',  
	      url:"http://"+__Host+"/admin3/?action=Tackle&do=GetContractCode&id="+$(this).val(),
	      dataType:'json',
	      success: function(data_p){  

			$("#cid").attr('value',data_p.ad_type);
			$("#vid").attr('value',data_p.customer_id);
			$("#title").attr('value',data_p.title);
			//$("#title").attr('readonly',true);
			$("#description").attr('value',data_p.description);
			$("#cpm").attr('value',data_p.day_cpm);

			$("#starttime").attr('value',data_p.starttime);
			$("#endtime").attr('value',data_p.endtime);
			
			$(".contract_city").html("<option>-- 请选择城市 --</option>");
			for(var i=0;i<data_p.citycode.length;i++){
				$(".contract_city").append('<option city='+data_p.citycode[i].city+' viewurl='+data_p.citycode[i].viewurl+' clickurl='+data_p.citycode[i].clickurl+' flvurl='+data_p.citycode[i].flvurl+' schedule='+data_p.citycode[i].schedule+'>'+data_p.citycode[i].city+'</option>');
			}
	      }  
    	});
	});

	//自动填写曝光及点击监测代码
	$(".contract_city").change(function(){
		var obj = $(this).find("option:selected");
		$("#tp_viewurl").attr('value',obj.attr("viewurl")=='#'||obj.attr("viewurl")=='null'?'':obj.attr("viewurl"));
		$("#tp_click").attr('value',obj.attr("clickurl")=='#'||obj.attr("clickurl")=='null'?'':obj.attr("clickurl"));
		$("#flv_url").attr('value',obj.attr("flvurl")=='#'||obj.attr("flvurl")=='null'?'':obj.attr("flvurl"));
		$("#city").html('');
		$("#city").append(obj.attr("city"));
		//$("#city").attr("readonly","true");
		$("#schedule").attr("value",obj.attr("schedule"));
		$("#schedual_preview").attr("src","http://"+__Host+"/admin3/?action=Order&do=Schedule&msg="+obj.attr("schedule"));
	});

	//加载模板信息
	$.ajax({  
      type:'post',  
      url: "http://"+__Host+"/admin3/?action=Tackle&do=GetTemplateInfo",
      dataType:'json',
      success: function(data_p){  
        for(var i=0;i<data_p.length;i++){
			jQuery(".ad_templates_selected").append('<option value='+data_p[i].t_url+'>'+data_p[i].t_name+'</option>');
		}
      }  
    });
	
	//批量暂停
	$(".multistop").click(function(){
		var check = $("input:checked");  //得到所有被选中的checkbox
		var aids = '';
		check.each(function(i){          //循环拼装被选中项的值
    		aids += $(this).val()+',';   //获得目标aid串
   		});
		if(confirm('确定要暂停订单号为'+aids+'的订单吗？')){
			$.ajax({
				url:"http://"+__Host+"/admin3/?action=Tackle&do=MultiStopRestart&switch=stop&aids="+aids,
	            type:"get",
	            dataType:"json",
	            success:function(data){
		            if(data.rs == 0){
		               alert('批量操作成功！');
		               location.reload();
		            }
	            }
	        });
		}
	});

	//批量恢复
	$(".multirestart").click(function(){
		var check = $("input:checked");  //得到所有被选中的checkbox
		var aids = '';
		check.each(function(i){          //循环拼装被选中项的值
    		aids += $(this).val()+',';   //获得目标aid串
   		});
		if(confirm('确定要恢复订单号为'+aids+'的订单吗？')){
			$.ajax({
				url:"http://"+__Host+"/admin3/?action=Tackle&do=MultiStopRestart&switch=restart&aids="+aids,
	            type:"get",
	            dataType:"json",
	            success:function(data){
		            if(data.rs == 0){
		               alert('批量操作成功！');
		               location.reload();
		            }
	            }
	        });
		}
	});
	
	//暂停广告
	$(".stop_ad").click(function(){
		var aid = $(this).attr('aid');
		if(aid != '' && confirm('确定要暂停订单号为'+aid+'的订单吗？')){
			$.ajax({
				url:"http://"+__Host+"/admin3/?action=Tackle&do=MultiStopRestart&switch=stop&aids="+aid,
	            type:"get",
	            dataType:"json",
	            success:function(data){
		            if(data.rs == 0){
		               alert('批量操作成功！');
		               location.reload();
		            }
	            }
	        });
		}
	});

	//批量广告
	$(".restart_ad").click(function(){
		var aid = $(this).attr('aid');
		if(aid != '' && confirm('确定要恢复订单号为'+aid+'的订单吗？')){
			$.ajax({
				url:"http://"+__Host+"/admin3/?action=Tackle&do=MultiStopRestart&switch=restart&aids="+aid,
	            type:"get",
	            dataType:"json",
	            success:function(data){
		            if(data.rs == 0){
		               alert('批量操作成功！');
		               location.reload();
		            }
	            }
	        });
		}
	});
	
	//根据广告位置加载对应广告类型
	$("#type").mouseover(function(){
		$.ajax({
			url:"http://"+__Host+"/admin3/?action=Tackle&do=Cid2Type&cid="+$("#cid").attr('value'),
            type:"get",
            dataType:"json",
            success:function(data){
            	$("#type").empty();
                for(var i in data){
                	$("#type").append('<option value='+data[i].id+'>'+data[i].name+'</option>');
                }
            }
        });
	});
	
	$('.click_detail').click(function(){
		var aid = $(this).attr("aid");
		$.fancybox({
			width				: 940,
			height				: 420,
			overlayColor 		: "#000000", //要指定黑色背景，
			overlayOpacity 	    : 0.7, 
			transitionIn		: 'true',
			transitionOut		: 'true',
			type				: 'iframe',
			centerOnScroll      : true,
			padding			    : 0,
			href             : './?action=Query&do=Statistics&ShowType=VcHistory&type=click&aid='+aid,
		});
	});
	
	$('.view_detail').click(function(){
		var aid = $(this).attr("aid");
		$.fancybox({
			width				: 940,
			height				: 420,
			overlayColor 		: "#000000", //要指定黑色背景，
			overlayOpacity 	    : 0.7, 
			transitionIn		: 'true',
			transitionOut		: 'true',
			type				: 'iframe',
			centerOnScroll      : true,
			padding			    : 0,
			href             : './?action=Query&do=Statistics&ShowType=VcHistory&type=view&aid='+aid,
		});
	});
	
	$('.ad_preview').click(function(){
		var aid = $(this).attr("aid");
		$.fancybox({
			width				: 1000,
			height				: 550,
			overlayColor 		: "#000000", //要指定黑色背景，
			overlayOpacity 	    : 0.7, 
			transitionIn		: 'true',
			transitionOut		: 'true',
			type				: 'iframe',
			centerOnScroll      : true,
			autoScale           : true,
			padding			    : 0,
			href                : './?action=Order&do=AdPreview&aid='+aid,
		});
	});
});