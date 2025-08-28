<?php
/**
 * The main template file
 */
get_header();
?>

<main id="primary" class="site-main">
    <?php
    if (have_posts()) :
        while (have_posts()) : the_post();
            the_content();
        endwhile;
    endif;
    ?>
    <!-- Content will be rendered by React frontend -->
    <div id="app"></div>
</main>

<?php
get_footer();
?>