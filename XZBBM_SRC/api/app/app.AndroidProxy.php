<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;
}

Class AndroidProxy {

	public $ret_msg; //最后返回的json串
	public $cache_key; //缓存键值
	public $json_msg; //客户端json命令
	public $msg; //客户端json命令
	public $msg_cmd; //客户端json控制命令
	private $_db; //数据库操作句柄

	public $now;
	public $log_path;

	public function __construct(){
		
		Core::InitDataCache(); //初始化数据缓类
		Core::InitDb();
		$this->_db = Core::$db['online'];
		define('XXTEA_KEY','2CXl6KwRSIvq8hCwoea9');
		
		if($_GET['debug'] == 'on'){
			$this->json_msg = $_REQUEST["json_msg"];
			$this->msg = json_decode($this->json_msg, true);
		}else if($_GET['upload'] == 'on'){
			$this->json_msg = $_REQUEST["json_msg"];
			$this->msg = json_decode($this->json_msg, true);
		}else{
			$this->json_msg = xxtea_decrypt(gzuncompress(file_get_contents("php://input")),XXTEA_KEY);
			$this->msg = json_decode($this->json_msg, true);
		}

		$this->msg_cmd = $this->msg["header"]["cmd"];
		$this->version = $this->msg["header"]["version"];

		$this->now = date("Y-m-d H:i:s");
		$this->log_path = "/data/log/access.log";
		/* 数据接口服务验证模块 */
			/*
			$s_token = md5($this->msg["header"]["ts"]."XZBBM_SERVER_PROTOCOL"); //XZBBM_SERVER_PROTOCOL为通信服务秘钥
			if($s_token != $this->msg["header"]["token"]){
				exit('Token Error!');
			}
			*/
		/* 数据接口服务验证模块 */
		
	}

    public function Main(){
    	
		$time_start = microtime(true);
		rlog(__CLASS__, 'DEBUG', "[input]".$this->json_msg);
		file_put_contents($this->log_path, "$this->now	[input]$this->json_msg \n", FILE_APPEND);

		if(!isset($this->msg_cmd)){
			$ret_cmd = array("cmd"=>1000);
			$ret_msg = array("header"=>$ret_cmd, "content" => "Input Error!");
			echo json_encode($ret_msg);
			exit;
		}

		if(in_array($this->msg_cmd, array(1,2,4,12,13))){ //仅对部分逻辑接口的数据进行缓存
			$this->cache_key = md5(json_encode($this->msg));	 //生成缓存KEY
			$this->ret_msg = Core::$dc->getData($this->cache_key,43200); //读取缓存数据
			
		}
		
		$time_end = microtime(true);
		$time_cost = $time_end - $time_start;

		if(!empty($this->ret_msg) && $_GET['i'] != 'c'){ //命中缓存
			rlog(__CLASS__, 'DEBUG', "[output][cache]"."[time_cost:$time_cost]".$this->ret_msg);			
			file_put_contents($this->log_path, "$this->now	[output][cache][time_cost:$time_cost]$this->ret_msg \n", FILE_APPEND);
		}else{
			switch ($this->msg_cmd){
			
			case 0: //获取APP端配置
				$ret = AppConfig::Main($this->msg["content"]);
				break;
				
			case 1: //获取学校文件列表
				$school_code = $this->msg["content"]["schoolcode"];
				$bid = $this->msg["content"]["batch_id"];
				$type = $this->msg["content"]["type"];
				if(empty($bid))
					$bid = 1;
				$ret = FilePush::Main($school_code, $bid ,$type);
				break;
			
			case 2: //获取文件详细
				$ver = $this->msg["header"]["version"];
				$file_code = $this->msg["content"]["filecode"];
				switch ($ver){
					case 1:
						$ret = GetFileInfo::Main($file_code);
						break;
					default:
						$ret = GetFileInfo2::Main($file_code);
						break;
				}
				break;
			
			case 3: //未登陆前发送邮件-直接下载 filecode = -1 表示直接下载，只执行注册逻辑
				$fcode = $this->msg["content"]["filecode"];
				$email = $this->msg["content"]["email"];
				$ret = SendEmailAndDownLoad::Main($fcode, $email);
				break;
				
			case 4: //获取学校编码
				$prov = $this->msg["content"]["province"];
				$ret = GetSchoolCode::Main($prov);
				break;
				
			case 5: //搜索文件
				$fname = $this->msg["content"]["filename"];
				$ucode = $this->msg["content"]["schoolcode"];
			    $stype = $this->msg["content"]["sort_type"];
				$bid = $this->msg["content"]["batch_id"];
				$uid = $this->msg["content"]["uid"];
				$ret = OpenSearchFile::Main($fname, $stype, $bid, $ucode ,$uid);
//                $ret = SearchFile::Main($fname, $stype, $bid, $ucode ,$uid);
				break;
			
			case 6: //通过UID发邮件
				$fcode = $this->msg["content"]["filecode"];
				$uid = $this->msg["content"]["uid"];
				$ret = SendEmailByUid::Main($fcode, $uid);
				break;

			case 7: //注册
				$email = $this->msg["content"]["email"];
				$sex = $this->msg["content"]["sex"];
				$password = $this->msg["content"]["password"];
				$uni = $this->msg["content"]["uni"];
				$col = $this->msg["content"]["col"];
				$pay_account = $this->msg["content"]["pay_account"];
				$ret = Login::Reg($email,$sex,$password,$uni,$col,$pay_account);
				break;

			case 8: //登陆
				$uname = $this->msg["content"]["uname"];
				$pwd = $this->msg["content"]["passwd"];
				$ret = Login::Main($uname, strtolower($pwd));
				break;

			case 9: //GPS定位学校接口 BY bo.wang
				$loc = $this->msg["content"]["loc"]; //经纬度
				$ret = Location::Main($loc);
				break;

			case 10: //GPS定位调试接口 用于定位不准确的时候校正使用 BY bo.wang 
				$loc = $this->msg["content"]["loc"]; //经纬度
				$ret = Location::DeBug($loc);
				break;

			case 11: //修改密码
				$username = $this->msg["content"]["username"];
				$old_pwd = $this->msg["content"]["old_pwd"];
				$new_pwd = $this->msg["content"]["new_pwd"];
				$ret = ModifyPasswd::Main($username,strtolower($old_pwd),strtolower($new_pwd));
				break;

			case 12: //标签云
				$ucode = $this->msg["content"]["schoolcode"];
				$ret = TagCloud::Main($ucode);
				break;

			case 13: //推广鸣谢
				$ret = FriendLink::Main();
				break;
				
			case 14: //错误日志
			    $log = $this->msg["content"]["errorlog"];
			    $ret = ErrorLog::Main($log);
			    break;
			
			case 15: //添加顶踩评论;type=0 顶一下;type=1 踩一下
			   	$fid = $this->msg["content"]["fid"];
			   	$uid = $this->msg["content"]["uid"];
			   	$type = $this->msg["content"]["type"];
				$ret = FileAction::AddComment($uid, $fid, $type);
				break;
			
			case 16: //获取私人云文件 $catg_id 1私人云
			   	$uid = $this->msg["content"]["uid"];
			   	$catg_id = $this->msg["content"]["catg_id"];
				$ret = FileAction::GetMyFavorite($uid,$catg_id);
				break;
			
			case 17://收藏、取消收藏文件 type 1收藏 0取消
			   	$fid = $this->msg["content"]["fid"];
			   	$uid = $this->msg["content"]["uid"];
			   	$type = $this->msg["content"]["type"];
				$ret = FileAction::Favorite($uid, $fid ,$type);
				break;

			case 18: //获取某一文件顶、踩、是否被该用户收藏实时数据
				$fid = $this->msg["content"]["fid"];
			   	$uid = $this->msg["content"]["uid"];
				$ret = FileAction::GetDingCaiFav($fid,$uid);
				break;
				
			case 19: //摇一摇
				$ucode = $this->msg["content"]["schoolcode"];
				$nettype = $this->msg["content"]["nettype"];
				$ret = Yao::Main($ucode,$nettype);
				break;
				
			case 20: //问答
				$par = $this->msg["content"];
				$obj = new Social($par['imei'],$par['msg'],$par['ucode'],$par['nettype'],$par['loc'],$par['key']);
				$ret = $obj->WenDa();
				break;
				
			case 21: //广播
				$par = $this->msg["content"];
				$obj = new Social($par['imei'],$par['msg'],$par['schoolcode'],$par['nettype'],$par['loc'],$par['key']);
				$ret = $obj->GuangBo();
				break;
				
			case 22: //痛扁学长
				$ucode = $this->msg["content"]["schoolcode"];
				$click_times = $this->msg["content"]["click_times"]; //游戏结果
				$ret = TongBianGame::Main($ucode,$click_times);
				break;
				
			case 23: //获取用户通讯录信息
				$uid = $this->msg["content"]["$uid"];
				$data = $this->msg["content"]["$data"];
				$ret = Login::SetPhoneBook($uid,$data);
				break;
				
			case 24:
				$schoolcode = $this->msg["content"]["schoolcode"];
				$uid = $this->msg["content"]["uid"];
				$filedesc = $this->msg["content"]["filedesc"];
				$filecount = $this->msg["content"]["filecount"];
				$file_tag = $this->msg["content"]["file_tag"];
				$islastfile = $this->msg["content"]["islastfile"];
				$file = $_FILES['Filedata'];
				$ret = UploadFile::Main($schoolcode, $uid, $filedesc, $filecount, $file_tag, $islastfile, $file);
				break;
				
			case 25: //实时获取用户积分及收益
				$uid = $this->msg["content"]["uid"];
				$ret = Login::GetProfile($uid);
				break;
				
			case 30: //获取随手拍标签信息
				$ucode = $this->msg["content"]["univer_id"];
				$ret = TagCloud::GetTags($ucode);
				break;

			case 31: //获取子分类下资料信息
				$ucode = $this->msg["content"]["univer_id"];
				$ccode = $this->msg["content"]["college_id"];
				$cate_name = $this->msg["content"]["categroy_name"]; //子分类名
				$key_word = $this->msg["content"]["key_word"]; //关键词名
				$num = $this->msg["content"]["num"]; //返回条数
				$page = $this->msg["content"]["page"]; //分页
				$ret = GetSubTypeFiles::Main($ucode,$ccode,$cate_name,$key_word,$num,$page);
				break;

			case 32: //获取子分类
				$ucode = $this->msg["content"]["univer_id"];
				$ret = GetSubTypes::Main($ucode);
				break;
				
			case 33: //获取私人云内标签
				$userid = $this->msg["content"]["uid"];
				$ret = GetPersonCloud::Tags($userid);
				break;
				
			case 34: //获取私人云内具体文件资料
				$userid = $this->msg["content"]["uid"];
				$catg_id = $this->msg["content"]["catg_id"];
				$page = $this->msg["content"]["page"]; //分页
				$ret = GetPersonCloud::GetFiles($userid,$catg_id,$page);
				break;

			default:
				$ret = "Not find $this->msg_cmd !";
				break;
			}

			$ret_cmd = array("cmd"=>1000);
			$ret_msg = array("header"=>$ret_cmd, "content" => $ret);

			arrayRecursive($ret_msg, 'urlencode', true);
			$this->ret_msg = urldecode(json_encode($ret_msg));

			Core::$dc->setData($this->cache_key,$this->ret_msg); //设置缓存数据

		}
		
		//读取完数据库或者缓存后的后续操作
		switch ($this->msg_cmd){
		case 1:
			$this->ret_msg = json_decode($this->ret_msg,true);
			shuffle($this->ret_msg['content']);
			arrayRecursive($this->ret_msg, 'urlencode', true);
			$this->ret_msg = urldecode(json_encode($this->ret_msg));
		case 2:
			$file_code = $this->msg["content"]["filecode"];
			$this->_db->conn("UPDATE pd_files SET file_views = (file_views + 1) WHERE file_id = $file_code LIMIT 1");
			break;
		case 5:
			if(!empty($ret)){
				$rs = $this->_db->rsArray("SELECT id FROM xz_tagscloud WHERE word = '$fname' and ucode = $ucode limit 1");
				if($rs['id']){
					//$s['uids'] == $uid?$uid.',':'';
					$this->_db->conn("update xz_tagscloud set total = total + 1 where id = $rs[id]");
				}else{
					$s = array('word' => $fname,'total' => 1,'ucode' => $ucode);
					//$s['uids'] == $uid?$uid.',':'';
					$this->_db->insert("xz_tagscloud",$s);
				}
			}
			break;
		}

		$time_end = microtime(true);
		$time_cost = $time_end - $time_start;

		rlog(__CLASS__, 'DEBUG', "[output]"."[time_cost:$time_cost]".$this->ret_msg);
		file_put_contents($this->log_path, "$this->now	[output][time_cost:$time_cost]$this->ret_msg \n", FILE_APPEND);

		if($_GET['debug'] == 'on'){
			echo $this->ret_msg;
		}else{
			if($this->version < 3){
				echo gzcompress(xxtea_encrypt($this->ret_msg,XXTEA_KEY));
			}else{
				echo xxtea_encrypt(gzcompress($this->ret_msg),XXTEA_KEY);
			}
		}
	}

	public function InterFaces(){
		$func = trim($_REQUEST['func']);
		switch($func){
		case 'GetLastedVersion':
			echo AppConfig::LastedVersion();
			break;
		}     
	}


}
