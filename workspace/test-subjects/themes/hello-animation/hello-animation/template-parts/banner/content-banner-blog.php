<?php 
  
   $banner_title    = get_bloginfo( 'name' );
   $show_breadcrumb = get_theme_mod('blog_banner_breadcrumb_enable',1);
   $show            = get_theme_mod('blog_banner_enable',1);
     
?>
 <!-- default banner start -->
<?php if($show): ?>
   <div class="<?php echo esc_attr( hello_animation_banner_class() ); ?>">
     <div class="container">
     <?php if($banner_title !=''): ?>  
       <h1 class="default-breadcrumb__title"><?php echo hello_animation_banner_title( $banner_title ); // Escaped word by word inside the helper. ?></h1>
     <?php endif; ?>
     <?php if($show_breadcrumb): ?>
        <?php hello_animation_get_breadcrumbs('<i class="icon-wcf-checvron-right"></i>'); ?>
     <?php endif; ?>
     </div>
   </div>
<?php endif; ?>
   <!-- default banner end -->
