<?php
if (! defined ( 'IN_SYS' ))
{
    header ( "HTTP/1.1 404 Not Found" );
    die ();
}
class TestPay extends Xzbbm
{
    public function __construct()
    {
        parent::__construct ();
        if ($_GET ['debug'] == 'on')
        {
            $this->msg = json_decode ( $_REQUEST ["msg"], true );
        }
        else
        {
            $this->msg = json_decode ( ( file_get_contents ( "php://input" ) ), true );
        }
        
        $y = date ( 'Y', TIMESTAMP );
        $m = date ( 'm', TIMESTAMP );
        $d = date ( 'd', TIMESTAMP );
        
        $this->file_store_path = "$y/$m/$d";
    }
    private function Error($str)
    {
        $ret = [ 
                "error" => $str 
        ];
        $this->Ret ( $ret );
        exit ();
    }
    private function Ret($ret_msg)
    {
        arrayRecursive ( $ret_msg, 'urlencode', true );
        $ret_msg = urldecode ( json_encode ( $ret_msg ) );
//         $this->say ( $ret_msg );
        if ($_GET ['debug'] == 'on')
        {
            echo $ret_msg;
        }
        else
        {
            echo gzcompress ( $ret_msg );
        }
    }

	private function say($str)
    {
        file_put_contents ( "/data/backend_service/log/testpay.log", "{$this->file_store_path} $str \n", FILE_APPEND );
    }

    public function RecvPayNotify()
    {
		echo "000";
		echo file_get_contents ( "php://input" ) ;
		$this->say(file_get_contents ( "php://input" ));
		echo "111";
		$this->say($this->msg);
		echo "222";
		/*
		foreach ( $this->msg as $key => $value ) {
			echo "$key -> $value. <br>";
		}
		 */
    }
}
