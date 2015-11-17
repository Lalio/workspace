<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;    
}
/*
 * 子合同系统模板
 */
?>
<?php include template('header');?>
<script type="text/javascript" src="./script/js/WdatePicker.js?v=1"></script>
<script type="text/javascript" src="./script/js/order.js?v=1"></script>
<div class="ctr_panel">
	<br>
	<?php if($this->show == 'search') {?>
	<!-- 查找合同模块开始 -->
	<form id="searchform" method="get" action="">
	<input type="hidden" name="action" value="<?= $this->action?>">
	<input type="hidden" name="do" value="<?= $this->do?>">
	<input type="hidden" name="func" value="search">
	<input type="hidden" name="show" value="list">
	<table>
	    <tr>
	        <th colspan="2"><a href="javascript:;">广告合同查询</a></th>
	    </tr>
	    <tr>
			<td>合同编号</td>
			<td><input type="text" name="contract_id" size="50"  value="" ></td>
		</tr>
		<tr>
			<td>投放人员</td>
		    <td><input type="text" name="admin" size="40"  value="" ></td>
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
	
	<?php if($this->show=='add'||$this->show=='edit') {?>
	<form id="input_form">
	<table>
	    <tr>
	        <th colspan="3"><a href="javascript:;">合同管理</a></th>
	    </tr>
	    <tr>
			<td colspan="2">客户名称 </td>
			<td>
				<select name="customer_id" id="customer_id">
					<?php 
						foreach($this->clients as $k => $v){ 
							echo $k==$this->pagedata['editInfo'][0]['customer_id']?"<option value ='$k' selected>$v</option>":"<option value ='$k'>$v</option>";   
						} 
					?>
				</select>
				<input class="client_quicksearch" for_id="customer_id" type="text" value="快速翻查" onclick="this.value=''" onblur="this.value='快速翻查'" size="10">
			    <? if($this->show == 'edit') {?><input type="checkbox" name="sync[client]">(!) 批量同步<?}?>
			</td>		
		</tr>
		<tr>
			<td colspan="2" width="13%">默认标题 </td>
			<td><input type="text" name="title" size="80" value="<?= $this->pagedata['editInfo'][0]['title']?>">   <? if($this->show == 'edit') {?><input type="checkbox" name="sync[title]">(!) 批量同步<?}?></td>
		</tr>
		<tr>
			<td colspan="2">默认描述 </td>
			<td><input type="text" name="description" size="120"  value="<?= $this->pagedata['editInfo'][0]['description']?>" >   <? if($this->show == 'edit') {?><input type="checkbox" name="sync[des]">(!) 批量同步<?}?></td>
		</tr>
		<tr id="common">
			<td colspan="2" width="13%">合同编号  <a href="javascript:;" class="add_c_contract"><strong>[+]</strong></a></td>
			<td><input type="text" name="contract_id" id="contract_id" size="40"  value="<?= $this->pagedata['editInfo'][0]['contract_id']?$this->pagedata['editInfo'][0]['contract_id']:"S-AD".TIMESTAMP.makerandom(2)."_Auto"?>"></td>
		</tr>
		<!-- 
		<tr>
			<td>单价</td>
			<td>￥<input type="text" name="unit_price" size="10"  value="<?= $this->pagedata['editInfo'][0]['unit_price']?$this->pagedata['editInfo'][0]['unit_price']:0?>">元</td>
		</tr>
		<tr>
			<td>总价</td>
			<td>￥<input type="text" name="total_price" size="10"  value="<?= $this->pagedata['editInfo'][0]['total_price']?$this->pagedata['editInfo'][0]['total_price']:0?>">元</td>
		</tr>
		<tr>
			<td>日CPM</td>
			<td><input type="text" name="day_cpm" size="5"  value="<?= $this->pagedata['editInfo'][0]['day_cpm']?$this->pagedata['editInfo'][0]['day_cpm']:0?>"></td>
		</tr>
		<tr>
			<td>总CPM</td>
			<td><input type="text" name="total_cpm" size="5"  value="<?= $this->pagedata['editInfo'][0]['total_cpm']?$this->pagedata['editInfo'][0]['total_cpm']:0?>"></td>
		</tr>
		 -->
		<?php foreach($this->pagedata['editInfo'] as $s => $editinfo){?>
			<tr>
				<td colspan="2">
					子合同(#<?= $editinfo['id']?>)  <a href="javascript:;" class="sz_inputpanel" for_id="inputpanel_<?= $editinfo['id']?>">[╣]</a>
					<?php if($this->show == 'add'){?><a href="javascript:;" onclick="$(this).parent().parent().remove();"><strong>[-]</strong></a><? }?>
					<input type="hidden" name="id[]" value="<?= $editinfo['id']?>">
					<input type="hidden" name="related_aids[]" value="<?= $editinfo['related_aids']?>">
				</td>	
				<td style="font-size:9px;padding:12px">
					<div class="inputpanel" id="inputpanel_<?= $editinfo['id']?>" style="display:block">
						<hr>授权编码
						<input type="text" name="reserve_code[]" size="40" value="<?= $editinfo['reserve_code']?>" <?= $editinfo['reserve_code']?'class="input_green" readonly':''?>>
						
						<hr>备注信息
						<input type="text" name="remark[]" size="120" style="font-weight: bolder;font-style: italic;" value="<?= $editinfo['remark']?>">
						
						<hr>投放周期
						<input readonly type="text" class="input_Calendar" onclick="WdatePicker({startDate:'<?= date('Y-m-d',TIMESTAMP)?> 0:0:0',dateFmt:'yyyy-MM-dd HH:mm:ss',highLineWeekDay:true,readonly:true})" name="starttime[]" size="30" value="<?= $editinfo['starttime']?date('Y-m-d H:i:s',$editinfo['starttime']):''?>"> - <input readonly type="text" class="input_Calendar" onclick="WdatePicker({startDate:'<?= date('Y-m-d',TIMESTAMP)?> 0:0:0',dateFmt:'yyyy-MM-dd HH:mm:ss',highLineWeekDay:true,readonly:true})" name="endtime[]" size="30"  value="<?= $editinfo['endtime']?date('Y-m-d H:i:s',$editinfo['endtime']):''?>">   
						<input type="checkbox" name="sync[<?= $s?>][time]">(!) 批量同步
						
						<hr>投放位置
						<select name="ad_type[]" class="cid" id="cid_<?= $editinfo['id']?>" from_cid_id="cid_<?= $editinfo['id']?>" for_type_id="type_<?= $editinfo['id']?>">
							<?php 
								foreach($this->adstype as $k => $v){ 
									echo in_array($k,array($editinfo['ad_type']))?"<option value ='$k' selected>$v</option>":"<option value ='$k'>$v</option>";   
								} 
							?>
						</select>
						<input class="adtype_quicksearch" for_id="cid_<?= $editinfo['id']?>" id="cqk_<?= $editinfo['id']?>" type="text" value="快速翻查" onclick="this.value=''" onblur="this.value='快速翻查'" size="10">   
						<input type="checkbox" name="sync[<?= $s?>][type]">(!) 批量同步
						
						<hr>投放类型
						<select name="ad_sub_type[]" class="type" id="type_<?= $editinfo['id']?>" from_cid_id="cid_<?= $editinfo['id']?>" for_type_id="type_<?= $editinfo['id']?>">
							<?php 
								foreach($this->ad_sub_type as $k => $v){ 
									echo $k == $editinfo['ad_sub_type']?"<option value ='$k' selected>$v</option>":"<option value ='$k'>$v</option>";   
								} 
							?>
						</select>
						<input type="checkbox" name="sync[<?= $s?>][ad_sub_type]">(!) 批量同步
						
						<?php if(!empty($editinfo['id'])) {?>
							<hr>自定义监测代码-素材-投放排期表 
							<a href="./etc/xls/multicitys_v2_demo.xls">[排期模板]</a>
							<?= $this->GetAutoSchedule($editinfo['reserve_code'])?>
							<br>
							<input type="hidden" name="monitor_code[]" id="monitor_code_<?= $editinfo['id']?>" value='<?= $editinfo['monitor_code']?>'>
							<iframe name="ExlUpload" width="550" height="45" src="./?action=Order&do=MultiCitysUploadForm&for_id=<?= $editinfo['id']?>" scrolling="no" border="0" frameborder="0">浏览器不支持嵌入式框架，或被配置为不显示嵌入式框架。</iframe>
							<input type="checkbox" name="sync[<?= $s?>][code]">(!) 批量同步
							<br><?= $this->GetScheduleHistory($editinfo['id'],$editinfo['monitor_code'])?>
					    <?php }?>
					    
	        	    	<?php $aids = $this->GetAidsByCid($editinfo['id']);
	        	    	  if($aids != ''){?>
	        	    	  <hr>
	        	    	  	<a class="button white small" target="_blank" href="./?action=<?= $_GET['action']?>&do=Contract2aid&id=<?= $editinfo['id']?>">修改记录</a>
	        	    		<a class="button red small" target="_blank" href="./?action=Order&do=Transaction&func=search&show=list&aid=<?= $aids?>&vid=&title=&area=&starttime=&endtime=&link=&cid=&type=&username=&status=">广告订单</a>
	        	    		<?php $re = $this->GetRidByCid($editinfo['id']); if(!empty($re)){?>
	        	    			<a class="button orange small" target="_blank" href="./?action=Reserve&do=Rms&show=edit&reserve_id=<?= $re['reserve_id']?>&id=<?= $re['id']?>">预订单</a>
	        	    		<?php }?>
	        	    		<?php
	        					$o = $this->_db->rsArray("SELECT status FROM ad_reserve WHERE reserve_code = '$editinfo[reserve_code]' LIMIT 0,1");
								if($o['status'] == 4){?>
		            				<a class="button blue small" onclick="if(confirm('请确认该合同对应所有订单已全部下单成功，然后再释放库存资源，是否继续？')){asyn_trigger('./?action=Reserve&do=Release&reserve_code=<?= $editinfo['reserve_code']?>')}">下单完成</a>
		            		<?php }?>
	        	    	<?php }?>
	        	    </div>
        	    </td>
			</tr>
		<?php }?>
		<?php if($this->do == 'FromReserveToContract' || ADMIN == 'yang.zhong'){?>
		<tr>
			<td colspan="2">指派投放人员</td>
			<td>
				<select name="admin">
					<?php foreach($this->workers as $k=>$v){?>
						<option value="<?= $v?>" <?= $this->pagedata['editInfo'][0]['admin'] == $v?'selected':''?>><?= $k?></option>
					<?php }?>
				</select>
			</td>
		</tr>
		<?php }else{?>
		<tr>
			<td colspan="2">投放人员</td>
			<td>
				<input type="hidden" name="admin" value="<?= $this->pagedata['editInfo'][0]['admin']?$this->pagedata['editInfo'][0]['admin']:ADMIN?>" />
				<?= $this->pagedata['editInfo'][0]['admin']?$this->pagedata['editInfo'][0]['admin']:ADMIN?>
			</td>
		</tr>
		<?php }?>
		<tr>
			<td colspan="2">最后操作时间</td>
			<td><?= $this->pagedata['editInfo'][0]['ts']?date('Y-m-d H:i:s',$this->pagedata['editInfo'][0]['ts']):date('Y-m-d H:i:s',TIMESTAMP)?><input type="hidden" name="ts" value="<?= TIMESTAMP?>"></td>
		</tr>
		<tr>
			<td colspan="2">广告素材快速上传工具</td>
			<td>
				<input type="text" id="flv_url" size="80"  value="" ><a style="display:none" id="flv_url_preview" href="<?= $this->pagedata['editInfo'][0]['flv_url']?>" target="_blank">[预览]</a>
				<iframe name="FlvUpload" width="550" height="40" src="./?action=Order&do=FlvUploadForm" scrolling="no" border="0" frameborder="0">浏览器不支持嵌入式框架，或被配置为不显示嵌入式框架。</iframe>
			</td>
		</tr>
	    <tr>
			<td colspan="3" style="height:60px;text-align:center">
			    <span class="button blue" id="sbt_btn" onclick="asyn_sbt('input_form','./?action=Order&do=ContractV2&func=<?= $_GET[show]=='edit'?'edit':'add'?>')">确认提交</span>
			    <span class="button white" onclick="location.href='./?action=Order&do=ContractV2&show=list'">返回首页</span>
		    </td>
		</tr>
	</table>
	</form>
	<br />		
	<?php }?>
	<?php if($this->show=='list') {?>
	<table>
	<tr>
			<td colspan="6"><?php echo empty($this->pagedata['list'])?'-暂无数据-':$this->pagedata['sp']?></td>
		</tr>
		<tr>
			<th><a>合同编号</a></th>
			<th><a>默认标题</a></th>
			<!-- <th><a>默认描述</a></th> -->
			<th><a>客户名称</a></th>
			<th><a>投放人员</a></th>
			<th><a>最后操作时间</a></th>
			<th width="120px">
			<?php if($this->role != 'WORKER'){?>
				<input class="button green small" type="button" onclick="location.href='./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&show=add'" value="新增"/>
			<?php }?>
				<span class="button green small" onclick="location.href='./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&show=search'">搜索</span>
			</th>
		</tr>
		<?php foreach ($this->pagedata['list'] as $data) {?>
		<tr style="height: 65px">
			<td style="font-style: italic;text-align: center"><?= $data['contract_id']?></td>
			<td><?= $data['title']?></td>
			<!-- <td><?= $data['description']?></td-->
			<td><?= $this->clients[$data['customer_id']]?></td>
			<td><?= $data['admin']?></td>
			<td><?= date('Y-m-d H:i:s',$data['ts'])?></td>
	        <td style="text-align: center;">
        		<a class="button white medium" onclick="location.href='./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&show=edit&contract_id=<?= $data['contract_id']?>'">编辑合同</a>
        		<?php if(ROLE == 'DEVELOPER'){?>
	            	<!-- <a class="button white medium" onclick="location.href='./?action=Reserve&do=Basic&show=edit&id='">预订单信息</a> -->
	            <?php }?>
	        </td>
		</tr>
		<? }?>
		<tr>
			<td colspan="6"><?php echo empty($this->pagedata['list'])?'-暂无数据-':$this->pagedata['sp']?></td>
		</tr>
	</table>
	<br />		
	<?php }?>
</div>
<script type="text/javascript">
	function rtn(data){
		switch (data.rs){
			case 0:
			<?php if($this->show == 'add') {?>
				location.href = './?action=Order&do=ContractV2&show=list';
			<?php }else{?>
	    		$("#sbt_btn").attr('class','button green');
	    		$("#sbt_btn").html("操作成功");
		    	setInterval(function(){
					location.reload();
		    		//$("#sbt_btn").attr('class','button blue');
		    		//$("#sbt_btn").html("确认提交");
		    	},1000)
			<?php }?>
			break;
		}
	}
	<?php if($_REQUEST['id']){?>
	$('#inputpanel_<?= intval($_REQUEST['id'])?>').fadeIn('slow');
	<?php }?>
</script>
<?php
include template('footer');
?>
