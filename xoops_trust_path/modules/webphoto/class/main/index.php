<?php
// $Id: index.php,v 1.22 2011/12/28 16:16:15 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-12-25 K.OHWADA
// map_timeline_build_rows_for_detail()
// 2010-11-03 K.OHWADA
// BUG: not show rss param
// 2010-05-10 K.OHWADA
// main_build_rows_for_detail()
// BUG: total is wrong
// 2010-02-15 K.OHWADA
// build_execution_time()
// 2010-01-10 K.OHWADA
// webphoto_show_list -> webphoto_factory
// 2009-11-11 K.OHWADA
// get_ini()
// 2009-10-25 K.OHWADA
// webphoto_show_list
// 2009-09-25 K.OHWADA
// Notice [PHP]: Undefined variable: main_rows
// 2009-05-30 K.OHWADA
// random_more_url_s -> show_random_more
// 2009-04-10 K.OHWADA
// build_main_param()
// 2009-03-15 K.OHWADA
// add_box_list() -> add_show_js_windows()
// 2008-12-12 K.OHWADA
// public_class
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
// QR code
// 2008-07-01 K.OHWADA
// build_navi() -> build_main_navi()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_main_index
//=========================================================

/**
 * Class webphoto_main_index
 */
class webphoto_main_index extends webphoto_factory
{
    public $_main_class;
    public $_date_class;
    public $_place_class;
    public $_tag_class;
    public $_user_class;
    public $_search_class;

    public $_ini_tagcloud_list_limit;

    public $_ini_view_mode_default;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_main_index constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_main_class = webphoto_main::getInstance($dirname, $trust_dirname);

        $this->_date_class = webphoto_date::getInstance($dirname, $trust_dirname);

        $this->_place_class = webphoto_place::getInstance($dirname, $trust_dirname);

        $this->_tag_class = webphoto_tag::getInstance($dirname, $trust_dirname);

        $this->_user_class = webphoto_user::getInstance($dirname, $trust_dirname);

        $this->_search_class = webphoto_search::getInstance($dirname, $trust_dirname);

        $this->set_template_main('main_index.tpl');

        $this->_ini_tagcloud_list_limit = $this->get_ini('tagcloud_list_limit');

        $this->_ini_view_mode_default = $this->get_ini('view_mode_default');
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_factory|\webphoto_lib_error|\webphoto_main_index
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
    // init
    //---------------------------------------------------------
    public function init()
    {
        $this->init_factory();
        $this->set_template_main_by_mode($this->_mode);
        $this->init_preload();
    }

    //---------------------------------------------------------
    // main
    //---------------------------------------------------------

    /**
     * @return array
     */
    public function main()
    {
        switch ($this->_mode) {
            case 'category':
            case 'date':
            case 'place':
            case 'tag':
            case 'user':
                $ret = $this->page_main();
                break;
            case 'search':
            default:
                $ret = $this->build_page_detail($this->_mode, $this->_param);
                break;
        }
        $arr = array_merge($ret, $this->build_execution_time());

        return $arr;
    }

    /**
     * @return array
     */
    public function page_main()
    {
        if ($this->page_sel()) {
            return $this->build_page_detail($this->_mode, $this->_param);
        }

        return $this->build_page_list($this->_mode);
    }

    /**
     * @return bool
     */
    public function page_sel()
    {
        switch ($this->_mode) {
            case 'user':
                $ret = $this->_user_class->page_sel($this->_param);
                break;
            default:
                $ret = $this->page_sel_default();
                break;
        }

        return $ret;
    }

    /**
     * @return bool
     */
    public function page_sel_default()
    {
        if ($this->_param) {
            return true;
        }

        return false;
    }

    //---------------------------------------------------------
    // page list
    //---------------------------------------------------------

    /**
     * @param $mode
     * @return array
     */
    public function build_page_list($mode)
    {
        $this->show_array_set_list_by_mode($mode);

        list($photo_list, $photo_rows, $category_photo_list, $error) = $this->build_photo_list_for_list($mode);

        $gmap_rows = $this->_gmap_class->build_rows_for_detail_main();
        $gmap_rows = $this->_gmap_class->array_merge_unique($photo_rows, $gmap_rows);
        $show_gmap = $this->set_tpl_gmap_for_list_with_check($gmap_rows);

        $this->xoops_header_array_set_by_mode($mode);
        $this->xoops_header_param();
        $this->xoops_header_rss_with_check($this->_ini_view_mode_default, null);
        $this->xoops_header_gmap_with_check($show_gmap);
        $this->xoops_header_assign();

        $this->show_param();
        $this->set_tpl_common();
        $this->set_tpl_mode($mode);
        $this->set_tpl_title_for_list($mode);
        $this->set_tpl_photo_list($photo_list);
        $this->set_tpl_error($error);
        $this->set_tpl_category_photo_list($category_photo_list);
        $this->set_tpl_timeline_with_check($mode, $this->_cat_id, $photo_rows);
        $this->set_tpl_tagcloud_with_check($this->_ini_tagcloud_list_limit);

        $this->set_tpl_show_js_windows();

        return $this->tpl_get();
    }

    /**
     * @param $mode
     * @return array
     */
    public function build_photo_list_for_list($mode)
    {
        $arr = [];
        $error = null;

        switch ($mode) {
            case 'category':
                list($category_photo_list, $photo_rows) = $this->_category_class->build_photo_list_for_list();

                return [null, $photo_rows, $category_photo_list, null];
                break;
            case 'date':
                $arr = $this->_date_class->build_rows_for_list();
                $error = $this->get_constant('DATE_NOT_SET');
                break;
            case 'place':
                $arr = $this->_place_class->build_rows_for_list();
                $error = $this->get_constant('PLACE_NOT_SET');
                break;
            case 'tag':
                $arr = $this->_tag_class->build_rows_for_list();
                $error = $this->get_constant('NO_TAG');
                break;
            case 'user':
                $arr = $this->_user_class->build_rows_for_list();
                break;
            default:
                break;
        }

        $photo_list = [];
        $photo_rows = [];

        if (!is_array($arr) || !count($arr)) {
            return [$photo_list, $photo_rows, null, $error];
        }

        foreach ($arr as $a) {
            list($title, $param, $total, $row) = $a;
            $photo_list[] = $this->build_photo_list_for_list_by_row($title, $param, $total, $row);
            $photo_rows[$row['item_id']] = $row;
        }

        return [$photo_list, $photo_rows, null, null];
    }

    /**
     * @param $title
     * @param $param
     * @param $total
     * @param $row
     * @return array
     */
    public function build_photo_list_for_list_by_row($title, $param, $total, $row)
    {
        $link = $this->_uri_class->build_list_link($this->_mode, $param);
        $photo = $this->build_photo_by_row($row);

        $arr = [
            'title' => $title,
            'title_s' => $this->sanitize($title),
            'link' => $link,
            'link_s' => $this->sanitize($link),
            'total' => $total,
            'photo' => $photo,
        ];

        return $arr;
    }

    //---------------------------------------------------------
    // page detail
    //---------------------------------------------------------

    /**
     * @param $mode
     * @param $param
     * @return array
     */
    public function build_page_detail($mode, $param)
    {
        $this->show_array_set_detail_by_mode($mode);
        $this->set_tpl_show_page_detail(true);

        list($title, $total, $rows, $cat_param) = $this->build_rows_for_detail($mode, $param);

        $param_extra = [
            'mode' => $mode,
            'rows' => $rows,
            'cat_param' => $cat_param,
            'cat_id' => $this->_cat_id,
            'sub_mode' => $this->_sub_mode,
            'sub_param' => $this->_sub_param,
            'cat_param' => $cat_param,
        ];

        $show_gmap = $this->set_tpl_gmap_for_detail_with_check($param_extra);
        $this->set_tpl_timeline_with_check($param_extra);

        if ('search' == $mode) {
            $query_param = $this->_search_class->build_query_param($total);
            $query_array = $query_param['search_query_array'];
            $this->_photo_class->set_flag_highlight(true);
            $this->_photo_class->set_keyword_array($query_array);
        }

        $photo_list = $this->build_photo_list_for_detail($rows);

        $show_ligthtbox = false;
        if ($this->show_check('photo') && isset($rows[0])) {
            $show_ligthtbox = $this->set_tpl_photo_for_detail($rows[0]);
        }

        $this->xoops_header_array_set_by_mode($mode);
        $this->xoops_header_param();

        // BUG: not show rss param
        $this->xoops_header_rss_with_check($mode, $param);

        $this->xoops_header_gmap_with_check($show_gmap);
        $this->xoops_header_lightbox_with_check($show_ligthtbox);
        $this->xoops_header_assign();

        $this->show_param();
        $this->set_tpl_common();
        $this->set_tpl_mode($mode);
        $this->set_tpl_title($title);
        $this->set_tpl_qr_with_check(0);
        $this->set_tpl_notification_select_with_check();
        $this->set_tpl_tagcloud_with_check($this->_cfg_tags);
        $this->set_tpl_photo_list($photo_list);
        $this->set_tpl_cat_param($cat_param);
        $this->set_tpl_cat_id($this->_cat_id);
        $this->set_tpl_catpath_with_check($this->_cat_id);
        $this->set_tpl_catlist_with_check($this->_cat_id);

        // for detail
        $this->set_tpl_total_for_detail($mode, $total);

        if ('search' == $mode) {
            $this->tpl_merge($query_param);
        }

        $lang_param = $this->_timeline_class->get_lang_param();
        if (is_array($lang_param)) {
            $this->tpl_merge($lang_param);
        }

        $this->set_tpl_show_js_windows();

        return $this->tpl_get();
    }

    /**
     * @param $mode
     * @param $param
     * @return array
     */
    public function build_rows_for_detail($mode, $param)
    {
        $param_out = null;
        $cat_param = null;

        switch ($mode) {
            case 'map':
            case 'timeline':
                list($title, $total, $rows, $cat_param) = $this->map_timeline_build_rows_for_detail($mode);
                break;
            case 'category':
                list($title, $total, $rows, $cat_param) = $this->category_build_rows_for_detail($param);
                break;
            case 'date':
                list($title, $total, $rows, $param_out) = $this->date_build_rows_for_detail($param);
                break;
            case 'place':
                list($title, $total, $rows) = $this->place_build_rows_for_detail($param);
                break;
            case 'tag':
                list($title, $total, $rows) = $this->tag_build_rows_for_detail($param);
                break;
            case 'user':
                list($title, $total, $rows) = $this->user_build_rows_for_detail($param);
                break;
            case 'search':
                list($title, $total, $rows) = $this->search_build_rows_for_detail($param);
                break;
            default:
                list($title, $total, $rows) = $this->main_build_rows_for_detail($mode);
                break;
        }

        if ($param_out) {
            $this->set_param_out($param_out);
        }

        return [$title, $total, $rows, $cat_param];
    }

    /**
     * @param $mode
     * @return array
     */
    public function map_build_rows_for_detail($mode)
    {
        $param_int = (int)$this->_sub_param;

        $photo_total_sum = 0;
        $photo_small_sum = 0;

        if (('category' == $this->_sub_mode) && ($param_int > 0)) {
            list($title, $total, $rows, $photo_total_sum, $photo_small_sum) = $this->sub_category_build_rows_for_detail($mode, $param_int);
            $gmap_rows = $this->_gmap_class->build_rows_for_detail_category($param_int);
        } else {
            list($title, $total, $rows) = $this->main_build_rows_for_detail($mode);
            $gmap_rows = $this->_gmap_class->build_rows_for_detail_main();
        }

        return [$title, $total, $rows, $gmap_rows, $photo_total_sum, $photo_small_sum];
    }

    /**
     * @param $param
     * @return array
     */
    public function date_build_rows_for_detail($param)
    {
        list($title, $total, $param_out) = $this->_date_class->build_total_for_detail($param);

        $rows = null;
        $start = $this->calc_navi_start($total);

        if ($total > 0) {
            $rows = $this->_date_class->build_rows_for_detail($param_out, $this->_orderby, $this->_PHOTO_LIMIT, $start);
        }

        return [$title, $total, $rows, $param_out];
    }

    /**
     * @param $param
     * @return array
     */
    public function place_build_rows_for_detail($param)
    {
        list($place_mode, $place_arr, $title, $total) = $this->_place_class->build_total_for_detail($param);

        $rows = null;
        $start = $this->calc_navi_start($total);

        if ($total > 0) {
            $rows = $this->_place_class->build_rows_for_detail($place_mode, $place_arr, $this->_orderby, $this->_PHOTO_LIMIT, $start);
        }

        return [$title, $total, $rows];
    }

    /**
     * @param $param
     * @return array
     */
    public function tag_build_rows_for_detail($param)
    {
        list($tag_name, $title, $total) = $this->_tag_class->build_total_for_detail($param);

        $rows = null;
        $start = $this->calc_navi_start($total);

        if ($total > 0) {
            $rows = $this->_tag_class->build_rows_for_detail($tag_name, $this->_orderby, $this->_PHOTO_LIMIT, $start);
        }

        return [$title, $total, $rows];
    }

    /**
     * @param $param
     * @return array
     */
    public function user_build_rows_for_detail($param)
    {
        list($title, $total) = $this->_user_class->build_total_for_detail($param);

        $rows = null;
        $start = $this->calc_navi_start($total);

        if ($total > 0) {
            $rows = $this->_user_class->build_rows_for_detail($param, $this->_orderby, $this->_PHOTO_LIMIT, $start);
        }

        return [$title, $total, $rows];
    }

    /**
     * @param $param
     * @return array
     */
    public function search_build_rows_for_detail($param)
    {
        list($sql_query, $title, $total) = $this->_search_class->build_total_for_detail($param);

        $rows = null;
        $start = $this->calc_navi_start($total);

        if ($total > 0) {
            $rows = $this->_search_class->build_rows_for_detail($sql_query, $this->_orderby, $this->_PHOTO_LIMIT, $start);
        }

        return [$title, $total, $rows];
    }

    /**
     * @param $mode
     * @return array
     */
    public function main_build_rows_for_detail($mode)
    {
        list($title, $total) = $this->_main_class->build_total_for_detail($mode);

        $rows = null;
        $start = $this->calc_navi_start($total);

        if ($total > 0) {
            $rows = $this->_main_class->build_rows_for_detail($mode, $this->_sort, $this->_PHOTO_LIMIT, $start);
        }

        return [$title, $total, $rows];
    }

    //---------------------------------------------------------
    // map timeline
    //---------------------------------------------------------

    /**
     * @param $mode
     * @return array
     */
    public function map_timeline_build_rows_for_detail($mode)
    {
        $param_int = (int)$this->_sub_param;

        $cat_param = null;

        if (('category' == $this->_sub_mode) && ($param_int > 0)) {
            list($title, $total, $rows, $cat_param) = $this->build_rows_for_detail_sub_category($mode, $param_int);
        } else {
            list($title, $total, $rows) = $this->main_build_rows_for_detail($mode);
        }

        return [$title, $total, $rows, $cat_param];
    }

    /**
     * @param $mode
     * @param $cat_id
     * @return array
     */
    public function build_rows_for_detail_sub_category($mode, $cat_id)
    {
        list($cat_title, $total, $rows, $cat_param) = $this->category_build_rows_for_detail($cat_id);

        $mode_title = $this->build_title_by_mode($mode);
        $title = $mode_title . ' : ' . $cat_title;

        return [$title, $total, $rows, $cat_param];
    }

    // --- class end ---
}
