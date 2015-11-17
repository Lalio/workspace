<?php
if ( !defined( 'IN_SYS' ) ) {
	header( "HTTP/1.1 404 Not Found" );
	die;
}

class Tools extends Xzbbm{

	private $db;
	public  $file;

	public function __construct(){
		
		//该类仅可运行在cli模式下
		if(!isset($_SERVER['SHELL']))  exit('脚本禁止运行');
		
		parent::__construct();
	}

	/**
	 * @todo 写入收益计划公式
	 * @author bo.wang
 	 * @version 2013-06-06 14:29
	 */
	public function InputProfile() {

		//解析文档
		$data =file_get_contents('/home/miracle/profile');
		$data = explode("\n", $data);
		
		foreach($data as $k => $v){
			var_dump($this->_db->insert('xz_profile',array('times' => $k,'profile' => $v)));
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
	 * @todo 校正location字段
	 * @author bo.wang
	 * @version 2013-06-06 14:29
	 */
	public function ReWriteLocation() {
			
		$rs = $this->_db->dataArray("SELECT file_id FROM pd_files WHERE location = ''");
	
		foreach($rs as $v){
			$file_ids[] = $v['file_id'];
		}
	
		foreach($file_ids as $id){
			$o = $this->_db->rsArray("SELECT ip FROM pd_files WHERE file_id = $id");
			$location = get_adress($o['ip']);
			if(true === $this->_db->update('pd_files',array('location' => $location),'file_id = '.$id)){
				echo $id."\n";
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
			
		$rs = $this->_db->dataArray("SELECT file_id FROM pd_files WHERE file_name LIKE '%复试%'");
	
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
	 * @todo 修改pd_files表的ucode字段，从原来的xz_university表的编号改到geo_universities表的编号
	 * @author bo.wang3
	 * @version 2013-10-26
	 */
	public function Tb(){
	
		$rss = $this->_db->dataArray("SELECT file_id,file_index FROM pd_files WHERE file_id BETWEEN 5000 AND 15000");
		
		foreach($rss as $rs){
	
			$o = $this->_db->rsArray("SELECT university_id FROM pd_files_old,geo_universities WHERE pd_files_old.file_index = '{$rs['file_index']}' and pd_files_old.ucode = geo_universities.sicon_id LIMIT 0,1");
			
			if(!empty($o)){
				$this->_db->update('pd_files',array('ucode' => $o['university_id']),"file_id = {$rs[file_id]}");
				echo $rs['file_id'].'<br/>';
			}
			unset($o);
		}
		echo 'ok';
	}
	
	/**
	 * @todo 同步sicon字段，补全geo_universities的数据
	 * @author bo.wang3
	 * @version 2013-10-26
	 */
	public function Tb1(){
	
		$rss = $this->_db->dataArray("SELECT * FROM xz_universities");
	
		foreach($rss as $rs){
	
			$c = $this->_db->rsArray("SELECT name from geo_universities where name LIKE '%{$rs['name']}%'");
			if(empty($c)){
				$m = $this->_db->rsArray("SELECT province_id FROM geo_provinces WHERE province LIKE '%{$rs[province]}%'");
				$v = array(
						'name' => $rs['name'],
						'province' => $m['province_id'],
						'sicon_id' => $rs['id']
				);
				$this->_db->insert('geo_universities',$v);
				echo $rs['id'].',';
			}else{
				$this->_db->update('geo_universities',array('sicon_id' => $rs['id']),"name LIKE '%{$rs['name']}%'");
			}
			unset($c);
		}
		echo 'ok';
	}
	
	/**
	 * @todo 导出学院数据不全的学校的数据
	 * @author bo.wang3
	 * @version 2013-10-26
	 */
	public function Tb3(){
	
		$rss = $this->_db->dataArray("SELECT * FROM geo_universities,geo_provinces WHERE geo_universities.name NOT LIKE '%职业%' and  geo_universities.name NOT LIKE '%专科%' and geo_universities.name NOT LIKE '%艺术%'  and geo_universities.name NOT LIKE '%学院%' and geo_universities.name NOT LIKE '%学校%'  and geo_universities.name NOT LIKE '%分校%'  and geo_universities.name NOT LIKE '%校区%'  and geo_universities.name NOT LIKE '%分院%' and geo_universities.name NOT LIKE '%职工%' and geo_universities.name NOT LIKE '%广播%' and geo_universities.province = geo_provinces.province_id");
	
		foreach($rss as $rs){
	
			$c = $this->_db->rsArray("SELECT college from geo_colleges where university_id = {$rs['university_id']} limit 0,1");
			if(empty($c)){
				echo "{$rs['name']}<br>";
			}
			unset($c);
		}
		echo 'ok';
	}
	
	/**
	 * @todo 学院数据批量入库
	 * @author bo.wang3
	 * @version 2013-10-26
	 */
	public function Tb4(){
	
		foreach(glob('/home/miracle/colleges/*') as $v){
			//glob ()这里就不介绍了，你可以看看php手册
			$files[] = $v;
		}
		
		foreach($files as $v){
			$tmp = explode('/', $v);
			$tmp2 = explode('.', $tmp[4]);
			$names[] = $tmp2[0];
		}
		
		//var_dump($files);
		//var_dump($names);
		//foreach ($names as $k=>$v){
			//$rs = $this->_db->rsArray("SELECT university_id from geo_universities WHERE name = '$v'");
			
			//var_dump(system("mv -f {$names[$k]} /home/miracle/colleges/{$rs[university_id]}.txt'"));
		//}
		
		foreach ($files as $k => $v){
			$ctc = iconv('gb2312', 'utf8', file_get_contents($v));
			if(empty($ctc)){ //兼容编码
				$ctc = iconv(mb_detect_encoding(file_get_contents($v)), 'utf8', file_get_contents($v));
			}
			$ctc_a = explode("\n", $ctc);
			foreach($ctc_a as $m => $p){
				$ctc_a[$m] = trim($p);
				$ctc_a[$m] = str_replace($names[$k],'',$p);
				if(empty($ctc_a[$m])) unset($ctc_a[$m]);
				if(!strstr($ctc_a[$m],'院') && !strstr($ctc_a[$m],'系') && !strstr($ctc_a[$m],'中心') && !strstr($ctc_a[$m],'班') && !strstr($ctc_a[$m],'部')) unset($ctc_a[$m]);
			}
			$infos[$names[$k]] = $ctc_a;
			unset($ctc);
			unset($ctc_a);
		}
		
		foreach($infos as $k=>$v){
			foreach($v as $m=>$p){
				$infos[$k][$m] = trim($p);
			}
		}
		
		foreach($infos as $k=>$v){
			$rs = $this->_db->rsArray("SELECT university_id from geo_universities WHERE name = '$k'");
			$uid = $rs['university_id'];
			
			foreach ($v as $data){
				var_dump($this->_db->insert('geo_colleges',array('college' => $data,'university_id' => $uid)));
			}
			unset($rs);
			unset($uid);
		}
	}
	
	public function BuildSearchEngine(){
		
		$rss = $this->_db->dataArray("select file_id from pd_files where file_id not in (select file_id from xz_search_engine)");
		
		foreach ($rss as $rs){
			$data = $this->_db->rsArray("SELECT file_id,file_name,file_tag,file_description from pd_files where file_id = ".$rs['file_id']);
			
			//对中文分词后入库
			$key_fc = file_get_contents("http://2.xzbbm.sinaapp.com/main/?action=ChFc&str=".urlencode($data['file_name']));
			$key_fc = json_decode($key_fc,true);
			foreach($key_fc as $v){
				$new['file_name'] .= str_replace("\"","",json_encode($v['word'])).' ';
			}
			
			$key_fc = file_get_contents("http://2.xzbbm.sinaapp.com/main/?action=ChFc&str=".urlencode($data['file_tag']));
			$key_fc = json_decode($key_fc,true);
			foreach($key_fc as $v){
				$new['file_tag'] .= str_replace("\"","",json_encode($v['word'])).' ';
			}
			
			$key_fc = file_get_contents("http://2.xzbbm.sinaapp.com/main/?action=ChFc&str=".urlencode($data['file_description']));
			$key_fc = json_decode($key_fc,true);
			foreach($key_fc as $v){
				$new['file_description'] .= str_replace("\"","",json_encode($v['word'])).' ';
			}
			
			$new['file_id'] = $data['file_id'];
			
			var_dump($this->_db->insert('xz_search_engine',$new));
			echo "\t\n";
			unset($new);
		}
		
	}
}
