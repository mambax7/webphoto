<?php
// $Id: flv.php,v 1.2 2010/10/06 02:22:46 ohwada Exp $

//=========================================================
// webphoto module
// 2010-10-01 K.OHWADA
//=========================================================

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_ext_flv
//=========================================================

/**
 * Class webphoto_ext_flv
 */
class webphoto_ext_flv extends webphoto_ext_base
{
    public $_ffmpeg_class;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_ext_flv constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_ffmpeg_class = webphoto_ffmpeg::getInstance($dirname, $trust_dirname);
    }

    //---------------------------------------------------------
    // check type
    //---------------------------------------------------------

    /**
     * @param $ext
     * @return bool
     */
    public function is_ext($ext)
    {
        return $this->match_ext_kind($ext, _C_WEBPHOTO_MIME_KIND_VIDEO_FLV);
    }

    //---------------------------------------------------------
    // create jpeg
    //---------------------------------------------------------

    /**
     * @param $param
     * @return int|null
     */
    public function create_jpeg($param)
    {
        $src_file = $param['src_file'];
        $jpeg_file = $param['jpeg_file'];

        return $this->_ffmpeg_class->create_jpeg($src_file, $jpeg_file);
    }

    //---------------------------------------------------------
    // create video_images
    //---------------------------------------------------------

    /**
     * @param $param
     * @return bool|int|null
     */
    public function create_video_images($param)
    {
        $item_id = $param['item_id'];
        $src_file = $param['src_file'];

        return $this->_ffmpeg_class->create_plural_images($item_id, $src_file);
    }

    //---------------------------------------------------------
    // duration
    //---------------------------------------------------------

    /**
     * @param $param
     * @return array|null
     */
    public function get_video_info($param)
    {
        $src_file = $param['src_file'];

        return $this->_ffmpeg_class->get_video_info($src_file);
    }

    // --- class end ---
}
