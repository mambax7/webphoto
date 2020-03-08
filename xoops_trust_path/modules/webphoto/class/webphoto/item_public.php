<?php
// $Id: item_public.php,v 1.3 2011/05/10 02:56:39 ohwada Exp $

//=========================================================
// webphoto module
// 2008-12-12 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// webphoto_lib_base -> webphoto_base_ini
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_item_handler
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_item_public
//=========================================================

/**
 * Class webphoto_item_public
 */
class webphoto_item_public extends webphoto_base_ini
{
    public $_config_class;
    public $_catHandler;
    public $_item_handler;

    public $_cfg_perm_cat_read;
    public $_cfg_perm_item_read;

    public $_item_row = null;
    public $_error = null;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_item_public constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_config_class = webphoto_config::getInstance($dirname);
        $this->_catHandler = webphoto_cat_handler::getInstance($dirname, $trust_dirname);
        $this->_item_handler = webphoto_item_handler::getInstance($dirname, $trust_dirname);

        $this->_cfg_perm_cat_read = $this->_config_class->get_by_name('perm_cat_read');
        $this->_cfg_perm_item_read = $this->_config_class->get_by_name('perm_item_read');
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_item_public|\webphoto_lib_error
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
    // main
    //---------------------------------------------------------

    /**
     * @param $item_id
     * @return bool
     */
    public function get_item_row($item_id)
    {
        $row = $this->_item_handler->get_row_by_id($item_id);
        if (!is_array($row)) {
            $this->_error = $this->get_constant('NOMATCH_PHOTO');

            return false;
        }

        $cat_id = $row['item_cat_id'];
        $status = $row['item_status'];

        if ($status <= 0) {
            $this->_error = $this->get_constant('NOMATCH_PHOTO');

            return false;
        }

        if (_C_WEBPHOTO_OPT_PERM_READ_ALL != $this->_cfg_perm_item_read) {
            if (!$this->_item_handler->check_perm_read_by_row($row)) {
                $this->_error = $this->get_constant('NOMATCH_PHOTO');

                return false;
            }
        }

        if (_C_WEBPHOTO_OPT_PERM_READ_ALL != $this->_cfg_perm_cat_read) {
            if (!$this->_catHandler->check_perm_read_by_id($cat_id)) {
                $this->_error = $this->get_constant('NOMATCH_PHOTO');

                return false;
            }
        }

        $this->_item_row = $row;

        return $row;
    }

    /**
     * @param $perm
     * @return bool
     */
    public function check_item_perm($perm)
    {
        return $this->_item_handler->check_perm_by_perm_groups($perm);
    }

    // --- class end ---
}
