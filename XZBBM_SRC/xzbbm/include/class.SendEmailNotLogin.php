<?php
if(!defined('IN_SYS')) {
	header("HTTP/1.1 404 Not Found");
	die;
}

Class SendEmailNotLogin {

	static function Main($fcode, $email){

		Core::InitDb();
		$db = Core::$db['online'];
		
		//为了更合理一点，这个判断逻辑改到客户端了		
 		if (!preg_match("/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i",$email)){
			$ret["res"] = "请键入正确的邮箱地址，推荐使用QQ或网易邮箱。";
			$ret["rcode"] = 1;
			return $ret;
		} 
		
		$user = array(
				'password' => md5("xzbbm"),
				'email' => $email,
				'gid' => 4,
				'is_locked' => 0,
				'last_login_time' => TIMESTAMP,
				'last_login_ip' => $_SERVER['REMOTE_ADDR'],
				'xztoken' => $random_xztoken,
                'reg_time' => TIMESTAMP,
                'reg_ip' => $_SERVER ['REMOTE_ADDR'],
                'last_login_ip' => $_SERVER ['REMOTE_ADDR'],
                'user_icon' => 'http://cdn.xzbbm.cn/usericons/default' . rand ( 1, 7 ) . '.png',
                'user_name' => substr_replace ( $email, '****', 3, 3 ) ? substr_replace ( $email, '****', 3, 3 ) : substr_replace ( $phone, '****', 3, 3 ) 
		);
		
		if($fcode > 0){ //按邮件地址发送邮件

			//检查用户是否已注册,已注册就直接发送邮件
			$r_sel = $db->rsArray("select userid from pd_users where email = '".$email."'");
			if($r_sel === false)
			{
				$ret["res"] = "资料发送失败啦，攻城师们正在查看是哪里出了问题~";
				$ret["rcode"] = 1;
				return $ret;
			}
			
			if(!empty($r_sel["userid"]))
			{
				$uid = $r_sel["userid"];
				
				//服务器端判断总下载次数，防止刷文件
				$client_ip = get_ip();
				
				$all = $db->rsArray("SELECT count(*) as total FROM xz_emaillist WHERE ts > '".date('Y-m-d 00:00:00',TIMESTAMP)."' and client_ip = '$client_ip'");
				$usr = $db->rsArray("SELECT count(*) as total FROM xz_emaillist WHERE ts > '".date('Y-m-d 00:00:00',TIMESTAMP)."' and uid = $uid");
				$per = $db->rsArray("SELECT count(*) as total FROM xz_emaillist WHERE ts > '".date('Y-m-d 00:00:00',TIMESTAMP)."' and client_ip = '$client_ip' and fid = $fcode");
				
				$at = intval($all['total']);
				$pt = intval($per['total']);
				$ut = intval($usr['total']);
				
				if($at > 10 || $pt > 0 || $ut > 4){
					$ret["res"] = "当前资料已经超出了单份资料日发送<1次，日总发送资料<4份的邮件递送限额。";
					$ret["rcode"] = 3;
					return $ret;
				}
				
				$data = array(
						'uid' => $uid,
						'fid' => $fcode,
						'client_ip' => $client_ip,
						'client_adress' => get_adress()
				);
				
				$rs = $db->insert("xz_emaillist", $data);
				
				$ut = $db->rsArray("SELECT file_downs FROM pd_files WHERE file_id = $fcode limit 0,1");
				
				if($rs != false)
				{
					$ret["rcode"] = 0;
					if($ut['file_downs'] > 0){
						$ret["res"] = "共有".intformat($ut['file_downs'])."位同学免费获取了此资料，学长只能帮你到这儿了。";
					}else{
						$ret["res"] = "学长只能帮你到这儿了。";
					}
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
				$ret["rcode"] = 2;
				$ret["uid"] = $uid;
				$ret["res"] = "资料正在风尘仆仆的奔向你的邮箱，学长只能帮你到这儿了~";
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
					$ret["res"] = "为你生成默认账号为".$email.", 密码：xzbbm。";
					return $ret;
				}
			}
		}
	}
}
