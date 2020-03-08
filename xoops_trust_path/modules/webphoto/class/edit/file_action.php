<?php
// $Id: file_action.php,v 1.2 2011/11/13 07:20:20 ohwada Exp $

//=========================================================
// webphoto module
// 2010-10-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-11-11 K.OHWADA
// set_flag_force_db()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_edit_file_action
//=========================================================

/**
 * Class webphoto_edit_file_action
 */
class webphoto_edit_file_action extends webphoto_edit_base_create
{
    public $_jpeg_create_class;
    public $_middle_thumb_create_class;

    public $_FILE_LIST;

    public $_flag_force_db = false;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_edit_file_action constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_jpeg_create_class = webphoto_edit_jpeg_create::getInstance($dirname, $trust_dirname);

        $this->_middle_thumb_create_class = webphoto_edit_middle_thumb_create::getInstance($dirname, $trust_dirname);

        $this->_FILE_LIST = explode('|', _C_WEBPHOTO_FILE_LIST);
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_edit_file_action|\webphoto_lib_error
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
    // set & get param
    //---------------------------------------------------------

    /**
     * @param $val
     */
    public function set_flag_force_db($val)
    {
        $this->_flag_force_db = (bool)$val;
    }

    //---------------------------------------------------------
    // get
    //---------------------------------------------------------

    /**
     * @param $item_row
     * @param $item_name
     * @return bool|null
     */
    public function get_file_row_by_item_name($item_row, $item_name)
    {
        $file_id = $this->get_file_id_by_item_name($item_row, $item_name);
        if ($file_id > 0) {
            $file_row = $this->_fileHandler->get_row_by_id($file_id);

            return $file_row;
        }

        return null;
    }

    /**
     * @param $item_row
     * @param $item_name
     * @return int
     */
    public function get_file_id_by_item_name($item_row, $item_name)
    {
        $id = isset($item_row[$item_name]) ? $item_row[$item_name] : 0;

        return $id;
    }

    //---------------------------------------------------------
    // insert
    //---------------------------------------------------------
    // factory.php

    /**
     * @param $item_id
     * @param $params
     * @return array|bool
     */
    public function insert_files_from_params($item_id, $params)
    {
        if (!is_array($params)) {
            return false;
        }

        $arr = [];
        foreach ($this->_FILE_LIST as $file) {
            $arr[$file . '_id'] = $this->insert_file_by_file_params($item_id, $params, $file);
        }

        return $arr;
    }

    /**
     * @param $item_id
     * @param $params
     * @param $name
     * @return bool|int|void
     */
    public function insert_file_by_file_params($item_id, $params, $name)
    {
        if (isset($params[$name]) && is_array($params[$name])) {
            return $this->insert_file_by_param($item_id, $params[$name]);
        }

        return 0;
    }

    /**
     * @param $item_id
     * @param $param
     * @return bool|void
     */
    public function insert_file_by_param($item_id, $param)
    {
        $param['item_id'] = $item_id;

        $row = $this->_fileHandler->create();
        $row = $this->_fileHandler->build_row_by_param($row, $param);

        $newid = $this->_fileHandler->insert($row, $this->_flag_force_db);
        if (!$newid) {
            $this->set_error($this->_fileHandler->get_errors());

            return false;
        }

        return $newid;
    }

    //---------------------------------------------------------
    // update
    //---------------------------------------------------------
    // factory.php

    /**
     * @param $row
     * @param $params
     * @return array|bool
     */
    public function update_files_from_params($row, $params)
    {
        if (!is_array($params)) {
            return false;
        }

        $arr = [];
        foreach ($this->_FILE_LIST as $file) {
            $arr[$file . '_id'] = $this->update_file_by_file_params($row, $params, $file);
        }

        return $arr;
    }

    /**
     * @param $row
     * @param $params
     * @param $name
     * @return bool|int|mixed|void
     */
    public function update_file_by_file_params($row, $params, $name)
    {
        $item_id = $row['item_id'];

        if (!isset($params[$name])) {
            return 0;
        }

        $param = $params[$name];
        if (!is_array($param)) {
            return 0;
        }

        $file_row = $this->get_file_row_by_kind($row, $param['kind']);

        return $this->insert_update_file_by_param($item_id, $file_row, $param);
    }

    /**
     * @param $item_id
     * @param $file_row
     * @param $param
     * @return bool|int|mixed|void
     */
    public function insert_update_file_by_param($item_id, $file_row, $param)
    {
        // update if exists
        if (is_array($file_row)) {
            $file_id = $file_row['file_id'];
            $file_path = $file_row['file_path'];

            // remove current file
            $this->unlink_current_file($file_path, $param['path']);

            $ret = $this->update_file_by_param($file_row, $param);
            if (!$ret) {
                return 0;
            }

            return $file_id;
            // insert if new
        }

        return $this->insert_file_by_param($item_id, $param);
    }

    /**
     * @param $row
     * @param $param
     * @return bool
     */
    public function update_file_by_param($row, $param)
    {
        $param['time_update'] = time();

        $row = $this->_fileHandler->build_row_by_param($row, $param);

        // update
        $ret = $this->_fileHandler->update($row);
        if (!$ret) {
            $this->set_error($this->_fileHandler->get_errors());

            return false;
        }

        return true;
    }

    // action.php

    /**
     * @param $item_row
     * @param $duration
     * @param $item_name
     * @return bool
     */
    public function update_duration($item_row, $duration, $item_name)
    {
        $file_row = $this->get_file_row_by_item_name($item_row, $item_name);
        if (!is_array($file_row)) {
            return true;
        }

        $file_row['file_duration'] = $duration;

        $ret = $this->_fileHandler->update($file_row);
        if (!$ret) {
            $this->set_error($this->_fileHandler->get_errors());

            return false;
        }

        return true;
    }

    //---------------------------------------------------------
    // create or update
    //---------------------------------------------------------
    // video_middle_thumb_create.php

    /**
     * @param $item_row
     * @param $src_file
     * @param $item_name
     * @return bool|int|mixed|void
     */
    public function create_update_file_for_video_thumb($item_row, $src_file, $item_name)
    {
        if (!is_file($src_file)) {
            return 0;  // no action
        }

        $item_id = $item_row['item_id'];
        $item_ext = $item_row['item_ext'];

        $file_row = $this->get_file_row_by_item_name($item_row, $item_name);

        // create param
        $param = $this->create_param($item_id, $src_file, $item_ext, $item_name);

        return $this->insert_update_file_by_param($item_id, $file_row, $param);
    }

    /**
     * @param $item_id
     * @param $src_file
     * @param $icon_name
     * @param $item_name
     * @return array|int|null
     */
    public function create_param($item_id, $src_file, $icon_name, $item_name)
    {
        $param_in = [
            'item_id' => $item_id,
            'src_file' => $src_file,
            'src_ext' => $this->parse_ext($src_file),
            'icon_name' => $icon_name,
        ];

        switch ($item_name) {
            case _C_WEBPHOTO_ITEM_FILE_JPEG:
                $param_out = $this->_jpeg_create_class->create_copy_param($param_in);
                break;
            case _C_WEBPHOTO_ITEM_FILE_THUMB:
                $param_out = $this->_middle_thumb_create_class->create_thumb_param($param_in);
                break;
            case _C_WEBPHOTO_ITEM_FILE_MIDDLE:
                $param_out = $this->_middle_thumb_create_class->create_middle_param($param_in);
                break;
            case _C_WEBPHOTO_ITEM_FILE_SMALL:
                $param_out = $this->_middle_thumb_create_class->create_small_param($param_in);
                break;
            default:
                return 0;
        }

        $param_out['duration'] = 0;

        return $param_out;
    }

    //---------------------------------------------------------
    // delete
    //---------------------------------------------------------
    // action.php

    /**
     * @param $item_row
     * @param $item_name
     * @return int
     */
    public function delete_file($item_row, $item_name)
    {
        $file_row = $this->get_file_row_by_item_name($item_row, $item_name);
        if (!is_array($file_row)) {
            return -1;
        }

        $file_id = $file_row['file_id'];
        $file_path = $file_row['file_path'];

        $this->unlink_path($file_path);

        $ret = $this->_fileHandler->delete_by_id($file_id);
        if (!$ret) {
            $this->set_error($this->_fileHandler->get_format_error());

            return -2;
        }

        return 0;
    }

    //---------------------------------------------------------
    // unlink
    //---------------------------------------------------------

    /**
     * @param $file_path
     * @param $param_path
     */
    public function unlink_current_file($file_path, $param_path)
    {
        if ($file_path && ($file_path != $param_path)) {
            $this->unlink_path($file_path);
        }
    }

    // --- class end ---
}
