<?php
/**
 * Team Members Post Type
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// ============================================================================
// TEAM MEMBERS POST TYPE
// ============================================================================

add_action('init', 'cda_register_team_members_post_type');
function cda_register_team_members_post_type() {
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
