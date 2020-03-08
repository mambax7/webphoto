<{* $Id: inc_gmap_icons.html,v 1.1.1.1 2008/06/21 12:22:18 ohwada Exp $ *}>

<{* -- gmap icon loop -- *}>
<{counter name="gmap_icon_i" assign="gmap_icon_i" start=0 print=false }><br>
<{foreach item=gmap_icon from=$gmap_icons}>
    <script type="text/javascript">
        //<![CDATA[

        var icon = new GIcon();
        icon.image = "<{$xoops_url}><{$gmap_icon.gicon_image_path}>";
        icon.iconSize = new GSize(parseInt( < {
            $gmap_icon.gicon_image_width
        } >),
        parseInt( < {
            $gmap_icon.gicon_image_height
        } >
        ) )
        ;
        icon.iconAnchor = new GPoint(parseInt( < {
            $gmap_icon.gicon_anchor_x
        } >),
        parseInt( < {
            $gmap_icon.gicon_anchor_y
        } >
        ) )
        ;
        icon.infoWindowAnchor = new GPoint(parseInt( < {
            $gmap_icon.gicon_info_x
        } >),
        parseInt( < {
            $gmap_icon.gicon_info_y
        } >
        ) )
        ;

        <
        {
            if $gmap_icon.gicon_shadow_path != '' }
        >
        icon.shadow = "<{$xoops_url}><{$gmap_icon.gicon_shadow_path}>";
        icon.shadowSize = new GSize(parseInt( < {
            $gmap_icon.gicon_shadow_width
        } >),
        parseInt( < {
            $gmap_icon.gicon_shadow_height
        } >
        ) )
        ;
        <
        {/
            if}
        >

        webphoto_gmap_icon[ < {
            $gmap_icon.gicon_id
        } >
        ]
        = icon;

        //]]>
    </script>
    <{counter name="gmap_icon_i" assign="gmap_icon_i" print=false }>
<{/foreach}>
<{* -- gmap icon loop end -- *}>
