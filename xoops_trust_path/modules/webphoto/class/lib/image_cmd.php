<?php
// $Id: image_cmd.php,v 1.9 2010/10/06 02:22:46 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-10-01 K.OHWADA
// same_ext()
// 2009-04-21 K.OHWADA
// chmod_file()
// 2009-01-10 K.OHWADA
// add_icon()
// 2008-11-08 K.OHWADA
// webphoto_lib_gd etc
// 2008-08-24 K.OHWADA
// exec_modify_photo()
// 2008-07-01 K.OHWADA
// changed rename to copy in modify_photo
// removed unlink in modify_photo
// removed create thumb icon
// 2008-04-02 K.OHWADA
// supported gif functions of GD
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_lib_image_cmd
//=========================================================

/**
 * Class webphoto_lib_image_cmd
 */
class webphoto_lib_image_cmd
{
    public $_gd_class;
    public $_imagemagick_class;
    public $_netpbm_class;

    public $_ini_safe_mode;

    public $_cfg_imagingpipe = 0;     // PIPEID_GD;
    public $_cfg_forcegd2 = false;
    public $_cfg_imagickpath = null;
    public $_cfg_netpbmpath = null;

    public $_normal_exts = ['jpg', 'jpeg', 'gif', 'png'];
    public $_flag_chmod = true;
    public $_msgs = [];

    public $_PATH_WATERMRAK;

    public $_PIPEID_GD = 0;
    public $_PIPEID_IMAGICK = 1;
    public $_PIPEID_NETPBM = 2;

    public $_CODE_READFAULT = -1;
    public $_CODE_FAILED = -2;
    public $_CODE_CREATED = 1;
    public $_CODE_COPIED = 2;
    public $_CODE_SKIPPED = 3;
    public $_CODE_RESIZE = 5;

    public $_CHMOD_MODE = 0777;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        $this->_gd_class = webphoto_lib_gd::getInstance();
        $this->_imagemagick_class = webphoto_lib_imagemagick::getInstance();
        $this->_netpbm_class = webphoto_lib_netpbm::getInstance();

        $this->_ini_safe_mode = ini_get('safe_mode');
    }

    /**
     * @return \webphoto_lib_image_cmd
     */
    public static function getInstance()
    {
        static $instance;
        if (null === $instance) {
            $instance = new self();
        }

        return $instance;
    }

    //---------------------------------------------------------
    // set param
    //---------------------------------------------------------

    /**
     * @param $val
     */
    public function set_watermark($val)
    {
        $this->_PATH_WATERMRAK = $val;
    }

    /**
     * @param $val
     */
    public function set_imagingpipe($val)
    {
        $this->_cfg_imagingpipe = $val;
    }

    /**
     * @param $val
     */
    public function set_forcegd2($val)
    {
        $this->_gd_class->set_force_gd2($val);
    }

    /**
     * @param $val
     */
    public function set_jpeg_quality($val)
    {
        $this->_gd_class->set_jpeg_quality($val);
    }

    /**
     * @param $val
     */
    public function set_imagickpath($val)
    {
        $this->_imagemagick_class->set_cmd_path($this->add_separator_to_tail($val));
    }

    /**
     * @param $val
     */
    public function set_netpbmpath($val)
    {
        $this->_netpbm_class->set_cmd_path($this->add_separator_to_tail($val));
    }

    /**
     * @param $val
     */
    public function set_normal_exts($val)
    {
        if (is_array($val)) {
            $this->_normal_exts = $val;
        }
    }

    /**
     * @param $val
     */
    public function set_flag_chmod($val)
    {
        $this->_flag_chmod = (bool)$val;
    }

    /**
     * @param $str
     * @return string
     */
    public function add_separator_to_tail($str)
    {
        // Check the path to binaries of imaging packages
        if ('' != trim($str) && DIRECTORY_SEPARATOR != mb_substr($str, -1)) {
            $str .= DIRECTORY_SEPARATOR;
        }

        return $str;
    }

    //---------------------------------------------------------
    // property
    //---------------------------------------------------------

    /**
     * @return bool
     */
    public function has_resize()
    {
        if ($this->_cfg_imagingpipe == $this->_PIPEID_IMAGICK) {
            return true;
        }
        if ($this->_cfg_imagingpipe == $this->_PIPEID_NETPBM) {
            return true;
        }
        if ($this->_gd_class->can_truecolor()) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function has_rotate()
    {
        if ($this->_cfg_imagingpipe == $this->_PIPEID_IMAGICK) {
            return true;
        }
        if ($this->_cfg_imagingpipe == $this->_PIPEID_NETPBM) {
            return true;
        }
        if ($this->_gd_class->can_rotate()) {
            return true;
        }

        return false;
    }

    //---------------------------------------------------------
    // return value
    //   -1 : read fault
    //    1 : complete created
    //    2 : copied
    //    3 : skipped
    //    5 : resize
    //---------------------------------------------------------

    /**
     * @param     $src_file
     * @param     $dst_file
     * @param     $max_width
     * @param     $max_height
     * @param int $rotate
     * @return int
     */
    public function resize_rotate($src_file, $dst_file, $max_width, $max_height, $rotate = 0)
    {
        if (!is_readable($src_file)) {
            return $this->_CODE_READFAULT; // read fault
        }

        if (!$this->is_image_file($src_file)) {
            return $this->_CODE_SKIPPED;
        }

        $ret = $this->exec_resize_rotate($src_file, $dst_file, $max_width, $max_height, $rotate);

        if ($this->_flag_chmod) {
            $this->chmod_file($dst_file, $this->_CHMOD_MODE);
        }

        return $ret;
    }

    /**
     * @param     $src_file
     * @param     $dst_file
     * @param     $max_width
     * @param     $max_height
     * @param int $rotate
     * @return int
     */
    public function exec_resize_rotate($src_file, $dst_file, $max_width, $max_height, $rotate = 0)
    {
        $width = 0;
        $height = 0;
        $flag_resize = $this->require_resize($src_file, $max_width, $max_height);
        $flag_same = $this->same_ext($src_file, $dst_file);

        // only copy when small enough and no rotate
        if ((!$flag_resize) && (0 == $rotate) && $flag_same) {
            $this->copy_file($src_file, $dst_file);

            return $this->_CODE_COPIED; // copied
        }

        $ret_code = $this->_CODE_CREATED;   // success

        if ($flag_resize) {
            $ret_code = $this->_CODE_RESIZE;    // resize
            $width = $max_width;
            $height = $max_height;
        }

        if ($this->_cfg_imagingpipe == $this->_PIPEID_IMAGICK) {
            $ret = $this->_imagemagick_class->resize_rotate($src_file, $dst_file, $width, $height, $rotate);

            if ($ret && file_exists($this->_PATH_WATERMRAK)) {
                $this->_imagemagick_class->add_watermark($src_file, $dst_file, $this->_PATH_WATERMRAK);
            }
        } elseif ($this->_cfg_imagingpipe == $this->_PIPEID_NETPBM) {
            $this->_netpbm_class->resize_rotate($src_file, $dst_file, $width, $height, $rotate);
        } else {
            $this->_gd_class->resize_rotate($src_file, $dst_file, $width, $height, $rotate);
        }

        if (is_readable($dst_file)) {
            return $ret_code;  // complete created
        }

        // didn't exec convert, rename it.
        if ($flag_same) {
            $this->copy_file($src_file, $dst_file);

            return $this->_CODE_COPIED; // copied
        }

        return $this->_CODE_FAILED;
    }

    /**
     * @param $src_file
     * @param $max_width
     * @param $max_height
     * @return bool
     */
    public function require_resize($src_file, $max_width, $max_height)
    {
        if (0 == $max_width) {
            return false;
        }
        if (0 == $max_height) {
            return false;
        }

        $image_size = getimagesize($src_file);
        if (!is_array($image_size)) {
            return false;
        }
        if ($image_size[0] > $max_width) {
            return true;
        }
        if ($image_size[1] > $max_height) {
            return true;
        }

        return false;
    }

    //---------------------------------------------------------
    // add icon
    //---------------------------------------------------------

    /**
     * @param $src_file
     * @param $dst_file
     * @param $icon_file
     */
    public function add_icon($src_file, $dst_file, $icon_file)
    {
        if ($this->_cfg_imagingpipe == $this->_PIPEID_IMAGICK) {
            $this->_imagemagick_class->add_icon($src_file, $dst_file, $icon_file);
        }
    }

    /**
     * @param      $src_file
     * @param      $dst_file
     * @param null $option
     */
    public function convert($src_file, $dst_file, $option = null)
    {
        if ($this->_cfg_imagingpipe == $this->_PIPEID_IMAGICK) {
            $this->_imagemagick_class->convert($src_file, $dst_file, $option);
        }
    }

    //---------------------------------------------------------
    // utility
    //---------------------------------------------------------

    /**
     * @param $file
     * @return bool
     */
    public function is_image_file($file)
    {
        return $this->is_normal_ext($this->parse_ext($file));
    }

    /**
     * @param $file
     * @return string
     */
    public function parse_ext($file)
    {
        return mb_strtolower(mb_substr(mb_strrchr($file, '.'), 1));
    }

    /**
     * @param $ext
     * @return bool
     */
    public function is_normal_ext($ext)
    {
        if (in_array(mb_strtolower($ext), $this->_normal_exts)) {
            return true;
        }

        return false;
    }

    /**
     * @param $src
     * @param $dst
     * @return bool
     */
    public function copy_file($src, $dst)
    {
        if ($this->check_file($src)) {
            return copy($src, $dst);
        }

        return false;
    }

    /**
     * @param $file
     * @return bool
     */
    public function check_file($file)
    {
        if ($file && file_exists($file) && is_file($file) && !is_dir($file)) {
            return true;
        }
        $this->set_msg('not exist file : ' . $file);

        return false;
    }

    /**
     * @param $file
     * @param $mode
     */
    public function chmod_file($file, $mode)
    {
        if (!$this->_ini_safe_mode && is_file($file)) {
            chmod($file, $mode);
        }
    }

    /**
     * @param $src_file
     * @param $dst_file
     * @return bool
     */
    public function same_ext($src_file, $dst_file)
    {
        if ($this->parse_ext($src_file) == $this->parse_ext($dst_file)) {
            return true;
        }

        return false;
    }

    //---------------------------------------------------------
    // msg
    //---------------------------------------------------------
    public function clear_msgs()
    {
        $this->_msgs = [];
    }

    /**
     * @return array
     */
    public function get_msgs()
    {
        return $this->_msgs;
    }

    /**
     * @param $msg
     */
    public function set_msg($msg)
    {
        // array type
        if (is_array($msg)) {
            foreach ($msg as $m) {
                $this->_msgs[] = $m;
            }

            // string type
        } else {
            $arr = explode("\n", $msg);
            foreach ($arr as $m) {
                $this->_msgs[] = $m;
            }
        }
    }

    // --- class end ---
}
