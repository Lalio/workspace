!function() {

	var show = {
        sliderState : function() {

            $('.slider-state').on('click',function() {
                var $this = $(this);
                if($this.hasClass('show-state')){
                    $('.left-col').animate({
                        "left" : "-310px"
                    },700,function() {
                        $this.removeClass('show-state').addClass('hide-state');
                    });
                    $('#main-content').animate({
                        "marginLeft" : "30px"
                    },700);
                }else{
                    $('.left-col').animate({
                        "left" : "0px"
                    },700,function() {
                        $this.removeClass('hide-state').addClass('show-state');
                    });
                    $('#main-content').animate({
                        "marginLeft" : "340px"
                    },700);
                }
            });

        },
        changeTab : function() {

            $(document).on('click','.pay-popbox .tab li',function(){
                var $this = $(this);
                $this.addClass('active').siblings().removeClass('active');
                if($this.hasClass('wechat-pay')){
                    $('.wechat-pay-bd').show();
                    $('.app-pay-bd').hide();
                }else{
                    $('.app-pay-bd').show();
                    $('.wechat-pay-bd').hide();
                }
            });

            $(document).on('click','.print-popbox .tab li',function(){
                var $this = $(this);
                $this.addClass('active').siblings().removeClass('active');
                if($this.hasClass('xz-send')){
                    $('.xz-send-bd').show();
                    $('.me-send-bd').hide();
                }else{
                    $('.me-send-bd').show();
                    $('.xz-send-bd').hide();
                }
            });

        },
        backToTop : function(){

            var $gotop = $('.show-gotop');

            $(window).on('scroll',function(){
                if($(window).scrollTop() > 180){
                    $gotop.addClass('gotop-show');
                }else{
                    $gotop.removeClass('gotop-show');
                }
            });
            
            $gotop.on('click',function(){
                $('html,body').animate({
                    'scrollTop' : '0px'
                },800);
            });

        },
        scrollFixed : function() {

            $(document).on('scroll',function() {
                var scrolltop = $(document).scrollTop();
                if(scrolltop < 80){
                    $('.left-col').css('top',80 - scrolltop+'px');
                }else{
                    $('.left-col').css('top','40px');
                }
            });

        },
        init : function() {
            this.sliderState();
            this.changeTab();
            this.backToTop();
            this.scrollFixed();
		}
	}

    if(typeof show !== "undefined")  window.show = show;

	show.init();
} ();

$('.view').on('click',function(){
	if(!$.cookie('xztoken')){
		wilson.popupBox("none", {boxSelector: ".login-popbox"});
	}else{
		
	}
});

$('.send').on('click',function(){
	if(!$.cookie('xztoken')){
		wilson.popupBox("none", {boxSelector: ".login-popbox"});
	}else{
		wilson.popupBox("none", {boxSelector: "#sendfile-popbox"});
	}
});

$('.download').on('click',function(){
	
	if(!$.cookie('xztoken')){
		wilson.popupBox("none", {boxSelector: ".login-popbox"});
	}else{
		$.ajax({
            url : common.indexUrl + '&do=DownloadFile&from=web&debug=on&msg={"xztoken":"'+$.cookie('xztoken')+'","file_index":"'+$(this).attr('file_index')+'"}',
            type : 'get',
            dataType : 'jsonp',
            success : function(data) {
            	if(data.isOk == false){
            		
            	}else{
            		location.href = data.download_addr;
            	}
            }
        });
	}
});

$('.print').on('click',function(){
	if(!$.cookie('xztoken')){
		wilson.popupBox("none", {boxSelector: ".login-popbox"});
	}else{
		wilson.popupBox("none", {boxSelector: ".pay-popbox"});
	}
});
