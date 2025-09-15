<?php
/**
 * Blog Categories Taxonomy
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// ============================================================================
// BLOG CATEGORIES TAXONOMY (for Blog Posts)
// ============================================================================

add_action('init', 'cda_register_blog_categories_taxonomy');
function cda_register_blog_categories_taxonomy() {
    register_taxonomy('blog_categories', array('blog_posts'), array(
        'labels' => array(
            'name' => 'Blog Categories',
            'singular_name' => 'Blog Category',
            'menu_name' => 'Categories',
            'all_items' => 'All Categories',
            'edit_item' => 'Edit Category',
            'view_item' => 'View Category',
            'update_item' => 'Update Category',
            'add_new_item' => 'Add New Category',
            'new_item_name' => 'New Category Name',
            'search_items' => 'Search Categories',
            'not_found' => 'No categories found',
        ),
        'description' => 'Categories for blog posts and articles',
        'public' => true,
        'publicly_queryable' => true,
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'rest_base' => 'blog-categories',
        'show_admin_column' => true,
        'show_in_graphql' => true,
        'graphql_single_name' => 'blogCategory',
        'graphql_plural_name' => 'blogCategories',
        'rewrite' => array(
            'slug' => 'blog-category',
            'with_front' => false,
            'hierarchical' => true,
        ),
    ));
    
    // Add default blog categories
    $default_blog_categories = array(
        'insights' => 'Industry Insights',
        'tutorials' => 'Tutorials & Guides',
        'case-studies' => 'Case Studies',
        'news' => 'Company News',
        'technology' => 'Technology',
        'marketing' => 'Digital Marketing',
        'development' => 'Web Development',
    );
    
    foreach ($default_blog_categories as $slug => $name) {
        if (!term_exists($name, 'blog_categories')) {
            wp_insert_term($name, 'blog_categories', array('slug' => $slug));
        }
    }
}
