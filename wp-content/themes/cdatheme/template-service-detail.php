<?php
/*
Template Name: Service Detail Page
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

get_header(); ?>

<div class="service-detail-page">
    <?php while (have_posts()) : the_post(); ?>
        
        <div class="page-content">
            <h1><?php the_title(); ?></h1>
            
            <?php 
            // For headless setup, this template serves as a targeting mechanism for ACF
            // The actual rendering will be handled by the frontend (Next.js)
            
            if (function_exists('get_field')) {
                $service_type = get_field('service_type');
                $service_content = get_field('service_detail_content');
                
                if ($service_content || $service_type) {
                    // Display a simple preview for backend users
                    echo '<div class="acf-preview">';
                    echo '<p><strong>Service Type:</strong> ' . ($service_type ?: 'Not specified') . '</p>';
                    echo '<p><strong>ACF Data Available:</strong> This page has unified Service Detail fields configured.</p>';
                    
                    if ($service_content && isset($service_content['hero_section'])) {
                        echo '<p><strong>Hero Title:</strong> ' . ($service_content['hero_section']['title'] ?: 'Not set') . '</p>';
                    }
                    
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