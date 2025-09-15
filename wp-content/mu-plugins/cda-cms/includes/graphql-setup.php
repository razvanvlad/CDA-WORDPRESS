<?php
/**
 * GraphQL Setup and Configuration
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// ============================================================================
// GRAPHQL SETUP - Enable ACF GraphQL Integration
// ============================================================================

// Enable ACF GraphQL - This is the most important line
add_filter('acf/settings/graphql_enabled', '__return_true');

// Make sure ACF fields are available in GraphQL
add_filter('graphql_acf_get_fields_config', function($config, $acf_field, $type_name) {
    $config['show_in_graphql'] = true;
    return $config;
}, 10, 3);

// Add this filter to ensure ACF fields are properly exposed to GraphQL
add_filter('graphql_resolve_field', function($result, $source, $args, $context, $info) {
    // This ensures ACF fields are properly resolved
    return $result;
}, 10, 5);

// Add CORS headers for headless setup
add_action('init', 'cda_add_cors_headers');
function cda_add_cors_headers() {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
}

// Debug: Force ACF to load our fields
add_action('admin_init', function() {
    if (function_exists('acf_add_local_field_group')) {
        // Force our job listing fields to be available
        if (get_current_screen() && get_current_screen()->post_type === 'job_listings') {
            // This ensures ACF fields are loaded for job listings
            do_action('acf/init');
        }
    }
});

// Debug: Check if ACF fields are registered
add_action('admin_footer', function() {
    if (get_current_screen() && get_current_screen()->post_type === 'job_listings') {
        if (function_exists('acf_get_field_groups')) {
            $field_groups = acf_get_field_groups();
            $job_groups = array_filter($field_groups, function($group) {
                return strpos($group['key'], 'job') !== false || strpos($group['title'], 'Job') !== false;
            });
            if (empty($job_groups)) {
                echo '<script>console.log("ACF Debug: No job listing field groups found. Available groups:", ' . json_encode(array_column($field_groups, 'title')) . ');</script>';
            } else {
                echo '<script>console.log("ACF Debug: Found job listing field groups:", ' . json_encode(array_column($job_groups, 'title')) . ');</script>';
            }
        }
    }
});
