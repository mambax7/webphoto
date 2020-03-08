<?php
// $Id: groupperm.php,v 1.6 2010/02/17 04:34:47 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-02-15 K.OHWADA
// get_errors()
// 2009-12-06 K.OHWADA
// webphoto_lib_groupperm
// 2009-01-04 K.OHWADA
// _B_WEBPHOTO_GPERM_HTML
// 2008-08-01 K.OHWADA
// added _B_WEBPHOTO_GPERM_MAIL
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_admin_groupperm
//=========================================================

/**
 * Class webphoto_admin_groupperm
 */
class webphoto_admin_groupperm extends webphoto_edit_base
{
    public $_groupperm_class;
    public $_form_class;

    public $_FLAG_SYSTEM = true;

    public $_THIS_FCT = 'groupperm';
    public $_THIS_URL;

    public $_TIME_SUCCESS = 1;
    public $_TIME_FAIL = 5;
    public $_TIME_DEBUG = 60;

    public $_DEBUG = false;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_admin_groupperm constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_form_class = webphoto_admin_groupperm_form::getInstance($dirname, $trust_dirname);

        $this->_groupperm_class = webphoto_lib_groupperm::getInstance();

        $this->_THIS_URL = $this->_MODULE_URL . '/admin/index.php?fct=' . $this->_THIS_FCT;
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_admin_groupperm|\webphoto_lib_error
     */
    public static function getInstance($dirname = null, $trust_dirname = null)
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new self($dirname, $trust_dirname);
        }

        return $instance;
    }

    //---------------------------------------------------------
    // main
    //---------------------------------------------------------
    public function main()
    {
        $perms = $this->get_post('perms');
        if (is_array($perms)) {
            $this->groupperm($perms);
            exit();
        }

        xoops_cp_header();

        echo $this->build_admin_menu();
        echo $this->build_admin_title('GROUPPERM');
        echo $this->build_form();

        xoops_cp_footer();
    }

    //---------------------------------------------------------
    // groupperm
    //---------------------------------------------------------

    /**
     * @param $perms
     */
    public function groupperm($perms)
    {
        if (!$this->check_token()) {
            redirect_header($this->_THIS_URL, $this->_TIME_FAIL, $this->get_token_errors());
            exit();
        }

        $this->_groupperm_class->modify($this->_MODULE_ID, $perms, $this->_FLAG_SYSTEM);
        $errors = $this->_groupperm_class->get_errors();
        $msgs = $this->_groupperm_class->get_msg_array();

        if ($this->_DEBUG && is_array($errors) && count($errors)) {
            $msg = implode("<br>\n", $errors);
            $msg = $this->highlight($msg);
            $time = $this->_TIME_FAIL;
        } else {
            $msg = _AM_WEBPHOTO_GPERMUPDATED;
            $time = $this->_TIME_SUCCESS;
        }

        if ($this->_DEBUG && is_array($msgs) && count($msgs)) {
            $msg .= "<br>\n" . implode("<br>\n", $msgs);
            $time = $this->_TIME_DEBUG;
        }

        redirect_header($this->_THIS_URL, $time, $msg);
        exit();
    }

    //---------------------------------------------------------
    // form
    //---------------------------------------------------------

    /**
     * @return mixed|string|void
     */
    public function build_form()
    {
        return $this->_form_class->build_form($this->_THIS_FCT);
    }

    // --- class end ---
}
