<?php
// $Id: jpg.php,v 1.1 2010/10/06 02:23:55 ohwada Exp $

//=========================================================
// webphoto module
// 2010-10-01 K.OHWADA
//=========================================================

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_ext_jpg
//=========================================================

/**
 * Class webphoto_ext_jpg
 */
class webphoto_ext_jpg extends webphoto_ext_base
{
    public $_imagemagick_class;
    public $_image_create_class;
    public $_exif_class;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_ext_jpg constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_imagemagick_class = webphoto_imagemagick::getInstance($dirname, $trust_dirname);
        $this->_image_create_class = webphoto_image_create::getInstance($dirname);
        $this->_exif_class = webphoto_exif::getInstance();

        $this->set_debug_by_name('JPG');
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
        return $this->match_ext_kind($ext, _C_WEBPHOTO_MIME_KIND_IMAGE_JPEG);
    }

    //---------------------------------------------------------
    // create jpeg
    //---------------------------------------------------------

    /**
     * @param $param
     * @return int|null
     */
    public function create_jpeg($param)
    {
        $src_file = $param['src_file'];
        $jpeg_file = $param['jpeg_file'];
        $rotate = $param['rotate'];
        $is_cmyk = $param['is_cmyk'];

        // cmyk -> rgb
        if ($is_cmyk) {
            return $this->_imagemagick_class->create_jpeg_from_cmyk($src_file, $jpeg_file, $rotate);
        // rotate
        } elseif ($rotate) {
            return $this->_image_create_class->cmd_rotate($src_file, $jpeg_file, $rotate);
        }

        // no action
        return null;
    }

    //---------------------------------------------------------
    // exif
    //---------------------------------------------------------

    /**
     * @param $param
     * @return array|bool|null
     */
    public function get_exif($param)
    {
        $src_file = $param['src_file'];

        return $this->_exif_class->get_exif($src_file);
    }

    // --- class end ---
}
