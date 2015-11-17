<?php
/**
 * @name:	class.UserLocation.php
 * @todo: 	用户所属地区
 * @author:	zhys9(jingki) @ 2008-9-2
 	<code>
 		//charset utf-8
 		UserLocation::NameToCode("广东省", "深圳市", "宝安区");
 
 		$s = UserLocation::CodeToName(1094389);
 		print_r($s);
 	</code>
 *
 */

class UserLocation {
    
    const API_HOST = 'reg.56.com';
    const API_PORT = 80;
    const API_ROOT = 'newreg/register/index.php';
    private static $parameters = array ('action' => 'register' );
    
    /**
     * @name NameToCode
     * @author zhys9
     * @todo 把地名转换为地区码； 注： utf-8 字符集
     * @param string $province 省	e.g. 广东省
     * @param string $city 市	广州市
     * @param string $area 区	天河区
     * @return int 地区代码
     *
     */
    public static function NameToCode($province, $city, $area) {
        $query_str = http_build_query ( self::$parameters );
        $query_str .= sprintf ( '&do=getZip&province=%s&city=%s&area=%s', base64_encode ( $province ), base64_encode ( $city ), base64_encode ( $area ) );
        $rs = Http::Get ( self::API_HOST, self::API_ROOT . '?' . $query_str, self::API_PORT );
        if (is_numeric ( $rs )) {
            return $rs;
        } else {
            return false;
        }
    
    }
    
    /**
     * @name CodeToName
     * @author zhys9
     * @param int $code 地区代码
     * @param mixed
     *
     */
    public static function CodeToName($code) {
        $query_str = http_build_query ( self::$parameters );
        $query_str .= sprintf ( '&do=getArea&zip=%d', $code );
        $rs = Http::Get ( self::API_HOST, self::API_ROOT . '?' . $query_str, self::API_PORT );
        $rs = unserialize ( $rs );
        if (is_array ( $rs )) {
            unset ( $rs ['id'] );
            return $rs;
        } else {
            return false;
        }
    }
    
    /**
     * @todo 根据地区code数组获取多地区名称
     * @author ddshine
     * @param $codeArray = array(
     * 0=>2334
     * );
     * @return Array
(
    [2334] => Array
        (
            [id] => 1
            [postalcode] => 10010
            [province] => 内蒙古
            [city] => 呼和浩特市
            [county] => 新城区
            [town] => 机场辅路
        )

)

     */
    public static function CodeArrayToName($codeArray) {
        $codeStr = implode ( ',', $codeArray );
        $query_str = http_build_query ( self::$parameters );
        $query_str .= '&do=getAreas&zip=' . $codeStr;
        $rs = Http::Get ( self::API_HOST, self::API_ROOT . '?' . $query_str, self::API_PORT );
        $rs = unserialize ( $rs );
        if (is_array ( $rs )) {
            return $rs;
        } else {
            return false;
        }
    }

}
?>