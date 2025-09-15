<?php
/**
 * Technologies Post Type
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// ============================================================================
// TECHNOLOGIES POST TYPE
// ============================================================================

add_action('init', 'cda_register_technologies_post_type');
function cda_register_technologies_post_type() {
    register_post_type('technologies', array(
        'labels' => array(
            'name' => 'Technologies',
            'singular_name' => 'Technology',
            'menu_name' => 'Technologies',
            'add_new' => 'Add Technology',
            'add_new_item' => 'Add New Technology',
            'edit_item' => 'Edit Technology',
            'new_item' => 'New Technology',
            'view_item' => 'View Technology',
            'view_items' => 'View Technologies',
            'search_items' => 'Search Technologies',
            'not_found' => 'No technologies found',
            'not_found_in_trash' => 'No technologies found in Trash',
            'all_items' => 'All Technologies',
            'archives' => 'Technology Archives',
            'attributes' => 'Technology Attributes',
            'insert_into_item' => 'Insert into technology',
            'uploaded_to_this_item' => 'Uploaded to this technology',
            'featured_image' => 'Technology Logo',
            'set_featured_image' => 'Set technology logo',
            'remove_featured_image' => 'Remove technology logo',
            'use_featured_image' => 'Use as technology logo',
        ),
        'description' => 'CDA Technologies - Tools, frameworks, and platforms we use',
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array(
            'slug' => 'technologies',
            'with_front' => false,
        ),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 24,
        'menu_icon' => 'dashicons-admin-tools',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes'),
        'show_in_rest' => true,
        'rest_base' => 'technologies',
        'show_in_graphql' => true,
        'graphql_single_name' => 'technology',
        'graphql_plural_name' => 'technologies',
    ));
}
