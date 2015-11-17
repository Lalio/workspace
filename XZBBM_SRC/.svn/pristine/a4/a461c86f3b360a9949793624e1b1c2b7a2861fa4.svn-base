l<?php
if(!defined('IN_SYS')) {    
	header("HTTP/1.1 404 Not Found");    
	die;
}

Class GetFileInfo {      

	static function Main($fcode){
	
       	Core::InitDb();
		$db = Core::$db['online'];
		$rsql = $db->rsArray("select * from pd_files where file_id = $fcode limit 1");

		$rs["file_name"] = $rsql["file_name"];
		$rs["file_time"] = $rsql["file_time"];
		$user = $db->rsArray("SELECT username from pd_users WHERE userid = $rsql[userid]");
		$uploader = strstr($user["username"],"@")?half_replace($user["username"]):$user["username"];
		$rs["file_description"] = "        本资料由可以帮你更多的好学长（姐） $uploader 友情贡献，优异的成绩源于踏实的付出，多看书、多思考、多练习才是掌握知识的根本途径。";
		//$rs["file_description"] = " 本资料由 $uploader 学长（姐）友情贡献，优异的成绩源于踏实的付出，多看书、多思考、多练习才是掌握知识的根本途径。";
		$rs["pic_count"] = $rsql["pic_count"];
		$rs["file_extension"] = $rsql["file_extension"];
		$rs["file_views"] = $rsql["file_views"];
		$rs["good_count"] = $rsql["good_count"];
		$rs["file_downs"] = $rsql["file_downs"];
		$rs["file_time"] = date("Y-m-d",$rsql["file_time"]);
		$rs["file_size"] = formatSize($rsql["file_size"]);
		$rs["download_addr"] = "http://www.xzbbm.cn/?do=FileDown&idf=$rsql[file_real_name]&key=$rsql[file_key]&token=kNUxOrw0oeVugRtb";
		$rs["qrcode_str"] = "http://www.xzbbm.cn/?do=ViewFile&file_id=$rsql[file_id]&from=app";
		$rs["file_preview"] = "http://cdn.xzbbm.cn/img/$rsql[file_store_path]/$rsql[file_real_name]-";

		return $rs;
	}
}
