<?php
// $Id: vote_table_manage.php,v 1.2 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_vote_handler
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_admin_vote_table_manage
//=========================================================

/**
 * Class webphoto_admin_vote_table_manage
 */
class webphoto_admin_vote_table_manage extends webphoto_lib_manage
{
    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_admin_vote_table_manage constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);
        $this->set_manage_handler(webphoto_vote_handler::getInstance($dirname, $trust_dirname));
        $this->set_manage_title_by_name('VOTE_TABLE_MANAGE');

        $this->set_manage_list_column_array(['vote_photo_id', 'vote_uid']);
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_admin_vote_table_manage|\webphoto_lib_element|\webphoto_lib_error|\webphoto_lib_form
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
            'vote_id' => $this->_post_class->get_post_get_int('vote_id'),
            'vote_time_create' => $this->_post_class->get_post_int('vote_time_create'),
            'vote_time_update' => $this->_post_class->get_post_int('vote_time_update'),
            'vote_photo_id' => $this->_post_class->get_post_int('vote_photo_id'),
            'vote_uid' => $this->_post_class->get_post_int('vote_uid'),
            'vote_rating' => $this->_post_class->get_post_int('vote_rating'),
            'vote_hostname' => $this->_post_class->get_post_text('vote_hostname'),
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
        echo $this->build_comp_text('vote_time_create');
        echo $this->build_comp_text('vote_time_update');
        echo $this->build_comp_text('vote_photo_id');
        echo $this->build_comp_text('vote_rating');
        echo $this->build_comp_text('vote_hostname');

        echo $this->build_manage_submit();

        echo "</table></form>\n";
    }

    // --- class end ---
}
