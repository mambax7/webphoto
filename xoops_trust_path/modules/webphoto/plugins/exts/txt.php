<?php
// $Id: txt.php,v 1.2 2009/11/29 07:34:23 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// $trust_dirname
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_ext_txt
//=========================================================

/**
 * Class webphoto_ext_txt
 */
class webphoto_ext_txt extends webphoto_ext_base
{
    public $_TXT_EXTS = ['txt'];

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_ext_txt constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);
    }

    //---------------------------------------------------------
    // check ext
    //---------------------------------------------------------

    /**
     * @param $ext
     * @return bool
     */
    public function is_ext($ext)
    {
        return $this->is_txt_ext($ext);
    }

    /**
     * @param $ext
     * @return bool
     */
    public function is_txt_ext($ext)
    {
        return $this->is_ext_in_array($ext, $this->_TXT_EXTS);
    }

    //---------------------------------------------------------
    // create image
    //---------------------------------------------------------

    //---------------------------------------------------------
    // text content
    //---------------------------------------------------------

    /**
     * @param $param
     * @return bool|null|string|string[]
     */
    public function get_text_content($param)
    {
        $file_cont = isset($param['file_cont']) ? $param['file_cont'] : null;

        if (!is_file($file_cont)) {
            return false;
        }

        $text = file_get_contents($file_cont);

        $encoding = $this->_multibyte_class->m_mb_detect_encoding($text);
        if ($encoding) {
            $text = $this->_multibyte_class->convert_encoding($text, _CHARSET, $encoding);
        }

        return $text;
    }

    // --- class end ---
}
