<{* $Id: main_date.html,v 1.1 2010/01/25 10:05:02 ohwada Exp $ *}>

<{if $show_page_detail }>
    <{include file="db:`$mydirname`_page_detail.tpl" }>
<{else}>
    <{include file="db:`$mydirname`_page_list.tpl" }>
<{/if}>
