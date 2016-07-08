<?php
// $Id: plugin_ini.php,v 1.1 2009/11/29 07:37:03 ohwada Exp $

//=========================================================
// webphoto module
// 2009-11-11 K.OHWADA
//=========================================================

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_plugin_ini
//=========================================================
class webphoto_plugin_ini extends webphoto_lib_plugin
{
    public $_ini_class;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_ini_class = webphoto_inc_ini::getSingleton($dirname, $trust_dirname);
        $this->_ini_class->read_main_ini();
    }

    //---------------------------------------------------------
    // ini class
    //---------------------------------------------------------
    public function get_ini($name)
    {
        return $this->_ini_class->get_ini($name);
    }

    public function explode_ini($name)
    {
        return $this->_ini_class->explode_ini($name);
    }

    // --- class end ---
}
