<?php
// $Id: download.php,v 1.1 2011/05/10 02:59:15 ohwada Exp $

//=========================================================
// webphoto module
// 2008-11-16 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-05-01 K.OHWADA
// Notice [PHP]: Undefined index: file_full
// build_filename_encode()
// 2010-09-17 K.OHWADA
// webphoto_lib_download
// 2008-12-12 K.OHWADA
// check_perm -> check_item_perm
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_main_download
//=========================================================

/**
 * Class webphoto_main_download
 */
class webphoto_main_download extends webphoto_file_read
{
    public $_readfile_class;
    public $_browser_class;
    public $_filename_class;

    public $_TIME_FAIL = 5;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_main_download constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_readfile_class = webphoto_lib_readfile::getInstance();
        $this->_browser_class = webphoto_lib_browser::getInstance();
        $this->_filename_class = webphoto_lib_download_filename::getInstance();

        $is_japanese = $this->xoops_class->is_japanese(_C_WEBPHOTO_JPAPANESE);

        $this->_filename_class->set_charset_local(_CHARSET);
        $this->_filename_class->set_langcode(_LANGCODE);
        $this->_filename_class->set_is_japanese($is_japanese);
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_file_read|\webphoto_item_public|\webphoto_lib_error|\webphoto_main_download
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
    // main
    //---------------------------------------------------------
    public function main()
    {
        $item_id = $this->_post_class->get_post_get_int('item_id');
        $file_kind = $this->_post_class->get_post_get_int('file_kind');

        $item_row = $this->get_item_row($item_id);
        if (!is_array($item_row)) {
            redirect_header($this->_MODULE_URL, $this->_TIME_FAIL, $this->_error);
            exit();
        }

        // check perm down
        if (!$this->check_item_perm($item_row['item_perm_down'])) {
            redirect_header($this->_MODULE_URL, $this->_TIME_FAIL, _NOPERM);
            exit();
        }

        $file_row = $this->get_file_row($item_row, $file_kind);
        if (!is_array($file_row)) {
            redirect_header($this->_MODULE_URL, $this->_TIME_FAIL, $this->_error);
            exit();
        }

        $mime = $file_row['file_mime'];
        $file_name = $file_row['file_name'];

        // Notice [PHP]: Undefined index: file_full
        $file = $file_row['full_path'];

        list($name, $name_alt) = $this->build_filename_by_row($item_row, $file_row);

        list($name, $is_rfc2231) = $this->build_filename_encode($name, $name_alt);

        $this->_readfile_class->readfile_down($file, $mime, $name, $is_rfc2231);

        exit();
    }

    /**
     * @param $item_row
     * @param $file_row
     * @return array
     */
    public function build_filename_by_row($item_row, $file_row)
    {
        $item_title = $item_row['item_title'];
        $file_name = $file_row['file_name'];
        $file_ext = $file_row['file_ext'];
        $file_kind = $file_row['file_kind'];

        $aux = $this->_fileHandler->get_download_image_aux($file_kind);

        if ($item_title) {
            if ($aux && $file_ext) {
                $name = $item_title . '_' . $aux . '.' . $file_ext;
            } elseif ($file_ext) {
                $name = $item_title . '.' . $file_ext;
            } elseif ($aux) {
                $name = $item_title . '_' . $aux;
            } else {
                $name = $item_title;
            }
        } else {
            $name = $file_name;
        }

        return [$name, $file_name];
    }

    /**
     * @param $name
     * @param $name_alt
     * @return array
     */
    public function build_filename_encode($name, $name_alt)
    {
        if (!$this->get_ini('download_filename_encode')) {
            return [$name_alt, false];
        }

        $this->_browser_class->presume_agent();
        $browser = $this->_browser_class->get_browser();

        return $this->_filename_class->build_encode($name, $name_alt, $browser);
    }

    // --- class end ---
}
