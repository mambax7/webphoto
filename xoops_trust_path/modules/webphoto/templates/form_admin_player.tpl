<{* $Id: form_admin_player.html,v 1.2 2011/05/10 02:56:39 ohwada Exp $ *}>

<{* === form === *}>
<form name="playerform" action="<{$xoops_url}>/modules/<{$xoops_dirname}>/admin/index.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="XOOPS_G_TICKET" value="<{$xoops_g_ticket}>">
    <input type="hidden" id="fct" name="fct" value="player_manager">
    <input type="hidden" id="op" name="op" value="<{$op}>">
    <input type="hidden" id="player_id" name="player_id" value="<{$player_id}>">
    <input type="hidden" id="item_id" name="item_id" value="<{$item_id}>">

    <{if $show_color_style_hidden}>
        <input type="hidden" id="player_screencolor" name="player_screencolor" value="<{$player_screencolor}>">
        <input type="hidden" id="player_backcolor" name="player_backcolor" value="<{$player_backcolor}>">
        <input type="hidden" id="player_frontcolor" name="player_frontcolor" value="<{$player_frontcolor}>">
        <input type="hidden" id="player_lightcolor" name="player_lightcolor" value="<{$player_lightcolor}>">
    <{/if}>

    <{* === table === *}>
    <table class="outer" cellpadding="4" cellspacing="1" width="100%">
        <tr align="center">
            <th colspan="2"><{$title}></th>
        </tr>
        <tr>
            <td class="head"><{$lang_player_id}></td>
            <td class="odd">
                <{if $player_id > 0 }>
                    <{$player_id}>
                <{else}>
                    ---
                <{/if}>
            </td>
        </tr>
        <tr>
            <td class="head"><{$lang_player_style}></td>
            <td class="odd">
                <select id="player_style" name="player_style" size="1"
                        onchange="window.location='<{$xoops_url}>/modules/<{$xoops_dirname}>/admin/index.php?fct=player_manager&amp;op=<{$op_player_style}>&amp;player_id=<{$player_id}>&amp;style='+this.value">
                    <{$player_style_options}>
                </select>
            </td>
        </tr>
        <tr>
            <td class="head"><{$lang_player_title}></td>
            <td class="odd">
                <input type="text" id="player_title" name="player_title" value="<{$player_title}>" size="20">
            </td>
        </tr>

        <{if $show_color_style}>
            <tr>
                <td class="head"><{$lang_player_screencolor}><br><br>
                    <span style="font-size: 90%; font-weight: 500;"><{$lang_flashvar_screencolor_dsc}></span>
                </td>
                <td class="odd">
                    <input type="text" id="player_screencolor" name="player_screencolor" value="<{$player_screencolor}>" size="10" style="<{$player_screencolor_style}>">
                    <input type="reset" id="player_screencolor_select" name="player_screencolor_select" value="<{$lang_button_color_pickup}>"
                           onclick="return TCP.popup('<{$xoops_url}>/modules/<{$xoops_dirname}>/libs/', document.getElementById('player_screencolor') )">
                </td>
            </tr>
            <tr>
                <td class="head"><{$lang_player_backcolor}><br><br>
                    <span style="font-size: 90%; font-weight: 500;"><{$lang_flashvar_backcolor_dsc}></span>
                </td>
                <td class="odd">
                    <input type="text" id="player_backcolor" name="player_backcolor" value="<{$player_backcolor}>" size="10" style="<{$player_backcolor_style}>">
                    <input type="reset" id="player_backcolor_select" name="player_backcolor_select" value="<{$lang_button_color_pickup}>"
                           onclick="return TCP.popup('<{$xoops_url}>/modules/<{$xoops_dirname}>/libs/', document.getElementById('player_backcolor') )">
                </td>
            </tr>
            <tr>
                <td class="head"><{$lang_player_frontcolor}><br><br>
                    <span style="font-size: 90%; font-weight: 500;"><{$lang_flashvar_frontcolor_dsc}></span>
                </td>
                <td class="odd">
                    <input type="text" id="player_frontcolor" name="player_frontcolor" value="<{$player_frontcolor}>" size="10" style="<{$player_frontcolor_style}>">
                    <input type="reset" id="player_frontcolor_select" name="player_frontcolor_select" value="<{$lang_button_color_pickup}>"
                           onclick="return TCP.popup('<{$xoops_url}>/modules/<{$xoops_dirname}>/libs/', document.getElementById('player_frontcolor') )">
                </td>
            </tr>
            <tr>
                <td class="head"><{$lang_player_lightcolor}><br><br>
                    <span style="font-size: 90%; font-weight: 500;"><{$lang_flashvar_lightcolor_dsc}></span>
                </td>
                <td class="odd">
                    <input type="text" id="player_lightcolor" name="player_lightcolor" value="<{$player_lightcolor}>" size="10" style="<{$player_lightcolor_style}>">
                    <input type="reset" id="player_lightcolor_select" name="player_lightcolor_select" value="<{$lang_button_color_pickup}>"
                           onclick="return TCP.popup('<{$xoops_url}>/modules/<{$xoops_dirname}>/libs/', document.getElementById('player_lightcolor') )">
                </td>
            </tr>
        <{/if}>
        <tr>
            <td class="head"><{$lang_player_width}><br><br>
                <span style="font-size: 90%; font-weight: 500;"><{$lang_flashvar_width_dsc}></span>
            </td>
            <td class="odd">
                <input type="text" id="player_width" name="player_width" value="<{$player_width}>" size="4">
            </td>
        </tr>
        <tr>
            <td class="head"><{$lang_player_height}><br><br>
                <span style="font-size: 90%; font-weight: 500;"><{$lang_flashvar_height_dsc}></span>
            </td>
            <td class="odd">
                <input type="text" id="player_height" name="player_height" value="<{$player_height}>" size="4">
            </td>
        </tr>

        <tr>
            <td class="head"></td>
            <td class="head">
                <input type="submit" id="submit" name="submit" value="<{$submit}>">
            </td>
        </tr>
    </table>
    <{* === table end === *}>

</form>
<{* === form end === *}>
