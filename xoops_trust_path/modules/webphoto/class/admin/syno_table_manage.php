<?php
// $Id: syno_table_manage.php,v 1.2 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_synoHandler
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_admin_syno_table_manage
//=========================================================

/**
 * Class webphoto_admin_syno_table_manage
 */
class webphoto_admin_syno_table_manage extends webphoto_lib_manage
{
    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_admin_syno_table_manage constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);
        $this->set_manage_handler(webphoto_synoHandler::getInstance($dirname, $trust_dirname));
        $this->set_manage_title_by_name('SYNO_TABLE_MANAGE');

        $this->set_manage_sub_title_array(['ID ascent', 'ID descent', 'Weight ascent', 'Weight descent']);

        $this->set_manage_list_column_array(['syno_weight', 'syno_key', 'syno_value']);
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_admin_syno_table_manage|\webphoto_lib_element|\webphoto_lib_error|\webphoto_lib_form
     */
    public static function getInstance($dirname = null, $trust_dirname = null)
    {
        static $instance;
        if (null === $instance) {
            $instance = new self($dirname, $trust_dirname);
        }

        return $instance;
    }

    //---------------------------------------------------------
    // main
    //---------------------------------------------------------
    public function main()
    {
        $this->_main();
    }

    //=========================================================
    // override for caller
    //=========================================================

    /**
     * @return array|void
     */
    public function _build_row_by_post()
    {
        $row = [
            'syno_id' => $this->_post_class->get_post_get_int('syno_id'),
            'syno_time_create' => $this->_post_class->get_post_int('syno_time_create'),
            'syno_time_update' => $this->_post_class->get_post_int('syno_time_update'),
            'syno_weight' => $this->_post_class->get_post_int('syno_weight'),
            'syno_key' => $this->_post_class->get_post_text('syno_key'),
            'syno_value' => $this->_post_class->get_post_text('syno_value'),
        ];

        return $row;
    }

    //---------------------------------------------------------
    // list
    //---------------------------------------------------------

    /**
     * @return mixed
     */
    public function _get_list_total()
    {
        switch ($this->pagenavi_get_sortid()) {
            case 0:
            case 1:
            case 2:
            case 3:
            default:
                $total = $this->_manage_handler->get_count_all();
                break;
        }

        $this->_manage_total = $total;

        return $total;
    }

    /**
     * @param $limit
     * @param $start
     * @return mixed
     */
    public function _get_list_rows($limit, $start)
    {
        switch ($this->pagenavi_get_sortid()) {
            case 1:
                $rows = $this->_manage_handler->get_rows_all_desc($limit, $start);
                break;
            case 2:
                $rows = $this->_manage_handler->get_rows_orderby_weight_asc($limit, $start);
                break;
            case 3:
                $rows = $this->_manage_handler->get_rows_orderby_weight_desc($limit, $start);
                break;
            case 0:
            default:
                $rows = $this->_manage_handler->get_rows_all_asc($limit, $start);
                break;
        }

        return $rows;
    }

    //---------------------------------------------------------
    // form
    //---------------------------------------------------------

    /**
     * @param null $row
     */
    public function _print_form($row = null)
    {
        echo $this->build_manage_form_begin($row);

        echo $this->build_table_begin();
        echo $this->build_manage_header();

        echo $this->build_manage_id();
        echo $this->build_comp_text('syno_time_create');
        echo $this->build_comp_text('syno_time_update');
        echo $this->build_comp_text('syno_weight');
        echo $this->build_comp_text('syno_key');
        echo $this->build_comp_text('syno_value');

        echo $this->build_manage_submit();

        echo "</table></form>\n";
    }

    // --- class end ---
}
