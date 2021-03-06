<?php
// $Id: mail_unlink.php,v 1.1 2009/01/24 07:10:39 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-01-10 K.OHWADA
// webphoto_mail_unlink -> webphoto_edit_mail_unlink
// 2008-11-08 K.OHWADA
// TMP_DIR -> MAIL_DIR
// 2008-08-24 K.OHWADA
// added unlink_attaches()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_edit_mail_unlink
//=========================================================

/**
 * Class webphoto_edit_mail_unlink
 */
class webphoto_edit_mail_unlink
{
    public $_config_class;
    public $_utility_class;

    public $_WORK_DIR;
    public $_MAIL_DIR;
    public $_SEPARATOR = '|';

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_edit_mail_unlink constructor.
     * @param $dirname
     */
    public function __construct($dirname)
    {
        $this->_config_class = webphoto_config::getInstance($dirname);
        $this->_utility_class = webphoto_lib_utility::getInstance();

        $this->_WORK_DIR = $this->_config_class->get_by_name('workdir');
        $this->_MAIL_DIR = $this->_WORK_DIR . '/mail';
    }

    /**
     * @param null $dirname
     * @return \webphoto_edit_mail_unlink
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
    // unlink
    //---------------------------------------------------------

    /**
     * @param $row
     */
    public function unlink_by_maillog_row($row)
    {
        $this->unlink_file($row);
        $this->unlink_attaches($row);
    }

    /**
     * @param $row
     */
    public function unlink_file($row)
    {
        $this->unlink_by_filename($row['maillog_file']);
    }

    /**
     * @param $row
     */
    public function unlink_attaches($row)
    {
        $attach_array = $this->_utility_class->str_to_array($row['maillog_attach'], $this->_SEPARATOR);
        if (!is_array($attach_array)) {
            return; // no action
        }
        foreach ($attach_array as $attach) {
            $this->unlink_by_filename($attach);
        }
    }

    /**
     * @param $file
     */
    public function unlink_by_filename($file)
    {
        if ($file) {
            $this->_utility_class->unlink_file($this->_MAIL_DIR . '/' . $file);
        }
    }

    // --- class end ---
}
