<?php
// $Id: form.php,v 1.19 2011/12/26 06:51:31 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-12-25 K.OHWADA
// webphoto_lib_mysql_utility
// 2010-11-11 K.OHWADA
// get_cached_file_extend_row_by_kind()
// 2010-09-17 K.OHWADA
// move build_form_user_select_options() to webphoto_admin_item_form
// 2010-02-15 K.OHWADA
// get_group_perms_for_group_perms_checkboxs()
// 2010-01-10 K.OHWADA
// show_form_editor -> submit_show_form_editor
// 2009-12-06 K.OHWADA
// build_group_perms_param_by_key()
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_item_handler
// webphoto_embed
// item_editor_default
// 2009-06-28 K.OHWADA
// set_default_item_row()
// 2009-05-30 K.OHWADA
// changed item_cat_id_options()
// 2009-05-17 K.OHWADA
// show_err_invalid_cat()
// 2009-05-05 K.OHWADA
// build_form_param() -> build_form_base_param()
// build_form_select_param()
// 2009-04-27 K.OHWADA
// build_script_edit_js()
// 2009-04-21 K.OHWADA
// Fatal error: Call to undefined method build_js_envelop()
// 2009-04-19 K.OHWADA
// build_form_param()
// 2009-03-15 K.OHWADA
// _SMALL_FIELD_NAME
// 2009-01-10 K.OHWADA
// webphoto_form_this -> webphoto_edit_form
// 2009-01-04 K.OHWADA
// build_line_category() etc
// 2008-12-12 K.OHWADA
// $_UPLOADS_PATH
// 2008-11-29 K.OHWADA
// $_ROOT_EXTS_URL
// 2008-11-16 K.OHWADA
// set_xoops_groups()
// 2008-11-08 K.OHWADA
// tmpdir -> workdir
// 2008-10-01 K.OHWADA
// use get_uploads_path()
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
// added preload_init()
// 2008-08-01 K.OHWADA
// added getInstance()
// tmppath -> tmpdir
// 2008-07-01 K.OHWADA
// used _TMP_PATH
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_edit_form
//=========================================================
class webphoto_edit_form extends webphoto_lib_form
{
    public $_cat_handler;
    public $_item_handler;
    public $_file_handler;
    public $_config_class;
    public $_preload_class;
    public $_perm_class;
    public $_embed_class;
    public $_ini_class;
    public $_mysql_utility_class;

    public $_cfg_gmap_apikey;
    public $_cfg_width;
    public $_cfg_height;
    public $_cfg_fsize;
    public $_cfg_makethumb;
    public $_cfg_file_size;
    public $_cfg_perm_item_read;
    public $_cfg_perm_cat_read;
    public $_cfg_use_pathinfo;
    public $_cfg_cat_width;

    public $_has_deletable;

    // post
    public $_post_form_editor   = 0;
    public $_post_form_embed    = 0;
    public $_post_form_playlist = 0;

    public $_is_japanese    = false;
    public $_checkbox_array = array();

    public $_FILED_COUNTER_1 = 1;
    public $_FILED_COUNTER_2 = 2;
    public $_FILED_COUNTER_4 = 4;

    public $_UPLOADS_PATH;
    public $_MEDIAS_PATH;
    public $_WORK_DIR;
    public $_FILE_DIR;
    public $_PHOTOS_PATH;
    public $_PHOTOS_DIR;
    public $_PHOTOS_URL;
    public $_THUMBS_PATH;
    public $_THUMBS_DIR;
    public $_THUMBS_URL;
    public $_CATS_PATH;
    public $_CATS_DIR;
    public $_CATS_URL;
    public $_MEDIAS_DIR;
    public $_MEDIAS_URL;
    public $_PLAYLISTS_DIR;
    public $_PLAYLISTS_URL;
    public $_TMP_DIR;
    public $_MAIL_DIR;
    public $_LOG_DIR;

    public $_ICONS_URL;
    public $_ICON_ROTATE_URL;
    public $_ROOT_EXTS_DIR;
    public $_ROOT_EXTS_URL;
    public $_LIBS_URL;

    public $_PHOTO_FIELD_NAME  = _C_WEBPHOTO_UPLOAD_FIELD_PHOTO;
    public $_THUMB_FIELD_NAME  = _C_WEBPHOTO_UPLOAD_FIELD_THUMB;
    public $_MIDDLE_FIELD_NAME = _C_WEBPHOTO_UPLOAD_FIELD_MIDDLE;
    public $_SMALL_FIELD_NAME  = _C_WEBPHOTO_UPLOAD_FIELD_SMALL;

    public $_THIS_FCT_IMAGEMANEGER = 'submit_imagemanager';
    public $_THIS_FCT_SUBMIT       = 'submit';
    public $_THIS_FCT_EDIT         = 'edit';
    public $_THIS_FCT_ADMIN        = 'item_manager';
    public $_THIS_FCT_ADMIN_BATCH  = 'batch';

    public $_FLAG_PERM_ADMIN = true;

    public $_FORM_ACTION = null;
    public $_THIS_FCT    = null;

    public $_USER_LIMIT = 200;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_config_class = webphoto_config::getInstance($dirname);

        $this->_cat_handler         = webphoto_cat_handler::getInstance($dirname, $trust_dirname);
        $this->_item_handler        = webphoto_item_handler::getInstance($dirname, $trust_dirname);
        $this->_file_handler        = webphoto_file_handler::getInstance($dirname, $trust_dirname);
        $this->_perm_class          = webphoto_permission::getInstance($dirname, $trust_dirname);
        $this->_embed_class         = webphoto_embed::getInstance($dirname, $trust_dirname);
        $this->_mysql_utility_class = webphoto_lib_mysql_utility::getInstance();
        $this->_ini_class           = webphoto_inc_ini::getSingleton($dirname, $trust_dirname);

        $this->_ini_class->read_main_ini();

        $this->_cfg_gmap_apikey    = $this->_config_class->get_by_name('gmap_apikey');
        $this->_cfg_width          = $this->_config_class->get_by_name('width');
        $this->_cfg_height         = $this->_config_class->get_by_name('height');
        $this->_cfg_fsize          = $this->_config_class->get_by_name('fsize');
        $this->_cfg_makethumb      = $this->_config_class->get_by_name('makethumb');
        $this->_cfg_file_size      = $this->_config_class->get_by_name('file_size');
        $this->_cfg_perm_item_read = $this->_config_class->get_by_name('perm_item_read');
        $this->_cfg_perm_cat_read  = $this->_config_class->get_by_name('perm_cat_read');

        // cat_form.php
        $this->_cfg_cat_width    = $this->_config_class->get_by_name('cat_width');
        $this->_cfg_use_pathinfo = $this->_config_class->get_by_name('use_pathinfo');

        $this->_has_deletable = $this->_perm_class->has_deletable();

        $this->_UPLOADS_PATH = $this->_config_class->get_uploads_path();
        $this->_MEDIAS_PATH  = $this->_config_class->get_medias_path();
        $this->_WORK_DIR     = $this->_config_class->get_by_name('workdir');
        $this->_FILE_DIR     = $this->_config_class->get_by_name('file_dir');

        $this->_PHOTOS_PATH = $this->_UPLOADS_PATH . '/photos';
        $this->_THUMBS_PATH = $this->_UPLOADS_PATH . '/thumbs';
        $this->_CATS_PATH   = $this->_UPLOADS_PATH . '/categories';
        $playlists_path     = $this->_UPLOADS_PATH . '/playlists';

        $this->_PHOTOS_DIR    = XOOPS_ROOT_PATH . $this->_PHOTOS_PATH;
        $this->_THUMBS_DIR    = XOOPS_ROOT_PATH . $this->_THUMBS_PATH;
        $this->_CATS_DIR      = XOOPS_ROOT_PATH . $this->_CATS_PATH;
        $this->_MEDIAS_DIR    = XOOPS_ROOT_PATH . $this->_MEDIAS_PATH;
        $this->_PLAYLISTS_DIR = XOOPS_ROOT_PATH . $playlists_path;

        $this->_PHOTOS_URL    = XOOPS_URL . $this->_PHOTOS_PATH;
        $this->_THUMBS_URL    = XOOPS_URL . $this->_THUMBS_PATH;
        $this->_CATS_URL      = XOOPS_URL . $this->_CATS_PATH;
        $this->_MEDIAS_URL    = XOOPS_URL . $this->_MEDIAS_PATH;
        $this->_PLAYLISTS_URL = XOOPS_URL . $playlists_path;

        $this->_TMP_DIR  = $this->_WORK_DIR . '/tmp';
        $this->_MAIL_DIR = $this->_WORK_DIR . '/mail';
        $this->_LOG_DIR  = $this->_WORK_DIR . '/log';

        $this->_ICONS_URL       = $this->_MODULE_URL . '/images/icons';
        $this->_ICON_ROTATE_URL = $this->_MODULE_URL . '/images/uploader';
        $this->_ROOT_EXTS_URL   = $this->_MODULE_URL . '/images/exts';
        $this->_ROOT_EXTS_DIR   = $this->_MODULE_DIR . '/images/exts';
        $this->_LIBS_URL        = $this->_MODULE_URL . '/libs';

        $this->_is_japanese = $this->_xoops_class->is_japanese(_C_WEBPHOTO_JPAPANESE);
    }

    public static function getInstance($dirname = null, $trust_dirname = null)
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new webphoto_edit_form($dirname, $trust_dirname);
        }
        return $instance;
    }

    //---------------------------------------------------------
    // photo form
    // submit.php submit_file.php etc
    //---------------------------------------------------------
    public function set_default_item_row($item_row)
    {
        if (empty($item_row['item_editor'])) {
            $item_row['item_editor'] = $this->get_ini('item_editor_default');
        }
        return $item_row;
    }

    public function ele_maxpixel($has_resize)
    {
        $text = $this->_cfg_width . ' x ' . $this->_cfg_height . "<br />\n";
        if ($has_resize) {
            $text .= $this->get_constant('DSC_PIXCEL_RESIZE');
        } else {
            $text .= $this->get_constant('DSC_PIXCEL_REJECT');
        }
        return $text;
    }

    public function ele_maxsize()
    {
        $size_desc = '';
        if (!ini_get('file_uploads')) {
            $size_desc = ' &nbsp; <b>"file_uploads" off</b>';
        }

        $text = $this->format_filesize($this->_cfg_fsize);
        $text .= $size_desc;
        return $text;
    }

    public function ele_allowed_exts($allowed_exts)
    {
        $text = implode(' ', $allowed_exts);
        return $text;
    }

    public function item_cat_id_options()
    {
        return $this->build_cat_id_options($this->get_row_by_key('item_cat_id'), $this->_FLAG_PERM_ADMIN);
    }

    public function build_cat_id_options($cat_id, $flag_admin = false)
    {
        $show = true;
        if ($this->_cfg_perm_cat_read == _C_WEBPHOTO_OPT_PERM_READ_NO_CAT) {
            $show = false;
        }

        return $this->_cat_handler->build_options_with_perm_post($cat_id, $show, $flag_admin);
    }

    public function show_err_invalid_cat($is_edit)
    {
        if (!$is_edit) {
            return false;
        }
        $value = $this->get_row_by_key('item_cat_id');
        $count = $this->_cat_handler->get_count_by_id($value);
        return !(bool)$count;
    }

    public function item_description_dhtml()
    {
        $name  = 'item_description';
        $value = $this->get_row_by_key($name);
        return $this->build_form_dhtml($name, $value);
    }

    // for misc_form.php
    public function get_item_editor($flag)
    {
        $value = $this->get_row_by_key('item_editor');
        if ($flag && empty($value)) {
            $value = $this->get_ini('item_editor_default');
        }
        return $value;
    }

    public function get_item_embed_type($flag)
    {
        $value = $this->get_row_by_key('item_embed_type');
        if ($flag && empty($value)) {
            $value = $this->get_ini('submit_embed_type_default');
        }
        return $value;
    }

    //---------------------------------------------------------
    // file handler
    //---------------------------------------------------------
    public function exists_photo($item_row)
    {
        $file_id = $this->_item_handler->build_value_fileid_by_kind($item_row, _C_WEBPHOTO_FILE_KIND_CONT);
        if ($file_id > 0) {
            return $this->_file_handler->exists_full_path_by_id($file_id);
        }
        return false;
    }

    public function build_file_url_by_kind($item_row, $kind)
    {
        $file_row = $this->get_cached_file_extend_row_by_kind($item_row, $kind);
        if (is_array($file_row)) {
            return $this->build_file_url_by_file_row($file_row);
        }
        return null;
    }

    public function get_cached_file_extend_row_by_kind($item_row, $kind)
    {
        $file_id = $this->_item_handler->build_value_fileid_by_kind($item_row, $kind);
        if ($file_id > 0) {
            return $this->_file_handler->get_cached_extend_row_by_id($file_id);
        }
        return null;
    }

    public function build_file_url_by_file_row($file_row)
    {
        list($url, $width, $height) = $this->_file_handler->build_show_file_image($file_row);
        return $url;
    }

    //---------------------------------------------------------
    // embed class
    //---------------------------------------------------------
    public function item_embed_type_select_options()
    {
        $value   = $this->get_item_embed_type(true);
        $options = $this->_embed_class->build_type_options($this->_is_module_admin);
        return $this->build_form_options($value, $options);
    }

    //---------------------------------------------------------
    // build image
    //---------------------------------------------------------
    public function build_img_pictadd()
    {
        $str = '<img src="' . $this->_ICONS_URL . '/pictadd.png" width="18" height="15" border="0" alt="' . _WEBPHOTO_TITLE_ADDPHOTO . '" title="' . _WEBPHOTO_TITLE_ADDPHOTO . '" />' . "\n";
        return $str;
    }

    public function build_img_edit()
    {
        $str = '<img src="' . $this->_ICONS_URL . '/edit.png" width="18" height="15" border="0" alt="' . _WEBPHOTO_TITLE_EDIT . '" title="' . _WEBPHOTO_TITLE_EDIT . '" />' . "\n";
        return $str;
    }

    public function build_img_deadlink()
    {
        $str = '<img src="' . $this->_ICONS_URL . '/deadlink.png"  width="16" height="16" border="0" alt="' . _AM_WEBPHOTO_DEADLINKMAINPHOTO . '" title="' . _AM_WEBPHOTO_DEADLINKMAINPHOTO . '" />'
               . "\n";
        return $str;
    }

    public function build_img_pixel($width, $height)
    {
        $str = '<img src="' . $this->_ICONS_URL . '/pixel_trans.png" width="' . $width . '" height="' . $height . '" border="0" alt="" />';
        return $str;
    }

    public function build_img_catadd()
    {
        $str = '<img src="' . $this->_ICONS_URL . '/cat_add.png" width="18" height="15"  border="0" alt="' . _AM_WEBPHOTO_CAT_LINK_MAKESUBCAT . '" title="' . _AM_WEBPHOTO_CAT_LINK_MAKESUBCAT . '" />'
               . "\n";
        return $str;
    }

    public function build_img_catedit()
    {
        $str = '<img src="' . $this->_ICONS_URL . '/cat_edit.png" width="18" height="15"  border="0" alt="' . _AM_WEBPHOTO_CAT_LINK_EDIT . '" title="' . _AM_WEBPHOTO_CAT_LINK_EDIT . '" />' . "\n";
        return $str;
    }

    //---------------------------------------------------------
    // group perms
    //---------------------------------------------------------
    public function build_ele_group_perms_by_key($name)
    {
        $text = $this->build_group_perms_check_all_by_key($name);
        $text .= $this->get_constant('GROUP_PERM_ALL');
        $text .= "<br />\n";
        $text .= $this->build_group_perms_checkboxs_by_key($name);
        return $text;
    }

    public function build_group_perms_check_all_by_key($name)
    {
        $all_name = $name . '_all';
        $id_name  = $name . '_ids';
        return $this->build_input_checkbox_js_check_all($all_name, $id_name);
    }

    public function build_group_perms_param_by_key($name)
    {
        $id_name = $name . '_ids';
        $groups  = $this->get_cached_xoops_db_groups();
        $perms   = $this->get_group_perms_array_by_row_name($this->get_row(), $name);
        $all_yes = $this->get_all_yes_group_perms_by_key($name);
        $options = $this->build_options_group_perms($id_name, $groups, $perms, $all_yes);
        $ret1    = $this->build_form_checkboxs_yes($options);
        $ret2    = $this->build_list_select_multi($options);
        $ret3    = $this->build_form_hiddens_yes($options);
        return array($ret1, $ret2, $ret3);
    }

    public function build_group_perms_checkboxs_by_key($name, $flag_admin = false)
    {
        $id_name = $name . '_ids';
        $groups  = $this->get_cached_xoops_db_groups();
        $perms   = $this->get_group_perms_for_group_perms_checkboxs($name, $groups, $flag_admin);
        $all_yes = $this->get_all_yes_group_perms_by_key($name);
        return $this->build_form_checkboxs_group_perms($id_name, $groups, $perms, $all_yes);
    }

    public function build_group_perms_hiddens_by_key($name)
    {
        $id_name = $name . '_ids';
        $groups  = $this->get_cached_xoops_db_groups();
        $perms   = $this->get_group_perms_array_by_row_name($this->get_row(), $name);
        $all_yes = $this->get_all_yes_group_perms_by_key($name);
        return $this->build_form_hiddens_group_perms($id_name, $groups, $perms, $all_yes);
    }

    public function build_group_perms_list($name)
    {
        $id_name = $name . '_ids';
        $groups  = $this->get_cached_xoops_db_groups();
        $perms   = $this->get_group_perms_array_by_row_name($this->get_row(), $name);
        $all_yes = $this->get_all_yes_group_perms_by_key($name);
        $options = $this->build_options_group_perms($id_name, $groups, $perms, $all_yes);
        return $this->build_list_select_multi($options);
    }

    public function get_group_perms_for_group_perms_checkboxs($name, $groups, $flag_admin = false)
    {
        $arr1 = $this->get_group_perms_array_by_row_name($this->get_row(), $name);
        if (!$flag_admin) {
            return $arr1;
        }
        $arr2 = $this->get_group_perms_of_module_admin($groups);
        $arr3 = array_merge($arr1, $arr2);
        $arr4 = array_unique($arr3);
        return $arr4;
    }

    public function get_group_perms_of_module_admin($groups)
    {
        $arr = array();
        foreach ($groups as $id => $name) {
            if ($this->get_module_admin_value($id)) {
                $arr[] = $id;
            }
        }
        print_r($arr);
        return $arr;
    }

    public function get_module_admin_value($group_id)
    {
        $this->_groupperm_handler = xoops_getHandler('groupperm');
        $arr                      = $this->_groupperm_handler->getItemIds('module_admin', $group_id, 1);
        if (isset($arr[0])) {
            return $arr[0];
        }
        return false;
    }

    public function get_group_perms_array_by_row_name($row, $name)
    {
        if (isset($row[$name])) {
            return $this->get_group_perms_array($row[$name]);
        } else {
            return array();
        }
    }

    public function get_group_perms_array($val)
    {
        return $this->str_to_array($val, $this->_PERM_SEPARATOR);
    }

    public function build_list_select_multi($options, $del = '<br />')
    {
        if (!is_array($options) || !count($options)) {
            return null;
        }

        $str = '';
        foreach ($options as $opt) {
            list($name, $val, $cap) = $opt;
            $str .= $this->build_list_select_multi_span($val);
            $str .= $cap;
            $str .= '</span>';
            $str .= $del;
        }
        return $str;
    }

    public function build_list_select_multi_span($value)
    {
        if ($value == $this->_C_YES) {
            $color = 'menutext';
        } else {
            $color = 'graytext';
        }
        $str = '<span style="color:' . $color . ';">';
        return $str;
    }

    //---------------------------------------------------------
    // java script
    // admin/cat_form.php, mime_form.php
    //---------------------------------------------------------
    public function build_script_edit_js()
    {
        return $this->build_script_js_libs('edit.js');
    }

    public function build_script_js_libs($js)
    {
        return $this->build_script_js($this->_LIBS_URL . '/' . $js);
    }

    public function build_script_js($src)
    {
        $str = '<script src="' . $src . '" type="text/javascript"></script>' . "\n";
        return $str;
    }

    public function build_input_checkbox_js_check_all($name, $id_name)
    {
        $onclick = "webphoto_check_all(this, '" . $id_name . "')";
        $extra   = 'onclick="' . $onclick . '"';
        return $this->build_input_checkbox_yes($name, 0, $extra);
    }

    //---------------------------------------------------------
    // preload class
    //---------------------------------------------------------
    public function init_preload()
    {
        $this->preload_init();
        $this->preload_constant();
    }

    public function preload_init()
    {
        $this->_preload_class = webphoto_d3_preload::getInstance();
        $this->_preload_class->init($this->_DIRNAME, $this->_TRUST_DIRNAME);
    }

    public function preload_constant()
    {
        $arr = $this->_preload_class->get_preload_const_array();

        if (!is_array($arr) || !count($arr)) {
            return true;    // no action
        }

        foreach ($arr as $k => $v) {
            $local_name = strtoupper('_' . $k);

            // array type
            if (strpos($k, 'array_') === 0) {
                $temp = $this->str_to_array($v, '|');
                if (is_array($temp) && count($temp)) {
                    $this->$local_name = $temp;
                }

                // string type
            } else {
                $this->$local_name = $v;
            }
        }
    }

    //---------------------------------------------------------
    // config
    //---------------------------------------------------------
    public function get_config_by_name($name)
    {
        return $this->_config_class->get_by_name($name);
    }

    public function get_config_array()
    {
        return $this->_config_class->get_config_array();
    }

    //---------------------------------------------------------
    // xoops param
    //---------------------------------------------------------
    public function get_user_param($uid, $userstart)
    {
        list($show, $list) = $this->get_user_param_list();

        $options = $this->get_user_param_options($uid, $userstart);

        return array($show, $list, $options);
    }

    public function get_user_param_list()
    {
        $show = false;
        $list = array();

        $count = $this->get_xoops_user_count();
        if ($count <= $this->_USER_LIMIT) {
            return array($show, $list);
        }

        $show  = true;
        $limit = (int)($count - 1);

        for ($i = 0; $i <= $limit; ++$i) {
            $list[] = array(
                'page'      => $i + 1,
                'userstart' => $i * $this->_USER_LIMIT,
            );
        }

        return array($show, $list);
    }

    public function get_user_param_options($uid, $userstart)
    {
        $list = $this->get_user_list($uid, $userstart);
        return $this->build_form_user_select_options($list, $uid);
    }

    public function build_form_user_select($list, $sel_name, $sel_value, $none = false)
    {
        $text = '<select name="' . $sel_name . '">';
        $text .= $this->build_form_user_select_options($list, $sel_value, $none);
        $text .= "</select>\n";
        return $text;
    }

    public function build_form_user_select_options($list, $sel_value, $none = false)
    {
        $opt = '';

        if ($none) {
            $opt .= '<option value="0">';
            $opt .= _AM_WEBPHOTO_OPT_NOCHANGE;
            $opt .= "</option>\n";
        }

        foreach ($list as $uid => $uname_s) {
            $selected = $this->build_form_selected($uid, $sel_value);
            $opt .= '<option value="' . $uid . '" ' . $selected . ' >';
            $opt .= $uname_s;
            $opt .= "</option>\n";
        }

        return $opt;
    }

    public function get_user_list($uid, $userstart)
    {
        $list = $this->get_xoops_user_list($this->_USER_LIMIT, $userstart);
        if (empty($uid) || isset($list[$uid])) {
            return $list;
        }

        // add user, if user NOT exists in list
        $name = $this->get_xoops_user_name($uid);
        if ($name) {
            $list[$uid] = $name;
        }
        return $list;
    }

    public function get_xoops_user_count()
    {
        return $this->_xoops_class->get_member_user_count();
    }

    //---------------------------------------------------------
    // xoops class
    //---------------------------------------------------------
    public function xoops_module_name($format = 's')
    {
        return $this->_xoops_class->get_my_module_name($format);
    }

    //---------------------------------------------------------
    // is show
    //---------------------------------------------------------
    public function is_show_form_admin($item_row)
    {
        if (!$this->is_show_form_photomanager($item_row)) {
            return false;
        }
        if ($this->is_show_form_embed() && $this->is_show_form_playlist()) {
            return true;
        }
        return false;
    }

    public function is_show_form_photomanager($item_row)
    {
        // from photomanager
        if ($item_row['item_cat_id'] > 0) {
            return false;
        }
        return true;
    }

    public function is_show_form_editor_option($options)
    {
        if (!is_array($options) || !count($options)) {
            return false;
        }
        return $this->is_show_form_editor();
    }

    public function is_show_form_editor()
    {
        if (!$this->get_ini('submit_show_form_editor')) {
            return false;
        }
        return $this->_is_show_form($this->_post_form_editor);
    }

    public function is_show_form_embed()
    {
        if (!$this->get_ini('submit_show_form_embed')) {
            return false;
        }
        return $this->_is_show_form($this->_post_form_embed);
    }

    public function is_show_form_playlist()
    {
        return $this->_is_show_form($this->_post_form_playlist);
    }

    public function _is_show_form($val)
    {
        $ret = $val ? false : true;
        return $ret;
    }

    //---------------------------------------------------------
    // common
    //---------------------------------------------------------
    public function build_form_base_param()
    {
        $arr = array_merge($this->build_base_param(), $this->build_system_language(), $this->get_lang_array());

        $arr['action'] = $this->_FORM_ACTION;
        $arr['fct']    = $this->_THIS_FCT;

        return $arr;
    }

    public function build_base_param()
    {
        $arr = array(
            'mydirname'        => $this->_DIRNAME,
            'xoops_g_ticket'   => $this->get_token(),
            'is_module_admin'  => $this->_is_module_admin,
            'has_deletable'    => $this->_has_deletable,

            // for XOOPS 2.0.18
            'xoops_dirname'    => $this->_DIRNAME,
            'xoops_modulename' => $this->xoops_module_name('s'),
        );

        // config
        $config_array = $this->get_config_array();
        foreach ($config_array as $k => $v) {
            $arr['cfg_' . $k] = $v;
        }

        return $arr;
    }

    public function build_system_language()
    {
        $arr = array(
            'lang_add'     => _ADD,
            'lang_edit'    => _EDIT,
            'lang_preview' => _PREVIEW,
            'lang_cancel'  => _CANCEL,
            'lang_delete'  => _DELETE,
            'lang_close'   => _CLOSE,
            'lang_yes'     => _YES,
            'lang_no'      => _NO,
        );
        return $arr;
    }

    public function build_item_row($row)
    {
        $arr = array();
        foreach ($row as $k => $v) {
            $arr[$k]        = $v;
            $arr[$k . '_s'] = $this->sanitize($v);
        }
        return $arr;
    }

    public function set_fct($val)
    {
        $this->_THIS_FCT = $val;
    }

    public function set_form_action($flag_admin)
    {
        if ($flag_admin) {
            $this->_FORM_ACTION = $this->_MODULE_URL . '/admin/index.php';
        } else {
            $this->_FORM_ACTION = $this->_MODULE_URL . '/index.php';
        }
    }

    //---------------------------------------------------------
    // common param
    //---------------------------------------------------------
    public function get_post_select_param()
    {
        $this->_post_form_editor   = $this->get_post_int('form_editor');
        $this->_post_form_embed    = $this->get_post_int('form_embed');
        $this->_post_form_playlist = $this->get_post_int('form_playlist');
    }

    public function build_form_select_param($mode = null)
    {
        $arr = array(
            'mode'          => $this->get_form_mode_default($mode),
            'form_editor'   => $this->_post_form_editor,
            'form_embed'    => $this->_post_form_embed,
            'form_playlist' => $this->_post_form_playlist,
        );
        return $arr;
    }

    public function build_form_mode_param($mode)
    {
        $arr = array(
            'mode' => $this->get_form_mode_default($mode),
        );
        return $arr;
    }

    public function get_form_mode_default($mode)
    {
        if (empty($mode)) {
            $mode = $this->_FORM_MODE;
        }
        return $mode;
    }

    public function set_form_mode($val)
    {
        $this->_FORM_MODE = $val;
    }

    //---------------------------------------------------------
    // checkbox
    //---------------------------------------------------------
    public function set_checkbox_array($val)
    {
        $this->_checkbox_array = $val;
    }

    public function get_checkbox_by_name($name)
    {
        if (isset($this->_checkbox_array[$name])) {
            return $this->_checkbox_array[$name];
        }
        return null;
    }

    public function build_checkbox_checked($name, $compare = 1)
    {
        $val = $this->get_checkbox_by_name($name);
        return $this->build_form_checked($val, $compare);
    }

    //---------------------------------------------------------
    // ini class
    //---------------------------------------------------------
    public function get_ini($name)
    {
        return $this->_ini_class->get_ini($name);
    }

    public function explode_ini($name)
    {
        return $this->_ini_class->explode_ini($name);
    }

    //---------------------------------------------------------
    // mysql
    //---------------------------------------------------------
    public function get_mysql_date_today()
    {
        return $this->_mysql_utility_class->get_mysql_date_today();
    }

    public function mysql_datetime_to_str($date)
    {
        return $this->_mysql_utility_class->mysql_datetime_to_str($date);
    }

    // --- class end ---
}
