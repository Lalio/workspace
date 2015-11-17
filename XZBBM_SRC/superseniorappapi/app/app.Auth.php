<?php
if (! defined ( 'IN_SYS' ))
{
    header ( "HTTP/1.1 404 Not Found" );
    die ();
}

/**
 *
 * @todo 用户认证模块
 * @author bo.wang3
 * @version 2013-4-22 14:29
 */
class Auth extends Xzbbm
{
    public function __construct()
    {
        parent::__construct ();
    }
    /**
     *
     * @todo 生成验证码
     * @author bo.wang3
     * @version 2013-4-22 14:29
     */
    public function GVerifyCode()
    {
        $v = new VerifyCode ( 100, 30, 4 ); // 实列化，默认根据字符大小产生图像，产生5个字符
        $v->ZH = false; // 打开中文验证码开关
        $v->bg_alpha = 127; // 背景全透明，注意IE6不支持png透明，需写hack
        $_SESSION ['verifycode'] = $v->show (); // 字符串写入session，并得到返回的验证字串
    }
    
    /**
     *
     * @todo 退出模块
     * @author bo.wang3
     * @version 2013-5-27 14:29
     */
    public function LogOut()
    {
        $this->userinfo = '';
        setcookie ( 'userinfo', '' );
        go_win ( 2 );
    }
    
    /**
     *
     * @todo 注册模块
     * @author bo.wang3
     * @version 2013-5-27 14:29
     */
    public function Reg()
    {
        if (strtoupper ( $_REQUEST ['yzm'] ) !== strtoupper ( $_SESSION ['verifycode'] ))
        {
            // 验证码不正确
            echo json_encode ( array (
                    'rs' => 1 
            ) );
            exit ();
        }
        
        if ($_REQUEST ['agree'] != 'on')
        {
            // 未同意协议
            echo json_encode ( array (
                    'rs' => 5 
            ) );
            exit ();
        }
        
        if (empty ( $_REQUEST ['email'] ) || empty ( $_REQUEST ['uni'] ) || empty ( $_REQUEST ['confirm_pwd'] ) || empty ( $_REQUEST ['password'] ))
        {
            // 参数填写不完整
            echo json_encode ( array (
                    'rs' => 2 
            ) );
            exit ();
        }
        
        if ($_REQUEST ['password'] != $_REQUEST ['confirm_pwd'])
        {
            // 两次密码输入不一致
            echo json_encode ( array (
                    'rs' => 3 
            ) );
            exit ();
        }
        
        $user = array (
                'email' => $_REQUEST ['email'],
                'sex' => $_REQUEST ['sex'],
                'gid' => 4,
                'is_locked' => 0,
                'last_login_time' => TIMESTAMP,
                'reg_time' => TIMESTAMP,
                'reg_ip' => $_SERVER ['REMOTE_ADDR'],
                'last_login_ip' => $_SERVER ['REMOTE_ADDR'],
                'credit' => 10,
                'exp' => 20,
                'ucode' => $_REQUEST ['uni'],
                'ccode' => $_REQUEST ['col'],
                'pay_account' => $_REQUEST ['pay_account'],
                'password' => md5 ( $_REQUEST ['password'] ) 
        );
        
        $u = $this->_db->rsArray ( "SELECT userid,ccode FROM pd_users WHERE email = '{$user['email']}' LIMIT 0,1" );
        
        if ($u ['ccode'] > 0)
        {
            // 该用户已存在
            echo json_encode ( array (
                    'rs' => 4 
            ) );
            exit ();
        }
        elseif (empty ( $u ['userid'] ))
        {
            $this->_db->insert ( 'pd_users', $user );
        }
        else
        {
            $this->_db->update ( 'pd_users', array (
                    'ucode' => $user ['ucode'],
                    'ccode' => $user ['ccode'],
                    'pay_account' => $user ['pay_account'],
                    'password' => $user ['password'] 
            ), "userid = {$u['userid']}" );
        }
        
        // 注册成功
        echo json_encode ( array (
                'rs' => 0 
        ) );
    }
    
    /**
     *
     * @todo 判断用户是否注册
     * @author bo.wang3
     * @version 2013-5-27 14:29
     */
    public function CheckUser()
    {
        $email = trim ( $_REQUEST ['email'] );
        
        if (! preg_match ( "/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i", $email ))
        {
            $rt = array (
                    'rs' => 2 
            ); // 邮箱格式不正确
        }
        else
        {
            $rs = $this->_db->rsArray ( "SELECT ccode FROM pd_users WHERE email = '$email' LIMIT 0,1" );
            if ($rs ['ccode'] == 0 || empty ( $rs ['ccode'] ))
            {
                $rt = array (
                        'rs' => 0 
                ); // 可注册
            }
            else
            {
                $rt = array (
                        'rs' => 1 
                ); // 不可注册
            }
        }
        
        echo json_encode ( $rt );
    }
    
    /**
     *
     * @todo 用户登录验证
     * @author bo.wang3
     * @version 2013-5-27 14:29
     */
    public function Login()
    {
        if (strtoupper ( $_REQUEST ['yzm'] ) !== strtoupper ( $_SESSION ['verifycode'] ))
        {
            // 验证码不正确
            echo json_encode ( array (
                    'rs' => 1 
            ) );
            exit ();
        }
        
        if (empty ( $_REQUEST ['email'] ) || empty ( $_REQUEST ['password'] ) || empty ( $_REQUEST ['yzm'] ))
        {
            // 参数填写不完整
            echo json_encode ( array (
                    'rs' => 2 
            ) );
            exit ();
        }
        
        $need = "profile,userid,email,sex,last_login_time,last_login_ip,reg_time,credit,ucode,ccode,pay_account";
        $u = $this->_db->rsArray ( "SELECT $need FROM pd_users WHERE email = '{$_REQUEST['email']}' and password = '" . md5 ( $_REQUEST ['password'] ) . "' LIMIT 0,1" );
        
        if (! empty ( $u ))
        {
            
            $ftotal = $this->_db->rsArray ( "SELECT count(*) as ftotal
										   FROM pd_files
										   WHERE userid = $u[userid]" );
            // 防止cookie劫持
            $u ['ip'] = $_SERVER ['REMOTE_ADDR'];
            $u ['ua'] = $_SERVER ['HTTP_USER_AGENT'];
            $u ['ftotal'] = $ftotal ['ftotal'] == 0 ? ' - ' : ( string ) $ftotal ['ftotal'];
            
            if ($_REQUEST ['rem'] == 'on')
            {
                setcookie ( 'userinfo', json_encode ( $u ), time () + 86400 * 31 );
            }
            else
            {
                setcookie ( 'userinfo', json_encode ( $u ), time () + 3600 * 12 );
            }
            $this->_db->update ( 'pd_users', array (
                    'last_login_ip' => $u ['ip'],
                    'last_login_time' => TIMESTAMP 
            ), 'userid = ' . $u ['userid'] );
            // 登陆成功
            echo json_encode ( array (
                    'rs' => 0,
                    'info' => $u 
            ) );
        }
        else
        {
            echo json_encode ( array (
                    'rs' => 3 
            ) );
        }
    }
}