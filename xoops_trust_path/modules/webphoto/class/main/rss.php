<?php
// $Id: rss.php,v 1.9 2009/03/06 03:54:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-03-01 K.OHWADA
// webphoto_rss()
// 2008-12-12 K.OHWADA
// webphoto_photo_public
// 2008-12-09 K.OHWADA
// Parse error & Fatal error
// 2008-11-29 K.OHWADA
// _build_file_image()
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_main_rss
//=========================================================

/**
 * Class webphoto_main_rss
 */
class webphoto_main_rss extends webphoto_rss
{
    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_main_rss constructor.
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
     * @return \webphoto_lib_rss|\webphoto_lib_xml|\webphoto_main_rss|\webphoto_rss
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
        $this->show_rss();
    }

    // --- class end ---
}
