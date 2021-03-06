<{* $Id: inc_photo_in_list.html,v 1.12 2010/10/06 02:22:46 ohwada Exp $ *}>

<table>
    <tr>

        <{* IMAGE PART *}>
        <td class="webphoto_list_td_image">
            <div class="webphoto_list_image">

                <{* POPUP IMAGE *}>
                <{if ( $photo.onclick == 2 ) && $cfg_use_popbox }>
                <{if $photo.img_thumb_width && $photo.img_thumb_height }>
                    <img src="<{$photo.img_thumb_src_s}>" alt="<{$photo.title_s}>" title="<{$photo.title_s}>" width="<{$photo.img_thumb_width}>" height="<{$photo.img_thumb_height}>"
                         class="PopBoxImageSmall" onclick="Pop(this,100,'PopBoxImageLarge');" pbSrcNL="<{$photo.img_large_src_s}>">
                <{else}>
                    <img src="<{$photo.img_thumb_src_s}>" alt="<{$photo.title_s}>" title="<{$photo.title_s}>" width="<{$cfg_thumb_width}>" class="PopBoxImageSmall"
                         onclick="Pop(this,100,'PopBoxImageLarge');" pbSrcNL="<{$photo.img_large_src_s}>">
                <{/if}>

                <{* DIRECT LINK *}>
                <{elseif $photo.onclick == 1 }>
                <{if $photo.media_url_s }>
                <a href="<{$photo.media_url_s}>" target="_blank">
                    <{elseif $cfg_use_pathinfo }>
                    <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php/photo/<{$photo.photo_id}>/">
                        <{else}>
                        <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?fct=photo&amp;p=<{$photo.photo_id}>">
                            <{/if}>

                            <{if $photo.img_thumb_width && $photo.img_thumb_height }>
                                <img src="<{$photo.img_thumb_src_s}>" alt="<{$photo.title_s}>" title="<{$photo.title_s}>" width="<{$photo.img_thumb_width}>" height="<{$photo.img_thumb_height}>">
                            <{else}>
                                <img src="<{$photo.img_thumb_src_s}>" alt="<{$photo.title_s}>" title="<{$photo.title_s}>" width="<{$cfg_thumb_width}>">
                            <{/if}>
                        </a>

                        <{* PHOTO PAGE *}>
                        <{else}>
                        <{if $cfg_use_pathinfo }>
                        <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php/photo/<{$photo.photo_id}>/">
                            <{else}>
                            <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?fct=photo&amp;p=<{$photo.photo_id}>">
                                <{/if}>
                                <{if $photo.img_thumb_width && $photo.img_thumb_height }>
                                    <img src="<{$photo.img_thumb_src_s}>" alt="<{$photo.title_s}>" title="<{$photo.title_s}>" width="<{$photo.img_thumb_width}>" height="<{$photo.img_thumb_height}>">
                                <{else}>
                                    <img src="<{$photo.img_thumb_src_s}>" alt="<{$photo.title_s}>" title="<{$photo.title_s}>" width="<{$cfg_thumb_width}>">
                                <{/if}>
                            </a>
                            <{/if}>

            </div>
        </td>

        <{* INFORMATION PART *}>
        <td class="webphoto_list_td_info">
            <{include file="db:`$mydirname`_inc_photo_info.tpl"}>
            <{if $show_photo_summary && ($photo.summary != '') }>
                <div class="webphoto_description">
                    <{$photo.summary}>
                </div>
            <{/if}>
        </td>

    </tr>
</table>
