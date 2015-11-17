//首焦
$('#homeslide').slides({
	doVML: true,
	play: 3000,
	pause: 2000,
	effect: 'fade',
	crossfade: true,
	hoverPause: true
});

//纳米说滚动
function startmarquee(elementID,h,n,speed,delay){
	var t = null;
	var box = '#' + elementID;
	$(box).hover(function(){
		clearInterval(t);
		}, function(){
		t = setInterval(start,delay);
	});
	function start(){
		$(box).children('ul:first').animate({marginTop: h},speed,function(){
			$(this).css({marginTop:'-230px'}).find("li:last").prependTo(this);
		})
	}
}

$(function(){

//首页50% OFF轮播
$('#sectionitems').slides({
	generatePagination: false,
	generateNextPrev: true
});
//鼠标上浮有黑底边框出现
$('#home .mainbox td').mouseover(function(){
	$('#home .mainbox td').removeClass('hover');
	$(this).addClass('hover');
});

//纳米说
startmarquee('maijia_say',0,1,500,5000);
$('#maijia_say').load("/comment/mjsForIndex").trigger('mouseout');

});


