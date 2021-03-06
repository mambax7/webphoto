<{* $Id: page_detail.html,v 1.6 2011/12/28 16:16:15 ohwada Exp $ *}>
<!--- page detail --->

<a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php">
    <{$xoops_modulename}></a>

<{* === title for normal === *}>
<{if $show_title && $title_bread_crumb }>
    &gt;&gt; <{$title_bread_crumb}> ( <{$total_bread_crumb}> )
<{/if}>

<{* === catpath for category === *}>
<{if $show_catpath }>
    &gt;&gt;
    <{include file="db:`$mydirname`_inc_catpath.tpl" }>
    ( <{$total_bread_crumb}> )
<{/if}>

<br>

<{* === gmap js === *}>
<{if $show_gmap }>
    <{include file="db:`$mydirname`_inc_gmap_js.tpl" }>
    <{include file="db:`$mydirname`_inc_gmap_icons.tpl" }>
    <{include file="db:`$mydirname`_inc_gmap_makers.tpl" }>
<{/if}>
<{* === gmap js end === *}>

<{* === timeline js === *}>
<{if $show_timeline }>
    <{$timeline_js }>
<{/if}>
<{* === timeline js end === *}>

<{* === windows js === *}>
<{if $show_js_window }>
    <script type="text/javascript">
        //<![CDATA[
        <
        {
            if $show_js_boxlist }
        >
        var webphoto_box_list = "<{$js_boxlist}>";
        <
        {/
            if}
        >
        <
        {
            if $show_js_load }
        >
        window.onload =
        <
        {
            $js_load
        }
        >
        ;
        <
        {/
            if}
        >
        <
        {
            if $show_js_unload }
        >
        window.onunload =
        <
        {
            $js_unload
        }
        >
        ;
        <
        {/
            if}
        >
        //]]>
    </script>
<{/if}>
<{* === windows js end === *}>

<div class="webphoto_page_title">
    <{$xoops_modulename}>
</div>

<{if $show_index_desc && ($cfg_index_desc != '') }>
    <div class="webphoto_index_desc">
        <{$cfg_index_desc}>
    </div>
<{/if}>

<{* === menue === *}>
<{if $show_menu }>
    <{include file="db:`$mydirname`_inc_menu.tpl" }>
<{/if}>

<{* === search form === *}>
<{if $show_search }>
    <{include file="db:`$mydirname`_inc_search.tpl" }>
<{/if}>

<{* === tag cloud === *}>
<{if $show_tagcloud }>
    <div id="webphoto_box_tagcloud_b">
        <a href="javascript:webphoto_box_visible_flip('webphoto_box_tagcloud', 0)">
            <img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/icons/box_minus.png" width="16" height="16" border="0" alt="minus">
            <{$lang_tagcloud_off}></a> <br>
        <div class="webphoto_tagcloud">
            <{foreach from=$tagcloud item=tagc }>
                <a href="<{$tagc.link}>" style="text-decoration:none;padding:2px;font-size:<{$tagc.size}>px;">
                    <{$tagc.name|escape}></a>
            <{/foreach}>
        </div>
    </div>
    <div id="webphoto_box_tagcloud_a" style="display:none">
        <img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/icons/box_plus.png" width="16" height="16" border="0" alt="minus">
        <a href="javascript:webphoto_box_visible_flip('webphoto_box_tagcloud', 1)">
            <{$lang_tagcloud_on}></a>
    </div>
<{/if}>

<{* === cat list === *}>
<{if $show_catlist }>
    <div id="webphoto_box_catlist_b">
        <a href="javascript:webphoto_box_visible_flip('webphoto_box_catlist', 0)">
            <img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/icons/box_minus.png" width="16" height="16" border="0" alt="minus">
            <{$lang_catlist_off}></a> <br>
        <div class="webphoto_categories">
            <{include file="db:`$mydirname`_inc_catlist.tpl"}>
        </div>
    </div>
    <div id="webphoto_box_catlist_a" style="display:none">
        <img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/icons/box_plus.png" width="16" height="16" border="0" alt="minus">
        <a href="javascript:webphoto_box_visible_flip('webphoto_box_catlist', 1)">
            <{$lang_catlist_on}></a>
    </div>
<{/if}>

<{* === timeline === *}>
<{if $show_timeline }>
    <div id="webphoto_box_timeline_b">
        <a href="javascript:webphoto_box_timeline_off()">
            <img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/icons/box_minus.png" width="16" height="16" border="0" alt="minus">
            <{$lang_timeline_off}></a> <br>

        <{if $show_timeline_large }>
            <div class="webphoto_timeline_navi">
                <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?op=timeline&amp;sm=<{$sub_mode}>&amp;sp=<{$sub_param}>">
                    <{$lang_timeline_large}></a>
            </div>
        <{/if}>

        <{if $show_timeline_unit }>
            <div class="webphoto_timeline_navi">
                <a href="#" onClick="return webphoto_timeline_zoom( this,'<{$mode}>','<{$sub_mode}>','<{$sub_param}>','century')">
                    <{$lang_timeline_unit_century}></a> |
                <a href="#" onClick="return webphoto_timeline_zoom( this,'<{$mode}>','<{$sub_mode}>','<{$sub_param}>','decade')">
                    <{$lang_timeline_unit_decade}></a> |
                <a href="#" onClick="return webphoto_timeline_zoom( this,'<{$mode}>','<{$sub_mode}>','<{$sub_param}>','year')">
                    <{$lang_timeline_unit_year}></a> |
                <a href="#" onClick="return webphoto_timeline_zoom( this,'<{$mode}>','<{$sub_mode}>','<{$sub_param}>','month')">
                    <{$lang_timeline_unit_month}></a> |
                <a href="#" onClick="return webphoto_timeline_zoom( this,'<{$mode}>','<{$sub_mode}>','<{$sub_param}>','week')">
                    <{$lang_timeline_unit_week}></a> |
                <a href="#" onClick="return webphoto_timeline_zoom( this,'<{$mode}>','<{$sub_mode}>','<{$sub_param}>','day')">
                    <{$lang_timeline_unit_day}></a>
            </div>
        <{/if}>

        <div id="<{$timeline_element}>" class="<{$timeline_class}>">Loading ...</div>

        <div class="webphoto_timeline_caution">
            <{$lang_timeline_caution_ie}>
        </div>

    </div>
    <div id="webphoto_box_timeline_a" style="display:none">
        <img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/icons/box_plus.png" width="16" height="16" border="0" alt="minus">
        <a href="javascript:webphoto_box_timeline_on()">
            <{$lang_timeline_on}></a> </a>
    </div>
    <br>
<{/if}>

<{* === gmap === *}>
<{if $show_gmap }>
    <div id="webphoto_box_gmap_b">
        <a href="javascript:webphoto_box_gmap_off()">
            <img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/icons/box_minus.png" width="16" height="16" border="0" alt="minus">
            <{$lang_gmap_off}></a> <br>
        <{$lang_gmap_desc}><br>

        <{if $show_map_large }>
            <div class="webphoto_gmap_navi">
                <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?op=map&amp;sm=<{$sub_mode}>&amp;sp=<{$sub_param}>">
                    <{$lang_map_large}></a>
            </div>
        <{/if}>

        <div id="webphoto_gmap_map" class="<{$gmap_class}>">Loading ...</div>
        <div id="webphoto_gmap_not_compatible" class="webphoto_error"></div>
    </div>
    <div id="webphoto_box_gmap_a" style="display:none">
        <img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/icons/box_plus.png" width="16" height="16" border="0" alt="minus">
        <a href="javascript:webphoto_box_gmap_on()">
            <{$lang_gmap_on}></a> </a>
    </div>
    <br>
<{/if}>

<{* === title for normal === *}>
<{if $show_title && $sub_title_s }>
    <div class="webphoto_sub_title_total">
        <span class="webphoto_sub_title"><{$sub_title_s}></span>
        Total <{$photo_total}>
        <{if $show_rss_icon }>
            <{include file="db:`$mydirname`_inc_rss_icon.tpl" }>
        <{/if}>
    </div>
<{/if}>
<{* === title end === *}>

<{* === catpath for category === *}>
<{if $show_catpath }>
    <div class="webphoto_catpath">
        <{include file="db:`$mydirname`_inc_catpath.tpl" }>
        : Total <{$photo_total}> ( <{$photo_small_sum}> / <{$photo_total_sum}> )
        <{if $show_rss_icon }>
            <{include file="db:`$mydirname`_inc_rss_icon.tpl" }>
        <{/if}>
    </div>
<{/if}>
<{* === catpath end === *}>

<{if $cat_desc_disp != '' }>
    <div class="webphoto_cat_desc"><{$cat_desc_disp}></div>
<{/if}>

<{if $sub_desc_s != '' }>
    <div class="webphoto_sub_desc"><{$sub_desc_s}></div>
<{/if}>

<{* === search === *}>
<{if $show_search }>
    <div class="webphoto_page_subtitle"><{$search_lang_searchresults}></div>
    <{$search_lang_keywords}>
    <{foreach item=keyword from=$search_keywords}>
        <span class="webphoto_sr_keyword"><{$keyword}></span>
    <{/foreach}>
    <br>
    <{if $search_show_candidate }>
        <br>
        <{$lang_sr_candicate}>:
        <br>
        <{foreach item=candidate from=$search_candidates}>
            <span class="webphoto_sr_keyword"><{$candidate.keyword}></span>
            (<{$candidate.lang}>)
        <{/foreach}>
        <br>
    <{/if}>

    <{if $search_show_ignore }>
        <br>
        <{$search_lang_keyignore}>
        <br>
        <{foreach item=ignore from=$search_ignores}>
            <span class="webphoto_sr_keyword"><{$ignore}></span>
        <{/foreach}>
        <br>
    <{/if}>
    <br>
<{/if}>

<{if $show_lang_keytooshort }>
    <div class="webphoto_error"><{$lang_keytooshort}></div>
<{/if}>

<{if $show_nomatch }>
    <div class="webphoto_error"><{$lang_nomatch_photo}></div>
<{/if}>

<{if $show_random_more }>
    <div class="webphoto_sub_desc">
        <{if $cfg_use_pathinfo }>
        <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php/random/">
            <{else}>
            <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?op=random">
                <{/if}>
                <{$lang_random_more}></a>
    </div>
<{/if}>

<{if $show_photo_list }>
    <ul>
        <li><{$lang_usage_photo}></li>
        <li><{$lang_usage_title}></li>
    </ul>
    <br>
<{/if}>

<{if $show_photo_list }>
    <div class="webphoto_photo_type">
        |
        <a href="javascript:webphoto_box_visible_flip('webphoto_box_photo', 0)">
            <{$lang_viewtype_list}></a> |
        <a href="javascript:webphoto_box_visible_flip('webphoto_box_photo', 1)">
            <{$lang_viewtype_table}></a> |
    </div>
<{/if}>

<{* === photo header === *}>
<{if $show_sort || $show_navi }>
<div class="webphoto_index_photo_header">
    <{/if}>

    <{if $show_sort }>
        <{include file="db:`$mydirname`_inc_sort.tpl" }>
        <br>
    <{/if}>

    <{if $show_navi }>
        <{include file="db:`$mydirname`_inc_navi.tpl" }>
    <{/if}>

    <{if $show_sort || $show_navi }>
</div>
<{/if}>
<{* === photo header end === *}>

<{* === photo list === *}>
<{if $show_photo_list }>
    <{include file="db:`$mydirname`_inc_photo_list.tpl" }>
<{/if}>
<{* === photo list end === *}>

<{* === photo footer end === *}>
<{if $show_navi }>
    <div class="webphoto_index_photo_footer">
        <{include file="db:`$mydirname`_inc_navi.tpl" }>
    </div>
<{/if}>
<{* === photo footer end === *}>

<{* === QR === *}>
<{if $show_qr }>
    <div align="center">
        <img src="<{$qrs_url}>/qr_index.png" border="0" alt="qr_index"><br>
        <a href="mailto:<{$mobile_email}>?subject=webphoto_url&amp;body=<{$mobile_url}>"><{$lang_mobile_mailto}></a><br>
    </div>
<{/if}>

<{* === notification === *}>
<{if $show_notification_select }>
    <div align="center">
        <{include file="db:`$mydirname`_inc_notification_select.tpl"}>
    </div>
    <br>
<{/if}>

<hr>
<noscript>
    <div class="webphoto_error"><{$lang_js_invalid}></div>
</noscript>

<div class="webphoto_execution_time">execution time : <{$execution_time}> sec</div>
<{if $is_module_admin && ($memory_usage > 0)}>
    <div class="webphoto_memory_usage">memory usage : <{$memory_usage}> MB</div>
<{/if}>

<{* this is NOT copyright. you can remove this. *}>
<{if $show_powered }>
    <div class="webphoto_footer">
        <a href="<{$happy_linux_url}>" target="_blank">Powered by Happy Linux</a> |
        <a href="http://www.peak.ne.jp/xoops/" target="_blank">myAlbum-P</a> |
        <a href="http://bluetopia.homeip.net/" target="_blank">original</a>
    </div>
<{/if}>

<{if $is_module_admin }>
    <br>
    <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/admin/index.php"><{$lang_goto_admin}></a>
<{/if}>
