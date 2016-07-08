<?php
// $Id: factory.php,v 1.10 2011/12/28 16:16:15 ohwada Exp $

//=========================================================
// webphoto module
// 2010-01-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-12-25 K.OHWADA
// show_check_timeline()
// 2010-11-03 K.OHWADA
// input_to_param()
// 2010-05-10 K.OHWADA
// calc_navi_start()
// BUG: total is wrong
// 2010-02-20 K.OHWADA
// build_execution_time()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_factory
//=========================================================
class webphoto_factory extends webphoto_base_this
{
    public $_photo_class;
    public $_category_class;
    public $_header_class;
    public $_page_class;
    public $_gmap_class;
    public $_qr_class;
    public $_sort_class;
    public $_public_class;
    public $_pagenavi_class;
    public $_timeline_class;
    public $_auto_publish_class;
    public $_tagcloud_class;
    public $_uri_parse_class;

    // config
    public $_cfg_cat_summary;
    public $_cfg_newphotos;
    public $_cfg_tags;

    // GET param
    public $_get_page  = null;
    public $_get_sort  = null; // allow empty
    public $_get_kind  = null; // allow empty
    public $_param     = null;
    public $_param_out = null;
    public $_sort      = null; // set default in empty
    public $_orderby   = null;
    public $_start     = null;
    public $_page      = null; // adjusted
    public $_mode      = null;
    public $_mode_orig = null;
    public $_sub_mode  = null;
    public $_sub_param = null;

    public $_cat_id = 0;

    public $_xoops_header_array = array();
    public $_show_array         = array();
    public $_templete_param     = array();
    public $_template_main      = null;

    public $_PHOTO_LIMIT;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_photo_class               = webphoto_photo::getInstance($dirname, $trust_dirname);
        $this->_category_class            = webphoto_category::getInstance($dirname, $trust_dirname);
        $this->_header_class              = webphoto_xoops_header::getInstance($dirname, $trust_dirname);
        $this->_page_class                = webphoto_page::getInstance($dirname, $trust_dirname);
        $this->_gmap_class                = webphoto_gmap::getInstance($dirname, $trust_dirname);
        $this->_qr_class                  = webphoto_qr::getInstance($dirname, $trust_dirname);
        $this->_public_class              = webphoto_photo_public::getInstance($dirname, $trust_dirname);
        $this->_pagenavi_class            = webphoto_pagenavi::getInstance($dirname, $trust_dirname);
        $this->_timeline_class            = webphoto_timeline::getInstance($dirname, $trust_dirname);
        $this->_notification_select_class = webphoto_notification_select::getInstance($dirname);
        $this->_sort_class                = webphoto_photo_sort::getInstance($dirname, $trust_dirname);
        $this->_tagcloud_class            = webphoto_inc_tagcloud::getSingleton($dirname, $trust_dirname);
        $this->_uri_parse_class           = webphoto_uri_parse::getInstance($dirname, $trust_dirname);

        $this->_auto_publish_class = webphoto_inc_auto_publish::getSingleton($dirname, $trust_dirname);
        $this->_auto_publish_class->set_workdir($this->_WORK_DIR);

        $this->_cfg_cat_summary = $this->_config_class->get_by_name('cat_summary');
        $this->_cfg_newphotos   = $this->_config_class->get_by_name('newphotos');
        $this->_cfg_tags        = $this->_config_class->get_by_name('tags');

        $this->_PHOTO_LIMIT = $this->_cfg_newphotos;
    }

    public static function getInstance($dirname = null, $trust_dirname = null)
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new webphoto_factory($dirname, $trust_dirname);
        }
        return $instance;
    }

    //---------------------------------------------------------
    // init
    //---------------------------------------------------------
    public function init_factory()
    {
        $this->_auto_publish_class->auto_publish();

        $this->get_page_mode();
        $this->get_pathinfo_param($this->_mode_orig);
        $this->get_get_param();
        $this->set_cat_id($this->_mode, $this->_param);

        $this->init_preload();
    }

    public function init_preload()
    {
        $this->preload_init();
        $this->preload_error($this->get_ini('debug_preload'));
        $this->preload_constant();
    }

    //---------------------------------------------------------
    // mode
    //---------------------------------------------------------
    public function set_mode($val)
    {
        $this->_mode = $val;
    }

    public function set_mode_orig($val)
    {
        $this->_mode_orig = $val;
    }

    //---------------------------------------------------------
    // get pathinfo param
    //---------------------------------------------------------
    public function get_pathinfo_param($mode_orig)
    {
        $this->_get_page = $this->_uri_parse_class->get_get_page();
        $this->_get_kind = $this->_uri_parse_class->get_get_kind();

        $params          = $this->_uri_parse_class->get_sort_orderby();
        $this->_get_sort = $params['get_sort'];
        $this->_sort     = $params['sort'];
        $this->_orderby  = $params['orderby'];

        $this->_param = $this->_uri_parse_class->get_param_by_mode($mode_orig);
        $this->set_param_out($this->_param);
    }

    public function get_get_param()
    {
        $sm = $this->_post_class->get_get_text('sm');
        $sp = $this->_post_class->get_get_text('sp');
        $this->set_sub_mode_param($sm, $sp);
    }

    public function set_param_out($val)
    {
        $this->_param_out = $val;
    }

    public function get_page_mode()
    {
        list($mode, $mode_orig) = $this->_uri_parse_class->get_page_mode();

        $this->set_mode($mode);
        $this->set_mode_orig($mode_orig);
        return $mode;
    }

    public function set_cat_id($mode, $param)
    {
        switch ($mode) {
            case 'category':
                $this->_cat_id = (int)$param;
                break;

            default:
                break;
        }
    }

    public function set_sub_mode_param($mode, $param)
    {
        $this->_sub_mode  = $mode;
        $this->_sub_param = $param;
    }

    //---------------------------------------------------------
    // xoops header
    //---------------------------------------------------------
    public function xoops_header_array_set_by_mode($mode)
    {
        $this->xoops_header_array_set_by_ini($this->get_header_ini($mode));
    }

    public function get_header_ini($mode)
    {
        $name1 = $mode . '_header_list';
        $name2 = 'main_header_list';

        if ($this->isset_ini($name1)) {
            return $name1;
        }

        return $name2;
    }

    public function xoops_header_array_set_by_ini($name)
    {
        $this->xoops_header_array_set($this->explode_ini($name));
    }

    public function xoops_header_array_set($val)
    {
        $this->_xoops_header_array = $val;
    }

    public function xoops_header_check($key)
    {
        if (in_array($key, $this->_xoops_header_array)) {
            return true;
        }
        return false;
    }

    public function xoops_header_param()
    {
        if ($this->xoops_header_check('css')) {
            $this->_header_class->set_flag_css(true);
        }
        if ($this->xoops_header_check('box')) {
            $this->_header_class->set_flag_box(true);
        }
        if ($this->xoops_header_check('popbox')) {
            $this->_header_class->set_flag_popbox(true);
        }
        if ($this->xoops_header_check('timeline')) {
            $this->_header_class->set_flag_timeline(true);
        }
    }

    public function xoops_header_gmap_with_check($flag)
    {
        if ($this->xoops_header_check('gmap')) {
            $this->_header_class->set_flag_gmap($flag);
        }
    }

    public function xoops_header_lightbox_with_check($flag)
    {
        if ($this->xoops_header_check('lightbox')) {
            $this->_header_class->set_flag_lightbox($flag);
        }
    }

    public function xoops_header_rss_with_check($mode, $param)
    {
        if ($this->xoops_header_check('rss')) {
            $flag         = true;
            $limit        = $this->get_ini('view_rss_limit');
            $param_encode = urlencode($this->_multibyte_class->convert_to_utf8($param));

            $this->_header_class->set_rss($flag, $mode, $param_encode, $limit);

            $this->tpl_set('show_rss_icon', $flag);
            $this->tpl_set('rss_mode', $mode);
            $this->tpl_set('rss_param', $param_encode);
            $this->tpl_set('rss_limit', $limit);
        }
    }

    public function xoops_header_assign()
    {
        $this->_header_class->assign_for_main();
    }

    //---------------------------------------------------------
    // template
    //---------------------------------------------------------
    public function set_template_main_by_mode($mode)
    {
        $this->set_template_main($this->get_template_name_by_mode($mode));
    }

    public function set_template_main($val)
    {
        $this->_template_main = $val;
    }

    public function get_template_main()
    {
        return $this->build_template_main();
    }

    public function build_template_main()
    {
        $str = $this->_DIRNAME . '_' . $this->_template_main;
        return $str;
    }

    public function get_template_name_by_mode($mode)
    {
        $name1 = 'main_' . $mode . '.html';
        $name2 = 'main_index.html';

        $file = $this->_TRUST_DIR . '/templates/' . $name1;
        if (file_exists($file)) {
            return $name1;
        }
        return $name2;
    }

    //---------------------------------------------------------
    // templete param
    //---------------------------------------------------------
    public function show_array_set_list_by_mode($mode)
    {
        $this->show_array_set_by_ini($this->get_show_list_ini($mode));
    }

    public function show_array_set_detail_by_mode($mode)
    {
        $this->show_array_set_by_ini($this->get_show_detail_ini($mode));
    }

    public function get_show_list_ini($mode)
    {
        $name1 = $mode . '_show_list_list';
        $name2 = 'main_show_list';

        if ($this->isset_ini($name1)) {
            return $name1;
        }

        return $name2;
    }

    public function get_show_detail_ini($mode)
    {
        $name1 = $mode . '_show_detail_list';
        $name2 = $mode . '_show_list';
        $name3 = 'main_show_list';

        if ($this->isset_ini($name1)) {
            return $name1;
        }
        if ($this->isset_ini($name2)) {
            return $name2;
        }
        return $name3;
    }

    public function get_show_array($mode)
    {
        $name = $mode . '_show_detail_list';

        $arr = $this->explode_ini($name);
        if (is_array($arr) && count($arr)) {
            return $arr;
        }

        $name  = 'main_show_list';
        $name1 = $mode . '_show_list';
        $name2 = $mode . '_show_add_list';
        $name3 = $mode . '_show_remove_list';

        return $this->build_array($name, $name1, $name2, $name3);
    }

    public function show_array_set_by_ini($name)
    {
        $this->show_array_set($this->explode_ini($name));
    }

    public function show_array_set($val)
    {
        $this->_show_array = $val;
    }

    public function show_check($key)
    {
        if (in_array($key, $this->_show_array)) {
            return true;
        }
        return false;
    }

    public function show_param()
    {
        $this->show_param_common();
        $this->show_param_with_check();
    }

    public function show_param_common()
    {
        $this->tpl_set('show_photo_edit_link', true);
        $this->tpl_set('show_photo_title_link', true);
        $this->tpl_set('show_photo_uid_link', true);
        $this->tpl_set('show_photo_rate_link', true);
        $this->tpl_set('show_photo_taf_link', true);

        if ($this->get_ini('show_powered')) {
            $this->tpl_set('show_powered', true);
        }
        if ($this->get_ini('show_photo_user_icon')) {
            $this->tpl_set('show_photo_user_icon', true);
        }
        if ($this->get_ini('show_photo_category_link')) {
            $this->tpl_set('show_photo_category_link', true);
        }
        if ($this->get_ini('show_photo_equipment_link')) {
            $this->tpl_set('show_photo_equipment_link', true);
        }
        if ($this->get_ini('show_photo_tag_link')) {
            $this->tpl_set('show_photo_tag_link', true);
        }

        if ($this->get_ini('show_photo_date_link')) {
            $this->tpl_set('show_photo_date_link', true);
        }
        if ($this->get_ini('show_photo_place_link')) {
            $this->tpl_set('show_photo_place_link', true);
        }
    }

    public function show_param_with_check()
    {
        if ($this->show_check('title')) {
            $this->tpl_set('show_title', true);
        }
        if ($this->show_check('menu')) {
            $this->tpl_set('show_menu', true);
        }
        if ($this->show_check('search')) {
            $this->tpl_set('show_search', true);
        }
        if ($this->show_check('comment')) {
            $this->tpl_set('show_comment', true);
        }
        if ($this->show_check('index_desc')) {
            $this->tpl_set('show_index_desc', true);
        }
        if ($this->show_check('random_more')) {
            $this->tpl_set('show_random_more', true);
        }
        if ($this->show_check('photo_desc')) {
            $this->tpl_set('show_photo_desc', true);
        }
        if ($this->show_check('photo_summary')) {
            $this->tpl_set('show_photo_summary', true);
        }
        if ($this->show_check('photo_misc')) {
            $this->tpl_set('show_photo_misc', true);
        }
        if ($this->show_check('photo_exif')) {
            $this->tpl_set('show_photo_exif', true);
        }
        if ($this->show_check('photo_content')) {
            $this->tpl_set('show_photo_content', true);
        }
        if ($this->show_check('box_js')) {
            $this->tpl_set('use_box_js', true);
        }
        if ($this->show_check('popbox_js')) {
            $this->tpl_set('use_popbox_js', true);
        }
        if ($this->show_check('lightbox_js')) {
            $this->tpl_set('use_lightbox_js', true);
        }
    }

    public function set_tpl_show_page_detail($val)
    {
        $this->tpl_set('show_page_detail', $val);
    }

    public function set_tpl_common()
    {
        $this->set_tpl_get_param();
        $this->set_tpl_is_taf_module();
        $this->set_tpl_photo_total_all();

        $this->tpl_merge($this->_page_class->build_main_param());
        $this->tpl_merge($this->_page_class->build_qrs_param());
    }

    public function set_tpl_get_param()
    {
        $this->tpl_set('page', $this->_get_page);
        $this->tpl_set('sort', $this->_get_sort);
        $this->tpl_set('kind', $this->_get_kind);

        $this->tpl_set('lang_cursortedby', $this->_sort_class->get_lang_sortby($this->_sort));
    }

    public function set_tpl_is_taf_module()
    {
        $this->tpl_set('is_taf_module', $this->_page_class->get_is_taf_module());
    }

    public function set_tpl_photo_total_all()
    {
        $total = $this->_public_class->get_count();
        $lang  = sprintf($this->get_constant('S_THEREARE'), $total);

        $this->tpl_set('photo_total_all', $total);
        $this->tpl_set('lang_thereare', $lang);
    }

    public function set_tpl_mode($mode)
    {
        $this->tpl_set('mode', $mode);
        $this->tpl_set('param_sort', $this->_uri_class->build_param_sort($mode, $this->_param_out, $this->_get_kind));

        $this->tpl_set('sub_mode', $this->_sub_mode);
        $this->tpl_set('sub_param', $this->_sub_param);

        if ($mode == 'random') {
            $this->tpl_set('show_random_more', true);
        }
    }

    public function set_tpl_title_for_list($mode)
    {
        $const = 'title_' . $mode . '_list';
        $title = $this->sanitize($this->get_constant($const));

        $this->set_tpl_title($title);
    }

    public function set_tpl_title($title)
    {
        $pagetitle = $title . ' - ' . $this->sanitize($this->_MODULE_NAME);

        $this->tpl_set('xoops_pagetitle', $pagetitle);
        $this->tpl_set('title_bread_crumb', $title);
        $this->tpl_set('sub_title_s', $title);
    }

    public function set_tpl_photo_list($val)
    {
        $this->tpl_set('photo_list', $val);
    }

    public function set_tpl_cat_param($param)
    {
        if (isset($param['photo_total_sum'])) {
            $this->tpl_set('photo_total_sum', $param['photo_total_sum']);
        }
        if (isset($param['photo_small_sum'])) {
            $this->tpl_set('photo_small_sum', $param['photo_small_sum']);
        }
    }

    public function set_tpl_category_photo_list($val)
    {
        $this->tpl_set('category_photo_list', $val);
    }

    public function set_tpl_cat_id($cat_id)
    {
        $this->tpl_set('cat_id', $cat_id);
    }

    public function set_tpl_photo_nav($photo_id, $cat_id)
    {
        $this->tpl_set('photo_nav', $this->_photo_class->build_photo_navi($photo_id, $cat_id));
    }

    public function set_tpl_photo_tags($photo_id)
    {
        $this->tpl_merge($this->_photo_class->build_photo_tags_param($photo_id));
    }

    public function set_tpl_catpath_with_check($cat_id)
    {
        if ($this->show_check('catpth')) {
            $catpath = $this->_category_class->build_catpath($cat_id);
            $this->tpl_set('catpath', $catpath);
            $this->tpl_set('show_catpath', true);
        }
    }

    public function set_tpl_catlist_with_check($cat_id)
    {
        if ($this->show_check('catlist')) {
            if ($cat_id > 0) {
                $cols    = $this->get_ini('view_cat_catlist_cols');
                $delmita = $this->get_ini('view_cat_catlist_delmita');
            } else {
                $cols    = $this->get_ini('view_top_catlist_cols');
                $delmita = $this->get_ini('view_top_catlist_delmita');
            }

            $param = $this->_category_class->build_catlist($cat_id, $cols, $delmita);
            $this->tpl_merge($param);
        }
    }

    public function set_tpl_qr_with_check($photo_id)
    {
        if ($this->show_check('qr')) {
            $this->_qr_class->create_mobile_qr($photo_id);
            $this->tpl_merge($this->_qr_class->build_mobile_param($photo_id));
            $this->tpl_set('show_qr', true);
        }
    }

    public function set_tpl_tagcloud_with_check($limit)
    {
        if ($this->show_check('tagcloud')) {
            $tagcloud = $this->_tagcloud_class->build_tagcloud($limit);
            if (is_array($tagcloud) && count($tagcloud)) {
                $this->tpl_set('show_tagcloud', true);
                $this->tpl_set('tagcloud', $tagcloud);
            }
        }
    }

    public function set_tpl_notification_select_with_check($cat_id = 0)
    {
        if ($this->show_check('notification')) {
            $param = $this->_notification_select_class->build_notification_select($cat_id);
            if (is_array($param) && count($param)) {
                $this->tpl_set('show_notification_select', true);
                $this->tpl_set('notification_select', $param);
            }
        }
    }

    public function set_tpl_total_for_detail($mode, $total)
    {
        $this->tpl_set('total_bread_crumb', $total);
        $this->tpl_set('photo_total', $total);

        if ($total > 0) {
            $this->tpl_set('show_photo_list', true);
        } else {
            $this->tpl_set('show_nomatch', true);
        }

        if ($this->show_check('sort')) {
            if ($total > 1) {
                $this->tpl_set('show_sort', true);
            }
        }

        if ($this->show_check('navi')) {
            if ($total > $this->_PHOTO_LIMIT) {
                $this->tpl_merge($this->build_navi_param($mode, $total));
                $this->tpl_set('show_navi', true);
            }
        }
    }

    public function set_tpl_photo_for_detail($row)
    {
        $this->tpl_set('photo', $this->build_photo_for_photo($row));
        $this->tpl_set('show_photo', true);

        return $this->show_ligthtbox($row);
    }

    public function set_tpl_error($val)
    {
        $this->tpl_set('error', $val);
    }

    public function set_tpl_show_js_windows()
    {
        $this->tpl_merge($this->_page_class->build_show_js_windows($this->tpl_get()));
    }

    public function tpl_set($key, $val)
    {
        $this->_templete_param[$key] = $val;
    }

    public function tpl_merge($arr)
    {
        if (is_array($arr)) {
            $this->_templete_param = array_merge($this->_templete_param, $arr);
        } else {
            //      echo 'system error :'. $arr;
            //      debug_print_backtrace();
        }
    }

    public function tpl_get()
    {
        return $this->_templete_param;
    }

    //---------------------------------------------------------
    // map timeline
    //---------------------------------------------------------
    public function set_tpl_gmap_for_list_with_check($rows)
    {
        $gmap_large = $this->show_check('gmap_large');
        if ($this->show_check('gmap') || $gmap_large) {
            $param = $this->_gmap_class->build_for_list($rows, $gmap_large);
            $this->tpl_merge($param);
            return $param['show_gmap'];
        }
        return false;
    }

    public function set_tpl_gmap_for_detail_with_check($param)
    {
        $gmap_large = $this->show_check('gmap_large');
        if ($this->show_check('gmap') || $gmap_large) {
            $param_tpl = $this->_gmap_class->build_for_detail($param, $gmap_large);
            $this->tpl_merge($param_tpl);
            return $param_tpl['show_gmap'];
        }
        return false;
    }

    public function set_tpl_gmap_for_photo_with_check($row)
    {
        if ($this->show_check('gmap')) {
            $param = $this->_gmap_class->build_for_photo($row);
            $this->tpl_merge($param);
            return $param['show_gmap'];
        }
        return false;
    }

    public function show_check_timeline($mode, $cat_id)
    {
        switch ($mode) {
            case 'category':
                $ret = $this->show_check_timeline_category($cat_id);
                if (!$ret) {
                    return false;
                }
                break;
        }

        if ($this->show_check('timeline')) {
            return true;
        }
        if ($this->show_check('timeline_large')) {
            return true;
        }
        return false;
    }

    public function show_check_timeline_category($cat_id)
    {
        $scale = $this->_cat_handler->get_cached_timeline_scale_by_id($cat_id);
        if ($scale == _C_WEBPHOTO_TIMELINE_SCALE_NONE) {
            return false;
        }
        return true;
    }

    public function set_tpl_timeline_with_check($param)
    {
        if (!$this->show_check_timeline($param['mode'], $param['cat_id'])) {
            return;
        }

        $param_tpl = $this->_timeline_class->build_for_detail($param, $this->show_check('timeline_large'));

        $this->tpl_merge($param_tpl);
    }

    //---------------------------------------------------------
    // build component
    //---------------------------------------------------------
    public function category_build_rows_for_detail($cat_id)
    {
        $this->set_sub_mode_param('category', $cat_id);

        $cat_param = $this->_category_class->build_total_for_detail($cat_id);

        $title = $this->sanitize($cat_param['cat_title']);
        $total = $cat_param['photo_total'];

        // BUG: total is wrong
        $total_sum = $cat_param['photo_total_sum'];
        $small_sum = $cat_param['photo_small_sum'];
        $sum_mode  = $cat_param['sum_mode'];

        $rows  = null;
        $start = $this->calc_navi_start($total);

        if ($total > 0) {
            $rows = $this->_category_class->build_rows_for_detail($sum_mode, $cat_id, $this->_orderby, $this->_PHOTO_LIMIT, $start);
        }

        return array($title, $total, $rows, $cat_param);
    }

    public function calc_navi_start($total)
    {
        $this->_page = $this->_pagenavi_class->calc_navi_page($this->_get_page, $this->_PHOTO_LIMIT, $total);
        return $this->_pagenavi_class->calc_navi_start($this->_page, $this->_PHOTO_LIMIT);
    }

    public function build_navi_param($mode, $total)
    {
        return $this->_pagenavi_class->build_navi($mode, $total, $this->_param_out, $this->_get_sort, $this->_get_kind, $this->_page, $this->_PHOTO_LIMIT);
    }

    //---------------------------------------------------------
    // show
    //---------------------------------------------------------
    public function build_photo_list_for_detail($rows)
    {
        if (!is_array($rows) || !count($rows)) {
            return false;
        }

        $arr = array();
        foreach ($rows as $row) {
            $arr[] = $this->build_photo_by_row($row);
        }
        return $arr;
    }

    public function build_photo_by_row($row)
    {
        return $this->_photo_class->build_photo_by_row($row);
    }

    public function build_photo_for_photo($row)
    {
        return $this->_photo_class->build_photo_for_photo($row);
    }

    public function show_ligthtbox($row)
    {
        $show = false;
        if (($row['item_displaytype'] == _C_WEBPHOTO_DISPLAYTYPE_IMAGE)
            && ($row['item_detail_onclick'] == _C_WEBPHOTO_DETAIL_ONCLICK_LIGHTBOX)
        ) {
            $show = true;
        }
        return $show;
    }

    public function build_execution_time()
    {
        $arr = array(
            'execution_time' => $this->_page_class->get_execution_time(),
            'memory_usage'   => $this->_page_class->get_memory_usage(),
        );
        return $arr;
    }

    // --- class end ---
}
