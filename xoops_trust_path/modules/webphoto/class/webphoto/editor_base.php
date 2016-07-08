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
class webphoto_editor_base
{
    public $_allow_in_not_has_html = false;
    public $_show_display_options  = false;
    public $_display_html          = 0;
    public $_display_smiley        = 0;
    public $_display_xcode         = 0;
    public $_display_image         = 0;
    public $_display_br            = 0;

    public function __construct()
    {
        // dummy
    }

    public function set_allow_in_not_has_html($val)
    {
        $this->_allow_in_not_has_html = (bool)$val;
    }

    public function set_show_display_options($val)
    {
        $this->_show_display_options = (bool)$val;
    }

    public function set_display_html($val)
    {
        $this->_display_html = (int)$val;
    }

    public function set_display_smiley($val)
    {
        $this->_display_smiley = (int)$val;
    }

    public function set_display_xcode($val)
    {
        $this->_display_xcode = (int)$val;
    }

    public function set_display_image($val)
    {
        $this->_display_image = (int)$val;
    }

    public function set_display_br($val)
    {
        $this->_display_br = (int)$val;
    }

    public function allow_in_not_has_html()
    {
        // typo
        return $this->_allow_in_not_has_html;
    }

    public function show_display_options()
    {
        return $this->_show_display_options;
    }

    public function display_options()
    {
        $arr = array(
            'html'   => $this->_display_html,
            'smiley' => $this->_display_smiley,
            'xcode'  => $this->_display_xcode,
            'image'  => $this->_display_image,
            'br'     => $this->_display_br
        );
        return $arr;
    }

    public function exists()
    {
        return false;
    }

    public function build_js()
    {
        return null;
    }

    public function build_textarea($id, $name, $value, $rows, $cols)
    {
        return null;
    }

    public function sanitize($str)
    {
        return htmlspecialchars($str, ENT_QUOTES);
    }

    // --- class end ---
}
