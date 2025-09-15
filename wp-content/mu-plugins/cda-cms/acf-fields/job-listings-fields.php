<?php
/**
 * Job Listings ACF Field Groups
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Register ACF fields with higher priority
add_action('acf/init', 'cda_add_job_listings_fields', 5);
add_action('init', 'cda_add_job_listings_fields', 20);

function cda_add_job_listings_fields() {
    
    // Debug: Log that function is called
    error_log('CDA ACF: cda_add_job_listings_fields() called');
    
    // Check if ACF is available
    if (!function_exists('acf_add_local_field_group')) {
        error_log('CDA ACF: acf_add_local_field_group function not found');
        return;
    }
    
    error_log('CDA ACF: About to register job listings field group');
    
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
