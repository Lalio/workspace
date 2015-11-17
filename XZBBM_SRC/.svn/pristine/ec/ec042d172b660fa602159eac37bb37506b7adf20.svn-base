<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;    
}
?>
<?php include template('header');?>
<div class="ctr_panel">
	<br>
	<table>
	    <tr>
	        <th colspan="3"><a href="javascript:;">合同批量修改对比</a></th>
	    </tr>
		<tr>
			<td>合同编号</td>
			<td colspan="2"><b><?= $this->pagedata['contract_id']?></b></td>
		</tr>
		<?php   
		foreach($this->pagedata['list'] as $data){
        unset($mdf);
		if(array_key_exists($data['aid'], $this->pagedata['last_m'])){ //对最后一次有改动的记录作特殊处理
		    foreach($this->pagedata['last_m'][$data['aid']] as $m_data){
                foreach ($m_data as $k => $v){
                    $mdf[$k] = $v;
                }
            }
		}
		?>
		<tr>
		    <td></td>
			<td colspan="2"></td>	
		</tr>
		<tr>
		    <td>广告单AID</td>
			<td colspan="2"><b>#<?= $data['aid']?><span class="contract_mdf"><?= $mdf['aid']?></span></b></td>	
		</tr>
		<tr>
		    <td>修改差异</td>
			<td>当前参数</td>	
			<td>改前参数</td>	
		</tr>
	    <tr>
			<td>客户名称</td>
			<td><?= $this->clients[$data['vid']]?></td>
			<? if(isset($mdf['vid']) && $data['vid'] != $mdf['vid']){?>
			<td><span class="contract_mdfd"><?= $this->clients[$mdf['vid']]?></span></td>
			<? }else{?>
			<td><?= $this->clients[$data['vid']]?></td>
			<? }?>	
		</tr>
		<tr>
			<td>标题</td>
			<td><?= $data['title']?></td>		
		    <? if(isset($mdf['title']) && $data['title'] != $mdf['title']){?>
			<td><span class="contract_mdfd"><?= $mdf['title']?></span></td>
			<? }else{?>
			<td><?= $data['title']?></td>
			<? }?>
		</tr>
		<tr>
			<td>简介</td>
			<td><?= $data['description']?></td>		
		    <? if(isset($mdf['description']) && $data['description'] != $mdf['descripiton']){?>
			<td><span class="contract_mdfd"><?= $mdf['description']?></span></td>
			<? }else{?>
			<td><?= $data['description']?></td>
			<? }?>
		</tr>
		<tr>
			<td>投放城市</td>
			<td><?= $data['city']?></td>		
		    <? if(isset($mdf['city']) && $data['city'] != $mdf['city']){?>
			<td><span class="contract_mdfd"><?= $mdf['city']?></span></td>
			<? }else{?>
			<td><?= $data['city']?></td>
			<? }?>
		</tr>
		<tr>
			<td>投放周期</td>
			<td><?= date('Y-m-d H:i:s',$data['starttime'])?> - <?= date('Y-m-d H:i:s',$data['endtime'])?></td>		
		    <? if((isset($mdf['starttime']) || isset($mdf['endtime'])) && (($data['starttime'] != $mdf['starttime']) || ($data['endtime'] != $mdf['endtime']))){?>
			<td><span class="contract_mdfd"><?= date('Y-m-d H:i:s',$mdf['starttime'])?> - <?= date('Y-m-d H:i:s',$mdf['endtime'])?></span>
			<? }else{?>
			<td><?= date('Y年m月d日',$data['starttime'])?> - <?= date('Y年m月d日',$data['endtime'])?></td>
			<? }?>
		</tr>
		<tr>
			<td>素材地址</td>
			<td><?= $data['tp_flv']?></td>		
		    <? if(isset($mdf['tp_flv']) && $data['tp_flv'] != $mdf['tp_flv']){?>
			<td><span class="contract_mdfd"><?= $mdf['tp_flv']?></span></td>
			<? }else{?>
			<td><?= $data['tp_flv']?></td>
			<? }?>
		</tr>
		<tr>
			<td>曝光监测</td>
			<td><?= $data['tp_viewurl']?></td>		
		    <? if(isset($mdf['tp_viewurl']) && $data['tp_viewurl'] != $mdf['tp_viewurl']){?>
			<td><span class="contract_mdfd"><?= $mdf['tp_viewurl']?></span></td>
			<? }else{?>
			<td><?= $data['tp_viewurl']?></td>
			<? }?>	
		</tr>
		<tr>
			<td>点击监测</td>
			<td><?= $data['tp_click']?></td>		
		    <? if(isset($mdf['tp_click']) && $data['tp_click'] != $mdf['tp_click']){?>
			<td><span class="contract_mdfd"><?= $mdf['tp_click']?></span></td>
			<? }else{?>
			<td><?= $data['tp_click']?></td>
			<? }?>
		</tr>
		<tr>
			<td>当日CPM量</td>
			<td><?= $data['cpm']?></td>		
		    <? if(isset($mdf['cpm']) && $data['cpm'] != $mdf['cpm']){?>
			<td><span class="contract_mdfd"><?= $mdf['cpm']?></span></td>
			<? }else{?>
			<td><?= $data['cpm']?></td>
			<? }?>
		</tr>
		<?php }?>
	</table>
</div>
<?php
include template('footer');
?>