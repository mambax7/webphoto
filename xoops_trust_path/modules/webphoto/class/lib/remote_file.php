<?php
// $Id: remote_file.php,v 1.1 2008/10/30 00:25:51 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//---------------------------------------------------------
// define constant
//---------------------------------------------------------
define('_C_WEBPHOTO_REMOTE_FILE_ERR_NOT_FETCH', -1);
define('_C_WEBPHOTO_REMOTE_FILE_ERR_NO_RESULT', -2);

//=========================================================
// class webphoto_lib_remote_file
// use class snoopy
//=========================================================

/**
 * Class webphoto_lib_remote_file
 */
class webphoto_lib_remote_file extends webphoto_lib_error
{
    // class instance
    public $_snoopy;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();

        // class instance
        $this->_snoopy = new Snoopy();
    }

    /**
     * @return \webphoto_lib_error|\webphoto_lib_remote_file
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
    // read_file
    //---------------------------------------------------------

    /**
     * @param $url
     * @return bool|string
     */
    public function read_file($url)
    {
        return $this->snoppy_fetch($url);
    }

    //---------------------------------------------------------
    // use class spoopy
    //---------------------------------------------------------

    /**
     * @param        $host
     * @param string $port
     * @param string $user
     * @param string $pass
     */
    public function set_snoopy_proxy($host, $port = '8080', $user = '', $pass = '')
    {
        $this->_snoopy->proxy_host = $host;
        $this->_snoopy->proxy_port = $port;

        if ($user) {
            $this->_snoopy->proxy_user = $user;
        }
        if ($pass) {
            $this->_snoopy->proxy_pass = $pass;
        }
    }

    /**
     * @param $time
     */
    public function set_snoopy_timeout_connect($time)
    {
        if ((int)$time > 0) {
            $this->_snoopy->_fp_timeout = (float)$time;
        }
    }

    /**
     * @param $time
     */
    public function set_snoopy_timeout_read($time)
    {
        if ((int)$time > 0) {
            $this->_snoopy->read_timeout = (float)$time;
        }
    }

    /**
     * @param $url
     * @return bool|string
     */
    public function snoppy_fetch($url)
    {
        $this->clear_error_code();
        $this->clear_errors();

        if (empty($url)) {
            return false;
        }

        if ($this->_snoopy->fetch($url)) {
            $res = $this->_snoopy->results;

            if ($res) {
                return $res;
            }
            $this->set_error_code(_C_WEBPHOTO_REMOTE_FILE_ERR_NO_RESULT);
            $this->set_error('remote_file: remote data is empty:');
            if ($this->_snoopy->error) {
                $this->set_error('snoopy: ' . $this->_snoopy->error);
            }

            return false;
        }
        $this->set_error_code(_C_WEBPHOTO_REMOTE_FILE_ERR_NOT_FETCH);
        $this->set_error('remote_file: cannot fetch remote data:');
        if ($this->_snoopy->error) {
            $this->set_error('snoopy: ' . $this->_snoopy->error);
        }

        return false;
    }

    //----- class end -----
}
