<?php
// $Id: item_delete.php,v 1.3 2010/11/16 23:43:38 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-11-11 K.OHWADA
// file_id_to_item_name()
// 2009-11-11 K.OHWADA
// $trust_dirname
// 2009-01-10 K.OHWADA
// webphoto_photo_delete -> webphoto_edit_item_delete
// 2008-10-01 K.OHWADA
// delete_photo_by_item_row()
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
// added delete_maillogs();
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_edit_item_delete
//=========================================================

/**
 * Class webphoto_edit_item_delete
 */
class webphoto_edit_item_delete extends webphoto_lib_error
{
    public $_item_handler;
    public $_fileHandler;
    public $_voteHandler;
    public $_p2tHandler;
    public $_maillog_handler;
    public $_mail_unlink_class;
    public $_utility_class;

    public $_MODULE_ID = 0;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_edit_item_delete constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct();

        $this->_item_handler = webphoto_item_handler::getInstance($dirname, $trust_dirname);
        $this->_fileHandler = webphoto_file_handler::getInstance($dirname, $trust_dirname);
        $this->_voteHandler = webphoto_vote_handler::getInstance($dirname, $trust_dirname);
        $this->_p2tHandler = webphoto_p2tHandler::getInstance($dirname, $trust_dirname);
        $this->_maillog_handler = webphoto_maillog_handler::getInstance($dirname, $trust_dirname);

        $this->_mail_unlink_class = webphoto_edit_mail_unlink::getInstance($dirname);
        $this->_utility_class = webphoto_lib_utility::getInstance();

        $this->_init_xoops_param();
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_edit_item_delete|\webphoto_lib_error
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
    // delete
    //---------------------------------------------------------

    /**
     * @param $item_id
     * @return bool
     */
    public function delete_photo_by_item_id($item_id)
    {
        $item_row = $this->_item_handler->get_row_by_id($item_id);

        return $this->delete_photo_by_item_row($item_row);
    }

    /**
     * @param $item_row
     * @return bool
     */
    public function delete_photo_by_item_row($item_row)
    {
        if (!is_array($item_row)) {
            return true;    // no action
        }

        $item_id = $item_row['item_id'];

        $this->delete_files_with_file($item_row);
        $this->delete_maillogs($item_id);

        $ret = $this->_item_handler->delete_by_id($item_id);
        if (!$ret) {
            $this->set_error($this->_item_handler->get_errors());
        }

        $ret = $this->_p2tHandler->delete_by_photoid($item_id);
        if (!$ret) {
            $this->set_error($this->_p2tHandler->get_errors());
        }

        $ret = $this->_voteHandler->delete_by_photoid($item_id);
        if (!$ret) {
            $this->set_error($this->_voteHandler->get_errors());
        }

        xoops_comment_delete($this->_MODULE_ID, $item_id);
        xoops_notification_deletebyitem($this->_MODULE_ID, 'photo', $item_id);

        return $this->return_code();
    }

    /**
     * @param $item_row
     * @return mixed
     */
    public function delete_files_with_file($item_row)
    {
        $item_id = $item_row['item_id'];

        // unlink files
        for ($i = 1; $i <= _C_WEBPHOTO_MAX_ITEM_FILE_ID; ++$i) {
            $name = $this->_item_handler->file_id_to_item_name($i);
            $file_id = $item_row[$name];
            if ($file_id > 0) {
                $file_path = $this->unlink_file_by_id($file_id);
            }
        }

        $ret = $this->_fileHandler->delete_by_itemid($item_id);
        if (!$ret) {
            $this->set_error($this->_fileHandler->get_errors());
        }

        return $ret;
    }

    /**
     * @param $photo_id
     * @return bool
     */
    public function delete_maillogs($photo_id)
    {
        $maillog_rows = $this->_maillog_handler->get_rows_by_photoid($photo_id);
        if (!is_array($maillog_rows)) {
            return true;    // no action
        }

        foreach ($maillog_rows as $maillog_row) {
            $this->delete_maillog_single($photo_id, $maillog_row);
        }
    }

    /**
     * @param $photo_id
     * @param $maillog_row
     * @return mixed|void
     */
    public function delete_maillog_single($photo_id, $maillog_row)
    {
        $photo_id_array = $this->_maillog_handler->build_photo_ids_row_to_array($maillog_row);
        if (is_array($photo_id_array) && (count($photo_id_array) > 1)) {
            return $this->remove_maillog_photoid($photo_id, $photo_id_array, $maillog_row);
        }

        return $this->delete_maillog_with_file($maillog_row);
    }

    /**
     * @param $photo_id
     * @param $photo_id_array
     * @param $maillog_row
     * @return mixed
     */
    public function remove_maillog_photoid($photo_id, $photo_id_array, $maillog_row)
    {
        $arr = [];
        foreach ($photo_id_array as $id) {
            if ($id != $photo_id) {
                $arr[] = $id;
            }
        }

        $row_update = $maillog_row;
        $row_update['maillo_photo_ids'] = $this->_maillog_handler->build_photo_ids_array_to_str($arr);

        $ret = $this->_maillog_handler->update($row_update);
        if (!$ret) {
            $this->set_error($this->_maillog_handler->get_errors());
        }

        return $ret;
    }

    /**
     * @param $maillog_row
     * @return mixed
     */
    public function delete_maillog_with_file($maillog_row)
    {
        $this->_mail_unlink_class->unlink_by_maillog_row($maillog_row);

        $ret = $this->_maillog_handler->delete($maillog_row);
        if (!$ret) {
            $this->set_error($this->_maillog_handler->get_errors());
        }

        return $ret;
    }

    /**
     * @param $file_id
     */
    public function unlink_file_by_id($file_id)
    {
        $file = $this->_fileHandler->get_full_path_by_id($file_id);
        $this->_utility_class->unlink_file($file);
    }

    //---------------------------------------------------------
    // xoops param
    //---------------------------------------------------------
    public function _init_xoops_param()
    {
        global $xoopsModule;
        if (is_object($xoopsModule)) {
            $this->_MODULE_ID = $xoopsModule->mid();
        }
    }

    // --- class end ---
}
