<?php
// $Id: tagcloud.php,v 1.6 2011/11/04 04:01:48 ohwada Exp $

//=========================================================
// webphoto module
// 2008-12-12 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-11-03 K.OHWADA
// Assigning the return value of new by reference is deprecated
// 2011-06-04 K.OHWADA
// remove cfg_use_pathinfo
// 2009-11-11 K.OHWADA
// webphoto_inc_handler -> webphoto_inc_base_ini
// 2009-01-25 K.OHWADA
// _init_config( $dirname )
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_inc_tagcloud
//=========================================================

/**
 * Class webphoto_inc_tagcloud
 */
class webphoto_inc_tagcloud extends webphoto_inc_base_ini
{
    public $_uri_class;

    public $_item_table;
    public $_cat_table;
    public $_tag_table;
    public $_p2t_table;

    public $_cfg_perm_cat_read = 0;
    public $_cfg_perm_item_read = 0;

    public $_PERM_ALLOW_ALL = _C_WEBPHOTO_PERM_ALLOW_ALL;
    public $_PERM_DENOY_ALL = _C_WEBPHOTO_PERM_DENOY_ALL;
    public $_PERM_SEPARATOR = _C_WEBPHOTO_PERM_SEPARATOR;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_inc_tagcloud constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct();
        $this->init_base_ini($dirname, $trust_dirname);
        $this->initHandler($dirname);

        $this->_init_config($dirname);

        $this->_uri_class = webphoto_inc_uri::getSingleton($dirname);

        $this->_item_table = $this->prefix_dirname('item');
        $this->_cat_table = $this->prefix_dirname('cat');
        $this->_tag_table = $this->prefix_dirname('tag');
        $this->_p2t_table = $this->prefix_dirname('p2t');
    }

    /**
     * @param $dirname
     * @param $trust_dirname
     * @return mixed
     */
    public static function getSingleton($dirname, $trust_dirname)
    {
        static $singletons;
        if (!isset($singletons[$dirname])) {
            $singletons[$dirname] = new self($dirname, $trust_dirname);
        }

        return $singletons[$dirname];
    }

    //---------------------------------------------------------
    // tagcloud
    //---------------------------------------------------------

    /**
     * @param $limit
     * @return array
     */
    public function build_tagcloud($limit)
    {
        $rows = $this->get_tag_rows($limit);
        if (!is_array($rows) || !count($rows)) {
            return [];
        }

        return $this->build_tagcloud_by_rows($rows);
    }

    /**
     * @param $rows
     * @return array
     */
    public function build_tagcloud_by_rows($rows)
    {
        // Assigning the return value of new by reference is deprecated
        $cloud_class = new webphoto_lib_cloud();

        ksort($rows);

        foreach (array_keys($rows) as $i) {
            $name = $rows[$i]['tag_name'];
            $count = $rows[$i]['photo_count'];
            $link = $this->_uri_class->build_tag($name);
            $cloud_class->addElement($name, $link, $count);
        }

        return $cloud_class->build();
    }

    //---------------------------------------------------------
    // get tag rows
    //---------------------------------------------------------

    /**
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_tag_rows($limit = 0, $offset = 0)
    {
        if ((_C_WEBPHOTO_OPT_PERM_READ_ALL == $this->_cfg_perm_cat_read)
            && (_C_WEBPHOTO_OPT_PERM_READ_ALL == $this->_cfg_perm_item_read)) {
            return $this->_get_tag_rows_with_count('tag_id', $limit, $offset);
        }

        return $this->_get_tag_rows_with_count_cat('tag_id', $limit, $offset);
    }

    //---------------------------------------------------------
    // get item count
    //---------------------------------------------------------

    /**
     * @param $tag
     * @return int
     */
    public function get_item_count_by_tag($tag)
    {
        if (_C_WEBPHOTO_OPT_PERM_READ_ALL == $this->_cfg_perm_cat_read) {
            return $this->_get_item_count_by_tag($tag);
        }

        return $this->_get_item_count_by_tag_for_cat($tag);
    }

    //---------------------------------------------------------
    // get item rows
    //---------------------------------------------------------

    /**
     * @param     $tag
     * @param     $orderby
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_item_id_array_by_tag($tag, $orderby, $limit = 0, $offset = 0)
    {
        $orderby = $this->convert_item_field($orderby);

        if (_C_WEBPHOTO_OPT_PERM_READ_ALL == $this->_cfg_perm_cat_read) {
            return $this->_get_item_id_array_by_tag($tag, $orderby, $limit, $offset);
        }

        return $this->_get_item_id_array_by_tag_for_cat($tag, $orderby, $limit, $offset);
    }

    //---------------------------------------------------------
    // where
    //---------------------------------------------------------

    /**
     * @param $tag
     * @return mixed|string
     */
    public function _build_where_by_tag_for_cat($tag)
    {
        $where = $this->_build_where_by_tag($tag);
        $where .= ' AND ';
        $where .= $this->build_where_cat_perm_read();

        return $where;
    }

    /**
     * @param $tag
     * @return mixed|string
     */
    public function _build_where_by_tag($tag)
    {
        $where = $this->convert_item_field($this->build_where_public_with_item());
        $where .= ' AND t.tag_name=' . $this->quote($tag);

        return $where;
    }

    //---------------------------------------------------------
    // sql
    //---------------------------------------------------------

    /**
     * @param $tag
     * @return int
     */
    public function _get_item_count_by_tag_for_cat($tag)
    {
        $sql = 'SELECT COUNT(DISTINCT i.item_id) ';
        $sql .= ' FROM ' . $this->_p2t_table . ' p2t ';
        $sql .= ' INNER JOIN ' . $this->_item_table . ' i ';
        $sql .= ' ON i.item_id = p2t.p2t_photo_id ';
        $sql .= ' INNER JOIN ' . $this->_tag_table . ' t ';
        $sql .= ' ON t.tag_id = p2t.p2t_tag_id ';
        $sql .= ' INNER JOIN ' . $this->_cat_table . ' c ';
        $sql .= ' ON i.item_cat_id = c.cat_id ';
        $sql .= ' WHERE ' . $this->_build_where_by_tag_for_cat($tag);

        return $this->get_count_by_sql($sql);
    }

    /**
     * @param $tag
     * @return int
     */
    public function _get_item_count_by_tag($tag)
    {
        $sql = 'SELECT COUNT(DISTINCT i.item_id) ';
        $sql .= ' FROM ' . $this->_p2t_table . ' p2t ';
        $sql .= ' INNER JOIN ' . $this->_item_table . ' i ';
        $sql .= ' ON i.item_id = p2t.p2t_photo_id ';
        $sql .= ' INNER JOIN ' . $this->_tag_table . ' t ';
        $sql .= ' ON t.tag_id = p2t.p2t_tag_id ';
        $sql .= ' WHERE ' . $this->_build_where_by_tag($tag);

        return $this->get_count_by_sql($sql);
    }

    /**
     * @param     $tag
     * @param     $orderby
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function _get_item_id_array_by_tag_for_cat($tag, $orderby, $limit = 0, $offset = 0)
    {
        $sql = 'SELECT DISTINCT i.item_id ';
        $sql .= ' FROM ' . $this->_p2t_table . ' p2t ';
        $sql .= ' INNER JOIN ' . $this->_item_table . ' i ';
        $sql .= ' ON i.item_id = p2t.p2t_photo_id ';
        $sql .= ' INNER JOIN ' . $this->_tag_table . ' t ';
        $sql .= ' ON t.tag_id = p2t.p2t_tag_id ';
        $sql .= ' INNER JOIN ' . $this->_cat_table . ' c ';
        $sql .= ' ON i.item_cat_id = c.cat_id ';
        $sql .= ' WHERE ' . $this->_build_where_by_tag_for_cat($tag);
        $sql .= ' ORDER BY ' . $orderby;

        return $this->get_first_rows_by_sql($sql, $limit, $offset);
    }

    /**
     * @param     $tag
     * @param     $orderby
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function _get_item_id_array_by_tag($tag, $orderby, $limit = 0, $offset = 0)
    {
        $sql = 'SELECT DISTINCT i.item_id ';
        $sql .= ' FROM ' . $this->_p2t_table . ' p2t ';
        $sql .= ' INNER JOIN ' . $this->_item_table . ' i ';
        $sql .= ' ON i.item_id = p2t.p2t_photo_id ';
        $sql .= ' INNER JOIN ' . $this->_tag_table . ' t ';
        $sql .= ' ON t.tag_id = p2t.p2t_tag_id ';
        $sql .= ' WHERE ' . $this->_build_where_by_tag($tag);
        $sql .= ' ORDER BY ' . $orderby;

        return $this->get_first_rows_by_sql($sql, $limit, $offset);
    }

    /**
     * @param     $key
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function _get_tag_rows_with_count_cat($key, $limit = 0, $offset = 0)
    {
        $sql = 'SELECT t.*, COUNT(*) AS photo_count ';
        $sql .= ' FROM ' . $this->_tag_table . ' t ';
        $sql .= ' INNER JOIN ' . $this->_p2t_table . ' p2t ';
        $sql .= ' ON t.tag_id = p2t.p2t_tag_id ';
        $sql .= ' INNER JOIN ' . $this->_item_table . ' i ';
        $sql .= ' ON i.item_id = p2t.p2t_photo_id ';
        $sql .= ' INNER JOIN ' . $this->_cat_table . ' c ';
        $sql .= ' ON i.item_cat_id = c.cat_id ';
        $sql .= ' WHERE ' . $this->build_where_public_with_item_cat();
        $sql .= ' GROUP BY t.tag_id ';
        $sql .= ' ORDER BY photo_count DESC';

        return $this->get_rows_by_sql($sql, $limit, $offset, $key);
    }

    /**
     * @param     $key
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function _get_tag_rows_with_count($key, $limit = 0, $offset = 0)
    {
        $sql = 'SELECT t.*, COUNT(*) AS photo_count ';
        $sql .= ' FROM ' . $this->_tag_table . ' t ';
        $sql .= ' INNER JOIN ' . $this->_p2t_table . ' p2t ';
        $sql .= ' ON t.tag_id = p2t.p2t_tag_id ';
        $sql .= ' GROUP BY t.tag_id ';
        $sql .= ' ORDER BY photo_count DESC';

        return $this->get_rows_by_sql($sql, $limit, $offset, $key);
    }

    //---------------------------------------------------------
    // config
    //---------------------------------------------------------

    /**
     * @param $dirname
     */
    public function _init_config($dirname)
    {
        $configHandler = webphoto_inc_config::getSingleton($dirname);

        $this->_cfg_perm_cat_read = $configHandler->get_by_name('perm_cat_read');
        $this->_cfg_perm_item_read = $configHandler->get_by_name('perm_item_read');
    }

    // --- class end ---
}
