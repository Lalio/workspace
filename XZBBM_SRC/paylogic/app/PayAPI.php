<?php
if (! defined ( 'IN_SYS' ))
{
    header ( "HTTP/1.1 404 Not Found" );
    die ();
}
require_once(dirname(__FILE__) . '/../init.php');

class PayAPI extends Xzbbm
{
//     -----------------+----------------+----------------+---------------+-------------------+-----------------+
//     | Field           | Type           | Null           | Key           | Default           | Extra           |
//     +-----------------+----------------+----------------+---------------+-------------------+-----------------+
//     | id              | int(20)        | NO             | PRI           |                   | auto_increment  |
//     | deal_id         | varchar(128)   | NO             |               |                   |                 |
//     | myvid           | int(20)        | NO             |               |                   |                 |
//     | othervid        | int(20)        | YES            |               |                   |                 |
//     | commodity_type  | int(20)        | NO             |               |                   |                 |
//     | commodity_id    | varchar(20)    | YES            |               |                   |                 |
//     | deal_type       | int(20)        | NO             |               |                   |                 |
//     | price           | int(20)        | NO             |               |                   |                 |
//     | cost_voucher    | int(20)        | NO             |               |                   |                 |
//     | cost_change     | int(20)        | NO             |               |                   |                 |
//     | cost_payment    | int(20)        | NO             |               |                   |                 |
//     | pay_type        | int(20)        | NO             |               |                   |                 |
//     | balance_voucher | int(20)        | NO             |               |                   |                 |
//     | balance_change  | int(20)        | NO             |               |                   |                 |
//     | create_time     | int(11)        | NO             |               |                   |                 |
//     +-----------------+----------------+----------------+---------------+-------------------+-----------------+
   public function Buy()
   {
       // private static final String CHANNEL_UPMP         = "upmp";          // 银联
       // private static final String CHANNEL_WECHAT       = "wx";            // 微信
       // private static final String CHANNEL_ALIPAY       = "alipay";        // 支付宝
       // private static final String CHANNEL_YHQ          = "yhq";           // 优惠券
       // private static final String SEND                 = "send";          // 发送到邮箱
       // private static final String DOWNLOAD             = "download";      // 下载
       // private static final String CLOUND_PRINT_EXPRESS = "express";       // 云印快递
       // private static final String CLOUND_PRINT_BUFFET  = "buffet";        // 云印自取
       
       $file_index = $this->msg[file_index];
       $action = $this->msg[action];
       $email = $this->msg[email];
       $phone = $this->msg[phone];
       $store_id = $this->msg[store_id];// 打印店ID号
       $duplex = $this->msg[duplex];// 0单面 1双面
       $total = $this->msg[total];// 份数
       $receiver_name = $this->msg[receiver_name]; // 收件人
       $address = $this->msg[address];// 收件地址
       $channel = $this->msg[total]; // 付款方式
        
       $user = $this->GetUser();
       $rs_file = $this->_db->rsArray ( "select * from pd_files where file_index = '$file_index' limit 1" );
       if(empty($rs_file) || empty($user))
       {
           $this->Error("参数错误");
           return;
       }
       $this->msg[file_id] =  $rs_file[file_id];
       
       $subject = "$action-$user[userid]";
       $body = "$file_index";
       $order_no = substr(md5(time()), 0, 12);
       $state = "create";//create client_success client_fail ping_fail ping_success
       $this->msg[subject] = $subject;
       $this->msg[body] = $body;
       $this->msg[order_no] = $order_no;
       $this->msg[state] = $state;
   }
    
   function BuyDownload()
   {
       
   }
   
   function BuySend()
   {
       
   }
   
   function BuyExpress()
   {
       
   }
   
   function BuyBuffet()
   {
       
   }
   
   
   
   public function BuyDocument()
   {
       
       
       
       
       $file_real_name = $this->msg ["file_real_name"];
       $rs_file = $this->_db->rsArray ( "select * from pd_files where file_real_name = '$file_real_name' limit 1" );
       $user = $this->GetUser();
       if(empty($rs_file) || empty($user))
       {
           $this->Error("参数错误");
           return;
       }
       
       $deal_id = md5 ( uniqid ( mt_rand (), true ) . microtime () . '1' );
       $myvid = $user[userid];
       $othervid = $rs_file[userid];
       $commodity_type = 0;//商品类型,0可使用代金券，1不可以使用代金券
       $commodity_id = $rs_file[file_id];
       $deal_type = 0;//0是买，1时卖
       $price = $rs_file[today_price];
       
   }
   public function Withdraw()
   {
       
   }
   
   function PingxxPay($orderNo, $channel, $amount, $subject, $body)
   {
       //$extra 在使用某些渠道的时候，需要填入相应的参数，其它渠道则是 array() .具体见以下代码或者官网中的文档。其他渠道时可以传空值也可以不传。
       $extra = array();
//        switch ($channel) {
//            case 'alipay_wap':
//                $extra = array(
//                'success_url' => 'http://www.yourdomain.com/success',
//                'cancel_url' => 'http://www.yourdomain.com/cancel'
//                        );
//                        break;
//            case 'upmp_wap':
//                $extra = array(
//                'result_url' => 'http://www.yourdomain.com/result?code='
//                        );
//                        break;
//            case 'bfb_wap':
//                $extra = array(
//                'result_url' => 'http://www.yourdomain.com/result?code='
//                        );
//                        break;
//            case 'upacp_wap':
//                $extra = array(
//                'result_url' => 'http://www.yourdomain.com/result?code='
//                        );
//                        break;
//            case 'wx_pub':
//                $extra = array(
//                'open_id' => 'Openid'
//                        );
//                        break;
//            case 'wx_pub_qr':
//                $extra = array(
//                'product_id' => 'Productid'
//                        );
//                        break;
//        }
       
       \Pingpp\Pingpp::setApiKey('sk_test_4K8enPCC8GaLH40Gi1mT8CG4');
       try {
           $ch = \Pingpp\Charge::create(
                   array(
                           "subject"   => $subject,
                           "body"      => $body,
                           "amount"    => $amount,
                           "order_no"  => $orderNo,
                           "currency"  => "cny",
                           "extra"     => $extra,
                           "channel"   => $channel,
                           "client_ip" => $_SERVER["REMOTE_ADDR"],
                           "app"       => array("id" => "app_9Wn9qPq9WnHOevnP")
                   )
           );
           echo $ch;
       } catch (\Pingpp\Error\Base $e) {
           header('Status: ' . $e->getHttpStatus());
           echo($e->getHttpBody());
       }
   }
   //charge
//    order_no: required
//    商户订单号，适配每个渠道对此参数的要求，必须在商户系统内唯一。(alipay: 1-64位， wx: 1-32 位，bfb: 1-20 位，upacp: 8-40 位，推荐使用8-20位)。
//    app[id]: required
//    支付使用的 app 对象的 id。
//    channel: required
//    支付使用的第三方支付渠道，取值范围
//    amount: required
//    订单总金额, 单位为对应币种的最小货币单位，例如：人民币为分（如订单总金额为1元，此处请填100）。
//    client_ip: required
//    发起支付请求终端的 IP 地址，格式为 IPV4，如: 127.0.0.1。
//    currency: required
//    三位 iSO 货币代码，目前仅支持人民币 cny。
//    subject: required
//    商品的标题，该参数最长为 32 个 Unicode 字符。
//    body: required
//    商品的描述信息，该参数最长为 128 个 Unicode 字符。
//    extra: optional
//    特定渠道发起交易时需要的额外参数。
//    time_expire: optional
//    订单失效时间，用 UTC 时间表示。时间范围在订单创建后的 1 分钟到 15 天，默认为 1 天，创建时间以 Ping++ 服务器时间为准。该参数不适用于微信支付渠道。
//    metadata: optional
//    参考 Metadata 元数据。
//    description: optional
//    订单附加说明，最多 255 个 Unicode 字符。
   
//    {
//        "id": "ch_L8qn10mLmr1GS8e5OODmHaL4",
//        "object": "charge",
//   "created": 1410834527,
//      "livemode": true,
//        "paid": false,
//        "refunded": false,
//   "app": "app_1234567890abcDEF",
//      "channel": "upmp",
//        "order_no": "123456789",
//        "client_ip": "127.0.0.1",
//   "amount": 100,
//      "amount_settle": 0,
//        "currency": "cny",
//        "subject": "苹果2",
//   "body": "一个又大又红的红富士苹果",
//   "extra":{},
//   "time_paid": null,
//   "time_expire": 1410838127,
//      "time_settle": null,
//        "transaction_no": null,
//        "refunds": {
//            "object": "list",
//                    "url": "/v1/charges/ch_L8qn10mLmr1GS8e5OODmHaL4/refunds",
//                    "has_more": false,
//     "data": [ ]
//    },
//    "amount_refunded": 0,
//    "failure_code": null,
//    "failure_msg": null,
//                "metadata": { },
//   "credential": {
//                "object": "credential",
//                "wx": { },
//                    "alipay": { },
//                    "upmp": {
//          "tn": "201409161028470000000",
//          "mode": "01"
//        }
//      },
//      "description": null
//    }
   public function PingxxNotify()
   {
       $this->say(var_dump(file_get_contents("php://input")));
       
       $input_data = json_decode(file_get_contents("php://input"), true);
       if($input_data['object'] == 'charge'&& $input_data['paid']==true)
       {
           $this->say("charge-success");
           echo 'success';//response to pingxx
       }
       else if($input_data['object'] == 'refund'&& $input_data['succeed']==true)
       {
           $this->say("refund-success");
           echo 'success';//response to pingxx
       }
       else
       {
           $this->say("refund-success");
           echo 'fail';//response to pingxx
       }
        
   }
}