<?php

/**
* Get started notice
*/

add_action( 'wp_ajax_creative_portfolio_lite_dismissed_notice_handler', 'creative_portfolio_lite_ajax_notice_handler' );

function creative_portfolio_lite_ajax_notice_handler() {
    if ( isset( $_POST['type'] ) ) {
        $type = sanitize_text_field( wp_unslash( $_POST['type'] ) );
        update_option( 'dismissed-' . $type, TRUE );
    }
}

function creative_portfolio_lite_deprecated_hook_admin_notice() {
    // Check if it's been dismissed...
    if ( ! get_option( 'dismissed-get_started', false ) ) {
        $current_screen = get_current_screen();

        // Check screen ID correctly
        if ( 
            $current_screen && 
            $current_screen->id !== 'appearance_page_creative-portfolio-lite-guide-page' &&
            $current_screen->id !== 'appearance_page_creativeportfoliolite-wizard'
        ) {
            $creative_portfolio_lite_comments_theme = wp_get_theme();
            ?>
            <div class="creative-portfolio-lite-notice-wrapper updated notice notice-get-started-class is-dismissible" data-notice="get_started">
                <div class="creative-portfolio-lite-notice">
                    <div class="creative-portfolio-lite-notice-content">
                        <div class="creative-portfolio-lite-notice-heading">
                            <h2>
                                <?php esc_html_e('Thanks For Installing ', 'creative-portfolio-lite'); ?>
                                <?php echo esc_html( $creative_portfolio_lite_comments_theme ); ?>
                                <?php esc_html_e('Theme', 'creative-portfolio-lite'); ?>
                            </h2>
                            <p>
                                <?php
                                /* translators: %s: theme name */
                                printf(
                                    esc_html__("%s is now installed and ready to use. We've provided some links to get you started.", 'creative-portfolio-lite'),
                                    $creative_portfolio_lite_comments_theme
                                );
                                ?>
                            </p>
                        </div>
                        <div class="diplay-flex-btn">
                            <a class="button button-primary" href="<?php echo esc_url( admin_url( 'themes.php?page=creative-portfolio-lite-guide-page' ) ); ?>">
                                <?php echo esc_html__('GET STARTED', 'creative-portfolio-lite'); ?>
                            </a>
                            <a class="button button-primary" target="_blank" href="<?php echo esc_url( CREATIVE_PORTFOLIO_LITE_BUY_NOW ); ?>">
                                <?php echo esc_html__('GO TO PREMIUM', 'creative-portfolio-lite'); ?>
                            </a>
                            <a class="button button-primary import" href="<?php echo esc_url( admin_url( 'themes.php?page=creativeportfoliolite-wizard' ) ); ?>">
                                <?php echo esc_html__('ONE CLICK DEMO IMPORTER', 'creative-portfolio-lite'); ?>
                            </a>
                        </div>
                    </div>
                    <div class="creative-portfolio-lite-notice-img">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/notification.png' ); ?>" alt="<?php esc_attr_e('logo', 'creative-portfolio-lite'); ?>">
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
add_action( 'admin_notices', 'creative_portfolio_lite_deprecated_hook_admin_notice' );

add_action( 'admin_menu', 'creative_portfolio_lite_getting_started' );
function creative_portfolio_lite_getting_started() {
	add_theme_page( esc_html__('Get Started', 'creative-portfolio-lite'), esc_html__('Get Started', 'creative-portfolio-lite'), 'edit_theme_options', 'creative-portfolio-lite-guide-page', 'creative_portfolio_lite_test_guide');
}

function creative_portfolio_lite_admin_enqueue_scripts() {
	wp_enqueue_style( 'creative-portfolio-lite-admin-style', esc_url( get_template_directory_uri() ).'/css/main.css' );
	wp_enqueue_script( 'creative-portfolio-lite-admin-script', get_template_directory_uri() . '/js/creative-portfolio-lite-admin-script.js', array( 'jquery' ), '', true );
    wp_localize_script( 'creative-portfolio-lite-admin-script', 'creative_portfolio_lite_ajax_object',
        array( 'ajax_url' => admin_url( 'admin-ajax.php' ) )
    );
}
add_action( 'admin_enqueue_scripts', 'creative_portfolio_lite_admin_enqueue_scripts' );

if ( ! defined( 'CREATIVE_PORTFOLIO_LITE_DOCS_FREE' ) ) {
define('CREATIVE_PORTFOLIO_LITE_DOCS_FREE',__('https://demo.misbahwp.com/docs/creative-portfolio-free-docs/','creative-portfolio-lite'));
}
if ( ! defined( 'CREATIVE_PORTFOLIO_LITE_DOCS_PRO' ) ) {
define('CREATIVE_PORTFOLIO_LITE_DOCS_PRO',__('https://demo.misbahwp.com/docs/creative-portfolio-pro-docs','creative-portfolio-lite'));
}
if ( ! defined( 'CREATIVE_PORTFOLIO_LITE_BUY_NOW' ) ) {
define('CREATIVE_PORTFOLIO_LITE_BUY_NOW',__('https://www.misbahwp.com/products/creative-portfolio-WordPress-theme','creative-portfolio-lite'));
}
if ( ! defined( 'CREATIVE_PORTFOLIO_LITE_SUPPORT_FREE' ) ) {
define('CREATIVE_PORTFOLIO_LITE_SUPPORT_FREE',__('https://WordPress.org/support/theme/creative-portfolio-lite','creative-portfolio-lite'));
}
if ( ! defined( 'CREATIVE_PORTFOLIO_LITE_REVIEW_FREE' ) ) {
define('CREATIVE_PORTFOLIO_LITE_REVIEW_FREE',__('https://WordPress.org/support/theme/creative-portfolio-lite/reviews/#new-post','creative-portfolio-lite'));
}
if ( ! defined( 'CREATIVE_PORTFOLIO_LITE_DEMO_PRO' ) ) {
define('CREATIVE_PORTFOLIO_LITE_DEMO_PRO',__('https://demo.misbahwp.com/creative-portfolio/','creative-portfolio-lite'));
}
if( ! defined( 'CREATIVE_PORTFOLIO_LITE_THEME_BUNDLE' ) ) {
define('CREATIVE_PORTFOLIO_LITE_THEME_BUNDLE',__('https://www.misbahwp.com/products/WordPress-bundle','creative-portfolio-lite'));
}

function creative_portfolio_lite_test_guide() { 
	$theme = wp_get_theme();?>
	<div class="wrap" id="main-page">
		<div class="demo-import-box">
			<h4><?php echo esc_html__('Import homepage demo in just one click.','creative-portfolio-lite'); ?></h4>
			<p><?php echo esc_html__('Get started with the WordPress theme installation','creative-portfolio-lite'); ?></p>
			<a class="button button-primary import" href="themes.php?page=creativeportfoliolite-wizard"><?php echo esc_html__('ONE CLICK DEMO IMPORTER','creative-portfolio-lite'); ?></a>
		</div>
		<div id="lefty">
            <div id="lefty-up">
                <div id="description">
                    <h3><?php esc_html_e('Welcome! Thank you for choosing ','creative-portfolio-lite'); ?><?php echo esc_html( $theme ); ?>  <span><?php esc_html_e('Version: ', 'creative-portfolio-lite'); ?><?php echo esc_html($theme['Version']);?></span></h3>
                    <div id="description-insidee">
                        <?php
                            $theme = wp_get_theme();
                            echo wp_kses_post( apply_filters( 'misbah_theme_description', esc_html( $theme->get( 'Description' ) ) ) );
                        ?>
                    </div>
                    <div id="admin_links">
                        <h3><?php esc_html_e('Unlock More Features With Premium Version','creative-portfolio-lite'); ?></h3>
                        <div id="admin_inside_links">
                            <a href="<?php echo esc_url( CREATIVE_PORTFOLIO_LITE_BUY_NOW ); ?>" target="_blank" class="blue-button-1"><?php esc_html_e( 'Get Premium', 'creative-portfolio-lite' ) ?></a>
                            <a href="<?php echo esc_url( CREATIVE_PORTFOLIO_LITE_DEMO_PRO ); ?>" id="customizer" target="_blank"><?php esc_html_e( 'Live Demo', 'creative-portfolio-lite' ); ?> </a>
                            <a class="blue-button-1" href="<?php echo esc_url( CREATIVE_PORTFOLIO_LITE_DOCS_PRO ); ?>" target="_blank" class="btn3"><?php esc_html_e( 'Pro Documentation', 'creative-portfolio-lite' ) ?></a>
                            <a class="blue-button-2" href="<?php echo esc_url( CREATIVE_PORTFOLIO_LITE_THEME_BUNDLE ); ?>" target="_blank" class="btn4"><?php esc_html_e( 'View All Themes', 'creative-portfolio-lite' ) ?></a>
                        </div>
                    </div>
                </div>
                <div id="theme-img">
					
                    <img class="img_responsive" style="width: 100%;" src="<?php echo esc_url( $theme->get_screenshot() ); ?>" />
                    <div id="img-btm-box">
                        <h3 class="bundle-box-title"><?php esc_html_e('Get This Premium Theme at Flat 20% OFF','creative-portfolio-lite'); ?></h3>
                        <div class="bundle-info">
                            <div class="bundle-left">
                                <p class="coupon-text"><?php esc_html_e('Use Coupon Code:','creative-portfolio-lite'); ?></p>
                                <p class="coupon-code"><?php esc_html_e('HEAT20','creative-portfolio-lite'); ?></p>
                            </div>
                            <div class="bundle-right">
                                <a class="white-button" href="<?php echo esc_url( CREATIVE_PORTFOLIO_LITE_BUY_NOW ); ?>" target="_blank"><?php esc_html_e( 'Buy Now $40', 'creative-portfolio-lite' ) ?><span><?php esc_html_e( '$60', 'creative-portfolio-lite' ) ?></span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="lefty-down">
                <div id="admin_links">
                    <h3><?php esc_html_e('Important Links','creative-portfolio-lite'); ?></h3>
                    <p id="description-insidee"><?php esc_html_e('Below are some Important Link, Customize your theme, Get Support, and If you are stuck somewhere get help with the documentation','creative-portfolio-lite'); ?></p>
                    <div id="admin_inside_links">
                        <a href="<?php echo esc_url( CREATIVE_PORTFOLIO_LITE_DOCS_FREE ); ?>" target="_blank" class="blue-button-1"><?php esc_html_e( 'Creative Portfolio Lite Documentation', 'creative-portfolio-lite' ) ?></a>
                        <a href="<?php echo esc_url( admin_url('customize.php') ); ?>" id="customizer" target="_blank"><?php esc_html_e( 'Customize Theme', 'creative-portfolio-lite' ); ?> </a>
                        <a class="blue-button-1" href="<?php echo esc_url( CREATIVE_PORTFOLIO_LITE_SUPPORT_FREE ); ?>" target="_blank" class="btn3"><?php esc_html_e( 'Get Support', 'creative-portfolio-lite' ) ?></a>
                        <a class="blue-button-2" href="<?php echo esc_url( CREATIVE_PORTFOLIO_LITE_REVIEW_FREE ); ?>" target="_blank" class="btn4"><?php esc_html_e( 'Review Theme', 'creative-portfolio-lite' ) ?></a>
                    </div>
                </div>
            </div>
		</div>

		<div id="righty">
			<div class="postboxx donate">
				<h3 class="hndle bundle"><?php esc_html_e( 'Get All Themes', 'creative-portfolio-lite' ); ?></h3>
				<div class="insidee theme-bundle">
					<img width="100%" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/bundle-image.png' ); ?>" alt="<?php esc_attr_e('logo', 'creative-portfolio-lite'); ?>">
					<p class="offer"><?php esc_html_e('Get 110+ Perfect WordPress Theme In A Single Package at just $89.','creative-portfolio-lite'); ?></p>
					<p class="coupon"><?php esc_html_e('Get Our Theme Pack of 110+ WordPress Themes At 20% Off ','creative-portfolio-lite'); ?><span><?php esc_html_e('"HEAT20"','creative-portfolio-lite'); ?></span></p>
				<div id="admin_pro_linkss">
					<a class="blue-button-1" href="<?php echo esc_url( CREATIVE_PORTFOLIO_LITE_THEME_BUNDLE ); ?>" target="_blank"><?php esc_html_e( 'Buy All Themes - $89', 'creative-portfolio-lite' ) ?></a>
				</div>
				<div class="d-table">
			    <ul class="d-column">
			      <li class="feature"><?php esc_html_e('Features','creative-portfolio-lite'); ?></li>
			      <li class="free"><?php esc_html_e('Pro','creative-portfolio-lite'); ?></li>
			      <li class="plus"><?php esc_html_e('Free','creative-portfolio-lite'); ?></li>
			    </ul>
			    <ul class="d-row">
			      <li class="points"><?php esc_html_e('24hrs Priority Support','creative-portfolio-lite'); ?></li>
			      <li class="right"><span class="dashicons dashicons-yes"></span></li>
			      <li class="wrong"><span class="dashicons dashicons-yes"></span></li>
			    </ul>
			    <ul class="d-row">
			      <li class="points"><?php esc_html_e('Advance Posttype','creative-portfolio-lite'); ?></li>
			      <li class="right"><span class="dashicons dashicons-yes"></span></li>
			      <li class="wrong"><span class="dashicons dashicons-no"></span></li>
			    </ul>
			    <ul class="d-row">
			      <li class="points"><?php esc_html_e('Section Reordering','creative-portfolio-lite'); ?></li>
			      <li class="right"><span class="dashicons dashicons-yes"></span></li>
			      <li class="wrong"><span class="dashicons dashicons-no"></span></li>
			    </ul>
			    <ul class="d-row">
			      <li class="points"><?php esc_html_e('Enable / Disable Option','creative-portfolio-lite'); ?></li>
			      <li class="right"><span class="dashicons dashicons-yes"></span></li>
			      <li class="wrong"><span class="dashicons dashicons-yes"></span></li>
			    </ul>
			    <ul class="d-row">
			      <li class="points"><?php esc_html_e('Multiple Sections','creative-portfolio-lite'); ?></li>
			      <li class="right"><span class="dashicons dashicons-yes"></span></li>
			      <li class="wrong"><span class="dashicons dashicons-no"></span></li>
			    </ul>
			    <ul class="d-row">
			      <li class="points"><?php esc_html_e('Advance Color Pallete','creative-portfolio-lite'); ?></li>
			      <li class="right"><span class="dashicons dashicons-yes"></span></li>
			      <li class="wrong"><span class="dashicons dashicons-no"></span></li>
			    </ul>
			    <ul class="d-row">
			      <li class="points"><?php esc_html_e('Advance Widgets','creative-portfolio-lite'); ?></li>
			      <li class="right"><span class="dashicons dashicons-yes"></span></li>
			      <li class="wrong"><span class="dashicons dashicons-yes"></span></li>
			    </ul>
			    <ul class="d-row">
			      <li class="points"><?php esc_html_e('Page Templates','creative-portfolio-lite'); ?></li>
			      <li class="right"><span class="dashicons dashicons-yes"></span></li>
			      <li class="wrong"><span class="dashicons dashicons-no"></span></li>
			    </ul>
			    <ul class="d-row">
			      <li class="points"><?php esc_html_e('Advance Typography','creative-portfolio-lite'); ?></li>
			      <li class="right"><span class="dashicons dashicons-yes"></span></li>
			      <li class="wrong"><span class="dashicons dashicons-no"></span></li>
			    </ul>
			    <ul class="d-row">
			      <li class="points"><?php esc_html_e('Section Background Image / Color ','creative-portfolio-lite'); ?></li>
			      <li class="right"><span class="dashicons dashicons-yes"></span></li>
			      <li class="wrong"><span class="dashicons dashicons-no"></span></li>
			    </ul>
	  		</div>
			</div>
		</div>
	</div>
<?php } ?>
