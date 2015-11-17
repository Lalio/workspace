<?php
if ( !defined( 'IN_SYS' ) ) {
	header( "HTTP/1.1 404 Not Found" );
	die;
}

$detail = array(
		'info' => '我的首页',
		'upload' => '我的上传',
		'download' => '我的下载',
		'yun' => '云收藏',
		);
?>
<?php include Template('header');?>
<title>个人中心 - 学长帮帮忙_xzbbm.cn</title>
</head>
<body style="padding:0 0 0 0;">
<div class="jiaodian">
  <div class="jd"></div>
</div>
<div class="main">
    <div class="tongyong_left">
       <h2>个人中心</h2>
       <ul>
          <li><a href="./?do=My&detail=info"<?= $_REQUEST['detail']=='info' || empty($_REQUEST['detail'])?' class="hover"':''?>>我的首页</a></li>
          <li><a href="./?do=My&detail=upload"<?= $_REQUEST['detail']=='upload'?' class="hover"':''?>>我的上传</a></li>
          <li><a href="./?do=My&detail=download"<?= $_REQUEST['detail']=='download'?' class="hover"':''?>>我的下载</a></li>
          <li><a href="./?do=My&detail=yun"<?= $_REQUEST['detail']=='yun'?' class="hover"':''?>>云收藏</a></li>
       </ul>
    </div>
    <div class="tongyong_right">
    <?php if ($_REQUEST['type'] == 'info' || empty($_REQUEST['detail'])) {?>
	       <div class="title"><h2><?= $this->userinfo['email']?></h2></div>
	       <div class="wenzhang_nr">
		       <div class="gognneng">
			       <ul>
				       <li><em></em> 我的大学：<?= $this->GetNameByUcode($this->userinfo['ucode'])?></li>
				       <li><em></em> 我的学院：<?= $this->GetNameByCcode($this->userinfo['ccode'])?></li>
				       <li><em></em> 我的积分：<strong><?= $this->userinfo['credit']?></strong></li>
				       <?php if(!empty($this->userinfo['pay_account'])){?>
				       <li><em></em> 我的收益：<strong>￥0.00</strong> </li>
				       <li><em></em> 支付账号：<?= $this->userinfo['pay_account']?></li>
				       <?php }?>
				       <li><em></em> 最后登录时间：<?= date('Y-m-d H:i:s',$this->userinfo['last_login_time'])?></li>
				       <li><em></em> 最后登录地点：<?= get_adress($this->userinfo['last_login_ip'])?> ()</li>
			       </ul>
			    </div>
		   </div>
	<?php }?>	
	<?php if ($_REQUEST['type'] == 'joinus') {?>
		   <div class="title"><h2>加入我们</h2></div>
	       <div class="wenzhang_nr">
	       	   <p>我们欢迎各路信奉 <strong>狂热创新，忠于技术，实用至上</strong> 的各路神仙快乐加盟。</p>
	       	   <p>只要你是互联网技术的狂热发烧友、只要你对移动互联网产品超级感冒、只要你有象牙塔内广阔的人际资源，那就尝试和我们联系下吧，说不定因为你，改变了移动互联网的未来~</p>
		       <p>应聘者请将简历投递至hr#xzbbm.cn（请将#替换为@），我们的HR专员将在3-5个工作日内予以答复。</p>
			   <div class="gognneng">
			       <h2>UI界面/交互设计师</h2>
			       <ul>
				       <li>☆ 负责学长帮帮忙平台界面的设计</li>
				       <li>• 大学专科或以上学历，美术、平面设计相关专业，有游戏UI设计经验者优先;精通Photoshop、Flash、等常用软件</li>
				       <li>• 具备扎实的美术基础，较强的手绘能力，独特的创意，良好的审美观、设计理念和用户体验</li>
				       <li>• 工作踏实认真，吃苦耐劳，责任心强，具备良好的沟通协作能力，有团队合作精神，能承受强大的工作压力</li>
			       </ul>
			 </div>
			   <div class="gognneng">
			       <h2>前端开发工程师</h2>
			       <ul>
				       <li>☆ 负责学长帮帮忙WEB/APP平台的前段开发</li>
				       <li>• 计算机或相关专业本科以上学历，2年以上工作经验</li>
				       <li>• 有一定的HTML和CSS的经验</li>
				       <li>• 对JavaScript有很深的理解（包括函数式编程，原型继承，和普通模式）</li>
				       <li>• 对一个MVC的Web应用框架有了解，比如Rails</li>
				       <li>• 了解怎么针对APP进行前端的优化</li>
				       <li>• 对APP兼容性问题有很强的认识</li>
				       <li>• 熟悉Git</li>
				       <li>• （加分项目）熟悉一些新的前端架构比如AngularJS</li>
				       <li>• （加分项目）熟悉Rails</li>
			       </ul>
			 </div>
			 <div class="gognneng">
			       <h2>Android开发工程师</h2>
			       <ul>
				       <li>☆ 负责学长帮帮忙Android平台的开发</li>
				       <li>• 1年以上Android平台开发经验，作为主力开发人员至少完整开发过一个Android应用</li>
				       <li>• 熟悉Android的UI布局，分辨率、版本适配</li>
				       <li>• 熟悉Android的内存使用和性能优化</li>
				       <li>• 熟悉Git</li>
				       <li>• （加分项目）有开源的Android项目</li>
				       <li>• （加分项目）有Android平台游戏(coco2d-x)开发经验</li>
			       </ul>
			 </div>
			 <div class="gognneng">
			       <h2>后端开发工程师</h2>
			       <ul>
				       <li>☆ 负责学长帮帮忙的服务器后端开发和性能优化</li>
				       <li>☆ 负责服务器群的部署和运维</li>
				       <li>• 计算机或相关专业本科以上学历，2年以上工作经验</li>
				       <li>• 一年以上Ruby相关的开发经验，比如Rails、Sinatra等</li>
				       <li>• 熟悉关系型数据库，如MySQL，也了解NoSQL，比如mongodb</li>
				       <li>• 熟悉Linux</li>
				       <li>• （加分项目）熟悉如何规模化一个Web应用（架构/数据库/缓存/CDN）</li>
				       <li>• （加分项目）有实际Linux服务器运维经验</li>
			       </ul>
			 </div>
			 <div class="gognneng">
			       <h2>数据工程师</h2>
			       <ul>
				       <li>☆ 负责开发工具和系统，对学长帮帮忙用户行为进行统计和分析，协助运营数据分析和系统性能数据分析</li>
				       <li>☆ 学习、实践当今最先进的产品分析和数据分析方法</li>
				       <li>• 负责开发工具和系统，对学长帮帮忙用户行为进行统计和分析，协助运营数据分析和系统性能数据分析</li>
				       <li>• 计算机科学或相关专业本科以上学历，熟悉经典的算法和数据结构</li>
				       <li>• 2年以上大数据处理相关工作经验</li>
				       <li>• 精通Java</li>
				       <li>• Hadoop/Hive/Pig, MapReduce, Storm等相关经验</li>
				       <li>• （加分项目）积极参与和大数据相关开源项目的开发</li>
				       <li>• （加分项目）了解机器学习</li>
			       </ul>
			 </div>
			 <div class="gognneng">
			       <h2>定量分析师</h2>
			       <ul>
				       <li>☆ 主动对用户数据进行深入的分析和建模，发现隐藏的机会；即时把发现和自己的见解和产品技术团队沟通</li>
				       <li>☆ 利用复杂的统计模型对大规模的用户行为进行分析</li>
				       <li>• 计算机、统计、数学或工程类相关专业本科以上学历</li>
				       <li>• 熟悉查询语言（Hive/Pig/SQL），预处理方法（unix/python）和一些统计分析的工具(R/Matlab/Stata)</li>
				       <li>• 至少熟悉以下一种分析方法：回归，SVM，boosting方法，HMM，图论，聚类等</li>
				       <li>• （加分项目）了解以下领域：机器学习，自然语言处理，时间序列分析/预测，数据可视化。</li>
			       </ul>
			 </div>
			 <div class="gognneng">
			       <h2>算法工程师</h2>
			       <ul>
				       <li>☆ 负责学长帮帮忙现有算法升级维护，以及整体算法架构的优化</li>
					   <li>☆ 对特定算法模块进行研究、仿真、开发和测试，并根据测试结果对算法模块进行优化和改进</li>
				       <li>• 计算机或相关专业本科以上学历</li>
				       <li>• 熟悉以下至少一个领域的常用算法: 机器学习，语音识别，自然语言处理，数据挖掘</li>
				       <li>• 熟悉linux/unix平台下的开发，具有扎实的编程能力和算法基础</li>
				       <li>• 能快速查阅国际论文、专利等文献</li>
				       <li>• 有创业激情，良好的抗压能力和团队精神</li>
				       <li>• （加分项目）具有海量数据挖掘相关项目经验，参加过完整的互联网数据挖掘项目</li>
				       <li>• （加分项目）具有大规模分布式计算平台（如Hadoop）的使用和并行算法开发</li>
			       </ul>
			 </div>
			 <div class="gognneng">
			       <h2>QA工程师</h2>
			       <ul>
			       <li>• 1年以上测试工作经验，有Android、iOS手机平台测试经验</li>
			       <li>• 熟悉Jenkins等CI工具</li>
			       <li>• 熟悉Git</li>
			       </ul>
			 </div>
	<?php }?> 
	<?php if ($_REQUEST['type'] == 'contact') {?>
		   <div class="title"><h2>联系方式</h2></div>
	       <div class="wenzhang_nr">
		       <p>公司名称：广州夜火网络科技有限公司</p>
		       <p>公司地址：广州市 天河区 东方一路</p>
		       <p>客服邮箱：cs@xzbbm.cn</p>
		       <p>业务联系：+86 15626060103</p>
		   </div>
	<?php }?>  
	<?php if ($_REQUEST['type'] == 'friendlinks') {?>
		   <div class="title"><h2>友情链接</h2></div>
	       <div class="wenzhang_nr">
		       <p>
		       	<a href="http://bbs.gdut.edu.cn" target="_blank">南国飘香</a>
		       	<a href="http://www.gdutbbs.com" target="_blank">工大后院</a>
		       	<a href="http://bbs.yanshuiting.com" target="_blank">华工烟水亭</a>
		       	<a href="http://www.smth.edu.cn" target="_blank">水木清华</a>
		       	<a href="http://bdwm.net" target="_blank">北大未名</a>
		       	<a href="http://www.tdrd.org" target="_blank">天地人大</a>
		       	<a href="http://bbs.waterdh.com" target="_blank">在水一方</a>
		       </p>
		       <p>
		        <a href="http://www.smuonline.net/bbs" target="_blank">海院在线</a>
		       	<a href="http://bbs.lzu.edu.cn" target="_blank">西北望站</a>
		       	<a href="http://bbs.tongji.net" target="_blank">同济大学BBS</a>
		       	<a href="http://bbs.ccnu.com.cn" target="_blank">华大博雅</a>
		       </p>
		   </div>
	<?php }?>   
	<?php if ($_REQUEST['type'] == 'culture') {?>
		   <div class="title"><h2>团队文化</h2></div>
	       <div class="wenzhang_nr">
		     <p>学长帮帮忙是广州夜火网络科技有限公司旗下，一款针对大学生群体的校园资料大全应用。现已同时拥有web端和Android端的全国最全的校园学习资料。</p>
		     <p>找期末试卷、课后答案、笔记、考试真题……，打造大学生私人云图书馆，只爱学长帮帮忙！</p>
		     <p>还记得，上学时候，坑爹的课后习题？</p>
		     <p>还记得，临近考试，挑灯临阵磨枪看试卷？</p>
		     <p>还记得，毕业后，一箱一箱的资料们卖了几斤几毛钱？</p>
		     <p>……</p>
		     <p>我们生来是颠覆者，颠覆高校资料乱象，化繁乱为有序；</p>
		     <p>我们生来是考神，只要有资料，临阵磨枪也能笑傲考场；</p>
		     <p>我们生来是环保主义者，学长帮帮忙把千万绿树全装进手机。</p>
		     <p>我们坚信技术创新、产品创新可以改变未来！</p>
		     <p>于是乎，让我们，这个团队一起：</p>
		     <p><strong>持续创新、善于分享、积极沟通、分享知识、传播文化</strong></p>
		     <p>去改变高校的繁文缛节，让学生从低效的学习中解放出来！</p>
		   </div>
	<?php }?>
    </div>
</div>
</div>
<div class="little_footer">
      <p class="little_p01">已有<strong> <?= $this->totalUser?> </strong>位同学加入学长帮帮忙，免费获取、分享、传播了<strong> <?= $this->totalDown?> </strong>次，超过 <strong> <?= $this->totalFile?> </strong>份学术资料</p>
      <p class="little_p02">Copyright © 2012-2015 xzbbm.cn All Rights Reserved  沪ICP备13045865号-1</p>
</div>
<!--
<div class="footer" id="bottom_ad">
  <div class="footer_bg"><a href = "javascript:void(0)" onclick = "document.getElementById('light2').style.display='block';document.getElementById('fade2').style.display='block'" class="dl">登陆</a><a href = "javascript:void(0)" onclick = "document.getElementById('light2').style.display='block';document.getElementById('fade2').style.display='block'" class="zc">注册</a></div>
</div>  
-->
<?php include Template('footer');?>