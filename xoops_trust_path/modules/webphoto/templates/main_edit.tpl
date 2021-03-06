<{* $Id: main_edit.html,v 1.3 2010/02/17 04:34:47 ohwada Exp $ *}>

<a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php">
    <{$xoops_modulename}></a>
&gt;&gt;
<a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?fct=edit&amp;photo_id=<{$item_id}>">
    <{$lang_title_edit}></a>
<br><br>

<{if $message != '' }>
    <{* color: red;  background-color: lightyellow;  border: gray; *}>
    <div style="color:#ff0000; background-color:#ffffeo; border:1px dotted #808080; padding:3px;">
        <{$message}>
    </div>
    <br>
<{/if}>

<{if $error != '' }>
    <{* color: red;  background-color: lightyellow;  border: gray; *}>
    <div style="color:#ff0000; background-color:#ffffeo; border:1px dotted #808080; padding:3px;">
        <{$error}>
    </div>
    <br>
<{/if}>

<{if $show_preview }>
<{include file="db:`$mydirname`_inc_photo_in_list.tpl"}>
<br>
-
<{if $cfg_use_pathinfo }>
<a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php/photo/<{$photo.photo_id}>/">
    <{else}>
    <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?fct=photo&amp;p=<{$photo.photo_id}>">
        <{/if}>
        <{$lang_look_photo}> : <{$photo.title_s}>
    </a> <br>
    <{/if}>

    <{if $show_admin_manager }>
        -
        <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/admin/index.php?fct=item_manager&amp;op=modify_form&amp;item_id=<{$item_id}>">
            goto admin item manager: <{$item_id}>
        </a>
        <br>
        <br>
    <{/if}>

    <{if $show_form_photo }>
        <{include file="db:`$mydirname`_form_photo.tpl" }>
        <br>
    <{/if}>

    <{if $show_form_redo }>
        <{include file="db:`$mydirname`_form_redo.tpl" }>
        <br>
    <{/if}>

    <{if $show_form_video_thumb }>
        <{include file="db:`$mydirname`_form_video_thumb.tpl" }>
        <br>
    <{/if}>

    <{if $show_form_confirm }>
        <{include file="db:`$mydirname`_form_confirm.tpl" }>
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
