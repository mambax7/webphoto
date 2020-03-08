<?php
// $Id: tag_handler.php,v 1.2 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// webphoto_lib_handler -> webphoto_handler_base_ini
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_tagHandler
//=========================================================

/**
 * Class webphoto_tagHandler
 */
class webphoto_tagHandler extends webphoto_handler_base_ini
{
    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_tagHandler constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);
        $this->set_table_prefix_dirname('tag');
        $this->set_id_name('tag_id');
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_lib_error|\webphoto_tagHandler
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
            'tag_id' => 0,
            'tag_time_create' => $time_create,
            'tag_time_update' => $time_update,
            'tag_name' => '',
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

        $sql .= 'tag_time_create, ';
        $sql .= 'tag_time_update, ';
        $sql .= 'tag_name ';

        $sql .= ') VALUES ( ';

        $sql .= (int)$tag_time_create . ', ';
        $sql .= (int)$tag_time_update . ', ';
        $sql .= $this->quote($tag_name) . ' ';

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

        $sql .= 'tag_time_create=' . (int)$tag_time_create . ', ';
        $sql .= 'tag_time_update=' . (int)$tag_time_update . ', ';
        $sql .= 'tag_name=' . $this->quote($tag_name) . ' ';

        $sql .= 'WHERE tag_id=' . (int)$tag_id;

        return $this->query($sql);
    }

    //---------------------------------------------------------
    // get row
    //---------------------------------------------------------

    /**
     * @param $name
     * @return bool
     */
    public function get_row_by_name($name)
    {
        $sql = 'SELECT * FROM ' . $this->_table;
        $sql .= ' WHERE tag_name=' . $this->quote($name);

        return $this->get_row_by_sql($sql);
    }

    // --- class end ---
}
