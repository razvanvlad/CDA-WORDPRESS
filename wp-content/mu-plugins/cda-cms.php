<?php
/**
 * Plugin Name: CDA Headless CMS helpers
 * Description: CPTs + enable ACF flexible rows in GraphQL + enable introspection
 */

/* ---------- 1. Register CPTs with GraphQL ---------- */
add_action('init', function () {
    $cpts = ['services','team','case_studies','jobs','news','sectors','technologies','policies'];
    foreach ($cpts as $cpt) {
        $single = ucfirst($cpt);
        $plural = $single.'s';
        register_post_type($cpt, [
            'public'            => true,
            'show_in_rest'      => true,
            'show_in_graphql'   => true,
            'graphql_single_name'  => $single,
            'graphql_plural_name'  => $plural,
            'supports'          => ['title','editor','thumbnail','excerpt','custom-fields'],
            'rewrite'           => ['slug' => str_replace('_','-',$cpt)],
            'labels'            => ['name' => ucwords(str_replace('_',' ',$cpt))],
        ]);
    }
});

/* ---------- 2.  Force ACF to expose flexible layouts ---------- */
add_filter('acf/settings/graphql_enabled', '__return_true');

/* ---------- 3.  Enable GraphQL introspection (for dev only) ---------- */
add_filter('graphql_debug_enabled', '__return_true');
add_filter('graphql_public_introspection_enabled', '__return_true');

/* ---------- 4.  Flush rewrite rules once ---------- */
register_activation_hook(__FILE__, 'flush_rewrite_rules');