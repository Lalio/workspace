<?php
class RoundedCorner {
	private $_r;
	private $_g;
	private $_b;
	private $_image_path;
	private $_radius;
	
	function __construct($image_path, $radius, $r = 255, $g = 0, $b = 0) {
		$this->_image_path = $image_path;
		$this->_radius = $radius;
		$this->_r = (int)$r;
		$this->_g = (int)$g;
		$this->_b = (int)$b;
	}
	
	private function _get_lt_rounder_corner() {
		$radius = $this->_radius;
                //$radius=20;
		$img = imagecreatetruecolor($radius, $radius);
                
		$bgcolor = imagecolorallocate($img, $this->_r, $this->_g, $this->_b);
		$fgcolor = imagecolorallocate($img, 0, 0, 0);
		imagefill($img, 0, 0, $bgcolor);
                
		imagefilledarc($img, $radius, $radius, $radius*2, $radius*2, 180, 270, $fgcolor, IMG_ARC_PIE);
		//imagefilledarc在指定的 image 上画一椭圆弧且填充。
                
                imagecolortransparent($img, $fgcolor);
                //imagecolortransparent() 将 image 图像中的透明色设定为 color。
                //image 是 imagecreatetruecolor() 返回的图像标识符，
                //color 是 imagecolorallocate() 返回的颜色标识符。
                
                //imagejpeg($img);
		return $img;
	}
	

	public function round_it($src_image,$lt=true,$lb=true,$rb=true,$rt=true) {
		// load the source image
		if ($src_image === false) {
                    die('对不起，图片加载失败'); 
		}
		$image_width = imagesx($src_image);
		$image_height = imagesy($src_image);
		
		// create a new image, with src_width, src_height, and fill it with transparent color
		$image = imagecreatetruecolor($image_width, $image_height);
		$trans_color = imagecolorallocate($image, $this->_r, $this->_g, $this->_b);
		imagefill($image, 0, 0, $trans_color);
		
		// then overwirte the source image to the new created image
		imagecopymerge($image, $src_image, 0, 0, 0, 0, $image_width, $image_height, 100);
		
		// then just copy all the rounded corner images to the 4 corners
		$radius = $this->_radius;
		// lt
                $lt_corner = $this->_get_lt_rounder_corner();
                
                if($lt){
                    imagecopymerge($image, $lt_corner, 0, 0, 0, 0, $radius, $radius, 100);
                }
		
		// lb
		$lb_corner = imagerotate($lt_corner, 90, $trans_color);
                //imagerotate将 src_im 图像用给定的 angle 角度旋转。bgd_color 指定了旋转后没有覆盖到的部分的颜色。
                //旋转的中心是图像的中心，旋转后的图像会按比例缩小以适合目标图像的大小——边缘不会被剪去。
                
                if($lb){
                    imagecopymerge($image, $lb_corner, 0, $image_height - $radius, 0, 0, $radius, $radius, 100);
                }
		
		// rb
		$rb_corner = imagerotate($lt_corner, 180, $trans_color);
                if($rb){
                    imagecopymerge($image, $rb_corner, $image_width - $radius, $image_height - $radius, 0, 0, $radius, $radius, 100);
                }
		
		// rt
		$rt_corner = imagerotate($lt_corner, 270, $trans_color);
                if($rt){
                    imagecopymerge($image, $rt_corner, $image_width - $radius, 0, 0, 0, $radius, $radius, 100);
                }
                
		// set the transparency
		imagecolortransparent($image, $trans_color);
		
                return $image;

	}
        
        
        //处理圆角
//   function rounded_corner($image,$size)
//   {
//        $this->angle = 0;
//        $topleft = true;
//        $bottomleft = true;
//        $bottomright = true;
//        $topright = true;
//        
//        $corner_source = imagecreatefrompng('rounded_corner.png');
//        $corner_width = imagesx($corner_source);  
//        $corner_height = imagesy($corner_source);  
//        $corner_resized = ImageCreateTrueColor($this->corner_radius, $this->corner_radius);
//        ImageCopyResampled($corner_resized, $corner_source, 0, 0, 0, 0, $this->corner_radius, $this->corner_radius, $corner_width, $corner_height);
//        
//        $corner_width = imagesx($corner_resized);  
//        $corner_height = imagesy($corner_resized);  
//        $white = ImageColorAllocate($image,255,255,255);
//        $black = ImageColorAllocate($image,0,0,0);
//
//        //顶部左圆角
//        if ($topleft == true) {
//            $dest_x = 0;  
//            $dest_y = 0;  
//            imagecolortransparent($corner_resized, $black); 
//            imagecopymerge($image, $corner_resized, $dest_x, $dest_y, 0, 0, $corner_width, $corner_height, 100);
//        } 
//
//        //下部左圆角
//        if ($bottomleft == true) {
//            $dest_x = 0;  
//            $dest_y = $size - $corner_height; 
//            $rotated = imagerotate($corner_resized, 90, 0);
//            imagecolortransparent($rotated, $black); 
//            imagecopymerge($image, $rotated, $dest_x, $dest_y, 0, 0, $corner_width, $corner_height, 100);  
//        }
//
//        //下部右圆角
//        if ($bottomright == true) {
//            $dest_x = $size - $corner_width;  
//            $dest_y = $size - $corner_height;  
//            $rotated = imagerotate($corner_resized, 180, 0);
//            imagecolortransparent($rotated, $black); 
//            imagecopymerge($image, $rotated, $dest_x, $dest_y, 0, 0, $corner_width, $corner_height, 100);  
//        }
//
//        //顶部右圆角
//        if ($topright == true) {
//            $dest_x = $size - $corner_width;  
//            $dest_y = 0;
//            $rotated = imagerotate($corner_resized, 270, 0);
//            imagecolortransparent($rotated, $black); 
//            imagecopymerge($image, $rotated, $dest_x, $dest_y, 0, 0, $corner_width, $corner_height, 100);  
//        }
//        $image = imagerotate($image, $this->angle, $white);
//        return $image; 
//   }

        
        
}

?>