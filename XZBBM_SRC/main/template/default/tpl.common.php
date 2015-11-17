<?php
if ( !defined( 'IN_SYS' ) ) {
	header( "HTTP/1.1 404 Not Found" );
	die;
}

$type = array(
		'intro' => '产品介绍',
		'culture' => '团队文化',
		'joinus' => '加入我们',
		'contact' => '联系我们',
		'friendlinks' => '友情链接',
		'profile' => '收益计划'
		);
?>
<?php include Template('header');?>
<title><?= $type[$_REQUEST['type']]?> - 学长帮帮忙_xzbbm.cn</title>
</head>
<body style="padding:0 0 0 0;">
<div class="jiaodian">
  <div class="jd"></div>
</div>
<div class="main">
    <div class="tongyong_left">
       <h2>关于学长</h2>
       <ul>
          <li><a href="http://www.xzbbm.cn/about/intro.html"<?= $_REQUEST['type']=='intro'?' class="hover"':''?>>产品简介</a></li>
          <li><a href="http://www.xzbbm.cn/about/culture.html"<?= $_REQUEST['type']=='culture'?' class="hover"':''?>>团队文化</a></li>
          <li><a href="http://www.xzbbm.cn/about/joinus.html"<?= $_REQUEST['type']=='joinus'?' class="hover"':''?>>加入我们</a></li>
          <li><a href="http://www.xzbbm.cn/about/contact.html"<?= $_REQUEST['type']=='contact'?' class="hover"':''?>>联系方式</a></li>
          <li><a href="http://www.xzbbm.cn/about/friendlinks.html"<?= $_REQUEST['type']=='friendlinks'?' class="hover"':''?>>友情链接</a></li>
          <li><a href="http://www.xzbbm.cn/about/profile.html"<?= $_REQUEST['type']=='profile'?' class="hover"':''?>>收益计划</a></li>
          <!-- <li><a href="#">加入我们</a></li>
          <li><a href="#">网站协议</a></li>
          <li><a href="#">联系我们</a></li>
          <li><a href="#">友情链接</a></li> -->
       </ul>
    </div>
    <div class="tongyong_right">
    <?php if ($_REQUEST['type'] == 'profile') {?>
	       <div class="title"><h2>资料分享收益计划</h2></div>
	       <div class="wenzhang_nr">
	       	   <p style="text-align:center;"><embed src="http://static.youku.com/v/swf/qplayer.swf?winType=adshow&VideoIDS=XNzMzODA5OTky&isAutoPlay=true&isShowRelatedVideo=false" wmode="transparent" width="658" align="center" border="0" height="270"></p>
		       <div class="gognneng">
			       <h2>Q:不仅可以免费下载资料，发布资料还能获得收益？</h2>
			       <p>A：从2014年6月15日起，登录学长帮帮忙账号，通过<a target="_blank" href="http://xzbbm.cn/acMOO">WEB端</a>或者<a target="_blank" href="http://xzbbm.cn/ebfrm">APP随手拍</a>功能发布资料。资料一旦发布成功且传播率（传播率=分享次数/曝光次数）大于1%时即可根据资料的曝光量和分享量获取收益。</p>
			       <p>您可以随时将资料分享计划获得的收益提现至您的的支付宝账号（最低提现金额为10元），提现过程中如果遇到任何问题请与我们的客服人员联系。(cs@xzbbm.cn)</p>
			       <h2>Q:资料分享计划如何计算收益？</h2>
			       <p>A：资料产生的收益由三部分组成，收益=评级收益+曝光收益+分享收益。</p>
			       <p>通过APP随手拍上传的资料，一旦评定为精品资料（仅限校内考试真题），即可获得1-10元的评级收益；该资料每一次在网页端、APP端、微信微博平台的曝光则会为您带来曝光收益；资料被分享、被发送至邮箱可获得分享收益。曝光、分享收益均不设上限。</p>
			       <p>东莞小明网络科技有限公司在法律允许的范围内对此活动持有解释权。</p>
			    </div>
			    <p style="text-align:center;"><img height="130" width="600" src="http://cdn.xzbbm.cn/web/images/profile.png"></p>
		   </div>
	<?php }?>
    <?php if ($_REQUEST['type'] == 'intro') {?>
	       <div class="title"><h2>产品简介</h2></div>
	       <div class="wenzhang_nr">
		       <p>学长帮帮忙最初源于人人网上一位学长的帖子：故事中开车的学长，用一脚油门给了不敢跟女生牵手的学弟一个机会，让学弟顺利牵上了妹子，学长感叹：“加油吧学弟，学长只能帮你到这了。”一个简单的故事，却透着学长助学弟勇夺爱情的高大形象。期末将至，面对那可恶又可恨的试题，面对那不知从何下手的复习，面对那背也背不完的资料... “学长，我们要怎么破？” “不用怕，学长来帮你！”</p>
		       <p style="text-align:center;"><embed src="http://static.youku.com/v/swf/qplayer.swf?winType=adshow&VideoIDS=XNDcxNDA1MTUy&isAutoPlay=true&isShowRelatedVideo=false" wmode="transparent" width="658" align="center" border="0" height="270"></p>
		       <p>学长帮帮忙是东莞小明网络科技有限公司旗下一款针对大学生群体的高校热门教学资源整合平台，我们致力于打造一个免费、简洁、开放的大学教学资源分享平台，目前平台资源覆盖全国1000多所高校，涵盖四六级、公考、专八、司考、研究生入学考试初复试真题及高校各专业历年期中期末考试真题、课件讲稿、复习大纲、习题答案、课程设计、毕业论文等学术资料。</p>
		       <div class="gognneng">
			       <h2>功能特色：</h2>
			       <ul>
				       <li><em>1</em> 快速精确定位校园资料。搜索各科目期末试卷、课后答案、笔记、课件，提高学习效率。</li>
				       <li><em>2</em> 快速分享资料。资料二维码、邮件发送集群，拷贝资料只需轻轻扫描二维码、即可发送到邮箱。</li>
				       <li><em>3</em> 精确分类珍藏电子资料。私有云，每个人都可以拥有一个定制的校园资料图书馆。</li>
				       <li><em>4</em> 海量教辅资料随身携带。PC、手机任意切换，资料精确到每一所学校。</li>
				       <li><em>5</em> 娱乐性的广播活动。让校园心声在校园传播，交校园朋友。</li>
				       <li><em>6</em> 一秒同学圈功能，让实用的资料快速、便捷的分发到每一位同学的手中。</li>
			       </ul>
			    </div>
		       <p>学长帮帮忙通过高校大学生间资料上传共享作为支撑点，让试卷“穿越时空”，最大程度的方便同学们获取教学资料的需求。</p>
		       <p>除了找到学习资料外，用户还可以快速进行共享资料，如：通过“拍试卷”共享自己当年的期末考试试卷、期末考试重点笔记；通过云群发功能给社团或班级小伙伴邮箱批量群发送电子资料；二维码“扫一扫”传递资料等。同时，学长帮帮忙还支持用户通过“私人云”构建自己的大学私人图书馆，将大学期间所需的、所获得的电子学习资料进行分类云存储，相当于一个随身携带的云图书馆。在众多电子资料应用中，学长帮帮忙凭借免费、便捷、精准、海量资料等特色受到大学生用户。在这里，除了享受免费的学习资源外，你还可随时随地感受到学长帮帮忙的视觉美感，创新感等非一般的感觉。</p>
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
		       <p>公司名称：小明网络(东莞)科技有限公司</p>
		       <p>广州公司：广州市番禺区大学城外环路100号</p>
		       <p>客服邮箱：cs@xzbbm.cn</p>
		       <p>业务联系：(+86)13430387985  (+86)15626060103</p>
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
		     <p>学长帮帮忙是东莞小明网络科技有限公司旗下，一款针对大学生群体的校园资料大全应用。现已同时拥有web端和Android端的全国最全的校园学习资料。</p>
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
	<p class="little_p02">
		2014年“创青春”全国大学生创业大赛移动互联网创业专项赛银奖作品&nbsp;&nbsp;&nbsp;&nbsp; © All Rights Reserved 2012-2015&nbsp;&nbsp;&nbsp;&nbsp;粤ICP备15020442号-1
	</p>
</div>
<!--
<div class="footer" id="bottom_ad">
  <div class="footer_bg"><a href = "javascript:void(0)" onclick = "document.getElementById('light2').style.display='block';document.getElementById('fade2').style.display='block'" class="dl">登陆</a><a href = "javascript:void(0)" onclick = "document.getElementById('light2').style.display='block';document.getElementById('fade2').style.display='block'" class="zc">注册</a></div>
</div>  
-->
<?php include Template('footer');?>
