<?php
// $Id: weblinks.php,v 1.8 2011/12/30 00:07:33 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-25 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-12-29 K.OHWADA
// change photos()
// 2011-06-04 K.OHWADA
// webphoto_inc_uri
// 2010-01-10 K.OHWADA
// BUG: Fatal error: Call to undefined function: getinstance()
// 2009-11-11 K.OHWADA
// WEBPHOTO_TRUST_DIRNAME in webphoto_inc_catlist
// 2009-04-18 K.OHWADA
// Notice [PHP]: Undefined index: use_pathinfo
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_inc_weblinks
//=========================================================

/**
 * Class webphoto_inc_weblinks
 */
class webphoto_inc_weblinks extends webphoto_inc_public
{
    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return \webphoto_inc_weblinks
     */
    public static function getInstance()
    {
        static $instance;
        if (null === $instance) {
            $instance = new self();
        }

        return $instance;
    }

    //---------------------------------------------------------
    // public
    //---------------------------------------------------------

    /**
     * @param $opts
     */
    public function albums($opts)
    {
        $dirname = isset($opts['dirname']) ? $opts['dirname'] : '';

        if (empty($dirname)) {
            return null;
        }

        $inc_class = webphoto_inc_catlist::getSingleton($dirname, WEBPHOTO_TRUST_DIRNAME);

        return $inc_class->get_cat_titles();
    }

    /**
     * @param $opts
     * @return array|null
     */
    public function photos($opts)
    {
        $dirname = isset($opts['dirname']) ? $opts['dirname'] : '';
        $width = isset($opts['width']) ? (int)$opts['width'] : 140;
        $album_limit = isset($opts['album_limit']) ? (int)$opts['album_limit'] : 1;
        $album_id = isset($opts['album_id']) ? (int)$opts['album_id'] : 0;
        $mode_sub = isset($opts['mode_sub']) ? (int)$opts['mode_sub'] : 1;
        $cycle = isset($opts['cycle']) ? (int)$opts['cycle'] : 60;
        $cols = isset($opts['cols']) ? (int)$opts['cols'] : 3;
        $title_max = isset($opts['title_max']) ? (int)$opts['title_max'] : 20;

        if (empty($dirname)) {
            return null;
        }

        $cache_time = 0;
        $disable_renderer = true;

        $options = [
            0 => $dirname,      // dirname
            1 => $album_limit,  // photos_num
            2 => $album_id,     // cat_limitation
            3 => $mode_sub,     // cat_limit_recursive
            4 => $title_max,    // title_max_length
            5 => $cols,         // cols
            6 => $cache_time,   // cache_time
            'disable_renderer' => $disable_renderer,
        ];

        $uri_class = webphoto_inc_uri::getSingleton($dirname);

        // BUG: Fatal error: Call to undefined function: getinstance()
        $inc_class = webphoto_inc_blocks::getSingleton($dirname, WEBPHOTO_TRUST_DIRNAME);

        $block = $inc_class->rphoto_show($options);

        if (!is_array($block) || !count($block)) {
            return null;
        }

        if (!is_array($block['photo']) || !count($block['photo'])) {
            return null;
        }

        $href_base = XOOPS_URL . '/modules/' . $dirname . '/';

        $ret = [];
        foreach ($block['photo'] as $photo) {
            $href = $href_base;
            $href .= $uri_class->build_relatevie_uri_mode_param('photo', $photo['item_id']);

            $cat_href = $href_base;
            $cat_href .= $uri_class->build_relatevie_uri_mode_param('category', $photo['item_cat_id']);

            if ($photo['img_thumb_width'] && $photo['img_thumb_height']) {
                $img_attribs = 'width="' . $photo['img_thumb_width'] . '" height="' . $photo['img_thumb_height'] . '"';
            } else {
                $img_attribs = 'width="' . $block['cfg_thumb_width'] . '"';
            }

            $ret[] = [
                'href' => $href,
                'cat_href' => $cat_href,
                'img_attribs' => $img_attribs,
                'title' => $photo['title_s'],
                'cat_title' => $photo['cat_title_s'],
                'img_src' => $photo['img_thumb_src_s'],
            ];
        }

        return $ret;
    }

    // --- class end ---
}
