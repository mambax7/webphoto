<{* $Id: inc_catlist.html,v 1.8 2010/01/25 10:03:07 ohwada Exp $ *}>

<table width="100%" border="0" cellspacing="5" cellpadding="0" align="center">

    <{* --- main category --- *}>
    <{foreach from=$catlist.cats item=cat key=count name=cat_list}>
        <{* -- table line start -- *}>
        <{if $smarty.foreach.cat_list.iteration mod $catlist.cols == 1}>
            <tr>
        <{/if}>

        <{* -- table column image -- *}>
        <td valign="top" align="right">

            <{if $cfg_use_pathinfo }>
            <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php/category/<{$cat.cat_id}>/">
                <{else}>
                <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?fct=category&amp;cat_id=<{$cat.cat_id}>">
                    <{/if}>

                    <{if $catlist.show_main_img && $cat.imgurl_s }>
                        <{if $cat.cat_main_width && $cat.cat_main_height }>
                            <img src="<{$cat.imgurl_s}>" width="<{$cat.cat_main_width}>" height="<{$cat.cat_main_height}>" border="0" alt="<{$cat.cat_title_s}>">
                        <{else}>
                            <img src="<{$cat.imgurl_s}>" width="<{$catlist.main_width}>" border="0" alt="<{$cat.cat_title_s}>">
                        <{/if}>
                    <{else}>
                        <img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/icons/parent_category.png" width="32" height="32" border="0" alt="<{$cat.cat_title_s}>">
                    <{/if}>

                </a>

        </td>
        <{* -- table column title -- *}>
        <td valign="top" align="left" width="<{$catlist.width}>%">

            <{if $cfg_use_pathinfo }>
            <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php/category/<{$cat.cat_id}>/">
                <{else}>
                <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?fct=category&amp;p=<{$cat.cat_id}>">
                    <{/if}>

                    <span style="font-size:120%; font-weight:bold;"><{$cat.cat_title_s}></span></a>
                <br>
                <{$lang_caption_total}>
                <{$cat.photo_total_sum}>&nbsp;(<{$cat.photo_small_sum}>)
                <br>

                <{if $cat.summary != '' }>
                    <{$cat.summary}>
                    <br>
                <{/if}>

                <{* --- sub category --- *}>
                <{if $catlist.show_sub }>
                <{foreach from=$cat.subcategories item=subcat}>

                <{if $cfg_use_pathinfo }>
                <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php/category/<{$subcat.cat_id}>/">
                    <{else}>
                    <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?fct=category&amp;p=<{$subcat.cat_id}>">
                        <{/if}>

                        <{if $catlist.show_sub_img && $subcat.imgurl_s }>
                            <{if $subcat.cat_sub_width && $subcat.cat_sub_height }>
                                <img src="<{$subcat.imgurl_s}>" width="<{$subcat.cat_sub_width}>" height="<{$subcat.cat_sub_height}>" border="0" alt="<{$subcat.cat_title_s}>">
                            <{else}>
                                <img src="<{$subcat.imgurl_s}>" width="<{$catlist.sub_width}>" border="0" alt="<{$subcat.cat_title_s}>">
                            <{/if}>
                        <{else}>
                            <img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/icons/category.png" width="16" height="16" border="0" alt="<{$subcat.cat_title_s}>">
                        <{/if}>

                        <{if $subcat.number_of_subcat}>
                            <img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/icons/subcat.png" width="15" height="15" border="0" alt="subcat">
                        <{/if}>

                        <{$subcat.cat_title_s}></a>
                    (<{$subcat.photo_total_sum}>)

                    <{if $catlist.delmita }>
                        <{$catlist.delmita}>
                    <{/if}>

                    <{/foreach}>
                    <{/if}>
                    <{* --- sub category end --- *}>

        </td>
        <{* -- table line end -- *}>
        <{if ($smarty.foreach.cat_list.iteration is div by $catlist.cols) || $smarty.foreach.cat_list.last}>
            </tr>
        <{/if}>
    <{/foreach}>
    <{* --- main category end --- *}>

</table>
