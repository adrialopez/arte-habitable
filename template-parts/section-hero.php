<section class="hero" id="hero">
    <div class="hero__slider">
        <?php for ($i = 1; $i <= 5; $i++) :
            $url = ah_get_image_url("ah_hero_slide_{$i}", 'ah-hero');
            if ($url) : ?>
                <div class="hero__slide <?php echo $i === 1 ? 'active' : ''; ?>">
                    <img src="<?php echo esc_url($url); ?>" alt="" <?php echo $i === 1 ? 'fetchpriority="high"' : 'loading="lazy"'; ?>>
                </div>
            <?php endif;
        endfor; ?>
    </div>
    <div class="hero__overlay"></div>
    <div class="hero__content">
        <p class="hero__subtitle reveal"><?php echo esc_html(get_theme_mod('ah_hero_subtitle', 'Estudio de diseño de interiores')); ?></p>
        <?php
        $hero_logo_id = get_theme_mod('ah_hero_logo');
        if ($hero_logo_id) :
            $hero_logo_url = wp_get_attachment_url($hero_logo_id);
            $hero_logo_mime = get_post_mime_type($hero_logo_id);
        ?>
            <h1 class="hero__title hero__title--img reveal">
                <img src="<?php echo esc_url($hero_logo_url); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" class="hero__title-img">
            </h1>
        <?php else : ?>
            <h1 class="hero__title reveal"><?php echo nl2br(esc_html(get_theme_mod('ah_hero_title', 'Arte Habitable'))); ?></h1>
        <?php endif; ?>
        <p class="hero__text reveal"><?php echo esc_html(get_theme_mod('ah_hero_text', 'Transformamos espacios en experiencias que combinan belleza, funcionalidad y emoción.')); ?></p>
        <a href="#portafolio" class="hero__cta reveal">Descubre nuestros proyectos</a>
    </div>
    <div class="hero__scroll">
        <span>Scroll</span>
        <div class="hero__scroll-line"></div>
    </div>
</section>
