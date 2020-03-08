<?php
// $Id: retrieve.php,v 1.3 2009/01/24 07:10:39 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-24 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-01-10 K.OHWADA
// webphoto_mail_retrieve -> webphoto_edit_mail_retrieve
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_bin_retrieve
//=========================================================

/**
 * Class webphoto_bin_retrieve
 */
class webphoto_bin_retrieve extends webphoto_bin_base
{
    public $_config_class;
    public $_retrieve_class;

    public $_TITLE = 'webphoto mail retrieve';

    public $_FLAG_MAIL_SEND = true;
    public $_DEBUG_BIN_RETRIVE = false;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_bin_retrieve constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_config_class = webphoto_config::getInstance($dirname);

        $this->_retrieve_class = webphoto_edit_mail_retrieve::getInstance($dirname, $trust_dirname);
        $this->_retrieve_class->set_flag_force_db(true);
        $this->_retrieve_class->set_flag_print_first_msg(true);

        // preload
        $this->_retrieve_class->preload_init();
        $this->_retrieve_class->preload_constant();
        $this->preload_init();
        $this->preload_constant();
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_bin_retrieve
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

    /**
     * @return bool
     */
    public function main()
    {
        $pass = $this->_config_class->get_by_name('bin_pass');

        $this->set_env_param();

        if (!$this->check_pass($pass)) {
            return false;
        }

        $flag_print = false;
        if ($this->_flag_print || $this->_DEBUG_BIN_RETRIVE) {
            $flag_print = true;
            $this->_retrieve_class->set_msg_level(_C_WEBPHOTO_MSG_LEVEL_ADMIN);
        }

        $this->print_write_data($this->get_html_header());

        $this->_retrieve_class->retrieve();
        $count = $this->_retrieve_class->get_mail_count();

        if ($flag_print) {
            echo $this->_retrieve_class->get_msg();
        }

        $this->print_write_data($this->get_html_footer());

        if ($this->_FLAG_MAIL_SEND && $count) {
            $text = "mail count: $count ";
            $this->send_mail($this->_adminmail, $this->_TITLE, $text);
        }
    }

    // --- class end ---
}
