<?php
// $Id: download_filename.php,v 1.1 2011/05/10 02:59:15 ohwada Exp $

//=========================================================
// webphoto module
// 2011-05-01 K.OHWADA
//=========================================================

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_lib_download_filename
//=========================================================

/**
 * Class webphoto_lib_download_filename
 */
class webphoto_lib_download_filename
{
    public $_CHARSET_LOCAL = 'utf-8';
    public $_LANGCODE = 'en';
    public $_is_japanese = false;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        // dummy
    }

    /**
     * @return \webphoto_lib_download_filename
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
    public function set_charset_local($val)
    {
        $this->_CHARSET_LOCAL = strtolowr($val);
    }

    /**
     * @param $val
     */
    public function set_langcode($val)
    {
        $this->_LANGCODE = strtolowr($val);
    }

    /**
     * @param $val
     */
    public function set_is_japanese($val)
    {
        $this->_is_japanese = (bool)$val;
    }

    //---------------------------------------------------------
    // function
    //---------------------------------------------------------

    /**
     * @param $name
     * @param $name_alt
     * @param $browser
     * @return array
     */
    public function build_encode($name, $name_alt, $browser)
    {
        $is_rfc2231 = false;

        $name = $this->substitute_filename($name);
        $kind = $this->get_kind($name, $browser);

        switch ($kind) {
            // ASCII
            case 'ascii':
                $name = rawurlencode($name);
                break;
            // SJIS
            case 'msie_ja':
                $name = $this->convert_encoding($name, 'sjis-win', $this->_CHARSET_LOCAL);
                break;
            // RFC2231
            case 'firefox':
            case 'chrome':
            case 'opera':
                $name = $this->convert_to_utf8($name);
                $name = $this->build_filename_rfc2231($name, 'utf-8', $this->_LANGCODE);
                $is_rfc2231 = true;
                break;
            // UTF-8
            case 'safari_utf8':
                $name = $this->convert_to_utf8($name);
                break;
            default:
                $name = rawurlencode($name_alt);
                break;
        }

        return [$name, $is_rfc2231];
    }

    /**
     * @param $name
     * @param $browser
     * @return string
     */
    public function get_kind($name, $browser)
    {
        $ascii = $this->convert_encoding($name, 'us-ascii', $this->_CHARSET_LOCAL);
        if ($ascii == $name) {
            $browser = 'ascii';
        }

        if (('msie' == $browser) && $this->_is_japanese) {
            $browser = 'msie_ja';
        }

        if (('safari' == $browser) && ('utf-8' == $this->_CHARSET_LOCAL)) {
            $browser = 'safari_utf8';
        }

        return $browser;
    }

    /**
     * @param $name
     * @param $charset
     * @param $langcode
     * @return string
     */
    public function build_filename_rfc2231($name, $charset, $langcode)
    {
        $str = mb_strtolower($charset . "'" . $langcode . "'");
        $str .= rawurlencode($name);

        return $str;
    }

    /**
     * @param $str
     * @return mixed|string
     */
    public function substitute_filename($str)
    {
        $str = $this->convert_space_zen_to_han($str);
        $str = $this->substitute_filename_to_underbar($str);

        return $str;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function substitute_filename_to_underbar($name)
    {
        // substitute the characters that cannot be used as the file name to underbar.
        // \ / : * ? " < > | sapce
        $search = ['\\', '/', ':', '*', '?', '"', '<', '>', '|', ' '];
        $replace = ['_', '_', '_', '_', '_', '_', '_', '_', '_', '_'];

        $str = str_replace($search, $replace, $name);

        return $str;
    }

    //---------------------------------------------------------
    // multibyte
    //---------------------------------------------------------

    /**
     * @param $str
     * @return null|string|string[]
     */
    public function convert_to_utf8($str)
    {
        return $this->convert_encoding($str, 'utf-8', $this->_CHARSET_LOCAL);
    }

    /**
     * @param $str
     * @param $charset_to
     * @param $charset_from
     * @return null|string|string[]
     */
    public function convert_encoding($str, $charset_to, $charset_from)
    {
        // no action when same charset
        if ($charset_from == $charset_to) {
            return $str;
        }

        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($str, $charset, $charset_from);
        }
        if (function_exists('iconv')) {
            return iconv($charset_from, $charset . '//TRANSLIT', $str);
        }

        return $str;
    }

    /**
     * @param $str
     * @return string
     */
    public function convert_space_zen_to_han($str)
    {
        if (function_exists('mb_convert_kana')) {
            return mb_convert_kana($str, 's');
        }

        return $str;
    }

    // --- class end ---
}
