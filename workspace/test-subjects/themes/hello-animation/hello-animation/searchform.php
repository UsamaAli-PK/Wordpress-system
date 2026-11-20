<?php
/**
 * Search Form for Hello Animation theme.
 *
 * @package     Hello Animation
 * @author      Wealcoder
 * @copyright   Copyright (c) 2024, wealcoder
 * @link        https://www.brainstormforce.com
 * @since       Hello animation 1.0
 */

/**
 * The field id has to be unique per form, not per theme.
 *
 * It was hard-coded to `s`, and get_search_form() is called from more than one
 * place — the no-results block renders one and a sidebar search widget renders
 * another. Two elements sharing an id makes the second label point at the first
 * form's input, so clicking "Search" in the sidebar focused the field further up
 * the page.
 */
$hello_animation_search_id = wp_unique_id( 'hello-animation-search-' );
?>
<div class="default-search__again-form">
	<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="hello-animation-search-form">
		<label for="<?php echo esc_attr( $hello_animation_search_id ); ?>"><?php echo esc_html__( 'Search', 'hello-animation' ) ?></label>
		<div class="hello-anim-search d-flex">
			<input
				id="<?php echo esc_attr( $hello_animation_search_id ); ?>"
				name="s"
				type="search"
				value="<?php echo get_search_query(); ?>"
				placeholder="<?php echo esc_attr__( 'Type and press enter…', 'hello-animation' ); ?>" />
			<?php // Explicitly submit, and give the icon a name — it had none, so it read as just "button" to a screen reader. ?>
			<button type="submit" aria-label="<?php echo esc_attr__( 'Search', 'hello-animation' ); ?>">
				<i class="icon-wcf-search" aria-hidden="true"></i>
			</button>
		</div>
	</form>
</div>
