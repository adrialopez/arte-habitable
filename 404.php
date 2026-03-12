<?php get_header(); ?>

<section class="error-404">
    <?php
    $bg_url = ah_get_image_url('ah_contacto_bg', 'ah-hero');
    if ($bg_url) : ?>
        <div class="error-404__bg" style="background-image: url('<?php echo esc_url($bg_url); ?>')"></div>
    <?php endif; ?>
    <div class="error-404__overlay"></div>

    <div class="error-404__content">
        <div class="error-404__deco">
            <span class="error-404__line"></span>
            <span class="error-404__label">Página no encontrada</span>
            <span class="error-404__line"></span>
        </div>

        <h1 class="error-404__title">
            <span class="error-404__num">4</span>
            <span class="error-404__icon">
                <svg viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="60" cy="60" r="56" stroke="currentColor" stroke-width="0.75"/>
                    <path d="M60 20 L60 40 M60 80 L60 100 M20 60 L40 60 M80 60 L100 60" stroke="currentColor" stroke-width="0.75"/>
                    <circle cx="60" cy="60" r="28" stroke="currentColor" stroke-width="0.5" stroke-dasharray="4 6"/>
                    <circle cx="60" cy="60" r="4" fill="currentColor" opacity="0.4"/>
                </svg>
            </span>
            <span class="error-404__num">4</span>
        </h1>

        <p class="error-404__text">El espacio que buscas no existe.<br>Quizás ha sido rediseñado.</p>

        <div class="error-404__actions">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="error-404__btn">
                <span>Volver al inicio</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
            <a href="<?php echo esc_url(home_url('/#portafolio')); ?>" class="error-404__link">Ver proyectos</a>
        </div>
    </div>

    <div class="error-404__footer">
        <?php if (has_custom_logo()) :
            $logo_id  = get_theme_mod('custom_logo');
            $logo_url = wp_get_attachment_url($logo_id);
        ?>
            <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" class="error-404__logo">
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
