<?php
// $Id: group.php,v 1.1 2009/12/16 13:36:20 ohwada Exp $

//=========================================================
// webphoto module
// 2009-12-06 K.OHWADA
//=========================================================

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_inc_group
//=========================================================

/**
 * Class webphoto_inc_group
 */
class webphoto_inc_group
{
    public $_memberHandler;
    public $_grouppermHandler;

    public $_DIRNAME;
    public $_MODULE_ID = 0;

    public $_SYSTEM_GROUPS = [XOOPS_GROUP_ADMIN, XOOPS_GROUP_USERS, XOOPS_GROUP_ANONYMOUS];

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_inc_group constructor.
     * @param $dirname
     */
    public function __construct($dirname)
    {
        $this->_DIRNAME = $dirname;

        $this->_memberHandler = xoops_getHandler('member');
        $this->_grouppermHandler = xoops_getHandler('groupperm');

        $this->_init_xoops_module($dirname);
    }

    /**
     * @param $dirname
     * @return mixed
     */
    public static function getSingleton($dirname)
    {
        static $singletons;
        if (!isset($singletons[$dirname])) {
            $singletons[$dirname] = new self($dirname);
        }

        return $singletons[$dirname];
    }

    //---------------------------------------------------------
    // group
    //---------------------------------------------------------

    /**
     * @param $group_id
     * @return bool
     */
    public function delete_group($group_id)
    {
        $group_id = (int)$group_id;
        if ($group_id <= 0) {
            return false;   // no action
        }
        if (in_array($group_id, $this->_SYSTEM_GROUPS)) {
            return false;   // no action
        }
        $this->delete_member_group($group_id);
        $this->delete_gperm_by_group($group_id);

        return true;
    }

    //---------------------------------------------------------
    // member handler
    //---------------------------------------------------------

    /**
     * @param $name
     * @param $desc
     * @return bool
     */
    public function create_member_group($name, $desc)
    {
        $group = $this->_memberHandler->createGroup();
        $group->setVar('name', $name);
        $group->setVar('description', $desc);

        $ret = $this->_memberHandler->insertGroup($group);
        if (!$ret) {
            return false;
        }

        return $group->getVar('groupid');
    }

    /**
     * @param $group_id
     */
    public function delete_member_group($group_id)
    {
        $group = $this->_memberHandler->getGroup($group_id);
        $this->_memberHandler->deleteGroup($group);
    }

    //---------------------------------------------------------
    // groupperm handler
    //---------------------------------------------------------

    /**
     * @param $groupid
     * @param $perms
     */
    public function create_gperm_webphoto_groupid($groupid, $perms)
    {
        foreach ($perms as $id) {
            $this->create_gperm_webphoto_itemid($groupid, $id);
        }
    }

    /**
     * @param $groupid
     */
    public function create_gperm_module_admin($groupid)
    {
        $gperm = $this->_grouppermHandler->create();
        $gperm->setVar('gperm_name', 'module_admin');
        $gperm->setVar('gperm_groupid', $groupid);
        $gperm->setVar('gperm_modid', 1);
        $gperm->setVar('gperm_itemid', $this->_MODULE_ID);
        $this->_grouppermHandler->insert($gperm);
        unset($gperm);
    }

    /**
     * @param $groupid
     */
    public function create_gperm_module_read($groupid)
    {
        $gperm = $this->_grouppermHandler->create();
        $gperm->setVar('gperm_name', 'module_read');
        $gperm->setVar('gperm_groupid', $groupid);
        $gperm->setVar('gperm_modid', 1);
        $gperm->setVar('gperm_itemid', $this->_MODULE_ID);
        $this->_grouppermHandler->insert($gperm);
        unset($gperm);
    }

    /**
     * @param $groupid
     * @param $itemid
     */
    public function create_gperm_webphoto_itemid($groupid, $itemid)
    {
        $gperm = $this->_grouppermHandler->create();
        $gperm->setVar('gperm_name', _C_WEBPHOTO_GPERM_NAME);
        $gperm->setVar('gperm_groupid', $groupid);
        $gperm->setVar('gperm_modid', $this->_MODULE_ID);
        $gperm->setVar('gperm_itemid', $itemid);
        $this->_grouppermHandler->insert($gperm);
        unset($gperm);
    }

    /**
     * @param      $group_id
     * @param null $mod_id
     */
    public function delete_gperm_by_group($group_id, $mod_id = null)
    {
        $this->_grouppermHandler->deleteByGroup($group_id, $mod_id);
    }

    //---------------------------------------------------------
    // xoops_module
    //---------------------------------------------------------

    /**
     * @param $dirname
     */
    public function _init_xoops_module($dirname)
    {
        $moduleHandler = xoops_getHandler('module');
        $module = $moduleHandler->getByDirname($dirname);
        if (is_object($module)) {
            $this->_MODULE_ID = $module->getVar('mid');
        }
    }

    // --- class end ---
}
