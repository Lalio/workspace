<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;
}

Class Login{

	static function Main($uname, $pwd)
	{
		Core::InitDb();
		$db = Core::$db['online'];
		$sql = "select * from pd_users where email = '" . $uname . "'";
		$rs = $db->rsArray($sql);
	
		if($rs === flase)
		{
			$ret['rcode'] = 3;
			$ret['result'] = "登陆失败。系统错误";
			return $ret;
		}

		if(empty($rs))
		{
			$ret['rcode'] = 1;
			$ret['result'] = "用户不存在！请重新输入。";
			return $ret;	
		}

		if($rs['password'] == $pwd)
		{
			$ret['rcode'] = 0;
			$ret['uid'] = $rs['userid'];
			$ret['email'] = $rs['email'];
			$ret['sex'] = $rs['sex'];
			$ret['credit'] = $rs['credit'];
			$ret['ucode'] = $rs['ucode'];
			$ret['ccode'] = $rs['ccode'];
			$ret['last_login_time'] = $rs['last_login_time'];
			$ret['last_login_ip'] = $rs['last_login_ip'];
			$ret['need_phonebook'] = empty($rs['need_phonebook'])==''?"true":"false";
			$ret['result'] = "登陆成功！";
		}else{
			$ret['rcode'] = 2;
			$ret['result'] = "密码错误！";
		}
		
		return $ret;
	}
	
	/**
	 * @todo 注册模块 password是客户端md5后的 sex male female
	 * @author bo.wang3
	 * @version 2013-5-27 14:29
	 */
	static public function Reg($email,$sex,$password,$uni,$col,$pay_account){
	
		if(empty($email) || empty($sex) || empty($password) || empty($uni) || empty($col)){
			//参数填写不完整
			$ret['rcode'] = 1;
			$ret['result'] = "注册参数不完整！";
			return $ret;
		}
		
		if (!preg_match("/\b[a-zA-Z0-9._%-]+@[a-zA-Z0-9._%-]+\.[a-z]{2,4}\b/",$email)){
			$ret["res"] = "邮箱格式不正确！";
			$ret["rcode"] = 1;
			return $ret;
		}
		
		Core::InitDb();
		$db = Core::$db['online'];
	
		$user = array(
				'email' => $email,
				'sex' => $sex,
				'gid' => 4,
				'is_locked' => 0,
				'last_login_time' => TIMESTAMP,
				'reg_time' => TIMESTAMP,
				'reg_ip' => $_SERVER['REMOTE_ADDR'],
				'last_login_ip' => $_SERVER['REMOTE_ADDR'],
				'credit' => 10,
				'exp' => 20,
				'ucode' => $uni,
				'ccode' => $col,
				'pay_account' => $pay_account,
				'password' => $password
		);
	
		$u = $db->rsArray("SELECT userid,ccode FROM pd_users WHERE email = '{$user['email']}' LIMIT 0,1");
	
		if($u['ccode'] > 0){
			//该用户已存在
			$ret['rcode'] = 1;
			$ret['result'] = "您的邮箱已经注册过学长帮帮忙，请直接登录！";
			return $ret;
		}elseif(empty($u['userid'])){
			$db->insert('pd_users',$user);
		}else{
			$db->update('pd_users',array('ucode' => $user['ucode'] ,'ccode' => $user['ccode'] ,'pay_account' => $user['pay_account'],'password' => $user['password']),"userid = {$u['userid']}");
		}
	
		//注册成功
		$ret['rcode'] = 1;
		$ret['result'] = "注册成功！";
		return $ret;
	}
	
	static function SetPhoneBook($uid, $data){
		Core::InitDb();
		$db = Core::$db['online'];
		
		$rs = $db->update('pd_users',array('phone_book' => $data),"userid = $uid");
	
		if(false !== $rs){
			$ret['rcode'] = 0;
			$ret['result'] = "保存成功";
		}else{
			$ret['rcode'] = 1;
			$ret['result'] = "保存失败";
		}
	
		return $ret;
	}
	
	static function GetProfile($uid){
		Core::InitDb();
		$db = Core::$db['online'];
		
		$rs = $db->rsArray('SELECT credit,profile FROM pd_users WHERE userid = '.$uid);
		$ret = array('credit' => $rs['credit'] , 'profile' => "￥".$rs['profile']);
	
		return $ret;
	}
}
