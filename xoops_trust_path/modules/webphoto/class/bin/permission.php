<?php
// $Id: permission.php,v 1.2 2009/01/24 07:10:39 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-24 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-01-10 K.OHWADA
// has_html()
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// class webphoto_permission
// substitute for clsss/webphoto/permission.php
//=========================================================
class webphoto_permission
{
    public $_has_insertable       = true;
    public $_has_superinsert      = true;
    public $_has_editable         = true;
    public $_has_supereditable    = true;
    public $_has_deletable        = true;
    public $_has_superdeletable   = true;
    public $_has_touchothers      = true;
    public $_has_supertouchothers = true;
    public $_has_rateview         = true;
    public $_has_ratevote         = true;
    public $_has_tellafriend      = true;
    public $_has_tagedit          = true;
    public $_has_mail             = true;
    public $_has_file             = true;
    public $_has_html             = true;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct($dirname)
    {
        // dummy
    }

    public static function getInstance($dirname = null)
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new webphoto_permission($dirname);
        }
        return $instance;
    }

    //---------------------------------------------------------
    // has permit
    //---------------------------------------------------------
    public function has_insertable()
    {
        return $this->_has_insertable;
    }

    public function has_superinsert()
    {
        return $this->_has_superinsert;
    }

    public function has_editable()
    {
        return $this->_has_editable;
    }

    public function has_superedit()
    {
        return $this->_has_superedit;
    }

    public function has_deletable()
    {
        return $this->_has_deletable;
    }

    public function has_superdelete()
    {
        return $this->_has_superdelete;
    }

    public function has_touchothers()
    {
        return $this->_has_touchothers;
    }

    public function has_supertouchothers()
    {
        return $this->_has_supertouchothers;
    }

    public function has_rateview()
    {
        return $this->_has_rateview;
    }

    public function has_ratevote()
    {
        return $this->_has_ratevote;
    }

    public function has_tellafriend()
    {
        return $this->_has_tellafriend;
    }

    public function has_tagedit()
    {
        return $this->_has_tagedit;
    }

    public function has_mail()
    {
        return $this->_has_mail;
    }

    public function has_file()
    {
        return $this->_has_file;
    }

    public function has_html()
    {
        return $this->_has_html;
    }

    // --- class end ---
}
