<?php
// $Id: xls.php,v 1.3 2010/10/06 02:22:46 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-25 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-10-01 K.OHWADA
// create_image() -> create_jpeg()
// 2009-11-11 K.OHWADA
// $trust_dirname
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_ext_xls
//=========================================================

/**
 * Class webphoto_ext_xls
 */
class webphoto_ext_xls extends webphoto_ext_base
{
    public $_pdf_class;
    public $_jod_class;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_ext_xls constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_pdf_class = webphoto_pdf::getInstance($dirname, $trust_dirname);
        $this->_jod_class = webphoto_jodconverter::getInstance($dirname, $trust_dirname);

        $this->set_debug_by_name('XLS');
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
        return $this->match_ext_kind($ext, _C_WEBPHOTO_MIME_KIND_OFFICE_XLS);
    }

    //---------------------------------------------------------
    // create pdf
    //---------------------------------------------------------

    /**
     * @param $param
     * @return int|null
     */
    public function create_pdf($param)
    {
        $src_file = $param['src_file'];
        $pdf_file = $param['pdf_file'];

        return $this->_jod_class->create_pdf($src_file, $pdf_file);
    }

    //---------------------------------------------------------
    // create image
    //---------------------------------------------------------

    /**
     * @param $param
     * @return int|null
     */
    public function create_jpeg($param)
    {
        $pdf_file = $param['pdf_file'];
        $jpeg_file = $param['jpeg_file'];

        return $this->_pdf_class->create_jpeg($pdf_file, $jpeg_file);
    }

    //---------------------------------------------------------
    // text content
    //---------------------------------------------------------

    /**
     * @param $param
     * @return array|null
     */
    public function get_text_content($param)
    {
        $file_cont = isset($param['file_cont']) ? $param['file_cont'] : null;

        return $this->_jod_class->get_text_content_for_xls_ppt($file_cont);
    }

    // --- class end ---
}
