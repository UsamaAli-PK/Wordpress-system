

<article <?php post_class('default-blog__item-single'); ?>>    
      <?php if(has_post_thumbnail()): ?>
        <?php
        /*
         * the_post_thumbnail(), not a hand-written <img> around
         * get_the_post_thumbnail_url(). The markup core generates carries
         * srcset, sizes, loading and decoding; the hand-written one carried
         * none of them, so every visitor on every device downloaded the full
         * 1200x780 crop for a card, eagerly, however far down the archive it sat.
         */
        ?>
        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'post-thumbnail', array( 'alt' => the_title_attribute( array( 'echo' => false ) ) ) ); ?></a>
      <?php endif; ?>
      <div class="default-blog__content">
        <?php hello_animation_post_meta(); ?>
          <h2 class="default-blog__item-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
          <div class="cf_text">
          <?php hello_animation_excerpt( 40, null ); ?>
          </div>
          <?php if(get_theme_mod('ha_blog_readmore',1)): ?>
            <div class="cf_btn">
              <a href="<?php the_permalink(); ?>" class="wc-btn-underline"><?php echo esc_html__('Readmore', 'hello-animation'); ?><i class="icon-wcf-arrow-right"></i></a>
            </div>
          <?php endif; ?>
      </div>
  </article>
