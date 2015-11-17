<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<title>56.com 广告管理系统  - 流量展示</title>
<script src="./script/js/jquery-1.4.4.js?jsversion=12"></script>
<script src="./script/js/ichart.1.2.min.js?jsversion=1"></script>
</head>
<body>
<div id="canvasDiv" style="text-align:center;">
	
<script type="text/javascript">
$(function(){
	var data = [
	        	{
	        		name : '上海',
	        		value:[4,16,18,24,32,36,38,38,36,26,20,14,4,16,18,24,32,36,38,38,36,26,20,14],
	        		color:'#aad0db',
	        		line_width:2
	        	},
	        	{
	        		name : '北京',
	        		value:[-9,1,12,20,26,30,32,29,22,12,0,-6,4,16,18,24,32,36,38,38,36,26,20,14],
	        		color:'#f68f70',
	        		line_width:2
	        	}
	        ];
    
	var labels = ["00","01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23"];
	
	var chart = new iChart.Area2D({
		render : 'canvasDiv',
		data: data,
		title : '北京-上海两城市2012年平均温度情况',
		width : 800,
		height : 400,
		legend : {
			enable : true,
			row:1,//设置在一行上显示，与column配合使用
			column : 'max',
			valign:'top',
			background_color:null,//设置透明背景
			offsetx:-30,//设置x轴偏移，满足位置需要
			border : false 
		},
		tip:{
			enable : true,
			listeners:{
				 //tip:提示框对象、name:数据名称、value:数据值、text:当前文本、i:数据点的索引
				parseText:function(tip,name,value,text,i){
					return name+"地区<br/>:"+labels[i]+"平均温度:"+value+"℃";
				}
			}
		},
		crosshair:{
			enable:true,
			line_color:'#62bce9',
			line_width:2
		},
		sub_option:{
			label:false,
			point_size:10 
		},
		background_color:'#ffffff',
		coordinate:{
			axis : {
				width : [0, 0, 2, 0]
			},
			background_color:'#ffffff',
			height:'90%',
			scale:[{
				 position:'left',	
				 scale_space:5,
				 scale_enable:false,//禁用小横线
				 listeners:{
					parseText:function(t,x,y){
						return {text:t+"℃"}
					}
				}
			},{
				 position:'bottom',	
				 start_scale:1,
				 end_scale:12,
				 parseText:function(t,x,y){
					return {textY:y+10}
				 },
				 labels:labels
			}]
		}
	});
	chart.draw();

});
</script>
</div>
</body>
</html>