<?php
// $Id: item_handler.php,v 1.25 2011/12/26 06:51:31 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-24 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-12-25 K.OHWADA
// str_to_mysql_datetime()
// 2010-11-11 K.OHWADA
// file_id_to_item_name()
// 2010-10-01 K.OHWADA
// item_displayfile etc
// 2010-03-18 K.OHWADA
// change insert update
// 2010-02-15 K.OHWADA
// add $flag_admin in check_perm_by_row_name_groups()
// 2010-01-10 K.OHWADA
// item_description_scroll
// 2009-12-06 K.OHWADA
// item_perm_level
// 2009-11-11 K.OHWADA
// webphoto_lib_handler -> webphoto_handler_base_ini
// info_array_to_str()
// item_detail_onclick
// 2009-10-25 K.OHWADA
// _C_WEBPHOTO_CODEINFO_JPEG
// 2009-01-25 K.OHWADA
// _C_WEBPHOTO_CODEINFO_SWF
// 2009-01-10 K.OHWADA
// item_content etc
// 2009-01-04 K.OHWADA
// item_editor
// 2008-12-12 K.OHWADA
// check_perm_by_row_name_groups()
// move get_rows_public() to item_catHandler
// 2008-12-07 K.OHWADA
// get_text_type_array()
// 2008-11-29 K.OHWADA
// item_icon_width
// get_rows_waiting() -> get_rows_status()
// 2008-11-16 K.OHWADA
// item_codeinfo
// 2008-11-08 K.OHWADA
// item_external_middle
// 2008-10-10 K.OHWADA
// item_embed_type item_playlist_srctype etc
// 2008-10-01 K.OHWADA
// BUG: Incorrect integer value: 'item_file_id_1'
// error in mysql 5 if datetime is null
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_item_handler
//=========================================================

/**
 * Class webphoto_item_handler
 */
class webphoto_item_handler extends webphoto_handler_base_ini
{
    public $_BUILD_SEARCH_ARRAY;
    public $_TEXT_ARRAY;
    public $_ENCODE_ARRAY;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_item_handler constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);
        $this->set_table_prefix_dirname('item');
        $this->set_id_name('item_id');
        $this->set_title_name('item_title');

        $this->_BUILD_SEARCH_ARRAY = $this->item_explode_ini('item_build_search_list');
        $this->_TEXT_ARRAY = $this->item_explode_ini('item_text_list');
        $this->_ENCODE_ARRAY = $this->item_explode_ini('item_encode_list');
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_item_handler|\webphoto_lib_error
     */
    public static function getInstance($dirname = null, $trust_dirname = null)
    {
        static $instance;
        if (null === $instance) {
            $instance = new self($dirname, $trust_dirname);
        }

        return $instance;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function item_explode_ini($name)
    {
        return $this->explode_ini($name, '|', 'item_');
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
            'item_id' => 0,
            'item_time_create' => $time_create,
            'item_time_update' => $time_update,
            'item_time_publish' => 0,
            'item_time_expire' => 0,
            'item_cat_id' => 0,
            'item_gicon_id' => 0,
            'item_player_id' => 0,
            'item_flashvar_id' => 0,
            'item_uid' => 0,
            'item_ext' => '',
            'item_title' => '',
            'item_place' => '',
            'item_equipment' => '',
            'item_gmap_latitude' => 0,
            'item_gmap_longitude' => 0,
            'item_gmap_zoom' => 0,
            'item_gmap_type' => 0,
            'item_status' => 0,
            'item_hits' => 0,
            'item_rating' => 0,
            'item_votes' => 0,
            'item_comments' => 0,
            'item_exif' => '',
            'item_description' => '',
            'item_search' => '',
            'item_duration' => 0,
            'item_width' => 0,
            'item_height' => 0,
            'item_siteurl' => '',
            'item_artist' => '',
            'item_album' => '',
            'item_label' => '',
            'item_displaytype' => 0,
            'item_displayfile' => 0,
            'item_onclick' => 0,
            'item_views' => 0,
            'item_chain' => 0,
            'item_icon_name' => '',
            'item_icon_width' => 0,
            'item_icon_height' => 0,
            'item_external_url' => '',
            'item_external_thumb' => '',
            'item_external_middle' => '',
            'item_embed_type' => '',
            'item_embed_src' => '',
            'item_embed_text' => '',
            'item_playlist_feed' => '',
            'item_playlist_dir' => '',
            'item_playlist_cache' => '',
            'item_playlist_type' => 0,
            'item_page_width' => 0,
            'item_page_height' => 0,
            'item_content' => '',
            'item_detail_onclick' => 0,
            'item_weight' => 0,
            'item_kind' => $this->get_ini('item_kind_default'),
            'item_datetime' => $this->get_ini('item_datetime_default'),
            'item_playlist_time' => $this->get_ini('item_playlist_time_defualt'),
            'item_showinfo' => $this->get_ini('item_showinfo_default'),
            'item_codeinfo' => $this->get_ini('item_codeinfo_default'),
            'item_perm_read' => $this->get_ini('item_perm_read_default'),
            'item_perm_down' => $this->get_ini('item_perm_down_default'),
            'item_perm_level' => $this->get_ini('item_perm_level_default'),
            'item_editor' => $this->get_ini('item_editor_default'),
            'item_description_html' => $this->get_ini('item_description_html_default'),
            'item_description_smiley' => $this->get_ini('item_description_smiley_default'),
            'item_description_xcode' => $this->get_ini('item_description_xcode_default'),
            'item_description_image' => $this->get_ini('item_description_image_default'),
            'item_description_br' => $this->get_ini('item_description_br_default'),
            'item_description_scroll' => $this->get_ini('item_description_scroll_default'),
        ];

        for ($i = 1; $i <= _C_WEBPHOTO_MAX_ITEM_FILE_ID; ++$i) {
            $name = $this->file_id_to_item_name($i);
            $arr[$name] = 0;
        }

        for ($i = 1; $i <= _C_WEBPHOTO_MAX_ITEM_TEXT; ++$i) {
            $name = $this->text_id_to_item_name($i);
            $arr[$name] = '';
        }

        return $arr;
    }

    /**
     * @param $id
     * @return string
     */
    public function file_id_to_item_name($id)
    {
        $str = 'item_file_id_' . $id;

        return $str;
    }

    /**
     * @param $id
     * @return string
     */
    public function text_id_to_item_name($id)
    {
        $str = 'item_text_' . $id;

        return $str;
    }

    /**
     * @param $kind
     * @return string
     */
    public function build_name_fileid_by_kind($kind)
    {
        return $this->file_id_to_item_name($kind);
    }

    /**
     * @param $kind
     * @return string
     */
    public function build_name_text_by_kind($kind)
    {
        return $this->text_id_to_item_name($kind);
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

        if ($item_id > 0) {
            $sql .= 'item_id, ';
        }

        $sql .= 'item_time_create, ';
        $sql .= 'item_time_update, ';
        $sql .= 'item_time_publish, ';
        $sql .= 'item_time_expire, ';
        $sql .= 'item_cat_id, ';
        $sql .= 'item_gicon_id, ';
        $sql .= 'item_player_id, ';
        $sql .= 'item_flashvar_id, ';
        $sql .= 'item_uid, ';
        $sql .= 'item_kind, ';
        $sql .= 'item_ext, ';
        $sql .= 'item_datetime, ';
        $sql .= 'item_title, ';
        $sql .= 'item_place, ';
        $sql .= 'item_equipment, ';
        $sql .= 'item_gmap_latitude, ';
        $sql .= 'item_gmap_longitude, ';
        $sql .= 'item_gmap_zoom, ';
        $sql .= 'item_gmap_type, ';
        $sql .= 'item_perm_read, ';
        $sql .= 'item_perm_down, ';
        $sql .= 'item_status, ';
        $sql .= 'item_hits, ';
        $sql .= 'item_rating, ';
        $sql .= 'item_votes, ';
        $sql .= 'item_comments, ';
        $sql .= 'item_exif, ';
        $sql .= 'item_description, ';
        $sql .= 'item_duration, ';
        $sql .= 'item_width, ';
        $sql .= 'item_height, ';
        $sql .= 'item_displaytype, ';
        $sql .= 'item_displayfile, ';
        $sql .= 'item_onclick, ';
        $sql .= 'item_views, ';
        $sql .= 'item_chain, ';
        $sql .= 'item_siteurl, ';
        $sql .= 'item_artist, ';
        $sql .= 'item_album, ';
        $sql .= 'item_label, ';
        $sql .= 'item_icon_name, ';
        $sql .= 'item_icon_width, ';
        $sql .= 'item_icon_height, ';
        $sql .= 'item_external_url, ';
        $sql .= 'item_external_thumb, ';
        $sql .= 'item_external_middle, ';
        $sql .= 'item_embed_type, ';
        $sql .= 'item_embed_src, ';
        $sql .= 'item_embed_text, ';
        $sql .= 'item_playlist_type, ';
        $sql .= 'item_playlist_time, ';
        $sql .= 'item_playlist_feed, ';
        $sql .= 'item_playlist_dir, ';
        $sql .= 'item_playlist_cache, ';
        $sql .= 'item_showinfo, ';
        $sql .= 'item_codeinfo, ';
        $sql .= 'item_page_width, ';
        $sql .= 'item_page_height, ';
        $sql .= 'item_editor, ';
        $sql .= 'item_description_html, ';
        $sql .= 'item_description_smiley, ';
        $sql .= 'item_description_xcode, ';
        $sql .= 'item_description_image, ';
        $sql .= 'item_description_br, ';
        $sql .= 'item_description_scroll, ';
        $sql .= 'item_content, ';
        $sql .= 'item_detail_onclick, ';
        $sql .= 'item_weight, ';
        $sql .= 'item_perm_level, ';

        for ($i = 1; $i <= _C_WEBPHOTO_MAX_ITEM_FILE_ID; ++$i) {
            $name = $this->file_id_to_item_name($i);
            $sql .= $name . ', ';
        }

        for ($i = 1; $i <= _C_WEBPHOTO_MAX_ITEM_TEXT; ++$i) {
            $name = $this->text_id_to_item_name($i);
            $sql .= $name . ', ';
        }

        $sql .= 'item_search ';

        $sql .= ') VALUES ( ';

        if ($item_id > 0) {
            $sql .= (int)$item_id . ', ';
        }

        $sql .= (int)$item_time_create . ', ';
        $sql .= (int)$item_time_update . ', ';
        $sql .= (int)$item_time_publish . ', ';
        $sql .= (int)$item_time_expire . ', ';
        $sql .= (int)$item_cat_id . ', ';
        $sql .= (int)$item_gicon_id . ', ';
        $sql .= (int)$item_player_id . ', ';
        $sql .= (int)$item_flashvar_id . ', ';
        $sql .= (int)$item_uid . ', ';
        $sql .= (int)$item_kind . ', ';
        $sql .= $this->quote($item_ext) . ', ';
        $sql .= $this->quote($item_datetime) . ', ';
        $sql .= $this->quote($item_title) . ', ';
        $sql .= $this->quote($item_place) . ', ';
        $sql .= $this->quote($item_equipment) . ', ';
        $sql .= (float)$item_gmap_latitude . ', ';
        $sql .= (float)$item_gmap_longitude . ', ';
        $sql .= (int)$item_gmap_zoom . ', ';
        $sql .= (int)$item_gmap_type . ', ';
        $sql .= $this->quote($item_perm_read) . ', ';
        $sql .= $this->quote($item_perm_down) . ', ';
        $sql .= (int)$item_status . ', ';
        $sql .= (int)$item_hits . ', ';
        $sql .= (float)$item_rating . ', ';
        $sql .= (int)$item_votes . ', ';
        $sql .= (int)$item_comments . ', ';
        $sql .= $this->quote($item_exif) . ', ';
        $sql .= $this->quote($item_description) . ', ';
        $sql .= (int)$item_duration . ', ';
        $sql .= (int)$item_width . ', ';
        $sql .= (int)$item_height . ', ';
        $sql .= (int)$item_displaytype . ', ';
        $sql .= (int)$item_displayfile . ', ';
        $sql .= (int)$item_onclick . ', ';
        $sql .= (int)$item_views . ', ';
        $sql .= (int)$item_chain . ', ';
        $sql .= $this->quote($item_siteurl) . ', ';
        $sql .= $this->quote($item_artist) . ', ';
        $sql .= $this->quote($item_album) . ', ';
        $sql .= $this->quote($item_label) . ', ';
        $sql .= $this->quote($item_icon_name) . ', ';
        $sql .= (int)$item_icon_width . ', ';
        $sql .= (int)$item_icon_height . ', ';
        $sql .= $this->quote($item_external_url) . ', ';
        $sql .= $this->quote($item_external_thumb) . ', ';
        $sql .= $this->quote($item_external_middle) . ', ';
        $sql .= $this->quote($item_embed_type) . ', ';
        $sql .= $this->quote($item_embed_src) . ', ';
        $sql .= $this->quote($item_embed_text) . ', ';
        $sql .= (int)$item_playlist_type . ', ';
        $sql .= (int)$item_playlist_time . ', ';
        $sql .= $this->quote($item_playlist_feed) . ', ';
        $sql .= $this->quote($item_playlist_dir) . ', ';
        $sql .= $this->quote($item_playlist_cache) . ', ';
        $sql .= $this->quote($item_showinfo) . ', ';
        $sql .= $this->quote($item_codeinfo) . ', ';
        $sql .= (int)$item_page_width . ', ';
        $sql .= (int)$item_page_height . ', ';
        $sql .= $this->quote($item_editor) . ', ';
        $sql .= (int)$item_description_html . ', ';
        $sql .= (int)$item_description_smiley . ', ';
        $sql .= (int)$item_description_xcode . ', ';
        $sql .= (int)$item_description_image . ', ';
        $sql .= (int)$item_description_br . ', ';
        $sql .= (int)$item_description_scroll . ', ';
        $sql .= $this->quote($item_content) . ', ';
        $sql .= (int)$item_detail_onclick . ', ';
        $sql .= (int)$item_weight . ', ';
        $sql .= (int)$item_perm_level . ', ';

        for ($i = 1; $i <= _C_WEBPHOTO_MAX_ITEM_FILE_ID; ++$i) {
            $name = $this->file_id_to_item_name($i);
            $sql .= (int)$row[$name] . ', ';
        }

        for ($i = 1; $i <= _C_WEBPHOTO_MAX_ITEM_TEXT; ++$i) {
            $name = $this->text_id_to_item_name($i);
            $sql .= $this->quote($row[$name]) . ', ';
        }

        $sql .= $this->quote($item_search) . ' ';

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
        $sql .= 'item_time_create=' . (int)$item_time_create . ', ';
        $sql .= 'item_time_update=' . (int)$item_time_update . ', ';
        $sql .= 'item_time_publish=' . (int)$item_time_publish . ', ';
        $sql .= 'item_time_expire=' . (int)$item_time_expire . ', ';
        $sql .= 'item_cat_id=' . (int)$item_cat_id . ', ';
        $sql .= 'item_gicon_id=' . (int)$item_gicon_id . ', ';
        $sql .= 'item_player_id=' . (int)$item_player_id . ', ';
        $sql .= 'item_flashvar_id=' . (int)$item_flashvar_id . ', ';
        $sql .= 'item_uid=' . (int)$item_uid . ', ';
        $sql .= 'item_kind=' . $this->quote($item_kind) . ', ';
        $sql .= 'item_ext=' . $this->quote($item_ext) . ', ';
        $sql .= 'item_datetime=' . $this->quote($item_datetime) . ', ';
        $sql .= 'item_title=' . $this->quote($item_title) . ', ';
        $sql .= 'item_place=' . $this->quote($item_place) . ', ';
        $sql .= 'item_equipment=' . $this->quote($item_equipment) . ', ';
        $sql .= 'item_gmap_latitude=' . (float)$item_gmap_latitude . ', ';
        $sql .= 'item_gmap_longitude=' . (float)$item_gmap_longitude . ', ';
        $sql .= 'item_gmap_zoom=' . (int)$item_gmap_zoom . ', ';
        $sql .= 'item_gmap_type=' . (int)$item_gmap_type . ', ';
        $sql .= 'item_perm_read=' . $this->quote($item_perm_read) . ', ';
        $sql .= 'item_perm_down=' . $this->quote($item_perm_down) . ', ';
        $sql .= 'item_status=' . (int)$item_status . ', ';
        $sql .= 'item_hits=' . (int)$item_hits . ', ';
        $sql .= 'item_rating=' . (float)$item_rating . ', ';
        $sql .= 'item_votes=' . (int)$item_votes . ', ';
        $sql .= 'item_comments=' . (int)$item_comments . ', ';
        $sql .= 'item_exif=' . $this->quote($item_exif) . ', ';
        $sql .= 'item_description=' . $this->quote($item_description) . ', ';
        $sql .= 'item_duration=' . (int)$item_duration . ', ';
        $sql .= 'item_width=' . (int)$item_width . ', ';
        $sql .= 'item_height=' . (int)$item_height . ', ';
        $sql .= 'item_displaytype=' . (int)$item_displaytype . ', ';
        $sql .= 'item_displayfile=' . (int)$item_displayfile . ', ';
        $sql .= 'item_onclick=' . (int)$item_onclick . ', ';
        $sql .= 'item_views=' . (int)$item_views . ', ';
        $sql .= 'item_chain=' . (int)$item_chain . ', ';
        $sql .= 'item_siteurl=' . $this->quote($item_siteurl) . ', ';
        $sql .= 'item_artist=' . $this->quote($item_artist) . ', ';
        $sql .= 'item_album=' . $this->quote($item_album) . ', ';
        $sql .= 'item_label=' . $this->quote($item_label) . ', ';
        $sql .= 'item_icon_name=' . $this->quote($item_icon_name) . ', ';
        $sql .= 'item_icon_width=' . (int)$item_icon_width . ', ';
        $sql .= 'item_icon_height=' . (int)$item_icon_height . ', ';
        $sql .= 'item_external_url=' . $this->quote($item_external_url) . ', ';
        $sql .= 'item_external_thumb=' . $this->quote($item_external_thumb) . ', ';
        $sql .= 'item_external_middle=' . $this->quote($item_external_middle) . ', ';
        $sql .= 'item_embed_type=' . $this->quote($item_embed_type) . ', ';
        $sql .= 'item_embed_src=' . $this->quote($item_embed_src) . ', ';
        $sql .= 'item_embed_text=' . $this->quote($item_embed_text) . ', ';
        $sql .= 'item_playlist_type=' . (int)$item_playlist_type . ', ';
        $sql .= 'item_playlist_time=' . (int)$item_playlist_time . ', ';
        $sql .= 'item_playlist_feed=' . $this->quote($item_playlist_feed) . ', ';
        $sql .= 'item_playlist_dir=' . $this->quote($item_playlist_dir) . ', ';
        $sql .= 'item_playlist_cache=' . $this->quote($item_playlist_cache) . ', ';
        $sql .= 'item_showinfo=' . $this->quote($item_showinfo) . ', ';
        $sql .= 'item_codeinfo=' . $this->quote($item_codeinfo) . ', ';
        $sql .= 'item_page_width=' . (int)$item_page_width . ', ';
        $sql .= 'item_page_height=' . (int)$item_page_height . ', ';
        $sql .= 'item_editor=' . $this->quote($item_editor) . ', ';
        $sql .= 'item_description_html=' . (int)$item_description_html . ', ';
        $sql .= 'item_description_smiley=' . (int)$item_description_smiley . ', ';
        $sql .= 'item_description_xcode=' . (int)$item_description_xcode . ', ';
        $sql .= 'item_description_image=' . (int)$item_description_image . ', ';
        $sql .= 'item_description_br=' . (int)$item_description_br . ', ';
        $sql .= 'item_description_scroll=' . (int)$item_description_scroll . ', ';
        $sql .= 'item_content=' . $this->quote($item_content) . ', ';
        $sql .= 'item_detail_onclick=' . (int)$item_detail_onclick . ', ';
        $sql .= 'item_weight=' . (int)$item_weight . ', ';
        $sql .= 'item_perm_level=' . (int)$item_perm_level . ', ';

        for ($i = 1; $i <= _C_WEBPHOTO_MAX_ITEM_FILE_ID; ++$i) {
            $name = $this->file_id_to_item_name($i);
            $sql .= $name . '=' . (int)$row[$name] . ', ';
        }

        for ($i = 1; $i <= _C_WEBPHOTO_MAX_ITEM_TEXT; ++$i) {
            $name = $this->text_id_to_item_name($i);
            $sql .= $name . '=' . $this->quote($row[$name]) . ', ';
        }

        $sql .= 'item_search=' . $this->quote($item_search) . ' ';
        $sql .= 'WHERE item_id=' . (int)$item_id;

        return $this->query($sql, 0, 0, $force);
    }

    /**
     * @param $id_array
     * @return mixed
     */
    public function update_status_by_id_array($id_array)
    {
        $sql = 'UPDATE ' . $this->_table . ' SET ';
        $sql .= 'item_status=1 ';
        $sql .= 'WHERE ' . $this->build_where_by_itemid_array($id_array);

        return $this->query($sql);
    }

    /**
     * @param $item_id
     * @param $votes
     * @param $rating
     * @return mixed
     */
    public function update_rating_by_id($item_id, $votes, $rating)
    {
        $sql = 'UPDATE ' . $this->_table . ' SET ';
        $sql .= 'item_rating=' . (float)$rating . ', ';
        $sql .= 'item_votes=' . (int)$votes . ' ';
        $sql .= 'WHERE item_id=' . (int)$item_id;

        return $this->query($sql);
    }

    /**
     * @param $gicon_id
     * @return mixed
     */
    public function clear_gicon_id($gicon_id)
    {
        $sql = 'UPDATE ' . $this->_table . ' SET ';
        $sql .= 'item_gicon_id=0 ';
        $sql .= 'WHERE item_gicon_id=' . (int)$gicon_id;

        return $this->query($sql);
    }

    // when GET request

    /**
     * @param      $item_id
     * @param bool $force
     * @return mixed
     */
    public function countup_hits($item_id, $force = false)
    {
        $sql = 'UPDATE ' . $this->_table . ' SET ';
        $sql .= 'item_hits = item_hits+1 ';
        $sql .= 'WHERE ' . $this->build_where_public();
        $sql .= 'AND item_id=' . (int)$item_id;

        return $this->query($sql, 0, 0, $force);
    }

    /**
     * @param      $item_id
     * @param bool $force
     * @return mixed
     */
    public function countup_views($item_id, $force = false)
    {
        $sql = 'UPDATE ' . $this->_table . ' SET ';
        $sql .= 'item_views = item_views+1 ';
        $sql .= 'WHERE ' . $this->build_where_public();
        $sql .= 'AND item_id=' . (int)$item_id;

        return $this->query($sql, 0, 0, $force);
    }

    /**
     * @param      $item_id
     * @param      $status
     * @param bool $force
     * @return mixed
     */
    public function update_status($item_id, $status, $force = false)
    {
        $sql = 'UPDATE ' . $this->_table . ' SET ';
        $sql .= ' item_status = ' . (int)$status;
        $sql .= ' WHERE item_id=' . (int)$item_id;

        return $this->query($sql, 0, 0, $force);
    }

    /**
     * @param      $item_id
     * @param      $cache
     * @param bool $force
     * @return mixed
     */
    public function update_playlist_cache($item_id, $cache, $force = false)
    {
        $sql = 'UPDATE ' . $this->_table . ' SET ';
        $sql .= 'item_playlist_cache =' . $this->quote($cache);
        $sql .= 'WHERE item_id=' . (int)$item_id;

        return $this->query($sql, 0, 0, $force);
    }

    //---------------------------------------------------------
    // get count
    //---------------------------------------------------------

    /**
     * @param $status
     * @return int
     */
    public function get_count_status($status)
    {
        return $this->get_count_by_where($this->build_where_status($status));
    }

    /**
     * @return int
     */
    public function get_count_waiting()
    {
        return $this->get_count_status(_C_WEBPHOTO_STATUS_WAITING);
    }

    /**
     * @param $cat_id
     * @return int
     */
    public function get_count_by_catid($cat_id)
    {
        $where = 'item_cat_id=' . (int)$cat_id;

        return $this->get_count_by_where($where);
    }

    /**
     * @param $item_id
     * @param $uid
     * @return int
     */
    public function get_count_by_itemid_uid($item_id, $uid)
    {
        $where = 'item_id=' . (int)$item_id;
        $where .= ' AND item_uid=' . (int)$uid;

        return $this->get_count_by_where($where);
    }

    /**
     * @return int
     */
    public function get_count_photo()
    {
        return $this->get_count_by_where($this->build_where_ext_photo());
    }

    /**
     * @return int
     */
    public function get_count_photo_detail_onclick()
    {
        return $this->get_count_by_where($this->build_where_ext_photo_detail_onclick_image());
    }

    //---------------------------------------------------------
    // get row
    //---------------------------------------------------------

    /**
     * @param $item_id
     * @return bool|mixed
     */
    public function get_title_by_id($item_id)
    {
        $row = $this->get_row_by_id($item_id);
        if (is_array($row)) {
            return $row['item_title'];
        }

        return false;
    }

    //---------------------------------------------------------
    // get rows
    //---------------------------------------------------------

    /**
     * @param     $status
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_rows_status($status, $limit = 0, $offset = 0)
    {
        $where = $this->build_where_status($status);
        $orderby = 'item_id ASC';

        return $this->get_rows_by_where_orderby($where, $orderby, $limit, $offset);
    }

    /**
     * @param     $cat_id
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_rows_by_catid($cat_id, $limit = 0, $offset = 0)
    {
        $where = 'item_cat_id=' . (int)$cat_id;
        $orderby = 'item_id ASC';

        return $this->get_rows_by_where_orderby($where, $orderby, $limit, $offset);
    }

    /**
     * @param     $id_array
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_rows_by_id_array($id_array, $limit = 0, $offset = 0)
    {
        $where = $this->build_where_by_itemid_array($id_array);
        $orderby = 'item_id ASC';

        return $this->get_rows_by_where_orderby($where, $orderby, $limit, $offset);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_rows_flashplayer($limit = 0, $offset = 0)
    {
        $sql = 'SELECT * FROM ' . $this->_table;
        $sql .= ' WHERE item_displaytype >= ' . _C_WEBPHOTO_DISPLAYTYPE_SWFOBJECT;
        $sql .= ' ORDER BY item_title ASC';

        return $this->get_rows_by_sql($sql, $limit, $offset);
    }

    /**
     * @param $id_array
     * @return array
     */
    public function get_rows_from_id_array($id_array)
    {
        $arr = [];
        foreach ($id_array as $id) {
            $arr[] = $this->get_row_by_id($id);
        }

        return $arr;
    }

    //---------------------------------------------------------
    // where
    //---------------------------------------------------------

    /**
     * @param $keyword_array
     * @param $cat_id
     * @return null|string
     */
    public function build_where_by_keyword_array_catid($keyword_array, $cat_id)
    {
        $where_key = $this->build_where_by_keyword_array($keyword_array, 'item_search');

        $where_cat = null;
        if (0 != $cat_id) {
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

    //---------------------------------------------------------
    // build
    //---------------------------------------------------------

    /**
     * @param     $rating
     * @param int $decimals
     * @return string
     */
    public function format_rating($rating, $decimals = 2)
    {
        return number_format($rating, $decimals);
    }

    /**
     * @param $row
     * @return string
     */
    public function build_search($row)
    {
        $text = '';

        foreach ($row as $k => $v) {
            if (in_array($k, $this->_BUILD_SEARCH_ARRAY)) {
                $text .= $v . ' ';
            }
        }

        for ($i = 1; $i <= _C_WEBPHOTO_MAX_ITEM_TEXT; ++$i) {
            $text .= $row['item_text_' . $i] . ' ';
        }

        return $text;
    }

    //---------------------------------------------------------
    // where
    //---------------------------------------------------------

    /**
     * @return string
     */
    public function build_where_public()
    {
        $where = ' item_status > 0 ';

        return $where;
    }

    /**
     * @param $status
     * @return string
     */
    public function build_where_status($status)
    {
        $where = ' item_status = ' . (int)$status;

        return $where;
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
    public function build_where_ext_photo()
    {
        $str = " ( item_ext='gif' ";
        $str .= "OR item_ext='png' ";
        $str .= "OR item_ext='jpg' ";
        $str .= "OR item_ext='jpeg' ) ";

        return $str;
    }

    /**
     * @return string
     */
    public function build_where_detail_onclick_image()
    {
        $str = ' ( item_detail_onclick=' . _C_WEBPHOTO_DETAIL_ONCLICK_IMAGE . ' ';
        $str .= 'OR item_detail_onclick=' . _C_WEBPHOTO_DETAIL_ONCLICK_LIGHTBOX . ' ) ';

        return $str;
    }

    /**
     * @return string
     */
    public function build_where_ext_photo_detail_onclick_image()
    {
        $str = $this->build_where_ext_photo();
        $str .= ' AND ';
        $str .= $this->build_where_detail_onclick_image();

        return $str;
    }

    //---------------------------------------------------------
    // build datetime
    //---------------------------------------------------------

    /**
     * @param      $key
     * @param null $default
     * @return bool|string
     */
    public function build_datetime_by_post($key, $default = null)
    {
        $val = isset($_POST[$key]) ? $_POST[$key] : $default;

        return $this->build_datetime($val);
    }

    /**
     * @param $str
     * @return bool|string
     */
    public function build_datetime($str)
    {
        return $this->str_to_mysql_datetime($str);
    }

    /**
     * @param $arr
     * @return bool|string
     */
    public function build_info($arr)
    {
        return $this->info_array_to_str($this->sanitize_array_int($arr));
    }

    //---------------------------------------------------------
    // for show
    //---------------------------------------------------------

    /**
     * @param $row
     * @param $kind
     * @return bool
     */
    public function build_value_fileid_by_kind($row, $kind)
    {
        if (isset($row[$this->build_name_fileid_by_kind($kind)])) {
            return $row[$this->build_name_fileid_by_kind($kind)];
        }

        return false;
    }

    /**
     * @param $row
     * @param $num
     * @return bool
     */
    public function build_value_text_by_num($row, $num)
    {
        if (isset($row[$this->build_name_text_by_num($num)])) {
            return $row[$this->build_name_text_by_num($num)];
        }

        return false;
    }

    /**
     * @param $row
     * @return mixed
     */
    public function build_show_description_disp($row)
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

    /**
     * @param $row
     * @return mixed
     */
    public function build_show_exif_disp($row)
    {
        $myts = MyTextSanitizer::getInstance();

        return $myts->displayTarea($row['item_exif'], 0, 0, 0, 0, 1);
    }

    /**
     * @param $item_row
     * @param $base_url
     * @return array
     */
    public function build_show_icon_image($item_row, $base_url)
    {
        $url = null;
        $name = $item_row['item_icon_name'];
        $width = $item_row['item_icon_width'];
        $height = $item_row['item_icon_height'];
        if ($name) {
            $url = $base_url . '/' . $name;
        }

        return [$url, $width, $height];
    }

    /**
     * @param $row
     * @return array
     */
    public function get_showinfo_array($row)
    {
        return $this->info_str_to_array($row['item_showinfo']);
    }

    /**
     * @param $row
     * @return array
     */
    public function get_codeinfo_array($row)
    {
        return $this->info_str_to_array($row['item_codeinfo']);
    }

    //---------------------------------------------------------
    // permission
    //---------------------------------------------------------

    /**
     * @param $row
     * @return array
     */
    public function get_perm_read_array($row)
    {
        return $this->get_perm_array($row['item_perm_read']);
    }

    /**
     * @param $row
     * @return array
     */
    public function get_perm_down_array($row)
    {
        return $this->get_perm_array($row['item_perm_down']);
    }

    /**
     * @param      $row
     * @param null $groups
     * @param bool $flag_admin
     * @return bool
     */
    public function check_perm_read_by_row($row, $groups = null, $flag_admin = false)
    {
        return $this->check_perm_by_row_name_groups($row, 'item_perm_read', $groups, $flag_admin);
    }

    /**
     * @param      $row
     * @param null $groups
     * @param bool $flag_admin
     * @return bool
     */
    public function check_perm_down_by_row($row, $groups = null, $flag_admin = false)
    {
        return $this->check_perm_by_row_name_groups($row, 'item_perm_down', $groups, $flag_admin);
    }

    /**
     * @param $row
     * @return bool
     */
    public function is_public_read_by_row($row)
    {
        return $this->check_perm_by_row_name_groups($row, 'item_perm_read', [XOOPS_GROUP_ANONYMOUS]);
    }

    //---------------------------------------------------------
    // for comment_new
    //---------------------------------------------------------

    /**
     * @return bool|mixed|null
     */
    public function get_replytitle()
    {
        $com_itemid = isset($_GET['com_itemid']) ? (int)$_GET['com_itemid'] : 0;

        if ($com_itemid > 0) {
            return $this->get_title_by_id($com_itemid);
        }

        return null;
    }

    //---------------------------------------------------------
    // text define
    //---------------------------------------------------------

    /**
     * @return array
     */
    public function get_text_type_array()
    {
        return array_merge($this->_TEXT_ARRAY, $this->_ENCODE_ARRAY);
    }

    /**
     * @return mixed
     */
    public function get_encode_type_array()
    {
        return $this->_ENCODE_ARRAY;
    }

    //---------------------------------------------------------
    // option
    //---------------------------------------------------------

    /**
     * @param string $kind
     * @return array
     */
    public function get_kind_options($kind = 'default')
    {
        switch ($kind) {
            case 'playlist':
                $arr = $this->get_kind_playlist_options();
                break;
            case 'default':
            default:
                $arr = $this->get_kind_default_options();
                break;
        }

        return $arr;
    }

    /**
     * @return array
     */
    public function get_kind_default_options()
    {
        $arr = [
            _C_WEBPHOTO_ITEM_KIND_UNDEFINED => _WEBPHOTO_ITEM_KIND_UNDEFINED,
            _C_WEBPHOTO_ITEM_KIND_NONE => _WEBPHOTO_ITEM_KIND_NONE,
            _C_WEBPHOTO_ITEM_KIND_GENERAL => _WEBPHOTO_ITEM_KIND_GENERAL,
            _C_WEBPHOTO_ITEM_KIND_IMAGE => _WEBPHOTO_ITEM_KIND_IMAGE,

            // v2.30
            _C_WEBPHOTO_ITEM_KIND_IMAGE_CMYK => _WEBPHOTO_ITEM_KIND_IMAGE_CMYK,

            _C_WEBPHOTO_ITEM_KIND_IMAGE_OTHER => _WEBPHOTO_ITEM_KIND_IMAGE_OTHER,
            _C_WEBPHOTO_ITEM_KIND_VIDEO => _WEBPHOTO_ITEM_KIND_VIDEO,

            // v2.30
            _C_WEBPHOTO_ITEM_KIND_VIDEO_H264 => _WEBPHOTO_ITEM_KIND_VIDEO_H264,

            _C_WEBPHOTO_ITEM_KIND_AUDIO => _WEBPHOTO_ITEM_KIND_AUDIO,
            _C_WEBPHOTO_ITEM_KIND_OFFICE => _WEBPHOTO_ITEM_KIND_OFFICE,
            _C_WEBPHOTO_ITEM_KIND_EXTERNAL_GENERAL => _WEBPHOTO_ITEM_KIND_EXTERNAL_GENERAL,
            _C_WEBPHOTO_ITEM_KIND_EXTERNAL_IMAGE => _WEBPHOTO_ITEM_KIND_EXTERNAL_IMAGE,
            _C_WEBPHOTO_ITEM_KIND_EMBED => _WEBPHOTO_ITEM_KIND_EMBED,
            _C_WEBPHOTO_ITEM_KIND_PLAYLIST_FEED => _WEBPHOTO_ITEM_KIND_PLAYLIST_FEED,
            _C_WEBPHOTO_ITEM_KIND_PLAYLIST_DIR => _WEBPHOTO_ITEM_KIND_PLAYLIST_DIR,
        ];

        return $arr;
    }

    /**
     * @return array
     */
    public function get_kind_playlist_options()
    {
        $arr = [
            _C_WEBPHOTO_ITEM_KIND_PLAYLIST_FEED => _WEBPHOTO_ITEM_KIND_PLAYLIST_FEED,
            _C_WEBPHOTO_ITEM_KIND_PLAYLIST_DIR => _WEBPHOTO_ITEM_KIND_PLAYLIST_DIR,
        ];

        return $arr;
    }

    /**
     * @return array
     */
    public function get_displaytype_options()
    {
        $arr = [
            _C_WEBPHOTO_DISPLAYTYPE_GENERAL => _WEBPHOTO_ITEM_DISPLAYTYPE_GENERAL,
            _C_WEBPHOTO_DISPLAYTYPE_IMAGE => _WEBPHOTO_ITEM_DISPLAYTYPE_IMAGE,
            _C_WEBPHOTO_DISPLAYTYPE_EMBED => _WEBPHOTO_ITEM_DISPLAYTYPE_EMBED,
            _C_WEBPHOTO_DISPLAYTYPE_SWFOBJECT => _WEBPHOTO_ITEM_DISPLAYTYPE_SWFOBJECT,
            _C_WEBPHOTO_DISPLAYTYPE_MEDIAPLAYER => _WEBPHOTO_ITEM_DISPLAYTYPE_MEDIAPLAYER,
            _C_WEBPHOTO_DISPLAYTYPE_IMAGEROTATOR => _WEBPHOTO_ITEM_DISPLAYTYPE_IMAGEROTATOR,
        ];

        return $arr;
    }

    /**
     * @return array
     */
    public function get_displayfile_options()
    {
        $arr = [
            _C_WEBPHOTO_DISPLAYFILE_DEFAULT => _WEBPHOTO_ITEM_DISPLAYFILE_DEFAULT,
            _C_WEBPHOTO_FILE_KIND_CONT => _WEBPHOTO_FILE_KIND_CONT,
            //      _C_WEBPHOTO_FILE_KIND_THUMB         => _WEBPHOTO_FILE_KIND_THUMB ,
            //      _C_WEBPHOTO_FILE_KIND_MIDDLE        => _WEBPHOTO_FILE_KIND_MIDDLE ,
            _C_WEBPHOTO_FILE_KIND_FLASH => _WEBPHOTO_FILE_KIND_FLASH,
            //      _C_WEBPHOTO_FILE_KIND_DOCOMO        => _WEBPHOTO_FILE_KIND_DOCOMO ,
            //      _C_WEBPHOTO_FILE_KIND_PDF           => _WEBPHOTO_FILE_KIND_PDF ,
            _C_WEBPHOTO_FILE_KIND_SWF => _WEBPHOTO_FILE_KIND_SWF,
            //      _C_WEBPHOTO_FILE_KIND_SMALL         => _WEBPHOTO_FILE_KIND_SMALL ,
            //      _C_WEBPHOTO_FILE_KIND_JPEG          => _WEBPHOTO_FILE_KIND_JPEG ,
            _C_WEBPHOTO_FILE_KIND_MP3 => _WEBPHOTO_FILE_KIND_MP3,
        ];

        return $arr;
    }

    /**
     * @return array
     */
    public function get_onclick_options()
    {
        $arr = [
            _C_WEBPHOTO_ONCLICK_PAGE => _WEBPHOTO_ITEM_ONCLICK_PAGE,
            _C_WEBPHOTO_ONCLICK_DIRECT => _WEBPHOTO_ITEM_ONCLICK_DIRECT,
            _C_WEBPHOTO_ONCLICK_POPUP => _WEBPHOTO_ITEM_ONCLICK_POPUP,
        ];

        return $arr;
    }

    /**
     * @return array
     */
    public function get_status_options()
    {
        $arr = [
            _C_WEBPHOTO_STATUS_WAITING => _WEBPHOTO_ITEM_STATUS_WAITING,
            _C_WEBPHOTO_STATUS_APPROVED => _WEBPHOTO_ITEM_STATUS_APPROVED,
            _C_WEBPHOTO_STATUS_UPDATED => _WEBPHOTO_ITEM_STATUS_UPDATED,
            _C_WEBPHOTO_STATUS_OFFLINE => _WEBPHOTO_ITEM_STATUS_OFFLINE,
            _C_WEBPHOTO_STATUS_EXPIRED => _WEBPHOTO_ITEM_STATUS_EXPIRED,
        ];

        return $arr;
    }

    /**
     * @return array
     */
    public function get_playlist_type_options()
    {
        $arr = [
            _C_WEBPHOTO_PLAYLIST_TYPE_NONE => _WEBPHOTO_ITEM_PLAYLIST_TYPE_NONE,
            _C_WEBPHOTO_PLAYLIST_TYPE_IMAGE => _WEBPHOTO_ITEM_PLAYLIST_TYPE_IMAGE,
            _C_WEBPHOTO_PLAYLIST_TYPE_AUDIO => _WEBPHOTO_ITEM_PLAYLIST_TYPE_AUDIO,
            _C_WEBPHOTO_PLAYLIST_TYPE_VIDEO => _WEBPHOTO_ITEM_PLAYLIST_TYPE_VIDEO,
            //      _C_WEBPHOTO_PLAYLIST_TYPE_FLASH  => _WEBPHOTO_ITEM_PLAYLIST_TYPE_FLASH ,
        ];

        return $arr;
    }

    /**
     * @return array
     */
    public function get_playlist_time_options()
    {
        $arr = [
            _C_WEBPHOTO_PLAYLIST_TIME_HOUR => _WEBPHOTO_ITEM_PLAYLIST_TIME_HOUR,
            _C_WEBPHOTO_PLAYLIST_TIME_DAY => _WEBPHOTO_ITEM_PLAYLIST_TIME_DAY,
            _C_WEBPHOTO_PLAYLIST_TIME_WEEK => _WEBPHOTO_ITEM_PLAYLIST_TIME_WEEK,
            _C_WEBPHOTO_PLAYLIST_TIME_MONTH => _WEBPHOTO_ITEM_PLAYLIST_TIME_MONTH,
        ];

        return $arr;
    }

    /**
     * @return array
     */
    public function get_showinfo_options()
    {
        $arr = [
            _C_WEBPHOTO_SHOWINFO_DESCRIPTION => _WEBPHOTO_ITEM_SHOWINFO_DESCRIPTION,
            _C_WEBPHOTO_SHOWINFO_LOGOIMAGE => _WEBPHOTO_ITEM_SHOWINFO_LOGOIMAGE,
            _C_WEBPHOTO_SHOWINFO_CREDITS => _WEBPHOTO_ITEM_SHOWINFO_CREDITS,
            _C_WEBPHOTO_SHOWINFO_STATISTICS => _WEBPHOTO_ITEM_SHOWINFO_STATISTICS,
            _C_WEBPHOTO_SHOWINFO_SUBMITTER => _WEBPHOTO_ITEM_SHOWINFO_SUBMITTER,
            _C_WEBPHOTO_SHOWINFO_POPUP => _WEBPHOTO_ITEM_SHOWINFO_POPUP,
            _C_WEBPHOTO_SHOWINFO_DOWNLOAD => _WEBPHOTO_ITEM_SHOWINFO_DOWNLOAD,
            _C_WEBPHOTO_SHOWINFO_WEBSITE => _WEBPHOTO_ITEM_SHOWINFO_WEBSITE,
            _C_WEBPHOTO_SHOWINFO_WEBFEED => _WEBPHOTO_ITEM_SHOWINFO_WEBFEED,
        ];

        return $arr;
    }

    /**
     * @return array
     */
    public function get_codeinfo_options()
    {
        $arr = [
            _C_WEBPHOTO_CODEINFO_CONT => _WEBPHOTO_ITEM_CODEINFO_CONT,
            _C_WEBPHOTO_CODEINFO_JPEG => _WEBPHOTO_ITEM_CODEINFO_JPEG,
            _C_WEBPHOTO_CODEINFO_THUMB => _WEBPHOTO_ITEM_CODEINFO_THUMB,
            _C_WEBPHOTO_CODEINFO_LARGE => _WEBPHOTO_ITEM_CODEINFO_LARGE,
            _C_WEBPHOTO_CODEINFO_MIDDLE => _WEBPHOTO_ITEM_CODEINFO_MIDDLE,
            _C_WEBPHOTO_CODEINFO_SMALL => _WEBPHOTO_ITEM_CODEINFO_SMALL,
            _C_WEBPHOTO_CODEINFO_FLASH => _WEBPHOTO_ITEM_CODEINFO_FLASH,
            //      _C_WEBPHOTO_CODEINFO_DOCOMO => _WEBPHOTO_ITEM_CODEINFO_DOCOMO ,
            _C_WEBPHOTO_CODEINFO_WAV => _WEBPHOTO_ITEM_CODEINFO_WAV,
            _C_WEBPHOTO_CODEINFO_MP3 => _WEBPHOTO_ITEM_CODEINFO_MP3,
            _C_WEBPHOTO_CODEINFO_PDF => _WEBPHOTO_ITEM_CODEINFO_PDF,
            _C_WEBPHOTO_CODEINFO_SWF => _WEBPHOTO_ITEM_CODEINFO_SWF,
            _C_WEBPHOTO_CODEINFO_PAGE => _WEBPHOTO_ITEM_CODEINFO_PAGE,
            _C_WEBPHOTO_CODEINFO_SITE => _WEBPHOTO_ITEM_CODEINFO_SITE,
            _C_WEBPHOTO_CODEINFO_PLAY => _WEBPHOTO_ITEM_CODEINFO_PLAY,
            _C_WEBPHOTO_CODEINFO_EMBED => _WEBPHOTO_ITEM_CODEINFO_EMBED,
            _C_WEBPHOTO_CODEINFO_JS => _WEBPHOTO_ITEM_CODEINFO_JS,
        ];

        return $arr;
    }

    /**
     * @return array
     */
    public function get_detail_onclick_options()
    {
        $arr = [
            _C_WEBPHOTO_DETAIL_ONCLICK_DEFAULT => _WEBPHOTO_ITEM_DETAIL_ONCLICK_DEFAULT,
            _C_WEBPHOTO_DETAIL_ONCLICK_DOWNLOAD => _WEBPHOTO_ITEM_DETAIL_ONCLICK_DOWNLOAD,
            _C_WEBPHOTO_DETAIL_ONCLICK_IMAGE => _WEBPHOTO_ITEM_DETAIL_ONCLICK_IMAGE,
            _C_WEBPHOTO_DETAIL_ONCLICK_LIGHTBOX => _WEBPHOTO_ITEM_DETAIL_ONCLICK_LIGHTBOX,
            //      _C_WEBPHOTO_FILE_KIND_CONT          => _WEBPHOTO_FILE_KIND_CONT ,
            //      _C_WEBPHOTO_FILE_KIND_THUMB         => _WEBPHOTO_FILE_KIND_THUMB ,
            //      _C_WEBPHOTO_FILE_KIND_MIDDLE        => _WEBPHOTO_FILE_KIND_MIDDLE ,
            //      _C_WEBPHOTO_FILE_KIND_FLASH         => _WEBPHOTO_FILE_KIND_FLASH ,
            //      _C_WEBPHOTO_FILE_KIND_DOCOMO        => _WEBPHOTO_FILE_KIND_DOCOMO ,
            _C_WEBPHOTO_FILE_KIND_PDF => _WEBPHOTO_FILE_KIND_PDF,
            //      _C_WEBPHOTO_FILE_KIND_SWF           => _WEBPHOTO_FILE_KIND_SWF ,
            //      _C_WEBPHOTO_FILE_KIND_SMALL         => _WEBPHOTO_FILE_KIND_SMALL ,
            //      _C_WEBPHOTO_FILE_KIND_JPEG          => _WEBPHOTO_FILE_KIND_JPEG ,
            //      _C_WEBPHOTO_FILE_KIND_MP3           => _WEBPHOTO_FILE_KIND_MP3 ,
        ];

        return $arr;
    }

    /**
     * @return array
     */
    public function get_perm_level_options()
    {
        $arr = [
            _C_WEBPHOTO_PERM_LEVEL_PUBLIC => _WEBPHOTO_ITEM_PERM_LEVEL_PUBLIC,
            _C_WEBPHOTO_PERM_LEVEL_GROUP => _WEBPHOTO_ITEM_PERM_LEVEL_GROUP,
        ];

        return $arr;
    }

    // --- class end ---
}
