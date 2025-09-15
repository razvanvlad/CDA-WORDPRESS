<?php
/**
 * Service Types Taxonomy
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// ============================================================================
// SERVICE TYPES TAXONOMY
// ============================================================================

add_action('init', 'cda_register_service_types_taxonomy');
function cda_register_service_types_taxonomy() {
    register_taxonomy('service_types', array('services'), array(
        'labels' => array(
            'name' => 'Service Types',
            'singular_name' => 'Service Type',
            'menu_name' => 'Service Types',
            'all_items' => 'All Service Types',
            'edit_item' => 'Edit Service Type',
            'view_item' => 'View Service Type',
            'update_item' => 'Update Service Type',
            'add_new_item' => 'Add New Service Type',
            'new_item_name' => 'New Service Type Name',
            'search_items' => 'Search Service Types',
            'not_found' => 'No service types found',
        ),
        'description' => 'Categories for different types of services',
        'public' => true,
        'publicly_queryable' => true,
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'rest_base' => 'service-types',
        'show_admin_column' => true,
        'show_in_graphql' => true,
        'graphql_single_name' => 'serviceType',
        'graphql_plural_name' => 'serviceTypes',
        'rewrite' => array(
            'slug' => 'service-type',
            'with_front' => false,
            'hierarchical' => true,
        ),
    ));
    
    // Add default service types
    $default_service_types = array(
        'ecommerce' => 'eCommerce Development',
        'b2b-lead-generation' => 'B2B Lead Generation',
        'software-development' => 'Software Development',
        'booking-systems' => 'Booking Systems',
        'digital-marketing' => 'Digital Marketing',
        'outsourced-cmo' => 'Outsourced CMO',
        'ai-solutions' => 'AI & Automation Solutions',
    );
    
    foreach ($default_service_types as $slug => $name) {
        if (!term_exists($name, 'service_types')) {
            wp_insert_term($name, 'service_types', array('slug' => $slug));
        }
    }
}
