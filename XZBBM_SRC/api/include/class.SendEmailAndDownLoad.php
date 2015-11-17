<?php
if(!defined('IN_SYS')) {
	header("HTTP/1.1 404 Not Found");
	die;
}

Class SendEmailAndDownLoad {

	static function Main($fcode, $email){

		Core::InitDb();
		$db = Core::$db['online'];
		
		//为了更合理一点，这个判断逻辑改到客户端了		
 		if (!preg_match("/\b[a-zA-Z0-9._%-]+@[a-zA-Z0-9._%-]+\.[a-z]{2,4}\b/",$email)){
			$ret["res"] = "你确定输入了正确的邮箱的地址吗？别闹了少年~";
			$ret["rcode"] = 1;
			return $ret;
		} 
			
		$user = array(
				'username' => $email,
				'password' => md5("xzbbm"),
				'email' => $email,
				'gid' => 4,
				'is_activated' => 0,
				'is_locked' => 0,
				'last_login_time' => 0,
				'reg_time' => TIMESTAMP,
				'reg_ip' => $_SERVER['REMOTE_ADDR'],
				'credit' => 10,
				'exp' => 20,
				'accept_pm' => 1
		);
		
		if($fcode > 0){ //按邮件地址发送邮件
			
			//检查用户是否已注册,已注册就直接发送邮件
			$r_sel = $db->rsArray("select userid from pd_users where email = '".$email."'");
			
			if($r_sel === false){
				$ret["res"] = "资料发送失败啦，攻城师们正在查看是哪里出了问题~";
				$ret["rcode"] = 1;
				return $ret;
			}
			
			if(!empty($r_sel["userid"])){
				
				$uid = $r_sel["userid"];
				
			//判断总下载次数，防止刷文件
				$client_ip = get_ip();
				
				$all = $db->rsArray("SELECT count(*) as total FROM xz_emaillist WHERE ts > '".date('Y-m-d 00:00:00',TIMESTAMP)."' and client_ip = '$client_ip'");
				$usr = $db->rsArray("SELECT count(*) as total FROM xz_emaillist WHERE ts > '".date('Y-m-d 00:00:00',TIMESTAMP)."' and uid = $uid");
				$per = $db->rsArray("SELECT count(*) as total FROM xz_emaillist WHERE ts > '".date('Y-m-d 00:00:00',TIMESTAMP)."' and client_ip = '$client_ip' and fid = $fcode");
				
				$all['total'] = intval($all['total']);
				$per['total'] = intval($per['total']);
				$usr['total'] = intval($usr['total']);
				
				if($all['total'] > 10 || $per['total'] > 0 || $usr['total'] > 8){
					$ret["res"] = "您已经超出了 [单份资料日发送<1次 日总发送资料<8份] 的邮件递送限额。";
					$ret["rcode"] = 1;
					return $ret;
				}
				
				$data = array(
						'uid' => $uid,
						'fid' => $fcode,
						'client_ip' => $client_ip,
						'client_adress' => get_adress()
				);
				$rs = $db->insert("xz_emaillist", $data);
				if($rs != false)
				{
					rlog(__CLASS__, 'INFO', "send email. uid: $uid, fid: $fcode");
					$ret["rcode"] = 0;
					$ret["res"] = "资料已经风尘仆仆的奔向".$email."啦，学长只能帮你到这儿了~";
					return $ret;
				}else{
					$ret["rcode"] = 1;
					$ret["res"] = "学长的邮差罢工了，我正在做他的思想工作。";
					return $ret;
				}
			}
			
			//先执行预注册
			$r_user = $db->insert("pd_users", $user);
			
			if($r_user == false)
			{
				$ret["rcode"] = 1;
				$ret["res"] = "资料发送失败啦，攻城师们正在查看是哪里出了问题~";
				return $ret;
			}
			
			//注册成功则查询uid
			$r_sel = $db->rsArray("select userid from pd_users where email = '".$email."'");
			if(!empty($r_sel["userid"]))
			{
				$uid = $r_sel["userid"];
			}else{
				$ret["rcode"] = 1;
				$ret["res"] = "资料发送失败啦，攻城师们正在查看是哪里出了问题~";
				return $ret;
			}
			
			//将uid和fid插入待发送表中
			$data = array(
				'uid' => $uid,
				'fid' => $fcode,
				'client_ip' => $client_ip,
				'client_adress' => get_adress()
			);
			$rs = $db->insert("xz_emaillist", $data);
			if($rs != false)
			{
				rlog(__CLASS__, 'INFO', "send email. uid: $uid, fid: $fcode");
				$ret["rcode"] = 2;
				$ret["uid"] = $uid;
				$ret["res"] = "资料已经通过".$email."邮寄给你啦，xzbbm 是我们的接头暗号，要牢记哦~";
				$ret["passwd"] = "xzbbm";
				return $ret;
			}else{
				$ret["rcode"] = 1;
				$ret["res"] = "资料发送失败啦，攻城师们正在查看是哪里出了问题~";
				return $ret;
			}
			
		}elseif($fcode == -1){  //下载文件判断用户
			
			//检查用户是否已注册,已注册就提示用户登录
			$r_sel = $db->rsArray("select userid from pd_users where email = '".$email."'");
			if($r_sel === false){
				$ret["res"] = "注册失败！";
				$ret["rcode"] = 1;
				return $ret;
			}
			if(!empty($r_sel["userid"])){
			    $ret["uid"] = $r_sel["userid"];
				$ret["rcode"] = 0;
				$ret["res"] = "发现您已经注册，请登录！";
				return $ret;
			}else{
				//先执行预注册
				$r_user = $db->insert("pd_users", $user);
					
				if(!is_numeric($r_user)){
					$ret["rcode"] = 1;
					$ret["res"] = "注册失败！";
					return $ret;
				}else{
					$ret["rcode"] = 2;
					$ret["res"] = "为你生成默认账号为".$email.", 密码：xzbbm。请根据提示修改密码。";
					return $ret;
				}
			}
		}
	}
}
