<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;    
}
?>
<?php include template('header');?>
<script type="text/javascript" src="./script/js/WdatePicker.js"></script>
<script type="text/javascript" src="./script/js/order.js?v=101"></script>
<div class="ctr_panel">
	<br>
	<?php if($this->show=='add'||$this->show=='edit') {?>
	<form id="input_form">
	<input type="hidden" name="id" value="<?= $_GET['id']?>">
	<input type="hidden" name="related_aids" value="<?= $this->pagedata['editInfo']['related_aids']?>">
	<table>
	    <tr>
	        <th colspan="2"><a href="javascript:;">合同管理</a></th>
	    </tr>
	    <tr>
			<td>预订单授权编码</td>
			<td><input type="text" name="reserve_code" size="40"  value="<?= $this->pagedata['editInfo']['reserve_code']?>"></td>
		</tr>
		<tr>
			<td>合同编号</td>
			<td><input type="text" name="contract_id" size="40"  value="<?= $this->pagedata['editInfo']['contract_id']?$this->pagedata['editInfo']['contract_id']:"S-AD".TIMESTAMP.makerandom(2)."_Auto"?>"></td>
		</tr>
		<tr>
			<td>签注</td>
			<td><input type="text" name="remark" size="120"  value="<?= $this->pagedata['editInfo']['remark']?>"></td>
		</tr>
		<tr>
			<td>客户名称</td>
			<td>
				<select name="customer_id" id="customer_id">
					<?php 
						foreach($this->clients as $k => $v){ 
							echo $k==$this->pagedata['editInfo']['customer_id']?"<option value ='$k' selected>$v</option>":"<option value ='$k'>$v</option>";   
						} 
					?>
				</select>
				<input class="client_quicksearch" for_id="customer_id" type="text" value="快速翻查" onclick="this.value=''" onblur="this.value='快速翻查'" size="10">
			    <? if($this->show == 'edit') {?><input type="checkbox" name="sync[client]">批量同步<?}?>
			</td>		
		</tr>
		<tr>
			<td>单价</td>
			<td>￥<input type="text" name="unit_price" size="10"  value="<?= $this->pagedata['editInfo']['unit_price']?$this->pagedata['editInfo']['unit_price']:0?>">元</td>
		</tr>
		<tr>
			<td>总价</td>
			<td>￥<input type="text" name="total_price" size="10"  value="<?= $this->pagedata['editInfo']['total_price']?$this->pagedata['editInfo']['total_price']:0?>">元</td>
		</tr>
		<tr>
			<td>日CPM</td>
			<td><input type="text" name="day_cpm" size="5"  value="<?= $this->pagedata['editInfo']['day_cpm']?$this->pagedata['editInfo']['day_cpm']:0?>"></td>
		</tr>
		<tr>
			<td>总CPM</td>
			<td><input type="text" name="total_cpm" size="5"  value="<?= $this->pagedata['editInfo']['total_cpm']?$this->pagedata['editInfo']['total_cpm']:0?>"></td>
		</tr>
		<tr>
			<td>投放周期</td>
			<td><input type="text" class="input_Calendar" onclick="WdatePicker({startDate:'<?= date('Y-m-d',TIMESTAMP)?> 0:0:0',dateFmt:'yyyy-MM-dd HH:mm:ss',highLineWeekDay:true,readonly:true})" name="starttime" size="30" value="<?= $this->pagedata['editInfo']['starttime']?date('Y-m-d H:i:s',$this->pagedata['editInfo']['starttime']):''?>"> - <input type="text" class="input_Calendar" onclick="WdatePicker({startDate:'<?= date('Y-m-d',TIMESTAMP)?> 0:0:0',dateFmt:'yyyy-MM-dd HH:mm:ss',highLineWeekDay:true,readonly:true})" name="endtime" size="30"  value="<?= $this->pagedata['editInfo']['endtime']?date('Y-m-d H:i:s',$this->pagedata['editInfo']['endtime']):''?>">   <? if($this->show == 'edit') {?><input type="checkbox" name="sync[time]">批量同步<?}?></td>
		</tr>
		<tr>
			<td>默认标题</td>
			<td><input type="text" name="title" size="60" value="<?= $this->pagedata['editInfo']['title']?>">   <? if($this->show == 'edit') {?><input type="checkbox" name="sync[title]">批量同步<?}?></td>
		</tr>
		<tr>
			<td>默认描述</td>
			<td><input type="text" name="description" size="120"  value="<?= $this->pagedata['editInfo']['description']?>" >   <? if($this->show == 'edit') {?><input type="checkbox" name="sync[des]">批量同步<?}?></td>
		</tr>
		<tr>
			<td>投放位置</td>
			<td>
				<select name="ad_type" class="cid" id="cid">
					<?php 
						foreach($this->adstype as $k => $v){ 
							echo in_array($k,array($this->pagedata['editInfo']['ad_type']))?"<option value ='$k' selected>$v</option>":"<option value ='$k'>$v</option>";   
						} 
					?>
				</select>
				<input class="adtype_quicksearch" for_id="cid" type="text" value="快速翻查" onclick="this.value=''" onblur="this.value='快速翻查'" size="10">   <? if($this->show == 'edit') {?><input type="checkbox" name="sync[type]">批量同步<?}?>
			</td>
		</tr>
		<tr>
			<td>投放类型</td>
			<td>
				<select name="ad_sub_type" id="type" class="type">
					<?php 
						foreach($this->ad_sub_type as $k => $v){ 
							echo $k == $this->pagedata['editInfo']['ad_sub_type']?"<option value ='$k' selected>$v</option>":"<option value ='$k'>$v</option>";   
						} 
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td>监测代码、素材、排期表批量导入 <a href="./etc/xls/multicitys_demo.xls">[范例]</a></td>
			<td><input type="hidden" name="monitor_code" id="monitor_code" value='<?= stripslashes($this->pagedata['editInfo']['monitor_code'])?>'>
				<iframe name="ExlUpload" width="650" height="45" src="./?action=Order&do=MultiCitysUploadForm" scrolling="no" border="0" frameborder="0">浏览器不支持嵌入式框架，或被配置为不显示嵌入式框架。</iframe>
				<?= $this->pagedata['editInfo']['monitor_code']?'当前版本：'.date('YmdHis',$this->pagedata['editInfo']['ts']).'<a target="_blank" href="./?action=Order&do=OutPutMultiCitys&id='.$this->pagedata['editInfo']['id'].'"> [查看]</a>  <a target="_blank" href="./?action=Order&do=OutPutMultiCitys&headerType=xls&id='.$this->pagedata['editInfo']['id'].'"> [导出]</a>':'';?>
			    <? if($this->show == 'edit') {?><input type="checkbox" name="sync[code]">批量同步<?}?>
			</td>
		</tr>
		<?php if($this->show=='add'){?>
			<tr>
				<td>投放人员</td>
				<td><?= $this->pagedata['editInfo']['admin']?$this->pagedata['editInfo']['admin']:ADMIN?><input type="hidden" name="admin" value="<?= ADMIN?>"></td>
			</tr>
		<?php }?>
		<tr>
			<td>最后操作时间</td>
			<td><?= $this->pagedata['editInfo']['ts']?date('Y-m-d H:i:s',$this->pagedata['editInfo']['ts']):date('Y-m-d H:i:s',TIMESTAMP)?><input type="hidden" name="ts" value="<?= TIMESTAMP?>"></td>
		</tr>
		<tr>
			<td>广告素材快速上传工具</td>
			<td>
				<input type="text" id="flv_url" size="80"  value="" ><a style="display:none" id="flv_url_preview" href="<?= $this->pagedata['editInfo']['flv_url']?>" target="_blank">[预览]</a></br>
				<iframe name="FlvUpload" width="550" height="40" src="./?action=Order&do=FlvUploadForm" scrolling="no" border="0" frameborder="0">浏览器不支持嵌入式框架，或被配置为不显示嵌入式框架。</iframe>
			</td>
		</tr>
	    <tr>
			<td colspan="2" style="height:60px;text-align:center">
			    <span class="button blue" id="sbt_btn" onclick="asyn_sbt('input_form','./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&func=<?= $_GET[show]=='edit'?'edit':'add'?>')">确认提交</span>
		    </td>
		</tr>
	</table>
	</form>
	<script type="text/javascript">
	function rtn(data){
		switch (data.rs){
			case 0:
				$("#sbt_btn").html('操作成功');
				$("#sbt_btn").attr('class','button green');
				location.href="./?action=<?= $_GET['action']?>&do=Contract2aid&contract_id=<?= $_GET['id']?>";
			break;
		}
	}
	</script>
	<br />		
	<?php }?>
	<?php if($this->show=='list') {?>
	<table>
	<tr>
			<td colspan="9"><?php echo empty($this->pagedata['list'])?'-暂无数据-':$this->pagedata['sp']?></td>
		</tr>
		<tr>
			<th colspan="2"><a>合同编号</a></th>
			<th><a>签注</a></th>
			<th><a>客户名称</a></th>
			<th><a>投放类型</a></th>
			<th><a>投放周期</a></th>
			<th><a>日均CPM</a></th>
			<th><a>单价</a></th>
			<th width="20px"><input class="button green medium" type="button" onclick="location.href='./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&show=add'" value="新增"/></th>
		</tr>
		<?php 
		foreach ($this->pagedata['list'] as $data) {
			$c_ids[] =  $data['contract_id'];
		}
		$tj = array_count_values($c_ids);//统计重复值
		$stack = array();
		?>
		<?php foreach ($this->pagedata['list'] as $data) {?>
		<tr<?= $tj[$data['contract_id']]>1 && in_array($data['contract_id'], $stack)?' class="'.$data['contract_id'].'"':''?>>
			<td style="text-align:center;"><?= $tj[$data['contract_id']]>1 && !in_array($data['contract_id'], $stack)?'<a href="javascript:;" class="fold" for_class="'.$data['contract_id'].'">折叠</a>':''?></td>
			<td><?= $data['contract_id']?></td>
			<td><?= $data['remark']?></td>
			<td><?= $this->clients[$data['customer_id']]?></td>
			<td><?= $this->adstype[$data['ad_type']]?></td>
			<td>开始：<?= date('Y 年 m 月 d 日',$data['starttime'])?><br>结束：<?= date('Y 年 m 月 d 日',$data['endtime'])?></td>
			<td><?= $data['day_cpm']?></td>
			<td><?= $data['unit_price']?></td>
	        <td>
	        	<div class="order_op_panel">
	        		<a class="button green small" onclick="location.href='./?action=<?= $_GET['action']?>&do=<?= $_GET['do']?>&show=edit&id=<?= $data['id']?>'">编辑合同</a>
		            <a class="button white small" onclick="location.href='./?action=<?= $_GET['action']?>&do=Contract2aid&id=<?= $data['id']?>'">修改记录</a>
	        	    <?php if(!empty($data['reserve_code'])){
	        			$o = $this->_db->rsArray("SELECT id,status FROM ad_reserve WHERE reserve_code = '$data[reserve_code]' LIMIT 0,1");
						if($o['status'] == 4){
	        		?>
		            	<a class="button blue small" onclick="if(confirm('请确认该合同已下单，然后再释放库存资源，是否继续？')){asyn_trigger('./?action=Reserve&do=Release&reserve_code=<?= $data['reserve_code']?>')}">释放资源</a>
		            <?php }}?>
		            <?php
		        	    if($o['id'] && ROLE == 'DEVELOPER'){
		        	    	echo '<a class="button white small" onclick="location.href=\'./?action=Reserve&do=Basic&show=edit&id='.$o[id].'\'">预订信息</a>';
		        	    }
	        	    ?>
	        	    <?php $aids = $this->GetAidsByCid($data['id']);
	        	    	  if($aids != ''){?>
	        	    	<a class="button red small" target="_blank" href="./?action=Order&do=Transaction&func=search&show=list&aid=<?= $aids?>&vid=&title=&area=&starttime=&endtime=&link=&cid=&type=&username=&status=">订单查看</a>
	        	    <?php }?>
	        	 </div>
	        </td>
		</tr>
		<?php 
			$stack[] = $data['contract_id'];
		}?>
		<tr>
			<td colspan="9"><?php echo empty($this->pagedata['list'])?'-暂无数据-':$this->pagedata['sp']?></td>
		</tr>
	</table>
	<br />		
	<?php }?>
</div>
<script type="text/javascript">
	function flvupload(ct){
		$('#flv_url').attr('value',ct);
		$('#flv_url_preview').toggle();
		$('#flv_url_preview').attr('href',ct);
	}
	function multicitysinput(ct){
		$('#monitor_code').attr('value',ct);
	}
</script>
<?php
include template('footer');
?>