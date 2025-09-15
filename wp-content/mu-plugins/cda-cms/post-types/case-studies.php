<?php
/**
 * Case Studies Post Type
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// ============================================================================
// CASE STUDIES POST TYPE
// ============================================================================

add_action('init', 'cda_register_case_studies_post_type');
function cda_register_case_studies_post_type() {
    register_post_type('case_studies', array(
        'labels' => array(
            'name' => 'Case Studies',
            'singular_name' => 'Case Study',
            'menu_name' => 'Case Studies',
            'add_new' => 'Add Case Study',
            'add_new_item' => 'Add New Case Study',
            'edit_item' => 'Edit Case Study',
            'new_item' => 'New Case Study',
            'view_item' => 'View Case Study',
            'view_items' => 'View Case Studies',
            'search_items' => 'Search Case Studies',
            'not_found' => 'No case studies found',
            'not_found_in_trash' => 'No case studies found in Trash',
            'all_items' => 'All Case Studies',
            'archives' => 'Case Study Archives',
            'attributes' => 'Case Study Attributes',
            'insert_into_item' => 'Insert into case study',
            'uploaded_to_this_item' => 'Uploaded to this case study',
            'featured_image' => 'Case Study Image',
            'set_featured_image' => 'Set case study image',
            'remove_featured_image' => 'Remove case study image',
            'use_featured_image' => 'Use as case study image',
        ),
        'description' => 'CDA Case Studies - Client project showcases and success stories',
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array(
            'slug' => 'case-studies',
            'with_front' => false,
        ),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 21,
        'menu_icon' => 'dashicons-chart-line',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes'),
        'show_in_rest' => true,
        'rest_base' => 'case-studies',
        'show_in_graphql' => true,
        'graphql_single_name' => 'caseStudy',
        'graphql_plural_name' => 'caseStudies',
    ));
}
