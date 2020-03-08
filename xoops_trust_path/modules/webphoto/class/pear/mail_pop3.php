<?php
// $Id: mail_pop3.php,v 1.3 2011/11/04 04:01:48 ohwada Exp $

//=========================================================
// mail pop3 woth pear
// 2011-05-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-11-03 K.OHWADA
// Function ereg() is deprecated
//---------------------------------------------------------

//=========================================================
// class pear_mail_pop3
//=========================================================

/**
 * Class webphoto_pear_mail_pop3
 */
class webphoto_pear_mail_pop3
{
    // set param
    public $_HOST = null;
    public $_USER = null;
    public $_PASS = null;

    public $_PORT = '110'; // pop3
    public $_MAX_MAIL = 10;

    public $_mail_arr = [];
    public $_error_arr = [];

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        // dummy
    }

    /**
     * @return \webphoto_pear_mail_pop3
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
    // set param
    //---------------------------------------------------------

    /**
     * @param $val
     */
    public function set_host($val)
    {
        $this->_HOST = $val;
    }

    /**
     * @param $val
     */
    public function set_user($val)
    {
        $this->_USER = $val;
    }

    /**
     * @param $val
     */
    public function set_pass($val)
    {
        $this->_PASS = $val;
    }

    /**
     * @param $val
     */
    public function set_max_mail($val)
    {
        $this->_MAX_MAIL = (int)$val;
    }

    //---------------------------------------------------------
    // pop mail
    //---------------------------------------------------------

    /**
     * @return bool|int|mixed
     */
    public function recv_mails()
    {
        $this->clear_mails();
        $this->clear_errors();

        if (empty($this->_HOST) || empty($this->_USER) || empty($this->_PASS)) {
            $this->set_error('not set param');

            return false;
        }

        // Function ereg() is deprecated
        // example.com:110
        if (preg_match('/^(.+):([0-9]+)$/', $this->_HOST, $hostinfo)) {
            $host = $hostinfo[1];
            $port = $hostinfo[2];
        } else {
            $host = $this->_HOST;
            $port = $this->_PORT;
        }

        $pop = new Net_POP3();
        $ret = $pop->connect($host, $port);
        if (!$ret) {
            $this->set_error('not connect');

            return false;
        }

        $ret = $pop->login($this->_USER, $this->_PASS);
        if (true !== $ret) {
            $this->set_error($ret);
            $pop->disconnect();

            return false;
        }

        $num = $pop->numMsg();

        // no mail
        if (0 == $num) {
            $pop->disconnect();

            return 0;
        }

        // set limit
        if ($num > $this->_MAX_MAIL) {
            $num = $this->_MAX_MAIL;
        }

        // get mails
        for ($i = 1; $i <= $num; ++$i) {
            $this->set_mail($pop->getMsg($i));
            $pop->deleteMsg($i);
        }

        $pop->disconnect();

        return $num;
    }

    //---------------------------------------------------------
    // msg
    //---------------------------------------------------------
    public function clear_mails()
    {
        $this->_mail_arr = [];
    }

    /**
     * @param $mail
     */
    public function set_mail($mail)
    {
        $this->_mail_arr[] = $mail;
    }

    /**
     * @return array
     */
    public function get_mails()
    {
        return $this->_mail_arr;
    }

    public function clear_errors()
    {
        $this->_error_arr = [];
    }

    /**
     * @param $err
     */
    public function set_error($err)
    {
        $this->_error_arr[] = $err;
    }

    /**
     * @return array
     */
    public function get_errors()
    {
        return $this->_error_arr;
    }

    // --- class end ---
}
