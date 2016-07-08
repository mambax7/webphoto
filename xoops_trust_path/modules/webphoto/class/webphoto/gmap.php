<?php
// $Id: gmap.php,v 1.17 2011/12/28 18:02:27 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-12-25 K.OHWADA
// build_rows_for_detail()
// 2010-11-11 K.OHWADA
// get_file_extend_row_by_kind()
// 2010-01-10 K.OHWADA
// build_for_category()
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_item_cat_handler
// 2009-04-10 K.OHWADA
// function array_merge_unique()
// 2009-01-25 K.OHWADA
// webphoto_gmap_info -> webphoto_inc_gmap_info
// get_gmap_center()
// 2008-12-12 K.OHWADA
// webphoto_item_cat_handler
// 2008-11-29 K.OHWADA
// build_show_file_image()
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
// used preload_init()
// 2008-07-01 K.OHWADA
// not use webphoto_convert_to_utf8()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_gmap
//=========================================================
class webphoto_gmap extends webphoto_base_this
{
    public $_gicon_handler;
    public $_item_cat_handler;
    public $_gmap_info_class;
    public $_catlist_class;
    public $_public_class;

    public $_cfg_perm_cat_read;
    public $_cfg_gmap_apikey;
    public $_cfg_gmap_latitude;
    public $_cfg_gmap_longitude;
    public $_cfg_gmap_zoom;
    public $_cfg_gmap_photos;

    public $_ORDERBY_LATEST = 'item_time_update DESC, item_id DESC';
    public $_KEY_NAME       = 'item_id';
    public $_LIMIT_ONE      = 1;
    public $_OFFSET_ZERO    = 0;
    public $_KEY_TRUE       = true;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_gicon_handler    = webphoto_gicon_handler::getInstance($dirname, $trust_dirname);
        $this->_item_cat_handler = webphoto_item_cat_handler::getInstance($dirname, $trust_dirname);
        $this->_catlist_class    = webphoto_inc_catlist::getSingleton($dirname, $trust_dirname);
        $this->_gmap_info_class  = webphoto_inc_gmap_info::getSingleton($dirname, $trust_dirname);
        $this->_public_class     = webphoto_photo_public::getInstance($dirname, $trust_dirname);

        $cfg_perm_item_read        = $this->get_config_by_name('perm_item_read');
        $this->_cfg_perm_cat_read  = $this->get_config_by_name('perm_cat_read');
        $this->_cfg_gmap_apikey    = $this->get_config_by_name('gmap_apikey');
        $this->_cfg_gmap_latitude  = $this->get_config_by_name('gmap_latitude');
        $this->_cfg_gmap_longitude = $this->get_config_by_name('gmap_longitude');
        $this->_cfg_gmap_zoom      = $this->get_config_by_name('gmap_zoom');
        $this->_cfg_gmap_photos    = $this->get_config_by_name('gmap_photos');

        $this->_item_cat_handler->set_perm_item_read($cfg_perm_item_read);

        $this->preload_init();
    }

    public static function getInstance($dirname = null, $trust_dirname = null)
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new webphoto_gmap($dirname, $trust_dirname);
        }
        return $instance;
    }

    //---------------------------------------------------------
    // list
    //---------------------------------------------------------
    public function build_for_list($rows, $flag_large)
    {
        $center_param = $this->get_gmap_center();

        if (is_array($rows) && count($rows)) {
            return $this->build_gmap_from_rows($rows, $center_param, $flag_large);
        }

        $arr = array(
            'show_gmap' => false,
        );
        return $arr;
    }

    //---------------------------------------------------------
    // detail
    //---------------------------------------------------------
    public function build_for_detail($param, $flag_large)
    {
        $rows = $param['rows'];

        $center_param = $this->get_gmap_center_for_detail($param);

        $extra_rows = $this->build_rows_for_detail($param);
        $all_rows   = $this->array_merge_unique($extra_rows, $rows);

        if (is_array($all_rows) && count($all_rows)) {
            return $this->build_gmap_from_rows($all_rows, $center_param, $flag_large);
        }

        $arr = array(
            'show_gmap' => false,
        );
        return $arr;
    }

    public function get_gmap_center_for_detail($param)
    {
        $mode          = $param['mode'];
        $cat_id        = $param['cat_id'];
        $sub_mode      = $param['sub_mode'];
        $sub_param     = $param['sub_param'];
        $sub_param_int = (int)$sub_param;

        if (($mode == 'category') && ($cat_id > 0)) {
            $new_cat_id = $cat_id;
        } elseif (($sub_mode == 'category') && ($sub_param_int > 0)) {
            $new_cat_id = $sub_param_int;
        } else {
            $new_cat_id = 0;
        }

        return $this->get_gmap_center(0, $new_cat_id);
    }

    public function build_rows_for_detail($param)
    {
        $mode          = $param['mode'];
        $cat_id        = $param['cat_id'];
        $sub_mode      = $param['sub_mode'];
        $sub_param     = $param['sub_param'];
        $sub_param_int = (int)$sub_param;

        if (($mode == 'category') && ($cat_id > 0)) {
            $rows = $this->build_rows_for_detail_category($cat_id);
        } elseif (($sub_mode == 'category') && ($sub_param_int > 0)) {
            $rows = $this->build_rows_for_detail_category($sub_param_int);
        } else {
            $rows = $this->build_rows_for_detail_main();
        }

        return $rows;
    }

    public function build_rows_for_detail_main()
    {
        return $this->get_rows_by_orderby($this->_ORDERBY_LATEST, $this->_cfg_gmap_photos);
    }

    public function build_rows_for_detail_category($cat_id)
    {
        return $this->get_rows_by_catid_orderby($cat_id, $this->_ORDERBY_LATEST, $this->_cfg_gmap_photos);
    }

    //---------------------------------------------------------
    // photo
    //---------------------------------------------------------
    public function build_for_photo($row)
    {
        $show  = false;
        $icons = null;

        $photo = $this->build_show($row);
        if (is_array($photo)) {
            $show  = true;
            $icons = $this->build_icon_list();
        }

        $arr = array(
            'show_gmap'                => $show,
            'gmap_photo'               => $photo,
            'gmap_icons'               => $icons,
            'gmap_latitude'            => $row['item_gmap_latitude'],
            'gmap_longitude'           => $row['item_gmap_longitude'],
            'gmap_zoom'                => $row['item_gmap_zoom'],
            'gmap_lang_not_compatible' => $this->get_constant('GMAP_NOT_COMPATIBLE'),
        );
        return $arr;
    }

    public function build_show($item_row)
    {
        if (empty($this->_cfg_gmap_apikey)) {
            return null;
        }
        if (!$this->exist_gmap_item($item_row)) {
            return null;
        }

        return $this->build_photo_single($item_row);
    }

    //---------------------------------------------------------
    // gmap
    //---------------------------------------------------------
    public function build_gmap_from_rows($rows, $center_param, $flag_large)
    {
        $show  = false;
        $icons = null;

        // Undefined variable: photos
        $photos = null;

        if (is_array($rows) && count($rows)) {
            $photos = $this->build_photos_from_rows($rows);
            if (is_array($photos) && count($photos)) {
                $show  = true;
                $icons = $this->build_icon_list();
            }
        }

        $arr = array(
            'show_gmap'                => $show,
            'gmap_photos'              => $photos,
            'gmap_icons'               => $icons,
            'gmap_latitude'            => $center_param['latitude'],
            'gmap_longitude'           => $center_param['longitude'],
            'gmap_zoom'                => $center_param['zoom'],
            'gmap_class'               => $this->get_gmap_class($flag_large),
            'show_map_large'           => !$flag_large,
            'gmap_lang_not_compatible' => $this->get_constant('GMAP_NOT_COMPATIBLE'),
        );
        return $arr;
    }

    public function get_gmap_class($flag_large)
    {
        if ($flag_large) {
            return 'webphoto_gmap_large';
        }
        return 'webphoto_gmap_normal';
    }

    public function build_icon_list($limit = 0, $offset = 0)
    {
        if (empty($this->_cfg_gmap_apikey)) {
            return null;
        }

        $rows = $this->_gicon_handler->get_rows_all_asc($limit, $offset);
        if (!is_array($rows)) {
            return null;
        }

        return $rows;
    }

    //---------------------------------------------------------
    // photos
    //---------------------------------------------------------
    public function build_photos_from_rows($item_rows)
    {
        if (empty($this->_cfg_gmap_apikey)) {
            return null;
        }

        if (!is_array($item_rows) || !count($item_rows)) {
            return null;
        }

        $arr = array();
        foreach ($item_rows as $item_row) {
            $arr[] = $this->build_photo_single($item_row);
        }
        return $arr;
    }

    public function build_photo_single($item_row)
    {
        $show                   = $item_row;
        $show['gmap_latitude']  = (float)$item_row['item_gmap_latitude'];
        $show['gmap_longitude'] = (float)$item_row['item_gmap_longitude'];
        $show['gmap_icon_id']   = (int)$this->_build_icon_id($item_row);
        $show['gmap_info']      = $this->_build_gmap_info($item_row);
        return $show;
    }

    public function _build_gmap_info($item_row)
    {
        $thumb_row = $this->get_file_extend_row_by_kind($item_row, _C_WEBPHOTO_FILE_KIND_THUMB);

        list($thumb_url, $thumb_width, $thumb_height) = $this->build_show_file_image($thumb_row);

        $param                 = $item_row;
        $param['thumb_url']    = $thumb_url;
        $param['thumb_width']  = $thumb_width;
        $param['thumb_height'] = $thumb_height;

        return $this->sanitize_control_code($this->_build_gmap_info_preload($param));
    }

    public function _build_gmap_info_preload($param)
    {
        if ($this->_preload_class->exists_class('gmap_info')) {
            return $this->_preload_class->exec_class_method('gmap_info', 'build_info_extend', $param);
        }
        return $this->_gmap_info_class->build_info($param);
    }

    public function _build_icon_id($item_row)
    {
        if ($item_row['item_gicon_id'] > 0) {
            return $item_row['item_gicon_id'];
        }
        return $this->_build_cat_gicon_id($item_row);
    }

    public function _build_cat_gicon_id($item_row)
    {
        return $this->_cat_handler->get_cached_value_by_id_name($item_row['item_cat_id'], 'cat_gicon_id');
    }

    //---------------------------------------------------------
    // gmap location
    //---------------------------------------------------------
    public function get_gmap_center($item_id = 0, $cat_id = 0)
    {
        $code      = 0;
        $latitude  = 0;
        $longitude = 0;
        $zoom      = 0;

        // config
        if ($this->exist_gmap_cfg()) {
            $code      = 1;
            $latitude  = $this->_cfg_gmap_latitude;
            $longitude = $this->_cfg_gmap_longitude;
            $zoom      = $this->_cfg_gmap_zoom;
        }

        // item
        if ($item_id > 0) {
            $row = $this->_item_handler->get_cached_row_by_id($item_id);
            if (is_array($row) && $this->exist_gmap_item($row)) {
                $code      = 2;
                $latitude  = $row['item_gmap_latitude'];
                $longitude = $row['item_gmap_longitude'];
                $zoom      = $row['item_gmap_zoom'];
            }

            // cat
        } elseif ($cat_id > 0) {
            $row = $this->_cat_handler->get_cached_row_by_id($cat_id);
            if (is_array($row) && $this->exist_gmap_cat($row)) {
                $code      = 3;
                $latitude  = $row['cat_gmap_latitude'];
                $longitude = $row['cat_gmap_longitude'];
                $zoom      = $row['cat_gmap_zoom'];
            }
        }

        $param = array(
            'code'      => $code,
            'latitude'  => $latitude,
            'longitude' => $longitude,
            'zoom'      => $zoom
        );

        return $param;
    }

    public function exist_gmap_cfg()
    {
        return $this->exist_gmap($this->_cfg_gmap_latitude, $this->_cfg_gmap_longitude, $this->_cfg_gmap_zoom);
    }

    public function exist_gmap_item($item_row)
    {
        return $this->exist_gmap($item_row['item_gmap_latitude'], $item_row['item_gmap_longitude'], $item_row['item_gmap_zoom']);
    }

    public function exist_gmap_cat($cat_row)
    {
        return $this->exist_gmap($cat_row['cat_gmap_latitude'], $cat_row['cat_gmap_longitude'], $cat_row['cat_gmap_zoom']);
    }

    public function exist_gmap($latitude, $longitude, $zoom)
    {
        if ($latitude == 0) {
            return false;
        }
        if ($longitude == 0) {
            return false;
        }
        if ($zoom == 0) {
            return false;
        }
        return true;
    }

    public function build_list_location($item_rows)
    {
        $arr = array();
        foreach ($item_rows as $item_row) {
            $row             = $item_row;
            $row['info']     = $this->_build_gmap_info($item_row);
            $row['gicon_id'] = $this->_build_icon_id($item_row);
            $arr[]           = $row;
        }
        return $arr;
    }

    //---------------------------------------------------------
    // public class
    //---------------------------------------------------------
    public function get_rows_by_orderby($orderby, $limit)
    {
        return $this->_public_class->get_rows_by_orderby($orderby, $limit, $this->_OFFSET_ZERO, $this->_KEY_TRUE);
    }

    public function get_rows_by_catid_orderby($cat_id, $orderby, $limit = 0, $offset = 0)
    {
        $catid_array = $this->_catlist_class->get_cat_parent_all_child_id_by_id($cat_id);

        return $this->_public_class->get_rows_by_gmap_catid_array($catid_array, $orderby, $limit, $offset);
    }

    //---------------------------------------------------------
    // utility
    //---------------------------------------------------------
    public function array_merge_unique($arr1, $arr2)
    {
        $arr_ret = null;
        if (is_array($arr1) && count($arr1)) {
            $arr_ret = $arr1;

            if (is_array($arr2) && count($arr2)) {
                foreach ($arr2 as $a) {
                    $key_val = $a[$this->_KEY_NAME];
                    if (!isset($arr_ret[$key_val]) && $this->exist_gmap_item($a)) {
                        $arr_ret[$this->_KEY_NAME] = $a;
                    }
                }
            }
        } elseif (is_array($arr2) && count($arr2)) {
            $arr_ret = $arr2;
        }

        return $arr_ret;
    }

    //---------------------------------------------------------
    // sanitize
    //---------------------------------------------------------
    public function sanitize_control_code($str)
    {
        $str = $this->str_replace_control_code($str);
        $str = $this->str_replace_tab_code($str);
        $str = $this->str_replace_return_code($str);
        return $str;
    }

    // --- class end ---
}
