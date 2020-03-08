<?php
// $Id: callback.php,v 1.2 2008/11/11 06:53:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-11-08 K.OHWADA
// webphoto_flash_log
//---------------------------------------------------------

//---------------------------------------------------------
// http://code.jeroenwijering.com/trac/wiki/Flashvars3
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_main_callback
//=========================================================

/**
 * Class webphoto_main_callback
 */
class webphoto_main_callback extends webphoto_flash_log
{
    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_main_callback constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname);
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_flash_log|\webphoto_main_callback
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
        $this->callback_log();
    }

    // --- class end ---
}
