<?php
// $Id: workdir.php,v 1.6 2009/04/21 15:14:54 ohwada Exp $

//=========================================================
// webphoto module
// 2008-11-08 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-04-21 K.OHWADA
// chmod_file()
// 2008-12-12 K.OHWADA
// getInstance() -> getSingleton()
// 2008-12-05 K.OHWADA
// init()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_inc_workdir
//=========================================================

/**
 * Class webphoto_inc_workdir
 */
class webphoto_inc_workdir
{
    public $_ini_safe_mode;

    public $_DIRNAME;
    public $_TRUST_DIRNAME;
    public $_DIR_TRUST_UPLOADS;
    public $_FILE_WORKDIR;

    public $_CHMOD_MODE = 0777;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_inc_workdir constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        $this->_DIRNAME = $dirname;
        $this->_TRUST_DIRNAME = $trust_dirname;

        $this->_DIR_TRUST_UPLOADS = XOOPS_TRUST_PATH . '/modules/' . $trust_dirname . '/uploads';

        $this->_FILE_WORKDIR = $this->_DIR_TRUST_UPLOADS . '/workdir.txt';

        $this->_ini_safe_mode = ini_get('safe_mode');
    }

    /**
     * @param $dirname
     * @param $trust_dirname
     * @return mixed
     */
    public static function getSingleton($dirname, $trust_dirname)
    {
        static $singletons;
        if (!isset($singletons[$dirname])) {
            $singletons[$dirname] = new self($dirname, $trust_dirname);
        }

        return $singletons[$dirname];
    }

    //---------------------------------------------------------
    // main
    //---------------------------------------------------------

    /**
     * @return string
     */
    public function get_config_workdir()
    {
        $name = $this->_DIRNAME;

        for ($i = 0; $i < 10; ++$i) {
            $workdir = $this->_DIR_TRUST_UPLOADS . '/' . $name;
            $match = $this->read_workdir($workdir);
            if (0 == $match) {
                break;
            }
            if (2 == $match) {
                break;
            }
            $name = uniqid('work_', true);
        }

        return $workdir;
    }

    /**
     * @param $workdir
     * @return int
     */
    public function read_workdir($workdir)
    {
        $match = 0;

        if (!file_exists($this->_FILE_WORKDIR)) {
            return $match;
        }

        $lines = $this->read_file_cvs($this->_FILE_WORKDIR);

        if (!is_array($lines)) {
            return $match;
        }

        foreach ($lines as $line) {
            if (isset($line[0]) && trim($line[0]) == $workdir) {
                $match = 1;

                if ((XOOPS_DB_NAME == trim($line[1]))
                    && (XOOPS_DB_PREFIX == trim($line[2]))
                    && (XOOPS_URL == trim($line[3]))
                    && (trim($line[4]) == $this->_DIRNAME)) {
                    $match = 2;
                }

                break;
            }
        }

        return $match;
    }

    /**
     * @param $workdir
     * @return bool|int
     */
    public function write_workdir($workdir)
    {
        $data = $workdir;
        $data .= ', ';
        $data .= XOOPS_DB_NAME;
        $data .= ', ';
        $data .= XOOPS_DB_PREFIX;
        $data .= ', ';
        $data .= XOOPS_URL;
        $data .= ', ';
        $data .= $this->_DIRNAME;
        $data .= "\n";

        return $this->write_file($this->_FILE_WORKDIR, $data, 'a', true);
    }

    /**
     * @param        $file
     * @param string $mode
     * @return array|bool
     */
    public function read_file_cvs($file, $mode = 'r')
    {
        $lines = [];

        $fp = fopen($file, $mode);
        if (!$fp) {
            return false;
        }

        while (!feof($fp)) {
            $lines[] = fgetcsv($fp, 1024);
        }

        fclose($fp);

        return $lines;
    }

    /**
     * @param        $file
     * @param        $data
     * @param string $mode
     * @param bool   $flag_chmod
     * @return bool|int
     */
    public function write_file($file, $data, $mode = 'w', $flag_chmod = false)
    {
        $fp = fopen($file, $mode);
        if (!$fp) {
            return false;
        }

        $byte = fwrite($fp, $data);
        fclose($fp);

        // the user can delete this file which apache made.
        if (($byte > 0) && $flag_chmod) {
            $this->chmod_file($file, $this->_CHMOD_MODE);
        }

        return $byte;
    }

    /**
     * @param $file
     * @param $mode
     */
    public function chmod_file($file, $mode)
    {
        if (!$this->_ini_safe_mode) {
            chmod($file, $mode);
        }
    }

    /**
     * @return string
     */
    public function get_filename()
    {
        return $this->_FILE_WORKDIR;
    }

    // --- class end ---
}
