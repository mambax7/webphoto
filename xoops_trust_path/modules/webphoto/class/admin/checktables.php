<?php
// $Id: checktables.php,v 1.7 2010/11/16 23:43:38 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-11-11 K.OHWADA
// get_file_full_by_kind()
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_mime_handler
// 2008-10-01 K.OHWADA
// player_handler
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
// 2008-08-01 K.OHWADA
// added webphoto_user_handler webphoto_maillog_handler
// 2008-07-01 K.OHWADA
// added $admin_link
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_admin_checktables
//=========================================================

/**
 * Class webphoto_admin_checktables
 */
class webphoto_admin_checktables extends webphoto_base_this
{
    public $_voteHandler;
    public $_gicon_handler;
    public $_mimeHandler;
    public $_tagHandler;
    public $_p2tHandler;
    public $_synoHandler;
    public $_user_handler;
    public $_maillog_handler;
    public $_player_handler;
    public $_flashvar_handler;
    public $_xoops_commentsHandler;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_admin_checktables constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_mimeHandler = webphoto_mime_handler::getInstance($dirname, $trust_dirname);
        $this->_voteHandler = webphoto_vote_handler::getInstance($dirname, $trust_dirname);
        $this->_gicon_handler = webphoto_gicon_handler::getInstance($dirname, $trust_dirname);
        $this->_tagHandler = webphoto_tagHandler::getInstance($dirname, $trust_dirname);
        $this->_p2tHandler = webphoto_p2tHandler::getInstance($dirname, $trust_dirname);
        $this->_synoHandler = webphoto_synoHandler::getInstance($dirname, $trust_dirname);
        $this->_user_handler = webphoto_user_handler::getInstance($dirname, $trust_dirname);
        $this->_maillog_handler = webphoto_maillog_handler::getInstance($dirname, $trust_dirname);
        $this->_player_handler = webphoto_player_handler::getInstance($dirname, $trust_dirname);
        $this->_flashvar_handler = webphoto_flashvar_handler::getInstance($dirname, $trust_dirname);

        $this->_xoops_commentsHandler = webphoto_xoops_commentsHandler::getInstance();
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_admin_checktables|\webphoto_lib_error
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
    public function main()
    {
        xoops_cp_header();

        echo $this->build_admin_menu();
        echo $this->build_admin_title('CHECKTABLES');

        $this->_print_check();

        xoops_cp_footer();
    }

    //---------------------------------------------------------
    // check
    //---------------------------------------------------------
    public function _print_check()
    {
        $cfg_makethumb = $this->_config_class->get_by_name('makethumb');

        //
        // TABLE CHECK
        //
        echo '<h4>' . _AM_WEBPHOTO_H4_TABLE . "</h4>\n";

        echo _WEBPHOTO_ITEM_TABLE . ': ';
        echo $this->_item_handler->get_table();
        echo ' &nbsp; ';

        echo _AM_WEBPHOTO_NUMBEROFPHOTOS . ': ';
        echo $this->_item_handler->get_count_all();
        echo "<br><br>\n";

        echo _WEBPHOTO_FILE_TABLE . ': ';
        echo $this->_fileHandler->get_table();
        echo ' &nbsp; ';

        echo _AM_WEBPHOTO_NUMBEROFPHOTOS . ': ';
        echo $this->_fileHandler->get_count_all();
        echo "<br><br>\n";

        echo _WEBPHOTO_CAT_TABLE . ': ';
        echo $this->_catHandler->get_table();
        echo ' &nbsp; ';

        echo _AM_WEBPHOTO_NUMBEROFCATEGORIES . ': ';
        echo $this->_catHandler->get_count_all();
        echo "<br><br>\n";

        echo _WEBPHOTO_VOTE_TABLE . ': ';
        echo $this->_voteHandler->get_table();
        echo ' &nbsp; ';

        echo _AM_WEBPHOTO_NUMBEROFVOTEDATA . ': ';
        echo $this->_voteHandler->get_count_all();
        echo "<br><br>\n";

        echo _WEBPHOTO_GICON_TABLE . ': ';
        echo $this->_gicon_handler->get_table();
        echo ' &nbsp; ';

        echo _AM_WEBPHOTO_NUMBEROFRECORED . ': ';
        echo $this->_gicon_handler->get_count_all();
        echo "<br><br>\n";

        echo _WEBPHOTO_MIME_TABLE . ': ';
        echo $this->_mimeHandler->get_table();
        echo ' &nbsp; ';

        echo _AM_WEBPHOTO_NUMBEROFRECORED . ': ';
        echo $this->_mimeHandler->get_count_all();
        echo "<br><br>\n";

        echo _WEBPHOTO_TAG_TABLE . ': ';
        echo $this->_tagHandler->get_table();
        echo ' &nbsp; ';

        echo _AM_WEBPHOTO_NUMBEROFRECORED . ': ';
        echo $this->_tagHandler->get_count_all();
        echo "<br><br>\n";

        echo _WEBPHOTO_P2T_TABLE . ': ';
        echo $this->_p2tHandler->get_table();
        echo ' &nbsp; ';

        echo _AM_WEBPHOTO_NUMBEROFRECORED . ': ';
        echo $this->_p2tHandler->get_count_all();
        echo "<br><br>\n";

        echo _WEBPHOTO_SYNO_TABLE . ': ';
        echo $this->_synoHandler->get_table();
        echo ' &nbsp; ';

        echo _AM_WEBPHOTO_NUMBEROFRECORED . ': ';
        echo $this->_synoHandler->get_count_all();
        echo "<br><br>\n";

        echo _WEBPHOTO_USER_TABLE . ': ';
        echo $this->_user_handler->get_table();
        echo ' &nbsp; ';

        echo _AM_WEBPHOTO_NUMBEROFRECORED . ': ';
        echo $this->_user_handler->get_count_all();
        echo "<br><br>\n";

        echo _WEBPHOTO_MAILLOG_TABLE . ': ';
        echo $this->_maillog_handler->get_table();
        echo ' &nbsp; ';

        echo _AM_WEBPHOTO_NUMBEROFRECORED . ': ';
        echo $this->_maillog_handler->get_count_all();
        echo "<br><br>\n";

        echo _WEBPHOTO_PLAYER_TABLE . ': ';
        echo $this->_player_handler->get_table();
        echo ' &nbsp; ';

        echo _AM_WEBPHOTO_NUMBEROFRECORED . ': ';
        echo $this->_player_handler->get_count_all();
        echo "<br><br>\n";

        echo _WEBPHOTO_FLASHVAR_TABLE . ': ';
        echo $this->_flashvar_handler->get_table();
        echo ' &nbsp; ';

        echo _AM_WEBPHOTO_NUMBEROFRECORED . ': ';
        echo $this->_flashvar_handler->get_count_all();
        echo "<br><br>\n";

        echo _AM_WEBPHOTO_COMMENTSTABLE . ': ';
        echo $this->_xoops_commentsHandler->get_table();
        echo ' &nbsp; ';

        echo _AM_WEBPHOTO_NUMBEROFCOMMENTS . ': ';
        echo $this->_xoops_commentsHandler->get_count_by_modid($this->_MODULE_ID);
        echo "<br><br>\n";

        //
        // CONSISTEMCY CHECK
        //
        echo '<h4>' . _AM_WEBPHOTO_H4_PHOTOLINK . "</h4>\n";
        echo _AM_WEBPHOTO_NOWCHECKING;

        $dead = [];
        for ($i = 1; $i <= _C_WEBPHOTO_MAX_ITEM_FILE_ID; ++$i) {
            $dead[$i] = 0;
        }

        $item_rows = $this->_item_handler->get_rows_all_asc();
        foreach ($item_rows as $item_row) {
            $item_id = $item_row['item_id'];
            $item_ext = $item_row['item_ext'];

            $admin_url = $this->_MODULE_URL . '/admin/index.php?fct=item_table_manage&amp;op=form&amp;id=' . $item_id;
            $admin_link = '<a href="' . $admin_url . '" target="_blank">' . sprintf('%04d', $item_id) . '</a> : ' . "\n";

            echo '. ';

            for ($i = 1; $i <= _C_WEBPHOTO_MAX_ITEM_FILE_ID; ++$i) {
                $file_full = $this->get_file_full_by_kind($item_row, $i);
                if ($file_full && !is_readable($file_full)) {
                    $name = $this->get_constant('FILE_KIND_' . $i);
                    echo "<br>\n";
                    echo $admin_link;
                    printf(_AM_WEBPHOTO_FMT_NOT_READABLE, $name, $file_full);
                    echo "<br>\n";
                    $dead[$i]++;
                }
            }
        }

        // show result
        $dead_photos = $dead[_C_WEBPHOTO_FILE_KIND_CONT];
        $dead_thumbs = $dead[_C_WEBPHOTO_FILE_KIND_THUMB];

        if (0 == $dead_photos) {
            if (!$cfg_makethumb || 0 == $dead_thumbs) {
                $this->_print_green('ok');
            } else {
                $msg = sprintf(_AM_WEBPHOTO_FMT_NUMBEROFDEADTHUMBS, $dead_thumbs);
                echo "<br>\n";
                $this->_print_red($msg);
                echo "<br>\n";
                echo $this->_build_form_redo_thumbs();
            }
        } else {
            $msg = sprintf(_AM_WEBPHOTO_FMT_NUMBEROFDEADPHOTOS, $dead_photos);
            echo "<br>\n";
            $this->_print_red($msg);
            echo "<br>\n";
            echo $this->_build_form_remove_rec();
        }
    }

    /**
     * @return string
     */
    public function _build_form_redo_thumbs()
    {
        $text = '<form action="' . $this->_ADMIN_INDEX_PHP . '" method="post">' . "\n";
        $text .= '<input type="hidden" name="fct" value="redothumbs" >' . "\n";
        $text .= '<input type="submit" value="' . _AM_WEBPHOTO_LINK_REDOTHUMBS . '" >' . "\n";
        $text .= "</form>\n";

        return $text;
    }

    /**
     * @return string
     */
    public function _build_form_remove_rec()
    {
        $text = '<form action="' . $this->_ADMIN_INDEX_PHP . '" method="post">' . "\n";
        $text .= '<input type="hidden" name="fct" value="redothumbs" >' . "\n";
        $text .= '<input type="hidden" name="removerec" value="1" >' . "\n";
        $text .= '<input type="submit" value="' . _AM_WEBPHOTO_LINK_TABLEMAINTENANCE . '" >' . "\n";
        $text .= "</form>\n";

        return $text;
    }

    /**
     * @param      $val
     * @param bool $flag_red
     */
    public function _print_on_off($val, $flag_red = false)
    {
        if ($val) {
            $this->_print_green('on');
        } elseif ($flag_red) {
            $this->_print_red('off');
        } else {
            $this->_print_green('off');
        }
    }

    /**
     * @param $str
     */
    public function _print_red($str)
    {
        echo '<font color="#FF0000"><b>' . $str . '</b></font>' . "<br>\n";
    }

    /**
     * @param $str
     */
    public function _print_green($str)
    {
        echo '<font color="#00FF00"><b>' . $str . '</b></font>' . "<br>\n";
    }

    // --- class end ---
}
