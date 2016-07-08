<?php
// $Id: handler.php,v 1.9 2009/05/31 18:22:59 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-05-30 K.OHWADA
// perm_read in build_form_select_options()
// 2009-05-17 K.OHWADA
// build_form_option_extra()
// 2009-04-19 K.OHWADA
// build_form_select_options()
// 2009-01-25 K.OHWADA
// debug_print_backtrace()
// 2008-12-12 K.OHWADA
// check_perm_by_row_name_groups()
// 2008-11-16 K.OHWADA
// check_perms_in_groups()
// 2008-10-01 K.OHWADA
// build_form_select_list()
// 2008-08-01 K.OHWADA
// added force in query()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_lib_handler
//=========================================================
class webphoto_lib_handler extends webphoto_lib_error
{
    public $_DIRNAME;

    public $_db;
    public $_table;
    public $_id_name;
    public $_pid_name;
    public $_title_name;

    public $_xoops_mid;
    public $_xoops_groups;
    public $_is_module_admin;

    public $_id          = 0;
    public $_xoops_uid   = 0;
    public $_cached      = array();
    public $_flag_cached = false;

    public $_use_prefix  = false;
    public $_NONE_VALUE  = '---';
    public $_PREFIX_NAME = 'prefix';
    public $_PREFIX_MARK = '.';
    public $_PREFIX_BAR  = '--';

    public $_PERM_ALLOW_ALL = '*';
    public $_PERM_DENOY_ALL = 'x';
    public $_PERM_SEPARATOR = '&';

    public $_DEBUG_SQL   = false;
    public $_DEBUG_ERROR = false;

    public $_FORM_SELECTED = ' selected="selected" ';
    public $_FORM_DISABLED = ' disabled="disabled" ';

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct($dirname = null)
    {
        parent::__construct();

        $this->_db = XoopsDatabaseFactory::getDatabaseConnection();

        $this->_xoops_groups    = $this->_get_xoops_groups();
        $this->_xoops_mid       = $this->_get_xoops_mid();
        $this->_is_module_admin = $this->_get_is_module_admin();

        $this->_DIRNAME = $dirname;
    }

    public function set_table_prefix_dirname($name)
    {
        $this->set_table($this->prefix_dirname($name));
    }

    public function set_table_prefix($name)
    {
        $this->set_table($this->db_prefix($name));
    }

    public function set_table($val)
    {
        $this->_table = $val;
    }

    public function get_table()
    {
        return $this->_table;
    }

    public function set_id_name($val)
    {
        $this->_id_name = $val;
    }

    public function get_id_name()
    {
        return $this->_id_name;
    }

    public function set_pid_name($val)
    {
        $this->_pid_name = $val;
    }

    public function get_pid_name()
    {
        return $this->_pid_name;
    }

    public function set_title_name($val)
    {
        $this->_title_name = $val;
    }

    public function get_title_name()
    {
        return $this->_title_name;
    }

    public function get_id()
    {
        return $this->_id;
    }

    public function prefix_dirname($name)
    {
        return $this->db_prefix($this->_DIRNAME . '_' . $name);
    }

    public function db_prefix($name)
    {
        return $this->_db->prefix($name);
    }

    public function set_use_prefix($val)
    {
        $this->_use_prefix = (bool)$val;
    }

    public function set_debug_sql_by_const_name($name)
    {
        $name = strtoupper($name);
        if (defined($name)) {
            $this->set_debug_sql(constant($name));
        }
    }

    public function set_debug_error_by_const_name($name)
    {
        $name = strtoupper($name);
        if (defined($name)) {
            $this->set_debug_error(constant($name));
        }
    }

    public function set_debug_sql($val)
    {
        $this->_DEBUG_SQL = (bool)$val;
    }

    public function set_debug_error($val)
    {
        $this->_DEBUG_ERROR = (int)$val;
    }

    //---------------------------------------------------------
    // insert
    //---------------------------------------------------------
    public function insert($row)
    {
        // dummy
    }

    //---------------------------------------------------------
    // update
    //---------------------------------------------------------
    public function update($row)
    {
        // dummy
    }

    //---------------------------------------------------------
    // delete
    //---------------------------------------------------------
    public function delete($row, $force = false)
    {
        return $this->delete_by_id($this->get_id_from_row($row), $force);
    }

    public function delete_by_id($id, $force = false)
    {
        $sql = 'DELETE FROM ' . $this->_table;
        $sql .= ' WHERE ' . $this->_id_name . '=' . (int)$id;
        return $this->query($sql, 0, 0, $force);
    }

    public function delete_by_id_array($id_array)
    {
        if (!is_array($id_array) || !count($id_array)) {
            return true;    // no action
        }

        $in  = implode(',', $id_array);
        $sql = 'DELETE FROM ' . $this->_table;
        $sql .= ' WHERE ' . $this->_id_name . ' IN (' . $in . ')';
        return $this->query($sql);
    }

    public function get_id_from_row($row)
    {
        if (isset($row[$this->_id_name])) {
            $this->_id = $row[$this->_id_name];
            return $this->_id;
        }
        return null;
    }

    public function truncate_table()
    {
        $sql = 'TRUNCATE TABLE ' . $this->_table;
        return $this->query($sql);
    }

    //---------------------------------------------------------
    // count
    //---------------------------------------------------------
    public function exists_record()
    {
        if ($this->get_count_all() > 0) {
            return true;
        }
        return false;
    }

    public function get_count_by_id($id)
    {
        $where = $this->_id_name . '=' . (int)$id;
        return $this->get_count_by_where($where);
    }

    public function get_count_all()
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->_table;
        return $this->get_count_by_sql($sql);
    }

    public function get_count_by_where($where)
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->_table;
        $sql .= ' WHERE ' . $where;
        return $this->get_count_by_sql($sql);
    }

    //---------------------------------------------------------
    // row
    //---------------------------------------------------------
    public function get_row_by_id($id)
    {
        $sql = 'SELECT * FROM ' . $this->_table;
        $sql .= ' WHERE ' . $this->_id_name . '=' . (int)$id;
        return $this->get_row_by_sql($sql);
    }

    public function get_row_by_id_or_default($id)
    {
        $row = $this->get_row_by_id($id);
        if (!is_array($row)) {
            $row = $this->create();
        }
        return $row;
    }

    public function create()
    {
        // dummy
    }

    //---------------------------------------------------------
    // rows
    //---------------------------------------------------------
    public function get_rows_all_asc($limit = 0, $offset = 0, $key = null)
    {
        $sql = 'SELECT * FROM ' . $this->_table;
        $sql .= ' ORDER BY ' . $this->_id_name . ' ASC';
        return $this->get_rows_by_sql($sql, $limit, $offset, $key);
    }

    public function get_rows_all_desc($limit = 0, $offset = 0, $key = null)
    {
        $sql = 'SELECT * FROM ' . $this->_table;
        $sql .= ' ORDER BY ' . $this->_id_name . ' DESC';
        return $this->get_rows_by_sql($sql, $limit, $offset, $key);
    }

    public function get_rows_by_where($where, $limit = 0, $offset = 0)
    {
        $sql = 'SELECT * FROM ' . $this->_table;
        $sql .= ' WHERE ' . $where;
        $sql .= ' ORDER BY ' . $this->_id_name . ' ASC';
        return $this->get_rows_by_sql($sql, $limit, $offset);
    }

    public function get_rows_by_orderby($orderby, $limit = 0, $offset = 0)
    {
        $sql = 'SELECT * FROM ' . $this->_table;
        $sql .= ' ORDER BY ' . $orderby;
        return $this->get_rows_by_sql($sql, $limit, $offset);
    }

    public function get_rows_by_where_orderby($where, $orderby, $limit = 0, $offset = 0)
    {
        $sql = 'SELECT * FROM ' . $this->_table;
        $sql .= ' WHERE ' . $where;
        $sql .= ' ORDER BY ' . $orderby;
        return $this->get_rows_by_sql($sql, $limit, $offset);
    }

    public function get_rows_by_groupby_orderby($groupby, $orderby, $limit = 0, $offset = 0)
    {
        $sql = 'SELECT * FROM ' . $this->_table;
        $sql .= ' GROUP BY ' . $groupby;
        $sql .= ' ORDER BY ' . $orderby;
        return $this->get_rows_by_sql($sql, $limit, $offset);
    }

    //---------------------------------------------------------
    // id array
    //---------------------------------------------------------
    public function get_id_array_by_where($where, $limit = 0, $offset = 0)
    {
        $sql = 'SELECT ' . $this->_id_name . ' FROM ' . $this->_table;
        $sql .= ' WHERE ' . $where;
        $sql .= ' ORDER BY ' . $this->_id_name . ' ASC';
        return $this->get_first_rows_by_sql($sql, $limit, $offset);
    }

    public function get_id_array_by_where_orderby($where, $orderby, $limit = 0, $offset = 0)
    {
        $sql = 'SELECT ' . $this->_id_name . ' FROM ' . $this->_table;
        $sql .= ' WHERE ' . $where;
        $sql .= ' ORDER BY ' . $orderby;
        return $this->get_first_rows_by_sql($sql, $limit, $offset);
    }

    //---------------------------------------------------------
    // cached
    //---------------------------------------------------------
    public function get_cached_row_by_id($id)
    {
        if (isset($this->_cached[$id])) {
            return $this->_cached[$id];
        }

        $row = $this->get_row_by_id($id);
        if (is_array($row)) {
            $this->_cached [$id] = $row;
            return $row;
        }

        return null;
    }

    public function get_cached_value_by_id_name($id, $name, $flag_sanitize = false)
    {
        $row = $this->get_cached_row_by_id($id);
        if (isset($row[$name])) {
            $val = $row[$name];
            if ($flag_sanitize) {
                $val = $this->sanitize($val);
            }
            return $val;
        }
        return null;
    }

    //---------------------------------------------------------
    // utility
    //---------------------------------------------------------
    public function get_count_by_sql($sql)
    {
        return (int)$this->get_first_row_by_sql($sql);
    }

    public function get_first_row_by_sql($sql)
    {
        $res = $this->query($sql);
        if (!$res) {
            return false;
        }

        $row = $this->_db->fetchRow($res);
        if (isset($row[0])) {
            return $row[0];
        }

        return false;
    }

    public function get_row_by_sql($sql)
    {
        $res = $this->query($sql);
        if (!$res) {
            return false;
        }

        $row = $this->_db->fetchArray($res);
        return $row;
    }

    public function get_rows_by_sql($sql, $limit = 0, $offset = 0, $key = null)
    {
        $arr = array();

        $res = $this->query($sql, $limit, $offset);
        if (!$res) {
            return false;
        }

        while ($row = $this->_db->fetchArray($res)) {
            if ($key && isset($row[$key])) {
                $arr[$row[$key]] = $row;
            } else {
                $arr[] = $row;
            }
        }
        return $arr;
    }

    public function get_first_rows_by_sql($sql, $limit = 0, $offset = 0)
    {
        $res = $this->query($sql, $limit, $offset);
        if (!$res) {
            return false;
        }

        $arr = array();

        while ($row = $this->_db->fetchRow($res)) {
            $arr[] = $row[0];
        }
        return $arr;
    }

    public function query($sql, $limit = 0, $offset = 0, $force = false)
    {
        if ($force) {
            return $this->queryF($sql, $limit, $offset);
        }

        $sql_full = $sql . ': limit=' . $limit . ' :offset=' . $offset;

        if ($this->_DEBUG_SQL) {
            echo $this->sanitize($sql_full) . "<br />\n";
        }

        $res = $this->_db->query($sql, (int)$limit, (int)$offset);
        if (!$res) {
            $error = $this->_db->error();
            if (empty($error)) {
                $error = 'Database update not allowed during processing of a GET request';
            }
            $this->set_error($error);
            if (!$this->_DEBUG_SQL) {
                echo $this->sanitize($sql_full) . "<br />\n";
            }
            if ($this->_DEBUG_ERROR) {
                echo $this->highlight($this->sanitize($error)) . "<br />\n";
            }
            if ($this->_DEBUG_ERROR > 1) {
                debug_print_backtrace();
            }
        }
        return $res;
    }

    public function queryF($sql, $limit = 0, $offset = 0)
    {
        if ($this->_DEBUG_SQL) {
            echo $this->sanitize($sql) . ': limit=' . $limit . ' :offset=' . $offset . "<br />\n";
        }

        $res = $this->_db->queryF($sql, (int)$limit, (int)$offset);
        if (!$res) {
            $error = $this->_db->error();
            $this->set_error($error);

            if ($this->_DEBUG_ERROR) {
                echo $this->highlight($this->sanitize($error)) . "<br />\n";
            }
        }
        return $res;
    }

    public function quote($str)
    {
        $str = "'" . addslashes($str) . "'";
        return $str;
    }

    //---------------------------------------------------------
    // search
    //---------------------------------------------------------
    public function build_where_by_keyword_array($keyword_array, $name, $andor = 'AND')
    {
        if (!is_array($keyword_array) || !count($keyword_array)) {
            return null;
        }

        switch (strtolower($andor)) {
            case 'exact':
                $where = $this->build_where_keyword_single($keyword_array[0], $name);
                return $where;

            case 'or':
                $andor_glue = 'OR';
                break;

            case 'and':
            default:
                $andor_glue = 'AND';
                break;
        }

        $arr = array();

        foreach ($keyword_array as $keyword) {
            $keyword = trim($keyword);
            if ($keyword) {
                $arr[] = $this->build_where_keyword_single($keyword, $name);
            }
        }

        if (is_array($arr) && count($arr)) {
            $glue  = ' ' . $andor_glue . ' ';
            $where = ' ( ' . implode($glue, $arr) . ' ) ';
            return $where;
        }

        return null;
    }

    public function build_where_keyword_single($str, $name)
    {
        $text = $name . " LIKE '%" . addslashes($str) . "%'";
        return $text;
    }

    //---------------------------------------------------------
    // permission
    //---------------------------------------------------------
    public function build_id_array_with_perm($id_array, $name, $groups = null)
    {
        $arr = array();
        foreach ($id_array as $id) {
            if ($this->check_perm_by_id_name_groups($id, $name, $groups)) {
                $arr[] = $id;
            }
        }
        return $arr;
    }

    public function build_rows_with_perm($rows, $name, $groups = null)
    {
        $arr = array();
        foreach ($rows as $row) {
            if ($this->check_perm_by_row_name_groups($row, $name, $groups)) {
                $arr[] = $row;
            }
        }
        return $arr;
    }

    public function check_perm_by_id_name_groups($id, $name, $groups = null)
    {
        $row = $this->get_cached_row_by_id($id);
        return $this->check_perm_by_row_name_groups($row, $name, $groups);
    }

    public function check_perm_by_row_name_groups($row, $name, $groups = null)
    {
        if (empty($name)) {
            return true;
        }

        if (!isset($row[$name])) {
            return false;
        }

        $val = $row[$name];

        if ($this->_PERM_ALLOW_ALL && ($val == $this->_PERM_ALLOW_ALL)) {
            return true;
        }

        if ($this->_PERM_DENOY_ALL && ($val == $this->_PERM_DENOY_ALL)) {
            return false;
        }

        $perms = $this->str_to_array($val, $this->_PERM_SEPARATOR);
        return $this->check_perms_in_groups($perms, $groups);
    }

    public function check_perm_by_perm_groups($perm, $groups = null)
    {
        if ($this->_PERM_ALLOW_ALL && ($perm == $this->_PERM_ALLOW_ALL)) {
            return true;
        }

        if ($this->_PERM_DENOY_ALL && ($perm == $this->_PERM_DENOY_ALL)) {
            return false;
        }

        $perms = $this->str_to_array($perm, $this->_PERM_SEPARATOR);
        return $this->check_perms_in_groups($perms, $groups);
    }

    public function check_perms_in_groups($perms, $groups = null)
    {
        if (!is_array($perms) || !count($perms)) {
            return false;
        }

        if (empty($groups)) {
            $groups = $this->_xoops_groups;
        }

        $arr = array_intersect($groups, $perms);
        if (is_array($arr) && count($arr)) {
            return true;
        }
        return false;
    }

    public function get_perm_array_by_row_name($row, $name)
    {
        if (isset($row[$name])) {
            return $this->get_perm_array($row[$name]);
        } else {
            return array();
        }
    }

    public function get_perm_array($val)
    {
        return $this->str_to_array($val, $this->_PERM_SEPARATOR);
    }

    //---------------------------------------------------------
    // selbox
    //---------------------------------------------------------
    public function build_form_selbox($name = '', $value = 0, $none = 0, $onchange = '')
    {
        return $this->build_form_select_list($this->get_rows_by_orderby($this->_title_name), $this->_title_name, $value, $none, $name, $onchange);
    }

    public function build_form_select_list($rows, $title_name = '', $preset_id = 0, $none = 0, $sel_name = '', $onchange = '')
    {
        $str = $this->build_form_select_tag($sel_name, $onchange);
        $str .= $this->build_form_select_option_none($none);
        $str .= $this->build_form_select_options($rows, $title_name, $preset_id);
        $str .= $this->build_form_select_tag_close();
        return $str;
    }

    public function build_form_select_tag($sel_name = '', $onchange = '')
    {
        if (empty($sel_name)) {
            $sel_name = $this->_id_name;
        }

        $str = '<select name="' . $sel_name . '" ';
        if ($onchange != '') {
            $str .= ' onchange="' . $onchange . '" ';
        }
        $str .= ">\n";
        return $str;
    }

    public function build_form_select_tag_close()
    {
        return "</select>\n";
    }

    public function build_form_select_option_none($none)
    {
        $str = '';
        if ($none) {
            $str .= $this->build_form_option(0, $this->_NONE_VALUE);
        }
        return $str;
    }

    public function build_form_select_options($rows, $title_name = '', $preset_id = 0)
    {
        if (!is_array($rows) || !count($rows)) {
            return null;
        }

        if (empty($title_name)) {
            $title_name = $this->_title_name;
        }

        $str = '';

        // build options
        foreach ($rows as $row) {
            $str .= $this->build_form_option($row[$this->_id_name], $this->build_form_option_caption($row, $title_name), $this->build_form_option_extra($row, $preset_id));
        }

        return $str;
    }

    public function build_form_select_options_with_perm_post($rows, $title_name, $preset_id, $perm_post, $perm_read, $show)
    {
        if (!is_array($rows) || !count($rows)) {
            return null;
        }

        if (empty($title_name)) {
            $title_name = $this->_title_name;
        }

        $flag_selected = false;
        $row_arr       = array();
        $str           = '';

        // set extra
        foreach ($rows as $row) {
            $extra    = '';
            $selected = false;
            $disabled = false;

            // not permit read
            if (!$this->check_perm_by_row_name_groups($row, $perm_read)) {
                if ($show) {
                    $disabled = true;
                } else {
                    continue;
                }
            }

            // match id
            if ($this->build_form_option_match($row, $preset_id)) {
                $selected = true;
            }

            // not permit post
            if (!$this->check_perm_by_row_name_groups($row, $perm_post)) {
                $disabled = true;
            }

            // both
            if ($selected && $disabled) {
                if ($this->_is_module_admin) {
                    $disabled = false;
                } else {
                    $selected = false;
                }
            }

            // selected
            if ($selected) {
                $extra         = $this->_FORM_SELECTED;
                $flag_selected = true;
            }

            // disabled
            if ($disabled) {
                $extra = $this->_FORM_DISABLED;
            }

            $row['extra'] = $extra;
            $row_arr[]    = $row;
        }

        // build options
        foreach ($row_arr as $row) {
            $id    = $row[$this->_id_name];
            $extra = $row['extra'];

            // only one first if no selected
            if (!$flag_selected && empty($extra)) {
                $flag_selected = true;
                $extra         = $this->_FORM_SELECTED;
            }

            $str .= $this->build_form_option($id, $this->build_form_option_caption($row, $title_name), $extra);
        }

        return $str;
    }

    public function build_form_option($value, $caption, $extra = null)
    {
        $str = '<option value="' . $value . '" ' . $extra . ' >';
        $str .= $caption;
        $str .= '</option >' . "\n";
        return $str;
    }

    public function build_form_option_caption($row, $title_name)
    {
        $prefix = '';
        if ($this->_use_prefix) {
            $prefix = $row[$this->_PREFIX_NAME];
            if ($prefix) {
                $prefix = str_replace($this->_PREFIX_MARK, $this->_PREFIX_BAR, $prefix) . ' ';
            }
        }

        $caption = $prefix . $this->sanitize($row[$title_name]);
        return $caption;
    }

    public function build_form_option_extra($row, $preset_id)
    {
        if ($this->build_form_option_match($row, $preset_id)) {
            return $this->_FORM_SELECTED;
        }
        return null;
    }

    public function build_form_option_match($row, $preset_id)
    {
        if ($row[$this->_id_name] == $preset_id) {
            return true;
        }
        return false;
    }

    //---------------------------------------------------------
    // utility
    //---------------------------------------------------------
    public function str_to_array($str, $pattern)
    {
        $arr1 = explode($pattern, $str);
        $arr2 = array();
        foreach ($arr1 as $v) {
            $v = trim($v);
            if ($v == '') {
                continue;
            }
            $arr2[] = $v;
        }
        return $arr2;
    }

    public function array_to_str($arr, $glue)
    {
        $val = false;
        if (is_array($arr) && count($arr)) {
            $val = implode($glue, $arr);
        }
        return $val;
    }

    public function array_to_perm($arr, $glue)
    {
        $val = $this->array_to_str($arr, $glue);
        if ($val) {
            $val = $glue . $val . $glue;
        }
        return $val;
    }

    public function sanitize_array_int($arr_in)
    {
        if (!is_array($arr_in) || !count($arr_in)) {
            return null;
        }

        $arr_out = array();
        foreach ($arr_in as $in) {
            $arr_out[] = (int)$in;
        }
        return $arr_out;
    }

    //---------------------------------------------------------
    // xoops param
    //---------------------------------------------------------
    public function _get_xoops_groups()
    {
        global $xoopsUser;
        if (is_object($xoopsUser)) {
            return $xoopsUser->getGroups();
        }
        return array(XOOPS_GROUP_ANONYMOUS);
    }

    public function _get_xoops_mid()
    {
        global $xoopsModule;
        if (is_object($xoopsModule)) {
            return $xoopsModule->getVar('mid');
        }
        return false;
    }

    public function _get_is_module_admin()
    {
        global $xoopsUser;
        if (is_object($xoopsUser)) {
            if ($xoopsUser->isAdmin($this->_xoops_mid)) {
                return true;
            }
        }
        return false;
    }

    //----- class end -----
}
