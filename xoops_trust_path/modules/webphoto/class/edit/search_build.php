<?php
// $Id: search_build.php,v 1.3 2010/01/25 10:03:07 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-01-10 K.OHWADA
// webphoto_tag -> webphoto_tag_build
// 2009-11-11 K.OHWADA
// $trust_dirname
// 2009-01-10 K.OHWADA
// webphoto_photo_build -> webphoto_edit_search_build
// get_text_content()
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_edit_search_build
//=========================================================

/**
 * Class webphoto_edit_search_build
 */
class webphoto_edit_search_build
{
    public $_item_handler;
    public $_fileHandler;
    public $_catHandler;
    public $_synoHandler;
    public $_tag_build_class;

    public $_DIRNAME;
    public $_MODULE_URL;
    public $_MODULE_DIR;

    public $_SPACE = ' ';

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_edit_search_build constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        $this->_DIRNAME = $dirname;
        $this->_MODULE_URL = XOOPS_URL . '/modules/' . $dirname;
        $this->_MODULE_DIR = XOOPS_ROOT_PATH . '/modules/' . $dirname;

        $this->_item_handler = webphoto_item_handler::getInstance($dirname, $trust_dirname);
        $this->_catHandler = webphoto_cat_handler::getInstance($dirname, $trust_dirname);
        $this->_fileHandler = webphoto_file_handler::getInstance($dirname, $trust_dirname);
        $this->_synoHandler = webphoto_synoHandler::getInstance($dirname, $trust_dirname);
        $this->_tag_build_class = webphoto_tag_build::getInstance($dirname, $trust_dirname);
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_edit_search_build
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
    // insert
    //---------------------------------------------------------

    /**
     * @param $row
     * @return string
     */
    public function build_with_tag($row)
    {
        $tag_array = $this->_tag_build_class->get_tag_name_array_by_photoid($row['item_id']);

        return $this->build_search($row, $tag_array);
    }

    /**
     * @param      $row
     * @param null $tag_name_array
     * @return mixed
     */
    public function build_row($row, $tag_name_array = null)
    {
        $row['item_search'] = $this->build_search($row, $tag_name_array);

        return $row;
    }

    /**
     * @param      $row
     * @param null $tag_name_array
     * @return string
     */
    public function build_search($row, $tag_name_array = null)
    {
        $str = $this->_item_handler->build_search($row);
        $str .= $this->get_category($row);
        $str .= $this->get_tag($tag_name_array);
        $str .= $this->get_synonym();

        return $str;
    }

    /**
     * @param $row
     * @return string
     */
    public function get_category($row)
    {
        $str = $this->_SPACE;
        $cat_rows = $this->_catHandler->get_parent_path_array($row['item_cat_id']);
        foreach ($cat_rows as $cat_row) {
            $str .= $cat_row['cat_title'] . $this->_SPACE;
        }

        return $str;
    }

    /**
     * @param $tag_name_array
     * @return string
     */
    public function get_tag($tag_name_array)
    {
        if (!is_array($tag_name_array) || !count($tag_name_array)) {
            return '';
        }

        $str = $this->_SPACE;
        foreach ($tag_name_array as $tag_name) {
            $str .= $tag_name . $this->_SPACE;
        }

        return $str;
    }

    /**
     * @return string
     */
    public function get_synonym()
    {
        $syno_rows = $this->_synoHandler->get_rows_orderby_weight_asc();
        if (!is_array($syno_rows) || !count($syno_rows)) {
            return '';
        }

        $str = $this->_SPACE;
        foreach ($syno_rows as $syno_row) {
            $key = $syno_row['syno_key'];
            $val = $syno_row['syno_value'];
            if ((mb_strpos($str, $key) > 0) && (false === mb_strpos($str, $val))) {
                $str .= $val . $this->_SPACE;
            }
        }

        return $str;
    }

    // --- class end ---
}
