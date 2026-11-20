<?php

if ( ! function_exists( 'hello_animation_post_thumbnail' ) ) :
	
	function hello_animation_post_thumbnail() {

		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) : ?>
			<?php if(has_post_thumbnail()) { ?>
				<div class="thumb">
			        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
				</div>
			<?php } ?>
		<?php else : ?>
			<div class="blog__details-top post-format-<?php echo esc_attr(get_post_format()); ?> <?php echo esc_attr(get_post_format() == 'video' || is_sticky() ? 'position-relative' : ''); ?> ">
						    <!-- Sticky Post ribbon -->
		        <?php 
			      if ( is_sticky() ) {
					echo '<sup class="meta-featured-post without-thumb"><i class="icon-wcf-sticky"></i></sup>';
				}               
                ?>
                  <!-- Archive Thumbnail Image -->
        		<?php
				the_post_thumbnail(
					'full',
					array(
						'alt' => the_title_attribute( array(
							'echo' => false,
						) ),
					)
				);
				?>
	        </div>
		<?php
		endif; 
	}
endif;

// display meta information for a specific post
// ----------------------------------------------------------------------------------------
if ( ! function_exists( 'hello_animation_breadcrumb_title' ) ) {
	/**
	 * A crumb's text, trimmed to the configured word limit.
	 *
	 * Split out because the trimming used to be written inline, four times, as
	 *
	 *     echo esc_html( $word ) != '' ? wp_trim_words( get_the_title(), $word ) : get_the_title();
	 *
	 * which reads like escaped output and is not. esc_html() there is applied to
	 * the *condition* — its result is compared to '' and thrown away — while the
	 * value actually printed, get_the_title() on the untrimmed branch, went out
	 * raw. Nothing here echoes; the caller escapes.
	 *
	 * @param string $text  Raw title.
	 * @param int    $words Word limit; 0 or less means do not trim.
	 *
	 * @return string
	 */
	function hello_animation_breadcrumb_title( $text, $words ) {
		$words = (int) $words;

		return $words > 0 ? wp_trim_words( $text, $words ) : $text;
	}
}

if ( ! function_exists( 'hello_animation_get_breadcrumbs' ) ) {

	/**
	 * The breadcrumb trail.
	 *
	 * Collected as a list first, printed once at the end. The previous version
	 * echoed as it went, in about twenty separate statements, and that is how it
	 * came to be emitting `<a href=` with no quotes around the URL, an
	 * unescaped category name inside the anchor, and an unescaped post title in
	 * the active crumb. Building the list separates deciding *what* the trail
	 * says from deciding how to escape it, and there is now exactly one place
	 * that does the second.
	 *
	 * @param string $separator Markup printed after each linked crumb. Theme-owned;
	 *                          both callers pass an icon element.
	 * @param string|int $word  Word limit for the current-page crumb. Overridden by
	 *                          the Breadcrumb Limit setting when that is set.
	 */
	function hello_animation_get_breadcrumbs( $separator = '/', $word = '' ) {

		if ( ! hello_animation_option( 'blog_banner_breadcrumb_enable', 1 ) ) {
			return;
		}

		$limit = (int) hello_animation_option( 'general_breadcrumb_limit' );

		if ( $limit > 0 ) {
			$word = $limit;
		}

		/*
		 * Each crumb is [ label, url ]. An empty url makes it the current page,
		 * which is rendered as plain text and marked .active — a crumb that
		 * links to the page you are already on is noise for everyone and a
		 * genuine nuisance with a screen reader.
		 */
		$crumbs = array();

		if ( ! is_home() ) {
			// home_url(), not get_home_url('/') — the first parameter of
			// get_home_url() is $blog_id, so the '/' was being passed as a site
			// id. Harmless on single site, wrong on multisite.
			$crumbs[] = array( esc_html__( 'Home', 'hello-animation' ), home_url( '/' ) );

			if ( is_singular( 'post' ) ) {

				$categories = get_the_category();

				if ( ! empty( $categories[0] ) ) {

					if ( ! empty( $categories[0]->parent ) ) {
						$parent = get_term( $categories[0]->parent, $categories[0]->taxonomy );

						if ( $parent && ! is_wp_error( $parent ) ) {
							$crumbs[] = array( $parent->name, get_category_link( $parent->term_id ) );
						}
					}

					$crumbs[] = array( $categories[0]->name, get_category_link( $categories[0]->term_id ) );
				}

				$crumbs[] = array( hello_animation_breadcrumb_title( get_the_title(), $word ), '' );

			} elseif ( is_category() ) {

				$crumbs[] = array( hello_animation_breadcrumb_title( single_cat_title( '', false ), $word ), '' );

			} elseif ( function_exists( 'is_shop' ) && is_shop() ) {

				// This branch was written out twice, identically. An `elseif`
				// cannot be reached twice, so the second copy was dead.
				$crumbs[] = array( esc_html__( 'Shop', 'hello-animation' ), '' );

			} elseif ( is_page() || is_single() ) {

				/*
				 * is_single() covers custom post types, which used to have a
				 * branch of their own. That branch built a $has_taxonomy it had
				 * just set to false, tested `is_array( false )`, and so could
				 * never run its body; the two variables it prepared first were
				 * read by nothing. All it ever did was print the title, which is
				 * what this does.
				 */
				$crumbs[] = array( hello_animation_breadcrumb_title( get_the_title(), $word ), '' );
			}
		}

		if ( is_tag() ) {

			$crumbs[] = array( single_tag_title( '', false ), '' );

		} elseif ( is_day() ) {

			/* translators: %s: date of the archive being viewed. */
			$crumbs[] = array( sprintf( esc_html__( 'Blogs for %s', 'hello-animation' ), get_the_date( 'F jS, Y' ) ), '' );

		} elseif ( is_month() ) {

			/* translators: %s: month and year of the archive being viewed. */
			$crumbs[] = array( sprintf( esc_html__( 'Blogs for %s', 'hello-animation' ), get_the_date( 'F, Y' ) ), '' );

		} elseif ( is_year() ) {

			/* translators: %s: year of the archive being viewed. */
			$crumbs[] = array( sprintf( esc_html__( 'Blogs for %s', 'hello-animation' ), get_the_date( 'Y' ) ), '' );

		} elseif ( is_author() ) {

			$crumbs[] = array( esc_html__( 'Author Blogs', 'hello-animation' ), '' );

		// get_query_var() rather than $_GET: pretty permalinks put the page
		// number in the path, so $_GET['paged'] was empty on every site not
		// using plain permalinks and this branch simply never ran there.
		} elseif ( get_query_var( 'paged' ) ) {

			$crumbs[] = array( esc_html__( 'Blogs', 'hello-animation' ), '' );

		} elseif ( is_search() ) {

			$crumbs[] = array( esc_html__( 'Search Result', 'hello-animation' ), '' );

		} elseif ( is_404() ) {

			/*
			 * There was an override here that read a '404_banner_page_title'
			 * out of an 'opt-tabbed-banner' setting. Nothing has ever
			 * registered that key, so the lookup returned an empty string, the
			 * isset() never passed, and the title below was the only outcome
			 * this branch could reach.
			 */
			$crumbs[] = array( esc_html__( '404 Error', 'hello-animation' ), '' );
		}

		if ( ! $crumbs ) {
			return;
		}

		echo '<ul class="default-breadcrumb__list">';

		foreach ( $crumbs as $crumb ) {
			list( $label, $url ) = $crumb;

			if ( '' === $url ) {
				printf( '<li class="active">%s</li>', esc_html( $label ) );
				continue;
			}

			printf(
				'<li><a href="%1$s">%2$s%3$s</a></li>',
				esc_url( $url ),
				esc_html( $label ),
				// Theme-owned markup, not user input — both callers pass an
				// icon element. Still filtered, because a child theme can call
				// this too.
				wp_kses_post( $separator )
			);
		}

		echo '</ul>';
	}
}

// wp_body_open() backword compatibility
if ( ! function_exists( 'wp_body_open' ) ) {	
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}

if(!function_exists('hello_animation_has_post_tags')) {
	function hello_animation_has_post_tags(){
	
		$single_post_tags = hello_animation_option('ha_single_post_tags',1);
		
	 	if( ! $single_post_tags ){
		   return;
		}
		
	 	$tag_list = get_the_tags();
	 	
		if(!$tag_list){
		   return [];
		}
		
		return $tag_list;
	}
}




