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

class webphoto_inc_xoops_config
{

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
            $instance = new webphoto_inc_xoops_config();
        }
        return $instance;
    }

    //---------------------------------------------------------
    // xoops class
    //---------------------------------------------------------
    public function get_config_by_dirname($dirname)
    {
        $modid = $this->get_modid_by_dirname($dirname);
        return $this->get_config_by_modid($modid);
    }

    public function get_config_by_modid($modid)
    {
        $config_handler = xoops_getHandler('config');
        return $config_handler->getConfigsByCat(0, $modid);
    }

    public function get_modid_by_dirname($dirname)
    {
        $module_handler = xoops_getHandler('module');
        $module         = $module_handler->getByDirname($dirname);
        if (!is_object($module)) {
            return false;
        }
        return $module->getVar('mid');
    }

    // --- class end ---
}
