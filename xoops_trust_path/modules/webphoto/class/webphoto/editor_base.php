<?php
// $Id: editor_base.php,v 1.3 2010/02/07 12:20:02 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-04 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-02-01 K.OHWADA
// sanitize()
// 2009-11-11 K.OHWADA
// typo _allow_in_not_has_htmll
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_editor_base
//=========================================================

/**
 * Class webphoto_editor_base
 */
class webphoto_editor_base
{
    public $_allow_in_not_has_html = false;
    public $_show_display_options = false;
    public $_display_html = 0;
    public $_display_smiley = 0;
    public $_display_xcode = 0;
    public $_display_image = 0;
    public $_display_br = 0;

    public function __construct()
    {
        // dummy
    }

    /**
     * @param $val
     */
    public function set_allow_in_not_has_html($val)
    {
        $this->_allow_in_not_has_html = (bool)$val;
    }

    /**
     * @param $val
     */
    public function set_show_display_options($val)
    {
        $this->_show_display_options = (bool)$val;
    }

    /**
     * @param $val
     */
    public function set_display_html($val)
    {
        $this->_display_html = (int)$val;
    }

    /**
     * @param $val
     */
    public function set_display_smiley($val)
    {
        $this->_display_smiley = (int)$val;
    }

    /**
     * @param $val
     */
    public function set_display_xcode($val)
    {
        $this->_display_xcode = (int)$val;
    }

    /**
     * @param $val
     */
    public function set_display_image($val)
    {
        $this->_display_image = (int)$val;
    }

    /**
     * @param $val
     */
    public function set_display_br($val)
    {
        $this->_display_br = (int)$val;
    }

    /**
     * @return bool
     */
    public function allow_in_not_has_html()
    {
        // typo
        return $this->_allow_in_not_has_html;
    }

    /**
     * @return bool
     */
    public function show_display_options()
    {
        return $this->_show_display_options;
    }

    /**
     * @return array
     */
    public function display_options()
    {
        $arr = [
            'html' => $this->_display_html,
            'smiley' => $this->_display_smiley,
            'xcode' => $this->_display_xcode,
            'image' => $this->_display_image,
            'br' => $this->_display_br,
        ];

        return $arr;
    }

    /**
     * @return bool
     */
    public function exists()
    {
        return false;
    }

    public function build_js()
    {
        return null;
    }

    /**
     * @param $id
     * @param $name
     * @param $value
     * @param $rows
     * @param $cols
     */
    public function build_textarea($id, $name, $value, $rows, $cols)
    {
        return null;
    }

    /**
     * @param $str
     * @return string
     */
    public function sanitize($str)
    {
        return htmlspecialchars($str, ENT_QUOTES);
    }

    // --- class end ---
}
