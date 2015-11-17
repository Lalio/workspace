<?php
/**
 * @name:	class.Upload.php
 * @todo: 	上传
 * @author:	zhys9(jingki) @ 2008-6-3
 * @author:	Melon 2010
 */
class Upload {
    protected static $minSize = 1024;
    protected static $maxSize = 2097152; //(1024*2)*1024=2097152 就是 2M
    protected static $allowExt = array ('gif', 'png', 'jpg', 'jpeg' );
    
    public function set($array) {
        foreach ( $array as $k => $v ) {
            if (isset ( self::$$k )) {
                self::$$k = $v;
            }
        }
    }
    
    /**
     * @todo 检查文件大小和格式是否合法
     * @param array $file 
     * @param string $extName 文件扩展名
     * @return bool|array
     */
    public function check($file, $extName = '') {
        if ($file ['error']) {
            switch ($file ['error']) {
                case '1' :
                    $error = '上传文件超出服务器限制';
                    break;
                case '2' :
                    $error = '上传文件超出限制';
                    break;
                case '3' :
                    $error = '文件上传不完整，请重新上传';
                    break;
                case '4' :
                    $error = '没有文件，请选择你要上传的文件';
                    break;
                case '6' :
                    $error = 'Missing a temporary folder';
                    break;
                case '7' :
                    $error = 'Failed to write file to disk';
                    break;
                case '8' :
                    $error = 'File upload stopped by extension';
                    break;
                case '999' :
                default :
                    $error = 'No error code avaiable';
            }
            return array ($file ['error'], $error );
        } else if (empty ( $file ['tmp_name'] ) || $file ['tmp_name'] == 'none') {
            return array (- 30, '上传出现异常，请稍后再试' );
        }
        
        $size = $ext = true;
        $upload_file = array ();
        $upload_file ['size'] = filesize ( $file ['tmp_name'] );
        if (( int ) self::$minSize < ( int ) self::$maxSize) {
            $size = $upload_file ['size'] > self::$minSize && $upload_file ['size'] < self::$maxSize;
        }
        if (is_array ( self::$allowExt )) {
            $upload_file ['ext'] = $extName ? $extName : self::fileExt ( $file ['name'] );
            $ext = in_array ( strtolower ( $upload_file ['ext'] ), self::$allowExt );
        }
        return $size && $ext;
    }
    
    /**
     * 创建图片缩略图,成功返回真，否则返回错误类型号
     *
     * @param string $dscChar   前缀
     * @param int $width    缩略图宽
     * @param int $height   缩略图高
     * @return 
     */
    public function thumb($directroy, $srcFileName, $dscChar = 'thumb_', $width = 160, $height = 120) {
        $srcFile = $srcFileName;
        $dscFile = $directroy . 'thumb/' . $dscChar . $srcFileName;
        list ( $widthsrc, $heightsrc, $type, $attr ) = getimagesize ( $directroy . $srcFile );
        switch ($type) {
            case 1 :
                if (! function_exists ( "imagecreatefromgif" )) {
                    echo "你的GD库不能使用GIF格式的图片，请使用Jpeg或PNG格式！<a href='javascript:go(-1);'>返回</a>";
                    exit ();
                }
                $im = imagecreatefromgif ( $directroy . $srcFile );
                break;
            case 2 :
                if (! function_exists ( "imagecreatefromjpeg" )) {
                    echo "你的GD库不能使用jpeg格式的图片，请使用其它格式的图片！<a href='javascript:go(-1);'>返回</a>";
                    exit ();
                }
                $im = imagecreatefromjpeg ( $directroy . $srcFile );
                break;
            case 3 :
                $im = imagecreatefrompng ( $directroy . $srcFile );
                break;
        }
        
        $srcW = imagesx ( $im );
        $srcH = imagesy ( $im );
        
        if (($srcW / $width) >= ($srcH / $height)) {
            $temp_height = $height;
            $temp_width = $srcW / ($srcH / $height);
            $src_X = abs ( ($width - $temp_width) / 2 );
            $src_Y = 0;
        } else {
            $temp_width = $width;
            $temp_height = $srcH / ($srcW / $width);
            $src_X = 0;
            $src_Y = abs ( ($height - $temp_height) / 2 );
        }
        $temp_img = imagecreatetruecolor ( $temp_width, $temp_height );
        imagecopyresized ( $temp_img, $im, 0, 0, 0, 0, $temp_width, $temp_height, $srcW, $srcH );
        
        $ni = imagecreatetruecolor ( $width, $height );
        imagecopyresized ( $ni, $temp_img, 0, 0, $src_X, $src_Y, $width, $height, $width, $height );
        $cr = imagejpeg ( $ni, $dscFile );
        chmod ( $dscFile, 0777 );
        
        if ($cr) {
            return true;
        } else {
            return false;
        }
    }
    
    public function random($length, $numeric = 0) {
        if ($numeric) {
            $hash = sprintf ( '%0' . $length . 'd', mt_rand ( 0, pow ( 10, $length ) - 1 ) );
        } else {
            $hash = '';
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
            $max = strlen ( $chars ) - 1;
            for($i = 0; $i < $length; $i ++) {
                $hash .= $chars [mt_rand ( 0, $max )];
            }
        }
        return $hash;
    }
    
    /**
     * @todo 保存上传后的文件
     * @param string $file 临时文件路径
     * @param string $saveTo 保存到什么地方
     * @param bool $autoDelete 保存后删除临时文件按
     * @return array
     * 
     */
    public function save($file, $saveTo, $autoDelete = true) {
        header ( "hfile: {$file}" );
        header ( "hsaveTo: {$saveTo}" );
        header ( "hserver: {$_SERVER['SERVER_ADDR']}" );
        if (! self::isUploadedFile ( $file )) {
            $return = array ('status' => false, 'code' => '201', 'msg' => 'not_uploaded_file' );
        }
        if (@move_uploaded_file ( $file, $saveTo )) {
            $return = array ('status' => true );
        } elseif (@copy ( $file, $saveTo )) {
            $return = array ('status' => true );
        } else {
            $return = array ('status' => false, 'code' => '202', 'msg' => 'process_err' );
        }
        if ($autoDelete) {
            self::unlink ( $file );
        }
        return $return;
    }
    /**
     * @todo get extension name of a file name
     * @return string ext
     */
    public function fileExt($fileName) {
        return trim ( substr ( strrchr ( $fileName, '.' ), 1 ) );
    }
    
    public function isUploadedFile($file) {
        return function_exists ( 'is_uploaded_file' ) && (is_uploaded_file ( $file ) || is_uploaded_file ( str_replace ( '\\\\', '\\', $file ) ));
    }
    
    /**
     * 删除文件
     */
    public function unlink($file) {
        @unlink ( $file );
    }
    
    /**
     * @todo: 获取目录
     * @author: Melon`` @ 2010
     *
     */
    public function getUploadDir($parm) {
        $r1 = sprintf ( '%n', crc32 ( $parm ) ) / 99;
        $r2 = sprintf ( '%n', crc32 ( $parm ) ) * 19 / 99;
        return UPLOAD_DIR . $r1 . '/' . $r2 . '/';
    }
    
    /**
     * @name 创建文件夹
     * @param string $path
     * @return boolean
     */
    public function createFolder($path) {
        if (! file_exists ( $path )) {
            mkdir ( $path, 0777, TRUE );
            return true;
        } else {
            return false;
        }
    }
}
 
 
