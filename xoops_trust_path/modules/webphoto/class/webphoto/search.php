<?php
// $Id: search.php,v 1.5 2010/11/04 02:23:19 ohwada Exp $

//=========================================================
// webphoto module
// 2010-01-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-11-03 K.OHWADA
// build_rows_for_rss()
// 2010-05-10 K.OHWADA
// build_total_for_detail()
// 2010-02-20 K.OHWADA
// build_lang_keytooshort()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_search
//=========================================================

/**
 * Class webphoto_search
 */
class webphoto_search extends webphoto_base_this
{
    public $_public_class;
    public $_search_class;

    public $_min_keyword;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_search constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_public_class = webphoto_photo_public::getInstance($dirname, $trust_dirname);

        $this->_search_class = webphoto_lib_search::getInstance();

        $this->_min_keyword = $this->_search_class->get_xoops_config_search_keyword_min();

        $this->_search_class->set_lang_zenkaku($this->get_constant('SR_ZENKAKU'));
        $this->_search_class->set_lang_hankaku($this->get_constant('SR_HANKAKU'));
        $this->_search_class->set_min_keyword($this->_min_keyword);
        $this->_search_class->set_is_japanese($this->_is_japanese);
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_lib_error|\webphoto_search
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
    // detail
    //---------------------------------------------------------

    /**
     * @param $query
     * @return array
     */
    public function build_total_for_detail($query)
    {
        $title = _SR_SEARCH;
        $total = 0;

        $sql_query = $this->build_sql_query($query);
        if (!$sql_query) {
            return [$sql_query, $title, $total];
        }

        $total = $this->_public_class->get_count_by_search($sql_query);
        if ($total > 0) {
            $title = _SR_SEARCH . ' : ' . $this->_search_class->get_query_raw('s');
        }

        return [$sql_query, $title, $total];
    }

    /**
     * @param $query
     * @return null|string
     */
    public function build_sql_query($query)
    {
        $this->_search_class->get_post_get_param();
        $this->_search_class->set_query($query);

        $ret = $this->_search_class->parse_query();
        if (!$ret) {
            return null;
        }

        $sql_query = $this->_search_class->build_sql_query('item_search');

        return $sql_query;
    }

    /**
     * @param     $sql_query
     * @param     $orderby
     * @param int $limit
     * @param int $start
     * @return array|bool
     */
    public function build_rows_for_detail($sql_query, $orderby, $limit = 0, $start = 0)
    {
        return $this->_public_class->get_rows_by_search_orderby($sql_query, $orderby, $limit, $start);
    }

    /**
     * @param $total
     * @return array
     */
    public function build_query_param($total)
    {
        $param = $this->_search_class->get_query_param();
        $param['show_search'] = true;
        $param['lang_keytooshort'] = $this->build_lang_keytooshort();
        $param['show_lang_keytooshort'] = $this->build_show_lang_keytooshort($total, $param);

        return $param;
    }

    /**
     * @return string
     */
    public function build_lang_keytooshort()
    {
        $str = sprintf($this->get_constant('SEARCH_KEYTOOSHORT'), $this->_min_keyword);

        return $str;
    }

    /**
     * @param $total
     * @param $param
     * @return bool
     */
    public function build_show_lang_keytooshort($total, $param)
    {
        if ($param['search_query'] && (0 == $total)) {
            return true;
        }

        return false;
    }

    //---------------------------------------------------------
    // rss
    //---------------------------------------------------------

    /**
     * @param     $query
     * @param     $orderby
     * @param int $limit
     * @param int $start
     * @return array|bool|null
     */
    public function build_rows_for_rss($query, $orderby, $limit = 0, $start = 0)
    {
        $sql_query = $this->build_sql_query($query);
        if (!$sql_query) {
            return null;
        }

        return $this->build_rows_for_detail($sql_query, $orderby, $limit, $start);
    }

    // --- class end ---
}
