<?php
// $Id: config.php,v 1.5 2011/11/12 11:05:02 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-11-11 K.OHWADA
// webphoto_inc_xoops_config()
// 2008-12-12 K.OHWADA
// getInstance() -> getSingleton()
// 2008-11-29 K.OHWADA
// get_path_by_name()
// 2008-07-01 K.OHWADA
// webphoto_xoops_base -> xoops_getHandler()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_inc_config
//=========================================================
//---------------------------------------------------------
// caller inc_xoops_version inc_blocks
//---------------------------------------------------------

/**
 * Class webphoto_inc_config
 */
class webphoto_inc_config
{
    public $_cached_config = [];
    public $_DIRNAME;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_inc_config constructor.
     * @param $dirname
     */
    public function __construct($dirname)
    {
        $this->_DIRNAME = $dirname;
        $this->_get_xoops_config($dirname);
    }

    /**
     * @param $dirname
     * @return mixed
     */
    public static function getSingleton($dirname)
    {
        static $singletons;
        if (!isset($singletons[$dirname])) {
            $singletons[$dirname] = new self($dirname);
        }

        return $singletons[$dirname];
    }

    //---------------------------------------------------------
    // cache
    //---------------------------------------------------------

    /**
     * @param $name
     * @return bool|mixed
     */
    public function get_by_name($name)
    {
        if (isset($this->_cached_config[$name])) {
            return $this->_cached_config[$name];
        }

        return false;
    }

    /**
     * @param $name
     * @return null|string
     */
    public function get_path_by_name($name)
    {
        $path = $this->get_by_name($name);
        if ($path) {
            return $this->_add_slash_to_head($path);
        }

        return null;
    }

    /**
     * @param $str
     * @return string
     */
    public function _add_slash_to_head($str)
    {
        // ord : the ASCII value of the first character of string
        // 0x2f slash

        if (0x2f != ord($str)) {
            $str = '/' . $str;
        }

        return $str;
    }

    //---------------------------------------------------------
    // xoops class
    //---------------------------------------------------------

    /**
     * @param $dirname
     */
    public function _get_xoops_config($dirname)
    {
        if (defined('WEBPHOTO_COMMOND_MODE') && (WEBPHOTO_COMMOND_MODE == 1)) {
            $config = webphoto_bin_config::getInstance();
        } else {
            $config = webphoto_inc_xoops_config::getInstance();
        }

        $this->_cached_config = $config->get_config_by_dirname($dirname);
    }

    // --- class end ---
}
