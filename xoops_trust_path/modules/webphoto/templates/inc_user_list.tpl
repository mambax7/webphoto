<{* $Id: inc_user_list.html,v 1.3 2010/01/26 08:36:07 ohwada Exp $ *}>

<{$lang_user_total}>: <{$total}> <br>

<{* link to group manager *}>
<{if $xoops_cube_legacy }>
<a href="<{$xoops_url}>/modules/user/admin/index.php?action=GroupMember&groupid=<{$group_id}>">
    <{else}>
    <a href="<{$xoops_url}>/modules/system/admin.php?fct=groups&op=modify&g_id=<{$group_id}>">
        <{/if}>

        <img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/user/members.gif" alt="<{$lang_user_assign}>" title="<{$lang_user_assign}>">
        <{$lang_user_assign}></a>

    <br><br>

    <table class="outer">
        <tr>
            <th>&nbsp;</th>
            <th><{$lang_user_uid}></th>
            <th><{$lang_user_uname}></th>
            <th><{$lang_user_name}></th>
            <th><{$lang_user_regdate}></th>
            <th><{$lang_user_lastlogin}></th>
            <th><{$lang_user_posts}></th>
            <th><{$lang_user_level}></th>
            <th><{$lang_user_control}></th>
        </tr>

        <{* user list *}>
        <{foreach from=$user_list item=user }>
            <tr class="<{cycle values=" odd,even
        "}>">
                <td class="user_list_image">
                    <img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/user/user.gif" alt="<{$lang_user_user}>" title="<{$lang_user_user}>">
                </td>
                <td class="user_list_id"><{$user.uid}></td>
                <td class="user_list_title"><{$user.uname_s}></td>
                <td class="user_list_name"><{$user.name_s}></td>
                <td class="user_list_date"><{$user.user_regdate_disp}></td>
                <td class="user_list_date"><{$user.last_login_disp}></td>
                <td class="user_list_number"><{$user.posts}></td>
                <td class="user_list_order"><{$user.level}></td>
                <td class="user_list_control">

                    <{* link to group manager *}>
                    <{if $xoops_cube_legacy }>
                    <a href="<{$xoops_url}>/modules/user/admin/index.php?action=UserEdit&amp;uid=<{$user.uid}>">
                        <{else}>
                        <a href="<{$xoops_url}>/modules/system/admin.php?fct=usersop=modifyUser&amp;uid=<{$user.uid}>">
                            <{/if}>

                            <img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/user/edit.gif" alt="<{$lang_user_edit}>" title="<{$lang_user_edit}>">
                        </a>

                </td>
            </tr>
        <{/foreach}>

    </table>
