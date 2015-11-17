$(document).ready(function() { 
$(".select_box").click(function(event) { 
event.stopPropagation(); 
$(this).find(".option").toggle(); 
$(this).parent().siblings().find(".option").hide(); 
}); 
$(document).click(function(event) { 
var eo = $(event.target); 
if ($(".select_box").is(":visible") && eo.attr("class") != "option" && 
!eo.parent(".option").length) $('.option').hide(); 
}); 
/*赋值给文本框*/ 
$(".option a").click(function() { 
var value = $(this).text(); 
$(this).parent().siblings(".select_txt").text(value) 
.siblings(".select_value").val(value); 
//$(this).parent() 
}) 
}) 


$(document).ready(function() {
	jQuery.jqtab = function(tabtit,tab_conbox,shijian) {
		$(tab_conbox).find("li").hide();
		$(tabtit).find("li:first").addClass("thistab").show(); 
		$(tab_conbox).find("li:first").show();
	
		$(tabtit).find("li").bind(shijian,function(){
		  $(this).addClass("thistab").siblings("li").removeClass("thistab"); 
			var activeindex = $(tabtit).find("li").index(this);
			$(tab_conbox).children().eq(activeindex).show().siblings().hide();
			return false;
		});
	
	};
	/*调用方法如下：*/
	$.jqtab("#tabs","#tab_conbox","click");
	$.jqtab("#tabs1","#tab_conbox1","click");
});