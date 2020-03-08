<?php
// $Id: language.php,v 1.2 2009/04/27 18:38:38 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-04-27 K.OHWADA
// typo
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_d3_language
//=========================================================

/**
 * Class webphoto_d3_language
 */
class webphoto_d3_language
{
    public $_DIRNAME;
    public $_TRUST_DIRNAME;
    public $_MODULE_DIR;
    public $_TRUST_DIR;

    public $_DEBUG_ERROR = false;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        // dummy
    }

    /**
     * @return \webphoto_d3_language
     */
    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * @param $dirname
     * @param $trust_dirname
     */
    public function init($dirname, $trust_dirname)
    {
        $this->_DIRNAME = $dirname;
        $this->_TRUST_DIRNAME = $trust_dirname;

        $this->_MODULE_DIR = XOOPS_ROOT_PATH . '/modules/' . $dirname;
        $this->_TRUST_DIR = XOOPS_TRUST_PATH . '/modules/' . $trust_dirname;

        $this->set_debug_error_by_const_name($dirname . '_C_DEBUG_ERROR');
    }

    //---------------------------------------------------------
    // public
    //---------------------------------------------------------

    /**
     * @return array
     */
    public function get_lang_array()
    {
        $arr1 = [];
        $arr2 = [];

        $needle1 = mb_strtoupper('_' . $this->_TRUST_DIRNAME . '_');
        $needle2 = mb_strtolower($this->_DIRNAME . '_');
        $constant_arr = get_defined_constants();

        foreach ($constant_arr as $k => $v) {
            if (0 !== mb_strpos($k, $needle1)) {
                continue;
            }

            $key = mb_strtolower(str_replace($needle1, '', $k));
            $arr1[$key] = $v;
        }

        foreach ($arr1 as $k => $v) {
            if (0 !== mb_strpos($k, $needle2)) {
                continue;
            }

            // overwrite
            $key = str_replace($needle2, '', $k);
            $arr1[$key] = $v;
        }

        foreach ($arr1 as $k => $v) {
            $arr2['lang_' . $k] = $v;
        }

        return $arr2;
    }

    /**
     * @param $name
     * @return mixed|string
     */
    public function get_constant($name)
    {
        $cont_name_1 = $this->_build_constant_name_1($name);
        $cont_name_2 = $this->_build_constant_name_2($name);
        $cont_name_3 = $this->_build_constant_name_3($name);

        // typo
        $cont_name_4 = $this->_build_constant_name_4($name);

        if (defined($cont_name_1)) {
            return constant($cont_name_1);
        } elseif (defined($cont_name_2)) {
            return constant($cont_name_2);
        } elseif (defined($cont_name_3)) {
            return constant($cont_name_3);
        }

        if ($this->_DEBUG_ERROR) {
            echo $this->_highlight('CANNOT get constant ' . $name) . "<br>\n";
        }

        return $cont_name_4;
    }

    /**
     * @param $val
     */
    public function set_debug_error($val)
    {
        $this->_DEBUG_ERROR = (bool)$val;
    }

    /**
     * @param $name
     */
    public function set_debug_error_by_const_name($name)
    {
        $name = mb_strtoupper($name);
        if (defined($name)) {
            $this->set_debug_error(constant($name));
        }
    }

    //---------------------------------------------------------
    // private
    //---------------------------------------------------------

    /**
     * @param $name
     * @return string
     */
    public function _build_constant_name_1($name)
    {
        return mb_strtoupper('_' . $this->_TRUST_DIRNAME . '_' . $this->_DIRNAME . '_' . $name);
    }

    /**
     * @param $name
     * @return string
     */
    public function _build_constant_name_2($name)
    {
        return mb_strtoupper('_' . $this->_DIRNAME . '_' . $name);
    }

    /**
     * @param $name
     * @return string
     */
    public function _build_constant_name_3($name)
    {
        return mb_strtoupper('_' . $this->_TRUST_DIRNAME . '_' . $name);
    }

    /**
     * @param $name
     * @return string
     */
    public function _build_constant_name_4($name)
    {
        return mb_strtoupper('_' . $name);
    }

    /**
     * @param $str
     * @return string
     */
    public function _highlight($str)
    {
        $val = '<span style="color:#ff0000;">' . $str . '</span>';

        return $val;
    }

    //----- class end -----
}
