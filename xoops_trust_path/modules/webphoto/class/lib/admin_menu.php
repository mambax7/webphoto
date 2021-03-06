<?php
// $Id: admin_menu.php,v 1.4 2009/12/16 13:32:34 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//---------------------------------------------------------
// change log
// 2009-12-06 K.OHWADA
// $trust_dirname in webphoto_inc_admin_menu
// 2008-12-12 K.OHWADA
// getInstance() -> getSingleton()
// 2008-10-01 K.OHWADA
// build_sub_menu()
//---------------------------------------------------------

//=========================================================
// class webphoto_lib_admin_menu
// base on myalbum's mymenu.php
//=========================================================

/**
 * Class webphoto_lib_admin_menu
 */
class webphoto_lib_admin_menu
{
    public $_menu_class;

    public $_DIRNAME;
    public $_MODULE_URL;
    public $_MODULE_DIR;
    public $_TRUST_DIRNAME;
    public $_TRUST_DIR;
    public $_MODULE_ID = 0;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_lib_admin_menu constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        $this->_DIRNAME = $dirname;
        $this->_MODULE_URL = XOOPS_URL . '/modules/' . $dirname;
        $this->_MODULE_DIR = XOOPS_ROOT_PATH . '/modules/' . $dirname;

        $this->_TRUST_DIRNAME = $trust_dirname;
        $this->_TRUST_DIR = XOOPS_TRUST_PATH . '/modules/' . $trust_dirname;

        $this->_init_xoops_param();
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_lib_admin_menu
     */
    public static function getInstance($dirname = null, $trust_dirname = null)
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new self($dirname, $trust_dirname);
        }

        return $instance;
    }

    //---------------------------------------------------------
    // main
    //---------------------------------------------------------

    /**
     * @param bool $flag_sub
     * @return null|string
     */
    public function build_menu_with_sub($flag_sub = true)
    {
        $str = $this->build_menu(!$flag_sub, false);

        if ($flag_sub) {
            $str .= "<br>\n";
            $str .= $this->build_sub_menu(true, false);
        }

        $str .= $this->build_hr(true);

        return $str;
    }

    /**
     * @param bool $flag_default
     * @param bool $flag_hr
     * @return null|string
     */
    public function build_menu($flag_default = true, $flag_hr = true)
    {
        //  if( defined( 'XOOPS_ORETEKI' ) ) { return null; }

        $admin_menu_class = webphoto_inc_admin_menu::getSingleton($this->_DIRNAME, $this->_TRUST_DIRNAME);
        $admin_menu = $admin_menu_class->build_menu();

        $add_menu = $this->_build_additinal_menu();

        $menu_array = null;
        if (is_array($admin_menu) && count($admin_menu)
            && is_array($add_menu)
            && count($add_menu)) {
            $menu_array = array_merge($admin_menu, $add_menu);
        } elseif (is_array($admin_menu) && count($admin_menu)) {
            $menu_array = $admin_menu;
        } elseif (is_array($add_menu) && count($add_menu)) {
            $menu_array = $add_menu;
        }

        if (is_array($menu_array) && count($menu_array)) {
            $str = $this->_build_highlight($menu_array, $flag_default);
            $str .= $this->build_hr($flag_hr);

            return $str;
        }

        return null;
    }

    /**
     * @param bool $flag_default
     * @param bool $flag_hr
     * @return null|string
     */
    public function build_sub_menu($flag_default = true, $flag_hr = true)
    {
        $admin_menu_class = webphoto_inc_admin_menu::getSingleton($this->_DIRNAME, $this->_TRUST_DIRNAME);
        $menu_array = $admin_menu_class->build_sub_menu();

        if (is_array($menu_array) && count($menu_array)) {
            $str = $this->_build_highlight($menu_array, $flag_default);
            $str .= $this->build_hr($flag_hr);

            return $str;
        }

        return null;
    }

    /**
     * @param bool $flag_hr
     * @return null|string
     */
    public function build_hr($flag_hr = true)
    {
        if ($flag_hr) {
            $str = "<hr style='display:block;' >\n";

            return $str;
        }

        return null;
    }

    /**
     * @return array
     */
    public function _build_additinal_menu()
    {
        // with XOOPS_TRUST_PATH and altsys

        $flag_preferences = false;

        $menu_array = [];

        if ($this->is_installed_altsys()) {
            // mytplsadmin (TODO check if this module has tplfile)
            if (file_exists(XOOPS_TRUST_PATH . '/libs/altsys/mytplsadmin.php')) {
                array_push($menu_array, [
                    'title' => $this->get_title('tplsadmin'),
                    'link' => 'admin/index.php?mode=admin&lib=altsys&page=mytplsadmin',
                ]);
            }

            // myblocksadmin
            if (file_exists(XOOPS_TRUST_PATH . '/libs/altsys/myblocksadmin.php')) {
                array_push($menu_array, [
                    'title' => $this->get_title('blocksadmin'),
                    'link' => 'admin/index.php?mode=admin&lib=altsys&page=myblocksadmin',
                ]);
            }

            // mypreferences
            if ($this->has_xoops_config_this_module()) {
                if (file_exists(XOOPS_TRUST_PATH . '/libs/altsys/mypreferences.php')) {
                    $flag_preferences = true;
                    array_push($menu_array, [
                        'title' => _PREFERENCES,
                        'link' => 'admin/index.php?mode=admin&lib=altsys&page=mypreferences',
                    ]);
                }
            }
        }

        // preferences
        if (!$flag_preferences && $this->has_xoops_config_this_module()) {
            // XOOPS Cube 2.1
            if (defined('XOOPS_CUBE_LEGACY')) {
                $link = XOOPS_URL . '/modules/legacy/admin/index.php?action=PreferenceEdit&confmod_id=' . $this->_MODULE_ID;

            // XOOPS 2.0
            } else {
                $link = XOOPS_URL . '/modules/system/admin.php?fct=preferences&op=showmod&mod=' . $this->_MODULE_ID;
            }

            array_push($menu_array, [
                'title' => _PREFERENCES,
                'link' => $link,
            ]);
        }

        array_push($menu_array, [
            'title' => $this->get_title('goto_module'),
            'link' => 'index.php',
        ]);

        return $menu_array;
    }

    /**
     * @param      $menu_array
     * @param bool $flag_default
     * @return string
     */
    public function _build_highlight($menu_array, $flag_default = true)
    {
        $mymenu_uri = $_SERVER['REQUEST_URI'];
        $mymenu_link = mb_substr(mb_strstr($mymenu_uri, '/admin/'), 1);

        $flag_highlight = false;

        // set gray all
        foreach (array_keys($menu_array) as $i) {
            $menu_array[$i]['color'] = '#DDDDDD';
        }

        $post_fct = isset($_POST['fct']) ? $_POST['fct'] : null;
        $fct = preg_replace('/[^a-zA-Z0-9_-]/', '', $post_fct);

        // highlight
        if ($fct) {
            $uri_fct = $mymenu_link . '?fct=' . $fct;
            foreach (array_keys($menu_array) as $i) {
                if ($uri_fct == $menu_array[$i]['link']) {
                    $menu_array[$i]['color'] = '#FFCCCC';
                    $flag_highlight = true;
                    break;
                }
            }
        }

        if ($fct && !$flag_highlight) {
            $uri_fct = $mymenu_uri . '?fct=' . $fct;
            foreach (array_keys($menu_array) as $i) {
                if (mb_stristr($uri_fct, $menu_array[$i]['link'])) {
                    $menu_array[$i]['color'] = '#FFCCCC';
                    $flag_highlight = true;
                    break;
                }
            }
        }

        if (!$flag_highlight) {
            foreach (array_keys($menu_array) as $i) {
                if ($mymenu_link == $menu_array[$i]['link']) {
                    $menu_array[$i]['color'] = '#FFCCCC';
                    $flag_highlight = true;
                    break;
                }
            }
        }

        if (!$flag_highlight) {
            foreach (array_keys($menu_array) as $i) {
                $link = $menu_array[$i]['link'];
                if ('admin/index.php' != $link
                    && 0 === mb_strpos($mymenu_link, $link)) {
                    $menu_array[$i]['color'] = '#FFCCCC';
                    $flag_highlight = true;
                    break;
                }
            }
        }

        if (!$flag_highlight && $flag_default) {
            foreach (array_keys($menu_array) as $i) {
                if (mb_stristr($mymenu_uri, $menu_array[$i]['link'])) {
                    $menu_array[$i]['color'] = '#FFCCCC';
                    break;
                }
            }
        }

        // link conversion from relative to absolute
        foreach (array_keys($menu_array) as $i) {
            if (false === mb_stristr($menu_array[$i]['link'], XOOPS_URL)) {
                $menu_array[$i]['link'] = $this->_MODULE_URL . '/' . $menu_array[$i]['link'];
            }
        }

        // display
        $text = "<div style='text-align:left;width:98%;'>\n";

        foreach ($menu_array as $menuitem) {
            $text .= "<div style='float:left;height:1.5em;'><nobr>";
            $text .= "<a href='" . $this->sanitize($menuitem['link']) . "' style='background-color:" . $menuitem['color'] . ";font:normal normal bold 9pt/12pt;'>";
            $text .= $this->sanitize($menuitem['title']);
            $text .= "</a> | </nobr></div>\n";
        }

        $text .= "</div>\n";
        $text .= "<br style='clear:left;' >\n";

        return $text;
    }

    //---------------------------------------------------------
    // utility
    //---------------------------------------------------------

    /**
     * @param $str
     * @return string
     */
    public function sanitize($str)
    {
        return htmlspecialchars($str, ENT_QUOTES);
    }

    //---------------------------------------------------------
    // language
    //---------------------------------------------------------

    /**
     * @param $name
     * @return mixed
     */
    public function get_title($name)
    {
        $const_name = mb_strtoupper('_AM_' . $this->_TRUST_DIRNAME . '_MYMENU_' . $name);
        $title = defined($const_name) ? constant($const_name) : $name;

        return $title;
    }

    //---------------------------------------------------------
    // xoops param
    //---------------------------------------------------------
    public function _init_xoops_param()
    {
        global $xoopsModule;
        if (is_object($xoopsModule)) {
            $this->_MODULE_ID = $xoopsModule->mid();
        }
    }

    /**
     * @return int
     */
    public function has_xoops_config_this_module()
    {
        $configHandler = xoops_getHandler('config');

        return count($configHandler->getConfigs(new Criteria('conf_modid', $this->_MODULE_ID)));
    }

    /**
     * @param $dirname
     * @return mixed
     */
    public function get_xoops_module_by_dirname($dirname)
    {
        $moduleHandler = xoops_getHandler('module');

        return $moduleHandler->getByDirname($dirname);
    }

    /**
     * @return bool
     */
    public function is_installed_altsys()
    {
        $module = $this->get_xoops_module_by_dirname('altsys');
        if (is_object($module)) {
            return true;
        }

        return false;
    }

    // --- class end ---
}
