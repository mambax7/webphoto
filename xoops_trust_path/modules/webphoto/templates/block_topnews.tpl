<{* $Id: block_topnews.html,v 1.5 2009/04/11 14:23:35 ohwada Exp $ *}>

<ul>
    <{foreach item=photo from=$block.photo}>
        <li>
            <{if $block.cfg_use_pathinfo }>
            <a href="<{$xoops_url}>/modules/<{$block.dirname}>/index.php/photo/<{$photo.photo_id}>/">
                <{else}>
                <a href="<{$xoops_url}>/modules/<{$block.dirname}>/index.php?fct=photo&amp;p=<{$photo.photo_id}>">
                    <{/if}>
                    <{$photo.title_s}></a>
                (<{$photo.item_time_update|formatTimestamp:"s"}>)
        </li>
    <{/foreach}>
</ul>
