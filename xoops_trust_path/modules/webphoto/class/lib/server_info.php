<?php
// $Id: server_info.php,v 1.3 2009/08/09 05:47:09 ohwada Exp $

//=========================================================
// webphoto module
// 2008-11-16 K.OHWADA
//=========================================================

//---------------------------------------------------------
// 2009-08-08 K.OHWADA
// remove build_php_mbstring()
// 2009-01-10 K.OHWADA
// move "program version"
//---------------------------------------------------------

//=========================================================
// class webphoto_lib_server_info
//=========================================================

/**
 * Class webphoto_lib_server_info
 */
class webphoto_lib_server_info
{
    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        // dummy
    }

    /**
     * @return \webphoto_lib_server_info
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
    // server info
    //---------------------------------------------------------

    /**
     * @return string
     */
    public function build_server()
    {
        $str = 'OS: ' . php_uname() . "<br>\n";
        $str .= 'PHP: ' . PHP_VERSION . "<br>\n";
        $str .= 'MySQL: ' . $GLOBALS['xoopsDB']->getServerVersion() . "<br>\n";
        $str .= 'XOOPS: ' . XOOPS_VERSION . "<br>\n";

        return $str;
    }

    /**
     * @param $dsc
     * @return string
     */
    public function build_php_secure($dsc)
    {
        $str = $this->build_ini_on_off('register_globals') . $dsc . "<br>\n";
        $str .= $this->build_ini_on_off('allow_url_fopen') . $dsc . "<br>\n";

        return $str;
    }

    /**
     * @return string
     */
    public function build_php_etc()
    {
        $str = 'error_reporting: ' . error_reporting() . "<br>\n";
        $str .= $this->build_ini_int('display_errors') . "<br>\n";
        $str .= $this->build_ini_int('memory_limit') . "<br>\n";
        $str .= 'magic_quotes_gpc: ' . @get_magic_quotes_gpc() . "<br>\n";
        $str .= $this->build_ini_int('safe_mode') . "<br>\n";
        $str .= $this->build_ini_val('open_basedir') . "<br>\n";

        return $str;
    }

    /**
     * @return string
     */
    public function build_php_exif()
    {
        $str = 'exif extention: ' . $this->build_func_load('exif_read_data') . "<br>\n";

        return $str;
    }

    /**
     * @param null $dsc
     * @return string
     */
    public function build_php_upload($dsc = null)
    {
        $str = $this->build_ini_on_off('file_uploads') . $dsc . "<br>\n";
        $str .= $this->build_ini_val('upload_max_filesize') . "<br>\n";
        $str .= $this->build_ini_val('post_max_size') . "<br>\n";
        $str .= $this->build_php_upload_tmp_dir();

        return $str;
    }

    /**
     * @return string
     */
    public function build_php_upload_tmp_dir()
    {
        $upload_tmp_dir = ini_get('upload_tmp_dir');

        $str = 'upload_tmp_dir : ' . $upload_tmp_dir . "<br>\n";

        $tmp_dirs = explode(PATH_SEPARATOR, $upload_tmp_dir);
        foreach ($tmp_dirs as $dir) {
            if ('' != $dir && (!is_writable($dir) || !is_readable($dir))) {
                $msg = "Error: upload_tmp_dir ($dir) is not writable nor readable .";
                $str .= $this->font_red($msg) . "<br>\n";
            }
        }

        return $str;
    }

    /**
     * @param $key
     * @return string
     */
    public function build_ini_int($key)
    {
        $str = $key . ': ' . (int)ini_get($key);

        return $str;
    }

    /**
     * @param $key
     * @return string
     */
    public function build_ini_val($key)
    {
        $str = $key . ': ' . ini_get($key);

        return $str;
    }

    /**
     * @param $key
     * @return string
     */
    public function build_ini_on_off($key)
    {
        $str = $key . ': ' . $this->build_on_off(ini_get($key));

        return $str;
    }

    /**
     * @param $func
     * @return string
     */
    public function build_func_load($func)
    {
        if (function_exists($func)) {
            $str = 'loaded';
        } else {
            $str = $this->font_red('not loaded');
        }

        return $str;
    }

    //---------------------------------------------------------
    // utility
    //---------------------------------------------------------

    /**
     * @param      $val
     * @param bool $flag_red
     * @return string
     */
    public function build_on_off($val, $flag_red = false)
    {
        $str = '';
        if ($val) {
            $str = $this->font_green('on');
        } elseif ($flag_red) {
            $str = $this->font_red('off');
        } else {
            $str = $this->font_green('off');
        }

        return $str;
    }

    /**
     * @param $str
     * @return string
     */
    public function font_red($str)
    {
        $str = '<font color="#FF0000"><b>' . $str . '</b></font>';

        return $str;
    }

    /**
     * @param $str
     * @return string
     */
    public function font_green($str)
    {
        $str = '<font color="#00FF00"><b>' . $str . '</b></font>';

        return $str;
    }

    // --- class end ---
}
