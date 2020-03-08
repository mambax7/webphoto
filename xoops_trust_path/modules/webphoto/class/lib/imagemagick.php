<?php
// $Id: imagemagick.php,v 1.6 2010/11/16 23:43:38 ohwada Exp $

//=========================================================
// webphoto module
// 2008-11-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-11-11 K.OHWADA
// changed resize_rotate()
// 2010-05-30 K.OHWADA
// is_win_os()
// 2009-11-21 K.OHWADA
// BUG: Fatal error: Call to undefined method webphoto_lib_imagemagick::get_msg_array()
// 2009-01-10 K.OHWADA
// version()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_lib_imagemagick
//=========================================================

/**
 * Class webphoto_lib_imagemagick
 */
class webphoto_lib_imagemagick
{
    public $_cmd_convert = 'convert';
    public $_cmd_composite = 'composite';

    public $_cmd_path = null;
    public $_flag_chmod = false;
    public $_msg_array = [];

    public $_CHMOD_MODE = 0777;
    public $_DEBUG = false;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        // dummy
    }

    /**
     * @return \webphoto_lib_imagemagick
     */
    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new self();
        }

        return $instance;
    }

    //---------------------------------------------------------
    // main
    //---------------------------------------------------------

    /**
     * @param $val
     */
    public function set_cmd_path($val)
    {
        $this->_cmd_path = $val;
        $this->_cmd_convert = $this->_cmd_path . 'convert';
        $this->_cmd_composite = $this->_cmd_path . 'composite';

        if ($this->is_win_os()) {
            $this->_cmd_convert = $this->conv_win_cmd($this->_cmd_convert);
            $this->_cmd_composite = $this->conv_win_cmd($this->_cmd_composite);
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
     * @param $val
     */
    public function set_debug($val)
    {
        $this->_DEBUG = (bool)$val;
    }

    /**
     * @param     $src
     * @param     $dst
     * @param int $max_width
     * @param int $max_height
     * @param int $rorate
     * @return bool
     */
    public function resize_rotate($src, $dst, $max_width = 0, $max_height = 0, $rorate = 0)
    {
        $option = '';

        if (($max_width > 0) && ($max_height > 0)) {
            $option .= ' -geometry ' . $max_width . 'x' . $max_height;
        }

        if ($rorate > 0) {
            $option .= ' -rotate ' . $rorate;
        }

        $this->convert($src, $dst, $option);

        return true;
    }

    /**
     * @param $src
     * @param $dst
     * @param $mark
     */
    public function add_watermark($src, $dst, $mark)
    {
        $option = '-compose plus ';
        $this->composite($src, $dst, $mark, $option);
    }

    /**
     * @param $src
     * @param $dst
     * @param $icon
     */
    public function add_icon($src, $dst, $icon)
    {
        $option = ' -gravity southeast ';
        $this->composite($src, $dst, $icon, $option);
    }

    /**
     * @param        $src
     * @param        $dst
     * @param string $option
     * @return bool
     */
    public function convert($src, $dst, $option = '')
    {
        $cmd = $this->_cmd_convert . ' ' . $option . ' ' . $src . ' ' . $dst;
        $ret_array = null;
        exec("$cmd 2>&1", $ret_array);
        if ($this->_DEBUG) {
            echo $cmd . "<br>\n";
            print_r($ret_array);
        }

        $this->set_msg($cmd);
        $this->set_msg($ret_array);

        if (is_file($dst) && filesize($dst)) {
            if ($this->_flag_chmod) {
                $this->chmod_file($dst, $this->_CHMOD_MODE);
            }

            return true;
        }

        return false;
    }

    /**
     * @param        $src
     * @param        $dst
     * @param        $change
     * @param string $option
     * @return bool
     */
    public function composite($src, $dst, $change, $option = '')
    {
        $cmd = $this->_cmd_composite . ' ' . $option . ' ' . $change . ' ' . $src . ' ' . $dst;

        $ret_array = null;
        exec("$cmd 2>&1", $ret_array);
        if ($this->_DEBUG) {
            echo $cmd . "<br>\n";
            print_r($ret_array);
        }

        $this->set_msg($cmd);
        $this->set_msg($ret_array);

        if (is_file($dst) && filesize($dst)) {
            if ($this->_flag_chmod) {
                $this->chmod_file($dst, $this->_CHMOD_MODE);
            }

            return true;
        }

        return false;
    }

    /**
     * @param $file
     * @param $mode
     */
    public function chmod_file($file, $mode)
    {
        if (!$this->_ini_safe_mode) {
            chmod($file, $mode);
        }
    }

    //---------------------------------------------------------
    // version
    //---------------------------------------------------------

    /**
     * @param $path
     * @return array
     */
    public function version($path)
    {
        $convert = $path . 'convert';
        if ($this->is_win_os()) {
            $convert = $this->conv_win_cmd($convert);
        }

        $cmd = $convert . ' --help';
        exec($cmd, $ret_array);
        if (count($ret_array) > 0) {
            $ret = true;
            $str = $ret_array[0] . "<br>\n";
        } else {
            $ret = false;
            $str = 'Error: ' . $convert . " can't be executed";
        }

        return [$ret, $str];
    }

    //---------------------------------------------------------
    // utility
    //---------------------------------------------------------

    /**
     * @return bool
     */
    public function is_win_os()
    {
        if (0 === mb_strpos(PHP_OS, 'WIN')) {
            return true;
        }

        return false;
    }

    /**
     * @param $cmd
     * @return string
     */
    public function conv_win_cmd($cmd)
    {
        $str = '"' . $cmd . '.exe"';

        return $str;
    }

    //---------------------------------------------------------
    // msg
    //---------------------------------------------------------
    public function clear_msg_array()
    {
        $this->_msg_array = [];
    }

    // BUG: Fatal error: Call to undefined method webphoto_lib_imagemagick::get_msg_array()

    /**
     * @return array
     */
    public function get_msg_array()
    {
        return $this->_msg_array;
    }

    /**
     * @param $ret_array
     */
    public function set_msg($ret_array)
    {
        if (is_array($ret_array)) {
            foreach ($ret_array as $line) {
                $this->_msg_array[] = $line;
            }
        } else {
            $this->_msg_array[] = $ret_array;
        }
    }

    // --- class end ---
}
