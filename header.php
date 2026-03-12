<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<nav class="nav" id="nav">
    <a href="<?php echo esc_url(home_url('/')); ?>" class="nav__logo">
        <?php if (has_custom_logo()) :
            $logo_id  = get_theme_mod('custom_logo');
            $logo_url = wp_get_attachment_url($logo_id);
            $mime     = get_post_mime_type($logo_id);
            if ($mime === 'image/svg+xml') : ?>
                <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" class="nav__logo-img">
            <?php else :
                echo wp_get_attachment_image($logo_id, 'medium', false, ['class' => 'nav__logo-img', 'alt' => get_bloginfo('name')]);
            endif;
        else : ?>
            <span class="nav__logo-text"><?php echo esc_html(get_bloginfo('name')); ?></span>
        <?php endif; ?>
    </a>
    <button class="nav__toggle" id="navToggle" aria-label="<?php esc_attr_e('Abrir menú', 'artehabitable'); ?>">
        <span></span><span></span><span></span>
    </button>
    <?php
    wp_nav_menu([
        'theme_location'  => 'primary',
        'container'       => false,
        'menu_class'      => 'nav__menu',
        'menu_id'         => 'navMenu',
        'fallback_cb'     => 'ah_fallback_menu',
    ]);
    ?>
</nav>

<?php
function ah_fallback_menu() {
    $base = esc_url(home_url('/'));
    echo '<ul class="nav__menu" id="navMenu">';
    echo '<li><a href="' . $base . '#estudio" class="nav__link">Estudio</a></li>';
    echo '<li><a href="' . $base . '#servicios" class="nav__link">Servicios</a></li>';
    echo '<li><a href="' . $base . '#portafolio" class="nav__link">Portafolio</a></li>';
    echo '<li><a href="' . $base . '#equipo" class="nav__link">Equipo</a></li>';
    echo '<li><a href="' . $base . '#contacto" class="nav__link nav__link--cta">Contacto</a></li>';
    echo '</ul>';
}
?>
