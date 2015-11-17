<?php
if ( !defined( 'IN_SYS' ) ) {
	header( "HTTP/1.1 404 Not Found" );
	die;
}

/**
 * @todo 全站二维码生成接口
 * @author bo.wang
 * @version 2013-03-12 14:29
 */
class QrCodes extends Xzbbm{
    
    public function __construct(){
        
    	parent::__construct();
    	//header("Content-type:image/png;");
    }
    
    public function GcQr(){
    
    	$fid = trim($_REQUEST[fid]);
    	
    	$fg = '#2088A5';
    	$bg = '#FFFFFF';
    	$pt = '#2088A5';
    	$inpt = '#2088A5';
    	$size = $_REQUEST['size']?$_REQUEST['size']:'180';
    	$style = $_REQUEST['style']?$_REQUEST['style']:1;
    	$level = "Q"; //L\M\Q\H
    	//$filelogo='https://xzbbm.cn/img/44logo.png';
    	$filebg='';
    	
    	if(!empty($fid)){
    		$rs = $this->GetData('SELECT file_index,file_store_path,file_key FROM pd_files WHERE file_id = '.$fid);
    		$dir = make_store_dir('/data/stores/cdn/qrcodes',$rs[0]['file_store_path']);
    		$path = $dir."/{$rs[0]['file_index']}-$size-$style-$level.png";
    	}
    	 
    	$data = $fid?"https://xzbbm.cn/".$rs[0]['file_key']:$_REQUEST['str'];

    	if(!file_exists($path)){
    		
    		$z = new QrcodeImg;
    		$z->set_qrcode_error_correct($level);   # set ecc level H
    		$z->qrcode_image_out($data,$path,$size,$filelogo,$filebg,$pt,$inpt,$fg,$bg,'#000000',$style);
    	}
    	
    
    	header("Location:http://cdn.xzbbm.cn/qrcodes/{$rs[0]['file_store_path']}/{$rs[0]['file_index']}-$size-$style-$level.png");
    
    /*
    	$fid = $_REQUEST['fid']?$_REQUEST['fid']:0;
    	$size = $_REQUEST['size']?$_REQUEST['size']:'180';
    	$level = $_REQUEST['level']?$_REQUEST['level']:"M"; //L\M\Q\H
    
    	$rs = $this->GetData('SELECT file_index,file_store_path FROM pd_files WHERE file_id = '.$fid);
    	$dir = make_store_dir('/data/stores/cdn/qrcodes',$rs[0]['file_store_path']);
    	$path = $dir."/{$rs[0]['file_index']}-$size-$level.png";
    		
    	while(!file_exists($path) || filesize($path) == 0){
    			
    		$para = array(
    				'bg'   => 'FFFFFF',
    				//'fg'   => '0066CC',
    				'fg'   => '404146',
    				'gc'   => '494A50',
    				//'gc'   => '56AAFE',
    				'el'   => $level,
    				'w'    => $size,
    				'm'    => '3',
    				'pt'   => '539A04',
    				'inpt' => '539A04',
    				'logo' => 'http://cdn.xzbbm.cn/web/images/xzbbm_ico.jpg',
    				'text' => $fid?"http://www.xzbbm.cn/?do=V&file_id=$fid&download":"http://xzbbm.cn/app"
    		);
    			 
    		$para_str  = http_build_query($para);
    			 
    		$opts = array(
    				'http'=>array(
    						'method'=>"GET",
    						'timeout'=>3,
    				)
    		);
    		$context = stream_context_create($opts);
    		$data = file_get_contents("http://qr.liantu.com/api.php?".$para_str , false , $context);

    		file_put_contents($path, $data);
    	}
    		
    	header("Location:http://cdn.xzbbm.cn/qrcodes/{$rs[0]['file_store_path']}/{$rs[0]['file_index']}-$size-$level.png");
    */
    }

	/**
	 * liantu API 二维码生成 - QRcode可以存储最多4296个字母数字类型的任意文本，具体可以查看二维码数据格式
	 *
	 * bg	背景颜色	bg=颜色代码，例如：bg=ffffff
	 * fg	前景颜色	fg=颜色代码，例如：fg=cc0000
	 * gc	渐变颜色	gc=颜色代码，例如：gc=cc00000
	 * el	纠错等级	el可用值：h\q\m\l，例如：el=h
	 * w	尺寸大小	w=数值（像素），例如：w=300
	 * m	静区（外边距）	m=数值（像素），例如：m=30
	 * pt	定位点颜色（外框）	pt=颜色代码，例如：pt=00ff00
	 * inpt	定位点颜色（内点）	inpt=颜色代码，例如：inpt=000000
	 * logo	logo图片	logo=图片地址，例如：logo=http://www.liantu.com/imaegs/2013/sample.jpg
	 */
	public function Liantu( $file_id, $size ='180', $level='m' ) {
		 
	    $text = $_REQUEST['str'];
	    
		$para = array(
	            'bg'   => 'FFFFFF',
	            //'fg'   => '0066CC',
	            'fg'   => '404146',
	            'gc'   => '494A50',
	            //'gc'   => '56AAFE',
	            'el'   => $level,
	            'w'    => $size,
	            'm'    => '3',
	            'pt'   => '539A04',
	            'inpt' => '539A04',
	            'logo' => 'http://cdn.xzbbm.cn/web/images/xzbbm_ico.jpg',
	            'text' => $text
	    );
	    
	    $para_str  = http_build_query($para);

	    $opts = array(
	    		'http'=>array(
	    			'method'=>"GET",
	    			'timeout'=>110,
	    		)
	    );
    	$context = stream_context_create($opts);
        $rs = file_get_contents("http://qr.liantu.com/api.php?".$para_str , false , $context);
	    
		echo $rs;
	}
	
	public function LiantuBig( $chl='', $size ='510', $level='m' ) {
		 
		$para = array(
				'bg'   => 'FFFFFF',
				//'fg'   => '0066CC',
				'fg'   => '404146',
				'gc'   => '494A50',
				//'gc'   => '56AAFE',
				'el'   => $level,
				'w'    => $size,
				'm'    => '3',
				'pt'   => '539A04',
				'inpt' => '539A04',
				'logo' => 'http://cdn.xzbbm.cn/web/images/xzbbm_ico.jpg',
				'text' => $_REQUEST[file_id]?"http://www.xzbbm.cn/?do=V&file_id=$_REQUEST[file_id]&download&from=app":"http://xzbbm.cn/app"
		);
		 
		$para_str  = http_build_query($para);
		 
		$cache_key = md5($para_str); //缓存键值
		Core::InitDataCache();
		$rs = Core::$dc->getData($cache_key,-1);
		 
		if(false === $rs || $_GET['i'] == 'c'){
			//从第三方API读
			$rs = file_get_contents("http://qr.liantu.com/api.php?".$para_str);
			//存入缓存
			Core::$dc->setData($cache_key,$rs);
		}
		
		echo $rs;
	}
	
	/**
	 * liantu API 二维码生成 - QRcode可以存储最多4296个字母数字类型的任意文本，具体可以查看二维码数据格式
	 *
	 * bg	背景颜色	bg=颜色代码，例如：bg=ffffff
	 * fg	前景颜色	fg=颜色代码，例如：fg=cc0000
	 * gc	渐变颜色	gc=颜色代码，例如：gc=cc00000
	 * el	纠错等级	el可用值：h\q\m\l，例如：el=h
	 * w	尺寸大小	w=数值（像素），例如：w=300
	 * m	静区（外边距）	m=数值（像素），例如：m=30
	 * pt	定位点颜色（外框）	pt=颜色代码，例如：pt=00ff00
	 * inpt	定位点颜色（内点）	inpt=颜色代码，例如：inpt=000000
	 * logo	logo图片	logo=图片地址，例如：logo=http://www.liantu.com/imaegs/2013/sample.jpg
	 */
	public function LiantuSpecial( $chl='', $size ='510', $level='m' ) {
		 
		$para = array(
				'bg'   => 'FFFFFF',
				//'fg'   => '0066CC',
				'fg'   => '404146',
				'gc'   => '494A50',
				//'gc'   => '56AAFE',
				'el'   => $level,
				'w'    => $size,
				'm'    => '3',
				'pt'   => '666699',
				'inpt' => 'FF0000',
				'logo' => 'http://cdn.xzbbm.cn/web/images/xzbbm_ico.jpg',
				'text' => $_REQUEST['text']
		);
		//var_dump($para);exit; 
		$para_str  = http_build_query($para);
		 
		//再从第三方API读
		$rs = Http::Post('qr.liantu.com','api.php',$para_str);
		//$rs = file_get_contents("http://qr.liantu.com/api.php?".$para_str);
		
		echo $rs;
	}
	
	//批量获取二维码，用于清洗数据
	public function MultiQr(){
		$ids = $this->_db->dataArray('SELECT file_id FROM pd_files WHERE in_recycle = 0 order by file_views desc');
		
		foreach($ids as $id){
			$file_id = $id;
		
			$para = array(
		            'bg'   => 'FFFFFF',
		            //'fg'   => '0066CC',
		            'fg'   => '404146',
		            'gc'   => '494A50',
		            //'gc'   => '56AAFE',
		            'el'   => 'm',
		            'w'    => 180,
		            'm'    => '3',
		            'pt'   => '539A04',
		            'inpt' => '539A04',
		            'logo' => 'http://cdn.xzbbm.cn/web/images/xzbbm_ico.jpg',
		            'text' => $file_id?"http://www.xzbbm.cn/?do=V&file_id=$file_id&download&from=app":"http://xzbbm.cn/app"
		    );
		    
		    $para_str  = http_build_query($para);
	
		    $cache_key = md5($para_str); //缓存键值
		    
		    Core::InitDataCache();
		    
		    $opts = array(
		    		'http'=>array(
		    				'method'=>"GET",
		    				'timeout'=>60,
		    		)
		    );
		    $context = stream_context_create($opts);
		    $rs = file_get_contents("http://qr.liantu.com/api.php?".$para_str , false , $context);

		    //存入缓存
		    Core::$dc->setData($cache_key,$rs);
		}
	}

}
