<?php
//============================================================+
// File name   : example_001.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 001 for TCPDF class
//               Default Header and Footer
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Default Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

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
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

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

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('example_001.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
