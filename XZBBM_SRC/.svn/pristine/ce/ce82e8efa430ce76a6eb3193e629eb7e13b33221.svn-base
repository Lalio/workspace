//***************下单模块通用逻辑js文件***by bo.wang3 @ Sys Dev 56.com***********************
//*** __Host 为全局变量由具体的逻辑页面设定

$.extend({'refresh_input2code_panel':function(){
	
	var cid = parseInt($('#cid').val());
	
	//全部隐藏
	var cfg1 = new Array('p_img','p_img_href','p_flash','p_sub_title_img','p_sub_title','p_sub_title_link','p_title','p_title_href','p_swf','p_views','p_comments','p_totaltime','p_type');
	//显示：图片地址、图片链接、文字标题、广告类型
	var cfg2 = new Array('p_flash','p_sub_title_img','p_sub_title','p_sub_title_link','p_title_href','p_swf','p_views','p_comments','p_totaltime');
	//隐藏:广告类型
	var cfg3 = new Array('p_type');
	//显示：图片地址、图片链接、文字标题
	var cfg4 = new Array('p_flash','p_sub_title_img','p_sub_title','p_sub_title_link','p_title_href','p_swf','p_views','p_comments','p_totaltime','p_type');
	//显示：图片地址、图片链接、文字标题、标题链接、视频地址
	var cfg5 = new Array('p_flash','p_sub_title_img','p_sub_title','p_sub_title_link','p_views','p_comments','p_totaltime','p_type');

	//需要显示：图片地址、图片链接、文字标题、广告类型
	var_tar2 = [250,251,252,253,254,255,256,257,250,251,252,253,254,255,256,257];
	//需要隐藏：广告类型
	var_tar3 = [221,239,240,241,214,215,216,217,218,219,220,221,222,223,224,225,226,227,228,239,240,241];
	//需要显示：图片地址、图片链接、文字标题
	var_tar4 = [177,178,179,180,181,183,184,186,187,188,193,19,195,196,197,198,199,200,201,202,203,204,205,206,207,208];
	//需要显示:图片地址、图片链接、文字标题、标题连接、视频地址
	var_tar5 = [134,135,147,148,150,154,157];
	
	//init
	for(var i=0;i<cfg1.length;i++){
		$('#'+cfg1[i]).show();
	}
	$('#p_p2c_commit').show();
	
	if($.inArray(cid, var_tar2) != -1){
		for(var i=0;i<cfg2.length;i++){
			$('#'+cfg2[i]).hide();
		}
	}else if($.inArray(cid, var_tar3) != -1){
		for(var i=0;i<cfg3.length;i++){
			$('#'+cfg3[i]).hide();
		}
	}else if($.inArray(cid, var_tar4) != -1){
		for(var i=0;i<cfg4.length;i++){
			$('#'+cfg4[i]).hide();
		}
	}else if($.inArray(cid, var_tar5) != -1){
		for(var i=0;i<cfg5.length;i++){
			$('#'+cfg5[i]).hide();
		}
	}else{
		alert('该广告位暂不支持自动生成代码功能！');
		$('#ad_js_panel').toggle('slow');
		$('#ad_js_input').toggle('slow');
		for(var i=0;i<cfg1.length;i++){
			$('#'+cfg1[i]).hide();
		}
		$('#p_p2c_commit').hide();
	}
	
}})

//根据广告位置加载对应广告类型
$.extend({'change_type':function(from_cid_id,for_type_id){
	
	if(typeof(from_cid_id) == "undefined") { 
		from_cid_id = "cid"; //默认广告类型控件 为向上兼容
	}
	
	if(typeof(for_type_id) == "undefined") { 
		for_type_id = "type"; //默认广告类型控件 为向上兼容
	}
	
	var obj = $("#"+for_type_id);
	var v = obj.val();
	
	$.ajax({
		url:"http://"+__Host+"/admin3/?action=Tackle&do=Cid2Type&cid="+$("#"+from_cid_id).attr('value'),
        type:"get",
        dataType:"json",
        success:function(data){
        	obj.empty();
    		for(var i in data){
    			if(data[i].name == ''){ 
    				continue;
    			}else if(data[i].id == v){
    				obj.append('<option value='+data[i].id+' selected>'+data[i].name+'</option>');
    			}else{
    				obj.append('<option value='+data[i].id+'>'+data[i].name+'</option>');
    			}
            }
        }
    });
}})

function multicitysinput(for_id,ct){
	$('#monitor_code_'+for_id).attr('value',ct);
}

function flvupload(ct){
	$('#flv_url').attr('value',ct);
	$('#flv_url_preview').toggle();
	$('#flv_url_preview').attr('href',ct);
}

jQuery(document).ready(function(){
	
	$(".type").live("mouseover",function(){
		$.change_type($(this).attr('from_cid_id'),$(this).attr('for_type_id'));
	});
	
	$(".input2param").live("click",function(){
		$('#ad_cmd_input').toggle('slow');
		$('#ad_cmd_panel').toggle('slow');
	});
	
	$(".input2code").live("click",function(){
		$.refresh_input2code_panel();
		$('#ad_js_panel').toggle('slow');
		$('#ad_js_input').toggle('slow');
	});

	//新增子合同
    $(".add_c_contract").click(function(){
    	//从后端初始化子合同面板
    	$.ajax({
			url:"http://"+__Host+"/admin3/?action=Order&do=GetSubContractPanel",
            type:"get",
            dataType:"html",
            success:function(htmlcode){
            	$("#common").after(htmlcode); 
                page_style(); //重新渲染样式
            }
        });
    });
	
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
					//$('#switch_panel').html('');
					$('#p_p2c_commit').append('<select id="switch_panel_s" onchange="$(\'#google\').attr(\'value\',$(this).val());$(\'#ad_js_panel\').fadeOut();$(\'#ad_js_input\').fadeIn();"><option>-请选择类型-</option></select>');
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
	
	//加载通用信息以及城市监测代码 
	$.extend({'load_comm_info':function(id,ned_param){
		$.ajax({  
		      type:'post',  
		      url:"http://"+__Host+"/admin3/?action=Tackle&do=GetContractCode&id="+id,
		      dataType:'json',
		      success: function(data_p){  
		    	
				if(true == ned_param){
					$("#cid").attr('value',data_p.ad_type);
					$("#vid").attr('value',data_p.customer_id);
					$("#title").attr('value',data_p.title);
					$("#description").attr('value',data_p.description);
					$("#type").attr('value',data_p.ad_sub_type);
					
					$("#starttime").attr('value',data_p.startdatetime);
					$("#endtime").attr('value',data_p.enddatetime);
					//$("#starttime").attr('value',data_p.starttime_year+"-"+data_p.starttime_month+"-"+data_p.starttime_day+" 00:00:00");
					//$("#endtime").attr('value',data_p.endtime_year+"-"+data_p.endtime_month+"-"+data_p.endtime_day+" 00:00:00");
				}
				
				$(".contract_city").html("<option>-- 请选择子合同 --</option>");
				
				if(typeof(data_p.citycode) == 'undefined'){//没有排期表数据
					
				}else{
					
					if(data_p.pqv == 2){
						for(var i=0;i<data_p.citycode.length;i++){
							$(".contract_city").append('<option today_cpm='+data_p.citycode[i].today_cpm+' idf='+data_p.citycode[i].idf+' city='+data_p.citycode[i].city+' viewurl='+data_p.citycode[i].viewurl+' clickurl='+data_p.citycode[i].clickurl+' flvurl='+data_p.citycode[i].flvurl+' schedule='+data_p.citycode[i].schedule+'>'+data_p.citycode[i].info+'</option>');
						}
					}else if(data_p.pqv == 1){ //旧合同订单系统
						for(var i=0;i<data_p.citycode.length;i++){
							$(".contract_city").append('<option today_cpm='+data_p.citycode[i].today_cpm+' viewurl='+data_p.citycode[i].viewurl+' clickurl='+data_p.citycode[i].clickurl+' flvurl='+data_p.citycode[i].flvurl+' schedule='+data_p.citycode[i].schedule+'>'+data_p.citycode[i].city+'</option>');
						}
						//if(confirm('当前版本系统不支持该合同的排期表类型，是否移步旧系统继续操作？')){
							//location.href = 'http://mads.56.com/admin3/?action=Order&do=Transaction&show=add';
						//}
					}
				}
		      }  
	    });
	}})
	
	//自动填写曝光及点击监测代码
	$(".contract_city").change(function(){
		
		var obj = $(this).find("option:selected");
		
    	if($("#type").attr('value') == 21){ //暂停广告的特殊处理
    		$("#link").attr('value',obj.attr("clickurl")=='#'||obj.attr("clickurl")=='null'?'':obj.attr("clickurl"));
    		$("#flash").attr('value',obj.attr("flvurl")=='#'||obj.attr("flvurl")=='null'?'':obj.attr("flvurl"));
    	}else{
    		$("#tp_click").attr('value',obj.attr("clickurl")=='#'||obj.attr("clickurl")=='null'?'':obj.attr("clickurl"));
    		$("#flv_url").attr('value',obj.attr("flvurl")=='#'||obj.attr("flvurl")=='null'?'':obj.attr("flvurl"));
    	}
    	
    	$("#tp_viewurl").attr('value',obj.attr("viewurl")=='#'||obj.attr("viewurl")=='null'?'':obj.attr("viewurl"));
		
		if(obj.attr("city") != '中国'){
			$("#city").html('');
			$("#city").append(obj.attr("city"));
		}
		$("#city").attr("readonly","true");
		
		$("#schedule").attr("value",obj.attr("schedule"));
		$("#schedual_preview").attr("src","http://"+__Host+"/admin3/?action=Order&do=Schedule&msg="+obj.attr("schedule"));
	
		$("#cpm").attr("value",obj.attr("today_cpm"));
		$("#idf").attr("value",obj.attr("idf"));
	});

	//页面开始加载合同信息
	if($(".contract_selected").length){
		var cid = $('#md_cid').val();
        if(cid != '') $.load_comm_info(cid,false); //加载通用信息
		$.ajax({  
		      type:'post',  
		      url: "http://"+__Host+"/admin3/?action=Tackle&do=GetContractInfo",
		      dataType:'json',
		      success: function(data_p){  
	    		for(var i=0;i<data_p.length;i++){
		        	if(cid == data_p[i].id){
		        		$(".contract_selected").append('<option value='+data_p[i].id+' selected>['+data_p[i].contract_id+']'+data_p[i].remark+'</option>');
		        	}else{
		        		$(".contract_selected").append('<option value='+data_p[i].id+'>['+data_p[i].contract_id+']'+data_p[i].remark+'</option>');
		        	}
				}
		      }
		 });
		
		$(".contract_selected").change(function(){
			$.load_comm_info($(this).val(),true);
		});
	}
	
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
			if($(this).val() != 'on'){
				aids += $(this).val()+',';   //获得目标aid串
			}
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
			if($(this).val() != 'on'){
				aids += $(this).val()+',';   //获得目标aid串
			}
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
	
	$('.click_detail').click(function(){
		var aid = $(this).attr("aid");
		var ts  = $(this).attr("ts");
		$.fancybox({
			width				: 940,
			height				: 430,
			overlayColor 		: "#000000", //要指定黑色背景，
			overlayOpacity 	    : 0.7, 
			transitionIn		: 'true',
			transitionOut		: 'true',
			type				: 'iframe',
			centerOnScroll      : true,
			padding			    : 0,
			href             : './?action=Query&do=Statistics&ShowType=VcHistory&type=click&ts='+ts+'&aid='+aid
		});
	});
	
	$('.view_detail').click(function(){
		var aid = $(this).attr("aid");
		var ts  = $(this).attr("ts");
		$.fancybox({
			width				: 940,
			height				: 430,
			overlayColor 		: "#000000", //要指定黑色背景，
			overlayOpacity 	    : 0.7, 
			transitionIn		: 'true',
			transitionOut		: 'true',
			type				: 'iframe',
			centerOnScroll      : true,
			padding			    : 0,
			href             : './?action=Query&do=Statistics&ShowType=VcHistory&type=view&ts='+ts+'&aid='+aid
		});
	});
	
	$('.ip_detail').click(function(){
		var aid = $(this).attr("aid");
		var ts  = $(this).attr("ts");
		$.fancybox({
			width				: 940,
			height				: 430,
			overlayColor 		: "#000000", //要指定黑色背景，
			overlayOpacity 	    : 0.7, 
			transitionIn		: 'true',
			transitionOut		: 'true',
			type				: 'iframe',
			centerOnScroll      : true,
			padding			    : 0,
			href             : './?action=Query&do=Statistics&ShowType=VcHistory&type=viewip&ts='+ts+'&aid='+aid
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
			href                : './?action=Order&do=AdPreview&aid='+aid
		});
	});
	
	//重新渲染统计柱状图
	$(".get_graph").click(function(){
		
		var aid = $(this).attr("aid");
		var year = $(this).attr("year");
		var month = $(this).attr("month");
		var day = $(this).attr("day");
		
		$.ajax({
			url:"http://"+__Host+"/admin3/?action=Order&do=GetDailyInfo",
            data:{aid:aid,year:year,month:month,day:day},
            type:"post",
            dataType:"json",
            success:function(data){
                $(".date").html(year+"-"+month+"-"+day);
                $("#view_gra").attr("src","../chart/chart.php?type=us_post&str="+data.view_str);
                $("#click_gra").attr("src","../chart/chart.php?type=us_post&str="+data.click_str);
                $("#viewip_gra").attr("src","../chart/chart.php?type=us_post&str="+data.viewip_str);
            }
        });
	});
	
});