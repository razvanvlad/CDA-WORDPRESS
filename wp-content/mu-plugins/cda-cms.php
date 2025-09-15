<?php
/**
 * Plugin Name: CDA Headless CMS helpers
 * Description: Custom functionality for CDA headless WordPress CMS - Modular Version
 * Version: 2.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('CDA_CMS_PATH', plugin_dir_path(__FILE__) . 'cda-cms/');
define('CDA_CMS_URL', plugin_dir_url(__FILE__) . 'cda-cms/');

// ============================================================================
// LOAD CORE INCLUDES
// ============================================================================

// Load GraphQL setup
require_once CDA_CMS_PATH . 'includes/graphql-setup.php';

// Load admin cleanup
require_once CDA_CMS_PATH . 'includes/admin-cleanup.php';

// Load utilities
if (file_exists(CDA_CMS_PATH . 'includes/utilities.php')) {
    require_once CDA_CMS_PATH . 'includes/utilities.php';
}

// ============================================================================
// LOAD POST TYPES
// ============================================================================

// Load all post type definitions
$post_types = [
    'blog-posts',
    'job-listings', 
    'services',
    'case-studies',
    'team-members',
    'technologies'
];

foreach ($post_types as $post_type) {
    $file = CDA_CMS_PATH . 'post-types/' . $post_type . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
}

// ============================================================================
// LOAD TAXONOMIES
// ============================================================================

// Load all taxonomy definitions
$taxonomies = [
    'service-types',
    'project-types',
    'blog-categories',
    'job-types',
    'tech-categories',
    'departments'
];

foreach ($taxonomies as $taxonomy) {
    $file = CDA_CMS_PATH . 'taxonomies/' . $taxonomy . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
}

// ============================================================================
// LOAD ACF FIELD GROUPS
// ============================================================================

// Load ACF field group definitions
// The individual files will check if ACF is available before registering
$field_groups = [
    'job-listings-fields',
    'services-fields',
    'case-studies-fields',
    'team-members-fields',
    'blog-posts-fields',
    'technologies-fields',
    'global-options',
    'page-specific-fields'
];

foreach ($field_groups as $field_group) {
    $file = CDA_CMS_PATH . 'acf-fields/' . $field_group . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
}

// ============================================================================
// GLOBAL OPTIONS PAGE
// ============================================================================

if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title' => 'Global Content',
        'menu_title' => 'Global Content',
        'menu_slug' => 'global-content',
        'capability' => 'edit_posts',
        'redirect' => false,
        'show_in_graphql' => true,
        'graphql_field_name' => 'globalOptions'
    ));
}


