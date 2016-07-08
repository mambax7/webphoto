<?php
// $Id: user.php,v 1.1 2008/08/08 04:39:14 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//=========================================================
// class webphoto_xoops_user
//=========================================================
class webphoto_xoops_user
{
    public $_user_handler;

    public $_MODULE_MID = 0;
    public $_USER_UID   = 0;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        $this->_user_handler = xoops_getHandler('user');
        $this->_init();
    }

    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new webphoto_xoops_user();
        }
        return $instance;
    }

    public function _init()
    {
        $this->_MODULE_MID = $this->get_my_module_value_by_name('mid');
        $this->_USER_UID   = $this->get_my_user_value_by_name('uid');
    }

    //---------------------------------------------------------
    // my user
    //---------------------------------------------------------
    public function get_my_user_value_by_name($name, $format = 's')
    {
        global $xoopsUser;
        if (is_object($xoopsUser)) {
            return $xoopsUser->getVar($name, $format);
        }
        return false;
    }

    public function get_my_user_groups()
    {
        global $xoopsUser;
        if (is_object($xoopsUser)) {
            return $xoopsUser->getGroups();
        }
        return false;
    }

    public function get_my_user_is_login()
    {
        global $xoopsUser;
        if (is_object($xoopsUser)) {
            return true;
        }
        return false;
    }

    public function get_my_user_is_module_admin()
    {
        global $xoopsUser;
        if (is_object($xoopsUser)) {
            if ($xoopsUser->isAdmin($this->_MODULE_MID)) {
                return true;
            }
        }
        return false;
    }

    //---------------------------------------------------------
    // user handler
    //---------------------------------------------------------
    public function get_user_by_uid($uid)
    {
        return $this->_user_handler->get($uid);
    }

    public function get_user_uname_from_id($uid, $usereal = 0)
    {
        return XoopsUser::getUnameFromId($uid, $usereal);
    }

    public function build_userinfo($uid, $usereal = 0)
    {
        $uname = $this->get_user_uname_from_id($uid, $usereal);

        // geust
        $uid = (int)$uid;
        if ($uid == 0) {
            return $uname;
        }

        $str = '<a href="' . XOOPS_URL . '/userinfo.php?uid=' . $uid . '">' . $uname . '</a>';
        return $str;
    }

    public function increment_post_by_own()
    {
        return $this->increment_post_by_uid($this->_USER_UID);
    }

    public function increment_post_by_uid($uid)
    {
        if ($uid <= 0) {
            return false;
        }

        $obj = $this->_user_handler->get($uid);
        if (!is_object($obj)) {
            return false;
        }

        return $obj->incrementPost();
    }

    public function increment_post_by_num_own($num)
    {
        return $this->increment_post_by_num_uid($num, $this->_USER_UID);
    }

    public function increment_post_by_num_uid($num, $uid)
    {
        if ($uid <= 0) {
            return false;
        }

        $obj = $this->_user_handler->get($uid);
        if (!is_object($obj)) {
            return false;
        }

        $ret_code = true;

        for ($i = 0; $i < $num; ++$i) {
            $ret = $obj->incrementPost();
            if (!$ret) {
                $ret_code = false;
            }
        }

        return $ret_code;
    }

    //--------------------------------------------------------
    // xoops module
    //--------------------------------------------------------
    public function get_my_module_value_by_name($name, $format = 's')
    {
        global $xoopsModule;
        if (is_object($xoopsModule)) {
            return $xoopsModule->getVar($name, $format);
        }
        return false;
    }

    //--------------------------------------------------------
    // utility
    //--------------------------------------------------------
    public function sanitize($str)
    {
        return htmlspecialchars($str, ENT_QUOTES);
    }

    // --- class end ---
}
