<?php
// $Id: place.php,v 1.3 2010/11/04 02:23:19 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-11-03 K.OHWADA
// build_rows_for_rss()
// 2010-05-10 K.OHWADA
// build_total_for_detail()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_place
//=========================================================

/**
 * Class webphoto_place
 */
class webphoto_place extends webphoto_base_this
{
    public $_public_class;
    public $_search_class;

    public $_PHOTO_LIST_PLACE_ORDER = 'item_place ASC, item_id DESC';
    public $_PHOTO_LIST_PLACE_GROUP = 'item_place';

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_place constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_public_class = webphoto_photo_public::getInstance($dirname, $trust_dirname);

        $this->_search_class = webphoto_lib_search::getInstance();
        $this->_search_class->set_is_japanese($this->_is_japanese);
        $this->_search_class->set_flag_candidate(false);
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_lib_error|\webphoto_place
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
    // list
    //---------------------------------------------------------

    /**
     * @return array|bool
     */
    public function build_rows_for_list()
    {
        $list_rows = $this->_item_handler->get_rows_by_groupby_orderby($this->_PHOTO_LIST_PLACE_GROUP, $this->_PHOTO_LIST_PLACE_ORDER);
        if (!is_array($list_rows) || !count($list_rows)) {
            return false;
        }

        $place_not = _C_WEBPHOTO_PLACE_VALUE_NOT_SET;

        $arr = [];
        foreach ($list_rows as $row) {
            $place = $row['item_place'];

            $photo_row = null;

            $place_arr = $this->str_to_array($place, ' ');
            $place_str = $this->array_to_str($place_arr, ' ');

            if ($place) {
                $title = $place_str;
                $param = $this->_utility_class->encode_slash($place_str);
                $total = $this->_public_class->get_count_by_place_array($place_arr);
                $photo_rows = $this->_public_class->get_rows_by_place_array_orderby($place_arr, $this->_PHOTO_LIST_UPDATE_ORDER, $this->_PHOTO_LIST_LIMIT);
            } else {
                $title = $this->get_constant('PLACE_NOT_SET');
                $param = _C_WEBPHOTO_PLACE_STR_NOT_SET;
                $total = $this->_public_class->get_count_by_place($place_not);
                $photo_rows = $this->_public_class->get_rows_by_place_orderby($place_not, $this->_PHOTO_LIST_UPDATE_ORDER, $this->_PHOTO_LIST_LIMIT);
            }

            if (isset($photo_rows[0])) {
                $photo_row = $photo_rows[0];
            }

            if ($total > 0) {
                $arr[] = [$title, $param, $total, $photo_row];
            }
        }

        return $arr;
    }

    //---------------------------------------------------------
    // page detail
    //---------------------------------------------------------

    /**
     * @param $place_in
     * @return array
     */
    public function build_total_for_detail($place_in)
    {
        list($mode, $place_arr) = $this->get_mode_for_detail($place_in);

        $place = $this->array_to_str($place_arr, ' ');

        // if not set place
        if (1 == $mode) {
            $title = $this->get_constant('PLACE_NOT_SET');
            $total = $this->_public_class->get_count_by_place(_C_WEBPHOTO_PLACE_VALUE_NOT_SET);

        // if set place
        } elseif (2 == $mode) {
            $title = $this->get_constant('PHOTO_PLACE') . ' : ' . $place;
            $total = $this->_public_class->get_count_by_place_array($place_arr);

        // if set nothig
        } else {
            $title = $this->get_constant('PHOTO_PLACE');
            $total = 0;
        }

        return [$mode, $place_arr, $title, $total];
    }

    /**
     * @param $place_in
     * @return array
     */
    public function get_mode_for_detail($place_in)
    {
        $place_str = $this->decode_uri_str($place_in);
        $place_arr = $this->_search_class->query_to_array($place_str);

        $mode = 0;

        // if not set place
        if (_C_WEBPHOTO_PLACE_STR_NOT_SET == $place_str) {
            $mode = 1;

        // if set place
        } elseif (is_array($place_arr) && count($place_arr)) {
            $mode = 2;
        }

        return [$mode, $place_arr];
    }

    /**
     * @param     $mode
     * @param     $place_arr
     * @param     $orderby
     * @param int $limit
     * @param int $start
     * @return array|bool|null
     */
    public function build_rows_for_detail($mode, $place_arr, $orderby, $limit = 0, $start = 0)
    {
        $rows = null;

        switch ($mode) {
            case 2:
                $rows = $this->_public_class->get_rows_by_place_array_orderby($place_arr, $orderby, $limit, $start);
                break;
            case 1:
                $rows = $this->_public_class->get_rows_by_place_orderby(_C_WEBPHOTO_PLACE_VALUE_NOT_SET, $orderby, $limit, $start);
                break;
        }

        return $rows;
    }

    //---------------------------------------------------------
    // rss
    //---------------------------------------------------------

    /**
     * @param     $place_in
     * @param     $orderby
     * @param int $limit
     * @param int $start
     * @return array|bool|null
     */
    public function build_rows_for_rss($place_in, $orderby, $limit = 0, $start = 0)
    {
        list($mode, $place_arr) = $this->get_mode_for_detail($place_in);

        return $this->build_rows_for_detail($mode, $place_arr, $orderby, $limit, $start);
    }

    // --- class end ---
}
