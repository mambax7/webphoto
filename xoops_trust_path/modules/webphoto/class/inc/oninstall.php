<?php
// $Id: oninstall.php,v 1.29 2011/12/28 16:16:15 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-12-25 K.OHWADA
// webphoto_inc_oninstall_cat
// 2011-11-11 K.OHWADA
// webphoto_lib_file_log
// 2011-11-03 K.OHWADA
// Assigning the return value of new by reference is deprecated
// _groupperm_webphoto_users()
// 2011-05-01 K.OHWADA
// webphoto_inc_oninstall_flashvar
// 2010-09-20 K.OHWADA
// webphoto_inc_oninstall_mime
// 2010-06-06 K.OHWADA
// _mime_add_record_docx_etc();
// 2010-01-10 K.OHWADA
// _item_add_column_210()
// 2009-12-06 K.OHWADA
// _item_add_column_200()
// 2009-11-11 K.OHWADA
// webphoto_inc_handler -> webphoto_inc_base_ini
// getInstance -> getSingleton
// _item_add_column_190();
// 2009-10-25 K.OHWADA
// _mime_add_column_kind_etc()
// 2009-08-22 K.OHWADA
// _item_modify_column_173()
// 2009-01-10 K.OHWADA
// _item_add_column_110()
// 2009-01-04 K.OHWADA
// _item_add_column_100()
// 2008-11-29 K.OHWADA
// item_add_column_080()
// 2008-11-16 K.OHWADA
// item_add_column_070()
// 2008-11-08 K.OHWADA
// item_add_column_060()
// 2008-10-01 K.OHWADA
// move config_update() to xoops_version.php
// _item_update()
// 2008-08-01 K.OHWADA
// changed _table_update() _groupperm_install()
// 2008-07-24 K.OHWADA
// BUG : undefined variable table_name
// 2008-07-01 K.OHWADA
// added _mime_update()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_inc_oninstall
//=========================================================

/**
 * Class webphoto_inc_oninstall
 */
class webphoto_inc_oninstall extends webphoto_inc_base_ini
{
    public $_group_class;
    public $_gperm_def_class;
    public $_oninstall_item_class;
    public $_oninstall_cat_class;
    public $_oninstall_mime_class;
    public $_oninstall_flashvar_class;
    public $_log_class;

    public $_table_item;
    public $_table_mime;
    public $_table_player;

    public $_IS_XOOPS_2018 = false;

    public $_use_groupperm_webphoto_users = false;
    public $_use_groupperm_module_read_anonymous = false;
    public $_use_cfg_groupid_admin = false;
    public $_use_cfg_groupid_user = false;
    public $_use_group_create = false;
    public $_use_group_delete = false;

    //  var $_msg_array = array();

    //  var $_TRUST_DIRNAME ;
    //  var $_TRUST_DIR;
    public $_MODULE_ID = 0;

    public $_flag_debug = false;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_inc_oninstall constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct();
        $this->init_base_ini($dirname, $trust_dirname);
        $this->initHandler($dirname);

        $this->_group_class = webphoto_inc_group::getSingleton($dirname);
        $this->_gperm_def_class = webphoto_inc_gperm_def::getInstance();
        $this->_log_class = webphoto_lib_file_log::getInstance();

        $this->_oninstall_item_class = webphoto_inc_oninstall_item::getSingleton($dirname, $trust_dirname);
        $this->_oninstall_cat_class = webphoto_inc_oninstall_cat::getSingleton($dirname, $trust_dirname);
        $this->_oninstall_mime_class = webphoto_inc_oninstall_mime::getSingleton($dirname, $trust_dirname);
        $this->_oninstall_flashvar_class = webphoto_inc_oninstall_flashvar::getSingleton($dirname, $trust_dirname);

        $this->_table_cat = $this->prefix_dirname('cat');
        $this->_table_item = $this->prefix_dirname('item');
        $this->_table_mime = $this->prefix_dirname('mime');
        $this->_table_player = $this->prefix_dirname('player');

        $this->_use_groupperm_webphoto_users = $this->get_ini('oninstall_groupperm_webphoto_users');
        $this->_use_groupperm_module_read_anonymous = $this->get_ini('oninstall_groupperm_module_read_anonymous');
        $this->_use_cfg_groupid_admin = $this->get_ini('xoops_version_cfg_groupid_admin');
        $this->_use_cfg_groupid_user = $this->get_ini('xoops_version_cfg_groupid_user');
        $this->_use_group_create = $this->get_ini('oninstall_group_create');
        $this->_use_group_delete = $this->get_ini('onuninstall_group_delete');

        // preload
        if (defined('_C_WEBPHOTO_PRELOAD_XOOPS_2018')) {
            $this->_IS_XOOPS_2018 = (bool)_C_WEBPHOTO_PRELOAD_XOOPS_2018;
        }
    }

    /**
     * @param $dirname
     * @param $trust_dirname
     * @return mixed
     */
    public static function getSingleton($dirname, $trust_dirname)
    {
        static $singletons;
        if (!isset($singletons[$dirname])) {
            $singletons[$dirname] = new self($dirname, $trust_dirname);
        }

        return $singletons[$dirname];
    }

    //---------------------------------------------------------
    // public
    //---------------------------------------------------------

    /**
     * @param $module
     * @return bool
     */
    public function install(&$module)
    {
        global $ret; // TODO :-D

        if (!is_array($ret)) {
            $ret = [];
        }

        $this->_init($module);
        $ret_code = $this->_exec_install();

        $msg_arr = $this->get_msg_array();
        if (is_array($msg_arr) && count($msg_arr)) {
            foreach ($msg_arr as $msg) {
                $ret[] = $msg . "<br>\n";
                $this->_write_log($msg);
            }
        }

        return $ret_code;
    }

    /**
     * @param $module
     * @return bool
     */
    public function update(&$module)
    {
        global $msgs; // TODO :-D

        if (!is_array($msgs)) {
            $msgs = [];
        }

        $this->_init($module);
        $ret_code = $this->_exec_update();

        $msg_arr = $this->get_msg_array();
        if (is_array($msg_arr) && count($msg_arr)) {
            foreach ($msg_arr as $msg) {
                $msgs[] = $msg;
                $this->_write_log($msg);
            }
        }

        return $ret_code;
    }

    /**
     * @param $module
     * @return bool
     */
    public function uninstall(&$module)
    {
        global $ret; // TODO :-D

        if (!is_array($ret)) {
            $ret = [];
        }

        $this->_init($module);
        $ret_code = $this->_exec_uninstall();

        $msg_arr = $this->get_msg_array();
        if (is_array($msg_arr) && count($msg_arr)) {
            foreach ($msg_arr as $msg) {
                $ret[] = $msg . '<br>';
                $this->_write_log($msg);
            }
        }

        return $ret_code;
    }

    //---------------------------------------------------------
    // private
    //---------------------------------------------------------

    /**
     * @param $module
     */
    public function _init($module)
    {
        $this->_MODULE_ID = $module->getVar('mid', 'n');
    }

    /**
     * @return bool
     */
    public function _exec_install()
    {
        // for Cube 2.1
        if (defined('XOOPS_CUBE_LEGACY')) {
            $name = 'Legacy.Admin.Event.ModuleInstall.' . ucfirst($this->_DIRNAME) . '.Success';
            $root = XCube_Root::getSingleton();
            $root->mDelegateManager->add($name, 'webphoto_message_append_oninstall');
        }

        $this->set_msg("\n Install module extention ...");

        $res = $this->_table_install();
        if (!$res) {
            return false;
        }

        $this->_template_install();
        $this->_groupperm_webphoto_admin();
        if ($this->_use_groupperm_webphoto_users) {
            $this->_groupperm_webphoto_users();
        }
        if ($this->_use_groupperm_module_read_anonymous) {
            $this->_groupperm_module_read_anonymous();
        }
        if ($this->_use_group_create) {
            $this->_group_create();
        }

        return true;
    }

    /**
     * @return bool
     */
    public function _exec_update()
    {
        // for Cube 2.1
        if (defined('XOOPS_CUBE_LEGACY')) {
            $name = 'Legacy.Admin.Event.ModuleUpdate.' . ucfirst($this->_DIRNAME) . '.Success';
            $root = XCube_Root::getSingleton();
            $root->mDelegateManager->add($name, 'webphoto_message_append_onupdate');
        }

        $this->set_msg("\n Update module extention ...");

        $this->_table_update();
        $this->_cat_update();
        $this->_item_update();
        $this->_mime_update();
        $this->_flashvar_update();
        $this->_template_update();

        return true;
    }

    /**
     * @return bool
     */
    public function _exec_uninstall()
    {
        // for Cube 2.1
        if (defined('XOOPS_CUBE_LEGACY')) {
            $name = 'Legacy.Admin.Event.ModuleUninstall.' . ucfirst($this->_DIRNAME) . '.Success';
            $root = XCube_Root::getSingleton();
            $root->mDelegateManager->add($name, 'webphoto_message_append_onuninstall');
        }

        $this->set_msg("\n Uninstall module extention ...");

        $this->_table_uninstall();
        $this->_template_uninstall();

        if ($this->_use_group_delete) {
            $this->_group_delete();
        }

        return true;
    }

    //---------------------------------------------------------
    // table handler
    //---------------------------------------------------------

    /**
     * @return bool
     */
    public function _table_install()
    {
        $sql_file_path = $this->_get_table_sql();
        if (!$sql_file_path) {
            return true;
        }    // no action

        $prefix_mod = $this->_db->prefix() . '_' . $this->_DIRNAME;
        $this->set_msg('SQL file found at <b>' . $this->sanitize($sql_file_path) . '</b>');
        $this->set_msg('Creating tables...');

        // Unknown Condition [8192]: Assigning the return value of new by reference is deprecated
        if (file_exists(XOOPS_ROOT_PATH . '/class/database/oldsqlutility.php')) {
            include_once XOOPS_ROOT_PATH . '/class/database/oldsqlutility.php';
            $sqlutil = new OldSqlUtility();
        } else {
            include_once XOOPS_ROOT_PATH . '/class/database/sqlutility.php';
            $sqlutil = new SqlUtility();
        }

        $sql_query = trim(file_get_contents($sql_file_path));
        $sqlutil->splitMySqlFile($pieces, $sql_query);
        if (!is_array($pieces) || !count($pieces)) {
            return true;
        }   // no action

        foreach ($pieces as $piece) {
            $prefixed_query = $sqlutil->prefixQuery($piece, $prefix_mod);
            if (!$prefixed_query) {
                $this->set_msg('Invalid SQL <b>' . $this->sanitize($piece) . '</b>');

                $this->_log_class->write($piece);

                return false;
            }

            // replace reserved words
            $sql = str_replace('{DIRNAME}', $this->_DIRNAME, $prefixed_query[0]);

            $ret = $this->query($sql);
            if (!$ret) {
                $this->set_msg($this->get_db_error());

                return false;
            }

            $table = $prefixed_query[4];
            $table_name_s = $this->sanitize($prefix_mod . '_' . $table);

            if ($this->_parse_create_table($sql)) {
                $this->set_msg('Table <b>' . $table_name_s . '</b> created');
            } else {
                $this->set_msg('Data inserted to table <b>' . $table_name_s . '</b>');
            }
        }

        return true;
    }

    /**
     * @return bool
     */
    public function _table_update()
    {
        $sql_file_path = $this->_get_table_sql();
        if (!$sql_file_path) {
            return true;    // no action
        }

        $prefix_mod = $this->_db->prefix() . '_' . $this->_DIRNAME;

        // Unknown Condition [8192]: Assigning the return value of new by reference is deprecated
        if (file_exists(XOOPS_ROOT_PATH . '/class/database/oldsqlutility.php')) {
            include_once XOOPS_ROOT_PATH . '/class/database/oldsqlutility.php';
            $sqlutil = new OldSqlUtility();
        } else {
            include_once XOOPS_ROOT_PATH . '/class/database/sqlutility.php';
            $sqlutil = new SqlUtility();
        }

        $sql_query = trim(file_get_contents($sql_file_path));
        $sqlutil->splitMySqlFile($pieces, $sql_query);
        if (!is_array($pieces) || !count($pieces)) {
            return true;    // no action
        }

        $sql_array = [];

        // get added table
        foreach ($pieces as $piece) {
            $prefixed_query = $sqlutil->prefixQuery($piece, $prefix_mod);
            if (!$prefixed_query) {
                $this->set_msg('Invalid SQL <b>' . $this->sanitize($piece) . '</b>');

                return false;
            }

            $sql = $prefixed_query[0];

            // get create table
            $table = $this->_parse_create_table($sql);
            if (empty($table)) {
                continue;
            }

            // already exists
            if ($this->exists_table($table)) {
                continue;
            }

            $sql_array[$table] = $sql;
        }

        if (!is_array($sql_array) || !count($sql_array)) {
            return true;    // no action
        }

        $this->set_msg('SQL file found at <b>' . $this->sanitize($sql_file_path) . '</b>');
        $this->set_msg('Creating tables...');

        // create added table
        foreach ($sql_array as $table => $sql) {
            $ret = $this->query($sql);
            if (!$ret) {
                $this->set_msg($this->get_db_error());

                return false;
            }

            $table_name_s = $this->sanitize($table);
            $this->set_msg('Table <b>' . $table_name_s . '</b> created');
        }

        return true;
    }

    /**
     * @return bool
     */
    public function _table_uninstall()
    {
        $sql_file_path = $this->_get_table_sql();
        if (!$sql_file_path) {
            return true;
        }    // no action

        $prefix_mod = $this->_db->prefix() . '_' . $this->_DIRNAME;

        $this->set_msg('SQL file found at <b>' . $this->sanitize($sql_file_path) . '</b>');
        $this->set_msg('Deleting tables...');

        $sql_lines = file($sql_file_path);

        foreach ($sql_lines as $sql_line) {
            // get create table
            $table = $this->_parse_create_table($sql_line);
            if (empty($table)) {
                continue;
            }

            // BUG : undefined variable table_name
            $table_name = $prefix_mod . '_' . $table;

            $table_name_s = $this->sanitize($table_name);
            $sql = 'DROP TABLE ' . $table_name;

            $ret = $this->query($sql);
            if ($ret) {
                $this->set_msg('Table <b>' . $table_name_s . '</b> dropped');
            } else {
                $this->set_msg($this->highlight('ERROR: Could not drop table <b>' . $table_name_s . '<b>.'));
                $this->set_msg($this->get_db_error());
            }
        }

        return true;
    }

    /**
     * @return bool|string
     */
    public function _get_table_sql()
    {
        $sql_trust_path = $this->_TRUST_DIR . '/sql/mysql.sql';
        $sql_root_path = $this->_MODULE_DIR . '/sql/mysql.sql';

        if (is_file($sql_root_path)) {
            return $sql_root_path;
        } elseif (is_file($sql_trust_path)) {
            return $sql_trust_path;
        }

        return false;
    }

    /**
     * @param $sql
     * @return bool
     */
    public function _parse_create_table($sql)
    {
        if (preg_match('/^CREATE TABLE \`?([a-zA-Z0-9_-]+)\`? /i', $sql, $match)) {
            return $match[1];
        }

        return false;
    }

    //---------------------------------------------------------
    // template handler
    //---------------------------------------------------------

    /**
     * @return bool
     */
    public function _template_install()
    {
        return $this->_template_common();
    }

    /**
     * @return bool
     */
    public function _template_update()
    {
        return $this->_template_common();
    }

    /**
     * @return bool
     */
    public function _template_common()
    {
        $this->set_msg('Updating tmplates ...');

        $TPL_TRUST_PATH = $this->_TRUST_DIR . '/templates';
        $TPL_ROOT_PATH = $this->_MODULE_DIR . '/templates';

        // read webphoto_xxx.html in root_path
        if ($this->_IS_XOOPS_2018) {
            $tpl_path = $TPL_ROOT_PATH . '/';
            $prefix = '';

        // read xxx.html in trust_path
        } else {
            $tpl_path = $TPL_TRUST_PATH . '/';
            $prefix = $this->_DIRNAME . '_';
        }

        // TEMPLATES
        $tplfileHandler = xoops_getHandler('tplfile');

        $handler = @opendir($tpl_path);
        if (!$handler) {
            xoops_template_clear_module_cache($this->_MODULE_ID);

            return true;
        }

        while (false !== ($file = readdir($handler))) {
            // check file
            if (!$this->_check_tpl_file($file)) {
                continue;
            }

            // use optional file, if exists
            $file_trust_path = $TPL_TRUST_PATH . '/' . $file;
            $file_root_path = $TPL_ROOT_PATH . '/' . $file;
            if (is_file($file_root_path)) {
                $file_path = $file_root_path;
            } elseif (is_file($file_trust_path)) {
                $file_path = $file_trust_path;
            } else {
                continue;
            }

            $dirname_file = $prefix . $file;
            $dirname_file_s = $this->sanitize($dirname_file);
            $mtime = (int)(@filemtime($file_path));

            // set table
            $tplfile = $tplfileHandler->create();
            $tplfile->setVar('tpl_source', file_get_contents($file_path), true);
            $tplfile->setVar('tpl_refid', $this->_MODULE_ID);
            $tplfile->setVar('tpl_tplset', 'default');
            $tplfile->setVar('tpl_file', $dirname_file);
            $tplfile->setVar('tpl_desc', '', true);
            $tplfile->setVar('tpl_module', $this->_DIRNAME);
            $tplfile->setVar('tpl_lastmodified', $mtime);
            $tplfile->setVar('tpl_lastimported', 0);
            $tplfile->setVar('tpl_type', 'module');

            $ret1 = $tplfileHandler->insert($tplfile);
            if ($ret1) {
                $tplid = $tplfile->getVar('tpl_id');
                $this->set_msg(' &nbsp; Template <b>' . $dirname_file_s . '</b> added to the database. (ID: <b>' . $tplid . '</b>)');

                // generate compiled file
                $ret2 = xoops_template_touch($tplid);
                if ($ret2) {
                    $this->set_msg(' &nbsp; Template <b>' . $dirname_file_s . '</b> compiled.</span>');
                } else {
                    $this->set_msg($this->highlight('ERROR: Failed compiling template <b>' . $dirname_file_s . '</b>.'));
                }
            } else {
                $this->set_msg($this->highlight('ERROR: Could not insert template <b>' . $dirname_file_s . '</b> to the database.'));
            }
        }

        closedir($handler);
        xoops_template_clear_module_cache($this->_MODULE_ID);

        return true;
    }

    public function _template_uninstall()
    {
        // TEMPLATES (Not necessary because modulesadmin removes all templates)
    }

    /**
     * @param $file
     * @return bool
     */
    public function _check_tpl_file($file)
    {
        // ignore . and ..
        if ('.' == $this->_parse_first_char($file)) {
            return false;
        }
        // ignore 'index.htm'
        if (('index.htm' == $file) || ('index.html' == $file)) {
            return false;
        }
        // ignore not html
        if ('html' != $this->_parse_ext($file)) {
            return false;
        }

        return true;
    }

    /**
     * @param $file
     * @return bool|string
     */
    public function _parse_first_char($file)
    {
        return mb_substr($file, 0, 1);
    }

    /**
     * @param $file
     * @return string
     */
    public function _parse_ext($file)
    {
        return mb_strtolower(mb_substr(mb_strrchr($file, '.'), 1));
    }

    //---------------------------------------------------------
    // group class
    //---------------------------------------------------------

    /**
     * @return bool
     */
    public function _group_create()
    {
        $this->set_msg('Ceate group <b>' . $this->_db->prefix('group') . '</b> ...');

        $name = $this->_DIRNAME;
        $desc = 'module id: ' . $this->_MODULE_ID . ' name: ' . $this->_DIRNAME;
        $groupid = $this->_group_class->create_member_group($name, $desc);

        if ($groupid) {
            $this->set_msg('Add groupperm to group <b>' . $groupid . '</b> ...');
            $this->_group_class->create_gperm_module_admin($groupid);
            $this->_group_class->create_gperm_module_read($groupid);
            $this->_group_class->create_gperm_webphoto_groupid($groupid, $this->_gperm_def_class->get_perms_admin());
            if ($this->_use_cfg_groupid_admin) {
                $this->save_xoops_config_mod($this->_MODULE_ID, 'groupid_admin', $groupid);
            }
            if ($this->_use_cfg_groupid_user) {
                $this->save_xoops_config_mod($this->_MODULE_ID, 'groupid_user', $groupid);
            }

            return true;
        }

        return false;
    }

    public function _group_delete()
    {
        $this->set_msg('Delete group <b>' . $this->_db->prefix('group') . '</b> ...');

        $groupid = 0;
        if ($this->_use_cfg_groupid_admin) {
            $groupid = $this->get_xoops_config_mod_val($this->_MODULE_ID, 'groupid_admin');
        }
        if (($groupid = 0) && $this->_use_cfg_groupid_user) {
            $groupid = $this->get_xoops_config_mod_val($this->_MODULE_ID, 'groupid_user');
        }
        if ($groupid) {
            $this->_group_class->delete_group($groupid);
        }
    }

    public function _groupperm_webphoto_admin()
    {
        $this->set_msg('Add groupperm to admin ...');
        $this->_group_class->create_gperm_webphoto_groupid(XOOPS_GROUP_ADMIN, $this->_gperm_def_class->get_perms_admin());
    }

    public function _groupperm_webphoto_users()
    {
        $this->set_msg('Add groupperm to user ...');
        $this->_group_class->create_gperm_webphoto_groupid(XOOPS_GROUP_USERS, $this->_gperm_def_class->get_perms_user());
    }

    public function _groupperm_module_read_anonymous()
    {
        $this->set_msg('Add groupperm to anonymous ...');
        $this->_group_class->create_gperm_module_read(XOOPS_GROUP_ANONYMOUS);
    }

    //---------------------------------------------------------
    // item table
    //---------------------------------------------------------
    public function _item_update()
    {
        $this->_oninstall_item_class->update();
        $arr = $this->_oninstall_item_class->get_msg_array();
        if (is_array($arr) && count($arr)) {
            $this->set_msg($arr);
        }
    }

    //---------------------------------------------------------
    // cat table
    //---------------------------------------------------------
    public function _cat_update()
    {
        $this->_oninstall_cat_class->update();
        $arr = $this->_oninstall_cat_class->get_msg_array();
        if (is_array($arr) && count($arr)) {
            $this->set_msg($arr);
        }
    }

    //---------------------------------------------------------
    // mime table
    //---------------------------------------------------------
    public function _mime_update()
    {
        $this->_oninstall_mime_class->update();
        $arr = $this->_oninstall_mime_class->get_msg_array();
        if (is_array($arr) && count($arr)) {
            $this->set_msg($arr);
        }
    }

    //---------------------------------------------------------
    // flashvar table
    //---------------------------------------------------------
    public function _flashvar_update()
    {
        $this->_oninstall_flashvar_class->update();
        $arr = $this->_oninstall_flashvar_class->get_msg_array();
        if (is_array($arr) && count($arr)) {
            $this->set_msg($arr);
        }
    }

    //---------------------------------------------------------
    // debug
    //---------------------------------------------------------

    /**
     * @param $data
     */
    public function _write_log($data)
    {
        if ($this->_flag_debug) {
            $this->_log_class->write($data);
        }
    }

    // --- class end ---
}
