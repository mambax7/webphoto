<?php
// $Id: image.php,v 1.5 2011/05/10 02:56:39 ohwada Exp $

//=========================================================
// webphoto module
// 2008-11-16 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-05-01 K.OHWADA
// Notice [PHP]: Undefined index: file_full
// 2010-09-17 K.OHWADA
// webphoto_lib_readfile
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_main_image
//=========================================================

/**
 * Class webphoto_main_image
 */
class webphoto_main_image extends webphoto_file_read
{
    public $_readfile_class;
    public $_kind_class;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_main_image constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_readfile_class = webphoto_lib_readfile::getInstance();
        $this->_kind_class = webphoto_kind::getInstance();
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_file_read|\webphoto_item_public|\webphoto_lib_error|\webphoto_main_image
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
    // public
    //---------------------------------------------------------
    public function main()
    {
        $item_id = $this->_post_class->get_post_get_int('item_id');
        $file_kind = $this->_post_class->get_post_get_int('file_kind');

        $item_row = $this->get_item_row($item_id);
        if (!is_array($item_row)) {
            exit();
        }

        $file_row = $this->get_file_row($item_row, $file_kind);
        if (!is_array($file_row)) {
            exit();
        }

        //print_r($file_row);

        $ext = $file_row['file_ext'];
        $mime = $file_row['file_mime'];
        $size = $file_row['file_size'];

        // Notice [PHP]: Undefined index: file_full
        $file = $file_row['full_path'];

        if (!$this->_kind_class->is_image_ext($ext)) {
            exit();
        }

        $this->_readfile_class->readfile_view($file, $mime);

        exit();
    }

    // --- class end ---
}
