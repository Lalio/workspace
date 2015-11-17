<?php
if(!defined('IN_SYS')) {
	header("HTTP/1.1 404 Not Found");
	die;
}

Class SendEmailByUid {

	static function Main($fcode, $uid){

		Core::InitDb();
		$db = Core::$db['online'];
		
		//判断总下载次数，防止刷文件
		$client_ip = get_ip();
			
		$all = $db->rsArray("SELECT count(*) as total FROM xz_emaillist WHERE ts > '".date('Y-m-d 00:00:00',TIMESTAMP)."' and client_ip = '$client_ip'");
		$usr = $db->rsArray("SELECT count(*) as total FROM xz_emaillist WHERE ts > '".date('Y-m-d 00:00:00',TIMESTAMP)."' and uid = $uid");
		$per = $db->rsArray("SELECT count(*) as total FROM xz_emaillist WHERE ts > '".date('Y-m-d 00:00:00',TIMESTAMP)."' and client_ip = '$client_ip' and fid = $fcode");
			
		$all['total'] = intval($all['total']);
		$per['total'] = intval($per['total']);
		$usr['total'] = intval($usr['total']);
			
		if($all['total'] > 15 || $per['total'] > 2 || $usr['total'] > 15){
			$ret["res"] = "您的等级权限 [单份资料日发送<3次 日总发送资料<30份] ，已经超出了这个范围。";
			$ret["rcode"] = 1;
			return $ret;
		}
		
		$data = array(
				'uid' => $uid,
				'fid' => $fcode,
				'client_ip' => $client_ip
			     );
		
		$rs = $db->insert("xz_emaillist", $data);
		if($rs != false){
			$ret["rcode"] = 0;
			$ret["res"] = "资料我已经邮寄给你啦，学长只能帮你到这儿了。";
			rlog(__CLASS__, 'INFO', "send email. uid: $uid, fid: $fcode");
		}else{
			$ret["rcode"] = 1;
			$ret["res"] = "学长的邮差罢工了，我正在做他的思想工作。";
		}
		return $ret;
	}
}
