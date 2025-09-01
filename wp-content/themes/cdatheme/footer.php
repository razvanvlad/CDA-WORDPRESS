<!-- wp-content/themes/cdatheme/footer.php -->
<footer id="colophon" class="site-footer bg-gray-800 text-white py-8">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="mb-4 md:mb-0">
                <h3 class="text-2xl font-bold">CDA</h3>
                <p class="text-gray-400">© <?php echo date('Y'); ?> All rights reserved.</p>
            </div>
            
            <!-- Footer Navigation Menu -->
            <div class="flex space-x-6">
                <?php
                if (has_nav_menu('footer')) {
                    wp_nav_menu(array(
                        'theme_location' => 'footer',
                        'menu_class' => 'flex space-x-6',
                        'container' => false,
                        'items_wrap' => '%3$s',
                        'fallback_cb' => false,
                    ));
                }
                ?>
            </div>
        </div>
        
        <div class="mt-8 flex justify-center space-x-6">
            <!-- Social media links would go here -->
            <a href="#" class="text-gray-400 hover:text-white transition-colors">Facebook</a>
            <a href="#" class="text-gray-400 hover:text-white transition-colors">Twitter</a>
            <a href="#" class="text-gray-400 hover:text-white transition-colors">Instagram</a>
            <a href="#" class="text-gray-400 hover:text-white transition-colors">LinkedIn</a>
            <a href="#" class="text-gray-400 hover:text-white transition-colors">YouTube</a>
        </div>
        
        <div class="mt-8 text-center text-gray-400">
            <p>Contact Us • 0203 780 0808</p>
        </div>
    </div>
    <?php wp_footer(); ?>
</footer>
</body>
</html>