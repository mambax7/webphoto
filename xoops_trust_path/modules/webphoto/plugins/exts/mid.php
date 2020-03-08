<?php
// $Id: mid.php,v 1.4 2010/10/06 02:22:46 ohwada Exp $

//=========================================================
// webphoto module
// 2009-10-25 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-10-01 K.OHWADA
// create_mp3() -> create_wav()
// 2009-11-11 K.OHWADA
// $trust_dirname
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_ext_mid
//=========================================================

/**
 * Class webphoto_ext_mid
 */
class webphoto_ext_mid extends webphoto_ext_base
{
    public $_timidity_class;
    public $_lame_class;
    public $_ffmpeg_class;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_ext_mid constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_timidity_class = webphoto_timidity::getInstance($dirname, $trust_dirname);
        $this->_lame_class = webphoto_lame::getInstance($dirname, $trust_dirname);
        $this->_ffmpeg_class = webphoto_ffmpeg::getInstance($dirname, $trust_dirname);

        $this->set_debug_by_name('MID');
    }

    //---------------------------------------------------------
    // check ext
    //---------------------------------------------------------

    /**
     * @param $ext
     * @return bool
     */
    public function is_ext($ext)
    {
        return $this->is_audio_mid_ext($ext);
    }

    /**
     * @param $ext
     * @return bool
     */
    public function is_audio_mid_ext($ext)
    {
        return $this->match_ext_kind($ext, _C_WEBPHOTO_MIME_KIND_AUDIO_MID);
    }

    //---------------------------------------------------------
    // create wav
    //---------------------------------------------------------

    /**
     * @param $param
     * @return int|null
     */
    public function create_wav($param)
    {
        $item_id = $param['item_id'];
        $src_file = $param['src_file'];
        $wav_file = $param['wav_file'];

        return $this->_timidity_class->create_wav($src_file, $wav_file);
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
