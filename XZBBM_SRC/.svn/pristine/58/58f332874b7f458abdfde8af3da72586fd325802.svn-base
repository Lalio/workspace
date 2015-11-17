<?php
if (! defined ( 'IN_SYS' ))
{
    header ( "HTTP/1.1 404 Not Found" );
    die ();
}
class PrintAPI extends Xzbbm
{
    /**
     * 打印接口
     */
    public function GetPrintInfo()
    {
        /*
         * $this->Ret ( [ "FileName" => "FileNameFileNameFileName", "UserName" => "xiaomengmeng", "UserPhone" => "12312344", "DownloadUrl" => "http://oss.xzbbm.cn/85689388d2495126389969e3b47dadec.pdf", "TipsID" => 1111, "PagesFrom" => 1, "PagesTo" => 10, "Copies" => 2, "Dulpex" => 0, "Color" => 0 ] ); exit;
         */

        $where = isset($_REQUEST['order_id'])?"order_id = '{$_REQUEST[order_id]}'":'cloudprint_task.state = 0';
    	
        $rs = $this->_db->rsArray ( "SELECT order_id,store_id,pages,total,duplex,file_name,file_real_name,phone,from_unixtime(ts) as ts,cloudprint_task.price 
							  		 FROM cloudprint_task,pd_users,pd_files 
							  		 WHERE cloudprint_task.state = 0
							  		 AND pd_users.userid = cloudprint_task.userid 
							  		 AND pd_files.file_id = cloudprint_task.file_id 
							  		 LIMIT 0,1" ); // 取最早提交的一个打印任务
        
        if (empty ( $rs ))
        {
            exit ();
        }
        else
        {
            // 改变状态
            $this->_db->update ( 'cloudprint_task', array (
                    'state' => 1 
            ), "order_id = '{$rs[order_id]}'" );
            
            $rs ['phone'] = substr_replace($rs ['phone'],'****',3,4);;
            $rs ['file_name'] = $rs ['file_name'];
            $rs ['pages'] = intval ( $rs ['pages'] );
            $rs ['total'] = intval ( $rs ['total'] );
            $rs ['duplex'] = intval ( $rs ['duplex'] );
            
            $rs ['file_url'] = $this->GetUrl ( array (
                    'file_real_name' => $rs ['file_real_name'],
                    'timeout' => 15,
                    'page' => 1,
                    'file_extension' => 'pdf' 
            ) );
            
            $params = array(
            		'phone' => $rs['phone'],
            		'order_id' => $rs['order_id'],
            		'file_name' => $rs['file_name'],
            		'ts' => $rs['ts'],
            		'pages' => $rs['pages'],
            		'total' => $rs ['total'],
            		'total_price' => sprintf("%.2f",$rs['pages']*$rs['price']),
            		'duplex' => $rs['duplex'] == 1? '单面':'双面'
            		);
            
            $rs ['title_url'] = "https://api.xzbbm.cn/?action=PrintAPI&do=Cover&".http_build_query($params);
            
            $this->Ret ( $rs );
        }
    }
    
    public function UpdatePrint()
    {
        $order_id = $_REQUEST['order_id'];
        $state = $_REQUEST['state'];
        $rs = $this->_db->rsArray ( "SELECT file_name, cloudprint_task.userid
							  		 FROM cloudprint_task,pd_files
							  		 WHERE pd_files.file_id = cloudprint_task.file_id
                                     AND cloudprint_task.order_id = $order_id
							  		 LIMIT 0,1" );
        $userid = $rs['userid']; 
        if($userid > 0)
        {
            if($state == 0){
            	$title = "资料已经开始打印！";
            	$content = "《{$rs['file_name']}》";
            }else{
            	$title = "资料打印完成！";
            	$content = "亲，请您及时领取并扫码确认收件";
            }
                
            $this->PushAccount([$userid], $title, $content);
            $this->ok();
        }else {
            $this->Error("订单无效");
        }
    }
    
    /**
     * 商家收益接口
     */
    public function GetProfile()
    {
    	$mctoken = trim($_REQUEST['mctoken']);
    	
    	$sl = $this->_db->dataArray ( "SELECT *
    								   FROM cloudprint_merchant,cloudprint_task
    								   WHERE mctoken = '$mctoken'
    								   AND cloudprint_task.store_id = cloudprint_merchant.id
    								   AND state = 1" ); // 取最早提交的一个打印任务
    	
    	
    	foreach($sl as $k => $v){
    		$rs['total_profile'] += (float)$v['price'];
    		if($v['ts'] > strtotime(date('Y-m-d',TIMESTAMP))){
    			$rs['today_pages'] += (float)$v['total']*(float)$v['pages'];
    			$rs['today_profile'] += (float)$v['price'];
    		}
    	}
    	
    	$rs['total_profile'] = "￥ ".sprintf("%.2f",$rs['total_profile']);
    	$rs['today_profile'] = "￥ ".sprintf("%.2f",$rs['today_profile']);
    	$rs['today_pages'] = $rs['today_pages'] == 0?'0页':(string)$rs['today_pages']."页";
    	$rs['rank'] = '34';
    	$rs['date'] = date('Y/m/d');
    	
    	$this->Ret ( $rs );
    }
    
    
    /**
     * 客户端语音提示接口
     */
    public function RecieveMusic()
    {
    	
    	$url = $this->GetUrl ( array (
    			'file_real_name' => 'a4600e16d296dcaae9d3763bb060a360',
    			'timeout' => 15,
    			'page' => 1,
    			'file_extension' => 'wav'
    	) );
    	
    	header("Location:".$url);
   
    }
    
    /**
     * 客户端升级
     */
    public function UpdateVersion()
    {
         
        $c_version = $_REQUEST['version'];
        
        $v = array(
            'version' => '1.0.2',
            'update_url' => 'http://cdn.xzbbm.cn/usericons/default6.png'
        );
         
        if($c_version != $v['version']){
            echo json_encode($v);
        }
         
    }
    
    
    /**
     * 客户端通用封面接口
     */
    public function Cover()
    {
    	
    	// create new PDF document
    	$pdf = new TcPdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    	
    	// set document information
    	$pdf->SetCreator(PDF_CREATOR);
    	$pdf->SetAuthor('Nicola Asuni');
    	$pdf->SetTitle('TCPDF Example 001');
    	$pdf->SetSubject('TCPDF Tutorial');
    	$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
    	
    	// set default header data
    	//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
    	//$pdf->setFooterData(array(0,64,0), array(0,64,128));
    	
    	// set header and footer fonts
    	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    	
    	// set default monospaced font
    	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    	
    	// set margins
    	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    	
    	// set auto page breaks
    	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    	
    	// set image scale factor
    	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    	
    	// set some language-dependent strings (optional)
    	//if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    		//require_once(dirname(__FILE__).'/lang/eng.php');
    		//$pdf->setLanguageArray($l);
    	//}
    	
    	// ---------------------------------------------------------
    	
    	// set default font subsetting mode
    	$pdf->setFontSubsetting(true);
    	
    	// Set font
    	// dejavusans is a UTF-8 Unicode font, if you only need to
    	// print standard ASCII chars, you can use core fonts like
    	// helvetica or times to reduce file size.
    	$pdf->SetFont('cid0cs');
    	
    	// Add a page
    	// This method has several options, check the source code documentation for more information.
    	$pdf->AddPage();
    	
    	// set text shadow effect
    	$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.1, 'depth_h'=>0.1, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
    	
    	$print_time = date('Y-m-d H:i:s');
    	
    	// Set some content to print
    	$html = <<<EOD
<h1>学长帮帮忙云印订单&nbsp;&nbsp;&nbsp;No&nbsp;$_REQUEST[order_id]</h1>
<h2><font style="font-size: 35px">$_REQUEST[file_name]</font></h2>
<p><font style="font-size: 15px">资料页数：$_REQUEST[pages]</font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<font style="font-size: 15px">打印份数：$_REQUEST[total]</font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<font style="font-size: 15px">单/双面：$_REQUEST[duplex]</font></p>
<p><font style="font-size: 18px">下单时间：$_REQUEST[ts]</font></p>
<p><font style="font-size: 18px">打印时间：$print_time</font></p>
<h1><font style="font-size: 30px">订单总价：<font color="red"><strong>￥$_REQUEST[total_price]</strong></font></font></h1>
<font color="#0092DC" style="font-size: 100px; text-align:left;"><b>$_REQUEST[phone]</b></font></p>
<p>--------------------------------------折-------叠-------线--------------------------------------</p>
<font color="#0092DC" style="font-size: 100px; text-align:left;"><b>$_REQUEST[phone]</b></font></p>
<p style="text-align:center;"><img id="erm_small" src="https://xzbbm.cn/?action=QrCodes&do=GcQr&size=180&fid=178775" width="125" height="125"></p>
<p style="text-align:center;"><font style="font-size: 10px">商家信息：大学城广东工业大学图书馆负一层打印店（100001） 联系电话（020-39023387）</font></p>
<p style="text-align:center;"><font style="font-size: 10px">专注校内知识分享&nbsp;&nbsp; WWW.XZBBM.CN</font></p>
EOD;
    	
    	// Print text using writeHTMLCell()
    	$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    	// ---------------------------------------------------------
    	// Close and output PDF document
    	// This method has several options, check the source code documentation for more information.
    	$pdf->Output('example_001.pdf', 'I');
    	
    	//============================================================+
    	// END OF FILE
    	//============================================================+
    	 
    }
    
    
    /**
     * 压缩包转化为图片
     */
    public function Zip2Png()
    {

    	// create new PDF document
    	$pdf = new TcPdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    	
    	// set document information
    	$pdf->SetCreator(PDF_CREATOR);
    	$pdf->SetAuthor('XZBBM');
    	$pdf->SetTitle('www.xzbbm.cn');
    	$pdf->SetSubject('www.xzbbm.cn');
    	$pdf->SetKeywords('XZBBM');
    	
    	// set default header data
    	//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
    	//$pdf->setFooterData(array(0,64,0), array(0,64,128));
    	
    	// set header and footer fonts
    	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    	
    	// set default monospaced font
    	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    	
    	// set margins
    	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    	
    	// set auto page breaks
    	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    	
    	// set image scale factor
    	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    	
    	// set some language-dependent strings (optional)
    	//if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    		//require_once(dirname(__FILE__).'/lang/eng.php');
    		//$pdf->setLanguageArray($l);
    	//}
    	
    	// ---------------------------------------------------------
    	
    	// set default font subsetting mode
    	$pdf->setFontSubsetting(true);
    	
    	// Set font
    	// dejavusans is a UTF-8 Unicode font, if you only need to
    	// print standard ASCII chars, you can use core fonts like
    	// helvetica or times to reduce file size.
    	$pdf->SetFont('cid0cs');
    	
    	// Add a page
    	// This method has several options, check the source code documentation for more information.
    	$pdf->AddPage();
    	
    	// set text shadow effect
    	$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.1, 'depth_h'=>0.1, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
    	
    	
    	/*
    	 // Set some content to print
    	$html = <<<EOD
    	<p>
    	<img width="60px" height="60px" src="http://pic.sosucai.com/b/2010_09/b_48161_cdr_20100909093637.jpg">
    	<font color="#0092DC" style="font-size: 100px; text-align:left;"><b>$_REQUEST[phone]</b></font></p>
    	<h1>学长帮帮忙云印订单&nbsp;&nbsp;&nbsp;No$_REQUEST[order_id]</h1>
    	<h1></h1>
    	<h2><font style="font-size: 40px">$_REQUEST[file_name]</font></h2>
    	<p><font style="font-size: 25px">下单时间：$_REQUEST[ts]</font></p>
    	<p><font style="font-size: 25px">资料页数：$_REQUEST[pages]</font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    	<font style="font-size: 25px">打印份数：$_REQUEST[total]</font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    	<font style="font-size: 25px">打印规格：$_REQUEST[duplex]</font></p>
    	<p><font style="font-size: 25px">订单总价：<font color="red"><strong>￥$_REQUEST[total_price]</strong></font></font></p>
    	<p></p>
    	<p></p>
    	<p style="text-align:center;"><img id="erm_small" src="http://www.xzbbm.cn/?action=QrCodes&do=GcQr&size=180&fid=178775" width="100" height="100"></p>
    	<p style="text-align:center;"><font style="font-size: 20px"><img width="30px" height="30px" src="http://pic.sucaibar.com/pic/201307/18/4ff535ba8b.png">&nbsp;&nbsp;已通过支付宝支付打印费用，请扫描上方二维码确认收货</font></p>
    	<p><font style="font-size: 15px">
    	商家信息：大学城广东工业大学图书馆负一层打印店（100001） 联系电话（020-39023387）</font></p>
    	<p></p>
    	<p></p>
    	<p></p>
    	<p>
    	<img width="60px" height="60px" src="http://pic.sosucai.com/b/2010_09/b_48161_cdr_20100909093637.jpg">
    	<font color="#0092DC" style="font-size: 100px; text-align:left;"><b>$_REQUEST[phone]</b></font></p>
    	EOD;
    	*/
    	
    	$rs = $this->GetData("SELECT file_real_name,file_size,file_extension FROM pd_files WHERE file_index = '$_REQUEST[file_index]'");
    	$rs = $rs[0];
    	
    	if($rs['file_extension'] == 'rar'){
    		$file_path = get_object ( $this->_oss, "$rs[file_real_name].rar");
    		$html = getrarinfo($file_path,$rs['file_size']);
    	}else{
    		$file_path = get_object ( $this->_oss, "$rs[file_real_name].zip");
    		$html = getzipinfo($file_path,$rs['file_size']);
    	}
    	
    	// Print text using writeHTMLCell()
    	$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    	
    	// ---------------------------------------------------------
    	
    	// Close and output PDF document
    	// This method has several options, check the source code documentation for more information.
    	$pdf->Output('example_001.pdf', 'I');
    	
    	//============================================================+
    	// END OF FILE
    	//============================================================+
    
    }
    
    /**
     * @todo 抽取关键信息
     * @author bo.wang
     * @version 2013-09-18 14:29
     */
    public function ConvertTxt() {
    
        $str = file_get_contents(get_object($this->_oss,$_REQUEST['file_real_name'].'.txt'));
        $rs = $this->_db->update('pd_files',array('file_description' => trim($str),'has_text' => 1),"file_id = ".$_REQUEST['file_id']);
        
        if(false !== $rs){
            echo 'Insert OK!';
        }
    }
    
    /**
     * @todo 商家登陆
     * @author bo.wang
     * @version 2013-09-18 14:29
     */
    public function MerChantLogin() {
        
        $account = trim($_REQUEST['account']);
        $password = trim($_REQUEST['password']);
    
        $rs = $this->_db->rsArray("SELECT mctoken,title,geo_universities.name as uname,phone
                                   FROM cloudprint_merchant,geo_universities
                                   WHERE (account = '$account' AND password = '$password')
                                   AND geo_universities.university_id = cloudprint_merchant.ucode");
        
        if(!empty($rs)){
            $rs['rcode'] = 0;
            echo json_encode($rs);
        }else{
            $rs['rcode'] = 1;
            echo json_encode($rs);
        }
    }
     
}