<?php
// $Id: readfile.php,v 1.3 2011/05/10 02:56:39 ohwada Exp $

//=========================================================
// webphoto module
// 2010-09-17 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-05-01 K.OHWADA
// $is_rfc2231
//---------------------------------------------------------

//---------------------------------------------------------
// http://jp.php.net/manual/ja/function.readfile.php
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_lib_readfile
//=========================================================

/**
 * Class webphoto_lib_readfile
 */
class webphoto_lib_readfile
{
    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        // dummy
    }

    /**
     * @return \webphoto_lib_readfile
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
    // main
    //---------------------------------------------------------

    /**
     * @param $file
     * @param $mime
     */
    public function readfile_view($file, $mime)
    {
        $this->zlib_off();
        $this->http_output_pass();
        $this->header_view($file, $mime);
        ob_clean();
        flush();
        readfile($file);
    }

    /**
     * @param      $file
     * @param      $mime
     * @param      $name
     * @param bool $is_rfc2231
     */
    public function readfile_down($file, $mime, $name, $is_rfc2231 = false)
    {
        $this->zlib_off();
        $this->http_output_pass();
        $this->header_down($file, $mime, $name, $is_rfc2231);
        ob_clean();
        flush();
        readfile($file);
    }

    /**
     * @param $file
     */
    public function readfile_xml($file)
    {
        $this->zlib_off();
        $this->http_output_pass();
        $this->header_xml();
        ob_clean();
        flush();
        readfile($file);
    }

    //---------------------------------------------------------
    // function
    //---------------------------------------------------------

    /**
     * @param $file
     * @param $mime
     */
    public function header_view($file, $mime)
    {
        $size = filesize($file);
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: no-store, no-cache, max-age=1, s-maxage=1, must-revalidate, post-check=0, pre-check=0');
        header('Content-Type: ' . $mime);
        header('Content-Length: ' . $size);
    }

    /**
     * @param      $file
     * @param      $mime
     * @param      $name
     * @param bool $is_rfc2231
     */
    public function header_down($file, $mime, $name, $is_rfc2231 = false)
    {
        if ($is_rfc2231) {
            $dis = 'Content-Disposition: attachment; filename*=';
        } else {
            $dis = 'Content-Disposition: attachment; filename=';
        }

        $size = filesize($file);
        header('Pragma: public');
        header('Cache-Control: must-revaitem_idate, post-check=0, pre-check=0');
        header('Content-Description: File Transfer');
        header('Content-Type: ' . $mime);
        header('Content-Length: ' . $size);
        header($dis . $name);
    }

    /**
     * @param $file
     * @param $mime
     * @param $name
     */
    public function header_down_rfc2131($file, $mime, $name)
    {
        $size = filesize($file);
        header('Pragma: public');
        header('Cache-Control: must-revaitem_idate, post-check=0, pre-check=0');
        header('Content-Description: File Transfer');
        header('Content-Type: ' . $mime);
        header('Content-Length: ' . $size);
        header('Content-Disposition: attachment; filename*=' . $name);
    }

    public function header_xml()
    {
        header('Content-Type:text/xml; charset=utf-8');
    }

    public function zlib_off()
    {
        if (ini_get('zlib.output_compression')) {
            ini_set('zlib.output_compression', 'Off');
        }
    }

    //---------------------------------------------------------
    // multibyte
    //---------------------------------------------------------

    /**
     * @return bool|string
     */
    public function http_output_pass()
    {
        return $this->http_output('pass');
    }

    /**
     * @param null $encoding
     * @return bool|string
     */
    public function http_output($encoding = null)
    {
        if (function_exists('mb_http_output')) {
            if ($encoding) {
                return mb_http_output($encoding);
            }

            return mb_http_output();
        }

        return false;
    }

    // --- class end ---
}
