<?php
/*----------------------------------------------------
                Blog Settings
-----------------------------------------------------*/

/**
 * See app/options/header.php for what this array is and who reads it.
 *
 * The Kirki version registered `ha_blog_single_nav` and `ha_blog_readmore`
 * twice each, with identical arguments both times. An array cannot hold a
 * duplicate key, so the second copy is simply gone.
 */

return [
	'section' => [
		'id'       => 'ha_blog_section',
		'title'    => __( 'Blog Settings', 'hello-animation' ),
		'priority' => 10,
	],

	'fields' => [

		'ha_blog_sidebar' => [
			'type'    => 'select',
			'label'   => __( 'Select Sidebar', 'hello-animation' ),
			'default' => 'left-sidebar',
			'choices' => [
				'left-sidebar'   => __( 'Left', 'hello-animation' ),
				'center-sidebar' => __( 'Center', 'hello-animation' ),
				'right-sidebar'  => __( 'Right', 'hello-animation' ),
			],
		],

		'ha_blog_single_nav' => [
			'type'        => 'checkbox',
			'label'       => __( 'Blog Navigation', 'hello-animation' ),
			'description' => __( 'Disable or Enable blog details Navigation', 'hello-animation' ),
			'default'     => true,
		],

		'ha_blog_comment' => [
			'type'        => 'checkbox',
			'label'       => __( 'Blog Comment', 'hello-animation' ),
			'description' => __( 'Disable or Enable blog coment', 'hello-animation' ),
			'default'     => false,
		],

		'ha_blog_date' => [
			'type'        => 'checkbox',
			'label'       => __( 'Blog Date', 'hello-animation' ),
			'description' => __( 'Disable or Enable blog date', 'hello-animation' ),
			'default'     => true,
		],

		'ha_blog_category' => [
			'type'        => 'checkbox',
			'label'       => __( 'Blog Category', 'hello-animation' ),
			'description' => __( 'Disable or Enable blog categrory', 'hello-animation' ),
			'default'     => true,
		],

		'ha_blog_category_limit' => [
			'type'        => 'number',
			'label'       => __( 'Category Limit', 'hello-animation' ),
			'default'     => 2,
			'input_attrs' => [
				'min'  => 1,
				'max'  => 15,
				'step' => 1,
			],
		],

		'ha_blog_author' => [
			'type'        => 'checkbox',
			'label'       => __( 'Blog Author', 'hello-animation' ),
			'description' => __( 'Disable or Enable blog author', 'hello-animation' ),
			'default'     => true,
		],

		'ha_blog_author_image' => [
			'type'        => 'checkbox',
			'label'       => __( 'Blog Author Image', 'hello-animation' ),
			'description' => __( 'Disable or Enable blog author Image', 'hello-animation' ),
			'default'     => false,
		],

		'ha_blog_readmore' => [
			'type'        => 'checkbox',
			'label'       => __( 'Blog Readmore', 'hello-animation' ),
			'description' => __( 'Disable or Enable blog readmore', 'hello-animation' ),
			'default'     => true,
		],

		'ha_blog_search_heading' => [
			'type'    => 'text',
			'label'   => __( 'Search Heading', 'hello-animation' ),
			'default' => __( 'Search Page', 'hello-animation' ),
		],

		'ha_blog_post_nav' => [
			'type'        => 'checkbox',
			'label'       => __( 'Blog Pagination', 'hello-animation' ),
			'description' => __( 'Disable or Enable Blog Pagination', 'hello-animation' ),
			'default'     => true,
		],

		'ha_single_post_tags' => [
			'type'        => 'checkbox',
			'label'       => __( 'Blog Tags', 'hello-animation' ),
			'description' => __( 'Disable or Enable blog details tags in post footer', 'hello-animation' ),
			'default'     => true,
		],

		'ha_single_blog_author' => [
			'type'        => 'checkbox',
			'label'       => __( 'Blog Author', 'hello-animation' ),
			'description' => __( 'Disable or Enable blog details author in post footer', 'hello-animation' ),
			'default'     => true,
		],
	],
];
