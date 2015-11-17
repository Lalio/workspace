<?php

// 代码风格按照C语言.... 我有点忍受不了自己
// DB的调用多封了一层, 也有点忍受不了了.
// 功能做完之后没事就重构之, 风格与后台里的其它代码对齐... add by walkerwu.
// 所有的函数， 必须保证用户的余额是存在的， 如果不存在， 访问是失败的。
require 'pay_db_operation.php';
// require_once 'init.php';
require_once 'lib/Pingpp.php';

define ( "ERR_DB_NO_RECORD", - 20001 );
define ( "ERR_SHORT_OF_WITHDRAW", - 20002 );
define ( "ERR_PAY_GET_CHARGE", - 20003 );

// 产生支付类型
define ( "DEAL_TYPE_BUY", 1 ); // 买
define ( "DEAL_TYPE_SELL", 2 ); // 卖
define ( "DEAL_TYPE_WITHDRAW", 3 ); // 提现
define ( "DEAL_TYPE_RECHARGE", 4 ); // 充值
define ( "DEAL_TYPE_INVITE_CODE", 5 ); // 邀请码收入
define ( "DEAL_TYPE_BUY_ROLL_BACK", 6 ); // 买家退款 加钱
define ( "DEAL_TYPE_SELL_ROLL_BACK", 7 ); // 卖家退款 减钱
define ( "DEAL_TYPE_PRINT", 8 ); // 打印店收入
                                 
// 支付方式
define ( "PAY_TYPE_WEIXIN", 1 );
define ( "PAY_TYPE_ZFB", 2 );
define ( "PAY_TYPE_BANK", 3 );

// 缺少的功能
class PayLogic
{
    
    // 不支持提现功能~ pingxx没有提现的接口。 mark.
    function OnPayTool_WithDraw($person_vid, $price, $pay_type)
    {
        return 0;
    }
    
    // 获取到支付charge，把这个charge扔给客户端。
    function OnPayTool_Pay($channel, $subject, $body, $amount, $arr_meta, &$arr_charge)
    {
        
        // $amount = 77;
        $orderNo = substr ( md5 ( time () ), 0, 12 );
        
        $extra = array ();
        switch ($channel)
        {
            case 'alipay_wap' :
                $extra = array (
                        'success_url' => 'http://www.baidu.com',
                        'cancel_url' => 'http://www.taobao.com' 
                );
                break;
            case 'upmp_wap' :
                $extra = array (
                        'result_url' => 'http://www.yourdomain.com/result?code=' 
                );
                break;
        }
        
        Pingpp::setApiKey ( "sk_test_4K8enPCC8GaLH40Gi1mT8CG4" );
        try
        {
            $ch = Pingpp_Charge::create ( array (
                    "subject" => $subject,
                    "body" => $body,
                    "amount" => $amount,
                    "order_no" => $orderNo,
                    "currency" => "cny",
                    "extra" => $extra,
                    "channel" => $channel,
                    "client_ip" => $_SERVER ["REMOTE_ADDR"],
                    "app" => array (
                            "id" => "app_1SC08SO4azjDWP4y" 
                    ),
                    
                    // add by walkerwu. 往metadata里丢一些额外数据， 异步通知拿到再操作数据库
                    'metadata' => $arr_meta 
            ) );
            echo "$ch <br>";
        }
        catch ( Pingpp_Error $e )
        {
            header ( 'Status: ' . $e->getHttpStatus () );
            echo ($e->getHttpBody ());
            return ERR_PAY_GET_CHARGE;
        }
        return 0;
    }
    
    // 要把修改余额和擦入dealrecord封装成一个函数, 调用太频繁了..
    function IsIncomeType($type)
    {
        if ($type == DEAL_TYPE_SELL || $type == DEAL_TYPE_INVITE_CODE || $type == DEAL_TYPE_SELL_ROLL_BACK || $type == DEAL_TYPE_PRINT)
            return true;
        else
            return false;
        return true;
    }
    
    // 获取我的余额
    function GetMyBalance($person_vid, &$arr_balance)
    {
        
        // 实时查询昨天凌晨到现在的现金收入
        $objDb = new PayDb ();
        
        $ret = $objDb->_select_deal_record ( $person_vid, $array_result );
        if ($ret != 0)
        {
            echo "walkerwu log, _select_deal_record failed, ret = " . $ret . ", vid = " . $person_vid . "<br>";
            return $ret;
        }
        
        $income_yesterday = 0;
        $change_yesterday = 0;
        $voucher_yesterday = 0;
        
        $time_today = date ( "Y-m-d" );
        // $time_today = "2015-03-15";
        $time_yesterday = date ( "Y-m-d 00:00:00", strtotime ( "-1 day" ) );
        echo "today = " . $time_today . " yesterday = " . $time_yesterday . "<br>";
        foreach ( $array_result as $row )
        {
            $create_time = $row ['create_time'];
            $deal_type = $row ['deal_type'];
            $price = $row ['price'];
            $change_tmp = $row ['cost_change'];
            $voucher_tmp = $row ['cost_voucher'];
            
            if ($this->IsIncomeType ( $deal_type ))
            {
                if ($create_time >= $time_yesterday)
                {
                    $income_yesterday += $price;
                    $change_yesterday += $change_tmp;
                    $voucher_yesterday += $voucher_tmp;
                }
            }
        }
        
        $ret = $objDb->_select_person_balance ( $person_vid, $array_result );
        if ($ret != 0)
        {
            echo "walkerwu log, _select_person_balance failed, ret = " . $ret . ", vid = " . $person_vid . "<br>";
            return $ret;
        }
        
        if (count ( $array_result ) > 0)
        {
            $row = $array_result [0];
            $person_balance_voucher = $row ['person_balance_voucher'];
            $person_balance_change = $row ['person_balance_change'];
            $person_count_frozen_voucher = $row ['person_count_frozen_voucher'];
            $person_count_frozen_change = $row ['person_count_frozen_change'];
        }
        else
        {
            return ERR_DB_NO_RECORD;
        }
        $arr_balance ['person_balance_voucher'] = $row ['person_balance_voucher'];
        $arr_balance ['person_balance_change'] = $row ['person_balance_change'];
        $arr_balance ['person_max_change_withdraw'] = $person_balance_change - $person_count_frozen_change - $change_yesterday;
        
        return 0;
    }
    
    // 获取我的收益
    function GetMyInCome($person_vid, &$arr_income)
    {
        $objDb = new PayDb ();
        
        $ret = $objDb->_select_deal_record ( $person_vid, $array_result );
        if ($ret != 0)
        {
            echo "walkerwu log, _select_deal_record failed, ret = " . $ret . ", vid = " . $person_vid . "<br>";
            return $ret;
        }
        
        $income_total = 0;
        $income_yesterday = 0;
        $change_total = 0;
        $change_yesterday = 0;
        $voucher_total = 0;
        $voucher_yesterday = 0;
        
        $time_today = date ( "Y-m-d" );
        // $time_today = "2015-03-15";
        $time_yesterday = date ( "Y-m-d", strtotime ( "-1 day" ) );
        echo "today = " . $time_today . " yesterday = " . $time_yesterday . "<br>";
        foreach ( $array_result as $row )
        {
            $create_time = $row ['create_time'];
            $deal_type = $row ['deal_type'];
            $price = $row ['price'];
            $change_tmp = $row ['cost_change'];
            $voucher_tmp = $row ['cost_voucher'];
            
            if ($this->IsIncomeType ( $deal_type ))
            {
                if ($create_time >= $time_yesterday && $create_time <= $time_today)
                {
                    $income_yesterday += $price;
                    $change_yesterday += $change_tmp;
                    $voucher_yesterday += $voucher_tmp;
                }
                $income_total += $price;
                $change_total += $change_tmp;
                $voucher_total += $voucher_tmp;
            }
        }
        
        // 拼成数组返回。
        $arr_income ['income_total'] = $income_total;
        $arr_income ['change_total'] = $change_total;
        $arr_income ['voucher_total'] = $voucher_total;
        
        $arr_income ['income_yesterday'] = $income_yesterday;
        $arr_income ['change_yesterday'] = $change_yesterday;
        $arr_income ['voucher_yesterday'] = $voucher_yesterday;
        
        return 0;
    }
    
    // 获取交易记录, 最近30条, 还是每天..
    function GetMyDealRecord($person_vid, &$arr_record)
    {
        $objDb = new PayDb ();
        
        $ret = $objDb->_select_deal_record ( $person_vid, $array_result );
        if ($ret != 0)
        {
            echo "walkerwu log, _select_deal_record failed, ret = " . $ret . ", vid = " . $person_vid . "<br>";
            return $ret;
        }
        
        $arr_record = $array_result;
        return 0;
    }
    
    // 冻结或者解冻. 正数就冻结, 负数就解冻
    function FreezeMyCount($person_vid, $nVoucher, $nChange)
    {
        $objDb = new PayDb ();
        
        $objDb->person_count_frozen_change = $nChange;
        $objDb->person_count_frozen_voucher = $nVoucher;
        
        $ret = $objDb->_update_person_balance ( $person_vid );
        if ($ret != 0)
        {
            echo "walkerwu log, _update_person_balance failed, ret = " . $ret . ", vid = " . $person_vid . "<br>";
            return $ret;
        }
        
        return 0;
    }
    function GeneDealId($person_vid)
    {
        $code = $person_vid . "_" . date ( "Ymdhis" ) . "_" . mt_rand ( 1000, 10000 ) . "_" . mt_rand ( 1000, 10000 );
        echo "dealid = " . $code . "<br>";
        return $code;
    }
    // 发起支付请求.
    // 这里的逻辑最复杂, 要查余额, 插入订单, 修改余额. (如果失败要回退, 这里还没有回退. mark)
    
    // 卖东西出去的收益 .. 直接加记录, 修改余额
    // 收入
    function OnPayIncome($person_vid, $deal_type, $price, $nVoucher, $nChange)
    {
        $objDb = new PayDb ();
        
        $objDb->person_balance_change = $nChange;
        $objDb->person_balance_voucher = $nVoucher;
        
        $ret = $objDb->_update_person_balance ( $person_vid );
        if ($ret != 0)
        {
            echo "walkerwu log, _update_person_balance failed, ret = " . $ret . ", vid = " . $person_vid . "<br>";
            return $ret;
        }
        
        $ret = $objDb->_select_person_balance ( $person_vid, $array_result );
        if ($ret != 0)
        {
            echo "walkerwu log, _select_person_balance failed, ret = " . $ret . ", vid = " . $person_vid . "<br>";
            return $ret;
        }
        if (count ( $array_result ) > 0)
        {
            $row = $array_result [0];
            $person_balance_voucher = $row ['person_balance_voucher'];
            $person_balance_change = $row ['person_balance_change'];
            
            $objDb->balance_voucher = $person_balance_voucher;
            $objDb->balance_change = $person_balance_change;
            
            $objDb->deal_id = $this->GeneDealId ( $person_vid );
            $objDb->myvid = $person_vid;
            $objDb->deal_type = $deal_type;
            $objDb->price = $price;
            $objDb->cost_voucher = $nVoucher;
            $objDb->cost_change = $nChange;
            
            $ret = $objDb->_insert_deal_record ();
            if ($ret != 0)
            {
                echo "walkerwu log, _insert_deal_record failed, ret = " . $ret . ", vid = " . $person_vid . "<br>";
                // 插入失败要回滚余额.. mark...
                return $ret;
            }
        }
        else
        {
            return ERR_DB_NO_RECORD;
        }
        
        return 0;
    }
    
    // 买东西. 填支付类型.. 这里有点恶心.. 有可能 代金券 +　零钱　＋　微信支付的情况．．　恶心呀． type怎么传？ 这里得传两个type.
    // 先默认使用代金券和余额, 不够部分用第三方支付.. 咔咔..
    // 这里还要考虑被冻结的情况..
    // 支出
    // 买东西，操作数据库要独立成一个函数，在notify.php里再异步调用去插入数据库。
    function OnPayOutcome($person_vid, $deal_type, $pay_type, $price, $subject, $body)
    {
        $objDb = new PayDb ();
        
        // 这里如果不对用户加锁的话 ， 会有问题， 因为是异步去减掉余额的。 我勒了个去呀。
        // 无锁状态先走通流程吧
        $ret = $objDb->_select_person_balance ( $person_vid, $array_result );
        if ($ret != 0)
        {
            echo "walkerwu log, _select_person_balance failed, ret = " . $ret . ", vid = " . $person_vid . "<br>";
            return $ret;
        }
        
        if (count ( $array_result ) > 0)
        {
            $row = $array_result [0];
            
            $person_balance_voucher = $row ['person_balance_voucher'];
            $person_balance_change = $row ['person_balance_change'];
            $person_count_frozen_voucher = $row ['person_count_frozen_voucher'];
            $person_count_frozen_change = $row ['person_count_frozen_change'];
            
            $max_pay_voucher = $person_balance_voucher - $person_count_frozen_voucher;
            if ($max_pay_voucher < 0)
                $max_pay_voucher = 0;
            
            $max_pay_change = $person_balance_change - $person_count_frozen_change;
            if ($max_pay_change < 0)
                $max_pay_change = 0;
            
            $pay_voucher = 0;
            $pay_change = 0;
            $pay_payment = 0;
            
            // 计算代金券, 零钱, 三方支付分别要付多少钱.
            $pay_left = $price;
            if ($pay_left > 0)
            {
                $pay_voucher = $pay_left < $max_pay_voucher ? $pay_left : $max_pay_voucher;
                $pay_left -= $pay_voucher;
            }
            if ($pay_left > 0)
            {
                $pay_change = $pay_left < $max_pay_change ? $pay_left : $max_pay_change;
                $pay_left -= $pay_change;
            }
            if ($pay_left > 0)
            {
                $pay_payment = $pay_left;
            }
            
            // 调用支付逻辑。
            $arr_meta = array ();
            $arr_meta ["person_vid"] = $person_vid;
            $arr_meta ["deal_type"] = $deal_type;
            $arr_meta ["pay_type"] = $pay_type;
            $arr_meta ["price"] = $price;
            $arr_meta ["pay_voucher"] = $pay_voucher;
            $arr_meta ["pay_change"] = $pay_change;
            $arr_meta ["pay_payment"] = $pay_payment;
            
            $arr_charge = array ();
            
            // 调用第三方支付去支付, 再由异步通知去调用 callback函数。
            if ($pay_payment > 0)
            {
                if ($pay_type == PAY_TYPE_WEIXIN)
                    $channel = 'alipay_wap';
                else if ($pay_type == PAY_TYPE_ZFB)
                    $channel = 'alipay_wap';
                
                $ret = $this->OnPayTool_Pay ( $channel, $subject, $body, $pay_payment, $arr_meta, $arr_charge );
                
                if ($ret != 0)
                {
                    echo "walkerwu log, OnPayTool_WithDraw failed, ret = " . $ret . ", vid = " . $person_vid . "<br>";
                    return $ret;
                }
                return 0;
            }
            else
            {
                // 自己调用callback函数。
                $arr_charge ["metadata"] = $arr_meta;
                $ret = $this->OnPayOutcomeCallBack ( $arr_charge );
                if ($ret != 0)
                {
                    echo "walkerwu log, OnPayOutcomeCallBack failed, ret = $ret <br>";
                    return $ret;
                }
            }
            
            /*
             * // 下面的操作都在callback里面做
             * // 减掉余额
             * $objDb->person_balance_change = - 1 * $pay_change;
             * $objDb->person_balance_voucher = - 1 * $pay_voucher;
             * $ret = $objDb->_update_person_balance ( $person_vid );
             * if ($ret != 0) {
             * echo "walkerwu log, _update_person_balance failed, ret = " . $ret . ", vid = " . $person_vid . "<br>";
             * return $ret;
             * }
             *
             * $objDb->balance_voucher = $person_balance_voucher - $pay_voucher;
             * $objDb->balance_change = $person_balance_change - $pay_change;
             *
             * $objDb->deal_id = $this->GeneDealId ( $person_vid );
             * $objDb->myvid = $person_vid;
             * $objDb->deal_type = $deal_type;
             * $objDb->price = $price;
             * $objDb->cost_voucher = - 1 * $pay_voucher;
             * $objDb->cost_change = - 1 * $pay_change;
             * $objDb->cost_payment = - 1 * $pay_payment;
             *
             * $ret = $objDb->_insert_deal_record ();
             * if ($ret != 0) {
             * echo "walkerwu log, _insert_deal_record failed, ret = " . $ret . ", vid = " . $person_vid . "<br>";
             * // 插入失败要回滚余额.. mark...
             * return $ret;
             * }
             */
        }
        else
        {
            return ERR_DB_NO_RECORD;
        }
        
        return 0;
    }
    // function OnPayOutcomeCallBack($person_vid, $deal_type, $pay_type, $price) {
    function OnPayOutcomeCallBack($arr_charge)
    {
        $objDb = new PayDb ();
        
        $arr_meta = $arr_charge ["metadata"];
        $pay_type = $arr_meta ["pay_type"];
        
        // 普通的买记录
        if ($pay_type != DEAL_TYPE_RECHARGE)
        {
            $person_vid = $arr_meta ["person_vid"];
            $deal_type = $arr_meta ["deal_type"];
            $pay_type = $arr_meta ["pay_type"];
            $price = $arr_meta ["price"];
            $pay_voucher = $arr_meta ["pay_voucher"];
            $pay_change = $arr_meta ["pay_change"];
            $pay_payment = $arr_meta ["pay_payment"];
            
            $ret = $objDb->_select_person_balance ( $person_vid, $array_result );
            if ($ret != 0)
            {
                echo "walkerwu log, _select_person_balance failed, ret = " . $ret . ", vid = " . $person_vid . "<br>";
                return $ret;
            }
            
            if (count ( $array_result ) > 0)
            {
                $row = $array_result [0];
                
                $person_balance_voucher = $row ['person_balance_voucher'];
                $person_balance_change = $row ['person_balance_change'];
                $person_count_frozen_voucher = $row ['person_count_frozen_voucher'];
                $person_count_frozen_change = $row ['person_count_frozen_change'];
                
                // 已经知道 代金券，零钱， 三方支付分别是多少钱， 操作数据库咯。
                
                // 操作交易记录表， 操作余额表
                $objDb->person_balance_change = - 1 * $pay_change;
                $objDb->person_balance_voucher = - 1 * $pay_voucher;
                $ret = $objDb->_update_person_balance ( $person_vid );
                if ($ret != 0)
                {
                    echo "walkerwu log, _update_person_balance failed, ret = " . $ret . ", vid = " . $person_vid . "<br>";
                    return $ret;
                }
                
                $objDb->balance_voucher = $person_balance_voucher - $pay_voucher;
                $objDb->balance_change = $person_balance_change - $pay_change;
                
                $objDb->deal_id = $this->GeneDealId ( $person_vid );
                $objDb->myvid = $person_vid;
                $objDb->deal_type = $deal_type;
                $objDb->pay_type = $pay_type;
                $objDb->price = $price;
                $objDb->cost_voucher = - 1 * $pay_voucher;
                $objDb->cost_change = - 1 * $pay_change;
                $objDb->cost_payment = - 1 * $pay_payment;
                
                $ret = $objDb->_insert_deal_record ();
                if ($ret != 0)
                {
                    echo "walkerwu log, _insert_deal_record failed, ret = " . $ret . ", vid = " . $person_vid . "<br>";
                    return $ret;
                }
                
                // 操作支付记录表。
                
                $charge_id = $arr_charge ["charge_id"];
                $charge_amount = $arr_charge ["charge_amount"];
                $charge_body = $arr_charge ["charge_body"];
                $charge_subject = $arr_charge ["charge_subject"];
                $charge_channel = $arr_charge ["charge_channel"];
                $charge_order_no = $arr_charge ["charge_order_no"];
                
                if ($charge_id != "")
                {
                    $objDb->charge_id = $charge_id;
                    $objDb->charge_amount = $charge_amount;
                    $objDb->charge_body = $charge_body;
                    $objDb->charge_subject = $charge_subject;
                    $objDb->charge_channel = $charge_channel;
                    $objDb->charge_order_no = $charge_order_no;
                    
                    $ret = $objDb->_insert_charge ();
                    if ($ret != 0)
                    {
                        echo "walkerwu log, _insert_charge failed, ret = " . $ret . ", vid = " . $person_vid . "<br>";
                        return $ret;
                    }
                }
            }
            else
            {
                return ERR_DB_NO_RECORD;
            }
        }
        else
        { // 如果是充值的话, 异步通知的操作稍稍不同, 也是操作 余额, 交易记录,支付记录.
            $person_vid = $arr_meta ["person_vid"];
            $deal_type = $arr_meta ["deal_type"];
            $pay_type = $arr_meta ["pay_type"];
            $price = $arr_meta ["price"];
            $pay_payment = $arr_meta ["pay_payment"];
            
            $charge_id = $arr_charge ["charge_id"];
            $charge_amount = $arr_charge ["charge_amount"];
            $charge_body = $arr_charge ["charge_body"];
            $charge_subject = $arr_charge ["charge_subject"];
            $charge_channel = $arr_charge ["charge_channel"];
            $charge_order_no = $arr_charge ["charge_order_no"];
            
            $nChange = $pay_payment;
            
            $objDb->person_balance_change = $nChange;
            $nVoucher = 0;
            $price = $nChange;
            
            $ret = $objDb->_update_person_balance ( $person_vid );
            if ($ret != 0)
            {
                echo "walkerwu log, _update_person_balance failed, ret = " . $ret . ", vid = " . $person_vid . "<br>";
                return $ret;
            }
            
            $ret = $objDb->_select_person_balance ( $person_vid, $array_result );
            if ($ret != 0)
            {
                echo "walkerwu log, _select_person_balance failed, ret = " . $ret . ", vid = " . $person_vid . "<br>";
                return $ret;
            }
            if (count ( $array_result ) > 0)
            {
                $row = $array_result [0];
                $person_balance_voucher = $row ['person_balance_voucher'];
                $person_balance_change = $row ['person_balance_change'];
                
                $objDb->balance_voucher = $person_balance_voucher;
                $objDb->balance_change = $person_balance_change;
                
                $objDb->deal_id = $this->GeneDealId ( $person_vid );
                $objDb->myvid = $person_vid;
                $objDb->deal_type = DEAL_TYPE_RECHARGE;
                $objDb->pay_type = $pay_type;
                $objDb->price = $price;
                $objDb->cost_voucher = 0;
                $objDb->cost_change = 0;
                $objDb->cost_payment = 1 * $pay_payment;
                
                $ret = $objDb->_insert_deal_record ();
                if ($ret != 0)
                {
                    echo "walkerwu log, _insert_deal_record failed, ret = " . $ret . ", vid = " . $person_vid . "<br>";
                    return $ret;
                }
            }
            else
            {
                return ERR_DB_NO_RECORD;
            }
            
            // 操作支付记录表。
            $charge_id = $arr_charge ["charge_id"];
            $charge_amount = $arr_charge ["charge_amount"];
            $charge_body = $arr_charge ["charge_body"];
            $charge_subject = $arr_charge ["charge_subject"];
            $charge_channel = $arr_charge ["charge_channel"];
            $charge_order_no = $arr_charge ["charge_order_no"];
            
            if ($charge_id != "")
            {
                $objDb->charge_id = $charge_id;
                $objDb->charge_amount = $charge_amount;
                $objDb->charge_body = $charge_body;
                $objDb->charge_subject = $charge_subject;
                $objDb->charge_channel = $charge_channel;
                $objDb->charge_order_no = $charge_order_no;
                
                $ret = $objDb->_insert_charge ();
                if ($ret != 0)
                {
                    echo "walkerwu log, _insert_charge failed, ret = " . $ret . ", vid = " . $person_vid . "<br>";
                    return $ret;
                }
            }
            else
            {
                return ERR_DB_NO_RECORD;
            }
        }
        
        return 0;
    }
    // 提现　
    // 提现功能不可用。 pingxx没有提供提现的接口
    function OnWithDraw($person_vid, $change_withdraw, $pay_type)
    {
        $ret = $this->GetMyBalance ( $person_vid, $arr_balance );
        if ($ret != 0)
        {
            echo "walkerwu log, GetMyBalance failed, ret = " . $ret . ", vid = " . $person_vid . "<br>";
            return $ret;
        }
        $max_change = $arr_balance ['GetMaxChangeWithDraw'];
        
        if ($change_withdraw > $max_change)
            return ERR_SHORT_OF_WITHDRAW;
        $ret = $this->OnPayTool_WithDraw ( $person_vid, $price, $pay_type );
        if ($ret != 0)
        {
            echo "walkerwu log, OnPayTool_WithDraw failed, ret = " . $ret . ", vid = " . $person_vid . "<br>";
            return $ret;
        }
        
        $objDb = new PayDb ();
        
        $nChange = - 1 * $change_withdraw;
        $objDb->person_balance_change = $nChange;
        $objDb->pay_type = $pay_type;
        $nVoucher = 0;
        $price = $change_withdraw;
        
        $ret = $objDb->_update_person_balance ( $person_vid );
        if ($ret != 0)
        {
            echo "walkerwu log, _update_person_balance failed, ret = " . $ret . ", vid = " . $person_vid . "<br>";
            return $ret;
        }
        
        $ret = $objDb->_select_person_balance ( $person_vid, $array_result );
        if ($ret != 0)
        {
            echo "walkerwu log, _select_person_balance failed, ret = " . $ret . ", vid = " . $person_vid . "<br>";
            return $ret;
        }
        if (count ( $array_result ) > 0)
        {
            $row = $array_result [0];
            $person_balance_voucher = $row ['person_balance_voucher'];
            $person_balance_change = $row ['person_balance_change'];
            
            $objDb->balance_voucher = $person_balance_voucher;
            $objDb->balance_change = $person_balance_change;
            
            $objDb->deal_id = $this->GeneDealId ( $person_vid );
            $objDb->myvid = $person_vid;
            $objDb->deal_type = DEAL_TYPE_WITHDRAW;
            $objDb->price = $price;
            $objDb->cost_voucher = $nVoucher;
            $objDb->cost_change = $nChange;
            
            $ret = $objDb->_insert_deal_record ();
            if ($ret != 0)
            {
                echo "walkerwu log, _insert_deal_record failed, ret = " . $ret . ", vid = " . $person_vid . "<br>";
                // 插入失败要回滚余额.. mark...
                return $ret;
            }
        }
        else
        {
            return ERR_DB_NO_RECORD;
        }
        
        return 0;
    }
    
    // 充值, 直接加余额. 要知道用哪种type.
    function OnRecharge($person_vid, $pay_type, $nChange)
    {
        
        // 调用支付逻辑。
        $arr_meta = array ();
        $arr_meta ["person_vid"] = $person_vid;
        $arr_meta ["deal_type"] = DEAL_TYPE_RECHARGE;
        $arr_meta ["pay_type"] = $pay_type;
        $arr_meta ["price"] = $nChange;
        $arr_meta ["pay_payment"] = $nChange;
        
        $arr_charge = array ();
        
        // 调用第三方支付去支付, 再由异步通知去调用 callback函数。
        if ($pay_payment > 0)
        {
            if ($pay_type == PAY_TYPE_WEIXIN)
                $channel = 'alipay_wap';
            else if ($pay_type == PAY_TYPE_ZFB)
                $channel = 'alipay_wap';
            
            $ret = $this->OnPayTool_Pay ( $channel, "充值", "充值", $nChange, $arr_meta, $arr_charge );
            if ($ret != 0)
            {
                echo "walkerwu log, OnPayTool_Pay failed, ret = " . $ret . ", vid = " . $person_vid . "<br>";
                return $ret;
            }
            return 0;
        }
        
        return 0;
    }
    
    // 还有买家退款, 卖家退款... 直接操作余额, 把余额弄成负数也没问题.
    // 这里的退款只是在自己平台内退款操作,而不涉及第三方平台的退款.
    function OnRefund($vid_add, $vid_sub, $nVoucherTotal, $nChangeTotal)
    {
        // 自己调用callback函数。
        $arr_meta = array ();
        $arr_meta ["vid_add"] = $vid_add;
        $arr_meta ["vid_sub"] = $vid_sub;
        $arr_meta ["nVoucherTotal"] = $nVoucherTotal;
        $arr_meta ["nChangeTotal"] = $nChangeTotal;
        $ret = $this->OnRefundCallBack ( $arr_meta );
        if ($ret != 0)
        {
            echo "walkerwu log, OnPayOutcomeCallBack failed, ret = $ret <br>";
            return $ret;
        }
        return 0;
    }
    
    //
    function OnRefundCallBack($arr_meta)
    {
        $vid_add = $arr_meta ["vid_add"];
        $vid_sub = $arr_meta ["vid_sub"];
        $$nVoucherTotal = $arr_meta ["nVoucherTotal"];
        $nChangeTotal = $arr_meta ["nChangeTotal"];
        
        $objDb = new PayDb ();
        
        $person_vid = $vid_sub;
        $nVoucher = $nVoucherTotal * - 1;
        $nChange = $nChangeTotal * - 1;
        $objDb->person_balance_change = $nChange;
        $objDb->person_balance_voucher = $nVoucher;
        $price = $nVoucherTotal + $nChangeTotal;
        
        $ret = $objDb->_update_person_balance ( $person_vid );
        if ($ret != 0)
        {
            echo "walkerwu log, _update_person_balance failed, ret = " . $ret . ", vid = " . $person_vid . "<br>";
            return $ret;
        }
        
        $ret = $objDb->_select_person_balance ( $person_vid, $array_result );
        if ($ret != 0)
        {
            echo "walkerwu log, _select_person_balance failed, ret = " . $ret . ", vid = " . $person_vid . "<br>";
            return $ret;
        }
        if (count ( $array_result ) > 0)
        {
            $row = $array_result [0];
            $person_balance_voucher = $row ['person_balance_voucher'];
            $person_balance_change = $row ['person_balance_change'];
            
            $objDb->balance_voucher = $person_balance_voucher;
            $objDb->balance_change = $person_balance_change;
            
            $objDb->deal_id = $this->GeneDealId ( $person_vid );
            $objDb->myvid = $person_vid;
            $objDb->deal_type = DEAL_TYPE_SELL_ROLL_BACK;
            $objDb->price = $price;
            $objDb->cost_voucher = $nVoucher;
            $objDb->cost_change = $nChange;
            
            $ret = $objDb->_insert_deal_record ();
            if ($ret != 0)
            {
                echo "walkerwu log, _insert_deal_record failed, ret = " . $ret . ", vid = " . $person_vid . "<br>";
                return $ret;
            }
        }
        else
        {
            return ERR_DB_NO_RECORD;
        }
        
        $person_vid = $vid_add;
        $nVoucher = $nVoucherTotal * 1;
        $nChange = $nChangeTotal * 1;
        $objDb->person_balance_change = $nChange;
        $objDb->person_balance_voucher = $nVoucher;
        $price = $nVoucherTotal + $nChangeTotal;
        
        $ret = $objDb->_update_person_balance ( $person_vid );
        if ($ret != 0)
        {
            echo "walkerwu log, _update_person_balance failed, ret = " . $ret . ", vid = " . $person_vid . "<br>";
            return $ret;
        }
        
        $ret = $objDb->_select_person_balance ( $person_vid, $array_result );
        if ($ret != 0)
        {
            echo "walkerwu log, _select_person_balance failed, ret = " . $ret . ", vid = " . $person_vid . "<br>";
            return $ret;
        }
        if (count ( $array_result ) > 0)
        {
            $row = $array_result [0];
            $person_balance_voucher = $row ['person_balance_voucher'];
            $person_balance_change = $row ['person_balance_change'];
            
            $objDb->balance_voucher = $person_balance_voucher;
            $objDb->balance_change = $person_balance_change;
            
            $objDb->deal_id = $this->GeneDealId ( $person_vid );
            $objDb->myvid = $person_vid;
            $objDb->deal_type = DEAL_TYPE_BUY_ROLL_BACK;
            $objDb->price = $price;
            $objDb->cost_voucher = $nVoucher;
            $objDb->cost_change = $nChange;
            
            $ret = $objDb->_insert_deal_record ();
            if ($ret != 0)
            {
                echo "walkerwu log, _insert_deal_record failed, ret = " . $ret . ", vid = " . $person_vid . "<br>";
                return $ret;
            }
        }
        else
        {
            return ERR_DB_NO_RECORD;
        }
        
        return 0;
    }
}

$objLogic = new PayLogic ();

/*
 *
 * // ok
 * $ret = $objLogic->GetMyBalance(999, $arr_balance);
 * foreach($arr_balance as $key=>$value)
 * echo "$key -> $value. <br>";
 *
 * // ok
 * $ret = $objLogic->FreezeMyCount(999, 1, 2);
 *
 * // ok
 *
 *
 * $ret = $objLogic->OnPayIncome(999 , DEAL_TYPE_SELL, 500, 400, 100);
 *
 *
 * // ok
 * $_COOKIEret = $objLogic->GetMyInCome(999, $arr_income);
 * foreach($arr_income as $key=>$value)
 * echo "$key -> $value. <br>";
 *
 * // ok
 * $ret = $objLogic->GetMyDealRecord(999, $arr_record);
 * foreach($arr_record as $row){
 * foreach($row as $key=>$value)
 * echo "$key -> $value. <br>";
 * }
 *
 * //PAY_TYPE_WEIXIN
 * // 还没有使用类型区分不使用代金券的情况， 嘻嘻。
 * $ret = $objLogic->OnPayOutcome(999, DEAL_TYPE_BUY, PAY_TYPE_WEIXIN, 10000);
 * echo "buy ret = " . $ret;
 *
 * $ret = $objLogic->OnWithDraw(999, 50, PAY_TYPE_WEIXIN);
 * echo "withdraw ret = " . $ret;
 *
 * $ret = $objLogic->OnRecharge(999, PAY_TYPE_WEIXIN, 1000);
 * echo "recharge ret = " . $ret;
 *
 * $ret = $objLogic->OnRefund(998, 999, 10, 20);
 * echo "recharge ret = " . $ret;
 */

// $ret = $objLogic->OnPayTool_Pay ( PAY_TYPE_ZFB, "walkerwu subject", "walkerwu body", 77, $arr_chage );

$ret = $objLogic->OnPayOutcome ( 999, DEAL_TYPE_BUY, PAY_TYPE_ZFB, 100000, "walkerwu subject", "walkerwu body" );
if ($ret == 0)
{
    echo "OnPayOutcome ok";
}
else
    echo "OnPayOutcome fail";