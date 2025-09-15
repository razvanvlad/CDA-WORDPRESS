<?php
/**
 * Blog Posts Post Type
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// ============================================================================
// BLOG POSTS POST TYPE
// ============================================================================

add_action('init', 'cda_register_blog_posts_post_type');
function cda_register_blog_posts_post_type() {
    register_post_type('blog_posts', array(
        'labels' => array(
            'name' => 'Blog Posts',
            'singular_name' => 'Blog Post',
            'menu_name' => 'Blog Posts',
            'add_new' => 'Add Blog Post',
            'add_new_item' => 'Add New Blog Post',
            'edit_item' => 'Edit Blog Post',
            'new_item' => 'New Blog Post',
            'view_item' => 'View Blog Post',
            'view_items' => 'View Blog Posts',
            'search_items' => 'Search Blog Posts',
            'not_found' => 'No blog posts found',
            'not_found_in_trash' => 'No blog posts found in Trash',
            'all_items' => 'All Blog Posts',
            'archives' => 'Blog Archives',
            'attributes' => 'Blog Post Attributes',
            'insert_into_item' => 'Insert into blog post',
            'uploaded_to_this_item' => 'Uploaded to this blog post',
            'featured_image' => 'Featured Image',
            'set_featured_image' => 'Set featured image',
            'remove_featured_image' => 'Remove featured image',
            'use_featured_image' => 'Use as featured image',
        ),
        'description' => 'CDA Blog Posts - Articles, news, and resources for the knowledge hub',
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array(
            'slug' => 'blog',
            'with_front' => false,
        ),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 19,
        'menu_icon' => 'dashicons-welcome-write-blog',
        'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custom-fields', 'page-attributes'),
        'show_in_rest' => true,
        'rest_base' => 'blog-posts',
        'show_in_graphql' => true,
        'graphql_single_name' => 'blogPost',
        'graphql_plural_name' => 'blogPosts',
    ));
}
