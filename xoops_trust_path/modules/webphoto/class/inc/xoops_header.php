<?php
// $Id: xoops_header.php,v 1.6 2011/11/04 03:52:14 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-11-03 K.OHWADA
// PopBox.js -> popbox/PopBox.js
// 2010-01-10 K.OHWADA
// build_envelop_css()
// 2009-01-25 K.OHWADA
// assign_or_check_gmap_api()
// 2008-12-12 K.OHWADA
// getInstance() -> getSingleton()
// 2008-07-01 K.OHWADA
// added $_XOOPS_MODULE_HADER
// assign_for_block() -> assign_or_get_popbox_js()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_inc_xoops_header
//=========================================================
//---------------------------------------------------------
// caller inc_blocks show_main
//---------------------------------------------------------

/**
 * Class webphoto_inc_xoops_header
 */
class webphoto_inc_xoops_header
{
    public $_DIRNAME;
    public $_MODULE_URL;
    public $_LIBS_URL;
    public $_POPBOX_URL;

    public $_XOOPS_MODULE_HADER = 'xoops_module_header';
    public $_BLOCK_POPBOX_JS = false;
    public $_LANG_POPBOX_REVERT = 'Click the image to shrink it.';

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_inc_xoops_header constructor.
     * @param $dirname
     */
    public function __construct($dirname)
    {
        $this->_DIRNAME = $dirname;
        $this->_MODULE_URL = XOOPS_URL . '/modules/' . $dirname;
        $this->_LIBS_URL = $this->_MODULE_URL . '/libs';
        $this->_POPBOX_URL = $this->_MODULE_URL . '/images/popbox';

        // preload
        if (defined('_C_WEBPHOTO_PRELOAD_XOOPS_MODULE_HEADER')) {
            $this->_XOOPS_MODULE_HADER = _C_WEBPHOTO_PRELOAD_XOOPS_MODULE_HEADER;
        }

        if (defined('_C_WEBPHOTO_PRELOAD_BLOCK_POPBOX_JS')) {
            $this->_BLOCK_POPBOX_JS = (bool)_C_WEBPHOTO_PRELOAD_BLOCK_POPBOX_JS;
        }
    }

    /**
     * @param $dirname
     * @return mixed
     */
    public static function getSingleton($dirname)
    {
        static $singletons;
        if (!isset($singletons[$dirname])) {
            $singletons[$dirname] = new self($dirname);
        }

        return $singletons[$dirname];
    }

    //--------------------------------------------------------
    // for block
    //--------------------------------------------------------

    /**
     * @param $lang_popbox_revert
     * @return null|string
     */
    public function assign_or_get_popbox_js($lang_popbox_revert)
    {
        if (!$this->check_popbox_js()) {
            return null;
        }

        $popbox_js = $this->build_popbox_js($lang_popbox_revert);

        if ($this->_BLOCK_POPBOX_JS) {
            return $popbox_js;
        }

        $this->assign_xoops_module_header($popbox_js);

        return null;
    }

    /**
     * @param $apikey
     * @return null|string
     */
    public function assign_or_get_gmap_api_js($apikey)
    {
        if (!$this->check_gmap_api()) {
            return null;
        }

        $api_js = $this->build_gmap_api($apikey);
        if ($this->_BLOCK_POPBOX_JS) {
            return $api_js;
        }

        $this->assign_xoops_module_header($api_js);

        return null;
    }

    /**
     * @return null|string
     */
    public function assign_or_get_gmap_block_js()
    {
        if (!$this->check_gmap_block_js()) {
            return null;
        }

        $block_js = $this->build_gmap_block_js();
        if ($this->_BLOCK_POPBOX_JS) {
            return $block_js;
        }

        $this->assign_xoops_module_header($block_js);

        return null;
    }

    //--------------------------------------------------------
    // for main
    //--------------------------------------------------------

    /**
     * @param $lang_popbox_revert
     * @return null|string
     */
    public function build_once_popbox_js($lang_popbox_revert)
    {
        if ($this->check_popbox_js()) {
            return $this->build_popbox_js($lang_popbox_revert);
        }

        return null;
    }

    /**
     * @return null|string
     */
    public function build_once_gmap_js()
    {
        if ($this->check_gmap_js()) {
            return $this->build_gmap_js();
        }

        return null;
    }

    /**
     * @return bool
     */
    public function check_gmap_js()
    {
        return $this->check_once($this->build_const_name('gmap_js'));
    }

    /**
     * @return bool
     */
    public function check_gmap_block_js()
    {
        return $this->check_once($this->build_const_name('gmap_block_js'));
    }

    /**
     * @return bool
     */
    public function check_popbox_js()
    {
        return $this->check_once($this->build_const_name('popbox_js'));
    }

    /**
     * @return string
     */
    public function build_gmap_js()
    {
        return $this->build_script_js_libs('gmap.js');
    }

    /**
     * @return string
     */
    public function build_gmap_block_js()
    {
        return $this->build_script_js_libs('gmap_block.js');
    }

    /**
     * @param null $lang_popbox_revert
     * @return string
     */
    public function build_popbox_js($lang_popbox_revert = null)
    {
        if (empty($lang_popbox_revert)) {
            $lang_popbox_revert = $this->_LANG_POPBOX_REVERT;
        }

        $text = '  popBoxRevertText    = "' . $lang_popbox_revert . '" ' . "\n";
        $text .= '  popBoxWaitImage.src = "' . $this->_POPBOX_URL . '/spinner40.gif" ' . "\n";
        $text .= '  popBoxRevertImage   = "' . $this->_POPBOX_URL . '/magminus.gif" ' . "\n";
        $text .= '  popBoxPopImage      = "' . $this->_POPBOX_URL . '/magplus.gif" ' . "\n";

        $str = $this->build_link_css_libs('popbox/Styles.css');
        $str .= $this->build_script_js_libs('popbox/PopBox.js');
        $str .= $this->build_envelop_js($text);

        return $str;
    }

    //--------------------------------------------------------
    // utility
    //--------------------------------------------------------

    /**
     * @param $name
     * @return string
     */
    public function build_const_name($name)
    {
        $str = mb_strtoupper('_C_WEBPHOTO_HEADER_LOADED_' . $name);

        return $str;
    }

    /**
     * @param $const_name
     * @return bool
     */
    public function check_once($const_name)
    {
        if (!defined($const_name)) {
            define($const_name, 1);

            return true;
        }

        return false;
    }

    /**
     * @param $css
     * @return string
     */
    public function build_link_css_libs($css)
    {
        return $this->build_link_css($this->_LIBS_URL . '/' . $css);
    }

    /**
     * @param $herf
     * @return string
     */
    public function build_link_css($herf)
    {
        $str = '<link rel="stylesheet" type="text/css" href="' . $herf . '" >' . "\n";

        return $str;
    }

    /**
     * @param $js
     * @return string
     */
    public function build_script_js_libs($js)
    {
        return $this->build_script_js($this->_LIBS_URL . '/' . $js);
    }

    /**
     * @param $src
     * @return string
     */
    public function build_script_js($src)
    {
        $str = '<script src="' . $src . '" type="text/javascript"></script>' . "\n";

        return $str;
    }

    /**
     * @param $url
     * @return string
     */
    public function build_link_rss($url)
    {
        $str = '<link rel="alternate" type="application/rss+xml" title="RSS" href="' . $url . '" >' . "\n";

        return $str;
    }

    /**
     * @param $text
     * @return string
     */
    public function build_envelop_js($text)
    {
        $ret = <<< EOF
<script type="text/javascript">
//<![CDATA[
$text
//]]>
</script>
EOF;

        return $ret;
    }

    /**
     * @param $text
     * @return string
     */
    public function build_envelop_css($text)
    {
        $ret = <<< EOF
<style type="text/css">
$text
</style>
EOF;

        return $ret;
    }

    //--------------------------------------------------------
    // template
    //--------------------------------------------------------
    // some block use xoops_module_header

    /**
     * @param $var
     */
    public function assign_xoops_module_header($var)
    {
        global $xoopsTpl;
        if ($var) {
            $xoopsTpl->assign($this->_XOOPS_MODULE_HADER, $var . "\n" . $this->get_xoops_module_header());
        }
    }

    /**
     * @return array
     */
    public function get_xoops_module_header()
    {
        global $xoopsTpl;

        return $xoopsTpl->get_template_vars($this->_XOOPS_MODULE_HADER);
    }

    //--------------------------------------------------------
    // common with weblinks
    //--------------------------------------------------------

    /**
     * @param $apikey
     * @return null|string
     */
    public function build_once_gmap_api($apikey)
    {
        return happy_linux_build_once_gmap_api($apikey);
    }

    /**
     * @return bool
     */
    public function check_gmap_api()
    {
        return happy_linux_check_once_gmap_api();
    }

    /**
     * @param $apikey
     * @return string
     */
    public function build_gmap_api($apikey)
    {
        return happy_linux_build_gmap_api($apikey);
    }

    // --- class end ---
}
