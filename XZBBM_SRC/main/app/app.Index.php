<?php
if ( !defined( 'IN_SYS' ) ) {
	header( "HTTP/1.1 404 Not Found" );
	die;
}

class Index extends Xzbbm{
    
	public function __construct(){
		
		parent::__construct();
	}
	
	/**
	 * @todo APP下载
	 * @author bo.wang
	 * @version 2013-09-18 14:29
	 */
	public function App() {

		$this->adriod_url = "http://cdn.xzbbm.cn/apps/xzbbm_1.4.9.apk";
		include Template('appdownload');
	}
	
	/**
	 * @todo 个人中心
	 * @author bo.wang
	 * @version 2013-09-18 14:29
	 */
	public function My() {
	
		if($this->userinfo){
			var_dump($this->userinfo);
		}else{
			header('Location:http://'.DOMAIN);
		}
	
		include Template('my');
	}
	
	/**
	 * @todo 云印功能
	 * @author bo.wang
	 * @version 2013-09-18 14:29
	 */
	public function CloudPrint() {
		
		$file_id = $_REQUEST['file_id'];
		
		$value = array(
				'order_id' => build_order_no(),
				'store_id' => '100001',
				'pages' => 3,
				'total' => 1,
				'duplex' => 1,
				'file_id' => $file_id,
				'userid' => 1,
				'price' => 0.2,
				'ts' => TIMESTAMP,
				'state' => 0
				);
		
		$this->_db->insert('xz_cloudprint',$value);
		
		echo json_encode(array('rs' => 0,'msg' => '您的打印任务已经推送给商家，请于'.date('Y-m-d H:i:s',(TIMESTAMP + 86400)).'前到广工图书馆一层领取。'));
	}
	
	/**
	 * @todo 资料页
	 * @author bo.wang
	 * @version 2013-09-18 14:29
	 */
	public function ViewFile() {
		
		if(isset($_REQUEST['file_id'])){
			$file_id = intval($_REQUEST['file_id']);
			$where_str = "file_id = $file_id";
		}elseif($_REQUEST['wb']){
			$file_index = $_REQUEST['wb']?strtolower($_REQUEST['wb']):$_REQUEST['index'];
			$where_str = "file_index = '$file_index'";
		}elseif($_REQUEST['file_key']){
			$file_key = trim($_REQUEST['file_key']);
			if($file_key == 'app'){
				$this->App();
				exit;
			}
			$where_str = "file_key = '$file_key'";
		}
		
		$sql = "SELECT * FROM pd_files WHERE $where_str LIMIT 0,1";
		
		$this->pageData = $this->GetData($sql);
		$this->pageData = $this->pageData[0];
		$file_id = $this->pageData['file_id'];
		 
		//实时数据
		$rs = $this->_db->rsArray("SELECT * FROM pd_files WHERE file_id = ".$file_id);
		
		$this->pageData['has_pdf'] = $rs['has_pdf'];
		$this->pageData['in_recycle'] = $rs['in_recycle'];
		$this->pageData['has_swf'] = $rs['has_swf'];
		$this->pageData['has_swf'] = $rs['has_swf'];
		$this->pageData['has_png'] = $rs['has_png'];
		$this->pageData['is_converted'] = $rs['is_converted'];
		$this->pageData['has_thumb'] = $rs['has_thumb'];
		$this->pageData['file_views'] = $rs['file_views'];
		$this->pageData['file_downs'] = $rs['file_downs'];
		$this->pageData['user_name'] = $rs['user_name'];
		$this->pageData['dingcai'] = array('good_count' => $rs['good_count'],'bad_count' => $rs['bad_count']);
		
		//触发转换机提升优先级进行转换
		if($this->pageData['is_converted'] != 0){
		    $this->_db->update('pd_files',array('is_converted' => 2),"file_id = $file_id");
		}
		
		//用户数据
		//$uinfo = $this->GetData("SELECT * FROM pd_users WHERE userid = ".$this->pageData['userid']." limit 0,1");
		//$this->pageData['uinfo'] = $uinfo[0];
		
		if(empty($rs) || $this->pageData['in_recycle'] == '1'){
			$this->Search();
			exit;
		}else{
			$this->_db->conn("UPDATE pd_files SET file_views = file_views + 10 WHERE file_id = $file_id");
		}
		
		//大家正在看文件列表
		$this->pageData['relate'] = $this->GetData("SELECT file_views,file_downs,file_index,file_name,file_extension FROM pd_files WHERE ucode = '".$this->ucode."' AND file_index != '$file_index' AND has_swf != 0 AND in_recycle = 0 ORDER BY file_views DESC LIMIT 0,150");
		
		if(count($this->pageData['relate']) < 30){
			$this->pageData['relate'] = $this->GetData("SELECT file_index,file_name,file_extension FROM pd_files WHERE ucode = 0 AND file_index != '$file_index' AND CHARACTER_LENGTH(file_name) > 10 AND in_recycle = 0 ORDER BY file_views DESC LIMIT 0,150");
		}
		 
		shuffle($this->pageData['relate']);
		$this->pageData['relate'] = array_slice($this->pageData['relate'],0,12);
	
		//社交平台分享缩略图
		if($this->pageData['has_png'] == 0){
			$this->pageData['pics'] = '';
		}elseif($this->pageData['has_png'] == 1){
			$this->pageData['pics'][] = "http://gt.xzbbm.cn/png/".$this->pageData['file_store_path']."/".$this->pageData['file_real_name'].".png";
		}else{
			for($i=0;$i<$this->pageData['has_png'];$i++){
				$this->pageData['pics'][] = "http://gt.xzbbm.cn/png/".$this->pageData['file_store_path']."/".$this->pageData['file_real_name']."-$i.png";
			}
		}
		 
		$this->pageData['pics'][] = 'http://".DOMAIN."/?action=QrCodes&do=GcQr&fid='.$this->pageData[file_id];
		$this->pageData['pics'] = implode("||", $this->pageData['pics']);
		
		if(false === is_crawler()){
			
			/*
			 $pdf_dir = '/data/stores/pdf'.$file_store_path;
			//对Office类文档生成PDF.
			if(in_array($file_extension,array('doc','docx','ppt','pptx','xls','xls'))){
			if(false == file_exists($pdf_dir)){
			//构造目录
			$dir = make_store_dir($pdf_dir);
			}
			while(passthru("PATH=/usr/local/mysql/bin:/usr/local/mysql/bin:/usr/local/mysql/bin:/usr/local/mysql/bin:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin /usr/bin/python /usr/bin/unoconv -f pdf /data/stores/file/{$ins['file_store_path']}/{$ins['file_real_name']}.{$ins['file_extension']}")){
			passthru("mv /data/stores/file/{$ins['file_store_path']}/{$ins['file_real_name']}.pdf /data/stores/pdf/{$ins['file_store_path']}/{$ins['file_real_name']}.pdf");
			}
			}
			*/
			
			$file_key = "{$this->pageData['file_real_name']}.{$this->pageData['file_extension']}";
			$pdf_key = "{$this->pageData['file_real_name']}.pdf";
			$swf_key = "{$this->pageData['file_real_name']}.swf";
			$png_key = "{$this->pageData['file_real_name']}-0.png";
			$file_path = get_object($this->_oss,$file_key);
			
			//统一使用资料转换机对文件进行转换
			//获取文件本身
			if(in_array($this->pageData['file_extension'], array('doc','docx','ppt','pptx','xls','xlsx','pdf'))){
			    /*
			    //生成pdf
				if($this->pageData['has_pdf'] == 0 && $this->pageData['file_extension'] != 'pdf'){
					$this->GeneratePdf($file_key,$pdf_key);
				}else{
					$this->_db->update('pd_files',array('has_pdf' => 1),"file_id = $file_id");	
				}
				*/
				//生成swf
				if($this->pageData['has_swf'] == 0){
					$this->GenerateSwf($pdf_key,$swf_key);
				}
				/*
				//生成png
				if($this->pageData['has_png'] == 0){
					$this->GeneratePng($pdf_key);
				}
				*/
				/*
				//生成缩略图  缩略图改用阿里云图片处理方式直接实现
				if($this->pageData['has_thumb'] == 0){
					//$this->GenerateThumb($pdf_key);
				}
				*/
			}
			
		}
		
		$this->filesize = round(($this->pageData['file_size']/1048576),2);
		$this->baoguang = intformat($this->pageData['file_views']);
		$this->fenxiang = intformat($this->pageData['file_downs']);
		$this->chuanbo = round(($this->pageData['file_downs']/$this->pageData['file_views']),5);
		$this->md5 = strtoupper($this->pageData['file_md5']);
		$this->shouyi = $this->pageData['profile'];
		 
		if(is_mobile() || isset($_REQUEST['imei']) || $_REQUEST['from'] == 'm'){
	
			$this->page = $_REQUEST['page']?intval($_REQUEST['page']):1;
			$this->pre_url = "http://".DOMAIN."/view/{$this->pageData['file_index']}?from={$_REQUEST['from']}&imei={$_REQUEST['imei']}&page=".($this->page-1);
			$this->next_url = "http://".DOMAIN."/view/{$this->pageData['file_index']}?from={$_REQUEST['from']}&imei={$_REQUEST['imei']}&page=".($this->page+1);			
			if($this->pageData['has_png'] == 0){ //触发统计
		   
				//if($this->pageData['has_png'] == 0){
					if(in_array($this->pageData['file_extension'], array('wma','exe','chm','txt','cpp'))){
						$this->pageData['attention'] = '<p>%>_<% 当前格式的资料暂时不支持直接预览，请通过发送功能免费获取资料。</p>';
					}elseif(in_array($this->pageData['file_extension'], array('zip','rar'))){
						$this->pageData['attention'] = $this->pageData['file_extension'] == 'zip'?getzipinfo($file_path,$this->pageData['file_size']):getrarinfo($file_path,$this->pageData['file_size']);
					}elseif(in_array($this->pageData['file_extension'], array('mp3'))){
						$this->pageData['attention'] = <<<HTMLCODE
	    		<p>{$this->pageData['file_description']}</p>
	    		<p>
		    		<br><br><object type="application/x-shockwave-flash" data="http://".DOMAIN."/etc/reader/dewplayer-vol.swf?mp3=http://gt.xzbbm.cn/{$this->pageData['file_store_path']}/{$this->pageData['file_real_name']}.{$this->pageData['file_extension']}" width="100%" height="50" id="dewplayer-vol">
		    			<param name="width" value="100%">
	    				<param name="height" value="50">
	    				<param name="wmode" value="transparent">
		    				<param name="movie" value="http://".DOMAIN."/etc/reader/dewplayer-vol.swf?mp3=http://gt.xzbbm.cn/{$this->pageData['file_store_path']}/{$this->pageData['file_real_name']}.{$this->pageData['file_extension']}">
		    		</object><br><br>
	    		</p>
HTMLCODE;
					}elseif(-8 >= $this->pageData['has_swf']){ //连续40秒未能生成判定为版权保护
						if($this->pageData['has_png'] > 0){
							$this->pageData['attention'] = $this->pageData['has_png'] == 1?'<img style="border:1px solid rgb(216, 218, 216);width:90%;margin:5px;" src="http://".DOMAIN."/GetFile/'.$this->pageData['file_id'].'/png/'.TIMESTAMP.'/'.sha1(TIMESTAMP.'sNsxCrth13LGsu60').'">':'<img style="border:1px solid rgb(216, 218, 216);width:90%;margin:5px;" src="http://".DOMAIN."/GetFile/'.$this->pageData['file_id'].'/png/'.TIMESTAMP.'/'.sha1(TIMESTAMP.'sNsxCrth13LGsu60').'?bit=0">';
							$this->pageData['attention'] .= '<p>%>_<%<br>基于版权保护规则，当前资料仅提供部分页面预览，请通过发送功能免费获取完整资料。';
						}else{
							$this->pageData['attention'] = '<p>%>_<%<br>基于版权保护规则，当前资料无法提供在线预览，请通过发送功能免费获取完整资料。';
						}
					}
				//}else{
				/*
					//计数并入库
					if(404 == is_object_exist($this->_oss,$png_key)){
						$this->pageData['has_png'] = 1;
					}else{
						for($i = 0;$i < 15;$i++){
							if(200 == is_object_exist($this->_oss,"{$this->pageData['file_real_name']}-{$i}.png")){
								$this->pageData['has_png'] = $i;
								break;
							}
						}
					}
					$this->_db->update('pd_files',array('has_png' => $this->pageData['has_png']),"file_id = ".$file_id);
				}
				*/
			}
			
			if(in_array($this->pageData['file_extension'], array('jpg','png'))){
				$this->pageData['attention'] = '<div class="nd_rotate" style="text-align: center">';
				$this->pageData['attention'] .= '<img style="border:1px solid rgb(216, 218, 216);width:90%;margin:5px;" src="http://'.DOMAIN.'/GetFile/'.$this->pageData['file_id'].'/png/'.TIMESTAMP.'/'.sha1(TIMESTAMP.'sNsxCrth13LGsu60').'/0">';
				$this->pageData['attention'] .= '</div>';
			}
			
			//强制客户端缓存网页
			$interval = 86400*31;//31天
			header('Cache-Control: public');
			header('Last-Modified: '.gmdate('r',TIMESTAMP));
			header('Expires: '.gmdate('r',(TIMESTAMP+$interval)));
			header('Cache-Control: max-age='.$interval);
			
			if($_REQUEST['imei'] != ''){
				include Template('appfile');
			}else{
				if($_REQUEST['from'] == 'm'){
					include Template('mfile');
				}else{
					include Template('newmedia');
				}
			}
	
		}else{

			if($this->pageData['has_swf'] == 0){
				if(in_array($this->pageData['file_extension'], array('wma','exe','chm','txt','cpp'))){
					$this->pageData['attention'] = '<p>%>_<% 当前格式的资料暂时不支持直接预览，请通过发送功能免费获取资料。</p>';
				}elseif(in_array($this->pageData['file_extension'], array('zip','rar'))){
					$this->pageData['attention'] = $this->pageData['file_extension'] == 'zip'?getzipinfo($file_path,$this->pageData['file_size']):getrarinfo($file_path,$this->pageData['file_size']);
				}elseif(in_array($this->pageData['file_extension'], array('jpg','png'))){
					$this->pageData['attention'] = '<div class="nd_rotate" style="text-align: center">';
					$this->pageData['attention'] .= '<img style="border:1px solid rgb(216, 218, 216);width:90%;margin:5px;" src="http://".DOMAIN."/GetFile/'.$this->pageData['file_id'].'/file/'.TIMESTAMP.'/'.sha1(TIMESTAMP.'sNsxCrth13LGsu60').'">';
					$this->pageData['attention'] .= '</div>';
					$this->pageData['attention'] .= '<p>%>_<% 基于版权保护规则，当前资料仅提供部分页面预览，请通过发送功能免费获取完整资料。';
				}elseif(in_array($this->pageData['file_extension'], array('mp3'))){
					$this->pageData['attention'] = <<<HTMLCODE
	    		<p>{$this->pageData['file_description']}</p>
	    		<p>
		    		<object type="application/x-shockwave-flash" data="http://".DOMAIN."/etc/reader/dewplayer-vol.swf?mp3=http://gt.xzbbm.cn/{$this->pageData['file_store_path']}/{$this->pageData['file_real_name']}.{$this->pageData['file_extension']}" width="500" height="50" id="dewplayer-vol">
		    			<param name="width" value="500">
	    				<param name="height" value="50">
	    				<param name="wmode" value="transparent">
		    				<param name="movie" value="http://".DOMAIN."/etc/reader/dewplayer-vol.swf?mp3=http://gt.xzbbm.cn/{$this->pageData['file_store_path']}/{$this->pageData['file_real_name']}.{$this->pageData['file_extension']}">
		    		</object>
	    		</p>
HTMLCODE;
				}elseif(-8 >= $this->pageData['has_swf']){ //连续40秒未能生成判定为版权保护
					if($this->pageData['has_png'] > 0){
						$this->pageData['attention'] = $this->pageData['has_png'] == 1?'<img style="border:1px solid rgb(216, 218, 216);width:90%;margin:5px;" src="http://".DOMAIN."/GetFile/'.$this->pageData['file_id'].'/png/'.TIMESTAMP.'/'.sha1(TIMESTAMP.'sNsxCrth13LGsu60').'">':'<img style="border:1px solid rgb(216, 218, 216);width:90%;margin:5px;" src="http://".DOMAIN."/GetFile/'.$this->pageData['file_id'].'/png/'.TIMESTAMP.'/'.sha1(TIMESTAMP.'sNsxCrth13LGsu60').'/0">';
						$this->pageData['attention'] .= '<p>%>_<% 基于版权保护规则，当前资料仅提供部分页面预览，请通过发送功能免费获取完整资料。';
					}else{
						$this->pageData['attention'] = '<p>%>_<% 基于版权保护规则，当前资料无法提供在线预览，请通过发送功能免费获取完整资料。';
					}
				}else{
					$refreshtime = 10000;
					$domain = DOMAIN;
					$this->pageData['attention'] = <<<HTMLCODE
	    		<p align="center"><img src="http://{$domain}/images/gc_loading.gif"><br><br><strong>资料正在拼命转换中，请稍等10S...</strong></p>
				<script type="text/javascript">
					setInterval(function(){location.reload();},$refreshtime);
				</script>
HTMLCODE;
				}
			}
	
			include Template('file');
		}
	}
	
	
	/**
	 * @todo 文件上传接口
	 * @author bo.wang
	 * @version 2013-09-18 14:29
	 */
	public function Upload() {
		
		//if(TIMESTAMP - $_COOKIE['timestamp'] > 10 ||$_COOKIE['token'] != md5('2zwep62GnVv08Z5W9GGc'.$_COOKIE['timestamp'])){
			//exit("你无权通过此接口上传！\nC_tk:$_COOKIE[token] \nC_ts:$_COOKIE[timestamp] \nS_tk:".md5('2zwep62GnVv08Z5W9GGc'.$_COOKIE['timestamp']));
			//exit("安全校验失败，请刷新页面后重新上传。");
		//}else{
			$file = $_FILES['Filedata'];
			$file_info = explode('.', $file["name"]);
			$file_name = $file_info[0];
			$file_extension = $file_info[(count($file_info) - 1)];
			$file_path = $file['tmp_name'];
			
			$userid = $_COOKIE['userid']?$_COOKIE['userid']:$_REQUEST['userid'];
			$ucode = $_COOKIE['ucode']?$_COOKIE['ucode']:$_REQUEST['ucode'];
			$ccode = $_COOKIE['ccode']?$_COOKIE['ccode']:$_REQUEST['ccode'];
			
			//if(empty($userid)){
				//exit("用户ID不正确或者不存在，系统禁止匿名上传。");
			//}
		//}
		
		 
		//判断文件类型
		if(true !== file_type($file_path)){
		  echo $file_path."\n";
		  exit('Error File Type!|'.file_type($file_path));
		}
		 
		if(!file_exists($file_path)){
			echo $file_path." - 文件不存在，请检查原路径是否正确。";
			exit;
		}else{
			
			$file_tag = $_REQUEST['tag'];
			$file_md5 = md5_file($file_path);
			$file_size = filesize($file_path);
			$file_index = md5(uniqid(mt_rand(),true).microtime().'1');
			$file_real_name = $file_index;
			$file_key = makerandom(5);
			
			//检查有没有已经存在的文件
			$file_alreay = $this->_db->rsArray("SELECT file_store_path,file_real_name FROM pd_files WHERE file_md5 = '$file_md5' LIMIT 0,1");
	
			if(empty($file_alreay)){
				$y = date('Y',TIMESTAMP);
				$m = date('m',TIMESTAMP);
				$d = date('d',TIMESTAMP);
					
				$file_store_path = "$y/$m/$d";
				//构造目录
				//$dir = make_store_dir('/data/stores/file',$file_store_path);
			}
			 
			$ins = array(
					'file_name' => $file_name,
					'file_index' => $file_index,
					'file_key' => $file_key,
					'file_tag' => $file_tag,
					'file_extension' => $file_extension,
					'file_description' => $_REQUEST['des'],
					'file_store_path' => empty($file_alreay['file_store_path'])?$file_store_path:$file_alreay['file_store_path'],
					'file_real_name' => empty($file_alreay['file_real_name'])?$file_real_name:$file_alreay['file_real_name'],
					'file_md5' => $file_md5,
					'file_size' => $file_size,
					'file_time' => TIMESTAMP,
					'file_views' => 1,
			        'is_converted' => 1,
					'userid' => $userid,
					'ip' => get_ip(),
					'location' => get_adress(),
					'ucode' => $ucode,
			        'ccode' => $ccode,
			);
	
			$rs_fid = $this->_db->insert('pd_files',$ins);
			
			$key = "$ins[file_real_name].$ins[file_extension]";
			 
			if(is_numeric($rs_fid)){
				if(empty($file_alreay)){ //把上传的资料推向云端
					if(false === upload_by_file($this->_oss,$key,$file_path)){
						echo '文件上传失败，请与开发人员联系。';
					}else{ 
						/*
						$pdf_dir = '/data/stores/pdf'.$file_store_path;
						//对Office类文档生成PDF.
						if(in_array($file_extension,array('doc','docx','ppt','pptx','xls','xls'))){
							if(false == file_exists($pdf_dir)){
								//构造目录
								$dir = make_store_dir($pdf_dir);
							}
							while(passthru("PATH=/usr/local/mysql/bin:/usr/local/mysql/bin:/usr/local/mysql/bin:/usr/local/mysql/bin:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin /usr/bin/python /usr/bin/unoconv -f pdf /data/stores/file/{$ins['file_store_path']}/{$ins['file_real_name']}.{$ins['file_extension']}")){
								passthru("mv /data/stores/file/{$ins['file_store_path']}/{$ins['file_real_name']}.pdf /data/stores/pdf/{$ins['file_store_path']}/{$ins['file_real_name']}.pdf");
							}
						}
						*/
					}
				}
				echo "{$ins['file_index']}";
			}else{
				echo '错误消息：'.$this->_db->_errorMsg;
			}
			//插入搜索引擎表
			//$this->_db->insert('xz_search_engine',array('file_id' => $rs_fid,'file_name' => str_replace("\"","",json_encode($ins['file_name'])),'file_tag' => str_replace("\"","",json_encode($ins['file_tag'])),'file_description' => str_replace("\"","",json_encode($ins['file_description']))));
		}
	}
	
	/**
	 * @todo 首页
	 * @author bo.wang
	 * @version 2013-09-18 14:29
	 */
	public function Index() {
	
		$select = 'profile,file_downs,file_views,file_id,file_name,file_extension,file_index,file_name,file_store_path,file_real_name';
		
		$key_words = array('听力','题','设计','课程','报告','论文','答案','版','学报','实验','课件');
		foreach ($key_words as $key_word){
			$key_word_str .= "file_name like '%$key_word%' OR ";
		}
		$key_word_str = rtrim($key_word_str,'OR ');
		
		
		$wherestr = "WHERE CHARACTER_LENGTH(file_name) > 3 
				     AND in_recycle = 0 
					 AND ($key_word_str)";
		
		$this->pageData['rand1'] = $this->GetData("SELECT $select FROM pd_files $wherestr AND ucode = {$this->ucode} ORDER BY rand() limit 0,10");
		$this->pageData['rand2'] = $this->GetData("SELECT $select FROM pd_files $wherestr AND ucode = 0 ORDER BY rand() desc limit 0,".(15 - count($this->pageData['rand1'])));
		$this->pageData['rand'] = array_merge($this->pageData['rand1'],$this->pageData['rand2']);
		shuffle($this->pageData['rand']);
		
		$this->pageData['top1'] = $this->GetData("SELECT $select FROM pd_files $wherestr AND ucode = {$this->ucode} ORDER BY file_views desc limit 0,10");
		$this->pageData['top2'] = $this->GetData("SELECT $select FROM pd_files $wherestr AND ucode = 0 ORDER BY file_views desc limit 0,".(15 - count($this->pageData['top1'])));
		$this->pageData['top'] = array_merge($this->pageData['top1'],$this->pageData['top2']);
		
		$this->pageData['kan'] = $this->GetData("SELECT $select FROM pd_files WHERE in_recycle = 0 AND has_png > 0 GROUP BY file_name ORDER BY rand() limit 0,20");
		shuffle($this->pageData['kan']);
		
		$this->pageData['subtype'] = $this->GetIndexTypes();
		$this->pageData['menu'] = $this->pageData['subtype']['names'];
		$this->pageData['keys'] = $this->pageData['subtype']['keys'];
		
		$this->pageData['tt'] = array("img01.png","img07.png","img08.png");
		shuffle($this->pageData['tt']);
		
		
		include Template('index');
	}
	
	/**
	 * @todo 获得首页辅助分类资料
	 * @author bo.wang
	 * @version 2013-09-18 14:29
	 */
	public function GetSubTypeInfo() {
	
		$key = trim($_REQUEST['key']);
	
		$wherestr = "WHERE file_name LIKE '%$key%' AND in_recycle = 0";//ucode = $this->ucode AND
	
		$this->pageData = array(
				'srow1' => $this->GetData("SELECT profile,file_views,file_id,file_name,file_extension,file_index,file_store_path,file_real_name FROM pd_files $wherestr AND has_jpg > 3 ORDER BY file_views DESC limit 0,5"),
				'srow2' => $this->GetData("SELECT profile,file_views,file_id,file_name,file_extension,file_index,file_store_path,file_real_name FROM pd_files $wherestr AND has_jpg > 3 ORDER BY file_views DESC limit 5,5"),
				'hots' => $this->GetData("SELECT profile,file_downs,file_views,file_id,file_name,file_extension,file_index as file_index FROM pd_files $wherestr ORDER BY file_downs DESC limit 0,10"),
				'news' => $this->GetData("SELECT profile,file_downs,file_views,file_id,file_name,file_extension,file_index as file_index FROM pd_files $wherestr ORDER BY rand() DESC limit 0,10")
		);
	
		foreach($this->pageData['hots'] as $k => $v){
			$this->pageData['hots'][$k]['file_views'] = intformat($v['file_views']+$v['file_downs']);
		}
	
		foreach($this->pageData['news'] as $k => $v){
			$this->pageData['news'][$k]['file_views'] = intformat($v['file_views']+$v['file_downs']);
		}
		echo json_encode($this->pageData);
	}
	
	/**
	 * @todo 获得首页补充资料
	 * @author bo.wang
	 * @version 2013-09-18 14:29
	 */
	public function GetIndexMore() {
	
		$bitch = intval($_REQUEST['bitch']);
	
		$wherestr = "WHERE in_recycle = 0 AND CHARACTER_LENGTH(file_name) > 10";
		$rs['newlist'] = $this->GetData("SELECT file_cname,file_extension,file_index FROM pd_files $wherestr AND ucode = 0 ORDER BY rand() DESC limit ".($bitch*15).",15");
		$rs['hotlist'] = $this->GetData("SELECT file_cname,file_extension,file_index FROM pd_files $wherestr AND ucode = {$this->ucode} ORDER BY weight DESC,file_downs DESC limit ".($bitch*15).",15");
	
		if(count($rs['hotlist']) < 15){ //不足的补全
			$rs['hotlist'] = array_merge($rs['hotlist'],$this->GetData("SELECT file_cname,file_extension,file_index FROM pd_files $wherestr AND ucode = 0 ORDER BY rand() DESC limit ".(($bitch+15)*15).",".(15 - count($rs['hotlist']))));
		}
	
		echo json_encode($rs);
	}
	
	/**
	 * @todo 首页分类数组
	 * @author bo.wang
	 * @version 2013-09-18 14:29
	 */
	public function GetIndexTypes() {
		//最多可配置六组分类
		$uid = $this->ucode?$this->ucode:$_REQUEST['ucode'];
		$rss = $this->GetUniversityInfo($uid,'','college');
		
		foreach($rss as $data){
			
			$total = $data['total'] == '0'?strtonum($data['college']):$data['total'];
			
			$rs1[$data['college_id']] = "{$data['college']} ({$total})";
			$rs2[$data['college_id']] = "{$data['college']}";
		}
		
		//用户当前学院排在第一位
		if($this->userinfo['ucode'] == $_SESSION['ucode'] && $this->userinfo['ccode']){
			$cols1[0] = $rs1[$this->userinfo['ccode']];
			$cols2[0] = $rs2[$this->userinfo['ccode']];
			unset($rs1[$this->userinfo['ccode']]);
			unset($rs2[$this->userinfo['ccode']]);
		}
		
		foreach($rs1 as $data){
			$cols1[] = $data;
		}
		
		foreach($rs2 as $data){
			$cols2[] = $data;
		}
		
		$names = array(
				'我的大学' => $cols1,
				'校内资料' => array(
						'期末试卷','课后答案','课程设计','实验报告','毕业论文','课件讲稿'
						),
				'研究生考试' => array(
						'数学','英语','政治','专业课','复试真题','高分心得'
						),
				'公务员考试' => array(
						'国考','省考','事业单位','申论','行测','提分经验'
				),
				'其他资料' => array(
						'四六级','司法考试','专业八级','求职简历','托福雅思','GRE','留学'
				)
		);
		
		$keys = array(
				'我的大学' => $cols2,
				'校内资料' => array(
						'期末','课后答案','设计 课设','实验 报告','论文','课件'
				),
				'研究生考试' => array(
						'数学','英语','政治','考研 初试 真题','复试','心得 体会'
				),
				'公务员考试' => array(
						'国考 国家','省','事业单位','申论','行测','提分经验'
				),
				'其他资料' => array(
						'四级 六级','司法考试','专业八级','简历','托福 雅思','GRE','留学 出国'
				)
		);
		
		if(empty($uid)){
			unset($names['我的大学']);
			unset($keys['我的大学']);
		}

		if($_REQUEST['from'] == 'app'){
			echo json_encode($names);
		}else{
			return array('names' => $names,'keys' => $keys);
		}
	}
	
	/**
	 * @todo 异步返回某个省份或者全国资料排行榜
	 * @author bo.wang
	 * @version 2013-09-18 14:29
	 */
	public function GetUniRank() {
		
		$client = trim($_REQUEST['client']);
		$pros = $this->GetData("SELECT * FROM geo_provinces");
		
		foreach($pros as $pro){
			$pro_a[$pro['province_id']] = $pro['province'];
		}
		
		$province_id = 0;
		
		foreach($pro_a as $k => $v){
			if(strstr($client,$v)){
				$province_id = $k;
				break;
			}
		}
		
		if($province_id != 0){
			$rs = $this->GetData("SELECT * FROM geo_universities WHERE province = $province_id ORDER BY total_files DESC,university_id ASC LIMIT 0,3");
		}else{
			$rs = $this->GetData("SELECT * FROM geo_universities ORDER BY total_files DESC,university_id ASC LIMIT 0,3");
		}
		
		$rt['pro'] = $province_id == 0?'全国高校资料TOP3':$pro_a[$province_id].'高校资料TOP3';
		
		foreach($rs as $k=>$v){
			$rt['ctc'] .= "<a href='javascript:;'><img height='27px' src='http://".DOMAIN."/images/sicons/{$v['sicon_id']}.png' onerror='this.src=\"http://".DOMAIN."/images/sicons/0.png\"'> {$v['name']}({$v['total_files']})</a>";
		}
		
		echo json_encode($rt);
	}
	
	/**
	 * @todo 临时首页
	 * @author bo.wang
	 * @version 2013-09-18 14:29
	 */
	public function PreIndex() {
	    include Template('preindex');
	}
	
	/**
	 * @todo 主搜索接口
	 * @author bo.wang
	 * @version 2013-09-18 14:29
	 */
	public function Search() {
	
		$key = urldecode($_REQUEST['k']);
		
		$_REQUEST['ucode'] = $_REQUEST['ucode']?$_REQUEST['ucode']:0;
		$ucode = isset($this->ucode)?intval($this->ucode):$_REQUEST['ucode'];
		
		$this->page = isset($_REQUEST['page'])?intval($_REQUEST['page']):1;
		
		$select = 'profile,ucode,file_size,file_downs,file_views,file_id,file_name,file_extension,file_index,file_name,good_count';
	
		//对关键进行分词
		$key_fc = file_get_contents("http://2.xzbbm.sinaapp.com/main/?action=ChFc&str=".urlencode($key));
		$key_fc = json_decode($key_fc,true);
		
		foreach($key_fc as $k => $v){
			$v['word'] = trim($v['word']);
			if(!empty($v['word'])){
				$key_a[] = $v['word'];
			}
		}
		
		if(count($key_a) > 1){
			$key_a[] = $key;
		}
		
		foreach($key_a as $k => $v){
			$key_a[$k] = str_replace("\"","",json_encode($v));
		}
		
		$key_a = array_reverse($key_a);
		
		//拼接关键词搜索序列
		foreach($key_a as $k){
			$key_str .= "+$k";
		}
		
		$file_ids = $this->GetData("SELECT file_id 
									FROM xz_search_engine 
   									WHERE Match(file_name,file_tag,file_description) 
				                    Against ('{$key_str}' IN BOOLEAN MODE);");
		
		foreach($file_ids as $v){
			$file_ids_tar[] = $v['file_id'];
		}
		/*
		echo "SELECT file_id
		FROM xz_seaech_engine
		WHERE Match(file_name,file_tag,file_description)
		Against ('{$key_str}' IN BOOLEAN MODE);";
		*/
		$key_str = "file_id IN (".implode(',',$file_ids_tar).")";
		//echo $key_str;exit;
		/*
		//拼接关键词搜索序列
		foreach($key_a as $k){
			$key_str .= "(file_name LIKE '%{$k}%' OR file_tag LIKE '%{$k}%' OR file_cname LIKE '%{$k}%' OR file_description LIKE '%{$k}%') OR ";
		}
		$key_str = rtrim($key_str,'OR ');
		*/
		
		switch ($_REQUEST['o']){
			case 'u': //搜索全校
				$u_str = "ucode = $ucode AND";
				$orderby = "ORDER BY file_views DESC";
				break;
			case 'd': //下载次数
				$u_str = "( ucode = 0 OR ucode = $ucode ) AND";
				$orderby = "ORDER BY file_downs DESC";
				break;
			case 'g': //用户好评
				$u_str = "( ucode = 0 OR ucode = $ucode ) AND";
				$orderby = "ORDER BY good_count DESC";
				break;
			case 'r': //智能推荐
				$u_str = "( ucode = 0 OR ucode = $ucode ) AND";
				$orderby = "ORDER BY file_views DESC";
				break;
			//case 'c': //学院搜索
				//$u_str = "ucode = $ucode AND file_tag LIKE '%%'";
				//$orderby = "ORDER BY file_views DESC";
				//break;
			case 'b': //全国搜索
				$u_str = "";
				$orderby = "ORDER BY file_views DESC";
				break;
			case 'i': //首页推荐
				$u_str = "( ucode = 0 OR ucode = $ucode OR ucode = ".mt_rand(50000, 500000).") AND";
				$orderby = "ORDER BY rand()";
				break;
		}
		
		$sql_0 = "SELECT $select FROM pd_files WHERE $u_str $key_str $orderby";
		$sql_t_0 = "SELECT count(*) as total FROM pd_files WHERE $u_str $key_str";
	
		$sql_1 = "SELECT $select FROM pd_files WHERE $u_str (file_name LIKE '%$key%' OR file_tag LIKE '%$key%') $orderby";
		$sql_t_1 = "SELECT count(*) as total FROM pd_files WHERE $u_str (file_name LIKE '%$key%' OR file_tag LIKE '%$key%')";
		
		$this->total = $this->GetData($sql_t_0);
		$this->total = $this->total[0]['total'];
		
		$sql = $this->total > 3?$sql_0:$sql_1;  //触发阈值3
		$sql_t = $this->total > 3?$sql_t_0:$sql_t_1;
		
		$this->total = $this->GetData($sql_t);
		$this->total = $this->total[0]['total'];
	
		//设置分页
		$sp = new Page();
		$sp->setvar ( array_merge ( array ('page', 'i', 'dy' )) );
		$sp->SetAdmin(20,$this->total,$this->page,'');
		$this->sp  = $sp->output(true);
	
		//获取列表
		$this->pageData = $this->GetData($sql." LIMIT ".$sp->Limit());
		
		if($_REQUEST['func'] == 'json' && count($this->pageData) < 15){//数据不足15个补齐
			$sql = "SELECT $select FROM pd_files WHERE ucode = 0 ORDER BY rand() limit 0,".(15 - count($this->pageData));
			$bc =  $this->GetData($sql);
			$this->pageData = array_merge($this->pageData,$bc);
		}
		
		//首页换一换
		if($_GET['from'] == 'index'){
			foreach($this->pageData as $k => $v){
				$this->pageData[$k]['file_views'] = intformat($v['file_views']+$v['file_downs']);
			}
			shuffle($this->pageData);
		}
		
		if($_REQUEST['func'] == 'json'){
			echo json_encode($this->pageData);
		}else{
			include Template('search');
		}
	}
	
	/**
	 * @todo 首页
	 * @author bo.wang
	 * @version 2013-09-18 14:29
	 */
	public function Main() {
	    $this->Index();
		//include Template('preindex');
	}
	
	/**
	 * @todo 获取优质资料
	 * @author bo.wang
	 * @version 2013-09-18 14:29
	 */
	public function TopFiles() {
		
		$num = $_REQUEST['num']?$_REQUEST['num']:10;
		$rs = $this->GetData('SELECT * FROM pd_files ORDER BY file_views DESC limit '.mt_rand(0,80).",".$num);
		
		echo json_encode($rs);
	}
	
	/**
	 * @todo 学校选择
	 * @author bo.wang
	 * @version 2013-09-18 14:29
	 */
	public function SwitchCollege() {
		 
		$ucode = trim($_REQUEST['ucode']);
		$_SESSION['ucode'] = is_numeric($ucode)?$ucode:0;
		//$this->Index();
		header('Location:'.$_SERVER['HTTP_REFERER']);   //返回其调用页面
	}
	
	/**
	 * @todo 发送邮件接口
	 * @author bo.wang
	 * @version 2013-09-18 14:29
	 */
	public function SendByMail() {
	
		$file_index = trim($_GET['file_index']);
		$addr = trim($_GET['addr']);
		setcookie(md5('xzbbm.cn_send_adress'),$addr);
		 
		$rs = $this->GetData("SELECT file_id FROM pd_files WHERE file_index = '".$file_index."' LIMIT 0,1");
		echo jcallback(json_encode(SendEmailNotLogin::Main($rs[0]['file_id'], $addr)));
		 
	}
	
	/**
	 * @todo 获取Swf文档用于展示
	 * @author bo.wang
	 * @version 2013-09-18 14:29
	 */
	public function GetSwf(){
		
		//Swf文件获取Token验证
		if(!isset($_REQUEST['ts']) || !isset($_REQUEST['token'])){
			go_win(1,'访问参数不完整。');
			exit;
		}
		
		if((TIMESTAMP - $_REQUEST['ts']) > 60 || sha1($_REQUEST['ts'].'sNsxCrth13LGsu60') != $_REQUEST['token']){
			go_win(1,"请刷新页面后重试！。C_ts:$_REQUEST[ts] C_token:$_REQUEST[token] S_ts:".TIMESTAMP." S_token:".sha1($_REQUEST['ts'].'sNsxCrth13LGsu60'));
			exit;
		}
		
		//define('FILESTORE_ROOT',"/data/stores/swf/");
		
		@set_time_limit(0);
		@ignore_user_abort(true);
		
		$idf = trim($_REQUEST['idf']);
		
		$rs = $this->GetData("select file_real_name from pd_files where file_index = '$idf' limit 0,1");
		$rs = $rs[0];
		
		//从云端取回文件
		$tmp_path = get_object($this->_oss,"$rs[file_real_name].swf");
		//$swf = FILESTORE_ROOT."{$rs['file_store_path']}/{$rs['file_real_name']}.swf";
		
		if(file_exists($tmp_path)){

			//输出开始
			ob_end_clean();
			$interval = 86400*31;//31天
			header('Cache-Control: public');
			header('Last-Modified: '.gmdate('r',TIMESTAMP));
			header('Expires: '.gmdate('r',(TIMESTAMP+$interval)));
			header('Cache-Control: max-age='.$interval);
			header('Content-Length: '.filesize($tmp_path));
			header('Content-Type: application/x-shockwave-flash');
			header('Content-Disposition: inline;');
			//header('Content-Disposition: attachment;filename="'.$rs['file_real_name'].'.pdf"');
			header('Content-Transfer-Encoding: binary');
			 
			$chunk = 6000; //下载速度控制
			$sent = 0;
	
			if(($fp = @fopen($tmp_path,'rb')) === false) exit('Can not open file!');
			
			do{
				$buf = fread($fp,$chunk);
				$sent += strlen($buf);
				echo $buf;
				ob_flush();
				flush();
				if(strlen($buf) ==0){
					break;
				}
			}while(true);
			
			@fclose($fp);
		}
	}
	
	/**
	 * @todo 以加密接口的形式获取各种形式的文件
	 * @author bo.wang
	 * @version 2013-09-18 14:29
	 */
	/*
	public function GetFile(){

		//文件获取Token验证
		if(!isset($_REQUEST['ts']) || !isset($_REQUEST['token'])){
			go_win(1,'访问参数不完整。');
			exit;
		}
		
		$file_id = trim($_REQUEST['fid']);
		$ext = trim($_REQUEST['ext']);
		$bit = trim($_REQUEST['bit']);
		
		if($ext != 'thumb'){
			if((TIMESTAMP - $_REQUEST['ts']) > 3600 || sha1($_REQUEST['ts'].'sNsxCrth13LGsu60') != $_REQUEST['token']){
				go_win(1,"学长帮帮忙资源禁止盗用，您没有获得授权(XZBBM GROUP)。");
				exit;
			}
		}
		
		$rs = $this->GetData("select file_real_name,file_extension from pd_files where file_id = '{$file_id}' limit 0,1");
		$rs = $rs[0];
			
		if(in_array($ext,array('jpg','png','pdf'))){
			if(isset($_REQUEST['bit'])){
				header("Location:http://oss.xzbbm.cn/{$rs['file_real_name']}-$bit.$ext");
			}else{
				header("Location:http://oss.xzbbm.cn/{$rs['file_real_name']}.$ext");
			}
		}elseif(in_array($ext,array('file'))){
			header("Location:http://oss.xzbbm.cn/{$rs['file_real_name']}.{$rs['file_extension']}");
		}elseif(in_array($ext,array('thumb'))){
			header("Location:http://img.xzbbm.cn/{$rs['file_real_name']}-{$bit}.png@!forested300");
		}
	}
	*/
	/**
	 * @todo 以加密接口的形式获取各种形式的文件
	 * @author bo.wang
	 * @version 2013-09-18 14:29
	 */
	public function GetFile(){
	
		//文件获取Token验证
		if(!isset($_REQUEST['ts']) || !isset($_REQUEST['token'])){
			go_win(1,'访问参数不完整。');
			exit;
		}
	
		$file_id = trim($_REQUEST['fid']);
		$ext = trim($_REQUEST['ext']);
		$bit = trim($_REQUEST['bit']);
	
		if($ext != 'thumb'){
			if((TIMESTAMP - $_REQUEST['ts']) > 300 || sha1($_REQUEST['ts'].'sNsxCrth13LGsu60') != $_REQUEST['token']){
				go_win(1,"学长帮帮忙资源禁止盗用，您没有获得授权(XZBBM GROUP)。");
				exit;
			}
		}
	
		$rs = $this->GetData("select file_real_name,file_extension from pd_files where file_id = '{$file_id}' limit 0,1");
		$rs = $rs[0];
			
		if(in_array($ext,array('jpg','png'))){
			header("Location:".$this->GetUrl(array('file_real_name' => $rs['file_real_name'],'page' => $bit,'degree' => '', 'timeout' => 10, 'file_extension' => $ext)));
		}elseif(in_array($ext,array('thumb'))){
			header("Location:".$this->GetUrl(array('file_real_name' => $rs['file_real_name'],'page' => $bit,'degree' => '300', 'timeout' => 10, 'file_extension' => 'png')));
		}elseif(in_array($ext,array('file,pdf'))){
			header("Location:".$this->GetUrl(array('file_real_name' => $rs['file_real_name'],'page' => $bit,'degree' => '', 'timeout' => 10, 'file_extension' => $ext)));
		}
	
	}
	
	/**
	 * @todo 资料下载
	 * @author bo.wang
	 * @version 2013-09-18 14:29
	 */
	public function FileDown() {
		
		//下载接口防盗链
		//if(!strstr($_SERVER['HTTP_REFERER'],'xzbbm.cn')&&!strstr($_SERVER['HTTP_REFERER'],'112.124.50.239')&&$_REQUEST['token']!='kNUxOrw0oeVugRtb'){
		if(!isset($_REQUEST['token']) || $_REQUEST['token']!='kNUxOrw0oeVugRtb'){
			alert('学长帮帮忙，学长为你想的更多~');
			header('Location:http://".DOMAIN."');
			exit;
		}
		 
		//下载Token验证
		if(isset($_REQUEST['ts']) && isset($_REQUEST['token'])){
			if((TIMESTAMP - $_REQUEST['ts']) > 60 || md5($_REQUEST['ts'].'sNkxCrtp13LGsutz') != $_REQUEST['token']){
				go_win(2,'下载链接已过期。');
				exit;
			}
		}
		
		//接口SESSION验证
		if(!isset($_SESSION['dl_times'])){
			$_SESSION['dl_times'] = 1;
		}elseif($_SESSION['dl_times'] > 200){
			go_win(2,'下载次数已超过设定值。');
			exit;
		}else{
			$_SESSION['dl_times']++;
		}
	
		if(isset($_REQUEST['swf'])){
			define('FILESTORE_ROOT','/data/stores/swf/');
		}else{
			define('FILESTORE_ROOT','/data/stores/file/');
		}
	
		@set_time_limit(0);
		@ignore_user_abort(true);
	
		$idf = trim($_REQUEST['idf']);
		$key = trim($_REQUEST['key']);
	
		$rs = $this->_db->rsArray("select * from pd_files where file_index = '$idf' and file_key='$key' limit 0,1");
	
		if(empty($rs)){
			alert('[授权码不正确] - 学长帮帮忙，学长为你想的更多~');
			exit;
		}else{
	
			$file_id = $rs['file_id'];
			$userid = $rs['userid'];
			$file_real_name = $rs['file_real_name'];
			$file_name_short = $rs['file_name'];
			$tmp_ext = $rs['file_extension'] ? '.'.$rs['file_extension'] : "";
			$file_name = $rs['file_name'].'[学长帮帮忙]'.$tmp_ext;
			 
			$file_extension = $rs['file_extension'];
			$file_size = $rs['file_size'];
			$file_store_path = $rs['file_store_path'];
			$is_locked = $rs['is_locked'];
	
			$file_location = FILESTORE_ROOT."$file_store_path/$file_real_name.$file_extension";
	
			if(!file_exists($file_location)){
				alert('[资料已被擦除或不存在] - 学长帮帮忙，学长为你想的更多~'.$file_location);
				exit;
			}
	
			if($is_locked){
				alert('[资料正在审核中，请耐心等待] - 学长帮帮忙，学长为你想的更多~');
				exit;
			}
	
			/* $exp_down = (int)$settings['exp_down'];
			 $db->query_unbuffered("update {$tpf}users set exp=exp+$exp_down where userid='$pd_uid'");
			$exp_down_my = (int)$settings['exp_down_my'];
			$db->query_unbuffered("update {$tpf}users set exp=exp+$exp_down_my where userid='$userid'"); */
	
			/* $db->query_unbuffered("update {$tpf}users set down_flow_count='$down_flow_count' where userid='$userid'"); */
			$this->_db->conn("UPDATE pd_downs SET file_downs = file_downs + 1 WHERE file_id = $file_id");
	
			//下载开始
			ob_end_clean();
			header('Cache-Control: max-age=86400');
			header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T',time()+86400));
			header('Content-Disposition: attachment;filename="'.iconv("utf-8","gb2312",$file_name).'"');
			header('Content-Encoding: none');
			header('Content-Transfer-Encoding: binary');
			header('Content-Type: application/octet-stream');
			header('Content-Length: '.filesize($file_location));
			 
			$chunk = 500; //下载速度控制
			//$speed = get_byte_value(1024);
			//$sleep = $speed ? floor(($chunk/($speed*1024))*1000000) : 0;
			//echo $sleep;exit;
			$sent = 0;
	
			if(($fp = @fopen($file_location,'rb')) === false) exit('Can not open file!');
			do{
				$buf = fread($fp,$chunk);
				$sent += strlen($buf);
				echo $buf;
				ob_flush();
				flush();
				if(strlen($buf) ==0){
					break;
				}
			}while(true);
			@fclose($fp);
		}
	}
	
	/**
	 * @todo 资料页返回随机推荐
	 * @author bo.wang
	 * @version 2013-09-18 14:29
	 */
	public function GetRelatedFile() {
	    
	    $sql = "SELECT file_index,file_name,file_views,file_downs FROM pd_files WHERE ucode = 0 AND has_swf != 0 ORDER BY file_views DESC LIMIT 10,150";
	    
	    $rs = $this->GetData($sql);
	    shuffle($rs);
	    
	    $return = array(
	            'file_name' => $rs[0]['file_name'],
	            'file_index' => $rs[0]['file_index'],
	    		'file_views' => intformat($rs[0]['file_views']),
	    		'file_downs' => intformat($rs[0]['file_downs']),
	            'rand' => mt_rand(0,20)
	            );
	    
	    echo json_encode($return);
	}
	
	
	
	
	/**
	 * @todo 顶踩接口
	 * @author bo.wang
	 * @version 2013-09-18 14:29
	 */
	public function Vote() {
	
	    $type = trim($_GET['type']);
	    $file_id = trim($_GET['id']);
	     
	    $rs = $this->_db->rsArray("SELECT good_count,bad_count FROM pd_files WHERE file_id = $file_id LIMIT 0,1");
	    
	    if($type == 'ding' && !isset($_COOKIE["ding_$file_id"])){
	        $this->_db->conn("UPDATE pd_files SET good_count = good_count + 1 WHERE file_id = $file_id");
	        echo json_encode(array('rs' => 0,'msg' => ($rs[good_count]+1)." 有用"));
	        setcookie("ding_$file_id",true);
	    }elseif($type == 'cai' && !isset($_COOKIE["cai_$file_id"])){
	        $this->_db->conn("UPDATE pd_files SET bad_count = bad_count + 1 WHERE file_id = $file_id");
	        echo json_encode(array('rs' => 0,'msg' => ($rs[bad_count]+1)." 一般"));
	        setcookie("cai_$file_id",true);
	    }else{
	        echo json_encode(array('rs' => 1));
	    }
	}
	
	/**
	 * @todo 获取某个省份学校列表
	 * @author bo.wang
	 * @version 2013-09-18 14:29
	 */
	public function GetUniversities() {
	    
	    echo json_encode($this->GetProvinceInfo('','data',$_REQUEST['province']));
	}
	
	/**
	 * @todo 获取学校学院列表
	 * @author bo.wang
	 * @version 2013-09-18 14:29
	 */
	public function GetColleges() {

		echo json_encode($this->GetUniversityInfo($_REQUEST['university_id'],'','college'));
	}
	
	
	/**
	 * @todo 上传成功之后修改文件名
	 * @author bo.wang
	 * @version 2013-09-18 14:29
	 */
	public function UploadMdfFileName(){
	
		$file_name = $_REQUEST['file_name'];
		$file_index = $_REQUEST['file_index'];
		$file_tag = $_REQUEST['tag'];
		
		if($_SERVER['HTTP_ORIGIN'] != 'http://".DOMAIN."'){
			exit('拒绝服务！');
		}
	
		$this->_db->update('pd_files',array('file_name' => $file_name),"file_index = '$file_index'");
		
		if($file_tag != 'undefined'){
			$this->_db->conn("update pd_files set file_tag = concat(file_tag,',$file_tag') WHERE file_index = '$file_index'");
		}
		
		echo json_encode(array('url' => 'http://".DOMAIN."/view/'.$file_index));
	}
	
	/**
	 * @todo 用户登录判定
	 * @author bo.wang
	 * @version 2013-09-18 14:29
	 */
	public function Login($filename){
	    
	    if($_SESSION['error_login'] > 15){
	        echo json_encode(array('rcode' => 1, 'msg' => '对不起，您输入错误次数过多，已被暂时禁止登陆！'));
	        exit;
	    }
	    
	    $username = trim($_REQUEST['username']);
	    $password = trim($_REQUEST['password']);
	    
	    $rs = $this->_db->rsArray("SELECT password FROM pd_files WHERE username = '$username' LIMIT 0,1");
	    
	    if(empty($rs)){
	        echo json_encode(array('rcode' => 1, 'msg' => '对不起，您的邮箱地址尚未注册！'."SELECT password FROM pd_files WHERE username = '$username' LIMIT 0,1"));
	    }elseif(md5($password) != $rs['password']){
	        $_SESSION['error_login'] = isset($_SESSION['error_login'])?$_SESSION['error_login']++:1;
	        echo json_encode(array('rcode' => 1, 'msg' => '您输入的邮箱地址或密码不正确！'));
	    }else{
	        echo json_encode(array('rcode' => 0));
	    }
        
	}
	
	/**
	 * @todo 资料预览
	 * @author bo.wang
	 * @version 2013-09-18 14:29
	 */
	public function OnlineView($idf){
		
		$this->idf = $idf?$idf:$_REQUEST['idf'];
		include Template('onlineview');
	
	}
	
	/**
	 * @todo 为什么学长帮帮忙
	 * @author bo.wang
	 * @version 2013-09-18 14:29
	 */
	public function ShowMovie(){
		
		$swf_ids = array(
				'XNDc0Njk1MzI4',
				'XNDkwNTc5NDA0',
				'XNDg3NDYxMDQ4',
				'XNDcxNDA1MTUy',
				'XNjM2MjU4MTg4',
				'XNDk3NzY3NDE2',
				'XNDc1Njk2NDU2'
				);
		
		shuffle($swf_ids);
		
		$swf_id = $swf_ids[0];
			
		include Template('showmovie');
		
	}
	
	/**
	 * @todo 降低二维码复杂度
	 * @author bo.wang
	 * @version 2013-09-18 14:29
	 */
	public function V(){
		$this->ViewFile();
	}
	
	/**
	 * @todo 按需求导出资料列表
	 * @author bo.wang
	 * @version 2013-06-06 14:29
	 */
	public function Lists() {
	
		$rs = $this->GetData("SELECT file_id,file_name,concat('http://xzbbm.cn/',file_key) as file_url FROM pd_files WHERE file_name LIKE '%复试%' and file_extension != 'rar' ORDER BY rand() LIMIT 0,30");
	
		echo <<<HTML
			<table style="width: 98%;border-collapse: collapse;border: 1px solid #525C3D;">
HTML;
	
		foreach ($rs as $k => $v){
			if($k%2){
				echo <<<HTML
			<tr>
				<td><img height="125px" width="125px" src="http://".DOMAIN."/?action=QrCode&do=Liantu&file_id={$v['file_id']}"></td>
				<td><a href="{$v['file_url']}" target="_blank">{$v['file_name']}</a></td>
			</tr>
HTML;
			}else{
				echo <<<HTML
			<tr>
				<td><a href="{$v['file_url']}" target="_blank">{$v['file_name']}</a></td>
				<td><img height="125px" width="125px" src="http://".DOMAIN."/?action=QrCode&do=Liantu&file_id={$v['file_id']}"></td>
			</tr>
HTML;
			}
		}
	
		echo <<<HTML
		</table>
HTML;
	}
	
	public function About() {
		
		include Template('common');
	}
	
	public function Wall() {
	
		include Template('list');
	}
	
	public function UploadPage() {
	
		include Template('uploadpage');
	}
	
	public function GetHotWords($from){
		$arr = array('数据库原理','通信原理','化工机械','考研英语','政治万能答题模板','李永乐考研数学','司法考试','公务员考试','高等代数解题方法','C++','金属材料','电动');
		
		if($from == 'self'){
			echo $arr[mt_rand(0,count($arr))];
		}else{
			echo json_encode(array('rs' => $arr[rand(0,count($arr))]));
		}
	}
	
	/**
	 * @todo 微信资料列表页
	 * @author bo.wang
	 * @version 2013-06-06 14:29
	 */
	public function WeiList(){
		
		$uname = $_REQUEST['uname']?$_REQUEST['uname']:'';
		$ucode = $_REQUEST['uid']?$_REQUEST['uid']:'';
		
		$uinfo = $this->GetData("SELECT university_id,name
									 FROM geo_universities
									 WHERE name = '$uname' OR university_id = $ucode");
		
		$uname = $uinfo[0]['name']?$uinfo[0]['name']:'学长帮帮忙';
		$ucode = $uinfo[0]['university_id']?$uinfo[0]['university_id']:0;
		
		$keywords = explode('_', $_REQUEST['keywords']);
		
		foreach($keywords as $k => $v){
			$where .= "file_name RLIKE '".trim($v)."' OR ";
		}
		$where = rtrim($where,'OR ');
		
		$this->rs = $this->GetData("SELECT file_id,file_name,file_key
									FROM pd_files
									WHERE ucode = $ucode
									AND ($where)
									ORDER BY file_views DESC");
		
		include Template('weilist');
	}
}
