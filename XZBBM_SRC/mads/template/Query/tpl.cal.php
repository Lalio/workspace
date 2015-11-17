<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;    
}
include template('header');
?>
</br>
<div class="cal_main">
	<table>
		<tr><td colspan = "7"><span class="cal_title"><?= $year?>年<?= $month?>月库存概览  <span class="sub_title"><?= $this->adstype[$cid]?></span></span></td></tr>
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

			$cur_ts = mktime(0,0,0,$month,$j,$year);
			
			if($cur_ts - $today > 7776000){ //90天后的数据不可操作
				echo "<td class='cnum ban' type='".$cid."' ts='".$cur_ts."'>".$j."</td>";
			}elseif($cur_ts < $today){
				echo "<td class='cnum invalid' type='".$cid."' ts='".$cur_ts."'>".$j."</td>";
			}else{ //有效数据
				if($j < date('j')){  
		       		echo "<td class='cnum invalid' type='".$cid."' ts='".$cur_ts."'>".$j."</td>";  
		    	}elseif($j > date('j')){ 
		        	echo "<td class='cnum enough' type='".$cid."' ts='".$cur_ts."'>".$j."</td>";  
		    	}else{
					echo "<td class='cnum ban' type='".$cid."' style='color:#CC3333;' ts='".$cur_ts."'>".$j."</td>";  
		    	}
			}

			if(($j+$startday) % 7 == 0){  
		        echo '</tr><tr>';  
		    } 
		}
		?>
		</tr> 
	</table>
</div>

</br>
<div class="info">
<table>
<tr>
	<td>服务器时间：<span class="clock"></span></td>
	<td class="invalid">Θ已过期或无效</td>
	<td class="ban">Θ暂无库存</td>
	<td class="lack">Θ部分区域有库存</td>
	<td class="enough">Θ库存充足</td>
	<td>
		<form method="Get">
			<input type="hidden" name="action" value="Query">
			<select name="y">
				<?php 
					for($k = 2012;$k<=2016;$k++){  
 						echo $k==$year?"<option value ='$k' selected>$k</option>":"<option value ='$k'>$k</option>";  
					} 
				?>
			</select>年
			<select name="m">
				<?php 
					for($k = 1;$k<=12;$k++){  
 						echo $k==$month?"<option value ='$k' selected>$k</option>":"<option value ='$k'>$k</option>";  
					} 
				?>
			</select>月
			<select name="t" id="type">
				<?php 
					foreach($this->adstype as $k => $v){ 
						echo $k==$cid?"<option value ='$k' selected>$v</option>":"<option value ='$k'>$v</option>";   
					} 
				?>
			</select>
			<input type="submit" value="查询"> <input type="button" onclick="location.href='./?action=Query'" value="本月">
			<!-- <span class="button blue">查询</span><span class="button blue" onclick="location.href='./?action=Query'">本月</span> -->
		</form>
	</td>
</tr>
</table>
</div>
<?php
include template('footer');
?>
