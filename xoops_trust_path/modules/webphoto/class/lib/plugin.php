<?php
// $Id: plugin.php,v 1.2 2009/01/24 07:10:39 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-04 K.OHWADA
//=========================================================

//---------------------------------------------------------
// 2009-01-10 K.OHWADA
// get_cached_class_object()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_plugin
//=========================================================

/**
 * Class webphoto_lib_plugin
 */
class webphoto_lib_plugin
{
    public $_utility_class;

    public $_cached_by_type = [];

    public $_DIRNAME;
    public $_TRUST_DIRNAME;
    public $_MODULE_URL;
    public $_MODULE_DIR;
    public $_TRUST_DIR;

    public $_TRUST_PLUGIN_DIR = null;
    public $_ROOT_PLUGIN_DIR = null;
    public $_PLUGIN_PREFIX = null;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_lib_plugin constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        $this->_DIRNAME = $dirname;
        $this->_MODULE_URL = XOOPS_URL . '/modules/' . $dirname;
        $this->_MODULE_DIR = XOOPS_ROOT_PATH . '/modules/' . $dirname;
        $this->_TRUST_DIRNAME = $trust_dirname;
        $this->_TRUST_DIR = XOOPS_TRUST_PATH . '/modules/' . $trust_dirname;

        $this->_utility_class = webphoto_lib_utility::getInstance();
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_lib_plugin
     */
    public static function getInstance($dirname = null, $trust_dirname = null)
    {
        static $instance;
        if (null === $instance) {
            $instance = new self($dirname, $trust_dirname);
        }

        return $instance;
    }

    //---------------------------------------------------------
    // set param
    //---------------------------------------------------------

    /**
     * @param $sub_dir
     */
    public function set_dirname($sub_dir)
    {
        $dir = '/plugins/' . $sub_dir;
        $this->_TRUST_PLUGIN_DIR = $this->_TRUST_DIR . $dir;
        $this->_ROOT_PLUGIN_DIR = $this->_MODULE_DIR . $dir;
    }

    /**
     * @param $val
     */
    public function set_prefix($val)
    {
        $this->_PLUGIN_PREFIX = $val;
    }

    //---------------------------------------------------------
    // plugin
    //---------------------------------------------------------

    /**
     * @return array
     */
    public function build_list()
    {
        $files = $this->_utility_class->get_files_in_dir($this->_TRUST_PLUGIN_DIR, 'php', false, true);
        $arr = [];
        foreach ($files as $file) {
            $arr[] = str_replace('.php', '', $file);
        }

        return $arr;
    }

    /**
     * @param $type
     * @return bool|mixed
     */
    public function &get_cached_class_object($type)
    {
        if (isset($this->_cached_by_type[$type])) {
            return $this->_cached_by_type[$type];
        }

        $obj = $this->get_class_object($type);
        if (is_object($obj)) {
            $this->_cached_by_type[$type] = $obj;
        }

        return $obj;
    }

    /**
     * @param $type
     * @return bool
     */
    public function &get_class_object($type)
    {
        $false = false;

        if (empty($type)) {
            return $false;
        }

        $this->include_once_file($type);

        $class_name = $this->get_class_name($type);
        if (empty($class_name)) {
            return $false;
        }

        $class = new $class_name();

        return $class;
    }

    /**
     * @param $type
     */
    public function include_once_file($type)
    {
        $file = $this->get_file_name($type);
        if ($file) {
            include_once $file;
        }
    }

    /**
     * @param $type
     * @return bool|string
     */
    public function get_file_name($type)
    {
        $type_php = $type . '.php';
        $file_trust = $this->_TRUST_PLUGIN_DIR . '/' . $type_php;
        $file_root = $this->_ROOT_PLUGIN_DIR . '/' . $type_php;

        if (file_exists($file_root)) {
            return $file_root;
        } elseif (file_exists($file_trust)) {
            return $file_trust;
        }

        return false;
    }

    /**
     * @param $type
     * @return bool|string
     */
    public function get_class_name($type)
    {
        $class = $this->_PLUGIN_PREFIX . $type;
        if (class_exists($class)) {
            return $class;
        }

        return false;
    }

    // --- class end ---
}
