<?php
// $Id: nicovideo.php,v 1.2 2010/10/10 11:02:10 ohwada Exp $

//=========================================================
// webphoto module
// 2010-06-06 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-10-01 K.OHWADA
// src for nm****
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_embed_nicovideo
//
// http://www.nicovideo.jp/watch/sm7389627
//
// <script type="text/javascript" src="http://ext.nicovideo.jp/thumb_watch/sm7389627">
//
// <iframe width="312" height="176" src="http://ext.nicovideo.jp/thumb/sm7389627" scrolling="no" style="border:solid 1px #CCC;" frameborder="0">
// <a href="http://www.nicovideo.jp/watch/sm7389627">
//=========================================================

/**
 * Class webphoto_embed_nicovideo
 */
class webphoto_embed_nicovideo extends webphoto_embed_base
{
    // this word is written by UTF-8
    public $_DESCRIPTION_REMOVE = '前→.*';

    public function __construct()
    {
        parent::__construct('nicovideo');
        $this->set_url('http://www.nicovideo.jp/watch/');
        $this->set_sample('sm7389627');
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
     * @return int
     */
    public function width()
    {
        return 312;
    }

    /**
     * @return int
     */
    public function height()
    {
        return 176;
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
        $url = 'http://www.nicovideo.jp/api/getthumbinfo/' . $src;
        $cont = $this->get_remote_file($url);
        if (empty($cont)) {
            return false;
        }

        $xml = $this->get_simplexml($cont);
        $status = $this->get_obj_attributes($xml, 'status');
        if ('ok' != $status) {
            return false;
        }

        $thumb = $this->get_obj_property($xml, 'thumb');
        if (!is_object($thumb)) {
            return false;
        }

        $arr = [
            'title' => $this->get_xml_title($thumb),
            'description' => $this->get_xml_description($thumb),
            'url' => $this->get_xml_url($thumb),
            'thumb' => $this->get_xml_thumb($thumb),
            'duration' => $this->get_xml_duration($thumb),
            'tags' => $this->get_xml_tags($thumb),
            'script' => $this->build_xml_script($src),
        ];

        return $arr;
    }

    /**
     * @param $thumb
     * @return bool|null|string|string[]
     */
    public function get_xml_title($thumb)
    {
        $str = $this->get_obj_property($thumb, 'title');
        $str = $this->convert_from_utf8((string)$str);

        return $str;
    }

    /**
     * @param $thumb
     * @return bool|null|string|string[]
     */
    public function get_xml_description($thumb)
    {
        $str = $this->get_obj_property($thumb, 'description');
        $str = preg_replace('/' . $this->_DESCRIPTION_REMOVE . '/', '', $str);
        $str = $this->convert_from_utf8((string)$str);

        return $str;
    }

    /**
     * @param $thumb
     * @return bool|string
     */
    public function get_xml_url($thumb)
    {
        $str = $this->get_obj_property($thumb, 'watch_url');
        $str = (string)$str;

        return $str;
    }

    /**
     * @param $thumb
     * @return bool|string
     */
    public function get_xml_thumb($thumb)
    {
        $str = $this->get_obj_property($thumb, 'thumbnail_url');
        $str = (string)$str;

        return $str;
    }

    /**
     * @param $thumb
     * @return float|int
     */
    public function get_xml_duration($thumb)
    {
        $str = $this->get_obj_property($thumb, 'length');
        $arr = explode(':', $str);
        $ret = ($arr[0] * 60) + $arr[1];

        return $ret;
    }

    /**
     * @param $thumb
     * @return array|bool
     */
    public function get_xml_tags($thumb)
    {
        $tags = $this->get_obj_property($thumb, 'tags');
        $arr = $this->get_obj_property($tags, 'tag');
        $arr = $this->obj_array_to_str_array($arr);
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
        $url = 'http://ext.nicovideo.jp/thumb_watch/' . $src . '?w=' . $width . '&h=' . $height;
        $str = $this->build_script_begin($url);
        $str .= '<!--so.addParam("wmode", "transparent");-->';
        $str .= $this->build_script_end();

        return $str;
    }

    // --- class end ---
}
