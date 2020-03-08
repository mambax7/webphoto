<?php
// $Id: jpeg_create.php,v 1.6 2010/11/16 23:43:38 ohwada Exp $

//=========================================================
// webphoto module
// 2009-10-25 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-11-11 K.OHWADA
// build_image_file_param();
// 2010-10-01 K.OHWADA
// create_jpeg() -> execute()
// 2009-11-11 K.OHWADA
// $trust_dirname
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_edit_jpeg_create
//=========================================================

/**
 * Class webphoto_edit_jpeg_create
 */
class webphoto_edit_jpeg_create extends webphoto_edit_base_create
{
    public $_ext_class;
    public $_image_create_class;

    public $_is_cmyk = false;
    public $_rotate = 0;

    public $_param_ext = 'jpg';
    public $_param_dir = 'jpegs';
    public $_param_mime = 'image/jpeg';
    public $_param_medium = 'image';
    public $_param_kind = _C_WEBPHOTO_FILE_KIND_JPEG;
    public $_msg_created = 'create jpeg';
    public $_msg_failed = 'fail to create jpeg';

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_edit_jpeg_create constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_ext_class = webphoto_ext::getInstance($dirname, $trust_dirname);

        $this->_image_create_class = webphoto_image_create::getInstance($dirname);
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_edit_jpeg_create|\webphoto_lib_error
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
     * @param $param
     * @return array|null
     */
    public function create_param($param)
    {
        $this->clear_msg_array();

        $item_id = $param['item_id'];
        $src_file = $param['src_file'];
        $src_ext = $param['src_ext'];
        $pdf_file = isset($param['pdf_file']) ? $param['pdf_file'] : null;
        $rotate = isset($param['rotate_angle']) ? $param['rotate_angle'] : 0;

        $this->_is_cmyk = false;
        $this->_rotate = 0;

        // set flag if image, rotate
        if ($this->is_image_ext($src_ext)) {
            if ($rotate) {
                $this->_rotate = $rotate;
            }
        }

        // set flag if jpeg, cmyk
        if ($this->is_jpeg_ext($src_ext)) {
            if ($this->is_image_cmyk($src_file)) {
                $this->_is_cmyk = true;
            }
        }

        $jpeg_param = $this->create_jpeg($item_id, $src_file, $src_ext, $pdf_file);
        if (!is_array($jpeg_param)) {
            return null;
        }

        return $jpeg_param;
    }

    /**
     * @param $item_id
     * @param $src_file
     * @param $src_ext
     * @param $pdf_file
     * @return array|null
     */
    public function create_jpeg($item_id, $src_file, $src_ext, $pdf_file)
    {
        $name_param = $this->build_name_param($item_id);
        $name = $name_param['name'];
        $path = $name_param['path'];
        $file = $name_param['file'];
        $url = $name_param['url'];

        $param = [
            'src_file' => $src_file,
            'src_ext' => $src_ext,
            'pdf_file' => $pdf_file,
            'jpeg_file' => $file,
            'is_cmyk' => $this->_is_cmyk,
            'rotate' => $this->_rotate,
        ];

        $ret = $this->_ext_class->execute('jpeg', $param);

        return $this->build_result($ret, $name_param);
    }

    /**
     * @return bool
     */
    public function is_cmyk()
    {
        return $this->_is_cmyk;
    }

    //---------------------------------------------------------
    // create image param (for gif, png)
    //---------------------------------------------------------

    /**
     * @param $param
     * @return array|null
     */
    public function create_image_param($param)
    {
        $item_id = $param['item_id'];
        $src_file = $param['src_file'];

        $name_param = $this->build_name_param($item_id);
        $jpeg_file = $name_param['file'];

        $this->_image_create_class->cmd_rotate($src_file, $jpeg_file, 0);

        return $this->build_copy_result($name_param);
    }

    // --- class end ---
}
