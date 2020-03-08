<?php
// $Id: submit.php,v 1.26 2011/05/10 02:56:39 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-04 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-05-01 K.OHWADA
// create_cache()
// 2010-10-01 K.OHWADA
// create_sub_files()
// 2010-06-06 K.OHWADA
// merge_tag_name_array( $val )
// 2010-03-18 K.OHWADA
// format_and_insert_item()
// 2010-02-15 K.OHWADA
// check_edit()
// 2010-01-10 K.OHWADA
// webphoto_tag -> webphoto_tag_build
// 2009-12-06 K.OHWADA
// notify_waiting()
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_show_image
// item_editor_default
// 2009-10-25 K.OHWADA
// create_jpeg_param()
// 2009-05-30 K.OHWADA
// show_photo_desc -> show_photo_summary
// 2009-05-05 K.OHWADA
// init_form()
// 2009-04-10 K.OHWADA
// webphoto_page
// insert_media_file_small()
// 2009-03-15 K.OHWADA
// create_small_param_by_photo()
// 2009-01-25 K.OHWADA
// create_swf_param()
// 2009-01-10 K.OHWADA
// webphoto_photo_submit -> webphoto_edit_submit
// webphoto_edit_external_build etc
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_edit_submit
//=========================================================

/**
 * Class webphoto_edit_submit
 */
class webphoto_edit_submit extends webphoto_edit_imagemanager_submit
{
    public $_page_class;
    public $_editor_class;
    public $_tag_build_class;
    public $_show_image_class;
    public $_external_build_class;
    public $_embed_build_class;
    public $_playlist_build_class;
    public $_photo_form_class;
    public $_misc_form_class;
    public $_mail_send_class;

    public $_cfg_addposts;
    public $_cfg_makethumb;
    public $_cfg_thumb_width;
    public $_cfg_thumb_height;
    public $_cfg_allownoimage;
    public $_cfg_file_dir;
    public $_cfg_file_size;

    public $_ini_file_jpeg;

    // post
    public $_post_form_embed = 0;
    public $_post_form_playlist = 0;
    public $_post_form_editor = 0;
    public $_checkbox_array = [];
    public $_form_action = null;
    public $_post_rotate = null;

    public $_is_video_thumb_form = false;

    public $_row_current = null;
    public $_row_update = null;
    public $_row_fetch = null;

    public $_URL_DAFAULT_IMAGE;
    public $_URL_PREVIEW_IMAGE;
    public $_URL_PIXEL_IMAGE;
    public $_PREVIEW_IMAGE_WIDTH = 64;
    public $_PREVIEW_IMAGE_HEIGHT = 64;

    public $_THIS_FCT = null;
    public $_THIS_URL = null;
    public $_FROM_ACTION = null;
    public $_FORM_MODE = null;
    public $_FLAG_ADMIN = false;

    public $_MAX_PHOTO_FILE = _C_WEBPHOTO_MAX_PHOTO_FILE;

    public $_FILE_LIST;
    public $_FILE_KIND_NAME_LIST;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_edit_submit constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_page_class = webphoto_page::getInstance($dirname, $trust_dirname);
        $this->_show_image_class = webphoto_show_image::getInstance($dirname, $trust_dirname);
        $this->_embed_build_class = webphoto_edit_embed_build::getInstance($dirname, $trust_dirname);
        $this->_editor_class = webphoto_editor::getInstance($dirname, $trust_dirname);
        $this->_photo_form_class = webphoto_edit_photo_form::getInstance($dirname, $trust_dirname);
        $this->_misc_form_class = webphoto_edit_misc_form::getInstance($dirname, $trust_dirname);
        $this->_playlist_build_class = webphoto_edit_playlist_build::getInstance($dirname, $trust_dirname);
        $this->_external_build_class = webphoto_edit_external_build::getInstance($dirname, $trust_dirname);
        $this->_tag_build_class = webphoto_tag_build::getInstance($dirname, $trust_dirname);
        $this->_mail_send_class = webphoto_mail_send::getInstance($dirname, $trust_dirname);

        $this->_tag_build_class->set_is_japanese($this->_is_japanese);

        $this->_cfg_addposts = $this->get_config_by_name('addposts');
        $this->_cfg_makethumb = $this->get_config_by_name('makethumb');
        $this->_cfg_thumb_width = $this->get_config_by_name('thumb_width');
        $this->_cfg_thumb_height = $this->get_config_by_name('thumb_height');
        $this->_cfg_allownoimage = $this->get_config_by_name('allownoimage');
        $this->_cfg_file_dir = $this->get_config_by_name('file_dir');
        $this->_cfg_file_size = $this->get_config_by_name('file_size');

        $this->_ini_file_jpeg = $this->check_show('file_jpeg');

        $this->_URL_DAFAULT_IMAGE = $this->_MODULE_URL . '/images/exts/default.png';
        $this->_URL_PREVIEW_IMAGE = $this->_MODULE_URL . '/images/exts/preview.png';
        $this->_URL_PIXEL_IMAGE = $this->_MODULE_URL . '/images/icons/pixel_trans.png';

        $this->_FLAG_FETCH_ALLOW_ALL = true;

        $this->_FILE_LIST = explode('|', _C_WEBPHOTO_FILE_LIST);

        $this->_FILE_KIND_NAME_LIST = explode('|', _C_WEBPHOTO_FILE_KIND_NAME_LIST);
    }

    // for admin_photo_manage admin_catmanager

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_edit_imagemanager_submit|\webphoto_edit_submit|\webphoto_lib_error
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
    // post param
    //---------------------------------------------------------
    public function get_post_param()
    {
        $this->_post_item_id = $this->get_post_item_id();

        $this->set_preview_name($this->get_post_text('preview_name'));
        $this->set_rotate($this->get_post_text('rotate'));

        $this->set_checkbox_by_post('item_time_update_checkbox');
        $this->set_checkbox_by_post('item_datetime_checkbox');

        $this->set_tag_name_array($this->_tag_build_class->str_to_tag_name_array($this->get_post_text('tags')));
    }

    //---------------------------------------------------------
    // creta itme row
    //---------------------------------------------------------

    /**
     * @return array|void
     */
    public function create_item_row_default()
    {
        $item_kind = $this->get_post_int('item_kind');
        $item_embed_type = $this->get_post_text('item_embed_type');
        $item_playlist_type = $this->get_post_int('item_playlist_type');

        $row = $this->_item_create_class->create(true);
        $row['item_cat_id'] = $this->get_post_cat_id();
        $row['item_editor'] = $this->get_post_text('item_editor');
        $row['item_embed_type'] = $item_embed_type;
        $row['item_playlist_type'] = $item_playlist_type;
        $row['item_uid'] = $this->_xoops_uid;

        // datetime
        $row['item_datetime'] = $this->get_mysql_date_today();
        $this->set_checkbox_by_name('item_datetime_checkbox', _C_WEBPHOTO_NO);

        // description option
        $options = $this->_editor_class->display_options($row['item_editor']);

        if (is_array($options)) {
            $row['item_description_smiley'] = $options['smiley'];
            $row['item_description_xcode'] = $options['xcode'];
            $row['item_description_image'] = $options['image'];
            $row['item_description_br'] = $options['br'];

            if ($this->_has_html) {
                $row['item_description_html'] = $options['html'];
            }
        }

        if ($item_embed_type) {
            $row['item_kind'] = _C_WEBPHOTO_ITEM_KIND_EMBED;
        } elseif ($this->_FLAG_ADMIN && $item_playlist_type) {
            $row['item_kind'] = $item_kind;
        }

        if (empty($row['item_editor'])) {
            $row['item_editor'] = $this->get_ini('item_editor_default');
        }

        return $row;
    }

    /**
     * @return array|mixed|void
     */
    public function create_item_row_preview()
    {
        $row = $this->create_item_row_by_post();

        $row['item_uid'] = $this->_xoops_uid;

        // datetime
        if ($row['item_datetime']) {
            $this->set_checkbox_by_name('item_datetime_checkbox', _C_WEBPHOTO_YES);
        } else {
            $row['item_datetime'] = $this->get_mysql_date_today();
        }

        if (empty($row['item_editor'])) {
            $row['item_editor'] = $this->get_ini('item_editor_default');
        }

        return $row;
    }

    /**
     * @return array|mixed|void
     */
    public function create_item_row_by_post()
    {
        $checkbox = $this->get_checkbox_by_name('item_datetime_checkbox');
        $row = $this->_item_create_class->create(true);
        $row['item_cat_id'] = $this->get_post_cat_id();
        $row = $this->build_row_submit_by_post($row, $checkbox);

        return $row;
    }

    //---------------------------------------------------------
    // submit
    //---------------------------------------------------------

    /**
     * @return bool|int
     */
    public function submit_exec()
    {
        $this->clear_msg_array();

        $item_row = $this->create_item_row_by_post();

        $ret = $this->submit_exec_check($item_row);
        if ($ret < 0) {
            return $ret;
        }

        $ret = $this->submit_exec_fetch($item_row);
        if ($ret < 0) {
            return $ret;
        }

        $item_row = $this->_row_fetch;
        $photo_name = $this->_photo_tmp_name;
        $jpeg_name = $this->_jpeg_tmp_name;

        // --- insert item ---
        $item_row = $this->build_item_row_submit_insert($item_row);
        $item_id = $this->format_and_insert_item($item_row);
        if (!$item_id) {
            return _C_WEBPHOTO_ERR_DB;
        }

        $item_row['item_id'] = $item_id;
        $this->set_created_row($item_row);

        // uploaded photo
        if ($photo_name || $jpeg_name) {
            // --- insert files
            $ret = $this->insert_media_files($item_row);
            if ($ret < 0) {
                return $ret;
            }

            // --- update item ---
            $item_row = $this->build_item_row_submit_update($item_row);
            $ret = $this->format_and_update_item($item_row);
            if (!$ret) {
                return _C_WEBPHOTO_ERR_DB;
            }
            $this->set_created_row($item_row);

            $this->unlink_uploaded_files();
        }

        // small image if empty
        if (empty($item_row[_C_WEBPHOTO_ITEM_FILE_SMALL])) {
            // --- insert files
            $newid = $this->insert_media_file_small($item_row);
            if ($newid > 0) {
                // --- update item ---
                $item_row[_C_WEBPHOTO_ITEM_FILE_SMALL] = $newid;
                $this->format_and_update_item($item_row);
                $this->set_created_row($item_row);
            }
        }

        // playlist
        if ($this->_FLAG_ADMIN) {
            $ret = $this->_playlist_build_class->create_cache($item_row);
            if ($ret < 0) {
                return $ret;
            }
            if (0 == $ret) {
                // --- update item ---
                $item_row = $this->_playlist_build_class->get_item_row();
                $this->format_and_update_item($item_row);
                $this->set_created_row($item_row);
            }
        }

        $this->set_factory_error();
        $this->submit_exec_tag_save($item_row);
        $this->submit_exec_post_count();
        $this->submit_exec_notify($item_row);
    }

    /**
     * @param $row
     * @return int
     */
    public function submit_exec_fetch($row)
    {
        $this->_row_fetch = $row;

        $ret = $this->_external_build_class->build($row);
        if ($ret <= 0) {
            $this->_row_fetch = $this->_external_build_class->get_item_row();

            return $ret;
        }

        $ret = $this->_embed_build_class->build($row);
        if ($ret <= 0) {
            $this->_row_fetch = $this->_embed_build_class->get_item_row();
            $this->merge_tag_name_array($this->_embed_build_class->get_tags());

            return $ret;
        }

        // playlist
        if ($this->_FLAG_ADMIN) {
            $ret = $this->_playlist_build_class->build_row($row);
            if ($ret <= 0) {
                $this->_row_fetch = $this->_playlist_build_class->get_item_row();

                return $ret;
            }
        }

        // Check if upload file name specified
        if (!$this->check_xoops_upload_file($this->_ini_file_jpeg)) {
            return _C_WEBPHOTO_ERR_NO_SPECIFIED;
        }

        // fetch photo
        $ret = $this->submit_exec_fetch_photo($row);
        if ($ret < 0) {
            return $ret;    // failed
        }

        // fetch jpeg
        if ($this->_ini_file_jpeg) {
            $this->upload_fetch_jpeg();
        }

        // upload
        if ($this->_photo_tmp_name) {
            return 0;
        }

        $row = $this->_row_fetch;

        // check title
        if (empty($row['item_title'])) {
            return _C_WEBPHOTO_ERR_NO_TITLE;
        }

        // check allow no image mode
        if ($this->_cfg_allownoimage) {
            $row['item_kind'] = _C_WEBPHOTO_ITEM_KIND_NONE;
            $this->_row_fetch = $row;

            return 0;
        }

        return _C_WEBPHOTO_ERR_NO_IMAGE;
    }

    /**
     * @param $item_row
     * @return int
     */
    public function submit_exec_tag_save($item_row)
    {
        $ret = $this->_tag_build_class->add_tags($item_row['item_id'], $this->_xoops_uid, $this->get_tag_name_array());
        if (!$ret) {
            return _C_WEBPHOTO_ERR_DB;
        }

        return 0;
    }

    public function submit_exec_post_count()
    {
        $xoops_user_class = webphoto_xoops_user::getInstance();
        $xoops_user_class->increment_post_by_num_own($this->_cfg_addposts);
    }

    /**
     * @param $item_row
     */
    public function submit_exec_notify($item_row)
    {
        if ($this->_has_superinsert) {
            $this->notify_new_photo($item_row);
        } else {
            if ($this->get_ini('submit_notify_waiting_by_mail')) {
                $this->mail_waiting($item_row);
            } else {
                $this->notify_waiting($item_row);
            }
        }
    }

    /**
     * @param $item_row
     */
    public function notify_new_photo($item_row)
    {
        $notification_class = webphoto_notification_event::getInstance($this->_DIRNAME, $this->_TRUST_DIRNAME);
        $notification_class->notify_new_photo($item_row['item_id'], $item_row['item_cat_id'], $item_row['item_title']);
    }

    /**
     * @param $item_row
     */
    public function notify_waiting($item_row)
    {
        $notification_class = webphoto_notification_event::getInstance($this->_DIRNAME, $this->_TRUST_DIRNAME);
        $notification_class->notify_waiting($item_row['item_id'], $item_row['item_title']);
    }

    //---------------------------------------------------------
    // mail send
    //---------------------------------------------------------

    /**
     * @param $item_row
     */
    public function mail_waiting($item_row)
    {
        return $this->_mail_send_class->send_waiting($item_row);
    }

    /**
     * @param $item_row
     * @return bool
     */
    public function mail_approve($item_row)
    {
        return $this->_mail_send_class->send_approve($item_row);
    }

    /**
     * @param $item_row
     * @return bool
     */
    public function mail_refuse($item_row)
    {
        return $this->_mail_send_class->send_refuse($item_row);
    }

    //---------------------------------------------------------
    // media files
    //---------------------------------------------------------

    /**
     * @param $item_row
     * @return int
     */
    public function insert_media_files($item_row)
    {
        $ret = $this->create_media_file_params($item_row);
        if ($ret < 0) {
            return $ret;
        }

        // --- insert file ---
        $this->_file_id_array = $this->insert_media_files_from_params($item_row);

        return 0;
    }

    /**
     * @param      $item_row
     * @param bool $is_submit
     * @return int
     */
    public function create_media_file_params($item_row, $is_submit = true)
    {
        $photo_name = $this->_photo_tmp_name;
        $jpeg_name = $this->_jpeg_tmp_name;

        $item_id = $item_row['item_id'];
        $item_kind = $item_row['item_kind'];
        $item_ext = $item_row['item_ext'];

        $photo_param = $item_row;

        $file_params = [];
        $cont_param = null;
        $middle_thumb_param = null;

        // set msg param
        $this->init_photo_create();

        // -- photo tmp
        $photo_param = $this->build_photo_param($item_row);

        // -- cont
        if ($photo_name) {
            list($ret, $cont_param) = $this->create_cont_param($photo_param);
            if ($ret < 0) {
                return $ret;
            }
            $file_params['cont'] = $cont_param;
        }

        // -- flash, pdf, video images
        if (is_array($cont_param)) {
            $sub_params = $this->create_sub_files($photo_param, $cont_param);
            if (is_array($sub_params)) {
                $file_params += $sub_params;
            }
        }

        // --- jpeg ---
        if ($jpeg_name) {
            $file_params['jpeg'] = $this->create_jpeg_param_by_tmp($item_row, $jpeg_name);
        } elseif (is_array($cont_param)) {
            $file_params['jpeg'] = $this->create_jpeg_param_by_photo($photo_param, $file_params);
        }

        // --- thumb, etc ---
        if (is_array($cont_param) || $jpeg_name) {
            $image_params = $this->create_image_params_by_photo($photo_param, $file_params);
            if (is_array($image_params)) {
                $file_params += $image_params;
            }
        }

        $this->_media_file_params = $file_params;

        return 0;
    }

    /**
     * @param $item_row
     * @return int
     */
    public function create_files_params($item_row)
    {
        $file_name_array = $this->_file_tmp_name_array;
        $file_media_array = $this->_file_media_type_array;

        $item_id = $item_row['item_id'];
        $item_kind = $item_row['item_kind'];
        $item_ext = $item_row['item_ext'];

        $file_params = $this->_media_file_params;

        // set msg param
        $this->init_photo_create();

        // -- photo tmp
        $param = $item_row;

        for ($i = 1; $i <= _C_WEBPHOTO_MAX_ITEM_FILE_ID; ++$i) {
            $name = 'file_' . $i;
            $file_name = isset($file_name_array[$name]) ? $file_name_array[$name] : null;
            $file_media = isset($file_media_array[$name]) ? $file_media_array[$name] : null;

            if ($file_name) {
                $src_file = $this->_TMP_DIR . '/' . $file_name;
                $param['src_file'] = $src_file;
                $param['src_ext'] = $this->parse_ext($src_file);
                $param['src_mime'] = $file_media;
                $param['src_kind'] = $i;

                $param_out = $this->_factory_create_class->create_single_copy_param($param);
                if (is_array($param_out)) {
                    $name = $this->_FILE_KIND_NAME_LIST[$i];
                    $file_params[$name] = $param_out;
                }
            }
        }

        $this->_media_file_params = $file_params;

        return 0;
    }

    /**
     * @param $rotate
     * @return int
     */
    public function conv_rotate($rotate)
    {
        $rot = 0;
        switch ($rotate) {
            case 'rot270':
                $rot = 270;
                break;
            case 'rot180':
                $rot = 180;
                break;
            case 'rot90':
                $rot = 90;
                break;
            case 'rot0':
            default:
                break;
        }

        return $rot;
    }

    /**
     * @param      $src_name
     * @param      $rotate
     * @param bool $flag_rename
     * @return mixed
     */
    public function rotate_tmp_image($src_name, $rotate, $flag_rename = false)
    {
        if (empty($src_name) || empty($rotate)) {
            return $src_name;   // no action
        }

        $dst_name = str_replace(_C_WEBPHOTO_UPLOADER_PREFIX, _C_WEBPHOTO_UPLOADER_PREFIX_ROT, $src_name);

        $src_file = $this->build_tmp_dir_file($src_name);
        $dst_file = $this->build_tmp_dir_file($dst_name);
        $name = $src_name;

        $this->rotate_image($src_file, $dst_file, $rotate);

        if (is_file($dst_file)) {
            if ($flag_rename) {
                unlink($src_file);
                rename($dst_file, $src_file);
            } else {
                $name = $dst_name;
            }
        }

        return $name;
    }

    //---------------------------------------------------------
    // bulk
    //---------------------------------------------------------

    /**
     * @param      $title
     * @param      $num
     * @param null $default
     * @return null|string
     */
    public function build_bulk_title($title, $num, $default = null)
    {
        $str = $default;

        // set title if specfy
        if ($title) {
            $str = $title . ' - ' . $num;
        }

        return $str;
    }

    //---------------------------------------------------------
    // preview
    //---------------------------------------------------------

    /**
     * @param $photo_name
     * @return array
     */
    public function create_preview_new($photo_name)
    {
        $preview_name = str_replace(_C_WEBPHOTO_UPLOADER_PREFIX, _C_WEBPHOTO_UPLOADER_PREFIX_PREV, $photo_name);

        $this->set_preview_name($preview_name);

        $src_file = $this->build_tmp_dir_file($photo_name);
        $dst_file = $this->build_tmp_dir_file($preview_name);
        rename($src_file, $dst_file);

        return $this->build_preview($preview_name);
    }

    /**
     * @param $preview_name
     * @return array
     */
    public function build_preview($preview_name)
    {
        $rotate_name = $this->rotate_tmp_image($preview_name, $this->_rotate_angle);

        $thumb_width = 0;
        $thumb_height = 0;
        $is_normal_image = false;

        $ext = $this->parse_ext($rotate_name);

        $path_photo = $this->build_tmp_dir_file($rotate_name);
        $media_url = $this->_MODULE_URL . '/index.php?fct=image_tmp&name=' . rawurlencode($rotate_name);
        $img_thumb_src = $media_url;

        // image type
        if ($this->is_normal_ext($ext)) {
            $is_normal_image = true;

            $size = getimagesize($path_photo);
            if (is_array($size)) {
                $photo_width = $size[0];
                $photo_height = $size[1];

                list($thumb_width, $thumb_height) = $this->_show_image_class->adjust_thumb_size($photo_width, $photo_height);
            }

            // other type
        } else {
            $img_thumb_src = $this->_URL_PREVIEW_IMAGE;
            $thumb_width = $this->_PREVIEW_IMAGE_WIDTH;
            $thumb_height = $this->_PREVIEW_IMAGE_HEIGHT;
        }

        $arr = [
            'media_url' => $this->sanitize($media_url),
            'img_thumb_src_s' => $this->sanitize($img_thumb_src),
            'img_thumb_width' => $thumb_width,
            'img_thumb_height' => $thumb_height,
            'is_normal_image' => $is_normal_image,
        ];

        return $arr;
    }

    /**
     * @return array
     */
    public function build_no_image_preview()
    {
        $arr = [
            'media_url_s' => '',
            'img_thumb_src_s' => $this->sanitize($this->_URL_PREVIEW_IMAGE),
            'img_thumb_width' => $this->_PREVIEW_IMAGE_WIDTH,
            'img_thumb_height' => $this->_PREVIEW_IMAGE_HEIGHT,
            'is_normal_image' => false,
        ];

        return $arr;
    }

    /**
     * @param $item_row
     * @return array
     */
    public function build_preview_embed($item_row)
    {
        $arr = null;

        $item_row['item_kind'] = _C_WEBPHOTO_ITEM_KIND_EMBED;
        $item_row['item_title'] = '';
        $item_row['item_external_thumb'] = '';

        $ret = $this->_embed_build_class->build($item_row);
        if ($ret > 0) {
            return [$item_row, $arr];
        }

        $row = $this->_embed_build_class->get_item_row();

        $thumb = $row['item_external_thumb'];
        if (empty($thumb)) {
            return [$item_row, $arr];
        }

        $arr = [
            'media_url' => '',
            'img_thumb_src_s' => $this->sanitize($thumb),
            'img_thumb_width' => $this->_cfg_thumb_width,
            'img_thumb_height' => 0,
            'is_normal_image' => false,
        ];

        return [$row, $arr];
    }

    /**
     * @param $row
     * @return mixed|string|void
     */
    public function build_preview_template($row)
    {
        $tpl = new XoopsTpl();

        $tpl->assign($this->_page_class->build_xoops_param());
        $tpl->assign($this->_page_class->build_config_param());
        $tpl->assign($this->_page_class->build_perm_param());

        // BUG: not show img alt
        $tpl->assign($this->get_lang_array());

        $tpl->assign('photo', $row);

        // BUG: not show description in preview
        $tpl->assign('show_photo_summary', true);

        $template = 'db:' . $this->_DIRNAME . '_inc_photo_in_list.tpl';

        return $tpl->fetch($template);
    }

    /**
     * @param $item_row
     * @return bool
     */
    public function is_embed_preview($item_row)
    {
        $kind = $item_row['item_kind'];
        $embed_type = $item_row['item_embed_type'];

        // preview
        if (((_C_WEBPHOTO_ITEM_KIND_UNDEFINED == $kind)
             || (_C_WEBPHOTO_ITEM_KIND_EMBED == $kind))
            && $embed_type) {
            return true;
        }

        return false;
    }

    //---------------------------------------------------------
    // factory class
    //---------------------------------------------------------

    /**
     * @param $row
     * @param $checkbox
     * @return mixed
     */
    public function build_row_submit_by_post($row, $checkbox)
    {
        return $this->_factory_create_class->build_row_submit_by_post($row, $checkbox);
    }

    /**
     * @param $photo_param
     * @param $cont_param
     * @return array
     */
    public function create_sub_files($photo_param, $cont_param)
    {
        $flag_plural = true;

        $param = $this->_factory_create_class->create_sub_files($photo_param, $cont_param, $flag_plural);

        if ($this->_factory_create_class->get_flag_flash_failed()) {
            $this->set_msg_array($this->get_constant('ERR_VIDEO_FLASH'));
        }
        if ($this->_factory_create_class->get_flag_jpeg_failed()) {
            $this->set_msg_array($this->get_constant('ERR_JPEG'));
        }
        if ($this->_factory_create_class->get_flag_pdf_failed()) {
            $this->set_msg_array($this->get_constant('ERR_PDF'));
        }
        if ($this->_factory_create_class->get_flag_swf_failed()) {
            $this->set_msg_array($this->get_constant('ERR_SWF'));
        }
        if ($this->_factory_create_class->get_flag_wav_failed()) {
            $this->set_msg_array($this->get_constant('ERR_WAV'));
        }
        if ($this->_factory_create_class->get_flag_mp3_failed()) {
            $this->set_msg_array($this->get_constant('ERR_MP3'));
        }
        if ($this->_factory_create_class->get_flag_video_image_created()) {
            $this->_is_video_thumb_form = true;
        }
        if ($this->_factory_create_class->get_flag_video_image_failed()) {
            $this->set_msg_array($this->get_constant('ERR_VIDEO_THUMB'));
        }

        return $param;
    }

    /**
     * @param $item_row
     * @return bool|void
     */
    public function insert_media_file_small($item_row)
    {
        $param = $this->_factory_create_class->create_small_param_from_external_icon($item_row);
        if (!is_array($param)) {
            return false;
        }

        // --- insert file ---
        return $this->insert_file_by_param($item_row['item_id'], $param);
    }

    /**
     * @param $item_id
     * @param $param
     * @return bool|void
     */
    public function insert_file_by_param($item_id, $param)
    {
        return $this->_factory_create_class->insert_file_by_param($item_id, $param);
    }

    /**
     * @param $src_file
     * @param $dst_file
     * @param $rotate
     */
    public function rotate_image($src_file, $dst_file, $rotate)
    {
        $this->_factory_create_class->rotate_image($src_file, $dst_file, $rotate);
    }

    /**
     * @param $item_row
     * @return bool
     */
    public function video_thumb($item_row)
    {
        return $this->_factory_create_class->video_thumb($item_row);
    }

    public function set_factory_error()
    {
        if ($this->_is_module_admin && error_reporting()) {
            $this->set_msg_array($this->_factory_create_class->get_errors());
        }
    }

    //---------------------------------------------------------
    // build_redirect
    //---------------------------------------------------------

    /**
     * @return array
     */
    public function build_submit_redirect()
    {
        $item_row = $this->get_created_row();
        $cat_id = $item_row['item_cat_id'];

        return $this->build_redirect($this->build_submit_redirect_param(false, $cat_id));
    }

    /**
     * @param $is_failed
     * @param $cat_id
     * @return array
     */
    public function build_submit_redirect_param($is_failed, $cat_id)
    {
        $param = [
            'is_failed' => $is_failed,
            'is_pending' => !$this->_has_superinsert,
            'url_success' => $this->build_submit_redirect_url_success($cat_id),
            'url_pending' => $this->build_uri_operate('latest'),
            'url_failed' => $this->_THIS_URL,
            'msg_success' => $this->get_constant('SUBMIT_RECEIVED'),
            'msg_pending' => $this->get_constant('SUBMIT_ALLPENDING'),
        ];

        return $param;
    }

    /**
     * @param $cat_id
     * @return string
     */
    public function build_submit_redirect_url_success($cat_id)
    {
        $param = [
            'orderby' => 'dated',
        ];

        return $this->build_uri_category($cat_id, $param);
    }

    //---------------------------------------------------------
    // build form
    //---------------------------------------------------------
    public function init_form()
    {
        $this->init_photo_form();
        $this->init_misc_form();
    }

    public function init_photo_form()
    {
        $this->_photo_form_class->set_fct($this->_THIS_FCT);
        $this->_photo_form_class->set_form_mode($this->_FORM_MODE);
        $this->_photo_form_class->set_form_action($this->_FLAG_ADMIN);
        $this->_photo_form_class->set_tag_name_array($this->_tag_name_array);
        $this->_photo_form_class->set_checkbox_array($this->_checkbox_array);
        $this->_photo_form_class->set_preview_name($this->_preview_name);
        $this->_photo_form_class->set_rotate($this->_post_rotate);
    }

    public function init_misc_form()
    {
        $this->_misc_form_class->get_post_select_param();
        $this->_misc_form_class->set_fct($this->_THIS_FCT);
        $this->_misc_form_class->set_form_mode($this->_FORM_MODE);
        $this->_misc_form_class->set_form_action($this->_FLAG_ADMIN);
    }

    /**
     * @return array
     */
    public function build_form_base_param()
    {
        return $this->_photo_form_class->build_form_base_param();
    }

    /**
     * @param $item_row
     * @return array
     */
    public function build_form_photo($item_row)
    {
        return $this->_photo_form_class->build_form_photo($item_row);
    }

    /**
     * @return array
     */
    public function build_form_select_param()
    {
        return $this->_misc_form_class->build_form_select_param();
    }

    /**
     * @param $item_row
     * @return array
     */
    public function build_form_editor($item_row)
    {
        return $this->_misc_form_class->build_form_editor($item_row);
    }

    /**
     * @param $item_row
     * @return array
     */
    public function build_form_embed($item_row)
    {
        return $this->_misc_form_class->build_form_embed($item_row);
    }

    /**
     * @param $item_row
     * @return array
     */
    public function build_form_redo($item_row)
    {
        return $this->_misc_form_class->build_form_redo_by_item_row($item_row);
    }

    /**
     * @param $key
     * @return bool
     */
    public function check_show($key)
    {
        return $this->_photo_form_class->check_show($key);
    }

    /**
     * @param $key
     * @return bool
     */
    public function check_edit($key)
    {
        return $this->_photo_form_class->check_edit($key);
    }

    /**
     * @return array
     */
    public function build_footer_param()
    {
        $arr = [
            'execution_time' => $this->_utility_class->get_execution_time(WEBPHOTO_TIME_START),
            'memory_usage' => $this->_utility_class->get_memory_usage(),
        ];

        return $arr;
    }

    //---------------------------------------------------------
    // form video thumb
    //---------------------------------------------------------

    /**
     * @param $item_row
     * @return array
     */
    public function build_form_video_thumb($item_row)
    {
        $message = null;
        if ($this->has_msg_array()) {
            $message = $this->get_format_msg_array(true, false, true);
        }

        $param = [
            'show_form_video_thumb' => true,
            'message' => $message,
        ];

        $arr = array_merge($this->build_form_base_param(), $this->_misc_form_class->build_form_video_thumb($item_row, true), $param);

        return $arr;
    }

    //---------------------------------------------------------
    // set param form
    //---------------------------------------------------------

    /**
     * @param $val
     */
    public function set_fct($val)
    {
        $this->_THIS_FCT = $val;

        if ($this->_FLAG_ADMIN) {
            $this->_THIS_URL = $this->_MODULE_URL . '/admin/index.php?fct=' . $val;
        } else {
            $this->_THIS_URL = $this->_MODULE_URL . '/index.php?fct=' . $val;
        }
    }

    /**
     * @param $val
     */
    public function set_form_mode($val)
    {
        $this->_FORM_MODE = $val;
    }

    /**
     * @param $val
     */
    public function set_preview_name($val)
    {
        $this->_preview_name = $val;
    }

    public function get_preview_name()
    {
        return $this->_preview_name;
    }

    /**
     * @param $val
     */
    public function set_tag_name_array($val)
    {
        if (is_array($val)) {
            $this->_tag_name_array = $val;
        }
    }

    /**
     * @param $val
     */
    public function merge_tag_name_array($val)
    {
        if (is_array($this->_tag_name_array) && is_array($val)) {
            $this->_tag_name_array += $val;
            $this->_tag_name_array = array_unique($this->_tag_name_array);
        } elseif (is_array($val)) {
            $this->_tag_name_array = $val;
        }
    }

    public function get_tag_name_array()
    {
        return $this->_tag_name_array;
    }

    /**
     * @param $name
     */
    public function set_checkbox_by_post($name)
    {
        $this->set_checkbox_by_name($name, $this->get_post_int($name));
    }

    /**
     * @param $name
     * @param $value
     */
    public function set_checkbox_by_name($name, $value)
    {
        $this->_checkbox_array[$name] = $value;
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function get_checkbox_by_name($name)
    {
        if (isset($this->_checkbox_array[$name])) {
            return $this->_checkbox_array[$name];
        }

        return null;
    }

    /**
     * @param $val
     */
    public function set_rotate($val)
    {
        $this->_post_rotate = $val;
        $this->_rotate_angle = $this->conv_rotate($val);
    }

    // --- class end ---
}
