<?php
// $Id: dir.php,v 1.1 2009/04/19 11:41:45 ohwada Exp $

//=========================================================
// webphoto module
// 2009-04-19 K.OHWADA
//=========================================================

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_lib_dir
//=========================================================

/**
 * Class webphoto_lib_dir
 */
class webphoto_lib_dir
{
    public $_MKDIR_MODE = 0777;

    public $_EXCEPT_FILES = ['.', '..', 'CVS', 'Thumbs.db'];

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        // dummy
    }

    /**
     * @return \webphoto_lib_dir
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
    // get files
    //---------------------------------------------------------

    /**
     * @param $dir
     * @return array
     */
    public function get_files_in_deep_dir($dir)
    {
        return $this->array_fullpath_recursive('', $this->array_dirlist_recursive($dir));
    }

    /**
     * @param      $path
     * @param null $ext
     * @param bool $flag_dir
     * @param bool $flag_sort
     * @param bool $id_as_key
     * @return array|bool
     */
    public function get_files_in_dir($path, $ext = null, $flag_dir = false, $flag_sort = false, $id_as_key = false)
    {
        $arr = [];

        $lists = $this->get_lists_in_dir($path);
        if (!is_array($lists)) {
            return false;
        }

        $pattern = "/\." . preg_quote($ext) . '$/';

        foreach ($lists as $list) {
            $path_list = $path . '/' . $list;

            // check is file
            if (is_dir($path_list) || !is_file($path_list)) {
                continue;
            }

            // check ext
            if ($ext && !preg_match($pattern, $list)) {
                continue;
            }

            $list_out = $list;
            if ($flag_dir) {
                $list_out = $path_list;
            }
            if ($id_as_key) {
                $arr[$list] = $list_out;
            } else {
                $arr[] = $list_out;
            }
        }

        if ($flag_sort) {
            asort($arr);
            reset($arr);
        }

        return $arr;
    }

    /**
     * @param      $path
     * @param bool $flag_dir
     * @param bool $flag_sort
     * @param bool $id_as_key
     * @return array|bool
     */
    public function get_dirs_in_dir($path, $flag_dir = false, $flag_sort = false, $id_as_key = false)
    {
        $arr = [];

        $lists = $this->get_lists_in_dir($path);
        if (!is_array($lists)) {
            return false;
        }

        foreach ($lists as $list) {
            $path_list = $path . '/' . $list;

            // check is dir
            if (!is_dir($path_list)) {
                continue;
            }

            // myself
            if ('.' == $list) {
                continue;
            }

            // parent
            if ('..' == $list) {
                continue;
            }

            $list_out = $list;
            if ($flag_dir) {
                $list_out = $path_list;
            }
            if ($id_as_key) {
                $arr[$list] = $list_out;
            } else {
                $arr[] = $list_out;
            }
        }

        if ($flag_sort) {
            asort($arr);
            reset($arr);
        }

        return $arr;
    }

    /**
     * @param $path
     * @return array|bool
     */
    public function get_lists_in_dir($path)
    {
        $arr = [];

        $path = $this->strip_slash_from_tail($path);

        // check is dir
        if (!is_dir($path)) {
            return false;
        }

        // open
        $dh = opendir($path);
        if (!$dh) {
            return false;
        }

        // read
        while (false !== ($list = readdir($dh))) {
            $arr[] = $list;
        }

        // close
        closedir($dh);

        return $arr;
    }

    //---------------------------------------------------------
    // make dir
    //---------------------------------------------------------

    /**
     * @param      $dir
     * @param bool $check_writable
     * @return string
     */
    public function make_dir($dir, $check_writable = true)
    {
        $not_dir = true;
        if (is_dir($dir)) {
            $not_dir = false;
            if ($check_writable && is_writable($dir)) {
                return '';
            } elseif (!$check_writable) {
                return '';
            }
        }

        if (ini_get('safe_mode')) {
            return $this->highlight('At first create & chmod 777 "' . $dir . '" by ftp or shell.') . "<br>\n";
        }

        if ($not_dir) {
            $ret = mkdir($dir, $this->_MKDIR_MODE);
            if (!$ret) {
                return $this->highlight('can not create directory : <b>' . $dir . '</b>') . "<br>\n";
            }
        }

        $ret = chmod($dir, $this->_MKDIR_MODE);
        if (!$ret) {
            return $this->highlight('can not change mode directory : <b>' . $dir . '</b> ', $this->_MKDIR_MODE) . "<br>\n";
        }

        $msg = 'create directory: <b>' . $dir . '</b>' . "<br>\n";

        return $msg;
    }

    /**
     * @param $dir
     * @return bool
     */
    public function check_dir($dir)
    {
        if ($dir && is_dir($dir) && is_writable($dir) && is_readable($dir)) {
            return true;
        }

        return false;
    }

    //---------------------------------------------------------
    // recursive function
    //---------------------------------------------------------

    /**
     * @param $dir
     * @param $dirlist
     * @return array
     */
    public function array_fullpath_recursive($dir, $dirlist)
    {
        $fullpath = [];
        foreach ($dirlist as $id => $filename) {
            if (is_array($filename)) {
                $fullpath = array_merge($fullpath, $this->array_fullpath_recursive($dir . '/' . $id, $filename));
            } else {
                $fullpath[] = $dir . '/' . $filename;
            }
        }

        return $fullpath;
    }

    /**
     * @param $dir
     * @return array
     */
    public function array_dirlist_recursive($dir)
    {
        $arr = [];
        if (!is_dir($dir)) {
            return $arr;
        }

        $files = $this->get_lists_in_dir($dir);
        foreach ($files as $file) {
            $new_file = $dir . '/' . $file;
            if (in_array($file, $this->_EXCEPT_FILES)) {
                continue;
            }
            if (is_link($new_file)) {
                continue;
            }
            if (is_file($new_file)) {
                $arr[] = $file;
            } elseif (is_dir($new_file)) {
                $arr[] = $file;
                $arr[$file] = $this->array_dirlist_recursive($new_file);
            }
        }

        return $arr;
    }

    //---------------------------------------------------------
    // utility
    //---------------------------------------------------------

    /**
     * @param $str
     * @return bool|string
     */
    public function strip_slash_from_tail($str)
    {
        if ('/' == mb_substr($str, -1, 1)) {
            $str = mb_substr($str, 0, -1);
        }

        return $str;
    }

    /**
     * @param $str
     * @return string
     */
    public function highlight($str)
    {
        $val = '<span style="color:#ff0000;">' . $str . '</span>';

        return $val;
    }

    // --- class end ---
}
