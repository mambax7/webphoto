<{* $Id: form_admin_invite.html,v 1.1 2009/12/16 13:38:31 ohwada Exp $ *}>

<{* === form === *}>
<form name="webphoto_invite" action="<{$xoops_url}>/modules/<{$xoops_dirname}>/admin/index.php" method="post">
    <input type="hidden" name="XOOPS_G_TICKET" value="<{$xoops_g_ticket}>">
    <input type="hidden" id="fct" name="fct" value="invite">

    <{* === table === *}>
    <table class="outer" cellpadding="4" cellspacing="1" width="100%">
        <tr align="center">
            <th colspan="2"><{$lang_title_invite}></th>
        </tr>

        <tr>
            <td class="head"><{$lang_invite_email}></td>
            <td class="odd">
                <input type="text" id="webphoto_email" name="email" value="<{$email}>" size="45">
            </td>
        </tr>

        <tr>
            <td class="head"><{$lang_invite_name}></td>
            <td class="odd">
                <input type="text" id="webphoto_name" name="name" value="<{$name}>" size="45">
            </td>
        </tr>

        <tr>
            <td class="head"><{$lang_invite_message}></td>
            <td class="odd">
                <textarea id="webphoto_message" name="message" rows="5" cols="45"><{$message}></textarea>
                <br>
                <{$lang_invite_example}>
            </td>
        </tr>

        <tr>
            <td class="head"></td>
            <td class="head">
                <input type="submit" id="webphoto_submit" name="submit" value="<{$lang_invite_submit}>">
            </td>
        </tr>

    </table>
</form>
<{* === form end === *}>
