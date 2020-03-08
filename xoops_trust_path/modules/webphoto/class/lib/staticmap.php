<?php
// $Id: staticmap.php,v 1.1 2009/09/19 20:45:27 ohwada Exp $

//=========================================================
// webphoto module
// 2009-09-20 K.OHWADA
//=========================================================

//---------------------------------------------------------
// http://code.google.com/intl/en/apis/maps/documentation/staticmaps/
// N903i 240?~270
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_lib_staticmap
//=========================================================

/**
 * Class webphoto_lib_staticmap
 */
class webphoto_lib_staticmap
{
    // map param
    public $_key = null;
    public $_width = 220;
    public $_height = 220;
    public $_maptype = 'mobile';
    public $_sanitize = true;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        // dummy
    }

    /**
     * @return \webphoto_lib_staticmap
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
    // build url
    //---------------------------------------------------------

    /**
     * @param $param
     * @return string
     */
    public function build_url($param)
    {
        $latitude = $param['latitude'];
        $longitude = $param['longitude'];
        $zoom = $param['zoom'];

        $key = isset($param['key']) ? $param['key'] : $this->_key;
        $maptype = isset($param['maptype']) ? $param['maptype'] : $this->_maptype;
        $markers = isset($param['markers']) ? $param['markers'] : null;
        $width = isset($param['width']) ? (int)$param['width'] : $this->_width;
        $height = isset($param['height']) ? (int)$param['height'] : $this->_height;
        $sanitize = isset($param['sanitize']) ? (bool)$param['sanitize'] : $this->_sanitize;

        $static_markers = $this->build_markers($markers);

        $str = 'http://maps.google.com/staticmap?';
        $str .= 'key=' . $key;
        $str .= '&center=' . $latitude . ',' . $longitude;
        $str .= '&zoom=' . $zoom;
        $str .= '&size=' . $width . 'x' . $height;
        $str .= '&maptype=' . $maptype;

        if ($static_markers) {
            $str .= '&markers=' . $static_markers;
        }

        if ($sanitize) {
            $str = $this->sanitize_url($str);
        }

        return $str;
    }

    /**
     * @param $markers
     * @return null|string
     */
    public function build_markers($markers)
    {
        if (!is_array($markers) || !count($markers)) {
            return null;
        }

        $arr = [];
        foreach ($markers as $marker) {
            $latitude = $marker['latitude'];
            $longitude = $marker['longitude'];
            $color = isset($marker['color']) ? $marker['color'] : null;
            $alpha = isset($marker['alpha']) ? $marker['alpha'] : null;

            $str = $latitude . ',' . $longitude;
            if ($color) {
                $str .= ',' . $color;
                if ($alpha) {
                    $str .= $alpha;
                }
            }

            $arr[] = $str;
        }

        return implode('|', $arr);
    }

    /**
     * @param $str
     * @return string
     */
    public function sanitize_url($str)
    {
        $str = str_replace('|', '%7C', $str);

        return htmlspecialchars($str, ENT_QUOTES);
    }

    //---------------------------------------------------------
    // set param
    //---------------------------------------------------------

    /**
     * @param $val
     */
    public function set_key($val)
    {
        $this->_key = $val;
    }

    /**
     * @param $val
     */
    public function set_maptype($val)
    {
        $this->_maptype = $val;
    }

    /**
     * @param $val
     */
    public function set_width($val)
    {
        $this->_width = (int)$val;
    }

    /**
     * @param $val
     */
    public function set_height($val)
    {
        $this->_height = (int)$val;
    }

    /**
     * @param $val
     */
    public function set_sanitize($val)
    {
        $this->_sanitize = (bool)$val;
    }

    // --- class end ---
}
