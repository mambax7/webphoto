<?php
// $Id: base_ini.php,v 1.2 2010/01/25 10:03:07 ohwada Exp $

//=========================================================
// webphoto module
// 2009-11-11 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-01-10 K.OHWADA
// isset_ini()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_base_ini
//=========================================================

/**
 * Class webphoto_base_ini
 */
class webphoto_base_ini extends webphoto_lib_base
{
    public $_ini_class;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_base_ini constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_ini_class = webphoto_inc_ini::getSingleton($dirname, $trust_dirname);
        $this->_ini_class->read_main_ini();
    }

    //---------------------------------------------------------
    // ini class
    //---------------------------------------------------------

    /**
     * @param $name
     * @return mixed
     */
    public function get_ini($name)
    {
        return $this->_ini_class->get_ini($name);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function explode_ini($name)
    {
        return $this->_ini_class->explode_ini($name);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function isset_ini($name)
    {
        return $this->_ini_class->isset_ini($name);
    }

    // --- class end ---
}
