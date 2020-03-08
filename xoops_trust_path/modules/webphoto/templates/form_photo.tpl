<{* $Id: form_photo.html,v 1.15 2010/10/08 15:53:16 ohwada Exp $ *}>

<script src="<{$xoops_url}>/modules/<{$xoops_dirname}>/libs/edit.js" type="text/javascript"></script>

<script type="text/javascript">
    //<![CDATA[
    function webphoto_gmap_disp_on() {
        var html = '<iframe src="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?fct=gmap_location" width="98%" height="650px" frameborder="0" scrolling="yes" ><{$lang_iframe_not_support}></iframe>';
        webphoto_set_gmap_iframe(html);
    }

    //]]>
</script>

<{* fckeditor *}>
<{$editor_js}>

<{* select-option-disabled *}>
<!--[if lt IE 8]>
<script src="<{$xoops_url}>/modules/<{$xoops_dirname}>/libs/select_option_disabled_emulation.js" type="text/javascript"></script>
<script type="text/javascript">
    window.onload = select_option_disabled_onload;
</script>
<![endif]-->

<{* === form === *}>
<form id="webphoto_edit" name="webphoto_edit" action="<{$action}>" method="post" enctype="multipart/form-data" <{if $show_uploading}>onsubmit="webphoto_uploading();"<{/if}> >
    <input type="hidden" name="XOOPS_G_TICKET" value="<{$xoops_g_ticket}>">
    <input type="hidden" id="fct" name="fct" value="<{$fct}>">
    <input type="hidden" id="op" name="op" value="<{$op_edit}>">
    <input type="hidden" id="max_file_size" name="max_file_size" value="<{$max_file_size}>">
    <input type="hidden" id="item_id" name="item_id" value="<{$item_id}>">
    <input type="hidden" id="photo_id" name="photo_id" value="<{$item_id}>">
    <input type="hidden" id="preview_name" name="preview_name" value="<{$preview_name}>">
    <input type="hidden" id="fieldCounter" name="fieldCounter" value="<{$field_counter}>">

    <input type="hidden" id="item_editor" name="item_editor" value="<{$item_editor_s}>">

    <{if $is_submit }>
        <input type="hidden" id="item_exif" name="item_exif" value="<{$item_exif_s}>">
        <input type="hidden" id="item_content" name="item_content" value="<{$item_content_s}>">
    <{/if}>

    <{if $show_item_cat_id_hidden }>
        <input type="hidden" id="item_cat_id" name="item_cat_id" value="<{$item_cat_id}>">
    <{/if}>

    <{if $show_desc_options_hidden }>
        <input type="hidden" id="item_description_html" name="item_description_html" value="<{$item_description_html}>">
        <input type="hidden" id="item_description_smiley" name="item_description_smiley" value="<{$item_description_smiley}>">
        <input type="hidden" id="item_description_xcode" name="item_description_xcode" value="<{$item_description_xcode}>">
        <input type="hidden" id="item_description_image" name="item_description_image" value="<{$item_description_image}>">
        <input type="hidden" id="item_description_br" name="item_description_br" value="<{$item_description_br}>">
    <{/if}>

    <{if $show_item_embed_type_hidden }>
        <input type="hidden" id="item_embed_type" name="item_embed_type" value="<{$item_embed_type_s}>">
    <{/if}>

    <{if $show_item_embed_src_hidden }>
        <input type="hidden" id="item_embed_src" name="item_embed_src" value="<{$item_embed_src_s}>">
    <{/if}>

    <{if $show_item_embed_text_hidden }>
        <input type="hidden" id="item_embed_text" name="item_embed_text" value="<{$item_embed_text_s}>">
    <{/if}>

    <{* === basic table === *}>
    <table class="outer" cellpadding="4" cellspacing="1" width="100%">
        <tr align="center">
            <th colspan="2"><{$form_title}></th>
        </tr>

        <{* item id *}>
        <{if $is_edit && $is_module_admin }>
            <tr>
                <td class="head" width="20%"><{$lang_item_id}></td>
                <td class="odd"><{$item_id}></td>
            </tr>
        <{/if}>

        <{if $show_maxsize }>
            <tr>
                <td class="head" width="20%">
                    <{$lang_cap_maxsize}>
                    <{if $lang_submit_cap_dsc_maxsize != "" }>
                        <br>
                        <br>
                        <span class="webphoto_submit_cap_dsc"><{$lang_submit_cap_dsc_maxsize}></span>
                    <{/if}>
                </td>
                <td class="odd"><{$ele_maxsize}></td>
            </tr>
        <{/if}>

        <{if $show_allowed_exts }>
            <tr>
                <td class="head" width="20%">
                    <{$lang_cap_allowed_exts}>
                    <{if $lang_submit_cap_dsc_allowed_exts != "" }>
                        <br>
                        <br>
                        <span class="webphoto_submit_cap_dsc"><{$lang_submit_cap_dsc_allowed_exts}></span>
                    <{/if}>
                </td>
                <td class="odd"><{$ele_allowed_exts}></td>
            </tr>
        <{/if}>

        <{* item cat id *}>
        <{if $show_item_cat_id }>
            <tr>
                <td class="head" width="20%">
                    <{$lang_category}>
                    <{if $lang_submit_cap_dsc_category != "" }>
                        <br>
                        <br>
                        <span class="webphoto_submit_cap_dsc"><{$lang_submit_cap_dsc_category}></span>
                    <{/if}>
                </td>
                <td class="odd">
                    <select id="item_cat_id" name="item_cat_id" size="1">
                        <{$item_cat_id_options}>
                    </select>
                </td>
            </tr>
        <{/if}>

        <{* item_perm_level *}>
        <{if $show_item_perm_level }>
            <tr>
                <td class="head" width="20%"><{$lang_item_perm_level}>
                    <{if $lang_submit_cap_dsc_perm_level != "" }>
                        <br>
                        <br>
                        <span class="webphoto_submit_cap_dsc"><{$lang_submit_cap_dsc_perm_level}></span>
                    <{/if}>
                </td>
                <td class="odd">

                    <{if $show_item_perm_level_options }>
                        <{$item_perm_level_options}>
                    <{else}>
                        <{$item_perm_level_disp}>
                        <input type="hidden" id="item_perm_level" name="item_perm_level" value="<{$item_perm_level}>">
                    <{/if}>

                </td>
            </tr>
        <{/if}>

        <{* item title *}>
        <tr>
            <td class="head" width="20%">
                <{$lang_item_title}>

                <{if $lang_submit_cap_dsc_title != "" }>
                    <br>
                    <br>
                    <span class="webphoto_submit_cap_dsc"><{$lang_submit_cap_dsc_title}></span>
                <{/if}>

            </td>
            <td class="odd">
                <input type="text" id="item_title" name="item_title" value="<{$item_title_s}>" size="50">
                <br>

                <{if $show_embed_support_title }>
                    <{$lang_embed_support_title}>
                <{else}>
                    <{$lang_dsc_title_blank}>
                <{/if}>

            </td>
        </tr>

        <{* item editor *}>
        <{if $show_item_editor }>
            <tr>
                <td class="head" width="20%"><{$lang_item_editor}></td>
                <td class="odd">
                    <{$item_editor_s}>
                </td>
            </tr>
        <{/if}>

        <tr>
            <td class="head" width="20%">
                <{$lang_item_description}>
                <{if $lang_submit_cap_dsc_description != "" }>
                    <br>
                    <br>
                    <span class="webphoto_submit_cap_dsc"><{$lang_submit_cap_dsc_description}></span>
                <{/if}>
            </td>
            <td class="odd">
                <{$ele_item_description}>

                <{if $show_embed_support_description }>
                    <br>
                    <{$lang_embed_support_description}>
                <{/if}>

            </td>
        </tr>

        <{* item description options *}>
        <{if $show_desc_options }>
            <tr>
                <td class="head" width="20%"><{$lang_cap_description_option}></td>
                <td class="odd">
                    <input type="checkbox" id="item_description_html" name="item_description_html" value="1" <{$item_description_html_checked}> >
                    <{$lang_cap_html}><br>
                    <input type="checkbox" id="item_description_smiley" name="item_description_smiley" value="1" <{$item_description_smiley_checked}> >
                    <{$lang_cap_smiley}><br>
                    <input type="checkbox" id="item_description_xcode" name="item_description_xcode" value="1" <{$item_description_xcode_checked}> >
                    <{$lang_cap_xcode}><br>
                    <input type="checkbox" id="item_description_image" name="item_description_image" value="1" <{$item_description_image_checked}> >
                    <{$lang_cap_image}><br>
                    <input type="checkbox" id="item_description_br" name="item_description_br" value="1" <{$item_description_br_checked}> >
                    <{$lang_cap_br}><br>
                </td>
            </tr>
        <{/if}>

        <{* item description scroll *}>
        <{if $show_item_description_scroll }>
            <tr>
                <td class="head" width="20%"><{$lang_item_description_scroll}></td>
                <td class="odd">
                    <input type="text" id="item_description_scroll" name="item_description_scroll" value="<{$item_description_scroll}>">
                    <br>
                    <{$lang_item_description_scroll_dsc}>
                </td>
            </tr>
        <{/if}>

        <{* item embed *}>
        <{if $show_item_embed_type }>
            <tr>
                <td class="head" width="20%"><{$lang_item_embed_type}></td>
                <td class="odd">
                    <{$item_embed_type_s}>
                    <input type="hidden" id="item_embed_type" name="item_embed_type" value="<{$item_embed_type_s}>">
                </td>
            </tr>
        <{/if}>

        <{* item embed src *}>
        <{if $show_item_embed_src }>
            <tr>
                <td class="head" width="20%"><{$lang_item_embed_src}></td>
                <td class="odd">
                    <input type="text" id="item_embed_src" name="item_embed_src" value="<{$item_embed_src_s}>" size="50">

                    <{if $embed_src_dsc != '' }>
                        <br>
                        <{$embed_src_dsc}>
                    <{/if}>

                </td>
            </tr>
        <{/if}>

        <{if $show_item_siteurl_1st }>
            <tr>
                <td class="head" width="20%"><{$lang_item_siteurl}></td>
                <td class="odd">
                    <input type="text" id="item_siteurl" name="item_siteurl" value="<{$item_siteurl_s}>" size="50">

                    <{if $show_embed_support_siteurl }>
                        <br>
                        <{$lang_embed_support_siteurl}>
                    <{/if}>

                </td>
            </tr>
        <{/if}>

        <{* item embed text *}>
        <{if $show_item_embed_text }>
            <tr>
                <td class="head" width="20%"><{$lang_item_embed_text}></td>
                <td class="odd">
                    <textarea id="item_embed_text" name="item_embed_text" rows="5" cols="80"><{$item_embed_text_s}></textarea>

                    <{if $show_embed_support_embed_text }>
                        <br>
                        <{$lang_embed_support_embed_text}>
                    <{/if}>

                </td>
            </tr>
        <{/if}>

        <{* --- file photo --- *}>
        <{if $show_file_photo }>
            <tr>
                <td class="head" width="20%"><{$lang_cap_photo_select}>
                    <{if $lang_submit_cap_dsc_file_photo != "" }>
                        <br>
                        <br>
                        <span class="webphoto_submit_cap_dsc"><{$lang_submit_cap_dsc_file_photo}></span>
                    <{/if}>
                </td>
                <td class="odd">
                    <input type="hidden" id="xoops_upload_file[]" name="xoops_upload_file[]" value="file_photo">
                    <input type="file" id="file_photo" name="file_photo" size="50">
                    <br>

                    <{if $lang_submit_dsc_file_photo != "" }>
                        <{$lang_submit_dsc_file_photo}>
                        <br>
                    <{/if}>

                    <br>

                    <{if $show_item_external_url }>
                        <{$lang_or}> <{$lang_item_external_url}>
                        <br>
                        <input type="text" id="item_external_url" name="item_external_url" value="<{$item_external_url_s}>" size="80">
                        <br>
                        <br>
                    <{/if}>

                    <{if $photo_url_s != '' }>
                        <a href="<{$photo_url_s}>" title="photo" target="_blank"><{$photo_url_s}></a>
                        <br>
                    <{/if}>

                    <{if $show_file_photo_delete }>
                        <input type="submit" id="file_photo_delete" name="file_photo_delete" value="<{$lang_delete}>">
                    <{/if}>

                </td>
            </tr>
        <{/if}>

        <{* rotate *}>
        <{if $show_rotate }>
            <tr>
                <td class="head" width="20%"><{$lang_radio_rotatetitle}></td>
                <td class="odd">
                    <input type="radio" name="rotate" value="rot0" <{$rotate_checked.rot0}> >
                    <{$lang_radio_rotate0}> &nbsp;
                    <input type="radio" name="rotate" value="rot90" <{$rotate_checked.rot90}> >
                    <img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/uploader/icon_rotate90.png" alt="<{$lang_radio_rotate90}>" title="<{$lang_radio_rotate90}>">
                    &nbsp;
                    <input type="radio" name="rotate" value="rot180" <{$rotate_checked.rot180}> >
                    <img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/uploader/icon_rotate180.png" alt="<{$lang_radio_rotate180}>" title="<{$lang_radio_rotate180}>">
                    &nbsp;
                    <input type="radio" name="rotate" value="rot270" <{$rotate_checked.rot270}> >
                    <img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/uploader/icon_rotate270.png" alt="<{$lang_radio_rotate270}>" title="<{$lang_radio_rotate270}>">
                </td>
            </tr>
        <{/if}>

        <{* file *}>
        <{if $show_file_ftp }>
            <tr>
                <td class="head" width="20%"><{$lang_cap_file_select}></td>
                <td class="odd">
                    <{if $file_select_options != '' }>
                        <select id="file" name="file" size="5">
                            <{$file_select_options}>
                        </select>
                    <{else}>
                        ---
                    <{/if}>
                </td>
            </tr>
        <{/if}>

        <{* file id array *}>
        <{if $show_file_ids }>
            <{foreach from=$file_id_array item=file_id }>
                <tr>
                    <td class="head" width="20%"><{$lang_cap_photo_select}> <{$file_id}></td>
                    <td class="odd">
                        <input type="hidden" id="xoops_upload_file[]" name="xoops_upload_file[]" value="file_<{$file_id}>">
                        <input type="file" id="file_<{$file_id}>" name="file_<{$file_id}>" size="50">
                    </td>
                </tr>
            <{/foreach}>
        <{/if}>

        <{if $show_batch_uid }>
            <tr>
                <td class="head" width="20%"><{$lang_submitter}></td>
                <td class="odd">
                    <select name="item_uid">
                        <{$item_uid_options}>
                    </select>
                </td>
            </tr>
        <{/if}>

        <{if $show_batch_update }>
            <tr>
                <td class="head" width="20%"><{$lang_photo_time_update}></td>
                <td class="odd">
                    <input type="text" id="item_time_update_disp" name="item_time_update_disp" value="<{$item_time_update_disp}>" size="50">
                </td>
            </tr>
        <{/if}>

        <{if $show_batch_dir }>
            <tr>
                <td class="head" width="20%"><{$lang_text_directory}></td>
                <td class="odd">
                    <{$lang_photopath}>
                    <input type="text" id="batch_dir" name="batch_dir" value="<{$batch_dir_s}>" size="50">
                    <br><{$lang_desc_photopath}>
                </td>
            </tr>
        <{/if}>

        <{if $show_detail_div }>
            <tr>
                <td class="head" width="20%"><{$lang_cap_detail}></td>
                <td class="odd">
                    <input type="checkbox" id="webphoto_form_detail_onoff" name="webphoto_form_detail_onoff" onclick="webphoto_detail_disp_onclick(this)">
                    <{$lang_cap_detail_onoff}>
                </td>
            </tr>
        <{/if}>

    </table>
    <{* basic table end *}>

    <{* === detail div === *}>
    <{if $show_detail_div }>
    <div id="webphoto_detail" style="display: none;">
        <{/if}>

        <{* === detail table === *}>
        <{if $show_detail_table }>
            <table class="outer" cellpadding="4" cellspacing="1" width="100%">

                <{* item_datetime *}>
                <{if $show_item_datetime }>
                    <tr>
                        <td class="head" width="20%"><{$lang_item_datetime}></td>
                        <td class="odd">
                            <input type="checkbox" name="item_datetime_checkbox" id="item_datetime_checkbox" value="1" <{$item_datetime_checkbox_checked}> >
                            <{$lang_dsc_set_datetime}><br>
                            <input type="text" id="item_datetime" name="item_datetime" value="<{$item_datetime_val_s}>" size="50">
                        </td>
                    </tr>
                <{/if}>

                <{if $show_item_place }>
                    <tr>
                        <td class="head" width="20%"><{$lang_item_place}></td>
                        <td class="odd">
                            <input type="text" id="item_place" name="item_place" value="<{$item_place_s}>" size="50">
                        </td>
                    </tr>
                <{/if}>

                <{if $show_item_equipment }>
                    <tr>
                        <td class="head" width="20%"><{$lang_item_equipment}></td>
                        <td class="odd">
                            <input type="text" id="item_equipment" name="item_equipment" value="<{$item_equipment_s}>" size="50">
                        </td>
                    </tr>
                <{/if}>

                <{if $show_item_duration }>
                    <tr>
                        <td class="head" width="20%"><{$lang_item_duration}> ( <{$lang_second}> )</td>
                        <td class="odd">
                            <input type="text" id="item_duration" name="item_duration" value="<{$item_duration_s}>" size="50">

                            <{if $show_embed_support_duration }>
                                <br>
                                <{$lang_embed_support_duration}>
                            <{/if}>

                        </td>
                    </tr>
                <{/if}>

                <{if $show_item_siteurl_2nd }>
                    <tr>
                        <td class="head" width="20%"><{$lang_item_siteurl}></td>
                        <td class="odd">
                            <input type="text" id="item_siteurl" name="item_siteurl" value="<{$item_siteurl_s}>" size="50">
                        </td>
                    </tr>
                <{/if}>

                <{if $show_item_artist }>
                    <tr>
                        <td class="head" width="20%"><{$lang_item_artist}></td>
                        <td class="odd">
                            <input type="text" id="item_artist" name="item_artist" value="<{$item_artist_s}>" size="50">
                        </td>
                    </tr>
                <{/if}>

                <{if $show_item_album }>
                    <tr>
                        <td class="head" width="20%"><{$lang_item_album}></td>
                        <td class="odd">
                            <input type="text" id="item_album" name="item_album" value="<{$item_album_s}>" size="50">
                        </td>
                    </tr>
                <{/if}>

                <{if $show_item_label }>
                    <tr>
                        <td class="head" width="20%"><{$lang_item_label}></td>
                        <td class="odd">
                            <input type="text" id="item_label" name="item_label" value="<{$item_label_s}>" size="50">
                        </td>
                    </tr>
                <{/if}>

                <{* item_text 1 - 10 *}>
                <{foreach item=item_text from=$item_text_array}>
                    <{if $item_text.show }>
                        <tr>
                            <td class="head" width="20%"><{$item_text.title_s}></td>
                            <td class="odd">
                                <input type="text" id="<{$item_text.name}>" name="<{$item_text.name}>" value="<{$item_text.value_s}>" size="50">
                            </td>
                        </tr>
                    <{/if}>
                <{/foreach}>

                <{if $show_item_page_width }>
                    <tr>
                        <td class="head" width="20%"><{$lang_item_page_width}></td>
                        <td class="odd">
                            <input type="text" id="item_page_width" name="item_page_width" value="<{$item_page_width}>" size="50">
                        </td>
                    </tr>
                <{/if}>

                <{if $show_item_page_height }>
                    <tr>
                        <td class="head" width="20%"><{$lang_item_page_height}></td>
                        <td class="odd">
                            <input type="text" id="item_page_height" name="item_page_height" value="<{$item_page_height}>" size="50">
                        </td>
                    </tr>
                <{/if}>

                <{if $show_item_exif }>
                    <tr>
                        <td class="head" width="20%"><{$lang_item_exif}></td>
                        <td class="odd">
                            <textarea id="item_exif" name="item_exif" rows="5" cols="80"><{$item_exif_s}></textarea>
                        </td>
                    </tr>
                <{/if}>

                <{if $show_item_content }>
                    <tr>
                        <td class="head" width="20%"><{$lang_item_content}></td>
                        <td class="odd">
                            <textarea id="item_content" name="item_content" rows="5" cols="80"><{$item_content_s}></textarea>
                        </td>
                    </tr>
                <{/if}>

                <{* tags *}>
                <{if $show_tags }>
                    <tr>
                        <td class="head" width="20%"><{$lang_tags}></td>
                        <td class="odd">
                            <input type="text" id="tags" name="tags" value="<{$tags_val_s}>" size="80">
                            <br><{$lang_dsc_tag_divid}>

                            <{if $show_embed_support_tags }>
                                <br>
                                <{$lang_embed_support_tags}>
                            <{/if}>

                        </td>
                    </tr>
                <{/if}>

                <{* --- file jpeg --- *}>
                <{if $show_file_jpeg }>
                    <tr>
                        <td class="head" width="20%"><{$lang_cap_jpeg_select}></td>
                        <td class="odd">
                            <input type="hidden" id="xoops_upload_file[]" name="xoops_upload_file[]" value="file_jpeg">
                            <input type="file" id="file_jpeg" name="file_jpeg" size="50">
                            <br>
                            <{$lang_file_jpeg_dsc}>
                            <br><br>

                            <{if $show_item_external_thumb }>
                                <{$lang_or}> <{$lang_item_external_thumb}>
                                <br>
                                <input type="text" id="item_external_thumb" name="item_external_thumb" value="<{$item_external_thumb_s}>" size="80">
                                <br>
                                <br>
                            <{/if}>

                            <{if $show_item_icon_name }>
                                <{$lang_or}> <{$lang_item_icon_name}>
                                <br>
                                <div style="border: #808080 1px solid; padding: 1px; width:80%;">
                                    <{$item_icon_name_s}>
                                </div>
                                <br>
                            <{/if}>

                            <{if $show_thumb_dsc_select }>
                                <{$lang_dsc_thumb_select}>
                                <br>
                            <{/if}>

                            <{if $show_thumb_dsc_embed }>
                                <{$lang_embed_thumb}>
                                <br>
                            <{/if}>

                            <{if $jpeg_url_s != '' }>
                                <br>
                                <a href="<{$jpeg_url_s}>" title="jpeg" target="_blank"><{$jpeg_url_s}></a>
                                <br>
                            <{/if}>

                            <{if $show_file_jpeg_delete }>
                                <input type="submit" id="file_jpeg_delete" name="file_jpeg_delete" value="<{$lang_delete}>">
                                <{$lang_file_jpeg_delete_dsc}>
                            <{/if}>

                        </td>
                    </tr>
                <{/if}>

                <{* item file others *}>
                <{foreach item=item_file from=$item_file_array}>
                    <{if $item_file.url_s != '' }>
                        <tr>
                            <td class="head" width="20%"><{$item_file.title_s}></td>
                            <td class="odd">
                                <a href="<{$item_file.url_s}>" target="_blank"><{$item_file.url_s}></a>
                            </td>
                        </tr>
                    <{/if}>
                <{/foreach}>

                <{* item_perm_read *}>
                <{if $show_item_perm_read }>
                    <tr>
                        <td class="head" width="20%"><{$lang_item_perm_read}></td>
                        <td class="odd">

                            <{if $show_input_item_perm_read }>
                                <input type="checkbox" name="item_perm_read_all" id="item_perm_read_all" value="1" onclick="webphoto_check_all(this, 'item_perm_read_ids')">
                                <{$lang_group_perm_all}>
                                <br>
                                <{$item_perm_read_input_checkboxs}>

                            <{else}>
                                <{$item_perm_read_list}>
                                <{$item_perm_read_hiddens}>
                            <{/if}>

                        </td>
                    </tr>
                <{/if}>

                <{* item_perm_down *}>
                <{if $show_item_perm_down }>
                    <tr>
                        <td class="head" width="20%"><{$lang_item_perm_down}></td>
                        <td class="odd">

                            <{if $show_input_item_perm_down }>
                                <input type="checkbox" name="item_perm_down_all" id="item_perm_down_all" value="1" onclick="webphoto_check_all(this, 'item_perm_down_ids')">
                                <{$lang_group_perm_all}>
                                <br>
                                <{$item_perm_down_input_checkboxs}>

                            <{else}>
                                <{$item_perm_down_list}>
                                <{$item_perm_down_hiddens}>
                            <{/if}>

                        </td>
                    </tr>
                <{/if}>

                <{* item_codeinfo *}>
                <{if $show_item_codeinfo }>
                    <tr>
                        <td class="head" width="20%"><{$lang_item_codeinfo}></td>
                        <td class="odd">
                            <select id="item_codeinfo" name="item_codeinfo[]" size="5" multiple="multiple">
                                <{$item_codeinfo_select_options}>
                            </select>
                        </td>
                    </tr>
                <{/if}>

                <{* item_gmap *}>
                <{if $show_gmap_ele }>
                    <tr>
                        <td class="head" width="20%"><{$lang_item_gmap_latitude}></td>
                        <td class="odd">
                            <input type="text" id="webphoto_gmap_latitude" name="item_gmap_latitude" value="<{$item_gmap_latitude}>" size="50">
                        </td>
                    </tr>
                    <tr>
                        <td class="head" width="20%"><{$lang_item_gmap_longitude}></td>
                        <td class="odd">
                            <input type="text" id="webphoto_gmap_longitude" name="item_gmap_longitude" value="<{$item_gmap_longitude}>" size="50">
                        </td>
                    </tr>
                    <tr>
                        <td class="head" width="20%"><{$lang_item_gmap_zoom}></td>
                        <td class="odd">
                            <input type="text" id="webphoto_gmap_zoom" name="item_gmap_zoom" value="<{$item_gmap_zoom}>" size="50">
                        </td>
                    </tr>
                    <{* item_gicon_id *}>
                    <tr>
                        <td class="head" width="20%"><{$lang_gmap_icon}></td>
                        <td class="odd">
                            <select id="item_gicon_id" name="item_gicon_id" size="1">
                                <{$item_gicon_id_select_options}>
                            </select>
                        </td>
                    </tr>
                <{/if}>

                <{if $show_gmap_onoff }>
                    <tr>
                        <td class="head" width="20%">google map</td>
                        <td class="odd">
                            <input type="checkbox" id="webphoto_form_gmap_onoff" name="webphoto_form_gmap_onoff" onclick="webphoto_gmap_disp_onclick(this)">
                            <{$lang_cap_detail_onoff}>
                        </td>
                    </tr>
                <{/if}>

            </table>
        <{/if}>
        <{* detail table end *}>

        <{* === gmap table === *}>
        <{if $show_gmap_table }>
            <table class="outer" cellpadding="4" cellspacing="1" width="100%">
                <tr>
                    <td style="background-color:#ffffff;"><{* white *}>

                        <{if $show_gmap_onoff }>
                            <div id="webphoto_gmap_iframe"></div>
                        <{/if}>

                        <{if $is_edit }>
                            <iframe width="98%" height="650px" frameborder="0" scrolling="yes"
                                    src="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?fct=gmap_location&amp;photo_id=<{$item_id}>">
                                <{$lang_iframe_not_support}>
                            </iframe>
                        <{/if}>

                    </td>
                </tr>
            </table>
        <{/if}>
        <{* gmap table end *}>

        <{if $show_detail_div }>
    </div>
    <{/if}>
    <{* detail div end *}>

    <{* === submit table === *}>
    <table class="outer" cellpadding="4" cellspacing="1" width="100%">
        <tr>
            <td class="head" width="20%"></td>
            <td class="head">
                <input type="submit" id="webphoto_photo_submit" name="submit" value="<{$button_submit}>">

                <{if $show_button_preview }>
                    <input type="submit" id="webphoto_photo_preview" name="preview" value="<{$lang_preview}>">
                <{/if}>

                <input type="reset" id="webphoto_photo_reset" name="reset" value="<{$lang_cancel}>">

                <{if $show_button_delete }>
                    <input type="submit" id="webphoto_photo_conf_delete" name="conf_delete" value="<{$lang_delete}>">
                <{/if}>

                <{if $show_button_preview && $lang_submit_dsc_preview }>
                    <br>
                    <{$lang_submit_dsc_preview}>
                <{/if}>

            </td>
        </tr>
    </table>
    <{* submit table end *}>

</form>

<{* === detail div === *}>
<{if $show_detail_div_on }>
    <script type="text/javascript">
        //<![CDATA[
        webphoto_detail_checkbox_onoff(true);
        //]]>
    </script>
<{/if}>

