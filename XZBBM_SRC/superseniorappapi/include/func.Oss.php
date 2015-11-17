<?php
if ( ! defined( 'IN_SYS' ) ) {
	header( "HTTP/1.1 404 Not Found" );
	die ();
}
/**
 * @File name: func.Oss.php
 * @todo: 阿里云存储相关函数
 */

/**
 * Service相关操作
 */
//get_service($oss_sdk_service);

/**
 * Bucket相关操作
 */
//create_bucket($oss_sdk_service);
//delete_bucket($oss_sdk_service);
//set_bucket_acl($oss_sdk_service);
//get_bucket_acl($oss_sdk_service);

//set_bucket_logging($oss_sdk_service);
//get_bucket_logging($oss_sdk_service);
//delete_bucket_logging($oss_sdk_service);

//set_bucket_website($oss_sdk_service);
//get_bucket_website($oss_sdk_service);
//delete_bucket_website($oss_sdk_service);

/**
 * 跨域资源共享(CORS)
 */
//set_bucket_cors($oss_sdk_service);
//get_bucket_cors($oss_sdk_service);
//delete_bucket_cors($oss_sdk_service);
//options_object($oss_sdk_service);

/**
 * Object相关操作
 */
//list_object($oss_sdk_service);
//create_directory($oss_sdk_service);
//upload_by_content($oss_sdk_service);
//upload_by_file($oss_sdk_service);
//copy_object($oss_sdk_service);
//get_object_meta($oss_sdk_service);
//delete_object($oss_sdk_service);
//delete_objects($oss_sdk_service);
//get_object($oss_sdk_service);
//is_object_exist($oss_sdk_service);
//upload_by_multi_part($oss_sdk_service);
//upload_by_dir($oss_sdk_service,'/data/stores/file');
//batch_upload_file($oss_sdk_service,$dir);

/**
 * 外链url相关
 */
//$obj = '/jpg/2011/02/18/0135cbe17371ff950539cd57742233ff-0.jpg';
//get_sign_url($oss_sdk_service,$obj);

/**
 * 函数定义
 */
/*%**************************************************************************************************************%*/
// Service 相关

//获取bucket列表
function get_service($obj){
	$response = $obj->list_bucket();
	_format($response);
}

/*%**************************************************************************************************************%*/
// Bucket 相关

//创建bucket
function create_bucket($obj){

	//$acl = ALIOSS::OSS_ACL_TYPE_PRIVATE;
	$acl = ALIOSS::OSS_ACL_TYPE_PUBLIC_READ;
	//$acl = ALIOSS::OSS_ACL_TYPE_PUBLIC_READ_WRITE;

	$response = $obj->create_bucket(BUCKET,$acl);
	_format($response);
}

//删除bucket
function delete_bucket($obj){

	$response = $obj->delete_bucket('xzbbm');
	_format($response);
}

//设置bucket ACL
function set_bucket_acl($obj){

	//$acl = ALIOSS::OSS_ACL_TYPE_PRIVATE;
	//$acl = ALIOSS::OSS_ACL_TYPE_PUBLIC_READ;
	$acl = ALIOSS::OSS_ACL_TYPE_PUBLIC_READ_WRITE;

	$response = $obj->set_bucket_acl(BUCKET,$acl);
	_format($response);
}

//获取bucket ACL
function get_bucket_acl($obj){

	$options = array(
			ALIOSS::OSS_CONTENT_TYPE => 'text/xml',
	);

	$response = $obj->get_bucket_acl(BUCKET,$options);
	_format($response);
}

//设置bucket logging
function  set_bucket_logging($obj){

	$target_bucket='backet2';
	$target_prefix='test';

	$response = $obj->set_bucket_logging(BUCKET,$target_bucket,$target_prefix);
	_format($response);
}

//获取bucket logging
function  get_bucket_logging($obj){

	$response = $obj->get_bucket_logging(BUCKET);
	_format($response);
}

//删除bucket logging
function  delete_bucket_logging($obj){

	$response = $obj->delete_bucket_logging(BUCKET);
	_format($response);
}

//设置bucket website
function  set_bucket_website($obj){

	$index_document='index.html';
	$error_document='error.html';

	$response = $obj->set_bucket_website(BUCKET,$index_document,$error_document);
	_format($response);
}

//获取bucket website
function  get_bucket_website($obj){

	$response = $obj->get_bucket_website(BUCKET);
	_format($response);
}

//删除bucket website
function  delete_bucket_website($obj){

	$response = $obj->delete_bucket_website(BUCKET);
	_format($response);
}

/*%**************************************************************************************************************%*/
//跨域资源共享(CORS)

//设置bucket cors
function  set_bucket_cors($obj){

	$cors_rule[ALIOSS::OSS_CORS_ALLOWED_HEADER]=array("x-oss-test");
	$cors_rule[ALIOSS::OSS_CORS_ALLOWED_METHOD]=array("GET");
	$cors_rule[ALIOSS::OSS_CORS_ALLOWED_ORIGIN]=array("http://www.b.com");
	$cors_rule[ALIOSS::OSS_CORS_EXPOSE_HEADER]=array("x-oss-test1");
	$cors_rule[ALIOSS::OSS_CORS_MAX_AGE_SECONDS] = 10;
	$cors_rules=array($cors_rule);

	$response = $obj->set_bucket_cors(BUCKET, $cors_rules);
	_format($response);
}

//获取bucket cors
function  get_bucket_cors($obj){

	$response = $obj->get_bucket_cors(BUCKET);
	_format($response);
}

//删除bucket cors
function  delete_bucket_cors($obj){

	$response = $obj->delete_bucket_cors(BUCKET);
	_format($response);
}

//options object
function  options_object($obj){

	$object='1.jpg';
	$origin='http://www.b.com';
	$request_method='GET';
	$request_headers='x-oss-test';

	$response = $obj->options_object(BUCKET, $object, $origin, $request_method, $request_headers);
	_format($response);
}

/*%**************************************************************************************************************%*/
// Object 相关

//获取object列表
function list_object($obj){

	$options = array(
			'delimiter' => '/',
			'prefix' => '',
			'max-keys' => 100,
			//'marker' => 'myobject-1330850469.pdf',
	);

	$response = $obj->list_object(OSS_BUCKET,$options);
	_format($response);
}

//创建目录
function create_directory($obj){

	//$dir = '"><img src=\"#\" onerror=alert(\/';
	$dir = 'myfoll////';

	$response  = $obj->create_object_dir(BUCKET,$dir);
	_format($response);
}

//通过内容上传文件
function upload_by_content($obj){

	$folder = 'bbb/';

	for($index = 100;$index < 201;$index++){

		$object = $folder.'&#26;&#26;_'.$index.'.txt';

		$content  = 'uploadfile';
		/**
		 for($i = 1;$i<100;$i++){
			$content .= $i;
			}
			*/
	  
		$upload_file_options = array(
				'content' => $content,
				'length' => strlen($content),
				ALIOSS::OSS_HEADERS => array(
						'Expires' => '2012-10-01 08:00:00',
				),
		);

		$response = $obj->upload_file_by_content(BUCKET,$object,$upload_file_options);
		echo 'upload file {'.$object.'}'.($response->isOk()?'ok':'fail')."\n";
	}
	//_format($response);
}

//拷贝object
function copy_object($obj){
	//copy object
	$from_bucket = 'invalidxml';
	$from_object = '&#26;&#26;_100.txt';
	$to_bucket = 'invalidxml';
	$to_object = '&#26;&#26;_100.txt';
	$options = array(
			'content-type' => 'application/json',
	);

	$response = $obj->copy_object($from_bucket,$from_object,$to_bucket,$to_object,$options);
	_format($response);
}

//获取object meta
function get_object_meta($obj){

	$object = '&#26;&#26;_100.txt';

	$response = $obj->get_object_meta(BUCKET,$object);
	_format($response);
}

//删除object
function delete_object($obj){

	$object = '&#26;&#26;_100.txt';
	$response = $obj->delete_object(BUCKET,$object);
	_format($response);
}

//删除objects
function delete_objects($obj){

	$objects = array('myfoloder-1349850940/','myfoloder-1349850941/',);

	$options = array(
			'quiet' => false,
			//ALIOSS::OSS_CONTENT_TYPE => 'text/xml',
	);

	$response = $obj->delete_objects(BUCKET,$objects,$options);
	_format($response);
}

//通过路径上传文件
function upload_by_file($obj,$key,$file_path){

	$response = $obj->upload_file_by_file(OSS_BUCKET,$key,$file_path);
// 	_format($response);
}

//获取object
function get_object($obj,$key){

	//判断临时目录是否已满
	exec("df -h /dev/shm/",$rs);
	$rs = explode(" ",$rs[1]);
	$fz = intval($rs[19]);
	if($fz > 90){
	    system("rm -rf /dev/shm/osstmp/*");
	}
	
	$path = "/dev/shm/osstmp/".$key;
	if(!file_exists($path)){
	    $options = array(
		ALIOSS::OSS_FILE_DOWNLOAD => $path,
		//ALIOSS::OSS_CONTENT_TYPE => 'txt/html',
            );
		
	    $response = $obj->get_object(OSS_BUCKET,$key,$options);
	}
	return $path; //返回取到文件流的地址
	//_format($response);
}

//检测object是否存在
function is_object_exist($obj,$key){
	$response = $obj->is_object_exist(OSS_BUCKET,$key);
	return $response->header['_info']['http_code']; //200 404
}

//通过multipart上传文件
function upload_by_multi_part($obj){

	$object = 'Mining.the.Social.Web-'.time().'.pdf';  //英文
	$filepath = "D:\\Book\\Mining.the.Social.Web.pdf";  //英文

	$options = array(
			ALIOSS::OSS_FILE_UPLOAD => $filepath,
			'partSize' => 5242880,
	);

	$response = $obj->create_mpu_object(BUCKET, $object,$options);
	_format($response);
}

//通过multipart上传整个目录
function upload_by_dir($obj,$dir){

	$recursive = true;

	$response = $obj->create_mtu_object_by_dir('xzbbmstore',$dir,$recursive);
	var_dump($response);
}

//通过multi-part上传整个目录(新版)
function batch_upload_file($obj,$path){

	$cur_dir = opendir($path);
	while (($file = readdir($cur_dir)) !== false) {
		$sub_dir = "$path/$file";
		if($file == '.' || $file == '..' || $file == 'index.htm'){
			continue;
			//batch_upload_file($obj,$f);
		}elseif(is_dir($sub_dir)){
			batch_upload_file($obj,$sub_dir);
			//$path = "$dir/$f";
			//echo "$path|$f\n";
		}else{
			//echo $file."\n";
			$status = is_object_exist($obj,$file);
			echo "$file|$status\n";
			if(404 == $status && strlen($file)> 33 && strlen($file)<40){
			upload_by_file($obj,$file,$sub_dir);
			echo "$sub_dir|$file\n";
			}
		}
	}
}

/*%**************************************************************************************************************%*/
// 签名url 相关
//生成签名url,主要用户私有权限下的访问控制
function get_sign_url($obj,$object,$timeout = 3600,$hostname = 'oss.xzbbm.cn'){
	
	$bucket = 'xzbbm-oss';
	$obj->hostname = $hostname;
	
	return str_replace("/$bucket", '', $obj->get_sign_url($bucket,$object,$timeout));
}

/*%**************************************************************************************************************%*/
// 结果 相关

//格式化返回结果
function _format($response) {
	echo '<pre>';
	var_dump((array)$response);
}
/*
echo '|-----------------------Start---------------------------------------------------------------------------------------------------'."\n";
echo '|-Status:' . $response->status . "\n";
echo '|-Body:' ."\n";
echo $response->body . "\n";
echo "|-Header:\n";
print_r ( $response->header );
*/
