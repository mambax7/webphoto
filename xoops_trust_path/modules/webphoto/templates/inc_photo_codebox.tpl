<{* $Id: inc_photo_codebox.html,v 1.2 2011/05/10 02:56:39 ohwada Exp $ *}>

<script type="text/javascript" src="<{$xoops_url}>/modules/<{$xoops_dirname}>/libs/code.js"></script>

<div class="webphoto_div_codebox">
    <form name="webphoto_codebox_form" id="webphoto_codebox_form">
        <table class="webphoto_table_code">

            <{foreach from=$photo.codes item=code}>
                <{if $code.show }>
                    <tr>
                        <td class="webphoto_td_code_left">
                            <{if $code.href_s }>
                                <a href="<{$code.href_s}>" target="<{$code.target}>" title="<{$code.title_s}>">
                                    <{$code.caption_s}>
                                </a>
                            <{else}>
                                <{$code.caption_s}>
                            <{/if}>
                        </td>
                        <td class="webphoto_td_code_middle">
                            <{if $code.filesize }>
                                <{if $code.show_img_download }>
                                    <img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/icons/download.png" alt="<{$lang_download}>" title="<{$lang_download}> border=" 0
                                    " >
                                <{elseif $code.show_img_view }>
                                    <img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/icons/view.png" alt="<{$lang_view}>" title="<{$lang_view}> border=" 0
                                    " >
                                <{/if}>
                                ( <{$code.filesize}> )
                            <{/if}>
                        </td>
                        <td class="webphoto_td_code_right">
                            <input name="webphoto_code_<{$code.name}>" type="text" value="<{$code.value_s}>" size="40" class="webphoto_input_codebox" onClick="webphoto_code_focus( this )" readonly="true">
                        </td>
                    </tr>
                <{/if}>
            <{/foreach}>

        </table>
    </form>
</div>
