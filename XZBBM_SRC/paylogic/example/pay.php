<?php
/* *
 * Ping++ Server SDK
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写, 并非一定要使用该代码。
 * 该代码仅供学习和研究 Ping++ SDK 使用，只是提供一个参考。
*/

require_once(dirname(__FILE__) . '/../init.php');
$input_data = json_decode(file_get_contents('php://input'), true);
$input_data['amount'] = 888;
if (empty($input_data['channel']) || empty($input_data['amount'])) {
    exit();
}
$channel = strtolower($input_data['channel']);
$amount = $input_data['amount'];
$orderNo = substr(md5(time()), 0, 12);

// public String file_index;
// public String action;
// public String email;
// public String phone;
// public String store_id;     // 打印店ID号
// public int    duplex;       // 0单面 1双面
// public int    total;        // 份数
// public String receiver_name; // 收件人
// public String address;      // 收件地址
// public String channel;      // 付款方式

// private static final String CHANNEL_UPMP         = "upmp";          // 银联
// private static final String CHANNEL_WECHAT       = "wx";            // 微信
// private static final String CHANNEL_ALIPAY       = "alipay";        // 支付宝
// private static final String CHANNEL_YHQ          = "yhq";           // 优惠券
// private static final String SEND                 = "send";          // 发送到邮箱
// private static final String DOWNLOAD             = "download";      // 下载
// private static final String CLOUND_PRINT_EXPRESS = "express";       // 云印快递
// private static final String CLOUND_PRINT_BUFFET  = "buffet";        // 云印自取

//$extra 在使用某些渠道的时候，需要填入相应的参数，其它渠道则是 array() .具体见以下代码或者官网中的文档。其他渠道时可以传空值也可以不传。
$extra = array();
switch ($channel) {
    case 'alipay_wap':
        $extra = array(
            'success_url' => 'http://www.yourdomain.com/success',
            'cancel_url' => 'http://www.yourdomain.com/cancel'
        );
        break;
    case 'upmp_wap':
        $extra = array(
            'result_url' => 'http://www.yourdomain.com/result?code='
        );
        break;
    case 'bfb_wap':
        $extra = array(
            'result_url' => 'http://www.yourdomain.com/result?code='
        );
        break;
    case 'upacp_wap':
        $extra = array(
            'result_url' => 'http://www.yourdomain.com/result?code='
        );
        break;
    case 'wx_pub':
        $extra = array(
            'open_id' => 'Openid'
        );
        break;
    case 'wx_pub_qr':
        $extra = array(
            'product_id' => 'Productid'
        );
        break;
}

\Pingpp\Pingpp::setApiKey('sk_test_4K8enPCC8GaLH40Gi1mT8CG4');
try {
    $ch = \Pingpp\Charge::create(
        array(
            "subject"   => "Your Subject",
            "body"      => "Your Body",
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
