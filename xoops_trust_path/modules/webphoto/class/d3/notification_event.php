<?php
// $Id: notification_event.php,v 1.1.1.1 2008/06/21 12:22:22 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_d3_notification
//
// subsitute for core's notifcationHandler->triggerEvent()
// modify from pico_main_trigger_event
//=========================================================

/**
 * Class webphoto_d3_notification_event
 */
class webphoto_d3_notification_event
{
    // xoops param
    public $_MODULE_ID = 0;
    public $_MODULE_DIRNAME = null;
    public $_MODULE_NAME = null;
    public $_xoops_language = null;
    public $_xoops_uid = 0;
    public $_xoops_uname = null;

    public $_DIRNAME;
    public $_MODULE_DIR;
    public $_MODULE_URL;
    public $_TRUST_DIR;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        $this->_init_xoops_param();
    }

    /**
     * @return \webphoto_d3_notification_event
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
     * @param $trust_dirname
     */
    public function init($dirname, $trust_dirname)
    {
        $this->_DIRNAME = $dirname;
        $this->_MODULE_DIR = XOOPS_ROOT_PATH . '/modules/' . $dirname;
        $this->_MODULE_URL = XOOPS_URL . '/modules/' . $dirname;
        $this->_TRUST_DIR = XOOPS_TRUST_PATH . '/modules/' . $trust_dirname;
    }

    //---------------------------------------------------------
    // public
    //---------------------------------------------------------

    /**
     * @param       $category
     * @param       $item_id
     * @param       $event
     * @param array $extra_tags
     * @param array $user_list
     * @param null  $omit_user_id
     */
    public function trigger_event($category, $item_id, $event, $extra_tags = [], $user_list = [], $omit_user_id = null)
    {
        $notificationHandler = xoops_getHandler('notification');

        // Check if event is enabled
        $configHandler = xoops_getHandler('config');
        $mod_config = $configHandler->getConfigsByCat(0, $this->_MODULE_ID);
        if (empty($mod_config['notification_enabled'])) {
            return false;
        }

        $category_info = notificationCategoryInfo($category, $this->_MODULE_ID);
        $event_info = notificationEventInfo($category, $event, $this->_MODULE_ID);
        if (!in_array(notificationGenerateConfig($category_info, $event_info, 'option_name'), $mod_config['notification_events']) && empty($event_info['invisible'])) {
            return false;
        }

        if (null === $omit_user_id) {
            $omit_user_id = $this->_xoops_uid;
        }

        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('not_modid', (int)$this->_MODULE_ID));
        $criteria->add(new Criteria('not_category', $category));
        $criteria->add(new Criteria('not_itemid', (int)$item_id));
        $criteria->add(new Criteria('not_event', $event));
        $mode_criteria = new CriteriaCompo();
        $mode_criteria->add(new Criteria('not_mode', XOOPS_NOTIFICATION_MODE_SENDALWAYS), 'OR');
        $mode_criteria->add(new Criteria('not_mode', XOOPS_NOTIFICATION_MODE_SENDONCETHENDELETE), 'OR');
        $mode_criteria->add(new Criteria('not_mode', XOOPS_NOTIFICATION_MODE_SENDONCETHENWAIT), 'OR');
        $criteria->add($mode_criteria);

        if (!empty($user_list)) {
            $user_criteria = new CriteriaCompo();
            foreach ($user_list as $user) {
                $user_criteria->add(new Criteria('not_uid', $user), 'OR');
            }
            $criteria->add($user_criteria);
        }

        $notifications = $notificationHandler->getObjects($criteria);
        if (empty($notifications)) {
            return;
        }

        // Add some tag substitutions here
        $tags = [];
        // {X_ITEM_NAME} {X_ITEM_URL} {X_ITEM_TYPE} from lookup_func are disabled
        $tags['X_MODULE'] = $this->_MODULE_NAME;
        $tags['X_MODULE_URL'] = XOOPS_URL . '/modules/' . $this->_MODULE_DIRNAME . '/';
        $tags['X_NOTIFY_CATEGORY'] = $category;
        $tags['X_NOTIFY_EVENT'] = $event;

        $template = $event_info['mail_template'] . '.tpl';
        $subject = $event_info['mail_subject'];

        $mail_template_dir = $this->_get_mail_template_dir($template);
        if (!$mail_template_dir) {
            return;
        }

        foreach ($notifications as $notification) {
            if (empty($omit_user_id) || $notification->getVar('not_uid') != $omit_user_id) {
                // user-specific tags
                //$tags['X_UNSUBSCRIBE_URL'] = 'TODO';
                // TODO: don't show unsubscribe link if it is 'one-time' ??
                $tags['X_UNSUBSCRIBE_URL'] = XOOPS_URL . '/notifications.php';
                $tags = array_merge($tags, $extra_tags);

                $notification->notifyUser($mail_template_dir, $template, $subject, $tags);
            }
        }
    }

    //---------------------------------------------------------
    // private
    //---------------------------------------------------------

    /**
     * @param $template
     * @return bool|string
     */
    public function _get_mail_template_dir($template)
    {
        // mail template dir
        $dir_trust_lang = $this->_TRUST_DIR . '/language/' . $this->_xoops_language . '/mail_template/';
        $dir_root_lang = $this->_MODULE_DIR . '/language/' . $this->_xoops_language . '/mail_template/';
        $dir_trust_eng = $this->_TRUST_DIR . '/language/english/mail_template/';
        $dir_root_eng = $this->_MODULE_DIR . '/language/english/mail_template/';

        if (file_exists($dir_root_lang . $template)) {
            return $dir_root_lang;
        } elseif (file_exists($dir_trust_lang . $template)) {
            return $dir_trust_lang;
        } elseif (file_exists($dir_root_eng . $template)) {
            return $dir_root_eng;
        } elseif (file_exists($dir_trust_eng . $template)) {
            return $dir_trust_eng;
        }

        return false;
    }

    //---------------------------------------------------------
    // xoops param
    //---------------------------------------------------------
    public function _init_xoops_param()
    {
        global $xoopsConfig, $xoopsUser, $xoopsModule;

        $this->_xoops_language = $xoopsConfig['language'];

        if (is_object($xoopsModule)) {
            $this->_MODULE_ID = $xoopsModule->mid();
            $this->_MODULE_DIRNAME = $xoopsModule->getVar('dirname', 'n');
            $this->_MODULE_NAME = $xoopsModule->getVar('name', 'n');
        }

        if (is_object($xoopsUser)) {
            $this->_xoops_uid = $xoopsUser->getVar('uid');
            $this->_xoops_uname = $xoopsUser->getVar('uname');
        }
    }

    // --- class end ---
}
