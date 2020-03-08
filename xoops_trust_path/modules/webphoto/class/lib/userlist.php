<?php
// $Id: userlist.php,v 1.2 2010/01/26 08:25:45 ohwada Exp $

//=========================================================
// webphoto module
// 2009-12-06 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-01-20 K.OHWADA
// XOOPS_CUBE_LEGACY
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_lib_userlist
//=========================================================

/**
 * Class webphoto_lib_userlist
 */
class webphoto_lib_userlist
{
    public $_memberHandler;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        $this->_memberHandler = xoops_getHandler('member');
    }

    /**
     * @return \webphoto_lib_userlist
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
    // function
    //---------------------------------------------------------

    /**
     * @param     $group_id
     * @param int $limit
     * @param int $start
     * @return array
     */
    public function build_param_by_groupid($group_id, $limit = 0, $start = 0)
    {
        $arr = [
            'group_id' => $group_id,
            'total' => $this->get_user_count_by_groupid($group_id),
            'user_list' => $this->get_users_by_groupid($group_id, $limit, $start),
        ];
        if (defined('XOOPS_CUBE_LEGACY')) {
            $arr['xoops_cube_legacy'] = XOOPS_CUBE_LEGACY;
        }

        return $arr;
    }

    /**
     * @param $group_id
     * @return mixed
     */
    public function get_user_count_by_groupid($group_id)
    {
        return $this->_memberHandler->getUserCountByGroup($group_id);
    }

    /**
     * @param     $group_id
     * @param int $limit
     * @param int $start
     * @return array
     */
    public function get_users_by_groupid($group_id, $limit = 0, $start = 0)
    {
        $users = $this->_memberHandler->getUsersByGroup($group_id, true, $limit, $start);

        $arr = [];
        foreach ($users as $user) {
            $uid = $user->getVar('uid', 'n');
            $uname = $user->getVar('uname', 'n');
            $name = $user->getVar('name', 'n');
            $user_regdate = $user->getVar('user_regdate', 'n');
            $last_login = $user->getVar('last_login', 'n');
            $posts = $user->getVar('posts', 'n');
            $level = $user->getVar('level', 'n');

            $arr[] = [
                'uid' => $uid,
                'uname' => $uname,
                'name' => $name,
                'user_regdate' => $user_regdate,
                'last_login' => $last_login,
                'posts' => $posts,
                'level' => $level,
                'uname_s' => $this->sanitize($uname),
                'name_s' => $this->sanitize($name),
                'user_regdate_disp' => formatTimestamp($user_regdate, 's'),
                'last_login_disp' => formatTimestamp($last_login, 'l'),
            ];
        }

        return $arr;
    }

    /**
     * @param $str
     * @return string
     */
    public function sanitize($str)
    {
        return htmlspecialchars($str, ENT_QUOTES);
    }

    // --- class end ---
}
