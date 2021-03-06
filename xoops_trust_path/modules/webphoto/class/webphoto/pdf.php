<?php
// $Id: pdf.php,v 1.9 2010/10/06 02:22:46 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-10-01 K.OHWADA
// create_image() -> create_jpeg()
// 2010-03-24 K.OHWADA
// create_jpeg_by_pdftops()
// 2009-11-11 K.OHWADA
// webphoto_lib_error -> webphoto_cmd_base
// 2009-04-21 K.OHWADA
// chmod_file()
// 2009-01-25 K.OHWADA
// icon_name in create_image()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_pdf
// wrapper for webphoto_lib_xpdf
//=========================================================

/**
 * Class webphoto_pdf
 */
class webphoto_pdf extends webphoto_cmd_base
{
    public $_multibyte_class;
    public $_xpdf_class;
    public $_imagemagick_class;

    public $_cfg_use_xpdf;
    public $_cfg_xpdfpath;
    public $_ini_cmd_pdf_jpeg;

    public $_cached = [];

    public $_PDF_EXT = 'pdf';
    public $_PS_EXT = 'ps';

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_pdf constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_xpdf_class = webphoto_lib_xpdf::getInstance();
        $this->_imagemagick_class = webphoto_lib_imagemagick::getInstance();
        $this->_multibyte_class = webphoto_multibyte::getInstance();

        $this->_cfg_use_xpdf = $this->get_config_by_name('use_xpdf');
        $this->_cfg_xpdfpath = $this->get_config_dir_by_name('xpdfpath');
        $this->_ini_cmd_pdf_jpeg = $this->get_ini('cmd_pdf_jpeg');

        $this->_xpdf_class->set_cmd_path($this->_cfg_xpdfpath);

        $this->set_debug_by_ini_name($this->_xpdf_class);
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_lib_error|\webphoto_pdf
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
    // create jpeg
    //---------------------------------------------------------

    /**
     * @param $pdf_file
     * @param $jpeg_file
     * @return int
     */
    public function create_jpeg($pdf_file, $jpeg_file)
    {
        if (!$this->_cfg_use_xpdf) {
            return 0;
        }

        if (!file_exists($pdf_file)) {
            return 0;
        }

        if ($this->pdftoppm_exists()) {
            $this->create_jpeg_by_pdftoppm($pdf_file, $jpeg_file);
        } else {
            $this->create_jpeg_by_pdftops($pdf_file, $jpeg_file);
        }

        if (file_exists($jpeg_file)) {
            return 1;
        }

        return -1;
    }

    /**
     * @return bool
     */
    public function pdftoppm_exists()
    {
        return $this->_xpdf_class->pdftoppm_exists();
    }

    /**
     * @param $pdf_file
     * @param $jpeg_file
     * @return bool
     */
    public function create_jpeg_by_pdftoppm($pdf_file, $jpeg_file)
    {
        $root = $this->_TMP_DIR . '/' . uniqid('ppm_', true);
        $ppm_file = $this->_xpdf_class->pdf_to_ppm($pdf_file, $root);

        if (!is_file($ppm_file)) {
            $this->set_error($this->_xpdf_class->get_msg_array());

            return false;
        }

        $this->convert_to_jpeg($ppm_file, $jpeg_file);

        return true;
    }

    /**
     * @param $pdf_file
     * @param $jpeg_file
     * @return bool
     */
    public function create_jpeg_by_pdftops($pdf_file, $jpeg_file)
    {
        $ps_file = $this->build_file_by_prefix_ext(uniqid('ps_', true), $this->_PS_EXT);
        $this->_xpdf_class->pdf_to_ps($pdf_file, $ps_file);

        if (!is_file($ps_file)) {
            $this->set_error($this->_xpdf_class->get_msg_array());

            return false;
        }

        $this->convert_to_jpeg($ps_file, $jpeg_file);

        return true;
    }

    /**
     * @param $src_file
     * @param $jpeg_file
     */
    public function convert_to_jpeg($src_file, $jpeg_file)
    {
        $this->_imagemagick_class->convert($src_file, $jpeg_file);

        // remove temp file
        unlink($src_file);

        if ($this->_flag_chmod) {
            $this->chmod_file($jpeg_file);
        }
    }

    //---------------------------------------------------------
    // text content
    //---------------------------------------------------------

    /**
     * @param $pdf_file
     * @return array|int
     */
    public function get_text_content($pdf_file)
    {
        $this->_content = null;

        if (empty($pdf_file)) {
            return 0;  // no action
        }
        if (!is_file($pdf_file)) {
            return 0;  // no action
        }
        if (!$this->_cfg_use_xpdf) {
            return 0;  // no action
        }

        $txt_file = $this->_TMP_DIR . '/' . uniqid('tmp_', true) . '.' . $this->_TEXT_EXT;
        $ret = $this->pdf_to_text($pdf_file, $txt_file);
        if (!$ret) {
            $arr = [
                'flag' => false,
                'errors' => $this->get_errors(),
            ];

            return $arr;
        }

        $text = file_get_contents($txt_file);
        $text = $this->_multibyte_class->convert_from_utf8($text);
        $text = $this->_multibyte_class->build_plane_text($text);
        $this->_content = $text;

        unlink($txt_file);

        $arr = [
            'flag' => true,
            'content' => $text,
        ];

        return $arr;
    }

    /**
     * @param $pdf_file
     * @param $txt_file
     * @return bool
     */
    public function pdf_to_text($pdf_file, $txt_file)
    {
        if (!$this->_cfg_use_xpdf) {
            return false;
        }

        $ret = $this->_xpdf_class->pdf_to_text($pdf_file, $txt_file);
        if ($ret && is_file($txt_file)) {
            if ($this->_flag_chmod) {
                chmod($txt_file, 0777);
            }

            return true;
        }

        $this->set_error($this->_xpdf_class->get_msg_array());

        return false;
    }

    // --- class end ---
}
