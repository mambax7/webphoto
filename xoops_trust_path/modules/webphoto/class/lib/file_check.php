<?php
// $Id: file_check.php,v 1.2 2009/04/21 15:14:54 ohwada Exp $

//=========================================================
// webphoto module
// 2009-04-19 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-04-21 K.OHWADA
// chmod_file()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_lib_file_check
//=========================================================

/**
 * Class webphoto_lib_file_check
 */
class webphoto_lib_file_check
{
    public $_dir_class;

    public $_ini_safe_mode;

    public $_DIRNAME;
    public $_TRUST_DIRNAME;
    public $_MODULE_DIR;
    public $_TRUST_DIR;

    public $_CHMOD_MODE = 0777;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_lib_file_check constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        $this->_DIRNAME = $dirname;
        $this->_MODULE_DIR = XOOPS_ROOT_PATH . '/modules/' . $dirname;
        $this->_TRUST_DIRNAME = $trust_dirname;
        $this->_TRUST_DIR = XOOPS_TRUST_PATH . '/modules/' . $trust_dirname;

        $this->_dir_class = webphoto_lib_dir::getInstance();

        $this->_ini_safe_mode = ini_get('safe_mode');
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_lib_file_check
     */
    public static function getInstance($dirname = null, $trust_dirname = null)
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new self($dirname, $trust_dirname);
        }

        return $instance;
    }

    //---------------------------------------------------------
    // function
    //---------------------------------------------------------

    /**
     * @param $name
     * @return string
     */
    public function create_list($name)
    {
        $data = '';
        $dir = $this->build_dir($name);

        $files = $this->_dir_class->get_files_in_deep_dir($dir);
        foreach ($files as $file) {
            $file_full = $dir . $file;
            if (is_dir($file_full) || !is_file($file_full)) {
                continue;
            }
            //      $md5  = md5_file( $file_full ) ;
            $size = filesize($file_full);
            $line = count(file($file_full));
            $str = $file . ' : ' . $size . ' : ' . $line . "\n";
            $data .= $str;
        }

        $this->write_file($name, $data);

        return $data;
    }

    /**
     * @param $name
     * @return string
     */
    public function check_list($name)
    {
        $msg = '';
        $dir = $this->build_dir($name);
        $data = $this->read_file($name);

        $lines = $this->str_to_array($data, "\n");
        foreach ($lines as $line) {
            list($file, $size_comp, $line_comp) = $this->str_to_array($line, ':');
            $file_full = $dir . trim($file);
            if (!file_exists($file_full)) {
                $msg .= 'not exist : ' . $file_full . "<br>\n";
                continue;
            }
            //      $md5  = md5_file( $file_full ) ;
            $size = filesize($file_full);
            $line = count(file($file_full));
            //      if ( $md5 != trim($md5) ) ) {
            //          $msg .= 'unmatch md5 : '.$file_full."<br>\n";
            //      }
            if ($size == trim($size_comp)) {
                continue;
            }
            if ($line == trim($line_comp)) {
                continue;
            }
            $msg .= 'unmatch : ' . $file_full . "<br>\n";
        }

        return $msg;
    }

    /**
     * @param $name
     * @return string
     */
    public function build_dir($name)
    {
        switch ($name) {
            case 'trust':
                $dir = $this->_TRUST_DIR;
                break;
            case 'root':
                $dir = $this->_MODULE_DIR;
                break;
        }

        return $dir;
    }

    /**
     * @param        $name
     * @param        $data
     * @param string $mode
     * @param bool   $flag_chmod
     * @return bool|int
     */
    public function write_file($name, $data, $mode = 'w', $flag_chmod = true)
    {
        $file = XOOPS_TRUST_PATH . '/tmp/' . $this->build_filename($name);
        $fp = fopen($file, $mode);
        if (!$fp) {
            return false;
        }

        $byte = fwrite($fp, $data);
        fclose($fp);

        // the user can delete this file which apache made.
        if (($byte > 0) && $flag_chmod) {
            $this->chmod_file($file, $this->_CHMOD_MODE);
        }

        return $byte;
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

    /**
     * @param $name
     * @return bool|string
     */
    public function read_file($name)
    {
        $file = $this->_TRUST_DIR . '/include/' . $this->build_filename($name);

        return file_get_contents($file);
    }

    /**
     * @param $name
     * @return string
     */
    public function build_filename($name)
    {
        $file = $this->_TRUST_DIRNAME . '_check_' . $name . '.txt';

        return $file;
    }

    /**
     * @param $str
     * @param $pattern
     * @return array
     */
    public function str_to_array($str, $pattern)
    {
        $arr1 = explode($pattern, $str);
        $arr2 = [];
        foreach ($arr1 as $v) {
            $v = trim($v);
            if ('' == $v) {
                continue;
            }
            $arr2[] = $v;
        }

        return $arr2;
    }

    // --- class end ---
}
