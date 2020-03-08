<?php
// $Id: comment.php,v 1.4 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// webphoto_inc_handler -> webphoto_inc_base_ini
// 2008-12-12 K.OHWADA
// getInstance() -> getSingleton()
// 2008-08-24 K.OHWADA
// table_photo -> table_item
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_inc_comment
//=========================================================

/**
 * Class webphoto_inc_comment
 */
class webphoto_inc_comment extends webphoto_inc_base_ini
{
    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_inc_comment constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct();
        $this->init_base_ini($dirname, $trust_dirname);
        $this->initHandler($dirname);
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
     * @param $item_id
     * @param $comments
     * @return mixed
     */
    public function update_photo_comments($item_id, $comments)
    {
        $sql = 'UPDATE ' . $this->prefix_dirname('item');
        $sql .= ' SET ';
        $sql .= 'item_comments=' . (int)$comments . ' ';
        $sql .= 'WHERE item_id=' . (int)$item_id;

        return $this->query($sql);
    }

    // --- class end ---
}
