//延迟执行代码的插件，主要包括延迟，阻止重复执行
(function($){var a={},c="doTimeout",d=Array.prototype.slice;$[c]=function(){return b.apply(window,[0].concat(d.call(arguments)))};$.fn[c]=function(){var f=d.call(arguments),e=b.apply(this,[c+f[0]].concat(f));return typeof f[0]==="number"||typeof f[1]==="number"?this:e};function b(l){var m=this,h,k={},g=l?$.fn:$,n=arguments,i=4,f=n[1],j=n[2],p=n[3];if(typeof f!=="string"){i--;f=l=0;j=n[1];p=n[2]}if(l){h=m.eq(0);h.data(l,k=h.data(l)||{})}else{if(f){k=a[f]||(a[f]={})}}k.id&&clearTimeout(k.id);delete k.id;function e(){if(l){h.removeData(l)}else{if(f){delete a[f]}}}function o(){k.id=setTimeout(function(){k.fn()},j)}if(p){k.fn=function(q){if(typeof p==="string"){p=g[p]}p.apply(m,d.call(n,i))===true&&!q?o():e()};o()}else{if(k.fn){j===undefined?e():k.fn(j===false);return true}else{e()}}}})(jQuery);
//延迟加载图片
function lazyload(option){var settings={defObj:null,defHeight:-200};settings=$.extend(settings,option||{});var defHeight=settings.defHeight,defObj=(typeof settings.defObj=="object")?settings.defObj.find("img"):$(settings.defObj).find("img");var pageTop=function(){return document.documentElement.clientHeight+Math.max(document.documentElement.scrollTop,document.body.scrollTop)-settings.defHeight;};var imgLoad=function(){defObj.each(function(){if($(this).offset().top<=pageTop()){var original=$(this).attr("original");if(original){$(this).attr("src",original).removeAttr("original").hide().fadeIn();};};});};imgLoad();$(window).bind("scroll",function(){imgLoad();});};
$(function(){
$("#header div.links").load("/user/loginAuth?"+new Date().getTime(),function(){
	updateCartCount();
});

//延迟加载页面上的图片
lazyload({defObj:"#wrapper"});		
})

