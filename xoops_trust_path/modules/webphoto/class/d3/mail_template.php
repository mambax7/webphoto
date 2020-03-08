<?php
// $Id: mail_template.php,v 1.3 2010/04/22 03:50:48 ohwada Exp $

//=========================================================
// webphoto module
// 2009-12-06 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-04-22 K.OHWADA
// get_template_file()
// 2010-02-01 K.OHWADA
// replace_str_by_tags()
//---------------------------------------------------------

//=========================================================
// class webphoto_d3_mail_template
// referrence: kernel/notification.php
//=========================================================

/**
 * Class webphoto_d3_mail_template
 */
class webphoto_d3_mail_template
{
    public $_DIRNAME;
    public $_TRUST_DIRNAME;
    public $_MODULE_DIR;
    public $_MODULE_URL;
    public $_TRUST_DIR;
    public $_PRELOAD_DIR;
    public $_SITE_URL;
    public $_UNSUBSCRIBE_URL;

    public $_xoops_language;
    public $_xoops_sitename;
    public $_xoops_adminmail;
    public $_xoops_module_name;

    public $_tag_array = [];

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_d3_mail_template constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        $this->_DIRNAME = $dirname;
        $this->_TRUST_DIRNAME = $trust_dirname;
        $this->_MODULE_DIR = XOOPS_ROOT_PATH . '/modules/' . $dirname;
        $this->_MODULE_URL = XOOPS_URL . '/modules/' . $dirname;
        $this->_TRUST_DIR = XOOPS_TRUST_PATH . '/modules/' . $trust_dirname;
        $this->_PRELOAD_DIR = $this->_MODULE_DIR . '/preload';

        $this->_SITE_URL = XOOPS_URL . '/';
        $this->_UNSUBSCRIBE_URL = XOOPS_URL . '/notifications.php';

        $this->_xoops_language = $this->get_xoops_language();
        $this->_xoops_sitename = $this->get_xoops_sitename();
        $this->_xoops_adminmail = $this->get_xoops_adminmail();
        $this->_xoops_module_name = $this->get_xoops_module_name();
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_d3_mail_template
     */
    public static function getInstance($dirname = null, $trust_dirname = null)
    {
        static $instance;
        if (null === $instance) {
            $instance = new self($dirname, $trust_dirname);
        }

        return $instance;
    }

    //-------------------------------------------------------------------
    // get_dir_mail_template
    //-------------------------------------------------------------------

    /**
     * @param $file
     * @return bool|string
     */
    public function get_template_file($file)
    {
        $template_file = $this->_PRELOAD_DIR . '/' . $file;
        if (file_exists($template_file)) {
            return $template_file;
        }

        $dir = $this->get_dir_mail_template($file);
        if ($dir) {
            return $dir . $file;
        }

        return false;
    }

    /**
     * @param $file
     * @return bool|string
     */
    public function get_dir_mail_template($file)
    {
        $dir_trust_lang = $this->build_dir($this->_TRUST_DIR, $this->_xoops_language);
        $dir_trust_eng = $this->build_dir($this->_TRUST_DIR, 'english');
        $dir_root_lang = $this->build_dir($this->_MODULE_DIR, $this->_xoops_language);

        if (file_exists($dir_root_lang . $file)) {
            return $dir_root_lang;
        } elseif (file_exists($dir_trust_lang . $file)) {
            return $dir_trust_lang;
        } elseif (file_exists($dir_trust_eng . $file)) {
            return $dir_trust_eng;
        }

        return false;
    }

    /**
     * @param $dir
     * @param $lang
     * @return string
     */
    public function build_dir($dir, $lang)
    {
        $str = $dir . '/language/' . $lang . '/mail_template/';

        return $str;
    }

    /**
     * @return string
     */
    public function build_dir_preload()
    {
        $str = $dir . '/language/' . $lang . '/mail_template/';

        return $str;
    }

    //---------------------------------------------------------
    // read template file
    //---------------------------------------------------------

    /**
     * @param $file
     * @return mixed
     */
    public function replace_tag_array_by_template($file)
    {
        return $this->replace_tag_array($this->read_template($file));
    }

    /**
     * @param $file
     * @return bool|string
     */
    public function read_template($file)
    {
        $temp_file = $this->get_template_file($file);
        if ($temp_file) {
            return $this->read_file($temp_file);
        }

        return false;
    }

    /**
     * @param $file
     * @return bool|string
     */
    public function read_file($file)
    {
        $fp = fopen($file, 'rb');
        if ($fp) {
            $ret = fread($fp, filesize($file));

            return $ret;
        }

        return false;
    }

    //---------------------------------------------------------
    // assign tags
    //---------------------------------------------------------
    public function init_tag_array()
    {
        $this->assign('X_SITEURL', $this->_SITE_URL);
        $this->assign('X_SITENAME', $this->_xoops_sitename);
        $this->assign('X_ADMINMAIL', $this->_xoops_adminmail);
        $this->assign('X_MODULE', $this->_xoops_module_name);
        $this->assign('X_MODULE_URL', $this->_MODULE_URL);
        $this->assign('X_UNSUBSCRIBE_URL', $this->_UNSUBSCRIBE_URL);
    }

    /**
     * @param $tags
     */
    public function merge_tag_array($tags)
    {
        if (is_array($tags)) {
            $this->_tag_array = array_merge($this->_tag_array, $tags);
        }
    }

    /**
     * @param      $tag
     * @param null $value
     */
    public function assign($tag, $value = null)
    {
        if (is_array($tag)) {
            foreach ($tag as $k => $v) {
                $this->assign($k, $v);
            }
        } else {
            if (!empty($tag) && null !== $value) {
                $tag = mb_strtoupper(trim($tag));
                $this->_tag_array[$tag] = $value;
            }
        }
    }

    /**
     * @param $str
     * @return mixed
     */
    public function replace_tag_array($str)
    {
        return $this->replace_str_by_tags($str, $this->_tag_array);
    }

    /**
     * @param $str
     * @param $tags
     * @return mixed
     */
    public function replace_str_by_tags($str, $tags)
    {
        foreach ($tags as $k => $v) {
            $str = str_replace('{' . $k . '}', $v, $str);
        }

        return $str;
    }

    //---------------------------------------------------------
    // XOOPS system
    //---------------------------------------------------------

    /**
     * @return mixed
     */
    public function get_xoops_language()
    {
        global $xoopsConfig;

        return $xoopsConfig['language'];
    }

    /**
     * @return mixed
     */
    public function get_xoops_sitename()
    {
        global $xoopsConfig;

        return $xoopsConfig['sitename'];
    }

    /**
     * @return mixed
     */
    public function get_xoops_adminmail()
    {
        global $xoopsConfig;

        return $xoopsConfig['adminmail'];
    }

    /**
     * @param string $format
     * @return bool
     */
    public function get_xoops_module_name($format = 'n')
    {
        $name = false;
        $moduleHandler = xoops_getHandler('module');
        $module = $moduleHandler->getByDirname($this->_DIRNAME);
        if (is_object($module)) {
            $name = $module->getVar('name', $format);
        }

        return $name;
    }

    // --- class end ---
}
