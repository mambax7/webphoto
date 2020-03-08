<?php
// $Id: config.php,v 1.1 2011/11/12 11:07:08 ohwada Exp $

//=========================================================
// webphoto module
// 2011-11-11 K.OHWADA
//=========================================================

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_bin_config
// when command mode, use instead of class/xoops/config.php
//=========================================================

/**
 * Class webphoto_bin_config
 */
class webphoto_bin_config extends webphoto_lib_handler
{
    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return \webphoto_bin_config|\webphoto_lib_error
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
    // xoops class
    //---------------------------------------------------------

    /**
     * @param $dirname
     * @return array
     */
    public function get_config_by_dirname($dirname)
    {
        $modid = $this->get_modid_by_dirname($dirname);

        return $this->get_config_by_modid($modid);
    }

    /**
     * @param $dirname
     * @return mixed
     */
    public function get_modid_by_dirname($dirname)
    {
        $sql = 'SELECT * FROM ' . $this->db_prefix('modules');
        $sql .= ' WHERE dirname = ' . $this->quote($dirname);
        $row = $this->get_row_by_sql($sql);

        return $row['mid'];
    }

    /**
     * @param $modid
     * @return array
     */
    public function get_config_by_modid($modid)
    {
        return $this->get_config_by_modid_catid($modid, 0);
    }

    /**
     * @param $modid
     * @param $catid
     * @return array
     */
    public function get_config_by_modid_catid($modid, $catid)
    {
        $sql = 'SELECT * FROM ' . $this->db_prefix('config');
        $sql .= ' WHERE (conf_modid = ' . (int)$modid;
        $sql .= ' AND conf_catid = ' . (int)$catid;
        $sql .= ' ) ';
        $sql .= ' ORDER BY conf_order ASC';

        $rows = $this->get_rows_by_sql($sql);

        $arr = [];
        foreach ($rows as $row) {
            $arr[$row['conf_name']] = $row['conf_value'];
        }

        return $arr;
    }

    // --- class end ---
}
