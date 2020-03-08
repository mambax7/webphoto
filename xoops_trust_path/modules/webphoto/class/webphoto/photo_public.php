<?php
// $Id: photo_public.php,v 1.14 2011/12/28 16:16:15 ohwada Exp $

//=========================================================
// webphoto module
// 2008-12-12 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-12-25 K.OHWADA
// change get_rows_by_gmap_catid_array()
// 2011-06-04 K.OHWADA
// remove cfg_use_pathinfo
// 2010-03-14 K.OHWADA
// set_perm_cat_read()
// 2010-01-10 K.OHWADA
// remove build_tagcloud()
// 2009-11-11 K.OHWADA
// $trust_dirname
// 2009-09-06 K.OHWADA
// add ns ew in get_rows_by_gmap_area()
// 2009-05-17 K.OHWADA
// _cfg_cat_child
// 2009-04-10 K.OHWADA
// add $key in get_rows_by_orderby()
// 2009-01-25 K.OHWADA
// remove catlist->set_perm_cat_read()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_photo_public
//=========================================================

/**
 * Class webphoto_photo_public
 */
class webphoto_photo_public
{
    public $_config_class;
    public $_item_catHandler;

    public $_cfg_perm_cat_read;

    public $_ORDERBY_ASC = 'item_id ASC';
    public $_ORDERBY_LATEST = 'item_time_update DESC, item_id DESC';

    // show
    public $_SHOW_CAT_SUB = true;
    public $_SHOW_CAT_MAIN_IMG = true;
    public $_SHOW_CAT_SUB_IMG = true;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_photo_public constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        $this->_item_catHandler = webphoto_item_catHandler::getInstance($dirname, $trust_dirname);

        $this->_config_class = webphoto_config::getInstance($dirname);

        $this->_cfg_perm_cat_read = $this->_config_class->get_by_name('perm_cat_read');
        $cfg_perm_item_read = $this->_config_class->get_by_name('perm_item_read');

        $this->_item_catHandler->set_perm_item_read($cfg_perm_item_read);
        $this->_item_catHandler->set_perm_cat_read($this->_cfg_perm_cat_read);
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_photo_public
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
    // count
    //---------------------------------------------------------

    /**
     * @return int
     */
    public function get_count()
    {
        return $this->get_count_by_name_param('public', null);
    }

    /**
     * @return int
     */
    public function get_count_imode()
    {
        return $this->get_count_by_name_param('imode', null);
    }

    /**
     * @param $param
     * @return int
     */
    public function get_count_by_catid_array($param)
    {
        return $this->get_count_by_name_param('catid_array', $param);
    }

    /**
     * @param $param
     * @return int
     */
    public function get_count_by_like_datetime($param)
    {
        return $this->get_count_by_name_param('like_datetime', $param);
    }

    /**
     * @param $param
     * @return int
     */
    public function get_count_by_place($param)
    {
        return $this->get_count_by_name_param('place', $param);
    }

    /**
     * @param $param
     * @return int
     */
    public function get_count_by_place_array($param)
    {
        return $this->get_count_by_name_param('place_array', $param);
    }

    /**
     * @param $param
     * @return int
     */
    public function get_count_by_search($param)
    {
        return $this->get_count_by_name_param('search', $param);
    }

    /**
     * @param $param
     * @return int
     */
    public function get_count_by_uid($param)
    {
        return $this->get_count_by_name_param('uid', $param);
    }

    /**
     * @param $name
     * @param $param
     * @return int
     */
    public function get_count_by_name_param($name, $param)
    {
        if (_C_WEBPHOTO_OPT_PERM_READ_ALL == $this->_cfg_perm_cat_read) {
            return $this->_item_catHandler->get_count_item_by_name_param($name, $param);
        }

        return $this->_item_catHandler->get_count_item_cat_by_name_param($name, $param);
    }

    //---------------------------------------------------------
    // rows
    //---------------------------------------------------------

    /**
     * @param      $orderby
     * @param int  $limit
     * @param int  $offset
     * @param bool $key
     * @return array|bool
     */
    public function get_rows_by_orderby($orderby, $limit = 0, $offset = 0, $key = false)
    {
        return $this->get_rows_by_name_param_orderby('public', null, $orderby, $limit, $offset, $key);
    }

    /**
     * @param     $orderby
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_rows_imode_by_orderby($orderby, $limit = 0, $offset = 0)
    {
        return $this->get_rows_by_name_param_orderby('imode', null, $orderby, $limit, $offset);
    }

    /**
     * @param     $orderby
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_rows_photo_by_orderby($orderby, $limit = 0, $offset = 0)
    {
        return $this->get_rows_by_name_param_orderby('photo', null, $orderby, $limit, $offset);
    }

    /**
     * @param     $param
     * @param     $orderby
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_rows_photo_by_catid_orderby($param, $orderby, $limit = 0, $offset = 0)
    {
        return $this->get_rows_by_name_param_orderby('photo_catid', $param, $orderby, $limit, $offset);
    }

    /**
     * @param     $param
     * @param     $orderby
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_rows_by_catid_array_orderby($param, $orderby, $limit = 0, $offset = 0)
    {
        return $this->get_rows_by_name_param_orderby('catid_array', $param, $orderby, $limit, $offset);
    }

    /**
     * @param     $param
     * @param     $orderby
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_rows_by_like_datetime_orderby($param, $orderby, $limit = 0, $offset = 0)
    {
        return $this->get_rows_by_name_param_orderby('like_datetime', $param, $orderby, $limit, $offset);
    }

    /**
     * @param     $param
     * @param     $orderby
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_rows_by_place_orderby($param, $orderby, $limit = 0, $offset = 0)
    {
        return $this->get_rows_by_name_param_orderby('place', $param, $orderby, $limit, $offset);
    }

    /**
     * @param     $param
     * @param     $orderby
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_rows_by_place_array_orderby($param, $orderby, $limit = 0, $offset = 0)
    {
        return $this->get_rows_by_name_param_orderby('place_array', $param, $orderby, $limit, $offset);
    }

    /**
     * @param     $param
     * @param     $orderby
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_rows_by_uid_orderby($param, $orderby, $limit = 0, $offset = 0)
    {
        return $this->get_rows_by_name_param_orderby('uid', $param, $orderby, $limit, $offset);
    }

    /**
     * @param     $param
     * @param     $orderby
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_rows_by_search_orderby($param, $orderby, $limit = 0, $offset = 0)
    {
        return $this->get_rows_by_name_param_orderby('search', $param, $orderby, $limit, $offset);
    }

    /**
     * @param     $catid_array
     * @param     $orderby
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_rows_by_gmap_catid_array($catid_array, $orderby, $limit = 0, $offset = 0)
    {
        return $this->get_rows_by_name_param_orderby('gmap_catid_array', $catid_array, $orderby, $limit, $offset);
    }

    /**
     * @param int  $limit
     * @param int  $offset
     * @param bool $key
     * @return array|bool
     */
    public function get_rows_by_gmap_latest($limit = 0, $offset = 0, $key = false)
    {
        return $this->get_rows_by_name_param_orderby('gmap_latest', null, $this->_ORDERBY_LATEST, $limit, $offset, $key);
    }

    /**
     * @param      $id
     * @param      $lat
     * @param      $lon
     * @param      $ns
     * @param      $ew
     * @param int  $limit
     * @param int  $offset
     * @param bool $key
     * @return array|bool
     */
    public function get_rows_by_gmap_area($id, $lat, $lon, $ns, $ew, $limit = 0, $offset = 0, $key = false)
    {
        return $this->get_rows_by_name_param_orderby('gmap_area', [$id, $lat, $lon, $ns, $ew], $this->_ORDERBY_ASC, $limit, $offset, $key);
    }

    /**
     * @param      $name
     * @param      $param
     * @param      $orderby
     * @param int  $limit
     * @param int  $offset
     * @param bool $key
     * @return array|bool
     */
    public function get_rows_by_name_param_orderby($name, $param, $orderby, $limit = 0, $offset = 0, $key = false)
    {
        $item_key = null;
        if ($key) {
            $item_key = 'item_id';
        }

        if (_C_WEBPHOTO_OPT_PERM_READ_ALL == $this->_cfg_perm_cat_read) {
            return $this->_item_catHandler->get_rows_item_by_name_param_orderby($name, $param, $orderby, $limit, $offset, $item_key);
        }

        return $this->_item_catHandler->get_rows_item_cat_by_name_param_orderby($name, $param, $this->_item_catHandler->convert_item_field($orderby), $limit, $offset, $this->_item_catHandler->convert_item_field($item_key));
    }

    //---------------------------------------------------------
    // get id array
    //---------------------------------------------------------

    /**
     * @param     $param
     * @param     $orderby
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_id_array_by_catid_orderby($param, $orderby, $limit = 0, $offset = 0)
    {
        return $this->_item_catHandler->get_id_array_item_by_name_param_orderby('catid', $param, $orderby, $limit, $offset);
    }

    // --- class end ---
}
