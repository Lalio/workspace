<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;    
}
include template('header');
?>
<script type="text/javascript">
if(self!=top){
	top.location=self.location;
}
</script>
<div class="login_main">
	<form id="login_form">
		<table >
			<tr><td colspan="2"><span class="title">56网广告系统访问授权</span></td></tr>
			<tr><td>OA账号</td><td><input type="text" name="username" size="30" autocomplete="off"></td></tr>
			<tr><td>密  码</td><td><input type="password" name="password" size="30" autocomplete="off" onclick="this.value=''"></td></tr>
			<tr><td>验证符</td><td><input type="text" name="vcode" size="10" onclick="this.value=''"><span class="vc_span"></span></td></tr>
			<tr><td colspan="2"><span class="button green" id="sbt_btn" onclick="asyn_sbt('login_form','./?action=Auth&do=Login')">提交验证</span> <span class="button white" onclick="window.close();">关闭页面</span> <!-- <span class="button gray" onclick="window.close();">二维码登陆</span> --></tr>
			<tr><td colspan="2">
				<font size="1">
					Powered By 56.com © Version 3.0
				</font>
				<?php if(!strstr($_SERVER["HTTP_USER_AGENT"],'AppleWebKit')){?>
				<br>
				<font size="2" color="green"><strong>(!) 您当前使用的浏览器存在操作风险</strong></font>
				<br>
				<font size="2" color="green">请使用基于WebKit内核的Chrome/360浏览器访问本系统，<a target="_blank" href="http://chrome.360.cn/">点击这里进行下载</a>。</font></td></tr>
				<?php }?>
		</table>
	</form>
</div>
<script type="text/javascript">
//验证码
make_vc();
//登陆处理逻辑
function rtn(data){
	alert(data.msg);
	<? if($_GET['ref']){?>
		location.href = "<?= $_GET['ref']?>?login_tk="+data.ext;
	<? }else{?>
		location.href = "./?action=Auth&do=Redirect";
	<? }?>
}
</script>
<?php
include template('footer');
?>
