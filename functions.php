<?php
/**
 * Arte Habitable Theme Functions
 */

define('AH_VERSION', '1.2.0');

// ── Theme Setup ─────────────────────────────
add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', [
        'height'      => 80,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
    add_theme_support('html5', ['search-form', 'comment-form', 'gallery', 'caption', 'style', 'script']);

    // Image sizes for projects
    add_image_size('ah-hero', 1920, 1080, true);
    add_image_size('ah-project-thumb', 800, 1000, true);
    add_image_size('ah-project-gallery', 1200, 900, true);
    add_image_size('ah-team', 600, 800, true);

    register_nav_menus([
        'primary' => __('Menú Principal', 'artehabitable'),
        'footer'  => __('Menú Footer', 'artehabitable'),
    ]);
});

// ── Enqueue Assets ──────────────────────────
add_action('wp_enqueue_scripts', function () {
    // Google Fonts
    wp_enqueue_style(
        'ah-google-fonts',
        'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&family=Inter:wght@300;400;500&display=swap',
        [],
        null
    );

    // Theme CSS
    wp_enqueue_style('ah-theme', get_template_directory_uri() . '/assets/css/theme.css', [], AH_VERSION);

    // Theme JS
    wp_enqueue_script('ah-main', get_template_directory_uri() . '/assets/js/main.js', [], AH_VERSION, true);

    // Pass data to JS
    wp_localize_script('ah-main', 'ahData', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('ah_nonce'),
    ]);
});

// ── Includes ────────────────────────────────
require_once get_template_directory() . '/inc/cpt-proyectos.php';
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/helpers.php';
require_once get_template_directory() . '/inc/seo.php';

// ── Remove unnecessary WP stuff ─────────────
add_action('wp_enqueue_scripts', function () {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('classic-theme-styles');
    wp_dequeue_style('global-styles');
}, 100);

remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');

// ── Allow SVG uploads ───────────────────────
add_filter('upload_mimes', function ($mimes) {
    $mimes['svg']  = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    return $mimes;
});

add_filter('wp_check_filetype_and_ext', function ($data, $file, $filename, $mimes) {
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if ($ext === 'svg') {
        $data['type'] = 'image/svg+xml';
        $data['ext']  = 'svg';
    }
    return $data;
}, 10, 4);

// ── Fix anchor-only menu links ─────────────
// Converts #section links to full home URL + #section
add_filter('nav_menu_link_attributes', function ($atts) {
    if (!empty($atts['href']) && str_starts_with($atts['href'], '#')) {
        $atts['href'] = home_url('/') . $atts['href'];
    }
    return $atts;
}, 10);

// ── Team member "Cargo" meta box ───────────
add_action('add_meta_boxes_page', function ($post) {
    $equipo_parent = get_page_by_path('equipo');
    if ($equipo_parent && $post->post_parent == $equipo_parent->ID) {
        add_meta_box('ah_team_role', 'Cargo en el equipo', function ($post) {
            wp_nonce_field('ah_team_role', 'ah_team_role_nonce');
            $role = get_post_meta($post->ID, '_ah_team_role', true);
            echo '<input type="text" name="ah_team_role" value="' . esc_attr($role) . '" class="widefat" placeholder="Ej: Dirección creativa">';
        }, 'page', 'normal', 'high');
    }
});

add_action('save_post_page', function ($post_id) {
    if (!isset($_POST['ah_team_role_nonce']) || !wp_verify_nonce($_POST['ah_team_role_nonce'], 'ah_team_role')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (isset($_POST['ah_team_role'])) {
        update_post_meta($post_id, '_ah_team_role', sanitize_text_field($_POST['ah_team_role']));
    }
});
