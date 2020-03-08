<{* $Id: inc_catpath.html,v 1.1 2009/04/19 11:41:45 ohwada Exp $ *}>

<{* image at first *}>
<{assign var="catpath_first" value=$catpath.first }>

<{if $cfg_use_pathinfo }>
<a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php/category/<{$catpath_first.cat_id}>/">
    <{else}>
    <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?fct=category&amp;p=<{$catpath_first.cat_id}>">
        <{/if}>

        <img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/icons/category.png" width="16" height="16" border="0" alt="<{$catpath_first.cat_title_s}>">
    </a>

    <{* === catpath === *}>
    <{foreach name=catpath item=catpath_list from=$catpath.list}>
    <{if $cfg_use_pathinfo }>
    <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php/category/<{$catpath_list.cat_id}>/">
        <{else}>
        <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?fct=category&amp;p=<{$catpath_list.cat_id}>">
            <{/if}>
            <{$catpath_list.cat_title_s}>
        </a>

        <{* not show at last *}>
        <{if $smarty.foreach.catpath.last === false }>
            &gt;
        <{/if}>
        <{/foreach}>
