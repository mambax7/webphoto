<{* $Id: inc_photo_desc.html,v 1.2 2010/02/07 12:20:02 ohwada Exp $ *}>

<{if $show_photo_desc && ($photo.description_disp != '') }>
    <div class="webphoto_description">

        <{* DESCRIPTION *}>
        <{if $photo.description_scroll > 0 }>
            <div style="overflow: scroll; height: <{$photo.description_scroll}>px;">
                <{$photo.description_disp}>
            </div>
        <{else}>
            <{$photo.description_disp}>
            <br>
        <{/if}>

    </div>
<{/if}>

<{if $show_photo_misc && $photo.show_misc }>
    <div class="webphoto_description">

        <{if $photo.siteurl_s != '' }>
            <{$lang_item_siteurl}>:
            <a href="<{$photo.siteurl_s}>" target="_blank"><{$photo.siteurl_s}></a>
            <br>
        <{/if}>

        <{if $photo.artist_s != '' }>
            <{$lang_item_artist}>: <{$photo.artist_s}>
            <br>
        <{/if}>

        <{if $photo.album_s != '' }>
            <{$lang_item_album}>: <{$photo.album_s}>
            <br>
        <{/if}>

        <{if $photo.label_s != '' }>
            <{$lang_item_label}>: <{$photo.label_s}>
            <br>
        <{/if}>

        <{* TEXT 1 - 10 *}>
        <{foreach item=photo_text from=$photo.texts}>
            <{if $photo_text.text_s != '' }>
                <{$photo_text.lang}>: <{$photo_text.text_s}>
                <br>
            <{/if}>
        <{/foreach}>

    </div>
<{/if}>

<{* CONTENT *}>
<{if $show_photo_content && ($photo.content_disp != '')}>
    <div class="webphoto_description">
        <{$photo.content_disp}> <br>
    </div>
<{/if}>

<{* EXIF *}>
<{if $show_photo_exif && $photo.is_owner && ($photo.exif_disp != '') }>
    <div class="webphoto_description">
        <{$photo.exif_disp}> <br>
    </div>
<{/if}>

<br>
