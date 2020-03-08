<{* $Id: inc_gmap_block.html,v 1.2 2009/01/31 20:15:53 ohwada Exp $ *}>

<{* google map script 1 *}>
<{if $gmap_api_load}>
    <{$gmap_api_js}>
<{/if}>

<{* google map script 2 *}>
<{if $gmap_block_load}>
    <{$gmap_block_js}>
<{/if}>

<{* google map script 3 *}>
<script type="text/javascript">
    //<![CDATA[
    var <
    {
        $gmap_name
    }
    >
    _info = new Array();

    function
    <
    {

    $gmap_name
    }
    >
    _show()
    {
        var <
        {
            $gmap_name
        }
    >
        _gmap = new GMap2(document.getElementById("<{$gmap_name}>_map"));
        var point = new Array( < {$gmap_latitude} >, <
        {
            $gmap_longitude
        }
    >,  <
        {
            $gmap_zoom
        }
    > )
        ;
        webphoto_gmap_b_show( < {$gmap_name} > _gmap, point,
    <
        {
            $gmap_name
        }
    >
        _info,
    <
        {
            $gmap_control
        }
    >, <
        {
            $gmap_type_control
        }
    > )
        ;
    }
    //]]>
</script>

<{counter name="photo_i" assign="photo_i" start=0 print=false}><br>
<{foreach item=photo from=$photos}>

    <{* google map script 4 *}>
    <script type="text/javascript">
        //<![CDATA[
        <
        {
            $gmap_name
        }
        >
        _info[ < {$photo_i} >
        ]
        = new Array( < {
            $photo.gmap_latitude
        } >,
        <
        {
            $photo.gmap_longitude
        }
        >,
        '<{$photo.gmap_info}>'
        )
        ;
        //]]>
    </script>
    <{counter name="photo_i" assign="photo_i" print=false }>
<{/foreach}>

<{* google map script 5 *}>
<script type="text/javascript">
    //<![CDATA[
    <
    {
        if $gmap_timeout > 0}
    >
    setTimeout('<{$gmap_name}>_show()', < {$gmap_timeout} >
    )
    ;
    <
    {else
    }
    >
    window.onload =
    <
    {
        $gmap_name
    }
    >
    _show;
    <
    {/
        if}
    >
    window.onunload = GUnload;
    //]]>
</script>

<div id="<{$gmap_name}>_map" style="width:100%; height:<{$gmap_height}>px; border:1px solid #909090; margin-bottom:6px;"></div>
