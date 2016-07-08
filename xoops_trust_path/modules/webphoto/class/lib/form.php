<?php
// $Id: form.php,v 1.8 2011/12/26 06:51:31 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-12-25 K.OHWADA
// remove get_mysql_date_today()
// 2009-12-06 K.OHWADA
// get_system_groups()
// 2009-05-05 K.OHWADA
// get_post_text()
// 2008-11-29 K.OHWADA
// format_timestamp()
// 2008-11-16 K.OHWADA
// get_cached_xoops_db_groups()
// _xoops_user_groups -> _xoops_groups
// 2008-10-01 K.OHWADA
// use build_menu_with_sub()
// 2008-08-01 K.OHWADA
// added build_error_msg()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_lib_form
//=========================================================
class webphoto_lib_form extends webphoto_lib_element
{
    public $_post_class;
    public $_utility_class;
    public $_pagenavi_class;
    public $_language_class;
    public $_xoops_class;

    // xoops param
    public $_is_login_user   = false;
    public $_is_module_admin = false;
    public $_xoops_language;
    public $_xoops_sitename;
    public $_xoops_uid       = 0;
    public $_xoops_uname     = null;
    public $_xoops_groups    = null;

    public $_DIRNAME       = null;
    public $_TRUST_DIRNAME = null;
    public $_MODULE_DIR;
    public $_MODULE_URL;
    public $_TRUST_DIR;

    public $_MODULE_NAME = null;
    public $_MODULE_ID   = 0;
    public $_TIME_START  = 0;

    public $_THIS_FCT_URL;

    public $_LANG_MUST_LOGIN = 'You must login';
    public $_LANG_TIME_SET   = 'Set Time';

    public $_FLAG_ADMIN_SUB_MENU = true;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct();

        $this->set_form_name($dirname . '_form');
        $this->set_title_header($dirname);

        $this->_xoops_class   = webphoto_xoops_base::getInstance();
        $this->_post_class    = webphoto_lib_post::getInstance();
        $this->_utility_class = webphoto_lib_utility::getInstance();

        $this->set_keyword_min($this->_xoops_class->get_search_config_by_name('keyword_min'));

        $this->_DIRNAME     = $dirname;
        $this->_MODULE_DIR  = XOOPS_ROOT_PATH . '/modules/' . $dirname;
        $this->_MODULE_URL  = XOOPS_URL . '/modules/' . $dirname;
        $this->_MODULE_NAME = $dirname;

        $this->_THIS_FCT_URL = $this->_THIS_URL;
        $get_fct             = $this->get_fct_from_post();
        if ($get_fct) {
            $this->_THIS_FCT_URL .= '?fct=' . $get_fct;
        }

        $this->_init_xoops_param();
        $this->_init_d3_language($dirname, $trust_dirname);
    }

    public static function getInstance($dirname = null, $trust_dirname = null)
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new webphoto_lib_form($dirname, $trust_dirname);
        }
        return $instance;
    }

    //---------------------------------------------------------
    // form
    //---------------------------------------------------------
    public function get_post_js_checkbox_array()
    {
        $name = $this->_FORM_NAME . '_id';
        return $this->get_post($name);
    }

    //---------------------------------------------------------
    // paginavi
    //---------------------------------------------------------
    public function init_pagenavi()
    {
        $this->_pagenavi_class = webphoto_lib_pagenavi::getInstance();
    }

    public function build_form_pagenavi_perpage()
    {
        $form_name = $this->_FORM_NAME . '_perpage';

        $text = '<div align="center">';
        $text .= $this->build_form_tag($form_name, $this->_THIS_URL, 'get');
        $text .= $this->build_input_hidden('sortid', $this->pagenavi_get_sortid());
        $text .= $this->build_input_hidden('fct', $this->get_fct_from_post());
        $text .= 'per page' . ' ';
        $text .= $this->build_input_text('perpage', $this->pagenavi_get_perpage(), $this->_SIZE_PERPAGE);
        $text .= ' ';
        $text .= $this->build_input_submit('submit', 'SET');
        $text .= $this->build_form_end();
        $text .= "</div><br />\n";
        return $text;
    }

    public function pagenavi_get_sortid()
    {
        return $this->_pagenavi_class->get_sortid();
    }

    public function pagenavi_get_perpage()
    {
        return $this->_pagenavi_class->get_perpage();
    }

    public function get_fct_from_post()
    {
        return $this->get_post_get_text('fct');
    }

    //---------------------------------------------------------
    // for admin
    //---------------------------------------------------------
    public function build_admin_menu()
    {
        $menu_class = webphoto_lib_admin_menu::getInstance($this->_DIRNAME, $this->_TRUST_DIRNAME);
        return $menu_class->build_menu_with_sub($this->_FLAG_ADMIN_SUB_MENU);
    }

    public function build_admin_title($name, $format = true)
    {
        $str = $this->get_admin_title($name);
        if ($format) {
            $str = '<h3>' . $str . "</h3>\n";
        }
        return $str;
    }

    public function get_admin_title($name)
    {
        $const_name_1 = strtoupper('_MI_' . $this->_DIRNAME . '_ADMENU_' . $name);
        $const_name_2 = strtoupper('_AM_' . $this->_TRUST_DIRNAME . '_TITLE_' . $name);

        if (defined($const_name_1)) {
            return constant($const_name_1);
        } elseif (defined($const_name_2)) {
            return constant($const_name_2);
        }
        return $const_name_2;
    }

    //---------------------------------------------------------
    // utility class
    //---------------------------------------------------------
    public function str_to_array($str, $pattern)
    {
        return $this->_utility_class->str_to_array($str, $pattern);
    }

    public function array_to_str($arr, $glue)
    {
        return $this->_utility_class->array_to_str($arr, $glue);
    }

    public function format_filesize($size)
    {
        return $this->_utility_class->format_filesize($size);
    }

    public function parse_ext($file)
    {
        return $this->_utility_class->parse_ext($file);
    }

    public function build_error_msg($msg, $title = '', $flag_sanitize = true)
    {
        return $this->_utility_class->build_error_msg($msg, $title, $flag_sanitize);
    }

    //---------------------------------------------------------
    // post class
    //---------------------------------------------------------
    public function get_post_text($key, $default = null)
    {
        return $this->_post_class->get_post_text($key, $default);
    }

    public function get_post_int($key, $default = 0)
    {
        return $this->_post_class->get_post_int($key, $default);
    }

    public function get_post_float($key, $default = 0)
    {
        return $this->_post_class->get_post_float($key, $default);
    }

    public function get_post($key, $default = null)
    {
        return $this->_post_class->get_post($key, $default);
    }

    public function get_post_get_text($key, $default = null)
    {
        return $this->_post_class->get_post_get_text($key, $default);
    }

    public function get_post_get_int($key, $default = 0)
    {
        return $this->_post_class->get_post_get_int($key, $default);
    }

    //---------------------------------------------------------
    // xoops
    //---------------------------------------------------------
    public function _init_xoops_param()
    {
        $this->_xoops_language = $this->_xoops_class->get_config_by_name('language');
        $this->_xoops_sitename = $this->_xoops_class->get_config_by_name('sitename');

        $this->_MODULE_ID   = $this->_xoops_class->get_my_module_id();
        $this->_MODULE_NAME = $this->_xoops_class->get_my_module_name('n');

        $this->_xoops_uid       = $this->_xoops_class->get_my_user_uid();
        $this->_xoops_uname     = $this->_xoops_class->get_my_user_uname('n');
        $this->_xoops_groups    = $this->_xoops_class->get_my_user_groups();
        $this->_is_login_user   = $this->_xoops_class->get_my_user_is_login();
        $this->_is_module_admin = $this->_xoops_class->get_my_user_is_module_admin();
    }

    public function get_xoops_group_objs()
    {
        return $this->_xoops_class->get_group_obj();
    }

    public function get_cached_xoops_db_groups($none = false, $none_name = '---', $format = 's')
    {
        return $this->_xoops_class->get_cached_groups($none, $none_name, $format);
    }

    public function get_system_groups()
    {
        return $this->_xoops_class->get_system_groups();
    }

    public function get_xoops_user_name($uid, $usereal = 0)
    {
        return $this->_xoops_class->get_user_uname_from_id($uid, $usereal);
    }

    public function build_xoops_userinfo($uid, $usereal = 0)
    {
        return $this->_xoops_class->build_userinfo($uid, $usereal);
    }

    public function get_xoops_user_list($limit = 0, $start = 0)
    {
        return $this->_xoops_class->get_member_user_list($limit, $start);
    }

    public function check_login()
    {
        if ($this->_is_login_user) {
            return true;
        }

        redirect_header(XOOPS_URL . '/user.php', 3, $this->_LANG_MUST_LOGIN);
        exit();
    }

    //---------------------------------------------------------
    // timestamp
    //---------------------------------------------------------
    public function format_timestamp($time, $format = 'l', $timeoffset = '')
    {
        return formatTimestamp($time, $format, $timeoffset);
    }

    //---------------------------------------------------------
    // d3 language
    //---------------------------------------------------------
    public function _init_d3_language($dirname, $trust_dirname)
    {
        $this->_language_class = webphoto_d3_language::getInstance();
        $this->_language_class->init($dirname, $trust_dirname);
        $this->set_trust_dirname($trust_dirname);
    }

    public function get_lang_array()
    {
        return $this->_language_class->get_lang_array();
    }

    public function get_constant($name)
    {
        return $this->_language_class->get_constant($name);
    }

    public function set_trust_dirname($trust_dirname)
    {
        $this->_TRUST_DIRNAME = $trust_dirname;
        $this->_TRUST_DIR     = XOOPS_TRUST_PATH . '/modules/' . $trust_dirname;
    }

    // --- class end ---
}
