<?php
/**
 * Deploy Travelin Homepage to WordPress
 */

$json_path = __DIR__ . '/homepage.json';
if (!file_exists($json_path)) {
    $json_path = '/var/www/html/homepage.json';
}
if (!file_exists($json_path)) {
    echo "Error: /tmp/homepage.json not found\n";
    exit(1);
}

$json_str = file_get_contents($json_path);

$page = get_page_by_path('home');
if (!$page) {
    $page_id = wp_insert_post([
        'post_title'   => 'Travel & LIV Collective',
        'post_name'    => 'home',
        'post_status'  => 'publish',
        'post_type'    => 'page',
        'post_content' => '<!-- Page built with Elementor -->',
    ]);
    echo "Created Home page with ID: {$page_id}\n";
} else {
    $page_id = $page->ID;
    echo "Found existing Home page with ID: {$page_id}\n";
}

update_post_meta($page_id, '_elementor_edit_mode', 'builder');
update_post_meta($page_id, '_elementor_template_type', 'wp-page');
update_post_meta($page_id, '_elementor_version', '4.4.1');
update_post_meta($page_id, '_wp_page_template', 'elementor_header_footer');
update_post_meta($page_id, '_elementor_data', wp_slash($json_str));

update_option('show_on_front', 'page');
update_option('page_on_front', $page_id);
update_option('blogname', 'Travel & LIV Collective');
update_option('blogdescription', 'Hosted Group Trips for Women & Solo Travelers');

if (class_exists('\Elementor\Plugin')) {
    \Elementor\Plugin::$instance->files_manager->clear_cache();
}

echo "SUCCESS: Front page set to ID {$page_id} and Elementor data loaded successfully.\n";
