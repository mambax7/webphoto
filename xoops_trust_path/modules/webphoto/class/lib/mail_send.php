<?php
// $Id: mail_send.php,v 1.3 2011/11/12 17:17:47 ohwada Exp $

//=========================================================
// webphoto module
// 2009-12-06 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-11-11 K.OHWADA
// webphoto_lib_mail
//---------------------------------------------------------

//=========================================================
// class webphoto_lib_mail_send
//=========================================================

/**
 * Class webphoto_lib_mail_send
 */
class webphoto_lib_mail_send extends webphoto_lib_error
{
    public $_mail_class;

    public $_xoops_sitename;
    public $_xoops_adminmail;
    public $_msg_array = [];

    public $_LANG_ERR_NO_TO_EMAIL = 'Not Set Email Address';

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();

        $this->_mail_class = webphoto_lib_mail::getInstance();

        $this->_xoops_sitename = $this->get_xoops_sitename();
        $this->_xoops_adminmail = $this->get_xoops_adminmail();
    }

    /**
     * @return \webphoto_lib_error|\webphoto_lib_mail_send
     */
    public static function getInstance()
    {
        static $instance;
        if (null === $instance) {
            $instance = new self();
        }

        return $instance;
    }

    //---------------------------------------------------------
    // send email
    //---------------------------------------------------------

    /**
     * @param $param
     * @return bool
     */
    public function send($param)
    {
        $to_emails = isset($param['to_emails']) ? $param['to_emails'] : null;
        $users = isset($param['users']) ? $param['users'] : null;
        $subject = isset($param['subject']) ? $param['subject'] : null;
        $body = isset($param['body']) ? $param['body'] : null;
        $tags = isset($param['tags']) ? $param['tags'] : null;
        $debug = isset($param['debug']) ? $param['debug'] : false;
        $from_name = isset($param['from_name']) ? $param['from_name'] : $this->_xoops_sitename;
        $from_email = isset($param['from_email']) ? $param['from_email'] : $this->_xoops_adminmail;

        if (empty($to_emails) && empty($users)) {
            $this->set_error($this->_LANG_ERR_NO_TO_EMAIL);

            return false;
        }

        $this->clear_errors();
        $this->clear_msg_array();

        // mail start
        $mailer = &getMailer();
        $mailer->reset();
        $mailer->setFromName($from_name);
        $mailer->setFromEmail($from_email);
        $mailer->setSubject($subject);
        $mailer->setBody($body);
        $mailer->useMail();

        if ($to_emails) {
            $mailer->setToEmails($to_emails);
        }

        if (is_array($users) && count($users)) {
            $mailer->setToUsers($users);
        }

        if (is_array($tags) && count($tags)) {
            $mailer->assign($tags);
        }

        $ret = $mailer->send($debug);
        if (!$ret) {
            $this->set_error($mailer->getErrors(false));

            return false;
        }

        $this->set_msg($mailer->getSuccess(false));

        return true;
    }

    /**
     * @param $addr
     * @return mixed|null
     */
    public function get_valid_mail_addr($addr)
    {
        return $this->_mail_class->get_valid_addr($addr);
    }

    //---------------------------------------------------------
    // msg
    //---------------------------------------------------------
    public function clear_msg_array()
    {
        $this->_msg_array = [];
    }

    /**
     * @return array
     */
    public function get_msg_array()
    {
        return $this->_msg_array;
    }

    /**
     * @param      $msg
     * @param bool $flag_highlight
     */
    public function set_msg($msg, $flag_highlight = false)
    {
        // array type
        if (is_array($msg)) {
            $arr = $msg;

        // string type
        } else {
            $arr = $this->str_to_array($msg, "\n");
            if ($flag_highlight) {
                $arr2 = [];
                foreach ($arr as $m) {
                    $arr2[] = $this->highlight($m);
                }
                $arr = $arr2;
            }
        }

        foreach ($arr as $m) {
            $m = trim($m);
            if ($m) {
                $this->_msg_array[] = $m;
            }
        }
    }

    //---------------------------------------------------------
    // XOOPS system
    //---------------------------------------------------------

    /**
     * @return mixed
     */
    public function get_xoops_sitename()
    {
        global $xoopsConfig;

        return $xoopsConfig['sitename'];
    }

    /**
     * @return mixed
     */
    public function get_xoops_adminmail()
    {
        global $xoopsConfig;

        return $xoopsConfig['adminmail'];
    }

    // --- class end ---
}
