        <?php
        // See header.php — a matching Theme Builder footer replaces the
        // theme's own rather than stacking with it.
        if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) {
            get_template_part( 'template-parts/footer/footer' );
        }
        ?>
        <?php wp_footer(); ?>
    </body>
</html>
