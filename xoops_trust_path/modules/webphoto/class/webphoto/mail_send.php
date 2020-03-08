<?php
// $Id: mail_send.php,v 1.2 2010/02/08 01:42:02 ohwada Exp $

//=========================================================
// webphoto module
// 2010-02-01 K.OHWADA
//=========================================================

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_mail_send
//=========================================================

/**
 * Class webphoto_mail_send
 */
class webphoto_mail_send extends webphoto_base_this
{
    public $_mail_template_class;
    public $_mail_send_class;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_mail_send constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_mail_template_class = webphoto_d3_mail_template::getInstance($dirname, $trust_dirname);

        $this->_mail_send_class = webphoto_lib_mail_send::getInstance();
    }

    // for admin_photo_manage admin_catmanager

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_lib_error|\webphoto_mail_send
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
    // submit
    //---------------------------------------------------------

    /**
     * @param $row
     */
    public function send_waiting($row)
    {
        $from_email = $this->_xoops_adminmail;
        $subject = $this->build_subject($this->get_constant('MAIL_SUBMIT_WAITING'));
        $body = $this->build_waiting_common_body($row, 'global_waiting_notify.tpl');

        $users = $this->build_waiting_users($row);
        foreach ($users as $user) {
            $param = [
                'to_emails' => $user->getVar('email', 'n'),
                'from_email' => $from_email,
                'subject' => $subject,
                'body' => $this->build_waiting_body($user, $body),
                'debug' => true,
            ];

            $this->send_by_param($param);
        }
    }

    /**
     * @param $item_row
     * @return mixed|null
     */
    public function build_waiting_users($item_row)
    {
        $users = null;

        // module admin
        $groupid_admin = $this->get_config_by_name('groupid_admin');
        if ($groupid_admin) {
            $users = $this->get_users_by_groupid($groupid_admin);
        }

        // xoops admin
        if (!is_array($users) || !count($users)) {
            $users = $this->get_users_by_groupid(XOOPS_GROUP_ADMIN);
        }

        return $users;
    }

    /**
     * @param $group_id
     * @return mixed
     */
    public function get_users_by_groupid($group_id)
    {
        return $this->_xoops_class->get_member_users_by_group($group_id, true);
    }

    /**
     * @param $row
     * @param $template
     * @return mixed
     */
    public function build_waiting_common_body($row, $template)
    {
        $url = $this->_MODULE_URL . '/admin/index.php?fct=item_manager&op=modify_form&item_id=' . $row['item_id'];
        $tags = [
            'PHOTO_TITLE' => $row['item_title'],
            'WAITING_URL' => $url,
        ];

        return $this->build_body_by_tags($tags, $template);
    }

    /**
     * @param $user
     * @param $str
     * @return mixed
     */
    public function build_waiting_body($user, $str)
    {
        $tags = [
            'X_UNAME' => $user->getVar('uname'),
        ];

        return $this->_mail_template_class->replace_str_by_tags($str, $tags);
    }

    //---------------------------------------------------------
    // admin
    //---------------------------------------------------------

    /**
     * @param $row
     * @return bool
     */
    public function send_approve($row)
    {
        return $this->send_approve_common($row, $this->get_constant('MAIL_SUBMIT_APPROVE'), 'submit_approve_notify.tpl');
    }

    /**
     * @param $row
     * @return bool
     */
    public function send_refuse($row)
    {
        return $this->send_approve_common($row, $this->get_constant('MAIL_SUBMIT_REFUSE'), 'submit_refuse_notify.tpl');
    }

    /**
     * @param $row
     * @param $subject
     * @param $template
     * @return bool
     */
    public function send_approve_common($row, $subject, $template)
    {
        $email = $this->get_xoops_email_by_uid($row['item_uid']);
        if (empty($email)) {
            return true;    // no mail
        }

        $param = [
            'to_emails' => $email,
            'from_email' => $this->_xoops_adminmail,
            'subject' => $this->build_subject($subject),
            'body' => $this->build_approve_body($row, $template),
            'debug' => true,
        ];

        return $this->send_by_param($param);
    }

    /**
     * @param $row
     * @param $template
     * @return mixed
     */
    public function build_approve_body($row, $template)
    {
        $tags = [
            'PHOTO_TITLE' => $row['item_title'],
            'PHOTO_URL' => $this->build_uri_photo($row['item_id']),
            'PHOTO_UNAME' => $this->get_xoops_uname_by_uid($row['item_uid']),
        ];

        return $this->build_body_by_tags($tags, $template);
    }

    //---------------------------------------------------------
    // utility
    //---------------------------------------------------------

    /**
     * @param $param
     * @return bool
     */
    public function send_by_param($param)
    {
        $ret = $this->_mail_send_class->send($param);
        if (!$ret) {
            $this->set_error($this->_mail_send_class->get_errors());

            return false;
        }

        return true;
    }

    /**
     * @param $subject
     * @return string
     */
    public function build_subject($subject)
    {
        $str = $subject;
        $str .= ' [' . $this->_xoops_sitename . '] ';
        $str .= $this->_MODULE_NAME;

        return $str;
    }

    /**
     * @param $tags
     * @param $template
     * @return mixed
     */
    public function build_body_by_tags($tags, $template)
    {
        $this->_mail_template_class->init_tag_array();
        $this->_mail_template_class->assign($tags);

        return $this->_mail_template_class->replace_tag_array_by_template($template);
    }

    // --- class end ---
}
