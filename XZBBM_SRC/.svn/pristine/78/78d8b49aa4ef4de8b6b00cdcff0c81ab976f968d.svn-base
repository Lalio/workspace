<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;    
}
?>
<html>
<head>
<title>投放排期</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<script type="text/javascript" src="http://s1.56img.com/script/lib/jquery/jquery-1.4.4.min.js"></script>
<style>
input{
	border-radius:5px;
	vertical-align:middle
}
</style>
<script type="text/javascript">
jQuery(document).ready(function(){
//修改每天CPM的投放量
$(".per_cpms").blur(function(){
    var obj = $(this);
	var daytime = obj.attr('daytime');
	var cpm = obj.attr('value');
	$.ajax({
		url:"../admin3/?action=Tackle&do=ChangePerCpm&aid=<?php echo $_REQUEST['aid'];?>&daytime="+daytime+"&cpm="+cpm,
        type:"post",
        dataType:"json",
        success:function(data){
            if(data.rs == 0){
                location.reload();
            }else{
            	alert('修改失败');
            }
        }
    });
});
});
</script>
</head>
<body topmargin="0" leftmargin="0">
<?php ?>
<? foreach($month_arr as $t){?>
	<!-- 输出某个月的排期表 由 $year 和 $month 指定 -->
	<?php
	    $year = $t['y'];
	    $month = $t['m'];
		$startday = date('w',mktime(0,0,0,$month,1,$year)); //w参数返回给定日期是星期中的第几天，这里判定某月的1号是星期几
		$totalday = date('t',mktime(0,0,0,$month,1,$year)); //t参数返回给定月份所应有的天数

		$week = array('星期日','星期一','星期二','星期三','星期四','星期五','星期六');
		$i = $j = 0;
	?>
	<div class="cal_main">
		<table>
			<tr><td colspan = "7"><span class="cal_title"><?= $year?>年<?= $month?>月投放排期表</span></td></tr>
			<tr>
			<?php
			foreach($week as $val){  
			    echo '<td>'.$val.'</td>';  
			}
			?>  
			</tr>
			<tr>
			<?php
			while(++$i <= $startday){  
			    echo '<td></td>';  
			}  
			while(++$j <= $totalday){

				if($j<10){
					$show_num = '0'.(string)$j;
				}else{
					$show_num = (string)$j;
				}

				$cur_ts = mktime(0,0,0,$month,$j,$year);

			    if(array_key_exists($cur_ts, $rs)){ 
			    	echo "<td>".$show_num."<input style='color:#008000;font-weight:bold;' class='per_cpm' daytime='$cur_ts' type='text' size='1' readonly='true' value='$rs[$cur_ts]'/></td>";  
			    }else{
					echo "<td>".$show_num."<input style='color:#008000;font-weight:bold;' class='per_cpm' daytime='$cur_ts' type='text' size='1' readonly='true' value=''/></td>";  
			    }

				if(($j+$startday) % 7 == 0){  
			        echo '</tr><tr>';  
			    } 
			}
			?>
			</tr> 
		</table>
	</div>
	<!-- 输出某个月的排期表 由 $year 和 $month 指定 -->
<? } ?>
</body>
</html>