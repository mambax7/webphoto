<?php
// $Id: tag_table_manage.php,v 1.2 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_tagHandler
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_admin_tag_table_manage
//=========================================================

/**
 * Class webphoto_admin_tag_table_manage
 */
class webphoto_admin_tag_table_manage extends webphoto_lib_manage
{
    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_admin_tag_table_manage constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);
        $this->set_manage_handler(webphoto_tagHandler::getInstance($dirname, $trust_dirname));
        $this->set_manage_title_by_name('TAG_TABLE_MANAGE');

        $this->set_manage_list_column_array(['tag_name']);
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_admin_tag_table_manage|\webphoto_lib_element|\webphoto_lib_error|\webphoto_lib_form
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
            'tag_id' => $this->_post_class->get_post_get_int('tag_id'),
            'tag_time_create' => $this->_post_class->get_post_int('tag_time_create'),
            'tag_time_update' => $this->_post_class->get_post_int('tag_time_update'),
            'tag_name' => $this->_post_class->get_post_text('tag_name'),
        ];

        return $row;
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
        echo $this->build_comp_text('tag_time_create');
        echo $this->build_comp_text('tag_time_update');
        echo $this->build_comp_text('tag_name');

        echo $this->build_manage_submit();

        echo "</table></form>\n";
    }

    // --- class end ---
}
