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

// Enable ACF GraphQL
add_filter('acf/settings/graphql_enabled', '__return_true');

// Enable GraphQL for all ACF field groups
add_filter('graphql_acf_get_fields_config', function($config, $acf_field, $type_name) {
    $config['show_in_graphql'] = true;
    return $config;
}, 10, 3);

// Remove Gutenberg and other page builders
add_action('init', 'cda_remove_gutenberg');
function cda_remove_gutenberg() {
    add_filter('use_block_editor_for_post_type', '__return_false', 100);
    remove_post_type_support('page', 'editor');
    remove_post_type_support('post', 'editor');
}

// Create Custom Post Types
add_action('init', 'cda_create_custom_post_types');
function cda_create_custom_post_types() {
    // Case Studies
    register_post_type('case_study', array(
        'labels' => array(
            'name' => __('Case Studies'),
            'singular_name' => __('Case Study'),
            'menu_name' => __('Case Studies'),
            'all_items' => __('All Case Studies'),
            'add_new' => __('Add New'),
            'add_new_item' => __('Add New Case Study'),
            'edit_item' => __('Edit Case Study'),
            'new_item' => __('New Case Study'),
            'view_item' => __('View Case Study'),
            'search_items' => __('Search Case Studies'),
            'not_found' => __('No case studies found'),
            'not_found_in_trash' => __('No case studies found in trash')
        ),
        'public' => false,
        'publicly_queryable' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => false,
        'show_in_admin_bar' => false,
        'show_in_rest' => true,
        'show_in_graphql' => true,
        'graphql_single_name' => 'CaseStudy',
        'graphql_plural_name' => 'CaseStudies',
        'rest_base' => 'case-studies',
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'exclude_from_search' => true,
        'rewrite' => false,
        'supports' => array('title', 'thumbnail', 'excerpt'),
        'menu_position' => 20,
        'menu_icon' => 'dashicons-portfolio'
    ));

    // Team Members
    register_post_type('team_member', array(
        'labels' => array(
            'name' => __('Team Members'),
            'singular_name' => __('Team Member'),
            'menu_name' => __('Team Members'),
            'all_items' => __('All Team Members'),
            'add_new' => __('Add New'),
            'add_new_item' => __('Add New Team Member'),
            'edit_item' => __('Edit Team Member'),
            'new_item' => __('New Team Member'),
            'view_item' => __('View Team Member'),
            'search_items' => __('Search Team Members'),
            'not_found' => __('No team members found'),
            'not_found_in_trash' => __('No team members found in trash')
        ),
        'public' => false,
        'publicly_queryable' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => false,
        'show_in_admin_bar' => false,
        'show_in_rest' => true,
        'show_in_graphql' => true,
        'graphql_single_name' => 'TeamMember',
        'graphql_plural_name' => 'TeamMembers',
        'rest_base' => 'team-members',
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'exclude_from_search' => true,
        'rewrite' => false,
        'supports' => array('title', 'thumbnail', 'editor'),
        'menu_position' => 21,
        'menu_icon' => 'dashicons-groups'
    ));

    // Jobs/Careers
    register_post_type('job', array(
        'labels' => array(
            'name' => __('Jobs'),
            'singular_name' => __('Job'),
            'menu_name' => __('Jobs'),
            'all_items' => __('All Jobs'),
            'add_new' => __('Add New'),
            'add_new_item' => __('Add New Job'),
            'edit_item' => __('Edit Job'),
            'new_item' => __('New Job'),
            'view_item' => __('View Job'),
            'search_items' => __('Search Jobs'),
            'not_found' => __('No jobs found'),
            'not_found_in_trash' => __('No jobs found in trash')
        ),
        'public' => false,
        'publicly_queryable' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => false,
        'show_in_admin_bar' => false,
        'show_in_rest' => true,
        'show_in_graphql' => true,
        'graphql_single_name' => 'Job',
        'graphql_plural_name' => 'Jobs',
        'rest_base' => 'jobs',
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'exclude_from_search' => true,
        'rewrite' => false,
        'supports' => array('title'),
        'menu_position' => 22,
        'menu_icon' => 'dashicons-businessman'
    ));

    // News Articles
    register_post_type('news_article', array(
        'labels' => array(
            'name' => __('News Articles'),
            'singular_name' => __('News Article'),
            'menu_name' => __('News Articles'),
            'all_items' => __('All News Articles'),
            'add_new' => __('Add New'),
            'add_new_item' => __('Add New News Article'),
            'edit_item' => __('Edit News Article'),
            'new_item' => __('New News Article'),
            'view_item' => __('View News Article'),
            'search_items' => __('Search News Articles'),
            'not_found' => __('No news articles found'),
            'not_found_in_trash' => __('No news articles found in trash')
        ),
        'public' => false,
        'publicly_queryable' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => false,
        'show_in_admin_bar' => false,
        'show_in_rest' => true,
        'show_in_graphql' => true,
        'graphql_single_name' => 'NewsArticle',
        'graphql_plural_name' => 'NewsArticles',
        'rest_base' => 'news-articles',
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'exclude_from_search' => true,
        'rewrite' => false,
        'supports' => array('title', 'editor', 'thumbnail', 'author', 'excerpt'),
        'menu_position' => 23,
        'menu_icon' => 'dashicons-megaphone'
    ));

    // Technologies
    register_post_type('technology', array(
        'labels' => array(
            'name' => __('Technologies'),
            'singular_name' => __('Technology'),
            'menu_name' => __('Technologies'),
            'all_items' => __('All Technologies'),
            'add_new' => __('Add New'),
            'add_new_item' => __('Add New Technology'),
            'edit_item' => __('Edit Technology'),
            'new_item' => __('New Technology'),
            'view_item' => __('View Technology'),
            'search_items' => __('Search Technologies'),
            'not_found' => __('No technologies found'),
            'not_found_in_trash' => __('No technologies found in trash')
        ),
        'public' => false,
        'publicly_queryable' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => false,
        'show_in_admin_bar' => false,
        'show_in_rest' => true,
        'show_in_graphql' => true,
        'graphql_single_name' => 'Technology',
        'graphql_plural_name' => 'Technologies',
        'rest_base' => 'technologies',
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'exclude_from_search' => true,
        'rewrite' => false,
        'supports' => array('title', 'thumbnail', 'editor'),
        'menu_position' => 24,
        'menu_icon' => 'dashicons-laptop'
    ));
}

// Create Taxonomies
add_action('init', 'cda_create_taxonomies');
function cda_create_taxonomies() {
    // Services taxonomy
    register_taxonomy('service', array('case_study', 'news_article', 'team_member'), array(
        'hierarchical' => true,
        'labels' => array(
            'name' => _x('Services', 'taxonomy general name'),
            'singular_name' => _x('Service', 'taxonomy singular name'),
            'search_items' => __('Search Services'),
            'all_items' => __('All Services'),
            'parent_item' => __('Parent Service'),
            'parent_item_colon' => __('Parent Service:'),
            'edit_item' => __('Edit Service'),
            'update_item' => __('Update Service'),
            'add_new_item' => __('Add New Service'),
            'new_item_name' => __('New Service Name'),
            'menu_name' => __('Services'),
        ),
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'service'),
        'show_in_rest' => true,
        'show_in_graphql' => true,
        'graphql_single_name' => 'Service',
        'graphql_plural_name' => 'Services'
    ));

    // Sectors taxonomy
    register_taxonomy('sector', array('case_study', 'news_article'), array(
        'hierarchical' => true,
        'labels' => array(
            'name' => _x('Sectors', 'taxonomy general name'),
            'singular_name' => _x('Sector', 'taxonomy singular name'),
            'search_items' => __('Search Sectors'),
            'all_items' => __('All Sectors'),
            'parent_item' => __('Parent Sector'),
            'parent_item_colon' => __('Parent Sector:'),
            'edit_item' => __('Edit Sector'),
            'update_item' => __('Update Sector'),
            'add_new_item' => __('Add New Sector'),
            'new_item_name' => __('New Sector Name'),
            'menu_name' => __('Sectors'),
        ),
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'sector'),
        'show_in_rest' => true,
        'show_in_graphql' => true,
        'graphql_single_name' => 'Sector',
        'graphql_plural_name' => 'Sectors'
    ));

    // Locations taxonomy
    register_taxonomy('location', array('team_member', 'job'), array(
        'hierarchical' => true,
        'labels' => array(
            'name' => _x('Locations', 'taxonomy general name'),
            'singular_name' => _x('Location', 'taxonomy singular name'),
            'search_items' => __('Search Locations'),
            'all_items' => __('All Locations'),
            'parent_item' => __('Parent Location'),
            'parent_item_colon' => __('Parent Location:'),
            'edit_item' => __('Edit Location'),
            'update_item' => __('Update Location'),
            'add_new_item' => __('Add New Location'),
            'new_item_name' => __('New Location Name'),
            'menu_name' => __('Locations'),
        ),
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'location'),
        'show_in_rest' => true,
        'show_in_graphql' => true,
        'graphql_single_name' => 'Location',
        'graphql_plural_name' => 'Locations'
    ));
}

// Add ACF options page for global content
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

// ACF Field Groups
add_action('acf/init', 'cda_add_acf_field_groups');
function cda_add_acf_field_groups() {
    
    // Global Content
    acf_add_local_field_group(array(
        'key' => 'group_global_content',
        'title' => 'Global Content',
        'fields' => array(
            array(
                'key' => 'field_global_header',
                'label' => 'Header',
                'name' => 'global_header',
                'type' => 'group',
                'show_in_graphql' => 1,
                'sub_fields' => array(
                    array(
                        'key' => 'field_header_logo',
                        'label' => 'Logo',
                        'name' => 'logo',
                        'type' => 'image',
                        'return_format' => 'array',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_header_navigation',
                        'label' => 'Navigation',
                        'name' => 'navigation',
                        'type' => 'repeater',
                        'show_in_graphql' => 1,
                        'sub_fields' => array(
                            array(
                                'key' => 'field_nav_item_label',
                                'label' => 'Label',
                                'name' => 'label',
                                'type' => 'text',
                                'show_in_graphql' => 1,
                            ),
                            array(
                                'key' => 'field_nav_item_link',
                                'label' => 'Link',
                                'name' => 'link',
                                'type' => 'page_link',
                                'show_in_graphql' => 1,
                            )
                        )
                    )
                )
            ),
            array(
                'key' => 'field_global_footer',
                'label' => 'Footer',
                'name' => 'global_footer',
                'type' => 'group',
                'show_in_graphql' => 1,
                'sub_fields' => array(
                    array(
                        'key' => 'field_footer_logo',
                        'label' => 'Logo',
                        'name' => 'logo',
                        'type' => 'image',
                        'return_format' => 'array',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_footer_description',
                        'label' => 'Description',
                        'name' => 'description',
                        'type' => 'textarea',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_footer_contact_info',
                        'label' => 'Contact Information',
                        'name' => 'contact_info',
                        'type' => 'group',
                        'show_in_graphql' => 1,
                        'sub_fields' => array(
                            array(
                                'key' => 'field_footer_email',
                                'label' => 'Email',
                                'name' => 'email',
                                'type' => 'email',
                                'show_in_graphql' => 1,
                            ),
                            array(
                                'key' => 'field_footer_phone',
                                'label' => 'Phone',
                                'name' => 'phone',
                                'type' => 'text',
                                'show_in_graphql' => 1,
                            ),
                            array(
                                'key' => 'field_footer_address',
                                'label' => 'Address',
                                'name' => 'address',
                                'type' => 'textarea',
                                'show_in_graphql' => 1,
                            )
                        )
                    )
                )
            ),
            array(
                'key' => 'field_global_forms',
                'label' => 'Forms',
                'name' => 'global_forms',
                'type' => 'group',
                'show_in_graphql' => 1,
                'sub_fields' => array(
                    array(
                        'key' => 'field_hubspot_portal_id',
                        'label' => 'HubSpot Portal ID',
                        'name' => 'hubspot_portal_id',
                        'type' => 'text',
                        'default_value' => '143891025',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_contact_form',
                        'label' => 'Contact Form',
                        'name' => 'contact_form',
                        'type' => 'text',
                        'instructions' => 'HubSpot form ID for general contact form',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_ecommerce_form',
                        'label' => 'eCommerce Services Form',
                        'name' => 'ecommerce_form',
                        'type' => 'text',
                        'default_value' => '80e897f8-198d-42e2-81b8-41f6732d4218',
                        'show_in_graphql' => 1,
                    )
                )
            )
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'acf-options'
                )
            )
        ),
        'show_in_graphql' => 1,
        'graphql_field_name' => 'globalContent',
    ));

    // Homepage Content
    acf_add_local_field_group(array(
        'key' => 'group_homepage',
        'title' => 'Homepage Content',
        'fields' => array(
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
                        'type' => 'text',
                        'required' => 1,
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_header_subtitle',
                        'label' => 'Subtitle',
                        'name' => 'subtitle',
                        'type' => 'textarea',
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
                    )
                )
            ),
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
                        'type' => 'textarea',
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
                                'type' => 'textarea',
                                'show_in_graphql' => 1,
                            )
                        )
                    )
                )
            ),
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
                        'type' => 'textarea',
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

    // Case Study Content
    acf_add_local_field_group(array(
        'key' => 'group_case_study',
        'title' => 'Case Study Content',
        'fields' => array(
            array(
                'key' => 'field_case_study_header',
                'label' => 'Case Study Header',
                'name' => 'case_study_header',
                'type' => 'group',
                'show_in_graphql' => 1,
                'sub_fields' => array(
                    array(
                        'key' => 'field_case_study_client',
                        'label' => 'Client',
                        'name' => 'client',
                        'type' => 'text',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_case_study_industry',
                        'label' => 'Industry',
                        'name' => 'industry',
                        'type' => 'text',
                        'show_in_graphql' => 1,
                    )
                )
            ),
            array(
                'key' => 'field_case_study_challenge',
                'label' => 'Challenge',
                'name' => 'challenge',
                'type' => 'wysiwyg',
                'show_in_graphql' => 1,
            ),
            array(
                'key' => 'field_case_study_solution',
                'label' => 'Solution',
                'name' => 'solution',
                'type' => 'wysiwyg',
                'show_in_graphql' => 1,
            ),
            array(
                'key' => 'field_case_study_results',
                'label' => 'Results',
                'name' => 'results',
                'type' => 'wysiwyg',
                'show_in_graphql' => 1,
            ),
            array(
                'key' => 'field_case_study_services_used',
                'label' => 'Services Used',
                'name' => 'services_used',
                'type' => 'relationship',
                'post_type' => array('page'),
                'show_in_graphql' => 1,
            )
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'case_study'
                )
            )
        ),
        'show_in_graphql' => 1,
        'graphql_field_name' => 'caseStudyContent',
    ));

    // Team Member Profile
    acf_add_local_field_group(array(
        'key' => 'group_team_member',
        'title' => 'Team Member Profile',
        'fields' => array(
            array(
                'key' => 'field_team_member_position',
                'label' => 'Position',
                'name' => 'position',
                'type' => 'text',
                'show_in_graphql' => 1,
            ),
            array(
                'key' => 'field_team_member_bio',
                'label' => 'Bio',
                'name' => 'bio',
                'type' => 'textarea',
                'show_in_graphql' => 1,
            ),
            array(
                'key' => 'field_team_member_email',
                'label' => 'Email',
                'name' => 'email',
                'type' => 'email',
                'show_in_graphql' => 1,
            ),
            array(
                'key' => 'field_team_member_linkedin',
                'label' => 'LinkedIn',
                'name' => 'linkedin',
                'type' => 'url',
                'show_in_graphql' => 1,
            )
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'team_member'
                )
            )
        ),
        'show_in_graphql' => 1,
        'graphql_field_name' => 'teamMemberContent',
    ));

    // Service Page Content
    acf_add_local_field_group(array(
        'key' => 'group_service_page',
        'title' => 'Service Page Content',
        'fields' => array(
            array(
                'key' => 'field_service_header',
                'label' => 'Service Header',
                'name' => 'service_header',
                'type' => 'group',
                'show_in_graphql' => 1,
                'sub_fields' => array(
                    array(
                        'key' => 'field_service_title',
                        'label' => 'Title',
                        'name' => 'title',
                        'type' => 'text',
                        'required' => 1,
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_service_subtitle',
                        'label' => 'Subtitle',
                        'name' => 'subtitle',
                        'type' => 'textarea',
                        'show_in_graphql' => 1,
                    )
                )
            ),
            array(
                'key' => 'field_service_related_case_studies',
                'label' => 'Related Case Studies',
                'name' => 'related_case_studies',
                'type' => 'relationship',
                'post_type' => array('case_study'),
                'show_in_graphql' => 1,
            )
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'template-service.php'
                )
            )
        ),
        'show_in_graphql' => 1,
        'graphql_field_name' => 'serviceContent',
    ));

    // Job Listing Details
    acf_add_local_field_group(array(
        'key' => 'group_job_listing',
        'title' => 'Job Listing Details',
        'fields' => array(
            array(
                'key' => 'field_job_position',
                'label' => 'Position',
                'name' => 'position',
                'type' => 'text',
                'required' => 1,
                'show_in_graphql' => 1,
            ),
            array(
                'key' => 'field_job_location',
                'label' => 'Location',
                'name' => 'location',
                'type' => 'text',
                'show_in_graphql' => 1,
            ),
            array(
                'key' => 'field_job_type',
                'label' => 'Job Type',
                'name' => 'job_type',
                'type' => 'select',
                'choices' => array(
                    'full_time' => 'Full Time',
                    'part_time' => 'Part Time',
                    'contract' => 'Contract',
                    'internship' => 'Internship'
                ),
                'show_in_graphql' => 1,
            ),
            array(
                'key' => 'field_job_description',
                'label' => 'Job Description',
                'name' => 'description',
                'type' => 'wysiwyg',
                'show_in_graphql' => 1,
            )
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'job'
                )
            )
        ),
        'show_in_graphql' => 1,
        'graphql_field_name' => 'jobContent',
    ));

    // News Article Details
    acf_add_local_field_group(array(
        'key' => 'group_news_article',
        'title' => 'News Article Details',
        'fields' => array(
            array(
                'key' => 'field_news_category',
                'label' => 'Category',
                'name' => 'category',
                'type' => 'select',
                'choices' => array(
                    'technology' => 'Technology',
                    'business' => 'Business',
                    'innovation' => 'Innovation',
                    'company_news' => 'Company News',
                    'industry_insights' => 'Industry Insights'
                ),
                'show_in_graphql' => 1,
            ),
            array(
                'key' => 'field_news_excerpt',
                'label' => 'Excerpt',
                'name' => 'excerpt',
                'type' => 'textarea',
                'show_in_graphql' => 1,
            ),
            array(
                'key' => 'field_news_related_case_studies',
                'label' => 'Related Case Studies',
                'name' => 'related_case_studies',
                'type' => 'relationship',
                'post_type' => array('case_study'),
                'show_in_graphql' => 1,
            )
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'news_article'
                )
            )
        ),
        'show_in_graphql' => 1,
        'graphql_field_name' => 'newsContent',
    ));

    // Technology Details
    acf_add_local_field_group(array(
        'key' => 'group_technology',
        'title' => 'Technology Details',
        'fields' => array(
            array(
                'key' => 'field_technology_category',
                'label' => 'Category',
                'name' => 'category',
                'type' => 'select',
                'choices' => array(
                    'frontend' => 'Frontend',
                    'backend' => 'Backend',
                    'database' => 'Database',
                    'cloud' => 'Cloud',
                    'devops' => 'DevOps',
                    'analytics' => 'Analytics',
                    'security' => 'Security'
                ),
                'show_in_graphql' => 1,
            ),
            array(
                'key' => 'field_technology_description',
                'label' => 'Description',
                'name' => 'description',
                'type' => 'textarea',
                'show_in_graphql' => 1,
            ),
            array(
                'key' => 'field_technology_related_services',
                'label' => 'Related Services',
                'name' => 'related_services',
                'type' => 'relationship',
                'post_type' => array('page'),
                'show_in_graphql' => 1,
            )
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'technology'
                )
            )
        ),
        'show_in_graphql' => 1,
        'graphql_field_name' => 'technologyContent',
    ));
}

// CORS Headers for GraphQL
add_action('graphql_init', function() {
    // Add CORS headers for GraphQL endpoint
    header('Access-Control-Allow-Origin: http://localhost:3000');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    header('Access-Control-Allow-Credentials: true');
    
    // Handle preflight requests
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        exit();
    }
});

?>