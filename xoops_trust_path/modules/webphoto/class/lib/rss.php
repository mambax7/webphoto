<?php
// $Id: rss.php,v 1.5 2009/03/06 04:11:37 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-03-01 K.OHWADA
// view_rss()
// 2008-12-09 K.OHWADA
// $cache_id in clear_compiled_tpl()
// 2008-10-01 K.OHWADA
// used webphoto_lib_xml
// 2008-08-01 K.OHWADA
// used webphoto_lib_multibyte
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//---------------------------------------------------------
// http://search.yahoo.com/mrss/
//
// <media:content
//   url="http://www.foo.com/movie.mov"
//   fileSize="12216320"
//   type="video/quicktime"
//   medium="video"
//   isDefault="true"
//   expression="full"
//   bitrate="128"
//   framerate="25"
//   samplingrate="44.1"
//   channels="2"
//   duration="185"
//   height="200"
//   width="300"
//   lang="en" />
//
// <media:thumbnail
//   url="http://www.foo.com/keyframe.jpg"
//   width="75"
//   height="50"
//   time="12:05:01.123" />
//---------------------------------------------------------

//=========================================================
// class webphoto_lib_rss
//=========================================================
class webphoto_lib_rss extends webphoto_lib_xml
{
    public $_DIRNAME;
    public $_MODULE_PATH;
    public $_MODULE_URL;

    public $_MAX_SUMMARY = 500;

    public $_SITE_AUTHOR_NAME_UID     = 1;
    public $_SITE_AUTHOR_NAME_DEFAULT = 'xoops';
    public $_SITE_LOGO                = 'images/logo.gif';
    public $_CHANNEL_GENERATOR        = 'XOOPS webphoto';
    public $_CHANNEL_DOCS             = 'http://backend.userland.com/rss/';

    public $_MODULE_ID;
    public $_MODULE_NAME;

    public $_multibyte_class;

    public $_xoops_sitename;
    public $_xoops_adminmail;
    public $_xoops_slogan;
    public $_xoops_site_author_name;
    public $_is_module_admin;

    public $_template = null;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct($dirname)
    {
        parent::__construct();

        $this->_multibyte_class = webphoto_lib_multibyte::getInstance();

        $this->_DIRNAME     = $dirname;
        $this->_MODULE_PATH = XOOPS_ROOT_PATH . '/modules/' . $dirname;
        $this->_MODULE_URL  = XOOPS_URL . '/modules/' . $dirname;

        $this->_init_xoops_param();
    }

    public static function getInstance($dirname = null)
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new webphoto_lib_rss($dirname);
        }
        return $instance;
    }

    //---------------------------------------------------------
    // modify from backend.php
    //---------------------------------------------------------
    public function build_rss($cache_id = null, $cache_time = 3600)
    {
        $this->http_output('pass');

        header('Content-Type:text/xml; charset=utf-8');

        $tpl = new XoopsTpl();

        if ($cache_time > 0) {
            $tpl->xoops_setCaching(2);
            $tpl->xoops_setCacheTime($cache_time);
        }

        if (!$tpl->is_cached($this->_template, $cache_id) || ($cache_time == 0)) {
            $this->assign_tpl($tpl);
        }

        return $tpl->fetch($this->_template, $cache_id);
    }

    public function set_template($val)
    {
        $this->_template = $val;
    }

    //---------------------------------------------------------
    // template
    //---------------------------------------------------------
    public function assign_tpl(&$tpl)
    {
        $channel = $this->build_channel_array();
        $tpl->assign($this->utf8_array($channel));

        $items = $this->build_items();
        foreach ($items as $item) {
            $tpl->append('items', $this->utf8_array($item));
        }
    }

    //---------------------------------------------------------
    // channel
    //---------------------------------------------------------
    public function build_channel_array()
    {
        $image_url = XOOPS_URL . '/' . $this->_SITE_LOGO;

        $dimention = getimagesize(XOOPS_ROOT_PATH . '/' . $this->_SITE_LOGO);
        if (empty($dimention[0])) {
            $width = 88;
        } else {
            $width = ($dimention[0] > 144) ? 144 : $dimention[0];
        }
        if (empty($dimention[1])) {
            $height = 31;
        } else {
            $height = ($dimention[1] > 400) ? 400 : $dimention[1];
        }

        $xoops_sitename_xml     = $this->xml_text($this->_xoops_sitename);
        $xoops_slogan_xml       = $this->xml_text($this->_xoops_slogan);
        $xoops_module_name_xml  = $this->xml_text($this->_MODULE_NAME);
        $xoops_site_author_name = $this->_xoops_site_author_name;

        $webmaster     = $this->_xoops_adminmail . ' ( ' . $xoops_site_author_name . ' ) ';
        $webmaster_xml = $this->xml_url($webmaster);

        $link_xml  = $this->xml_url(XOOPS_URL . '/');
        $atom_link = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        $lastbuild = date('r', time());

        $arr = array(
            'channel_title'          => $xoops_sitename_xml,
            'channel_link'           => $link_xml,
            'channel_atom_link'      => $this->xml_url($atom_link),
            'channel_description'    => $xoops_slogan_xml,
            'channel_lastbuild'      => $this->xml_text($lastbuild),
            'channel_webmaster'      => $webmaster_xml,
            'channel_managingeditor' => $webmaster_xml,
            'channel_category'       => $xoops_module_name_xml,
            'channel_generator'      => $this->xml_text($this->_CHANNEL_GENERATOR),
            'channel_language'       => $this->xml_text(_LANGCODE),
            'channel_docs'           => $this->xml_url($this->_CHANNEL_DOCS),
            'image_url'              => $this->xml_url($image_url),
            'image_title'            => $xoops_sitename_xml,
            'image_link'             => $link_xml,
            'image_width'            => (int)$width,
            'image_height'           => (int)$height,
        );

        return $arr;
    }

    //---------------------------------------------------------
    // items
    //---------------------------------------------------------
    public function build_items()
    {
        // dummy
    }

    //---------------------------------------------------------
    // clear template
    //---------------------------------------------------------
    public function clear_compiled_tpl_for_admin($cache_id = null, $flag_msg = false)
    {
        if ($this->_is_module_admin) {
            $tpl = new XoopsTpl();
            $tpl->clear_compiled_tpl($this->_template);
            $tpl->clear_cache($this->_template, $cache_id);
            if ($flag_msg) {
                echo 'template cleared : ' . $this->_template;
            }
        }
    }

    public function clear_compiled_tpl($cache_id = null)
    {
        $tpl = new XoopsTpl();
        $tpl->clear_compiled_tpl($this->_template);
        $tpl->clear_cache($this->_template, $cache_id);
    }

    //---------------------------------------------------------
    // head
    //---------------------------------------------------------
    public function view_rss()
    {
        $this->http_output('pass');

        echo $this->build_header_content_type();
        echo $this->build_html_head();
        echo $this->build_html_body_begin();
        echo $this->build_view_rss();
        echo "<br /><hr />\n";
        echo $this->build_close_button();
        echo $this->build_html_body_end();
    }

    public function build_header_content_type()
    {
        header('Content-Type:text/html; charset=UTF-8');
    }

    public function build_html_head($title = null)
    {
        if (empty($title)) {
            $title = $this->utf8($this->_xoops_sitename . ' - ' . $this->_MODULE_NAME);
        }

        $str = '<html><head>' . "\n";
        $str .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />' . "\n";
        $str .= '<title>' . $this->sanitize($title) . '</title>' . "\n";
        $str .= '</head>' . "\n";
        return $str;
    }

    public function build_html_body_begin()
    {
        $str = '<body>' . "\n";
        return $str;
    }

    public function build_html_body_end()
    {
        $str = '</body></html>' . "\n";
        return $str;
    }

    public function build_close_button()
    {
        $str = '<input class="formButton" value="' . $this->utf8(_CLOSE) . '" type="button" onclick="javascript:window.close();" />';
        return $str;
    }

    public function build_view_rss($cache_id = null)
    {
        $tpl = new XoopsTpl();
        $this->assign_tpl($tpl);
        $str = $tpl->fetch($this->_template, $cache_id);
        return nl2br($this->sanitize($str));
    }

    //---------------------------------------------------------
    // multibyte
    //---------------------------------------------------------
    public function http_output($encoding)
    {
        return $this->_multibyte_class->m_mb_http_output($encoding);
    }

    public function utf8($str)
    {
        return $this->_multibyte_class->convert_to_utf8($str);
    }

    public function utf8_array($arr_in)
    {
        $arr = array();
        foreach ($arr_in as $k => $v) {
            $arr[$k] = $this->utf8($v);
        }
        return $arr;
    }

    //---------------------------------------------------------
    // xoops class
    //---------------------------------------------------------
    public function _init_xoops_param()
    {
        $xoops_class = webphoto_xoops_base::getInstance();

        $this->_xoops_language  = $xoops_class->get_config_by_name('language');
        $this->_xoops_sitename  = $xoops_class->get_config_by_name('sitename');
        $this->_xoops_slogan    = $xoops_class->get_config_by_name('slogan');
        $this->_is_module_admin = $xoops_class->get_my_user_is_module_admin();
        $this->_MODULE_ID       = $xoops_class->get_my_module_id();
        $this->_MODULE_NAME     = $xoops_class->get_my_module_name('n');

        $name = $xoops_class->get_user_uname_from_id($this->_SITE_AUTHOR_NAME_UID);
        if (empty($name)) {
            $name = $this->_SITE_AUTHOR_NAME_DEFAULT;
        }

        $this->_xoops_site_author_name = $name;
    }

    // --- class end ---
}
