<?php
/**
 * Plugin Name: CDA Headless CMS helpers
 * Description: Custom functionality for CDA headless WordPress CMS
 * Version: 1.0.0
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

// ============================================================================
// WORDPRESS CLEANUP - Remove Gutenberg and other page builders
// ============================================================================

add_action('init', 'cda_remove_gutenberg');
function cda_remove_gutenberg() {
    add_filter('use_block_editor_for_post_type', '__return_false', 100);
    remove_post_type_support('page', 'editor');
    remove_post_type_support('post', 'editor');
}

// ============================================================================
// CUSTOM POST TYPES - Add your custom post types here
// ============================================================================

add_action('init', 'cda_create_custom_post_types');
function cda_create_custom_post_types() {
    
    // ============================================================================
    // SERVICES POST TYPE
    // ============================================================================
    register_post_type('services', array(
        'labels' => array(
            'name' => 'Services',
            'singular_name' => 'Service',
            'menu_name' => 'Services',
            'add_new' => 'Add Service',
            'add_new_item' => 'Add New Service',
            'edit_item' => 'Edit Service',
            'new_item' => 'New Service',
            'view_item' => 'View Service',
            'view_items' => 'View Services',
            'search_items' => 'Search Services',
            'not_found' => 'No services found',
            'not_found_in_trash' => 'No services found in Trash',
            'all_items' => 'All Services',
            'archives' => 'Service Archives',
            'attributes' => 'Service Attributes',
            'insert_into_item' => 'Insert into service',
            'uploaded_to_this_item' => 'Uploaded to this service',
            'featured_image' => 'Service Image',
            'set_featured_image' => 'Set service image',
            'remove_featured_image' => 'Remove service image',
            'use_featured_image' => 'Use as service image',
        ),
        'description' => 'CDA Services - Different services offered by CDA',
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array(
            'slug' => 'services',
            'with_front' => false,
        ),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 20,
        'menu_icon' => 'dashicons-portfolio',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes'),
        'show_in_rest' => true,
        'rest_base' => 'services',
        'show_in_graphql' => true,
        'graphql_single_name' => 'service',
        'graphql_plural_name' => 'services',
    ));
    
    // ============================================================================
    // CASE STUDIES POST TYPE
    // ============================================================================
    register_post_type('case_studies', array(
        'labels' => array(
            'name' => 'Case Studies',
            'singular_name' => 'Case Study',
            'menu_name' => 'Case Studies',
            'add_new' => 'Add Case Study',
            'add_new_item' => 'Add New Case Study',
            'edit_item' => 'Edit Case Study',
            'new_item' => 'New Case Study',
            'view_item' => 'View Case Study',
            'view_items' => 'View Case Studies',
            'search_items' => 'Search Case Studies',
            'not_found' => 'No case studies found',
            'not_found_in_trash' => 'No case studies found in Trash',
            'all_items' => 'All Case Studies',
            'archives' => 'Case Study Archives',
            'attributes' => 'Case Study Attributes',
            'insert_into_item' => 'Insert into case study',
            'uploaded_to_this_item' => 'Uploaded to this case study',
            'featured_image' => 'Case Study Image',
            'set_featured_image' => 'Set case study image',
            'remove_featured_image' => 'Remove case study image',
            'use_featured_image' => 'Use as case study image',
        ),
        'description' => 'CDA Case Studies - Client project showcases and success stories',
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array(
            'slug' => 'case-studies',
            'with_front' => false,
        ),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 21,
        'menu_icon' => 'dashicons-chart-line',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes'),
        'show_in_rest' => true,
        'rest_base' => 'case-studies',
        'show_in_graphql' => true,
        'graphql_single_name' => 'caseStudy',
        'graphql_plural_name' => 'caseStudies',
    ));
    
    // ============================================================================
    // TEAM MEMBERS POST TYPE
    // ============================================================================
    register_post_type('team_members', array(
        'labels' => array(
            'name' => 'Team Members',
            'singular_name' => 'Team Member',
            'menu_name' => 'Team Members',
            'add_new' => 'Add Team Member',
            'add_new_item' => 'Add New Team Member',
            'edit_item' => 'Edit Team Member',
            'new_item' => 'New Team Member',
            'view_item' => 'View Team Member',
            'view_items' => 'View Team Members',
            'search_items' => 'Search Team Members',
            'not_found' => 'No team members found',
            'not_found_in_trash' => 'No team members found in Trash',
            'all_items' => 'All Team Members',
            'archives' => 'Team Member Archives',
            'attributes' => 'Team Member Attributes',
            'insert_into_item' => 'Insert into team member',
            'uploaded_to_this_item' => 'Uploaded to this team member',
            'featured_image' => 'Profile Photo',
            'set_featured_image' => 'Set profile photo',
            'remove_featured_image' => 'Remove profile photo',
            'use_featured_image' => 'Use as profile photo',
        ),
        'description' => 'CDA Team Members - Staff profiles and information',
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array(
            'slug' => 'team',
            'with_front' => false,
        ),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 22,
        'menu_icon' => 'dashicons-groups',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes'),
        'show_in_rest' => true,
        'rest_base' => 'team-members',
        'show_in_graphql' => true,
        'graphql_single_name' => 'teamMember',
        'graphql_plural_name' => 'teamMembers',
    ));
}

// ============================================================================
// TAXONOMIES - Add your custom taxonomies here
// ============================================================================

add_action('init', 'cda_create_taxonomies');
function cda_create_taxonomies() {
    
    // ============================================================================
    // SERVICE TYPES TAXONOMY
    // ============================================================================
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
    
    // ============================================================================
    // PROJECT TYPES TAXONOMY (for Case Studies)
    // ============================================================================
    register_taxonomy('project_types', array('case_studies'), array(
        'labels' => array(
            'name' => 'Project Types',
            'singular_name' => 'Project Type',
            'menu_name' => 'Project Types',
            'all_items' => 'All Project Types',
            'edit_item' => 'Edit Project Type',
            'view_item' => 'View Project Type',
            'update_item' => 'Update Project Type',
            'add_new_item' => 'Add New Project Type',
            'new_item_name' => 'New Project Type Name',
            'search_items' => 'Search Project Types',
            'not_found' => 'No project types found',
        ),
        'description' => 'Categories for different types of projects and case studies',
        'public' => true,
        'publicly_queryable' => true,
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'rest_base' => 'project-types',
        'show_admin_column' => true,
        'show_in_graphql' => true,
        'graphql_single_name' => 'projectType',
        'graphql_plural_name' => 'projectTypes',
        'rewrite' => array(
            'slug' => 'project-type',
            'with_front' => false,
            'hierarchical' => true,
        ),
    ));
    
    // ============================================================================
    // DEPARTMENTS TAXONOMY (for Team Members)
    // ============================================================================
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

// ============================================================================
// GLOBAL OPTIONS PAGE - Create options page for global content
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

// ============================================================================
// GLOBAL BLOCKS - Why CDA and Approach blocks for reuse across pages
// ============================================================================

add_action('acf/init', 'cda_add_global_blocks');
function cda_add_global_blocks() {
    // Global Shared Content Field Group
    acf_add_local_field_group(array(
        'key' => 'group_global_shared_content',
        'title' => 'Global Shared Content',
        'fields' => array(
            
            // ============================================================================
            // WHY CDA BLOCK - Global "Why choose us" content
            // ============================================================================
            array(
                'key' => 'field_global_why_cda_block',
                'label' => 'Why CDA Block',
                'name' => 'why_cda_block',
                'type' => 'group',
                'instructions' => 'Global Why CDA content that can be used across all pages',
                'required' => false,
                'conditional_logic' => false,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => ''
                ),
                'layout' => 'block',
                'show_in_graphql' => 1,
                'sub_fields' => array(
                    array(
                        'key' => 'field_global_why_cda_title',
                        'label' => 'Title',
                        'name' => 'title',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => ''
                        ),
                        'default_value' => 'Why CDA',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                        'show_in_graphql' => 1
                    ),
                    array(
                        'key' => 'field_global_why_cda_subtitle',
                        'label' => 'Subtitle',
                        'name' => 'subtitle',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => ''
                        ),
                        'default_value' => 'What Makes Us The Right Choice?',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                        'show_in_graphql' => 1
                    ),
                    array(
                        'key' => 'field_global_why_cda_cards',
                        'label' => 'Cards',
                        'name' => 'cards',
                        'type' => 'repeater',
                        'instructions' => 'Add cards explaining why customers should choose CDA',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => ''
                        ),
                        'collapsed' => '',
                        'min' => 0,
                        'max' => 0,
                        'layout' => 'table',
                        'button_label' => 'Add Card',
                        'show_in_graphql' => 1,
                        'rows_per_page' => 20,
                        'sub_fields' => array(
                            array(
                                'key' => 'field_global_why_card_title',
                                'label' => 'Title',
                                'name' => 'title',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '30',
                                    'class' => '',
                                    'id' => ''
                                ),
                                'default_value' => '',
                                'placeholder' => 'e.g., Expert Team',
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                                'show_in_graphql' => 1,
                                'parent_repeater' => 'field_global_why_cda_cards'
                            ),
                            array(
                                'key' => 'field_global_why_card_description',
                                'label' => 'Description',
                                'name' => 'description',
                                'type' => 'textarea',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '45',
                                    'class' => '',
                                    'id' => ''
                                ),
                                'default_value' => '',
                                'placeholder' => 'Explain this benefit...',
                                'maxlength' => '',
                                'rows' => 4,
                                'new_lines' => '',
                                'show_in_graphql' => 1,
                                'parent_repeater' => 'field_global_why_cda_cards'
                            ),
                            array(
                                'key' => 'field_global_why_card_image',
                                'label' => 'Image',
                                'name' => 'image',
                                'type' => 'image',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '25',
                                    'class' => '',
                                    'id' => ''
                                ),
                                'return_format' => 'array',
                                'preview_size' => 'medium',
                                'library' => 'all',
                                'min_width' => '',
                                'min_height' => '',
                                'min_size' => '',
                                'max_width' => '',
                                'max_height' => '',
                                'max_size' => '',
                                'mime_types' => '',
                                'show_in_graphql' => 1,
                                'parent_repeater' => 'field_global_why_cda_cards'
                            )
                        )
                    )
                )
            ),
            
            // ============================================================================
            // APPROACH BLOCK - Global "How we work" process steps
            // ============================================================================
            array(
                'key' => 'field_global_approach_block',
                'label' => 'Approach Block',
                'name' => 'approach_block',
                'type' => 'group',
                'instructions' => 'Global Approach content - Our step-by-step process that can be used across all pages',
                'required' => false,
                'conditional_logic' => false,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => ''
                ),
                'layout' => 'block',
                'show_in_graphql' => 1,
                'sub_fields' => array(
                    array(
                        'key' => 'field_global_approach_title',
                        'label' => 'Title',
                        'name' => 'title',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => ''
                        ),
                        'default_value' => 'Our Approach',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_global_approach_subtitle',
                        'label' => 'Subtitle',
                        'name' => 'subtitle',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => ''
                        ),
                        'default_value' => 'How We Work',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_global_approach_steps',
                        'label' => 'Approach Steps',
                        'name' => 'steps',
                        'type' => 'repeater',
                        'instructions' => 'Add up to 5 steps for your approach process. Steps will be connected with arrows on frontend.',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => ''
                        ),
                        'collapsed' => '',
                        'min' => 1,
                        'max' => 5,
                        'layout' => 'table',
                        'button_label' => 'Add Step',
                        'show_in_graphql' => 1,
                        'sub_fields' => array(
                            array(
                                'key' => 'field_approach_step_number',
                                'label' => 'Step Number',
                                'name' => 'step_number',
                                'type' => 'number',
                                'instructions' => 'Step order (1-5) for sorting',
                                'required' => 1,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '15',
                                    'class' => '',
                                    'id' => ''
                                ),
                                'default_value' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                                'min' => 1,
                                'max' => 5,
                                'step' => '',
                                'show_in_graphql' => 1,
                            ),
                            array(
                                'key' => 'field_approach_step_title',
                                'label' => 'Step Title',
                                'name' => 'title',
                                'type' => 'text',
                                'instructions' => 'Name of this step',
                                'required' => 1,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '25',
                                    'class' => '',
                                    'id' => ''
                                ),
                                'default_value' => '',
                                'placeholder' => 'e.g., Discovery & Planning',
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                                'show_in_graphql' => 1,
                            ),
                            array(
                                'key' => 'field_approach_step_description',
                                'label' => 'Description',
                                'name' => 'description',
                                'type' => 'textarea',
                                'instructions' => 'Brief description of what happens in this step',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '35',
                                    'class' => '',
                                    'id' => ''
                                ),
                                'default_value' => '',
                                'placeholder' => 'Describe what happens in this step...',
                                'maxlength' => '',
                                'rows' => 3,
                                'new_lines' => '',
                                'show_in_graphql' => 1,
                            ),
                            array(
                                'key' => 'field_approach_step_image',
                                'label' => 'Image/Icon',
                                'name' => 'image',
                                'type' => 'image',
                                'instructions' => 'Upload an image or icon for this step',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '25',
                                    'class' => '',
                                    'id' => ''
                                ),
                                'return_format' => 'array',
                                'preview_size' => 'thumbnail',
                                'library' => 'all',
                                'min_width' => '',
                                'min_height' => '',
                                'min_size' => '',
                                'max_width' => '',
                                'max_height' => '',
                                'max_size' => '',
                                'mime_types' => '',
                                'show_in_graphql' => 1,
                            ),
                        ),
                    ),
                ),
            ),
            
            // ============================================================================
            // TECHNOLOGIES SHOWCASE BLOCK - Global technologies showcase
            // ============================================================================
            array(
                'key' => 'field_global_technologies_block',
                'label' => 'Technologies Showcase Block',
                'name' => 'technologies_block',
                'type' => 'group',
                'instructions' => 'Global Technologies showcase that can be used across all pages',
                'required' => false,
                'conditional_logic' => false,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => ''
                ),
                'layout' => 'block',
                'show_in_graphql' => 1,
                'sub_fields' => array(
                    array(
                        'key' => 'field_global_technologies_title',
                        'label' => 'Title',
                        'name' => 'title',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => ''
                        ),
                        'default_value' => 'Technologies We Use',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                        'show_in_graphql' => 1
                    ),
                    array(
                        'key' => 'field_global_technologies_subtitle',
                        'label' => 'Subtitle',
                        'name' => 'subtitle',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => ''
                        ),
                        'default_value' => 'Cutting-Edge Tools & Platforms',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                        'show_in_graphql' => 1
                    ),
                    array(
                        'key' => 'field_global_technologies_categories',
                        'label' => 'Technology Categories',
                        'name' => 'categories',
                        'type' => 'repeater',
                        'instructions' => 'Add technology categories with icons and descriptions',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => ''
                        ),
                        'collapsed' => '',
                        'min' => 0,
                        'max' => 0,
                        'layout' => 'table',
                        'button_label' => 'Add Technology Category',
                        'show_in_graphql' => 1,
                        'rows_per_page' => 20,
                        'sub_fields' => array(
                            array(
                                'key' => 'field_global_tech_cat_icon',
                                'label' => 'Icon',
                                'name' => 'icon',
                                'type' => 'image',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '20',
                                    'class' => '',
                                    'id' => ''
                                ),
                                'return_format' => 'array',
                                'preview_size' => 'thumbnail',
                                'library' => 'all',
                                'min_width' => '',
                                'min_height' => '',
                                'min_size' => '',
                                'max_width' => '',
                                'max_height' => '',
                                'max_size' => '',
                                'mime_types' => '',
                                'show_in_graphql' => 1,
                                'parent_repeater' => 'field_global_technologies_categories'
                            ),
                            array(
                                'key' => 'field_global_tech_cat_name',
                                'label' => 'Name',
                                'name' => 'name',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '25',
                                    'class' => '',
                                    'id' => ''
                                ),
                                'default_value' => '',
                                'placeholder' => 'e.g., Frontend, Backend',
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                                'show_in_graphql' => 1,
                                'parent_repeater' => 'field_global_technologies_categories'
                            ),
                            array(
                                'key' => 'field_global_tech_cat_desc',
                                'label' => 'Description',
                                'name' => 'description',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '35',
                                    'class' => '',
                                    'id' => ''
                                ),
                                'default_value' => '',
                                'placeholder' => 'Brief description of this technology category',
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                                'show_in_graphql' => 1,
                                'parent_repeater' => 'field_global_technologies_categories'
                            ),
                            array(
                                'key' => 'field_global_tech_cat_url',
                                'label' => 'Link URL',
                                'name' => 'url',
                                'type' => 'url',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '20',
                                    'class' => '',
                                    'id' => ''
                                ),
                                'default_value' => '',
                                'placeholder' => '',
                                'show_in_graphql' => 1,
                                'parent_repeater' => 'field_global_technologies_categories'
                            )
                        )
                    )
                )
            ),
            
            // ============================================================================
            // SHOWREEL BLOCK - Global video showreel with client logos
            // ============================================================================
            array(
                'key' => 'field_global_showreel_block',
                'label' => 'Showreel Block',
                'name' => 'showreel_block',
                'type' => 'group',
                'instructions' => 'Global Showreel video showcase with client logos that can be used across all pages',
                'required' => false,
                'conditional_logic' => false,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => ''
                ),
                'layout' => 'block',
                'show_in_graphql' => 1,
                'sub_fields' => array(
                    array(
                        'key' => 'field_global_showreel_title',
                        'label' => 'Title',
                        'name' => 'title',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => ''
                        ),
                        'default_value' => 'Our Work',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                        'show_in_graphql' => 1
                    ),
                    array(
                        'key' => 'field_global_showreel_subtitle',
                        'label' => 'Subtitle',
                        'name' => 'subtitle',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => ''
                        ),
                        'default_value' => 'Brands We\'ve Helped Grow',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                        'show_in_graphql' => 1
                    ),
                    array(
                        'key' => 'field_global_showreel_video_thumbnail',
                        'label' => 'Video Thumbnail',
                        'name' => 'video_thumbnail',
                        'type' => 'image',
                        'instructions' => 'Upload a thumbnail image for the video player',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => ''
                        ),
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'library' => 'all',
                        'min_width' => '',
                        'min_height' => '',
                        'min_size' => '',
                        'max_width' => '',
                        'max_height' => '',
                        'max_size' => '',
                        'mime_types' => '',
                        'show_in_graphql' => 1
                    ),
                    array(
                        'key' => 'field_global_showreel_video_url',
                        'label' => 'Video URL',
                        'name' => 'video_url',
                        'type' => 'url',
                        'instructions' => 'URL to the showreel video (YouTube, Vimeo, etc.)',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => ''
                        ),
                        'default_value' => '',
                        'placeholder' => 'https://www.youtube.com/watch?v=...',
                        'show_in_graphql' => 1
                    ),
                    array(
                        'key' => 'field_global_showreel_client_logos',
                        'label' => 'Client Logos',
                        'name' => 'client_logos',
                        'type' => 'repeater',
                        'instructions' => 'Add client logos that appear in the showreel section',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => ''
                        ),
                        'collapsed' => '',
                        'min' => 0,
                        'max' => 0,
                        'layout' => 'table',
                        'button_label' => 'Add Client Logo',
                        'show_in_graphql' => 1,
                        'rows_per_page' => 20,
                        'sub_fields' => array(
                            array(
                                'key' => 'field_global_showreel_logo',
                                'label' => 'Logo',
                                'name' => 'logo',
                                'type' => 'image',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '30',
                                    'class' => '',
                                    'id' => ''
                                ),
                                'return_format' => 'array',
                                'preview_size' => 'thumbnail',
                                'library' => 'all',
                                'min_width' => '',
                                'min_height' => '',
                                'min_size' => '',
                                'max_width' => '',
                                'max_height' => '',
                                'max_size' => '',
                                'mime_types' => '',
                                'show_in_graphql' => 1,
                                'parent_repeater' => 'field_global_showreel_client_logos'
                            ),
                            array(
                                'key' => 'field_global_showreel_client_name',
                                'label' => 'Client Name',
                                'name' => 'name',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '35',
                                    'class' => '',
                                    'id' => ''
                                ),
                                'default_value' => '',
                                'placeholder' => 'Client company name',
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                                'show_in_graphql' => 1,
                                'parent_repeater' => 'field_global_showreel_client_logos'
                            ),
                            array(
                                'key' => 'field_global_showreel_client_url',
                                'label' => 'Client URL',
                                'name' => 'url',
                                'type' => 'url',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '35',
                                    'class' => '',
                                    'id' => ''
                                ),
                                'default_value' => '',
                                'placeholder' => '',
                                'show_in_graphql' => 1,
                                'parent_repeater' => 'field_global_showreel_client_logos'
                            )
                        )
                    )
                )
            ),
            
            // ============================================================================
            // NEWSLETTER SIGNUP BLOCK - Global newsletter subscription form
            // ============================================================================
            array(
                'key' => 'field_global_newsletter_block',
                'label' => 'Newsletter Signup Block',
                'name' => 'newsletter_block',
                'type' => 'group',
                'instructions' => 'Global Newsletter signup content that can be used across all pages',
                'required' => false,
                'conditional_logic' => false,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => ''
                ),
                'layout' => 'block',
                'show_in_graphql' => 1,
                'sub_fields' => array(
                    array(
                        'key' => 'field_global_newsletter_title',
                        'label' => 'Title',
                        'name' => 'title',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => ''
                        ),
                        'default_value' => 'Want These Insights?',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                        'show_in_graphql' => 1
                    ),
                    array(
                        'key' => 'field_global_newsletter_subtitle',
                        'label' => 'Subtitle',
                        'name' => 'subtitle',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => ''
                        ),
                        'default_value' => 'Sign Up To Our Newsletter',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                        'show_in_graphql' => 1
                    ),
                    array(
                        'key' => 'field_global_newsletter_description',
                        'label' => 'Description',
                        'name' => 'description',
                        'type' => 'textarea',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => ''
                        ),
                        'default_value' => '',
                        'placeholder' => 'Brief description about newsletter benefits',
                        'maxlength' => '',
                        'rows' => 3,
                        'new_lines' => '',
                        'show_in_graphql' => 1
                    ),
                    array(
                        'key' => 'field_global_newsletter_submit_text',
                        'label' => 'Submit Button Text',
                        'name' => 'submit_text',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => ''
                        ),
                        'default_value' => 'Sign Up',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                        'show_in_graphql' => 1
                    ),
                    array(
                        'key' => 'field_global_newsletter_privacy_text',
                        'label' => 'Privacy Notice',
                        'name' => 'privacy_text',
                        'type' => 'textarea',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => ''
                        ),
                        'default_value' => 'I agree to the Terms and Conditions and consent to receive email updates and newsletters',
                        'placeholder' => '',
                        'maxlength' => '',
                        'rows' => 2,
                        'new_lines' => '',
                        'show_in_graphql' => 1
                    )
                )
            ),
            
            // ============================================================================
            // CTA READY TO START BLOCK - Global call-to-action section
            // ============================================================================
            array(
                'key' => 'field_global_cta_block',
                'label' => 'Ready To Start CTA Block',
                'name' => 'cta_block',
                'type' => 'group',
                'instructions' => 'Global "Ready To Start Your Project" call-to-action that can be used across all pages',
                'required' => false,
                'conditional_logic' => false,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => ''
                ),
                'layout' => 'block',
                'show_in_graphql' => 1,
                'sub_fields' => array(
                    array(
                        'key' => 'field_global_cta_pretitle',
                        'label' => 'Pre-title',
                        'name' => 'pretitle',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => ''
                        ),
                        'default_value' => 'Take The First Step Toward Something Great',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                        'show_in_graphql' => 1
                    ),
                    array(
                        'key' => 'field_global_cta_title',
                        'label' => 'Main Title',
                        'name' => 'title',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => ''
                        ),
                        'default_value' => 'Ready To Start Your Project?',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                        'show_in_graphql' => 1
                    ),
                    array(
                        'key' => 'field_global_cta_button_text',
                        'label' => 'Button Text',
                        'name' => 'button_text',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => ''
                        ),
                        'default_value' => 'Let\'s Talk',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                        'show_in_graphql' => 1
                    ),
                    array(
                        'key' => 'field_global_cta_button_url',
                        'label' => 'Button URL',
                        'name' => 'button_url',
                        'type' => 'url',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '50',
                            'class' => '',
                            'id' => ''
                        ),
                        'default_value' => '',
                        'placeholder' => '/contact',
                        'show_in_graphql' => 1
                    ),
                    array(
                        'key' => 'field_global_cta_background_image',
                        'label' => 'Background Image',
                        'name' => 'background_image',
                        'type' => 'image',
                        'instructions' => 'Optional background image or decorative elements',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => ''
                        ),
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'library' => 'all',
                        'min_width' => '',
                        'min_height' => '',
                        'min_size' => '',
                        'max_width' => '',
                        'max_height' => '',
                        'max_size' => '',
                        'mime_types' => '',
                        'show_in_graphql' => 1
                    )
                )
            ),
            
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'global-content'
                )
            )
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
        'show_in_rest' => 0,
        'show_in_graphql' => 1,
        'graphql_field_name' => 'globalSharedContent',
        'map_graphql_types_from_location_rules' => 1,
        'graphql_types' => array('Page', 'GlobalOptions')
    ));
}

// ============================================================================
// SEO SETTINGS - Add SEO fields to all pages
// ============================================================================

add_action('acf/init', 'cda_add_seo_field_groups');
function cda_add_seo_field_groups() {
    // SEO Settings for all pages
    acf_add_local_field_group(array(
        'key' => 'group_seo_settings',
        'title' => 'SEO Settings',
        'fields' => array(
            array(
                'key' => 'field_seo_title',
                'label' => 'SEO Title',
                'name' => 'seo_title',
                'type' => 'text',
                'instructions' => 'Custom title for SEO (max 60 characters)',
                'show_in_graphql' => 1,
            ),
            array(
                'key' => 'field_seo_description',
                'label' => 'Meta Description',
                'name' => 'seo_description',
                'type' => 'textarea',
                'instructions' => 'Custom meta description for SEO (max 160 characters)',
                'rows' => 3,
                'show_in_graphql' => 1,
            ),
            array(
                'key' => 'field_seo_keywords',
                'label' => 'Meta Keywords',
                'name' => 'seo_keywords',
                'type' => 'text',
                'instructions' => 'Comma-separated keywords for SEO',
                'show_in_graphql' => 1,
            ),
            array(
                'key' => 'field_noindex',
                'label' => 'No Index',
                'name' => 'noindex',
                'type' => 'true_false',
                'instructions' => 'Check to prevent search engines from indexing this page',
                'show_in_graphql' => 1,
            ),
            array(
                'key' => 'field_nofollow',
                'label' => 'No Follow',
                'name' => 'nofollow',
                'type' => 'true_false',
                'instructions' => 'Check to prevent search engines from following links on this page',
                'show_in_graphql' => 1,
            ),
            array(
                'key' => 'field_canonical_url',
                'label' => 'Canonical URL',
                'name' => 'canonical_url',
                'type' => 'url',
                'instructions' => 'Specify a canonical URL for this page',
                'show_in_graphql' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'page'
                )
            )
        ),
        'show_in_graphql' => 1,
        'graphql_field_name' => 'seoSettings',
    ));
}

// ============================================================================
// HOMEPAGE CONTENT - Page-specific fields for homepage
// ============================================================================

add_action('acf/init', 'cda_add_homepage_fields');
function cda_add_homepage_fields() {
    
    // Homepage Content
    acf_add_local_field_group(array(
        'key' => 'group_homepage',
        'title' => 'Homepage Content',
        'fields' => array(
            
            // Header Section
            array(
                'key' => 'field_header_section',
                'label' => 'Header Section',
                'name' => 'header_section',
                'type' => 'group',
                'show_in_graphql' => 1,
                'sub_fields' => array(
                    array(
                        'key' => 'field_header_title',
                        'label' => 'Title',
                        'name' => 'title',
                        'type' => 'wysiwyg',
                        'required' => 1,
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_header_subtitle',
                        'label' => 'Subtitle',
                        'name' => 'subtitle',
                        'type' => 'text',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_primary_cta',
                        'label' => 'Primary CTA',
                        'name' => 'primary_cta',
                        'type' => 'link',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_secondary_cta',
                        'label' => 'Secondary CTA',
                        'name' => 'secondary_cta',
                        'type' => 'link',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_header_image',
                        'label' => 'Desktop Image',
                        'name' => 'desktopImage',
                        'type' => 'image',
                        'return_format' => 'object',
                        'show_in_graphql' => 1,
                        'preview_size' => 'large',
                    )
                )
            ),
            
            // Who We Are Section
            array(
                'key' => 'field_who_we_are_section',
                'label' => 'Who We Are Section',
                'name' => 'whoWeAreSection',
                'type' => 'group',
                'show_in_graphql' => 1,
                'sub_fields' => array(
                    array(
                        'key' => 'field_who_we_are_title',
                        'label' => 'Title',
                        'name' => 'title',
                        'type' => 'text',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_who_we_are_subtitle',
                        'label' => 'Subtitle',
                        'name' => 'subtitle',
                        'type' => 'text',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_who_we_are_image',
                        'label' => 'Image',
                        'name' => 'image',
                        'type' => 'image',
                        'return_format' => 'object',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_who_we_are_button',
                        'label' => 'Button',
                        'name' => 'button',
                        'type' => 'link',
                        'show_in_graphql' => 1,
                    )
                )
            ),
            
            // Services Accordion
            array(
                'key' => 'field_services_accordion',
                'label' => 'Services Accordion',
                'name' => 'services_accordion',
                'type' => 'repeater',
                'show_in_graphql' => 1,
                'sub_fields' => array(
                    array(
                        'key' => 'field_accordion_title',
                        'label' => 'Title',
                        'name' => 'title',
                        'type' => 'text',
                        'required' => 1,
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_accordion_description',
                        'label' => 'Description',
                        'name' => 'description',
                        'type' => 'wysiwyg',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_accordion_link',
                        'label' => 'Link',
                        'name' => 'link',
                        'type' => 'link',
                        'show_in_graphql' => 1,
                    )
                )
            ),
            
            // Other homepage sections...
            array(
                'key' => 'field_platforms_section',
                'label' => 'Platforms Section',
                'name' => 'platforms_section',
                'type' => 'group',
                'show_in_graphql' => 1,
                'sub_fields' => array(
                    array(
                        'key' => 'field_platforms_title',
                        'label' => 'Title',
                        'name' => 'title',
                        'type' => 'text',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_platforms_subtitle',
                        'label' => 'Subtitle',
                        'name' => 'subtitle',
                        'type' => 'text',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_platforms_logos',
                        'label' => 'Logos',
                        'name' => 'logos',
                        'type' => 'repeater',
                        'show_in_graphql' => 1,
                        'sub_fields' => array(
                            array(
                                'key' => 'field_logo_image',
                                'label' => 'Logo Image',
                                'name' => 'logo',
                                'type' => 'image',
                                'return_format' => 'object',
                                'show_in_graphql' => 1,
                            )
                        )
                    )
                )
            ),
            
            // Values Section
            array(
                'key' => 'field_values_section',
                'label' => 'Values Section',
                'name' => 'values_section',
                'type' => 'group',
                'show_in_graphql' => 1,
                'sub_fields' => array(
                    array(
                        'key' => 'field_values_title',
                        'label' => 'Title',
                        'name' => 'title',
                        'type' => 'text',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_values_items',
                        'label' => 'Value Items',
                        'name' => 'value_items',
                        'type' => 'repeater',
                        'show_in_graphql' => 1,
                        'sub_fields' => array(
                            array(
                                'key' => 'field_value_title',
                                'label' => 'Title',
                                'name' => 'title',
                                'type' => 'text',
                                'show_in_graphql' => 1,
                            ),
                            array(
                                'key' => 'field_value_description',
                                'label' => 'Description',
                                'name' => 'description',
                                'type' => 'text',
                                'show_in_graphql' => 1,
                            )
                        )
                    )
                )
            ),
            
            // Case Studies Section
            array(
                'key' => 'field_case_studies_section',
                'label' => 'Case Studies Section',
                'name' => 'case_studies_section',
                'type' => 'group',
                'show_in_graphql' => 1,
                'sub_fields' => array(
                    array(
                        'key' => 'field_case_studies_title',
                        'label' => 'Title',
                        'name' => 'title',
                        'type' => 'text',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_case_studies',
                        'label' => 'Case Studies',
                        'name' => 'case_studies',
                        'type' => 'relationship',
                        'post_type' => array('case_study'),
                        'show_in_graphql' => 1,
                    )
                )
            ),
            
            // Newsletter Section
            array(
                'key' => 'field_newsletter_section',
                'label' => 'Newsletter Section',
                'name' => 'newsletter_section',
                'type' => 'group',
                'show_in_graphql' => 1,
                'sub_fields' => array(
                    array(
                        'key' => 'field_newsletter_title',
                        'label' => 'Title',
                        'name' => 'title',
                        'type' => 'text',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_newsletter_subtitle',
                        'label' => 'Subtitle',
                        'name' => 'subtitle',
                        'type' => 'text',
                        'show_in_graphql' => 1,
                    )
                )
            )
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'template-homepage.php'
                )
            )
        ),
        'show_in_graphql' => 1,
        'graphql_field_name' => 'homepageContent',
    ));
}

// ============================================================================
// ABOUT US PAGE - Page-specific fields for about us page
// ============================================================================

add_action('acf/init', 'cda_add_about_us_fields');
function cda_add_about_us_fields() {
    // About Us Page Content
    acf_add_local_field_group(array(
        'key' => 'group_about_us_page',
        'title' => 'About Us Page Content',
        'fields' => array(
            
            // Content Page Header
            array(
                'key' => 'field_content_page_header',
                'label' => 'Content Page Header',
                'name' => 'content_page_header',
                'type' => 'group',
                'show_in_graphql' => 1,
                'sub_fields' => array(
                    array(
                        'key' => 'field_header_title',
                        'label' => 'Title',
                        'name' => 'title',
                        'type' => 'wysiwyg',
                        'required' => 1,
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_header_text',
                        'label' => 'Text',
                        'name' => 'text',
                        'type' => 'wysiwyg',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_header_cta',
                        'label' => 'CTA',
                        'name' => 'cta',
                        'type' => 'link',
                        'show_in_graphql' => 1,
                    )
                )
            ),
            
            // Who We Are Section
            array(
                'key' => 'field_who_we_are_section_about',
                'label' => 'Who We Are - Your Digital Partner',
                'name' => 'who_we_are_section',
                'type' => 'group',
                'show_in_graphql' => 1,
                'sub_fields' => array(
                    array(
                        'key' => 'field_image_with_frame',
                        'label' => 'Image with Frame',
                        'name' => 'image_with_frame',
                        'type' => 'image',
                        'return_format' => 'object',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_section_title',
                        'label' => 'Section Title',
                        'name' => 'section_title',
                        'type' => 'wysiwyg',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_section_text',
                        'label' => 'Section Text',
                        'name' => 'section_text',
                        'type' => 'wysiwyg',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_section_cta',
                        'label' => 'CTA',
                        'name' => 'cta',
                        'type' => 'link',
                        'show_in_graphql' => 1,
                    )
                )
            ),
            
            // Why CDA Section (page-specific, different from global)
            array(
                'key' => 'field_why_cda_section_about',
                'label' => 'Why CDA (About Page)',
                'name' => 'why_cda_section',
                'type' => 'repeater',
                'show_in_graphql' => 1,
                'sub_fields' => array(
                    array(
                        'key' => 'field_usp_title',
                        'label' => 'USP Title',
                        'name' => 'title',
                        'type' => 'wysiwyg',
                        'required' => 1,
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_usp_description',
                        'label' => 'Description',
                        'name' => 'description',
                        'type' => 'wysiwyg',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_usp_icon',
                        'label' => 'Icon',
                        'name' => 'icon',
                        'type' => 'image',
                        'return_format' => 'object',
                        'show_in_graphql' => 1,
                    )
                )
            ),
            
            // Rest of about us fields...
            // (Keeping your existing about us fields here)
            
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'template-about-us.php'
                )
            )
        ),
        'show_in_graphql' => 1,
        'graphql_field_name' => 'aboutUsContent',
        'map_graphql_types_from_location_rules' => false,
        'graphql_types' => array('Page')
    ));
}

// ============================================================================
// PAGE OVERRIDES - Allow pages to override global blocks (optional)
// ============================================================================

add_action('acf/init', 'cda_add_page_overrides');
function cda_add_page_overrides() {
    // Page-level overrides for global blocks
    acf_add_local_field_group(array(
        'key' => 'group_page_global_overrides',
        'title' => 'Global Block Overrides',
        'fields' => array(
            
            // Why CDA Override
            array(
                'key' => 'field_page_why_cda_override',
                'label' => 'Why CDA Override',
                'name' => 'why_cda_override',
                'type' => 'group',
                'instructions' => 'Override the global Why CDA block for this page',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => ''
                ),
                'layout' => 'block',
                'show_in_graphql' => 1,
                'sub_fields' => array(
                    array(
                        'key' => 'field_override_why_cda',
                        'label' => 'Override Global Why CDA',
                        'name' => 'override_why_cda',
                        'type' => 'true_false',
                        'instructions' => 'Check to use custom Why CDA content for this page',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => ''
                        ),
                        'message' => '',
                        'default_value' => 0,
                        'ui' => 1,
                        'ui_on_text' => '',
                        'ui_off_text' => '',
                        'show_in_graphql' => 1,
                    ),
                    // Custom Why CDA fields would go here when override is enabled
                )
            ),
            
            // Approach Override
            array(
                'key' => 'field_page_approach_override',
                'label' => 'Approach Override',
                'name' => 'approach_override',
                'type' => 'group',
                'instructions' => 'Override the global approach block for this page',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => ''
                ),
                'layout' => 'block',
                'show_in_graphql' => 1,
                'sub_fields' => array(
                    array(
                        'key' => 'field_override_approach',
                        'label' => 'Override Global Approach',
                        'name' => 'override_approach',
                        'type' => 'true_false',
                        'instructions' => 'Check to use custom approach content for this page',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => ''
                        ),
                        'message' => '',
                        'default_value' => 0,
                        'ui' => 1,
                        'ui_on_text' => '',
                        'ui_off_text' => '',
                        'show_in_graphql' => 1,
                    ),
                    // Custom approach fields would go here when override is enabled
                )
            ),
            
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'page'
                )
            )
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
        'show_in_graphql' => 1,
        'graphql_field_name' => 'globalBlockOverrides',
    ));
}

// ============================================================================
// SERVICES ACF FIELD GROUPS
// ============================================================================

add_action('acf/init', 'cda_add_services_fields');
function cda_add_services_fields() {
    
    // Services Content Field Group
    acf_add_local_field_group(array(
        'key' => 'group_services_content',
        'title' => 'Service Content',
        'fields' => array(
            
            // HERO SECTION
            array(
                'key' => 'field_service_hero_section',
                'label' => 'Hero Section',
                'name' => 'hero_section',
                'type' => 'group',
                'instructions' => 'Main hero section for this service page',
                'layout' => 'block',
                'show_in_graphql' => 1,
                'graphql_field_name' => 'heroSection',
                'sub_fields' => array(
                    array(
                        'key' => 'field_service_hero_subtitle',
                        'label' => 'Subtitle',
                        'name' => 'subtitle',
                        'type' => 'text',
                        'instructions' => 'Subtitle that appears above the main title',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_service_hero_description',
                        'label' => 'Description',
                        'name' => 'description',
                        'type' => 'textarea',
                        'instructions' => 'Brief description of the service',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_service_hero_image',
                        'label' => 'Hero Image',
                        'name' => 'hero_image',
                        'type' => 'image',
                        'return_format' => 'array',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_service_hero_cta',
                        'label' => 'Call to Action',
                        'name' => 'cta',
                        'type' => 'link',
                        'show_in_graphql' => 1,
                    ),
                ),
            ),
            
            // KEY STATISTICS
            array(
                'key' => 'field_service_statistics',
                'label' => 'Key Statistics',
                'name' => 'key_statistics',
                'type' => 'repeater',
                'instructions' => 'Add key metrics and statistics for this service',
                'min' => 0,
                'max' => 6,
                'layout' => 'table',
                'button_label' => 'Add Statistic',
                'show_in_graphql' => 1,
                'sub_fields' => array(
                    array(
                        'key' => 'field_service_stat_number',
                        'label' => 'Number',
                        'name' => 'number',
                        'type' => 'text',
                        'instructions' => 'e.g., "250%", "£5M", "50+"',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_service_stat_label',
                        'label' => 'Label',
                        'name' => 'label',
                        'type' => 'text',
                        'instructions' => 'e.g., "Increase in ROI", "Revenue Generated"',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_service_stat_description',
                        'label' => 'Description',
                        'name' => 'description',
                        'type' => 'text',
                        'instructions' => 'Brief description of this metric',
                        'show_in_graphql' => 1,
                    ),
                ),
            ),
            
            // SERVICE FEATURES/BENEFITS
            array(
                'key' => 'field_service_features',
                'label' => 'Service Features',
                'name' => 'service_features',
                'type' => 'repeater',
                'instructions' => 'Key features and benefits of this service',
                'min' => 0,
                'max' => 8,
                'layout' => 'block',
                'button_label' => 'Add Feature',
                'show_in_graphql' => 1,
                'sub_fields' => array(
                    array(
                        'key' => 'field_service_feature_icon',
                        'label' => 'Icon',
                        'name' => 'icon',
                        'type' => 'image',
                        'return_format' => 'array',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_service_feature_title',
                        'label' => 'Title',
                        'name' => 'title',
                        'type' => 'text',
                        'required' => 1,
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_service_feature_description',
                        'label' => 'Description',
                        'name' => 'description',
                        'type' => 'textarea',
                        'show_in_graphql' => 1,
                    ),
                ),
            ),
            
            // RELATED CASE STUDIES
            array(
                'key' => 'field_service_case_studies',
                'label' => 'Featured Case Studies',
                'name' => 'featured_case_studies',
                'type' => 'post_object',
                'instructions' => 'Select case studies to feature for this service',
                'post_type' => array('case_studies'),
                'multiple' => 1,
                'max' => 4,
                'return_format' => 'object',
                'show_in_graphql' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'services',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => 'Content fields for services',
        'show_in_graphql' => 1,
        'graphql_field_name' => 'serviceFields',
    ));
}

// ============================================================================
// CASE STUDIES ACF FIELD GROUPS  
// ============================================================================

add_action('acf/init', 'cda_add_case_studies_fields');
function cda_add_case_studies_fields() {
    
    // Case Studies Content Field Group
    acf_add_local_field_group(array(
        'key' => 'group_case_studies_content',
        'title' => 'Case Study Content',
        'fields' => array(
            
            // PROJECT OVERVIEW
            array(
                'key' => 'field_case_study_overview',
                'label' => 'Project Overview',
                'name' => 'project_overview',
                'type' => 'group',
                'instructions' => 'Basic information about this project',
                'layout' => 'block',
                'show_in_graphql' => 1,
                'graphql_field_name' => 'projectOverview',
                'sub_fields' => array(
                    array(
                        'key' => 'field_case_study_client',
                        'label' => 'Client Name',
                        'name' => 'client_name',
                        'type' => 'text',
                        'required' => 1,
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_case_study_client_logo',
                        'label' => 'Client Logo',
                        'name' => 'client_logo',
                        'type' => 'image',
                        'return_format' => 'array',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_case_study_project_url',
                        'label' => 'Project URL',
                        'name' => 'project_url',
                        'type' => 'url',
                        'instructions' => 'Link to the live project (if applicable)',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_case_study_completion_date',
                        'label' => 'Completion Date',
                        'name' => 'completion_date',
                        'type' => 'date_picker',
                        'return_format' => 'Y-m-d',
                        'show_in_graphql' => 1,
                    ),
                ),
            ),
            
            // CHALLENGE, SOLUTION, RESULTS
            array(
                'key' => 'field_case_study_challenge',
                'label' => 'The Challenge',
                'name' => 'challenge',
                'type' => 'wysiwyg',
                'instructions' => 'What problem did we solve?',
                'toolbar' => 'basic',
                'media_upload' => 1,
                'show_in_graphql' => 1,
            ),
            
            array(
                'key' => 'field_case_study_solution',
                'label' => 'Our Solution',
                'name' => 'solution',
                'type' => 'wysiwyg',
                'instructions' => 'How did we solve the problem?',
                'toolbar' => 'basic',
                'media_upload' => 1,
                'show_in_graphql' => 1,
            ),
            
            array(
                'key' => 'field_case_study_results',
                'label' => 'The Results',
                'name' => 'results',
                'type' => 'wysiwyg',
                'instructions' => 'What were the outcomes?',
                'toolbar' => 'basic',
                'media_upload' => 1,
                'show_in_graphql' => 1,
            ),
            
            // KEY METRICS
            array(
                'key' => 'field_case_study_metrics',
                'label' => 'Key Metrics & Results',
                'name' => 'key_metrics',
                'type' => 'repeater',
                'instructions' => 'Quantifiable results and metrics',
                'min' => 0,
                'max' => 6,
                'layout' => 'table',
                'button_label' => 'Add Metric',
                'show_in_graphql' => 1,
                'sub_fields' => array(
                    array(
                        'key' => 'field_case_study_metric_number',
                        'label' => 'Number/Percentage',
                        'name' => 'number',
                        'type' => 'text',
                        'instructions' => 'e.g., "150%", "£2.5M", "45%"',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_case_study_metric_label',
                        'label' => 'Metric Label',
                        'name' => 'label',
                        'type' => 'text',
                        'instructions' => 'e.g., "Increase in Sales", "Revenue Generated"',
                        'show_in_graphql' => 1,
                    ),
                ),
            ),
            
            // PROJECT GALLERY
            array(
                'key' => 'field_case_study_gallery',
                'label' => 'Project Gallery',
                'name' => 'project_gallery',
                'type' => 'gallery',
                'instructions' => 'Screenshots, photos, and visuals from the project',
                'return_format' => 'array',
                'show_in_graphql' => 1,
            ),
            
            // FEATURED FLAG
            array(
                'key' => 'field_case_study_featured',
                'label' => 'Featured Case Study',
                'name' => 'featured',
                'type' => 'true_false',
                'instructions' => 'Mark as featured to show on homepage and other prominent locations',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => 'Yes',
                'ui_off_text' => 'No',
                'show_in_graphql' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'case_studies',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => 'Content fields for case studies',
        'show_in_graphql' => 1,
        'graphql_field_name' => 'caseStudyFields',
    ));
}

// ============================================================================
// TEAM MEMBERS ACF FIELD GROUPS
// ============================================================================

add_action('acf/init', 'cda_add_team_members_fields');
function cda_add_team_members_fields() {
    
    // Team Members Content Field Group
    acf_add_local_field_group(array(
        'key' => 'group_team_members_content',
        'title' => 'Team Member Information',
        'fields' => array(
            
            // BASIC INFO
            array(
                'key' => 'field_team_member_job_title',
                'label' => 'Job Title',
                'name' => 'job_title',
                'type' => 'text',
                'required' => 1,
                'instructions' => 'e.g., "Senior Developer", "Creative Director"',
                'show_in_graphql' => 1,
            ),
            
            array(
                'key' => 'field_team_member_short_bio',
                'label' => 'Short Bio',
                'name' => 'short_bio',
                'type' => 'textarea',
                'instructions' => 'Brief description for team listings (1-2 sentences)',
                'maxlength' => 200,
                'show_in_graphql' => 1,
            ),
            
            array(
                'key' => 'field_team_member_full_bio',
                'label' => 'Full Biography',
                'name' => 'full_bio',
                'type' => 'wysiwyg',
                'instructions' => 'Detailed biography for individual profile pages',
                'toolbar' => 'basic',
                'media_upload' => 0,
                'show_in_graphql' => 1,
            ),
            
            // CONTACT INFORMATION
            array(
                'key' => 'field_team_member_email',
                'label' => 'Email Address',
                'name' => 'email',
                'type' => 'email',
                'show_in_graphql' => 1,
            ),
            
            array(
                'key' => 'field_team_member_linkedin',
                'label' => 'LinkedIn URL',
                'name' => 'linkedin_url',
                'type' => 'url',
                'show_in_graphql' => 1,
            ),
            
            // SKILLS & EXPERTISE
            array(
                'key' => 'field_team_member_skills',
                'label' => 'Skills & Expertise',
                'name' => 'skills',
                'type' => 'repeater',
                'instructions' => 'Key skills and areas of expertise',
                'min' => 0,
                'max' => 10,
                'layout' => 'table',
                'button_label' => 'Add Skill',
                'show_in_graphql' => 1,
                'sub_fields' => array(
                    array(
                        'key' => 'field_team_member_skill_name',
                        'label' => 'Skill Name',
                        'name' => 'name',
                        'type' => 'text',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_team_member_skill_level',
                        'label' => 'Proficiency Level',
                        'name' => 'level',
                        'type' => 'select',
                        'choices' => array(
                            'beginner' => 'Beginner',
                            'intermediate' => 'Intermediate',
                            'advanced' => 'Advanced',
                            'expert' => 'Expert',
                        ),
                        'show_in_graphql' => 1,
                    ),
                ),
            ),
            
            // VISIBILITY SETTINGS
            array(
                'key' => 'field_team_member_featured',
                'label' => 'Featured Team Member',
                'name' => 'featured',
                'type' => 'true_false',
                'instructions' => 'Show on homepage and other prominent locations',
                'default_value' => 0,
                'ui' => 1,
                'show_in_graphql' => 1,
            ),
            
            array(
                'key' => 'field_team_member_leadership',
                'label' => 'Leadership Team',
                'name' => 'leadership',
                'type' => 'true_false',
                'instructions' => 'Member of the leadership team',
                'default_value' => 0,
                'ui' => 1,
                'show_in_graphql' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'team_members',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => 'Information fields for team members',
        'show_in_graphql' => 1,
        'graphql_field_name' => 'teamMemberFields',
    ));
}

?>