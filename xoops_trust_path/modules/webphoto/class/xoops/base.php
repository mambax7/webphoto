<?php
// $Id: base.php,v 1.11 2010/10/10 12:46:37 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-10-01 K.OHWADA
// get_member_user_count()
// 2010-02-01 K.OHWADA
// get_module_info_value_by_dirname()
// 2009-12-06 K.OHWADA
// get_system_groups()
// 2009-04-19 K.OHWADA
// get_xoops_themecss()
// 2009-01-25 K.OHWADA
// get_block_options_by_bid()
// 2009-01-10 K.OHWADA
// user_to_server_time()
// 2008-11-16 K.OHWADA
// get_cached_groups()
// 2008-10-01 K.OHWADA
// use XOOPS_GROUP_ANONYMOUS in get_my_user_groups()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_xoops_base
//=========================================================

/**
 * Class webphoto_xoops_base
 */
class webphoto_xoops_base
{
    public $_cached_config_search_array = null;
    public $_cached_group_objs = null;

    public $_MY_MODULE_ID = 0;
    public $_LANGUAGE;

    public $_STR_JPAPANESE = 'japanese|japaneseutf|ja_utf8';

    public $_SYSTEM_GROUPS = [XOOPS_GROUP_ADMIN, XOOPS_GROUP_USERS, XOOPS_GROUP_ANONYMOUS];

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        $this->_init();
    }

    /**
     * @return \webphoto_xoops_base
     */
    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new self();
        }

        return $instance;
    }

    public function _init()
    {
        $this->_MY_MODULE_ID = $this->get_my_module_id();
        $this->_LANGUAGE = $this->get_config_by_name('language');
    }

    //---------------------------------------------------------
    // config
    //---------------------------------------------------------

    /**
     * @param $name
     * @return bool
     */
    public function get_config_by_name($name)
    {
        global $xoopsConfig;
        if (isset($xoopsConfig[$name])) {
            return $xoopsConfig[$name];
        }

        return false;
    }

    /**
     * @param null $str
     * @return bool
     */
    public function is_japanese($str = null)
    {
        if (empty($str)) {
            $str = $this->_STR_JPAPANESE;
        }

        if (in_array($this->_LANGUAGE, explode('|', $str))) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function get_xoops_themecss()
    {
        return getcss($this->get_config_by_name('theme_set'));
    }

    //---------------------------------------------------------
    // my module
    //---------------------------------------------------------

    /**
     * @param string $format
     * @return bool|mixed
     */
    public function get_my_module_id($format = 's')
    {
        return $this->get_my_module_value_by_name('mid', $format);
    }

    /**
     * @param string $format
     * @return bool|mixed
     */
    public function get_my_module_name($format = 's')
    {
        return $this->get_my_module_value_by_name('name', $format);
    }

    /**
     * @param bool $flag_format
     * @return bool|float|mixed
     */
    public function get_my_module_version($flag_format = false)
    {
        $ver = $this->get_my_module_value_by_name('version');
        if ($flag_format) {
            $ver = $this->convertVersionIntToFloat($ver);
        }

        return $ver;
    }

    /**
     * @param        $name
     * @param string $format
     * @return bool|mixed
     */
    public function get_my_module_value_by_name($name, $format = 's')
    {
        global $xoopsModule;
        if (is_object($xoopsModule)) {
            return $xoopsModule->getVar($name, $format);
        }

        return false;
    }

    //---------------------------------------------------------
    // my user
    //---------------------------------------------------------

    /**
     * @param string $format
     * @return bool|mixed
     */
    public function get_my_user_uid($format = 's')
    {
        return $this->get_my_user_value_by_name('uid', $format);
    }

    /**
     * @param string $format
     * @return bool|mixed
     */
    public function get_my_user_uname($format = 's')
    {
        return $this->get_my_user_value_by_name('uname', $format);
    }

    /**
     * @param        $name
     * @param string $format
     * @return bool|mixed
     */
    public function get_my_user_value_by_name($name, $format = 's')
    {
        global $xoopsUser;
        if (is_object($xoopsUser)) {
            return $xoopsUser->getVar($name, $format);
        }

        return false;
    }

    /**
     * @return array
     */
    public function get_my_user_groups()
    {
        global $xoopsUser;
        if (is_object($xoopsUser)) {
            return $xoopsUser->getGroups();
        }

        return [XOOPS_GROUP_ANONYMOUS];
    }

    /**
     * @return bool
     */
    public function get_my_user_is_login()
    {
        global $xoopsUser;
        if (is_object($xoopsUser)) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function get_my_user_is_module_admin()
    {
        global $xoopsUser;
        if (is_object($xoopsUser)) {
            if ($xoopsUser->isAdmin($this->_MY_MODULE_ID)) {
                return true;
            }
        }

        return false;
    }

    //---------------------------------------------------------
    // config handler
    //---------------------------------------------------------

    /**
     * @return int
     */
    public function has_my_module_config()
    {
        $configHandler = xoops_getHandler('config');

        return count($configHandler->getConfigs(new Criteria('conf_modid', $this->_MY_MODULE_ID)));
    }

    /**
     * @param $dirname
     * @return mixed
     */
    public function get_module_config_by_dirname($dirname)
    {
        return $this->get_module_config_by_mid($this->get_module_mid_by_dirname($dirname));
    }

    /**
     * @param $mid
     * @return mixed
     */
    public function get_module_config_by_mid($mid)
    {
        $configHandler = xoops_getHandler('config');

        return $configHandler->getConfigsByCat(0, $mid);
    }

    /**
     * @return mixed
     */
    public function get_search_config()
    {
        $configHandler = xoops_getHandler('config');
        $conf = $configHandler->getConfigsByCat(XOOPS_CONF_SEARCH);
        $this->_cached_config_search_array = $conf;

        return $conf;
    }

    /**
     * @param $name
     * @return bool|mixed
     */
    public function get_search_config_by_name($name)
    {
        if (!is_array($this->_cached_config_search_array)) {
            $this->_cached_config_search_array = $this->get_search_config();
        }
        if (isset($this->_cached_config_search_array[$name])) {
            return $this->_cached_config_search_array[$name];
        }

        return false;
    }

    //---------------------------------------------------------
    // module handler
    //---------------------------------------------------------

    /**
     * @param        $dirname
     * @param string $format
     * @return bool
     */
    public function get_module_mid_by_dirname($dirname, $format = 's')
    {
        return $this->get_module_value_by_dirname($dirname, 'mid', $format);
    }

    /**
     * @param        $dirname
     * @param string $format
     * @return bool
     */
    public function get_module_name_by_dirname($dirname, $format = 's')
    {
        return $this->get_module_value_by_dirname($dirname, 'name', $format);
    }

    /**
     * @param        $dirname
     * @param        $name
     * @param string $format
     * @return bool
     */
    public function get_module_value_by_dirname($dirname, $name, $format = 's')
    {
        $moduleHandler = xoops_getHandler('module');
        $module = $moduleHandler->getByDirname($dirname);
        if (is_object($module)) {
            return $module->getVar($name, $format);
        }

        return false;
    }

    /**
     * @param      $dirname
     * @param bool $flag_format
     * @return bool|float
     */
    public function get_module_info_version_by_dirname($dirname, $flag_format = false)
    {
        $ver = $this->get_module_info_value_by_dirname($dirname, 'version');
        if ($ver && $flag_format) {
            $ver = $this->convertVersionFromModinfoToInt($ver);
        }

        return $ver;
    }

    /**
     * @param $dirname
     * @param $name
     * @return bool
     */
    public function get_module_info_value_by_dirname($dirname, $name)
    {
        $moduleHandler = xoops_getHandler('module');
        $module = $moduleHandler->getByDirname($dirname);
        if (is_object($module)) {
            return $module->getInfo($name);
        }

        return false;
    }

    //---------------------------------------------------------
    // user handler
    //---------------------------------------------------------

    /**
     * @param     $uid
     * @param int $usereal
     * @return string
     */
    public function get_user_uname_from_id($uid, $usereal = 0)
    {
        return XoopsUser::getUnameFromId($uid, $usereal);
    }

    /**
     * @param $uid
     * @return bool|mixed
     */
    public function get_user_email_from_id($uid)
    {
        $user_handler = xoops_getHandler('user');
        $obj = $user_handler->get($uid);
        if (is_object($obj)) {
            return $obj->getVar('email');
        }

        return false;
    }

    /**
     * @param     $uid
     * @param int $usereal
     * @return string
     */
    public function build_userinfo($uid, $usereal = 0)
    {
        $uname = $this->get_user_uname_from_id($uid, $usereal);

        // geust
        $uid = (int)$uid;
        if (0 == $uid) {
            return $uname;
        }

        $str = '<a href="' . XOOPS_URL . '/userinfo.php?uid=' . $uid . '">' . $uname . '</a>';

        return $str;
    }

    //---------------------------------------------------------
    // group handler
    //---------------------------------------------------------

    /**
     * @return mixed
     */
    public function get_group_obj()
    {
        $groupHandler = xoops_getHandler('group');
        $objs = $groupHandler->getObjects(null, true);

        return $objs;
    }

    /**
     * @return array|null
     */
    public function get_cached_group_obj()
    {
        if (!is_array($this->_cached_group_objs)) {
            $this->_cached_group_objs = $this->get_group_obj();
        }

        return $this->_cached_group_objs;
    }

    /**
     * @param bool   $none
     * @param string $none_name
     * @param string $format
     * @return array
     */
    public function get_cached_groups($none = false, $none_name = '---', $format = 's')
    {
        $objs = $this->get_cached_group_obj();
        $arr = [];
        if ($none) {
            $arr[0] = $none_name;
        }
        foreach ($objs as $obj) {
            $groupid = $obj->getVar('groupid', $format);
            $name = $obj->getVar('name', $format);
            $arr[$groupid] = $name;
        }

        return $arr;
    }

    /**
     * @param        $id
     * @param        $name
     * @param string $format
     * @return bool
     */
    public function get_cached_group_by_id_name($id, $name, $format = 's')
    {
        $objs = $this->get_cached_group_obj();

        if (isset($objs[$id])) {
            return $objs[$id]->getVar($name, $format);
        }

        return false;
    }

    /**
     * @return array
     */
    public function get_system_groups()
    {
        return $this->_SYSTEM_GROUPS;
    }

    /**
     * @param $id
     * @return bool
     */
    public function is_system_group($id)
    {
        if (in_array($id, $this->_SYSTEM_GROUPS)) {
            return true;
        }

        return false;
    }

    //---------------------------------------------------------
    // member handler
    //---------------------------------------------------------

    /**
     * @param int $limit
     * @param int $start
     * @return mixed
     */
    public function get_member_user_list($limit = 0, $start = 0)
    {
        $criteria = new CriteriaCompo();
        $criteria->setStart($start);
        $criteria->setLimit($limit);
        $criteria->setSort('uname');

        $memberHandler = xoops_getHandler('member');

        return $memberHandler->getUserList($criteria);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function get_member_user($id)
    {
        $memberHandler = xoops_getHandler('member');

        return $memberHandler->getUser($id);
    }

    /**
     * @param      $group_id
     * @param bool $asobject
     * @param int  $limit
     * @param int  $start
     * @return mixed
     */
    public function get_member_users_by_group($group_id, $asobject = false, $limit = 0, $start = 0)
    {
        $memberHandler = xoops_getHandler('member');

        return $memberHandler->getUsersByGroup($group_id, $asobject, $limit, $start);
    }

    /**
     * @param null $criteria
     * @return mixed
     */
    public function get_member_user_count($criteria = null)
    {
        $memberHandler = xoops_getHandler('member');

        return $memberHandler->getUserCount($criteria);
    }

    //--------------------------------------------------------
    // xoops block handler
    //--------------------------------------------------------

    /**
     * @param $bid
     * @return array|null
     */
    public function get_block_options_by_bid($bid)
    {
        $obj = $this->get_block_by_bid($bid);
        if (is_object($obj)) {
            $options = explode('|', $obj->getVar('options'));

            return $options;
        }

        return null;
    }

    /**
     * @param $bid
     * @return \XoopsObject
     */
    public function get_block_by_bid($bid)
    {
        $blockHandler = xoops_getHandler('block');

        return $blockHandler->get($bid);
    }

    //---------------------------------------------------------
    // timestamp
    //---------------------------------------------------------

    /**
     * @param     $time
     * @param int $default
     * @return float|int
     */
    public function user_to_server_time($time, $default = 0)
    {
        if ($time <= 0) {
            return $default;
        }

        global $xoopsConfig, $xoopsUser;
        if ($xoopsUser) {
            $timeoffset = $xoopsUser->getVar('timezone_offset');
        } else {
            $timeoffset = $xoopsConfig['default_TZ'];
        }
        $timestamp = $time - (($timeoffset - $xoopsConfig['server_TZ']) * 3600);

        return $timestamp;
    }

    //---------------------------------------------------------
    // same as Legacy_Utils
    //---------------------------------------------------------

    /**
     * @param $version
     * @return float
     */
    public function convertVersionFromModinfoToInt($version)
    {
        return round(100 * (float)$version);
    }

    /**
     * @param $version
     * @return float
     */
    public function convertVersionIntToFloat($version)
    {
        return round((float)((int)$version / 100), 2);
    }

    // --- class end ---
}
