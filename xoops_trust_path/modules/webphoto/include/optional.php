<?php
// $Id: optional.php,v 1.4 2008/08/25 19:28:06 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-08-24 K.OHWADA
// webphoto_get_language()
// 2008-07-01 K.OHWADA
// added  webphoto_include_once_trust()
// change webphoto_fct()
//---------------------------------------------------------

//---------------------------------------------------------
// $MY_DIRNAME WEBPHOTO_TRUST_PATH are set by caller
//---------------------------------------------------------

if (!defined('XOOPS_TRUST_PATH')) {
    die('not permit');
}

//=========================================================
// optional functions
// do not replace this file
//=========================================================
/**
 * @return null|string|string[]
 */
function webphoto_fct()
{
    $page_array = [
        'photo_id' => 'photo',
        'cat_id' => 'category',
    ];

    $d3_class = webphoto_d3_optional::getInstance();

    return $d3_class->get_fct($page_array);
}

/**
 * @param      $file
 * @param bool $debug
 * @return bool
 */
function webphoto_include_once_trust($file, $debug = true)
{
    $d3_class = webphoto_d3_optional::getInstance();
    $d3_class->init_trust(WEBPHOTO_TRUST_DIRNAME);

    return $d3_class->include_once_trust_file($file, $debug);
}

/**
 * @param      $file
 * @param null $dirname
 * @param bool $debug
 * @return bool
 */
function webphoto_include_once($file, $dirname = null, $debug = true)
{
    $d3_class = webphoto_d3_optional::getInstance();
    $d3_class->init(webphoto_get_dirname($dirname), WEBPHOTO_TRUST_DIRNAME);

    return $d3_class->include_once_file($file, $debug);
}

/**
 * @param      $file
 * @param null $dirname
 * @param null $language
 * @return bool
 */
function webphoto_include_once_language($file, $dirname = null, $language = null)
{
    $d3_class = webphoto_d3_optional::getInstance();
    $d3_class->init(webphoto_get_dirname($dirname), WEBPHOTO_TRUST_DIRNAME);
    $d3_class->set_language(webphoto_get_language($language));

    return $d3_class->include_once_language($file);
}

/**
 * @param      $file
 * @param null $dirname
 * @param null $language
 * @return bool
 */
function webphoto_include_language($file, $dirname = null, $language = null)
{
    $d3_class = webphoto_d3_optional::getInstance();
    $d3_class->init(webphoto_get_dirname($dirname), WEBPHOTO_TRUST_DIRNAME);
    $d3_class->set_language(webphoto_get_language($language));

    return $d3_class->include_language($file);
}

/**
 * @param      $file
 * @param null $dirname
 */
function webphoto_debug_msg($file, $dirname = null)
{
    $d3_class = webphoto_d3_optional::getInstance();
    $d3_class->init(webphoto_get_dirname($dirname), WEBPHOTO_TRUST_DIRNAME);

    return $d3_class->debug_msg_include_file($file);
}

/**
 * @param null $dirname
 * @return bool
 */
function webphoto_include_once_preload($dirname = null)
{
    $preload_class = webphoto_d3_preload::getInstance();
    $preload_class->init(webphoto_get_dirname($dirname), WEBPHOTO_TRUST_DIRNAME);

    return $preload_class->include_once_preload_files();
}

/**
 * @return bool
 */
function webphoto_include_once_preload_trust()
{
    $preload_class = webphoto_d3_preload::getInstance();
    $preload_class->init_trust(WEBPHOTO_TRUST_DIRNAME);

    return $preload_class->include_once_preload_trust_files();
}

/**
 * @param $dirname
 * @return mixed|null|string
 */
function webphoto_get_dirname($dirname)
{
    if (!defined('WEBPHOTO_TRUST_DIRNAME')) {
        die('not permit');
    }

    if (empty($dirname)) {
        if (defined('WEBPHOTO_DIRNAME')) {
            $dirname = WEBPHOTO_DIRNAME;
        } else {
            die('not permit');
        }
    }

    return $dirname;
}

/**
 * @param null $language
 */
function webphoto_get_language($language = null)
{
    if ($language) {
        return $language;
    }

    global $xoopsConfig;

    return $xoopsConfig['language'];
}
