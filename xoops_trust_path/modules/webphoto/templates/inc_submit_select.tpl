<{* $Id: inc_submit_select.html,v 1.2 2010/01/25 10:03:07 ohwada Exp $ *}>

<{* === table === *}>
<{if $show_menu_select_bulk || $show_menu_select_file }>
    <table class="outer" cellpadding="4" cellspacing="1" width="100%">
        <tr align="center">
            <th colspan="2"><{$lang_title_submit_select}></th>
        </tr>
        <tr>
            <td class="head">

                <{* === form === *}>
                <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?fct=submit">
                    <{$lang_title_submit_single}></a>

                <{if $show_menu_select_bulk }>
                    |
                    <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?fct=submit&amp;op=bulk_form">
                        <{$lang_title_submit_bulk}></a>
                <{/if}>

                <{if $show_menu_select_file }>
                    |
                    <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?fct=submit&amp;op=file_form">
                        <{$lang_title_submit_file}></a>
                <{/if}>

            </td>
        </tr>
    </table>
<{/if}>
