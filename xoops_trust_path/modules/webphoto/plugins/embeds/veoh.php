<?php
// $Id: veoh.php,v 1.1 2008/10/30 00:24:19 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_embed_veoh
//
// http://www.veoh.com/videos/v1688234qJjc3gNG?source=featured&cmpTag=featured&rank=3
//
// <embed src="http://www.veoh.com/videodetails2.swf?permalinkId=v1688234qJjc3gNG&id=anonymous&player=videodetailsembedded&videoAutoPlay=0" allowFullScreen="true" width="540" height="438" bgcolor="#000000" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>
//=========================================================

/**
 * Class webphoto_embed_veoh
 */
class webphoto_embed_veoh extends webphoto_embed_base
{
    public function __construct()
    {
        parent::__construct('veoh');
        $this->set_url('http://www.veoh.com/videos/');
    }

    /**
     * @param        $src
     * @param        $width
     * @param        $height
     * @param string $backcolor
     * @return null|string
     */
    public function embed($src, $width, $height, $backcolor = '000000')
    {
        $movie = 'http://www.veoh.com/videodetails2.swf?permalinkId=' . $src . '&amp;id=anonymous&amp;player=videodetailsembedded&amp;videoAutoPlay=0';

        $embed = '<embed src="' . $movie . '" allowFullScreen="true" width="' . $width . '" height="' . $height . '" bgcolor=#"' . $backcolor . '" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" >';

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
        return $this->build_desc_span($this->_url_head, 'v1688234qJjc3gNG', '?source=featured');
    }

    // --- class end ---
}
