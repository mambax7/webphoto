<{* $Id: form_admin_cat.html,v 1.4 2011/12/28 16:16:15 ohwada Exp $ *}>

<script type="text/javascript" src="<{$xoops_url}>/modules/<{$xoops_dirname}>/libs/edit.js"></script>

<{* select-option-disabled *}>
<!--[if lt IE 8]>
<script src="<{$xoops_url}>/modules/<{$xoops_dirname}>/libs/select_option_disabled_emulation.js" type="text/javascript"></script>
<script type="text/javascript">
    window.onload = select_option_disabled_onload;
</script>
<![endif]-->

<{if $show_parent}>
    <b><{$lang_parent}></b>
    <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/admin/index.php?fct=catmanager&amp;disp=edit&amp;cat_id=<{$parent_cat_id}>">
        <{$parent_cat_title_s}></a>
    <br>
    <br>
<{/if}>

<{if $show_children}>
    <b><{$lang_cat_child_cap}></b>
    <br>
    <{foreach item=ch from=$children_list}>
        &nbsp; <{$ch.prefix}>
        <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/admin/index.php?fct=catmanager&amp;disp=edit&amp;cat_id=<{$ch.cat_id}>">
            <{$ch.cat_title_s}></a>
        <br>
    <{/foreach}>
    <br>
<{/if}>

<{* --- form --- *}>
<form name="webphoto_form" action="<{$xoops_url}>/modules/<{$xoops_dirname}>/admin/index.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="XOOPS_G_TICKET" value="<{$xoops_g_ticket}>">
    <input type="hidden" id="fct" name="fct" value="catmanager">
    <input type="hidden" id="action" name="action" value="<{$op}>">
    <input type="hidden" id="cat_id" name="cat_id" value="<{$cat_id}>">
    <input type="hidden" id="max_file_size" name="max_file_size" value="<{$cfg_fsize}>">
    <input type="hidden" id="fieldCounter" name="fieldCounter" value="1">

    <{* --- table --- *}>
    <table class="outer" width="100%" cellpadding="4" cellspacing="1">
        <tr align="center">
            <th colspan="2"><{$lang_title}></th>
        </tr>

        <tr>
            <td class="head"><{$lang_cat_title}></td>
            <td class="odd">
                <input type="text" id="cat_title" name="cat_title" value="<{$cat_title_s}>" size="50">
            </td>
        </tr>

        <{if $show_cat_pid}>
            <tr>
                <td class="head"><{$lang_cat_th_parent}></td>
                <td class="odd">
                    <select name="cat_pid">
                        <{$cat_pid_options}>
                    </select>
                </td>
            </tr>
        <{/if}>

        <tr>
            <td class="head"><{$lang_cat_description}></td>
            <td class="odd"><{$cat_description_ele}></td>
        </tr>

        <tr>
            <td class="head">
                <{$lang_cap_cat_select}><br><br>
                <span style="font-size: 90%; font-weight: 500;"><{$lang_dsc_cat_folder}></span>
            </td>
            <td class="odd">
                <{$lang_cap_maxpixel}> <{$cfg_cat_width}> x <{$cfg_cat_width}> px<br>
                <{$lang_dsc_pixcel_resize}><br>
                <input type="hidden" id="xoops_upload_file[]" name="xoops_upload_file[]" value="file_category">
                <input type="file" id="file_category" name="file_category" size="50">
                <br><br>
                <{$lang_or}><br>
                <{$lang_cat_img_name}><br>
                <select id="cat_img_name" name="cat_img_name" size="1" onchange="showImgSelected('img_disp', 'cat_img_name', '<{$js_img_path}>', '', '<{$xoops_url}>')">
                    <{$cat_img_name_options}>
                </select>
                <br><br>
                <{$lang_or}><br>
                <{$lang_cat_img_path}><br>
                <{$lang_dsc_cat_path}><br>
                <input type="text" id="cat_img_path" name="cat_img_path" value="<{$cat_img_path_s}>" size="80">
                <br>
                <{if $img_src_s != "" }>
                    <a href="<{$img_src_s}>" target="_blank">
                        <img id="img_disp" src="<{$img_src_s}>" height="50" style="padding:3px; margin: 3px;">
                    </a>
                <{/if}>
            </td>
        </tr>

        <tr>
            <td class="head"><{$lang_cat_weight}></td>
            <td class="odd">
                <input type="text" id="cat_weight" name="cat_weight" value="<{$cat_weight}>" size="5">
            </td>
        </tr>

        <{if $show_parent_note}>
            <tr>
                <td class="head"><{$lang_cat_parent_cap}></td>
                <td class="odd"><{$parent_note_s}></td>
            </tr>
        <{/if}>

        <{if $show_cat_perm_read}>
            <tr>
                <td class="head"><{$lang_cat_perm_read}></td>
                <td class="odd">
                    <input type="checkbox" name="cat_perm_read_all" id="cat_perm_read_all" value="1" onclick="webphoto_check_all(this, 'cat_perm_read_ids')">
                    <{$lang_group_perm_all}><br>
                    <{$cat_perm_read_checkboxs}>
                </td>
            </tr>
        <{/if}>

        <tr>
            <td class="head"><{$lang_cat_perm_post}></td>
            <td class="odd">
                <input type="checkbox" name="cat_perm_post_all" id="cat_perm_post_all" value="1" onclick="webphoto_check_all(this, 'cat_perm_post_ids')">
                <{$lang_group_perm_all}><br>
                <{$cat_perm_post_checkboxs}>
            </td>
        </tr>

        <{if $show_cat_group_id }>
            <tr>
                <td class="head"><{$lang_cat_group_id}></td>
                <td class="odd">
                    <select id="cat_group_id" name="cat_group_id" size="5">
                        <{$cat_group_id_options}>
                    </select>
                </td>
            </tr>
        <{/if}>

        <{if $show_child_num }>
            <tr>
                <td class="head"><{$lang_cat_child_cap}></td>
                <td class="odd">
                    <{$lang_cat_child_num}> : <{$child_num}><br>
                    <{if $show_perm_child}>
                        <input type="checkbox" name="perm_child" id="perm_child" value="1" checked>
                        <{$lang_cat_child_perm}>
                    <{/if}>
                </td>
            </tr>
        <{/if}>

        <{if $show_gmap}>
            <tr>
                <td class="head"><{$lang_cat_gmap_latitude}></td>
                <td class="odd">
                    <input type="text" id="webphoto_gmap_latitude" name="cat_gmap_latitude" value="<{$cat_gmap_latitude}>" size="50">
                </td>
            </tr>
            <tr>
                <td class="head"><{$lang_cat_gmap_longitude}></td>
                <td class="odd">
                    <input type="text" id="webphoto_gmap_longitude" name="cat_gmap_longitude" value="<{$cat_gmap_longitude}>" size="50">
                </td>
            </tr>
            <tr>
                <td class="head"><{$lang_cat_gmap_zoom}></td>
                <td class="odd">
                    <input type="text" id="webphoto_gmap_zoom" name="cat_gmap_zoom" value="<{$cat_gmap_zoom}>" size="50">
                </td>
            </tr>
            <tr>
                <td class="head"><{$lang_gmap_icon}></td>
                <td class="odd">
                    <select id="cat_gicon_id" name="cat_gicon_id" size="1">
                        <{$cat_gicon_id_options}>
                    </select>
                </td>
            </tr>
        <{/if}>

        <{if $show_timeline}>
            <tr>
                <td class="head"><{$lang_cat_timeline_scale}></td>
                <td class="odd">
                    <select name="cat_timeline_scale">
                        <{$cat_timeline_scale_options}>
                    </select>
                </td>
            </tr>
        <{/if}>

        <tr>
            <td class="head"></td>
            <td class="odd">
                <input type="submit" id="submit" name="submit" value="<{$lang_button}>">

                <{if $is_edit}>
                    <input type="submit" id="del_confirm" name="del_confirm" value="<{$lang_delete}>">
                    <input type="reset" id="reset" name="reset" value="<{$lang_cancel}>">
                <{/if}>

            </td>
        </tr>
    </table>
    <{* --- table --- *}>

</form>
<{* --- form end --- *}>

<{if $show_gmap}>
    <iframe src="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?fct=gmap_location&amp;cat_id=<{$cat_id}>" width="100%" height="650px" frameborder="0" scrolling="yes"><{$lang_iframe_not_support}>
    </iframe>
<{/if}>

