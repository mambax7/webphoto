<{* $Id: inc_rss_icon.html,v 1.1 2010/11/04 02:24:48 ohwada Exp $ *}>

<{if $cfg_use_pathinfo }>
<{if $rss_param && $rss_limit }>
<a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php/rss/<{$rss_mode}>/<{$rss_param}>/limit=<{$rss_limit}>/" target="_blank">
    <{elseif $rss_limit }>
    <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php/rss/<{$rss_mode}>/limit=<{$rss_limit}>/" target="_blank">
        <{else}>
        <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php/rss/<{$rss_mode}>/" target="_blank">
            <{/if}>
            <{else}>
            <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?fct=rss&amp;mode=<{$rss_mode}>&amp;param=<{$rss_param}>&amp;limit=<{$rss_limit}>" target="_blank">
                <{/if}>

                <img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/icons/rss.png" border="0" alt="<{$lang_title_rss}>" title="<{$lang_title_rss}>">
            </a>
