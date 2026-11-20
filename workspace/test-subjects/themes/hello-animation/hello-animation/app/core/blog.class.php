<?php

namespace Hello_Animation\Core;

/**
 * Blogs.
 */
class Blog {
	/**
	 * register default hooks and actions for WordPress
	 * @return
	 */
	public function register() {

		add_filter( 'comment_form_defaults', [ $this, 'add_submit_button_attr_class' ] );
		add_filter( 'next_posts_link_attributes', [ $this, 'next_posts_link_attributes' ] );
		add_filter( 'previous_posts_link_attributes', [ $this, 'previous_posts_link_attributes' ] );
		add_filter( 'body_class', [ $this, 'body_class' ] );		

		if ( ! is_admin() ) {
			// An action, not a filter: pre_get_posts passes WP_Query by
			// reference and ignores anything returned, so the old add_filter()
			// was registering a callback whose return value went nowhere.
			add_action( 'pre_get_posts', [ $this, 'search_filter' ] );
		}

	
		add_action( 'comment_form_before_fields', [ $this, 'comment_form_before_fields' ] );
		add_action( 'comment_form_after_fields', [ $this, 'comment_form_after_fields' ] );
		add_filter( 'comment_form_submit_field', [ $this, 'comment_form_submit_field' ], 10, 2 );

	}


	function comment_form_submit_field( $bt, $args ) {
		return $bt;
	}

	function comment_form_after_fields() {
		echo '</div>';
	}

	function comment_form_before_fields() {
		echo '<div class="comment-form-fields-wrapper order-3">';
	}
	public function body_class( $classes ) {
		$custom_classes = array( 'joya-gl-blog', 'hello-animation-base' );

		// The motion switches (Customizer > Animations), as the classes the
		// stylesheets and script.js key off. Off by default; a filter per switch
		// lets a child theme or plugin decide for itself.
		if ( apply_filters( 'hello_animation/motion/menu', (bool) get_theme_mod( 'ha_anim_menu', 0 ) ) ) {
			$custom_classes[] = 'ha-motion-menu';
		}

		if ( apply_filters( 'hello_animation/motion/split', (bool) get_theme_mod( 'ha_anim_menu_split', 0 ) ) ) {
			$custom_classes[] = 'ha-motion-split';
		}

		if ( apply_filters( 'hello_animation/motion/content', (bool) get_theme_mod( 'ha_anim_content', 0 ) ) ) {
			$custom_classes[] = 'ha-motion-content';
		}

		$classes = array_merge( $classes, $custom_classes );

		return $classes;
	}

	public function next_posts_link_attributes( $attr ) {
		return 'class="page-link"';
	}

	public function previous_posts_link_attributes( $attr ) {
		return 'class="page-link"';
	}

	public function add_submit_button_attr_class( $arg ) {

		$arg['class_submit'] = 'submit btn-comment btn btn-primary';
		return $arg;
	}


	/**
	 * Restrict search results to posts.
	 *
	 * is_main_query() is what keeps this to the search page. Without it the
	 * callback rewrote every search WP_Query on the request — a plugin's
	 * "related posts" lookup, a widget, a REST search — and forced each one to
	 * post_type=post regardless of what it had asked for.
	 *
	 * @param \WP_Query $query
	 */
	function search_filter( $query ) {

		if ( $query->is_main_query() && $query->is_search() ) {
			$query->set( 'post_type', 'post' );
		}
	}

}




