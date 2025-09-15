<?php
/**
 * Services Post Type
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// ============================================================================
// SERVICES POST TYPE
// ============================================================================

add_action('init', 'cda_register_services_post_type');
function cda_register_services_post_type() {
    register_post_type('services', array(
        'labels' => array(
            'name' => 'Services',
            'singular_name' => 'Service',
            'menu_name' => 'Services',
            'add_new' => 'Add Service',
            'add_new_item' => 'Add New Service',
            'edit_item' => 'Edit Service',
            'new_item' => 'New Service',
            'view_item' => 'View Service',
            'view_items' => 'View Services',
            'search_items' => 'Search Services',
            'not_found' => 'No services found',
            'not_found_in_trash' => 'No services found in Trash',
            'all_items' => 'All Services',
            'archives' => 'Service Archives',
            'attributes' => 'Service Attributes',
            'insert_into_item' => 'Insert into service',
            'uploaded_to_this_item' => 'Uploaded to this service',
            'featured_image' => 'Service Image',
            'set_featured_image' => 'Set service image',
            'remove_featured_image' => 'Remove service image',
            'use_featured_image' => 'Use as service image',
        ),
        'description' => 'CDA Services - Different services offered by CDA',
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array(
            'slug' => 'services',
            'with_front' => false,
        ),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 20,
        'menu_icon' => 'dashicons-portfolio',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes'),
        'show_in_rest' => true,
        'rest_base' => 'services',
        'show_in_graphql' => true,
        'graphql_single_name' => 'service',
        'graphql_plural_name' => 'services',
    ));
}
