<?php
// $Id: video_middle_thumb_create.php,v 1.5 2010/10/06 02:22:46 ohwada Exp $

//=========================================================
// webphoto module
// 2008-01-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-10-01 K.OHWADA
// webphoto_edit_base -> webphoto_edit_base_create
// 2010-03-18 K.OHWADA
// format_and_update_item()
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_ffmpeg etc
// 2009-03-15 K.OHWADA
// create_small_param()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_edit_video_middle_thumb_create
//=========================================================

/**
 * Class webphoto_edit_video_middle_thumb_create
 */
class webphoto_edit_video_middle_thumb_create extends webphoto_edit_base_create
{
    public $_ffmpeg_class;

    //  var $_middle_thumb_create_class;

    public $_item_build_class;
    public $_item_create_class;
    public $_file_action_class;

    // config
    public $_cfg_makethumb;
    public $_cfg_use_ffmpeg;

    public $_item_row = null;
    public $_item_cat_id = 0;
    public $_flag_created = false;
    public $_flag_failed = false;
    public $_file_jpeg = null;

    public $_VIDEO_THUMB_MAX = _C_WEBPHOTO_VIDEO_THUMB_PLURAL_MAX;
    public $_SUB_DIR_JPEGS = 'jpegs';

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_edit_video_middle_thumb_create constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_ffmpeg_class = webphoto_ffmpeg::getInstance($dirname, $trust_dirname);
        $this->_item_create_class = webphoto_edit_item_create::getInstance($dirname, $trust_dirname);
        $this->_item_build_class = webphoto_edit_item_build::getInstance($dirname, $trust_dirname);
        $this->_file_action_class = webphoto_edit_file_action::getInstance($dirname, $trust_dirname);

        $this->_cfg_makethumb = $this->get_config_by_name('makethumb');
        $this->_cfg_use_ffmpeg = $this->get_config_by_name('use_ffmpeg');
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_edit_video_middle_thumb_create|\webphoto_lib_error
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
    // video thumb
    //---------------------------------------------------------

    /**
     * @param $item_row
     * @return bool
     */
    public function video_thumb($item_row)
    {
        $num = $this->_post_class->get_post_text('num');
        $ret = $this->video_thumb_exec($item_row, $num);

        return $this->build_failed_msg($ret);
    }

    /**
     * @param $item_row
     * @param $num
     * @return int
     */
    public function video_thumb_exec($item_row, $num)
    {
        $this->clear_msg_array();

        $ret = $this->update_video_thumb_by_item_row($item_row, $num);
        if ($ret < 0) {
            return $ret;
        }

        if ($this->_flag_failed) {
            $this->set_msg_array($this->get_constant('ERR_VIDEO_THUMB'));
        }

        return 0;
    }

    // Fatal error: Call to undefined method build_failed_msg()

    /**
     * @param $ret
     * @return bool
     */
    public function build_failed_msg($ret)
    {
        switch ($ret) {
            case _C_WEBPHOTO_ERR_DB:
                $this->set_error_in_head_with_admin_info('DB Error');

                return false;
        }

        return true;
    }

    //---------------------------------------------------------
    // update video thumb
    //---------------------------------------------------------

    /**
     * @param $item_row
     * @param $num
     * @return int
     */
    public function update_video_thumb_by_item_row($item_row, $num)
    {
        if (!is_array($item_row)) {
            return 0;  // no action
        }

        $item_id = $item_row['item_id'];
        $cat_id = $item_row['item_cat_id'];
        $ext = $item_row['item_ext'];

        $this->_item_row = null;
        $this->_item_cat_id = 0;

        $file_id_array = $this->update_video_jpeg_thumb($item_row, $num);

        $row_update = $this->_item_build_class->build_row_files($item_row, $file_id_array);

        // --- update item ---
        $ret = $this->format_and_update_item($row_update, $this->_flag_force_db);
        if (!$ret) {
            return _C_WEBPHOTO_ERR_DB;
        }

        // save row
        $this->_item_row = $row_update;
        $this->_item_cat_id = $cat_id;

        return 0;
    }

    //---------------------------------------------------------
    // update video thumb
    //---------------------------------------------------------

    /**
     * @param $item_row
     * @param $num
     * @return array|null
     */
    public function update_video_jpeg_thumb($item_row, $num)
    {
        $item_id = $item_row['item_id'];

        $file_id_array = null;

        $jpeg_id = $this->update_video_jpeg($item_row, $num);

        if ($jpeg_id) {
            $file_id_array = $this->update_video_middle_thumb($item_row, $this->_file_jpeg);
            $file_id_array['jpeg_id'] = $jpeg_id;
        }

        // remove files
        $this->unlink_video_thumb_temp_files($item_id);

        return $file_id_array;
    }

    /**
     * @param $item_row
     * @param $num
     * @return bool|int|mixed|void
     */
    public function update_video_jpeg($item_row, $num)
    {
        $this->_flag_created = false;
        $this->_flag_failed = false;

        $item_id = $item_row['item_id'];

        $jpeg_id = 0;

        // created jpeg
        $src_file = $this->build_video_thumb_file($item_id, $num);
        if (is_file($src_file)) {
            $jpeg_id = $this->create_update_video_thumb_common($item_row, $src_file, _C_WEBPHOTO_ITEM_FILE_JPEG);
        }

        if ($jpeg_id > 0) {
            $this->_flag_created = true;
            $this->_file_jpeg = $src_file;
        } else {
            $this->_flag_failed = true;
        }

        return $jpeg_id;
    }

    /**
     * @param $item_row
     * @param $src_file
     * @return array
     */
    public function update_video_middle_thumb($item_row, $src_file)
    {
        // created thumb
        $thumb_id = $this->create_update_video_thumb_common($item_row, $src_file, _C_WEBPHOTO_ITEM_FILE_THUMB);
        $middle_id = $this->create_update_video_thumb_common($item_row, $src_file, _C_WEBPHOTO_ITEM_FILE_MIDDLE);
        $small_id = $this->create_update_video_thumb_common($item_row, $src_file, _C_WEBPHOTO_ITEM_FILE_SMALL);

        // update date
        $file_id_array = [
            'thumb_id' => $thumb_id,
            'middle_id' => $middle_id,
            'small_id' => $small_id,
        ];

        return $file_id_array;
    }

    /**
     * @param $item_row
     * @param $src_file
     * @param $item_name
     * @return bool|int|mixed|void
     */
    public function create_update_video_thumb_common($item_row, $src_file, $item_name)
    {
        $ret = $this->_file_action_class->create_update_file_for_video_thumb($item_row, $src_file, $item_name);
        if (!$ret) {
            $this->set_error($this->_file_action_class->get_errors());
        }

        return $ret;
    }

    /**
     * @param $item_id
     */
    public function unlink_video_thumb_temp_files($item_id)
    {
        for ($i = 1; $i <= $this->_VIDEO_THUMB_MAX; ++$i) {
            $file = $this->build_video_thumb_file($item_id, $i);
            $this->unlink_file($file);
        }
    }

    /**
     * @param $item_id
     * @param $num
     * @return null|string
     */
    public function build_video_thumb_file($item_id, $num)
    {
        $file = null;
        $name = $this->_ffmpeg_class->build_thumb_name($item_id, $num);
        if ($name) {
            $file = $this->_TMP_DIR . '/' . $name;
        }

        return $file;
    }

    //---------------------------------------------------------
    // item create class
    //---------------------------------------------------------

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
    // get param
    //---------------------------------------------------------

    /**
     * @return bool
     */
    public function get_flag_created()
    {
        return $this->_flag_created;
    }

    /**
     * @return bool
     */
    public function get_flag_failed()
    {
        return $this->_flag_failed;
    }

    // --- class end ---
}
