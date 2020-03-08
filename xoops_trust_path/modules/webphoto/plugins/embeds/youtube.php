<?php
// $Id: youtube.php,v 1.3 2010/06/16 22:24:47 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-06-06 K.OHWADA
// get_xml_params()
// 2008-11-16 K.OHWADA
// width()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_embed_youtube
//
// http://www.youtube.com/watch?v=xFnwzdKNtpI
//
// <object width="425" height="373">
// <param name="movie" value="http://www.youtube.com/v/xFnwzdKNtpI&rel=0&border=1"></param>
// <param name="wmode" value="transparent"></param>
// <embed src="http://www.youtube.com/v/lGVwm326rnk&rel=0&border=1" type="application/x-shockwave-flash" wmode="transparent" width="425" height="373"></embed>
// </object>
//=========================================================

/**
 * Class webphoto_embed_youtube
 */
class webphoto_embed_youtube extends webphoto_embed_base
{
    public $_URL_REMOVE = '&feature=.*';

    public function __construct()
    {
        parent::__construct('youtube');
        $this->set_url('http://www.youtube.com/watch?v=');
        $this->set_sample('xFnwzdKNtpI');
    }

    /**
     * @param $src
     * @param $width
     * @param $height
     * @return null|string
     */
    public function embed($src, $width, $height)
    {
        $str = $this->build_embed_script($src, $width, $height);

        return $str;
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
     * @param $src
     * @return null|string
     */
    public function thumb($src)
    {
        $str = 'http://img.youtube.com/vi/' . $src . '/2.jpg';

        return $str;
    }

    /**
     * @return int
     */
    public function width()
    {
        return 425;
    }

    /**
     * @return int
     */
    public function height()
    {
        return 344;
    }

    /**
     * @return null|string
     */
    public function desc()
    {
        return $this->build_desc();
    }

    //---------------------------------------------------------
    // xml
    //---------------------------------------------------------

    /**
     * @return array|null
     */
    public function support_params()
    {
        return $this->build_support_params();
    }

    /**
     * @param $src
     * @return array|bool|null
     */
    public function get_xml_params($src)
    {
        if ('-' == mb_substr($src, 0, 1)) {
            $d = mb_substr($src, 1);
        } else {
            $id = $src;
        }

        $url = 'http://gdata.youtube.com/feeds/api/videos?vq=' . $id;
        $cont = $this->get_remote_file($url);
        if (empty($cont)) {
            return false;
        }

        $xml = $this->get_simplexml($cont);
        $total = $this->get_xpath($xml, '//openSearch:totalResults');
        if (1 != $total) {
            return false;
        }

        $entry = $this->get_obj_property($xml, 'entry');
        if (!is_object($entry)) {
            return false;
        }

        $arr = [
            'title' => $this->get_xml_title($entry),
            'description' => $this->get_xml_description($entry),
            'url' => $this->get_xml_url($entry),
            'thumb' => $this->get_xml_thumb($entry),
            'duration' => $this->get_xml_duration($entry),
            'tags' => $this->get_xml_tags($entry),
            'script' => $this->build_xml_script($src),
        ];

        return $arr;
    }

    /**
     * @param $entry
     * @return bool|null|string|string[]
     */
    public function get_xml_title($entry)
    {
        $str = $this->get_xpath($entry, '//media:title');
        $str = $this->convert_from_utf8((string)$str);

        return $str;
    }

    /**
     * @param $entry
     * @return bool|null|string|string[]
     */
    public function get_xml_description($entry)
    {
        $str = $this->get_xpath($entry, '//media:description');
        $str = $this->convert_from_utf8((string)$str);

        return $str;
    }

    /**
     * @param $entry
     * @return bool|null|string|string[]
     */
    public function get_xml_url($entry)
    {
        $xpath = $this->get_xpath($entry, '//media:player');
        $str = $this->get_obj_attributes($xpath, 'url');
        $str = preg_replace('/' . $this->_URL_REMOVE . '/', '', $str);

        return $str;
    }

    /**
     * @param $entry
     * @return bool|string
     */
    public function get_xml_thumb($entry)
    {
        $xpath = $this->get_xpath($entry, '//media:thumbnail');
        $str = $this->get_obj_attributes($xpath, 'url');
        $str = (string)$str;

        return $str;
    }

    /**
     * @param $entry
     * @return bool|string
     */
    public function get_xml_duration($entry)
    {
        $xpath = $this->get_xpath($entry, '//yt:duration');
        $str = $this->get_obj_attributes($xpath, 'seconds');
        $str = (string)$str;

        return $str;
    }

    /**
     * @param $entry
     * @return array
     */
    public function get_xml_tags($entry)
    {
        $str = $this->get_xpath($entry, '//media:keywords');
        $arr = $this->str_to_array($str, ',');
        $arr = $this->convert_array_from_utf8($arr);

        return $arr;
    }

    /**
     * @param $src
     */
    public function build_xml_script($src)
    {
        $str = $this->build_embed_script_with_repalce($src);

        return $str;
    }

    /**
     * @param $src
     * @param $width
     * @param $height
     * @return null|string
     */
    public function build_embed_script($src, $width, $height)
    {
        $movie = 'http://www.youtube.com/v/' . $src . '&amp;rel=0&amp;border=0';
        $wmode = 'transparent';
        $extra = 'wmode="' . $wmode . '"';

        $str = $this->build_object_begin($width, $height);
        $str .= $this->build_param('movie', $movie);
        $str .= $this->build_param('wmode', $wmode);
        $str .= $this->build_embed_flash($movie, $width, $height, $extra);
        $str .= $this->build_object_end();

        return $str;
    }

    // --- class end ---
}
