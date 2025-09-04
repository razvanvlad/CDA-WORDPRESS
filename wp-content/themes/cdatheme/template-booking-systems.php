<?php
/*
Template Name: Booking Systems Page
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

get_header(); ?>

<div class="booking-systems-page">
    <?php while (have_posts()) : the_post(); ?>
        
        <div class="page-content">
            <h1><?php the_title(); ?></h1>
            
            <?php 
            // For headless setup, this template mainly serves as a targeting mechanism for ACF
            // The actual rendering will be handled by the frontend (Next.js)
            
            if (function_exists('get_field')) {
                $booking_content = get_field('booking_systems_content');
                
                if ($booking_content) {
                    // Display a simple preview for backend users
                    echo '<div class="acf-preview">';
                    echo '<p><strong>ACF Data Available:</strong> This page has Booking Systems fields configured.</p>';
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