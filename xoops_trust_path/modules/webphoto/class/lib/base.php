<?php
// $Id: base.php,v 1.20 2011/12/26 06:51:31 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-12-25 K.OHWADA
// remove get_mysql_date_today()
// 2010-10-01 K.OHWADA
// is_image_cmyk()
// 2010-01-10 K.OHWADA
// is_system_group()
// 2009-12-06 K.OHWADA
// get_system_groups()
// 2009-11-11 K.OHWADA
// get_files_in_dir()
// 2009-01-10 K.OHWADA
// build_random_file_name()
// 2008-12-12 K.OHWADA
// array_to_perm()
// 2008-11-29 K.OHWADA
// user_to_server_time()
// 2008-11-16 K.OHWADA
// get_cached_xoops_db_groups()
// 2008-10-10 K.OHWADA
// set_error_in_head_with_admin_info()
// 2008-10-01 K.OHWADA
// BUG : not set xoops_group
// 2008-09-01 K.OHWADA
// added build_set_msg()
// 2008-08-01 K.OHWADA
// added set_msg_array() check_token_and_redirect()
// 2008-07-01 K.OHWADA
// added build_error_msg()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_lib_base
//=========================================================

/**
 * Class webphoto_lib_base
 */
class webphoto_lib_base extends webphoto_lib_error
{
    public $_utility_class;
    public $_language_class;
    public $_xoops_class;
    public $_msg_class;

    // xoops param
    public $_xoops_language = null;
    public $_xoops_sitename = null;
    public $_xoops_adminmail = null;
    public $_xoops_anonymous = null;
    public $_xoops_uname = null;
    public $_xoops_groups = null;
    public $_xoops_uid = 0;
    public $_is_module_admin = false;
    public $_is_login_user = false;

    public $_token_error_flag = false;
    public $_token_errors = null;

    public $_msg_array = [];
    public $_msg_level_array = [];
    public $_msg_level = 0;

    public $_DIRNAME = null;
    public $_TRUST_DIRNAME = null;
    public $_MODULE_URL;
    public $_MODULE_DIR;
    public $_TRUST_DIR;

    public $_INDEX_PHP;
    public $_ADMIN_INDEX_PHP;

    public $_MODULE_NAME = null;
    public $_MODULE_ID = 0;
    public $_MODULE_HAS_CONFIG = false;

    public $_FLAG_ADMIN_SUB_MENU = true;

    public $_PERM_ALLOW_ALL = '*';
    public $_PERM_DENOY_ALL = 'x';
    public $_PERM_SEPARATOR = '&';

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_lib_base constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct();

        $this->_xoops_class = webphoto_xoops_base::getInstance();
        $this->_utility_class = webphoto_lib_utility::getInstance();

        // each msg box
        $this->_msg_class = new webphoto_lib_msg();

        $this->_DIRNAME = $dirname;
        $this->_MODULE_URL = XOOPS_URL . '/modules/' . $dirname;
        $this->_MODULE_DIR = XOOPS_ROOT_PATH . '/modules/' . $dirname;

        $this->_INDEX_PHP = $this->_MODULE_URL . '/index.php';
        $this->_ADMIN_INDEX_PHP = $this->_MODULE_URL . '/admin/index.php';

        $this->set_trust_dirname($trust_dirname);
        $this->_init_d3_language($dirname, $trust_dirname);
        $this->_init_xoops_param();
    }

    //---------------------------------------------------------
    // check
    //---------------------------------------------------------

    /**
     * @param $uid
     * @return bool
     */
    public function check_not_owner($uid)
    {
        if ($this->_is_module_admin) {
            return false;
        } elseif ($this->_is_login_user) {
            if ($this->_xoops_uid != $uid) {
                return true;
            }
        } else {
            return true;
        }

        return false;
    }

    //---------------------------------------------------------
    // header
    //---------------------------------------------------------

    /**
     * @param $title
     * @param $url
     * @return string
     */
    public function build_bread_crumb($title, $url)
    {
        $text = '<a href="' . $this->_MODULE_URL . '/index.php">';
        $text .= $this->sanitize($this->_MODULE_NAME);
        $text .= '</a>';
        $text .= ' &gt;&gt; ';
        $text .= '<a href="' . $url . '">';
        $text .= $this->sanitize($title);
        $text .= '</a>';
        $text .= "<br><br>\n";

        return $text;
    }

    //---------------------------------------------------------
    // for admin
    //---------------------------------------------------------

    /**
     * @param $title
     * @param $url
     * @return string
     */
    public function build_admin_bread_crumb($title, $url)
    {
        $text = '<a href="' . $this->_MODULE_URL . '/admin/index.php">';
        $text .= $this->sanitize($this->_MODULE_NAME);
        $text .= '</a>';
        $text .= ' &gt;&gt; ';
        $text .= '<a href="' . $url . '">';
        $text .= $this->sanitize($title);
        $text .= '</a>';
        $text .= "<br><br>\n";

        return $text;
    }

    /**
     * @return null|string
     */
    public function build_admin_menu()
    {
        $menu_class = webphoto_lib_admin_menu::getInstance($this->_DIRNAME, $this->_TRUST_DIRNAME);

        return $menu_class->build_menu_with_sub($this->_FLAG_ADMIN_SUB_MENU);
    }

    /**
     * @param      $name
     * @param bool $format
     * @return mixed|string
     */
    public function build_admin_title($name, $format = true)
    {
        $str = $this->get_admin_title($name);
        if ($format) {
            $str = '<h3>' . $str . "</h3>\n";
        }

        return $str;
    }

    /**
     * @param $name
     * @return mixed|string
     */
    public function get_admin_title($name)
    {
        $const_name_1 = mb_strtoupper('_MI_' . $this->_DIRNAME . '_ADMENU_' . $name);
        $const_name_2 = mb_strtoupper('_AM_' . $this->_TRUST_DIRNAME . '_TITLE_' . $name);

        if (defined($const_name_1)) {
            return constant($const_name_1);
        } elseif (defined($const_name_2)) {
            return constant($const_name_2);
        }

        return $const_name_2;
    }

    /**
     * @param      $msg
     * @param bool $flag_highlight
     * @param bool $flag_br
     */
    public function print_admin_msg($msg, $flag_highlight = false, $flag_br = false)
    {
        echo $this->build_admin_msg($msg, $flag_highlight, $flag_br);
    }

    /**
     * @param      $msg
     * @param bool $flag_highlight
     * @param bool $flag_br
     * @return null|string
     */
    public function build_admin_msg($msg, $flag_highlight = false, $flag_br = false)
    {
        if (!$this->_is_module_admin) {
            return null;
        }
        if ($flag_highlight) {
            $msg = $this->highlight($msg);
        }
        if ($flag_br) {
            $msg .= "<br>\n";
        }

        return $msg;
    }

    //---------------------------------------------------------
    // utility
    //---------------------------------------------------------

    /**
     * @param $arr
     * @param $glue
     * @return bool|string
     */
    public function array_to_perm($arr, $glue)
    {
        return $this->_utility_class->array_to_perm($arr, $glue);
    }

    /**
     * @param $str
     * @param $pattern
     * @return array
     */
    public function str_to_array($str, $pattern)
    {
        return $this->_utility_class->str_to_array($str, $pattern);
    }

    /**
     * @param $arr
     * @param $glue
     * @return bool|string
     */
    public function array_to_str($arr, $glue)
    {
        return $this->_utility_class->array_to_str($arr, $glue);
    }

    /**
     * @param $str
     * @return string
     */
    public function add_slash_to_head($str)
    {
        return $this->_utility_class->add_slash_to_head($str);
    }

    /**
     * @param $str
     * @return bool|string
     */
    public function strip_slash_from_head($str)
    {
        return $this->_utility_class->strip_slash_from_head($str);
    }

    /**
     * @param $dir
     * @return bool|string
     */
    public function strip_slash_from_tail($dir)
    {
        return $this->_utility_class->strip_slash_from_tail($dir);
    }

    /**
     * @param $file
     * @return string
     */
    public function parse_ext($file)
    {
        return $this->_utility_class->parse_ext($file);
    }

    /**
     * @param $file
     * @return mixed
     */
    public function strip_ext($file)
    {
        return $this->_utility_class->strip_ext($file);
    }

    /**
     * @param $old
     * @param $new
     * @return bool
     */
    public function rename_file($old, $new)
    {
        return $this->_utility_class->rename_file($old, $new);
    }

    /**
     * @param      $src
     * @param      $dst
     * @param bool $flag
     * @return bool
     */
    public function copy_file($src, $dst, $flag = false)
    {
        return $this->_utility_class->copy_file($src, $dst, $flag);
    }

    /**
     * @param $file
     * @return bool
     */
    public function unlink_file($file)
    {
        return $this->_utility_class->unlink_file($file);
    }

    /**
     * @param $str
     * @return bool
     */
    public function check_http_start($str)
    {
        return $this->_utility_class->check_http_start($str);
    }

    /**
     * @param $str
     * @return bool
     */
    public function check_http_null($str)
    {
        return $this->_utility_class->check_http_null($str);
    }

    /**
     * @param $width
     * @param $height
     * @param $max_width
     * @param $max_height
     * @return array
     */
    public function adjust_image_size($width, $height, $max_width, $max_height)
    {
        return $this->_utility_class->adjust_image_size($width, $height, $max_width, $max_height);
    }

    /**
     * @param $file
     * @return bool
     */
    public function is_image_cmyk($file)
    {
        return $this->_utility_class->is_image_cmyk($file);
    }

    /**
     * @param        $msg
     * @param string $title
     * @param bool   $flag_sanitize
     * @return string
     */
    public function build_error_msg($msg, $title = '', $flag_sanitize = true)
    {
        return $this->_utility_class->build_error_msg($msg, $title, $flag_sanitize);
    }

    /**
     * @param      $id
     * @param      $ext
     * @param null $extra
     * @return string
     */
    public function build_random_file_name($id, $ext, $extra = null)
    {
        return $this->_utility_class->build_random_file_name($id, $ext, $extra);
    }

    /**
     * @param      $id
     * @param null $extra
     * @return string
     */
    public function build_random_file_node($id, $extra = null)
    {
        return $this->_utility_class->build_random_file_node($id, $extra);
    }

    /**
     * @param $url
     * @return mixed|null
     */
    public function parse_url_to_filename($url)
    {
        return $this->_utility_class->parse_url_to_filename($url);
    }

    /**
     * @param      $path
     * @param null $ext
     * @param bool $flag_dir
     * @param bool $flag_sort
     * @param bool $id_as_key
     * @return array|bool
     */
    public function get_files_in_dir($path, $ext = null, $flag_dir = false, $flag_sort = false, $id_as_key = false)
    {
        return $this->_utility_class->get_files_in_dir($path, $ext, $flag_dir, $flag_sort, $id_as_key);
    }

    //---------------------------------------------------------
    // sanitize
    //---------------------------------------------------------
    //---------------------------------------------------------
    // TAB \x09 \t
    // LF  \xOA \n
    // CR  \xOD \r
    //---------------------------------------------------------

    /**
     * @param        $str
     * @param string $replace
     * @return null|string|string[]
     */
    public function str_replace_control_code($str, $replace = ' ')
    {
        $str = preg_replace('/[\x00-\x08]/', $replace, $str);
        $str = preg_replace('/[\x0B-\x0C]/', $replace, $str);
        $str = preg_replace('/[\x0E-\x1F]/', $replace, $str);
        $str = preg_replace('/[\x7F]/', $replace, $str);

        return $str;
    }

    /**
     * @param        $str
     * @param string $replace
     * @return null|string|string[]
     */
    public function str_replace_tab_code($str, $replace = ' ')
    {
        return preg_replace("/\t/", $replace, $str);
    }

    /**
     * @param        $str
     * @param string $replace
     * @return null|string|string[]
     */
    public function str_replace_return_code($str, $replace = ' ')
    {
        $str = preg_replace("/\n/", $replace, $str);
        $str = preg_replace("/\r/", $replace, $str);

        return $str;
    }

    /**
     * @param $arr_in
     * @return array|null
     */
    public function sanitize_array_int($arr_in)
    {
        if (!is_array($arr_in) || !count($arr_in)) {
            return null;
        }

        $arr_out = [];
        foreach ($arr_in as $in) {
            $arr_out[] = (int)$in;
        }

        return $arr_out;
    }

    //---------------------------------------------------------
    // msg class
    //---------------------------------------------------------

    /**
     * @param      $msg
     * @param bool $flag_highlight
     * @param bool $flag_br
     */
    public function build_set_msg($msg, $flag_highlight = false, $flag_br = false)
    {
        $this->set_msg($this->build_msg($msg, $flag_highlight, $flag_br));
    }

    /**
     * @param $val
     */
    public function set_msg_level($val)
    {
        $this->_msg_level = (int)$val;
    }

    /**
     * @param $level
     * @return bool
     */
    public function check_msg_level($level)
    {
        if (($this->_msg_level > 0) && ($this->_msg_level >= $level)) {
            return true;
        }

        return false;
    }

    /**
     * @param      $level
     * @param      $msg
     * @param bool $flag_highlight
     * @param bool $flag_br
     * @return null|string
     */
    public function build_msg_level($level, $msg, $flag_highlight = false, $flag_br = false)
    {
        if ($this->check_msg_level($level)) {
            return $this->build_msg($msg, $flag_highlight, $flag_br);
        }

        return null;
    }

    /**
     * @param      $msg
     * @param bool $flag_highlight
     * @param bool $flag_br
     * @return string
     */
    public function build_msg($msg, $flag_highlight = false, $flag_br = false)
    {
        if ($flag_highlight) {
            $msg = $this->highlight($msg);
        }
        if ($flag_br) {
            $msg .= "<br>\n";
        }

        return $msg;
    }

    /**
     * @param $msg
     */
    public function set_error_in_head_with_admin_info($msg)
    {
        $arr = $this->get_errors();
        $this->clear_errors();
        $this->set_error($msg);
        if ($this->_is_module_admin) {
            $this->set_error($arr);
        }
    }

    //---------------------------------------------------------
    // msg class
    //---------------------------------------------------------
    public function clear_msg_array()
    {
        $this->_msg_class->clear_msg_array();
    }

    /**
     * @return array
     */
    public function get_msg_array()
    {
        return $this->_msg_class->get_msg_array();
    }

    /**
     * @return bool
     */
    public function has_msg_array()
    {
        return $this->_msg_class->has_msg_array();
    }

    /**
     * @param      $msg
     * @param bool $flag_highlight
     */
    public function set_msg_array($msg, $flag_highlight = false)
    {
        return $this->_msg_class->set_msg($msg, $flag_highlight);
    }

    /**
     * @param      $msg
     * @param bool $flag_highlight
     */
    public function set_msg($msg, $flag_highlight = false)
    {
        return $this->_msg_class->set_msg($msg, $flag_highlight);
    }

    /**
     * @param bool $flag_sanitize
     * @param bool $flag_highlight
     * @param bool $flag_br
     * @return string
     */
    public function get_format_msg_array($flag_sanitize = true, $flag_highlight = true, $flag_br = true)
    {
        return $this->_msg_class->get_format_msg_array($flag_sanitize, $flag_highlight, $flag_br);
    }

    //---------------------------------------------------------
    // head
    //---------------------------------------------------------

    /**
     * @param null $title
     * @param null $charset
     * @return string
     */
    public function build_html_head($title = null, $charset = null)
    {
        if (empty($charset)) {
            $charset = _CHARSET;
        }

        $text = '<html><head>' . "\n";
        $text .= '<meta http-equiv="Content-Type" content="text/html; charset=' . $this->sanitize($charset) . '" >' . "\n";
        $text .= '<title>' . $this->sanitize($title) . '</title>' . "\n";
        $text .= '</head>' . "\n";

        return $text;
    }

    /**
     * @return string
     */
    public function build_html_body_begin()
    {
        $text = '<body>' . "\n";

        return $text;
    }

    /**
     * @return string
     */
    public function build_html_body_end()
    {
        $text = '</body></html>' . "\n";

        return $text;
    }

    //---------------------------------------------------------
    // token
    //---------------------------------------------------------

    /**
     * @return string
     */
    public function get_token_name()
    {
        return 'XOOPS_G_TICKET';
    }

    public function get_token()
    {
        global $xoopsGTicket;
        if (is_object($xoopsGTicket)) {
            return $xoopsGTicket->issue();
        }

        return null;
    }

    /**
     * @param bool $allow_repost
     * @return bool
     */
    public function check_token($allow_repost = false)
    {
        global $xoopsGTicket;
        if (is_object($xoopsGTicket)) {
            if (!$xoopsGTicket->check(true, '', $allow_repost)) {
                $this->_token_error_flag = true;
                $this->_token_errors = $xoopsGTicket->getErrors();

                return false;
            }
        }
        $this->_token_error_flag = false;

        return true;
    }

    public function get_token_errors()
    {
        return $this->_token_errors;
    }

    /**
     * @return bool
     */
    public function check_token_with_print_error()
    {
        $ret = $this->check_token();
        if (!$ret) {
            echo $this->build_error_msg('Token Error');
        }

        return $ret;
    }

    /**
     * @param     $url
     * @param int $time
     * @return bool
     */
    public function check_token_and_redirect($url, $time = 5)
    {
        if (!$this->check_token()) {
            $msg = 'Token Error';
            if ($this->_is_module_admin) {
                $msg .= '<br>' . $this->get_token_errors();
            }
            redirect_header($url, $time, $msg);
            exit();
        }

        return true;
    }

    public function set_token_error()
    {
        $this->set_error('Token Error');
        if ($this->_is_module_admin) {
            $this->set_error($this->get_token_errors());
        }
    }

    //---------------------------------------------------------
    // xoops param
    //---------------------------------------------------------
    public function _init_xoops_param()
    {
        $this->_xoops_language = $this->_xoops_class->get_config_by_name('language');
        $this->_xoops_sitename = $this->_xoops_class->get_config_by_name('sitename');
        $this->_xoops_adminmail = $this->_xoops_class->get_config_by_name('adminmail');
        $this->_xoops_anonymous = $this->_xoops_class->get_config_by_name('anonymous');

        $this->_MODULE_ID = $this->_xoops_class->get_my_module_id();
        $this->_MODULE_NAME = $this->_xoops_class->get_my_module_name('n');
        $this->_MODULE_HAS_CONFIG = $this->_xoops_class->get_my_module_value_by_name('hasconfig');

        $this->_xoops_uid = $this->_xoops_class->get_my_user_uid();
        $this->_xoops_uname = $this->_xoops_class->get_my_user_uname('n');
        $this->_xoops_groups = $this->_xoops_class->get_my_user_groups();
        $this->_is_login_user = $this->_xoops_class->get_my_user_is_login();
        $this->_is_module_admin = $this->_xoops_class->get_my_user_is_module_admin();
    }

    /**
     * @return int
     */
    public function has_xoops_config_this_module()
    {
        return $this->_xoops_class->has_my_module_config();
    }

    /**
     * @param     $uid
     * @param int $usereal
     * @return string
     */
    public function get_xoops_uname_by_uid($uid, $usereal = 0)
    {
        return $this->_xoops_class->get_user_uname_from_id($uid, $usereal);
    }

    /**
     * @param $uid
     * @return bool|mixed
     */
    public function get_xoops_email_by_uid($uid)
    {
        return $this->_xoops_class->get_user_email_from_id($uid);
    }

    /**
     * @param $dirname
     * @return mixed
     */
    public function get_xoops_module_by_dirname($dirname)
    {
        return $this->_xoops_class->get_module_by_dirname($dirname);
    }

    /**
     * @return mixed
     */
    public function get_xoops_group_objs()
    {
        return $this->_xoops_class->get_group_obj();
    }

    /**
     * @param bool   $none
     * @param string $none_name
     * @param string $format
     * @return array
     */
    public function get_cached_xoops_db_groups($none = false, $none_name = '---', $format = 's')
    {
        return $this->_xoops_class->get_cached_groups($none, $none_name, $format);
    }

    /**
     * @param        $id
     * @param string $format
     * @return bool
     */
    public function get_xoops_group_name($id, $format = 's')
    {
        return $this->_xoops_class->get_cached_group_by_id_name($id, 'name', $format);
    }

    /**
     * @return array
     */
    public function get_system_groups()
    {
        return $this->_xoops_class->get_system_groups();
    }

    /**
     * @param $id
     * @return bool
     */
    public function is_system_group($id)
    {
        return $this->_xoops_class->is_system_group($id);
    }

    //---------------------------------------------------------
    // timestamp
    //---------------------------------------------------------

    /**
     * @param $time
     * @return float|int
     */
    public function user_to_server_time($time)
    {
        return $this->_xoops_class->user_to_server_time($time);
    }

    /**
     * @param        $time
     * @param string $format
     * @param string $timeoffset
     * @return string
     */
    public function format_timestamp($time, $format = 'l', $timeoffset = '')
    {
        return formatTimestamp($time, $format, $timeoffset);
    }

    //---------------------------------------------------------
    // d3 language
    //---------------------------------------------------------

    /**
     * @param $dirname
     * @param $trust_dirname
     */
    public function _init_d3_language($dirname, $trust_dirname)
    {
        $this->_language_class = webphoto_d3_language::getInstance();
        $this->_language_class->init($dirname, $trust_dirname);
    }

    /**
     * @return mixed
     */
    public function get_lang_array()
    {
        return $this->_language_class->get_lang_array();
    }

    /**
     * @param $name
     * @return mixed
     */
    public function get_constant($name)
    {
        return $this->_language_class->get_constant($name);
    }

    /**
     * @param $trust_dirname
     */
    public function set_trust_dirname($trust_dirname)
    {
        $this->_TRUST_DIRNAME = $trust_dirname;
        $this->_TRUST_DIR = XOOPS_TRUST_PATH . '/modules/' . $trust_dirname;
    }

    // --- class end ---
}
