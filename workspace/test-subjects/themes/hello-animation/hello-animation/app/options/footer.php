<?php
/*----------------------------------------------------
                Footer Settings
-----------------------------------------------------*/

/**
 * See app/options/header.php for what this array is and who reads it.
 */

return [
	'section' => [
		'id'       => 'ha_footer_section',
		'title'    => __( 'Footer Settings', 'hello-animation' ),
		'priority' => 10,
	],

	'fields' => [

		/**
		 * The braces in the description are not decoration: footer.php swaps
		 * `{` and `}` for opening and closing <span> tags before printing, so
		 * they are how a word gets the accent colour. Undocumented until now.
		 */
		'ha_copyright_text' => [
			'type'        => 'textarea',
			'label'       => __( 'Copyright Text', 'hello-animation' ),
			'description' => __( 'Wrap a word in { } to highlight it, e.g. "reserved by {Wealcoder}".', 'hello-animation' ),
			'default'     => __( '© 2026 - 2027 | All rights reserved by Wealcoder', 'hello-animation' ),
		],
	],
];
