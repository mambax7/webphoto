<?php
// $Id: xoops_base.php,v 1.3 2011/11/12 11:05:02 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-24 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-11-11 K.OHWADA
// _include_setting_php()
// 2009-05-15 K.OHWADA
// _include_once_file() -> _include_global_php()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_xoops_base
// substitute for clsss/xoops/base.php
//=========================================================

/**
 * Class webphoto_xoops_base
 */
class webphoto_xoops_base extends webphoto_lib_handler
{
    public $_cached_config_search_array = null;
    public $_cached_group_objs = null;

    public $_MY_MODULE_ID = 0;
    public $_LANGUAGE;

    public $_STR_JPAPANESE = 'japanese|japaneseutf|ja_utf8';

    public $_xoops_config = null;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();

        $this->_init();
    }

    /**
     * @return \webphoto_lib_error|\webphoto_xoops_base
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
        $this->get_system_config();

        $this->_MY_MODULE_ID = $this->get_my_module_id();
        $this->_LANGUAGE = $this->get_config_by_name('language');

        $this->_include_global_php();
        $this->_include_setting_php();
    }

    public function _include_global_php()
    {
        $file = 'global.php';

        $file_sys_lang = $this->_build_system_lang_file($file, $this->_LANGUAGE);
        $file_sys_eng = $this->_build_system_lang_file($file, 'english');

        // for XCL 2.1
        $file_leg_lang = $this->_build_legacy_lang_file($file, $this->_LANGUAGE);
        $file_leg_eng = $this->_build_legacy_lang_file($file, 'english');

        if (file_exists($file_sys_lang)) {
            include_once $file_sys_lang;
        } elseif (file_exists($file_sys_eng)) {
            include_once $file_sys_eng;
        } elseif (file_exists($file_leg_lang)) {
            include_once $file_leg_lang;
        } elseif (file_exists($file_leg_eng)) {
            include_once $file_leg_eng;
        }
    }

    public function _include_setting_php()
    {
        // for XCL 2.2
        $file = 'setting.php';

        $file_leg_lang = $this->_build_legacy_lang_file($file, $this->_LANGUAGE);
        $file_leg_eng = $this->_build_legacy_lang_file($file, 'english');

        if (file_exists($file_leg_lang)) {
            include_once $file_leg_lang;
        } elseif (file_exists($file_leg_eng)) {
            include_once $file_leg_eng;
        }
    }

    /**
     * @param $file
     * @param $lang
     * @return string
     */
    public function _build_system_lang_file($file, $lang)
    {
        $str = XOOPS_ROOT_PATH . '/language/' . $lang . '/' . $file;

        return $str;
    }

    /**
     * @param $file
     * @param $lang
     * @return string
     */
    public function _build_legacy_lang_file($file, $lang)
    {
        return $this->_build_mod_lang_file($file, $lang, 'legacy');
    }

    /**
     * @param $file
     * @param $lang
     * @param $module
     * @return string
     */
    public function _build_mod_lang_file($file, $lang, $module)
    {
        $str = XOOPS_ROOT_PATH . '/modules/' . $module . '/language/' . $lang . '/' . $file;

        return $str;
    }

    /**
     * @return mixed
     */
    public function get_language()
    {
        return $this->_LANGUAGE;
    }

    /**
     * @return mixed
     */
    public function set_db_charset()
    {
        return $this->_db->set_charset();
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
        if (isset($this->_xoops_config[$name])) {
            return $this->_xoops_config[$name];
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

    //---------------------------------------------------------
    // my module
    //---------------------------------------------------------

    /**
     * @param string $format
     * @return mixed
     */
    public function get_my_module_id($format = 's')
    {
        return $this->get_module_mid_by_dirname(WEBPHOTO_DIRNAME);
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
     * @return array|bool
     */
    public function get_my_user_groups()
    {
        global $xoopsUser;
        if (is_object($xoopsUser)) {
            return $xoopsUser->getGroups();
        }

        return false;
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
     * @return array
     */
    public function get_system_config()
    {
        $conf = $this->get_config_by_modid_catid(0, 1);

        $GLOBALS['xoopsConfig'] = $conf;
        $this->_xoops_config = $conf;

        return $conf;
    }

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
     * @return array
     */
    public function get_module_config_by_dirname($dirname)
    {
        return $this->get_module_config_by_mid($this->get_module_mid_by_dirname($dirname));
    }

    /**
     * @param $mid
     * @return array
     */
    public function get_module_config_by_mid($mid)
    {
        return $this->get_config_by_modid_catid($mid, 0);
    }

    /**
     * @param $modid
     * @param $catid
     * @return array
     */
    public function get_config_by_modid_catid($modid, $catid)
    {
        $sql = 'SELECT * FROM ' . $this->db_prefix('config');
        $sql .= ' WHERE (conf_modid = ' . (int)$modid;
        $sql .= ' AND conf_catid = ' . (int)$catid;
        $sql .= ' ) ';
        $sql .= ' ORDER BY conf_order ASC';

        $rows = $this->get_rows_by_sql($sql);

        $arr = [];
        foreach ($rows as $row) {
            $arr[$row['conf_name']] = $row['conf_value'];
        }

        return $arr;
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
     * @return mixed
     */
    public function get_module_mid_by_dirname($dirname, $format = 's')
    {
        $sql = 'SELECT * FROM ' . $this->db_prefix('modules');
        $sql .= ' WHERE dirname = ' . $this->quote($dirname);
        $row = $this->get_row_by_sql($sql);

        return $row['mid'];
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
            return $module->getVar($name, $format = 's');
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
        $this->_cached_group_objs = $objs;

        return $objs;
    }

    /**
     * @param        $id
     * @param        $name
     * @param string $format
     * @return bool
     */
    public function get_group_by_id_name($id, $name, $format = 's')
    {
        if (!is_array($this->_cached_group_objs)) {
            $this->_cached_group_objs = $this->get_group_obj();
        }
        if (isset($this->_cached_group_objs[$id])) {
            return $this->_cached_group_objs[$id]->getVar($name, $format);
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

    // --- class end ---
}
