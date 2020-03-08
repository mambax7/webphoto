<?php
// $Id: video_images_create.php,v 1.2 2010/10/06 02:22:46 ohwada Exp $

//=========================================================
// webphoto module
// 2010-10-01 K.OHWADA
//=========================================================

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_edit_video_images_create
//=========================================================

/**
 * Class webphoto_edit_video_images_create
 */
class webphoto_edit_video_images_create extends webphoto_edit_base_create
{
    public $_ext_class;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_edit_video_images_create constructor.
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
     * @return \webphoto_edit_video_images_create|\webphoto_lib_error
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
    // create
    //---------------------------------------------------------

    /**
     * @param $param
     * @return int
     */
    public function create($param)
    {
        $this->clear_flags();

        $ret = $this->_ext_class->execute('video_images', $param);
        if (1 == $ret) {
            $this->set_flag_created();

            return 1;
        } elseif (-1 == $ret) {
            $this->set_flag_failed();

            return -1;
        }

        return 0;
    }

    // --- class end ---
}
