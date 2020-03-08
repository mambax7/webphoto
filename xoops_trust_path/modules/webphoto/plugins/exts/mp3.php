<?php
// $Id: mp3.php,v 1.2 2010/10/06 02:22:46 ohwada Exp $

//=========================================================
// webphoto module
// 2010-10-01 K.OHWADA
//=========================================================

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_ext_mp3
//=========================================================

/**
 * Class webphoto_ext_mp3
 */
class webphoto_ext_mp3 extends webphoto_ext_base
{
    public $_ffmpeg_class;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_ext_mp3 constructor.
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
        return $this->is_audio_ext($ext);
    }

    /**
     * @param $ext
     * @return bool
     */
    public function is_audio_ext($ext)
    {
        return $this->match_ext_kind($ext, _C_WEBPHOTO_MIME_KIND_AUDIO_MP3);
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
