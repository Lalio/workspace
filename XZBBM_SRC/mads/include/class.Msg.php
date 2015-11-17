<?php
/**
 * @name:	class.Msg.php
 * @todo: 	短消息处理
 * @author:	zhys9(jingki) @ 2008-9-4
 * <code>
        $sys_msg = sprintf("你在[my56url=%s]%s[/my56url]发起的PK中战败，%s的得分是：%s，超过了你在游戏“[my56url=%s]%s[/my56url]”中的最高分:%s，并且拿走了你在该游戏中积累的%d个金币。[br]小样，[my56url=%s]我要和他再PK一次[/my56url]", 
            User::UserUrl($_gUser['Account']),
            $_gUser['nickname'],
            $_gUser['Gender']=='M'?'他':'她',
            sprintf("%.4f",$score),
            $game_info['app_url'],
            $game_info['name'],
            $last_pk_user_bonus['score'],
            $bonus_total,
            $game_info['app_url'].'&pk='.USER_ID,
            $_gUser['Gender']=='M'?'他':'她'
        );
        $msg = Msg::Send('cs', $pk_user_id, $sys_msg);
    </code>
 *
 */

class Msg {
    
    const API_HOST = 'msg.56.com';
    const API_PORT = '80';
    const API_ROOT = 'api/sendsys.php';
    
    /**
     * @name __construct
     * @author zhys9
     * 
     */
    public function __construct() {
    
    }
    
    /**
     * @name Send
     * @author zhys9
     * @todo 发送短消息
     * @param strint $from 发消息的ID,默认账户是cs,代表56网
     * @param string $to 消息发送对象的用户ID,多个用户ID用半角','隔开
     * @param string $content 消息内容
     * @return array()
     * @modify heqintry@2012-4-28
     */
    public static function Send($from = 'cs', $to, $content) {
        $uri = sprintf ( "%s?from=%s&to=%s&content=%s&key=%s", self::API_ROOT, $from, $to, urlencode ( $content ), md5 ( 'msg_no_ip_ban' ) );
        $rs = Http::Get ( self::API_HOST, $uri, self::API_PORT );
        return json_decode ( $rs, true );
    }
    
    /**
     * @name __destruct
     * @author zhys9
     *
     */
    public function __destruct() {
    
    }

}
