<?php
if ( !defined( 'IN_SYS' ) ) {
	header( "HTTP/1.1 404 Not Found" );
	die;
}
?>
<?php include Template('header');?>
<title><?= $this->pageData['ucode'] >0 && !strstr($this->pageData['file_name'],$this->GetUniversityInfo($this->pageData['ucode'],'','name'))?' '.$this->GetUniversityInfo($this->pageData['ucode'],'','name'):''?><?= $this->pageData['file_name']?> - 学长帮帮忙 - XZBBM.CN</title>
</head>
<body>
<script type="text/javascript" src="http://<?= DOMAIN?>/js/swfobject.js"></script>
<div class="jiaodian"></div>
<div class="main">
  <div class="ziliao_left">
       <div class="wenzhang_title">
          <h2><img width="40px" height="100%" onerror="this.src='http://<?= DOMAIN?>/images/chm_b.jpg'" src="http://<?= DOMAIN?>/images/<?= $this->pageData['file_extension']?>_b.jpg" />  <?= syssubstr($this->pageData['file_name'], 30)?></h2>
          <span>
          <?php if((strtolower($this->pageData['file_extension']) == 'zip') && ($this->pageData['has_png'] != '0')){?>
          	<strong>随手拍</strong>&nbsp;
          <?php }else{?>
          	大小：<strong><?= $this->filesize?>MB</strong>&nbsp;
          <?php }?>	
			归属：<?= $this->ShowUicon($this->pageData['ucode']);?>  <?= $this->GetUniversityInfo($this->pageData['ucode'],'','name')?>&nbsp;
          	<!-- 校验码：<strong><?= strtoupper($this->pageData['file_md5'])?></strong> -->
          	曝光：<strong><?= $this->baoguang?></strong>
          	分享：<strong><?= $this->fenxiang?></strong>
          	传播率：<strong><?= $this->chuanbo*100?>%</strong>
          	<?php if(isset($_REQUEST['benifit'])){?>
          	收益：<strong><a href="http://<?= DOMAIN?>/about/profile.html" target="_blank">￥<?= $this->shouyi?></a></strong>
          	<?php }?>
          	<strong><a href="http://<?= DOMAIN?>/view/<?= $this->pageData['file_index']?>?from=m" target="_blank">[大图]</a></strong>
          	<!-- <strong><a href="javascript:;" id="cloudprint" file_id="<?= $this->pageData['file_id']?>">[云印]</a></strong> -->
          </span>
       </div>
       <div class="wenzhang_nr" style="<?= $this->pageData['has_swf']>0?'min-height:840px;*+height:100%;_height:840px;':''?>">
       <?php if(false === is_crawler() && !isset($this->pageData['attention'])){?>
       <script type="text/javascript"> 
            var swfVersionStr = "10.0.0";
            var xiSwfUrlStr = "http://<?= DOMAIN?>/view/playerProductInstall.swf";

            var flashvars = { 
            	  SwfFile : escape("http://<?= DOMAIN?>/GetSwf/<?= TIMESTAMP?>/<?= sha1(TIMESTAMP.'sNsxCrth13LGsu60')?>/<?= $this->pageData['file_index']?>"),
    			  Scale : 0.6, 
				  ZoomTransition : "easeOut",
				  ZoomTime : 0.5,
  				  ZoomInterval : 0.2,
  				  FitPageOnLoad : true,
  				  FitWidthOnLoad : true,
  				  PrintEnabled : true,
  				  FullScreenAsMaxWindow : false,
  				  ProgressiveLoading : true,
  				  PrintToolsVisible : false,
  				  ViewModeToolsVisible : true,
    			  SearchMatchAll : true,
  				  ZoomToolsVisible : true,
  				  FullScreenVisible : false,
  				  NavToolsVisible : true,
  				  CursorToolsVisible : true,
  				  SearchToolsVisible : true,
    			  InitViewMode : 'Portrait',
  				  RenderingOrder : 'flash',
  				  MinZoomSize : 0.2,
				  MaxZoomSize : 5,
				  };
				  
			var params = {};
            params.quality = "high";
            params.bgcolor = "#ffffff";
            params.allowscriptaccess = "sameDomain";
            params.allowfullscreen = "true";
            params.wmode = "opaque";
            
            var attributes = {};
            attributes.id = "FlexPaperViewer";
            attributes.name = "FlexPaperViewer";
            
            swfobject.embedSWF(
                "http://<?= DOMAIN?>/etc/reader/FlexPaperViewer.swf", "flashContent", 
                "680", "800",swfVersionStr, xiSwfUrlStr, flashvars, params, attributes);
			swfobject.createCSS("#flashContent", "display:block;text-align:center;");
       </script> 
	        <div id="flashContent"> 
	        	<p> 
		        	To view this page ensure that Adobe Flash Player version 
					10.0.0 or greater is installed.  
				</p> 
				<script type="text/javascript"> 
					var pageHost = ((document.location.protocol == "https:") ? "https://" :	"http://"); 
					document.write("<a href='http://www.adobe.com/go/getflashplayer'><img src='" 
									+ pageHost + "www.adobe.com/images/shared/download_buttons/get_flash_player.gif' alt='Get Adobe Flash player' /></a>" ); 
				</script> 
	        </div>
       	<?php }else{?>
       		<p><?= $this->pageData['attention']?></p>
       	<?php }?>
       </div>
       <div class="share">
          <div class="share_left">
          	<div class="bshare-custom icon-medium">
    				    分享到：<!-- <div class="bsPromo bsPromo1"></div> -->
    				    <a title="分享到新浪微博" class="bshare-sinaminiblog" href="javascript:void(0);"></a>
    				    <a title="分享到人人网" class="bshare-renren" href="javascript:void(0);"></a>
    				    <a title="分享到QQ空间" class="bshare-qzone" href="javascript:void(0);"></a>
    				    <a title="分享到朋友网" class="bshare-qqxiaoyou" href="javascript:void(0);"></a>
    				    <a title="分享到腾讯微博" class="bshare-qqmb" href="javascript:void(0);"></a>
    				    <!-- <a class="bshare-more bshare-more-icon more-style-addthis" title="更多平台"></a> -->
    				    <span style="float: none;" class="BSHARE_COUNT bshare-share-count"></span>
		    </div>
		    <script type="text/javascript" charset="utf-8" src="http://static.bshare.cn/b/buttonLite.js#style=-1&uuid=c52a9d36-aa5b-4f8c-bc99-6cfbc2ebc44e&pophcol=2&lang=zh"></script>
		    <script type="text/javascript" charset="utf-8" src="http://static.bshare.cn/b/bshareC0.js"></script>
		    <script type="text/javascript" charset="utf-8">
                        bShare.addEntry({
                            title:"<?= $this->pageData['file_name']?> （源自 <?= $this->GetUniversityInfo($this->pageData['ucode'],'','name')?>） [好喜欢]",
                            summary:"手机扫描二维码可以直接下载资料、浏览课件、转发邮箱，扫起来吧，小伙伴们~",
                            pic: "<?= $this->pageData['pics']?>"
                        });
             </script>
          </div>
          <div class="share_right">
             <a href="javascript:;" class="ding" id="<?= $this->pageData['file_id']?>"><?= $this->pageData['dingcai']['good_count']?> 有用</a>
             <a href="javascript:;" class="cai" id="<?= $this->pageData['file_id']?>"><?= $this->pageData['dingcai']['bad_count']?> 一般</a>
          </div>
       </div>
       <div class="pinlun">
	       <div class="comment">
			 <div id="disqus_thread"></div>
			    <!-- Duoshuo Comment BEGIN -->
			    <div class="ds-thread" data-thread-key="<?= $this->pageData['file_index']?>" data-title="<?= $this->pageData['file_name']?>"></div>
			        <script type="text/javascript">
			        var duoshuoQuery = {short_name:"xzbbm"};	
			        (function() {		
				        var ds = document.createElement('script');		
				        ds.type = 'text/javascript';ds.async = true;		
				        ds.src = 'http://static.duoshuo.com/embed.js';		
				        ds.charset = 'UTF-8';		
				        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(ds);	})();	
			        </script>
			     <!-- Duoshuo Comment END -->
		   </div>
		</div>
  </div>
  <div class="ziliao_right">
     <div class="ewm02">
         <h3><img src="http://<?= DOMAIN?>/images/wx.png">微信或客户端扫描二维码</h3>
         <a title="学长只能帮你到这儿了~" file_name="<?= $this->pageData['file_name']?>" href="javascript:;" src="http://<?= DOMAIN?>/?action=QrCodes&do=GcQr&size=510&fid=<?= $this->pageData['file_id']?>" class="show_big_qrcode">
         	<img id="erm_small" src="http://<?= DOMAIN?>/?action=QrCodes&do=GcQr&size=180&fid=<?= $this->pageData['file_id']?>" width="180" height="180" />
         </a>
         <h3>使用移动设备传播和分享此资料</h3>
         <span>MD5 Hash <?= $this->md5?></span>
         <div class="big_qrcode" style="display:none">
			<img id="erm_big" src="" width="180" height="180">
		</div>
     </div>
     <div class="youxiang">
        <a href="javascript:void(0)" onclick="$('#fade1').fadeIn('slow');$('#light3').fadeIn('slow');" class="youxiang_btn"><img src="http://<?= DOMAIN?>/images/youxiang_btn.jpg" width="211" height="50" /></a>
		<input type="text" size="18" value="http://xzbbm.cn/<?= $this->pageData['file_key']?>" />					
         <object id="clippy" class="clippy" width="62" height="24" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000">
			<param value="http://<?= DOMAIN?>/etc/copy/copy.swf" name="movie">
			<param value="always" name="allowScriptAccess">
			<param value="high" name="quality">
			<param value="noscale" name="scale">
			<param value="txt=<?= $this->pageData['file_name']?>  源自  <?= $this->GetUniversityInfo($this->pageData['ucode'],'','name')?> http://xzbbm.cn/<?= $this->pageData['file_key']?>" name="FlashVars">
			<param value="opaque" name="wmode">
			<embed width="62" height="24" align="middle" flashvars="txt=<?= $this->pageData['file_name']?>  源自  <?= $this->GetUniversityInfo($this->pageData['ucode'],'','name')?> http://xzbbm.cn/<?= $this->pageData['file_key']?>" src="http://<?= DOMAIN?>/etc/copy/copy.swf" quality="high" wmode="transparent" allowscriptaccess="sameDomain" pluginspage="http://www.adobe.com/go/getflashplayer" type="application/x-shockwave-flash">
		</object>
		<!--  <ul>
          <li>DOC PDF SWF(1-6) PNG(1-6)</li>
        </ul> -->
     </div>
     <div class="kantai">
        <h4>大家正在看</h4>
        <ul>
        	<?php $i=0; foreach($this->pageData['relate'] as $data) {?>
           		<li id="relate_<?= $i++?>">
           			<span>
           				<a target="self" href="http://<?= DOMAIN?>/view/<?= $data['file_index']?>" class="kan_title related" title="<?= $data['file_name']?>"><?= $data['file_name']?></a>
           			</span>
           			<span><em class="eye"><?= intformat($data['file_views'])?></em><em class="lie"><?= intformat($data['file_downs'])?></em></span>
           		</li>
        	<?php }?>
        </ul>
     </div>
     <div class="banner01"><a href="#"><img id="index_banner" alt="学长帮帮忙，学长可以帮你更多。" title="为什么不猛戳？" src="http://<?= DOMAIN?>/images/banner01.jpg" width="248" height="138" /></a></div>
  </div>
</div>
<div id="light3" class="white_content">
  <div class="ty">
     <div class="ty_nr">
         <div class="qh_title">
        <h2>资料免费递送服务</h2> <a onclick="$('#fade1').fadeOut('slow');$('#light3').fadeOut('slow');" href="javascript:void(0)"><img width="20" height="18" src="http://<?= DOMAIN?>/images/guanbi.jpg"></a> </div>
         <div class="ty_wenzi">
             <h2>请输入邮箱地址</h2>
             <span>建议使用新浪、网易或腾讯邮箱</span>
            </div>
             <input id="sendmailaddr" file_index="<?= $this->pageData['file_index']?>" type="text" class="txt6" value="<?= $this->userinfo['email']?$this->userinfo['email']:$_COOKIE[md5('xzbbm.cn_send_adress')]?>"/>
             <span class="btn06"><a href="javascript:;" class="btn06" id="sendmailsbt">开始投递</a></span>
     </div>
     <div class="ty_bottom"></div>
  </div>
</div>
<div class="little_footer">
	<p class="little_p01">已有<strong> <?= $this->totalUser?> </strong>位同学加入学长帮帮忙，免费获取、分享、传播了<strong> <?= $this->totalDown?> </strong>次，超过 <strong> <?= $this->totalFile?> </strong>份校内资料</p>
	<p class="little_p02">
		2014年“创青春”全国大学生创业大赛移动互联网创业专项赛银奖作品&nbsp;&nbsp;&nbsp;&nbsp; © All Rights Reserved 2012-2015&nbsp;&nbsp;&nbsp;&nbsp;粤ICP备15020442号-1
	</p>
</div>
<div class="send_rs"></div>
<?php include Template('footer');?>
