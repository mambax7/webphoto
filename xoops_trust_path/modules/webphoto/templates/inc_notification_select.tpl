<{* $Id: inc_notification_select.html,v 1.1.1.1 2008/06/21 12:22:18 ohwada Exp $ *}>
<{* base on system_notification_select.html *}>

<form name="notification_select" action="<{$notification_select.target_page}>" method="post">
    <h4 style="text-align:center;"><{$smarty.const._NOT_ACTIVENOTIFICATIONS}></h4>
    <input type="hidden" name="not_redirect" value="<{$notification_select.redirect_script}>">

    <{* for XOOPS 2.0.18 *}>
    <input type="hidden" name="XOOPS_TOKEN_REQUEST" value="<{$notification_select.token}>">

    <table class="outer">
        <tr>
            <th colspan="3"><{$smarty.const._NOT_NOTIFICATIONOPTIONS}></th>
        </tr>
        <tr>
            <td class="head"><{$smarty.const._NOT_CATEGORY}></td>
            <td class="head">
                <input name="allbox" id="allbox" onclick="xoopsCheckAll('notification_select','allbox');" type="checkbox" value="<{$smarty.const._NOT_CHECKALL}>">
            </td>
            <td class="head"><{$smarty.const._NOT_EVENTS}></td>
        </tr>
        <{foreach name=outer item=category from=$notification_select.categories}>
            <{foreach name=inner item=event from=$category.events}>
                <tr>
                    <{if $smarty.foreach.inner.first}>
                        <td class="even" rowspan="<{$smarty.foreach.inner.total}>"><{$category.title}></td>
                    <{/if}>
                    <td class="odd">
                        <{counter assign=index}>
                        <input type="hidden" name="not_list[<{$index}>][params]" value="<{$category.name}>,<{$category.itemid}>,<{$event.name}>">
                        <input type="checkbox" id="not_list[]" name="not_list[<{$index}>][status]" value="1" <{if $event.subscribed}>checked<{/if}> >
                    </td>
                    <td class="odd"><{$event.caption}></td>
                </tr>
            <{/foreach}>
        <{/foreach}>
        <tr>
            <td class="foot" colspan="3" align="center">
                <input type="submit" name="not_submit" value="<{$smarty.const._NOT_UPDATENOW}>">
            </td>
        </tr>
    </table>
    <div align="center">
        <{$smarty.const._NOT_NOTIFICATIONMETHODIS}>:&nbsp;<{$notification_select.user_method}>&nbsp;&nbsp;
        [<a href="<{$xoops_url}>/edituser.php?uid=<{$xoops_userid}>"><{$smarty.const._NOT_CHANGE}></a>]
    </div>
</form>
