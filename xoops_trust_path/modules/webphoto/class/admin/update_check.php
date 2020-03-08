<?php
// $Id: update_check.php,v 1.6 2010/01/25 10:03:07 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-01-10 K.OHWADA
// webphoto_lib_base -> webphoto_base_ini
// check_210()
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_item_handler
// 2009-03-15 K.OHWADA
// check_130()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_admin_update_check
//=========================================================

/**
 * Class webphoto_admin_update_check
 */
class webphoto_admin_update_check extends webphoto_base_ini
{
    public $_item_handler;
    public $_fileHandler;
    public $_player_handler;
    public $_photo_handler;

    public $_item_count_all;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_admin_update_check constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_item_handler = webphoto_item_handler::getInstance($dirname, $trust_dirname);
        $this->_fileHandler = webphoto_file_handler::getInstance($dirname, $trust_dirname);
        $this->_player_handler = webphoto_player_handler::getInstance($dirname, $trust_dirname);
        $this->_photo_handler = webphoto_photo_handler::getInstance($dirname);

        $this->_item_count_all = $this->_item_handler->get_count_all();
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_admin_update_check|\webphoto_lib_error
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
    // check
    //---------------------------------------------------------

    /**
     * @param      $msg
     * @param bool $flag_highlight
     * @param bool $flag_br
     * @return null|string
     */
    public function build_msg($msg, $flag_highlight = false, $flag_br = false)
    {
        $str = null;

        if ($this->check_040()) {
            $msg = '<a href="' . $this->get_url('040') . '">';
            $msg .= _AM_WEBPHOTO_MUST_UPDATE;
            $msg .= '</a>';
            $str = $this->build_error_msg($msg, '', false);
        } elseif ($this->check_050()) {
            $msg = '<a href="' . $this->get_url('050') . '">';
            $msg .= _AM_WEBPHOTO_MUST_UPDATE;
            $msg .= '</a>';
            $str = $this->build_error_msg($msg, '', false);
        } elseif ($this->check_130()) {
            $msg = '<a href="' . $this->get_url('130') . '">';
            $msg .= _AM_WEBPHOTO_MUST_UPDATE;
            $msg .= '</a>';
            $str = $this->build_error_msg($msg, '', false);
        } elseif ($this->check_210()) {
            $msg = '<a href="' . $this->get_url('210') . '">';
            $msg .= _AM_WEBPHOTO_MUST_UPDATE;
            $msg .= '</a>';
            $str = $this->build_error_msg($msg, '', false);
        }

        return $str;
    }

    /**
     * @return bool
     */
    public function check_040()
    {
        if ($this->_item_count_all > 0) {
            return false;
        }
        if ($this->_photo_handler->get_count_all() > 0) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function check_050()
    {
        if (0 == $this->_player_handler->get_count_all()) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function check_130()
    {
        if (0 == $this->_item_count_all) {
            return false;
        }
        if (0 == $this->_fileHandler->get_count_by_kind(_C_WEBPHOTO_FILE_KIND_SMALL)) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function check_210()
    {
        if (0 == $this->_item_count_all) {
            return false;
        }
        if (0 == $this->_item_handler->get_count_photo()) {
            return false;
        }
        if (0 == $this->_item_handler->get_count_photo_detail_onclick()) {
            return true;
        }

        return false;
    }

    /**
     * @param $ver
     * @return string
     */
    public function get_url($ver)
    {
        $url = $this->_MODULE_URL . '/admin/index.php?fct=update_' . $ver;

        return $url;
    }

    // --- class end ---
}
