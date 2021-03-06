<?php
// $Id: qr.php,v 1.2 2010/01/26 09:34:01 ohwada Exp $

//=========================================================
// webphoto module
// 2010-01-10 K.OHWADA
//=========================================================

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_qr
//=========================================================

/**
 * Class webphoto_qr
 */
class webphoto_qr extends webphoto_base_this
{
    public $_user_handler;

    public $_QR_MODULE_SIZE = 3;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_qr constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_user_handler = webphoto_user_handler::getInstance($dirname, $trust_dirname);
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_lib_error|\webphoto_qr
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
    // qr code
    //---------------------------------------------------------

    /**
     * @param $id
     */
    public function create_mobile_qr($id)
    {
        $file = $this->_QRS_DIR . '/' . $this->build_mobile_filename($id);
        if (!is_file($file)) {
            $qrimage = new Qrcode_image();
            $qrimage->set_module_size($this->_QR_MODULE_SIZE);
            $qrimage->qrcode_image_out($this->build_mobile_url($id), 'png', $file);
        }
    }

    /**
     * @param $photo_id
     * @return array
     */
    public function build_mobile_param($photo_id)
    {
        $arr = [
            'mobile_email' => $this->get_mobile_email(),
            'mobile_url' => $this->build_mobile_url($photo_id),
            'mobile_qr_image' => $this->build_mobile_filename($photo_id),
        ];

        return $arr;
    }

    /**
     * @param $id
     * @return string
     */
    public function build_mobile_url($id)
    {
        $url = $this->_MODULE_URL . '/i.php';
        if ($id > 0) {
            $url .= '?id=' . $id;
        }

        return $url;
    }

    /**
     * @param $id
     * @return string
     */
    public function build_mobile_filename($id)
    {
        $file = 'qr_index.png';
        if ($id > 0) {
            $file = 'qr_id_' . $id . '.png';
        }

        return $file;
    }

    /**
     * @return mixed|null
     */
    public function get_mobile_email()
    {
        $row = $this->_user_handler->get_row_by_uid($this->_xoops_uid);
        if (is_array($row)) {
            return $row['user_email'];
        }

        return null;
    }

    // --- class end ---
}
