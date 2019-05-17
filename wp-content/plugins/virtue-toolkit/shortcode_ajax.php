<?php 
function virtue_toolkit_columns_ajax_tinymce() {
    if (!current_user_can('edit_pages') && !current_user_can('edit_posts')){
        die(__("You are not allowed to be here"));
    }

    include_once( dirname(dirname(__FILE__)) . '/virtue-toolkit/shortcodes/columns/columns_popup.php');
}
add_action('wp_ajax_kadcolumns_tinymce', 'virtue_toolkit_columns_ajax_tinymce');

function virtue_toolkit_icons_ajax_tinymce() {
    if (!current_user_can('edit_pages') && !current_user_can('edit_posts')) {
        die(__("You are not allowed to be here"));
    }

    include_once( dirname(dirname(__FILE__)) . '/virtue-toolkit/shortcodes/icons/icon_popup.php');
}
add_action('wp_ajax_kadicons_tinymce', 'virtue_toolkit_icons_ajax_tinymce');

function virtue_toolkit_quote_ajax_tinymce() {
    if (!current_user_can('edit_pages') && !current_user_can('edit_posts')) {
        die(__("You are not allowed to be here"));
    }

    include_once( dirname(dirname(__FILE__)) . '/virtue-toolkit/shortcodes/pullquote/quote_popup.php');
}
add_action('wp_ajax_kadquote_tinymce', 'virtue_toolkit_quote_ajax_tinymce');

function virtue_toolkit_youtube_ajax_tinymce() {
    if (!current_user_can('edit_pages') && !current_user_can('edit_posts')) {
        die(__("You are not allowed to be here"));
    }

    include_once( dirname(dirname(__FILE__)) . '/virtue-toolkit/shortcodes/youtube/youtube_popup.php');
}
add_action('wp_ajax_kadyoutube_tinymce', 'virtue_toolkit_youtube_ajax_tinymce');

function virtue_toolkit_vimeo_ajax_tinymce() {
    if (!current_user_can('edit_pages') && !current_user_can('edit_posts')) {
        die(__("You are not allowed to be here"));
    }

    include_once( dirname(dirname(__FILE__)) . '/virtue-toolkit/shortcodes/vimeo/vimeo_popup.php');
}
add_action('wp_ajax_kadvimeo_tinymce', 'virtue_toolkit_vimeo_ajax_tinymce');

function virtue_toolkit_btns_ajax_tinymce() {
    if (!current_user_can('edit_pages') && !current_user_can('edit_posts')) {
        die(__("You are not allowed to be here"));
    }

    include_once( dirname(dirname(__FILE__)) . '/virtue-toolkit/shortcodes/btns/btns_popup.php');
}
add_action('wp_ajax_kadbtns_tinymce', 'virtue_toolkit_btns_ajax_tinymce');

function virtue_toolkit_divider_ajax_tinymce() {
    if (!current_user_can('edit_pages') && !current_user_can('edit_posts')) {
        die(__("You are not allowed to be here"));
    }

    include_once( dirname(dirname(__FILE__)) . '/virtue-toolkit/shortcodes/divider/divider_popup.php');
}
add_action('wp_ajax_kaddivider_tinymce', 'virtue_toolkit_divider_ajax_tinymce');

function virtue_toolkit_accordion_ajax_tinymce() {
    if (!current_user_can('edit_pages') && !current_user_can('edit_posts')) {
        die(__("You are not allowed to be here"));
    }

    include_once( dirname(dirname(__FILE__)) . '/virtue-toolkit/shortcodes/accordion/accordion_popup.php');
}
add_action('wp_ajax_kadaccordion_tinymce', 'virtue_toolkit_accordion_ajax_tinymce');