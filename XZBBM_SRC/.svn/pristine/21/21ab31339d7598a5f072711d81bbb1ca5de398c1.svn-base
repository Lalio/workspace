<?php
if ( !defined( 'IN_SYS' ) ) {
	header( "HTTP/1.1 404 Not Found" );
	die;
}
?>
<?php include Template('header');?>
<title>学长帮帮忙 - 专注校内资料的互动与分享</title>
</head>
<body> 
<div class="jiaodian">
  <div class="jd_links">
     <a href="http://xzbbm.cn/app" target="_blank"></a>
  </div>
  <div id="full-screen-slider">
    <ul id="slides">
      <li style="background:url('http://<?= DOMAIN?>/images/<?= $this->pageData['tt'][0]?>') no-repeat center top"></li>
      <li style="background:url('http://<?= DOMAIN?>/images/<?= $this->pageData['tt'][1]?>') no-repeat center top"></li>
      <li style="background:url('http://<?= DOMAIN?>/images/<?= $this->pageData['tt'][2]?>') no-repeat center top"></li>
    </ul>
  </div>
</div>
<div class="main">
  <div class="main_left" id="mainbox">
    <ul class="ljxz">
    <?php $i=1;foreach($this->pageData['menu'] as $k => $v){?>
    	<li class="color0<?= $i?>" onmouseover="this.className='color<?= $i?> color00<?= $i?>'" onmouseout="this.className='color0<?= $i?>'">
        <h3><a href="javascript:;"><?= $k?></a></h3>
        <?php foreach($v as $m => $c){?>
        	<a <?= $m > 6?'style="display:none" class="u_more"':''?>href="javascript:;" onclick="$.getmore('<?= $this->pageData['keys'][$k][$m]?>');" title="<?= $c?>"><?= syssubstr($c,10,true)?></a>
        <?php }?>
        <?php if($m > 6){?>
        	<a href="javascript:;" onclick="$('.u_more').toggle();$(this).toggle();$('#up').toggle();" style="display: block" id="down">...</a>
        	<a href="javascript:;" onclick="$('.u_more').toggle();$(this).toggle();$('#down').toggle();" style="display: none" id="up">↑收起</a>
        <?php }?>
        </li>
    <?php $i++;}?>
    </ul>
    <div class="biaoqian">
      <h4>热门标签</h4>
      <a target="_blank" href="./?do=Search&o=d&u=&k=%E8%AF%BE%E5%90%8E%E7%AD%94%E6%A1%88">课后答案</a>
      <a target="_blank" href="./?do=Search&o=r&u=&k=%E5%A4%8D%E8%AF%95%E7%9C%9F%E9%A2%98">复试真题</a>
      <a target="_blank" href="./?do=Search&k=%E6%9C%9F%E6%9C%AB%E8%AF%95%E9%A2%98&">期末试题</a>
      <a target="_blank" href="./?do=Search&k=%E8%AF%BE%E7%A8%8B%E8%AE%BE%E8%AE%A1&">课程设计</a>
      <a target="_blank" href="./?do=Search&o=d&u=&k=%E6%AF%95%E4%B8%9A%E8%AE%BA%E6%96%87">毕业论文</a>
      <a target="_blank" href="./?do=Search&k=%E5%8F%B8%E6%B3%95%E8%80%83%E8%AF%95">司法考试</a> 
      <a target="_blank" href="./?do=Search&k=%E6%B3%A8%E5%86%8C%E4%BC%9A%E8%AE%A1%E5%B8%88">注册会计师</a> 
    </div>
  </div>
  <div class="main_right">
    <div class="zhengzaikan xiaoyuan">
      <div class="xiaoyuan_title">
        <h2>大家正在看</h2>
        <a href="javascript:;" class="switch" style="color: #838181" onclick="$.getmore('','random')">换一换</a></div>
      <ul class="subtype_zl" id="news">
      	<?php foreach($this->pageData['rand'] as $k => $v) {?>
			<li>
				<a target="_blank" class="kan_title" href="./view/<?= $v['file_index']?>">
					<img src="http://<?= DOMAIN?>/images/<?= $v['file_extension']?>.png">
					&nbsp;<?= $v['file_name']?>
				</a>
				<span>
					<em class="eye"><?= intformat($v['file_views']+$v['file_downs'])?>人看过</em>
					<em class="lie"> <?= $v['profile']?>元收益</em>
				</span>
			</li>
		<?php }?>
      </ul>
    </div>
    <div class="zhengzaikan xiaoyuan floatr">
      <div class="xiaoyuan_title">
        <h2>热点排行榜</h2>
        <a href="#" class="fabu_action" style="color: #D5383F;">上传资料，每天获取现金收益！</a></div>
      <ul class="subtype_zl" id="hots">
      	<?php foreach($this->pageData['top'] as $k => $v) {?>
      		<li>
				<a target="_blank" class="kan_title" href="./view/<?= $v['file_index']?>">
					<img src="http://<?= DOMAIN?>/images/<?= $v['file_extension']?>.png">
					&nbsp;<?= $v['file_name']?>
				</a>
				<span>
					<em class="eye"><?= intformat($v['file_views']+$v['file_downs'])?>人看过</em>
					<em class="lie"> <?= $v['profile']?>元收益</em>
				</span>
			</li>
		<?php }?>
      </ul>
    </div>
    <div class="jingxuan">
	  <div class="jingxuan_title">
	      <h2 class="geo_pro"></h2>
	      <span class="geo_ctc"></span>
	  </div>
      <div id="wrapper">
        <div id="home">
          <div class="mainbox">
            <div class="sectionitems" id="sectionitems">
              <div class="sectionitems_ul">
                <div class="slides_container">
                  <ul class="productsrow" id="srow1">
                    <?php for($i=0;$i<5;$i++) {if(empty($this->pageData['kan'][$i])) continue;?>
                    	<li><a target="_blank" class="productitem" href="./view/<?= $this->pageData['kan'][$i]['file_index']?>" ><span class="productimg"><img src="http://<?= DOMAIN?>/GetFile/<?= $this->pageData['kan'][$i]['file_id']?>/thumb/<?= TIMESTAMP?>/<?= sha1(TIMESTAMP.'sNsxCrth13LGsu60')?>/0" width="92" height="125"/></span><span class="nalaprice"><?= syssubstr($this->pageData['kan'][$i]['file_name'],11)?></span></a></li>
                  	<?php }?>
                  </ul>
                  <ul class="productsrow" id="srow2">
                    <?php for($i=5;$i<10;$i++) {if(empty($this->pageData['kan'][$i])) continue;?>
                     	<li><a target="_blank" class="productitem" href="./view/<?= $this->pageData['kan'][$i]['file_index']?>" ><span class="productimg"><img src="http://<?= DOMAIN?>/GetFile/<?= $this->pageData['kan'][$i]['file_id']?>/thumb/<?= TIMESTAMP?>/<?= sha1(TIMESTAMP.'sNsxCrth13LGsu60')?>/0" width="92" height="125"/></span><span class="nalaprice"><?= syssubstr($this->pageData['kan'][$i]['file_name'],11)?></span></a></li>
                  	<?php }?>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <div class="clear"></div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="links">
  <div class="links_nr">
    <p class="links_wenzi">已有<strong> <?= $this->totalUser?> </strong>位同学加入学长帮帮忙，免费获取、分享、传播了<strong> <?= $this->totalDown?> </strong>次，超过 <strong> <?= $this->totalFile?> </strong>份校内资料</p>
    <div class="links_list">
      <div class="about_us">
        <h2>关于学长</h2>
        <a href="http://<?= DOMAIN?>/about/intro.html" target="_blank">产品简介</a><a href="http://<?= DOMAIN?>/about/culture.html" target="_blank">团队文化</a><a href="http://<?= DOMAIN?>/about/joinus.html" target="_blank">加入我们</a><a href="http://<?= DOMAIN?>/about/contact.html" target="_blank">联系方式</a></div>
      <div class="tel_khd">
        <h2>Android APP</h2>
        <a href="http://xzbbm.cn/app" target="_blank">下载客户端</a></div>
      <div class="guanzhu">
        <h2>关注学长</h2>
        <a href="http://weibo.com/xzbbm" class="xinlang" target="_blank">新浪微博</a><a href="http://t.qq.com/xzbbmnb" class="tengxun" target="_blank">腾讯微博</a><a href="http://zhan.renren.com/profile/254939500?from=bar" class="renren" target="_blank">人人网</a></div>
      <div class="weixin">
        <h2>微信公众号</h2>
        <span class="ewm"><img src="http://<?= DOMAIN?>/images/ewm.jpg" width="107" height="106" /></span></div>
    </div>
    <p class="banquan">
    	“创青春”全国大学生创业大赛银奖作品&nbsp;&nbsp;&nbsp;&nbsp;东莞市小明网络科技有限公司&nbsp;&nbsp;&nbsp;&nbsp; © All Rights Reserved 2012-2015&nbsp;&nbsp;&nbsp;&nbsp;粤ICP备15020442号-1
    </p>
  </div>
</div>
<?php if(empty($this->ucode)) {?>
<script type="text/javascript">
$(document).ready( function() {
	$('#light').fadeIn('slow');
	$('#fade').fadeIn('slow');
})
</script>
<?php }?>
<?php include Template('footer');?>