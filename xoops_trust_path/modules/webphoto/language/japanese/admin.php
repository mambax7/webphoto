<?php
// $Id: admin.php,v 1.25 2011/05/10 02:56:39 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-10-01 K.OHWADA
// �̿� -> �̿���ư�衦��ǥ���
//---------------------------------------------------------

// === define begin ===
if (!defined('_AM_WEBPHOTO_LANG_LOADED')) {
    define('_AM_WEBPHOTO_LANG_LOADED', 1);

    //=========================================================
    // base on myalbum
    //=========================================================

    // menu
    define('_AM_WEBPHOTO_MYMENU_TPLSADMIN', '�ƥ�ץ졼�ȴ���');
    define('_AM_WEBPHOTO_MYMENU_BLOCKSADMIN', '�֥�å�����/������������');

    //define("_AM_WEBPHOTO_MYMENU_MYPREFERENCES","��������");

    // add for webphoto
    define('_AM_WEBPHOTO_MYMENU_GOTO_MODULE', '�⥸�塼���');

    // Index (Categories)
    define('_AM_WEBPHOTO_CAT_TH_PHOTOS', '�̿���ư�衦��ǥ����ο�');
    define('_AM_WEBPHOTO_CAT_TH_OPERATION', '���');
    define('_AM_WEBPHOTO_CAT_TH_IMAGE', '���᡼��');
    define('_AM_WEBPHOTO_CAT_TH_PARENT', '�ƥ��ƥ��꡼');
    define('_AM_WEBPHOTO_CAT_MENU_NEW', '���ƥ��꡼�ο�������');
    define('_AM_WEBPHOTO_CAT_MENU_EDIT', '���ƥ��꡼���Խ�');
    define('_AM_WEBPHOTO_CAT_INSERTED', '���ƥ��꡼���ɲä��ޤ���');
    define('_AM_WEBPHOTO_CAT_UPDATED', '���ƥ��꡼�򹹿����ޤ���');
    define('_AM_WEBPHOTO_CAT_BTN_BATCH', '�ѹ���ȿ�Ǥ���');
    define('_AM_WEBPHOTO_CAT_LINK_MAKETOPCAT', '�ȥåץ��ƥ��꡼���ɲ�');
    define('_AM_WEBPHOTO_CAT_LINK_ADDPHOTOS', '���Υ��ƥ��꡼�˲������ɲ�');
    define('_AM_WEBPHOTO_CAT_LINK_EDIT', '���Υ��ƥ��꡼���Խ�');
    define('_AM_WEBPHOTO_CAT_LINK_MAKESUBCAT', '���Υ��ƥ��꡼���˥��֥��ƥ��꡼����');
    define('_AM_WEBPHOTO_CAT_FMT_NEEDADMISSION', '̤��ǧ�μ̿���ư�衦��ǥ������� (%s ��)');
    define('_AM_WEBPHOTO_CAT_FMT_CATDELCONFIRM', '���ƥ��꡼ %s �������Ƥ�����Ǥ����� �۲��Υ��֥��ƥ��꡼��ޤᡢ�̿���ư�衦��ǥ����䥳���Ȥ����٤ƺ������ޤ�');

    // Admission
    define('_AM_WEBPHOTO_TH_BATCHUPDATE', '�����å������̿���ư�衦��ǥ�����ޤȤ���ѹ�����');
    define('_AM_WEBPHOTO_OPT_NOCHANGE', '�ѹ��ʤ�');
    define('_AM_WEBPHOTO_JS_UPDATECONFIRM', '���ꤵ�줿���ܤˤĤ��ƤΤߡ������å������̿���ư�衦��ǥ������ѹ����ޤ�');

    // Module Checker
    define('_AM_WEBPHOTO_H4_ENVIRONMENT', '�Ķ������å�');
    define('_AM_WEBPHOTO_PHPDIRECTIVE', 'PHP����');
    define('_AM_WEBPHOTO_BOTHOK', 'ξ��ok');
    define('_AM_WEBPHOTO_NEEDON', '��on');

    define('_AM_WEBPHOTO_H4_TABLE', '�ơ��֥�����å�');
    define('_AM_WEBPHOTO_COMMENTSTABLE', '�����ȥơ��֥�');
    define('_AM_WEBPHOTO_NUMBEROFPHOTOS', '�̿���ư�衦��ǥ��������');
    define('_AM_WEBPHOTO_NUMBEROFDESCRIPTIONS', '�ƥ��������');
    define('_AM_WEBPHOTO_NUMBEROFCATEGORIES', '���ƥ��꡼���');
    define('_AM_WEBPHOTO_NUMBEROFVOTEDATA', '��ɼ���');
    define('_AM_WEBPHOTO_NUMBEROFCOMMENTS', '���������');

    define('_AM_WEBPHOTO_H4_CONFIG', '��������å�');
    define('_AM_WEBPHOTO_PIPEFORIMAGES', '���������ץ����');

    define('_AM_WEBPHOTO_ERR_LASTCHAR', "���顼: �Ǹ��ʸ����'/'��ɬ�פ���ޤ���");
    define('_AM_WEBPHOTO_ERR_FIRSTCHAR', "���顼: �ǽ��ʸ����'/'�Ǥʤ���Фʤ�ޤ���");
    define('_AM_WEBPHOTO_ERR_PERMISSION', '���顼: �ޤ����Υǥ��쥯�ȥ��Ĥ��äƲ����������ξ�ǡ������ǽ�����ꤷ�Ʋ�������Unix�Ǥ�chmod 777��Windows�Ǥ��ɤ߼������°���򳰤��ޤ�');
    define('_AM_WEBPHOTO_ERR_NOTDIRECTORY', '���顼: ���ꤵ�줿�ǥ��쥯�ȥ꤬����ޤ���.');
    define('_AM_WEBPHOTO_ERR_READORWRITE', '���顼: ���ꤵ�줿�ǥ��쥯�ȥ���ɤ߽Ф��ʤ����񤭹���ʤ����Τ����줫�Ǥ�������ξ������Ĥ�������ˤ��Ʋ�������Unix�Ǥ�chmod 777��Windows�Ǥ��ɤ߼������°���򳰤��ޤ�');
    define('_AM_WEBPHOTO_ERR_SAMEDIR', '���顼: �̿���ư�衦��ǥ����ѥǥ��쥯�ȥ�ȥ���ͥ����ѥǥ��쥯�ȥ꤬���Ǥ����ʤ���������Բ�ǽ�Ǥ���');
    define('_AM_WEBPHOTO_LNK_CHECKGD2', 'GD2(truecolor)�⡼�ɤ�ư�����ɤ����Υ����å�');
    define('_AM_WEBPHOTO_CHECKGD2', '�ʤ��Υ���褬�����ɽ������ʤ���С�GD2�⡼�ɤǤ�ư���ʤ���Τ�����Ƥ���������');
    define('_AM_WEBPHOTO_GD2SUCCESS', '�������ޤ���!<br>�����餯�����Υ����Ф�PHP�Ǥϡ�GD2(true color)�⡼�ɤǲ�����������ǽ�Ǥ���');

    define('_AM_WEBPHOTO_H4_PHOTOLINK', '�̿���ư�衦��ǥ����ȥ���ͥ���Υ�󥯥����å�');
    define('_AM_WEBPHOTO_NOWCHECKING', '�����å��� .');

    define('_AM_WEBPHOTO_FMT_NUMBEROFDEADPHOTOS', '�̿���ư�衦��ǥ����Τʤ��쥳���ɤ� %s �Ĥ���ޤ�����');
    define('_AM_WEBPHOTO_FMT_NUMBEROFDEADTHUMBS', '����ͥ��뤬 %s ��̤�����Ǥ�');
    define('_AM_WEBPHOTO_FMT_NUMBEROFREMOVEDTMPS', '�ƥ�ݥ��� %s �ĺ�����ޤ���');
    define('_AM_WEBPHOTO_LINK_REDOTHUMBS', '����ͥ���ƹ���');
    define('_AM_WEBPHOTO_LINK_TABLEMAINTENANCE', '�ơ��֥���ƥʥ�');

    // Redo Thumbnail
    define('_AM_WEBPHOTO_FMT_CHECKING', '%s ������å��� ... ');
    define('_AM_WEBPHOTO_FORM_RECORDMAINTENANCE', '����ͥ���κƹ��ۤʤɡ��̿���ư�衦��ǥ����γƼ���ƥʥ�');

    define('_AM_WEBPHOTO_FAILEDREADING', '�̿���ư�衦��ǥ����Υե�������ɤ߹��߼���');
    define('_AM_WEBPHOTO_CREATEDTHUMBS', '����ͥ��������λ');
    define('_AM_WEBPHOTO_BIGTHUMBS', '����ͥ��������Ǥ��ʤ��Τǡ����ԡ����ޤ���');
    define('_AM_WEBPHOTO_SKIPPED', '�����åפ��ޤ�');
    define('_AM_WEBPHOTO_SIZEREPAIRED', '(��Ͽ����Ƥ����ԥ�������������ޤ���)');
    define('_AM_WEBPHOTO_RECREMOVED', '���Υ쥳���ɤϺ������ޤ���');
    define('_AM_WEBPHOTO_PHOTONOTEXISTS', '�̿���ư�衦��ǥ���������ޤ���');
    define('_AM_WEBPHOTO_PHOTORESIZED', '������Ĵ�����ޤ���');

    define('_AM_WEBPHOTO_TEXT_RECORDFORSTARTING', '�����򳫻Ϥ���쥳�����ֹ�');
    define('_AM_WEBPHOTO_TEXT_NUMBERATATIME', '���٤˽�������̿���ư�衦��ǥ����ο�');
    define('_AM_WEBPHOTO_LABEL_DESCNUMBERATATIME', '���ο����礭����������ȥ����ФΥ����ॢ���Ȥ򾷤��ޤ�');

    define('_AM_WEBPHOTO_RADIO_FORCEREDO', '����ͥ��뤬���äƤ��˺�����ľ��');
    define('_AM_WEBPHOTO_RADIO_REMOVEREC', '�̿���ư�衦��ǥ������ʤ��쥳���ɤ�������');
    define('_AM_WEBPHOTO_RADIO_RESIZE', '���Υԥ��������������礭�ʲ����ϥ��������ڤ�Ĥ��');

    define('_AM_WEBPHOTO_FINISHED', '��λ');
    define('_AM_WEBPHOTO_LINK_RESTART', '�ƥ�������');
    define('_AM_WEBPHOTO_SUBMIT_NEXT', '����');

    // GroupPerm Global
    define('_AM_WEBPHOTO_GROUPPERM_GLOBALDESC', '���롼�׸ġ��ˤĤ��ơ����¤����ꤷ�ޤ�');
    define('_AM_WEBPHOTO_GPERMUPDATED', '����������ѹ����ޤ���');

    // Import
    define('_AM_WEBPHOTO_H3_FMT_IMPORTTO', '%s �ؤμ̿���ư�衦��ǥ����Υ���ݡ���');
    define('_AM_WEBPHOTO_FMT_IMPORTFROMMYALBUMP', 'myAblum-P�⥸�塼��: ��%s�� ����μ����ߡʥ��ƥ��꡼ñ�̡�');
    define('_AM_WEBPHOTO_FMT_IMPORTFROMIMAGEMANAGER', '���᡼�����ޥ͡����㤫��μ����ߡʥ��ƥ��꡼ñ�̡�');

    define('_AM_WEBPHOTO_IMPORTCONFIRM', '����ݡ��Ȥ��ޤ���������Ǥ�����');
    define('_AM_WEBPHOTO_FMT_IMPORTSUCCESS', '%s ��μ̿���ư�衦��ǥ����򥤥�ݡ��Ȥ��ޤ���');

    // Export
    define('_AM_WEBPHOTO_H3_FMT_EXPORTTO', '%s ����¾�⥸�塼�����ؤμ̿���ư�衦��ǥ����Υ������ݡ���');
    define('_AM_WEBPHOTO_FMT_EXPORTTOIMAGEMANAGER', '���᡼�����ޥ͡�����ؤν񤭽Ф��ʥ��ƥ��꡼ñ�̡�');
    define('_AM_WEBPHOTO_FMT_EXPORTIMSRCCAT', '���ԡ������ƥ��꡼');
    define('_AM_WEBPHOTO_FMT_EXPORTIMDSTCAT', '���ԡ��襫�ƥ��꡼');
    define('_AM_WEBPHOTO_CB_EXPORTRECURSIVELY', '���֥��ƥ��꡼�⥨�����ݡ��Ȥ���');
    define('_AM_WEBPHOTO_CB_EXPORTTHUMB', '����ͥ�����������򥨥����ݡ��Ȥ���');
    define('_AM_WEBPHOTO_EXPORTCONFIRM', '�������ݡ��Ȥ��ޤ���������Ǥ�����');
    define('_AM_WEBPHOTO_FMT_EXPORTSUCCESS', '%s ��μ̿���ư�衦��ǥ����򥨥����ݡ��Ȥ��ޤ���');

    //---------------------------------------------------------
    // move from main.php
    //---------------------------------------------------------
    define('_AM_WEBPHOTO_BTN_SELECTALL', '������');
    define('_AM_WEBPHOTO_BTN_SELECTNONE', '������');
    define('_AM_WEBPHOTO_BTN_SELECTRVS', '����ȿž');
    define('_AM_WEBPHOTO_FMT_PHOTONUM', '%s ��');

    define('_AM_WEBPHOTO_ADMISSION', '�̿���ư�衦��ǥ����ξ�ǧ');
    define('_AM_WEBPHOTO_ADMITTING', '�̿���ư�衦��ǥ�����ǧ���ޤ���');
    define('_AM_WEBPHOTO_LABEL_ADMIT', '�����å������̿���ư�衦��ǥ�����ǧ����');
    define('_AM_WEBPHOTO_BUTTON_ADMIT', '��ǧ');
    define('_AM_WEBPHOTO_BUTTON_EXTRACT', '���');

    define('_AM_WEBPHOTO_LABEL_REMOVE', '�����å������̿���ư�衦��ǥ�����������');
    define('_AM_WEBPHOTO_JS_REMOVECONFIRM', '������Ƥ�����Ǥ���');
    define('_AM_WEBPHOTO_LABEL_MOVE', '�����å������̿���ư�衦��ǥ������ư����');
    define('_AM_WEBPHOTO_BUTTON_MOVE', '��ư');
    define('_AM_WEBPHOTO_BUTTON_UPDATE', '�ѹ�');
    define('_AM_WEBPHOTO_DEADLINKMAINPHOTO', '�̿���ư�衦��ǥ�����¸�ߤ��ޤ���');

    define('_AM_WEBPHOTO_NOSUBMITTED', '������Ƥμ̿���ư�衦��ǥ����Ϥ���ޤ���');
    define('_AM_WEBPHOTO_ADDMAIN', '�ȥåץ��ƥ�����ɲ�');
    define('_AM_WEBPHOTO_IMGURL', '������URL (�����ι⤵�Ϥ��餫����50pixel��): ');
    define('_AM_WEBPHOTO_ADD', '�ɲ�');
    define('_AM_WEBPHOTO_ADDSUB', '���֥��ƥ�����ɲ�');
    define('_AM_WEBPHOTO_IN', '');
    define('_AM_WEBPHOTO_MODCAT', '���ƥ����ѹ�');

    define('_AM_WEBPHOTO_MODREQDELETED', '�ѹ���������');
    define('_AM_WEBPHOTO_IMGURLMAIN', '����URL (�����ι⤵�Ϥ��餫����50pixel��): ');
    define('_AM_WEBPHOTO_PARENT', '�ƥ��ƥ���:');
    define('_AM_WEBPHOTO_SAVE', '�ѹ�����¸');
    define('_AM_WEBPHOTO_CATDELETED', '���ƥ���ξõλ');
    define('_AM_WEBPHOTO_CATDEL_WARNING', '���ƥ����Ʊ���ˤ����˴ޤޤ��̿���ư�衦��ǥ�������ӥ����Ȥ����ƺ������ޤ���������Ǥ�����');

    define('_AM_WEBPHOTO_NEWCATADDED', '�����ƥ����ɲä�����!');
    define('_AM_WEBPHOTO_ERROREXIST', '���顼: �󶡤����̿���ư�衦��ǥ����Ϥ��Ǥ˥ǡ����١�����¸�ߤ��ޤ���');
    define('_AM_WEBPHOTO_ERRORTITLE', '���顼: �����ȥ뤬ɬ�פǤ�!');
    define('_AM_WEBPHOTO_ERRORDESC', '���顼: ������ɬ�פǤ�!');
    define('_AM_WEBPHOTO_WEAPPROVED', '�̿���ư�衦��ǥ����Υǡ����١����ؤΥ��������ǧ���ޤ�����');
    define('_AM_WEBPHOTO_THANKSSUBMIT', '�����ͭ���񤦤������ޤ���');
    define('_AM_WEBPHOTO_CONFUPDATED', '����򹹿����ޤ�����');

    define('_AM_WEBPHOTO_PHOTOBATCHUPLOAD', '�����Ф˥��åץ��ɺѥե�����ΰ����Ͽ');
    define('_AM_WEBPHOTO_PHOTOPATH', 'Path:');
    define('_AM_WEBPHOTO_TEXT_DIRECTORY', '�ǥ��쥯�ȥ�');
    define('_AM_WEBPHOTO_DESC_PHOTOPATH', '�̿���ư�衦��ǥ����δޤޤ��ǥ��쥯�ȥ�����Хѥ��ǻ��ꤷ�Ʋ�����');
    define('_AM_WEBPHOTO_MES_INVALIDDIRECTORY', '���ꤵ�줿�ǥ��쥯�ȥ꤫��̿���ư�衦��ǥ������ɤ߽Ф��ޤ���');
    define('_AM_WEBPHOTO_MES_BATCHDONE', '%s ��μ̿���ư�衦��ǥ�������Ͽ���ޤ���');
    define('_AM_WEBPHOTO_MES_BATCHNONE', '���ꤵ�줿�ǥ��쥯�ȥ�˼̿���ư�衦��ǥ����Υե����뤬�ߤĤ���ޤ���Ǥ���');

    //---------------------------------------------------------
    // move from myalbum_constants.php
    //---------------------------------------------------------
    // Global Group Permission
    define('_AM_WEBPHOTO_GPERM_INSERTABLE', '��Ʋġ��׾�ǧ��');
    define('_AM_WEBPHOTO_GPERM_SUPERINSERT', '��Ʋġʾ�ǧ���ס�');
    define('_AM_WEBPHOTO_GPERM_EDITABLE', '�Խ��ġ��׾�ǧ��');
    define('_AM_WEBPHOTO_GPERM_SUPEREDIT', '�Խ��ġʾ�ǧ���ס�');
    define('_AM_WEBPHOTO_GPERM_DELETABLE', '����ġ��׾�ǧ��');
    define('_AM_WEBPHOTO_GPERM_SUPERDELETE', '����ġʾ�ǧ���ס�');
    define('_AM_WEBPHOTO_GPERM_TOUCHOTHERS', '¾�桼���Υ��᡼�����Խ�������ġ��׾�ǧ��');
    define('_AM_WEBPHOTO_GPERM_SUPERTOUCHOTHERS', '¾�桼���Υ��᡼�����Խ�������ġʾ�ǧ���ס�');
    define('_AM_WEBPHOTO_GPERM_RATEVIEW', '��ɼ������');
    define('_AM_WEBPHOTO_GPERM_RATEVOTE', '��ɼ��');
    define('_AM_WEBPHOTO_GPERM_TELLAFRIEND', 'ͧ�ͤ��Τ餻��');

    // add for webphoto
    define('_AM_WEBPHOTO_GPERM_TAGEDIT', '�����Խ��ġʾ�ǧ���ס�');

    // v0.30
    define('_AM_WEBPHOTO_GPERM_MAIL', '�᡼����Ʋġʾ�ǧ���ס�');
    define('_AM_WEBPHOTO_GPERM_FILE', '�ե�������Ʋġʾ�ǧ���ס�');

    //=========================================================
    // add for webphoto
    //=========================================================

    //---------------------------------------------------------
    // google icon
    // modify from gnavi
    //---------------------------------------------------------

    // list
    define('_AM_WEBPHOTO_GICON_ADD', '��������򿷵��ɲ�');
    define('_AM_WEBPHOTO_GICON_LIST_IMAGE', '��������');
    define('_AM_WEBPHOTO_GICON_LIST_SHADOW', '����ɡ�');
    define('_AM_WEBPHOTO_GICON_ANCHOR', '���󥫡��ݥ����');
    define('_AM_WEBPHOTO_GICON_WINANC', '������ɥ����󥫡�');
    define('_AM_WEBPHOTO_GICON_LIST_EDIT', '����������Խ�');

    // form
    define('_AM_WEBPHOTO_GICON_MENU_NEW', '��������ο�������');
    define('_AM_WEBPHOTO_GICON_MENU_EDIT', '����������Խ�');
    define('_AM_WEBPHOTO_GICON_IMAGE_SEL', '�����������������');
    define('_AM_WEBPHOTO_GICON_SHADOW_SEL', '�������󥷥�ɡ�������');
    define('_AM_WEBPHOTO_GICON_SHADOW_DEL', '�������󥷥�ɡ�����');
    define('_AM_WEBPHOTO_GICON_DELCONFIRM', '�������� %s �������Ƥ�����Ǥ����� ');

    //---------------------------------------------------------
    // mime type
    // modify from wfdownloads
    //---------------------------------------------------------

    // Mimetype Form
    define('_AM_WEBPHOTO_MIME_CREATEF', 'MIME������ ����');
    define('_AM_WEBPHOTO_MIME_MODIFYF', 'MIME������ �Խ�');
    define('_AM_WEBPHOTO_MIME_NOMIMEINFO', 'MIME�����פ����򤵤�Ƥ��ޤ���');
    define('_AM_WEBPHOTO_MIME_INFOTEXT', '<ul><li>������MIME�����פ�������뤳�Ȥ��Ǥ������Υե����फ���ñ���Խ��ڤӺ�����뤳�Ȥ��Ǥ��ޤ��� </li>
    <li>�����Եڤӥ桼�������åץ��ɤǤ���MIME�����פ��ǧ�Ǥ��ޤ���</li>
    <li>���åץ��ɤ���Ƥ���MIME�����פ��ѹ������������ޤ���</li></ul>
    ');

    // Mimetype Database
    define('_AM_WEBPHOTO_MIME_DELETETHIS', '���򤵤줿MIME�����פ������ޤ���������Ǥ�����');
    define('_AM_WEBPHOTO_MIME_MIMEDELETED', 'MIME������ %s �Ϻ������ޤ�����');
    define('_AM_WEBPHOTO_MIME_CREATED', 'MIME�����פ�������ޤ�����');
    define('_AM_WEBPHOTO_MIME_MODIFIED', 'MIME�����פ򹹿����ޤ�����');

    //image admin icon
    define('_AM_WEBPHOTO_MIME_ICO_EDIT', '���Υ����ƥ���Խ�');
    define('_AM_WEBPHOTO_MIME_ICO_DELETE', '���Υ����ƥ����');
    define('_AM_WEBPHOTO_MIME_ICO_ONLINE', '����饤��');
    define('_AM_WEBPHOTO_MIME_ICO_OFFLINE', '���ե饤��');

    // added for webphoto
    define('_AM_WEBPHOTO_MIME_PERMS', '���Ĥ���Ƥ��륰�롼��');
    define('_AM_WEBPHOTO_MIME_ALLOWED', '���Ĥ���Ƥ���MIME������');
    define('_AM_WEBPHOTO_MIME_NOT_ENTER_EXT', '��ĥ�Ҥ����Ϥ���Ƥ��ʤ�');

    //---------------------------------------------------------
    // check config
    //---------------------------------------------------------
    define('_AM_WEBPHOTO_DIRECTORYFOR_PHOTOS', '�̿���ư�衦��ǥ��� �ǥ��쥯�ȥ�');
    define('_AM_WEBPHOTO_DIRECTORYFOR_THUMBS', '����ͥ��� �ǥ��쥯�ȥ�');
    define('_AM_WEBPHOTO_DIRECTORYFOR_GICONS', 'Google �������� �ǥ��쥯�ȥ�');
    define('_AM_WEBPHOTO_DIRECTORYFOR_TMP', '����ե����� �ǥ��쥯�ȥ�');

    //---------------------------------------------------------
    // check table
    //---------------------------------------------------------
    define('_AM_WEBPHOTO_NUMBEROFRECORED', '�쥳���ɿ�');

    //---------------------------------------------------------
    // manage
    //---------------------------------------------------------
    define('_AM_WEBPHOTO_MANAGE_DESC', '<b>���</b><br>�ơ��֥�ñ�Τδ����Ǥ�<br>��Ϣ����ơ��֥���ѹ�����ޤ���');
    define('_AM_WEBPHOTO_ERR_NO_RECORD', '�ǡ�����¸�ߤ��ʤ�');

    //---------------------------------------------------------
    // import
    //---------------------------------------------------------
    define('_AM_WEBPHOTO_FMT_IMPORTFROM_WEBPHOTO', 'webphoto �⥸�塼��: ��%s�� ����μ����ߡʥ��ƥ��꡼ñ�̡�');
    define('_AM_WEBPHOTO_IMPORT_COMMENT_NO', '�����Ȥ򥳥ԡ����ʤ�');
    define('_AM_WEBPHOTO_IMPORT_COMMENT_YES', '�����Ȥ򥳥ԡ�����');

    //---------------------------------------------------------
    // v0.20
    //---------------------------------------------------------
    define('_AM_WEBPHOTO_PATHINFO_LINK', 'Pathinfo ��ư�����ɤ����Υ����å�');
    define('_AM_WEBPHOTO_PATHINFO_DSC', '�ʤ��Υ���褬�����ɽ������ʤ���С�Pathinfo ��ư���ʤ���Τ�����Ƥ���������');
    define('_AM_WEBPHOTO_PATHINFO_SUCCESS', '�������ޤ���!<br>�����餯�����Υ����ФǤϡ�Pathinfo �����ѤǤ��ޤ�');
    define('_AM_WEBPHOTO_CAP_REDO_EXIF', 'Exif �μ���');
    define('_AM_WEBPHOTO_RADIO_REDO_EXIF_TRY', '���ꤵ��Ƥ��ʤ��Ȥ��˼���');
    define('_AM_WEBPHOTO_RADIO_REDO_EXIF_ALWAYS', '��˼�������');

    //---------------------------------------------------------
    // v0.30
    //---------------------------------------------------------
    // checkconfigs
    define('_AM_WEBPHOTO_DIRECTORYFOR_FILE', 'FTP �ե����� �ǥ��쥯�ȥ�');
    define('_AM_WEBPHOTO_WARN_GEUST_CAN_READ', '���Υǥ��쥯�ȥ�ϥ����Ȥ��ɤळ�Ȥ�����ޤ�');
    define('_AM_WEBPHOTO_WARN_RECOMMEND_PATH', '�ɥ�����ȡ��롼�Ȱʳ������ꤹ�뤳�Ȥ򤪴��ᤷ�ޤ�');
    define('_AM_WEBPHOTO_MULTIBYTE_LINK', 'ʸ���������Ѵ���ư�����ɤ����Υ����å�');
    define('_AM_WEBPHOTO_MULTIBYTE_DSC', '�ʤ��Υ���褬�����ɽ������ʤ���С�ʸ���������Ѵ���ư���ʤ��褦�Ǥ���');
    define('_AM_WEBPHOTO_MULTIBYTE_SUCCESS', '����ʸ��ʸ������������ɽ������Ƥ��ޤ�����');

    // maillog manager
    define('_AM_WEBPHOTO_SHOW_LIST', '����ɽ��');
    define('_AM_WEBPHOTO_MAILLOG_STATUS_REJECT', '���ݤ��줿�᡼��');
    define('_AM_WEBPHOTO_MAILLOG_STATUS_PARTIAL', '������ź�եե����뤬���ݤ��줿�᡼��');
    define('_AM_WEBPHOTO_MAILLOG_STATUS_SUBMIT', '��Ƥ��줿�᡼��');
    define('_AM_WEBPHOTO_BUTTON_SUBMIT_MAIL', '���Υ᡼�����Ƥ���');
    define('_AM_WEBPHOTO_ERR_MAILLOG_NO_ATTACH', 'ź�եե����뤬���򤵤�Ƥ��ʤ�');

    // mimetype
    define('_AM_WEBPHOTO_MIME_ADD_NEW', 'MIME �����פ��ɲä���');

    //---------------------------------------------------------
    // v0.40
    //---------------------------------------------------------
    // index
    define('_AM_WEBPHOTO_MUST_UPDATE', '���åץǡ��Ȥ�ɬ�פǤ�');
    define('_AM_WEBPHOTO_TITLE_BIN', '���ޥ�ɤδ���');
    define('_AM_WEBPHOTO_TEST_BIN', '���ޥ�ɤΥƥ��ȼ¹�');

    // redothumbs
    define('_AM_WEBPHOTO_ERR_GET_IMAGE_SIZE', 'image size �������Ǥ��ʤ�');

    // checktables
    define('_AM_WEBPHOTO_FMT_NOT_READABLE', '%s (%s) ���ɤ�ޤ���.');

    //---------------------------------------------------------
    // v0.50
    //---------------------------------------------------------
    // config check
    define('_AM_WEBPHOTO_DIRECTORYFOR_UPLOADS', '���åץ��ɡ��ǥ��쥯�ȥ�');
    define('_AM_WEBPHOTO_DIRECTORYFOR_MEDIAS', '��ǥ������ǥ��쥯�ȥ�');

    // item manager
    define('_AM_WEBPHOTO_ITEM_SELECT', '�����ƥ������');
    define('_AM_WEBPHOTO_ITEM_ADD', '�����ƥ���ɲ�');
    define('_AM_WEBPHOTO_ITEM_LISTING', '�����ƥ�α���');
    define('_AM_WEBPHOTO_VOTE_DELETED', '��ɼ�ǡ����Ϻ�����줿');
    define('_AM_WEBPHOTO_VOTE_STATS', '��ɼ������');
    define('_AM_WEBPHOTO_VOTE_ENTRY', '���Τ���ɼ');
    define('_AM_WEBPHOTO_VOTE_USER', '��Ͽ�桼������ɼ');
    define('_AM_WEBPHOTO_VOTE_GUEST', '�����Ȥ���ɼ');
    define('_AM_WEBPHOTO_VOTE_TOTAL', '��ɼ��');
    define('_AM_WEBPHOTO_VOTE_USERAVG', '�桼����ʿ��ɾ��');
    define('_AM_WEBPHOTO_VOTE_USERVOTES', '�桼��������ɼ��');
    define('_AM_WEBPHOTO_LOG_VIEW', '���ե�����α���');
    define('_AM_WEBPHOTO_LOG_EMPT', '���ե��������ˤ���');
    define('_AM_WEBPHOTO_PLAYLIST_PATH', '�ץ쥤�ꥹ�ȤΥѥ�');
    define('_AM_WEBPHOTO_PLAYLIST_REFRESH', '�ץ쥤�ꥹ�ȤΥ���å���κ�����');
    define('_AM_WEBPHOTO_STATUS_CHANGE', '���ơ��������ѹ�');
    define('_AM_WEBPHOTO_STATUS_OFFLINE', '���ե饤��');
    define('_AM_WEBPHOTO_STATUS_ONLINE', '����饤��');
    define('_AM_WEBPHOTO_STATUS_AUTO', '��ưȯ��');

    // item form
    define('_AM_WEBPHOTO_TIME_NOW', '��������');

    // playlist form
    define('_AM_WEBPHOTO_PLAYLIST_ADD', '�ץ쥤�ꥹ�Ȥ��ɲä���');
    define('_AM_WEBPHOTO_PLAYLIST_TYPE', '�ץ쥤�ꥹ�ȤΥ�����');
    define('_AM_WEBPHOTO_PLAYLIST_FEED_DSC', 'WEB Feed URL �����Ϥ���');
    define('_AM_WEBPHOTO_PLAYLIST_DIR_DSC', '�ǥ��쥯�ȥ�̾�����򤹤� ');

    // player manager
    define('_AM_WEBPHOTO_PLAYER_MANAGER', '�ץ쥤�䡼����');
    define('_AM_WEBPHOTO_PLAYER_ADD', '�ץ쥤�䡼���ɲ�');
    define('_AM_WEBPHOTO_PLAYER_MOD', '�ץ쥤�䡼���ѹ�');
    define('_AM_WEBPHOTO_PLAYER_CLONE', '�ץ쥤�䡼��ʣ��');
    define('_AM_WEBPHOTO_PLAYER_ADDED', '�ץ쥤�䡼���ɲä���');
    define('_AM_WEBPHOTO_PLAYER_DELETED', '�ץ쥤�䡼��������');
    define('_AM_WEBPHOTO_PLAYER_MODIFIED', '�ץ쥤�䡼���ѹ�����');
    define('_AM_WEBPHOTO_PLAYER_PREVIEW', '�ץ�ӥ塼');
    define('_AM_WEBPHOTO_PLAYER_PREVIEW_DSC', '�ǽ���ѹ�����¸���Ƥ���������');
    define('_AM_WEBPHOTO_PLAYER_PREVIEW_LINK', '�ץ�ӥ塼�Υ�����');
    define('_AM_WEBPHOTO_PLAYER_NO_ITEM', '�������륢���ƥब����ޤ���');
    define('_AM_WEBPHOTO_PLAYER_WARNING', '[�ٹ�] �ץ쥤�䡼�������Ƥ⤤���Ǥ����� <br>����������ˡ����Υץ��䡼����Ѥ��Ƥ������ƤΥ����ƥ���ư���ѹ����Ƥ���������');
    define('_AM_WEBPHOTO_PLAYER_ERR_EXIST', '[���顼] Ʊ��̾���Υץ쥤�䡼��¸�ߤ��Ƥ��ޤ���');
    define('_AM_WEBPHOTO_BUTTON_CLONE', 'ʣ��');

    //---------------------------------------------------------
    // v0.60
    //---------------------------------------------------------
    // cat form
    define('_AM_WEBPHOTO_CAP_CAT_SELECT', '���ƥ������������');
    define('_AM_WEBPHOTO_DSC_CAT_PATH', "XOOPS���󥹥ȡ����褫��Υѥ�����ꤷ�ޤ��ʺǽ��'/'��ɬ�ס�");
    define('_AM_WEBPHOTO_DSC_CAT_FOLDER', '���ꤷ�ʤ��Ȥ��ϡ��ե��������������ɽ������ޤ�');

    //---------------------------------------------------------
    // v0.70
    //---------------------------------------------------------
    define('_AM_WEBPHOTO_RECOMMEND_OFF', '�侩 off');

    //---------------------------------------------------------
    // v0.80
    //---------------------------------------------------------
    define('_AM_WEBPHOTO_TITLE_WAITING', '��ǧ�Ԥ�����');
    define('_AM_WEBPHOTO_TITLE_OFFLINE', '���ե饤�����');
    define('_AM_WEBPHOTO_TITLE_EXPIRED', '�����ڤ����');

    //---------------------------------------------------------
    // v0.81
    //---------------------------------------------------------
    // checkconfigs
    define('_AM_WEBPHOTO_QR_CHECK_LINK', 'QR�����ɤ�ɽ���Ǥ��뤫�Υ����å�');
    define('_AM_WEBPHOTO_QR_CHECK_DSC', '�ʤ��Υ���褬�����ɽ������ʤ���С�QR�����ɤ�ɽ���Ǥ��ޤ����');
    define('_AM_WEBPHOTO_QR_CHECK_SUCCESS', 'QR�����ɤ�ɽ������Ƥ��ޤ�����');
    define('_AM_WEBPHOTO_QR_CHECK_SHOW', '�ǥХå�����򸫤�');
    define('_AM_WEBPHOTO_QR_CHECK_INFO', '�ǥХå�����');

    //---------------------------------------------------------
    // v0.90
    //---------------------------------------------------------
    // cat form
    define('_AM_WEBPHOTO_CAT_PARENT_CAP', '�ƥ��ƥ���θ���');
    define('_AM_WEBPHOTO_CAT_PARENT_FMT', '�ƥ��ƥ��� ( %s ) �θ��¤�Ѿ�����');
    define('_AM_WEBPHOTO_CAT_CHILD_CAP', '���̤Υ��ƥ���');
    define('_AM_WEBPHOTO_CAT_CHILD_NUM', '���̤Υ��ƥ���ο�');
    define('_AM_WEBPHOTO_CAT_CHILD_PERM', '���̤Υ��ƥ���θ��¤��ѹ�����');

    //---------------------------------------------------------
    // v1.00
    //---------------------------------------------------------
    // groupperm
    define('_AM_WEBPHOTO_GPERM_HTML', 'HTML��Ʋ�');

    //---------------------------------------------------------
    // v1.21
    //---------------------------------------------------------
    define('_AM_WEBPHOTO_RSS_DEBUG', 'RSS �ǥХå�ɽ��');
    define('_AM_WEBPHOTO_RSS_CLEAR', 'RSS ����å��塦���ꥢ');
    define('_AM_WEBPHOTO_RSS_CLEARED', '���ꥢ����');

    //---------------------------------------------------------
    // v1.40
    //---------------------------------------------------------
    define('_AM_WEBPHOTO_TIMELINE_MODULE', '������饤�󡦥⥸�塼��');
    define('_AM_WEBPHOTO_MODULE_NOT_INSTALL', '�⥸�塼��ϥ��󥹥ȡ��뤵��Ƥ��ʤ�');

    //---------------------------------------------------------
    // v1.50
    //---------------------------------------------------------
    define('_AM_WEBPHOTO_FILE_CHECK', '�ե�������������θ���');
    define('_AM_WEBPHOTO_FILE_CHECK_DSC', 'ɬ�פʥե����뤬���뤫�ե����륵�����ǥ����å�����');

    //---------------------------------------------------------
    // v1.72
    //---------------------------------------------------------
    define('_AM_WEBPHOTO_MYSQL_CONFIG', 'MySQL ����');
    define('_AM_WEBPHOTO_MULTIBYTE_CONFIG', '�ޥ���Х��� ����');

    //---------------------------------------------------------
    // v2.00
    //---------------------------------------------------------
    // invite
    define('_AM_WEBPHOTO_INVITE_EMAIL', '�������Υ᡼�륢�ɥ쥹');
    define('_AM_WEBPHOTO_INVITE_NAME', '���ʤ��Τ�̾��');
    define('_AM_WEBPHOTO_INVITE_MESSAGE', '��å�����');
    define('_AM_WEBPHOTO_INVITE_SUBMIT', '���Ԥ���');
    define('_AM_WEBPHOTO_INVITE_EXAMPLE', '�㡧����ˤ��ϡ������Ǥ����������ä��������Ȥξ��Ծ�������ޤ���<br>��������⤼����Ͽ���ƤߤƤ���������');
    define('_AM_WEBPHOTO_INVITE_SUBJECT', '%s ���󤫤� %s �ؤξ��Ծ����Ϥ��Ƥ��ޤ�');
    define('_AM_WEBPHOTO_INVITE_ERR_NO_NAME', '̾�����ʤ�');

    // gperm
    define('_AM_WEBPHOTO_GROUP_MOD_ADMIN', '���Υ⥸�塼��δ����ԥ��롼��');
    define('_AM_WEBPHOTO_GROUP_MOD_USER', '���Υ⥸�塼��Υ桼�������롼��');
    define('_AM_WEBPHOTO_GROUP_MOD_CATEGORY', '���Υ⥸�塼��Υ��ƥ���Υ��롼��');
    define('_AM_WEBPHOTO_GPERM_MODULE_ADMIN', '�⥸�塼�����');
    define('_AM_WEBPHOTO_GPERM_MODULE_READ', '�⥸�塼�롦��������');

    // item manage
    define('_AM_WEBPHOTO_BUTTON_REFUSE', '����');
    define('_AM_WEBPHOTO_ERR_NO_SELECT', '���顼: �����ƥब���򤵤�Ƥ��ʤ�');

    // user list
    define('_AM_WEBPHOTO_USER_UID', 'UID');
    define('_AM_WEBPHOTO_USER_UNAME', '�桼����̾');
    define('_AM_WEBPHOTO_USER_NAME', '��̾');
    define('_AM_WEBPHOTO_USER_POSTS', '��ƿ�');
    define('_AM_WEBPHOTO_USER_LEVEL', '��٥�');
    define('_AM_WEBPHOTO_USER_REGDATE', '��Ͽ��');
    define('_AM_WEBPHOTO_USER_LASTLOGIN', '�ǽ�������');
    define('_AM_WEBPHOTO_USER_CONTROL', '���');
    define('_AM_WEBPHOTO_USER_TOTAL', '���С���');
    define('_AM_WEBPHOTO_USER_ASSIGN', '���С�����Ͽ');
    define('_AM_WEBPHOTO_USER_USER', '�桼����');

    //---------------------------------------------------------
    // v2.40
    //---------------------------------------------------------
    define('_AM_WEBPHOTO_PLEASE_IMPORT_MYALBUM', 'Myalbum ����ΰ�祤��ݡ��Ȥ�¹Ԥ�������');

    // === define end ===
}
