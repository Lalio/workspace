<?php
/**
 * @name	class.MadsService.php
 * @todo 	广告系统服务类
 * @version 2012-10-30
 * @author  bo.wang3 
 */
Class MadsService{
	
	protected $_db; //mysql数据库句柄
	protected $_db_t3; //mysql数据库句柄
	protected $_db_slave; //mysql数据库句柄
	protected $_re; //redis数据库句柄
	protected $_dc; //数据缓存操作句柄
	
	/**
	 * @todo 构造函数 初始化相关数据
	 * @author bo.wang3
	 * @version 2012-10-30
	 */
	function __construct(){
		 
		Core::InitDb();
		$this->_db = Core::$db['local'];  //正式机
		$this->_db_t3 = Core::$db['t3'];  //t3机
		$this->_db_slave = Core::$db['mads_slave']; //从库
	
		$this->_re = new RedisDb(Core::$configs ['redis']['master']); //redis数据库
	
	}
	
	/**
	 * @todo 获取数据 memCache->Database
	 * @author bo.wang3
	 * @param $sql SQL查询命令
	 * @version 2012-11-01
	 */
	public function GetData($sql){
	
		$data = array();
		Core::InitDb();
		$data = $this->_db->dataArray($sql);
	
		return $data;
	}

	/**
	 * @todo 普通文件入数据库，好处在于三机备份，重要数据便于回滚
	 * @author bo.wang3
	 * @version 2014-08-04 14:29
	 * @param file_path 文件原始路径  useful 文件用途  file_name 文件名  index 文件索引
	 * @return 操作成功后返回一个标示该文件的数据库记录id
	 */
	public function PutFileIntoDb($file_path,$useful,$file_name,$index,$file_extension = 'xls'){
	
		//重定位文件，便于Mysql获得操作文件的权限
		if(false === move_uploaded_file($file_path , TMPFILE)){
			copy($file_path , TMPFILE);
		}
	
		$fid = $this->_db->conn("INSERT INTO ad_document VALUES('','$useful','$file_name','$file_extension',".filesize(TMPFILE)." , '".filetype(TMPFILE)."' ,LOAD_FILE('".TMPFILE."'),$index)");
	
		if(false === $fid){
			echo "INSERT INTO ad_document VALUES('','$useful','$file_name','$file_extension',".filesize(TMPFILE)." , '".filetype(TMPFILE)."' ,LOAD_FILE('".TMPFILE."'),$index)";
		}else{
			return $fid;
		}
		
		unlink(TMPFILE);
	}
	
	/**
	 * @todo 邮件发送
	 * @author bo.wang3
	 * @version 2013-7-31 14:29
	 * @param  topic 邮件主题
	 * @param  message 邮件正文
	 * @param  to 收件人
	 * @param  $rid 预订单编号 用于判断抄送给相关销售人员
	 */
	public function PushEmail($topic,$body,$to,$rid = '',$cc = 'bo.wang3,junfeng.deng'){
	
		$mail = new PHPMailer();
		$mail->CharSet="UTF-8";
		$mail->Host = HOSTNAME;
		$mail->IsHTML(true);
		 
		if(HOSTNAME == 't3.56.com'){ //测试系统不群发邮件
			return false;
		}
	
		$mail->From = "mads@renren-inc.com";
		$mail->FromName = "56网广告系统";
	
		$subject = " [56网广告系统] - ".$topic;
		$mail->Subject = $subject;
		$mail->Body = $body.'<br><hr>Mads_System( '.HOSTNAME.' )：'.date('Y-m-d H:i:s',TIMESTAMP).'   <br />如有任何疑问请与广告系统开发组同事联系，请勿直接回复本邮件。';
	
		if(strstr($to,'@renren-inc.com')){
			$mail->AddAddress($to, "");
		}else{
			$mail->AddAddress($to."@renren-inc.com", "");
		}
	
		$mail->AddAddress("bo.wang3@renren-inc.com", "");
		 
		if($rid != ''){
			//各个环节通知对应的销售人员
			$rsv = $this->_db->rsArray('SELECT dcs,dcs_leader,cs,cs_leader FROM ad_reserve WHERE id = '.$rid);
			 
			if(!empty($this->sales[$rsv[dcs]])){
				$mail->AddCC($this->sales[$rsv[dcs]]."@renren-inc.com", $name = $this->sales[$rsv[dcs]]);
			}
			if(!empty($this->sales[$rsv[dcs_leader]])){
				$mail->AddCC($this->sales[$rsv[dcs_leader]]."@renren-inc.com", $name = $this->sales[$rsv[dcs_leader]]);
			}
			if(!empty($this->sales[$rsv[cs]])){
				$mail->AddCC($this->sales[$rsv[cs]]."@renren-inc.com", $name = $this->sales[$rsv[cs]]);
			}
			if(!empty($this->sales[$rsv[cs_leader]])){
				$mail->AddCC($this->sales[$rsv[cs_leader]]."@renren-inc.com", $name = $this->sales[$rsv[cs_leader]]);
			}
		}
		 
		if($cc){
			$cc = explode(',', $cc);
			foreach($cc as $v){
				$mail->AddCC("$v@renren-inc.com", $name = $v);
			}
		}
		 
		if(!$mail->Send()){
			return false;
		}
		return true;
	}
	
	/**
	 * @todo URL有效性探测
	 * @author bo.wang3
	 * @version 2013-6-3 14:29
	 */
	public function UrlValidity($m_url){
	
		if(!empty($m_url)){
				
			$validstatue = array(  //有效的返回状态
					"HTTP/1.1 200 OK",
					"HTTP/1.0 200 OK",
					"HTTP/1.1 302 Found",
					"HTTP/1.0 302 Found"
			);
			$rs = get_headers($m_url);
			if(false == $rs || !in_array($rs[0], $validstatue)){
				return $m_url." 对应的资源可能不存在($rs[0])，请重新检查。";
			}else{
				return true;
			}
		}
	}
	
	/**
	 * @todo 按照特定需求输出标准Excel文件
	 * @author bo.wang3
	 * @version 2014-8-29 14:29
	 */
	public function BuildExcelFile($input){
	
		$objPHPExcel = new PHPExcel();
		
		$objPHPExcel->getProperties()->setCreator('Maarten Balliauw');
		$objPHPExcel->getProperties()->setLastModifiedBy('Maarten Balliauw');
		$objPHPExcel->getProperties()->setTitle('Office 2007 XLSX Test Document');
		$objPHPExcel->getProperties()->setSubject('Office 2007 XLSX Test Document');
		$objPHPExcel->getProperties()->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.');
		$objPHPExcel->getProperties()->setKeywords('office 2007 openxml php');
		$objPHPExcel->getProperties()->setCategory('Test result file');
		
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Hello');
		$objPHPExcel->getActiveSheet()->setCellValue('B2', 'world!');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Hello');
		$objPHPExcel->getActiveSheet()->setCellValue('D2', 'world!');

		$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
		
	}
	
}