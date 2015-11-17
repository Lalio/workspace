<?php
// echo "here is walkerwu test...2222" . "<br />";
class PayDb
{
    
    // 交易记录表 字段
    var $deal_id;
    var $myvid;
    var $othervid;
    var $commodity_type;
    var $commodity_id; // '商品id',
    var $deal_type; // '买还是卖',
    var $price;// price = cost_voucher + cost_change + cost_payment
    var $cost_voucher; // 代金券
    var $cost_change; // 零钱
    var $cost_payment; // 第三方支付的钱
    var $pay_type;
    var $balance_voucher; // '存商品快照',代金券
    var $balance_change; // '存商品快照',零钱
    var $create_time;
    var $ref_pay_id; // 新增一个支付id. 可以为空。 可以关联到具体的支付信息 。 mark 20150321 16:25//TODO
    var $ref_refund_id; // 退款id. 因为跟支付是不同的表， 所以再建个id咯。 写起来不用判断来判断去//TODO
                        
    // 个人余额表字段 这个应该在用户注册时就插入一条记录.
    var $person_vid;
    var $person_balance_voucher; // 代金券余额
    var $person_balance_change; // 个人零钱余额
    var $person_count_frozen_voucher; // 被冻结的代金券
    var $person_count_frozen_change; // 被冻结的零钱
    var $person_count_withdraw; // 可提现的余额, 不存, 实时计算.
                                
    // 支付记录表
    var $charge_id;
    var $charge_subject;
    var $charge_body;
    var $charge_amount;
    var $charge_order_no;
    var $charge_channel;
    var $charge_meta;
    var $charge_create_time;
    
    // 退款记录表
    var $refund_id;
    var $refund_amount;
    var $refund_description;
    var $refund_meta;
    var $refund_create_time;
    function PayDb()
    {
        $con = mysql_connect ( "localhost", "root", "" );
        if (! $con)
        {
            die ( 'Could not connect db: ' . mysql_error () );
        }
        echo ("connect db ok" . "<br />" . "it is ok" . "<br />");
        
        mysql_select_db ( "pay", $con );
        
        $this->deal_id = "";
        $this->myvid = 0;
        $this->othervid = 0;
        $this->commodity_type = 0;
        $this->commodity_id = "";
        $this->deal_type = 0;
        $this->price = 0;
        $this->cost_voucher = 0;
        $this->cost_change = 0;
        $this->cost_payment = 0;
        $this->pay_type = 0;
        $this->balance_voucher = 0;
        $this->balance_change = 0;
        $this->pay_id = "";
        $this->refund_id = "";
        
        $this->person_vid = 0;
        $this->person_balance_voucher = 0; // 代金券余额
        $this->person_balance_change = 0; // 个人零钱余额
        $this->person_count_frozen_voucher = 0; // 被冻结的金额.
        $this->person_count_frozen_change = 0; // 被冻结的金额.
        $this->person_count_withdraw = 0; // 可提现的余额, 不存, 实时计算.
        
        $this->charge_id = "";
        $this->charge_subject = "";
        $this->charge_body = "";
        $this->charge_amount = "";
        $this->charge_order_no = "";
        $this->charge_channel = "";
        $this->charge_meta = "";
        
        // 退款记录表
        $this->refund_id = "";
        $this->refund_amount = "";
        $this->refund_description = "";
        $this->refund_meta = "";
    }
    function _insert_deal_record()
    {
        date_default_timezone_set ( "Asia/Shanghai" );
        
        //
        if (abs ( $this->price ) != abs ( $this->cost_voucher + $this->cost_change + $this->cost_payment ))
        {
            echo "insert fail.   price, cost_voucher, cost_change, cost_payment" . $this->price . " " . $this->cost_voucher . " " . $this->cost_change . " " . $this->cost_payment;
            return - 2;
        }
        
        $create_time_stamp = time ();
        $create_time_date = date ( "Y-m-d H:i:s", $create_time_stamp );
        $sql = "INSERT INTO dealrecord (deal_id, myvid, othervid, commodity_type, commodity_id, deal_type, price,  cost_voucher,   
			cost_change, cost_payment, pay_type, balance_voucher, balance_change, create_time) VALUES ('$this->deal_id', $this->myvid, $this->othervid, 
			$this->commodity_type, '$this->commodity_id', $this->deal_type, $this->price, $this->cost_voucher, $this->cost_change, 
			$this->cost_payment, $this->pay_type, $this->balance_voucher, $this->balance_change, '$create_time_date')";
        echo "sql = " . "$sql" . "<br />";
        if (! mysql_query ( $sql ))
        {
            die ( "Err" . mysql_error () );
        }
        else
        {
            echo "insert on record ok" . "<br />";
        }
        return 0;
    }
    function _delete_deal_record($deal_id)
    {
        $sql = "DELETE FROM dealrecord WHERE deal_id = '$deal_id'";
        echo "sql = " . "$sql" . "<br />";
        if (! mysql_query ( $sql ))
        {
            die ( "Err" . mysql_error () );
        }
        else
        {
            echo "delete " . $deal_id . " on record ok" . "<br />";
        }
        return 0;
    }
    function _select_deal_record($myvid, &$array_result)
    {
        $sql = "SELECT * FROM dealrecord WHERE myvid = $myvid";
        echo "sql = " . "$sql" . "<br />";
        if (! ($result = mysql_query ( $sql )))
        {
            die ( "Err" . mysql_error () );
        }
        else
        {
            // 这里可以访问到result吧? 可以。
            $rows = array ();
            while ( $row = mysql_fetch_array ( $result ) )
            {
                // echo "row = " . "$row" . "<br />";
                foreach ( $row as $key => $value )
                    echo "$key = $value ";
                echo "<br>";
                array_push ( $rows, $row );
            }
        }
        $array_result = $rows;
        return 0;
    }
    function _insert_person_balance()
    {
        $sql = "INSERT INTO person_balance (person_vid, person_balance_voucher, person_balance_change, person_count_frozen_voucher, person_count_frozen_change)  
		VALUES ($this->person_vid, $this->person_balance_voucher, $this->person_balance_change, $this->person_count_frozen_voucher, $this->person_count_frozen_change)";
        echo "sql = " . "$sql" . "<br />";
        if (! mysql_query ( $sql ))
        {
            die ( "Err" . mysql_error () );
        }
        else
        {
            echo "insert on record ok" . "<br />";
        }
        return 0;
    }
    function _update_person_balance($person_vid)
    {
        $sql = "UPDATE person_balance SET person_balance_voucher = person_balance_voucher + $this->person_balance_voucher, 
		person_balance_change = person_balance_change + $this->person_balance_change, 
		person_count_frozen_voucher = person_count_frozen_voucher + $this->person_count_frozen_voucher, 
		person_count_frozen_change = person_count_frozen_change + $this->person_count_frozen_change
		WHERE person_vid = $person_vid";
        echo "sql = " . "$sql" . "<br />";
        if (! mysql_query ( $sql ))
        {
            die ( "Err" . mysql_error () );
        }
        else
        {
            echo "update" . $person_vid . " on record ok" . "<br />";
        }
        return 0;
    }
    function _select_person_balance($person_vid, &$array_result)
    {
        $sql = "SELECT * FROM person_balance WHERE person_vid = $person_vid";
        echo "sql = " . "$sql" . "<br />";
        if (! ($result = mysql_query ( $sql )))
        {
            die ( "Err" . mysql_error () );
        }
        else
        {
            // 这里可以访问到result吧?
            
            $rows = array ();
            while ( $row = mysql_fetch_array ( $result ) )
            {
                // echo "row = " . "$row" . "<br />";
                foreach ( $row as $key => $value )
                    echo "$key = $value ";
                echo "<br>";
                array_push ( $rows, $row );
            }
        }
        $array_result = $rows;
        return 0;
    }
    
    // 第三方支付记录
    function _insert_charge()
    {
        $create_time_stamp = time ();
        $create_time_date = date ( "Y-m-d H:i:s", $create_time_stamp );
        $sql = "INSERT INTO charge_record (charge_id, charge_subject, charge_body, charge_amount, charge_order_no, charge_channel, charge_meta, charge_create_time) 
		VALUES ($this->charge_id, $this->charge_subject, $this->charge_body, $this->charge_amount, $this->charge_order_no, $this->charge_channel, $this->charge_meta, $this->charge_create_time)";
        echo "sql = " . "$sql" . "<br />";
        if (! mysql_query ( $sql ))
        {
            die ( "Err" . mysql_error () );
        }
        else
        {
            echo "insert on record ok" . "<br />";
        }
        return 0;
    }
    function _select_charge($charge_id, &$array_result)
    {
        $sql = "SELECT * FROM charge_record WHERE charge_id = $charge_id";
        echo "sql = " . "$sql" . "<br />";
        if (! ($result = mysql_query ( $sql )))
        {
            die ( "Err" . mysql_error () );
        }
        else
        {
            $rows = array ();
            while ( $row = mysql_fetch_array ( $result ) )
            {
                // echo "row = " . "$row" . "<br />";
                foreach ( $row as $key => $value )
                    echo "$key = $value ";
                echo "<br>";
                array_push ( $rows, $row );
            }
        }
        $array_result = $rows;
        return 0;
    }
    
    // 第三方退款记录。
    function _insert_refund()
    {
        $create_time_stamp = time ();
        $create_time_date = date ( "Y-m-d H:i:s", $create_time_stamp );
        $sql = "INSERT INTO refund_record (refund_id, refund_amount, refund_description, refund_meta, refund_create_time) 
		VALUES ($this->refund_id, $this->refund_amount, $this->refund_description, $this->refund_meta, $create_time_date)";
        echo "sql = " . "$sql" . "<br />";
        if (! mysql_query ( $sql ))
        {
            die ( "Err" . mysql_error () );
        }
        else
        {
            echo "insert on record ok" . "<br />";
        }
        return 0;
    }
    function _select_refund($refund_id, &$array_result)
    {
        $sql = "SELECT * FROM refund_record WHERE refund_id = $refund_id";
        echo "sql = " . "$sql" . "<br />";
        if (! ($result = mysql_query ( $sql )))
        {
            die ( "Err" . mysql_error () );
        }
        else
        {
            $rows = array ();
            while ( $row = mysql_fetch_array ( $result ) )
            {
                // echo "row = " . "$row" . "<br />";
                foreach ( $row as $key => $value )
                    echo "$key = $value ";
                echo "<br>";
                array_push ( $rows, $row );
            }
        }
        $array_result = $rows;
        return 0;
    }
}

// $objRecord = new PayDb ();

//$objRecord->person_vid = 998;
//$objRecord->_insert_person_balance();
/*
//$objRecord->delete_deal_record ('deal_id_999');
$objRecord->person_count_frozen_voucher = 1;
$objRecord->_update_person_balance(999);
$ret = $objRecord->_select_person_balance(999, $rows);
foreach($rows as $row){
	foreach($row as $key=>$value)	
		echo "$key -> $value. <br>";
}
*/
	
