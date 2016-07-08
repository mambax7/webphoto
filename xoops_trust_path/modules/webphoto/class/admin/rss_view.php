<?php
// $Id: rss_view.php,v 1.1 2009/03/06 03:54:16 ohwada Exp $

//=========================================================
// webphoto module
// 2009-03-01 K.OHWADA
//=========================================================

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_admin_rss_view
//=========================================================
class webphoto_admin_rss_view extends webphoto_rss
{

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);
    }

    public static function getInstance($dirname = null, $trust_dirname = null)
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new webphoto_admin_rss_view($dirname, $trust_dirname);
        }
        return $instance;
    }

    //---------------------------------------------------------
    // main
    //---------------------------------------------------------
    public function main()
    {
        $this->view_rss();
    }

    // --- class end ---
}
