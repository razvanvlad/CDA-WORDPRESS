<?php
/**
 * Plugin Name: CDA Headless CMS helpers
 * Description: CPTs, GraphQL tweaks, ACF exposure
 */

/* ---------- 1. CPTs ---------- */


/* ---------- 3. GraphQL tweaks ---------- */
add_filter('acf/settings/graphql_enabled', '__return_true');

