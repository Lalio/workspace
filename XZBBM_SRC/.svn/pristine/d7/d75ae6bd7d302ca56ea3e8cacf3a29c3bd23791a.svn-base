
(function (a) {
    a.event.special.textchange = {
        setup: function () {
            a(this).data("lastValue", this.contentEditable === "true" ? a(this).html() : a(this).val());
            a(this).bind("keyup.textchange", a.event.special.textchange.handler);
            a(this).bind("cut.textchange paste.textchange input.textchange", a.event.special.textchange.delayedHandler)
        },
        teardown: function () {
            a(this).unbind(".textchange")
        },
        handler: function () {
            a.event.special.textchange.triggerIfChanged(a(this))
        },
        delayedHandler: function () {
            var b = a(this);
            setTimeout(function () {
                a.event.special.textchange.triggerIfChanged(b)
            },
            25)
        },
        triggerIfChanged: function (b) {
            var c = b[0].contentEditable === "true" ? b.html() : b.val();
            if (c !== b.data("lastValue")) {
                b.trigger("textchange", b.data("lastValue"));
                b.data("lastValue", c)
            }
        }
    };
    a.event.special.hastext = {
        setup: function () {
            a(this).bind("textchange", a.event.special.hastext.handler)
        },
        teardown: function () {
            a(this).unbind("textchange", a.event.special.hastext.handler)
        },
        handler: function (b, c) {
            c === "" && c !== a(this).val() && a(this).trigger("hastext")
        }
    };
    a.event.special.notext = {
        setup: function () {
            a(this).bind("textchange",
            a.event.special.notext.handler)
        },
        teardown: function () {
            a(this).unbind("textchange", a.event.special.notext.handler)
        },
        handler: function (b, c) {
            a(this).val() === "" && a(this).val() !== c && a(this).trigger("notext")
        }
    }
})(jQuery);
  


!function() {

	//搜索数据
	var search_href = window.location.search;
	var xzSearch = decodeURIComponent(search_href.split("=")[1]);
	xzSearch = xzSearch == 'undefined' ? "" : xzSearch;
	var ucode = $('.school-name').attr('ucode') ? $('.school-name').attr('ucode') : 0;
	var ccode = $('.college-name').attr('ccode') ? $('.college-name').attr('ccode') : 0;
	var uname = "";
	var cname = "";
	var order = $('.sort-name').attr('order') ? $('.sort-name').attr('order') : 0;
	var pay = $('.price-name').attr('pay') ? $('.price-name').attr('pay') : 0;

	//滚动加载
	var scrollLoadFlag = true;

	var search = {
		pageNum : 0,   //第几次加载数据
		indexUrl : 'http://112.124.50.239/?action=SuperAPI',
		searchInit : function() {

			$('.search-bd').val(xzSearch);
			search.searchKeyword(ucode,ccode,search.pageNum,10,xzSearch,order,"","",pay);

		},
		searchKeyword : function(ucode,ccode,from,limit,keyword,order,uname,cname,pay) {

			var url = search.indexUrl + '&do=SearchDocument&debug=on&msg={"ucode":'+ucode+',"ccode":'+ccode+',"from":'+from+',"limit":'+limit+',"keyword":"'+keyword+'","order":'+order+',"uname":"'+uname+'","cname":"'+cname+'","pay":'+pay+'}';
			$.ajax({
				url : url,
				type : 'get',
				dataType : 'jsonp',
				success : function(data) {
					var title_html = '',
					    list_html = '',
					    block_html = '',
					    documentItemList = data.documentItemList,
					    data_len = documentItemList.length;

					if(data_len == 0){
						if(from === 0){
							$('.loading').hide();
							$('.search-result').html("找不到该资料");
							return;
						}else{
							$('.loading').hide();
							$('.load-over').show();
							return;
						}
					}

					if(ucode == 0 && ccode == 0 && uname == "" && cname == ""){
						title_html = '学长帮你在<span class="result-position">全站</span>找到<span class="result-num">11</span>份关于<span class="result-name">'+keyword+'</span>的资料';
					}else{
						title_html += '学长帮你在<span class="result-position">'; 
						if(ucode != 0 || uname != ""){
							title_html += $('.school-name').html();
						}
						if(ccode != 0 || cname != ""){
							title_html += '  ' + $('.college-name').html();
						}
						title_html += '</span>找到<span class="result-num">11</span>份关于<span class="result-name">'+keyword+'</span>的资料';
					}
					$('.search-result').html(title_html);

					$('#result-block,#result-list');

					//添加列表式内容
					for(var i = 0;i < data_len;i++){
						var thumbnail = 'http://www.wilsonup.xyz/moren.jpg';
						list_html += '<li>'+
			                            '<div class="thumbnail">'+
			                                '<a href="'+documentItemList[i].web_url+'" target="_blank" class="thumbnail-href">'+
			                                '<img src="'+documentItemList[i].thumbnail+'" alt="" onerror="this.src=\''+thumbnail+'\'">';
			            if(documentItemList[i].today_price == 0){
			            	list_html +='<span class="pass-price free">免费</span>';
			            }else{
			            	list_html +='<span class="pass-price non-free">￥ '+documentItemList[i].today_price+'</span>';
			            }
			            
			            list_html +=        '</a>'+
			                            '</div>'+
			                            '<div class="thumbnail-bd">'+
			                                '<div class="title-wrap">'+
			                                    '<h2 class="passage-title"><a href="'+documentItemList[i].web_url+'" title="'+documentItemList[i].file_name+'" target="_blank">'+documentItemList[i].file_name+'</a></h2>'+
			                                    '<span class="passage-style '+documentItemList[i].file_extension+'"></span>'+
			                                '</div>'+
			                                '<div class="passage-source">'+
			                                    '<p>资料来源：</p>'+
			                                    '<p class="school-ico"></p>'+
			                                    '<p class="form-school">'+documentItemList[i].uname+'-'+documentItemList[i].cname+'</p>'+
			                                    '<p class="parting-line">|</p>'+
			                                    '<p>上传者：</p>'+
			                                    '<p class="form-user">' + documentItemList[i].user_name + '</p>'+
			                                    '<p class="parting-line">|</p>'+
			                                    '<p>上传日期：</p>'+
			                                    '<p class="form-date">'+search.transformDate(documentItemList[i].file_time)+'</p>'+
			                                '</div>'+
			                                '<div class="passage-info">'+
			                                    '<p class="passage-intro">'+documentItemList[i].file_info+'</p>'+
			                                '</div>'+
			                                '<ul class="passage-detail">'+
			                                    '<li>'+
			                                        '<span class="view-ico"></span>'+
			                                        '<span> 浏览 : </span>'+
			                                        '<span class="view-num">'+documentItemList[i].file_views+'</span>'+
			                                    '</li>'+
			                                    '<li>'+
			                                        '<span class="download-ico"></span>'+
			                                        '<span>下载 : </span>'+
			                                        '<span class="view-num">'+documentItemList[i].file_downs+'</span>'+
			                                    '</li>'+
			                                    '<li>'+
			                                        '<span class="reputation-ico"></span>'+
			                                        '<span> 好评率 : </span>'+
			                                        '<span class="star">'+
			                                            search.transformstar(documentItemList[i].good_count) +
			                                        '</span>'+
			                                    '</li>'+
			                                '</ul>'+
			                            '</div>'+
			                        '</li>';
					}
					
					$('.loading').hide();
					$('#result-list').append(list_html);
						
					//添加列表式内容
					for(var i = 0;i < data_len;i++){
						block_html += '<li>'+
				                            '<div class="thumbnail">'+
				                               	'<a class="thumbnail-a" href="'+documentItemList[i].web_url+'" target="_blank">'+
				                               	'<img src="'+documentItemList[i].thumbnail+'" alt="" onerror="this.src=\''+thumbnail+'\'">';
				        if(documentItemList[i].today_price == 0){
			            	block_html +='<span class="pass-price free">免费</span>';
			            }else{
			            	block_html +='<span class="pass-price non-free">￥ '+documentItemList[i].today_price+'</span>';
			            }
					     block_html +=              '<div class="thumbnail-mask">'+
					                                    '<p>'+documentItemList[i].file_info+'</p>'+
					                                '</div>'+
				                               	'</a>'+ 
				                            '</div>'+
				                            '<div class="thumbnail-bd">'+
				                                '<h2 class="passage-title"><a href="'+documentItemList[i].web_url+'" title="'+documentItemList[i].file_name+'" target="_blank">'+documentItemList[i].file_name+'</a></h2>'+
				                                '<div class="passage-source">'+
				                                    '<p class="school-ico"></p>'+
				                                    '<p class="form-school">'+documentItemList[i].uname+'-'+documentItemList[i].cname+'</p>'+
				                                    '<p class="upload-date">上传日期：</p>'+
				                                    '<p class="form-date">'+search.transformDate(documentItemList[i].file_time)+'</p>'+
				                                    '<p class="passage-info">浏览 <span>'+documentItemList[i].file_views+'</span> / 下载 <span>'+documentItemList[i].file_downs+'</span> / 评论 <span>'+documentItemList[i].comment_count+'</span></p>'+
				                                    '<p class="reputation">'+
				                                        '<span> 好评率 : </span>'+
				                                        '<span class="star">'+
				                                            search.transformstar(documentItemList[i].good_count) +
				                                        '</span>'+
				                                    '</p>'+
				                                '</div>'+
				                                '<div class="uploader">'+
				                                    '<span class="uploader-voice"></span>'+
				                                    '<span class="uploader-img"></span>'+
				                                    '<span class="uploader-name">' + documentItemList[i].user_name + '</span>'+
				                                '</div>'+
				                            '</div>'+
				                        '</li>';
					}
					$('#result-block').append(block_html);					
					//滚动加载完成，可继续下次加载
					scrollLoadFlag = true;
					//数据加载完成
 					if(data_len < 10){
 						$('.load-over').show();
 						$('.loading').hide();
 					}
				},
				error : function() {}
			});

		},
		changeStyle : function(){

			$('.style-list').on('click',function(){
				if(!$(this).hasClass('active')){
					$(this).addClass('active').siblings().removeClass('active');
					$('.style-change').addClass('list-active').removeClass('block-active');
					$('.result-block').hide();
					$('.result-list').show();
				}
			});

			$('.style-block').on('click',function(){
				if(!$(this).hasClass('active')){
					$(this).addClass('active').siblings().removeClass('active');
					$('.style-change').removeClass('list-active').addClass('block-active');
					$('.result-list').hide();
					$('.result-block').show();
				}
			});

		},
		inputName : function(){

			var dataFlag;        		 //选中哪种选项
			var dataPrice = 0;  		 //存储选中的价格
			var dataSort = 0;            //存储选中的排序
			var isFocus = false;
			var isCollege = false;       //学院是否可以选择

			//显示下拉层
			$('.search-name,.arrow').on('click',function() {

				dataFlag = $(this).parent().attr('data-flag');

				//未选择学校，禁学院
				if(dataFlag == 'college'){
					if(!isCollege){
						return;
					}else{
						$('.college-name').removeClass('college-name-not').addClass('college-name-allow');
					}
				}

				$('.'+dataFlag+'-auto').show();	
				$('.input-mask').show();
				$('.'+dataFlag+'-wrap .arrow').removeClass('arrow-down').addClass('arrow-up');

				//清空输入框的内容
				$('.input-text').val('').focus();

				if(dataFlag == 'school' || dataFlag == 'college'){
					$('#school-search-list,#college-search-list').html('');
				}
				//显示学校提示
				$('.school-auto .input-tips').show();
				$('.input-none').hide();

				//选择了学校就列出学院
				if(dataFlag == 'college'){
					if(ucode != 0){
						$('.college-input-wrap .ajax-loading').show();
						$.ajax({
							url : search.indexUrl + '&do=WebGetCollege&debug=on&msg={"ucode":' + ucode + ',"cname":""}',
							type : 'get',
							dataType : 'jsonp',
							success : function(data) {

								var html = "",
								    college_len = data.length;

								$('#college-search-list').html('');
								$('.college-input-wrap .ajax-loading').hide();
								
								if(college_len == 0){
									$('.college-auto .input-none').show();
									return;
								}

								$('.college-auto .input-none').hide();
								for(var i = 0;i < college_len;i++){
									html += '<li collegeid="' + data[i].college_id + '">' + data[i].college + '</li>';
								}
								$('#college-search-list').html(html);

							},
							error : function() {}
						});
						$('.college-auto .input-tips').hide();
					}else{
						$('.college-auto .input-tips').show();
					}
				}

			});

			//选定内容
			$('.search-con').on('click','.choose-auto ul li',function() {
				
				var thisHtml = $(this).html();
				$('.'+dataFlag+'-wrap .search-name ').html(thisHtml);
				$('.'+dataFlag+'-auto').hide();	
				$('.input-mask').hide();
				$('.load-over').hide();
				$('.'+dataFlag+'-wrap .arrow').removeClass('arrow-up').addClass('arrow-down');
				
				var $dataFlagSelect = $('.'+dataFlag+'-bwrap .close-search-name');
				if(thisHtml === $('.'+dataFlag+'-wrap').attr('data-default')){
					$dataFlagSelect.hide();
				}else{
					$dataFlagSelect.show();
				}
				switch(dataFlag){
					case "sort" :
						var orderIndex = $(this).index();
						if(orderIndex === dataSort) return;
						order = orderIndex;
						dataSort = orderIndex;

						//初始化内容
						$('.loading').show();
						$('#result-list,#result-block').html('');
						search.pageNum = 0;

						search.searchKeyword(ucode,ccode,search.pageNum,10,xzSearch,order,uname,cname,pay);					
						break; 
					case "price" :
						var priceIndex = $(this).index();
						if(priceIndex === dataPrice) return;
						pay = priceIndex;
						dataPrice = priceIndex;

						//初始化内容
						$('.loading').show();
						$('#result-list,#result-block').html('');
						search.pageNum = 0;

						search.searchKeyword(ucode,ccode,search.pageNum,10,xzSearch,order,uname,cname,pay);					
						break; 
					case "school" :
						ucode = $(this).attr("universityid");
						
						//初始化内容
						$('.loading').show();
						$('#result-list,#result-block').html('');
						search.pageNum = 0;
						//选择了学校，学院解禁
						isCollege = true;
						$('.college-name').removeClass('college-name-not').addClass('college-name-allow');						
						$('.college-wrap .arrow').removeClass('arrow-not').addClass('arrow-down');
						
						search.searchKeyword(ucode,ccode,search.pageNum,10,xzSearch,order,uname,cname,pay);	
						break;
					case "college" :
						ccode = $(this).attr("collegeid");
						
						//初始化内容
						$('.loading').show();
						$('#result-list,#result-block').html('');
						search.pageNum = 0;
						
						search.searchKeyword(ucode,ccode,search.pageNum,10,xzSearch,order,uname,cname,pay);	
						break;
				}

			});

			//焦点在输入框内才可以回车搜索
			$('.search-con').on('focus','.input-text',function() {
				isFocus = true;
			});
			$('.search-con').on('blur','.input-text',function() {
				isFocus = false;
			});

			$(document).on('keyup',function(e) {

				if(!isFocus) return;
				var event = e ? e : window.event;

				if(event.keyCode == 13){
					if(dataFlag === 'school'){
						uname = $.trim($('.school-input-wrap .input-text').val());
						if(uname != ''){
							$('.school-name').html(uname);
							$('.school-bwrap .close-search-name').show();
							$('.school-wrap .arrow').removeClass('arrow-up').addClass('arrow-down');
							//选择了学校，学院解禁
							isCollege = true;
							$('.college-name').removeClass('college-name-not').addClass('college-name-allow');						
							$('.college-wrap .arrow').removeClass('arrow-not').addClass('arrow-down');
						}else{
							return;
						}
					}
					if(dataFlag === 'college'){
						cname = $.trim($('.college-input-wrap .input-text').val());
						if(cname != ''){
							$('.college-name').html(cname);
							$('.college-bwrap .close-search-name').show();
							$('.college-wrap .arrow').removeClass('arrow-up').addClass('arrow-down');
						}else{
							return;
						}
					}
					
					//初始化内容
					$('.loading').show();
					$('#result-list,#result-block').html('');
					search.pageNum = 0;


					$('.choose-auto').hide();
					$('.input-mask').hide();

					search.searchKeyword(ucode,ccode,search.pageNum,10,xzSearch,order,uname,cname,pay);
				}


			});

			//监听输入框
			var school_name = '';
			var college_name = '';
			//监听学校框
			$('.search-con .school-input').on('input propertychange',function() {
				
				$('.school-auto .input-tips').hide();
				$('.school-input-wrap .ajax-loading').show();
				school_name = $.trim($(this).val());
				
				if(school_name == ''){
					$('.school-input-wrap .ajax-loading').hide();
					$('.school-auto .input-none').show();
					return;
				}
				
				$.ajax({
					url : search.indexUrl + '&do=WebGetUniversity&debug=on&msg={"uname":"'+school_name+'"}',
					type : 'get',
					dataType : 'jsonp',
					success : function(data) {
						var html = "",
						    school_len = data.length;

						$('#school-search-list').html('');
						$('.school-input-wrap .ajax-loading').hide();
						if(school_len == 0){
							$('.school-auto .input-none').show();
							return;
						}
		
						$('.school-auto .input-none').hide();
						for(var i = 0;i < school_len;i++){
							html += '<li universityid="' + data[i].university_id + '">' + data[i].name + '</li>';
						}
						$('#school-search-list').html(html);

					},
					error : function() {}
				})

			});
			//监听学院框
			$('.search-con .college-input').on('input propertychange',function() {

				$('.college-auto .input-tips').hide();
				$('.college-input-wrap .ajax-loading').show();

				college_name = $.trim($(this).val());
				
				if(college_name == ''){
					$('.college-input-wrap .ajax-loading').hide();
					return;
				}

				$.ajax({
					url : search.indexUrl + '&do=WebGetCollege&debug=on&msg={"ucode":' + ucode + ',"cname":"' + college_name + '"}',
					type : 'get',
					dataType : 'jsonp',
					success : function(data) {

						var html = "",
						    college_len = data.length;

						$('#college-search-list').html('');
						$('.college-input-wrap .ajax-loading').hide();
						
						if(college_len == 0){
							$('.college-auto .input-none').show();
							return;
						}

						$('.college-auto .input-none').hide();
						for(var i = 0;i < college_len;i++){
							html += '<li collegeid="' + data[i].college_id + '">' + data[i].college + '</li>';
						}
						$('#college-search-list').html(html);

					},
					error : function() {}
				})


			});
				
			

			//删除选定的选项
			$('.search-con').on('click','.close-search-name',function() {

				var $nextSelect = $(this).next(),
				    defaultValue = $nextSelect.attr('data-default'),
				    delectflag = $nextSelect.attr('data-flag');
				$nextSelect.find('.search-name').html(defaultValue);
				$(this).hide();
				$('.load-over').hide();

				switch(delectflag){
					case "sort":
						order = 0;

						//初始化内容
						$('.loading').show();
						$('#result-list,#result-block').html('');
						search.pageNum = 0;
						search.searchKeyword(ucode,ccode,search.pageNum,10,xzSearch,order,uname,cname,pay);
						break;
					case "price":
						pay = 0;

						//初始化内容
						$('.loading').show();
						$('#result-list,#result-block').html('');
						search.pageNum = 0;
						search.searchKeyword(ucode,ccode,search.pageNum,10,xzSearch,order,uname,cname,pay);
						break;
					case "school":
						ucode = 0;
						ccode = 0;
						uname = "";
						cname = "";

						//禁止选择学院
						isCollege = false;
						$('.college-name').removeClass('college-name-allow').addClass('college-name-not').html('学院');
						$('.college-wrap .arrow').removeClass('arrow-down arrow-up').addClass('arrow-not');
						$('.college-bwrap .close-search-name').hide();

						//初始化内容
						$('.loading').show();
						$('#result-list,#result-block').html('');
						search.pageNum = 0;
						search.searchKeyword(ucode,ccode,search.pageNum,10,xzSearch,order,uname,cname,pay);
						break;
					case "college":
						ccode = 0;
						cname = "";

						//初始化内容
						$('.loading').show();
						$('#result-list,#result-block').html('');
						search.pageNum = 0;
						search.searchKeyword(ucode,ccode,search.pageNum,10,xzSearch,order,uname,cname,pay);
						break;
				}
				
			});

			//焦点离开，隐藏下拉层
			$('.input-mask').on('click',function() {

				$(this).hide();
				$('.choose-auto').hide();
				$('.'+dataFlag+'-wrap .arrow').removeClass('arrow-up').addClass('arrow-down');

			});

		},	
		transformDate : function(time) {

			var t = Number(time) * 1000;
			var date = new Date(t),
			    year = date.getFullYear(),
			    month = date.getMonth() + 1,
			    day = date.getDay(),
			    str = year + '/' + month + '/' +day;
				return 	str;

		},
		transformstar : function(star) {

			var star_html = '',
			    star = Number(star);

			if(star > 0 && star <= 0.5){
				star_html = '<i class="half-star"></i>';
				return star_html;
			}
			if(star > 0.5 && star <= 1){
				star_html = '<i class="all-star"></i>';
				return star_html;
			}
			if(star > 1 && star <= 1.5){
				star_html = '<i class="all-star"></i><i class="half-star"></i>';
				return star_html;
			}
			if(star > 1.5 && star <= 2){
				star_html = '<i class="all-star"></i><i class="all-star"></i>';
				return star_html;
			}
			if(star > 2 && star <= 2.5){
				star_html = '<i class="all-star"></i><i class="all-star"></i><i class="half-star"></i>';
				return star_html;
			}
			if(star > 2.5 && star <= 3){
				star_html = '<i class="all-star"></i><i class="all-star"></i><i class="all-star"></i>';
				return star_html;
			}
			if(star > 3 && star <= 3.5){
				star_html = '<i class="all-star"></i><i class="all-star"></i><i class="all-star"></i><i class="half-star"></i>';
				return star_html;
			}
			if(star > 3.5 && star <= 4){
				star_html = '<i class="all-star"></i><i class="all-star"></i><i class="all-star"></i><i class="all-star"></i>';
				return star_html;
			}
			if(star > 4 && star <= 4.5){
				star_html = '<i class="all-star"></i><i class="all-star"></i><i class="all-star"></i><i class="all-star"></i><i class="half-star"></i>';
				return star_html;
			}
			if(star > 4.5 && star <= 5){
				star_html = '<i class="all-star"></i><i class="all-star"></i><i class="all-star"></i><i class="all-star"></i><i class="all-star"></i>';
				return star_html;
			}
			return '';

		},
		scrollLoadData : function() {

			$(window).scroll(function() {
				var documentHeight = $(document).height(),
				    scrollTop = $(window).scrollTop(),
				    windowHeight = $(window).height();
				if(documentHeight - scrollTop <= windowHeight && scrollLoadFlag){
					$('.loading').show();
					scrollLoadFlag = false;
					search.pageNum++;
					search.searchKeyword(ucode,ccode,search.pageNum * 10,10,xzSearch,order,uname,cname,pay);
				}
			});

		},
		backToTop : function(){

			var $gotop = $('.gotop');

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
		loginControl : function() {

            $('.user-login').on('click',function() {
                $('#login-popbox .form-input').val('');
                $('#login-error-tips').html('');
                wilson.popupBox("none", {boxSelector: ".login-popbox"});
            });

            $('.forget-pass,#findback-form-submit').on('click',function() {
                $('.J_maskLayer,.popbox').hide();
                wilson.popupBox("none", {boxSelector: ".sendemail-popbox"});
            });

            $('.toregister').on('click',function() {
                $('#register-popbox .password,#register-form-submit').show();
                $('#findback-form-submit,.finback-tips').hide();
                $('#register-popbox .form-input').val('');
                $('#register-error-tips').html('');
                $('.J_maskLayer,.popbox').hide();
                wilson.popupBox("none", {boxSelector: ".register-popbox"});
            });

        },
        registerControl : function() {

            $('.user-register').on('click',function() {
                $('#register-popbox .password,#register-form-submit').show();
                $('#findback-form-submit,.finback-tips').hide();
                $('#register-popbox .form-input').val('');
                $('#register-error-tips').html('');
                wilson.popupBox("none", {boxSelector: ".register-popbox"});
            });

        },
		getLoginCookie : function() {

            var email = $.cookie('email');
            if(email){
                $('.user-btn').hide(); 
                $('.hd-control .user-name').html(email);
                $('.hd-control .user-name,.hd-control .logout').show();
            }

        },
        register : function() {

            var isTureEmail = false;

            $('#register-popbox .email').on('blur',function() {

                var username = $.trim($('#register-popbox .email').val());
                if(username === ''){
                    $('#register-error-tips').html('邮箱不能为空！');
                    isTureEmail = false;
                    return;
                }
                if(!/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i.test(username)){
                    $('#register-error-tips').html('邮箱格式错误！');
                    isTureEmail = false;
                    return;
                }else{
                    $('.email-loading').show();
                    
                    $.ajax({
                        url : search.indexUrl + '&do=WebTestEmail&debug=on&msg={"email":"' + username + '"}',
                        type : 'get',
                        dataType : 'jsonp',
                        success : function(data) {
                            
                            $('.email-loading').hide();

                            if(data.rcode === 0){
                                isTureEmail = true;
                                $('#register-error-tips').html('');
                                return;
                            }

                            if(!data.isOk){
                                isTureEmail = false;
                                if(data.error == '亲，你的邮箱号码已注册！'){
                                    $('#register-popbox .password,#register-form-submit').hide();
                                    $('#findback-form-submit,.finback-tips').show();
                                }else{
                                    $('#register-error-tips').html(data.error);
                                }
                            }

                        }
                    });
                }

            });

            $('#register-form-submit').on('click',function(e) {
                
                e.preventDefault();
                var username = $.trim($('#register-popbox .email').val());
                var password = $.trim($('#register-popbox .password').val());
                
                if(username === '' || password === ''){
                    $('#register-error-tips').html('邮箱或者密码不能为空！');
                    return;
                }

                if(isTureEmail){
                    $('.submit-loading').show();
                    $.ajax({
                        url : search.indexUrl + '&do=WebRegister&debug=on&msg={"email":"'+username+'","password":"'+password+'"}',
                        type : 'get',
                        dataType : 'jsonp',
                        success : function(data) {
                            
                            $('.submit-loading').hide();
                            $('.J_maskLayer,#register-popbox').hide();

                            wilson.popupBox("none", {boxSelector: "#success-popbox"});
                            $('#success-popbox .success-email').html(username);
                        }
                    });
                }
            });

        },
        login : function() {

            $('#login-form-submit').on('click',function(e) {
                
                e.preventDefault();
                var username = $.trim($('#login-popbox .email').val());
                var password = $.trim($('#login-popbox .password').val());
                
                if(username === '' || password === ''){
                    $('#login-error-tips').html('邮箱或者密码不能为空！');
                    return;
                }

                
                $('.submit-loading').show();
                $.ajax({
                    url : search.indexUrl + '&do=WebLogin&debug=on&msg={"email":"'+username+'","password":"'+password+'"}',
                    type : 'get',
                    dataType : 'jsonp',
                    success : function(data) {
                        $('.submit-loading').hide();

                        if(data.error && data.error == '输入信息有误'){
                            $('#login-error-tips').html('用户名或者密码错误');
                            return;
                        }
                                                
                        $('.J_maskLayer,#login-popbox,.user-btn').hide(); 
                        $('.hd-control .user-name').html(data.user.email);
                        $('.hd-control .user-name,.hd-control .logout').show();

                        $.cookie('email',data.user.email,{
                            path : '/',
                            expires : 7
                        });
                        $.cookie('xztoken',data.xztoken,{
                            path : '/',
                            expires : 7
                        });
                        $.cookie('user_icon',data.user.user_icon,{
                            path : '/',
                            expires : 7
                        });
                    }
                });
            });

        },
        logout : function() {

            $('.logout').on('click',function() {
                $.cookie('email', null);
                $.cookie('xztoken', null);
                $.cookie('ccode', null);
                $.cookie('ucode', null);
                $.cookie('findback_email', null);
                $.cookie('user_icon', null);
                $.cookie('reset_token', null);
                location.reload();
            });

        },
        emailTimer : null,
        sendEmail : function() {

            $('#sendemail-form-submit').on('click',function() {
                clearInterval(search.emailTimer);
                var value =$.trim($('#sendemail').val());
                if(value === ''){
                    $('#sendmail-error-tips').html('邮箱不能为空！'); 
                    return;
                }
                if(!/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i.test(value)){
                   $('#sendmail-error-tips').html('邮箱格式错误！'); 
                    return;
                }

                $.cookie('findback_email',value);
                search.sendEmailAjax(value);
                
            });

        },
        sendEmailAjax : function(value) {

            $('.submit-loading').show();
            var isSend = false;
            $.ajax({
                url : search.indexUrl + '&do=WebChangePassword&debug=on&msg={"temp_ip":"http://192.168.1.118:8000/index.html","email":"'+value+'"}',
                type : 'get',
                dataType : 'jsonp',
                success : function(data) {
                    $('.submit-loading').hide();
                                            
                    $('.J_maskLayer,#sendemail-popbox').hide(); 
                    wilson.popupBox("none", {boxSelector: ".sendemail-success-popbox"});
                    $('.success-email').html(value);

                    $('.countdown').off('click',function() {
                        search.sendEmailAjax(value);
                    });

                    isSend = false;
                    $('#resend').addClass('nonsend');

                    var countdown = 60;
                    search.emailTimer = setInterval(function() {
                        if(countdown == 0) {
                            clearInterval(search.emailTimer);
                            isSend = true;
                            $('#resend').removeClass('nonsend');
                            $('#resend').on('click',function() {
                                if(isSend){
                                    search.sendEmailAjax(value);
                                    isSend = false;
                                }
                            });
                        }
                        $('.countdown').html(countdown--);
                    },1000);
                }
            });

        },
   		init : function() {
			this.searchInit();
			this.changeStyle();
			this.inputName();
			this.scrollLoadData();
			this.backToTop();	
			this.loginControl();
			this.registerControl();	
			this.getLoginCookie();
            this.register();
            this.login();
            this.logout();
            this.sendEmail();
		}
	}

    if(typeof search !== "undefined")  window.search = search;

	search.init();
} ();



