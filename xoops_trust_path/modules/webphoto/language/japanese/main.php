<?php
// $Id: main.php,v 1.43 2011/12/28 16:16:15 ohwada Exp $

//=========================================================
// webphoto module
// EUC-JP
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-10-01 K.OHWADA
// �̿� -> �̿���ư�衦��ǥ���
//---------------------------------------------------------

// === define begin ===
if (!defined('_MB_WEBPHOTO_LANG_LOADED')) {
    define('_MB_WEBPHOTO_LANG_LOADED', 1);

    //=========================================================
    // base on myalbum
    //=========================================================

    define('_WEBPHOTO_CATEGORY', '���ƥ���');
    define('_WEBPHOTO_SUBMITTER', '��Ƽ�');
    define('_WEBPHOTO_NOMATCH_PHOTO', '�̿���ư�衦��ǥ���������ޤ���');

    define('_WEBPHOTO_ICON_NEW', '����');
    define('_WEBPHOTO_ICON_UPDATE', '����');
    define('_WEBPHOTO_ICON_POPULAR', '��ҥå�');
    define('_WEBPHOTO_ICON_LASTUPDATE', '���󹹿�');
    define('_WEBPHOTO_ICON_HITS', '�ҥåȿ�');
    define('_WEBPHOTO_ICON_COMMENTS', '�����ȿ�');

    define('_WEBPHOTO_SORT_IDA', '�쥳�����ֹ澺��');
    define('_WEBPHOTO_SORT_IDD', '�쥳�����ֹ�߽�');
    define('_WEBPHOTO_SORT_HITSA', '�ҥåȿ� (�㢪��)');
    define('_WEBPHOTO_SORT_HITSD', '�ҥåȿ� (�⢪��)');
    define('_WEBPHOTO_SORT_TITLEA', '�����ȥ� (A �� Z)');
    define('_WEBPHOTO_SORT_TITLED', '�����ȥ� (Z �� A)');
    define('_WEBPHOTO_SORT_DATEA', '�������� (�좪��)');
    define('_WEBPHOTO_SORT_DATED', '�������� (������)');
    define('_WEBPHOTO_SORT_RATINGA', 'ɾ�� (�㢪��)');
    define('_WEBPHOTO_SORT_RATINGD', 'ɾ�� (�⢪��)');
    define('_WEBPHOTO_SORT_RANDOM', '������');

    define('_WEBPHOTO_SORT_SORTBY', '�¤��ؤ�:');
    define('_WEBPHOTO_SORT_TITLE', '�����ȥ�');
    define('_WEBPHOTO_SORT_DATE', '��������');
    define('_WEBPHOTO_SORT_HITS', '�ҥåȿ�');
    define('_WEBPHOTO_SORT_RATING', 'ɾ��');
    define('_WEBPHOTO_SORT_S_CURSORTEDBY', '���ߤ��¤ӽ�: %s');

    define('_WEBPHOTO_NAVI_PREVIOUS', '��');
    define('_WEBPHOTO_NAVI_NEXT', '��');
    define('_WEBPHOTO_S_NAVINFO', '%s �� - %s �֤�ɽ�� (�� %s ��)');
    define('_WEBPHOTO_S_THEREARE', '�ǡ����١����ˤ���̿���ư�衦��ǥ����� <b>%s</b> ��Ǥ�');
    define('_WEBPHOTO_S_MOREPHOTOS', '%s ����β������ä�');
    define('_WEBPHOTO_ONEVOTE', '��ɼ�� 1');
    define('_WEBPHOTO_S_NUMVOTES', '��ɼ�� %s');
    define('_WEBPHOTO_ONEPOST', '�����ȿ�');
    define('_WEBPHOTO_S_NUMPOSTS', '�����ȿ� %s');
    define('_WEBPHOTO_VOTETHIS', '��ɼ����');
    define('_WEBPHOTO_TELLAFRIEND', 'ͧ�ͤ��Τ餻��');
    define('_WEBPHOTO_SUBJECT4TAF', '���򤤼̿���ư�衦��ǥ����򸫤Ĥ��ޤ���');

    //---------------------------------------------------------
    // submit
    //---------------------------------------------------------
    // only "Y/m/d" , "d M Y" , "M d Y" can be interpreted
    define('_WEBPHOTO_DTFMT_YMDHI', 'Y-m-d H:i');

    define('_WEBPHOTO_TITLE_ADDPHOTO', '�̿���ư�衦��ǥ������ɲä���');
    define('_WEBPHOTO_TITLE_PHOTOUPLOAD', '�̿���ư�衦��ǥ����Υ��åץ���');
    define('_WEBPHOTO_CAP_MAXPIXEL', '�������������');
    define('_WEBPHOTO_CAP_MAXSIZE', '�ե����륵������� (byte)');
    define('_WEBPHOTO_CAP_VALIDPHOTO', '��ǧ');
    define('_WEBPHOTO_DSC_TITLE_BLANK', '�����ȥ��������ˤ�����硢�ե�����̾�򥿥��ȥ�Ȥ��ޤ�');

    define('_WEBPHOTO_RADIO_ROTATETITLE', '������ž');
    define('_WEBPHOTO_RADIO_ROTATE0', '��ž���ʤ�');
    define('_WEBPHOTO_RADIO_ROTATE90', '����90�ٲ�ž');
    define('_WEBPHOTO_RADIO_ROTATE180', '180�ٲ�ž');
    define('_WEBPHOTO_RADIO_ROTATE270', '����90�ٲ�ž');

    define('_WEBPHOTO_SUBMIT_RECEIVED', '�̿���ư�衦��ǥ�������Ͽ���ޤ����������ͭ�񤦤������ޤ���');
    define('_WEBPHOTO_SUBMIT_ALLPENDING', '��Ƥ������٤Ƥμ̿���ư�衦��ǥ����ϳ�ǧ�Τ��Ჾ��Ͽ�Ȥʤ�ޤ���');

    define('_WEBPHOTO_ERR_MUSTREGFIRST', '����������ޤ��󤬥����������¤�����ޤ���<br>��Ͽ���뤫���������ˤ��ꤤ���ޤ���');
    define('_WEBPHOTO_ERR_MUSTADDCATFIRST', '�ɲä��뤿��ˤϥ��ƥ��꤬ɬ�פǤ���<br>�ޤ����ƥ����������Ʋ�������');
    define('_WEBPHOTO_ERR_NOIMAGESPECIFIED', '�̿���ư�衦��ǥ�����̤���򡧥��åץ��ɤ��٤��̿���ư�衦��ǥ����Υե���������򤷤Ʋ�������');
    define('_WEBPHOTO_ERR_FILE', '���åץ��ɤ˼��ԡ��̿���ư�衦��ǥ����Υե����뤬���Ĥ���ʤ����������¤�ۤ��Ƥޤ���');
    define('_WEBPHOTO_ERR_FILEREAD', '�ɹ����ԡ��ʤ�餫����ͳ�ǥ��åץ��ɤ��줿�̿���ư�衦��ǥ����Υե�������ɤ߽Ф��ޤ���');
    define('_WEBPHOTO_ERR_TITLE', '�����ȥ뤬ɬ�פǤ�');

    //---------------------------------------------------------
    // edit
    //---------------------------------------------------------
    define('_WEBPHOTO_TITLE_EDIT', '���μ̿���ư�衦��ǥ������Խ�����');
    define('_WEBPHOTO_TITLE_PHOTODEL', '�̿���ư�衦��ǥ�����������');
    define('_WEBPHOTO_CONFIRM_PHOTODEL', '�̿���ư�衦��ǥ����������ޤ���?');
    define('_WEBPHOTO_DBUPDATED', '�ǡ����١�������������!');
    define('_WEBPHOTO_DELETED', '������ޤ���!');

    //---------------------------------------------------------
    // rate
    //---------------------------------------------------------
    define('_WEBPHOTO_RATE_VOTEONCE', 'Ʊ��μ̿���ư�衦��ǥ����ؤ���ɼ�ϰ��٤����ˤ��ꤤ���ޤ���');
    define('_WEBPHOTO_RATE_RATINGSCALE', 'ɾ���� 1 ���� 10 �ޤǤǤ��� 1 �����㡢 10 ���ǹ�');
    define('_WEBPHOTO_RATE_BEOBJECTIVE', '�Ҵ�Ū��ɾ���򤪴ꤤ���ޤ���������1��10�Τߤ��Ƚ���դ��ΰ�̣������ޤ���');
    define('_WEBPHOTO_RATE_DONOTVOTE', '��ʬ����Ͽ�����̿���ư�衦��ǥ�������ɼ�Ǥ��ޤ���');
    define('_WEBPHOTO_RATE_IT', '��ɼ����!');
    define('_WEBPHOTO_RATE_VOTEAPPRE', '��ɼ������դ��ޤ���');
    define('_WEBPHOTO_RATE_S_THANKURATE', '�������� %s �ؤΤ���ɼ�����꤬�Ȥ��������ޤ���');

    define('_WEBPHOTO_ERR_NORATING', 'ɾ�������򤵤�Ƥޤ���');
    define('_WEBPHOTO_ERR_CANTVOTEOWN', '��ʬ����Ƥ����̿���ư�衦��ǥ����ˤ���ɼ�Ǥ��ޤ���<br>��ɼ�ˤ������ܤ��̤��ޤ�');
    define('_WEBPHOTO_ERR_VOTEONCE', '���򤷤��̿���ư�衦��ǥ����ؤ���ɼ�ϰ��٤����ˤ��ꤤ���ޤ���<br>��ɼ�ˤϤ��٤��ܤ��̤��ޤ���');

    //---------------------------------------------------------
    // move from myalbum_constants.php
    //---------------------------------------------------------
    // Caption
    define('_WEBPHOTO_CAPTION_TOTAL', 'Total:');
    define('_WEBPHOTO_CAPTION_GUESTNAME', '������');
    define('_WEBPHOTO_CAPTION_REFRESH', '����');
    define('_WEBPHOTO_CAPTION_IMAGEXYT', '������');
    define('_WEBPHOTO_CAPTION_CATEGORY', '���ƥ��꡼');

    //=========================================================
    // add for webphoto
    //=========================================================

    //---------------------------------------------------------
    // database table items
    //---------------------------------------------------------

    // photo table
    define('_WEBPHOTO_PHOTO_TABLE', '�̿���ư�衦��ǥ����ơ��֥�');
    define('_WEBPHOTO_PHOTO_ID', '�̿���ư�衦��ǥ���ID');
    define('_WEBPHOTO_PHOTO_TIME_CREATE', '��������');
    define('_WEBPHOTO_PHOTO_TIME_UPDATE', '��������');
    define('_WEBPHOTO_PHOTO_CAT_ID', '���ƥ����ֹ�');
    define('_WEBPHOTO_PHOTO_GICON_ID', 'GoogleMap ���������ֹ�');
    define('_WEBPHOTO_PHOTO_UID', '�桼���ֹ�');
    define('_WEBPHOTO_PHOTO_DATETIME', '��������');
    define('_WEBPHOTO_PHOTO_TITLE', '�̿���ư�衦��ǥ��������ȥ�');
    define('_WEBPHOTO_PHOTO_PLACE', '���ƾ��');
    define('_WEBPHOTO_PHOTO_EQUIPMENT', '���Ƶ���');
    define('_WEBPHOTO_PHOTO_FILE_URL', '�ե����� URL');
    define('_WEBPHOTO_PHOTO_FILE_PATH', '�ե����� �ѥ�');
    define('_WEBPHOTO_PHOTO_FILE_NAME', '�ե����� ̾');
    define('_WEBPHOTO_PHOTO_FILE_EXT', '�ե����� ��ĥ��');
    define('_WEBPHOTO_PHOTO_FILE_MIME', '�ե����� MIME������');
    define('_WEBPHOTO_PHOTO_FILE_MEDIUM', '�ե����� ��ǥ���������');
    define('_WEBPHOTO_PHOTO_FILE_SIZE', '�ե����� ������');
    define('_WEBPHOTO_PHOTO_CONT_URL', '�̿���ư�衦��ǥ��� URL');
    define('_WEBPHOTO_PHOTO_CONT_PATH', '�̿���ư�衦��ǥ��� �ѥ�');
    define('_WEBPHOTO_PHOTO_CONT_NAME', '�̿���ư�衦��ǥ��� �ե�����̾');
    define('_WEBPHOTO_PHOTO_CONT_EXT', '�̿���ư�衦��ǥ��� ��ĥ��');
    define('_WEBPHOTO_PHOTO_CONT_MIME', '�̿���ư�衦��ǥ��� MIME������');
    define('_WEBPHOTO_PHOTO_CONT_MEDIUM', '�̿���ư�衦��ǥ��� ��ǥ���������');
    define('_WEBPHOTO_PHOTO_CONT_SIZE', '�̿���ư�衦��ǥ��� �ե����륵����');
    define('_WEBPHOTO_PHOTO_CONT_WIDTH', '�̿���ư�衦��ǥ��� ��������');
    define('_WEBPHOTO_PHOTO_CONT_HEIGHT', '�̿���ư�衦��ǥ��� �����⤵');
    define('_WEBPHOTO_PHOTO_CONT_DURATION', '�ӥǥ���������');
    define('_WEBPHOTO_PHOTO_CONT_EXIF', 'Exif ����');
    define('_WEBPHOTO_PHOTO_MIDDLE_WIDTH', '�ߥɥ� ��������');
    define('_WEBPHOTO_PHOTO_MIDDLE_HEIGHT', '�ߥɥ� �����⤵');
    define('_WEBPHOTO_PHOTO_THUMB_URL', '����ͥ��� URL');
    define('_WEBPHOTO_PHOTO_THUMB_PATH', '����ͥ��� �ѥ�');
    define('_WEBPHOTO_PHOTO_THUMB_NAME', '����ͥ��� �ե�����̾');
    define('_WEBPHOTO_PHOTO_THUMB_EXT', '����ͥ��� ��ĥ��');
    define('_WEBPHOTO_PHOTO_THUMB_MIME', '����ͥ��� MIME������');
    define('_WEBPHOTO_PHOTO_THUMB_MEDIUM', '����ͥ��� ��ǥ���������');
    define('_WEBPHOTO_PHOTO_THUMB_SIZE', '����ͥ��� �ե����륵����');
    define('_WEBPHOTO_PHOTO_THUMB_WIDTH', '����ͥ��� ��������');
    define('_WEBPHOTO_PHOTO_THUMB_HEIGHT', '����ͥ��� �����⤵');
    define('_WEBPHOTO_PHOTO_GMAP_LATITUDE', 'GoogleMap ����');
    define('_WEBPHOTO_PHOTO_GMAP_LONGITUDE', 'GoogleMap ����');
    define('_WEBPHOTO_PHOTO_GMAP_ZOOM', 'GoogleMap ������');
    define('_WEBPHOTO_PHOTO_GMAP_TYPE', 'GoogleMap ������');
    define('_WEBPHOTO_PHOTO_PERM_READ', '��������');
    define('_WEBPHOTO_PHOTO_STATUS', '����');
    define('_WEBPHOTO_PHOTO_HITS', '�ҥåȿ�');
    define('_WEBPHOTO_PHOTO_RATING', 'ɾ��');
    define('_WEBPHOTO_PHOTO_VOTES', '��ɼ��');
    define('_WEBPHOTO_PHOTO_COMMENTS', '�����ȿ�');
    define('_WEBPHOTO_PHOTO_TEXT1', 'text1');
    define('_WEBPHOTO_PHOTO_TEXT2', 'text2');
    define('_WEBPHOTO_PHOTO_TEXT3', 'text3');
    define('_WEBPHOTO_PHOTO_TEXT4', 'text4');
    define('_WEBPHOTO_PHOTO_TEXT5', 'text5');
    define('_WEBPHOTO_PHOTO_TEXT6', 'text6');
    define('_WEBPHOTO_PHOTO_TEXT7', 'text7');
    define('_WEBPHOTO_PHOTO_TEXT8', 'text8');
    define('_WEBPHOTO_PHOTO_TEXT9', 'text9');
    define('_WEBPHOTO_PHOTO_TEXT10', 'text10');
    define('_WEBPHOTO_PHOTO_DESCRIPTION', '�̿���ư�衦��ǥ�������ʸ');
    define('_WEBPHOTO_PHOTO_SEARCH', '����ʸ');

    // category table
    define('_WEBPHOTO_CAT_TABLE', '���ƥ���ơ��֥�');
    define('_WEBPHOTO_CAT_ID', '���ƥ���ID');
    define('_WEBPHOTO_CAT_TIME_CREATE', '��������');
    define('_WEBPHOTO_CAT_TIME_UPDATE', '��������');
    define('_WEBPHOTO_CAT_GICON_ID', 'GoogleMap ���������ֹ�');
    define('_WEBPHOTO_CAT_FORUM_ID', '�ե�������ֹ�');
    define('_WEBPHOTO_CAT_PID', '���ֹ�');
    define('_WEBPHOTO_CAT_TITLE', '���ƥ���̾');
    define('_WEBPHOTO_CAT_IMG_PATH', '���ƥ������');
    define('_WEBPHOTO_CAT_IMG_MODE', '������ɽ���⡼��');
    define('_WEBPHOTO_CAT_ORIG_WIDTH', '�����θ����β���');
    define('_WEBPHOTO_CAT_ORIG_HEIGHT', '�����θ����ι⤵');
    define('_WEBPHOTO_CAT_MAIN_WIDTH', '�ᥤ�󥫥ƥ���ɽ���β����β���');
    define('_WEBPHOTO_CAT_MAIN_HEIGHT', '�ᥤ�󥫥ƥ���ɽ���β����ι⤵');
    define('_WEBPHOTO_CAT_SUB_WIDTH', '���֥��ƥ���ɽ���β����β���');
    define('_WEBPHOTO_CAT_SUB_HEIGHT', '���֥��ƥ���ɽ���β����ι⤵');
    define('_WEBPHOTO_CAT_WEIGHT', 'ɽ����');
    define('_WEBPHOTO_CAT_DEPTH', '����');
    define('_WEBPHOTO_CAT_ALLOWED_EXT', '���Ĥ��줿��ĥ��');
    define('_WEBPHOTO_CAT_ITEM_TYPE', '�����Υ�����');
    define('_WEBPHOTO_CAT_GMAP_MODE', 'GoogleMap ɽ���⡼��');
    define('_WEBPHOTO_CAT_GMAP_LATITUDE', 'GoogleMap ����');
    define('_WEBPHOTO_CAT_GMAP_LONGITUDE', 'GoogleMap ����');
    define('_WEBPHOTO_CAT_GMAP_ZOOM', 'GoogleMap ������');
    define('_WEBPHOTO_CAT_GMAP_TYPE', 'GoogleMap ������');
    define('_WEBPHOTO_CAT_PERM_READ', '��������');
    define('_WEBPHOTO_CAT_PERM_POST', '��Ƹ���');
    define('_WEBPHOTO_CAT_TEXT1', 'text1');
    define('_WEBPHOTO_CAT_TEXT2', 'text2');
    define('_WEBPHOTO_CAT_TEXT3', 'text3');
    define('_WEBPHOTO_CAT_TEXT4', 'text4');
    define('_WEBPHOTO_CAT_TEXT5', 'text5');
    define('_WEBPHOTO_CAT_DESCRIPTION', '���ƥ�������ʸ');

    // vote table
    define('_WEBPHOTO_VOTE_TABLE', '��ɼ�ơ��֥�');
    define('_WEBPHOTO_VOTE_ID', '��ɼID');
    define('_WEBPHOTO_VOTE_TIME_CREATE', '��������');
    define('_WEBPHOTO_VOTE_TIME_UPDATE', '��������');
    define('_WEBPHOTO_VOTE_PHOTO_ID', '�����ƥ��ֹ�');
    define('_WEBPHOTO_VOTE_UID', '�桼���ֹ�');
    define('_WEBPHOTO_VOTE_RATING', 'ɾ��');
    define('_WEBPHOTO_VOTE_HOSTNAME', 'IP���ɥ쥹');

    // google icon table
    define('_WEBPHOTO_GICON_TABLE', 'Google��������ơ��֥�');
    define('_WEBPHOTO_GICON_ID', '��������ID');
    define('_WEBPHOTO_GICON_TIME_CREATE', '��������');
    define('_WEBPHOTO_GICON_TIME_UPDATE', '��������');
    define('_WEBPHOTO_GICON_TITLE', '��������̾');
    define('_WEBPHOTO_GICON_IMAGE_PATH', '���� �ѥ�');
    define('_WEBPHOTO_GICON_IMAGE_NAME', '���� �ե�����̾');
    define('_WEBPHOTO_GICON_IMAGE_EXT', '���� ��ĥ��');
    define('_WEBPHOTO_GICON_SHADOW_PATH', '����ɡ� �ѥ�');
    define('_WEBPHOTO_GICON_SHADOW_NAME', '����ɡ� �ե�����̾');
    define('_WEBPHOTO_GICON_SHADOW_EXT', '����ɡ� ��ĥ��');
    define('_WEBPHOTO_GICON_IMAGE_WIDTH', '���� ��������');
    define('_WEBPHOTO_GICON_IMAGE_HEIGHT', '���� �����⤵');
    define('_WEBPHOTO_GICON_SHADOW_WIDTH', '����ɡ� ��������');
    define('_WEBPHOTO_GICON_SHADOW_HEIGHT', '����ɡ� �����⤵');
    define('_WEBPHOTO_GICON_ANCHOR_X', '���󥫡� X������');
    define('_WEBPHOTO_GICON_ANCHOR_Y', '���󥫡� Y������');
    define('_WEBPHOTO_GICON_INFO_X', 'WindowInfo X������');
    define('_WEBPHOTO_GICON_INFO_Y', 'WindowInfo Y������');

    // mime type table
    define('_WEBPHOTO_MIME_TABLE', 'MIME�����ץơ��֥�');
    define('_WEBPHOTO_MIME_ID', 'MIME ID');
    define('_WEBPHOTO_MIME_TIME_CREATE', '��������');
    define('_WEBPHOTO_MIME_TIME_UPDATE', '��������');
    define('_WEBPHOTO_MIME_EXT', '��ĥ��');
    define('_WEBPHOTO_MIME_MEDIUM', '��ǥ���������');
    define('_WEBPHOTO_MIME_TYPE', 'MIME������');
    define('_WEBPHOTO_MIME_NAME', 'MIME̾��');
    define('_WEBPHOTO_MIME_PERMS', '�ѡ��ߥå����');

    // added in v0.20
    define('_WEBPHOTO_MIME_FFMPEG', 'ffmpeg ���ץ����');

    // added in v1.80
    define('_WEBPHOTO_MIME_KIND', '�ե��������');
    define('_WEBPHOTO_MIME_OPTION', '���ޥ�ɡ����ץ����');

    // tag table
    define('_WEBPHOTO_TAG_TABLE', '�����ơ��֥�');
    define('_WEBPHOTO_TAG_ID', '����ID');
    define('_WEBPHOTO_TAG_TIME_CREATE', '��������');
    define('_WEBPHOTO_TAG_TIME_UPDATE', '��������');
    define('_WEBPHOTO_TAG_NAME', '����̾');

    // photo-to-tag table
    define('_WEBPHOTO_P2T_TABLE', '�̿���ư�衦��ǥ����ȥ�����Ϣ�ơ��֥�');
    define('_WEBPHOTO_P2T_ID', '�̿���ư�衦��ǥ����ȥ�����ϢID');
    define('_WEBPHOTO_P2T_TIME_CREATE', '��������');
    define('_WEBPHOTO_P2T_TIME_UPDATE', '��������');
    define('_WEBPHOTO_P2T_PHOTO_ID', '�����ƥ��ֹ�');
    define('_WEBPHOTO_P2T_TAG_ID', '�����ֹ�');
    define('_WEBPHOTO_P2T_UID', '�桼���ֹ�');

    // synonym table
    define('_WEBPHOTO_SYNO_TABLE', '�����ơ��֥�');
    define('_WEBPHOTO_SYNO_ID', '�����ID');
    define('_WEBPHOTO_SYNO_TIME_CREATE', '��������');
    define('_WEBPHOTO_SYNO_TIME_UPDATE', '��������');
    define('_WEBPHOTO_SYNO_WEIGHT', '�¤ӽ�');
    define('_WEBPHOTO_SYNO_KEY', '����');
    define('_WEBPHOTO_SYNO_VALUE', '�����');

    //---------------------------------------------------------
    // title
    //---------------------------------------------------------
    define('_WEBPHOTO_TITLE_LATEST', '����');
    define('_WEBPHOTO_TITLE_SUBMIT', '���');
    define('_WEBPHOTO_TITLE_POPULAR', '��͵�');
    define('_WEBPHOTO_TITLE_HIGHRATE', '�ȥåץ��');
    define('_WEBPHOTO_TITLE_MYPHOTO', '��ʬ�����');
    define('_WEBPHOTO_TITLE_RANDOM', '������ɽ��');
    define('_WEBPHOTO_TITLE_HELP', '�إ��');
    define('_WEBPHOTO_TITLE_CATEGORY_LIST', '���ƥ��� ����');
    define('_WEBPHOTO_TITLE_TAG_LIST', '���� ����');
    define('_WEBPHOTO_TITLE_TAGS', '����');
    define('_WEBPHOTO_TITLE_USER_LIST', '��Ƽ� ����');
    define('_WEBPHOTO_TITLE_DATE_LIST', '�������� ����');
    define('_WEBPHOTO_TITLE_PLACE_LIST', '���ƾ�� ����');
    define('_WEBPHOTO_TITLE_RSS', 'RSS');

    define('_WEBPHOTO_VIEWTYPE_LIST', '�ꥹ�ȷ���');
    define('_WEBPHOTO_VIEWTYPE_TABLE', '�ơ��֥����');

    define('_WEBPHOTO_CATLIST_ON', '���ƥ����ɽ������');
    define('_WEBPHOTO_CATLIST_OFF', '���ƥ����ɽ�����ʤ�');
    define('_WEBPHOTO_TAGCLOUD_ON', '�������饦�ɤ�ɽ������');
    define('_WEBPHOTO_TAGCLOUD_OFF', '�������饦�ɤ�ɽ�����ʤ�');
    define('_WEBPHOTO_GMAP_ON', 'Google�ޥåפ�ɽ������');
    define('_WEBPHOTO_GMAP_OFF', 'Google�ޥåפ�ɽ�����ʤ�');

    define('_WEBPHOTO_NO_TAG', '���������ꤵ��Ƥ��ʤ�');

    //---------------------------------------------------------
    // google maps
    //---------------------------------------------------------
    define('_WEBPHOTO_TITLE_GET_LOCATION', '���١����٤�����');
    define('_WEBPHOTO_GMAP_DESC', 'Google�ޥåפΥޡ������򥯥�å�����ȡ�����ͥ��������ɽ������ޤ�');
    define('_WEBPHOTO_GMAP_ICON', 'GoogleMap ��������');
    define('_WEBPHOTO_GMAP_LATITUDE', 'GoogleMap ����');
    define('_WEBPHOTO_GMAP_LONGITUDE', 'GoogleMap ����');
    define('_WEBPHOTO_GMAP_ZOOM', 'GoogleMap ������');
    define('_WEBPHOTO_GMAP_ADDRESS', '����');
    define('_WEBPHOTO_GMAP_GET_LOCATION', '���١����٤��������');
    define('_WEBPHOTO_GMAP_SEARCH_LIST', '������̤ΰ���');
    define('_WEBPHOTO_GMAP_CURRENT_LOCATION', '���ߤΰ���');
    define('_WEBPHOTO_GMAP_CURRENT_ADDRESS', '���ߤν���');
    define('_WEBPHOTO_GMAP_NO_MATCH_PLACE', '���������꤬�ʤ�');
    define('_WEBPHOTO_GMAP_NOT_COMPATIBLE', '�����Υ֥饦���Ǥ� GoogleMaps ��ɽ���Ǥ��ޤ���');
    define('_WEBPHOTO_JS_INVALID', '�����Υ֥饦���Ǥ� JavaScript �����ѤǤ��ޤ���');
    define('_WEBPHOTO_IFRAME_NOT_SUPPORT', '�����Υ֥饦���Ǥ� iframe �����ѤǤ��ʤ�');

    //---------------------------------------------------------
    // search
    //---------------------------------------------------------
    define('_WEBPHOTO_SR_SEARCH', '����');

    //---------------------------------------------------------
    // popbox
    //---------------------------------------------------------
    define('_WEBPHOTO_POPBOX_REVERT', '����å�����ȡ����ξ����������ˤʤ�');

    //---------------------------------------------------------
    // tag
    //---------------------------------------------------------
    define('_WEBPHOTO_TAGS', '����');
    define('_WEBPHOTO_EDIT_TAG', '�������Խ�����');
    define('_WEBPHOTO_DSC_TAG_DIVID', 'ʣ���� ���ꤹ����� ����� , �Ƕ��ڤ�');
    define('_WEBPHOTO_DSC_TAG_EDITABLE', '��ʬ����Ͽ���������Τ��Խ��Ǥ��ޤ�');

    //---------------------------------------------------------
    // submit form
    //---------------------------------------------------------
    define('_WEBPHOTO_CAP_ALLOWED_EXTS', '���Ĥ���Ƥ����ĥ��');
    define('_WEBPHOTO_CAP_PHOTO_SELECT', '�̿���ư�衦��ǥ���������');
    define('_WEBPHOTO_CAP_THUMB_SELECT', '����ͥ������������');
    define('_WEBPHOTO_DSC_THUMB_SELECT', '���ꤷ�ʤ��Ȥ��ϡ��̿���ư�衦��ǥ�����꼫ư���������');
    define('_WEBPHOTO_DSC_SET_DATETIME', '�������������ꤹ��');

    //define("_WEBPHOTO_DSC_SET_TIME_UPDATE", "�����������ѹ�����");

    define('_WEBPHOTO_DSC_PIXCEL_RESIZE', '����ʾ��礭�������ϥꥵ�������ޤ�');
    define('_WEBPHOTO_DSC_PIXCEL_REJECT', '����ʾ��礭�������ϥ��åץ��ɤǤ��ޤ���');
    define('_WEBPHOTO_BUTTON_CLEAR', '�ꥻ�å�');
    define('_WEBPHOTO_SUBMIT_RESIZED', '�������礭���Τǡ��ꥵ��������');

    // PHP upload error
    // http://www.php.net/manual/en/features.file-upload.errors.php
    define('_WEBPHOTO_PHP_UPLOAD_ERR_OK', '���顼�Ϥʤ����ե����륢�åץ��ɤ��������Ƥ��ޤ�');
    define('_WEBPHOTO_PHP_UPLOAD_ERR_INI_SIZE', '���åץ��ɤ��줿�ե�����ϡ�upload_max_filesize ���ͤ�Ķ���Ƥ��ޤ�');
    define('_WEBPHOTO_PHP_UPLOAD_ERR_FORM_SIZE', '���åץ��ɤ��줿�ե�����ϡ�%s ��Ķ���Ƥ��ޤ�');
    define('_WEBPHOTO_PHP_UPLOAD_ERR_PARTIAL', '���åץ��ɤ��줿�ե�����ϰ����Τߤ������åץ��ɤ���Ƥ��ޤ���');
    define('_WEBPHOTO_PHP_UPLOAD_ERR_NO_FILE', '�ե�����ϥ��åץ��ɤ���ޤ���Ǥ���');
    define('_WEBPHOTO_PHP_UPLOAD_ERR_NO_TMP_DIR', '�ƥ�ݥ��ե����������ޤ���');
    define('_WEBPHOTO_PHP_UPLOAD_ERR_CANT_WRITE', '�ǥ������ؤν񤭹��ߤ˼��Ԥ��ޤ���');
    define('_WEBPHOTO_PHP_UPLOAD_ERR_EXTENSION', '�ե�����Υ��åץ��ɤ���ĥ�⥸�塼��ˤ�ä���ߤ���ޤ���');

    // upload error
    define('_WEBPHOTO_UPLOADER_ERR_NOT_FOUND', '���åץ��ɡ��ե����뤬���Ĥ���ʤ�');
    define('_WEBPHOTO_UPLOADER_ERR_INVALID_FILE_SIZE', '�ե����롦�����������ꤵ��Ƥ��ʤ�');
    define('_WEBPHOTO_UPLOADER_ERR_EMPTY_FILE_NAME', '�ե�����̾�����ꤵ��Ƥ��ʤ�');
    define('_WEBPHOTO_UPLOADER_ERR_NO_FILE', '�ե�����ϥ��åץ��ɤ���Ƥʤ�');
    define('_WEBPHOTO_UPLOADER_ERR_NOT_SET_DIR', '���åץ��ɡ��ǥ��쥯�ȥ꤬���ꤵ��Ƥ��ʤ�');
    define('_WEBPHOTO_UPLOADER_ERR_NOT_ALLOWED_EXT', '���Ĥ���Ƥ��ʤ���ĥ�ҤǤ�');
    define('_WEBPHOTO_UPLOADER_ERR_PHP_OCCURED', '���åץ������ǥ��顼��ȯ������ ');
    define('_WEBPHOTO_UPLOADER_ERR_NOT_OPEN_DIR', '���åץ��ɡ��ǥ��쥯�ȥ꤬�����ץ�Ǥ��ʤ� ');
    define('_WEBPHOTO_UPLOADER_ERR_NO_PERM_DIR', '���åץ��ɡ��ǥ��쥯�ȥ�Υ����������¤��ʤ� ');
    define('_WEBPHOTO_UPLOADER_ERR_NOT_ALLOWED_MIME', '���Ĥ���Ƥ��ʤ�MIME�����פǤ� ');
    define('_WEBPHOTO_UPLOADER_ERR_LARGE_FILE_SIZE', '�ե����롦���������礭������ ');
    define('_WEBPHOTO_UPLOADER_ERR_LARGE_WIDTH', '�����������礭������ ');
    define('_WEBPHOTO_UPLOADER_ERR_LARGE_HEIGHT', '�����⤵���礭������ ');
    define('_WEBPHOTO_UPLOADER_ERR_UPLOAD', '���åץ��ɤ˼��Ԥ��� ');

    //---------------------------------------------------------
    // help
    //---------------------------------------------------------
    define('_WEBPHOTO_HELP_DSC', '�����Υѥ������ư��륢�ץꥱ�����硼��������Ǥ�');

    define('_WEBPHOTO_HELP_PICLENS_TITLE', 'PicLens');
    define('_WEBPHOTO_HELP_PICLENS_DSC', '
Piclens �� Cooliris �Ҥ��󶡤��� FireFox �Υ��ɥ���Ǥ�<br>
WEB�����Ȥβ������������ӥ塼��Ǥ�<br><br>
<b>���͵���</b><br>
<a href="http://www.forest.impress.co.jp/article/2007/09/13/piclens.html" target="_blank">
������ͭ�������������������ѤΥӥ塼����ɲä���Firefox��ĥ��PicLens��
</a><br><br>
<b>������ˡ</b><br>
(1) FireFox ���������ɤ���<br>
<a href="http://www.mozilla-japan.org/products/firefox/" target="_blank">
http://www.mozilla-japan.org/products/firefox/
</a><br><br>
(2) Piclens ���ɥ��� ���������ɤ���<br>
<a href="http://www.piclens.com/" target="_blank">
http://www.piclens.com/
</a><br><br>
(3) FireFox �� webphoto �򸫤�<br>
http://���Υ�����/modules/webphoto/ <br><br>
(4) Firefox �α�����Ĥ��ޡ���������å�����<br>
�ޡ����������Ȥ��ϡ�piclens �ϻ��ѤǤ��ʤ�<br>');

    define('_WEBPHOTO_HELP_MEDIARSSSLIDESHOW_TITLE', 'Media RSS ���饤�ɥ��硼');
    define('_WEBPHOTO_HELP_MEDIARSSSLIDESHOW_DSC', '
Media RSS ���饤�ɥ��硼 �� Google �������åȤǤ�<br>
���󥿡��ͥåȤ���β����򥹥饤�ɥ��硼��ɽ�����ޤ�<br><br>
<b>������ˡ</b><br>
(1) Google �ǥ����ȥå� ���������ɤ���<br>
<a href="http://desktop.google.co.jp/" target="_blank">
http://desktop.google.co.jp/
</a><br><br>
(2) ��Media RSS ���饤�ɥ��硼�פΥ������åȤ��������ɤ���<br>
<a href="http://desktop.google.com/plugins/i/mediarssslideshow.html" target="_blank">
http://desktop.google.com/plugins/i/mediarssslideshow.html
</a><br><br>
(3) �������åȤΥ��ץ����ˤơ���MediaRSS �� URL�פ򲼵����ѹ�����<br>');

    //---------------------------------------------------------
    // others
    //---------------------------------------------------------
    define('_WEBPHOTO_RANDOM_MORE', '�̿���ư�衦��ǥ����������ˤ�äȸ���');
    define('_WEBPHOTO_USAGE_PHOTO', '�����򥯥�å�����ȡ��礭�ʲ������ݥåץ��åפ��ޤ�');
    define('_WEBPHOTO_USAGE_TITLE', '�����ȥ�򥯥�å�����ȡ����μ̿���ư�衦��ǥ����Υڡ����������ޤ�');
    define('_WEBPHOTO_DATE_NOT_SET', '�������� ����ʤ�');
    define('_WEBPHOTO_PLACE_NOT_SET', '���ƾ�� ����ʤ�');
    define('_WEBPHOTO_GOTO_ADMIN', '�����Բ��̤�');

    //---------------------------------------------------------
    // search for Japanese
    //---------------------------------------------------------
    define('_WEBPHOTO_SR_CANDICATE', '�����θ���');
    define('_WEBPHOTO_SR_ZENKAKU', '����');
    define('_WEBPHOTO_SR_HANKAKU', 'Ⱦ��');

    define('_WEBPHOTO_JA_KUTEN', '��');
    define('_WEBPHOTO_JA_DOKUTEN', '��');
    define('_WEBPHOTO_JA_PERIOD', '��');
    define('_WEBPHOTO_JA_COMMA', '��');

    //---------------------------------------------------------
    // v0.20
    //---------------------------------------------------------
    define('_WEBPHOTO_TITLE_VIDEO_THUMB_SEL', 'ư��Υ���ͥ�������򤹤�');
    define('_WEBPHOTO_TITLE_VIDEO_REDO', '���åץ��ɺѤߤ�ư���� Flashư��ȥ���ͥ������������');
    define('_WEBPHOTO_CAP_REDO_THUMB', '����ͥ������������');
    define('_WEBPHOTO_CAP_REDO_FLASH', 'Flash ư�����������');
    define('_WEBPHOTO_ERR_VIDEO_FLASH', 'Flash ư��������Ǥ��ʤ��ä�');
    define('_WEBPHOTO_ERR_VIDEO_THUMB', 'ư��Υ���ͥ��뤬�����Ǥ��ʤ��ä��Τǡ�������������Ѥ���');
    define('_WEBPHOTO_BUTTON_SELECT', '����');

    define('_WEBPHOTO_DSC_DOWNLOAD_PLAY', '��������ɤ��ƺ�������');
    define('_WEBPHOTO_ICON_VIDEO', 'ư��');
    define('_WEBPHOTO_HOUR', '����');
    define('_WEBPHOTO_MINUTE', 'ʬ');
    define('_WEBPHOTO_SECOND', '��');

    //---------------------------------------------------------
    // v0.30
    //---------------------------------------------------------
    // user table
    define('_WEBPHOTO_USER_TABLE', '�桼������ơ��֥�');
    define('_WEBPHOTO_USER_ID', '�桼�����ID');
    define('_WEBPHOTO_USER_TIME_CREATE', '��������');
    define('_WEBPHOTO_USER_TIME_UPDATE', '��������');
    define('_WEBPHOTO_USER_UID', '�桼���ֹ�');
    define('_WEBPHOTO_USER_CAT_ID', '���ƥ����ֹ�');
    define('_WEBPHOTO_USER_EMAIL', '�᡼�륢�ɥ쥹');
    define('_WEBPHOTO_USER_TEXT1', 'text1');
    define('_WEBPHOTO_USER_TEXT2', 'text2');
    define('_WEBPHOTO_USER_TEXT3', 'text3');
    define('_WEBPHOTO_USER_TEXT4', 'text4');
    define('_WEBPHOTO_USER_TEXT5', 'text5');

    // maillog
    define('_WEBPHOTO_MAILLOG_TABLE', '�᡼������ơ��֥�');
    define('_WEBPHOTO_MAILLOG_ID', '�᡼���ID');
    define('_WEBPHOTO_MAILLOG_TIME_CREATE', '��������');
    define('_WEBPHOTO_MAILLOG_TIME_UPDATE', '��������');
    define('_WEBPHOTO_MAILLOG_PHOTO_IDS', '�����ƥ��ֹ�');
    define('_WEBPHOTO_MAILLOG_STATUS', '����');
    define('_WEBPHOTO_MAILLOG_FROM', '�����ԥ᡼�륢�ɥ쥹');
    define('_WEBPHOTO_MAILLOG_SUBJECT', '��̾');
    define('_WEBPHOTO_MAILLOG_BODY', '��ʸ');
    define('_WEBPHOTO_MAILLOG_FILE', '�ե�����̾');
    define('_WEBPHOTO_MAILLOG_ATTACH', 'ź�եե�����');
    define('_WEBPHOTO_MAILLOG_COMMENT', '������');

    // mail register
    define('_WEBPHOTO_TITLE_MAIL_REGISTER', '��륢����Ͽ');
    define('_WEBPHOTO_MAIL_HELP', '�Ȥ����ϥإ�פ�����������');
    define('_WEBPHOTO_CAT_USER', '�桼��̾');
    define('_WEBPHOTO_BUTTON_REGISTER', '��Ͽ');
    define('_WEBPHOTO_NOMATCH_USER', '��������桼�������ʤ�');
    define('_WEBPHOTO_ERR_MAIL_EMPTY', '�᡼�륢�ɥ쥹��ɬ�פǤ�');
    define('_WEBPHOTO_ERR_MAIL_ILLEGAL', '�᡼�륢�ɥ쥹�η�������������');

    // mail retrieve
    define('_WEBPHOTO_TITLE_MAIL_RETRIEVE', '�᡼�����');
    define('_WEBPHOTO_DSC_MAIL_RETRIEVE', '�᡼�륵���С�����᡼����������');
    define('_WEBPHOTO_BUTTON_RETRIEVE', '�᡼�����');
    define('_WEBPHOTO_SUBTITLE_MAIL_ACCESS', '�᡼�륵���С��˥����������ޤ�');
    define('_WEBPHOTO_SUBTITLE_MAIL_PARSE', '���������᡼�����ɤ��ޤ�');
    define('_WEBPHOTO_SUBTITLE_MAIL_PHOTO', '�᡼���ź�դ��줿��������Ͽ���ޤ�');
    define('_WEBPHOTO_TEXT_MAIL_ACCESS_TIME', '��������������Ǥ�');
    define('_WEBPHOTO_TEXT_MAIL_RETRY', '��ʬ��˥����������Ƥ�������');
    define('_WEBPHOTO_TEXT_MAIL_NOT_RETRIEVE', '�᡼�������Ǥ��ʤ��ä���<br>���Ū���̿��㳲�Ȼפ��ޤ���<br>���Ф餯���֤򤢤��Ƥ��顢��ߤƤ���������');
    define('_WEBPHOTO_TEXT_MAIL_NO_NEW', '����᡼��Ϥ���ޤ���');
    define('_WEBPHOTO_TEXT_MAIL_RETRIEVED_FMT', '%s ��Υ᡼���������ޤ���');
    define('_WEBPHOTO_TEXT_MAIL_NO_VALID', 'ͭ���ʥ᡼��Ϥ���ޤ���');
    define('_WEBPHOTO_TEXT_MAIL_SUBMITED_FMT', '%s ��β�������Ͽ���ޤ���');
    define('_WEBPHOTO_GOTO_INDEX', '�⥸�塼��Υȥåץڡ�����');

    // i.php
    define('_WEBPHOTO_TITLE_MAIL_POST', '�᡼�뤫����Ƥ���');

    // file
    define('_WEBPHOTO_TITLE_SUBMIT_FILE', '�ե����뤫��μ̿���ư�衦��ǥ������ɲ�');
    define('_WEBPHOTO_CAP_FILE_SELECT', '�ե����������');
    define('_WEBPHOTO_ERR_EMPTY_FILE', '�ե��������ꤷ�Ƥ�������');
    define('_WEBPHOTO_ERR_EMPTY_CAT', '���ƥ������ꤷ�Ƥ�������');
    define('_WEBPHOTO_ERR_INVALID_CAT', '̵���ʥ��ƥ���Ǥ�');
    define('_WEBPHOTO_ERR_CREATE_PHOTO', '��������Ͽ�Ǥ��ʤ��ä�');
    define('_WEBPHOTO_ERR_CREATE_THUMB', '����ͥ������Ͽ�Ǥ��ʤ��ä�');

    // help
    define('_WEBPHOTO_HELP_MUST_LOGIN', '�ܤ����������ɤ�ˤϡ������󤷤Ƥ�������');
    define('_WEBPHOTO_HELP_NOT_PERM', '�����ˤϵ��Ĥ���Ƥ��ޤ��󡣴����ԤޤǤ��䤤��碌����������');

    define('_WEBPHOTO_HELP_MOBILE_TITLE', '��������');
    define('_WEBPHOTO_HELP_MOBILE_DSC', '�������äˤơ�������ư���ɽ�����뤳�Ȥ��Ǥ��ޤ�<br>240��320 ���٤β��̥������Ǥ�');
    define('_WEBPHOTO_HELP_MOBILE_TEXT_FMT', '
<b>��������URL</b><br>
<a href="{MODULE_URL}/i.php" target="_blank">{MODULE_URL}/i.php</a>');

    define('_WEBPHOTO_HELP_MAIL_TITLE', '���ӥ᡼��');
    define('_WEBPHOTO_HELP_MAIL_DSC', '�������ä���᡼����������ơ�������ư�����Ƥ��뤳�Ȥ��Ǥ��ޤ�');
    define('_WEBPHOTO_HELP_MAIL_GUEST', '����ϸ��ܤǤ������¤��ʤ��ȡ��������᡼�륢�ɥ쥹��ɽ������ޤ���');

    define('_WEBPHOTO_HELP_FILE_TITLE', 'FTP �ˤ�����');
    define('_WEBPHOTO_HELP_FILE_DSC', 'FTP �ˤ��ե�����򥢥åץ��ɤ��뤳�Ȥǡ��ե��������̤��礭�ʼ̿���ư�衦��ǥ�������Ƥ��뤳�Ȥ��Ǥ��ޤ�');
    define('_WEBPHOTO_HELP_FILE_TEXT_FMT', '
<b>�����ˡ</b><br>
(1) ���ꤵ�줿 FTP �����С��˥ե�����򥢥åץ��ɤ���<br>
(2) <a href="{MODULE_URL}/index.php?fct=submit_file" target="_blank">�֥ե����뤫��μ̿���ư�衦��ǥ������ɲá�</a> �򥯥�å�����<br>
(3) ���åץ��ɤ����ե��������ꤷ����Ƥ���');

    // mail check
    // for Japanese
    define('_WEBPHOTO_MAIL_DENY_TITLE_PREG', "/((̤|��)\s?��\s?(��|ǧ)\s?��\s?��|��ߥ��|18��|�����ȤΤ��Ҳ�)/i");
    define('_WEBPHOTO_MAIL_AD_WORD_1', '�����Ͽ��̵��  ���¤������ʥ����ƥ�ʤ� MSN �����������');
    define('_WEBPHOTO_MAIL_AD_WORD_2', 'ͧã��24���֥ۥåȥ饤���MSN ��å��󥸥㡼�ס���������������ɡ�');

    //---------------------------------------------------------
    // v0.40
    //---------------------------------------------------------
    // item table
    define('_WEBPHOTO_ITEM_TABLE', '�����ƥࡦ�ơ��֥�');
    define('_WEBPHOTO_ITEM_ID', '�����ƥ�ID');
    define('_WEBPHOTO_ITEM_TIME_CREATE', '��������');
    define('_WEBPHOTO_ITEM_TIME_UPDATE', '��������');
    define('_WEBPHOTO_ITEM_CAT_ID', '���ƥ����ֹ�');
    define('_WEBPHOTO_ITEM_GICON_ID', 'GoogleMap ���������ֹ�');
    define('_WEBPHOTO_ITEM_UID', '�桼���ֹ�');
    define('_WEBPHOTO_ITEM_KIND', '����');
    define('_WEBPHOTO_ITEM_EXT', '��ĥ��');
    define('_WEBPHOTO_ITEM_DATETIME', '��������');
    define('_WEBPHOTO_ITEM_TITLE', '�̿���ư�衦��ǥ����Υ����ȥ�');
    define('_WEBPHOTO_ITEM_PLACE', '���ƾ��');
    define('_WEBPHOTO_ITEM_EQUIPMENT', '���Ƶ���');
    define('_WEBPHOTO_ITEM_GMAP_LATITUDE', 'GoogleMap ����');
    define('_WEBPHOTO_ITEM_GMAP_LONGITUDE', 'GoogleMap ����');
    define('_WEBPHOTO_ITEM_GMAP_ZOOM', 'GoogleMap ������');
    define('_WEBPHOTO_ITEM_GMAP_TYPE', 'GoogleMap ������');
    define('_WEBPHOTO_ITEM_PERM_READ', '��������');
    define('_WEBPHOTO_ITEM_STATUS', '����');
    define('_WEBPHOTO_ITEM_HITS', '�ҥåȿ�');
    define('_WEBPHOTO_ITEM_RATING', 'ɾ��');
    define('_WEBPHOTO_ITEM_VOTES', '��ɼ��');
    define('_WEBPHOTO_ITEM_DESCRIPTION', '�̿���ư�衦��ǥ���������ʸ');
    define('_WEBPHOTO_ITEM_EXIF', 'Exif ����');
    define('_WEBPHOTO_ITEM_SEARCH', '����ʸ');
    define('_WEBPHOTO_ITEM_COMMENTS', '�����ȿ�');
    define('_WEBPHOTO_ITEM_FILE_ID_1', '�ե������ֹ桧����ƥ��');
    define('_WEBPHOTO_ITEM_FILE_ID_2', '�ե������ֹ桧����ͥ���');
    define('_WEBPHOTO_ITEM_FILE_ID_3', '�ե������ֹ桧�ߥɥ�');
    define('_WEBPHOTO_ITEM_FILE_ID_4', '�ե������ֹ桧Flash �ӥǥ�');
    define('_WEBPHOTO_ITEM_FILE_ID_5', '�ե������ֹ桧�ɥ��� �ӥǥ�');
    define('_WEBPHOTO_ITEM_FILE_ID_6', '�ե������ֹ桧PDF');
    define('_WEBPHOTO_ITEM_FILE_ID_7', '�ե������ֹ桧Flash swf');
    define('_WEBPHOTO_ITEM_FILE_ID_8', '�ե������ֹ桧���⡼��');
    define('_WEBPHOTO_ITEM_FILE_ID_9', '�ե������ֹ桧JPEG');
    define('_WEBPHOTO_ITEM_FILE_ID_10', '�ե������ֹ桧MP3');
    define('_WEBPHOTO_ITEM_TEXT_1', 'text1');
    define('_WEBPHOTO_ITEM_TEXT_2', 'text2');
    define('_WEBPHOTO_ITEM_TEXT_3', 'text3');
    define('_WEBPHOTO_ITEM_TEXT_4', 'text4');
    define('_WEBPHOTO_ITEM_TEXT_5', 'text5');
    define('_WEBPHOTO_ITEM_TEXT_6', 'text6');
    define('_WEBPHOTO_ITEM_TEXT_7', 'text7');
    define('_WEBPHOTO_ITEM_TEXT_8', 'text8');
    define('_WEBPHOTO_ITEM_TEXT_9', 'text9');
    define('_WEBPHOTO_ITEM_TEXT_10', 'text10');

    // file table
    define('_WEBPHOTO_FILE_TABLE', '�ե����롦�ơ��֥�');
    define('_WEBPHOTO_FILE_ID', '�ե�����ID');
    define('_WEBPHOTO_FILE_TIME_CREATE', '��������');
    define('_WEBPHOTO_FILE_TIME_UPDATE', '��������');
    define('_WEBPHOTO_FILE_ITEM_ID', '�����ƥ��ֹ�');
    define('_WEBPHOTO_FILE_KIND', '����');
    define('_WEBPHOTO_FILE_URL', 'URL');
    define('_WEBPHOTO_FILE_PATH', '�ѥ�');
    define('_WEBPHOTO_FILE_NAME', '�ե�����̾');
    define('_WEBPHOTO_FILE_EXT', '��ĥ��');
    define('_WEBPHOTO_FILE_MIME', 'MIME������');
    define('_WEBPHOTO_FILE_MEDIUM', '��ǥ���������');
    define('_WEBPHOTO_FILE_SIZE', '�ե����륵����');
    define('_WEBPHOTO_FILE_WIDTH', '��������');
    define('_WEBPHOTO_FILE_HEIGHT', '�����⤵');
    define('_WEBPHOTO_FILE_DURATION', '�ӥǥ���������');

    // file kind ( for admin checktables )
    define('_WEBPHOTO_FILE_KIND_1', '����ƥ��');
    define('_WEBPHOTO_FILE_KIND_2', '����ͥ���');
    define('_WEBPHOTO_FILE_KIND_3', '�ߥɥ�');
    define('_WEBPHOTO_FILE_KIND_4', 'Flash �ӥǥ�');
    define('_WEBPHOTO_FILE_KIND_5', '�ɥ��� �ӥǥ�');
    define('_WEBPHOTO_FILE_KIND_6', 'PDF');
    define('_WEBPHOTO_FILE_KIND_7', 'Flash swf');
    define('_WEBPHOTO_FILE_KIND_8', '���⡼��');
    define('_WEBPHOTO_FILE_KIND_9', 'JPEG');
    define('_WEBPHOTO_FILE_KIND_10', 'MP3');

    // index
    define('_WEBPHOTO_MOBILE_MAILTO', '�������ä�URL����������');

    // i.php
    define('_WEBPHOTO_TITLE_MAIL_JUDGE', '�������õ��ε����Ƚ�ꤹ��');
    define('_WEBPHOTO_MAIL_MODEL', '����');
    define('_WEBPHOTO_MAIL_BROWSER', 'WEB�֥饦��');
    define('_WEBPHOTO_MAIL_NOT_JUDGE', '���郎Ƚ��Ǥ��ʤ�');
    define('_WEBPHOTO_MAIL_TO_WEBMASTER', '�����ȴ����Ԥ�Ϣ����');

    // help
    define('_WEBPHOTO_HELP_MAIL_POST_FMT', '
<b>����</b><br>
�������äΥ᡼�륢�ɥ쥹����Ͽ���Ƥ�������<br>
<a href="{MODULE_URL}/index.php?fct=mail_register" target="_blank">�֥᡼�륢�ɥ쥹��Ͽ��</a><br><br>
<b>�����ˡ</b><br>
�����Υ᡼�륢�ɥ쥹�˥᡼�����������<br>
<a href="mailto:{MAIL_ADDR}">{MAIL_ADDR}</a> {MAIL_GUEST} <br><br>
<b>�����β�ž</b><br>
��̾ (Subject) �������˲����Τ褦�˵������뤳�Ȥǡ���������ž���ޤ���<br>
 R@ : ����ž <br>
 L@ : ����ž <br><br>');
    define('_WEBPHOTO_HELP_MAIL_SUBTITLE_RETRIEVE', '<b>�᡼��μ����ȡ���������Ͽ</b><br>');
    define('_WEBPHOTO_HELP_MAIL_RETRIEVE_FMT', '
�᡼����������Ƥ��顢���ø�� <a href="{MODULE_URL}/i.php?op=post" target="_blank">�֥᡼�뤫����Ƥ����</a> �򥯥�å����Ƥ���������<br>');
    define('_WEBPHOTO_HELP_MAIL_RETRIEVE_TEXT', '���ʤ������������᡼��������ǡ�������ư���Ǻܤ��ޤ���<br>');
    define('_WEBPHOTO_HELP_MAIL_RETRIEVE_AUTO_FMT', '
�᡼�����������ȡ� %s �ø�˼�ưŪ����Ͽ����ޤ���<br>
��Ͽ����ʤ����ϡ�<a href="{MODULE_URL}/i.php?op=post" target="_blank">�֥᡼�뤫����Ƥ����</a> �򥯥�å����Ƥ���������<br>');

    //---------------------------------------------------------
    // v0.50
    //---------------------------------------------------------
    // item table
    define('_WEBPHOTO_ITEM_TIME_PUBLISH', 'ȯ������');
    define('_WEBPHOTO_ITEM_TIME_EXPIRE', '��λ����');
    define('_WEBPHOTO_ITEM_PLAYER_ID', '�ץ쥤�䡼�ֹ�');
    define('_WEBPHOTO_ITEM_FLASHVAR_ID', '�ե�å����ѿ��ֹ�');
    define('_WEBPHOTO_ITEM_DURATION', '�ӥǥ���������');
    define('_WEBPHOTO_ITEM_DISPLAYTYPE', 'ɽ������');
    define('_WEBPHOTO_ITEM_ONCLICK', '����ͥ���򥯥�å������Ȥ���ư��');
    define('_WEBPHOTO_ITEM_SITEURL', '������URL');
    define('_WEBPHOTO_ITEM_ARTIST', '�����ƥ�����');
    define('_WEBPHOTO_ITEM_ALBUM', '����Х�');
    define('_WEBPHOTO_ITEM_LABEL', '�졼�٥�');
    define('_WEBPHOTO_ITEM_VIEWS', '������');
    define('_WEBPHOTO_ITEM_PERM_DOWN', '��������ɸ���');
    define('_WEBPHOTO_ITEM_EMBED_TYPE', '�ץ饰����Υ�����');
    define('_WEBPHOTO_ITEM_EMBED_SRC', '�ץ饰�����URL�ѥ�᡼��');
    define('_WEBPHOTO_ITEM_EXTERNAL_URL', '������󥯤�URL');
    define('_WEBPHOTO_ITEM_EXTERNAL_THUMB', '������󥯤Υ���ͥ���URL');
    define('_WEBPHOTO_ITEM_PLAYLIST_TYPE', '�ץ쥤�ꥹ�ȤΥ�����');
    define('_WEBPHOTO_ITEM_PLAYLIST_FEED', '�ץ쥤�ꥹ�Ȥ�Feed URL');
    define('_WEBPHOTO_ITEM_PLAYLIST_DIR', '�ץ쥤�ꥹ�ȤΥǥ��쥯�ȥ�');
    define('_WEBPHOTO_ITEM_PLAYLIST_CACHE', '�ץ쥤�ꥹ�ȤΥ���å���̾');
    define('_WEBPHOTO_ITEM_PLAYLIST_TIME', '�ץ쥤�ꥹ�ȤΥ���å������');
    define('_WEBPHOTO_ITEM_CHAIN', '��������');
    define('_WEBPHOTO_ITEM_SHOWINFO', 'ɽ������');

    // player table
    define('_WEBPHOTO_PLAYER_TABLE', '�ץ쥤�䡼���ơ��֥�');
    define('_WEBPHOTO_PLAYER_ID', '�ץ쥤�䡼 ID');
    define('_WEBPHOTO_PLAYER_TIME_CREATE', '��������');
    define('_WEBPHOTO_PLAYER_TIME_UPDATE', '��������');
    define('_WEBPHOTO_PLAYER_TITLE', '�ץ쥤�䡼̾�� ');
    define('_WEBPHOTO_PLAYER_STYLE', '�������롦���ץ����');
    define('_WEBPHOTO_PLAYER_WIDTH', '�ץ쥤�䡼��');
    define('_WEBPHOTO_PLAYER_HEIGHT', '�ץ쥤�䡼�⤵');
    define('_WEBPHOTO_PLAYER_DISPLAYWIDTH', '�����꡼����');
    define('_WEBPHOTO_PLAYER_DISPLAYHEIGHT', '�����꡼��⤵');
    define('_WEBPHOTO_PLAYER_SCREENCOLOR', '�ץ쥤�䡼�طʿ�');
    define('_WEBPHOTO_PLAYER_BACKCOLOR', '�طʿ�');
    define('_WEBPHOTO_PLAYER_FRONTCOLOR', '�ƥ����ȿ�');
    define('_WEBPHOTO_PLAYER_LIGHTCOLOR', '�ϥ��饤�ȿ�');

    // FlashVar table
    define('_WEBPHOTO_FLASHVAR_TABLE', 'Flash�ѿ��ơ��֥�');
    define('_WEBPHOTO_FLASHVAR_ID', 'Flash�ѿ� ID');
    define('_WEBPHOTO_FLASHVAR_TIME_CREATE', '��������');
    define('_WEBPHOTO_FLASHVAR_TIME_UPDATE', '��������');
    define('_WEBPHOTO_FLASHVAR_ITEM_ID', '�����ƥ� ID');
    define('_WEBPHOTO_FLASHVAR_WIDTH', '�ץ쥤�䡼��');
    define('_WEBPHOTO_FLASHVAR_HEIGHT', '�ץ쥤�䡼�⤵');
    define('_WEBPHOTO_FLASHVAR_DISPLAYWIDTH', '�����꡼����');
    define('_WEBPHOTO_FLASHVAR_DISPLAYHEIGHT', '�����꡼��⤵');
    define('_WEBPHOTO_FLASHVAR_IMAGE_SHOW', '������ɽ��');
    define('_WEBPHOTO_FLASHVAR_SEARCHBAR', '�����С�');
    define('_WEBPHOTO_FLASHVAR_SHOWEQ', '�����饤����ɽ��');
    define('_WEBPHOTO_FLASHVAR_SHOWICONS', '�ץ쥤�ܥ���ɽ��');
    define('_WEBPHOTO_FLASHVAR_SHOWNAVIGATION', '����ȥ��롦�С�ɽ��');
    define('_WEBPHOTO_FLASHVAR_SHOWSTOP', '���ȥå�ɽ��');
    define('_WEBPHOTO_FLASHVAR_SHOWDIGITS', '�в����ɽ��');
    define('_WEBPHOTO_FLASHVAR_SHOWDOWNLOAD', '���������ɽ��');
    define('_WEBPHOTO_FLASHVAR_USEFULLSCREEN', '�ե륹���꡼��ɽ��');
    define('_WEBPHOTO_FLASHVAR_AUTOSCROLL', '��������С�');
    define('_WEBPHOTO_FLASHVAR_THUMBSINPLAYLIST', '����ͥ���');
    define('_WEBPHOTO_FLASHVAR_AUTOSTART', '�����ȥ�������');
    define('_WEBPHOTO_FLASHVAR_REPEAT', '�����֤�');
    define('_WEBPHOTO_FLASHVAR_SHUFFLE', '����ե�');
    define('_WEBPHOTO_FLASHVAR_SMOOTHING', 'ư�襹�ࡼ��');
    define('_WEBPHOTO_FLASHVAR_ENABLEJS', 'JavaScript ͭ��');
    define('_WEBPHOTO_FLASHVAR_LINKFROMDISPLAY', '�ǥ����ץ쥤����Υ��');
    define('_WEBPHOTO_FLASHVAR_LINK_TYPE', '��󥯡�������');
    define('_WEBPHOTO_FLASHVAR_BUFFERLENGTH', '�Хåե���������');
    define('_WEBPHOTO_FLASHVAR_ROTATETIME', '���ƥ��Ȼ���');
    define('_WEBPHOTO_FLASHVAR_VOLUME', '����');
    define('_WEBPHOTO_FLASHVAR_LINKTARGET', '��󥯡��������å�');
    define('_WEBPHOTO_FLASHVAR_OVERSTRETCH', '����/ư��ο�ĥ');
    define('_WEBPHOTO_FLASHVAR_TRANSITION', '���������ؤ�����');
    define('_WEBPHOTO_FLASHVAR_SCREENCOLOR', '�����꡼���طʿ�');
    define('_WEBPHOTO_FLASHVAR_BACKCOLOR', '�طʿ�');
    define('_WEBPHOTO_FLASHVAR_FRONTCOLOR', '�ƥ����ȿ�');
    define('_WEBPHOTO_FLASHVAR_LIGHTCOLOR', '�ϥ��饤�ȿ�');
    define('_WEBPHOTO_FLASHVAR_TYPE', '��ĥ��');
    define('_WEBPHOTO_FLASHVAR_FILE', '��ǥ������ե�����');
    define('_WEBPHOTO_FLASHVAR_IMAGE', '�ץ�ӥ塼����');
    define('_WEBPHOTO_FLASHVAR_LOGO', '������');
    define('_WEBPHOTO_FLASHVAR_LINK', '���');
    define('_WEBPHOTO_FLASHVAR_AUDIO', '�����ǥ���');
    define('_WEBPHOTO_FLASHVAR_CAPTIONS', '���Ф� URL');
    define('_WEBPHOTO_FLASHVAR_FALLBACK', '�ե�����Хå� URL');
    define('_WEBPHOTO_FLASHVAR_CALLBACK', '������Хå� URL');
    define('_WEBPHOTO_FLASHVAR_JAVASCRIPTID', 'JavaScript ID');
    define('_WEBPHOTO_FLASHVAR_RECOMMENDATIONS', '�侩');
    define('_WEBPHOTO_FLASHVAR_STREAMSCRIPT', '���ȥ꡼�ߥ� URL');
    define('_WEBPHOTO_FLASHVAR_SEARCHLINK', '�������');

    // log file
    define('_WEBPHOTO_LOGFILE_LINE', '��');
    define('_WEBPHOTO_LOGFILE_DATE', '����');
    define('_WEBPHOTO_LOGFILE_REFERER', 'Referer');
    define('_WEBPHOTO_LOGFILE_IP', 'IP ���ɥ쥹');
    define('_WEBPHOTO_LOGFILE_STATE', '����');
    define('_WEBPHOTO_LOGFILE_ID', 'ID');
    define('_WEBPHOTO_LOGFILE_TITLE', '�����ȥ�');
    define('_WEBPHOTO_LOGFILE_FILE', '�ե�����');
    define('_WEBPHOTO_LOGFILE_DURATION', '��������');

    // item option
    define('_WEBPHOTO_ITEM_KIND_UNDEFINED', '̤���');
    define('_WEBPHOTO_ITEM_KIND_NONE', '��ǥ����ʤ�');
    define('_WEBPHOTO_ITEM_KIND_GENERAL', '����');
    define('_WEBPHOTO_ITEM_KIND_IMAGE', '���� (jpg,gif,png)');
    define('_WEBPHOTO_ITEM_KIND_VIDEO', 'ư�� (wmv,mov,flv...');
    define('_WEBPHOTO_ITEM_KIND_AUDIO', '�����ǥ��� (mp3...)');
    define('_WEBPHOTO_ITEM_KIND_EMBED', '�ץ饰����');
    define('_WEBPHOTO_ITEM_KIND_EXTERNAL_GENERAL', '������� ����');
    define('_WEBPHOTO_ITEM_KIND_EXTERNAL_IMAGE', '������� ����');
    define('_WEBPHOTO_ITEM_KIND_PLAYLIST_FEED', '�ץ쥤�ꥹ�� Web Feed');
    define('_WEBPHOTO_ITEM_KIND_PLAYLIST_DIR', '�ץ쥤�ꥹ�� ��ǥ������ǥ��쥯�ȥ�');

    define('_WEBPHOTO_ITEM_DISPLAYTYPE_GENERAL', '����');
    define('_WEBPHOTO_ITEM_DISPLAYTYPE_IMAGE', '���� (jpg,gif,png)');
    define('_WEBPHOTO_ITEM_DISPLAYTYPE_EMBED', '�ץ饰����');
    define('_WEBPHOTO_ITEM_DISPLAYTYPE_SWFOBJECT', 'FlashPlayer (swf)');
    define('_WEBPHOTO_ITEM_DISPLAYTYPE_MEDIAPLAYER', 'MediaPlayer (jpg,gif,png,flv,mp3)');
    define('_WEBPHOTO_ITEM_DISPLAYTYPE_IMAGEROTATOR', 'ImageRotator (jpg,gif,png)');

    define('_WEBPHOTO_ITEM_ONCLICK_PAGE', '�ܺ٥ڡ���');
    define('_WEBPHOTO_ITEM_ONCLICK_DIRECT', '�����쥯�ȥ��');
    define('_WEBPHOTO_ITEM_ONCLICK_POPUP', '�����ݥåץ��å�');

    define('_WEBPHOTO_ITEM_PLAYLIST_TYPE_DSC', 'What is the media file type?');
    define('_WEBPHOTO_ITEM_PLAYLIST_TYPE_NONE', '�ʤ�');
    define('_WEBPHOTO_ITEM_PLAYLIST_TYPE_IMAGE', '���� (jpg,gif,png)');
    define('_WEBPHOTO_ITEM_PLAYLIST_TYPE_AUDIO', '�����ǥ��� (mp3)');
    define('_WEBPHOTO_ITEM_PLAYLIST_TYPE_VIDEO', 'ư�� (flv)');
    define('_WEBPHOTO_ITEM_PLAYLIST_TYPE_FLASH', '�ե�å��� (swf)');

    define('_WEBPHOTO_ITEM_SHOWINFO_DESCRIPTION', '����');
    define('_WEBPHOTO_ITEM_SHOWINFO_LOGOIMAGE', '����ͥ���');
    define('_WEBPHOTO_ITEM_SHOWINFO_CREDITS', '���쥸�å�');
    define('_WEBPHOTO_ITEM_SHOWINFO_STATISTICS', '����');
    define('_WEBPHOTO_ITEM_SHOWINFO_SUBMITTER', '��Ƽ�');
    define('_WEBPHOTO_ITEM_SHOWINFO_POPUP', '�ݥåץ��å�');
    define('_WEBPHOTO_ITEM_SHOWINFO_TAGS', '����');
    define('_WEBPHOTO_ITEM_SHOWINFO_DOWNLOAD', '���������');
    define('_WEBPHOTO_ITEM_SHOWINFO_WEBSITE', 'WEB ������');
    define('_WEBPHOTO_ITEM_SHOWINFO_WEBFEED', 'WEB Feed');

    define('_WEBPHOTO_ITEM_STATUS_WAITING', '��ǧ�Ԥ�');
    define('_WEBPHOTO_ITEM_STATUS_APPROVED', '��ǧ�Ѥ�');
    define('_WEBPHOTO_ITEM_STATUS_UPDATED', '����');
    define('_WEBPHOTO_ITEM_STATUS_OFFLINE', '���ե饤��');
    define('_WEBPHOTO_ITEM_STATUS_EXPIRED', '�����ڤ�');

    // player option
    define('_WEBPHOTO_PLAYER_STYLE_MONO', '��Υ���');
    define('_WEBPHOTO_PLAYER_STYLE_THEME', '�ơ��ޤ���ο�');
    define('_WEBPHOTO_PLAYER_STYLE_PLAYER', '�������ࡦ�ץ쥤�䡼');
    define('_WEBPHOTO_PLAYER_STYLE_PAGE', '�������ࡦ�ץ쥤�䡼/�ڡ���');

    // flashvar desc
    define('_WEBPHOTO_FLASHVAR_ID_DSC', '[Basics] <br>mediaplayer �ΤȤ���RTMP ���ȥ꡼�� ID �����ꤹ��<br>ID �� ���ץ�����Хå������������<br>�ץ쥤�ꥹ�ȤΤȤ��ϡ�����ȥ��������Ǥ���');
    //define("_WEBPHOTO_FLASHVAR_HEIGHT_DSC","[Basics] ");
    //define("_WEBPHOTO_FLASHVAR_WIDTH_DSC","[Basics] ");
    define('_WEBPHOTO_FLASHVAR_DISPLAYHEIGHT_DSC', '[Playlist] [mediaplayer] ');
    define('_WEBPHOTO_FLASHVAR_DISPLAYWIDTH_DSC', '[Playlist] [mediaplayer] <br>�ץ쥤�ꥹ�Ȥ򲼤�ɽ��:<br> �����꡼���� = �ץ쥤�䡼��<br>�ץ쥤�ꥹ�Ȥ򲣤�ɽ��<br>�����꡼���� &gt; �ץ쥤�䡼�� ');
    define('_WEBPHOTO_FLASHVAR_DISPLAY_DEFAULT', '0 �ΤȤ��ϡ��ץ쥤�䡼�����꤬���Ѥ����');
    //define("_WEBPHOTO_FLASHVAR_SCREENCOLOR_DSC","[Colors] <br>imagerotator �ΤȤ��� <br>�ۤʤ��礭���β��������ߤ��Ƥ⡢���ʤ���HTML�ڡ����˹礦�褦���ѹ�����");
    //define("_WEBPHOTO_FLASHVAR_BACKCOLOR_DSC","[Colors] <br>����ȥ�����طʿ�");
    //define("_WEBPHOTO_FLASHVAR_FRONTCOLOR_DSC","[Colors] <br>����ȥ���Υƥ����Ȥȥܥ���ο�");
    //define("_WEBPHOTO_FLASHVAR_LIGHTCOLOR_DSC","[Colors] <br>����ȥ���Υޥ��������Ф����Ȥ��ο�");
    define('_WEBPHOTO_FLASHVAR_COLOR_DEFAULT', '���ΤȤ��ϡ��ץ쥤�䡼�����꤬���Ѥ����');
    define('_WEBPHOTO_FLASHVAR_IMAGE_SHOW_DSC', '[Basics] <br>�ץ�ӥ塼������ɽ������');
    //define("_WEBPHOTO_FLASHVAR_IMAGE_DSC","[Basics] <br>�⤷���ڤ�ư����������ʤ�С��ץ�ӥ塼������ URL �����ꤹ�� <br>�ץ쥤�ꥹ�ȤΤȤ��ϡ�����ȥ��������Ǥ���");
    define('_WEBPHOTO_FLASHVAR_FILE_DSC', '[Basics] <br>�ե����뤫�ץ쥤�ꥹ�Ȥ� URL �����ꤹ��<br>imagerorate �ΤȤ��ϡ��ץ쥤�ꥹ�ȤΤ�����Ǥ���');
    define('_WEBPHOTO_FLASHVAR_SEARCHBAR_DSC', '[Basics] <br>�����꡼��β��˸����С���ɽ������ <br>�ָ�����󥯡פˤ�긡���������Ǥ���');
    //define("_WEBPHOTO_FLASHVAR_LOGO_DSC","[Display] <br>�����꡼��α����ɽ����������������ꤹ��<br>Ʃ�� PNG ����Ŭ");
    //define("_WEBPHOTO_FLASHVAR_OVERSTRETCH_DSC","[Display] <br>����/ư��򥹥��꡼����礭���˳�ĥ������ˡ�����ꤹ��<br>false (�ǥե����) = �����꡼��˰��פ���<br>true = �Ѱ�˳�ĥ����<br>fit = �ԶѰ�˳�ĥ����<br>none = �����礭�����ݻ�����");
    define('_WEBPHOTO_FLASHVAR_SHOWEQ_DSC', '[Display] <br>�����꡼��β��˵���Ū�ʥ����饤������ɽ������ <br>MP3 �˺�Ŭ ');
    //define("_WEBPHOTO_FLASHVAR_SHOWICONS_DSC","[Display] <br>�����꡼�������˥ץ쥤�ܥ����ɽ������");
    //define("_WEBPHOTO_FLASHVAR_TRANSITION_DSC","[Display] [imagerotator] <br>���������ؤ����̤����ꤹ�� ");
    //define("_WEBPHOTO_FLASHVAR_SHOWNAVIGATION_DSC","[Controlbar] <br>����ȥ��롦�С���ɽ������");
    define('_WEBPHOTO_FLASHVAR_SHOWSTOP_DSC', '[Controlbar] [mediaplayer] <br>����ȥ��롦�С��˥��ȥåס��ܥ����ɽ������');
    define('_WEBPHOTO_FLASHVAR_SHOWDIGITS_DSC', '[Controlbar] [mediaplayer] <br>����ȥ��롦�С��˷в����/�Ĥ���֤�ɽ������ ');
    define('_WEBPHOTO_FLASHVAR_SHOWDOWNLOAD_DSC', '[Controlbar] [mediaplayer] <br>����ȥ��롦�С��˥�󥯡��ܥ����ɽ������<br>�֥�󥯡פˤ����������ꤹ��');
    //define("_WEBPHOTO_FLASHVAR_USEFULLSCREEN_DSC","[Controlbar] <br>�ե륹���꡼�󡦥ܥ����ɽ������");
    define('_WEBPHOTO_FLASHVAR_AUTOSCROLL_DSC', '[Playlist] [mediaplayer] <br>�ץ쥤�ꥹ�ȤΥ������롦�С���ɽ����������ˡ��ޥ��������С������Ȥ��˼�ưŪ�˥������뤹��');
    define('_WEBPHOTO_FLASHVAR_THUMBSINPLAYLIST_DSC', '[Playlist] [mediaplayer] <br>�����꡼��˥���ͥ��������ɽ������');
    //define("_WEBPHOTO_FLASHVAR_AUDIO_DSC","[Playback] <br>Ʊ������ MP3 �������Ƥ�<br>mediaplayer �β��������ޤ��ϥǥ��쥯�����Υ����ȡ����뤤�� imagerotator ���طʲ��ڤȤ��ơ����Ѥ���<br>mediaplayer �ȥץ쥤�ꥹ�ȤΤȤ��ϡ�����ȥ���˥����ǥ���������Ǥ��� ");
    //define("_WEBPHOTO_FLASHVAR_AUTOSTART_DSC","[Playback] <br>mediaplayer �ˤ� true �ΤȤ��ϡ��ڡ�������ɤ����Ȥ��˼�ưŪ�˥������Ȥ���<br>imagerotator �ˤ� false �ΤȤ��� ��ưŪ�ʲ����ڤ��ؤ���Ԥ�ʤ�");
    //define("_WEBPHOTO_FLASHVAR_BUFFERLENGTH_DSC","[Playback]  [mediaplayer] <br>�����������ˡ�ư���Хåե������ÿ������ꤹ��<br>��®���̿��Ķ���û��ư��ΤȤ��Ͼ������ͤ����ꤹ��<br>��®���̿��Ķ��ΤȤ����礭���ͤ����ꤹ�� ");
    define('_WEBPHOTO_FLASHVAR_CAPTIONS_DSC', '[Playback] [mediaplayer] <br>�ָ��Ф��פ� TimedText �����Ǥ��뤳�� <br>�ץ쥤�ꥹ�ȤΤȤ��ϡ�����ȥ���˸��Ф�������Ǥ��� ');
    define('_WEBPHOTO_FLASHVAR_FALLBACK_DSC', '[Playback] [mediaplayer] <br>�⤷ MP4 ���������ʤ�С��ե�����Хå����� FLV �� URL �����ꤹ�뤳�� <br>�Ť��С������Υե�å��塦�ץ쥤�䡼�Ǥϼ�ưŪ�����򤵤�� ');
    //define("_WEBPHOTO_FLASHVAR_REPEAT_DSC","[Playback] <br>true �ΤȤ������ƤΥե�������֤��������� <br>�ץ쥤�ꥹ�ȤΤȤ��� ���٤����������� ");
    //define("_WEBPHOTO_FLASHVAR_ROTATETIME_DSC","[Playback] <br>���������ؤ�Ȥ����ÿ������ꤹ�� ");
    //define("_WEBPHOTO_FLASHVAR_SHUFFLE_DSC","[Playback] <br>�֤Ϥ��פΤȤ����ץ쥤�ꥹ�Ȥ������˺������� ");
    //define("_WEBPHOTO_FLASHVAR_SMOOTHING_DSC","[Playback] [mediaplayer] <br>�֤������פΤȤ��������Υ��ࡼ���󥰤�Ԥ�ʤ� <br>���������뤬����ǽ�ϸ��夹�� <br>HD �ե�������٤� PC �˺�Ŭ ");
    //define("_WEBPHOTO_FLASHVAR_VOLUME_DSC","[Playback] <br>���ڤ�ư��β��̤����ꤹ��");
    define('_WEBPHOTO_FLASHVAR_ENABLEJS_DSC', '[External] <br>�֤Ϥ��פΤȤ���JavaScript �ˤ�����������ͭ���ˤ��� <br>����饤��ΤȤ�ư���<br>JavaScript �ˤ����������ˤϡ�����ȥ���С��䡢��ǥ����ե�����Υ��ǥ󥰤�Ʊ���䡢�ȥ�å�������ֿ��ʤɤ�ޤ� ');
    define('_WEBPHOTO_FLASHVAR_JAVASCRIPTID_DSC', '[External] <br>�⤷�⡢ʣ���� mediaplayers/imagerotators �� JavaScript �ˤ�����������Ԥ��Ȥ��ϡ����ι��ܤˤ��줾����Ф����ˡ����� ID �����ꤹ�� ');
    define('_WEBPHOTO_FLASHVAR_LINK_TYPE_DSC', '[External] <br>�����꡼�������󥯡��ܥ���˳���������󥯤μ��̤����ꤹ��<br> �֤ʤ��פΤȤ��ϡ����⤷�ʤ� <br>����ʳ��ϡ����Υ�󥯤�����Ƥ�');
    //define("_WEBPHOTO_FLASHVAR_LINK_DSC","[External] <br>���� URL ���������ɲ�ǽ�ʥե���������ꤹ��<br>���Υ�󥯤ϥ����꡼�������󥯡��ܥ���˳��������<br>�ץ쥤�ꥹ�ȤΤȤ��ϡ�XML �����ˤƥ���ȥ��������Ǥ���");
    //define("_WEBPHOTO_FLASHVAR_LINKFROMDISPLAY_DSC","[External] <br>�֤Ϥ��פΤȤ��ϡ������꡼��򥯥�å�����ȡ��֥�󥯡פ����ꤵ�줿 WEB �ڡ����Υ����פ��� ");
    //define("_WEBPHOTO_FLASHVAR_LINKTARGET_DSC","[External] <br>��󥯤���������ɥ����̤μ���");
    define('_WEBPHOTO_FLASHVAR_CALLBACK_DSC', '[External] <br>Set this to a serverside script that can process statistics. <br>The player will send it a POST every time an item starts/stops. <br>To send callbacks automatically to Google Analytics, set this to urchin or analytics. ');
    define('_WEBPHOTO_FLASHVAR_RECOMMENDATIONS_DSC', '[External] [mediaplayer] <br>�侩������ܤ� XML ���������ꤹ�� <br>ư�褬��ߤ��Ƥ���Ȥ��ϡ�Youtube ��Ʊ���褦�˥���ͥ��뤬ɽ������� ');
    define('_WEBPHOTO_FLASHVAR_SEARCHLINK_DSC', '[External] [mediaplayer] <br>�����С��θ���������ꤹ�� <br>�ǥե���Ȥϡ�search.longtail.tv�פǤ��� <br>�����С��򱣤��ˤϡָ����С��פ���Ѥ��� ');
    define('_WEBPHOTO_FLASHVAR_STREAMSCRIPT_DSC', "[External] [mediaplayer] <br>���ȥ꡼�ߥ��ۿ��˻��Ѥ��륹����ץȤ� URL �����ꤹ�� <br>�ѥ�᡼�����ե�����ϥ�����ץȤ���������� <br>�⤷ LigHTTPD ���ȥ꡼�ߥ� ����Ѥ���ʤ�С�'lighttpd' �����ꤹ�� . ");
    define('_WEBPHOTO_FLASHVAR_TYPE_DSC', '[External] [mediaplayer] <br>mediaplayer �ϡ�file�פκǸ�Σ�ʸ�����ĥ�Ҥ򸫤ʤ��Ƥ��� <br>�ǡ����١����� ID �� mod_rewrite �ʤɤǤ��ޤ�ư��ʤ����ˤϡ���������ĥ�Ҥ����ꤹ�뤳�� <br>�⤷�⡢�Τ��Ǥʤ��Ȥ��ϡ��ץ쥤�ꥹ�Ȥ����ɤ��줿�ȸ��ʤ���');

    // flashvar option
    define('_WEBPHOTO_FLASHVAR_LINK_TYPE_NONE', '�ʤ�');
    define('_WEBPHOTO_FLASHVAR_LINK_TYPE_SITE', 'Web������ URL');
    define('_WEBPHOTO_FLASHVAR_LINK_TYPE_PAGE', '�ܺ٥ڡ���');
    define('_WEBPHOTO_FLASHVAR_LINK_TYPE_FILE', '��ǥ������ե�����');
    define('_WEBPHOTO_FLASHVAR_LINKTREGET_SELF', 'Ʊ������ self ');
    define('_WEBPHOTO_FLASHVAR_LINKTREGET_BLANK', '���������� blank ');
    define('_WEBPHOTO_FLASHVAR_OVERSTRETCH_FALSE', 'False');
    define('_WEBPHOTO_FLASHVAR_OVERSTRETCH_FIT', 'Fit');
    define('_WEBPHOTO_FLASHVAR_OVERSTRETCH_TRUE', 'True');
    define('_WEBPHOTO_FLASHVAR_OVERSTRETCH_NONE', 'None');
    define('_WEBPHOTO_FLASHVAR_TRANSITION_OFF', '�ʤ�');
    define('_WEBPHOTO_FLASHVAR_TRANSITION_FADE', '�ե����� Fade');
    define('_WEBPHOTO_FLASHVAR_TRANSITION_SLOWFADE', '�٤��ե����� Slow Fade');
    define('_WEBPHOTO_FLASHVAR_TRANSITION_BGFADE', '�طʥե����� Background Fade');
    define('_WEBPHOTO_FLASHVAR_TRANSITION_CIRCLES', '�� Circles');
    define('_WEBPHOTO_FLASHVAR_TRANSITION_BLOCKS', '�ͳ� Blokcs');
    define('_WEBPHOTO_FLASHVAR_TRANSITION_BUBBLES', 'ˢ Bubbles');
    define('_WEBPHOTO_FLASHVAR_TRANSITION_FLASH', '�� Flash');
    define('_WEBPHOTO_FLASHVAR_TRANSITION_FLUIDS', 'ή�� Fluids');
    define('_WEBPHOTO_FLASHVAR_TRANSITION_LINES', '�� Lines');
    define('_WEBPHOTO_FLASHVAR_TRANSITION_RANDOM', '������ Random');

    // edit form
    define('_WEBPHOTO_CAP_DETAIL', '�ܺ�����');
    define('_WEBPHOTO_CAP_DETAIL_ONOFF', 'ɽ��/��ɽ��');
    define('_WEBPHOTO_PLAYER', '�ץ쥤�䡼');
    define('_WEBPHOTO_EMBED_ADD', '�ץ饰������ɲä���');
    define('_WEBPHOTO_EMBED_THUMB', '���Υץ饰����ϥ���ͥ�����󶡤���');
    define('_WEBPHOTO_ERR_EMBED', '�ץ饰��������꤬ɬ�פǤ�');
    define('_WEBPHOTO_ERR_PLAYLIST', '�ץ쥤�ꥹ�Ȥ����꤬ɬ�פǤ�');

    // sort
    define('_WEBPHOTO_SORT_VOTESA', '��ɼ�� (�㢪��)');
    define('_WEBPHOTO_SORT_VOTESD', '��ɼ�� (�⢪��)');
    define('_WEBPHOTO_SORT_VIEWSA', '������ (�㢪��)');
    define('_WEBPHOTO_SORT_VIEWSD', '������ (�⢪��)');

    // flashvar form
    //define("_WEBPHOTO_FLASHVARS_FORM","Flash�ѿ����Խ�");
    define('_WEBPHOTO_FLASHVARS_LIST', 'Flash�ѿ��ΰ���(�Ѹ�)');
    define('_WEBPHOTO_FLASHVARS_LOGO_SELECT', '������������');
    define('_WEBPHOTO_FLASHVARS_LOGO_UPLOAD', '�������Υ��åץ���');
    define('_WEBPHOTO_FLASHVARS_LOGO_DSC', '[Display] <br>�������Υǥ��쥯�ȥ� ');
    define('_WEBPHOTO_BUTTON_COLOR_PICKUP', '������');
    define('_WEBPHOTO_BUTTON_RESTORE', '�ǥե�����ͤ��᤹');

    // Playlist Cache
    define('_WEBPHOTO_PLAYLIST_STATUS_REPORT', '�������');
    define('_WEBPHOTO_PLAYLIST_STATUS_FETCHED', 'WEB Feed �ϼ������줿');
    define('_WEBPHOTO_PLAYLIST_STATUS_CREATED', '�ץ쥤�ꥹ�Ȥ���������');
    define('_WEBPHOTO_PLAYLIST_ERR_CACHE', '[ERROR] ����å��塦�ե�����������˼��Ԥ���');
    define('_WEBPHOTO_PLAYLIST_ERR_FETCH', 'WEB Feed �μ����˼��Ԥ���<br>WEB Feed ���ǧ���ơ�����å���κ������򤷤Ƥ�������');
    define('_WEBPHOTO_PLAYLIST_ERR_NODIR', '��ǥ������ǥ��쥯�ȥ��¸�ߤ��ʤ�');
    define('_WEBPHOTO_PLAYLIST_ERR_EMPTYDIR', '��ǥ������ǥ��쥯�ȥ�϶��Ǥ�');
    define('_WEBPHOTO_PLAYLIST_ERR_WRITE', '����å��塦�ե�����˽���ߤǤ��ʤ�');

    define('_WEBPHOTO_USER', '�桼��');
    define('_WEBPHOTO_OR', '���뤤��');

    //---------------------------------------------------------
    // v0.60
    //---------------------------------------------------------
    // item table
    //define("_WEBPHOTO_ITEM_ICON" , "��������̾" ) ;

    define('_WEBPHOTO_ITEM_EXTERNAL_MIDDLE', '������󥯤Υߥɥ�URL');

    // cat table
    define('_WEBPHOTO_CAT_IMG_NAME', '���ƥ������̾');

    // edit form
    define('_WEBPHOTO_CAP_MIDDLE_SELECT', '�ߥɥ����������');

    //---------------------------------------------------------
    // v0.70
    //---------------------------------------------------------
    // item table
    define('_WEBPHOTO_ITEM_CODEINFO', '������ɽ������');
    define('_WEBPHOTO_ITEM_PAGE_WIDTH', '�ڡ�������');
    define('_WEBPHOTO_ITEM_PAGE_HEIGHT', '�ڡ����⤵');
    define('_WEBPHOTO_ITEM_EMBED_TEXT', '�����');

    // item option
    define('_WEBPHOTO_ITEM_CODEINFO_CONT', '��ǥ���');
    define('_WEBPHOTO_ITEM_CODEINFO_THUMB', '����ͥ������');
    define('_WEBPHOTO_ITEM_CODEINFO_MIDDLE', '�ߥɥ����');
    define('_WEBPHOTO_ITEM_CODEINFO_FLASH', '�ե�å���ư��');
    define('_WEBPHOTO_ITEM_CODEINFO_DOCOMO', '�ɥ���ư��');
    define('_WEBPHOTO_ITEM_CODEINFO_PAGE', 'URL');
    define('_WEBPHOTO_ITEM_CODEINFO_SITE', '������');
    define('_WEBPHOTO_ITEM_CODEINFO_PLAY', '�ץ��ꥹ��');
    define('_WEBPHOTO_ITEM_CODEINFO_EMBED', '�����');
    define('_WEBPHOTO_ITEM_CODEINFO_JS', '������ץ�');

    define('_WEBPHOTO_ITEM_PLAYLIST_TIME_HOUR', '1����');
    define('_WEBPHOTO_ITEM_PLAYLIST_TIME_DAY', '1��');
    define('_WEBPHOTO_ITEM_PLAYLIST_TIME_WEEK', '1����');
    define('_WEBPHOTO_ITEM_PLAYLIST_TIME_MONTH', '1����');

    // photo
    define('_WEBPHOTO_DOWNLOAD', '���������');

    // file_read
    define('_WEBPHOTO_NO_FILE', '�ե����뤬¸�ߤ��ʤ�');

    //---------------------------------------------------------
    // v0.80
    //---------------------------------------------------------
    // item table
    define('_WEBPHOTO_ITEM_ICON_NAME', '��������̾');
    define('_WEBPHOTO_ITEM_ICON_WIDTH', '����������');
    define('_WEBPHOTO_ITEM_ICON_HEIGHT', '��������⤵');

    // item form
    define('_WEBPHOTO_DSC_SET_ITEM_TIME_UPDATE', '�����������ѹ�����');
    define('_WEBPHOTO_DSC_SET_ITEM_TIME_PUBLISH', 'ȯ�����������ꤹ��');
    define('_WEBPHOTO_DSC_SET_ITEM_TIME_EXPIRE', '��λ���������ꤹ��');

    //---------------------------------------------------------
    // v0.81
    //---------------------------------------------------------
    // vote option
    define('_WEBPHOTO_VOTE_RATING_1', '1');
    define('_WEBPHOTO_VOTE_RATING_2', '2');
    define('_WEBPHOTO_VOTE_RATING_3', '3');
    define('_WEBPHOTO_VOTE_RATING_4', '4');
    define('_WEBPHOTO_VOTE_RATING_5', '5');
    define('_WEBPHOTO_VOTE_RATING_6', '6');
    define('_WEBPHOTO_VOTE_RATING_7', '7');
    define('_WEBPHOTO_VOTE_RATING_8', '8');
    define('_WEBPHOTO_VOTE_RATING_9', '9');
    define('_WEBPHOTO_VOTE_RATING_10', '10');

    //---------------------------------------------------------
    // v0.90
    //---------------------------------------------------------
    // edit form
    define('_WEBPHOTO_GROUP_PERM_ALL', '���ƤΥ��롼��');

    //---------------------------------------------------------
    // v1.00
    //---------------------------------------------------------
    // item table
    define('_WEBPHOTO_ITEM_EDITOR', '���ǥ���');
    define('_WEBPHOTO_ITEM_DESCRIPTION_HTML', 'HTML����');
    define('_WEBPHOTO_ITEM_DESCRIPTION_SMILEY', '���ޥ��꡼��������');
    define('_WEBPHOTO_ITEM_DESCRIPTION_XCODE', 'XOOPS������');
    define('_WEBPHOTO_ITEM_DESCRIPTION_IMAGE', '����');
    define('_WEBPHOTO_ITEM_DESCRIPTION_BR', '����');

    // edit form
    define('_WEBPHOTO_TITLE_EDITOR_SELECT', '���ǥ��������򤹤�');
    define('_WEBPHOTO_CAP_DESCRIPTION_OPTION', '���ץ����');
    define('_WEBPHOTO_CAP_HTML', 'HTML������ͭ���ˤ���');
    define('_WEBPHOTO_CAP_SMILEY', '���ޥ��꡼���������ͭ���ˤ���');
    define('_WEBPHOTO_CAP_XCODE', 'XOOPS�����ɤ�ͭ���ˤ���');
    define('_WEBPHOTO_CAP_IMAGE', '������ͭ���ˤ���');
    define('_WEBPHOTO_CAP_BR', '���Ԥ�ͭ���ˤ���');

    //---------------------------------------------------------
    // v1.10
    //---------------------------------------------------------
    // item table
    define('_WEBPHOTO_ITEM_WIDTH', '�����β���');
    define('_WEBPHOTO_ITEM_HEIGHT', '�����ι⤵');
    define('_WEBPHOTO_ITEM_CONTENT', '�ƥ�����ʸ');

    //---------------------------------------------------------
    // v1.20
    //---------------------------------------------------------
    // item option
    define('_WEBPHOTO_ITEM_CODEINFO_PDF', 'PDF');
    define('_WEBPHOTO_ITEM_CODEINFO_SWF', '�ե�å���');

    // form
    define('_WEBPHOTO_ERR_PDF', 'PDF �������Ǥ��ʤ��ä�');
    define('_WEBPHOTO_ERR_SWF', '�ե�å��� �������Ǥ��ʤ��ä�');

    // jodconverter
    define('_WEBPHOTO_JODCONVERTER_JUNK_WORDS', '�ǽ�Υڡ���|�Ǹ�Υڡ���|�ڡ���|���|����|�ƥ�����|��|�ܼ�|ɽ');

    //---------------------------------------------------------
    // v1.30
    //---------------------------------------------------------
    define('_WEBPHOTO_TITLE_MAP', '�ޥå�ɽ��');
    define('_WEBPHOTO_MAP_LARGE', '�礭�ʥޥåפ�ɽ������');

    // timeline
    define('_WEBPHOTO_TITLE_TIMELINE', '������饤��ɽ��');
    define('_WEBPHOTO_TIMELINE_ON', '������饤���ɽ������');
    define('_WEBPHOTO_TIMELINE_OFF', '������饤���ɽ�����ʤ�');
    define('_WEBPHOTO_TIMELINE_SCALE_WEEK', '������');
    define('_WEBPHOTO_TIMELINE_SCALE_MONTH', '������');
    define('_WEBPHOTO_TIMELINE_SCALE_YEAR', '��ǯ');
    define('_WEBPHOTO_TIMELINE_SCALE_DECADE', '����ǯ');
    define('_WEBPHOTO_TIMELINE_LARGE', '�礭�ʥ�����饤���ɽ������');
    define('_WEBPHOTO_TIMELINE_CAUTION_IE', 'InternetExpolar �Ǥ�ɽ���Ǥ��ʤ����Ȥ�����ޤ��� Firfox, Opera, Safari �ʤ�¾�Υ֥饦���ˤƤ������������');

    // item option
    define('_WEBPHOTO_ITEM_CODEINFO_SMALL', '���⡼�����');

    // edit form
    define('_WEBPHOTO_CAP_SMALL_SELECT', '���⡼�����������');

    //---------------------------------------------------------
    // v1.60
    //---------------------------------------------------------
    define('_WEBPHOTO_TITLE_SUBMIT_SELECT', '��Ͽ���������ؤ�');
    define('_WEBPHOTO_TITLE_SUBMIT_SINGLE', '�������Ͽ');
    define('_WEBPHOTO_TITLE_SUBMIT_BULK', 'ʣ����ΰ����Ͽ');

    //---------------------------------------------------------
    // v1.80
    //---------------------------------------------------------
    // item option
    define('_WEBPHOTO_ITEM_CODEINFO_JPEG', 'JPEG����');
    define('_WEBPHOTO_ITEM_CODEINFO_MP3', 'MP3');

    // form
    define('_WEBPHOTO_ERR_JPEG', 'JPEG �������Ǥ��ʤ��ä�');
    define('_WEBPHOTO_ERR_MP3', 'MP3 �������Ǥ��ʤ��ä�');

    // mime option
    define('_WEBPHOTO_MIME_KIND_GENERAL', '����');
    define('_WEBPHOTO_MIME_KIND_IMAGE', '����');
    define('_WEBPHOTO_MIME_KIND_IMAGE_CONVERT', '���� convert');
    define('_WEBPHOTO_MIME_KIND_VIDEO', '�ӥǥ�');
    define('_WEBPHOTO_MIME_KIND_VIDEO_FFMPEG', '�ӥǥ� ffmpeg');
    define('_WEBPHOTO_MIME_KIND_AUDIO', '�����ǥ���');
    define('_WEBPHOTO_MIME_KIND_AUDIO_MID', '�����ǥ��� midi');
    define('_WEBPHOTO_MIME_KIND_AUDIO_WAV', '�����ǥ��� wav');
    define('_WEBPHOTO_MIME_KIND_OFFICE', '���ե���');

    // player option
    define('_WEBPHOTO_PLAYER_TITLE_DEFAULT', '̤���');

    define('_WEBPHOTO_TITLE_IMAGE', '�Ż߲�');
    define('_WEBPHOTO_TITLE_VIDEO', 'ư��');
    define('_WEBPHOTO_TITLE_MUSIC', '����');
    define('_WEBPHOTO_TITLE_OFFICE', '���ե���');

    //---------------------------------------------------------
    // v1.90
    //---------------------------------------------------------
    // menu
    define('_WEBPHOTO_TITLE_PICTURE', '�Ż߲�');
    define('_WEBPHOTO_TITLE_AUDIO', '����');

    // item
    define('_WEBPHOTO_ITEM_DETAIL_ONCLICK', '�ܺ٤򥯥�å������Ȥ���ư��');
    define('_WEBPHOTO_ITEM_WEIGHT', '�¤ӽ�');

    // item option
    define('_WEBPHOTO_ITEM_KIND_OFFICE', '���ե��� (doc,xls,ppt...)');
    define('_WEBPHOTO_ITEM_KIND_IMAGE_OTHER', '���� (bmp,tif,wmf...)');
    define('_WEBPHOTO_ITEM_DETAIL_ONCLICK_DEFAULT', 'ɸ�� (����ƥ��)');

    // file
    define('_WEBPHOTO_FILE_KIND_CONT', '����ƥ��');
    define('_WEBPHOTO_FILE_KIND_THUMB', '����ͥ���');
    define('_WEBPHOTO_FILE_KIND_MIDDLE', '�ߥɥ�');
    define('_WEBPHOTO_FILE_KIND_FLASH', 'Flash �ӥǥ�');
    define('_WEBPHOTO_FILE_KIND_DOCOMO', '�ɥ��� �ӥǥ�');
    define('_WEBPHOTO_FILE_KIND_PDF', 'PDF');
    define('_WEBPHOTO_FILE_KIND_SWF', 'Flash swf');
    define('_WEBPHOTO_FILE_KIND_SMALL', '���⡼��');
    define('_WEBPHOTO_FILE_KIND_JPEG', 'JPEG');
    define('_WEBPHOTO_FILE_KIND_MP3', 'MP3');

    // mime option
    define('_WEBPHOTO_MIME_KIND_OFFICE_DOC', '���ե��� doc');
    define('_WEBPHOTO_MIME_KIND_OFFICE_XLS', '���ե��� xls');
    define('_WEBPHOTO_MIME_KIND_OFFICE_PPT', '���ե��� ppt');
    define('_WEBPHOTO_MIME_KIND_OFFICE_PDF', '���ե��� pdf');

    // submit
    define('_WEBPHOTO_UPLOADING', '���åץ�����...');
    define('_WEBPHOTO_EMBED_ENTER', 'URL����Υӥǥ�ID�����Ϥ��Ƥ�������');
    define('_WEBPHOTO_EMBED_EXAMPLE', '��:');

    // photo
    define('_WEBPHOTO_ICON_GROUP', '���롼�������');

    //---------------------------------------------------------
    // v2.00
    //---------------------------------------------------------
    // item
    define('_WEBPHOTO_ITEM_PERM_LEVEL', '������٥�');
    define('_WEBPHOTO_ITEM_PERM_LEVEL_PUBLIC', '���̸���');
    define('_WEBPHOTO_ITEM_PERM_LEVEL_GROUP', '���롼����');

    // cat
    define('_WEBPHOTO_CAT_GROUP_ID', '�桼�����롼��ID');

    //---------------------------------------------------------
    // v2.10
    //---------------------------------------------------------
    // item
    define('_WEBPHOTO_ITEM_DESCRIPTION_SCROLL', '�̿���ư�衦��ǥ���������ʸ�Υ�������ɽ��');
    define('_WEBPHOTO_ITEM_DESCRIPTION_SCROLL_DSC', '��������ι⤵�� pxñ�̤ǻ��ꤹ�� <br>0 �ΤȤ��ϥ�������ʤ����̾��ɽ��');

    // item option
    define('_WEBPHOTO_ITEM_DETAIL_ONCLICK_IMAGE', '�̿���ư�衦��ǥ������̲��̤�ɽ������');
    define('_WEBPHOTO_ITEM_DETAIL_ONCLICK_LIGHTBOX', '�̿���ư�衦��ǥ����� lightbox ��ɽ������');

    // submit
    define('_WEBPHOTO_MAIL_SUBMIT_WAITING', '��ǧ�Ԥ��μ̿���ư�衦��ǥ�������Ƥ���ޤ���');
    define('_WEBPHOTO_MAIL_SUBMIT_APPROVE', '�̿���ư�衦��ǥ�������ǧ����ޤ���');
    define('_WEBPHOTO_MAIL_SUBMIT_REFUSE', '�̿���ư�衦��ǥ��������ݤ���ޤ���');

    // edit
    define('_WEBPHOTO_LOOK_PHOTO', '�̿���ư�衦��ǥ����򸫤�');

    //---------------------------------------------------------
    // v2.11
    //---------------------------------------------------------
    // submit
    define('_WEBPHOTO_ITEM_KIND_GROUP_UNDEFINED', '̤���');
    define('_WEBPHOTO_ITEM_KIND_GROUP_IMAGE', '�Ż߲�');
    define('_WEBPHOTO_ITEM_KIND_GROUP_VIDEO', 'ư��');
    define('_WEBPHOTO_ITEM_KIND_GROUP_AUDIO', '����');
    define('_WEBPHOTO_ITEM_KIND_GROUP_OFFICE', '���ե���');
    define('_WEBPHOTO_ITEM_KIND_GROUP_OTHERS', '����¾');

    define('_WEBPHOTO_CONFIRM_PHOTODEL_DSC', '����ʸ�䥳���Ȥʤɴ�Ϣ�������Ƥξ��󤬺������ޤ�');

    // search
    define('_WEBPHOTO_SEARCH_KEYTOOSHORT', '������ɤ�Ⱦ�� %s ʸ���ʾ�ǻ��ꤷ�Ƥ�������');

    //---------------------------------------------------------
    // v2.20
    //---------------------------------------------------------
    // edit form
    define('_WEBPHOTO_EMBED_SUPPORT_TITLE', '���Υץ饰����ϥ����ȥ��������ޤ�');
    define('_WEBPHOTO_EMBED_SUPPORT_DESCRIPTION', '���Υץ饰���������ʸ��������ޤ�');
    define('_WEBPHOTO_EMBED_SUPPORT_SITEURL', '���Υץ饰����ϥ�����URL��������ޤ�');
    define('_WEBPHOTO_EMBED_SUPPORT_DURATION', '���Υץ饰����ϥӥǥ��������֤�������ޤ�');
    define('_WEBPHOTO_EMBED_SUPPORT_EMBED_TEXT', '���Υץ饰���������ߤ�������ޤ�');
    define('_WEBPHOTO_EMBED_SUPPORT_TAGS', '���Υץ饰����ϥ�����������ޤ�');

    //---------------------------------------------------------
    // v2.30
    //---------------------------------------------------------
    // item table
    define('_WEBPHOTO_ITEM_DISPLAYFILE', 'ɽ���ե�����');
    define('_WEBPHOTO_ITEM_FILE_ID_11', '�ե������ֹ桧WAV');
    define('_WEBPHOTO_ITEM_FILE_ID_12', '�ե������ֹ桧�顼��');
    define('_WEBPHOTO_ITEM_FILE_ID_13', '�ե������ֹ桧13');
    define('_WEBPHOTO_ITEM_FILE_ID_14', '�ե������ֹ桧14');
    define('_WEBPHOTO_ITEM_FILE_ID_15', '�ե������ֹ桧15');
    define('_WEBPHOTO_ITEM_FILE_ID_16', '�ե������ֹ桧16');
    define('_WEBPHOTO_ITEM_FILE_ID_17', '�ե������ֹ桧17');
    define('_WEBPHOTO_ITEM_FILE_ID_18', '�ե������ֹ桧18');
    define('_WEBPHOTO_ITEM_FILE_ID_19', '�ե������ֹ桧19');
    define('_WEBPHOTO_ITEM_FILE_ID_20', '�ե������ֹ桧20');

    // file
    define('_WEBPHOTO_FILE_KIND_11', 'WAVE');
    define('_WEBPHOTO_FILE_KIND_12', '�顼��');
    define('_WEBPHOTO_FILE_KIND_13', '�ե�������̡� 13');
    define('_WEBPHOTO_FILE_KIND_14', '�ե�������̡� 14');
    define('_WEBPHOTO_FILE_KIND_15', '�ե�������̡� 15');
    define('_WEBPHOTO_FILE_KIND_16', '�ե�������̡� 16');
    define('_WEBPHOTO_FILE_KIND_17', '�ե�������̡� 17');
    define('_WEBPHOTO_FILE_KIND_18', '�ե�������̡� 18');
    define('_WEBPHOTO_FILE_KIND_19', '�ե�������̡� 19');
    define('_WEBPHOTO_FILE_KIND_20', '�ե�������̡� 20');

    // item kind
    define('_WEBPHOTO_ITEM_KIND_IMAGE_CMYK', '���� (cmyk)');
    define('_WEBPHOTO_ITEM_KIND_VIDEO_H264', 'ư�� (H264)');

    // item detail
    define('_WEBPHOTO_ITEM_DETAIL_ONCLICK_DOWNLOAD', '��������ɤ���');

    // item codeinfo
    define('_WEBPHOTO_ITEM_CODEINFO_LARGE', '�顼������');
    define('_WEBPHOTO_ITEM_CODEINFO_WAV', 'WAVE');

    // item display file
    define('_WEBPHOTO_ITEM_DISPLAYFILE_DEFAULT', 'ɸ��');

    // photo form
    define('_WEBPHOTO_CAP_JPEG_SELECT', 'JPEG����������');
    define('_WEBPHOTO_FILE_JPEG_DSC', '����ͥ���������������ޤ�');
    define('_WEBPHOTO_FILE_JPEG_DELETE_DSC', '����ͥ�������������ޤ�');

    // mime option
    define('_WEBPHOTO_MIME_KIND_IMAGE_JPEG', '���� jpg');
    define('_WEBPHOTO_MIME_KIND_VIDEO_FLV', '�ӥǥ� flv');
    define('_WEBPHOTO_MIME_KIND_AUDIO_MP3', '�����ǥ��� mp3');
    define('_WEBPHOTO_MIME_KIND_AUDIO_FFMPEG', '�����ǥ��� ffmpeg');

    // error
    define('_WEBPHOTO_ERR_WAV', 'wav �������Ǥ��ʤ��ä�');

    //---------------------------------------------------------
    // v2.40
    //---------------------------------------------------------
    // === ���褫��������Ƥ�����Τ��������� ===
    define('_WEBPHOTO_FLASHVARS_FORM', 'Flash Player �Υ��ץ������Խ�');

    // --- flashvar ---
    // common
    define('_WEBPHOTO_FLASHVAR_HEIGHT_DSC', '[Basics] height ');
    define('_WEBPHOTO_FLASHVAR_WIDTH_DSC', '[Basics] width ');

    define('_WEBPHOTO_FLASHVAR_SCREENCOLOR_DSC', '[Colors] screencolor <br>imagerotator �ΤȤ��� <br>�ۤʤ��礭���β��������ߤ��Ƥ⡢���ʤ���HTML�ڡ����˹礦�褦���ѹ�����');
    define('_WEBPHOTO_FLASHVAR_BACKCOLOR_DSC', '[Colors] backcolor <br>����ȥ�����طʿ�');
    define('_WEBPHOTO_FLASHVAR_FRONTCOLOR_DSC', '[Colors] frontcolor <br>����ȥ���Υƥ����Ȥȥܥ���ο�');
    define('_WEBPHOTO_FLASHVAR_LIGHTCOLOR_DSC', '[Colors] lightcolor <br>����ȥ���Υޥ��������Ф����Ȥ��ο�');

    define('_WEBPHOTO_FLASHVAR_SHUFFLE_DSC', '[Behaviour] shuffle <br>�֤Ϥ��פΤȤ����ץ쥤�ꥹ�Ȥ������˺������� ');
    define('_WEBPHOTO_FLASHVAR_VOLUME_DSC', '[Behaviour] volume <br>���ڤ�ư��β��̤����ꤹ��');

    // player
    define('_WEBPHOTO_FLASHVAR_IMAGE_DSC', '[Playlist] image <br>�⤷���ڤ�ư����������ʤ�С��ץ�ӥ塼������ URL �����ꤹ�� <br>�ץ쥤�ꥹ�ȤΤȤ��ϡ�����ȥ��������Ǥ���');

    define('_WEBPHOTO_FLASHVAR_AUTOSTART_DSC', '[Behaviour] autostart <br>true �ΤȤ��ϡ��ڡ�������ɤ����Ȥ��˼�ưŪ�˥������Ȥ���');
    define('_WEBPHOTO_FLASHVAR_BUFFERLENGTH_DSC', '[Behaviour] bufferlength <br>�����������ˡ�ư���Хåե������ÿ������ꤹ��<br>��®���̿��Ķ���û��ư��ΤȤ��Ͼ������ͤ����ꤹ��<br>��®���̿��Ķ��ΤȤ����礭���ͤ����ꤹ�� ');
    define('_WEBPHOTO_FLASHVAR_SMOOTHING_DSC', '[Behaviour] smoothing <br>�֤������פΤȤ��������Υ��ࡼ���󥰤�Ԥ�ʤ� <br>���������뤬����ǽ�ϸ��夹�� <br>HD �ե�������٤� PC �˺�Ŭ ');

    // imagetotator
    define('_WEBPHOTO_FLASHVAR_LOGO_DSC', '[Appearance] logo <br>�����꡼��α����ɽ����������������ꤹ��<br>Ʃ�� PNG ����Ŭ');
    define('_WEBPHOTO_FLASHVAR_OVERSTRETCH_DSC', '[Appearance] overstretch <br>����/ư��򥹥��꡼����礭���˳�ĥ������ˡ�����ꤹ��<br>false (�ǥե����) = �����꡼��˰��פ���<br>true = �Ѱ�˳�ĥ����<br>fit = �ԶѰ�˳�ĥ����<br>none = �����礭�����ݻ�����');
    define('_WEBPHOTO_FLASHVAR_SHOWICONS_DSC', '[Appearance] showicons <br>�����꡼�������˥ץ쥤�ܥ����ɽ������');
    define('_WEBPHOTO_FLASHVAR_SHOWNAVIGATION_DSC', '[Appearance] shownavigation <br>����ȥ��롦�С���ɽ������');
    define('_WEBPHOTO_FLASHVAR_TRANSITION_DSC', '[Appearance] transition <br>���������ؤ����̤����ꤹ�� ');
    define('_WEBPHOTO_FLASHVAR_USEFULLSCREEN_DSC', '[Appearance] usefullscreen <br>�ե륹���꡼�󡦥ܥ����ɽ������');

    define('_WEBPHOTO_FLASHVAR_AUDIO_DSC', '[Behaviour] audio <br>Ʊ������ MP3 �������Ƥ�<br>mediaplayer �β��������ޤ��ϥǥ��쥯�����Υ����ȡ����뤤�� imagerotator ���طʲ��ڤȤ��ơ����Ѥ���<br>mediaplayer �ȥץ쥤�ꥹ�ȤΤȤ��ϡ�����ȥ���˥����ǥ���������Ǥ��� ');
    define('_WEBPHOTO_FLASHVAR_LINKFROMDISPLAY_DSC', '[Behaviour] linkfromdisplay <br>�֤Ϥ��פΤȤ��ϡ������꡼��򥯥�å�����ȡ��ץ쥤�ꥹ�Ȥ����ꤵ�줿 WEB �ڡ����Υ����פ��� ');
    define('_WEBPHOTO_FLASHVAR_LINKTARGET_DSC', '[Behaviour] linktarget <br>��󥯤���������ɥ����̤μ���');
    define('_WEBPHOTO_FLASHVAR_REPEAT_DSC', '[Behaviour] repeat <br>true �ΤȤ������ƤΥե�������֤��������� <br>�ץ쥤�ꥹ�ȤΤȤ��� ���٤����������� ');
    define('_WEBPHOTO_FLASHVAR_ROTATETIME_DSC', '[Behaviour] rotatetime <br>���������ؤ�Ȥ����ÿ������ꤹ�� ');

    // === �����ɲ� ===
    define('_WEBPHOTO_FLASHVARS_ADD', 'Flash Player �Υ��ץ������ɲä���');

    // --- flashvar �ơ��֥� ---
    // Playlist Properties
    define('_WEBPHOTO_FLASHVAR_PLAYLISTFILE', '�ץ쥤�ꥹ�ȡ��ե�����');
    define('_WEBPHOTO_FLASHVAR_START', '���ϻ���');
    define('_WEBPHOTO_FLASHVAR_DURATION', '��������');
    define('_WEBPHOTO_FLASHVAR_MEDIAID', '��ǥ���ID');
    define('_WEBPHOTO_FLASHVAR_PROVIDER', '�ץ�Х�����');
    define('_WEBPHOTO_FLASHVAR_STREAMER', '���ȥ꡼�ޡ�');
    define('_WEBPHOTO_FLASHVAR_NETSTREAMBASEPATH', '�ͥåȥ��ȥ꡼��Υ١����ѥ�');

    // Layout
    define('_WEBPHOTO_FLASHVAR_DOCK', '�ץ饰���󡦥ܥ����ɽ��');
    define('_WEBPHOTO_FLASHVAR_ICONS', '����ȥ��롦���������ɽ��');
    define('_WEBPHOTO_FLASHVAR_SKIN', '������');
    define('_WEBPHOTO_FLASHVAR_CONTROLBAR_POSITION', '����ȥ���С��ΰ���');
    define('_WEBPHOTO_FLASHVAR_CONTROLBAR_IDLEHIDE', '����ȥ���С�����߻��˱���');
    define('_WEBPHOTO_FLASHVAR_DISPLAY_SHOWMUTE', '�ߥ塼�ȡ����������ɽ��');
    define('_WEBPHOTO_FLASHVAR_PLAYLIST_POSITION', '�ץ쥤�ꥹ�Ȥΰ���');
    define('_WEBPHOTO_FLASHVAR_PLAYLIST_SIZE', '�ץ쥤�ꥹ�Ȥ��礭��');

    // Behavior
    define('_WEBPHOTO_FLASHVAR_PLAYER_REPEAT', '�����֤�');
    define('_WEBPHOTO_FLASHVAR_ITEM', '�����ƥ�');
    define('_WEBPHOTO_FLASHVAR_MUTE', '�ߥ塼��');
    define('_WEBPHOTO_FLASHVAR_PLAYERREADY', 'playerReady()');
    define('_WEBPHOTO_FLASHVAR_PLUGINS', '�ץ饰����');
    define('_WEBPHOTO_FLASHVAR_STRETCHING', '����/ư��ο�ĥ');

    // Logo
    define('_WEBPHOTO_FLASHVAR_LOGO_FILE', '���Υե�����');
    define('_WEBPHOTO_FLASHVAR_LOGO_LINK', '���Υ��');
    define('_WEBPHOTO_FLASHVAR_LOGO_LINKTARGET', '���Υ�󥯥������å�');
    define('_WEBPHOTO_FLASHVAR_LOGO_HIDE', '���򱣤�');
    define('_WEBPHOTO_FLASHVAR_LOGO_MARGIN', '����;��');
    define('_WEBPHOTO_FLASHVAR_LOGO_POSITION', '���ΰ���');
    define('_WEBPHOTO_FLASHVAR_LOGO_TIMEOUT', '���Υ����ॢ����');
    define('_WEBPHOTO_FLASHVAR_LOGO_OVER', '���Υޥ��������С�����Ʃ����');
    define('_WEBPHOTO_FLASHVAR_LOGO_OUT', '�����̾��Ʃ����');

    // --- description ---
    define('_WEBPHOTO_FLASHVAR_START_DSC', '[Playlist] start <br>�����򳫻Ϥ������(��)');
    define('_WEBPHOTO_FLASHVAR_DURATION_DSC', '[Playlist] duration <br>�����������(��)');
    define('_WEBPHOTO_FLASHVAR_PLAYLISTFILE_DSC', '[Playlist] playlistfile <br>�ץ쥤�ꥹ�Ȥ�XML�ե������URL');
    define('_WEBPHOTO_FLASHVAR_MEDIAID_DSC', '[Playlist] mediaid <br>��ǥ����ե�������̤����ˡ�����ʸ����<br>�����Υץ饰����ǻ��Ѥ���ޤ���<br>�ץ졼�䡼���Ȥϻ��Ѥ��ޤ���');
    define('_WEBPHOTO_FLASHVAR_PROVIDER_DSC', '[Playlist] provider <br>��ǥ����η��������ꤹ�롣<br>�ǥե���ȤǤϡ��ե������ĥ�Ҥ˴�Ť���ưŪ�˸��Ф���ޤ���<br>��ĥ�Ҥ������ʤ���С���ư�Ǥ�������ꤷ�ޤ���');
    define('_WEBPHOTO_FLASHVAR_STREAMER_DSC', '[Playlist] streamer ');
    define('_WEBPHOTO_FLASHVAR_NETSTREAMBASEPATH_DSC', '[Playlist] netstreambasepath ');

    define('_WEBPHOTO_FLASHVAR_DOCK_DSC', '[Layout] dock <br>�ǥե���ȤǤϡ��֤Ϥ��פǡ��ץ饰����ܥ���ϥǥ��ץ쥤��ɽ������롣<br>�֤������פΤȤ��ϡ�����ȥ���С���ɽ������롣');
    define('_WEBPHOTO_FLASHVAR_ICONS_DSC', '[Layout] icons <br>�֤������פΤȤ����ǥ����ץ쥤�Υץ쥤�ܥ����ɽ������ʤ���');
    define('_WEBPHOTO_FLASHVAR_SKIN_DSC', '[Layout] skin <br>�ץ졼�䡼�γ��Ѥ��Ѥ��륹���󡦥ե������URL��');
    define('_WEBPHOTO_FLASHVAR_CONTROLBAR_POSITION_DSC', '[Layout] controlbar.position <br>bottom, top, ���뤤�� none');
    define('_WEBPHOTO_FLASHVAR_CONTROLBAR_IDLEHIDE_DSC', '[Layout] controlbar.idlehide <br>�֤Ϥ��פΤȤ��ϡ��ץ졼�䡼���ݡ�������뤫���ޤ��ϻߤ����Ȥ�������ȥ���С���ɽ������ʤ���');
    define('_WEBPHOTO_FLASHVAR_DISPLAY_SHOWMUTE_DSC', '[Layout] display.showmute <br>�֤Ϥ��פΤȤ����ǥ����ץ쥤��������ɥ��˥ߥ塼�ȡ����������ɽ�����ޤ���<br>�ǥե���Ȥϡ֤������פǤ���');
    define('_WEBPHOTO_FLASHVAR_PLAYLIST_POSITION_DSC', '[Layout] playlist.position <br>�ץ쥤�ꥹ�Ȥ�ɽ��������֤���ꤷ�ޤ���<br>bottom, top, right, left, over or none.<br>�ǥե���Ȥ� ɽ�����ʤ� none �Ǥ���');
    define('_WEBPHOTO_FLASHVAR_PLAYLIST_SIZE_DSC', '[Layout]  playlist.size <br>�ץ쥤�ꥹ�Ȥ�ǥ����ץ쥤�β��˻��ꤷ���Ȥ��ϡ��⤵���Ѥ���Τˤ��Υ��ץ�������ѤǤ��ޤ��� <br>�ץ쥤�ꥹ�Ȥ��ǥ����ץ쥤�κ��ޤ����˻��ꤷ���Ȥ��ϡ����Υ��ץ����ϡ�����ɽ���ޤ���<br>����¾�ΤȤ��ϡ����Υ��ץ�����ɬ�פǤϤ���ޤ���');

    define('_WEBPHOTO_FLASHVAR_PLAYER_REPEAT_DSC', '[Behaviour] repeat <br>none �ץ쥤�ꥹ�Ȥκǽ�Υե�����<br>list �ץ쥤�ꥹ�Ȥ�ǽ餫��Ǹ�ޤ�<br>always �ץ쥤�ꥹ�Ȥη����֤�<br>single �ץ쥤�ꥹ�Ȥ�Ʊ���ե�����η����֤�');
    define('_WEBPHOTO_FLASHVAR_ITEM_DSC', '[Behaviour] item <br>���Ϥ���ץ쥤�ꥹ�Ȥι����ֹ档<br>�������Ѥ���ȡ��ǽ�ι��ܤ����������ι��ܤ��鳫�ϤǤ��ޤ���');
    define('_WEBPHOTO_FLASHVAR_MUTE_DSC', '[Behaviour] mute <br>���Ϥ���Ȥ��˲���ä���<br>���å����������¸����ޤ���');
    define('_WEBPHOTO_FLASHVAR_PLAYERREADY_DSC', '[Behaviour] playerready <br>���줬����������Ȥ����ǥե���ȤǤϡ��ץ졼�䡼�Ͻ���������Ȥ��ˡ�JavaScript �� playerReady() ��ƤӤޤ���<br>��������줿��ˡ��ץ졼�䡼�˰ۤʤä���ǽ��ƤФ���Τˡ����Υ��ץ����ϻ��Ѥ���ޤ���');
    define('_WEBPHOTO_FLASHVAR_PLUGINS_DSC', '[Behaviour] plugins <br>����ϥ��ɤ���ץ饰����Υ���ޤ��ڤ�Υ���줿�ꥹ�ȤǤ���<br>�ץ졼�䡼�ε�ǽ���򹭤����̡��� JavaScript �� SWF �ե�����Ǥ���<br>addons ��ݥ��ȥ��ˬ�䤷�ơ����Ѳ�ǽ�ʥץ饰����򸫤Ƥ���������');
    define(
        '_WEBPHOTO_FLASHVAR_STRETCHING_DSC',
        '[Behaviour] stretching <br>����/ư��򥹥��꡼����礭���˳�ĥ������ˡ�����ꤹ��<br>none: �������ݤ��ޤ���<br>exactfit: ������˥ӥǥ�/���᡼���򿭤Ф��ơ��ǥ����ץ쥤�ˤԤä����碌�롣<br>uniform: �����ڥ��ȥ쥷����ݻ����ơ����᡼��/�ӥǥ�����礷�ޤ�������������ɽ������ޤ���<br>fill: �����ڥ��ȥ쥷����ݻ����ơ����᡼��/�ӥǥ�����礷�ޤ����ǥ����ץ쥤���äѤ��˳��礷�ޤ��� '
    );

    define('_WEBPHOTO_FLASHVAR_LOGO_FILE_DSC', '[Logo] logo.file <br>');
    define('_WEBPHOTO_FLASHVAR_LOGO_LINK_DSC', '[Logo] logo.link <br>���򥯥�å������Ȥ��Υ��������ꤹ�롣<br>���ꤵ��Ƥ��ʤ��Ȥ��ϡ����⤷�ʤ���');
    define('_WEBPHOTO_FLASHVAR_LOGO_LINKTARGET_DSC', '[Logo] logo.linktarget <br>���򥯥�å������Ȥ��β��̤����ꤹ�롣<br>_self, _blank, _parent, _top ���뤤�� �ե졼��̾');
    define('_WEBPHOTO_FLASHVAR_LOGO_HIDE_DSC', '[Logo] logo.hide <br>�ǥե���Ȥϡ֤Ϥ��פǡ����ϼ�ưŪ��ɽ�����졢3�ø�˾ä��ޤ���<br>�֤������פΤȤ��ϡ��ä��ޤ���');
    define('_WEBPHOTO_FLASHVAR_LOGO_MARGIN_DSC', '[Logo] logo.margin <br>�ץ졼�䡼�γ��Ȥ����Υ��<br>pixel ñ�̡�');
    define('_WEBPHOTO_FLASHVAR_LOGO_POSITION_DSC', '[Logo] logo.position <br>����ɽ������Ͷ�����ꤷ�ޤ���<br>bottom-left, bottom-right, top-left, top-right.<br>�ǥե���ȤϺ��� bottom-left �Ǥ���');
    define('_WEBPHOTO_FLASHVAR_LOGO_TIMEOUT_DSC', '[Logo] logo.timeout <br>logo.hide ���֤Ϥ��פΤȤ��ˡ������ä���ޤǤλ��� (��)��');
    define('_WEBPHOTO_FLASHVAR_LOGO_OVER_DSC', '[Logo] logo.over <br>�ޥ��������С�����Ʃ���̡�<br>�����飱�ޤǤο��͡�');
    define('_WEBPHOTO_FLASHVAR_LOGO_OUT_DSC', '[Logo] logo.out <br>�ޥ��������줿�Ȥ���Ʃ���̡�<br>�����飱�ޤǤο��͡�');

    // --- options ---
    define('_WEBPHOTO_FLASHVAR_PLAYER_REPEAT_NONE', 'none �ץ쥤�ꥹ�Ȥκǽ�Υե�����');
    define('_WEBPHOTO_FLASHVAR_PLAYER_REPEAT_LIST', 'list �ץ쥤�ꥹ�Ȥ�ǽ餫��Ǹ�ޤ�');
    define('_WEBPHOTO_FLASHVAR_PLAYER_REPEAT_ALWAYS', 'always �ץ쥤�ꥹ�Ȥη����֤�');
    define('_WEBPHOTO_FLASHVAR_PLAYER_REPEAT_SINGLE', 'single �ץ쥤�ꥹ�Ȥ�Ʊ���ե�����η����֤�');

    define('_WEBPHOTO_FLASHVAR_STRETCHING_NONE', 'none');
    define('_WEBPHOTO_FLASHVAR_STRETCHING_EXACTFIT', 'exactfit');
    define('_WEBPHOTO_FLASHVAR_STRETCHING_UNIFORM', 'uniform');
    define('_WEBPHOTO_FLASHVAR_STRETCHING_FILL', 'fill');

    define('_WEBPHOTO_FLASHVAR_CONTROLBAR_POSITION_BOTTOM', '��   bottom');
    define('_WEBPHOTO_FLASHVAR_CONTROLBAR_POSITION_TOP', '��   top');
    define('_WEBPHOTO_FLASHVAR_CONTROLBAR_POSITION_OVER', '��� over');
    define('_WEBPHOTO_FLASHVAR_CONTROLBAR_POSITION_NONE', '�ʤ� none');

    define('_WEBPHOTO_FLASHVAR_PLAYLIST_POSITION_BOTTOM', '���� bottom');
    define('_WEBPHOTO_FLASHVAR_PLAYLIST_POSITION_TOP', '�塡 top');
    define('_WEBPHOTO_FLASHVAR_PLAYLIST_POSITION_RIGHT', '���� right');
    define('_WEBPHOTO_FLASHVAR_PLAYLIST_POSITION_LEFT', '���� left');
    define('_WEBPHOTO_FLASHVAR_PLAYLIST_POSITION_OVER', '��� over');
    define('_WEBPHOTO_FLASHVAR_PLAYLIST_POSITION_NONE', '�ʤ� none');

    define('_WEBPHOTO_FLASHVAR_LOGO_POSITION_BOTTOM_LEFT', '���� bottom-left');
    define('_WEBPHOTO_FLASHVAR_LOGO_POSITION_BOTTOM_RIGHT', '���� bottom-right');
    define('_WEBPHOTO_FLASHVAR_LOGO_POSITION_TOP_LEFT', '���� top-left');
    define('_WEBPHOTO_FLASHVAR_LOGO_POSITION_TOP_RIGHT', '���� top-right');

    //---------------------------------------------------------
    // v2.60
    //---------------------------------------------------------
    // cat handler
    define('_WEBPHOTO_CAT_TIMELINE_MODE', '������饤��Υ⡼��');
    define('_WEBPHOTO_CAT_TIMELINE_SCALE', '������饤��λ�����');

    // === define end ===
}
