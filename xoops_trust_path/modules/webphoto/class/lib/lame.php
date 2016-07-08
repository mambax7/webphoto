<?php
// $Id: lame.php,v 1.2 2010/06/16 22:24:47 ohwada Exp $

//=========================================================
// webphoto module
// 2009-10-25 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-06-06 K.OHWADA
// is_win_os()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_lib_lame
//=========================================================

class webphoto_lib_lame
{
    public $_cmd_lame = 'lame';

    public $_cmd_path  = null;
    public $_msg_array = array();
    public $_DEBUG     = false;

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
            $instance = new webphoto_lib_lame();
        }
        return $instance;
    }

    //---------------------------------------------------------
    // main
    //---------------------------------------------------------
    public function set_cmd_path($val)
    {
        $this->_cmd_path = $val;
        $this->_cmd_lame = $this->_cmd_path . 'lame';

        if ($this->is_win_os()) {
            $this->_cmd_lame = $this->conv_win_cmd($this->_cmd_lame);
        }
    }

    public function set_debug($val)
    {
        $this->_DEBUG = (bool)$val;
    }

    public function wav_to_mp3($wav, $mp3, $option = '')
    {
        $cmd_option = ' -V2 ' . $option;
        return $this->lame($wav, $mp3, $cmd_option);
    }

    public function lame($wav, $mp3, $option = '')
    {
        $cmd = $this->_cmd_lame . ' ' . $option . ' ' . $wav . ' ' . $mp3;
        exec("$cmd 2>&1", $ret_array, $ret_code);
        if ($this->_DEBUG) {
            echo $cmd . "<br />\n";
        }
        $this->set_msg($cmd);
        $this->set_msg($ret_array);
        return $ret_code;
    }

    //---------------------------------------------------------
    // version
    //---------------------------------------------------------
    public function version($path)
    {
        // LAME 32bits version 3.97 (http://www.mp3dev.org/)

        $lame = $path . 'lame';
        if ($this->is_win_os()) {
            $lame = $this->conv_win_cmd($lame);
        }

        $cmd = $lame . ' --help 2>&1';
        exec($cmd, $ret_array);
        if (count($ret_array) > 0) {
            $ret = true;
            $msg = $ret_array[0];
        } else {
            $ret = false;
            $msg = 'Error: ' . $lame . " can't be executed";
        }

        return array($ret, $msg);
    }

    //---------------------------------------------------------
    // utility
    //---------------------------------------------------------
    public function is_win_os()
    {
        if (strpos(PHP_OS, 'WIN') === 0) {
            return true;
        }
        return false;
    }

    public function conv_win_cmd($cmd)
    {
        $str = '"' . $cmd . '.exe"';
        return $str;
    }

    //---------------------------------------------------------
    // msg
    //---------------------------------------------------------
    public function clear_msg_array()
    {
        $this->_msg_array = array();
    }

    public function get_msg_array()
    {
        return $this->_msg_array;
    }

    public function set_msg($ret_array)
    {
        if (is_array($ret_array)) {
            foreach ($ret_array as $line) {
                $this->_msg_array[] = $line;
            }
        } else {
            $this->_msg_array[] = $ret_array;
        }
    }

    // --- class end ---
}
