<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;    
}
?>
<?php include template('header');?>
<script type="text/javascript" src="./script/js/order.js?v=101"></script>
<div class="ctr_panel">
	<br />
	<table>
		<tr>
			<td width="18%">广告ID：<strong><?= $_GET['aid']?></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="./?action=Order&do=StatisticsPage&aid=<?= $_GET['aid']?>&tc=<?= $_GET['tc']?((int)$_GET['tc'])+1:2?>">更多</a></td>
			<td width="14%">展示量</td>
			<td width="14%">点击量</td>
			<td width="14%">独立IP数</td>
			<td width="14%">每IP曝光量</td>
			<td width="14%">每IP点击量</td>
			<td width="12%">曝光率</td>
		</tr>
		<?php foreach($this->pagedata['detail'] as $data){?>
			<tr>
				<td><a href="#graph" class="get_graph" aid="<?= $_GET['aid']?>" year="<?= $data['year']?>" month="<?= $data['month']?>" day="<?= $data['day']?>"><?= "$data[year]-$data[month]-$data[day]"?></a></td>
				<td><?= intformat($data['views'])?></td>
				<td><?= intformat($data['clicks'])?></td>
				<td><?= intformat($data['viewips'])?></td>
				<td><?= number_format($data['views']/$data['viewips'],4)?></td>
				<td><?= number_format($data['clicks']/$data['viewips'],4)?></td>
				<td><strong><?= round($data['clicks']/$data['views']*100,3)?>%</strong></td>
			</tr>
		<?php }?>
		<tr>
			<td>今日当前</td>
			<td><?= intformat($this->pagedata['today_views'])?></td>
			<td><?= intformat($this->pagedata['today_clicks'])?></td>
			<td><?= intformat($this->pagedata['today_viewips'])?></td>
			<td><?= number_format($this->pagedata['today_per_view'],4)?></td>
			<td><?= number_format($this->pagedata['today_per_click'],4)?></td>
			<td><strong><?= round($this->pagedata['today_clicks']/$this->pagedata['today_views']*100,3)?>%</strong></td>
		</tr>
		<tr>
			<td><font color="green">今日预测</font></td>
			<td><font color="green"><strong><?= intformat($this->pagedata['pre_views'])?></strong></font></td>
			<td><font color="green"><strong><?= intformat($this->pagedata['pre_clicks'])?></strong></font></td>
			<td><font color="green"><strong><?= intformat($this->pagedata['pre_viewips'])?></strong></font></td>
			<td><font color="green"><strong><?= number_format($this->pagedata['pre_per_view'],4)?></strong></font></td>
			<td><font color="green"><strong><?= number_format($this->pagedata['pre_per_click'],4)?></strong></font></td>
			<td><font color="green"><strong><?= round($this->pagedata['pre_clicks']/$this->pagedata['pre_views']*100,4)?>%</strong></font></td>
		</tr>
		<tr>
			<td>本月汇总</td>
			<td><?= intformat($this->pagedata['month_views'])?></td>
			<td><?= intformat($this->pagedata['month_clicks'])?></td>
			<td><?= intformat($this->pagedata['month_viewips'])?></td>
			<td><?= number_format($this->pagedata['month_per_view'],4)?></td>
			<td><?= number_format($this->pagedata['month_per_click'],4)?></td>
			<td><?= round($this->pagedata['month_clicks']/$this->pagedata['month_views']*100,3)?>%</strong></td>
		</tr>
		<tr>
			<td>全部汇总</td>
			<td><?= intformat($this->pagedata['total_views'])?></td>
			<td><?= intformat($this->pagedata['total_clicks'])?></td>
			<td><?= intformat($this->pagedata['total_viewips'])?></td>
			<td><?= number_format($this->pagedata['total_per_view'],4)?></td>
			<td><?= number_format($this->pagedata['total_per_click'],4)?></td>
			<td><strong><?= round($this->pagedata['total_clicks']/$this->pagedata['total_views']*100,3)?>%</strong></td>
		</tr>
		<tr>
			<td>平均每日</td>
			<td><?= intformat($this->pagedata['avg_views'])?></td>
			<td><?= intformat($this->pagedata['avg_clicks'])?></td>
			<td><?= intformat($this->pagedata['avg_viewips'])?></td>
			<td><?= number_format($this->pagedata['avg_per_view'],4)?></td>
			<td><?= number_format($this->pagedata['avg_per_click'],4)?></td>
			<td><strong><?= round($this->pagedata['avg_clicks']/$this->pagedata['avg_views']*100,3)?>%</strong></td>
		</tr>
		<tr id="graph">
			<td colspan="7"><span class="date"><?= date('Y-m-d',TIMESTAMP)?></span> 展示量分布</td>
		</tr>
		<tr>
			<td colspan="7" style="text-align:center;"><img id="view_gra" src="../chart/chart.php?type=us_post&str=<?= $this->pagedata['view_str']?>"></td>
		</tr>
		<tr>
			<td colspan="7"><span class="date"><?= date('Y-m-d',TIMESTAMP)?></span> 点击量分布</td>
		</tr>
		<tr>
			<td colspan="7" style="text-align:center;"><img id="click_gra" src="../chart/chart.php?type=us_post&str=<?= $this->pagedata['click_str']?>"></td>
		</tr>
		<tr>
			<td colspan="7"><span class="date"><?= date('Y-m-d',TIMESTAMP)?></span> 独立IP量分布</td>
		</tr>
		<tr>
			<td colspan="7" style="text-align:center;"><img id="viewip_gra" src="../chart/chart.php?type=us_post&str=<?= $this->pagedata['viewip_str']?>"></td>
		</tr>
	</table>
	<br />		<!-- 关闭空的html标签 -->
</div>
<?php
include template('footer');
?>