<?php
/**
 * CDA Theme functions and definitions
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Theme setup
function cdatheme_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    add_theme_support('customize-selective-refresh-widgets');
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'cdatheme'),
    ));
}
add_action('after_setup_theme', 'cdatheme_setup');

// Enqueue scripts and styles
function cdatheme_scripts() {
    // Remove any frontend styles since we're using headless
    // We only need this for admin functionality
}
add_action('wp_enqueue_scripts', 'cdatheme_scripts');

// Register widget areas
function cdatheme_widgets_init() {
    register_sidebar(array(
        'name' => __('Sidebar', 'cdatheme'),
        'id' => 'sidebar-1',
        'description' => __('Add widgets here.', 'cdatheme'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
}
add_action('widgets_init', 'cdatheme_widgets_init');
?>