<?php
// $Id: pagenavi.php,v 1.3 2010/05/10 10:34:49 ohwada Exp $

//=========================================================
// webphoto module
// 2010-01-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-05-10 K.OHWADA
// calc_navi_page()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_pagenavi
//=========================================================

/**
 * Class webphoto_pagenavi
 */
class webphoto_pagenavi extends webphoto_base_this
{
    public $_pagenavi_class;

    public $_FLAG_DEBUG_TRACE = false;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_pagenavi constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_pagenavi_class = webphoto_lib_pagenavi::getInstance();
        $this->_pagenavi_class->set_mark_id_prev('<b>' . $this->get_constant('NAVI_PREVIOUS') . '</b>');
        $this->_pagenavi_class->set_mark_id_next('<b>' . $this->get_constant('NAVI_NEXT') . '</b>');

        $cfg_use_pathinfo = $this->_config_class->get_by_name('use_pathinfo');

        // separator
        if ($cfg_use_pathinfo) {
            $this->_pagenavi_class->set_separator_path('/');
            $this->_pagenavi_class->set_separator_query('/');
        }

        if ($this->_is_module_admin && error_reporting()) {
            $this->_pagenavi_class->set_flag_debug_msg(true);
            $this->_pagenavi_class->set_flag_debug_trace($this->_FLAG_DEBUG_TRACE);
        }
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_lib_error|\webphoto_pagenavi
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
    // pagenavi class
    //---------------------------------------------------------

    /**
     * @param $mode
     * @param $total
     * @param $param_out
     * @param $sort
     * @param $kind
     * @param $page
     * @param $limit
     * @return array
     */
    public function build_navi($mode, $total, $param_out, $sort, $kind, $page, $limit)
    {
        $url = $this->_uri_class->build_navi_url($mode, $param_out, $sort, $kind);

        $navi_page = $this->build_navi_page($url, $page, $limit, $total);
        $navi_info = $this->build_navi_info($page, $limit, $total);

        $arr = [
            'navi_page' => $navi_page,
            'navi_info' => $navi_info,
        ];

        return $arr;
    }

    /**
     * @param $url
     * @param $page
     * @param $limit
     * @param $total
     * @return string
     */
    public function build_navi_page($url, $page, $limit, $total)
    {
        return $this->_pagenavi_class->build($url, $page, $limit, $total);
    }

    /**
     * @param $page
     * @param $limit
     * @param $total
     * @return string
     */
    public function build_navi_info($page, $limit, $total)
    {
        $start = $this->calc_navi_start($page, $limit);
        $end = $this->calc_navi_end($start, $limit, $total);

        return sprintf($this->get_constant('S_NAVINFO'), $start + 1, $end, $total);
    }

    /**
     * @param $page
     * @param $limit
     * @param $total
     * @return bool|int
     */
    public function calc_navi_page($page, $limit, $total)
    {
        return $this->_pagenavi_class->calc_page($page, $limit, $total);
    }

    /**
     * @param $page
     * @param $limit
     * @return bool|float|int
     */
    public function calc_navi_start($page, $limit)
    {
        return $this->_pagenavi_class->calc_start($page, $limit);
    }

    /**
     * @param $start
     * @param $limit
     * @param $total
     * @return bool|int
     */
    public function calc_navi_end($start, $limit, $total)
    {
        return $this->_pagenavi_class->calc_end($start, $limit, $total);
    }

    // --- class end ---
}
