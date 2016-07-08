<?php
// $Id: mail_register.php,v 1.9 2011/11/12 17:17:47 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-11-11 K.OHWADA
// webphoto_lib_mail
// 2011-05-16 K.OHWADA
// webphoto_edit_mail_check
// 2011-05-01 K.OHWADA
// _check_submit() user_text2
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_user_handler
// 2009-01-10 K.OHWADA
// webphoto_base_this -> webphoto_edit_base
// 2008-08-24 K.OHWADA
// added print_user_form()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_main_mail_register
//=========================================================
class webphoto_main_mail_register extends webphoto_edit_base
{
    public $_user_handler;
    public $_mail_class;
    public $_xoops_user_class;

    public $_is_set_mail = false;
    public $_has_mail    = false;

    public $_post_user_uid = 0;

    public $_row_current = null;
    public $_row_update  = null;
    public $_is_new      = false;

    public $_REDIRECT_THIS_URL = null;

    public $_TIME_SUCCESS = 1;
    public $_TIME_FAIL    = 5;

    public $_ERR_NOMATCH_USER = -1;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_user_handler = webphoto_user_handler::getInstance($dirname, $trust_dirname);

        $this->_mail_class       = webphoto_lib_mail::getInstance();
        $this->_xoops_user_class = webphoto_xoops_user::getInstance();

        $this->_is_set_mail = $this->_config_class->is_set_mail();
        $this->_has_mail    = $this->_perm_class->has_mail();
    }

    public static function getInstance($dirname = null, $trust_dirname = null)
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new webphoto_main_mail_register($dirname, $trust_dirname);
        }
        return $instance;
    }

    //---------------------------------------------------------
    // main
    //---------------------------------------------------------
    public function check_action()
    {
        $this->_check();

        $action = $this->_get_action();
        switch ($action) {
            case 'submit':
                $this->_check_token_exit();
                if ($this->_check_submit()) {
                    $this->_submit();
                    exit();
                }
                break;

            default:
                break;
        }

        return true;
    }

    //---------------------------------------------------------
    // check
    //---------------------------------------------------------
    public function _check()
    {
        $this->clear_errors();

        // set login uid if not specify uid
        $this->_post_user_uid = $this->_post_class->get_post_get_int('user_uid', $this->_xoops_uid);

        $this->_REDIRECT_THIS_URL = $this->_MODULE_URL . '/index.php?fct=mail_register&amp;user_uid=' . $this->_post_user_uid;

        switch ($this->_exec_check($this->_post_user_uid)) {
            case _C_WEBPHOTO_ERR_NO_PERM:
                redirect_header($this->_INDEX_PHP, $this->_TIME_FAIL, _NOPERM);
                exit;

            case $this->_ERR_NOMATCH_USER:
                redirect_header($this->_INDEX_PHP, $this->_TIME_FAIL, $this->get_constant('NOMATCH_USER'));
                exit;

            case 0:
            default:
                break;
        }

        return true;
    }

    public function _exec_check($user_uid)
    {
        if (!$this->_is_set_mail) {
            return _C_WEBPHOTO_ERR_NO_PERM;
        }

        if (!$this->_has_mail) {
            return _C_WEBPHOTO_ERR_NO_PERM;
        }

        // specified user id
        if ($user_uid > 0) {
            $user = $this->_xoops_user_class->get_user_by_uid($user_uid);
            if (!is_object($user)) {
                return $this->_ERR_NOMATCH_USER;
            }

            $row = $this->_user_handler->get_row_by_uid($user_uid);
            if (!is_array($row)) {
                $this->_is_new   = true;
                $row             = $this->_user_handler->create(true);
                $row['user_uid'] = $user_uid;
            }

            // guest
        } else {
            return $this->_ERR_NOMATCH_USER;
        }

        if (!$this->_check_perm($row)) {
            return _C_WEBPHOTO_ERR_NO_PERM;
        }

        // save
        $this->_row_current = $row;
        return 0;
    }

    public function _check_perm($row)
    {
        // if admin
        if ($this->_is_module_admin) {
            return true;
        }

        // user can register own record
        if (($this->_xoops_uid != 0)
            && ($this->_xoops_uid == $row['user_uid'])
        ) {
            return true;
        }

        return false;
    }

    public function _get_action()
    {
        return $this->_post_class->get_post_text('op');
    }

    public function _check_token_exit()
    {
        if (!$this->check_token()) {
            $msg = 'Token Error';
            if ($this->_is_module_admin) {
                $msg .= '<br />' . $this->get_token_errors();
            }
            redirect_header($this->_REDIRECT_THIS_URL, $this->_TIME_FAIL, $msg);
            exit();
        }
    }

    //---------------------------------------------------------
    // modify
    //---------------------------------------------------------
    public function _check_submit()
    {
        $email = $this->_post_class->get_post_text('user_email');
        $text2 = $this->_post_class->get_post_text('user_text2');
        $text3 = $this->_post_class->get_post_text('user_text3');
        $text4 = $this->_post_class->get_post_text('user_text4');
        $text5 = $this->_post_class->get_post_text('user_text5');

        // overwrite
        $this->_row_current['user_cat_id'] = $this->_post_class->get_post_int('user_cat_id');

        if (empty($email) && empty($text2) && empty($text3) && empty($text4) && empty($text5)) {

            // if new
            if ($this->_is_new) {
                $this->set_error($this->get_constant('ERR_MAIL_EMPTY'));
                return false;

                // allow to clear email
            } else {
                return true;
            }
        }

        if (!$this->_check_mail_addr($email)) {
            return false;
        }
        if (!$this->_check_mail_addr($text2)) {
            return false;
        }
        if (!$this->_check_mail_addr($text3)) {
            return false;
        }
        if (!$this->_check_mail_addr($text4)) {
            return false;
        }
        if (!$this->_check_mail_addr($text5)) {
            return false;
        }

        // overwrite
        $this->_row_current['user_email'] = $email;
        $this->_row_current['user_text2'] = $text2;
        $this->_row_current['user_text3'] = $text3;
        $this->_row_current['user_text4'] = $text4;
        $this->_row_current['user_text5'] = $text5;

        return true;
    }

    public function _check_mail_addr($mail)
    {
        $lang_error = $this->get_constant('ERR_MAIL_ILLEGAL');

        if (empty($mail)) {
            return true;
        }

        if ($this->_mail_class->check_valid_addr($mail)) {
            return true;
        }

        $this->set_error($lang_error . ' : ' . $mail);
    }

    public function _submit()
    {
        $ret = $this->_exec_submit();

        switch ($ret) {
            case _C_WEBPHOTO_ERR_DB:
                $msg = 'DB Error';
                if ($this->_is_module_admin) {
                    $msg .= '<br />' . $this->get_format_error();
                }
                redirect_header($this->_REDIRECT_THIS_URL, $this->_TIME_FAIL, $msg);
                exit();

            case 0:
            default:
                break;
        }

        redirect_header($this->_REDIRECT_THIS_URL, $this->_TIME_SUCCESS, $this->get_constant('DBUPDATED'));
        exit();
    }

    public function _exec_submit()
    {

        // load
        $row = $this->_row_current;

        if ($this->_is_new) {
            $ret = $this->_user_handler->insert($row);
        } else {
            $ret = $this->_user_handler->update($row);
        }
        if (!$ret) {
            $this->set_error($this->_user_handler->get_errors());
            return _C_WEBPHOTO_ERR_DB;
        }

        return 0;
    }

    //---------------------------------------------------------
    // print_form
    //---------------------------------------------------------
    public function print_form()
    {
        // load
        $row = $this->_row_current;

        echo $this->build_bread_crumb($this->get_constant('TITLE_MAIL_REGISTER'), $this->_REDIRECT_THIS_URL);

        echo $this->get_constant('HELP_MAIL_DSC');
        echo "<br /><br />\n";

        $url = $this->_uri_class->build_full_uri_mode('help');
        echo '<a href="' . $url . '" target="_blank">';
        echo $this->get_constant('MAIL_HELP');
        echo "</a><br /><br />\n";

        if ($this->has_error()) {
            echo $this->build_error_msg($this->get_format_error(false, false), null, false);
            echo "<br />\n";
        }

        if ($this->_is_new) {
            $mode = 'add';
        } else {
            $mode = 'edit';
        }

        $param = array(
            'mode' => $mode,
        );

        $form = webphoto_edit_mail_register_form::getInstance($this->_DIRNAME, $this->_TRUST_DIRNAME);

        if ($this->_check_user_form()) {
            $form->print_user_form($row);
            echo "<br />\n";
        }

        $form->print_submit_form($row, $param);
    }

    public function _check_user_form()
    {
        $action = $this->_get_action();
        if ($this->_is_module_admin && ($action != 'submit') && ($action != 'form')) {
            return true;
        }
        return false;
    }

    // --- class end ---
}
