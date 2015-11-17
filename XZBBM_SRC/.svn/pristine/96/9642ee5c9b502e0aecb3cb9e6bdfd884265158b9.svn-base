<?php
class AcceptCheckEmail extends Xzbbm
{
    public function __construct()
    {
        parent::__construct ();
    }
    public function accpetCheckEmail()
    {
        $encode = str_replace ( ' ', '+', $this->msg ['encode'] ); // 经过url之后+号会变成空格，要替换过来
        
        $email = rawurldecode ( $this->msg ['email'] );
        $decode = $this->authcode ( $encode, "DECODE", "key" );
        
        file_put_contents ( "/data/backend_service/log/testapi.log", "{$this->file_store_path} $email.'this is email !and after urldecode is '.$encode \n", FILE_APPEND );
        
        if ($decode == "this is check email number")
        {
            $update ["validity"] = 1;
            $success = $this->_db->update ( "pd_users", $update, "email='$email'" );
            
            echo "<script>setTimeout(\"this.location='http://www.xzbbm.cn'\",1000);</script>
              <p align=\"center\"; valign=\"center\">邮箱验证成功，页面正在跳转......</p>";
            
            // header("Location: http://www.xzbbm.cn");//header前面不能有输出语句
        }
        else
        {
            echo "<script>setTimeout(\"this.location='http://www.xzbbm.cn'\",1000);</script>
              <p align=\"center\"; valign=\"center\">此链接已过期或密文解析失败，页面正在跳转......</p>";
        }
    }
    // 验证邮箱-加密/解密
    function authcode($string, $operation = 'DECODE', $key = '', $expiry = 3600)
    {
        $ckey_length = 4;
        // 随机密钥长度 取值 0-32;
        // 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
        // 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
        // 当此值为 0 时，则不产生随机密钥
        
        $key = md5 ( $key ? $key : 'key' ); // 这里可以填写默认key值
        $keya = md5 ( substr ( $key, 0, 16 ) );
        $keyb = md5 ( substr ( $key, 16, 16 ) );
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr ( $string, 0, $ckey_length ) : substr ( md5 ( microtime () ), - $ckey_length )) : '';
        
        $cryptkey = $keya . md5 ( $keya . $keyc );
        $key_length = strlen ( $cryptkey );
        
        $string = $operation == 'DECODE' ? base64_decode ( substr ( $string, $ckey_length ) ) : sprintf ( '%010d', $expiry ? $expiry + time () : 0 ) . substr ( md5 ( $string . $keyb ), 0, 16 ) . $string;
        $string_length = strlen ( $string );
        
        $result = '';
        $box = range ( 0, 255 );
        
        $rndkey = array ();
        for($i = 0; $i <= 255; $i ++)
        {
            $rndkey [$i] = ord ( $cryptkey [$i % $key_length] );
        }
        
        for($j = $i = 0; $i < 256; $i ++)
        {
            $j = ($j + $box [$i] + $rndkey [$i]) % 256;
            $tmp = $box [$i];
            $box [$i] = $box [$j];
            $box [$j] = $tmp;
        }
        
        for($a = $j = $i = 0; $i < $string_length; $i ++)
        {
            $a = ($a + 1) % 256;
            $j = ($j + $box [$a]) % 256;
            $tmp = $box [$a];
            $box [$a] = $box [$j];
            $box [$j] = $tmp;
            $result .= chr ( ord ( $string [$i] ) ^ ($box [($box [$a] + $box [$j]) % 256]) );
        }
        
        if ($operation == 'DECODE')
        {
            if ((substr ( $result, 0, 10 ) == 0 || substr ( $result, 0, 10 ) - time () > 0) && substr ( $result, 10, 16 ) == substr ( md5 ( substr ( $result, 26 ) . $keyb ), 0, 16 ))
            {
                return substr ( $result, 26 );
            }
            else
            {
                return '';
            }
        }
        else
        {
            return $keyc . str_replace ( '=', '', base64_encode ( $result ) );
        }
    }
}