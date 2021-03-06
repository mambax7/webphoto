<?php
// $Id: group_permission.php,v 1.6 2010/02/17 04:34:47 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-02-15 K.OHWADA
// add $flag_admin in has_perm()
// 2009-11-11 K.OHWADA
// webphoto_inc_handler -> webphoto_inc_base_ini
// 2008-12-12 K.OHWADA
// getInstance() -> getSingleton()
// 2008-07-01 K.OHWADA
// webphoto_xoops_base -> xoops_getHandler()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_inc_group_permission
// caller webphoto_permission webphoto_inc_xoops_version
//=========================================================

/**
 * Class webphoto_inc_group_permission
 */
class webphoto_inc_group_permission extends webphoto_inc_base_ini
{
    public $_cached_perms = [];

    public $_xoops_mid = 0;
    public $_xoops_uid = 0;
    public $_xoops_groups = null;
    public $_is_module_adimin = false;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_inc_group_permission constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct();
        $this->init_base_ini($dirname, $trust_dirname);
        $this->initHandler($dirname);

        $this->_init_xoops($dirname);
        $this->_init_permission($dirname);
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
    // has permit
    //---------------------------------------------------------

    /**
     * @param      $name
     * @param bool $flag_admin
     * @return bool
     */
    public function has_perm($name, $flag_admin = false)
    {
        if ($flag_admin && $this->_is_module_adimin) {
            return true;
        }
        $bit = constant(mb_strtoupper('_B_WEBPHOTO_GPERM_' . $name));

        return $this->_has_perm_by_bit($bit);
    }

    //---------------------------------------------------------
    // cache
    //---------------------------------------------------------

    /**
     * @param $bit
     * @return bool
     */
    public function _has_perm_by_bit($bit)
    {
        if ($this->_cached_perms & $bit) {
            return true;
        }

        return false;
    }

    //---------------------------------------------------------
    // xoops_group_permission
    //---------------------------------------------------------

    /**
     * @param $dirname
     * @return int
     */
    public function _init_permission($dirname)
    {
        $perms = 0;

        // correct SQL error
        // no action when not installed this module
        if (empty($this->_xoops_mid)) {
            return $perms;
        }

        $sql = 'SELECT gperm_itemid FROM ' . $this->_db->prefix('group_permission');
        $sql .= ' WHERE gperm_modid=' . (int)$this->_xoops_mid;
        $sql .= ' AND gperm_name=' . $this->quote(_C_WEBPHOTO_GPERM_NAME);
        $sql .= ' AND ( ' . $this->_build_where_groupid() . ' )';

        $rows = $this->get_rows_by_sql($sql);
        if (!is_array($rows) || !count($rows)) {
            return 0;
        }

        foreach ($rows as $row) {
            $perms |= $row['gperm_itemid'];
        }

        $this->_cached_perms = $perms;
    }

    /**
     * @return string
     */
    public function _build_where_groupid()
    {
        if (is_array($this->_xoops_groups) && count($this->_xoops_groups)) {
            $where = 'gperm_groupid IN (';
            foreach ($this->_xoops_groups as $groupid) {
                $where .= "$groupid,";
            }
            $where = mb_substr($where, 0, -1) . ')';
        } else {
            $where = 'gperm_groupid=' . XOOPS_GROUP_ANONYMOUS;
        }

        return $where;
    }

    //---------------------------------------------------------
    // xoops class
    //---------------------------------------------------------

    /**
     * @param $dirname
     */
    public function _init_xoops($dirname)
    {
        $moduleHandler = xoops_getHandler('module');
        $module = $moduleHandler->getByDirname($dirname);
        if (is_object($module)) {
            $this->_xoops_mid = $module->getVar('mid');
        }

        global $xoopsUser;
        if (is_object($xoopsUser)) {
            $this->_xoops_uid = $xoopsUser->getVar('uid');
            $this->_xoops_groups = $xoopsUser->getGroups();
            $this->_is_module_adimin = $xoopsUser->isAdmin($this->_xoops_mid);
        }
    }

    // --- class end ---
}
