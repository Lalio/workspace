<?php
/**
 * @name:	class.Db.php
 * @todo: 	Mysql数据库操作类库
 *
 */
class Db {
    protected $_config = array ('host' => '127.0.0.1', 'database' => null, 'username' => null, 'password' => null );
    #@ class cache
    protected $_rs;
    protected $_conn; //_connection
    protected $_sql;
    protected $_insertId;
    protected $_queryTimes;
    #db charset
    protected $_charset = 'utf8'; //,'utf8','gbk';
    /**
     * @desc 字段对应的类型，避免隐式类型转换，例如字符型要用’’，数字型不要使用’’，注意，使用非“?”绑定值时才有效<br />
     * 目前只区分数字型（numeric不加引号）和非数字型（char加引号）
     * @example 不加引号array('id'=>'int','times'=>'numeric')
     * @example 加引号array('date'=>'datetime','pic'=>'char',)
     * @var array 
     */
    protected $_field_type = array ();
    /**
     * @desc select时的字段列表，格式如：array('SEL_all'=>'id,title,url,pic...', 'SEL_notify'=>'id,title,url')，尽量避免使用*<br />
     * 建议在配置文件中添加配置，且以大写字母做前缀以示区分
     * @example 
     * <pre>
     * Core::$configs['db']['db1']=array('_fields'=>array('SEL_all'=>'id,title,url,pic'),'host'=>'','username'=>'','password'=>'','database'=>'','_charset'=>'');
     * Code::InitDb('db1');
     * Code::$db['db1']->select('SEL_all','hotvideo',false,false,'dateline desc,id desc',false,false,true)
     * </pre>
     * @var array
     */
    protected $_fields = array ();
    
    /**
     * @todo: 初始化，传入配置参数
     * @example 参考config/inc.System.php 的 Core::$configs['db']
     * @author:	Melon`` @ 2010
     *
     */
    public function __construct($config, $set = false) {
        $this->_config = $config;
        $this->setFieldType ( $config ['_field_type'] );
        $this->setFields ( $config ['_fields'] );
        if ($set) {
            $key = array ('_cache', '_cacheTime', '_charset' );
            foreach ( $set as $k => $v ) {
                if (in_array ( $k, $key ))
                    $this->$k = $v;
            }
        }
    }
    
    /**
     * @desc 简单查询
     * @param string $select 查询的字段列表，如果配置中有_fields，也可以直接传_fields中的key
     * @param string $from 数据来源（表）
     * @param mixed $where 条件，可选
     * @param mixed $group_by 聚类查询 ，可选
     * @param mixed $order_by 排序 ，可选
     * @param mixed $limit 限制返回记录数，可选，默认为1
     * @param mixed $bind 绑定值列表，可选
     * @param bool $single 如果为true则返回单行数据格式，默认为返回多行数据格式
     * @example <pre>
     * Code::$db['db1']->select('SEL_all','hotvideo',false,false,'dateline desc,id desc',1,false,true)
     * Code::$db['db1']->select('id,title','hotvideo','review>:rev',false,'dateline desc,id desc',10,array('rev'=>10),false)
     * </pre>
     */
    public function select($select, $from, $where = false, $group_by = false, $order_by = false, $limit = false, $bind = false, $single = false) {
        $sql = "SELECT " . ($this->_fields [$select] ? $this->_fields [$select] : $select);
        $sql += " FROM " . $from;
        $where && ($sql += " WHERE " . $where);
        $group_by && ($sql += " GROUP BY " . $group_by);
        $order_by && ($sql += " ORDER BY " . $order_by);
        $sql += " LIMIT " . ($limit ? $limit : 1);
        if ($single) {
            return $this->RsArray ( $sql, $bind );
        } else {
            return $this->DataArray ( $sql, $bind );
        }
    }
    
    /**
     * @desc 设置select字段列表
     * @param array $array
     */
    public function setFields($array) {
        ! empty ( $array ) && is_array ( $array ) && ($this->_fields = $array);
    }
    /**
     * @desc 获取select字段列表
     * @param array $array
     */
    public function getFields($name = 'ALL_FIELDS') {
        return $name == 'ALL_FIELDS' ? $this->_fields : $this->_fields [$name];
    }
    /**
     * @desc 设置字段类型
     * @param array $array
     */
    public function setFieldType($array) {
        ! empty ( $array ) && is_array ( $array ) && ($this->_field_type = $array);
    }
    /**
     * @desc 获取字段类型
     * @param array $array
     */
    public function getFieldType($name = 'ALL_FIELD_TYPE') {
        return $name == 'ALL_FIELD_TYPE' ? $this->_field_type : $this->_field_type [$name];
    }
    
    /**
     * @todo: 建立连接，不执行查询是不会建立连接的。
     * @author:	Melon`` @ 2010
     *
     */
    protected function connect() {
        if (! $this->_conn) {
            $this->_conn = mysql_connect ( $this->_config ['host'], $this->_config ['username'], $this->_config ['password'], 0, MYSQL_CLIENT_IGNORE_SPACE ) or die ( "sorry, server is busy now." );
            
            mysql_select_db ( $this->_config ['database'], $this->_conn );
            mysql_query ( "SET NAMES " . $this->_charset, $this->_conn );
        }
        return $this->_conn;
    }
    protected function formatSql($sql, $bind) {
        $this->connect ();
        if (is_array ( $bind ) and ! strstr ( $sql, "?" )) {
            $sql = $this->bindValue ( $sql, $bind );
        } else {
            $sql = $this->quoteInto ( $sql, $bind );
        }
        return $sql;
    }
    protected function bindValue($sql, $bind) {
        $sqlArray = preg_split ( "/(\:[A-Za-z0-9_]+)\b/", $sql, - 1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY );
        foreach ( $sqlArray as &$v ) {
            if ($v [0] == ':')
                $v = $this->quote ( $bind [substr ( $v, 1 )], substr ( $v, 1 ) );
        }
        return implode ( '', $sqlArray );
    }
    
    /**
     * @todo: 定位
     * @param $text string
     * @param $value string
     * @return sql string
     * @author:	Melon`` @ 2010
     *
     */
    protected function quoteInto($text, $value) {
        if (empty ( $value )) {
            return $text;
        }
        return str_replace ( '?', $this->quote ( $value ), $text );
    }
    
    /**
     * @desc	添加引号防止数据库攻击
     * @param $value string
     * @param $field_name array 指定值对应的字段名，可选
     * @return mysql_real_escape_string
     * @author:	Melon`` @ 2010
     *
     */
    protected function quote($value, $field_name = null) {
        if (is_array ( $value )) {
            foreach ( $value as & $val ) {
                $val = $this->quote ( $val );
            }
            return implode ( ', ', $value );
        } else {
            $quote_str = "'";
            switch ($this->getFieldType ( $field_name )) {
                case 'int' :
                case 'float' :
                case 'double' :
                case 'numeric' :
                    $quote_str = "";
                    break;
                case 'char' :
                case 'varchar' :
                case 'varchar2' :
                case 'datetime' :
                case 'string' :
                default :
                    $quote_str = "'";
            
            }
            if (get_magic_quotes_gpc ()) {
                return $quote_str . ($value) . $quote_str;
            } else {
                return $quote_str . mysql_real_escape_string ( $value, $this->_conn ) . $quote_str;
            }
        }
    }
    
    /**
     * @todo: 查询
     * @param  $sql string
     * @param  $bind  string|array
     * @return MYSQL句柄
     * @author:	Melon`` @ 2010
     *
     */
    public function Conn($sql, $bind = '') {
        //连接
        $this->connect ();
        //SQL
        if ($sql)
            $this->_sql = $this->formatSql ( $sql, $bind );
        
        //mysql_pconnect
        $this->_rs = mysql_query ( $this->_sql, $this->_conn ); // or die(mysql_error());
        $this->_queryTimes ++;
        $this->_insertId = mysql_insert_id ( $this->_conn );
        if (! $this->_rs) {
            $this->_errorMsg = mysql_error ( $this->_conn );
            $this->_errorNo = mysql_errno ( $this->_conn );
        }
        return $this->_rs;
    }
    
    /**
     * @todo: 	插入一条记录
     * @param $table 表名
     * @param $bind  array
     * @return 本条记录的插入ID或结果
     * @author:	Melon`` @ 2010
     *
     */
    public function Insert($table, $bind, $ext = '') {

        // Check for associative array
        if (array_keys ( $bind ) !== range ( 0, count ( $bind ) - 1 )) {
            // Associative array
            $cols = array_keys ( $bind );
            $sql = "INSERT IGNORE INTO $table " . '(`' . implode ( '`, `', $cols ) . '`) ' . 'VALUES (:' . implode ( ', :', $cols ) . ') ' . $ext;
            $this->Conn ( $sql, $bind );
        } else {
            // Indexed array
            $tmpArray = array ();
            $cols = array_keys ( $bind [0] );
            foreach ( $bind as $v ) {
                $tmpArray [] = $this->formatSql ( ' :' . implode ( ', :', $cols ) . ' ', $v );
            }
            $sql = "INSERT IGNORE INTO $table " . '(`' . implode ( '`, `', $cols ) . '`) ' . 'VALUES (' . implode ( '),(', $tmpArray ) . ') ' . $ext;
            $this->Conn ( $sql );
        }
        return ($this->_insertId ? $this->_insertId : $this->_rs);
    }
    
    /**
     * @todo: 	Inserts()一次有多个时用这个
     * @param $table 表名
     * @param $bind  array
     * @return 本条记录的IDDELAYED
     * @author:	Melon`` @ 2010
     *
     */
    public function Inserts($table, $bind) {

        $cols = array_keys ( $bind );
        $sql = "INSERT DELAYED INTO $table " . '(`' . implode ( '`, `', $cols ) . '`) ' . 'VALUES (:' . implode ( ', :', $cols ) . ')';
        $this->Conn ( $sql, $bind );
        return $this->_insertId;
    }
    
    /**
     * @todo: 	插入一条记录
     * @param $table 表名
     * @param $bind  array
     * @return 本条记录的ID
     * @author:	Melon`` @ 2010
     *
     */
    public function Replace($table, $bind) {
        $cols = array_keys ( $bind );
        $sql = "REPLACE INTO $table " . '(`' . implode ( '`, `', $cols ) . '`) ' . 'VALUES (:' . implode ( ', :', $cols ) . ')';
        $this->Conn ( $sql, $bind );
        return $this->_insertId ? $this->_insertId : $this->_rs;
    }
    
    /**
     * @todo: 	数椐更新
     * @param $table 表名
     * @param $bind array
     * @param $where 条件
     * @return 执行状态
     * @author:	Melon`` @ 2010
     *
     */
    public function Update($table, $data, $where = false, $bind = '') {

        if ($where)
            $where = $this->formatSql ( $where, $bind );
        $set = array ();
        foreach ( $data as $col => $val ) {
            $set [] = "`$col` = :$col";
        }
        $sql = "UPDATE $table " . 'SET ' . implode ( ', ', $set ) . (($where) ? " WHERE $where" : '');
        return $this->Conn ( $sql, $data );
    }
    
    /**
     * @todo: 	数椐叠加
     * @param $table 表名
     * @param $bind  array
     * @param $where 条件
     * @return 执行状态
     * @author:	Melon`` @ 2010
     *
     */
    public function AddDate($table, $data, $where = false, $bind = '') {
        if ($where)
            $where = $this->formatSql ( $where, $bind );
        $set = array ();
        foreach ( $data as $col => $val ) {
            $set [] = "`$col` = $col + " . ( int ) $val;
        }
        $sql = "UPDATE $table " . 'SET ' . implode ( ', ', $set ) . (($where) ? " WHERE $where" : '');
        return $this->Conn ( $sql );
    }
    
    /**
     * @todo: 	数椐叠加
     * @param $table 表名
     * @param $bind  array
     * @param $where 条件
     * @return 执行状态
     * @author:	Melon`` @ 2010
     *
     */
    public function Cutdate($table, $data, $where = false, $bind = '') {
        if ($where)
            $where = $this->formatSql ( $where, $bind );
        $set = array ();
        foreach ( $data as $col => $val ) {
            $set [] = "`$col` = $col - " . ( int ) $val;
        }
        $sql = "UPDATE $table " . 'SET ' . implode ( ', ', $set ) . (($where) ? " WHERE $where" : '');
        return $this->Conn ( $sql );
    }
    
    /**
     * @todo: 	删除记录
     * @param  $table 表名
     * @param  $where 条件
     * @param  执行状态
     * @author:	Melon`` @ 2010
     *
     */
    public function Delete($table, $where = false, $bind = '') {
        $sql = "DELETE FROM $table" . (($where) ? " WHERE $where" : '');
        return $this->Conn ( $sql, $bind );
    }
    
    /**
     * @todo: 得到数椐的行数
     * @param	$sql
     * @param bind 绑定
     * @return int
     * @author:	Melon`` @ 2010
     *
     */
    public function RsCount($sql = '', $bind = '') {
        if ($sql)
            $this->Conn ( $sql, $bind );
        if ($this->_rs) {
            return mysql_num_rows ( $this->_rs );
        } else {
            return false;
        }
    }
    
    /**
     * @todo: 	得到数椐数组
     * @param	$sql
     * @param bind 绑定
     * @return array
     * @author:	Melon`` @ 2010
     *
     */
    public function RsArray($sql = '', $bind = '') {
        if ($sql)
            $this->Conn ( $sql, $bind );
        
        if ($this->_rs) {
            $rs = mysql_fetch_assoc ( $this->_rs );
            return $rs; //判断编码并返回正确的编码格式
        } else {
            return false;
        }
    }
    
    /**
     * @todo: 得到数椐数组
     * @param	$sql
     * @param bind 绑定
     * @return array
     * @author:	Melon`` @ 2010
     *
     */
    public function DataArray($sql = '', $bind = '') {
        $rs = array ();
        $rsCount = $this->RsCount ( $sql, $bind );
        for($i = 0; $i < $rsCount; $i ++) {
            $rs [] = $this->RsArray ();
        }
        return $rs;
    }
    
    /**
     * @todo: 得到数椐数组，DataArray的别名
     * @param	$sql
     * @param bind 绑定
     * @return array
     * @author:	Melon`` @ 2010
     *
     */
    public function FetchAll($sql = '', $bind = '') {
        return $this->DataArray ( $sql, $bind );
    }
    
    /**
     * @todo: 得到数椐数组
     * @param	$sql
     * @param bind 绑定
     * @return array
     * @author:	Melon`` @ 2010
     *
     */
    public function FetchAssoc($sql = null, $bind = null, $cache = -1) {
        $result = $this->DataArray ( $sql, $bind, $cache );
        $data = array ();
        foreach ( $result as &$v ) {
            $tmp = array_values ( $v );
            $data [$tmp [0]] = $v;
        }
        return $data;
    }
    
    /**
     * @todo: 返回某个栏位元的内容是否重复
     * @param 	$table 表
     * @param 	$row 栏位元
     * @param 	$msg 内容
     * @return 有返回true 没有为false
     * @author:	Melon`` @ 2010
     *
     */
    public function RowRepeat($table, $row, $msg) //表,栏位元,内容
{
        if (! empty ( $table ) and ! empty ( $row ) and ! empty ( $msg )) {
            $rsArray = $this->RsArray ( "select count(" . $row . ") as c from " . $table . " where " . $row . " = ? ", $msg );
            if ($rsArray ['c']) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    /**
     * @todo: 	返回最后一次插入的自增ID
     * @author:	Melon`` @ 2010
     *
     */
    public function InsertID() {
        return $this->_insertId;
    }
    
    /**
     * @todo: 	返回查询的次数
     * @return 查询的次数
     * @author:	Melon`` @ 2010
     *
     */
    public function QueryTimes() {
        return $this->_queryTimes;
    }
    
    /**
     * @todo: 获取当前查询的sql
     * @author:	Melon`` @ 2010
     *
     */
    public function GetSql() {
        return $this->_sql;
    }
    
    /**
     * @todo: 	关闭打开的连接
     * @author:	Melon`` @ 2010
     *
     */
    public function Close() {
        if ($this->_conn)
            mysql_close ( $this->_conn );
    }
    
    /**
     * @name AffectedRows
     * @author zhys9
     */
    public function AffectedRows() {
        return mysql_affected_rows ( $this->_conn );
    }
    
    /**
     * @todo: 	升级用户收藏 新添加方法
     * @author:	Melon`` @ 2010
     *
     */
    public function GetByKey($sql, $key) {
        if ($sql)
            $this->Conn ( $sql );
        if ($this->_rs) {
            $result = array ();
            while ( $row = @mysql_fetch_assoc ( $this->_rs ) ) {
                $result [$row [$key]] = $row;
            }
            return $result;
        } else {
            return false;
        }
    }
    
    /**
     * @todo: 	ResultToArray
     * @author:	Melon`` @ 2010
     *
     */
    public function ResultToArray($result, $index = -1) {
        if (! $this->HasResult ( $result ))
            return null;
        $ret = array ();
        while ( $row = mysql_fetch_array ( $result, MYSQL_ASSOC ) ) {
            if ($index != - 1)
                $ret [] = $row [$index];
            else
                $ret [] = $row;
        }
        return $ret;
    }
    
    /**
     * @todo: 	带缓存的查询（已废弃）
     * @author:	Melon`` @ 2010
     *
     */
    public function SqlToArray($sql, $index = -1, $key = '', $cachetime = '1', $skey = 'sql') {
        if ($this->_cache) {
            $key = $key ? $key : base64_encode ( $sql );
            $ret = dc::get ( $key, $cachetime, $skey );
            if (! $ret) {
                $result = $this->Conn ( $sql );
                $ret = $this->ResultToArray ( $result, $index );
                dc::put ( $key, $ret, $skey );
            }
        } else {
            $result = $this->Conn ( $sql );
            $ret = $this->ResultToArray ( $result, $index );
        }
        return $ret;
    }
    
    /**
     * @todo: 	mysql_num_rows
     * @author:	Melon`` @ 2010
     *
     */
    public function NumRows($query) {
        $query = mysql_num_rows ( $query );
        return $query;
    }
    
    /**
     * @todo: 	HasResult
     * @author:	Melon`` @ 2010
     *
     */
    public function HasResult($result) {
        if ($result && (mysql_num_rows ( $result ) > 0))
            return true;
        else
            return false;
    }
    /**
     * @name GetErrorInfo
     * @author zhys9
     */
    public function GetErrorInfo() {
        $this->_errorMsg;
    }
    
    /**
     * @todo: 事务开启
     * @author:	Melon`` @ 2010
     *
     */
    public function TransactionStart() {
        $this->Conn ( 'SET AUTOCOMMIT = 0' );
        $this->Conn ( 'START TRANSACTION' );
    }
    
    /**
     * @todo: 事务执行
     * @author:	Melon`` @ 2010
     *
     */
    public function TransactionCommit() {
        $this->Conn ( 'COMMIT' );
    }
    
    /**
     * @todo: 事务回滚
     * @author:	Melon`` @ 2010
     *
     */
    public function TransactionRollback() {
        $this->Conn ( 'ROLLBACK' );
    }
}