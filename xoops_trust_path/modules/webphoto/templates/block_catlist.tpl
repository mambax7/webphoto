<{* $Id: block_catlist.html,v 1.1 2008/11/30 10:37:30 ohwada Exp $ *}>

<{assign var="mydirname"     value=$block.dirname }>
<{assign var="xoops_dirname" value=$block.dirname }>
<{assign var="catlist"       value=$block.catlist }>

<{include file="db:`$mydirname`_inc_catlist.tpl"}>
