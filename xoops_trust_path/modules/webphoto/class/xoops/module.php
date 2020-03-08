<?php
// $Id: module.php,v 1.2 2008/08/08 04:36:09 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//=========================================================
// class webphoto_xoops_module
//=========================================================

/**
 * Class webphoto_xoops_module
 */
class webphoto_xoops_module
{
    public $_moduleHandler;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        $this->_moduleHandler = xoops_getHandler('module');
    }

    /**
     * @return \webphoto_xoops_module
     */
    public static function getInstance()
    {
        static $instance;
        if (null === $instance) {
            $instance = new self();
        }

        return $instance;
    }

    //--------------------------------------------------------
    // my module
    //--------------------------------------------------------

    /**
     * @param string $format
     * @return bool|mixed
     */
    public function get_my_mid($format = 's')
    {
        return $this->get_my_value_by_name('mid', $format);
    }

    /**
     * @param string $format
     * @return bool|mixed
     */
    public function get_my_name($format = 's')
    {
        return $this->get_my_value_by_name('name', $format);
    }

    /**
     * @param        $name
     * @param string $format
     * @return bool|mixed
     */
    public function get_my_value_by_name($name, $format = 's')
    {
        global $xoopsModule;
        if (is_object($xoopsModule)) {
            return $xoopsModule->getVar($name, $format);
        }

        return false;
    }

    //--------------------------------------------------------
    // xoops module
    //--------------------------------------------------------

    /**
     * @param        $dirname
     * @param string $format
     * @return bool
     */
    public function get_mid_by_dirname($dirname, $format = 's')
    {
        return $this->get_value_by_dirname($dirname, 'mid', $format);
    }

    /**
     * @param        $dirname
     * @param string $format
     * @return bool
     */
    public function get_name_by_dirname($dirname, $format = 's')
    {
        return $this->get_value_by_dirname($dirname, 'name', $format);
    }

    /**
     * @param $dirname
     * @return bool
     */
    public function is_active_by_dirname($dirname)
    {
        return $this->get_value_by_dirname($dirname, 'isactive', $format);
    }

    /**
     * @param        $dirname
     * @param        $name
     * @param string $format
     * @return bool
     */
    public function get_value_by_dirname($dirname, $name, $format = 's')
    {
        $moduleHandler = xoops_getHandler('module');
        $module = $this->_moduleHandler->getByDirname($dirname);
        if (is_object($module)) {
            return $module->getVar($name, $format = 's');
        }

        return false;
    }

    /**
     * @param $dirname
     * @return mixed
     */
    public function get_module_by_dirname($dirname)
    {
        return $this->_moduleHandler->getByDirname($dirname);
    }

    /**
     * @param $mid
     * @return \XoopsObject
     */
    public function get_module_by_mid($mid)
    {
        return $this->_moduleHandler->get($mid);
    }

    /**
     * @param null $criteria
     * @param bool $id_as_key
     * @return mixed
     */
    public function get_module_objects($criteria = null, $id_as_key = false)
    {
        return $this->_moduleHandler->getObjects($criteria, $id_as_key);
    }

    /**
     * @param null $param
     * @return array
     */
    public function get_module_list($param = null)
    {
        $isactive = isset($param['isactive']) ? $param['isactive'] : true;
        $file = isset($param['file']) ? $param['file'] : null;
        $except = isset($param['except']) ? $param['except'] : null;

        $criteria = new CriteriaCompo();

        if ($isactive) {
            $criteria->add(new Criteria('isactive', '1', '='));
        }

        $arr = [];

        $objs = $this->_moduleHandler->getObjects($criteria);
        foreach ($objs as $obj) {
            $mod_id = $obj->getVar('mid');
            $mod_dirname = $obj->getVar('dirname');
            $mod_file = XOOPS_ROOT_PATH . '/modules/' . $mod_dirname . '/' . $file;

            if ($file && !file_exists($mod_file)) {
                continue;
            }

            if ($except && ($mod_dirname == $except)) {
                continue;
            }

            $arr[$mod_id] = $obj;
        }

        return $arr;
    }

    /**
     * @param      $mod_objs
     * @param null $param
     * @return array|null
     */
    public function get_dirname_list($mod_objs, $param = null)
    {
        // none_key must be string, not integer 0
        // 0 match any stings

        $none_flag = isset($param['none_flag']) ? $param['none_flag'] : false;
        $none_key = isset($param['none_key']) ? $param['none_key'] : '-';
        $none_value = isset($param['none_value']) ? $param['none_value'] : '---';
        $dirname_default = isset($param['dirname_default']) ? $param['dirname_default'] : null;
        $flag_dirname = isset($param['flag_dirname']) ? $param['flag_dirname'] : true;
        $flag_name = isset($param['flag_name']) ? $param['flag_name'] : true;
        $flag_sanitize = isset($param['flag_sanitize']) ? $param['flag_sanitize'] : true;
        $sort_asort = isset($param['sort_asort']) ? $param['sort_asort'] : true;
        $sort_flip = isset($param['sort_flip']) ? $param['sort_flip'] : true;

        $arr = [];

        if ($none_flag) {
            $arr[$none_key] = $none_value;
        }

        foreach ($mod_objs as $obj) {
            $mod_dirname = $obj->getVar('dirname');
            $mod_name = $obj->getVar('name');

            $str = '';
            if ($flag_dirname) {
                $str .= $mod_dirname;
            }
            if ($flag_name) {
                if ($str) {
                    $str .= ': ';
                }
                $str .= $mod_name;
            }

            if ($flag_sanitize) {
                $str = $this->sanitize($str);
            }

            $arr[$mod_dirname] = $str;
        }

        if ($dirname_default && !isset($arr[$dirname_default])) {
            $str = '';
            if ($flag_dirname) {
                $str .= $dirname_default;
            }
            if ($flag_name) {
                if ($str) {
                    $str .= ' : ';
                }
                $str .= $dirname_default . ' module';
            }

            if ($flag_sanitize) {
                $str = $this->sanitize($str);
            }
            $arr[$dirname_default] = $str;
        }

        if ($sort_asort) {
            asort($arr);
            reset($arr);
        }

        if ($sort_flip) {
            $arr = array_flip($arr);
        }

        return $arr;
    }

    //--------------------------------------------------------
    // utility
    //--------------------------------------------------------

    /**
     * @param $str
     * @return string
     */
    public function sanitize($str)
    {
        return htmlspecialchars($str, ENT_QUOTES);
    }

    // --- class end ---
}
