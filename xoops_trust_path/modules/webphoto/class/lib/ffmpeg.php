<?php
// $Id: ffmpeg.php,v 1.12 2010/10/09 02:19:20 ohwada Exp $

//=========================================================
// webphoto module
// 2008-07-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-10-01 K.OHWADA
// create_wav()
// 2010-06-06 K.OHWADA
// is_win_os()
// 2009-04-21 K.OHWADA
// chmod_file()
// 2009-01-10 K.OHWADA
// version()
// 2008-08-24 K.OHWADA
// flag_chmod
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_lib_ffmpeg
//=========================================================

/**
 * Class webphoto_lib_ffmpeg
 */
class webphoto_lib_ffmpeg
{
    public $_ini_safe_mode;

    // set param
    public $_CMD_PATH = null;
    public $_TMP_PATH = null;
    public $_prefix = 'thumb';
    public $_ext = 'jpg';
    public $_offset = 0;
    public $_flag_chmod = false;

    public $_audio_aac_array = ['aac', 'libfaad'];
    public $_video_h264_array = ['h264'];

    public $_msg_array = [];

    public $_CMD_FFMPEG = 'ffmpeg';
    public $_PARAM_INFO = ' -i %s';
    public $_PARAM_CREATE_THUMBS = ' -vframes 1 -ss %s -i %s -f image2 %s';
    public $_PARAM_CREATE_FLASH = ' -i %s -vcodec flv %s -f flv %s';
    public $_PARAM_CREATE_MP3 = ' -i %s %s -f mp3 %s';
    public $_PARAM_CREATE_WAV = ' -i %s %s -f wav %s';
    public $_PARAM_VERSION = ' -version';

    public $_EXT_FLV = 'flv';
    public $_EXT_MP3 = 'mp3';
    public $_EXT_WAV = 'wav';
    public $_CHMOD_MODE = 0777;

    public $_DEBUG = false;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        $this->_ini_safe_mode = ini_get('safe_mode');
    }

    /**
     * @return \webphoto_lib_ffmpeg
     */
    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new self();
        }

        return $instance;
    }

    //---------------------------------------------------------
    // set
    //---------------------------------------------------------
    // MUST path has no sapce
    // cannot use windows type's path like the following
    // C:/Program Files/program/

    /**
     * @param $val
     */
    public function set_cmd_path($val)
    {
        $this->_CMD_PATH = $val;
        $this->_CMD_FFMPEG = $this->_CMD_PATH . 'ffmpeg';

        if ($this->is_win_os()) {
            $this->_CMD_FFMPEG = $this->conv_win_cmd($this->_CMD_FFMPEG);
        }
    }

    /**
     * @param $val
     */
    public function set_tmp_path($val)
    {
        $this->_TMP_PATH = $val;
    }

    /**
     * @param $val
     */
    public function set_prefix($val)
    {
        $this->_prefix = $val;
    }

    /**
     * @param $val
     */
    public function set_ext($val)
    {
        $this->_ext = $val;
    }

    /**
     * @param $val
     */
    public function set_offset($val)
    {
        $this->_offset = $val;
    }

    /**
     * @param $val
     */
    public function set_flag_chmod($val)
    {
        $this->_flag_chmod = (bool)$val;
    }

    /**
     * @param $val
     */
    public function set_debug($val)
    {
        $this->_DEBUG = (bool)$val;
    }

    //---------------------------------------------------------
    // get duration width height
    //
    // forcible method
    // duration time in strerr, when execute the input-file only
    // reference http://blog.ishiro.com/?p=182
    //
    // Input #0, avi, from 'hoge.avi':
    //  Duration: 00:00:09.00, start: 0.000000, bitrate: 9313 kb/s
    //    Stream #0.0: Video: mjpeg, yuvj422p, 640x480, 30.00 tb(r)
    //    Stream #0.1: Audio: pcm_u8, 11024 Hz, mono, 88 kb/s
    //---------------------------------------------------------

    /**
     * @param $file
     * @return array|bool
     */
    public function get_video_info($file)
    {
        $cmd = $this->_CMD_FFMPEG . sprintf($this->_PARAM_INFO, $file);

        $this->clear_msg_array();
        $ret_array = $this->cmd_excute($cmd);
        if (!$ret_array) {
            return false;
        }

        $line_duration = null;
        $line_audio = null;
        $line_video = null;
        $audio_codec = null;
        $video_codec = null;
        $duration = 0;
        $width = 0;
        $height = 0;

        foreach ($ret_array as $line) {
            if (preg_match("/duration.*(\d+):(\d+):(\d+)/i", $line, $match)) {
                $line_duration = $line;
                $duration = (int)$match[1] * 3600 + (int)$match[2] * 60 + (int)$match[3];
            }
            if (preg_match('/stream.*audio:(.*)/i', $line, $match)) {
                $line_audio = $line;
                $arr = explode(',', $match[1]);
                if (isset($arr[0])) {
                    $audio_codec = trim($arr[0]);
                }
            }
            if (preg_match("/stream.*video:(.*)\s(\d+)x(\d+)/i", $line, $match)) {
                $line_video = $line;
                $width = (int)$match[2];
                $height = (int)$match[3];
                $arr = explode(',', $match[1]);
                if (isset($arr[0])) {
                    $video_codec = trim($arr[0]);
                }
            }
        }

        $arr = [
            'line_duration' => $line_duration,
            'line_audio' => $line_audio,
            'line_video' => $line_video,
            'audio_codec' => $audio_codec,
            'video_codec' => $video_codec,
            'is_h264_aac' => $this->is_h264_aac($video_codec, $audio_codec),
            'duration' => $duration,
            'width' => $width,
            'height' => $height,
        ];

        return $arr;
    }

    /**
     * @param $video
     * @param $audio
     * @return bool
     */
    public function is_h264_aac($video, $audio)
    {
        if ($this->is_video_h264($video)
            && $this->is_audio_aac($audio)) {
            return true;
        }

        return false;
    }

    /**
     * @param $video
     * @return bool
     */
    public function is_video_h264($video)
    {
        if (in_array($video, $this->_video_h264_array)) {
            return true;
        }

        return false;
    }

    /**
     * @param $audio
     * @return bool
     */
    public function is_audio_aac($audio)
    {
        if (in_array($audio, $this->_audio_aac_array)) {
            return true;
        }

        return false;
    }

    //---------------------------------------------------------
    // create thumbs
    //---------------------------------------------------------

    /**
     * @param     $file_in
     * @param int $max
     * @param int $start
     * @param int $step
     * @return int
     */
    public function create_thumbs($file_in, $max = 5, $start = 0, $step = 1)
    {
        $this->clear_msg_array();

        $count = 0;
        for ($i = 0; $i < $max; ++$i) {
            $sec = $i * $step + $start;
            $name = $this->build_thumb_name($i + $this->_offset);
            $file_out = $this->_TMP_PATH . '/' . $name;

            $ret = $this->create_single_thumb($file_in, $file_out, $sec);
            if ($ret) {
                ++$count;
            }
        }

        return $count;
    }

    /**
     * @param $file_in
     * @param $file_out
     * @param $sec
     * @return bool
     */
    public function create_single_thumb($file_in, $file_out, $sec)
    {
        $cmd = $this->_CMD_FFMPEG . sprintf($this->_PARAM_CREATE_THUMBS, $sec, $file_in, $file_out);
        $this->cmd_excute($cmd);

        return $this->chmod_file_out($file_out);
    }

    /**
     * @param $num
     * @return string
     */
    public function build_thumb_name($num)
    {
        $str = $this->_prefix . $num . '.' . $this->_ext;

        return $str;
    }

    //---------------------------------------------------------
    // create flash
    //---------------------------------------------------------

    /**
     * @param      $file_in
     * @param      $file_out
     * @param null $extra
     * @return bool
     */
    public function create_flash($file_in, $file_out, $extra = null)
    {
        // return input file is same format
        $ret = $this->check_file_in($file_in, $this->_EXT_FLV);
        if ($ret) {
            return false;
        }

        $cmd = $this->_CMD_FFMPEG . sprintf($this->_PARAM_CREATE_FLASH, $file_in, $extra, $file_out);

        $this->clear_msg_array();
        $this->cmd_excute($cmd);

        return $this->chmod_file_out($file_out);
    }

    //---------------------------------------------------------
    // create wav
    //---------------------------------------------------------

    /**
     * @param      $file_in
     * @param      $file_out
     * @param null $extra
     * @return bool
     */
    public function create_wav($file_in, $file_out, $extra = null)
    {
        // return input file is same format
        $ret = $this->check_file_in($file_in, $this->_EXT_WAV);
        if ($ret) {
            return false;
        }

        $cmd = $this->_CMD_FFMPEG . sprintf($this->_PARAM_CREATE_WAV, $file_in, $extra, $file_out);

        $this->clear_msg_array();
        $this->cmd_excute($cmd);

        return $this->chmod_file_out($file_out);
    }

    //---------------------------------------------------------
    // create mp3
    //---------------------------------------------------------

    /**
     * @param      $file_in
     * @param      $file_out
     * @param null $extra
     * @return bool
     */
    public function create_mp3($file_in, $file_out, $extra = null)
    {
        // return input file is same format
        $ret = $this->check_file_in($file_in, $this->_EXT_MP3);
        if ($ret) {
            return false;
        }

        $cmd = $this->_CMD_FFMPEG . sprintf($this->_PARAM_CREATE_MP3, $file_in, $extra, $file_out);

        $this->clear_msg_array();
        $this->cmd_excute($cmd);

        return $this->chmod_file_out($file_out);
    }

    //---------------------------------------------------------
    // version
    //---------------------------------------------------------

    /**
     * @param $path
     * @return array
     */
    public function version($path)
    {
        $ret = false;
        $msg = '';

        $ffmpeg = $path . 'ffmpeg';
        if ($this->is_win_os()) {
            $ffmpeg = $this->conv_win_cmd($ffmpeg);
        }

        $cmd = $ffmpeg . $this->_PARAM_VERSION;

        exec("$cmd 2>&1", $ret_array);
        if (is_array($ret_array) && count($ret_array)) {
            foreach ($ret_array as $line) {
                if (preg_match('/version/i', $line)) {
                    $msg .= $line . "<br>\n";
                    $ret = true;
                }
            }
        }

        if (!$ret) {
            $msg = 'Error: ' . $ffmpeg . " can't be executed";
        }

        return [$ret, $msg];
    }

    //---------------------------------------------------------
    // function
    //---------------------------------------------------------

    /**
     * @param $cmd
     * @return bool|null
     */
    public function cmd_excute($cmd)
    {
        $ret_array = null;
        exec("$cmd 2>&1", $ret_array);
        if ($this->_DEBUG) {
            echo $cmd . "<br>\n";
            print_r($ret_array);
            echo "<br>\n";
        }

        $this->set_msg($cmd);
        $this->set_msg($ret_array);

        if (!is_array($ret_array)) {
            return false;
        }

        return $ret_array;
    }

    /**
     * @param $file
     * @param $ext
     * @return bool
     */
    public function check_file_in($file, $ext)
    {
        // file matches ext
        if ($this->parse_ext($file) == $ext) {
            return true;
        }

        return false;
    }

    /**
     * @param $file
     * @return bool
     */
    public function chmod_file_out($file)
    {
        // file exists ?
        if (!file_exists($file) || (0 == filesize($file))) {
            return false;
        }

        // chmod
        if ($this->_flag_chmod && !$this->_ini_safe_mode) {
            chmod($file, $this->_CHMOD_MODE);
        }

        return true;
    }

    /**
     * @param $file
     * @return string
     */
    public function parse_ext($file)
    {
        return mb_strtolower(mb_substr(mb_strrchr($file, '.'), 1));
    }

    //---------------------------------------------------------
    // utility
    //---------------------------------------------------------

    /**
     * @return bool
     */
    public function is_win_os()
    {
        if (0 === mb_strpos(PHP_OS, 'WIN')) {
            return true;
        }

        return false;
    }

    /**
     * @param $cmd
     * @return string
     */
    public function conv_win_cmd($cmd)
    {
        $str = '"' . $cmd . '.exe"';

        return $str;
    }

    //---------------------------------------------------------
    // msg
    //---------------------------------------------------------
    public function clear_msg_array()
    {
        $this->_msg_array = [];
    }

    /**
     * @return array
     */
    public function get_msg_array()
    {
        return $this->_msg_array;
    }

    /**
     * @param $ret_array
     */
    public function set_msg($ret_array)
    {
        if (is_array($ret_array)) {
            foreach ($ret_array as $line) {
                $this->_msg_array[] = $line;
            }
        } else {
            $this->_msg_array[] = $ret_array;
        }
    }

    // --- class end ---
}
