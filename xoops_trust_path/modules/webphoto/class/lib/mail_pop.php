<?php
// $Id: mail_pop.php,v 1.1 2008/08/08 04:39:14 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//=========================================================
// class webphoto_lib_mail_pop
// base on mailbbs's pop.php
//=========================================================
class webphoto_lib_mail_pop
{
    // set param
    public $_HOST = null;
    public $_USER = null;
    public $_PASS = null;

    public $_PORT     = '110'; // pop3
    public $_TIMEOUT  = 10;
    public $_MAX_MAIL = 10;

    public $_fp;
    public $_mail_arr  = array();
    public $_msg_arr   = array();
    public $_error_arr = array();

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        // dummy
    }

    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new webphoto_lib_mail_pop();
        }
        return $instance;
    }

    //---------------------------------------------------------
    // set param
    //---------------------------------------------------------
    public function set_host($val)
    {
        $this->_HOST = $val;
    }

    public function set_user($val)
    {
        $this->_USER = $val;
    }

    public function set_pass($val)
    {
        $this->_PASS = $val;
    }

    //---------------------------------------------------------
    // pop mail
    //---------------------------------------------------------
    public function recv_mails()
    {
        $this->clear_mails();
        $this->clear_msgs();
        $this->clear_errors();

        if (empty($this->_HOST) || empty($this->_USER) || empty($this->_PASS)) {
            $this->set_error('not set param');
            return -1;
        }

        $fp = fsockopen($this->_HOST, $this->_PORT, $err, $errno, $this->_TIMEOUT);
        if (!$fp) {
            $this->set_error($err);
            return -1;
        }
        $this->_fp = $fp;

        $ret = $this->recv();
        if (!$ret) {
            fclose($this->_fp);
            return -1;
        }

        $ret = $this->send_recv('USER ' . $this->_USER);
        if (!$ret) {
            fclose($this->_fp);
            return -1;
        }

        $ret = $this->send_recv('PASS ' . $this->_PASS);
        if (!$ret) {
            fclose($this->_fp);
            return -1;
        }

        $data = $this->send_recv('STAT');
        if (!$data) {
            fclose($this->_fp);
            return -1;
        }

        sscanf($data, '+OK %d %d', $num, $size);
        $num = (int)$num;

        // no mail
        if ($num == 0) {
            $this->send_recv('QUIT');
            fclose($this->_fp);
            return 0;
        }

        // set limit
        if ($num > $this->_MAX_MAIL) {
            $num = $this->_MAX_MAIL;
        }

        // get mails
        for ($i = 1; $i <= $num; ++$i) {
            $this->send("RETR $i");
            $body = $this->recv_body();
            if (!$body) {
                fclose($this->_fp);
                return -1;
            }

            $this->set_mail($body);
            $ret = $this->send_recv("DELE $i");
            if (!$ret) {
                fclose($this->_fp);
                return -1;
            }
        }

        $this->send_recv('QUIT');

        fclose($this->_fp);
        return $num;
    }

    public function send_recv($cmd)
    {
        $this->send($cmd);
        return $this->recv();
    }

    public function send($cmd)
    {
        $this->set_msg($cmd);
        fwrite($this->_fp, $cmd . "\r\n");
    }

    public function recv()
    {
        $buf = fgets($this->_fp, 512);
        $this->set_msg($buf);
        if (substr($buf, 0, 3) == '+OK') {
            return $buf;
        }
        $this->set_error($buf);
        return false;
    }

    public function recv_body()
    {
        $line = fgets($this->_fp, 512);
        $dat  = '';

        // read until '.'
        while (!preg_match("/^\.\r\n/", $line)) {
            $line = fgets($this->_fp, 512);
            $dat .= $line;
        }

        $this->set_msg($dat);
        return $dat;
    }

    //---------------------------------------------------------
    // msg
    //---------------------------------------------------------
    public function clear_mails()
    {
        $this->_mail_arr = array();
    }

    public function set_mail($mail)
    {
        $this->_mail_arr[] = $mail;
    }

    public function get_mails()
    {
        return $this->_mail_arr;
    }

    public function clear_msgs()
    {
        $this->_msg_arr = array();
    }

    public function set_msg($msg)
    {
        $this->_msg_arr[] = $msg;
    }

    public function get_msgs()
    {
        return $this->_msg_arr;
    }

    public function clear_errors()
    {
        $this->_error_arr = array();
    }

    public function set_error($err)
    {
        $this->_error_arr[] = $err;
    }

    public function get_errors()
    {
        return $this->_error_arr;
    }

    // --- class end ---
}
