<?php
// $Id: text.php,v 1.3 2010/09/19 06:43:11 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-09-17 K.OHWADA
// webphoto_lib_readfile
// 2008-11-08 K.OHWADA
// TMP_DIR -> MAIL_DIR
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_admin_text
//=========================================================

/**
 * Class webphoto_admin_text
 */
class webphoto_admin_text extends webphoto_base_this
{
    public $_readfile_class;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_admin_text constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_readfile_class = webphoto_lib_readfile::getInstance();
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_admin_text|\webphoto_lib_error
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
        $name = $this->_post_class->get_get_text('name');
        $file = $this->_MAIL_DIR . '/' . $name;

        if (empty($name) || !is_file($file)) {
            exit();
        }

        $this->_readfile_class->readfile_view($file, 'text/plain');
        exit();
    }

    // --- class end ---
}
