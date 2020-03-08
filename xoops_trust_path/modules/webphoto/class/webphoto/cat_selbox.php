<?php
// $Id: cat_selbox.php,v 1.4 2010/09/19 06:43:11 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-09-17 K.OHWADA
// build_selbox_options()
// 2009-11-11 K.OHWADA
// $trust_dirname
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_cat_selbox
//=========================================================

/**
 * Class webphoto_cat_selbox
 */
class webphoto_cat_selbox
{
    public $_catHandler;
    public $_item_handler;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        // dummy
    }

    /**
     * @return \webphoto_cat_selbox
     */
    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * @param $dirname
     * @param $trust_dirname
     */
    public function init($dirname, $trust_dirname)
    {
        $this->_item_handler = new webphoto_item_handler($dirname, $trust_dirname);
        $this->_catHandler = new webphoto_cat_handler($dirname, $trust_dirname);
    }

    //---------------------------------------------------------
    // selbox
    //---------------------------------------------------------

    /**
     * @param string $order
     * @param int    $preset_id
     * @param string $none_title
     * @param string $sel_name
     * @param string $onchange
     * @return null|string
     */
    public function build_selbox($order = 'cat_title', $preset_id = 0, $none_title = '--', $sel_name = 'cat_id', $onchange = '')
    {
        $options = $this->build_selbox_options($order, $preset_id, $none_title, $sel_name);

        if (empty($options)) {
            return null;    // no action
        }

        $str = '<select name="' . $sel_name . '" ';
        if ('' != $onchange) {
            $str .= ' onchange="' . $onchange . '" ';
        }
        $str .= ">\n";

        $str .= $options;
        $str .= "</select>\n";

        return $str;
    }

    /**
     * @param string $order
     * @param int    $preset_id
     * @param string $none_title
     * @param string $sel_name
     * @return null|string
     */
    public function build_selbox_options($order = 'cat_title', $preset_id = 0, $none_title = '--', $sel_name = 'cat_id')
    {
        $tree = $this->_catHandler->get_all_tree_array($order);
        if (!is_array($tree) || !count($tree)) {
            return null;    // no action
        }

        $str = '';

        if ($none_title) {
            $str .= '<option value="0">' . $none_title . "</option>\n";
        }

        foreach ($tree as $row) {
            $catid = $row['cat_id'];
            $title = $row['cat_title'];
            $prefix = $row['prefix'];

            $num = $this->_item_handler->get_count_by_catid($catid);

            if ($prefix) {
                $prefix = str_replace('.', '--', $prefix) . ' ';
            }

            $sel = '';
            if ($catid == $preset_id) {
                $sel = ' selected="selected" ';
            }

            $str .= '<option value="' . $catid . '" ' . $sel . '>';
            $str .= $prefix . $this->sanitize($title) . ' (' . $num . ')';
            $str .= "</option>\n";
        }

        return $str;
    }

    /**
     * @param $str
     * @return string
     */
    public function sanitize($str)
    {
        return htmlspecialchars($str, ENT_QUOTES);
    }

    // --- class end ---
}
