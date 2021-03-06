<{* $Id: main_help.html,v 1.6 2009/04/11 14:23:35 ohwada Exp $ *}>

<a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php">
    <{$xoops_modulename}></a> &gt;&gt;
<b><{$lang_title_help}></b>
<br><br>

<{* === menue === *}>
<{include file="db:`$mydirname`_inc_menu.tpl" }>

<h3><{$lang_title_help}></h3>
<{$lang_help_dsc}>
<br>

<div class="webphoto_help_subtitle">
    <{$lang_help_piclens_title}>
</div>

<{$lang_help_piclens_dsc}>
<br>

<img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/help/piclens_on.png" border="0">
<img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/help/piclens_off.png" border="0">
<br><br>

<div class="webphoto_help_subtitle">
    <{$lang_help_mediarssslideshow_title}>
</div>

<{$lang_help_mediarssslideshow_dsc}>

<{if $cfg_use_pathinfo }>
<a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php/rss/random/" target="_blank">
    <{$xoops_url}>/modules/<{$xoops_dirname}>/index.php/rss/random/
    <{else}>
    <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?fct=rss&amp;mode=random" target="_blank">
        <{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?fct=rss&amp;mode=random
        <{/if}>

    </a><br>

    <div class="webphoto_help_subtitle">
        <{$lang_help_mobile_title}>
    </div>

    <{$lang_help_mobile_dsc}><br><br>
    <{$lang_help_mobile_text}><br><br>

    <{if $show_help_mail }>
        <div class="webphoto_help_subtitle">
            <{$lang_help_mail_title}>
        </div>
        <{$lang_help_mail_dsc}>
        <br>
        <br>
        <{if $lang_help_mail_perm }>
            <{$lang_help_mail_perm}>
            <br>
            <br>
        <{/if}>
        <{if $show_help_mail_text }>
            <{$lang_help_mail_text}>
        <{/if}>
    <{/if}>

    <{if $show_help_file }>
        <div class="webphoto_help_subtitle">
            <{$lang_help_file_title}>
        </div>
        <{$lang_help_file_dsc}>
        <br>
        <br>
        <{if $lang_help_file_perm }>
            <{$lang_help_file_perm}>
            <br>
            <br>
        <{/if}>
        <{if $show_help_file_text }>
            <{$lang_help_file_text_1}>
            <br>
            <br>
            <{$lang_help_file_text_2}>
        <{/if}>
    <{/if}>

    <{if $is_module_admin }>
        <hr>
        <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/admin/index.php"><{$lang_goto_admin}></a>
    <{/if}>
