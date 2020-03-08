<html>
<head>
    <{* $Id: main_submit_imagemanager.html,v 1.1 2009/04/19 11:41:45 ohwada Exp $ *}>
    <meta http-equiv="content-type" content="text/html; charset=<{$xoops_charset}>">
    <link rel="stylesheet" type="text/css" media="all" href="<{$xoops_url}>/xoops.css">
    <link rel="stylesheet" type="text/css" media="all" href="<{$xoops_url}>/modules/system/style.css">
    <{if $xoops_themecss != ""}>
        <link rel="stylesheet" type="text/css" media="screen" href="<{$xoops_themecss}>">
    <{/if}>
    <link rel="stylesheet" type="text/css" media="all" href="<{$xoops_url}>/modules/<{$xoops_dirname}>/libs/default.css">
    <title><{$lang_title_photoupload}></title>
</head>
<body>
<div class="webphoto_imagemanager">

    <{* === form === *}>
    <form name="webphoto_imagemanager" action="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="XOOPS_G_TICKET" value="<{$xoops_g_ticket}>">
        <input type="hidden" id="fct" name="fct" value="submit_imagemanager">
        <input type="hidden" id="op" name="op" value="submit">
        <input type="hidden" id="max_file_size" name="max_file_size" value="<{$max_file_size}>">
        <input type="hidden" id="fieldCounter" name="fieldCounter" value="1">

        <{* === table === *}>
        <table class="outer" cellpadding="4" cellspacing="1" width="100%">
            <tr align="center">
                <th colspan="2"><{$lang_title_photoupload}></th>
            </tr>

            <tr>
                <td class="head"><{$lang_cap_maxpixel}></td>
                <td class="odd"><{$ele_maxpixel}></td>
            </tr>
            <tr>
                <td class="head"><{$lang_cap_maxsize}></td>
                <td class="odd"><{$ele_maxsize}></td>
            </tr>
            <tr>
                <td class="head"><{$lang_cap_allowed_exts}></td>
                <td class="odd"><{$ele_allowed_exts}></td>
            </tr>

            <{* item cat id *}>
            <tr>
                <td class="head"><{$lang_category}></td>
                <td class="odd">
                    <select id="item_cat_id" name="item_cat_id" size="1">
                        <{$item_cat_id_options}>
                    </select>
                </td>
            </tr>

            <{* item title *}>
            <tr>
                <td class="head"><{$lang_item_title}></td>
                <td class="odd">
                    <input tyep="text" id="item_title" name="item_title" value="<{$item_title_s}>" size="50">
                    <br><{$lang_dsc_title_blank}>
                </td>
            </tr>

            <tr>
                <td class="head"><{$lang_item_description}></td>
                <td class="odd"><{$ele_item_description}></td>
            </tr>

            <{* file photo *}>
            <tr>
                <td class="head"><{$lang_cap_photo_select}></td>
                <td class="odd">
                    <input type="hidden" id="xoops_upload_file[]" name="xoops_upload_file[]" value="file_photo">
                    <input type="file" id="file_photo" name="file_photo" size="50">
                </td>
            </tr>

            <tr>
                <td class="head"></td>
                <td class="head">
                    <input type="submit" id="add" name="add" value="<{$lang_add}>">
                </td>
            </tr>
        </table>
    </form>

    <div class="webphoto_close">
        <input value="<{$lang_close}>" type="button" onclick="javascript:window.close();">
    </div>
</div>
</body>
</html>
