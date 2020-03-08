<?php
// $Id: use_item.php,v 1.2 2010/02/17 04:34:47 ohwada Exp $

//=========================================================
// webphoto module
// 2010-02-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-02-15 K.OHWADA
// check_edit()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_edit_use_item
//=========================================================

/**
 * Class webphoto_edit_use_item
 */
class webphoto_edit_use_item extends webphoto_base_this
{
    public $_cfg_gmap_apikey;
    public $_cfg_perm_item_read;

    public $_item_array;
    public $_show_array;
    public $_edit_array;

    public $_flag_admin = false;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_edit_use_item constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_cfg_gmap_apikey = $this->get_config_by_name('gmap_apikey');
        $this->_cfg_perm_item_read = $this->get_config_by_name('perm_item_read');

        $this->_item_array = $this->explode_ini('submit_item_list');
        $this->_show_array = $this->explode_ini('submit_show_list');
        $this->_edit_array = $this->explode_ini('edit_list');
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_edit_use_item|\webphoto_lib_error
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
     * @param $val
     */
    public function set_flag_admin($val)
    {
        $this->_flag_admin = (bool)$val;
    }

    //---------------------------------------------------------
    // submit edit form
    //---------------------------------------------------------

    /**
     * @return bool
     */
    public function use_item_perm_read()
    {
        if (($this->_cfg_perm_item_read > 0)
            && $this->use_item_or_admin('perm_read')) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function use_item_perm_level()
    {
        if (($this->_cfg_perm_item_read > 0)
            && $this->use_item('perm_level')) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function editable_item_perm_level()
    {
        if ($this->use_item_perm_level()
            && $this->check_edit_or_admin('perm_level')) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function use_gmap()
    {
        if ($this->_cfg_gmap_apikey && $this->check_show_or_admin('gmap')) {
            return true;
        }

        return false;
    }

    /**
     * @param $key
     * @return bool
     */
    public function use_item_or_admin($key)
    {
        if ($this->_flag_admin || $this->use_item($key)) {
            return true;
        }

        return false;
    }

    /**
     * @param $key
     * @return bool
     */
    public function check_show_or_admin($key)
    {
        if ($this->_flag_admin || $this->check_show($key)) {
            return true;
        }

        return false;
    }

    /**
     * @param $key
     * @return bool
     */
    public function check_edit_or_admin($key)
    {
        if ($this->_flag_admin || $this->check_edit($key)) {
            return true;
        }

        return false;
    }

    /**
     * @param $key
     * @return bool
     */
    public function use_item($key)
    {
        return in_array($key, $this->_item_array);
    }

    /**
     * @param $key
     * @return bool
     */
    public function check_show($key)
    {
        return in_array($key, $this->_show_array);
    }

    /**
     * @param $key
     * @return bool
     */
    public function check_edit($key)
    {
        return in_array($key, $this->_edit_array);
    }

    // --- class end ---
}
