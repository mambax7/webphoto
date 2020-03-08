<?php
// $Id: rss.php,v 1.10 2011/06/05 07:23:40 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-06-04 K.OHWADA
// webphoto_uri
// 2010-11-03 K.OHWADA
// webphoto_main
// 2010-01-10 K.OHWADA
// webphoto_tag
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_item_handler
// 2009-08-30 K.OHWADA
// user name
// 2009-02-28 K.OHWADA
// use_pathinfo
// georss
// 2008-12-12 K.OHWADA
// webphoto_photo_public
// 2008-12-09 K.OHWADA
// Parse error & Fatal error
// 2008-11-29 K.OHWADA
// _build_file_image()
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//---------------------------------------------------------
// usage
// index.php/rss/mode/param/limit=xxx/clear=1/
//   mode : latest (default)
//          category, user, random, etc
//   param : non (default)
//          category id, user id, etc
//   limit : 20 (default) : max 100
//   clear : 0 = noting (default)
//           1 = clear compiled template & cache
//---------------------------------------------------------

//---------------------------------------------------------
// TODO
//   show video in mediaRSS
//---------------------------------------------------------

//=========================================================
// class webphoto_rss
//=========================================================

/**
 * Class webphoto_rss
 */
class webphoto_rss extends webphoto_lib_rss
{
    public $_config_class;
    public $_item_handler;
    public $_fileHandler;
    public $_catHandler;
    public $_pathinfo_class;
    public $_multibyte_class;
    public $_sort_class;
    public $_search_class;
    public $_utility_class;
    public $_public_class;
    public $_tag_class;
    public $_main_class;
    public $_category_class;
    public $_user_class;
    public $_place_class;
    public $_date_class;
    public $_uri_class;

    public $_mode = null;
    public $_param = null;
    public $_limit = 20;

    public $_MAX_SUMMARY = 500;
    public $_MODE_DEFAULT = 'latest';
    public $_ORDERBY_RANDOM = 'rand()';

    public $_CACHE_TIME_RAMDOM = 60;   // 1 min
    public $_CACHE_TIME_LATEST = 3600; // 1 hour
    public $_CACHE_TIME_DEBUG = 0;    // no cache

    public $_LIMIT_DEFAULT = 20;
    public $_LIMIT_MAX = 100;

    public $_DEBUG_FLAG_CACHE_TIME = false;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_rss constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname);
        $this->set_template('db:' . $dirname . '_main_rss.html');

        $this->_catHandler = webphoto_cat_handler::getInstance($dirname, $trust_dirname);
        $this->_item_handler = webphoto_item_handler::getInstance($dirname, $trust_dirname);
        $this->_fileHandler = webphoto_file_handler::getInstance($dirname, $trust_dirname);
        $this->_public_class = webphoto_photo_public::getInstance($dirname, $trust_dirname);
        $this->_sort_class = webphoto_photo_sort::getInstance($dirname, $trust_dirname);
        $this->_tag_class = webphoto_tag::getInstance($dirname, $trust_dirname);
        $this->_main_class = webphoto_main::getInstance($dirname, $trust_dirname);
        $this->_category_class = webphoto_category::getInstance($dirname, $trust_dirname);
        $this->_user_class = webphoto_user::getInstance($dirname, $trust_dirname);
        $this->_place_class = webphoto_place::getInstance($dirname, $trust_dirname);
        $this->_date_class = webphoto_date::getInstance($dirname, $trust_dirname);
        $this->_search_class = webphoto_search::getInstance($dirname, $trust_dirname);

        $this->_config_class = webphoto_config::getInstance($dirname);
        $this->_uri_class = webphoto_uri::getInstance($dirname);

        $this->_pathinfo_class = webphoto_lib_pathinfo::getInstance();
        $this->_utility_class = webphoto_lib_utility::getInstance();
        $this->_multibyte_class = webphoto_multibyte::getInstance();
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_lib_rss|\webphoto_lib_xml|\webphoto_rss
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
    // main
    //---------------------------------------------------------
    public function show_rss()
    {
        $this->_mode = $this->_get_mode();
        $this->_param = $this->_get_param($this->_mode);
        $this->_limit = $this->_get_limit();

        $clear = $this->_get_clear();
        $cache_id = $this->_get_cache_id($this->_mode, $this->_param);
        $cache_time = $this->_get_cache_time($this->_mode);

        if ($clear) {
            $this->clear_compiled_tpl_for_admin($cache_id, true);
            exit();
        }

        echo $this->build_rss($cache_id, $cache_time);
    }

    /**
     * @return bool|null
     */
    public function _get_mode()
    {
        $mode_input = $this->_pathinfo_class->get('mode');
        if (empty($mode_input)) {
            $mode_input = $this->_pathinfo_class->get_path(1);
        }

        switch ($mode_input) {
            case 'clear':
            case 'randomphotos':
                $mode = $mode_input;
                break;
            default:
                list($mode, $mode_orig) = $this->_sort_class->input_to_mode($mode_input);
                break;
        }

        return $mode;
    }

    /**
     * @param $mode
     * @return mixed
     */
    public function _get_param($mode)
    {
        $param_input = $this->_pathinfo_class->get('param');
        if (empty($param_input)) {
            $param_input = $this->_pathinfo_class->get_path(2);
        }

        $param = $this->_sort_class->input_to_param_for_rss($mode, $param_input);

        return $param;
    }

    /**
     * @return int
     */
    public function _get_limit()
    {
        $limit = $this->_pathinfo_class->get_int('limit');
        if ($limit <= 0) {
            $limit = $this->_LIMIT_DEFAULT;
        } elseif ($limit > $this->_LIMIT_MAX) {
            $limit = $this->_LIMIT_MAX;
        }

        return $limit;
    }

    /**
     * @return int
     */
    public function _get_clear()
    {
        return $this->_pathinfo_class->get_int('clear');
    }

    /**
     * @param $mode
     * @param $param
     * @return string
     */
    public function _get_cache_id($mode, $param)
    {
        $cache_id = md5($mode . $param);

        return $cache_id;
    }

    /**
     * @param $mode
     * @return int
     */
    public function _get_cache_time($mode)
    {
        if ($this->_DEBUG_FLAG_CACHE_TIME) {
            return $this->_CACHE_TIME_DEBUG;
        }

        switch ($mode) {
            case 'random':
                $cache_time = $this->_CACHE_TIME_RAMDOM;
                break;
            default:
                $cache_time = $this->_CACHE_TIME_LATEST;
                break;
        }

        return $cache_time;
    }

    //---------------------------------------------------------
    // items
    //---------------------------------------------------------

    /**
     * @return array|void
     */
    public function build_items()
    {
        $ret = [];

        $rows = $this->_get_photo_rows();
        foreach ($rows as $row) {
            $cat_row = $this->_catHandler->get_cached_row_by_id($row['item_cat_id']);
            $cont_row = $this->_get_file_row_by_kind($row, _C_WEBPHOTO_FILE_KIND_CONT);
            $thumb_row = $this->_get_file_row_by_kind($row, _C_WEBPHOTO_FILE_KIND_THUMB);

            $link_xml = $this->xml_text($this->_build_link($row));
            $title_xml = $this->xml_text($row['item_title']);
            $pubdate = date('r', $row['item_time_update']);
            list($content, $summary, $desc) = $this->_build_description($row, $thumb_row);

            // georss
            $geo_lat = '';
            $geo_long = '';
            $georss_point = '';

            if ((0 != $row['item_gmap_latitude'])
                || (0 != $row['item_gmap_longitude'])
                || (0 != $row['item_gmap_zoom'])) {
                $geo_lat = $row['item_gmap_latitude'];
                $geo_long = $row['item_gmap_longitude'];
                $georss_point = $geo_lat . ' ' . $geo_long;
            }

            // mediarss
            $media_title_xml = '';
            $media_description = '';
            $media_content_url = '';
            $media_content_filesize = '';
            $media_content_height = '';
            $media_content_width = '';
            $media_content_type = '';
            $media_content_medium = '';
            $media_content_duration = '';
            $media_thumbnail_url = '';
            $media_thumbnail_height = '';
            $media_thumbnail_width = '';
            $media_thumbnail_large_url = '';
            $media_thumbnail_large_height = '';
            $media_thumbnail_large_width = '';

            if (is_array($cont_row)) {
                list($media_content_url, $media_content_width, $media_content_height) = $this->_build_file_image($cont_row);

                $media_title_xml = $title_xml;
                $media_description = $summary;
                $media_content_filesize = $cont_row['file_size'];
                $media_content_duration = $cont_row['file_duration'];
                $media_content_type = $cont_row['file_mime'];

                // imaeg type
                if ($this->_is_kind_image($row)) {
                    $media_content_medium = 'image';

                    if (is_array($thumb_row)) {
                        list($media_thumbnail_url, $media_thumbnail_width, $media_thumbnail_height) = $this->_build_file_image($thumb_row);

                        $media_thumbnail_large_url = $media_content_url;
                        $media_thumbnail_large_height = $media_content_height;
                        $media_thumbnail_large_width = $media_content_width;
                    }

                    // video type
                } elseif ($this->_is_kind_video($row)) {
                    $media_content_medium = 'video';
                }
            }

            $arr = [
                'link' => $link_xml,
                'guid' => $link_xml,
                'title' => $title_xml,
                'category' => $this->xml_text($cat_row['cat_title']),
                'pubdate' => $this->xml_text($pubdate),
                'description' => $this->xml_text($desc),

                // user name
                'dc_creator' => XoopsUser::getUnameFromId($row['item_uid']),

                'geo_lat' => $geo_lat,
                'geo_long' => $geo_long,
                'georss_point' => $georss_point,
                'media_title' => $media_title_xml,
                'media_description' => $this->xml_text($media_description),
                'media_content_url' => $this->xml_url($media_content_url),
                'media_content_filesize' => (int)$media_content_filesize,
                'media_content_height' => (int)$media_content_height,
                'media_content_width' => (int)$media_content_width,
                'media_content_type' => $this->xml_text($media_content_type),
                'media_content_medium' => $this->xml_text($media_content_medium),
                'media_content_duration' => (int)$media_content_duration,
                'media_thumbnail_url' => $this->xml_url($media_thumbnail_url),
                'media_thumbnail_height' => (int)$media_thumbnail_height,
                'media_thumbnail_width' => (int)$media_thumbnail_width,
                'media_thumbnail_large_url' => $this->xml_url($media_thumbnail_large_url),
                'media_thumbnail_large_height' => (int)$media_thumbnail_large_height,
                'media_thumbnail_large_width' => (int)$media_thumbnail_large_width,
            ];

            $ret[] = $arr;
        }

        return $ret;
    }

    /**
     * @param $row
     * @param $thumb_row
     * @return array
     */
    public function _build_description($row, $thumb_row)
    {
        $context = $this->_build_context($row);
        $summary = $this->_multibyte_class->build_summary($context, $this->_MAX_SUMMARY);

        $desc = '';

        if ($this->_is_kind_image($row) && is_array($thumb_row)) {
            // Parse error & Fatal error
            list($thumb_url, $thumb_width, $thumb_height) = $this->_build_file_image($thumb_row);

            $img = '<img src="' . $thumb_url . '" ';
            $img .= 'alt="' . $row['item_title'] . '" ';
            if ($thumb_width && $thumb_height) {
                $img .= 'width="' . $thumb_width . '" ';
                $img .= 'height="' . $thumb_height . '" ';
            }
            $img .= '">';

            $desc .= '<a href="' . $this->_build_link($row) . '" target="_blank">';
            $desc .= $img . '</a><br>';
        }

        if (mb_strlen($context) > $this->_MAX_SUMMARY) {
            $desc .= $summary;
        } else {
            $desc .= $context;
        }

        return [$context, $summary, $desc];
    }

    /**
     * @param $row
     * @return mixed
     */
    public function _build_context($row)
    {
        return $this->_item_handler->build_show_description_disp($row);
    }

    /**
     * @param $row
     * @return string
     */
    public function _build_link($row)
    {
        return $this->_uri_class->build_photo($row['item_id'], false);
    }

    /**
     * @param $row
     * @return bool
     */
    public function _is_kind_image($row)
    {
        if (_C_WEBPHOTO_ITEM_KIND_IMAGE == $row['item_kind']) {
            return true;
        }

        return false;
    }

    /**
     * @param $row
     * @return bool
     */
    public function _is_kind_video($row)
    {
        if (_C_WEBPHOTO_ITEM_KIND_VIDEO == $row['item_kind']) {
            return true;
        }

        return false;
    }

    /**
     * @param $row
     * @param $kind
     * @return bool|null
     */
    public function _get_file_row_by_kind($row, $kind)
    {
        $file_id = $this->_item_handler->build_value_fileid_by_kind($row, $kind);
        if ($file_id > 0) {
            return $this->_fileHandler->get_row_by_id($file_id);
        }

        return null;
    }

    /**
     * @param $file_row
     * @return array
     */
    public function _build_file_image($file_row)
    {
        return $this->_fileHandler->build_show_file_image($file_row);
    }

    //---------------------------------------------------------
    // handler
    //---------------------------------------------------------

    /**
     * @return array|bool|null
     */
    public function _get_photo_rows()
    {
        $limit = $this->_limit;

        $param = $this->_param;
        $param_int = (int)$param;
        $param_decode = $this->_multibyte_class->convert_from_utf8($param);

        $where = null;
        $orderby = null;
        $rows = null;

        $sort = null;
        $orderby_default = $this->_sort_class->sort_to_orderby($sort);
        $orderby = $orderby_default;

        switch ($this->_mode) {
            case 'category':
                if ($param_int > 0) {
                    $rows = $this->_category_class->build_rows_for_rss($param_int, $orderby, $limit);
                }
                break;
            case 'date':
                if ($param) {
                    $rows = $this->_date_class->build_rows_for_rss($param, $orderby, $limit);
                }
                break;
            case 'place':
                if ($param_decode) {
                    $rows = $this->_place_class->build_rows_for_rss($param_decode, $orderby, $limit);
                }
                break;
            case 'tag':
                if ($param_decode) {
                    $rows = $this->_tag_class->build_rows_for_rss($param_decode, $orderby, $limit);
                }
                break;
            case 'user':
                if ($param_int > 0) {
                    $rows = $this->_user_class->build_rows_for_rss($param_int, $orderby, $limit);
                }
                break;
            case 'search':
                if ($param) {
                    $rows = $this->_search_class->build_rows_for_rss($param, $orderby, $limit);
                }
                break;
            // only photo for slide show
            case 'randomphotos':
                $rows = $this->_build_rows_randomphotos($param_int, $limit);
                break;
            default:
                $rows = $this->_main_class->build_rows_for_rss($this->_mode, $limit);
                break;
        }

        if (is_array($rows) && count($rows)) {
            return $rows;
        }

        $rows = $this->_main_class->build_rows_for_rss($this->_MODE_DEFAULT, $limit);

        return $rows;
    }

    /**
     * @param $param_int
     * @param $limit
     * @return array|bool
     */
    public function _build_rows_randomphotos($param_int, $limit)
    {
        if ($param_int > 0) {
            $rows = $this->_public_class->get_rows_photo_by_catid_orderby($param_int, $this->_ORDERBY_RANDOM, $limit);
        } else {
            $rows = $this->_public_class->get_rows_photo_by_orderby($this->_ORDERBY_RANDOM, $limit);
        }

        return $rows;
    }

    // --- class end ---
}
