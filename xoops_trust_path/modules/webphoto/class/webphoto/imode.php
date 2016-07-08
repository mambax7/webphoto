<?php
// $Id: imode.php,v 1.3 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_photo_public
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_imode
//=========================================================
class webphoto_imode extends webphoto_show_photo
{
    public $_agent_class;
    public $_pagenavi_class;
    public $_multibyte_class;
    public $_photo_public_class;
    public $_item_public_class;

    public $_xoops_sitename;
    public $_item_ecnode_type_array;
    public $_is_set_mail;
    public $_has_mail;

    public $_TITLE_S;
    public $_MOBILE_TEMPLATE = null;

    public $_MOBILE_CHARSET_INTERNAL = _CHARSET;
    public $_MOBILE_CHARSET_OUTPUT   = _CHARSET;

    public $_MOBILE_LATEST_LIMIT   = 1;
    public $_MOBILE_RANDOM_LIMIT   = 1;
    public $_MOBILE_RANDOM_ORDERBY = 'rand()';
    public $_MOBILE_LIST_LIMIT     = 10;
    public $_MOBILE_LIST_ORDERBY   = 'item_time_update DESC, item_id DESC';
    public $_MOBILE_NAVI_WINDOWS   = 4;

    // preload
    public $_ARRAY_MOBILE_TEXT = null;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_agent_class        = webphoto_lib_user_agent::getInstance();
        $this->_pagenavi_class     = webphoto_lib_pagenavi::getInstance();
        $this->_multibyte_class    = webphoto_lib_multibyte::getInstance();
        $this->_photo_public_class = webphoto_photo_public::getInstance($dirname, $trust_dirname);
        $this->_item_public_class  = webphoto_item_public::getInstance($dirname, $trust_dirname);

        $this->set_charset_output();
        $this->set_mobile_carrier_array();

        $this->_is_set_mail = $this->_config_class->is_set_mail();
        $this->_has_mail    = $this->_perm_class->has_mail();

        $this->_MOBILE_TEMPLATE = 'db:' . $dirname . '_main_i.html';

        $this->_xoops_sitename = $this->_xoops_class->get_config_by_name('sitename');
        $this->_TITLE_S        = $this->sanitize($this->_MODULE_NAME . ' - ' . $this->_xoops_sitename);
    }

    public static function getInstance($dirname = null, $trust_dirname = null)
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new webphoto_imode($dirname, $trust_dirname);
        }
        return $instance;
    }

    //---------------------------------------------------------
    // set param
    //---------------------------------------------------------
    public function set_charset_output()
    {
        if (defined('_WEBPHOTO_CHARSET_MOBILE')) {
            if (constant('_WEBPHOTO_CHARSET_MOBILE')) {
                $this->_MOBILE_CHARSET_OUTPUT = _WEBPHOTO_CHARSET_MOBILE;
            }
        }
    }

    public function set_mobile_carrier_array()
    {
        if (function_exists('webphoto_mobile_carrier_array')) {
            $arr = webphoto_mobile_carrier_array();
            if (isset($arr)) {
                $this->_agent_class->set_mobile_carrier_array($arr);
            }
        }
    }

    public function get_encode_type_array()
    {
        $encode_type_array = $this->_item_handler->get_encode_type_array();

        $arr = array('uname');

        foreach ($encode_type_array as $name) {
            $arr[] = str_replace('item_', '', $name);
        }

        if (is_array($this->_ARRAY_MOBILE_TEXT)) {
            for ($i = 1; $i <= _C_WEBPHOTO_MAX_ITEM_TEXT; ++$i) {
                $name_i = 'text_' . $i;
                if (in_array('item_' . $name_i, $this->_ARRAY_MOBILE_TEXT)) {
                    $arr[] = $name_i;
                }
            }
        }

        return $arr;
    }

    //---------------------------------------------------------
    // common
    //---------------------------------------------------------
    public function output_header()
    {
        $this->http_output('pass');
        header('Content-Type:text/html; charset=' . $this->_MOBILE_CHARSET_OUTPUT);
    }

    public function check_perm()
    {
        if (!$this->_is_set_mail) {
            return false;
        }
        if ($this->_has_mail) {
            return true;
        }
        if ($this->_agent_class->parse_mobile_carrier()) {
            return true;
        }
        return false;
    }

    public function build_goto()
    {
        $url  = $this->_MODULE_URL . '/i.php?op=latest';
        $text = "<br><br>\n";
        $text .= '<a href="' . $url . '">';
        $text .= $this->sanitize($this->_MODULE_NAME);
        $text .= "</a><br>\n";
        return $text;
    }

    //---------------------------------------------------------
    // multibyte
    //---------------------------------------------------------
    public function http_output($encoding)
    {
        return $this->_multibyte_class->m_mb_http_output($encoding);
    }

    public function conv($str)
    {
        return $this->_multibyte_class->convert_encoding($str, $this->_MOBILE_CHARSET_OUTPUT, $this->_MOBILE_CHARSET_INTERNAL);
    }

    // --- class end ---
}
