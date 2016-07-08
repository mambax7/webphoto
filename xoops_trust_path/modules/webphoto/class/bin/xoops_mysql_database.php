<?php
// $Id: xoops_mysql_database.php,v 1.1 2008/08/25 19:30:22 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-24 K.OHWADA
//=========================================================

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class mysql_database
// substitute for class XOOPS XoopsMySQLDatabase
// base on happy_linux/class/xoops_mysql_database.php
//=========================================================
class mysql_database extends Database
{

    // Database connection
    public $conn;

    public $prefix;

    // debug
    public $flag_print_error = 1;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        $this->setPrefix(XOOPS_DB_PREFIX);
    }

    //---------------------------------------------------------
    // function
    //---------------------------------------------------------
    public function connect()
    {
        $this->conn = mysql_connect(XOOPS_DB_HOST, XOOPS_DB_USER, XOOPS_DB_PASS);

        if (!$this->conn) {
            $this->_print_error();
            return false;
        }

        if (!mysql_select_db(XOOPS_DB_NAME)) {
            $this->_print_error();
            return false;
        }

        return true;
    }

    public function set_charset()
    {
        if (defined('_WEBPHOTO_MYSQL_CHARSET')) {
            $sql = '/*!40101 SET NAMES ' . _WEBPHOTO_MYSQL_CHARSET . ' */';
            $ret = $this->query($sql);
            if (!$ret) {
                $this->_print_error();
                return false;
            }
        }
        return true;
    }

    public function fetchRow($result)
    {
        return @mysql_fetch_row($result);
    }

    public function fetchArray($result)
    {
        return @mysql_fetch_assoc($result);
    }

    public function fetchBoth($result)
    {
        return @mysql_fetch_array($result, MYSQL_BOTH);
    }

    public function getInsertId()
    {
        return mysql_insert_id($this->conn);
    }

    public function getRowsNum($result)
    {
        return @mysql_num_rows($result);
    }

    public function getAffectedRows()
    {
        return mysql_affected_rows($this->conn);
    }

    public function close()
    {
        mysql_close($this->conn);
    }

    public function freeRecordSet($result)
    {
        return mysql_free_result($result);
    }

    public function error()
    {
        return @mysql_error();
    }

    public function errno()
    {
        return @mysql_errno();
    }

    public function quoteString($str)
    {
        $str = "'" . str_replace('\\"', '"', addslashes($str)) . "'";
        return $str;
    }

    public function &queryF($sql, $limit = 0, $start = 0)
    {
        if (!empty($limit)) {
            if (empty($start)) {
                $start = 0;
            }
            $sql = $sql . ' LIMIT ' . (int)$start . ', ' . (int)$limit;
        }

        $result = mysql_query($sql, $this->conn);

        if (!$result) {
            $this->_print_error($sql);
            $false = false;
            return $false;
        }

        return $result;
    }

    public function &query($sql, $limit = 0, $start = 0)
    {
        return $this->queryF($sql, $limit, $start);
    }

    public function setPrefix($value)
    {
        $this->prefix = $value;
    }

    public function prefix($tablename = '')
    {
        if ($tablename != '') {
            return $this->prefix . '_' . $tablename;
        } else {
            return $this->prefix;
        }
    }

    //---------------------------------------------------------
    // debug
    //---------------------------------------------------------
    public function _print_error($sql = '')
    {
        if (!$this->flag_print_error) {
            return;
        }

        if ($sql) {
            echo "sql: $sql <br />\n";
        }

        echo "<font color='red'>" . $this->error() . "</font><br />\n";
    }

    //---------------------------------------------------------
}
