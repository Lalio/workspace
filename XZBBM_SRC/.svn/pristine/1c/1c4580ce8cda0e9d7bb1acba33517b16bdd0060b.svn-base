<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;
}

Class Social {
	
	private $_db;
	
	public $imem;
	public $msg;
	public $ucode;
	public $nettype;
	public $loc;
	public $key;
	
	function __construct($imei,$msg,$ucode,$nettype,$loc,$key){
		
		$this->imei = $imei;
		$this->msg = $msg;
		$this->ucode = $ucode;
		$this->nettype = $nettype;
		$this->loc = $loc;
		$this->key = $key;
		
		Core::InitDb();
		$this->_db = Core::$db['online'];
		Core::InitDataCache();
		$this->_dc = Core::$dc;
	}
	
	public function WenDa(){
		
		$keyword = $this->msg;
		
		/* if(strstr($this->msg,'广工超级信息检索大赛')){

			$validtime = array(
					array(
							's' => '2014-01-10 22:30:00',
							'e' => '2014-01-10 22:40:00',
							'total' => 100000,
					),
			);
			
			$in_time = false; //是否在有效期内
			foreach ($validtime as $time){ //进行判断
				if(TIMESTAMP >= strtotime($time[s]) && TIMESTAMP <= strtotime($time[e])){
					$in_time = $time;
					break;
				}
			}
			if(false !== $in_time){
                if($this->_dc->getData('wd_cj_tag'.$this->key.$in_time['s']) == 'ok'){
					$response = '你的手机已经参加过了这一波抽奖，欢迎你继续关注学长帮帮忙，学长总是为你想的更多！';
				}else{
					$this->_dc->setData('wd_cj_tag'.$this->key.$in_time['s'],'ok');
					if(1 == mt_rand(1,$in_time['total'])){
						$this->GuangBo('#广工超级信息检索大赛# 又一位同学获得学长帮帮忙限量版U盘一个，Ta的识别码是 '.$this->key.' ,问答输入 我的识别码 查看自己的识别码~');
						$response = '哎呦，恭喜你获得了学长帮帮忙（xzbbm.cn）限量纪念版U盘一个，你的识别码是 '.$this->key.'，点击我把喜悦分享到朋友圈！';
					}else{
						$response = '好遗憾啊，没抽中奖，学长心碎的跟捧出来跟饺子馅儿似的！';
					}
				}
			}else{
				if(TIMESTAMP < strtotime('2014-01-10 21:30:00')){
					$response = '距离第一波抽奖开始还剩'.floor((strtotime('2014-1-10 21:30:00')-TIMESTAMP)/60).'分钟 +'.((strtotime('2014-1-10 21:30:00')-TIMESTAMP)%60).'秒钟，先吃根雪糕等等吧。';
				}elseif(TIMESTAMP > strtotime('2014-01-10 22:40:00')){
					$response = '本次抽奖活动已经结束了，感谢你关注学长帮帮忙~';
				}else{
					$response = '抽奖活动稍后继续，先吃根雪糕等等吧，摩拳擦掌迎来最后一波！';
				}
			}
			
		}else */
		if(strstr($this->msg,'我的识别码')){
			$response = '你手机的识别码是：'.$this->key;
		}elseif(strstr($this->msg,'cet')){
			
			$input = explode('#',$this->msg);

			$url = "http://www.chsi.com.cn/cet/query?zkzh=".$input[1]."&xm=".urlencode($input[2]);
			//$url = "http://www.chsi.com.cn/cet/query?zkzh=440381131206118&xm=%E6%9B%BE%E6%99%93%E5%8D%97";
			
			$cookie = "Filtering=0.0; popupCookie=true; selected_nc=ch; __utma=119922954.1755371935.1389180919.1389180919.".TIMESTAMP.".1; __utmb=119922954.22.7.".TIMESTAMP.".0000; __utmc=119922954; __utmz=119922954.".TIMESTAMP.".1.1.utmcsr=google|utmccn=(organic)|utmcmd=organic|utmctr=(not%20provided)";
			// 这个CURL就是模拟发起请求咯，直接返回的就是JSON
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_REFERER, "http://www.chsi.com.cn/cet/");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_COOKIE, $cookie);
			$content = curl_exec($ch);
			curl_close($ch);
			preg_match_all('(.*)',$content,$match);
			
			$info = array($match[0][214],$match[0][222],$match[0][230],$match[0][238],$match[0][246],$match[0][254]);
			foreach($info as $k=>$v){
				$info[$k] = str_replace('<td>','',$info[$k]);
				$info[$k] = str_replace('</td>','',$info[$k]);
				$info[$k] = str_replace('		','',$info[$k]);
				$info[$k] = str_replace('		','',$info[$k]);
				$info[$k] = str_replace('<strong><span style="color: #F00;">','',$info[$k]);
				$info[$k] = str_replace('<span>','',$info[$k]);
				$info[$k] = str_replace('</span>','',$info[$k]);
				$info[$k] = str_replace('<span class="color01">','',$info[$k]);
				$info[$k] = str_replace('&nbsp;','',$info[$k]);
				$info[$k] = str_replace('</strong>','',$info[$k]);
				$info[$k] = str_replace('听力：','_',$info[$k]);
				$info[$k] = str_replace('阅读：','_',$info[$k]);
				$info[$k] = str_replace('综合：','_',$info[$k]);
				$info[$k] = str_replace('写作与翻译：','_',$info[$k]);
			}
			
			$info[5] = explode('_',$info[5]);

			$response = "学校：$info[1]\n考试类别：$info[2]\n考试时间：$info[4]\n\n总分：{$info[5][0]}\n听力：{$info[5][1]}\n阅读：{$info[5][2]}\n综合：{$info[5][3]}\n写作与翻译：{$info[5][4]}\n";	
					
		}else{
			
			//这个curl是因为官方每次请求都有唯一的COOKIE，我们必须先把COOKIE拿出来，不然会一直返回“HI”
			/* 		$ch = curl_init();
			 * $url = "http://www.simsimi.com/talk.htm?lc=ch";
			 curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$content = curl_exec($ch);
			curl_close($ch);
			list($header, $body) = explode("\r\n\r\n", $content);
			preg_match("/set\-cookie:([^\r\n]*)/i", $header, $matches);
			$cookie = $matches[1]; */
			
			$cookie = "sid=s%3AFd2fum5Bp18JiHrcaKIWBKMm.4wsst50wkhSUqMTbB3Ie43oqII3dQOmp90CwATapfhg; Filtering=0.0; Filtering=0.0; simsimi_uid=56731668; simsimi_uid=56731668; isFirst=1; isFirst=1; selected_nc=ch; selected_nc=ch; __utma=119922954.1150491389.".TIMESTAMP.".".TIMESTAMP.".".TIMESTAMP.".1; __utmb=119922954.5.9.".TIMESTAMP."; __utmc=119922954; __utmz=119922954.".TIMESTAMP.".1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none)";
			$urll = 'http://www.simsimi.com/func/reqN?lc=ch&ft=0.0&req='.urlencode($keyword);
				
			// 这个CURL就是模拟发起请求咯，直接返回的就是JSON
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $urll);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_REFERER, "http://www.simsimi.com/talk.htm?lc=ch");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_COOKIE, $cookie);
			$content = json_decode(curl_exec($ch),true);
			$response = $content['sentence_resp'];
			curl_close($ch);
			
		}
		
		$rs = array(
				'showtime' => 300000,
				'type' => 'text',
				'content' => $response,
				'link' => '',
				'show_delay' => 0
		);
		
		return $rs;
	}

	public function GuangBo($text){
		
		$data = array(
			'showtime'   => 300000,
			'type'       => 'text',
			'content'    => empty($text)?$this->msg:$text,
			'link'       => '',
			'show_delay' => 0,
			'ucode'      => $this->ucode,
			'starttime'  => 0,
			'endtime'    => 0
		);
		
		if($this->_db->insert('xz_yao',$data)){
			return array('rs' => 0);
		}else{
			return array('rs' => 1);
		}
	}
}