<?php
if(!defined('IN_SYS')) {
	header("HTTP/1.1 404 Not Found");
	die;
}
?>
<?php include template('header');?>
<script language=javascript src="http://s1.56img.com/js/changedate.js" charset="utf-8"></script>

<body topmargin="10" marginheight="10" marginwidth="10" ondblclick="oAdmin.closeFoot();">
<div id="main_tpl">
  <table width="100%"  style="border:#CCCCCC solid 1px" cellpadding="3" cellspacing="1" class="tableoutline">
	<tr>
    <td colspan="10" class="tbhead">acs广告报表查询-区域统计
				</td>
  </tr>

  <tr class="firstalt b">
  	<td>
		<form action="index.php" method="post" name="form1">
			<input type="hidden" name="action" value="Adreport" />
			<input type="hidden" name="do" value="addRpt" />
			开始时间：<input type="text"  name="bt" value="<?=date('Ymd',time()-86400*10)?>" size="40" onClick='popUpCalendar(this, this, "yyyymmdd")'/>
			结束时间：<input type="text" name="et" value="<?=date('Ymd',time()-86400)?>" size="40" onClick='popUpCalendar(this, this, "yyyymmdd")'/>
			<input type="submit" value="查询" />
		</form>
	</td>
  </tr>
</table>
<?php include template('footer');?>