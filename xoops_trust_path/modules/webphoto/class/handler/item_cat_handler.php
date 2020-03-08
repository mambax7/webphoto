<?php
// $Id: item_cat_handler.php,v 1.7 2010/03/14 14:30:45 ohwada Exp $

//=========================================================
// webphoto module
// 2008-12-12 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-03-14 K.OHWADA
// set_perm_cat_read()
// 2010-02-15 K.OHWADA
// $_FLAG_PERM_ADMIN
// 2009-11-11 K.OHWADA
// webphoto_lib_handler -> webphoto_handler_base_ini
// build_where_by_image()
// 2009-09-06 K.OHWADA
// add ns ew in build_where_item_gmap_area()
// 2009-04-10 K.OHWADA
// add key in get_rows_item_cat_by_where_orderby()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_item_catHandler
//=========================================================

/**
 * Class webphoto_item_catHandler
 */
class webphoto_item_catHandler extends webphoto_handler_base_ini
{
    public $_item_table;
    public $_cat_table;
    public $_tag_table;
    public $_p2t_table;

    public $_cfg_perm_item_read = false;

    // Undefined property: $_cfg_perm_cat_read
    public $_cfg_perm_cat_read = false;

    public $_FLAG_PERM_ADMIN = false;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_item_catHandler constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_item_table = $this->prefix_dirname('item');
        $this->_cat_table = $this->prefix_dirname('cat');
        $this->_tag_table = $this->prefix_dirname('tag');
        $this->_p2t_table = $this->prefix_dirname('p2t');
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_item_catHandler|\webphoto_lib_error
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
    // set param
    //---------------------------------------------------------

    /**
     * @param $val
     */
    public function set_perm_item_read($val)
    {
        $this->_cfg_perm_item_read = (bool)$val;
    }

    /**
     * @param $val
     */
    public function set_perm_cat_read($val)
    {
        $this->_cfg_perm_cat_read = (bool)$val;
    }

    //---------------------------------------------------------
    // get count
    //---------------------------------------------------------

    /**
     * @param $name
     * @param $param
     * @return int
     */
    public function get_count_item_cat_by_name_param($name, $param)
    {
        $where = $this->build_where_item_cat_by_name_param($name, $param);

        return $this->get_count_item_cat_by_where($where);
    }

    /**
     * @param $name
     * @param $param
     * @return int
     */
    public function get_count_item_by_name_param($name, $param)
    {
        $where = $this->build_where_by_name_param($name, $param);

        return $this->get_count_item_by_where($where);
    }

    //---------------------------------------------------------
    // get rows
    //---------------------------------------------------------

    /**
     * @param      $name
     * @param      $param
     * @param      $orderby
     * @param int  $limit
     * @param int  $offset
     * @param null $key
     * @return array|bool
     */
    public function get_rows_item_cat_by_name_param_orderby($name, $param, $orderby, $limit = 0, $offset = 0, $key = null)
    {
        $where = $this->build_where_item_cat_by_name_param($name, $param);

        return $this->get_rows_item_cat_by_where_orderby($where, $orderby, $limit, $offset, $key);
    }

    /**
     * @param      $name
     * @param      $param
     * @param      $orderby
     * @param int  $limit
     * @param int  $offset
     * @param null $key
     * @return array|bool
     */
    public function get_rows_item_by_name_param_orderby($name, $param, $orderby, $limit = 0, $offset = 0, $key = null)
    {
        $where = $this->build_where_by_name_param($name, $param);

        return $this->get_rows_item_by_where_orderby($where, $orderby, $limit, $offset, $key);
    }

    //---------------------------------------------------------
    // get id array
    //---------------------------------------------------------

    /**
     * @param     $name
     * @param     $param
     * @param     $orderby
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_id_array_item_by_name_param_orderby($name, $param, $orderby, $limit = 0, $offset = 0)
    {
        $where = $this->build_where_by_name_param($name, $param);

        return $this->get_id_array_item_by_where_orderby($where, $orderby, $limit, $offset);
    }

    //---------------------------------------------------------
    // item cat where
    //---------------------------------------------------------

    /**
     * @param $name
     * @param $param
     * @return mixed|string
     */
    public function build_where_item_cat_by_name_param($name, $param)
    {
        $where = $this->convert_item_field($this->build_where_by_name_param($name, $param));

        if ($this->_FLAG_PERM_ADMIN && $this->_is_module_admin) {
            return $where;
        }
        if ($this->_cfg_perm_cat_read > _C_WEBPHOTO_OPT_PERM_READ_ALL) {
            $where .= ' AND ' . $this->build_where_cat_groups();
        }

        return $where;
    }

    /**
     * @param $str
     * @return mixed
     */
    public function convert_item_field($str)
    {
        return str_replace('item_', 'i.item_', $str);
    }

    //---------------------------------------------------------
    // item where
    //---------------------------------------------------------

    /**
     * @param $name
     * @param $param
     * @return null|string
     */
    public function build_where_by_name_param($name, $param)
    {
        $where = null;

        switch ($name) {
            case 'public':
                $where = $this->build_where_public();
                break;
            case 'imode':
                $where = $this->build_where_imode();
                break;
            case 'photo':
                $where = $this->build_where_photo();
                break;
            case 'photo_catid':
                $where = $this->build_where_photo_by_catid($param);
                break;
            case 'catid':
                $where = $this->build_where_by_catid($param);
                break;
            case 'catid_array':
                $where = $this->build_where_by_catid_array($param);
                break;
            case 'datetime':
                $where = $this->build_where_by_datetime($param);
                break;
            case 'like_datetime':
                $where = $this->build_where_by_like_datetime($param);
                break;
            case 'gmap_latest':
                $where = $this->build_where_by_gmap_latest($param);
                break;
            case 'gmap_catid_array':
                $where = $this->build_where_by_gmap_catid_array($param);
                break;
            case 'gmap_area':
                $where = $this->build_where_by_gmap_area($param);
                break;
            case 'place':
                $where = $this->build_where_by_place($param);
                break;
            case 'place_array':
                $where = $this->build_where_by_place_array($param);
                break;
            case 'search':
                $where = $this->build_where_by_search($param);
                break;
            case 'uid':
                $where = $this->build_where_by_uid($param);
                break;
            case 'picture':
                $where = $this->build_where_by_picture($param);
                break;
            case 'picture_catid_array':
                $where = $this->build_where_by_picture_catid_array($param);
                break;
            case 'video':
                $where = $this->build_where_by_video($param);
                break;
            case 'video_catid_array':
                $where = $this->build_where_by_video_catid_array($param);
                break;
            case 'audio':
                $where = $this->build_where_by_audio($param);
                break;
            case 'audio_catid_array':
                $where = $this->build_where_by_audio_catid_array($param);
                break;
            case 'office':
                $where = $this->build_where_by_office($param);
                break;
            case 'office_catid_array':
                $where = $this->build_where_by_office_catid_array($param);
                break;
            default:
                //          xoops_error( "$name $param" );
                break;
        }

        return $where;
    }

    /**
     * @return string
     */
    public function build_where_public()
    {
        $where = ' item_status > 0 ';
        if ($this->_FLAG_PERM_ADMIN && $this->_is_module_admin) {
            return $where;
        }
        if ($this->_cfg_perm_item_read > _C_WEBPHOTO_OPT_PERM_READ_ALL) {
            $where .= ' AND ' . $this->build_where_item_groups();
        }

        return $where;
    }

    /**
     * @return string
     */
    public function build_where_imode()
    {
        $where = $this->build_where_public();
        $where .= ' AND ' . $this->build_where_item_imode();

        return $where;
    }

    /**
     * @return string
     */
    public function build_where_photo()
    {
        $where = $this->build_where_public();
        $where .= ' AND ' . $this->build_where_item_photo();

        return $where;
    }

    /**
     * @param $cat_id
     * @return string
     */
    public function build_where_photo_by_catid($cat_id)
    {
        $where = $this->build_where_public();
        $where .= ' AND ' . $this->build_where_item_photo();
        $where .= ' AND item_cat_id=' . (int)$cat_id;

        return $where;
    }

    /**
     * @param $cat_id
     * @return string
     */
    public function build_where_by_catid($cat_id)
    {
        $where = $this->build_where_public();
        $where .= ' AND item_cat_id=' . (int)$cat_id;

        return $where;
    }

    /**
     * @param $catid_array
     * @return string
     */
    public function build_where_by_catid_array($catid_array)
    {
        $where = $this->build_where_public();
        $where .= ' AND ' . $this->build_where_item_catid_array($catid_array);

        return $where;
    }

    /**
     * @param $datetime
     * @return string
     */
    public function build_where_by_datetime($datetime)
    {
        $where = $this->build_where_public();
        $where .= ' AND item_datetime =' . $this->quote($datetime);

        return $where;
    }

    /**
     * @param $datetime
     * @return string
     */
    public function build_where_by_like_datetime($datetime)
    {
        $where = $this->build_where_public();
        $where .= ' AND item_datetime LIKE ' . $this->quote($datetime . '%');

        return $where;
    }

    /**
     * @param $place
     * @return string
     */
    public function build_where_by_place($place)
    {
        $where = $this->build_where_public();
        $where .= ' AND item_place =' . $this->quote($place);

        return $where;
    }

    /**
     * @param $place_array
     * @return string
     */
    public function build_where_by_place_array($place_array)
    {
        $where = $this->build_where_public();
        $where .= ' AND ' . $this->build_where_item_place_array($place_array);

        return $where;
    }

    /**
     * @param $sql_query
     * @return string
     */
    public function build_where_by_search($sql_query)
    {
        $where = $this->build_where_public();
        $where .= ' AND ' . $sql_query;

        return $where;
    }

    /**
     * @param $uid
     * @return string
     */
    public function build_where_by_uid($uid)
    {
        $where = $this->build_where_public();
        $where .= ' AND item_uid=' . (int)$uid;

        return $where;
    }

    /**
     * @return string
     */
    public function build_where_by_picture()
    {
        $where = $this->build_where_public();
        $where .= ' AND ' . $this->build_where_item_picture();

        return $where;
    }

    /**
     * @param $catid_array
     * @return string
     */
    public function build_where_by_picture_catid_array($catid_array)
    {
        $where = $this->build_where_public();
        $where .= ' AND ' . $this->build_where_item_picture();
        $where .= ' AND ' . $this->build_where_item_catid_array($catid_array);

        return $where;
    }

    /**
     * @return string
     */
    public function build_where_by_video()
    {
        $where = $this->build_where_public();
        $where .= ' AND ' . $this->build_where_item_video();

        return $where;
    }

    /**
     * @param $catid_array
     * @return string
     */
    public function build_where_by_video_catid_array($catid_array)
    {
        $where = $this->build_where_public();
        $where .= ' AND ' . $this->build_where_item_video();
        $where .= ' AND ' . $this->build_where_item_catid_array($catid_array);

        return $where;
    }

    /**
     * @return string
     */
    public function build_where_by_audio()
    {
        $where = $this->build_where_public();
        $where .= ' AND ' . $this->build_where_item_audio();

        return $where;
    }

    /**
     * @param $catid_array
     * @return string
     */
    public function build_where_by_audio_catid_array($catid_array)
    {
        $where = $this->build_where_public();
        $where .= ' AND ' . $this->build_where_item_audio();
        $where .= ' AND ' . $this->build_where_item_catid_array($catid_array);

        return $where;
    }

    /**
     * @return string
     */
    public function build_where_by_office()
    {
        $where = $this->build_where_public();
        $where .= ' AND ' . $this->build_where_item_office();

        return $where;
    }

    /**
     * @param $catid_array
     * @return string
     */
    public function build_where_by_office_catid_array($catid_array)
    {
        $where = $this->build_where_public();
        $where .= ' AND ' . $this->build_where_item_office();
        $where .= ' AND ' . $this->build_where_item_catid_array($catid_array);

        return $where;
    }

    /**
     * @return string
     */
    public function build_where_item_imode()
    {
        $where = " ( item_ext='gif' ";
        $where .= "OR item_ext='jpg' ";
        $where .= "OR item_ext='jpeg' ";
        $where .= "OR item_ext='3gp' ";
        $where .= "OR item_ext='3g2' )";

        return $where;
    }

    /**
     * @return string
     */
    public function build_where_item_photo()
    {
        $where = " ( item_ext='gif' ";
        $where .= "OR item_ext='png' ";
        $where .= "OR item_ext='jpg' ";
        $where .= "OR item_ext='jpeg' ) ";

        return $where;
    }

    /**
     * @return string
     */
    public function build_where_item_picture()
    {
        return $this->build_where_item_kind('item_kind_list_image');
    }

    /**
     * @return string
     */
    public function build_where_item_video()
    {
        return $this->build_where_item_kind('item_kind_list_video');
    }

    /**
     * @return string
     */
    public function build_where_item_audio()
    {
        return $this->build_where_item_kind('item_kind_list_audio');
    }

    /**
     * @return string
     */
    public function build_where_item_office()
    {
        return $this->build_where_item_kind('item_kind_list_office');
    }

    /**
     * @param $list
     * @return string
     */
    public function build_where_item_kind($list)
    {
        return $this->build_where_item_in_array('item_kind', $this->explode_ini($list));
    }

    /**
     * @param $array
     * @return string
     */
    public function build_where_item_catid_array($array)
    {
        return $this->build_where_item_in_array('item_cat_id', $array);
    }

    /**
     * @param $item_name
     * @param $array
     * @return string
     */
    public function build_where_item_in_array($item_name, $array)
    {
        $where = ' ' . $item_name . ' IN ( ';
        foreach ($array as $val) {
            $where .= (int)$val . ', ';
        }

        // 0 means to belong no array
        $where .= ' 0 ) ';

        return $where;
    }

    /**
     * @param $place_array
     * @return null|string
     */
    public function build_where_item_place_array($place_array)
    {
        return $this->build_where_by_keyword_array($place_array, 'AND', 'item_place');
    }

    /**
     * @param $keyword_array
     * @param $cat_id
     * @return null|string
     */
    public function build_where_by_keyword_array_catid($keyword_array, $cat_id)
    {
        $where_key = $this->build_where_by_keyword_array($keyword_array);

        $where_cat = null;
        if ($cat_id > 0) {
            $where_cat = 'item_cat_id=' . (int)$cat_id;
        }

        if ($where_key && $where_cat) {
            $where = $where_key . ' AND ' . $where_cat;

            return $where;
        } elseif ($where_key) {
            return $where_key;
        } elseif ($where_cat) {
            return $where_cat;
        }

        return null;
    }

    /**
     * @param        $keyword_array
     * @param string $andor
     * @param string $name
     * @return null|string
     */
    public function build_where_by_keyword_array($keyword_array, $andor = 'AND', $name = 'item_search')
    {
        if (!is_array($keyword_array) || !count($keyword_array)) {
            return null;
        }

        switch (mb_strtolower($andor)) {
            case 'exact':
                $where = $this->build_where_keyword_single($keyword_array[0], $name);

                return $where;
            case 'or':
                $andor_glue = 'OR';
                break;
            case 'and':
            default:
                $andor_glue = 'AND';
                break;
        }

        $arr = [];

        foreach ($keyword_array as $keyword) {
            $keyword = trim($keyword);
            if ($keyword) {
                $arr[] = $this->build_where_keyword_single($keyword, $name);
            }
        }

        if (is_array($arr) && count($arr)) {
            $glue = ' ' . $andor_glue . ' ';
            $where = ' ( ' . implode($glue, $arr) . ' ) ';

            return $where;
        }

        return null;
    }

    /**
     * @param        $str
     * @param string $name
     * @return string
     */
    public function build_where_keyword_single($str, $name = 'item_search')
    {
        $text = $name . " LIKE '%" . addslashes($str) . "%'";

        return $text;
    }

    /**
     * @param $id_array
     * @return string
     */
    public function build_where_by_itemid_array($id_array)
    {
        $where = '';
        foreach ($id_array as $id) {
            $where .= 'item_id=' . (int)$id . ' OR ';
        }

        // 0 means to belong no category
        $where .= '0';

        return $where;
    }

    /**
     * @return string
     */
    public function build_where_item_groups()
    {
        return $this->build_where_groups('item_perm_read');
    }

    /**
     * @return string
     */
    public function build_where_cat_groups()
    {
        return $this->build_where_groups('c.cat_perm_read');
    }

    /**
     * @param $name
     * @return string
     */
    public function build_where_groups($name)
    {
        $groups = $this->_xoops_groups;

        $pre = '%' . _C_WEBPHOTO_PERM_SEPARATOR;
        $post = _C_WEBPHOTO_PERM_SEPARATOR . '%';

        $where = $name . '=' . $this->quote(_C_WEBPHOTO_PERM_ALLOW_ALL);

        if (is_array($groups) && count($groups)) {
            foreach ($groups as $group) {
                $where .= ' OR ' . $name . ' LIKE ';
                $where .= $this->quote($pre . (int)$group . $post);
            }
        }

        return ' ( ' . $where . ' ) ';
    }

    //---------------------------------------------------------
    // build gmap
    //---------------------------------------------------------

    /**
     * @return string
     */
    public function build_where_by_gmap_latest()
    {
        $where = $this->build_where_public();
        $where .= ' AND ' . $this->build_where_item_gmap();

        return $where;
    }

    /**
     * @param $catid_array
     * @return string
     */
    public function build_where_by_gmap_catid_array($catid_array)
    {
        $where = $this->build_where_public();
        $where .= ' AND ' . $this->build_where_item_catid_array($catid_array);
        $where .= ' AND ' . $this->build_where_item_gmap();

        return $where;
    }

    /**
     * @param $param
     * @return null|string
     */
    public function build_where_by_gmap_area($param)
    {
        if (!is_array($param)) {
            return null;
        }

        list($id, $lat, $lon, $ns, $ew) = $param;

        $where = $this->build_where_public();
        $where .= ' AND ' . $this->build_where_item_gmap();
        $where .= ' AND ' . $this->build_where_item_gmap_area($lat, $lon, $ns, $ew);
        $where .= ' AND item_id <> ' . (int)$id;

        return $where;
    }

    /**
     * @return string
     */
    public function build_where_item_gmap()
    {
        $where = ' ( item_gmap_latitude <> 0 ';
        $where .= 'OR item_gmap_longitude <> 0 ';
        $where .= 'OR item_gmap_zoom <> 0 ) ';

        return $where;
    }

    /**
     * @param $lat
     * @param $lon
     * @param $ns
     * @param $ew
     * @return string
     */
    public function build_where_item_gmap_area($lat, $lon, $ns, $ew)
    {
        $north = $this->adjust_latitude($lat + $ns);
        $south = $this->adjust_latitude($lat - $ns);
        $east = $this->adjust_longitude($lon + $ew);
        $west = $this->adjust_longitude($lon - $ew);

        $where = ' item_gmap_latitude > ' . (float)$south;
        $where .= ' AND item_gmap_latitude  < ' . (float)$north;
        $where .= ' AND item_gmap_longitude > ' . (float)$west;
        $where .= ' AND item_gmap_longitude < ' . (float)$east;

        return $where;
    }

    /**
     * @param $lat
     * @return int
     */
    public function adjust_latitude($lat)
    {
        // north pole
        if ($lat > 90) {
            $lat = 90;

        // south pole
        } elseif ($lat < -90) {
            $lat = -90;
        }

        return $lat;
    }

    /**
     * @param $lon
     * @return int
     */
    public function adjust_longitude($lon)
    {
        // international date line
        if ($lon > 180) {
            $lon = -360 + $lon;
        } elseif ($lon < -180) {
            $lon = 360 + $lon;
        }

        return $lon;
    }

    //---------------------------------------------------------
    // sql
    //---------------------------------------------------------

    /**
     * @param $where
     * @return int
     */
    public function get_count_item_cat_by_where($where)
    {
        $sql = 'SELECT COUNT(*) FROM ';
        $sql .= $this->_item_table . ' i ';
        $sql .= ' INNER JOIN ' . $this->_cat_table . ' c ';
        $sql .= ' ON i.item_cat_id = c.cat_id ';
        $sql .= ' WHERE ' . $where;

        return $this->get_count_by_sql($sql);
    }

    /**
     * @param $where
     * @return int
     */
    public function get_count_item_by_where($where)
    {
        $sql = 'SELECT COUNT(*) FROM ';
        $sql .= $this->_item_table;
        $sql .= ' WHERE ' . $where;

        return $this->get_count_by_sql($sql);
    }

    /**
     * @param      $where
     * @param      $orderby
     * @param int  $limit
     * @param int  $offset
     * @param null $key
     * @return array|bool
     */
    public function get_rows_item_cat_by_where_orderby($where, $orderby, $limit = 0, $offset = 0, $key = null)
    {
        $sql = 'SELECT i.* FROM ';
        $sql .= $this->_item_table . ' i ';
        $sql .= ' INNER JOIN ' . $this->_cat_table . ' c ';
        $sql .= ' ON i.item_cat_id = c.cat_id ';
        $sql .= ' WHERE ' . $where;
        $sql .= ' ORDER BY ' . $orderby;

        return $this->get_rows_by_sql($sql, $limit, $offset, $key);
    }

    /**
     * @param      $where
     * @param      $orderby
     * @param int  $limit
     * @param int  $offset
     * @param null $key
     * @return array|bool
     */
    public function get_rows_item_by_where_orderby($where, $orderby, $limit = 0, $offset = 0, $key = null)
    {
        $sql = 'SELECT * FROM ';
        $sql .= $this->_item_table;
        $sql .= ' WHERE ' . $where;
        $sql .= ' ORDER BY ' . $orderby;

        return $this->get_rows_by_sql($sql, $limit, $offset, $key);
    }

    /**
     * @param     $where
     * @param     $orderby
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_id_array_item_by_where_orderby($where, $orderby, $limit = 0, $offset = 0)
    {
        $sql = 'SELECT item_id';
        $sql .= ' FROM ' . $this->_item_table;
        $sql .= ' WHERE ' . $where;
        $sql .= ' ORDER BY ' . $orderby;

        return $this->get_first_rows_by_sql($sql, $limit, $offset);
    }

    // --- class end ---
}
