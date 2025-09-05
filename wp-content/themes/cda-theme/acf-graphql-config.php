<?php
/**
 * ACF GraphQL Configuration
 *
 * This file ensures all ACF field groups are properly exposed to GraphQL
 * It automatically configures show_in_graphql and graphql_field_name for all field groups
 */

// Hook into ACF initialization
add_action('acf/init', 'cda_configure_acf_for_graphql');

/**
 * Configure all ACF field groups to be exposed to GraphQL
 */
function cda_configure_acf_for_graphql() {
    // Only proceed if ACF and WPGraphQL are active
    if (!function_exists('acf_get_field_groups') || !function_exists('register_graphql_field')) {
        return;
    }
    
    // Get all ACF field groups
    $field_groups = acf_get_field_groups();
    
    if (empty($field_groups)) {
        return;
    }
    
    // Loop through each field group
    foreach ($field_groups as $field_group) {
        // Skip if already configured for GraphQL
        if (isset($field_group['show_in_graphql']) && $field_group['show_in_graphql']) {
            continue;
        }
        
        // Get the field group key
        $key = $field_group['key'];
        
        // Generate a GraphQL field name from the title
        $graphql_field_name = generate_graphql_field_name($field_group['title']);
        
        // Update the field group to show in GraphQL
        $field_group['show_in_graphql'] = 1;
        $field_group['graphql_field_name'] = $graphql_field_name;
        
        // Update the field group
        acf_update_field_group($field_group);
        
        // Also update all fields in this group to show in GraphQL
        $fields = acf_get_fields($key);
        if (!empty($fields)) {
            update_fields_for_graphql($fields);
        }
    }
}

/**
 * Recursively update fields to show in GraphQL
 */
function update_fields_for_graphql($fields) {
    foreach ($fields as $field) {
        // Skip if already configured
        if (isset($field['show_in_graphql']) && $field['show_in_graphql']) {
            continue;
        }
        
        // Update field to show in GraphQL
        $field['show_in_graphql'] = 1;
        acf_update_field($field);
        
        // If this is a repeater or group field, update sub-fields
        if (in_array($field['type'], ['repeater', 'group', 'flexible_content']) && isset($field['sub_fields'])) {
            update_fields_for_graphql($field['sub_fields']);
        }
    }
}

/**
 * Generate a valid GraphQL field name from a title
 */
function generate_graphql_field_name($title) {
    // Convert to lowercase
    $name = strtolower($title);
    
    // Replace spaces and special characters with underscores
    $name = preg_replace('/[^a-z0-9]+/', '_', $name);
    
    // Remove leading/trailing underscores
    $name = trim($name, '_');
    
    // Add 'Content' suffix for page field groups
    if (strpos($name, 'page') !== false || strpos($title, 'Page') !== false) {
        $name .= '_content';
    }
    
    // Ensure it's camelCase for GraphQL
    $name = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $name))));
    
    return $name;
}

/**
 * Register hook to ensure new field groups are also configured
 */
add_action('acf/update_field_group', 'cda_ensure_field_group_graphql_config');

function cda_ensure_field_group_graphql_config($field_group) {
    // Skip if already configured
    if (isset($field_group['show_in_graphql']) && $field_group['show_in_graphql']) {
        return;
    }
    
    // Configure for GraphQL
    $field_group['show_in_graphql'] = 1;
    $field_group['graphql_field_name'] = generate_graphql_field_name($field_group['title']);
    
    // Update the field group
    acf_update_field_group($field_group);
}

/**
 * Specifically fix the Software Development page field group
 */
add_action('acf/init', 'cda_fix_software_development_fields', 20);

function cda_fix_software_development_fields() {
    // Get the Software Development field group
    $field_group = acf_get_field_group('group_software_development');
    
    if (!$field_group) {
        // Try to find it by title
        $field_groups = acf_get_field_groups();
        foreach ($field_groups as $group) {
            if (strpos(strtolower($group['title']), 'software development') !== false) {
                $field_group = $group;
                break;
            }
        }
    }
    
    if ($field_group) {
        // Ensure it's configured for GraphQL
        $field_group['show_in_graphql'] = 1;
        $field_group['graphql_field_name'] = 'softwareDevelopmentContent';
        
        // Update the field group
        acf_update_field_group($field_group);
        
        // Update all fields in this group
        $fields = acf_get_fields($field_group['key']);
        if (!empty($fields)) {
            update_fields_for_graphql($fields);
        }
    }
}