<?php
/**
 * WordPress Admin Cleanup for Headless Setup
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// ============================================================================
// WORDPRESS CLEANUP - Remove Gutenberg and other page builders
// ============================================================================

add_action('init', 'cda_remove_gutenberg');
function cda_remove_gutenberg() {
    add_filter('use_block_editor_for_post_type', '__return_false', 100);
    remove_post_type_support('page', 'editor');
    remove_post_type_support('post', 'editor');
}
