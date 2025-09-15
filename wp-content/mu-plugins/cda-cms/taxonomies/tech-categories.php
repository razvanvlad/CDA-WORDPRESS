<?php
/**
 * Tech Categories Taxonomy
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// ============================================================================
// TECH CATEGORIES TAXONOMY (for Technologies)
// ============================================================================

add_action('init', 'cda_register_tech_categories_taxonomy');
function cda_register_tech_categories_taxonomy() {
    register_taxonomy('tech_categories', array('technologies'), array(
        'labels' => array(
            'name' => 'Tech Categories',
            'singular_name' => 'Tech Category',
            'menu_name' => 'Tech Categories',
            'all_items' => 'All Tech Categories',
            'edit_item' => 'Edit Tech Category',
            'view_item' => 'View Tech Category',
            'update_item' => 'Update Tech Category',
            'add_new_item' => 'Add New Tech Category',
            'new_item_name' => 'New Tech Category Name',
            'search_items' => 'Search Tech Categories',
            'not_found' => 'No tech categories found',
        ),
        'description' => 'Categories for different technology types',
        'public' => true,
        'publicly_queryable' => true,
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'rest_base' => 'tech-categories',
        'show_admin_column' => true,
        'show_in_graphql' => true,
        'graphql_single_name' => 'techCategory',
        'graphql_plural_name' => 'techCategories',
        'rewrite' => array(
            'slug' => 'tech-category',
            'with_front' => false,
            'hierarchical' => true,
        ),
    ));
    
    // Add default tech categories
    $default_tech_categories = array(
        'frontend' => 'Frontend',
        'backend' => 'Backend',
        'database' => 'Database',
        'cms' => 'CMS',
        'framework' => 'Framework',
        'tool' => 'Development Tool',
        'design' => 'Design Tool',
        'analytics' => 'Analytics',
        'hosting' => 'Hosting & Infrastructure',
    );
    
    foreach ($default_tech_categories as $slug => $name) {
        if (!term_exists($name, 'tech_categories')) {
            wp_insert_term($name, 'tech_categories', array('slug' => $slug));
        }
    }
}
