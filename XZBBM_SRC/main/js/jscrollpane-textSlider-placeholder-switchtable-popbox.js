!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):"object"==typeof exports?module.exports=a(require("jquery")):a(jQuery)}(function(a){a.fn.jScrollPane=function(b){function c(b,c){function d(c){var f,h,j,k,l,o,p=!1,q=!1;if(N=c,void 0===O)l=b.scrollTop(),o=b.scrollLeft(),b.css({overflow:"hidden",padding:0}),P=b.innerWidth()+rb,Q=b.innerHeight(),b.width(P),O=a('<div class="jspPane" />').css("padding",qb).append(b.children()),R=a('<div class="jspContainer" />').css({width:P+"px",height:Q+"px"}).append(O).appendTo(b);else{if(b.css("width",""),p=N.stickToBottom&&A(),q=N.stickToRight&&B(),k=b.innerWidth()+rb!=P||b.outerHeight()!=Q,k&&(P=b.innerWidth()+rb,Q=b.innerHeight(),R.css({width:P+"px",height:Q+"px"})),!k&&sb==S&&O.outerHeight()==T)return void b.width(P);sb=S,O.css("width",""),b.width(P),R.find(">.jspVerticalBar,>.jspHorizontalBar").remove().end()}O.css("overflow","auto"),S=c.contentWidth?c.contentWidth:O[0].scrollWidth,T=O[0].scrollHeight,O.css("overflow",""),U=S/P,V=T/Q,W=V>1,X=U>1,X||W?(b.addClass("jspScrollable"),f=N.maintainPosition&&($||bb),f&&(h=y(),j=z()),e(),g(),i(),f&&(w(q?S-P:h,!1),v(p?T-Q:j,!1)),F(),C(),L(),N.enableKeyboardNavigation&&H(),N.clickOnTrack&&m(),J(),N.hijackInternalLinks&&K()):(b.removeClass("jspScrollable"),O.css({top:0,left:0,width:R.width()-rb}),D(),G(),I(),n()),N.autoReinitialise&&!pb?pb=setInterval(function(){d(N)},N.autoReinitialiseDelay):!N.autoReinitialise&&pb&&clearInterval(pb),l&&b.scrollTop(0)&&v(l,!1),o&&b.scrollLeft(0)&&w(o,!1),b.trigger("jsp-initialised",[X||W])}function e(){W&&(R.append(a('<div class="jspVerticalBar" />').append(a('<div class="jspCap jspCapTop" />'),a('<div class="jspTrack" />').append(a('<div class="jspDrag" />').append(a('<div class="jspDragTop" />'),a('<div class="jspDragBottom" />'))),a('<div class="jspCap jspCapBottom" />'))),cb=R.find(">.jspVerticalBar"),db=cb.find(">.jspTrack"),Y=db.find(">.jspDrag"),N.showArrows&&(hb=a('<a class="jspArrow jspArrowUp" />').bind("mousedown.jsp",k(0,-1)).bind("click.jsp",E),ib=a('<a class="jspArrow jspArrowDown" />').bind("mousedown.jsp",k(0,1)).bind("click.jsp",E),N.arrowScrollOnHover&&(hb.bind("mouseover.jsp",k(0,-1,hb)),ib.bind("mouseover.jsp",k(0,1,ib))),j(db,N.verticalArrowPositions,hb,ib)),fb=Q,R.find(">.jspVerticalBar>.jspCap:visible,>.jspVerticalBar>.jspArrow").each(function(){fb-=a(this).outerHeight()}),Y.hover(function(){Y.addClass("jspHover")},function(){Y.removeClass("jspHover")}).bind("mousedown.jsp",function(b){a("html").bind("dragstart.jsp selectstart.jsp",E),Y.addClass("jspActive");var c=b.pageY-Y.position().top;return a("html").bind("mousemove.jsp",function(a){p(a.pageY-c,!1)}).bind("mouseup.jsp mouseleave.jsp",o),!1}),f())}function f(){db.height(fb+"px"),$=0,eb=N.verticalGutter+db.outerWidth(),O.width(P-eb-rb);try{0===cb.position().left&&O.css("margin-left",eb+"px")}catch(a){}}function g(){X&&(R.append(a('<div class="jspHorizontalBar" />').append(a('<div class="jspCap jspCapLeft" />'),a('<div class="jspTrack" />').append(a('<div class="jspDrag" />').append(a('<div class="jspDragLeft" />'),a('<div class="jspDragRight" />'))),a('<div class="jspCap jspCapRight" />'))),jb=R.find(">.jspHorizontalBar"),kb=jb.find(">.jspTrack"),_=kb.find(">.jspDrag"),N.showArrows&&(nb=a('<a class="jspArrow jspArrowLeft" />').bind("mousedown.jsp",k(-1,0)).bind("click.jsp",E),ob=a('<a class="jspArrow jspArrowRight" />').bind("mousedown.jsp",k(1,0)).bind("click.jsp",E),N.arrowScrollOnHover&&(nb.bind("mouseover.jsp",k(-1,0,nb)),ob.bind("mouseover.jsp",k(1,0,ob))),j(kb,N.horizontalArrowPositions,nb,ob)),_.hover(function(){_.addClass("jspHover")},function(){_.removeClass("jspHover")}).bind("mousedown.jsp",function(b){a("html").bind("dragstart.jsp selectstart.jsp",E),_.addClass("jspActive");var c=b.pageX-_.position().left;return a("html").bind("mousemove.jsp",function(a){r(a.pageX-c,!1)}).bind("mouseup.jsp mouseleave.jsp",o),!1}),lb=R.innerWidth(),h())}function h(){R.find(">.jspHorizontalBar>.jspCap:visible,>.jspHorizontalBar>.jspArrow").each(function(){lb-=a(this).outerWidth()}),kb.width(lb+"px"),bb=0}function i(){if(X&&W){var b=kb.outerHeight(),c=db.outerWidth();fb-=b,a(jb).find(">.jspCap:visible,>.jspArrow").each(function(){lb+=a(this).outerWidth()}),lb-=c,Q-=c,P-=b,kb.parent().append(a('<div class="jspCorner" />').css("width",b+"px")),f(),h()}X&&O.width(R.outerWidth()-rb+"px"),T=O.outerHeight(),V=T/Q,X&&(mb=Math.ceil(1/U*lb),mb>N.horizontalDragMaxWidth?mb=N.horizontalDragMaxWidth:mb<N.horizontalDragMinWidth&&(mb=N.horizontalDragMinWidth),_.width(mb+"px"),ab=lb-mb,s(bb)),W&&(gb=Math.ceil(1/V*fb),gb>N.verticalDragMaxHeight?gb=N.verticalDragMaxHeight:gb<N.verticalDragMinHeight&&(gb=N.verticalDragMinHeight),Y.height(gb+"px"),Z=fb-gb,q($))}function j(a,b,c,d){var e,f="before",g="after";"os"==b&&(b=/Mac/.test(navigator.platform)?"after":"split"),b==f?g=b:b==g&&(f=b,e=c,c=d,d=e),a[f](c)[g](d)}function k(a,b,c){return function(){return l(a,b,this,c),this.blur(),!1}}function l(b,c,d,e){d=a(d).addClass("jspActive");var f,g,h=!0,i=function(){0!==b&&tb.scrollByX(b*N.arrowButtonSpeed),0!==c&&tb.scrollByY(c*N.arrowButtonSpeed),g=setTimeout(i,h?N.initialDelay:N.arrowRepeatFreq),h=!1};i(),f=e?"mouseout.jsp":"mouseup.jsp",e=e||a("html"),e.bind(f,function(){d.removeClass("jspActive"),g&&clearTimeout(g),g=null,e.unbind(f)})}function m(){n(),W&&db.bind("mousedown.jsp",function(b){if(void 0===b.originalTarget||b.originalTarget==b.currentTarget){var c,d=a(this),e=d.offset(),f=b.pageY-e.top-$,g=!0,h=function(){var a=d.offset(),e=b.pageY-a.top-gb/2,j=Q*N.scrollPagePercent,k=Z*j/(T-Q);if(0>f)$-k>e?tb.scrollByY(-j):p(e);else{if(!(f>0))return void i();e>$+k?tb.scrollByY(j):p(e)}c=setTimeout(h,g?N.initialDelay:N.trackClickRepeatFreq),g=!1},i=function(){c&&clearTimeout(c),c=null,a(document).unbind("mouseup.jsp",i)};return h(),a(document).bind("mouseup.jsp",i),!1}}),X&&kb.bind("mousedown.jsp",function(b){if(void 0===b.originalTarget||b.originalTarget==b.currentTarget){var c,d=a(this),e=d.offset(),f=b.pageX-e.left-bb,g=!0,h=function(){var a=d.offset(),e=b.pageX-a.left-mb/2,j=P*N.scrollPagePercent,k=ab*j/(S-P);if(0>f)bb-k>e?tb.scrollByX(-j):r(e);else{if(!(f>0))return void i();e>bb+k?tb.scrollByX(j):r(e)}c=setTimeout(h,g?N.initialDelay:N.trackClickRepeatFreq),g=!1},i=function(){c&&clearTimeout(c),c=null,a(document).unbind("mouseup.jsp",i)};return h(),a(document).bind("mouseup.jsp",i),!1}})}function n(){kb&&kb.unbind("mousedown.jsp"),db&&db.unbind("mousedown.jsp")}function o(){a("html").unbind("dragstart.jsp selectstart.jsp mousemove.jsp mouseup.jsp mouseleave.jsp"),Y&&Y.removeClass("jspActive"),_&&_.removeClass("jspActive")}function p(a,b){W&&(0>a?a=0:a>Z&&(a=Z),void 0===b&&(b=N.animateScroll),b?tb.animate(Y,"top",a,q):(Y.css("top",a),q(a)))}function q(a){void 0===a&&(a=Y.position().top),R.scrollTop(0),$=a||0;var c=0===$,d=$==Z,e=a/Z,f=-e*(T-Q);(ub!=c||wb!=d)&&(ub=c,wb=d,b.trigger("jsp-arrow-change",[ub,wb,vb,xb])),t(c,d),O.css("top",f),b.trigger("jsp-scroll-y",[-f,c,d]).trigger("scroll")}function r(a,b){X&&(0>a?a=0:a>ab&&(a=ab),void 0===b&&(b=N.animateScroll),b?tb.animate(_,"left",a,s):(_.css("left",a),s(a)))}function s(a){void 0===a&&(a=_.position().left),R.scrollTop(0),bb=a||0;var c=0===bb,d=bb==ab,e=a/ab,f=-e*(S-P);(vb!=c||xb!=d)&&(vb=c,xb=d,b.trigger("jsp-arrow-change",[ub,wb,vb,xb])),u(c,d),O.css("left",f),b.trigger("jsp-scroll-x",[-f,c,d]).trigger("scroll")}function t(a,b){N.showArrows&&(hb[a?"addClass":"removeClass"]("jspDisabled"),ib[b?"addClass":"removeClass"]("jspDisabled"))}function u(a,b){N.showArrows&&(nb[a?"addClass":"removeClass"]("jspDisabled"),ob[b?"addClass":"removeClass"]("jspDisabled"))}function v(a,b){var c=a/(T-Q);p(c*Z,b)}function w(a,b){var c=a/(S-P);r(c*ab,b)}function x(b,c,d){var e,f,g,h,i,j,k,l,m,n=0,o=0;try{e=a(b)}catch(p){return}for(f=e.outerHeight(),g=e.outerWidth(),R.scrollTop(0),R.scrollLeft(0);!e.is(".jspPane");)if(n+=e.position().top,o+=e.position().left,e=e.offsetParent(),/^body|html$/i.test(e[0].nodeName))return;h=z(),j=h+Q,h>n||c?l=n-N.horizontalGutter:n+f>j&&(l=n-Q+f+N.horizontalGutter),isNaN(l)||v(l,d),i=y(),k=i+P,i>o||c?m=o-N.horizontalGutter:o+g>k&&(m=o-P+g+N.horizontalGutter),isNaN(m)||w(m,d)}function y(){return-O.position().left}function z(){return-O.position().top}function A(){var a=T-Q;return a>20&&a-z()<10}function B(){var a=S-P;return a>20&&a-y()<10}function C(){R.unbind(zb).bind(zb,function(a,b,c,d){bb||(bb=0),$||($=0);var e=bb,f=$,g=a.deltaFactor||N.mouseWheelSpeed;return tb.scrollBy(c*g,-d*g,!1),e==bb&&f==$})}function D(){R.unbind(zb)}function E(){return!1}function F(){O.find(":input,a").unbind("focus.jsp").bind("focus.jsp",function(a){x(a.target,!1)})}function G(){O.find(":input,a").unbind("focus.jsp")}function H(){function c(){var a=bb,b=$;switch(d){case 40:tb.scrollByY(N.keyboardSpeed,!1);break;case 38:tb.scrollByY(-N.keyboardSpeed,!1);break;case 34:case 32:tb.scrollByY(Q*N.scrollPagePercent,!1);break;case 33:tb.scrollByY(-Q*N.scrollPagePercent,!1);break;case 39:tb.scrollByX(N.keyboardSpeed,!1);break;case 37:tb.scrollByX(-N.keyboardSpeed,!1)}return e=a!=bb||b!=$}var d,e,f=[];X&&f.push(jb[0]),W&&f.push(cb[0]),O.focus(function(){b.focus()}),b.attr("tabindex",0).unbind("keydown.jsp keypress.jsp").bind("keydown.jsp",function(b){if(b.target===this||f.length&&a(b.target).closest(f).length){var g=bb,h=$;switch(b.keyCode){case 40:case 38:case 34:case 32:case 33:case 39:case 37:d=b.keyCode,c();break;case 35:v(T-Q),d=null;break;case 36:v(0),d=null}return e=b.keyCode==d&&g!=bb||h!=$,!e}}).bind("keypress.jsp",function(a){return a.keyCode==d&&c(),!e}),N.hideFocus?(b.css("outline","none"),"hideFocus"in R[0]&&b.attr("hideFocus",!0)):(b.css("outline",""),"hideFocus"in R[0]&&b.attr("hideFocus",!1))}function I(){b.attr("tabindex","-1").removeAttr("tabindex").unbind("keydown.jsp keypress.jsp")}function J(){if(location.hash&&location.hash.length>1){var b,c,d=escape(location.hash.substr(1));try{b=a("#"+d+', a[name="'+d+'"]')}catch(e){return}b.length&&O.find(d)&&(0===R.scrollTop()?c=setInterval(function(){R.scrollTop()>0&&(x(b,!0),a(document).scrollTop(R.position().top),clearInterval(c))},50):(x(b,!0),a(document).scrollTop(R.position().top)))}}function K(){a(document.body).data("jspHijack")||(a(document.body).data("jspHijack",!0),a(document.body).delegate("a[href*=#]","click",function(b){var c,d,e,f,g,h,i=this.href.substr(0,this.href.indexOf("#")),j=location.href;if(-1!==location.href.indexOf("#")&&(j=location.href.substr(0,location.href.indexOf("#"))),i===j){c=escape(this.href.substr(this.href.indexOf("#")+1));try{d=a("#"+c+', a[name="'+c+'"]')}catch(k){return}d.length&&(e=d.closest(".jspScrollable"),f=e.data("jsp"),f.scrollToElement(d,!0),e[0].scrollIntoView&&(g=a(window).scrollTop(),h=d.offset().top,(g>h||h>g+a(window).height())&&e[0].scrollIntoView()),b.preventDefault())}}))}function L(){var a,b,c,d,e,f=!1;R.unbind("touchstart.jsp touchmove.jsp touchend.jsp click.jsp-touchclick").bind("touchstart.jsp",function(g){var h=g.originalEvent.touches[0];a=y(),b=z(),c=h.pageX,d=h.pageY,e=!1,f=!0}).bind("touchmove.jsp",function(g){if(f){var h=g.originalEvent.touches[0],i=bb,j=$;return tb.scrollTo(a+c-h.pageX,b+d-h.pageY),e=e||Math.abs(c-h.pageX)>5||Math.abs(d-h.pageY)>5,i==bb&&j==$}}).bind("touchend.jsp",function(){f=!1}).bind("click.jsp-touchclick",function(){return e?(e=!1,!1):void 0})}function M(){var a=z(),c=y();b.removeClass("jspScrollable").unbind(".jsp"),b.replaceWith(yb.append(O.children())),yb.scrollTop(a),yb.scrollLeft(c),pb&&clearInterval(pb)}var N,O,P,Q,R,S,T,U,V,W,X,Y,Z,$,_,ab,bb,cb,db,eb,fb,gb,hb,ib,jb,kb,lb,mb,nb,ob,pb,qb,rb,sb,tb=this,ub=!0,vb=!0,wb=!1,xb=!1,yb=b.clone(!1,!1).empty(),zb=a.fn.mwheelIntent?"mwheelIntent.jsp":"mousewheel.jsp";"border-box"===b.css("box-sizing")?(qb=0,rb=0):(qb=b.css("paddingTop")+" "+b.css("paddingRight")+" "+b.css("paddingBottom")+" "+b.css("paddingLeft"),rb=(parseInt(b.css("paddingLeft"),10)||0)+(parseInt(b.css("paddingRight"),10)||0)),a.extend(tb,{reinitialise:function(b){b=a.extend({},N,b),d(b)},scrollToElement:function(a,b,c){x(a,b,c)},scrollTo:function(a,b,c){w(a,c),v(b,c)},scrollToX:function(a,b){w(a,b)},scrollToY:function(a,b){v(a,b)},scrollToPercentX:function(a,b){w(a*(S-P),b)},scrollToPercentY:function(a,b){v(a*(T-Q),b)},scrollBy:function(a,b,c){tb.scrollByX(a,c),tb.scrollByY(b,c)},scrollByX:function(a,b){var c=y()+Math[0>a?"floor":"ceil"](a),d=c/(S-P);r(d*ab,b)},scrollByY:function(a,b){var c=z()+Math[0>a?"floor":"ceil"](a),d=c/(T-Q);p(d*Z,b)},positionDragX:function(a,b){r(a,b)},positionDragY:function(a,b){p(a,b)},animate:function(a,b,c,d){var e={};e[b]=c,a.animate(e,{duration:N.animateDuration,easing:N.animateEase,queue:!1,step:d})},getContentPositionX:function(){return y()},getContentPositionY:function(){return z()},getContentWidth:function(){return S},getContentHeight:function(){return T},getPercentScrolledX:function(){return y()/(S-P)},getPercentScrolledY:function(){return z()/(T-Q)},getIsScrollableH:function(){return X},getIsScrollableV:function(){return W},getContentPane:function(){return O},scrollToBottom:function(a){p(Z,a)},hijackInternalLinks:a.noop,destroy:function(){M()}}),d(c)}return b=a.extend({},a.fn.jScrollPane.defaults,b),a.each(["arrowButtonSpeed","trackClickSpeed","keyboardSpeed"],function(){b[this]=b[this]||b.speed}),this.each(function(){var d=a(this),e=d.data("jsp");e?e.reinitialise(b):(a("script",d).filter('[type="text/javascript"],:not([type])').remove(),e=new c(d,b),d.data("jsp",e))})},a.fn.jScrollPane.defaults={showArrows:!1,maintainPosition:!0,stickToBottom:!1,stickToRight:!1,clickOnTrack:!0,autoReinitialise:!1,autoReinitialiseDelay:500,verticalDragMinHeight:0,verticalDragMaxHeight:99999,horizontalDragMinWidth:0,horizontalDragMaxWidth:99999,contentWidth:void 0,animateScroll:!1,animateDuration:300,animateEase:"linear",hijackInternalLinks:!1,verticalGutter:4,horizontalGutter:4,mouseWheelSpeed:3,arrowButtonSpeed:0,arrowRepeatFreq:50,arrowScrollOnHover:!1,trackClickSpeed:0,trackClickRepeatFreq:70,verticalArrowPositions:"split",horizontalArrowPositions:"split",enableKeyboardNavigation:!0,hideFocus:!1,keyboardSpeed:0,initialDelay:300,speed:30,scrollPagePercent:.8}});


(function($){$.fn.textSlider=function(settings){settings=jQuery.extend({speed:"normal",line:2,timer:3000},settings);return this.each(function(){$.fn.textSlider.scllor($(this),settings)})};$.fn.textSlider.scllor=function($this,settings){var ul=$("ul:eq(0)",$this);var timerID;var li=ul.children();var liHight=$(li[0]).height();var upHeight=0-settings.line*liHight;var scrollUp=function(){ul.animate({marginTop:upHeight},settings.speed,function(){for(i=0;i<settings.line;i++){ul.find("li:first",$this).appendTo(ul)}ul.css({marginTop:0})})};var autoPlay=function(){timerID=window.setInterval(scrollUp,settings.timer)};var autoStop=function(){window.clearInterval(timerID)};ul.hover(autoStop,autoPlay).mouseout()}})(jQuery);

"use strict";!function(e,t){function i(e){return r.createElement(e)}function n(e){return e.runtimeStyle||e.style}function a(e){return e.currentStyle||b(e)}function s(){var t=this;if(/^text(area)?|password|email|search|tel|url$/i.test(t.type)){var s,o,l,d=function(){var e=c in t?t[c]:t.getAttribute(c);!s&&e&&(s=i(c),s.onmousedown=function(){return setTimeout(function(){t.focus()},1),!1}),s&&(s.innerHTML=e||"")},f=function(){if(clearTimeout(o),s){var e=s.innerHTML&&!t.value,i=a(t),l=n(s),r=t.parentNode,c=r&&(t.offsetHeight||t.offsetWidth);l.display=e&&c?"block":"none",c?e&&(/^textarea$/i.test(t.tagName)?(l.whiteSpace=_,l.wordBreak="break-all"):(l.whiteSpace="nowrap",l.wordBreak=_),(i.position!==g||a(r).position!==g)&&(l.width="left"===i.textAlign?"auto":b?b(t).width:t.clientWidth-v(i.paddingLeft)-v(i.paddingRight)+$,l.left=t.offsetLeft+t.clientLeft+$,l.top=t.offsetTop+t.clientTop+$,l.position="absolute",h("marginLeft","paddingLeft"),h("marginTop","paddingTop")),b&&i.lineHeight===_?l.lineHeight=b(t).height:h("lineHeight"),h("textAlign"),h("fontFamily"),h("fontWidth"),h("fontSize"),t.nextSibling?r.insertBefore(s,t.nextSibling):r.appendChild(s)):o=setTimeout(f,50)}},h=function(e,i){try{n(s)[e]=a(t)[i||e]}catch(o){}};u.forEach?(l=function(e,i,n){(n||t).addEventListener(e,i,!0),n||r.addEventListener(e,function(e){e.target===t&&i()},!1)},u.forEach(function(e){l(e,function(){d(),f()})})):t.attachEvent&&(l=function(e,i,n){(n||t).attachEvent("on"+e,i)},l("propertychange",function(){switch(event.propertyName){case c:d();default:f()}}),l("keypress",f)),d(),f(),l&&l("resize",f,e)}}function o(e){var t=e("input,textarea").each(s);f&&function(){"complete"===r.readyState?t.each(function(){var e=this;if(null!==e.getAttribute("autofocus"))try{return e.focus(),!1}catch(t){}}):setTimeout(arguments.callee,200)}()}function l(e,t){t=(t||"")+c,m.styleSheet?m.styleSheet.addRule(t,e):m.appendChild(r.createTextNode(t+"{"+e+"}"))}var r=document,c="placeholder",d="text-overflow:ellipsis;overflow:hidden;cursor:text;color:gray;opacity:1;padding:0;border:0;",u="change keypress keyup keydown input blur DOMAttrModified".split(/\s/),f=i("input")[c]===t,h=r.documentElement.firstChild,m=i("style"),p=r.documentMode,v=e.parseInt,_="normal",g="static",$="px",b=e.getComputedStyle?function(t){return e.getComputedStyle(t,null)}:0;if(h.insertBefore(m,h.firstChild),(f||p)&&(l(d),e.LQ?o(LQ):e.jQuery&&jQuery(o)),f){var y={set:function(e){this.setAttribute(c,e)},get:function(){return this.getAttribute(c)||""}},C=Object.defineProperty,x="prototype";C&&(C(HTMLTextAreaElement[x],c,y),C(HTMLInputElement[x],c,y))}else p>9?l("color:transparent !important;",":-ms-input-"):l(d,"netscape"in e?"::-moz-":"::-webkit-input-")}(this);

var LEGO=LEGO?LEGO:{};jQuery.extend(jQuery.easing,{easeOutExpo:function(x,t,b,c,d){return(t==d)?b+c:c*(-Math.pow(2,-10*t/d)+1)+b;}});(function($,k,w){function Switchable(elem,opts){if(arguments.length!==0){this.opts={event:"click",effect:"none",autoPlay:false,speed:"normal",timer:4000,nav:"J_nav",content:"J_content",btnPrev:"J_btnPrev",btnNext:"J_btnNext",steps:1,hoverInterval:100,mousewheel:false,figureNav:false}
jQuery.extend(this.opts,this.opts,opts);this.$elem=elem;this.$nav=elem.find("."+this.opts.nav).eq(0);this.$cont=elem.find("."+this.opts.content).eq(0);this.$btnPrev=elem.find("."+this.opts.btnPrev).eq(0);this.$btnNext=elem.find("."+this.opts.btnNext).eq(0);this._eventTimer=null;this._autoPlayTimer=null;this._activeIndex=0;}}
Switchable.prototype={_struct:function(){var self=this;if(this.constructor===Tab&&this.opts.event==="click"){this.$nav.children().hover(function(){$(this).addClass("hover");},function(){$(this).removeClass("hover");});}
if(this.constructor===Carousel){var max=Math.ceil(this.$cont.children().size()/this.opts.steps)-1;if(this.$nav.length&&($.trim(this.$nav[0].innerHTML)=='')){for(var i=0;i<=max;i++){var a="<a href='#"+i+"' target='_self'></a>";if(i==0){a="<a href='#"+i+"' class='selected' target='_self'></a>";}
this.$nav.append(a);}}
if(this.opts.mousewheel){this.$elem.bind('mousewheel',function(event,delta){event.preventDefault();if(!self.$cont.is(":animated")){if(delta===1){self.prev();}else{self.next();}}});}
if(this.opts.figureNav&&this.$elem.find(".steps").length){var figure=max+1;var figureHtml='<span class="curr-step">1</span><span class="total-step">/'+figure+'</span>';this.$elem.find(".steps").html(figureHtml)}}
this.$nav.children().each(function(index,elem){if(self.opts.event==="hover"){$(elem).hover(function(){clearTimeout(self._eventTimer);self._eventTimer=setTimeout(function(){self._switch(index);},self.opts.hoverInterval);},function(){clearTimeout(self._eventTimer);});}else{$(elem).click(function(){self._switch(index);});}
$(elem)[self.opts.event](function(){if(self.opts.event==="click"){self._switch(index);}else{}});});if(this.$btnPrev.size()){this.$btnPrev.unbind("click");this.$btnPrev.bind("click",function(){self.prev();});}
if(this.$btnNext.size()){this.$btnNext.unbind("click");this.$btnNext.bind("click",function(){self.next();});}
switch(this.opts.effect){case"scrollX":var contElems=this.$cont.children(),contWidth=contElems.outerWidth(true)*contElems.length;contElems.css({"float":"left"});this.$cont.css({"width":contWidth});break;case"scrollY":break;case"fade":this.$cont.css({"position":"relative"});this.$cont.children().css({"position":"absolute","left":"0","top":"0","z-index":"0","opacity":"0"}).eq(0).css({"opacity":1,"z-index":1});break;}
(this.opts.autoPlay===true)&&this._autoPlay();},_switch:function(index){var indexOjb=this._checkIndex(index);switch(this.opts.effect){case"scrollX":this._switchScrollx(indexOjb);break;case"scrollY":this._switchScrolly(indexOjb);break;case"fade":this._switchFade(indexOjb);break;default:this._switchNormal(indexOjb);}
if(this.opts.figureNav&&this.$elem.find(".steps").length){this._figureNav(indexOjb.index);}
this._setNavCls(indexOjb.index);this._activeIndex=indexOjb.index;},_figureNav:function(index){this.$elem.find(".curr-step").text(index+1);},_switchNormal:function(indexOjb){var contElems=this.$cont.children();contElems.hide();contElems.eq(indexOjb.index).show();},_switchFade:function(indexOjb){var contElems=this.$cont.children();contElems.eq(this._activeIndex).stop().animate({opacity:0},600).css("z-index",0);contElems.eq(indexOjb.index).stop().animate({opacity:1},600).css("z-index",1);},_switchScrollx:function(indexOjb){var contElems=this.$cont.children(),panelWidth=contElems.outerWidth(true),moveWidth=-indexOjb.index*this.opts.steps*panelWidth,moveWidth2=-(indexOjb.index-1)*this.opts.steps*panelWidth,relaWidth=panelWidth*(indexOjb.maxIndex+1)*this.opts.steps;contElems.each(function(index,element){this.style.cssText="float:left";});switch(indexOjb.type){case"max":for(var i=0;i<this.opts.steps;i++){contElems.eq(i).css({"position":"relative","left":relaWidth});}
this.$cont.animate({"left":-relaWidth},400,"easeOutExpo",function(){contElems.each(function(index,element){this.style.cssText="float:left";});$(this).css("left",moveWidth);});break;case"min":for(var i=this.opts.steps;i>0;i--){contElems.eq(contElems.length-i).css({"position":"relative","left":-relaWidth});}
this.$cont.animate({"left":panelWidth*this.opts.steps},400,"easeOutExpo",function(){contElems.each(function(index,element){this.style.cssText="float:left";});$(this).css("left",moveWidth);});break;default:this.$cont.stop().animate({"left":moveWidth},400,"easeOutExpo");}},_switchScrolly:function(indexOjb){var contElems=this.$cont.children(),panelWidth=contElems.outerHeight(true),moveWidth=-indexOjb.index*this.opts.steps*panelWidth;switch(indexOjb.type){case"max":for(var i=0;i<this.opts.steps;i++){contElems.eq(i).css({"position":"relative","top":panelWidth*(indexOjb.maxIndex+1)*this.opts.steps});}
this.$cont.stop().animate({"top":-panelWidth*this.opts.steps*(indexOjb.maxIndex+1)},400,"easeOutExpo",function(){contElems.each(function(){this.style.cssText="";});$(this).css("top",moveWidth);});break;case"min":for(var i=this.opts.steps;i>0;i--){contElems.eq(contElems.length-i).css({"position":"relative","top":-panelWidth*(indexOjb.maxIndex+1)*this.opts.steps});}
this.$cont.stop().animate({"top":panelWidth*this.opts.steps},400,"easeOutExpo",function(){contElems.each(function(){this.style.cssText="";});$(this).css("top",moveWidth);});break;default:this.$cont.stop().animate({"top":moveWidth},400,"easeOutExpo");}},_setNavCls:function(index){var index=index||0,navElems=this.$nav.children();navElems.removeClass("selected");navElems.eq(index).addClass("selected");},_autoPlay:function(){var self=this
clearInterval(this._autoPlayTimer);this._autoPlayTimer=setInterval(function(){self._doPlay();},self.opts.timer);this.$elem.mouseenter(function(){clearInterval(self._autoPlayTimer);});this.$elem.mouseleave(function(){clearInterval(self._autoPlayTimer);self._autoPlayTimer=setInterval(function(){self._doPlay();},self.opts.timer);});},_doPlay:function(){this._switch(this._activeIndex+1);},_checkIndex:function(index){var maxIndex=Math.ceil(this.$cont.children().size()/this.opts.steps)-1;var obj={index:index,type:"normal",maxIndex:maxIndex}
if(index<0){obj.type="min";obj.index=maxIndex;}else if(index>maxIndex){obj.type="max";obj.index=0;}
return obj;},stop:function(){alert("stop")},start:function(){},switchTo:function(index){this._switch(index);},prev:function(){this._switch(this._activeIndex-1);},next:function(){this._switch(this._activeIndex+1);},_init:function(){this._struct();}};function kExtend(selector,opts,subClass){var elems=$(selector),reFun=[];if(elems.length==1){reFun=new subClass(elems,opts);}else{for(var i=0;i<elems.length;i++){reFun.push(new subClass(elems.eq(i),opts));}}
return reFun;}
function Slide(selector,opts){var slideOpts={event:"hover",effect:"fade",autoPlay:true}
$.extend(slideOpts,slideOpts,opts);Switchable.call(this,selector,slideOpts);this._init();}
Slide.prototype=new Switchable();Slide.prototype.constructor=Slide;k.slide=function(selector,opts){return kExtend(selector,opts,Slide);};function wideSlide(selector,opts){var slideOpts={event:"hover",effect:"fade",autoPlay:true}
$.extend(slideOpts,slideOpts,opts);Switchable.call(this,selector,slideOpts);var windowWidth=$(window).width(),slideWrap=$(selector),selectorWidth=$(selector).width();if(selectorWidth>windowWidth)
{slideWrap.css('left',-(selectorWidth-windowWidth)/2+"px");}
$(window).resize(function(event){if(selectorWidth>$(window).width())
{slideWrap.css('left',-(selectorWidth-$(window).width())/2+"px");}});this._init();}
wideSlide.prototype=new Switchable();wideSlide.prototype.constructor=wideSlide;k.wideSlide=function(selector,opts){return kExtend(selector,opts,wideSlide);};function Tab(selector,opts){Switchable.call(this,selector,opts);this._init();}
Tab.prototype=new Switchable();Tab.prototype.constructor=Tab;k.tab=function(selector,opts){return kExtend(selector,opts,Tab);};k.calendar=function(selector,opts){var calendarOpts={event:"click",fromSunday:false},day=new Date().getDay(),dayNum=day,Calendar=[];$.extend(calendarOpts,calendarOpts,opts);if(!calendarOpts.fromSunday){dayNum=day==0?6:day-1;}
Calendar=kExtend(selector,opts,Tab);if($(selector).length==1){Calendar.switchTo(dayNum);}else{for(var i=0;i<elems.length;i++){Calendar[i].switchTo(dayNum);}}};function Carousel(selector,opts){Switchable.call(this,selector,opts);this._init();}
Carousel.prototype=new Switchable();Carousel.prototype.constructor=Carousel;k.imageScroll=function(selector,opts){var caroOpts={effect:"scrollX",autoPlay:true,mousewheel:true,figureNav:true}
$.extend(caroOpts,caroOpts,opts);return kExtend(selector,caroOpts,Carousel);};k.textScroll=function(selector,opts){var listLength=$(selector).find('li').length;if(listLength<=1)
{return;}
var caroOpts={effect:"scrollY",autoPlay:true}
$.extend(caroOpts,caroOpts,opts);return kExtend(selector,caroOpts,Carousel);};})(jQuery,LEGO,window);


var wilson=wilson?wilson:{};wilson.popupBox=function(selector,options){var el=jQuery(selector);if(!el.length&&selector!=="none")return;if(!options||!options.boxSelector)return;el.each(function(event){$(this).get(0).onclick=popFun;});function popFun(){var box=jQuery(options.boxSelector)[0];if(!box)return;var _selfpopup=this;$("body").get(0).onkeyup=function(event){var evt=event?event:window.event;if(evt.keyCode==27)
{_selfpopup.popu_close();}
return false;}
var _default={existMask:true};var opts=jQuery.extend({},_default,options);this.open=function(maskCss,boxCss){this.box.style.cssText=boxCss;this.mask.style.cssText=maskCss;if(!window.XMLHttpRequest){document.documentElement.scrollTop++;document.documentElement.scrollTop--;}
if(opts.existMask)document.body.appendChild(this.mask);},this.popu_close=function(){this.box.style.cssText="";this.box.style.display="none";if(opts.existMask)document.body.removeChild(this.mask);}
this.box=box;this.mask=document.createElement("div");this.mask.className="J_maskLayer";var maskCode='';maskCode+="<iframe style=\"position:absolute;top:0;left:0;z-index:-1;width:1920px;height:1080px;filter:mask();\" src=\"javascript:void(0)\"><\/iframe>";if('undefined'==typeof(document.body.style.maxHeight)){this.mask.innerHTML=maskCode;}
this.box.style.display="block";var boxWidth=this.box.clientWidth,boxHeight=this.box.clientHeight;var maskCss="position:fixed;left:0;top:0;z-index:32766;width:100%;height:100%;filter:alpha(opacity=70);-moz-opacity:0.7;opacity:0.7;background:#000;overflow:hidden;",boxCss="display:block;position:fixed;left:50%;top:50%;z-index:32767;margin:-"+boxHeight/2+"px 0 0 -"+boxWidth/2+"px;";if(!window.XMLHttpRequest){maskCss+="position:absolute;top:expression(parent.document.documentElement.scrollTop);height:expression(parent.document.documentElement.clientHeight);";boxCss+="position:absolute;top:expression(documentElement.scrollTop + document.documentElement.clientHeight/2);";if(document.getElementsByTagName("html")[0].style.backgroundImage==""){document.getElementsByTagName("html")[0].style.backgroundImage="url(blank)";}}
this.open(maskCss,boxCss);var tags=this.box.getElementsByTagName("*");for(var i=0;i<tags.length;i++){if(jQuery(tags[i]).hasClass("J_btnClose")){var self=this;tags[i].onclick=function(){self.popu_close();return false;}}}
return false;}
if(selector==="none")popFun();};


(function(a){if(typeof define==="function"&&define.amd){define(["jquery"],a)}else{if(typeof exports==="object"){module.exports=a}else{a(jQuery)}}}(function(e){var d=["wheel","mousewheel","DOMMouseScroll","MozMousePixelScroll"],j=("onwheel" in document||document.documentMode>=9)?["wheel"]:["mousewheel","DomMouseScroll","MozMousePixelScroll"],h=Array.prototype.slice,k,b;if(e.event.fixHooks){for(var c=d.length;c;){e.event.fixHooks[d[--c]]=e.event.mouseHooks}}var a=e.event.special.mousewheel={version:"3.1.9",setup:function(){if(this.addEventListener){for(var m=j.length;m;){this.addEventListener(j[--m],l,false)}}else{this.onmousewheel=l}e.data(this,"mousewheel-line-height",a.getLineHeight(this));e.data(this,"mousewheel-page-height",a.getPageHeight(this))},teardown:function(){if(this.removeEventListener){for(var m=j.length;m;){this.removeEventListener(j[--m],l,false)}}else{this.onmousewheel=null}},getLineHeight:function(i){return parseInt(e(i)["offsetParent" in e.fn?"offsetParent":"parent"]().css("fontSize"),10)},getPageHeight:function(i){return e(i).height()},settings:{adjustOldDeltas:true}};e.fn.extend({mousewheel:function(i){return i?this.bind("mousewheel",i):this.trigger("mousewheel")},unmousewheel:function(i){return this.unbind("mousewheel",i)}});function l(i){var n=i||window.event,r=h.call(arguments,1),s=0,o=0,p=0,q=0;i=e.event.fix(n);i.type="mousewheel";if("detail" in n){p=n.detail*-1}if("wheelDelta" in n){p=n.wheelDelta}if("wheelDeltaY" in n){p=n.wheelDeltaY}if("wheelDeltaX" in n){o=n.wheelDeltaX*-1}if("axis" in n&&n.axis===n.HORIZONTAL_AXIS){o=p*-1;p=0}s=p===0?o:p;if("deltaY" in n){p=n.deltaY*-1;s=p}if("deltaX" in n){o=n.deltaX;if(p===0){s=o*-1}}if(p===0&&o===0){return}if(n.deltaMode===1){var t=e.data(this,"mousewheel-line-height");s*=t;p*=t;o*=t}else{if(n.deltaMode===2){var m=e.data(this,"mousewheel-page-height");s*=m;p*=m;o*=m}}q=Math.max(Math.abs(p),Math.abs(o));if(!b||q<b){b=q;if(f(n,q)){b/=40}}if(f(n,q)){s/=40;o/=40;p/=40}s=Math[s>=1?"floor":"ceil"](s/b);o=Math[o>=1?"floor":"ceil"](o/b);p=Math[p>=1?"floor":"ceil"](p/b);i.deltaX=o;i.deltaY=p;i.deltaFactor=b;i.deltaMode=0;r.unshift(i,s,o,p);if(k){clearTimeout(k)}k=setTimeout(g,200);return(e.event.dispatch||e.event.handle).apply(this,r)}function g(){b=null}function f(m,i){return a.settings.adjustOldDeltas&&m.type==="mousewheel"&&i%120===0}}));