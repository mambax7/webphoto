<?php
// $Id: notification_select.php,v 1.3 2008/07/05 12:54:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-07-01 K.OHWADA
// added _get_script()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//---------------------------------------------------------
// xoops system files
//---------------------------------------------------------
include_once XOOPS_ROOT_PATH . '/include/notification_constants.php';
include_once XOOPS_ROOT_PATH . '/include/notification_functions.php';

//=========================================================
// class webphoto_d3_notification_select
// subsitute for core's notification_select.php
//=========================================================

/**
 * Class webphoto_d3_notification_select
 */
class webphoto_d3_notification_select
{
    public $_notificationHandler;

    public $_DIRNAME;
    public $_MODULE_DIR;
    public $_MODULE_URL;

    public $_MODULE_ID = 0;
    public $_xoops_uid = 0;
    public $_xoops_notify_method = 0;
    public $_is_module_admin = false;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        $this->_notificationHandler = xoops_getHandler('notification');

        $this->_init_xoops_param();
    }

    /**
     * @return \webphoto_d3_notification_select
     */
    public static function getInstance()
    {
        static $instance;
        if (null === $instance) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * @param $dirname
     */
    public function init($dirname)
    {
        $this->_DIRNAME = $dirname;
        $this->_MODULE_DIR = XOOPS_ROOT_PATH . '/modules/' . $dirname;
        $this->_MODULE_URL = XOOPS_URL . '/modules/' . $dirname;
    }

    //---------------------------------------------------------
    // public
    //---------------------------------------------------------

    /**
     * @return bool|string
     */
    public function get_new_php_self()
    {
        $phpself = $_SERVER['PHP_SELF'];
        $pos = mb_strpos($phpself, 'index.php');
        $new = mb_substr($phpself, 0, $pos + 9);

        return $new;
    }

    /**
     * @return array|bool
     */
    public function build()
    {
        $event_count = 0;
        $notification = [];

        if (empty($this->_xoops_uid) || !$this->_notificationEnabled('inline')) {
            return false;
        }

        $categories = $this->_notificationSubscribableCategoryInfo();
        if (empty($categories)) {
            return false;
        }

        foreach ($categories as $category) {
            $section['name'] = $category['name'];
            $section['title'] = $category['title'];
            $section['description'] = $category['description'];
            $section['itemid'] = $category['item_id'];
            $section['events'] = [];
            $subscribed_events = $this->_getSubscribedEvents($category['name'], $category['item_id'], $this->_MODULE_ID, $this->_xoops_uid);

            $events = $this->_notificationEvents($category['name'], true);
            foreach ($events as $event) {
                if (!empty($event['admin_only']) && !$this->_is_module_admin) {
                    continue;
                }
                if (!empty($event['invisible'])) {
                    continue;
                }
                $subscribed = in_array($event['name'], $subscribed_events) ? 1 : 0;
                $section['events'][$event['name']] = [
                    'name' => $event['name'],
                    'title' => $event['title'],
                    'caption' => $event['caption'],
                    'description' => $event['description'],
                    'subscribed' => $subscribed,
                ];
                ++$event_count;
            }

            $notification['categories'][$category['name']] = $section;
        }

        if (0 == $event_count) {
            return false;
        }

        //  $notification['target_page'] = "notification_update.php";
        $notification['target_page'] = $this->_MODULE_URL . '/index.php?fct=notification_update';

        //  $notification['redirect_script'] = xoops_getenv('PHP_SELF');
        $notification['redirect_script'] = $this->_get_script();

        //  $xoopsTpl->assign(array('lang_activenotifications' => _NOT_ACTIVENOTIFICATIONS, 'lang_notificationoptions' => _NOT_NOTIFICATIONOPTIONS, 'lang_updateoptions' => _NOT_UPDATEOPTIONS, 'lang_updatenow' => _NOT_UPDATENOW, 'lang_category' => _NOT_CATEGORY, 'lang_event' => _NOT_EVENT, 'lang_events' => _NOT_EVENTS, 'lang_checkall' => _NOT_CHECKALL, 'lang_notificationmethodis' => _NOT_NOTIFICATIONMETHODIS, 'lang_change' => _NOT_CHANGE, 'editprofile_url' => XOOPS_URL . '/edituser.php?uid=' . $xoopsUser->getVar('uid')));

        // probably notificationEvents include language file
        if (!defined('_NOT_EMAIL')) {
            $this->_include_lang_file();
        }

        switch ($this->_xoops_notify_method) {
            case XOOPS_NOTIFICATION_METHOD_DISABLE:
                $user_method = _NOT_DISABLE;
                break;
            case XOOPS_NOTIFICATION_METHOD_PM:
                $user_method = _NOT_PM;
                break;
            case XOOPS_NOTIFICATION_METHOD_EMAIL:
                $user_method = _NOT_EMAIL;
                break;
        }

        $notification['user_method'] = $user_method;
        $notification['token'] = $this->_create_token();

        return $notification;
    }

    /**
     * @param $category
     * @param $item_id
     * @param $module_id
     * @param $user_id
     * @return mixed
     */
    public function _getSubscribedEvents($category, $item_id, $module_id, $user_id)
    {
        return $this->_notificationHandler->getSubscribedEvents($category, $item_id, $module_id, $user_id);
    }

    /**
     * @param      $style
     * @param null $module_id
     * @return bool
     */
    public function _notificationEnabled($style, $module_id = null)
    {
        return notificationEnabled($style, $module_id);
    }

    /**
     * @param null $module_id
     * @return mixed
     */
    public function _notificationSubscribableCategoryInfo($module_id = null)
    {
        return notificationSubscribableCategoryInfo($module_id);
    }

    /**
     * @param      $category_name
     * @param      $enabled_only
     * @param null $module_id
     * @return mixed
     */
    public function _notificationEvents($category_name, $enabled_only, $module_id = null)
    {
        return notificationEvents($category_name, $enabled_only, $module_id);
    }

    /**
     * @return string
     */
    public function _get_script()
    {
        $path = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : null;
        $query = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : null;

        // if path_info
        if ($path) {
            $ret = $this->_MODULE_URL . '/index.php';

        // if query
        } elseif ($query) {
            $ret = $this->_MODULE_URL . '/index.php';

        // else
        } else {
            $ret = xoops_getenv('PHP_SELF');
        }

        return $ret;
    }

    // for XOOPS 2.0.18

    public function _create_token()
    {
        if (is_object($GLOBALS['xoopsSecurity'])) {
            return $GLOBALS['xoopsSecurity']->createToken();
        }

        return null;
    }

    //---------------------------------------------------------
    // xoops param
    //---------------------------------------------------------
    public function _init_xoops_param()
    {
        global $xoopsUser, $xoopsModule;

        if (is_object($xoopsModule)) {
            $this->_MODULE_ID = $xoopsModule->mid();
        }

        if (is_object($xoopsUser)) {
            $this->_xoops_uid = $xoopsUser->getVar('uid');
            $this->_xoops_notify_method = $xoopsUser->getVar('notify_method');

            if ($xoopsUser->isAdmin($this->_MODULE_ID)) {
                $this->_is_module_admin = true;
            }
        }
    }

    public function _include_lang_file()
    {
        global $xoopsConfig;
        $LANGUAGE = $xoopsConfig['language'];
        if (file_exists(XOOPS_ROOT_PATH . '/language/' . $LANGUAGE . '/notification.php')) {
            include_once XOOPS_ROOT_PATH . '/language/' . $LANGUAGE . '/notification.php';
        } else {
            include_once XOOPS_ROOT_PATH . '/language/english/notification.php';
        }
    }

    // --- class end ---
}
