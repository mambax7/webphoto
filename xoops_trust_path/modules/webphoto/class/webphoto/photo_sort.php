<?php
// $Id: photo_sort.php,v 1.7 2010/11/04 02:23:19 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-11-03 K.OHWADA
// input_to_mode()
// 2010-01-10 K.OHWADA
// get_photo_kind_name()
// 2009-11-11 K.OHWADA
// picture
// 2009-03-15 K.OHWADA
// timeline
// 2008-10-01 K.OHWADA
// photo_sort_array_admin()
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_photo_sort
//=========================================================
class webphoto_photo_sort
{
    public $_config_class;
    public $_ini_class;

    public $_DIRNAME       = null;
    public $_TRUST_DIRNAME = null;
    public $_MODULE_URL;
    public $_MODULE_DIR;
    public $_TRUST_DIR;

    public $_PHOTO_SORT_ARRAY;

    public $_PHOTO_SORT_DEFAULT;
    public $_ORDERBY_RANDOM = 'rand()';

    public $_MODE_DEFAULT;
    public $_SORT_TO_ORDER_ARRAY;
    public $_MODE_TO_KIND_ARRAY;
    public $_MODE_TO_SORT_ARRAY;
    public $_KIND_TO_NAME_ARRAY;
    public $_PHOTO_KIND_ARRAY;
    public $_NAME_DEFAULT;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct($dirname, $trust_dirname)
    {
        $this->_config_class = webphoto_config::getInstance($dirname);

        $this->_ini_class = webphoto_inc_ini::getSingleton($dirname, $trust_dirname);
        $this->_ini_class->read_main_ini();

        $this->set_trust_dirname($trust_dirname);
        $this->_init_d3_language($dirname, $trust_dirname);

        $cfg_sort = $this->_config_class->get_by_name('sort');
        $this->set_photo_sort_default($cfg_sort);

        $this->_MODE_DEFAULT = $this->_ini_class->get_ini('view_mode_default');

        $this->_SORT_TO_ORDER_ARRAY       = $this->_ini_class->hash_ini('sort_to_order');
        $this->_SORT_TO_ORDER_ADMIN_ARRAY = $this->_ini_class->hash_ini('sort_to_order_admin');
        $this->_MODE_TO_KIND_ARRAY        = $this->_ini_class->hash_ini('mode_to_kind');
        $this->_MODE_TO_SORT_ARRAY        = $this->_ini_class->hash_ini('mode_to_sort');
        $this->_KIND_TO_NAME_ARRAY        = $this->_ini_class->hash_ini('kind_to_name');
        $this->_NAME_DEFAULT              = $this->_ini_class->get_ini('name_default');

        $this->_PHOTO_KIND_ARRAY = array_keys($this->_KIND_TO_NAME_ARRAY);
    }

    public static function getInstance($dirname = null, $trust_dirname = null)
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new webphoto_photo_sort($dirname, $trust_dirname);
        }
        return $instance;
    }

    //---------------------------------------------------------
    // init
    //---------------------------------------------------------
    public function init_for_admin()
    {
        $this->_SORT_TO_ORDER_ARRAY = $this->_SORT_TO_ORDER_ADMIN_ARRAY;
    }

    //---------------------------------------------------------
    // mode
    //---------------------------------------------------------
    public function input_to_mode($mode_input)
    {
        $mode_orig = $mode_input;

        switch ($mode_input) {
            case 'latest':
            case 'popular':
            case 'highrate':
            case 'random':
            case 'map':
            case 'timeline':
                //      case 'new':
            case 'picture':
            case 'video':
            case 'audio':
            case 'office':
            case 'category':
            case 'date':
            case 'place':
            case 'tag':
            case 'user':
            case 'search':
            case 'photo':
                $mode = $mode_orig;
                break;

            case 'myphoto':
                $mode = 'user';
                break;

            default:
                $mode      = $this->_MODE_DEFAULT;
                $mode_orig = $this->_MODE_DEFAULT;
                break;
        }

        return array($mode, $mode_orig);
    }

    public function input_to_param($mode, $input, $second, $cat_id, $uid, $my_uid)
    {
        $p = $input;

        switch ($mode) {
            case 'category':
                $p = $cat_id;
                break;

            case 'user':
                $p = $uid;
                break;

            case 'myphoto':
                $p = $my_uid;
                break;

            case 'tag':
            case 'date':
            case 'place':
            case 'search':
                $p = $second;
                break;
        }

        return $p;
    }

    public function input_to_param_for_rss($mode, $input)
    {
        $second = $input;
        $cat_id = $input;
        $uid    = $input;
        $my_uid = $input;

        return $this->input_to_param($mode, $input, $second, $cat_id, $uid, $my_uid);
    }

    public function mode_to_orderby($mode, $sort_in)
    {
        $sort = $this->mode_to_sort($mode);
        if (empty($sort)) {
            $sort = $this->get_photo_sort_name($sort_in, true);
        }
        return $this->sort_to_orderby($sort);
    }

    public function mode_to_name($mode)
    {
        $kind = $this->mode_to_kind($mode);
        return $this->kind_to_name($kind);
    }

    public function mode_to_kind($mode)
    {
        if (isset($this->_MODE_TO_KIND_ARRAY[$mode])) {
            return $this->_MODE_TO_KIND_ARRAY[$mode];
        }
        return null;
    }

    public function mode_to_sort($mode)
    {
        if (isset($this->_MODE_TO_SORT_ARRAY[$mode])) {
            return $this->_MODE_TO_SORT_ARRAY[$mode];
        }
        return null;
    }

    //---------------------------------------------------------
    // photo sort
    //---------------------------------------------------------
    public function get_sort_to_order_array()
    {
        return $this->_SORT_TO_ORDER_ARRAY;
    }

    public function sort_to_orderby($sort)
    {
        $order = null;
        if (isset($this->_SORT_TO_ORDER_ARRAY[$sort])) {
            $order = $this->_SORT_TO_ORDER_ARRAY[$sort];
        } elseif (isset($this->_SORT_TO_ORDER_ARRAY[$this->_PHOTO_SORT_DEFAULT])) {
            $order = $this->_SORT_TO_ORDER_ARRAY[$this->_PHOTO_SORT_DEFAULT];
        }

        if (($order != 'item_id DESC') && ($order != 'rand()')) {
            $order = $order . ', item_id DESC';
        }
        return $order;
    }

    public function sort_to_lang($sort)
    {
        return $this->get_constant('sort_' . $sort);
    }

    public function get_lang_sortby($sort)
    {
        return sprintf($this->get_constant('SORT_S_CURSORTEDBY'), $this->sort_to_lang($sort));
    }

    public function get_photo_sort_name($name, $flag = false)
    {
        if ($name && isset($this->_SORT_TO_ORDER_ARRAY[$name])) {
            return $name;
        } elseif ($flag && isset($this->_SORT_TO_ORDER_ARRAY[$this->_PHOTO_SORT_DEFAULT])) {
            return $this->_PHOTO_SORT_DEFAULT;
        }
        return false;
    }

    public function set_photo_sort_default($val)
    {
        $this->_PHOTO_SORT_DEFAULT = $val;
    }

    public function get_random_orderby()
    {
        return $this->_ORDERBY_RANDOM;
    }

    //---------------------------------------------------------
    // kind
    //---------------------------------------------------------
    public function kind_to_name($kind)
    {
        if (isset($this->_KIND_TO_NAME_ARRAY[$kind])) {
            return $this->_KIND_TO_NAME_ARRAY[$kind];
        }
        return $this->_NAME_DEFAULT;
    }

    public function get_photo_kind_name($name)
    {
        if ($name && in_array($name, $this->_PHOTO_KIND_ARRAY)) {
            return $name;
        }
        return null;
    }

    //---------------------------------------------------------
    // join sql
    //---------------------------------------------------------
    public function convert_orderby_join($str)
    {
        return str_replace('item_', 'i.item_', $str);
    }

    //---------------------------------------------------------
    // d3 language
    //---------------------------------------------------------
    public function _init_d3_language($dirname, $trust_dirname)
    {
        $this->_language_class = webphoto_d3_language::getInstance();
        $this->_language_class->init($dirname, $trust_dirname);
    }

    public function get_lang_array()
    {
        return $this->_language_class->get_lang_array();
    }

    public function get_constant($name)
    {
        return $this->_language_class->get_constant($name);
    }

    public function set_trust_dirname($trust_dirname)
    {
        $this->_TRUST_DIRNAME = $trust_dirname;
        $this->_TRUST_DIR     = XOOPS_TRUST_PATH . '/modules/' . $trust_dirname;
    }

    // --- class end ---
}
