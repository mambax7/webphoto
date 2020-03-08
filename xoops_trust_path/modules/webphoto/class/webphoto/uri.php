<?php
// $Id: uri.php,v 1.11 2011/06/05 07:23:40 ohwada Exp $

//=========================================================
// webphoto module
// 2008-07-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-06-04 K.OHWADA
// build_photo_id_title()
// 2010-11-03 K.OHWADA
// move get_pathinfo_param() to webphoto_uri_parse
// 2010-01-10 K.OHWADA
// build_navi_url()
// 2009-10-25 K.OHWADA
// build_list_navi_url_kind()
// 2009-03-15 K.OHWADA
// flag_amp_sanitize in build_photo()
// 2008-12-12 K.OHWADA
// getInstance() -> getSingleton()
// 2008-11-29 K.OHWADA
// webphoto_inc_uri
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_uri
//=========================================================

/**
 * Class webphoto_uri
 */
class webphoto_uri extends webphoto_inc_uri
{
    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_uri constructor.
     * @param $dirname
     */
    public function __construct($dirname)
    {
        parent::__construct($dirname);
    }

    /**
     * @param null $dirname
     * @return \webphoto_uri
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
    // buiid uri
    //---------------------------------------------------------

    /**
     * @param        $id
     * @param        $title
     * @param string $target
     * @param bool   $flag_amp_sanitize
     * @param bool   $flag_title_sanitize
     * @return string
     */
    public function build_photo_id_title($id, $title, $target = '_blank', $flag_amp_sanitize = true, $flag_title_sanitize = true)
    {
        $str = $this->build_photo_a_href($id, $target, $flag_amp_sanitize);
        $str .= $id;

        if ($title) {
            $str .= ' : ';
            if ($flag_title_sanitize) {
                $str .= $this->sanitize($title);
            } else {
                $str .= $title;
            }
        }

        $str .= '</a>';

        return $str;
    }

    /**
     * @param        $id
     * @param string $target
     * @param bool   $flag_amp_sanitize
     * @return string
     */
    public function build_photo_id($id, $target = '_blank', $flag_amp_sanitize = true)
    {
        $str = $this->build_photo_a_href($id, $target, $flag_amp_sanitize);
        $str .= $id;
        $str .= '</a>';

        return $str;
    }

    /**
     * @param        $title
     * @param string $target
     * @param bool   $flag_amp_sanitize
     * @param bool   $flag_title_sanitize
     * @return string
     */
    public function build_photo_title($title, $target = '_blank', $flag_amp_sanitize = true, $flag_title_sanitize = true)
    {
        $str = $this->build_photo_a_href($id, $target, $flag_amp_sanitize);
        if ($flag_title_sanitize) {
            $str .= $this->sanitize($title);
        } else {
            $str .= $title;
        }
        $str .= '</a>';

        return $str;
    }

    /**
     * @param        $id
     * @param string $target
     * @param bool   $flag_amp_sanitize
     * @return string
     */
    public function build_photo_a_href($id, $target = '_blank', $flag_amp_sanitize = true)
    {
        $url = $this->build_photo($id, $flag_amp_sanitize);
        if ($target) {
            $str = '<a href="' . $url . '" target="' . $target . '">';
        } else {
            $str = '<a href="' . $url . '>';
        }

        return $str;
    }

    //---------------------------------------------------------
    // buiid uri
    //---------------------------------------------------------

    /**
     * @param $op
     * @return string
     */
    public function build_operate($op)
    {
        if ($this->_cfg_use_pathinfo) {
            $str = $this->_MODULE_URL . '/index.php/' . $this->sanitize($op) . '/';
        } else {
            $str = $this->_MODULE_URL . '/index.php?op=' . $this->sanitize($op);
        }

        return $str;
    }

    /**
     * @return string
     */
    public function build_photo_pagenavi()
    {
        $str = $this->build_full_uri_mode('photo');
        $str .= $this->build_part_uri_param_name();

        return $str;
    }

    /**
     * @param      $id
     * @param bool $flag_amp_sanitize
     * @return string
     */
    public function build_photo($id, $flag_amp_sanitize = true)
    {
        return $this->build_full_uri_mode_param('photo', (int)$id, $flag_amp_sanitize);
    }

    /**
     * @param      $id
     * @param null $param
     * @return string
     */
    public function build_category($id, $param = null)
    {
        $str = $this->build_full_uri_mode_param('category', (int)$id);
        $str .= $this->build_param($param);

        return $str;
    }

    /**
     * @param $id
     * @return string
     */
    public function build_user($id)
    {
        return $this->build_full_uri_mode_param('user', (int)$id);
    }

    /**
     * @param $param
     * @return string
     */
    public function build_param($param)
    {
        return $this->build_uri_extention($param);
    }

    //---------------------------------------------------------
    // buiid uri for show_main
    //---------------------------------------------------------

    /**
     * @param      $mode
     * @param      $param
     * @param      $sort
     * @param      $kind
     * @param null $viewtype
     * @return string
     */
    public function build_navi_url($mode, $param, $sort, $kind, $viewtype = null)
    {
        $str = $this->_MODULE_URL . '/index.php';
        $str .= $this->build_mode_param($mode, $param, true);
        $str .= $this->build_sort($sort);
        $str .= $this->build_kind($kind);
        $str .= $this->build_viewtype($viewtype);

        return $str;
    }

    /**
     * @param      $mode
     * @param      $param
     * @param      $kind
     * @param null $viewtype
     * @return string
     */
    public function build_param_sort($mode, $param, $kind, $viewtype = null)
    {
        $str = $this->build_mode_param($mode, $param, true);
        $str .= $this->build_kind($kind);
        $str .= $this->build_viewtype($viewtype);
        $str .= $this->get_separator();

        return $str;
    }

    /**
     * @param      $mode
     * @param      $param
     * @param bool $flag_head_slash
     * @return string
     */
    public function build_mode_param($mode, $param, $flag_head_slash = false)
    {
        switch ($mode) {
            case 'category':
            case 'user':
                $str_1 = $mode . '/' . (int)$param;
                $str_2 = '?fct=' . $mode . '&amp;p=' . (int)$param;
                break;
            case 'tag':
            case 'date':
            case 'place':
            case 'search':
                $str_1 = $mode . '/' . rawurlencode($param);
                $str_2 = '?fct=' . $mode . '&amp;p=' . rawurlencode($param);
                break;
            default:
                $str_1 = $this->sanitize($mode);
                $str_2 = '?op=' . $this->sanitize($mode);
                break;
        }

        if ($this->_cfg_use_pathinfo) {
            if ($flag_head_slash) {
                $str = '/' . $str_1;
            } else {
                $str = $str_1;
            }
        } else {
            $str = $str_2;
        }

        return $str;
    }

    /**
     * @param $val
     * @return string
     */
    public function build_sort($val)
    {
        return $this->build_param_str('sort', $val);
    }

    /**
     * @param $val
     * @return string
     */
    public function build_kind($val)
    {
        return $this->build_param_str('kind', $val);
    }

    /**
     * @param $val
     * @return string
     */
    public function build_viewtype($val)
    {
        return $this->build_param_str('viewtype', $val);
    }

    /**
     * @param $val
     * @return string
     */
    public function build_page($val)
    {
        return $this->build_param_int('page', $val);
    }

    /**
     * @param $name
     * @param $val
     * @return string
     */
    public function build_param_str($name, $val)
    {
        $str = '';
        if ($val) {
            $str = $this->_SEPARATOR . $name . '=' . $this->sanitize($val);
        }

        return $str;
    }

    /**
     * @param $name
     * @param $val
     * @return string
     */
    public function build_param_int($name, $val)
    {
        $str = '';
        if ($val) {
            $str = $this->_SEPARATOR . $name . '=' . (int)$val;
        }

        return $str;
    }

    //---------------------------------------------------------
    // buiid uri for show_list
    //---------------------------------------------------------

    /**
     * @param $mode
     * @param $param
     * @return string
     */
    public function build_list_link($mode, $param)
    {
        // not sanitize
        if ($this->_cfg_use_pathinfo) {
            $str = 'index.php/' . $mode . '/' . rawurlencode($param) . '/';
        } else {
            $str = 'index.php?fct=' . $mode . '&p=' . rawurlencode($param);
        }

        return $str;
    }

    // --- class end ---
}
