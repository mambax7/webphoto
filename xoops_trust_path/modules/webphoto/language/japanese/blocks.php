<?php
// $Id: blocks.php,v 1.10 2010/10/10 11:02:10 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-10-01 K.OHWADA
// �̿� -> �̿���ư�衦��ǥ���
// 2010-04-04 K.OHWADA
// use $mydirname
//---------------------------------------------------------

// test
if (defined('FOR_XOOPS_LANG_CHECKER')) {
    $MY_DIRNAME = 'webphoto';
}

if (!isset($MY_DIRNAME)) {
    // call by altsys D3LanguageManager
    if (isset($mydirname)) {
        $MY_DIRNAME = $mydirname;

    // probably error
    } elseif (isset($GLOBALS['MY_DIRNAME'])) {
        $MY_DIRNAME = $GLOBALS['MY_DIRNAME'];
    } else {
        $MY_DIRNAME = 'webphoto';
    }
}

$constpref = mb_strtoupper('_BL_' . $MY_DIRNAME . '_');

// === define begin ===
if (!defined($constpref . 'LANG_LOADED')) {
    define($constpref . 'LANG_LOADED', 1);

    //=========================================================
    // same as myalbum
    //=========================================================

    define($constpref . 'BTITLE_TOPNEW', '�ǿ��μ̿���ư�衦��ǥ���');
    define($constpref . 'BTITLE_TOPHIT', '�ҥåȿ���¿���̿���ư�衦��ǥ���');
    define($constpref . 'BTITLE_RANDOM', '�ԥå����åפμ̿���ư�衦��ǥ���');
    define($constpref . 'TEXT_DISP', 'ɽ����');
    define($constpref . 'TEXT_STRLENGTH', '�̿���ư�衦��ǥ����Υ����ȥ�κ���ɽ��ʸ����');
    define($constpref . 'TEXT_CATLIMITATION', '���ƥ������');

    // v2.30
    define($constpref . 'TEXT_CATLIMITRECURSIVE', '���֥��ƥ�����о�<br>���ƥ������ΤȤ�ͭ��');

    define($constpref . 'TEXT_BLOCK_WIDTH', '����ɽ��������');
    define($constpref . 'TEXT_BLOCK_WIDTH_NOTES', '�ʢ� ������0�ˤ�����硢����ͥ�������򤽤ΤޤޤΥ�������ɽ�����ޤ���');
    define($constpref . 'TEXT_RANDOMCYCLE', '�������ڤ��ؤ�������ñ�̤��á�');
    define($constpref . 'TEXT_COLS', '�̿���ư�衦��ǥ��������');

    //---------------------------------------------------------
    // v0.20
    //---------------------------------------------------------
    define($constpref . 'POPBOX_REVERT', '����å�����ȡ����ξ����������ˤʤ�');

    //---------------------------------------------------------
    // v0.30
    //---------------------------------------------------------
    define($constpref . 'TEXT_CACHETIME', '����å������');

    //---------------------------------------------------------
    // v0.80
    //---------------------------------------------------------
    define($constpref . 'TEXT_CATLIST_SUB', '���֥��ƥ����ɽ��');
    define($constpref . 'TEXT_CATLIST_MAIN_IMG', '�ᥤ�󥫥ƥ���μ̿���ư�衦��ǥ�����ɽ��');
    define($constpref . 'TEXT_CATLIST_SUB_IMG', '���֥��ƥ���μ̿���ư�衦��ǥ�����ɽ��');
    define($constpref . 'TEXT_CATLIST_COLS', '�����¤٤륫�ƥ���ο�');
    define($constpref . 'TEXT_TAGCLOUD_LIMIT', '������ɽ�������');

    //---------------------------------------------------------
    // v1.20
    //---------------------------------------------------------
    // google map
    define($constpref . 'GMAP_MODE', 'GoogleMap �⡼��');
    define($constpref . 'GMAP_MODE_NONE', '��ɽ��');
    define($constpref . 'GMAP_MODE_DEFAULT', '�ǥե����');
    define($constpref . 'GMAP_MODE_SET', '������������');
    define($constpref . 'GMAP_LATITUDE', '����');
    define($constpref . 'GMAP_LONGITUDE', '����');
    define($constpref . 'GMAP_ZOOM', '������');
    define($constpref . 'GMAP_HEIGHT', 'ɽ���ι⤵');
    define($constpref . 'PIXEL', '�ԥ�����');

    //---------------------------------------------------------
    // v1.40
    //---------------------------------------------------------
    define($constpref . 'TIMELINE_LATEST', '������������ɽ������̿���ư�衦��ǥ����ο�');
    define($constpref . 'TIMELINE_RANDOM', '�������ɽ������̿���ư�衦��ǥ����ο�');
    define($constpref . 'TIMELINE_HEIGHT', 'ɽ���ι⤵');
    define($constpref . 'TIMELINE_SCALE', '������饤��λ�����');
    define($constpref . 'TIMELINE_SCALE_WEEK', '������');
    define($constpref . 'TIMELINE_SCALE_MONTH', '������');
    define($constpref . 'TIMELINE_SCALE_YEAR', '��ǯ');
    define($constpref . 'TIMELINE_SCALE_DECADE', '����ǯ');

    // === define end ===
}
