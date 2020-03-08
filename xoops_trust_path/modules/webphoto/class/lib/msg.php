<?php
// $Id: msg.php,v 1.3 2009/01/24 15:33:44 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-01-10 K.OHWADA
// change set_msg()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_lib_error
//=========================================================

/**
 * Class webphoto_lib_msg
 */
class webphoto_lib_msg
{
    public $_msg_array = [];

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        // dummy
    }

    /**
     * @return \webphoto_lib_msg
     */
    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new self();
        }

        return $instance;
    }

    //---------------------------------------------------------
    // msg
    //---------------------------------------------------------
    public function clear_msg_array()
    {
        $this->_msg_array = [];
    }

    /**
     * @return array
     */
    public function get_msg_array()
    {
        return $this->_msg_array;
    }

    /**
     * @return bool
     */
    public function has_msg_array()
    {
        if (is_array($this->_msg_array) && count($this->_msg_array)) {
            return true;
        }

        return false;
    }

    /**
     * @param      $msg
     * @param bool $flag_highlight
     */
    public function set_msg($msg, $flag_highlight = false)
    {
        // array type
        if (is_array($msg)) {
            $arr = $msg;

        // string type
        } else {
            $arr = $this->str_to_array($msg, "\n");
            if ($flag_highlight) {
                $arr2 = [];
                foreach ($arr as $m) {
                    $arr2[] = $this->highlight($m);
                }
                $arr = $arr2;
            }
        }

        foreach ($arr as $m) {
            $m = trim($m);
            if ($m) {
                $this->_msg_array[] = $m;
            }
        }
    }

    /**
     * @param string $glue
     * @return bool|string
     */
    public function get_msg_str($glue = '')
    {
        return $this->array_to_str($this->_msg_array, $glue);
    }

    /**
     * @param bool $flag_sanitize
     * @param bool $flag_highlight
     * @param bool $flag_br
     * @return string
     */
    public function get_format_msg_array($flag_sanitize = true, $flag_highlight = true, $flag_br = true)
    {
        $str = '';
        foreach ($this->_msg_array as $msg) {
            if ($flag_sanitize) {
                $msg = $this->sanitize($msg);
            }
            $str .= $msg;
            $str .= ' ';
            if ($flag_br) {
                $str .= "<br>\n";
            }
        }

        if ($flag_highlight) {
            $str = $this->highlight($str);
        }

        return $str;
    }

    //---------------------------------------------------------
    // utility
    //---------------------------------------------------------

    /**
     * @param $str
     * @return string
     */
    public function highlight($str)
    {
        $val = '<span style="color:#ff0000;">' . $str . '</span>';

        return $val;
    }

    /**
     * @param $str
     * @return string
     */
    public function sanitize($str)
    {
        return htmlspecialchars($str, ENT_QUOTES);
    }

    /**
     * @param $str
     * @param $pattern
     * @return array
     */
    public function str_to_array($str, $pattern)
    {
        $arr1 = explode($pattern, $str);
        $arr2 = [];
        foreach ($arr1 as $v) {
            $v = trim($v);
            if ('' === $v) {
                continue;
            }
            $arr2[] = $v;
        }

        return $arr2;
    }

    /**
     * @param $arr
     * @param $glue
     * @return bool|string
     */
    public function array_to_str($arr, $glue)
    {
        $val = false;
        if (is_array($arr) && count($arr)) {
            $val = implode($glue, $arr);
        }

        return $val;
    }

    //----- class end -----
}
