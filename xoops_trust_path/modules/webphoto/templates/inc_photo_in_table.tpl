<{* $Id: inc_photo_in_table.html,v 1.13 2010/10/06 02:22:46 ohwada Exp $ *}>

<{* IMAGE PART *}>
<div class="webphoto_table_image">

    <{* NORMAL IMAGES with popbox *}>
    <{if ( $photo.onclick == 2 ) && $cfg_use_popbox }>
    <{if $photo.img_thumb_width && $photo.img_thumb_height }>
        <img src="<{$photo.img_thumb_src_s}>" alt="<{$photo.title_s}>" title="<{$photo.title_s}>" width="<{$photo.img_thumb_width}>" height="<{$photo.img_thumb_height}>" class="PopBoxImageSmall"
             onclick="Pop(this,100,'PopBoxImageLarge');" pbSrcNL="<{$photo.img_large_src_s}>">
    <{else}>
        <img src="<{$photo.img_thumb_src_s}>" alt="<{$photo.title_s}>" title="<{$photo.title_s}>" width="<{$cfg_thumb_width}>" class="PopBoxImageSmall" onclick="Pop(this,100,'PopBoxImageLarge');"
             pbSrcNL="<{$photo.img_large_src_s}>">
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

<{* EDIT ICON *}>
<{if $photo.can_edit}>
    <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?fct=edit&amp;photo_id=<{$photo.photo_id}>">
        <img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/icons/edit.png" border="0" alt="<{$lang_title_edit}>" title="<{$lang_title_edit}>"></a>
<{/if}>

<{* GROUP ICON *}>
<{if !$photo.is_public}>
    <img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/icons/group.png" border="0" alt="<{$lang_icon_group}>" title="<{$lang_icon_group}>">
    </a>
<{/if}>

<{* PHOTO"S SUBJECT *}>
<a name="<{$photo.photo_id}>"></a>

<{if $cfg_use_pathinfo }>
<a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php/photo/<{$photo.photo_id}>/">
    <{else}>
    <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?fct=photo&amp;p=<{$photo.photo_id}>">
        <{/if}>

        <span class="webphoto_table_title"><{$photo.title_s}></span>
    </a><br>

    <div class="webphoto_table_info">

        <{* SUBMITTER *}>
        <{* user *}>
        <{if $photo.uid > 0}>
        <{if $cfg_use_pathinfo }>
        <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php/user/<{$photo.uid}>/">
            <{else}>
            <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?fct=user&amp;p=<{$photo.uid}>">
                <{/if}>

                <{$photo.uname_s}></a>

            <{* guest *}>
            <{else}>
            <{$photo.uname_s}>

            <{/if}>
            <br>

            <{* DATE *}>
            <{if $photo.datetime_disp != '' }>
            <{if $cfg_use_pathinfo }>
            <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php/date/<{$photo.datetime_disp}>/">
                <{else}>
                <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?fct=date&amp;p=<{$photo.datetime_disp}>">
                    <{/if}>

                    <{$photo.datetime_disp}>
                </a> <br>
                <{/if}>

                <{* PLACE *}>
                <{if $photo.place_s != '' }>
                <{if $cfg_use_pathinfo }>
                <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php/place/<{$photo.place_urlencode}>/">
                    <{else}>
                    <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?fct=place&amp;p=<{$photo.place_urlencode}>">
                        <{/if}>

                        <{$photo.place_s}>
                    </a> <br>
                    <{/if}>

                    <{* VIDEO *}>
                    <{if $photo.is_video }>
                        <img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/icons/video.png" width="16" height="16" alt="<{$lang_icon_video}>" title="<{$lang_icon_video}>">
                    <{/if}>

                    <{* DURATION *}>
                    <{if $photo.cont_duration_disp > 0 }>
                        <{$photo.cont_duration_disp}>
                        <br>
                    <{/if}>

    </div>
