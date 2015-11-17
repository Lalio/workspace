!function() {

	var superHelp = {
		cache : {
			
		},
		initSlider : function() {
			new Swiper(".swiper-container", {
				mode: "vertical",
				mousewheelControl:true,
				touchRatio : 1.5,
				onSlideChangeEnd: function(swiper) {
					
					$('#page-num').find('.current-num').html(swiper.activeIndex + 1);
					$('.swiper-slide').removeClass('active');
					// $('#swiper-container').removeClass('active-ico');
					var $index = $(swiper.activeSlide());

					$index.addClass('active');
					// setTimeout(function() {
					// 	$('#swiper-container').addClass('active-ico');
					// },10);
					

				}
			})
		},
		imgInit : function(){
			var loadImage = function(path, callback){
	            var img = new Image();
	            img.onload = function(){
	                img.onload = null;
	                callback(path);
	            }
	            img.src = path;
	        }

			var imgLoader = function(imgs, callback){
	            var len = imgs.length, i = 0;
	            while(imgs.length){
	                loadImage(imgs.shift(), function(path){
	                    callback(path, ++i, len);
	                });
	            }
        	}

        	//图片加载路径
        	var img_path = 'http://www.xzbbm.cn/images/newmedia/';
        	var img_list = ['loading.gif','bg.jpg','sprite.png','logo-text.png','dialog-bg.png','close.png','send.png'];
        	for(var i = 0;i < img_list.length;i++){
        		img_list[i] = img_path + img_list[i];
        	}

        	imgLoader(img_list, function(path, curNum, total){
	            var percent = curNum/total;
	            percent = Math.round(percent * 100)/100;
	  
	            $('.loading-txt').html(Math.ceil(percent * 100) + '%');
	            if(percent == 1){
	                setTimeout(function() {

	                	//loading页隐藏
		               $('.loading').addClass('hide');
		               //初始化首屏动画
		               $('#swiper-container').addClass('active-ico');


	                },500);
	            }
	        });
		},
		musicAutoPlay : function(){
			var $audio = $('#audio')[0];

			if($audio.paused){
				$(document).one('touchstart',function(){
					$audio.play();
					$('.music').removeClass('musicoff').addClass('musicon');
				});
			}
							
			$('.music').on('touchstart',function(){
				if($audio.paused){
					$audio.play();
					$('.music').removeClass('musicoff').addClass('musicon');
				}else{
					$audio.pause();
					$('.music').removeClass('musicon').addClass('musicoff');
				}
			});
		},
		openDialog : function() {

			$dialog = $('.dialog');
			$mask = $('#mask');
			$J_download = $('#J_download');

			//检查手机版本，添加app下载链接
			var browserVersion = navigator.userAgent;
			if(browserVersion.indexOf('Android') > -1){
				$J_download.attr('href',"#Android");
			}
			if(browserVersion.indexOf('iPhone') > -1 || browserVersion.indexOf('iPad') > -1){
				$J_download.attr('href',"#apple");
			}

			$('.download-ico').on('touchstart',function() {

				$dialog.show();
				$mask.show();

			});
			$('.J_close').on('touchstart',function() {

				$dialog.hide();
				$mask.hide();

			});

		},
		checkEmail : function() {

			$('#J_send').on('touchstart',function() {
				var emailVal = $.trim($('#email-input').val());
				if(emailVal === "" || !/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/.test(emailVal)){
					console.log('fail');
				}else{
					console.log('success')
				}
			});

		},	
		init : function() {
			this.initSlider();
			this.imgInit();			
			this.musicAutoPlay();
			this.openDialog();
			this.checkEmail();
		}
	}

    if(typeof superHelp !== "undefined")  window.superHelp = superHelp;

	superHelp.init();
} ();
