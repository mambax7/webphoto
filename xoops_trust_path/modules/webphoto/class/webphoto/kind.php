<?php
// $Id: kind.php,v 1.7 2010/09/27 03:42:54 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-09-20 K.OHWADA
// is_wav_ext()
// 2009-10-25 K.OHWADA
// is_jpeg_ext()
// 2009-03-15 K.OHWADA
// change is_ext_in_array()
// 2009-01-25 K.OHWADA
// is_swf_ext()
// 2009-01-10 K.OHWADA
// is_general_kind() etc
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_kind
//=========================================================
class webphoto_kind
{
    public $_IMAGE_EXTS;
    public $_SWFOBJECT_EXTS;
    public $_MEDIAPLAYER_AUDIO_EXTS;
    public $_MEDIAPLAYER_VIDEO_EXTS;
    public $_VIDEO_DOCOMO_EXTS;

    public $_FLASH_EXTS = array('flv');
    public $_PDF_EXTS   = array('pdf');
    public $_SWF_EXTS   = array('swf');
    public $_JPEG_EXTS  = array('jpg', 'jpeg');
    public $_MP3_EXTS   = array('mp3');
    public $_WAV_EXTS   = array('wav');

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        $this->_IMAGE_EXTS             = explode('|', _C_WEBPHOTO_IMAGE_EXTS);
        $this->_SWFOBJECT_EXTS         = explode('|', _C_WEBPHOTO_SWFOBJECT_EXTS);
        $this->_MEDIAPLAYER_AUDIO_EXTS = explode('|', _C_WEBPHOTO_MEDIAPLAYER_AUDIO_EXTS);
        $this->_MEDIAPLAYER_VIDEO_EXTS = explode('|', _C_WEBPHOTO_MEDIAPLAYER_VIDEO_EXTS);
        $this->_VIDEO_DOCOMO_EXTS      = explode('|', _C_WEBPHOTO_VIDEO_DOCOMO_EXTS);
    }

    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new webphoto_kind();
        }
        return $instance;
    }

    //---------------------------------------------------------
    // exts
    //---------------------------------------------------------
    public function get_image_exts()
    {
        return $this->_IMAGE_EXTS;
    }

    public function is_mediaplayer_ext($ext)
    {
        if ($this->is_mediaplayer_audio_ext($ext)) {
            return true;
        }
        if ($this->is_mediaplayer_video_ext($ext)) {
            return true;
        }
        return false;
    }

    public function is_image_ext($ext)
    {
        return $this->is_ext_in_array($ext, $this->_IMAGE_EXTS);
    }

    public function is_jpeg_ext($ext)
    {
        return $this->is_ext_in_array($ext, $this->_JPEG_EXTS);
    }

    public function is_swfobject_ext($ext)
    {
        return $this->is_ext_in_array($ext, $this->_SWFOBJECT_EXTS);
    }

    public function is_mediaplayer_audio_ext($ext)
    {
        return $this->is_ext_in_array($ext, $this->_MEDIAPLAYER_AUDIO_EXTS);
    }

    public function is_mediaplayer_video_ext($ext)
    {
        return $this->is_ext_in_array($ext, $this->_MEDIAPLAYER_VIDEO_EXTS);
    }

    public function is_video_docomo_ext($ext)
    {
        return $this->is_ext_in_array($ext, $this->_VIDEO_DOCOMO_EXTS);
    }

    public function is_flash_ext($ext)
    {
        return $this->is_ext_in_array($ext, $this->_FLASH_EXTS);
    }

    public function is_pdf_ext($ext)
    {
        return $this->is_ext_in_array($ext, $this->_PDF_EXTS);
    }

    public function is_swf_ext($ext)
    {
        return $this->is_ext_in_array($ext, $this->_SWF_EXTS);
    }

    public function is_mp3_ext($ext)
    {
        return $this->is_ext_in_array($ext, $this->_MP3_EXTS);
    }

    public function is_wav_ext($ext)
    {
        return $this->is_ext_in_array($ext, $this->_WAV_EXTS);
    }

    public function is_ext_in_array($ext, $arr)
    {
        if ($ext && in_array(strtolower($ext), $arr)) {
            return true;
        }
        return false;
    }

    //---------------------------------------------------------
    // kind
    //---------------------------------------------------------
    public function is_src_image_kind($kind)
    {
        if ($this->is_image_kind($kind)) {
            return true;
        }
        if ($this->is_external_image_kind($kind)) {
            return true;
        }
    }

    public function is_video_audio_kind($kind)
    {
        if ($this->is_video_kind($kind)) {
            return true;
        }
        if ($this->is_audio_kind($kind)) {
            return true;
        }
        return false;
    }

    public function is_external_embed_playlist_kind($kind)
    {
        if ($this->is_external_kind($kind)) {
            return true;
        }
        if ($this->is_embed_kind($kind)) {
            return true;
        }
        if ($this->is_playlist_kind($kind)) {
            return true;
        }
        return false;
    }

    public function is_external_kind($kind)
    {
        if ($this->is_external_general_kind($kind)) {
            return true;
        }
        if ($this->is_external_image_kind($kind)) {
            return true;
        }
        return false;
    }

    public function is_playlist_kind($kind)
    {
        if ($this->is_playlist_feed_kind($kind)) {
            return true;
        }
        if ($this->is_playlist_dir_kind($kind)) {
            return true;
        }
        return false;
    }

    public function is_undefined_kind($kind)
    {
        return $this->_is_kind($kind, _C_WEBPHOTO_ITEM_KIND_UNDEFINED);
    }

    public function is_none_kind($kind)
    {
        return $this->_is_kind($kind, _C_WEBPHOTO_ITEM_KIND_NONE);
    }

    public function is_general_kind($kind)
    {
        return $this->_is_kind($kind, _C_WEBPHOTO_ITEM_KIND_GENERAL);
    }

    public function is_image_kind($kind)
    {
        return $this->_is_kind($kind, _C_WEBPHOTO_ITEM_KIND_IMAGE);
    }

    public function is_video_kind($kind)
    {
        return $this->_is_kind($kind, _C_WEBPHOTO_ITEM_KIND_VIDEO);
    }

    public function is_audio_kind($kind)
    {
        return $this->_is_kind($kind, _C_WEBPHOTO_ITEM_KIND_AUDIO);
    }

    public function is_embed_kind($kind)
    {
        return $this->_is_kind($kind, _C_WEBPHOTO_ITEM_KIND_EMBED);
    }

    public function is_external_general_kind($kind)
    {
        return $this->_is_kind($kind, _C_WEBPHOTO_ITEM_KIND_EXTERNAL_GENERAL);
    }

    public function is_external_image_kind($kind)
    {
        return $this->_is_kind($kind, _C_WEBPHOTO_ITEM_KIND_EXTERNAL_IMAGE);
    }

    public function is_playlist_feed_kind($kind)
    {
        return $this->_is_kind($kind, _C_WEBPHOTO_ITEM_KIND_PLAYLIST_FEED);
    }

    public function is_playlist_dir_kind($kind)
    {
        return $this->_is_kind($kind, _C_WEBPHOTO_ITEM_KIND_PLAYLIST_DIR);
    }

    public function _is_kind($kind, $const)
    {
        if ($kind == $const) {
            return true;
        }
        return false;
    }

    // --- class end ---
}
