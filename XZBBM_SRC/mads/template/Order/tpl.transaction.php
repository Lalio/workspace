<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;    
}
?>
<?php include template('header');?>
<script type="text/javascript" src="./script/js/WdatePicker.js"></script>
<script type="text/javascript" src="./script/js/order.js?v=104"></script>
<div class="ctr_panel">
	<br />
	<?php if($this->show == 'search') {?>
	<!-- 查单模块开始 -->
	<form id="searchform" method="get" action="">
	<input type="hidden" name="action" value="<?= $this->action?>">
	<input type="hidden" name="do" value="<?= $this->do?>">
	<input type="hidden" name="func" value="search">
	<input type="hidden" name="show" value="list">
	<table>
	    <tr>
	        <th colspan="2"><a href="javascript:;">广告订单查询</a></th>
	    </tr>
	    <tr>
			<td>订单ID</td>
			<td><input type="text" name="aid" size="50"  value="" >（多个ID请用英文,分隔）</td>
		</tr>
		<tr>
			<td>广告客户</td>
			<td>
				<select name="vid" id="vid">
					<option value=""></option>
					<?php 
						foreach($this->clients as $k => $v){ 
							echo $k==$this->pagedata['editInfo']['client']?"<option value ='$k' selected>$v</option>":"<option value ='$k'>$v</option>";   
						} 
					?>
				</select>
				<input class="client_quicksearch" for_id="vid" type="text" value="快速翻查" onclick="this.value=''" onblur="this.value='快速翻查'" size="10">
			</td>		
		</tr>
		<tr>
			<td>广告标题</td>
			<td><input type="text" name="title" size="40"  value="" ></td>
		</tr>
		<tr>
			<td>定向区域</td>
			<td><input type="text" name="area" size="40"  value="" ></td>
		</tr>
		<tr>
			<td>投放周期</td>
			<td><input type="text" class="input_Calendar" onclick="WdatePicker({startDate:'<?= date('Y-m-d',TIMESTAMP)?> 0:0:0',dateFmt:'yyyy-MM-dd HH:mm:ss',highLineWeekDay:true,readonly:true})" name="starttime" size="30" value=""> - <input type="text" class="input_Calendar" onclick="WdatePicker({startDate:'<?= date('Y-m-d',TIMESTAMP)?> 0:0:0',dateFmt:'yyyy-MM-dd HH:mm:ss',highLineWeekDay:true,readonly:true})" name="endtime" size="30"  value=""></td>
		</tr>
		<tr>
			<td>链接参数</td>
			<td><input type="text" name="link" size="40"  value="" ></td>
		</tr>
		<tr>
			<td>广告位置</td>
			<td>
				<select name="cid" class="cid" id="cid">
				    <option></option>
					<?php
						foreach($this->adstype as $k => $v){
							 echo $k==$this->pagedata['editInfo']['cid']?"<option value ='$k' selected>$v</option>":"<option value ='$k'>$v</option>";   
						} 
					?>
				</select>
				<input class="adtype_quicksearch" for_id="cid" type="text" value="快速翻查" onclick="this.value=''" onblur="this.value='快速翻查'" size="10" x-webkit-speech="true">
			</td>
		</tr>
		<tr>
			<td>广告类型</td>
			<td>
				<select name="type" id="type" class="type">
				    <option></option>
					<?php
						foreach($this->ad_sub_type as $k => $v){
							 echo $k==$this->pagedata['editInfo']['cid']?"<option value ='$k' selected>$v</option>":"<option value ='$k'>$v</option>";   
						} 
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td>投放人员</td>
		    <td><input type="text" name="username" size="40"  value="" ></td>
		</tr>
		<tr>
			<td>投放状态</td>
			<td>
				<select name="status">
				    <option></option>
					<option value="3">正在投放</option>
					<option value="4">预定中</option>
					<option value="2">暂停投放</option>
					<option value="1">投放结束</option>
				</select>
			</td>
		</tr>
	    <tr>
			<td colspan="2" style="height:60px;text-align:center">
				<span class="button blue" id="sbt_btn" onclick="searchform.submit();">搜 索</span>
			</td>
		</tr>
	</table>
	</form>
	<br />	
	<!-- 查单模块结束-->
	<?php }?>
	
	<?php if($_GET['show']=='add'||$_GET['show']=='edit') {
		//编辑格式预处理
		$edata = $this->pagedata['editInfo'];
	    $edata['display_hours'] = isset($edata['display_hours'])?explode(',',$edata['display_hours']):'';
		$edata['area'] = explode('_',$edata['area']);
		$edata['channel'] = explode('_',$edata['channel']);
		$edata['keyword'] = str_replace('_', ' ', $edata['keyword']);
		$edata['excludekeyword'] = str_replace('_', ' ', $edata['excludekeyword']);
		$edata['city'] = str_replace('_', ',', $edata['city']);
		$edata['excludearea'] = str_replace('_', ',', $edata['excludearea']); 
	?>
	<form id="input_form">
	<input type="hidden" name="aid" value="<?= $_GET['func']=='copy' || $_GET['show']=='add'?'':$_GET['aid']?>">
	<input type="hidden" name="enable" value="<?= isset($edata['enable'])?$edata['enable']:0?>">
	<input type="hidden" id="idf" name="idf" value="<?= $edata['idf']?>">
	<table>
		<tr><td>合同信息</td>
			<td>
				<select class="contract_selected" name="contractid">
					<option value="">-- 按合同生成 --</option>
				</select><br>
				<select class="contract_city">
					<option>-- 请选择子合同 --</option>
				</select>
				<input type="hidden" id="md_cid" value="<?= $this->cinfo['cid']?>" />
			</td>
		</tr>
		<?php if(isset($_GET['aid'])){?>
		<tr><td>广告编号 - <a href="#op_log">历史</a></td><td><?= $_GET['aid']?></td></tr>
		<?php }?>
		<tr>
			<td width="15%">广告客户</td>
			<td><select name="vid" id="vid">
					<option value=""></option>
					<?php 
						foreach($this->clients as $k => $v){ 
							echo $k == $edata['vid']?"<option value ='$k' selected>$v</option>":"<option value ='$k'>$v</option>";   
						} 
					?>
				</select>
				<input class="client_quicksearch" for_id="vid" type="text" value="快速翻查" onclick="this.value=''" onblur="this.value='快速翻查'" size="10">
			</td>
		</tr>
		<tr><td>投放广告位置</td>
			<td><select name="cid" class="cid" id="cid">
				    <option></option>
					<?php
						foreach($this->adstype as $k => $v){
							 echo $k == $edata['cid']?"<option value ='$k' selected>$v</option>":"<option value ='$k'>$v</option>";   
						} 
					?>
				</select>
				<input class="adtype_quicksearch" for_id="cid" type="text" value="快速翻查" onclick="this.value=''" onblur="this.value='快速翻查'" size="10">
			</td>
		</tr>
		<tr><td>广告类型</td>
			<td>
				<select name="type" id="type" class="type">
				    <option></option>
					<?php foreach($this->ad_sub_type as $k => $v){ ?>
						<option value ='<?= $k?>'<?= $k == $edata['type']?' selected':''?>><?= $v?></option>
					<?php } ?> 
				</select>
			</td>
		</tr>
		<tr><td>广告标题</td><td><input type="text" name="title" size="50" id="title" value="<?= $edata['title']?>"></td></tr>
		<tr><td>广告文案</td><td><input type="text" name="description" size="100" id="description" value="<?= $edata['description']?>"></td></tr>
		<tr><td>贴片_FLV地址</td><td><input type="text" name="tp_flv" id="flv_url" size="110" value="<?= $edata['tp_flv']?>"></td></tr>
		<tr><td>贴片_曝光监测URL</td><td><input type="text" name="tp_viewurl" id="tp_viewurl" size="110" value="<?= $edata['tp_viewurl']?>"></td></tr>
		<tr><td>贴片_跳转监测URL</td><td><input type="text" name="tp_click" id="tp_click" size="110" value="<?= $edata['tp_click']?>"></td></tr>
		<tr><td>广告素材</td><td><input id="flash" type="text" name="flash" size="110" value="<?= $edata['flash']?>">   <select class="ad_templates_selected" onchange="$('#flash').attr('value',this.value)"><option value="">-- 选择广告模板 --</option></select></td></tr>
		<tr><td>投放周期</td>
			<td>
				<input type="text" class="input_Calendar" onclick="WdatePicker({startDate:'<?= date('Y-m-d',TIMESTAMP)?> 0:0:0',dateFmt:'yyyy-MM-dd HH:mm:ss',highLineWeekDay:true,readonly:true})" name="starttime" size="30" value="<? if(isset($edata['starttime'])) echo date('Y-m-d H:i:s',$edata['starttime'])?>" id="starttime"> - 
            	<input type="text" class="input_Calendar" onclick="WdatePicker({startDate:'<?= date('Y-m-d',TIMESTAMP)?> 0:0:0',dateFmt:'yyyy-MM-dd HH:mm:ss',highLineWeekDay:true,readonly:true})" name="endtime" size="30" value="<? if(isset($edata['endtime'])) echo date('Y-m-d H:i:s',$edata['endtime'])?>" id="endtime">
            </td>
        </tr>
		<tr><td>今日投放量</td><td><input type="text" id="cpm" name="cpm" size="4" value="<?= $edata['cpm']?>">CPM/天 (-1：不投放 0:任意投 >0:峰值投放)  <font color="orange">(!) 手动修改投放周期、日CPM量则排期功能自动失效，再次生效需重新绑定合同。</font></td></tr>
		<tr id="schedule">
			<td>投放排期表</td>
			<td>
				<input type="hidden" name="schedule" id="schedule" value="">
				<iframe id="schedual_preview" width="500" height="220" src="../admin3/?action=Order&do=Schedule&aid=<?= $_GET['aid']?>" scrolling="yes" border="0">浏览器不支持嵌入式框架，或被配置为不显示嵌入式框架。</iframe>
				<script type="text/javascript">
				function scheduleupload(ct){
					$("#schedule").attr("value",ct);
					$("#schedual_preview").attr("src","../admin3/?action=Order&do=Schedule&msg="+ct);
				}
				</script>
			</td>
		</tr>
		<tr id="link1">
			<td>链接地址 <a href="javascript:;" class="input2param button small white">参数<->面板</a></td>
			<td>
				<span id="ad_cmd_panel" style="display:none">
						时长<input type="text" name="link_para[sec]" size="3" value="15">秒
						频控<input type="text" name="link_para[freq]" size="3">天
						公共频控ID<input type="text" name="link_para[pub_freq]" size="3"><hr>
						优先级<input type="text" name="link_para[level]" size="3">
						互斥ID<input type="text" name="link_para[pub_show]" size="3">
						类型<select size="1" name="link_para[tpl]">
							<option value="#tpl_flv">FLV格式</option>
							<option value="#tpl_swf">SWF格式</option>
						</select>
						展示方式<select size="1" name="link_para[show]">
							<option value="#one_tick">单贴展示</option>
							<option value="#double_tick" selected>多贴展示</option>
						</select><hr>
						其他：
						性别覆盖<select size="1" name="link_para[sex]">
							<option value='' selected>默认</option>
							<option value="#sex_male">仅投男性</option>
							<option value="#sex_female">仅投女性</option>
						</select>
						年龄覆盖<input type="text" name="link_para[age_s]" size="1">-<input type="text" name="link_para[age_n]" size="1"><hr>
						时长覆盖<input type="text" name="link_para[time]" size="3">
						<input class="small button blue" type="button" value="确定" id="gc_cmd" onclick="$('#ad_cmd_input').toggle();$('#ad_cmd_panel').toggle();">
				</span>
				<span id="ad_cmd_input">
					<input id="link" type="text" name="link" size="110" value="<?= $edata['link']?>">
				</span>
			</td>
		</tr>
		<tr><td>广告代码<a href="javascript:;" class="input2code button small white">代码<->面板</a></td>
			<td>
				<?php $htmlcode = json_decode($edata['google'],true);
					if(!isset($htmlcode['img'])){
						if(isset($htmlcode['file'])){
							$htmlcode['img'] = $htmlcode['file'];
						}
					}
				?>
				<div id="ad_js_panel" style="display: none;">
					<div id="p_img"> 
					图片地址<input type="text" size="80" name="p_img" value="<?= $htmlcode['img_url']?str_replace("http://acs.56.com/redirect/view/{$_REQUEST['aid']}/",'', $htmlcode['img_url']):str_replace("http://acs.56.com/redirect/view/{$_REQUEST['aid']}/",'', $htmlcode['img'])?>"><br>
					</div>
					<div id="p_img_href">
					图片链接<input type="text" size="80" name="p_img_href" value="<?= $htmlcode['img_link']?str_replace("http://acs.56.com/redirect/click/{$_REQUEST['aid']}/",'', $htmlcode['img_link']):str_replace("http://acs.56.com/redirect/click/{$_REQUEST['aid']}/",'', $htmlcode['url'])?>"><br>
					</div>
					<div id="p_flash">
					flash地址<input type="text" size="80" name="p_flash" value="<?= str_replace("http://acs.56.com/redirect/view/{$_REQUEST['aid']}/", '', $htmlcode['flash_url'])?>"><br>
					</div>
					<div id="p_sub_title_img">
					副标题图片<input type="text" size="80" name="p_sub_title_img" value="<?= $htmlcode['sub_title_img']?>"><br>
					</div>
					<div id="p_sub_title">
					副标题文字 <input type="text" size="30" name="p_sub_title" value="<?= $htmlcode['sub_title']?>"><br>
					</div>
					<div id="p_sub_title_link">
					副标题链接 <input type="text" size="30" name="p_sub_title_link" value="<?= $htmlcode['sub_title_link']?>"><br>
					</div>
					<div id="p_title">
					文字标题<input type="text" size="30" name="p_title" value="<?= $htmlcode['title']?>"><br>
					</div>
					<div id="p_title_href">
					标题链接<input type="text" size="80" name="p_title_href" value="<?= str_replace("http://acs.56.com/redirect/click/{$_REQUEST['aid']}/", '', $htmlcode['title_link'])?>"><br>
					</div>
					<div id="p_swf">
					视频地址(用于自动同步播放数、评论数、视频时长)<input type="text" size="80" name="p_swf" value="<?= $htmlcode['swf']?>"><br>
					</div>
					<!-- 
					视频链接<input type="text" size="80" name="p_swf_href" value="<?= $htmlcode['swf_href']?>"><br>	
					<hr>
					-->
					<div id="p_views">
					播放数      <input type="text" size="10" name="p_views" value="<?= $htmlcode['views']?>"><br>
					</div>
					<div id="p_comments">
					评论数      <input type="text" size="10" name="p_comments" value="<?= $htmlcode['comments']?>"><br>
					</div>
					<div id="p_totaltime">
					视频时长<input type="text" size="20" name="p_totaltime" value="<?= $htmlcode['totaltime']?>">秒<br>
					</div>
					<div id="p_type">
					广告类型  <select name="p_type"><option value="1" <?= $htmlcode['type'] == 1?'checked':''?>>视频</option><option value="2" <?= $htmlcode['type'] == 2?'selected':''?>>广告链接</option></select>
					</div>
					<div id="p_p2c_commit">
					<input class="small button green" type="button" id="gc_js" value="确定">
					</div>
				</div>
				<div id="ad_js_input" style="display: block;">
					<textarea name="google" cols="100" rows="5" id="google"><?= $edata['google']?></textarea>
				</div>
			</td>
		</tr>
		<tr><td>投放频道</td>
			<td>
				<input class="checkall" type="checkbox" id="channels"><a>全选/全不选</a></br>
				<? foreach(Core::$vars['Channel'] as $k => $v){ ?>
					<input type="checkbox" class="channels" name="channel[]" value="<?= $k?>" <?= in_array($k, $edata['channel'])?'checked':''?> id="<?= "channel_$v";?>"><label for="<?= "channel_$v";?>"><?= $v?></label>
				<? }?>
			</td>
		</tr>
		<tr><td>投放关键字</td><td><input type="text" name="keyword" size="80" value="<?= $edata['keyword']?>">(关键字之间请用空格符间隔，例如：美女 视频 周杰伦)</td></tr>
		<tr><td>屏蔽关键字</td><td><input type="text" name="excludekeyword" size="80" value="<?= $edata['excludekeyword']?>">(关键字之间请用空格符间隔，例如：美女 视频 周杰伦)</td></tr>
		<tr><td>投放地区</td>
			<td><input class="checkall" type="checkbox" id="area"><a>全选/全不选</a><br>
			<? foreach(Core::$vars['province'] as $data) {?>
				<input type="checkbox" class="area" name="area[]" value="<?= $data?>" <?= in_array($data, $edata['area'])?'checked':''?> id="<?= "area_$data";?>"><label for="<?= "area_$data";?>"><?= $data?></label>
			<? }?>
			</td>
		</tr>
		<tr><td>自定义投放区域:</td>
			<td>
				<textarea rows="3" cols="130" name="city" class="city_input" id="city"><?= $edata['city']?></textarea><br />
				<select class="province_selected" related_id="01"><option>-- 请选择省份 --</option></select>
			    <select class="city_selected" id="01" related_id="city"><option>-- 请选择城市 --</option></select>
			    (城市之间请用英文 , 分隔)
			</td>
		</tr>
		<tr><td>自定义屏蔽区域</td>
			<td>
				<textarea rows="3" cols="130" name="excludearea" class="city_input" id="excludearea"><?= $edata['excludearea']?></textarea><br />
				<select class="province_selected" related_id="02"><option>-- 请选择省份 --</option></select>
			    <select class="city_selected" id="02" related_id="excludearea"><option>-- 请选择城市 --</option></select>
			    (城市之间请用英文 , 分隔)
			</td>
		</tr>
		<tr><td>投放权重</td><td><input type="text" name="weight" size="5" value="<?= $edata['weight']?$edata['weight']:10000?>"></td></tr>
		<tr><td>投放频率(每人每天)</td><td><input type="text" name="freq" size="2" value="<?= $edata['freq']?>">次 (0表示不限制)</td></tr>
		<tr><td>投放时刻控制</td>
			<td>
				<input class="checkall" type="checkbox" id="display_hours"><a>全选/全不选</a><br>
				<? for($i=0;$i<24;$i++) {?>
					<input type="checkbox" class="display_hours" name="display_hours[]" value="<?= $i?>" <?= isset($edata['display_hours']) && in_array((string)$i, $edata['display_hours'])?'checked':''?>><?= $i?>
				<? }?>
				<br>
			</td>
		</tr>
		<tr><td>单位小时最大投放</td><td><input type="text" name="hour_cpm" size="4" value="<?= $edata['hour_cpm']?>">CPM (0表示不限制)</td></tr>
		<!-- 
		<tr><td>当前执行金额</td><td><input type="text" name="fee" size="5" value="<?= $edata['fee']?>">元/天 或 <input type="text" name="fee_cpm" value="0" size="2" value="<?= $edata['fee_cpm']?>">元/CPM (0表示不统计执行额)</td></tr>
		<tr><td>执行额备注</td><td><input type="text" name="memo" size="80" value="<?= $edata['memo']?>"></td></tr>
		<tr><td>联动广告ID</td><td><input type="text" name="linkid" size="5"  value="<?= $edata['linkid']?$edata['linkid']:0?>"> (缺省0不联动)</td></tr>
		 -->
		<tr><td>其他设置</td><td>均匀投放<input type="checkbox" name="isavg" <?= $edata['isavg']==1?'checked':''?>>   润成投放<input type="checkbox" name="maxavg" <?= $edata['maxavg']==1?'checked':''?>></td></tr>
	    <tr id="op_log">
			<td colspan="2" style="height:60px;text-align:center">
				<span class="sbt_btn">
					<span class="button blue" id="sbt_btn" onclick="asyn_sbt_uncheck('input_form','./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&func=<?= $_GET[show]=='add' || $_GET['func']=='copy'?'add':'edit'?>')">确认提交</span>
				</span>
				<span class="op_area" style="display: none">
					<span class="op_rs" style="font-size:20px;font-weight:bold;"></span><br>
					<span class="op_rs_cp button medium green" onclick="location.href='./?action=Order&do=Transaction&show=edit&aid='+$('.op_rs_cp').attr('aid')+'&func=copy'">复制当前</span>
					<span class="button medium orange" onclick="location.href='./?action=Order&do=Transaction&show=add'">继续添加</span>
					<span class="button medium white" onclick="location.href='./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&show=list'">回到首页</span>
				</span>
			</td>
		</tr>
		<?php if($this->show == 'edit' && $this->func != 'copy'){?>
		<? foreach ($this->pagedata['logs'] as $data) {?>
			<tr>
				<td><font color="red">(!) <?= $data['logtime']?></font></td>
				<td>[<?= get_mid_chars($data['log'], '[', ']')?>] <strong>(<?= $data['username']?>)</strong> <br>
					<?= !isset($data['detail']['title'])?'':'广告标题(title):'.$data['detail']['title'].'</br>'?>
					<?= !isset($data['detail']['description'])?'':'广告描述(description):'.$data['detail']['description'].'</br>'?>
					<?= !isset($data['detail']['cid'])?'':'广告位置(cid):'.$this->adstype[$data['detail']['cid']].'</br>'?>
					<?= !isset($data['detail']['vid'])?'':'广告客户(vid):'.$this->clients[$data['detail']['vid']].'</br>'?>
					<?= !isset($data['detail']['type'])?'':'广告类型(type):'.$this->ad_sub_type[$data['detail']['type']].'</br>'?>
					<?= !isset($data['detail']['cpm'])?'':'投放量(cpm):'.$data['detail']['cpm'].'</br>'?>
					<?= !isset($data['detail']['flash'])?'':'素材/模板(flash):'.$data['detail']['flash'].'</br>'?>
					<?= !isset($data['detail']['link'])?'':'链接/参数(link):'.$data['detail']['link'].'</br>'?>
					<?= !isset($data['detail']['keyword'])?'':'关键词(keyword):'.syssubstr($data['detail']['keyword'], 150).'</br>'?>
					<?= !isset($data['detail']['excludekeyword'])?'':'屏蔽关键词(excludekeyword):'.syssubstr($data['detail']['excludekeyword'],150).'</br>'?>
					<?= !isset($data['detail']['area'])?'':'定向区域(area):'.$data['detail']['area'].'</br>'?>
					<?= !isset($data['detail']['excludearea'])?'':'屏蔽区域(excludearea):'.$data['detail']['excludearea'].'</br>'?>
					<?= !isset($data['detail']['weight'])?'':'权重(weight):'.$data['detail']['weight'].'</br>'?>
					<?= !isset($data['detail']['starttime'])?'':'开始时间(starttime):'.date('Y-m-d H:i:s',$data['detail']['starttime']).'</br>'?>
					<?= !isset($data['detail']['endtime'])?'':'结束时间(endtime):'.date('Y-m-d H:i:s',$data['detail']['endtime']).'</br>'?>
					<?= !isset($data['detail']['tp_flv'])?'':'素材地址(tp_flv):'.syssubstr($data['detail']['tp_flv'],150).'</br>'?>
					<?= !isset($data['detail']['tp_viewurl'])?'':'第三方曝光监测(tp_viewurl):'.syssubstr($data['detail']['tp_viewurl'],150).'</br>'?>
					<?= !isset($data['detail']['tp_click'])?'':'第三方点击监测(tp_click):'.syssubstr($data['detail']['tp_click'],150).'</br>'?>
				</td>
			</tr>
		<?php }?>
		<?php }?>
	</table>
	</form>
	<script type="text/javascript">
	function rtn(data){
		switch (data.rs){
			case 0:
				<?php if($this->show == 'add' || $this->func == 'copy'){?>
					$(".op_rs").html('新增广告成功 广告ID为'+data.msg);
					$(".op_rs_cp").attr('aid',data.msg);
					//$(".op_rs_cp").attr('onclick',"location.href='./?action=Order&do=Transaction&show=edit&aid="+data.msg+"&func=copy'");
					$(".sbt_btn").toggle();
					$(".op_area").toggle();
				<?php }else{?>
					$("#sbt_btn").html('修改成功');
					$("#sbt_btn").attr('class','button green');
			    	setInterval(function(){
				    	location.reload();
			    	},1000)
		    	<?php }?>
			break;
		}
	}
	document.onkeydown = function(e){ 
	    var ev = document.all ? window.event : e;
	    if(ev.keyCode==13) {
	    	asyn_sbt_uncheck('input_form','./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&func=<?= $_GET[show]=='add' || $_GET['func']=='copy'?'add':'edit'?>');
	     }
	}
	</script>
	<br />		<!-- 关闭空的html标签 -->
	<div class="ad_preview" style="display:none; position:fixed; right:14px; top:50px; z-index: 3"></div>
	<?php }?>
	
	<?php if($this->show == 'list') {?>
	<table>
		<tr>
			<td colspan="10"><?php echo empty($this->pagedata['list'])?'-暂无数据-':$this->pagedata['sp']?></td>
		</tr>
		<tr>
			<th width="70px"><input class="checkall" type="checkbox" id="aids"><a href="./?<?= str_replace('orderby', '', $_SERVER['QUERY_STRING']).'&orderby=aid'?>">ID</a></th>
			<th width="70px"><a>状态</a></th>
			<th width="200px"><a href="./?<?= str_replace('orderby', '', $_SERVER['QUERY_STRING']).'&orderby=cid'?>">位置/类型</a></th>
			<!-- <th width="65px"><a href="./?<?= str_replace('orderby', '', $_SERVER['QUERY_STRING']).'&orderby=type'?>">类型</a></th> -->
			<!-- <th width="10%"><a>客户</a></th> -->
			<th><a href="./?<?= str_replace('orderby', '', $_SERVER['QUERY_STRING']).'&orderby=title'?>">标题</a></th>
			<!-- <th><a>投放频道</a></th> -->
			<th width="60px"><a href="./?<?= str_replace('orderby', '', $_SERVER['QUERY_STRING']).'&orderby=city'?>">地区</a></th>
			<th width="70px"><a href="./?<?= str_replace('orderby', '', $_SERVER['QUERY_STRING']).'&orderby=keyword'?>">关键词</a></th>
			<th width="125px"><a href="./?<?= str_replace('orderby', '', $_SERVER['QUERY_STRING']).'&orderby=starttime'?>">投放周期</a></th>
			<th width="90px"><a>预定/当前/昨日（CPM）</a></th>
			<th width="80px"><a>当前/昨日（点击）</a></th>
			<th width="240px"><a>操作</a></th>
		</tr>
		<?php foreach ($this->pagedata['list'] as $data) {
			
			$where_t = "WHERE aid = $data[aid] AND year = ".date('Y',TIMESTAMP)." AND month = ".date('n',TIMESTAMP)." AND day = ".date('j',TIMESTAMP);
			$where_y = "WHERE aid = $data[aid] AND year = ".date('Y',(TIMESTAMP-86400))." AND month = ".date('n',(TIMESTAMP-86400))." AND day = ".date('j',(TIMESTAMP-86400));
			
			$yd = $this->_db->rsArray("SELECT sum(view) as views FROM ad_log $where_y");
			$yd += $this->_db->rsArray("SELECT sum(click) as clicks FROM ad_log $where_y");
			$td = $this->_db->rsArray("SELECT sum(view) as views FROM ad_log $where_t");
			$td += $this->_db->rsArray("SELECT sum(click) as clicks FROM ad_log $where_t");
		?>
		<tr>
			<td><a href="javascript:;"><input class="aids" id="<?= $data['aid']?>" value="<?= $data['aid']?>" type="checkbox"><?= $data['aid']?></a></td>
			<td>
		        <? 
					if($data['status'] == 3){
					    echo '<span style="color:forestgreen;cursor:pointer;font-family:华文雅黑;" onclick="location.href=\'./?action=Order&do=Transaction&func=search&show=list&aid=&vid=&title=&area=&starttime=&endtime=&link=&cid=&type=&username=&status=3\'">正在投放</span>';
	                }
	                if($data['status'] == 4){
	                	echo '<span style="color:mediumblue;cursor:pointer;font-family:华文雅黑;" onclick="location.href=\'./?action=Order&do=Transaction&func=search&show=list&aid=&vid=&title=&area=&starttime=&endtime=&link=&cid=&type=&username=&status=4\'">预订投放</span>';
	                }
	                if($data['status'] == 2){
	                	echo '<span style="color:red;cursor:pointer;font-family:华文雅黑;" onclick="location.href=\'./?action=Order&do=Transaction&func=search&show=list&aid=&vid=&title=&area=&starttime=&endtime=&link=&cid=&type=&username=&status=2\'">暂停投放</span>';
	                }
	                if($data['status'] == 1){
	                	echo '<span style="color:grey;cursor:pointer;font-family:华文雅黑;" onclick="location.href=\'./?action=Order&do=Transaction&func=search&show=list&aid=&vid=&title=&area=&starttime=&endtime=&link=&cid=&type=&username=&status=1\'">投放结束</span>';
	                }
		        ?>
	        </td>
			<td>
				<a href="javascript:;" onclick="location.href='./?action=Order&do=Transaction&func=search&show=list&aid=&vid=&title=&area=&starttime=&endtime=&link=&cid=<?= $data['cid']?>&type=&ae='"><?= $this->adstype[$data['cid']]?></a><br>
				<a href="javascript:;" onclick="location.href='./?action=Order&do=Transaction&func=search&show=list&aid=&vid=&title=&area=&starttime=&endtime=&link=&cid=&type=<?= $data['type']?>&ae='"><font style="font-size:11px;color:green;">[<?= $this->ad_sub_type[$data['type']]?>]</font></a>
			</td>
			<!-- <td><a href="javascript:;" onclick="location.href='./?action=Order&do=Transaction&func=search&show=list&aid=&vid=<?= $data['vid']?>&title=&area=&starttime=&endtime=&link=&cid=&type=&ae='"><?= $this->clients[$data['vid']]?></a></td> -->
			<td><?= $data['title']?></td>
			<!-- <td><?= str_replace('_', '<br>', $data['channel'])?></td> -->
	        <td><textarea rows="3" cols="6"><?= str_replace("_", "\r\n", $data['city'].$data['area'])?></textarea></td>
	        <td><textarea rows="3" cols="11"><?= str_replace("_", "\r\n", $data['keyword'])?></textarea></td>
	        <td>开始：<br><?= date('Y-m-d H:i:s',$data['starttime'])?><br />结束：<br><?= date('Y-m-d H:i:s',$data['endtime'])?></td>
	        <td>
	        	<?= $data['cpm']?>/<b><?= intval($td['views']/1000)?></b>/<?= intval($yd['views']/1000)?> 
	        	<br><a class="view_detail" href="javascript:;" aid="<?= $data['aid']?>" ts="<?= TIMESTAMP?>"><font style="font-size: 11px">[曝光量走势]</font></a>
	        </td>
	        <td><b><?= $td['clicks']?$td['clicks']:'-'?></b>/<?= $yd['clicks']?$yd['clicks']:'-'?> 
	        	<br><a class="click_detail" href="javascript:;" aid="<?= $data['aid']?>" ts="<?= TIMESTAMP?>"><font style="font-size: 11px">[点击量走势]</font></a>
	        </td>
	        <td style="text-align: center" class="order_op_panel">
	            <span class="button white  small" onclick="location.href='./?action=Order&do=Transaction&show=edit&aid=<?= $data['aid']?>'">修改</span>
        	    <span class="button green small" onclick="location.href='./?action=Order&do=Transaction&show=edit&aid=<?= $data['aid']?>&func=copy'">复制</span>
                <span class="button white small" onclick="location.href='./?action=Order&do=StatisticsPage&aid=<?= $data['aid']?>'">数据</span>
        		<span class="ad_preview button white small" aid="<?= $data['aid']?>">预览</span>
	        	<div style="font-size:11px;margin:10px auto auto 10px;text-align:left;">
		        	投放授权编码：
		        	<?php $sq = $this->GetCidByAid($data['aid']);?>
		       		<?= empty($sq['reserve_code'])?'无对应投放授权':"<a href='./?action=Order&do=ContractV2&show=edit&contract_id={$sq['contract_id']}&id={$sq['id']}' target='_blank'>{$sq['reserve_code']}</a>"?>
	        	</div>
	        </td>
		</tr>
		<?php }?>
		<tr>
			<td colspan="10"><?php echo empty($this->pagedata['list'])?'-暂无数据-':$this->pagedata['sp']?></td>
		</tr>
	</table>
	<br />		<!-- 关闭空的html标签 -->
	<table>
		<tr>
			<td>贴片广告控制参数批量添加</td>
			<td><input type="text" size="10" value="#pub_show" class="multi_avg_cmd"></td>
			<td><input type="text" size="60" value="" class="multi_avg_aids">(多个AID请用 , 分隔，例如 11801,11802,11803,11804)</td>
			<td><span class="button black small multi_avg_cmt">执行</span></td>
		</tr>
	</table>
	<?php }?>
</div>
<div class="order_ctr_panel">
	<span class="button red medium" onclick="location.href='./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&show=add'">发布广告</span>
	<span class="button green medium" onclick="location.href='./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&show=search'">搜索广告</span>
	<?php if($this->show == 'list'){?>
	<a href="javascript:;" class="multistop button black medium">暂停投放</a>
	<a href="javascript:;" class="multirestart button blue medium">恢复投放</a>
	<?php }?>
</div>
<?php
include template('footer');
?>