<?php
// $Id: gicon_create.php,v 1.2 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_edit_gicon_create
//=========================================================

/**
 * Class webphoto_edit_gicon_create
 */
class webphoto_edit_gicon_create extends webphoto_edit_base_create
{
    public $_image_create_class;

    public $_cfg_gicon_width;
    public $_cfg_gicon_height;

    public $_SUB_DIR_GICONS = 'gicons';
    public $_SUB_DIR_GSHADOWS = 'gshadows';
    public $_INFO_Y_DEFAULT = 3;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_edit_gicon_create constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_image_create_class = webphoto_image_create::getInstance($dirname);

        $this->_cfg_gicon_width = $this->get_config_by_name('gicon_width');
        $this->_cfg_gicon_height = $this->get_config_by_name('gicon_height');
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_edit_gicon_create|\webphoto_lib_error
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
    // create main image
    //---------------------------------------------------------

    /**
     * @param $row
     * @param $tmp_name
     * @return mixed
     */
    public function create_main_row($row, $tmp_name)
    {
        if (empty($tmp_name)) {
            return $row;
        }

        $gicon_id = $row['gicon_id'];
        $image_info = $this->resize_image($gicon_id, $tmp_name, $this->_SUB_DIR_GICONS);

        if (!is_array($image_info) || !$image_info['is_image']) {
            return $row;
        }

        $image_width = $image_info['width'];
        $image_height = $image_info['height'];

        $row['gicon_image_path'] = $image_info['path'];
        $row['gicon_image_name'] = $image_info['name'];
        $row['gicon_image_ext'] = $image_info['ext'];
        $row['gicon_image_width'] = $image_width;
        $row['gicon_image_height'] = $image_height;
        $row['gicon_anchor_x'] = $image_width / 2;
        $row['gicon_anchor_y'] = $image_height;
        $row['gicon_info_x'] = $image_width / 2;
        $row['gicon_info_y'] = $this->_INFO_Y_DEFAULT;

        return $row;
    }

    //---------------------------------------------------------
    // create shadow image
    //---------------------------------------------------------

    /**
     * @param $row
     * @param $tmp_name
     * @return mixed
     */
    public function create_shadow_row($row, $tmp_name)
    {
        if (empty($tmp_name)) {
            return $row;
        }

        $gicon_id = $row['gicon_id'];
        $image_info = $this->resize_image($gicon_id, $tmp_name, $this->_SUB_DIR_GSHADOWS);

        if (!is_array($image_info) || !$image_info['is_image']) {
            return $row;
        }

        $row['gicon_shadow_path'] = $image_info['path'];
        $row['gicon_shadow_name'] = $image_info['name'];
        $row['gicon_shadow_ext'] = $image_info['ext'];
        $row['gicon_shadow_width'] = $image_info['width'];
        $row['gicon_shadow_height'] = $image_info['height'];

        return $row;
    }

    //---------------------------------------------------------
    // common
    //---------------------------------------------------------

    /**
     * @param $gicon_id
     * @param $tmp_name
     * @param $sub_dir
     * @return array|null
     */
    public function resize_image($gicon_id, $tmp_name, $sub_dir)
    {
        $width = 0;
        $height = 0;
        $is_image = false;

        $ext = $this->parse_ext($tmp_name);
        $tmp_file = $this->_TMP_DIR . '/' . $tmp_name;

        $name_param = $this->build_random_name_param($gicon_id, $ext, $sub_dir);
        $name = $name_param['name'];
        $path = $name_param['path'];
        $file = $name_param['file'];
        $url = $name_param['url'];

        $ret = $this->_image_create_class->cmd_resize($tmp_file, $file, $this->_cfg_gicon_width, $this->_cfg_gicon_width);

        if ((_C_WEBPHOTO_IMAGE_READFAULT == $ret)
            || (_C_WEBPHOTO_IMAGE_SKIPPED == $ret)) {
            return null;
        }

        if ($this->is_image_ext($ext)) {
            $size = getimagesize($file);
            if (is_array($size)) {
                $width = $size[0];
                $height = $size[1];
                $is_image = true;
            }
        }

        $arr = [
            'url' => $url,
            'path' => $path,
            'name' => $name,
            'ext' => $ext,
            'width' => $width,
            'height' => $height,
            'is_image' => $is_image,
        ];

        return $arr;
    }

    // --- class end ---
}
