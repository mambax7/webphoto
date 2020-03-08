<?php
// $Id: ini.php,v 1.2 2010/01/25 10:03:07 ohwada Exp $

//=========================================================
// webphoto module
// 2009-11-11 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-01-10 K.OHWADA
// hash_ini()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_inc_ini
//=========================================================

/**
 * Class webphoto_inc_ini
 */
class webphoto_inc_ini
{
    public $_DIRNAME;
    public $_TRUST_DIRNAME;
    public $_MODULE_DIR;
    public $_TRUST_DIR;

    public $_array_ini = null;

    public $_DEBUG_READ = false;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_inc_ini constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        $this->_DIRNAME = $dirname;
        $this->_MODULE_DIR = XOOPS_ROOT_PATH . '/modules/' . $dirname;
        $this->_TRUST_DIRNAME = $trust_dirname;
        $this->_TRUST_DIR = XOOPS_TRUST_PATH . '/modules/' . $trust_dirname;

        $constpref = mb_strtoupper('_P_' . $dirname . '_');
        $this->set_debug_read_by_const_name($constpref . 'DEBUG_READ');
    }

    /**
     * @param $dirname
     * @param $trust_dirname
     * @return mixed
     */
    public static function getSingleton($dirname, $trust_dirname)
    {
        static $singletons;
        if (!isset($singletons[$dirname])) {
            $singletons[$dirname] = new self($dirname, $trust_dirname);
        }

        return $singletons[$dirname];
    }

    //---------------------------------------------------------
    // public
    //---------------------------------------------------------

    /**
     * @param bool $debug
     */
    public function read_main_ini($debug = false)
    {
        $this->read_ini('main.ini', $debug);
    }

    /**
     * @param      $file
     * @param bool $debug
     * @return bool
     */
    public function read_ini($file, $debug = false)
    {
        $file_trust_include = $this->_TRUST_DIR . '/include/' . $file;
        $file_trust_preload = $this->_TRUST_DIR . '/preload/' . $file;
        $file_root_include = $this->_MODULE_DIR . '/include/' . $file;
        $file_root_preload = $this->_MODULE_DIR . '/preload/' . $file;

        $arr = [];

        // root: high priority
        if (file_exists($file_root_include)) {
            $this->debug_msg_read_file($file_root_include, $debug);
            $arr_ini = parse_ini_file($file_root_include);
            if (is_array($arr_ini)) {
                $arr = array_merge($arr, $arr_ini);
            }

            // trust: low priority
        } elseif (file_exists($file_trust_include)) {
            $this->debug_msg_read_file($file_trust_include, $debug);
            $arr_ini = parse_ini_file($file_trust_include);
            if (is_array($arr_ini)) {
                $arr = array_merge($arr, $arr_ini);
            }

            // read if trust
            if (file_exists($file_trust_preload)) {
                $this->debug_msg_read_file($file_trust_preload, $debug);
                $arr_ini = parse_ini_file($file_trust_preload);
                if (is_array($arr_ini)) {
                    $arr = array_merge($arr, $arr_ini);
                }
            }
        }

        // read preload
        if (file_exists($file_root_preload)) {
            $this->debug_msg_read_file($file_root_preload, $debug);
            $arr_ini = parse_ini_file($file_root_preload);
            if (is_array($arr_ini)) {
                $arr = array_merge($arr, $arr_ini);
            }
        }

        $this->_array_ini = $arr;

        return true;
    }

    /**
     * @param $name
     * @return bool
     */
    public function isset_ini($name)
    {
        if (isset($this->_array_ini[$name])) {
            return true;
        }

        return false;
    }

    /**
     * @param $name
     */
    public function get_ini($name)
    {
        if (isset($this->_array_ini[$name])) {
            return $this->_array_ini[$name];
        }

        return null;
    }

    /**
     * @param        $name
     * @param string $grue
     * @param null   $prefix
     * @return array
     */
    public function explode_ini($name, $grue = '|', $prefix = null)
    {
        return $this->str_to_array($this->get_ini($name), $grue, $prefix);
    }

    /**
     * @param        $name
     * @param string $grue1
     * @param string $grue2
     * @return array|bool
     */
    public function hash_ini($name, $grue1 = '|', $grue2 = ':')
    {
        $arr = $this->str_to_array($this->get_ini($name), $grue1, null);
        if (!is_array($arr)) {
            return false;
        }

        $ret = [];
        foreach ($arr as $a) {
            $t = $this->str_to_array($a, $grue2, null);
            if (isset($t[0]) && isset($t[1])) {
                $ret[$t[0]] = $t[1];
            }
        }

        return $ret;
    }

    /**
     * @param $str
     * @param $grue
     * @param $prefix
     * @return array
     */
    public function str_to_array($str, $grue, $prefix)
    {
        $arr = explode($grue, $str);
        $ret = [];
        foreach ($arr as $a) {
            $a = trim($a);
            if ('' == $a) {
                continue;
            }
            $ret[] = $prefix . $a;
        }

        return $ret;
    }

    //---------------------------------------------------------
    // debug
    //---------------------------------------------------------

    /**
     * @param      $file
     * @param bool $debug
     */
    public function debug_msg_read_file($file, $debug = true)
    {
        $file_win = str_replace('/', '\\', $file);

        if ($this->_DEBUG_READ && $debug) {
            echo 'read ' . $file . "<br>\n";
        }
    }

    /**
     * @param $val
     */
    public function set_debug_read($val)
    {
        $this->_DEBUG_READ = (bool)$val;
    }

    /**
     * @param $name
     */
    public function set_debug_read_by_const_name($name)
    {
        $name = mb_strtoupper($name);
        if (defined($name)) {
            $this->set_debug_read(constant($name));
        }
    }

    //----- class end -----
}
