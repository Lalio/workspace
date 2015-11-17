<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;
}

Class ModifyPasswd {

	static function Main($username, $old_pwd , $new_pwd)
	{
		Core::InitDb();
		$db = Core::$db['online'];
		
		
		$user = $db->rsArray("SELECT userid FROM pd_users WHERE username = '$username' and password = '$old_pwd'");
		
                if(empty($user)){
		    $ret['rcode'] = 1;
		}else{
		    $data = array(
		        'password' => $new_pwd,
		    );
		    if(false !== $db->update('pd_users',$data,"userid = $user[userid]")){
		        $ret['rcode'] = 0;
		    }
		}
		return $ret;
	}
}
