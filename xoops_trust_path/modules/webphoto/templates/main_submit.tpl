<{* $Id: main_submit.html,v 1.5 2010/02/17 04:34:47 ohwada Exp $ *}>

<a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php">
    <{$xoops_modulename}></a>
&gt;&gt;
<a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?fct=submit">
    <{$lang_title_addphoto}></a>
&gt;
<b><{$lang_title_sub}></b>
<br><br>

<{if $show_uploading}>
    <div id="webphoto_uploading">
        <img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/spinner40.gif" alt="uploading">
        <{$lang_uploading}>
    </div>
<{/if}>

<{* div *}>
<div id="webphoto_submit_form">

    <{if $message != '' }>
        <{* color: red; background-color: lightyellow; border: gray; *}>
        <div id="webphoto_submit_message" style="color:#ff0000; background-color:#ffffeo; border:1px dotted #808080; padding:3px;">
            <{$message}>
        </div>
        <br>
    <{/if}>

    <{if $error != '' }>
        <{* color: red; background-color: lightyellow; border: gray; *}>
        <div id="webphoto_submit_error" style="color:#ff0000; background-color:#ffffeo; border:1px dotted #808080; padding:3px;">
            <{$error}>
        </div>
        <br>
    <{/if}>

    <{if $show_preview }>
        <{include file="db:`$mydirname`_inc_preview.tpl"}>
    <{/if}>

    <{if $show_submit_select }>
        <{include file="db:`$mydirname`_inc_submit_select.tpl"}>
        <br>
    <{/if}>

    <{if $show_form_embed }>
        <{include file="db:`$mydirname`_form_embed.tpl" }>
        <br>
    <{/if}>

    <{if $show_form_editor }>
        <{include file="db:`$mydirname`_form_editor.tpl" }>
        <br>
    <{/if}>

    <{if $show_form_photo }>
        <{include file="db:`$mydirname`_form_photo.tpl" }>
        <br>
    <{/if}>

    <{if $show_form_video_thumb }>
        <{include file="db:`$mydirname`_form_video_thumb.tpl" }>
        <br>
    <{/if}>

    <hr>
    <div class="webphoto_execution_time">execution time : <{$execution_time}> sec</div>
    <{if $is_module_admin && ($memory_usage > 0)}>
        <div class="webphoto_memory_usage">memory usage : <{$memory_usage}> MB</div>
    <{/if}>

    <{if $is_module_admin }>
        <br>
        <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/admin/index.php"><{$lang_goto_admin}></a>
    <{/if}>

</div>
<{* div end *}>
