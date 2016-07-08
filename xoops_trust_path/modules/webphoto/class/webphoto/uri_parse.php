<?php
// $Id: uri_parse.php,v 1.1 2010/11/04 02:24:16 ohwada Exp $

//=========================================================
// webphoto module
// 2010-11-03 K.OHWADA
//=========================================================

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_uri_parse
//=========================================================
class webphoto_uri_parse
{
    public $_sort_class;
    public $_pathinfo_class;
    public $_xoops_class;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct($dirname, $trust_dirname)
    {
        $this->_sort_class = webphoto_photo_sort::getInstance($dirname, $trust_dirname);

        $this->_xoops_class    = webphoto_xoops_base::getInstance();
        $this->_pathinfo_class = webphoto_lib_pathinfo::getInstance();
    }

    public static function getInstance($dirname = null, $trust_dirname = null)
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new webphoto_uri_parse($dirname, $trust_dirname);
        }
        return $instance;
    }

    //---------------------------------------------------------
    // factory
    //---------------------------------------------------------
    public function get_page_mode()
    {
        $input = $this->_pathinfo_class->get_fct_op_0();
        return $this->_sort_class->input_to_mode($input);
    }

    public function get_get_page()
    {
        return $this->_pathinfo_class->get_page();
    }

    public function get_get_sort()
    {
        return $this->_sort_class->get_photo_sort_name($this->_pathinfo_class->get_text('sort'));
    }

    public function get_get_kind()
    {
        return $this->_sort_class->get_photo_kind_name($this->_pathinfo_class->get_text('kind'));
    }

    public function get_sort_orderby()
    {
        $get_sort = $this->_sort_class->get_photo_sort_name($this->_pathinfo_class->get_text('sort'));
        $sort     = $this->_sort_class->get_photo_sort_name($get_sort, true);
        $orderby  = $this->_sort_class->sort_to_orderby($sort);

        $arr = array(
            'get_sort' => $get_sort,
            'sort'     => $sort,
            'orderby'  => $orderby,
        );
        return $arr;
    }

    public function get_param_by_mode($mode)
    {
        $input       = $this->get_pathinfo_param();
        $isset       = $this->isset_pathinfo_param();
        $path_second = $this->get_pathinfo_path_second();

        $second = $this->get_second($input, $path_second);
        $uid    = $this->get_uid($input, $isset, $path_second);
        $cat_id = $this->get_id_by_key('cat_id');
        $my_uid = $this->_xoops_class->get_my_user_uid();

        return $this->_sort_class->input_to_param($mode, $input, $second, $cat_id, $uid, $my_uid);
    }

    public function get_uid($input, $isset, $path_second)
    {
        $uid = _C_WEBPHOTO_UID_DEFAULT; // not set
        if ($isset) {
            $uid = $input;
        } elseif (!$isset && ($path_second !== false)) {
            $uid = (int)$path_second;
        }
        return $uid;
    }

    public function get_second($input, $path_second)
    {
        if ($input) {
            $ret = $input;
        } else {
            $ret = $path_second;
        }
        return $ret;
    }

    //---------------------------------------------------------
    // photo
    //---------------------------------------------------------
    public function get_photo_orderby()
    {
        return $this->_sort_class->sort_to_orderby($this->get_by_key('order'));
    }

    public function get_photo_keyword_array()
    {
        $keywords = $this->_pathinfo_class->get_text('keywords');
        $arr      = preg_split("/[\s|\+]/", $keywords);
        return array_unique($arr);
    }

    //---------------------------------------------------------
    // utility
    //---------------------------------------------------------
    public function get_id_by_key($id_name)
    {
        // POST
        $id = isset($_POST[$id_name]) ? (int)$_POST[$id_name] : 0;
        if ($id > 0) {
            return $id;
        }

        // GET
        $id = isset($_GET[$id_name]) ? (int)$_GET[$id_name] : 0;
        if ($id > 0) {
            return $id;
        }

        // PATH_INFO
        $id = (int)$this->get_pathinfo_param();
        if ($id > 0) {
            return $id;
        }

        $id = (int)$this->get_pathinfo_path_second();
        return $id;
    }

    public function isset_pathinfo_param()
    {
        return $this->_pathinfo_class->isset_param(_C_WEBPHOTO_URI_PARAM_NAME);
    }

    public function get_pathinfo_param()
    {
        return $this->_pathinfo_class->get(_C_WEBPHOTO_URI_PARAM_NAME);
    }

    public function get_pathinfo_path_second()
    {
        return $this->_pathinfo_class->get_path(_C_WEBPHOTO_URI_PATH_SECOND);
    }

    public function get_by_key($key)
    {
        return $this->_pathinfo_class->get($key);
    }

    public function get_int_by_key($key)
    {
        return $this->_pathinfo_class->get_int($key);
    }

    // --- class end ---
}
