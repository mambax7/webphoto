<?php
// $Id: embed.php,v 1.6 2010/06/16 22:24:47 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-06-06 K.OHWADA
// get_xml_params()
// 2009-11-22 K.OHWADA
// _WEBPHOTO_EMBED_ENTER
// 2009-11-21 K.OHWADA
// typo
// 2009-01-04 K.OHWADA
// webphoto_lib_plugin
// 2008-11-16 K.OHWADA
// $class->width()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_embed
//=========================================================

/**
 * Class webphoto_embed
 */
class webphoto_embed extends webphoto_lib_plugin
{
    public $_config_class;

    public $_param = null;

    public $_WIDTH_DEFAULT = _C_WEBPHOTO_EMBED_WIDTH_DEFAULT;
    public $_HEIGHT_DEFAULT = _C_WEBPHOTO_EMBED_HEIGHT_DEFAULT;

    public $_WORK_DIR;
    public $_TMP_DIR;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_embed constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);
        $this->set_dirname('embeds');
        $this->set_prefix('webphoto_embed_');

        $this->_config_class = webphoto_config::getInstance($dirname);
        $this->_WORK_DIR = $this->_config_class->get_by_name('workdir');
        $this->_TMP_DIR = $this->_WORK_DIR . '/tmp';
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_embed|\webphoto_lib_plugin
     */
    public static function getInstance($dirname = null, $trust_dirname = null)
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new self($dirname, $trust_dirname);
        }

        return $instance;
    }

    //---------------------------------------------------------
    // embed
    //---------------------------------------------------------

    /**
     * @param $val
     */
    public function set_param($val)
    {
        if (is_array($val)) {
            $this->_param = $val;
        }
    }

    /**
     * @param $type
     * @param $src
     * @return bool
     */
    public function get_xml_params($type, $src)
    {
        if (empty($type)) {
            return false;
        }

        if (empty($src)) {
            return false;
        }

        $class = $this->get_class_object($type);
        if (!is_object($class)) {
            return false;
        }

        $class->set_tmp_dir($this->_TMP_DIR);

        return $class->get_xml_params($src);
    }

    /**
     * @param $type
     * @param $src
     * @param $width
     * @param $height
     * @return array|bool
     */
    public function build_embed_link($type, $src, $width, $height)
    {
        if (empty($type)) {
            return false;
        }

        if (empty($src)) {
            return false;
        }

        $class = $this->get_class_object($type);
        if (!is_object($class)) {
            return false;
        }

        if (is_array($this->_param)) {
            $class->set_param($this->_param);
        }

        // plugin if empty
        if (empty($width)) {
            $width = $class->width();
        }
        if (empty($height)) {
            $height = $class->height();
        }

        // default if empty
        if (empty($width)) {
            $width = $this->_WIDTH_DEFAULT;
        }
        if (empty($height)) {
            $height = $this->_HEIGHT_DEFAULT;
        }

        $embed = $class->embed($src, $width, $height);
        $link = $class->link($src);

        return [$embed, $link];
    }

    /**
     * @param $type
     * @param $src
     * @return bool
     */
    public function build_link($type, $src)
    {
        if (empty($type)) {
            return false;
        }

        if (empty($src)) {
            return false;
        }

        $class = $this->get_class_object($type);
        if (!is_object($class)) {
            return false;
        }

        return $class->link($src);
    }

    /**
     * @param $flag_general
     * @return array
     */
    public function build_type_options($flag_general)
    {
        $list = $this->build_list();

        $arr = [];
        foreach ($list as $type) {
            if ((_C_WEBPHOTO_EMBED_NAME_GENERAL == $type) && !$flag_general) {
                continue;
            }
            $arr[$type] = $type;
        }

        return $arr;
    }

    /**
     * @param $type
     * @param $src
     * @return bool|string
     */
    public function build_src_desc($type, $src)
    {
        if (empty($type)) {
            return false;
        }

        $class = $this->get_class_object($type);
        if (!is_object($class)) {
            return false;
        }

        $lang = $class->lang_desc();
        if (empty($lang)) {
            $lang = _WEBPHOTO_EMBED_ENTER;
        }

        // typo
        $str = $lang . "<br>\n";
        $str .= _WEBPHOTO_EMBED_EXAMPLE . "<br>\n";
        $str .= $class->desc() . "<br>\n";

        if ($src) {
            $str .= '<img src="' . $class->thumb($src) . ' border="0" >';
            $str .= "<br>\n";
        }

        return $str;
    }

    /**
     * @param $type
     * @param $src
     * @return bool
     */
    public function build_thumb($type, $src)
    {
        if (empty($type)) {
            return false;
        }

        if (empty($src)) {
            return false;
        }

        $class = $this->get_class_object($type);
        if (!is_object($class)) {
            return false;
        }

        return $class->thumb($src);
    }

    /**
     * @param $type
     * @return array|bool
     */
    public function build_support_params($type)
    {
        if (empty($type)) {
            return false;
        }

        $class = $this->get_class_object($type);
        if (!is_object($class)) {
            return false;
        }

        $arr = $class->support_params();
        if (is_array($arr)) {
            return $arr;
        }

        $ret = $class->thumb('example');
        if ($ret) {
            $arr = [
                'thumb' => true,
            ];
        }

        return false;
    }

    // --- class end ---
}
