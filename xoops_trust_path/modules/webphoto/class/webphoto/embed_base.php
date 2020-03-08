<?php
// $Id: embed_base.php,v 1.4 2010/06/16 22:24:47 ohwada Exp $

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
// 2008-11-08 K.OHWADA
// BUG: forget to close height
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_embed_base
//=========================================================

/**
 * Class webphoto_embed_base
 */
class webphoto_embed_base
{
    public $_snoopy_class;

    public $_param = null;
    public $_url_head = null;
    public $_url_tail = null;
    public $_sample = null;
    public $_tmp_dir = null;

    public $_TYPE = null;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_embed_base constructor.
     * @param $type
     */
    public function __construct($type)
    {
        $this->_TYPE = $type;

        $this->_snoopy_class = new Snoopy();
    }

    //---------------------------------------------------------
    // interface
    //---------------------------------------------------------

    /**
     * @param      $src
     * @param      $width
     * @param      $height
     * @param null $extra
     */
    public function embed($src, $width, $height, $extra = null)
    {
        return null;
    }

    /**
     * @param $src
     */
    public function link($src)
    {
        return null;
    }

    /**
     * @param $src
     */
    public function thumb($src)
    {
        return null;
    }

    public function desc()
    {
        return null;
    }

    public function lang_desc()
    {
        return null;
    }

    /**
     * @return int
     */
    public function width()
    {
        return 0;
    }

    /**
     * @return int
     */
    public function height()
    {
        return 0;
    }

    public function support_params()
    {
        return null;
    }

    /**
     * @param $src
     */
    public function get_xml_params($src)
    {
        return null;
    }

    /**
     * @param $src
     * @param $width
     * @param $height
     */
    public function build_embed_script($src, $width, $height)
    {
        return null;
    }

    //---------------------------------------------------------
    // set param
    //---------------------------------------------------------

    /**
     * @param $val
     */
    public function set_param($val)
    {
        $this->_param = $val;
    }

    /**
     * @param $name
     * @return bool
     */
    public function get_param($name)
    {
        if (isset($this->_param[$name])) {
            return $this->_param[$name];
        }

        return false;
    }

    /**
     * @param        $head
     * @param string $tail
     */
    public function set_url($head, $tail = '')
    {
        $this->_url_head = $head;
        if ($tail) {
            $this->_url_tail = $tail;
        }
    }

    /**
     * @param $sample
     */
    public function set_sample($sample)
    {
        $this->_sample = $sample;
    }

    /**
     * @param $val
     */
    public function set_tmp_dir($val)
    {
        $this->_tmp_dir = $val;
    }

    //---------------------------------------------------------
    // build
    //---------------------------------------------------------

    /**
     * @param      $width
     * @param      $height
     * @param null $extra
     * @return string
     */
    public function build_object_begin($width, $height, $extra = null)
    {
        // BUG: forget to close height
        $str = '<object width="' . $width . '" height="' . $height . '" ' . $extra . ' >' . "\n";

        return $str;
    }

    /**
     * @return string
     */
    public function build_object_end()
    {
        $str = "</object>\n";

        return $str;
    }

    /**
     * @param $name
     * @param $value
     * @return string
     */
    public function build_param($name, $value)
    {
        $str = '<param name="' . $name . '" value="' . $value . '" >' . "\n";

        return $str;
    }

    /**
     * @param      $src
     * @param      $width
     * @param      $height
     * @param null $extra
     * @return string
     */
    public function build_embed_flash($src, $width, $height, $extra = null)
    {
        $str = '<embed src="' . $src . '" width="' . $width . '" height="' . $height . '" ' . $extra . ' type="application/x-shockwave-flash" >' . "\n";

        return $str;
    }

    /**
     * @param      $src
     * @param null $extra
     * @return string
     */
    public function build_script($src, $extra = null)
    {
        $str = $this->build_script_begin($src, $extra);
        $str .= $this->build_script_end();

        return $str;
    }

    /**
     * @param      $src
     * @param null $extra
     * @return string
     */
    public function build_script_begin($src, $extra = null)
    {
        $str = '<script language="JavaScript" type="text/JavaScript" src="' . $src . '" ' . $extra . ' >';

        return $str;
    }

    /**
     * @return string
     */
    public function build_script_end()
    {
        $str = '</script>';

        return $str;
    }

    /**
     * @param $src
     * @return string
     */
    public function build_link($src)
    {
        $str = $this->_url_head . $src . $this->_url_tail;

        return $str;
    }

    /**
     * @return string
     */
    public function build_desc()
    {
        return $this->build_desc_span($this->_url_head, $this->_sample, $this->_url_tail);
    }

    /**
     * @param      $head
     * @param      $sample
     * @param null $tail
     * @return string
     */
    public function build_desc_span($head, $sample, $tail = null)
    {
        $str = $head . '<span style="color: #FF0000;">' . $sample . '</span>' . $tail;

        return $str;
    }

    /**
     * @param $str
     * @return mixed
     */
    public function build_lang_desc($str)
    {
        $cont_name = mb_strtoupper('_WEBPHOTO_EXTERNEL_' . $this->_TYPE);
        if (defined($cont_name)) {
            $str = constant($cont_name);
        }

        return $str;
    }

    /**
     * @param $str
     * @return null|string|string[]
     */
    public function replace_width_height($str)
    {
        $replacement_width = 'width="' . _C_WEBPHOTO_EMBED_REPLACE_WIDTH . '"';
        $replacement_height = 'height="' . _C_WEBPHOTO_EMBED_REPLACE_HEIGHT . '"';

        $str = preg_replace('/width="\d+"/', $replacement_width, $str);
        $str = preg_replace('/height="\d+"/', $replacement_height, $str);

        return $str;
    }

    /**
     * @param $src
     */
    public function build_embed_script_with_repalce($src)
    {
        $str = $this->build_embed_script($src, _C_WEBPHOTO_EMBED_REPLACE_WIDTH, _C_WEBPHOTO_EMBED_REPLACE_HEIGHT);

        return $str;
    }

    /**
     * @return array
     */
    public function build_support_params()
    {
        $arr = [
            'title' => true,
            'description' => true,
            'url' => true,
            'thumb' => true,
            'duration' => true,
            'tags' => true,
            'script' => true,
        ];

        return $arr;
    }

    //---------------------------------------------------------
    // Simple XML
    //---------------------------------------------------------

    /**
     * @param $cont
     * @return bool|\SimpleXMLElement
     */
    public function get_simplexml($cont)
    {
        if (function_exists('simplexml_load_string')) {
            return simplexml_load_string($cont, 'SimpleXMLElement', LIBXML_NOCDATA);
        }

        return false;
    }

    /**
     * @param $obj
     * @param $key
     * @return bool
     */
    public function get_xpath($obj, $key)
    {
        if (is_object($obj)) {
            $xpath = $obj->xpath($key);
            if (isset($xpath[0])) {
                return $xpath[0];
            }
        }

        return false;
    }

    /**
     * @param $obj
     * @param $key
     * @return bool
     */
    public function get_obj_method($obj, $key)
    {
        if (is_object($obj) && method_exists($obj, $key)) {
            return $obj->$key();
        }

        return false;
    }

    /**
     * @param $obj
     * @param $key
     * @return bool
     */
    public function get_obj_property($obj, $key)
    {
        if (is_object($obj) && property_exists($obj, $key)) {
            return $obj->$key;
        }

        return false;
    }

    /**
     * @param $obj
     * @param $key
     * @return bool
     */
    public function get_obj_attributes($obj, $key)
    {
        $attr = $this->get_obj_method($obj, 'attributes');
        $str = $this->get_obj_property($attr, $key);

        return $str;
    }

    //---------------------------------------------------------
    // snoopy
    //---------------------------------------------------------

    /**
     * @param $url
     * @return bool|string
     */
    public function get_remote_file($url)
    {
        if ($this->_snoopy_class->fetch($url)) {
            return $this->_snoopy_class->results;
        }

        return false;
    }

    /**
     * @param $url
     * @return array|bool
     */
    public function get_remote_meta_tags($url)
    {
        $cont = $this->get_remote_file($url);
        if (empty($cont)) {
            return false;
        }

        $file = $this->build_tmp_file_name();
        $ret = $this->write_file($file, $cont);
        if (!$ret) {
            return false;
        }

        $tags = get_meta_tags($file);
        $this->unlink_file($file);

        return $tags;
    }

    //---------------------------------------------------------
    // file
    //---------------------------------------------------------

    /**
     * @return string
     */
    public function build_tmp_file_name()
    {
        $str = $this->_tmp_dir . '/' . uniqid('embed');

        return $str;
    }

    /**
     * @param $file
     * @param $text
     * @return bool|int
     */
    public function write_file($file, $text)
    {
        if (function_exists('file_put_contents')) {
            return file_put_contents($file, $text);
        }

        return false;
    }

    /**
     * @param $file
     * @return bool
     */
    public function unlink_file($file)
    {
        if (file_exists($file)) {
            return unlink($file);
        }

        return false;
    }

    //---------------------------------------------------------
    // multibyte
    //---------------------------------------------------------

    /**
     * @param $str
     * @return null|string|string[]
     */
    public function convert_from_utf8($str)
    {
        return $this->convert_encoding($str, _CHARSET, 'UTF-8');
    }

    /**
     * @param $arr
     * @return array
     */
    public function convert_array_from_utf8($arr)
    {
        $ret = [];
        foreach ($arr as $v) {
            $ret[] = $this->convert_from_utf8($v);
        }

        return $ret;
    }

    /**
     * @param $str
     * @param $to
     * @param $from
     * @return null|string|string[]
     */
    public function convert_encoding($str, $to, $from)
    {
        if (mb_strtolower($to) == mb_strtolower($from)) {
            return $str;
        }
        if (function_exists('iconv')) {
            return iconv($from, $to . '//IGNORE', $str);
        }
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($str, $to, $from);
        }

        return $str;
    }

    //---------------------------------------------------------
    // utility
    //---------------------------------------------------------

    /**
     * @param $str
     * @param $pattern
     * @return array
     */
    public function str_to_array($str, $pattern)
    {
        $arr1 = explode($pattern, $str);
        $arr2 = [];
        foreach ($arr1 as $v) {
            $v = trim($v);
            if ('' == $v) {
                continue;
            }
            $arr2[] = $v;
        }

        return $arr2;
    }

    /**
     * @param $arr1
     * @param $arr2
     * @return array
     */
    public function array_remove($arr1, $arr2)
    {
        if (!is_array($arr1) || !count($arr1)) {
            return $arr1;
        }
        if (!is_array($arr2) || !count($arr2)) {
            return $arr1;
        }

        $ret = [];
        foreach ($arr1 as $a) {
            if (!in_array($a, $arr2)) {
                $ret[] = $a;
            }
        }

        return $ret;
    }

    /**
     * @param $arr
     * @return array
     */
    public function obj_array_to_str_array($arr)
    {
        $ret = [];
        foreach ($arr as $a) {
            $ret[] = (string)$a;
        }

        return $ret;
    }

    // --- class end ---
}
