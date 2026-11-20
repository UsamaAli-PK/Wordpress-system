<?php

namespace Hello_Animation\Core\Controls;

/**
 * A repeating list of rows, for the one setting that needs one.
 *
 * Core has no repeater, and the offcanvas gallery is a list of image + link
 * pairs — so this is the single custom control the theme carries. It is loaded
 * from Customizer::add_field() only when the customizer is actually being
 * built; nothing here is parsed on a front-end request.
 *
 * Rows are described by the `fields` argument (from the option file's spec), so
 * the markup is not hard-wired to image + link_url even though that is the only
 * shape in use today. A field typed `image` gets the media picker; anything
 * else gets a plain input.
 *
 * The value travels as JSON in one hidden input — that is what $this->link()
 * binds to, so the customizer sees a single scalar change like it does for any
 * other control. Customizer::sanitize_repeater() decodes it back to the array
 * the templates loop over.
 */
class Repeater_Control extends \WP_Customize_Control {

	public $type = 'hello-animation-repeater';

	/** @var array Row shape: key => [ type, label ]. */
	public $fields = [];

	public function enqueue() {
		wp_enqueue_media();

		wp_enqueue_style(
			'hello-animation-customizer',
			HELLO_ANIMATION_CSS . '/customizer.css',
			[],
			HELLO_ANIMATION_VERSION
		);

		wp_enqueue_script(
			'hello-animation-customizer-repeater',
			HELLO_ANIMATION_JS . '/customizer-repeater.js',
			[ 'jquery', 'jquery-ui-sortable' ],
			HELLO_ANIMATION_VERSION,
			true
		);

		wp_localize_script(
			'hello-animation-customizer-repeater',
			'helloAnimationRepeaterL10n',
			[
				// __() and not esc_html__(): wp_localize_script escapes for JS
				// itself, and pre-escaping here would show &amp; to the user.
				'frameTitle'  => __( 'Select image', 'hello-animation' ),
				'frameButton' => __( 'Use this image', 'hello-animation' ),
			]
		);
	}

	public function render_content() {
		$rows = $this->value();

		if ( is_string( $rows ) ) {
			$rows = json_decode( $rows, true );
		}

		if ( ! is_array( $rows ) ) {
			$rows = [];
		}
		?>
		<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>

		<?php if ( ! empty( $this->description ) ) : ?>
			<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
		<?php endif; ?>

		<div class="ha-repeater">

			<ul class="ha-repeater__rows">
				<?php foreach ( $rows as $row ) : ?>
					<?php $this->render_row( is_array( $row ) ? $row : [] ); ?>
				<?php endforeach; ?>
			</ul>

			<button type="button" class="button ha-repeater__add">
				<?php echo esc_html__( 'Add image', 'hello-animation' ); ?>
			</button>

			<?php // Cloned by the JS for every new row, so both paths render identically. ?>
			<script type="text/html" class="ha-repeater__tpl">
				<?php $this->render_row( [] ); ?>
			</script>

			<input type="hidden" class="ha-repeater__value" <?php $this->link(); ?>
				value="<?php echo esc_attr( wp_json_encode( $rows ) ); ?>" />
		</div>
		<?php
	}

	/**
	 * One row — also the empty-row template.
	 *
	 * @param array $row
	 */
	private function render_row( $row ) {
		?>
		<li class="ha-repeater__row">
			<span class="ha-repeater__handle dashicons dashicons-menu" aria-hidden="true"></span>

			<div class="ha-repeater__body">
				<?php foreach ( $this->fields as $key => $field ) : ?>
					<?php
					$value = isset( $row[ $key ] ) ? $row[ $key ] : '';
					$label = isset( $field['label'] ) ? $field['label'] : $key;
					$type  = isset( $field['type'] ) ? $field['type'] : 'text';
					?>

					<?php if ( 'image' === $type ) : ?>
						<div class="ha-repeater__field ha-repeater__field--image">
							<span class="ha-repeater__label"><?php echo esc_html( $label ); ?></span>

							<div class="ha-repeater__preview <?php echo $value ? '' : 'is-empty'; ?>">
								<?php if ( $value ) : ?>
									<img src="<?php echo esc_url( $value ); ?>" alt="" />
								<?php endif; ?>
							</div>

							<button type="button" class="button ha-repeater__pick">
								<?php echo esc_html__( 'Select image', 'hello-animation' ); ?>
							</button>

							<input type="hidden" data-key="<?php echo esc_attr( $key ); ?>"
								value="<?php echo esc_attr( $value ); ?>" />
						</div>
					<?php else : ?>
						<label class="ha-repeater__field">
							<span class="ha-repeater__label"><?php echo esc_html( $label ); ?></span>
							<input type="<?php echo esc_attr( 'url' === $type ? 'url' : 'text' ); ?>"
								data-key="<?php echo esc_attr( $key ); ?>"
								value="<?php echo esc_attr( $value ); ?>" />
						</label>
					<?php endif; ?>

				<?php endforeach; ?>
			</div>

			<button type="button" class="button-link ha-repeater__remove">
				<?php echo esc_html__( 'Remove', 'hello-animation' ); ?>
			</button>
		</li>
		<?php
	}
}
