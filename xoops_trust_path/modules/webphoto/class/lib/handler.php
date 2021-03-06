<?php
// $Id: handler.php,v 1.14 2010/11/16 23:43:38 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-11-11 K.OHWADA
// get_value_by_id_name()
// 2010-03-14 K.OHWADA
// BUG: echo sql always if error
// 2010-02-15 K.OHWADA
// add $flag_admin in check_perm_by_row_name_groups()
// 2009-11-11 K.OHWADA
// Notice [PHP]: Undefined index: prefix
// 2009-08-08 K.OHWADA
// build_config_character()
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

/**
 * Class webphoto_lib_handler
 */
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

    public $_id = 0;
    public $_xoops_uid = 0;
    public $_cached = [];
    public $_flag_cached = false;
    public $_cached_perm_key_array = [];

    public $_use_prefix = false;
    public $_NONE_VALUE = '---';
    public $_PREFIX_NAME = 'prefix';
    public $_PREFIX_MARK = '.';
    public $_PREFIX_BAR = '--';

    public $_PERM_ALLOW_ALL = '*';
    public $_PERM_DENOY_ALL = 'x';
    public $_PERM_SEPARATOR = '&';

    public $_DEBUG_SQL = false;
    public $_DEBUG_ERROR = false;

    public $_FORM_SELECTED = ' selected="selected" ';
    public $_FORM_DISABLED = ' disabled="disabled" ';

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_lib_handler constructor.
     * @param null $dirname
     */
    public function __construct($dirname = null)
    {
        parent::__construct();

        $this->_db = XoopsDatabaseFactory::getDatabaseConnection();

        $this->_xoops_groups = $this->_get_xoops_groups();
        $this->_xoops_mid = $this->_get_xoops_mid();
        $this->_is_module_admin = $this->_get_is_module_admin();

        $this->_DIRNAME = $dirname;
    }

    /**
     * @param $name
     */
    public function set_table_prefix_dirname($name)
    {
        $this->set_table($this->prefix_dirname($name));
    }

    /**
     * @param $name
     */
    public function set_table_prefix($name)
    {
        $this->set_table($this->db_prefix($name));
    }

    /**
     * @param $val
     */
    public function set_table($val)
    {
        $this->_table = $val;
    }

    /**
     * @return mixed
     */
    public function get_table()
    {
        return $this->_table;
    }

    /**
     * @param $val
     */
    public function set_id_name($val)
    {
        $this->_id_name = $val;
    }

    /**
     * @return mixed
     */
    public function get_id_name()
    {
        return $this->_id_name;
    }

    /**
     * @param $val
     */
    public function set_pid_name($val)
    {
        $this->_pid_name = $val;
    }

    /**
     * @return mixed
     */
    public function get_pid_name()
    {
        return $this->_pid_name;
    }

    /**
     * @param $val
     */
    public function set_title_name($val)
    {
        $this->_title_name = $val;
    }

    /**
     * @return mixed
     */
    public function get_title_name()
    {
        return $this->_title_name;
    }

    /**
     * @return int
     */
    public function get_id()
    {
        return $this->_id;
    }

    /**
     * @param $name
     * @return string
     */
    public function prefix_dirname($name)
    {
        return $this->db_prefix($this->_DIRNAME . '_' . $name);
    }

    /**
     * @param $name
     * @return string
     */
    public function db_prefix($name)
    {
        return $this->_db->prefix($name);
    }

    /**
     * @param $val
     */
    public function set_use_prefix($val)
    {
        $this->_use_prefix = (bool)$val;
    }

    /**
     * @param $name
     */
    public function set_debug_sql_by_const_name($name)
    {
        $name = mb_strtoupper($name);
        if (defined($name)) {
            $this->set_debug_sql(constant($name));
        }
    }

    /**
     * @param $name
     */
    public function set_debug_error_by_const_name($name)
    {
        $name = mb_strtoupper($name);
        if (defined($name)) {
            $this->set_debug_error(constant($name));
        }
    }

    /**
     * @param $val
     */
    public function set_debug_sql($val)
    {
        $this->_DEBUG_SQL = (bool)$val;
    }

    /**
     * @param $val
     */
    public function set_debug_error($val)
    {
        $this->_DEBUG_ERROR = (int)$val;
    }

    //---------------------------------------------------------
    // config
    //---------------------------------------------------------

    /**
     * @return string
     */
    public function build_config_character()
    {
        $text = '';
        $rows = $this->get_config_character();

        if (is_array($rows)) {
            foreach ($rows as $row) {
                $msg = $row['Variable_name'] . ': ' . $row['Value'];
                $text .= $this->sanitize($msg) . "<br>\n";
            }
        } else {
            $text .= $this->highlight('sql failed') . "<br>\n";
            $text .= $this->get_format_error();
        }

        return $text;
    }

    /**
     * @return array|bool
     */
    public function get_config_character()
    {
        $sql = "show variables like 'character\_set\_%'";

        return $this->get_rows_by_sql($sql, 0, 0, null, true);
    }

    //---------------------------------------------------------
    // insert
    //---------------------------------------------------------

    /**
     * @param $row
     */
    public function insert($row)
    {
        // dummy
    }

    //---------------------------------------------------------
    // update
    //---------------------------------------------------------

    /**
     * @param $row
     */
    public function update($row)
    {
        // dummy
    }

    //---------------------------------------------------------
    // delete
    //---------------------------------------------------------

    /**
     * @param      $row
     * @param bool $force
     * @return mixed
     */
    public function delete($row, $force = false)
    {
        return $this->delete_by_id($this->get_id_from_row($row), $force);
    }

    /**
     * @param      $id
     * @param bool $force
     * @return mixed
     */
    public function delete_by_id($id, $force = false)
    {
        $sql = 'DELETE FROM ' . $this->_table;
        $sql .= ' WHERE ' . $this->_id_name . '=' . (int)$id;

        return $this->query($sql, 0, 0, $force);
    }

    /**
     * @param $id_array
     * @return bool|mixed
     */
    public function delete_by_id_array($id_array)
    {
        if (!is_array($id_array) || !count($id_array)) {
            return true;    // no action
        }

        $in = implode(',', $id_array);
        $sql = 'DELETE FROM ' . $this->_table;
        $sql .= ' WHERE ' . $this->_id_name . ' IN (' . $in . ')';

        return $this->query($sql);
    }

    /**
     * @param $row
     * @return int|null
     */
    public function get_id_from_row($row)
    {
        if (isset($row[$this->_id_name])) {
            $this->_id = $row[$this->_id_name];

            return $this->_id;
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function truncate_table()
    {
        $sql = 'TRUNCATE TABLE ' . $this->_table;

        return $this->query($sql);
    }

    //---------------------------------------------------------
    // count
    //---------------------------------------------------------

    /**
     * @return bool
     */
    public function exists_record()
    {
        if ($this->get_count_all() > 0) {
            return true;
        }

        return false;
    }

    /**
     * @param $id
     * @return int
     */
    public function get_count_by_id($id)
    {
        $where = $this->_id_name . '=' . (int)$id;

        return $this->get_count_by_where($where);
    }

    /**
     * @return int
     */
    public function get_count_all()
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->_table;

        return $this->get_count_by_sql($sql);
    }

    /**
     * @param $where
     * @return int
     */
    public function get_count_by_where($where)
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->_table;
        $sql .= ' WHERE ' . $where;

        return $this->get_count_by_sql($sql);
    }

    //---------------------------------------------------------
    // row
    //---------------------------------------------------------

    /**
     * @param $id
     * @return bool
     */
    public function get_row_by_id($id)
    {
        $sql = 'SELECT * FROM ' . $this->_table;
        $sql .= ' WHERE ' . $this->_id_name . '=' . (int)$id;

        return $this->get_row_by_sql($sql);
    }

    /**
     * @param $id
     * @return bool|void
     */
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

    /**
     * @param      $id
     * @param      $name
     * @param bool $flag_sanitize
     * @return null|string
     */
    public function get_value_by_id_name($id, $name, $flag_sanitize = false)
    {
        $row = $this->get_row_by_id($id);
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
    // rows
    //---------------------------------------------------------

    /**
     * @param int  $limit
     * @param int  $offset
     * @param null $key
     * @return array|bool
     */
    public function get_rows_all_asc($limit = 0, $offset = 0, $key = null)
    {
        $sql = 'SELECT * FROM ' . $this->_table;
        $sql .= ' ORDER BY ' . $this->_id_name . ' ASC';

        return $this->get_rows_by_sql($sql, $limit, $offset, $key);
    }

    /**
     * @param int  $limit
     * @param int  $offset
     * @param null $key
     * @return array|bool
     */
    public function get_rows_all_desc($limit = 0, $offset = 0, $key = null)
    {
        $sql = 'SELECT * FROM ' . $this->_table;
        $sql .= ' ORDER BY ' . $this->_id_name . ' DESC';

        return $this->get_rows_by_sql($sql, $limit, $offset, $key);
    }

    /**
     * @param     $where
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_rows_by_where($where, $limit = 0, $offset = 0)
    {
        $sql = 'SELECT * FROM ' . $this->_table;
        $sql .= ' WHERE ' . $where;
        $sql .= ' ORDER BY ' . $this->_id_name . ' ASC';

        return $this->get_rows_by_sql($sql, $limit, $offset);
    }

    /**
     * @param     $orderby
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_rows_by_orderby($orderby, $limit = 0, $offset = 0)
    {
        $sql = 'SELECT * FROM ' . $this->_table;
        $sql .= ' ORDER BY ' . $orderby;

        return $this->get_rows_by_sql($sql, $limit, $offset);
    }

    /**
     * @param     $where
     * @param     $orderby
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_rows_by_where_orderby($where, $orderby, $limit = 0, $offset = 0)
    {
        $sql = 'SELECT * FROM ' . $this->_table;
        $sql .= ' WHERE ' . $where;
        $sql .= ' ORDER BY ' . $orderby;

        return $this->get_rows_by_sql($sql, $limit, $offset);
    }

    /**
     * @param     $groupby
     * @param     $orderby
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
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

    /**
     * @param     $where
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_id_array_by_where($where, $limit = 0, $offset = 0)
    {
        $sql = 'SELECT ' . $this->_id_name . ' FROM ' . $this->_table;
        $sql .= ' WHERE ' . $where;
        $sql .= ' ORDER BY ' . $this->_id_name . ' ASC';

        return $this->get_first_rows_by_sql($sql, $limit, $offset);
    }

    /**
     * @param     $where
     * @param     $orderby
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
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

    /**
     * @param $id
     * @return bool|mixed|null
     */
    public function get_cached_row_by_id($id)
    {
        if (isset($this->_cached[$id])) {
            return $this->_cached[$id];
        }

        $row = $this->get_row_by_id($id);
        if (is_array($row)) {
            $this->_cached[$id] = $row;

            return $row;
        }

        return null;
    }

    /**
     * @param      $id
     * @param      $name
     * @param bool $flag_sanitize
     * @return null|string
     */
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

    /**
     * @param $sql
     * @return int
     */
    public function get_count_by_sql($sql)
    {
        return (int)$this->get_first_row_by_sql($sql);
    }

    /**
     * @param $sql
     * @return bool
     */
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

    /**
     * @param      $sql
     * @param bool $force
     * @return bool
     */
    public function get_row_by_sql($sql, $force = false)
    {
        $res = $this->query($sql, 0, 0, $force);
        if (!$res) {
            return false;
        }

        $row = $this->_db->fetchArray($res);

        return $row;
    }

    /**
     * @param      $sql
     * @param int  $limit
     * @param int  $offset
     * @param null $key
     * @param bool $force
     * @return array|bool
     */
    public function get_rows_by_sql($sql, $limit = 0, $offset = 0, $key = null, $force = false)
    {
        $arr = [];

        $res = $this->query($sql, $limit, $offset, $force);
        if (!$res) {
            return false;
        }

        while (false !== ($row = $this->_db->fetchArray($res))) {
            if ($key && isset($row[$key])) {
                $arr[$row[$key]] = $row;
            } else {
                $arr[] = $row;
            }
        }

        return $arr;
    }

    /**
     * @param     $sql
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function get_first_rows_by_sql($sql, $limit = 0, $offset = 0)
    {
        $res = $this->query($sql, $limit, $offset);
        if (!$res) {
            return false;
        }

        $arr = [];

        while (false !== ($row = $this->_db->fetchRow($res))) {
            $arr[] = $row[0];
        }

        return $arr;
    }

    /**
     * @param      $sql
     * @param int  $limit
     * @param int  $offset
     * @param bool $force
     * @return mixed
     */
    public function query($sql, $limit = 0, $offset = 0, $force = false)
    {
        // BUG: echo sql always if error
        $flag_echo_sql = false;

        if ($force) {
            return $this->queryF($sql, $limit, $offset);
        }

        $sql_full = $sql . ': limit=' . $limit . ' :offset=' . $offset;

        if ($this->_DEBUG_SQL) {
            $flag_echo_sql = true;
            echo $this->sanitize($sql_full) . "<br>\n";
        }

        $res = $this->_db->query($sql, (int)$limit, (int)$offset);
        if (!$res) {
            $error = $this->_db->error();
            if (empty($error)) {
                $error = 'Database update not allowed during processing of a GET request';
            }
            $this->set_error($error);
            if ($this->_DEBUG_SQL && !$flag_echo_sql) {
                echo $this->sanitize($sql_full) . "<br>\n";
            }
            if ($this->_DEBUG_ERROR) {
                echo $this->highlight($this->sanitize($error)) . "<br>\n";
            }
            if ($this->_DEBUG_ERROR > 1) {
                debug_print_backtrace();
            }
        }

        return $res;
    }

    /**
     * @param     $sql
     * @param int $limit
     * @param int $offset
     * @return mixed
     */
    public function queryF($sql, $limit = 0, $offset = 0)
    {
        if ($this->_DEBUG_SQL) {
            echo $this->sanitize($sql) . ': limit=' . $limit . ' :offset=' . $offset . "<br>\n";
        }

        $res = $this->_db->queryF($sql, (int)$limit, (int)$offset);
        if (!$res) {
            $error = $this->_db->error();
            $this->set_error($error);

            if ($this->_DEBUG_ERROR) {
                echo $this->highlight($this->sanitize($error)) . "<br>\n";
            }
        }

        return $res;
    }

    /**
     * @param $str
     * @return string
     */
    public function quote($str)
    {
        $str = "'" . addslashes($str) . "'";

        return $str;
    }

    //---------------------------------------------------------
    // search
    //---------------------------------------------------------

    /**
     * @param        $keyword_array
     * @param        $name
     * @param string $andor
     * @return null|string
     */
    public function build_where_by_keyword_array($keyword_array, $name, $andor = 'AND')
    {
        if (!is_array($keyword_array) || !count($keyword_array)) {
            return null;
        }

        switch (mb_strtolower($andor)) {
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

        $arr = [];

        foreach ($keyword_array as $keyword) {
            $keyword = trim($keyword);
            if ($keyword) {
                $arr[] = $this->build_where_keyword_single($keyword, $name);
            }
        }

        if (is_array($arr) && count($arr)) {
            $glue = ' ' . $andor_glue . ' ';
            $where = ' ( ' . implode($glue, $arr) . ' ) ';

            return $where;
        }

        return null;
    }

    /**
     * @param $str
     * @param $name
     * @return string
     */
    public function build_where_keyword_single($str, $name)
    {
        $text = $name . " LIKE '%" . addslashes($str) . "%'";

        return $text;
    }

    //---------------------------------------------------------
    // permission
    //---------------------------------------------------------

    /**
     * @param        $row
     * @param        $name
     * @param null   $groups
     * @param string $key
     * @return bool
     */
    public function check_cached_perm_by_row_name_groups_key($row, $name, $groups = null, $key = '0')
    {
        $id = $row[$this->_id_name];
        if (isset($this->_cached_perm_key_array[$id][$key])) {
            return $this->_cached_perm_key_array[$id][$key];
        }
        $ret = $this->check_perm_by_row_name_groups($row, $name, $groups);
        $this->_cached_perm_key_array[$id][$key] = $ret;

        return $ret;
    }

    /**
     * @param      $id_array
     * @param      $name
     * @param null $groups
     * @return array
     */
    public function build_id_array_with_perm($id_array, $name, $groups = null)
    {
        $arr = [];
        foreach ($id_array as $id) {
            if ($this->check_perm_by_id_name_groups($id, $name, $groups)) {
                $arr[] = $id;
            }
        }

        return $arr;
    }

    /**
     * @param      $rows
     * @param      $name
     * @param null $groups
     * @return array
     */
    public function build_rows_with_perm($rows, $name, $groups = null)
    {
        $arr = [];
        foreach ($rows as $row) {
            if ($this->check_perm_by_row_name_groups($row, $name, $groups)) {
                $arr[] = $row;
            }
        }

        return $arr;
    }

    /**
     * @param      $id
     * @param      $name
     * @param null $groups
     * @return bool
     */
    public function check_perm_by_id_name_groups($id, $name, $groups = null)
    {
        $row = $this->get_cached_row_by_id($id);

        return $this->check_perm_by_row_name_groups($row, $name, $groups);
    }

    /**
     * @param      $row
     * @param      $name
     * @param null $groups
     * @param bool $flag_admin
     * @return bool
     */
    public function check_perm_by_row_name_groups($row, $name, $groups = null, $flag_admin = false)
    {
        if (empty($name)) {
            return true;
        }

        if (!isset($row[$name])) {
            return false;
        }

        $val = $row[$name];

        if ($flag_admin && $this->_is_module_admin) {
            return true;
        }

        if ($this->_PERM_ALLOW_ALL && ($val == $this->_PERM_ALLOW_ALL)) {
            return true;
        }

        if ($this->_PERM_DENOY_ALL && ($val == $this->_PERM_DENOY_ALL)) {
            return false;
        }

        $perms = $this->str_to_array($val, $this->_PERM_SEPARATOR);

        return $this->check_perms_in_groups($perms, $groups);
    }

    /**
     * @param      $perm
     * @param null $groups
     * @return bool
     */
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

    /**
     * @param      $perms
     * @param null $groups
     * @return bool
     */
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

    /**
     * @param $row
     * @param $name
     * @return array
     */
    public function get_perm_array_by_row_name($row, $name)
    {
        if (isset($row[$name])) {
            return $this->get_perm_array($row[$name]);
        }

        return [];
    }

    /**
     * @param $val
     * @return array
     */
    public function get_perm_array($val)
    {
        return $this->str_to_array($val, $this->_PERM_SEPARATOR);
    }

    //---------------------------------------------------------
    // selbox
    //---------------------------------------------------------

    /**
     * @param string $name
     * @param int    $value
     * @param int    $none
     * @param string $onchange
     * @return string
     */
    public function build_form_selbox($name = '', $value = 0, $none = 0, $onchange = '')
    {
        return $this->build_form_select_list($this->get_rows_by_orderby($this->_title_name), $this->_title_name, $value, $none, $name, $onchange);
    }

    /**
     * @param        $rows
     * @param string $title_name
     * @param int    $preset_id
     * @param int    $none
     * @param string $sel_name
     * @param string $onchange
     * @return string
     */
    public function build_form_select_list($rows, $title_name = '', $preset_id = 0, $none = 0, $sel_name = '', $onchange = '')
    {
        $str = $this->build_form_select_tag($sel_name, $onchange);
        $str .= $this->build_form_select_option_none($none);
        $str .= $this->build_form_select_options($rows, $title_name, $preset_id);
        $str .= $this->build_form_select_tag_close();

        return $str;
    }

    /**
     * @param string $sel_name
     * @param string $onchange
     * @return string
     */
    public function build_form_select_tag($sel_name = '', $onchange = '')
    {
        if (empty($sel_name)) {
            $sel_name = $this->_id_name;
        }

        $str = '<select name="' . $sel_name . '" ';
        if ('' != $onchange) {
            $str .= ' onchange="' . $onchange . '" ';
        }
        $str .= ">\n";

        return $str;
    }

    /**
     * @return string
     */
    public function build_form_select_tag_close()
    {
        return "</select>\n";
    }

    /**
     * @param $none
     * @return string
     */
    public function build_form_select_option_none($none)
    {
        $str = '';
        if ($none) {
            $str .= $this->build_form_option(0, $this->_NONE_VALUE);
        }

        return $str;
    }

    /**
     * @param        $rows
     * @param string $title_name
     * @param int    $preset_id
     * @return null|string
     */
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

    /**
     * @param      $rows
     * @param      $title_name
     * @param      $preset_id
     * @param      $perm_post
     * @param      $perm_read
     * @param      $show
     * @param bool $flag_admin
     * @return null|string
     */
    public function build_form_select_options_with_perm_post($rows, $title_name, $preset_id, $perm_post, $perm_read, $show, $flag_admin = false)
    {
        if (!is_array($rows) || !count($rows)) {
            return null;
        }

        if (empty($title_name)) {
            $title_name = $this->_title_name;
        }

        $flag_selected = false;
        $row_arr = [];
        $str = '';

        // set extra
        foreach ($rows as $row) {
            $extra = '';
            $selected = false;
            $disabled = false;

            // not permit read
            if (!$this->check_perm_by_row_name_groups($row, $perm_read, null, $flag_admin)) {
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
            if (!$this->check_perm_by_row_name_groups($row, $perm_post, null, $flag_admin)) {
                $disabled = true;
            }

            // both
            if ($selected && $disabled) {
                if ($flag_admin && $this->_is_module_admin) {
                    $disabled = false;
                } else {
                    $selected = false;
                }
            }

            // selected
            if ($selected) {
                $extra = $this->_FORM_SELECTED;
                $flag_selected = true;
            }

            // disabled
            if ($disabled) {
                $extra = $this->_FORM_DISABLED;
            }

            $row['extra'] = $extra;
            $row_arr[] = $row;
        }

        // build options
        foreach ($row_arr as $row) {
            $id = $row[$this->_id_name];
            $extra = $row['extra'];

            // only one first if no selected
            if (!$flag_selected && empty($extra)) {
                $flag_selected = true;
                $extra = $this->_FORM_SELECTED;
            }

            $str .= $this->build_form_option($id, $this->build_form_option_caption($row, $title_name), $extra);
        }

        return $str;
    }

    /**
     * @param      $value
     * @param      $caption
     * @param null $extra
     * @return string
     */
    public function build_form_option($value, $caption, $extra = null)
    {
        $str = '<option value="' . $value . '" ' . $extra . ' >';
        $str .= $caption;
        $str .= '</option >' . "\n";

        return $str;
    }

    /**
     * @param $row
     * @param $title_name
     * @return string
     */
    public function build_form_option_caption($row, $title_name)
    {
        // Notice [PHP]: Undefined index: prefix
        $prefix = '';
        if ($this->_use_prefix && isset($row[$this->_PREFIX_NAME])) {
            $prefix = $row[$this->_PREFIX_NAME];
            if ($prefix) {
                $prefix = str_replace($this->_PREFIX_MARK, $this->_PREFIX_BAR, $prefix) . ' ';
            }
        }

        $caption = $prefix . $this->sanitize($row[$title_name]);

        return $caption;
    }

    /**
     * @param $row
     * @param $preset_id
     * @return null|string
     */
    public function build_form_option_extra($row, $preset_id)
    {
        if ($this->build_form_option_match($row, $preset_id)) {
            return $this->_FORM_SELECTED;
        }

        return null;
    }

    /**
     * @param $row
     * @param $preset_id
     * @return bool
     */
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
     * @param $arr
     * @param $glue
     * @return bool|string
     */
    public function array_to_str($arr, $glue)
    {
        $val = false;
        if (is_array($arr) && count($arr)) {
            $val = implode($glue, $arr);
        }

        return $val;
    }

    /**
     * @param $arr
     * @param $glue
     * @return bool|string
     */
    public function array_to_perm($arr, $glue)
    {
        $val = $this->array_to_str($arr, $glue);
        if ($val) {
            $val = $glue . $val . $glue;
        }

        return $val;
    }

    /**
     * @param $arr_in
     * @return array|null
     */
    public function sanitize_array_int($arr_in)
    {
        if (!is_array($arr_in) || !count($arr_in)) {
            return null;
        }

        $arr_out = [];
        foreach ($arr_in as $in) {
            $arr_out[] = (int)$in;
        }

        return $arr_out;
    }

    //---------------------------------------------------------
    // xoops param
    //---------------------------------------------------------

    /**
     * @return array
     */
    public function _get_xoops_groups()
    {
        global $xoopsUser;
        if (is_object($xoopsUser)) {
            return $xoopsUser->getGroups();
        }

        return [XOOPS_GROUP_ANONYMOUS];
    }

    /**
     * @return bool|mixed
     */
    public function _get_xoops_mid()
    {
        global $xoopsModule;
        if (is_object($xoopsModule)) {
            return $xoopsModule->getVar('mid');
        }

        return false;
    }

    /**
     * @return bool
     */
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
