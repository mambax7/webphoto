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

/**
 * Class mysql_database
 */
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

    /**
     * @return bool
     */
    public function connect()
    {
        $this->conn = mysql_connect(XOOPS_DB_HOST, XOOPS_DB_USER, XOOPS_DB_PASS);

        if (!$this->conn) {
            $this->_print_error();

            return false;
        }

        if (!mysqli_select_db($GLOBALS['xoopsDB']->conn, XOOPS_DB_NAME)) {
            $this->_print_error();

            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
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

    /**
     * @param $result
     * @return array
     */
    public function fetchRow($result)
    {
        return @$GLOBALS['xoopsDB']->fetchRow($result);
    }

    /**
     * @param $result
     * @return array
     */
    public function fetchArray($result)
    {
        return @$GLOBALS['xoopsDB']->fetchArray($result);
    }

    /**
     * @param $result
     * @return array
     */
    public function fetchBoth($result)
    {
        return @$GLOBALS['xoopsDB']->fetchBoth($result, MYSQL_BOTH);
    }

    /**
     * @return int
     */
    public function getInsertId()
    {
        return $GLOBALS['xoopsDB']->getInsertId($this->conn);
    }

    /**
     * @param $result
     * @return int
     */
    public function getRowsNum($result)
    {
        return @$GLOBALS['xoopsDB']->getRowsNum($result);
    }

    /**
     * @return int
     */
    public function getAffectedRows()
    {
        return $GLOBALS['xoopsDB']->getAffectedRows($this->conn);
    }

    public function close()
    {
        $GLOBALS['xoopsDB']->close($this->conn);
    }

    /**
     * @param $result
     * @return bool
     */
    public function freeRecordSet($result)
    {
        return $GLOBALS['xoopsDB']->freeRecordSet($result);
    }

    /**
     * @return string
     */
    public function error()
    {
        return @$GLOBALS['xoopsDB']->error();
    }

    /**
     * @return int
     */
    public function errno()
    {
        return @$GLOBALS['xoopsDB']->errno();
    }

    /**
     * @param $str
     * @return string
     */
    public function quoteString($str)
    {
        $str = "'" . str_replace('\\"', '"', addslashes($str)) . "'";

        return $str;
    }

    /**
     * @param     $sql
     * @param int $limit
     * @param int $start
     * @return bool|resource
     */
    public function &queryF($sql, $limit = 0, $start = 0)
    {
        if (!empty($limit)) {
            if (empty($start)) {
                $start = 0;
            }
            $sql = $sql . ' LIMIT ' . (int)$start . ', ' . (int)$limit;
        }

        $result = $GLOBALS['xoopsDB']->queryF($sql, $this->conn);

        if (!$result) {
            $this->_print_error($sql);
            $false = false;

            return $false;
        }

        return $result;
    }

    /**
     * @param     $sql
     * @param int $limit
     * @param int $start
     * @return bool|resource
     */
    public function &query($sql, $limit = 0, $start = 0)
    {
        return $this->queryF($sql, $limit, $start);
    }

    /**
     * @param $value
     */
    public function setPrefix($value)
    {
        $this->prefix = $value;
    }

    /**
     * @param string $tablename
     * @return string
     */
    public function prefix($tablename = '')
    {
        if ('' != $tablename) {
            return $this->prefix . '_' . $tablename;
        }

        return $this->prefix;
    }

    //---------------------------------------------------------
    // debug
    //---------------------------------------------------------

    /**
     * @param string $sql
     */
    public function _print_error($sql = '')
    {
        if (!$this->flag_print_error) {
            return;
        }

        if ($sql) {
            echo "sql: $sql <br>\n";
        }

        echo "<font color='red'>" . $this->error() . "</font><br>\n";
    }

    //---------------------------------------------------------
}
