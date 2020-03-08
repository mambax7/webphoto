<?php
// $Id: check_mb.php,v 1.3 2009/08/08 08:40:06 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-08-08 K.OHWADA
// mb_conv() iconv_conv()
// 2008-12-07 K.OHWADA
// window.close()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_admin_check_mb
//=========================================================

/**
 * Class webphoto_admin_check_mb
 */
class webphoto_admin_check_mb extends webphoto_base_this
{
    public $_multibyte_class;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_admin_check_mb constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_multibyte_class = webphoto_lib_multibyte::getInstance();
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_admin_check_mb|\webphoto_lib_error
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
        restore_error_handler();
        error_reporting(E_ALL);

        $charset = $this->_post_class->get_get_text('charset', _CHARSET);

        $this->http_output('pass');
        header('Content-Type:text/html; charset=' . $charset);

        $title = 'Check Multibyte';

        $text = $this->build_html_head($title, $charset);
        $text .= $this->build_html_body_begin();
        $text .= 'charset : ' . $charset . "<br><br>\n";

        if ($this->mb_exists()) {
            $text .= "<b>mb_convert_encoding</b> <br>\n";
            $text .= $this->mb_conv(_AM_WEBPHOTO_MULTIBYTE_SUCCESS, $charset);
            $text .= "<br><br>\n";
        } else {
            $text .= "<b>mb_convert_encoding</b> not exist <br><br>\n";
        }

        if ($this->i_exists()) {
            $text .= "<b>iconv</b> <br>\n";
            $text .= $this->i_conv(_AM_WEBPHOTO_MULTIBYTE_SUCCESS, $charset);
            $text .= "<br><br>\n";
        } else {
            $text .= "<b>iconv</b> not exist <br><br>\n";
        }

        $text .= '<input class="formButton" value="CLOSE" type="button" onclick="javascript:window.close();" >';
        $text .= $this->build_html_body_end();

        echo $text;
    }

    //---------------------------------------------------------
    // multibyte
    //---------------------------------------------------------

    /**
     * @param $encoding
     * @return bool|string
     */
    public function http_output($encoding)
    {
        return $this->_multibyte_class->m_mb_http_output($encoding);
    }

    /**
     * @param $str
     * @param $charset
     * @return null|string|string[]
     */
    public function conv($str, $charset)
    {
        return $this->_multibyte_class->convert_encoding($str, $charset, _CHARSET);
    }

    /**
     * @return bool
     */
    public function mb_exists()
    {
        if (function_exists('mb_convert_encoding')) {
            return true;
        }

        return false;
    }

    /**
     * @param $str
     * @param $to
     * @return null|string|string[]
     */
    public function mb_conv($str, $to)
    {
        if (_CHARSET == $to) {
            return $str;
        }

        return mb_convert_encoding($str, $to, _CHARSET);
    }

    /**
     * @return bool
     */
    public function i_exists()
    {
        if (function_exists('iconv')) {
            return true;
        }

        return false;
    }

    /**
     * @param        $str
     * @param        $to
     * @param string $extra
     * @return string
     */
    public function i_conv($str, $to, $extra = '//IGNORE')
    {
        if (_CHARSET == $to) {
            return $str;
        }

        return iconv(_CHARSET, $to . $extra, $str);
    }

    // --- class end ---
}
