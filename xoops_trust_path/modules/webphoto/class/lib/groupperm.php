<?php
// $Id: groupperm.php,v 1.1 2009/12/16 13:36:20 ohwada Exp $

//=========================================================
// webphoto module
// 2009-12-06 K.OHWADA
//=========================================================

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_lib_groupperm
// refer myalubum's mygroupperm.php
//=========================================================

/**
 * Class webphoto_lib_groupperm
 */
class webphoto_lib_groupperm
{
    public $_db;
    public $_moduleHandler;
    public $_memberHandler;
    public $_grouppermHandler;

    public $_errors = [];
    public $_msg_array = [];

    public $_flag_system = false;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        $this->_db = XoopsDatabaseFactory::getDatabaseConnection();
        $this->_moduleHandler = xoops_getHandler('module');
        $this->_memberHandler = xoops_getHandler('member');
        $this->_grouppermHandler = xoops_getHandler('groupperm');
    }

    /**
     * @return \webphoto_lib_groupperm
     */
    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new self();
        }

        return $instance;
    }

    //---------------------------------------------------------
    // main
    //---------------------------------------------------------

    /**
     * @param      $mod_id
     * @param      $perms
     * @param bool $flag_system
     * @return bool
     */
    public function modify($mod_id, $perms, $flag_system = false)
    {
        $this->_flag_system = $flag_system;

        // check by the permission of eather 'altsys' or 'system'
        if (1 == $mod_id) {
            $module = $this->_moduleHandler->getByDirname('altsys');
            if (!is_object($module)) {
                $module = $this->_moduleHandler->getByDirname('system');
                if (!is_object($module)) {
                    $this->set_error('there is no altsys nor system.');

                    return false;
                }
            }
            $mid = $module->getVar('mid');
            $xoops_groups = $this->get_xoops_groups();
            if (!is_array($xoops_groups)
                || !$this->_grouppermHandler->checkRight('module_admin', $mid, $xoops_groups)) {
                $this->set_error('only admin of altsys can access this area');

                return false;
            }

            // check the permission of 'module_admin' of the module
        } else {
            if ($mod_id <= 0 || !$this->is_admin($mod_id)) {
                $this->set_error(_NOPERM);

                return false;
            }
            $module = $this->_moduleHandler->get($mod_id);
            if (!is_object($module) || !$module->getVar('isactive')) {
                $this->set_error(_MODULENOEXIST);

                return false;
            }
        }

        if (!is_array($perms['groups']) || !is_array($perms['itemname'])) {
            $this->set_error('not set perms');

            return false;   // no ection
        }

        $this->include_language();
        $module_name = $module->getVar('name');
        $group_list = $this->_memberHandler->getGroupList();

        foreach ($perms['groups'] as $group_id => $group_data) {
            if ($this->_flag_system) {
                $ret = $this->delete_gperm_system($mod_id, $group_id);
                if (!$ret) {
                    $this->set_msg(sprintf(_MD_AM_PERMRESETNG, $module_name));
                }
            }

            if (isset($group_list[$group_id])) {
                $group_name = $group_list[$group_id];
            } else {
                continue;
            }

            $this->exec_itemname($mod_id, $module_name, $group_id, $group_name, $group_data, $perms['itemname']);
        }
    }

    /**
     * @param $mod_id
     * @param $module_name
     * @param $group_id
     * @param $group_name
     * @param $group_data
     * @param $perms_itemname
     */
    public function exec_itemname($mod_id, $module_name, $group_id, $group_name, $group_data, $perms_itemname)
    {
        foreach ($perms_itemname as $perm_name => $item_data) {
            if (!$this->check_perm_name($perm_name)) {
                $ret = $this->delete_gperm_local($mod_id, $group_id, $perm_name);
                if (!$ret) {
                    $this->set_msg(sprintf(_MD_AM_PERMRESETNG, $module_name));
                    continue;
                }
            }

            if (isset($group_data[$perm_name])) {
                $group_item_ids = $group_data[$perm_name];
            } else {
                continue;
            }

            $this->exec_itemdata($mod_id, $group_id, $group_name, $perm_name, $group_item_ids, $item_data);
        }
    }

    /**
     * @param $mod_id
     * @param $group_id
     * @param $group_name
     * @param $perm_name
     * @param $group_item_ids
     * @param $item_data
     */
    public function exec_itemdata($mod_id, $group_id, $group_name, $perm_name, $group_item_ids, $item_data)
    {
        foreach ($item_data as $item_id => $item_name) {
            $selected = isset($group_item_ids[$item_id]) ? $group_item_ids[$item_id] : 0;
            if (1 != $selected) {
                continue;
            }

            if (!$this->_flag_system && $this->check_perm_name($perm_name)) {
                continue;
            }

            $ret = $this->insert_groupperm($mod_id, $group_id, $item_id, $perm_name);
            if ($ret) {
                $fmt = _MD_AM_PERMADDOK;
            } else {
                $fmt = _MD_AM_PERMADDNG;
            }
            $this->set_msg(sprintf($fmt, '<b>' . $perm_name . '</b>', '<b>' . $item_name . '</b>', '<b>' . $group_name . '</b>'));
        }
    }

    /**
     * @param $mod_id
     * @param $group_id
     * @param $item_id
     * @param $perm_name
     */
    public function insert_groupperm($mod_id, $group_id, $item_id, $perm_name)
    {
        if ($this->check_perm_name($perm_name)) {
            $gperm = $this->create_gperm($group_id, $perm_name, 1, $mod_id);
        } else {
            $gperm = $this->create_gperm($group_id, $perm_name, $mod_id, $item_id);
        }

        $ret = $this->_grouppermHandler->insert($gperm);

        unset($gperm);

        return $ret;
    }

    /**
     * @param $parents
     * @param $item_ids
     * @return bool
     */
    public function check_parent_ids($parents, $item_ids)
    {
        if ('' == $parents) {
            return true;
        }

        // one of the parent items were not selected, so skip this item
        $parent_ids = explode(':', $parents);
        foreach ($parent_ids as $pid) {
            if (0 != $pid && !in_array($pid, array_keys($item_ids))) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $group_id
     * @param $gperm_name
     * @param $gperm_modid
     * @param $gperm_itemid
     * @return \XoopsObject
     */
    public function create_gperm($group_id, $gperm_name, $gperm_modid, $gperm_itemid)
    {
        $gperm = $this->_grouppermHandler->create();
        $gperm->setVar('gperm_groupid', $group_id);
        $gperm->setVar('gperm_name', $gperm_name);
        $gperm->setVar('gperm_modid', $gperm_modid);
        $gperm->setVar('gperm_itemid', $gperm_itemid);

        return $gperm;
    }

    /**
     * @param $mod_id
     * @param $group_id
     * @return mixed
     */
    public function delete_gperm_system($mod_id, $group_id)
    {
        $criteria = new CriteriaCompo(new Criteria('gperm_itemid', (int)$mod_id));
        $criteria->add(new Criteria('gperm_groupid', (int)$group_id));
        $criteria->add(new Criteria('gperm_modid', 1));
        $criteria2 = new CriteriaCompo(new Criteria('gperm_name', 'module_admin'));
        $criteria2->add(new Criteria('gperm_name', 'module_read'), 'OR');
        $criteria->add($criteria2);

        return $this->_grouppermHandler->deleteAll($criteria);
    }

    /**
     * @param      $mod_id
     * @param      $group_id
     * @param      $perm_name
     * @param null $item_id
     * @return mixed
     */
    public function delete_gperm_local($mod_id, $group_id, $perm_name, $item_id = null)
    {
        $criteria = new CriteriaCompo(new Criteria('gperm_modid', (int)$mod_id));
        $criteria->add(new Criteria('gperm_groupid', (int)$group_id));
        if ($perm_name) {
            $criteria->add(new Criteria('gperm_name', $perm_name));
            if ($item_id) {
                $criteria->add(new Criteria('gperm_itemid', (int)$item_id));
            }
        }

        return $this->_grouppermHandler->deleteAll($criteria);
    }

    /**
     * @param $perm_name
     * @return bool
     */
    public function check_perm_name($perm_name)
    {
        if (('module_admin' == $perm_name)
            || ('module_read' == $perm_name)) {
            return true;
        }

        return false;
    }

    //---------------------------------------------------------
    // xoops param
    //---------------------------------------------------------
    public function include_language()
    {
        global $xoopsConfig;
        $language = $xoopsConfig['language'];

        $file_xc_lang = XOOPS_ROOT_PATH . '/modules/legacy/language/' . $language . '/admin.php';
        $file_xc_eng = XOOPS_ROOT_PATH . '/modules/legacy/language/english/admin.php';
        $file_20_lang = XOOPS_ROOT_PATH . '/modules/system/language/' . $language . '/admin.php';
        $file_20_eng = XOOPS_ROOT_PATH . '/modules/system/language/english/admin.php';

        // XOOPS Cube 2.1
        if (defined('XOOPS_CUBE_LEGACY')) {
            if (file_exists($file_xc_lang)) {
                include_once $file_xc_lang;
            } else {
                include_once $file_xc_eng;
            }

            // XOOPS 2.0
        } elseif (file_exists($file_20_lang)) {
            include_once $file_20_lang;
        } else {
            include_once $file_20_eng;
        }
    }

    /**
     * @return array|bool
     */
    public function get_xoops_groups()
    {
        global $xoopsUser;
        if (is_object($xoopsUser)) {
            return $xoopsUser->getGroups();
        }

        return false;
    }

    /**
     * @param $mod_id
     * @return bool
     */
    public function is_admin($mod_id)
    {
        global $xoopsUser;
        if (is_object($xoopsUser)) {
            return $xoopsUser->isAdmin($mod_id);
        }

        return false;
    }

    //---------------------------------------------------------
    // error
    //---------------------------------------------------------

    /**
     * @param $msg
     */
    public function set_error($msg)
    {
        // array type
        if (is_array($msg)) {
            foreach ($msg as $m) {
                $this->_errors[] = $m;
            }

            // string type
        } else {
            $arr = explode("\n", $msg);
            foreach ($arr as $m) {
                $this->_errors[] = $m;
            }
        }
    }

    /**
     * @return array
     */
    public function get_errors()
    {
        return $this->_errors;
    }

    /**
     * @param $msg
     */
    public function set_msg($msg)
    {
        // array type
        if (is_array($msg)) {
            $arr = $msg;

        // string type
        } else {
            $arr = explode("\n", $msg);
        }

        foreach ($arr as $m) {
            $m = trim($m);
            if ($m) {
                $this->_msg_array[] = $m;
            }
        }
    }

    /**
     * @return array
     */
    public function get_msg_array()
    {
        return $this->_msg_array;
    }

    // --- class end ---
}
