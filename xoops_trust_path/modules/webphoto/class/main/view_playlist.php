<?php
// $Id: view_playlist.php,v 1.2 2010/09/19 06:43:11 ohwada Exp $

//=========================================================
// webphoto module
// 2008-11-16 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-09-17 K.OHWADA
// webphoto_lib_readfile
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_main_view_playlist
//=========================================================

/**
 * Class webphoto_main_view_playlist
 */
class webphoto_main_view_playlist extends webphoto_file_read
{
    public $_config_class;
    public $_kind_class;
    public $_readfile_class;

    public $_PLAYLISTS_DIR;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_main_view_playlist constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_config_class = webphoto_config::getInstance($dirname);
        $this->_kind_class = webphoto_kind::getInstance();
        $this->_readfile_class = webphoto_lib_readfile::getInstance();

        $uploads_path = $this->_config_class->get_uploads_path();
        $this->_PLAYLISTS_DIR = XOOPS_ROOT_PATH . $uploads_path . '/playlists';
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_file_read|\webphoto_item_public|\webphoto_lib_error|\webphoto_main_view_playlist
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
    // public
    //---------------------------------------------------------
    public function main()
    {
        $item_id = $this->_post_class->get_post_get_int('item_id');
        $item_row = $this->get_item_row($item_id);
        if (!is_array($item_row)) {
            exit();
        }

        $kind = $item_row['item_kind'];
        $cache = $item_row['item_playlist_cache'];
        $file = $this->_PLAYLISTS_DIR . '/' . $cache;

        if (!$this->_kind_class->is_playlist_kind($kind)) {
            exit();
        }

        if (empty($cache) || !file_exists($file)) {
            exit();
        }

        $this->_readfile_class->readfile_xml($file);

        exit();
    }

    // --- class end ---
}
