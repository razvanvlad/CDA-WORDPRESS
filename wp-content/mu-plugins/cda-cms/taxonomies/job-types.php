<?php
/**
 * Job Types Taxonomy
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// ============================================================================
// JOB TYPES TAXONOMY (for Job Listings)
// ============================================================================

add_action('init', 'cda_register_job_types_taxonomy');
function cda_register_job_types_taxonomy() {
    register_taxonomy('job_types', array('job_listings'), array(
        'labels' => array(
            'name' => 'Job Types',
            'singular_name' => 'Job Type',
            'menu_name' => 'Job Types',
            'all_items' => 'All Job Types',
            'edit_item' => 'Edit Job Type',
            'view_item' => 'View Job Type',
            'update_item' => 'Update Job Type',
            'add_new_item' => 'Add New Job Type',
            'new_item_name' => 'New Job Type Name',
            'search_items' => 'Search Job Types',
            'not_found' => 'No job types found',
        ),
        'description' => 'Categories for different types of job positions',
        'public' => true,
        'publicly_queryable' => true,
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'rest_base' => 'job-types',
        'show_admin_column' => true,
        'show_in_graphql' => true,
        'graphql_single_name' => 'jobType',
        'graphql_plural_name' => 'jobTypes',
        'rewrite' => array(
            'slug' => 'job-type',
            'with_front' => false,
            'hierarchical' => true,
        ),
    ));
    
    // Add default job types
    $default_job_types = array(
        'full-time' => 'Full-Time',
        'part-time' => 'Part-Time',
        'contract' => 'Contract',
        'remote' => 'Remote',
        'hybrid' => 'Hybrid',
        'internship' => 'Internship',
    );
    
    foreach ($default_job_types as $slug => $name) {
        if (!term_exists($name, 'job_types')) {
            wp_insert_term($name, 'job_types', array('slug' => $slug));
        }
    }
}
