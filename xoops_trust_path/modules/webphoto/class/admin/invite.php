<?php
// $Id: invite.php,v 1.2 2011/11/12 11:05:02 ohwada Exp $

//=========================================================
// webphoto module
// 2009-12-06 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-11-11 K.OHWADA
// get_valid_mail_addr()
//---------------------------------------------------------

if (!defined('WEBPHOTO_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_admin_invite
//=========================================================

/**
 * Class webphoto_admin_invite
 */
class webphoto_admin_invite extends webphoto_base_this
{
    public $_mail_template_class;
    public $_mail_send_class;
    public $_msg_class;

    public $_xoops_user_email = null;
    public $_xoops_user_name = null;

    public $_post_email = null;
    public $_post_name = null;
    public $_post_message = null;

    public $_FORM_TEMPLATE = 'form_admin_invite.html';
    public $_MAIL_TEMPLATE = 'invite.tpl';

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_admin_invite constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_mail_template_class = webphoto_d3_mail_template::getInstance($dirname, $trust_dirname);

        $this->_mail_send_class = webphoto_lib_mail_send::getInstance();
        $this->_msg_class = webphoto_lib_msg::getInstance();

        $this->_xoops_user_email = $this->_xoops_class->get_my_user_value_by_name('email', 'n');
        $this->_xoops_user_name = $this->_xoops_class->get_user_uname_from_id($this->_xoops_uid, 1);
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_admin_invite|\webphoto_lib_error
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
        xoops_cp_header();

        echo $this->build_admin_menu();
        echo $this->build_admin_title('INVITE');

        switch ($this->_get_op()) {
            case 'submit':
                if ($this->check_token_with_print_error()) {
                    $this->_invite();
                }
                break;
            case 'form':
            default:
                $this->_print_form();
                break;
        }

        xoops_cp_footer();
        exit();
    }

    /**
     * @return string
     */
    public function _get_op()
    {
        $this->_post_email = $this->_post_class->get_post_text('email');
        $this->_post_name = $this->_post_class->get_post_text('name');
        $this->_post_message = $this->_post_class->get_post_text('message');

        $submit = $this->_post_class->get_post_text('submit');
        if ($submit) {
            return 'submit';
        }

        return '';
    }

    //---------------------------------------------------------
    // invite
    //---------------------------------------------------------

    /**
     * @return bool
     */
    public function _invite()
    {
        $ret = $this->_invite_exec();
        if (1 == $ret) {
            echo $this->get_format_msg_array(true, false, true);

            return true;
        }

        echo $this->get_format_error();

        if (-1 == $ret) {
            echo $this->_print_form();
        }

        return false;
    }

    /**
     * @return int
     */
    public function _invite_exec()
    {
        $email = $this->_mail_send_class->get_valid_mail_addr($this->_post_email);
        if (empty($email)) {
            $this->set_error($this->get_constant('ERR_MAIL_ILLEGAL'));

            return -1;
        }

        if (empty($this->_post_name)) {
            $this->set_error(_AM_WEBPHOTO_INVITE_ERR_NO_NAME);

            return -1;
        }

        $param = [
            'to_emails' => $email,
            'from_email' => $this->_get_from_email(),
            'subject' => $this->_get_subject(),
            'body' => $this->_get_body(),
            'debug' => true,
        ];

        $ret = $this->_mail_send_class->send($param);
        if (!$ret) {
            $this->set_error($this->_mail_send_class->get_errors());

            return -2;
        }

        $this->set_msg($this->_mail_send_class->get_msg_array());

        return 1;
    }

    /**
     * @return bool|mixed|null
     */
    public function _get_from_email()
    {
        if ($this->_xoops_user_email) {
            return $this->_xoops_user_email;
        }

        return $this->_xoops_adminmail;
    }

    /**
     * @return string
     */
    public function _get_subject()
    {
        return sprintf(_AM_WEBPHOTO_INVITE_SUBJECT, $this->_post_name, $this->_MODULE_NAME);
    }

    /**
     * @return mixed
     */
    public function _get_body()
    {
        $tags = [
            'INVITE_NAME' => $this->_post_name,
            'INVITE_MASSAGE' => $this->_post_message,
        ];

        $this->_mail_template_class->init_tag_array();
        $this->_mail_template_class->assign($tags);
        $str = $this->_mail_template_class->replace_tag_array_by_template($this->_MAIL_TEMPLATE);

        return $str;
    }

    //---------------------------------------------------------
    // print form
    //---------------------------------------------------------
    public function _print_form()
    {
        echo $this->_build_form_invite();
    }

    /**
     * @return mixed|string|void
     */
    public function _build_form_invite()
    {
        $template = $this->build_form_template($this->_FORM_TEMPLATE);

        $name = $this->_post_name;
        if (empty($name)) {
            $name = $this->_xoops_user_name;
        }

        $arr = [
            'xoops_g_ticket' => $this->get_token(),
            'email' => $this->_post_email,
            'name' => $name,
            'message' => $this->_post_message,

            'lang_title_invite' => $this->get_admin_title('INVITE'),
            'lang_invite_email' => _AM_WEBPHOTO_INVITE_EMAIL,
            'lang_invite_name' => _AM_WEBPHOTO_INVITE_NAME,
            'lang_invite_message' => _AM_WEBPHOTO_INVITE_MESSAGE,
            'lang_invite_submit' => _AM_WEBPHOTO_INVITE_SUBMIT,
            'lang_invite_example' => _AM_WEBPHOTO_INVITE_EXAMPLE,

            // for XOOPS 2.0.18
            'xoops_dirname' => $this->_DIRNAME,
        ];

        $tpl = new XoopsTpl();
        $tpl->assign($arr);

        return $tpl->fetch($template);
    }

    /**
     * @param $name
     * @return string
     */
    public function build_form_template($name)
    {
        $str = 'db:' . $this->_DIRNAME . '_' . $name;

        return $str;
    }

    //---------------------------------------------------------
    // msg
    //---------------------------------------------------------
    public function clear_msg_array()
    {
        $this->_msg_class->clear_msg_array();
    }

    /**
     * @param      $msg
     * @param bool $flag_highlight
     */
    public function set_msg($msg, $flag_highlight = false)
    {
        $this->_msg_class->set_msg($msg, $flag_highlight);
    }

    /**
     * @return array
     */
    public function get_msg_array()
    {
        return $this->_msg_class->get_msg_array();
    }

    /**
     * @param bool $flag_sanitize
     * @return mixed
     */
    public function get_format_msg($flag_sanitize = true)
    {
        return $this->_msg_class->get_format_msg($flag_sanitize);
    }

    // --- class end ---
}
