<?php
// $Id: ffmpeg.php,v 1.6 2010/10/06 02:22:46 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-10-01 K.OHWADA
// create_wav()
// 2009-11-11 K.OHWADA
// $trust_dirname
// 2009-10-25 K.OHWADA
// webphoto_cmd_base
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_ffmpeg
// wrapper for webphoto_lib_ffmpeg
//=========================================================

/**
 * Class webphoto_ffmpeg
 */
class webphoto_ffmpeg extends webphoto_cmd_base
{
    public $_ffmpeg_class;

    public $_cfg_use_ffmpeg = false;

    public $_thumb_id = 0;

    public $_PLURAL_MAX = _C_WEBPHOTO_VIDEO_THUMB_PLURAL_MAX;
    public $_PLURAL_SECOND = 0;
    public $_PLURAL_FIRST = 0;
    public $_PLURAL_OFFSET = 1;

    public $_SINGLE_SECOND = 1;

    public $_THUMB_PREFIX = _C_WEBPHOTO_VIDEO_THUMB_PREFIX;   // tmp_ffmpeg_

    public $_CMD_FFMPEG = 'ffmpeg';

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_ffmpeg constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_cfg_use_ffmpeg = $this->_config_class->get_by_name('use_ffmpeg');
        $cfg_ffmpegpath = $this->_config_class->get_dir_by_name('ffmpegpath');

        $this->_ffmpeg_class = webphoto_lib_ffmpeg::getInstance();
        $this->_ffmpeg_class->set_tmp_path($this->_TMP_DIR);
        $this->_ffmpeg_class->set_cmd_path($cfg_ffmpegpath);
        $this->_ffmpeg_class->set_ext($this->_JPEG_EXT);
        $this->_ffmpeg_class->set_flag_chmod(true);

        $this->set_debug_by_ini_name($this->_ffmpeg_class);
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_ffmpeg|\webphoto_lib_error
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
    // duration
    //---------------------------------------------------------

    /**
     * @param $file
     * @return array|null
     */
    public function get_video_info($file)
    {
        if (!$this->_cfg_use_ffmpeg) {
            return null;
        }

        return $this->_ffmpeg_class->get_video_info($file);
    }

    //---------------------------------------------------------
    // create jpeg
    //---------------------------------------------------------

    /**
     * @param $src_file
     * @param $dst_file
     * @return int
     */
    public function create_jpeg($src_file, $dst_file)
    {
        if (!$this->_cfg_use_ffmpeg) {
            return 0;
        }

        $this->_ffmpeg_class->create_single_thumb($src_file, $dst_file, $this->_SINGLE_SECOND);
        if (!is_file($dst_file)) {
            return -1;
        }

        return 1;
    }

    //---------------------------------------------------------
    // plural images
    //---------------------------------------------------------

    /**
     * @param $id
     * @param $file
     * @return bool|int
     */
    public function create_plural_images($id, $file)
    {
        if (!$this->_cfg_use_ffmpeg) {
            return false;
        }

        $this->_ffmpeg_class->set_prefix($this->build_ffmpeg_prefix($id));
        $this->_ffmpeg_class->set_offset($this->_PLURAL_OFFSET);

        $count = $this->_ffmpeg_class->create_thumbs($file, $this->_PLURAL_MAX, $this->_PLURAL_SECOND);

        if (0 == $count) {
            $this->set_error($this->_ffmpeg_class->get_msg_array());

            return -1;
        }

        return 1;
    }

    /**
     * @param $id
     * @return string
     */
    public function build_ffmpeg_prefix($id)
    {
        // prefix_123_
        $str = $this->_THUMB_PREFIX . $id . '_';

        return $str;
    }

    // for misc_form

    /**
     * @param $id
     * @param $num
     * @return string
     */
    public function build_thumb_name($id, $num)
    {
        // prefix_123_456.jpg
        $str = $this->build_thumb_node($id, $num) . '.' . $this->_JPEG_EXT;

        return $str;
    }

    /**
     * @param $id
     * @param $num
     * @return string
     */
    public function build_thumb_node($id, $num)
    {
        // prefix_123_456
        $str = $this->build_ffmpeg_prefix($id) . $num;

        return $str;
    }

    //---------------------------------------------------------
    // flash
    //---------------------------------------------------------

    /**
     * @param      $src_file
     * @param      $dst_file
     * @param null $option
     * @return bool
     */
    public function create_flash($src_file, $dst_file, $option = null)
    {
        if (empty($option)) {
            $option = $this->get_cmd_option($src_file, $this->_CMD_FFMPEG);
        }

        $ret = $this->_ffmpeg_class->create_flash($src_file, $dst_file, $option);
        if (!$ret) {
            $this->set_error($this->_ffmpeg_class->get_msg_array());
        }

        return $ret;
    }

    //---------------------------------------------------------
    // mp3
    //---------------------------------------------------------

    /**
     * @param      $src_file
     * @param      $dst_file
     * @param null $option
     * @return bool
     */
    public function create_mp3($src_file, $dst_file, $option = null)
    {
        if (empty($option)) {
            $option = $this->get_cmd_option($src_file, $this->_CMD_FFMPEG);
        }

        $ret = $this->_ffmpeg_class->create_mp3($src_file, $dst_file, $option);
        if (!$ret) {
            $this->set_error($this->_ffmpeg_class->get_msg_array());
        }

        return $ret;
    }

    //---------------------------------------------------------
    // wav
    //---------------------------------------------------------

    /**
     * @param      $src_file
     * @param      $dst_file
     * @param null $option
     * @return bool
     */
    public function create_wav($src_file, $dst_file, $option = null)
    {
        if (empty($option)) {
            $option = $this->get_cmd_option($src_file, $this->_CMD_FFMPEG);
        }

        $ret = $this->_ffmpeg_class->create_wav($src_file, $dst_file, $option);
        if (!$ret) {
            $this->set_error($this->_ffmpeg_class->get_msg_array());
        }

        return $ret;
    }

    // --- class end ---
}
