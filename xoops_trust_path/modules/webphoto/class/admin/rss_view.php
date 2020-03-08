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

/**
 * Class webphoto_admin_rss_view
 */
class webphoto_admin_rss_view extends webphoto_rss
{
    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_admin_rss_view constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_admin_rss_view|\webphoto_lib_rss|\webphoto_lib_xml|\webphoto_rss
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
    // main
    //---------------------------------------------------------
    public function main()
    {
        $this->view_rss();
    }

    // --- class end ---
}
