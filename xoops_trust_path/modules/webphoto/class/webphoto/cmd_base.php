<?php
// $Id: cmd_base.php,v 1.3 2010/03/31 02:49:06 ohwada Exp $

//=========================================================
// webphoto module
// 2009-10-25 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-03-24 K.OHWADA
// build_ipeg_file -> build_jpeg_file
// 2009-11-11 K.OHWADA
// webphoto_lib_error -> webphoto_base_ini
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_cmd_base
//=========================================================
class webphoto_cmd_base extends webphoto_base_ini
{
    public $_config_class;
    public $_mime_class;
    public $_command_class;
    public $_ini_safe_mode;

    public $_flag_chmod = true;

    public $_DIRNAME;
    public $_TMP_DIR;

    public $_JPEG_EXT   = 'jpg';
    public $_TEXT_EXT   = 'txt';
    public $_MP3_EXT    = 'mp3';
    public $_CHMOD_MODE = 0777;

    public $_DEBUG = false;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct($dirname, $trust_dirname)
    {
        $this->_DIRNAME = $dirname;
        parent::__construct($dirname, $trust_dirname);

        $this->_mime_class = webphoto_mime::getInstance($dirname, $trust_dirname);

        $this->_config_class = webphoto_config::getInstance($dirname);

        $this->_ini_safe_mode = ini_get('safe_mode');
        $this->_TMP_DIR       = $this->_config_class->get_work_dir('tmp');
    }

    public function set_command_class($class)
    {
        $this->_command_class = $class;
    }

    //---------------------------------------------------------
    // create
    //---------------------------------------------------------
    public function get_cmd_option($file, $cmd)
    {
        $ext = $this->parse_ext($file);

        $options = $this->_mime_class->get_cached_mime_options_by_ext($ext);
        if (isset($options[$cmd])) {
            return $options[$cmd];
        }

        return '';
    }

    public function build_jpeg_file($item_id)
    {
        return $this->build_file_by_prefix_ext($this->build_prefix($item_id), $this->_JPEG_EXT);
    }

    public function build_mp3_file($item_id)
    {
        return $this->build_file_by_prefix_ext($this->build_prefix($item_id), $this->_MP3_EXT);
    }

    public function build_prefix($item_id)
    {
        $prefix = 'tmp_' . sprintf('%04d', $item_id);
        return $prefix;
    }

    public function build_file_by_prefix_ext($prefix, $ext)
    {
        $file = $this->_TMP_DIR . '/' . $prefix . '.' . $ext;
        return $file;
    }

    public function is_file_in_array($file, $arr)
    {
        return $this->is_ext_in_array($this->parse_ext($file), $arr);
    }

    public function is_ext_in_array($ext, $arr)
    {
        if (in_array(strtolower($ext), $arr)) {
            return true;
        }
        return false;
    }

    public function parse_ext($file)
    {
        return strtolower(substr(strrchr($file, '.'), 1));
    }

    public function chmod_file($file)
    {
        if ($this->_flag_chmod && !$this->_ini_safe_mode && is_file($file)) {
            chmod($file, $this->_CHMOD_MODE);
        }
    }

    public function set_get_errors($msg)
    {
        $this->set_error($msg);
        return $this->get_errors();
    }

    //---------------------------------------------------------
    // config
    //---------------------------------------------------------
    public function get_config_by_name($name)
    {
        return $this->_config_class->get_by_name($name);
    }

    public function get_config_dir_by_name($name)
    {
        return $this->_config_class->get_dir_by_name($name);
    }

    //---------------------------------------------------------
    // debug
    //---------------------------------------------------------
    public function set_debug_by_const_name($class, $name_in)
    {
        $name = strtoupper('_P_' . $this->_DIRNAME . '_' . $name_in);
        if (defined($name)) {
            $val          = constant($name);
            $this->_DEBUG = (bool)$val;
            if (is_object($class)) {
                $class->set_debug($val);
            }
        }
    }

    public function set_debug_by_ini_name($class, $name = 'debug_cmd')
    {
        $val = $this->get_ini($name);
        if ($val) {
            $this->set_debug($val);
            if (is_object($class)) {
                $class->set_debug($val);
            }
        }
    }

    public function set_debug($val)
    {
        $this->_DEBUG = (bool)$val;
    }

    // --- class end ---
}
