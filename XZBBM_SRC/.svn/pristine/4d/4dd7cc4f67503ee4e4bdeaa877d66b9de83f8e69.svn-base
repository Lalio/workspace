<?php
/**
 * @name:	class.Mem.php
 * @todo: 	memcache 操作类库
 * @author:	zhys9(jingki) @ 2008-6-1
 	<code>
 		$configs = array();
 		$configs['server'][0] = array(
 									'host'	=> '192.168.1.38',
 									'port'	=> 22222,
 									'weight'=> 11
 								);
 		$mem = & new Mem($configs['server']);
 		$mem->Put('test', 'jingki', 10);
 		$rs = $mem->Get('test');
 		echo $rs;
 	</code>
	@modified   Melon @ 2010-04-26
 */

class Mem {
    
 	
	private $memCache;
	
	private $compression = TRUE;
	private $compressMinSize = 2000;
	private $compressLevel	 = 0.2;
	
	const persistent = true;
	const weight	 = 10;
	const timeout	 = 3600000;
	const retryInterval = 15;
	
	public function Mem(array $configServerArray) {
		if(is_array($configServerArray['servers'])) {
			foreach($configServerArray['servers'] as $val) {
				$this->AddServer($val);
			}
		}
	}
	
	protected function _Connect() {
		if (!$this->memCache) {
			$this->memCache = new Memcached;
		}else{
			return ;
		}
	}
	
	/**
	 * @name AddServer
	 * @author zhys9
	 * @todo 添加一个server
	 *
	 */
	public function AddServer($arr) {
		$this->_Connect();
		
		$this->memCache->addServer($arr['host'], $arr['port']);
		$this->memCache->setSaslAuthData($arr['account'], $arr['password']);
//		if($this->compression) {
//			$this->memCache->setCompressThreshold($this->compressMinSize, $this->compressLevel);
//		}						
	}
	
	/**
	 * @name Get
	 * @author zhys9
	 * @todo 取出某个值
	 * @param string $key
	 * @return mixed
	 *
	 */
 	public function Get($key) {
		$this->_Connect();
		$value = $this->memCache->get($key);
		if ($value === FALSE){
			return FALSE;
		}else{
			return json_decode($value, true);
		}
	}

	/**
	 * @name Put
	 * @author zhys9
	 * @todo 存储一条数据
	 * @param string $key
	 * @param mixed $val
	 * @return bool
	 *
	 */
	public function Put($key, $val , $time) {
		$this->_Connect();
		$time = $time?$time:mt_rand(3000,3600); //缓存时间不要同步
		return $this->memCache->set($key, json_encode($val), intval($time));//modified by bo.wang3
	}
	
	/**
	 * @name Del
	 * @author zhys9
	 * @todo 删除某个值
	 * @param string $key
	 * @return bool
	 *
	 */
	public function Del($key) {
		$this->_Connect();
		return $this->memCache->delete($key);
	}

	/**
	 * @name Status
	 * @author zhys9
	 * @todo 查看状态
	 * @return array
	 *
	 */
	public function Status() {
		$this->_Connect();
		return $this->memCache->getExtendedStats();
	}
	
	/**
	 * @name Flush
	 * @author zhys9
	 * @todo 清除所有memcache数据，慎用！
	 *
	 */
	public function Flush() {
		return $this->flush($this->memCache);
	}

	public function __destruct() {
		if ($this->memCache) {
//			$this->memCache->close();
		}
	}
}
?>