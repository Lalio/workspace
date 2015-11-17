<?php
/**
 * @name	class.DbAction.php
 * @todo 	广告系统超类
 * @version 2012-10-30
 * @author  bo.wang3 
 */
Class DbAction{

    protected $_db; //数据库句柄
    protected $_pn; //分页数
    protected $_callback_rs; //回调结果
    protected $_pagedata;  //页面数据
    public $action; //操作类
    public $do;  //操作方法
    public $func; //功能模式
    public $show; //展示模式
    public $pagedata;
    
    /**
     * @todo 构造函数 初始化相关数据
     * @author bo.wang3
     * @version 2012-10-30
     */
    function __construct(){

	    Core::InitDb();
	    $this->_db = Core::$db['online'];
	    
	    Core::InitDataCache(); //初始化数据缓类
	    $this->_dc = Core::$dc;
	    
        $this->_Init();
    }
    
    /**
     * @todo 析构函数 释放内存
     * @author bo.wang3
     * @version 2012-10-30
     */
    function __destruct(){

        $this->_db = null;   //free
        $this->_pn = null;   //free
        $this->_pagedata = null;   //free

    }
    
    /**
     * @todo 获取数据 fileCache->Database
     * @author bo.wang3
     * @param $sql SQL查询命令
     * @version 2012-11-01
     */
    public function GetData($sql){
    
        $data = array();
        $memKey = md5($sql);
    
        $data = $this->_dc->getData($memKey,43200); //读取缓存数据;
        if(false === $data || (isset($_GET['i']) && $_GET['i']=='c')){
            $data = $this->_db->dataArray($sql);
            $this->_dc->setData($memKey,$data); //设置缓存数据
        }
    
        return $data;
    }

    protected function _Init(){

        $this->action = $_REQUEST['action']?trim($_REQUEST['action']):'Order';
        $this->do = $_REQUEST['do']?trim($_REQUEST['do']):'Main';
        $this->func = $_REQUEST['func']?trim($_REQUEST['func']):'';
        $this->show = $_REQUEST['show']?trim($_REQUEST['show']):'list';
        $this->_pn = $_REQUEST['page']?intval($_REQUEST['page']):1;
    }

    /**
     * @todo 封装了后台表增删改查的逻辑
     * @author bo.wang3
     * @param $func:操作类型 $data：数据数组 $table：数据表$templatefile：模版文件$pageSize：分页条数; $ext 逻辑层向页面传递的辅助信息
     * @version 2012-09-14
     */
    function BackendDbLogic($data,$table,$templatefile,$wherestr = '',$orderstr = ' ORDER BY id DESC',$pageSize = 30,$ext){

        if(is_array($data)){
            foreach ($data as $k => $v){  
                $data[$k] = addslashes($v); //输入数据防SQL注入
            }
        }
        
        $idstr = $table != 'pd_files'?"id = $_REQUEST[id]":"file_id = $_REQUEST[file_id]";
        
        switch($this->func){
            
            case 'add': //添加数据

                $op_rs = $this->_db->insert($table,$data);

                break;

            case 'delete': //删除数据

                $op_rs = $this->_db->delete($table,$idstr);
                
                break;    
                
            case 'edit': //编辑数据
            
                $op_rs = $this->_db->update($table,$data,$idstr);
                
                break;
                
           case 'top': //置顶数据

                $op_rs = $this->_db->update($table,array('ts' => TIMESTAMP),$idstr);
                
                break;
                
           case 'up': //上升一位
            
                $rs = $this->_db->rsArray("SELECT id,ts FROM $table WHERE ts > $_REQUEST[ts] ORDER BY ts ASC LIMIT 0,1");
                
                if(empty($rs)){
                    $op_rs = false;
                    $this->_db->_errorMsg = '当前已经是最大';
                }else{
                    $this->_db->update($table,array('ts' => $_REQUEST['ts']),$idstr);
                    $this->_db->update($table,array('ts' => $rs['ts']),$idstr);
                    $op_rs = true;
                }
                
                break; 

           case 'down': //下降一位
                
                $rs = $this->_db->rsArray("SELECT id,ts FROM $table WHERE ts < $_REQUEST[ts] ORDER BY ts DESC LIMIT 0,1");
                
                if(empty($rs)){
                    $op_rs = false;
                    $this->_db->_errorMsg = '当前已经是最小';
                }else{
                    $this->_db->update($table,array('ts' => $_REQUEST['ts']),$idstr);
                    $this->_db->update($table,array('ts' => $rs['ts']),$idstr);
                    $op_rs = true;
                }
                
                break;

            default: //读取页面数据

            	//设置分页
                $sp = new Page();
                $sp->setvar ( array_merge ( array ('page', 'i', 'dy' )) );
                $sp->SetAdmin($pageSize,$this->_db->rsCount("SELECT * FROM $table ".($wherestr?' WHERE '.$wherestr:'')),$this->pn,'');
                $this->_pagedata['sp']  = $sp->output(true);

                $this->_pagedata['list'] = $this->_db->dataArray("SELECT * FROM $table".($wherestr?' WHERE '.$wherestr:'').$orderstr." LIMIT ".$sp->Limit());
                
                //编辑
                if($this->show == 'edit'){
                    $this->_pagedata['editInfo'] = $this->_db->rsArray("SELECT * FROM $table WHERE $idstr LIMIT 0,1");
                }
                
                include template($templatefile,$_REQUEST['action']); //根据action加载不同目录下的模板
                exit;
        }
        
        //返回操作结果
        if(false !== $op_rs){
             $this->_callback_rs = array('rs' => 0,'msg' => $op_rs);
        }else{
             $this->_callback_rs = array('rs' => 1,'msg' => $this->_db->_errorMsg);            
        }

        if(is_array($this->_callback_rs)){
            echo json_encode($this->_callback_rs);
            exit;
        }
    }
    
}
