<{* $Id: inc_gmap_js.html,v 1.1.1.1 2008/06/21 12:22:18 ohwada Exp $ *}>

<script type="text/javascript">
    //<![CDATA[

    <
    {*
        value: lang
        strings *
    }
    >
    <
    {
        if $gmap_lang_latitude != "" }
    >
    webphoto_gmap_lang_latitude = "<{$gmap_lang_latitude}>";
    <
    {/
        if}
    >
    <
    {
        if $gmap_lang_longitude != "" }
    >
    webphoto_gmap_lang_longitude = "<{$gmap_lang_longitude}>";
    <
    {/
        if}
    >
    <
    {
        if $gmap_lang_zoom != "" }
    >
    webphoto_gmap_lang_zoom = "<{$gmap_lang_zoom}>";
    <
    {/
        if}
    >
    <
    {
        if $gmap_lang_not_compatible != "" }
    >
    webphoto_gmap_lang_not_compatible = "<{$gmap_lang_not_compatible}>";
    <
    {/
        if}
    >
    <
    {
        if $gmap_lang_no_match_place != "" }
    >
    webphoto_gmap_lang_no_match_place = "<{$gmap_lang_no_match_place}>";
    <
    {/
        if}
    >

    <
    {*
        value: numeric *
    }
    >
    <
    {
        if $gmap_latitude != "" }
    >
    webphoto_gmap_default_latitude =
    <
    {
        $gmap_latitude
    }
    >
    ;
    <
    {/
        if}
    >
    <
    {
        if $gmap_longitude != "" }
    >
    webphoto_gmap_default_longitude =
    <
    {
        $gmap_longitude
    }
    >
    ;
    <
    {/
        if}
    >
    <
    {
        if $gmap_zoom != "" }
    >
    webphoto_gmap_default_zoom =
    <
    {
        $gmap_zoom
    }
    >
    ;
    <
    {/
        if}
    >

    <
    {*
        value: strings *
    }
    >
    <
    {
        if $xoops_url != "" }
    >
    webphoto_gmap_xoops_url = "<{$xoops_url}>";
    <
    {/
        if}
    >
    <
    {
        if $xoops_dirname != "" }
    >
    webphoto_gmap_dirname = "<{$xoops_dirname}>";
    <
    {/
        if}
    >
    <
    {
        if $xoops_langcode != "" }
    >
    webphoto_gmap_xoops_langcode = "<{$xoops_langcode}>";
    <
    {/
        if}
    >
    <
    {
        if $gmap_opener_mode != "" }
    >
    webphoto_gmap_opener_mode = "<{$gmap_opener_mode}>";
    <
    {/
        if}
    >
    <
    {
        if $gmap_loc_marker_html != "" }
    >
    webphoto_gmap_location_marker_html = "<{$gmap_loc_marker_html}>";
    <
    {/
        if}
    >
    <
    {
        if $gmap_map_type }
    >
    webphoto_gmap_map_type = "<{$gmap_map_type}>";
    <
    {/
        if}
    >
    <
    {
        if $gmap_map_control }
    >
    webphoto_gmap_map_control = "<{$gmap_map_control}>";
    <
    {/
        if}
    >
    <
    {
        if $gmap_geocode_kind }
    >
    webphoto_gmap_geocode_kind = "<{$gmap_geocode_kind}>";
    <
    {/
        if}
    >
    <
    {
        if $gmap_geo_url }
    >
    webphoto_gmap_geo_url = "<{$gmap_geo_url}>";
    <
    {/
        if}
    >
    <
    {
        if $gmap_type_control }
    >
    webphoto_gmap_type_control = "<{$gmap_type_control}>";
    <
    {/
        if}
    >

    <
    {*
        value: strings : "true"
        or
        "false" *
    }
    >
    <
    {
        if $gmap_use_scale_control != "" }
    >
    webphoto_gmap_use_scale_control =
    <
    {
        $gmap_use_scale_control
    }
    >
    ;
    <
    {/
        if}
    >
    <
    {
        if $gmap_use_overview_map_control != "" }
    >
    webphoto_gmap_use_overview_map_control =
    <
    {
        $gmap_use_overview_map_control
    }
    >
    ;
    <
    {/
        if}
    >
    <
    {
        if $gmap_use_draggable_marker != "" }
    >
    webphoto_gmap_use_draggable_marker =
    <
    {
        $gmap_use_draggable_marker
    }
    >
    ;
    <
    {/
        if}
    >
    <
    {
        if $gmap_use_search_marker != "" }
    >
    webphoto_gmap_use_search_marker =
    <
    {
        $gmap_use_search_marker
    }
    >
    ;
    <
    {/
        if}
    >
    <
    {
        if $gmap_use_loc_marker != "" }
    >
    webphoto_gmap_use_location_marker =
    <
    {
        $gmap_use_loc_marker
    }
    >
    ;
    <
    {/
        if}
    >
    <
    {
        if $gmap_use_loc_marker_click != "" }
    >
    webphoto_gmap_use_location_marker_click =
    <
    {
        $gmap_use_loc_marker_click
    }
    >
    ;
    <
    {/
        if}
    >
    <
    {
        if $gmap_use_nishioka_inverse != "" }
    >
    webphoto_gmap_use_nishioka_inverse =
    <
    {
        $gmap_use_nishioka_inverse
    }
    >
    ;
    <
    {/
        if}
    >
    <
    {
        if $gmap_use_set_parent_location != "" }
    >
    webphoto_gmap_use_set_parent_location =
    <
    {
        $gmap_use_set_parent_location
    }
    >
    ;
    <
    {/
        if}
    >
    <
    {
        if $gmap_use_set_parent_address != "" }
    >
    webphoto_gmap_use_set_parent_address =
    <
    {
        $gmap_use_set_parent_address
    }
    >
    ;
    <
    {/
        if}
    >
    <
    {
        if $gmap_use_get_parent_location != "" }
    >
    webphoto_gmap_use_get_parent_location =
    <
    {
        $gmap_use_get_parent_location
    }
    >
    ;
    <
    {/
        if}
    >

    //]]>
</script>
