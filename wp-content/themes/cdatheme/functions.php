<?php
/**
 * CDA Theme functions and definitions
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Include ACF GraphQL Configuration
require_once get_template_directory() . '/../cda-theme/acf-graphql-config.php';

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
        'footer' => __('Footer Menu', 'cdatheme')
    ));
}
add_action('after_setup_theme', 'cdatheme_setup');

// Enqueue scripts and styles
function cdatheme_scripts() {
    // Remove any frontend styles since we're using headless
    // We only need this for admin functionality
}
add_action('wp_enqueue_scripts', 'cdatheme_scripts');

function add_cors_headers() {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
}
add_action('init', 'add_cors_headers');

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

// Allow SVG uploads
function allow_svg_upload($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'allow_svg_upload');

// Fix SVG display in media library
function fix_svg_display($response, $attachment, $meta) {
    if ($response['type'] === 'image' && $response['subtype'] === 'svg+xml') {
        $response['image'] = array(
            'src' => $response['url'],
            'width' => 150,
            'height' => 150,
        );
    }
    return $response;
}
add_filter('wp_prepare_attachment_for_js', 'fix_svg_display', 10, 3);


// Add Global Content Options in cda-cms.php
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

// // Add Header and Footer Fields
// add_action('acf/init', 'cda_add_global_content_fields');
// function cda_add_global_content_fields() {
//     // Header Content
//     acf_add_local_field_group(array(
//         'key' => 'group_header_content',
//         'title' => 'Header Content',
//         'fields' => array(
//             array(
//                 'key' => 'field_header_logo',
//                 'label' => 'Header Logo',
//                 'name' => 'header_logo',
//                 'type' => 'image',
//                 'return_format' => 'array',
//                 'show_in_graphql' => 1,
//             ),
//             array(
//                 'key' => 'field_header_phone',
//                 'label' => 'Phone Number',
//                 'name' => 'header_phone',
//                 'type' => 'text',
//                 'show_in_graphql' => 1,
//             ),
//             array(
//                 'key' => 'field_header_email',
//                 'label' => 'Email Address',
//                 'name' => 'header_email',
//                 'type' => 'email',
//                 'show_in_graphql' => 1,
//             )
//         ),
//         'location' => array(
//             array(
//                 array(
//                     'param' => 'options_page',
//                     'operator' => '==',
//                     'value' => 'global-content'
//                 )
//             )
//         ),
//         'show_in_graphql' => 1,
//         'graphql_field_name' => 'headerContent'
//     ));

//     // Footer Content
//     acf_add_local_field_group(array(
//         'key' => 'group_footer_content',
//         'title' => 'Footer Content',
//         'fields' => array(
//             array(
//                 'key' => 'field_footer_logo',
//                 'label' => 'Footer Logo',
//                 'name' => 'footer_logo',
//                 'type' => 'image',
//                 'return_format' => 'array',
//                 'show_in_graphql' => 1,
//             ),
//             array(
//                 'key' => 'field_footer_text',
//                 'label' => 'Footer Text',
//                 'name' => 'footer_text',
//                 'type' => 'wysiwyg',
//                 'show_in_graphql' => 1,
//             ),
//             array(
//                 'key' => 'field_footer_copyright',
//                 'label' => 'Copyright Text',
//                 'name' => 'footer_copyright',
//                 'type' => 'text',
//                 'default_value' => '© ' . date('Y') . ' All rights reserved.',
//                 'show_in_graphql' => 1,
//             ),
//             array(
//                 'key' => 'field_footer_social_links',
//                 'label' => 'Social Links',
//                 'name' => 'footer_social_links',
//                 'type' => 'repeater',
//                 'show_in_graphql' => 1,
//                 'sub_fields' => array(
//                     array(
//                         'key' => 'field_social_platform',
//                         'label' => 'Platform',
//                         'name' => 'platform',
//                         'type' => 'select',
//                         'choices' => array(
//                             'facebook' => 'Facebook',
//                             'twitter' => 'Twitter',
//                             'instagram' => 'Instagram',
//                             'linkedin' => 'LinkedIn',
//                             'youtube' => 'YouTube'
//                         ),
//                         'show_in_graphql' => 1,
//                     ),
//                     array(
//                         'key' => 'field_social_url',
//                         'label' => 'URL',
//                         'name' => 'url',
//                         'type' => 'url',
//                         'show_in_graphql' => 1,
//                     )
//                 )
//             )
//         ),
//         'location' => array(
//             array(
//                 array(
//                     'param' => 'options_page',
//                     'operator' => '==',
//                     'value' => 'global-content'
//                 )
//             )
//         ),
//         'show_in_graphql' => 1,
//         'graphql_field_name' => 'footerContent'
//     ));
// }

/**
 * UNIFIED SERVICE DETAIL FIELD GROUP
 * Handles all 7 service types with conditional logic
 */
add_action('acf/init', 'create_unified_service_template');
function create_unified_service_template() {
    
    // Main Service Detail Field Group
    acf_add_local_field_group(array(
        'key' => 'group_unified_service_detail',
        'title' => 'Service Detail Content',
        'fields' => array(
            
            // SERVICE TYPE SELECTOR
            array(
                'key' => 'field_service_type',
                'label' => 'Service Type',
                'name' => 'service_type',
                'type' => 'select',
                'instructions' => 'Select the type of service this page represents',
                'required' => 1,
                'choices' => array(
                    'ecommerce' => 'eCommerce Website Development',
                    'b2b-lead-generation' => 'B2B Lead Generation',
                    'software-development' => 'Software Development',
                    'booking-systems' => 'Booking Systems',
                    'digital-marketing' => 'Digital Marketing',
                    'outsourced-cmo' => 'Outsourced CMO',
                    'ai' => 'AI Content & Solutions',
                ),
                'default_value' => 'ecommerce',
                'allow_null' => 0,
                'return_format' => 'value',
                'show_in_graphql' => 1,
                'graphql_field_name' => 'serviceType',
            ),
            
            // UNIFIED SERVICE CONTENT GROUP
            array(
                'key' => 'field_service_detail_content',
                'label' => 'Service Detail Content',
                'name' => 'service_detail_content',
                'type' => 'group',
                'layout' => 'block',
                'show_in_graphql' => 1,
                'graphql_field_name' => 'serviceDetailContent',
                'sub_fields' => array(
                    
                    // HERO SECTION
                    array(
                        'key' => 'field_service_hero',
                        'label' => 'Hero Section',
                        'name' => 'hero_section',
                        'type' => 'group',
                        'layout' => 'block',
                        'show_in_graphql' => 1,
                        'graphql_field_name' => 'heroSection',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_service_hero_title',
                                'label' => 'Service Title',
                                'name' => 'title',
                                'type' => 'text',
                                'instructions' => 'Main service headline',
                                'show_in_graphql' => 1,
                            ),
                            array(
                                'key' => 'field_service_hero_description',
                                'label' => 'Service Description',
                                'name' => 'description',
                                'type' => 'textarea',
                                'instructions' => 'Brief description of the service',
                                'show_in_graphql' => 1,
                            ),
                            array(
                                'key' => 'field_service_hero_icon',
                                'label' => 'Service Icon',
                                'name' => 'icon',
                                'type' => 'image',
                                'instructions' => 'Icon or illustration representing this service',
                                'return_format' => 'array',
                                'show_in_graphql' => 1,
                            ),
                        ),
                    ),
                    
                    // KEY STATISTICS
                    array(
                        'key' => 'field_service_statistics',
                        'label' => 'Key Statistics',
                        'name' => 'statistics',
                        'type' => 'repeater',
                        'instructions' => 'Add key metrics and statistics for this service',
                        'min' => 0,
                        'max' => 4,
                        'layout' => 'table',
                        'button_label' => 'Add Statistic',
                        'show_in_graphql' => 1,
                        'graphql_field_name' => 'statistics',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_stat_number',
                                'label' => 'Number',
                                'name' => 'number',
                                'type' => 'text',
                                'instructions' => 'e.g., "250%", "£5M", "50+"',
                                'show_in_graphql' => 1,
                            ),
                            array(
                                'key' => 'field_stat_label',
                                'label' => 'Label',
                                'name' => 'label',
                                'type' => 'text',
                                'instructions' => 'e.g., "Increase in ROI", "Revenue Generated"',
                                'show_in_graphql' => 1,
                            ),
                            array(
                                'key' => 'field_stat_metric_type',
                                'label' => 'Metric Type',
                                'name' => 'metric_type',
                                'type' => 'text',
                                'instructions' => 'e.g., "ROI", "Revenue", "Clients"',
                                'show_in_graphql' => 1,
                            ),
                        ),
                    ),
                    
                    // SERVICE PROCESS
                    array(
                        'key' => 'field_service_process',
                        'label' => 'Our Process',
                        'name' => 'process',
                        'type' => 'group',
                        'layout' => 'block',
                        'show_in_graphql' => 1,
                        'graphql_field_name' => 'process',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_process_heading',
                                'label' => 'Process Section Heading',
                                'name' => 'heading',
                                'type' => 'text',
                                'default_value' => 'How We Work',
                                'show_in_graphql' => 1,
                            ),
                            array(
                                'key' => 'field_process_steps',
                                'label' => 'Process Steps',
                                'name' => 'steps',
                                'type' => 'repeater',
                                'min' => 1,
                                'max' => 6,
                                'layout' => 'block',
                                'button_label' => 'Add Process Step',
                                'show_in_graphql' => 1,
                                'sub_fields' => array(
                                    array(
                                        'key' => 'field_step_title',
                                        'label' => 'Step Title',
                                        'name' => 'title',
                                        'type' => 'text',
                                        'show_in_graphql' => 1,
                                    ),
                                    array(
                                        'key' => 'field_step_description',
                                        'label' => 'Step Description',
                                        'name' => 'description',
                                        'type' => 'textarea',
                                        'show_in_graphql' => 1,
                                    ),
                                    array(
                                        'key' => 'field_step_image',
                                        'label' => 'Step Image',
                                        'name' => 'image',
                                        'type' => 'image',
                                        'return_format' => 'array',
                                        'show_in_graphql' => 1,
                                    ),
                                ),
                            ),
                        ),
                    ),
                    
                    // CASE STUDIES SECTION
                    array(
                        'key' => 'field_service_case_studies',
                        'label' => 'Featured Case Studies',
                        'name' => 'case_studies',
                        'type' => 'group',
                        'layout' => 'block',
                        'show_in_graphql' => 1,
                        'graphql_field_name' => 'caseStudies',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_case_studies_heading',
                                'label' => 'Case Studies Heading',
                                'name' => 'heading',
                                'type' => 'text',
                                'default_value' => 'Success Stories',
                                'show_in_graphql' => 1,
                            ),
                            array(
                                'key' => 'field_featured_case_studies',
                                'label' => 'Select Case Studies',
                                'name' => 'featured_studies',
                                'type' => 'post_object',
                                'instructions' => 'Choose case studies to feature for this service',
                                'post_type' => array('case_studies'),
                                'multiple' => 1,
                                'max' => 3,
                                'return_format' => 'object',
                                'show_in_graphql' => 1,
                            ),
                        ),
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'template-service-detail.php',
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
        'description' => 'Unified service template that handles all 7 service types with conditional fields',
        'show_in_graphql' => 1,
        'graphql_field_name' => 'serviceDetailFields',
    ));
}

?>