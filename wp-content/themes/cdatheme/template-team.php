<?php
/**
 * Template Name: Team
 * Description: Team page template with flexible content sections
 */
get_header();
?>

<main>
    <div class="max-w-6xl mx-auto px-4 py-8">
        
        <!-- Content Page Header -->
        <?php if (have_rows('content_page_header')) : ?>
            <?php while (have_rows('content_page_header')) : the_row(); ?>
                <section class="py-20">
                    <div class="flex flex-col lg:flex-row items-center gap-12">
                        <div class="lg:w-1/2">
                            <h1 class="text-4xl font-bold text-gray-800 mb-4">
                                <?php the_sub_field('title'); ?>
                            </h1>
                            <div class="text-gray-600 mb-8 prose">
                                <?php the_sub_field('text'); ?>
                            </div>
                            <?php if (get_sub_field('cta')) : ?>
                                <a href="<?php echo get_sub_field('cta')['url']; ?>" 
                                   class="px-6 py-3 bg-black text-white rounded hover:bg-gray-800 transition-colors">
                                    <?php echo get_sub_field('cta')['title']; ?>
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="lg:w-1/2">
                            <?php if (get_sub_field('image_with_frame')) : ?>
                                <div class="relative">
                                    <img 
                                        src="/images/Photo-Frame.png" 
                                        alt="Frame" 
                                        class="absolute inset-0 z-10 w-full h-full object-contain"
                                    />
                                    <img 
                                        src="<?php echo get_sub_field('image_with_frame')['url']; ?>" 
                                        alt="<?php echo get_sub_field('image_with_frame')['alt']; ?>"
                                        class="w-full h-auto rounded-lg"
                                    />
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
            <?php endwhile; ?>
        <?php endif; ?>

        <!-- Meet the Founder Section -->
        <?php if (have_rows('founder_section')) : ?>
            <?php while (have_rows('founder_section')) : the_row(); ?>
                <section class="py-16">
                    <div class="max-w-6xl mx-auto">
                        <div class="flex flex-col lg:flex-row items-center gap-12">
                            <div class="lg:w-1/2">
                                <?php if (get_sub_field('founder_image')) : ?>
                                    <div class="relative">
                                        <img 
                                            src="<?php echo get_sub_field('founder_image')['url']; ?>" 
                                            alt="<?php echo get_sub_field('founder_image')['alt']; ?>"
                                            class="w-full max-w-md mx-auto rounded-lg shadow-lg"
                                        />
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="lg:w-1/2">
                                <h2 class="text-3xl font-bold text-gray-800 mb-6">
                                    <?php the_sub_field('section_title'); ?>
                                </h2>
                                <div class="prose text-gray-600 mb-6">
                                    <?php the_sub_field('section_text'); ?>
                                </div>
                                <?php if (get_sub_field('cta')) : ?>
                                    <a href="<?php echo get_sub_field('cta')['url']; ?>" 
                                       class="px-6 py-3 bg-black text-white rounded hover:bg-gray-800 transition-colors">
                                        <?php echo get_sub_field('cta')['title']; ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </section>
            <?php endwhile; ?>
        <?php endif; ?>

        <!-- Meet the Team Section - Dynamic Team Profiles -->
        <section class="py-16 bg-gray-50">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-3xl font-bold text-gray-800 mb-12 text-center">
                    Meet the Team
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php
                    $team_query = new WP_Query(array(
                        'post_type' => 'team',
                        'posts_per_page' => -1,
                        'orderby' => 'menu_order',
                        'order' => 'ASC',
                        'post_status' => 'publish'
                    ));
                    ?>
                    
                    <?php if ($team_query->have_posts()) : ?>
                        <?php while ($team_query->have_posts()) : $team_query->the_post(); ?>
                            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="aspect-square overflow-hidden">
                                        <?php the_post_thumbnail('medium', array('class' => 'w-full h-full object-cover')); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="p-6">
                                    <h3 class="font-semibold text-gray-800 text-xl mb-2">
                                        <?php the_title(); ?>
                                    </h3>
                                    
                                    <?php if (get_field('job_title')) : ?>
                                        <p class="text-gray-600 font-medium mb-3">
                                            <?php the_field('job_title'); ?>
                                        </p>
                                    <?php endif; ?>
                                    
                                    <?php if (get_field('bio')) : ?>
                                        <div class="text-gray-600 text-sm mb-4">
                                            <?php the_field('bio'); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Social Links -->
                                    <div class="flex space-x-3">
                                        <?php if (get_field('linkedin_url')) : ?>
                                            <a href="<?php the_field('linkedin_url'); ?>" 
                                               class="text-blue-600 hover:text-blue-800 transition-colors"
                                               target="_blank" rel="noopener">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                                </svg>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if (get_field('twitter_url')) : ?>
                                            <a href="<?php the_field('twitter_url'); ?>" 
                                               class="text-blue-400 hover:text-blue-600 transition-colors"
                                               target="_blank" rel="noopener">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                                </svg>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if (get_field('email')) : ?>
                                            <a href="mailto:<?php the_field('email'); ?>" 
                                               class="text-gray-600 hover:text-gray-800 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                </svg>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>
                    <?php else : ?>
                        <div class="col-span-full text-center py-12">
                            <p class="text-gray-600">No team members found.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Join Our Team Section -->
        <?php if (have_rows('join_team_section')) : ?>
            <?php while (have_rows('join_team_section')) : the_row(); ?>
                <section class="py-16 bg-black text-white">
                    <div class="max-w-4xl mx-auto text-center">
                        <h2 class="text-3xl font-bold mb-6">
                            <?php the_sub_field('section_title'); ?>
                        </h2>
                        <div class="prose prose-lg prose-invert mx-auto mb-8">
                            <?php the_sub_field('section_text'); ?>
                        </div>
                        <?php if (get_sub_field('cta')) : ?>
                            <a href="<?php echo get_sub_field('cta')['url']; ?>" 
                               class="px-8 py-4 bg-white text-black rounded hover:bg-gray-200 transition-colors font-semibold">
                                <?php echo get_sub_field('cta')['title']; ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </section>
            <?php endwhile; ?>
        <?php endif; ?>

        <!-- Video Section -->
        <?php if (have_rows('video_section')) : ?>
            <?php while (have_rows('video_section')) : the_row(); ?>
                <section class="py-16">
                    <div class="max-w-6xl mx-auto">
                        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">
                            <?php the_sub_field('title'); ?>
                        </h2>
                        <div class="aspect-video bg-black rounded-lg overflow-hidden shadow-lg">
                            <?php if (get_sub_field('video_url')) : ?>
                                <iframe 
                                    src="<?php echo esc_url(get_sub_field('video_url')); ?>" 
                                    width="100%" 
                                    height="100%" 
                                    frameborder="0" 
                                    allowfullscreen
                                    class="w-full h-full"
                                ></iframe>
                            <?php elseif (get_sub_field('video_embed')) : ?>
                                <div class="w-full h-full flex items-center justify-center">
                                    <?php the_sub_field('video_embed'); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
            <?php endwhile; ?>
        <?php endif; ?>

    </div>
</main>

<?php get_footer(); ?>