<?php
// $Id: file_read.php,v 1.7 2011/05/10 02:56:39 ohwada Exp $

//=========================================================
// webphoto module
// 2008-11-16 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-05-01 K.OHWADA
// webphoto_lib_multibyte -> webphoto_multibyte
// 2010-11-11 K.OHWADA
// get_extend_row_by_id()
// 2010-09-17 K.OHWADA
// BUG: slash '/' is unnecessary
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_file_handler
// 2008-12-12 K.OHWADA
// webphoto_item_public
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_file_read
//=========================================================

/**
 * Class webphoto_file_read
 */
class webphoto_file_read extends webphoto_item_public
{
    public $_fileHandler;
    public $_multibyte_class;
    public $_post_class;
    public $_utility_class;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_file_read constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_fileHandler = webphoto_file_handler::getInstance($dirname, $trust_dirname);
        $this->_multibyte_class = webphoto_multibyte::getInstance();
        $this->_post_class = webphoto_lib_post::getInstance();
        $this->_utility_class = webphoto_lib_utility::getInstance();
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_file_read|\webphoto_item_public|\webphoto_lib_error
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

    /**
     * @param $item_row
     * @param $kind
     * @return array|bool|null
     */
    public function get_file_row($item_row, $kind)
    {
        $file_id = $this->_item_handler->build_value_fileid_by_kind($item_row, $kind);

        if (0 == $file_id) {
            $this->_error = $this->get_constant('NO_FILE');

            return false;
        }

        $file_row = $this->_fileHandler->get_extend_row_by_id($file_id);
        if (!is_array($file_row)) {
            $this->_error = $this->get_constant('NO_FILE');

            return false;
        }

        $exists = $file_row['full_path_exists'];

        if (!$exists) {
            $this->_error = $this->get_constant('NO_FILE');

            return false;
        }

        return $file_row;
    }

    // --- class end ---
}
