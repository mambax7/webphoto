<?php
// $Id: groupperm_form.php,v 1.4 2010/02/17 04:34:47 ohwada Exp $

//=========================================================
// webphoto module
// 2009-12-06 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-02-15 K.OHWADA
// check_right()
// 2010-01-20 K.OHWADA
// XOOPS_CUBE_LEGACY
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_lib_groupperm_form
// refer myalubum's MyXoopsGroupPermForm
//=========================================================

/**
 * Class webphoto_lib_groupperm_form
 */
class webphoto_lib_groupperm_form
{
    public $_moduleHandler;
    public $_memberHandler;
    public $_grouppermHandler;

    public $_CHECKED = 'checked';

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        $this->_moduleHandler = xoops_getHandler('module');
        $this->_memberHandler = xoops_getHandler('member');
        $this->_grouppermHandler = xoops_getHandler('groupperm');
    }

    /**
     * @return \webphoto_lib_groupperm_form
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
    // main
    //---------------------------------------------------------

    /**
     * @param      $mod_id
     * @param null $action
     * @return array
     */
    public function build_param($mod_id, $action = null)
    {
        $arr = [
            'cols' => 4,
            'modid' => $mod_id,
            'action' => $action,
            'g_ticket' => $this->get_token(),
            'xoops_dirname' => $this->get_dirname($mod_id),
        ];
        if (defined('XOOPS_CUBE_LEGACY')) {
            $arr['xoops_cube_legacy'] = XOOPS_CUBE_LEGACY;
        }

        return array_merge($arr, $this->get_lang());
    }

    /**
     * @param      $mod_id
     * @param      $perm_name
     * @param      $item_array
     * @param bool $flag_admin
     * @return array
     */
    public function build_group_list($mod_id, $perm_name, $item_array, $flag_admin = false)
    {
        $system_list = $this->_memberHandler->getGroupList();

        $group_list = [];
        foreach (array_keys($system_list) as $id) {
            $group_list[$id] = $this->build_group_list_single($mod_id, $id, $system_list[$id], $perm_name, $item_array, $flag_admin);
        }

        return $group_list;
    }

    /**
     * @param      $mod_id
     * @param      $group_id
     * @param      $group_name
     * @param      $perm_name
     * @param      $item_array
     * @param bool $flag_admin
     * @return array
     */
    public function build_group_list_single($mod_id, $group_id, $group_name, $perm_name, $item_array, $flag_admin = false)
    {
        $module_admin_right = $this->check_right('module_admin', $mod_id, $group_id);
        $module_read_right = $this->check_right('module_read', $mod_id, $group_id);

        $all_checked = ($flag_admin && $module_admin_right);

        $item_id_array = $this->_grouppermHandler->getItemIds($perm_name, $group_id, $mod_id);

        $item_list = [];
        foreach ($item_array as $item_id => $item_name) {
            $item_list[$item_id] = [
                'item_id' => $item_id,
                'item_name' => $item_name,
                'checked' => $this->build_checked_array($item_id, $item_id_array, $all_checked),
            ];
        }

        $group_list = [
            'group_id' => $group_id,
            'group_name' => $group_name,
            'perm_name' => $perm_name,
            'item_list' => $item_list,
            'module_admin_checked' => $this->build_checked($module_admin_right),
            'module_read_checked' => $this->build_checked($module_read_right),
        ];

        return $group_list;
    }

    /**
     * @param $perm_name
     * @param $mod_id
     * @param $group_id
     * @return mixed
     */
    public function check_right($perm_name, $mod_id, $group_id)
    {
        return $this->_grouppermHandler->checkRight($perm_name, $mod_id, $group_id);
    }

    /**
     * @param $val
     * @param $array
     * @param $all_checked
     * @return string
     */
    public function build_checked_array($val, $array, $all_checked)
    {
        if ($all_checked) {
            return $this->_CHECKED;
        }
        if (is_array($array) && in_array($val, $array)) {
            return $this->_CHECKED;
        }

        return '';
    }

    /**
     * @param $val
     * @return string
     */
    public function build_checked($val)
    {
        if ($val) {
            return $this->_CHECKED;
        }

        return '';
    }

    /**
     * @param $id
     * @return bool|mixed
     */
    public function get_dirname($id)
    {
        $obj = $this->_moduleHandler->get($id);
        if (is_object($obj)) {
            return $obj->getVar('dirname', 'n');
        }

        return false;
    }

    /**
     * @param $id
     * @return bool
     */
    public function get_group_name($id)
    {
        $obj = $this->_memberHandler->getGroup($id);
        if (is_object($obj)) {
            return $obj->getVar('name');
        }

        return false;
    }

    /**
     * @return bool
     */
    public function get_token()
    {
        global $xoopsGTicket;
        if (is_object($xoopsGTicket)) {
            return $xoopsGTicket->issue(__LINE__);
        }

        return false;
    }

    /**
     * @return array
     */
    public function get_lang()
    {
        $arr = [
            'lang_none' => _NONE,
            'lang_all' => _ALL,
            'lang_submit' => _SUBMIT,
            'lang_cancel' => _CANCEL,
        ];

        return $arr;
    }

    // --- class end ---
}
