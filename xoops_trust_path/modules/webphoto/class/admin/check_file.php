<?php
// $Id: check_file.php,v 1.2 2010/01/26 08:25:45 ohwada Exp $

//=========================================================
// webphoto module
// 2009-12-06 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-01-10 K.OHWADA
// green()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_admin_check_file
//=========================================================

/**
 * Class webphoto_admin_check_file
 */
class webphoto_admin_check_file extends webphoto_base_this
{
    public $_file_check_class;

    // color: green;
    public $_SPAN_STYLE_GREEN = 'color: #00ff00;';

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_admin_check_file constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_file_check_class = webphoto_lib_file_check::getInstance($dirname, $trust_dirname);
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_admin_check_file|\webphoto_lib_error
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
    // main
    //---------------------------------------------------------
    public function main()
    {
        xoops_cp_header();

        echo $this->build_admin_menu();
        echo '<h3>' . _AM_WEBPHOTO_FILE_CHECK . "</h3>\n";

        echo _AM_WEBPHOTO_FILE_CHECK_DSC . "<br><br>\n";
        $this->_print_file_check();

        xoops_cp_footer();
    }

    //---------------------------------------------------------
    // check file
    //---------------------------------------------------------
    public function _print_file_check()
    {
        $flag_error = false;

        $msg = $this->_file_check_class->check_list('trust');
        if ($msg) {
            $flag_error = true;
            echo $this->highlight($msg);
        }

        $msg = $this->_file_check_class->check_list('root');
        if ($msg) {
            $flag_error = true;
            echo $this->highlight($msg);
        }

        if (!$flag_error) {
            echo $this->green('OK');
        }
        echo "<br>\n";
    }

    /**
     * @param $msg
     * @return string
     */
    public function green($msg)
    {
        $str = '<span style="' . $this->_SPAN_STYLE_GREEN . '">';
        $str .= $msg;
        $str .= "</span>\n";

        return $str;
    }

    // --- class end ---
}
