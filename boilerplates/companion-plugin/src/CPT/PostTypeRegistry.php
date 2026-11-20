<?php
/**
 * Custom Post Types & Taxonomies Registry
 *
 * @package Agency\Companion\CPT
 */

declare(strict_types=1);

namespace Agency\Companion\CPT;

final class PostTypeRegistry
{
    public function init(): void
    {
        add_action('init', [$this, 'register_all']);
    }

    public function register_all(): void
    {
        $this->register_portfolio();
        $this->register_portfolio_category();
    }

    private function register_portfolio(): void
    {
        $labels = [
            'name'               => _x('Portfolios', 'post type general name', 'agency-companion'),
            'singular_name'      => _x('Portfolio', 'post type singular name', 'agency-companion'),
            'menu_name'          => _x('Portfolio', 'admin menu', 'agency-companion'),
            'add_new'            => _x('Add New', 'portfolio', 'agency-companion'),
            'add_new_item'       => __('Add New Portfolio Item', 'agency-companion'),
            'edit_item'          => __('Edit Portfolio Item', 'agency-companion'),
            'new_item'           => __('New Portfolio Item', 'agency-companion'),
            'view_item'          => __('View Portfolio Item', 'agency-companion'),
            'search_items'       => __('Search Portfolios', 'agency-companion'),
            'not_found'          => __('No portfolios found', 'agency-companion'),
            'not_found_in_trash' => __('No portfolios found in Trash', 'agency-companion'),
        ];

        $args = [
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => ['slug' => 'portfolio', 'with_front' => false],
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 20,
            'menu_icon'          => 'dashicons-portfolio',
            'supports'           => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'show_in_rest'       => true, // Essential for Block Editor & REST/MCP APIs
        ];

        register_post_type('agency_portfolio', $args);
    }

    private function register_portfolio_category(): void
    {
        $labels = [
            'name'              => _x('Portfolio Categories', 'taxonomy general name', 'agency-companion'),
            'singular_name'     => _x('Portfolio Category', 'taxonomy singular name', 'agency-companion'),
            'search_items'      => __('Search Categories', 'agency-companion'),
            'all_items'         => __('All Categories', 'agency-companion'),
            'parent_item'       => __('Parent Category', 'agency-companion'),
            'parent_item_colon' => __('Parent Category:', 'agency-companion'),
            'edit_item'         => __('Edit Category', 'agency-companion'),
            'update_item'       => __('Update Category', 'agency-companion'),
            'add_new_item'      => __('Add New Category', 'agency-companion'),
            'new_item_name'     => __('New Category Name', 'agency-companion'),
            'menu_name'         => __('Categories', 'agency-companion'),
        ];

        $args = [
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => ['slug' => 'portfolio-category'],
            'show_in_rest'      => true,
        ];

        register_taxonomy('agency_portfolio_cat', ['agency_portfolio'], $args);
    }
}
