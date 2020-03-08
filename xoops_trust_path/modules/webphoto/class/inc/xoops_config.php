<?php
// $Id: xoops_config.php,v 1.1 2011/11/12 11:07:08 ohwada Exp $

//=========================================================
// webphoto module
// 2011-11-11 K.OHWADA
//=========================================================

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_inc_xoops_config
//=========================================================

/**
 * Class webphoto_inc_xoops_config
 */
class webphoto_inc_xoops_config
{
    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        // dummy
    }

    /**
     * @return \webphoto_inc_xoops_config
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
     * @return mixed
     */
    public function get_config_by_dirname($dirname)
    {
        $modid = $this->get_modid_by_dirname($dirname);

        return $this->get_config_by_modid($modid);
    }

    /**
     * @param $modid
     * @return mixed
     */
    public function get_config_by_modid($modid)
    {
        $configHandler = xoops_getHandler('config');

        return $configHandler->getConfigsByCat(0, $modid);
    }

    /**
     * @param $dirname
     * @return bool
     */
    public function get_modid_by_dirname($dirname)
    {
        $moduleHandler = xoops_getHandler('module');
        $module = $moduleHandler->getByDirname($dirname);
        if (!is_object($module)) {
            return false;
        }

        return $module->getVar('mid');
    }

    // --- class end ---
}
