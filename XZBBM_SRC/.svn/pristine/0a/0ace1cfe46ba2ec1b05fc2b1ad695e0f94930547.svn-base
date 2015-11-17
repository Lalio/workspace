<?php
if ( !defined( 'IN_SYS' ) ) {
	header( "HTTP/1.1 404 Not Found" );
	die;
}

class Tools {

	private $db;
	public  $file;

	public function __construct(){
        
    	if(!($_SERVER['PHP_AUTH_USER'] == 'xzbbm' && $_SERVER['PHP_AUTH_PW'] == 'mNmH592v')) {
              header('WWW-Authenticate: Basic realm="XZBBM.CN Tools"');
              header('HTTP/1.0 401 Unauthorized');
              echo '未通过HTTP认证，请联系管理员！';
              exit;
         }
         
		Core::InitDb(array('online','tmp'));
		$this->_db = Core::$db['online'];
		$this->_db_tmp = Core::$db['tmp'];
	}

	/**
	 * @todo 清除接口缓存
	 * @author bo.wang
 	 * @version 2013-06-06 14:29
	 */
	public function CleanCache() {

		$res = system("rm -rf /data/tmp/cache/data", $retval);
		echo "接口缓存清除成功！- " . date('Y-m-d H:i:s');
	}


	/**
	 * @todo 同步SVN代码
	 * @author bo.wang
 	 * @version 2013-06-06 14:29
	 */
	public function SvnUp() {

		$res = system("cd /data/backend_service/src/api/;svn up;", $retval);
		echo "服务器代码更新成功！- " . date('Y-m-d H:i:s');
		
	}

	/**
	 * @todo 批量写入简介信息
	 * @author bo.wang
 	 * @version 2013-06-06 14:29
	 */
	public function InputDescription() {

		$data = array('file_description' => '学长提示：优异的成绩源于踏实的付出，多看书、多思考、多做题才是获得高分的根本保证。');

		for($i=0;$i<15000;$i++){

			$file = $this->_db->rsArray("SELECT file_id,file_description FROM pd_files WHERE file_id = $i");

			if(!empty($file) && empty($file['file_description'])){
				var_dump($this->_db->update('pd_files',$data,"file_id = $i"));
			}

		}
		
	}

	/**
	 * @todo 批量生成md5和file_size
	 * @author bo.wang
 	 * @version 2013-06-06 14:29
	 */
	public function MakeMd5() {

		$sql = "SELECT file_id from pd_files limit 1,12000";
		$rs = $this->_db->dataArray($sql);

		foreach ($rs as $data) {
			$sql = "SELECT file_store_path,file_real_name,file_extension from pd_files where file_id = $data[file_id] limit 1";
			$rs = $this->_db->rsArray($sql);
			$file_path = "/data/stores/file/$rs[file_store_path]/$rs[file_real_name].$rs[file_extension]";
			
			$v = array(
				'file_md5' => md5_file($file_path),
				'file_size' => filesize($file_path)
				);
			var_dump($this->_db->update('pd_files',$v,'file_id = '.$data['file_id']));
		}
		
	}

	/**
	 * @todo 文件去重脚本
	 * @author bo.wang
 	 * @version 2013-06-06 14:29
	 */
	public function CleanRepeat() {

		$sql = "SELECT file_id FROM pd_files GROUP BY file_md5 HAVING (COUNT( * )) >1";
		$rs = $this->_db->dataArray($sql);
		foreach($rs as $v){
			$str .= $v[file_id].',';
		}

		//var_dump($str);exit;
		file_get_contents('http://112.124.50.239:8866/?action=Tools&do=CleanFileInfo&auth=wb3108010638&ids='.$str);
       
	}
	
	/**
	 * @todo 导出学校列表
	 * @author bo.wang
	 * @version 2013-06-06 14:29
	 */
	public function OutPutSchoolList() {
	
	    $sql = "SELECT id,name FROM xz_universities limit 0,300";
	    $rs = $this->_db->dataArray($sql);
	    echo "<table border='1'>";
	    echo "<tr><td>学校编码</td><td>学校名称</td></tr>";
	    foreach($rs as $data){
	        echo "<tr><td>$data[id]</td><td>$data[name]</td></tr>";
	    }
	    echo "</table>";
	}
	
	/**
	 * @todo 批量转移TAG
	 * @author bo.wang
	 * @version 2013-10-25 14:29
	 */
	public function MoveTags() {
	
	    for($i=0;$i<1200;$i++){
	        $rs = $this->_db->rsArray("SELECT * FROM pd_file2tag WHERE ftid = $i LIMIT 1");
	        if(!empty($rs)){
	            $data = array(
	                    'file_tag' => $rs[tag_name]
	                    );
	            var_dump($this->_db->update('pd_files',$data,"file_id = $rs[file_id]"));
	        }
	    }
	}
	
	public function GoodFilesInput() {
	    $file_path = $_REQUEST['file_path'];
	
	    if(file_exists($file_path)){
	        
	        $ucode = 1;
	        //$tag = trim($_REQUEST['tag']); //文档标签
	
	        $tmp = explode('.', $file_path);
	        $file_extension = $tmp[1];
	        $tmp = explode('/', $tmp[0]);
	        $file_name = $tmp[(count($tmp)-1)];
	        $file_md5 = md5_file($file_path);
	        $file_size = filesize($file_path);
	        $file_real_name = md5(uniqid(mt_rand(),true).microtime().'1');
	        $file_key = makerandom(8);
	
	        //$y = 2013;
	        //$m = mt_rand(1,7);
	        $y = 2013;//$y = 2012;
	        $m = mt_rand(8,8);//$m = mt_rand(11,12);
	        if($m < 10){
	            $m = '0'.$m;
	        }
	        $d = mt_rand(1,10);//$d = mt_rand(1,28);
	        if($d < 10){
	            $d = '0'.$d;
	        }
	
	        $store_dir = '/data/stores/file';
	        $file_store_path = "$y/$m/$d";
	
	        $y_dir = $store_dir.'/'.$y.'/';
	        $m_dir = $y_dir.$m.'/';
	        $d_dir = $m_dir.$d.'/';
	
	        if(!is_dir($y_dir)){
	            mkdir($y_dir ,0777);
	        }
	        if(!is_dir($m_dir)){
	            mkdir($m_dir ,0777);
	        }
	        if(!is_dir($d_dir)){
	            mkdir($d_dir ,0777);
	        }
	
	        $ins = array(
	                'file_name' => $file_name,
	                'file_key' => $file_key,
	                'file_extension' => $file_extension,
	                'file_description' => '',
	                'file_store_path' => $file_store_path,
	                'file_real_name' => $file_real_name,
	                'file_md5' => $file_md5,
	                'file_size' => $file_size,
	                'file_time' => (strtotime($file_store_path) + mt_rand(0,86400)),
	                'is_checked' => 1,
	                'report_status' => 0,
	                'userid' => 1,
	                'ip' => '113.107.234.109',
	                'ucode' => $ucode
	        );
	
	        $dest = "$store_dir/$file_store_path/$file_real_name.$file_extension";
	        echo $dest;
	        echo $file_path;
	        if(true === copy($file_path,$dest)){
	            $rs_fid = $this->_db->insert('pd_files',$ins);
	            if(is_numeric($rs_fid)){
	                if(isset($tag)){
	                    $tag_arr = explode(',', $tag);
	                    foreach ($tag_arr as $t){
	                        $file_tag = array(
	                                tag_name => $t,
	                                file_id => $rs_fid
	                        );
	                        $this->_db->insert('pd_file2tag',$file_tag);
	                    }
	                }
	                echo $file_path.' - 文件入库成功! ID：'.$rs_fid.'<br>';
	            }else{
	                unlink($dest);
	                echo $file_path.' - 文件入库失败,文件已经存在！<br>';
	            }
	        }else{
	            echo $file_path.'- 文件复制失败，请检查磁盘空间余量！<br>';
	            var_dump($ins);
	        }
	    }else{
	        echo $file_path." - 文件不存在，请检查原路径是否正确。";
	        exit;
	    }
	}
	
	/**
	 * @todo 文件入库脚本
	 * @author bo.wang
	 * @version 2013-06-06 14:29
	 */
	public function FileInput() {
	    
        $ucode = 1;
        $file_tag = trim($_REQUEST['tag']); //文档标签

        $file_extension = 'zip';
        
        $file_path = $_FILES["file"]["tmp_name"];
        $file_name = $_FILES["file"]["name"];
        $file_md5 = md5_file($file_path);
        $file_size = filesize($file_path);
        $file_real_name = md5(uniqid(mt_rand(),true).microtime().'1');
        $file_key = makerandom(8);

        //$y = 2013;
        //$m = mt_rand(1,7);
        $y = 2013;//$y = 2012;
        $m = mt_rand(10,10);//$m = mt_rand(11,12);
        if($m < 10){
            $m = '0'.$m;
        }
        $d = mt_rand(1,25);//$d = mt_rand(1,28);
        if($d < 10){
            $d = '0'.$d;
        }

        $store_dir = '/data/stores/file';
        $file_store_path = "$y/$m/$d";

        $y_dir = $store_dir.'/'.$y.'/';
        $m_dir = $y_dir.$m.'/';
        $d_dir = $m_dir.$d.'/';

        if(!is_dir($y_dir)){
            mkdir($y_dir ,0777);
        }
        if(!is_dir($m_dir)){
            mkdir($m_dir ,0777);
        }
        if(!is_dir($d_dir)){
            mkdir($d_dir ,0777);
        }

        $ins = array(
                'file_name' => $file_name,
                'file_tag' => $file_tag,
                'file_key' => $file_key,
                'file_extension' => $file_extension,
                'file_description' => '',
                'file_store_path' => $file_store_path,
                'file_real_name' => $file_real_name,
                'file_md5' => $file_md5,
                'file_size' => $file_size,
                'file_time' => (strtotime($file_store_path) + mt_rand(0,86400)),
                'is_checked' => 1,
                'report_status' => 0,
                'userid' => 1,
                'ip' => '113.107.234.109',
                'ucode' => $ucode
        );

        $dest = "$store_dir/$file_store_path/$file_real_name.$file_extension";
        echo $dest;
        echo $file_path;
        if(true === copy($file_path,$dest)){
            $rs_fid = $this->_db->insert('pd_files',$ins);
            if(is_numeric($rs_fid)){
                echo $file_path.' - 文件入库成功! ID：'.$rs_fid.'<br>';
            }else{
                unlink($dest);
                echo $file_path.' - 文件入库失败,文件已经存在！<br>';
            }
        }else{
            echo $file_path.'- 文件复制失败，请检查磁盘空间余量！<br>';
            var_dump($ins);
        }
	}

	/**
	 * @todo 清除失效文件信息
	 * @author bo.wang
	 * @version 2013-06-06 14:29
	 */
	public function CleanInValidFileInfo() {

		for($i = 0 ; $i < 100000 ; $i ++ ){
			$file = $this->_db->rsArray("SELECT file_store_path,file_real_name,file_extension from f_tmp where file_id = $i limit 0,1");
			if(!empty($file)){
				$path = "/data/stores/file/$file[file_store_path]/$file[file_real_name].$file[file_extension]";
#if(!file_exists($path)){
#	echo "($i)$path - 文件不存在！ <br>";
#	$this->_db->conn("DELETE FROM pd_files WHERE file_id = $i");
#}
				$size = filesize($path);
				if($size < 20){
					echo "($i)$path - 文件大小为: $size . <br>";
					$this->_db->conn("DELETE FROM f_tmp WHERE file_id = $i");
					if (unlink($path)) {
						echo "The file was deleted successfully.", "\n";
					} else {
						echo "The specified file could not be deleted. Please try again.", "\n";
					}
				}
			}
		}

	}
	
	/**
	 * @todo 校正文件信息
	 * @author bo.wang
	 * @version 2013-06-06 14:29
	 */
	public function MdfFileInfo() {
	    
	    $id = $_REQUEST['id']?$_REQUEST['id']:8;
	    $value = $_REQUEST['value'];
	    
	    if(isset($_GET['mdname'])){
	        $this->_db->conn("UPDATE pd_files SET file_name = '$value' WHERE file_id = $id");
	    }elseif(isset($_GET['mddes'])){
	        $this->_db->conn("UPDATE pd_files SET file_description = '$value' WHERE file_id = $id");
	    }else{
	        $this->file = $this->_db->rsArray("SELECT * from pd_files WHERE file_id = $id");
	        if(empty($this->file)){
	            if(isset($_GET['pre'])){
	                $rs = $this->_db->rsArray("SELECT file_id FROM pd_files WHERE file_id < $id order by file_id desc LIMIT 1");
	                header("Location:http://www.xzbbm.cn/?action=Tools&do=MdfFileInfo&id=$rs[file_id]");
	            }else{
	                $rs = $this->_db->rsArray("SELECT file_id FROM pd_files WHERE file_id > $id order by file_id asc LIMIT 1");
	                header("Location:http://www.xzbbm.cn/?action=Tools&do=MdfFileInfo&id=$rs[file_id]");
	            }
	            exit;
	        } 
	        include Template('filerewrite','backend');
	    }
	}
	
	/**
	 * @todo 校正swf_able字段
	 * @author bo.wang
	 * @version 2013-06-06 14:29
	 */
	public function ReWriteSwf() {
		 
		$rs = $this->_db->dataArray("SELECT file_id FROM pd_files");

		foreach($rs as $v){
			$file_ids[] = $v['file_id'];
		}
		
		foreach($file_ids as $id){
			$o = $this->_db->rsArray("SELECT file_store_path,file_real_name FROM pd_files WHERE swf_able = 0 AND file_id = $id");
			$path = "/data/stores/swf/$o[file_store_path]/$o[file_real_name].swf";
			if(file_exists($path)){
				//echo $path.'</br>';
				$this->_db->update('pd_files',array('swf_able' => 1),"file_id = $id");
			}else{
				echo $path.'</br>';
				$this->_db->update('pd_files',array('swf_able' => 0),"file_id = $id");
			}
		}
	}
	
	/**
	 * @todo 校正file_key字段
	 * @author bo.wang
	 * @version 2013-06-06 14:29
	 */
	public function ReWriteFileKey() {
			
		$rs = $this->_db->dataArray("SELECT file_id FROM pd_files");
	
		foreach($rs as $v){
			$file_ids[] = $v['file_id'];
		}
	
		foreach($file_ids as $id){
			$this->_db->Conn("UPDATE pd_files SET file_key = '".makerandom(5)."' WHERE file_id = $id");
			echo $id.'<br>';
		}
	}
	
	/**
	 * @todo 校正file_name字段
	 * @author bo.wang
	 * @version 2013-06-06 14:29
	 */
	public function ReWriteFileName() {
			
		$rs = $this->_db->dataArray("SELECT file_id FROM pd_files WHERE file_name LIKE '%初试真题%'");
	
		foreach($rs as $v){
			$file_ids[] = $v['file_id'];
		}
	
		foreach($file_ids as $id){
			$tar = $this->_db->rsArray("SELECT file_name,ucode FROM pd_files WHERE file_id = $id");
			
			if(false === strstr($tar['file_name'],'大学')){
				$u = $this->_db->rsArray("SELECT name FROM xz_universities WHERE id = {$tar['ucode']}");
				if(strstr($tar['file_name'],'学院') && strstr($u['name'],'学院')){

				}else{
					$this->_db->update('pd_files',array('file_name' => $u['name'].$tar['file_name']),'file_id = '.$id);
					echo $id.'<br />';
				}
				//$u = $this->_db->rsArray("SELECT name FROM xz_universities WHERE id = {$tar['ucode']}");			
			}
		}
	}
	
	/**
	 * @todo 补全数据
	 * @author bo.wang
	 * @version 2013-06-06 14:29
	 */
	public function Restore() {
		
		$rs = $this->_db->dataArray("SELECT id,name FROM xz_universities");
		foreach($rs as $v){
			$schs[$v['id']] = $v['name'];
		}

		foreach($names as $name){
			$sql .= "update pd_files set file_cname = replace(file_cname,'$name','');";
			
			//echo $id.$a[name].'<br>';
		}
		echo $sql;
		//var_dump($this->_db->conn($sql));
	}
	
	/**
	 * @todo 从临时库恢复数据
	 * @author bo.wang
	 * @version 2013-06-06 14:29
	 */
	public function ReBack() {
		
		for($i = 183725;$i <= 183764;$i ++){
			
			$arr = $this->_db_tmp->rsArray("SELECT file_name,file_cname FROM pd_files WHERE file_id = $i and ucode = 145");
			var_dump($this->_db_tmp);
			var_dump($arr);
			if(false !== $arr){
				var_dump($this->_db->update('pd_files',$arr,'file_id = '.$i));
			}
			
			//echo '1-';
			//var_dump($arr1);
			
			//$arr2 = $this->_db->rsArray("SELECT file_id,file_name FROM pd_files WHERE file_id = $i and ucode = 145");
			//echo '2-';
			//var_dump($arr2);
			//var_dump($this->_db->update('pd_files',array('file_name' => $arr['file_name']),"file_id = $i"));
		}
	}
}
