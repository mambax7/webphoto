<?php
// $Id: ameba.php,v 1.1 2010/06/16 22:46:22 ohwada Exp $

//=========================================================
// webphoto module
// 2010-06-06 K.OHWADA
//=========================================================

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_embed_ameba
//
// http://vision.ameba.jp/watch.do?movie=1726761;
//
// <script language="JavaScript" type="text/JavaScript"
// src="http://visionmovie.ameba.jp/mcj.php?id=XXX&width=320&height=240&skin=gray"></script>
//
// <meta name="keywords" content="ピグ,裏技,透明人間,動画" />
//
//=========================================================

class webphoto_embed_ameba extends webphoto_embed_base
{

    // this word is written by UTF-8
    public $_TAGS_REMOVE = array('動画');

    public function __construct()
    {
        parent::__construct('ameba');
        $this->set_url('http://vision.ameba.jp/watch.do?movie=');
        $this->set_sample('1726761');
    }

    public function embed($src, $width, $height)
    {
        $item = $this->get_xml_item($src);
        if (!is_object($item)) {
            return false;
        }

        $url = $this->get_xml_script_src($item);
        if (empty($url)) {
            return false;
        }

        $str = $this->build_embed_script($url, $width, $height);
        return $str;
    }

    public function link($src)
    {
        return $this->build_link($src);
    }

    public function width()
    {
        return 320;
    }

    public function height()
    {
        return 240;
    }

    public function desc()
    {
        return $this->build_desc();
    }

    //---------------------------------------------------------
    // xml
    //---------------------------------------------------------
    public function support_params()
    {
        return $this->build_support_params();
    }

    public function get_xml_params($src)
    {
        $item = $this->get_xml_item($src);
        if (!is_object($item)) {
            return false;
        }

        $arr = array(
            'title'       => $this->get_xml_title($item),
            'description' => $this->get_xml_description($item),
            'url'         => $this->get_xml_url($item),
            'thumb'       => $this->get_xml_thumb($item),
            'duration'    => $this->get_xml_duration($item),
            'tags'        => $this->get_xml_tags($src),
            'script'      => $this->get_xml_script($item),
        );
        return $arr;
    }

    public function get_xml_item($src)
    {
        $url  = 'http://vision.ameba.jp/api/get/detailMovie.do?movie=' . $src;
        $cont = $this->get_remote_file($url);
        if (empty($cont)) {
            return false;
        }

        $xml   = $this->get_simplexml($cont);
        $error = trim($this->get_obj_property($xml, 'error'));
        if ($error) {
            return false;
        }

        $item = $this->get_obj_property($xml, 'item');
        return $item;
    }

    public function get_xml_title($item)
    {
        $str = $this->get_obj_property($item, 'title');
        $str = $this->convert_from_utf8((string)$str);
        return $str;
    }

    public function get_xml_description($item)
    {
        $str = $this->get_obj_property($item, 'description');
        $str = $this->convert_from_utf8((string)$str);
        return $str;
    }

    public function get_xml_url($item)
    {
        $str = $this->get_obj_property($item, 'link');
        $str = (string)$str;
        return $str;
    }

    public function get_xml_thumb($item)
    {
        $str = $this->get_obj_property($item, 'imageUrlLarge');
        $str = (string)$str;
        return $str;
    }

    public function get_xml_duration($item)
    {
        $str = $this->get_obj_property($item, 'playTimeSecond');
        $arr = explode(':', $str);
        if (!isset($arr[1])) {
            return false;
        }
        $ret = ($arr[0] * 60) + $arr[1];
        return $ret;
    }

    public function get_xml_tags($src)
    {
        $url  = $this->build_link($src);
        $tags = $this->get_remote_meta_tags($url);
        if (!isset($tags['keywords'])) {
            return false;
        }

        $str = $tags['keywords'];
        $arr = $this->str_to_array($str, ',');
        $arr = $this->array_remove($arr, $this->_TAGS_REMOVE);
        $arr = $this->convert_array_from_utf8($arr);
        return $arr;
    }

    public function get_xml_script($item)
    {
        $url = $this->get_xml_script_src($item);
        if (empty($url)) {
            return false;
        }

        $str = $this->build_embed_script_with_repalce($url);
        return $str;
    }

    public function get_xml_script_src($item)
    {
        $player = $this->get_obj_property($item, 'player');
        $script = $this->get_obj_property($player, 'script');
        $str    = $this->get_obj_attributes($script, 'src');
        $str    = (string)$str;
        return $str;
    }

    public function build_embed_script($src, $width, $height)
    {
        $url = $src . '&width=' . $width . '&height=' . $height . '&skin=gray';
        $str = $this->build_script($url);
        return $str;
    }

    // --- class end ---
}
