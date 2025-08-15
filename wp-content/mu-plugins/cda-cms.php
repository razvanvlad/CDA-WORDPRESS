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

add_action('graphql_register_types', function () {
  // Expose flexible rows under Page
  register_graphql_field('Page', 'rows', [
    'type'        => ['list_of' => 'AcfFlexibleContentRow'],
    'description' => 'Flexible ACF rows',
    'resolve'     => fn($page) => get_field('rows', $page->databaseId)
  ]);

  // Expose meta on CPTs
  foreach (['team','case_studies','services','jobs','news','sectors','technologies','policies'] as $cpt) {
    register_graphql_field(strtoupper($cpt[0]).substr($cpt,1), 'meta', [
      'type'    => 'String',
      'resolve' => fn($post) => get_fields($post->databaseId)
    ]);
  }
});