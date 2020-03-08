<?php
// $Id: p2t_handler.php,v 1.2 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// webphoto_lib_handler -> webphoto_handler_base_ini
//---------------------------------------------------------

//=========================================================
// class webphoto_p2tHandler
//=========================================================

/**
 * Class webphoto_p2tHandler
 */
class webphoto_p2tHandler extends webphoto_handler_base_ini
{
    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_p2tHandler constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);
        $this->set_table_prefix_dirname('p2t');
        $this->set_id_name('p2t_id');
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_lib_error|\webphoto_p2tHandler
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
            'p2t_id' => 0,
            'p2t_time_create' => $time_create,
            'p2t_time_update' => $time_update,
            'p2t_photo_id' => 0,
            'p2t_tag_id' => 0,
            'p2t_uid' => 0,
        ];

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

        $sql .= 'p2t_time_create, ';
        $sql .= 'p2t_time_update, ';
        $sql .= 'p2t_photo_id, ';
        $sql .= 'p2t_tag_id, ';
        $sql .= 'p2t_uid ';

        $sql .= ') VALUES ( ';

        $sql .= (int)$p2t_time_create . ', ';
        $sql .= (int)$p2t_time_update . ', ';
        $sql .= (int)$p2t_photo_id . ', ';
        $sql .= (int)$p2t_tag_id . ', ';
        $sql .= (int)$p2t_uid . ' ';

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

        $sql .= 'p2t_time_create=' . (int)$p2t_time_create . ', ';
        $sql .= 'p2t_time_update=' . (int)$p2t_time_update . ', ';
        $sql .= 'p2t_photo_id=' . (int)$p2t_photo_id . ', ';
        $sql .= 'p2t_tag_id=' . (int)$p2t_tag_id . ', ';
        $sql .= 'p2t_uid=' . (int)$p2t_uid . ' ';

        $sql .= 'WHERE p2t_id=' . (int)$p2t_id;

        return $this->query($sql);
    }

    //---------------------------------------------------------
    // delete
    //---------------------------------------------------------

    /**
     * @param $photo_id
     * @return mixed
     */
    public function delete_by_photoid($photo_id)
    {
        $sql = 'DELETE FROM ' . $this->_table;
        $sql .= ' WHERE p2t_photo_id=' . (int)$photo_id;

        return $this->query($sql);
    }

    /**
     * @param $photo_id
     * @param $tag_id_array
     * @return bool
     */
    public function delete_by_photoid_tagid_array($photo_id, $tag_id_array)
    {
        $where = $this->build_sql_tagid_in_array($tag_id_array);
        if (!$where) {
            return true;    // no action
        }

        $sql = 'DELETE FROM ' . $this->_table;
        $sql .= ' WHERE p2t_photo_id=' . (int)$photo_id;
        $sql .= ' AND ' . $where;

        return $this->query($sql);
    }

    /**
     * @param $photo_id
     * @param $uid
     * @param $tag_id_array
     * @return bool
     */
    public function delete_by_photoid_uid_tagid_array($photo_id, $uid, $tag_id_array)
    {
        $where = $this->build_sql_tagid_in_array($tag_id_array);
        if (!$where) {
            return true;    // no action
        }

        $sql = 'DELETE FROM ' . $this->_table;
        $sql .= ' WHERE p2t_photo_id=' . (int)$photo_id;
        $sql .= ' AND p2t_uid=' . (int)$uid;
        $sql .= ' AND ' . $where;

        return $this->query($sql);
    }

    /**
     * @param $tag_id_array
     * @return bool|string
     */
    public function build_sql_tagid_in_array($tag_id_array)
    {
        if (!is_array($tag_id_array) || !count($tag_id_array)) {
            return false;
        }

        $in = implode(',', $tag_id_array);
        $sql = 'p2t_tag_id IN (' . $in . ')';

        return $sql;
    }

    //---------------------------------------------------------
    // get count
    //---------------------------------------------------------

    /**
     * @param $photo_id
     * @param $tag_id
     * @return int
     */
    public function get_count_by_photoid_tagid($photo_id, $tag_id)
    {
        $where = 'p2t_photo_id=' . (int)$photo_id;
        $where .= ' AND p2t_tag_id=' . (int)$tag_id;

        return $this->get_count_by_where($where);
    }

    //---------------------------------------------------------
    // get id array
    //---------------------------------------------------------

    /**
     * @param     $photo_id
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_tag_id_array_by_photoid($photo_id, $limit = 0, $offset = 0)
    {
        $sql = 'SELECT p2t_tag_id FROM ' . $this->_table;
        $sql .= ' WHERE p2t_photo_id=' . (int)$photo_id;
        $sql .= ' ORDER BY p2t_id ASC';

        return $this->get_first_rows_by_sql($sql, $limit, $offset);
    }

    /**
     * @param     $photo_id
     * @param     $uid
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_tag_id_array_by_photoid_uid($photo_id, $uid, $limit = 0, $offset = 0)
    {
        $sql = 'SELECT p2t_tag_id FROM ' . $this->_table;
        $sql .= ' WHERE p2t_photo_id=' . (int)$photo_id;
        $sql .= ' AND   p2t_uid=' . (int)$uid;
        $sql .= ' ORDER BY p2t_id ASC';

        return $this->get_first_rows_by_sql($sql, $limit, $offset);
    }

    /**
     * @param     $photo_id
     * @param     $uid
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_tag_id_array_by_photoid_without_uid($photo_id, $uid, $limit = 0, $offset = 0)
    {
        $sql = 'SELECT p2t_tag_id FROM ' . $this->_table;
        $sql .= ' WHERE p2t_photo_id=' . (int)$photo_id;
        $sql .= ' AND   p2t_uid <> ' . (int)$uid;
        $sql .= ' ORDER BY p2t_id ASC';

        return $this->get_first_rows_by_sql($sql, $limit, $offset);
    }

    // --- class end ---
}
