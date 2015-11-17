<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;
}

/**
 * @todo 超级外卖单微信服务号数据接口
 * @author bo.wang3
 * @version 2013-4-22 14:29
 */
Class SuperCarte{
	
	private $_db;
	private $_dc;
	private $_accesstoken;
	private $_postdata;
	
	public function __Construct(){
		
			$options = array(
					'token'=>'9cdNRQSZXhKiM43Y7e2b5T81WOfUgLg', //填写你设定的key
					'appid'=>'wx932129ae52fe6fc4', //填写高级调用功能的app id
					'appsecret'=>'58fbd4dda181eacc45f6ae033b9eb803', //填写高级调用功能的密钥
					'partnerid'=>'', //财付通商户身份标识
					'partnerkey'=>'', //财付通商户权限密钥Key
					'paysignkey'=>'' //商户签名密钥Key
			);
			
			$this->weObj = new Wechat($options);
			//$this->weObj->valid();
			
			Core::InitDb();  //初始化数据库
			Core::InitDataCache(); //初始化数据缓类
			$this->_db = Core::$db['online'];
			$this->_dc = Core::$dc;
			$this->_logpath = "/data/log/super.log";
			
			$memKey = md5('access_token');
			$this->_accesstoken = $this->_dc->getData($memKey,7200); //读取缓存数据;
			if(false === $this->_accesstoken || $_GET['i'] == 'c'){
				$param = array(
						'grant_type' => "client_credential",
						'appid' => $options['appid'],
						'secret' => $options['appsecret']
						);
				
				$url = 'https://api.weixin.qq.com/cgi-bin/token?'.http_build_query($param);
				$result = $this->Curl($url,'GET');
				
				$this->_accesstoken = json_decode($result,true);
				$this->_accesstoken = $this->_accesstoken['access_token'];
				$this->_dc->setData($memKey,$this->_accesstoken); //设置缓存数据
			}
			
			//输入日志
			file_put_contents($this->_logpath, date('Y-m-d H:i:s')." [input] ".json_encode($this->_postdata).json_encode($this->_postdata['Content'])."\n", FILE_APPEND);
	}
	
	/**
	 * @todo 利用Curl实现https通信
	 * @author bo.wang3
	 * @version 2014-9-9 14:29
	 */
	public function Curl($url,$func = 'POST',$postdata = ''){
	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if($func != 'GET'){
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
		}
	
		return curl_exec($ch);
	}
	
	
	/**
	 * @todo 从这里开始
	 * @author bo.wang3
	 * @version 2014-9-9 14:29
	 */
	public function Main(){

	     $type = $this->weObj->getRev()->getRevType();
	     $content = $this->weObj->getRev()->getRevContent();
	     
	     switch($type) {
	    		case Wechat::MSGTYPE_TEXT:
	    			
	    			$rpl = '好的，主人请稍等！';
	    			
	    			if(strstr($content,' ')){ //开启功能模式
	    				$cmd = explode(' ', $content);
	    				switch (strtoupper($cmd[0])){
	    					case 'S':
	    						$rs = $this->_db->rsArray("SELECT * FROM shops_info WHERE id = '{$cmd[1]}'");
	    						if(!empty($rs)){
	    							$rpl = "店名：{$rs['name']}\n地址：{$rs['adress']}\n经纬度：({$rs['x']},{$rs['y']})\n电话：{$rs['phone']}";
	    						}else{
	    							$rpl = "暂无该编号下店铺信息！";
	    						}
	    						break;
	    					case 'M':
	    						$v['name'] = $cmd[2];
	    						$v['phone'] = $cmd[3];
	    						if($this->_db->update('shops_info',$v,"id = '{$cmd[1]}'")){
	    							$rpl = "店铺信息修改成功，回复“S {$cmd[1]}”查看当前店铺信息！";
	    						}else{
	    							$rpl = "店铺信息修改失败，请检查命令是否正确！";
	    						}
	    						break;
	    					case 'D':
	    						break;
	    				}
	    			}
	    			
	    			$this->weObj->text($rpl)->reply();
	    			exit;
	    			break;
	    		case Wechat::MSGTYPE_LOCATION:
	    			$geo = $this->weObj->getRev()->getRevGeo();
    				$v = array(
    						'adress' => $geo['label'],
    						'x' => $geo['x'],
    						'y' => $geo['y'],
    						'carte_num' => 1
    				);
    				$id = $this->_db->insert('shops_info',$v);
    				if(is_numeric($id)){
    					$rpl = "你当前的地理位置是:$geo[label](东经$geo[x]，北纬$geo[y]，精度$geo[scale]米)，店铺数据（ID:{$id}）已成功录入！（回复“S#店铺编码”查看店铺详情）";
    				}else{
    					$rs = $this->_db->rsArray("select id from shopinfo where adress = '$v'");
    					$rpl = "当前店铺已经存在，店铺编号为{$rs['id']}。（回复“S 店铺编码”查看店铺详情）";
    				}
    				if(!is_numeric($id) && !is_numeric($rs['id'])){
    					$rpl = $this->_db->_errorMsg;
    				}
    				$this->weObj->text($rpl)->reply();
    				exit;
	    			break;
	    		case Wechat::MSGTYPE_IMAGE:
	    			break;
    			case Wechat::MSGTYPE_EVENT:
    				$this->weObj->text('欢迎您关注超级外卖单服务号，小单帮你一网打尽周边外卖信息~')->reply();
    				break;
	    		default:
	    			$this->weObj->text($type)->reply();
	     }
    }

	/**
	 * @todo 自定义按钮
	 * @author bo.wang3
	 * @version 2014-9-9 14:29
	 */
	public function MenuSet(){
	
		$btn = array(
			'button' => array(
					array(
						"name" => "我要订餐",
						"type" => "click",
						"key"  => "dingcan",
						/*
						"sub_button" => array(
								array(
									"name" => "吃的最好",
									"type" => "click",
									"key"  => "best"
										),
								array(
									"name" => "吃的最快",
									"type" => "click",
									"key"  => "fast"
								)
						),
						*/
					)
			)
		);
		
		var_dump($this->weObj->createMenu($btn));
		//var_dump($this->weObj->deleteMenu());
	}
	
	
	/**
	 * @author bo.wang3
	 * @version 2014-9-9 14:29
	 */
	public function DbTest(){
	
		$rs = $this->_db->rsArray("SELECT * FROM shops_info WHERE id = '{$cmd[1]}'");
	    $rpl = "店名：{$rs['name']}\n地址：{$rs['adress']}\n经纬度：({$rs['x']},{$rs['y']})\n电话：{$rs['phone']}";
	}
	
}