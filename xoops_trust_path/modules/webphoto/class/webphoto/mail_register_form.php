<?php
// $Id: mail_register_form.php,v 1.2 2008/08/25 19:28:05 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-08-24 K.OHWADA
// added print_user_form()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_mail_register_form
//=========================================================

/**
 * Class webphoto_mail_register_form
 */
class webphoto_mail_register_form extends webphoto_form_this
{
    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_mail_register_form constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_mail_register_form
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
    // user form
    //---------------------------------------------------------

    /**
     * @param $row
     */
    public function print_user_form($row)
    {
        $this->set_row($row);

        echo $this->build_form_begin();
        echo $this->build_input_hidden('op', 'form');
        echo $this->build_input_hidden('fct', 'mail_register');

        echo $this->build_table_begin();
        echo $this->build_line_title($this->get_constant('TITLE_MAIL_REGISTER'));

        echo $this->build_line_ele($this->get_constant('CAT_USER'), $this->_build_ele_user_submitter());

        echo $this->build_line_ele('', $this->build_input_submit('submit', $this->get_constant('BUTTON_REGISTER')));

        echo $this->build_table_end();
        echo $this->build_form_end();
    }

    /**
     * @return mixed
     */
    public function _build_ele_user_submitter()
    {
        $uid = $this->get_row_by_key('user_uid');
        $text = $this->build_form_user_select('user_uid', $uid, false);

        return $text;
    }

    //---------------------------------------------------------
    // submit form
    //---------------------------------------------------------

    /**
     * @param $row
     * @param $param
     */
    public function print_submit_form($row, $param)
    {
        $mode = $param['mode'];

        switch ($mode) {
            case 'edit':
                $submit = _EDIT;
                break;
            case 'add':
            default:
                $submit = $this->get_constant('BUTTON_REGISTER');
                break;
        }

        $this->set_row($row);

        echo $this->build_form_begin();
        echo $this->build_html_token();
        echo $this->build_input_hidden('op', 'submit');
        echo $this->build_input_hidden('fct', 'mail_register');

        echo $this->build_table_begin();
        echo $this->build_line_title($this->get_constant('TITLE_MAIL_REGISTER'));

        echo $this->build_line_ele($this->get_constant('CAT_USER'), $this->_build_ele_submitter());

        echo $this->build_line_ele($this->get_constant('CATEGORY'), $this->_build_ele_category());

        echo $this->build_row_text($this->get_constant('USER_EMAIL'), 'user_email');

        echo $this->build_line_ele('', $this->build_input_submit('submit', $submit));

        echo $this->build_table_end();
        echo $this->build_form_end();
    }

    /**
     * @return mixed
     */
    public function _build_ele_category()
    {
        return $this->_catHandler->build_selbox_with_perm_post($this->get_row_by_key('user_cat_id'), 'user_cat_id');
    }

    /**
     * @return string
     */
    public function _build_ele_submitter()
    {
        $uid = $this->get_row_by_key('user_uid');
        $text = $this->_xoops_class->get_user_uname_from_id($uid);
        $text .= ' ';
        $text .= $this->build_input_hidden('user_uid', $uid);

        return $text;
    }

    // --- class end ---
}
