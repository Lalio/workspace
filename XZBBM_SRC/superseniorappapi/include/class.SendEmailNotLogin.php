<?php
if (! defined ( 'IN_SYS' ))
{
    header ( "HTTP/1.1 404 Not Found" );
    die ();
}
class SendEmailNotLogin
{
    static function Main($fcode, $uid)
    {
        Core::InitDb ();
        $db = Core::$db ['online'];
        
        // 服务器端判断总下载次数，防止刷文件
        $client_ip = get_ip ();
        
        // $all = $db->rsArray ( "SELECT count(*) as total FROM xz_emaillist WHERE ts > '" . date ( 'Y-m-d 00:00:00', TIMESTAMP ) . "' and client_ip = '$client_ip'" );
        // $usr = $db->rsArray ( "SELECT count(*) as total FROM xz_emaillist WHERE ts > '" . date ( 'Y-m-d 00:00:00', TIMESTAMP ) . "' and uid = $uid" );
        // $per = $db->rsArray ( "SELECT count(*) as total FROM xz_emaillist WHERE ts > '" . date ( 'Y-m-d 00:00:00', TIMESTAMP ) . "' and client_ip = '$client_ip' and fid = $fcode" );
        
        // $at = intval ( $all ['total'] );
        // $pt = intval ( $per ['total'] );
        // $ut = intval ( $usr ['total'] );
        
        // if ($at > 8 || $pt > 0 || $ut > 8)
        // {
        // return false;
        // }
        
        $data = array (
                'uid' => $uid,
                'fid' => $fcode,
                'client_ip' => $client_ip,
                'client_adress' => get_adress () 
        );
        
        $rs = $db->insert ( "xz_emaillist", $data );
        return $rs;
        // $ut = $db->rsArray ( "SELECT file_downs FROM pd_files WHERE file_id = $fcode limit 0,1" );
        
        // if ($rs != false)
        // {
        // $ret ["rcode"] = 0;
        // if ($ut ['file_downs'] > 0)
        // {
        // $ret ["notice"] = "共有" . intformat ( $ut ['file_downs'] ) . "位同学免费获取了此资料，学长只能帮你到这儿了。";
        // }
        // else
        // {
        // $ret ["notice"] = "学长只能帮你到这儿了。";
        // }
        // return $ret;
        // }
        // else
        // {
        // $ret ["rcode"] = 1;
        // $ret ["error"] = "学长的邮差罢工了，我正在做他的思想工作。";
        // return $ret;
        // }
        // }
        // }
    }
}
