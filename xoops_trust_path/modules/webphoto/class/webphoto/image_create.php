<?php
// $Id: image_create.php,v 1.12 2010/10/06 02:22:46 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-10-01 K.OHWADA
// cmd_rotate()
// 2009-01-10 K.OHWADA
// merge webphoto_image_info
// add_icon()
// 2008-11-16 K.OHWADA
// image -> image_tmp
// 2008-11-08 K.OHWADA
// cmd_modify_photo() -> cmd_resize_rotate()
// 2008-10-01 K.OHWADA
// use _MIDDLES_PATH
// 2008-08-24 K.OHWADA
// added create_middle_from_image_file()
// 2008-08-01 K.OHWADA
// added create_thumb_from_image_file(), copy_thumb_icon_in_dir()
// 2008-07-01 K.OHWADA
// create_photo_thumb()
//  -> create_photo() create_thumb_from_upload() etc
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_edit_image_create
// wrapper for webphoto_lib_image_cmd
//=========================================================

/**
 * Class webphoto_image_create
 */
class webphoto_image_create
{
    public $_image_cmd_class;
    public $_config_class;
    public $_kind_class;

    public $_has_resize = false;
    public $_has_rotate = false;
    public $_flag_chmod = true;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_image_create constructor.
     * @param $dirname
     */
    public function __construct($dirname)
    {
        $this->_kind_class = webphoto_kind::getInstance();
        $this->_config_class = webphoto_config::getInstance($dirname);

        $this->_init_image_cmd();
    }

    /**
     * @param null $dirname
     * @return \webphoto_image_create
     */
    public static function getInstance($dirname = null)
    {
        static $instance;
        if (null === $instance) {
            $instance = new self($dirname);
        }

        return $instance;
    }

    //---------------------------------------------------------
    // init
    //---------------------------------------------------------
    public function _init_image_cmd()
    {
        $this->_image_cmd_class = webphoto_lib_image_cmd::getInstance();

        $this->_image_cmd_class->set_imagingpipe($this->get_config_by_name('imagingpipe'));
        $this->_image_cmd_class->set_forcegd2($this->get_config_by_name('forcegd2'));
        $this->_image_cmd_class->set_imagickpath($this->get_config_by_name('imagickpath'));
        $this->_image_cmd_class->set_netpbmpath($this->get_config_by_name('netpbmpath'));
        $this->_image_cmd_class->set_jpeg_quality($this->get_config_by_name('jpeg_quality'));

        $this->_image_cmd_class->set_normal_exts($this->get_image_exts());
        $this->_image_cmd_class->set_flag_chmod($this->_flag_chmod);

        $this->_has_resize = $this->_image_cmd_class->has_resize();
        $this->_has_rotate = $this->_image_cmd_class->has_rotate();
    }

    /**
     * @return bool
     */
    public function has_resize()
    {
        return $this->_has_resize;
    }

    /**
     * @return bool
     */
    public function has_rotate()
    {
        return $this->_has_rotate;
    }

    //---------------------------------------------------------
    // config class
    //---------------------------------------------------------

    /**
     * @param $name
     */
    public function get_config_by_name($name)
    {
        return $this->_config_class->get_by_name($name);
    }

    //---------------------------------------------------------
    // kind class
    //---------------------------------------------------------

    /**
     * @return array
     */
    public function get_image_exts()
    {
        return $this->_kind_class->get_image_exts();
    }

    //---------------------------------------------------------
    // image cmd class
    //---------------------------------------------------------

    /**
     * @param     $src_file
     * @param     $dst_file
     * @param     $max_width
     * @param     $max_height
     * @param int $rotate
     * @return mixed
     */
    public function cmd_resize_rotate($src_file, $dst_file, $max_width, $max_height, $rotate = 0)
    {
        return $this->_image_cmd_class->resize_rotate($src_file, $dst_file, $max_width, $max_height, $rotate);
    }

    /**
     * @param     $src_file
     * @param     $dst_file
     * @param int $rotate
     * @return int
     */
    public function cmd_rotate($src_file, $dst_file, $rotate = 0)
    {
        $ret = $this->_image_cmd_class->resize_rotate($src_file, $dst_file, 0, 0, $rotate);
        if ($ret < 0) {
            return -1;
        }

        return 1;
    }

    /**
     * @param $src_file
     * @param $dst_file
     * @param $max_width
     * @param $max_height
     * @return mixed
     */
    public function cmd_resize($src_file, $dst_file, $max_width, $max_height)
    {
        return $this->_image_cmd_class->resize_rotate($src_file, $dst_file, $max_width, $max_height, 0);
    }

    /**
     * @param $src_file
     * @param $dst_file
     * @param $icon_file
     * @return mixed
     */
    public function cmd_add_icon($src_file, $dst_file, $icon_file)
    {
        return $this->_image_cmd_class->add_icon($src_file, $dst_file, $icon_file);
    }

    /**
     * @param      $src_file
     * @param      $dst_file
     * @param null $option
     * @return mixed
     */
    public function cmd_convert($src_file, $dst_file, $option = null)
    {
        return $this->_image_cmd_class->convert($src_file, $dst_file, $option);
    }

    // --- class end ---
}
