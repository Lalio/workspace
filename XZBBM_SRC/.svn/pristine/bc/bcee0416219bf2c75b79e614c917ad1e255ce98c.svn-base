<?php
if(!defined('IN_SYS')) {
    header("HTTP/1.1 404 Not Found");
    die;
}

/**
 * @todo 56广告系统页面JS/HTML代码
 * @author bo.wang3
 * @version 2013-5-7 17:11
 */
Class AdvCode extends Mads{

  protected $_id;
  protected $_title;
  protected $_href;
  protected $_src;
  protected $_return = array();

  /**
   * @author bo.wang3
   * @version 2013-5-6 14:29
   */
  public function __construct(){

      $this->_cid = trim($_REQUEST['cid']);
      $this->_img = trim($_REQUEST['p_img']); //图片素材地址
      $this->_flash = trim($_REQUEST['p_flash']); //图片素材地址
      $this->_img_href = trim($_REQUEST['p_img_href']); //图片素材链接
      $this->_title = trim($_REQUEST['p_title']); //标题
      $this->_title_href = trim($_REQUEST['p_title_href']); //标题链接
      $this->_sub_title = trim($_REQUEST['p_sub_title']); //副标题文字
      $this->_sub_title_img = trim($_REQUEST['p_sub_title_img']); //副标题图片
      $this->_sub_title_link = trim($_REQUEST['p_sub_title_link']); //副标题链接
      $this->_views = trim($_REQUEST['p_views']); //播放数
      $this->_comments = trim($_REQUEST['p_comments']); //评论数
      $this->_swf_href = trim($_REQUEST['p_swf_href']); //图片素材链接
      $this->_swf = trim($_REQUEST['p_swf']); //视频素材地址
      $this->_totaltime = trim($_REQUEST['p_totaltime']); //视频时长
      $this->_type = intval($_REQUEST['p_type']); //视频时长
      $this->_return['state'] = 0; //0表示成功 1表示失败
  }

  /**
   * @author bo.wang3
   * @version 2013-5-6 14:29
   */
  public function __destruct(){

      foreach($this->_return['rs'] as $k => $v){
        $this->_return['rs'][$k] = htmlspecialchars($v);
      }

      echo json_encode($this->_return);
  }

  /**
   * @todo 
   * @author bo.wang3
   * @version 2013-5-6 14:29
   */
  public function Advs(){

    switch($this->_cid){
        
     case 150://游戏频道右侧焦点种子视频
         
     $this->_return['rs']['默认'] = <<<HTMLCODE
<div class="sbox ad_player"> 
  <div class="player" style="height:208px"> 
    <a href="$this->_img_href" target="_blank"><img src="$this->_img"/></a> 
  </div> 
  <p class="ad_title">
    <a href="$this->_title_href" target="_blank">$this->_title</a>
  </p>
</div>
HTMLCODE;

     break;

      case 81:case 175:case 186://右侧焦点视频

      $this->_return['rs']['视频类'] = <<<JSCODE
<script type="text/javascript">
var cid_81_v_params = {
    "file" : "$this->_swf",
    "title" : "$this->_title",
    "link" : "$this->_title_href"
};
</script>
<script src="http://s2.56img.com/script/page/index/v4/cid_81_v.1.js" type="text/javascript" charset="gbk"></script>
JSCODE;


      $this->_return['rs']['图片类'] = <<<JSCODE
<script type="text/javascript">
var cid_81_v_params = {
    "file" : "$this->_img",
    "title" : "$this->_title",
    "link" : "$this->_img_href"
};
</script>
<script src="http://s2.56img.com/script/page/index/v4/cid_81_v.1.js" type="text/javascript" charset="gbk"></script>
JSCODE;

      $this->_return['rs']['视频类[IPAD显示图片]'] = <<<JSCODE
<script type="text/javascript">
var cid_81_v_params = {
    "file" : "$this->_swf",
    "title" : "$this->_title",
    "link" : "$this->_swf_href",
    "opts" : {
        "cover" : "$this->_img",
    }
}; 
</script>
<script src="http://s2.56img.com/script/page/index/v4/cid_81_v.1.js" type="text/javascript" charset="gbk"></script>
JSCODE;

      $this->_return['rs']['图片类[图片和标题链接不一致]'] = <<<JSCODE
<script type="text/javascript">
var cid_81_v_params = {
    "file" : "$this->_img",
    "title" : "$this->_title",
    "link" : {
        "imglink" : "$this->_img_href",
        "textlink" : "$this->_title_href"
    }
}; 
</script>
<script src="http://s2.56img.com/script/page/index/v4/cid_81_v.1.js" type="text/javascript" charset="gbk"></script>
JSCODE;

      $this->_return['rs']['视频类[IPAD显示图片且图片和标题链接不一致]'] = <<<JSCODE
<script type="text/javascript">
var cid_81_v_params = {
    "file" : "$this->_swf",
    "title" : "$this->_title",
    "link" : {
        "imglink" : "$this->_img_href",
        "textlink" : "$this->_title_href"
    },
    "opts" : {
        "cover" : "$this->_img"
    }
};
</script>
<script src="http://s2.56img.com/script/page/index/v4/cid_81_v.1.js" type="text/javascript" charset="gbk"></script>
JSCODE;

      break;
      
      case 120://视频、专辑播放页右侧广告300*100

      $this->_return['rs']['默认'] = <<<JSCODE
<script type="text/javascript">
__aid  = "";
__href = "$this->_swf_href";
__src  = "$this->_swf";
__height = 100;
__width = 300;
var __img_tpl = "<a href='{HREF}' target='_blank'><img width='{WIDTH}' height='{HEIGHT}' border='0' src='{SRC}'/></a>";
var __html_tpl = "<iframe  height='{HEIGHT}' frameborder='0' width='{WIDTH}' scrolling='no'  src='{SRC}' marginwidth='0' marginheight='0'></iframe>";
var __flash_tpl = "<embed width='{WIDTH}' height='{HEIGHT}' type='application/x-shockwave-flash' wmode='opaque' quality='medium' src='{SRC}'/>";
getTpl(__src);
function getTpl(sUrl){
  try{
  var tType = sUrl.substr(sUrl.lastIndexOf(".")).toLowerCase();
  var sInner = "";
  switch(tType){
      case ".html":
        sInner = __html_tpl.replace("{HEIGHT}",__height).replace("{WIDTH}",__width).replace("{SRC}",sUrl);
        break;
      case ".swf":
        sInner = __flash_tpl.replace("{HEIGHT}",__height).replace("{WIDTH}",__width).replace("{SRC}",sUrl);
        break;
      default :
        if(__aid){
          sUrl   = "http://acs.56.com/redirect/view/"+ __aid +"/" + sUrl;
          __href = "http://acs.56.com/redirect/click/" + __aid  + "/" + __href;
        }
        sInner = __img_tpl.replace("{SRC}",sUrl).replace("{HEIGHT}",__height).replace("{WIDTH}",__width).replace("{HREF}",__href);
  }
  document.write('<div marginwidth="0" marginheight="0" style="display:block;width:'+__width+'px;height:'+__height+'px">'+sInner+'</div>');
 }catch(e){ 
 }
}
</script>
JSCODE;

      break;

      case 124://视频播放页右一触发

      $this->_return['rs']['默认'] = <<<JSCODE
<script type="text/javascript">
__aid  = "";
__href = "$this->_img_href";
__src  = "$this->_img";
__height = 250;
__width = 300;
var __img_tpl = "<a href='{HREF}' target='_blank'><img width='{WIDTH}' height='{HEIGHT}' border='0' src='{SRC}'/></a>";
var __html_tpl = "<iframe  height='{HEIGHT}' frameborder='0' width='{WIDTH}' scrolling='no'  src='{SRC}' marginwidth='0' marginheight='0'></iframe>";
var __flash_tpl = "<embed width='{WIDTH}' height='{HEIGHT}' type='application/x-shockwave-flash' wmode='opaque' quality='medium' src='{SRC}'/>";
getTpl(__src);
function getTpl(sUrl){
  try{
  var tType = sUrl.substr(sUrl.lastIndexOf(".")).toLowerCase();
  var sInner = "";
  switch(tType){
      case ".html":
        sInner = __html_tpl.replace("{HEIGHT}",__height).replace("{WIDTH}",__width).replace("{SRC}",sUrl);
        break;
      case ".swf":
        sInner = __flash_tpl.replace("{HEIGHT}",__height).replace("{WIDTH}",__width).replace("{SRC}",sUrl);
        break;
      default :
        if(__aid){
          sUrl   = "http://acs.56.com/redirect/view/"+ __aid +"/" + sUrl;
          __href = "http://acs.56.com/redirect/click/" + __aid  + "/" + __href;
        }
        sInner = __img_tpl.replace("{SRC}",sUrl).replace("{HEIGHT}",__height).replace("{WIDTH}",__width).replace("{HREF}",__href);
  }
  document.write('<div marginwidth="0" marginheight="0" style="display:block;width:'+__width+'px;height:'+__height+'px">'+sInner+'</div>');
 }catch(e){ 
 }
}
</script>
JSCODE;

      break;

      case 134:case 135:case 147;case 148;case 154;case 157:  //频道 右侧焦点视频 276*208

$this->_return['rs']['视频'] = <<<JSCODE
<div class="sbox ad_player">  
      <div class="player">
      <object height="100%" width="100%" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" id="object_flash_player" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000">
      <param value="$this->_swf" name="movie"/>
      <param value="high" name="quality"/>
      <param value="opaque" name="wmode"/>
      <param value="always" name="allowScriptAccess"/>
      <embed height="100%" width="100%" allowfullscreen="true" type="application/x-shockwave-flash"wmode="opaque" pluginspage="http://www.macromedia.com/go/getflashplayer" quality="high" src="$this->_swf"/>
      </object>
      </div>
<p class="ad_title"><a href="$this->_title_href" target="_blank">$this->_title</a></p>
</div>
JSCODE;

$this->_return['rs']['图片'] = <<<JSCODE
<div class="sbox ad_player"> 
  <div class="player" style="height:208px"> 
    <a href="$this->_img_href" target="_blank"><img src="$this->_img"/></a> 
  </div> 
  <p class="ad_title">
    <a href="$this->_title_href" target="_blank">$this->_title</a>
  </p>
</div>
JSCODE;

      break;
	  
	  case 177:case 178:case 179:case 180:case 181:case 183:case 184:case 186:case 187:case 188: case 193:case 194:case 195:case 196:case 197:case 198:case 199:case 200:case 201:case 202:case 203:case 204:case 205:case 206:case 207:case 208://页面广告通用json代码

		$code = array(
			'file' => $this->_img,
			'title' => $this->_title,
			'url' => $this->_img_href
		);

		foreach($code as $k => $v){
			$code[$k] = urlencode($v);
		}

		$this->_return['rs']['默认'] = stripcslashes(urldecode(json_encode($code)));

		break;

      case 214:case 215:case 216:case 217:case 218:case 219:case 220:case 221:case 222:case 223:case 224:case 225:case 226:case 227:case 228:case 239:case 240:case 241:  //全站种子视频通用JSON代码
      
      	$code = array(
      		'img_url' => $this->_img,
      		'img_link' => $this->_img_href,
      		'title' => $this->_title,
      		'title_link' => $this->_title_href,
      		'sub_title' => $this->_sub_title,
      		'sub_title_link' => $this->_sub_title_link,
      		'sub_title_img' => $this->_sub_title_img,
      		'views' => $this->_views,
      		'comments' => $this->_comments,
      		'vid' => g::GetUrlId($this->_swf),
      		'totaltime' => $this->_totaltime,
      		//'swf' => $this->_swf,
      		//'swf_href' => $this->_swf_href,
      		'flash_url' => $this->_flash
      	);
      	
      	if(!empty($code['flash_url'])){
      		$code['type'] = 'swf';
      	}else{
      		$code['type'] = 'jpg';
      	}
      	
      	$this->_return['rs']['默认'] = json_encode($code);
      
      	break;
      
      	case 250:case 251:case 252:case 253:case 254:case 255:case 256:case 257:  //主客首页种子视频
      	
      		$code = array(
	      		'title' => $this->_title,
	      		'img' => $this->_img,
	      		'url' => $this->_img_href,
	      		'type' => $this->_type,
      		);
      		 
      		$this->_return['rs']['默认'] = json_encode($code);
      	
      		break;

      default:

        $this->_return['state'] = 1;
    }
  }
  
}
