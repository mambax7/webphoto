<{* $Id: form_redo.html,v 1.2 2009/05/17 08:59:00 ohwada Exp $ *}>

<{* === form === *}>
<form name="webphoto_redo" action="<{$action}>" method="post">
    <input type="hidden" name="XOOPS_G_TICKET" value="<{$xoops_g_ticket}>">
    <input type="hidden" id="fct" name="fct" value="<{$fct}>">
    <input type="hidden" id="op" name="op" value="redo">
    <input type="hidden" id="item_id" name="item_id" value="<{$item_id}>">
    <input type="hidden" id="photo_id" name="photo_id" value="<{$item_id}>">

    <{* === table === *}>
    <table class="outer" cellpadding="4" cellspacing="1" width="100%">
        <tr align="center">
            <th colspan="2"><{$lang_title_video_redo}></th>
        </tr>

        <tr>
            <td class="head"><{$lang_cap_redo_flash}></td>
            <td class="odd">
                <input type="checkbox" name="redo_flash" id="redo_flash" value="1" checked>
                <{$lang_cap_redo_flash}><br>

                <{if $flash_url_s != '' }>
                    <a href="<{$flash_url_s}>" target="_blank">
                        <{$flash_url_s}></a>
                    <br>
                    <input type="submit" id="flash_delete" name="flash_delete" value="<{$lang_delete}>">
                <{/if}>

            </td>
        </tr>

        <{if $cfg_makethumb }>
            <tr>
                <td class="head"><{$lang_cap_redo_thumb}></td>
                <td class="odd">
                    <input type="checkbox" name="redo_thumb" id="redo_thumb" value="1" checked>
                    <{$lang_cap_redo_thumb}>
                </td>
            </tr>
        <{/if}>

        <tr>
            <td class="head"></td>
            <td class="head">
                <input type="submit" id="webphoto_redo_submit" name="submit" value="<{$lang_edit}>">
            </td>
        </tr>
    </table>

</form>
