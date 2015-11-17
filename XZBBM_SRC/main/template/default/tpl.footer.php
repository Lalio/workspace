<?php
if (! defined ( 'IN_SYS' )) {
	header ( "HTTP/1.1 404 Not Found" );
	die ();
}
?>
<div class="login" id="float_banner">
	<div class="login_hx">
		<a href="http://<?= DOMAIN?>" class="logo"><img src="http://<?= DOMAIN?>/images/logo.png" width="188" height="37" /></a>
		<?php if($this->ucode == 0){?>
		<a href="#" class="school_tc_show school_qh">学校切换</a>
		<?php }else{?>
		<span class="top_uname">
			<a href="#" class="school_tc_show">
				<?= $this->ShowUicon($this->ucode);?>
				<?= $this->GetUniversityInfo($this->ucode,'',$need = 'name')?>
			</a>
		</span>
		<?php }?>
		<a href="#" class="fabu_btn fabu_action">发布资料</a>
		<?php if($_GET['do'] != 'Search'){?>
	     <div class="ss_k">
	      <!--<div class="select_box">  <span class="select_txt">通用分类</span> <a class="selet_open"></a>
	         <div class="option"> <a>习题答案</a> <a>考研真题</a> <a>司法考试</a> </div>
	         <input type="hidden" class="select_value" /> 
	      </div>-->
	      <form action="http://<?= DOMAIN?>" method="get">
	      	  <input name="do" type="hidden" value="Search"/>
	      	  <input name="u" type="hidden" value="<?= $this->ucode?>"/>
	      	  <input name="o" type="hidden" value="u"/>
		      <input name="k" type="text" class="txt" id="s_container" value="<?= $this->GetHotWords('self');?>" onclick="javascript:s_in_action = true;"/>
		      <input alt="submit" type="image" src="http://<?= DOMAIN?>/images/ss_k03.jpg" class="btn_pic" />
	      </form>
	    </div>
	    <?php }?>
    <? if($this->userinfo == ''){?>
    	<span class="dlzc" style="font-size:13px;cursor:hand"><a href="#" onclick="$('#light2').fadeIn('slow');$('#fade2').fadeIn('slow');">♟ 登录/注册</a></span>
	<?php }else{?>
		<span class="dlzc" style="font-size:13px;cursor:hand"><?= $this->userinfo['email']?> <?= $this->userinfo['pay_account']?'收益:￥'.$this->userinfo['profile']:'资料:'.$this->userinfo['ftotal']?>份 <a href="http://<?= DOMAIN?>/?action=Auth&do=Logout">注销</a></span>
	<?php }?>
	<div class="clear"></div>
	</div>
</div>
<div id="fade" class="black_overlay"></div>
<div id="fade1" class="black_overlay"></div>
<div id="fade2" class="black_overlay"></div>
<div id="light" class="white_content">
	<div class="qh01">
		<div class="qh01_nr">
			<div class="qh_title">
				<h2>学校选择</h2>
				<span>学长帮帮忙平台教学资料已经覆盖全国<strong>3000+</strong>所高校</span> <a href="javascript:void(0)" onclick="location.reload();"><img src="http://<?= DOMAIN?>/images/guanbi.jpg" width="20" height="18" /></a>
			</div>
			<div class="qh01_left">
			<?php foreach ($this->GetProvinces() as $data){?>
				<a href="javascript:;" class="sle_pro" id="<?= $data['province_id']?>"><?= $data['province']?></a>
			<?php }?>
			</div>
			<div class="qh01_right">
				<div class="qh_overflow">
					<?php foreach ($this->GetProvinceInfo('北京','data') as $data){?>
							<a href="http://<?= DOMAIN?>/?do=SwitchCollege&ucode=<?= $data['id']?>"><?= $data['name']?></a>
					<?php }?>
				</div>
			</div>
		</div>
		<div class="qh01_bottom"></div>
	</div>
</div>
<div id="light1" class="white_content">
	<div class="qh01">
		<div class="qh01_nr">
			<div class="qh_title">
				<h2>发布资料</h2>
				<span>予人玫瑰，手余馨香</span> <a href="javascript:void(0)" onclick="location.reload();"><img src="http://<?= DOMAIN?>/images/guanbi.jpg" width="20" height="18" /></a>
			</div>
			<p id="role">
				<br>
				1.您应保证您上传的资料不违反法律法规的规定，不包含暴力、色情、反动等一切违法或不良因素，同时不侵犯任何第三人的合法权利。<br />
				2.您应当为您的上传行为独立完全承担法律责任和对外经济赔偿责任。因您的个人行为所产生的一切争议和纠纷以及诉讼，与本平台无关。<br />
				3.您的上传行为代表您同意您上传的作品在本站内的公开发布与传播并授权本站使用您上传的作品。任何非法转载行为与本平台无关。<br />
				4.学长帮帮忙并不是一个永久存储平台，您上传的资料在3个月后可能会被我们的优化算法筛除，请及时存储到私人云。<br />
				5.资料归属：
				<select name="pro" class="select_pro_1" style="background:#fff; border:1px #d8d8d8 solid; height:28px;"> 
					<?php if($this->userinfo['ucode']){?>
						<option value="<?= $this->GetProByUcode($this->userinfo['ucode'],'province_id')?>"><?= $this->GetProByUcode($this->userinfo['ucode'])?></option>
					<?php }?>
					<?php foreach ($this->GetProvinces() as $data) {?>
						<option value="<?= $data['province_id']?>"><?= $data['province']?></option>
					<?php }?>
				</select>
				<select name="ucode" id="uni" class="select_uni_1" style="background:#fff; border:1px #d8d8d8 solid; height:28px;"> 
					<?php if($this->userinfo['ucode']){?>
						<option value="<?= $this->userinfo['ucode']?>"><?= $this->GetNameByUcode($this->userinfo['ucode'])?></option>
					<?php }?>
					<?php foreach ($this->GetProvinceInfo('北京','data') as $data) {?>
						<option value="<?= $data['id']?>"><?= $data['name']?></option>
					<?php }?>
				</select>
				<select name="ccode" id="col" class="select_col_1" style="background:#fff; border:1px #d8d8d8 solid; height:28px;"> 
					<?php if($this->userinfo['ccode']){?>
						<option value="<?= $this->userinfo['ccode']?>"><?= $this->GetNameByCcode($this->userinfo['ccode'])?></option>
					<?php }?>
					<?php foreach ($this->GetUniversityInfo(1000,'清华大学','college') as $data) {?>
						<option value="<?= $data['college_id']?>"><?= $data['college']?></option>
					<?php }?>
				</select>
				<span id="filelist">
				</span>
			</p>
			<div class="tongyi">
				<input type="checkbox" name="benifit" checked/>
					 <em><?= $this->userinfo['userid']?'参加知识文档增值收益计划':'匿名公开发表此资料'?></em>
				<input type="checkbox" id="agree" checked/> 
					<em>我已经理解并同意了 <a target="_blank"
						href="http://zh.wikipedia.org/zh-cn/Wikipedia%3ACC-BY-SA-3.0%E5%8D%8F%E8%AE%AE%E6%96%87%E6%9C%AC">知识共享3.0协议（CC BY-SA 3.0）</a> 及以上分享协议
					</em>
				<span id="loading" style="display:none"><img src="http://<?= DOMAIN?>/images/loading.gif" /></span>
				<span id="fb_btn" class="button green large" style="display: none">确认发布</span>
			</div>
			<div class="ps">
				<div class="uploadflash" style="margin:10px auto">
					<form>
						<input id="file_upload" name="file_upload" type="file" />
						<input name="from" value="web" type="hidden" />
						<div id="queue"></div>
					</form>
				</div>
				<span id="upload_param"></span>
			</div>
		</div>
		<div class="qh01_bottom"></div>
	</div>
</div>
<div id="light2" class="white_content" style="display: none;">
<div class="login_zd">
    <div class="login_zd_nr">
      <ul class="tabs" id="tabs">
        <li onclick="javascript:reg_btn = false;">即刻登录</li>
        <li onclick="javascript:reg_btn = true;">加入学长</li>
        <a href="javascript:void(0)" onclick="$('#light2').fadeOut('slow');$('#fade2').fadeOut('slow');"><img src="http://<?= DOMAIN?>/images/guanbi.jpg" width="20" height="18"></a>
      </ul>
      <ul class="tab_conbox" id="tab_conbox">
        <li class="tab_con" style="display: list-item;">
        	<form id="loginform">
              <table class="denglu" width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tbody>
						  <tr>
						    <td align="center" width="70">电子邮箱</td>
						    <td width="270"><input type="text" name="email" id="textfield"></td>
						    <td>&nbsp;</td>
						  </tr>
						  <tr>
						    <td align="center">密&nbsp;&nbsp;码</td>
						    <td><input type="password" name="password" id="textfield"></td>
						    <td>&nbsp;</td>
						  </tr>
						  <tr>
						    <td align="center">验证码</td>
						    <td class="yanzhengma"><input type="text" name="yzm" value="点击查看" id="login_yzm" class="txt03" onclick="this.value='';$.make_vc();" style="font-size:3px"><span class="vc_span"></span></td>
						    <td>&nbsp;</td>
						  </tr>
						  <tr>
						    <td>&nbsp;</td>
						    <td><input type="checkbox" name="rem" id="checkbox" class="fuxuan" checked><em>记住我</em><a class="fgt_pwd" href="javascript:;" style="float:right;"></a></td>
						    <td>&nbsp;</td>
						  </tr>
						  <tr>
						    <td>&nbsp;</td>
						    <td><a href="javascript:;" class="btn login" onclick="$.login('loginform');">立即登录</a></td>
						    <td>&nbsp;</td>
						  </tr>
					</tbody>
				</table>
		  	</form>
        </li>
        <li class="tab_con" style="display: none;">
        <form id="regform">
           <table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tbody>
			  <tr>
			    <td align="right">电子邮箱</td>
			    <td><label for="textfield"></label>
			    <input type="text" name="email" id="reg_email"></td>
			    <td id="emailcheck">&nbsp;</td>
			  </tr>
			  <tr>
			    <td align="right">密码</td>
			    <td><input type="password" name="password" id="pwd"></td>
			    <td id="pwdrs">&nbsp;</td>
			  </tr>
			  <tr>
			    <td align="right">确认密码</td>
			    <td><input type="password" name="confirm_pwd" id="confirm_pwd"></td>
			    <td id="pwdcheckrs">&nbsp;</td>
			  </tr>
			  <tr>
			    <td align="right">学校/学院</td>
			    <td>
			    	<select name="pro" class="select_pro_1" style="float:left; margin-left:8px; background:#fff; border:1px #d8d8d8 solid; height:28px;"> 
						<?php foreach ($this->GetProvinces() as $data) {?>
						<option value="<?= $data['province_id']?>"><?= $data['province']?></option>
						<?php }?>
					</select>
					<br>
					<select name="uni" id="uni" class="select_uni_1" style="float:left; margin-left:8px; background:#fff; border:1px #d8d8d8 solid; height:28px;"> 
						<?php foreach ($this->GetProvinceInfo('北京','data') as $data) {?>
						<option value="<?= $data['id']?>"><?= $data['name']?></option>
						<?php }?>
					</select>
			    </td>
			    <td>
					<select name="col" id="col" class="select_col_1" style="float:left; margin-left:8px; background:#fff; border:1px #d8d8d8 solid; height:28px;"> 
						<?php foreach ($this->GetUniversityInfo(1000,'清华大学','college') as $data) {?>
						<option value="<?= $data['college_id']?>"><?= $data['college']?></option>
						<?php }?>
					</select>
			    </td>
			  </tr>
			  <tr>
			    <td align="right">身份</td>
			    <td>
					<select name="sex" style="float:left; margin-left:8px; background:#fff; border:1px #d8d8d8 solid; height:28px;"> 
						<option value="male">♂ 学长</option>
						<option value="female">♀ 学姐</option>
					</select>
			    </td>
			  </tr>
			  <tr>
			    <td align="right">验证码</td>
			    <td class="yanzhengma"><input type="text" name="yzm" id="reg_yzm" class="txt03" value="点击查看" onclick="this.value='';$.make_vc();" style="font-size:3px">&nbsp;&nbsp;<span class="vc_span"></span></td>
			    <td>&nbsp;</td>
			  </tr>
			  <tr>
			    <td align="center">支付宝账号</td>
			    <td><input type="text" name="pay_account"></td>
			    <td><font style="font-size:13px;color:#72A90C;">(!)仅用于向您汇入资料分享收益</font></td>
			  </tr>
			  <tr>
			    <td>&nbsp;</td>
			    <td colspan="2">
			    <input type="checkbox" name="agree" class="fuxuan" checked>
			      <label for="checkbox"></label>我已阅读并同意 <a href="javascript:;" title="">《学长帮帮忙服务条款》</a></td>
			  </tr>
			  <tr>
			    <td>&nbsp;</td>
			    <td><a href="javascript:;" class="btn reg">立即注册</a></td>
			    <td>&nbsp;</td>
			  </tr>
			</tbody>
		</table>
		</form>
        </li>
      </ul>
    </div>
    <div class="login_bottom"></div>
 </div>
</div>
<div class="footer" id="bottom_ad">
	<div class="footer_bg">
		<a href="javascript:void(0)"
			onclick="$('#light2').toggle();$('#fade2').toggle()" class="dl">登陆</a><a
			href="javascript:void(0)"
			onclick="$('#light2').toggle();$('#fade2').toggle()" class="zc">注册</a>
	</div>
</div>
<input type="hidden" id="ts" value="<?= TIMESTAMP?>" />
<input type="hidden" id="token" value="<?= md5('2zwep62GnVv08Z5W9GGc'.TIMESTAMP);?>" />
<script type="text/javascript" src="http://<?= DOMAIN?>/js/jquery.jslides.js"></script>
<script type="text/javascript" src="http://<?= DOMAIN?>/js/jquery.scroll_loading-min.js?v=6"></script>
<script type="text/javascript" src="http://<?= DOMAIN?>/js/js.js"></script>
<script type="text/javascript" src="http://<?= DOMAIN?>/js/slides.js"></script>
<script type="text/javascript" src="http://<?= DOMAIN?>/js/oldindex.js"></script>
<script type="text/javascript" src="http://<?= DOMAIN?>/js/jquery.fancybox-1.3.4.js"></script>
<script type="text/javascript" src="http://<?= DOMAIN?>/js/jquery.uploader.min.js"></script>
<script type="text/javascript" src="http://pv.sohu.com/cityjson?ie=utf-8"></script>
<script type="text/javascript" src="http://<?= DOMAIN?>/js/base.js?v=33"></script>
<div class="tj" style="display:none">
	<script src="http://s95.cnzz.com/z_stat.php?id=1254673635&web_id=1254673635" language="JavaScript"></script>
   <!--  <script type="text/javascript" src="http://tajs.qq.com/stats?sId=28758788" charset="UTF-8"></script> -->
</div>
</body>
</html>