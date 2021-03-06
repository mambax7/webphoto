<?php
// $Id: photo_build.php,v 1.2 2008/08/25 19:28:05 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_photo_build
//=========================================================

/**
 * Class webphoto_photo_build
 */
class webphoto_photo_build extends webphoto_lib_error
{
    public $_item_handler;
    public $_catHandler;
    public $_synoHandler;

    public $_DIRNAME;
    public $_MODULE_URL;
    public $_MODULE_DIR;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_photo_build constructor.
     * @param $dirname
     */
    public function __construct($dirname)
    {
        parent::__construct();

        $this->_DIRNAME = $dirname;
        $this->_MODULE_URL = XOOPS_URL . '/modules/' . $dirname;
        $this->_MODULE_DIR = XOOPS_ROOT_PATH . '/modules/' . $dirname;

        $this->_item_handler = webphoto_item_handler::getInstance($dirname);
        $this->_catHandler = webphoto_cat_handler::getInstance($dirname);
        $this->_synoHandler = webphoto_synoHandler::getInstance($dirname);
    }

    /**
     * @param null $dirname
     * @return \webphoto_lib_error|\webphoto_photo_build
     */
    public static function getInstance($dirname = null)
    {
        static $instance;
        if (null === $instance) {
            $instance = new self($dirname);
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
    public function build_search_with_tag($row)
    {
        $tag_class = webphoto_tag::getInstance($this->_DIRNAME);

        return $this->build_search($row, $tag_class->get_tag_name_array_by_photoid($row['item_id']));
    }

    /**
     * @param      $row
     * @param null $tag_name_array
     * @return string
     */
    public function build_search($row, $tag_name_array = null)
    {
        $str = $this->_item_handler->build_search($row);
        $str .= ' ';

        // add category
        $cat_rows = $this->_catHandler->get_parent_path_array($row['item_cat_id']);
        foreach ($cat_rows as $cat_row) {
            $str .= $cat_row['cat_title'] . ' ';
        }

        // add tag
        if (is_array($tag_name_array) && count($tag_name_array)) {
            foreach ($tag_name_array as $tag_name) {
                $str .= $tag_name . ' ';
            }
        }

        // add synonym
        $syno_rows = $this->_synoHandler->get_rows_orderby_weight_asc();
        if (is_array($syno_rows) && count($syno_rows)) {
            foreach ($syno_rows as $syno_row) {
                $key = $syno_row['syno_key'];
                $val = $syno_row['syno_value'];
                if ((mb_strpos($str, $key) > 0) && (false === mb_strpos($str, $val))) {
                    $str .= $val . ' ';
                }
            }
        }

        return $str;
    }

    // --- class end ---
}
