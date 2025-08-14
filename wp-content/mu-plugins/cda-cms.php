<?php
/**
 * Plugin Name: CDA Headless CMS helpers
 * Description: CPTs, GraphQL tweaks, ACF exposure
 */

/* ---------- 1. CPTs ---------- */
add_action('init', function () {
  $cpts = ['services','team','case_studies','jobs','news','sectors','technologies','policies'];
  foreach ($cpts as $cpt) {
    register_post_type($cpt, [
      'public'       => true,
      'show_in_rest' => true,
      'show_in_graphql' => true,
      'graphql_single_name'  => ucfirst($cpt),
      'graphql_plural_name'  => ucfirst($cpt).'s',
      'supports'     => ['title','editor','thumbnail','excerpt','custom-fields'],
      'rewrite'      => ['slug' => str_replace('_','-',$cpt)],
      'labels'       => ['name' => ucwords(str_replace('_',' ',$cpt))],
    ]);
  }
});

/* ---------- 2. Allow SVG ---------- */
add_filter('upload_mimes', function ($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
});

/* ---------- 3. GraphQL tweaks ---------- */
add_filter('acf/settings/graphql_enabled', '__return_true');