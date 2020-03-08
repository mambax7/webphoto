<?php
// $Id: editor.php,v 1.2 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-04 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// webphoto_lib_plugin -> webphoto_plugin_ini
// item_editor_fefault
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_editor
//=========================================================

/**
 * Class webphoto_editor
 */
class webphoto_editor extends webphoto_plugin_ini
{
    public $_has_html = false;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_editor constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);
        $this->set_dirname('editors');
        $this->set_prefix('webphoto_editor_');

        $this->_perm_class = webphoto_permission::getInstance($dirname, $trust_dirname);
        $this->_has_html = $this->_perm_class->has_html();
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_editor|\webphoto_lib_plugin
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
    // editor
    //---------------------------------------------------------

    /**
     * @param $type
     * @return bool
     */
    public function display_options($type)
    {
        if (empty($type)) {
            $type = $this->get_ini('item_editor_default');
        }

        $class = $this->get_class_object($type);
        if (!is_object($class)) {
            return false;
        }

        return $class->display_options();
    }

    /**
     * @param $type
     * @param $id
     * @param $name
     * @param $value
     * @param $rows
     * @param $cols
     * @return array|bool
     */
    public function init_form($type, $id, $name, $value, $rows, $cols)
    {
        $class = $this->get_class_object($type);
        if (!is_object($class)) {
            return false;
        }

        $arr = [
            'js' => $class->build_js(),
            'show' => $class->show_display_options(),
            'desc' => $class->build_textarea($id, $name, $value, $rows, $cols),
        ];

        return $arr;
    }

    /**
     * @param $flag
     * @return array|bool
     */
    public function build_list_options($flag)
    {
        $list = $this->build_list();
        $arr = [];
        foreach ($list as $type) {
            if ($this->exists($type)) {
                $arr[$type] = $type;
            }
        }
        if ($flag
            && is_array($arr)
            && (1 == count($arr))
            && isset($arr[_C_WEBPHOTO_EDITOR_DEFAULT])) {
            return false;
        }

        return $arr;
    }

    /**
     * @param $type
     * @return bool
     */
    public function exists($type)
    {
        $class = $this->get_class_object($type);
        if (!is_object($class)) {
            return false;
        }

        if ($class->exists()
            && ($this->_has_html || $class->allow_in_not_has_html())) {
            return true;
        }

        return false;
    }

    // --- class end ---
}
