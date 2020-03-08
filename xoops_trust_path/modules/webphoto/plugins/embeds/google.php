<?php
// $Id: google.php,v 1.1 2008/10/30 00:24:19 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_embed_google
//
// http://video.google.com/videoplay?docid=-8290192083117426204
//
// <embed style="width:400px; height:326px;" id="VideoPlayback" type="application/x-shockwave-flash" src="http://video.google.com/googleplayer.swf?docId=-8290192083117426204&hl=en" flashvars=""> </embed>
//=========================================================

/**
 * Class webphoto_embed_google
 */
class webphoto_embed_google extends webphoto_embed_base
{
    public function __construct()
    {
        parent::__construct('google');
        $this->set_url('http://video.google.com/videoplay?docid=');
        $this->set_sample('-8290192083117426204');
    }

    /**
     * @param $src
     * @param $width
     * @param $height
     * @return null|string
     */
    public function embed($src, $width, $height)
    {
        $movie = 'http://video.google.com/googleplayer.swf?docId=' . $src;
        $style = 'width:' . $width . 'px; height:' . $height . 'px;';
        $embed = '<embed style="' . $style . '" id="VideoPlayback" type="application/x-shockwave-flash" src="' . $movie . '" flashvars="" >';

        return $embed;
    }

    /**
     * @param $src
     * @return null|string
     */
    public function link($src)
    {
        return $this->build_link($src);
    }

    /**
     * @return null|string
     */
    public function desc()
    {
        return $this->build_desc();
    }

    // --- class end ---
}
