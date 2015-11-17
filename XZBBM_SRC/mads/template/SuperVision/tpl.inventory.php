<?php
if(!defined('IN_SYS')) {
	header("HTTP/1.1 404 Not Found");
	die;
}
if($this->show == 'normal'){
	include template('header');
}

$this->listdata = json_decode($_SESSION[$_REQUEST['skey']],ture);

$ori = array(
		'vv' => $this->listdata['中国']['vv'],
		'kc' => $this->listdata['中国']['kc'],
		'kcz' => $this->listdata['中国']['kcz'],
		'st' => $this->listdata['中国']['st'],
		'nb' => $this->listdata['中国']['nb'],
		'tt' => $this->listdata['中国']['tt'],
		'qy' => $this->listdata['中国']['qy'],
		'bsgs' => $this->listdata['中国']['bsgs'],
		'pq' => $this->listdata['中国']['pq'],
		'pq_s' => $this->listdata['中国']['pq_no'],
		'yx' => $this->listdata['中国']['yx'],
		'pq_yd' => $this->listdata['中国']['pq_yd']
);
?>
<?php if($this->show != 'normal'){?>
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
<?php }?>
<!-- 数据展示模块 -->
<br>
	<table class="report_list">
		<tr>
			<th colspan="4">
				<a>广告系统运行数据盘点报告 - 全国区域库存盘点 [ <?= $_REQUEST['type'] == 1?'前贴':($_REQUEST['type'] == 2?'中贴':($_REQUEST['type'] == 3?'后贴':'暂停广告'))?> ]（<?= $this->listdata['中国']['bt']?> 至 <?= $this->listdata['中国']['et']?>  @ <?= date('Y-m-d H:i:s',TIMESTAMP)?>）</a>
			</th>
		</tr>
	</table>
<br>
	<table class="report_list">
			<tr>
				<td><strong>维度</strong></td>
				<td><strong>可投视频VV</strong></td>
				<td><strong>广告库存</strong></td>
				<td><strong>实际投放</strong></td>
				<td><strong>“我秀和内部”投放</strong></td>
				<td><strong>有效投放</strong></td>
				<td><strong>全国通投</strong></td>
				<td><strong>“区域定向”实投</strong></td>
				<td><strong>“北上广深”实投</strong></td>
				<td><strong>品牌实投</strong></td>
				<td><strong>预定实投</strong></td>
			</tr>
    		<tr>
    			<td>单位：次数</td>
    			<td><?= intformat($ori['vv'])?></td>
    			<td><?= intformat($ori['kcz'])?></td>
    			<td><?= intformat($ori['st'])?></td>
    			<td><?= $ori['nb']?intformat($ori['nb']):0?></td>
    			<td><?= intformat($ori['st'] - $ori['nb'])?></td>
    			<td><?= intformat($ori['tt'])?></td>
    			<td><?= intformat($ori['qy'])?></td>
    			<td><?= intformat($ori['bsgs'])?></td>
    			<td><?= intformat($ori['pq'])?></td>
    			<td><?= intformat($ori['pq_yd'])?></td>
			</tr>
			<tr>
    			<td>单位：CPM</td>
    			<td><?= intformat(floor($ori['vv']/1000))?></td>
    			<td><?= intformat(floor($ori['kc']))?></td>
    			<td><?= intformat(floor($ori['st']/1000))?></td>
    			<td><?= $ori['nb']?intformat(floor($ori['nb']/1000)):0?></td>
    			<td><?= intformat(floor($ori['yx'])/1000)?></td>
    			<td><?= intformat(floor($ori['tt']/1000))?></td>
    			<td><?= intformat(floor($ori['qy']/1000))?></td>
    			<td><?= intformat(floor($ori['bsgs']/1000))?></td>
    			<td><?= intformat(floor($ori['pq']/1000))?></td>
    			<td><?= intformat(floor($ori['pq_yd']/1000))?></td>
			</tr>
			<tr>
    			<td>占比</td>
    			<td><?= round($ori['vv']/$ori['kcz'],4)*100?>% （*/库存）</td>
    			<td>100% （*/*）</td>
    			<td><?= round($ori['st']/$ori['kcz'],4)*100?>% （*/库存）</td>
    			<td><?= round($ori['nb']/$ori['kcz'],4)*100?>%（*/库存）</td>
    			<td>100% （*/*）</td>
    			<td><?= round($ori['tt']/($ori['yx']),4)*100?>% （*/有效）</td>
    			<td><?= round($ori['qy']/($ori['yx']),4)*100?>% （*/有效）</td>
    			<td><?= round($ori['bsgs']/($ori['yx']),4)*100?>% （*/有效）</td>
    			<td><?= round($ori['pq']/($ori['yx']),4)*100?>% （*/有效）</td>
    			<td><?= round($ori['pq_yd']/($ori['yx']),4)*100?>% （*/有效）</td>
			</tr>
	</table>
	<br>
	<table>
		<tr>
			<td style="text-align: center;width: 50%;">
				<div id="canvasDiva">
				</div>
			</td>
			<td style="text-align: center;width: 50%;">
				<div id="canvasDivb">
				</div>
			</td>
		</tr>
	</table>
	<br>
	<table>
		<tr>
			<td style="text-align: left;font-size: 9px;">数据说明：1、预定实投与品牌实投差异一般由预定区间与实投区间不一致导致。2、有效实投与品牌实投差异一般由订单自主投放导致。3、区域定向及定向北上广深的数据限于订单只投放该单一城市。4、以上数据包含盘点结束日期当天。</td>
		</tr>
	</table>
<? if($this->show == 'normal'){?>
<script type="text/javascript">
$(function(){
	var data = [
        	{name : '全国通投',value : <?= round($ori['tt']/($ori['yx']),4)*100?>,color:'#2F9649'},
        	{name : '定向区域实投',value : <?= round($ori['qy']/($ori['yx']),4)*100?>,color:'#49B6DE'},
        	{name : '定向“北上广深”实投',value : <?= round($ori['bsgs']/($ori['yx']),4)*100?>,color:'#FF5500'},
        ];

	var chart = new iChart.Pie3D({
		render : 'canvasDiva',
		data: data,
		title : {
			text : '广告有效投放量区域占比 - [ <?= $_REQUEST['type'] == 1?'前贴':($_REQUEST['type'] == 2?'中贴':($_REQUEST['type'] == 3?'后贴':'暂停广告'))?> ]',
			height:20,
			fontsize : 15,
			color : '#282828'
		},
		footnote : {
			text : 'mads.56.com',
			color : '#336699',
			fontsize : 6,
			padding : '0 38'
		},
		sub_option : {
			mini_label_threshold_angle : 40,//迷你label的阀值,单位:角度
			mini_label:{//迷你label配置项
				fontsize:14,
				fontweight:600,
				color : '#ffffff'
			},
			label : {
				background_color:null,
				sign:false,//设置禁用label的小图标
				padding:'0 4',
				border:{
					enable:false,
					color:'#666666'
				},
				fontsize:9,
				fontweight:600,
				color : '#4572a7'
			},
			border : {
				width : 0,
				color : '#ffffff'
			},
			listeners:{
				parseText:function(d, t){
					return d.get('value')+"%";//自定义label文本
				}
			} 
		},
		legend:{
			enable:true,
			padding:0,
			offsetx:120,
			offsety:50,
			color:'#3e576f',
			fontsize:10,//文本大小
			sign_size:10,//小图标大小
			line_height:14,//设置行高
			sign_space:5,//小图标与文本间距
			border:false,
			align:'left',
			background_color : null//透明背景
		},
		animation : true,//开启过渡动画
		animation_duration:1000,//1000ms完成动画 
		shadow : true,
		shadow_blur : 4,
		shadow_color : '#aaaaaa',
		shadow_offsetx : 0,
		shadow_offsety : 0,
		background_color: null,
		align:'right',//右对齐
		offsetx:-100,//设置向x轴负方向偏移位置60px
		offset_angle:-90,//逆时针偏移120度
		width : 620,
		height : 280,
		radius:100,
		border: 0,
		gradient: true
	});
	//利用自定义组件构造左侧侧说明文本
	chart.plugin(new iChart.Custom({
			drawFn:function(){
				//在右侧的位置，渲染说明文字
				chart.target.textAlign('start')
				.textBaseline('top')
				.textFont('300 10px Verdana')
				.fillText('有效实投量 = 全国通投 + 区域定向 + 北上广深',60,40,false,'#FF0000',false,12)
				.textFont('300 6px Verdana')
				.fillText('统计区间：<?= $this->listdata['中国']['bt']?> 至 <?= $this->listdata['中国']['et']?>',60,80,false,'#333333');
			}
	}));
	
	chart.draw();
});

$(function(){
	var data = [
        	{name : '品牌投放',value : <?= round($ori['pq']/($ori['yx']),4)*100?>,color:'#2F9649'},
        	{name : '非品牌投放',value : <?= round(1 - $ori['pq']/($ori['yx']),4)*100?>,color:'#49B6DE'},
        ];

	var chart = new iChart.Pie3D({
		render : 'canvasDivb',
		data: data,
		title : {
			text : '广告品牌投放对比 - [ <?= $_REQUEST['type'] == 1?'前贴':($_REQUEST['type'] == 2?'中贴':($_REQUEST['type'] == 3?'后贴':'暂停广告'))?> ]',
			height:20,
			fontsize : 15,
			color : '#282828'
		},
		footnote : {
			text : 'mads.56.com',
			color : '#336699',
			fontsize : 6,
			padding : '0 38'
		},
		sub_option : {
			mini_label_threshold_angle : 40,//迷你label的阀值,单位:角度
			mini_label:{//迷你label配置项
				fontsize:14,
				fontweight:600,
				color : '#ffffff'
			},
			label : {
				background_color:null,
				sign:false,//设置禁用label的小图标
				padding:'0 4',
				border:{
					enable:false,
					color:'#666666'
				},
				fontsize:9,
				fontweight:600,
				color : '#4572a7'
			},
			border : {
				width : 0,
				color : '#ffffff'
			},
			listeners:{
				parseText:function(d, t){
					return d.get('value')+"%";//自定义label文本
				}
			} 
		},
		legend:{
			enable:true,
			padding:0,
			offsetx:120,
			offsety:50,
			color:'#3e576f',
			fontsize:10,//文本大小
			sign_size:10,//小图标大小
			line_height:14,//设置行高
			sign_space:5,//小图标与文本间距
			border:false,
			align:'left',
			background_color : null//透明背景
		},
		animation : true,//开启过渡动画
		animation_duration:1000,//1000ms完成动画 
		shadow : true,
		shadow_blur : 4,
		shadow_color : '#aaaaaa',
		shadow_offsetx : 0,
		shadow_offsety : 0,
		background_color: null,
		align:'right',//右对齐
		offsetx:-100,//设置向x轴负方向偏移位置60px
		offset_angle:-90,//逆时针偏移120度
		width : 620,
		height : 280,
		radius:100,
		border: 0,
		gradient: true
	});
	//利用自定义组件构造左侧侧说明文本
	chart.plugin(new iChart.Custom({
			drawFn:function(){
				//在右侧的位置，渲染说明文字
				chart.target.textAlign('start')
				.textBaseline('top')
				.textFont('300 10px Verdana')
				.fillText('有效实投量 = 品牌投放 + 非品牌投放',60,40,false,'#FF0000',false,12)
				.textFont('300 6px Verdana')
				.fillText('统计区间：<?= $this->listdata['中国']['bt']?> 至 <?= $this->listdata['中国']['et']?>',60,80,false,'#333333');
			}
	}));
	
	chart.draw();
});
</script>
<br>
<?php include template('footer');?>
<?php }?>