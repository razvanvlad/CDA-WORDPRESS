<?php
/**
 * CDA Theme functions and definitions
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Theme setup
function cdatheme_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    add_theme_support('customize-selective-refresh-widgets');
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'cdatheme'),
        'footer' => __('Footer Menu', 'cdatheme')
    ));
}
add_action('after_setup_theme', 'cdatheme_setup');

// Enqueue scripts and styles
function cdatheme_scripts() {
    // Remove any frontend styles since we're using headless
    // We only need this for admin functionality
}
add_action('wp_enqueue_scripts', 'cdatheme_scripts');

function add_cors_headers() {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
}
add_action('init', 'add_cors_headers');

// Register widget areas
function cdatheme_widgets_init() {
    register_sidebar(array(
        'name' => __('Sidebar', 'cdatheme'),
        'id' => 'sidebar-1',
        'description' => __('Add widgets here.', 'cdatheme'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
}
add_action('widgets_init', 'cdatheme_widgets_init');

// Add Global Content Options in cda-cms.php
if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title' => 'Global Content',
        'menu_title' => 'Global Content',
        'menu_slug' => 'global-content',
        'capability' => 'edit_posts',
        'redirect' => false,
        'show_in_graphql' => true,
        'graphql_field_name' => 'globalOptions'
    ));
}

// Add Header and Footer Fields
add_action('acf/init', 'cda_add_global_content_fields');
function cda_add_global_content_fields() {
    // Header Content
    acf_add_local_field_group(array(
        'key' => 'group_header_content',
        'title' => 'Header Content',
        'fields' => array(
            array(
                'key' => 'field_header_logo',
                'label' => 'Header Logo',
                'name' => 'header_logo',
                'type' => 'image',
                'return_format' => 'array',
                'show_in_graphql' => 1,
            ),
            array(
                'key' => 'field_header_phone',
                'label' => 'Phone Number',
                'name' => 'header_phone',
                'type' => 'text',
                'show_in_graphql' => 1,
            ),
            array(
                'key' => 'field_header_email',
                'label' => 'Email Address',
                'name' => 'header_email',
                'type' => 'email',
                'show_in_graphql' => 1,
            )
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'global-content'
                )
            )
        ),
        'show_in_graphql' => 1,
        'graphql_field_name' => 'headerContent'
    ));

    // Footer Content
    acf_add_local_field_group(array(
        'key' => 'group_footer_content',
        'title' => 'Footer Content',
        'fields' => array(
            array(
                'key' => 'field_footer_logo',
                'label' => 'Footer Logo',
                'name' => 'footer_logo',
                'type' => 'image',
                'return_format' => 'array',
                'show_in_graphql' => 1,
            ),
            array(
                'key' => 'field_footer_text',
                'label' => 'Footer Text',
                'name' => 'footer_text',
                'type' => 'wysiwyg',
                'show_in_graphql' => 1,
            ),
            array(
                'key' => 'field_footer_copyright',
                'label' => 'Copyright Text',
                'name' => 'footer_copyright',
                'type' => 'text',
                'default_value' => '© ' . date('Y') . ' All rights reserved.',
                'show_in_graphql' => 1,
            ),
            array(
                'key' => 'field_footer_social_links',
                'label' => 'Social Links',
                'name' => 'footer_social_links',
                'type' => 'repeater',
                'show_in_graphql' => 1,
                'sub_fields' => array(
                    array(
                        'key' => 'field_social_platform',
                        'label' => 'Platform',
                        'name' => 'platform',
                        'type' => 'select',
                        'choices' => array(
                            'facebook' => 'Facebook',
                            'twitter' => 'Twitter',
                            'instagram' => 'Instagram',
                            'linkedin' => 'LinkedIn',
                            'youtube' => 'YouTube'
                        ),
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_social_url',
                        'label' => 'URL',
                        'name' => 'url',
                        'type' => 'url',
                        'show_in_graphql' => 1,
                    )
                )
            )
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'global-content'
                )
            )
        ),
        'show_in_graphql' => 1,
        'graphql_field_name' => 'footerContent'
    ));
}

?>