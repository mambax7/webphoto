<?php
// $Id: jodconverter.php,v 1.4 2011/05/10 20:17:10 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-25 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-05-01 K.OHWADA
// print_r
// 2010-06-06 K.OHWADA
// is_win_os()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_lib_jodconverter
//=========================================================

//---------------------------------------------------------
// http://www.artofsolving.com/opensource/jodconverter
//---------------------------------------------------------

/**
 * Class webphoto_lib_jodconverter
 */
class webphoto_lib_jodconverter
{
    public $_cmd_java = 'java';

    public $_CMD_PATH_JAVA = '';
    public $_jodconverter_jar = '';
    public $_msg_array = [];
    public $_DEBUG = false;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        // dummy
    }

    /**
     * @return \webphoto_lib_jodconverter
     */
    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new self();
        }

        return $instance;
    }

    //---------------------------------------------------------
    // set
    //---------------------------------------------------------

    /**
     * @param $val
     */
    public function set_cmd_path_java($val)
    {
        $this->_CMD_PATH_JAVA = $val;
        $this->_cmd_java = $this->_CMD_PATH_JAVA . 'java';

        if ($this->is_win_os()) {
            $this->_cmd_java = $this->conv_win_cmd($this->_cmd_java);
        }
    }

    /**
     * @param $val
     */
    public function set_jodconverter_jar($val)
    {
        $this->_jodconverter_jar = $val;
    }

    /**
     * @param $val
     */
    public function set_debug($val)
    {
        $this->_DEBUG = (bool)$val;
    }

    //---------------------------------------------------------
    // main
    //---------------------------------------------------------

    /**
     * @param $src_file
     * @param $dst_file
     * @return mixed
     */
    public function convert($src_file, $dst_file)
    {
        $this->clear_msg_array();

        $cmd = $this->_cmd_java . ' -jar ' . $this->_jodconverter_jar . ' ' . $src_file . ' ' . $dst_file;
        exec("$cmd 2>&1", $ret_array, $ret_code);
        if ($this->_DEBUG) {
            echo $cmd . "<br>\n";
            print_r($ret_array);
        }
        $this->set_msg($cmd);
        $this->set_msg($ret_array);

        return $ret_code;
    }

    //---------------------------------------------------------
    // version
    //---------------------------------------------------------

    /**
     * @return array
     */
    public function version()
    {
        $cmd = $this->_cmd_java . ' -version';
        exec("$cmd 2>&1", $ret_array, $ret_code);
        if ($this->_DEBUG) {
            echo $cmd . "<br>\n";
        }

        $ret = false;
        if (is_array($ret_array) && count($ret_array)) {
            $msg = $ret_array[0] . "<br>\n";
            list($ret, $msg_jod) = $this->get_version_jodconverter();
            $msg .= $msg_jod;
        } else {
            $msg = 'Error: ' . $this->_cmd_java . ' cannot be executed';
        }

        return [$ret, $msg];
    }

    /**
     * @return array
     */
    public function get_version_jodconverter()
    {
        $ret = false;

        if (file_exists($this->_jodconverter_jar)) {
            $ret = true;
            $msg = ' jodconverter version ';
            $msg .= $this->parse_version_jodconverter();
        } else {
            $msg = 'Error: cannot find ' . $this->_jodconverter_jar;
        }

        return [$ret, $msg];
    }

    public function parse_version_jodconverter()
    {
        preg_match('/jodconverter-cli-(.*)\.jar/i', $this->_jodconverter_jar, $matches);
        if (isset($matches[1])) {
            return $matches[1];
        }

        return null;
    }

    //---------------------------------------------------------
    // utility
    //---------------------------------------------------------

    /**
     * @return bool
     */
    public function is_win_os()
    {
        if (0 === mb_strpos(PHP_OS, 'WIN')) {
            return true;
        }

        return false;
    }

    /**
     * @param $cmd
     * @return string
     */
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
     * @param $ret_array
     */
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
