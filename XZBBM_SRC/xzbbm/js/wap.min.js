/*
$.extend({'share':function(_appid){
	var isCommit = confirm('使用学长客户端即可在手机查看、分享海量高校教辅资料，点击确定开始下载。');
	if(isCommit == true){
		location.href  = 'http://www.xzbbm.cn/?do=App&from=wap';
	}
	return false;
}})
*/$(document).ready(function(){$("#sendmailsbt").click(function(){var a=$("#sendmailaddr");$(this).attr("from");if(""==a.val()||"\u7535\u5b50\u90ae\u7bb1\u5730\u5740"==a.val())return!1;confirm("\u8bf7\u786e\u8ba4\u90ae\u7bb1\u5730\u5740:"+a.val())&&$.ajax({url:"/?action\x3dIndex\x26do\x3dSendByMail\x26file_index\x3d"+a.attr("file_index")+"\x26addr\x3d"+a.val(),type:"get",dataType:"jsonp",jsonp:"callback",success:function(a){1==a.rcode?alert(a.res):(alert(a.res),$(".dialog").toggle())}})});$("#cloudprint").click(function(){var a=$(this).attr("file_id");$.ajax({url:"/?action\x3dIndex\x26do\x3dCloudPrint\x26file_id\x3d"+a,type:"get",dataType:"json",success:function(a){0==a.rs?alert(a.msg):alert("\u6253\u5370\u63a5\u53e3\u8c03\u7528\u5931\u8d25\uff01")}})})});
//@ sourceMappingURL=wap.min.js.map