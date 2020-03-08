<?php
// $Id: mail.php,v 1.1 2011/11/12 17:18:25 ohwada Exp $

//=========================================================
// webphoto module
// 2011-11-11 K.OHWADA
//=========================================================

//=========================================================
// class webphoto_lib_mail
//=========================================================

/**
 * Class webphoto_lib_mail
 */
class webphoto_lib_mail
{
    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        // dummy
    }

    /**
     * @return \webphoto_lib_mail
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
    // utility
    //---------------------------------------------------------

    /**
     * @param $str
     * @return mixed|null
     */
    public function get_valid_addr($str)
    {
        list($name, $addr) = $this->parse_name_addr($str);

        if ($this->check_valid_addr($addr)) {
            return $addr;
        }

        return null;
    }

    /**
     * @param $addr
     * @return bool
     */
    public function check_valid_addr($addr)
    {
        // same as class/xoopsmailer.php
        $PATTERN = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$/i";

        if (preg_match($PATTERN, $addr)) {
            return true;
        }

        return false;
    }

    /**
     * @param $str
     * @return array
     */
    public function parse_name_addr($str)
    {
        $name = '';

        // taro <taro@example.com>
        $PATTERN = '/(.*)<(.*)>/i';

        if (preg_match($PATTERN, $str, $matches)) {
            $name = trim($matches[1]);
            $addr = trim($matches[2]);
        } else {
            $addr = trim($str);
        }

        return [$name, $addr];
    }

    // --- class end ---
}
