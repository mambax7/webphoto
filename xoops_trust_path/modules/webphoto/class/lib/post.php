<?php
// $Id: post.php,v 1.1.1.1 2008/06/21 12:22:29 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_lib_post
//=========================================================

/**
 * Class webphoto_lib_post
 */
class webphoto_lib_post
{
    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        // dummy
    }

    /**
     * @return \webphoto_lib_post
     */
    public static function getInstance()
    {
        static $instance;
        if (null === $instance) {
            $instance = new self();
        }

        return $instance;
    }

    //---------------------------------------------------------
    // function
    //---------------------------------------------------------

    /**
     * @param      $key
     * @param null $default
     */
    public function get_post($key, $default = null)
    {
        $str = isset($_POST[$key]) ? $_POST[$key] : $default;

        return $str;
    }

    /**
     * @param      $key
     * @param null $default
     * @return array|string
     */
    public function get_post_text($key, $default = null)
    {
        return $this->_strip_slashes_gpc($this->get_post($key, $default));
    }

    /**
     * @param     $key
     * @param int $default
     * @return int
     */
    public function get_post_int($key, $default = 0)
    {
        return (int)$this->get_post($key, $default);
    }

    /**
     * @param     $key
     * @param int $default
     * @return float
     */
    public function get_post_float($key, $default = 0)
    {
        return (float)$this->get_post($key, $default);
    }

    /**
     * @param      $key
     * @param null $default
     * @return array|null|string
     */
    public function get_post_url($key, $default = null)
    {
        $str = $this->get_post_text($key, $default);
        if ($this->check_http_start($str) && $this->check_http_fill($str)) {
            return $str;
        }

        return $default;
    }

    /**
     * @param      $key
     * @param null $default
     * @return false|int
     */
    public function get_post_time($key, $default = null)
    {
        return $this->str_to_time($this->get_post_text($key, $default));
    }

    /**
     * @param      $key
     * @param null $default
     */
    public function get_get($key, $default = null)
    {
        $str = isset($_GET[$key]) ? $_GET[$key] : $default;

        return $str;
    }

    /**
     * @param      $key
     * @param null $default
     * @return array|string
     */
    public function get_get_text($key, $default = null)
    {
        return $this->_strip_slashes_gpc($this->get_get($key, $default));
    }

    /**
     * @param     $key
     * @param int $default
     * @return int
     */
    public function get_get_int($key, $default = 0)
    {
        return (int)$this->get_get($key, $default);
    }

    /**
     * @param      $key
     * @param null $default
     */
    public function get_post_get($key, $default = null)
    {
        $str = $default;
        if (isset($_POST[$key])) {
            $str = $_POST[$key];
        } elseif (isset($_GET[$key])) {
            $str = $_GET[$key];
        }

        return $str;
    }

    /**
     * @param      $key
     * @param null $default
     * @return array|string
     */
    public function get_post_get_text($key, $default = null)
    {
        return $this->_strip_slashes_gpc($this->get_post_get($key, $default));
    }

    /**
     * @param     $key
     * @param int $default
     * @return int
     */
    public function get_post_get_int($key, $default = 0)
    {
        return (int)$this->get_post_get($key, $default);
    }

    //---------------------------------------------------------
    // utlity
    //---------------------------------------------------------

    /**
     * @param $str
     * @return array|string
     */
    public function _strip_slashes_gpc($str)
    {
        if (@!get_magic_quotes_gpc()) {
            return $str;
        }

        if (!is_array($str)) {
            return stripslashes($str);
        }

        $arr = [];
        foreach ($str as $k => $v) {
            $arr[$k] = stripslashes($v);
        }

        return $arr;
    }

    /**
     * @param $str
     * @return bool
     */
    public function check_http_start($str)
    {
        if (preg_match('|^https?://|', $str)) {
            return true;    // include HTTP
        }

        return false;
    }

    /**
     * @param $str
     * @return bool
     */
    public function check_http_fill($str)
    {
        if (('' != $str) && ('http://' != $str) && ('https://' != $str)) {
            return true;
        }

        return false;
    }

    /**
     * @param $str
     * @return false|int
     */
    public function str_to_time($str)
    {
        $str = trim($str);
        if ($str) {
            $time = strtotime($str);
            if ($time > 0) {
                return $time;
            }

            return -1;  // failed to convert
        }

        return 0;
    }

    // --- class end ---
}
