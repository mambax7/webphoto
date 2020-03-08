<?php
// $Id: pdf_create.php,v 1.6 2010/11/16 23:43:38 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-25 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-11-11 K.OHWADA
// changed create_param()
// 2010-10-01 K.OHWADA
// create_pdf() -> execute()
// 2009-11-11 K.OHWADA
// $trust_dirname
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_edit_pdf_create
//=========================================================

/**
 * Class webphoto_edit_pdf_create
 */
class webphoto_edit_pdf_create extends webphoto_edit_base_create
{
    public $_ext_class;

    public $_param_ext = 'pdf';
    public $_param_dir = 'pdfs';
    public $_param_mime = 'application/pdf';
    public $_param_medium = '';
    public $_param_kind = _C_WEBPHOTO_FILE_KIND_PDF;
    public $_msg_created = 'create pdf';
    public $_msg_failed = 'fail to create pdf';

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_edit_pdf_create constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_ext_class = webphoto_ext::getInstance($dirname, $trust_dirname);
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_edit_pdf_create|\webphoto_lib_error
     */
    public static function getInstance($dirname = null, $trust_dirname = null)
    {
        static $instance;
        if (null === $instance) {
            $instance = new self($dirname, $trust_dirname);
        }

        return $instance;
    }

    //---------------------------------------------------------
    // create pdf
    //---------------------------------------------------------

    /**
     * @param $param
     * @return array|null
     */
    public function create_param($param)
    {
        $this->clear_msg_array();

        $item_id = $param['item_id'];
        $src_file = $param['src_file'];
        $src_ext = $param['src_ext'];

        // return input file is pdf
        if ($this->is_pdf_ext($src_ext)) {
            return null;
        }

        $pdf_param = $this->create_pdf($item_id, $src_file, $src_ext);
        if (!is_array($pdf_param)) {
            return null;
        }

        return $pdf_param;
    }

    /**
     * @param $item_id
     * @param $src_file
     * @param $src_ext
     * @return array|null
     */
    public function create_pdf($item_id, $src_file, $src_ext)
    {
        $name_param = $this->build_name_param($item_id);
        $file = $name_param['file'];

        $param = [
            'src_file' => $src_file,
            'src_ext' => $src_ext,
            'pdf_file' => $file,
        ];

        $ret = $this->_ext_class->execute('pdf', $param);

        return $this->build_result($ret, $name_param);
    }

    // --- class end ---
}
