<?php
/*
Template Name: Terms & Conditions Page
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

get_header(); ?>

<div class="terms-conditions-page">
    <?php while (have_posts()) : the_post(); ?>
        
        <div class="page-content">
            <h1><?php the_title(); ?></h1>
            
            <?php 
            // For headless setup, this template mainly serves as a targeting mechanism for ACF
            // The actual rendering will be handled by the frontend (Next.js)
            
            if (function_exists('get_field')) {
                $terms_conditions_content = get_field('terms_conditions_content');
                
                if ($terms_conditions_content) {
                    // Display a simple preview for backend users
                    echo '<div class="acf-preview">';
                    echo '<p><strong>ACF Data Available:</strong> This page has Terms & Conditions fields configured.</p>';
                    echo '<p><em>Note: Full content rendering handled by headless frontend.</em></p>';
                    echo '</div>';
                }
            }
            
            the_content(); 
            ?>
        </div>
        
    <?php endwhile; ?>
</div>

<?php get_footer(); ?>