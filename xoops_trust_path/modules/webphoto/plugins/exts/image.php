<?php
// $Id: image.php,v 1.1 2010/10/06 02:23:55 ohwada Exp $

//=========================================================
// webphoto module
// 2010-10-01 K.OHWADA
//=========================================================

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_ext_image
//=========================================================

/**
 * Class webphoto_ext_image
 */
class webphoto_ext_image extends webphoto_ext_base
{
    public $_image_create_class;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_ext_image constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_image_create_class = webphoto_image_create::getInstance($dirname);
        $this->set_debug_by_name('IMAGE');
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
        return $this->match_ext_kind($ext, _C_WEBPHOTO_MIME_KIND_IMAGE);
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

        return $this->_image_create_class->cmd_rotate($src_file, $jpeg_file, $rotate);
    }

    // --- class end ---
}
