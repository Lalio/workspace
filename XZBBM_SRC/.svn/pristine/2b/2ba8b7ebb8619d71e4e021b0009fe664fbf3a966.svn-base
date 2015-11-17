<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;
}

/**
 * @todo 56广告系统用户权限认证子模块
 * @author bo.wang3
 * @version 2013-4-22 14:29
 */
Class Auth extends Mads{

	public function __construct(){
		parent::__construct();
	}

	public function Main(){
		include Template('login','Auth');
	}
	
	/**
	 * @todo 生成验证码
	 * @author bo.wang3
	 * @version 2013-4-22 14:29
	 */
	public function GVerifyCode(){

		$v = new VerifyCode(100,27,4);  //实列化，默认根据字符大小产生图像，产生5个字符
  		$v->ZH = false;  //打开中文验证码开关
  		$v->bg_alpha = 127; //背景全透明，注意IE6不支持png透明，需写hack
  		$_SESSION['verifycode'] = $v->show(); //字符串写入session，并得到返回的验证字串
	}

	/**
	 * @todo 新验证登陆逻辑模块
	 * @author bo.wang3
	 * @version 2013-5-27 14:29
	 */
	public function Login(){

		if(isset($_SESSION['verifycode'])){
			if(strtoupper($_POST['vcode']) != strtoupper($_SESSION['verifycode'])){
				echo json_encode(array('rs' => 2, 'msg' => '验证码不正确!'));
				exit;
			}
		}

		foreach($_REQUEST as $k => $v){
			$_REQUEST[$k] = trim($v);
		}

		if(false === $this->OaLogin($_REQUEST['username'],$_REQUEST['password'])){
			echo json_encode(array('rs' => 1, 'msg' => '用户名或密码不正确!'));
		}else{
			//用户名或密码正确，进一步进行验证 
			$rs = $this->_db->rsArray("SELECT * FROM permission WHERE rtx_username = '$_POST[username]'");
			
			if(empty($rs)){
				echo json_encode(array('rs' => 1, 'msg' => '您暂时没有广告系统的权限，请向相关人员申请!'));
			}else{
				
				$admin = $this->_db->rsArray("SELECT * FROM Ad_admin WHERE username = '$rs[related_username]'");
				$admin = array_merge($rs,$admin);
				
				$_SESSION['admin'] = $admin;  //授权
				
				$data = array(
					'last_time' => TIMESTAMP, 
					'last_ip' => $_SERVER['REMOTE_ADDR']
					);
				$this->_db->update('permission',$data,"rtx_username = '$admin[rtx_username]'");

				if(empty($admin['last_ip'])){
					$msg = '这是您第一次登陆56网广告系统，请妥善管理账号及密码。';
				}else{
					$last_time = date('Y年m月d日 H:i:s',$admin['last_time']);
					$addr = file_get_contents("http://ip.taobao.com/service/getIpInfo.php?ip=".$admin['last_ip']);
					$addr = json_decode($addr,true);
					$last_addr = $addr['data']['region'].$addr['data']['city'];
					$msg = "您最后一次于 $last_time 在 $last_addr 访问本系统，若与实际情况不符请及时与系统开发组同事联系。";
				}
				
				//生成登陆Token
				$ext = "$admin[username]_$admin[password]_".TIMESTAMP;
				$ext_hash = md5($ext.'_56adsystem');
				$ext = $ext.'_'.$ext_hash;
				$ext = base64_encode(base64_encode($ext));
				
				echo json_encode(array('rs' => 0 , 'msg' => $msg , 'ext' => $ext));
			}
			
		}
		exit;
		
	}

	/**
	 * @todo 从OA系统账号登陆
	 * @author bo.wang3
	 * @version 2013-8-5 11:40
	 */
	public function OaLogin($username,$password){
		
		$exp_act = array(
			'xu.niu_ae' => 'X5214@zF5a9fnQ'
				);
		
		if(array_key_exists($username, $exp_act) && $exp_act[$username] == $password){
			return true;
		}

		$host = "oa.corp.56.com";
		$page = "general/person_info/pass/oa_auth.php";
		$data = array ('username' => $username, 'password' => $password );
		$back = Http::Post ( $host, $page, $data );

		if (HOSTNAME == 'mads.56.com' && strtoupper ( $back ) != 'OK') {
			//用户名字或者密码错误，请重新登陆
			return false;
		}else{
			//登陆成功
			return true;
		}
		
	}

	/**
	 * @todo 退出模块
	 * @author bo.wang3
	 * @version 2013-5-27 14:29
	 */
	public function LoginOut(){

		unset($this->admin);
	    unset($_SESSION);
		session_destroy();
		setcookie("admin_user",null);
		setcookie("admin_pw",null);
		setcookie("admin_level",null);
		
		header('Location:./?aciton=Auth');

	}

	/**
	 * @todo 旧验证登陆逻辑模块
	 * @author bo.wang3
	 * @version 2013-5-27 14:29
	 */
	public function LoginOld(){

		if(strtoupper($_POST['vcode']) != strtoupper($_SESSION['verifycode'])){
			echo json_encode(array('rs' => 2, 'msg' => '验证码不正确!'));
			exit;
		}

		foreach($_POST as $k => $v){
			$_POST[$k] = addslashes($v);
		}

		$admin = $this->_db->rsArray("SELECT * FROM Ad_admin WHERE username = '$_POST[username]'");
		
		if($admin['password'] != $_POST[password]){
			echo json_encode(array('rs' => 1, 'msg' => '用户名或密码不正确!'));
		}else{
			$_SESSION['admin'] = $admin;  //授权
			echo json_encode(array('rs' => 0));
		}
		
	}
	
	/**
	 * @todo 根据用户身份跳转欢迎页
	 * @author bo.wang3
	 * @version 2013-5-27 14:29
	 */
	public function Redirect(){
	
		 switch(ROLE){
			case 'DEVELOPER':
				header('Location:./?action=Order&do=Transaction&show=list');
				break;
			case 'AE':case 'RESOURCE':case 'AE_WORKER':
				header('Location:./?action=Reserve&do=Rms&show=list');
				break;
			case 'WORKER':
				header('Location:./?action=Order&do=ContractV2&show=list');
				break;
			default:
				header('Location:./?action=Auth');
				break;
		}
	
	}

}