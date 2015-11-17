<?php
/**
* @name:Adreport
* @todo:导出广告数据报表
**/
if(!defined('IN_SYS')) {
	header("HTTP/1.1 404 Not Found");
	die;
}

class AdReport extends Mads{
	
	/**
	 * 获取结果集里的时间
	 */
 	private $_getDays=array();
	/**
	 *组装数据生成输出报表数组
	 */
	private $_getReport=array();
	/**
	 * 广告list
	 */
	private  $_getAdsList=array();
	

	public function __construct(){
		parent::__Construct();
	}
	
	/**
     * 首页
     *
     */
    public function Run(){
		 include template('ad_report','others');
	}


 
	public function report(){ 

		    set_time_limit(0);

			$_bt=$_REQUEST['bt']?$_REQUEST['bt']:'';
			$_et=$_REQUEST['et']?$_REQUEST['et']:'';
			$vid=$_REQUEST['vid']?trim($_REQUEST['vid']):'';
			$aid=$_REQUEST['aid']?trim($_REQUEST['aid']):'';
            
			$diff_date=(strtotime($_et)-strtotime($_bt))/86400;

            if( empty($_bt) || empty($_et)){
			     die("<script>alert('时间参数错误,请检查,格式如20121212');history.go(-1);</script>");
			}
			
           $p=array(
			    'f_date'=>$_bt,
			    't_date'=>$_et
		   );

           $rst=array();

		   if($aid){//第一判断广告id
			  
		       $aid=urldecode($aid);
			   $aids=explode(',',$aid);
			   if( count($aids) > 100 ){
			       die("<script>alert('查询的广告ID不能超过10个');history.go(-1);</script>");
			   }

			   foreach($aids as $a){
				   $p['search_id']=(int)$a;
				   $_rst=$this->_getApi($p);
				   $rst=array_merge($rst,is_array($_rst)?$_rst:array());
			   }
			   
		        
		   }elseif( is_numeric($vid)){//第二判断客户id
		   
		       $p['vid']=$vid;
			   $rst=$this->_getApi($p);
			   $rst=is_array($rst)?$rst:array();
		   
		   }else{
		   

		        die("<script>alert('必须要有正确的客户ID或广告ID');history.go(-1);</script>");
				/** 容易超时，所以被屏蔽使用
				if( $diff_date > 7){
				     die("<script>alert('最多只能查询7天数据');history.go(-1);</script>");
				
				}
				$rst=$this->_getApi($p);
				$rst=is_array($rst)?$rst:array();
		       **/
		   }


		  $days=$this->_getDays;  //获取收集到的days，只有key有效
		  ksort($days);			  //把key顺序排下
		 
		  //模板参数赋值
          Core::$vars['days']=$days;
		  Core::$vars['rst']=$rst;

		  include template('result_report','others');


					
	}
  
   //处理时间格式
    private function _getDate($date){
	    
		if(is_numeric($date)){
		       
            $date=substr($date,0,4).'-'.substr($date,4,2).'-'.substr($date,6,2);
		    return $date;
		}

		return false;
	}

    //解析link字段的信息,tpl_flv => sec_15
	private function _getDataFromLink($link){
	      if(empty($link)){return array();}
		  $arr=explode('#',$link);
		  $result=array();
		  foreach($arr  as $val){
			   if($val=='tpl_flv'){
                   $result['sec']=15;
				   continue;
				}

		        $rs=explode('_',$val);
			    $result[$rs[0]]=$rs[1];
		  }

		  return $result;
	}


    //从api获取对应的数据
	private function _getApi($params){
	
		  //$url = "http://mads.56.com/admin/api.search.php" ;// 数据接口,搜索
          //$hostname = $_SERVER[HTTP_HOST] == "t3.56.com"?$_SERVER[HTTP_HOST]:"mads.56.com";
	      $url = "http://" . HOSTNAME . "/admin/api.search.php" ;// 数据接口,搜索
		  $mday=getdate();
		  $api_key = md5("acs.56.com".$mday['mday']);
		  $params['api_key']=$api_key;
		  $params['type']='';
		  
		  $bt=$params['f_date'];
		  $et=$params['t_date'];

		  if( $params['search_id'] ){//如果查询searc_id,去掉时间限制 ，vid必须要有时间否则超时
		      unset($params['f_date'],$params['t_date']);   
		  }

		  $url.='?'.http_build_query($params); 
		  $rs=file_get_contents($url);
		  $rs=json_decode($rs,true);
         
		  if(is_array($rs)  && $rs ){
		        
				$bt=str_replace('-','',$bt);
				$et=str_replace('-','',$et);

				foreach($rs as $k=>& $v){
					  //重新定义区域
					  $v['zone']=$v['area'].$v['city'];
                      $play=$this->_getDataFromLink($v['link']);
					  $v['play_time']=isset($play['time'])?(int)$play['time']:0;
					  $v['play_level']=isset($play['level'])?(int)$play['level']:0;
					  $v['play_ad_time']=isset($play['sec'])?(int)$play['sec']:0;
					  $v['freq_time']=isset($play['freq'])?($play['freq']/2880):0; //换算成日

					  $arr=array();
					  $arr['api_key']=$api_key;
				      $arr['aid']=(int)$v['aid'];
					  $arr['f_date']=$bt;
					  $arr['t_date']=$et;
					  
					  //$url = "http://mads.56.com/admin/api.data.php" ;// 数据接口
                      //$hostname = $_SERVER[HTTP_HOST] == "t3.56.com"?$_SERVER[HTTP_HOST]:"mads.56.com";
					  $url = "http://" . HOSTNAME. "/admin/api.data.php" ;// 数据接口
					  $rss=file_get_contents($url."?".http_build_query($arr));
									 
					  $rss=$rss?json_decode($rss,true):array();
					  					
					 
					  if( is_array($rss) && $rss){
									   
			 
						   $data=array();
						   					      

						   if($rss){
							   
						        foreach($rss as $_v){

									  $data['pv'][$_v['atime']]=$_v['view'];
									  $data['click'][$_v['atime']]=$_v['click'];
							          $atime=explode('-',$_v['atime']);
									  $atime=$atime[0].str_pad($atime[1],2,'0',STR_PAD_LEFT).str_pad($atime[2],2,'0',STR_PAD_LEFT);
							          $this->_getDays[$atime]=$_v['atime']; //搜集所有的date
							     }
						   }
					       $rs[$k]['data']=$data;
					  }
				}
		  }
		  return $rs;
	}

 	

	//第二张报表
	
    public function Run2(){
	    include template('ad_report2','others');
	}

    //写任务
    public function addRpt(){
	     $bt=$_REQUEST['bt']?trim($_REQUEST['bt']):'';
		 $et=$_REQUEST['et']?trim($_REQUEST['et']):'';
		 if(empty($bt) || empty($et)){
		    exit('the date error  format :20121201');
		 }
		 //把任务加入到日志文件
		 $title=$bt.'_'.$et;
		 file_put_contents(ROOT_DIR.'rpt_tmp/rpt_todo.txt',$title."\n",FILE_APPEND);
		 echo 'add the task['.$title.'] for search area report please ,check the area_report_list';
	}

    //查看列表，可以点击下载
	public function checkRptList(){
		 $file=ROOT_DIR.'rpt_tmp/rpt_done.txt';
		 
	     if(!file_exists($file)){
		     die('no rpt_done.txt');
		 }	
	
	     $rows=file($file);
		
		 if(!is_array($rows) || empty($rows)){die('no data');}

         $html="<ul style='float:left'>";
		 //$web="http://backend.v.56.com/adminv5/list/rpt_tmp/";
		 $web="/admin3/rpt_tmp/";
		 $rows=array_reverse($rows);
		 //echo "<pre>";var_dump($rows);
         foreach($rows as $row){
			  $row=trim($row);
			  if(empty($row)){continue;}
		      $link=$web.$row.'.html';
		      $html.="<li><a href='{$link}'>".$row."</a></li>";
		 }

		 $html.="</ul>";
		 echo $html;
	
	}

	public function report2(){
		
		    set_time_limit(0);

			$_bt=$_REQUEST['bt']?$_REQUEST['bt']:'';
			$_et=$_REQUEST['et']?$_REQUEST['et']:'';
			$vid=$_REQUEST['vid']?trim($_REQUEST['vid']):'';
			$aid=$_REQUEST['aid']?trim($_REQUEST['aid']):'';
            
			$diff_date=(strtotime($_et)-strtotime($_bt))/86400;

            if( empty($_bt) || empty($_et)){
			     die("<script>alert('时间参数错误,请检查,格式如20121212');history.go(-1);</script>");
			}
			
           $p=array(
			    'f_date'=>$_bt,
			    't_date'=>$_et
		   );

           $rst=array();
		   
		   $this->_getApi2($p);
		   $rst=is_array($rst)?$rst:array();
		   		   

		   $days=$this->_getDays;  //获取收集到的days，只有key有效
		   ksort($days);			  //把key顺序排下
		
		  //模板页赋值
		  Core::$vars['ads']=$this->_getAdsList;
		  Core::$vars['rst']=$this->_getReport;
		  Core::$vars['days']=$days;
         
		  unset($this->_getAdsList,$this->_getReport);//清理大数组
		  
		   ob_start();
		   include template('result_report2','others');
		   $data=ob_get_clean();
		   ob_end_flush();
		   $filename=$_bt.'_'.$_et;
		   file_put_contents(ROOT_DIR.'/rpt_tmp/'.$filename.'.html',$data);
		   echo 'ok';
	}

	//从api2获取对应的数据,为报表二准备的逻辑
	private function _getApi2($params){
	
	      //$url = "http://mads.56.com/admin/api.search.php" ;// 数据接口,搜索
          //$hostname = $_SERVER[HTTP_HOST] == "t3.56.com"?$_SERVER[HTTP_HOST]:"mads.56.com";
	      $url = "http://" . HOSTNAME . "/admin/api.search.php" ;// 数据接口,搜索
		  $mday=getdate();
		  $api_key = md5("acs.56.com".$mday['mday']);
		  $params['api_key']=$api_key;
		  $params['type']='';
		  
		  $bt=$params['f_date'];
		  $et=$params['t_date'];
 
		  $url.='?'.http_build_query($params); 
		  $rs=file_get_contents($url);
		  $rs=json_decode($rs,true);
         
		  if(is_array($rs)  && $rs ){
		        
				$bt=str_replace('-','',$bt);
				$et=str_replace('-','',$et);

				foreach($rs as $k=>& $v){
					  //重新定义区域
					  $v['zone']=$v['area'].$v['city'];
                      $play=$this->_getDataFromLink($v['link']);
					  $v['play_time']=isset($play['time'])?(int)$play['time']:0;
					  $v['play_level']=isset($play['level'])?(int)$play['level']:0;
					  $v['play_ad_time']=isset($play['sec'])?(int)$play['sec']:0;
					  $v['freq_time']=isset($play['freq'])?($play['freq']/2880):0; //换算成日
					  //广告集合 aid=>info
					  $this->_getAdsList[$v['aid']]=$v;

					  $arr=array();
					  $arr['api_key']=$api_key;
				      $arr['aid']=(int)$v['aid'];
					  $arr['f_date']=$bt;
					  $arr['t_date']=$et;
					  
					  //$url = "http://mads.56.com/admin/api.data.php" ;// 数据接口
                      $hostname = $_SERVER[HTTP_HOST] == "t3.56.com"?$_SERVER[HTTP_HOST]:"mads.56.com";
					  $url = "http://" . $hostname . "/admin/api.data.php" ;// 数据接口
					  $rss=file_get_contents($url."?".http_build_query($arr));
									 
					  $rss=$rss?json_decode($rss,true):array();
					  					
					 
					  if( is_array($rss) && $rss){
						   
							   
						        foreach($rss as $_v){
									  $this->_getReport[$v['zone']?$v['zone']:'---'][$v['channel']]['pv'][$v['aid']][$_v['atime']]+=$_v['view'];
									  $this->_getReport[$v['zone']?$v['zone']:'---'][$v['channel']]['click'][$v['aid']][$_v['atime']]+=$_v['click'];
									
									  //补充时间
							          $atime=explode('-',$_v['atime']);
									  $atime=$atime[0].str_pad($atime[1],2,'0',STR_PAD_LEFT).str_pad($atime[2],2,'0',STR_PAD_LEFT);
							          $this->_getDays[$atime]=$_v['atime']; //搜集所有的date
									  
							     }
					  }
				}
		  }
				unset($rs);//清理rs
	}
}