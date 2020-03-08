<?php
// $Id: modinfo.php,v 1.26 2011/12/28 16:16:15 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-10-01 K.OHWADA
// �̿� -> �̿���ư�衦��ǥ���
// 2010-04-04 K.OHWADA
// remove echo
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

$constpref = mb_strtoupper('_MI_' . $MY_DIRNAME . '_');

// === define begin ===
if (defined('FOR_XOOPS_LANG_CHECKER') || !defined($constpref . 'LANG_LOADED')) {
    define($constpref . 'LANG_LOADED', 1);

    //=========================================================
    // same as myalbum
    //=========================================================

    // The name of this module
    define($constpref . 'NAME', 'WEB �̿���ư�衦��ǥ�����');

    // A brief description of this module
    define($constpref . 'DESC', '�̿���ư��䤽��¾�Υ�ǥ�����������륢��Хࡦ�⥸�塼��Ǥ�');

    // Names of blocks for this module (Not all module has blocks)
    define($constpref . 'BNAME_RECENT', '�Ƕ�μ̿���ư�衦��ǥ���');
    define($constpref . 'BNAME_HITS', '�͵��μ̿���ư�衦��ǥ���');
    define($constpref . 'BNAME_RANDOM', '�ԥå����åפμ̿���ư�衦��ǥ���');
    define($constpref . 'BNAME_RECENT_P', '�Ƕ�μ̿���ư�衦��ǥ���(����ͥ�����)');
    define($constpref . 'BNAME_HITS_P', '�͵��μ̿���ư�衦��ǥ���(����ͥ�����)');

    define($constpref . 'CFG_IMAGINGPIPE', '����������Ԥ碌��ѥå���������');
    define($constpref . 'CFG_DESCIMAGINGPIPE', '�ۤȤ�ɤ�PHP�Ķ���ɸ��Ū�����Ѳ�ǽ�ʤΤ�GD�Ǥ�����ǽŪ������ޤ�<br>��ǽ�Ǥ����ImageMagick��NetPBM�λ��Ѥ򤪴��ᤷ�ޤ�');
    define($constpref . 'CFG_FORCEGD2', '����GD2�⡼��');
    define($constpref . 'CFG_DESCFORCEGD2', '����Ū��GD2�⡼�ɤ�ư����ޤ�<br>������PHP�Ǥ϶���GD2�⡼�ɤǥ���ͥ�������˼��Ԥ��ޤ�<br>���������ѥå������Ȥ���GD�����򤷤����Τ߰�̣������ޤ�');
    define($constpref . 'CFG_IMAGICKPATH', 'ImageMagick�μ¹ԥѥ�');
    define($constpref . 'CFG_DESCIMAGICKPATH', 'convert��¸�ߤ���ǥ��쥯�ȥ��ե�ѥ��ǻ��ꤷ�ޤ���������Ǥ��ޤ��Ԥ����Ȥ�¿���Ǥ��礦��<br>���������ѥå������Ȥ���ImageMagick�����򤷤����Τ߰�̣������ޤ�');
    define($constpref . 'CFG_NETPBMPATH', 'NetPBM�μ¹ԥѥ�');
    define($constpref . 'CFG_DESCNETPBMPATH', 'pnmscale����¸�ߤ���ǥ��쥯�ȥ��ե�ѥ��ǻ��ꤷ�ޤ���������Ǥ��ޤ��Ԥ����Ȥ�¿���Ǥ��礦��<br>���������ѥå������Ȥ���NetPBM�����򤷤����Τ߰�̣������ޤ�');
    define($constpref . 'CFG_POPULAR', "'POP'�������󤬤Ĥ������ɬ�פʥҥåȿ�");
    define($constpref . 'CFG_NEWDAYS', "'new'��'update'��������ɽ�����������");
    define($constpref . 'CFG_NEWPHOTOS', '�ȥåץڡ����ǿ����Ȥ���ɽ�������');
    define($constpref . 'CFG_PERPAGE', '1�ڡ�����ɽ�������̿���ư�衦��ǥ����ο�');
    define($constpref . 'CFG_DESCPERPAGE', '�����ǽ�ʿ����� | �Ƕ��ڤäƲ�����<br>��: 10|20|50|100');
    define($constpref . 'CFG_ALLOWNOIMAGE', '�̿���ư�衦��ǥ����Τʤ���Ƥ���Ĥ���');
    define($constpref . 'CFG_MAKETHUMB', '����ͥ�����������');
    define($constpref . 'CFG_DESCMAKETHUMB', '���������ʤ��פ������������פ��ѹ��������ˤϡ��֥���ͥ���κƹ��ۡפ�ɬ�פǤ���');

    // v2.30
    //define( $constpref."CFG_WIDTH" , "���������" ) ;
    //define( $constpref."CFG_DESCWIDTH" , "�������åץ��ɻ��˼�ưĴ�������ᥤ������κ�������<br>GD�⡼�ɤ�TrueColor�򰷤��ʤ����ˤ�ñ�ʤ륵��������" ) ;
    //define( $constpref."CFG_HEIGHT" , "���������" ) ;
    //define( $constpref."CFG_DESCHEIGHT" , "��������Ʊ����̣�Ǥ�" ) ;
    define($constpref . 'CFG_WIDTH', '�ݥåץ��åפǤβ�������');
    define($constpref . 'CFG_DESCWIDTH', '�ݥåץ��åפ����Ȥ��β������礭���Ǥ���<br><br>����ΥС������Ǥϥ��åץ��ɲ�ǽ�ʲ������礭�������¤Ǥ�����<br>�������¤Ϥʤ��ʤ�ޤ�����<br>2.30�ˤƻ��ͤ��Ѥ��ޤ�����');
    define($constpref . 'CFG_HEIGHT', '�ݥåץ��åפǤβ���������');
    define($constpref . 'CFG_DESCHEIGHT', '����������Ʊ����̣�Ǥ�');

    define($constpref . 'CFG_FSIZE', '����ե����륵����');
    define($constpref . 'CFG_DESCFSIZE', '���åץ��ɻ��Υե����륵��������(byte)');
    define($constpref . 'CFG_ADDPOSTS', '�̿���ư�衦��ǥ�������Ƥ������˥�����ȥ��åפ������ƿ�');
    define($constpref . 'CFG_DESCADDPOSTS', '�ＱŪ�ˤ�0��1�Ǥ�������ͤ�0�ȸ��ʤ���ޤ�');
    define($constpref . 'CFG_CATONSUBMENU', '���֥�˥塼�ؤΥȥåץ��ƥ��꡼����Ͽ');
    define($constpref . 'CFG_NAMEORUNAME', '��Ƽ�̾��ɽ��');
    define($constpref . 'CFG_DESCNAMEORUNAME', '������̾���ϥ�ɥ�̾�����򤷤Ʋ�����');
    define($constpref . 'CFG_VIEWTYPE', '����ɽ����ɽ��������');
    define($constpref . 'CFG_COLSOFTABLE', '�ơ��֥�ɽ�����Υ�����');
    define($constpref . 'CFG_USESITEIMG', '���᡼���ޥ͡���������Ǥ�[siteimg]����');
    define($constpref . 'CFG_DESCUSESITEIMG', '���᡼���ޥ͡���������ǡ�[img]�����������[siteimg]��������������褦�ˤʤ�ޤ���<br>���ѥ⥸�塼��¦��[siteimg]������ͭ���˵�ǽ����褦�ˤʤäƤ���ɬ�פ�����ޤ�');
    define($constpref . 'OPT_USENAME', '�ϥ�ɥ�̾');
    define($constpref . 'OPT_USEUNAME', '������̾');
    define($constpref . 'OPT_VIEWLIST', '����ʸ�եꥹ��ɽ��');
    define($constpref . 'OPT_VIEWTABLE', '�ơ��֥�ɽ��');

    // Text for notifications
    define($constpref . 'GLOBAL_NOTIFY', '�⥸�塼������');
    define($constpref . 'GLOBAL_NOTIFYDSC', '�⥸�塼�����Τˤ��������Υ��ץ����');
    define($constpref . 'CATEGORY_NOTIFY', '���ƥ��꡼');
    define($constpref . 'CATEGORY_NOTIFYDSC', '������Υ��ƥ��꡼���Ф������Υ��ץ����');
    define($constpref . 'PHOTO_NOTIFY', '�̿���ư�衦��ǥ���');
    define($constpref . 'PHOTO_NOTIFYDSC', 'ɽ����μ̿���ư�衦��ǥ������Ф������Υ��ץ����');

    define($constpref . 'GLOBAL_NEWPHOTO_NOTIFY', '�̿���ư�衦��ǥ����ο�����Ͽ');
    define($constpref . 'GLOBAL_NEWPHOTO_NOTIFYCAP', '�̿���ư�衦��ǥ�������������Ͽ���줿�������Τ���');
    define($constpref . 'GLOBAL_NEWPHOTO_NOTIFYDSC', '�̿���ư�衦��ǥ�������������Ͽ���줿�������Τ���');
    define($constpref . 'GLOBAL_NEWPHOTO_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE}: �����˼̿���ư�衦��ǥ�������Ͽ����ޤ���');

    define($constpref . 'CATEGORY_NEWPHOTO_NOTIFY', '���ƥ�����μ̿���ư�衦��ǥ����ο�����Ͽ');
    define($constpref . 'CATEGORY_NEWPHOTO_NOTIFYCAP', '���Υ��ƥ���˿����˼̿���ư�衦��ǥ�������Ͽ���줿�������Τ���');
    define($constpref . 'CATEGORY_NEWPHOTO_NOTIFYDSC', '���Υ��ƥ���˿����˼̿���ư�衦��ǥ�������Ͽ���줿�������Τ���');
    define($constpref . 'CATEGORY_NEWPHOTO_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE}: �����˼̿���ư�衦��ǥ�������Ͽ����ޤ���');

    //=========================================================
    // add for webphoto
    //=========================================================

    // Config Items
    define($constpref . 'CFG_SORT', '�ǥե���Ȥ�ɽ����');
    define($constpref . 'OPT_SORT_IDA', '�쥳�����ֹ澺��');
    define($constpref . 'OPT_SORT_IDD', '�쥳�����ֹ�߽�');
    define($constpref . 'OPT_SORT_HITSA', '�ҥåȿ� (�㢪��)');
    define($constpref . 'OPT_SORT_HITSD', '�ҥåȿ� (�⢪��)');
    define($constpref . 'OPT_SORT_TITLEA', '�����ȥ� (A �� Z)');
    define($constpref . 'OPT_SORT_TITLED', '�����ȥ� (Z �� A)');
    define($constpref . 'OPT_SORT_DATEA', '�������� (�좪��)');
    define($constpref . 'OPT_SORT_DATED', '�������� (������)');
    define($constpref . 'OPT_SORT_RATINGA', 'ɾ�� (�㢪��)');
    define($constpref . 'OPT_SORT_RATINGD', 'ɾ�� (�⢪��)');
    define($constpref . 'OPT_SORT_RANDOM', '������');
    define($constpref . 'CFG_MIDDLE_WIDTH', '���󥰥�ӥ塼�Ǥβ�������');
    define($constpref . 'CFG_MIDDLE_HEIGHT', '���󥰥�ӥ塼�Ǥβ����ι⤵');
    define($constpref . 'CFG_THUMB_WIDTH', '����ͥ����������');
    define($constpref . 'CFG_THUMB_HEIGHT', '����ͥ�������ι⤵');

    define($constpref . 'CFG_APIKEY', 'Google API Key');
    define(
        $constpref . 'CFG_APIKEY_DSC',
        'Google Maps �����Ѥ������ <br> <a href="http://www.google.com/apis/maps/signup.html" target="_blank">Sign Up for the Google Maps API</a> <br> �ˤ� <br> API key ��������Ƥ�������<br><br>�ѥ�᡼���ξܺ٤ϲ���������������<br><a href="http://www.google.com/apis/maps/documentation/reference.html" target="_blank">Google Maps API Reference</a>'
    );
    define($constpref . 'CFG_LATITUDE', '����');
    define($constpref . 'CFG_LONGITUDE', '����');
    define($constpref . 'CFG_ZOOM', '������');

    define($constpref . 'CFG_USE_POPBOX', 'PopBox ����Ѥ���');

    define($constpref . 'CFG_INDEX_DESC', '�ȥåץڡ�����ɽ����������ʸ');
    define($constpref . 'CFG_INDEX_DESC_DEFAULT', '�����ˤ�����ʸ��ɽ�����ޤ���<br>����ʸ�ϡְ�������פˤ��Խ��Ǥ��ޤ���<br>');

    // Sub menu titles
    define($constpref . 'SMNAME_SUBMIT', '���');
    define($constpref . 'SMNAME_POPULAR', '��͵�');
    define($constpref . 'SMNAME_HIGHRATE', '�ȥåץ��');
    define($constpref . 'SMNAME_MYPHOTO', '��ʬ�����');

    // Names of admin menu items
    define($constpref . 'ADMENU_PHOTOMANAGER', '��ǥ�������');
    define($constpref . 'ADMENU_CATMANAGER', '���ƥ������');
    define($constpref . 'ADMENU_CHECKCONFIGS', 'ư������å���');
    define($constpref . 'ADMENU_BATCH', '��ǥ����ΰ����Ͽ');
    define($constpref . 'ADMENU_GROUPPERM', '�ƥ��롼�פθ���');
    define($constpref . 'ADMENU_IMPORT', '��ǥ����Υ���ݡ���');
    define($constpref . 'ADMENU_EXPORT', '��ǥ����Υ������ݡ���');

    define($constpref . 'ADMENU_GICONMANAGER', 'Google�����������');
    define($constpref . 'ADMENU_MIMETYPES', 'MIME�����״���');
    define($constpref . 'ADMENU_IMPORT_MYALBUM', 'Myalbum ����ΰ�祤��ݡ���');
    define($constpref . 'ADMENU_CHECKTABLES', '�ơ��֥�ư������å�');
    define($constpref . 'ADMENU_PHOTO_TABLE_MANAGE', '�̿��ơ��֥����');
    define($constpref . 'ADMENU_CAT_TABLE_MANAGE', '���ƥ���ơ��֥����');
    define($constpref . 'ADMENU_VOTE_TABLE_MANAGE', '��ɼ�ơ��֥����');
    define($constpref . 'ADMENU_GICON_TABLE_MANAGE', 'Google��������ơ��֥����');
    define($constpref . 'ADMENU_MIME_TABLE_MANAGE', 'MIME�ơ��֥����');
    define($constpref . 'ADMENU_TAG_TABLE_MANAGE', '�����ơ��֥����');
    define($constpref . 'ADMENU_P2T_TABLE_MANAGE', '�̿�������Ϣ�ơ��֥����');
    define($constpref . 'ADMENU_SYNO_TABLE_MANAGE', '�����ơ��֥����');

    //---------------------------------------------------------
    // v0.20
    //---------------------------------------------------------
    define($constpref . 'CFG_USE_FFMPEG', 'ffmpeg ����Ѥ���');
    define($constpref . 'CFG_FFMPEGPATH', 'ffmpeg �μ¹ԥѥ�');
    define($constpref . 'CFG_DESCFFMPEGPATH', 'ffmpeg ��¸�ߤ���ǥ��쥯�ȥ��ե�ѥ��ǻ��ꤷ�ޤ�������Ǥ��ޤ��Ԥ����Ȥ�¿���Ǥ��礦��<br>��ffmpeg ����Ѥ���פΡ֤Ϥ��פ����򤷤����Τ߰�̣������ޤ�');
    define($constpref . 'CFG_USE_PATHINFO', 'pathinfo ����Ѥ���');

    //---------------------------------------------------------
    // v0.30
    //---------------------------------------------------------
    define($constpref . 'CFG_MAIL_HOST', '�᡼�� �����С� �ۥ���̾');
    define($constpref . 'CFG_MAIL_USER', '�᡼�� �桼����ID');
    define($constpref . 'CFG_MAIL_PASS', '�᡼�� �ѥ����');
    define($constpref . 'CFG_MAIL_ADDR', '����� �᡼�륢�ɥ쥹');
    define($constpref . 'CFG_MAIL_CHARSET', '�᡼���ʸ��������');
    define($constpref . 'CFG_MAIL_CHARSET_DSC', "'|' �Ƕ��ڤä����Ϥ��Ʋ�������<br>ʸ�������ɤˤ������å���Ԥ�ʤ����ˤϡ����������ˤ��ޤ�");
    define($constpref . 'CFG_MAIL_CHARSET_LIST', 'ISO-2022-JP|JIS|Shift_JIS|EUC-JP|UTF-8');
    define($constpref . 'CFG_FILE_DIR', 'FTP �ե��������¸��ǥ��쥯�ȥ�');
    define($constpref . 'CFG_FILE_DIR_DSC', "�ե�ѥ������ʺǸ��'/'�����ס�<br>�ɥ�����ȡ��롼�Ȱʳ������ꤹ�뤳�Ȥ򤪴��ᤷ�ޤ�");
    define($constpref . 'CFG_FILE_SIZE', 'FTP ����ե��������� (byte)');
    define($constpref . 'CFG_FILE_DESC', 'FTP �إ������ʸ');
    define($constpref . 'CFG_FILE_DESC_DSC', '�֥ե�������ơפθ��¤�������ˡ��إ�פ�ɽ������ޤ�');
    define($constpref . 'CFG_FILE_DESC_TEXT', '
<b>FTP �����С�</b><br>
FTP �����С� �ۥ���̾: xxx<br>
FTP �桼����ID: xxx<br>
FTP �ѥ����: xxx<br>');

    define($constpref . 'ADMENU_MAILLOG_MANAGER', '�᡼�������');
    define($constpref . 'ADMENU_MAILLOG_TABLE_MANAGE', '�᡼������ơ��֥����');
    define($constpref . 'ADMENU_USER_TABLE_MANAGE', '�桼������ơ��֥����');

    //---------------------------------------------------------
    // v0.40
    //---------------------------------------------------------
    define($constpref . 'CFG_BIN_PASS', '���ޥ�ɤΥѥ����');
    define($constpref . 'CFG_COM_DIRNAME', '���������礹��d3forum��dirname');
    define($constpref . 'CFG_COM_FORUM_ID', '���������礹��ե��������ֹ�');
    define($constpref . 'CFG_COM_VIEW', '�����������ɽ����ˡ');

    define($constpref . 'ADMENU_UPDATE', '���åץǡ���');
    define($constpref . 'ADMENU_ITEM_TABLE_MANAGE', '�����ƥࡦ�ơ��֥����');
    define($constpref . 'ADMENU_FILE_TABLE_MANAGE', '�ե����롦�ơ��֥����');

    //---------------------------------------------------------
    // v0.50
    //---------------------------------------------------------
    define($constpref . 'CFG_UPLOADSPATH', '���åץ��ɡ��ե��������¸��ǥ��쥯�ȥ�');
    define($constpref . 'CFG_UPLOADSPATH_DSC', "XOOPS���󥹥ȡ����褫��Υѥ������ʺǽ��'/'��ɬ�ס��Ǹ��'/'�����ס�<br>Unix�ǤϤ��Υǥ��쥯�ȥ�ؤν��°����ON�ˤ��Ʋ�����");
    define($constpref . 'CFG_MEDIASPATH', '��ǥ������ե�����Υǥ��쥯�ȥ�');
    define($constpref . 'CFG_MEDIASPATH_DSC', "�ץ쥤�ꥹ�Ȥθ��ˤʤ��ǥ������ե�����Τ���ǥ��쥯�ȥ� <br>XOOPS���󥹥ȡ����褫��Υѥ������ʺǽ��'/'��ɬ�ס��Ǹ��'/'�����ס�");
    define($constpref . 'CFG_LOGO_WIDTH', '�ץ쥤�䡼�������������ȹ⤵');
    define($constpref . 'CFG_USE_CALLBACK', '������Хå���������Ѥ���');
    define($constpref . 'CFG_USE_CALLBACK_DSC', '������Хå�����Ѥ��� Flash Player �Υ��٥�Ȥ�Ͽ����');

    define($constpref . 'ADMENU_ITEM_MANAGER', '�����ƥ����');
    define($constpref . 'ADMENU_PLAYER_MANAGER', '�ץ쥤�䡼����');
    define($constpref . 'ADMENU_FLASHVAR_MANAGER', '�ե�å����ѿ�����');
    define($constpref . 'ADMENU_PLAYER_TABLE_MANAGE', '�ץ쥤�䡼���ơ��֥����');
    define($constpref . 'ADMENU_FLASHVAR_TABLE_MANAGE', '�ե�å����ѿ����ơ��֥����');

    //---------------------------------------------------------
    // v0.60
    //---------------------------------------------------------
    define($constpref . 'CFG_WORKDIR', '����ѤΥǥ��쥯�ȥ�');
    define($constpref . 'CFG_WORKDIR_DSC', "�ե�ѥ������ʺǸ��'/'�����ס�<br>�ɥ�����ȡ��롼�Ȱʳ������ꤹ�뤳�Ȥ򤪴��ᤷ�ޤ�");
    define($constpref . 'CFG_CAT_WIDTH', '���ƥ�����������ȹ⤵');
    define($constpref . 'CFG_CSUB_WIDTH', '���֥��ƥ����ɽ��������������ȹ⤵');
    define($constpref . 'CFG_GICON_WIDTH', 'GoogleMap ����������������ȹ⤵');
    define($constpref . 'CFG_JPEG_QUALITY', 'JPEG �ʼ�');
    define($constpref . 'CFG_JPEG_QUALITY_DSC', '1 - 100 <br>���������ѥå������Ȥ���GD�����򤷤����Τ߰�̣������ޤ�');

    //---------------------------------------------------------
    // v0.80
    //---------------------------------------------------------
    define($constpref . 'BNAME_CATLIST', '���ƥ������');
    define($constpref . 'BNAME_TAGCLOUD', '��������');

    //---------------------------------------------------------
    // v0.90
    //---------------------------------------------------------
    define($constpref . 'CFG_PERM_CAT_READ', '���ƥ���α�������');
    define($constpref . 'CFG_PERM_CAT_READ_DSC', '���ƥ��ꡦ�ơ��֥������ȹ�碌��ͭ���ˤʤ�');
    define($constpref . 'CFG_PERM_ITEM_READ', '�����ƥ�α�������');
    define($constpref . 'CFG_PERM_ITEM_READ_DSC', '�����ƥࡦ�ơ��֥������ȹ�碌��ͭ���ˤʤ�');
    define($constpref . 'OPT_PERM_READ_ALL', '����ɽ������');
    define($constpref . 'OPT_PERM_READ_NO_ITEM', '�����ƥ����ɽ���ˤ���');
    define($constpref . 'OPT_PERM_READ_NO_CAT', '���ƥ���ȥ����ƥ����ɽ���ˤ���');

    //---------------------------------------------------------
    // v1.10
    //---------------------------------------------------------
    define($constpref . 'CFG_USE_XPDF', 'xpdf ����Ѥ���');
    define($constpref . 'CFG_XPDFPATH', 'xpdf �μ¹ԥѥ�');
    define($constpref . 'CFG_XPDFPATH_DSC', 'pdftoppm �ʤɤ�¸�ߤ���ǥ��쥯�ȥ��ե�ѥ��ǻ��ꤷ�ޤ�������Ǥ��ޤ��Ԥ����Ȥ�¿���Ǥ��礦��<br>��xpdf ����Ѥ���פΡ֤Ϥ��פ����򤷤����Τ߰�̣������ޤ�');

    //---------------------------------------------------------
    // v1.21
    //---------------------------------------------------------
    define($constpref . 'ADMENU_RSS_MANAGER', 'RSS ����');

    //---------------------------------------------------------
    // v1.30
    //---------------------------------------------------------
    define($constpref . 'CFG_SMALL_WIDTH', '������饤��Ǥβ�������');
    define($constpref . 'CFG_SMALL_HEIGHT', '������饤��Ǥβ����ι⤵');
    define($constpref . 'CFG_TIMELINE_DIRNAME', 'timeline �⥸�塼��Υǥ��쥯�ȥ�̾');
    define($constpref . 'CFG_TIMELINE_DIRNAME_DSC', '������饤��ǽ����Ѥ���Ȥ��˻��ꤹ��');
    define($constpref . 'CFG_TIMELINE_SCALE', '������饤��λ�����');
    define($constpref . 'CFG_TIMELINE_SCALE_DSC', '�� 600px �β�����ɽ���������');
    define($constpref . 'OPT_TIMELINE_SCALE_WEEK', '������');
    define($constpref . 'OPT_TIMELINE_SCALE_MONTH', '������');
    define($constpref . 'OPT_TIMELINE_SCALE_YEAR', '��ǯ');
    define($constpref . 'OPT_TIMELINE_SCALE_DECADE', '����ǯ');

    //---------------------------------------------------------
    // v1.40
    //---------------------------------------------------------
    // timeline
    define($constpref . 'CFG_TIMELINE_LATEST', '������饤��ο�����������ɽ������̿���ư�衦��ǥ����ο�');
    define($constpref . 'CFG_TIMELINE_RANDOM', '������饤��Υ������ɽ������̿���ư�衦��ǥ����ο�');
    define($constpref . 'BNAME_TIMELINE', '������饤��');

    // map, tag
    define($constpref . 'CFG_GMAP_PHOTOS', '�ޥåפ�ɽ������̿���ư�衦��ǥ����ο�');
    define($constpref . 'CFG_TAGS', '�������饦�ɤ�ɽ�����륿���ο�');

    //---------------------------------------------------------
    // v1.70
    //---------------------------------------------------------
    define($constpref . 'CFG_ITEM_SUMMARY', '�̿���ư�衦��ǥ����������κ����ʸ����');
    define($constpref . 'CFG_ITEM_SUMMARY_DSC', '������ɽ������̿���ư�衦��ǥ���������ʸ�κ����ʸ��������ꤹ��<br>-1 �����¤ʤ�');
    define($constpref . 'CFG_CAT_SUMMARY', '���ƥ���������κ����ʸ����');
    define($constpref . 'CFG_CAT_SUMMARY_DSC', '���ƥ��������ɽ����������ʸ�κ����ʸ��������ꤹ��<br>-1 �����¤ʤ�');
    define($constpref . 'CFG_CAT_CHILD', '���̥��ƥ���μ̿���ư�衦��ǥ�����ɽ��');
    define($constpref . 'CFG_CAT_CHILD_DSC', '���ƥ���ɽ���ΤȤ��˲��̥��ƥ���μ̿���ư�衦��ǥ�����ɽ�����뤫�ݤ�����ꤹ��');
    define($constpref . 'OPT_CAT_CHILD_NON', '���ƥ���μ̿���ư�衦��ǥ����Τߤ�ɽ�����롣��˲��̥��ƥ���μ̿���ư�衦��ǥ�����ɽ�����ʤ�');
    define($constpref . 'OPT_CAT_CHILD_EMPTY', '���ƥ���̿���ư�衦��ǥ���������ΤȤ��ˡ����̥��ƥ���μ̿���ư�衦��ǥ�����ɽ������');
    define($constpref . 'OPT_CAT_CHILD_ALWAYS', '��˲��̥��ƥ���μ̿���ư�衦��ǥ�����ɽ������');

    //---------------------------------------------------------
    // v1.80
    //---------------------------------------------------------
    define($constpref . 'CFG_USE_LAME', 'lame ����Ѥ���');
    define($constpref . 'CFG_LAMEPATH', 'lame �μ¹ԥѥ�');
    define($constpref . 'CFG_LAMEPATH_DSC', 'lame ��¸�ߤ���ǥ��쥯�ȥ��ե�ѥ��ǻ��ꤷ�ޤ�������Ǥ��ޤ��Ԥ����Ȥ�¿���Ǥ��礦��<br>��lame ����Ѥ���פΡ֤Ϥ��פ����򤷤����Τ߰�̣������ޤ�');

    define($constpref . 'CFG_USE_TIMIDITY', 'timidity ����Ѥ���');
    define($constpref . 'CFG_TIMIDITYPATH', 'timidity �μ¹ԥѥ�');
    define($constpref . 'CFG_TIMIDITYPATH_DSC', 'timidity ��¸�ߤ���ǥ��쥯�ȥ��ե�ѥ��ǻ��ꤷ�ޤ�������Ǥ��ޤ��Ԥ����Ȥ�¿���Ǥ��礦��<br>��timidity ����Ѥ���פΡ֤Ϥ��פ����򤷤����Τ߰�̣������ޤ�');

    define($constpref . 'SMNAME_SEARCH', '����');

    //---------------------------------------------------------
    // v2.00
    //---------------------------------------------------------
    // config
    define($constpref . 'CFG_GROUPID_ADMIN', '������ ���롼��ID');
    define($constpref . 'CFG_GROUPID_ADMIN_DSC', '���Υ⥸�塼��δ����ԤΥ桼�����롼��ID��<br>�⥸�塼�륤�󥹥ȡ���������ꤵ��롣<br>���ߤ��ѹ����ʤ�����');
    define($constpref . 'CFG_GROUPID_USER', '���Ѽ� ���롼��ID');
    define($constpref . 'CFG_GROUPID_USER_DSC', '���Υ⥸�塼������ѼԤΥ桼�����롼��ID��<br>�⥸�塼�륤�󥹥ȡ���������ꤵ��롣<br>���ߤ��ѹ����ʤ�����');

    // admin menu
    define($constpref . 'ADMENU_INVITE', 'ͧ�ͤ��Ԥ���');

    // notifications
    define($constpref . 'GLOBAL_WAITING_NOTIFY', '��ǧ�Ԥ��μ̿���ư�衦��ǥ��������');
    define($constpref . 'GLOBAL_WAITING_NOTIFYCAP', '��ǧ�Ԥ��μ̿���ư�衦��ǥ�������Ƥ��줿�������Τ��� (������)');
    define($constpref . 'GLOBAL_WAITING_NOTIFYDSC', '��ǧ�Ԥ��μ̿���ư�衦��ǥ�������Ƥ��줿�������Τ���');
    define($constpref . 'GLOBAL_WAITING_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE}: ��ǧ�Ԥ��μ̿���ư�衦��ǥ���������Ƥ���ޤ���');

    //---------------------------------------------------------
    // v2.10
    //---------------------------------------------------------
    define($constpref . 'CFG_USE_LIGHTBOX', 'LightBox ����Ѥ���');

    //---------------------------------------------------------
    // v2.11
    //---------------------------------------------------------
    define($constpref . 'ADMENU_REDOTHUMBS', '����ͥ���κƹ���');

    //---------------------------------------------------------
    // v2.20
    //---------------------------------------------------------
    define($constpref . 'CFG_EMBED_WIDTH', 'ư�襵���Ȥ�ɽ������');
    define($constpref . 'CFG_EMBED_HEIGHT', 'ư�襵���Ȥ�ɽ���ι⤵');

    //---------------------------------------------------------
    // v2.40
    //---------------------------------------------------------
    define($constpref . 'CFG_PEAR_PATH', 'PEAR �饤�֥��Υѥ�');
    define($constpref . 'CFG_PEAR_PATH_DSC', 'Net_POP3 �Τ��� PEAR �饤�֥������Хѥ�����ꤹ��<br>���ꤷ�ʤ��Ȥ��� modules/webphoto/PEAR �����Ѥ����');

    //---------------------------------------------------------
    // v2.60
    //---------------------------------------------------------
    define($constpref . 'OPT_TIMELINE_SCALE_HOUR', '������');
    define($constpref . 'OPT_TIMELINE_SCALE_DAY', '����');
    define($constpref . 'OPT_TIMELINE_SCALE_CENTURY', '������');
    define($constpref . 'OPT_TIMELINE_SCALE_MILLENNIUM', '��������');
}// === define begin ===
