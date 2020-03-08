<?php
// $Id: file_handler.php,v 1.11 2011/11/12 11:05:02 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-24 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-11-11 K.OHWADA
// change build_show_file_image()
// 2011-05-01 K.OHWADA
// get_download_image_aux()
// 2010-11-11 K.OHWADA
// build_full_path()
// 2010-09-20 K.OHWADA
// function get_file_full_by_key()
// 2009-11-11 K.OHWADA
// webphoto_lib_handler -> webphoto_handler_base_ini
// 2009-03-15 K.OHWADA
// get_count_by_kind()
// 2009-01-10 K.OHWADA
// build_row_by_param( $row, $param )
// 2008-12-07 K.OHWADA
// not need '/' in build_show_file_image()
// 2008-11-29 K.OHWADA
// build_show_file_image()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_file_handler
//=========================================================

/**
 * Class webphoto_file_handler
 */
class webphoto_file_handler extends webphoto_handler_base_ini
{
    public $_cached_extend = [];

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_file_handler constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);
        $this->set_table_prefix_dirname('file');
        $this->set_id_name('file_id');
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_file_handler|\webphoto_lib_error
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
            'file_id' => 0,
            'file_time_create' => $time_create,
            'file_time_update' => $time_update,
            'file_item_id' => 0,
            'file_kind' => 0,
            'file_url' => '',
            'file_path' => '',
            'file_name' => '',
            'file_ext' => '',
            'file_mime' => '',
            'file_medium' => '',
            'file_size' => 0,
            'file_width' => 0,
            'file_height' => 0,
            'file_duration' => 0,
        ];

        return $arr;
    }

    //---------------------------------------------------------
    // insert
    //---------------------------------------------------------

    /**
     * @param      $row
     * @param bool $force
     * @return bool|void
     */
    public function insert($row, $force = false)
    {
        extract($row);

        $sql = 'INSERT INTO ' . $this->_table . ' (';

        if ($file_id > 0) {
            $sql .= 'file_id, ';
        }

        $sql .= 'file_time_create, ';
        $sql .= 'file_time_update, ';
        $sql .= 'file_item_id, ';
        $sql .= 'file_kind, ';
        $sql .= 'file_url, ';
        $sql .= 'file_path, ';
        $sql .= 'file_name, ';
        $sql .= 'file_ext, ';
        $sql .= 'file_mime, ';
        $sql .= 'file_medium, ';
        $sql .= 'file_size, ';
        $sql .= 'file_width, ';
        $sql .= 'file_height, ';
        $sql .= 'file_duration ';

        $sql .= ') VALUES ( ';

        if ($file_id > 0) {
            $sql .= (int)$file_id . ', ';
        }

        $sql .= (int)$file_time_create . ', ';
        $sql .= (int)$file_time_update . ', ';
        $sql .= (int)$file_item_id . ', ';
        $sql .= (int)$file_kind . ', ';
        $sql .= $this->quote($file_url) . ', ';
        $sql .= $this->quote($file_path) . ', ';
        $sql .= $this->quote($file_name) . ', ';
        $sql .= $this->quote($file_ext) . ', ';
        $sql .= $this->quote($file_mime) . ', ';
        $sql .= $this->quote($file_medium) . ', ';
        $sql .= (int)$file_size . ', ';
        $sql .= (int)$file_width . ', ';
        $sql .= (int)$file_height . ', ';
        $sql .= (int)$file_duration . ' ';

        $sql .= ')';

        $ret = $this->query($sql, 0, 0, $force);
        if (!$ret) {
            return false;
        }

        return $this->_db->getInsertId();
    }

    //---------------------------------------------------------
    // update
    //---------------------------------------------------------

    /**
     * @param      $row
     * @param bool $force
     * @return mixed
     */
    public function update($row, $force = false)
    {
        extract($row);

        $sql = 'UPDATE ' . $this->_table . ' SET ';
        $sql .= 'file_time_create=' . (int)$file_time_create . ', ';
        $sql .= 'file_time_update=' . (int)$file_time_update . ', ';
        $sql .= 'file_item_id=' . (int)$file_item_id . ', ';
        $sql .= 'file_kind=' . (int)$file_kind . ', ';
        $sql .= 'file_url=' . $this->quote($file_url) . ', ';
        $sql .= 'file_path=' . $this->quote($file_path) . ', ';
        $sql .= 'file_name=' . $this->quote($file_name) . ', ';
        $sql .= 'file_ext=' . $this->quote($file_ext) . ', ';
        $sql .= 'file_mime=' . $this->quote($file_mime) . ', ';
        $sql .= 'file_medium=' . $this->quote($file_medium) . ', ';
        $sql .= 'file_size=' . (int)$file_size . ', ';
        $sql .= 'file_width=' . (int)$file_width . ', ';
        $sql .= 'file_height=' . (int)$file_height . ', ';
        $sql .= 'file_duration=' . (int)$file_duration . ' ';
        $sql .= 'WHERE file_id=' . (int)$file_id;

        return $this->query($sql, 0, 0, $force);
    }

    //---------------------------------------------------------
    // delete
    //---------------------------------------------------------

    /**
     * @param $item_id
     * @return mixed
     */
    public function delete_by_itemid($item_id)
    {
        $sql = 'DELETE FROM ' . $this->_table;
        $sql .= ' WHERE file_item_id=' . (int)$item_id;

        return $this->query($sql);
    }

    //---------------------------------------------------------
    // get
    //---------------------------------------------------------

    /**
     * @param $kind
     * @return int
     */
    public function get_count_by_kind($kind)
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->_table;
        $sql .= ' WHERE file_kind=' . (int)$kind;

        return $this->get_count_by_sql($sql);
    }

    /**
     * @param     $kind
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_rows_by_kind($kind, $limit = 0, $offset = 0)
    {
        $sql = 'SELECT * FROM ' . $this->_table;
        $sql .= ' WHERE file_kind=' . (int)$kind;
        $sql .= ' ORDER BY file_id ASC';

        return $this->get_rows_by_sql($sql, $limit, $offset);
    }

    /**
     * @param $id
     * @return array|bool|null
     */
    public function get_extend_row_by_id($id)
    {
        $row = $this->get_row_by_id($id);
        if (is_array($row)) {
            $full_path = $this->build_full_path_by_row($row);
            $full_url = $this->build_full_url_by_row($row);
            $row['full_path'] = $full_path;
            $row['full_url'] = $full_url;
            $row['full_path_exists'] = $this->exists_file($full_path);

            return $row;
        }

        return null;
    }

    /**
     * @param $id
     * @return array|bool|mixed|null
     */
    public function get_cached_extend_row_by_id($id)
    {
        if (isset($this->_cached_extend[$id])) {
            return $this->_cached_extend[$id];
        }

        $row = $this->get_extend_row_by_id($id);
        if (is_array($row)) {
            $this->_cached_extend[$id] = $row;

            return $row;
        }

        return null;
    }

    //---------------------------------------------------------
    // build
    //---------------------------------------------------------

    /**
     * @param $row
     * @param $param
     * @return mixed
     */
    public function build_row_by_param($row, $param)
    {
        $item_id = isset($param['item_id']) ? (int)$param['item_id'] : 0;
        $width = isset($param['width']) ? (int)$param['width'] : 0;
        $height = isset($param['height']) ? (int)$param['height'] : 0;
        $duration = isset($param['duration']) ? (int)$param['duration'] : 0;
        $time_update = isset($param['time_update']) ? (int)$param['time_update'] : 0;

        $row['file_url'] = $param['url'];
        $row['file_path'] = $param['path'];
        $row['file_name'] = $param['name'];
        $row['file_ext'] = $param['ext'];
        $row['file_mime'] = $param['mime'];
        $row['file_medium'] = $param['medium'];
        $row['file_size'] = $param['size'];
        $row['file_kind'] = $param['kind'];
        $row['file_width'] = $width;
        $row['file_height'] = $height;
        $row['file_duration'] = $duration;

        if ($item_id > 0) {
            $row['file_item_id'] = $item_id;
        }

        if ($time_update > 0) {
            $row['file_time_update'] = $time_update;
        }

        return $row;
    }

    //---------------------------------------------------------
    // show
    //---------------------------------------------------------

    /**
     * @param      $file_row
     * @param bool $flag_exists
     * @return array
     */
    public function build_show_file_image($file_row, $flag_exists = false)
    {
        $url = null;
        $width = 0;
        $height = 0;

        if (is_array($file_row)) {
            $file_url = $file_row['file_url'];
            $width = $file_row['file_width'];
            $height = $file_row['file_height'];

            $full_url = $this->build_full_url_by_row($file_row);
            $full_path = $this->build_full_path_by_row($file_row);
            $exists = $this->exists_file($full_path);

            if ($flag_exists && $exists && $full_url) {
                $url = $full_url;
            } elseif ($file_url) {
                $url = $file_url;
            }
        }

        return [$url, $width, $height];
    }

    /**
     * @param $id
     * @return mixed|null
     */
    public function get_full_path_by_id($id)
    {
        $row = $this->get_extend_row_by_id($id);

        return $this->get_full_path_by_row($row);
    }

    /**
     * @param $id
     * @return bool
     */
    public function exists_full_path_by_id($id)
    {
        $row = $this->get_extend_row_by_id($id);

        return $this->exists_full_path_by_row($row);
    }

    /**
     * @param $row
     * @return bool
     */
    public function exists_full_path_by_row($row)
    {
        $file = $this->get_full_path_by_row($row);

        return $this->exists_file($file);
    }

    /**
     * @param $row
     * @return mixed|null
     */
    public function get_full_path_by_row($row)
    {
        if (is_array($row) && $row['full_path']) {
            return $row['full_path'];
        }

        return null;
    }

    /**
     * @param $row
     * @return null|string
     */
    public function build_full_path_by_row($row)
    {
        if (is_array($row) && $row['file_path']) {
            return $this->build_full_path($row['file_path']);
        }

        return null;
    }

    /**
     * @param $row
     * @return null|string
     */
    public function build_full_url_by_row($row)
    {
        if (is_array($row) && $row['file_path']) {
            return $this->build_full_url($row['file_path']);
        }

        return null;
    }

    /**
     * @param $path
     * @return null|string
     */
    public function build_full_path($path)
    {
        if ($path) {
            $str = XOOPS_ROOT_PATH . $path;

            return $str;
        }

        return null;
    }

    /**
     * @param $path
     * @return null|string
     */
    public function build_full_url($path)
    {
        if ($path) {
            $str = XOOPS_URL . $path;

            return $str;
        }

        return null;
    }

    /**
     * @param $file
     * @return bool
     */
    public function exists_file($file)
    {
        if ($file && file_exists($file) && is_file($file) && !is_dir($file)) {
            return true;
        }

        return false;
    }

    //---------------------------------------------------------
    // options
    //---------------------------------------------------------

    /**
     * @return array
     */
    public function get_file_kind_all_options()
    {
        $arr = [
            _C_WEBPHOTO_FILE_KIND_CONT => 'cont',
            _C_WEBPHOTO_FILE_KIND_THUMB => 'thumb',
            _C_WEBPHOTO_FILE_KIND_MIDDLE => 'middile',
            _C_WEBPHOTO_FILE_KIND_FLASH => 'flash',
            _C_WEBPHOTO_FILE_KIND_DOCOMO => 'docomo',
            _C_WEBPHOTO_FILE_KIND_PDF => 'pdf',
            _C_WEBPHOTO_FILE_KIND_MIDDLE => 'middile',
            _C_WEBPHOTO_FILE_KIND_SWF => 'swf',
            _C_WEBPHOTO_FILE_KIND_JPEG => 'jpeg',
            _C_WEBPHOTO_FILE_KIND_MP3 => 'mp3',
            _C_WEBPHOTO_FILE_KIND_WAV => 'wav',
            _C_WEBPHOTO_FILE_KIND_LARGE => 'large',
        ];

        return $arr;
    }

    /**
     * @return array
     */
    public function get_file_kind_image_options()
    {
        $arr = [
            //      _C_WEBPHOTO_FILE_KIND_CONT   => 'cont',
            _C_WEBPHOTO_FILE_KIND_THUMB => 'thumb',
            _C_WEBPHOTO_FILE_KIND_MIDDLE => 'middile',
            //      _C_WEBPHOTO_FILE_KIND_FLASH  => 'flash',
            //      _C_WEBPHOTO_FILE_KIND_DOCOMO => 'docomo',
            //      _C_WEBPHOTO_FILE_KIND_PDF    => 'pdf',
            _C_WEBPHOTO_FILE_KIND_MIDDLE => 'middile',
            //      _C_WEBPHOTO_FILE_KIND_SWF    => 'swf',
            _C_WEBPHOTO_FILE_KIND_JPEG => 'jpeg',
            //      _C_WEBPHOTO_FILE_KIND_MP3    => 'mp3',
            //      _C_WEBPHOTO_FILE_KIND_WAV    => 'wav',
            _C_WEBPHOTO_FILE_KIND_LARGE => 'large',
        ];

        return $arr;
    }

    //---------------------------------------------------------
    // options
    //---------------------------------------------------------

    /**
     * @param $file_kind
     * @return bool|mixed
     */
    public function get_download_image_aux($file_kind)
    {
        $options = $this->get_file_kind_image_options();
        if (isset($options[$file_kind])) {
            return $options[$file_kind];
        }

        return false;
    }

    // --- class end ---
}
