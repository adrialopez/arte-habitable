<?php
/**
 * Theme helper functions
 */

/**
 * Get customizer image URL by setting name
 */
function ah_get_image_url($setting, $size = 'full') {
    $id = get_theme_mod($setting);
    if (!$id) return '';
    $img = wp_get_attachment_image_src($id, $size);
    return $img ? $img[0] : '';
}

/**
 * Get project gallery images
 */
function ah_get_project_gallery($post_id = null) {
    if (!$post_id) $post_id = get_the_ID();
    $gallery = get_post_meta($post_id, '_ah_gallery', true);
    if (!$gallery) return [];
    return array_filter(explode(',', $gallery));
}

/**
 * Get projects for front page
 */
function ah_get_projects($limit = -1) {
    return new WP_Query([
        'post_type'      => 'proyecto',
        'posts_per_page' => $limit,
        'meta_key'       => '_ah_order',
        'orderby'        => 'meta_value_num',
        'order'          => 'ASC',
        'post_status'    => 'publish',
    ]);
}

/**
 * Get team members page (uses a regular page with specific template)
 */
function ah_get_team_members() {
    return new WP_Query([
        'post_type'      => 'page',
        'meta_key'       => '_wp_page_template',
        'meta_value'     => 'templates/team-member.php',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ]);
}
