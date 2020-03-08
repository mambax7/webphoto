<html><{strip}>
    <head>
        <{* $Id: main_i.html,v 1.9 2009/09/19 20:40:44 ohwada Exp $ *}>
        <meta http-equiv="content-type" content="text/html; charset=<{$charset}>">
        <title><{$pagetitle_conv}> - <{$sitename_conv}></title>
    </head>
    <body>

    <{* === photo body === *}>
    <{if $show_photo }>
        <div align="center">

            <{* IMAGE PART *}>

            <{* BIG NORMAL IMAGES *}>
            <{if $photo.is_normal_image && ( $size == 1 ) }>
                <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/i.php?id=<{$photo.photo_id}>">
                    <{if $photo.img_middle_width && $photo.img_middle_height }>
                        <img src="<{$photo.img_middle_src_s}>" alt="<{$photo.title_conv}>" width="<{$photo.img_middle_width}>" height="<{$photo.img_middle_height}>">
                    <{else}>
                        <img src="<{$photo.img_middle_src_s}>" alt="<{$photo.title_conv}>" width="<{$cfg_middle_width}>">
                    <{/if}>
                </a>
                <{* NORMAL IMAGES *}>
            <{elseif $photo.is_normal_image }>
                <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/i.php?id=<{$photo.photo_id}>&amp;s=1">
                    <{if $photo.img_thumb_width && $photo.img_thumb_height }>
                        <img src="<{$photo.img_thumb_src_s}>" alt="<{$photo.title_conv}>" width="<{$photo.img_thumb_width}>" height="<{$photo.img_thumb_height}>">
                    <{else}>
                        <img src="<{$photo.img_thumb_src_s}>" alt="<{$photo.title_conv}>" width="<{$cfg_thumb_width}>">
                    <{/if}>
                </a>
                <{* MOBILE VIDEO , OTHERS *}>
            <{else}>
                <a href="<{$photo.media_url_s}>">
                    <{if $photo.img_thumb_width && $photo.img_thumb_height }>
                        <img src="<{$photo.img_thumb_src_s}>" alt="<{$photo.title_conv}>" width="<{$photo.img_thumb_width}>" height="<{$photo.img_thumb_height}>">
                    <{else}>
                        <img src="<{$photo.img_thumb_src_s}>" alt="<{$photo.title_conv}>" width="<{$cfg_thumb_width}>">
                    <{/if}>
                </a>
            <{/if}>
            <br>

            <{* PHOTO"S SUBJECT *}>
            <{$photo.title_conv}><br>

            <{* SUBMITTER *}>
            <{$photo.uname_conv}><br>

            <{* DATE *}>
            <{if $photo.datetime_disp != '' }>
                <{$photo.datetime_disp}>
                <br>
            <{/if}>

            <{* PLACE *}>
            <{if $photo.place_conv != '' }>
                <{$photo.place_conv}>
                <br>
            <{/if}>

            <{* DURATION *}>
            <{if $photo.is_video }>
                <{$lang_video_conv}>
                <{if $photo.cont_duration > 0 }>
                    <{$photo.cont_duration}> <{$lang_second_conv}>
                    <br>
                <{/if}>
            <{/if}>

            <{* SUMMARY *}>
            <{if $photo.summary_conv != '' }>
                <{$photo.summary_conv}>
                <br>
            <{/if}>

            <{* OTHERS *}>
            <{if $photo.artist_conv != '' }>
                <{$photo.artist_conv}>
                <br>
            <{/if}>

            <{if $photo.album_conv != '' }>
                <{$photo.album_conv}>
                <br>
            <{/if}>

            <{if $photo.label_conv != '' }>
                <{$photo.label_conv}>
                <br>
            <{/if}>

            <{if $photo.text_1_conv != '' }>
                <{$photo.text_1_conv}>
                <br>
            <{/if}>

            <{if $photo.text_2_conv != '' }>
                <{$photo.text_2_conv}>
                <br>
            <{/if}>

            <{if $photo.text_3_conv != '' }>
                <{$photo.text_3_conv}>
                <br>
            <{/if}>

            <{if $photo.text_4_conv != '' }>
                <{$photo.text_4_conv}>
                <br>
            <{/if}>

            <{if $photo.text_5_conv != '' }>
                <{$photo.text_5_conv}>
                <br>
            <{/if}>

            <{if $photo.text_6_conv != '' }>
                <{$photo.text_6_conv}>
                <br>
            <{/if}>

            <{if $photo.text_7_conv != '' }>
                <{$photo.text_7_conv}>
                <br>
            <{/if}>

            <{if $photo.text_8_conv != '' }>
                <{$photo.text_8_conv}>
                <br>
            <{/if}>

            <{if $photo.text_9_conv != '' }>
                <{$photo.text_9_conv}>
                <br>
            <{/if}>

            <{if $photo.text_10_conv != '' }>
                <{$photo.text_10_conv}>
                <br>
            <{/if}>

            <{if $photo.has_map }>
                <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/i.php?id=<{$photo.photo_id}>&amp;op=map">
                    <{$lang_show_map}>
                </a>
            <{/if}>

        </div>
    <{/if}>
    <{* === photo body end === *}>

    <{if $show_map }>
        <div align="center">
            <img src="<{$photo.map_src}>" alt="Google Static Maps">
            <br><br>

            <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/i.php?id=<{$photo.photo_id}>">
                <{$photo.title_conv}>
            </a> <br>

            <{if $photo.place_conv != '' }>
                <{$photo.place_conv}>
                <br>
            <{/if}>

        </div>
    <{/if}>

    <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/i.php">
        <{$modulename_conv}></a><br>

    <{* === photo list begin === *}>
    <{foreach item=photo_l from=$photo_list}>
        <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/i.php?id=<{$photo_l.photo_id}>">
            - <{$photo_l.title_conv}>
        </a>
        (<{$photo_l.time_update_m}>)
        <br>
    <{/foreach}>
    <{* === photo list end === *}>

    <{if $navi != '' }>
        <{$navi}>
        <br>
    <{/if}>

    <{* === post === *}>
    <{if $show_post }>
        <br>
        <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?fct=i_post">
            <{$lang_post_conv}>
        </a>
        <br>
    <{/if}>

    <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/i.php?op=judge">
        <{$lang_judge_conv}>
    </a><br>

    </body>
<{/strip}>
</html>
