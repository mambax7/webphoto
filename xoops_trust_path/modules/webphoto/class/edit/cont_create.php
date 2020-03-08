<?php
// $Id: cont_create.php,v 1.5 2010/10/06 02:22:46 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-10-01 K.OHWADA
// remove create_image_param()
// 2009-11-11 K.OHWADA
// $trust_dirname
// 2009-03-15 K.OHWADA
// rotate_image()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_edit_cont_create
//=========================================================

/**
 * Class webphoto_edit_cont_create
 */
class webphoto_edit_cont_create extends webphoto_edit_base_create
{
    public $_cont_param = null;

    public $_SUB_DIR_PHOTOS = 'photos';

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_edit_cont_create constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_edit_cont_create|\webphoto_lib_error
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
    // create image file
    //---------------------------------------------------------

    /**
     * @param $p
     * @return int
     */
    public function create_param($p)
    {
        $this->clear_msg_array();

        $item_id = $p['item_id'];
        $src_file = $p['src_file'];
        $src_ext = $p['src_ext'];
        $mime = isset($p['src_mime']) ? $p['src_mime'] : null;
        $duration = isset($p['item_duration']) ? (int)$p['item_duration'] : 0;
        $width = isset($p['item_width']) ? (int)$p['item_width'] : 0;
        $height = isset($p['item_height']) ? (int)$p['item_height'] : 0;

        $name_param = $this->build_random_name_param($item_id, $src_ext, $this->_SUB_DIR_PHOTOS);
        $name = $name_param['name'];
        $path = $name_param['path'];
        $file = $name_param['file'];
        $url = $name_param['url'];

        // set width if image
        if ($this->is_image_ext($src_ext)) {
            $image_size = getimagesize($src_file);
            if (is_array($image_size)) {
                $width = $image_size[0];
                $height = $image_size[1];
                if (empty($mime)) {
                    $mime = $image_size['mime'];
                }
            }
        }

        // set mime if not
        if (empty($mime)) {
            $mime = $this->ext_to_mime($src_ext);
        }

        $medium = $this->mime_to_medium($mime);

        copy($src_file, $file);

        $arr = [
            'url' => XOOPS_URL . $path,
            'path' => $path,
            'name' => $name,
            'ext' => $src_ext,
            'mime' => $mime,
            'medium' => $medium,
            'width' => $width,
            'height' => $height,
            'duration' => $duration,
            'size' => filesize($src_file),
            'kind' => _C_WEBPHOTO_FILE_KIND_CONT,
        ];

        $this->_cont_param = $arr;

        return 0;
    }

    //---------------------------------------------------------
    // get param
    //---------------------------------------------------------

    public function get_param()
    {
        return $this->_cont_param;
    }

    // --- class end ---
}
