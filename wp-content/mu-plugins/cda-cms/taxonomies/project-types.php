<?php
/**
 * Project Types Taxonomy (for Case Studies)
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// ============================================================================
// PROJECT TYPES TAXONOMY (for Case Studies)
// ============================================================================

add_action('init', 'cda_register_project_types_taxonomy');
function cda_register_project_types_taxonomy() {
    register_taxonomy('project_types', array('case_studies'), array(
        'labels' => array(
            'name' => 'Project Types',
            'singular_name' => 'Project Type',
            'menu_name' => 'Project Types',
            'all_items' => 'All Project Types',
            'edit_item' => 'Edit Project Type',
            'view_item' => 'View Project Type',
            'update_item' => 'Update Project Type',
            'add_new_item' => 'Add New Project Type',
            'new_item_name' => 'New Project Type Name',
            'search_items' => 'Search Project Types',
            'not_found' => 'No project types found',
        ),
        'description' => 'Categories for different types of projects and case studies',
        'public' => true,
        'publicly_queryable' => true,
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'rest_base' => 'project-types',
        'show_admin_column' => true,
        'show_in_graphql' => true,
        'graphql_single_name' => 'projectType',
        'graphql_plural_name' => 'projectTypes',
        'rewrite' => array(
            'slug' => 'project-type',
            'with_front' => false,
            'hierarchical' => true,
        ),
    ));
}
