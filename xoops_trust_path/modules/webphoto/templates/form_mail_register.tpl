<{* $Id: form_mail_register.html,v 1.1 2011/05/10 02:59:15 ohwada Exp $ *}>

<{* === form === *}>
<form name="webphoto_user_register_form" action="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php" method="post">
    <input name="XOOPS_G_TICKET" value="<{$xoops_g_ticket}>" type="hidden">
    <input name="op" value="submit" type="hidden">
    <input name="fct" value="mail_register" type="hidden">

    <{* === table === *}>
    <table class="outer" width="100%" cellpadding="4" cellspacing="1">

        <tr align="center">
            <th colspan="2">
                <{$lang_title_mail_register}>
            </th>
        </tr>

        <tr>
            <td class="head">
                <{$lang_cat_user}>
            </td>
            <td class="odd">
                <{$submitter}>
                <input id="user_uid" name="user_uid" value="<{$user_uid}>" type="hidden">
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_category}>
            </td>
            <td class="odd">
                <{$ele_user_cat_id}>
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_user_email}> 1
            </td>
            <td class="odd">
                <input id="user_email" name="user_email" value="<{$user_email}>" size="50" type="text">
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_user_email}> 2
            </td>
            <td class="odd">
                <input id="user_text1" name="user_text2" value="<{$user_text2}>" size="50" type="text">
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_user_email}> 3
            </td>
            <td class="odd">
                <input id="user_text1" name="user_text3" value="<{$user_text3}>" size="50" type="text">
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_user_email}> 4
            </td>
            <td class="odd">
                <input id="user_text1" name="user_text4" value="<{$user_text4}>" size="50" type="text">
            </td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_user_email}> 5
            </td>
            <td class="odd">
                <input id="user_text1" name="user_text5" value="<{$user_text5}>" size="50" type="text">
            </td>
        </tr>

        <tr>
            <td class="head"></td>
            <td class="odd">
                <input id="submit" name="submit" value="<{$button_submit}>" type="submit">
            </td>
        </tr>

    </table>
    <{* === table end === *}>

</form>
<{* === form end === *}>
