<?php
// $Id: search.inc.php,v 1.2 2008/06/21 18:25:12 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if (!defined('XOOPS_TRUST_PATH')) {
    die('set XOOPS_TRUST_PATH into mainfile.php');
}

$MY_DIRNAME = basename(dirname(__DIR__));

require XOOPS_ROOT_PATH . '/modules/' . $MY_DIRNAME . '/include/mytrustdirname.php'; // set $mytrustdirname
require XOOPS_TRUST_PATH . '/modules/' . $MY_TRUST_DIRNAME . '/include/search.inc.php';
