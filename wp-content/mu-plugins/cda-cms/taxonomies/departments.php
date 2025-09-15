<?php
/**
 * Departments Taxonomy
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// ============================================================================
// DEPARTMENTS TAXONOMY (for Team Members)
// ============================================================================

add_action('init', 'cda_register_departments_taxonomy');
function cda_register_departments_taxonomy() {
    register_taxonomy('departments', array('team_members'), array(
        'labels' => array(
            'name' => 'Departments',
            'singular_name' => 'Department',
            'menu_name' => 'Departments',
            'all_items' => 'All Departments',
            'edit_item' => 'Edit Department',
            'view_item' => 'View Department',
            'update_item' => 'Update Department',
            'add_new_item' => 'Add New Department',
            'new_item_name' => 'New Department Name',
            'search_items' => 'Search Departments',
            'not_found' => 'No departments found',
        ),
        'description' => 'Organizational departments for team members',
        'public' => true,
        'publicly_queryable' => true,
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'rest_base' => 'departments',
        'show_admin_column' => true,
        'show_in_graphql' => true,
        'graphql_single_name' => 'department',
        'graphql_plural_name' => 'departments',
        'rewrite' => array(
            'slug' => 'department',
            'with_front' => false,
            'hierarchical' => true,
        ),
    ));
    
    // Add default departments
    $default_departments = array(
        'leadership' => 'Leadership',
        'development' => 'Development',
        'design' => 'Design',
        'marketing' => 'Marketing',
        'operations' => 'Operations',
        'consultancy' => 'Consultancy',
    );
    
    foreach ($default_departments as $slug => $name) {
        if (!term_exists($name, 'departments')) {
            wp_insert_term($name, 'departments', array('slug' => $slug));
        }
    }
}
