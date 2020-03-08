<?php
// $Id: image_tmp.php,v 1.2 2010/09/19 06:43:11 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-09-17 K.OHWADA
// webphoto_lib_readfile
// 2008-11-16 K.OHWADA
// image.php -> image_tmp.php
// 2008-11-08 K.OHWADA
// tmpdir -> workdir
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_main_image
//=========================================================

/**
 * Class webphoto_main_image_tmp
 */
class webphoto_main_image_tmp
{
    public $_config_class;
    public $_post_class;
    public $_readfile_class;

    public $_TMP_DIR;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_main_image_tmp constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        $this->_config_class = webphoto_config::getInstance($dirname);
        $this->_post_class = webphoto_lib_post::getInstance();
        $this->_readfile_class = webphoto_lib_readfile::getInstance();

        $work_dir = $this->_config_class->get_by_name('workdir');
        $this->_TMP_DIR = $work_dir . '/tmp';
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_main_image_tmp
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
        $name = $this->_post_class->get_get_text('name');
        $file = $this->_TMP_DIR . '/' . $name;

        if (empty($name) || !is_file($file)) {
            exit();
        }

        $image_size = getimagesize($file);
        if (!is_array($image_size)) {
            exit();
        }
        $mime = $image_size['mime'];

        $this->_readfile_class->readfile_view($file, $mime);

        exit();
    }

    // --- class end ---
}
