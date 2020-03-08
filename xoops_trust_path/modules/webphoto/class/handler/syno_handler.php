<?php
// $Id: syno_handler.php,v 1.3 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// webphoto_lib_handler -> webphoto_handler_base_ini
// 2008-07-01 K.OHWADA
// _C_ -> _P_
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_synoHandler
//=========================================================

/**
 * Class webphoto_synoHandler
 */
class webphoto_synoHandler extends webphoto_handler_base_ini
{
    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_synoHandler constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);
        $this->set_table_prefix_dirname('syno');
        $this->set_id_name('syno_id');
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_lib_error|\webphoto_synoHandler
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
            'syno_id' => 0,
            'syno_time_create' => $time_create,
            'syno_time_update' => $time_update,
            'syno_weight' => 0,
            'syno_key' => '',
            'syno_value' => '',
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

        $sql .= 'syno_time_create, ';
        $sql .= 'syno_time_update, ';
        $sql .= 'syno_weight, ';
        $sql .= 'syno_key, ';
        $sql .= 'syno_value ';

        $sql .= ') VALUES ( ';

        $sql .= (int)$syno_time_create . ', ';
        $sql .= (int)$syno_time_update . ', ';
        $sql .= (int)$syno_weight . ', ';
        $sql .= $this->quote($syno_key) . ', ';
        $sql .= $this->quote($syno_value) . ' ';

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

        $sql .= 'syno_time_create=' . (int)$syno_time_create . ', ';
        $sql .= 'syno_time_update=' . (int)$syno_time_update . ', ';
        $sql .= 'syno_weight=' . (int)$syno_weight . ', ';
        $sql .= 'syno_key=' . $this->quote($syno_key) . ', ';
        $sql .= 'syno_value=' . $this->quote($syno_value) . ' ';

        $sql .= 'WHERE syno_id=' . (int)$syno_id;

        return $this->query($sql);
    }

    //---------------------------------------------------------
    // rows
    //---------------------------------------------------------

    /**
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_rows_orderby_weight_asc($limit = 0, $offset = 0)
    {
        $orderby = 'syno_weight ASC, syno_id ASC';

        return $this->get_rows_by_orderby($orderby, $limit = 0, $offset = 0);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_rows_orderby_weight_desc($limit = 0, $offset = 0)
    {
        $orderby = 'syno_weight DESC, syno_id DESC';

        return $this->get_rows_by_orderby($orderby, $limit = 0, $offset = 0);
    }

    // --- class end ---
}
