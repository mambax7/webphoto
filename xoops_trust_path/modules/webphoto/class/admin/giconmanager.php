<?php
// $Id: giconmanager.php,v 1.9 2010/11/16 23:43:38 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-11-11 K.OHWADA
// build_file_full_path()
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_gicon_handler
// 2009-01-10 K.OHWADA
// webphoto_edit_gicon_create
// 2008-11-08 K.OHWADA
// _rename_image() -> _resize_image()
// _C_WEBPHOTO_UPLOAD_FIELD_GICON
// 2008-10-01 K.OHWADA
// move $_GICONS_PATH to webphoto_base_this
// BUG: not delete gicon
// 2008-07-01 K.OHWADA
// used get_my_allowed_mimes()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_admin_giconmanager
//=========================================================

/**
 * Class webphoto_admin_giconmanager
 */
class webphoto_admin_giconmanager extends webphoto_edit_base
{
    public $_gicon_handler;
    public $_upload_class;
    public $_mime_class;
    public $_gicon_create_class;

    public $_post_gicon_id;
    public $_post_delgicon;
    public $_tmp_name;
    public $_media_name;

    public $_THIS_FCT = 'giconmanager';
    public $_THIS_URL;

    public $_ERR_ALLOW_EXTS = null;

    public $_IMAGE_FIELD_NAME = _C_WEBPHOTO_UPLOAD_FIELD_GICON;
    public $_SHADOW_FIELD_NAME = _C_WEBPHOTO_UPLOAD_FIELD_GSHADOW;

    public $_TIME_SUCCESS = 1;
    public $_TIME_FAIL = 5;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_admin_giconmanager constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_gicon_handler = webphoto_gicon_handler::getInstance($dirname, $trust_dirname);
        $this->_upload_class = webphoto_upload::getInstance($dirname, $trust_dirname);
        $this->_mime_class = webphoto_mime::getInstance($dirname, $trust_dirname);
        $this->_gicon_create_class = webphoto_edit_gicon_create::getInstance($dirname, $trust_dirname);

        $this->_ERR_ALLOW_EXTS = 'allowed file type is ' . implode(',', $this->get_normal_exts());

        $this->_THIS_URL = $this->_MODULE_URL . '/admin/index.php?fct=' . $this->_THIS_FCT;
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_admin_giconmanager|\webphoto_lib_error
     */
    public static function getInstance($dirname = null, $trust_dirname = null)
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new self($dirname, $trust_dirname);
        }

        return $instance;
    }

    //---------------------------------------------------------
    // main
    //---------------------------------------------------------
    public function main()
    {
        $this->_check();

        switch ($this->_get_action()) {
            case 'insert':
                $this->_insert();
                exit();

            case 'update':
                $this->_update();
                exit();

            case 'delete':
                $this->_delete();
                exit();

            default:
                break;
        }

        xoops_cp_header();

        echo $this->build_admin_menu();
        echo $this->build_admin_title('GICONMANAGER');

        switch ($this->_get_disp()) {
            case 'edit_form':
                $this->_print_edit_form();
                break;
            case 'new_form':
                $this->_print_new_form();
                break;
            case 'list':
            default:
                $this->_print_list();
                break;
        }

        xoops_cp_footer();
        exit();
    }

    /**
     * @return string
     */
    public function _get_action()
    {
        $this->_post_gicon_id = $this->_post_class->get_post_get_int('gicon_id');
        $this->_post_delgicon = $this->_post_class->get_post_int('delgicon');
        $post_action = $this->_post_class->get_post_text('action');

        if ('insert' == $post_action) {
            return 'insert';
        } elseif (('update' == $post_action) && ($this->_post_gicon_id > 0)) {
            return 'update';
        } elseif ($this->_post_delgicon > 0) {
            return 'delete';
        }

        return 'list';
    }

    /**
     * @return string
     */
    public function _get_disp()
    {
        $get_disp = $this->_post_class->get_get_text('disp');

        if (('edit' == $get_disp) && ($this->_post_gicon_id > 0)) {
            return 'edit_form';
        } elseif ('new' == $get_disp) {
            return 'new_form';
        }

        return 'list';
    }

    //---------------------------------------------------------
    // check
    //---------------------------------------------------------
    public function _check()
    {
        $ret = $this->_exec_check();
        switch ($ret) {
            case _C_WEBPHOTO_ERR_CHECK_DIR:
                redirect_header($this->_ADMIN_INDEX_PHP, $this->_TIME_FAIL, $this->get_format_error());
                exit();

            case 0:
            default:
                break;
        }
    }

    /**
     * @return int
     */
    public function _exec_check()
    {
        $ret1 = $this->check_dir($this->_TMP_DIR);
        if ($ret1 < 0) {
            return $ret1;
        }

        $ret2 = $this->check_dir($this->_GICONS_DIR);
        if ($ret2 < 0) {
            return $ret2;
        }

        return 0;
    }

    //---------------------------------------------------------
    // insert
    //---------------------------------------------------------
    public function _insert()
    {
        if (!$this->check_token()) {
            redirect_header($this->_THIS_URL, $this->_TIME_FAIL, $this->get_token_errors());
            exit();
        }

        $ret = $this->_excute_insert();
        switch ($ret) {
            case _C_WEBPHOTO_ERR_DB:
                $msg = 'DB error <br>';
                $msg .= $this->get_format_error();
                redirect_header($this->_THIS_URL, $this->_TIME_FAIL, $msg);
                exit();

            case _C_WEBPHOTO_ERR_UPLOAD:
                $msg = 'File Upload Error';
                $msg .= '<br>' . $this->get_format_error(false);
                redirect_header($this->_THIS_URL, $this->_TIME_FAIL, $msg);
                exit();

            case _C_WEBPHOTO_ERR_FILEREAD:
                redirect_header($this->_THIS_URL, $this->_TIME_FAIL, _WEBPHOTO_ERR_FILEREAD);
                exit();

            case _C_WEBPHOTO_ERR_NO_IMAGE:
                redirect_header($this->_THIS_URL, $this->_TIME_FAIL, _WEBPHOTO_ERR_NOIMAGESPECIFIED);
                exit();

            case _C_WEBPHOTO_ERR_NOT_ALLOWED_EXT:
                redirect_header($this->_THIS_URL, $this->_TIME_FAIL, $this->_ERR_ALLOW_EXTS);
                exit();

            default:
                break;
        }

        redirect_header($this->_THIS_URL, $this->_TIME_SUCCESS, _WEBPHOTO_DBUPDATED);
        exit();
    }

    /**
     * @return bool|int|void
     */
    public function _excute_insert()
    {
        $shadow_tmp_name = null;

        $ret1 = $this->_fetch_image(false);
        if ($ret1 < 0) {
            return $ret1;
        } elseif (0 == $ret1) {
            return _C_WEBPHOTO_ERR_NO_IMAGE;
        }

        $image_tmp_name = $this->_get_tmp_name();
        $this->_media_name = $this->_get_media_name();

        // check image tmp name
        if (empty($image_tmp_name)) {
            return _C_WEBPHOTO_ERR_NO_IMAGE;
        }

        $ret2 = $this->_fetch_shadow();
        if ($ret2 < 0) {
            return $ret2;
        } elseif (1 == $ret2) {
            $shadow_tmp_name = $this->_get_tmp_name();
        }

        $row = $this->_gicon_handler->create();
        $newid = $this->_gicon_handler->insert($row);
        if (!$newid) {
            return $newid;
        }

        $row['gicon_id'] = $newid;
        $row['gicon_time_create'] = time();

        $ret4 = $this->_update_common($row, $image_tmp_name, $shadow_tmp_name);
        if (!$ret4) {
            return $ret4;
        }

        return 0;
    }

    /**
     * @param bool $allow_noimage
     * @return int
     */
    public function _fetch_image($allow_noimage = false)
    {
        return $this->_fetch_common($this->_IMAGE_FIELD_NAME, $allow_noimage);
    }

    /**
     * @return int
     */
    public function _fetch_shadow()
    {
        return $this->_fetch_common($this->_SHADOW_FIELD_NAME, false);
    }

    /**
     * @param $field
     * @param $allow_noimage
     * @return int
     */
    public function _fetch_common($field, $allow_noimage)
    {
        $ret = $this->_upload_class->fetch_image($field);
        $tmp_name = $this->_upload_class->get_uploader_file_name();

        if ($ret < 0) {
            if ($tmp_name) {
                $this->unlink_file($this->_TMP_DIR . '/' . $tmp_name);
            }
            $this->set_error($this->_upload_class->get_errors());
        }

        if (empty($tmp_name) && !$allow_noimage) {
            return 0;   // no image
        }

        return $ret;
    }

    public function _get_tmp_name()
    {
        return $this->_upload_class->get_tmp_name();
    }

    public function _get_media_name()
    {
        return $this->_upload_class->get_uploader_media_name();
    }

    /**
     * @param $row
     * @param $image_tmp_name
     * @param $shadow_tmp_name
     * @return int
     */
    public function _update_common($row, $image_tmp_name, $shadow_tmp_name)
    {
        $gicon_id = $row['gicon_id'];

        $title = $this->_post_class->get_post_text('gicon_title');

        // create image if upload
        if ($image_tmp_name) {
            $row = $this->_gicon_create_class->create_main_row($row, $image_tmp_name);

            if (empty($title)) {
                $title = $this->_media_name;
            }
        }

        // create shadow if upload
        if ($shadow_tmp_name) {
            $row = $this->_gicon_create_class->create_shadow_row($row, $shadow_tmp_name);
        }

        if ($title) {
            $row['gicon_title'] = $title;
        }

        $row['gicon_time_update'] = time();

        $ret3 = $this->_gicon_handler->update($row);
        if (!$ret3) {
            $this->set_error($this->_gicon_handler->get_errors());

            return _C_WEBPHOTO_ERR_DB;
        }

        return 0;
    }

    //---------------------------------------------------------
    // update
    //---------------------------------------------------------
    public function _update()
    {
        if (!$this->check_token()) {
            redirect_header($this->_THIS_URL, $this->_TIME_FAIL, $this->get_token_errors());
            exit();
        }

        $ret = $this->_excute_update();
        switch ($ret) {
            case _C_WEBPHOTO_ERR_NO_RECORD:
                redirect_header($this->_THIS_URL, $this->_TIME_FAIL, _AM_WEBPHOTO_ERR_NO_RECORD);
                exit();

            case _C_WEBPHOTO_ERR_DB:
                $msg = 'DB error <br>';
                $msg .= $this->get_format_error();
                redirect_header($this->_THIS_URL, $this->_TIME_FAIL, $msg);
                exit();

            case _C_WEBPHOTO_ERR_UPLOAD:
                $msg = 'File Upload Error';
                $msg .= '<br>' . $this->get_format_error(false);
                redirect_header($this->_THIS_URL, $this->_TIME_FAIL, $msg);
                exit();

            case _C_WEBPHOTO_ERR_FILEREAD:
                redirect_header($this->_THIS_URL, $this->_TIME_FAIL, _WEBPHOTO_ERR_FILEREAD);
                exit();

            case _C_WEBPHOTO_ERR_NOT_ALLOWED_EXT:
                redirect_header($this->_THIS_URL, $this->_TIME_FAIL, $this->_ERR_ALLOW_EXTS);
                exit();

            default:
                break;
        }

        redirect_header($this->_THIS_URL, $this->_TIME_SUCCESS, _WEBPHOTO_DBUPDATED);
        exit();
    }

    /**
     * @return int
     */
    public function _excute_update()
    {
        $image_tmp_name = null;
        $shadow_tmp_name = null;

        $post_shadow_del = $this->_post_class->get_post_int('shadow_del');

        $row = $this->_gicon_handler->get_row_by_id($this->_post_gicon_id);
        if (!is_array($row)) {
            return _C_WEBPHOTO_ERR_NO_RECORD;
        }

        // set by post
        $row['gicon_anchor_x'] = $this->_post_class->get_post_int('gicon_anchor_x');
        $row['gicon_anchor_y'] = $this->_post_class->get_post_int('gicon_anchor_y');
        $row['gicon_info_x'] = $this->_post_class->get_post_int('gicon_info_x');
        $row['gicon_info_y'] = $this->_post_class->get_post_int('gicon_info_y');

        $ret1 = $this->_fetch_image(true);
        if ($ret1 < 0) {
            return $ret1;
        } elseif (1 == $ret1) {
            $image_tmp_name = $this->_get_tmp_name();
        }

        $ret2 = $this->_fetch_shadow();
        if ($ret2 < 0) {
            return $ret2;
        } elseif (1 == $ret2) {
            $shadow_tmp_name = $this->_get_tmp_name();
        }

        //delete old files
        if ($post_shadow_del || $shadow_tmp_name) {
            // default icons have no name value
            if ($row['gicon_shadow_path'] && $row['gicon_shadow_name']) {
                $file = $this->build_file_full_path($row['gicon_shadow_path']);
                $this->unlink_file($file);
                $row['gicon_shadow_path'] = '';
                $row['gicon_shadow_name'] = '';
                $row['gicon_shadow_ext'] = '';
                $row['gicon_shadow_width'] = 0;
                $row['gicon_shadow_height'] = 0;
            }
        }

        $ret4 = $this->_update_common($row, $image_tmp_name, $shadow_tmp_name);
        if (!$ret4) {
            return $ret4;
        }

        return 0;
    }

    //---------------------------------------------------------
    // delete
    //---------------------------------------------------------
    public function _delete()
    {
        $gicon_id = $this->_post_delgicon;

        if (!$this->check_token()) {
            redirect_header($this->_THIS_URL, $this->_TIME_FAIL, $this->get_token_errors());
            exit();
        }

        $row = $this->_gicon_handler->get_row_by_id($gicon_id);
        if (!is_array($row)) {
            redirect_header($this->_THIS_URL, $this->_TIME_FAIL, _AM_WEBPHOTO_ERR_NO_RECORD);
            exit();
        }

        // delete image files
        // default icons have no name value
        if ($row['gicon_image_path'] && $row['gicon_image_name']) {
            $file = $this->build_file_full_path($row['gicon_image_path']);
            $this->unlink_file($file);
        }
        if ($row['gicon_shadow_path'] && $row['gicon_shadow_name']) {
            $file = $this->build_file_full_path($row['gicon_shadow_path']);
            $this->unlink_file($file);
        }

        $ret1 = $this->_catHandler->clear_gicon_id($gicon_id);
        if (!$ret1) {
            $this->set_error($this->_catHandler->get_errors());
        }

        $ret2 = $this->_item_handler->clear_gicon_id($gicon_id);
        if (!$ret2) {
            $this->set_error($this->_item_handler->get_errors());
        }

        $ret3 = $this->_gicon_handler->delete_by_id($gicon_id);
        if (!$ret3) {
            $this->set_error($this->_gicon_handler->get_errors());
        }

        if (!$this->return_code()) {
            $msg = 'DB error <br>';
            $msg .= $this->get_format_error();
            redirect_header($this->_THIS_URL, $this->_TIME_FAIL, $msg);
            exit();
        }

        redirect_header($this->_THIS_URL, $this->_TIME_SUCCESS, _WEBPHOTO_DBUPDATED);
        exit();
    }

    //---------------------------------------------------------
    // form
    //---------------------------------------------------------
    public function _print_edit_form()
    {
        $row = $this->_gicon_handler->get_row_by_id($this->_post_gicon_id);
        if (!is_array($row)) {
            redirect_header($this->_THIS_URL, $this->_TIME_FAIL, _AM_WEBPHOTO_ERR_NO_RECORD);
        }

        $this->_print_gicon_form('edit', $row);
    }

    public function _print_new_form()
    {
        $row = $this->_gicon_handler->create();

        $this->_print_gicon_form('new', $row);
    }

    //---------------------------------------------------------
    // list
    //---------------------------------------------------------
    public function _print_list()
    {
        echo '<p><a href="' . $this->_THIS_URL . '&amp;disp=new">';
        echo _AM_WEBPHOTO_GICON_ADD;
        echo '</a></p>' . "\n";

        $rows = $this->_gicon_handler->get_rows_all_asc();

        $this->_print_gicon_list($rows);
    }

    //---------------------------------------------------------
    // admin_gicon_form
    //---------------------------------------------------------

    /**
     * @param $mode
     * @param $row
     */
    public function _print_gicon_form($mode, $row)
    {
        $form = webphoto_admin_gicon_form::getInstance($this->_DIRNAME, $this->_TRUST_DIRNAME);
        $form->print_form($mode, $row);
    }

    /**
     * @param $rows
     */
    public function _print_gicon_list($rows)
    {
        $form = webphoto_admin_gicon_form::getInstance($this->_DIRNAME, $this->_TRUST_DIRNAME);
        $form->print_list($rows);
    }

    // --- class end ---
}
