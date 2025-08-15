<?php
/**
 * Plugin Name: CDA Headless CMS helpers
 * Description: CPTs + correct ACF flexible rows + GraphQL tweaks
 */

/* ---------- 1.  Register CPTs (use hyphens, no underscores) ---------- */
add_action('init', function () {
    $cpts = [
        'services',
        'team',
        'case-studies',   // ← hyphen instead of underscore
        'jobs',
        'news',
        'sectors',
        'technologies',
        'policies'
    ];
    foreach ($cpts as $cpt) {
        $single = str_replace('-', '', ucwords($cpt, '-')); // CaseStudies
        $plural = $single.'s';
        register_post_type(str_replace('-', '_', $cpt), [ // internal name still uses _
            'public'       => true,
            'show_in_rest' => true,
            'show_in_graphql' => true,
            'graphql_single_name'  => $single,
            'graphql_plural_name'  => $plural,
            'supports'     => ['title','editor','thumbnail','excerpt','custom-fields'],
            'rewrite'      => ['slug' => $cpt],
            'labels'       => ['name' => ucwords(str_replace('-', ' ', $cpt))],
        ]);
    }
});

/* ---------- 2.  Force ACF flexible layouts ---------- */
add_filter('acf/settings/graphql_enabled', '__return_true');

/* ---------- 3.  Expose flexible field with correct name ---------- */
add_action('graphql_register_types', function () {
    register_graphql_field('Page', 'rows', [
        'type'    => ['list_of' => 'AcfFlexibleContentRow'],
        'resolve' => fn($page) => get_field('rows', $page->databaseId),
    ]);
});

/* ---------- 4.  Enable GraphQL introspection for DEV ---------- */
add_filter('graphql_public_introspection_enabled', '__return_true');
add_filter('graphql_debug_enabled', '__return_true');

/* ---------- 5.  Flush rules once ---------- */
register_activation_hook(__FILE__, 'flush_rewrite_rules');