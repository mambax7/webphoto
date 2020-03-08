<?php
// $Id: notification_event.php,v 1.3 2009/12/16 13:32:34 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-12-06 K.OHWADA
// notify_waiting()
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_cat_handler
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_notification
//=========================================================

/**
 * Class webphoto_notification_event
 */
class webphoto_notification_event extends webphoto_d3_notification_event
{
    public $_catHandler;
    public $_uri_class;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_notification_event constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct();
        $this->init($dirname, $trust_dirname);

        $this->_catHandler = webphoto_cat_handler::getInstance($dirname, $trust_dirname);
        $this->_uri_class = webphoto_uri::getInstance($dirname);
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_d3_notification_event|\webphoto_notification_event
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
    // function
    //---------------------------------------------------------

    /**
     * @param $photo_id
     * @param $cat_id
     * @param $photo_title
     */
    public function notify_new_photo($photo_id, $cat_id, $photo_title)
    {
        $cat_title = $this->_catHandler->get_cached_value_by_id_name($cat_id, 'cat_title');

        $photo_uri = $this->_uri_class->build_photo($photo_id);

        // Global Notification
        $photo_tags = [
            'PHOTO_TITLE' => $photo_title,
            'PHOTO_URI' => $photo_uri,
        ];

        $this->trigger_event('global', 0, 'new_photo', $photo_tags);

        // Category Notification
        if ($cat_title) {
            $cat_tags = [
                'PHOTO_TITLE' => $photo_title,
                'CATEGORY_TITLE' => $cat_title,
                'PHOTO_URI' => $photo_uri,
            ];

            $this->trigger_event('category', $cat_id, 'new_photo', $cat_tags);
        }
    }

    /**
     * @param $photo_id
     * @param $photo_title
     */
    public function notify_waiting($photo_id, $photo_title)
    {
        $url = $this->_MODULE_URL . '/admin/index.php?fct=item_manager&op=modify_form&item_id=' . $photo_id;
        $tags = [
            'PHOTO_TITLE' => $photo_title,
            'WAITING_URL' => $url,
        ];
        $this->trigger_event('global', 0, 'waiting', $tags);
    }

    // --- class end ---
}
