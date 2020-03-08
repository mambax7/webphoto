<?php
// $Id: swf_create.php,v 1.4 2010/10/08 15:53:16 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-25 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-10-01 K.OHWADA
// create_swf() -> execute()
// 2009-11-11 K.OHWADA
// $trust_dirname
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_edit_swf_create
//=========================================================

/**
 * Class webphoto_edit_swf_create
 */
class webphoto_edit_swf_create extends webphoto_edit_base_create
{
    public $_ext_class;

    public $_param_ext = 'swf';
    public $_param_dir = 'swfs';
    public $_param_mime = 'application/x-shockwave-flash';
    public $_param_medium = '';
    public $_param_kind = _C_WEBPHOTO_FILE_KIND_SWF;
    public $_msg_created = 'create swf';
    public $_msg_failed = 'fail to create swf';

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------

    /**
     * webphoto_edit_swf_create constructor.
     * @param $dirname
     * @param $trust_dirname
     */
    public function __construct($dirname, $trust_dirname)
    {
        parent::__construct($dirname, $trust_dirname);

        $this->_ext_class = webphoto_ext::getInstance($dirname, $trust_dirname);
    }

    /**
     * @param null $dirname
     * @param null $trust_dirname
     * @return \webphoto_edit_swf_create|\webphoto_lib_error
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
    // create swf
    //---------------------------------------------------------

    /**
     * @param $param
     * @return array|null
     */
    public function create_param($param)
    {
        $this->clear_msg_array();

        $item_id = $param['item_id'];
        $src_file = $param['src_file'];
        $src_ext = $param['src_ext'];
        $src_kind = $param['src_kind'];

        // return input file is swf
        if ($this->is_swf_ext($src_ext)) {
            return null;
        }

        $swf_param = $this->create_swf($item_id, $src_file, $src_ext);
        if (!is_array($swf_param)) {
            return null;
        }

        return $swf_param;
    }

    /**
     * @param $item_id
     * @param $src_file
     * @param $src_ext
     * @return array|null
     */
    public function create_swf($item_id, $src_file, $src_ext)
    {
        $name_param = $this->build_name_param($item_id);
        $file = $name_param['file'];

        $param = [
            'src_file' => $src_file,
            'src_ext' => $src_ext,
            'swf_file' => $file,
        ];

        $ret = $this->_ext_class->execute('swf', $param);

        return $this->build_result($ret, $name_param);
    }

    // --- class end ---
}
