<?php
// $Id: public.php,v 1.7 2011/06/05 07:23:40 ohwada Exp $

//=========================================================
// webphoto module
// 2008-12-12 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-06-04 K.OHWADA
// remove cfg_use_pathinfo
// 2010-06-06 K.OHWADA
// build_img_url()
// 2009-11-11 K.OHWADA
// webphoto_inc_handler -> webphoto_inc_base_ini
// $trust_dirname
// 2009-04-10 K.OHWADA
// change build_item_description()
// 2009-03-19 K.OHWADA
// FLAG_IMAGEMANAGER_IMAGE_ONLY
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_inc_public
//=========================================================

/**
 * Class webphoto_inc_public
 */
class webphoto_inc_public extends webphoto_inc_base_ini
{
    public $_cfg_workdir = null;
    public $_cfg_perm_cat_read = false;
    public $_cfg_perm_item_read = false;

    public $_cat_cached = [];

    public $_ITEM_ORDERBY = 'item_time_update DESC, item_id DESC';
    public $_FLAG_IMAGEMANAGER_IMAGE_ONLY = false;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $dirname
     * @param $trust_dirname
     */
    public function init_public($dirname, $trust_dirname)
    {
        $this->init_base_ini($dirname, $trust_dirname);
        $this->initHandler($dirname);

        $this->_init_xoops_config($dirname);
    }

    //---------------------------------------------------------
    // item rows
    //---------------------------------------------------------

    /**
     * @param      $options
     * @param      $orderby
     * @param int  $limit
     * @param int  $offset
     * @param bool $key
     * @return array|bool
     */
    public function get_item_rows_for_block($options, $orderby, $limit = 0, $offset = 0, $key = false)
    {
        return $this->get_item_rows_by_name_param_orderby('block', $options, $orderby, $limit, $offset, $key);
    }

    /**
     * @param     $cat_id
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_item_rows_for_imagemanager($cat_id, $limit = 0, $offset = 0)
    {
        return $this->get_item_rows_by_name_param_orderby('imagemanager_catid', $cat_id, $this->_ITEM_ORDERBY, $limit, $offset);
    }

    /**
     * @param     $query_array
     * @param     $andor
     * @param     $uid
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_item_rows_for_search($query_array, $andor, $uid, $limit = 0, $offset = 0)
    {
        return $this->get_item_rows_by_name_param_orderby('search', [$query_array, $andor, $uid], $this->_ITEM_ORDERBY, $limit, $offset);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_item_rows_for_whatsnew($limit = 0, $offset = 0)
    {
        return $this->get_item_rows_by_name_param_orderby('whatsnew', null, $this->_ITEM_ORDERBY, $limit, $offset);
    }

    /**
     * @param      $name
     * @param      $param
     * @param      $orderby
     * @param int  $limit
     * @param int  $offset
     * @param bool $key
     * @return array|bool
     */
    public function get_item_rows_by_name_param_orderby($name, $param, $orderby, $limit = 0, $offset = 0, $key = false)
    {
        $item_key = null;
        if ($key) {
            $item_key = 'item_id';
        }

        if (_C_WEBPHOTO_OPT_PERM_READ_ALL == $this->_cfg_perm_cat_read) {
            return $this->get_item_rows_item_by_name_param_orderby($name, $param, $orderby, $limit, $offset, $item_key);
        }

        return $this->get_item_rows_item_cat_by_name_param_orderby($name, $param, $this->convert_item_field($orderby), $limit, $offset, $this->convert_item_field($item_key));
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
    public function get_item_rows_item_cat_by_name_param_orderby($name, $param, $orderby, $limit = 0, $offset = 0, $key = null)
    {
        $where = $this->build_where_item_cat_by_name_param($name, $param);

        return $this->get_item_rows_by_where_orderby_with_cat($where, $orderby, $limit, $offset, $key);
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
    public function get_item_rows_item_by_name_param_orderby($name, $param, $orderby, $limit = 0, $offset = 0, $key = null)
    {
        $where = $this->build_where_by_name_param($name, $param);

        return $this->get_item_rows_by_where_orderby($where, $orderby, $limit, $offset, $key);
    }

    //---------------------------------------------------------
    // item count
    //---------------------------------------------------------

    /**
     * @param $cat_id
     * @return int
     */
    public function get_item_count_for_imagemanager($cat_id)
    {
        return $this->get_item_count_by_name_param('imagemanager_catid', $cat_id);
    }

    /**
     * @param $name
     * @param $param
     * @return int
     */
    public function get_item_count_by_name_param($name, $param)
    {
        if (_C_WEBPHOTO_OPT_PERM_READ_ALL == $this->_cfg_perm_cat_read) {
            return $this->get_item_count_item_by_name_param($name, $param);
        }

        return $this->get_item_count_item_cat_by_name_param($name, $param);
    }

    /**
     * @param $name
     * @param $param
     * @return int
     */
    public function get_item_count_item_cat_by_name_param($name, $param)
    {
        $where = $this->build_where_item_cat_by_name_param($name, $param);

        return $this->get_item_count_by_where_with_cat($where);
    }

    /**
     * @param $name
     * @param $param
     * @return int
     */
    public function get_item_count_item_by_name_param($name, $param)
    {
        $where = $this->build_where_by_name_param($name, $param);

        return $this->get_item_count_by_where($where);
    }

    //---------------------------------------------------------
    // imagemanager
    //---------------------------------------------------------

    /**
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_item_catlist_for_imagemanager($limit = 0, $offset = 0)
    {
        if (_C_WEBPHOTO_OPT_PERM_READ_ALL == $this->_cfg_perm_cat_read) {
            return $this->get_item_catlist_for_imagemanager_with_item($limit, $offset);
        }

        return $this->get_item_catlist_for_imagemanager_with_item_cat($limit, $offset);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_item_catlist_for_imagemanager_with_item_cat($limit = 0, $offset = 0)
    {
        $where = $this->build_where_item_cat_by_name_param('imagemanager_catlist', null);

        $sql = 'SELECT i.item_cat_id, COUNT(i.item_id) AS photo_sum ';
        $sql .= ' FROM ';
        $sql .= $this->prefix_dirname('item') . ' i ';
        $sql .= ' INNER JOIN ';
        $sql .= $this->prefix_dirname('cat') . ' c ';
        $sql .= ' ON i.item_cat_id = c.cat_id ';
        $sql .= ' WHERE ' . $where;
        $sql .= ' GROUP BY i.item_cat_id';
        $sql .= ' ORDER BY i.item_cat_id';

        return $this->get_rows_by_sql($sql, $limit, $offset);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_item_catlist_for_imagemanager_with_item($limit = 0, $offset = 0)
    {
        $where = $this->build_where_by_name_param('imagemanager_catlist', null);

        $sql = 'SELECT item_cat_id, COUNT(item_id) AS photo_sum ';
        $sql .= ' FROM ';
        $sql .= $this->prefix_dirname('item');
        $sql .= ' WHERE ' . $where;
        $sql .= ' GROUP BY item_cat_id';
        $sql .= ' ORDER BY item_cat_id';

        return $this->get_rows_by_sql($sql, $limit, $offset);
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
        $where .= ' AND ' . $this->build_where_cat_perm_read();

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
            case 'block':
                $where = $this->build_where_for_block($param);
                break;
            case 'imagemanager_catlist':
                $where = $this->build_where_for_imagemanager_catlist();
                break;
            case 'imagemanager_catid':
                $where = $this->build_where_for_imagemanager_catid($param);
                break;
            case 'search':
                $where = $this->build_where_for_search($param);
                break;
            case 'whatsnew':
                $where = $this->build_where_for_whatsnew();
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
    public function build_where_for_whatsnew()
    {
        return $this->build_where_public_with_item();
    }

    /**
     * @param $options
     * @return string
     */
    public function build_where_for_block($options)
    {
        // defined in block.php
        $where_limitation = $this->build_where_block_cat_limitation($options);

        $where = $this->build_where_public_with_item();
        if ($where_limitation) {
            $where .= ' AND ' . $where_limitation;
        }

        return $where;
    }

    /**
     * @return string
     */
    public function build_where_for_imagemanager_catlist()
    {
        return $this->build_where_for_imagemanager_image();
    }

    /**
     * @param $cat_id
     * @return string
     */
    public function build_where_for_imagemanager_catid($cat_id)
    {
        $where = $this->build_where_for_imagemanager_image();
        $where .= ' AND item_cat_id=' . (int)$cat_id;

        return $where;
    }

    /**
     * @return string
     */
    public function build_where_for_imagemanager_image()
    {
        $where = $this->build_where_public_with_item();
        $where .= ' AND item_file_id_1 > 0';
        $where .= ' AND item_file_id_2 > 0';
        if ($this->_FLAG_IMAGEMANAGER_IMAGE_ONLY) {
            $where .= ' AND item_kind=' . _C_WEBPHOTO_ITEM_KIND_IMAGE;
        }

        return $where;
    }

    //---------------------------------------------------------
    // item where search
    //---------------------------------------------------------

    /**
     * @param $param
     * @return null|string
     */
    public function build_where_for_search($param)
    {
        if (!is_array($param)) {
            return null;
        }

        list($query_array, $andor, $uid) = $param;

        $where_search = $this->build_where_for_search_query($query_array, $andor, $uid);

        $where = $this->build_where_public_with_item();
        if ($where_search) {
            $where .= ' AND ' . $where_search;
        }

        return $where;
    }

    /**
     * @param $keyword_array
     * @param $andor
     * @param $uid
     * @return null|string
     */
    public function build_where_for_search_query($keyword_array, $andor, $uid)
    {
        $where_key = $this->build_where_by_keyword_array($keyword_array, $andor);

        $where_uid = null;
        if (0 != $uid) {
            $where_uid = 'item_uid=' . (int)$uid;
        }

        $where = null;
        if ($where_key && $where_uid) {
            $where = $where_key . ' AND ' . $where_uid;
        } elseif ($where_key) {
            $where = $where_key;
        } elseif ($where_uid) {
            $where = $where_uid;
        }

        return $where;
    }

    /**
     * @param        $keyword_array
     * @param string $andor
     * @return null|string
     */
    public function build_where_by_keyword_array($keyword_array, $andor = 'AND')
    {
        if (!is_array($keyword_array) || !count($keyword_array)) {
            return null;
        }

        switch (mb_strtolower($andor)) {
            case 'exact':
                $where = $this->_build_where_search_single($keyword_array[0]);

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
                $arr[] = $this->build_where_search_single($keyword);
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
     * @param $str
     * @return string
     */
    public function build_where_search_single($str)
    {
        $text = "item_search LIKE '%" . addslashes($str) . "%'";

        return $text;
    }

    //---------------------------------------------------------
    // item filed
    //---------------------------------------------------------

    /**
     * @param $row
     * @return mixed
     */
    public function build_item_description($row)
    {
        $editor = $row['item_editor'];
        $text = $row['item_description'];

        // new version (v0.10)
        $html = $row['item_description_html'];
        $smiley = $row['item_description_smiley'];
        $xcode = $row['item_description_xcode'];
        $image = $row['item_description_image'];
        $br = $row['item_description_br'];

        // prev version (v0.90)
        if (empty($editor)) {
            $html = 0;
            $smiley = 1;
            $xcode = 1;
            $image = 1;
            $br = 1;
        }

        $myts = MyTextSanitizer::getInstance();

        return $myts->displayTarea($text, $html, $smiley, $xcode, $image, $br);
    }

    //---------------------------------------------------------
    // cat handler
    //---------------------------------------------------------

    /**
     * @param $id
     * @return bool|mixed|null
     */
    public function get_cat_cached_row_by_id($id)
    {
        if (isset($this->_cat_cached[$id])) {
            return $this->_cat_cached[$id];
        }

        $row = $this->get_cat_row_by_id($id);
        if (is_array($row)) {
            $this->_cat_cached[$id] = $row;

            return $row;
        }

        return null;
    }

    //---------------------------------------------------------
    // build_img_url
    //---------------------------------------------------------

    /**
     * @param      $item_row
     * @param bool $show_image
     * @param bool $show_icon
     * @return array
     */
    public function build_img_url($item_row, $show_image = false, $show_icon = false)
    {
        $img_url = null;
        $img_width = 0;
        $img_height = 0;

        $item_kind = $item_row['item_kind'];
        $item_external_thumb = $item_row['item_external_thumb'];

        $is_image = $this->is_image_kind($item_kind);
        $is_external = $this->is_kind_external_thumb($item_kind);

        $thumb_row = $this->get_file_row_by_kind($item_row, _C_WEBPHOTO_FILE_KIND_THUMB);

        list($thumb_url, $thumb_width, $thumb_height) = $this->build_show_file_image($thumb_row);

        list($icon_url, $icon_width, $icon_height) = $this->build_show_icon_image($item_row);

        if (($is_image || $show_image) && $thumb_url) {
            $img_url = $thumb_url;
            $img_width = $thumb_width;
            $img_height = $thumb_height;
        } elseif (($is_external || $show_image) && $item_external_thumb) {
            $img_url = $item_external_thumb;
        } elseif ($show_icon && $icon_url) {
            $img_url = $icon_url;
            $img_width = $icon_width;
            $img_height = $icon_height;
        }

        return [$img_url, $img_width, $img_height];
    }

    /**
     * @param $kind
     * @return bool
     */
    public function is_kind_external_thumb($kind)
    {
        if ($this->is_embed_kind($kind)
            || $this->is_external_image_kind($kind)) {
            return true;
        }

        return false;
    }

    //---------------------------------------------------------
    // auto publish
    //---------------------------------------------------------
    public function auto_publish()
    {
        $publish_class = webphoto_inc_auto_publish::getSingleton($this->_DIRNAME, $this->_TRUST_DIRNAME);
        $publish_class->set_workdir($this->_cfg_workdir);
        $publish_class->auto_publish();
    }

    //---------------------------------------------------------
    // xoops config
    //---------------------------------------------------------

    /**
     * @param $dirname
     */
    public function _init_xoops_config($dirname)
    {
        $configHandler = webphoto_inc_config::getSingleton($dirname);

        $this->_cfg_workdir = $configHandler->get_by_name('workdir');
        $this->_cfg_perm_cat_read = $configHandler->get_by_name('perm_cat_read');
        $this->_cfg_perm_item_read = $configHandler->get_by_name('perm_item_read');
    }

    // --- class end ---
}
