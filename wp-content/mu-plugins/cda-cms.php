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

// Enable ACF GraphQL - This is the most important line
add_filter('acf/settings/graphql_enabled', '__return_true');

// Make sure ACF fields are available in GraphQL
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

// Create Custom Post Types (your existing code)
add_action('init', 'cda_create_custom_post_types');
function cda_create_custom_post_types() {
    // Your existing custom post type code...
}

// Create Taxonomies (your existing code)
add_action('init', 'cda_create_taxonomies');
function cda_create_taxonomies() {
    // Your existing taxonomy code...
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

// SEO SETTINGS
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

// ACF Field Groups
add_action('acf/init', 'cda_add_acf_field_groups');
function cda_add_acf_field_groups() {
    
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
                        'type' => 'wysiwyg',
                        'required' => 1,
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_header_subtitle',
                        'label' => 'Subtitle',
                        'name' => 'subtitle',
                        'type' => 'wysiwyg',
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
                        'type' => 'wysiwyg',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_who_we_are_subtitle',
                        'label' => 'Subtitle',
                        'name' => 'subtitle',
                        'type' => 'wysiwyg',
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
                        'type' => 'wysiwyg',
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
                        'type' => 'wysiwyg',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_platforms_subtitle',
                        'label' => 'Subtitle',
                        'name' => 'subtitle',
                        'type' => 'wysiwyg',
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
                        'type' => 'wysiwyg',
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
                                'type' => 'wysiwyg',
                                'show_in_graphql' => 1,
                            ),
                            array(
                                'key' => 'field_value_description',
                                'label' => 'Description',
                                'name' => 'description',
                                'type' => 'wysiwyg',
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
                        'type' => 'wysiwyg',
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
                        'type' => 'wysiwyg',
                        'show_in_graphql' => 1,
                    ),
                    array(
                        'key' => 'field_newsletter_subtitle',
                        'label' => 'Subtitle',
                        'name' => 'subtitle',
                        'type' => 'wysiwyg',
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
?>