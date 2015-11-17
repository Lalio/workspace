<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;
}

/**
 * @todo XZBBM后台管理系统
 * @author bo.wang3
 * @version 2013-4-22 14:29
 */
Class Index	extends DbAction{

	public function __Construct(){
		
		if(!($_SERVER['PHP_AUTH_USER'] == 'xzbbm' && $_SERVER['PHP_AUTH_PW'] == 'mNmH592v6')) {
              header('WWW-Authenticate: Basic realm="XZBBM.CN Tools"');
              header('HTTP/1.0 401 Unauthorized');
              echo '未通过HTTP认证，请联系管理员！';
              exit;
         }

         parent::__construct();
         
         //初始化学校数组
         $rs = $this->GetData("SELECT university_id,name,total_files FROM geo_universities ORDER BY total_files DESC");
         $this->schlist[-1] = '不限资料来源';
         $this->schlist[0] = '学长帮帮忙';
         $this->schnamelist[0] = '学长帮帮忙';
         foreach ($rs as $data){
         	$this->schlist[$data['university_id']] = "{$data['name']}({$data['total_files']})";
         	$this->schnamelist[$data['university_id']] = "{$data['name']}";
         }
         
         //初始化学院数组
         $rs = $this->GetData("SELECT * FROM geo_colleges");
         foreach ($rs as $data){
             $this->collist[$data['college_id']] = "{$data['college']}";
         }
         
         //初始化学校学院信息
         foreach ($rs as $data){
             $this->u2c[$data['university_id']][$data['college_id']] = "{$data['college']}";
         }
	}
	
	public function MdfFileInfo(){
	
	    $data[$_REQUEST['op_type']] = urldecode($_REQUEST['value']);
	
	    if($_REQUEST['op_type'] == 'ucode'){
	        $arr = array_flip($this->schnamelist);
	        if(array_key_exists($_REQUEST['value'], $arr)){
	            $data[$_REQUEST['op_type']] = $arr[$_REQUEST['value']];
	        }else{
	            echo json_encode(array('rcode' => 1,'msg' => '学校名输入错误或不存在！'));exit;
	        }
	    }
	
	    if($_REQUEST['op_type'] == 'ccode'){
	        $arr = array_flip($this->u2c[$_REQUEST['ext']]);
	        if(array_key_exists($_REQUEST['value'], $arr)){
	            $data[$_REQUEST['op_type']] = $arr[$_REQUEST['value']];
	        }else{
	            echo json_encode(array('rcode' => 1,'msg' => '该学校下不存在此学院！'));exit;
	        }
	    }
	
	    if($this->_db->update('pd_files',$data,"file_id = ".$_REQUEST['file_id'])){
	        echo json_encode(array('rcode' => 0));
	    }
	
	}

	public function Main(){
		
/* 		$this->id_total = $this->GetData('SELECT count(file_id) as sum FROM pd_files');
		$this->id_total = $this->id_total[0]['sum'];
		$this->md5_total = $this->GetData('SELECT count(file_md5) as sum FROM pd_files GROUP BY file_md5');
		$this->md5_total = $this->md5_total[0]['sum'];
		$this->user_total = $this->GetData('SELECT count(userid) as sum FROM pd_users');
		$this->user_total = $this->user_total[0]['sum'];
		$this->down_total = $this->GetData('SELECT sum(file_downs) as sum FROM pd_files');
		$this->down_total = sprintf("%.0f", $this->down_total[0]['sum']); */
		
		$this->Files();
	}
	
	/**
     * @author bo.wang3
     * @version 2013-4-22 14:29
     */
	public function Files(){

/* 		if($this->func == 'search'){
		    //搜索配置
		    $_REQUEST['starttime'] = strtotime($_REQUEST['starttime']);
		    $_REQUEST['endtime'] = strtotime($_REQUEST['endtime']);
		
		    foreach($_GET as $k => $v){
		        if(!empty($v) && !in_array($k, array('action','do','func','show','page'))){
		            if($k == 'aid'){ //广告ID批量查找
		                $where .= "aid IN ($v) and ";
		            }elseif(is_numeric($v)){
		                $where .= "$k = '$v' and ";
		            }else{
		                //广告参数特殊处理
	                    $v_arr = explode('_',$v);
	                    unset($v);
	                    foreach($v_arr as $t){
	                        $v .= "%$t";
	                    }
	                    
		                $where .= "$k LIKE '%$v%' and ";
		            }
		        }
		    }
		} */
		
		$ucode = $_GET['u']?$_GET['u']:0;
		$type = $_REQUEST['type'] == 'in_recycle'?'in_recycle':'all';
		
		if($ucode == -1){
		    $ucode = 'ucode';
		}
		
		switch ($_GET['o']){
			case 'file_best': $order = ' ORDER BY weight DESC,file_downs DESC';break;
			case 'file_downs': $order = ' ORDER BY file_downs DESC';break;
			default: $order = ' ORDER BY file_id DESC';break;
		}
		
		if($type == 'in_recycle'){
			$where = "in_recycle = 1 AND ucode = $ucode";
		}else{
			$where = "in_recycle = 0 AND ucode = $ucode";
		}
		
		if(isset($_REQUEST['keyword'])){
		    $where = "(file_id = '{$_REQUEST[keyword]}') OR (file_name like '%{$_REQUEST[keyword]}%') OR (file_real_name = '{$_REQUEST[keyword]}')";
		}
		
		$this->BackendDbLogic($_POST,'pd_files','file',$where,$order,30,''); //功能切换、数据、数据表名、模版文件名
	}
	
	/**
	 * @todo 批量生成推广链接
	 * @author bo.wang3
	 * @version 2013-4-22 14:29
	 */
	public function Dapao(){
	
		$ucode = $_GET['uid']?$_GET['uid']:0;
		$ucode = $ucode == -1 ? 'ucode':$ucode;
		
		$keywords = explode(' ', $_REQUEST['keywords']);
		foreach($keywords as $k => $v){
			$where .= "file_name RLIKE '".trim($v)."' OR ";
		}
		$where = rtrim($where,'OR ');
	
		if ($_GET['uid'] != '' && $_GET['keywords'] != ''){
			$this->rs = $this->_db->dataArray("SELECT file_id,file_name,file_key 
												FROM pd_files 
												WHERE ucode = $ucode
												AND ($where)");
		}
	
		include Template('dapao','Index');
	}
	
	/**
	 * @todo 清除指定资源信息
	 * @author bo.wang
	 * @version 2013-06-06 14:29
	 */
	public function CleanFileInfo() {
	
		$ids = trim($_GET['ids']);
		$ids = explode(',',$ids);
	
		foreach ($ids as $id) {
			 
			$file = $this->_db->rsArray("SELECT file_id,file_extension,file_name,file_store_path,file_real_name,file_md5 FROM pd_files WHERE file_id = $id");
			//判定该文件是不是唯一 唯一则删除源文件，不唯一只是清除表信息
			$md5s = $this->_db->rsArray("SELECT file_id FROM pd_files WHERE file_md5 = '$file[file_md5]' LIMIT 0,5");
	
			if(count($md5s) == 1){
				$path = "/data/stores/file/$file[file_store_path]/$file[file_real_name].$file[file_extension]";
				unlink($path);
	
				$path = "/data/stores/pdf/$file[file_store_path]/$file[file_real_name].pdf";
				unlink($path);
	
				$path = "/data/stores/swf/$file[file_store_path]/$file[file_real_name].swf";
				unlink($path);
	
				for($i=0;$i<10;$i++){
					$path = "/data/stores/img/$file[file_store_path]/$file[file_real_name]-$i.jpg";
					unlink($path);
					$path = "/data/stores/img/$file[file_store_path]/$file[file_real_name]-big-$i.jpg";
					unlink($path);
				}
	
				for($i=0;$i<10;$i++){
					$path = "/data/stores/png/$file[file_store_path]/$file[file_real_name]-$i.png";
					unlink($path);
					$path = "/data/stores/png/$file[file_store_path]/$file[file_real_name]-big-$i.png";
					unlink($path);
				}
			}
	
			$this->_db->conn("DELETE FROM pd_files WHERE file_id = $id");
			echo "$file[file_name]($file[file_id])文件信息清除成功。";
		}
	}
	
	public function GetTag(){
	
		$rs = $this->_db->rsArray('SELECT file_tag FROM pd_files WHERE file_id = '.$_REQUEST['file_id']);
		
		echo json_encode($rs['file_tag']);
	
	}

}