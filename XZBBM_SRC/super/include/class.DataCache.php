<?php
if(!defined('IN_SYS')) {
    echo ("HTTP/1.1 404 Not Found");
    die;
}
 /**
  * 数据缓存类
  */
 class DataCache{
     
     private $cacheDir = '/cache' ;   //缓存绝对路径
     private $gzip = true;           //是否对缓存数据压缩
     private $md5 = true;            //是否对键值加密
     private $cacheTime = 3600;      //默认缓存时间
     private $level = 2;             //缓存文件夹层数 0 , 1 , 2
     private $dirCount = 100;        //每层文件夹数目
     private $mtime = '';            //当前文件修改时间
     private $cacheKey = '';         //键
     private $fileName = '';         //文件名
     private $file = '';             //文件完整路径
     private $site = 'xzbbm.net';       //站点标识
     private $cat = 'default';       //缓存数据分类
     private $type = 'txt';          //缓存数据类型data , sql 
     
     
     /*
         * 功能: 构造函数
         * 参数: 无
         * 返回: 无
         */
     public function __construct(){
     }
     
     /*
         * 功能: 配置数据缓存类
         * 参数: $config->数组
         * 返回: 无
         */
     public function setConfig($config){
         if(is_array($config)){
             foreach($config as $k => $v){
                 $this->__set($k , $v);
             }   
         }
     }
     
     public function __set($key='' , $value=''){
         $this->$key = $value;
     }
     
     /*
         * 功能: 获取缓存数据
         * 参数: $cacheKey->键 $cacheTime->缓存时间 $type->缓存数据类型 $cat->缓存数据分类
         * 返回: 是否获取数据成功 Boolen
         */
     public function getData($cacheKey,$cacheTime=3600,$type='',$cat=''){
         
        $cacheTime = ($cacheTime == -1) ? 9999999999 : $cacheTime;
        $this->cacheTime = $cacheTime;
        $this->type = $type ? $type : $this->type;
        $this->cat = $cat ? $cat : $this->cat;
         // 缓存超时或不缓存返回false
         if (!$this->cacheTime){
             return false;
         }else {
             $this->file = $this->getFile($cacheKey); 
             if (!is_file($this->file))return false;
             $this->mtime = @filemtime($this->file);
             
             if (time() - $this->cacheTime < $this->mtime){
                 //返回数据  
                 return $this->de(file_get_contents($this->file));           
             }else{
                 return false;
             }
         }
         
     }
     
     /*
         * 功能: 设置缓存数据
         * 参数: $cacheKey->键 $data->缓存数据 $type->缓存数据类型 $cat->缓存数据分类
         * 返回: 写入是否成功 Boolen
         */
     public function setData($cacheKey,$data,$type='',$cat=''){
         
         $this->type = $type ? $type : $this->type;
         $this->cat = $cat ? $cat : $this->cat;
         
         $this->file = $this->getFile($cacheKey); 
         
         $data = $this->en($data);
         return tep_write($this->file,$data);
         
     }
     
     /*
         * 功能: 删除缓存文件
         * 说明: $cacheKey 存在，删除此缓存数据。
         * 说明: $cacheKey 为空，$cat存在，删除此分类下所有缓存数据
         * 说明: $cacheKey 为空，$cat为空，$type存在，删除此类型下所有缓存数据
         * 说明: 参数全部为空，删除所有缓存数据
         * 参数: 
         * 返回: Boolen
         */
     public function delData($cacheKey='',$type='',$cat=''){
         //删除指定KEY的缓存
         if ($cacheKey){
             if (!$type){ $type = $this->type; }
             if (!$cat){ $cat = $this->cat; }
             $_file = $this->getThisFile($cacheKey,$type,$cat);
             if (file_exists($_file)) unlink($_file); else return false;
         }else {
             //删除指定分类目录下所有缓存文件
             if ($cat){
                 if (!$type)return false;
                 $_dir = $this->cacheDir .'/'. $type .'/'.$cat;
                 if (file_exists($_dir)) tep_rmdir($_dir); else return false;
             }else {
                 //删除指定类型下的所有缓存文件
                 if ($type){
                     $_dir = $this->cacheDir .'/'. $type;
                     if (file_exists($_dir)) tep_rmdir($_dir); else return false;
                 }else {
                     //删除所有数据缓存文件 
                      $_dir = $this->cacheDir;
                     if (file_exists($_dir)) tep_rmdir($_dir); else return false;
                 }
             }
         }
         return true;
     }
     
     /*
         * 功能: 获取数据缓存路径
         * 参数: 
         * 返回: 完整路径
         */
     public function getThisFile($cacheKey='',$type='',$cat=''){
         
         //取出当前完整路径
         if (!$cacheKey){
             return $this->file;
         }else {
             $this->type = $type ? $type : $this->type;
             $this->cat = $cat ? $cat : $this->cat;
             return $this->getFile($cacheKey);
         }
         
     }
     
     
     function getFileSize(){
         
     }
     
     function getDirSize(){
         
     }
     
     /*
         * 功能:建立缓存文件完整路径 
         * 参数: 文件名（键值）
         * 返回: 缓存文件完整路径
         */
     private function getFile($fileName){
         
         //文件存在返回文件，不存在就建立路径
         $_dir1 = '';   //第一层
         $_dir2 = '';   //第二层
         
         if ($this->md5) $fileName = md5($fileName);
         //$_len = strlen($fileName);
         $_len = 5;
         $_dir = $this->cacheDir .'/'. $this->type .'/'.$this->cat.'/';
         
         if ($this->level==2){
             for($i=0;$i<$_len;$i++){
                 $_dir1 += ord($fileName{$i})*$i;
                 $_dir2 += ord($fileName{$i})*($i+3);
             }
             $_dir1 %= $this->dirCount;
             $_dir2 %= $this->dirCount;
             tep_multDir($_dir .$_dir1.'/'.$_dir2);
             $this->file = $_dir .$_dir1.'/'.$_dir2.'/'.$this->site .'_'.$fileName.'.'.$this->type;
         }elseif ($this->level==1){
             for($i=0;$i<$_len;$i++){
                 $_dir1 += ord($fileName{$i})*$i;
             }
             $_dir1 %= $this->dirCount;
             tep_multDir($_dir .$_dir1);
             $this->file = $_dir .$_dir1.'/'.$this->site .'_'.$fileName.'.'.$this->type;
         }else {
             tep_multDir($_dir);
             $this->file = $_dir .$this->site .'_'.$fileName.'.'.$this->type;
         }
         
         unset($_dir1,$_dir2,$_len,$_dir);
         
         return $this->file; 
         
         
     }
     
     
     /*
         * 功能: 解压缩，反序列化输出
         * 参数: 缓存数据内容
         * 返回: 数据
         */
     private function de($data){
         
         if ($this->gzip) return unserialize(gzinflate($data));
         return unserialize($data);

     }
     
     /*
         * 功能: 序列化，压缩输入
         * 参数: 缓存数据内容
         * 返回: 数据
         */
      private function en($data){
         
         if ($this->gzip) return gzdeflate(serialize($data));
         return serialize($data);
         
      }
     
 }
 
 
 
 /**
 ****************************
 ****** 附上调用的函数  *******
 ****************************
 */

 /*
     * 功能: 建立多层路径
     * 参数: 完整路径
     * 返回: Boolen
     */
 function  tep_multDir($pathname,$mode=0777){
      
       $pathArr = explode('/',$pathname);
        $_path = '';
        foreach ($pathArr as $v){
            $_path.= $v;
            if (!is_dir($_path)){
                @mkdir($_path,$mode);
            }
            $_path.='/';
        }
        if (is_dir($pathname)){
            return true;
        }else{
            return false;
        }
           
 }

 /*
     * 功能: 写文件
     * 参数: 文件名，内容，操作类型
     * 返回: Boolen
     */
 function tep_write($fileName, $content, $type = "w"){
     
      $fd = fopen($fileName, $type);
      ignore_user_abort(true);
      if(!flock($fd,LOCK_EX)){
        die();
      }
      //fseek()
      if ($fd){
         fwrite($fd, $content);
         fclose($fd);
         ignore_user_abort(false);
         return true;
      } else{
         ignore_user_abort(false);
         return false;
      }
 }   

 /*
     * 功能: 移除文件夹 
     * 参数: 路径
     * 返回: Boolen
     */
 function tep_rmdir($path){
    
     if (!is_dir($path)) return false;
     if ($path{strlen($path)-1}=="/"){
        $path = substr($path,0,-1);
     }
     
    /* $handle=@opendir($path);//echo $path;
     while($val=@readdir($handle)){//echo $val;
         if ($val=='.' || $val=='..') continue;
         $value=$path."/".$val;
         if (is_dir($value)){
             tep_rmdir($value);
         }else if (is_file($value)){echo $value,'<br>';
             @unlink($value);
         }
     }
     @closedir($handle);
     rmdir($path);*/
    $d = dir($path);
    while (false !== ($val = $d->read())) {
      if ($val=='.' || $val=='..') continue;
      $value=$path."/".$val;
     if (is_dir($value)){
         tep_rmdir($value);
     }else if (is_file($value)){
         @unlink($value);
     }
    }
    $d->close();
    rmdir($path);
    
     return true;
     
 }