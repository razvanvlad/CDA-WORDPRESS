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
?>