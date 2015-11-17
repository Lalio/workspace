<?php
if (! defined ( 'IN_SYS' ))
{
    header ( "HTTP/1.1 404 Not Found" );
    die ();
}
require_once (dirname ( __FILE__ ) . '/../init.php');
class PayAPI extends Xzbbm
{
    public function CreateOrder()
    {
        // private static final String CHANNEL_UPMP = "upmp"; // 银联
        // private static final String CHANNEL_WECHAT = "wx"; // 微信
        // private static final String CHANNEL_ALIPAY = "alipay"; // 支付宝
        // private static final String CHANNEL_YHQ = "yhq"; // 优惠券
        $order [file_index] = $this->msg [file_index];
        $order [action] = $this->msg [action];
        $order [email] = $this->msg [email];
        $order [phone] = $this->msg [phone];
        $order [store_id] = '100001'; // $this->msg [store_id]; // 打印店ID号//TODO
        $order [duplex] = $this->msg [duplex]; // 0单面 1双面
        $order [total] = $this->msg [total]; // 份数
        $order [receiver_name] = $this->msg [receiver_name]; // 收件人
        $order [address] = $this->msg [address]; // 收件地址
        $order [channel] = $this->msg [channel]; // 付款方式
        $order [time] = TIMESTAMP;
        $user = $this->GetUser ( $this->msg [xztoken] );
        $rs_file = $this->_db->rsArray ( "select * from pd_files where file_index = '{$order[file_index]}' limit 1" );
        if ($rs_file [file_id] <= 0 || $user [userid] <= 0)
        {
            $this->Error ( "参数错误" );
            return;
        }
        $order [file_id] = $rs_file [file_id];
        $order [userid] = $user [userid];
        $order [subject] = $rs_file [file_name];
        $order [body] = "将发送资料到您的邮箱：" . $order [email];
        $order [order_no] = substr ( md5 ( time () ), 0, 12 );
        $order [state] = "create"; // create client_success client_fail ping_fail ping_success
        $amount = 0;
        if ($order [action] == "express" || $order [action] == "buffet")
        {
            $is_duplex = $order [duplex] == 0 ? false : true;
            $ret_price = $this->GetNormalPrice ( $rs_file, $is_duplex, $order [total] );
            $amount = $order [action] == "express" ? $ret_price ["express_price"] : $ret_price ["buffet_price"];
        }
        else
        {
            $amount = $rs_file [today_price];
        }
        $order [amount] = $amount;
        $order [file_own_userid] = $rs_file [userid];
        $rs_fid = $this->_db->insert ( "pay_order", $order );
        
        if (is_numeric ( $rs_fid ))
        {
            // $this->ok ();
            // echo var_dump($this->msg);
        }
        else
        {
            $this->Error ( $this->_db->_errorMsg );
        }
        
        $this->PingxxPay ( $order [order_no], $order [channel], $order [amount], $order [subject], $order [body] );
    }
    public function ClientPayResult()
    {
        $order_no = $this->msg [order_no];
        $pay_ret = $this->msg [pay_result];
        $rs = $this->_db->rsArray ( "select * from pay_order where order_no='$order_no' limit 1" );
        
        if ($rs [state] == "create")
        {
            if ($pay_ret == "success")
            {
                $update [state] = "client_success";
                $this->ClientSuccess ( $rs );
            }
            else
            {
                $update [state] = "client_fail";
            }
            $this->_db->update ( "pay_order", $update, "order_no='$order_no'" );
        }
        $this->ok ();
    }
    function ClientSuccess($order)
    {
        $action = $order [action];
        $this->operate($order[userid], $order[file_id], Xzbbm::operate_buy);
        $this->operate($order [file_own_userid] , $order[file_id], Xzbbm::operate_sell);
        
        $user = $this->_db->rsArray ( "SELECT user_name FROM pd_users WHERE userid = '{$order[userid]}' LIMIT 0,1" );
        $rs = $this->_db->rsArray ( "SELECT * FROM pd_files WHERE file_id = '{$order[file_id]}' LIMIT 0,1" );
        
        $price = sprintf ( '%.2f', $rs ['today_price'] / 100 );
        $title = "您获得了 ¥{$price}元 收益";
        $content = "$user[user_name] 购买了您的《$rs[file_name]》";
        $this->PushAccount ( [ 
                $order [file_own_userid] 
        ], $title, $content );
        
        if ($action == "send") // 发送到邮箱
        {
            $this->BuySend ( $order );
        }
        else if ($action == "download") // 下载
        {
            $this->BuyDownload ( $order );
        }
        else if ($action == "express") // 云印快递
        {
            $this->BuyExpress ( $order );
        }
        else if ($action == "buffet") // 云印自取
        {
            $this->BuyBuffet ( $order );
        }
    }
    function PingSuccess()
    {
    }
    function PingFail()
    {
    }
    function BuyDownload($order)
    {
    }
    function BuySend($order)
    {
        $email = $order ["email"];
        $user = $this->_db->rsArray ( "SELECT * FROM pd_users WHERE userid = '{$order[userid]}' LIMIT 0,1" );
        $rs = $this->_db->rsArray ( "SELECT file_id FROM pd_files WHERE file_id = '{$order[file_id]}' LIMIT 0,1" );
        $file_id = $rs ["file_id"];
        if ($user [userid] > 0 && $file_id > 0)
        {
            if ($email != $user [email])
            {
                $ret_update = $this->_db->update ( 'pd_users', [ 
                        'email' => $email 
                ], "userid = '$user[userid]'" );
            }
            SendEmailNotLogin::Main ( $file_id, $user [userid] );
            $this->operate($order[userid], $order[file_id], Xzbbm::operate_send);            
        }
    }
    function BuyExpress($order)
    {
        $this->BuyBuffet ( $order );
    }
    function BuyBuffet($order)
    {
        $rs = $this->_db->rsArray ( "SELECT * FROM pd_files WHERE file_id = '{$order[file_id]}' LIMIT 0,1" );
        
        $is_duplex = $order [duplex] == 0 ? false : true;
        $ret_price = $this->GetNormalPrice ( $rs, $is_duplex, $order [total] );
        $amount = $ret_price ["buffet_price"] - $rs[today_price];
        
        $value = array (
                'order_id' => build_order_no (),
                'order_no' => $order [order_no],
                'store_id' => $order [store_id],
                'pages' => $rs[total_page],
                'total' => $order [total],
                'duplex' => $is_duplex == true ? 2 : 1,
                'file_id' => $order [file_id],
                'userid' => $order [userid],
                'price' => $amount / 100,
                'ts' => TIMESTAMP,
                'state' => 0 
        );
        $this->_db->insert ( 'cloudprint_task', $value );
        $this->operate($order[userid], $order[file_id], Xzbbm::operate_print);
    }
    public function Withdraw()
    {
    }
    function PingxxPay($orderNo, $channel, $amount, $subject, $body)
    {
        // $extra 在使用某些渠道的时候，需要填入相应的参数，其它渠道则是 array() .具体见以下代码或者官网中的文档。其他渠道时可以传空值也可以不传。
        $extra = array ();
        switch ($channel)
        {
            case 'alipay_wap' :
                $extra = array (
                        'success_url' => 'http://www.yourdomain.com/success',
                        'cancel_url' => 'http://www.yourdomain.com/cancel' 
                );
                break;
            case 'upmp_wap' :
                $extra = array (
                        'result_url' => 'http://www.yourdomain.com/result?code=' 
                );
                break;
            case 'bfb_wap' :
                $extra = array (
                        'result_url' => 'http://www.yourdomain.com/result?code=' 
                );
                break;
            case 'upacp_wap' :
                $extra = array (
                        'result_url' => 'http://www.yourdomain.com/result?code=' 
                );
                break;
            case 'wx_pub' :
                $extra = array (
                        'open_id' => 'Openid' 
                );
                break;
            case 'wx_pub_qr' :
                $extra = array (
                        'product_id' => 'Productid' 
                );
                break;
        }
        
        \Pingpp\Pingpp::setApiKey ( 'sk_live_88uXLCSu1ur5SGuv5CGu54GO' );
        try
        {
            $ch = \Pingpp\Charge::create ( array (
                    "subject" => $subject,
                    "body" => $body,
                    "amount" => $amount,
                    "order_no" => $orderNo,
                    "currency" => "cny",
                    "extra" => $extra,
                    "channel" => $channel,
                    "client_ip" => $_SERVER ["REMOTE_ADDR"],
                    "app" => array (
                            "id" => "app_9Wn9qPq9WnHOevnP" 
                    ) 
            ) );
            echo $ch;
        }
        catch ( \Pingpp\Error\Base $e )
        {
            header ( 'Status: ' . $e->getHttpStatus () );
            echo ($e->getHttpBody ());
        }
    }
    // charge
    // order_no: required
    // 商户订单号，适配每个渠道对此参数的要求，必须在商户系统内唯一。(alipay: 1-64位， wx: 1-32 位，bfb: 1-20 位，upacp: 8-40 位，推荐使用8-20位)。
    // app[id]: required
    // 支付使用的 app 对象的 id。
    // channel: required
    // 支付使用的第三方支付渠道，取值范围
    // amount: required
    // 订单总金额, 单位为对应币种的最小货币单位，例如：人民币为分（如订单总金额为1元，此处请填100）。
    // client_ip: required
    // 发起支付请求终端的 IP 地址，格式为 IPV4，如: 127.0.0.1。
    // currency: required
    // 三位 iSO 货币代码，目前仅支持人民币 cny。
    // subject: required
    // 商品的标题，该参数最长为 32 个 Unicode 字符。
    // body: required
    // 商品的描述信息，该参数最长为 128 个 Unicode 字符。
    // extra: optional
    // 特定渠道发起交易时需要的额外参数。
    // time_expire: optional
    // 订单失效时间，用 UTC 时间表示。时间范围在订单创建后的 1 分钟到 15 天，默认为 1 天，创建时间以 Ping++ 服务器时间为准。该参数不适用于微信支付渠道。
    // metadata: optional
    // 参考 Metadata 元数据。
    // description: optional
    // 订单附加说明，最多 255 个 Unicode 字符。
    
    // {
    // "id": "ch_L8qn10mLmr1GS8e5OODmHaL4",
    // "object": "charge",
    // "created": 1410834527,
    // "livemode": true,
    // "paid": false,
    // "refunded": false,
    // "app": "app_1234567890abcDEF",
    // "channel": "upmp",
    // "order_no": "123456789",
    // "client_ip": "127.0.0.1",
    // "amount": 100,
    // "amount_settle": 0,
    // "currency": "cny",
    // "subject": "苹果2",
    // "body": "一个又大又红的红富士苹果",
    // "extra":{},
    // "time_paid": null,
    // "time_expire": 1410838127,
    // "time_settle": null,
    // "transaction_no": null,
    // "refunds": {
    // "object": "list",
    // "url": "/v1/charges/ch_L8qn10mLmr1GS8e5OODmHaL4/refunds",
    // "has_more": false,
    // "data": [ ]
    // },
    // "amount_refunded": 0,
    // "failure_code": null,
    // "failure_msg": null,
    // "metadata": { },
    // "credential": {
    // "object": "credential",
    // "wx": { },
    // "alipay": { },
    // "upmp": {
    // "tn": "201409161028470000000",
    // "mode": "01"
    // }
    // },
    // "description": null
    // }
    public function PingxxNotify()
    {
        $this->say ( var_dump ( file_get_contents ( "php://input" ) ) );
        
        $input_data = json_decode ( file_get_contents ( "php://input" ), true );
        if ($input_data ['object'] == 'charge' && $input_data ['paid'] == true)
        {
            $this->say ( "[PingxxNotify]charge-success" );
            $this->say ( $input_data );
            echo 'success'; // response to pingxx
        }
        else if ($input_data ['object'] == 'refund' && $input_data ['succeed'] == true)
        {
            $this->say ( "[PingxxNotify]refund-success" );
            $this->say ( $input_data );
            echo 'success'; // response to pingxx
        }
        else
        {
            $this->say ( "[PingxxNotify]fail" );
            $this->say ( $input_data );
            echo 'fail'; // response to pingxx
        }
    }
    
    /**
     * APP api 获得我的总收益和今天收益
     *
     * @author why
     */
    public function MyWallet()
    {
        $user = $this->GetUser ();
        if ($user [userid] <= 0)
        {
            $this->Error ( "参数错误" );
            return;
        }
        $today_zero_time = strtotime ( "today" );
        $today_porfit = $this->_db->rsArray ( "select sum(amount) as profit from pay_order where file_own_userid=$user[userid] and time > $today_zero_time and state = 'ping_success'" )['profit'];
        $total_porfit = $this->_db->rsArray ( "select sum(amount) as profit from pay_order where file_own_userid=$user[userid] and state = 'ping_success'" )['profit'];
        
        $ret ['today_porfit'] = $today_porfit;
        $ret ['total_porfit'] = $total_porfit;
        
        $this->Ret ( $ret );
    }
    /**
     * WEB test api 获得我的收益相关信息
     *
     * @author why
     */
    public function MyProfit()
    {
        $user = $this->GetUser ();
        if ($user [userid] <= 0)
        {
            $this->Error ( "参数错误" );
            return;
        }
        $ret ['today_porfit'] = sprintf ( "%.2f", rand ( 0, 0 ) / 10 );
        $ret ['total_porfit'] = sprintf ( "%.2f", rand ( 0, 0 ) / 10 );
        $ret ['file_count'] = $this->GetData ( "SELECT count(*) as sum FROM pd_files WHERE userid = {$user [userid]}" )[0]['sum'];
        $ret ['fans_count'] = rand ( 0, 0 );
        
        for($i = 0; $i < 7; $i ++)
        {
            $ret ['avarige_profit_array'] [$i] = rand ( 0, 100 ) / 10;
        }
        for($i = 0; $i < 7; $i ++)
        {
            $ret ['my_profit_array'] [$i] = rand ( 0, 0 ) / 10;
        }
        $this->Ret ( $ret );
    }
}