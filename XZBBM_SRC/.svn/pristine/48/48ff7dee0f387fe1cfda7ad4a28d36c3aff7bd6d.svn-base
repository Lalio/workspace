<?php
if ( !defined( 'IN_SYS' ) ) {
	header( "HTTP/1.1 404 Not Found" );
	die;
}
?>
<?php include Template('header');?>
<title><?= '关键词:'.$_REQUEST['k'].'     '?>学长搜索_最犀利的校园资料检索工具</title>
</head>
<body style="padding:0 0 0 0;">
<div class="jiaodian">
  <div class="jd" style="width:100%; height:90px; overflow:hidden; background:url(http://<?= DOMAIN?>/images/search_h_4.jpg) center no-repeat;"></div>
</div>
<div class="main">
   <h2 class="ss_bg">学长搜索</h2> 
   <div class="suosou">
        <ul class="tabs" id="tabs1">
       <li>所有类别</li><!-- <li>资料</li><li>图标</li><li>文档</li> -->
    </ul>
    <ul class="tab_conbox" id="tab_conbox1">
       <!--  <li class="tab_con">
           <div class="select_box"> <span class="select_txt">请选择类别</span> <a class="selet_open"></a>
                <div class="option"> <a>类别1</a> <a>类别2</a> <a>类别3</a> </div>
                <input type="hidden" class="select_value" />
              </div>
           <div class="select_box"> <span class="select_txt">请选择类别</span> <a class="selet_open"></a>
                <div class="option"> <a>类别1</a> <a>类别2</a> <a>类别3</a> </div>
                <input type="hidden" class="select_value" />
              </div>
           <div class="select_box"> <span class="select_txt">请选择类别</span> <a class="selet_open"></a>
                <div class="option"> <a>类别1</a> <a>类别2</a> <a>类别3</a> </div>
                <input type="hidden" class="select_value" />
              </div>
           <input type="text" class="txt04" />   
            <input name="" type="image" src="images/fangdajing.jpg" class="btn01" />  
        </li>
         <li class="tab_con">
           <div class="select_box"> <span class="select_txt">请选择类别</span> <a class="selet_open"></a>
                <div class="option"> <a>类别1</a> <a>类别2</a> <a>类别3</a> </div>
                <input type="hidden" class="select_value" />
              </div>
           <div class="select_box"> <span class="select_txt">请选择类别</span> <a class="selet_open"></a>
                <div class="option"> <a>类别1</a> <a>类别2</a> <a>类别3</a> </div>
                <input type="hidden" class="select_value" />
              </div>
           <div class="select_box"> <span class="select_txt">请选择类别</span> <a class="selet_open"></a>
                <div class="option"> <a>类别1</a> <a>类别2</a> <a>类别3</a> </div>
                <input type="hidden" class="select_value" />
              </div>
           <input type="text" class="txt04" />   
            <input name="" type="image" src="images/fangdajing.jpg" class="btn01" />  
        </li> -->
        <li class="tab_con">
<!--            <div class="select_box"> <span class="select_txt">请选择类别</span> <a class="selet_open"></a>
                <div class="option"> <a>类别1</a> <a>类别2</a> <a>类别3</a> </div>
                <input type="hidden" class="select_value" />
              </div>
           <div class="select_box"> <span class="select_txt">请选择类别</span> <a class="selet_open"></a>
                <div class="option"> <a>类别1</a> <a>类别2</a> <a>类别3</a> </div>
                <input type="hidden" class="select_value" />
              </div>
           <div class="select_box"> <span class="select_txt">请选择类别</span> <a class="selet_open"></a>
                <div class="option"> <a>类别1</a> <a>类别2</a> <a>类别3</a> </div>
                <input type="hidden" class="select_value" />
           </div> -->
           <form action="" method="get">
           	   <input type="hidden" name="do" value="Search"/> 
	           <input type="text" class="txt04" name="k" value="<?= $_GET['k']?>"/>
	           <input type="hidden" class="txt04" name="o" value="<?= $_GET['o']?$_GET['o']:'u'?>"/>   
	           <input type="hidden" class="txt04" name="u" value="<?= $this->ucode?>"/>      
	           <input name="" alt="submit" type="image" src="http://<?= DOMAIN?>/images/fangdajing.jpg" class="btn01" />  
           </form>
        </li>
    </ul>
   </div>
   <div class="paixu">
      <div class="paixu_left"><em>排序：</em>
      	  <a href="./?do=Search&o=b&u=<?= $_GET['u']?>&k=<?= $_GET['k']?>"<?= $_GET['o']=='b'?' class="dazhi"':''?> title="在全国高校内搜索">全国搜索</a>
      	  <?php if(empty($this->ucode)){?>
	      	<a href="#" onclick="$('#light').fadeIn('slow');$('#fade').fadeIn('slow');" title="在全校范围内搜索">全校搜索</a>
	      	<!-- <a href="#" onclick="$('#light').fadeIn('slow');$('#fade').fadeIn('slow');" title="在全学院范围内搜索">学院搜索</a>  -->
	      <?php }else{?>
	      	<a href="./?do=Search&o=u&u=<?= $_GET['u']?>&k=<?= $_GET['k']?>"<?= $_GET['o']=='u'?' class="dazhi"':''?> title="在全校范围内搜索">全校搜索</a>
	      	<!-- <a href="./?do=Search&o=c&u=<?= $_GET['u']?>&k=<?= $_GET['k']?>"<?= $_GET['o']=='c'?' class="dazhi"':''?> title="在全学院范围内搜索">学院搜索</a>  -->
	      <?php }?>
	      <a href="./?do=Search&o=d&u=<?= $_GET['u']?>&k=<?= $_GET['k']?>"<?= $_GET['o']=='d'?' class="dazhi"':''?> title="按下载次数排序">下载次数</a>
	      <a href="./?do=Search&o=g&u=<?= $_GET['u']?>&k=<?= $_GET['k']?>"<?= $_GET['o']=='g'?' class="dazhi"':''?> title="按用户好评排序">用户好评</a>
	      <a href="./?do=Search&o=r&u=<?= $_GET['u']?>&k=<?= $_GET['k']?>"<?= !isset($_GET['o'])||$_GET['o']=='r'?' class="dazhi"':''?> title="根据云端数据为你智能推荐">智能推荐</a>
      </div>
      <?php if($this->total > 0){?>
      <div class="paixu_right">共帮你找到  <strong><?= intformat($this->total)?></strong> 份相关资料 <? if($this->page > 1){?><a href="./?do=Search&k=<?= $_REQUEST['k']?>&page=<?= $_REQUEST['page']-1?>">上一页</a><? }?><? if($this->page < ($this->total/20)){?><a href="./?do=Search&k=<?= $_REQUEST['k']?>&page=<?= $this->page+1?>">下一页</a><? }?></div>
   	  <?php }?>
   </div>
	<?php if($this->total == 0){?>
		<?php if($_GET['o'] != 'b'){?>
			<div class="list"><h2>学长在 <?= $this->GetNameByUcode($this->ucode);?> 暂时没有找到相关的资料，你还可以尝试 <a href="javascript:;" onclick="location.href='./?do=Search&o=b&u=<?= $this->ucode?>&k=<?= $_GET['k']?>'">去其他学校看看</a></h2></div>
		<?php }else{?>
			<div class="list"><h2>%>_<% 学长暂时没有找到你想要的资料，你可以过一段时间搜索或者换个关键词试试</h2></div>
		<?php }?>
	<?php }?>
	
   <?php for($i=0;$i<20;$i++){?>
   		<?php if(!empty($this->pageData[$i])){?>
	   		<div class="list">
		       <h2><img src="http://<?= DOMAIN?>/images/<?= $this->pageData[$i]['file_extension']?>.png" onerror="this.src='http://<?= DOMAIN?>/images/chm.png'"/>  <a target="_blank" style="font-size:19.5px" href="http://www.xzbbm.cn/view/<?= $this->pageData[$i]['file_index']?>"><?=syssubstr($this->pageData[$i]['file_name'],35)?></a>   <font style="font-size:12px;">源自：<?= $this->GetUniversityInfo($this->pageData[$i]['ucode'],'','name')?></font></h2>
		       <span>类型：[<?= strtoupper($this->pageData[$i]['file_extension'])?>]   /   大小：<?= round(($this->pageData[$i]['file_size']/1048576),2)?>MB   /   传播：<?= intformat($this->pageData[$i]['file_downs'])?>   /   点赞：<?= intformat($this->pageData[$i]['good_count'])?>   /   传播率：<?= 100*round(($this->pageData[$i]['file_downs']/$this->pageData[$i]['file_views']),5)?>%</span>
		    </div>
   		<?php }else{?>
   			<div class="list">
	    	</div>
   		<?php }?>
   <?php }?>
   <div class="fenye"><?= $this->sp?></div>
</div>
<div class="little_footer">
      <p class="little_p01">已有<strong> <?= $this->totalUser?> </strong>位同学加入学长帮帮忙，免费获取、分享、传播了<strong> <?= $this->totalDown?> </strong>次，超过 <strong> <?= $this->totalFile?> </strong>份学术资料</p>
      <p class="little_p02">Copyright © 2012-2015 xzbbm.cn All Rights Reserved  沪ICP备13045865号-1</p>
</div>
<?php include Template('footer');?>
