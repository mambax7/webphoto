<{* $Id: form_admin_photo.html,v 1.1 2010/09/19 06:43:11 ohwada Exp $ *}>

<table class="outer" cellpadding="4" cellspacing="1" width="100%">
    <tr align="center">
        <th colspan="2"><{$lang_th_batchupdate}></th>
    </tr>
    <tr>
        <td class="head"><{$lang_photo_title}></td>
        <td class="odd">
            <input type="text" id="new_title" name="new_title" value="" size="50">
        </td>
    </tr>
    <tr>
        <td class="head"><{$lang_photo_place}></td>
        <td class="odd">
            <input type="text" id="new_place" name="new_place" value="" size="50">
        </td>
    </tr>
    <tr>
        <td class="head"><{$lang_photo_equipment}></td>
        <td class="odd">
            <input type="text" id="new_equipment" name="new_equipment" value="" size="50">
        </td>
    </tr>

    <{foreach item=new_text from=$new_text_array}>
        <{if $new_text.show }>
            <tr>
                <td class="head"><{$new_text.title_s}></td>
                <td class="odd">
                    <input type="text" id="<{$new_text.name}>" name="<{$new_text.name}>" value="" size="50">
                </td>
            </tr>
        <{/if}>
    <{/foreach}>

    <tr>
        <td class="head"><{$lang_photo_description}></td>
        <td class="odd">
            <textarea id="new_description" name="new_description" rows="5" cols="50"></textarea>
        </td>
    </tr>
    <tr>
        <td class="head"><{$lang_category}></td>
        <td class="odd">
            <select name="new_cat_id">
                <{$new_cat_id_options}>
            </select>
        </td>
    </tr>
    <tr>
        <td class="head"><{$lang_submitter}></td>
        <td class="odd">
            <select name="new_uid">
                <{$new_uid_options}>
            </select>

            <{if $show_user_list }>
                <br>
                <{foreach from=$user_list item=list }>
                    <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/admin/index.php?fct=photomanager&amp;perpage=<{$perpage}>&amp;cat_id=<{$cat_id}>&amp;pos=<{$pos}>&amp;userstart=<{$list.userstart}>&amp;txt=<{$txt_encode}>&amp;mes=<{$mes_encode}>"><{$list.page}></a>
                <{/foreach}>
            <{/if}>

        </td>
    </tr>
    <tr>
        <td class="head"><{$lang_photo_datetime}></td>
        <td class="odd">
            <inputtype
            ="checkbox" name="new_datetime_checkbox" id="new_datetime_checkbox" value="1" >
            <{$lang_dsc_set_datetime}><br>
            <input type="text" id="new_datetime" name="new_datetime" value="<{$new_datetime}>" size="50">
        </td>
    </tr>
    <tr>
        <td class="head"><{$lang_photo_time_update}></td>
        <td class="odd">
            <input type="checkbox" name="new_time_update_checkbox" id="new_time_update_checkbox" value="1">
            <{$lang_dsc_set_item_time_update}><br>
            <input type="text" id="new_time_update" name="new_time_update" value="<{$new_time_update}>" size="50">
        </td>
    </tr>
    <tr>
        <td class="head"></td>
        <td class="odd">
            <input type="submit" id="update" name="update" value="<{$lang_button_update}>" onclick="return confirm(<{$lang_js_updateconfirm}>)" tabindex="1">
        </td>
    </tr>
</table>

