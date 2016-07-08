<?php
// $Id: groupperm.php,v 1.1.1.1 2008/06/21 12:22:18 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

// XOOPS Cube 2.1
if (defined('XOOPS_CUBE_LEGACY')) {
    include_once XOOPS_ROOT_PATH . '/modules/legacy/include/xoops2_system_constants.inc.php';

    // XOOPS 2.0
} else {
    include_once XOOPS_ROOT_PATH . '/modules/system/constants.php';
}

//=========================================================
// class webphoto_xoops_groupperm
//=========================================================
class webphoto_xoops_groupperm
{
    public $_groupperm_handler;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        $this->_groupperm_handler = xoops_getHandler('groupperm');
    }

    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new webphoto_xoops_groupperm();
        }
        return $instance;
    }

    //---------------------------------------------------------
    // get
    //---------------------------------------------------------
    public function has_system_image()
    {
        return $this->_check_right(XOOPS_SYSTEM_IMAGE);
    }

    public function has_system_comment()
    {
        return $this->_check_right(XOOPS_SYSTEM_COMMENT);
    }

    public function _check_right($itemid, $name = 'system_admin', $groupid = null, $modid = 1)
    {
        if (empty($groupid)) {
            $groupid = $this->_get_xoops_user_groups();
        }

        return $this->_groupperm_handler->checkRight($name, $itemid, $groupid, $modid);
    }

    //---------------------------------------------------------
    // xoops param
    //---------------------------------------------------------
    public function _get_xoops_user_groups()
    {
        global $xoopsUser;
        if (is_object($xoopsUser)) {
            return $xoopsUser->getGroups();
        }
        return XOOPS_GROUP_ANONYMOUS;
    }

    // --- class end ---
}
