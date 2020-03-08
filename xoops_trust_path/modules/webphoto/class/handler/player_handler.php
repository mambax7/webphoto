<?php
// $Id: player_handler.php,v 1.4 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// webphoto_lib_handler -> webphoto_handler_base_ini
// player_title_default
// 2009-10-25 K.OHWADA
// BUG: player id is not correctly selected
// 2009-04-19 K.OHWADA
// build_row_options()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_player_handler
//=========================================================

/**
 * Class webphoto_player_handler
 */
class webphoto_player_handler extends webphoto_handler_base_ini
{
    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_player_handler constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);
        $this->set_table_prefix_dirname('player');
        $this->set_id_name('player_id');
        $this->set_title_name($this->get_ini('player_title_name'));
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_lib_error|\webphoto_player_handler
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
            'player_id' => 0,
            'player_time_create' => $time_create,
            'player_time_update' => $time_update,
            'player_pid' => 0,
            'player_style' => 0,
            'player_title' => $this->get_ini('player_title_default'),
            'player_width' => $this->get_ini('player_width_default'),
            'player_height' => $this->get_ini('player_height_default'),
            'player_displaywidth' => 0,
            'player_displayheight' => 0,
            'player_screencolor' => '',
            'player_backcolor' => '',
            'player_frontcolor' => '',
            'player_lightcolor' => '',
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

        $sql .= 'player_time_create, ';
        $sql .= 'player_time_update, ';
        $sql .= 'player_style, ';
        $sql .= 'player_title, ';
        $sql .= 'player_width, ';
        $sql .= 'player_height, ';
        $sql .= 'player_displaywidth, ';
        $sql .= 'player_displayheight, ';
        $sql .= 'player_screencolor, ';
        $sql .= 'player_backcolor, ';
        $sql .= 'player_frontcolor, ';
        $sql .= 'player_lightcolor ';

        $sql .= ') VALUES ( ';

        if ($player_id > 0) {
            $sql .= (int)$player_id . ', ';
        }

        $sql .= (int)$player_time_create . ', ';
        $sql .= (int)$player_time_update . ', ';
        $sql .= (int)$player_style . ', ';
        $sql .= $this->quote($player_title) . ', ';
        $sql .= (int)$player_width . ', ';
        $sql .= (int)$player_height . ', ';
        $sql .= (int)$player_displaywidth . ', ';
        $sql .= (int)$player_displayheight . ', ';
        $sql .= $this->quote($player_screencolor) . ', ';
        $sql .= $this->quote($player_backcolor) . ', ';
        $sql .= $this->quote($player_frontcolor) . ', ';
        $sql .= $this->quote($player_lightcolor) . ' ';

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
        $sql .= 'player_time_create=' . (int)$player_time_create . ', ';
        $sql .= 'player_time_update=' . (int)$player_time_update . ', ';
        $sql .= 'player_style=' . (int)$player_style . ', ';
        $sql .= 'player_title=' . $this->quote($player_title) . ', ';
        $sql .= 'player_width=' . (int)$player_width . ', ';
        $sql .= 'player_height=' . (int)$player_height . ', ';
        $sql .= 'player_displaywidth=' . (int)$player_displaywidth . ', ';
        $sql .= 'player_displayheight=' . (int)$player_displayheight . ', ';
        $sql .= 'player_screencolor=' . $this->quote($player_screencolor) . ', ';
        $sql .= 'player_backcolor=' . $this->quote($player_backcolor) . ', ';
        $sql .= 'player_frontcolor=' . $this->quote($player_frontcolor) . ', ';
        $sql .= 'player_lightcolor=' . $this->quote($player_lightcolor) . ' ';
        $sql .= 'WHERE player_id=' . (int)$player_id;

        return $this->query($sql);
    }

    //---------------------------------------------------------
    // get rows
    //---------------------------------------------------------

    /**
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_rows_list($limit = 0, $offset = 0)
    {
        $sql = 'SELECT * FROM ' . $this->_table;
        $sql .= ' WHERE player_id > 0 ';
        $sql .= ' ORDER BY player_title';

        return $this->get_rows_by_sql($sql, $limit, $offset);
    }

    /**
     * @param     $title
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_rows_by_title($title, $limit = 0, $offset = 0)
    {
        $sql = 'SELECT * FROM ' . $this->_table;
        $sql .= ' WHERE player_title = ' . $this->quote($title);
        $sql .= ' ORDER BY player_id';

        return $this->get_rows_by_sql($sql, $limit, $offset);
    }

    //---------------------------------------------------------
    // option
    //---------------------------------------------------------

    /**
     * @return array
     */
    public function get_style_options()
    {
        $arr = [
            '0' => _WEBPHOTO_PLAYER_STYLE_MONO,
            //      '1' => _WEBPHOTO_PLAYER_STYLE_THEME ,
            '2' => _WEBPHOTO_PLAYER_STYLE_PLAYER,
            //      '3' => _WEBPHOTO_PLAYER_STYLE_PAGE ,
        ];

        return $arr;
    }

    //---------------------------------------------------------
    // selbox
    //---------------------------------------------------------
    // BUG: player id is not correctly selected

    /**
     * @param      $preset_id
     * @param bool $flag_undefined
     * @return null|string
     */
    public function build_row_options($preset_id, $flag_undefined = false)
    {
        $player_title_name = $this->get_ini('player_title_name');

        $rows = $this->get_rows_by_orderby($player_title_name);

        if ($flag_undefined) {
            array_unshift($rows, $this->create());
        }

        return $this->build_form_select_options($rows, $player_title_name, $preset_id);
    }

    // --- class end ---
}
