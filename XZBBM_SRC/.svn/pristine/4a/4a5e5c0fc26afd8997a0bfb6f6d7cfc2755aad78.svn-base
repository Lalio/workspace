<?php
/**
 * @name	class.Mads.php
 * @todo 	广告系统类
 * @version 2012-10-30
 * @author  bo.wang3 
 */
Class Mads extends MadsService{

    protected $_pn; //分页数
    protected $_callback_rs; //回调结果
    protected $_pagedata;  //页面数据
    protected $_scriptstarttime;
    public $adstype; //广告类型
    public $ad_sub_type; //旧广告类型
    public $clients; //广告客户
    public $area;  //投放区域
    public $action; //操作类
    public $do;  //操作方法
    public $func; //功能模式
    public $show; //展示模式
    public $admin; //用户信息
    public $menu; //菜单栏
    public $channel; //频道定向
    public $pagedata;
    public $sales; //销售人员信息
    public $works; //投放人员信息
    
    /**
     * @todo 构造函数 初始化相关数据
     * @author bo.wang3
     * @version 2012-10-30
     */
    function __construct(){
    	
    	parent::__construct();

        $this->_Init();  //初始化系统参数
        $this->_CheckAuth();  //检测登陆及授权
        $this->_InitAdsType();  //初始化广告类型数组
        $this->_InitClient();  //初始化广告客户数组
        $this->_InitMenu();  //初始化顶部菜单栏
        
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

    protected function _Init(){

        $this->action = $_REQUEST['action']?trim($_REQUEST['action']):'Order';
        $this->do = $_REQUEST['do']?trim($_REQUEST['do']):'Main';
        $this->func = $_REQUEST['func']?trim($_REQUEST['func']):'';
        $this->show = $_REQUEST['show']?trim($_REQUEST['show']):'list';
        $this->_pn = $_REQUEST['page']?intval($_REQUEST['page']):1;
        $this->area = Core::$vars['Area'];
        $this->sales = Core::$vars['Sales'];
        $this->workers = Core::$vars['Workers'];
        $this->_scriptstarttime = microtime(true);  //记录脚本开始执行时间
    }
    
    protected function _InitMenu(){
    
		$menulist = array(
						'<th'.($this->do=='Rms' || $this->do=='Basic'?' id=menu_active':'').'><a href="./?action=Reserve&do=Rms&show=list">预定资源</a></th>',
			            '<th'.($this->action=='Query' && $this->do=='Task'?' id=menu_active':'').'><a href="./?action=Query&do=Task">库存查量</a></th>',
						'<th'.($this->action=='Query' && $this->do=='Puv'?' id=menu_active':'').'><a href="./?action=Query&do=Puv">P/UV查量</a></th>',
			            '<th'.($this->action=='Query' && $this->do=='RealTime'?' id=menu_active':'').' style="display:none;"><a href="./?action=Query&do=RealTime">实时库存</a></th>',
			            '<th'.($this->action=='Order' && $this->do=='ContractV2'?' id=menu_active':'').'><a href="./?action=Order&do=ContractV2&show=list">合同管理</a></th>',
			            '<th'.($this->do=='Transaction' && $this->show!='search'?' id=menu_active':'').'><a href="./?action=Order&do=Transaction&show=list">广告下单</a></th>',
						'<th'.($this->do=='Transaction' && $this->show=='search'?' id=menu_active':'').'><a href="./?action=Order&do=Transaction&show=search">订单检索</a></th>',
			            '<th'.($this->action=='FlashSet' && $this->do=='Template'?' id=menu_active':'').'><a href="./?action=FlashSet&do=Template&show=list">容器配置</a></th>',
			            '<th'.($this->action=='FlashSet' && $this->do=='Show2Account'?' id=menu_active':'').'><a href="./?action=FlashSet&do=Show2Account&show=list">自节帐配</a></th>',
			            '<th'.($this->do=='PhaseManage'?' id=menu_active':'').'><a href="javascript:;" style="display:none;">投放系统</a></th>',
			            '<th'.($this->action=='SuperVision'?' id=menu_active':'').'><a href="./?action=SuperVision&do=Run&show=list">数据盘点</a></th>',
						'<th'.($this->do=='RuleSet'?' id=menu_active':'').'><a href="./?action=System&do=RuleSet&show=list">规则设定</a></th>',
						'<th'.($this->do=='ClientManage'?' id=menu_active':'').'><a href="./?action=System&do=ClientManage&show=list">客户管理</a></th>',
						'<th'.($this->do=='CommercialClient'?' id=menu_active':'').'><a href="./?action=System&do=CommercialClient&show=list">商业客户</a></th>',
						'<th'.($this->do=='CidManage'?' id=menu_active':'').'><a href="./?action=System&do=CidManage&show=list">广告位</a></th>',
						'<th'.($this->do=='BlackList'?' id=menu_active':'').'><a href="./?action=System&do=BlackList&show=list">黑词库</a></th>',
						'<th'.($this->do=='Tgid'?' id=menu_active':'').'><a href="./?action=System&do=Tgid&show=list">TGID</a></th>'
						); 

    	$items = explode(',', Core::$configs['auth'][$this->admin[role]]);
		foreach($items as $item){
			$this->menu .= $menulist[$item];
		}
    }

    protected function _InitAdsType(){

        $rs = $this->_db->dataArray('SELECT * FROM channel WHERE flag = 0');  //获得广告类型
        foreach($rs as $data){ //初始化广告类型数组
            $this->adstype[$data['cid']] = '['.$data['cid'].'] '.$data['cname'];
        }
        
        $rs = $this->_db->dataArray('SELECT * FROM channel');  //获得全部广告类型
        foreach($rs as $data){ //初始化广告类型数组
        	$this->adsalltype[$data['cid']] = '['.$data['cid'].'] '.$data['cname'];
        }
        
        $this->ad_sub_type = Core::$vars['Type1'];
        $this->channel = Core::$vars['Channel'];
    }

    protected function _InitClient(){

        $rs = $this->_db->dataArray('SELECT * FROM client');  //获得广告客户
        foreach($rs as $data){ //初始化广告类型数组
            $this->clients[$data['vid']] = '['.$data['vid'].'] '.$data['vname'];
        }

    }

    /**
     * @todo 验证登陆 已登录返回用户信息数组 未登录跳去登陆页
     * @author bo.wang3
     * @version 2012-10-30
     */
    protected function _CheckAuth(){
        
        $ip_allow = array("127.0.0.1","113.106.26.45","180.153.21.79"); //绕过服务器本地接口访问
        $action_allow = array("Auth"); //公共类白名单
        $do_allow = array("ReadBinLog","Schedule","Daemon","InformAe","Aid2Contract","ZzSync","CheckAmountResult","ReadBinLog","RealTimeBackup"); //公共方法白名单
        
        $this->admin = $_SESSION['admin'];
        
        if(empty($this->admin['role']) && !in_array($this->action,$action_allow) && !in_array($this->do,$do_allow) && !in_array(get_ip(), $ip_allow)){
        	header('Location:./?action=Auth');
            exit;
        }else{
            define("ADMIN",$this->admin['username']);
            define("ROLE",$this->admin['role']);
        }
        
        //检查二级授权
        /*
        switch(ROLE){
        	case 'WORKER':
        		if(!(in_array($this->action,array('Order','Auth')) &&　in_array($this->action,array('ContractV2','Transaction')))){
	        		go_win(2,'对不起，你的权限不足。');
	        		exit;
        		}
        		break;
        }
        if(ROLE == 'AE' && !in_array($this->do,array('Order'))){
        	go_win(2,'对不起，你的权限不足。');
        	exit;
        }
        
        if(ROLE == 'WORKER' && !in_array($this->do,array('Reserve'))){
        	go_win(2,'对不起，你的权限不足。');
        	exit;
        }
        */
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
        
        switch ($table){
        	
        	case 'ads':
        		$idstr = "aid = $_REQUEST[aid]";
        		break;
        		
        	case 'client':
        		$idstr = "vid = $_REQUEST[id]";
        		break;
        		
        	case 'channel':
        		$idstr = "cid = $_REQUEST[cid]";
        		break;
        		
        	default:
        		$idstr = "id = $_REQUEST[id]";
        		break;
        }
        
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
                $this->pagedata['sp']  = $sp->output(true);

                $this->pagedata['list'] = $this->_db->dataArray("SELECT * FROM $table".($wherestr?' WHERE '.$wherestr:'').$orderstr." LIMIT ".$sp->Limit());
                
                if(isset($_GET['dg']) && $_GET['dg'] == 'ml'){
                	echo "SELECT * FROM $table".($wherestr?' WHERE '.$wherestr:'').$orderstr." LIMIT ".$sp->Limit();
                }
                
                //编辑
                if($this->show == 'edit'){
                    $this->pagedata['editInfo'] = $this->_db->rsArray("SELECT * FROM $table WHERE $idstr LIMIT 0,1");
                }
                
                include template($templatefile,$_REQUEST['action']); //根据action加载不同目录下的模板
                exit;
        }
        
        //主库操作的后续操作
        
        //广告下单模块
        if($table == 'ads' && in_array($this->func,array('add','edit'))){
        	
        	$op_aid = empty($data['aid'])?$op_rs:intval($data['aid']);
        	
        	if(in_array($this->func,array('add','copy'))){
        		$log = "[增加广告] id= ($op_aid) $data[title]";
        		$data['aid'] = $op_rs; 
        		foreach($data as $k => $v){
        			$data[$k] = urlencode($v);
        		}
        		$ext = json_encode($data);
        	}elseif($this->func == 'edit'){
        		$log = "[修改广告] id= ($op_aid) $data[title]";
        	}
        	
        	//订单绑定合同
        	if($op_aid && $_REQUEST['contractid']){
        		$this->AidBindContract($op_aid, $_REQUEST['contractid']);
        	}
        	
        	//排期表处理
        	if($this->func == 'add' && !empty($_REQUEST['schedule'])){
        		$schedule = stripslashes($_REQUEST['schedule']);
        		$schedule_arr = json_decode($schedule,true);
        		foreach($schedule_arr as $k => $v){
        			mysql_query( "insert into ads_ext (aid,daytime,cpm) values ('$op_aid', '".strtotime($k)."' , '$v')" );
        		}
        	}
        	
        	//如果投放周期、日CPM量有一个发生改变，则该订单禁用排期表管理
        	if($this->func == 'edit' && false != preg_match('/\"starttime\"|\"endtime\"|\"cpm\"/', $ext)){
        		$this->_db->delete('ads_ext',"aid = $op_aid");
        	}
        	
        	//日志处理
        	$rs = $this->WriteLog(ADMIN, $log, $op_aid, urldecode($ext));
        	if(false === $rs['rs']){
        		echo json_encode(array('rs'=>1,'msg'=>'日志写入失败！'+$rs['msg']));exit;
        	}
        }
        
        //PUV查量模块 
        if($table == 'ad_puvamounts' && in_array($this->func,array('add'))){
        	$data['tid'] = $op_rs;
        	$rs = file_get_contents('http://14.17.117.101/ad_t4ulog.php?'.http_build_query($data));
        	echo $rs;
        }	
        
        //库存查量模块
        if($table == 'ad_checkamounts' && in_array($this->func,array('add'))){
        	
        	$msg = json_decode(stripslashes($data['input']),true);
        	if($msg['version'] == 2){

        		$msg['id'] = $op_rs;
        		$msg['op_type'] = $data['op_type'];
        		if(HOSTNAME == 't3.56.com'){
        			$msg['srcpath'] = '/tmp/dispatch_t3';
        			$msg['workpath'] = '/tmp/dispatch_t3_workspace';
        			$port = 8081;
        		}else{
        			$msg['srcpath'] = '/home/applications/ad_simulation/dispatch';
        			$msg['workpath'] = '/home/applications/adsim_workspace/dispatch';
        			$port = 8080;
        		}
        		$msg = urlencode(json_encode($msg));
        		
        		//与查量服务器通信
        		Http::Post('180.153.21.185','dispatch',$msg,$port);
        	}
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
    
    /**
     * @todo 适用于子合同、子预定单系统
     * @author bo.wang3
     * @param $func:操作类型 $data：数据数组 $table：数据表$templatefile：模版文件$pageSize：分页条数; $ext 逻辑层向页面传递的辅助信息
     * @version 2012-09-14
     */
    function BackendDbLogicMultiEdit($data,$table,$templatefile,$wherestr = '',$orderstr = ' ORDER BY id DESC',$pageSize = 30,$ext){
    	
    	switch ($table){
    		case 'ad_contract':
    			$idstr = "id = $_REQUEST[id]";
    			$edit_str = "contract_id = '$_REQUEST[contract_id]'";
    			$group_str = " GROUP BY contract_id";
    			break;
    		case 'ad_reserve':
    			$idstr = "id = $_REQUEST[id]";
    			$edit_str = "reserve_id = '$_REQUEST[reserve_id]'";
    			$group_str = "GROUP BY reserve_id";
    			break;
    		default:
	    		$edit_str = "id = $_REQUEST[id]";
	    		break;
    	}
    	
    	switch($this->func){
    		case 'add': //添加数据
    			foreach($data as $k => $v){
					$data[$k]['id'] = $this->_db->insert($table,$v);
					if(!empty($data[$k]['id'])){
						if($table == 'ad_reserve'){
							$this->WriteReserveLog(ADMIN, "[新增订单]", $data[$k]['id'], "ad_reserve", $v, "", "insert","");
						}elseif($table == 'ad_contract'){
							$this->WriteReserveLog(ADMIN, "[新增合同]", $data[$k]['id'], "ad_contract", $v, "", "insert","");
						}
					}
    			}
    			break;
    		case 'delete': //删除数据
				$op_rs = $this->_db->delete($table,$idstr);
				if(!empty($op_rs)){
					if($table == 'ad_reserve'){
						$this->WriteReserveLog(ADMIN, "[删除订单]", $_REQUEST['id'], "ad_reserve", "", "", "delete" , $ext);
					}elseif($table == 'ad_contract'){
						$this->WriteReserveLog(ADMIN, "[删除合同]", $_REQUEST[id], "ad_contract", "", "", "delete",""); 
					}
				}
    			break;
    		case 'edit': //编辑数据
    			foreach($data as $k => $v){
    				if(empty($data[$k]['id'])){ //同时处理新增的情况
						$data[$k]['id'] = $this->_db->insert($table,$v);
						if(!empty($data[$k]['id'])){
							if($table == 'ad_reserve'){
								$this->WriteReserveLog(ADMIN, "[新增订单]", $data[$k]['id'], "ad_reserve", $v, "", "insert","");
							}elseif($table == 'ad_contract'){
								$this->WriteReserveLog(ADMIN, "[新增合同]", $data[$k]['id'], "ad_contract", $v, "", "insert","");
							}
						}
    				}else{
						if($table == 'ad_reserve'){
							$databefore = $this->_db->dataArray("SELECT * FROM ad_reserve WHERE id = $v[id]");
    						//可编辑的状态 保存 审核通过（失败）
    						if($v['status'] == 0 || ($v['status'] == 2 && $v['result'] == 2) || ROLE != 'AE'){
								$this->_db->update($table,$v,"id = $v[id]");
								$this->WriteReserveLog( ADMIN, "[修改订单]", $data[$k]['id'], "ad_reserve", $v, $databefore, "update","");
							}
						}else{
							if($table == 'ad_contract'){
								$databefore = $this->_db->dataArray("SELECT * FROM ad_contract WHERE id = $v[id]");
							}
							$this->_db->update($table,$v,"id = $v[id]");
							if($table == 'ad_contract'){
								$this->WriteReserveLog( ADMIN, "[修改合同]", $data[$k]['id'], "ad_contract", $v, $databefore, "update","");
							}
    					}
    				}
				}
    			break;
    		default: //读取页面数据
    			if($this->show == 'edit'){ //编辑
    				$this->pagedata['editInfo'] = $this->_db->dataArray("SELECT * FROM $table WHERE $edit_str ORDER BY id DESC");
    			}else{ //展示
    				//设置分页
    				$sp = new Page();
    				$sp->setvar ( array_merge ( array ('page', 'i', 'dy' )) );
    				$sp->SetAdmin($pageSize,$this->_db->rsCount("SELECT * FROM $table".($wherestr?" WHERE $wherestr":"").$group_str));
    				$this->pagedata['sp']  = $sp->output(true);
    				$this->pagedata['list'] = $this->_db->dataArray("SELECT * FROM $table".($wherestr?" WHERE $wherestr":"")."$group_str $orderstr LIMIT ".$sp->Limit());
    			
    				if(isset($_GET['dg']) && $_GET['dg'] == 'ml'){
    					echo "SELECT * FROM $table".($wherestr?" WHERE $wherestr":"")."$group_str $orderstr LIMIT ".$sp->Limit();
    				}
    			
    			}
    
    			include template($templatefile,$_REQUEST['action']); //根据action加载不同目录下的模板
    			exit;
    		}
    
    		//主库操作的后续操作
    		
    		//预订系统模块 初始化ads_pre
    		/* 初始化ads_pre改到预锁资源的时候进行
    		if($table == 'ad_reserve' && in_array($this->func,array('add','edit'))){
    			
    			foreach($data as $cindex => $cvalue){
    				
    				if(ROLE != 'AE' || $cvalue['status'] == 0 || ($cvalue['status'] == 2 && $cvalue['result'] == 2)){

    					//清除旧的预订单信息
    					$this->_db->delete('ads_pre',"for_rid = {$cvalue['id']}");
    					
    					$areas = explode("\n",trim($cvalue['area']));
    					//ads_pre入库
    					foreach ($areas as $v){
    						if(empty($v)) continue;
    						$tmp = explode('_', $v);
    						//将CPM量平均分配到每一天
    						$cpm = floor($tmp[1]/count(pre_dates($cvalue['starttime'],$cvalue['endtime'])));
    						//分别获取频控和频次
    						$pq = explode('_', $cvalue['freq']);
    						
    						$value = array(
    								'title' => "预订单数据",
    								'description' => "$cvalue[dcs]_$cvalue[cs]_$cvalue[starttime]_$cvalue[endtime]",
    								'link' => '#freq_'.(2880*(int)$pq[0]),
    								'freq' => $pq[1],
    								'starttime' => $cvalue['starttime'],
    								'endtime' => $cvalue['endtime'],
    								'city' => $tmp[0]=='中国'?'':$tmp[0],
    								'cpm' => $cpm,
    								'cid' => $cvalue['cid'],
    								'type' => $cvalue['type'],
    								'channel' => $cvalue['channel'],
    								'username' => 'ads_system',
    								'status' => ($cvalue['status'] == 2 && $cvalue['result'] == 1)?0:1,//支持资源手动改量
    								'for_rid' => $cvalue['id']
    						);
    						
    						if(false === $this->_db->insert('ads_pre',$value)){
    							echo json_encode(array('rs'=>1,'msg'=>"子预订单系统预锁量模块发生异常，请与开发人员联系！"));exit;
    						}
    						unset($cpm);
    						unset($value);
    						unset($tmp);
    					}
    				}
    			}
    		}
    		*/
    		
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
    
    /**
     * @todo 频道字符串转换
     * @author bo.wang3
     * @version 2012-11-01
     */
    public function ChannelSwt($str){
    
    	$array = explode('_', $str);
    	foreach($array as $v){
    		$data .= $this->channel[$v].'_';
    	}
    	
    	rtrim($data,'_');
    	return $data;
    }
    
    /**
     * @todo 根据广告系统名获得公司邮箱地址
     * @author bo.wang3
     * @version 2013-7-31 14:29
     */
    public function GetEmailAdress($name){
    
    	$rs = $this->_db->rsArray("SELECT rtx_username from permission where related_username = '$name'");
    	return $rs['rtx_username'].'@renren-inc.com';
    }
    
	/**
	 * @todo 单次操作记录写日志
	 * @return array('rs','msg')
	 * @author bo.wang3 
	 * @version 2013-10-17 14:29
	 */
	public function WriteLog($username,$log,$aid,$detail){
	/* 
	    $param = array(
	            $this->_db->rsArray("SELECT related_username FROM permission WHERE related_username = '$username' LIMIT 0,1"),
	            $this->_db->rsArray("SELECT aid FROM ads WHERE aid = $aid LIMIT 0,1")
	            );
	    
	    if(empty($param[0])){
	        return array('rs' => false,'msg' => '用户名非法！');
	    }else{
	        $username = $param[0][related_username];
	    }
	    
	    if(empty($param[1])){
	        return array('rs' => false,'msg' => '广告ID非法！');
	    }else{
	        $aid = $param[1][aid];
	    }
	 */
		
        $v = array(
            'username' => $username,
            'log' => $log,
            'aid' => $aid,
            'detail' => stripcslashes($detail)
        );
        
        $rs = $this->_db->insert('admin_log',$v);
        
        if(true === $rs){
            return array('rs' => true);
        }else{
            return array('rs' => false,'msg' => '日志写入失败');
        }
	}
	
	/**
	 * @todo 订单绑定合同
	 * @return array('rs','msg')
	 * @author bo.wang3
	 * @version 2013-10-17 14:29
	 */
	public function AidBindContract($aid,$cid){
		
		$rs = $this->_db->rsArray("SELECT id FROM ad_contract WHERE id = $cid AND (related_aids LIKE '%,$aid,%' OR related_aids LIKE '$aid,%')");

		if(empty($rs)){
			$databefore = $this->_db->dataArray("SELECT * FROM ad_contract WHERE id = $cid");
			$this->_db->conn("UPDATE ad_contract SET related_aids = concat(related_aids,'{$aid},') WHERE id = $cid");
			$this->WriteReserveLog(ADMIN, "[新增广告]", $cid, "ad_contract", array('related_aids'=> implode("",array($databefore[0][related_aids],$aid,',')), 'ts' => TIMESTAMP), $databefore, "update","");
		}
	}
	
	/**
	 * @todo 格式化输出aid信息
	 * @author bo.wang3
	 * @version 2013-10-17 14:29
	 */
	public function DumpAidInfo($aid){

		$rs = $this->_db->rsArray("SELECT * FROM ads WHERE aid = $aid");
		
		$str = '<p>广告编号(aid):'.$aid.'</br>';
		$str .= '广告标题(title):'.$rs['title'].'</br>';
		$str .= '广告位置(cid):'.$this->adstype[$rs['cid']].'</br>';
		$str .= '广告类型(type):'.$this->ad_sub_type[$rs['type']].'</br>';
		$str .= '广告客户(vid):'.$this->clients[$rs['vid']].'</br>';
		/*
		$str .= '投放量(cpm):'.$rs['cpm'].'</br>';
		$str .= '素材/模板(flash):'.$rs['flash'].'</br>';
		$str .= '链接/参数(link):'.$rs['link'].'</br>';
		$str .= '关键词(keyword):'.$rs['keyword'].'</br>';
		$str .= '屏蔽关键词(excludekeyword):'.$rs['excludekeyword'].'</br>';
		$str .= '定向区域(area):'.$rs['area'].'</br>';
		$str .= '屏蔽区域(excludearea):'.$rs['excludearea'].'</br>';
		$str .= '权重(weight):'.$rs['weight'].'</br>';
		$str .= '开始时间(starttime):'.date('Y-m-d H:i:s',$rs['starttime']).'</br>';
		$str .= '结束时间(endtime):'.date('Y-m-d H:i:s',$rs['endtime']).'</br>';
		$str .= '素材地址(tp_flv):'.$rs['tp_flv'].'</br>';
		$str .= '第三方曝光监测(tp_viewurl):'.$rs['tp_viewurl'].'</br>';
		$str .= '第三方点击监测(tp_click):'.$rs['tp_click'].'</br></p>';
		*/
		return $str;
	}
	
	/**
	 * @todo 根据Vid获取损耗率
	 * @author bo.wang3
	 * @version 2013-6-25 14:29
	 */
	public function GetLossRateByVid($vid){
	
		$rs = $this->_db->rsArray("SELECT lossrate FROM client WHERE vid = $vid");
		return $rs['lossrate'];
	}
	
	/**
	 * @todo 对查量结果按照损耗规则重新处理
	 * @ 基于最后两次查量结果取较大值
	 * @author bo.wang3
	 * @version 2013-8-27 14:29
	 * @return string
	 */
	public function GetProcessedCheckAmountResult($task_id){
	
		$chk_rs = '';
	
		//筛选省份信息
		foreach($this->area as $k => $v){
			if(in_array($k, array('北京','天津','上海','重庆','港澳台','海外'))) continue;
			$pros[] = $k;
		}

		//获取查完量的预订单
		$rs = $this->_db->rsArray("SELECT input,output,for_rid 
								   FROM ad_checkamounts 
								   WHERE id = $task_id 
								   LIMIT 0,1");
		
		if(empty($rs)){ //没有查量结果则返回空
			return '';
		}
		
		//特定客户损耗
		$inputs = json_decode($rs['input'],true);
		$vid_rate = !empty($inputs['client'])?$this->GetLossRateByVid($inputs['client']):1;
	
		//特定区域损耗
		$outputs = explode(',', json_decode($rs['output'],true));
		
		//重新排序
		$areas = explode('_', $inputs['area']);
		foreach($areas as $area){
			foreach($outputs as $output){
				if(strstr($output,$area)){
					$tar[] = $output;
				}
			}
		}
		
		foreach ($tar as $output){
	
			$tmp = explode(':', $output);
	
			if($tmp[0] == '中国'){ //全国 90%
				$chk_rs .= "$tmp[0]:".round($tmp[1]*$vid_rate*0.9,1).',';
				continue;
			}
	
			foreach($pros as $pro){ //省份 80%
				if(strstr($tmp[0], $pro)){
					$chk_rs .= "$tmp[0]:".round($tmp[1]*$vid_rate*0.8,1).',';
					continue 2;
				}
			}
	
			//地区70%
			if(strstr($tmp[0], '广州')){
				//广州特殊50%的损耗 改回70%
				$chk_rs .= "$tmp[0]:".round($tmp[1]*$vid_rate*0.7,1).',';
			}else{
				$chk_rs .= "$tmp[0]:".round($tmp[1]*$vid_rate*0.7,1).',';
			}
		}
	
		return rtrim($chk_rs,',');
	}
	
	/**
	 * @todo 获取实际投放量:根据时间区间，预订单编号或广告订单号
	 * @备注：由于有了ad_dayfinish表，所以对于实际投放量和投放设置量实际上可以直接从ad_dayfinish中拿到
	 * @author bo.wang3
	 * @version 2013-8-27 14:29
	 * @input 时间戳
	 * @$q_type 需要盘点的贴片类型 主要是处理全贴片
	 * @return int
	 */
	public function GetActualAmount($starttime,$endtime,$id,$type = 'ads',$q_type = 1){
		
		if(!is_numeric($starttime) || !is_numeric($endtime)){
			exit('GetActualAmount - 时间戳输入不正确！');
		}
		
		if($type == 'ads'){
			
			$rs = $this->_db->rsArray("SELECT ads.aid,sum(view) as t,cid,type,from_unixtime(starttime),from_unixtime(endtime) FROM ad_log,ads
							   		   WHERE ad_log.aid = $id
							  		   AND stime between $starttime and $endtime
							   		   AND ad_log.aid = ads.aid");
			
			//判定类型的有效性
			if($rs['type'] != 43 && $rs['type'] != $q_type){
				return 0;
			}
			
			//全贴的预定量按 0.85 0.05 0.15 的比例计算
			if($rs['cid'] == 213 && $rs['type'] == 43){
				if($q_type == 1){
					$total = ($rs['t']*0.85);
				}elseif($q_type == 2){
					$total = ($rs['t']*0.05);
				}else{
					$total = ($rs['t']*0.10);
				}
			}else{
				$total = $rs['t'];
			}
			
			return $total;
			
		}elseif($type == 'ad_reserve'){
			
			$total = 0;
			
			//找出对应的aid
			$rss = $this->_db->dataArray("select ad_contract.related_aids from ad_contract,ad_reserve where ad_reserve.id = {$id} and ad_reserve.reserve_code = ad_contract.reserve_code");
			foreach ($rss as $rs){ //一个授权编码对应了多个子合同
				$related_aids .= $rs['related_aids'];
			}
			
			$related_aids = explode(',',rtrim($related_aids,','));
			
			foreach($related_aids as $aid){
				if(empty($aid)) continue;
				$total += $this->GetActualAmount($starttime,$endtime,$aid,'ads',$q_type);
			}
			
			return $total;
		}
	}
	
	/**
	 * @todo 获取客户真实需求量:根据预订单编号或者广告订单号
	 * @author bo.wang3
	 * @version 2013-8-27 14:29
	 * @input
	 * @return int
	 */
	public function GetConsumerNeed($aid = '',$rid = '',$type = 'ads',$starttime,$endtime){
		
		if($type == 'ads'){
			//获取城市名
			$ads = $this->_db->rsArray("select concat(city,area) as ca from ads where aid = $aid");
			//获取预订单ID
			$res = $this->_db->rsArray("select ad_reserve.id from ad_reserve,ad_contract where concat(',',ad_contract.related_aids) rlike ',$aid,' and ad_reserve.reserve_code = ad_contract.reserve_code");
			if(empty($res)){
				return '-';
			}
			//获得客户真实需求量
			$v = $this->_db->rsArray("select starttime,endtime,cpm from ads_pre where for_rid = $res[id] and city rlike '$ads[ca]'");
			
			$data_point = GetMixedTime(array('start' => intval($v['starttime']),'end' => intval($v['endtime'])),array('start' => $starttime,'end' => $endtime));
			$valid_days = count(pre_dates($data_point['start'], $data_point['end']));
			
			$needs[0]['cpm'] = $valid_days*$v['cpm'];	
			
		}elseif($type == 'ad_reserve'){
			//获取客户真实需求
			$rss = $this->_db->dataArray("select cpm,city,starttime,endtime from ads_pre where for_rid = $rid");
			
			foreach($rss as $k=>$v){
				$data_point = GetMixedTime(array('start' => $v['starttime'],'end' => $v['endtime']),array('start' => $starttime,'end' => $endtime));
				$valid_days = count(pre_dates($data_point['start'], $data_point['end']));
				$rss[$k]['cpm'] = $valid_days*intval($v['cpm']);
			}
			$needs = $rss;
		}
		
		if($type == 'ad_reserve'){
			return $needs;
		}else{
			return $needs[0]['cpm'];
		}
	}
	
	/**
	 * @todo 根据合同id获取该合同下绑定的订单信息
	 * @author bo.wang3
	 * @version 2013-6-25 14:29
	 */
	public function GetAidsByCid($cid){
	
		$rs = $this->_db->rsArray("SELECT related_aids 
								   FROM ad_contract 
								   WHERE id = $cid");
		
		return rtrim($rs['related_aids'],',');
	}
	
	/**
	 * @todo 根据广告订单id获取与该订单绑定的合同信息
	 * @author bo.wang3
	 * @return $rs = array('id','contract_id')
	 * @version 2013-6-25 14:29
	 */
	public function GetCidByAid($aid){
	
		$rs = $this->_db->rsArray("SELECT id,contract_id,reserve_code
								   FROM ad_contract 
								   WHERE concat(',',related_aids) RLIKE ',$aid,'");
		
		return $rs;
	}
	
	/**
	 * @todo 根据合同id获取与该合同对应的预订单信息
	 * @author bo.wang3
	 * @version 2013-6-25 14:29
	 */
	public function GetRidByCid($cid){
	
		$rs = $this->_db->rsArray("SELECT ad_reserve.id,reserve_id 
								   FROM ad_contract,ad_reserve 
								   WHERE ad_contract.id = $cid 
								   AND ad_reserve.reserve_code = ad_contract.reserve_code");
		
		return $rs;
	}
	
   /**
   	* @todo 单次预定资源订单操作记录日志
   	* @return array('rs','msg')
   	* @author xiandi.ou
   	* @version 2014-10-13 10:27
   	*/
	public function WriteReserveLog($username,$log,$id,$table,$data,$databefore,$state,$ads_pre_detail){
            
		//获取ads_pre aid 信息
		if(is_array($ads_pre_detail)){
			if(!isset($ads_pre_detail['aid'])){
				$ads_pre_id = $this->_db->dataArray("SELECT aid FROM ads_pre where for_rid =$id");
				$ads_pre_detail['aid'] = "";
				foreach($ads_pre_id as $k => $v){
					$ads_pre_detail['aid'] = implode("",array($ads_pre_detail['aid'],$ads_pre_id[$k]['aid'],","));
				}
			}
		}
                
		//update ads_pre 处理方法
		if($table == 'ads_pre'){
            if($state == "update"){
				$ads_pre_aid = explode(',',$ads_pre_detail['aid']);
				$ads_pre_detail['aid']="";
				foreach ($ads_pre_aid as $v){
					if($v !=""){
						$add_aid = 0;
						$ads_pre_before = $this->_db->rsArray("SELECT * FROM ads_pre where aid = $v");
						foreach($ads_pre_detail as $a => $b){
							if($a != 'aid'){
								if($ads_pre_detail[$a] != $ads_pre_before[$a]){
									$add_aid = 1;
								}
							}
						}
						if($add_aid == 1){
							$ads_pre_detail['aid'] = implode("",array($ads_pre_detail['aid'],$v,","));
						}
					}
				}
			}
			$state = "delete";
			if($ads_pre_detail['aid'] != ""){
				$table = "ad_reserve";
			}
		}

        //ads_pre_detail 内容转成json串
        if(is_array($ads_pre_detail)){
			foreach($ads_pre_detail as $k => $v){
				$ads_pre_detail[$k] = urlencode($v);
			}
			$ads_pre_data =urldecode(json_encode($ads_pre_detail));
		}             

		//detail内容转成json串
		if($state == 'insert'){
//			$crid = $this->_db->dataArray("SELECT id FROM ad_reserve_code = $data[reserve_code]");
			foreach($data as $k => $v){
				$data[$k] = urlencode($v);
			}
			$detail = urldecode(json_encode($data));
		}elseif($state == 'update'){
//			$crid = $this->_db->dataArray("SELECT id FROM ad_reserve_code = $databefore[0][reserve_code]");
			foreach($data as $k => $v){
				if($k != 'ts'){
					if($databefore[0][$k] != $v){
						$common_d[$k] = $v;
					}
				}
			}
			if(!empty($common_d)){
				$common_d['ts'] = $data['ts'];
				foreach($common_d as $k => $v){
					$common_d[$k] = urlencode($v);
				}
				$detail = urldecode(json_encode($common_d));
			}else{
				return;
			}
		}else{
//			$crid = $databefore;
			$detail = "";
		}

		//insert reserve_log 数据表
		if($table == 'ad_reserve' ){
			$v = array(
				'username' => $username,
				'log' => $log,
				'rid' => $id,
				'cid' => "",
				'reserve_detail' => stripcslashes($detail),
				'contract_detail' => "",
				'ads_pre_detail'=>stripcslashes($ads_pre_data)
			);
			$rs = $this->_db->insert('reserve_log',$v);

			if(false == $rs){
				echo $this->_db->errorMsg;
				exit();
			}
		}elseif($table == 'ad_contract'){
			$reserve_code = $this->_db->rsArray("SELECT reserve_code FROM ad_contract where id = $id");
			$rid = $this->_db->rsArray("SELECT id FROM ad_reserve where reserve_code = '".$reserve_code['reserve_code']."' LIMIT 0,1");
			$v = array(
				'username' => $username,
				'log' => $log,
				'rid' => $rid['id'],
				'cid' => $id,
				'reserve_detail' => "",
				'contract_detail' => stripcslashes($detail),
				'ads_pre_detail'=>stripcslashes($ads_pre_data)
			);
			$rs = $this->_db->insert('reserve_log',$v);

			if(false == $rs){
				echo $this->_db->errorMsg;
				exit();
			}
		}
	}	
	
	/**
	 * @todo 判断预订单是否已经锁量
	 * @author bo.wang3
	 * @version 2013-6-25 14:29
	 */
	public function IsLocked($id){
			
		$rs = $this->_db->dataArray("SELECT status FROM ads_pre where for_rid = $id");
	
		foreach($rs as $v){
			$ss += $v['status'];
		}
	
		if(empty($rs) || $ss != 0){
			return false;
		}else{
			return true;
		}
	}
}
