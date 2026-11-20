<?php


/*-----------------------------
	BANNER CLASSES
------------------------------*/
if ( ! function_exists( 'hello_animation_banner_class' ) ) :

	/**
	 * Class list for the blog / search banner.
	 *
	 * `is-default` marks a banner nobody has customised: neither a background
	 * colour nor a background image is set in the Customizer. Only that banner
	 * gets the theme's own gradient (see .default-breadcrumb__area.is-default
	 * in _blog.scss). Choosing a colour or an image removes the class, so the
	 * banner a site owner built keeps looking exactly the way they built it.
	 *
	 * `has-title-anim` switches the title entrance on; it is the same switch
	 * hello_animation_banner_title() reads, so the markup and the CSS agree.
	 *
	 * @return string
	 */
	function hello_animation_banner_class() {
		$classes = 'default-breadcrumb__area pb-150 pt-150';

		if ( '' === (string) get_theme_mod( 'blog_banner_bg_color', '' ) && '' === (string) get_theme_mod( 'blog_banner_bg_image', '' ) ) {
			$classes .= ' is-default';
		}

		if ( hello_animation_banner_title_animated() ) {
			$classes .= ' has-title-anim';
		}

		return $classes;
	}

endif;

if ( ! function_exists( 'hello_animation_banner_title_animated' ) ) :

	/**
	 * Is the banner title entrance on? The Customizer switch, and a filter for
	 * code that wants it off everywhere (a page builder that animates it itself,
	 * a child theme).
	 *
	 * @return bool
	 */
	function hello_animation_banner_title_animated() {
		return (bool) apply_filters( 'hello_animation/banner/animate_title', (bool) get_theme_mod( 'blog_banner_title_animation', 0 ) );
	}

endif;

if ( ! function_exists( 'hello_animation_banner_title' ) ) :

	/**
	 * The banner title as HTML, ready to echo.
	 *
	 * With the animation on, each word gets its own wrapper so CSS can raise the
	 * words into place one after another. Words, not letters: splitting letters
	 * breaks the joined forms of Arabic and other cursive scripts, and the words
	 * stay separated by real spaces, so the heading still reads and indexes as
	 * the plain title. Every piece of text is escaped here; the only markup added
	 * is the two class-only spans.
	 *
	 * @param string $title Unescaped title text.
	 *
	 * @return string
	 */
	function hello_animation_banner_title( $title ) {
		$title = (string) $title;

		if ( ! hello_animation_banner_title_animated() ) {
			return esc_html( $title );
		}

		$words = preg_split( '/\s+/u', trim( $title ), -1, PREG_SPLIT_NO_EMPTY );

		if ( ! $words ) {
			return esc_html( $title );
		}

		$out = array();

		foreach ( $words as $word ) {
			$out[] = '<span class="ha-bt-word"><span class="ha-bt-inner">' . esc_html( $word ) . '</span></span>';
		}

		return implode( ' ', $out );
	}

endif;


/*-----------------------------
	Info RANDOM SINGLE CATEGORY
------------------------------*/
if ( !function_exists( 'hello_animation_get_random_category' ) ): 

	function hello_animation_get_random_category(){

		$blog_cat_show   = get_theme_mod( 'ha_blog_category' , 1 );
		$limit          = get_theme_mod( 'ha_blog_category_limit' , 0 );
		$category_html = '';
		if( ! $blog_cat_show ){
          return;
		}
      $counter = 0;
		if ( 'post' === get_post_type() ) {
			// Once, not twice. This runs for every post in the loop, and the
			// second call was only ever asking the same question again.
			$category = get_the_category();

			if( ! $category ){
               return false;
			}

			foreach( $category as $value ):
				 ++$counter;
				 $category_html .= '<a 
							class="jpost-cat" 
							href="'. esc_url(get_category_link($value->term_id) ) .'"
							>'. 
							esc_html(get_cat_name($value->term_id)).
						'</a>';
				if ($limit == $counter) {
					break;
				}	
					
			endforeach;  
			
		}
		
		return $category_html;
	}

endif;

/*
 * hello_animation_post_meta_2() used to sit here — a near-copy of
 * hello_animation_post_meta() below, differing only in reading the orphan
 * blog_date / blog_comment keys that no settings framework ever registered
 * instead of the ha_blog_* ones that exist. Nothing in the theme, its templates
 * or the bundled plugins ever called it.
 */

/*------------------------------------------------------
   DISPLAY META INFORMATION FOR A SPECIFIC POST
-------------------------------------------------------*/
if ( ! function_exists( 'hello_animation_post_meta' ) ) :
   // post and post meta
  function hello_animation_post_meta() {
  
      $post_meta = [];
      $category = hello_animation_get_random_category();

      /*
       * Every entry pushed into $post_meta below is escaped as it is built, so
       * the list is assembled ready to print and goes out as it is.
       *
       * It used to be run through hello_animation_kses() at the end instead,
       * which cost more than it bought: get_avatar() returns an <img> carrying
       * srcset, sizes, loading and decoding, and that filter allows an img only
       * alt, class, height, src and width — so the retina sources and the
       * lazy-loading hint core had just worked out were quietly stripped off
       * every author avatar on the site.
       */
      if( get_theme_mod('ha_blog_author',0) ):
        $_posts_url = esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );
        $get_author = esc_html( get_the_author() );
         if( get_theme_mod('ha_blog_author_image',0) ):
            // Core's own markup, already escaped by get_avatar().
            $avatar = get_avatar( get_the_author_meta( 'ID' ), 55 );
            $post_meta[] = "<a href='{$_posts_url}'>{$avatar} {$get_author}</a>";
         else:
            $post_meta[] = esc_html__('by','hello-animation')."<strong><a href='{$_posts_url}'>&nbsp;{$get_author}</a></strong>";
         endif;
      endif;

      if ( get_post_type() === 'post' && get_theme_mod('ha_blog_date','1') ) {
         $post_meta[] = esc_html( get_the_date( get_option( 'date_format' ) ) );
      }

      if($category){
         $post_meta[] = $category;
      }

      if( get_theme_mod('ha_blog_comment',0) ){
         // _n(), not `> 1 ? plural : singular` — that read "0 comment" for an
         // empty post, and two hard-coded branches cannot express the plural
         // rules of most languages anyway.
         $comments_number = (int) get_comments_number();
         $post_meta[]     = sprintf(
            /* translators: %s: number of comments on the post. */
            esc_html( _n( '%s comment', '%s comments', $comments_number, 'hello-animation' ) ),
            esc_html( number_format_i18n( $comments_number ) )
         );
      }

      if(is_array($post_meta) && !count($post_meta)){
        return;
      }
      $meta_cls = is_single() ? 'mb-30 d-block' : '';
   ?>
      <i class="default-blog__item-meta <?php echo esc_attr($meta_cls); ?>">
         <?php
            // Escaped piece by piece above; see the note where the list starts.
            echo implode( '<span></span>', $post_meta ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
         ?>
      </i>
   <?php }
 endif;  

if ( !function_exists('hello_animation_link_pages') ):

   function hello_animation_link_pages() {

      $args = array(
         'before'			    => '<div class="page-links"><span class="page-link-text">' . esc_html__( 'More pages: ', 'hello-animation' ) . '</span>',
         'after'				 => '</div>',
         'link_before'		 => '<span class="page-link">',
         'link_after'		 => '</span>',
         'next_or_number'	 => 'number',
         'separator'			 => '  ',
         'nextpagelink'		 => esc_html__( 'Next ', 'hello-animation' ) . '<i class="icon-wcf-angle-right"></i>',
         'previouspagelink' => '<i class="icon-wcf-angle-left"></i>' . esc_html__( ' Previous', 'hello-animation' ),
      );
      
      wp_link_pages( $args );
   }

endif;

/*----------------------------------------
   CUSTOM COMMENNS WALKER
-------------------------------------------*/
if ( !function_exists('hello_animation_comment_style') ):

   function hello_animation_comment_style( $comment, $args, $depth ) {
      if ( 'div' === $args[ 'style' ] ) {
         $tag		 = 'div';
         $add_below	 = 'comment';
      } else {
         $tag		 = 'li ';
         $add_below	 = 'div-comment';
      }
      ?>
     
      <<?php
      echo hello_animation_kses( $tag );
      comment_class( empty( $args[ 'has_children' ] ) ? 'no-reply' : 'parent has-reply'  );
      ?> id="comment-<?php comment_ID() ?>"><?php if ( 'div' != $args[ 'style' ] ) { ?>
         <div id="div-comment-<?php comment_ID() ?>" class="comment-body"><?php }
      ?>	
        
         <div class="default-details-comment-wrapper mb-50">
            <div class="default-details-comment-thumb">
               <?php
                  if ( $args[ 'avatar_size' ] != 0 ) {
                     echo get_avatar( $comment, $args[ 'avatar_size' ], '', '', array( 'class' => 'comment-avatar float-left' ) );
                  }
               ?>
            </div>
            <div class="default-details-comment-meta">
              <h3 class="default-details-comment-name">
                  <?php
                     echo get_comment_author_link();
                  ?>
              </h3>
              <p class="default-details-comment-date">
                 <?php
                    echo get_comment_date() .'<span></span>' . get_comment_time();
                  ?>
              </p>
              <div class="builder-comment-text"><?php comment_text(); ?></div>
              <?php
                  comment_reply_link(
                  array_merge(
                  $args, array(
                     'add_below'	 => $add_below,
                     'depth'		 => $depth,
                     'max_depth'	 => $args[ 'max_depth' ]
                  ) ) );
               ?>
               <?php if ( $comment->comment_approved == '0' ) { ?>
               <p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'hello-animation' ); ?></p><br/><?php }
               ?>
            </div>
           
         </div>
         <?php if ( 'div' != $args[ 'style' ] ) : ?>
         </div><?php
      endif;
   }
endif;

/*---------------------------------------
   EXCERPT CUSTOM WORD COUNT
-----------------------------------------*/

/**
 * The trimmed excerpt, returned rather than printed.
 *
 * Split out of hello_animation_excerpt() so that a caller wanting to escape the
 * text can hold it first — passing the echoing version to esc_attr() runs it
 * while the attribute is half-written and escapes nothing at all.
 *
 * Its original caller was a description meta tag the theme printed on single
 * posts. That has gone: describing a page for search engines is an SEO plugin's
 * job, and a theme printing its own meant any site running one carried two
 * <meta name="description"> tags with different text. This function stays
 * because hello_animation_excerpt() is built on it and the templates use that.
 *
 * $words used to be overridden by a 'blog_excerpt_word' setting, but no options
 * framework this theme has shipped ever registered that key, so the lookup
 * always handed back the argument it was given as a fallback.
 *
 * @param int    $words Number of words to keep.
 * @param string $more  Appended when the text is trimmed.
 *
 * @return string
 */
if ( ! function_exists( 'hello_animation_get_excerpt' ) ) :
   function hello_animation_get_excerpt( $words = 40, $more = '' ) {

      if ( 'button' === $more ) {
         $more = '<a class="btn btn-primary">' . esc_html__( 'read more', 'hello-animation' ) . '</a>';
      }

      return wp_trim_words( get_the_excerpt(), $words, $more );
   }
endif;

function hello_animation_excerpt( $words = 40, $more = '' ) {
   echo wpautop( wp_kses_post( hello_animation_get_excerpt( $words, $more ) ) );
}

/*--------------------------------------
   SINGLE POST NAVIGATION
---------------------------------------*/
if ( !function_exists('hello_animation_post_nav') ):

// display navigation to the next/previous set of posts
// ----------------------------------------------------------------------------------------
function hello_animation_post_nav() {
   // Don't print empty markup if there's nowhere to navigate.
  
      if( !get_theme_mod('ha_blog_single_nav',1) ){
         return;
      }

      $next_post	 = get_next_post();
      $pre_post	 = get_previous_post();
      if ( !$next_post && !$pre_post ) {
         return;
      }
   ?>
      <nav class="joya--post-navigation clearfix mb-140">
         <div class="post-previous">
            <?php if ( !empty( $pre_post ) ): ?>
               <a href="<?php echo esc_url( get_the_permalink( $pre_post->ID ) ); ?>">
                  <h3><?php echo esc_html( get_the_title( $pre_post->ID ) ); ?></h3>
                  <span><i class="icon-wcf-angle-left"></i><?php esc_html_e( 'Previous post', 'hello-animation' ) ?></span>
               </a>
            <?php endif; ?>
         </div>
         <div class="post-next">
            <?php if ( !empty( $next_post ) ): ?>
               <a href="<?php echo esc_url( get_the_permalink( $next_post->ID ) ); ?>">
                  <h3><?php echo esc_html( get_the_title( $next_post->ID ) ); ?></h3>
   
                  <span><?php esc_html_e( 'Next post', 'hello-animation' ) ?> <i class="icon-wcf-angle-right"></i></span>
               </a>
            <?php endif; ?>
         </div>
      </nav>
   <?php }
 endif;



