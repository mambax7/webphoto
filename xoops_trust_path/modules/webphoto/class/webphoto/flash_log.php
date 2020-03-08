<?php
// $Id: flash_log.php,v 1.2 2008/12/20 06:11:27 ohwada Exp $

//=========================================================
// webphoto module
// 2008-11-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-12-12 K.OHWADA
// getInstance() -> getSingleton()
//---------------------------------------------------------

//---------------------------------------------------------
// http://code.jeroenwijering.com/trac/wiki/Flashvars3
//
// Only for the mediaplayer.
// Set this to a serverside script that can process statistics.
// The player will send it a POST every time an item starts/stops.
// To send callbacks automatically to Google Analytics,
// set this to urchin (if you use the old urchinTracker code)
// or analytics (if you use the new pageTracker code).
//
// The player returns $id, $title, $file, $state, $duration in POST variable
// $state (start/stop)
// $duration is set at stop
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_flash_log
// caller webphoto_main_callback webphoto_admin_item_manager
//=========================================================

/**
 * Class webphoto_flash_log
 */
class webphoto_flash_log
{
    public $_config_class;
    public $_utility_class;

    public $_WORK_DIR;
    public $_LOG_FILE;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_flash_log constructor.
     * @param $dirname
     */
    public function __construct($dirname)
    {
        $this->_utility_class = webphoto_lib_utility::getInstance();
        $this->_post_class = webphoto_lib_post::getInstance();

        $this->_init_xoops_config($dirname);

        $this->_LOG_FILE = $this->_WORK_DIR . '/log/flash.txt';
    }

    /**
     * @param null $dirname
     * @return \webphoto_flash_log
     */
    public static function getInstance($dirname = null)
    {
        static $instance;
        if (null === $instance) {
            $instance = new self($dirname);
        }

        return $instance;
    }

    //---------------------------------------------------------
    // callback
    //---------------------------------------------------------

    /**
     * @return bool|int
     */
    public function callback_log()
    {
        $id = $this->_post_class->get_post_int('id');
        $duration = $this->_post_class->get_post_int('duration');
        $title = $this->_post_class->get_post_text('title');
        $file = $this->_post_class->get_post_text('file');
        $state = $this->_post_class->get_post_text('state');

        if ('start' != $state) {
            return true;    // no action
        }

        $http_referer = null;
        $remote_addr = null;

        if (null !== (\Xmf\Request::getString('HTTP_REFERER', '', 'SERVER'))) {
            $http_referer = \Xmf\Request::getString('HTTP_REFERER', '', 'SERVER');
        }

        if (isset($_SERVER['REMOTE_ADDR'])) {
            $remote_addr = $_SERVER['REMOTE_ADDR'];
        }

        $data = formatTimestamp(time(), 'm') . ',';
        $data .= $http_referer . ',';
        $data .= $remote_addr . ',';
        $data .= $state . ',';
        $data .= $id . ',';
        $data .= $title . ',';
        $data .= $file . ',';
        $data .= $duration;
        $data .= "\r\n";

        return $this->append_log($data);
    }

    //---------------------------------------------------------
    // write read
    //---------------------------------------------------------

    /**
     * @return string
     */
    public function get_filename()
    {
        return $this->_LOG_FILE;
    }

    /**
     * @param $data
     * @return bool|int
     */
    public function append_log($data)
    {
        return $this->_utility_class->write_file($this->_LOG_FILE, $data, 'a', true);
    }

    /**
     * @return array|bool
     */
    public function read_log()
    {
        if (!file_exists($this->_LOG_FILE)) {
            return false;   // no file
        }

        $lines = $this->_utility_class->read_file_cvs($this->_LOG_FILE);

        if (!is_array($lines)) {
            return false;
        }

        $count = count($lines);

        // empty file
        if (0 == $count) {
            return [];    // no data
        }

        // one line and empty line
        if ((1 == $count) && empty($lines[0])) {
            return [];    // no data
        }

        // remove last line if empty
        if (empty($lines[$count][0])) {
            array_pop($lines);
        }

        return $lines;
    }

    /**
     * @return bool|int
     */
    public function empty_log()
    {
        if (!file_exists($this->_LOG_FILE)) {
            return false;
        }

        return $this->_utility_class->write_file($this->_LOG_FILE, '', 'w', true);
    }

    //---------------------------------------------------------
    // xoops_config
    //---------------------------------------------------------

    /**
     * @param $dirname
     */
    public function _init_xoops_config($dirname)
    {
        $configHandler = webphoto_inc_config::getSingleton($dirname);

        $this->_WORK_DIR = $configHandler->get_by_name('workdir');
    }

    // --- class end ---
}
