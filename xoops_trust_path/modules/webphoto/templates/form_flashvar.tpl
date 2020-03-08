<{* $Id: form_flashvar.html,v 1.1 2011/05/10 02:59:15 ohwada Exp $ *}>

<script type="text/javascript" src="<{$xoops_url}>/modules/<{$xoops_dirname}>/libs/color-picker.js"></script>

<{* === form === *}>
<form name="flashform" action="<{$form_action}>" method="post" enctype="multipart/form-data">

    <input name="XOOPS_G_TICKET" value="<{$xoops_g_ticket}>" type="hidden">
    <input name="fct" value="<{$form_fct}>" type="hidden">
    <input name="op" value="<{$form_op}>" type="hidden">
    <input name="flashvar_id" value="<{$flashvar_id}>" type="hidden">
    <input name="item_id" value="<{$flashvar_item_id}>" type="hidden">
    <input name="photo_id" value="<{$flashvar_item_id}>" type="hidden">
    <input name="flashvar_item_id" value="<{$flashvar_item_id}>" type="hidden">
    <input name="max_file_size" value="<{$cfg_fsize}>" type="hidden">
    <input name="fieldCounter" value="1" type="hidden">

    <{* === table === *}>
    <table class="outer" width="100%" cellpadding="4" cellspacing="1">

        <tr align="center">
            <th colspan="2">
                <{$lang_flashvars_form}>
            </th>
        </tr>

        <tr>
            <td class="head">
                <{$lang_flashvars_list}>
            </td>
            <td class="odd">
                <a href="http://www.longtailvideo.com/support/jw-player/jw-player-for-flash-v5/12536/configuration-options" target="_blank">
                    JW Player
                </a> ,
                <a href="http://developer.longtailvideo.com/trac/wiki/ImageRotatorVars" target="_blank">
                    ImageRotator
                </a>
            </td>
        </tr>

        <{if $is_module_admin }>
            <tr>
                <td class="head">
                    <{$lang_flashvar_id}>
                </td>
                <td class="odd">
                    <{$flashvar_id}>
                </td>
            </tr>
            <tr>
                <td class="head">
                    <{$lang_flashvar_item_id}>
                </td>
                <td class="odd">
                    <{$flashvar_item_id}>
                </td>
            </tr>
        <{/if}>

        <tr align="center">
            <td class="head" colspan="2">
                common
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_flashvar_height}>
                <br><br>
                <span style="font-size: 90%; font-weight: 500;">
<{$lang_flashvar_height_dsc}>
<br>
<{$lang_flashvar_display_default}>
</span>
            </td>
            <td class="odd">
                <input id="flashvar_height" name="flashvar_height" value="<{$flashvar_height}>" size="4" type="text">
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_flashvar_width}>
                <br><br>
                <span style="font-size: 90%; font-weight: 500;">
<{$lang_flashvar_width_dsc}>
<br>
<{$lang_flashvar_display_default}>
</span>
            </td>
            <td class="odd">
                <input id="flashvar_width" name="flashvar_width" value="<{$flashvar_width}>" size="4" type="text">
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_flashvar_screencolor}>
                <br><br>
                <span style="font-size: 90%; font-weight: 500;">
<{$lang_flashvar_screencolor_dsc}>
<br>
<{$lang_flashvar_color_default}>
</span>
            </td>
            <td class="odd">
                <input id="flashvar_screencolor" name="flashvar_screencolor" value="<{$flashvar_screencolor}>" size="10" type="text">
                <input id="flashvar_screencolor_select" name="flashvar_screencolor_select" value="<{$lang_button_color_pickup}>"
                       onclick="return TCP.popup('<{$xoops_url}>/modules/<{$xoops_dirname}>/libs/', document.getElementById('flashvar_screencolor') )" type="reset">
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_flashvar_backcolor}>
                <br><br>
                <span style="font-size: 90%; font-weight: 500;">
<{$lang_flashvar_backcolor_dsc}>
<br>
<{$lang_flashvar_color_default}>
</span>
            </td>
            <td class="odd">
                <input id="flashvar_backcolor" name="flashvar_backcolor" value="<{$flashvar_backcolor}>" size="10" type="text">
                <input id="flashvar_backcolor_select" name="flashvar_backcolor_select" value="<{$lang_button_color_pickup}>"
                       onclick="return TCP.popup('<{$xoops_url}>/modules/<{$xoops_dirname}>/libs/', document.getElementById('flashvar_backcolor') )" type="reset">
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_flashvar_frontcolor}>
                <br><br>
                <span style="font-size: 90%; font-weight: 500;">
<{$lang_flashvar_frontcolor_dsc}>
<br>
<{$lang_flashvar_color_default}>
</span>
            </td>
            <td class="odd">
                <input id="flashvar_frontcolor" name="flashvar_frontcolor" value="<{$flashvar_frontcolor}>" size="10" type="text">
                <input id="flashvar_frontcolor_select" name="flashvar_frontcolor_select" value="<{$lang_button_color_pickup}>"
                       onclick="return TCP.popup('<{$xoops_url}>/modules/<{$xoops_dirname}>/libs/', document.getElementById('flashvar_frontcolor') )" type="reset">
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_flashvar_lightcolor}>
                <br><br>
                <span style="font-size: 90%; font-weight: 500;">
<{$lang_flashvar_lightcolor_dsc}>
<br>
<{$lang_flashvar_color_default}>
</span>
            </td>
            <td class="odd">
                <input id="flashvar_lightcolor" name="flashvar_lightcolor" value="<{$flashvar_lightcolor}>" size="10" style="" type="text">
                <input id="flashvar_lightcolor_select" name="flashvar_lightcolor_select" value="<{$lang_button_color_pickup}>"
                       onclick="return TCP.popup('<{$xoops_url}>/modules/<{$xoops_dirname}>/libs/', document.getElementById('flashvar_lightcolor') )" type="reset">
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_flashvar_shuffle}>
                <br><br>
                <span style="font-size: 90%; font-weight: 500;">
<{$lang_flashvar_shuffle_dsc}>
</span>
            </td>
            <td class="odd">
                <input name="flashvar_shuffle" value="1" type="radio"
                       <{if $flashvar_shuffle == 1}>checked<{/if}> >
                <{$lang_yes}>
                <input name="flashvar_shuffle" value="0" type="radio"
                       <{if $flashvar_shuffle != 1}>checked<{/if}> >
                <{$lang_no}>
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_flashvar_volume}>
                <br><br>
                <span style="font-size: 90%; font-weight: 500;">
<{$lang_flashvar_volume_dsc}>
</span>
            </td>
            <td class="odd">
                <input id="flashvar_volume" name="flashvar_volume" value="<{$flashvar_volume}>" size="50" type="text">
            </td>
        </tr>

        <tr align="center">
            <td class="head" colspan="2">
                JW Player
            </td>
        </tr>

        <{* Playlist Properties *}>

        <tr>
            <td class="head">
                <{$lang_flashvar_start}>
                <br><br>
                <span style="font-size: 90%; font-weight: 500;">
<{$lang_flashvar_start_dsc}>
</span>
            </td>
            <td class="odd">
                <input id="flashvar_start" name="flashvar_start" value="<{$flashvar_start}>" size="50" type="text">
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_flashvar_duration}>
                <br><br>
                <span style="font-size: 90%; font-weight: 500;">
<{$lang_flashvar_duration_dsc}>
</span>
            </td>
            <td class="odd">
                <input id="flashvar_duration" name="flashvar_duration" value="<{$flashvar_duration}>" size="50" type="text">
            </td>
        </tr>

        <{* Layout *}>

        <tr>
            <td class="head">
                <{$lang_flashvar_dock}>
                <br><br>
                <span style="font-size: 90%; font-weight: 500;">
<{$lang_flashvar_dock_dsc}>
</span>
            </td>
            <td class="odd">
                <input name="flashvar_dock" value="1" type="radio"
                       <{if $flashvar_dock == 1}>checked<{/if}> >
                <{$lang_yes}>
                <input name="flashvar_dock" value="0" type="radio"
                       <{if $flashvar_dock != 1}>checked<{/if}> >
                <{$lang_no}>
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_flashvar_icons}>
                <br><br>
                <span style="font-size: 90%; font-weight: 500;">
<{$lang_flashvar_icons_dsc}>
</span>
            </td>
            <td class="odd">
                <input name="flashvar_icons" value="1" type="radio"
                       <{if $flashvar_icons == 1}>checked<{/if}> >
                <{$lang_yes}>
                <input name="flashvar_icons" value="0" type="radio"
                       <{if $flashvar_icons != 1}>checked<{/if}> >
                <{$lang_no}>
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_flashvar_controlbar_position}>
                <br><br>
                <span style="font-size: 90%; font-weight: 500;">
<{$lang_flashvar_controlbar_position_dsc}>
</span>
            </td>
            <td class="odd">
                <select id="flashvar_controlbar_position" name="flashvar_controlbar_position" size="1">
                    <{foreach from=$flashvar_controlbar_position_options key=v item=k }>
                        <option value="<{$v}>"
                                <{if $v == $flashvar_controlbar_position }>selected="selected"<{/if}> ><{$k}></option>
                    <{/foreach}>
                </select>
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_flashvar_controlbar_idlehide}>
                <br><br>
                <span style="font-size: 90%; font-weight: 500;">
<{$lang_flashvar_controlbar_idlehide_dsc}>
</span>
            </td>
            <td class="odd">
                <input name="flashvar_controlbar_idlehide" value="1" type="radio"
                       <{if $flashvar_controlbar_idlehide == 1}>checked<{/if}> >
                <{$lang_yes}>
                <input name="flashvar_controlbar_idlehide" value="0" type="radio"
                       <{if $flashvar_controlbar_idlehide != 1}>checked<{/if}> >
                <{$lang_no}>
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_flashvar_display_showmute}>
                <br><br>
                <span style="font-size: 90%; font-weight: 500;">
<{$lang_flashvar_display_showmute_dsc}>
</span>
            </td>
            <td class="odd">
                <input name="flashvar_display_showmute" value="1" type="radio"
                       <{if $flashvar_display_showmute == 1}>checked<{/if}> >
                <{$lang_yes}>
                <input name="flashvar_display_showmute" value="0" type="radio"
                       <{if $flashvar_display_showmute != 1}>checked<{/if}> >
                <{$lang_no}>
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_flashvar_playlist_position}>
                <br><br>
                <span style="font-size: 90%; font-weight: 500;">
<{$lang_flashvar_playlist_position_dsc}>
</span>
            </td>
            <td class="odd">
                <select id="flashvar_playlist_position" name="flashvar_playlist_position" size="1">
                    <{foreach from=$flashvar_playlist_position_options key=v item=k }>
                        <option value="<{$v}>"
                                <{if $v == $flashvar_playlist_position }>selected="selected"<{/if}> ><{$k}></option>
                    <{/foreach}>
                </select>
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_flashvar_playlist_size}>
                <br><br>
                <span style="font-size: 90%; font-weight: 500;">
<{$lang_flashvar_playlist_size_dsc}>
</span>
            </td>
            <td class="odd">
                <input id="flashvar_playlist_size" name="flashvar_playlist_size" value="<{$flashvar_playlist_size}>" size="50" type="text">
            </td>
        </tr>


        <tr>
            <td class="head">
                <{$lang_flashvar_skin}>
                <br><br>
                <span style="font-size: 90%; font-weight: 500;">
<{$lang_flashvar_skin_dsc}>
<br>
<a href="http://www.longtailvideo.com/addons/skins" target="_blank">
AddOns | Skins | LongTail Video
</a>
</span>
            </td>
            <td class="odd">
                <input id="flashvar_skin" name="flashvar_skin" value="<{$flashvar_skin}>" size="50" type="text">
            </td>
        </tr>

        <{* Behavior *}>

        <tr>
            <td class="head">
                <{$lang_flashvar_autostart}>
                <br><br>
                <span style="font-size: 90%; font-weight: 500;">
<{$lang_flashvar_autostart_dsc}>
</span>
            </td>
            <td class="odd">
                <input name="flashvar_autostart" value="1" type="radio"
                       <{if $flashvar_autostart == 1}>checked<{/if}> >
                <{$lang_yes}>
                <input name="flashvar_autostart" value="0" type="radio"
                       <{if $flashvar_autostart != 1}>checked<{/if}> >
                <{$lang_no}>
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_flashvar_bufferlength}>
                <br><br>
                <span style="font-size: 90%; font-weight: 500;">
<{$lang_flashvar_bufferlength_dsc}>
</span>
            </td>
            <td class="odd">
                <input id="flashvar_bufferlength" name="flashvar_bufferlength" value="<{$flashvar_bufferlength}>" size="50" type="text">
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_flashvar_mute}>
                <br><br>
                <span style="font-size: 90%; font-weight: 500;">
<{$lang_flashvar_mute_dsc}>
</span>
            </td>
            <td class="odd">
                <input name="flashvar_mute" value="1" type="radio"
                       <{if $flashvar_mute == 1}>checked<{/if}> >
                <{$lang_yes}>
                <input name="flashvar_mute" value="0" type="radio"
                       <{if $flashvar_mute != 1}>checked<{/if}> >
                <{$lang_no}>
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_flashvar_player_repeat}>
                <br><br>
                <span style="font-size: 90%; font-weight: 500;">
<{$lang_flashvar_player_repeat_dsc}>
</span>
            </td>
            <td class="odd">
                <select id="flashvar_player_repeat" name="flashvar_player_repeat" size="1">
                    <{foreach from=$flashvar_player_repeat_options key=v item=k }>
                        <option value="<{$v}>"
                                <{if $v == $flashvar_player_repeat }>selected="selected"<{/if}> ><{$k}></option>
                    <{/foreach}>
                </select>
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_flashvar_stretching}>
                <br><br>
                <span style="font-size: 90%; font-weight: 500;">
<{$lang_flashvar_stretching_dsc}>
</span>
            </td>
            <td class="odd">
                <select id="flashvar_stretching" name="flashvar_stretching" size="1">
                    <{foreach from=$flashvar_stretching_options key=v item=k }>
                        <option value="<{$v}>"
                                <{if $v == $flashvar_stretching }>selected="selected"<{/if}> ><{$k}></option>
                    <{/foreach}>
                </select>
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_flashvar_smoothing}>
                <br><br>
                <span style="font-size: 90%; font-weight: 500;">
<{$lang_flashvar_smoothing_dsc}>
</span>
            </td>
            <td class="odd">
                <input name="flashvar_smoothing" value="1" type="radio"
                       <{if $flashvar_smoothing == 1}>checked<{/if}> >
                <{$lang_yes}>
                <input name="flashvar_smoothing" value="0" type="radio"
                       <{if $flashvar_smoothing != 1}>checked<{/if}> >
                <{$lang_no}>
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_flashvar_item}>
                <br><br>
                <span style="font-size: 90%; font-weight: 500;">
<{$lang_flashvar_item_dsc}>
</span>
            </td>
            <td class="odd">
                <input id="flashvar_item" name="flashvar_item" value="<{$flashvar_item}>" size="50" type="text">
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_flashvar_plugins}>
                <br><br>
                <span style="font-size: 90%; font-weight: 500;">
<{$lang_flashvar_plugins_dsc}>
<br>
<a href="http://www.longtailvideo.com/addons/plugins" target="_blank">
AddOns | Plugins | LongTail Video
</a>
</span>
            </td>
            <td class="odd">
                <input id="flashvar_plugins" name="flashvar_plugins" value="<{$flashvar_plugins}>" size="50" type="text">
            </td>
        </tr>

        <tr align="center">
            <td class="head" colspan="2">
                ImageRotator
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_flashvar_overstretch}>
                <br><br>
                <span style="font-size: 90%; font-weight: 500;">
<{$lang_flashvar_overstretch_dsc}>
</span>
            </td>
            <td class="odd">
                <select id="flashvar_overstretch" name="flashvar_overstretch" size="1">
                    <{foreach from=$flashvar_overstretch_options key=v item=k }>
                        <option value="<{$v}>"
                                <{if $v == $flashvar_overstretch }>selected="selected"<{/if}> ><{$k}></option>
                    <{/foreach}>
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_flashvar_showicons}>
                <br><br>
                <span style="font-size: 90%; font-weight: 500;">
<{$lang_flashvar_showicons_dsc}>
</span>
            </td>
            <td class="odd">
                <input name="flashvar_showicons" value="1" type="radio"
                       <{if $flashvar_showicons == 1}>checked<{/if}> >
                <{$lang_yes}>
                <input name="flashvar_showicons" value="0" type="radio"
                       <{if $flashvar_showicons != 1}>checked<{/if}> >
                <{$lang_no}>
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_flashvar_shownavigation}>
                <br><br>
                <span style="font-size: 90%; font-weight: 500;">
<{$lang_flashvar_shownavigation_dsc}>
</span>
            </td>
            <td class="odd">
                <input name="flashvar_shownavigation" value="1" type="radio"
                       <{if $flashvar_shownavigation == 1}>checked<{/if}> >
                <{$lang_yes}>
                <input name="flashvar_shownavigation" value="0" type="radio"
                       <{if $flashvar_shownavigation != 1}>checked<{/if}> >
                <{$lang_no}>
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_flashvar_usefullscreen}>
                <br><br>
                <span style="font-size: 90%; font-weight: 500;">
<{$lang_flashvar_usefullscreen_dsc}>
</span>
            </td>
            <td class="odd">
                <input name="flashvar_usefullscreen" value="1" type="radio"
                       <{if $flashvar_usefullscreen == 1}>checked<{/if}> >
                <{$lang_yes}>
                <input name="flashvar_usefullscreen" value="0" type="radio"
                       <{if $flashvar_usefullscreen != 1}>checked<{/if}> >
                <{$lang_no}>
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_flashvar_transition}>
                <br><br>
                <span style="font-size: 90%; font-weight: 500;">
<{$lang_flashvar_transition_dsc}>
</span>
            </td>
            <td class="odd">
                <select id="flashvar_transition" name="flashvar_transition" size="1">
                    <{foreach from=$flashvar_transition_options key=v item=k }>
                        <option value="<{$v}>"
                                <{if $v == $flashvar_transition }>selected="selected"<{/if}> ><{$k}></option>
                    <{/foreach}>
                </select>
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_flashvar_audio}>
                <br><br>
                <span style="font-size: 90%; font-weight: 500;">
<{$lang_flashvar_audio_dsc}>
</span>
            </td>
            <td class="odd">
                <input id="flashvar_audio" name="flashvar_audio" value="<{$flashvar_audio}>" size="50" type="text">
            </td>
        </tr

        <tr>
            <td class="head">
                <{$lang_flashvar_linkfromdisplay}>
                <br><br>
                <span style="font-size: 90%; font-weight: 500;">
<{$lang_flashvar_linkfromdisplay_dsc}>
</span>
            </td>
            <td class="odd">
                <input name="flashvar_linkfromdisplay" value="1" type="radio"
                       <{if $flashvar_linkfromdisplay == 1}>checked<{/if}> >
                <{$lang_yes}>
                <input name="flashvar_linkfromdisplay" value="0" type="radio"
                       <{if $flashvar_linkfromdisplay != 1}>checked<{/if}> >
                <{$lang_no}>
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_flashvar_linktarget}>
                <br><br>
                <span style="font-size: 90%; font-weight: 500;">
<{$lang_flashvar_linktarget_dsc}>
</span>
            </td>
            <td class="odd">
                <select id="flashvar_linktarget" name="flashvar_linktarget" size="1">
                    <{foreach from=$flashvar_linktarget_options key=v item=k }>
                        <option value="<{$v}>"
                                <{if $v == $flashvar_linktarget }>selected="selected"<{/if}> ><{$k}></option>
                    <{/foreach}>
                </select>
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_flashvar_repeat}>
                <br><br>
                <span style="font-size: 90%; font-weight: 500;">
<{$lang_flashvar_repeat_dsc}>
</span>
            </td>
            <td class="odd">
                <input name="flashvar_repeat" value="1" type="radio"
                       <{if $flashvar_repeat == 1}>checked<{/if}> >
                <{$lang_yes}>
                <input name="flashvar_repeat" value="0" type="radio"
                       <{if $flashvar_repeat != 1}>checked<{/if}> >
                <{$lang_no}>
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_flashvar_rotatetime}>
                <br><br>
                <span style="font-size: 90%; font-weight: 500;">
<{$lang_flashvar_rotatetime_dsc}>
</span>
            </td>
            <td class="odd">
                <input id="flashvar_rotatetime" name="flashvar_rotatetime" value="<{$flashvar_rotatetime}>" size="50" type="text">
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_flashvars_logo_upload}>
                <br><br>
                <span style="font-size: 90%; font-weight: 500;">
<{$lang_cap_maxpixel}>
                    <{$cfg_logo_width}> x <{$cfg_logo_width}>
<br>
<{$lang_dsc_pixcel_resize}>
</span>
            </td>
            <td class="odd">
                <input id="file_plogo" name="file_plogo" size="50" type="file">
                <input name="xoops_upload_file[]" value="file_plogo" type="hidden">
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_flashvars_logo_select}>
                <br><br>
                <span style="font-size: 90%; font-weight: 500;">
<{$lang_flashvars_logo_dsc}>
<br>
<{$xoops_path}>/uploads/webphoto/logos
</span>
            </td>
            <td class="odd">
                <select id="flashvar_logo" name="flashvar_logo" size="1" onchange="showImgSelected('plogo', 'flashvar_logo', '/uploads/webphoto/logos', '', '<{$xoops_url}>')">
                    <{foreach from=$flashvar_logo_options key=v item=k }>
                        <option value="<{$v}>"
                                <{if $v == $flashvar_logo }>selected="selected"<{/if}> ><{$k}></option>
                    <{/foreach}>
                </select>

                <{if $show_logo }>
                    <div style="padding: 8px;">
                        <img src="<{$logo_url}>" name="plogo" id="plogo" alt="plogo">
                    </div>
                <{/if}>

            </td>
        </tr>

        <{if $show_captcha }>
            <tr>
                <td class="head">
                    <{$cap_captcha}>
                </td>
                <td class="odd">
                    <{$ele_captcha}>
                </td>
            </tr>
        <{/if}>

        <tr>
            <td class="head"></td>
            <td class="head">
                <{if $flashvar_id > 0 }>
                    <input id="submit" name="submit" value="<{$lang_edit}>" type="submit">
                    <input id="restore" name="restore" value="<{$lang_button_restore}>" type="submit">
                <{else}>
                    <input id="submit" name="submit" value="<{$lang_add}>" type="submit">
                <{/if}>
            </td>
        </tr>

    </table>
    <{* === table end === *}>

</form>
<{* === form end === *}>
