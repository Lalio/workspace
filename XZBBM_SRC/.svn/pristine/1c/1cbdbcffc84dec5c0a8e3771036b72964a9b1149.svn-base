<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;
}
?>
</div>
<br />
<div class="footer">
<? if($_GET['action'] != 'Auth'){ ?>
<p><a title="Developer: WangBo | Sys Team" href="javascript:;">广州市千钧网络科技有限公司 版权所有</a></p>
<p><a><font size="0.5pt">Total Times:<?php printf('%.5f',(microtime(true) - $this->_scriptstarttime))?>(s) , DataBase:Master[OK] - Slave[OK] , Gzip:On , Server Time:<span class="clock"></span> , 56.com Sys Dev Team</font></a></p>
<? }?>
</div>
<!-- 
<div class="order_ctr_panel_common" style="	position: fixed;bottom: 36px;z-index: 3;right: 25px;">
	<a style="background-color: rgba(233, 87, 87, 0.85098); height:30px; width:20% ; bottom: 5% ; font-size:18px ; color:white ;" href="http://mads.56.com/pre/?action=Auth">(!) 旧系统入口</a>
</div>
 -->
<div class="order_ctr_panel_common">
	<!-- <span class="button green medium" onclick="location.href='./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&show=add'">新建</span> -->
	<a href="javascript:;" id="totop" class="button blue medium">顶部</a>
	<span class="button white medium" onclick="$('.main').printArea();">打印</span>
	<a href="http://mads.56.com/pre/admin3/?action=Auth" class="button white medium">旧版</a>
</div>
<script type="text/javascript">
var currentDate = new Date('<?= date('D M d Y H:i:s O');?>'); 
window.setInterval(function(){
	currentDate.setSeconds(currentDate.getSeconds()+1); 
	var y=currentDate.getFullYear();
	var m=currentDate.getMonth()+1;
	var d=currentDate.getDate();
	var h=currentDate.getHours();
	var i=currentDate.getMinutes();
	var s=currentDate.getSeconds();
    $(".clock").html(y+'-'+m+'-'+d+' '+h+':'+i+':'+s); 
}, 1000);
</script>
</body>
</html>