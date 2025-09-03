<!-- wp-content/themes/cdatheme/template-about-us.php -->
<?php
/**
 * Template Name: About Us
 */
get_header();
?>

<main>
    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Content Page Header -->
        <?php if (have_rows('content_page_header')) : ?>
            <section class="py-20">
                <div class="flex flex-col lg:flex-row items-center gap-12">
                    <div className="lg:w-1/2">
                        <h1 className="text-4xl font-bold text-gray-800 mb-4">
                            <?php the_sub_field('title'); ?>
                        </h1>
                        <p className="text-gray-600 mb-8">
                            <?php the_sub_field('text'); ?>
                        </p>
                        <?php if (get_sub_field('cta')) : ?>
                            <a href="<?php echo get_sub_field('cta')['url']; ?>" 
                               class="px-6 py-3 bg-black text-white rounded hover:bg-gray-800 transition-colors">
                                <?php echo get_sub_field('cta')['title']; ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div className="lg:w-1/2">
                        <img 
                            src="/images/Photo-Frame.png" 
                            alt="Frame" 
                            className="frame-image"
                        />
                        <img 
                            src="<?php echo get_sub_field('image_with_frame')['url']; ?>" 
                            alt="<?php echo get_sub_field('image_with_frame')['alt']; ?>"
                            className="content-image"
                        />
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- Who We Are Section -->
        <?php if (have_rows('who_we_are_section')) : ?>
            <section class="py-16">
                <div class="max-w-6xl mx-auto">
                    <div class="flex flex-col lg:flex-row items-center gap-12">
                        <div className="lg:w-1/2 relative">
                            <div className="image-container">
                                <img 
                                    src="/images/Photo-Frame.png" 
                                    alt="Frame" 
                                    className="frame-image"
                                />
                                <img 
                                    src="<?php echo get_sub_field('image_with_frame')['url']; ?>" 
                                    alt="<?php echo get_sub_field('image_with_frame')['alt']; ?>"
                                    className="content-image"
                                />
                            </div>
                        </div>
                        <div className="lg:w-1/2">
                            <h2 className="text-2xl font-bold text-gray-800 mb-4">
                                <?php the_sub_field('section_title'); ?>
                            </h2>
                            <p className="text-gray-600 mb-8">
                                <?php the_sub_field('section_text'); ?>
                            </p>
                            <?php if (get_sub_field('cta')) : ?>
                                <a href="<?php echo get_sub_field('cta')['url']; ?>" 
                                   class="text-blue-600 underline hover:text-blue-800 transition-colors inline-flex items-center">
                                    <?php echo get_sub_field('cta')['title']; ?>
                                    <svg className="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- Why CDA Section -->
        <?php if (have_rows('why_cda_section')) : ?>
            <section class="py-16 bg-gray-50">
                <div class="max-w-6xl mx-auto">
                    <h2 className="text-3xl font-bold text-gray-800 mb-8">
                        Why CDA
                    </h2>
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php while (have_rows('why_cda_section')) : the_row(); ?>
                            <div className="bg-white p-6 rounded-lg shadow-md">
                                <div className="mb-4">
                                    <?php if (get_sub_field('icon')) : ?>
                                        <img 
                                            src="<?php echo get_sub_field('icon')['url']; ?>" 
                                            alt="<?php echo get_sub_field('icon')['alt']; ?>"
                                            className="w-12 h-12"
                                        />
                                    <?php endif; ?>
                                </div>
                                <h3 className="font-semibold text-gray-800 mb-2">
                                    <?php the_sub_field('title'); ?>
                                </h3>
                                <p className="text-gray-600">
                                    <?php the_sub_field('description'); ?>
                                </p>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- Services Section -->
        <?php if (have_rows('services_section')) : ?>
            <section class="py-16">
                <div class="max-w-4xl mx-auto">
                    <h2 className="text-3xl font-bold text-gray-800 mb-8">
                        Our Services
                    </h2>
                    <div className="space-y-4">
                        <?php while (have_rows('services_accordion')) : the_row(); ?>
                            <div key={get_sub_field('title')} className="border-b border-gray-200 py-4">
                                <div className="flex justify-between items-center cursor-pointer">
                                    <h3 className="font-semibold text-gray-800">{get_sub_field('title')}</h3>
                                    <span className="text-gray-500">+</span>
                                </div>
                                <div 
                                    className="mt-2 text-gray-600 leading-relaxed"
                                    dangerouslySetInnerHTML={{ __html: get_sub_field('description') }}
                                />
                                {get_sub_field('link') && (
                                    <div className="mt-3">
                                        <button className="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100 transition-colors">
                                            {get_sub_field('link')['title']}
                                        </button>
                                    </div>
                                )}
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- Culture Section -->
        <?php if (have_rows('culture_section')) : ?>
            <section class="py-16 bg-gray-50">
                <div class="max-w-6xl mx-auto">
                    <h2 className="text-3xl font-bold text-gray-800 mb-8">
                        Culture
                    </h2>
                    <div className="flex flex-wrap gap-8 justify-center">
                        <?php while (have_rows('gallery')) : the_row(); ?>
                            <img 
                                src="<?php echo get_sub_field('url'); ?>" 
                                alt="<?php echo get_sub_field('alt'); ?>"
                                className="w-32 h-32 object-contain"
                            />
                        <?php endwhile; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- Approach Section -->
        <?php if (have_rows('approach_section')) : ?>
            <section class="py-16">
                <div class="max-w-6xl mx-auto">
                    <div className="flex flex-col lg:flex-row items-center gap-12">
                        <div className="lg:w-1/2">
                            <h2 className="text-3xl font-bold text-gray-800 mb-6">
                                <?php the_sub_field('title'); ?>
                            </h2>
                            <p className="text-gray-600 mb-8">
                                <?php the_sub_field('text'); ?>
                            </p>
                        </div>
                        <div className="lg:w-1/2">
                            <img 
                                src="<?php echo get_sub_field('image')['url']; ?>" 
                                alt="<?php echo get_sub_field('image')['alt']; ?>"
                                className="w-full h-auto rounded-lg"
                            />
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- Stats Section -->
        <?php if (have_rows('stats_section')) : ?>
            <section class="py-16 bg-gray-50">
                <div class="max-w-6xl mx-auto">
                    <h2 className="text-3xl font-bold text-gray-800 mb-8">
                        Stats
                    </h2>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div className="text-center">
                            <div className="text-3xl font-bold text-gray-800">
                                <?php the_sub_field('number'); ?>
                            </div>
                            <div className="text-gray-600">
                                <?php the_sub_field('label'); ?>
                            </div>
                        </div>
                        <div className="text-center">
                            <img 
                                src="<?php echo get_sub_field('image')['url']; ?>" 
                                alt="<?php echo get_sub_field('image')['alt']; ?>"
                                className="w-full h-auto rounded-lg"
                            />
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- Video Section -->
        <?php if (have_rows('video_section')) : ?>
            <section class="py-16">
                <div class="max-w-6xl mx-auto">
                    <h2 className="text-3xl font-bold text-gray-800 mb-8">
                        <?php the_sub_field('title'); ?>
                    </h2>
                    <div className="aspect-video bg-black rounded-lg overflow-hidden">
                        <?php if (get_sub_field('url')) : ?>
                            <iframe 
                                src="<?php echo esc_url(get_sub_field('url')); ?>" 
                                width="100%" 
                                height="100%" 
                                frameborder="0" 
                                allowfullscreen
                                className="w-full h-full"
                            ></iframe>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- Leadership Team Section -->
        <?php if (have_rows('leadership_section')) : ?>
            <section class="py-16 bg-gray-50">
                <div class="max-w-6xl mx-auto">
                    <h2 className="text-3xl font-bold text-gray-800 mb-8">
                        Leadership Team
                    </h2>
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php while (have_rows('leadership_section')) : the_row(); ?>
                            <div className="bg-white p-6 rounded-lg shadow-md">
                                <div className="mb-4">
                                    <img 
                                        src="<?php echo get_sub_field('image')['url']; ?>" 
                                        alt="<?php echo get_sub_field('image')['alt']; ?>"
                                        className="w-32 h-32 object-cover rounded-full mx-auto"
                                    />
                                </div>
                                <h3 className="font-semibold text-gray-800 text-center mb-1">
                                    <?php the_sub_field('name'); ?>
                                </h3>
                                <p className="text-gray-600 text-center mb-4">
                                    <?php the_sub_field('position'); ?>
                                </p>
                                <div className="text-gray-600">
                                    <?php the_sub_field('bio'); ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- Showreel Section -->
        <?php if (have_rows('showreel_section')) : ?>
            <section class="py-16">
                <div class="max-w-6xl mx-auto">
                    <h2 className="text-3xl font-bold text-gray-800 mb-8">
                        Our Work Video & Logos
                    </h2>
                    <div className="aspect-video bg-black rounded-lg overflow-hidden mb-8">
                        <?php if (get_sub_field('video')) : ?>
                            <iframe 
                                src="<?php echo esc_url(get_sub_field('video')); ?>" 
                                width="100%" 
                                height="100%" 
                                frameborder="0" 
                                allowfullscreen
                                className="w-full h-full"
                            ></iframe>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Client Logos -->
                    <div className="flex flex-wrap gap-8 justify-center">
                        <?php while (have_rows('logos')) : the_row(); ?>
                            <img 
                                src="<?php echo get_sub_field('image')['url']; ?>" 
                                alt="<?php echo get_sub_field('image')['alt']; ?>"
                                className="w-32 h-12 object-contain"
                            />
                        <?php endwhile; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>