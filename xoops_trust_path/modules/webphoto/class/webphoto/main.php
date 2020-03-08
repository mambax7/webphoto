<?php
// $Id: main.php,v 1.4 2011/12/28 16:16:15 ohwada Exp $

//=========================================================
// webphoto module
// 2010-01-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-12-25 K.OHWADA
// remove build_title()
// 2010-11-03 K.OHWADA
// build_rows_for_rss()
// 2010-05-10 K.OHWADA
// build_total_for_detail()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_main
//=========================================================

/**
 * Class webphoto_main
 */
class webphoto_main extends webphoto_base_this
{
    public $_public_class;
    public $_sort_class;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_main constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_public_class = webphoto_photo_public::getInstance($dirname, $trust_dirname);
        $this->_sort_class = webphoto_photo_sort::getInstance($dirname, $trust_dirname);
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_lib_error|\webphoto_main
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
     * @param $mode
     * @return array
     */
    public function build_total_for_detail($mode)
    {
        $title = $this->build_title_by_mode($mode);
        $name = $this->_sort_class->mode_to_name($mode);
        $total = $this->_public_class->get_count_by_name_param($name, null);

        return [$title, $total];
    }

    /**
     * @param     $mode
     * @param     $sort
     * @param int $limit
     * @param int $start
     * @return array|bool
     */
    public function build_rows_for_detail($mode, $sort, $limit = 0, $start = 0)
    {
        $name = $this->_sort_class->mode_to_name($mode);
        $orderby = $this->_sort_class->mode_to_orderby($mode, $sort);

        return $this->_public_class->get_rows_by_name_param_orderby($name, null, $orderby, $limit, $start);
    }

    //---------------------------------------------------------
    // rss
    //---------------------------------------------------------

    /**
     * @param     $mode
     * @param int $limit
     * @param int $start
     * @return array|bool
     */
    public function build_rows_for_rss($mode, $limit = 0, $start = 0)
    {
        $sort = null;

        return $this->build_rows_for_detail($mode, $sort, $limit, $start);
    }

    // --- class end ---
}
