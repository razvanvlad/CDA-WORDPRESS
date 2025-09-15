<?php
/**
 * Job Listings Post Type
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// ============================================================================
// JOB LISTINGS POST TYPE
// ============================================================================

add_action('init', 'cda_register_job_listings_post_type');
function cda_register_job_listings_post_type() {
    register_post_type('job_listings', array(
        'labels' => array(
            'name' => 'Job Listings',
            'singular_name' => 'Job Listing',
            'menu_name' => 'Jobs',
            'add_new' => 'Add Job',
            'add_new_item' => 'Add New Job Listing',
            'edit_item' => 'Edit Job Listing',
            'new_item' => 'New Job Listing',
            'view_item' => 'View Job Listing',
            'view_items' => 'View Job Listings',
            'search_items' => 'Search Jobs',
            'not_found' => 'No jobs found',
            'not_found_in_trash' => 'No jobs found in Trash',
            'all_items' => 'All Jobs',
            'archives' => 'Job Archives',
            'attributes' => 'Job Attributes',
            'insert_into_item' => 'Insert into job listing',
            'uploaded_to_this_item' => 'Uploaded to this job listing',
            'featured_image' => 'Job Image',
            'set_featured_image' => 'Set job image',
            'remove_featured_image' => 'Remove job image',
            'use_featured_image' => 'Use as job image',
        ),
        'description' => 'CDA Job Listings - Open positions and career opportunities',
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array(
            'slug' => 'careers',
            'with_front' => false,
        ),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 23,
        'menu_icon' => 'dashicons-businessperson',
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields', 'excerpt'),
        'show_in_rest' => true,
        'rest_base' => 'job-listings',
        'show_in_graphql' => true,
        'graphql_single_name' => 'jobListing',
        'graphql_plural_name' => 'jobListings',
    ));
}
