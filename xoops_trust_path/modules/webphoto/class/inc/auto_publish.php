<?php
// $Id: auto_publish.php,v 1.6 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2008-11-29 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// webphoto_inc_handler -> webphoto_inc_base_ini
// 2009-04-21 K.OHWADA
// Warning: chmod()
// chmod_file()
// 2009-01-10 K.OHWADA
// tmp -> log
// 2008-12-12 K.OHWADA
// getInstance() -> getSingleton()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_inc_auto_publish
// caller webphoto_show_main webphoto_inc_public
//=========================================================

/**
 * Class webphoto_inc_auto_publish
 */
class webphoto_inc_auto_publish extends webphoto_inc_base_ini
{
    public $_table_item;

    public $_ini_safe_mode;

    public $_FILE_AUTO_PUBLISH;
    public $_TIME_AUTO_PUBLISH = 3600; // 1 hour
    public $_FLAG_AUTO_PUBLISH_CHMOD = true;
    public $_CHMOD_MODE = 0777;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_inc_auto_publish constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct();
        $this->init_base_ini($dirname, $trust_dirname);
        $this->initHandler($dirname);

        $this->_table_item = $this->prefix_dirname('item');

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
    // init
    //---------------------------------------------------------

    /**
     * @param $workdir
     */
    public function set_workdir($workdir)
    {
        $this->_FILE_AUTO_PUBLISH = $workdir . '/log/auto_publish';
    }

    //---------------------------------------------------------
    // public
    //---------------------------------------------------------
    public function auto_publish()
    {
        if ($this->check_auto_publish_time()) {
            $this->item_auto_publish(true);
            $this->item_auto_expire(true);
        }

        // set time before execute
        $this->renew_auto_publish_time();
    }

    //---------------------------------------------------------
    // private
    //---------------------------------------------------------

    /**
     * @return bool
     */
    public function check_auto_publish_time()
    {
        return $this->check_file_time($this->_FILE_AUTO_PUBLISH, $this->_TIME_AUTO_PUBLISH);
    }

    public function renew_auto_publish_time()
    {
        $this->write_file($this->_FILE_AUTO_PUBLISH, time(), 'w', $this->_FLAG_AUTO_PUBLISH_CHMOD);
    }

    /**
     * @param $file
     * @param $interval
     * @return bool
     */
    public function check_file_time($file, $interval)
    {
        // if passing interval time
        if (file_exists($file)) {
            $time = (int)trim(file_get_contents($file));
            if (($time > 0)
                && (time() > ($time + $interval))) {
                return true;
            }

            // if not exists file ( at first time )
        } else {
            return true;
        }

        return false;
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
            $this->chmod_file($file);
        }

        return $byte;
    }

    /**
     * @param $file
     */
    public function chmod_file($file)
    {
        // Warning: chmod()
        if (!$this->_ini_safe_mode) {
            chmod($file, $this->_CHMOD_MODE);
        }
    }

    //---------------------------------------------------------
    // item handler
    //---------------------------------------------------------

    /**
     * @param bool $force
     */
    public function item_auto_publish($force = false)
    {
        $rows = $this->get_item_rows_coming_publish();
        if (is_array($rows) && count($rows)) {
            foreach ($rows as $row) {
                $this->update_item_status($row['item_id'], _C_WEBPHOTO_STATUS_UPDATED, $force);
            }
        }
    }

    /**
     * @param bool $force
     */
    public function item_auto_expire($force = false)
    {
        $rows = $this->get_item_rows_coming_expire();
        if (is_array($rows) && count($rows)) {
            foreach ($rows as $row) {
                $this->update_item_status($row['item_id'], _C_WEBPHOTO_STATUS_EXPIRED, $force);
            }
        }
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_item_rows_coming_publish($limit = 0, $offset = 0)
    {
        $sql = 'SELECT * FROM ' . $this->_table_item;
        $sql .= ' WHERE item_status = ' . _C_WEBPHOTO_STATUS_OFFLINE;
        $sql .= ' AND item_time_publish > 0 ';
        $sql .= ' AND item_time_publish < ' . time();
        $sql .= ' ORDER BY item_id';

        return $this->get_rows_by_sql($sql, $limit, $offset);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_item_rows_coming_expire($limit = 0, $offset = 0)
    {
        $sql = 'SELECT * FROM ' . $this->_table_item;
        $sql .= ' WHERE item_status > 0 ';
        $sql .= ' AND item_time_expire > 0 ';
        $sql .= ' AND item_time_expire < ' . time();
        $sql .= ' ORDER BY item_id';

        return $this->get_rows_by_sql($sql, $limit, $offset);
    }

    /**
     * @param      $item_id
     * @param      $status
     * @param bool $force
     * @return mixed
     */
    public function update_item_status($item_id, $status, $force = false)
    {
        $sql = 'UPDATE ' . $this->_table_item . ' SET ';
        $sql .= ' item_status = ' . (int)$status;
        $sql .= ' WHERE item_id=' . (int)$item_id;

        return $this->query($sql, 0, 0, $force);
    }

    // --- class end ---
}
