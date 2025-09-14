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
    // BLOG POSTS / NEWS ARTICLES (for Resource Center)
    // ============================================================================
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
    
    // ============================================================================
    // JOB LISTINGS (for Career Pages)
    // ============================================================================
    register_post_type('job_listings', array(
        'labels' => array(
            'name' => 'Job Listings',
            'singular_name' => 'Job Listing',
            'menu_name' => 'Jobs',
            'add_new' => 'Add Job',
            'add_new_item' => 'Add New Job Listing',
            'edit_item' => 'Edit Job Listing',
            'new_item' => 'New Job Listing',
            'view_item' => 'View Job Listing',
            'view_items' => 'View Job Listings',
            'search_items' => 'Search Jobs',
            'not_found' => 'No jobs found',
            'not_found_in_trash' => 'No jobs found in Trash',
            'all_items' => 'All Jobs',
            'archives' => 'Job Archives',
            'attributes' => 'Job Attributes',
            'insert_into_item' => 'Insert into job listing',
            'uploaded_to_this_item' => 'Uploaded to this job listing',
            'featured_image' => 'Job Image',
            'set_featured_image' => 'Set job image',
            'remove_featured_image' => 'Remove job image',
            'use_featured_image' => 'Use as job image',
        ),
        'description' => 'CDA Job Listings - Open positions and career opportunities',
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array(
            'slug' => 'careers',
            'with_front' => false,
        ),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 23,
        'menu_icon' => 'dashicons-businessperson',
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields', 'excerpt'),
        'show_in_rest' => true,
        'rest_base' => 'job-listings',
        'show_in_graphql' => true,
        'graphql_single_name' => 'jobListing',
        'graphql_plural_name' => 'jobListings',
    ));

}

// ============================================================================
// JOB LISTINGS ACF FIELD GROUPS
// ============================================================================

// Register ACF fields with higher priority
add_action('acf/init', 'cda_add_job_listings_fields', 5);
add_action('init', 'cda_add_job_listings_fields', 20);

function cda_add_job_listings_fields() {
    
    // Check if ACF is available
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }
    
    // Add a simple test field first to ensure ACF is working
    acf_add_local_field_group(array(
        'key' => 'group_job_test_simple',
        'title' => 'Job Test Fields (Simple)',
        'fields' => array(
            array(
                'key' => 'field_job_test_location',
                'label' => 'Job Location (Test)',
                'name' => 'job_location_test',
                'type' => 'text',
                'instructions' => 'This is a test field to ensure ACF is working',
                'required' => 0,
                'show_in_graphql' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'job_listings',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'active' => true,
        'description' => 'Test field group for job listings',
    ));
    
    // Job Listings Content Field Group
    acf_add_local_field_group(array(
        'key' => 'group_job_listings_content',
        'title' => 'Job Listing Details',
        'fields' => array(
            
            // JOB DETAILS
            array(
                'key' => 'field_job_listing_details',
                'label' => 'Job Details',
                'name' => 'job_details',
                'type' => 'group',
                'instructions' => 'Basic job information',
                'layout' => 'block',
                'show_in_graphql' => 1,
                'graphql_field_name' => 'jobDetails',
                'sub_fields' => array(
                    array(
                        'key' => 'field_job_listing_location',
                        'label' => 'Location',
                        'name' => 'location',
                        'type' => 'text',
                        'instructions' => 'Job location (e.g., "Remote", "London, UK", "Hybrid")',
                        'required' => 1,
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_job_listing_salary',
                        'label' => 'Salary',
                        'name' => 'salary',
                        'type' => 'text',
                        'instructions' => 'e.g., "£30,000 - £45,000", "Competitive", "Based on experience"',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_job_listing_experience_level',
                        'label' => 'Experience Level',
                        'name' => 'experience_level',
                        'type' => 'select',
                        'choices' => array(
                            'entry' => 'Entry Level',
                            'junior' => 'Junior',
                            'mid' => 'Mid Level',
                            'senior' => 'Senior',
                            'lead' => 'Lead/Principal',
                            'director' => 'Director',
                        ),
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_job_listing_publish_date',
                        'label' => 'Publish Date',
                        'name' => 'publish_date',
                        'type' => 'date_picker',
                        'return_format' => 'Y-m-d',
                        'show_in_graphql' => 1,
                    ),
                ),
            ),
            
            // JOB REQUIREMENTS
            array(
                'key' => 'field_job_listing_requirements',
                'label' => 'Requirements & Qualifications',
                'name' => 'requirements',
                'type' => 'group',
                'layout' => 'block',
                'show_in_graphql' => 1,
                'sub_fields' => array(
                    array(
                        'key' => 'field_job_listing_about_the_position',
                        'label' => 'ABOUT THE POSITION',
                        'name' => 'about_the_position',
                        'type' => 'wysiwyg',
                        'instructions' => 'Information about the position',
                        'toolbar' => 'basic',
                        'media_upload' => 0,
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_job_listing_our_dream_candidate',
                        'label' => 'OUR DREAM CANDIDATE',
                        'name' => 'our_dream_candidate',
                        'type' => 'wysiwyg',
                        'instructions' => 'Information about the ideal candidate',
                        'toolbar' => 'basic',
                        'media_upload' => 0,
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_job_listing_required_skills',
                        'label' => 'KEY RESPONSIBILITIES',
                        'name' => 'required_skills',
                        'type' => 'repeater',
                        'instructions' => 'Key responsabilities for this position',
                        'min' => 1,
                        'max' => 10,
                        'layout' => 'table',
                        'button_label' => 'Add responsability',
                        'show_in_graphql' => 1,
                        'sub_fields' => array(
                            array(
                                'key' => 'field_job_responsability_name',
                                'label' => 'Responsability',
                                'name' => 'responsability',
                                'type' => 'text',
                                'show_in_graphql' => 1,
                            ),
                        ),
                    ),
                                        array(
                        'key' => 'field_job_listing_qualifications',
                        'label' => 'QUALIFICATIONS AND EXPERIENCE',
                        'name' => 'required_qualifications',
                        'type' => 'repeater',
                        'instructions' => 'Candidate qualifications and experience for this position',
                        'min' => 1,
                        'max' => 10,
                        'layout' => 'table',
                        'button_label' => 'Add qualification',
                        'show_in_graphql' => 1,
                        'sub_fields' => array(
                            array(
                                'key' => 'field_job_qualification_name',
                                'label' => 'Qualification',
                                'name' => 'qualification',
                                'type' => 'text',
                                'show_in_graphql' => 1,
                            ),
                        ),
                    ),
                ),
            ),
            
            // APPLICATION PROCESS
            
            // JOB STATUS
            array(
                'key' => 'field_job_listing_status',
                'label' => 'Job Status',
                'name' => 'job_status',
                'type' => 'select',
                'instructions' => 'Current status of this job listing',
                'choices' => array(
                    'open' => 'Open - Accepting Applications',
                    'urgent' => 'Urgent - Immediate Start',
                    'closing_soon' => 'Closing Soon',
                    'filled' => 'Position Filled',
                    'on_hold' => 'On Hold',
                ),
                'default_value' => 'open',
                'show_in_graphql' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'job_listings',
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
        'description' => 'Content fields for job listings',
        'show_in_graphql' => 1,
        'graphql_field_name' => 'jobListingFields',
    ));
}

// ============================================================================
// TECHNOLOGIES (Individual Tech Stack Items)
// ============================================================================
function cda_register_technologies_post_type() {
    register_post_type('technologies', array(
        'labels' => array(
            'name' => 'Technologies',
            'singular_name' => 'Technology',
            'menu_name' => 'Technologies',
            'add_new' => 'Add Technology',
            'add_new_item' => 'Add New Technology',
            'edit_item' => 'Edit Technology',
            'new_item' => 'New Technology',
            'view_item' => 'View Technology',
            'view_items' => 'View Technologies',
            'search_items' => 'Search Technologies',
            'not_found' => 'No technologies found',
            'not_found_in_trash' => 'No technologies found in Trash',
            'all_items' => 'All Technologies',
            'archives' => 'Technology Archives',
            'attributes' => 'Technology Attributes',
            'insert_into_item' => 'Insert into technology',
            'uploaded_to_this_item' => 'Uploaded to this technology',
            'featured_image' => 'Technology Logo',
            'set_featured_image' => 'Set technology logo',
            'remove_featured_image' => 'Remove technology logo',
            'use_featured_image' => 'Use as technology logo',
        ),
        'description' => 'CDA Technologies - Tools, frameworks, and platforms we use',
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array(
            'slug' => 'technologies',
            'with_front' => false,
        ),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 24,
        'menu_icon' => 'dashicons-admin-tools',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes'),
        'show_in_rest' => true,
        'rest_base' => 'technologies',
        'show_in_graphql' => true,
        'graphql_single_name' => 'technology',
        'graphql_plural_name' => 'technologies',
    ));
    
    // ============================================================================
    // TECHNOLOGIES (Individual Tech Stack Items)
    // ============================================================================
    register_post_type('technologies', array(
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
// JOB LISTINGS ACF FIELD GROUPS (Outside main function)
// ============================================================================

// Register ACF fields with higher priority
add_action('acf/init', 'cda_add_job_listings_fields', 5);
add_action('init', 'cda_add_job_listings_fields', 20);

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
    // BLOG CATEGORIES TAXONOMY (for Blog Posts)
    // ============================================================================
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
    
    // ============================================================================
    // JOB TYPES TAXONOMY (for Job Listings)
    // ============================================================================
    register_taxonomy('job_types', array('job_listings'), array(
        'labels' => array(
            'name' => 'Job Types',
            'singular_name' => 'Job Type',
            'menu_name' => 'Job Types',
            'all_items' => 'All Job Types',
            'edit_item' => 'Edit Job Type',
            'view_item' => 'View Job Type',
            'update_item' => 'Update Job Type',
            'add_new_item' => 'Add New Job Type',
            'new_item_name' => 'New Job Type Name',
            'search_items' => 'Search Job Types',
            'not_found' => 'No job types found',
        ),
        'description' => 'Categories for different types of job positions',
        'public' => true,
        'publicly_queryable' => true,
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'rest_base' => 'job-types',
        'show_admin_column' => true,
        'show_in_graphql' => true,
        'graphql_single_name' => 'jobType',
        'graphql_plural_name' => 'jobTypes',
        'rewrite' => array(
            'slug' => 'job-type',
            'with_front' => false,
            'hierarchical' => true,
        ),
    ));
    
    // Add default job types
    $default_job_types = array(
        'full-time' => 'Full-Time',
        'part-time' => 'Part-Time',
        'contract' => 'Contract',
        'remote' => 'Remote',
        'hybrid' => 'Hybrid',
        'internship' => 'Internship',
    );
    
    foreach ($default_job_types as $slug => $name) {
        if (!term_exists($name, 'job_types')) {
            wp_insert_term($name, 'job_types', array('slug' => $slug));
        }
    }
    
    // ============================================================================
    // TECH CATEGORIES TAXONOMY (for Technologies)
    // ============================================================================
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

// add_action('acf/init', 'cda_add_global_blocks');
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
            // VALUES BLOCK - Global "Our Values" content
            // ============================================================================
            array(
                'key' => 'field_global_values_block',
                'label' => 'Values Block',
                'name' => 'values_block',
                'type' => 'group',
                'instructions' => 'Global Values content that can be used across all pages',
                'required' => false,
                'conditional_logic' => false,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => ''
                ),
                'layout' => 'block',
                'show_in_graphql' => 1,
                'graphql_field_name' => 'valuesBlock',
                'sub_fields' => array(
                    array(
                        'key' => 'field_global_values_title',
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
                        'default_value' => 'Our Values',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                        'show_in_graphql' => 1
                    ),
                    array(
                        'key' => 'field_global_values_subtitle',
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
                        'default_value' => 'The Foundation Of Our Work',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                        'show_in_graphql' => 1
                    ),
                    array(
                        'key' => 'field_global_values_cards',
                        'label' => 'Value Cards',
                        'name' => 'cards',
                        'type' => 'repeater',
                        'instructions' => 'Add value cards with numbers, titles, descriptions and images',
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
                        'button_label' => 'Add Value Card',
                        'show_in_graphql' => 1,
                        'rows_per_page' => 20,
                        'sub_fields' => array(
                            array(
                                'key' => 'field_global_value_card_number',
                                'label' => 'Card Number',
                                'name' => 'card_number',
                                'type' => 'number',
                                'instructions' => 'Card order number for sorting',
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
                                'max' => 99,
                                'step' => '',
                                'show_in_graphql' => 1,
                                'parent_repeater' => 'field_global_values_cards'
                            ),
                            array(
                                'key' => 'field_global_value_card_title',
                                'label' => 'Title',
                                'name' => 'title',
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
                                'placeholder' => 'e.g., Innovation',
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                                'show_in_graphql' => 1,
                                'parent_repeater' => 'field_global_values_cards'
                            ),
                            array(
                                'key' => 'field_global_value_card_description',
                                'label' => 'Description',
                                'name' => 'description',
                                'type' => 'textarea',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '35',
                                    'class' => '',
                                    'id' => ''
                                ),
                                'default_value' => '',
                                'placeholder' => 'Describe this value...',
                                'maxlength' => '',
                                'rows' => 4,
                                'new_lines' => '',
                                'show_in_graphql' => 1,
                                'parent_repeater' => 'field_global_values_cards'
                            ),
                            array(
                                'key' => 'field_global_value_card_image',
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
                                'parent_repeater' => 'field_global_values_cards'
                            )
                        )
                    ),
                    array(
                        'key' => 'field_global_values_corner_image',
                        'label' => 'Corner Image',
                        'name' => 'corner_image',
                        'type' => 'image',
                        'instructions' => 'Optional decorative image for bottom-right corner',
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
            // TECHNOLOGIES SLIDER BLOCK - Global technologies slider with logos
            // ============================================================================
            array(
                'key' => 'field_global_technologies_slider_block',
                'label' => 'Technologies Slider Block',
                'name' => 'technologies_slider_block',
                'type' => 'group',
                'instructions' => 'Global Technologies slider with logos that can be used across all pages',
                'required' => false,
                'conditional_logic' => false,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => ''
                ),
                'layout' => 'block',
                'show_in_graphql' => 1,
                'graphql_field_name' => 'technologiesSliderBlock',
                'sub_fields' => array(
                    array(
                        'key' => 'field_global_tech_slider_subtitle',
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
                        'default_value' => 'Our Technology Stack',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                        'show_in_graphql' => 1
                    ),
                    array(
                        'key' => 'field_global_tech_slider_title',
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
                        'default_value' => 'Technologies We Master',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                        'show_in_graphql' => 1
                    ),
                    array(
                        'key' => 'field_global_tech_slider_logos',
                        'label' => 'Technology Logos',
                        'name' => 'logos',
                        'type' => 'repeater',
                        'instructions' => 'Add technology logos for the slider',
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
                        'button_label' => 'Add Technology Logo',
                        'show_in_graphql' => 1,
                        'rows_per_page' => 20,
                        'sub_fields' => array(
                            array(
                                'key' => 'field_global_tech_logo_image',
                                'label' => 'Logo Image',
                                'name' => 'image',
                                'type' => 'image',
                                'instructions' => '',
                                'required' => 1,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '40',
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
                                'parent_repeater' => 'field_global_tech_slider_logos'
                            ),
                            array(
                                'key' => 'field_global_tech_logo_name',
                                'label' => 'Technology Name',
                                'name' => 'name',
                                'type' => 'text',
                                'instructions' => 'Name of the technology (for alt text)',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '30',
                                    'class' => '',
                                    'id' => ''
                                ),
                                'default_value' => '',
                                'placeholder' => 'e.g., React, Node.js, WordPress',
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                                'show_in_graphql' => 1,
                                'parent_repeater' => 'field_global_tech_slider_logos'
                            ),
                            array(
                                'key' => 'field_global_tech_logo_url',
                                'label' => 'Link URL',
                                'name' => 'url',
                                'type' => 'url',
                                'instructions' => 'Optional link to technology website',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '30',
                                    'class' => '',
                                    'id' => ''
                                ),
                                'default_value' => '',
                                'placeholder' => 'https://...',
                                'show_in_graphql' => 1,
                                'parent_repeater' => 'field_global_tech_slider_logos'
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
            
            // ============================================================================
            // PHOTO FRAME BLOCK - Global photo frame with layered design
            // ============================================================================
            array(
                'key' => 'field_global_photo_frame_block',
                'label' => 'Photo Frame Block',
                'name' => 'photo_frame_block',
                'type' => 'group',
                'instructions' => 'Global Photo Frame block with layered frame design that can be used across all pages',
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
                        'key' => 'field_global_photo_frame_image',
                        'label' => 'Frame Image',
                        'name' => 'frame_image',
                        'type' => 'image',
                        'instructions' => 'Upload the decorative frame image that will overlay the main image',
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
                        'key' => 'field_global_photo_frame_inner_image',
                        'label' => 'Inner Image/GIF',
                        'name' => 'inner_image',
                        'type' => 'image',
                        'instructions' => 'Upload the main image or GIF that will sit inside the frame',
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
                        'mime_types' => 'jpg,jpeg,png,gif',
                        'show_in_graphql' => 1
                    ),
                    array(
                        'key' => 'field_global_photo_frame_subtitle',
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
                        'default_value' => 'Who we are',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                        'show_in_graphql' => 1
                    ),
                    array(
                        'key' => 'field_global_photo_frame_title',
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
                        'default_value' => 'Your Digital Partner',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                        'show_in_graphql' => 1
                    ),
                    array(
                        'key' => 'field_global_photo_frame_text',
                        'label' => 'Text Content',
                        'name' => 'text',
                        'type' => 'textarea',
                        'instructions' => 'Main text content - will expand to show all text without cutting off',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => ''
                        ),
                        'default_value' => '',
                        'placeholder' => 'Enter your detailed text content here...',
                        'maxlength' => '',
                        'rows' => 5,
                        'new_lines' => 'br',
                        'show_in_graphql' => 1
                    ),
                    array(
                        'key' => 'field_global_photo_frame_button_title',
                        'label' => 'Button Title',
                        'name' => 'button_title',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '33.33',
                            'class' => '',
                            'id' => ''
                        ),
                        'default_value' => 'About Us',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                        'show_in_graphql' => 1
                    ),
                    array(
                        'key' => 'field_global_photo_frame_button_url',
                        'label' => 'Button URL',
                        'name' => 'button_url',
                        'type' => 'url',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '33.33',
                            'class' => '',
                            'id' => ''
                        ),
                        'default_value' => '',
                        'placeholder' => '/about',
                        'show_in_graphql' => 1
                    ),
                    array(
                        'key' => 'field_global_photo_frame_button_target',
                        'label' => 'Button Target',
                        'name' => 'button_target',
                        'type' => 'select',
                        'instructions' => 'Choose whether the link opens in current or new tab',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '33.33',
                            'class' => '',
                            'id' => ''
                        ),
                        'choices' => array(
                            '_self' => 'Current Tab',
                            '_blank' => 'New Tab'
                        ),
                        'default_value' => '_self',
                        'allow_null' => 0,
                        'multiple' => 0,
                        'ui' => 0,
                        'return_format' => 'value',
                        'ajax' => 0,
                        'placeholder' => '',
                        'show_in_graphql' => 1
                    ),
                    array(
                        'key' => 'field_global_photo_frame_arrow_illustration',
                        'label' => 'Arrow Illustration',
                        'name' => 'arrow_illustration',
                        'type' => 'image',
                        'instructions' => 'Upload arrow illustration image, video, or GIF',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
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
                        'mime_types' => 'jpg,jpeg,png,gif,svg,mp4,webm',
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
            ),
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
        'show_in_rest' => 0,
        'show_in_graphql' => 1,
        'graphql_field_name' => 'globalSharedContent',
        'map_graphql_types_from_location_rules' => 1,
        'graphql_types' => array('Page', 'GlobalOptions')
    ));
    
    // Debug: Log PhotoFrame Block registration
    error_log('CDA DEBUG: PhotoFrame Block field group registered successfully');
}

// ============================================================================
// WHY CDA BLOCK - Global content block
// ============================================================================

// add_action('acf/init', 'cda_add_why_cda_global');
function cda_add_why_cda_global() {
    acf_add_local_field_group(array(
        'key' => 'group_global_why_cda',
        'title' => 'Why CDA Block',
        'fields' => array(
            array(
                'key' => 'field_global_why_cda_group',
                'label' => 'Why CDA Block',
                'name' => 'why_cda_block',
                'type' => 'group',
                'instructions' => 'Global Why CDA content that can be used across pages',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => ''
                ),
                'layout' => 'block',
                'show_in_graphql' => 1,
                'graphql_field_name' => 'whyCdaBlock',
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
            )
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'global-content'
                )
            ),
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
        'show_in_rest' => 0,
        'show_in_graphql' => 1,
        'graphql_field_name' => 'whyCdaBlock',
        'map_graphql_types_from_location_rules' => 1,
        'graphql_types' => array('Page', 'GlobalOptions')
    ));
}

// ============================================================================
// SERVICES ACCORDION - Global content block
// ============================================================================

// add_action('acf/init', 'cda_add_services_accordion_global');
function cda_add_services_accordion_global() {
    acf_add_local_field_group(array(
        'key' => 'group_global_services_accordion',
        'title' => 'Services Accordion',
        'fields' => array(
            array(
                'key' => 'field_global_services_accordion_group',
                'label' => 'Services Accordion Block',
                'name' => 'services_accordion_block',
                'type' => 'group',
                'instructions' => 'Global Services Accordion content that can be used across pages',
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
                        'key' => 'field_global_services_subtitle',
                        'label' => 'Subtitle',
                        'name' => 'subtitle',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => ''
                        ),
                        'default_value' => '',
                        'placeholder' => 'What We Do',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                        'show_in_graphql' => 1
                    ),
                    array(
                        'key' => 'field_global_services_title',
                        'label' => 'Title',
                        'name' => 'title',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => ''
                        ),
                        'default_value' => '',
                        'placeholder' => 'Our Services',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                        'show_in_graphql' => 1
                    ),
                    array(
                        'key' => 'field_global_services_illustration',
                        'label' => 'Illustration',
                        'name' => 'illustration',
                        'type' => 'image',
                        'instructions' => 'Upload illustration image, video, or GIF',
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
                        'mime_types' => 'jpg,jpeg,png,gif,svg,mp4,webm',
                        'show_in_graphql' => 1
                    ),
                    array(
                        'key' => 'field_global_services_accordion',
                        'label' => 'Services Accordion',
                        'name' => 'services_accordion',
                        'type' => 'relationship',
                        'instructions' => 'Select services from the Services post type. Title and linked page will be populated automatically. You can add intro text manually.',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => ''
                        ),
                        'post_type' => array('service'),
                        'taxonomy' => '',
                        'filters' => array('search', 'taxonomy'),
                        'elements' => '',
                        'min' => 0,
                        'max' => '',
                        'return_format' => 'object',
                        'show_in_graphql' => 1
                    )
                )
            )
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'global-content'
                )
            ),
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
        'show_in_rest' => 0,
        'show_in_graphql' => 1,
        'graphql_field_name' => 'servicesAccordionBlock',
        'map_graphql_types_from_location_rules' => 1,
        'graphql_types' => array('Page', 'GlobalOptions')
    ));
}

// ============================================================================
// STATS & IMAGE - Global content block
// ============================================================================

// add_action('acf/init', 'cda_add_stats_image_global');
function cda_add_stats_image_global() {
    acf_add_local_field_group(array(
        'key' => 'group_global_stats_image',
        'title' => 'Stats & Image',
        'fields' => array(
            array(
                'key' => 'field_global_stats_image_group',
                'label' => 'Stats & Image Block',
                'name' => 'stats_image_block',
                'type' => 'group',
                'instructions' => 'Global Stats & Image content that can be used across pages',
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
                        'key' => 'field_global_stats_repeater',
                        'label' => 'Stats (4 maximum)',
                        'name' => 'stats',
                        'type' => 'repeater',
                        'instructions' => 'Add up to 4 statistics. Each stat will have a custom CSS class for color underlines.',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => ''
                        ),
                        'collapsed' => '',
                        'min' => 0,
                        'max' => 4,
                        'layout' => 'table',
                        'button_label' => 'Add Stat',
                        'show_in_graphql' => 1,
                        'sub_fields' => array(
                            array(
                                'key' => 'field_global_stat_number',
                                'label' => 'Stat Number',
                                'name' => 'stat_number',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 1,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '30',
                                    'class' => '',
                                    'id' => ''
                                ),
                                'default_value' => '',
                                'placeholder' => '100+',
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                                'show_in_graphql' => 1
                            ),
                            array(
                                'key' => 'field_global_stat_text',
                                'label' => 'Stat Text',
                                'name' => 'stat_text',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 1,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '40',
                                    'class' => '',
                                    'id' => ''
                                ),
                                'default_value' => '',
                                'placeholder' => 'Projects Completed',
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                                'show_in_graphql' => 1
                            ),
                            array(
                                'key' => 'field_global_stat_color_class',
                                'label' => 'Color Class',
                                'name' => 'color_class',
                                'type' => 'select',
                                'instructions' => 'Select color class for underline styling',
                                'required' => 1,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '30',
                                    'class' => '',
                                    'id' => ''
                                ),
                                'choices' => array(
                                    'stat-color-1' => 'Color 1',
                                    'stat-color-2' => 'Color 2',
                                    'stat-color-3' => 'Color 3',
                                    'stat-color-4' => 'Color 4'
                                ),
                                'default_value' => 'stat-color-1',
                                'allow_null' => 0,
                                'multiple' => 0,
                                'ui' => 0,
                                'return_format' => 'value',
                                'ajax' => 0,
                                'placeholder' => '',
                                'show_in_graphql' => 1
                            )
                        )
                    ),
                    array(
                        'key' => 'field_global_stats_text',
                        'label' => 'Text',
                        'name' => 'text',
                        'type' => 'textarea',
                        'instructions' => 'Text block will expand to show all content - it must not be cut off',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => ''
                        ),
                        'default_value' => '',
                        'placeholder' => 'Enter descriptive text about your statistics...',
                        'maxlength' => '',
                        'rows' => 4,
                        'new_lines' => 'wpautop',
                        'show_in_graphql' => 1
                    ),
                    array(
                        'key' => 'field_global_stats_button',
                        'label' => 'Button',
                        'name' => 'button',
                        'type' => 'group',
                        'instructions' => '',
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
                                'key' => 'field_global_stats_button_title',
                                'label' => 'Button Title',
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
                                'default_value' => '',
                                'placeholder' => 'Learn More',
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                                'show_in_graphql' => 1
                            ),
                            array(
                                'key' => 'field_global_stats_button_link',
                                'label' => 'Button Link',
                                'name' => 'link',
                                'type' => 'page_link',
                                'instructions' => 'Select page to link to',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '50',
                                    'class' => '',
                                    'id' => ''
                                ),
                                'post_type' => array('page'),
                                'taxonomy' => '',
                                'allow_null' => 1,
                                'allow_archives' => 1,
                                'multiple' => 0,
                                'show_in_graphql' => 1
                            )
                        )
                    ),
                    array(
                        'key' => 'field_global_stats_illustration',
                        'label' => 'Illustration',
                        'name' => 'illustration',
                        'type' => 'image',
                        'instructions' => 'Upload illustration image, video, or GIF',
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
                        'mime_types' => 'jpg,jpeg,png,gif,svg,mp4,webm',
                        'show_in_graphql' => 1
                    )
                )
            )
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'global-content'
                )
            ),
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
        'show_in_rest' => 0,
        'show_in_graphql' => 1,
        'graphql_field_name' => 'statsImageBlock',
        'map_graphql_types_from_location_rules' => 1,
        'graphql_types' => array('Page', 'GlobalOptions')
    ));
}

// ============================================================================
// SERVICES ACCORDION - Global content block
// ============================================================================



// ============================================================================
// LOCATIONS WITH IMAGE - Global content block
// ============================================================================

// add_action('acf/init', 'cda_add_locations_image_global');
function cda_add_locations_image_global() {
    acf_add_local_field_group(array(
        'key' => 'group_global_locations_image',
        'title' => 'Locations with Image',
        'fields' => array(
            array(
                'key' => 'field_global_locations_image_group',
                'label' => 'Locations with Image Block',
                'name' => 'locations_image_block',
                'type' => 'group',
                'instructions' => 'Global Locations with Image content that can be used across pages',
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
                        'key' => 'field_global_locations_subtitle',
                        'label' => 'Subtitle',
                        'name' => 'subtitle',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => ''
                        ),
                        'default_value' => '',
                        'placeholder' => 'Where We Are',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                        'show_in_graphql' => 1
                    ),
                    array(
                        'key' => 'field_global_locations_title',
                        'label' => 'Title',
                        'name' => 'title',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => ''
                        ),
                        'default_value' => '',
                        'placeholder' => 'Our Global Locations',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                        'show_in_graphql' => 1
                    ),
                    array(
                        'key' => 'field_global_locations_countries',
                        'label' => 'Countries',
                        'name' => 'countries',
                        'type' => 'repeater',
                        'instructions' => 'Add countries with their office locations and contact details',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => ''
                        ),
                        'collapsed' => 'field_global_country_name',
                        'min' => 0,
                        'max' => '',
                        'layout' => 'block',
                        'button_label' => 'Add Country',
                        'show_in_graphql' => 1,
                        'sub_fields' => array(
                            array(
                                'key' => 'field_global_country_name',
                                'label' => 'Country Name',
                                'name' => 'country_name',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 1,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => ''
                                ),
                                'default_value' => '',
                                'placeholder' => 'United Kingdom',
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                                'show_in_graphql' => 1
                            ),
                            array(
                                'key' => 'field_global_country_offices',
                                'label' => 'Offices',
                                'name' => 'offices',
                                'type' => 'repeater',
                                'instructions' => 'Add up to 2 offices per country. Empty fields will be hidden automatically.',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => ''
                                ),
                                'collapsed' => 'field_global_office_name',
                                'min' => 0,
                                'max' => 2,
                                'layout' => 'table',
                                'button_label' => 'Add Office',
                                'show_in_graphql' => 1,
                                'sub_fields' => array(
                                    array(
                                        'key' => 'field_global_office_name',
                                        'label' => 'Office Name',
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
                                        'placeholder' => 'London Office',
                                        'prepend' => '',
                                        'append' => '',
                                        'maxlength' => '',
                                        'show_in_graphql' => 1
                                    ),
                                    array(
                                        'key' => 'field_global_office_address',
                                        'label' => 'Address',
                                        'name' => 'address',
                                        'type' => 'textarea',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '25',
                                            'class' => '',
                                            'id' => ''
                                        ),
                                        'default_value' => '',
                                        'placeholder' => '123 Business St\nLondon, UK',
                                        'maxlength' => '',
                                        'rows' => 3,
                                        'new_lines' => 'br',
                                        'show_in_graphql' => 1
                                    ),
                                    array(
                                        'key' => 'field_global_office_email',
                                        'label' => 'Email',
                                        'name' => 'email',
                                        'type' => 'email',
                                        'instructions' => '',
                                        'required' => 0,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => '25',
                                            'class' => '',
                                            'id' => ''
                                        ),
                                        'default_value' => '',
                                        'placeholder' => 'london@company.com',
                                        'prepend' => '',
                                        'append' => '',
                                        'show_in_graphql' => 1
                                    ),
                                    array(
                                        'key' => 'field_global_office_phone',
                                        'label' => 'Phone',
                                        'name' => 'phone',
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
                                        'placeholder' => '+44 20 1234 5678',
                                        'prepend' => '',
                                        'append' => '',
                                        'maxlength' => '',
                                        'show_in_graphql' => 1
                                    )
                                )
                            )
                        )
                    ),
                    array(
                        'key' => 'field_global_locations_illustration',
                        'label' => 'Illustration',
                        'name' => 'illustration',
                        'type' => 'image',
                        'instructions' => 'Upload illustration image, video, or GIF',
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
                        'mime_types' => 'jpg,jpeg,png,gif,svg,mp4,webm',
                        'show_in_graphql' => 1
                    )
                )
            )
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'global-content'
                )
            ),
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
        'show_in_rest' => 0,
        'show_in_graphql' => 1,
        'graphql_field_name' => 'locationsImageBlock',
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

// add_action('acf/init', 'cda_add_homepage_fields');
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
            

            
            // Flexible Content Repeater
            array(
                'key' => 'field_homepage_content_blocks',
                'label' => 'Content Blocks',
                'name' => 'content_blocks',
                'type' => 'flexible_content',
                'instructions' => 'Add content blocks to build your homepage. You can use global blocks or individual sections.',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => ''
                ),
                'layouts' => array(
                    // Global Block Layout
                    'layout_global_block' => array(
                        'key' => 'layout_global_block',
                        'name' => 'global_block',
                        'label' => 'Global Block',
                        'display' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_global_block_type',
                                'label' => 'Global Block Type',
                                'name' => 'global_block_type',
                                'type' => 'select',
                                'instructions' => 'Select which global block to use',
                                'required' => 1,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '50',
                                    'class' => '',
                                    'id' => ''
                                ),
                                'choices' => array(
                                    'image_frame_text_cta' => 'Image & Frame Left, Text & CTA Right',
                                    'services_accordion' => 'Services Accordion',
                                    'technologies_slider' => 'Technologies Slider',
                                    'values' => 'Values',
                                    'showreel' => 'Showreel',
                                    'stats_image' => 'Stats & Image',
                                    'locations_image' => 'Locations with Image',
                                    'news_carousel' => 'News Carousel'
                                ),
                                'default_value' => '',
                                'allow_null' => 0,
                                'multiple' => 0,
                                'ui' => 1,
                                'ajax' => 0,
                                'return_format' => 'value',
                                'placeholder' => '',
                                'show_in_graphql' => 1
                            ),
                            array(
                                'key' => 'field_use_global_content',
                                'label' => 'Use Global Content',
                                'name' => 'use_global_content',
                                'type' => 'true_false',
                                'instructions' => 'Use content from Global Content settings, or override with custom content for this page',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '50',
                                    'class' => '',
                                    'id' => ''
                                ),
                                'message' => '',
                                'default_value' => 1,
                                'ui' => 1,
                                'ui_on_text' => 'Yes',
                                'ui_off_text' => 'No',
                                'show_in_graphql' => 1
                            )
                        ),
                        'min' => '',
                        'max' => ''
                    ),
                    // Case Studies Individual Layout
                    'layout_case_studies' => array(
                        'key' => 'layout_case_studies',
                        'name' => 'case_studies',
                        'label' => 'Case Studies (Individual)',
                        'display' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_case_studies_subtitle',
                                'label' => 'Subtitle',
                                'name' => 'subtitle',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'default_value' => '',
                                'placeholder' => 'Our Work',
                                'show_in_graphql' => 1,
                            ),
                            array(
                                'key' => 'field_case_studies_title',
                                'label' => 'Title',
                                'name' => 'title',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'default_value' => '',
                                'placeholder' => 'Case Studies',
                                'show_in_graphql' => 1,
                            ),
                            array(
                                'key' => 'field_case_studies_hub_link',
                                'label' => 'Knowledge Hub Link',
                                'name' => 'hub_link',
                                'type' => 'url',
                                'instructions' => 'Link to Knowledge Hub filtered by Case Studies',
                                'required' => 0,
                                'default_value' => '',
                                'placeholder' => '/knowledge-hub?filter=case-studies',
                                'show_in_graphql' => 1,
                            ),
                            array(
                                'key' => 'field_case_studies_selected',
                                'label' => 'Selected Case Studies',
                                'name' => 'selected_case_studies',
                                'type' => 'relationship',
                                'instructions' => 'Select 2 case studies to show on homepage',
                                'required' => 0,
                                'post_type' => array('case_study'),
                                'taxonomy' => '',
                                'filters' => array('search'),
                                'elements' => '',
                                'min' => 0,
                                'max' => 2,
                                'return_format' => 'object',
                                'show_in_graphql' => 1,
                            )
                        ),
                        'min' => '',
                        'max' => ''
                    )
                ),
                'button_label' => 'Add Content Block',
                'min' => '',
                'max' => '',
                'show_in_graphql' => 1
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
            // Content Page Header (keep page-specific)
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
                        'key' => 'field_about_header_image',
                        'label' => 'Header Image',
                        'name' => 'header_image',
                        'type' => 'image',
                        'return_format' => 'object',
                        'preview_size' => 'large',
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

            // Who We Are – Your Digital Partner (keep page-specific)
            array(
                'key' => 'field_who_we_are_section_about',
                'label' => 'Who We Are – Your Digital Partner',
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

            // Leadership Team (keep page-specific)
            array(
                'key' => 'field_leadership_section',
                'label' => 'Leadership Team',
                'name' => 'leadership_section',
                'type' => 'group',
                'show_in_graphql' => 1,
                'sub_fields' => array(
                    array(
                        'key' => 'field_leader_image',
                        'label' => 'Leader Image',
                        'name' => 'image',
                        'type' => 'image',
                        'return_format' => 'object',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_leader_name',
                        'label' => 'Name',
                        'name' => 'name',
                        'type' => 'text',
                        'required' => 1,
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_leader_position',
                        'label' => 'Position',
                        'name' => 'position',
                        'type' => 'text',
                        'required' => 1,
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_leader_bio',
                        'label' => 'Bio',
                        'name' => 'bio',
                        'type' => 'wysiwyg',
                        'show_in_graphql' => 1,
                    )
                )
            ),

            // Global Content Selection (toggles)
            array(
                'key' => 'field_global_content_selection',
                'label' => 'Global Content Selection',
                'name' => 'global_content_selection',
                'type' => 'group',
                'show_in_graphql' => 1,
                'graphql_field_name' => 'globalContentSelection',
                'sub_fields' => array(
                    array(
                        'key' => 'field_enable_image_frame',
                        'label' => 'Enable Image Frame',
                        'name' => 'enable_image_frame',
                        'type' => 'true_false',
                        'ui' => 1,
                        'default_value' => 0,
                        'show_in_graphql' => 1,
                        'graphql_field_name' => 'enableImageFrame',
                    ),
                    array(
                        'key' => 'field_enable_services_accordion',
                        'label' => 'Enable Services Accordion',
                        'name' => 'enable_services_accordion',
                        'type' => 'true_false',
                        'ui' => 1,
                        'default_value' => 0,
                        'show_in_graphql' => 1,
                        'graphql_field_name' => 'enableServicesAccordion',
                    ),
                    array(
                        'key' => 'field_enable_why_cda',
                        'label' => 'Enable Why CDA',
                        'name' => 'enable_why_cda',
                        'type' => 'true_false',
                        'ui' => 1,
                        'default_value' => 0,
                        'show_in_graphql' => 1,
                        'graphql_field_name' => 'enableWhyCda',
                    ),
                    array(
                        'key' => 'field_enable_showreel',
                        'label' => 'Enable Showreel',
                        'name' => 'enable_showreel',
                        'type' => 'true_false',
                        'ui' => 1,
                        'default_value' => 0,
                        'show_in_graphql' => 1,
                        'graphql_field_name' => 'enableShowreel',
                    ),
                    array(
                        'key' => 'field_enable_culture_gallery_slider',
                        'label' => 'Enable Culture Gallery Slider',
                        'name' => 'enable_culture_gallery_slider',
                        'type' => 'true_false',
                        'ui' => 1,
                        'default_value' => 0,
                        'show_in_graphql' => 1,
                        'graphql_field_name' => 'enableCultureGallerySlider',
                    ),
                    array(
                        'key' => 'field_enable_approach',
                        'label' => 'Enable Approach',
                        'name' => 'enable_approach',
                        'type' => 'true_false',
                        'ui' => 1,
                        'default_value' => 0,
                        'show_in_graphql' => 1,
                        'graphql_field_name' => 'enableApproach',
                    ),
                    array(
                        'key' => 'field_enable_full_video',
                        'label' => 'Enable Full Video',
                        'name' => 'enable_full_video',
                        'type' => 'true_false',
                        'ui' => 1,
                        'default_value' => 0,
                        'show_in_graphql' => 1,
                        'graphql_field_name' => 'enableFullVideo',
                    ),
                    array(
                        'key' => 'field_enable_join_our_team',
                        'label' => 'Enable Join Our Team',
                        'name' => 'enable_join_our_team',
                        'type' => 'true_false',
                        'ui' => 1,
                        'default_value' => 0,
                        'show_in_graphql' => 1,
                        'graphql_field_name' => 'enableJoinOurTeam',
                    ),
                    array(
                        'key' => 'field_enable_three_columns_with_icons',
                        'label' => 'Enable 3 x Columns With Icons',
                        'name' => 'enable_three_columns_with_icons',
                        'type' => 'true_false',
                        'ui' => 1,
                        'default_value' => 0,
                        'show_in_graphql' => 1,
                        'graphql_field_name' => 'enableThreeColumnsWithIcons',
                    ),
                    array(
                        'key' => 'field_enable_contact_form_left_image_right',
                        'label' => 'Enable Contact Form Left, Image Right',
                        'name' => 'enable_contact_form_left_image_right',
                        'type' => 'true_false',
                        'ui' => 1,
                        'default_value' => 0,
                        'show_in_graphql' => 1,
                        'graphql_field_name' => 'enableContactFormLeftImageRight',
                    ),
                    array(
                        'key' => 'field_enable_technologies_slider',
                        'label' => 'Enable Technologies Slider',
                        'name' => 'enable_technologies_slider',
                        'type' => 'true_false',
                        'ui' => 1,
                        'default_value' => 0,
                        'show_in_graphql' => 1,
                        'graphql_field_name' => 'enableTechnologiesSlider',
                    ),
                    array(
                        'key' => 'field_enable_values',
                        'label' => 'Enable Values',
                        'name' => 'enable_values',
                        'type' => 'true_false',
                        'ui' => 1,
                        'default_value' => 0,
                        'show_in_graphql' => 1,
                        'graphql_field_name' => 'enableValues',
                    ),
                    array(
                        'key' => 'field_enable_stats_image',
                        'label' => 'Enable Stats Image',
                        'name' => 'enable_stats_image',
                        'type' => 'true_false',
                        'ui' => 1,
                        'default_value' => 0,
                        'show_in_graphql' => 1,
                        'graphql_field_name' => 'enableStatsImage',
                    ),
                    array(
                        'key' => 'field_enable_locations_image',
                        'label' => 'Enable Locations Image',
                        'name' => 'enable_locations_image',
                        'type' => 'true_false',
                        'ui' => 1,
                        'default_value' => 0,
                        'show_in_graphql' => 1,
                        'graphql_field_name' => 'enableLocationsImage',
                    ),
                    array(
                        'key' => 'field_enable_news_carousel',
                        'label' => 'Enable News Carousel',
                        'name' => 'enable_news_carousel',
                        'type' => 'true_false',
                        'ui' => 1,
                        'default_value' => 0,
                        'show_in_graphql' => 1,
                        'graphql_field_name' => 'enableNewsCarousel',
                    ),
                    array(
                        'key' => 'field_enable_newsletter_signup',
                        'label' => 'Enable Newsletter Signup',
                        'name' => 'enable_newsletter_signup',
                        'type' => 'true_false',
                        'ui' => 1,
                        'default_value' => 0,
                        'show_in_graphql' => 1,
                        'graphql_field_name' => 'enableNewsletterSignup',
                    ),
                )
            ),
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

// add_action('acf/init', 'cda_add_page_overrides');
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
        'description' => 'Allow per-page overrides of global content blocks',
        'show_in_graphql' => 1,
        'graphql_field_name' => 'globalBlockOverrides',
    ));
}

// ============================================================================
// CONTACT PAGE CONTENT
// ============================================================================

add_action('acf/init', 'cda_add_contact_page_fields');
function cda_add_contact_page_fields() {
    acf_add_local_field_group(array(
        'key' => 'group_contact_page_content',
        'title' => 'Contact Page Content',
        'fields' => array(
            array(
                'key' => 'field_contact_header',
                'label' => 'Header',
                'name' => 'header',
                'type' => 'group',
                'layout' => 'block',
                'show_in_graphql' => 1,
                'sub_fields' => array(
                    array(
                        'key' => 'field_contact_title',
                        'label' => 'Title',
                        'name' => 'title',
                        'type' => 'text',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_contact_text',
                        'label' => 'Text',
                        'name' => 'text',
                        'type' => 'wysiwyg',
                        'show_in_graphql' => 1,
                    ),
                )
            ),
            array(
                'key' => 'field_contact_form_code',
                'label' => 'HubSpot Form Code',
                'name' => 'form_code',
                'type' => 'textarea',
                'instructions' => 'Paste the HubSpot form embed code',
                'rows' => 5,
                'show_in_graphql' => 1,
            ),
            array(
                'key' => 'field_contact_details',
                'label' => 'Contact Details',
                'name' => 'details',
                'type' => 'wysiwyg',
                'instructions' => 'Optional extra details to show alongside the form',
                'show_in_graphql' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'template-contact.php'
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
        'description' => 'Content for Contact page',
        'show_in_graphql' => 1,
        'graphql_field_name' => 'contactContent',
        'map_graphql_types_from_location_rules' => false,
        'graphql_types' => array('Page')
    ));
}

// ============================================================================
// KNOWLEDGE HUB PAGE CONTENT
// ============================================================================

add_action('acf/init', 'cda_add_knowledge_hub_fields');
function cda_add_knowledge_hub_fields() {
    acf_add_local_field_group(array(
        'key' => 'group_knowledge_hub_page_content',
        'title' => 'Knowledge Hub Content',
        'fields' => array(
            array(
                'key' => 'field_kh_header',
                'label' => 'Header',
                'name' => 'header',
                'type' => 'group',
                'layout' => 'block',
                'show_in_graphql' => 1,
                'sub_fields' => array(
                    array(
                        'key' => 'field_kh_title',
                        'label' => 'Title',
                        'name' => 'title',
                        'type' => 'text',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_kh_intro',
                        'label' => 'Intro Text',
                        'name' => 'intro',
                        'type' => 'wysiwyg',
                        'show_in_graphql' => 1,
                    ),
                )
            ),
            array(
                'key' => 'field_kh_featured_posts',
                'label' => 'Featured Posts',
                'name' => 'featured_posts',
                'type' => 'post_object',
                'post_type' => array('post','blog_posts'),
                'multiple' => 1,
                'return_format' => 'object',
                'show_in_graphql' => 1,
            )
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'template-knowledge-hub.php'
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
        'description' => 'Content for the Knowledge Hub landing page',
        'show_in_graphql' => 1,
        'graphql_field_name' => 'knowledgeHubContent',
        'map_graphql_types_from_location_rules' => false,
        'graphql_types' => array('Page')
    ));
}

// ============================================================================
// PAGE-LEVEL GLOBAL CONTENT TOGGLES (for any standard page)
// ============================================================================

add_action('acf/init', 'cda_add_page_global_toggles');
function cda_add_page_global_toggles() {
    acf_add_local_field_group(array(
        'key' => 'group_page_global_content_selection',
        'title' => 'Global Content Selection',
        'fields' => array(
            array('key' => 'field_page_enable_image_frame', 'label' => 'Enable Image Frame', 'name' => 'enable_image_frame', 'type' => 'true_false', 'ui' => 1, 'default_value' => 0, 'show_in_graphql' => 1, 'graphql_field_name' => 'enableImageFrame'),
            array('key' => 'field_page_enable_services_accordion', 'label' => 'Enable Services Accordion', 'name' => 'enable_services_accordion', 'type' => 'true_false', 'ui' => 1, 'default_value' => 0, 'show_in_graphql' => 1, 'graphql_field_name' => 'enableServicesAccordion'),
            array('key' => 'field_page_enable_why_cda', 'label' => 'Enable Why CDA', 'name' => 'enable_why_cda', 'type' => 'true_false', 'ui' => 1, 'default_value' => 0, 'show_in_graphql' => 1, 'graphql_field_name' => 'enableWhyCda'),
            array('key' => 'field_page_enable_showreel', 'label' => 'Enable Showreel', 'name' => 'enable_showreel', 'type' => 'true_false', 'ui' => 1, 'default_value' => 0, 'show_in_graphql' => 1, 'graphql_field_name' => 'enableShowreel'),
            array('key' => 'field_page_enable_culture_gallery_slider', 'label' => 'Enable Culture Gallery Slider', 'name' => 'enable_culture_gallery_slider', 'type' => 'true_false', 'ui' => 1, 'default_value' => 0, 'show_in_graphql' => 1, 'graphql_field_name' => 'enableCultureGallerySlider'),
            array('key' => 'field_page_enable_approach', 'label' => 'Enable Approach', 'name' => 'enable_approach', 'type' => 'true_false', 'ui' => 1, 'default_value' => 0, 'show_in_graphql' => 1, 'graphql_field_name' => 'enableApproach'),
            array('key' => 'field_page_enable_full_video', 'label' => 'Enable Full Video', 'name' => 'enable_full_video', 'type' => 'true_false', 'ui' => 1, 'default_value' => 0, 'show_in_graphql' => 1, 'graphql_field_name' => 'enableFullVideo'),
            array('key' => 'field_page_enable_join_our_team', 'label' => 'Enable Join Our Team', 'name' => 'enable_join_our_team', 'type' => 'true_false', 'ui' => 1, 'default_value' => 0, 'show_in_graphql' => 1, 'graphql_field_name' => 'enableJoinOurTeam'),
            array('key' => 'field_page_enable_three_columns_with_icons', 'label' => 'Enable 3 x Columns With Icons', 'name' => 'enable_three_columns_with_icons', 'type' => 'true_false', 'ui' => 1, 'default_value' => 0, 'show_in_graphql' => 1, 'graphql_field_name' => 'enableThreeColumnsWithIcons'),
            array('key' => 'field_page_enable_contact_form_left_image_right', 'label' => 'Enable Contact Form Left, Image Right', 'name' => 'enable_contact_form_left_image_right', 'type' => 'true_false', 'ui' => 1, 'default_value' => 0, 'show_in_graphql' => 1, 'graphql_field_name' => 'enableContactFormLeftImageRight'),
            array('key' => 'field_page_enable_technologies_slider', 'label' => 'Enable Technologies Slider', 'name' => 'enable_technologies_slider', 'type' => 'true_false', 'ui' => 1, 'default_value' => 0, 'show_in_graphql' => 1, 'graphql_field_name' => 'enableTechnologiesSlider'),
            array('key' => 'field_page_enable_values', 'label' => 'Enable Values', 'name' => 'enable_values', 'type' => 'true_false', 'ui' => 1, 'default_value' => 0, 'show_in_graphql' => 1, 'graphql_field_name' => 'enableValues'),
            array('key' => 'field_page_enable_stats_image', 'label' => 'Enable Stats Image', 'name' => 'enable_stats_image', 'type' => 'true_false', 'ui' => 1, 'default_value' => 0, 'show_in_graphql' => 1, 'graphql_field_name' => 'enableStatsImage'),
            array('key' => 'field_page_enable_locations_image', 'label' => 'Enable Locations Image', 'name' => 'enable_locations_image', 'type' => 'true_false', 'ui' => 1, 'default_value' => 0, 'show_in_graphql' => 1, 'graphql_field_name' => 'enableLocationsImage'),
            array('key' => 'field_page_enable_news_carousel', 'label' => 'Enable News Carousel', 'name' => 'enable_news_carousel', 'type' => 'true_false', 'ui' => 1, 'default_value' => 0, 'show_in_graphql' => 1, 'graphql_field_name' => 'enableNewsCarousel'),
            array('key' => 'field_page_enable_newsletter_signup', 'label' => 'Enable Newsletter Signup', 'name' => 'enable_newsletter_signup', 'type' => 'true_false', 'ui' => 1, 'default_value' => 0, 'show_in_graphql' => 1, 'graphql_field_name' => 'enableNewsletterSignup')
        ),
        'location' => array(
            array(
                array('param' => 'post_type', 'operator' => '==', 'value' => 'page'),
                array('param' => 'page_template', 'operator' => '!=', 'value' => 'template-about-us.php')
            )
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => 'Toggle rendering of global content blocks on standard pages.',
        'show_in_graphql' => 1,
        'graphql_field_name' => 'globalContentSelection',
        'map_graphql_types_from_location_rules' => false,
        'graphql_types' => array('Page')
    ));
}

// ============================================================================
// SERVICES ACF FIELD GROUPS
// ============================================================================

// add_action('acf/init', 'cda_add_services_fields');
// The Services field group is managed in WordPress (DB/JSON). Disabling the hardcoded
// PHP registration prevents it from overriding the imported ACF group.
function cda_add_services_fields() {
    // Intentionally left disabled.
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

// ============================================================================
// BLOG POSTS ACF FIELD GROUPS
// ============================================================================

add_action('acf/init', 'cda_add_blog_posts_fields');
function cda_add_blog_posts_fields() {
    
    // Blog Posts Content Field Group
    acf_add_local_field_group(array(
        'key' => 'group_blog_posts_content',
        'title' => 'Blog Post Content',
        'fields' => array(
            
            // ARTICLE META
            array(
                'key' => 'field_blog_post_meta',
                'label' => 'Article Meta Information',
                'name' => 'article_meta',
                'type' => 'group',
                'instructions' => 'Additional information about this article',
                'layout' => 'block',
                'show_in_graphql' => 1,
                'graphql_field_name' => 'articleMeta',
                'sub_fields' => array(
                    array(
                        'key' => 'field_blog_post_read_time',
                        'label' => 'Estimated Read Time',
                        'name' => 'read_time',
                        'type' => 'number',
                        'instructions' => 'Estimated read time in minutes',
                        'min' => 1,
                        'max' => 60,
                        'default_value' => 5,
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_blog_post_featured',
                        'label' => 'Featured Article',
                        'name' => 'featured',
                        'type' => 'true_false',
                        'instructions' => 'Mark as featured to show prominently on resource center',
                        'default_value' => 0,
                        'ui' => 1,
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_blog_post_external_url',
                        'label' => 'External URL',
                        'name' => 'external_url',
                        'type' => 'url',
                        'instructions' => 'If this article links to external content',
                        'show_in_graphql' => 1,
                    ),
                ),
            ),
            
            // SEO & SOCIAL
            array(
                'key' => 'field_blog_post_social_sharing',
                'label' => 'Social Sharing',
                'name' => 'social_sharing',
                'type' => 'group',
                'layout' => 'block',
                'show_in_graphql' => 1,
                'sub_fields' => array(
                    array(
                        'key' => 'field_blog_post_social_title',
                        'label' => 'Social Media Title',
                        'name' => 'social_title',
                        'type' => 'text',
                        'instructions' => 'Custom title for social media sharing',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_blog_post_social_description',
                        'label' => 'Social Media Description',
                        'name' => 'social_description',
                        'type' => 'textarea',
                        'instructions' => 'Custom description for social media sharing',
                        'rows' => 3,
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_blog_post_social_image',
                        'label' => 'Social Media Image',
                        'name' => 'social_image',
                        'type' => 'image',
                        'instructions' => 'Image for social media sharing (if different from featured image)',
                        'return_format' => 'array',
                        'show_in_graphql' => 1,
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'blog_posts',
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
        'description' => 'Content fields for blog posts and articles',
        'show_in_graphql' => 1,
        'graphql_field_name' => 'blogPostFields',
    ));
}



// ============================================================================
// TECHNOLOGIES ACF FIELD GROUPS
// ============================================================================

add_action('acf/init', 'cda_add_technologies_fields');
function cda_add_technologies_fields() {
    
    // Technologies Content Field Group
    acf_add_local_field_group(array(
        'key' => 'group_technologies_content',
        'title' => 'Technology Information',
        'fields' => array(
            
            // TECH DETAILS
            array(
                'key' => 'field_technology_details',
                'label' => 'Technology Details',
                'name' => 'technology_details',
                'type' => 'group',
                'instructions' => 'Information about this technology',
                'layout' => 'block',
                'show_in_graphql' => 1,
                'graphql_field_name' => 'technologyDetails',
                'sub_fields' => array(
                    array(
                        'key' => 'field_technology_logo',
                        'label' => 'Logo/Icon',
                        'name' => 'logo',
                        'type' => 'image',
                        'instructions' => 'Technology logo or icon',
                        'return_format' => 'array',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_technology_website_url',
                        'label' => 'Official Website',
                        'name' => 'website_url',
                        'type' => 'url',
                        'instructions' => 'Link to the official website',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_technology_version',
                        'label' => 'Version We Use',
                        'name' => 'version',
                        'type' => 'text',
                        'instructions' => 'Current version we use (if applicable)',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_technology_proficiency',
                        'label' => 'Our Proficiency Level',
                        'name' => 'proficiency',
                        'type' => 'select',
                        'choices' => array(
                            'learning' => 'Learning',
                            'competent' => 'Competent',
                            'proficient' => 'Proficient',
                            'expert' => 'Expert',
                            'specialist' => 'Specialist',
                        ),
                        'show_in_graphql' => 1,
                    ),
                ),
            ),
            
            // USAGE INFO
            array(
                'key' => 'field_technology_usage',
                'label' => 'Usage Information',
                'name' => 'usage_info',
                'type' => 'group',
                'layout' => 'block',
                'show_in_graphql' => 1,
                'sub_fields' => array(
                    array(
                        'key' => 'field_technology_use_cases',
                        'label' => 'What We Use It For',
                        'name' => 'use_cases',
                        'type' => 'textarea',
                        'instructions' => 'Brief description of how we use this technology',
                        'rows' => 3,
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_technology_benefits',
                        'label' => 'Key Benefits',
                        'name' => 'benefits',
                        'type' => 'repeater',
                        'instructions' => 'Main benefits of using this technology',
                        'min' => 0,
                        'max' => 5,
                        'layout' => 'table',
                        'button_label' => 'Add Benefit',
                        'show_in_graphql' => 1,
                        'sub_fields' => array(
                            array(
                                'key' => 'field_technology_benefit_text',
                                'label' => 'Benefit',
                                'name' => 'benefit',
                                'type' => 'text',
                                'show_in_graphql' => 1,
                            ),
                        ),
                    ),
                ),
            ),
            
            // PORTFOLIO CONNECTION
            array(
                'key' => 'field_technology_projects',
                'label' => 'Related Projects',
                'name' => 'related_projects',
                'type' => 'post_object',
                'instructions' => 'Case studies or services that use this technology',
                'post_type' => array('case_studies', 'services'),
                'multiple' => 1,
                'max' => 5,
                'return_format' => 'object',
                'show_in_graphql' => 1,
            ),
            
            // FEATURED FLAG
            array(
                'key' => 'field_technology_featured',
                'label' => 'Featured Technology',
                'name' => 'featured',
                'type' => 'true_false',
                'instructions' => 'Mark as featured to show prominently',
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
                    'value' => 'technologies',
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
        'description' => 'Information fields for technologies',
        'show_in_graphql' => 1,
        'graphql_field_name' => 'technologyFields',
    ));
}

?>
