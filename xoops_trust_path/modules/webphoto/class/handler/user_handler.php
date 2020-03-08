<?php
// $Id: user_handler.php,v 1.3 2011/05/10 02:56:39 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-05-01 K.OHWADA
// change get_row_by_email()
// 2009-11-11 K.OHWADA
// webphoto_lib_handler -> webphoto_handler_base_ini
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_user_handler
//=========================================================

/**
 * Class webphoto_user_handler
 */
class webphoto_user_handler extends webphoto_handler_base_ini
{
    public $_cached_email_array = [];

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_user_handler constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);
        $this->set_table_prefix_dirname('user');
        $this->set_id_name('user_id');
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_lib_error|\webphoto_user_handler
     */
    public static function getInstance($dirname = null, $trust_dirname = null)
    {
        static $instance;
        if (null === $instance) {
            $instance = new self($dirname, $trust_dirname);
        }

        return $instance;
    }

    //---------------------------------------------------------
    // create
    //---------------------------------------------------------

    /**
     * @param bool $flag_new
     * @return array|void
     */
    public function create($flag_new = false)
    {
        $time_create = 0;
        $time_update = 0;

        if ($flag_new) {
            $time = time();
            $time_create = $time;
            $time_update = $time;
        }

        $arr = [
            'user_id' => 0,
            'user_time_create' => $time_create,
            'user_time_update' => $time_update,
            'user_uid' => 0,
            'user_cat_id' => 0,
            'user_email' => '',
        ];

        for ($i = 1; $i <= _C_WEBPHOTO_MAX_USER_TEXT; ++$i) {
            $arr['user_text' . $i] = '';
        }

        return $arr;
    }

    //---------------------------------------------------------
    // insert
    //---------------------------------------------------------

    /**
     * @param $row
     * @return bool|void
     */
    public function insert($row)
    {
        extract($row);

        $sql = 'INSERT INTO ' . $this->_table . ' (';

        $sql .= 'user_time_create, ';
        $sql .= 'user_time_update, ';
        $sql .= 'user_uid, ';
        $sql .= 'user_cat_id, ';

        for ($i = 1; $i <= _C_WEBPHOTO_MAX_USER_TEXT; ++$i) {
            $sql .= 'user_text' . $i . ', ';
        }

        $sql .= 'user_email ';

        $sql .= ') VALUES ( ';

        $sql .= (int)$user_time_create . ', ';
        $sql .= (int)$user_time_update . ', ';
        $sql .= (int)$user_uid . ', ';
        $sql .= (int)$user_cat_id . ', ';

        for ($i = 1; $i <= _C_WEBPHOTO_MAX_USER_TEXT; ++$i) {
            $sql .= $this->quote($row['user_text' . $i]) . ', ';
        }

        $sql .= $this->quote($user_email) . ' ';

        $sql .= ')';

        $ret = $this->query($sql);
        if (!$ret) {
            return false;
        }

        return $this->_db->getInsertId();
    }

    //---------------------------------------------------------
    // update
    //---------------------------------------------------------

    /**
     * @param $row
     * @return mixed
     */
    public function update($row)
    {
        extract($row);

        $sql = 'UPDATE ' . $this->_table . ' SET ';

        $sql .= 'user_time_create=' . (int)$user_time_create . ', ';
        $sql .= 'user_time_update=' . (int)$user_time_update . ', ';
        $sql .= 'user_uid=' . (int)$user_uid . ', ';
        $sql .= 'user_cat_id=' . (int)$user_cat_id . ', ';

        for ($i = 1; $i <= _C_WEBPHOTO_MAX_USER_TEXT; ++$i) {
            $name = 'user_text' . $i;
            $sql .= $name . '=' . $this->quote($row[$name]) . ', ';
        }

        $sql .= 'user_email=' . $this->quote($user_email) . ' ';

        $sql .= 'WHERE user_id=' . (int)$user_id;

        return $this->query($sql);
    }

    //---------------------------------------------------------
    // get row
    //---------------------------------------------------------

    /**
     * @param $uid
     * @return bool
     */
    public function get_row_by_uid($uid)
    {
        $sql = 'SELECT * FROM ' . $this->_table;
        $sql .= ' WHERE user_uid=' . (int)$uid;

        return $this->get_row_by_sql($sql);
    }

    /**
     * @param $email
     * @return bool
     */
    public function get_row_by_email($email)
    {
        $email = $this->quote($email);

        $sql = 'SELECT * FROM ' . $this->_table;
        $sql .= ' WHERE user_email=' . $email;
        $sql .= ' OR user_text2=' . $email;
        $sql .= ' OR user_text3=' . $email;
        $sql .= ' OR user_text4=' . $email;
        $sql .= ' OR user_text5=' . $email;

        return $this->get_row_by_sql($sql);
    }

    /**
     * @param $email
     * @return bool|mixed
     */
    public function get_cached_row_by_email($email)
    {
        if (isset($this->_cached_email_array[$email])) {
            return $this->_cached_email_array[$email];
        }

        $row = $this->get_row_by_email($email);
        if (!is_array($row)) {
            return false;
        }

        $this->_cached_email_array[$email] = $row;

        return $row;
    }

    // --- class end ---
}
