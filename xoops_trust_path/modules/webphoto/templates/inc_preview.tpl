<{* $Id: inc_preview.html,v 1.2 2010/10/10 11:02:10 ohwada Exp $ *}>

<table>
    <tr>

        <{* IMAGE PART *}>
        <td class="webphoto_list_td_image">
            <div class="webphoto_list_image">
                <{if $photo.img_thumb_width && $photo.img_thumb_height }>
                    <img src="<{$photo.img_thumb_src_s}>" alt="<{$photo.title_s}>" title="<{$photo.title_s}>" width="<{$photo.img_thumb_width}>" height="<{$photo.img_thumb_height}>">
                <{elseif $photo.img_thumb_width }>
                    <img src="<{$photo.img_thumb_src_s}>" alt="<{$photo.title_s}>" title="<{$photo.title_s}>" width="<{$photo.img_thumb_width}>">
                <{else}>
                    <img src="<{$photo.img_thumb_src_s}>" alt="<{$photo.title_s}>" title="<{$photo.title_s}>" width="<{$cfg_thumb_width}>">
                <{/if}>
            </div>
        </td>

        <{* INFORMATION PART *}>
        <td class="webphoto_list_td_info">
            <{include file="db:`$mydirname`_inc_photo_info.tpl"}>
            <{if $photo.summary != '' }>
                <div class="webphoto_description">
                    <{$photo.summary}>
                </div>
            <{/if}>
        </td>

    </tr>
</table>
