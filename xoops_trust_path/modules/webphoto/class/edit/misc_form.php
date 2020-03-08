<?php
// $Id: misc_form.php,v 1.7 2011/04/30 23:30:20 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-04 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-05-01 K.OHWADA
// Fatal error: Call to undefined method get_cached_file_row_by_kind()
// 2010-11-11 K.OHWADA
// build_file_url_by_file_row()
// 2010-10-01 K.OHWADA
// item_kind_list_video
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_ffmpeg
// move item_embed_type_select_options()
// 2009-05-05 K.OHWADA
// use build_form_mode_param()
// 2009-04-19 K.OHWADA
// print_form_editor() -> build_form_editor_with_template()
// 2009-01-10 K.OHWADA
// webphoto_photo_misc_form -> webphoto_edit_misc_form
// webphoto_ffmpeg
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_edit_misc_form
//=========================================================

/**
 * Class webphoto_edit_misc_form
 */
class webphoto_edit_misc_form extends webphoto_edit_form
{
    public $_editor_class;
    public $_ffmpeg_class;
    public $_icon_build_class;
    public $_kind_class;

    public $_ini_kind_list_video = null;

    public $_VIDEO_THUMB_WIDTH = 120;
    public $_VIDEO_THUMB_MAX = _C_WEBPHOTO_VIDEO_THUMB_PLURAL_MAX;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_edit_misc_form constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_editor_class = webphoto_editor::getInstance($dirname, $trust_dirname);
        $this->_ffmpeg_class = webphoto_ffmpeg::getInstance($dirname, $trust_dirname);
        $this->_icon_build_class = webphoto_edit_icon_build::getInstance($dirname);
        $this->_kind_class = webphoto_kind::getInstance();

        $this->_ini_kind_list_video = $this->explode_ini('item_kind_list_video');
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_edit_form|\webphoto_edit_misc_form|\webphoto_lib_element|\webphoto_lib_error|\webphoto_lib_form
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
    // editor
    //---------------------------------------------------------

    /**
     * @param $mode
     * @param $item_row
     * @return mixed|string|void
     */
    public function build_form_editor_with_template($mode, $item_row)
    {
        $template = 'db:' . $this->_DIRNAME . '_form_editor.tpl';

        list($show_editor, $param_editor) = $this->build_form_editor($item_row, $mode);

        if (!$show_editor) {
            return '';
        }

        $arr = array_merge($this->build_form_select_param($mode), $this->build_form_base_param(), $this->build_item_row($item_row), $param_editor);

        $tpl = new XoopsTpl();
        $tpl->assign($arr);

        return $tpl->fetch($template);
    }

    /**
     * @param      $item_row
     * @param null $mode
     * @return array
     */
    public function build_form_editor($item_row, $mode = null)
    {
        $options = $this->_editor_class->build_list_options(true);

        if (!$this->is_show_form_editor_option($options)) {
            return [false, []];
        }

        $param = [
            'mode' => $this->get_form_mode_default($mode),
            'options' => $options,
        ];

        $arr = [
            true,
            $this->build_form_editor_with_param($item_row, $param),
        ];

        return $arr;
    }

    /**
     * @param $row
     * @param $param
     * @return array
     */
    public function build_form_editor_with_param($row, $param)
    {
        $mode = $param['mode'];
        $options = $param['options'];

        switch ($mode) {
            case 'bulk':
                $op = 'bulk_form';
                break;
            case 'file':
                $op = 'file_form';
                break;
            case 'admin_modify':
                $op = 'modify_form';
                break;
            case 'user_submit':
            case 'admin_submit':
            case 'admin_batch':
            default:
                $op = 'submit_form';
                break;
        }

        $this->set_row($row);

        $arr = [
            'op_editor' => $op,
            'item_editor_select_options' => $this->item_editor_select_options($options),
        ];

        return $arr;
    }

    /**
     * @param $options
     * @return null|string
     */
    public function item_editor_select_options($options)
    {
        $value = $this->get_item_editor(true);

        return $this->build_form_options($value, $options);
    }

    //---------------------------------------------------------
    // embed
    //---------------------------------------------------------

    /**
     * @param $mode
     * @param $item_row
     * @return mixed|string|void
     */
    public function build_form_embed_with_template($mode, $item_row)
    {
        $template = 'db:' . $this->_DIRNAME . '_form_embed.tpl';

        if (!$this->is_show_form_admin($item_row)) {
            return '';
        }

        $arr = array_merge($this->build_form_select_param($mode), $this->build_form_base_param(), $this->build_form_embed_with_row($item_row), $this->build_item_row($item_row));

        $tpl = new XoopsTpl();
        $tpl->assign($arr);

        return $tpl->fetch($template);
    }

    /**
     * @param $item_row
     * @return array
     */
    public function build_form_embed($item_row)
    {
        if (!$this->is_show_form_embed()) {
            return [false, []];
        }

        $arr = [
            true,
            $this->build_form_embed_with_row($item_row),
        ];

        return $arr;
    }

    /**
     * @param $item_row
     * @return array
     */
    public function build_form_embed_with_row($item_row)
    {
        $this->set_row($item_row);

        $arr = [
            'item_embed_type_select_options' => $this->item_embed_type_select_options(),
        ];

        return $arr;
    }

    //---------------------------------------------------------
    // video thumb
    //---------------------------------------------------------

    /**
     * @param $mode
     * @param $row
     * @return mixed|string|void
     */
    public function build_form_video_thumb_with_template($mode, $row)
    {
        $template = 'db:' . $this->_DIRNAME . '_form_video_thumb.tpl';

        $arr = array_merge($this->build_form_mode_param($mode), $this->build_form_base_param(), $this->build_form_video_thumb($row, true));

        $tpl = new XoopsTpl();
        $tpl->assign($arr);

        return $tpl->fetch($template);
    }

    /**
     * @param $row
     * @param $flag_row
     * @return array
     */
    public function build_form_video_thumb($row, $flag_row)
    {
        $param = [
            'video_thumb_array' => $this->build_video_thumb_array($row),
            'colspan_video_submit' => $this->_VIDEO_THUMB_MAX + 1,
        ];

        if ($flag_row) {
            $arr = array_merge($param, $this->build_item_row($row));
        } else {
            $arr = $param;
        }

        return $arr;
    }

    /**
     * @param $row
     * @return array
     */
    public function build_video_thumb_array($row)
    {
        $item_id = $row['item_id'];
        $ext = $row['item_ext'];

        $arr = [];
        for ($i = 0; $i <= $this->_VIDEO_THUMB_MAX; ++$i) {
            $src = null;
            $width = 0;

            // default icon
            if (0 == $i) {
                list($name, $width, $height) = $this->_icon_build_class->build_icon_image($ext);
                if ($name) {
                    $src = $this->_ROOT_EXTS_URL . '/' . $name;
                }

                // created thumbs
            } else {
                $name = $this->_ffmpeg_class->build_thumb_name($item_id, $i);
                $file = $this->_TMP_DIR . '/' . $name;
                $width = $this->_VIDEO_THUMB_WIDTH;

                if (is_file($file)) {
                    $name_encode = rawurlencode($name);
                    $src = $this->_MODULE_URL . '/index.php?fct=image_tmp&name=' . $name_encode;
                }
            }

            $arr[] = [
                'src_s' => $this->sanitize($src),
                'width' => $width,
                'num' => $i,
            ];
        }

        return $arr;
    }

    //---------------------------------------------------------
    // redo
    //---------------------------------------------------------

    /**
     * @param $mode
     * @param $item_row
     * @param $flash_row
     * @return mixed|string|void
     */
    public function build_form_redo_with_template($mode, $item_row, $flash_row)
    {
        $template = 'db:' . $this->_DIRNAME . '_form_redo.tpl';

        if (!$this->is_show_form_redo($item_row)) {
            return '';
        }

        $arr = array_merge($this->build_form_mode_param($mode), $this->build_form_base_param(), $this->build_form_redo_by_flash_row($flash_row), $this->build_item_row($item_row));

        $tpl = new XoopsTpl();
        $tpl->assign($arr);

        return $tpl->fetch($template);
    }

    /**
     * @param $item_row
     * @return array
     */
    public function build_form_redo_by_item_row($item_row)
    {
        if (!$this->is_show_form_redo($item_row)) {
            return [false, []];
        }

        // Fatal error: Call to undefined method get_cached_file_row_by_kind()
        $flash_row = $this->get_cached_file_extend_row_by_kind($item_row, _C_WEBPHOTO_FILE_KIND_VIDEO_FLASH);

        $arr = [
            true,
            $this->build_form_redo_by_flash_row($flash_row),
        ];

        return $arr;
    }

    /**
     * @param $flash_row
     * @return array
     */
    public function build_form_redo_by_flash_row($flash_row)
    {
        $arr = [
            'flash_url_s' => $this->build_flash_url_s($flash_row),
        ];

        return $arr;
    }

    /**
     * @param $flash_row
     * @return string
     */
    public function build_flash_url_s($flash_row)
    {
        return $this->sanitize($this->build_file_url_by_file_row($flash_row));
    }

    /**
     * @param $item_row
     * @return bool
     */
    public function is_show_form_redo($item_row)
    {
        if ($this->is_video_kind($item_row['item_kind'])) {
            return true;
        }

        return false;
    }

    /**
     * @param $kind
     * @return bool
     */
    public function is_video_kind($kind)
    {
        if (in_array($kind, $this->_ini_kind_list_video)) {
            return true;
        }

        return false;
    }

    // --- class end ---
}
