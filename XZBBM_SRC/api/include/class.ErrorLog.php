<?php
if(!defined('IN_SYS')) {
	header("HTTP/1.1 404 Not Found");
	die;
}

Class ErrorLog {

	static function Main($log){
	    
	    $mail = new PHPMailer();
	    $mail->IsHTML(true);
	    $mail->IsSMTP();
	    $mail->CharSet='UTF-8';
	    $mail->Host = "smtp.ym.163.com";
	    $mail->SMTPAuth = true;
	    $mail->Username = 'error_report@xzbbm.cn';
	    $mail->Password = "6JYctyplbN";
	    $mail->From = 'error_report@xzbbm.cn';
	    $mail->FromName = "学长帮帮忙报错邮件";
	    
	    //$adresses = array('wzz@xzbbm.cn','chenliang@xzbbm.cn','miracle@xzbbm.cn','815305658@qq.com','410180260@qq.com','599017308@qq.com');
	    $adresses = array('815305658@qq.com','410180260@qq.com','599017308@qq.com');
	    
	    foreach($adresses as $data){ //批量添加收信人
	        $mail->AddAddress($data, "");
	    }
	    
	    $mail->Subject = "学长帮帮忙错误日志反馈 - ".date('Y-m-d H:i:s',TIMESTAMP);
	    $mail->Body = $log;
	    if(!$mail->Send()){
	        return 0;
	    }else{
	        return 1;
	    }
	}
}
