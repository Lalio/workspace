<?php
if (!defined('IN_SYS')) {
	header("HTTP/1.1 404 Not Found");
	die;
}

define("IS_LAST_FILE", 1);
define("NOT_LAST_FILE", 0);

class UploadFile {
	static function Main($schoolcode, $uid, $filedesc, $filecount, $file_tag, $islastfile, $file)
	{
	//	echo "$schoolcode $cademyid $filedesc $filecount $categoryid $islastfile<br />";
		$filename = $file['name'];

		$log_path = "/data/log/upload_pic.log";
		file_put_contents($log_path, "upload $filename success. uid:$uid  file_tag:$file_tag islastfile: $islastfile \n", FILE_APPEND);

		$file_info = explode('.', $file["name"]);
		$file_name = $file_info[0];
		$file_extension = $file_info[(count($file_info) - 1)];
		$file_path = $file['tmp_name'];


		$handy_file_tmp_path = "/data/handy_upload/files/";
		if (!is_dir($handy_file_tmp_path)) {
			file_put_contents($log_path, "/data/handy_upload/files is not exist\n", FILE_APPEND);
			//return "fail";
			$ret['rcode'] = 1;
			return $ret;
		}

		$file_dest = $handy_file_tmp_path . $filedesc . "-$filecount." . $file_extension;
		if (file_exists($file_dest)) {
			file_put_contents($log_path, "$file_dest is existed\n", FILE_APPEND);
		}
		
		if(false === move_uploaded_file($file_path,$file_dest)) {
			file_put_contents($log_path, "move $file_path to $file_dest failed\n", FILE_APPEND);
			//return "fail";
			$ret['rcode'] = 1;
			return $ret;
		}

		file_put_contents($log_path, "move $file_path to $file_dest success\n", FILE_APPEND);

		if ($islastfile == IS_LAST_FILE) {
			$file_key = makerandom(5);

			for($i=0; $i < ($filecount + 1); $i++)
			{
				$src_name = $handy_file_tmp_path . $filedesc . "-$i." . $file_extension;
				$new_name = $handy_file_tmp_path . "pic-" . $file_key . "-$i" . ".png";
				rename($src_name, $new_name);
				file_put_contents($log_path, "rename $src_name to $new_name success\n", FILE_APPEND);
			}
			
			$zip_name = $handy_file_tmp_path . $filedesc . ".zip";
			$src_files = $handy_file_tmp_path . "pic-" . $file_key . "*";
			exec("zip -qj $zip_name $src_files");

			$file_md5 = md5_file($zip_name);
			$file_size = filesize($zip_name);
			$file_index = md5(uniqid(mt_rand(),true).microtime().'1');
			$file_real_name = $file_index;

			$y = date('Y',TIMESTAMP);
			$m = date('m',TIMESTAMP);
			$d = date('d',TIMESTAMP);

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

			$store_png_dir = '/data/stores/png';

			$y_dir = $store_png_dir.'/'.$y.'/';
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

			//检查有没有已经存在的文件
			Core::InitDb();
			$db = Core::$db['online'];
			$file_alreay = $db->dataArray("SELECT file_store_path,file_real_name FROM pd_files WHERE file_md5 = '$file_md5' LIMIT 0,1");
			$ins = array(
					'file_name' => $filedesc,
					'file_index' => $file_index,
					'file_tag' => $file_tag,
					'file_key' => $file_key,
					'file_extension' => "zip",
					'file_description' => $filedesc,
					'file_store_path' => empty($file_alreay['file_store_path'])?$file_store_path:$file_alreay['file_store_path'],
					'file_real_name' => empty($file_alreay['file_real_name'])?$file_real_name:$file_alreay['file_real_name'],
					'file_md5' => $file_md5,
					'file_size' => $file_size,
					'file_time' => TIMESTAMP,
					'is_checked' => 0,
					'userid' => $uid,
					'ip' => get_ip(),
					'location' => get_adress(),
					'ucode' => $schoolcode,
					'has_png' => $filecount+1
				    );

			$dest = "$store_dir/$file_store_path/$file_real_name.zip";
			$rs_fid = $db->insert('pd_files',$ins);
			if ($rs_fid != false) {
				rename($zip_name,$dest);
				file_put_contents($log_path, "move $zip_name to $dest\n", FILE_APPEND);
			}
				
			for($i=0; $i < ($filecount + 1); $i++)
			{
				$png_src_name = $handy_file_tmp_path . "pic-" . $file_key . "-$i" . ".png";
				if( $filecount == 0){
					$png_dst_name = "$store_png_dir/$file_store_path/$file_real_name" . ".png";
				}else{
					$png_dst_name = "$store_png_dir/$file_store_path/$file_real_name" . "-$i" . ".png";
				}
				rename($png_src_name, $png_dst_name);
				file_put_contents($log_path, "rename $png_src_name to $png_dst_name success\n", FILE_APPEND);
			}
			
		}
		//return "success";
		$ret['rcode'] = 0;
		$info = array(
			'file_id' => $rs_fid,
			'file_name' => $filedesc,
			'file_extension' => "zip",
			'download_addr' => "http://www.xzbbm.cn/?do=FileDown&idf=$ins[file_real_name]&key=$ins[file_key]&token=kNUxOrw0oeVugRtb",
			'qrcode_str' => "http://www.xzbbm.cn/?do=ViewFile&file_id=$rs_fid&download&from=app"
		);
		$ret['rinfo'] = $info;

		return $ret;
	}
}

?>
