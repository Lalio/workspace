<?php 
/********************************************************************************* 
 * InitPHP 2.0 国产PHP开发框架  Dao-Nosql-Redis 
 *------------------------------------------------------------------------------- 
 * 版权所有: CopyRight By initphp.com 
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己 
 * miracle优化了以下代码：
 * 1、连接配置中添加对密码的支持
 * 2、增加析构函数，自动释放链接
 * 3、增加构造函数，初始化时即完成对象的配置
 *------------------------------------------------------------------------------- 
 * $Author:zhuli 
 * $Dtime:2011-10-09  
***********************************************************************************/  
class RedisDb {  
      
    private $redis; //redis对象  
      
    /** 
     * 初始化Redis 
     * $config = array( 
     *  'server' => '127.0.0.1' 服务器 
     *  'port'   => '6379' 端口号 
     * ) 
     * @param array $config 
     */  
    public function __construct($config = array()) {  
    	
    		if ($config['server'] == '')  $config['server'] = '127.0.0.1';
    		if ($config['port'] == '')   $config['port'] = '6379';
    		if (!isset($config['pwd']))  $config['pwd'] = '';
    		$this->redis = new Redis();
    		$this->redis->connect($config['server'], $config['port']);
    		$this->redis->auth($config['pwd']);
    }  
    
    /**
     * 释放Redis
     */
    public function __destruct() {

    	$this->redis->close();
    }
      
    /** 
     * 设置值 
     * @param string $key KEY名称 
     * @param string|array $value 获取得到的数据 
     * @param int $timeOut 时间 
     */  
    public function set($key, $value, $timeOut = 0) {  
        $value = json_encode($value);  
        $retRes = $this->redis->set($key, $value);  
        if ($timeOut > 0) $this->redis->setTimeout($key, $timeOut);  
        return $retRes;  
    }  
  
    /** 
     * 通过KEY获取数据 
     * @param string $key KEY名称 
     */  
    public function get($key) {  
        $result = $this->redis->get($key);  
        return json_decode($result, TRUE);  
    }  
      
    /** 
     * 删除一条数据 
     * @param string $key KEY名称 
     */  
    public function delete($key) {  
    	
        return $this->redis->delete($key);  
    }  
      
    /** 
     * 清空数据 
     */  
    public function flushAll() {  
        return $this->redis->flushAll();  
    }  
      
    /** 
     * 数据入队列 
     * @param string $key KEY名称 
     * @param string|array $value 获取得到的数据 
     * @param bool $right 是否从右边开始入 
     */  
    public function push($key, $value ,$right = true) {  
        $value = json_encode($value);  
        return $right ? $this->redis->rPush($key, $value) : $this->redis->lPush($key, $value);  
    }  
      
    /** 
     * 数据出队列 
     * @param string $key KEY名称 
     * @param bool $left 是否从左边开始出数据 
     */  
    public function pop($key , $left = true) {  
        $val = $left ? $this->redis->lPop($key) : $this->redis->rPop($key);  
        return json_decode($val);  
    }  
      
    /** 
     * 数据自增 
     * @param string $key KEY名称 
     */  
    public function increment($key) {  
        return $this->redis->incr($key);  
    }  
  
    /** 
     * 数据自减 
     * @param string $key KEY名称 
     */  
    public function decrement($key) {  
        return $this->redis->decr($key);  
    }  
      
    /** 
     * key是否存在，存在返回ture 
     * @param string $key KEY名称 
     */  
    public function exists($key) {  
        return $this->redis->exists($key);  
    }  
      
    /** 
     * 返回redis对象 
     * redis有非常多的操作方法，我们只封装了一部分 
     * 拿着这个对象就可以直接调用redis自身方法 
     */  
    public function redis() {  
        return $this->redis;  
    }  
}