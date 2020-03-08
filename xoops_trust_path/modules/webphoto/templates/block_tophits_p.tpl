<{* $Id: block_tophits_p.html,v 1.13 2010/10/06 02:22:46 ohwada Exp $ *}>

<{* popbox.js *}>
<{if $block.show_popbox_js}>
    <{$block.popbox_js}>
<{/if}>

<{* google map *}>
<{if $block.show_gmap}>
    <{$block.gmap}>
<{/if}>

<table width="100%" cellspacing="0" cellpadding="0" border="0">

    <{* count begins from 1 *}>
    <{foreach item=photo key=count from=$block.photo name=photo_list}>

        <{* -- open table row -- *}>
        <{if $block.cols == 1}>
            <tr>
                <{elseif $smarty.foreach.photo_list.iteration mod $block.cols == 1}>
            <tr>
        <{/if}>
        <td align="center" style="margin:0px;padding:5px 0px;">
            <{if $photo.title_short_s != '' }>
            <{if $block.cfg_use_pathinfo }>
            <a href="<{$xoops_url}>/modules/<{$block.dirname}>/index.php/photo/<{$photo.photo_id}>/">
                <{else}>
                <a href="<{$xoops_url}>/modules/<{$block.dirname}>/index.php?fct=photo&amp;photo_id=<{$photo.photo_id}>">
                    <{/if}>

                    <{$photo.title_short_s}></a>
                <{/if}>
                (<{$photo.item_hits}> <{$photo.hits_suffix}>) <br>

                <{* POPUP IMAGE *}>
                <{if ( $photo.onclick == 2 ) && $block.show_popbox }>
                <{if $photo.img_thumb_width && $photo.img_thumb_height }>
                    <img src="<{$photo.img_thumb_src_s}>" alt="<{$photo.title_s}>" title="<{$photo.title_s}>" width="<{$photo.img_thumb_width}>" height="<{$photo.img_thumb_height}>"
                         class="PopBoxImageSmall" onclick="Pop(this,100,'PopBoxImageLarge');" pbSrcNL="<{$photo.img_large_src_s}>">
                <{else}>
                    <img src="<{$photo.img_thumb_src_s}>" alt="<{$photo.title_s}>" title="<{$photo.title_s}>" width="<{$block.cfg_thumb_width}>" class="PopBoxImageSmall"
                         onclick="Pop(this,100,'PopBoxImageLarge');" pbSrcNL="<{$photo.img_large_src_s}>">
                <{/if}>

                <{* PHOTO PAGE *}>
                <{else}>
                <{if $block.cfg_use_pathinfo }>
                <a href="<{$xoops_url}>/modules/<{$block.dirname}>/index.php/photo/<{$photo.photo_id}>/">
                    <{else}>
                    <a href="<{$xoops_url}>/modules/<{$block.dirname}>/index.php?fct=photo&amp;p=<{$photo.photo_id}>">
                        <{/if}>

                        <{if $photo.img_thumb_src_s && $photo.img_thumb_width && $photo.img_thumb_height }>
                            <img src="<{$photo.img_thumb_src_s}>" alt="<{$photo.title_s}>" title="<{$photo.title_s}>" width="<{$photo.img_thumb_width}>" height="<{$photo.img_thumb_height}>">
                        <{elseif $photo.img_thumb_src_s }>
                            <img src="<{$photo.img_thumb_src_s}>" alt="<{$photo.title_s}>" title="<{$photo.title_s}>" width="<{$block.cfg_thumb_width}>">
                        <{/if}>
                    </a>

                    <{/if}>
        </td>
        <{* -- close table row -- *}>
        <{if $block.cols == 1}>
            </tr>
        <{elseif $smarty.foreach.photo_list.iteration is div by $block.cols}>
            </tr>
        <{elseif $smarty.foreach.photo_list.last}>
            </tr>
        <{/if}>

    <{/foreach}>
</table>
