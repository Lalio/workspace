<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
td{
	text-align: left;
}
body {
    font-size: 14pt;
}
table tr{
	border: 1px solid #525C3D;
}
</style>
</head>
<body>
<table>
	<tr>
	    <th>ID</th>
	    <th>代理</th>
	    <th>区域</th>
	    <th>客户名称</th>
	    <th>品牌/产品线</th>
	    <th>直客销售</th>
	    <th>直客销售Leader</th>
	    <th>渠道销售</th>
	    <th>渠道销售Leader</th>
	    <th>投放周期-开始</th>
	    <th>投放周期-结束</th>
	    <th>定向区域</th>
	    <th>CPM量</th>
	    <th>广告用途</th>
	    <th>广告位置</th>
	    <th>广告形式</th>
	    <th>定向频道</th>
	    <th>广告时长</th>
	    <th>频次控制</th>
	    <th>投放时间</th>
	    <th>KPI说明</th>
	    <th>关键词</th>
	    <th>提交AE</th>
	    <th>提交时间</th>
	    <th>备注信息</th>
	</tr>
	<?php foreach($this->pagedata as $data){
	        $areas = explode("\n", $data[area]);
	        $data['channel'] = explode("_", $data['channel']);
	        foreach($data['channel'] as $k => $v){
	        	$data['channel'][$k] = $this->channel[$v];
	        }
	        $data['channel'] = implode(",", $data['channel']);
	    foreach($areas as $v){
	        $areas_cpm = explode("_", $v);
    ?>
	<tr>
    	<td><?= $data['id']?></td>
    	<td><?= $data['agent']?></td>
    	<td><?= $data['region']?></td>
    	<td><?= $data['vname']?></td>
    	<td><?= $data['brand']?></td>
    	<td><?= $data['dcs']?></td>
    	<td><?= $data['dcs_leader']?></td>
    	<td><?= $data['cs']?></td>
    	<td><?= $data['cs_leader']?></td>
    	<td><?= $data['st']?></td>
    	<td><?= $data['et']?></td>
    	<td><?= $areas_cpm[0]?></td>
    	<td><?= $areas_cpm[1]?></td>
    	<td><?= $data['mode']?></td>
    	<td><?= $this->adstype[$data['cid']]?></td>
    	<td><?= $this->ad_sub_type[$data[type]]?></td>
    	<td><?= $data['channel']?></td>
    	<td><?= $data['tp_time']?></td>
    	<td><?= $data['days']."天".$data['times']."次"?></td>
    	<td><?= $data['display_hour']?></td>
    	<td><?= $data['kpi']?></td>
    	<td><?= $data['keyword']?></td>
    	<td><?= $data['ae']?></td>
    	<td><?= $data['ts']?></td>
    	<td><?= $data['message']?></td>
	</tr>
	<?php }}?>
</table>
</body>
</html>