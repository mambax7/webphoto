<{* item form *}>
<{if $show_waiting }>
<form name="item_list_form" action="<{$xoops_url}>/modules/<{$xoops_dirname}>/admin/index.php" method="post">
    <input type="hidden" name="XOOPS_G_TICKET" value="<{$xoops_g_ticket}>">
    <input type="hidden" name="fct" value="item_manager">
    <input type="hidden" name="op" value="approve">
    <{/if}>

    <{* item table *}>
    <table border="1" cellspacing="0" cellpadding="1" style="font-size: 90%;">
        <tr class="head" align="center">

            <{if $show_waiting }>
                <th width="5px">
                    <input type="checkbox" id="item_list_form_checkall" name="item_list_form_checkall" onclick="xoopsCheckAll('item_list_form','item_list_form_checkall')">
                </th>
            <{else}>
                <th width="10%"><{$lang_item_status}></th>
            <{/if}>

            <th><{$lang_item_id}></th>
            <th width="18%"><{$lang_item_title}></th>
            <th><{$lang_item_kind}></th>
            <th><{$lang_item_ext}></th>
            <th><{$lang_category}></th>

            <{if $show_perm_level }>
                <th><{$lang_item_perm_level}></th>
            <{/if}>

            <th><{$lang_submitter}></th>
            <th><{$lang_player}></th>
            <th><{$lang_item_time_create}></th>
            <th><{$lang_item_time_update}></th>
            <th><{$lang_item_hits}></th>
            <th><{$lang_item_views}></th>
            <th><{$lang_item_rating}></th>
            <th><{$lang_item_votes}></th>
        </tr>

        <{* each line *}>
        <{foreach from=$item_list item=i }>
            <tr class="even" colspan="14">
                <td align="center">

                    <{if $show_waiting }>
                        <input type="checkbox" id="item_list_form_id[]" name="item_list_form_id[]" value="<{$i.item_id}>">
                    <{else}>
                        <a href="<{$i.status_link}>" title="<{$i.status_report}>">
                            <img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/icons/<{$i.status_icon}>" border="0">
                        </a>
                    <{/if}>

                </td>
                <td>
                    <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/admin/index.php?fct=item_manager&amp;op=modify_form&amp;item_id=<{$i.item_id}>" title="<{$lang_edit}>">
                        <img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/icons/edit.png" border="0">
                    </a>
                    <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/admin/index.php?fct=item_manager&amp;op=modify_form&amp;item_id=<{$i.item_id}>" title="<{$lang_edit}>">
                        <{$i.item_id}>
                    </a>
                </td>
                <td width="18%">
                    <a href="<{$i.photo_url|escape}>" title="<{$lang_item_listing}>" target="_blank">
                        <{$i.item_title|escape}>
                    </a>
                </td>
                <td><{$i.kind_options}></td>
                <td>
                    <{if $i.item_ext != "" }>
                        <{$i.item_ext}>
                    <{else}>
                        ---
                    <{/if}>
                </td>
                <td nowrap="nowrap">
                    <{if $i.cat_title === false }>
                        <span style="color: #ff0000;"><{$lang_err_invalid_cat}></span>
                    <{else}>
                        <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/admin/index.php?fct=catmanager&amp;disp=edit&amp;cat_id=<{$i.item_cat_id}>">
                            <{$i.cat_title|escape}>
                        </a>
                    <{/if}>
                </td>

                <{if $show_perm_level }>
                    <td><{$i.perm_level}></td>
                <{/if}>

                <td>
                    <a href="<{$xoops_url}>/userinfo.php?uid=<{$i.item_uid}>">
                        <{$i.uname|escape}>
                    </a>
                </td>
                <td>
                    <{if $i.player_title === false }>
                        ---
                    <{else}>
                        <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/admin/index.php?fct=player_manager&amp;op=modify_form&amp;player_id=<{$i.item_player_id}>&amp;item_id=<{$i.item_id}>"
                           title="<{$lang_player_mod}>">
                            <{$i.player_title|escape}>
                        </a>
                    <{/if}>
                </td>
                <td><{$i.item_time_create|date_format:"%Y-%m-%d"}></td>
                <td><{$i.item_time_update|date_format:"%Y-%m-%d"}></td>
                <td><{$i.item_hits}></td>
                <td><{$i.item_views}></td>
                <td><{$i.item_rating}></td>
                <td>
                    <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/admin/index.php?fct=item_manager&amp;op=vote_stats&amp;item_id=<{$i.item_id}>" title="<{$lang_vote_stats}>">
                        <{$i.item_votes}>
                    </a>
                </td>
            </tr>
        <{/foreach}>
        <{* each line end *}>

        <{if $show_waiting }>
            <tr>

                <{if $show_perm_level }>
                <td colspan="15" align="left">
                    <{else}>
                <td colspan="14" align="left">
                    <{/if}>

                    <{$lang_label_admit}>
                    <input type="submit" value="<{$lang_button_admit}>">
                </td>
            </tr>
        <{/if}>

    </table>
    <{* item table end *}>

    <{if $show_waiting }>
</form>
<{/if}>
<{* item form end *}>
