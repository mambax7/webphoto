<?php
// $Id: base.php,v 1.7 2010/11/16 23:43:38 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-11-11 K.OHWADA
// move clear_tmp_files_in_tmp_dir() to webphoto_admin_redothumbs
// 2010-10-01 K.OHWADA
// move unlink_path()
// 2010-03-18 K.OHWADA
// format_and_insert_item()
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_mime
// 2009-05-05 K.OHWADA
// build_tmp_dir_file()
// 2009-04-19 K.OHWADA
// build_form_video_thumb()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_edit_base
//=========================================================

/**
 * Class webphoto_edit_base
 */
class webphoto_edit_base extends webphoto_base_this
{
    public $_item_create_class;
    public $_mime_class;
    public $_icon_build_class;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_edit_base constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_item_create_class = webphoto_edit_item_create::getInstance($dirname, $trust_dirname);
        $this->_mime_class = webphoto_mime::getInstance($dirname, $trust_dirname);
        $this->_icon_build_class = webphoto_edit_icon_build::getInstance($dirname);
    }

    //---------------------------------------------------------
    // check dir
    //---------------------------------------------------------
    // BUG : wrong judgment in check_dir

    /**
     * @param $dir
     * @return int
     */
    public function check_dir($dir)
    {
        if ($dir && is_dir($dir) && is_writable($dir) && is_readable($dir)) {
            return 0;
        }
        $this->set_error('dir error : ' . $dir);

        return _C_WEBPHOTO_ERR_CHECK_DIR;
    }

    //---------------------------------------------------------
    // post class
    //---------------------------------------------------------

    /**
     * @param      $key
     * @param null $default
     * @return array|string
     */
    public function get_post_text($key, $default = null)
    {
        return $this->_post_class->get_post_text($key, $default);
    }

    /**
     * @param     $key
     * @param int $default
     * @return int
     */
    public function get_post_int($key, $default = 0)
    {
        return $this->_post_class->get_post_int($key, $default);
    }

    /**
     * @param     $key
     * @param int $default
     * @return float
     */
    public function get_post_float($key, $default = 0)
    {
        return $this->_post_class->get_post_float($key, $default);
    }

    /**
     * @param      $key
     * @param null $default
     */
    public function get_post($key, $default = null)
    {
        return $this->_post_class->get_post($key, $default);
    }

    //---------------------------------------------------------
    // item create class
    //---------------------------------------------------------

    /**
     * @param      $row
     * @param bool $flag_force
     * @return bool|void
     */
    public function format_and_insert_item($row, $flag_force = false)
    {
        $newid = $this->_item_create_class->format_and_insert($row, $flag_force);
        if (!$newid) {
            $this->set_error($this->_item_create_class->get_errors());

            return false;
        }

        return $newid;
    }

    /**
     * @param      $row
     * @param bool $flag_force
     * @return bool
     */
    public function format_and_update_item($row, $flag_force = false)
    {
        $ret = $this->_item_create_class->format_and_update($row, $flag_force);
        if (!$ret) {
            $this->set_error($this->_item_create_class->get_errors());

            return false;
        }

        return true;
    }

    //---------------------------------------------------------
    // mime class
    //---------------------------------------------------------

    /**
     * @param $ext
     * @return int|mixed
     */
    public function ext_to_kind($ext)
    {
        return $this->_mime_class->ext_to_kind($ext);
    }

    /**
     * @return array|bool|mixed
     */
    public function get_my_allowed_mimes()
    {
        return $this->_mime_class->get_my_allowed_mimes();
    }

    /**
     * @param $ext
     * @return bool|mixed
     */
    public function is_my_allow_ext($ext)
    {
        return $this->_mime_class->is_my_allow_ext($ext);
    }

    //---------------------------------------------------------
    // icon
    //---------------------------------------------------------

    /**
     * @param      $row
     * @param null $ext
     * @return mixed
     */
    public function build_item_row_icon_if_empty($row, $ext = null)
    {
        return $this->_icon_build_class->build_row_icon_if_empty($row, $ext);
    }

    /**
     * @param $ext
     * @return array
     */
    public function build_icon_image($ext)
    {
        return $this->_icon_build_class->build_icon_image($ext);
    }

    //---------------------------------------------------------
    // timestamp
    //---------------------------------------------------------

    /**
     * @param     $key
     * @param int $default
     * @return float|int
     */
    public function get_server_time_by_post($key, $default = 0)
    {
        $time = $this->_post_class->get_post_time($key, $default);
        if ($time > 0) {
            return $this->user_to_server_time($time);
        }

        return $default;
    }

    //---------------------------------------------------------
    // tmp dir
    //---------------------------------------------------------

    /**
     * @param $name
     * @return string
     */
    public function build_tmp_dir_file($name)
    {
        $str = $this->_TMP_DIR . '/' . $name;

        return $str;
    }

    /**
     * @param $name
     */
    public function unlink_tmp_dir_file($name)
    {
        if ($name) {
            $this->unlink_file($this->build_tmp_dir_file($name));
        }
    }

    /**
     * @param $name
     * @return string
     */
    public function build_file_dir_file($name)
    {
        $str = $this->_FILE_DIR . '/' . $name;

        return $str;
    }

    //---------------------------------------------------------
    // msg
    //---------------------------------------------------------

    /**
     * @return bool
     */
    public function check_msg_level_admin()
    {
        return $this->check_msg_level(_C_WEBPHOTO_MSG_LEVEL_ADMIN);
    }

    /**
     * @return bool
     */
    public function check_msg_level_user()
    {
        return $this->check_msg_level(_C_WEBPHOTO_MSG_LEVEL_USER);
    }

    /**
     * @param      $msg
     * @param bool $flag_highlight
     * @param bool $flag_br
     */
    public function set_msg_level_admin($msg, $flag_highlight = false, $flag_br = false)
    {
        if (!$this->check_msg_level_admin()) {
            return;    // no action
        }
        $str = $this->build_msg($msg, $flag_highlight, $flag_br);
        if ($str) {
            $this->set_msg($str);
        }
    }

    /**
     * @param      $msg
     * @param bool $flag_highlight
     * @param bool $flag_br
     */
    public function set_msg_level_user($msg, $flag_highlight = false, $flag_br = false)
    {
        if (!$this->check_msg_level_user()) {
            return;    // no action
        }
        $str = $this->build_msg($msg, $flag_highlight, $flag_br);
        if ($str) {
            $this->set_msg($str);
        }
    }

    // --- class end ---
}
